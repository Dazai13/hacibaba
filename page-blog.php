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
</main>
<?php get_footer();?>