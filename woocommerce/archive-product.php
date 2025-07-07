<?php
if (!defined('ABSPATH')) {
    exit;
}
get_header('shop');
?>

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

    <div class="catalog__titles">
        <?php if (apply_filters('woocommerce_show_page_title', true)) : ?>
            <div class="catalog-header">
                <h1 class="woocommerce-products-header__title page-title">
                    <?php woocommerce_page_title(); ?>
                </h1>
                <p class="product-count">
                    <?php
                    global $wp_query;
                    echo sprintf(
                        _n('%s товар', '%s товаров', $wp_query->found_posts, 'woocommerce'),
                        number_format_i18n($wp_query->found_posts)
                    );
                    ?>
                </p>
            </div>
        <?php endif; ?>

        <div class="category-search">
            <form role="search" method="get" id="live-search-form">
                <svg id="search-form"><use xlink:href="<?php echo get_template_directory_uri(); ?>/images/sprite.svg#search-form"></use></svg>
                <input type="search" 
                        class="search-field" 
                        id="live-search-input"
                        placeholder="<?php echo esc_attr_x('Поиск товаров...', 'placeholder', 'woocommerce'); ?>" 
                        value="<?php echo get_search_query(); ?>" 
                        name="s" 
                        title="<?php echo esc_attr_x('Search for:', 'label', 'woocommerce'); ?>" />
                <input type="hidden" name="post_type" value="product" />
                <input type="hidden" name="product_cat" id="current-category" value="<?php echo esc_attr(get_queried_object()->term_id); ?>" />
            </form>
        </div>
    </div>


    <section id="catalog" class="catalog">
        <div class="container">
            <div class="catalog__inner inner" style="padding:2rem 4rem 6.5rem">
                <div class="catalog__wrapper" id="products-container">
                    <?php 
                    $current_category = get_queried_object();
                    
                    $args = array(
                        'post_type'      => 'product',
                        'posts_per_page' => -1,
                        'tax_query'      => array(
                            array(
                                'taxonomy' => 'product_cat',
                                'field'    => 'term_id',
                                'terms'    => $current_category->term_id,
                            )
                        )
                    );
                    
                    if (isset($_GET['s']) && !empty($_GET['s'])) {
                        $args['s'] = sanitize_text_field($_GET['s']);
                    }
                    
                    $loop = new WP_Query($args);
                    
                    if ($loop->have_posts()) {
                        while ($loop->have_posts()) : $loop->the_post();
                            global $product;
                            
                            $product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'full');
                            ?>
                            
                            <div class="card product-item">
                                <a href="<?php echo get_permalink($product->get_id()); ?>">
                                    <?php if ($product_image) : ?>
                                        <img src="<?php echo esc_url($product_image[0]); ?>" class="card__image" alt="<?php echo esc_attr($product->get_name()); ?>">
                                    <?php else : ?>
                                        <img src="<?php echo wc_placeholder_img_src(); ?>" class="card__image" alt="Placeholder">
                                    <?php endif; ?>
                                </a>
                                <p class="card__title"><?php echo esc_html($product->get_name()); ?></p>
                                <p class="card__subtitle"><?php echo esc_html($product->get_short_description()); ?></p>
                                <div class="card__line line"></div>
                                <p class="card__price"><?php echo $product->get_price_html(); ?></p>
                                <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="card__btn">Добавить в корзину</a>
                            </div>
                            
                        <?php endwhile;
                    } else {
                        echo '<p class="no-products">' . __('Товары не найдены', 'woocommerce') . '</p>';
                    }
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
        </div>
    </section>

    <section class="additionally">
        <div class="container">
            <div class="additionally__innner inner">
                    <h1>ДОПОЛНИТЕЛЬНО</h1>
                    <h3>Уважаемые покупатели! Вы можете дополнительно упаковать любой товар сайта в красивую подарочную упаковку.</h3>

            </div>
        </div>
    </section>

</div>

<script>
jQuery(document).ready(function($) {
    // Live search functionality
    $('#live-search-input').on('input', function() {
        var searchQuery = $(this).val();
        var categoryId = $('#current-category').val();
        
        $.ajax({
            url: '<?php echo admin_url("admin-ajax.php"); ?>',
            type: 'POST',
            data: {
                action: 'live_product_search',
                search: searchQuery,
                category: categoryId
            },
            beforeSend: function() {
                $('#products-container').html('<div class="loading">Поиск товаров...</div>');
            },
            success: function(response) {
                $('#products-container').html(response);
                updateProductCount();
            },
            error: function() {
                $('#products-container').html('<p>Произошла ошибка при поиске</p>');
            }
        });
    });

    function updateProductCount() {
        var productCount = $('.product-item').length;
        var countText = productCount + ' ' + (productCount % 10 === 1 && productCount % 100 !== 11 ? 'товар' : 
                          productCount % 10 >= 2 && productCount % 10 <= 4 && (productCount % 100 < 10 || productCount % 100 >= 20) ? 'товара' : 'товаров');
        $('.product-count').text(productCount === 0 ? 'Нет товаров' : countText);
    }
});
</script>

<?php get_footer('shop'); ?>
