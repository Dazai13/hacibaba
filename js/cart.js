jQuery(document).ready(function($) {

    function updateCartCounter() {
        $.post(wc_cart_params.ajax_url, { action: 'get_cart_count' }, function(response) {
            $('.icon__shop-item').text(response);
        });
    }

    function updateWooCartQty(input) {
        const $form = input.closest('form.woocommerce-cart-form');
        if (!$form.length) {
            console.error('Форма корзины не найдена!');
            return;
        }

        const nonceInput = $form.find('input[name="woocommerce-cart-nonce"]');
        const nonce = nonceInput.length ? nonceInput.val() : wc_cart_params.nonce;

        const data = {
            action: 'update_cart',
            'update_cart': 'Обновить корзину',
            'woocommerce-cart-nonce': nonce,
        };

        // Отправляем только текущее количество товара
        const name = input.attr('name'); // например "cart[abc123][qty]"
        const val = input.val();
        data[name] = val;

        $.post(wc_cart_params.ajax_url, data)
            .done(function(response) {
                if (response && response.fragments) {
                    $.each(response.fragments, function(key, value) {
                        $(key).replaceWith(value);
                    });
                    updateCartCounter();
                } else {
                    location.reload(); // Если нет фрагментов — перезагружаем страницу
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.error('Ошибка AJAX update_cart:', textStatus, errorThrown);
            });
    }

    function removeItemFromCart(input) {
        const match = input.attr('name').match(/cart\[(.*?)\]\[qty\]/);
        if (!match) return;
        const cartItemKey = match[1];

        $.post(wc_cart_params.ajax_url, {
            action: 'remove_from_cart',
            cart_item_key: cartItemKey,
            _wpnonce: wc_cart_params.nonce
        }, function(response) {
            if (response.success) {
                location.reload();
            } else {
                console.error(response.data);
            }
        });
    }

    function handleQuantityChange(input, isIncrease) {
        let val = parseInt(input.val()) || 0;
        const min = parseInt(input.attr('min')) || 0;
        const max = parseInt(input.attr('max')) || Infinity;
        const newVal = isIncrease ? Math.min(val + 1, max) : Math.max(val - 1, min);
        input.val(newVal).trigger('change');

        if (newVal === 0) {
            removeItemFromCart(input);
        } else {
            updateWooCartQty(input);
        }
    }

    $(document).on('click', '.quantity-plus', function(e) {
        e.preventDefault();
        const input = $(this).closest('.quantity-wrapper').find('.qty');
        if (!input.length) {
            console.error('Инпут .qty не найден рядом с кнопкой +');
            return;
        }
        handleQuantityChange(input, true);
    });

    $(document).on('click', '.quantity-minus', function(e) {
        e.preventDefault();
        const input = $(this).closest('.quantity-wrapper').find('.qty');
        if (!input.length) {
            console.error('Инпут .qty не найден рядом с кнопкой −');
            return;
        }
        handleQuantityChange(input, false);
    });

    updateCartCounter();
});
document.addEventListener('DOMContentLoaded', function() {
    // Находим все элементы товаров в корзине
    const cartItems = document.querySelectorAll('.cart-item-block');
    
    // Если товаров больше одного, добавляем разделители
    if (cartItems.length > 1) {
        cartItems.forEach((item, index) => {
            // Пропускаем первый элемент
            if (index > 0) {
                // Создаем элемент-разделитель
                const divider = document.createElement('div');
                divider.className = 'cart-item-divider';
                
                // Вставляем разделитель перед текущим элементом
                item.parentNode.insertBefore(divider, item);
            }
        });
    }
});