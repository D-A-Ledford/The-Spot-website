<?php
	require_once("connect.php");
	$pagetitle = "Search Orders";
	require_once("header.php");
	
	function getLocation($db, $locationid) {
		$sql = "SELECT * FROM location WHERE locationid = :locationid";
		$location = $db->prepare($sql);
		$location->bindValue(':locationid', $locationid);
		$location->execute();
		$locationdata = $location->fetch();
		return $locationdata['locationarea'];
	}
	
	function getOrderStatus($db) {
		$sql = "SELECT * FROM status";
		$status = $db->prepare($sql);
		$status->execute();
		return $status->fetchAll();
	}
	
	function getOrderStatusName($db, $statusid) {
		$sql = "SELECT * FROM status WHERE statusid = :status";
		$status = $db->prepare($sql);
		$status->bindValue(':status', $statusid);
		$status->execute();
		$statusdata = $status->fetch();
		return $statusdata['statusname'];
	}
	
	
	
	if(isset($_POST['CloseOrder'])) {
		$orderid = $_POST['orderid'];
		
		$sql = "UPDATE orderdetail SET statusid = 2 WHERE orderdetailid = :orderid";
		$update = $db->prepare($sql);
		$update->bindValue(':orderid', $orderid);
		$update->execute();
	}
	
	if(isset($_POST['OpenOrder'])) {
		$orderid = $_POST['orderid'];
		
		$sql = "UPDATE orderdetail SET statusid = 1 WHERE orderdetailid = :orderid";
		$update = $db->prepare($sql);
		$update->bindValue(':orderid', $orderid);
		$update->execute();
	}
			
			//The intial orders to get are open order
			$status = 1;
			
			//Handles the status view change
			if(isset($_POST['status'])) {
				$status = $_POST['status'];
			}
			
			//get the order details		
			$sqlselect = "SELECT DISTINCT orderdetail.*, customer.customerfirst, employee.employeefirst FROM orderdetail 
			LEFT JOIN customer 
			ON customer.customerid = orderdetail.customerid 
			LEFT JOIN employee 
			ON employee.employeeid = orderdetail.employeeid 
			WHERE statusid = :status
			ORDER BY orderdetail.orderdetailid";
			$orderresults = $db->prepare($sqlselect);
			$orderresults->bindValue(':status', $status);
			$orderresults->execute();

			//get the array
			$orderdetaila = $orderresults->fetchAll();

			//loop through the order details to get the IDs
			for($i = 0; $i < count($orderdetaila); $i++) {

				//store the IDs in an array
				$orderdetailid[$i] = $orderdetaila[$i]['orderdetailid'];

				//select statement to get the products related to the corresponding ID
				$sql = "SELECT product.*, orderitem.* FROM orderitem
				LEFT JOIN product
				ON product.productid = orderitem.Productid
				RIGHT JOIN orderdetail
				ON orderitem.orderdetailid = orderdetail.orderdetailid
				WHERE orderitem.orderdetailid = :orderid
                		ORDER BY product.productCategoryid";
				$productresults = $db->prepare($sql);
				$productresults->bindValue(':orderid', $orderdetailid[$i]);
				$productresults->execute();

				//Store the products into an array
				$products[$i] = $productresults->fetchAll();
				//print_r($products);
			}



	
	if($_SESSION['usertype'] >= 1) {
?>
		<section>
		<div class="row">
		<div class="col-12">
		<form id="statusform" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<select id ="status" name="status" form="statusform">
			<?php $statusdata = getOrderStatus($db);
			foreach($statusdata as $s) { ?>
			
			<option value="<?php echo $s['statusid']; ?>" <?php if($s['statusid'] == $status) { echo "selected"; } ?>>View <?php echo $s['statusname']; ?> Orders</option>
			<?php } ?>
		</select>
		 <input class="button" type="hidden" name="StatusChange" value="Submit">
		</form>

					<?php 
						for($i = 0; $i < count($orderdetaila); $i++) {

							//get the id for the table
							$orderdetailid = $orderdetaila[$i]['orderdetailid'];
							
							echo '<table id="orderdetails">';
							echo '<tr><td colspan="2"><b>Order #:</b> ' . $orderdetailid . '</td></tr>';
							echo '<tr><td><b>Order Date:</b> ' . $orderdetaila[$i]['orderdate'] . '</td>';
							echo '<td><b>Order Time:</b> ' . $orderdetaila[$i]['ordertime'] . '</td></tr>';
							
							if(isset($orderdetaila[$i]['customerfirst'])) {
							echo '<tr><td><b>Customer Name:</b> ' . $orderdetaila[$i]['customerfirst'] . '</td>';
							echo '<td><b>Order Location:</b> Online Order</td></tr>';
							} else {
							echo '<tr><td><b>Employee Name:</b> ' . $orderdetaila[$i]['employeefirst'] . '</td>';
							
							echo '<td><b>Order Location:</b> ' . getLocation($db, $orderdetaila[$i]['locationid']) . '</td></tr>';
							}
							
							echo '<tr><td><b>Product(s) Ordered:</b></td>';
							echo '<td><b>Product Price:</b></td></tr>';
							
							for($p = 0; $p < count($products[$i]); $p++) {
								echo '<tr class="productsrow"><td>' . $products[$i][$p]['productname'] . '</td>';
								echo '<td>' . $products[$i][$p]['orderitemprice'] . '</td></tr>';
								 if(!empty($products[$i][$p]['orderitemnotes'])) { echo '<tr><td>Item Notes:</td><td>' . $products[$i][$p]['orderitemnotes'] . '</td></tr>'; }
							
							}
							echo '<tr><td><b>Total Cost:</b></td><td>' . $orderdetaila[$i]['ordertotal'] . '</td></tr>';
							echo '<tr><td><b>Status:</b> ' . getOrderStatusName($db, $orderdetaila[$i]['statusid']) . '</td>';
							
							//if the order is open display the closed button
							//if the user is a manager display the re-open button
							if($orderdetaila[$i]['statusid'] == 2) {
							if($_SESSION['usertype'] == 3) {
								echo '<td><form method="POST" action='. $_SERVER['PHP_SELF'] . '>
								<input type="hidden" name="orderid" value="' . $orderdetailid . '">
								<input class="button" type="submit" name="OpenOrder" value="Re-open Order"></form></tr>';
								}
							} else {
								echo '<td><form method="POST" action='. $_SERVER['PHP_SELF'] . '>
								<input type="hidden" name="orderid" value="' . $orderdetailid . '">
								<input class="button" type="submit" name="CloseOrder" value="Close Order"></form></td></tr>';
							}

							echo '</table>';
						}
					?>
					</div>
					</div>
</section>







<?php
} else {
	echo '<p>You are not autorized to access this page</p>';
}
	require_once("footer.php");
?>