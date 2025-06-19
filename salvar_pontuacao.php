<?php
session_start();
require_once 'src/db.php';

header('Content-Type: application/json');

// Verifica se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Método de requisição inválido. Use POST.'
    ]);
    exit;
}

// Lê e decodifica o corpo JSON da requisição
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Recupera user_id da sessão
$user_id = $_SESSION['user_id'] ?? null;
$pontuacao = $data['pontuacao'] ?? null;

// Valida os dados recebidos
if (!$user_id || !is_numeric($pontuacao)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Usuário não autenticado ou pontuação inválida.'
    ]);
    exit;
}

// Verifica conexão com o banco
if (!isset($conn) || $conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro de conexão com o banco de dados.'
    ]);
    exit;
}

// Prepara e executa a inserção da pontuação
$stmt = $conn->prepare("INSERT INTO scores (usuario_id, pontuacao) VALUES (?, ?)");

if (!$stmt) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao preparar a query: ' . $conn->error
    ]);
    exit;
}

$stmt->bind_param("ii", $user_id, $pontuacao);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Pontuação salva com sucesso!'
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao salvar pontuação: ' . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>
