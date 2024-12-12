<?php

use GabineteDigital\Controllers\UsuarioController;

require './pages/includes/verificaLogado.php';
require_once '../autoloader.php';

$usuarioController = new UsuarioController;

?>


<div class="d-flex" id="wrapper">
    <?php include 'pages/includes/sider_bar.php'; ?>
    <div id="page-content-wrapper">
        <?php include 'pages/includes/top_menu.php'; ?>
        <div class="container-fluid p-2">
            <div class="card mb-2 ">
                <div class="card-body p-1">
                    <a class="btn btn-primary btn-sm custom-nav card-description" href="?secao=home" role="button"><i class="bi bi-house-door-fill"></i> Início</a>
                </div>
            </div>

            <div class="card mb-2 card-description ">
                <div class="card-header bg-primary text-white px-2 py-1 card-background"><i class="bi bi-people-fill"></i> Adicionar usuários</div>
                <div class="card-body p-2">
                    <p class="card-text mb-0">Todos os campos são obrigatórios (exceto a foto) <br> A foto deve ser em JPG ou PNG</p>
                </div>
            </div>

            <div class="card shadow-sm mb-2">
                <div class="card-body p-2">
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_salvar'])) {
                        if ($_POST['usuario_senha'] !== $_POST['usuario_senha2']) {
                            echo '<div class="alert alert-info px-2 py-1 mb-2 custom-alert" data-timeout="3" role="alert">As senha não conferem</div>';
                        } elseif (strlen($_POST['usuario_senha']) < 6) {
                            echo '<div class="alert alert-danger px-2 py-1 mb-2 custom-alert" data-timeout="3" role="alert">A senha tem menos de 6 caracteres</div>';
                        } else {

                            $usuario = [
                                'usuario_nome' => htmlspecialchars($_POST['usuario_nome'], ENT_QUOTES, 'UTF-8'),
                                'usuario_email' => htmlspecialchars($_POST['usuario_email'], ENT_QUOTES, 'UTF-8'),
                                'usuario_telefone' => htmlspecialchars($_POST['usuario_telefone'], ENT_QUOTES, 'UTF-8'),
                                'usuario_aniversario' => htmlspecialchars($_POST['usuario_aniversario'], ENT_QUOTES, 'UTF-8'),
                                'usuario_ativo' => htmlspecialchars($_POST['usuario_ativo'], ENT_QUOTES, 'UTF-8'),
                                'usuario_nivel' => htmlspecialchars($_POST['usuario_nivel'], ENT_QUOTES, 'UTF-8'),
                                'usuario_senha' => htmlspecialchars($_POST['usuario_senha'], ENT_QUOTES, 'UTF-8'),
                                'foto' => isset($_FILES['usuario_foto']) ? $_FILES['usuario_foto'] : null
                            ];

                            $result = $usuarioController->criarUsuario($usuario);

                            if ($result['status'] == 'success') {
                                echo '<div class="alert alert-success px-2 py-1 mb-2 custom-alert" data-timeout="3" role="alert">' . $result['message'] . '</div>';
                            } else if ($result['status'] == 'duplicated' || $result['status'] == 'bad_request' || $result['status'] == 'invalid_email') {
                                echo '<div class="alert alert-info px-2 py-1 mb-2 custom-alert" data-timeout="3" role="alert">' . $result['message'] . '</div>';
                            } else if ($result['status'] == 'error' || $result['status'] == 'forbidden') {
                                echo '<div class="alert alert-danger px-2 py-1 mb-2 custom-alert" data-timeout="0" role="alert">' . $result['message'] . '</div>';
                            }
                        }
                    }
                    ?>

                    <form class="row g-2 form_custom" id="form_novo" method="POST" enctype="multipart/form-data">
                        <div class="col-md-6 col-12">
                            <input type="text" class="form-control form-control-sm" name="usuario_nome" placeholder="Nome" required>
                        </div>
                        <div class="col-md-2 col-12">
                            <input type="email" class="form-control form-control-sm" name="usuario_email" placeholder="Email" required>
                        </div>
                        <div class="col-md-2 col-6">
                            <input type="text" class="form-control form-control-sm" name="usuario_telefone" placeholder="Celular (com DDD)" data-mask="(00) 00000-0000" maxlength="11" required>
                        </div>
                        <div class="col-md-2 col-6">
                            <input type="text" class="form-control form-control-sm" name="usuario_aniversario" data-mask="00/00" placeholder="Aniversário (dd/mm)" required>
                        </div>
                        <div class="col-md-1 col-6">
                            <select class="form-select form-select-sm" name="usuario_ativo" required>
                                <option value="1" selected>Ativado</option>
                                <option value="0">Desativado</option>
                            </select>
                        </div>
                        <div class="col-md-1 col-6">
                            <select class="form-select form-select-sm" name="usuario_nivel" required>
                                <option value="1">Administrador</option>
                                <option value="2" selected>Assessor</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-6">
                            <input type="password" class="form-control form-control-sm" id="usuario_senha" name="usuario_senha" placeholder="Senha" required>
                        </div>
                        <div class="col-md-2 col-6">
                            <input type="password" class="form-control form-control-sm" id="usuario_senha2" name="usuario_senha2" placeholder="Confirme a senha" required>
                        </div>
                        <div class="col-md-3 col-12">
                            <div class="file-upload">
                                <input type="file" id="file-input" name="usuario_foto" style="display: none;" />
                                <button id="file-button" type="button" class="btn btn-primary btn-sm"><i class="bi bi-camera-fill"></i> Escolher Foto</button>
                                <button type="submit" class="btn btn-success btn-sm" name="btn_salvar"><i class="bi bi-floppy-fill"></i> Salvar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm mb-2">
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-striped mb-0 custom-table">
                            <thead>
                                <tr>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Telefone</th>
                                    <th scope="col">Nível</th>
                                    <th scope="col">Ativo</th>
                                    <th scope="col">Criado</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $busca = $usuarioController->listarUsuarios();
                                if ($busca['status'] == 'success') {
                                    foreach ($busca['dados'] as $usuario) {
                                        echo '<tr>';
                                        echo '<td style="white-space: nowrap; justify-content: center; align-items: center;"><a href="?secao=usuario&id=' . $usuario['usuario_id'] . '">' . $usuario['usuario_nome'] . '</a></td>';
                                        echo '<td style="white-space: nowrap;">' . $usuario['usuario_email'] . '</td>';
                                        echo '<td style="white-space: nowrap;">' . $usuario['usuario_telefone'] . '</td>';
                                        echo '<td style="white-space: nowrap;">' . ($usuario['usuario_nivel'] ? 'Administrador' : 'Assessor') . '</td>';
                                        echo '<td style="white-space: nowrap;">' . ($usuario['usuario_ativo'] ? 'Ativo' : 'Desativado') . '</td>';
                                        echo '<td style="white-space: nowrap;">' . date('m/d', strtotime($usuario['usuario_criado_em'])) . '</td>';
                                        echo '</tr>';
                                    }
                                } else if ($busca['status'] == 'empty' || $busca['status'] == 'error') {
                                    echo '<tr><td colspan="6">' . $busca['message'] . '</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>