<?php
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';

verificaLogin();

if (!isset($_GET['id'])) {
    header('Location: anuncios.php');
    exit();
}

$anuncio = getAnuncio($_GET['id']);

if (!$anuncio) {
    header('Location: anuncios.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Remove a imagem associada se existir
    if ($anuncio['imagem']) {
        $imagem_path = "../assets/images/anuncios/" . $anuncio['imagem'];
        if (file_exists($imagem_path)) {
            unlink($imagem_path);
        }
    }
    
    // Exclui o anúncio do banco de dados
    $stmt = $pdo->prepare("DELETE FROM anuncios WHERE id = ?");
    
    if ($stmt->execute([$anuncio['id']])) {
        $_SESSION['sucesso'] = "Anúncio excluído com sucesso!";
        header('Location: anuncios.php');
        exit();
    } else {
        $erro = "Erro ao excluir anúncio. Tente novamente.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Anúncio | Synawrld Underground</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;700&family=Bebas+Neue&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="container">
            <h1 class="logo">SYNAWRLD</h1>
            <nav class="nav">
                <a href="../public/index.php">Home</a>
                <a href="dashboard.php">Dashboard</a>
                <a href="nova_noticia.php">Nova Notícia</a>
                <a href="anuncios.php">Anúncios</a>
                <a href="../public/logout.php">Sair</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <section class="delete-section">
            <h2>Excluir Anúncio</h2>
            
            <?php if (isset($erro)): ?>
                <div class="alert alert-error"><?= $erro ?></div>
            <?php endif; ?>
            
            <div class="delete-confirm">
                <p>Tem certeza que deseja excluir o anúncio <strong>"<?= htmlspecialchars($anuncio['nome']) ?>"</strong>?</p>
                
                <?php if ($anuncio['imagem']): ?>
                <div class="anuncio-imagem-preview">
                    <img src="../assets/images/anuncios/<?= $anuncio['imagem'] ?>" alt="<?= htmlspecialchars($anuncio['nome']) ?>">
                </div>
                <?php endif; ?>
                
                <div class="anuncio-info">
                    <p><strong>Link:</strong> <?= htmlspecialchars($anuncio['link']) ?></p>
                    <p><strong>Valor:</strong> R$ <?= number_format($anuncio['valor'], 2, ',', '.') ?></p>
                    <p><strong>Status:</strong> <?= $anuncio['ativo'] ? 'Ativo' : 'Inativo' ?></p>
                    <p><strong>Destaque:</strong> <?= $anuncio['destaque'] ? 'Sim' : 'Não' ?></p>
                    <p><strong>Popup:</strong> <?= $anuncio['popup'] ? 'Sim' : 'Não' ?></p>
                </div>
                
                <p class="warning-text">Esta ação não pode ser desfeita e a imagem associada será permanentemente removida.</p>
                
                <form method="POST">
                    <div class="form-actions">
                        <button type="submit" class="btn-confirm-delete">Confirmar Exclusão</button>
                        <a href="anuncios.php" class="btn-cancel">Cancelar</a>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>Synawrld Underground &copy; <?= date('Y') ?></p>
        </div>
    </footer>
</body>
</html>