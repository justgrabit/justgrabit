</div><!-- end content_wrapper started in header or full_width_row from prop list -->

<?php 
$footer_background          =   wprentals_get_option('wp_estate_footer_background','url');
$repeat_footer_back_status  =   wprentals_get_option('wp_estate_repeat_footer_back');
$footer_style               =   '';
$footer_back_class          =   '';
$wide_footer                =   wprentals_get_option('wp_estate_wide_footer');

if ($footer_background!=''){
    $footer_style='style=" background-image: url('.esc_url($footer_background).') "';
}

if( $repeat_footer_back_status=='repeat' ){
    $footer_back_class = ' footer_back_repeat ';
}else if( $repeat_footer_back_status=='repeat x' ){
    $footer_back_class = ' footer_back_repeat_x ';
}else if( $repeat_footer_back_status=='repeat y' ){
    $footer_back_class = ' footer_back_repeat_y ';
}else if( $repeat_footer_back_status=='no repeat' ){
    $footer_back_class = ' footer_back_repeat_no ';
}
 

if( !is_search() && !is_category() && !is_tax() &&  !is_tag() &&  !is_archive() && wpestate_check_if_admin_page($post->ID) ){
    // do nothing for now  
  
} else if(!is_search() && !is_category() && !is_tax() &&  !is_tag() &&  !is_archive() && basename(get_page_template($post->ID)) == 'property_list_half.php'){
    // do nothing for now   
  
} else if( ( is_category() || is_tax() ) &&  wprentals_get_option('wp_estate_property_list_type')==2){
    // do nothing for now
  
} else if(  is_page_template('advanced_search_results.php') &&  wprentals_get_option('wp_estate_property_list_type_adv')==2){
    // do nothing for now
 
}else{ 
    

?>


<footer id="colophon" <?php print wp_kses_post($footer_style); ?> class=" <?php print esc_attr($footer_back_class);?> ">    
    <?php 
        $wide_footer_class='';
        if($wide_footer=='yes'){
            $wide_footer_class=" wide_footer ";
        }
        ?>
    
    <div id="footer-widget-area" class="row <?php print esc_attr($wide_footer_class);?>">
        <?php  get_sidebar('footer');?>
    </div><!-- #footer-widget-area -->

    <div class="sub_footer">  
        <div class="sub_footer_content <?php print esc_attr($wide_footer_class);?>">
            <span class="copyright">
                <?php      
                if (function_exists('icl_translate') ){
                   $property_copy_text      =   icl_translate('wprentals','wp_estate_property_copyright_text', stripslashes ( esc_html( wprentals_get_option('wp_estate_copyright_message') ) ) );
                   print trim($property_copy_text);
                }else{
                    print stripslashes ( esc_html (wprentals_get_option('wp_estate_copyright_message') ) );
                }
                ?>
            </span>

            <div class="subfooter_menu">
                <?php      
                    wp_nav_menu( array(
                        'theme_location'    => 'footer_menu',
                        'depth'             => 1                           
                    ));  
                ?>
            </div>  
        </div>  
    </div>
</footer><!-- #colophon -->

<?php } // end property_list_half?>
<?php include(locate_template('templates/footer_buttons.php'));?>
<?php  
    if(is_singular('estate_property')){
        include(locate_template('templates/book_per_hour_form.php'));
    }
?>
<?php wp_get_schedules(); ?>

 
</div> <!-- end class container -->

<?php include(locate_template('templates/social_share.php'));?>

</div> <!-- end website wrapper -->


<?php 
if(is_singular('estate_property') ){
    ?>
    <!-- Modal -->
   <div class="modal fade" id="instant_booking_modal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
       <div class="modal-dialog">
           <div class="modal-content">
               <div class="modal-header"> 
                   <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                   <h2 class="modal-title_big" ><?php esc_html_e( 'Confirm your booking','wprentals');?></h2>
                   <h4 class="modal-title" id="myModalLabel"><?php esc_html_e( 'Review the dates and confirm your booking','wprentals');?></h4>
               </div>

               <div class="modal-body"></div>

               </div><!-- /.modal-content -->
           </div><!-- /.modal-dialog -->
       </div><!-- /.modal -->
   </div>
<?php
}

if ( isset($_GET['check_in_prop']) && isset($_GET['check_out_prop'])   ){

      print '<script type="text/javascript">
              //<![CDATA[
              jQuery(document).ready(function(){   
                setTimeout(function(){ 
                    jQuery("#end_date,#start_date").parent().removeClass("calendar_icon");
                    jQuery("#end_date").trigger("change");
                },1000);
              });
              //]]>
      </script>';

  }

  
 
if( is_singular('estate_property') || is_singular('estate_agent') ||  basename(get_page_template()) == 'user_dashboard_my_reservations.php' ){
   wpestate_ajax_show_contact_owner_form();
} 
?>
<?php 
if ( !is_user_logged_in() ) {  
    include(locate_template('templates/login_modal_form.php'));
}
$ajax_nonce = wp_create_nonce( "wprentals_ajax_filtering_nonce" );
print'<input type="hidden" id="wprentals_ajax_filtering" value="'.esc_html($ajax_nonce).'" />    ';

    
$ajax_nonce_log_reg = wp_create_nonce( "wpestate_ajax_log_reg_nonce" );
print'<input type="hidden" id="wpestate_ajax_log_reg" value="'.esc_html($ajax_nonce_log_reg).'" />    ';  

wp_footer();  ?>
</body>
</html>