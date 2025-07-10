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
                                echo $thumbnail;
                                ?>
                            </div>
                                
                            <div class="cart-item-details">
                                <div class="cart-item-name">
                                    <?php
                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
                                    ?>
                                </div>
                                <div class="cart-item-price">
                                    <?php echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); ?>
                                </div>                              
                                <div class="cart-item-quantity">
                                    <?php
                                    if ($_product->is_sold_individually()) {
                                        echo sprintf(
                                            '1 <input type="hidden" name="cart[%s][qty]" value="1" />',
                                            esc_attr($cart_item_key)
                                        );
                                    } else {
                                        $input = woocommerce_quantity_input([
                                            'input_name'  => "cart[{$cart_item_key}][qty]",
                                            'input_value' => $cart_item['quantity'],
                                            'max_value'   => $_product->get_max_purchase_quantity(),
                                            'min_value'   => 0,
                                            'input_class' => ['qty'],
                                            'return'      => true,
                                        ], $_product, false);

                                        // Получаем URL спрайта
                                        $sprite_url = esc_url(get_template_directory_uri() . '/images/sprite.svg');
                                        
                                        echo '<div class="quantity-wrapper">'
                                            . '<button type="button" class="quantity-minus"><svg id="minus"><use xlink:href="' . $sprite_url . '#minus"></use></svg></button>'
                                            . $input
                                            . '<button type="button" class="quantity-plus"><svg id="plus"><use xlink:href="' . $sprite_url . '#plus"></use></svg></button>'
                                            . '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <!-- Правая колонка - данные покупателя -->
        <div class="customer-data-column">
            
            <div class="checkout__contact">
                <h3>Контактные данные</h3>
                <div class="form-row">
                    <label for="customer_name">Ф.И.О.</label>
                    <input type="text" id="customer_name" name="customer_name" required>
                </div>
                <div class="form-row">
                    <label for="customer_phone">Контактный телефон</label>
                    <div style="display: flex; height:38px;gap:1rem;">
                        <select id="country_code" name="country_code">
                            <option value="7">+7</option>
                            <option value="380">+380</option>
                            <option value="375">+375</option>
                            <option value="77">+77</option>
                            <option value="1">+1</option>
                            <option value="44">+44</option>
                            <option value="49">+49</option>
                            <option value="33">+33</option>
                            <option value="86">+86</option>
                        </select>
                        <input type="tel" id="customer_phone" name="customer_phone" placeholder="(___) ___-__-__" required
                            style="flex-grow: 1;">
                    </div>
                </div>
            </div>
            <div class="checkout__address">
                <h3>Адрес доставки</h3>
                <div class="form-row">
                    <label for="customer_address">Местоположение</label>
                    <select type="address" id="customer_address" name="customer_address" required>
                        <option value="Москва">Москва</option>
                    </select>
                </div>
                
                <div class="form-row">
                    <label for="sdek_address">Адрес пункта выдачи СДЭК</label>
                    <input id="sdek_address" name="sdek_address" rows="3"></input>
                </div>
            </div>      
            <div class="checkout__comment">
                <h3>Комментарий к заказу</h3>
                <div class="form-row">
                    <label for="customer_notes">Комментарий к заказу</label>
                    <textarea id="customer_notes" name="customer_notes" rows="3"></textarea>
                </div>
            </div>          
            <div class="checkout__payment">
                <?php if (WC()->cart->needs_payment()) : ?>
                    <?php if (!empty($available_gateways)) : ?>
                        <ul class="wc_payment_methods payment_methods methods">
                            <?php foreach ($available_gateways as $gateway) : ?>
                                <li class="wc_payment_method payment_method_<?php echo esc_attr($gateway->id); ?>">
                                    <input 
                                        id="payment_method_<?php echo esc_attr($gateway->id); ?>" 
                                        type="radio" 
                                        class="input-radio" 
                                        name="payment_method" 
                                        value="<?php echo esc_attr($gateway->id); ?>" 
                                        <?php checked($gateway->chosen, true); ?> 
                                    />
                                    <label for="payment_method_<?php echo esc_attr($gateway->id); ?>">
                                        <?php echo wp_kses_post($gateway->get_title()); ?>
                                        <?php if ($gateway->get_icon()) : ?>
                                            <?php echo wp_kses_post($gateway->get_icon()); ?>
                                        <?php endif; ?>
                                    </label>
                                    <?php if ($gateway->has_fields() || $gateway->get_description()) : ?>
                                        <div class="payment_box payment_method_<?php echo esc_attr($gateway->id); ?>">
                                            <?php $gateway->payment_fields(); ?>
                                        </div>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p class="woocommerce-notice woocommerce-notice--info woocommerce-info">
                            <?php
                            echo esc_html(
                                WC()->customer->get_billing_country() 
                                    ? __('No payment methods available for your country.', 'woocommerce')
                                    : __('Please enter your address to see payment options.', 'woocommerce')
                            );
                            ?>
                        </p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="cart-totals">
                <?php do_action('woocommerce_cart_totals_before_order_total'); ?>
                <div class="order-total">
                    <span>Итого:</span>
                    <span><?php wc_cart_totals_order_total_html(); ?></span>
                </div>
                <?php do_action('woocommerce_cart_totals_after_order_total'); ?>
            </div>
            <?php
            echo '<pre>';
print_r(WC()->payment_gateways->get_available_payment_gateways());
echo '</pre>';
            ?>
<div class="checkout-button">
                <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="checkout-button button alt wc-forward">
                    <?php esc_html_e('Оформить заказ', 'woocommerce'); ?>
                </a>
            </div>
        </div>
    </div>
    <?php do_action('woocommerce_after_cart_contents'); ?>
</form>
<?php endif; ?>
<?php do_action('woocommerce_after_cart'); ?>
<?php get_footer('shop'); ?>