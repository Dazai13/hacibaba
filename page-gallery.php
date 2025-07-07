<?php get_header(); ?>
<main class="main">
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
    </div>
    <section class="gallery">
        <div class="container">
            <div class="gallery__inner inner">
                <h3 style="text-transform: uppercase;font-family: var(--main-font);">Галерея</h3>
                <div class="gallery__items">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/gallery__item-1.png" alt="" class="gallery__item">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/gallery__item-2.png" alt="" class="gallery__item">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/gallery__item-3.png" alt="" class="gallery__item">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/gallery__item-4.png" alt="" class="gallery__item">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/gallery__item-5.png" alt="" class="gallery__item">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/gallery__item-6.png" alt="" class="gallery__item">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/gallery__item-7.png" alt="" class="gallery__item">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/gallery__item-8.png" alt="" class="gallery__item">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/gallery__item-9.png" alt="" class="gallery__item">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/gallery__item-10.png" alt="" class="gallery__item">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/gallery__item-11.png" alt="" class="gallery__item">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/gallery__item-12.png" alt="" class="gallery__item">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/gallery__item-13.png" alt="" class="gallery__item">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/gallery__item-14.png" alt="" class="gallery__item">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/gallery__item-15.png" alt="" class="gallery__item">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/gallery__item-16.png" alt="" class="gallery__item">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/gallery__item-17.png" alt="" class="gallery__item">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/gallery__item-18.png" alt="" class="gallery__item">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/gallery__item-19.png" alt="" class="gallery__item">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/gallery__item-20.png" alt="" class="gallery__item">
                </div>
            </div>
        </div>
    </section>
</main>
<?php get_footer();?>