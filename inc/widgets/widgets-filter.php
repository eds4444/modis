<?php

class Modis_Filter_Widget extends WP_Widget 
{

    /**
     * General Setup
     */
    public function __construct(){

        /*Widget settings. */
        $widget_ops = array(
            'classname' => 'modis_filter_widget',
            'description' => 'Виджет который выводит блок Ajax Фильтрация'
        );

        /*Widget control settings. */
        $control_ops = array(
            'width' => 500,
            'height' => 450,
            'id_base' => 'modis_filter_widget'
        );
        /* Create the widget. */
        parent::__construct('modis_filter_widget', 'Ajax Фильтрация', $widget_ops, $control_ops );
    }

    /**
     * Display Widget
     * @param array $args
     * @param array @instance
     */
    public function widget( $args, $instance)
    {
        extract( $args );

        $title1 = $instance['title1'];
        $title2 = $instance['title2'];

        $prices = $this->get_filter_price();
        $min    = floor( $prices->min_price );
        $max    = ceil( $prices->max_price );

        global $woocommerce;

        //Display widget
        ?>
        <div class="sortby modis_sortby" data-minprice="<?php echo $min;?>" data-maxprice="<?php echo $max;?>">
            <h5 class="sortby_title"><?php  echo $title1; ?></h5>
            <div id="sider-range"></div>
            <p class="sortby_price">
                <label for="amount">Цена:</label>
                <span class="field">
                    <?php echo get_woocommerce_currency_symbol();?><input type="text" id="priceMin" class="min_price"> -
                    <?php echo get_woocommerce_currency_symbol();?><input type="text" id="priceMax" class="max_price"> 
                </span>
            </p>
        </div>
        <div class="categories side-nav log">
           <h5 class="categories__title"><?php echo $title2?></h5>
           <div id="st-accordion" class="st-accordion">
               <ul>
                   <?php

                     $categories = get_terms(
                         'product_cat',
                         array(
                             //'orderby' => 'name',
                             'hierarchical' => true,
                             'hide_empty' => 1,
                             'parent' => 0
                         )
                    );

                    //print_r($categories);

                    foreach ($categories as $cat) { ?>
                        <li class="modis_filter_check">
                            <?php $temp_cat = get_terms(
                                'product_cat',
                                array(
                                    'orderby' => 'name',
                                    'hierarchical' => true,
                                    'hide_empty' => 1,
                                    'parent' => $cat->term_id
                                )
                            );

                            $class='';
                            if($temp_cat){$class='has_child';} else {$class='no_child';} ?>
                            <input id="term_<?php echo $temp_cat->term_id; ?>" type="checkbox" name="category" value="<?php echo $temp_cat->term_id; ?>">
                            <a href="#" class="<?php echo $class;?>"><?php echo $cat->name; ?></a>

                            <?php 

                            if ($temp_cat) {
                                echo '<div class="st-content cat-list">'; 
                        
                                foreach ($temp_cat as $temp) { ?>
                                   <div class="log__group chek">
                                       <input id="term_<?php echo $temp->term_id; ?>" type="checkbox" name="category" value="<?php echo $temp->term_id; ?>">
                                       <label for="term_<?php echo $temp->term_id; ?>"><?php echo $temp->term_name; ?></label>
                                   </div>
                            <?php }
                                   echo '</div>';                                    
                             
                            }

                            ?>

                        </li>
                     <?php }
                   ?>
               </ul>
           </div>
        </div>
        <?php
    }    

    protected function get_filtered_price() {
        global $wpdb;

        $args       = wc()->query->get_main_query()->query_vars;
        $tax_query  = isset($args['tax_query']) ? $args['tax_query'] : array();
        $meta_query = isset($args['meta_query']) ? $args['meta_query'] : array();

        if ( ! is_post_type_archive( 'product' ) && ! empty( $args['taxonomy']) && ! empty( $args['term'])){
            $tax_query[] = array(
                'taxonomy' => $args['taxonomy'],
                'terms'    => array( $args['term']),
                'field'    => 'slug',
            );
        }

        foreach ($meta_query + $tax_query as $key => $query) {
            if ( ! empty( $query['price_filter'] ) || ! empty( $query['rating_filter'] ) ) {
                unset( $meta_query[ $key ] );
            }
        }

        $meta_query = new WP_Meta_Query( $meta_query );
        $tax_query  = new WP_Tax_Query( $tax_query );

        $meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
        $tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

    
    }   

}