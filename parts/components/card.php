<?php 
$product = get_query_var('product');
?>
<div class="card" data-product-id="<?php echo $product['id']; ?>">
    <img src="<?php echo esc_url($product['image']); ?>" class="card__image" alt="<?php echo esc_attr($product['title']); ?>">
    <p class="card__title"><?php echo esc_html($product['title']); ?></p>
    <p class="card__subtitle"><?php echo esc_html($product['subtitle']); ?></p>
    <div class="card__line line"></div>
    <p class="card__price"><?php echo $product['price']; ?></p>
    <a class="card__btn">Добавить в корзину</a>
</div>  