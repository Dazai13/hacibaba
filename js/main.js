(function($) {
    'use strict';

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

    window.debounce = debounce;

    function initSmoothScrolling() {
        $('[data-scroll-to]').on('click', function(e) {
            e.preventDefault();
            var target = $(this).data('scroll-to');
            var offset = $(this).data('scroll-offset') || 0;
            
            if ($(target).length) {
                $('html, body').stop().animate({
                    scrollTop: $(target).offset().top - offset
                }, 800);
            }
        });
    }

    function initAll() {
        initSliders();
        initSmoothScrolling();
        initResponsiveElements();
        
    }

    $(document).ready(function() {
        initAll();
    });

    $(window).on('load', function() {
        initAll();
    });
})(jQuery);