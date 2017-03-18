<?php  //Begin php tag

/*Group 8
	CPT 262
	Final Project
	This page will allow for a user to add products 
	to the inventory table of the database.
*/
	$pagetitle = "Home Base Inventory";//Displays page title
	require_once "header.php"; //connects the header
	require_once "connect.php"; //connects to connect.php which houses verification for logging into the database.

	function isProductInventoried($db, $productid) {
		$sql = "SELECT * FROM inventory WHERE productid = :productid";
		$product = $db->prepare($sql);
		$product->bindValue(':productid', $productid);
		$product->execute();

		if($product->rowCount() <= 0) {
			$inventoried = false;
		} else {
			$inventoried = true;
		}
		return $inventoried;
	}
	
	$errormsg = ""; //set string error message to empty
	
	$formfield['product'] = ($_POST['product']);


	
	//This code will only occur after the enter button
	//has been clicked
	if (isset($_POST['myEnter']))
	{
	if(!empty($_POST['product'])) {
		$formfield['amount'] = trim($_POST['amount']);
		
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
		
		//check if there are results
		//if the product is already in the inventory then add it
		if($sqlinventory->rowCount() <= 0) { 

					$sqlinsert = 'INSERT INTO inventory
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
			
			} else {
			
			$inventory = $sqlinventory->fetch();
			$inventoryamount = $inventory['inventoryamount']; 
			$amount = $inventoryamount + $formfield['amount'];
				$sql = "UPDATE inventory SET inventoryamount = :amount
				WHERE productid = :productid";
				$update = $db->prepare($sql);
				$update->bindValue(':amount', $amount);
				$update->bindValue(':productid', $formfield['product']);
				$update->execute();

			}

		} else {
			$error .= "<p class='errormsg'>You must enter a positive number</p>";

		}
		
		} else {
			$error .= "<p class='errormsg'>You must choose a product</p>";
		}
	}

	if (isset($_POST['DeleteItem']))
	{
		$sqldelete = "DELETE FROM inventory
					WHERE productid = :theproduct";
		$stmtdelete = $db->prepare($sqldelete);
		$stmtdelete->bindvalue(':theproduct', $formfield['product']);
		$stmtdelete->execute();
	}

	//Accesses the Inventory table to collect information to select for input
	$sqlselectinventory = "SELECT * FROM inventory 
				LEFT JOIN product
				ON inventory.productid = product.productid
				ORDER BY product.productid";
	$resultinventory = $db->prepare($sqlselectinventory);
	$resultinventory->execute();
	
	//Accesses the Products table to collect information to select for input
	$sqlselectproduct = "SELECT * from product";
	$resultproduct = $db->prepare($sqlselectproduct);
	$resultproduct->execute();
	
	
	
	//Accesses the Location table to collect information to select for input
	$sqlselectlocation = "SELECT * from location";
	$resultlocation = $db->prepare($sqlselectlocation);
	$resultlocation->execute();
	
	if ($visible == 1 && $permit == 3)
	{
	if(isset($error)) { echo $error; }
?>

	
	<div class="row">
	<div class="col-6">
	<form action="<?php echo $_SERVER['PHP_SELF'];?>" method = "post">
	
		<!--The <fieldset> tag is used to group related elements in a form
		The <legend> tag defines a caption for the <fieldset> element-->
		
		<h2>Enter Inventory</h2>
		<!--An HTML table consists of the <table> element and one or more <tr>, <th>, and <td> elements.
		The <tr> element defines a table row, the <th> element defines a table header, and the <td> element 
		defines a table cell-->	
		<table>	
			<tr>
			<th><label form="product">Product Name</label></th><!--Name of table-->
				<td>
				
				<select style="width 50%" name="product" id="product"><!--The select creates a drop selection box-->
						<option value = "">Please Select the Product</option><!--Default text of selection field-->
						<?php
						//In order to eliminate user error, the selection box is populated
						while ($rowproduct = $resultproduct->fetch() )
																//with records from the product table
							{
									echo '<option value="'. $rowproduct['productid'] . '">' . $rowproduct['productname'] . '</option>';
							}
						?>
		</select>
		
	</td>
	</tr>
		<tr>
			<th class="ridge"><label for="amount">Quantity</label></th><!--creates a label-->
			<td><input type="text" name="amount" id="amount"/></td><!--creates an input box-->
		</tr>
					
		</table><!--End table--><br>
		<!--Input selection which assigns a button to press to submit webpage-->
		<button type="submit" name="myEnter" value="Enter">Submit</button>
		<!--End Field set-->
	</form></div><!--End Form-->
	
	
	<div class="col-6 right">
	<h2>Inventory List</h2>
	<table><!--Creates an out put table to display information from the database-->
	<tr>
		
		<th class="ridge">Product ID</th>
		<th class="ridge">Product Name</th>		
		<th class="ridge">Item Quantity</th>
		<th class="ridge">Delete</th>
		
		
	</tr>
		<?php
		//Will call all information that exist with in the table and populate the fields in the above table
			while ( $rowinventory = $resultinventory->fetch() )
			{ //displays the product id, product name and inventory amount
				echo '<tr><td align="center">' . $rowinventory['productid'] . '</td><td align="center">'
				. $rowinventory['productname'] . '</td><td align="center">' 
				. $rowinventory['inventoryamount']. '</td>';
				echo '<td align="center">';
				echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
				echo '<input type = "hidden"  name = "product" value = "'. $rowinventory['productid'] .'">';
				echo '<input id="amount1" type = "hidden"  name = "amount" value = "'. $rowinventory['inventoryamount'] .'">';
				echo '<input type="hidden" name="DeleteItem" value="Delete">';
				echo '<input class="deleteitem" type="image" src="images/delete.png" alt="submit" width="25" height="25">';
				echo '</form></td>';
				echo '</tr>';
			}
	?>
	</table><!--End table-->
		<!--End Div-->
	</div>
	<!--End Div-->
</div>
<!--Begin Footer Div-->
<div id ="footer">
<?php
}
include_once 'footer.php';//connects footer to the page.
?>
</div><!--End Div-->