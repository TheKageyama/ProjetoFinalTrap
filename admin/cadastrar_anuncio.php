<?php
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';

verificaLogin();

$mensagem = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $link = trim($_POST['link'] ?? '');
    $texto = trim($_POST['texto'] ?? '');
    $ativo = isset($_POST['ativo']) ? 1 : 0;
    $destaque = isset($_POST['destaque']) ? 1 : 0;
    $popup = isset($_POST['popup']) ? 1 : 0;
    $valor = floatval($_POST['valor'] ?? 0);

    if (empty($nome) || empty($link)) {
        $erro = "Nome e Link são obrigatórios!";
    } else {
        $imagem = '';
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $upload = uploadImagem($_FILES['imagem'], 'anuncios');
            if ($upload['success']) {
                $imagem = $upload['filename'];
            } else {
                $erro = $upload['message'];
            }
        }

        if (empty($erro)) {
            $stmt = $pdo->prepare("INSERT INTO anuncios 
                                (nome, imagem, link, texto, ativo, destaque, popup, valor) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            
            if ($stmt->execute([$nome, $imagem, $link, $texto, $ativo, $destaque, $popup, $valor])) {
                $mensagem = "Anúncio cadastrado com sucesso!";
            } else {
                $erro = "Erro ao cadastrar anúncio. Tente novamente.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Anúncio | Cultura Underground</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Urbanist:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="container header-container">
            <h1 class="logo">CULTURA UNDERGROUND</h1>
            <nav class="nav">
                <a href="../public/index.php">Home</a>
                <a href="dashboard.php">Dashboard</a>
                <a href="cadastrar_anuncio.php">Anunciar</a>
                <a href="../public/logout.php">Sair</a>
            </nav>
            <button id="dark-mode-toggle" style="margin-left:20px;"><i class="fas fa-moon"></i></button>
        </div>
    </header>

    <main class="admin-container">
        <section class="cadastro-section">
            <h2 style="text-align:center;font-size:2.2rem;margin-bottom:32px;letter-spacing:2px;">Cadastrar Anúncio</h2>
            <?php if (!empty($mensagem)): ?>
                <div class="alert alert-success" style="font-size:1.2rem;"><?= $mensagem ?></div>
            <?php elseif (!empty($erro)): ?>
                <div class="alert alert-error" style="font-size:1.2rem;"><?= $erro ?></div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data" class="form-anuncio">
                <div class="form-group">
                    <label for="nome">Nome do Anúncio*</label>
                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required placeholder="Ex: Loja Underground" autofocus>
                </div>
                <div class="form-group">
                    <label for="imagem">Imagem</label>
                    <input type="file" id="imagem" name="imagem" accept="image/*">
                    <small>Formatos: JPG, PNG, WEBP (Máx. 5MB)</small>
                </div>
                <div class="form-group">
                    <label for="link">Link de Destino*</label>
                    <input type="url" id="link" name="link" value="<?= htmlspecialchars($_POST['link'] ?? '') ?>" required placeholder="https://">
                </div>
                <div class="form-group">
                    <label for="texto">Texto do Anúncio</label>
                    <textarea id="texto" name="texto" placeholder="Descreva o anúncio de forma atrativa" style="min-height:140px;"><?= htmlspecialchars($_POST['texto'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label for="valor">Valor do Anúncio (R$)</label>
                    <input type="number" id="valor" name="valor" step="0.01" min="0" value="<?= htmlspecialchars($_POST['valor'] ?? '0') ?>" placeholder="0.00">
                </div>
                <div class="form-check">
                    <input type="checkbox" id="ativo" name="ativo" value="1" <?= isset($_POST['ativo']) ? 'checked' : 'checked' ?>>
                    <label for="ativo">Ativo</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" id="destaque" name="destaque" value="1" <?= isset($_POST['destaque']) ? 'checked' : '' ?>>
                    <label for="destaque">Destaque</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" id="popup" name="popup" value="1" <?= isset($_POST['popup']) ? 'checked' : '' ?>>
                    <label for="popup">Exibir como pop-up</label>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar Anúncio</button>
                    <a href="dashboard.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
                </div>
            </form>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>Cultura Underground &copy; <?= date('Y') ?></p>
        </div>
    </footer>
    <script src="https://kit.fontawesome.com/7b2e1e2e2a.js" crossorigin="anonymous"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>