<?php 
session_start();
$c = !empty($_GET["c"]) ? $_GET["c"]: "home";
$a = !empty($_GET["a"]) ? $_GET["a"]: "list";
require "../bootstrap.php";

$controller = ucfirst($c) . "Controller";//HomeController
require "controller/$controller" . ".php";
$controller = new $controller();//new HomeController()
$controller->$a();

 ?>