<?php
	session_start();
	
	
	//This code will only occur after the enter button
		//has been clicked
if(!empty($_SESSION['cartproducts'])) {

	require_once "header.php";
	require_once "connect.php";
	
	$showform = 1;
	if (isset($_POST['myEnter']))
	{

			//get the current date
			$date = date("Y-m-d");

			//get the current time
			$time = date('h:i:s');

			//apply the date and time
			$_SESSION['cart']['date'] = $date;
			$_SESSION['cart']['time'] = $time;


			//This code will place the entered data into an
			//Associative array after cleansing
			$formfield['firstname'] = trim($_POST['firstname']);
			$formfield['lastname'] = trim($_POST['lastname']);
			$formfield['email'] = trim(strtolower($_POST['email']));
			$formfield['phone'] = trim($_POST['phone']);
			$formfield['truck'] = $_POST['truck'];
			//Validates that the fields were entered and display the appropriate
			//error message if a field was not entered.
			if(empty($formfield['firstname'])) {
			$errormsg .= "<p>Your First Name is Required.</p>";
			}
			if(empty($formfield['lastname'])) {
			$errormsg .= "<p>Your Last Name is Required.</p>";
			}
			if(empty($formfield['truck'])) {
			$errormsg .= "<p>Please Select a Location.</p>";
			}
	
			if (isset($errormsg)) {
			echo "<div class = 'msgerror'>";
			echo $errormsg;
			echo "</div>";
			} else {
				
				//Enters the data into the database
				$sqlinsert = 'INSERT INTO customer (customerfirst, customerlast,customeremail, customerphone) VALUES (:thefirst, :thelast, :theemail, :thephone)';
			
				//Prepares the SQL Statement for execution
				$stmtinsert = $db->prepare($sqlinsert);
				//Binds the associative array variables to the bound
				//variables in the sql statement
				$stmtinsert->bindvalue(':thefirst', $formfield['firstname']);
				$stmtinsert->bindvalue(':thelast', $formfield['lastname']);
				$stmtinsert->bindvalue(':theemail', $formfield['email']);
				$stmtinsert->bindvalue(':thephone', $formfield['phone']);
				//Runs the insert statement and query
				$stmtinsert->execute();

				//get the customer id
				$custid = $db->lastInsertId();
				
				$_SESSION['cart']['customer'] = $custid;
				$_SESSION['cart']['customerfirst'] = $formfield['firstname'];
				$_SESSION['cart']['customerlast'] = $formfield['lastname'];
				$_SESSION['cart']['customerphone'] = $formfield['phone'];
				$_SESSION['cart']['customeremail'] = $formfield['email'];
				$_SESSION['cart']['location'] = $formfield['truck'];

		$status =1;

		$delaytime = "00:00:00";
		
		//Insert order info into order detail table.
		$sql = "INSERT INTO orderdetail (customerid, orderdate, ordertime, statusid, orderdelaytime, ordertotal, locationid) 
		VALUES (:theid, :thedate, :thetime, :thestatus, :thedelay, :thetotal, :thelocation)";
		//Prepares the SQL Statement for execution
		$stmtdetail = $db->prepare($sql);
		$stmtdetail->bindvalue(':theid', $custid);
		$stmtdetail->bindvalue(':thedate', $date);
		$stmtdetail->bindvalue(':thetime', $time);
		$stmtdetail->bindvalue(':thestatus', 1);
		$stmtdetail->bindvalue(':thedelay', $delaytime);
		$stmtdetail->bindvalue(':thetotal', $_SESSION['cart']['ordertotal']);
		$stmtdetail->bindvalue(':thelocation',$_POST['truck']);
		$stmtdetail->execute();

		//get the number of the order
		$orderid = $db->lastInsertId();
		
		//add the order id to the session
		$_SESSION['cart']['orderid'] = $orderid;

		//loop through the products and add them to the database as well as their inventory
		foreach ($_SESSION['cartproducts'] as $orderitem) {
			$sql = "INSERT INTO orderitem(orderdetailid, productid, orderitemprice, orderitemnotes) VALUES (:orderid, :product, :itemprice, :itemnotes)";
			$orderinsert = $db->prepare($sql);
			$orderinsert->bindValue(':orderid', $orderid);
			$orderinsert->bindValue(':product', $orderitem['productid']);
			$orderinsert->bindValue(':itemprice', $orderitem['productprice']);
			$orderinsert->bindValue(':itemnotes', $orderitem['productnotes']);
			$orderinsert->execute();
			
			//update the inventory for the chosen truck

			//if the user chose truck one
			if($_SESSION['cart']['location'] == 1) {

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

			//if the user chose truck two
			} else if($_SESSION['cart']['location'] == 2) {

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

			}
		}

			//include the success page confirmation
			require_once "success.php";

			//clear the cart
			unset($_SESSION['cart']);
			
			//clear the cart
			unset($_SESSION['cartproducts']);
			
			
			//hide the checkout form
			$showform = 0;

		
	}
	
}

//deletes the item from the cart
if (isset($_POST['RemoveItem']))
{
	//subtract the price from the cart total
	$_SESSION['cart']['ordertotal'] -= $_SESSION['cartproducts'][$_POST['orderitem']]['productprice'];

	//unset the array for the item the user wishes to remove
	unset($_SESSION['cartproducts'][$_POST['orderitem']]);

}
	
	if ($showform == 1)
		{
?>

<div class="row">


<div id="info" class ="col-6">
			<h3>Customer Info</h3>
			<form action="<?php echo $_SERVER['PHP_SELF'];?>" method = "post">
			
				<input type="text" name="firstname" id="firstname" placeholder = "*First Name">
				
				<input type="text" name="lastname" id="lastname" placeholder="*Last Name">
			
				<input type="text" name="email" id="email" placeholder="Email">
			
				
				<input type="text" name="phone" id="phone" placeholder="Phone">
			
				<label >*Choose Your Location:</label><br>
				<input type="radio" id="one" name="truck" value="1">Truck One: 1725 US-17 BUS Myrtle Beach, SC 29575<br>
				<input type="radio" id="two" name="truck" value="2">Truck Two: 2100 N Kings Hwy Myrtle Beach, SC 29577
					
		<div class="button5"><input type="submit" name="myEnter" value="Complete Checkout"></div>
		<div id ="require"><p>Fields marked with an * are required.</p></div>
			</form>
	
</div>

<div id="ordersum" class="col-6 right">	
		<h3>Order Summary</h3>
		<table>
			<tr>
				<th><div class="top">Item</div></th>
				<th><div class="top">Price</div></th>
				<th><div class="note">Notes</div></th>
			</tr>
		</table>
		<table>
			
			<tr>
			
			<?php
				foreach ($_SESSION['cartproducts'] as $key => $orderitem)
				{
				echo '<tr><td><div class="name">' . $orderitem['productname'] . '</div></td><td><div class="amount">&nbsp;&nbsp;$' . $orderitem['productprice'] . '</div></td>';
				echo '<td><div class="notes">&nbsp;&nbsp;' . $orderitem['productnotes'] . '</div></td>';
				echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
				echo '<input type = "hidden" name = "orderitem" value = "'. $key .'">';
				echo '</form></td><td>';
				echo '<form action = "' . $_SERVER['PHP_SELF'] . '" method = "post">';
				echo '<input type = "hidden" name = "orderitem" value = "'. $key .'">';
				echo '<div id = "remove"><input type="submit" name="RemoveItem" value="Remove"></div>';
				echo '</form></td></tr>';
				
				}
			?>
			</tr>
		</table>
		
		<?php
			$tax = .11;
			$subtotal = $_SESSION['cart']['ordertotal'];
			$taxamount = $subtotal*$tax;
			$total = $subtotal + $taxamount;
		?>
		<div class = "total"><p>Subtotal: $<?php echo $_SESSION['cart']['ordertotal']; ?><br>
		Tax: $<?php echo number_format((float)$taxamount, 2, '.', '');?><br>
		Total: $<?php echo number_format((float)$total, 2, '.', ''); ?></p></div>
</div>	
</div><!--end row-->

	<?php
}//visible
include_once 'footer.php';
} else {
	header('Location: menu.php');
}
?>		