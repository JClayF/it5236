<?php

// Import the application classes
require_once('include/classes.php');

// Declare an empty array of error messages
$errors = array();

// Create an instance of the Application class
$app = new Application();
$app->setup();

// Check for logged in user since this page is protected
$app->protectPage($errors);

// Declare a set of variables to hold the details for the user
$userid = "";
$username = "";
$email = "";
$isadminFlag = FALSE;

$sessionid = $_COOKIE['sessionid'];
$user = $app->getSessionUser($sessionid, $errors);
$loggedinuserid = $user["userid"];

// If someone is accessing this page for the first time, try and grab the userid from the GET request
// then pull the user's details from the database
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

	// Get the userid
	if (!isset($_GET['userid'])) {

		$userid = $loggedinuserid;

	} else {

		$userid = $_GET['userid'];
		
	}
	
	// Attempt to obtain the user information.
	$user = $app->getUser($userid, $errors);
	
	if ($user != NULL){
		$username = $user['username'];
		$email = $user['email'];
		$isadminFlag = ($user['isadmin'] == "1");
		$password = "";
	}

// If someone is attempting to edit their profile, process the request	
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	// Get the form values 
	$userid   = $_POST['userid'];
	$username = $_POST['username'];
	$email    = $_POST['email'];
	$password = $_POST['password'];
	if (isset($_POST['isadmin']) && $_POST['isadmin'] == "isadmin") {
		$isadminFlag = TRUE;
	} else {
		$isadminFlag = FALSE;
	}

	// Attempt to update the user information.
	$result = $app->updateUser($userid, $username, $email, $password, $isadminFlag, $errors);
	
	// Display message upon success.
	if ($result == TRUE){
		$message = "User successfully updated.";
		$user = $app->getUser($userid, $errors);
	}
		
}

?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>clayfulcher.me</title>
	<meta name="description" content="Clay Fulcher's personal website for Sports Lists!">
	<meta name="author" content="Clay Fulcher">
	<link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen">
  	<link href="css/style.css" type="text/css" rel="stylesheet" media="screen">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="section no-pad-bot light-blue darken-4" id="index-banner">
	<?php include 'include/header.php'; ?>

	<h2 class="amber-text text-accent-2">Edit Profile</h2>
	
	<?php include 'include/messages.php'; ?>	
	
	<div>
		<form action="editprofile.php" method="post">
			<input type="hidden" name="userid" value="<?php echo $userid; ?>" />
			<input type="text" name="username" id="username" placeholder="Pick a username" value="<?php echo $username; ?>" />
			<br/>
			<input type="password" name="password" id="password" placeholder="Enter a password" value="<?php echo $password; ?>" /> (optional)
			<br/>
			<input type="text" name="email" id="email" placeholder="Enter your email" size="40" value="<?php echo $email; ?>" />
			<?php if ($loggedinuserid != $userid) { ?>
			<br/>
			<input type="checkbox" name="isadmin" id="isadmin" <?php echo ($isadminFlag ? "checked=checked" : ""); ?> value="isadmin" />
			<label for="isadmin">Grant admin rights</label>
			<?php } ?>
			<br/>
			<input type="submit" value="Update profile" />
		</form>
	</div>
	<h3 class="white-text">
		&#34;One more time for the greatest team in America&#33;&#34;
	</h3>
	<?php include 'include/footer.php'; ?>
	<script src="js/site.js"></script>
	<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
 	<script src="js/materialize.js"></script>
	<script src="js/init.js"></script>

</body>
</html>
