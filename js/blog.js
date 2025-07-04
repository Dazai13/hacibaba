(function($) {
    'use strict';

    // Функция debounce для оптимизации обработки resize
    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                func.apply(context, args);
            }, wait);
        };
    }

    // Инициализация блога
    function initBlogSection() {
        const $blogWrapper = $('.blog__wrapper');
        if (!$blogWrapper.length) return;

        // Мокап данных (в реальном проекте можно заменить на AJAX-запрос)
        const blogData = [
            {
                title: "История рахат-лукума",
                excerpt: "История рахат-лукума истоки сладкого наследия Востока, уходящие корнями в глубину веков...",
                image: "<?php echo get_template_directory_uri(); ?>../images/blog__card-image.jpg",
                link: "#"
            },
            {
                title: "История рахат-лукума",
                excerpt: "История рахат-лукума истоки сладкого наследия Востока, уходящие корнями в глубину веков...",
                image: "<?php echo get_template_directory_uri(); ?>../images/blog__card-image.jpg",
                link: "#"
            },
            {
                title: "История рахат-лукума",
                excerpt: "История рахат-лукума истоки сладкого наследия Востока, уходящие корнями в глубину веков...",
                image: "<?php echo get_template_directory_uri(); ?>../images/blog__card-image.jpg",
                link: "#"
            }
        ];

        // Функция определения типа устройства
        function getDeviceType() {
            const width = window.innerWidth;
            if (width < 768) return 'mobile';
            if (width < 1024) return 'tablet';
            return 'desktop';
        }

        // Рендер карточек в зависимости от устройства
        function renderCards() {
            $blogWrapper.empty();
            const deviceType = getDeviceType();

            // Рендер карточек
            blogData.forEach(item => {
                let cardHTML = '';

                if (deviceType === 'mobile') {
                    cardHTML = `
                        <div class="blog__card blog__card--mobile">
                            <img src="${item.image}" alt="${item.title}" class="blog__card-image">
                            <div class="blog__card-nav">
                                <div class="blog__card-text">
                                    <h4>${item.title}</h4>
                                    <p>${item.excerpt}</p>
                                </div>
                            </div>
                            <a href="${item.link}" class="blog__card-btn">читать статью</a>
                        </div>
                    `;
                } else if (deviceType === 'tablet') {
                    cardHTML = `
                        <div class="blog__card blog__card--tablet">
                            <img src="${item.image}" alt="${item.title}" class="blog__card-image">
                            <div class="blog__card-nav">
                                <div class="blog__card-text">
                                    <h4>${item.title}</h4>
                                    <p>${item.excerpt}</p>
                                </div>
                                <a href="${item.link}" class="blog__card-btn">читать статью</a>
                            </div>
                        </div>
                    `;
                } else {
                    // Desktop version
                    cardHTML = `
                        <div class="blog__card blog__card--desktop">
                            <img src="${item.image}" alt="${item.title}" class="blog__card-image">
                            <div class="blog__card-nav">
                                <div class="blog__card-text">
                                    <h4>${item.title}</h4>
                                    <p>${item.excerpt}</p>
                                </div>
                                <a href="${item.link}" class="blog__card-btn">читать статью</a>
                            </div>
                        </div>
                    `;
                }

                $blogWrapper.append(cardHTML);
            });

            // Добавляем кнопку "Читать все статьи" в конец блока
            $blogWrapper.append(`
                <div class="blog__wrapper-btn">
                    <h4>Читать все статьи</h4>
                </div>
            `);

            // Инициализация обработчиков событий после рендера
            initCardInteractions();
        }

        // Инициализация взаимодействий с карточками
        function initCardInteractions() {
            $('.blog__card').on('click', function(e) {
                // Обработка клика по карточке
                if (!$(e.target).closest('.blog__card-btn').length) {
                    const link = $(this).find('a').attr('href');
                    if (link) window.location.href = link;
                }
            });

            // Ховер-эффекты для десктопа
            if (getDeviceType() === 'desktop') {
                $('.blog__card').hover(
                    function() {
                        $(this).addClass('is-hovered');
                    },
                    function() {
                        $(this).removeClass('is-hovered');
                    }
                );
            }

            // Обработка кнопки "Читать все статьи"
            $('.blog__wrapper-btn').on('click', function() {
                window.location.href = "<?php echo get_permalink(get_option('page_for_posts')); ?>";
            });
        }

        // Первоначальный рендер
        renderCards();

        // Реакция на изменение размера окна
        $(window).on('resize', debounce(function() {
            renderCards();
        }, 300));
    }

    // Инициализация при загрузке документа
    $(document).ready(function() {
        initBlogSection();
    });

})(jQuery);