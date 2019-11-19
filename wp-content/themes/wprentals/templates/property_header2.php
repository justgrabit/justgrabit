<?php
global $post;
global $property_action_terms_icon;
global $property_action;
global $property_category_terms_icon;
global $property_category;
global $guests;
global $bedrooms;
global $bathrooms;
global $favorite_text;
global $favorite_class;
global $wpestate_options;
$rental_type=wprentals_get_option('wp_estate_item_rental_type');
?>--><div class="property_header property_header2">
	
        <div class="property_categs ">
			
			  <div  id="contact_me_long" class=" owner_read_more " data-postid="<?php esc_attr(the_ID());?>" ><?php esc_html_e('Contact Owner','wprentals');?></div>
			
			
			
			
            <div class="property_header_wrapper 
                <?php 
                if ( $wpestate_options['content_class']=='col-md-12' || $wpestate_options['content_class']=='none'){
                    print 'col-md-8';
                }else{
                   print  esc_attr($wpestate_options['content_class']); 
                }?> 
            ">
           
                <div class="category_wrapper ">
                    <div class="category_details_wrapper">
                        <?php 
                        
                        if(wprentals_get_option( 'wp_estate_use_custom_icon_area') =='yes' ){ 
                            wprentals_icon_bar_design();
                        }else{
                            wprentals_icon_bar_classic($property_action,$property_category,$rental_type,$guests,$bedrooms,$bathrooms);
                        } 
                        ?>
                        
                    </div>
                    
                    <a href="#listing_calendar" class="check_avalability"><?php esc_html_e('Check Availability','wprentals');?></a>
					    
                </div>
                
              
                
                <div  id="listing_description">
                <?php
                    $content = get_the_content();
                    $content = apply_filters('the_content', $content);
                    $content = str_replace(']]>', ']]&gt;', $content);
                    $wpestate_property_description_text         =  wprentals_get_option('wp_estate_property_description_text');
                    if (function_exists('icl_translate') ){
                        $wpestate_property_description_text     =   icl_translate('wprentals','wp_estate_property_description_text', esc_html( wprentals_get_option('wp_estate_property_description_text') ) );
                    }
                    
                    if($content!=''){   
                        print '<h4 class="panel-title-description">'.esc_html($wpestate_property_description_text).'</h4>';
                        print '<div itemprop="description" id="listing_description_content"   class="panel-body">'.$content.'</div>'; //escaped above      
                    }
                ?>
                </div>        
                <div id="view_more_desc"><?php esc_html_e('View more','wprentals');?></div>         
        </div>
    <?php  
        $post_id=$post->ID; 
        $guest_no_prop ='';
        if(isset($_GET['guest_no_prop'])){
            $guest_no_prop = intval($_GET['guest_no_prop']);
        }
        $guest_list= wpestate_get_guest_dropdown('noany');
    ?>

    <div class="booking_form_request  
        <?php
        if($wpestate_options['sidebar_class']=='' || $wpestate_options['sidebar_class']=='none' ){
            print ' col-md-4 '; 
        }else{
            print esc_attr($wpestate_options['sidebar_class']);
        }
        ?>
         " id="booking_form_request">
        <div id="booking_form_request_mess"></div>
            <h3 ><?php esc_html_e('Book Now','wprentals');?></h3>
             
                <div class="has_calendar calendar_icon">
                    <input type="text" id="start_date" placeholder="<?php  echo wpestate_show_labels('check_in',$rental_type); ?>"  class="form-control calendar_icon" size="40" name="start_date" 
                            value="<?php if( isset($_GET['check_in_prop']) ){
                               echo sanitize_text_field ( $_GET['check_in_prop'] );
                            }
                            ?>">
                </div>

                <div class=" has_calendar calendar_icon">
                    <input type="text" id="end_date" disabled placeholder="<?php  echo wpestate_show_labels('check_out',$rental_type); ?>" class="form-control calendar_icon" size="40" name="end_date" 
                            value="<?php if( isset($_GET['check_out_prop']) ){
                               echo sanitize_text_field ( $_GET['check_out_prop'] );
                            }
                            ?>">
                </div>

                <?php 
                $max_guest = get_post_meta($post_id,'guest_no',true);
                if($rental_type==0){ 
                ?>
                    <div class=" has_calendar guest_icon ">
                        <?php 
                       
                        print '
                        <div class="dropdown form-control">
                            <div data-toggle="dropdown" id="booking_guest_no_wrapper" class="filter_menu_trigger" data-value="';
                                if(isset($_GET['guest_no_prop']) && $_GET['guest_no_prop']!=''){
                                    echo esc_html( $_GET['guest_no_prop'] );
                                }else{
                                  echo 'all';
                                }
                            print '">';
                            print '<div class="text_selection">';
                            if(isset($_GET['guest_no_prop']) && $_GET['guest_no_prop']!=''){
                                echo esc_html( $_GET['guest_no_prop'] ).' '.esc_html__( 'guests','wprentals');
                            }else{
                                esc_html_e('Guests','wprentals');
                            }
                            print '</div>';

                            print '<span class="caret caret_filter"></span>
                            </div>           
                            <input type="hidden" name="booking_guest_no"  value="">
                            <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="booking_guest_no_wrapper" id="booking_guest_no_wrapper_list">
                                '.$guest_list.'
                            </ul>        
                        </div>';//escaped above
                        ?> 
                    </div>
                <?php 
                }else{ 
                ?>
                   <input type="hidden" name="booking_guest_no"  value="1">
                <?php 
                }
                // shw extra options
                wpestate_show_extra_options_booking($post_id)
                ?>
            
                <p class="full_form " id="add_costs_here"></p>            

                <input type="hidden" id="listing_edit" name="listing_edit" value="<?php print intval($post_id);?>" />

                <div class="submit_booking_front_wrapper">
                    <?php   
                    $overload_guest                 =   floatval   ( get_post_meta($post_id, 'overload_guest', true) );
                    $price_per_guest_from_one       =   floatval   ( get_post_meta($post_id, 'price_per_guest_from_one', true) );
                    ?>
                    
                    <?php  $instant_booking                 =   floatval   ( get_post_meta($post_id, 'instant_booking', true) ); 
                    if($instant_booking ==1){ ?>
                        <div id="submit_booking_front_instant_wrap"><input type="submit" id="submit_booking_front_instant" data-maxguest="<?php print esc_attr($max_guest); ?>" property_header_2 data-overload="<?php print esc_attr($overload_guest);?>" data-guestfromone="<?php print esc_attr($price_per_guest_from_one); ?>"  class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" value=" <?php esc_html_e('Instant Booking','wprentals');?>" /></div>
                    <?php }else{?>   
                        <input type="submit" id="submit_booking_front" data-maxguest="<?php print esc_attr($max_guest); ?>" data-overload="<?php print esc_attr($overload_guest);?>" property_header_2 data-guestfromone="<?php print esc_attr($price_per_guest_from_one); ?>"  class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" value="<?php esc_html_e('Book Now','wprentals');?>" />
                    <?php }?>
                        
                    <?php wp_nonce_field( 'booking_ajax_nonce', 'security-register-booking_front' );?>
                </div>

                <div class="third-form-wrapper">
                    <div class="col-md-6 reservation_buttons">
                        <div id="add_favorites" class=" <?php print esc_attr($favorite_class);?>" data-postid="<?php esc_attr(the_ID());?>">
                            <?php print trim($favorite_text);?>
                        </div>                 
                    </div>

                    <div class="col-md-6 reservation_buttons">
                        <div id="contact_host" class="col-md-6"  data-postid="<?php esc_attr(the_ID());?>">
                            <?php esc_html_e('Contact Owner','wprentals');?>
                        </div>  
                    </div>
                </div>
                
                <?php 
                echo wpestate_share_unit_desing($post_id);
                ?>

                         

        </div>
    
    
    
    
     </div>
</div>