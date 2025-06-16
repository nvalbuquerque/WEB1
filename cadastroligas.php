<?php
session_start();
require_once 'src/db.php';

$mensagem = '';

if (!isset($_SESSION['idusuario'])) {
    header("Location: login.php");
    exit;
}

$idusuario = $_SESSION['idusuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nomeliga']) && !empty(trim($_POST['nomeliga']))) {
        $nomeliga = trim($_POST['nomeliga']);

        $stmt = $conn->prepare("INSERT INTO ligatable (nomeliga) VALUES (?)");
        $stmt->bind_param("s", $nomeliga);

        if ($stmt->execute()) {
            $idligaNova = $conn->insert_id;

            $update = $conn->prepare("UPDATE cadastrotable SET idliga = ? WHERE idusuario = ?");
            $update->bind_param("ii", $idligaNova, $idusuario);

            if ($update->execute()) {
                $mensagem = "Liga criada!";
            } else {
                $mensagem = "Erro ao associar a liga ao usuário: " . $conn->error;
            }

            $update->close();
        } else {
            $mensagem = "Erro ao cadastrar a liga: " . $conn->error;
        }

        $stmt->close();
    } else {
        $mensagem = "O nome da liga não pode estar vazio.";
    }
}

$ligas = $conn->query("SELECT idliga, nomeliga FROM ligatable ORDER BY nomeliga ASC");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Liga</title>
    <link rel="stylesheet" href="CSS/csspadrao.css">
</head>
<body>
    <div class="background"></div>

    <div class="retangulo">
        <h1>Cadastrar Liga</h1>

        <?php if (!empty($mensagem)): ?>
            <p><?php echo htmlspecialchars($mensagem); ?></p>
        <?php endif; ?>

        <div id="texto">
            <form method="POST" action="">
                <label for="nomeliga">Nome da nova liga:</label>
                <input type="text" name="nomeliga" id="nomeliga" required>
            </form>
        </div>

        <div class="BotaoLigas">
            <button type="submit" id="BotaoLigas">Cadastrar</button>
            <button id="BotaoLigas" onclick="window.history.back()">Voltar</button>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
