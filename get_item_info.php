<?php

require('database.php');

function get_wowarmory_xml($item_id)
{
	// wow search url
	$wowarmory_url = "http://www.wowarmory.com/item-tooltip.xml?i=" . $item_id;

	// Setup cURL options
	$ch = curl_init($wowarmory_url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION    ,1); 
	curl_setopt($ch, CURLOPT_HEADER            ,0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER    ,1);
	curl_setopt($ch, CURLOPT_USERAGENT    ,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.6pre) Gecko/2009011606 Firefox/3.1');

	// Dump raw data into variable
	$raw_data = curl_exec($ch);

	// Parse XML	
	$xml = simplexml_load_string($raw_data);

	return $xml;
}

function get_item_info($item_id)
{
	// Wowhead XML export URL
	$wowhead_url = "http://www.wowhead.com/item=" . $item_id . "&xml";

	// Get XML results
	$xml = simplexml_load_string(file_get_contents($wowhead_url));

	// Get JSON results
	$json_xml = '{'.$xml->item->jsonEquip.'}'; 
	$json = json_decode($json_xml, true);

	// Put things into variables
	$name = $xml->item->name;
	$item_level = $xml->item->level;
	$quality = $xml->item->quality;
	$vendor_price = $json[sellprice];
	$created_by = $xml->item->createdBy->spell[name];
	$class = $xml->item->class;
	$subclass = $xml->item->subclass;
	
	// Get wowarmory xml data for stack sizes
	$wow_xml = get_wowarmory_xml($item_id);
	$stack_size = $wow_xml->itemTooltips->itemTooltip->stackable;
	
	// Sanitize input
	$name = addslashes($name);
	$created_by = addslashes($created_by);
	
	// MySQL query
	$query = "INSERT IGNORE INTO item_info VALUES ('', '$item_id', '$name', '$item_level', '$quality', '$vendor_price', '$class', '$subclass', '$stack_size')";
	
	// Execute MySQL
	mysql_query($query);
	
	if (isset($created_by))
	{ return $created_by; }
	else
	{ return $name; }
	


}


function get_reagent_info($item_id)
{
	// Wowhead XML export URL
	$wowhead_url = "http://www.wowhead.com/item=" . $item_id . "&xml";

	// Get XML results
	$xml = simplexml_load_string(file_get_contents($wowhead_url));
	
	$item_count = $xml->item->createdBy->spell[minCount];
	
	for($id = 0; $id < 6; $id++)
	{
		// Build dynamic variables
		$reagent = "reagent" . ($id + 1);
		$$reagent = $xml->item->createdBy->spell->reagent[$id][id];
		
		$reagent_count = "reagent" . ($id + 1) . "_count";
		$$reagent_count = $xml->item->createdBy->spell->reagent[$id][count];
		
		// Build a dynamic mysql query
		if (isset($$reagent))
		{ $dyn_query = $dyn_query . ", '" . $$reagent . "'"; }
		else 
		{ $dyn_query = $dyn_query . ", NULL"; }
		
		if (isset($$reagent_count))
		{ $dyn_query = $dyn_query . ", '" . $$reagent_count . "'"; }
		else
		{ $dyn_query = $dyn_query . ", NULL"; }
	}
	
	// MySQL query
	$query = "INSERT IGNORE INTO reagents VALUES ('', '$item_id', '$item_count'" . $dyn_query . ")";

	// Execute MySQL
	mysql_query($query);
}

function insert_professions($item_id, $name, $profession, $type)
{
	// mysql query 
	$query = "INSERT IGNORE INTO professions VALUES ('', '$item_id', '$name', '$profession', '$type')";
	
	// insert records
	mysql_query($query);
}

function insert_scanlist($item_id)
{
	//mysql query
	$query = "INSERT IGNORE INTO scan_list VALUES ('', '$item_id')";
	
	mysql_query($query);
	
	echo "Inserted item_id " . $item_id . " into the scan_list.<br />";
}

function update_scan_list()
{
	
	// Clear item_ids array just in case
	unset($item_ids);
	
	// Build queries
	$queries[0] = "SELECT item_id from professions where item_id is not null";
	$queries[1] = "SELECT item_id from reagents where item_id is not null";
	$queries[2] = "SELECT reagent1 from reagents where reagent1 is not null";
	$queries[3] = "SELECT reagent2 from reagents where reagent2 is not null";
	$queries[4] = "SELECT reagent3 from reagents where reagent3 is not null";
	$queries[5] = "SELECT reagent4 from reagents where reagent4 is not null";
	$queries[6] = "SELECT reagent5 from reagents where reagent5 is not null";
	$queries[7] = "SELECT reagent6 from reagents where reagent6 is not null";
	
	// Go through each query
	foreach ($queries as $query)
	{
		// Get mySQL data
		$results = mysql_query($query);
		
		// Put results into item_ids array
		while ($row = mysql_fetch_array($results))
		{
			$item_ids[] = $row[0];
		}
		
	}
	
	// Insert every item_id into the scan list
	foreach ($item_ids as $item)
	{
		insert_scanlist($item);
	}
	
}

