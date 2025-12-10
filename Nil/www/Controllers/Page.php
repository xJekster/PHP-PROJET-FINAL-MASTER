<?php

namespace App\Controller;

use App\Core\Render;
use App\Model\PageModel;

class Page
{
    public function index(): void
    {
        $pageModel = new PageModel();
        $pages = $pageModel->getAll();

        $render = new Render("pages", "backoffice", [
            "pages" => $pages
        ]);
        $render->render();
    }

    public function create(): void
    {
        $errors = [];
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title']);
            $slug = trim($_POST['slug']);
            $content = $_POST['content'];

            $pageModel = new PageModel();
            $pageModel->create($title, $slug, $content, $_SESSION["user_id"]);
            $success = "Page créée avec succès.";
        } 


        $render = new Render("pages_form", "backoffice", [
            "success" => $success,
            "errors"  => $errors
        ]);
        $render->render();
    }

    public function edit(int $id): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $pageModel = new PageModel();
        $page = $pageModel->getById($id);

        if (!$page) {
            $render = new Render("404", "frontoffice", ["message" => "Page introuvable."]);
            $render->render();
            return;
        }

        $userModel = new \App\Model\User();
        $currentUser = $userModel->getById($_SESSION['user_id']);

        if ($page['user_id'] !== $_SESSION['user_id'] && $currentUser['role'] !== 'admin') {
            $render = new Render("403", "frontoffice", ["message" => "Vous n'avez pas la permission de modifier cette page."]);
            $render->render();
            return;
        }

        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $title = trim($_POST['title']);
            $slug = trim($_POST['slug']);
            $content = $_POST['content'];

            $pageModel->update($id, $title, $slug, $content);

            $success = "Page mise à jour avec succès.";

            $page = $pageModel->getById($id);
        }

        $render = new Render("pages_form", "backoffice", [
            "page" => $page,
            "success" => $success
        ]);
        $render->render();
    }



    public function delete(int $id): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $pageModel = new PageModel();
        $page = $pageModel->getById($id);

        if (!$page) {
            $render = new Render("404", "frontoffice", ["message" => "Page introuvable."]);
            $render->render();
            return;
        }

        $userModel = new \App\Model\User();
        $currentUser = $userModel->getById($_SESSION['user_id']);

        if ($page['user_id'] !== $_SESSION['user_id'] && $currentUser['role'] !== 'admin') {
            $render = new Render("403", "frontoffice", ["message" => "Vous n'avez pas l'autorisation de supprimer cette page."]);
            $render->render();
            return;
        }

        $pageModel->delete($id);

        $pages = $pageModel->getAll();

        $render = new Render("pages", "backoffice", [
            "pages" => $pages,
            "success" => "La page a bien été supprimée."
        ]);

        $render->render();
    }



    public function show(string $slug): void
    {
        $pageModel = new PageModel();
        $page = $pageModel->getBySlug($slug);

        if (!$page) {
            $render = new Render("404", "frontoffice", [
                "message" => "Page introuvable."
            ]);
            $render->render();
            return;
        }

        $render = new Render("show", "backoffice", [
            "title"   => $page["title"],
            "content" => $page["content"]
        ]);

        $render->render();
    }



    private function renderNotFound(string $message = "Ressource introuvable.")
    {
        $render = new Render("404", "frontoffice", [
            "message" => $message
        ]);
        $render->render();
    }
}
