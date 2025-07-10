document.addEventListener('DOMContentLoaded', function() {
    const widget = document.getElementById('weather-widget');
    if (!widget) return;

    // Cria o formulário de cidade
    const form = document.createElement('form');
    form.id = 'weather-form';
    form.innerHTML = `
        <input type="text" id="weather-city" placeholder="Digite a cidade" style="padding:4px 8px; border-radius:4px; border:1px solid #333; width:120px;">
        <button type="submit" style="padding:4px 10px; border-radius:4px; background:#FF2D00; color:#fff; border:none; margin-left:4px; cursor:pointer;">Ver</button>
    `;
    widget.innerHTML = '';
    widget.appendChild(form);
    const resultDiv = document.createElement('div');
    resultDiv.id = 'weather-result';
    widget.appendChild(resultDiv);

    function renderWeather(data) {
        if (data && data.weather && data.weather[0]) {
            resultDiv.innerHTML = `
                <img src="https://openweathermap.org/img/wn/${data.weather[0].icon}.png" alt="Clima">
                <span>${Math.round(data.main.temp)}°C | ${data.weather[0].description}</span>
            `;
        } else {
            resultDiv.innerHTML = '<span style="color:#FFD700">Não foi possível obter o clima.</span>';
        }
    }

    // Busca clima padrão ao carregar
    fetch('/NoticiasTrap-main/public/api/get_weather.php?city=São Paulo')
        .then(r => r.json())
        .then(renderWeather);

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const city = document.getElementById('weather-city').value.trim();
        if (!city) return;
        resultDiv.innerHTML = 'Carregando...';
        fetch(`/NoticiasTrap-main/public/api/get_weather.php?city=${encodeURIComponent(city)}`)
            .then(r => r.json())
            .then(renderWeather)
            .catch(() => resultDiv.innerHTML = '<span style="color:#FFD700">Erro ao buscar clima.</span>');
    });
});
