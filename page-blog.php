<?php get_header(); ?>
<main class="main">
    <div class="container">
        <?php
        if (function_exists('woocommerce_breadcrumb')) {
            woocommerce_breadcrumb(array(
                'delimiter'   => ' &gt; ',
                'wrap_before' => '<nav class="woocommerce-breadcrumb">',
                'wrap_after'  => '</nav>',
                'home'        => _x('Главная', 'breadcrumb', 'woocommerce'),
            ));
        }
        ?>
        <h2 class="title">БЛОГ</h2>
        <div class="blog__wrappers">
            <?php
            $blog_posts = new WP_Query(array(
                'post_type' => 'post',
                'posts_per_page' => 6,
                'post_status' => 'publish'
            ));

            if ($blog_posts->have_posts()) :
                while ($blog_posts->have_posts()) : $blog_posts->the_post();
                    $title = get_the_title();
                    $excerpt = get_the_excerpt();
                    // Обработка excerpt по вашему методу
                    $excerpt = $excerpt 
                        ? preg_replace('/<[^>]+>/', '', $excerpt) // Удаляем HTML-теги
                        : '';
                    $excerpt = $excerpt 
                        ? mb_substr($excerpt, 0, 100) . '...' // Обрезаем до 100 символов
                        : '';
                    $link = get_permalink();
                    $image_id = get_post_thumbnail_id();
                    $image = $image_id ? wp_get_attachment_image_url($image_id, 'medium') : get_template_directory_uri() . '/images/blog__card-image.jpg';
                    ?>
                    
                    <div class="blog__card" data-card>
                        <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>" class="blog__card-image">
                        <div class="blog__card-nav">
                            <div class="blog__card-text">
                                <h4><?php echo esc_html($title); ?></h4>
                                <?php if ($excerpt) : ?>
                                    <p><?php echo esc_html($excerpt); ?></p>
                                <?php endif; ?>
                            </div>
                            <a href="<?php echo esc_url($link); ?>" class="blog__card-btn">читать статью</a>
                        </div>
                    </div>
                    
                <?php endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>
</main>

<script>
    (function($) {
    'use strict';

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

    function getDeviceType() {
        const width = window.innerWidth;
        if (width < 768) return 'mobile';
        if (width < 1024) return 'tablet';
        return 'desktop';
    }

    function updateCards() {
        const deviceType = getDeviceType();
        $('[data-card]').each(function() {
            const $card = $(this);
            const $btn = $card.find('.blog__card-btn');
            
            $card.removeClass('blog__card--mobile blog__card--tablet blog__card--desktop')
                 .addClass('blog__card--' + deviceType);
            
            if (deviceType === 'mobile') {
                $btn.appendTo($card);
            } else {
                $btn.appendTo($card.find('.blog__card-nav'));
            }
        });
    }

    function initCardInteractions() {
        $('.blog__card').off('click').on('click', function(e) {
            if (!$(e.target).closest('.blog__card-btn').length) {
                const link = $(this).find('a').attr('href');
                if (link) window.location.href = link;
            }
        });

        if (getDeviceType() === 'desktop') {
            $('.blog__card').hover(
                function() { $(this).addClass('is-hovered'); },
                function() { $(this).removeClass('is-hovered'); }
            );
        } else {
            $('.blog__card').off('mouseenter mouseleave');
        }
    }

    $(document).ready(function() {
        updateCards();
        initCardInteractions();
        
        $(window).on('resize', debounce(function() {
            updateCards();
            initCardInteractions();
        }, 300));
    });

    })(jQuery);
</script>
<?php get_footer(); ?>