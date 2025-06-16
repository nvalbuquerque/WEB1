<?php
$servername = "localhost";
$username = "root";
$password = "";
$port = 3307;
$dbname = "web1db";

$conn = new mysqli($servername, $username, $password, "", $port);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$result = $conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'");
if ($result->num_rows == 0) {
    if (!$conn->query("CREATE DATABASE `$dbname`")) {
        die("Erro ao criar banco: " . $conn->error);
    }
    echo "Banco criado com sucesso!<br>";
}
$conn->select_db($dbname);

$tables = [
    "ligatable" => "CREATE TABLE IF NOT EXISTS ligatable (
        idliga INT AUTO_INCREMENT PRIMARY KEY,
        nomeliga VARCHAR(100) NOT NULL,
        pontosliga INT DEFAULT 0,
        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "cadastrotable" => "CREATE TABLE IF NOT EXISTS cadastrotable (
        idusuario INT AUTO_INCREMENT PRIMARY KEY,
        nomeusuario VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        senha VARCHAR(255) NOT NULL,
        idliga INT,
        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (idliga) REFERENCES ligatable(idliga)
    )",
    "historicotable" => "CREATE TABLE IF NOT EXISTS historicotable (
        idhistorico INT AUTO_INCREMENT PRIMARY KEY,
        idusuario INT NOT NULL,
        idpartida INT NOT NULL,
        tempo_jogo TIME NOT NULL,
        pontuacaoPartida INT DEFAULT 0,
        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (idusuario) REFERENCES cadastrotable(idusuario)
    )"
];

foreach ($tables as $sql) {
    $conn->query($sql);
}

echo "Banco e tabelas prontos.";
$conn->close();
?>
