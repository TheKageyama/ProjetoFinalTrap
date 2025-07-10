<?php
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';

verificaLogin();

if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit();
}

$noticia = getNoticia($_GET['id']);

if (!$noticia || $noticia['autor'] != $_SESSION['usuario_id']) {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $conteudo = trim($_POST['conteudo'] ?? '');
    
    $imagem = $noticia['imagem'];
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadImagem($_FILES['imagem']);
        if ($upload['success']) {
            // Remove a imagem antiga se existir
            if ($imagem) {
                $old_image_path = "../assets/images/uploads/" . $imagem;
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
            $old_image_path = "../assets/images/uploads/" . $imagem;
            if (file_exists($old_image_path)) {
                unlink($old_image_path);
            }
        }
        $imagem = null;
    }
    
    if (empty($titulo) || empty($categoria) || empty($conteudo)) {
        $erro = "Preencha todos os campos obrigatórios!";
    } else {
        $stmt = $pdo->prepare("UPDATE noticias SET titulo = ?, categoria = ?, noticia = ?, imagem = ? WHERE id = ?");
        if ($stmt->execute([$titulo, $categoria, $conteudo, $imagem, $noticia['id']])) {
            $_SESSION['sucesso'] = "Notícia atualizada com sucesso!";
            header('Location: dashboard.php');
            exit();
        } else {
            $erro = "Erro ao atualizar notícia. Tente novamente.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Notícia | Synawrld Underground</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;700&family=Bebas+Neue&display=swap" rel="stylesheet">
</head>
<body>
    <header class="main-header">
        <div class="container header-container">
            <h1 class="logo">SYNAWRLD</h1>
            <span class="sublogo">UNDERGROUND CULTURE</span>
            <nav class="main-nav" style="gap:18px;">
                <a href="../public/index.php" class="main-nav-btn"><i class="fas fa-home"></i> Home</a>
                <a href="dashboard.php" class="main-nav-btn"><i class="fas fa-gauge"></i> Dashboard</a>
                <a href="nova_noticia.php" class="main-nav-btn"><i class="fas fa-plus"></i> Nova Notícia</a>
                <a href="cadastrar_anuncio.php" class="main-nav-btn"><i class="fas fa-bullhorn"></i> Anunciar</a>
                <a href="editar_usuario.php" class="main-nav-btn"><i class="fas fa-user-edit"></i> Editar Perfil</a>
                <a href="../public/logout.php" class="main-nav-btn"><i class="fas fa-sign-out-alt"></i> Sair</a>
            </nav>
            <button id="dark-mode-toggle" style="margin-left:20px;"><i class="fas fa-moon"></i></button>
            <button class="mobile-menu-toggle"><i class="fas fa-bars"></i></button>
        </div>
    </header>

    <main class="container">
        <section class="profile-card" style="margin:40px auto 32px auto;max-width:600px;background:var(--medium-gray);border-radius:18px;box-shadow:0 6px 32px #0007;padding:48px 36px 36px 36px;">
            <h2 style="text-align:center;font-size:2.1rem;color:var(--primary);margin-bottom:18px;letter-spacing:1px;">Editar Notícia</h2>
            <?php if (isset($erro)): ?>
                <div class="alert alert-error" style="background:#ff2d2d22;color:#ff2d2d;padding:12px 18px;border-radius:8px;margin-bottom:18px;text-align:center;font-weight:700;">
                    <?= $erro ?>
                </div>
            <?php elseif (isset($_SESSION['sucesso'])): ?>
                <div class="alert alert-success" style="background:#1db95422;color:#1db954;padding:12px 18px;border-radius:8px;margin-bottom:18px;text-align:center;font-weight:700;">
                    <?= $_SESSION['sucesso'] ?>
                </div>
                <?php unset($_SESSION['sucesso']); ?>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:22px;">
                <div class="form-group" style="display:flex;flex-direction:column;gap:8px;">
                    <label for="titulo" style="font-size:1.1rem;font-weight:700;color:var(--primary);">Título</label>
                    <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($noticia['titulo']) ?>" required style="font-size:1.15rem;padding:16px 18px;border-radius:10px;border:2px solid var(--primary);background:var(--dark);color:var(--light);outline:none;">
                </div>
                <div class="form-group" style="display:flex;flex-direction:column;gap:8px;">
                    <label for="categoria" style="font-size:1.1rem;font-weight:700;color:var(--primary);">Categoria</label>
                    <select id="categoria" name="categoria" required style="font-size:1.1rem;padding:14px 16px;border-radius:10px;border:2px solid var(--primary);background:var(--dark);color:var(--light);outline:none;">
                        <option value="">Selecione...</option>
                        <option value="Música" <?= $noticia['categoria'] === 'Música' ? 'selected' : '' ?>>Música</option>
                        <option value="Arte" <?= $noticia['categoria'] === 'Arte' ? 'selected' : '' ?>>Arte</option>
                        <option value="Cinema" <?= $noticia['categoria'] === 'Cinema' ? 'selected' : '' ?>>Cinema</option>
                        <option value="Literatura" <?= $noticia['categoria'] === 'Literatura' ? 'selected' : '' ?>>Literatura</option>
                        <option value="Eventos" <?= $noticia['categoria'] === 'Eventos' ? 'selected' : '' ?>>Eventos</option>
                        <option value="Hip-Hop" <?= $noticia['categoria'] === 'Hip-Hop' ? 'selected' : '' ?>>Hip-Hop</option>
                        <option value="Street Art" <?= $noticia['categoria'] === 'Street Art' ? 'selected' : '' ?>>Street Art</option>
                    </select>
                </div>
                <div class="form-group" style="display:flex;flex-direction:column;gap:8px;">
                    <label for="imagem" style="font-size:1.1rem;font-weight:700;color:var(--primary);">Imagem (opcional)</label>
                    <input type="file" id="imagem" name="imagem" accept="image/*" style="font-size:1.05rem;padding:10px 0;background:none;color:var(--light);border:none;">
                    <?php if ($noticia['imagem']): ?>
                        <p style="font-size:0.98rem;color:var(--light-gray);margin:6px 0 0 0;">Imagem atual: <a href="../assets/images/uploads/<?= $noticia['imagem'] ?>" target="_blank" style="color:var(--primary);font-weight:700;"><?= $noticia['imagem'] ?></a></p>
                        <label style="font-size:0.98rem;color:var(--primary);margin-top:4px;"><input type="checkbox" name="remover_imagem"> Remover imagem</label>
                    <?php endif; ?>
                </div>
                <div class="form-group" style="display:flex;flex-direction:column;gap:8px;">
                    <label for="conteudo" style="font-size:1.1rem;font-weight:700;color:var(--primary);">Conteúdo</label>
                    <textarea id="conteudo" name="conteudo" rows="10" required style="font-size:1.1rem;padding:14px 16px;border-radius:10px;border:2px solid var(--primary);background:var(--dark);color:var(--light);outline:none;resize:vertical;min-height:180px;"><?= htmlspecialchars($noticia['noticia']) ?></textarea>
                </div>
                <div class="form-actions" style="display:flex;gap:24px;justify-content:center;margin-top:18px;">
                    <button type="submit" class="btn btn-primary" style="font-size:1.15rem;padding:18px 38px;border-radius:8px;font-weight:700;letter-spacing:1px;box-shadow:0 2px 12px #0003;cursor:pointer;text-transform:uppercase;background:var(--primary);color:#fff;">Salvar Alterações</button>
                    <a href="dashboard.php" class="btn btn-secondary" style="font-size:1.15rem;padding:18px 38px;border-radius:8px;font-weight:700;letter-spacing:1px;box-shadow:0 2px 12px #0003;cursor:pointer;text-transform:uppercase;background:#222;color:#FFD700;border:2px solid #FFD700;">Cancelar</a>
                </div>
            </form>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>Synawrld Underground &copy; <?= date('Y') ?> - Todas as vozes da rua</p>
        </div>
    </footer>
</body>
</html>