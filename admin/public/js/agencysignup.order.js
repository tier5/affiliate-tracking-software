/**
 * 
 */
$(document).ready(function() {
	if (document.getElementById('URL')) {
		document.getElementById('URL').addEventListener("focusout", checkAvailableSubDomain);
	}
	if (document.getElementById('OwnerEmail')) {
		document.getElementById('OwnerEmail').addEventListener("focusout", checkAvailableEmail);
	}
	if (document.getElementById('CreditCardNumber')) {
		document.getElementById('CreditCardNumber').addEventListener("focusout", verifyCreditCardNumber);
	}
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
	
	
	
	function checkAvailableSubDomain() {

		var min_chars = 8; 
		var characters_error = 'Minimum of 8 Letters';
		var checking_html = 'Checking if available...';
		
		var return_field_name = 'URL_availability_result';
		var return_field_object = document.getElementById(return_field_name);
		var URL_field_name = 'URL';
		var URL_field_object = document.getElementById(URL_field_name);
		
		if (URL_field_object.value.length <= 0) {
			return_field_object.value = "";
			return false;
		}
		
		if (!/^[a-zA-Z]/.test(URL_field_object.value)) {
			return_field_object.innerHTML = "Letters only";
			return false;
		}
		
	    if ( URL_field_object.value.innerHTML < min_chars ) {
	    	return_field_object.value = characters_error;
	    	return false;
	    } else {
	    	return_field_object.innerHTML = checking_html;  
	        checkSubDomainAvailability(URL_field_object.value, 'BigGreenSubmit', return_field_name);
	        return true;
	    }    
	}

	
	function checkAvailableEmail() {
				
		//the min chars for email  
		var return_field_name = 'Email_availability_result';
		var return_field_object = document.getElementById(return_field_name);
		var email_field_name = 'OwnerEmail';
		var email_field_object = document.getElementById(email_field_name);
		var min_chars = 6;
		if (!validEmail(email_field_object.value)) {
			return_field_object.innerHTML = "Invalid Email Address";
			return false;
		}
	    return checkEmailAvailability(email_field_object.value, 'BigGreenSubmit', return_field_name);

	}
	
	
	function passwordVerification() {
		var password_field_name = 
        $('#ConfirmPassword').focusout(function() {
            if ($('#Password').val() != $('#ConfirmPassword').val()) {
            	document.getElementById('Confirm_password_result').innerHTML = 'Password mismatch';
            } else {
            	document.getElementById('Confirm_password_result').innerHTML = 'Passwords Match';
            }
            if ($('#Password').val().length < 6) {
            	document.getElementById('Confirm_password_result').innerHTML = 'Requirement: more than 6';
            }
            //$('#payment-form').submit();
        });
	}

	
	function submitPayment() {
		var $form = $('#payment-form');
		$form.submit(function (event) {
		    // Disable the submit button to prevent repeated clicks:
		$form.find('.submit').prop('disabled', true);
		
		// Request a token from Stripe:
		Stripe.card.createToken($form, stripeResponseHandler);
		
		// Prevent the form from being submitted:
		      return false;
		});
	}

	
	function verifyCreditCardNumber() {
		var CreditCardNumber = $('#CreditCardNumber').val();
		
		if (validCreditCardNumber(CreditCardNumber) == 1) {
			document.getElementById('Valid_credit_card_number_result').innerHTML = 'Valid Number';
		} else {
			document.getElementById('Valid_credit_card_number_result').innerHTML = 'Invalid Number';
		}
	}
	
	
	function stripeResponseHandler(status, response) {
	      // Grab the form:
		var $form = $('#payment-form');
		
		if (response.error) { // Problem!

			// Show the errors on the form:
			$form.find('.payment-errors').text(response.error.message);
			$form.find('.submit').prop('disabled', false); // Re-enable submission
			
		} else { // Token was created!
			
			// Get the token ID:
			var token = response.id;
			
			// Insert the token ID into the form so it gets submitted to the server:
			$form.append($('<input type="hidden" name="stripeToken">').val(token));
			
			// Submit the form:
			$form.get(0).submit();
	    }
	}

	
	require("/js/checkAvailability.js");
	passwordVerification();
	submitPayment();
});