<?php
include "header.php";
session_start();
if (($_SESSION['perm'] < "5"))  {
echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}
$fileId = $_GET['fileId'];



$query="SELECT * FROM teams WHERE team_id = '$fileId'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result))
{
$team_name = $row["team_name"];
}

if ($_POST['Delete'] == 'Delete') {



// Delete the team from 'teams'
mysql_query("DELETE FROM teams WHERE team_id = $fileId ")
or die(mysql_error());

?> <meta HTTP-EQUIV="Refresh" CONTENT="0; URL=teamlist.php"><?php

}

?>

<HTML>
<BODY>

<TITLE>Delete Team</TITLE>

<?php echo "<font color='#$col_text'>"; ?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF'];echo "?fileId="; echo "$fileId";?>">


Deleting a team will remove them completely from all league and cup results. <BR>
Do you really want to delete <?php echo "$team_name" ?> ?<BR><BR>

<input type="Submit" name="Delete" value="Delete">


</BODY> 
</HTML>
