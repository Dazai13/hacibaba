<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_title(); ?>
    <?php wp_head(); ?>    
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header__inner">
                <div class="header__inner-logo">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="">
                    <svg id="burger"><use xlink:href="<?php echo get_template_directory_uri(); ?>/images/sprite.svg#burger"></use></svg>
                    <div class="mobile-menu">
                        
                    </div>
                </div>
                <nav class="header__inner-menu">
                        <a href="/" class="header__inner-link menu-header">Главная</a>
                        <a href="#about" class="header__inner-link menu-header">О нас</a>
                        <a href="#blog" class="header__inner-link menu-header">Блог</a>
                        <a href="#sweets" class="header__inner-link menu-header">Рахат-лукум</a>
                        <a href="#sweets" class="header__inner-link menu-header">Кофе</a>
                        <a href="#sweets" class="header__inner-link menu-header">Пахлава</a>
                        <a href="#sweets" class="header__inner-link menu-header">Пишмание</a>
                        <a href="#gallery" class="header__inner-link menu-header">Галерея</a>
                        <a href="#contact" class="header__inner-link menu-header">Контакты</a> 
                </nav>
                <div class="header__inner-icons">
                    <svg id="search"><use xlink:href="<?php echo get_template_directory_uri(); ?>/images/sprite.svg#search"></use></svg>
                    <div class="header__icon-shop">
                        <div class="icon__shop-item">0</div>
                        <svg id="shop"><use xlink:href="<?php echo get_template_directory_uri(); ?>/images/sprite.svg#shop"></use></svg>
                    </div>
                </div>
            </div>
            <?php
            include(locate_template('parts/sections/search.php'));
            ?>
        </div>
    </header>
