<?php
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';

$noticias = getNoticias(10);
$anunciosDestaque = getAnuncios(true, 1);
$anunciosLaterais = getAnuncios(false, 3);
$weather = getWeather();
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Synawrld Underground | Cultura e Arte</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="weather-widget" id="weather-widget">
                <?php if ($weather && isset($weather['weather'][0])): ?>
                    <img src="https://openweathermap.org/img/wn/<?= $weather['weather'][0]['icon'] ?>.png" alt="Clima">
                    <span><?= round($weather['main']['temp']) ?>°C | <?= $weather['weather'][0]['description'] ?></span>
                <?php endif; ?>
            </div>
            <div class="top-links">
                <button id="dark-mode-toggle"><i class="fas fa-moon"></i></button>
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <a href="../admin/dashboard.php"><i class="fas fa-user"></i> Painel</a>
                    <a href="../public/logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
                <?php else: ?>
                    <a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
                    <a href="cadastro.php"><i class="fas fa-user-plus"></i> Cadastro</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="main-header">
        <div class="container">
            <div class="logo-container">
                <h1 class="logo">SYNAWRLD</h1>
                <span class="sublogo">UNDERGROUND CULTURE</span>
            </div>
            <nav class="main-nav" style="gap:18px;">
                <a href="index.php" class="main-nav-btn active"><i class="fas fa-home"></i> Home</a>
                <a href="#" class="main-nav-btn"><i class="fas fa-microphone-lines"></i> Hip-Hop</a>
                <a href="#" class="main-nav-btn"><i class="fas fa-spray-can"></i> Street Art</a>
                <a href="#" class="main-nav-btn"><i class="fas fa-calendar-alt"></i> Eventos</a>
                <a href="#" class="main-nav-btn"><i class="fas fa-comments"></i> Entrevistas</a>
                <a href="#" class="main-nav-btn"><i class="fas fa-images"></i> Galeria</a>
                <a href="busca.php" class="main-nav-btn"><i class="fas fa-search"></i> Pesquisa</a>
                <a href="previsao.php" class="main-nav-btn"><i class="fas fa-cloud-sun"></i> Previsão do Tempo</a>
                <a href="../admin/anuncios.php" class="btn-admin-anuncios"><i class="fas fa-bullhorn"></i> Gerenciar Anúncios</a>
            </nav>
            <button class="mobile-menu-toggle"><i class="fas fa-bars"></i></button>
        </div>
    </header>

    <!-- Banner Destaque -->
    <?php if ($anunciosDestaque && count($anunciosDestaque) > 0): ?>
    <section class="featured-banner">
        <a href="<?= $anunciosDestaque[0]['link'] ?>" target="_blank" rel="noopener">
            <img src="../assets/images/anuncios/<?= $anunciosDestaque[0]['imagem'] ?>" alt="<?= $anunciosDestaque[0]['nome'] ?>">
        </a>
    </section>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="container main-grid">
        <!-- Conteúdo Principal -->
        <section class="content-primary">
            <h2 class="section-title"><span>Últimas Notícias</span></h2>
            
            <div class="news-grid">
                <?php foreach ($noticias as $noticia): ?>
                <article class="news-card">
                    <a href="noticia.php?id=<?= $noticia['id'] ?>" class="news-image-link">
                        <?php
                        // Corrige caminho da imagem: verifica se está em 'noticias' ou 'uploads'
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
                    <div class="news-content">
                        <span class="news-category"><?= $noticia['categoria'] ?></span>
                        <h3 class="news-title"><a href="noticia.php?id=<?= $noticia['id'] ?>"><?= $noticia['titulo'] ?></a></h3>
                        <p class="news-excerpt"><?= substr(strip_tags($noticia['noticia']), 0, 150) ?>...</p>
                        <div class="news-meta">
                            <span><i class="fas fa-user"></i> <?= $noticia['autor_nome'] ?></span>
                            <span><i class="fas fa-clock"></i> <?= date('d/m/Y H:i', strtotime($noticia['data'])) ?></span>
                            <button class="btn-pdf" data-id="<?= $noticia['id'] ?>"><i class="fas fa-file-pdf"></i> PDF</button>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            
            <a href="#" class="btn-load-more">Carregar Mais Notícias</a>
        </section>

        <!-- Sidebar -->
        <aside class="content-sidebar">
            <!-- Anúncios Laterais -->
            <div class="sidebar-widget">
                <h3 class="widget-title">Patrocinadores</h3>
                <div class="advertisements-carousel">
                    <button class="carousel-btn carousel-btn-prev" aria-label="Anterior"><i class="fas fa-chevron-left"></i></button>
                    <div class="carousel-track">
                        <?php foreach ($anunciosLaterais as $anuncio): ?>
                        <div class="ad-card carousel-slide">
                            <a href="<?= $anuncio['link'] ?>" target="_blank" rel="noopener">
                                <?php if (!empty($anuncio['imagem'])): ?>
                                <img src="/NoticiasTrap-main/assets/images/anuncios/<?= $anuncio['imagem'] ?>" alt="<?= $anuncio['nome'] ?>" class="ad-image">
                                <?php endif; ?>
                                <div class="ad-info">
                                    <h4><?= $anuncio['nome'] ?></h4>
                                    <p><?= $anuncio['texto'] ?></p>
                                </div>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-btn carousel-btn-next" aria-label="Próximo"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>

            <!-- Newsletter -->
            <div class="sidebar-widget newsletter-widget">
                <h3 class="widget-title">Newsletter</h3>
                <p>Receba as últimas notícias da cena underground</p>
                <form class="newsletter-form" id="newsletter-form">
                    <input type="email" name="email" id="newsletter-email" placeholder="Seu e-mail" required>
                    <button type="submit" class="btn-newsletter">Assinar</button>
                    <div id="newsletter-feedback" style="margin-top:8px;font-size:0.95em;"></div>
                </form>
            </div>

            <!-- Redes Sociais -->
            <div class="sidebar-widget social-widget">
                <h3 class="widget-title">Siga-nos</h3>
                <div class="social-links">
                    <a href="#" class="social-link instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link youtube"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="social-link spotify"><i class="fab fa-spotify"></i></a>
                    <a href="#" class="social-link tiktok"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>
        </aside>
    </main>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h4 class="footer-title">SYNAWRLD</h4>
                    <p>O portal definitivo da cultura underground e arte urbana.</p>
                    <p>© <?= date('Y') ?> Synawrld Underground. Todos os direitos reservados.</p>
                </div>
                <div class="footer-col">
                    <h4 class="footer-title">Links Rápidos</h4>
                    <ul class="footer-links">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="#">Sobre Nós</a></li>
                        <li><a href="#">Contato</a></li>
                        <li><a href="#">Anuncie</a></li>
                        <li><a href="#">Termos de Uso</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4 class="footer-title">Categorias</h4>
                    <ul class="footer-links">
                        <li><a href="#">Hip-Hop</a></li>
                        <li><a href="#">Street Art</a></li>
                        <li><a href="#">Eventos</a></li>
                        <li><a href="#">Entrevistas</a></li>
                        <li><a href="#">Galeria</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4 class="footer-title">Contato</h4>
                    <ul class="footer-contact">
                        <li><i class="fas fa-envelope"></i> contato@synawrld.com</li>
                        <li><i class="fas fa-phone"></i> (51) 99861-914</li>
                        <li><i class="fas fa-map-marker-alt"></i> Esteio- RS</li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Popup Anúncio -->
    <?php 
    $popupAd = getAnuncios(false, 1);
    if ($popupAd && count($popupAd) > 0): ?>
    <div class="ad-popup" id="adPopup">
        <div class="ad-popup-content">
            <button class="close-popup" id="closePopup">&times;</button>
            <a href="<?= $popupAd[0]['link'] ?>" target="_blank" rel="noopener">
                <img src="../assets/images/anuncios/<?= $popupAd[0]['imagem'] ?>" alt="<?= $popupAd[0]['nome'] ?>">
            </a>
        </div>
    </div>
    <?php endif; ?>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="../assets/js/pdf-export.js"></script>
    <script src="../assets/js/weather.js"></script>
    <script src="../assets/js/main.js"></script>
    <!-- O controle do dark mode é feito exclusivamente pelo main.js -->
</body>
</html>