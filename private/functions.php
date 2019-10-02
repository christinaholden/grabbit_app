<?php
// Library of functions for PHP processing.

	/* Connects to the MySQL database
	 * Throws an error if unsuccessful
	 * Returns the connection */
	function db_connect() {
		$db_connection = new mysqli('localhost', 'webuser', '000Assign2_1', 'db_grabbit');
		if (mysqli_connect_error()) {
    		die('Connect Error ('.mysqli_connect_errno().') '.mysqli_connect_error());
		}
		return $db_connection;
	}

	/* Closes an open connection to the MySQL database
	 * Param $db_connection: the open connection */
	function db_disconnect($db_connection) {
		if(isset($db_connection)) {
			$db_connection->close();
		}
	}

	/* Adds a URL to the favorites table
	 * Param $id: the siteID of the URL in the site_bookmarks table
	 * Param $user: the userID of logged in user  */
	function addFavorite ($id, $user, $db) {
		$query = "INSERT INTO favorites (siteID, userID) VALUES ('" . $id . "', '" . $user . "')";
    	mysqli_query($db, $query);
	}

	/* Adds a URL to the site_bookmarks table; calls upvoteBookmark
	 * Param $url: the url of the site to be added
	 * Param $db: the open database connection 
	 * Returns id of new bookmark */
	function addBookmark ($url, $db) {
		$name = parse_url($url, PHP_URL_HOST);
		$name = str_replace("www.", "", $name);
		$query = "INSERT INTO site_bookmarks (site_url, site_name, site_rating) VALUES ('"  . sanitize($db, $url) . "', '" . sanitize($db, $name) . "', 0)";
    	mysqli_query($db, $query);
    	$newID = mysqli_insert_id ($db);
    	upvoteBookmark($name, $db);
    	return $newID;
	}

	/* Updates the rating of a bookmark in the site_bookmarks table
	 * Param $bookmark: an associative array containing the values to be added
	 * Param $db: the open database connection */
	function upvoteBookmark ($name, $db) {
		$query = "SELECT site_rating, siteID from site_bookmarks WHERE site_name = '" . $name . "'" ;
		$result_set = mysqli_query($db, $query);

		while($site = mysqli_fetch_assoc($result_set)) {
			$rating = $site['site_rating'];
			$rating++;
			$query = "UPDATE site_bookmarks SET site_rating = '" . $rating . "' WHERE siteID = '" . $site['siteID'] . "' LIMIT 1";
			mysqli_query($db, $query);
		}
	}

	function downvoteBookmark($name, $db) {
		$query = "SELECT site_rating, siteID from site_bookmarks WHERE site_name = '" . $name . "'" ;
		$result_set = mysqli_query($db, $query);

		while($site = mysqli_fetch_assoc($result_set)) {
			$rating = $site['site_rating'];
			if ($rating >= 0) {
				$rating--;
			}
			$query = "UPDATE site_bookmarks SET site_rating = '" . $rating . "' WHERE siteID = '" . $site['siteID'] . "' LIMIT 1";
			mysqli_query($db, $query);
		}
	}

	/* Removes a favorite from the favorites table that is associated with the logged in user
	 * Param $id: the siteID of the URL in the site_bookmarks table
	 * Param $user: the userID of logged in user 
	 * Param $db: the open database connection */
	function deleteFavorite ($id, $user, $db) {
 		$query = "DELETE FROM favorites WHERE siteID = '" . $id . "' AND  userID = '" . $user ."'";
		mysqli_query($db, $query);
	}

	/* Inserts a new user into the user_accounts table
	 * Param $username: data provided in the sign_up.html form for username; must be unique
	 * Param $email: data provided in the sign_up.html form for user's email: must be valid
	 * Param $hashedPassword: an encrypted password 
	 * Param $db: the open database connection */
	function addUserAccount($username, $email, $hashedPassword, $db) {
		$query = "INSERT INTO user_accounts (user_name, user_email, userID) VALUES ('"  . sanitize($db, $username) . "', '" . sanitize($db, $email) . "', '"  . $hashedPassword . "')";
    	mysqli_query($db, $query);
	}

	/* Checks that an ip address in an URL is valid
	 * Param $url: the URL to be checked
	 * Returns a boolean */
	function isValidAddress($url) {
		$url = parse_url($url);
		$host = $url['host'];
		if ($ip = gethostbyname($host)) {
			return true;
		} else {return false;}
	}	

	/* Abbreviated form of PHP redirect function
	 * Sends a new request 
	 * Param $location: url of new page requested; sent in the header */
	function redirect ($location) {
		header("Location: " . $location);
		exit;
	}

	/* Abbreviated form of PHP escape function
	 * Escapes characters in the string to make it safe for use with the database
	 * Param $string: string to be prepared for use with the database; 
	 * usually a variable provided by a user
	 * Param $db: the open database connection */
	function sanitize($db, $string) {
		return mysqli_real_escape_string($db, $string);
	}	

?>