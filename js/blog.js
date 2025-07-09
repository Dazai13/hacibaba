(function($) {
    'use strict';

    const imageBasePath = blogVars.templateUri + '/images/';

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

    // Определение типа устройства
    function getDeviceType() {
        const width = window.innerWidth;
        if (width < 768) return 'mobile';
        if (width < 1024) return 'tablet';
        return 'desktop';
    }

    // Генерация HTML для карточки
    function generateCardHTML(post, deviceType) {
        const postImage = post.featured_image_url || imageBasePath + "blog__card-image.jpg";
        const excerpt = post.excerpt.rendered 
            ? post.excerpt.rendered.replace(/<[^>]+>/g, '').substring(0, 100) + '...'
            : '';

        let cardHTML = '';
        const cardClass = `blog__card blog__card--${deviceType}`;
        
        if (deviceType === 'mobile') {
            cardHTML = `
                <div class="${cardClass}">
                    <img src="${postImage}" alt="${post.title.rendered}" class="blog__card-image">
                    <div class="blog__card-nav">
                        <div class="blog__card-text">
                            <h4>${post.title.rendered}</h4>
                            <p>${excerpt}</p>
                        </div>
                    </div>
                    <a href="${post.link}" class="blog__card-btn">читать статью</a>
                </div>
            `;
        } else {
            cardHTML = `
                <div class="${cardClass}">
                    <img src="${postImage}" alt="${post.title.rendered}" class="blog__card-image">
                    <div class="blog__card-nav">
                        <div class="blog__card-text">
                            <h4>${post.title.rendered}</h4>
                            <p>${excerpt}</p>
                        </div>
                        <a href="${post.link}" class="blog__card-btn">читать статью</a>
                    </div>
                </div>
            `;
        }

        return cardHTML;
    }

    // Инициализация блога
    function initBlogSection() {
        const $blogWrapper = $('.blog__wrapper');
        if (!$blogWrapper.length) return;

        // Рендер карточек
        function renderCards(posts) {
            $blogWrapper.empty();
            const deviceType = getDeviceType();

            // Рендерим максимум 3 карточки
            posts.slice(0, 3).forEach(post => {
                $blogWrapper.append(generateCardHTML(post, deviceType));
            });

            // Добавляем кнопку "Читать все статьи" как ссылку с сохранением стилей
            $blogWrapper.append(`
                <a href="/blog" class="blog__wrapper-btn">
                    <h4>Читать все статьи</h4>
                </a>
            `);

            initCardInteractions();
        }

        // Инициализация взаимодействий
        function initCardInteractions() {
            // Клик по карточке
            $('.blog__card').on('click', function(e) {
                if (!$(e.target).closest('.blog__card-btn').length) {
                    const link = $(this).find('a').attr('href');
                    if (link) window.location.href = link;
                }
            });

            // Ховер-эффекты для десктопа
            if (getDeviceType() === 'desktop') {
                $('.blog__card').hover(
                    function() { $(this).addClass('is-hovered'); },
                    function() { $(this).removeClass('is-hovered'); }
                );
            }
        }

        // Загрузка изображений для постов
        async function loadFeaturedImages(posts) {
            const postsWithImages = await Promise.all(posts.map(async post => {
                if (!post.featured_media) {
                    return {
                        ...post,
                        featured_image_url: imageBasePath + "blog__card-image.jpg"
                    };
                }

                try {
                    const media = await $.get(`/wp-json/wp/v2/media/${post.featured_media}`);
                    return {
                        ...post,
                        featured_image_url: media.source_url
                    };
                } catch (error) {
                    return {
                        ...post,
                        featured_image_url: imageBasePath + "blog__card-image.jpg"
                    };
                }
            }));

            return postsWithImages;
        }

        // Основная функция загрузки постов
        async function loadPosts() {
            try {
                const posts = await $.get({
                    url: '/wp-json/wp/v2/posts',
                    data: {
                        per_page: 3,
                        _fields: 'id,title,excerpt,link,featured_media'
                    }
                });

                const postsWithImages = await loadFeaturedImages(posts);
                renderCards(postsWithImages);
            } catch (error) {
                console.error('Error loading posts:', error);
                // Можно показать сообщение об ошибке
            }
        }

        // Первоначальная загрузка
        loadPosts();

        // Обработка ресайза
        $(window).on('resize', debounce(() => {
            // Обновляем только если изменился тип устройства
            const currentDeviceType = getDeviceType();
            if (currentDeviceType !== window.lastDeviceType) {
                loadPosts();
                window.lastDeviceType = currentDeviceType;
            }
        }, 300));
    }

    // Инициализация
    $(document).ready(() => {
        window.lastDeviceType = getDeviceType();
        initBlogSection();
    });

})(jQuery);