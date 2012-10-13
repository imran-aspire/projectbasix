<?php

/**
 * projectbasix CLASS 
 *
 * @link http://www.mdimran.net/
 * @author Imran <imran.aspire@gmail.com>
 *
 */

class projectbasix{
    
    private $wpdb;

   
    public function projectbasix(){
         global $wpdb;
         $this->wpdb=$wpdb;
           
         
    }
    
    /**
    * projectBasix database_install
    * @return bool 'true' | false(if failed)
    */
    function database_install(){
    
        // install the database table 
        
        $project = new project();
        $project->createTable();
        
        $task= new task();
        $task->createTable();
        
        $comment= new comment();
        $comment->createTable();
        
        $file = new file();
        $file->createTable();
        
        $activity=new activity();
        $activity->createTable();
        
        $client = new client();
        $client->createTable();
        
        $company= new company();
        $company->createTable();
        
        $notification = new notification();
        $notification->createTable();
        // add projectBasix Page
        
        $page = get_page_by_title( 'ProjectBasix' );
        if(empty($page)){
            
            // Create page object
            
            $my_post = array(
            'post_title' => 'ProjectBasix', 
            'post_status' => 'publish',
            'post_type' => "page"
            );
            
            // Insert the post into the database
            $projectBasixID = wp_insert_post( $my_post );
            
            add_option("projectBasix_page",$projectBasixID);
        }
        
        
        // make directory  
        
        wp_mkdir_p( ABSPATH . 'wp-content/uploads/' );
        wp_mkdir_p( ABSPATH . 'wp-content/uploads/projectbasix/' );
    
    
    }
    
    /**
     *  projectBasix main dashboard
     *  in the theme template
     * */  
    function main_dashboard(){
        global $current_user;                   
        get_currentuserinfo();
        
        //print_r($current_user);
    
        // main dashboard
        if (is_page("projectbasix")) {
            if(is_user_logged_in())
                projectbasix::showProjectBasixDashboard();
            else{
                
                $projectbasix_link=get_permalink(get_option("projectBasix_page"));
                wp_redirect(wp_login_url( $projectbasix_link ));
               
             }
             exit();
        }

                
        
    }
    
    
    /**
     * show the dashboard
     * */
    
