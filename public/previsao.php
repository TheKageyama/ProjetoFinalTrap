<?php
// Página de Previsão do Tempo Completa
$cidade = $_GET['cidade'] ?? 'São Paulo';
$apiKey = '96bdd413791da838f51f315b5cf7319f';

// Consulta previsão atual

$weatherUrl = "https://api.openweathermap.org/data/2.5/weather?q=" . urlencode($cidade) . "&appid=$apiKey&units=metric&lang=pt_br";
$weatherData = json_decode(file_get_contents($weatherUrl), true);

// Consulta previsão para os próximos dias
$forecastUrl = "https://api.openweathermap.org/data/2.5/forecast?q=" . urlencode($cidade) . "&appid=$apiKey&units=metric&lang=pt_br";
$forecastData = json_decode(file_get_contents($forecastUrl), true);

function agruparPrevisoesPorDia($list) {
    $dias = [];
    foreach ($list as $item) {
        $dia = date('Y-m-d', strtotime($item['dt_txt']));
        if (!isset($dias[$dia])) {
            $dias[$dia] = [];
        }
        $dias[$dia][] = $item;
    }
    return $dias;
}
$previsoesPorDia = $forecastData && isset($forecastData['list']) ? agruparPrevisoesPorDia($forecastData['list']) : [];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Previsão do Tempo | Synawrld Underground</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .weather-main { max-width: 600px; margin: 40px auto; background: var(--medium-gray); border-radius: 18px; box-shadow: 0 6px 32px #0007; padding: 36px 28px; }
        .weather-main h2 { text-align: center; font-size: 2.1rem; color: var(--primary); margin-bottom: 18px; }
        .weather-form { display: flex; gap: 12px; margin-bottom: 24px; justify-content: center; }
        .weather-form input { font-size: 1.15rem; padding: 12px 18px; border-radius: 8px; border: 2px solid var(--primary); background: var(--dark); color: var(--light); }
        .weather-form button { font-size: 1.1rem; padding: 12px 28px; border-radius: 8px; background: var(--primary); color: #fff; font-weight: 700; border: none; cursor: pointer; transition: var(--transition); }
        .weather-form button:hover { background: var(--primary-dark); color: #FFD700; }
        .weather-current { text-align: center; margin-bottom: 32px; }
        .weather-current img { width: 80px; }
        .weather-forecast { display: flex; flex-wrap: wrap; gap: 18px; justify-content: center; }
        .forecast-card { background: var(--dark); border-radius: 10px; padding: 18px 14px; min-width: 120px; text-align: center; box-shadow: 0 2px 12px #0003; }
        .forecast-card img { width: 48px; }
        .forecast-card .date { font-size: 1.05rem; color: var(--accent); margin-bottom: 6px; }
        .forecast-card .temp { font-size: 1.2rem; font-weight: 700; color: var(--primary); }
        .forecast-card .desc { font-size: 0.98rem; color: var(--light-gray); }
    </style>
</head>
<body>
    <header class="header">
        <div class="container header-container">
            <h1 class="logo">SYNAWRLD</h1>
            <nav class="main-nav" style="gap:18px;">
                <a href="index.php" class="main-nav-btn"><i class="fas fa-home"></i> Home</a>
                <a href="#" class="main-nav-btn"><i class="fas fa-microphone-lines"></i> Hip-Hop</a>
                <a href="#" class="main-nav-btn"><i class="fas fa-spray-can"></i> Street Art</a>
                <a href="#" class="main-nav-btn"><i class="fas fa-calendar-alt"></i> Eventos</a>
                <a href="#" class="main-nav-btn"><i class="fas fa-comments"></i> Entrevistas</a>
                <a href="#" class="main-nav-btn"><i class="fas fa-images"></i> Galeria</a>
                <a href="busca.php" class="main-nav-btn"><i class="fas fa-search"></i> Pesquisa</a>
            </nav>
            <button id="dark-mode-toggle" style="margin-left:20px;"><i class="fas fa-moon"></i></button>
        </div>
    </header>
    <main class="weather-main">
        <h2>Previsão do Tempo</h2>
        <form class="weather-form" method="get" action="">
            <input type="text" name="cidade" placeholder="Digite a cidade..." value="<?= htmlspecialchars($cidade) ?>" required>
            <button type="submit"><i class="fas fa-search"></i> Pesquisar</button>
        </form>
        <?php if ($weatherData && isset($weatherData['main'])): ?>
        <div class="weather-current">
            <h3 style="font-size:1.4rem; color:var(--primary); margin-bottom:8px;">Agora em <?= htmlspecialchars($weatherData['name']) ?>, <?= $weatherData['sys']['country'] ?></h3>
            <img src="https://openweathermap.org/img/wn/<?= $weatherData['weather'][0]['icon'] ?>@2x.png" alt="Clima">
            <div style="font-size:2.2rem; font-weight:700; color:var(--accent);">
                <?= round($weatherData['main']['temp']) ?>°C
            </div>
            <div style="font-size:1.1rem; color:var(--light-gray); margin-bottom:8px;">
                <?= ucfirst($weatherData['weather'][0]['description']) ?>
            </div>
            <div style="font-size:1rem; color:var(--light-gray);">
                Sensação: <?= round($weatherData['main']['feels_like']) ?>°C | Umidade: <?= $weatherData['main']['humidity'] ?>% | Vento: <?= round($weatherData['wind']['speed']) ?> km/h
            </div>
        </div>
        <?php endif; ?>
        <?php if ($previsoesPorDia): ?>
        <h3 style="text-align:center; color:var(--primary); margin-bottom:18px;">Próximos dias</h3>
        <div class="weather-forecast">
            <?php $count = 0; foreach ($previsoesPorDia as $dia => $lista): if ($count++ >= 5) break;
                $prev = $lista[0]; // Pega a primeira previsão do dia
            ?>
            <div class="forecast-card">
                <div class="date"><?= date('d/m', strtotime($dia)) ?></div>
                <img src="https://openweathermap.org/img/wn/<?= $prev['weather'][0]['icon'] ?>.png" alt="Clima">
                <div class="temp">
                    <?= round($prev['main']['temp_min']) ?>°C / <?= round($prev['main']['temp_max']) ?>°C
                </div>
                <div class="desc">
                    <?= ucfirst($prev['weather'][0]['description']) ?>
                </div>
            </div>
            <?php endforeach; ?>
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
