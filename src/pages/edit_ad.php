<?php
include '../lib/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'], $_POST['titulo'], $_POST['descricao'])) {
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];

    $sql = "UPDATE ads SET titulo=?, descricao=? WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssi", $titulo, $descricao, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("Location: admin.php");
        exit();
    }
} else {
    if(isset($_GET['id'])) {
        $id = $_GET['id'];

        $sql = "SELECT * FROM ads WHERE id = $id";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $titulo = $row['titulo'];
            $descricao = $row['descricao'];
?>
<!DOCTYPE html>
<html>
    <body>
        <h2>Editar Anúncio</h2>
        <form action="" method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <label for="titulo">Título do Anúncio:</label>
            <input type="text" name="titulo" value="<?php echo $titulo; ?>" required>

            <label for="descricao">Descrição do Anúncio:</label>
            <textarea name="descricao" required><?php echo $descricao; ?></textarea>

            <input type="submit" value="Atualizar Anúncio">
        </form>
    </body>
</html>
<?php
        }
    }
}

mysqli_close($conn);
?>