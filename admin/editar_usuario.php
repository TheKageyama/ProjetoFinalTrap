<?php
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/verifica_login.php';
require_once __DIR__ . '/../includes/funcoes.php';

$usuario = getUsuario($_SESSION['usuario_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $senha_atual = $_POST['senha_atual'] ?? '';
    $nova_senha = $_POST['nova_senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';
    
    // Validações básicas
    if (empty($nome) || empty($email)) {
        $erro = "Nome e e-mail são obrigatórios!";
    } else {
        // Verifica se o email já existe (para outro usuário)
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
        $stmt->execute([$email, $_SESSION['usuario_id']]);
        
        if ($stmt->fetch()) {
            $erro = "Este e-mail já está em uso por outro usuário!";
        } else {
            // Atualiza os dados básicos
            $sql = "UPDATE usuarios SET nome = ?, email = ?, bio = ?";
            $params = [$nome, $email, $bio];
            
            // Se informou senha, verifica e atualiza
            if (!empty($senha_atual)) {
                if (!password_verify($senha_atual, $usuario['senha'])) {
                    $erro = "Senha atual incorreta!";
                } elseif ($nova_senha !== $confirmar_senha) {
                    $erro = "As novas senhas não coincidem!";
                } elseif (strlen($nova_senha) < 6) {
                    $erro = "A nova senha deve ter pelo menos 6 caracteres!";
                } else {
                    $sql .= ", senha = ?";
                    $params[] = password_hash($nova_senha, PASSWORD_DEFAULT);
                }
            }
            
            if (!isset($erro)) {
                $sql .= " WHERE id = ?";
                $params[] = $_SESSION['usuario_id'];
                
                $stmt = $pdo->prepare($sql);
                if ($stmt->execute($params)) {
                    $sucesso = "Perfil atualizado com sucesso!";
                    $_SESSION['usuario_nome'] = $nome;
                    $usuario = getUsuario($_SESSION['usuario_id']);
                } else {
                    $erro = "Erro ao atualizar perfil. Tente novamente.";
                }
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
    <title>Editar Perfil | Synawrld Underground</title>
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
                <a href="editar_usuario.php" class="main-nav-btn active"><i class="fas fa-user-edit"></i> Editar Perfil</a>
                <a href="../public/logout.php" class="main-nav-btn"><i class="fas fa-sign-out-alt"></i> Sair</a>
            </nav>
            <button id="dark-mode-toggle" style="margin-left:20px;"><i class="fas fa-moon"></i></button>
            <button class="mobile-menu-toggle"><i class="fas fa-bars"></i></button>
        </div>
    </header>

    <main class="container">
        <section class="profile-card" style="margin:40px auto 32px auto;max-width:520px;background:var(--medium-gray);border-radius:18px;box-shadow:0 6px 32px #0007;padding:48px 36px 36px 36px;">
            <h2 style="text-align:center;font-size:2.1rem;color:var(--primary);margin-bottom:18px;letter-spacing:1px;">Editar Perfil</h2>
            <?php if (isset($erro)): ?>
                <div class="alert alert-error" style="background:#ff2d2d22;color:#ff2d2d;padding:12px 18px;border-radius:8px;margin-bottom:18px;text-align:center;font-weight:700;"><?= $erro ?></div>
            <?php elseif (isset($sucesso)): ?>
                <div class="alert alert-success" style="background:#1db95422;color:#1db954;padding:12px 18px;border-radius:8px;margin-bottom:18px;text-align:center;font-weight:700;"><?= $sucesso ?></div>
            <?php endif; ?>
            <form method="POST" class="profile-form" style="display:flex;flex-direction:column;gap:22px;">
                <div class="form-group" style="display:flex;flex-direction:column;gap:8px;">
                    <label for="nome" style="font-size:1.1rem;font-weight:700;color:var(--primary);">Nome</label>
                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required style="font-size:1.15rem;padding:16px 18px;border-radius:10px;border:2px solid var(--primary);background:var(--dark);color:var(--light);outline:none;">
                </div>
                <div class="form-group" style="display:flex;flex-direction:column;gap:8px;">
                    <label for="email" style="font-size:1.1rem;font-weight:700;color:var(--primary);">E-mail</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required style="font-size:1.15rem;padding:16px 18px;border-radius:10px;border:2px solid var(--primary);background:var(--dark);color:var(--light);outline:none;">
                </div>
                <div class="form-group" style="display:flex;flex-direction:column;gap:8px;">
                    <label for="bio" style="font-size:1.1rem;font-weight:700;color:var(--primary);">Biografia</label>
                    <textarea id="bio" name="bio" rows="4" style="font-size:1.1rem;padding:14px 16px;border-radius:10px;border:2px solid var(--primary);background:var(--dark);color:var(--light);outline:none;resize:vertical;min-height:90px;"><?= htmlspecialchars($usuario['bio'] ?? '') ?></textarea>
                </div>
                <h3 class="form-subtitle" style="font-size:1.15rem;color:var(--accent);margin-top:18px;margin-bottom:0;">Alterar Senha</h3>
                <p class="form-info" style="font-size:0.98rem;color:var(--light-gray);margin-bottom:0;">Preencha apenas se desejar alterar sua senha</p>
                <div class="form-group" style="display:flex;flex-direction:column;gap:8px;">
                    <label for="senha_atual" style="font-size:1.1rem;font-weight:700;color:var(--primary);">Senha Atual</label>
                    <input type="password" id="senha_atual" name="senha_atual" style="font-size:1.15rem;padding:16px 18px;border-radius:10px;border:2px solid var(--primary);background:var(--dark);color:var(--light);outline:none;">
                </div>
                <div class="form-group" style="display:flex;flex-direction:column;gap:8px;">
                    <label for="nova_senha" style="font-size:1.1rem;font-weight:700;color:var(--primary);">Nova Senha</label>
                    <input type="password" id="nova_senha" name="nova_senha" style="font-size:1.15rem;padding:16px 18px;border-radius:10px;border:2px solid var(--primary);background:var(--dark);color:var(--light);outline:none;">
                </div>
                <div class="form-group" style="display:flex;flex-direction:column;gap:8px;">
                    <label for="confirmar_senha" style="font-size:1.1rem;font-weight:700;color:var(--primary);">Confirmar Nova Senha</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha" style="font-size:1.15rem;padding:16px 18px;border-radius:10px;border:2px solid var(--primary);background:var(--dark);color:var(--light);outline:none;">
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