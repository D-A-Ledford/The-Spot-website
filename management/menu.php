<?php
require_once "connect.php";
//if logged in, only show the logout link.  if not logged in, show the login link
if(isset($_SESSION['userid']))
{

	$sql = 'SELECT employeepositionid FROM employee
			WHERE employeeid = :userid';
    $pos = $db->prepare($sql);
    $pos->bindValue(':userid', $_SESSION['userid']);
    $pos->execute();
    $position = $pos->fetch();
    
	$permit = 0;
	echo '<ul>';
	
	if ($position['employeepositionid'] >= 1)
	{
		echo '<li><a href="">Product</a>
			<ul>
				<li><a href="viewmenu.php">View Menu</a></li>';
		if ($position['employeepositionid'] == 3) {		
				echo'<li><a href="insertproduct.php">Insert Product</a></li>';
		}	
			echo '</ul>
		</li>	
		<li><a href="">Order</a>
			<ul>
				<li><a href="vieworders.php">View Orders</a></li>
				<li><a href="insertorderitem.php">Insert Order</a></li>
			</ul>
		</li>';
	}

	if ($position['employeepositionid'] >= 2)
	{
		$permit = 2;
	}
	
	if ($position['employeepositionid'] == 3)
	{

		$permit = 3;
		
		echo '<li><a href="">Employee</a>
			<ul>
				<li><a href="searchemployees.php">Search Employee</a></li>
				<li><a href="insertemployee.php">Insert Employee</a></li>
			</ul>
		</li>
		<li><a href="">Inventory</a>
			<ul>
				<li><a href="insertinventoryhome.php">Home Base Inventory</a></li>
				<li><a href="insertinventorytruckone.php">Truck One Inventory</a></li>
				<li><a href="insertinventorytrucktwo.php">Truck Two Inventory</a></li>
			</ul>
		</li>';
	}
	
		echo'<li><a href="logout.php">Logout</a></li>
	</ul>';
	$visible = 1;
}	
else {
	echo '<li><a href="login.php>Login</a></li>';
	$visible =0;
}	
		


?>		