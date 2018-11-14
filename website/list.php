<?php
	
// Import the application classes
require_once('include/classes.php');

// Create an instance of the Application class
$app = new Application();
$app->setup();

// Declare an empty array of error messages
$errors = array();

// Check for logged in user since this page is protected
$app->protectPage($errors);

$name = "";

// Attempt to obtain the list of things
$things = $app->getThings($errors);

// Check for url flag indicating that there was a "no thing" error.
if (isset($_GET["error"]) && $_GET["error"] == "nothing") {
	$errors[] = "Lists not found.";
}

// Check for url flag indicating that a new thing was created.
if (isset($_GET["newthing"]) && $_GET["newthing"] == "success") {
	$message = "List successfully created.";
}
	
// If someone is attempting to create a new thing, the process the request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	// Pull the title and thing text from the <form> POST
	$name = $_POST['name'];
	$attachment = $_FILES['attachment'];

	// Attempt to create the new thing and capture the result flag
	$result = $app->addThing($name, $attachment, $errors);

	// Check to see if the new thing attempt succeeded
	if ($result == TRUE) {

		// Redirect the user to the login page on success
	    header("Location: list.php?newthing=success");
		exit();

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

<!--1. Display Errors if any exists 
	2. If no errors display things -->
<body class="section no-pad-bot light-blue darken-4" id="index-banner">
	<?php include 'include/header.php'; ?>
	<h2 class="amber-text text-accent-2">Sports You're Following</h2>
	<?php include('include/messages.php'); ?>
	
	<div class="search">
		<form action="list.php" method="post">
			<label for="search" class="white-text">Filter:</label>
			<input type="text" id="search" name="search"/>
			<input type="submit" value="Apply" />
		</form>
	</div>
	<ul class="things">
		<?php if (sizeof($things) == 0) { ?>
		<li class="white-text">No lists found</li>
		<?php } ?>
		<?php foreach ($things as $thing) { ?>
		<li>
			<a href="thing.php?thingid=<?php echo $thing['thingid']; ?>" class="amber-text text-accent-2"><?php echo $thing['thingname']; ?></a>
			<span class="author white-text"><?php echo $thing['thingcreated']; ?></span>
		</li>
		<?php } ?>

	</ul>
	<div class="newthing">
		<form enctype="multipart/form-data" method="post" action="list.php">
			<input type="text" name="name" id="name" size="81" placeholder="Enter a List name" value="<?php echo $name; ?>" />
			<br/>
			<label for="attachment">Add an image, PDF, etc.</label>
			<input id="attachment" name="attachment" type="file" class="white-text">
			<br/>
			<input type="submit" name="start" value="Create New List" />
			<input type="submit" name="cancel" value="Cancel" />
		</form>
	<h3 class="white-text">
		&#34;The best way to win a game is not to lose it.&#34;
	</h3>
	</div>
	
	<?php include 'include/footer.php'; ?>
	<script src="js/site.js"></script>
	<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
 	<script src="js/materialize.js"></script>
	<script src="js/init.js"></script>
</body>
</html>
