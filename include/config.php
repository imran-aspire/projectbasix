<?php

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ---------------------------------    configuration of the ProjectBasix               ---------------------------
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    // includes required files 
    
    include_once('functions.php');
    include_once('router.php');
    include_once('actions.php');
   

    /**
    *   include the plugin classes 
    * */
    
    
    $classes = scandir(PBX_MY_PLUGIN_DIR."/class/");  
    if($classes){
    for($i=2;$i<count($classes);$i++)
        include(PBX_MY_PLUGIN_DIR."/class/".$classes[$i]);
    
    }
    
    // define db charset
    
    global $client_user;
    $client_user="editor";
    
    
    
?>