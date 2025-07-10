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
            
            <!-- Кнопка перехода к данным покупателя (видна только на мобильных) -->
            <div class="show-customer-data-button">
                <button type="button" class="button alt">Далее</button>
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
                <div class="payment-methods">
                    <h3>Способы оплаты</h3>
                    <div class="wc_payment_method payment_method_stripe">
                            <input 
  id="payment_method_stripe" 
  type="radio" 
  class="input-radio" 
  name="payment_method" 
  value="stripe" 
  checked="checked" 
  style="background: none; accent-color: black;"
>
                            <label for="payment_method_stripe">
                                Оплата банковской картой онлайн
                            </label>
                        </div>
                </div>
            </div>
  
            </script>
            <div class="cart-totals">
                <?php do_action('woocommerce_cart_totals_before_order_total'); ?>
                <div class="order-total">
                    <span>Итого к оплате:</span>
                    <span><?php wc_cart_totals_order_total_html(); ?></span>
                </div>
                <?php do_action('woocommerce_cart_totals_after_order_total'); ?>
            </div>
            <div class="checkout-buttons">
                <button type="button" id="open-checkout-popup" class="checkout-button button alt wc-forward">
                    <?php esc_html_e('Оплатить заказ', 'woocommerce'); ?>
                </button>
            </div>
        </div>
    </div>
    <?php do_action('woocommerce_after_cart_contents'); ?>
</form>

<div id="checkout-popup" class="checkout-popup" style="display: none;">
    <div class="checkout-popup-overlay"></div>
    <div class="checkout-popup-content">

        <div class="checkout-step-content" id="step-1-content">
            <div class="popup-header">
            <div class="popup__haeder-text">
                <h3>Оплата онлайн </h3>
                <div class="checkout-steps">
                    <div class="step" data-step="1">1</div>
                    <div class="step-line"></div>
                    <div class="step" data-step="2">2</div>
                </div>
            </div>
            <div class="popup__header-close">
                <svg id="close-popup" class="popup-close">
                    <use xlink:href="<?php echo get_template_directory_uri(); ?>/images/sprite.svg#popup"></use>
                </svg>
            </div>                 
            </div>
            <div class="popup-body">
                <img src="<?php echo get_template_directory_uri(); ?>/images/card.png" alt="">
                <form id="payment-form">
                    <div class="form-group">
                        <input type="text" id="card-number" placeholder="1234 5678 9123 4567" maxlength="19" required>
                        <img src="<?php echo get_template_directory_uri(); ?>/images/mir.png" alt="">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" id="card-expiry" placeholder="MM/YY" maxlength="5" required>
                        </div>
                        
                        <div class="form-group">
                            <input type="text" id="card-cvc" placeholder="123" maxlength="3" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <input type="text" id="card-name" placeholder="IVAN IVANOV" required>
                    </div>
                    <div class="form-cards">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/mir.png" width="61px" height="18px" alt="">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/visa.png" alt="">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/mastercard.png" alt="">
                    </div>
                </form>
            </div>
            <div class="popup-footer"><button type="submit" id="submit-payment" class="checkout-button button alt">Оплатить</button></div>    
        </div>
        <div class="checkout-step-content" id="step-2-content" style="display: none;">
            <div class="payment-status">
            <div class="popup-header">
            <div class="popup__haeder-text">
                <h3>Оплата онлайн </h3>
                <div class="checkout-steps">
                    <div class="step" data-step="1">1</div>
                    <div class="step-line"></div>
                    <div class="step active" data-step="2">2</div>
                </div>
            </div>
            <div class="popup__header-close">
                <svg id="close-popup" class="popup-close">
                    <use xlink:href="<?php echo get_template_directory_uri(); ?>/images/sprite.svg#popup"></use>
                </svg>
            </div>                 
            </div>
            <div class="popup-body">
                <div class="popup-succsses">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/Success.png" alt="">
                    <h4>Успешно</h4>
                    <p>Ваш заказ будет отправлен в течение 3-х дней. После передачи в службу доставки вам поступит СМС с трек-номером для отслеживания.</p>
                </div>
            </div>
            <div class="popup-footer"><button type="submit" id="return-to-shop" class="checkout-button button alt">Закрыть</button></div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const popup = document.getElementById('checkout-popup');
    const closeButton = document.getElementById('close-popup');
    const overlay = document.querySelector('.checkout-popup-overlay');
    const paymentForm = document.getElementById('payment-form');
    const returnToShopBtn = document.getElementById('return-to-shop');
    const cardNumberInput = document.getElementById('card-number');
    const cardExpiryInput = document.getElementById('card-expiry');
    const submitButton = document.getElementById('submit-payment');
    const openPopupBtn = document.getElementById('open-checkout-popup');

    // Функция открытия popup
    function openCheckoutPopup() {
        if (popup) {
            popup.style.display = 'block';
            document.body.style.overflow = 'hidden';
            document.querySelector('.step[data-step="1"]').classList.add('active');
            document.querySelector('.step[data-step="2"]').classList.remove('active');
            document.getElementById('step-1-content').style.display = 'block';
            document.getElementById('step-2-content').style.display = 'none';
        }
    }

    // Функция закрытия popup
    function closeCheckoutPopup() {
        if (popup) {
            popup.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    // Навешиваем обработчики
    if (closeButton) closeButton.addEventListener('click', closeCheckoutPopup);
    if (overlay) overlay.addEventListener('click', closeCheckoutPopup);
    if (openPopupBtn) openPopupBtn.addEventListener('click', openCheckoutPopup);

    const popupContent = document.querySelector('.checkout-popup-content');
    if (popupContent) {
        popupContent.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    }

    // Форматирование ввода номера карты
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\s+/g, '');
            if (value.length > 0) {
                value = value.match(/.{1,4}/g)?.join(' ') || '';
            }
            e.target.value = value;
        });
    }

    // Форматирование ввода срока действия
    if (cardExpiryInput) {
        cardExpiryInput.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });
    }

    // Обработка кнопки "Оплатить"
    if (submitButton) {
        submitButton.addEventListener('click', function (e) {
            e.preventDefault();

            const cardNumber = cardNumberInput.value.replace(/\s+/g, '');
            const cardExpiry = cardExpiryInput.value;
            const cardCvc = document.getElementById('card-cvc').value;
            const cardName = document.getElementById('card-name').value;

            if (!cardNumber || cardNumber.length < 16) {
                alert('Введите корректный номер карты');
                return;
            }

            if (!cardExpiry || cardExpiry.length < 5) {
                alert('Введите срок действия карты (MM/YY)');
                return;
            }

            if (!cardCvc || cardCvc.length < 3) {
                alert('Введите CVC/CVV код');
                return;
            }

            if (!cardName) {
                alert('Введите имя владельца карты');
                return;
            }

            // Переход на шаг 2
            document.querySelector('.step[data-step="1"]').classList.remove('active');
            document.querySelector('.step[data-step="2"]').classList.add('active');
            document.getElementById('step-1-content').style.display = 'none';
            document.getElementById('step-2-content').style.display = 'block';

            // Имитация обработки платежа
            setTimeout(function () {
                const statusIcon = document.querySelector('.status-icon');
                if (statusIcon) {
                    statusIcon.classList.remove('loading');
                    statusIcon.classList.add('success');
                }

                document.querySelector('.status-title').textContent = 'Оплата прошла успешно!';
                document.querySelector('.status-message').textContent = 'Ваш заказ оформлен. Спасибо за покупку!';

                // Очистка корзины WooCommerce
                if (typeof wc_checkout_params !== 'undefined') {
                    fetch(wc_checkout_params.ajax_url, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'action=woocommerce_clear_cart&security=' + wc_checkout_params.clear_cart_nonce
                    });
                }
            }, 2000);
        });
    }

    // Кнопка "Вернуться в магазин"
    if (returnToShopBtn) {
        returnToShopBtn.addEventListener('click', function () {
            closeCheckoutPopup();
            window.location.href = '/';
        });
    }
});
</script>


