<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="utf-8">
    <title><?php echo $pagetitle; ?></title>
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/grid.css">
	<link rel="stylesheet" href="styles/login.css">
  <script src="scripts/login.js"></script>
    <script src="scripts/jquery-1.12.3.min.js"></script>
    <script src="scripts/scripts.js"></script>
	
</head>
<body>
<header>
	<div class="wrapper">
    <div class="row">
  	<h1 hidden="hidden">The Spot</h1>
    <div class="col-2">
      <img src="images/logoupdated.png" width="125" height="125" alt="Logo" id="headerimg">
    </div>
    <nav class="col-10">
  		
	  </nav>
  </div>
	</div>
</header>
<main class="wrapper">
  <h1><?php echo $pagetitle ?></h1>