<?php

class database {
	
	// Create database connection
	function __construct()
	{
		// Connection parameters
		$username = "wowah";
		$password = "wowah.123";
		$database = "wowah";
		
		// create mysql connection
		mysql_connect(localhost, $username, $password);

		// connect to database
		mysql_select_db($database) or die ("Unable to select database");
		
		
	}
	
	function time_calc()
	{
		$current_time = time();
		$one_hour_ago = time() - (60 * 60);
		
		return array($current_time, $one_hour_ago);
	}
	
	// Insert market price cache data
	
	function insert_market_price_cache($now, $item, $market_price, $available)
	{
		// mysql query
		$query = "INSERT INTO market_price_cache VALUES ('', '$now', '$item', '$market_price', '$available')";
		
		// perform query
		mysql_query($query);
		
		return mysql_affected_rows();
	}
	
	// Get the price of an item 
	function get_prices($item)
	{
		
		// Get the time right now and one hour ago
		list($current_time, $one_hour_ago) = $this->time_calc();
		
		// mysql query
		//$query = "SELECT price FROM auctions WHERE item_id = '$item' ORDER BY price";
		
		$query = "SELECT DISTINCT auction_id, price FROM auctions WHERE item_id = '$item' and price > 0 and scan_time between '$one_hour_ago' and '$current_time' ORDER BY price ASC";

		// query results
		$results = mysql_query($query);
		
		$i = 0;

		// parse through the results
		while($row = mysql_fetch_array($results))
		{
			// what is the price?
			$price[$i] = $row['price'];
			$i++;
		}

		return $price;
	}
	
	
	// Get the price of an item from cache
	
	function get_market_price($item)
	{
		// Get the time right now and one hour ago
		list($current_time, $one_hour_ago) = $this->time_calc();
		
		// mysql query
		$query = "SELECT DISTINCT item_id, market_price FROM market_price_cache WHERE item_id = '$item' and cache_time between '$one_hour_ago' and '$current_time'";
		
		// perform query
		$result = mysql_query($query);
		
		// get market price and put it into a variable
		while ($row = mysql_fetch_array($result))
		{
			$market_price = $row['market_price'];
		}
		
		return $market_price;
	}
	
	function get_market_available($item)
	{
		// Get the time right now and one hour ago
		list($current_time, $one_hour_ago) = $this->time_calc();
		
		// mysql query
		$query = "SELECT DISTINCT item_id, available FROM market_price_cache WHERE item_id = '$item' and cache_time between '$one_hour_ago' and '$current_time'";
		
		// perform query
		$result = mysql_query($query);
		
		// get available and put it into a variable
		while ($row = mysql_fetch_array($result))
		{
			$available = $row['available'];
		}
		
		return $available;
	}
	
	function get_avail($item)
	{
		// Get the time right now and one hour ago
		list($current_time, $one_hour_ago) = $this->time_calc();
		
		// mysql query
		$query = "SELECT DISTINCT auction_id, quantity FROM auctions WHERE item_id = '$item' and scan_time between '$one_hour_ago' and '$current_time'";
		
		$results = mysql_query($query);
		
		$count = 0;
		
		while($row = mysql_fetch_array($results))
		{
			// how many auctions?
			$count = $count + (1 * $row['quantity']);
		}
		
		return $count;
	}
	
	// Get all item_ids out of the database "scan_list" table
	
	function get_scan_items()
	{
		// mysql query
		$query = "SELECT * FROM scan_list";
		
		// perform query
		$results = mysql_query($query);
		
		// put results into an array
		while ($row = mysql_fetch_array($results))
		{
			$scan_items[] = $row['item_id'];
		}
		
		return $scan_items;
		
	}
	
	function get_crafts($craft)
	{
		// mysql query
		$query = "SELECT * FROM reagents WHERE item_id = '$craft'";

		// perform query
		$results = mysql_query($query);
		
		return $results;
	}
	
	function get_profession($profession, $type)
	{
		//mysql query
		$query = "SELECT * FROM professions WHERE profession = '$profession'";
		
		// perform query
		$results = mysql_query($query);
		
		return $results;
	}
	
	function get_profession_types($profession)
	{
		// mysql query
		$query = "SELECT DISTINCT(type) FROM professions WHERE profession = '$profession'";
		
		// perform query
		$results = mysql_query($query);
		
		return $results;
	}
	
	function get_item_info($item_id)
	{
		// mysql query
		$query = "SELECT * FROM item_info WHERE item_id = '$item_id'";
		
		// perform query
		$result = mysql_query($query);
		
		// Gather results into an array
		while ($row = mysql_fetch_array($result))
		{
			$item_info['name'] = $row['name'];
			$item_info['item_level'] = $row['item_level'];
			$item_info['quality'] = $row['quality'];
			$item_info['vendor_price'] = $row['vendor_price'];
			$item_info['class'] = $row['class'];
			$item_info['subclass'] = $row['subclass'];
			$item_info['stack_size'] = $row['stacksize'];
		}
		
		return $item_info;
	}
	
/*	function get_market_available($item_id)
	{
		// mysql query
		$query = "SELECT count(*) FROM auctions WHERE item_id = '$item_id'";
		
		// perform query
		$result = mysql_query($query);
		
		while ($row = mysql_fetch_array($result))
		{
			$available = $row[0];
		}
		
		return $available;
	}*/
	
	function get_sold_count($item_id)
	{
		// mysql query
		$query = "SELECT count(*) FROM sold WHERE item_id = '$item_id'";
		
		// perform query
		$result = mysql_query($query);
		
		while ($row = mysql_fetch_array($result))
		{
			$sold_count = $row[0];
		}
		
		return $sold_count;
	}
		
	function get_sold()
	{
		//mysql query
		$query = "SELECT sold.time, sold.money, sold.item_id, sold.quantity, sold.buyer, item_info.name FROM sold LEFT JOIN item_info on sold.item_id = item_info.item_id ORDER BY time DESC";
		
		// perform query
		$results = mysql_query($query);
		
		// set the array/loop key
		$id = '0';
		
		// parse through the results and stick them into an array
		while($row = mysql_fetch_array($results))
		{
			// assign the data to a multidimensional array
			$sold_auctions[$id]['time'] = $row['time'];
			$sold_auctions[$id]['money'] = $row['money'];
			$sold_auctions[$id]['name'] = $row['name'];
			$sold_auctions[$id]['buyer'] = $row['buyer'];
			$sold_auctions[$id]['item_id'] = $row['item_id'];
			$sold_auctions[$id]['quantity'] = $row['quantity'];
			
			// increment key
			$id++;
		}
		
		return $sold_auctions;
	}
}
?>