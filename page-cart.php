<?php
/**
 * Шаблон корзины с товарами в виде блоков + пустая корзина
 */
if (!defined('ABSPATH')) {
    exit;
}

get_header('shop'); ?>

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
</div>

<?php do_action('woocommerce_before_cart'); ?>

<?php if (WC()->cart->is_empty()) : ?>
    <!-- Блок для пустой корзины -->
    <div class="empty-cart-container">
        <div class="empty-cart-content">      
            <h3 class="empty-cart-title"><?php esc_html_e('ВАША КОРЗИНА ПУСТА', 'woocommerce'); ?></h2>       
            <div class="empty-cart-button">
                <a href="<?php echo esc_url(wc_get_page_permalink('/')); ?>" class="button">
                    <?php esc_html_e('перейти на главную', 'woocommerce'); ?>
                </a>
            </div>
        </div>
    </div>
<?php else : ?>
<!-- Блок для корзины с товарами -->
<form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
    <?php do_action('woocommerce_before_cart_contents'); ?>
    
    <div class="cart-layout-wrapper">
        <!-- Левая колонка - товары -->
        <div class="cart-items-column">
            <div class="cart-items-grid">
                <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) : 
                    $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                    if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) :
                        $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                ?>
                        <div class="cart-item-block <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
                            
                            <div class="cart-item-image">
                                <?php
                                $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
                                if (!$product_permalink) {
                                    echo $thumbnail;
                                } else {
                                    printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail);
                                }
                                ?>
                            </div>
                            
                            <div class="cart-item-details">
                                <div class="cart-item-name">
                                    <?php
                                    if (!$product_permalink) {
                                        echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
                                    } else {
                                        echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                    }
                                    ?>
                                </div>
                                
                                <div class="cart-item-price">
                                    <?php echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); ?>
                                </div>
                                
                                <div class="cart-item-quantity">
                                    <?php
                                    if ($_product->is_sold_individually()) {
                                        $product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
                                    } else {
                                        $product_quantity = woocommerce_quantity_input(
                                            array(
                                                'input_name'   => "cart[{$cart_item_key}][qty]",
                                                'input_value'  => $cart_item['quantity'],
                                                'max_value'    => $_product->get_max_purchase_quantity(),
                                                'min_value'    => '0',
                                                'product_name' => $_product->get_name(),
                                            ),
                                            $_product,
                                            false
                                        );
                                    }
                                    echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item);
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            
            <div class="cart-actions">
                <button type="submit" class="button" name="update_cart" value="<?php esc_attr_e('Обновить корзину', 'woocommerce'); ?>"><?php esc_html_e('Обновить корзину', 'woocommerce'); ?></button>
                <?php do_action('woocommerce_cart_actions'); ?>
                <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
            </div>
        </div>
        
        <!-- Правая колонка - данные покупателя -->
        <div class="customer-data-column">
            <h3>Данные покупателя</h3>
            
            <div class="customer-form">
                <div class="form-row">
                    <label for="customer_name">Имя и фамилия*</label>
                    <input type="text" id="customer_name" name="customer_name" required>
                </div>
                
                <div class="form-row">
                    <label for="customer_phone">Телефон*</label>
                    <input type="tel" id="customer_phone" name="customer_phone" required>
                </div>
                
                <div class="form-row">
                    <label for="customer_email">Email*</label>
                    <input type="email" id="customer_email" name="customer_email" required>
                </div>
                
                <div class="form-row">
                    <label for="customer_address">Адрес доставки</label>
                    <textarea id="customer_address" name="customer_address" rows="3"></textarea>
                </div>
                
                <div class="form-row">
                    <label for="customer_notes">Примечания к заказу</label>
                    <textarea id="customer_notes" name="customer_notes" rows="3"></textarea>
                </div>
                
                <?php if (wc_coupons_enabled()) { ?>
                    <div class="coupon-form">
                        <label for="coupon_code">Купон на скидку</label>
                        <div class="coupon-input">
                            <input type="text" name="coupon_code" id="coupon_code" placeholder="<?php esc_attr_e('Введите купон', 'woocommerce'); ?>">
                            <button type="submit" name="apply_coupon" value="<?php esc_attr_e('Применить', 'woocommerce'); ?>"><?php esc_html_e('Применить', 'woocommerce'); ?></button>
                        </div>
                    </div>
                <?php } ?>
                
                <div class="cart-totals">
                    <?php do_action('woocommerce_cart_totals_before_order_total'); ?>
                    <div class="order-total">
                        <span>Итого:</span>
                        <span><?php wc_cart_totals_order_total_html(); ?></span>
                    </div>
                    <?php do_action('woocommerce_cart_totals_after_order_total'); ?>
                </div>
                
                <div class="checkout-button">
                    <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="checkout-button button alt wc-forward">
                        <?php esc_html_e('Оформить заказ', 'woocommerce'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <?php do_action('woocommerce_after_cart_contents'); ?>
</form>

<style>
    /* Стили для двухколоночного макета */
    .cart-layout-wrapper {
        display: flex;
        gap: 30px;
    }
    
    .cart-items-column {
        flex: 2;
    }
    
    .customer-data-column {
        flex: 1;
        background: #f9f9f9;
        padding: 20px;
        border-radius: 5px;
    }
    
    .customer-data-column h3 {
        margin-top: 0;
        margin-bottom: 20px;
        font-size: 1.5em;
    }
    
    .form-row {
        margin-bottom: 15px;
    }
    
    .form-row label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
    }
    
    .form-row input,
    .form-row textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    
    .coupon-form {
        margin: 20px 0;
        padding: 15px;
        background: #fff;
        border: 1px dashed #ccc;
    }
    
    .coupon-input {
        display: flex;
        gap: 10px;
    }
    
    .coupon-input input {
        flex: 1;
    }
    
    .cart-totals {
        margin: 20px 0;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }
    
    .order-total {
        display: flex;
        justify-content: space-between;
        font-size: 1.2em;
        font-weight: bold;
    }
    
    .checkout-button {
        margin-top: 20px;
    }
    
    .checkout-button .button {
        width: 100%;
        text-align: center;
        padding: 15px;
        font-size: 1.1em;
    }
    
    @media (max-width: 768px) {
        .cart-layout-wrapper {
            flex-direction: column;
        }
    }
