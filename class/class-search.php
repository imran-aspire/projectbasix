<?php

/**
 * search CLASS 
 *
 * @link http://www.mdimran.net/
 * @author Imran <imran.aspire@gmail.com>
 *
 */

class search{
    
    private $wpdb;
    private $current_user;
   
    public function search(){
         global $wpdb,$current_user;
         $this->wpdb=$wpdb;
         get_currentuserinfo();
         $this->current_user= $current_user;
         
         
    }
    
    public function index(){
        
        $contact_table = $this->wpdb->prefix . "pbx_client";
        $company_table = $this->wpdb->prefix . "pbx_company";
        $project_table = $this->wpdb->prefix . "pbx_project";
        $task_table = $this->wpdb->prefix . "pbx_task";
        $currentRole=  $this->current_user->roles[0];
        $user_id=$this->current_user->id;
        
        $searchKey= $_POST['searchKey'];
        $contact_listStr=""; 
        if($_POST['searchType']==1){
            $sql ="Select *from $contact_table where (first_name like '%$searchKey%' or last_name like '%$searchKey%' or user_email like '$searchKey%' )";
            $searchResult = $this->wpdb->get_results($sql);
            // contact list
            if($searchResult){
                $contact_listStr .= '<table width="100%" cellspacing="0" cellpadding="0" class="tableStatic">
                        <tr>
                            <td width="20%"><strong>First Name</strong></td>
                            <td width="20%"><strong>Last Name</strong></td>
                            <td width="20%"><strong>Email</strong></td>
                            <td width="20%"><strong>Phone</strong></td>
                            <td  width="20%" colspan="1" style="text-align:center"><strong>Action</strong></td>
                         </tr>
                         <tbody>';
                foreach($searchResult as $row ){
                            $contact_listStr .= 
                                    '<tr>
                                        <td>'.$row->first_name.'</td>
                                        <td>'.$row->last_name.'</td>
                                        <td>'.$row->user_email.'</td>
                                        <td>'.$row->cell.'</td>
                                        <td style="text-align:center"><a href="'.$pageLink . "?page=contact&action=edit&id=" . $row->id . '"  class="delete">Edit</a></td>
                                   </tr>';
                    
                }
                $contact_listStr .= '</tbody>
                    </table>';
            }
         }
         
         if($_POST['searchType']==3){
            $qStr="";
            if($currentRole =="editor")
                $qStr=" and author=$user_id ";        
            $sql ="Select *from $company_table where (name like '%$searchKey%' or head like '%$searchKey%' ) $qStr";
            //echo $sql;
            $searchResult = $this->wpdb->get_results($sql);
            // contact list
            if($searchResult){
                $contact_listStr .= '<table width="100%" cellspacing="0" cellpadding="0" class="tableStatic">
                        <tr>
                            <td width="20%"><strong>ID</strong></td>
                            <td width="20%"><strong>Company Name</strong></td>
                            <td width="20%"><strong>Head</strong></td>
                            <td  width="20%" colspan="1" style="text-align:center"><strong>Action</strong></td>
                         </tr>
                         <tbody>';
                foreach($searchResult as $row ){
                            $contact_listStr .= 
                                    '<tr>
                                        <td>'.$row->id.'</td>
                                        <td>'.$row->name.'</td>
                                        <td>'.$row->head.'</td>
                                        <td style="text-align:center"><a href="'.$pageLink . "?page=company&action=editCompany&id=" . $row->id . '"  class="delete">Edit</a></td>
                                   </tr>';
                    
                }
                $contact_listStr .= '</tbody>
                    </table>';
            }
         }
         if($_POST['searchType']==4){
            
            $qStr="";
            if($currentRole =="editor")
                $qStr=" and author=$user_id ";        
           
            $sql ="Select *from $project_table where (name like '%$searchKey%' or description like '%$searchKey%' ) $qStr";
        
            if($currentRole =="subscriber"){
                $company_table = $this->wpdb->prefix . "pbx_company"; 
                $company_client_table = $this->wpdb->prefix . "pbx_company_clients"; 
                $table_name = $this->wpdb->prefix . "pbx_project";
        
             $sql="select 
                        $table_name.id,$table_name.name ,$table_name.status,$table_name.author,$table_name.deadline
                        from $table_name,$company_table,$company_client_table  
                        where $table_name.status=1 
                        and $company_table.id=$table_name.cid 
                        and $company_client_table.cid=$table_name.cid 
                        and $company_client_table.uid=$user_id  
                        and ($table_name.name like '%$searchKey%' or $table_name.description like '%$searchKey%' )
                        order by $table_name.id desc";
                
            }
            
             
            
                       
            $searchResult = $this->wpdb->get_results($sql);
            
            // contact list
            if($searchResult){
                $contact_listStr .= '<table width="100%" cellspacing="0" cellpadding="0" class="tableStatic">
                        <tr>
                            <td width="20%"><strong>ID</strong></td>
                            <td width="20%"><strong>Project</strong></td>
                            <td width="20%"><strong>Deadline</strong></td>
                            <td  width="20%" colspan="1" style="text-align:center"><strong>Action</strong></td>
                         </tr>
                         <tbody>';
                foreach($searchResult as $row ){
                            $contact_listStr .= 
                                    '<tr>
                                        <td>'.$row->id.'</td>
                                        <td><a href="javascript:void(0);" class="projectDetail" rel="'.$row->id.'" >'.$row->name.'</a></td>
                                        <td>'.$row->deadline.'</td>
                                        <td style="text-align:center"><a href="javascript:void(0);" class="projectDetail" rel="'.$row->id.'" >Edit</a></td>
                                   </tr>';
                    
                }
                $contact_listStr .= '</tbody>
                    </table>';
            }
         }   
        if($_POST['searchType']==5){
            
            $sql ="Select *from $task_table where (title like '%$searchKey%' or description like '%$searchKey%' )";
            
            $qStr="";
            
            if($currentRole =="editor"){
                
                $sql ="Select $task_table.id,$task_table.title,$task_table.enddate ,$task_table.status
                    from $task_table,$project_table 
                    where 
                    $task_table.pid=$project_table.id and
                    $project_table.author=$user_id and
                    ($task_table.title like '%$searchKey%' or $task_table.description like '%$searchKey%' )";
            
            }
            if($currentRole =="subscriber"){
                 $sql ="Select *from $task_table where (title like '%$searchKey%' or description like '%$searchKey%' ) and author=$user_id";
            
            }
                    
           //echo $sql;
            
            $searchResult = $this->wpdb->get_results($sql);
            // contact list
            if($searchResult){
                $contact_listStr .= '<table width="100%" cellspacing="0" cellpadding="0" class="tableStatic">
                        <tr>
                            <td width="20%"><strong>ID</strong></td>
                            <td width="20%"><strong>Task</strong></td>
                            <td width="20%"><strong>Deadline</strong></td>
                            <td width="20%"><strong>Status</strong></td>
                            <td  width="20%" colspan="1" style="text-align:center"><strong>Action</strong></td>
                         </tr>
                         <tbody>';
                foreach($searchResult as $row ){
                    
                    $staus="<span style='color:green;'>Complete</span>";
                    if($row->status==1)
                        $staus="<span style='color:red;'>InComplete</span>";
                    
                            $contact_listStr .= 
                                    '<tr>
                                        <td>'.$row->id.'</td>
                                        <td><a href="javascript:void(0);" class="taskDetail" rel="'.$row->id.'" >'.$row->title.'</a></td>
                                        <td>'.$row->enddate.'</td>
                                        <td>'.$staus.'</td>
                                        <td style="text-align:center"><a href="javascript:void(0);" class="taskDetail" rel="'.$row->id.'" >Edit</a></td>
                                   </tr>';
                    
                }
                $contact_listStr .= '</tbody>
                    </table>';
            }
         }   
    
    ?>
    <?php if($contact_listStr){ ?>
    <div class="widget" style="margin-top: 0px !important;">
            
            <div id="pbxAddTaskFormBox">
                <div class="head">
                    <h5 class="iUsers">Search Result</h5> 
                </div>
                <?php echo $contact_listStr; ?> 
          </div>
    </div>
    <div id="taskDetailBox"></div>
    <?php }else{
                echo "<h2>No Search Result</h2>";
          } 
                       
    } 
}  
?>