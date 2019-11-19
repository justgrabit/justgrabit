<?php
global $wpestate_unit;
global $edit_id;
global $property_size;
global $property_lot_size;
global $property_rooms;
global $property_bedrooms;
global $property_bathrooms;
global $custom_fields;    
global $custom_fields_array;
global $edit_link_location;
global $submission_page_fields;
global $extra_options;
$measure_sys            = esc_html ( wprentals_get_option('wp_estate_measure_sys','') ); 

$i=0;
$custom_fields_show ='';
if( !empty($custom_fields)){  
    while($i< count($custom_fields) ){
        $name               =   $custom_fields[$i][0];
        $label              =   stripslashes( $custom_fields[$i][1] );
        $type               =   $custom_fields[$i][2];
        $order              =   $custom_fields[$i][3];
        $dropdown_values    =   '';
        if(isset($custom_fields[$i][4])){
            $dropdown_values    =   $custom_fields[$i][4];
        }
        
        $slug  =$prslig            =   strtolower(str_replace(' ','_',$name));
        $prslig1      =    strtolower( htmlspecialchars ( str_replace(' ','_', trim($name) ) , ENT_QUOTES ));
        $slug         =   wpestate_limit45(sanitize_title( $name ));
        $slug         =   sanitize_key($slug);
        $post_id      =   $post->ID;
        $show         =   1;  
        $i++;

        if (function_exists('icl_translate') ){
            $label     =   icl_translate('wprentals','wp_estate_property_custom_front_'.$label, $label ) ;
        }   

        $custom_fields_show.= '<div class="col-md-6"><p>';
        $value  =   $custom_fields_array[$slug];
        if(   is_array($submission_page_fields) && ( in_array($prslig, $submission_page_fields) ||  in_array($slug, $submission_page_fields))  ) { 
            $custom_fields_show.=  wpestate_show_custom_field(0,$slug,$name,$label,$type,$order,$dropdown_values,$post_id,$value);
        }

        $custom_fields_show.= '</p></div>';

   }
}
 
?> 
<div class="col-md-12 custom_details_wrapper">
    <div class="user_dashboard_panel">
        <h4 class="user_dashboard_panel_title"><?php esc_html_e('Listing Details','wprentals');?></h4>
        <?php wpestate_show_mandatory_fields();?>
        <div class="col-md-12" id="profile_message"></div>
        <div class="row">  
            <div class="col-md-12">   
                <div class="col-md-3 dashboard_chapter_label"><?php esc_html_e('Listing Details','wprentals');?></div>
               
               
                <div class="col-md-6"> 
                    <?php
                    $rental_type=wprentals_get_option('wp_estate_item_rental_type');
             
                    ?>
                        <?php 
                        if(is_array($submission_page_fields) && in_array('property_size', $submission_page_fields)) {
                        ?>
                            <div class="col-md-6">
                                <p>
                                    <label for="property_size"> <?php esc_html_e('Size in','wprentals');print ' '.esc_html($measure_sys).'<sup>2</sup>';?></label>
                                    <input type="text" id="property_size" size="40" class="form-control"  name="property_size" value="<?php print esc_html($property_size);?>">
                                </p>
                            </div>
                        <?php } ?>



                        <?php 
                        if(is_array($submission_page_fields) && in_array('property_rooms', $submission_page_fields)) {
                        ?>  
                            <div class="col-md-6">
                                <p>
                                    <label for="property_rooms"><?php esc_html_e('Rooms','wprentals');?></label>
                                    <input type="text" id="property_rooms" size="40" class="form-control"  name="property_rooms" value="<?php print esc_html($property_rooms);?>">
                                </p>
                            </div>
                        <?php } ?>

                        <?php 
                        if(is_array($submission_page_fields) && in_array('property_bedrooms', $submission_page_fields)) {
                        ?>
                            <div class="col-md-6">
                                <p>
                                    <label for="property_bedrooms "><?php esc_html_e('Bedrooms','wprentals');?></label>
                                    <input type="text" id="property_bedrooms" size="40" class="form-control"  name="property_bedrooms" value="<?php print esc_html($property_bedrooms);?>">
                                </p>
                            </div>
                        <?php } ?>


                        <?php 
                        if(is_array($submission_page_fields) && in_array('property_bathrooms', $submission_page_fields)) {
                        ?>
                            <div class="col-md-6">
                                <p>
                                    <label for="property_bedrooms"><?php esc_html_e('Bathrooms','wprentals');?></label>
                                    <input type="text" id="property_bathrooms" size="40" class="form-control"  name="property_bathrooms" value="<?php print esc_html($property_bathrooms);?>">
                                </p>
                            </div>
                        <?php } ?>

            <!-- Add custom details -->
            <?php
            print trim($custom_fields_show);
            ?>  

            
            <div id="extra_details_list">
                <?php
                $details =   get_post_meta($edit_id, 'property_custom_details', true);
                print '<h4 class="user_dashboard_panel_title">'.esc_html__('Custom Details','wprentals').'</h4>';
                if(is_array($details)){
                   
                    foreach ($details as $label=>$value){
                        print ' 
                        <div class="extra_detail_option_wrapper">
                            <div class="extra_detail_option col-md-4">
                                <input type="text" class=" extra_option_name form-control" value="'.esc_html($label).'">
                            </div>
                            <div class="extra_detail_option col-md-4">
                                <input type="text"class=" extra_option_value form-control" value="'.esc_html($value).'">
                            </div>
                            <div class="extra_detail_option col-md-4">
                              <span class="delete_extra_detail">'.__('Delete','wprentals').'</span>
                            </div>
                        </div>';
                    }
                    
                }
                
                ?>
               
            </div> 
            
           <div class="col-md-12 add_custom_detail"> 
                    <label class="extra_details_label"><?php esc_html_e('Extra Details','wprentals');?></label>
                 
                    <div class="col-md-4">
                        <input type="text" id="custom_detail_label" class="form-control" name="custom_detail_label" placeholder="<?php _e('Label','wprentals');?>">
                    </div> 
                    <div class="col-md-4">
                        <input type="text" id="custom_detail_value" class="form-control" name="custom_detail_value" placeholder="<?php _e('Value','wprentals');?>">
                    </div>
                    <div class="col-md-4">
                        <span id="add_extra_detail"><?php _e('Add Detail','wprentals')?></span>
                    </div>
            </div> 

        </div>
                <div class="col-md-12" style="display: inline-block;">  
                    <input type="hidden" name="" id="listing_edit" value="<?php print intval($edit_id);?>">
                    <input type="submit" class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="edit_prop_details" value="<?php esc_html_e('Save', 'wprentals') ?>" />
                    <a href="<?php echo  esc_url($edit_link_location);?>" class="next_submit_page"><?php esc_html_e('Go to Location settings.','wprentals');?></a>

                    <?php
                    $ajax_nonce = wp_create_nonce( "wprentals_edit_prop_details_nonce" );
                    print'<input type="hidden" id="wprentals_edit_prop_details_nonce" value="'.esc_html($ajax_nonce).'" />    ';
                    ?>    
                    
                </div>
                </div>
            </div>   
        
    </div>  
</div>