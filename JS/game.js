let tempoRestante = 60;
let score = 0;
let paragrafoAtual = '';
let autorAtual = '';
let indiceLetra = 0;
let paragrafos = [];

const textoElem = document.getElementById('texto');
const autorElem = document.getElementById('autor');
const inputElem = document.getElementById('inputTexto');
const timerElem = document.getElementById('timer');
const scoreElem = document.getElementById('score');

fetch('JS/words.json')
  .then(res => res.json())
  .then(data => {
    paragrafos = data.paragrafos;
    sortearParagrafo();
    startTimer();
  });

function sortearParagrafo() {
  const aleatorio = Math.floor(Math.random() * paragrafos.length);
  paragrafoAtual = paragrafos[aleatorio].texto;
  autorAtual = paragrafos[aleatorio].autor;

  textoElem.innerText = paragrafoAtual;
  autorElem.innerText = `— ${autorAtual}`;

  inputElem.value = '';
  indiceLetra = 0;
}

function startTimer() {
  const interval = setInterval(() => {
    tempoRestante--;
    timerElem.innerText = `Tempo: ${tempoRestante}s`;

    if (tempoRestante <= 0) {
      clearInterval(interval);
      inputElem.disabled = true;

      const tempoFormatado = new Date(60 * 1000).toISOString().substr(11, 8);

      fetch('salvar_pontuacao.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          pontuacao: score,
          tempo: tempoFormatado
        })
      })
      .then(res => res.json())
      .then(data => {
        // Redireciona para score.php após salvar
        window.location.href = 'TelaScore.php';
      })
      .catch(() => {
        // Redireciona mesmo se ocorrer erro no salvamento
        window.location.href = 'TelaScore.php';
      });
    }
  }, 1000);
}

inputElem.addEventListener('input', () => {
  const input = inputElem.value;
  const esperado = paragrafoAtual[indiceLetra];

  if (input.at(-1) === esperado) {
    score++;
    indiceLetra++;
    scoreElem.innerText = `Pontuação: ${score}`;

    if (indiceLetra >= paragrafoAtual.length) {
      sortearParagrafo();
    }
  } else {
    inputElem.value = input.slice(0, -1);
  }
});
