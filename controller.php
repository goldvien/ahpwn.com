<?php

class controller {
	
	// Variables
	protected $item;
	protected $item_count;
	protected $mats; //array
	protected $mat_count; //array
	protected $mat_cost;
	protected $craft_price;
	protected $profit;
	protected $reagent_cost; //array
	protected $total_money;

	
	// Find out the market price
	function market_price($item)
	{
		
		// Create database instance
		$db = new database();
		
		// Get the price list
		$prices = $db->get_prices($item);
		
		// Get the numbers of prices
		$count = $db->get_avail($item);
		
		// Reset the truncated distribution range
		$truncated_dist = 0;
		
		// If we have fewer than 10 results, skip the truncating.
		// NOTE: Should at least calculate deviation between prices, and eliminate outer bounds
		if (($count) <= (10))
		{
			$truncated_dist = $count;
		}
		else 
		{
			// Calculate the truncated distribution by 15%
			$truncated_dist = intval(0.15 * $count);
		}
		
		// Reset the sum
		$sum = 0;
		
		// calculate the sum of the prices
		for ($id = 0; $id < $truncated_dist; $id++)
		{
			$sum = $sum + $prices[$id];
		}
		
		// calculate the mean
		$mean = $sum / $truncated_dist;
		
		// convert float to int
		$int_mean = intval($mean);
		
		// return mean to parent method	
		return $int_mean;
				
	}
	
	function get_market_price($item_id)
	{
		// Create database instance
		$db = new database();
		
		$market_price = $db->get_market_price($item_id);
		
		return $market_price;
	}
	
	function get_market_available($item_id)
	{
		$db = new database();
		
		$market_available = $db->get_market_available($item_id);
		
		return $market_available;
	}
	
	// Calculate profit
	function find_reagents($item)
	{

		// Assign to class variables the craft item
		$this->item = $item;
		
		// Create database instance
		$db = new database();

		// Get the reagents for this item
		$craft_results = $db->get_crafts($item);

		// Calculate material costs for various reagents
		while($row = mysql_fetch_array($craft_results))
		{
			
			
			// Parse through up to 6 reagents
			for ($id = 1; $id <= 6; $id++)
			{
				// what is the current reagent?
				$current_reagent = 'reagent' . $id;
				$current_reagent_count = 'reagent' . $id . '_count';

				// change id to array key
				$array_id = $id - 1;

				// set the reagent
				$reagent[$array_id] = $row[$current_reagent];
				$reagent_count[$array_id] = $row[$current_reagent_count];
				
				$this->mats[$array_id] = $reagent[$array_id];
				$this->mat_count[$array_id] = $reagent_count[$array_id];
				
												
			}
			
		// Get the item count	
		$item_count = $row['item_count'];	
		
		$this->item_count = $item_count;
			
		}

	//	return $this->item;
	
	}
	
	
	function calc_cost() 
	{
		
		// Initialize the material cost to 0
		$material_cost = '0';
		
		
		// initialize the db class
		$db = new database();
	
		// loop
		for ($id = 0; $id < 6; $id++)
		{
			
			// Check to see if we have a reagent to scan for
			if (isset($this->mats[$id]))
			{
				$reagent_price = $this->get_market_price($this->mats[$id]);
			
			
				// figure out reagent cost for item
				$reagent_cost = $reagent_price * $this->mat_count[$id];
			
				// save a copy of the reagent price to the class variables
				$this->reagent_cost[$id] = $reagent_cost;
			
				// figure out total material cost
				$material_cost = $material_cost + $reagent_cost;
			}
								
		}

		$this->mat_cost = $material_cost;
			
	}
	
	function sold_count()
	{
		// Create database instance
		$db = new database();
		
		// Get how many items were sold
		$sold_count = $db->get_sold_count($this->item);
				
		return $sold_count;
		
	}
	
	function calc_profit()
	{	
		
		// initialize the db class
		$db = new database();
		
		// get the item price
		$craft_price = $this->get_market_price($this->item);
			
		// how many crafted items
		$this->craft_price = $craft_price * $this->item_count;
		
		// calculate profit
		$this->profit = $this->craft_price - $this->mat_cost;
		
	}
	
	function get_item_info($item_id)
	{
		// Create database instance
		$db = new database();
		
		// Get the item information
		$item_info = $db->get_item_info($item_id);
		
		return $item_info;
	}
	
	function get_available($item_id)
	{
		// Create database instance
		$db = new database();
		
		// Get how many items are on the market in the last hour
		$available_items = $db->get_avail($item_id);
		
		return $available_items;
	}
	
	function sold_auctions()
	{
		
		// Initialize the database class
		$db = new database();
		
		// get the sold items
		$this->sold_auctions = $db->get_sold();
	}
	
	function get_sold_total_money()
	{
		// How many total sold auctions do we have?
		$num_sold_auctions = count($this->sold_auctions);
		
		// Reset variable
		$total_money = 0;
		
		// Let's calculate the total
		for ($id = 0; $id < $num_sold_auctions; $id++)
		{
			$total_money = $total_money + $this->sold_auctions[$id]['money'];
		}
		
		$this->num_sold_auctions = $num_sold_auctions;
		
		$this->total_money = $total_money;
		
		// Return total amount
		return $total_money;
	}
	
	function get_sold_items()
	{
		return $this->sold_auctions;
	}
	
	function get_reagent_cost ()
	{
		return $this->reagent_cost;
	}
	
	function get_reagents()
	{
		return $this->mats;
	}
	
	function get_reagent_count()
	{
		return $this->mat_count;
	}
	
	function get_craft_price()
	{
		return $this->craft_price;
	}
	
	function get_mat_cost()
	{
		return $this->mat_cost;
	}
	
	function get_profit()
	{
		return $this->profit;
	}
	
}
?>