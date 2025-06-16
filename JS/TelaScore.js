const BotaoPontuacao = document.getElementById("BotaoPontuacao");
const BotaoJogarNovamente = document.getElementById("BotaoJogarNovamente");
const BotaoSair = document.getElementById("BotaoSair");


BotaoPontuacao.addEventListener("click", function(event) {
 window.location.href = "TelaClassif.html";  /*MUDAR ARQUIVO PARA PÁGINA DE PONTUAÇÃO*/
});

BotaoJogarNovamente.addEventListener("click", function(event) {
 window.location.href = "https://www.w3schools.com/css/css_colors.asp";    /*MUDAR ARQUIVO PARA PÁGINA DO JOGO*/
});

BotaoSair.addEventListener("click", function(event) {
 window.location.href = "TelaInicio.html"; /*MUDAR ARQUIVO PARA PÁGINA DE ÍNICIO*/
});

function mostrarTela(tela) {
      document.getElementById("telaScore").style.display = (tela === 'score') ? 'block' : 'none';
      document.getElementById("telaInicio").style.display = (tela === 'Inicio') ? 'block' : 'none';
    }
