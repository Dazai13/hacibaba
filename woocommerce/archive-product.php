<?php
if (!defined('ABSPATH')) {
    exit;
}
get_header('shop');
?>

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

    <div class="catalog__titles">
        <?php if (apply_filters('woocommerce_show_page_title', true)) : ?>
            <div class="catalog-header">
                <h1 class="woocommerce-products-header__title page-title">
                    <?php woocommerce_page_title(); ?>
                </h1>
                <p class="product-count">
                    <?php
                    global $wp_query;
                    echo sprintf(
                        _n('%s товар', '%s товаров', $wp_query->found_posts, 'woocommerce'),
                        number_format_i18n($wp_query->found_posts)
                    );
                    ?>
                </p>
            </div>
        <?php endif; ?>

        <div class="category-search">
            <form role="search" method="get" id="live-search-form">
                <svg id="search-form"><use xlink:href="<?php echo get_template_directory_uri(); ?>/images/sprite.svg#search-form"></use></svg>
                <input type="search" 
                        class="search-field" 
                        id="live-search-input"
                        placeholder="<?php echo esc_attr_x('Поиск товаров...', 'placeholder', 'woocommerce'); ?>" 
                        value="<?php echo get_search_query(); ?>" 
                        name="s" 
                        title="<?php echo esc_attr_x('Search for:', 'label', 'woocommerce'); ?>" />
                <input type="hidden" name="post_type" value="product" />
                <input type="hidden" name="product_cat" id="current-category" value="<?php echo esc_attr(get_queried_object()->term_id); ?>" />
            </form>
        </div>
    </div>


    <section id="catalog" class="catalog">
        <div class="container">
            <div class="catalog__inner inner" style="padding:2rem 4rem 6.5rem">
                <div class="catalog__wrapper" id="products-container">
                    <?php 
                    $current_category = get_queried_object();
                    
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
                    
                    if (isset($_GET['s']) && !empty($_GET['s'])) {
                        $args['s'] = sanitize_text_field($_GET['s']);
                    }
                    
                    $loop = new WP_Query($args);
                    
                    if ($loop->have_posts()) {
                        while ($loop->have_posts()) : $loop->the_post();
                            global $product;
                            
                            $product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'full');
                            ?>
                            
                            <div class="card product-item" >
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
                        echo '<p class="no-products">' . __('Товары не найдены', 'woocommerce') . '</p>';
                    }
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
        </div>
    </section>

<section class="additionally">
    <div class="container">
        <div class="additionally__innner inner">
            <h1 class="additionally__title">ДОПОЛНИТЕЛЬНО</h1>
            <h3 class="additionally__subtitle">Уважаемые покупатели! Вы можете дополнительно упаковать любой товар сайта в красивую подарочную упаковку.</h3>
            <div class="additionally__wrapper">
                <?php
                // Получаем товары из категории "Упаковка"
                $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => -1,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field' => 'slug',
                            'terms' => 'упаковка' // Укажите slug вашей категории "Упаковка"
                        )
                    )
                );
                
                $products = new WP_Query($args);
                
                if ($products->have_posts()) :
                    while ($products->have_posts()) : $products->the_post();
                        global $product;
                        $product_id = $product->get_id();
                        $image = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'full');
                        ?>
                        
                        <div class="additionally__item" data-product-id="<?php echo esc_attr($product_id); ?>">
                            <img src="<?php echo esc_url($image[0] ?? get_template_directory_uri() . '/images/placeholder.png'); ?>" alt="<?php the_title_attribute(); ?>">
                            <p class="card__title"><?php the_title(); ?></p>
                            
                            <div class="card__subtitle">
                                <?php
                                // Выводим варианты атрибутов для вариативных товаров
                                if ($product->is_type('variable')) {
                                    $attributes = $product->get_variation_attributes();
                                    
                                    foreach ($attributes as $attribute_name => $options) {
                                        $attribute_label = wc_attribute_label($attribute_name);
                                        ?>
                                        <div class="variation-options">
                                            <div class="variation-buttons" data-attribute="<?php echo esc_attr($attribute_name); ?>">
                                                <?php 
                                                $first_option = true;
                                                foreach ($options as $option) : 
                                                    ?>
                                                    <button 
                                                        type="button"
                                                        class="variation-button <?php echo $first_option ? 'active' : ''; ?>"
                                                        data-value="<?php echo esc_attr($option); ?>"
                                                    >
                                                        <?php
                                                        if (taxonomy_exists($attribute_name)) {
                                                            $term = get_term_by('slug', $option, $attribute_name);
                                                            echo esc_html($term ? $term->name : $option);
                                                        } else {
                                                            echo esc_html($option);
                                                        }
                                                        ?>
                                                    </button>

                                                    <?php 
                                                    $first_option = false;
                                                endforeach; 
                                                ?>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                            
                            <div class="card__line line"></div>
                            <p class="card__price"><?php echo $product->get_price_html(); ?></p>
                            <a href="#" class="additionally__btn add-to-cart-btn">
                                Добавить в корзину
                            </a>
                        </div>
                        
                    <?php endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p>Нет товаров в категории "Упаковка"</p>';
                endif;
                ?>
            </div>
            <p class="additionally__text">Просто добавьте ее в корзину и мы все упакуем!</p>
        </div>
    </div>
</section>
</div>
<?php get_footer('shop'); ?>
<script>
    jQuery(document).ready(function($) {
    // Обработчик клика на кнопку "Добавить в корзину"
    $('.additionally__item .add-to-cart-btn').on('click', function(e) {
        e.preventDefault();
        
        // Находим родительский элемент товара
        const $productItem = $(this).closest('.additionally__item');
        const productId = $productItem.data('product-id');
        
        // Для вариативных товаров собираем выбранные атрибуты
        let variationData = {};
        if ($productItem.find('.variation-buttons').length) {
            $productItem.find('.variation-buttons').each(function() {
                const attribute = $(this).data('attribute');
                const value = $(this).find('.variation-button.active').data('value');
                variationData['attribute_' + attribute] = value;
            });
        }
        
        // Добавляем товар в корзину через AJAX
        $.ajax({
            type: 'POST',
            url: wc_add_to_cart_params.ajax_url,
            data: {
                action: 'add_additional_product_to_cart',
                product_id: productId,
                variation_data: variationData,
                quantity: 1
            },
            beforeSend: function() {
                // Показываем индикатор загрузки (опционально)
                $productItem.find('.add-to-cart-btn').text('Добавляем...');
            },
            success: function(response) {
                if (response.success) {
                    // Обновляем мини-корзину
                    $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash]);
                    
                    // Возвращаем исходный текст кнопки
                    $productItem.find('.add-to-cart-btn').text('Добавлено!');
                    
                    // Через 2 секунды возвращаем исходный текст
                    setTimeout(function() {
                        $productItem.find('.add-to-cart-btn').text('Добавить в корзину');
                    }, 2000);
                } else {
                    // В случае ошибки
                    alert(response.data.message || 'Произошла ошибка при добавлении товара');
                    $productItem.find('.add-to-cart-btn').text('Добавить в корзину');
                }
            },
            error: function() {
                alert('Ошибка соединения с сервером');
                $productItem.find('.add-to-cart-btn').text('Добавить в корзину');
            }
        });
    });
});
</script>

