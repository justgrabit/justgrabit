/*global $, jQuery, ajaxcalls_vars, document,mega_details,booking_array,price_array,wpestate_convert_selected_days, control_vars, window, map, setTimeout, Modernizr, property_vars*/
jQuery(window).scroll(function ($) {
    "use strict";
    var scroll = jQuery(window).scrollTop();
    if (scroll >= 400) {
        if (!Modernizr.mq('only all and (max-width: 1180px)')) {
            jQuery(".property_menu_wrapper_hidden").fadeIn(400);
            jQuery(".property_menu_wrapper").fadeOut(400);
        }
    } else {
        jQuery(".property_menu_wrapper_hidden").fadeOut(400);
        jQuery(".property_menu_wrapper").fadeIn(400);
    }
});


var booking_started=0;
price_array=[];
if(control_vars.custom_price!==''){
    price_array     = JSON.parse (control_vars.custom_price);
}

booking_array=[];
if( control_vars.booking_array!=='' && control_vars.booking_array.length!==0 ){
    booking_array   = JSON.parse (control_vars.booking_array);
}



cleaning_fee_per_day            =   control_vars.cleaning_fee_per_day;        
city_fee_per_day                =   control_vars.city_fee_per_day;
price_per_guest_from_one        =   control_vars.price_per_guest_from_one;
checkin_change_over             =   control_vars.checkin_change_over;
checkin_checkout_change_over    =   control_vars.checkin_checkout_change_over;
min_days_booking                =   control_vars.min_days_booking;
extra_price_per_guest           =   control_vars.extra_price_per_guest;
price_per_weekeend              =   control_vars.price_per_weekeend;

mega_details=[];
if(control_vars.mega_details!==''){
    mega_details                    =   JSON.parse(control_vars.mega_details);
}

weekdays=[];
if(control_vars.weekdays!==''){
    weekdays                        =   JSON.parse(control_vars.weekdays);
}

function wprentals_show_booking_calendars(in_date, out_date){
     if(property_vars.book_type==='2'){
        check_in_out_enable_calendar_per_hour(in_date, out_date);
    }else {
        check_in_out_enable2(in_date, out_date);
    }
}

// block past dates
// 2 click selection adn hover
// show severak weeks in header
//


function check_in_out_enable2(in_date, out_date) {
    "use strict";
    var today, prev_date,selected_date,selected_min_days,who_is,read_in_date;
    
   
    
    today = new Date();
  
    jQuery("#" + in_date).datepicker({
        dateFormat:control_vars.date_format,
        minDate: today,
        beforeShowDay:function (date){
            return enableAllTheseDays(date, in_date );
        }    
    }, jQuery.datepicker.regional[control_vars.datepick_lang]).focus(function () {
                jQuery(this).blur();
            }).datepicker('widget').wrap('<div class="ll-skin-melon"/>');




    jQuery("#" + in_date).change(function () {
     
        prev_date = new Date(jQuery('#' + in_date).val());
        read_in_date = jQuery('#' + in_date).val();
        selected_date       =   wpestate_get_unix_time(prev_date);
        var read_in_date_converted = wpestate_convert_selected_days(read_in_date);       
        selected_min_days   =   wpestate_return_min_days_value(wpestate_get_unix_time(read_in_date_converted));
        
        
        if (selected_min_days>0){
            prev_date =wpestate_UTC_addDays( read_in_date_converted,selected_min_days-1 );
        }else{
            prev_date =wpestate_UTC_addDays( read_in_date_converted,0 );
        }
        
        
        jQuery("#" + out_date).val('');
        jQuery("#" + out_date).removeAttr('disabled');
        jQuery("#" + out_date).datepicker("destroy");
        jQuery("#" + out_date).datepicker({
            dateFormat:control_vars.date_format,
            minDate: prev_date,
            beforeShowDay:function (date){
            return enableAllTheseDays(date, out_date );
        } 
        }, jQuery.datepicker.regional[control_vars.datepick_lang]);
        
    });


    jQuery("#" + out_date).datepicker({
        dateFormat:control_vars.date_format,
        minDate: today,
        beforeShowDay:function (date){
            return enableAllTheseDays(date, out_date );
        } 
    }, jQuery.datepicker.regional[control_vars.datepick_lang]).focus(function () {
            jQuery(this).blur();
    });

    jQuery("#" + in_date).on('click',function(){
        who_is=1;
    });
    
    jQuery("#" + out_date).on('click',function(){
        who_is=2;
      
    });

    jQuery("#" + in_date + ",#" + out_date).change(function (event) {
        //jQuery(this).parent().removeClass('calendar_icon');
    });
    
     
    jQuery("html").on("mouseenter",".wpestate_calendar ", function() {
        var price,unit_class;
        price = jQuery(this).attr('title');
        unit_class = jQuery(this).attr('class');
        unit_class = unit_class.match(/\d+/);
        
      
        if( unit_class ){
            unit_class = unit_class[0];
            
            jQuery(this).append('<span class="hover_price">'+price+'</span>');
            if(who_is===1){
                wpestate_show_min_days_reservation(unit_class);
            }
        }
    });


    jQuery("html").on("mouseleave",".wpestate_calendar", function() {
        jQuery(this).find('.hover_price').remove();
        wpestate_remove_min_days_reservation(this);
    });
  
}

var start_reservation,end_reservation,reservation_class;

function  wpestate_return_weekeend_price(week_day,unixtime1_key){
    display_price='';
    if(mega_details[unixtime1_key] !== undefined){
        if (  parseFloat (mega_details[unixtime1_key]['period_price_per_weekeend'],10)!==0 ){
            display_price = parseFloat (mega_details[unixtime1_key]['period_price_per_weekeend'],10);
        }             
    }else if( parseFloat(price_per_weekeend,10)!==0 ){
        display_price = parseFloat(price_per_weekeend,10);
    } 

    return display_price;  
                
}

