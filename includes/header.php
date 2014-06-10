<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 

<link rel="stylesheet" type="text/css" href="./css/style.css"  /> 

<link rel="shortcut icon" type="image/x-icon" href="./favicon.ico" />

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script type="text/javascript" src="http://static.wowhead.com/widgets/power.js"></script>
<script type="text/javascript" src="./js/jToggle.js"></script>

<title>AHPWN</title>
</head>
<body>

<div class="wrap">
	
	
	<!-- HEADER, LOGO AND NAVIGATION -->
	
	<div id="header">
		<div id="logo">
			<a href="./index.php" /><img src="./images/logo.png" /></a>
		</div>
		
		<?php if(($profession) == ('Alchemy')) { ?> 
		<div class="nav_active_container">
			<div class="nav_active"><a href="./profs.php?profession=Alchemy"><img src="./images/alch.png" /></a></div>	
		</div>
		<?php } else { ?>
		<div class="nav">
			<div class="nav_link"><a href="./profs.php?profession=Alchemy"><img src="./images/alch.png" /></a></div>
		</div>	
		<?php } ?>
		
		<?php if(($profession) == ('Blacksmithing')) { ?> 
		<div class="nav_active_container">
			<div class="nav_active"><a href="./profs.php?profession=Blacksmithing"><img src="./images/bs.png" /></a></div>	
		</div>
		<?php } else { ?>
		<div class="nav">
			<div class="nav_link"><a href="./profs.php?profession=Blacksmithing"><img src="./images/bs.png" /></a></div>
		</div>	
		<?php } ?>
		
		<?php if(($profession) == ('Enchanting')) { ?> 
		<div class="nav_active_container">
			<div class="nav_active"><a href="./profs.php?profession=Enchanting"><img src="./images/ench.png" /></a></div>	
		</div>
		<?php } else { ?>
		<div class="nav">
			<div class="nav_link"><a href="./profs.php?profession=Enchanting"><img src="./images/ench.png" /></a></div>
		</div>	
		<?php } ?>
		
		<?php if(($profession) == ('Engineering')) { ?> 
		<div class="nav_active_container">
			<div class="nav_active"><a href="./profs.php?profession=Engineering"><img src="./images/eng.png" /></a></div>	
		</div>
		<?php } else { ?>
		<div class="nav">
			<div class="nav_link"><a href="./profs.php?profession=Engineering"><img src="./images/eng.png" /></a></div>
		</div>	
		<?php } ?>
		
		<?php if(($profession) == ('Inscription')) { ?> 
		<div class="nav_active_container">
			<div class="nav_active"><a href="./profs.php?profession=Inscription"><img src="./images/insc.png" /></a></div>	
		</div>
		<?php } else { ?>
		<div class="nav">
			<div class="nav_link"><a href="./profs.php?profession=Inscription"><img src="./images/insc.png" /></a></div>
		</div>	
		<?php } ?>
		
		<?php if(($profession) == ('Jewelcrafting')) { ?> 
		<div class="nav_active_container">
			<div class="nav_active"><a href="./profs.php?profession=Jewelcrafting"><img src="./images/jc.png" /></a></div>	
		</div>
		<?php } else { ?>
		<div class="nav">
			<div class="nav_link"><a href="./profs.php?profession=Jewelcrafting"><img src="./images/jc.png" /></a></div>
		</div>	
		<?php } ?>
		
		<?php if(($profession) == ('Leatherworking')) { ?> 
		<div class="nav_active_container">
			<div class="nav_active"><a href="./profs.php?profession=Leatherworking"><img src="./images/lw.png" /></a></div>	
		</div>
		<?php } else { ?>
		<div class="nav">
			<div class="nav_link"><a href="./profs.php?profession=Leatherworking"><img src="./images/lw.png" /></a></div>
		</div>	
		<?php } ?>
		
		<?php if(($profession) == ('Tailoring')) { ?> 
		<div class="nav_active_container">
			<div class="nav_active"><a href="./profs.php?profession=Tailoring"><img src="./images/tail.png" /></a></div>	
		</div>
		<?php } else { ?>
		<div class="nav">
			<div class="nav_link"><a href="./profs.php?profession=Tailoring"><img src="./images/tail.png" /></a></div>
		</div>	
		<?php } ?>
				
	</div>
	
	
	
	
	<!-- LINE 1 -->
	
	<div id="line1"></div>
