require(["jquery",'Magento_Ui/js/modal/modal'], function ($,modal) {

      $(document).ready(function() {

		$('#company_main_org_type_id').css('pointer-events','none');
		$('#company_main_parent_id').css('pointer-events','none');
        $('#company_main_country').css('pointer-events','none');
		$('#company_main_status').css('pointer-events','none');

		$('#new_video_screenshot').change(function(){
		    var file = $('#new_video_screenshot')[0].files[0];
	         getBase64(file);
		});

		$(".video-cancel-button").click(function(){
			$("#title").removeClass("mage-error","required-entry");
		    $( ".titleerrorlable" ).css("display","none");
		    $("#description").removeClass("mage-error","required-entry");
		    $(".descerror").css("display","none");
		    $( ".fileerror" ).css("display","none");
			$('#myModel').modal('closeModal');
		})

		$("#doclist").on("click",".clickme", function(){


			$("#new_video_screenshot").replaceWith($("#new_video_screenshot").val('').clone(true));
			    
				$("#title").removeClass("mage-error","required-entry").attr("disabled",false);
                $( ".titleerrorlable" ).css("display","none");
                $("#description").removeClass("mage-error","required-entry").attr("disabled",false);
                $(".descerror").css("display","none");
                $( ".fileerror" ).css("display","none");
                $("#new_video_screenshot").css("display","none");
                $(".uploadlabel").css("display","none");

              $('#myModel').modal('openModal');
			  var pid=$(this).attr('id');
			  $("#item_id").val(pid);
			  $("#title").val($("#"+pid+"title").val());
			  $("#description").val($("#"+pid+"description").val());
			  $("#comments").val($("#"+pid+"comments").val());
			  $("#doclink").attr("href",$("#"+pid+"filedata").val());
			  $("#title").attr("disabled","disabled");
              $("#description").attr("disabled","disabled");
            });

		$("#savedocument").on("click",function(){
        //$('#myModel').modal('openModal');
    var docid=$("#item_id").val();
    var orgid = $("#orgid").val();
    var title = $("#title").val();
	var description = $("#description").val();
	var comments = $("#comments").val();
	var basestring = $("#base64").html();
	var siteurl = $("#file_name").val();


	 if((orgid!="" && title!="" && description!="" && basestring!="") || (orgid!="" && title!="" && description!="" && basestring=="" && docid!="") ){
	 	$('#myModel').modal('openModal');
	 		$("#title").removeClass("mage-error","required-entry");
		    $( ".titleerrorlable" ).css("display","none");
		    $("#description").removeClass("mage-error","required-entry");
		    $(".descerror").css("display","none");
		    $( ".fileerror" ).css("display","none");

		     $.ajax({

		                    url:siteurl,
		                    type:'POST',
		                    showLoader: true,
		                    //dataType:'json',
		                    data: {orgid:orgid,docid:docid,title:title,description:description,comments:comments,basestring:basestring},
		                    success:function(response){
		                       $('#doclist').html(response);
							    $('#myModel').modal('closeModal');
		                    }

		                });
              }
              else
              {
              	$('#myModel').modal('openModal');
              	if(title=="")
	              	{
	              		$("#title").addClass("mage-error");
	              		$( ".titleerrorlable" ).css("display","block");
	              	}
	             else
		             {
		             	$("#title").removeClass("mage-error","required-entry");
		             	$( ".titleerrorlable" ).css("display","none");
		             }


              	if(description=="")
	              	{
	              		$("#description").addClass("mage-error");
	              		$(".descerror").css("display","block");

	              	}
	             else
		             {
		             	$("#description").removeClass("mage-error","required-entry");
		             	$(".descerror").css("display","none");

		             }

              	if(basestring=="")
	              	{
	              		$( ".fileerror" ).css("display","block");
	              	}
	            else
		            {
		            	$( ".fileerror" ).css("display","none");

		            }

              }


    return false;
});


	});





	var options = {
                type: 'popup',
                responsive: true,
                innerScroll: false,
                title: 'Document Upload',
                buttons: [{

                    class: 'video-cancel-button',
                    click: function () {
                        this.closeModal();
                    }
                }]
            };
            var popup = modal(options, $('#myModel'));
            $("#add_video_button").on("click",function(){
            	$("#new_video_screenshot").replaceWith($("#new_video_screenshot").val('').clone(true));
            	$("#item_id").val("");
             $("#title").val("");
              $("#comments").val("");
              $("#description").val("");
            	$("#title").removeClass("mage-error","required-entry").attr("disabled",false);
                $( ".titleerrorlable" ).css("display","none");
                $("#description").removeClass("mage-error","required-entry").attr("disabled",false);
                $(".descerror").css("display","none");
                $( ".fileerror" ).css("display","none");
                $('#myModel').modal('openModal');

                $("#new_video_screenshot").css("display","block");
                $(".uploadlabel").css("display","block");
            });







});


function getBase64(file) {
   var reader = new FileReader();
   reader.readAsDataURL(file);
   reader.onload = function () {
     console.log(reader.result);
	 document.getElementById("base64").innerHTML=reader.result;

   };
   reader.onerror = function (error) {
     console.log('Error: ', error);
   };

}
