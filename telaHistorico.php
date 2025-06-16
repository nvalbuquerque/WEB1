<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['idusuario'])) {
    header("Location: login.php");
    exit;
}

$sql = "SELECT c.nomeusuario, h.criado_em, h.tempo_jogo, h.pontuacaoPartida
        FROM historicotable h
        INNER JOIN cadastrotable c ON h.idusuario = c.idusuario
        ORDER BY h.criado_em DESC";

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
    <title>Tela Histórico</title>
    <link rel="stylesheet" href="CSS/telahistorico.css" />
</head>
<body>
    <div class="background"></div>
    <div class="retangulo">
        <h1>HISTÓRICO</h1>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Jogador</th>
                    <th>Data</th>
                    <th>Tempo</th>
                    <th>Pontuação</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $dataFormatada = date('d/m/Y', strtotime($row['criado_em']));
                        $tempo = $row['tempo_jogo'];
                        $tempoFormatado = date('i:s', strtotime($tempo));

                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nomeusuario']) . "</td>";
                        echo "<td>" . $dataFormatada . "</td>";
                        echo "<td>" . $tempoFormatado . "</td>";
                        echo "<td>" . htmlspecialchars($row['pontuacaoPartida']) . " pts</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Nenhum histórico encontrado.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="Voltar">
            <button id="BotaoVoltar" onclick="window.history.back()">Voltar</button>
        </div>
    </div>

    <script src="TelaHistorico.js"></script>
</body>
</html>

<?php
$conn->close();
?>
