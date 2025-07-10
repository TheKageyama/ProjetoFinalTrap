// Função para atualizar o widget de clima
function updateWeatherWidget() {
    const weatherWidget = document.getElementById('weather-widget');
    
    if (!weatherWidget) return;
    
    // Pega a cidade do localStorage ou usa São Paulo como padrão
    const cidade = localStorage.getItem('weather_city') || 'São Paulo';
    
    fetch(`../api/get_weather.php?city=${encodeURIComponent(cidade)}`)
        .then(response => response.json())
        .then(data => {
            if (data.cod && data.cod !== 200) {
                throw new Error(data.message || 'Erro ao obter dados do clima');
            }
            
            const temp = Math.round(data.main.temp);
            const desc = data.weather[0].description;
            const icon = data.weather[0].icon;
            
            weatherWidget.innerHTML = `
                <img src="https://openweathermap.org/img/wn/${icon}.png" alt="Clima">
                <span>${temp}°C | ${desc}</span>
            `;
        })
        .catch(error => {
            console.error('Erro ao obter dados do clima:', error);
            weatherWidget.innerHTML = '<span>Clima indisponível</span>';
        });
}

// Atualiza o clima quando a página carrega
document.addEventListener('DOMContentLoaded', updateWeatherWidget);

// Atualiza a cada 30 minutos
setInterval(updateWeatherWidget, 30 * 60 * 1000);