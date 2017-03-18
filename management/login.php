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

if(isset ($_POST['submit']))
{

    session_start();

    //CLEANSE DATA THE SAME AS THE REGISTRATION PAGE
    $formfield['myusername'] = strtolower(htmlspecialchars(stripslashes(trim($_POST['uname']))));
    $formfield['mypwd'] = trim($_POST['pwd']);

    //CHECKING FOR EMPTY FIELDS THE SAME AS THE REGISTRATION PAGE
    if (empty($formfield['myusername'])){$errormsg .= "<p style='color: red' class='error'>*Username is missing.</p>";}
    if (empty($formfield['mypwd'])){$errormsg .= "<p style='color: red' class='error'>*Password is missing.</p>";}


    //display error

    if($errormsg !="")
    {
        $errormsg =  "<p class='error'>There are errors:  <br> " . $errormsg . "</p>";
    }
    else
    {
        //GET THE USER AND SALT FROM THE DATABASE
        try
        {
            $sql = 'SELECT employeeusername, employeesalt FROM employee WHERE employeeusername = :theusername';
            $s = $db->prepare($sql);
            $s->bindValue(':theusername', $formfield['myusername']);
            $s->execute();
            $count = $s->rowCount();
        }
        catch (PDOException $e)
        {
            echo 'Error fetching users: ' . $e->getMessage();
            exit();
        }
        //if query okay, see if there is a result
        if ($count < 1)
        {
            $errormsg=  "<p style='color: red' class='error'>*The username you entered does not match any account.</p>";
        }
        else
        {
            $row = $s->fetch();
            $confirmeduname = $row['employeeusername'];
            $confirmedsalt = $row['employeesalt'];
            //rehash password using unique salt from dbase for the user and password from form
            $securepwd = crypt($formfield['mypwd'],$confirmedsalt);
           /*
			echo $confirmedsalt;
            echo "<br />";
			echo $formfield['mypwd'];
			echo "<br />";
			echo $securepwd;
			*/
            try
            {
                $sql2 = 'SELECT * FROM employee
                             WHERE employeeusername = :theusername
                             AND password = :thepwd';
                $s2 = $db->prepare($sql2);
                $s2->bindValue(':theusername', $confirmeduname);
                $s2->bindValue(':thepwd', $securepwd);
                $s2->execute();
                $count2 = $s2->rowCount();
                // echo if necessary echo $count2;
            }
            catch (PDOException $e2)
            {
                echo 'Error fetching users 2: ' . $e2->getMessage();
                exit();
            }

            $row2 = $s2->fetch();
            if ($count2 <1)
            {
                $errormsg= "<p style='color: red' class='error'>*The username and password combination you entered is incorrect.</p>";
            }
            else
            {
                $_SESSION['userid']= $row2['employeeid'];
                $_SESSION['uname'] = $row2['employeeusername'];
                $_SESSION['usertype'] = $row2['employeepositionid'];
                $_SESSION['userlocation'] = $row2['locationid'];
                $showform = 0;
                header("Location: home.php");
            }
        }//username exists
    }//else errormessage
}//ifisset



if($showform == 1)
{

    require_once 'header2.php';
	echo $errormsg;
?>
    <p>You are not logged in.  Please log in to access restricted areas.</p>
<div class="row">
<div class="col-7">	
    <form name="loginForm" id="loginForm" method="post" action="login.php">
	   <table>
            <tr>
                <td>Username:</td>
                <td><input type="text" placeholder="Username" name="uname" id="uname" size="20" required value="<?php if(isset($formfield['uname'])){echo $formfield['uname'];} ?>"/></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input type="password" placeholder="Password" name="pwd" id="pwd" required size="20" /></td>
            </tr>
		</table>
			<div class="button2"><input type="submit" name="submit" value="SUBMIT"></div>
	</form><br><br><br><br><br><br><br><br>
</div>
</div>	
<?php
}//showform
include_once 'footer.php';
?>