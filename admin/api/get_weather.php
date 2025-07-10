<?php
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';

header('Content-Type: application/json');

$cidade = filter_input(INPUT_GET, 'city', FILTER_SANITIZE_STRING) ?? 'São Paulo';

try {
    $weatherData = getWeather($cidade);
    
    if ($weatherData) {
        echo json_encode($weatherData);
    } else {
        echo json_encode(['error' => 'Não foi possível obter dados do clima.']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}