function wpestate_get_unix_time(date){
    unixtime = new Date(date).getTime()/1000; 
//    unixtime1= unixtime - date.getTimezoneOffset()*60;
    unixtime1_key = String(unixtime);
    return unixtime1_key;
}
 
 

function wpestate_addDays(date, days) {
    var result = new Date(date);
    result.setDate(result.getDate()+1 +days);
    return result;
}


function wpestate_return_min_days_value(item){
    var step, minim_days;
    step=0;
    minim_days=0;
  
    if( mega_details[item] != undefined  ){
        minim_days = parseFloat( mega_details[item]['period_min_days_booking'] ,10 );
    }else if(min_days_booking !=undefined && min_days_booking>0){
        minim_days=parseFloat (min_days_booking,10);
    }
    
    return minim_days;
}







function wpestate_show_min_days_reservation(item){
    var step, minim_days ,classad,item_count;
    step=0;
    minim_days=0;
    

    if( mega_details[item] != undefined  ){
        minim_days = parseFloat( mega_details[item]['period_min_days_booking'] ,10 );
    }else if(min_days_booking !=undefined && min_days_booking>0){
        minim_days=parseFloat (min_days_booking,10);
    }
    
    classad='date'+item;
    item_count=parseFloat(item,10);
    if(minim_days>0){
        while(step<minim_days){
            step++;
            jQuery('.'+classad).addClass('minim_days_reservation');
            item_count = item_count+(24*60*60);//next day
            classad='date'+item_count;
        }
        
    }

   
}


function wpestate_remove_min_days_reservation(item){
    jQuery('.wpestate_calendar').removeClass('minim_days_reservation');
}




function enableAllTheseDays(date, from_who) {
    "use strict";
    var  today,block_check_in, block_check_in_check_out, week_day, display_price, received_date,unixtime, unixtime1,unixtime1_key,from_css_class ; 
    
    received_date       =   new Date(date);
    today               =   Math.floor(Date.now() / 1000);
    unixtime            =   received_date.getTime()/1000; 
    unixtime1           =   unixtime - date.getTimezoneOffset()*60;
    unixtime1_key       =   String(unixtime1);
    reservation_class   =   '';
    display_price       =   '';
    from_css_class      =   '';
    block_check_in      =   0;
    week_day            =   received_date.getDay();
    block_check_in_check_out    =   0;

    if(week_day===0){
        week_day=7;
    }
  
    
    // establish the price per weekend
    ////////////////////////////////////////////////////////////////////////////
    
    if ( control_vars.setup_weekend_status === '0' && (week_day==6 || week_day==7) ){
        display_price = wpestate_return_weekeend_price(week_day,unixtime1_key);
    }else if(control_vars.setup_weekend_status === '1'  && (week_day==5 || week_day==6) ){
       display_price = wpestate_return_weekeend_price(week_day,unixtime1_key);
    }else if(control_vars.setup_weekend_status === '2' && (week_day==5 || week_day==6 || week_day==7)){
       display_price = wpestate_return_weekeend_price(week_day,unixtime1_key);
    }
    
   
    // establish the price per guest mode
    ////////////////////////////////////////////////////////////////////////////
    
    if( parseFloat(price_per_guest_from_one,10)===1 ){
      
//      display_price = '<div class="hover_from">'+control_vars.from+" "+parseFloat (extra_price_per_guest,10)+'</div>';
     //   display_price = control_vars.from+" "+parseFloat (extra_price_per_guest,10);
    
        from_css_class= " hover_from ";
    }
   
    // establish the start day block
    ////////////////////////////////////////////////////////////////////////////
    if(mega_details[unixtime1_key] !== undefined){
        if( parseFloat(mega_details[unixtime1_key]['period_checkin_change_over'],10)!==0 ) {
            block_check_in =  parseFloat(mega_details[unixtime1_key]['period_checkin_change_over'],10);
        }
    }else if( parseFloat(checkin_change_over)!==0 ){
        block_check_in =  parseFloat(checkin_change_over,10);
    }
    
    // establish the start day - end day block
    ////////////////////////////////////////////////////////////////////////////   
    if(mega_details[unixtime1_key] !== undefined){
        if( parseFloat(mega_details[unixtime1_key]['period_checkin_checkout_change_over'],10)!==0 ) {
            block_check_in_check_out =  parseFloat(mega_details[unixtime1_key]['period_checkin_checkout_change_over'],10);
        }
    }else if( parseFloat(checkin_checkout_change_over)!==-1 ){
        block_check_in_check_out =  parseFloat(checkin_checkout_change_over,10);
    }

    
   
    
    //  if( booking_array.indexOf(unixtime1) > -1 ){
    if( booking_array[unixtime1] != undefined){
        end_reservation=1;
        
        if(start_reservation==1){
            reservation_class=' start_reservation';
            start_reservation=0;
            return [true,"wpestate_calendar calendar-reserved"+reservation_class, control_vars.unavailable_check]; 
        }
        
      
        return [false,"wpestate_calendar calendar-reserved"+reservation_class,  control_vars.unavailable]; 
    
    }else{
       
        start_reservation=1;
        if(end_reservation===1){
            reservation_class=' end_reservation';
            end_reservation=0;
           
        }
        if(week_day !== block_check_in_check_out && block_check_in_check_out!==0 && unixtime1_key > (today-24*60*60) ){ 
      
            if(reservation_class !== ' end_reservation'){
                reservation_class=' check_in_block 1';
            }
            return [
                false,
                "wpestate_calendar "+reservation_class+" date"+unixtime1_key, 
                wpestate_booking_calendat_get_price(unixtime1_key,display_price) 
            ]; 
        
        //Check in/Check out  only on '+weekdays[block_check_in]
        }else if(week_day !== block_check_in && block_check_in!==0 && from_who ==='start_date' && unixtime1_key > (today-24*60*60) ){ 
   
            if(reservation_class !== ' end_reservation'){
                reservation_class=' check_in_block 2';
            }
            return [
                false,
                "wpestate_calendar "+reservation_class+" date"+unixtime1_key, 
                wpestate_booking_calendat_get_price(unixtime1_key,display_price) 
            ];

        }  
        
        return [
            true, 
            "freetobook wpestate_calendar"+reservation_class+" date"+unixtime1_key+" "+from_css_class, 
            wpestate_booking_calendat_get_price(unixtime1_key,display_price) 
            ];              
    } 

 


}




