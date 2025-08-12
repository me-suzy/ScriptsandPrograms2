<?
### headlines.php - added [v1.12] 

// Automatically get $tpath to avoid possible security holes
$tpath = realpath(__FILE__);
$tpath = substr($tpath,0,strrpos($tpath,DIRECTORY_SEPARATOR)+1);
// Check if the file exists on local server and include it
if(file_exists($tpath . "cn_config.php")) {
	require_once($tpath . "cn_config.php");
} else {
	die("Could not include required configuration file");
}

// Check if a connection to the database was established
if(!isset($link)) {
	die("Please make sure the \"\$tpath\" veriable is the root path to where 'headlines.php' is on your server.");
}

// Page URL to link the news items 
if(!isset($page)) { $page = "/index.php"; }
// Set limit for number of items displayed 
if(!isset($lim)) { $lim = "5"; }
// Number of characters to cut news titles at 
if(!isset($charnum)) { $charnum = "18"; }

// Get news items from database, and order them from newest to oldest 
if($c != "") { $t_news .= " WHERE cat = '$c'"; }
$q[info] = mysql_query("SELECT * FROM $t_news ORDER BY date DESC LIMIT 0, $lim", $link);

while($h = mysql_fetch_array($q[info], MYSQL_ASSOC)) {
	// Edit the HTML code below for the output of your headlines 
	// Edit the HTML code between the dashed lines
	// ------------------------------------------------------------------
	?>
	&nbsp;&#187; <a href="<? echo $page; ?>?a=<? echo $h[id]; ?>" title="<? echo $h[subject]; ?>"><? echo cn_cutstr($h[subject],"$charnum"); ?></a><br />
	<?
	// ------------------------------------------------------------------
}
?>