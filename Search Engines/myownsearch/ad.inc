<?

function textad($search){
	global $adstable,$newwin;
	$search = str_replace("%20", " ", $search);
	$search = str_replace("-", " ", $search);
	$search = str_replace("_", " ", $search);
	$search = str_replace("\"", "", $search);
	$search = str_replace("'", "", $search);
	$search = str_replace("!", "", $search);
	$search = str_replace("?", "", $search);
	$search = str_replace("@", "", $search);
	$search = str_replace("#", "", $search);
	$search = str_replace("$", "", $search);
	$search = str_replace("%", "", $search);
	$search = str_replace("^", "", $search);
	$search = str_replace("&", "", $search);
	$search = str_replace("*", "", $search);
	$search = str_replace("(", "", $search);
	$search = str_replace(")", "", $search);
	$search = str_replace(",", "", $search);
	$search = str_replace(".", "", $search);
	$search = str_replace("<", "", $search);
	$search = str_replace(">", "", $search);
	$search = str_replace("/", "", $search);
	$search = str_replace("\\", "", $search);
	$search = str_replace("{", "", $search);
	$search = str_replace("}", "", $search);
	$search = str_replace("[", "", $search);
	$search = str_replace("]", "", $search);

	$searcharray = split(" ", $search);
	$cnt = count($search);
	for ($x=0;$x<$cnt;$x++){
		$wrd = $searcharray[$x];
		$wrd = strtolower($wrd);
		if (!$adtext) {
			$sql = "select id,ad,impressions from $adstable where keyword = '$wrd'";
			$result = mysql_query($sql) or die("Failed: $sql");
			$numrows = mysql_num_rows($result);
			if ($numrows!=0){
				$resrow = mysql_fetch_row($result);
				$id = $resrow[0];
				$ad = $resrow[1];
				$impressions = $resrow[2];
				$impressions++;
				$sql = "update $adstable set impressions='$impressions' where id='$id'";
				$result = mysql_query($sql) or die("Failed: $sql");
				if ($newwin=="1") $targetwin = " target='_$url'";
				$adtext = "<blockquote><b><a href='out.php?adid=$id'$targetwin>$ad</a></b></blockquote><br>";
			}
		}
	}
return $adtext;
}
?>