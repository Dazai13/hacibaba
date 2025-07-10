<?php
function my_theme_scripts() {
    // Подключение CSS
    wp_enqueue_style('slick-style', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css');
    wp_enqueue_style('slick-theme-style', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css');
    wp_enqueue_style('dadata-theme-style', 'https://cdn.jsdelivr.net/npm/suggestions-jquery@21.12.0/dist/css/suggestions.min.css');
    wp_enqueue_style('main-style', get_stylesheet_uri());
    wp_enqueue_style('custom-css', get_template_directory_uri() . '/css/style.css');

    // Подключение скриптов
    if (!is_admin()) {
        wp_deregister_script('jquery');
        wp_register_script(
            'jquery',
            'https://code.jquery.com/jquery-3.6.0.min.js',
            false, // нет зависимостей
            '3.6.0',
            false // в футере
        );
        wp_enqueue_script('jquery');
    }

    wp_register_script(
        'slick-js',
        'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js',
        array('jquery'), // зависит от jQuery
        '1.8.1',
        false
    );
    wp_enqueue_script('slick-js');
    
// Подключение стилей и скриптов
add_action('wp_enqueue_scripts', 'my_theme_enqueue_scripts');
function my_theme_enqueue_scripts() {
    // Подключаем jQuery (уже встроен в WordPress)
    wp_enqueue_script('jquery');
    
    // DaData Suggestions JS + CSS
    wp_register_script(
        'dadata-js',
        'https://cdn.jsdelivr.net/npm/suggestions-jquery@21.12.0/dist/js/jquery.suggestions.min.js',
        array('jquery'), // Зависит от jQuery
        '21.12.0',
        true // Подключаем в footer
    );
    wp_enqueue_script('dadata-js');
    
    // InputMask
    wp_register_script(
        'mask-js',
        'https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js',
        array('jquery'), // Зависит от jQuery
        '5.0.6',
        true // Подключаем в footer
    );
    wp_enqueue_script('mask-js');
    
    // Наш скрипт с инициализацией DaData
    wp_enqueue_script(
        'dadata-init',
        get_template_directory_uri() . '/js/dadata.js',
        array('jquery', 'mask-js', 'dadata-js'), // Явно указываем зависимости
        filemtime(get_template_directory() . '/js/dadata.js'),
        true // Подключаем в footer
    );
    
    // Стили DaData (если нужны)
    wp_enqueue_style(
        'dadata-css',
        'https://cdn.jsdelivr.net/npm/suggestions-jquery@21.12.0/dist/css/suggestions.min.css',
        array(),
        '21.12.0'
    );
}
    wp_enqueue_script(
        'utils',
        get_template_directory_uri() . '/js/utils.js',
        array('jquery', 'slick-js'),
        filemtime(get_template_directory() . '/js/utils.js'),
        false
    );
        wp_enqueue_script(
        'slider',
        get_template_directory_uri() . '/js/sliders.js',
        array('jquery', 'slick-js'),
        filemtime(get_template_directory() . '/js/sliders.js'),
        false
    );

    wp_enqueue_script(
        'main',
        get_template_directory_uri() . '/js/main.js',
        array('jquery', 'slick-js'),
        filemtime(get_template_directory() . '/js/main.js'),
        false
    );
    wp_enqueue_script(
        'data-target',
        get_template_directory_uri() . '/js/data-target.js',
        array(),
        filemtime(get_template_directory() . '/js/data-target.js'),
        false
    );
    wp_enqueue_script(
        'responsive-elements',
        get_template_directory_uri() . '/js/responsive-elements.js',
        array('jquery', 'slick-js'),
        filemtime(get_template_directory() . '/js/responsive-elements.js'),
        false
    );
    wp_enqueue_script(
        'search',
        get_template_directory_uri() . '/js/search.js',
        array('jquery', 'slick-js'),
        filemtime(get_template_directory() . '/js/search.js'),
        false
    );
        wp_enqueue_script(
        'popup',
        get_template_directory_uri() . '/js/popup.js',
        array('jquery', 'slick-js'),
        filemtime(get_template_directory() . '/js/popup.js'),
        false
    );
}
add_action('wp_enqueue_scripts', 'my_theme_scripts');
function mytheme_add_woocommerce_support() {
    add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'mytheme_add_woocommerce_support');

add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

function enqueue_blog_scripts() {
    wp_enqueue_script(
        'blog-script', 
        get_template_directory_uri() . '/js/blog.js', 
        array('jquery'),
        '1.0', 
        false
    );
    wp_localize_script(
        'blog-script',
        'blogVars',
        array(
            'templateUri' => get_template_directory_uri(),
            'postsPageUrl' => get_permalink(get_option('page_for_posts')),
            'restUrl' => rest_url('wp/v2/')
        )
    );
}
add_action('wp_enqueue_scripts', 'enqueue_blog_scripts');

function enqueue_about_scripts() {
    wp_enqueue_script(
        'about-script', 
        get_template_directory_uri() . '/js/about-page.js', 
        array('jquery'),
        '1.0', 
        false
    );
    wp_localize_script(
        'about-script',
        'aboutVars',
        array(
            'templateUri' => get_template_directory_uri(),
            'postsPageUrl' => get_permalink(get_option('page_for_posts'))
        )
    );
}
add_action('wp_enqueue_scripts', 'enqueue_about_scripts');

function enqueue_footer_scripts() {
    wp_enqueue_script(
        'footer-script', 
        get_template_directory_uri() . '/js/footer.js', 
        array('jquery'),
        '1.0', 
        false
    );
    wp_localize_script(
        'footer-script',
        'footerVars',
        array(
            'templateUri' => get_template_directory_uri(),
            'postsPageUrl' => get_permalink(get_option('page_for_posts'))
        )
    );
}
add_action('wp_enqueue_scripts', 'enqueue_footer_scripts');

add_action('wp_ajax_product_search', 'product_search_callback');
add_action('wp_ajax_nopriv_product_search', 'product_search_callback');

function product_search_callback() {
    $query = sanitize_text_field($_POST['query']);
    
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 10,
        's' => $query,
        'meta_query' => array(
            array(
                'key' => '_stock_status',
                'value' => 'instock'
            )
        )
    );
    
    $search_query = new WP_Query($args);
    $output = '';
    
    if ($search_query->have_posts()) {
        while ($search_query->have_posts()) {
            $search_query->the_post();
            $product = wc_get_product(get_the_ID());
            
            $output .= '<a href="' . get_permalink() . '" class="search-result-item">';
            $output .= '<div class="search-result-thumbnail">' . get_the_post_thumbnail(get_the_ID(), 'thumbnail') . '</div>';
            $output .= '<div class="search-result-content">';
            $output .= '<h4 class="search-result-title">' . get_the_title() . '</h4>';
            $output .= '<div class="search-result-price">' . $product->get_price_html() . '</div>';
            $output .= '</div>';
            $output .= '</a>';
        }
        wp_reset_postdata();
        wp_send_json_success($output);
    } else {
        wp_send_json_error('Ничего не найдено');
    }
}

// Удаление обработки якорных ссылок
function remove_anchor_links() {
    wp_dequeue_script('smooth-scroll'); // Если есть скрипт плавной прокрутки
    remove_action('wp_footer', 'some_smooth_scroll_script'); // Удаление хуков
}
add_action('wp_enqueue_scripts', 'remove_anchor_links', 100);

add_theme_support('woocommerce');

function enqueue_footer1_scripts() {
    // Подключаем JS-файл футера
    wp_enqueue_script(
        'footer-script', 
        get_template_directory_uri() . '/js/footer.js', 
        array('jquery'), // Зависимости (jQuery)
        null, 
        false // Подключаем в футере
    );
    
    // Определяем базовый URL для якорных ссылок
    $is_home = is_front_page() || is_home();
    $base_url = $is_home ? '' : home_url('/');
    
    // Подготавливаем данные для передачи в JS
    $footer_data = array(
        'templateUri' => get_template_directory_uri(), // Путь к теме
        'mainLinks' => array(
            array(
                'text' => 'Категории',
                'href' => $is_home ? '#sweets' : $base_url . '#sweets',
                'class' => 'main-link'
            ),
            array(
                'text' => 'О нас',
                'href' => $is_home ? '#about' : $base_url . '#about',
                'class' => 'main-link'
            ),
            array(
                'text' => 'Блог',
                'href' => '/blog',
                'class' => 'main-link'
            ),
            array(
                'text' => 'Галерея',
                'href' => '/gallery',
                'class' => 'main-link'
            )
        ),
        'subLinks' => array(
            array(
                'text' => 'Рахат-лукум',
                'href' => esc_url(get_term_link('рахат-лукум', 'product_cat'))
            ),
            array(
                'text' => 'Пахлава',
                'href' => esc_url(get_term_link('пахлава', 'product_cat'))
            ),
            array(
                'text' => 'Кофе',
                'href' => esc_url(get_term_link('кофе', 'product_cat'))
            ),
            array(
                'text' => 'Пишмание',
                'href' => esc_url(get_term_link('пишмание', 'product_cat'))
            )
        ),
        'contacts' => array(
            'phone' => '8 903 522 53 45',
            'email' => 'email@mail.ru'
        ),
        'policyText' => 'Политика конфиденциальности',
        'socialIcons' => array('whatsapp', 'telegram', 'mail', 'number')
    );
    
    // Локализуем скрипт (передаем данные из PHP в JS)
    wp_localize_script('footer-script', 'footerVars', $footer_data);
}
add_action('wp_enqueue_scripts', 'enqueue_footer1_scripts');


add_action('wp_ajax_live_product_search', 'live_product_search');
add_action('wp_ajax_nopriv_live_product_search', 'live_product_search');

function live_product_search() {
    $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
    $category_id = isset($_POST['category']) ? intval($_POST['category']) : 0;

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    );

    if (!empty($search)) {
        $args['s'] = $search;
    }

    if ($category_id > 0) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $category_id,
            )
        );
    }

    $loop = new WP_Query($args);

    ob_start();

    if ($loop->have_posts()) {
        while ($loop->have_posts()) : $loop->the_post();
            global $product;
            $product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'full');
            ?>
            <div class="card product-item">
                <a href="<?php echo get_permalink($product->get_id()); ?>">
                    <?php if ($product_image) : ?>
                        <img src="<?php echo esc_url($product_image[0]); ?>" class="card__image" alt="<?php echo esc_attr($product->get_name()); ?>">
                    <?php else : ?>
                        <img src="<?php echo wc_placeholder_img_src(); ?>" class="card__image" alt="Placeholder">
                    <?php endif; ?>
                </a>
                <p class="card__title"><?php echo esc_html($product->get_name()); ?></p>
                <p class="card__subtitle"><?php echo esc_html($product->get_short_description()); ?></p>
                <div class="card__line line"></div>
                <p class="card__price"><?php echo $product->get_price_html(); ?></p>
                <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="card__btn">Добавить в корзину</a>
            </div>
            <?php
        endwhile;
    } else {
        echo '<p class="no-products">' . __('Товары не найдены', 'woocommerce') . '</p>';
    }

    wp_reset_postdata();
    echo ob_get_clean();
    wp_die();
}


