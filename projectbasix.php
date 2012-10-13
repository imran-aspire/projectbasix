<?php
/*
Plugin Name: projectBasix
Plugin URI: http://projectbasix.com
Description: Its a great tool for project management.
Version: 1.0
Author: imran.aspire
Author URI: http://mdimran.net
*/

/*----------------------------------------------------------------------------------------------------
					D E C L E A R I N G     V A R I A B L E S
----------------------------------------------------------------------------------------------------*/

if ( ! defined( 'PBX_WP_CONTENT_URL' ) ) define( 'PBX_WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'PBX_WP_CONTENT_DIR' ) ) define( 'PBX_WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'PBX_WP_PLUGIN_URL'  ) ) define( 'PBX_WP_PLUGIN_URL',  PBX_WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'PBX_WP_PLUGIN_DIR'  ) ) define( 'PBX_WP_PLUGIN_DIR',  PBX_WP_CONTENT_DIR . '/plugins' );
if ( ! defined( 'PBX_MY_PLUGIN_DIR'  ) ) define( 'PBX_MY_PLUGIN_DIR',  PBX_WP_CONTENT_DIR . '/plugins/projectbasix' );




//admin user level
$PBX_plugin_access = 8;  

//error_reporting(0);

//add_action("admin_init","DFF");
function DFF(){
    
global $current_user;
get_currentuserinfo();
print_r($current_user);
}
//including config files
include_once(dirname(__FILE__).'/include/config.php');




//ini_set("output_buffering","on");

/*----------------------------------------------------------------------------------------------------
									A D M I N     M E N U
----------------------------------------------------------------------------------------------------*/


function PBX_admin_menu(){
	
    $mymenu = new PBX_option_list();    
}


add_action('admin_menu', 'PBX_admin_menu');


/**
 *   request handler calss     
 * 
 * 
 * */

class PBX_option_list{
    
    public function __construct(){
        
        $request_handler = array( $this, 'request_handler' );
        global $PBX_plugin_access;
        //add_menu_page(__( 'WpText2Ad'),__( 'WpText2Ad'),$WpText2Ad_plugin_access,'wptext2ad_option',$request_handler);
        //add_submenu_page('wptext2ad_option',  __( 'AD List' ), __( 'AD List' ), $WpText2Ad_plugin_access, 'wptext2ad_adlist', $request_handler );
        
        
    }
 
    /*The request handler function that declares the needed vars and calls
      the router*/
    public function request_handler(){
        
        /*as mentioned, we use the page as the controller*/
        $controller = $_GET['page'];
      
        /*and the action variable for the method*/
       
        $action = $_GET['action'];
 
        // we add a small check to see if the page requested is this
        //  controller
        
        if( $controller == get_class( $this ) )
        {
            // if it is, we can use the instance of this controller instead
            $controller = $this;
        }
 
        // now the params. All the other get variables
        $params = $_GET;
 
        // we can remove the page and action variables first
        unset( $params['page'] );
        unset( $params['action'] );
 
        // finally! let's set up data for the router
        $route = array( $controller, $action, $params );
        
        // we are using the instance of this class as the default controller
        $default_controller = $this;
 
        // the default method - Kohana 2 style!
        $default_method = 'index';
       

        if(is_string($route[0]) && file_exists( MY_PLUGIN_DIR."/modules/".$route[0].".php"))
            include_once("modules/".$route[0].".php");
        
        $router = new WpText2Ad_Router( $route, $default_controller, $default_method );
        
        
    }
    
    /* since this is the default controller,
    we should set up the default method here as well*/
 
    public function index( $args = NULL )
    {
    
    }
     
 
 
}



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ---------------------------------    install the database   ----------------------------------------------------
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////


register_activation_hook( __FILE__, array('projectbasix', 'database_install') );

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ---------------------------------    add script and style   ----------------------------------------------------
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* 
if(isset($_GET['page'])) 
add_action("init","init_pingback_power");
*/
function init_pingback_power() {
    
   wp_enqueue_style('date_all',WP_PLUGIN_URL."/pingbackpower/library/datepicker/base/jquery.ui.all.css" );
    wp_enqueue_style('date_all');

    
      // pingback power script 
    wp_enqueue_script('jquery',WP_PLUGIN_URL."/pingbackpower/js/jquery.js" );
    wp_enqueue_script('jquery');
     
    

    // date picker JC 
    wp_enqueue_script('date_core',WP_PLUGIN_URL."/pingbackpower/library/datepicker/ui/jquery.ui.core.js" );
    wp_enqueue_script('date_core');
   
    wp_enqueue_script('widget',WP_PLUGIN_URL."/pingbackpower/library/datepicker/ui/jquery.ui.widget.js" );
    wp_enqueue_script('widget');
   
   wp_enqueue_script('datepicker',WP_PLUGIN_URL."/pingbackpower/library/datepicker/ui/jquery.ui.datepicker.js" );
    wp_enqueue_script('datepicker');
   
    // date picker CSS 
        
  
    
    wp_enqueue_style('demos',WP_PLUGIN_URL."/pingbackpower/library/datepicker/demos.css" );
    wp_enqueue_style('demos');

    
  
    // pingback power script 
    wp_enqueue_script('script',WP_PLUGIN_URL."/pingbackpower/js/script.js" );
    wp_enqueue_script('script');
    
    // style include 
    wp_enqueue_style('style',WP_PLUGIN_URL."/pingbackpower/style/style.css" );
    wp_enqueue_style('style');
    
    
}



?>