<?php
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';

verificaLogin();

$usuario_id = $_SESSION['usuario_id'];
$noticias = getNoticiasPorAutor($usuario_id);
$usuario = getUsuario($usuario_id);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Synawrld Underground</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Urbanist:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="container header-container">
            <h1 class="logo">SYNAWRLD</h1>
            <nav class="nav">
                <a href="../public/index.php" class="btn btn-home"><i class="fas fa-home"></i> Home</a>
                <a href="dashboard.php" class="btn btn-dashboard active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="nova_noticia.php" class="btn btn-criar"><i class="fas fa-plus"></i> Nova Notícia</a>
                <a href="cadastrar_anuncio.php" class="btn btn-view"><i class="fas fa-bullhorn"></i> Anunciar</a>
                <a href="../public/logout.php" class="btn btn-delete"><i class="fas fa-sign-out-alt"></i> Sair</a>
            </nav>
            <button id="dark-mode-toggle" style="margin-left:20px;"><i class="fas fa-moon"></i></button>
        </div>
    </header>

    <main class="container">
        <div class="dashboard-grid">
            <section class="profile-section">
                <div class="profile-card">
                    <h2 style="font-size:2.2rem; margin-bottom:24px;">Seu Perfil</h2>
                    <div class="profile-info" style="font-size:1.15rem;">
                        <p><strong>Nome:</strong> <?= htmlspecialchars($usuario['nome']) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
                        <?php if (!empty($usuario['bio'])): ?>
                            <p><strong>Bio:</strong> <?= htmlspecialchars($usuario['bio']) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="profile-actions">
                        <a href="editar_usuario.php" class="btn btn-perfil"><i class="fas fa-user-edit"></i> Editar Perfil</a>
                        <a href="excluir_usuario.php" class="btn btn-delete"><i class="fas fa-user-times"></i> Excluir Conta</a>
                    </div>
                </div>
            </section>

            <section class="news-section">
                <h2 style="font-size:2rem; margin-bottom:24px;">Suas Notícias</h2>
                <?php if (count($noticias) > 0): ?>
                    <div class="news-grid" style="justify-content:center;">
                        <?php foreach ($noticias as $noticia): ?>
                            <article class="news-card">
                                <div class="news-header">
                                    <span class="news-category"><?= htmlspecialchars($noticia['categoria']) ?></span>
                                    <h3><?= htmlspecialchars($noticia['titulo']) ?></h3>
                                </div>
                                <div class="news-meta">
                                    <span><i class="fas fa-calendar-alt"></i> <?= date('d/m/Y', strtotime($noticia['data'])) ?></span>
                                    <span><i class="fas fa-eye"></i> 0 visualizações</span>
                                </div>
                                <div class="news-actions">
                                    <a href="../public/noticia.php?id=<?= $noticia['id'] ?>" class="btn btn-view"><i class="fas fa-eye"></i> Ver</a>
                                    <a href="editar_noticia.php?id=<?= $noticia['id'] ?>" class="btn btn-criar"><i class="fas fa-edit"></i> Editar</a>
                                    <a href="excluir_noticia.php?id=<?= $noticia['id'] ?>" class="btn btn-delete"><i class="fas fa-trash"></i> Excluir</a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-news">
                        <p>Você ainda não publicou nenhuma notícia.</p>
                        <a href="nova_noticia.php" class="btn btn-criar"><i class="fas fa-plus"></i> Criar Primeira Notícia</a>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>Synawrld Underground &copy; <?= date('Y') ?></p>
        </div>
    </footer>

    <script src="../assets/js/main.js"></script>
</body>
</html>