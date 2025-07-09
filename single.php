<?php
/**
 * Template Name: Blog Post Template
 * Template Post Type: post
 */
get_header(); ?>

<main class="main blog-single">
    <div class="container">

        <?php
        // Кастомные хлебные крошки для записей блога
        if (function_exists('woocommerce_breadcrumb')) {
            $blog_page_id = get_option('page_for_posts');
            $blog_page_title = $blog_page_id ? get_the_title($blog_page_id) : 'Блог';
            $blog_page_url = $blog_page_id ? get_permalink($blog_page_id) : home_url('/blog');
            
            $crumbs = array(
                array(
                    'text' => _x('Главная', 'breadcrumb', 'woocommerce'),
                    'url'  => home_url()
                ),
                array(
                    'text' => $blog_page_title,
                    'url'  => $blog_page_url
                ),
                array(
                    'text' => get_the_title(),
                    'url'  => ''
                )
            );
            
            echo '<nav class="woocommerce-breadcrumb">';
            foreach ($crumbs as $i => $crumb) {
                if (!empty($crumb['url']) && sizeof($crumbs) !== $i + 1) {
                    echo '<a href="' . esc_url($crumb['url']) . '">' . esc_html($crumb['text']) . '</a>';
                    echo ' &gt; ';
                } else {
                    echo esc_html($crumb['text']);
                }
            }
            echo '</nav>';
        }
        ?>
        <h2 class="title">БЛОГ</h2>
        <article id="post-<?php the_ID(); ?>" <?php post_class('blog-post'); ?>>
            <div class="blog-post__content">
                <?php the_content(); ?>
            </div>

            <?php
            // Блок похожих записей (2 записи)
            $related = new WP_Query(array(
                'post_type' => 'post',
                'category__in' => wp_get_post_categories(get_the_ID()),
                'post__not_in' => array(get_the_ID()),
                'posts_per_page' => 2,
                'orderby' => 'rand'
            ));

            if ($related->have_posts()) : ?>
                <section class="related-posts">
                    <h2 class="related-posts__title">ДРУГИЕ СТАТЬИ</h2>
                    <div class="blog__wrappers">
<?php while ($related->have_posts()) : $related->the_post();
    $title = get_the_title();
    $excerpt = get_the_excerpt();
    $excerpt = $excerpt ? wp_strip_all_tags($excerpt) : ''; // Strip HTML tags
    $excerpt = $excerpt ? substr($excerpt, 0, 100) . '...' : ''; // Limit to 100 chars
    $link = get_permalink();
    $image_id = get_post_thumbnail_id();
    $image = $image_id ? wp_get_attachment_image_url($image_id, 'medium') : get_template_directory_uri() . '/images/blog__card-image.jpg';
    ?>
    
    <div class="blog__card" data-card>
        <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>" class="blog__card-image">
        <div class="blog__card-nav">
            <div class="blog__card-text">
                <h4><?php echo esc_html($title); ?></h4>
                <p><?php echo esc_html($excerpt); ?></p>
            </div>
            <a href="<?php echo esc_url($link); ?>" class="blog__card-btn">читать статью</a>
        </div>
    </div>
    
<?php endwhile; ?>
                    </div>
                </section>
                <?php wp_reset_postdata(); ?>
            <?php endif; ?>
        </article>
    </div>
</main>

<script>
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

        // Функция определения типа устройства
        function getDeviceType() {
            const width = window.innerWidth;
            if (width < 768) return 'mobile';
            if (width < 1024) return 'tablet';
            return 'desktop';
        }

        // Обновление классов и структуры карточек
        function updateCards() {
            const deviceType = getDeviceType();
            $('[data-card]').each(function() {
                const $card = $(this);
                const $btn = $card.find('.blog__card-btn');
                
                // Удаляем все классы устройств
                $card.removeClass('blog__card--mobile blog__card--tablet blog__card--desktop');
                
                // Добавляем нужный класс устройства
                $card.addClass('blog__card--' + deviceType);
                
                // Перемещаем кнопку в зависимости от устройства
                if (deviceType === 'mobile') {
                    $btn.appendTo($card);
                } else {
                    $btn.appendTo($card.find('.blog__card-nav'));
                }
            });
        }

        // Инициализация обработчиков событий
        function initCardInteractions() {
            // Обработка кликов по карточке
            $('.blog__card').off('click').on('click', function(e) {
                if (!$(e.target).closest('.blog__card-btn').length) {
                    const link = $(this).find('a').attr('href');
                    if (link) window.location.href = link;
                }
            });

            // Ховер-эффекты только для десктопа
            if (getDeviceType() === 'desktop') {
                $('.blog__card').hover(
                    function() { $(this).addClass('is-hovered'); },
                    function() { $(this).removeClass('is-hovered'); }
                );
            } else {
                $('.blog__card').off('mouseenter mouseleave');
            }
        }

        // Инициализация
        $(document).ready(function() {
            // Первоначальное обновление
            updateCards();
            initCardInteractions();
            
            // Обработка изменения размера
            $(window).on('resize', debounce(function() {
                updateCards();
                initCardInteractions();
            }, 300));
        });

    })(jQuery);
</script>
<style>
    @media (max-width:1024px){
        .blog-post__content{
        margin: 0px 67px;
    }
    @media(max-width:767px){
        .blog-post__content{
            margin:0 21px;
        }
        .blog__wrappers{
            margin:0;
        }
        .blog-post__content p{
            text-align:center;  
        }
        .related-posts__title{
            margin:30px 0;
        }
        .blog-single .title{
            margin-bottom: 3rem;
        }
    }
}
</style>
<?php get_footer(); ?>