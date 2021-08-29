<?php
/*
Template name: Cart Template
*/

get_header();
?>
<div class="hero-wrap hero-bread" style="background-image: url('<?php echo get_template_directory_uri(). '/assets/images/bg_6.jpg';?>');">
<div class="container">
    <div class="row no-gutters slider-text align-items-center justify-content-center">
        <div class="col-md-9 ftco-animate text-center">
            <h1 class="mb-0 bread"><?php the_title(); ?></h1>
        <?php if ( in_array( 'woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
                    woocommerce_breadcrumb(); } ?>
        </div>
    </div>
    </div>
</div>

            <?php
            while ( have_posts() ) :
                the_post();
                the_content();
            endwhile;?>                

<?php
get_footer();
