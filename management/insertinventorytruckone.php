<?php  //Begin php tag

/*Bryan L, Chris K, Danielle L, Chris G, Josh S
	CPT 262
	Final Project
	This page will allow for a user to insert inventory into food truck one, delete, and updates main inventory
	to subtract items inserted. Validations are in place so that a user can not subtract more than what's available
	and enter the same item twice.
	php.
*/
	$pagetitle = "Truck One Inventory";//Displays page title
	require_once "header.php";
	require_once "connect.php";
	//This code will place the entered data into an
	//Associative array after cleansing
	$formfield['inventory'] = $_POST['inventory'];
	$formfield['product'] = $_POST['product'];
	$formfield['amount'] = $_POST['amount'];
	
	//This code will only occur after the enter button
	//has been clicked
	if (isset($_POST['OIEnter']))
	{
		//set string error message to empty
		$error = "";
		//if statement determines if the amount entered is greater than zero
		//if argument is satisfied than the following will take place. 
		if($formfield['amount'] > 0) {

		//Get the inventory amount from the main inventory
		$sql = "SELECT * FROM inventory WHERE productid = :productid";
		$sqlinventory = $db->prepare($sql);
		$sqlinventory->bindValue(':productid', $formfield['product']);
		$sqlinventory->execute();

		//Get the row 
		$inventory = $sqlinventory->fetch();

		//Get the inventory amount for that product
		$inventoryamount = $inventory['inventoryamount'];

			//check if the amount the user entered compared to the main inventory doesn't become negative
			if(($inventoryamount - $formfield['amount']) >= 0) {
				//subtracts current amount from home inventory to the amount entered
				$inventoryamount = $inventoryamount - $formfield['amount'];
				//Get the inventory amount from the main inventory
				$sql = "SELECT * FROM inventorytruckone WHERE productid = :productid";
				$sqlproductexist = $db->prepare($sql);
				$sqlproductexist->bindValue(':productid', $formfield['product']);
				$sqlproductexist->execute();
				//We need a variable to determine if the product already exisits with in the 
				$productexists = $sqlproductexist->fetch();
				//check to see if the product already exists
				if(empty($productexists)) {
					//Creates the sql insert that will insert input into the inventory truck one database
					$sqlinsert = 'INSERT INTO inventorytruckone 
					(inventoryamount, productid)
					VALUES (:theamount, :theproduct)';
					
					//Prepares the SQL Statement for execution
					$stmtinsert = $db->prepare($sqlinsert);
					//Binds our associative array variables to the bound
					//variables in the sql statement
					$stmtinsert->bindValue(':theamount', $formfield['amount']);
					$stmtinsert->bindValue(':theproduct', $formfield['product']);
					//Runs the insert statement and query
					$stmtinsert->execute();

					//update main inventory with new amount
					$sqlupdate = 'Update inventory
								SET  inventoryamount = :amount
								WHERE productid = :productid';
					$stmtupdate = $db->prepare($sqlupdate);
					$stmtupdate->bindvalue(':amount', $inventoryamount);
					$stmtupdate->bindvalue(':productid', $formfield['product']);
					$stmtupdate->execute();

				} else {
					//displays error id product exists
					$error .= "<p class='errormsg'>The product is already in the truck one database</p>";
				}
				
			} else {
				//displays if not enough product to transfer
				$error .= "<p class='errormsg'>The main inventory does not have enough product available</p>";
			}

		} else {
			//displays if number entered is not a positive number
			$error .= "<p class='errormsg'>You must enter a positive number</p>";

		}
	}
