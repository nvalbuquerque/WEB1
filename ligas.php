<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['idusuario'])) {
    header("Location: login.php");
    exit;
}

$sql = "SELECT idliga, nomeliga, pontosliga, criado_em FROM ligatable ORDER BY pontosliga DESC";
$result = $conn->query($sql);

if (!$result) {
    die("Erro na consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Ligas</title>
    <link rel="stylesheet" href="csspadrao.css">
</head>
<body>

<div class="background"></div>

<div class="retangulo">
    <h1>Ligas</h1>

    <input type="text" id="barraPesquisa" placeholder="Pesquisar ligas">

    <?php if ($result->num_rows > 0): ?>
        <table id="tabelaLigas" border="1" cellpadding="6" cellspacing="0">
            <thead>
                <tr>
                    <th id="texto">ID</th>
                    <th id="texto">Nome</th>
                    <th id="texto">Pontos</th>
                    <th id="texto">Criada em</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr id="textotabela">
                        <td><?php echo htmlspecialchars($row['idliga']); ?></td>
                        <td><?php echo htmlspecialchars($row['nomeliga']); ?></td>
                        <td><?php echo htmlspecialchars($row['pontosliga']); ?></td>
                        <td><?php echo htmlspecialchars($row['criado_em']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhuma liga encontrada.</p>
    <?php endif; ?>

    <div id="Botoes">
    <button id="BotaoLigas" onclick="window.location.href='cadastroligas.php'">Nova liga</button>
    <button id="BotaoLigas" onclick="window.location.href='logout.php'">Sair</button>
    </div>
    
</div>

<script>
    const barraPesquisa = document.getElementById('barraPesquisa');
    barraPesquisa.addEventListener('keyup', function() {
        const filtro = barraPesquisa.value.toLowerCase();
        const tabela = document.getElementById('tabelaLigas');
        const linhas = tabela.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        for (let i = 0; i < linhas.length; i++) {
            const colunaNome = linhas[i].getElementsByTagName('td')[1];
            if (colunaNome) {
                const textoColuna = colunaNome.textContent || colunaNome.innerText;
                if (textoColuna.toLowerCase().indexOf(filtro) > -1) {
                    linhas[i].style.display = '';
                } else {
                    linhas[i].style.display = 'none';
                }
            }
        }
    });
</script>

</body>
</html>

<?php
$conn->close();
?>
