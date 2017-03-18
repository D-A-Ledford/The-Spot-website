<?php
	//this page is the category navigation for the menu items
//this page provides the menu items page with the category

	$sql = "SELECT * FROM productcategory";
	$cat = $db->prepare($sql);
	$cat->execute();
?>

<div class="catnavbar">
	<ul class="catnav">
		<?php
		while ($rowcat = $cat->fetch() )
		{ ?>
		<li><a class ="active" href = "<?php echo $_SERVER['PHP_SELF'] . '?category=' . $rowcat['productcategoryid']; ?>"><?php echo $rowcat['categoryname']; ?></a></li>
		<?php } ?>
	</ul>

</div>