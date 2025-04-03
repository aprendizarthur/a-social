document.addEventListener('DOMContentLoaded', function() {
    const feedback = document.getElementById('feedback-usuario');

    //vendo se o elemento existe e tem conteúdo
    if (feedback && feedback.textContent.trim() !== '') {
        // após 3 segundos esconde o elemento
        setTimeout(function() {
            feedback.style.display = 'none';
        }, 3000);
    }
});