<?php

// Get the profession from the URL
$profession = $_GET['profession'];


// Load database class
require('database.php');

// Load controller class
require('controller.php');

// Load functions
require('functions.php');

// Load the HTML header
require('./includes/header.php'); 

// Load the items class
require('items.php');


// Make new items object
$craftables = new items($profession);

// Get all the craftables for this profession
$items = $craftables->get_craftables();

// Find out how many items there are in the items array
$num_items = count($items);

?>


<!-- SUMMARY INFORMATION -->

<div id="summary">
	<p>Today you sold <span class="number_highlight">34</span> auctions, for a total of <span class="number_highlight">2300</span><span class="gold">g</span> <span class="number_highlight">50</span>s <span class="number_highlight">42</span><span class="copper">c</span><p>
	<p>On average, you make 2400g per day over 12 days.</p>
	<p>Your total sales earned you a gross profit of 13342g 42s 85c.</p>
</div>


<!-- LINE 2 -->

<div id="line2"></div>


<!-- TABLES -->

<div id="t_container">
	
	<div id="t_header">
		<div class="row">
			<div class="item">ITEM</div>
			<div class="sold">SOLD</div>
			<div class="available">AVAILABLE</div>
			<div class="market_price">MARKET PRICE</div>
			<div class="profit">PROFIT</div>
	</div>
	
	<div id="t_header_underline"></div>

	<div id="t_body">
	
	
	<?php
	
	
	// Display contents from the items arrays
	for ($id = 0; $id < $num_items; $id++)
	{
	?>
		<div id="<?php echo $id; ?>" class="<?php if (($id + 1) % 2) { echo "show"; } else { echo "show_odd"; }; ?>">
			<div class="item"><?php echo wowhead_url($items[$id]['item_id'], $items[$id]['craft_name']); ?></div>
			<div class="sold"><?php echo $items[$id]['sold_count']?></div>
			<div class="available"><?php echo $items[$id]['available']?></div>
			<div class="market_price"><?php echo format_money($items[$id]['craft_price']); ?></div>
			<div class="profit"><?php echo format_profit($items[$id]['profit']); ?></div>
		</div>
		
		<div id="hidden<?php echo $id; ?>" class="hidden">
			
			<div class="hidden_line"></div>
		
		<?php
		
		for ($idr = 0; $idr < 6; $idr++)
		{ 
			if (isset($items[$id]['reagent' . $idr . '_item_id']))
			{
			$link = '<a href="http://www.wowhead.com/item=' . $items[$id]['reagent' . $idr . '_item_id'] . '" rel="item=' . $items[$id]['reagent' . $idr . '_item_id'] . '">';

		?>
			
			<div class="item"><?php echo $link . $items[$id]['reagent' . $idr . '_name'] . '</a> (' . $items[$id]['reagent' . $idr . '_count'] . ') '; ?></div>
			<div class="sold"></div>
			<div class="available"><?php echo $items[$id]['reagent' . $idr . '_available']?></div>
			<div class="market_price"><?php echo format_money($items[$id]['reagent' . $idr . '_cost']); ?></div>
			<div class="profit"></div>
			
		<?php	
			}
		}
		?>
		
			<div class="hidden_line"></div>
		
			<div class="item">TOTAL MATERIAL COST</div>
			<div class="sold"></div>
			<div class="available"></div>
			<div class="market_price"><?php echo format_money($items[$id]['total_mat_cost']); ?></div>
			<div class="profit"></div>
			
			<div class="table_spacer">&nbsp;</div>
	
		</div>
		
	<?php
	}
	?>
	
		</div><!-- ends t_body -->
	</div><!-- ends t_container -->
	
<?php require('./includes/footer.php'); ?>

