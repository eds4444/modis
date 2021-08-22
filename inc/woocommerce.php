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


        //sales
        add_filter( 'woocommerce_sale_flash', 'add_percentage_to_sale_badge', 20, 3 );
        function add_percentage_to_sale_badge( $html, $post, $product ) {

        if( $product->is_type('variable')){
            $percentages = array();

            // Get all variation prices
            $prices = $product->get_variation_prices();

            // Loop through variation prices
            foreach( $prices['price'] as $key => $price ){
                // Only on sale variations
                if( $prices['regular_price'][$key] !== $price ){
                    // Calculate and set in the array the percentage for each variation on sale
                    $percentages[] = round( 100 - ( floatval($prices['sale_price'][$key]) / floatval($prices['regular_price'][$key]) * 100 ) );
                }
            }
            // We keep the highest value
            $percentage = max($percentages) . '%';

        } elseif( $product->is_type('grouped') ){
            $percentages = array();

            // Get all variation prices
            $children_ids = $product->get_children();

            // Loop through variation prices
            foreach( $children_ids as $child_id ){
                $child_product = wc_get_product($child_id);

                $regular_price = (float) $child_product->get_regular_price();
                $sale_price    = (float) $child_product->get_sale_price();

                if ( $sale_price != 0 || ! empty($sale_price) ) {
                    // Calculate and set in the array the percentage for each child on sale
                    $percentages[] = round(100 - ($sale_price / $regular_price * 100));
                }
            }
            // We keep the highest value
            $percentage = max($percentages) . '%';

        } else {
            $regular_price = (float) $product->get_regular_price();
            $sale_price    = (float) $product->get_sale_price();

            if ( $sale_price != 0 || ! empty($sale_price) ) {
                $percentage    = round(100 - ($sale_price / $regular_price * 100)) . '%';
            } else {
                return $html;
            }
        }
        return '<span class="status">' . $percentage . '</span>';
        }

    //отключаем хуки, вверхнюю сортировку товара
    //remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
    //remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

    //отключаем сайдбар
    remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

    //product content отключаем ссылки на картинках
    remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);

    //image  добавяем хук на ссылку картинки
    add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 5);
    add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 15);

    //product data  название продукта
    remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);

    function my_custome_title(){
        echo '<h3><a href="'.get_the_permalink(). '">'.get_the_title().'</a></h3>';
    }
    add_action( 'woocommerce_shop_loop_item_title', 'my_custome_title', 15);

    //single image
    add_action( 'after_setup_theme', 'ale_woocommerse_plugin_setup');

    function ale_woocommerse_plugin_setup(){
        //add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        //add_theme_support( 'wc-product-gallery-slider' );

    }
    
    //single product right part
    function modis_single_title(){
        echo '<h3>' .get_the_title(). '</h3>';
    }
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);

    add_action( 'woocommerce_single_product_summary', 'modis_single_title', 5);
    
}    
