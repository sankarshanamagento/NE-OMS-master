require(["jquery",'Magento_Ui/js/modal/modal'], function ($,modal) {
  
      $(document).ready(function() {
		
		
		
		$('#services_main_store').change(function(){
			   var siteurl=$('input[name=url]').attr('title');
			   
			   var formData = {storeid:$(this).val()}
			   $('body').loader('show');
				jQuery.ajax({
				url : siteurl,
				type: "POST",
				data : formData,
				success: function(data, textStatus, jqXHR)
				{
					
				  var reponse=JSON.parse(data);
				  var categoryoptions;
				jQuery('#services_main_categoryid').html("");
				 jQuery.each(reponse, function(idx, obj) {
	                 
					 categoryoptions +="<option value='"+idx+"'>"+obj+"</option>";
                   });
				   
				   jQuery('#services_main_categoryid').html(categoryoptions);
				   var categorylabel ="<option value='' selected='selected'>Categories</option>";
				   jQuery("#services_main_categoryid").prepend(categorylabel);
                   $('body').loader('hide');				   
					
				}
		   })
		   
		   })
		   
		   
		   $('#services_main_categoryid').change(function(){
			   var siteurl=$('input[name=url]').attr('title');
			   
			   var formData = {categoryid:$(this).val()}
			   $('body').loader('show');
				jQuery.ajax({
				url : siteurl,
				type: "POST",
				data : formData,
				success: function(data, textStatus, jqXHR)
				{
				  jQuery('#services_main_subcategoryid').html("");
				  var reponse=JSON.parse(data);
				  var categoryoptions;
				
				 jQuery.each(reponse, function(idx, obj) {
	                 
					 categoryoptions +="<option value='"+idx+"'>"+obj+"</option>";
                   });
				   
				   jQuery('#services_main_subcategoryid').html(categoryoptions);  
				   var categorylabel ="<option value='' selected='selected'>Sub Categories</option>";
				   jQuery("#services_main_subcategoryid").prepend(categorylabel);
                   $('body').loader('hide');				
				}
		   })
		   
		   })
		
		   $('input[type=file]').change(function(){

			   $("input").each(function () {
					   $(this).removeClass("required-entry _required");
				   }); 
			   $("select").each(function () {
					   $(this).removeClass("required-entry _required");
				   });  
			})
	 
           $('input').change(function(){
			   $('input[type=file]').removeClass("required-entry _required")
		   })
		   
		   $('select').change(function(){
			   $('input[type=file]').removeClass("required-entry _required")
		   })
		   
		   if($("#services_main_part_id").val()!="")
		   {
			   $('input[type=file]').removeClass("required-entry _required");
			   
			    $('input[name=partnumber]').change(function(){
			      $('input[name=url]').val("chnaged");
		   })
		   }
 

  
   
		
	});
	
	
	
	
	
	
			
			
			
			
	
	
       
   
  
});



