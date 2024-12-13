/*
 *		auth.js	--	 This javascript procedure contains the routines needed
 *					to support the authorization process in the client.
 */

//	var auth		 = "";		// is now in the context.
	var loginAttempt = 0;
	var whoAreYouAttempt = 0;
	var securityCodeAttempt = 0;

	// Launch the login form, immediately.
//	showModalForm("loginForm");


// Add the following jQuery event handlers.
$(document).ready(function(){			

	// Execute the "continue" button's click event when the user hit's ENTER.
	$("form").keyup(function(e){
		var key = e.which;
		if(key == 13)  // the enter key code
		  {
			$("#continue").click();
			return false;  
		  }
	});
});

/* 	
	// Give the user ID field the focus.
	$('#loginFormDiv').on('shown.bs.modal', function () {
	  $('#user_id5ea06028df13c').trigger('focus')
	})
 */



function cbChecked(checkboxName){
	var elementName = "column[" + checkboxName + "]";
	var cbElement = document.getElementsByName(elementName)[0];
	if (typeof cbElement === 'object' && cbElement !== null){
		if (cbElement.checked){ return true; }
	}
	return false ;
}


/*
 *	checkPasswords() -- Check that the passwords match.
 */
function checkPasswords() {
	// Make sure both passwords match.
	var password1 = document.getElementsByName(name="column[password]")[0].value;
	var password2 = document.getElementsByName(name="column[password2]")[0].value;
	
	if (password1 == "") {
		alert("You need to provide a password.");
		return false;
	}
	
	if (password2 == "") {
		alert("You need to confirm your password.");
		return false;
	}
	
	if (password1 !== password2){
		alert("Sorry, but your password entries don't match.  Please try again.");
		return false;
	}
	return true;
}
 
 
/*
 *	friendlyName() - returns a user-friendly name based on the authorization.
 */
function friendlyName(){
	if (!auth){return "";}
	var friendlyName = auth['contact_noSearch'];
	if (friendlyName === "") { return auth['user_id'];}
	else {
		var nameArray = friendlyName.split(", ");
		return nameArray[1];
	}
}


/*
 *	loggedin -- checks to see if a valid authentication is available.  If 
 *				not, control is passed to the login page.
 */ 
function loggedin(){

	if (auth){
		if (typeof(auth) == "object") {
			if (auth.hasOwnProperty('authCode')) { return true; }
		}
	}
	alert("You must log in to use this feature.");
	location.replace("/login.php");
}


/*
 *	login -- accepts the user credentials from the loginForm, authenticates
 *				the user, and loads the user's authorization into the 
 *				server-side session, as well as the client-side context.
 */ 
function login(){
	
	// Redirect if the user needs to register a new account.
	if (cbChecked("register")){ 
		location.replace("register.php");
		return;
	}

	// Redirect if the user forgot their password.
	if (cbChecked("forgot")){ 
		location.replace("whoAreYou.php");
		return;
	}

	var queryStr = queryStrFromForm("loginForm");
	loginNew(queryStr);

}


/*
 *	loginNew -- accepts user credentials from either the loginForm or registerForm, 
 *				authenticates the user, and loads the user's authorization into the 
 *				server-side session, as well as the client-side context.
 */ 
function loginNew(queryStr){
	
	// Here's a sample REST API call to authenticate the user.
	// https://vm.demo.pubassist.com/common/rest/auth.php/auth?column[user_id]=rlawrence41&column[password]=<ron's_password>
	
	var restURI = "/common/rest/auth.php/auth";
	restURI += "?" + queryStr;

	// Capture the referring URL.
	// NOTE: document.referrer DOESN'T work.
	// var referrer = document.referrer;
	
	// The referring URL MUST be rendered into the document.
//	alert("Referred here from: " + referringURL);

	// 	Make sure a referring URL has been set. 
	//	Otherwise, this page simply loops around to itself!
	if (referringURL == "") {referringURL = "/";}
	
	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4) {
			
//alert(this.responseText);

			// Save the response to an element to register the change.
			$("#restResponse").html(this.responseText);

			if (this.status == 200) {
				
				var message = "<h3>Success!  One moment to render the page you requested.</h3>";
				document.getElementById("messageDiv").innerHTML = message;
				// Return to the referring page.
				location.replace(referringURL);
			}
			else {
				alert("Your username or password are not correct.");
				loginAttempt++ ;
				if (loginAttempt > 3) {
					location.replace("authenticationFailed.html");
				}
			}
		}
	}
	xhttp.open("GET", restURI, true);
	xhttp.send();
	
}


/*
 *	logout - removes the user authorization from the session.
 */
function logout(destinationURL="/"){
	 
	// Remove the authorization from the session.

	// Here's a sample REST API call to authenticate the user.
	// https://vm.demo.pubassist.com/common/rest/auth.php/auth?column[user_id]=rlawrence41&column[password]=<ron's_password>
	
	var url = "/common/rest/logout.php";
	
	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {

			// Save the response to an element to register the change.
			$("#restResponse").html(this.responseText);

			// Clear the authorization in the client.
			if (typeof auth !== 'undefined') {auth = ""; }
			
			// Reset the user profile menu option.
			$("#loginNav").text("My Account");
			
			// Announce the logout action.
			alert("You have successfully signed out.");
			
			// Redirect to the home page.
			location.replace(destinationURL);
			
		}
	}
	xhttp.open("GET", url, true);
	xhttp.send();

}


/*
 *	register() -- calls the REST API to add a new user to the database.
 */
