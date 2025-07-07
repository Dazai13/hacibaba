function initSliders() {
    if (typeof $ === 'undefined' || typeof $.fn.slick === 'undefined') {
        console.error('Required libraries not loaded: jQuery or Slick Slider');
        return;
    }

    initMainSlider();
    initGallerySlider();
}

function initMainSlider() {
    var $slider = $('.intro__slider');
    
    if (!$slider.length) {
        console.warn('Main slider element not found');
        return;
    }

    if ($slider.hasClass('slick-initialized')) {
        console.warn('Main slider already initialized');
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
        console.log('Main slider successfully initialized');
    } catch (e) {
        console.error('Main slider initialization failed:', e);
    }
}

function initGallerySlider() {
    var $gallery = $('.gallery__wrapper');
    var mobileBreakpoint = 1240;
    
    if (!$gallery.length) {
        console.warn('Gallery slider element not found');
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
                    console.log('Gallery slider initialized');
                } catch (e) {
                    console.error('Gallery slider init error:', e);
                }
            }
        } else {
            if ($gallery.hasClass('slick-initialized')) {
                try {
                    $gallery.slick('unslick');
                    console.log('Gallery slider destroyed');
                } catch (e) {
                    console.error('Gallery slider destroy error:', e);
                }
            }
        }
    }

    checkGallerySlider();
    $(window).on('resize', debounce(checkGallerySlider, 300));
}