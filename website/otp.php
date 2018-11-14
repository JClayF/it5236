<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Import the application classes
require_once('include/classes.php');

// Create an instance of the Application class
$app = new Application();
$app->setup();

// Declare a set of variables to hold the username and password for the user
$otp = "";

// Declare an empty array of error messages
$errors = array();


// If someone is attempting to login, process their request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	// Check to see if the login attempt succeeded

		// Redirect the user to the topics page on success
		header("Location: list.php");
		exit();


}

if (isset($_GET['register']) && $_GET['register']== 'success') {
	$message = "Registration successful. Please check your email. A message has been sent to validate your address.";
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

<!--1. Display Errors if any exists 
	2. Display Login form (sticky):  Username and Password -->

<body class="section no-pad-bot light-blue darken-4" id="index-banner">

	<?php include 'include/header.php'; ?>

	<h2 class="amber-text text-accent-2">Please enter your one time password</h2>

	<?php include('include/messages.php'); ?>
	
	<div>
		<form method="post" action="otp.php">
			
			<input type="text" name="opt" id="otp" placeholder="One Time Password" value="<?php echo $otp; ?>" />
			<br/>

			<input type="submit" value="Submit Your Code" name="login" />
		</form>
	<h4 class="white-text">&#34;If we score, we may win. If they never score, we&#39;ll never lose.&#34;
	</div>
	<?php include 'include/footer.php'; ?>
	<script src="js/site.js"></script>
	<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
 	<script src="js/materialize.js"></script>
	<script src="js/init.js"></script>

</body>
</html>
