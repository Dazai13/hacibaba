<?php
/**
 * Empty cart template
 *
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<div class="empty-cart-container">
    <div class="empty-cart-content">
        <?php
        /**
         * Хук: woocommerce_cart_is_empty.
         *
         * @hooked wc_empty_cart_message - 10
         */
        do_action( 'woocommerce_cart_is_empty' );
        ?>
        
        <div class="empty-cart-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
        </div>
        
        <h2 class="empty-cart-title"><?php esc_html_e( 'Ваша корзина пуста', 'woocommerce' ); ?></h2>
        
        <p class="empty-cart-text"><?php esc_html_e( 'Похоже, вы еще ничего не добавили в корзину.', 'woocommerce' ); ?></p>
        
        <div class="empty-cart-button">
            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="button">
                <?php esc_html_e( 'Вернуться в магазин', 'woocommerce' ); ?>
            </a>
        </div>
    </div>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>