<?php
    $conn = mysqli_connect("localhost", "SPPA", "sppatestdb", "SPPA");
    if (!$conn) {
        die("Não foi possivel conectar ao bd!");
    }
    date_default_timezone_set('Brazil/East');
    mysqli_query($conn, "SET NAMES 'utf8'");
?>