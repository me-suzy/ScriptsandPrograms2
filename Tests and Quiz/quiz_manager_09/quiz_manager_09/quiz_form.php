<?

        include("config.php");
	include("template.inc");
        include("quiz_lib.php");

        $dbh = Connect_Database();
        $query = "select id from quizes where start<=CURDATE() and end>=CURDATE()";
        $dbc = mysql_query($query);
        if($row = @mysql_fetch_array($dbc) ){
		$quiz_id = $row[0];
		print HTML_Code($quiz_id, "");
        }	
	else {
		print "No Quiz found for today!";
	}
?>
