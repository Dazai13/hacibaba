function initSmoothScrolling() {
    $(document).on('click', '[href^="#"], [data-target]', function(e) {
        e.preventDefault();
        
        var target = $(this).attr('href') || '#' + $(this).data('target');
        if (target === '#') return;
        
        var $target = $(target);
        if ($target.length) {
            var headerHeight = $('header').outerHeight() || 0;
            var offset = 20;
            
            $('html, body').animate({
                scrollTop: $target.offset().top - headerHeight - offset
            }, 800);
        }
    });

    // Обработка хеша в URL при загрузке
    if (window.location.hash) {
        var $target = $(window.location.hash);
        if ($target.length) {
            setTimeout(function() {
                var headerHeight = $('header').outerHeight() || 0;
                $('html, body').scrollTop($target.offset().top - headerHeight);
            }, 100);
        }
    }
}