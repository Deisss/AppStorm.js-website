<?php
define("DB_FOLDER", "db");
define("DS", DIRECTORY_SEPARATOR);


/*
-------------------------------
  FUNCTION
-------------------------------
*/

/**
 * Return the client's IP
 *
 * @return {String} The client's IP
*/
function ip(){
	$ip = "0.0.0.0";
	if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	if(!filter_var($ip, FILTER_VALIDATE_IP)) {
		return "0.0.0.0";
	} else {
		return $ip;
	}
}



/*
-------------------------------
  CLASS
-------------------------------
*/

/**
 * Database class to manage database system using SQLite
 *
 * The database is flushed every 15 min, for everybody...
*/
class database {
	/**
	 * Create the file and install the root if possible
	 *
	 * @param $name string The name to search in
	 * @return string The new filename
	*/
	private static function createDatabase($name) {
		$path = dirname(__FILE__).DS.DB_FOLDER.DS.$name.".sqlite3";

		if(!file_exists($path)) {
			touch($path);
			chmod($path, 0700);
		}
		return $path;
	}

	/**
	 * Prepare the database table
	 * @param Object $db The database object to use
	*/
	private static function prepareDatabase($db) {
		$seen = <<< SEEN_TABLE
			CREATE TABLE IF NOT EXISTS seen(
				id INTEGER PRIMARY KEY,
				ip VARCHAR(48)
			)
SEEN_TABLE;
		$download = <<< DOWNLOAD_TABLE
			CREATE TABLE IF NOT EXISTS download(
				id INTEGER PRIMARY KEY,
				ip VARCHAR(48),
				request DATETIME,
				version VARCHAR(12),
				target VARCHAR(64)
			)
DOWNLOAD_TABLE;

		// Create table if they are not existing
		$db->exec($seen);
		$db->exec($download);
	}

	/**
	 * Connect to the database and return a PDO Object
	 *
	 * @param $name string The name to search in
	 * @return PDO The new pdo object
	*/
	public static function connect($name){
		$root = self::createDatabase($name);
		
		//Selecting database
		$db = new PDO("sqlite:".$root);
		//Setting attributes
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		self::prepareDatabase($db);
		return $db;
	}
}
?>