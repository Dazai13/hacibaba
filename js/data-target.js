document.addEventListener('DOMContentLoaded', function() {
    // Обработчик для всех навигационных ссылок
    document.querySelectorAll('.header__inner-menu a, .main-link').forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            // Пропускаем обычные ссылки (не якорные) и внешние ссылки
            if (href === '/' || href.startsWith('http') || !href.startsWith('#')) {
                return;
            }
            
            e.preventDefault();
            
            // Получаем цель прокрутки
            const targetId = href;
            if (!targetId || targetId === '#') return;
            
            // Находим целевой элемент
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                // Рассчитываем позицию с учетом возможного фиксированного header'а
                const headerHeight = document.querySelector('header')?.offsetHeight || 0;
                const offsetPosition = targetElement.offsetTop - headerHeight;
                
                // Плавная прокрутка
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
                
                // Очищаем URL от якоря
                history.replaceState(null, null, window.location.pathname);
            }
        });
    });
    
    // Очищаем хеш при загрузке страницы
    if (window.location.hash) {
        history.replaceState(null, null, window.location.pathname);
    }
});