</style>
<?php endif; ?>

<?php do_action('woocommerce_after_cart'); ?>

<style>
    /* Стили для корзины с товарами */
    .cart-items-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .cart-item-block {
        border: 1px solid #e0e0e0;
        padding: 15px;
        border-radius: 5px;
        display: flex;
        flex-direction: column;
    }
    
    /* Стили для пустой корзины */
    .empty-cart-container {
        max-width: 1360px;
        margin: 0 auto;
        text-align: center;
        height: 800px;
        justify-content: center;
  display: flex;
  align-items: center;
}
    }
    
    .empty-cart-icon {
        margin-bottom: 20px;
    }
    
    .empty-cart-icon svg {
        width: 80px;
        height: 80px;
    }
    
    .empty-cart-title {
        font-family: var(--main-font);
    }
    
    .empty-cart-button {
        margin-top: 55px;
    }
    
    .empty-cart-button .button {
        background-color: var(--red);
        color: #fff;
        text-decoration: none;
        border-radius: 4px;
        font-family: var(--main-font);
        font-size: 40px;
        line-height: 46px;
        font-weight: 700;
        padding: 12px 30px;
        border-radius: 50px;
    }
    
    .empty-cart-button .button:hover {
        background-color: white;
        color:var(--red);
        border: 1px solid var(--red);
        transition: all 0.3s ease;
    }
@media (max-width:450px) {
    .empty-cart-button .button{
        font-size: 18px;
        line-height: 18px;
    }
}
</style>

<?php get_footer('shop'); ?>