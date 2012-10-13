<?php
/*
Developed by Reneesh T.K
reneeshtk@gmail.com
You can use it with out any worries...It is free for you..It will display the out put like:
First | Previous | 3 | 4 | 5 | 6 | 7| 8 | 9 | 10 | Next | Last
Page : 7  Of  10 . Total Records Found: 20
*/
class Pagination_class{
	var $result;
	var $anchors;
	var $total;
    var $selector;
    var $totalRow;
	function Pagination_class($qry,$starting,$recpage,$selector)
	{  
	    global $wpdb;   
		$rst		=	mysql_query($qry) or die(mysql_error());
		$numrows	=	mysql_num_rows($rst);
		$qry		 .=	" limit $starting, $recpage";
        
        $this->totalRow=$numrows;
        
        $this->result=$wpdb->get_results($qry);
		
        //$this->result	=	mysql_query($qry) or die(mysql_error());
        
        $this->selector=$selector;
       /* 
        global $wpdb;
        $result=$wpdb->get_results($qry);
        $numrows=count($result); */
        //echo $numrows.">>>";die;
       
		$next		=	$starting+$recpage;
		$var		=	((intval($numrows/$recpage))-1)*$recpage;
		$page_showing	=	intval($starting/$recpage)+1;
		$total_page	=	ceil($numrows/$recpage);
        
        
		if($numrows % $recpage != 0){
			$last = ((intval($numrows/$recpage)))*$recpage;
		}else{
			$last = ((intval($numrows/$recpage))-1)*$recpage;
		}
		$previous = $starting-$recpage;
       
        
		$anc = "<div class='pagination'><ul class='pages'>";
		
        if($previous < 0){
			$anc .= "<li><a  href='javascript:void(0)'> First</a></li>";
			$anc .= "<li><a  href='javascript:void(0)'>  Previous</a></li>";
		}else{
			$anc .= "<li><a  href='javascript:void(0)' class='paginationLink' rel='0' >First </a></li>";
			$anc .= "<li><a   href='javascript:void(0)' class='paginationLink' rel='$previous'>Previous </a></li>";
		}
		
		################If you dont want the numbers just comment this block###############	
		$norepeat = 4;//no of pages showing in the left and right side of the current page in the anchors 
		$j = 1;
		$anch = "";
		for($i=$page_showing; $i>1; $i--){
			$fpreviousPage = $i-1;
			$page = ceil($fpreviousPage*$recpage)-$recpage;
			$anch = "<li> <a class='paginationLink'  href='javascript:void(0)' rel='$page' >$fpreviousPage </a></li>".$anch;
			if($j == $norepeat) break;
			$j++;
		}
		$anc .= $anch;
		$anc .= '<li><a  href="javascript:void(0)" class="active">'.$page_showing.'</a></li>';
		$j = 1;
		for($i=$page_showing; $i<$total_page; $i++){
			$fnextPage = $i+1;
			$page = ceil($fnextPage*$recpage)-$recpage;
			$anc .= "<li><a class='paginationLink' rel='$page'  href='javascript:void(0)' >$fnextPage</a></li>";
			if($j==$norepeat) break;
			$j++;
		}
		############################################################
		if($next >= $numrows){
			$anc .= "<li class='previous-off'><a href='javascript:void(0)'>Next</a></li>";
			$anc .= "<li class='previous-off'><a href='javascript:void(0)'>Last</a></li>";
		}else{
			$anc .= "<li class='next'> <a  href='javascript:void(0)'  class='paginationLink' rel='$next'>Next </a></li>";
			$anc .= "<li class='prev'><a   href='javascript:void(0)' class='paginationLink' rel='$last'>Last</a></li>";
		}
			$anc .= "</ul></div>";
            $anc .="<input type='hidden' id='selector' value='$this->selector' />";
		$this->anchors = $anc;
		
		$this->total = "Page : $page_showing <i> Of  </i> $total_page . Total Records Found: $numrows";
	}
}
?>