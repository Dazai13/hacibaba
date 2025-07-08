<?php
/**
 * Product Popup Template with Exclusive Main Image Slider
 * 
 * @param WC_Product $product
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Получаем изображения
$main_image_id = $product->get_image_id();
$gallery_ids = $product->get_gallery_image_ids();
?>

<div class="product__gallery">
<?php if ($main_image_id || !empty($gallery_ids)) : ?>
    <div class="popup-product-gallery-exclusive">
        <!-- Главное изображение (только одно) -->
        <div class="exclusive-main-image">
            <?php if ($main_image_id) : ?>
                <?php echo wp_get_attachment_image($main_image_id, 'large', false, [
                    'class' => 'current-main-image',
                    'data-id' => $main_image_id
                ]); ?>
            <?php endif; ?>
            <button class="exclusive-prev"><svg id="image-prev"><use xlink:href="<?php echo get_template_directory_uri(); ?>/images/sprite.svg#image-prev"></use></svg></button>
            <button class="exclusive-next"><svg id="image-next"><use xlink:href="<?php echo get_template_directory_uri(); ?>/images/sprite.svg#image-next"></use></svg></button>
        </div>

        <!-- Галерея (все остальные изображения) -->
        <div class="exclusive-gallery-thumbs">
            <?php foreach ($gallery_ids as $image_id) : ?>
                <div class="exclusive-thumb" data-id="<?php echo $image_id; ?>">
                    <?php echo wp_get_attachment_image($image_id, 'large'); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
</div>

<div class="product__info">
    <!-- Остальной контент popup'а -->
    <h3 class="popup-product-title"><?php echo $product->get_name(); ?></h3>
    <div class="popup-product-description"><?php echo apply_filters('the_content', $product->get_description()); ?></div>
    
    <?php if ($product->get_attributes()) : ?>
        <div class="popup-product-attributes">
            <div class="product-attributes-table">
                <?php foreach ($product->get_attributes() as $attribute) : ?>
                    <?php 
                    $attribute_name = $attribute->get_name();
                    $attribute_label = wc_attribute_label($attribute_name);
                    $attribute_values = $product->get_attribute($attribute_name);
                    
                    if ($attribute_values) : ?>
                        <div class="product__attribute">
                            <div class="attribute-name">
                                <p class="product__attribute-name"><?php echo esc_html($attribute_label); ?></p>
                                <span class="dotted-line"></span>
                            </div>
                            <p class="product__attribute-value"><?php echo esc_html($attribute_values); ?></p>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="popup-product-add-to-cart">
        <div class="popup-product-add-to-cart">
            <button type="button" class="card__btn single_add_to_cart_button button alt">
                <?php _e('Добавить в корзину', 'woocommerce'); ?>
            </button>
        </div>
    </div>
</div>

<svg id="close-popup" class="popup-close">
    <use xlink:href="<?php echo get_template_directory_uri(); ?>/images/sprite.svg#popup"></use>
</svg>

<script>
jQuery(document).ready(function($) {
    function initExclusiveGallery() {
        const $gallery = $('.popup-product-gallery-exclusive');
        const $mainImgContainer = $('.exclusive-main-image');
        let $mainImg = $mainImgContainer.find('img.current-main-image');
        const $thumbsContainer = $('.exclusive-gallery-thumbs');
        const $navButtons = $mainImgContainer.find('.exclusive-prev, .exclusive-next');
        
        function activateThumb($thumb) {
            const $thumbs = $thumbsContainer.find('.exclusive-thumb');
            $thumbs.show().removeClass('active');
            $thumb.addClass('active').hide();
            updateMainImage($thumb);
        }
        
        function updateMainImage($thumb) {
            const imgSrc = $thumb.find('img').attr('src');
            const newImg = $('<img>', {
                src: imgSrc,
                class: 'current-main-image',
                css: { width: '100%' }
            });
            
            $mainImg.replaceWith(newImg);
            $mainImg = newImg;
        }
        
        $thumbsContainer.on('click', '.exclusive-thumb', function() {
            activateThumb($(this));
        });
        
        $('.exclusive-prev').on('click', function(e) {
            e.stopPropagation();
            const $thumbs = $thumbsContainer.find('.exclusive-thumb');
            const $active = $thumbs.filter('.active');
            let $prev = $active.prev('.exclusive-thumb');
            
            if (!$prev.length) {
                $prev = $thumbs.last();
            }
            
            activateThumb($prev);
        });
        
        $('.exclusive-next').on('click', function(e) {
            e.stopPropagation();
            const $thumbs = $thumbsContainer.find('.exclusive-thumb');
            const $active = $thumbs.filter('.active');
            let $next = $active.next('.exclusive-thumb');
            
            if (!$next.length) {
                $next = $thumbs.first();
            }
            
            activateThumb($next);
        });
        
        let touchStartX = 0;
        $gallery.on('touchstart', function(e) {
            touchStartX = e.originalEvent.touches[0].clientX;
        });
        
        $gallery.on('touchend', function(e) {
            const touchEndX = e.originalEvent.changedTouches[0].clientX;
            const diff = touchStartX - touchEndX;
            
            if (diff > 50) {
                $('.exclusive-next').trigger('click');
            } else if (diff < -50) {
                $('.exclusive-prev').trigger('click');
            }
        });
        
        activateThumb($thumbsContainer.find('.exclusive-thumb').first());
    }
    
    // Инициализация галереи
    initExclusiveGallery();
    
    // Обработка добавления в корзину
// Обработка добавления в корзину
$('.single_add_to_cart_button').on('click', function(e) {
    e.preventDefault();
    var $button = $(this);

    $.ajax({
        type: 'POST',
        url: wc_add_to_cart_params.ajax_url,
        data: {
            action: 'woocommerce_add_to_cart',
            product_id: <?php echo $product->get_id(); ?>,
            quantity: 1
        },
        beforeSend: function() {
            $button.addClass('');
        },
        complete: function() {
            $button.removeClass('');
        },
        success: function(response) {
            if (response.error) {
                alert(response.error);
                return;
            }

            // Способ 1: Через фрагменты WooCommerce (рекомендуется)
            if (response.fragments) {
                $.each(response.fragments, function(key, value) {
                    $(key).replaceWith(value);
                });
            }

            // Способ 2: Ручной запрос количества
            $('.icon__shop-item').text(WC_CART_FRAGMENTS_REFRESHED ? 
                $(response.fragments['.icon__shop-item']).text() : 
                (parseInt($('.icon__shop-item').text()) + 1)
            );

            $('#close-popup').trigger('click');
        },
        error: function() {
            alert('Ошибка добавления в корзину');
        }
    });
});
    // Закрытие попапа
    $('#close-popup').on('click', function() {
        $(this).closest('#custom-popup').fadeOut(300);
    });
});
</script>