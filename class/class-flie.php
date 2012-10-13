<?php

/**
 * file CLASS 
 *
 * @link http://www.mdimran.net/
 * @author Imran <imran.aspire@gmail.com>
 *
 */

class file{
    
    private $wpdb;
    private $current_user;
   
    public function file(){
         global $wpdb,$current_user;
         $this->wpdb=$wpdb;
         
         get_current_user();
         $this->current_user=$current_user;
         
    }
    
    /**
    * Create flie table
    *
    * 
    * @return bool 'true' | false(if failed)
    */
    public function createTable(){
        
        
        
        $table_name = $this->wpdb->prefix . "pbx_file";
        // staue :   share => 1 , not share = > 0  
       
        if($this->wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $sql = "CREATE TABLE " . $table_name . " (
            	id          bigint(11) NOT NULL AUTO_INCREMENT,
              	pid          bigint(11),
            	tid          bigint(11),
            
                title       varchar(555) NOT NULL,
                description       TEXT,
                filename       varchar(555) NOT NULL,
                status       tinyint,
                PRIMARY KEY   (id)
                
            );";
            $results = $this->wpdb->query( $sql );
        }
       
    
    }
    function getFileList($tid){
        $flietable = $this->wpdb->prefix . "pbx_file";
        
        $sql="Select *from $flietable where tid=$tid";
        $fileResult=$this->wpdb->get_results($sql);
        $totalFile=count($fileResult);
        $filesStr = "";
        
        $chkHead="";
        if($this->current_user->roles[0]!="subscriber")
            $chkHead =' <td width="10%">Remove files</td>';
        
        
        
        
        if($fileResult){
          
          $filesStr='<div class="widget" style="width:665px;margin-left:3px">
                    <div class="head">
                        <h5 class="iChart8">Attached Files</h5>
                        <div class="num"><a class="blueNum" href="#">+'.$totalFile.'</a></div></div>
                    <table width="100%" cellspacing="0" cellpadding="0" class="tableStatic">
                        <thead>
                            <tr>
                              <td width="40%">Title</td>  
                              <td width="40%">FileName</td>
                              <td>Download</td>
                              '.$chkHead.'  
                            </tr>
                        </thead>
                        <tbody>';
           foreach($fileResult as $row){   //'.$filepath.$row->filename.'
            $filepath=PBX_WP_CONTENT_URL."/uploads/projectbasix/$row->pid/";
            
            $chkData="";
            if($this->current_user->roles[0]!="subscriber")
            $chkData= '<td><a class="fileDelete"  onclick="return confirm(\'Are you sure you want to delete this File?\');" href="javascript:void(0);"  rel="'.$row->pid.'" val="'.$row->filename.'" >Delete </a></td>';
            
            $filesStr .='   <tr>
                                <td>'.$row->title.'</td>
                                <td><a rel="nofollow" target="_blank" class="fileDownload"  href="'.$filepath.$row->filename.'" >'.$row->title.'</a></td>
                                <td> <a rel="nofollow" target="_blank" class="fileDownload"  href="'.$filepath.$row->filename.'" >Download</a></td>
                                '.$chkData.'
                            </tr>';  
          }
          $filesStr .='</tbody>
                    </table>                    
                </div>';                                     
        }
        
        return $filesStr;    
    }
    
    function getProjectFileList($pid){
        $flietable = $this->wpdb->prefix . "pbx_file";
        $tasktable = $this->wpdb->prefix . "pbx_task";
        
        $userid = $this->current_user->ID;
        
        $sql="Select *from $flietable where pid=$pid";
        
        if($this->current_user->roles[0]=="subscriber"){
        
            $sql="Select $flietable.pid,$flietable.title,$flietable.filename 
            from $flietable,$tasktable  
            where $flietable.tid =$tasktable.id and $tasktable.author= $userid and $flietable.pid=$pid";
        
        }
       // echo $sql;
        
        
        $fileResult=$this->wpdb->get_results($sql);
        $totalFile=count($fileResult);
        $filesStr = "No Files";
        
        $chkHead="";
        if($this->current_user->roles[0]!="subscriber")
            $chkHead =' <td width="10%">Remove files</td>';
        
        
        
        
        if($fileResult){
          
          $filesStr='<br />
                    <div class="widget" style="width:100%;margin-left:3px">
                    <div class="head">
                        <h5 class="iChart8">Attached Files</h5>
                        <div class="num"><a class="blueNum" href="#">+'.$totalFile.'</a></div></div>
                    <table width="100%" cellspacing="0" cellpadding="0" class="tableStatic">
                        <thead>
                            <tr>
                              <td width="40%">Title</td>  
                              <td width="40%">FileName</td>
                              <td>Download</td>
                              '.$chkHead.'  
                            </tr>
                        </thead>
                        <tbody>';
           foreach($fileResult as $row){   //'.$filepath.$row->filename.'
            $filepath=PBX_WP_CONTENT_URL."/uploads/projectbasix/$row->pid/";
            
            $chkData="";
            if($this->current_user->roles[0]!="subscriber")
            $chkData= '<td><a class="fileDelete"  onclick="return confirm(\'Are you sure you want to delete this File?\');" href="javascript:void(0);"  rel="'.$row->pid.'" val="'.$row->filename.'" >Delete </a></td>';
            
            $filesStr .='   <tr>
                                <td>'.$row->title.'</td>
                                <td><a rel="nofollow" target="_blank" class="fileDownload"  href="'.$filepath.$row->filename.'" >'.$row->filename.'</a></td>
                                <td> <a rel="nofollow" target="_blank" class="fileDownload"  href="'.$filepath.$row->filename.'" >Download</a></td>
                                '.$chkData.'
                            </tr>';  
          }
          $filesStr .='</tbody>
                    </table>                    
                </div>';                                     
        }
        
        return $filesStr;    
    }   
    function deleteFile($pid,$filename){
          global $wpdb;  
          $flietable = $wpdb->prefix . "pbx_file";
          
          $filepath=PBX_WP_CONTENT_DIR."\uploads\projectbasix\\$pid\\$filename";
          if(file_exists($filepath)){
                unlink($filepath);
                $sql="delete  from  $flietable where pid=$pid and filename='$filename'";
                $result3 =$wpdb->query($sql);
                
                return 1;
          }
          else
          return 0;
                        
        
    }
}  
?>