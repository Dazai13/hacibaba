<section id="catalog" class="catalog">
    <div class="container">
        <div class="catalog__inner inner">
            <h2 class="title">Вы готовы к сладкому приключению?</h2>
            <div class="catalog__wrapper">
                <?php 
                $args = array(
                    'post_type'      => 'product',
                    'posts_per_page' => 8,
                    'tax_query'      => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field'    => 'slug',
                            'terms'    => 'рахат-лукум',
                        )
                    )
                );
                
                $loop = new WP_Query($args);
                
                if ($loop->have_posts()) {
                    while ($loop->have_posts()) : $loop->the_post();
                        global $product;
                        
                        $product_data = array(
                            'id'        => $product->get_id(),
                            'image'     => wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'full')[0],
                            'title'     => $product->get_name(),
                            'subtitle'  => $product->get_short_description(),
                            'price'     => $product->get_price_html(),
                            'add_to_cart' => $product->add_to_cart_url()
                        );
                        
                        set_query_var('product', $product_data);
                        get_template_part('parts/components/card');
                        
                    endwhile;
                } else {
                    echo __('Товары не найдены');
                }
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </div>
</section>

<script>
jQuery(document).ready(function($) {
    $('.card').on('click', function() {
        var productId = $(this).data('product-id');
        
        // AJAX запрос для загрузки данных товара
        $.ajax({
            url: '<?php echo admin_url("admin-ajax.php"); ?>',
            type: 'POST',
            data: {
                action: 'load_product_popup',
                product_id: productId
            },
            success: function(response) {
                $('#custom-popup .popup-content').html(response);
                $('#custom-popup').fadeIn();
            }
        });
    });
    
    // Закрытие попапа
    $('#close-popup').on('click', function() {
        $('#custom-popup').fadeOut();
    });
});
</script>