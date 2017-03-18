<?php
//This page is the view for the menu items of the category provided
	session_start();
	//Database Connection\
	require_once "connect.php";
	require_once "header.php";

	require_once "categorynavbar.php";

	
		include_once "cartcontrol.php";

		function maxCategory($db) {
			$sql = "SELECT MAX(productcategoryid) FROM productcategory";
			$max = $db->prepare($sql);
			$max->execute();

			$maxid = $max->fetch(); 
			return $maxid[0];
		}

		$maxcat = maxCategory($db);

		$category = 1;

		if(isset($_GET['category'])) {
			$category = $_GET['category'];
		}

		//prevent the category from exceeding the categories in the database
		if($category > $maxcat) {

			//set the category to the default
			$category = 1;
		}
	
//Select statement to determine the category for this page.	
	$sqlselectc = "SELECT * from productcategory WHERE productcategoryid = :category";
	$resultc = $db->prepare($sqlselectc);
	$resultc->bindValue(':category', $category);
	$resultc->execute();

	
		function getInventory($db, $productid, $location) {
			//get the inventory for the product
			if($location == 1) {

				//getting the inventory for the truck one location
				$sql = "SELECT * FROM inventorytruckone WHERE productid = :productid";
				$inventory = $db->prepare($sql);
				$inventory->bindValue(':productid', $productid);
				$inventory->execute();

				//check if the query returned anything
				if($inventory->rowCount() >= 1) {

					//get the row
					$productinventory = $inventory->fetch();
			

					//check if the inventory can be removed without leaving a negative
					if(($productinventory['inventoryamount'] - 1) < 0 ) {
						$productavailable = false;

					//product is available
					} else {

					$productavailable = true;
				}

					//if there isn't a product in the inventory
				} else {
					$productavailable = false;
				}

			//get the inventory for truck two
			} else if($location == 2) {

					//getting the inventory for the truck two location
				$sql = "SELECT * FROM inventorytrucktwo WHERE productid = :productid";
				$inventory = $db->prepare($sql);
				$inventory->bindValue(':productid', $product);
				$inventory->execute();

				//check if the query returned anything
				if($inventory->rowCount() >= 1) {

					//get the row
					$productinventory = $inventory->fetch();

					//check if the invetory can be removed without leaving a negative
					if(($productinventory['inventoryamount'] - 1) < 0 ) {

						$productavailable = false;
					
					//the product is available
					} else {
						$productavailable = true;
					}
						//if there isn't a product in the inventory
				} else {
					$productavailable = false;
				}
			} 
			return $productavailable;
		}
		
		
?>
	<div class="row">
	<div class="col-8">	
	<div class="row">
			
<?php 
  
    while ($rowc = $resultc->fetch() )
	{
		$sqlselectp = "SELECT * FROM product WHERE productcategoryid = :prodcat";
		$resultp = $db->prepare($sqlselectp);
		$resultp->bindValue(':prodcat', $rowc['productcategoryid']);
		$resultp->execute();
			
		while($rowp = $resultp->fetch())
		{
          $available = getInventory($db, $rowp['productid'], 1);
?> 		
		
		
			<div class="options col-7">
				
				<div class="row">

			<form action = "<?PHP echo $_SERVER['PHP_SELF'] . '?category=' . $category; ?>" method = "post">
			
			<div class="col-4"><img class="product-img" src ="<?PHP echo $rowp['productimg'] ?>" alt="Product Image" width= "160px" height="140px"></div><!--end button-->
			<div class="col-8">
			<h4><?PHP echo  $rowp['productname'] ?> <?PHP echo '$'.$rowp['productprice'] ?></h4> 
				<p><?PHP echo $rowp['productdescription'] ?></p>
				<div class ="button2">
					<input type="submit" name="myEnter" value="Add To Order" <?php if($available === false) { echo 'class="btndisabled" disabled'; }?>>
					<?php if($available === false) { echo '<p class="msgerror">This product is temporarily unavailable.</p>'; } ?>
				</div>
			
			<?PHP echo '<input type = "hidden" name = "prodid" value = "'. $rowp['productid'] .'">'; ?>
			<?PHP echo '<input type = "hidden" name = "prodprice" value = "'. $rowp['productprice'] .'">'; ?>
					
			</div><!--End row-->
			</form>
			</div>
		</div><!--End options-->

<?php 
	
		}  
    } 
?>
</div>
</div>
	<?php include_once "sidecart.php"; ?>
</div>

<?php
		include_once 'footer.php';
	?>	