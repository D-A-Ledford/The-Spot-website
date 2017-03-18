<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>The Spot</title> 
	<script src="scripts/spot.js"></script>
	<link rel="stylesheet" href="styles/styles2.css">
</head>
<body>
<div class ="fixed">	
	<header>
		<h1 hidden="hidden">The Spot</h1>
		<img id ="mobileheader" width="200" height="200" alt="The Spot" src="images/logo.png">

	
	
<nav class="wrapper">
	<ul class="row">
		<li class="col-2 navlink">
			<a href="index.php">Home</a>
		</li>
		<li class="col-2 navlink">
	
			<a href="spotus.php">&quot;Spot&quot;<br>Us</a>
		</li>

		<li class="col-2" id="header">
			<img id ="center" width="400" height="400" alt="The Spot" src="images/headersplat.png">
		</li>
		<li class="col-2 navlink">
			<a href="sandwich.php">Menu</a>
		</li>
		<li class="col-2 navlink">
			<a href="aboutus.php">About<br>Us</a>
		</li>
	</ul>
</nav>
</header>

<?PHP
	require_once "connect.php";
	
	$sgl = "SELECT * FROM navmenu";
	$stmt = $db->prepare($sgl);
	$stmt->execute();
	
?>

<div class ="nav2">
<ul>

	<?php
	while ($row = $stmt->fetch(PDO::FETCH_OBJ))
	{
		?>
		<li><a class ="active" href = "<?php echo $row->urllink ; ?>"><?php echo $row->navlabel; ?></a></li>
		<?php
	}	
		?>

</ul>
</div>

<div class="nav3">
	<ul>
		<li><a class ="active" href = "sandwich.php">Sandwiches</a></li>
		<li><a class ="active" href = "soup.php">Soups</a></li>
		<li><a class ="active" href = "drinks.php">Drinks</a></li>
		<li><a class ="active" href = "sides.php">Sides</a></li>
	</ul>

</div>
</div>

<div class="spacer">
    &nbsp;
</div>
<main class="wrapper">