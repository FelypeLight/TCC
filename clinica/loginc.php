<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
</head>
<?php
include('../conexao.php');

function showModal($message) {
    echo "<div class='modal' tabindex='-1' role='dialog' id='errorModal'>
            <div class='modal-dialog' role='document'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title'>Aviso!</h5>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                    <div class='modal-body'>
                        <p>$message</p>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-secondary' data-dismiss='modal'>Fechar</button>
                    </div>
                </div>
            </div>
          </div>";
    echo "<script>
            $(document).ready(function() {
                $('#errorModal').modal('show');
                $('#errorModal').on('hidden.bs.modal', function () {
                    window.location.href = 'login.php';
                });
                setTimeout(function() {
                    $('#errorModal').modal('hide');
                }, 3000); // 3 segundos
            });
          </script>";
}

/////////// Login
if(isset($_POST['login'])) {
    if(empty($_POST['email'])) {
        showModal('Preencha seu email');
    } elseif(empty($_POST['senha'])) {
        showModal('Preencha sua senha');
    } else {
        $email = $mysqli->real_escape_string($_POST['email']);
        $senha = $mysqli->real_escape_string($_POST['senha']);

        // Consulta para verificar se o usuário e senha estão corretos
        $sql_code_login = "SELECT * FROM tb_login_clinica WHERE email = '$email' AND senha = '$senha'";
        $sql_query_login = $mysqli->query($sql_code_login) or die("Falha na execução" . $mysqli->error);
        $quantidade_login = $sql_query_login->num_rows;

        if ($quantidade_login == 1) {
            // Consulta para verificar se o pagamento está confirmado
            $usuario = $sql_query_login->fetch_assoc();
            $user_id = $usuario['id'];

            $sql_code_pagamento = "SELECT * FROM tb_pagamentos WHERE user_id = '$user_id' AND status = 'confirmado'";
            $sql_query_pagamento = $mysqli->query($sql_code_pagamento) or die("Falha na execução" . $mysqli->error);
            $quantidade_pagamento = $sql_query_pagamento->num_rows;

            if ($quantidade_pagamento == 1) {
                // Inicia a sessão
                session_start();

                // Define as variáveis de sessão
                $_SESSION['user'] = $usuario['id'];

                // Redireciona para a página do usuário
                header('Location: user.php');
                exit;
            } else {
                showModal('Pagamento não confirmado!');
            }
        } else {
            showModal('E-mail ou senha incorretos!');
        }
    }
}
?>
<body style="background-color: rgb(0, 201, 221);">

    <div class="container ">

        <!-- Outer Row -->
        <div class="row justify-content-center align-items-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Bem-Vindo de Volta!</h1>
                                    </div>
                                    <form class="user" method="post" action="loginc.php">
                                        <div class="form-group">
                                            <label for="email">E-mail:</label>
                                            <input name="email" type="email" class="form-control form-control-user"
                                                id="exampleInputEmail" aria-describedby="emailHelp"
                                                placeholder="Insira endereço de e-mail...">
                                        </div>
                                        <div class="form-group">
                                            <label for="senha">Senha:</label>
                                            <input name="senha" type="password" class="form-control form-control-user"
                                                id="exampleInputPassword" placeholder="Senha">
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">Lembre-se de
                                                    mim</label>
                                            </div>
                                        </div>

                                        <input type="submit" name="login" class="btn btn-primary btn-user btn-block"
                                            value="Login">
                                        
                                    </form>

                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="forgot-password.php">Esqueceu da senha?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="register.php">Criar uma conta!</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="../index.php">Voltar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>




    <div class="modal fade" id="avisoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Aviso</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Falha ao logar! E-mail ou senha incorretos!</div>
                    <div class="modal-footer">
                        <a class="btn btn-primary" href="login.php">Voltar ao inicio</a>
                        <a class="btn btn-danger" href="login.php">Fazer login</a>
                    </div>
                </div>
            </div>
        </div>


    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

</body>
</html>
