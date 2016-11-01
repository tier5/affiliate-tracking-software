//Interactive Chart
jQuery(window).ready(function ($) {
	// function to include javscript files necessary 
	function require(script) {
	    $.ajax({
	        url: script,
	        dataType: "script",
	        async: false,           // <-- This is the key
	        success: function () {
	            // all good...
	        },
	        error: function () {
	            throw new Error("Could not load script " + script);
	        }
	    });
	}
	require("/js/checkAvailability.js");
	
	if ( $('#expirationval-m') && $('#expirationval-m') ) {
		var month = $('#expirationval-m').val();
		var year = $('#expirationval-y').val();
		
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
	    if ($('#email').val().length < min_chars) {
	        //if it's bellow the minimum show characters_error text '  
	        $('#email_availability_result').html(characters_error);  
	    } else {  
	        //else show the cheking_text and run the function to check  
	        $('#email_availability_result').html(checking_html);  
	      checkEmailAvailability();  
	    }
	});


});
