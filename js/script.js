
var baseurl;

jQuery(function() {
     jQuery(".chzn-select").chosen(); jQuery(".chzn-select-deselect").chosen({allow_single_deselect:true}); 
    // define the baseurl
    baseurl=jQuery("#pluginurl").val()+"/projectbasix/ajax/";
    
    
    //--------------------------------------------------------------------------------------
    //------- project scripts --------------------------------------------------------------
    //--------------------------------------------------------------------------------------
    
    
    
    //------------------------------------------
    // ------   toggel the add project box -----
    // -----------------------------------------
    jQuery(".clickFormBox").live("click",function(){
        jQuery("#"+jQuery(this).attr("rel")).slideToggle("slow");
        jQuery("."+jQuery(this).attr("rel")).slideToggle("slow");
        
        
    });
        
    
    //------------------------------------------
    // ------  project page show ajax call -----
    // -----------------------------------------
    jQuery(".sidebarlink").click(function(){
        jQuery("#loaderBox").css("display","block");
        jQuery(".pageContent").slideUp("slow");
        var page=jQuery(this).attr("rel");
        $.post(baseurl+"ajax-page.php",
            {page: page},
            function(data){
                
                jQuery(".pageContent2").slideUp("slow");
                
                jQuery("#projectContent").slideDown("slow");
                jQuery("#projectContent").html(data);
                jQuery("#loaderBox").css("display","none");
        
            } 
        );
    })
    
    //------------------------------------------
    // ------  project add ajax call -----------
    // -----------------------------------------
    jQuery("#addProjectForm").live("submit",function(){
        
        if(jQuery("#name").val()==""){
            alert("Enter Project Name");
            return false;
        }
        if(jQuery("#description").val()==""){
            alert("Enter Project Description");
            return false;
        }
        if(jQuery("#companyList").val()==""){
            alert("Enter Company");
            return false;
        }
        
        
        /*
        var data =jQuery("#addProjectForm").serialize();
        
        jQuery("#loaderBox").css("display","block");
        jQuery(".nNote").css("display","none");
        
        $.ajax({
                async: false,
                url:baseurl+"ajax-project.php",
                type: 'POST',
                data: data,
                success: function(data) {
                    if(data==true){
                        jQuery(".nSuccess").css("display","block");
                        jQuery(".nSuccess").html('<p><strong>SUCCESS: </strong>Project Added Successully</p>');
                    }else{
                        jQuery(".nFailure").css("display","block");
                        jQuery(".nFailure").html(' <p><strong>FAILURE: </strong>Oops sorry. Some problem happen .Try again later</p>');
                    }
                    jQuery('#addProjectForm').each (function(){
                      this.reset();
                    });
                    jQuery("#loaderBox").css("display","none");
                    jQuery("#pbxAddFormBox").toggle("slow");
                    
            }
        });
        */
        return true;
    })
    
    
    //------------------------------------------
    // ------   toggel the  project summery box 
    // -----------------------------------------
    jQuery(".projectSummeryClick").live("click",function(){
          var pid;
          pid = jQuery(this).attr("pid");
          jQuery("#loaderBox").css("display","block");
          $.ajax({
                async: false,
                url:baseurl+"ajax-project.php",
                type: 'POST',
                data: "ajaxtype=getSummeryList&pid="+pid,
                success: function(data) {
                     jQuery("#projectSummery"+pid+" .widget").html(data);  
                     jQuery("#projectSummery"+pid).slideDown("slow");
                     jQuery("#loaderBox").css("display","none");    
                }
        });
    });
    
    
    //------------------------------------------
    // ------   close the  project summery box 
    // -----------------------------------------
    jQuery(".closeSummery").live("click",function(){
          jQuery(this).parent().parent().parent().slideUp("slow"); 
    });
    
    //------------------------------------------
    // ------   show the project detail 
    // -----------------------------------------
    /*
    jQuery(".projectDetail").live("click",function(){
          var pid;
          pid = jQuery(this).attr("rel");
          jQuery("#loaderBox").css("display","block"); 
          $.ajax({
                async: false,
                url:baseurl+"ajax-project.php",
                type: 'POST',
                data: "ajaxtype=getProjectDetail&pid="+pid,
                success: function(data) {
                     jQuery("#projectSummery"+pid+" .widget").html(data);  
                     jQuery("#projectSummery"+pid).slideDown("slow");
                     jQuery("#loaderBox").css("display","none");    
                }
        });
    });
   */
    //------------------------------------------
    // ------   ajax-pagination 
    // -----------------------------------------
  
    
   jQuery(".paginationLink").live("click",function(){ 
        jQuery("#loaderBox").css("display","block"); 
        var selector = jQuery("#selector").val();
        $.ajax({
                async: false,
                url:baseurl+"ajax-pagination.php",
                type: 'POST',
                data: "ajaxtype=getProjectList&start="+jQuery(this).attr("rel"),
                success: function(data) {
                     jQuery("#"+selector).html(data);
                     jQuery("#loaderBox").css("display","none"); 
        
                }
        });
   
   }); 
    //------------------------------------------
    // ------   project Tab  click 
    // -----------------------------------------
  
	$.fn.simpleTabs = function(){ 
	   
		//Default Action
		$(this).find(".tab_content").hide(); //Hide all content
		$(this).find("ul.tabs li:first").addClass("activeTab").show(); //Activate first tab
		$(this).find(".tab_content:first").show(); //Show first tab content
	
		//On Click Event
		$("ul.tabs li").click(function() { 
			$(this).parent().parent().find("ul.tabs li").removeClass("activeTab"); //Remove any "active" class
			$(this).addClass("activeTab"); //Add "active" class to selected tab
			$(this).parent().parent().find(".tab_content").hide(); //Hide all tab content
			var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
			
            getProjectTabContent($(this).find("a").attr("rel"),activeTab,jQuery("#pid").val());
            
        	return false;
		});
	
	};//end function
    
    
    //--------------------------------------------------
    // ------------  project detail click --------------
    //--------------------------------------------------
    
    jQuery(".projectDetail").live("click",function(){
        var pid=jQuery(this).attr("rel");
        jQuery("#loaderBox").css("display","block"); 
        
        $.ajax({
                async: false,
                url:baseurl+"ajax-project.php",
                type: 'POST',
                data: "ajaxtype=getProjectDetail&pid="+pid,
                success: function(data) {
                    jQuery(".pageContent2").slideUp("slow");
                    
                    jQuery("#projectContent").slideDown("slow");
                    jQuery("#projectContent").html(data);
                    jQuery("#loaderBox").css("display","none");
                }
        });
    });
    
    
    //--------------------------------------------------
    // ------------ edit project user detail      -----
    //--------------------------------------------------
    
   
     jQuery("#editProjectUserForm").live("submit",function(){
        
       
        var data =jQuery("#editProjectUserForm").serialize();
        
        jQuery("#loaderBox").css("display","block");
        jQuery(".nNote").css("display","none");
        
        $.ajax({
                async: false,
                url:baseurl+"ajax-project.php",
                type: 'POST',
                data: data,
                success: function(data) {
                    if(data==true){
                        jQuery(".nSuccess").css("display","block");
                        jQuery(".nSuccess").html('<p><strong>SUCCESS: </strong>Project User Update Successully</p>');
                    }else{
                        jQuery(".nFailure").css("display","block");
                        jQuery(".nFailure").html(' <p><strong>FAILURE: </strong>Oops sorry. Some problem happen .Try again later</p>');
                    }
                    /*
                    jQuery('#addProjectForm').each (function(){
                      this.reset();
                    });
                    */
                    jQuery("#loaderBox").css("display","none");
                    jQuery("#pbxAddFormBox").toggle("slow");
                    
            }
        });
      
        return false;
    })
    
    
    //--------------------------------------------------
    // ------------ ediitProjectSettings submit    -----
    //--------------------------------------------------
    
   
     jQuery("#ediitProjectSettings").live("submit",function(){
        
       
        var data =jQuery("#ediitProjectSettings").serialize();
        
        jQuery("#loaderBox").css("display","block");
        jQuery(".nNote").css("display","none");
        
        $.ajax({
                async: false,
                url:baseurl+"ajax-project.php",
                type: 'POST',
                data: data,
                success: function(data) {
                    if(data==true){
                        jQuery(".nSuccess").css("display","block");
                        jQuery(".nSuccess").html('<p><strong>SUCCESS: </strong>Project User Update Successully</p>');
                    }else{
                        jQuery(".nFailure").css("display","block");
                        jQuery(".nFailure").html(' <p><strong>FAILURE: </strong>Oops sorry. Some problem happen .Try again later</p>');
                    }
                    /*
                    jQuery('#addProjectForm').each (function(){
                      this.reset();
                    });
                    */
                    jQuery("#loaderBox").css("display","none");
                    jQuery("#pbxAddFormBox").toggle("slow");
                    
            }
        });
      
        return false;
    })
    
    
    
    //--------------------------------------------------
    // ------------ addTaskForm submit    -----
    //--------------------------------------------------
     jQuery("#addTaskForm").live("submit",function(){
        
        if(jQuery("#title").val()==""){
            alert("Enter Task Title");
            return false;
        }
        
        var data =jQuery("#addTaskForm").serialize();
        
        jQuery("#loaderBox").css("display","block");
        jQuery(".nNote").css("display","none");
        
        jQuery.ajax({
                async: false,
                url:baseurl+"ajax-task.php",
                type: 'POST',
                data: data,
                success: function(data) {
                    jQuery("#loaderBox").css("display","block"); 
                    jQuery.ajax({
                            async: false,
                            url:baseurl+"ajax-project.php",
                            type: 'POST',
                            data: "ajaxtype=getProjectDetail&pid="+jQuery('#pid').val(),
                            success: function(data) {
                                jQuery(".pageContent2").slideUp("slow");
                                
                                jQuery("#projectContent").slideDown("slow");
                                jQuery("#projectContent").html(data);
                                jQuery("#loaderBox").css("display","none");
                            }
                    });
        
                   
                    if(data==true){
                        jQuery(".nSuccess").css("display","block");
                        jQuery(".nSuccess").html('<p><strong>SUCCESS: </strong>Task is ADD Successully</p>');
                    }else{
                        jQuery(".nFailure").css("display","block");
                        jQuery(".nFailure").html(' <p><strong>FAILURE: </strong>Oops sorry. Some problem happen .Try again later</p>');
                    }
                
                    jQuery("#loaderBox").css("display","none");
                   
                    
                
            }
        });
      
        return false;
    })
    
  
     //--------------------------------------------------
    // ------------ taskDetail click    -----
    //--------------------------------------------------
    
   
    jQuery(".taskDetail").live("click",function(){ //alert("DDFD")
        var tid=jQuery(this).attr("rel");
        jQuery("#loaderBox").css("display","block"); 
        
        $.ajax({
                async: false,
                url:baseurl+"ajax-task.php",
                type: 'POST',
                data: "type=getTaskDetail&tid="+tid,
                success: function(data) {
                   
                    jQuery("#pbxAddTaskFormBox").slideUp("slow");
                    jQuery(".table").slideUp("slow");
                    
                    jQuery("#taskDetailBox").html(data);
                    jQuery("#taskDetailBox").slideDown("slow");
                    
                    jQuery("#loaderBox").css("display","none");
                   
                }
        });
    });
    
    //editTaskForm
    
     //--------------------------------------------------
    // ------------ editTaskForm submit    -----
    //--------------------------------------------------
     jQuery("#editTaskForm").live("submit",function(){
        
      
        var data =jQuery("#editTaskForm").serialize();
       
        jQuery("#loaderBox").css("display","block");
        jQuery(".nNote").css("display","none");
        
        $.ajax({
                async: false,
                url:baseurl+"ajax-task.php",
                type: 'POST',
                data: data,
                success: function(data) {
                    //tab2
                    /*
                     $.ajax({
                        async: false,
                        url:baseurl+"ajax-project.php",
                        type: 'POST',
                        data: "ajaxtype=getProjectTabContent&tabtype=tasks&pid="+jQuery("#pid").val(),
                        success: function(data) {
                            jQuery("#tab2").html(data);
                        }
                    }); 
                    
                    
                    if(data==true){
                        jQuery(".nSuccess").css("display","block");
                        jQuery(".nSuccess").html('<p><strong>SUCCESS: </strong>Task is Update Successully</p>');
                    }else{
                        jQuery(".nFailure").css("display","block");
                        jQuery(".nFailure").html(' <p><strong>FAILURE: </strong>Oops sorry. Some problem happen .Try again later</p>');
                    }
                
                   
                    jQuery(".uploader_filelist").html("");
                    jQuery(".plupload_filelist_footer").css("display","block");
                    jQuery("#loaderBox").css("display","none");
                   
                    jQuery("#pbxAddFormBox").toggle("slow");
                    */
                     if(data==true){
                        jQuery(".nSuccess").css("display","block");
                        jQuery(".nSuccess").html('<p><strong>SUCCESS: </strong>Task is Update Successully</p>');
                    }else{
                        jQuery(".nFailure").css("display","block");
                        jQuery(".nFailure").html(' <p><strong>FAILURE: </strong>Oops sorry. Some problem happen .Try again later</p>');
                    }
                
                   
                    jQuery(".uploader_filelist").html("");
                    jQuery(".plupload_filelist_footer").css("display","block");
                    jQuery("#loaderBox").css("display","none");
                   
                    jQuery("#pbxAddFormBox").toggle("slow");
                
            }
        });
      
        return false;
    })
    
    //projectSelect
    
       //--------------------------------------------------
    // ------------ task project select    -----
    //--------------------------------------------------
    
    
    jQuery("#projectSelect").live("change",function(){ 
        var pid=jQuery(this).val();
        
        if(pid){
            
           // jQuery("#currentProject").val(pid);
            jQuery("#loaderBox").css("display","block"); 
        
            $.ajax({
                    async: false,
                    url:baseurl+"ajax-task.php",
                    type: 'POST',
                    data: "type=getProjectUserList&pid="+pid,
                    success: function(data) {
                       
                        jQuery(".taskBox").html(data);
                       
                        jQuery(".taskBox").slideDown("slow");
                        
                        jQuery("#loaderBox").css("display","none");
                       
                    }
            });    
        }else
            jQuery(".taskBox").slideUp("slow");
        
    });
    
    
    //addProjectTaskForm
    
    
    //--------------------------------------------------
    // ------------ addProjectTaskForm submit    -----
    //--------------------------------------------------
     jQuery("#addProjectTaskForm").live("submit",function(){
       
        var error=1;
        jQuery(".requiredField").each(function(i){
           jQuery(this).css("border","1px solid #E5E4E4");
            if(jQuery(this).val()==""){
                jQuery(this).css("border","1px solid red");
                error=0;
            }
        });
        
        if(error==0){
           jQuery(".nFailure").css("display","block");
           jQuery(".nFailure").html(' <p>Please fill required flied</p>');
            return false;
        }
        var data =jQuery("#addProjectTaskForm").serialize();
        
        jQuery("#loaderBox").css("display","block");
        jQuery(".nNote").css("display","none");
        
        $.ajax({
                async: false,
                url:baseurl+"ajax-task.php",
                type: 'POST',
                data: data,
                success: function(data) {
                    
                    jQuery("#loaderBox").css("display","block");
                    jQuery(".pageContent").slideUp("slow");
                    var page="task";
                    $.post(baseurl+"ajax-page.php",
                        {page: page},
                        function(data){
                            
                            jQuery(".pageContent2").slideUp("slow");
                            
                            jQuery("#projectContent").slideDown("slow");
                            jQuery("#projectContent").html(data);
                            jQuery("#loaderBox").css("display","none");
                    
                        } 
                    );
                    
                    if(data==true){
                        jQuery(".nSuccess").css("display","block");
                        jQuery(".nSuccess").html('<p><strong>SUCCESS: </strong>Task is ADD Successully</p>');
                    }else{
                        jQuery(".nFailure").css("display","block");
                        jQuery(".nFailure").html(' <p><strong>FAILURE: </strong>Oops sorry. Some problem happen .Try again later</p>');
                    }
                
                   /*
                    jQuery(".uploader_filelist").html("");
                    jQuery(".plupload_filelist_footer").css("display","block");
                    jQuery("#loaderBox").css("display","none");
                   */
                    //jQuery("#pbxAddFormBox").toggle("slow");
                    
                
            }
        });
      
        return false;
    })
    
     //--------------------------------------------------
    // ------------ companyAddForm submit    -----
    //--------------------------------------------------
    jQuery("#companyAddForm").submit(function(){
        var count = jQuery("#clientList :selected").length;
        
        if(jQuery("#name").val()==""){
            alert("Enter Company Name");
            return false;
        }
        
        if(count>10){
            alert("Maximum 10 user can add in a company at a time");
            return false;
        }    
    })
    
  
    	oTable = jQuery('#example').dataTable({
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		"sDom": '<""f>t<"F"lp>'
	});
  	
    //
      jQuery(".topDir").tipsy({fade: true, gravity: "s"}); 
    
    //  manager contact list 
    
    jQuery('#myList').listnav({ 
		initLetter: 'a', 
		includeAll: true, 
		includeOther: true, 
		flagDisabled: true, 
		noMatchText: 'Nothing matched your filter, please click another letter.', 
		prefixes: ['the','a'] ,
	});

    //--------------------------------------------------
    // ------------ task project select    -----
    //--------------------------------------------------
    
   
    jQuery(".companyUser").click(function(){ 
        var uid=jQuery(this).attr("rel");
        
        if(confirm('Are you sure to delete this client ?')) {
             $.ajax({
                    async: false,
                    url:baseurl+"ajax-company.php",
                    type: 'POST',
                    data: "type=deleteClient&uid="+uid,
                    success: function(data) { //alert(data)
                       if(data==1){
                        jQuery(".tipsy").remove();
                        jQuery("#au"+uid).remove();
                       } 
                    }
            });            
        }
    });
    
    jQuery(".fileDelete").live("click",function(){
       // alert("data");
     
        
      
      
        var pid=jQuery(this).attr("rel");
        var filename=jQuery(this).attr("val");
        jQuery("#loaderBox").css("display","block");
        $.ajax({
            async: false,
            url:baseurl+"ajax-task.php",
            type: 'POST',
            data: "type=deleteFile&pid="+pid+"&filename="+filename,
            success: function(data) { //alert(data)
                if(data==1)
                    jQuery(this).parent().parent().remove();
                
                jQuery("#loaderBox").css("display","none");
             
            }
        });            
        
    });
    
    jQuery("#uploader").pluploadQueue({
                		runtimes : 'html5,html4',
                		url : jQuery("#uploadurl").val(),
                		max_file_size : '10mb',
                		unique_names : true,
                		filters : [
                			{title : "Image files", extensions : "jpg,gif,png"},
                            {title : "Docs files", extensions : "doc,docx,xls,xlsx,ppt,pttx,pdf"},
                			{title : "Zip files", extensions : "zip,rar"}
                		]
    });
    
    
    jQuery("#simpleCountries").multiSelect({});
    
    jQuery(".datepicker").datepicker({ 
		defaultDate: +7,
		autoSize: true,
		appendText: '(yyyy-mm-dd)',
    	dateFormat: 'yy-mm-dd',
		numberOfMonths: 1
	});	
    
    jQuery(".datepicker").live("click", function(){
        jQuery(this).datepicker({ 
           	defaultDate: +7,
    		autoSize: true,
    		appendText: '(yyyy-mm-dd)',
    		dateFormat: 'yy-mm-dd',
            showOn:'focus'
        }).focus();
    });
    // file downlaod 
    
    jQuery(".searchClick").click(function(){
        
        jQuery("."+jQuery(this).attr("rel")).slideToggle("slow");
    })
    
    jQuery(".seachUpClck").click(function(){
        jQuery("."+jQuery(this).attr("rel")).slideUp("slow");
    })
    
    
    //sendremaind
    jQuery(".sendremaind").live("click",function(){
        var tid=jQuery(this).attr("rel");
        jQuery("#loaderBox").css("display","block");
        
        $.ajax({
            async: false,
            url:baseurl+"ajax-task.php",
            type: 'POST',
            data: "type=sendRemind&tid="+tid,
            success: function(data) {
                
                if(data==1){
                    jQuery(".nSuccess").css("display","block");
                    jQuery(".nSuccess").html('<p><strong>SUCCESS: </strong>Remaind Email is Successully Send</p>');
                }else{
                    jQuery(".nFailure").css("display","block");
                    jQuery(".nFailure").html(' <p><strong>FAILURE: </strong>Oops sorry. Some problem happen .Try again later</p>');
                }
                jQuery("#loaderBox").css("display","none");
            }
        });   
        
            
    });
    
    // contact list export 
    /*
    jQuery("#exportContactList").click(function(){
        
        jQuery("#loaderBox").css("display","block");    
        jQuery("#projectListBox").slideUp("slow");
        jQuery.ajax({
            async: false,
            url:baseurl+"contact-csv.php",
            type: 'POST',
            data: "" ,
            success: function(data) {
                //jQuery("#projectListBox").html(data);
                //jQuery("#projectListBox").slideDown("slow");
                jQuery("#loaderBox").css("display","none");
                
            }
        }); 
        
        return false;
    })
    */
    jQuery("#allProject").click(function(){
       
        if(jQuery(this).is(":checked")==true){
            jQuery("#projectDate").slideUp("slow");
        }
        else
            jQuery("#projectDate").slideDown("slow");

    })
    
    jQuery("#companyList").change(function(){
        var all;
        if(jQuery("#allProject").is(":checked"))
            all=1;
        else{
            all=0;
            if(jQuery("#projectFrom").val()=="" || jQuery("#projectTo").val()==""){
                alert("Please Enter Project Date");
                return false;
            }
            
        }
        
        jQuery("#loaderBox").css("display","block");    
        jQuery("#projectListBox").slideUp("slow");
        jQuery.ajax({
            async: false,
            url:baseurl+"ajax-report.php",
            type: 'POST',
            data: "type=getProjectList&all="+all+"&from="+jQuery("#projectFrom").val()+"&to="+jQuery("#projectTo").val()+"&cid="+jQuery(this).val()+"&cname="+jQuery("#companyList :selected").text() ,
            success: function(data) {
                jQuery("#projectListBox").html(data);
                jQuery("#projectListBox").slideDown("slow");
                jQuery("#loaderBox").css("display","none");
                
            }
        }); 
        
             
    })
    jQuery("#clickReport").click(function(){
                var all;
        if(jQuery("#allProject").is(":checked"))
            all=1;
        else{
            all=0;
            if(jQuery("#projectFrom").val()=="" || jQuery("#projectTo").val()==""){
                alert("Please Enter Project Date");
                return false;
            }
            
        }
        
        jQuery("#loaderBox").css("display","block");    
        jQuery("#projectListBox").slideUp("slow");
        jQuery.ajax({
            async: false,
            url:baseurl+"ajax-report.php",
            type: 'POST',
            data: "type=getProjectList&all="+all+"&from="+jQuery("#projectFrom").val()+"&to="+jQuery("#projectTo").val()+"&cid="+jQuery("#companyList").val()+"&cname="+jQuery("#cname").val() ,
            success: function(data) {
                jQuery("#projectListBox").html(data);
                jQuery("#projectListBox").slideDown("slow");
                jQuery("#loaderBox").css("display","none");
                
            }
        });    
    })
    
    // ----------------------------------
    //   clientwise reports 
    //  ---------------------------------
    
    jQuery("#clientReportForm").submit(function(){
        var all;
        if(jQuery("#allProject").is(":checked"))
            all=1;
        else{
            all=0;
            if(jQuery("#projectFrom").val()=="" || jQuery("#projectTo").val()==""){
                alert("Please Enter Project Date");
                return false;
            }
            
        }
        if(jQuery("#clientList").val()==""){
                alert("Please Enter Client");
                return false;
        }
             
    })
    
    // print report   
    jQuery(".printReport").live("click",function(){
        window.print();
        return false;
    }) 
    
    jQuery("#reportTab").click(function(){
        jQuery("#reportTabSub").slideToggle("slow");
    })
    /*
    jQuery(".exportReport").live("click",function(){
        
        
        var all;
        
        if(jQuery("#allProject").is(":checked"))
            all=1;
        else
            all=0;
        
        jQuery("#loaderBox").css("display","block");    
        jQuery("#projectListBox").slideUp("slow");
        
        jQuery.ajax({
            async: false,
            url:baseurl+"report-csv.php",
            type: 'POST',
            data: "type=companyWiseReport&all="+all+"&from="+jQuery("#projectFrom").val()+"&to="+jQuery("#projectTo").val()+"&cid="+jQuery(this).val()+"&cname="+jQuery("#companyList :selected").text() ,
            success: function(data) {
                jQuery("#projectListBox").html(data);
                jQuery("#projectListBox").slideDown("slow");
                jQuery("#loaderBox").css("display","none");
                
            }
        }); 
        
             
    })
    */
     
    /*
    jQuery(".fileDownload").live("click",function(){
         $.ajax({
            async: false,
            url:baseurl+"ajax-task.php",
            type: 'POST',
            data: "type=downloadFile&file="+jQuery(this).attr("rel"),
            success: function(data) {
                //jQuery("#tab2").html(data);
            }
        }); 
 
    });
    */
	/* Bars */

	jQuery(function () {
    var d2 = [[0.0, 29], [2.6, 13], [4.6, 46], [6.6, 30], [8.6, 48], [10.6, 22], [12.6, 40], [14.6, 32], [16.6, 39], [18.6, 16], [20.6, 27], [22.6, 22], [24.6, 2], [26.6, 45], [28.6, 23], [30.6, 28], [32.6, 30], [34.6, 40], [36.6, 20], [38.6, 47], [40.6, 12], [42.6, 49], [44.6, 28], [46.6, 15], [48.6, 24]];
	
    var plot = jQuery.plot(jQuery(".bars"),
           [ { data: d2} ], {
               series: {
                   bars: { 
					show: true,
					lineWidth: 0.5,
					barWidth: 0.85, 
					fill: true,
					fillColor: { colors: [ { opacity: 0.8 }, { opacity: 1 } ] },
					align: "left", 
					horizontal: false,
				   },
               },
               grid: { hoverable: true, clickable: true },
               yaxis: { min: 0, max: 50 },
			   xaxis: { min: 0, max: 50 },
             });

	});

    
      
});


// getProjectTabContent

function getProjectTabContent(type,activeTab,pid){ 
    jQuery(".nNote").css("display","none");
    jQuery("#loaderBox").css("display","block"); 
        $.ajax({
                async: false,
                url:baseurl+"ajax-project.php",
                type: 'POST',
                data: "ajaxtype=getProjectTabContent&tabtype="+type+"&pid="+pid,
                success: function(data) { 
                     $(activeTab).html(data);
                     $(activeTab).show();
                     jQuery("#loaderBox").css("display","none"); 
                }
     });    

}
/*
function chkNumiric($value){
			  var cell_ptrn=/^\+?\d+$/;
			  var cell_str= $value;
			  if(cell_str!=""){
				  if(cell_str.match(cell_ptrn)==null) 
					return 0;
                  else
                    return 1;
			  }
}


function emailCheck(email){
    var mail_ptrn=/^[0-9a-zA-Z]([-.\w]*[0-9a-zA-Z_+])*@([0-9a-zA-Z][-\w]*[0-9a-zA-Z]\.)+[a-zA-Z]{2,9}$/;
    var mail_str= email;
    if(mail_str.match(mail_ptrn)==null)
        return 0;
    else 
      return 1;
      
}
*/