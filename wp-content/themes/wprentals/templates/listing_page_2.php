<?php
global $current_user;
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
include(locate_template('templates/listingslider.php') ); 
include(locate_template('templates/property_header2.php') );
?>

<div  class="row content-fixed-listing">
    <div class=" <?php 
    if ( $wpestate_options['content_class']=='col-md-12' || $wpestate_options['content_class']=='none'){
        print 'col-md-8';
    }else{
        if(isset($wpestate_options['content_class'])){
            print esc_attr( $wpestate_options['content_class']); 
        }
    }?> ">
 
        <?php include(locate_template('templates/ajax_container.php')); ?>
        <?php
        while (have_posts()) : the_post();
            $image_id       =   get_post_thumbnail_id();
            $image_url      =   wp_get_attachment_image_src($image_id, 'wpestate_property_full_map');
            $full_img       =   wp_get_attachment_image_src($image_id, 'full');
            $image_url      =   $image_url[0];
            $full_img       =   $full_img [0];     
        ?>

        <div class="single-content listing-content">
        <!-- property images   -->   
        <div class="panel-wrapper imagebody_wrapper">
           
            <div class="panel-body imagebody imagebody_new">
                <?php  
                include(locate_template('templates/property_pictures.php') );
                ?>
            </div>
            
            
            <div class="panel-body video-body">
                <?php
                $video_id           = esc_html( get_post_meta($post->ID, 'embed_video_id', true) );
                $video_type         = esc_html( get_post_meta($post->ID, 'embed_video_type', true) );

                if($video_id!=''){
                    if($video_type=='vimeo'){
                        echo wpestate_custom_vimdeo_video($video_id);
                    }else{
                        echo wpestate_custom_youtube_video($video_id);
                    }    
                }
                ?>
            </div>
     
        </div>

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
                <div itemprop="priceSpecification" class="panel-body panel-body-border">
                    <?php print estate_listing_price($post->ID); ?>
                    <?php  wpestate_show_custom_details($post->ID); ?>
                    <?php  wpestate_show_custom_details_mobile($post->ID); ?>
                </div>
            </div>
        </div>
		
        
        <div class="panel-wrapper">
            <!-- property address   -->             
            <a class="panel-title" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseTwo">  <span class="panel-title-arrow"></span>
                <?php if($wpestate_property_adr_text!=''){
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
                print'<a class="panel-title"  id="listing_details" data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseTree"><span class="panel-title-arrow"></span>'.esc_html($wpestate_property_details_text).'  </a>';
            }
            ?>
            <div id="collapseTree" class="panel-collapse collapse in">
                <div class="panel-body panel-body-border">
                    <?php print estate_listing_details($post->ID);?>
                </div>
            </div>
        </div>


        <!-- Features and Amenities -->
        <div class="panel-wrapper">
            <?php 
            $terms = get_terms( array(
                   'taxonomy' => 'property_features',
                   'hide_empty' => false,
            ) );
            if ( count( $terms )!=0 && !count( $terms )!=1 ){ //  if are features and ammenties
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
            <div class="panel-wrapper">
                <a class="panel-title" id="yelp_details" data-toggle="collapse" data-parent="#yelp_details" href="#collapseFive"><span class="panel-title-arrow"></span> <?php esc_html_e( 'What\'s Nearby', 'wprentals');?>  </a>

                
                <div id="collapseFive" class="panel-collapse collapse in">
                    <div class="panel-body panel-body-border">
                        <?php print wpestate_yelp_details($post->ID); ?>
                    </div>
                </div>

            </div>
        <?php }?>
        
        <?php
        include(locate_template ('/templates/show_avalability.php') );
        wp_reset_query();
        ?>  
         
        <?php
        endwhile; // end of the loop
        $show_compare=1;
        ?>
        </div><!-- end single content -->
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
        widget-area-sidebar listingsidebar" id="primary" >
     
        <?php  include(get_theme_file_path('sidebar-listing.php')); ?>
    </div>
</div>   

<div class="full_width_row">    
    <?php include(locate_template ('/templates/listing_reviews.php') ); ?>
    <div class="owner-page-wrapper">
        <div class="owner-wrapper  content-fixed-listing row" id="listing_owner">
            <?php include(locate_template ('/templates/owner_area.php' ) ); ?>
        </div>
    </div>
    
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
            data-cur_lat="<?php   print esc_attr($wpestate_gmap_lat);?>" 
            data-cur_long="<?php print esc_attr($wpestate_gmap_long); ?>" 
            data-post_id="<?php print intval($post->ID); ?>">
        </div>
    </div>    
    <?php   include(locate_template ('/templates/similar_listings.php') );?>

</div>

<?php get_footer(); ?>