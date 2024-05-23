<?php
session_start();
session_set_cookie_params([
    'secure' => true, // Apenas transmitir cookies sobre HTTPS
    'httponly' => true, // Impedir acesso JavaScript
    'samesite' => 'Strict', // Reforçar a política SameSite
]);

if (!isset($_SESSION['CPF'])) {
    header("Location: login01.php");
    exit; // Encerrar o script pra nao ter nenhuma falha de invasão.
}
?>
