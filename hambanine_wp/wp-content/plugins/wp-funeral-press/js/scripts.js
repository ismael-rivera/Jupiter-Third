function wpfh_dialog(title,div,w,h){
	
	 jQuery(div).dialog({
		 	title: title,
		 	width: w,
            height: h
        });
}

function wpfh_message_button(type) {


    jQuery('.wpfh_message_button').removeClass('selected');
    jQuery('.wpfh_photo_button').removeClass('selected');
    jQuery('.wpfh_youtube_button').removeClass('selected');
    jQuery('.' + type).addClass('selected');
    jQuery('.wpfh_message_form_fields').hide();
    jQuery('.' + type + '_form').show();


    if (type == 'wpfh_message_button') {
        jQuery('#wpfh_mode').val('guestbook');
    }
    if (type == 'wpfh_photo_button') {
        jQuery('#wpfh_mode').val('photo');
    }
    if (type == 'wpfh_youtube_button') {
        jQuery('#wpfh_mode').val('youtube');
    }
}
jQuery(document).ready(function(){
jQuery("#wpfh_tabs").tabs();
});
jQuery(function () {

    
    jQuery('.wpfh_upload_form').bind('submit', function () {

        var mode = jQuery('#wpfh_mode').val();


        if (mode == 'guestbook') {
            if (jQuery('#wpfh_message_textarea').val() == '') {
                alert("Please enter your message");
                return false;
            } else {
                return true;
            }

        }
        if (mode == 'photo') {
            if (jQuery('#wpfh_message_file').val() == '') {
                alert("Please upload a photo");
                return false;
            } else {
                return true;
            }

        }
        if (mode == 'youtube') {


            var matches = jQuery('#wpfh_message_youtube').val().match(/^(https?:\/\/)?([^\/]*\.)?youtube\.com\/watch\?([^]*&)?v=\w+(&[^]*)?/i);

            if (jQuery('#wpfh_message_youtube').val() == '' || matches == null) {
                alert("Please paste a valid youtube link");
                return false;
            } else {
                return true;
            }

        }
        return false;
    });

    jQuery('.wpfh_popup').bind('click', function () {

        window.open(jQuery(this).attr('href'), '1350057650603', 'width=700,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');
        return false;
    });


    jQuery(".datepicker").datepicker({


        dateFormat: "yy-mm-dd",
        changeMonth: true,
        yearRange: '1850:2050',
        changeYear: true

    });



});