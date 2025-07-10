
<?php
// Arquivo pra proteger páginas que só podem ser acessadas logado
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start(); // Garante que a sessão está ativa
}
require_once 'funcoes.php';

// Se não estiver logado, manda pro login
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../public/login.php');
    exit();
}
?>