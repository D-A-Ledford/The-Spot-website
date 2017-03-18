<?php
	$pagetitle = "Search Employees";
	require_once "header.php";
	require_once "connect.php";
	
	$formfield['myfirstname'] = $_POST['firstname'];
	$formfield['mylastname'] = $_POST['lastname'];
	$formfield['myposition'] = $_POST['position'];
	$formfield['mylocation'] = $_POST['location'];
	
	$sqlselecte = "SELECT * from employeeposition";
	$resulte = $db->prepare($sqlselecte);
	$resulte->execute();
	
	$sqlselectl = "SELECT * from location";
	$resultl = $db->prepare($sqlselectl);
	$resultl->execute();
	
	if (isset($_POST['submit']))
	{
		if ($formfield['myposition'] == '') 
		{
		
			if ($formfield['mylocation'] == '') 
			{
			
				$sqlselect = "SELECT employee.*, employeeposition.positionname, location.locationarea FROM employee, employeeposition, location WHERE
							  employeefirst like  CONCAT('%', :thefirstname, '%')
							  AND employeelast like  CONCAT('%', :thelastname, '%')
						      AND employee.employeepositionid =  employeeposition.employeepositionid
						      AND employee.locationid =  location.locationid
						      ";
			}
			else 
			{
				$sqlselect = "SELECT employee.*, employeeposition.positionname, location.locationarea FROM employee, employeeposition, location WHERE 	
							  employeefirst like  CONCAT('%', :thefirstname, '%')
							  AND employeelast like  CONCAT('%', :thelastname, '%')
						      AND employee.locationid = :thelocation
							  AND employee.employeepositionid =  employeeposition.employeepositionid
							  AND employee.locationid =  location.locationid
							";
			}
	
		}
		else 
		{
			if ($formfield['mylocation'] == '')
			{
				$sqlselect = "SELECT employee.*, employeeposition.positionname, location.locationarea FROM employee, employeeposition, location WHERE
							  employeefirst like  CONCAT('%', :thefirstname, '%')
							  AND employeelast like  CONCAT('%', :thelastname, '%')					
						      AND employee.employeepositionid = :theposition 
						      AND employee.employeepositionid = employeeposition.employeepositionid
						      AND employee.locationid =  location.locationid
						     ";
			}
			else 
			{
				$sqlselect = "SELECT employee.*, employeeposition.positionname, location.locationarea FROM employee, employeeposition, location WHERE
							  employeefirst like  CONCAT('%', :thefirstname, '%')
							  AND employeelast like  CONCAT('%', :thelastname, '%')	
							  AND employee.locationid = :thelocation							  
						      AND employee.employeepositionid = :theposition 
						      AND employee.employeepositionid = employeeposition.employeepositionid
						      AND employee.locationid =  location.locationid
						     ";
			}
		}
		
		
		$result = $db->prepare($sqlselect);
		
		
		$result->bindValue(':thefirstname', $formfield['myfirstname']);
		$result->bindValue(':thelastname', $formfield['mylastname']);
		
		if ($formfield['myposition'] != '') {
			$result->bindValue(':theposition', $formfield['myposition']);
		}

		if ($formfield['mylocation'] != '') {
			$result->bindValue(':thelocation', $formfield['mylocation']);
		}
	
		$result->execute();	
	}
		
	else
	{
		$sqlselect = "SELECT employee.*, employeeposition.positionname, location.locationarea FROM employee, employeeposition, location WHERE 
					  employee.employeepositionid = employeeposition.employeepositionid
					  AND employee.locationid = location.locationid
					  ";
		$result = $db->prepare($sqlselect);
		$result->execute();
	}
		
if ($visible == 1)
{
?>
<div class="row">
<div class="col-6">
	<h2>Employee Details</h2>
	<form action="<?php echo $_SERVER['PHP_SELF'];?>" method = "post">	
		<table>
			<tr>
				<th>First Name</th>
				<td align="left"><input type="text" name="firstname" id="firstname" 
					value = "<?php echo $formfield['myfirstname'] ?>" ></td>
			</tr>
			<tr>
				<th>Last Name</th>
				<td align="left"><input type="text" name="lastname" id="lastname" 
					value = "<?php echo $formfield['mylastname'] ?>" ></td>
			</tr>
			<tr>
				<th><label for="position">Position</label></th>
				<td align="left"><select name="position" id="position">
						<option value = "">SELECT POSITION</option>
						<? while ($rowe = $resulte->fetch() )
							{
								echo '<option value="'. $rowe['employeepositionid'] . '">' . $rowe['positionname'] . '</option>';
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<th><label for="location">Location</label></th>
				<td align="left"><select name="location" id="location">
						<option value = "">SELECT LOCATION</option>
						<? while ($rowl = $resultl->fetch() )
							{
								echo '<option value="'. $rowl['locationid'] . '">' . $rowl['locationarea'] . '</option>';
							}
						?>
					</select>
				</td>
			</tr>
		</table>
			<button type="submit" name="submit" value="Submit"/>Submit</button>&nbsp &nbsp &nbsp &nbsp
			<button type="reset" value="Reset">Reset</button>
		</form>
		</div>
		<div class="col-6 right">
		<h2>Employee List</h2>
		<table>
		<tr>
			<th>FIRST NAME</th>
			<th>LAST NAME</th>
			<th>POSITION</th>
			<th>LOCATION</th>
			<th>EDIT</th>
			<th>DELETE</th>
		</tr>
		<?php
			while ($row = $result->fetch() )
			{
				if ($permit == 3)
				{
					echo '<tr><td>' . $row['employeefirst'] . '</td><td>' . $row['employeelast']. '</td><td>' . $row['positionname'] . '</td><td>' . $row['locationarea']
					. '</td><td><a href="updateuser.php?userid=' . $row['employeeid'] 
					. '"><img src="images/edit.png" alt="Update Info" width="35" height"35"></a></td><td><a href="delete.php?userid=' . $row['employeeid']
					. '"><img src="images/delete.png" alt="Delete User" width="25" height"25"></a></td></tr>';
				}
				else 
				{
					echo '<tr><td>' . $row['employeefirst'] . '</td><td>' . $row['employeelast']
					. '</td><td>' . $row['positionname'] . '</td><td>' . $row['locationarea'] . '</td>                </tr>';
				}				
			}
			?>
		</table></div></div>
<?php
}
include_once 'footer.php';
?>