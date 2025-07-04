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
        'smooth-scroll',
        get_template_directory_uri() . '/js/smooth-scroll.js',
        array('jquery', 'slick-js'),
        filemtime(get_template_directory() . '/js/smooth-scroll.js'),
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
        'blog',
        get_template_directory_uri() . '/js/blog.js',
        array('jquery', 'slick-js'),
        filemtime(get_template_directory() . '/js/blog.js'),
        false
    );
}
add_action('wp_enqueue_scripts', 'my_theme_scripts');
function mytheme_add_woocommerce_support() {
    add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'mytheme_add_woocommerce_support');