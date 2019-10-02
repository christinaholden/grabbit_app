<?php 
// Processor for the add_site.html form. Adds a bookmark to the user's list of favorites.

	session_start();
	require_once('functions.php');
    $db = db_connect();

    // Retrieve posted data
    $url = $_POST['siteURL'];

    if (filter_var($url, FILTER_VALIDATE_URL) && isValidAddress($url)){

    	// Identify user
	    $user = $_SESSION['userID'];

	    // Check to see if the site the user is adding already exists in the database
	    $query = "SELECT siteID, site_rating, site_name FROM site_bookmarks WHERE site_url = '" . sanitize($db, $url) . "'";
	    $result_set = mysqli_query($db, $query);
	    $bookmark = mysqli_fetch_assoc($result_set);
	    $id = $bookmark['siteID'];

	    // If the site is in the database
	    if ($id !== null) {

	    	// Check to see if the site the user is adding already exists as a favorite
	    	$query = "SELECT siteID, userID FROM favorites WHERE siteID = '" . $id . "' AND userID = '" . $user . "' LIMIT 1";
	    	$result = mysqli_query($db, $query);
	    	$favorite = mysqli_fetch_assoc($result);
	    	$site = $favorite['siteID'];

	    	// If the site does not already exist as a favorite, add it and update its rating
	    	if  ($site === null) {
	    		addFavorite ($id, $user, $db);
	    		upvoteBookmark ($bookmark['site_name'], $db);

	    	// IF the site does exist as a favorite, inform the user 
	    	} else {
    			db_disconnect($db);

	    		echo ("<SCRIPT type='text/javascript'>
    			window.alert('You have already added this bookmark.')
    			window.location.href='../home.php';
    			</SCRIPT>");
	    	} 
	    } 

	    // If the site does not exist, add it to the database and add it as a favorite
	    else if ($id === null) {	
	    	$newID = addBookmark ($url, $db);
	    	addFavorite ($newID, $user, $db);
	    } 

	// If the URL is invalid, redirect to add_site.html form
	 } else {
    	db_disconnect($db);
    	
	 	echo ("<SCRIPT type='text/javascript'>
    	window.alert('URL is invalid.')
    	window.location.href='../add_site.html';
    	</SCRIPT>");
	 }
    
    db_disconnect($db);
    redirect("../home.php");
 ?>