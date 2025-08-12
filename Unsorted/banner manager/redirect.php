<?
	if(isset($url)&&isset($bid))
	{
		include('./include/connection.php');
		if($expired != 1)
		{
			$SQL = "UPDATE banner_stat SET clicks = clicks + 1 WHERE banner_id = $bid";
			mysql_query($SQL,$con);
			$result = mysql_query("SELECT url FROM banner WHERE banner.id = $bid");
			$row = mysql_fetch_array($result);
			$url = $row["url"];
		}
		header("Location: $url");
	}
?>