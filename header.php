<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>    
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header__inner">
                <div class="header__inner-logo">
                    <a href='/'><img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt=""></a>
                    <svg id="burger"><use xlink:href="<?php echo get_template_directory_uri(); ?>/images/sprite.svg#burger"></use></svg>
                    <div class="mobile-menu">
                        <?php
                        $is_home = is_front_page() || is_home();
                        $base_url = $is_home ? '' : home_url('/');
                        ?>
                        <a href="<?php echo $base_url; ?>#about" class="header__inner-link menu-header">О нас</a>
                        <a href="/blog" class="header__inner-link menu-header">Блог</a>
                        <a href="<?php echo esc_url(get_term_link('рахат-лукум', 'product_cat')); ?>" class="header__inner-link menu-header">Рахат-лукум</a>
                        <a href="<?php echo esc_url(get_term_link('кофе', 'product_cat')); ?>" class="header__inner-link menu-header">Кофе</a>
                        <a href="<?php echo esc_url(get_term_link('пахлава', 'product_cat')); ?>" class="header__inner-link menu-header">Пахлава</a>
                        <a href="<?php echo esc_url(get_term_link('пишмание', 'product_cat')); ?>" class="header__inner-link menu-header">Пишмание</a>
                        <a href="/gallery" class="header__inner-link menu-header">Галерея</a>
                        <a href="<?php echo $base_url; ?>#contact" class="header__inner-link menu-header">Контакты</a> 
                    </div>
                </div>
                <nav class="header__inner-menu">
                    <a href="/" class="header__inner-link menu-header">Главная</a>
                        <?php
                        $is_home = is_front_page() || is_home();
                        $base_url = $is_home ? '' : home_url('/');
                        ?>
                    <a href="<?php echo $base_url; ?>#about" class="header__inner-link menu-header">О нас</a>
                    <a href="/blog" class="header__inner-link menu-header">Блог</a>
                    <a href="<?php echo esc_url(get_term_link('рахат-лукум', 'product_cat')); ?>" class="header__inner-link menu-header">Рахат-лукум</a>
                    <a href="<?php echo esc_url(get_term_link('кофе', 'product_cat')); ?>" class="header__inner-link menu-header">Кофе</a>
                    <a href="<?php echo esc_url(get_term_link('пахлава', 'product_cat')); ?>" class="header__inner-link menu-header">Пахлава</a>
                    <a href="<?php echo esc_url(get_term_link('пишмание', 'product_cat')); ?>" class="header__inner-link menu-header">Пишмание</a>
                    <a href="/gallery" class="header__inner-link menu-header">Галерея</a>
                    <a href="<?php echo $base_url; ?>#contact" class="header__inner-link menu-header">Контакты</a> 
                </nav>
                <div class="header__inner-icons">
                    <svg id="search"><use xlink:href="<?php echo get_template_directory_uri(); ?>/images/sprite.svg#search"></use></svg>
                    <a href="<?php echo wc_get_cart_url(); ?>" class="header__icon-shop">
                        <div class="icon__shop-item"> 
                            <?php echo WC()->cart->get_cart_contents_count(); ?>
                        </div>
                        <svg id="shop"><use xlink:href="<?php echo get_template_directory_uri(); ?>/images/sprite.svg#shop"></use></svg>
                    </a>
                </div>
            </div>
            <?php
            include(locate_template('parts/sections/search.php'));
            ?>
        </div>
    </header>
