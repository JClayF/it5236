<?php
	
// Import the application classes
require_once('include/classes.php');

// Create an instance of the Application class
$app = new Application();
$app->setup();

// Declare an empty array of error messages
$errors = array();

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
	<div class="wrapper">
	<?php include 'include/header.php'; ?>
	<h2 class="amber-text text-accent-2">Welcome to the Georgia Southern Lists Website!</h2>
	<p class="white-text"> 
		<br>
		<br>
		This is a website for discussing the differnt sports that Georgia Southern participates in!. Look for Erk Russell quotes on each page!
		Students currently registered  may <a href="register.php" class="amber-text text-accent-2">create an account</a> or proceed directly to the 
		<a href="login.php" class="amber-text text-accent-2">login page</a>.
		<br>
		<br>
		<h5 class="white-text">&#34;I&#39;m gonna say it one more time. We are Georgia Southern. Our colors are blue and white. 
					We call ourselves the Bald Eagles. We call our offense the Georgia Power Company&#133;and that&#39;s a terrific name for an offense. 
					Our snap count is &#39;rate, hike.&#39; We practice on the banks of Beautiful Eagle Creek and that&#39;s in Statesboro, Georgia&#150;the gnat capital of America. 
					Our weekends begin on Thursday. The co-eds outnumber the men 3&#150;to&#150;2. They&#39;re all good looking and they&#39;re all rich. And folks, you just can&#39;t beat that&#133;and you just can&#39;t beat Georgia Southern. 
					And you ain&#39;t seen nothin yet.&#34;</h5>
	</p>
	<?php include 'include/footer.php'; ?>
	</div>
	<script src="js/site.js"></script>
	<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
 	<script src="js/materialize.js"></script>
	<script src="js/init.js"></script>
	</body>
</html>