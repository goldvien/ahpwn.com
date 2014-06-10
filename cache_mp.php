<?php

// AHPWN
// Cache Market Price Data
// Andrew Breja - April 2011


// Load database class
require('database.php');

// Load controller class
require('controller.php');

// Load functions
require('functions.php');

// Create database connection
$db = new database();

// See if we need to make cache data
$query = "SELECT active FROM script_control WHERE script = 'cache'";

$result = mysql_query($query);

while ($row = mysql_fetch_array($result))
{
	$active = $row['active'];
}

if (($active) == (1))
{
	// Create controller instance
	$control = new controller();

	// Get all the items from the scan_list
	$scan_items = $db->get_scan_items();

	// Go through each item in the scan items

	foreach ($scan_items as $item)
	{
		// Get the market price for the current item
		$market_price = $control->market_price($item);
	
		// Get the number of available items
		$available = $db->get_avail($item);
	
		// What is the current time?
		$now = time();
	
		// Insert data into market price cache
		$query_results = $db->insert_market_price_cache($now, $item, $market_price, $available);
	
		if (($query_results) > (0))
		{
			$success = ". Created cache data.";
		}
		elseif (($query_results) <= (0))
		{
			$success = ". Failed to create cache data.";
		}
	
		// Debug mode
		echo "Gathering data for item_id " . $item . $success . "<br />";
	}
	
	// Flag script_control that we're done making delicious apple pie
	$query = "UPDATE script_control SET active = '0' WHERE script = 'cache'";

	mysql_query($query);
	
	
}
else
{
	// No need to create new cache data.
	echo "There is no need to create new cache data.";
}

?>