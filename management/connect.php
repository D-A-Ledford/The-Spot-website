<?php
	$currency = '$ '; //Currency Character or code
	$dsn = 'mysql:host=localhost; dbname=grp8sp16_restaurant';
	$username = 'grp8sp16_grp8adm';
	$password = 'grp8adm';
	
	try	{
		$db = new PDO($dsn, $username, $password);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
	}
	catch (PDOException $e)
	{
		echo 'ERROR connecting to database!' . $e->getMessage();
		exit();
	}
?>