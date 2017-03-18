<?php //Starting tag for php code

/*Group 8
	CPT 262
	Final Project
	This page will allow for a user to add employees information into a database with 
	php.
*/
	$pagetitle = "Add Employee";//Displays the page title
	require_once "header.php";//Calls for header page to display html elements
	require_once "connect.php"; //connects to connect.php which houses verification for logging into the database.
	$errormsg = ""; //declare variable errormsg as an empty string.
	//connection to the database
	
	//Accesses the employeepsoition table to collect information to select for input/output
	$sqlselectemp = "SELECT * from employeeposition";
	$resultemp = $db->prepare($sqlselectemp);
	$resultemp->execute();
	//Access the location table to collect information to select for input/output
	$sqlselectloc = "SELECT * from location";
	$resultloc = $db->prepare($sqlselectloc);
	$resultloc->execute();
	
	
	if (isset($_POST['submit'])) //will determine what happens when a user clicks the submit button.
	{
		
		
		//This code will place the entered data into an
		//associative array after cleansing. Trim will empty and whitespace
		//accidental entered by the user.
		$formfield['uname'] = trim($_POST['uname']);
		$formfield['pass'] = trim($_POST['pass']);		
		$formfield['pass2'] = trim($_POST['pass2']);
		$formfield['position'] = trim($_POST['position']);
		$formfield['first'] = trim($_POST['first']);
		$formfield['last'] = trim($_POST['last']);
		$formfield['location'] = trim($_POST['location']);

	//checks if the username is empty
	if(empty($formfield['uname']))
	{$errormsg .= "<p class='msgerror'>*Must enter a username.</p>";} //Error massage will display if user did not enter into field
	//checks if password was entered and if they match
	if(empty($formfield['pass']))
	{
		$errormsg .= "<p class='msgerror'>*Must enter a password.</p>";//Error massage will display if user did not enter into field
	}
	if($formfield['pass'] != $formfield['pass2'])
	{
		$errormsg .= "<p class='msgerror'>*Passwords do not match.</p>";//Error massage will display if user did not enter into field
	}
	//Checks if position was entered
	if(empty($formfield['position']))
	{$errormsg .= "<p class='msgerror'>*Must select a position.</p>";} //Error massage will display if user did not enter into field
	//Checks if first name was entered
	if(empty($formfield['first']))
	{$errormsg .= "<p class='msgerror'>*Must enter a first name.</p>";} //Error massage will display if user did not enter into field
	//Checks if last name was entered
	if(empty($formfield['last']))
	{$errormsg .= "<p class='msgerror'>*Must enter a last name.</p>";}//Error massage will display if user did not enter into field
	//Checks if location was entered
	if(empty($formfield['location']))
	{$errormsg .= "<p class='msgerror'>*Must select a location.</p>";}//Error massage will display if user did not enter into field
	
		//Checks if any Error messages are occur
	if($errormsg !="") //will test to see if any error messages where flagged and displays those errors
	{
		echo $errormsg;
	}
	else
	{
		//in case there is an error on insert into the database, we use a try statement
		try
		{
			// Creates a string of 22 of characters/digits/symbols in the substring shown.
			for ($i = 0; $i < 22; $i++) {
						$char22 .= substr("./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789", mt_rand(0, 63), 1);
					}
			//Creates a salt variable that starts with a string of our creation and
			//concatenates the random string to it.
			$salt = '$2a$07$' . $char22;
			$securepwd = crypt($formfield['pass'],$salt);
			//enter data into the database using this insert command followed by the fields entered.
			$sqlinsert ='INSERT INTO employee (employeefirst,
												employeelast, 
												employeeusername,
												password, 
												employeesalt,
												employeepositionid,
												locationid)
												
										VALUES(:ffirst, 
										:flast,
										:funame, 
										:fpass, 
										:fsalt, 
										:fposition,
										:flocation)';
			$stmtinsert  = $db->prepare($sqlinsert);//prepares the insert command
			$stmtinsert->bindValue(':ffirst', $_POST['first']);//bind values from formfield with values from HTML doc.
			$stmtinsert->bindValue(':flast', $_POST['last']);//bind values from formfield with values from HTML doc.
			$stmtinsert->bindValue(':funame', $_POST['uname']);//bind values from formfield with values from HTML doc.
			$stmtinsert->bindValue(':fpass', $securepwd);//bind values from formfield with values from HTML doc.
			$stmtinsert->bindvalue(':fsalt', $salt); //binds salt
			$stmtinsert->bindValue(':fposition', $_POST['position']);//bind values from formfield with values from HTML doc.
			$stmtinsert->bindValue(':flocation', $_POST['location']);//bind values from formfield with values from HTML doc.

			$stmtinsert->execute();//executes insert statement
			echo "<div class='success'><p>There are no errors. Thank you.</p></div>";//Displays if no errors occur
			echo '<p>Your form was submitted!!</p>'; //displays a message if all fiends entered are valid.
		}
		catch(PDOException $e)//if errors occur message will display
		{
			echo 'ERROR!!' .$e->getMessage();
			exit();
		}
	}
	
	}
	//Selects the employee table and concatenates values from different tables
	//to display names instead of numeric ids.
	$sqlselect = "SELECT employee.*, employeeposition.positionname, 
				location.locationarea FROM employee, employeeposition, location WHERE 
					  employee.employeepositionid = employeeposition.employeepositionid
					  AND employee.locationid = location.locationid
					  ";
	$result = $db->prepare($sqlselect);
	$result->execute();	//query is used to display results
	//Determine if user has access to page
	//if ($visible == 1 && $permit == 3)
	//{
	
	?><!--End php tag-->
		<!--When the form is submitted, the form data is sent with method="post".
		the $_SERVER["PHP_SELF"] sends the submitted form data to the page itself, 
		instead of jumping to a different page.-->
		<div class="row">
		<div class="col-6">
		<form action="<?php echo $_SERVER['PHP_SELF'];?>" method = "post">
			<!--The <fieldset> tag is used to group related elements in a form
			The <legend> tag defines a caption for the <fieldset> element-->
		<h2>Employee Details</h2>
				<table><!--An HTML table consists of the <table> element and one or more <tr>, <th>, and <td> elements.
						The <tr> element defines a table row, the <th> element defines a table header, and the <td> element defines a table cell-->
					<tr>
						<th><label form="first">First Name*</label></th><!--creates a label-->
						<td><input type="text" name="first" id="first" placeholder = "Required field"/>
						</td><!--creates an input box and determine if email is in valid format-->
					</tr>
					<tr>
						<th><label form="last">Last Name*</label></th><!--creates a label-->
						<td><input type="text" name="last" id="last" placeholder = "Required field"/></td><!--creates an input box -->
					</tr>
					<tr>
						<th><label form="uname">User Name*</label></th><!--creates a label-->
						<td><input type="text" name="uname" id="uname" placeholder = "Required field"/>
						</td><!--creates an input box and determine if email is in valid format-->
					</tr>
					<tr>
						<th><label form="pass">Password*</label></th><!--creates a label-->
						<td><input type="password" name="pass" id="pass" placeholder = "Required field"/></td><!--creates an input box and hides password entered-->
					</tr>
					<tr>
						<th><label form="pass">Password Verify*</label></th><!--creates an input box-->
						<!--creates and input box and hides password entered, the value php tag will determine if the password entered 
						matches the password from previous input -->
						<td><input type="password" name="pass2" id="pass2" placeholder = "Required field" /></td>
					</tr>
					
					
					</tr>
					<th><label form="position">Position*</label></th><!--Name of label-->
					<td>
					<div class="select-styled">
					<select name="position" id="position"><!--The select creates a drop selection box-->
						<option value = "">SELECT POSITION</option><!--Default text of selection field-->
						<?php while ($rowemp = $resultemp->fetch() )//In order to eliminate user error, the selection box is populated
																//with records from the Ordertype table
							{
							echo '<option value="'. $rowemp['employeepositionid'] . '">' . $rowemp['positionname'] . '</option>';
							}
						?>
					</select>
					</div>
				</td>
				</tr>
					<tr>
					
						<th><label for="location">Location*</label></th><!--Name of label-->
						<td>
						<div class="select-styled">
						<select name="location" id="location"><!--The select creates a drop selection box-->
						<option value = "">SELECT LOCATION</option><!--Default text of selection field-->
						<?php while ($rowloc = $resultloc->fetch() )//In order to eliminate user error, the selection box is populated
																//with records from the Ordertype table
							{
							echo '<option value="'. $rowloc['locationid'] . '">' . $rowloc['locationarea'] . '</option>';
							}
						?>
					</select>
					</div>
				</td></tr>
			</table><!--end table--><br>
			<button type="submit" name="submit" value="Submit"/>Submit</button>&nbsp &nbsp &nbsp &nbsp
			<button type="reset" value="Reset">Reset</button>	
		</form><!--end form-->
		<br><br><!--page break x2-->
		</div>
		<div class="col-6 right">
		<h2>Employee List</h2>
		<table><!--An HTML table consists of the <table> element and one or more <tr>, <th>, and <td> elements.
						The <tr> element defines a table row, the <th> element defines a table header, and the 
						<td> element defines a table cell-->
		<tr>
			<th>FIRST NAME</th>
			<th>LAST NAME</th>
			<th>USERNAME</th>
			<th>POSITION</th>
			<th>LOCATION</th>
			
		</tr>
		<?php //begin php code fro retrieving database information
		while ($row = $result-> fetch() )//while the input matches the database, 
											//this will loop until all information enter is matched with existing fields 
											//with in the database
		{
			echo '<tr><td>' .$row['employeefirst'] . '</td><td>' .$row['employeelast'] . '</td><td>' 
			. $row['employeeusername'] . '</td><td>' .$row['positionname']
			. '</td><td>' . $row['locationarea']. '</td></tr>';//displays the results in the table
		}
		?>
		</table><!--end table-->
		</div>
		</div>
<div id="footer">
<?php
	//}//visible
	include_once 'footer.php';//Displays and connects to footer page.
?>
</div>