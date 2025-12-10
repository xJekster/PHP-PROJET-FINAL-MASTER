<?php
namespace App\Controller;
use App\Core\Render;
require_once __DIR__ . '/../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Core\Database;
use \App\Model\User;



class Auth
{
    private $db;

    public function __construct()
    {
        
        $this->db = Database::getInstance()->getConnection();
    }

    public function login(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $errors[] = "Erreur de sécurité. Veuillez réessayer.";
            } else {

                $email = trim($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';

                if (empty($email) || empty($password)) {
                    $errors[] = "Veuillez remplir tous les champs.";
                }

                if (empty($errors)) {

                    $userModel = new User();
                    $user = $userModel->getUserByEmail($email);

                    if (!$user || !password_verify($password, $user['password'])) {
                        $errors[] = "Identifiants incorrects.";
                    } elseif (!$user['is_active']) {
                        $errors[] = "Votre compte n'a pas encore été validé.";
                    } else {

                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['role'] = $user['role'] ?? 'user';

                        if (!headers_sent()) {
                            session_regenerate_id(true);
                        }

                        unset($_SESSION['csrf_token']);

                        $render = new Render("dashboard", "frontoffice", [
                            "message" => "Bienvenue, vous êtes maintenant connecté."
                        ]);
                        $render->render();
                        return;
                    }
                }
            }
        }

        $render = new Render("login", "frontoffice", [
            'csrf_token' => $_SESSION['csrf_token'],
            'errors'     => $errors
        ]);

