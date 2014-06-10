<?php

require('database.php');

// Create database connection

$db = new database();


// ITEM_INFO

$query = "SELECT item_id from item_info";

$results = mysql_query($query);

while ($row = mysql_fetch_array($results))
{
	$all_items[] = $row['item_id'];
}


// PROFESSIONS

$query = "SELECT item_id from professions";

$results = mysql_query($query);

while ($row = mysql_fetch_array($results))
{
	$all_items[] = $row['item_id'];
}

// PROFESSIONS

$query = "SELECT item_id, reagent1, reagent2, reagent3, reagent4, reagent5, reagent6 from reagents";

$results = mysql_query($query);

while ($row = mysql_fetch_array($results))
{
	$all_items[] = $row['item_id'];
	if (isset($row['reagent1']))
	{ $all_items[] = $row['reagent1']; }
	if (isset($row['reagent2']))
	{ $all_items[] = $row['reagent2']; }
	if (isset($row['reagent3']))
	{ $all_items[] = $row['reagent3']; }
	if (isset($row['reagent4']))
	{ $all_items[] = $row['reagent4']; }
	if (isset($row['reagent5']))
	{ $all_items[] = $row['reagent5']; }
	if (isset($row['reagent6']))
	{ $all_items[] = $row['reagent6']; }
}



foreach ($all_items as $item)
{
	$query = "INSERT IGNORE INTO scan_list VALUES ('', '$item')";
	mysql_query($query);
	
	$updated_rows = $updated_rows + mysql_affected_rows();
}

echo "Found " . count($all_items) . " item_id's.<br>";
echo "Inserted " . $updated_rows . " into the database.";

?>