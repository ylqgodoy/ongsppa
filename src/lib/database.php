<?php
    $conn = mysqli_connect("localhost", "22092", "Etk193@", "22092");
    if (!$conn) {
        die("Não foi possivel conectar ao bd!");
    }
    date_default_timezone_set('Brazil/East');
    mysqli_query($conn, "SET NAMES 'utf8'");
?>