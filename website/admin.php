<?php
	
// Import the application classes
require_once('include/classes.php');

// Create an instance of the Application class
$app = new Application();
$app->setup();

// Declare an empty array of error messages
$errors = array();

// Check for logged in admin user since this page is "isadmin" protected
// NOTE: passing optional parameter TRUE which indicates the user must be an admin
$app->protectPage($errors, TRUE);

// Attempt to obtain the list of users
$users = $app->getUsers($errors);


// If someone is adding a new attachment type
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	if ($_POST['attachmenttype'] == "add") {
		
		$name = $_POST['name'];;
		$extension = $_POST['extension'];;
	
		$attachmenttypeid = $app->newAttachmentType($name, $extension, $errors);
		
		if ($attachmenttypeid != NULL) {
			$messages[] = "New attachment type added";
		}

	}

}

// Attempt to obtain the list of users
$attachmentTypes = $app->getAttachmentTypes($errors);

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
	<h2 class="amber-text text-accent-2">Admin Functions</h2>
	<?php include 'include/messages.php'; ?>
	<h3 class="amber-text text-accent-3">User List - Click to go to profile</h3>
	<ul class="users amber-text text-accent-2t">
		<?php foreach($users as $user) { ?>
			<li><a href="editprofile.php?userid=<?php echo $user['userid']; ?>" class="white-text"><?php echo $user['username']; ?></a></li>
		<?php } ?>
	</ul>
	<h3 class="amber-text text-accent-3">Valid Attachment Types</h3>
	<ul class="attachmenttypes white-text">
		<?php foreach($attachmentTypes as $attachmentType) { ?>
			<li><?php echo $attachmentType['name']; ?> [<?php echo $attachmentType['extension']; ?>]</li>
		<?php } ?>
		<?php if (sizeof($attachmentTypes) == 0) { ?>
			<li>No attachment types found in the database</li>
		<?php } ?>
	</ul>
	<div class="newattachmenttype section no-pad-bot grey lighten-1 white-text">
		<h4 class="white-text">Add Attachment Type</h4>
		<form enctype="multipart/form-data" method="post" action="admin.php">
			<label for="name" class="white-text">Name</label>
			<input id="name" name="name" type="text">
			<br/>
			<label for="extension" class="white-text">Extension</label>
			<input id="extension" name="extension" type="text">
			<br/>
			<input type="hidden" name="attachmenttype" value="add" />
			<input type="submit" name="addattachmenttype" value="Add type" />
		</form>
	</div>
	<h4 class="white-text">
		&#34;At Georgia Southern, we don&#39t cheat. That costs money and we dont have that.&#34;
	</h4>
	<?php include 'include/footer.php'; ?>
	<script src="js/site.js"></script>
	<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
 	<script src="js/materialize.js"></script>
	<script src="js/init.js"></script>

</body>
</html>
