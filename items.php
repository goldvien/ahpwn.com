<?php

class items extends controller {
	
	// Variables
	private $craftables; // 2d array
	
	function __construct($profession)
	{
		
		// Create database instance
		$db = new database();
		
		// Get all items for specified profession
		$profession_items = $db->get_profession($profession);
		
		// Array key
		$id = 0;
		
		while ($row = mysql_fetch_array($profession_items))
		{
			// Get the item ID
			$this->craftables[$id]['item_id'] = $row['item_id'];
			// Get the craft name
			$this->craftables[$id]['craft_name'] = $row['name'];
			
			// Find reagents and calculate costs
			$this->find_reagents($row['item_id']);
			$this->calc_cost();
			$this->calc_profit();
			
			// Get how many auctions sold of this item
			$this->craftables[$id]['sold_count'] = $this->sold_count();
			
			// Get how many auctions were seen on the AH in the last hour
			$this->craftables[$id]['available'] = $this->get_market_available($row['item_id']);
			
			// Get the craft item's market price
			$this->craftables[$id]['craft_price'] = $this->craft_price;
			
			// Get the potential profit for crafting and selling this item
			$this->craftables[$id]['profit'] = $this->profit;
			
			// Find out how many reagents there are
			//$how_many_reagents = count($reagents);

			for ($reg_id = 0; $reg_id < 6; $reg_id++)
			{
				// Get the item information
				$item_info = $this->get_item_info($this->mats[$reg_id]);
				
				// Reagent ID
				$this->craftables[$id]['reagent' . $reg_id . '_item_id'] = $this->mats[$reg_id];
				// Reagent count
				$this->craftables[$id]['reagent' . $reg_id . '_count'] = $this->mat_count[$reg_id];
				
				// Get the reagent cost if a reagent exists
				// If there is no reagent cost, look for vendor price
				if (isset($this->mats[$reg_id]))
				{
					if (($this->reagent_cost[$reg_id]) == ('0'))
					{
						// Vendor Price
						$this->craftables[$id]['reagent' . $reg_id . '_cost'] = ($item_info['vendor_price'] * $this->mat_count[$reg_id]);
					}
					else
					{
						// AH Market Price
						$this->craftables[$id]['reagent' . $reg_id . '_cost'] = $this->reagent_cost[$reg_id];
					}
				}
				// Reagent name
				$this->craftables[$id]['reagent' . $reg_id . '_name'] = $item_info['name'];
				
				// How many reagents are on the market
				// If the reagent is bought from the vendor, return an unlimited amount
				if (($this->reagent_cost[$reg_id]) == ('0'))
				{
					$this->craftables[$id]['reagent' . $reg_id . '_available'] = 'Unlimited';
				}
				else 
				{
					$this->craftables[$id]['reagent' . $reg_id . '_available'] = $this->get_available($this->mats[$reg_id]);
				}
				
			} // ends for loop
			
			// Get the total material cost
			$this->craftables[$id]['total_mat_cost'] = $this->mat_cost;
			
			$id++;
			
		} // ends while loop
		
		
	} // ends __construct function
	
	function sort_items()
	{
		$data = $this->craftables;
		
		// Obtain a list of columns
		foreach ($data as $key => $row) 
		{
			$profit[$key] = $row['profit'];
		}
		
		// Sort by profit
		array_multisort($profit, SORT_DESC, $data);
		
		return $data;
	}
	
	function get_craftables()
	{
		$sorted_items = $this->sort_items();
		return $sorted_items;
	}
	
}

?>