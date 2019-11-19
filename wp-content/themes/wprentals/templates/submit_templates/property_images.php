<?php
global $action;
global $edit_id;
global $embed_video_id;
global $option_video;
global $edit_link_details;
global $submission_page_fields;
$images='';
$thumbid='';
$attachid='';


$arguments = array(
      'numberposts'     => -1,
      'post_type'       => 'attachment',
      'post_parent'     => $edit_id,
      'post_status'     => null,
      'exclude'         => get_post_thumbnail_id(),
      'orderby'         => 'menu_order',
      'order'           => 'ASC'
  );
$post_attachments = get_posts($arguments);
$post_thumbnail_id = $thumbid = get_post_thumbnail_id( $edit_id );

   
    foreach ($post_attachments as $attachment) {
        $preview =  wp_get_attachment_image_src($attachment->ID, 'wpestate_property_listings');    
        
        if($preview[0]!=''){
            $images .=  '<div class="uploaded_images" data-imageid="'.esc_attr($attachment->ID).'"><img src="'.esc_url($preview[0]).'" alt="'.esc_html__('thumb','wprentals').'" /><i class="far fa-trash-alt"></i>';
            if($post_thumbnail_id == $attachment->ID){
                $images .='<i class="fa thumber fa-star"></i>';
            }
        }else{
            $images .=  '<div class="uploaded_images" data-imageid="'.esc_attr($attachment->ID).'"><img src="'.get_template_directory_uri().'/img/pdf.png" alt="'.esc_html__('thumb','wprentals').'" /><i class="far fa-trash-alt"></i>';
            if($post_thumbnail_id == $attachment->ID){
                $images .='<i class="fa thumber fa-star"></i>';
            }
        }
        $images .='</div>';
        $attachid.= ','.intval($attachment->ID);
    }


?>


<div class="col-md-12" id="new_post2">
    <div class="user_dashboard_panel">
    <h4 class="user_dashboard_panel_title"><?php  esc_html_e('Listing Media','wprentals');?></h4>
  
    <?php wpestate_show_mandatory_fields();?>
		<small style="    font-size: 10px;
    line-height: 0;
    padding: 15px;
    font-weight: bold;">After hitting submit, and the loader reaches 100%, please wait until you see the image of your item on your screen before moving on to the next step. This usually takes about 20 to 30 seconds.</small>
    <div class="col-md-12" id="profile_message"></div>
     
    <?php 
    if(is_array($submission_page_fields) && in_array('attachid', $submission_page_fields)) {
    ?>

        <div class="col-md-12">
            <div id="upload-container">                 
                <div id="aaiu-upload-container">                 
                    <div id="aaiu-upload-imagelist">
                        <ul id="aaiu-ul-list" class="aaiu-upload-list"></ul>
                    </div>

                    <div id="imagelist">
                    <?php 
                        $ajax_nonce = wp_create_nonce( "wpestate_image_upload" );
                        print'<input type="hidden" id="wpestate_image_upload" value="'.esc_html($ajax_nonce).'" />    ';
                        if($images!=''){
                            print trim($images);//escaped above
                        }
                    ?>  
                    </div>

                    <div id="aaiu-uploader"  class=" wpb_btn-small wpestate_vc_button  vc_button"><?php esc_html_e('Select Media','wprentals');?></div>
                    <input type="hidden" name="attachid" id="attachid" value="<?php print esc_html($attachid);?>">
                    <input type="hidden" name="attachthumb" id="attachthumb" value="<?php print esc_html($thumbid);?>">
                    <p class="full_form full_form_image">
                        <?php esc_html_e('*Double Click on the image to select featured. ','wprentals');?></br>
                         <?php esc_html_e('**Change images order with Drag & Drop. ','wprentals');?>
                    </p>
                </div>  
            </div>
        </div>
    <?php } ?>
    
      
    <?php 
    if(is_array($submission_page_fields) && in_array('embed_video_type', $submission_page_fields)) {
    ?>

        <div class="col-md-4">
            <p>
                <label for="embed_video_type"><?php esc_html_e('Video from','wprentals');?></label>
                <select id="embed_video_type" name="embed_video_type" class="select-submit2">
                    <?php print trim($option_video);?>
                </select>
            </p>
        </div>

    <?php } ?>
    
    <?php 
    if(is_array($submission_page_fields) && in_array('embed_video_id', $submission_page_fields)) {
    ?>
        <div class="col-md-4">
            <p>     
               <label for="embed_video_id"><?php esc_html_e('Video id: ','wprentals');?></label>
               <input type="text" id="embed_video_id" class="form-control"  name="embed_video_id" size="40" value="<?php print esc_html($embed_video_id);?>">
            </p>
        </div>
    
    <?php } ?>
    
    <div class="col-md-12" style="display: inline-block;"> 
        <input type="hidden" name="" id="listing_edit" value="<?php print intval($edit_id);?>">
		<input type="submit" class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="edit_prop_image" value="<?php esc_html_e('Save', 'wprentals') ?>" />
        <a href="<?php echo site_url(); ?>?p=<?php print intval($edit_id);?>" class="next_submit_page"><?php esc_html_e('View Listing.','wprentals');?></a>
  
        <?php
        $ajax_nonce = wp_create_nonce( "wprentals_edit_prop_image_nonce" );
        print'<input type="hidden" id="wprentals_edit_prop_image_nonce" value="'.esc_html($ajax_nonce).'" />    ';
        ?>
        
    </div>
   
</div>  