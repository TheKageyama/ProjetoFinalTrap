<?php
require_once 'conexao.php';

// Funções utilitárias do meu projeto 

// --- AUTENTICAÇÃO ---
// Checa se o usuário está logado. Se não tiver, manda pro login.
function verificaLogin() {
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: ../public/login.php');
        exit();
    }
}

// Busca um usuário pelo ID (usado pra mostrar nome, perfil, etc)
function getUsuario($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// --- NOTÍCIAS ---
// Busca várias notícias, pode limitar e pesquisar por termo
function getNoticias($limit = null, $search = null) {
    global $pdo;
    $sql = "SELECT n.*, u.nome as autor_nome FROM noticias n 
            JOIN usuarios u ON n.autor = u.id 
            WHERE 1=1";
    $params = [];
    if ($search) {
        $sql .= " AND (n.titulo LIKE ? OR n.noticia LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    $sql .= " ORDER BY n.data DESC";
    if ($limit !== null) {
        $sql .= " LIMIT " . intval($limit);
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// Busca notícias só de um autor
function getNoticiasPorAutor($autor_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT n.*, u.nome as autor_nome 
                          FROM noticias n 
                          JOIN usuarios u ON n.autor = u.id 
                          WHERE n.autor = ? 
                          ORDER BY n.data DESC");
    $stmt->execute([$autor_id]);
    return $stmt->fetchAll();
}

// Busca uma notícia específica pelo ID
function getNoticia($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT n.*, u.nome as autor_nome, u.bio as autor_bio 
                          FROM noticias n 
                          JOIN usuarios u ON n.autor = u.id 
                          WHERE n.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// --- ANÚNCIOS ---
// Busca anúncios (pode ser só os de destaque ou todos)
function getAnuncios($destaque = false, $limit = null) {
    global $pdo;
    $sql = "SELECT * FROM anuncios WHERE ativo = 1";
    if ($destaque) {
        $sql .= " AND destaque = 1";
    }
    $sql .= " ORDER BY RAND()";
    if ($limit !== null) {
        $sql .= " LIMIT " . intval($limit);
    }
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

// Busca um anúncio pelo ID
function getAnuncio($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM anuncios WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// --- UPLOAD DE IMAGEM ---
// Faz upload de imagem para a pasta certa, checa tamanho e formato
function uploadImagem($file, $pasta = 'noticias') {
    $diretorio = "../assets/images/" . $pasta . "/";
    if (!is_dir($diretorio)) {
        mkdir($diretorio, 0777, true);
    }
    $extensao = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $nomeUnico = uniqid('syn_') . '.' . $extensao;
    $caminhoCompleto = $diretorio . $nomeUnico;
    // Verifica se é imagem mesmo
    $check = getimagesize($file['tmp_name']);
    if ($check === false) {
        return ['success' => false, 'message' => 'Arquivo não é uma imagem'];
    }
    // Tamanho máximo 5MB
    if ($file['size'] > 5000000) {
        return ['success' => false, 'message' => 'Imagem muito grande (máx. 5MB)'];
    }
    // Só aceita jpg, png, gif, webp
    $formatosPermitidos = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($extensao, $formatosPermitidos)) {
        return ['success' => false, 'message' => 'Formato não permitido'];
    }
    if (move_uploaded_file($file['tmp_name'], $caminhoCompleto)) {
        return ['success' => true, 'filename' => $nomeUnico];
    } else {
        return ['success' => false, 'message' => 'Erro no upload'];
    }
}

// --- PREVISÃO DO TEMPO ---
// Busca a previsão do tempo usando a API do OpenWeather
function getWeather($cidade = 'São Paulo') {
    $apiKey = '96bdd413791da838f51f315b5cf7319f'; // Chave OpenWeather do usuário
    $url = "https://api.openweathermap.org/data/2.5/weather?q=$cidade&appid=$apiKey&units=metric&lang=pt_br";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

// --- BUSCA DE NOTÍCIAS ---
// Busca notícias por termo (usado na busca do site)
function buscarNoticias($termo) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT n.*, u.nome as autor_nome FROM noticias n
                          JOIN usuarios u ON n.autor = u.id
                          WHERE n.titulo LIKE ? OR n.noticia LIKE ?
                          ORDER BY n.data DESC");
    $stmt->execute(["%$termo%", "%$termo%"]);
    return $stmt->fetchAll();
}