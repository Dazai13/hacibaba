<section id="sweets" class="sweets">
    <div class="container">
        <div class="sweets__inner inner">
            <h2 class="title">НАШИ СЛАДОСТИ</h2>
            <div class="sweets__wrapper">
                <!-- Рахат-лукум -->
                <a href="<?php echo esc_url(get_term_link('рахат-лукум', 'product_cat')); ?>" class="sweet__wrapper-item">
                    <img class="sweets__item-image" src="<?php echo get_template_directory_uri(); ?>/images/sweets__item-1.jpg" alt="Рахат-лукум">
                    <h3 class="sweets__item-text">Рахат-лукум</h3>
                </a>
                
                <!-- Пишмание -->
                <a href="<?php echo esc_url(get_term_link('пишмание', 'product_cat')); ?>" class="sweet__wrapper-item">
                    <img class="sweets__item-image" src="<?php echo get_template_directory_uri(); ?>/images/sweets__item-2.jpg" alt="Пишмание">
                    <h3 class="sweets__item-text">Пишмание</h3>
                </a>
                
                <!-- Пахлава -->
                <a href="<?php echo esc_url(get_term_link('пахлава', 'product_cat')); ?>" class="sweet__wrapper-item">
                    <img class="sweets__item-image" src="<?php echo get_template_directory_uri(); ?>/images/sweets__item-3.jpg" alt="Пахлава">
                    <h3 class="sweets__item-text">Пахлава</h3>
                </a>
                
                <!-- Кофе -->
                <a href="<?php echo esc_url(get_term_link('кофе', 'product_cat')); ?>" class="sweet__wrapper-item">
                    <img class="sweets__item-image" src="<?php echo get_template_directory_uri(); ?>/images/sweets__item-4.jpg" alt="Кофе">
                    <h3 class="sweets__item-text">Кофе</h3>
                </a>
            </div>
        </div>
    </div>
</section>