<?php
function my_theme_scripts() {
    // Подключение CSS
    wp_enqueue_style('slick-style', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css');
    wp_enqueue_style('slick-theme-style', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css');
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

// Обеспечиваем корректную обработку вариативных товаров
add_filter('woocommerce_add_to_cart_validation', 'validate_variation_before_add', 10, 6);
function validate_variation_before_add($passed, $product_id, $quantity, $variation_id = '', $variations = '', $cart_item_data = []) {
    // Если товар вариативный, проверяем что вариация указана
    $product = wc_get_product($product_id);
    if ($product && $product->is_type('variable') && empty($variation_id)) {
        wc_add_notice(__('Пожалуйста, выберите все необходимые опции товара.', 'woocommerce'), 'error');
        return false;
    }
    return $passed;
}

// Улучшаем обработку AJAX-запросов
add_action('wp_ajax_woocommerce_add_to_cart', 'woocommerce_ajax_add_to_cart');
add_action('wp_ajax_nopriv_woocommerce_add_to_cart', 'woocommerce_ajax_add_to_cart');
function woocommerce_ajax_add_to_cart() {
    ob_start();
    
    // Проверяем nonce (защита от CSRF)
    if (!isset($_POST['product_id'])) {
        wp_send_json_error(['message' => 'Не указан ID товара']);
    }

    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount(absint($_POST['quantity']));
    $variation_id = 0;
    
    // Если товар вариативный, находим нужную вариацию
    $product = wc_get_product($product_id);
    if ($product->is_type('variable')) {
        $variation_attributes = [];
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'attribute_') === 0) {
                $variation_attributes[sanitize_title(str_replace('attribute_', '', $key))] = sanitize_text_field($value);
            }
        }
        
        $data_store = WC_Data_Store::load('product');
        $variation_id = $data_store->find_matching_product_variation($product, $variation_attributes);
        
        if (!$variation_id) {
            wp_send_json_error(['message' => 'Не удалось найти подходящую вариацию']);
        }
    }
    
    // Добавляем в корзину
    $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation_attributes);
    
    if ($cart_item_key) {
        do_action('woocommerce_ajax_added_to_cart', $product_id);
        
        // Получаем обновленные фрагменты
        WC_AJAX::get_refreshed_fragments();
    } else {
        $error_message = wc_get_notices('error');
        wc_clear_notices();
        
        if (empty($error_message)) {
            $error_message = __('Неизвестная ошибка при добавлении в корзину', 'woocommerce');
        }
        
        wp_send_json_error(['message' => is_array($error_message) ? implode(' ', $error_message) : $error_message]);
    }
}