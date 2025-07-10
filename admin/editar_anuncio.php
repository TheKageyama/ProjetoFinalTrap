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
    $nome = trim($_POST['nome'] ?? '');
    $link = trim($_POST['link'] ?? '');
    $texto = trim($_POST['texto'] ?? '');
    $ativo = isset($_POST['ativo']) ? 1 : 0;
    $destaque = isset($_POST['destaque']) ? 1 : 0;
    $popup = isset($_POST['popup']) ? 1 : 0;
    $valor = floatval($_POST['valor'] ?? 0);

    $imagem = $anuncio['imagem'];
    
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadImagem($_FILES['imagem'], 'anuncios');
        if ($upload['success']) {
            // Remove a imagem antiga se existir
            if ($imagem) {
                $old_image_path = "../assets/images/anuncios/" . $imagem;
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }
            $imagem = $upload['filename'];
        } else {
            $erro = $upload['message'];
        }
    }
    
    if (isset($_POST['remover_imagem']) && $_POST['remover_imagem'] === 'on') {
        if ($imagem) {
            $old_image_path = "../assets/images/anuncios/" . $imagem;
            if (file_exists($old_image_path)) {
                unlink($old_image_path);
            }
        }
        $imagem = null;
    }
    
    if (empty($nome) || empty($link)) {
        $erro = "Nome e Link são obrigatórios!";
    } else {
        $stmt = $pdo->prepare("UPDATE anuncios SET nome = ?, imagem = ?, link = ?, texto = ?, ativo = ?, destaque = ?, popup = ?, valor = ? WHERE id = ?");
        
        if ($stmt->execute([$nome, $imagem, $link, $texto, $ativo, $destaque, $popup, $valor, $anuncio['id']])) {
            $_SESSION['sucesso'] = "Anúncio atualizado com sucesso!";
            header('Location: anuncios.php');
            exit();
        } else {
            $erro = "Erro ao atualizar anúncio. Tente novamente.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Anúncio | Synawrld Underground</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Urbanist:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="container header-container">
            <h1 class="logo">SYNAWRLD</h1>
            <nav class="nav main-nav">
                <a href="../public/index.php"><i class="fas fa-home"></i> Home</a>
                <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="nova_noticia.php"><i class="fas fa-plus"></i> Nova Notícia</a>
                <a href="anuncios.php"><i class="fas fa-bullhorn"></i> Anúncios</a>
                <a href="../public/logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
            </nav>
            <button id="dark-mode-toggle" style="margin-left:20px;"><i class="fas fa-moon"></i></button>
        </div>
    </header>
</html>
<script src="../assets/js/main.js"></script>

    <main class="admin-container">
        <section class="cadastro-section">
            <h2>Editar Anúncio</h2>
            
            <?php if (isset($erro)): ?>
                <div class="alert alert-error"><?= $erro ?></div>
            <?php elseif (isset($_SESSION['sucesso'])): ?>
                <div class="alert alert-success"><?= $_SESSION['sucesso'] ?></div>
                <?php unset($_SESSION['sucesso']); ?>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nome">Nome do Anúncio*</label>
                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($anuncio['nome']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="imagem">Imagem</label>
                    <input type="file" id="imagem" name="imagem" accept="image/*">
                    <?php if ($anuncio['imagem']): ?>
                        <p>Imagem atual: <a href="../assets/images/anuncios/<?= $anuncio['imagem'] ?>" target="_blank"><?= $anuncio['imagem'] ?></a></p>
                        <label><input type="checkbox" name="remover_imagem"> Remover imagem</label>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="link">Link de Destino*</label>
                    <input type="url" id="link" name="link" value="<?= htmlspecialchars($anuncio['link']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="texto">Texto do Anúncio</label>
                    <textarea id="texto" name="texto"><?= htmlspecialchars($anuncio['texto']) ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="valor">Valor do Anúncio (R$)</label>
                    <input type="number" id="valor" name="valor" step="0.01" min="0" 
                           value="<?= htmlspecialchars($anuncio['valor']) ?>">
                </div>
                
                <div class="form-check">
                    <input type="checkbox" id="ativo" name="ativo" value="1" <?= $anuncio['ativo'] ? 'checked' : '' ?>>
                    <label for="ativo">Ativo</label>
                </div>
                
                <div class="form-check">
                    <input type="checkbox" id="destaque" name="destaque" value="1" <?= $anuncio['destaque'] ? 'checked' : '' ?>>
                    <label for="destaque">Destaque</label>
                </div>
                
                <div class="form-check">
                    <input type="checkbox" id="popup" name="popup" value="1" <?= $anuncio['popup'] ? 'checked' : '' ?>>
                    <label for="popup">Exibir como pop-up</label>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    <a href="anuncios.php" class="btn btn-secondary">Cancelar</a>
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