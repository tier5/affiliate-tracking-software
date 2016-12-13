



//function to check custom_domain availability
function checkSubDomainAvailability(custom_domain, button, returnResult) {
        //get the Subdomain

        //use ajax to run the check
        $.post("/session/checkForAvailableSubDomain", { 'custom_domain': custom_domain },
            function(result, status) {
        	if (result == 0) {
                //show that the email is available  
            	document.getElementById(returnResult).innerHTML = 'Available';
            	$('#' + returnResult).css('color', 'green');
                document.getElementById(button).disabled = false;
            } else {
                //show that the email is NOT available
            	document.getElementById(returnResult).innerHTML = 'Not Available';
            	$('#' + returnResult).css('color', 'red');
                document.getElementById(button).disabled = true;
            }
        	return result;
        });
}


function validEmail(email) {
    var re = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
    return re.test(email);
}

function validCreditCardNumber(value) {
  // accept only digits, dashes or spaces
	if (/[^0-9-\s]+/.test(value)) return false;

	// The Luhn Algorithm. It's so pretty.
	var nCheck = 0, nDigit = 0, bEven = false;
	value = value.replace(/\D/g, "");

	for (var n = value.length - 1; n >= 0; n--) {
		var cDigit = value.charAt(n),
			  nDigit = parseInt(cDigit, 10);

		if (bEven) {
			if ((nDigit *= 2) > 9) nDigit -= 9;
		}

		nCheck += nDigit;
		bEven = !bEven;
	}

	return (nCheck % 10) == 0;
}