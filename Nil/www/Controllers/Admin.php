<?php

namespace App\Controller;

use App\Core\Render;
use App\Model\User;

class Admin
{
    public function index(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (empty($_SESSION['user_id'])) {
            $render = new Render("403", "frontoffice", [
                "message" => "Vous devez être connecté pour accéder à cette page."
            ]);
            $render->render();
            return;
        }

        $userModel = new User();
        $user = $userModel->getById($_SESSION['user_id']);

        if (!$user || $user["role"] !== "admin") {
            $render = new Render("403", "frontoffice", [
                "message" => "Accès réservé aux administrateurs."
            ]);
            $render->render();
            return;
        }

        $render = new Render("admin", "backoffice", [
            "title" => "Panneau Administrateur",
            "user"  => $user
        ]);
        $render->render();
    }
}
