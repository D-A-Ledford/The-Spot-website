<?php
	$pagetitle = "View Menu";
	require_once "header.php";
	require_once "connect.php";
	
	$sqlselectsa = "SELECT product.*, productcategory.categoryname FROM product, productcategory WHERE 
					product.productcategoryid = productcategory.productcategoryid
					AND productcategory.productcategoryid = '1'
					";
	$resultsa = $db->prepare($sqlselectsa);
	$resultsa->execute();	
	
	$sqlselectso = "SELECT product.*, productcategory.categoryname FROM product, productcategory WHERE
					product.productcategoryid = productcategory.productcategoryid
					AND productcategory.productcategoryid = '2'
					";
	$resultso = $db->prepare($sqlselectso);
	$resultso->execute();
	
	$sqlselectsi = "SELECT product.*, productcategory.categoryname FROM product, productcategory WHERE
					product.productcategoryid = productcategory.productcategoryid
					AND productcategory.productcategoryid = '3'
					";
	$resultsi = $db->prepare($sqlselectsi);
	$resultsi->execute();
	
	$sqlselectd = "SELECT product.*, productcategory.categoryname FROM product, productcategory WHERE
					product.productcategoryid = productcategory.productcategoryid
					AND productcategory.productcategoryid = '4'
					";
	$resultd = $db->prepare($sqlselectd);
	$resultd->execute();

if ($visible == 1 && ($permit == 1 || $permit == 2 || $permit == 3 ))
{	
?>
	<form action="<?php echo $_SERVER['PHP_SELF'];?>" method = "post">
		<fieldset>
		<table>
			<tr>
				<td style ="vertical-align: top;">
					<table style="margin-right: 65px;" cellpadding="2">
					<caption style="font-weight: bolder; text-decoration: underline;">SANDWICHES</caption>
					<?php
					while ( $rowsa = $resultsa-> fetch() )
					{
						echo '<tr><td>' . $rowsa['productname'] . '</td><td style="text-align: right;"> ' . '$' .$rowsa['productprice'] . '</td></tr>';
					}
					?>	
					</table>
				</td>
				
				<td style ="vertical-align: top;">
					<table style="margin-right: 75px;" cellpadding="2">
					<caption style="font-weight: bolder; text-decoration: underline;">SOUPS</caption>
					<?php
					while ( $rowso = $resultso-> fetch() )
					{
						echo '<tr><td>' . $rowso['productname'] . '</td><td style="text-align: right;"> ' . '$' .$rowso['productprice'] . '</td></tr>';
					}
					?>
					</table>
				</td>
				<td style ="vertical-align: top;">
					<table style="margin-right: 75px; "cellpadding="2">
					<caption style="font-weight: bolder; text-decoration: underline;">SIDES</caption>
					<?php
					while ( $rowsi = $resultsi-> fetch() )
					{
						echo '<tr><td>' . $rowsi['productname'] . '</td><td style="text-align: right;"> ' . '$' .$rowsi['productprice'] . '</td></tr>';
					}
					?>	
					</table>
				</td>
				<td style ="vertical-align: top;">
					<table style="margin-right: 75px;" cellpadding="2">
					<caption style="font-weight: bolder; text-decoration: underline;">DRINKS</caption>
					<?php
					while ( $rowd = $resultd-> fetch() )
					{
						echo '<tr><td>' . $rowd['productname'] . '</td><td style="text-align: right;"> ' . '$' .$rowd['productprice'] . '</td></tr>';
					}
					?>	
					</table>
				</td>		
			</tr>
		</table>
</fieldset>
<?php
}include_once 'footer.php';
?>