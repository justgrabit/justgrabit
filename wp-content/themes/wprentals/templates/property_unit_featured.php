<?php
global $wpestate_curent_fav;
global $wpestate_currency;
global $wpestate_where_currency;
global $show_compare;
global $wpestate_show_compare_only;
global $show_remove_fav;
global $wpestate_options;
global $isdashabord;
global $align;
global $align_class;
global $is_shortcode;
global $wpestate_row_number_col;
global $type;
$pinterest          =   '';
$previe             =   '';
$compare            =   '';
$extra              =   '';
$property_size      =   '';
$property_bathrooms =   '';
$property_rooms     =   '';
$measure_sys        =   '';
$col_class          =   'col-md-6';
$col_org            =   4;
$booking_type       =   wprentals_return_booking_type($post->ID);
$rental_type        =   wprentals_get_option('wp_estate_item_rental_type');
if(isset($is_shortcode) && $is_shortcode==1 ){
    $col_class='col-md-'.esc_attr($wpestate_row_number_col).' shortcode-col';
}

$link           =   esc_url(get_permalink());
$preview        =   array();
$preview[0]     =   '';
?>  

<div class="listing_wrapper" data-org="12" data-listid="<?php print intval($post->ID);?>" > 
    <div class="property_listing" data-link="<?php print esc_attr($link);?>">
        <?php
        if ( has_post_thumbnail() ):
           
            $preview   = wp_get_attachment_image_src(get_post_thumbnail_id(), 'wpestate_property_listings');          
            $extra= array(
                'data-original' =>  $preview[0],
                'class'         =>  'lazyload img-responsive',    
            );

            $thumb_prop         =   get_the_post_thumbnail($post->ID, 'wpestate_property_listings',$extra);
            $thumb_id           =   get_post_thumbnail_id($post->ID);
            $thumb_prop_url     =   wp_get_attachment_image_src($thumb_id,'wpestate_property_featured');
            $featured           =   intval  ( get_post_meta($post->ID, 'prop_featured', true) );               
            $property_city      =   get_the_term_list($post->ID, 'property_city', '', ', ', '') ;
            $property_area      =   get_the_term_list($post->ID, 'property_area', '', ', ', '');
          
          
        
            
           
           
            print   '<div class="listing-hover-gradient"></div><div class="listing-hover" ></div>';           
            print   '<div class="listing-unit-img-wrapper" style="background-image:url('.esc_url($thumb_prop_url[0]).')"></div>';
            if($featured==1){
                print '<div class="featured_div">'.esc_html__( 'featured','wprentals').'</div>';
            }
                
            echo wpestate_return_property_status($post->ID);
            
            print   '<div class="category_name">';
            
            print'<div class="price_unit">';
            wpestate_show_price($post->ID,$wpestate_currency,$wpestate_where_currency,0);
            print '<span class="pernight"> '.wpestate_show_labels('per_night2',$rental_type,$booking_type).'</span></div> ';
            
            if(wpestate_has_some_review($post->ID)!==0){
                print wpestate_display_property_rating( $post->ID ); 
            }
                
            print   '<a class="featured_listing_title" href="'.esc_url($link).'">';
                
                $title=get_the_title();
                echo mb_substr( html_entity_decode($title), 0, 40); 
                if(strlen($title)>40){
                    echo '...';   
                }
                
                print   '</a><div class="category_tagline">';
                if ($property_area != '') {
                    print trim($property_area).', ';
                }       
                print trim($property_city).'</div>';
            print '</div>';          
        endif;        
        ?>
    </div>          
</div>