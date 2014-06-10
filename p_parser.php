<?php

// New parser prototype

include('./config.php');

class databaseHandler
{
	// Connection parameters
	private string databaseConnectionHost;
	private string databaseConnectionPort;
	private string databaseConnectionUsername;
	private string databaseConnectionPassword;

	private $databaseConnectionState = false;

	// Database names
	private string databaseName;
	private string databaseTable;

	// Results
	private rawMysqlResults;

	// Constructor
	function __construct(string $givenDatabaseTable)
	{
		// Sets and database table
		$this->databaseTable 				= $givenDatabaseTable;

		// Get configuration parameters from the config.php file
		$this->databaseName 			= $CONFIG_databaseName;
		$this->databaseConnectionUsername	= $CONFIG_databaseUsername;
		$this->databaseConnectionPassword	= $CONFIG_databsaePassword;
		$this->databaseConnectionHost		= $CONFIG_databaseHost;
		$this->databaseConnectionPort		= $CONFIG_databasePort;

		// Create database connection
		$this->createConnection();
	}

	// This function should get connection parameters from a configuration file "config.php"
	private function createConnection()
	{
		// Do we have a current connection?
		if (!$this->databaseConnectionState)
		{
			// Create mysql connection
			$connectionSession = @mysql_connect($this->databaseConnectionHost, $this->databaseConnectionUsername, $this->$databaseConnectionPassword);

			if ($connectionSession)
			{
				// Connect to the database
				$selectedDatabase = @mysql_select_db($this->$databaseName) or die ("Unable to select database");
				
				if ($selectedDatabase)
				{
					$this->databaseConnectionState = true;
					return true;
				}
				else
				{
					return false;
				}
			else
			{
				return false;
			}
		else
		{
			return true;
		}
	}

	private function closeConnection()
	{
		// Close mysql connection
		mysql_close();
	}

	public function selectFromTable(string $givenDatabaseTable, array $givenWhere, string $givenOrderBy)
	{
		// Mysql query
		$rawMysqlQuery = "SELECT * FROM $givenDatabaseTable";

		// Execute query
		/*$results = mysql_query($rawMysqlQuery);

		$this->rawMysqlResults;

		return $this->parseMySqlResults();*/

		return parseMySqlResults(mysql_query($rawMysqlQuery));
	}

	private function parseMySqlResults()
	{
		$iterator = 0;

		while ($mysqlRow = mysql_fetch_array($results))
		{
			$temporaryCollectionContainer[$iterator] = $mysqlRow['asdasd'];
			$iterator++;
		}

		return $temporaryCollectionContainer;
	}

	public function customSQLQuery(string $givenCustomSQLQuery)
	{
		return parseMySqlResults(mysql_query($givenCustomSQLQuery));
	}




}

?>