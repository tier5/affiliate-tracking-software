{{ content() }}



<div id="locationlist">
    {{ content() }}
<input type="hidden" id="user_id" value="{{user_id}}">
 </div>
 <div id="twilio-contain">
      <?php if($twilio_details!=0){?>
        <table id="basic-datatables" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
            <tr>
            <th>Number</th>
            <th>Friendly Number</th>
            <th>Booking Date & Time</th>
            <th>Action</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($twilio_details as $key => $mobile_number) { ?>
            <tr>
              <td><?php echo $mobile_number['friendly_name']; ?></td>
              <td><?php echo $mobile_number['phone_number']; ?></td>
              <td><?php echo $mobile_number['created']; ?></td>
              <td><a  href="/twilio/releseThisnumber/<?php echo base64_encode($mobile_number['phone_number']);?>||<?php echo base64_encode($mobile_number['friendly_name']);?>||"><input id="gather_info" class="btnLink btnPrimary" value="Release This Number" style="height: 42px; line-height: 14px; padding: 15px 36px; text-align: left;" type="button"></a></td>
            </tr>
          <?php } ?>
          </tbody>
        </table>
        <?php } else{?>
            <form class="form-horizontal" id="userform" role="form" method="post" autocomplete="off">
                <div class="form-group" style="padding-top: 30px;">
                    <label for="name" class="col-md-2 control-label">Country:</label>
                    <div class="col-md-6">
              <select name="country" id="country_select" class="form-control" style="width: 100%;" >
                <option value="">SELECT</option>
                <?php foreach($countries as $cid=>$country){ ?>

                  <option value="<?php echo $cid; ?>" <?php if($cid=="US"){?> selected <?php }?>><?php echo $country; ?></option>
                <?php } ?>
                
              </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="phone" class="col-md-2 control-label">Area Code:</label>
                    <div class="col-md-6">
                        <input id="area_code" name="area_code" class="form-control"  type="text">
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-10">
                        <input id="gather_info" class="btnLink btnPrimary" value="Get Available Number" style="height: 42px; line-height: 14px; padding: 15px 36px; text-align: left;" type="button">
                      
                    </div>
                </div> 
                <div class="form-group"></div>       
            </form>
            <div id="result_valx">
            </div>
        <?php } ?>
      </div>

      <script>
    $( document ).ready(function() {
    
    $('#gather_info').click(function(){
      $("#result_valx").html("");
      var country_select=$('#country_select').val();
      var number_type_select="";
      var area_code=$('#area_code').val();
      var Contains="";
      if(country_select!=""){
      $("#result_valx").html("<span>loading......</span>");
        
        $.ajax({
        type: 'POST',
        url: "/twilio/getAvailableNumberagency", 
        data:{country_select : country_select,number_type_select:number_type_select,area_code:area_code,Contains:Contains, user_id :$('#user_id').val()},
        success: function(result){
          if(result){
          alert(result);
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
        url: "/twilio/getPreviousNumberAgency", 
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