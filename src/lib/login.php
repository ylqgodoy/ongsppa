<?php
            if (!isset($_POST['CPF'])) {
                ob_clean();
                header("Location: index.php");
            }

            require("database.php");
            $CPF = $_POST['CPF'];
            $senha = $_POST['pass'];

            $sql = "SELECT senha, CPF, nome, email, errosLogin, nivel FROM users WHERE CPF=? ";
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
                echo ("Não foi possível recuperar dados<br>");
            }else {
                mysqli_stmt_close($stmt);
                require("cryp2graph2.php");
                if (ChecaSenha($senha, $senhabd)) {
                    session_start();
                    $_SESSION['nome'] = $nome;
                    $_SESSION['email'] = $email;
                    $_SESSION['CPF'] = $CPF;
                    $_SESSION['nivel'] = $nivel; 

                    $errosLogin = 0;
                    ob_clean();

                    $sql = "UPDATE users set errosLogin=? where CPF=? ";
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

                        $sql = "UPDATE users set errosLogin=? where CPF=? ";
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
                        $sql = "UPDATE users set senha=? WHERE CPF=?";
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
                            require("email.php");
                            $mensagem = "Olá " . $nome . ",<br><br>";
                            $mensagem .= "Recebemos a solicitação de um código para o acesso da sua conta.<br>";
                            $mensagem .= "Seu código de acesso é: <strong><span style='background-color: #D3D3D3;'>" . $senhaTemp . "</span></strong><br><br>";
                            if (mandarEmail($nome, $email, "Recuperação de Senha", $mensagem)) {
                                $errosLogin = 0;
                                $sql = "UPDATE users set errosLogin=? WHERE CPF=?";
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
                                    echo ("<div class='msgenviada'>Você excedeu o limite de tentativas e um email foi enviado a sua caixa de entrada! ($email)</div><br>");
                                    echo ("<button class='btnemailenviado' onclick='window.location=\"login01.php\";'>Voltar ao Login</button></form>");
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
                        <?php  ?>
                        Você errou pela <?php echo $errosLogin; ?>ª vez a combinação de CPF/Senha. Você tem apenas 3 tentativas!
                        <br><br>
                        <button onclick="window.location='login01.php';">Voltar ao Login</button>
                    </form>
                <?php }
            }
            ?>