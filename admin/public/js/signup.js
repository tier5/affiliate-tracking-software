
$(document).ready(function() {
	if ((document.getElementById('email'))) {
		document.getElementById('email').addEventListener("focusout", checkAvailableEmail);
	}
	if ((document.getElementById('confirmPassword'))) {
		document.getElementById('confirmPassword').addEventListener("focusout", passwordVerification);
	}
	if ((document.getElementById('password'))) {
		document.getElementById('password').addEventListener("focusout", passwordVerification);
	}
	if ((document.getElementById('register-submit-btn'))) {
		document.getElementById('register-submit-btn').addEventListener("click", submitForm);
		var register_button_name = 'register-submit-btn';
		var register_button_object = document.getElementById(register_button_name);
		register_button_object.disable = true;		
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
	

	function submitForm() {
		document.getElementById('register-form').submit();
	}
	
	function checkAvailableEmail() {
				
		var return_field_name = 'Email_availability_result';
		var return_field_object = document.getElementById(return_field_name);
		var email_field_name = 'email';
		var email_field_object = document.getElementById(email_field_name);
		
		if ( !validEmail(email_field_object.value) ) { 
			return_field_object.innerHTML = 'Invalid Email Address';
			register_button_object.disable = true;
			return false;
		} else {
			return_field_object.innerHTML = '';
			register_button_object.disable = false;
		}
		
	    return checkEmailAvailability(email_field_object.value, register_button_name, return_field_name);
	}
	
	
	function passwordVerification() {
		
        if ( document.getElementById('password').value != document.getElementById('confirmPassword').value ) {
        	document.getElementById('Confirm_password_result').innerHTML = 'Password mismatch';
        	register_button_object.disable = true;
        } else {
        	document.getElementById('Confirm_password_result').innerHTML = 'Passwords Match';
        	register_button_object.disable = false;
        }
        
        if (document.getElementById('password').value.length < 8) {
        	document.getElementById('Confirm_password_result').innerHTML = 'Requirement: minimum 8 characters';
        	register_button_object.disable = true;
        }

	}

	
	require("/js/checkAvailability.js");
});