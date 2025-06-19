<?php
session_start();
require_once 'src/db.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Game</title>
    <link rel="stylesheet" href="CSS/csspadrao.css" />
  </head>
<body>
  <div class="background"></div>

  <div class="retangulo">
    <h1>PLAY!</h1>

    <div id="jogo">
      <p id="tempo">Tempo: 60s</p>
      <p id="pontuacao">Pontuação: 0</p>
      <p id="palavra-display">Clique em iniciar</p>
      <input type="text" id="input-digitacao" placeholder="Digite a palavra..." disabled />
      <button id="iniciar-jogo">Iniciar Jogo</button>
      <p id="feedback-mensagem"></p>
    </div>
  </div>

  <script src="JS/game.js"></script>
</body>

</html>