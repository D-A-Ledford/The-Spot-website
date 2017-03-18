<?php
require_once("connect.php");

/***** DATABASE ACCESS FUNCTIONS *****/

function getCategory($db) {
	$sql = "SELECT * FROM productcategory";
	$result = $db->prepare($sql);
	$result->execute();
	return $result;
}

function getProduct($db) {
	$sql = "SELECT * FROM product";
	$result = $db->prepare($sql);
	$result->execute();
	return $result;
}

function getCustomer($db) {
	$sql = "SELECT * FROM customer";
	$result = $db->prepare($sql);
	$result->execute();
	return $result;
}

function getEmployee($db) {
	$sql = "SELECT * FROM employee";
	$result = $db->prepare($sql);
	$result->execute();
	return $result;
}

function getInventoryMain($db) {
	$sql = "SELECT * FROM inventory";
	$result = $db->prepare($sql);
	$result->execute();
	return $result;
}

function getInventoryTruckOne($db) {
	$sql = "SELECT * FROM inventorytruckone";
	$result = $db->prepare($sql);
	$result->execute();
	return $result;
}

function getInventoryTruckTwo($db) {
	$sql = "SELECT * FROM inventorytrucktwo";
	$result = $db->prepare($sql);
	$result->execute();
	return $result;
}

function getLocation($db) {
	$sql = "SELECT * FROM location";
	$result = $db->prepare($sql);
	$result->execute();
	return $result;
}

function getNavMenu($db) {
	$sql = "SELECT * FROM navmenu";
	$result = $db->prepare($sql);
	$result->execute();
	return $result;
}

function getOrderDetail($db) {
	$sql = "SELECT * FROM orderdetail";
	$result = $db->prepare($sql);
	$result->execute();
	return $result;
}

function getOrderTemp($db) {
	$sql = "SELECT * FROM ordertemp";
	$result = $db->prepare($sql);
	$result->execute();
	return $result;
}

function getStatus($db) {
	$sql = "SELECT * FROM status";
	$result = $db->prepare($sql);
	$result->execute();
	return $result;
}

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
				$inventory->bindValue(':productid', $productid);
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
			} else if($location == 3) {

					//getting the inventory for the truck two location
				$sql = "SELECT * FROM inventory WHERE productid = :productid";
				$inventory = $db->prepare($sql);
				$inventory->bindValue(':productid', $productid);
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

		//determines if the product in the database has a size
		function hasSizes($db, $productcatid) {
			$sql = "SELECT * FROM sizes WHERE productcategoryid = :catid";
			$select = $db->prepare($sql);
			$select->bindValue('catid', $productcatid);
			$select->execute();

			if($select->rowCount() <= 0) {
				$hassizes = false;
			} else {
				$hassizes = true;
			}
			return $hassizes;
		}

		//gets the sizes
		function getSizes($db, $catid) {
			$sql = "SELECT * FROM sizes
			RIGHT JOIN product
			ON product.productcategoryid = sizes.productcategoryid 
			WHERE sizes.productcategoryid = :catid";
			$select = $db->prepare($sql);
			$select->bindValue('catid', $catid);
			$select->execute();
			return $select;
		}

		function getProducts($db, $catid) {
			//get the products from that category
			$sql = "SELECT * FROM product WHERE productcategoryid = :category";
			$product = $db->prepare($sql);
			$product->bindvalue(":category", $catid);
			$product->execute();
			return $product;
		}