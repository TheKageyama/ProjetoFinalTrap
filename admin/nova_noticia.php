<?php
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';

verificaLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $conteudo = trim($_POST['conteudo'] ?? '');
    $autor = $_SESSION['usuario_id'];
    
    $imagem = null;
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadImagem($_FILES['imagem']);
        if ($upload['success']) {
            $imagem = $upload['filename'];
        } else {
            $erro = $upload['message'];
        }
    }
    
    if (empty($titulo) || empty($categoria) || empty($conteudo) || empty($imagem)) {
        $erro = "Todos os campos são obrigatórios, incluindo a imagem!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO noticias (titulo, categoria, noticia, imagem, autor, data) VALUES (?, ?, ?, ?, ?, NOW())");
        if ($stmt->execute([$titulo, $categoria, $conteudo, $imagem, $autor])) {
            $_SESSION['sucesso'] = "Notícia publicada com sucesso!";
            header('Location: dashboard.php');
            exit();
        } else {
            $erro = "Erro ao publicar notícia. Tente novamente.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Notícia | Synawrld Underground</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Urbanist:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="container header-container">
            <h1 class="logo">SYNAWRLD</h1>
            <nav class="nav">
                <a href="../public/index.php">Home</a>
                <a href="dashboard.php">Dashboard</a>
                <a href="nova_noticia.php" class="active">Nova Notícia</a>
                <a href="cadastrar_anuncio.php">Anunciar</a>
                <a href="../public/logout.php">Sair</a>
            </nav>
            <button id="dark-mode-toggle" style="margin-left:20px;"><i class="fas fa-moon"></i></button>
        </div>
    </header>

    <main class="admin-container">
        <section class="cadastro-section">
            <h2>Nova Notícia</h2>
            
            <?php if (isset($erro)): ?>
                <div class="alert alert-error"><?= $erro ?></div>
            <?php endif; ?>
            
            <form class="form-noticia" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="titulo">Título*</label>
                    <input type="text" id="titulo" name="titulo" required>
                </div>
                
                <div class="form-group">
                    <label for="categoria">Categoria*</label>
                    <select id="categoria" name="categoria" required>
                        <option value="">Selecione...</option>
                        <option value="Música">Música</option>
                        <option value="Arte">Arte</option>
                        <option value="Cinema">Cinema</option>
                        <option value="Literatura">Literatura</option>
                        <option value="Eventos">Eventos</option>
                        <option value="Hip-Hop">Hip-Hop</option>
                        <option value="Street Art">Street Art</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="imagem">Imagem*</label>
                    <input type="file" id="imagem" name="imagem" accept="image/*" required>
                    <small>Formatos: JPG, PNG (Máx. 5MB)</small>
                </div>
                
                <div class="form-group">
                    <label for="conteudo">Conteúdo*</label>
                    <textarea id="conteudo" name="conteudo" rows="10" required></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Publicar</button>
                    <a href="dashboard.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>Synawrld Underground &copy; <?= date('Y') ?></p>
        </div>
    </footer>
</body>
</html>