function show_booking_costs() {
        var guest_fromone, guest_no, fromdate, todate, property_id, ajaxurl;
        ajaxurl             =   control_vars.admin_url + 'admin-ajax.php';
        property_id         =   jQuery("#listing_edit").val();
        
        fromdate            =   jQuery("#start_date").val();
        fromdate            =   wpestate_convert_selected_days(fromdate);
        
        todate              =   jQuery("#end_date").val();
        todate              =   wpestate_convert_selected_days(todate);
        

        
        
        guest_no            =   parseInt( jQuery('#booking_guest_no_wrapper').attr('data-value'),10);
        
        jQuery('.cost_row_extra input').each(function(){
            jQuery(this).prop("checked", false);
        });
                
                
        if (fromdate === '' || todate === '') {
            jQuery('#show_cost_form').remove();
            return;
        }

       
        guest_fromone       =   parseInt( jQuery('#submit_booking_front').attr('data-guestfromone'),10);  
        if (document.getElementById('submit_booking_front_instant')) {
            guest_fromone       =   parseInt( jQuery('#submit_booking_front_instant').attr('data-guestfromone'),10);  
        }
         
         
         
        if( isNaN(guest_fromone) ){
            guest_fromone=0;
        } 
              
        if(isNaN(guest_no)){
            guest_no=0;
        }
 
              
        if(guest_fromone===1 && guest_no<1 ){
            return;
        }
        
         
        jQuery('#booking_form_request_mess').empty().hide();
        if(fromdate>todate && todate!=='' ){
            jQuery('#booking_form_request_mess').show().empty().append(property_vars.nostart);
            jQuery('#show_cost_form').remove(); 
            return;
        }
        
        if(todate=='Invalid date'){
            return;
        }
        

       var nonce = jQuery('#wprentals_add_booking').val();
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                'action'            :   'wpestate_ajax_show_booking_costs',
                'fromdate'          :   fromdate,
                'todate'            :   todate,
                'property_id'       :   property_id,
                'guest_no'          :   guest_no,
                'guest_fromone'     :   guest_fromone,
                'security'          :   nonce
            },
            success: function (data) {
            
                jQuery('#show_cost_form,.cost_row_instant').remove();
                jQuery('#add_costs_here').before(data);
                wpestate_redo_listing_sidebar();
            },
            error: function (errorThrown) {
             
            }
        });
    }
   



