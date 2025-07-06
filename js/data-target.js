document.addEventListener('DOMContentLoaded', function() {
    // Обработчик для всех навигационных ссылок
    document.querySelectorAll('.header__inner-menu a').forEach(link => {
        link.addEventListener('click', function(e) {
            // Для обычных ссылок (не якорных) ничего не делаем
            if (this.getAttribute('href') === '/' || 
                !(this.hasAttribute('href') || this.hasAttribute('data-scroll-target'))) {
                return;
            }
            
            e.preventDefault();
            
            // Получаем цель прокрутки
            const targetId = this.getAttribute('href') || this.getAttribute('data-scroll-target');
            if (!targetId || targetId === '#') return;
            
            // Находим целевой элемент
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                // Плавная прокрутка
                window.scrollTo({
                    top: targetElement.offsetTop,
                    behavior: 'smooth'
                });
                
                // Очищаем URL от якоря
                history.replaceState(null, null, ' ');
            }
        });
    });
    
    // Очищаем хеш при загрузке страницы
    if (window.location.hash) {
        history.replaceState(null, null, ' ');
    }
});