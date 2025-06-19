// JS/game.js

const words = [
  "espaco", "sideral", "universo", "galaxia", "estrela", "planeta", "lua", "sol", "orbita",
  "cometa", "asteroide", "meteoro", "atmosfera", "cosmo", "astronauta", "telescopio",
  "nave", "foguete", "satelite", "sonda", "gravidade", "constelacao", "via lactea",
  "nebulosa", "quasar", "pulsar", "buraco negro", "exoplaneta", "sistemasolar",
  "extraterrestre", "exploracao", "colonizacao", "jupiter", "marte", "venus",
  "mercurio", "saturno", "urano", "netuno", "plutao", "estrelacadente",
  "cinturaodeasteroides", "bigbang", "buracodeverme", "materiaescura",
  "energiaescura", "velocidadedaluz", "cosmonauta", "astrofisica", "intergalactico"
];

let wordDisplayEl;
let guessInputEl;
let timerDisplayEl;
let scoreDisplayEl;
let startButtonEl;
let feedbackMessageEl;

let currentWord = "";
let score = 0;
let timeLeft = 60;
let timerInterval;
let gameActive = false;

document.addEventListener("DOMContentLoaded", () => {
  wordDisplayEl = document.getElementById("palavra-display");
  guessInputEl = document.getElementById("input-digitacao");
  timerDisplayEl = document.getElementById("tempo");
  scoreDisplayEl = document.getElementById("pontuacao");
  startButtonEl = document.getElementById("iniciar-jogo");
  feedbackMessageEl = document.getElementById("feedback-mensagem");

  timerDisplayEl.textContent = `Tempo: ${timeLeft}s`;
  scoreDisplayEl.textContent = `Pontuação: ${score}`;

  startButtonEl.addEventListener("click", startGame);
  guessInputEl.addEventListener("keypress", handleKeyPress);

  guessInputEl.disabled = true;
});

function getRandomWord() {
  const randomIndex = Math.floor(Math.random() * words.length);
  return words[randomIndex];
}

function startGame() {
  score = 0;
  timeLeft = 60;
  gameActive = true;

  scoreDisplayEl.textContent = `Pontuação: ${score}`;
  timerDisplayEl.textContent = `Tempo: ${timeLeft}s`;
  feedbackMessageEl.textContent = "";
  guessInputEl.value = "";
  guessInputEl.disabled = false;
  guessInputEl.focus();

  startButtonEl.disabled = true;

  nextWord();
  startTimer();
}

function nextWord() {
  currentWord = getRandomWord();
  wordDisplayEl.textContent = currentWord;
  guessInputEl.value = "";
}

function handleKeyPress(event) {
  if (event.key === "Enter" && gameActive) {
    checkGuess();
  }
}

function checkGuess() {
  const userGuess = guessInputEl.value.toLowerCase().trim();

  if (userGuess === currentWord) {
    const wordLength = currentWord.length;
    let pointsEarned = 0;

    if (wordLength <= 5) {
      pointsEarned = 10;
    } else if (wordLength <= 8) {
      pointsEarned = 20;
    } else if (wordLength <= 12) {
      pointsEarned = 35;
    } else {
      pointsEarned = 50;
    }

    score += pointsEarned;
    feedbackMessageEl.textContent = `Correto! (+${pointsEarned} pontos)`;
    feedbackMessageEl.style.color = "green";

  } else {
    feedbackMessageEl.textContent = `Errado! A palavra correta era "${currentWord}".`;
    feedbackMessageEl.style.color = "red";
  }

  scoreDisplayEl.textContent = `Pontuação: ${score}`;
  nextWord();
}

function startTimer() {
  clearInterval(timerInterval);
  timerInterval = setInterval(() => {
    timeLeft--;
    timerDisplayEl.textContent = `Tempo: ${timeLeft}s`;

    if (timeLeft <= 0) {
      clearInterval(timerInterval);
      endGame();
    }
  }, 1000);
}

function endGame() {
  gameActive = false;
  guessInputEl.disabled = true;
  wordDisplayEl.textContent = `Fim de jogo! Sua pontuação final: ${score}`;
  startButtonEl.disabled = false;
  feedbackMessageEl.textContent = "Jogo encerrado.";
  feedbackMessageEl.style.color = "black";

  sendScore(score);
}

async function sendScore(finalScore) {
  const userId = 1;

  try {
    const response = await fetch("salvar_pontuacao.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        user_id: userId,
        pontuacao: finalScore,
      }),
    });

    const result = await response.json();

    if (response.ok) {
      console.log("Resposta do servidor:", result.message);
      alert(`Pontuação salva com sucesso! ${result.message || ''}`);
    } else {
      throw new Error(result.message || `Erro do servidor: ${response.status}`);
    }

  } catch (error) {
    console.error("Erro ao enviar pontuação:", error);
    alert(`Erro ao salvar sua pontuação: ${error.message || 'Verifique o console para mais detalhes.'}`);
  }
}