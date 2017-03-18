<?php
/*Group 8
	CPT 262
	Final Project
	This page will allow for a user to add orders 
	to the orderitem table of the database.
*/
$pagetitle = "OrderEntry";//Displays page title
	require_once "header.php";//connects and displays the header page
	require_once "connect.php";//connects to the database


	require_once("functions.php");

	//Check Privileges to stop url jump
if(empty($_SESSION['userid']) ) {
			include_once("header.php");//connects and displays header page
			echo '<p>You are not logged in'; //displays message if user is not logged in
			include_once("footer.php"); //connects and displays footer page
			exit(); //exits orderentry page

			//if the user isn't authorized or they entered the page out of order
		} else if($_SESSION['usertype'] >= 1) {
			$errormsg = "";
			$message = "";
			$showform = 1;

			//Handles the completed order action
	if (isset($_POST['CompleteOrder'])) {
		
		//add the data into the database
		$sql = "INSERT INTO orderdetail(employeeid, orderdate, ordertime, statusid, orderdelaytime, ordertotal, locationid)
		VALUES(:employee, :orderdate, :ordertime, :status, :delay, :ordertotal, :location)";
		$resulto = $db->prepare($sql);
		$resulto->bindValue(':employee', $_SESSION['order']['employeeid']);
		$resulto->bindValue(':orderdate', $_SESSION['order']['date']);
		$resulto->bindValue(':ordertime', $_SESSION['order']['time']);
		$resulto->bindValue(':status', 1);
		$resulto->bindValue(':delay', "00:00:00");
		$resulto->bindValue(':ordertotal', $_SESSION['order']['ordertotal']);
		$resulto->bindValue(':location', $_SESSION['order']['location']);
		$resulto->execute();

		//get the numer of the order
		$orderid = $db->lastInsertId();

		//loop through the products and add them to the database as well as their inventory
		foreach ($_SESSION['products'] as $orderitem) {
			$sql = "INSERT INTO orderitem(orderdetailid, productid, orderitemprice, orderitemnotes) VALUES (:orderid, :product, :itemprice, :itemnotes)";
			$orderinsert = $db->prepare($sql);
			$orderinsert->bindValue(':orderid', $orderid);
			$orderinsert->bindValue(':product', $orderitem['productid']);
			$orderinsert->bindValue(':itemprice', $orderitem['productprice']);
			$orderinsert->bindValue(':itemnotes', $orderitem['productnotes']);
			$orderinsert->execute();

			if($_SESSION['userlocation'] == 1) {

				//retrieve the current inventory data
				$sql = "SELECT * FROM inventorytruckone WHERE productid = :productid";
				$select = $db->prepare($sql);
				$select->bindValue(':productid', $orderitem['productid']);
				$select->execute();

				//get the amount after the order items are subtracted
				$inv = $select->fetch();

				$currentinv = $inv['inventoryamount'];

				$updateinventory = $currentinv - 1;

				//update the database with the new values
				$sql = "UPDATE inventorytruckone SET inventoryamount = :inventory
				WHERE productid = :productid";
				$update = $db->prepare($sql);
				$update->bindValue(':inventory', $updateinventory);
				$update->bindValue(':productid', $orderitem['productid']);
				$update->execute();

			} else if($_SESSION['userlocation'] == 2) {

				//retrieve the current inventory data
				$sql = "SELECT * FROM inventorytrucktwo WHERE productid = :productid";
				$select = $db->prepare($sql);
				$select->bindValue(':productid', $orderitem['productid']);
				$select->execute();

				//get the amount after the order items are subtracted
				$inv = $select->fetch();

				$currentinv = $inv['inventoryamount'];

				$updateinventory = $currentinv - 1;
				
				//update the database with the new values
				$sql = "UPDATE inventorytrucktwo SET inventoryamount = :inventory
				WHERE productid = :productid";
				$update = $db->prepare($sql);
				$update->bindValue(':inventory', $updateinventory);
				$update->bindValue(':productid', $orderitem['productid']);
				$update->execute();

			} else if($_SESSION['userlocation'] == 3) {

				//retrieve the current inventory data
				$sql = "SELECT * FROM inventory WHERE productid = :productid";
				$select = $db->prepare($sql);
				$select->bindValue(':productid', $orderitem['productid']);
				$select->execute();

				//get the amount after the order items are subtracted
				$inv = $select->fetch();

				$currentinv = $inv['inventoryamount'];

				$updateinventory = $currentinv - 1;
				
				//update the database with the new values
				$sql = "UPDATE inventory SET inventoryamount = :inventory
				WHERE productid = :productid";
				$update = $db->prepare($sql);
				$update->bindValue(':inventory', $updateinventory);
				$update->bindValue(':productid', $orderitem['productid']);
				$update->execute();

			}


		}

		//unset the arrays for the order
		unset($_SESSION['order']);
		unset($_SESSION['products']);

		$message =  "<p class='message'>Order Sent!</p>";
		$showform = 0;
	}
	
		if(isset($_POST['CancelOrder'])) {

			//unset the arrays for the order
			unset($_SESSION['order']);
			unset($_SESSION['products']);
		}

		//check if the order is already started
		if(empty($_SESSION['order'])) {

			//get the current date
			$date = date("Y-m-d");

			//get the current time
			$time = date('h:i:s');

			//initialize the order
			$_SESSION['order'] = array();

			//add details to the order
			$_SESSION['order']['date'] = $date;
			$_SESSION['order']['time'] = $time;
			$_SESSION['order']['employeeid'] = $_SESSION['userid'];
			$_SESSION['order']['location'] = $_SESSION['userlocation'];
			$_SESSION['order']['ordertotal'] = 0;
			$_SESSION['products'] = array();

			//get the employee name
			$sql = "SELECT employeefirst FROM employee WHERE employeeid = :empployeeid";
			$employeename = $db->prepare($sql);
			$employeename->bindvalue(':empployeeid', $_SESSION['order']['employeeid']);
			$employeename->execute();

			//get the row
			$empname = $employeename->fetch();

			//set the name to the session
			$_SESSION['order']['employeename'] = $empname['employeefirst'];

			//get the location name
			$sql = "SELECT locationarea FROM location WHERE locationid = :locationid";
			$location = $db->prepare($sql);
			$location->bindvalue(':locationid', $_SESSION['order']['location']);
			$location->execute();

			//get the row
			$locationname = $location->fetch();

			//set the name to the session
			$_SESSION['order']['locationarea'] = $locationname['locationarea'];
		}
	
	//Handles each item entered into an order
	if (isset($_POST['OIEnter']))
	{

		//get the product information from the database
		$sql = "SELECT * FROM product WHERE productid = :productid";
		$productinfo = $db->prepare($sql);
		$productinfo->bindValue(':productid', $_POST['product']);
		$productinfo->execute();

		//get the data
		$prod = $productinfo->fetch();

		//amount the current has of the product selected
		//default to 1 to account for product attempting to order
		$inventorycount = 1;

		//flag that allows or disallows the product to be added
		$productavailable = 0;

		//count the amount of items of the same type the user has selected
		foreach ($_SESSION['products'] as $key => $orderitem) {

			//if there is a matching item add 1 to the count
			if($orderitem['productid'] == $_POST['product']) {
				$inventorycount += 1;
			}
		}

		//get the inventory for the product
		if($_SESSION['userlocation'] == 1) {

			//getting the inventory for the truck one location
			$sql = "SELECT * FROM inventorytruckone WHERE productid = :productid";
			$inventory = $db->prepare($sql);
			$inventory->bindValue(':productid', $_POST['product']);
			$inventory->execute();

			//check if the query returned anything
			if($inventory->rowCount() >= 1) {

				//get the row
				$productinventory = $inventory->fetch();
		

				//check if the inventory can be removed without leaving a negative
				if(($productinventory['inventoryamount'] - $inventorycount) < 0 ) {
					echo '<p class="msgerror">The product is not available due to inventory shortage</p>';

					$productavailable = 0;
				//update the inventory
				} else {

					$productavailable = 1;
				}

				//if there isn't a product in the inventory
			} else {
				echo '<p class="msgerror">The product is not available in the inventory</p>';
			}

		//get the inventory for truck two
		} else if($_SESSION['userlocation'] == 2) {

				//getting the inventory for the truck two location
			$sql = "SELECT * FROM inventorytrucktwo WHERE productid = :productid";
			$inventory = $db->prepare($sql);
			$inventory->bindValue(':productid', $_POST['product']);
			$inventory->execute();

			//check if the query returned anything
			if($inventory->rowCount() >= 1) {

				//get the row
				$productinventory = $inventory->fetch();

				//check if the invetory can be removed without leaving a negative
				if(($productinventory['inventoryamount'] - $inventorycount) < 0 ) {

					$productavailable = 0;
					echo '<p class="msgerror">The product is not available due to inventory shortage</p>';
				
				//update the inventory
				} else {

					$productavailable = 1;
				}

				//if there isn't a product in the inventory
			} else {
				echo '<p class="msgerror">The product is not available in the inventory</p>';
			}

		} else if($_SESSION['userlocation'] == 3) {

				//getting the inventory for the Home Base location
			$sql = "SELECT * FROM inventory WHERE productid = :productid";
			$inventory = $db->prepare($sql);
			$inventory->bindValue(':productid', $_POST['product']);
			$inventory->execute();

			//check if the query returned anything
			if($inventory->rowCount() >= 1) {

				//get the row
				$productinventory = $inventory->fetch();

				//check if the invetory can be removed without leaving a negative
				if(($productinventory['inventoryamount'] - $inventorycount) < 0 ) {
					echo '<p class="msgerror">The product is not available due to inventory shortage</p>';
				
				//update the inventory
				} else {

					//flag to allow the product to be added to the order
					$productavailable = 1;
				}

				//if there isn't a product in the inventory
			} else {
				echo '<p class="msgerror">The product is not available in the inventory</p>';
			}

		//if the user has an unknown location id
		} else {

			$productavailable = 0;
			echo '<p class="msgerror">ERROR: Could not identifiy your location</p>';
		}

		//check if there is enough in the inventory for the users location
		if($productavailable == 1) {

			//apply to the session
			$_SESSION['products'][] = array(
				'productid' => $prod['productid'],
				'productname' => $prod['productname'],
				'productprice' => $prod['productprice'],
				'productnotes' => ""
				);

			//add to the total of the order
			$_SESSION['order']['ordertotal'] += $prod['productprice'];
		}
	}

	//handles items deleted from an order in progress
	if (isset($_POST['DeleteItem']))
	{
		//unset the array for the item the user wishes to remove
		unset($_SESSION['products'][$_POST['orderitem']]);
	}

	if (isset($_POST['NoteEntry'])) {

		//set the notes entered to the corespdoning item
		$_SESSION['products'][$_POST['orderitem']]['productnotes'] = $_POST['notes'];

	}
	
	
	//Handles price changes set by manager
	if (isset($_POST['PriceChange'])) {
		//set the notes entered to the corespdoning item
		$_SESSION['products'][$_POST['orderitem']]['productprice'] = $_POST['price'];
	}

	

	if($showform == 1) {
?>
	<article id="insertorderpage">
		<section class="col-8" id="products">
		<h2>Products</h2>
					<?php
							$sql = "SELECT * FROM productcategory";
							$category = $db->prepare($sql);
							$category->execute();
							while ($rowc = $category->fetch() )
								{
									//check if there are sizes for the category
									
									if(hasSizes($db, $rowc['productcategoryid']) === true) {

										//get teh products and their sizes
										$product = getSizes($db, $rowc['productcategoryid']);
									} else {

										//get teh products
										$product = getProducts($db, $rowc['productcategoryid']);
									}

									echo '<h3 class="categoryname">' . $rowc['categoryname'] . '</h3>';

									//output the products in the current category
									echo '<ul class="productcategory row" id="' . $rowc['categoryname'] .'">';
									while($rowp = $product->fetch()) {
										?>
										<li class="col-3">
										<form class="productform" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
										<input type="hidden" name="product" value="<?php echo  $rowp['productid']; ?>">
										<input type="submit" name="OIEnter" id="addproduct" value="<?php if(isset($rowp['size'])) { echo $rowp['size'] . ' - '; } echo $rowp['productname']; ?>" <?php if(getInventory($db, $rowp['productid'], $_SESSION['userlocation']) === false) { echo 'class="disabled" disabled'; } ?>>
										</form></li>
									<?php
									}
									echo '</ul>';
								} ?>
							</section>

							<section class="col-4" id="ordersummary">
							<h2>Order Summary</h2>
								<table id="ordersummarytable">
									<th colspan="2">Order Summary</th>
							<?php
								echo '<tr><td>' . $_SESSION['order']['employeename'] . '</td>';
								echo '<td>' . $_SESSION['order']['locationarea'] . '</td></tr>';
								echo '<tr><td>' . $_SESSION['order']['date'] . '</td>';
								echo '<td>' . $_SESSION['order']['time'] . '</td></tr>';
								
							?>
			<?php

			//used to get the current index of the array
			$i = 0;
				foreach ($_SESSION['products'] as $key => $orderitem)
				{
				echo '<div id="productdetail"><tr class="productsummary"><td>' . $orderitem['productname'] . '</td>';

				//If the user is a manager allow price changes
				if($_SESSION['usertype'] == 3) {

				//Update Price
				echo '<td colspan="2"><form class="orderform" action = "insertorderitem.php" method = "post">';
				echo '<input type = "hidden" name = "orderitem" value = "'. $key .'">';
				echo '<input type="text" name="price" id="price" value="' . $orderitem['productprice'] . '">';
				echo '<input class="button" type="submit" name="PriceChange" value="Update Price">';
				echo '</form>';
				echo '</td></tr>';
			} 	else {
				 echo '<td>' . $orderitem['productprice'] . '</td></tr>';
			}
				
				//notes
				echo '<tr><td><form class="orderform" action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
				echo '<input type="text" name="notes" id="notes" placeholder="Notes" value="' . $orderitem['productnotes'] . '">';
				echo '<input type="hidden" name="orderitem" value="' . $key . '"></td>';
				echo '<td><input class="button" type="submit" name="NoteEntry" value="Update Note">';
				echo '</form></td></tr>';
				
				//Delete Items
				echo '<tr class="deleteitem"><td colspan="2"><form class="orderform" action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
				echo '<input type="hidden" name="orderitem" value="' . $key . '">';
				echo '<input class="button" type="submit" name="DeleteItem" value="Delete">';
				echo '</form></td></tr>';
				$i += 1;
				}
			?>
		</div>
		</table>
		<table>
			<tr>
				<td>
					<form class="orderform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
						<div class="button2"><input type="submit" name="CompleteOrder" id="CompleteOrder" value="Complete Order"></div>
					</form>
				</td>

				<td>
					<form class="orderform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
						<div class="button2"><input type="submit" name="CancelOrder" id="CancelOrder" value="Cancel Order"></div>
					</form>
				</td>
			</tr>
		</table>
	</section>
</article>

<?php
//if showform is not equal to 1
} else {
?>

	<h2><?php echo $message ?></h2>
	<form method="post" action="insertorderitem.php">
		<input class="button" type="submit" value="New Order">
	</form>

<?php
}

} else {
	echo '<p class="msgerror">You are not autorized to access this page</p>';
}

echo "</article>";
include_once 'footer.php';
?>