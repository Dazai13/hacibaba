<?php get_header(); ?>
<?php include(locate_template('parts/sections/intro.php'));?>
<main class="main">
    <?php 
    include(locate_template('parts/sections/catalog.php'));
    echo '<div class="line"></div>';
    include(locate_template('parts/sections/sweets.php'));
    echo '<div class="line"></div>';
    include(locate_template('parts/sections/about.php'));
    echo '<div class="line"></div>';
    include(locate_template('parts/sections/blog.php'));
    echo '<div class="line"></div>';
    include(locate_template('parts/sections/gallery.php'));
    ?>
</main>
<?php get_footer(); ?>
