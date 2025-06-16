const words = [
  "espaco",
  "sideral",
  "universo",
  "galaxia",
  "estrela",
  "planeta",
  "lua",
  "sol",
  "orbita",
  "cometa",
  "asteroide",
  "meteoro",
  "atmosfera",
  "cosmo",
  "astronauta",
  "telescopio",
  "nave",
  "foguete",
  "satelite",
  "sonda",
  "gravidade",
  "orbita",
  "constelacao",
  "via lactea",
  "nebulosa",
  "quasar",
  "pulsar",
  "buraco negro",
  "exoplaneta",
  "sistemasolar",
  "extraterrestre",
  "exploracao",
  "colonizacao",
  "jupiter",
  "marte",
  "venus",
  "mercurio",
  "saturno",
  "urano",
  "netuno",
  "plutao",
  "estrelacadente",
  "cinturaodeasteroides",
  "bigbang",
  "buracodeverme",
  "materiaescura",
  "energiaescura",
  "velocidadedaluz",
];

let gameContainer;
let wordDisplay;
let guessInput;
let timerDisplay;
let scoreDisplay;
let startButton;

let currentWord = "";
let score = 0;
let timeLeft = 60;
let timerInterval;

document.addEventListener("DOMContentLoaded", () => {
  gameContainer = document.createElement("div");
  gameContainer.classList.add("game-container");

  wordDisplay = document.createElement("h2");
  wordDisplay.id = "word-display";
  wordDisplay.textContent = "Clique em Iniciar para começar!";

  guessInput = document.createElement("input");
  guessInput.type = "text";
  guessInput.id = "guess-input";
  guessInput.placeholder = "Digite a palavra aqui...";
  guessInput.disabled = true;

  timerDisplay = document.createElement("p");
  timerDisplay.id = "timer-display";
  timerDisplay.textContent = `Tempo: ${timeLeft}s`;

  scoreDisplay = document.createElement("p");
  scoreDisplay.id = "score-display";
  scoreDisplay.textContent = `Pontuação: ${score}`;

  startButton = document.createElement("button");
  startButton.id = "start-button";
  startButton.textContent = "Iniciar Jogo";
  startButton.addEventListener("click", startGame);

  gameContainer.append(
    wordDisplay,
    guessInput,
    timerDisplay,
    scoreDisplay,
    startButton
  );

  const playArea = document.querySelector(".retangulo");
  if (playArea) {
    playArea.appendChild(gameContainer);
  } else {
    document.body.appendChild(gameContainer);
  }

  guessInput.addEventListener("keypress", (event) => {
    if (event.key === "Enter") {
      checkGuess();
    }
  });
});

function getRandomWord() {
  const randomIndex = Math.floor(Math.random() * words.length);
  return words[randomIndex];
}

function startGame() {
  score = 0;
  timeLeft = 60;
  scoreDisplay.textContent = `Pontuação: ${score}`;
  timerDisplay.textContent = `Tempo: ${timeLeft}s`;
  guessInput.value = "";
  guessInput.disabled = false;
  guessInput.focus();

  startButton.disabled = true;
  nextWord();
  startTimer();
}

function nextWord() {
  currentWord = getRandomWord();
  wordDisplay.textContent = currentWord;
  guessInput.value = "";
}

function checkGuess() {
  const userGuess = guessInput.value.toLowerCase().trim();

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

    scoreDisplay.textContent = `Pontuação: ${score}`;
  } else {
  }
  scoreDisplay.textContent = `Pontuação: ${score}`; 
  nextWord();
}

function startTimer() {
  timerInterval = setInterval(() => {
    timeLeft--;
    timerDisplay.textContent = `Tempo: ${timeLeft}s`;

    if (timeLeft <= 0) {
      clearInterval(timerInterval);
      endGame();
    }
  }, 1000);
}

function endGame() {
  guessInput.disabled = true;
  wordDisplay.textContent = `Fim de jogo! Sua pontuação final: ${score}`;
  startButton.disabled = false;

  sendScore(score);
}

function sendScore(finalScore) {
  fetch("save_score.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `score=${finalScore}`,
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error(`Erro HTTP! Status: ${response.status}`);
      }
      return response.text();
    })
    .then((data) => {
      console.log(data);
      alert("Pontuação salva! Verifique o placar de líderes.");
    })
    .catch((error) => {
      console.error("Erro ao enviar pontuação:", error);
      alert("Erro ao salvar sua pontuação.");
    });
}