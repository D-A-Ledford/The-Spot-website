<?php
$pagetitle = "Login";
require_once 'connect.php';


if(isset($_SESSION['userid']))
{
	require_once 'header.php';
    echo "<p class='error'>You are already logged in.</p>";
    include_once 'footer.php';
    exit();
}

$showform = 1;
$errormsg = "";

if($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	 session_start();
    //CLEANSE DATA THE SAME AS THE REGISTRATION PAGE
    $formfield['mypwd'] = trim($_POST['pwd']);

        try
        {
            $sql = 'SELECT * FROM employee WHERE password = :thepwd';
            $s = $db->prepare($sql);
            $s->bindValue(':thepwd', $formfield['mypwd']);
            $s->execute();
            $count = $s->rowCount();
        }
        catch (PDOException $e)
        {
            echo 'Error fetching users: ' . $e->getMessage();
            exit();
        }
        //if query okay, see if there is a result
		$row = $s->fetch();
		if ($count < 1)
        {
            echo  "<p style='color: red' class='error'>*The pin you entered does not match any account.</p>";
        }
        else
        {
           
            $_SESSION['userid']= $row['employeeid'];
            $_SESSION['uname'] = $row['employeeusername'];
            $_SESSION['usertype'] = $row['employeepositionid'];
            $_SESSION['userlocation'] = $row['locationid'];
            $showform = 0;
            header("Location: index.php");   
        }
}//ifisset

if($showform == 1)
{
	require_once 'header.php';
?>
	 <form name="loginForm" id="loginForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	
		<table id="keypad" cellpadding="5" cellspacing="3">	
			<tr>
				<td onclick="addpwd('1')">1</td>
				<td onclick="addpwd('2')">2</td>
				<td onclick="addpwd('3')">3</td>
			</tr>
			<tr>
				<td onclick="addpwd('4')">4</td>
				<td onclick="addpwd('5')">5</td>
				<td onclick="addpwd('6')">6</td>
			</tr>
			<tr>
				<td onclick="addpwd('7')">7</td>
				<td onclick="addpwd('8')">8</td>
				<td onclick="addpwd('9')">9</td>
			</tr>
			<tr>
				<td onclick="addpwd('*')">*</td>
				<td onclick="addpwd('0')">0</td>
				<td onclick="addpwd('#')">#</td>
			</tr>
		</table>		
		<input type="text" name="pwd" id="pwd" maxlength="4" class="display">
		<p id="message">Attempting to login...</p>
	</form>
<?php
}//showform
include_once 'footer.php';
?>