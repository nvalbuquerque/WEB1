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
  <title>Jogo de Digitação</title>
  <link rel="stylesheet" href="CSS/csspadrao.css" />
</head>

<body>
  <div class="background"></div>

  <div class="retangulo">
    <h1>Jogo de Digitação</h1>

    <div id="timer">Tempo: 60s</div>
    <div id="score">Pontuação: 0</div>
    <div id="texto" style="white-space: pre-wrap; margin: 1em 0; font-size: 1.2em;"></div>
    <div id="autor" style="font-style: italic; margin-bottom: 1em;"></div>
    <input type="text" id="inputTexto" autocomplete="off" autofocus />

  </div>

  <script src="JS/game.js"></script>
</body>

</html>
