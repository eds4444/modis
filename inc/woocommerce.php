<?php 

// проверка на наличие woocommerce
if ( in_array( 'woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    
    //хук для истемы переписывания файлов. Support
    function modis_add_woocommerce_support() { 
        add_theme_support( 'woocommerce' );
    }
    add_action( 'after_setup_theme', 'modis_add_woocommerce_support' );



    //отключить хуки, хлебные крошки
    remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);


    //персонализируем хлебные крошки
    add_filter( 'woocommerce_breadcrumb_defaults', 'wcc_change_breadcrumb_delimiter' );
    function wcc_change_breadcrumb_delimiter( $defaults ) {
        // Change the breadcrumb delimeter from '/' to '>'
        $defaults['delimiter'] = ' &nbsp; ';
        $defaults['wrap_before'] = '<p class="breadcrumbs"><span>';
        $defaults['wrap_after']  = '</span></p>';

        return $defaults;
    }

    //отключаем хуки, вверхнюю сортировку товара
    //remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
    //remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);




}    
