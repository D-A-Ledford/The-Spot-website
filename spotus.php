<?php
	session_start();
	include_once "header.php";
	//Database Connection
	require_once "connect.php";
	
	$sqlselect = "SELECT truckoneaddr1 FROM truckonelocation WHERE truckonelocationid = 1";
	$addr = $db->prepare($sqlselect);
	$addr->execute();
	$t1l11 = $addr->fetch();
	
	$sqlselect = "SELECT truckoneaddr1 FROM truckonelocation WHERE truckonelocationid = 2";
	$addr = $db->prepare($sqlselect);
	$addr->execute();
	$t1l21 = $addr->fetch();
	
	$sqlselect = "SELECT truckoneaddr1 FROM truckonelocation WHERE truckonelocationid = 3";
	$addr = $db->prepare($sqlselect);
	$addr->execute();
	$t1l31 = $addr->fetch();
	
	$sqlselect = "SELECT truckoneaddr1 FROM truckonelocation WHERE truckonelocationid = 4";
	$addr = $db->prepare($sqlselect);
	$addr->execute();
	$t1l41 = $addr->fetch();
	
	$sqlselect = "SELECT truckoneaddr1 FROM truckonelocation WHERE truckonelocationid = 5";
	$addr = $db->prepare($sqlselect);
	$addr->execute();
	$t1l51 = $addr->fetch();
	
	$sqlselect = "SELECT truckoneaddr1 FROM truckonelocation WHERE truckonelocationid = 6";
	$addr = $db->prepare($sqlselect);
	$addr->execute();
	$t1l61 = $addr->fetch();
	
	$sqlselect = "SELECT truckoneaddr2 FROM truckonelocation WHERE truckonelocationid = 1";
	$addr = $db->prepare($sqlselect);
	$addr->execute();
	$t1l12 = $addr->fetch();
	
	$sqlselect = "SELECT truckoneaddr2 FROM truckonelocation WHERE truckonelocationid = 2";
	$addr = $db->prepare($sqlselect);
	$addr->execute();
	$t1l22 = $addr->fetch();
	
	$sqlselect = "SELECT truckoneaddr2 FROM truckonelocation WHERE truckonelocationid = 3";
	$addr = $db->prepare($sqlselect);
	$addr->execute();
	$t1l32 = $addr->fetch();
	
	$sqlselect = "SELECT truckoneaddr2 FROM truckonelocation WHERE truckonelocationid = 4";
	$addr = $db->prepare($sqlselect);
	$addr->execute();
	$t1l42 = $addr->fetch();
	
	$sqlselect = "SELECT truckoneaddr2 FROM truckonelocation WHERE truckonelocationid = 5";
	$addr = $db->prepare($sqlselect);
	$addr->execute();
	$t1l52 = $addr->fetch();
	
	$sqlselect = "SELECT truckoneaddr2 FROM truckonelocation WHERE truckonelocationid = 6";
	$addr = $db->prepare($sqlselect);
	$addr->execute();
	$t1l62 = $addr->fetch();
	
	$sqlselect = "SELECT trucktwoaddr1 FROM trucktwolocation WHERE trucktwolocationid = 1";
	$addr = $db->prepare($sqlselect);
	$addr->execute();
	$t2l11 = $addr->fetch();
	
	$sqlselect = "SELECT trucktwoaddr1 FROM trucktwolocation WHERE trucktwolocationid = 2";
	$addr = $db->prepare($sqlselect);
	$addr->execute();
	$t2l21 = $addr->fetch();
	
	$sqlselect = "SELECT trucktwoaddr1 FROM trucktwolocation WHERE trucktwolocationid = 3";
	$addr = $db->prepare($sqlselect);
	$addr->execute();
	$t2l31 = $addr->fetch();
	
	$sqlselect = "SELECT trucktwoaddr1 FROM trucktwolocation WHERE trucktwolocationid = 4";
	$addr = $db->prepare($sqlselect);
	$addr->execute();
	$t2l41 = $addr->fetch();
	
	$sqlselect = "SELECT trucktwoaddr1 FROM trucktwolocation WHERE trucktwolocationid = 5";
	$addr = $db->prepare($sqlselect);
	$addr->execute();
	$t2l51 = $addr->fetch();
	
	$sqlselect = "SELECT trucktwoaddr1 FROM trucktwolocation WHERE trucktwolocationid = 6";
	$addr = $db->prepare($sqlselect);
	$addr->execute();
	$t2l61 = $addr->fetch();
	
	$sqlselect = "SELECT trucktwoaddr2 FROM trucktwolocation WHERE trucktwolocationid = 1";
	$addr = $db->prepare($sqlselect);
	$addr->execute();
	$t2l12 = $addr->fetch();
	
	$sqlselect = "SELECT trucktwoaddr2 FROM trucktwolocation WHERE trucktwolocationid = 2";
	$addr = $db->prepare($sqlselect);
	$addr->execute();
	$t2l22 = $addr->fetch();
	
	$sqlselect = "SELECT trucktwoaddr2 FROM trucktwolocation WHERE trucktwolocationid = 3";
	$addr = $db->prepare($sqlselect);
	$addr->execute();
	$t2l32 = $addr->fetch();
	
	$sqlselect = "SELECT trucktwoaddr2 FROM trucktwolocation WHERE trucktwolocationid = 4";
	$addr = $db->prepare($sqlselect);
	$addr->execute();
	$t2l42 = $addr->fetch();
	
	$sqlselect = "SELECT trucktwoaddr2 FROM trucktwolocation WHERE trucktwolocationid = 5";
	$addr = $db->prepare($sqlselect);
	$addr->execute();
	$t2l52 = $addr->fetch();
	
	$sqlselect = "SELECT trucktwoaddr2 FROM trucktwolocation WHERE trucktwolocationid = 6";
	$addr = $db->prepare($sqlselect);
	$addr->execute();
	$t2l62 = $addr->fetch();
