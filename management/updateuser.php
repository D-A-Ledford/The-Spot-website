<?php
	$pagetitle = "Update Employee";//Displays the page title
	require_once "header.php";
	require_once "connect.php";

	$errormsg = "";
	
	$sqlselectep = "SELECT * from employeeposition";
	$resultep = $db->prepare($sqlselectep);
	$resultep->execute();
	
	$sqlselectl = "SELECT * from location";
	$resultl = $db->prepare($sqlselectl);
	$resultl->execute();
	
	//This code will only occur after the enter button
	//has been clicked
	if (isset($_POST['submit']) )
	{
		//This code will place the entered data into an
		//Associative array after cleansing
		$formfield['myfirstname'] = trim($_POST['firstname']);
		$formfield['mylastname'] = trim($_POST['lastname']);
		$formfield['myposition'] = $_POST['position'];
		$formfield['mylocation'] = $_POST['location'];
		$formfield['myuserpass'] = trim($_POST['userpass']);
		$formfield['myuserpass2'] = trim($_POST['userpass2']);
		$formfield['userid'] = trim($_POST['userid']);
		$_GET['userid'] = $formfield['userid'];
		
		//Validates that the fields were entered
		if(empty($formfield['myfirstname'])) {
			$errormsg .= "<p>*Must enter a first name.</p>";
		}
		if(empty($formfield['mylastname'])) {
			$errormsg .= "<p>*Must enter a last name.</p>";
		}

		if(empty($formfield['myposition'])) {
			$errormsg .= "<p>*Must select a position.</p>";
		}
		if(empty($formfield['mylocation'])) {
			$errormsg .= "<p>Must select a location.</p>";
		}
		if(empty($formfield['myuserpass'])) {
			$errormsg .= "<p>*Must enter a password.</p>";
		}
		if ($formfield['myuserpass'] != $formfield['myuserpass2']) {
			$errormsg .= "<p>*Must retype and verify password.</p>";
		}
		
		
		if ($errormsg != "") {
			echo "YOU HAVE ERRORS!!!!";
			echo $errormsg;
		}		
		else {
			
			// Creates a string of 22 of characters/digits/symbols in the substring shown.
			for ($i = 0; $i < 22; $i++) {
						$char22 .= substr("./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789", mt_rand(0, 63), 1);
					}
			//Creates a salt variable that starts with a string of our creation and
			//concatenates the random string to it.
			$salt = '$2a$07$' . $char22;
			$securepwd = crypt($formfield['myuserpass'],$salt);
			//echo $securepwd;
			//Creates the sql query
			$sqlinsert = 'UPDATE employee set employeefirst =:thefirstname, employeelast =:thelastname, employeepositionid=:theposition, 
						  locationid=:thelocation, password=:theuserpass, employeesalt=:theempsalt WHERE employeeid = :ID';
			
			//Prepares the SQL Statement for execution
			$stmtinsert = $db->prepare($sqlinsert);
			//Binds our associative array variables to the bound
			//variables in the sql statement
			$stmtinsert->bindvalue(':thefirstname', $formfield['myfirstname']);
			$stmtinsert->bindvalue(':thelastname', $formfield['mylastname']);
			$stmtinsert->bindvalue(':theposition', $formfield['myposition']);
			$stmtinsert->bindvalue(':thelocation', $formfield['mylocation']);
			$stmtinsert->bindvalue(':theuserpass', $securepwd);
			$stmtinsert->bindvalue(':theempsalt', $salt);
			$stmtinsert->bindvalue(':ID', $formfield['userid']);
			//Runs the insert statement and query
			$stmtinsert->execute();
			
			echo "IT WORKED!!";
		}
	}
	
        $sql = 'SELECT * FROM employee WHERE employeeid = :ID';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':ID', $_GET['userid']);
        $stmt->execute();
        $row = $stmt->fetch(); 
		
if ($visible == 1 && $permit == 3)
{
?>
	<form action="<?php echo $_SERVER['PHP_SELF'];?>" method = "post">
		<h2>Employee Details</h2>
		<table>
			<tr>
				<th>First Name*</th>
				<td><input type="text" name="firstname" id="firstname"
					value = "<?php echo $row['employeefirst']; ?>"	></td>
			</tr>
			<tr>
				<th>Last Name*</th>
				<td><input type="text" name="lastname" id="lastname"
					value = "<?php echo $row['employeelast']; ?>"	></td>
			</tr>
			<tr>
			<th><label for="position">Position*</label></th>
				<td><select name="position" id="position">
						<option value = "">SELECT POSITION</option>
						<?php while ($rowep = $resultep->fetch() )
							{
							if ($row['employeepositionid'] == $rowep['employeepositionid']) {
							$selected = 'selected'; 
							} else {
							$selected = '';
							}
							echo '<option value="'. $rowep['employeepositionid'] . '" ' . $selected . '>' 
							. $rowep['positionname'] . '</option>';
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
			<th><label for="location">Location*</label></th>
				<td><select name="location" id="location">
						<option value = "">SELECT LOCATION</option>
						<?php while ($rowl = $resultl->fetch() )
							{
							if ($row['locationid'] == $rowl['locationid']) {
							$selected = 'selected'; 
							} else {
							$selected = '';
							}
							echo '<option value="'. $rowl['locationid'] . '" ' . $selected . '>' 
							. $rowl['locationarea'] . '</option>';
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<th>Password*</th>
				<td><input type="password" name="userpass" id="userpass" placeholder = "Required field" /></td>
			</tr>
			<tr>
				<th>Confirm Password*</th>
				<td><input type="password" name="userpass2" id="userpass2" placeholder = "Required field" /></td>
			</tr>
		</table>
		<!--Input selection which assigns a button to press to submit webpage-->
			<button type="submit" name="submit" value="UPDATE"/>Update</button>&nbsp &nbsp &nbsp &nbsp;
			<button type="reset" value="Reset">Reset</button> &nbsp &nbsp &nbsp &nbsp;
			<button type="button" onclick="history.back();">BACK</button>
	</form>
	<br><br>
<?php
}
else {
	echo "You do not have the necessary permissions to view this page.<br>Please contact your Administrator.";
}
include_once 'footer.php';
?>