<?php
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_email'] = $usuario['email'];
        header('Location: ../admin/dashboard.php');
        exit();
    } else {
        $erro = "E-mail ou senha incorretos!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Synawrld Underground</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Urbanist:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="main-header">
        <div class="container header-container">
            <h1 class="logo">SYNAWRLD</h1>
            <span class="sublogo">UNDERGROUND CULTURE</span>
            <nav class="main-nav" style="gap:18px;">
                <a href="index.php" class="main-nav-btn"><i class="fas fa-home"></i> Home</a>
                <a href="login.php" class="main-nav-btn active"><i class="fas fa-sign-in-alt"></i> Login</a>
                <a href="cadastro.php" class="main-nav-btn"><i class="fas fa-user-plus"></i> Cadastre-se</a>
            </nav>
            <button id="dark-mode-toggle" style="margin-left:20px;"><i class="fas fa-moon"></i></button>
            <button class="mobile-menu-toggle"><i class="fas fa-bars"></i></button>
        </div>
    </header>

    <main class="container">
        <section class="profile-card" style="margin:40px auto 32px auto;max-width:420px;background:var(--medium-gray);border-radius:18px;box-shadow:0 6px 32px #0007;padding:48px 36px 36px 36px;">
            <h2 style="text-align:center;font-size:2.1rem;color:var(--primary);margin-bottom:18px;letter-spacing:1px;">Acesse a Cena</h2>
            <?php if (isset($erro)): ?>
                <div class="alert alert-error" style="background:#ff2d2d22;color:#ff2d2d;padding:12px 18px;border-radius:8px;margin-bottom:18px;text-align:center;font-weight:700;">
                    <?= $erro ?>
                </div>
            <?php endif; ?>
            <form method="POST" style="display:flex;flex-direction:column;gap:22px;">
                <div class="form-group" style="display:flex;flex-direction:column;gap:8px;">
                    <label for="email" style="font-size:1.1rem;font-weight:700;color:var(--primary);">E-mail</label>
                    <input type="email" id="email" name="email" required maxlength="80" style="font-size:1.15rem;padding:16px 18px;border-radius:10px;border:2px solid var(--primary);background:var(--dark);color:var(--light);outline:none;">
                </div>
                <div class="form-group" style="display:flex;flex-direction:column;gap:8px;">
                    <label for="senha" style="font-size:1.1rem;font-weight:700;color:var(--primary);">Senha</label>
                    <input type="password" id="senha" name="senha" required minlength="6" maxlength="32" style="font-size:1.15rem;padding:16px 18px;border-radius:10px;border:2px solid var(--primary);background:var(--dark);color:var(--light);outline:none;">
                </div>
                <button type="submit" class="btn btn-primary" style="font-size:1.15rem;padding:18px 38px;border-radius:8px;font-weight:700;letter-spacing:1px;box-shadow:0 2px 12px #0003;cursor:pointer;text-transform:uppercase;background:var(--primary);color:#fff;">Entrar</button>
            </form>
            <p class="auth-link" style="text-align:center;margin-top:18px;">Não tem conta? <a href="cadastro.php" style="color:var(--primary);font-weight:700;">Cadastre-se</a></p>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>Synawrld Underground &copy; <?= date('Y') ?></p>
        </div>
    </footer>
</body>
</html>