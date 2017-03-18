<?php  //Begin php tag

/*Bryan L, Chris K, Danielle L, Chris G, Josh S
	CPT 262
	Final Project
	This page will allow for a user to insert inventory into food truck two, delete, and updates main inventory
	to subtract items inserted. Validations are in place so that a user can not subtract more than what's available
	and enter the same item twice.
	php.
*/
	$pagetitle = "Truck Two Inventory";//Displays page title
	require_once "header.php"; //Connects header to page 
	require_once "connect.php"; //connects page to database
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

				$inventoryamount = $inventoryamount - $formfield['amount'];

				$sql = "SELECT * FROM inventorytrucktwo WHERE productid = :productid";
				$sqlproductexist = $db->prepare($sql);
				$sqlproductexist->bindValue(':productid', $formfield['product']);
				$sqlproductexist->execute();

				$productexists = $sqlproductexist->fetch();

				if(empty($productexists)) {

					$sqlinsert = 'INSERT INTO inventorytrucktwo 
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

					//update main inventory
					$sqlupdate = 'Update inventory
								SET  inventoryamount = :amount
								WHERE productid = :productid';
					$stmtupdate = $db->prepare($sqlupdate);
					$stmtupdate->bindvalue(':amount', $inventoryamount);
					$stmtupdate->bindvalue(':productid', $formfield['product']);
					$stmtupdate->execute();

				} else {
					$error .= "<p class='errormsg'>The product is already in the truck one database</p>";
				}
				
			} else {
				$error .= "<p class='errormsg'>The main inventory does not have enough product available</p>";
			}

		} else {
			$error .= "<p class='errormsg'>You must enter a positive number</p>";

		}
	}

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
		$sqldelete = "DELETE FROM inventorytrucktwo
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

	
	if (isset($_POST['Add']))
	{
		$error = "";

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
			$sql = "SELECT * FROM inventorytrucktwo WHERE productid = :productid";
			$sqlinventory = $db->prepare($sql);
			$sqlinventory->bindValue(':productid', $formfield['product']);
			$sqlinventory->execute();

			//Get the row 
			$inventorytrucktwo = $sqlinventory->fetch();

			//Get the inventory amount for that product
			$trucktwoinventoryamount = $inventorytrucktwo['inventoryamount'];

			//check if the amount the user entered compared to the main inventory doesn't become negative
			if(($inventoryamount - $formfield['amount']) >= 0) {

				//add the amount to the truck one inventory
				$trucktwoinventoryamount = $trucktwoinventoryamount + $formfield['amount'];
				
				//get the remaining amount from the main inventory to update the main inventory
				$inventoryamount = $inventoryamount - $formfield['amount'];

				//update truck one inventory
				$sqlupdateoi = 'Update inventorytrucktwo
							SET  inventoryamount = :amount
							WHERE productid = :productid';
				$stmtupdateoi = $db->prepare($sqlupdateoi);
				$stmtupdateoi->bindvalue(':amount', $trucktwoinventoryamount);
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
				$error .= "<p class='errormsg'>There is not enough to add from the main inventory</p>";
			}
		} else {
			$error .= "<p class='errormsg'>You must enter a positive number</p>";
		}
	}
	if (isset($_POST['Subtract']))
	{

		$error = "";

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
			$sql = "SELECT * FROM inventorytrucktwo WHERE productid = :productid";
			$sqlinventory = $db->prepare($sql);
			$sqlinventory->bindValue(':productid', $formfield['product']);
			$sqlinventory->execute();

			//Get the row 
			$inventorytrucktwo = $sqlinventory->fetch();

			//Get the inventory amount for that product
			$trucktwoinventoryamount = $inventorytrucktwo['inventoryamount'];

			//check if the amount the user entered compared to the main inventory doesn't leave truck two negative
			if(($trucktwoinventoryamount - $formfield['amount']) >= 0) {
				//add the amount to the truck one inventory
				$trucktwoinventoryamount = $trucktwoinventoryamount - $formfield['amount'];
				
				//get the remaining amount from the main inventory to update the main inventory
				$inventoryamount = $inventoryamount + $formfield['amount'];

				//update truck one inventory
				$sqlupdateoi = 'Update inventorytrucktwo
							SET  inventoryamount = :amount
							WHERE productid = :productid';
				$stmtupdateoi = $db->prepare($sqlupdateoi);
				$stmtupdateoi->bindvalue(':amount', $trucktwoinventoryamount);
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
				$error .= "<p class='errormsg'>There is not enough to subtract from the truck two inventory</p>";
			}
		} else {
			$error .= "<p class='errormsg'>You must enter a positive number</p>";
		}
	}
	//Accesses the Inventory Truck Two table to collect information to select for input
	$sqlselectinventoryone = "SELECT inventorytrucktwo.*, product.productname FROM `inventorytrucktwo` 
				LEFT JOIN product
				ON inventorytrucktwo.productid = product.productid";
	$resultinventoryone = $db->prepare($sqlselectinventoryone);
	$resultinventoryone->execute();
	
	
	//Accesses the Products table to collect information to select for input
	$sqlselectproduct = "SELECT inventory.*, product.productname from inventory
							LEFT JOIN product
							ON inventory.productid = product.productid";
	$resultproduct = $db->prepare($sqlselectproduct);
	$resultproduct->execute();
	
	
	if ($visible == 1 && $permit == 3)
	{
?>
<?php
	if(!empty($error)) {
		echo $error;
	}
?>
<div class="row">
<div class="col-6">
<h2>Adjust Inventory</h2>
<form action = "<?php echo $_SERVER['PHP_SELF'];?>" method = "post">	
		<table>
			<tr>
				<th>AMOUNT<br>AVAILABLE</th>
				<th>AMOUNT<br>DESIRED</th>
				<th>PRODUCT</th>
			</tr>
			<?php
					while ($rowproduct = $resultproduct->fetch() )
						{
						echo '<tr><td align="center">' . $rowproduct['inventoryamount'] . '</td><td>';
						echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
						echo '<input type = "hidden" name = "product" value = "'. $rowproduct['productid'] .'">';
						?>
						<input type="text" name="amount" id="amount">
						<?php
						echo '</td><td align="center"><input class="button" type="submit" name="OIEnter" value="'. $rowproduct['productname'] .'">';
						echo '</form>';
						
						echo '</td></tr>';
						}
			?>
		</table>
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
				while ($rowinventoryone = $resultinventoryone->fetch() )
				{
				echo '<tr><td align="center">' . $rowinventoryone['productname'] . '</td>';
				echo '<td>' . $rowinventoryone['inventoryamount'] . '</td>';
				
				echo '<td align="center">';
				echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
				echo '<input type = "hidden" name = "product" value = "'. $rowinventoryone['productid'] .'">';
				echo '<input id = "amount1" type="hidden" name = "amount" value = "'. $rowinventoryone['inventoryamount'] .'">';
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