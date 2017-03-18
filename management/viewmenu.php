<?php
	$pagetitle = "Search Products";
	require_once "header.php";
	// Connects to database
	require_once "connect.php";
	
	$formfield['myprodname'] = $_POST['prodname'];
	$formfield['mycategory'] = $_POST['category'];
	
	$sqlselectp = "SELECT * from productcategory";
	$resultp = $db->prepare($sqlselectp);
	$resultp->execute();
	
	// Checks to see if submit has been pressed yet
	if( isset($_POST['submit']) )
	{
		if ($formfield['mycategory'] == '') {
			$sqlselect = "SELECT product.*, productcategory.categoryname 
						  FROM product, productcategory 
						  WHERE product.productname like  CONCAT('%', :theproductname, '%')
						  AND product.productcategoryid = productcategory.productcategoryid
						  ";
		}
		else {
			$sqlselect = "SELECT product.*, productcategory.categoryname 
						  FROM product, productcategory 
						  WHERE product.productname like  CONCAT('%', :theproductname, '%')
						  AND product.productcategoryid = :theproductcategoryid
						  AND product.productcategoryid = productcategory.productcategoryid
						  ";
		}
		
		$result = $db->prepare($sqlselect);
		
		$result->bindValue(':theproductname', $formfield['myprodname']);
		
		if ($formfield['mycategory'] != '') {
			$result->bindValue(':theproductcategoryid', $formfield['mycategory']);
		}
		
		$result->execute();		
	}
	else
	{
		$sqlselect = "SELECT product.*, productcategory.categoryname 
					 FROM product, productcategory 
					 WHERE product.productcategoryid = productcategory.productcategoryid
					 ";
		$result = $db->prepare($sqlselect);
		$result->execute();
	}
	
if ($visible == 1 && ($permit == 1 || $permit == 2 || $permit == 3 ))
{
?>
<div class="row">
<div class="col-6">
	<h2>Product Search</h2>
	<form action="<?php echo $_SERVER['PHP_SELF'];?>" method = "post">
		<table>
			<tr>
				<th><label for="prodname">Name</label></th>
				<td align="left"><input type="text" name="prodname" id="prodname" 
					value = "<?php echo $formfield['myprodname'] ?>" ></td>
			</tr>
			<tr>
				<th><label for="category">Category</label></th>
				<td align="left"><select name="category" id="category" >
						<option value = "">SELECT CATEGORY</option>
						<? while ($rowp = $resultp->fetch() )
							{
							echo '<option value="'. $rowp['productcategoryid'] . '">' . $rowp['categoryname'] . '</option>';
							}
						?>
					</select>
				</td>
			</tr>
			</table>
			<button type="submit" name="submit" value="Submit"/>Submit</button>&nbsp &nbsp &nbsp &nbsp
			<button type="reset" value="Reset">Reset</button>
</form></div>

<div class="col-6 right">
	<h2>Product List</h2>
<table>
	<tr>
		<th>CATEGORY</th>
		<th>NAME</th>
		<th>DESCRIPTION</th>
		<th>PRICE</th>
	</tr>
	<?php
		while($row = $result-> fetch())
		{
			echo '<tr><td>' . $row['categoryname'] . '</td><td> ' . $row['productname'] .'</td><td>'.$row['productdescription'].'</td><td> ' . '$' . $row['productprice'] . '</td></tr>';
		}
	?>	
</table>
</div>
</div>
<?php
	}//visible
	include_once 'footer.php';
?>