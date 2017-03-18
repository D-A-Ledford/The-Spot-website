<?php
//This page will return the table of data for the insertorderitem page\
session_start();

	require_once "connect.php";
	//Check Privileges to stop url jump
	
	//Handles each item entered into an order
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		echo "You made it here";

		//handles items delted from an order in progress
	if (isset($_POST['DeleteItem']))
	{
		//unset the array for the item the user wishes to remove
		unset($_SESSION['products'][$_POST['orderitem']]);
	}

		print_r($_POST);

		//get the product information from the database
		$sql = "SELECT * FROM product WHERE productid = :productid";
		$productinfo = $db->prepare($sql);
		$productinfo->bindValue(':productid', $_POST['product']);
		$productinfo->execute();

		//get the data
		$prod = $productinfo->fetch();

		//apply to the session
		$_SESSION['products'][] = array(
			'productid' => $prod['productid'],
			'productname' => $prod['productname'],
			'productprice' => $prod['productprice'],
			'productinventory' => $prod['productinventory'],
			'productnotes' => ""
			);

		//add to the total of the order
		$_SESSION['order']['ordertotal'] += $prod['productprice'];

	}

	//handles items delted from an order in progress
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

	//Handles the completeed order action
	if (isset($_POST['CompleteOrder'])) {
		
		//add the data into the database
		$sql = "INSERT INTO orderdetail(employeeid, orderdate, ordertime, statusid, orderdelaytime, ordertotal, locationid)
		VALUES(:employee, :orderdate, :ordertime, :status, :delay, :ordertotal, :location)";
		$resulto = $db->prepare($sql);
		$resulto->bindValue(':employee', $_SESSION['order']['employeeid']);
		$resulto->bindValue(':orderdate', $_SESSION['order']['date']);
		$resulto->bindValue(':ordertime', $_SESSION['order']['time']);
		$resulto->bindValue(':status', 0);
		$resulto->bindValue(':delay', "00:00:00");
		$resulto->bindValue(':ordertotal', $_SESSION['order']['ordertotal']);
		$resulto->bindValue(':location', $_SESSION['order']['location']);
		$resulto->execute();

		//get the numer of the order
		$orderid = $db->lastInsertId();

		//loop through the products and add them to the database
		foreach ($_SESSION['products'] as $orderitem) {
			$sql = "INSERT INTO orderitem(orderdetailid, productid, orderitemprice, orderitemnotes) VALUES (:orderid, :product, :itemprice, :itemnotes)";
			$orderinsert = $db->prepare($sql);
			$orderinsert->bindValue(':orderid', $orderid);
			$orderinsert->bindValue(':product', $orderitem['productid']);
			$orderinsert->bindValue(':itemprice', $orderitem['productprice']);
			$orderinsert->bindValue(':itemnotes', $orderitem['productnotes']);
		}

		//unset the arrays for the order
		unset($_SESSION['order']);
		unset($_SESSION['products']);

		echo "Order Sent!";
	}

?>
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
				echo '<td colspan="2"><form action = "' . "insertorderitem.php" . '" method = "post">';
				echo '<input type = "hidden" name = "orderitem" value = "'. $key .'">';
				echo '<input type="text" name="price" id="price" value="' . $orderitem['productprice'] . '">';
				echo '<input class="button" type="submit" name="PriceChange" value="Update Price">';
				echo '</form>';
				echo '</td></tr>';
			} 	else {
				 echo '<td>' . $orderitem['productprice'] . '</td></tr>';
			}
				
				//notes
				echo '<tr><td><form action = "' . "insertorderitem.php" . '" method = "post">';
				echo '<input type="text" name="notes" id="notes" placeholder="Notes" value="' . $orderitem['productnotes'] . '">';
				echo '<input type="hidden" name="orderitem" value="' . $key . '"></td>';
				echo '<td><input class="button" type="submit" name="NoteEntry" value="Update Note">';
				echo '</form></td></tr>';
				
				//Delete Items
				echo '<tr class="deleteitem"><td colspan="2"><form action = "' . "insertorderitem.php" . '" method = "post">';
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
					<form action="insertorderitem.php" method="POST">
						<div class="button2"><input type="submit" name="CompleteOrder" id="CompleteOrder" value="Complete Order"></div>
					</form>
				</td>

				<td>
					<form action="insertorderitem.php" method="POST">
						<div class="button2"><input type="submit" name="CancelOrder" id="CancelOrder" value="Cancel Order"></div>
					</form>
				</td>
			</tr>
		</table>
	</section>