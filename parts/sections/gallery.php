    <section id="gallery" class="gallery">
        <div class="container">
            <div class="gallery__inner inner">
                <h2 class="title">ГАЛЕРЕЯ</h2>
                <div class="gallery__wrapper">
                    <picture class="gallery__wrapper-item">
                        <source media="(min-width: 1201px)" srcset="<?php echo esc_url(get_template_directory_uri() . '/images/gallery__item-1-1440.jpg'); ?> ">
                        <source media="(max-width: 1200px)" srcset="<?php echo esc_url(get_template_directory_uri() . '/images/gallery__item-1-768.png'); ?> ">
                        <source media="(max-width: 390px)" srcset="<?php echo esc_url(get_template_directory_uri() . '/images/gallery__item-1-390.png'); ?>">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/images/gallery__item-1-1440.jpg'); ?>" alt="">
                    </picture>
                    <picture class="gallery__wrapper-item">
                        <source media="(min-width: 1201px)" srcset="<?php echo esc_url(get_template_directory_uri() . '/images/gallery__item-2-1440.jpg'); ?> ">
                        <source media="(max-width: 1200px)" srcset="<?php echo esc_url(get_template_directory_uri() . '/images/gallery__item-2-768.png'); ?> ">
                        <source media="(max-width: 390px)" srcset="<?php echo esc_url(get_template_directory_uri() . '/images/gallery__item-2-390.png'); ?>">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/images/gallery__item-2-1440.jpg'); ?>" alt="">
                    </picture>
                    <picture class="gallery__wrapper-item">
                        <source media="(min-width: 1201px)" srcset="<?php echo esc_url(get_template_directory_uri() . '/images/gallery__item-3-1440.jpg'); ?> ">
                        <source media="(max-width: 1200px)" srcset="<?php echo esc_url(get_template_directory_uri() . '/images/gallery__item-3-768.png'); ?> ">
                        <source media="(max-width: 390px)" srcset="<?php echo esc_url(get_template_directory_uri() . '/images/gallery__item-3-390.png'); ?>">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/images/gallery__item-3-1440.jpg'); ?>" alt="">
                    </picture>
                    <picture class="gallery__wrapper-item">
                        <source media="(min-width: 1201px)" srcset="<?php echo esc_url(get_template_directory_uri() . '/images/gallery__item-4-1440.jpg'); ?> ">
                        <source media="(max-width: 1200px)" srcset="<?php echo esc_url(get_template_directory_uri() . '/images/gallery__item-4-768.png'); ?> ">
                        <source media="(max-width: 390px)" srcset="<?php echo esc_url(get_template_directory_uri() . '/images/gallery__item-4-390.png'); ?>">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/images/gallery__item-4-1440.jpg'); ?>" alt="">
                    </picture>
                    <picture class="gallery__wrapper-item">
                        <source media="(min-width: 1201px)" srcset="<?php echo esc_url(get_template_directory_uri() . '/images/gallery__item-5-1440.png'); ?> ">
                        <source media="(max-width: 1200px)" srcset="<?php echo esc_url(get_template_directory_uri() . '/images/gallery__item-5-768.png'); ?> ">
                        <source media="(max-width: 390px)" srcset="<?php echo esc_url(get_template_directory_uri() . '/images/gallery__item-5-390.png'); ?>">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/images/gallery__item-5-1440.png'); ?>" alt="">
                    </picture>
                    <picture class="gallery__wrapper-item">
                        <source media="(min-width: 1201px)" srcset="<?php echo esc_url(get_template_directory_uri() . '/images/gallery__item-6-1440.jpg'); ?> ">
                        <source media="(max-width: 1200px)" srcset="<?php echo esc_url(get_template_directory_uri() . '/images/gallery__item-6-768.png'); ?> ">
                        <source media="(max-width: 390px)" srcset="<?php echo esc_url(get_template_directory_uri() . '/images/gallery__item-6-390.png'); ?>">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/images/gallery__item-6-1440.jpg'); ?>" alt="">
                    </picture>
                </div>
                <div class="about__arrows">
                    <button class="about__arrow-prev"><svg id="prev"><use xlink:href="<?php echo get_template_directory_uri(); ?>/images/sprite.svg#prev__arrow-about"></use></svg></button>
                    <button class="about__arrow-next"><svg id="next"><use xlink:href="<?php echo get_template_directory_uri(); ?>/images/sprite.svg#next__arrow-about"></use></svg></button>
                </div>
            </div>
        </div>
    </section>