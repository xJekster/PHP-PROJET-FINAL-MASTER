<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
//include
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
/*
 * Un tableau HTML avec la liste des users
 * et un bouton par ligne pour supprimer un user
 * avec un confirm JS pour confirmer la suppression en BDD
 * et un mail pour prévenir le user
 */

try{
    $pdo = new PDO("pgsql:host=db;port=5432;dbname=devdb","devuser", "devpass");
}catch(Exception $e){
    die("Erreur ".$e->getMessage());
}

if (isset($_GET["id"]) && isset($_GET["action"])){
    $sql = 'SELECT "firstname", "lastname", "email" FROM "user" WHERE "id"=:id';
    $queryPrepared = $pdo->prepare($sql);
    $queryPrepared->execute(["id"=>$_GET["id"]]);
    $user = $queryPrepared->fetch(PDO::FETCH_ASSOC);
    if($user){
        if ( $_GET["action"]=="delete"){
            $sql = 'DELETE FROM "user" WHERE "id"=:id';
            $queryPrepared = $pdo->prepare($sql);
            $queryPrepared->execute(["id"=>$_GET["id"]]);
            //Envoyer un mail de notification
            $mail = new PHPMailer(true);
            try {
                //Server settings                     //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'mailpit';                              //Set the SMTP server to send through
                $mail->SMTPAuth   = false;                                   //Enable SMTP authentication
                $mail->Port       = 1025;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom('from@example.com', 'Mailer');
                $mail->addAddress($user['email'], $user['firstname'].' '.$user['lastname']);     //Add a recipient

                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'Suppression de votre compte';
                $mail->Body    = 'Votre compte a été <b>supprimé</b>.';
                $mail->AltBody = 'Votre compte a été supprimé.';

                $mail->send();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }else if ( $_GET["action"]=="active"){
            //Activer / Désactiver un user
            $sql = 'UPDATE "user" SET "is_active" = NOT "is_active" WHERE "id"=:id';
            $queryPrepared = $pdo->prepare($sql);
            $queryPrepared->execute(["id"=>$_GET["id"]]);
        }

    }

}

$sql = 'SELECT "id", "firstname", "lastname", "email", "is_active" FROM "user" ORDER BY "id" DESC';
$queryPrepared = $pdo->prepare($sql);
$queryPrepared->execute();
$users = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TP2 - Liste des users</title>
    <meta name="description" content="TP2 - Liste des users">
</head>
<body>
<h1>Liste des users</h1>
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Prénom</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['id']) ?></td>
            <td><?= htmlspecialchars($user['firstname']) ?></td>
            <td><?= htmlspecialchars($user['lastname']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= $user['is_active']?"1":"0" ?></td>
            <td>
                <a href="TP2.php?action=active&id=<?= $user['id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir <?= $user['is_active']?"désactiver":"activer" ?> cet utilisateur ?');"><?= $user['is_active']?"Désactiver":"Activer" ?></a>
                <a href="TP2.php?action=delete&id=<?= $user['id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
