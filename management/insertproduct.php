<?php

/*Group 8
	CPT 262
	Final Project
	This page will allow for a user to add products 
	to the products table of the database.
*/
$pagetitle = "Add Product";//Displays page title
	require_once "header.php";//connects and displays header
	require_once "connect.php";//connects to database
	$errormsg = "";//sets error message to empty
	//Selects records from product category
	$sqlselectp = "SELECT * from productcategory";
	$resultp = $db->prepare($sqlselectp);
	$resultp->execute();
	
	//This code will only occur after the enter button
	//has been clicked
	//Checks if submit button was clicked
	if( isset($_POST['submit']))
	{

		//This code will place the entered data into an
		//Associative array after cleansing
		$formfield['myprodname'] = trim($_POST['prodname']);
		$formfield['myproddescr'] = trim($_POST['proddescr']);
		$formfield['myprodprice'] = $_POST['prodprice'];
		$formfield['myprodcat'] = $_POST['prodcat'];
	
		
		if(empty($formfield['myprodcat'])) {
		//Displays error message
			$errormsg .= "<p class='msgerror'>*Must enter a product category.</p>";
		}
		//Validates that the fields were entered
		//checks to see if field is empty
		if(empty($formfield['myprodname'])) {
			//Displays error message
			$errormsg .= "<p class='msgerror'>*Must enter a product name.</p>";
		}
		
		//Validates that the fields were entered
		//checks to see if field is empty
		if(empty($formfield['myproddescr'])) {
		//Displays error message
			$errormsg .= "<p class='msgerror'>*Must enter a product description.</p>";
		}
		//Validates that the fields were entered
		//checks to see if field is empty
		if(empty($formfield['myprodprice'])) {
			//Displays error message
			$errormsg .= "<p class='msgerror'>*Must enter a product price.</p>";
		}
		//Validates that the fields were entered
		//checks to see if field is empty

		
		
		//Checks if errors have occured
		if ($errormsg != "") {
			//Displays error message
			echo $errormsg;
		}
		
		else //else is no error message, proceed--
		{
			try //attempt to insert input into database
			{
				//insert statement calls columns from the product inventory
				//and assigns them values that are used to bind to input
				$sqlinsert = 'INSERT INTO product (productcategoryid, productname, productdescription, productprice)
							  VALUES (:theprodcat, :theprodname, :theproddescr, :theprodprice)';
				$stmtinsert = $db->prepare($sqlinsert);
				$stmtinsert->bindvalue(':theprodname', $_POST['prodname']);
				$stmtinsert->bindvalue(':theprodprice', $_POST['prodprice']);
				$stmtinsert->bindvalue(':theprodcat', $_POST['prodcat']);
				$stmtinsert->bindvalue(':theproddescr', $_POST['proddescr']);
				$stmtinsert->execute();
				//displays message
				echo "<div class='success'><p style='font-weight: bold'>Product entered successfully! Thank you.</p></div>";
			}	
			catch(PDOException $e)//catch any errors that may occur
			{
				echo 'ERROR!!!' .$e->getMessage();//displays message
				exit();//exits the database
			}
			
		}

	}	
	//select all fileds from the product table
	$sqlselect = "SELECT product.*, productcategory.categoryname 
				  FROM product, productcategory 
				  WHERE product.productcategoryid = productcategory.productcategoryid";
	$result = $db-> query($sqlselect);
	$result->execute();
	//checks if user has permission to view page
if ($visible == 1 && $permit == 3)
{
?>
<div class="row">
<div class="col-6">
<!--Displays title for column-->
<h2>Product Details</h2>
	<!--Self refernecing form action. Meaning that the page will refresh and itself each time
		the submit button is clicked-->
	<form action="<?php echo $_SERVER['PHP_SELF'];?>" method = "post">
	<!--An HTML table consists of the <table> element and one or more <tr>, <th>, and <td> elements.
		The <tr> element defines a table row, the <th> element defines a table header, and the <td> element 
		defines a table cell-->	
			<table>
				<!--Begin table Row-->
				<tr>
					<!--Begin Table Header-->
					<th>
					<!--Label for Product Category-->
					<label for="prodcat">Category*</label>
					<!--End Table Header-->
					</th>
					<!--Begin Table Data-->
					<td align="left">
					<!--Begin Select Statement, This will gather data from the products table 
					and turn it into selectable input-->
					<select name="prodcat" id="prodcat">
						<option value = "">SELECT CATEGORY</option>
							<? 
							//Fetches data from the products table
							while ($rowp = $resultp->fetch() )
								{
								//calls records from the products table, that can be selected by the user
								echo '<option value="'. $rowp['productcategoryid'] . '">' . $rowp['categoryname'] . '</option>';
								}
							?>
					<!--End Select-->
					</select>
					<!--End Table Data-->
					</td>
					<!--End Table Row-->
				</tr>
				<!--Begin Table Row-->
				<tr>
				<!--End Table Header-->
					<th>
					<!--Create Label for product name-->
					<label for="prodname">Name*</label>
					<!--End Table Header-->
					</th>
					<!--Begin Table Data-->
					<td align="left">
					<!--Allows user to enter name of the product-->
					<input type="text" name="prodname" id="prodname" placeholder = "Required field" />
					<!--End Table Data-->
					</td>
					<!--End Table Row-->
				</tr>
				<!--Begin Table Row-->
				<tr>
				<!--Begin Table Header-->
					<th>
					<!--Create Label for Product Description-->
					<label for="proddescr">Description*</label>
					<!--End Table Header-->
					</th>
					<!--Begin Table Data-->
					<td align="left">
					<!--Allows user to enter a product description-->
					<input type="text" name="proddescr" id="proddescr" placeholder = "Required field" />
					<!--End Table Data-->
					</td>
					<!--End Table Row-->
				</tr>
				<!--Begin Table Row-->
				<tr>
				<!--Being Table Header-->
					<th>
					<!--Creates Label for Prduct Price-->
					<label for="prodprice">Price*</label>
					<!--End Table Header-->
					</th>
					<!--Begin Table Data-->
					<td align="left">
					<!--Allows user to enter a product price and creates an auto increment/decrement
					in decimal format-->
					<input type="number" name="prodprice" id="prodprice" placeholder = "Required field" />
					<!--End Table Data-->
					</td>
					<!--End Table Row-->
				</tr>
			<!--End Table-->
			</table>
			<!--Creates clickable button that will submit form-->
			<button type="submit" name="submit" value="Submit"/>Submit</button>&nbsp &nbsp &nbsp &nbsp
			<button type="reset" value="Reset">Reset</button>
			<!--End form-->
	</form>
	</div>
	<div class="col-6 right">
	<!--Displays title for column-->
	<h2>Product List</h2>
	<!--Begin table-->
	<table>
	<!--Begin table row-->
		<tr>
		<!--Begin table headers-->
			<th>CATEGORY</th>
			<th>NAME</th>
			<th>DESCRIPTION</th>
			<th>PRICE</th>
			<!--End table row-->
		</tr>
		<?php
		//Gathers records presently in the products table and displays as table data
			while ( $row = $result-> fetch() )
				{
					echo '<tr><td>' . $row['categoryname'] . '</td><td> ' . $row['productname'] . '</td><td> ' . 
					$row['productdescription'] . '</td><td> ' . '$' . $row['productprice'] . '</td></tr>';
				}
		?>	
		<!--End table-->
	</table>
	</div>
	</div>
<?php
}
//If user doesn not have permission to view page
else {
	//Displays message
	echo "You do not have the necessary permissions to view this page.<br>Please contact your Administrator.";
}
include_once 'footer.php';//connects and displays footer
?>
	