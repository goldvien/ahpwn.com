<?php

// Load database class
require('database.php');

// Load controller class
require('controller.php');

// Load functions
require('functions.php');

// Load the HTML header
//require('./includes/header.php'); 

// Create database connection
$db = new database();

// Get the sold auctions
$sold = new controller();
$sold->sold_auctions();
$sold_auctions = $sold->get_sold_items();
	
$num_sold_auctions = count($sold_auctions);


$total_money = '0';

?>


<!-- SUMMARY INFORMATION -->

<div id="summary">
	<p>You have sold a total of <span class="number_highlight"><?php echo $num_sold_auctions; ?></span> auctions, earning you a gross profit of <?php echo format_money($sold->get_sold_total_money()); ?></p>
</div>


<!-- LINE 2 -->

<div id="line2"></div>


<!-- TABLES -->

<div id="t_container">
	
	<div id="t_header">
		<div class="row">
			<div class="item">ITEM</div>
			<div class="sold">QUANTITY</div>
			<div class="available">BUYER</div>
			<div class="market_price">TIME</div>
			<div class="profit">MONEY</div>
	</div>
	
	<div id="t_header_underline"></div>

	<div id="t_body">
	
	
	<?php
	
	for ($id = 0; $id < 50; $id++)
	{
	?>

		<div id="<?php echo $id; ?>" class="<?php if (($id + 1) % 2) { echo "show"; } else { echo "show_odd"; }; ?>">
			<div class="item"><?php 

			$item_id = $sold_auctions[$id]['item_id']; 

			if (isset($sold_auctions[$id]['name']))
			{ $name = $sold_auctions[$id]['name']; }
			else
			{ $name = $item_id; }

			echo '<a href="http://www.wowhead.com/item=' . $item_id . '" rel="item=' . $item_id . '">' . $name . '</a>'; ?></div>
			<div class="sold"><?php echo $sold_auctions[$id]['quantity']; ?></div>
			<div class="available"><?php echo $sold_auctions[$id]['buyer']; ?></div>
			<div class="market_price"><?php echo date("D - g:ia", $sold_auctions[$id]['time']); ?></div>
			<div class="profit"><?php echo format_money($sold_auctions[$id]['money']); ?></div>
		</div>
		
		
		
	<?php	
	$total_money = $total_money + $sold_auctions[$id]['money'];
	}
	
	?>
	
		</div><!-- ends t_body -->
	</div><!-- ends t_container -->
	
<?php //require('./includes/footer.php'); ?>