        $render->render();
    }



    public function register(): void
    {
        $errors = [];
        $success = null;
        $old = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $firstname = trim($_POST['firstname'] ?? '');
            $lastname = trim($_POST['lastname'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $password_confirm = $_POST['password_confirm'] ?? '';

            $old = [
                'firstname' => $firstname,
                'lastname'  => $lastname,
                'email'     => $email,
            ];

            if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($password_confirm)) {
                $errors[] = "Tous les champs sont obligatoires.";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'adresse email n'est pas valide.";
            }

            if (strlen($password) < 8) {
                $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
            }

            if (!preg_match('/[A-Z]/', $password) 
                || !preg_match('/[a-z]/', $password) 
                || !preg_match('/[0-9]/', $password) 
                || !preg_match('/[^a-zA-Z0-9]/', $password)) {
                $errors[] = "Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial.";
            }

            if ($password !== $password_confirm) {
                $errors[] = "Les mots de passe ne correspondent pas.";
            }

            $userModel = new \App\Model\User();

            if (empty($errors) && $userModel->emailExists($email)) {
                $errors[] = "Cette adresse email est déjà utilisée.";
            }

            if (empty($errors)) {
                try {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $token = bin2hex(random_bytes(32));

                    if ($userModel->register($firstname, $lastname, $email, $hashed_password, $token)) {

                        $this->sendVerificationEmail($email, $token);

                        $success = "Inscription réussie ! Un email de confirmation vous a été envoyé.";
                        $old = [];
                    } else {
                        $errors[] = "Une erreur est survenue pendant l'inscription.";
                    }

                } catch (\Exception $e) {
                    $errors[] = "Erreur interne : " . $e->getMessage();
                }
            }
        }

        $render = new Render("register", "frontoffice", [
            'errors' => $errors,
            'success' => $success,
            'old'     => $old
        ]);

        $render->render();
    }

    public function forgot(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $errors = [];
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $errors[] = "Erreur CSRF, veuillez réessayer.";
            } else {

                $email = trim($_POST['email'] ?? '');
                $userModel = new User();

                if ($userModel->emailExists($email)) {
                    $token = bin2hex(random_bytes(32));
                    $userModel->saveResetToken($email, $token);

                    $this->sendResetPasswordEmail($email, $token);

                    $success = "Un email de réinitialisation vous a été envoyé.";
                } else {
                    $success = "Un email de réinitialisation vous a été envoyé.";
                }
            }
        }

        $render = new Render("forgot", "frontoffice", [
            'csrf_token' => $_SESSION['csrf_token'],
            'errors'     => $errors,
            'success'    => $success
        ]);

        $render->render();
    }


    private function sendVerificationEmail(string $recipientEmail, string $token): void
    {
        

        $mail = new PHPMailer(true);

        
        $mail->isSMTP();
        $mail->Host = 'mailpit'; 
        $mail->Port = 1025;
        $mail->SMTPAuth = false; 
        $mail->SMTPSecure = false;
        $mail->CharSet = 'UTF-8';


        $mail->setFrom('no-reply@mail.com', 'Mailer');
        $mail->addAddress($recipientEmail);
        $mail->isHTML(true);
        $mail->Subject = 'Confirmation de votre inscription';

        
        $verificationLink = "http://localhost:8080/verify?token=" . $token;

        $mail->Body = "
            <h1>Bienvenue !</h1>
            <p>Merci de vous être inscrit. Veuillez cliquer sur le lien ci-dessous pour confirmer votre adresse email :</p>
            <p><a href='{$verificationLink}'>Confirmer mon compte</a></p>
            <p>Si vous n'avez pas demandé cette inscription, veuillez ignorer cet email.</p>
        ";
        $mail->AltBody = "Merci de vous être inscrit. Veuillez copier et coller le lien suivant dans votre navigateur pour confirmer votre adresse email : " . $verificationLink;

        $mail->send();
    }

    private function sendResetPasswordEmail(string $email, string $token): void
    {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'mailpit';
        $mail->Port = 1025;
        $mail->SMTPAuth = false;
        $mail->CharSet = "UTF-8";

        $mail->setFrom("no-reply@mail.com", "Support");
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = "Réinitialisation de mot de passe";

        $link = "http://localhost:8080/reset?token=" . $token;

        $mail->Body = "
            <h2>Réinitialisation</h2>
            <p>Cliquez ici pour changer votre mot de passe :</p>
            <a href='{$link}'>Réinitialiser mon mot de passe</a>
        ";

        $mail->send();
    }


    public function verify(): void
    {
        $token = $_GET['token'] ?? '';
        $message = "";

        if (empty($token)) {
            $message = "Token de vérification manquant.";
        } else {
            try {
                $stmt = $this->db->prepare("SELECT id FROM users WHERE token = :token AND is_active = FALSE");
                $stmt->execute(['token' => $token]);
                $user = $stmt->fetch();

                if ($user) {

                    $updateStmt = $this->db->prepare("UPDATE users SET is_active = TRUE, token = NULL WHERE id = :id");
                    $updateStmt->execute(['id' => $user['id']]);
                    $message = "Votre compte a été vérifié avec succès ! Vous pouvez maintenant vous connecter.";
                } else {
                    $message = "Token invalide ou compte déjà vérifié.";
                }
            } catch (\PDOException $e) {
                $message = "Une erreur est survenue lors de la vérification : " . $e->getMessage();
            }
        }

        $render = new Render("login", "frontoffice", ['message' => $message]);
        $render->render();
        return;
    }

    public function reset(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    
        $token = $_GET['token'] ?? null;
        $userModel = new User();
        $user = $token ? $userModel->getUserByResetToken($token) : null;
    
        if (!$user) {
            $render = new Render("reset_invalid", "frontoffice", [
                "message" => "Ce lien n'est plus valide."
            ]);
            $render->render();
            return;
        }
    
        $errors = [];
        $success = null;
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
            $password = $_POST['password'] ?? '';
            $confirm  = $_POST['confirm'] ?? '';
        
            if ($password !== $confirm) {
                $errors[] = "Les mots de passe ne correspondent pas.";
            } else {
            
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $userModel->updatePassword($user['id'], $hash);
            
                $success = "Votre mot de passe a été modifié. Vous pouvez maintenant vous connecter.";
            }
        }
    
        $render = new Render("reset", "frontoffice", [
            "errors"  => $errors,
            "success" => $success,
            "token"   => $token
        ]);
    
        $render->render();
    }


    public function logout(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION = [];

        session_destroy();

        session_start();

        $render = new Render("login", "frontoffice", [
            "message" => "Vous avez été déconnecté avec succès."
        ]);

        $render->render();
    }


}