jQuery(document).ready(function ($) {
    "use strict";
    
   

   
     
     
    wpestate_redo_listing_sidebar();
    var today, booking_error;
    booking_error = 0;
    today = new Date();
    wpestare_booking_retrive_cookies();

    if( $('#listing_description').outerHeight() > 169 ){
        $('#view_more_desc').show();
    }
    
    //180
    var sidebar_padding=0;
    
    $('#view_more_desc').on('click',function(event){
       
        
        var new_margin = 0;
        if( $(this).hasClass('lessismore') ){
         
            $(this).text(property_vars.viewmore).removeClass('lessismore');
          
            $('#listing_description .panel-body').removeAttr('style');
            $('#listing_description .panel-body').css('max-height','129px');
            $('#listing_description .panel-body').css('overflow','hidden');
          
            if ( !jQuery('#primary').hasClass('listing_type_1') ){
                $('#primary').css('margin-top',sidebar_padding);
            }
           
           
        }else{
            sidebar_padding=$('.listingsidebar').css('margin-top');
            
            $(this).text(property_vars.viewless).addClass('lessismore');
            $('#listing_description .panel-body').css('max-height','100%');
            $('#listing_description .panel-body').css('overflow','initial');
            
            if ( !jQuery('#primary').hasClass('listing_type_1') ){
                new_margin = $('.property_header').outerHeight() - 390;
                new_margin = 180-new_margin;

                if(new_margin <180){
                    $('#primary').css('margin-top',new_margin+'px');
                }else{
                    $('#primary').css('margin-top','0px');
                }
            }
          
        }
        
        
    });
    
    
    ////////////////////////////////////////////////////////////////////////////
    /// tooltip property
    ////////////////////////////////////////////////////////////////////////////     
    $('#listing_main_image_photo').bind('mousemove', function (e) {
        $('#tooltip-pic').css({'top': e.pageY, 'left': e.pageX, 'z-index': '1'});
    });
    setTimeout(function () {
        $('#tooltip-pic').fadeOut("fast");
    }, 10000);
    /////////////////////////////////////////////////////////////////////////////////////////
    // booking form calendars
    /////////////////////////////////////////////////////////////////////////////////////////

    
    wprentals_show_booking_calendars('start_date', 'end_date'); //booking form search
    
    wprentals_show_static_calendar();
  
    

    $('#end_date').change(function () {
        var prop_id=jQuery('#listing_edit').val();
        wpestate_setCookie('booking_prop_id_cookie',  prop_id , 1);
        wpestate_setCookie('booking_end_date_cookie',  $('#end_date').val() , 1);
        booking_started=1;
        show_booking_costs();
    });
    
    $('#start_date').change(function () {
        var prop_id=jQuery('#listing_edit').val();
        wpestate_setCookie('booking_prop_id_cookie',  prop_id , 1);
        wpestate_setCookie('booking_start_date_cookie',  $('#start_date').val() , 1);
        if( booking_started===1){
            show_booking_costs();
        }
    });
    
    $('#booking_guest_no_wrapper_list li').on('click',function (){
        var prop_id=jQuery('#listing_edit').val();
        wpestate_setCookie('booking_prop_id_cookie',  prop_id , 1);
        var booking_guest_no    =   parseInt( jQuery('#booking_guest_no_wrapper').attr('data-value') ); 
        wpestate_setCookie('booking_guest_cookie',  booking_guest_no , 1);
        if( booking_started===1){
            show_booking_costs();
        }
    });
    
    
    function wpestare_booking_retrive_cookies(){
        var booking_guest_cookie        =   wpestate_getCookie( "booking_guest_cookie");
        var booking_start_date_cookie   =   wpestate_getCookie("booking_start_date_cookie");
        var booking_end_date_cookie     =   wpestate_getCookie("booking_end_date_cookie");
        var booking_prop_id             =   wpestate_getCookie("booking_prop_id_cookie");
        var prop_id                     =   jQuery('#listing_edit').val();


        if ( prop_id === booking_prop_id &&  property_vars.logged_in==="yes" ){
            if(booking_start_date_cookie!==''){
                jQuery('#start_date').val(booking_start_date_cookie);
            }

            if(booking_end_date_cookie!==''){
                jQuery('#end_date').val(booking_end_date_cookie);
            }

            if(booking_guest_cookie!==''){
                jQuery('#booking_guest_no_wrapper').attr('data-value',booking_guest_cookie);
                jQuery('#booking_guest_no_wrapper .text_selection').html(booking_guest_cookie+' '+property_vars.guests);
            }


            if(booking_start_date_cookie!=='' && booking_end_date_cookie!=='' && booking_guest_cookie!==''){
                booking_started=1;
                show_booking_costs();
            
                // jQuery('#booking_guest_no_wrapper_list li').trigger('click');
            }

        }


    }




    
    $('#booking_form_request li').on('click',function (event){
        event.preventDefault();
        var guest_fromone, guest_overload;
        
        guest_overload      =   parseInt(jQuery('#submit_booking_front').attr('data-overload'),10);
        guest_fromone       =   parseInt( jQuery('#submit_booking_front').attr('data-guestfromone'),10);
        if (document.getElementById('submit_booking_front_instant')) {
            guest_overload      =   parseInt(jQuery('#submit_booking_front_instant').attr('data-overload'),10);
            guest_fromone       =   parseInt( jQuery('#submit_booking_front_instant').attr('data-guestfromone'),10);
        }
        
        if( ( guest_overload===1 &&  booking_started===1) || guest_fromone===1 ){
            show_booking_costs();
        }
    });
    
    

    $("#start_date,#end_date").change(function (event) {
       // $(this).parent().removeClass('calendar_icon');
    });

    /////////////////////////////////////////////////////////////////////////////////////////
    // contact host
    /////////////////////////////////////////////////////////////////////////////////////////
    function wpestate_show_contact_owner_form(booking_id, agent_id) {
        var  ajaxurl;
        ajaxurl     =   ajaxcalls_vars.admin_url + 'admin-ajax.php';
        
        
        jQuery('#contact_owner_modal').modal();
        enable_actions_modal_contact();
    
    }


    $('#contact_host,#contact_me_long,.contact_owner_reservation').on('click',function () {
        var booking_id, agent_id,property_id;

        agent_id    =   0;
        booking_id  =   $(this).attr('data-postid');
        property_id =   $(this).attr('data-bookid');
        $('#submit_mess_front').attr('data-property_id',property_id);
        wpestate_show_contact_owner_form(booking_id, agent_id);
    });

    $('#contact_me_long_owner').on('click',function () {
        var agent_id, booking_id;
        booking_id =   0;
        agent_id  =   $(this).attr('data-postid');
        wpestate_show_contact_owner_form(booking_id, agent_id);
    });
    
    
    

    function enable_actions_modal_contact() {
        jQuery('#contact_owner_modal').on('hidden.bs.modal', function (e) {
            jQuery('#contact_owner_modal').hide();
        });
        var today =new Date().getTime();

        $("#booking_from_date").datepicker({
          //  dateFormat : "yy-mm-dd",
            minDate: today
        }, jQuery.datepicker.regional[control_vars.datepick_lang]);

        $("#booking_from_date").change(function () {
            var  prev_date = new Date($('#booking_from_date').val());
            var read_in_date = $("#booking_from_date").val();
            prev_date = wpestate_convert_selected_days_simple_add_days(read_in_date,1);

                  
            jQuery("#booking_to_date").datepicker("destroy");
            jQuery("#booking_to_date").datepicker({
                minDate: prev_date
            }, jQuery.datepicker.regional[control_vars.datepick_lang]);
        });
        
        $("#booking_from_date").datepicker('widget').wrap('<div class="ll-skin-melon"/>'); 
 
        $("#booking_to_date").datepicker({
         //   dateFormat : "yy-mm-dd",
            minDate: today
        }, jQuery.datepicker.regional[control_vars.datepick_lang]);

        $("#booking_to_date").datepicker('widget').wrap('<div class="ll-skin-melon"/>'); 

        $("#booking_from_date,#booking_to_date").change(function (event) {
           // $(this).parent().removeClass('calendar_icon');
        });

        $('#submit_mess_front').on('click',function (event) {
            event.preventDefault();
            var ajaxurl, subject, booking_from_date, booking_to_date, booking_guest_no, message, nonce, agent_property_id, agent_id;
            ajaxurl              =   control_vars.admin_url + 'admin-ajax.php';
            booking_from_date       =   $("#booking_from_date").val();
            booking_to_date         =   $("#booking_to_date").val();
            booking_guest_no        =   $("#booking_guest_no").val();
            message                 =   $("#booking_mes_mess").val();
            agent_property_id       =   $("#agent_property_id").val();
            agent_id                =   $('#agent_id').val();
            nonce                   =   $("#security-register-mess-front").val();

            var contact_u_email     =   $("#contact_u_email").val();
            var contact_u_name      =   $("#contact_u_name").val();

            if (subject === '' || message === '') {
                $("#booking_form_request_mess_modal").empty().append(property_vars.plsfill);
                return;
            }
            if( property_vars.logged_in!=="yes" && ( contact_u_email === '' || contact_u_name === '')) {
                $("#booking_form_request_mess_modal").empty().append(property_vars.plsfill);
                return;
            }

  
            if(agent_property_id==0 && jQuery('.contact_owner_reservation').length>0 ){
                agent_property_id=$(this).attr('data-property_id');
                agent_id=0;
            }
         
       
            var nonce = jQuery('#wprentals_submit_mess_front_nonce').val();
            
            if(property_vars.use_gdpr==='yes'){
            if ( !$('#wpestate_agree_gdpr').is(':checked') ){
                $("#booking_form_request_mess_modal").empty().append(property_vars.gdpr_terms);
                return;
            }
        }
        
        
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    'action'            :   'wpestate_mess_front_end',
                    'message'           :   message,
                    'booking_guest_no'  :   booking_guest_no,
                    'booking_from_date' :   booking_from_date,
                    'booking_to_date'   :   booking_to_date,
                    'agent_property_id' :   agent_property_id,
                    'agent_id'          :   agent_id,
                    'contact_u_email'   :   contact_u_email,
                    'contact_u_name'    :   contact_u_name,
                    'security'          :   nonce
                },
                success: function (data) {
                 
                    $("#booking_form_request_mess_modal").empty().append(data);
                    setTimeout(function () {
                        $('#contact_owner_modal').modal('hide');
                      
                            // reset contact form
                            $("#booking_form_request_mess_modal").empty();
                            $("#contact_u_email").val('');
                            $("#contact_u_name").val('');
                            $("#booking_from_date").val('');
                            $("#booking_to_date").val('');
                            $("#booking_guest_no").val('1');
                            $("#booking_mes_mess").val('');
                            $('#submit_mess_front').unbind('click');
                    
                    
                    }, 2000);
                    
                  
                    
                },
                error: function (errorThrown) {
                  
                }

            });
        });
    }


    /////////////////////////////////////////////////////////////////////////////////////////
    // extra expenses front
    /////////////////////////////////////////////////////////////////////////////////////////

    $('.cost_row_extra input').on('click',function(){
        var key_to_add,row_to_add,total_value,value_to_add,value_how,value_name,parent,fromdate,todate,listing_edit,booking_guest_no,cost_value_show;
          
        parent= $(this).parent().parent();
        value_to_add    =   parseFloat( parent.attr('data-value_add') );
        value_to_add    =   parseFloat( wpestate_booking_form_currency_convert(value_to_add) );
              
        value_how           =   parseFloat ( parent.attr('data-value_how') );
        value_name          =   parent.attr('data-value_name');
        key_to_add          =   jQuery(this).attr('data-key');
        fromdate            =   wpestate_convert_selected_days( jQuery("#start_date").val() );
        todate              =   wpestate_convert_selected_days( jQuery("#end_date").val() );
        listing_edit        =   jQuery('#listing_edit').val();
        booking_guest_no    =   parseInt( jQuery('#booking_guest_no_wrapper').attr('data-value') ); 
        cost_value_show     =   parent.find('.cost_value_show').text();
        var firstDate   =   new Date(fromdate);
        var secondDate  =   new Date(todate);
        var oneDay      =   24*60*60*1000;
        if(property_vars.book_type==='2'){
            oneDay=60*60*1000; // one hour
        }
        
        var diffDays    =   Math.round(Math.abs((firstDate.getTime() - secondDate.getTime())/(oneDay)));
        var total_curent    =   parseFloat( $('#total_cost_row .cost_value').attr('data_total_price') );
        total_curent        =   parseFloat( wpestate_booking_form_currency_convert(total_curent) );
        if(booking_guest_no === 0 || isNaN(booking_guest_no)){
            booking_guest_no=1;
        }
        
  
        
        
        if( ($(this).is(":checked")) ){
            
            if(value_how===0){
                total_value = value_to_add;
            }else if( value_how === 1 ){
                total_value = value_to_add * diffDays;
            }else if( value_how === 2 ){
                total_value = value_to_add * booking_guest_no;
            }else if( value_how === 3 ){
                total_value = value_to_add * diffDays*booking_guest_no;
            }
            
           
            row_to_add='<div class="cost_row" id="'+estate_makeSafeForCSS(value_name)+'" data-added="'+total_value+'"><div class="cost_explanation">'+value_name+'</div><div class="cost_value">'+estate_format_number_with_currency( total_value.toFixed(2) )+'</div></div>';
            $('#total_cost_row').before(row_to_add);
            
            var new_early_bird_before_convert;
            var new_early_bird  =   parseFloat( $('#early_bird_discount').attr('data-early-bird') );
            new_early_bird      =   parseFloat( wpestate_booking_form_currency_convert(new_early_bird) );
             
            if( isNaN(new_early_bird) ||new_early_bird === 0){
                new_early_bird=0;
            }
            new_early_bird.toFixed(2);
            
              
            
            
          
            total_curent    =   total_curent    +   new_early_bird;
            total_curent    =   total_curent    +   total_value;
            if(new_early_bird !==0){
                new_early_bird  =   new_early_bird  +    total_value * property_vars.early_discount/100;
              
                var new_early_bird_before_convert =  wpestate_booking_form_currency_convert_back(new_early_bird);
                new_early_bird.toFixed(2);
            }
            
          
            total_curent    =   total_curent    -   new_early_bird;
            total_curent    =   total_curent.toFixed(2);
           
            var  total_curent_deposit=total_curent;
                           
            if(control_vars.include_expeses==='no'){
               
                
                var cleaning_fee=parseFloat ( $('.cleaning_fee_value').attr('data_cleaning_fee') );
                cleaning_fee.toFixed(2);
                var city_fee=parseFloat ( $('.city_fee_value').attr('data_city_fee') );
                city_fee.toFixed(2);
                
                if(isNaN(city_fee)){
                    city_fee=0;
                }
                if(isNaN(cleaning_fee)){
                    cleaning_fee=0;
                }
                
                total_curent_deposit=total_curent_deposit-cleaning_fee-city_fee;
                total_curent_deposit.toFixed(2);
            }
                   
                     
            
            $('#total_cost_row .cost_value').text( estate_format_number_with_currency( total_curent ) );
            var total_curent_before_convert = wpestate_booking_form_currency_convert_back(total_curent);
                          
            $('#total_cost_row .cost_value').attr('data_total_price',total_curent_before_convert);                 
            var new_depozit =   wpestate_instant_book_depozit(total_curent_deposit);
            var new_balance =   total_curent-new_depozit;           
            $('.instant_depozit_value').text(estate_format_number_with_currency( new_depozit.toFixed(2) ) );
            $('.instant_balance_value').text(estate_format_number_with_currency( new_balance.toFixed(2) ) );
            $('#early_bird_discount').text(estate_format_number_with_currency( new_early_bird.toFixed(2) ) );
            $('#early_bird_discount').attr('data-early-bird',new_early_bird_before_convert);
         
         
        } else{
            value_name           =  estate_makeSafeForCSS(value_name);
            var remove_row_value =  parseFloat( $('#'+value_name).attr('data-added') );
           
              
            $('#'+value_name).remove();
            
            var new_early_bird =   parseFloat( $('#early_bird_discount').attr('data-early-bird') );
            if( isNaN(new_early_bird) ||new_early_bird === 0){
                new_early_bird=0;
            }
            var new_early_bird_before_convert =  wpestate_booking_form_currency_convert(new_early_bird);
            new_early_bird.toFixed(2);

            total_curent    =   total_curent    +   new_early_bird_before_convert; 
            total_curent    =   total_curent    -   remove_row_value;
            
            if(new_early_bird !==0){
                new_early_bird  =   new_early_bird_before_convert  -   remove_row_value * property_vars.early_discount/100;
                new_early_bird_before_convert =  wpestate_booking_form_currency_convert_back(new_early_bird);
                new_early_bird.toFixed(2);
             
            }
            
          
            total_curent    =   total_curent    -   new_early_bird;
           
             
            total_curent = total_curent.toFixed(2);
            
             var  total_curent_deposit=total_curent;
            if(control_vars.include_expeses==='no'){
                var cleaning_fee=parseFloat ( $('.cleaning_fee_value').attr('data_cleaning_fee') );
                cleaning_fee = isNaN(cleaning_fee) ? 0 : cleaning_fee; 
                var city_fee=parseFloat ( $('.city_fee_value').attr('data_city_fee') );
                city_fee = isNaN(city_fee) ? 0 : city_fee; 
                total_curent_deposit=total_curent_deposit-cleaning_fee-city_fee;
                total_curent_deposit.toFixed(2);
            }
            
            
            $('#total_cost_row .cost_value').text( estate_format_number_with_currency (total_curent) );
            var total_curent_before_convert = wpestate_booking_form_currency_convert_back(total_curent);
            $('#total_cost_row .cost_value').attr('data_total_price',total_curent_before_convert);
            
            var new_depozit =   wpestate_instant_book_depozit(total_curent_deposit);
            var new_balance =   total_curent-new_depozit;
             
            $('.instant_depozit_value').text(estate_format_number_with_currency(new_depozit) );
            $('.instant_balance_value').text(estate_format_number_with_currency(new_balance) );
            $('#early_bird_discount').text(estate_format_number_with_currency(new_early_bird) );
            $('#early_bird_discount').attr('data-early-bird',new_early_bird_before_convert);
        }
        wpestate_redo_listing_sidebar();
        
    });


    function wpestate_instant_book_depozit(total_price){
        var deposit=0;
       
    
        if (  control_vars.wp_estate_book_down_fixed_fee === '0') {

            if(control_vars.wp_estate_book_down === '' || control_vars.wp_estate_book_down === '0'){
                deposit                =   0;
            }else{
                deposit                =  control_vars.wp_estate_book_down*total_price/100;
            }
        }else{
            deposit = control_vars.wp_estate_book_down_fixed_fee;
        }
        return deposit;
       
    }



    
