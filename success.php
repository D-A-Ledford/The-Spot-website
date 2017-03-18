<?php
session_start();
//this page is pulled into the cart.php when the user successfully completes their order

	//check if the id has been added to the session to prevent url jumps
	if(!empty($_SESSION['cart']['orderid'])) {

		if ($_SESSION['cart']['location'] == 1){
			$address = "1725 US-17 BUS Surfside Beach, SC 29575";
		} else if ($_SESSION['cart']['location'] == 2){
			$address = "2100 N Kings Hwy Myrtle Beach, SC 29577";
		}
		
?>
		<div class = "success">
			<h3>Thank You <?php echo $_SESSION['cart']['customerfirst'] ?> For Your Purchase</h3>
			<p> Your order can be picked up at: <?php echo $address; ?></p>
			<h3>Customer Information</h3>
			<table>
				<tr>
					<th>Name</th>
					<td><?php echo $_SESSION['cart']['customerfirst'] ?></td>
					<td><?php echo $_SESSION['cart']['customerlast'] ?></td>
				</tr>
				<tr>
					<th>Date &amp; Time</th>
					<td><?php echo $_SESSION['cart']['date'] ?></td>
					<td><?php echo $_SESSION['cart']['time'] ?></td>
				</tr>
				<tr>
					<th>Phone &amp; Email</th>
					<td><?php echo $_SESSION['cart']['customerphone'] ?></td>
					<td><?php echo $_SESSION['cart']['customeremail'] ?></td>
				</tr>
			</table>
				

			<h3>Order Review</h3>
			<table>
				<tr>
					<th>Product</th>
					<th>Price</th>
					<th>Notes</th>
				</tr>
				<?php
				foreach ($_SESSION['cartproducts'] as $key => $orderitem)
				{ ?>
					<tr>
						<td><?php echo $orderitem['productname']; ?></td>
						<td>$<?php echo $orderitem['productprice']; ?></td>
						<td><?php echo $orderitem['productnotes']; ?></td>
					</tr>
					<?php } ?>
					<?php
						$tax = .11;
						$subtotal = $_SESSION['cart']['ordertotal'];
						$taxamount = $subtotal*$tax;
						$total = $subtotal + $taxamount;
					?>
					<tr>
						<td class="totalline">Subotal:<br>Tax:<br>Total:</td>
						<td class="totalline">$<?php echo $_SESSION['cart']['ordertotal']; ?><br>$<?php echo number_format((float)$taxamount, 2, '.', '');?><br>$<?php echo number_format((float)$total, 2, '.', '');?> </td>
					</tr>
				
			</table>
		</div>
<?php
	} else {
		header("Location: index.php");
	} ?>