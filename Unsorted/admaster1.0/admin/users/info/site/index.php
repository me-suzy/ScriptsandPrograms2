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
	global $UserProgramID;
	
	$db = new DB ();
	$db->execute ("select ID from UserSite where UserProgramID = $UserProgramID");

	$buff = '';
	
	for ($i = 0; $i < $db->getNumRows (); $i++)
	{
		$id = $db->recordSet [$i][0];
		global ${"DeleteID$id"};
		
		if (isset (${"DeleteID$id"}))
		{
			$ddb = new DB ();
			$ddb->execute ("delete from UserSite where ID = $id");
		}
	}
	?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u><font color="#000000">Message</font></u><font color="#000000">:
  </font></font></b><font size="3" color="#000000"><font size="2">Selected sites
  have been deleted.</font></font></font></p>	
	<?
}
function deleteConfirm ($ID)
{
	global $UserProgramID;
	
	$db = new DB ();
	$db->execute ("select ID from UserSite where UserProgramID = $UserProgramID");

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
		  </font></font></b><font size="3" color="#333333"><font size="2">Select at least one site to remove.</font></font></font></p>
		<?
		
		return false;
	}
	
	?>
	<p><font class=Dialog>Do you really want to remove these sites?</font></p>
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
		   $Letter,
		   $UserProgramID;

	$db = new DB ();


	$db->execute ("select ID, Title, URL, RefID from UserSite where UserProgramID = $UserProgramID");
	
	if ($db->getNumRows () == 0)
	{
		?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u>Warning</u>:<font color="#333333">
  </font></font></b><font size="3" color="#333333"><font size="2">User has not
  added any site to this program currently.</font></font></font></p>		
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
            sites</td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td class=TableHeader align="left" valign="middle" colspan="4" height="1"></td>
        </tr>
        <tr> 
          <td  class=TableHeader bgcolor="#C0C0C0" align="center" valign="middle"><font color="#FFFFFF">Title</font></td>
          <td  class=TableHeader bgcolor="#C0C0C0" align="center" valign="middle"><font color="#FFFFFF">URL</font></td>
          <td  class=TableHeader bgcolor="#C0C0C0" align="center" valign="middle"><font color="#FFFFFF">Reference ID</font></td>
          <td  class=TableHeader bgcolor="#C0C0C0" align="center" valign="middle"><font color="#FFFFFF">Delete</font></td>
        </tr>
<?
	for ($i = 0; $i < $db->getNumRows (); $i++)
	{
     	$siteID = $db->recordSet [$i][0];
     	$title  = $db->recordSet [$i][1];
     	$url    = $db->recordSet [$i][2];
     	$refID  = $db->recordSet [$i][3];
     	?>
     	<tr> 
          <td  class=TableElement bgcolor="#FFFFFF"><?=$title?></a></td>
          <td  class=TableElement bgcolor="#FFFFFF"><a href=<?=$url?>><?=$url?></a></td>
          <td  class=TableElement bgcolor="#FFFFFF"><?=$refID?></td>
          <td  class=TableElement bgcolor="#FFFFFF" align=center><input type=checkbox name=DeleteID<?=$siteID?> value="<?=$title?>"></td>
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
<title>Admin | Users | User info | User sites</title>
<link rel=stylesheet type=text/css href=../../admin.css>
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
          $hiddenLink1 = "List=on&page=$page&Letter=$Letter&UserName=$UserName&Email=$Email&URL=$URL&RefID=$RefID";
          $hiddenLink2 = "ID=$ID&List=on&page=$page&Letter=$Letter&UserName=$UserName&Email=$Email&URL=$URL&RefID=$RefID";
          
          ?>
          <a href=../../index.php?<?print "UserName=$UserName&Email=$Email&URL=$URL&RefID=$RefID"?>>user search</a>
            </font> | <font class=Mail><a href=../../index.php?<?=$hiddenLink1?>>user list</a></font>
            | <font class=Mail><a href=../index.php?<?=$hiddenLink2?>>user info</a></font> | <font class=Mail>user sites</font></td>
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
				print "\n<input type=hidden name=UserProgramID value=$UserProgramID>\n";
				
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