<script>
jQuery(document).ready(function($) {

    // Обработка выбора вариаций
    $(document).on('click', '.variation-button', function() {
        const $button = $(this);
        const $container = $button.closest('.variation-buttons');
        $container.find('.variation-button').removeClass('active');
        $button.addClass('active');

        // Убираем красную рамку, если была
        $container.css('border', 'none');
    });

    // Добавление вариативного товара из блока "additionally" без popup
    $(document).on('click', '.additionally__item .add-to-cart-btn', function(e) {
        e.preventDefault();

        const $button = $(this);
        const $productItem = $button.closest('.additionally__item');
        const productId = $productItem.data('product-id');
        const variationData = {};
        let missingAttributes = [];

        // Сбор выбранных атрибутов
        $productItem.find('.variation-buttons').each(function() {
            const $active = $(this).find('.variation-button.active');
            const attributeName = $(this).data('attribute');

            if ($active.length === 0) {
                missingAttributes.push(attributeName);
                $(this).css('border', '1px solid red');
            } else {
                variationData[`attribute_${attributeName}`] = $active.data('value');
            }
        });

        if (missingAttributes.length > 0) {
            alert('Пожалуйста, выберите: ' + missingAttributes.join(', '));
            return;
        }

        $button.prop('disabled', true).text('Добавляем...');

        // Получаем ID вариации по атрибутам
        $.post(wc_add_to_cart_params.ajax_url, {
            action: 'find_product_variation',
            product_id: productId,
            attributes: variationData
        }, function(variation_id) {
            if (variation_id && variation_id > 0) {
                // Добавление в корзину
                $.post(wc_add_to_cart_params.ajax_url, {
                    action: 'woocommerce_ajax_add_to_cart',
                    product_id: productId,
                    variation_id: variation_id,
                    quantity: 1,
                    attributes: variationData
                }, function(response) {
                    if (response && response.fragments) {
                        $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash]);
                        alert('Товар добавлен в корзину!');
                    } else {
                        alert('Ошибка добавления в корзину.');
                    }
                }).always(function() {
                    $button.prop('disabled', false).text('Добавить в корзину');
                });
            } else {
                alert('Не удалось найти подходящую вариацию товара');
                $button.prop('disabled', false).text('Добавить в корзину');
            }
        }).fail(function() {
            alert('Ошибка при поиске вариации');
            $button.prop('disabled', false).text('Добавить в корзину');
        });
    });

    // Показываем popup только для товаров вне блока .additionally__item
    $(document).on('click', '.show-product-popup', function(e) {
        if ($(this).closest('.additionally__item').length > 0) {
            // Это товар из блока "Дополнительно" — ничего не делаем
            e.preventDefault();
            return;
        }

        e.preventDefault();

        const productId = $(this).data('product-id');
        $('#custom-popup').show();

        $.ajax({
            url: '<?php echo admin_url("admin-ajax.php"); ?>',
            type: 'POST',
            data: {
                action: 'get_product_data',
                product_id: productId
            },
            beforeSend: function() {
                $('#custom-popup .popup-content').html('<div class="loading">Загрузка товара...</div>');
            },
            success: function(response) {
                $('#custom-popup .popup-content').html(response);
            },
            error: function() {
                $('#custom-popup .popup-content').html('<p>Ошибка загрузки товара</p>');
            }
        });
    });

    // Закрытие popup
    $(document).on('click', '#close-popup, .popup-close', function() {
        $('#custom-popup').hide();
    });

});
</script>

