<?php 

	#
	#	AHPWN - A World of Warcraft Auction House Web App
	#	Andrew Breja : 2010-2011
	#	
	#	Battle.net AH Armory Parser Module
	#	
	#	This module connects to the specified battle.net server and retrieves
	#	auction house data (gzipped) from the Armory API. The module then parses
	#	through the JSON data and dumps it into the AHPWN auctions database.
	#


function getTime() 
{ 
    $a = explode (' ',microtime()); 
    return(double) $a[0] + $a[1]; 
} 
$Start = getTime(); 

function databaseConnection() 
{

	// mysql credentials
	$username = "wowah";
	$password = "wowah.123";
	$database = "wowah";

	// create mysql connection
	mysql_connect(localhost, $username, $password);

	// connect to database
	@mysql_select_db($database) or die ("Unable to select database");
}

function writeLog($message)
{
	$logFilepath = "./logs/parser.log";

	// open log file, and append to the end of it. if it doesn't exist, create it
	$logFile = fopen($logFilepath, 'a');

	// get current time
	$currentTime = date("[m.d.Y - H:i:s]");

	$messageString = $currentTime . " " . $message . "\n";

	fwrite($logFile, $messageString);

	
}

function countRecursive ($array, $limit)
{
    foreach ($array as $id => $_array)
    {
        if (is_array ($_array) && $limit > 0) $count += countRecursive ($_array, $limit - 1); else $count += 1;
    }
    return $count;
}

function isParserEnabled()
{
	$query = "SELECT value FROM master_control WHERE program = 'parser_enabled'";

	$result = mysql_query($query);

	while ($row = mysql_fetch_array($result))
	{
		$parserIsActive = $row['value']; 
	}

	//return $parserIsActive;

	return 1;
}

databaseConnection();

$parserStatus = isParserEnabled();


if (($parserStatus) == (1))
{
	echo "Parsing data.<br>";

	// initiate log writing
	writeLog("---- SESSION BEGIN ----");

	// connect to the DATABAU5
	writeLog("Established database connection");
	

	// find out what the latest JSON dump is
	/*$query = "SELECT value FROM master_control WHERE program = 'latest_json_dump'";

	$result = mysql_query($query);

	while ($row = mysql_fetch_array($result))
	{
		$latestJSONdump = $row['value'];
	}*/

	$latestJSONdump = '1325095203';

	writeLog ("Latest JSON dump is " . $latestJSONdump);

	// open up JSON file
	//writeLog("Opening up JSON file. Using debug file located in /datastore/auctions.json");


	// location of JSON file
	$filepath = "./datastore/auctions_" . $latestJSONdump . ".json";
	writeLog("Opening up JSON file according to the latest JSON dump: " . $filepath);

	if($fh = fopen($filepath, "r"))
	{ 

		writeLog("Opened up the JSON file successfully");
		writeLog("Parsing through JSON file and adding each line to auctionsArray");

		while (!feof($fh))
		{ 
			$auctionsArray[] = fgets($fh, 1024); 
		} 

		writeLog("Done parsing through the file");

		fclose($fh); 
	} 


	// put the array together into a string for JSON decoding
	$auctionsString = implode(" ", $auctionsArray);

	writeLog("Converted array into string for JSON decoding preparation");
	writeLog("Begin JSON decode of auctionsString");

	// JSON decode the string into array object
	$auctions = json_decode($auctionsString, true);

	writeLog("JSON decoding complete");

	//debug echo "we decoded something.<br>";

	// count elements into the array
	$auctionArraySize = countRecursive($auctions[alliance], 1);

	writeLog("There are " . $auctionArraySize . " elements in the auction array");

	writeLog("Starting to insert all auctions into the database");

	for ($id = 0; $id < $auctionArraySize; $id++)
	{
		
		// dump JSON stuff into local variables for mysql insert
		$auctionID = $auctions[alliance][auctions][$id][auc];
		$itemID = $auctions[alliance][auctions][$id][item];
		$seller = $auctions[alliance][auctions][$id][owner];
		$bid = $auctions[alliance][auctions][$id][bid];
		$buyout = $auctions[alliance][auctions][$id][buyout];
		$quantity = $auctions[alliance][auctions][$id][quantity];
		$timeLeft = $auctions[alliance][auctions][$id][timeLeft];

		// current time
		$now = time();

		// mysql query - note "insert ignore" : if duplicate 'auctionID' found, will ignore insert
		$query = "INSERT IGNORE INTO auctions VALUE ('', '$now', '$auctionID', '$itemID', '$seller', '$quantity', '$bid', '$buyout', '$timeLeft')";

		#debug echo "<br>" . $query;

		// insert record
		//mysql_query($query);

		echo $query . "<br>";

		//$mysqlInserts = $mysqlInserts + mysql_affected_rows();

	}

	writeLog("Added " . $mysqlInserts . " auctions into the database"); 

	// tell master control to disable the parser
	//$query = "UPDATE master_control SET value = '0' WHERE program = 'parser_enabled'";
	//mysql_query($query);

	writeLog("Telling MASTER CONTROL to put the parser program on standby");

	$End = getTime(); 
	echo "<br>";
	$timeTaken = number_format(($End - $Start),2) . " seconds";
	echo "Time taken = " . $timeTaken;

	writeLog("Parser completed in " . $timeTaken);
	writeLog("---- SESSION END ----");

	fclose($logFile);
}
else
{
	echo "Parser is not enabled.";
}

?>
