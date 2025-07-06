document.addEventListener('DOMContentLoaded', function() {
    // Находим элементы
    const searchIcon = document.getElementById('search');
    const searchWrapper = document.querySelector('.search__wrapper');
    const header = document.querySelector('.header__inner');
    const closeIcon = document.getElementById('close')
    
    // Добавляем обработчик клика на иконку поиска
    searchIcon.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Переключаем видимость элементов
        if (searchWrapper.style.display !== 'block') {
            searchWrapper.style.display = 'block';
            header.style.display = 'none';
        } else {
            searchWrapper.style.display = 'none';
            header.style.display = 'flex';
        }
    });
    closeIcon.addEventListener('click', function(e) {
        e.preventDefault();

        if (searchWrapper.style.display === 'block') {
            searchWrapper.style.display = 'none';
            header.style.display = 'flex';
        } else {
            searchWrapper.style.display = 'block';
            header.style.display = 'none';
        }
    });
});

jQuery(document).ready(function($) {
    const searchInput = $('#search-input');
    const searchResults = $('.search-results');
    const closeBtn = $('#close');
    const searchWrapper = $('.search__wrapper');
    const header = $('header.header');
    
    // Показ/скрытие поиска
    $('#search').on('click', function(e) {
        e.preventDefault();
        searchWrapper.addClass('active');
        header.addClass('hidden');
        searchInput.focus();
    });
    
    // Закрытие поиска
    closeBtn.on('click', function() {
        searchWrapper.removeClass('active');
        header.removeClass('hidden');
        searchResults.hide().empty();
    });
    
    // Поиск товаров
    searchInput.on('input', function() {
        const query = $(this).val().trim();
        
        if (query.length < 2) {
            searchResults.hide().empty();
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
                searchResults.html('<div class="loading">Поиск...</div>').show();
            },
            success: function(response) {
                if (response.success) {
                    searchResults.html(response.data).show();
                } else {
                    searchResults.html('<div class="no-results">Ничего не найдено</div>').show();
                }
            }
        });
    });
    
    // Клик вне области поиска
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.search__inner').length) {
            searchResults.hide();
        }
    });
});