// Обработчик AJAX запроса для получения данных товара
add_action('wp_ajax_get_product_data', 'get_product_data');
add_action('wp_ajax_nopriv_get_product_data', 'get_product_data');

function get_product_data() {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $product = wc_get_product($product_id);
    
    if ($product && is_a($product, 'WC_Product')) {
        ob_start();
        // Подключаем шаблон
        include get_template_directory() . '/product-popup-template.php';
        echo ob_get_clean();
    } else {
        echo '<p>Товар не найден</p>';
    }
    
    wp_die();
}

// Обработка AJAX запроса для загрузки попапа с товаром
add_action('wp_ajax_load_product_popup', 'load_product_popup_handler');
add_action('wp_ajax_nopriv_load_product_popup', 'load_product_popup_handler');

function load_product_popup_handler() {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $product = wc_get_product($product_id);
    
    if ($product && is_a($product, 'WC_Product')) {
        // Подключаем тот же шаблон
        include get_template_directory() . '/product-popup-template.php';
    } else {
        echo '<p>Товар не найден</p>';
    }
    
    wp_die();
}

add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {
    $fragments['.icon__shop-item'] = '<span class="icon__shop-item">' . WC()->cart->get_cart_contents_count() . '</span>';
    return $fragments;
});

add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields');
function custom_override_checkout_fields($fields) {
    // Изменяем поле "Имя"
    $fields['billing']['billing_first_name']['label'] = 'Ваше имя';
    $fields['billing']['billing_first_name']['placeholder'] = 'Введите ваше имя';
    
    // Добавляем новое поле
    $fields['billing']['billing_custom_field'] = array(
        'label' => 'Дополнительная информация',
        'placeholder' => 'Ваши пожелания',
        'required' => false,
        'class' => array('form-row-wide'),
        'clear' => true,
        'type' => 'textarea'
    );
    
    // Удаляем ненужные поля
    unset($fields['billing']['billing_company']);
    unset($fields['shipping']['shipping_company']);
    
    return $fields;
}

