<?php

// ----------------------------------------------------------------------------------------------------------------  
// ---------------------------------    add actions for the plugin   ----------------------------------------------
// ----------------------------------------------------------------------------------------------------------------  



 
add_action('init', 'do_output_buffer');
function do_output_buffer() {
     ob_start();
     ini_set('max_execution_time', 300);
}

add_action("wp_head",array("projectbasix","main_dashboard"));

function pbx_mail_content_type(){
    return "text/html";
}
add_filter( 'wp_mail_content_type','pbx_mail_content_type' ); 



    /**
     * wp cron job 
     * */

// send notification hourly 

add_action('sendHourlyNotificationEmailEvent', 'sendHourlyNotificationEmail');
add_action('updateDailyTaskDeadlineNotificationEvent', 'updateDailyTaskDeadlineNotification');

function pbx_schedule_activation() {
	if ( !wp_next_scheduled( 'sendHourlyNotificationEmailEvent' ) ) {
		wp_schedule_event(time(), 'hourly', 'sendHourlyNotificationEmailEvent');
	}
	if ( !wp_next_scheduled( 'updateDailyTaskDeadlineNotificationEvent' ) ) {
		wp_schedule_event(time(), 'twicedaily', 'updateDailyTaskDeadlineNotificationEvent');
	}

    
}

function updateDailyTaskDeadlineNotification(){
    $notification = new notification();
    $notification->taskDeadLine();    
}
function sendHourlyNotificationEmail() {
    $notification = new notification();
    $notification->sendNotification();
}

add_action('wp', 'pbx_schedule_activation');


function my_custom_login_logo() {
    echo '<style type="text/css">
        h1 a { background-image:url('.PBX_WP_PLUGIN_URL.'/projectbasix/logo/logo.png) !important; width:274px;height:64px }
        body{ background-color:#F5F5F5 !important; }
    </style>';
    $projectBasix = new projectbasix();

}

add_action('login_head', 'my_custom_login_logo');




?>