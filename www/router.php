<?php

$pagina = isset($_GET['secao']) ? $_GET['secao'] :  include './pages/home/home.php';

$rotas = [
   
    'login' => './pages/login/login.php',
    'home' => './pages/home/home.php',

];

if (array_key_exists($pagina, $rotas)) {
    include $rotas[$pagina];
} else {
    include './pages/errors/404.php';
}