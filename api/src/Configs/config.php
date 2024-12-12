<?php
return [

    'database' => [
        'host' => 'localhost',
        'name' => 'gabinete_digital',
        'user' => 'root',
        'password' => 'root',
    ],

    'master_user' => [
        'master_name' => 'Administrador',
        'master_email' => 'admin@admin.com',
        'master_pass' => 'intell01',
    ],

    'app' => [
        'token_key' => '62696e326865782872616e646f6d5f62797465732833322929',
        'token_time' => 24,
        'base_url' =>rtrim($_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/', '')
    ],

    'deputado' => [
        'id' => '204536',
        'nome' => 'Kim Kataguiri',
        'estado' => 'AP',
        'ano_primeiro_mandato' => 2019,
        'email_deputado' => 'dep.kimkataguiri@camara.leg.br',
        'telefone_gabinete' => '6132155414'
    ]
];
