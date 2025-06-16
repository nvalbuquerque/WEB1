<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['idusuario'])) {
    header("Location: login.php");
    exit;
}

$sql = "SELECT c.nomeusuario, SUM(h.pontuacaoPartida) AS pontuacaoTotal
        FROM historicotable h
        INNER JOIN cadastrotable c ON h.idusuario = c.idusuario
        GROUP BY c.idusuario
        ORDER BY pontuacaoTotal DESC";

$result = $conn->query($sql);

if (!$result) {
    die("Erro na consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Tela Classificação</title>
    <link rel="stylesheet" href="CSS/telaclassif.css" />
</head>
<body>
     <div class="background"></div>
     <div class="retangulo">
          <h1>CLASSIFICAÇÃO GERAL</h1>
          <div class="Botoes">
              <button id="BotaoJogar">Jogar Novamente</button>
              <button id="BotaoHistorico">Histórico</button>
          </div>
          <div class="Sair">
              <button id="BotaoSair">Sair</button>
          </div>

          <ol class="dadosClassif">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<li>" . htmlspecialchars($row['nomeusuario']) . " - <span class='pontos'>" . intval($row['pontuacaoTotal']) . " pts</span></li>";
                }
            } else {
                echo "<li>Nenhuma pontuação encontrada.</li>";
            }
            ?>
          </ol>

          <div id="fraseMotivacional">
              <p>Continue praticando e melhore sua pontuação! 🚀</p>
          </div>
     </div>
<script src="telaclassif.js"></script>
</body>
</html>

<?php
$conn->close();
?>