//checks to see if the delete button was selected
if (isset($_POST['DeleteItem']))
	{

		//Get the inventory amount from the main inventory
		$sql = "SELECT * FROM inventory WHERE productid = :productid";
		$sqlinventory = $db->prepare($sql);
		$sqlinventory->bindValue(':productid', $formfield['product']);
		$sqlinventory->execute();

		//Get the row 
		$inventory = $sqlinventory->fetch();

		//Get the inventory amount for that product
		$inventoryamount = $inventory['inventoryamount'];
		
		//add the returned value to the formfield
		$formfield['amount'] += $inventoryamount;

		//remove the row
		$sqldelete = "DELETE FROM inventorytruckone
					WHERE productid = :theproduct";
		$stmtdelete = $db->prepare($sqldelete);
		$stmtdelete->bindvalue(':theproduct', $formfield['product']);
		$stmtdelete->execute();

		//add the product back into the main inventory
		$sql = "UPDATE  inventory
				SET inventoryamount = :amount
				WHERE productid = :product";
		$stmtupdate = $db->prepare($sql);
		$stmtupdate->bindvalue(':amount', $formfield['amount']);
		$stmtupdate->bindvalue(':product', $formfield['product']);
		$stmtupdate->execute();
	}

	//checks to see if the update button was selected
	if (isset($_POST['Add']))
	{
		//set error message to empty
		$error = "";
		//check if the amount the user entered is greater than 0
		if($formfield['amount'] > 0) {

			//Get the inventory amount from the main inventory
			$sql = "SELECT * FROM inventory WHERE productid = :productid";
			$sqlinventory = $db->prepare($sql);
			$sqlinventory->bindValue(':productid', $formfield['product']);
			$sqlinventory->execute();

			//Get the row 
			$inventory = $sqlinventory->fetch();

			//Get the inventory amount for that product
			$inventoryamount = $inventory['inventoryamount'];
			
			//Get the Truck One Inventory
			$sql = "SELECT * FROM inventorytruckone WHERE productid = :productid";
			$sqlinventory = $db->prepare($sql);
			$sqlinventory->bindValue(':productid', $formfield['product']);
			$sqlinventory->execute();

			//Get the row 
			$inventorytruckone = $sqlinventory->fetch();

			//Get the inventory amount for that product
			$truckoneinventoryamount = $inventorytruckone['inventoryamount'];

			//check if the amount the user entered compared to the main inventory doesn't become negative
			if(($inventoryamount - $formfield['amount']) >= 0) {


				//add the amount to the truck one inventory
				$truckoneinventoryamount = $truckoneinventoryamount + $formfield['amount'];
				
				//get the remaining amount from the main inventory to update the main inventory
				$inventoryamount = $inventoryamount - $formfield['amount'];

				//update truck one inventory
				$sqlupdateoi = 'Update inventorytruckone
							SET  inventoryamount = :amount
							WHERE productid = :productid';
				$stmtupdateoi = $db->prepare($sqlupdateoi);
				$stmtupdateoi->bindvalue(':amount', $truckoneinventoryamount);
				$stmtupdateoi->bindvalue(':productid', $formfield['product']);
				$stmtupdateoi->execute();

				//update main inventory
				$sqlupdate = 'Update inventory
							SET  inventoryamount = :amount
							WHERE productid = :productid';
				$stmtupdate = $db->prepare($sqlupdate);
				$stmtupdate->bindvalue(':amount', $inventoryamount);
				$stmtupdate->bindvalue(':productid', $formfield['product']);
				$stmtupdate->execute();
			} else {
				//Displays if not enough inventory to transfer
				$error .= "<p class='errormsg'>There is not enough to add from the main inventory</p>";
			}
		} else {
			//Displays if numbered enter is not positive
			$error .= "<p class='errormsg'>You must enter a positive number</p>";
		}
	}
	//checks to see if the update button was selected
	if (isset($_POST['Subtract']))
	{
		//set error message to empty
		$error = "";
		//check if the amount the user entered is greater than 0
		if($formfield['amount'] > 0) {

			//Get the inventory amount from the main inventory
			$sql = "SELECT * FROM inventory WHERE productid = :productid";
			$sqlinventory = $db->prepare($sql);
			$sqlinventory->bindValue(':productid', $formfield['product']);
			$sqlinventory->execute();

			//Get the row 
			$inventory = $sqlinventory->fetch();

			//Get the inventory amount for that product
			$inventoryamount = $inventory['inventoryamount'];
			
			//Get the Truck One Inventory
			$sql = "SELECT * FROM inventorytruckone WHERE productid = :productid";
			$sqlinventory = $db->prepare($sql);
			$sqlinventory->bindValue(':productid', $formfield['product']);
			$sqlinventory->execute();

			//Get the row 
			$inventorytruckone = $sqlinventory->fetch();

			//Get the inventory amount for that product
			$truckoneinventoryamount = $inventorytruckone['inventoryamount'];

			//check if the amount the user entered compared to the main inventory doesn't become negative
			if(($truckoneinventoryamount - $formfield['amount']) >= 0) {


				//add the amount to the truck one inventory
				$truckoneinventoryamount = $truckoneinventoryamount - $formfield['amount'];
				
				//get the remaining amount from the main inventory to update the main inventory
				$inventoryamount = $inventoryamount + $formfield['amount'];

				//update truck one inventory
				$sqlupdateoi = 'Update inventorytruckone
							SET  inventoryamount = :amount
							WHERE productid = :productid';
				$stmtupdateoi = $db->prepare($sqlupdateoi);
				$stmtupdateoi->bindvalue(':amount', $truckoneinventoryamount);
				$stmtupdateoi->bindvalue(':productid', $formfield['product']);
				$stmtupdateoi->execute();

				//update main inventory
				$sqlupdate = 'Update inventory
							SET  inventoryamount = :amount
							WHERE productid = :productid';
				$stmtupdate = $db->prepare($sqlupdate);
				$stmtupdate->bindvalue(':amount', $inventoryamount);
				$stmtupdate->bindvalue(':productid', $formfield['product']);
				$stmtupdate->execute();
			} else {
				//Displays if not enough inventory to transfer
				$error .= "<p class='errormsg'>There is not enough to subtract from the truck one inventory</p>";
			}
		} else {
			//Displays if numbered enter is not positive
			$error .= "<p class='errormsg'>You must enter a positive number</p>";
		}
	}
	//Accesses the Inventory Truck One table to collect information to select for input
	$sqlselectinventoryone = "SELECT inventorytruckone.*, product.productname FROM `inventorytruckone` 
				LEFT JOIN product
				ON inventorytruckone.productid = product.productid";
	$resultinventoryone = $db->prepare($sqlselectinventoryone);
	$resultinventoryone->execute();
	
	
	//Accesses the Products table to collect information to select for input
	$sqlselectproduct = "SELECT inventory.*, product.productname from inventory
							LEFT JOIN product
							ON inventory.productid = product.productid";
	$resultproduct = $db->prepare($sqlselectproduct);
	$resultproduct->execute();
	
	//checks to see if user has permission to access page
	if ($visible == 1 && $permit == 3)
	{
?>
<?php
	//checks to see if no error messages are present
	if(!empty($error)) {
		echo $error;
	}
?>
<!--handles responsiveness to display screen-->
<div class="row">
<div class="col-6">
<form action = "<?php echo $_SERVER['PHP_SELF'];?>" method = "post">
		<h2>Adjust Inventory</h2>
		<!--An HTML table consists of the <table> element and one or more <tr>, <th>, and <td> elements.
		The <tr> element defines a table row, the <th> element defines a table header, and the <td> element 
		defines a table cell-->	
		<table>
			<tr>
				<th>AMOUNT<br>AVAILABLE</th>
				<th>AMOUNT<br>DESIRED</th>
				<th>PRODUCT</th>
			</tr>
			<?php
			//Will call all information that exist with in the table and populate the fields in the above table
					while ($rowproduct = $resultproduct->fetch() )
						{
						echo '<tr><td align="center">' . $rowproduct['inventoryamount'] . '</td><td>';
						echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
						echo '<input type = "hidden" name = "product" value = "'. $rowproduct['productid'] .'">';
						?>
						<input type="text" name="amount" id="amount" >
						<?php
						echo '</td><td align="center"><input class="button" type="submit" name="OIEnter" value="'. $rowproduct['productname'] .'">';
						echo '</form>';
						
						echo '</td></tr>';
						}
			?>
		</table><!--end table-->
	
</form></div>
	<div class="col-6 right">
	<h2>Inventory List</h2>
		<table>
			<tr>
				<th>PRODUCT</th>
				<th>AMOUNT</th>
				<th>DELETE</th>
				<th>ADJUST AMOUNT</th>
				<th>+/-</th>
			</tr>
			<?php
			//Will call all information that exist with in the table and populate the fields in the above table
				while ($rowinventoryone = $resultinventoryone->fetch() )
				{
				echo '<tr><td align="center">' . $rowinventoryone['productname'] . '</td>';
				echo '<td align="center">' . $rowinventoryone['inventoryamount'] . '</td>';
				
				echo '<td align="center">';
				echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
				echo '<input type = "hidden"  name = "product" value = "'. $rowinventoryone['productid'] .'">';
				echo '<input id="amount1" type = "hidden"  name = "amount" value = "'. $rowinventoryone['inventoryamount'] .'">';
				echo '<input type="hidden" name="DeleteItem" value="Delete">';
				echo '<input class="deleteitem" type="image" src="images/delete.png" alt="submit" width="25" height="25">';
				echo '</form></td>';

				echo '<td align="center">';
				echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
				echo '<input type="text" name="amount">';
				echo '<input type = "hidden" name = "product" value = "'. $rowinventoryone['productid'] .'">';
				
				echo '<td><input class ="button" type="submit" name="Add" value="+" font="2em">';
				echo '<input class ="button" type="submit" name="Subtract" value="-" font="2em">';
				echo '</form></td></td></tr>';
				}
			?>
		</table>
		</div>
</div>
<?php
}//visible
include_once 'footer.php';
?>