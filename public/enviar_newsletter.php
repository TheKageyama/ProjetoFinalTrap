<?php
// Envia um e-mail de confirmação para o usuário que cadastrou o e-mail na newsletter
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método inválido']);
    exit;
}

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'E-mail inválido']);
    exit;
}

$assunto = 'Confirmação de inscrição na Newsletter | Synawrld Underground';
$mensagem = "<h2>Bem-vindo à Newsletter Synawrld Underground!</h2>\n<p>Obrigado por se inscrever. Você receberá novidades e conteúdos exclusivos da cena underground diretamente no seu e-mail.</p>\n<p>Se não foi você quem se inscreveu, basta ignorar este e-mail.</p>";
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";
$headers .= "From: Synawrld Underground <contato@synawrld.com>\r\n";

$enviado = mail($email, $assunto, $mensagem, $headers);

if ($enviado) {
    echo json_encode(['success' => true, 'message' => 'E-mail enviado com sucesso!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao enviar o e-mail.']);
}