<?php endif; ?>

<script>
jQuery(document).ready(function($) {
    function checkScreenSize() {
        if ($(window).width() > 1200) {
            $('.cart-items-column').removeClass('hidden');
            $('.customer-data-column').removeClass('active');
            
            $('.woocommerce-breadcrumb').html('<a href="<?php echo esc_url(home_url()); ?>">Главная</a> &gt; Корзина');
        }
    }

    checkScreenSize();
    
    $(window).on('resize', function() {
        checkScreenSize();
    });

    function updateBreadcrumb(section) {
        if ($(window).width() <= 1200) {
            var breadcrumb = $('.woocommerce-breadcrumb');
            
            if (section === 'customer') {
                breadcrumb.html('<a href="<?php echo esc_url(home_url()); ?>">Главная</a> &gt; <a href="<?php echo esc_url(wc_get_cart_url()); ?>">Корзина</a> &gt; Контактные данные');
            } else {
                breadcrumb.html('<a href="<?php echo esc_url(home_url()); ?>">Главная</a> &gt; Корзина');
            }
        }
    }

    $('.show-customer-data-button button').on('click', function() {
        $('.cart-items-column').addClass('hidden');
        $('.customer-data-column').addClass('active');
        updateBreadcrumb('customer');
    });

    $('.back-to-cart-button button').on('click', function() {
        $('.cart-items-column').removeClass('hidden');
        $('.customer-data-column').removeClass('active');
        updateBreadcrumb('cart');
    });

    $(document).on('change', 'input[name="payment_method"]', function() {
        $('body').trigger('update_checkout');
    });

    $(document).on('change', 'input[name="shipping_method"]', function() {
        $('body').trigger('update_checkout');
    });

    $(document).on('updated_checkout', function() {
        console.log('Checkout updated');
    });
});
</script>

<?php do_action('woocommerce_after_cart'); ?>
<?php get_footer('shop'); ?>