    function showProjectBasixDashboard(){
   
         global $current_user;
         get_currentuserinfo();
         $currentRole=  $current_user->roles[0];
         $projectbasix_link=get_permalink(get_option("projectBasix_page"));
         
        
         
         //=="administrator"
         
          
        if(get_option('permalink_structure')=="")
            $pageLink=$projectbasix_link."&page=";
        else 
            $pageLink=$projectbasix_link."?page=";  
            
         
        self::header();
    ?>


    <!-- Top navigation bar -->
    <?php self::topNavigationBar(); ?>
    
    
    <!-- Header -->
    <div id="header" class="wrapper" style="padding-top:10px;">
        
        <div class="logo">
            <a href="<?php echo get_bloginfo("home"); ?>" title=""><img width="200" height="70" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/logo/logo.png" alt="" /></a></div>
        
        <?php /*
        <div class="middleNav">
        	<ul>
            	<li class="iMes"><a href="#" title=""><span>Support tickets</span></a><span class="numberMiddle">9</span></li>
                <li class="iStat"><a href="" title=""><span>Statistics</span></a></li>
                <li class="iUser"><a href="#" title=""><span>User list</span></a></li>
                <li class="iOrders"><a href="#" title=""><span>Billing panel</span></a></li>
            </ul>
        </div>
        <div class="fix"></div>
        */
         if($currentRole=="subscriber"){
            $result= self::getCompanyName($current_user->ID);
            echo    '<div class="companyTitle"><h1>'.$result->name.'</h1><br /><small>'.$result->head.'</small>
                        <div class="fix"></div></div> 
                        '; 
         }
        ?>
        
        <input type="hidden" id="pluginurl" value="<?php echo PBX_WP_PLUGIN_URL; ?>" />
    </div>
    
    <!-- Content wrapper -->
    <div class="wrapper">
    	
    	<!-- Left navigation -->
        <div class="leftNav">
        	<ul id="menu">
            	<li class="dash"><a href="<?php echo $projectbasix_link; ?>" title="" rel="dashboard" <?php if($_GET['page']=="") echo 'class="active"'; ?> ><span>Dashboard</span></a></li>
                
                <?php if($currentRole=="administrator"){ ?>
                    <li class="login"><a  href="<?php echo $pageLink; ?>manager"  title="" rel="manager" <?php if($_GET['page']=="manager") echo 'class="active"'; ?> ><span>Manager</span></a></li>
                <?php } ?>
                
                <?php if($currentRole=="administrator" || $currentRole=="editor"){ ?>
                <li class="contacts"><a  href="<?php echo $pageLink; ?>contact"  title="" rel="manager" <?php if($_GET['page']=="contact") echo 'class="active"'; ?>><span>Contact List</span></a></li>
                <?php } ?>
                
                <?php if($currentRole=="administrator" || $currentRole=="editor"){ ?>
                <li class="companies"><a  href="<?php echo $pageLink; ?>company"  title="" rel="manager" <?php if($_GET['page']=="company") echo 'class="active"'; ?> ><span>Company</span></a></li>
                <?php } ?>
                
                <li class="suitcase"><a  rel="project" href="<?php echo $pageLink; ?>project" title="" <?php if($_GET['page']=="project") echo 'class="active"'; ?> ><span>Projects</span></a></li>
                <li class="typo"><a href="<?php echo $pageLink; ?>task"  rel="task"   title="" <?php if($_GET['page']=="task") echo 'class="active"'; ?> ><span>Tasks</span></a></li>
                <li class="mail"><a href="<?php echo $pageLink; ?>message" rel="reports"  class="sidebarlink <?php if($_GET['page']=="message") echo ' active'; ?>"     ><span>Message</span></a></li>
                <li class="graphs"><a  rel="reports"  class="exp" id="reportTab"  title="" <?php if($_GET['page']=="contact") echo 'class="active"'; ?> href="javascript:void(0)" ><span>Reports</span></a>
                    <ul class="sub" id="reportTabSub">
                        <li><a href="<?php echo $pageLink; ?>report" title="">Company Wise Report</a></li>
                        <li><a href="<?php echo $pageLink; ?>report&tab=client" title="">Client Wise Report</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        
       <!-- Content -->
       <div class="content">
            <div class="nNote nSuccess hideit">
            </div>
            <div class="nNote nFailure hideit">
            </div>
            <?php /*
            <div id="dashboardContent" class="pageContent2" style="display: block;">
                <div class="title"><h5>Dashboard</h5></div>
            </div>
            */
            ?>
            <!--  profile  Content -->
            <?php if($_GET['page']=="settings"){  ?>
                <div id="projectContent" class="pageContent2">
                    <div class="title"><h5>User Profile</h5></div>
                        <?php
                            $settings = new settings();
                            $settings->showUserSettingsForm();  
                        ?>
                </div>
               
            <!--  manager  Content -->
            <?php }else if($_GET['page']=="manager"){  ?>
                <div id="projectContent" class="pageContent2">
                    <div class="title"><h5 class="">Manager</h5></div>
                        <?php
                            $manager =new manager();
                            $manager->index();
                        ?>
                </div>
               
            
           <!--  contact list  Content -->
            <?php }else if($_GET['page']=="contact"){  ?>
                <div id="projectContent" class="pageContent2">
                    <div class="title"><h5>Contact List</h5></div>
                        <?php
                            $contact =new contact();
                            $contact->index();
                        ?>
                </div>
           
           <!--  company  Content -->
            <?php }else if($_GET['page']=="company"){  ?>
                <div id="projectContent" class="pageContent2">
                    <div class="title"><h5>Company</h5></div>
                        <?php
                            
                            $company =new company();
                            $company->index();
                        ?>
                </div>
            <!--  Project  Content -->
            <?php }else if($_GET['page']=="project"){  ?>
                <div id="projectContent" class="pageContent2">
                    
                        <?php
                            $project= new project();
                            echo $project->projectView();
                        ?>
                </div>
            <?php }else if($_GET['page']=="task"){  ?>
                <div id="projectContent" class="pageContent2">
                    
                        <?php
                            $task= new task();
                         if($currentRole!="subscriber"){ 
                            echo $task->showTaskTab();
                         }
                         else{
                            echo $task->clientTaskList();                                
                         }       
                        ?>
                </div>
            <?php }else if($_GET['page']=="search"){  ?>
                <div id="projectContent" class="pageContent2">
                    
                        <?php
                            $search = new search();
                            $search->index();         
                        ?>
                </div>
            <?php }else if($_GET['page']=="develope"){  ?>
                <div id="projectContent" class="pageContent2">
                        <div class="title"><h5>About ProjectBasix</h5></div>
                        <?php
                            $develope = new develope();
                            $develope->index(); 
                        ?>
                </div>
            
            
            <?php }else if($_GET['page']=="message"){  ?>
                <div id="projectContent" class="pageContent2">
                     <div class="title"><h5>Message Inbox</h5></div>
                        <?php
                            $message = new message();
                            $message->index();
                        ?>
            </div>
            <?php }else if($_GET['page']=="report"){  ?>
                <div id="projectContent" class="pageContent2">
                     <div class="title" id="mainHead"><h5>Report</h5></div>
                      <?php
                        $report = new report();
                        if($_GET['tab']=="")  
                            $report->index();
                        if($_GET['tab']=="client")
                            $report->clinetWiseReport();
                      ?>
                </div>
            <?php }else{ ?>
           
               
           
                <div id="projectContent" class="pageContent2">
                    <div class="title"><h5>Dashboard</h5></div>
                    <!-- Bars -->
                    <div class="widget">
                        <div class="head"><h5 class="iStats">Bars</h5></div>
                        <div class="body">
                            <div class="bars" style="width: 700px; height: 200px;"></div>
                        </div>
                    </div>
             
                </div>
            
            <?php } ?>
             
            <?php
            
            
             /*
            <!--  Projects  Detail  Content -->
            <div id="projectDetailContent" class="pageContent2"> <?php  $project =new project(); //echo $project->showProjectDetail(22) ?> </div>
            
            <div id="dashboardContent" class="pageContent2">
            </div>
            */ ?>
        </div>
        
     </div>
     
     <div class="fix"></div>
   </div>

    <?php  self::footer(); ?>
    <div id="loaderBox">
        <img src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/images/loaders/loader7.gif" />
    
    </div>    

            
    <?php     
    } 
    
