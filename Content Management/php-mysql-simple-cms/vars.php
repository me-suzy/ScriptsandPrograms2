<?
include "config.php";
//vars.php here are the layout variabels. 
database_connect();
$query = "SELECT *
          FROM config";

$result = mysql_query($query) or die(mysql_error()); 
while ($config = mysql_fetch_object($result)) {
        $title = $config->titel;
		$startpage = $config->startpage;
		$keywords = $config->keywords;
		$description = $config->description;
		$bgcolor = $config->bgcolor;
		$textcolor = $config->textcolor;
		$texttype = $config->texttype;
}

?>