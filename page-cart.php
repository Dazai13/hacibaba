<?php
/**
 * Шаблон корзины с товарами в виде блоков
 */
if (!defined('ABSPATH')) {
    exit;
}

get_header('shop'); ?>

<div class="container">
    <?php woocommerce_breadcrumb(); ?>
</div>

<?php do_action('woocommerce_before_cart'); ?>

<form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
    <?php do_action('woocommerce_before_cart_contents'); ?>
    
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
								echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key) .'');
                            }
                            
                            do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);
                            echo wc_get_formatted_cart_item_data($cart_item);
                            ?>
                        </div>
                        
                        <div class="cart-item-price">
                            <?php
                                echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
                            ?>
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
                        
                        <div class="cart-item-subtotal">
                            <?php
                                echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
                            ?>
                        </div>
                        
                        <div class="cart-item-remove">
                            <?php
                                echo apply_filters(
                                    'woocommerce_cart_item_remove_link',
                                    sprintf(
                                        '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s</a>',
                                        esc_url(wc_get_cart_remove_url($cart_item_key)),
                                        esc_attr__('Remove this item', 'woocommerce'),
                                        esc_attr($product_id),
                                        esc_attr($_product->get_sku()),
                                        esc_html__('Remove', 'woocommerce')
                                    ),
                                    $cart_item_key
                                );
                            ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    
    <div class="cart-actions">
        <?php if (wc_coupons_enabled()) { ?>
            <div class="coupon">
                <label for="coupon_code"><?php esc_html_e('Coupon:', 'woocommerce'); ?></label> 
                <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e('Coupon code', 'woocommerce'); ?>" /> 
                <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>"><?php esc_html_e('Apply coupon', 'woocommerce'); ?></button>
                <?php do_action('woocommerce_cart_coupon'); ?>
            </div>
        <?php } ?>

        <button type="submit" class="button" name="update_cart" value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>"><?php esc_html_e('Update cart', 'woocommerce'); ?></button>
        
        <?php do_action('woocommerce_cart_actions'); ?>
        <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
    </div>
    
    <?php do_action('woocommerce_after_cart_contents'); ?>
</form>

<?php do_action('woocommerce_after_cart'); ?>

<style>
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
    
    .cart-item-image img {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 0 auto 15px;
    }
    
    .cart-item-name {
        font-weight: bold;
        margin-bottom: 10px;
    }
    
    .cart-item-price,
    .cart-item-subtotal {
        margin: 5px 0;
        font-size: 16px;
    }
    
    .cart-item-quantity {
        margin: 10px 0;
    }
    
    .cart-item-quantity .quantity {
        display: flex;
        align-items: center;
    }
    
    .cart-item-quantity input {
        width: 60px;
        text-align: center;
    }
    
    .cart-item-remove a {
        color: #ff0000;
        text-decoration: none;
    }
    
    .cart-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: center;
        margin-top: 20px;
    }
    
    .coupon {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    
    @media (max-width: 768px) {
        .cart-items-grid {
            grid-template-columns: 1fr;
        }
        
        .cart-actions {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .coupon {
            flex-direction: column;
            align-items: flex-start;
            width: 100%;
        }
    }
</style>

<?php get_footer('shop'); ?>