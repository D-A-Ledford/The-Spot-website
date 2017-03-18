<?php
include_once("header.php");
include_once("connect.php");

if(empty($_SESSION['userid'])) {
			include_once("header.php");
			echo '<p>You are not logged in';
			include_once("footer.php");
			exit();
} else if($_SESSION['usertype'] >= 1) {

	//initialize errormsg
	$errormsg = "";

	//gets the current date
	$date = date("Y-m-d");

	//will get the current time
	$time = date('h:i:s');;

	//default order delay
	$orderdelay = "00:00:00";

	//Default status for orders
	$status = 0;

	$showform = 1;

	//get the employees
	$sql = "SELECT * from employee";
	$resulte = $db->prepare($sql);
	$resulte->execute();

	if(isset($_POST['CustomerForm'])) {
	
		if(!empty($_POST['customerfirst'])) {
			$errormsg = "<p class='errormsg'>You did not enter a customer name</p>";
		} 

		if(empty($_POST['employee'])) {
			$errormsg = "<p class='errormsg'>You did not enter an employee</p>";
		}

		if(!empty($errormsg)) {
			
			//Creates the sql query
			$sql = 'INSERT INTO customer (customerfirst) VALUES (:customerfirst)';
			
			//Prepares the SQL Statement for execution
			$stmtinsert = $db->prepare($sql);
			$stmtinsert->bindvalue(':customerfirst', $_POST['customerfirst']);
			$stmtinsert->execute();

			//get the customerid
			$customerid = $db->lastInsertId();
			

			//add the order to the database
			$sql = "INSERT INTO orderdetail(customerID, employeeID, orderDate, orderTime) VALUES (:customerID, :employeeID, :orderDate, :orderTime)";
			$insert = $db->prepare($sql);
			$insert->bindvalue(':customerID', $customerid);
			$insert->bindvalue(':employeeID', $_POST['employee']);
			$insert->bindvalue(':orderDate', $date);
			$insert->bindvalue(':orderTime', $time);
			$insert->execute();

			//get the order id
			$orderid = $db->lastInsertId();

			echo "Order Number: " . $orderid;
			echo '<br><br><form action="insertorderitem.php" method = "post">';
			echo '<input type = "hidden" name = "ordid" value = "'. $orderid .'">';
			echo '<input type="submit" name="submit" value="Enter Order Items">';
			echo "</form>";
			$showform = 0;
			
		}
	}


	if ($showform === 1) {
		
	?>
<section>
	<h1>Add New Orders</h1>

	<?php 
	//Display the error message if there are any
	if(isset($errormsg)) { echo $errormsg; } ?>

	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method = "POST">
		<fieldset><legend>Order Info</legend>
		
		<table border>
			<tr>
				<th>Customer Name</th>
				<td><input type="text" name="customerfirst" id="custoemrfirst"></td>
			</tr>
			<tr>
			<th><label for="employee">Employee:</label></th>
				<td><select name="employee" id="employee">
						<option value = "">Please Select an Employee</option>
						<?php while ($rowe = $resulte->fetch() )
							{
								if ($_SESSION['userid'] == $rowe['employeeid']) {
									$selected = 'selected'; 
								} else {
									$selected = '';
								}
								echo '<option value="'. $rowe['employeeid'] . '" ' . $selected . '>' 
								. $rowe['employeefirst'] . " " . $rowe['employeelast'] . '</option>';
							}
						?>
					</select>
				</td>
			</tr>
		</table>
		<input type="submit" name="CustomerForm" value="Submit">
		</fieldset>
	</form>
</section>

<?php
}
} else {
	echo '<p class="msgerror">You are not autorized to access this page</p>';
}
include_once 'footer.php';
?>	