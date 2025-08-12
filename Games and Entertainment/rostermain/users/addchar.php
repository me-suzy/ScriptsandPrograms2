<?
if (isset($_SESSION['valid_user']))
{
$acharname = $_POST['addcharname'];
	$acharclass = $_POST['addcharclass'];
	$acharrace = $_POST['addcharrace'];
	if (!isset($_POST['addcharname']) || !isset($_POST['addcharlvl']) || !isset($_POST['addcharclass']) || !isset($_POST['addcharrace']))
	{
		echo '<div align="center">Please complete all fields before submitting.</div>';
	}
	elseif (strlen($_POST['addcharname'])>30 || strlen($_POST['addcharname'])<1)
	{
	echo '<div align="center">Character names must be between 1 and 30 characters.</div>';
	}
	elseif (strlen($_POST['addcharlvl'])>3 || strlen($_POST['addcharlvl'])<1)
	{
	echo '<div align="center">Character level must be between 1 and 100.</div>';
	}
	elseif (strlen($_POST['addcharclass'])>30 || strlen($_POST['addcharclass'])<1)
	{
	echo '<div align="center">Character class must be between 1 and 30 characters.</div>';
	}
	elseif (strlen($_POST['addcharrace'])>30 || strlen($_POST['addcharrace'])<1)
	{
	echo '<div align="center">Character race must be between 1 and 30 characters.</div>';
	}
	else
	{
		if (strlen($_POST['addcharname'])<1)
		{
			$acharname = 'not set';
		}
		if (strlen($_POST['addcharclass'])<1)
		{
			$acharclass = 'not set';
		}
		if (strlen($_POST['addcharrace'])<1)
		{
			$acharrace = 'not set';
		}
		$addchar = "INSERT INTO characters (username,charactername,level,charclass,race,main) VALUES ('$_SESSION[valid_user]','$acharname','$_POST[addcharlvl]','$acharclass','$acharrace','0')";
		$addcharresult = mysql_query($addchar, $db_conn) or die("query [$addchar] failed: ".mysql_error());
		if (isset($addcharresult))
	{
		echo 'Character added.';
	}
	}
	echo '<form method="post" action="index.php?page=addchar">';
	echo 'Character name:<br />';
	echo '<input type=text name="addcharname" size="30" style="font-size:10px;border:solid 1px;"><br />';
	echo 'Level:<br />';
	echo '<input type=text name="addcharlvl" size="3" style="font-size:10px;border:solid 1px;"><br />';
	echo '<br />';
	echo 'Class:<br />';
	echo '<input type=text name="addcharclass" size="30" style="font-size:10px;border:solid 1px;"><br />';
	echo '<br />';
	echo 'Race:<br />';
	echo '<input type=text name="addcharrace" size="30" style="font-size:10px;border:solid 1px;"><br />';
	echo '<br />';
	echo '<input type="submit" name="add" value="Add character" style="font-size:10px;color:#FFFFFF;background-color:#9A0602;border: 0px;">';
	echo '</form>';
	
}
?>