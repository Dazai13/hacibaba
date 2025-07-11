document.addEventListener('DOMContentLoaded', function() {
    // Находим все элементы
    const burgerIcon = document.getElementById('burger');
    const mobileMenuWrapper = document.querySelector('.mobile-menu');
    const searchIcon = document.getElementById('search');
    const searchWrapper = document.querySelector('.search__wrapper');
    const header = document.querySelector('.header__inner');
    const closeIcon = document.getElementById('close');
    const searchInput = document.getElementById('search-input');
    const searchResults = document.querySelector('.search-results');

    // Функция для проверки ширины экрана
    function checkScreenSize() {
        if (window.innerWidth > 1024) {
            mobileMenuWrapper.style.display = 'none';
        }
    }

    // Проверяем при загрузке и при изменении размера
    checkScreenSize();
    window.addEventListener('resize', checkScreenSize);

    // Функции управления видимостью элементов
    function showMobileMenu() {
        mobileMenuWrapper.style.display = 'flex';
        searchWrapper.style.display = 'none';
        header.style.display = 'flex';
    }

    function hideMobileMenu() {
        mobileMenuWrapper.style.display = 'none';
    }

    function showSearch() {
        searchWrapper.style.display = 'block';
        header.style.display = 'none';
        mobileMenuWrapper.style.display = 'none';
        if (searchInput) searchInput.focus();
    }

    function hideSearch() {
        searchWrapper.style.display = 'none';
        header.style.display = 'flex';
        if (searchResults) searchResults.style.display = 'none';
    }

    // Обработчики событий
    burgerIcon.addEventListener('click', function(e) {
        e.preventDefault();
        if (mobileMenuWrapper.style.display !== 'flex') {
            showMobileMenu();
        } else {
            hideMobileMenu();
        }
    });

    searchIcon.addEventListener('click', function(e) {
        e.preventDefault();
        if (searchWrapper.style.display !== 'block') {
            showSearch();
        } else {
            hideSearch();
        }
    });

    closeIcon.addEventListener('click', function(e) {
        e.preventDefault();
        hideSearch();
    });

    // Закрытие при клике вне области
    document.addEventListener('click', function(e) {
        // Для поиска
        if (!searchWrapper.contains(e.target) && e.target !== searchIcon) {
            hideSearch();
        }
        // Для мобильного меню
        if (!mobileMenuWrapper.contains(e.target) && e.target !== burgerIcon) {
            hideMobileMenu();
        }
    });

    // Закрытие по Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideSearch();
            hideMobileMenu();
        }
    });

    // AJAX поиск товаров (jQuery)
    if (typeof jQuery !== 'undefined') {
        jQuery(function($) {
            $(searchInput).on('input', function() {
                const query = $(this).val().trim();
                
                if (query.length < 2) {
                    $(searchResults).hide().empty();
                    return;
                }
                
                $.ajax({
                    url: '/wp-admin/admin-ajax.php',
                    type: 'POST',
                    data: {
                        action: 'product_search',
                        query: query
                    },
                    beforeSend: function() {
                        $(searchResults).html('<div class="loading">Поиск...</div>').show();
                    },
                    // В обработчике успешного ответа AJAX поиска замените на:
                    success: function(response) {
                        if (response.success) {
                            $(searchResults).html(response.data).show();
                            
                            // Обработчик клика для открытия popup
                            $(searchResults).find('a.search-result-item').on('click', function(e) {
                                e.preventDefault();
                                const productId = $(this).data('product-id');
                                
                                // Загружаем шаблон popup через AJAX
                                $.ajax({
                                    url: '/wp-admin/admin-ajax.php',
                                    type: 'POST',
                                    data: {
                                        action: 'get_product_popup_template',
                                        product_id: productId
                                    },
                                    beforeSend: function() {
                                        $('body').append('<div class="popup-loader">Загрузка...</div>');
                                    },
                                    success: function(popupResponse) {
                                        if (popupResponse.success) {
                                            // Создаем popup, если его нет
                                            if ($('#custom-popup').length === 0) {
                                                $('body').append('<div id="custom-popup"></div>');
                                            }
                                            
                                            // Заполняем popup контентом
                                            $('#custom-popup').html('<div class="popup-content">' + popupResponse.data + '</div>').fadeIn(300);

                                            
                                            // Инициализируем скрипты для popup
                                            initProductPopup();
                                        }
                                    },
                                    complete: function() {
                                        $('.popup-loader').remove();
                                        hideSearch();
                                    }
                                });
                            });
                        } else {
                            $(searchResults).html('<div class="no-results">Ничего не найдено</div>').show();
                        }
                    }
                });
            });
        });
    }

    // Функция для загрузки и отображения popup с товаром
    function loadProductPopup(productId) {
        if (typeof jQuery !== 'undefined') {
            jQuery(function($) {
                $.ajax({
                    url: '/wp-admin/admin-ajax.php',
                    type: 'POST',
                    data: {
                        action: 'get_product_popup_content',
                        product_id: productId
                    },
                    beforeSend: function() {
                        $('body').append('<div class="popup-loader">Загрузка...</div>');
                    },
                    success: function(response) {
                        if (response.success) {
                            // Создаем popup, если его нет
                            if ($('#custom-popup').length === 0) {
                                $('body').append('<div id="custom-popup"></div>');
                            }
                            
                            // Заполняем popup контентом
                            $('#custom-popup').html(response.data);
                            
                            // Показываем popup
                            $('#custom-popup').fadeIn(300);
                            
                            // Инициализируем галерею и другие элементы
                            initProductPopup();
                        }
                    },
                    complete: function() {
                        $('.popup-loader').remove();
                    }
                });
            });
        }
    }

    // Функция для инициализации элементов в popup
    function initProductPopup() {
        if (typeof jQuery !== 'undefined') {
            jQuery(function($) {
                // Закрытие popup
                $('.popup-close, #custom-popup').on('click', function(e) {
                    if ($(e.target).is('#custom-popup')) {
                        $('#custom-popup').fadeOut(300);
                    }
                });
                
                $('.popup-content').on('click', function(e) {
                    e.stopPropagation();
                });
                
                $('.popup-close').on('click', function() {
                    $('#custom-popup').fadeOut(300);
                });

                // Галерея товара
                function initGallery($container) {
                    const $mainImgContainer = $container.find('.exclusive-main-image');
                    const $thumbsContainer = $container.find('.exclusive-gallery-thumbs');

                    function activateThumb($thumb) {
                        const $thumbs = $thumbsContainer.find('.exclusive-thumb');
                        $thumbs.show().removeClass('active');
                        $thumb.addClass('active').hide();
                        updateMainImage($thumb);
                    }

                    function updateMainImage($thumb) {
                        const $mainImg = $mainImgContainer.find('img.current-main-image');
                        const newImgSrc = $thumb.find('img').attr('src');
                        const newImgAlt = $thumb.find('img').attr('alt') || '';

                        const $newImg = $('<img>', {
                            src: newImgSrc,
                            class: 'current-main-image',
                            alt: newImgAlt
                        }).css('display', 'none');

                        $mainImg.fadeOut(200, function() {
                            $(this).replaceWith($newImg);
                            $newImg.fadeIn(200);
                        });
                    }

                    $thumbsContainer.on('click', '.exclusive-thumb', function() {
                        activateThumb($(this));
                    });

                    $mainImgContainer.find('.exclusive-prev').on('click', function(e) {
                        e.stopPropagation();
                        const $thumbs = $thumbsContainer.find('.exclusive-thumb');
                        const $active = $thumbs.filter('.active');
                        let $prev = $active.prev('.exclusive-thumb');
                        if (!$prev.length) $prev = $thumbs.last();
                        activateThumb($prev);
                    });

                    $mainImgContainer.find('.exclusive-next').on('click', function(e) {
                        e.stopPropagation();
                        const $thumbs = $thumbsContainer.find('.exclusive-thumb');
                        const $active = $thumbs.filter('.active');
                        let $next = $active.next('.exclusive-thumb');
                        if (!$next.length) $next = $thumbs.first();
                        activateThumb($next);
                    });

                    // Swipe support
                    let touchStartX = 0;
                    let touchEndX = 0;
                    const swipeThreshold = 50;

                    $mainImgContainer.on('touchstart', function(e) {
                        touchStartX = e.originalEvent.touches[0].clientX;
                    });

                    $mainImgContainer.on('touchmove', function(e) {
                        e.preventDefault();
                    });

                    $mainImgContainer.on('touchend', function(e) {
                        touchEndX = e.originalEvent.changedTouches[0].clientX;
                        const diff = touchStartX - touchEndX;

                        if (diff > swipeThreshold) {
                            $mainImgContainer.find('.exclusive-next').trigger('click');
                        } else if (diff < -swipeThreshold) {
                            $mainImgContainer.find('.exclusive-prev').trigger('click');
                        }
                    });

                    // Активировать первую миниатюру
                    activateThumb($thumbsContainer.find('.exclusive-thumb').first());
                }

                // Инициализация галереи в зависимости от устройства
                const isMobile = window.matchMedia('(max-width: 767px)').matches;
                if (isMobile) {
                    initGallery($('.mobile-content'));
                    
                    // Аккордеон для мобильной версии
                    $('.accordion-title').on('click', function() {
                        const $inner = $(this).closest('.popup__header').next('.accordion__inner');
                        const $arrow = $(this).find('.accordion__arrow');
                        $inner.slideToggle(300);
                        $arrow.toggleClass('active');
                    });
                } else {
                    initGallery($('.desktop-content'));
                }

                // Добавление в корзину
                $('.single_add_to_cart_button').on('click', function(e) {
                    e.preventDefault();
                    const productId = $(this).closest('.product').data('product_id') || $('#custom-popup').data('product_id');
                    
                    $.ajax({
                        type: 'POST',
                        url: wc_add_to_cart_params.ajax_url,
                        data: {
                            action: 'woocommerce_add_to_cart',
                            product_id: productId,
                            quantity: 1
                        },
                        success: function(response) {
                            if (response.error) {
                                alert(response.error);
                                return;
                            }

                            if (response.fragments) {
                                $.each(response.fragments, function(key, value) {
                                    $(key).replaceWith(value);
                                });
                            }

                            $('.icon__shop-item').text(WC_CART_FRAGMENTS_REFRESHED
                                ? $(response.fragments['.icon__shop-item']).text()
                                : (parseInt($('.icon__shop-item').text()) + 1));

                            $('#custom-popup').fadeOut(300);
                        },
                        error: function() {
                            alert('Ошибка добавления в корзину');
                        }
                    });
                });
            });
        }
    }
});