/**
 * Send a message to team
*/
function doContact() {
	var message = {
		name : document.getElementById("contact-name").value,
		email : document.getElementById("contact-email").value,
		subject : document.getElementById("contact-subject").value,
		message : document.getElementById("contact-message").value
	};

	// Basic check name
	if(message.name.length <= 1 || message.name.length > 64) {
		alert("The name must be between 1 and 64 characters length");
		return;
	}

	// Basic check email
	if(message.email.length <= 5 || message.email.length > 96 || message.email.indexOf("@") <= 0 || message.email.indexOf(".") === -1) {
		alert("The email must be between 5 and 86 characters length, including a '@' and a '.'");
		return;
	}

	// Basic check subject
	if(message.subject.length <= 1 || message.subject.length > 256) {
		alert("The subject must be between 1 and 256 characters length");
		return;
	}

	var request = new a.ajax({
		url : "contact",
		method : "POST",
		data : message
	},
	function(result) {
		if(result === "ok") {
			alert("Message has been successfully sended to team");
		} else {
			alert("An error occurs, can't send email: " + result);
		}
	},
	function() {
		alert("An error occurs, can't send email");
	});

	// Sending
	request.send();
};