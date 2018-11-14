<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Import the application classes
require_once('include/classes.php');

// Create an instance of the Application class
$app = new Application();
$app->setup();

// Declare a set of variables to hold the username and password for the user
$username = "";
$password = "";

// Declare an empty array of error messages
$errors = array();

// If someone has clicked their email validation link, then process the request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

	if (isset($_GET['id'])) {
		
		$success = $app->processEmailValidation($_GET['id'], $errors);
		if ($success) {
			$message = "Email address validated. You may login.";
		}

	}

}

// If someone is attempting to login, process their request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	// Pull the username and password from the <form> POST
	$username = $_POST['usernameField'];
	$password = $_POST['password'];

	// Attempt to login the user and capture the result flag
	$result = $app->login($username, $password, $errors);

	// Check to see if the login attempt succeeded
	if ($result == TRUE) {

		// Redirect the user to the topics page on success
		header("Location: otp.php");
		exit();

	}

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
  	<link href="css/style.css" type="text/css" rel="stylesheet" media="screen">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<!--1. Display Errors if any exists 
	2. Display Login form (sticky):  Username and Password -->

<body class="section no-pad-bot light-blue darken-4" id="index-banner">
	<?php include 'include/header.php'; ?>

	<h2 class="amber-text text-accent-2">Login</h2>

	<?php include('include/messages.php'); ?>
	
	<div>
		<form method="post" action="login.php" id="usernameForm" name="usernameForm">
			
			<input type="text" name="usernameField" id="usernameField" placeholder="Username" value="<?php echo $username; ?>" />
			<br/>

			<input type="password" name="password" id="password" placeholder="Password" value="<?php echo $password; ?>" />
			<br/>

			<input type="submit" value="Play Ball!" name="login" />

			<br>
  			<input type="radio" name="save" id="saveLocal" value="local"> Save To Local Storage<br>
  			<input type="radio" name="save" id="saveSession" value="storage"> Save to Session Storage<br>
  			<input type="radio" name="save" id="noSave" value="delete"> Delete Storage
		</form>
		<script>
		function doSubmit(e) {
			var saveLocal = document.getElementById("saveLocal").checked;
			var saveSession = document.getElementById("saveSession").checked;
		if (saveLocal) {
			console.log("Saving username to local storage");
			var username = document.getElementById("usernameField").value;
			localStorage.setItem("username",username);
			sessionStorage.removeItem("username");
		} else if (saveSession) {
			console.log("Saving username to session storage");
			var username = document.getElementById("usernameField").value;
			sessionStorage.setItem("username",username);
			localStorage.removeItem("username");
		} else {
			localStorage.removeItem("username");
			sessionStorage.removeItem("username");
		}
	}

	function doPageLoad(e) {
		console.log("Reading username from local/session storage");
		var usernameLocal = localStorage.getItem("username");
		var usernameSession = sessionStorage.getItem("username");
		if (usernameLocal) {
			document.getElementById("saveLocal").checked = true;
			document.getElementById("usernameField").value = usernameLocal;
		}
		else if (usernameSession) {
			document.getElementById("saveSession").checked = true;
			document.getElementById("usernameField").value = usernameSession;
		} else {
			document.getElementById("noSave").checked = true;
		}
	}

// Add event listeners for page load and form submit
window.addEventListener("load", doPageLoad, false)
document.getElementById("usernameForm").addEventListener("submit", doSubmit, false);
		</script>
	<h5 class="white-text">&#34;I looked down and there was a dime on the ground. 
				    I picked it up, put it in my left shoe. &#133;We beat Clemson that day&#133;
				    I taped the dime in my shoe so I wouldn&#39;t lose it, and made sure that I wore it throughout the season. 
				    We were 12&#150;0 and won the national championship, and I&#39;m sure the dime did it.&#34;
	</h5>
	</div>
	<a href="register.php" class="white-text">Need to create an account?</a>
	<br/>
	<a href="reset.php" class="white-text">Forgot your password?</a>
	<?php include 'include/footer.php'; ?>
	<script src="js/site.js"></script>
	<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
 	<script src="js/materialize.js"></script>
	<script src="js/init.js"></script>
</body>
</html>
