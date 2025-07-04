<?php 
$product = get_query_var('product');
?>
<div class="card">
    <a href="<?php echo get_permalink($product['id']); ?>">
        <img src="<?php echo esc_url($product['image']); ?>" class="card__image" alt="<?php echo esc_attr($product['title']); ?>">
    </a>
    <p class="card__title"><?php echo esc_html($product['title']); ?></p>
    <p class="card__subtitle"><?php echo esc_html($product['subtitle']); ?></p>
    <div class="card__line line"></div>
    <p class="card__price"><?php echo $product['price']; ?></p>
    <a href="<?php echo esc_url($product['add_to_cart']); ?>" class="card__btn">Добавить в корзину</a>
</div>