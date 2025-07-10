<?php
// Endpoint pra buscar um anúncio popup aleatório (usado pra mostrar pop-up de propaganda)
require_once 'conexao.php';
require_once 'funcoes.php';

// Busca um anúncio ativo e marcado como popup
$stmt = $pdo->prepare("SELECT * FROM anuncios WHERE ativo = 1 AND popup = 1 ORDER BY RAND() LIMIT 1");
$stmt->execute();
$anuncio = $stmt->fetch(PDO::FETCH_ASSOC);

// Retorna o anúncio em formato JSON (ou false se não tiver)
header('Content-Type: application/json');
echo json_encode($anuncio ? $anuncio : false);
?>
