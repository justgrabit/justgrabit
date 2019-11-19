<?php
global $post;
global $current_user;
global $feature_list_array;
global $wpestate_propid;
global $post_attachments;
global $wpestate_options;
global $wpestate_where_currency;
global $wpestate_property_description_text;     
global $wpestate_property_details_text;  
global $wpestate_property_details_text;
global $wpestate_property_adr_text;  
global $wpestate_property_price_text;   
global $wpestate_property_pictures_text;    
global $wpestate_propid;
global $wpestate_gmap_lat;  
global $wpestate_gmap_long;
global $wpestate_unit;
global $wpestate_currency;
global $wpestate_use_floor_plans;
global $favorite_text;
global $favorite_class;
global $property_action_terms_icon;
global $property_action;
global $property_category_terms_icon;
global $property_category;
global $guests;
global $bedrooms;
global $bathrooms;
global $show_sim_two;
global $guest_list;
global $post_id;

$price              =   intval   ( get_post_meta($post->ID, 'property_price', true) );
$price_label        =   esc_html ( get_post_meta($post->ID, 'property_label', true) );  
$property_city      =   get_the_term_list($post->ID, 'property_city', '', ', ', '') ;
$property_area      =   get_the_term_list($post->ID, 'property_area', '', ', ', '');
$post_id            =   $post->ID; 
$guest_no_prop      =   '';
if(isset($_GET['guest_no_prop'])){
    $guest_no_prop = intval($_GET['guest_no_prop']);
}
$guest_list= wpestate_get_guest_dropdown('noany');
?>


