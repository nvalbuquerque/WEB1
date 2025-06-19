<?php
session_start();

require_once 'src/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método de requisição inválido.']);
    http_response_code(405);
    exit;
}
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$user_id = $_SESSION['user_id'] ?? null;

$pontuacao = $data['pontuacao'] ?? null;

if (empty($user_id) || !is_numeric($pontuacao)) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos ou inválidos (ID do usuário ou pontuação ausente/inválido).']);
    http_response_code(400); 
    exit;
}

if (!isset($conn) || $conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Erro de conexão com o banco de dados. Verifique o arquivo db.php.']);
    http_response_code(500);
    exit;
}

$stmt = $conn->prepare("INSERT INTO scores (usuario_id, pontuacao) VALUES (?, ?)");

if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Erro na preparação da query SQL: ' . $conn->error]);
    http_response_code(500);
    exit;
}

$stmt->bind_param("ii", $user_id, $pontuacao);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Pontuação salva com sucesso!']);
    http_response_code(200);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar a pontuação no banco de dados: ' . $stmt->error]);
    http_response_code(500);
}

$stmt->close();

$conn->close();

?>