    public function topNavigationBar(){
        global $current_user;                   
        get_currentuserinfo();
        $projectbasix_link=get_permalink(get_option("projectBasix_page"));
        $currentRole=  $current_user->roles[0];
        
        if(get_option('permalink_structure')=="")
            $pageLink=$projectbasix_link."&page=";
        else 
            $pageLink=$projectbasix_link."?page=";  
        
        $notification = new notification();
    ?>
    <div id="topNav">
        <div class="fixed">
            <div class="wrapper"> 
                <div class="welcome">
                <?php echo get_avatar( $current_user->user_email, 22 ); ?>
                <span>Howdy, <?php echo $current_user->display_name; ?> !</span></div>
                <div class="userNav">
                    <ul>
                        <li><a  class="searchClick" rel="searchOption"><img  src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/logo/search.png"/><span>Search</span></a></li>
                        <li><a href="<?php echo $pageLink; ?>settings" title=""><img src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/images/icons/topnav/profile.png" alt="" /><span>Profile</span></a></li>
                        <li><a href="javascript:void(0)"  rel="task" class="sidebarlink" title=""><img src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/images/icons/topnav/tasks.png" alt="" /><span>Tasks</span></a></li>
                        <li class="dd">
                            <a href="javascript:void(0)"  class="searchClick" rel="messageBox">
                            <img src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/images/icons/topnav/messages.png" alt="" />
                            <span>Messages</span>
                            </a>
                            <?php if($notification->getCurrentUserTotalNotification()!=0)
                                    echo '<span class="numberTop">'.$notification->getCurrentUserTotalNotification().'</span>';
                            ?>
                        </li>
                        <li><a href="<?php echo get_bloginfo("home"); ?>" title=""><span><?php echo get_bloginfo("name"); ?></span></a></li>
                        <li><a href="<?php echo wp_logout_url( get_bloginfo("home") ); ?> " title=""><img src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/images/icons/topnav/logout.png" alt="" /><span>Logout</span></a></li>
                    </ul>
                </div>
                <div class="fix"></div>
            </div>
        </div>
    </div>
    <div class="searchOption">
        <div style="width: 980px; margin: auto;">
            <p>
                <form method="post" action="<?php echo $pageLink; ?>search">
                    <?php if($currentRole!="subscriber"){ ?>
                    <label>Contact list</label> <input type="radio" checked="" value="1" name="searchType" />
                    <label>Company</label> <input type="radio" value="3" name="searchType" />
                    <?php } ?> 
                    <label>Project</label> <input type="radio" value="4" name="searchType" /> 
                    <label>Task</label> <input type="radio" value="5" name="searchType" /> 
                    <input type="text" name="searchKey" />
                    <input type="submit" class="greyishBtn submitForm" style="float: none;" value="Search" />
                    <a href="javascript:void(0);" rel="searchOption" class="seachUpClck"><img src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/images/forms/spinnerTop.png" /></a>
                </form>    
            </p>
        </div>    
    </div>
    <div class="messageBox">
        <div style="width: 980px; margin: auto;">
            <div class="widget" style="margin: 10px 0px !important;">
                    <div class="head"><a href="<?php echo $pageLink; ?>message"><h5 class="iMail">All Message</h5></a> </div>
                    <div class="body">
                        <div class="list arrow2Green pt12">
                            <?php
                                echo $notification->getCurrentUserNotification(); 
                            ?>
                        </div>
                    </div>
                    <a href="javascript:void(0);" rel="messageBox" class="seachUpClck"><img src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/images/forms/spinnerTop.png" /></a>
                </div>
               
        </div>    
    </div>       
    <?php    
    }
    
