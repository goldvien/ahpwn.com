<?php

function format_money($currency)
{
	if (($currency) == ('0'))
	{
		$formatted = 'N/A';
	}
	
	/*elseif (($currency) == (''))
	{
		$formatted = "N/A";
	}*/
	
	else
	{
	$copper = substr($currency, -2); // count 2 from the end of the string
	$silver = substr($currency, -4, 2); // count 4 from the end of the string but ignore 2
	$gold = substr($currency, 0, -4); // count 0 from the beginning of the string, but ignore the last 4 from the end of the string
		
		// If the price has gold...
		if ((strlen($currency)) > ('4'))
		{
			$formatted = '<span class="number_highlight">' . $gold . '</span><span class="gold">g</span> <span class="number_highlight">' . $silver . '</span><span class="silver">s</span> <span class="number_highlight">' . $copper . '</span><span class="copper">c</span>';
		}
		
		// Or if the price has silver and copper
		elseif ((strlen($currency)) <= ('4'))
		{
			$formatted = '<span class="number_highlight">' . $silver . '</span><span class="silver">s</span> <span class="number_highlight">' . $copper . '</span><span class="copper">c</span>';
		}
		
		// Or if the price has only copper
		elseif ((strlen($currency)) <= ('2'))
		{
			$formatted = '<span class="number_highlight">' . $copper . '</span><span class="copper">c</span>';
		}
	}
	
	return $formatted;
}

function ah_cut($money)
{
	// Figure out the AH %5 cut
	$ah_cut = ($money * 0.05);
	
	// Subtract the AH cut from the sell price
	$after_ah = $money - intval($ah_cut);
	
	return $after_ah;
}

function format_profit($currency)
{
	// Calculate AH cut
	$after_ah = ah_cut($currency);
	
	
	if ((strlen($after_ah)) <= (2))
	{
		// copper conversion
		//$copper = substr($currency, -2);
		
		// Copper price
		$formatted_profit = '<span class="yellow"><span class="bold">' . $currency . '</span>c</span>';
	}
	elseif ((strlen($after_ah)) <= (4))
	{
		// Silver conversion
		$silver = substr($currency, -4, 2);
		
		// Silver price
		$formatted_profit = '<span class="yellow"><span class="bold">0.' . $silver . '</span>g</span>';
	}
	else 
	{
		$profit = substr($after_ah, 0, -4);
	
		if (($profit) > (20))
		{
			$formatted_profit = '<span class="green"><span class="bold">' . $profit . '</span>g</span>';
		}
		elseif (($profit) < (0))
		{
			$formatted_profit = '<span class="red"><span class="bold">' . $profit . '</span>g</span>';
		}
		else
		{
			$formatted_profit = '<span class="yellow"><span class="bold">' . $profit . '</span>g</span>';
		}
	}
	
	return $formatted_profit;
	
}

function wowhead_url($item_id, $name)
{
	$wowhead_url = '<a href="http://theunderminejournal.com/item.php?realm=A-Dalaran&item=' . $item_id . '" rel="item=' . $item_id . '">' . $name . '</a>';
	
	return $wowhead_url;
}


function getTime() 
    { 
    $a = explode (' ',microtime()); 
    return(double) $a[0] + $a[1]; 
    }


?>