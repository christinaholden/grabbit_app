<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <title>GRABBIT Bookmarking Service</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="css/reset.css"/>
    <link rel="stylesheet" href="css/bookmark_style.css"/>
    <link href="https://fonts.googleapis.com/css?family=Princess+Sofia" rel="stylesheet"> 
</head>

<body>

    <!-- Loads a personal header if a user is logged in. -->
    <?php 
        if (isset($_SESSION['userID'])) { ?>
            <div id="identifier">
                <span>Welcome <?php echo $_SESSION['username'] ?></span><a href="private/log_out.php"><button id=log-out>Log Out</button></a><a href="add_site.html"><button id=add-site>Add Site</button></a>
            </div>
       <?php } else { ?>
            <div id="identifier">
                <span>Grabbit Bookmarking Service</span><a href="log_in.html"><button id=log-in>Log In</button></a>
            </div>
      <?php }
    ?>

    <header>
        <h1><a href="index.php">Grabbit</a></h1>
    </header>
    <section id="content">
         <ul>

            <!-- Loads the user's bookmarks from the database for display. -->
            <?php 
                require_once('private/functions.php');
                $db = db_connect();
                $userID = $_SESSION['userID'];

                $query = "SELECT site_url, site_name, siteID FROM site_bookmarks WHERE siteID IN ";
                $query .= "(SELECT siteID FROM favorites WHERE userID='" . $userID . "')";
                $result_set = mysqli_query($db, $query);

                // Loop through bookmark result set retrieved from favorites table.
                while($site = mysqli_fetch_assoc($result_set)) { ?>
                <li>
                    <!-- Site info -->
                    <a target="blank" href="<?php echo $site['site_url'] ?>">
                        <img class="favicon" height="25" width="25" src="http://www.google.com/s2/favicons?domain=<?php echo $site['site_url'] ?>" />
                        <span class="text"><?php echo $site['site_name'] ?></span>
                    </a>
                    <!-- Each bookmark has it's own 'remove bookmark' form. -->
                    <form action="private/remove_site.php" method="POST" onclick="return confirm('Remove this site from Favorites?')" />
                        <input type="hidden" name="site-id" value="<?php echo $site['siteID'] ?>" >
                        <button type="submit" class="remove-btn" aria-label="Remove bookmark">-</button>
                    </form>
                </li>               
            <?php }  ?>
            
         </ul>
    </section>
    <footer>
         <span>&copy; 2017 GRABBIT</span>
    </footer>
</body>

</html>

<?php db_disconnect($db); ?>


