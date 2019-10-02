<?php
// Processor for the Log Out button. Ends the user's session.

	session_start();
	require_once('functions.php');

	// If user logged in, mark the user as logged out
	if (isset($_SESSION['userID'])) {
		unset($_SESSION['userID']); 		
	}

	redirect("../index.php");
?>