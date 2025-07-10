// Função para exportar notícias para PDF
function exportToPDF(noticiaId) {
    // Carrega os dados da notícia via AJAX
    fetch(`/NoticiasTrap-main/api/get_noticia.php?id=${noticiaId}`)
        .then(response => response.json())
        .then(noticia => {
            // Cria o PDF
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            
            // Configurações do PDF
            doc.setFont('helvetica');
            doc.setFontSize(20);
            doc.setTextColor(255, 45, 0); // Cor Synawrld
            
            // Título
            doc.text(noticia.titulo, 15, 20);
            
            // Categoria e data
            doc.setFontSize(12);
            doc.setTextColor(0, 0, 0);
            doc.text(`Categoria: ${noticia.categoria}`, 15, 30);
            doc.text(`Publicado em: ${new Date(noticia.data).toLocaleDateString()}`, 15, 36);
            
            // Autor
            doc.text(`Por: ${noticia.autor_nome}`, 15, 42);
            
            // Conteúdo
            doc.setFontSize(10);
            const splitText = doc.splitTextToSize(noticia.noticia, 180);
            doc.text(splitText, 15, 50);
            
            // Rodapé
            doc.setFontSize(8);
            doc.setTextColor(100);
            doc.text('© Synawrld Underground - Cultura e Arte Urbana', 105, 285, null, null, 'center');
            
            // Salva o PDF
            doc.save(`synawrld-${noticiaId}.pdf`);
        })
        .catch(error => {
            console.error('Erro ao exportar para PDF:', error);
            alert('Erro ao exportar notícia para PDF');
        });
}