function wpestate_booking_form_currency_convert(display_price){
    var return_price;
    return_price ='';
  
    
    if (!isNaN(my_custom_curr_pos) && my_custom_curr_pos !== -1) { // if we have custom curency
        return_price =     ( display_price * my_custom_curr_coef) ;
        return return_price;
    }else{
        return display_price;
    }   
     
        

}

    
function wpestate_booking_form_currency_convert_back(display_price){
    var return_price;
    return_price ='';
 
    if (!isNaN(my_custom_curr_pos) && my_custom_curr_pos !== -1) { // if we have custom curency
        return_price =     ( display_price / my_custom_curr_coef) ;
        return return_price;
    }else{
        return display_price;
    }   
     
        

}






    /////////////////////////////////////////////////////////////////////////////////////////
    // submit booking front
    /////////////////////////////////////////////////////////////////////////////////////////
    $('#submit_booking_front,#submit_booking_front_instant').on('click',function (event) {
        event.preventDefault();
        var scroll_val =$('#booking_form_request').offset().top -100;
        $("html, body").animate({ scrollTop: scroll_val}, 400);  
    
        if(property_vars.logged_in==="no"){
            $('#booking_form_request_mess').show().empty().addClass('book_not_available').append(property_vars.notlog);
          
        }
        
        var guest_number, guest_overload,guestfromone,max_guest;
        if (!check_booking_form()  || booking_error === 1) {
            return;
        }
        
        guest_number = jQuery('#booking_guest_no_wrapper').attr('data-value');
        guest_number = parseInt(guest_number,10);
        
        if (isNaN(guest_number)){
            guest_number=0;
        }
       
       
        if(property_vars.rental_type==='1'){
            guest_number=1;
        }
        
        max_guest       =   parseInt  (jQuery('#submit_booking_front').attr('data-maxguest'),10);
        guest_overload  =   parseInt  (jQuery('#submit_booking_front').attr('data-overload'),10);
        guestfromone    =   parseInt  (jQuery('#submit_booking_front').attr('data-guestfromone'),10);
        
        
        if (document.getElementById('submit_booking_front_instant')) {
            max_guest       =   parseInt  (jQuery('#submit_booking_front_instant').attr('data-maxguest'),10);
            guest_overload  =   parseInt  (jQuery('#submit_booking_front_instant').attr('data-overload'),10);
            guestfromone    =   parseInt  (jQuery('#submit_booking_front_instant').attr('data-guestfromone'),10);
        }
        
        
        if (guestfromone===1 && guest_number < 1){
            $('#booking_form_request_mess').show().empty().addClass('book_not_available').append(property_vars.noguest);
            return;
        }
       
       
        if(guest_number < 1){
            $('#booking_form_request_mess').show().empty().addClass('book_not_available').append(property_vars.noguest);
            return;
        }
        
        if(guest_overload===0 && guest_number>max_guest){
            $('#booking_form_request_mess').show().empty().addClass('book_not_available').append(property_vars.guestoverload+max_guest+' '+property_vars.guests);
            return;
        }
   
     
        
        if(property_vars.logged_in==="no"){
            $('#booking_form_request_mess').show().empty().addClass('book_not_available').append(property_vars.notlog);
            login_modal_type=3;
            wpestate_show_login_form(1, 0, 0);
         
        }else{
            $('#booking_form_request_mess').show().empty().removeClass('book_not_available').append(property_vars.sending);
            wpestate_redo_listing_sidebar();
            wprentals_check_booking_valability();
        }

    });


    function check_booking_form() {
        var book_from, book_to;
        book_from         =   $("#start_date").val();
        book_to           =   $("#end_date").val();
  
        if (book_from === '' || book_to === '') {
            $('#booking_form_request_mess').empty().addClass('book_not_available').show().append(property_vars.plsfill);
            return false;
        } else {
            return true;
        }
    }
    
   
       
   


