// Carrossel de Anúncios
document.addEventListener('DOMContentLoaded', function() {
    const track = document.querySelector('.carousel-track');
    const slides = document.querySelectorAll('.carousel-slide');
    const btnPrev = document.querySelector('.carousel-btn-prev');
    const btnNext = document.querySelector('.carousel-btn-next');
    let currentIndex = 0;
    if (track && slides.length > 1 && btnPrev && btnNext) {
        function updateCarousel() {
            track.style.transform = `translateX(-${currentIndex * 100}%)`;
        }
        btnPrev.addEventListener('click', function() {
            currentIndex = (currentIndex - 1 + slides.length) % slides.length;
            updateCarousel();
        });
        btnNext.addEventListener('click', function() {
            currentIndex = (currentIndex + 1) % slides.length;
            updateCarousel();
        });
        // Swipe para mobile
        let startX = 0;
        track.addEventListener('touchstart', function(e) {
            startX = e.touches[0].clientX;
        });
        track.addEventListener('touchend', function(e) {
            let endX = e.changedTouches[0].clientX;
            if (endX - startX > 50) btnPrev.click();
            if (startX - endX > 50) btnNext.click();
        });
    }
});
// Newsletter AJAX
const newsletterForm = document.getElementById('newsletter-form');
if (newsletterForm) {
    newsletterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const emailInput = document.getElementById('newsletter-email');
        const feedback = document.getElementById('newsletter-feedback');
        feedback.textContent = '';
        const email = emailInput.value.trim();
        if (!email) {
            feedback.textContent = 'Por favor, insira um e-mail válido.';
            feedback.style.color = '#e74c3c';
            return;
        }
        feedback.textContent = 'Enviando...';
        feedback.style.color = '#888';
        fetch('enviar_newsletter.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'email=' + encodeURIComponent(email)
        })
        .then(res => res.json())
        .then(data => {
            feedback.textContent = data.message;
            feedback.style.color = data.success ? '#27ae60' : '#e74c3c';
            if (data.success) emailInput.value = '';
        })
        .catch(() => {
            feedback.textContent = 'Erro ao enviar. Tente novamente.';
            feedback.style.color = '#e74c3c';
        });
    });
}
// Carregar Mais Notícias (index.php)
const btnLoadMore = document.querySelector('.btn-load-more');
const newsGrid = document.querySelector('.news-grid');
if (btnLoadMore && newsGrid) {
    let offset = document.querySelectorAll('.news-card').length;
    btnLoadMore.addEventListener('click', function(e) {
        e.preventDefault();
        btnLoadMore.textContent = 'Carregando...';
        fetch('carregar_mais_noticias.php?offset=' + offset)
            .then(res => res.json())
            .then(data => {
                if (data.html && data.html.length > 0) {
                    data.html.forEach(card => {
                        const temp = document.createElement('div');
                        temp.innerHTML = card;
                        newsGrid.appendChild(temp.firstElementChild);
                    });
                    offset += data.html.length;
                    if (!data.hasMore) {
                        btnLoadMore.style.display = 'none';
                    } else {
                        btnLoadMore.textContent = 'Carregar Mais Notícias';
                    }
                } else {
                    btnLoadMore.style.display = 'none';
                }
                // Reativa botões PDF para novas notícias
                document.querySelectorAll('.btn-pdf').forEach(btn => {
                    btn.onclick = function() {
                        const noticiaId = this.getAttribute('data-id');
                        exportToPDF(noticiaId);
                    };
                });
            })
            .catch(() => {
                btnLoadMore.textContent = 'Tentar Novamente';
            });
    });
}
// Dark Mode Toggle
document.getElementById('dark-mode-toggle').addEventListener('click', function() {
    const html = document.documentElement;
    const currentTheme = html.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    html.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    
    // Atualiza o ícone
    const icon = this.querySelector('i');
    icon.classList.toggle('fa-moon');
    icon.classList.toggle('fa-sun');
});

// Verifica o tema salvo no localStorage
const savedTheme = localStorage.getItem('theme') || 'dark';
document.documentElement.setAttribute('data-theme', savedTheme);

// Atualiza o ícone conforme o tema
const themeToggle = document.getElementById('dark-mode-toggle');
if (themeToggle) {
    const icon = themeToggle.querySelector('i');
    if (savedTheme === 'light') {
        icon.classList.remove('fa-moon');
        icon.classList.add('fa-sun');
    }
}

// Mobile Menu Toggle
const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
const mainNav = document.querySelector('.main-nav');

if (mobileMenuToggle && mainNav) {
    mobileMenuToggle.addEventListener('click', function() {
        mainNav.classList.toggle('active');
    });
}

// Popup Anúncio
const adPopup = document.getElementById('adPopup');
const closePopup = document.getElementById('closePopup');

if (adPopup && closePopup) {
    // Mostra o popup após 3 segundos
    setTimeout(() => {
        adPopup.classList.add('active');
    }, 3000);
    
    // Fecha o popup quando clicar no botão
    closePopup.addEventListener('click', function() {
        adPopup.classList.remove('active');
    });
    
    // Fecha o popup quando clicar fora do conteúdo
    adPopup.addEventListener('click', function(e) {
        if (e.target === adPopup) {
            adPopup.classList.remove('active');
        }
    });
}

// Botão PDF
document.querySelectorAll('.btn-pdf').forEach(btn => {
    btn.addEventListener('click', function() {
        const noticiaId = this.getAttribute('data-id');
        exportToPDF(noticiaId);
    });
});

// Removido: função exportToPDF duplicada. A função correta está em pdf-export.js