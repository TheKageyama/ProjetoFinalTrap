<?php
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';

$termo = $_GET['q'] ?? '';
$resultados = buscarNoticias($termo);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Busca | Synawrld Underground</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Urbanist:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="main-header">
        <div class="container header-container">
            <h1 class="logo">SYNAWRLD</h1>
            <span class="sublogo">UNDERGROUND CULTURE</span>
            <nav class="main-nav" style="gap:18px;">
                <a href="index.php" class="main-nav-btn"><i class="fas fa-home"></i> Home</a>
                <a href="#" class="main-nav-btn"><i class="fas fa-microphone-lines"></i> Hip-Hop</a>
                <a href="#" class="main-nav-btn"><i class="fas fa-spray-can"></i> Street Art</a>
                <a href="#" class="main-nav-btn"><i class="fas fa-calendar-alt"></i> Eventos</a>
                <a href="#" class="main-nav-btn"><i class="fas fa-comments"></i> Entrevistas</a>
                <a href="#" class="main-nav-btn"><i class="fas fa-images"></i> Galeria</a>
                <a href="busca.php" class="main-nav-btn active"><i class="fas fa-search"></i> Pesquisa</a>
                <a href="previsao.php" class="main-nav-btn"><i class="fas fa-cloud-sun"></i> Previsão do Tempo</a>
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <a href="../admin/dashboard.php" class="main-nav-btn"><i class="fas fa-user"></i> Painel</a>
                    <a href="../public/logout.php" class="main-nav-btn"><i class="fas fa-sign-out-alt"></i> Sair</a>
                <?php else: ?>
                    <a href="login.php" class="main-nav-btn"><i class="fas fa-sign-in-alt"></i> Login</a>
                    <a href="cadastro.php" class="main-nav-btn"><i class="fas fa-user-plus"></i> Cadastro</a>
                <?php endif; ?>
            </nav>
            <button id="dark-mode-toggle" style="margin-left:20px;"><i class="fas fa-moon"></i></button>
            <button class="mobile-menu-toggle"><i class="fas fa-bars"></i></button>
        </div>
    </header>

    <div class="search-container" style="background: var(--medium-gray);padding: 36px 0 18px 0;">
        <div class="container search-box" style="max-width: 600px; margin: 0 auto;">
            <form action="busca.php" method="get" style="display:flex;gap:12px;align-items:center;">
                <input type="text" name="q" placeholder="Buscar notícias da rua..." value="<?= htmlspecialchars($termo) ?>" style="flex:1;font-size:1.25rem;padding:18px 20px;border-radius:10px;border:2px solid var(--primary);background:var(--dark);color:var(--light);outline:none;">
                <button type="submit" style="font-size:1.15rem;padding:18px 32px;border-radius:8px;background:var(--primary);color:#fff;font-weight:700;letter-spacing:1px;border:none;cursor:pointer;transition:var(--transition);"><i class="fas fa-search"></i> Pesquisar</button>
            </form>
        </div>
    </div>

    <main class="container">
        <h2 class="section-title" style="font-size:2rem;text-align:center;margin-bottom:32px;"><span>Resultados para: "<?= htmlspecialchars($termo) ?>"</span></h2>
        
        <?php if (count($resultados) > 0): ?>
        <div class="news-grid">
            <?php foreach ($resultados as $noticia): ?>
            <article class="news-card" style="box-shadow:0 4px 24px #0004;">
                <a href="noticia.php?id=<?= $noticia['id'] ?>" class="news-image-link">
                    <?php
                    $imgPath = '';
                    if (!empty($noticia['imagem'])) {
                        if (file_exists(__DIR__ . '/../assets/images/noticias/' . $noticia['imagem'])) {
                            $imgPath = '/NoticiasTrap-main/assets/images/noticias/' . htmlspecialchars($noticia['imagem']);
                        } elseif (file_exists(__DIR__ . '/../assets/images/uploads/' . $noticia['imagem'])) {
                            $imgPath = '/NoticiasTrap-main/assets/images/uploads/' . htmlspecialchars($noticia['imagem']);
                        }
                    }
                    ?>
                    <?php if ($imgPath): ?>
                    <img src="<?= $imgPath ?>" alt="<?= $noticia['titulo'] ?>" class="news-image">
                    <?php endif; ?>
                </a>
                <div class="news-content" style="font-size:1.15rem;">
                    <span class="news-category"><?= $noticia['categoria'] ?></span>
                    <h3 class="news-title"><a href="noticia.php?id=<?= $noticia['id'] ?>"><?= $noticia['titulo'] ?></a></h3>
                    <p class="news-excerpt"><?= substr(strip_tags($noticia['noticia']), 0, 150) ?>...</p>
                    <div class="news-meta">
                        <span><i class="fas fa-user"></i> <?= $noticia['autor_nome'] ?></span>
                        <span><i class="fas fa-clock"></i> <?= date('d/m/Y H:i', strtotime($noticia['data'])) ?></span>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="no-results" style="text-align:center;margin:48px 0 32px 0;font-size:1.3rem;">
            <p style="margin-bottom:12px;">Nenhum resultado encontrado para "<?= htmlspecialchars($termo) ?>"</p>
            <p style="font-size:1.1rem;">Tente outros termos ou volte para a <a href="index.php" style="color:var(--primary);font-weight:700;">página inicial</a></p>
        </div>
        <?php endif; ?>
    </main>

    <footer class="footer">
        <div class="container">
            <p>Synawrld Underground &copy; <?= date('Y') ?></p>
        </div>
    </footer>

    <script src="../assets/js/main.js"></script>
</body>
</html>