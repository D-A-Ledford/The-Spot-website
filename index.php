<?php
	session_start();
	include_once "header.php";
	//Database Connection
	require_once "connect.php";
?>
    <script src="scripts/slideshow.js"></script>
	<script src="scripts/tinymce.js"></script>
	<script src="scripts/html5shiv.js"></script>
	<script src="scripts/jquery.js"></script>
	<script src="scripts/slideshow.js"></script>
	<link href="styles/scriptfontstyle.css" rel='stylesheet' type='text/css'>
	
	<br>
 <div> 
		<table id="showtable">
		<tr>
			<td>
				<!--slideshow-->
	
				<img id="slide" src="images/green.png" alt="">
				<div id="slides"> 
					<img id="slide" src="images/threemeat2.jpg" alt="The Way A Sandwich Should Be.">
				<img src="images/rb2.jpg" alt="The Best Meats From Our Local Butcher.">	
				<img src="images/tomato2.jpg" alt="Always Delicious, Try Our Home made Soups.">
				<img src="images/soup2.jpg" alt="We Use Only Locally Grown Vegetables.">
				<img src="images/lemon.jpg" alt=" Lemonade Served With Freshly Squeezed Lemons.">
				<img src="images/nut.jpg" alt="Cookies, Made Just Like Grandma's.">	
				</div>
			</td>
			<td>
				
				<h2 id="caption">The Way A Sandwich Should Be.</h2>
				<h2 id ="order"><a href="menu.php">Order Online Today</a></h2>
			</td>
		</tr>
	</table>
    
	
	
</div>
<?php
include_once 'footer.php';
?>	

