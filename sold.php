<?php


require('database.php');
require('functions.php');
require('controller.php');

// Timer
$Start = getTime();




?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 

<title>AHPwnage</title>

<script type="text/javascript" src="http://static.wowhead.com/widgets/power.js"></script>
</head>

<body>

<h1>AHpwnage</h1>

<h2>Sold Auctions</h2>


<table cellspacing="15">
	<tr>
		<td><strong>TIME</strong></td>
		<td><strong>ITEM_ID</strong></td>
		<td><strong>QTY</strong></td>
		<td><strong>MONEY</strong></td>
		<td><strong>BUYER</strong></td>
	</tr>

<?php

// Create database connection
$db = new database();

	
// Get the sold auctions
$sold = new controller();
$sold->sold_auctions();
$sold_auctions = $sold->get_sold_items();
	
$num_sold_auctions = count($sold_auctions);


$total_money = '0';

for ($id = 0; $id < $num_sold_auctions; $id++)
{
?>
	<tr>
		<td><?php echo date("n.j - D - g:ia", $sold_auctions[$id]['time']); ?></td>
		<td><?php 
		
		$item_id = $sold_auctions[$id]['item_id']; 
		
		if (isset($sold_auctions[$id]['name']))
		{ $name = $sold_auctions[$id]['name']; }
		else
		{ $name = $item_id; }
		
		echo '<a href="http://www.wowhead.com/item=' . $item_id . '" rel="item=' . $item_id . '">' . $name . '</a>'; ?></td>
		<td><?php echo $sold_auctions[$id]['quantity']; ?></td>
		<td><?php echo format_money($sold_auctions[$id]['money']); ?></td>
		<td><?php echo $sold_auctions[$id]['buyer']; ?>
	</tr>
		
<?php	
$total_money = $total_money + $sold_auctions[$id]['money'];
}
echo '---------------------<br />';
echo format_money($total_money);
echo '<br />---------------------';

$End = getTime(); 
echo "<br>";
echo "Time taken = ".number_format(($End - $Start),2)." secs";

?>




</table>

</body>
</html>