// В functions.php

add_action('wp_ajax_find_product_variation', 'my_find_product_variation');
add_action('wp_ajax_nopriv_find_product_variation', 'my_find_product_variation');

function my_find_product_variation() {
    // Проверяем nonce, если есть (необязательно, зависит от реализации)
    // if ( ! isset($_POST['nonce']) || ! wp_verify_nonce($_POST['nonce'], 'your_nonce_action') ) {
    //     wp_send_json_error('Неверный nonce');
    // }

    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $attributes = isset($_POST['attributes']) ? (array) $_POST['attributes'] : array();

    if (!$product_id || empty($attributes)) {
        wp_send_json(0); // Возвращаем 0 — вариация не найдена
    }

    $product = wc_get_product($product_id);

    if (!$product || $product->get_type() !== 'variable') {
        wp_send_json(0);
    }

    // Попытка найти вариацию по атрибутам
    $variation_id = 0;

    // Подготовим массив для поиска вариации — атрибуты без префикса "attribute_"
    $attributes_for_search = array();
    foreach ($attributes as $key => $value) {
        if (strpos($key, 'attribute_') === 0) {
            $attr_name = substr($key, 10);
            $attributes_for_search[$attr_name] = $value;
        }
    }

    // Перебираем все вариации
    foreach ($product->get_children() as $child_id) {
        $variation = wc_get_product($child_id);
        if (!$variation || $variation->get_type() !== 'variation') {
            continue;
        }

        $variation_attributes = $variation->get_attributes();

        // Проверяем совпадение всех атрибутов
        $matched = true;
        foreach ($attributes_for_search as $attr_name => $attr_value) {
            // Атрибуты вариации могут быть в разном регистре — приводим к нижнему
            if (!isset($variation_attributes[$attr_name]) || strtolower($variation_attributes[$attr_name]) !== strtolower($attr_value)) {
                $matched = false;
                break;
            }
        }

        if ($matched) {
            $variation_id = $variation->get_id();
            break;
        }
    }

    wp_send_json($variation_id);
}

