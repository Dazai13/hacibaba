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
                    success: function(response) {
                        if (response.success) {
                            $(searchResults).html(response.data).show();
                        } else {
                            $(searchResults).html('<div class="no-results">Ничего не найдено</div>').show();
                        }
                    }
                });
            });
        });
    }
});