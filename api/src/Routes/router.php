<?php

$pagina = isset($_GET['rota']) ? $_GET['rota'] :  include './src/Routes/home/home.php';

$rotas = [
    'login' => './src/Routes/login/login.php',
    'usuarios' => './src/Routes/usuarios/usuarios.php',
    'tipos-orgaos' => './src/Routes/orgaos/tipos-orgaos.php',
    'orgaos' => './src/Routes/orgaos/orgaos.php',
    'tipos-pessoas' => './src/Routes/pessoas/tipos-pessoas.php',
    'pessoas' => './src/Routes/pessoas/pessoas.php',
    'profissoes' => './src/Routes/pessoas/profissoes.php',
    'proposicoes' => './src/Routes/proposicoes/proposicoes.php',
    'atualizar-proposicoes' => './src/Routes/proposicoes/atualizar-proposicoes.php',

    'oficios' => './src/Routes/oficios/oficios.php',

];

if (array_key_exists($pagina, $rotas)) {
    include $rotas[$pagina];
} else {
    include './src/Routes/error/404.php';
}
