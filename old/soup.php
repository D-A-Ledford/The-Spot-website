<?php
	session_start();
	require_once "header.php";
	require_once "categorynavbar.php";
	//Database Connection
		require_once "connect.php";
		
	$formfield['myprodid'] = $_POST['prodid'];

	//see if order detail id is set. If so set order detail key to
	//max id.
		if (isset($_POST['orderdetid'])) {
		
			$maxid = $_POST['orderdetid'];
			
		} else {
		//SQL statment to get the order detail id if it is not all ready //set.
		$sqlmax = "SELECT MAX(orderdetailid) AS maxid from orderdetail";
			$resultmax = $db->prepare($sqlmax);
			$resultmax->execute();
			$rowmax = $resultmax->fetch();
			$maxid = $rowmax["maxid"];	
			$maxid = $maxid + 1;
		}
	
//Select statement to determine the category for this page.	
	$sqlselectc = "SELECT * from productcategory WHERE productcategoryid = 2";
	$resultc = $db->prepare($sqlselectc);
	$resultc->execute();
		
	
	
	if (isset($_POST['size']))
	{
		$formfield['size'] = trim($_POST['size']);
		
		if(empty($formfield['size'])){$errormsg .= "<p>Please select a size.</p>";}
		
		$price = "";
		
		if($formfield['size'] == "SN"){
			$price = .99;
		} else if ($formfield['size'] == "SM"){
			$price = 1.49;
		} else if ($formfield['size'] == "M"){
			$price = 2.99;
		} else if ($fromfield['size'] == "L"){
			$price = 4.49;
		}
		
		$sqlinsert = 'INSERT INTO ordertemp ( orderdetailid,
				productid, orderitemprice) VALUES ( :theordid,:theprodid,:theitemprice)';
			
			//Prepares the SQL Statement for execution
			$stmtinsert = $db->prepare($sqlinsert);
			//Binds the associative array variables to the bound
			//variables in the sql statement
			$stmtinsert->bindvalue(':theordid', $maxid);
			$stmtinsert->bindvalue(':theprodid', $formfield['myprodid']);
			$stmtinsert->bindvalue(':theitemprice', $price);

			//Runs the insert statement and query
			$stmtinsert->execute();
			
			echo '<script src="spot.js"></script>';
	}
	
	if (isset($_POST['RemoveItem']))
	{
		$sqldelete = 'DELETE FROM ordertemp 
					WHERE tempkey = :theorderitemid';
		$stmtdelete = $db->prepare($sqldelete);
		$stmtdelete->bindvalue(':theorderitemid', $_POST['orderitemid']);
		$stmtdelete->execute();
	}
	
	$sqlselecto = "SELECT ordertemp.*, product.productname from ordertemp, product WHERE product.productid = ordertemp.productid
	AND ordertemp.orderdetailid = :theordid";
	$resulto = $db->prepare($sqlselecto);
	$resulto->bindValue(':theordid', $maxid);
	$resulto->execute();
		
?>		
	<div class="row">
		<div class="col-8">
<?php 
  
    while ($rowc = $resultc->fetch() )
	{
		$sqlselectp = "SELECT * FROM product WHERE productcategoryid = :prodcat";
		$resultp = $db->prepare($sqlselectp);
		$resultp->bindValue(':prodcat', $rowc['productcategoryid']);
		$resultp->execute();
			
		while($rowp = $resultp->fetch())
		{
          
?> 
			<div class="options">
				<div class="row">
			<form action = "<?PHP echo $_SERVER['PHP_SELF'];?>" method = "post">
			
			<div class="col-4"><img class="product-img" src ="<?PHP echo $rowp['productimg'] ?>" alt="Soups" width= "150px" height="120px"></div>
			<div class="col-8">
			<h4><?PHP echo  $rowp['productname']?></h4> 
            <p><?PHP echo $rowp['productdescription'] ?></p>
			<select onchange="this.form.submit();"  name="size" id="myselect">
			<option value="" <?php if( isset($_POST['size']) && $_POST['size'] == "" )?>>Select Your Size</option>
			<option value="SN" <?php if( isset($_POST['size']) && $_POST['size'] == "SN" )?>>Snack Bowl - .99</option>
			<option value="SM" <?php if( isset($_POST['size']) && $_POST['size'] == "SM" )?>>Small-$1.49</option>
			<option value="M" <?php if( isset($_POST['size']) && $_POST['size'] == "M" )?>>Medium-$2.99</option>
			<option value="L" <?php if( isset($_POST['size']) && $_POST['size'] == "L" )?>>Large-4.49</option>
			</select> 
            <?PHP echo'<input type = "hidden" name = "orderdetid" value = "'. $maxid .'">'; ?>
			<?PHP echo '<input type = "hidden" name = "prodid" value = "'. $rowp['productid'] .'">'; ?>
			<?PHP echo '<input type = "hidden" name = "prodprice" value = "'. $rowp['productprice'] .'">'; ?>
				</div>
			</form>	
		</div>
			</div><!--End Options-->	
<?php 
	
		}  
    } 
    ?>
</div>
	<?php include_once "sidecart.php"; ?>
</div>

<?php include_once 'footer.php'; ?>	