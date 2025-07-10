<?php
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';

verificaLogin();

// Lógica do CRUD aqui
$stmt = $pdo->query("SELECT * FROM anuncios ORDER BY data_cadastro DESC");
$anuncios = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Processar exclusão
    if (isset($_POST['excluir'])) {
        $id = $_POST['id'] ?? 0;
        
        // Busca o anúncio para pegar o nome da imagem
        $anuncio = getAnuncio($id);
        
        if ($anuncio) {
            // Remove a imagem se existir
            if ($anuncio['imagem']) {
                $image_path = "../assets/images/anuncios/" . $anuncio['imagem'];
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }
            
            $stmt = $pdo->prepare("DELETE FROM anuncios WHERE id = ?");
            if ($stmt->execute([$id])) {
                $_SESSION['sucesso'] = "Anúncio excluído com sucesso!";
                header('Location: anuncios.php');
                exit();
            } else {
                $erro = "Erro ao excluir anúncio.";
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
    <title>Anúncios | Synawrld Underground</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Urbanist:wght@400;600&display=swap" rel="stylesheet">
</head>
</head>
<style>
    .admin-section {
        max-width: 1100px;
        margin: 0 auto;
        background: var(--medium-gray);
        border-radius: 16px;
        box-shadow: 0 6px 32px #0007;
        padding: 48px 36px 36px 36px;
        margin-top: 40px;
        text-align: center;
        transition: background 0.3s;
    }
    [data-theme="dark"] .admin-section {
        background: #191919;
        color: #fff;
    }
    .admin-header {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 32px;
        margin-bottom: 32px;
    }
    .admin-header h2 {
        font-size: 2.3rem;
        color: #FFD700;
        font-family: 'Bebas Neue', 'Urbanist', sans-serif;
        letter-spacing: 2px;
        margin: 0;
    }
    .admin-header .btn-primary {
        font-size: 1.05rem !important;
        padding: 10px 22px !important;
        border-radius: 7px !important;
        font-weight: 700 !important;
        margin-left: 18px;
        background: linear-gradient(90deg, #FFD700 60%, #FF2D00 100%);
        color: #191919 !important;
        border: none;
        box-shadow: 0 2px 8px #0002;
        transition: background 0.2s, color 0.2s;
    }
    .admin-header .btn-primary:hover {
        background: linear-gradient(90deg, #FF2D00 60%, #FFD700 100%);
        color: #fff !important;
    }
    .admin-table {
        margin-top: 18px;
    }
    .admin-table table {
        width: 100%;
        border-collapse: collapse;
        background: #222;
        border-radius: 8px;
        overflow: hidden;
        font-size: 1.18rem;
        transition: background 0.3s;
    }
    [data-theme="light"] .admin-table table {
        background: #f8f8f8;
    }
    .admin-table th, .admin-table td {
        padding: 16px 12px;
        border-bottom: 1px solid #333;
        text-align: center;
        font-size: 1.18rem;
    }
    .admin-table th {
        background: #232323;
        color: #FFD700;
        font-weight: 800;
        font-size: 1.22rem;
    }
    [data-theme="light"] .admin-table th {
        background: #FFD700;
        color: #191919;
    }
    .admin-table tr:last-child td {
        border-bottom: none;
    }
    .admin-table img {
        max-width: 90px;
        max-height: 60px;
        border-radius: 6px;
        border: 2px solid #333;
        background: #fff;
    }
    .actions {
        display: flex;
        gap: 8px;
        justify-content: center;
    }
    .actions .btn-edit, .actions .btn-delete {
        font-size: 0.98rem !important;
        padding: 7px 18px !important;
        border-radius: 5px !important;
        font-weight: 700 !important;
        margin: 0 2px;
        border: none;
        cursor: pointer;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        box-shadow: 0 2px 8px #0001;
    }
    .actions .btn-edit {
        background: linear-gradient(90deg, #FFD700 60%, #FF2D00 100%);
        color: #191919 !important;
    }
    .actions .btn-edit:hover {
        background: linear-gradient(90deg, #FF2D00 60%, #FFD700 100%);
        color: #fff !important;
        box-shadow: 0 4px 16px #FF2D0033;
    }
    .actions .btn-delete {
        background: linear-gradient(90deg, #FF2D00 60%, #FFD700 100%);
        color: #fff !important;
    }
    .actions .btn-delete:hover {
        background: linear-gradient(90deg, #FFD700 60%, #FF2D00 100%);
        color: #191919 !important;
        box-shadow: 0 4px 16px #FFD70033;
    }
</style>
<body>
    <header class="header">
        <div class="container header-container">
            <h1 class="logo">SYNAWRLD</h1>
            <nav class="nav main-nav">
                <a href="../public/index.php"><i class="fas fa-home"></i> Home</a>
                <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="nova_noticia.php"><i class="fas fa-plus"></i> Nova Notícia</a>
                <a href="anuncios.php" class="active"><i class="fas fa-bullhorn"></i> Anúncios</a>
                <a href="../public/logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
            </nav>
            <button id="dark-mode-toggle" style="margin-left:20px;"><i class="fas fa-moon"></i></button>
        </div>
    </header>
</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
<script src="../assets/js/main.js"></script>

    <main class="admin-container">
        <section class="admin-section">
            <div class="admin-header">
                <h2>Gerenciar Anúncios</h2>
                <a href="cadastrar_anuncio.php" class="btn btn-primary">Novo Anúncio</a>
            </div>
            
            <?php if (isset($_SESSION['sucesso'])): ?>
                <div class="alert alert-success"><?= $_SESSION['sucesso'] ?></div>
                <?php unset($_SESSION['sucesso']); ?>
            <?php elseif (isset($erro)): ?>
                <div class="alert alert-error"><?= $erro ?></div>
            <?php endif; ?>
            
            <div class="admin-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Imagem</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Destaque</th>
                            <th>Popup</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($anuncios as $anuncio): ?>
                        <tr>
                            <td><?= $anuncio['id'] ?></td>
                            <td><?= htmlspecialchars($anuncio['nome']) ?></td>
                            <td>
                                <?php if ($anuncio['imagem']): ?>
                                    <img src="../assets/images/anuncios/<?= $anuncio['imagem'] ?>" alt="<?= htmlspecialchars($anuncio['nome']) ?>" width="50">
                                <?php else: ?>
                                    Nenhuma
                                <?php endif; ?>
                            </td>
                            <td>R$ <?= number_format($anuncio['valor'], 2, ',', '.') ?></td>
                            <td><?= $anuncio['ativo'] ? 'Ativo' : 'Inativo' ?></td>
                            <td><?= $anuncio['destaque'] ? 'Sim' : 'Não' ?></td>
                            <td><?= $anuncio['popup'] ? 'Sim' : 'Não' ?></td>
                            <td class="actions">
                                <a href="editar_anuncio.php?id=<?= $anuncio['id'] ?>" class="btn btn-edit">Editar</a>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="id" value="<?= $anuncio['id'] ?>">
                                    <button type="submit" name="excluir" class="btn btn-delete" onclick="return confirm('Tem certeza que deseja excluir este anúncio?')">Excluir</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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