?>
<br>
<body>
 <script type="text/javascript" src="scripts/jquery-ui-1.8.23.custom.min.js"></script>
	<script type="text/javascript" src="scripts/jquery-1.12.3.min.js"></script>
	<script type="text/javascript" src="scripts/jquery.cycle.all.js"></script>
	<script type="text/javascript" src="scripts/html5shiv.js"></script>
<script>
var main = function () {
    $('.titles').click(function (e) {

        e.preventDefault();

        $('.titles').removeClass('current');
        
         $(this).addClass('current');
        
        $('.description').hide();  
        
        var id = $(this).attr('href');
        
        $(id).find('.description').show();
    });
}

$(document).ready(main);
</script>

<div class="row">
           <div class="col-4">
                <ul class="spot">
                    <li><a href="#Monday" class="titles">MONDAY</a>
                    </li>
                    <li><a href="#Tuesday" class="titles">TUESDAY</a>
                    </li>
                    <li><a href="#Wednesday" class="titles">WEDNESDAY</a>
                    </li>
					<li><a href="#Thursday" class="titles">THURSDAY</a>
                    </li>
					<li><a href="#Friday" class="titles">FRIDAY</a>
                    </li>
					<li><a href="#Saturday" class="titles">SATURDAY</a>
                    </li>
					<li><a href="#Events" class="titles">EVENTS</a>
                    </li>
                </ul>
          </div>
    <!-- Main -->
    <div id="col-9" align="center">
			<h2>We are open Monday - Saturday from 11:00am - 6:00pm.<br>Select the day to the left to find the location nearest you.<br>
			</h2>
    <section id="Monday">
			<p class="description">Located at:<br><?php echo $t1l11['truckoneaddr1']; ?>
			<br><?php echo $t1l12['truckoneaddr2']; ?></p>
			<p class="description">Located at:<br><?php echo $t2l11['trucktwoaddr1']; ?>
			<br><?php echo $t2l12['trucktwoaddr2']; ?></p>
    </section>
    
    <section id="Tuesday"> 
			<p class="description">Located at:<br><?php echo $t1l21['truckoneaddr1']; ?>
			<br><?php echo $t1l22['truckoneaddr2']; ?></p>
			<p class="description">Located at:<br><?php echo $t2l21['trucktwoaddr1']; ?>
			<br><?php echo $t2l22['trucktwoaddr2']; ?></p>
    </section>
	
    <section id="Wednesday">        
			<p class="description">Located at:<br><?php echo $t1l31['truckoneaddr1']; ?>
			<br><?php echo $t1l32['truckoneaddr2']; ?></p>
			<p class="description">Located at:<br><?php echo $t2l31['trucktwoaddr1']; ?>
			<br><?php echo $t2l32['trucktwoaddr2']; ?></p>
    </section>
	
	<section id="Thursday">
			<p class="description">Located at:<br><?php echo $t1l41['truckoneaddr1']; ?>
			<br><?php echo $t1l42['truckoneaddr2']; ?></p>
			<p class="description">Located at:<br><?php echo $t2l41['trucktwoaddr1']; ?>
			<br><?php echo $t2l42['trucktwoaddr2']; ?></p>
    </section>
    
    <section id="Friday">
			<p class="description">Located at:<br><?php echo $t1l51['truckoneaddr1']; ?>
			<br><?php echo $t1l52['truckoneaddr2']; ?></p>
			<p class="description">Located at:<br><?php echo $t2l51['trucktwoaddr1']; ?>
			<br><?php echo $t2l52['trucktwoaddr2']; ?></p>
    </section>
	
    <section id="Saturday">    
            <p class="description">Located at:<br><?php echo $t1l61['truckoneaddr1']; ?>
			<br><?php echo $t1l62['truckoneaddr2']; ?></p>
			<p class="description">Located at:<br><?php echo $t2l61['trucktwoaddr1']; ?>
			<br><?php echo $t2l62['trucktwoaddr2']; ?></p>
    </section>
	
	<section id="Events">    
            <p class="description">Visit either location.<br>Mention the code word<br> <br>"The Spot"<br><br> and have a drink on us!</p>
    </section>
    </div>
    </div>
</body>
	<br><br>
	<!--Ends Main-->
	</main>
	</body>
	<?php
include_once 'footer.php';
?>	



</html>