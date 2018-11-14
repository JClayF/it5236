<?php

	// Assume the user is not logged in and not an admin
	$isadmin = FALSE;
	$loggedin = FALSE;
	
	// If we have a session ID cookie, we might have a session
	if (isset($_COOKIE['sessionid'])) {
		
		$user = $app->getSessionUser($errors); 
		$loggedinuserid = $user["userid"];

		// Check to see if the user really is logged in and really is an admin
		if ($loggedinuserid != NULL) {
			$loggedin = TRUE;
			$isadmin = $app->isAdmin($errors, $loggedinuserid);
		}

	} else {
		
		$loggedinuserid = NULL;

	}


?>  	<link href="css/style.css" type="text/css" rel="stylesheet" media="screen">
	<div class="nav">
		<a href="index.php" class="amber-text text-accent-2">Home</a>
		&nbsp;&nbsp;
		<?php if (!$loggedin) { ?>
			<a href="login.php" class="amber-text text-accent-2">Login</a>
			&nbsp;&nbsp;
			<a href="register.php" class="amber-text text-accent-2">Register</a>
			&nbsp;&nbsp;
		<?php } ?>
		<?php if ($loggedin) { ?>
			<a href="list.php" class="amber-text text-accent-2">List</a>
			&nbsp;&nbsp;
			<a href="editprofile.php" class="amber-text text-accent-2">Profile</a>
			&nbsp;&nbsp;
			<?php if ($isadmin) { ?>
				<a href="admin.php" class="amber-text text-accent-2">Admin</a>
				&nbsp;&nbsp;
			<?php } ?>
			<a href="fileviewer.php?file=include/help.txt" class="amber-text text-accent-2">Help</a>
			&nbsp;&nbsp;
			<a href="logout.php" class="amber-text text-accent-2">Logout</a>
			&nbsp;&nbsp;

		<?php } ?>
	</div>
	<h1 class="white-text">IT 5236</h1>
