<?php 

// проверка на наличие woocommerce
if ( in_array( 'woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    
    //хук для истемы переписывания файлов. Support
    function modis_add_woocommerce_support() { 
        add_theme_support( 'woocommerce' );
    }
    add_action( 'after_setup_theme', 'modis_add_woocommerce_support' );

}
