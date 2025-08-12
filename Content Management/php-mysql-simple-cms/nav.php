<table width="150">
<?php 
database_connect();
$query = "SELECT * from content
          WHERE status = 1
          ORDER by position;";
$error = mysql_error();
if (!$result = mysql_query($query)) {
    print "$error";
	exit;
	}

while($rij = mysql_fetch_object($result)){
  $title = $rij->title;
  $id = $rij->id;
  print("<tr>
         <td>
		 <a href=\"page.php?id=$id\" target=\"centerframe\"><strong>$title</strong></a>
		 </td>
		 </tr>
        ");
	}
?>
</table>
