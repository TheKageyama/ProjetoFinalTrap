<?php
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';

$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit = 10;
$noticias = getNoticias($limit, $offset);

header('Content-Type: application/json');
$cards = [];
foreach ($noticias as $noticia) {
    $imgPath = '';
    if (!empty($noticia['imagem'])) {
        if (file_exists(__DIR__ . '/../assets/images/noticias/' . $noticia['imagem'])) {
            $imgPath = '/NoticiasTrap-main/assets/images/noticias/' . htmlspecialchars($noticia['imagem']);
        } elseif (file_exists(__DIR__ . '/../assets/images/uploads/' . $noticia['imagem'])) {
            $imgPath = '/NoticiasTrap-main/assets/images/uploads/' . htmlspecialchars($noticia['imagem']);
        }
    }
    ob_start();
    ?>
    <article class="news-card">
        <a href="noticia.php?id=<?= $noticia['id'] ?>" class="news-image-link">
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
    <?php
    $cards[] = ob_get_clean();
}

echo json_encode([
    'html' => $cards,
    'hasMore' => count($noticias) === $limit
]);
