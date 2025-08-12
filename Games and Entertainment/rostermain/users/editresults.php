<?

echo 'dfsdf';
if (isset($_POST['lvlform']))
	   {
		   if(strlen($_POST['lvlform'])<1)
		   {
			   $name = 'not set';
		   }
		   else
		   {
		$lvlupdate = "UPDATE characters SET level='$_POST[lvlform]' WHERE charactername='$_GET[select]'"; 
		$lvlresult = mysql_query($lvlupdate, $db_conn) or die("query [$lvlupdate] failed: ".mysql_error());
		if (isset($lvlresult))
		   {
			echo 'Information updated.<br />';
			echo '<br /><a href="index.php?page=profile" />Return to your profile.</a>';
		   }
		   }
	   }
if (isset($_POST['nameform']))
	   {
		   if(strlen($_POST['nameform'])<1)
		   {
			   $name = 'not set';
		   }
		   else
		   {
			   $name = $_POST['nameform'];
		$nameupdate = "UPDATE characters SET charactername='$name' WHERE charactername='$_GET[select]'"; 
		$nameresult = mysql_query($nameupdate, $db_conn) or die("query [$nameupdate] failed: ".mysql_error());
		if (isset($nameresult))
		   {
			echo 'Information updated.<br />';
			echo '<br /><a href="index.php?page=profile" />Return to your profile.</a>';
		   }
		   }
	   }
if (isset($_POST['classform']))
	   {
		   if(strlen($_POST['classform'])<1)
		   {
			   $name = 'not set';
		   }
		   else
		   {
		$classupdate = "UPDATE characters SET charclass='$_POST[classform]' WHERE charactername='$_GET[select]'"; 
		$classresult = mysql_query($classupdate, $db_conn) or die("query [$classupdate] failed: ".mysql_error());
		if (isset($classresult))
		   {
			echo 'Information updated.<br />';
			echo '<br /><a href="index.php?page=profile" />Return to your profile.</a>';
		   }
		   }
	   }
if (isset($_POST['raceform']))
	   {
		   if(strlen($_POST['raceform'])<1)
		   {
			   $name = 'not set';
		   }
		   else
		   {
		$raceupdate = "UPDATE characters SET race='$_POST[raceform]' WHERE charactername='$_GET[select]'"; 
		$raceresult = mysql_query($raceupdate, $db_conn) or die("query [$raceupdate] failed: ".mysql_error());
		if (isset($raceresult))
		   {
			echo 'Information updated.<br />';
			echo '<br /><a href="index.php?page=profile" />Return to your profile.</a>';
		   }
		   }
	   }
?>