/// end jquery
});


function estate_format_number_with_currency(number){
   
    if (!isNaN(my_custom_curr_pos) && my_custom_curr_pos !== -1){
        if (my_custom_curr_cur_post === 'before') {    
            return  ( my_custom_curr_symbol2 ) +" "+number;
        }else{
            return number+" "+ ( my_custom_curr_symbol2 );
        }
    }else{
        if( control_vars.where_curency==='before'){
            return control_vars.curency+" "+number;
        }else{
            return number+" "+control_vars.curency;
        }   
    }
    
      
}


function estate_makeSafeForCSS(name) {
    return name.replace(/[^a-z0-9]/g, function(s) {
        var c = s.charCodeAt(0);
        if (c == 32) return '-';
        if (c >= 65 && c <= 90) return '_' + s.toLowerCase();
        return '__' + ('000' + c.toString(16)).slice(-4);
    });
}

function wpestate_setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function wpestate_getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}






function wprentals_show_static_calendar(){
    if( jQuery("#all-front-calendars_per_hour").length >0){
        var  today = new Date();
        var start_temp='';
        var listing_book_dates=[];

        for (var key in booking_array) {
            if (booking_array.hasOwnProperty(key) && key!=='') {
                var temp_book=[];
                temp_book['title']     =   property_vars.reserved;
                temp_book ['start']    =   moment.unix(key).utc().format();
                temp_book ['end']      =   moment.unix( booking_array[key]).utc().format();
                temp_book ['editable'] =   false;
                listing_book_dates.push(temp_book);
            }
        }

        jQuery("#all-front-calendars_per_hour").fullCalendar({
            defaultView: 'agendaWeek',
            navLinks: false,
            defaultDate: today,   
            selectable:false,
            selectHelper:true,
            selectOverlap :false,
            footer:false,
            slotDuration:'01:00:00',
            allDayText:'hours',
            weekNumbers: false,
            weekNumbersWithinDays: true,
            weekNumberCalculation: 'ISO',
            editable: false,
            eventLimit: true,
            unselectAuto:false,
            isRTL:control_vars.rtl_book_hours_calendar,
            header: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },

            minTime:control_vars.booking_start_hour,
            maxTime:control_vars.booking_end_hour,
            events: listing_book_dates
        });
    }
}

