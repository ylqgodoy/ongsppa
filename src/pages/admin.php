<?php
    include '../lib/database.php';
    date_default_timezone_set('America/Sao_Paulo');

    $meses = date('Y-m-d H:i:s', strtotime('-2 months'));
    $deletar = "DELETE FROM ads WHERE criado < ?";
    $stmtdel = mysqli_prepare($conn, $deletar);
    if ($stmtdel) {
        mysqli_stmt_bind_param($stmtdel, "s", $meses);
        mysqli_stmt_execute($stmtdel);
        mysqli_stmt_close($stmtdel);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $titulo = $_POST['titulo'];
        $descricao = $_POST['descricao'];
        $imagem = $_FILES['imagem']['tmp_name'];

        if ($imagem) {
            $conteudoimg = file_get_contents($imagem);

            $exclusao = date('Y-m-d H:i:s', strtotime('+2 months'));

            $sql = "INSERT INTO ads (titulo, descricao, imagem, criado, exclusao) VALUES (?, ?, ?, NOW(), ?)";
            $stmtcriar = mysqli_prepare($conn, $sql);

            if ($stmtcriar) {
                mysqli_stmt_bind_param($stmtcriar, "ssss", $titulo, $descricao, $conteudoimg, $exclusao);
                mysqli_stmt_execute($stmtcriar);
                mysqli_stmt_close($stmtcriar);
            }
        }
    }

    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];

        $sql = "DELETE FROM ads WHERE id = ?";
        $stmtdel2 = mysqli_prepare($conn, $sql);

        if ($stmtdel2) {
            mysqli_stmt_bind_param($stmtdel2, "i", $id);
            mysqli_stmt_execute($stmtdel2);
            mysqli_stmt_close($stmtdel2);
            header("Location: admin.php");
            exit();
        }
    }

    $sql = "SELECT * FROM ads";
    $result = mysqli_query($conn, $sql);

    $ads = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $ads[] = $row;
        }
    }

    mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
    <body>
        <h2>Lista de Anúncios</h2>
        <?php 
            if (!empty($ads)) {
                foreach ($ads as $ad) {
                    $imgData = base64_encode($ad['imagem']);
                    $src = 'data:imagem/jpeg;base64,' . $imgData;
                    ?>
                    <div>
                        <h5><?php echo htmlspecialchars($ad["titulo"]); ?></h5>
                        <p><?php echo htmlspecialchars($ad["descricao"]); ?></p>
                        <img src="<?php echo $src; ?>" alt="imagemm do Anúncio">
                        <p><small>Criado em: <?php echo htmlspecialchars($ad["criado"]); ?></small></p>
                        <a href="edit_ad.php?id=<?php echo $ad["id"]; ?>">Editar</a>
                        <a href="admin.php?delete=<?php echo $ad["id"]; ?>" onclick="return confirm('Tem certeza que deseja excluir este anúncio?')">Excluir</a>
                    </div>
                <?php 
                }
            }
        ?>
        <h2>Criar Anúncio</h2>
        <form action="admin.php" method="post" enctype="multipart/form-data">
            <label for="titulo">Título do Anúncio:</label>
            <input type="text" name="titulo" required><br><br>

            <label for="descricao">Descrição do Anúncio:</label>
            <textarea name="descricao" required></textarea><br><br>

            <label for="imagem">imagemm do Anúncio:</label>
            <input type="file" name="imagem" required><br><br>

            <input type="submit" value="Criar Anúncio">
        </form>
    </body>
</html>