add_action('wp_ajax_get_product_data', 'my_get_product_data');
add_action('wp_ajax_nopriv_get_product_data', 'my_get_product_data');

function my_get_product_data() {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

    if (!$product_id) {
        echo '<p>Товар не найден.</p>';
        wp_die();
    }

    $product = wc_get_product($product_id);

    if (!$product) {
        echo '<p>Товар не найден.</p>';
        wp_die();
    }

    // Формируем HTML для popup (пример)
    ?>
    <div class="popup-product">
        <h2><?php echo esc_html($product->get_name()); ?></h2>
        <div class="popup-product-image">
            <?php echo $product->get_image('medium'); ?>
        </div>
        <div class="popup-product-price">
            <?php echo $product->get_price_html(); ?>
        </div>
        <div class="popup-product-description">
            <?php echo wp_kses_post($product->get_short_description()); ?>
        </div>
        <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="button add-to-cart-button">
            Добавить в корзину
        </a>
    </div>
    <?php

    wp_die(); // Завершаем ajax обработку
}

// В functions.php или в вашем плагине

// AJAX: получить количество товаров в корзине
add_action('wp_ajax_get_cart_count', 'get_cart_count');
add_action('wp_ajax_nopriv_get_cart_count', 'get_cart_count');
function get_cart_count() {
    echo WC()->cart->get_cart_contents_count();
    wp_die();
}