function update_item_info()
{
	// Clear the items_with_info array
	unset($items_with_info);
	unset($all_items);
	
	// Get all items from the item_info table
	$query = "SELECT item_id FROM item_info ORDER BY item_id";
	
	// Process query
	$results = mysql_query($query);
	
	// Put each item_id into an array
	while ($row = mysql_fetch_array($results))
	{
		$items_with_info[] = $row[0];
	}
	
	// Build queries
	$queries[0] = "SELECT item_id from professions where item_id is not null";
	$queries[1] = "SELECT item_id from reagents where item_id is not null";
	$queries[2] = "SELECT reagent1 from reagents where reagent1 is not null";
	$queries[3] = "SELECT reagent2 from reagents where reagent2 is not null";
	$queries[4] = "SELECT reagent3 from reagents where reagent3 is not null";
	$queries[5] = "SELECT reagent4 from reagents where reagent4 is not null";
	$queries[6] = "SELECT reagent5 from reagents where reagent5 is not null";
	$queries[7] = "SELECT reagent6 from reagents where reagent6 is not null";
	
	foreach ($queries as $query)
	{
		// Get the item_ids from the specific query
		$results = mysql_query($query);
		
		// Put results into array
		while ($row = mysql_fetch_array($results))
		{
			$all_items[] = $row[0];
		}
	}
	
	// Compare all items vs items with info and get which item ids are not in items with info
	// array1(red, green, yellow, blue)
	// array2(red, green, blue)
	// array_diff(array1, array2)
	// result = yellow (is not in array2)
	
	$items_without_info = array_diff($all_items, $items_with_info);
	
	foreach ($items_without_info as $item)
	{
		// Get wowhead xml and put into database
		get_item_info($item);
		
		// Say something about processing
		echo "Inserted item_id " . $item . " into the item_info database.<br />";
			
	}
}






// Create database connection

$db = new database();

// Unset things just for safety
unset($items);
unset($type);


if (isset($_GET['scanlist']))
{
	$scan_items = explode(" ", $_GET['scanlist']);
	
	
	
	foreach ($scan_items as $item)
	{
		insert_scanlist($item);
		echo "Inserted " . $item . " into scan_list.<br />";
	}
}

if (isset($_GET['updateinfo']))
{
	update_item_info();
}

if (isset($_GET['iteminfolist']))
{
	$item_infos = explode(" ", $_GET['iteminfolist']);
	
	
	
	foreach ($item_infos as $item)
	{
		get_item_info($item);
		echo "Inserted " . $item . " into item_info.<br />";
	}
}

if (isset($_GET['updatescan']))
{
	// Update the scan list
	update_scan_list();
}

if (isset($_GET['action']))
{
	// mysql query
	$query = "SELECT item_id from sold";
	
	// execute query
	$results = mysql_query($query);
	
	// Reset found records counter
	$records_found = '0';
	
	// Loop through results
	while($row = mysql_fetch_array($results))
	{
		// Update item information
		get_item_info($row['item_id']);
		
		// Increment records found
		$records_found++;
		
		// Add up updated mysql rows
		$updated_rows = $updated_rows + mysql_affected_rows();
	}
	
	echo "Found " . $records_found . " item_id's.<br>";
	echo "Inserted " . $updated_rows . " into the database.<br>";
}

// Get sent information from form and put them into PHP variables
$items = $_POST['items'];
$type = $_POST['type'];
$profession = $_POST['profession'];

// Do we have information to work with?
if (isset($items))
{
	// Put the items into an array
	$items_arr = explode(" ", $items);
	
	foreach ($items_arr as $item_id)
	{
		// Let the browser know we are doing something
		echo "<br>Inserting information for: " . $item_id . "...<br><br>";
		
		// Get the item information via XML and put it into the "ITEM_INFO" mysql table
		// Also, return the 'created_by' field and send that to the insert_professions()
		$name = get_item_info($item_id);
		
		// Get the reagent information via XML and put it into the "REAGENTS" mysql table
		get_reagent_info($item_id);
		
		// Insert information into "PROFESSIONS" mysql table
		insert_professions($item_id, $name, $profession, $type);
		
	}
}




?>
<h2>Insert New Items</h2>

<form action="get_item_info.php" method="post">
	
	<p>Please type out the item IDs you wish to search for. <br />Use space " " as a delimiter.</p>
	
	<table>
		<tr>
			<td>Item IDs</td>
			<td><textarea name="items" rows="6" cols="50"></textarea></td>
		</tr>
		<tr>
			<td>Type: </td>
			<td><input type="text" name="type" /></td>
		<tr>
			<td>Profession: </td>
			<td><select name="profession">
					<option value="Alchemy">Alchemy</option>
					<option value="Blacksmithing">Blacksmithing</option>
					<option value="Enchanting">Enchanting</option>
					<option value="Engineering">Engineering</option>
					<option value="Inscription">Inscription</option>
					<option value="Jewelcrafting">Jewelcrafting</option>
					<option value="Leatherworking">Leatherworking</option>
					<option value="Tailoring">Tailoring</option>
				</select>
			</td>
		</tr>
	</table>
	
	<br />
	
	<td><input type="submit" value="Search &amp; Insert"></td>
	
</form>

<br />

<h2>Get Item Info for Sold Items</h2>

<form action="get_item_info.php" method="get">
	
	<p>This action will update item information from sold items.</p>
	
	<input type="submit" name="action" value="Update">
	
</form>

<h2>Insert into scan list</h2>
<form action="get_item_info.php" method="get">
	<textarea name="scanlist" rows="6" cols="50"></textarea>
	<br />
	<input type="submit" name="dostuff" value="Insert into scan list">
</form>

<br />
<h2>Add item_info and tooltips</h2>

<form action="get_item_info.php" method="get">
	<p>Scan items on WoWHead and add them to the item info table</p>
	<textarea name="iteminfolist" rows="6" cols="50"></textarea>
	<br />
	<input type="submit" name="dostuff" value="Search WoWHead and Update Tooltips">
</form>

<h2>Find items without item_info/tooltips and get item information.</h2>
<form action="get_item_info.php" method="get">
	<input type="submit" name="updateinfo" value="Update Items">
</form>

<h2>Update Scan List</h2>
<p>This will get a list of all the item_ids and insert new ones into the scan_list</p>
<form action="get_item_info.php" metho="get">
	<br />
	<input type="submit" name="updatescan" value="Update Scan List">
</form>