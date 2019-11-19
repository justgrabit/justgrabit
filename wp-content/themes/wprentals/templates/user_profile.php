<?php
$current_user           =   wp_get_current_user();
$userID                 =   $current_user->ID;
$user_login             =   $current_user->user_login;
$first_name             =   get_the_author_meta( 'first_name' , $userID );
$last_name              =   get_the_author_meta( 'last_name' , $userID );
$user_email             =   get_the_author_meta( 'user_email' , $userID );
$user_mobile            =   get_the_author_meta( 'mobile' , $userID );
$user_phone             =   get_the_author_meta( 'phone' , $userID );
$description            =   get_the_author_meta( 'description' , $userID );
$facebook               =   get_the_author_meta( 'facebook' , $userID );
$twitter                =   get_the_author_meta( 'twitter' , $userID );
$linkedin               =   get_the_author_meta( 'linkedin' , $userID );
$pinterest              =   get_the_author_meta( 'pinterest' , $userID );
$user_skype             =   get_the_author_meta( 'skype' , $userID );
$instagram              =   get_the_author_meta( 'instagram' , $userID );
$youtube                =   get_the_author_meta( 'youtube' , $userID );
$user_website           =   get_the_author_meta( 'website' , $userID );


$user_title          =   get_the_author_meta( 'title' , $userID );
$user_custom_picture =   get_the_author_meta( 'custom_picture' , $userID );
$user_small_picture  =   get_the_author_meta( 'small_custom_picture' , $userID );
$image_id            =   get_the_author_meta( 'small_custom_picture',$userID);
$user_id_picture     =   get_the_author_meta( 'user_id_image', $userID);
$id_image_id         =   get_the_author_meta( 'user_id_image_id', $userID);
$about_me            =   get_the_author_meta( 'description' , $userID );
$live_in             =   get_the_author_meta( 'live_in' , $userID );
$i_speak             =   get_the_author_meta( 'i_speak' , $userID );
$paypal_payments_to  =   get_the_author_meta( 'paypal_payments_to' , $userID );
$payment_info        =   get_the_author_meta( 'payment_info' , $userID );
  
if($user_custom_picture==''){
    $user_custom_picture=get_stylesheet_directory_uri().'/img/default_user.png';
}

if($user_id_picture == '' ){
    $user_id_picture =get_stylesheet_directory_uri().'/img/default_user.png';
}

?>


