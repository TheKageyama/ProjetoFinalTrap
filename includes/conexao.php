<?php
// Conexão com o banco de dados (PDO)
session_start(); // Inicia a sessão pra login, etc

$host = 'localhost'; // Host do banco (normalmente localhost)
$dbname = 'Synawrld_news'; // Nome do banco de dados
$username = 'root'; // Usuário do banco (padrão do XAMPP)
$password = '';

try {
    // Cria a conexão PDO 
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Mostra erro bonito
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Retorna arrays associativos
} catch (PDOException $e) {
    // Se der ruim na conexão, mostra o erro e para tudo
    die("Erro na conexão: " . $e->getMessage());
}

// Garante que datas e horas vão estar sempre no fuso de SP
date_default_timezone_set('America/Sao_Paulo');
?>