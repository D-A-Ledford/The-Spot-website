<?php
//This page handles all actions performed on the cart for the customer order system

		//Database Connection\
		require_once "connect.php";
		
	$formfield['productid'] = $_POST['prodid'];
	$message = "";

	//check if the cart has already been set
	if(!isset($_SESSION['cart'])) {

			//initialize the cart
			$_SESSION['cart'] = array();

			//add the details to the cart
			$_SESSION['cart']['location'] = 1;

			$_SESSION['cart']['ordertotal'] = 0;
			$_SESSION['cartproducts'] = array();
	}

		//adds the item to the cart
		if (isset($_POST['myEnter'])) {

		//get the product information from the database
		$sql = "SELECT * FROM product WHERE productid = :productid";
		$productinfo = $db->prepare($sql);
		$productinfo->bindValue(':productid', $formfield['productid']);
		$productinfo->execute();

		//get the data
		$prod = $productinfo->fetch();

		//apply to the cart
		$_SESSION['cartproducts'][] = array(
			'productid' => $prod['productid'],
			'productname' => $prod['productname'],
			'productprice' => $prod['productprice'],
			'productnotes' => ""
			);

			//add to the total of the order
			$_SESSION['cart']['ordertotal'] += $prod['productprice'];

			//let the user know the success
			$message = "Item added to cart";
	}
		//adds notes to the item
		if (isset($_POST['NoteEntry'])) {

			//set the notes entered to the corespdoning item
			$_SESSION['cartproducts'][$_POST['orderitem']]['productnotes'] = $_POST['notes'];

			//let the user know the success
			$message = "Note added";

		}

		//deletes the item from the cart
		if (isset($_POST['RemoveItem']))
		{
			//subtract the price from the cart total
			$_SESSION['cart']['ordertotal'] -= $_SESSION['cartproducts'][$_POST['orderitem']]['productprice'];

			//unset the array for the item the user wishes to remove
			unset($_SESSION['cartproducts'][$_POST['orderitem']]);

		}

		if(isset($_POST['ClearCart'])) {

			//unset the arrays for the order
			unset($_SESSION['cart']);
			unset($_SESSION['cartproducts']);
		}
