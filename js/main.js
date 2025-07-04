(function($) {
    'use strict';

    // Добавляем функцию debounce прямо в основной файл
    function debounce(func, wait) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                func.apply(context, args);
            }, wait);
        };
    }

    // Делаем функцию доступной для других модулей
    window.debounce = debounce;

    // Инициализация всех модулей
    function initAll() {
        initSliders();
        initSmoothScrolling();
        initResponsiveElements();
    }

    // Двойная инициализация
    $(document).ready(function() {
        console.log('DOM ready - initializing modules');
        initAll();
    });

    $(window).on('load', function() {
        console.log('Window loaded - rechecking modules');
        initAll();
    });

    // Логирование версий
    console.log('jQuery version:', $.fn.jquery);
    console.log('Slick version:', $.fn.slick ? 'loaded' : 'not loaded');

})(jQuery);