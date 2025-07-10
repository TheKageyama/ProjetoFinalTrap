<?php
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';
    
    if (empty($nome) || empty($email) || empty($senha)) {
        $erro = "Todos os campos são obrigatórios!";
    } elseif ($senha !== $confirmar_senha) {
        $erro = "As senhas não coincidem!";
    } elseif (strlen($senha) < 6) {
        $erro = "A senha deve ter pelo menos 6 caracteres!";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $erro = "Este e-mail já está cadastrado!";
        } else {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
            
            if ($stmt->execute([$nome, $email, $senha_hash])) {
                $sucesso = "Cadastro realizado com sucesso! Faça login.";
            } else {
                $erro = "Erro ao cadastrar. Tente novamente.";
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
    <title>Cadastro | Synawrld Underground</title>
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
                <a href="login.php" class="main-nav-btn"><i class="fas fa-sign-in-alt"></i> Login</a>
                <a href="cadastro.php" class="main-nav-btn active"><i class="fas fa-user-plus"></i> Cadastre-se</a>
            </nav>
            <button id="dark-mode-toggle" style="margin-left:20px;"><i class="fas fa-moon"></i></button>
            <button class="mobile-menu-toggle"><i class="fas fa-bars"></i></button>
        </div>
    </header>

    <main class="container">
        <section class="profile-card" style="margin:40px auto 32px auto;max-width:420px;background:var(--medium-gray);border-radius:18px;box-shadow:0 6px 32px #0007;padding:48px 36px 36px 36px;">
            <h2 style="text-align:center;font-size:2.1rem;color:var(--primary);margin-bottom:18px;letter-spacing:1px;">Junte-se à Cena</h2>
            <?php if (isset($erro)): ?>
                <div class="alert alert-error" style="background:#ff2d2d22;color:#ff2d2d;padding:12px 18px;border-radius:8px;margin-bottom:18px;text-align:center;font-weight:700;">
                    <?= $erro ?>
                </div>
            <?php elseif (isset($sucesso)): ?>
                <div class="alert alert-success" style="background:#1db95422;color:#1db954;padding:12px 18px;border-radius:8px;margin-bottom:18px;text-align:center;font-weight:700;">
                    <?= $sucesso ?>
                </div>
            <?php endif; ?>
            <form method="POST" id="form-cadastro" style="display:flex;flex-direction:column;gap:22px;">
                <div class="form-group" style="display:flex;flex-direction:column;gap:8px;">
                    <label for="nome" style="font-size:1.1rem;font-weight:700;color:var(--primary);">Nome</label>
                    <input type="text" id="nome" name="nome" required minlength="2" maxlength="50" style="font-size:1.15rem;padding:16px 18px;border-radius:10px;border:2px solid var(--primary);background:var(--dark);color:var(--light);outline:none;">
                    <small style="color:var(--light-gray);font-size:0.97rem;">Obrigatório. 2 a 50 caracteres.</small>
                </div>
                <div class="form-group" style="display:flex;flex-direction:column;gap:8px;">
                    <label for="email" style="font-size:1.1rem;font-weight:700;color:var(--primary);">E-mail</label>
                    <input type="email" id="email" name="email" required maxlength="80" style="font-size:1.15rem;padding:16px 18px;border-radius:10px;border:2px solid var(--primary);background:var(--dark);color:var(--light);outline:none;">
                    <small style="color:var(--light-gray);font-size:0.97rem;">Obrigatório. Digite um e-mail válido.</small>
                </div>
                <div class="form-group" style="display:flex;flex-direction:column;gap:8px;">
                    <label for="senha" style="font-size:1.1rem;font-weight:700;color:var(--primary);">Senha</label>
                    <input type="password" id="senha" name="senha" required minlength="6" maxlength="32" style="font-size:1.15rem;padding:16px 18px;border-radius:10px;border:2px solid var(--primary);background:var(--dark);color:var(--light);outline:none;">
                    <small style="color:var(--light-gray);font-size:0.97rem;">Mínimo 6 caracteres.</small>
                </div>
                <div class="form-group" style="display:flex;flex-direction:column;gap:8px;">
                    <label for="confirmar_senha" style="font-size:1.1rem;font-weight:700;color:var(--primary);">Confirmar Senha</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha" required minlength="6" maxlength="32" style="font-size:1.15rem;padding:16px 18px;border-radius:10px;border:2px solid var(--primary);background:var(--dark);color:var(--light);outline:none;">
                    <small style="color:var(--light-gray);font-size:0.97rem;">Repita a senha digitada acima.</small>
                </div>
                <button type="submit" class="btn btn-primary" style="font-size:1.15rem;padding:18px 38px;border-radius:8px;font-weight:700;letter-spacing:1px;box-shadow:0 2px 12px #0003;cursor:pointer;text-transform:uppercase;background:var(--primary);color:#fff;">Cadastrar</button>
            </form>
            <p class="auth-link" style="text-align:center;margin-top:18px;">Já tem conta? <a href="login.php" style="color:var(--primary);font-weight:700;">Faça login</a></p>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>Synawrld Underground &copy; <?= date('Y') ?></p>
        </div>
    </footer>

    <script>
    // Validação de senha igual
    document.getElementById('form-cadastro').addEventListener('submit', function(e) {
        const senha = document.getElementById('senha').value;
        const confirmar = document.getElementById('confirmar_senha').value;
        if (senha !== confirmar) {
            alert('As senhas não coincidem!');
            e.preventDefault();
        }
    });
    </script>
</body>
</html>