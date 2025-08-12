<?
include_once "sys/Conf.inc";

// functions block
function isCorrect ($ID)
{
	$db = new DB ();
	$db->execute ("select ID from User where ID = $ID");

	if ($db->getNumRows () == 0)
		return false;

	return true;
}

function delete ($ID)
{
	$db = new DB ();
	$db->execute ("select ID from UserProgram where UserID = $ID");

	$buff = '';
	
	for ($i = 0; $i < $db->getNumRows (); $i++)
	{
		$lid = $db->recordSet [$i][0];
		global ${"DeleteID$id"};
		
		if (isset (${"DeleteID$id"}))
		{
			$ddb = new DB ();
			$ddb->execute ("delete from UserSite where UserProgramID = $lid");
			$ddb->execute ("delete from UserProgram where ID = $lid");
			$ddb->execute ("update UserProgram set ProgramNum = ProgramNum - 1 where ID = $ID");
		}
	}
	?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u><font color="#000000">Message</font></u><font color="#000000">:
  </font></font></b><font size="3" color="#000000"><font size="2">Selected programs
  have been deleted.</font></font></font></p>	
	<?
}
function deleteConfirm ($ID)
{
	$db = new DB ();
	$db->execute ("select ID from UserProgram where UserID = $ID");

	$buff = '';
	
	for ($i = 0; $i < $db->getNumRows (); $i++)
	{
		$id = $db->recordSet [$i][0];
		global ${"DeleteID$id"};
		
		if (isset (${"DeleteID$id"}))
			$buff .= "<li>".${"DeleteID$id"}."<input type=hidden name=DeleteID$id value=\"".${"DeleteID$id"}."\">\n";
	}
	
	if (strlen ($buff) == 0)
	{
		?>
		<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u>Warning</u>:<font color="#333333">
		  </font></font></b><font size="3" color="#333333"><font size="2">Select at least one program to remove.</font></font></font></p>
		<?
		
		return false;
	}
	
	?>
	<p><font class=Dialog>Do you really want to remove these programs?</font></p>
	<ul><?=$buff?></ul><br>
	<input type=button onClick="javascript:history.back()" value="No">&nbsp;<input type=submit name=DeleteConfirm value="Yes">	
	<?
	
	return true;
}
function displayInfo ($ID)
{
	global $UserName,
		   $Email,
		   $URL,
		   $RefID,
		   $page,
		   $Letter;

	$db = new DB ();
	$db->execute ("select FirstName, LastName, UserName, Email from User where ID = $ID");
	
	$fname = $db->recordSet [0][0];
	$lname = $db->recordSet [0][1];
	$uname = $db->recordSet [0][2];
	$email = $db->recordSet [0][3];	
	?>
<table width="200" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td bgcolor="#C0C0C0" align="left" valign="top"> 
      <table width="200" border="0" cellspacing="1" cellpadding="3">
        <tr align="left" valign="middle"> 
          <td bgcolor="#C0C0C0" colspan="2" class=TableHeader>User details</td>
        </tr>
        <tr> 
          <td class=TableHeader bgcolor="#FFFFFF">Name</td>
          <td class=TableElement bgcolor="#FFFFFF"><?=$fname?>&nbsp;<?=$lname?></td>
        </tr>
        <tr> 
          <td class=TableHeader bgcolor="#FFFFFF">Username</td>
          <td class=TableElement bgcolor="#FFFFFF"><?=$uname?></td>
        </tr>
        <tr> 
          <td class=TableHeader bgcolor="#FFFFFF">E-mail</td>
          <td class=TableElement bgcolor="#FFFFFF"><font class=Mail><a href=mailto:<?=$email?>><?=$email?></a></font></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
	<?

	$db->execute ("select ID, ProgramID from UserProgram where UserID = $ID");
	
	if ($db->getNumRows () == 0)
	{
		?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u>Warning</u>:<font color="#333333">
  </font></font></b><font size="3" color="#333333"><font size="2">User is not
  subscribed to any programs currently.</font></font></font></p>		
		<?
		return;
	}
?>
<br>
<table border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td bgcolor="#C0C0C0" align="left" valign="top"> 
      <table border="0" cellspacing="1" cellpadding="3">
        <tr> 
          <td class=TableHeader align="left" valign="middle" colspan="4" bgcolor="#C0C0C0">User 
            programs</td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td class=TableHeader align="left" valign="middle" colspan="4" height="1"></td>
        </tr>
        <tr> 
          <td  class=TableHeader bgcolor="#C0C0C0" align="center" valign="middle"><font color="#FFFFFF">Program</font></td>
          <td  class=TableHeader bgcolor="#C0C0C0" align="center" valign="middle"><font color="#FFFFFF">Description</font></td>
          <td  class=TableHeader bgcolor="#C0C0C0" valign="middle" align="center"><font color="#FFFFFF">Category&nbsp;&nbsp;&nbsp;</font></td>
          <td  class=TableHeader bgcolor="#C0C0C0" align="center" valign="middle"><font color="#FFFFFF">Delete</font></td>
        </tr>
<?
	for ($i = 0; $i < $db->getNumRows (); $i++)
	{
     	$userProgramID = $db->recordSet [$i][0];
     	$programID     = $db->recordSet [$i][1];
     	
     	$programDB = new DB ();
     	$programDB->execute ("select CatID, Name, ShortInfo from Program where ID = ".$programID);

     	$programCatID = $programDB->recordSet [0][0];
     	$programName  = $programDB->recordSet [0][1];
     	$programDesc  = $programDB->recordSet [0][2];
     	
     	$programDB->execute ("select Name from Category where ID = ".$programCatID);
     	$catName = $programDB->recordSet [0][0];
     	
     	$hiddenLink = "./site/index.php?ID=$ID&page=$page&Letter=$Letter&UserName=$UserName&Email=$Email&URL=$URL&RefID=$RefID&UserProgramID=$userProgramID";
     	?>
     	<tr> 
          <td  class=TableElement bgcolor="#FFFFFF"><a href=<?=$hiddenLink?>><?=$programName?></a></td>
          <td  class=TableElement bgcolor="#FFFFFF"><?=$programDesc?></td>
          <td  class=TableElement bgcolor="#FFFFFF"><?=$catName?></td>
          <td  class=TableElement bgcolor="#FFFFFF" align=center><input type=checkbox name=DeleteID<?=$userProgramID?> value="<?=$programName?>"></td>
        </tr>
		<?
	}
?>
      </table>
    </td>
  </tr>
</table>
<p>
  <input type="submit" name="Delete" value="Delete checked" style="font-size: 10pt;">
</p>
<?
}
?>
<html>
<head>
<title>Admin | Users | User info</title>
<link rel=stylesheet type=text/css href=../admin.css>
</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" height=100% border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" bgcolor="#C0C0C0">
      <table width="100%" height=100% border="0" cellspacing="1" cellpadding="5">
        <tr>
          <td bgcolor="#C0C0C0" width="25" height=20>&nbsp;</td>
          <td bgcolor="#FFFFFF"><font class=Mail>
          <?
          $hiddenLink = "List=on&page=$page&Letter=$Letter&UserName=$UserName&Email=$Email&URL=$URL&RefID=$RefID";
          ?>
          <a href=../index.php?<?print "UserName=$UserName&Email=$Email&URL=$URL&RefID=$RefID"?>>user search</a>
            </font> | <font class=Mail><a href=../index.php?<?=$hiddenLink?>>user list</a></font>
            | <font class=Mail>user info</font></td>
        </tr>
        <tr> <form method=GET action=./index.php>
          <td align="left" valign="top" bgcolor="#FFFFFF" width="25">&nbsp; </td>
            <td align="left" valign="top" bgcolor="#FFFFFF" height=100%> 
			<?
				print "\n<input type=hidden name=ID value=$ID>\n";
				print "\n<input type=hidden name=UserName value=$UserName>\n";
				print "\n<input type=hidden name=Email value=$Email>\n";
				print "\n<input type=hidden name=URL value=$URL>\n";
				print "\n<input type=hidden name=page value=$page>\n";
				print "\n<input type=hidden name=Letter value=$Letter>\n";
				if (!isCorrect ($ID))
				{
					?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u>Error</u>:
  </font></b><font size="3"><font size="2">Check your params or try to return
  to user list.</font></font></font></p>
					<?
				}
				else if (isset ($DeleteConfirm))
				{
					delete ($ID);
					displayInfo ($ID);
				}
				else if (isset ($Delete))
				{
					if (!deleteConfirm ($ID))
						displayInfo ($ID);
				}
				else
					displayInfo ($ID);
			?>
            </td>
          </form>
        </tr>
        <tr>
          <td bgcolor="#C0C0C0" colspan="2" height=20><font class=Mail>Comments to: <a href=mailto:locihome@yahoo.com>locihome@yahoo.com</a></font></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
