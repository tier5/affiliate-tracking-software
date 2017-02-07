
var flag1 = false;
var flag2 = false;

if ((document.getElementById('email'))) {
	document.getElementById('email').addEventListener("focusout", emailValidandAvailable);
}
if ((document.getElementById('confirmPassword'))) {
	document.getElementById('confirmPassword').addEventListener("focusout", passwordValid);
}
if ((document.getElementById('password'))) {
	document.getElementById('password').addEventListener("focusout", passwordValid);
}
if ((document.getElementById('register-submit-btn'))) {
	document.getElementById('register-submit-btn').addEventListener("click", submitForm);
	var register_button_name = 'register-submit-btn';
	var register_button_object = document.getElementById(register_button_name);
	register_button_object.disable = true;		
}



// function to include javscript files necessary 
function require(script)
{
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

function submitForm()
{
    var validPassword = passwordValid();
    var validEmail = emailValid();

    console.log(validPassword, validEmail);

	if (validPassword && validEmail) {
        emailAvailable(function() {
            document.getElementById('register-form').submit();
        });
    }
}

function emailValidandAvailable()
{
    emailValid();
    emailAvailable();
}

function emailValid()
{
	var return_field_name = 'Email_availability_result';
	var return_field_object = document.getElementById(return_field_name);
	var email_field_name = 'email';
	var email_field_object = document.getElementById(email_field_name);
	
	if ( !validEmail(email_field_object.value) ) {
		return_field_object.innerHTML = 'Invalid Email Address';
		$('#Email_availability_result').css('color', 'red');
		register_button_object.disable = true;

		return false;
	} else if (email.length < 6) { 
        document.getElementById(returnResult).innerHTML = 'Min length is 6';

        return false;
    } else {
		return_field_object.innerHTML = '';
		register_button_object.disable = false;
        return true;
	}
}

//function to check email availability
function emailAvailable(callback)
{
    var callback = callback || 0;
    var email_field_name = 'email';
    var returnResult = 'Email_availability_result';
    var button = register_button_name; // defined in global scope
    var email = document.getElementById(email_field_name).value;
    
    
    $.post("/session/checkForAvailableEmail", { 'email': email },
        function(result, status) {
            if (result == 0) {          
                //show that the email is available
                document.getElementById(returnResult).innerHTML = 'Available';
                $('#' + returnResult).css('color', 'green');
                document.getElementById(button).disabled = false;
                if (callback) {
                    callback();
                }
                return true
            } else {
                //show that the email is NOT available
                document.getElementById(returnResult).innerHTML = 'Not Available';
                $('#' + returnResult).css('color', 'red');
                document.getElementById(button).disabled = true;
                return false;
            }
    });  
}


function passwordValid()
{

	 if (document.getElementById('password').value.length < 8) {
    	document.getElementById('Confirm_password_result').innerHTML = 'Requirement: minimum 8 characters';
    	register_button_object.disable = true;
    	validPassword = false;
    }
	
    if ( document.getElementById('password').value != document.getElementById('confirmPassword').value ) {
    	document.getElementById('Confirm_password_result').innerHTML = 'Password mismatch';
    	$('#Confirm_password_result').css('color', 'red');
    	register_button_object.disable = true;
    	return false;
    } else {
    	document.getElementById('Confirm_password_result').innerHTML = 'Passwords Match';
    	$('#Confirm_password_result').css('color', 'green');
    	register_button_object.disable = false;
    	return true;
    }
}

function chkchk()
{
    if (document.getElementById('password').value.length < 8) {
        document.getElementById('Confirm_password_result').innerHTML = 'Requirement: minimum 8 characters';
        register_button_object.disable = true;
        return false;
    }
    
    if ( document.getElementById('password').value != document.getElementById('confirmPassword').value ) {
        document.getElementById('Confirm_password_result').innerHTML = 'Password mismatch';
        $('#Confirm_password_result').css('color', 'red');
        register_button_object.disable = true;
        return false;
    } else {
        document.getElementById('Confirm_password_result').innerHTML = 'Passwords Match';
        $('#Confirm_password_result').css('color', 'green');
        register_button_object.disable = false;
        return true;
    }
}

/*var adjustLogoMargins = function() {
	var headerHeight = eval($('header').height());
	var imageHeight = eval($('.logo img').height());

	// calculate top and bottom margins for logo
	var margins = (headerHeight - imageHeight) / 2;

	$('.logo').css('margin-top', margins);
	$('.logo').css('margin-bottom', margins);
	$('.logo img').css('display', 'block');
};

adjustLogoMargins();*/

//window.addEventListener("resize", adjustLogoMargins);

require("/js/checkAvailability.js");
