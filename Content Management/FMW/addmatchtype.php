<?php

include "header.php";
session_start();
	if (($_SESSION['perm'] < "5"))  {
	echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
		}

	if ($_POST['submit'] == 'submit') {
		$matchtype = $_POST['matchtype'];

		$query="INSERT INTO matchtypes (matchtype, match_cat) 
		VALUES ('$matchtype', '$matchtype') ";
		mysql_query($query); 

		?><meta HTTP-EQUIV="Refresh" CONTENT="0; URL=matchtype.php"><?php

		} 

?>

<HTML>
<HEAD>
<TITLE>Add Cup Type</TITLE>
</HEAD>
<BODY>
<font color="#<?php echo $col_text ?>">
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">

Enter New Cup Name<br>
<input id="matchtype" size="50" name="matchtype"><br>
<br>
<input type="Submit" name="submit" value="submit">
</form>

</BODY> 
</HTML>
