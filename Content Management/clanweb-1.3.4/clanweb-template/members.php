<h2>Members</h2>
<?php
      require("cfg.php"); // Make sure that the path to cfg.php is correct

    $sql = "SELECT * FROM " .$db_prefix. "members ORDER BY id DESC";
    $query = mysql_query($sql);
		while ($read=mysql_fetch_array($query))
		{
					$id=$read["id"];
					$nick=$read["nickname"];
					$position=$read["position"];

		  echo"<a href=?p=member&amp;id=$id>$nick - $position</a> <br>\n";

		}
		
?>

