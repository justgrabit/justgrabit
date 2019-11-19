<?php
global $feature_list_array;
global $edit_id;
global $moving_array;
global $edit_link_calendar;
global $submission_page_fields;

$list_to_show='';
$terms = get_terms( array(
    'taxonomy' => 'property_features',
    'hide_empty' => false,
) );



if(is_array($terms)):
    foreach($terms as $key => $term){
        $post_var_name  =   $term->slug;
        
        if(  in_array($post_var_name, $submission_page_fields) ) { 
            $value_label= $term->name;
      

            $list_to_show.= ' <div class="col-md-4" ><p>
                   <input type="hidden"    name="'.esc_attr($post_var_name).'" value="" style="display:block;">
                   <input type="checkbox" class="feature_list_save"  id="'.esc_attr($post_var_name).'" name="'.esc_attr($post_var_name).'" value="1"   data-feature="'.intval($term->term_id).'"  ';

            if (has_term( $post_var_name, 'property_features',$edit_id )  ) {
                $list_to_show.=' checked="checked" ';
            }else{
                if(is_array($moving_array) ){                      
                    if( in_array($post_var_name,$moving_array) ){
                          $list_to_show.=' checked="checked" ';
                    }
                }
            }
            $list_to_show.=' /><label for="'.esc_attr($post_var_name).'">'. esc_html( stripslashes($value_label) ).'</label></p></div>'; 
        }
    }
endif;

?>
  

<div class="col-md-12">
    <div class="user_dashboard_panel">
    <h4 class="user_dashboard_panel_title"><?php esc_html_e('Amenities and Features','wprentals');?></h4>     
     <?php wpestate_show_mandatory_fields();?>
    <div class="col-md-12" id="profile_message"></div>
    
    
    <?php 
    
    if ( $list_to_show!='' ){ ?>
        <div class="row">  
            <div class="col-md-12 dashboard_amenities">  
                <div class="col-md-3 dashboard_chapter_label"><?php esc_html_e('Select the amenities and features that apply for your listing','wprentals');?></div>
                <div class="col-md-9">
                    <?php print trim($list_to_show);//escaped above?>
                </div>
            </div>
        </div>
    <?php
    }         
    ?>
    
    <div class="col-md-12" style="display: inline-block;">  
        <input type="hidden" name="" id="listing_edit" value="<?php print intval($edit_id);?>">
        <input type="submit" class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="edit_prop_ammenities" value="<?php esc_html_e('Save', 'wprentals') ?>" />
        <a href="<?php echo  esc_url($edit_link_calendar);?>" class="next_submit_page"><?php esc_html_e('Go to Calendar settings.','wprentals');?></a>
  
        <?php
        $ajax_nonce = wp_create_nonce( "wprentals_amm_features_nonce" );
        print'<input type="hidden" id="wprentals_amm_features_nonce" value="'.esc_html($ajax_nonce).'" />    ';
                
        ?>
        
    </div>
</div>