<?php

// AHPWN
// File write module
// Andrew Breja - 2011



// DATASET -- Example array
/*
$craft_queue[0]['id'] = "45736";
$craft_queue[0]['name'] = "Glyph of Deep Freezze";
$craft_queue[0]['count'] = "1";

$craft_queue[1]['id'] = "45740";
$craft_queue[1]['name'] = "Glyph of Awesome Sauce";
$craft_queue[1]['count'] = "3";

*/

function file_write($craft_queue)
{

	// Make filename
	$filename = "./datastore/AdvancedTradeSkillWindow.lua";

	// Open the file for writing
	$file = fopen($filename, 'w');

	// Write file header
	fwrite($file, "atsw_savedqueue = {\n");
	fwrite($file, "	[\"Marmaladé\"] = {\n");
	fwrite($file, "		[\"Inscription\"] = {\n");


	// Write queue contents
	for ($id = 0; $id < count($craft_queue); $id++)
	{
		// Section open
		fwrite($file, "			{\n");
	
		// How many to create
		fwrite($file, "				[\"count\"] = " . $craft_queue[$id]['count'] . ",\n");
	
		// Item name
		fwrite($file, "				[\"name\"] = \"" . $craft_queue[$id]['name'] . "\",\n");
	
		// Item Link/metadata
		fwrite($file, "				[\"link\"] = \"|cffffffff|Hitem:" . $craft_queue[$id]['id'] . ":0:0:0:0:0:0:0:82:0|h[" . $craft_queue[$id]['name'] . "]|h|r\",\n");
	
		// Section close
		fwrite($file, "			}, -- [" . ($id + 1) . "]\n");
	}


	// Write file footer
	fwrite($file, "		},\n");
	fwrite($file, "	},\n");
	fwrite($file, "}\n");

	// Close the file
	fclose($file);

	echo "Created file.";

	// Tell script_control we are ready to copy the file to NUTELLA

	$query = "UPDATE script_control SET active = '1' WHERE script = 'rsync'";

	mysql_query($query);
}


?>