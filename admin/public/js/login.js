//Interactive Chart
jQuery(window).ready(function ($) {
  if ( $('#expirationval-m') && $('#expirationval-m') ) {
    var month = $('#expirationval-m').val();
    var year = $('#expirationval-y').val();

    document.getElementById('register-submit-btn').disabled = true;
    if (month != '') {
      $('.expiry').val(month + ' / ' + year);
      $("input[name='expiry-month']").val(month);
      $("input[name='expiry-year']").val(year);
      //console.log('month:' + $('.expiry-month').val() + ':year:' + $("input[name='expiry-year']").val());
    }
  }
  

  	        //the min chars for email  
  	        var min_chars = 6;  
  	  
  	        //result texts  
  	        var characters_error = 'Minimum amount of chars is 6';  
  	        var checking_html = 'Checking if available...';  
  	  
  	        //when button is clicked  
  	        $('#email').focusout(function() {  
  	            //run the character number check  
  	            if($('#email').val().length < min_chars){  
  	                //if it's bellow the minimum show characters_error text '  
  	                $('#email_availability_result').html(characters_error);  
  	            }else{  
  	                //else show the cheking_text and run the function to check  
  	                $('#email_availability_result').html(checking_html);  
  	                checkAvailability();  
  	            }  
  	        });  
  	  

  	  
  	  
  	  //function to check username availability  
  	function checkAvailability(){  
  	  
  	        //get the username  
  	        var email = $('#email').val();  
  	  
  	        //use ajax to run the check  
  	        $.post("/session/checkForAvailableEmail", { email: email },  
  	            function(result){  
  	                //if the result is 1  
  	                if(result == 1){  
  	                    //show that the email is available  
  	                    $('#email_availability_result').html(email + ' is Available'); 
  	                    document.getElementById('register-submit-btn').disabled = false;
  	                }else{ 
  	                    //show that the email is NOT available  
  	                    $('#email_availability_result').html(email + ' is not Available');
  	                    document.getElementById('register-submit-btn').disabled = true;
  	                  
  	                }  
  	        });  
  	  
  	}  
  
});
