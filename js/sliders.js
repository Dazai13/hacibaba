function initSliders() {
    if (typeof $ === 'undefined' || typeof $.fn.slick === 'undefined') {
        return;
    }

    initMainSlider();
    initGallerySlider();
}

function initMainSlider() {
    var $slider = $('.intro__slider');
    
    if (!$slider.length) {
        return;
    }

    if ($slider.hasClass('slick-initialized')) {
        return;
    }

    var settings = {
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        autoplay: true,
        dots: false,
        autoplaySpeed: 5000,
        adaptiveHeight: true,
        prevArrow: '.intro__arrow-prev',
        nextArrow: '.intro__arrow-next',
    };

    try {
        $slider.slick(settings);
    } catch (e) {
    }
}

function initGallerySlider() {
    var $gallery = $('.gallery__wrapper');
    var mobileBreakpoint = 1240;
    
    if (!$gallery.length) {
        
        return;
    }

    var mobileSettings = {
        slidesToScroll: 1,
        slidesToShow: 3,
        infinite: true,
        centerMode: true,
        variableWidth: true,
        arrows: true,
        autoplay: true,
        autoplaySpeed: 5000,
        dots: false,
        prevArrow: '.about__arrow-prev',
        nextArrow: '.about__arrow-next'
    };

    function checkGallerySlider() {
        if (window.innerWidth < mobileBreakpoint) {
            if (!$gallery.hasClass('slick-initialized')) {
                try {
                    $gallery.slick(mobileSettings);
                } catch (e) {
                }
            }
        } else {
            if ($gallery.hasClass('slick-initialized')) {
                try {
                    $gallery.slick('unslick');
                } catch (e) {
                }
            }
        }
    }

    checkGallerySlider();
    $(window).on('resize', debounce(checkGallerySlider, 300));
}