    public function getCompanyName($uid){
         global $wpdb;
         $table_name = $wpdb->prefix . "pbx_company";
         $clients_table = $wpdb->prefix . "pbx_company_clients";
         
         $sql="Select $table_name.name,$table_name.head ,$table_name.id
             from $table_name,$clients_table 
             where $clients_table.cid=$table_name.id and $clients_table.uid=$uid";
         
         return  $wpdb->get_row($sql);
         //return $result->name;    
        
    }
    
    public function header(){
    ?>
    <link href="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/css/main.css" rel="stylesheet" type="text/css" />
    <link href='http://fonts.googleapis.com/css?family=Cuprum' rel='stylesheet' type='text/css' />
    <link href="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/style/style.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/style/print.css" rel="stylesheet" type="text/css" media="print" />
    
    <!-- js script  !-->
    <script src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/jquery-1.4.4.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/js/script.js"></script>
    
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/spinner/jquery.mousewheel.js"></script>
  
    
    
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/spinner/ui.spinner.js"></script>
    
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script> 
    
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/fileManager/elfinder.min.js"></script>
    
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/wysiwyg/jquery.wysiwyg.js"></script>
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/wysiwyg/wysiwyg.image.js"></script>
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/wysiwyg/wysiwyg.link.js"></script>
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/wysiwyg/wysiwyg.table.js"></script>
    
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/flot/jquery.flot.js"></script>
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/flot/jquery.flot.pie.js"></script>
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/flot/excanvas.min.js"></script>
    
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/dataTables/jquery.dataTables.js"></script>
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/dataTables/colResizable.min.js"></script>
    
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/tprojectbasix/library/html/js/forms/forms.js"></script>
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/forms/autogrowtextarea.js"></script>
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/forms/autotab.js"></script>
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/forms/jquery.validationEngine-en.js"></script>
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/forms/jquery.validationEngine.js"></script>
    
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/colorPicker/colorpicker.js"></script>
    
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/uploader/plupload.js"></script>
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/uploader/plupload.html5.js"></script>
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/uploader/plupload.html4.js"></script>
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/uploader/jquery.plupload.queue.js"></script>
    
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/ui/progress.js"></script>
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/ui/jquery.jgrowl.js"></script>
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/ui/jquery.tipsy.js"></script>
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/ui/jquery.alerts.js"></script>
    
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/jBreadCrumb.1.1.js"></script>
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/cal.min.js"></script>
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/jquery.collapsible.min.js"></script>
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/jquery.ToTop.js"></script>
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/jquery.listnav.js"></script>
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/jquery.sourcerer.js"></script>
    
        
    
    <link href="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/multi-select/css/multi-select.css" rel="stylesheet" type="text/css" />
   
    <?php /*<script src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/multi-select/js/jquery.js" type="text/javascript"></script> */ ?>
    <script src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/multi-select/js/jquery.quicksearch.js" type="text/javascript"></script>
    <script src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/multi-select/js/jquery.multi-select.js" type="text/javascript"></script>
    <script src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/multi-select/js/application.js" type="text/javascript"></script>
   
   
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/html/js/custom.js"></script>
    
    <!-- pagination js -->
    <script type="text/javascript" src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/js/pagination.js"></script>
    
    <!-- chossen !-->
    <script src="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/chosen/chosen.jquery.js" type="text/javascript"></script>
    <link rel="stylesheet" href="<?php echo PBX_WP_PLUGIN_URL; ?>/projectbasix/library/chosen/chosen.css" />

    <?php    
    }
    public function footer(){
        $projectbasix_link=get_permalink(get_option("projectBasix_page"));
        
        if(get_option('permalink_structure')=="")
            $pageLink=$projectbasix_link."&page=";
        else 
            $pageLink=$projectbasix_link."?page=";  
            
    ?>
    <!-- Footer -->
    <div id="footer">
    	<div class="wrapper">
        	<span>&copy; Copyright 2012. All rights reserved. <a href="http://projectbasix.com" title="">projectBasix</a> |  develope by <a href="<?php echo $pageLink; ?>develope">imran.aspire </a>  </span>
        </div>
    </div>
    <?php    
    }
}  
?>