<?php
$current_category = get_queried_object();
$search_query = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';

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

if (!empty($search_query)) {
    $args['s'] = $search_query;
}

$loop = new WP_Query($args);

if ($loop->have_posts()) {
    while ($loop->have_posts()) : $loop->the_post();
        global $product;
        
        $product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'full');
        ?>
        
        <div class="card" >
            <?php if ($product_image) : ?>
                <img src="<?php echo esc_url($product_image[0]); ?>" class="card__image" alt="<?php echo esc_attr($product->get_name()); ?>">
            <?php else : ?>
                <img src="<?php echo wc_placeholder_img_src(); ?>" class="card__image" alt="Placeholder">
            <?php endif; ?>
            <p class="card__title"><?php echo esc_html($product->get_name()); ?></p>
            <p class="card__subtitle"><?php echo esc_html($product->get_short_description()); ?></p>
            <div class="card__line line"></div>
            <p class="card__price"><?php echo $product->get_price_html(); ?></p>
            <a href="#" 
            class="card__btn show-product-popup" 
            data-product-id="<?php echo esc_attr($product->get_id()); ?>">
            Добавить в корзину
            </a>
        </div>
        
    <?php endwhile;
} else {
    echo '<p class="no-products">' . __('Товары не найдены', 'textdomain') . '</p>';
}
wp_reset_postdata();
?>