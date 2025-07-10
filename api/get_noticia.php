<?php
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';

header('Content-Type: application/json');

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    echo json_encode(['erro' => 'ID inválido']);
    exit;
}

$noticia = getNoticia($id);
if (!$noticia) {
    echo json_encode(['erro' => 'Notícia não encontrada']);
    exit;
}

echo json_encode($noticia);
