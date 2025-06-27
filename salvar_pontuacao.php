<?php
session_start();
require_once 'src/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['idusuario'])) {
    echo json_encode(['erro' => 'Usuário não autenticado']);
    exit;
}
$idusuario = $_SESSION['idusuario'];

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['pontuacao']) || !isset($data['tempo'])) {
    echo json_encode(['erro' => 'Dados incompletos']);
    exit;
}

$pontuacao = (int)$data['pontuacao'];
$tempo = $data['tempo'];
$idpartida = time();

// Criar tabela se não existir (sem usar prepare)
$conn->query("
    CREATE TABLE IF NOT EXISTS historicotable (
        idhistorico INT AUTO_INCREMENT PRIMARY KEY,
        idusuario INT NOT NULL,
        idpartida INT NOT NULL,
        tempo_jogo TIME NOT NULL,
        pontuacaoPartida INT DEFAULT 0,
        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (idusuario) REFERENCES cadastrotable(idusuario)
    )
");

$stmt = $conn->prepare("
    INSERT INTO historicotable (idusuario, idpartida, tempo_jogo, pontuacaoPartida)
    VALUES (?, ?, ?, ?)
");

if (!$stmt) {
    echo json_encode(['erro' => 'Erro no prepare: ' . $conn->error]);
    exit;
}

$stmt->bind_param("iisi", $idusuario, $idpartida, $tempo, $pontuacao);

if ($stmt->execute()) {
    echo json_encode(['sucesso' => true, 'mensagem' => 'Pontuação salva com sucesso!']);
} else {
    echo json_encode(['erro' => 'Erro ao salvar pontuação: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
