<?php
session_start();

if (!isset($_SESSION['idusuario'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="CSS/telainicial.css" />
    <title>Início</title>
</head>
<body>
    <div class="background"></div>

    <div class="retangulo">
        <h1>Início</h1>

        <div class="Jogar">
            <button class="botao" onclick="window.location.href='index.php'">Jogar</button>
        </div>

        <div class="Historico">
            <button class="botao" onclick="window.location.href='telaHistorico.php'">Histórico</button>
        </div>

        <div class="Classificacao">
            <button class="botao" onclick="window.location.href='TelaClassificacao.php'">Classificação</button>
        </div>

        <div class="Liga">
            <button class="botao" onclick="window.location.href='ligas.php'">Ligas</button>
        </div>

        <div class="Sair">
            <button class="botao" onclick="window.location.href='logout.php'">Sair</button>
        </div>

        <div id="texto">
            <h5>O melhor jogo de digitação!</h5>
        </div>
    </div>

    <script src="JS/Telainicio.js"></script>
</body>
</html>
