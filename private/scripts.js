/* script.js
 * JavaScript functions for Bookmark Services Application.
 * Author: Christina Holden
 * September 2017 */

/* Sets listeners for sign_up.html form */
 function setSignUpListeners () {
	USERNAME.addEventListener("blur", userValidate, false);
	EMAIL.addEventListener("blur", emailValidate, false);
	PASSWORD.addEventListener("blur", passwordValidate, false);
	PASSWORD.addEventListener("focus", passwordHint, false);
	CONFIRM.addEventListener("blur", confirmPassword, false);
 }

/* Sets listeners for log_in.html form */
function setLogInListeners() {
	USERNAME.addEventListener("blur", userValidate, false);
	PASSWORD.addEventListener("blur", passwordValidate, false);
}

/* Sets listeners for add_site.html form */
function setAddSiteListeners() {
	URL.addEventListener("blur", urlValidate, false);
}

/* Validates that a username has been entered.
 * Note: Graceful fall back for browsers that don't recognize required attribute. */
function userValidate() {
	if (!USERNAME.value) {
		document.querySelector("#username-invalid").innerHTML = "A username is required.";
	} else {
		document.querySelector("#username-invalid").innerHTML = "";
		return true;
	}
}

/* Validates that an email has been entered.
 * Note: Graceful fall back for browsers that don't recognize required attribute. */
function emailValidate() {
	if (!EMAIL.value) {
		document.querySelector("#email-invalid").innerHTML = "An email is required.";
	} else {
		document.querySelector("#email-invalid").innerHTML = "";
		return true;
	}
}

/* Provides instruction for a valid password when user focuses on the field. */
function passwordHint() {
	document.querySelector("#password-invalid").innerHTML = "Include uppercase, lowercase and numeric characters.";
}

/* Validates that the password includes the correct characters is of an appropriate length. */
function passwordValidate() {
	if (!PASSWORD.value) {
		document.querySelector("#password-invalid").innerHTML = "A password is required.";
	} 
	if (PASSWORD.value.length < 8) {
		document.querySelector("#password-invalid").innerHTML = "Password must be at least 8 characters.";
	} else {
		var pswPattern = new RegExp("^(?=.*\\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\\s).*$", "i");
		var isValid = PASSWORD.value.search(pswPattern) >= 0;
		if (!isValid) {
				document.querySelector("#password-invalid").innerHTML = "Password should include uppercase, lowercase and numbers.";
			} else {
				document.querySelector("#password-invalid").innerHTML = "";
				return true;
			}
		}
}

/* Confirms password to ensure that the user entered the password intended. */
function confirmPassword() {
	if (!CONFIRM.value) {
		document.querySelector("#confirm-invalid").innerHTML = "Please confirm your password.";
	} else {
		if (!(CONFIRM.value == PASSWORD.value)) {
				document.querySelector("#confirm-invalid").innerHTML = "Passwords don't match.";
			} else {
				document.querySelector("#confirm-invalid").innerHTML = "";
				return true;
			}
	}
}

/* Provides help to the user to provide a valid url. */
function urlValidate() {
	if (!URL.value) {
		document.querySelector("#siteURL-invalid").innerHTML = "Copy and paste the web address into the field.";
	} else {
		document.querySelector("#siteURL-invalid").innerHTML = "";
	}
		
}

/* Prevents the submission of invalid data by validating before post. */
function validateUserData() {
	if (userValidate() &&
		emailValidate() &&
		passwordValidate() &&
		confirmPassword()) {
	return true;	
	} else return false;	
}

   
	