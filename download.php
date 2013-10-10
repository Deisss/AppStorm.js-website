<?php
require(dirname(__FILE__).DIRECTORY_SEPARATOR."database.php");

/*
-------------------------------
  VAR
-------------------------------
*/

// Naming it with "." before help to add filter to it
$dbname = ".appstorm";

// Refer to database class to keep them linked in case of modification
$dbfile = dirname(__FILE__).DS.DB_FOLDER.DS.$dbname.".sqlite3";

// Don't forget separator at the end !
$downloadRootFolder = dirname(__FILE__).DS."downloads".DS;


$version = "current";
$target  = "";

if(isset($_GET) && !empty($_GET["version"]) && !empty($_GET["target"])) {
	$version = strtolower(trim($_GET["version"]));
	$target  = strtolower(trim($_GET["target"]));
}


/*
-------------------------------
  FUNCTION
-------------------------------
*/

/**
 * Extract from given folder the existing js (ONLY MINIFIED ONE)
 *
 * @param $dir {String} The root folder to search inside (not recursive)
 * @return {Array} The final tab
*/
function extractJs($dir) {
	$dir = realpath($dir);
	$result = array();

	if(isset($dir) && is_readable($dir) && is_dir($dir)) {
		$fileList = scandir($dir);
		foreach($fileList as $file) {
			if(is_readable($dir.DS.$file) && substr($file, -7) === ".min.js") {
				$result[] = $file;
			}
		}
	}

	return $result;
}

/**
 * get all existing folder into given directory
 *
 * @param $dir {String} The root folder to search inside (not recursive)
 * @return {Array} The final tab
*/
function scanfolder($dir) {
	$dir = realpath($dir);
	$result = array();

	if(isset($dir) && is_readable($dir) && is_dir($dir)) {
		$folderList = scandir($dir);
		foreach($folderList as $folder) {
			$finalFolder = $dir.DS.$folder;
			if($folder != "." && $folder != ".." && is_dir($finalFolder) && is_readable($finalFolder)) {
				
				// We extract zip from that place
				$result[] = array(
					"version" => $folder,
					"content" => extractJs($finalFolder)
				);
			}
		}
	}

	return $result;
}


/**
 * Search inside version list array the good version
 *
 * @param $version {String} The version to search
 * @param $vl {Array} The array content to search inside
 * @return {Array | null} The array or null if nothing has been found
*/
function searchVersion($version, $vl) {
	foreach($vl as $value) {
		if(strtolower(trim($value["version"])) === $version) {
			return $value["content"];
		}
	}
	return null;
}

/**
 * Search target inside content list found
 *
 * @param $target {String} The target to search
 * @param $content {Array} The content to check inside
 * @return {String | null} The target found, or null if nothing has been found
*/
function searchTarget($target, $content) {
	// !!!!!!!!!!!!! See ___/compile/tools/archive/output.py___ before changing anything !!!!!!!!!!!!!
	$tmpTarget = "appstorm-".$target.".min.js";

	foreach($content as $value) {
		if($tmpTarget === $value) {
			return $value;
		}
	}
	return null;
}

/**
 * Perform a file download
 *
 * @param $file {String} The file to download
 * @param $version {String} The version (for download stats)
 * @param $target {String} The target name (for download name)
*/
function download($file, $version, $target) {
	global $dbname;
	$db = database::connect($dbname);

	// Counting download
	$sqlDownload = <<< DOWNLOAD_ENTRY
			INSERT INTO download (id, ip, request, version, target)
					VALUES (NULL, :ip, date('now'), :version, :target)
DOWNLOAD_ENTRY;

	// Binding request
	$newDownload = $db->prepare($sqlDownload);
	$newDownload->bindParam(":ip",         ip(),        PDO::PARAM_STR, 48);
	$newDownload->bindParam(":version",    $version,    PDO::PARAM_STR, 12);
	$newDownload->bindParam(":target",     $target,     PDO::PARAM_STR, 64);

	// Sending request
	$newDownload->execute();

	// Sending file
	header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
	header("Cache-Control: public"); // needed for i.e.
	header("Content-Type: application/javascript");
	header("Content-Transfer-Encoding: Binary");
	header("Content-Length:".filesize($file));
	// Replace by "inline" into Content-disposition for appearing on screen without download
	header("Content-Disposition: attachment; filename=".$target);
	readfile($file);
}

/*
-------------------------------
  CONTROLLER
-------------------------------
*/

// getting full content
$versionList = scanFolder(dirname(__FILE__).DS."downloads");

// Target cannot be empty, in this case we send back the list of content
if($target === "" || $target === null) {
	// Return full array
	print_r(json_encode($versionList));

} else {
	$content = searchVersion($version, $versionList);

	if($content === null) {
		die('{"error":"Version not found"}');
	}

	// Now we search target
	$file = searchTarget($target, $content);
	$fullpath = $downloadRootFolder.$version.DS.$file;

	if($file === null || !file_exists($fullpath)) {
		die('{"error":"Target not found"}');
	}

	// Downloading file
	download($fullpath, $version, $file);
}
?>