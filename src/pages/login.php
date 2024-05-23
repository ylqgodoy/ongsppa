<?php
require("../lib/database.php");

$msgerror = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $CPF = $_POST['CPF'];
    $senha = $_POST['pass'];

    if (empty($CPF) || empty($senha)) {
        $msgerror = "Por favor, insira o CPF e/ou senha.";
    } else {
        $sql = "SELECT senha, CPF, nome, email, errosLogin, nivel FROM users WHERE CPF=?";
        $stmt = mysqli_prepare($conn, $sql);

        if (!$stmt) {
            die("Erro no SQL.");
        }

        if (!mysqli_stmt_bind_param($stmt, "s", $CPF)) {
            die("Erro vinculando parâmetros!");
        }

        if (!mysqli_execute($stmt)) {
            die("Erro ao executar");
        }

        if (!mysqli_stmt_bind_result($stmt, $senhabd, $CPF, $nome, $email, $errosLogin, $nivel)) {
            die("Não foi possível vincular resultados");
        }

        $fetch = mysqli_stmt_fetch($stmt);

        if (!$fetch) {
            $msgerror = "Insira um CPF válido e/ou registrado!";
        } else {
            mysqli_stmt_close($stmt);
            require("../lib/cryp2graph2.php");

            if (ChecaSenha($senha, $senhabd)) {
                session_start();
                $_SESSION['nome'] = $nome;
                $_SESSION['email'] = $email;
                $_SESSION['CPF'] = $CPF;
                $_SESSION['nivel'] = $nivel;

                $errosLogin = 0;

                $sql = "UPDATE users SET errosLogin=? WHERE CPF=?";
                $stmt = mysqli_prepare($conn, $sql);

                if (!$stmt) {
                    die("Não foi possível preparar a atualização!");
                }

                if (!mysqli_stmt_bind_param($stmt, "is", $errosLogin, $CPF)) {
                    die("Não foi possível vincular parâmetros!");
                }

                if (!mysqli_stmt_execute($stmt)) {
                    echo ("Não foi possível alterar os erros!");
                }

                ob_clean();
                header("Location: ../pages/menu.php");
            } else {
                $sql = "SELECT errosLogin FROM users WHERE CPF=? ";

                $stmt = mysqli_prepare($conn, $sql);

                if (!$stmt) {
                    echo ($sql);
                    die("Não foi possível preparar a consulta!");
                }

                if (!mysqli_stmt_bind_param($stmt, "s", $CPF)) {
                    die("Não foi possível vincular parâmetros!");
                }

                if (!mysqli_stmt_execute($stmt)) {
                    die("Não foi possível executar busca no Banco de Dados!");
                }

                if (!mysqli_stmt_bind_result($stmt, $errosLogin)) {
                    die("Não foi possível vincular resultados");
                }

                $fetch = mysqli_stmt_fetch($stmt);

                if (!$fetch) {
                    die("Não foi possível recuperar dados");
                }

                if ($fetch == null) {
                    die("CPF não foi localizado!<br>");
                }

                mysqli_stmt_close($stmt);

                if ($errosLogin < 2) {
                    $errosLogin++;

                    $sql = "UPDATE users SET errosLogin=? WHERE CPF=?";
                    $stmt = mysqli_prepare($conn, $sql);

                    if (!$stmt) {
                        die("Não foi possível preparar a atualização!");
                    }

                    if (!mysqli_stmt_bind_param($stmt, "is", $errosLogin, $CPF)) {
                        die("Não foi possível vincular parâmetros!");
                    }

                    if (!mysqli_stmt_execute($stmt)) {
                        echo ("Não foi possível atualizar dados!");
                    }
                } else {
                    $senhaTemp = CriaAlgo(6);
                    $sennovacryp = FazSenha($CPF, $senhaTemp);
                    $sql = "UPDATE users SET senha=? WHERE CPF=?";
                    $stmt = mysqli_prepare($conn, $sql);

                    if (!$stmt) {
                        echo ($sql);
                        die("Não foi possível preparar a consulta!");
                    }

                    if (!mysqli_stmt_bind_param($stmt, "ss", $sennovacryp, $CPF)) {
                        die("Não foi possível vincular parâmetros!");
                    }

                    if (!mysqli_stmt_execute($stmt)) {
                        echo ("Não foi possível atualizar dados!");
                    } else {
                        require("../lib/email.php");
                        $mensagem = "Olá " . $nome . ",<br><br>";
                        $mensagem .= "Recebemos a solicitação de um código para o acesso da sua conta.<br>";
                        $mensagem .= "Seu código de acesso é: <strong><span style='background-color: #D3D3D3;'>" . $senhaTemp . "</span></strong><br><br>";

                        if (mandarEmail($nome, $email, "Recuperação de Senha", $mensagem)) {
                            $errosLogin = 0;
                            $sql = "UPDATE users SET errosLogin=? WHERE CPF=?";
                            $stmt = mysqli_prepare($conn, $sql);

                            if (!$stmt) {
                                echo ($sql);
                                die("Não foi possível preparar a consulta!");
                            }

                            if (!mysqli_stmt_bind_param($stmt, "is", $errosLogin, $CPF)) {
                                die("Não foi possível vincular parâmetros!");
                            }

                            if (!mysqli_stmt_execute($stmt)) {
                                echo ("Não foi possível atualizar dados!");
                            } else {
                                $msgerror="Você excedeu o limite de tentativas e um email foi enviado a sua caixa de entrada!";
                                // Exibindo a mensagem de email enviada
                                $emailEnviado = true;
                            }
                        } else {
                            echo ("Problema no envio da sua nova senha! Aguarde e tente novamente em breve.");
                        }
                    }
                }
            }

            if (!isset($emailEnviado)) { ?>
                <form>
                    <?php
                    $msgerror = "Você errou pela  $errosLogin ª vez a combinação de CPF e/ou senha!"; ?>
                </form>
            <?php }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
        <title>SPPA - Administrador</title>
        <script type="text/javascript" src="../assets/js/scripts.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../assets/css/styles.css">
    </head>
    <body>
        <section class="vh-100 sectionbg" style="background-color: #1f2029;">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col col-xl-10">
                        <div class="card" style="border-radius: 1rem;">
                            <div class="row g-0">
                                <div class="col-md-6 col-lg-5 d-none d-md-block">
                                    <img src="../assets/images/test.jpg"
                                        alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem;" />
                                </div>
                                <div class="col-md-6 col-lg-7 d-flex align-items-center">
                                    <div class="card-body p-4 p-lg-5 text-black">
                                        <form name="frm" method="POST" action="">
                                            <h2 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Acessar painel administrativo</h2>
                                            <?php if (!empty($msgerror)) { ?>
                                                <div class="msgerror">
                                                    <?php echo $msgerror; ?>
                                                </div>
                                            <?php } ?>
                                            <div class="form-outline mb-4 position-relative">
                                                <i class="input-icon fas fa-user position-absolute start-0 top-50 translate-middle-y"></i>
                                                <input type="text" id="cpf" name="CPF" class="form-control form-control-lg" placeholder="Seu CPF" maxlength="14" minlength="11" required autofocus/>
                                            </div>
                                            <div class="form-outline mb-4 position-relative">
                                                <i class="input-icon fas fa-lock position-absolute start-0 top-50 translate-middle-y"></i>
                                                <input type="password" id="pass" name="pass" class="form-control form-control-lg" placeholder="Sua senha" maxlength="32" minlength="6" />
                                            </div>
                                            <div class="pt-1 mb-4">
                                                <button class="btn btn-primary btn-lg btn-block" type="submit">Acessar</button>
                                            </div>
                                            <a class="small text-muted" href="#!">Esqueceu sua senha?</a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-4">
                                            Copyright &copy; 1989-2024 &mdash; Sociedade Piracicabana de Protecao Aos Animais. 
                                        </div>
            </div>
        </section>
    </body>
</html>