function check_in_out_enable_calendar_per_hour(in_date, out_date){
    "use strict";
    //wprentals_is_per_hour

    jQuery("#" + out_date).removeAttr('disabled');
    jQuery("#" + in_date).on('click',function(){
        jQuery('#book_per_hour_wrapper').show();
        wprentals_show_Calendar(in_date, out_date);
        jQuery('#book_per_hour_calendar').fullCalendar('render');
    });
    
    jQuery("#" + out_date).on('click',function(){
        jQuery('#book_per_hour_wrapper').show();
               wprentals_show_Calendar(in_date, out_date);
        jQuery('#book_per_hour_calendar').fullCalendar('render');
    });
    
    jQuery('#book_per_hour_close').on('click',function(){
        jQuery('#book_per_hour_wrapper').hide();
    });
    
     jQuery('#per_hour_ok').on('click',function(){
        jQuery('#book_per_hour_wrapper').hide();
        show_booking_costs();
    });

    
    jQuery('#per_hour_cancel').on('click',function(){
        jQuery('#book_per_hour_wrapper').hide();
        jQuery("#" + in_date).val('');
        jQuery("#" + out_date).val('');
    });
    
    
    var today, prev_date,read_in_date;
    jQuery("#" + in_date+',#'+out_date).blur();

          

}

