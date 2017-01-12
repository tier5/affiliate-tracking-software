  {{ content() }}
<div class="row">
        <div class="col-xs-12">
            <div class="portlet box red">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-globe"></i>Twilio Phone Number
                    </div>
                    <div class="tools"> </div>
                </div>
		    <div class="portlet-body form">
		        <form class="form-horizontal" id="userform" role="form" method="post" autocomplete="off">
		            <div class="form-group" style="padding-top: 30px;">
		                <label for="name" class="col-md-2 control-label">Country:</label>
		                <div class="col-md-6">
							<select name="country" id="country_select" class="form-control" style="width: 100%;" >
								<option value="">SELECT</option>
								<?php foreach($countries as $cid=>$country){ ?>

									<option value="<?php echo $cid; ?>"><?php echo $country; ?></option>
								<?php } ?>
								
							</select>
		                </div>
		            </div>
		            <div class="form-group">
		                <label for="email" class="col-md-2 control-label">Type Of Number :</label>
		                <div class="col-md-6 number_type">
		                	
		                </div>
		            </div>
		            <div class="form-group">
		                <label for="phone" class="col-md-2 control-label">Area Code:</label>
		                <div class="col-md-6">
		                    <input id="area_code" name="area_code" class="form-control"  type="number">
		                </div>
		            </div>
		            <div class="form-group">
		                <label for="phone" class="col-md-2 control-label">Number Contains:</label>
		                <div class="col-md-6">
		                    <input id="Contains" name="Contains" class="form-control"  type="number">
		                </div>
		            </div>
		            <div class="form-group">
		                <div class="col-md-offset-2 col-md-10">
		                    <input id="gather_info" class="btnLink btnPrimary" value="Get Available Number" style="height: 42px; line-height: 14px; padding: 15px 36px; text-align: left;" type="button">
		                	OR
		                	<a href="" id="purchased_number_list" class="" >Get Number Already Purchased</a>
		                </div>
		            </div> 
		            <div class="form-group"></div>       
		        </form>
		    </div>




            </div>
        </div>
</div>
<div id="result_valx">
</div>
    <script>
    $( document ).ready(function() {
		$('#country_select').change(function(){
			$(".number_type").html("<span>loading......</span>");
			var country_select=$(this).val();
			$('#gather_info').prop('disabled', true);
			if(country_select!=""){
				$.ajax({
				type: 'POST',
				url: "/twilio/getTypeNumber", 
				data:{country_select : country_select},
				success: function(result){
					if(result){
					//alert(result);
					$('#gather_info').prop('disabled', false);
					$(".number_type").html("");
					$(".number_type").html(result);
					}
				}
				});
			}
		});
		$('#gather_info').click(function(){
			$("#result_valx").html("");
			var country_select=$('#country_select').val();
			var number_type_select="";
			var area_code=$('#area_code').val();
			var Contains=$('#Contains').val();
			if(country_select!=""){
			$("#result_valx").html("<span>loading......</span>");
				number_type_select=$('#number_type_select').val();
				$.ajax({
				type: 'POST',
				url: "/twilio/getAvailableNumber", 
				data:{country_select : country_select,number_type_select:number_type_select,area_code:area_code,Contains:Contains},
				success: function(result){
					if(result){
						$("#result_valx").html("");
					 	$("#result_valx").html(result);
					}
				}
				});
			}else{
			alert("Please Select Country!!!");
			}
		});
		$('#purchased_number_list').click(function(){
		
		$("#result_valx").html("");
		$("#result_valx").html("<span>loading......</span>");
			$.ajax({
				type: 'POST',
				url: "/twilio/getPreviousNumber", 
				data:{},
				success: function(result){
					if(result){
					$("#result_valx").html("");
					 	$("#result_valx").html(result);	
					 	
					}
				}
				});
		return false;
		});
    });
 
    
    </script>