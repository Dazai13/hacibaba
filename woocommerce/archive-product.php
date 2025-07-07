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

    <?php
    /**
     * Вывод заголовка и количества товаров
     */
    if (apply_filters('woocommerce_show_page_title', true)) : ?>
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

<section id="catalog" class="catalog">
    <div class="container">
        <div class="catalog__inner inner">
            <div class="catalog__wrapper">
                <?php 
                // Определяем текущую категорию
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
                
                $loop = new WP_Query($args);
                
                if ($loop->have_posts()) {
                    while ($loop->have_posts()) : $loop->the_post();
                        global $product;
                        
                        $product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'full');
                        ?>
                        
                        <div class="card">
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
                    echo '<p class="no-products">' . __('Товары не найдены', 'textdomain') . '</p>';
                }
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </div>
</section>

</div>

<?php get_footer('shop'); ?>