<?php

namespace App\Controller;

use App\Core\Render;
use App\Model\User;

class UserAdmin
{
    public function index(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (empty($_SESSION['user_id'])) {
            $this->forbidden("Vous devez être connecté pour accéder à cette page.");
            return;
        }

        $userModel = new User();
        $loggedUser = $userModel->getById($_SESSION['user_id']);

        if ($loggedUser["role"] !== "admin") {
            $this->forbidden("Accès réservé aux administrateurs.");
            return;
        }

        $users = $userModel->getAllUsers();

        $render = new Render("users", "backoffice", ["users" => $users]);
        $render->render();
    }


    public function updateRole(int $id): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $userModel = new User();
        $current = $userModel->getById($_SESSION['user_id']);

        if ($current['role'] !== 'admin') {
            $this->forbidden();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newRole = $_POST['role'] ?? 'user';
            $userModel->updateUserRole($id, $newRole);
        }

        $users = $userModel->getAllUsers();

        $render = new Render("users", "backoffice", [
            "users" => $users,
            "success" => "Le rôle a été mis à jour."
        ]);

        $render->render();
    }


    public function delete(int $id): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $userModel = new User();
        $current = $userModel->getById($_SESSION['user_id']);

        if ($current['role'] !== 'admin') {
            $this->forbidden();
            return;
        }

        if ($_SESSION["user_id"] == $id) {
            $users = $userModel->getAllUsers();
            $render = new Render("users", "backoffice", [
                "users" => $users,
                "errors" => ["Vous ne pouvez pas supprimer votre propre compte."]
            ]);
            $render->render();
            return;
        }

        if (!$userModel->deleteUser($id)) {
            $users = $userModel->getAllUsers();
            $render = new Render("users", "backoffice", [
                "users" => $users,
                "errors" => ["Impossible de supprimer cet utilisateur car il possède encore des pages."]
            ]);
            $render->render();
            return;
        }

        $users = $userModel->getAllUsers();
        $render = new Render("users", "backoffice", [
            "users" => $users,
            "success" => "Utilisateur supprimé avec succès."
        ]);

        $render->render();
    }



    private function forbidden(string $msg = "Accès refusé")
    {
        $render = new Render("403", "frontoffice", ["message" => $msg]);
        $render->render();
    }
}