window.wprentals_mobilecheck = function() {
  var check = false;
  (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
  return check;
};

function wprentals_show_Calendar(in_date,out_date){
    var  today = new Date();
    var start_temp='';
    var listing_book_dates=[];

    for (var key in booking_array) {
        if (booking_array.hasOwnProperty(key) && key!=='') {
            var temp_book=[];
            temp_book['title']     =   property_vars.reserved;
            temp_book ['start']    =   moment.unix(key).utc().format(),
            temp_book ['end']      =   moment.unix( booking_array[key]).utc().format(),
            temp_book ['editable'] =   false;
            listing_book_dates.push(temp_book);
        }
    }

 
    //minTime, maxTime ,  isRTL: true .   'locale':control_vars.datepick_lang,
    jQuery("#book_per_hour_calendar").fullCalendar({
        defaultView: 'agendaWeek',
        defaultDate: today,   
        selectable:true,
        selectHelper:true,
        selectOverlap :false,
        footer:false,
        slotDuration:'01:00:00',
        validRange: {
            start: today,
            end: '2025-06-01'
        },
        allDayText:'hours',
        allDay :false,
        forceEventDuration:true,
        defaultTimedEventDuration:'05:00:00',
        navLinks: false,
        weekNumbers: false,
        weekNumbersWithinDays: false,
        weekNumberCalculation: 'ISO',
        editable: true,
        eventLimit: true,
        unselectAuto:false,
        nowIndicator :true,
        defaultEventMinutes :200,
        isRTL:control_vars.rtl_book_hours_calendar,
        longPressDelay :100,
        eventLongPressDelay:100,
        selectLongPressDelay:100,
        header: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        },
        
        minTime:control_vars.booking_start_hour,
        maxTime:control_vars.booking_end_hour,

     

        


        dayRender:function( date, cell ) { 
     
            if (date.isSame(today, "day")) {
                cell.attr("xday", "meday");
            }
        },
      
  
 
        dayClick: function(date, jsEvent, view) {
        
         },
        select:function( start, end, jsEvent, view ){
            end             =   moment(end);
            var min_hours   =   parseFloat( control_vars.min_days_booking);
            
            var start_of_day=( moment(start).startOf('day') )/1000;
            if( mega_details[start_of_day] !== undefined){
                min_hours=  mega_details[start_of_day]['period_min_days_booking'];
            }
            
            var should_end  =   moment(start).add(min_hours, 'hours');
            
            
            
            
            if(should_end>end){
               end = should_end;
            }
            
            jQuery('#book_per_hour_calendar').fullCalendar('unselect');
            jQuery('#book_per_hour_calendar').fullCalendar('removeEvents','rentals_custom_book_initial');
            
            jQuery('#book_per_hour_calendar').fullCalendar('renderEvent',{  
                id:'rentals_custom_book_initial',
                start: start,
                end: end,
                allDay: false,
                editable:false,
                },
                true // stick the event
           );
         
    
        
            jQuery('#book_per_hour_calendar').fullCalendar('removeEvents','rentals_custom_book');
            var date_format = control_vars.date_format.toUpperCase()+" HH:mm";
            var new_date_format=date_format.replace("YY", "YYYY");  
   
            jQuery("#" + in_date).val(start.format(new_date_format));
            jQuery("#" + out_date).val(end.format(new_date_format));
            jQuery('.fc-center .hour_selection').empty().html(start.format(new_date_format)+' to '+end.format(new_date_format));
            var prop_id=jQuery('#listing_edit').val();
            wpestate_setCookie('booking_prop_id_cookie',  prop_id , 1);
            wpestate_setCookie('booking_start_date_cookie',  start.format(new_date_format) , 1);
            wpestate_setCookie('booking_end_date_cookie', end.format(new_date_format) , 1);
            booking_started=1;
            
            

        },

      
      
        events: listing_book_dates
    });
    
    jQuery('.fc-center .hour_selection').remove();
    jQuery('#book_per_hour .fc-center').append('<div class="hour_selection">click and drag to select the hours</div>');
}