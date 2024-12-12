<link href="css/login.css" rel="stylesheet">
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="centralizada text-center">
        <img src="img/logo_white.png" alt="" class="img_logo" />
        <h2 class="login_title mb-4">Gabinete Digital</h2>
        <div id="alerta"></div>
        <?php

        use GabineteDigital\Controllers\LoginController;

        require_once '../autoloader.php';

        $loginController = new LoginController();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_logar'])) {
            $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
            $senha = htmlspecialchars($_POST['senha'], ENT_QUOTES, 'UTF-8');

            $resultado = $loginController->Logar($email, $senha);

            if ($resultado['status'] == 'success') {
                session_start();
                $_SESSION['usuario_nome'] = $resultado['usuario']['nome'];
                $_SESSION['usuario_email'] = $resultado['usuario']['email'];
                $_SESSION['usuario_nivel'] = $resultado['usuario']['nivel'];
                $_SESSION['usuario_id'] = $resultado['usuario']['id'];
                $_SESSION['usuario_token'] = $resultado['token'];

                setcookie(session_name(), session_id(), time() + 86400);

                echo '<div class="alert alert-success px-2 py-1 mb-2 rounded-5 custom-alert" data-timeout="3" role="alert">' . $resultado['message'] . '. Aguarde...</div>';
                echo '<script>
                        setTimeout(function(){
                            window.location.href = "?secao=home";
                        }, 1000);
                      </script>';
            } else if ($resultado['status'] == 'not_found' || $resultado['status'] == 'deactivated') {
                echo '<div class="alert alert-info px-2 py-1 mb-2  rounded-5 custom-alert" data-timeout="3" role="alert">' . $resultado['message'] . '</div>';
            } else if ($resultado['status'] == 'wrong_password' || $resultado['status'] == 'error' || $resultado['status'] == 'deactived') {
                echo '<div class="alert alert-danger px-2 rounded-5 py-1 mb-2 custom-alert" data-timeout="3" role="alert">' . $resultado['message'] . '</div>';
            }
        }

        ?>

        <form id="form_login" class="form-group" action="" method="post" enctype="application/x-www-form-urlencoded">
            <div class="form-group">
                <input type="email" class="form-control" name="email" id="email" placeholder="E-mail" value="jairojeffersont@gmail.com" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="senha" id="senha" placeholder="Senha" value="intell01" required>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <button type="submit" name="btn_logar" class="btn btn-login">Entrar</button>
            </div>
        </form>
        <p class="mt-3 link">Esqueceu a senha? | <a href="?secao=cadastro">Faça seu cadastro</a></p>
        <p class="mt-3 copyright"><?php echo date('Y') ?> | JS Digital System</p>
    </div>
</div>

<div class="modal fade" id="modalLoading" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body p-0">
                <img class="rounded mx-auto d-block" width="200" src="img/loading.gif">
            </div>
        </div>
    </div>
</div>