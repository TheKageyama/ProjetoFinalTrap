<?php
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';

// Configuração de URL base
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/synawrld-underground/public');

// Validação do ID
$noticia_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$noticia_id) {
    header("Location: " . BASE_URL . "/index.php?erro=id_invalido");
    exit();
}

// Busca a notícia
$noticia = getNoticia($noticia_id);
if (!$noticia) {
    header("Location: " . BASE_URL . "/index.php?erro=noticia_nao_encontrada");
    exit();
}

// Atualiza visualizações
$pdo->prepare("UPDATE noticias SET visualizacoes = visualizacoes + 1 WHERE id = ?")
   ->execute([$noticia_id]);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($noticia['titulo']) ?> | Synawrld Underground</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Urbanist:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="container header-container">
            <h1 class="logo">SYNAWRLD</h1>
            <nav class="nav">
                <a href="../public/index.php" class="btn btn-home"><i class="fas fa-home"></i> Home</a>
                <a href="../public/busca.php" class="btn btn-dashboard"><i class="fas fa-search"></i> Busca</a>
                <a href="../public/login.php" class="btn btn-perfil"><i class="fas fa-user"></i> Login</a>
            </nav>
            <button id="dark-mode-toggle" style="margin-left:20px;"><i class="fas fa-moon"></i></button>
        </div>
    </header>

    <main class="container">
        <article class="noticia-detalhe" style="max-width: 800px; margin: 40px auto 32px auto; background: var(--medium-gray); border-radius: 18px; box-shadow: 0 6px 32px #0007; padding: 48px 36px 36px 36px;">
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
                <img src="<?= $imgPath ?>" alt="<?= htmlspecialchars($noticia['titulo']) ?>" class="news-image" style="width:100%;max-height:420px;object-fit:cover;border-radius:12px;box-shadow:0 2px 16px #0006;margin-bottom:28px;">
            <?php endif; ?>
            <h1 style="font-size:2.3rem;letter-spacing:1.5px;margin-bottom:18px;line-height:1.1; color:var(--primary);text-align:center;word-break:break-word;"> <?= htmlspecialchars($noticia['titulo']) ?> </h1>
            <div style="display:flex;justify-content:center;align-items:center;gap:18px;margin-bottom:18px;">
                <span style="font-size:1.1rem;color:var(--light-gray);"><i class="fas fa-user"></i> <?= htmlspecialchars($noticia['autor_nome']) ?></span>
                <span style="font-size:1.1rem;color:var(--light-gray);"><i class="fas fa-calendar"></i> <?= date('d/m/Y', strtotime($noticia['data'])) ?></span>
            </div>
            <button class="btn btn-pdf" data-id="<?= $noticia_id ?>" style="margin:0 auto 24px auto;display:block;"><i class="fas fa-file-pdf"></i> Exportar PDF</button>
            <div class="noticia-conteudo" style="font-size:1.25rem;line-height:1.7;color:var(--light);margin-top:18px;word-break:break-word;">
                <?= nl2br(htmlspecialchars($noticia['noticia'])) ?>
            </div>
        </article>
    </main>

    <footer class="footer">
        <div class="container">
            <p>Synawrld Underground &copy; <?= date('Y') ?></p>
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
                <img src="/NoticiasTrap-main/assets/images/anuncios/<?= $popupAd[0]['imagem'] ?>" alt="<?= $popupAd[0]['nome'] ?>">
            </a>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://kit.fontawesome.com/7b2e1e2e2a.js" crossorigin="anonymous"></script>
    <script src="../assets/js/main.js"></script>
    <script>
      // Garante que o tema salvo será aplicado ao abrir a página
      const savedTheme = localStorage.getItem('theme') || 'dark';
      document.documentElement.setAttribute('data-theme', savedTheme);
      const themeToggle = document.getElementById('dark-mode-toggle');
      if (themeToggle) {
        const icon = themeToggle.querySelector('i');
        if (savedTheme === 'light') {
          icon.classList.remove('fa-moon');
          icon.classList.add('fa-sun');
        } else {
          icon.classList.remove('fa-sun');
          icon.classList.add('fa-moon');
        }
      }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="../assets/js/pdf-export.js"></script>
    <script>
        document.querySelector('.btn-pdf').addEventListener('click', function() {
            var noticiaId = this.getAttribute('data-id');
            fetch('../api/get_noticia.php?id=' + noticiaId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Lógica para exportar PDF com os dados da notícia
                    } else {
                        alert('Erro ao buscar os dados da notícia.');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                });
        });
    </script>
</body>
</html>