function register() {

	// Capture the form data.
	var jsonStr = jsonFromForm("registerForm");
	var jsonData = JSON.parse(jsonStr);
	var queryStr = "column[user_id]=" + jsonData.user_id + "&column[password]=" + jsonData.password ;

	if (!checkPasswords()) {return;}

	var restURI = "/common/rest/auth.php/auth" ;
//	alert ("Updating user: " + jsonData.user_id + " with: " + jsonStr);

	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4) {

			// Save the response to an element to register the change.
			$("#restResponse").html(this.responseText);
			
			if (this.status == 200) {

				var message = "<p>Update was successful.</p>";
				document.getElementById("messageDiv").innerHTML = message;
				
				// Log the new user in.
				loginNew(queryStr);
			}
			else {
				alert ("Sorry, but your user Id is already in use.  Please try another.");
			}
		} 
	}
	xhttp.open("PUT", restURI, true);
//	xhttp.setRequestHeader('Content-Type', 'application/json');	// Expect a JSON encoded string.
	xhttp.send(jsonStr);

}


/*
 *	resetPassword() -- calls the REST API to update an instance in the database.
 */
function resetPassword() {

	// Capture the form data.
	var jsonStr = jsonFromForm("resetPasswordForm");
	var jsonData = JSON.parse(jsonStr);

	if (!checkPasswords()) {return;}
	
	// Here's a sample REST API call to authenticate the user.
	// https://vm.demo.pubassist.com/common/rest/auth.php/auth?column[user_id]=rlawrence41&column[password]=<ron's_password>

	var restURI = "/common/rest/auth.php/auth?" ;
//	alert ("Updating user: " + jsonData.user_id + " with: " + jsonStr);

	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {

			// Save the response to an element to register the change.
			$("#restResponse").html(this.responseText);

			var message = "<p>Update was successful.</p>";
			document.getElementById("messageDiv").innerHTML = message;
			
			location.replace("/");

		} 
	}
	xhttp.open("PATCH", restURI, true);
//	xhttp.setRequestHeader('Content-Type', 'application/json');	// Expect a JSON encoded string.
	xhttp.send(jsonStr);

}


/*
 *	securityCode - Checks the submitted security code against the one generated
 *				for this session.
 */
function securityCode(){

	// Redirect if the user forgot their password.
	if (cbChecked("resend")){ 
		location.replace("whoAreYou.php");
	}

	// Give the user 3 tries to enter their security code.
	securityCodeAttempt++ ;	// Should be global.
	if (securityCodeAttempt > 3) {
		auth = "";
		location.replace("authenticationFailed.html");
	}

	// Capture the query string from the form.
	var queryStr = queryStrFromForm("securityCodeForm");
	if (queryStr.length == 0){ 
		// If no data was provided, bail out...
		alert("Please enter the security code we sent you.");		
		return; 
	}

//alert("Searching for: " + queryStr);

	var url = "checkSecurityCode.php?" + queryStr;

	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4) {
			
			// Save the response to an element to register the change.
			$("#restResponse").html(this.responseText);
			
			// Return a value of true if the user was found.
			if (this.status == 200){

//********************************* Successful retrieval! *******************************
				var message = "<p>One moment please.</p>";
				document.getElementById("messageDiv").innerHTML = message;
				location.replace("resetPassword.php");
			}
			else {
				alert(this.responseText);
			}
		}
	}
	xhttp.open("GET", url, true);
	xhttp.send();

}


/*
 *	whoAreYou -- accepts the user credentials from the whoAreYouForm, authenticates
 *				the user, and loads the user's authorization into the 
 *				server-side session, as well as the client-side context.
 */ 
function whoAreYou(){

	// Give the user 3 tries to find their account record.
	whoAreYouAttempt++ ;	// Should be global.
	if (whoAreYouAttempt > 3) {
		auth = "";
		location.replace("authenticationFailed.html");
	}

	// Capture the query string from the form.
	var queryStr = queryStrFromForm("whoAreYouForm");


	if (queryStr.length == 0){ 
		// If no data was provided, bail out...
		alert("Please supply your username, email, and/or cell phone in order to reset your password.");		
		return; 
	}


	// Capture the notification method.
	var notify = document.getElementById("notifyMail");
	if (notify.checked) {queryStr += "&notify=email";}
	else {queryStr += "&notify=text";}

	var url = "getSecurityCode.php?" + queryStr;
//alert("AJAX call to: " + url + queryStr);

	// Sample authorization returned...
	/*
	[{"count":"1","page":1,"perPage":10},
	[{"id":"1","user_id":"rlawrence","contact_no":"38135","contact_noSearch":"LAWRENCE, RON","email":"rlawrence@pubassist.com","phone":"802-310-8085","authCode":"2000"}]]
	*/

	// Instantiate the request.
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4) {
			
			// Save the response to an element to register the change.
			$("#restResponse").html(this.responseText);
			
			// Return a value of true if the user was found.
			if (this.status == 200){

				// Make sure ONLY one user was returned.
				var result = JSON.parse(this.responseText);
				if (result[0]['count'] == 1) {

//********************************* Successful retrieval! *******************************

					var message = "<p>One moment please.</p>";
					document.getElementById("messageDiv").innerHTML = message;
					location.replace("securityCode.php");
				}
				else {
					alert("You need to provide at least 2: User Id, email, and/or cell phone.");
				}
			}
			else {

				// If the user was not found.
				alert("We can't find these identifiers in our system.");				
			}
		}
	}
	xhttp.open("GET", url, true);
	xhttp.send();

}
