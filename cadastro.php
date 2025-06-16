<?php
require_once 'validate.php';
require_once 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$result = $conn->query("SHOW TABLES LIKE 'cadastrotable'");
if ($result->num_rows == 0) {
    die("A tabela cadastrotable não existe!");
}

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$existencia = true;
$erroNome = $erroEmail = $erroSenha = $erroConfirmarSenha = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $confirmarSenha = $_POST['confirmar_senha'] ?? '';

    $erroNome = verifica_Nulo($nome, $existencia);
    $erroEmail = verifica_Nulo(verifica_Campo($email), $existencia);
    $erroSenha = verifica_Nulo($senha, $existencia);
    $erroConfirmarSenha = verifica_Nulo($confirmarSenha, $existencia);

    if ($erroConfirmarSenha === '' && $senha !== $confirmarSenha) {
        $erroConfirmarSenha = "* As senhas não coincidem.";
        $existencia = false;
    }

    if ($erroNome !== '' || $erroEmail !== '' || $erroSenha !== '') {
        $existencia = false;
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p class='erro'>* Email inválido.</p>";
        $existencia = false;
    }

    if ($existencia) {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO cadastrotable (nomeusuario, email, senha, idliga) VALUES (?, ?, ?, NULL)");
        $stmt->bind_param("sss", $nome, $email, $senhaHash);

        if ($stmt->execute() === false) {
            if ($conn->errno == 1062) {
                echo "<p class='erro'>Este email já está cadastrado!</p>";
            } else {
                echo "<p class='erro'>Erro ao cadastrar: " . $conn->error . "</p>";
            }
        } else {
            echo "<script>
                    setTimeout(function() {
                        alert('Cadastro realizado com sucesso! Você será redirecionado para a tela de início.');
                        window.location.href = 'TelaInicio.php';
                    }, 2000);
                </script>";
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="csspadrao.css">
</head>
<body>
    <div class="background"></div>
    <div class="retangulo">
        <h1>Cadastro</h1>

        <form method="POST" action="">
            <div id="texto">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" required 
                        value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : '' ?>">
                    <?php if(isset($erroNome) && $erroNome !== '') echo "<p class='erro'>$erroNome</p>"; ?>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required 
                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                    <?php if(isset($erroEmail) && $erroEmail !== '') echo "<p class='erro'>$erroEmail</p>"; ?>
                </div>

                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" required>
                    <?php if(isset($erroSenha) && $erroSenha !== '') echo "<p class='erro'>$erroSenha</p>"; ?>
                </div>

                <div class="form-group">
                    <label for="confirmar_senha">Confirmar senha</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha" required>
                    <?php if(isset($erroConfirmarSenha) && $erroConfirmarSenha !== '') echo "<p class='erro'>$erroConfirmarSenha</p>"; ?>
                </div>

                <div class="BotaoCadastrar">
                    <button type="submit" id="Botao">Cadastrar</button>
                </div>
            </div>
        </form>

        <div class="BotaoEntrar">
            <h5>Já tem cadastro?</h5>
            <button type="button" id="Botao" onclick="window.location.href='login.php'">Entrar</button>
        </div>
    </div>
</body>
</html>
