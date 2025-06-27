<?php
session_start();
require_once 'src/db.php';

if (!isset($_SESSION['idusuario'])) {
    header("Location: login.php");
    exit;
}

$itensPorPagina = 3;
$paginaAtual = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$pesquisa = isset($_GET['pesquisa']) ? trim($conn->real_escape_string($_GET['pesquisa'])) : '';
$filtroSQL = '';
if ($pesquisa !== '') {
    if (is_numeric($pesquisa)) {
        $filtroSQL = "WHERE idliga = $pesquisa OR nomeliga LIKE '%$pesquisa%'";
    } else {
        $filtroSQL = "WHERE nomeliga LIKE '%$pesquisa%'";
    }
}

$offset = ($paginaAtual - 1) * $itensPorPagina;

$sqlCount = "SELECT COUNT(*) as total FROM ligatable $filtroSQL";
$resultCount = $conn->query($sqlCount);
$totalRegistros = 0;
if ($resultCount) {
    $rowCount = $resultCount->fetch_assoc();
    $totalRegistros = (int)$rowCount['total'];
}
$totalPaginas = ceil($totalRegistros / $itensPorPagina);

$sql = "SELECT idliga, nomeliga, pontosliga, criado_em 
        FROM ligatable 
        $filtroSQL 
        ORDER BY pontosliga DESC 
        LIMIT $itensPorPagina OFFSET $offset";
$result = $conn->query($sql);

if (!$result) {
    die("Erro na consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Ligas</title>
  <link rel="stylesheet" href="CSS/ligas.css" />
</head>
<body>

<div class="background"></div>
<div class="retangulo">
  <h1>Ligas</h1>
  <form method="GET">
    <input type="text" name="pesquisa" id="barraPesquisa" placeholder="Pesquisar por nome ou ID" value="<?php echo htmlspecialchars($pesquisa); ?>"/>
    <button type="submit" id="botaoBuscar">Buscar</button>
    <a href="ligas.php">Limpar</a>
  </form>
  <?php if ($totalRegistros > 0): ?>
    <div class="tabela-container">
      <table id="tabelaLigas" class="tabela-clara">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Pontos</th>
            <th>Criada em</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['idliga']); ?></td>
              <td><?php echo htmlspecialchars($row['nomeliga']); ?></td>
              <td><?php echo htmlspecialchars($row['pontosliga']); ?></td>
              <td><?php echo htmlspecialchars($row['criado_em']); ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
    <div class="paginacao">
      <?php if ($paginaAtual > 1): ?>
        <a href="?page=<?php echo $paginaAtual - 1; ?>&pesquisa=<?php echo urlencode($pesquisa); ?>" class="botaoPagina">Anterior</a>
      <?php endif; ?>

      Página <?php echo $paginaAtual; ?> de <?php echo $totalPaginas; ?>

      <?php if ($paginaAtual < $totalPaginas): ?>
        <a href="?page=<?php echo $paginaAtual + 1; ?>&pesquisa=<?php echo urlencode($pesquisa); ?>" class="botaoPagina">Próximo</a>
      <?php endif; ?>
    </div>
  <?php else: ?>
    <p class="mensagem-vazia" style="text-align: center; margin-top: 20px;">Nenhuma liga encontrada.</p>
  <?php endif; ?>

  <div class="Botoes" style="margin-top: 25px;">
    <button id="BotaoNovaLiga" onclick="window.location.href='cadastroligass.php'">Nova liga</button>
    <button id="BotaoVoltar" onclick="window.location.href='TelaInicio.php'">Voltar</button>
  </div>
</div>

</body>
</html>

<?php
$conn->close();
?>
