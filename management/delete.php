<?php

session_start();
include_once "connect.php";

if($_SESSION['usertype'] == 3) {
	if(isset($_GET['userid'])) {

	$userid = $_GET['userid'];

	$sql = "DELETE FROM employee WHERE  employeeid = :empid";
	$delete = $db->prepare($sql);
	$delete->bindValue(':empid', $userid);
	$delete->execute();

	header("Location: searchemployee.php?delete=true");

	} else {
		header("Location: searchemployees.php");
	}
} else {
	header("Location: index.php");
}