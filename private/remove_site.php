<?php
// Processor for the Remove site form. Deletes the bookmark from the user's list.
// Note: Javascript runs confirmation on this action before the form is sent.

	session_start();	
	require_once('functions.php');
    $db = db_connect();

    // Retrieves data from the form.
    $id = $_POST['site-id'];

    // Retrieves user's identity.
    $user = $_SESSION['userID'];

    // Retrieve site_name.
    $query = "SELECT site_name from site_bookmarks WHERE siteID = '" . $id . "'" ;
    $result = mysqli_query($db, $query);
    $site= mysqli_fetch_assoc($result);
    $name = $site['site_name'];

    // Remove the site.
    deleteFavorite($id, $user, $db);
    // update site_rating.
    downvoteBookmark($name, $db);

    db_disconnect($db);
    redirect("../home.php");
?>