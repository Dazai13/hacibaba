(function($) {
    'use strict';
    
    // Делаем функцию доступной глобально через объект window
    window.debounce = function(func, wait) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                func.apply(context, args);
            }, wait);
        };
    };

})(jQuery);