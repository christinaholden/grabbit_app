<?php
// Processor for the sign_up.html form. Verifies uniquess of user name and adds user to database.
	session_start();
	require_once('functions.php');
    $db = db_connect();

    // Retrieve data from the form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Set flag for validation
    $okay = true;

    // Validate that data exists
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || 
    	!isset($username) || 
    	!isset($password)) {
    	$okay = false;
    }


    if ($okay == true) {
    	// Look for use of the user name to ensure uniqueness
		$query = "SELECT user_name  FROM user_accounts WHERE user_name = '" . sanitize($db, $username) . "'";
		$result_set = mysqli_query($db, $query);
		$account= mysqli_fetch_assoc($result_set);
		$exists = $account['user_name'];
		
		// If username does not exist, create a new user and start a session
		if ($exists === null) {
			$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
			addUserAccount($username, $email, $hashedPassword, $db);
			$_SESSION['userID'] = $hashedPassword;
			$_SESSION['username'] = $username;
		} 

		// If username already taken, inform the user and redirect back to sign_up.html.
		else {
		 	echo ("<SCRIPT type='text/javascript'>
	    	window.alert('This user name is in use.')
	    	window.location.href='../sign_up.html';
	    	</SCRIPT>");
			} 
    } 
    // If flag is false, inform user of a problem and redirect to sign_up.html.
    else if ($okay == false) {
		db_disconnect($db);
	 	echo ("<SCRIPT type='text/javascript'>
    	window.alert('There was a problem. Please try again.')
    	window.location.href='../sign_up.html';
    	</SCRIPT>");
	}
    
    // Redirect to home.php.
	db_disconnect($db);
    redirect("../home.php");
?>