<?php
namespace App;

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}


spl_autoload_register(function ($class){
    $namespaceArray = [
        "namespace" => ["App\\Model\\", "App\\Core\\", "App\\Controller\\"],
        "path" => ["Model/", "Core/", "Controllers/"],
    ];
    $filname = str_ireplace($namespaceArray['namespace'],$namespaceArray['path'], $class  ). ".php";
    if(file_exists($filname)) {
        include $filname;
    }
});

use App\Controller\Page;
use App\Model\PageModel;
use App\Core\Render;

$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), "/");



if (preg_match('#^edit/([0-9]+)$#', $uri, $match)) {
    (new Page())->edit((int)$match[1]);
    return;
}


if (preg_match('#^delete/([0-9]+)$#', $uri, $match)) {
    (new Page())->delete((int)$match[1]);
    return;
}

if (preg_match('#^show/(.+)$#', $uri, $match)) {
    (new Page())->show($match[1]);
    return;
}





$pageModel = new PageModel();
$page = $pageModel->getBySlug($uri);

if ($page) {
    (new Page())->show($uri);
    return;
}




if(!file_exists("routes.yml")){
    die("Le fichier de routing routes.yml n'existe pas");
}

$routes = yaml_parse_file("routes.yml");

if(empty($routes["/".$uri])){

    $render = new Render("404", "frontoffice", [
        "message" => "Page introuvable"
    ]);
    $render->render();
    return;
}

$route = $routes["/".$uri];


if(empty($route["controller"]) || empty($route["action"])){
    die("Erreur routing YAML : controller ou action absent.");
}

$controllerName = "App\\Controller\\".$route["controller"];
$action = $route["action"];

if(!file_exists("Controllers/".$route["controller"].".php")){
    die("Erreur : le fichier controller ".$route["controller"]." n'existe pas.");
}

include "Controllers/".$route["controller"].".php";

if(!class_exists($controllerName)){
    die("Erreur : classe ".$controllerName." introuvable.");
}

$controllerInstance = new $controllerName();

if(!method_exists($controllerInstance, $action)){
    die("Erreur : action ".$action." introuvable dans ".$controllerName);
}

$controllerInstance->$action();
return;