<div class="user_profile_div">    
        <div class=" row">     
                <div class="col-md-12">
                <?php
                
                $sms_verification =esc_html( wprentals_get_option('wp_estate_sms_verification',''));
                if($sms_verification==='yes'){
                    $check_phone = get_the_author_meta( 'check_phone_valid' , $userID);
                  
                    if($check_phone!='yes'){
                
                    ?>
                    <div class="sms_wrapper">
                        <h4 class="user_dashboard_panel_title"><?php esc_html_e(' Validate your Mobile Phone Number to receive SMS Notifications','wprentals');?></h4>
                        <div class="col-md-12" id="sms_profile_message"></div>
                        <div class="col-md-9">
                            <?php //echo get_user_meta( $userID, 'validation_pin',true). '</br>';
                                esc_html_e('1. Add your Mobile no in Your Details section. Make sure you add it with country code.','wprentals');echo '</br>';
                                esc_html_e('2. Click on the button "Send me validation code".','wprentals');echo '</br>';
                                esc_html_e('3. You will get a 4 digit code number via sms at','wprentals');echo ' '.esc_html($user_mobile).'.</br> ';
                                esc_html_e('4. Add the 4 digit code in the form below and click "Validate Mobile Phone Number"','wprentals');
                                
                            ?>
                            <input type="text" style="max-width:250px;" id="validate_phoneno" class="form-control" value=""  name="validate_phoneno">
                            <button class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="send_sms_pin"><?php esc_html_e('Send me validation code','wprentals');?></button>
                            <button class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="validate_phone"><?php esc_html_e('Validate Mobile Phone Number','wprentals');?></button>
                            <?php  echo '</br>'; esc_html_e('*** If you don\'t receive the SMS, please check that your mobile phone number has the proper format (use the country code ex: +1 3232 232)','wprentals');echo '</br>';?>
                            
                            <?php
                            $ajax_nonce = wp_create_nonce( "wprentals_send_sms_nonce" );
                            print'<input type="hidden" id="wprentals_send_sms_nonce" value="'.esc_html($ajax_nonce).'" />    ';
                            ?>
                        
                        </div>    
                        <div class="col-md-6"></div>
                    </div>
                    <?php    
                    }
                }
     
                ?>       
                <div class="user_dashboard_panel">
                <h4 class="user_dashboard_panel_title"><?php esc_html_e('Your details','wprentals');?></h4>
                <div class="col-md-12" id="profile_message"></div>
                <div class="col-md-4">
                    <p>
                        <label for="firstname"><?php esc_html_e('First Name','wprentals');?></label>
                        <input type="text" id="firstname" class="form-control" value="<?php print esc_html($first_name);?>"  name="firstname">
                    </p>

                    <p>
                        <label for="secondname"><?php esc_html_e('Last Name','wprentals');?></label>
                        <input type="text" id="secondname" class="form-control" value="<?php print esc_html($last_name);?>"  name="firstname">
                    </p>

                    <p>
                        <label for="useremail"><?php esc_html_e('Email','wprentals');?></label>
                        <input type="text" id="useremail"  class="form-control" value="<?php print esc_html($user_email);?>"  name="useremail">
                    </p>
                    
                    <p>
                        <label for="about_me"><?php esc_html_e('About Me','wprentals');?></label>
                        <textarea id="about_me" class="form-control about_me_profile" name="about_me"><?php print esc_textarea($about_me);?></textarea>
                    </p>
                       
               
                </div>
                
                <div class="col-md-4">
                    <p>
                        <label for="userphone"><?php esc_html_e('Phone','wprentals');?></label>
                        <input type="text" id="userphone" class="form-control" value="<?php print esc_html($user_phone);?>"  name="userphone">
                    </p>
                    <p>
                        <label for="usermobile"><?php esc_html_e('Mobile (*Add the country code format Ex :+1 232 3232)','wprentals');?></label>
                        <input type="text" id="usermobile" class="form-control" value="<?php print esc_html($user_mobile);?>"  name="usermobile">
                    </p>
                                    
                       <p>
                    <label for="live_in"><?php esc_html_e('I live in','wprentals');?></label>
                       <input type="text" id="live_in"  class="form-control" value="<?php print esc_html($live_in);?>"  name="live_in">
                    </p>
                       
                    <p>
                        <label for="i_speak"><?php esc_html_e('I speak','wprentals');?></label>
                        <input type="text" id="i_speak"  class="form-control" value="<?php print esc_html($i_speak);?>"  name="i_speak">
                    </p>
                    
                 
                
                </div>
                <?php   wp_nonce_field( 'profile_ajax_nonce', 'security-profile' );   ?>
            
                
                <div class="col-md-4">
                     <div  id="profile-div" class="feature-media-upload">
                           <?php print '<img id="profile-image" src="'.esc_url($user_custom_picture).'" alt="'.esc_html__('thumb','wprentals').'" data-profileurl="'.esc_attr($user_custom_picture).'" data-smallprofileurl="'.esc_attr($image_id).'" >';?>

                            <div id="upload-container">                 
                                <div id="aaiu-upload-container">                 

                                    <button id="aaiu-uploader" class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button"><?php esc_html_e('Upload Image','wprentals');?></button>
                                    <div id="profile-div-upload-imagelist">
                                        <ul id="aaiu-ul-list" class="aaiu-upload-list"></ul>
                                    </div>
                                </div>  
                            </div>
                            <span class="upload_explain"><?php esc_html_e('* recommended size: minimum 550px ','wprentals');?></span>
                    </div>         
                </div>
                
                <div class="col-md-4">
                      <?php
                    $user_verified = get_user_meta( $userID, 'user_id_verified', TRUE );
                    $user_id_class = ( $user_verified == 1 ) ? 'verified' : 'feature-media-upload';
                ?>
                    
                    
                </div>
                
            
                <p class="fullp-button">  
                    <button class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="update_profile"><?php esc_html_e('Update profile','wprentals');?></button>
                  
                    <?php
                    $ajax_nonce = wp_create_nonce( "wprentals_update_profile_nonce" );
                    print'<input type="hidden" id="wprentals_update_profile_nonce" value="'.esc_html($ajax_nonce).'" />    ';
                    
                    ?>

                    <?php
                      $agent_id   =   get_user_meta($userID, 'user_agent_id', true);
                        if ( $agent_id!=0 && get_post_status($agent_id)=='publish'  ){
                            print'<a href='.esc_url ( get_permalink($agent_id) ).' class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="view_profile">'.esc_html__('View public profile', 'wprentals').'</a>';
                        }
                    ?>
                    <button class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="delete_profile"><?php esc_html_e('Delete account','wprentals');?></button>             
                </p>
               
                
            </div>
        </div>

        <div class="col-md-12">
            <div class="user_dashboard_panel">
            </div>
        </div>
 
        <div class="col-md-12">  
            <div class="user_dashboard_panel">
                <h4 class="user_dashboard_panel_title"><?php esc_html_e('Change Password','wprentals');?></h4>

                <div class="col-md-12" id="profile_pass">
                     <?php esc_html_e('*After you change the password you will have to login again.','wprentals'); ?>
                </div> 

                <p  class="col-md-4">
                    <label for="oldpass"><?php esc_html_e('Old Password','wprentals');?></label>
                    <input  id="oldpass" value=""  class="form-control" name="oldpass" type="password">
                </p>

                <p  class="col-md-4">
                    <label for="newpass"><?php esc_html_e('New Password ','wprentals');?></label>
                    <input  id="newpass" value="" class="form-control" name="newpass" type="password">
                </p>
                <p  class="col-md-4">
                    <label for="renewpass"><?php esc_html_e('Confirm New Password','wprentals');?></label>
                    <input id="renewpass" value=""  class="form-control" name="renewpass"type="password">
                </p>

                <?php   wp_nonce_field( 'pass_ajax_nonce', 'security-pass' );   ?>
                <p class="fullp-button">
                    <button class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="change_pass"><?php esc_html_e('Reset Password','wprentals');?></button>
                </p>
           </div>
        </div>

 </div>