// AJAX: удалить товар из корзины по ключу
add_action('wp_ajax_remove_from_cart', 'remove_from_cart');
add_action('wp_ajax_nopriv_remove_from_cart', 'remove_from_cart');
function remove_from_cart() {
    $cart_item_key = sanitize_text_field($_POST['cart_item_key'] ?? '');

    if (!$cart_item_key) {
        wp_send_json_error('Нет ключа товара');
        wp_die();
    }

    if (WC()->cart->remove_cart_item($cart_item_key)) {
        WC()->cart->calculate_totals();
        wp_send_json_success(WC()->cart->get_cart_contents_count());
    } else {
        wp_send_json_error('Не удалось удалить товар');
    }

    wp_die();
}

function my_enqueue_cart_script() {
    if (is_cart()) {
        wp_enqueue_script(
            'my-cart-js',
            get_template_directory_uri() . '/js/cart.js',
            ['jquery'],
            null,
            false
        );

        wp_localize_script('my-cart-js', 'wc_cart_params', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('woocommerce-cart')
        ]);
    }
}
add_action('wp_enqueue_scripts', 'my_enqueue_cart_script');

add_action('woocommerce_review_order_before_payment', 'custom_move_payment_methods');
function custom_move_payment_methods() {
    echo '<div class="checkout__payment">';
}

// Обработка AJAX-запроса для добавления товара упаковки
add_action('wp_ajax_add_additional_product_to_cart', 'add_additional_product_to_cart');
add_action('wp_ajax_nopriv_add_additional_product_to_cart', 'add_additional_product_to_cart');

function add_additional_product_to_cart() {
    if (!isset($_POST['product_id'])) {
        wp_send_json_error(array('message' => 'Не указан ID товара'));
    }

    $product_id = intval($_POST['product_id']);
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $variation_data = isset($_POST['variation_data']) ? $_POST['variation_data'] : array();
    $variation_id = 0;

    // Если товар вариативный, находим ID вариации
    if (!empty($variation_data)) {
        $product = wc_get_product($product_id);
        if ($product && $product->is_type('variable')) {
            $data_store = WC_Data_Store::load('product');
            $variation_id = $data_store->find_matching_product_variation($product, $variation_data);
        }
    }

    // Добавляем товар в корзину
    $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation_data);

    if ($cart_item_key) {
        // Возвращаем данные для обновления фрагментов корзины
        $data = array(
            'success' => true,
            'fragments' => apply_filters('woocommerce_add_to_cart_fragments', array()),
            'cart_hash' => apply_filters('woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5(json_encode(WC()->cart->get_cart_for_session())) : '', WC()->cart->get_cart_for_session())
        );
        wp_send_json($data);
    } else {
        $error_message = 'Не удалось добавить товар в корзину';
        if (wc_notice_count('error') > 0) {
            $error_message = wc_get_notices('error');
            wc_clear_notices();
        }
        wp_send_json_error(array('message' => $error_message));
    }
}