<?php
include '../lib/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'], $_POST['nome'], $_POST['descricao'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];

    $sql = "UPDATE ads SET nome=?, descricao=? WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssi", $nome, $descricao, $id);
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
            $nome = $row['nome'];
            $descricao = $row['descricao'];
?>
<!DOCTYPE html>
<html>
    <head>
		
    </head>
    <body>
        <h2>Editar Anúncio</h2>
        <form action="" method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <label for="nome">Título do Anúncio:</label>
            <input type="text" name="nome" value="<?php echo $nome; ?>" required>

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