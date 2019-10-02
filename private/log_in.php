<?php
// Processor for log_in.html f Verifies user and starts a session.

	session_start();
	require_once('functions.php');
	$db = db_connect();

	// Retrieve posted data
	$username = $_POST['username'];
	$password = $_POST['password'];

	// Set flag for validation
	$okay = true;

	// Validate that data exists
	if (!isset($username) || 
	    !isset($password)) {
	    $okay = false;
	 }

	if ($okay == true) {
		// Look for user in the database using username
    	$query = "SELECT user_name, userID FROM user_accounts WHERE user_name = '" . sanitize($db, $username) . "'";
		$result_set = mysqli_query($db, $query);
		$account= mysqli_fetch_assoc($result_set);
		$userID = $account['userID'];

		// If the user is in the database, verify password
		if ($userID !== null) {

			 // If password verifies, log the user in and load bookmarks at home.php
			if (password_verify($password, $userID) ){
				$_SESSION['userID'] = $userID;
				$_SESSION['username'] = $username;
				db_disconnect($db);
				redirect("../home.php");

			// If password doesn't verify, inform user and redirect to log_in.html
			} else {
				db_disconnect($db);
	    		echo ("<SCRIPT type='text/javascript'>
    			window.alert('There was a problem. Please try again.')
    			window.location.href='../log_in.html';
    			</SCRIPT>");
			} 
		
		// If user does not exist, inform user and redirect to sign_up.html	
		} else if ($userID == null) {
			db_disconnect($db);
	    	echo ("<SCRIPT type='text/javascript'>
    		window.alert('You do not have an account yet.')
    		window.location.href='../sign_up.html';
    		</SCRIPT>");
		} 
	
	// If flag is false, inform user of a problem and redirect to log_in.html	
    } else if ($okay == false) {
    	db_disconnect($db);
	 	echo ("<SCRIPT type='text/javascript'>
    	window.alert('There was a problem. Please try again.')
    	window.location.href='../log_in.html';
    	</SCRIPT>");
    }

?>