<div   itemscope itemtype="http://schema.org/RentAction" class="row content-fixed-listing listing_type_1">
    <div class=" <?php 
    if ( $wpestate_options['content_class']=='col-md-12' || $wpestate_options['content_class']=='none'){
        print 'col-md-8';
    }else{
       print esc_attr( $wpestate_options['content_class']); 
    }?> ">
    
        <?php include(locate_template('templates/ajax_container.php'));?>
        <?php
        while (have_posts()) : the_post();
            $image_id       =   get_post_thumbnail_id();
            $image_url      =   wp_get_attachment_image_src($image_id, 'wpestate_property_full_map');
            $full_img       =   wp_get_attachment_image_src($image_id, 'full');
            $image_url      =   $image_url[0];
            $full_img       =   $full_img [0];     
        ?>
        
     
    <div class="single-content listing-content">      
        <div class="booking_form_mobile">
            <?php
            if ( wp_is_mobile() ) {
                include(locate_template ('templates/booking_form_template.php'));
            }
            ?>
        </div>
        
        <h1 itemprop="name" class="entry-title entry-prop"><?php the_title(); ?>  </h1>     
        
        <div class="property_ratings">
            <?php 
            if(wpestate_has_some_review($post->ID)!==0){
                $args = array(
                    'post_id' => $post->ID, // use post_id, not post_ID
                );
                $comments   =   get_comments($args);
                $coments_no =   0;
                $stars_total=   0;


                foreach($comments as $comment) :
                    $coments_no++;

                endforeach;
                
                if($coments_no>0){
                    print wpestate_display_property_rating( $post->ID ); 
                    print '<div class="rating_no">('.esc_html($coments_no).')</div>';
                }
            } 
            ?>         
        </div> 
       
        
        <div class="listing_main_image_location" itemprop="location" itemscope itemtype="http://schema.org/Place">
            <?php print  wp_kses_post($property_city.', '.$property_area); ?>     
            <div  class="schema_div_noshow" itemprop="name"><?php echo strip_tags (  $property_city.', '.$property_area); ?></div>
        </div>   

        <div class="panel-wrapper imagebody_wrapper">
            <div class="panel-body imagebody imagebody_new">
                <?php  
                include(locate_template('templates/property_pictures3.php'));
                ?>
            </div> 
        </div>
        
        <div class="category_wrapper ">
            <div class="category_details_wrapper">
                <?php 

                    if(wprentals_get_option( 'wp_estate_use_custom_icon_area') =='yes' ){ 
                        wprentals_icon_bar_design();
                    }else{
                        $rental_type    =   wprentals_get_option('wp_estate_item_rental_type');
                        wprentals_icon_bar_classic($property_action,$property_category,$rental_type,$guests,$bedrooms,$bathrooms);
                    } 
                ?>
                        
            </div>
            <a href="#listing_calendar" class="check_avalability"><?php esc_html_e('Check Availability','wprentals');?></a>
        </div>
      
        <div id="listing_description">
        <?php
            $content = get_the_content();
            $content = apply_filters('the_content', $content);
            $content = str_replace(']]>', ']]&gt;', $content);
            if($content!=''){   
                   
                $wpestate_property_description_text =  wprentals_get_option('wp_estate_property_description_text');
                if (function_exists('icl_translate') ){
                    $wpestate_property_description_text     =   icl_translate('wprentals','wp_estate_property_description_text', esc_html( wprentals_get_option('wp_estate_property_description_text') ) );
                }
                print '<h4 class="panel-title-description">'.esc_html($wpestate_property_description_text).'</h4>';
                print '<div class="panel-body" id="listing_description_content"  itemprop="description">'.$content.'</div>'; //escpaed above      
            }
        ?>
             
        </div>
        <div id="view_more_desc"><?php esc_html_e('View more','wprentals');?></div>
     
  
        <!-- property price   -->   
        <div class="panel-wrapper" id="listing_price">
            <a class="panel-title" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseOne"> <span class="panel-title-arrow"></span>
                <?php if($wpestate_property_price_text!=''){
                    print esc_html($wpestate_property_price_text);
                } else{
                    esc_html_e('Property Price','wprentals');
                }  ?>
            </a>
            <div id="collapseOne" class="panel-collapse collapse in">
                <div class="panel-body panel-body-border" itemprop="priceSpecification" >
                    <?php print estate_listing_price($post->ID); ?>
                    <?php  wpestate_show_custom_details($post->ID); ?>
                    <?php  wpestate_show_custom_details_mobile($post->ID); ?>
                </div>
            </div>
        </div>
        

        <div class="panel-wrapper">
            <!-- property address   -->             
            <a class="panel-title" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseTwo">  <span class="panel-title-arrow"></span>
                <?php 
                if($wpestate_property_adr_text!=''){
                    print esc_html($wpestate_property_adr_text);
                } else{
                    esc_html_e('Property Address','wprentals');
                }
                ?>
            </a>   
   
            <div id="collapseTwo" class="panel-collapse collapse in">
                <div class="panel-body panel-body-border">
                    <?php print estate_listing_address($post->ID); ?>
                </div>
                
            </div>
        </div>
             
        
        <!-- property details   -->  
        <div class="panel-wrapper">
            <?php                                       
            if($wpestate_property_details_text=='') {
                print'<a class="panel-title" id="listing_details" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseTree"><span class="panel-title-arrow"></span>'.esc_html__( 'Property Details', 'wprentals').'  </a>';
            }else{
                print'<a class="panel-title"  id="listing_details" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseTree"><span class="panel-title-arrow"></span>'.esc_html($wpestate_property_details_text).'</a>';
            }
            ?>
            <div id="collapseTree" class="panel-collapse collapse in">
                <div class="panel-body panel-body-border">
                    <?php print estate_listing_details($post->ID);?>
                </div>
            </div>
        </div>

        

        <!-- Features and Amenities -->
        <div class="panel-wrapper features_wrapper">
            <?php 
            
             $terms = get_terms( array(
                'taxonomy' => 'property_features',
                'hide_empty' => false,
            ));
            if ( count( $terms )!=0 && !count( $terms )!=1 ){

                if($wpestate_property_features_text ==''){
                    print '<a class="panel-title" id="listing_ammenities" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseFour"><span class="panel-title-arrow"></span>'.esc_html__( 'Amenities and Features', 'wprentals').'</a>';
                }else{
                    print '<a class="panel-title" id="listing_ammenities" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseFour"><span class="panel-title-arrow"></span>'. $wpestate_property_features_text.'</a>';
                }
                ?>
                <div id="collapseFour" class="panel-collapse collapse in">
                    <div class="panel-body panel-body-border">
                        <?php print estate_listing_features($post->ID); ?>
                    </div>
                </div>
            <?php
            } // end if are features and ammenties
            ?>
        </div>
        
        <?php  
        $yelp_client_id         =   trim(wprentals_get_option('wp_estate_yelp_client_id',''));
        $yelp_client_secret     =   trim(wprentals_get_option('wp_estate_yelp_client_secret',''));

        if($yelp_client_secret!=='' && $yelp_client_id!==''  ){ ?>
            <!-- Yelp -->
            <div class="panel-wrapper yelp_wrapper">
                
                <a class="panel-title" id="yelp_details" data-toggle="collapse" data-parent="#yelp_details" href="#collapseFive"><span class="panel-title-arrow"></span> <?php esc_html_e( 'What\'s Nearby', 'wprentals');?>  </a>
                <div id="collapseFive" class="panel-collapse collapse in">
                    <div class="panel-body panel-body-border">
                        <?php print wpestate_yelp_details($post->ID); ?>
                    </div>
                </div>

            </div>
        <?php } ?>    

        <div class="property_page_container ">
            <?php
            include(locate_template ('/templates/show_avalability.php') );
            wp_reset_query();
            ?>  
        </div>    
         
        <?php
        endwhile; // end of the loop
        $show_compare=1;
        ?>

        <?php     include(locate_template ('/templates/listing_reviews.php')); ?>

        </div><!-- end single content -->
        
        <div class="property_page_container google_map_type1"> 
            <h3 class="panel-title" id="on_the_map"><?php esc_html_e('On the Map','wprentals');?></h3>
            <div class="google_map_on_list_wrapper">  
              
                    <div id="gmapzoomplus"></div>
                    <div id="gmapzoomminus"></div>
                    
                    <?php 
                    if( wprentals_get_option('wp_estate_kind_of_map')==1){ ?>
                    
                        <div id="gmapstreet"></div>
                        <?php echo wpestate_show_poi_onmap();
                    }
                    ?>
                
                <div id="google_map_on_list" 
                    data-cur_lat="<?php print esc_attr($wpestate_gmap_lat);?>" 
                    data-cur_long="<?php print esc_attr($wpestate_gmap_long );?>" 
                    data-post_id="<?php print intval($post->ID); ?>">
                </div>
            </div>    
        </div> 
 
            
        <?php   
        $show_sim_two=1;
        include(locate_template ('/templates/similar_listings.php'));
        ?> 
    </div><!-- end 8col container-->

    <div class="clearfix visible-xs"></div>
    <div class=" 
        <?php
        if($wpestate_options['sidebar_class']=='' || $wpestate_options['sidebar_class']=='none' ){
            print ' col-md-4 '; 
        }else{
            print esc_attr($wpestate_options['sidebar_class']);
        }
        ?> 
        widget-area-sidebar listingsidebar2 listing_type_1" id="primary" >


            <?php
            if ( !wp_is_mobile() ) {
                include(locate_template ('templates/booking_form_template.php' ) );
            }
            ?>
            <div class="owner_area_wrapper_sidebar" id="listing_owner">
                <?php include(locate_template ('/templates/owner_area.php')); ?>
            </div>

            <?php  include(get_theme_file_path('sidebar-listing.php')); ?>

        
    </div>
</div>   

<?php get_footer(); ?>