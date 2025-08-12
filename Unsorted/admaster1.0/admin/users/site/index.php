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
function displayUserInfo ()
{
	global $UserID;
	
	$db = new DB ();
	
	$query = "select Company,
			 		 FirstName,
		             Name,
					 Street,
					 Country,
					 ZipCode,
					 Location,
					 WorkPhone,
					 HomePhone,
					 MobilePhone,
			 		 Fax,
			 		 Email,
			 		 BirthDate,
			 		 UserName,
			 		 RegDate
			  from User 
			  where ID = $UserID";
	
	$db->execute ($query);
	
	$Company     = stripslashes ($db->recordSet [0][0]);
	$FirstName   = stripslashes ($db->recordSet [0][1]);
	$Name        = stripslashes ($db->recordSet [0][2]);
	$Street      = stripslashes ($db->recordSet [0][3]);
	$Country     = stripslashes ($db->recordSet [0][4]);
	$ZipCode     = stripslashes ($db->recordSet [0][5]);
	$Location    = stripslashes ($db->recordSet [0][6]);
	$WorkPhone   = stripslashes ($db->recordSet [0][7]);
	$HomePhone   = stripslashes ($db->recordSet [0][8]);
	$MobilePhone = stripslashes ($db->recordSet [0][9]);
	$Fax         = stripslashes ($db->recordSet [0][10]);
	$Email       = stripslashes ($db->recordSet [0][11]);
	$BirthDate   = stripslashes ($db->recordSet [0][12]);
	$UserName    = stripslashes ($db->recordSet [0][13]);
	$RegDate     = stripslashes ($db->recordSet [0][14]);
	
?>
<table width=100% border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td bgcolor="#C0C0C0" align="left" valign="top"> 
<table width=100% cellspacing=1 cellpadding=3 border=0>
  <tbody> 
  <tr> 
    <td class=TableHeader valign=center align=left 
                      bgcolor=#c0c0c0 colspan=2>User details</td>
  </tr>
  <tr bgcolor=#ffffff> 
    <td class=TableHeader valign=center align=left colspan=2 
                      height=1></td>
  </tr>
  <tr> 
    <td class=TableHeader bgcolor=#ffffff>Username</td>
    <td class=TableElement bgcolor=#ffffff><?=$UserName?></td>
  </tr>
   <tr> 
     <td class=TableHeader bgcolor=#ffffff>Company</td>
     <td class=TableElement bgcolor=#ffffff><?=$Company?></td>
  </tr>
    <tr> 
      <td class=TableHeader bgcolor=#ffffff>Firstname</td>
      <td class=TableElement bgcolor=#ffffff><?=$FirstName?></td>
  </tr>
    <tr> 
      <td class=TableHeader bgcolor=#ffffff>Name</td>
      <td class=TableElement bgcolor=#ffffff><?=$Name?></td>
  </tr>
    <tr> 
      <td class=TableHeader bgcolor=#ffffff>ZipCode</td>
      <td class=TableElement bgcolor=#ffffff><?=$ZipCode?></td>
  </tr>
    <tr> 
      <td class=TableHeader bgcolor=#ffffff>Location</td>
      <td class=TableElement bgcolor=#ffffff><?=$Location?></td>
  </tr>
    <tr> 
      <td class=TableHeader bgcolor=#ffffff>WorkPhone</td>
      <td class=TableElement bgcolor=#ffffff><?=$WorkPhone?></td>
  </tr>
    <tr> 
      <td class=TableHeader bgcolor=#ffffff>HomePhone</td>
      <td class=TableElement bgcolor=#ffffff><?=$HomePhone?></td>
  </tr>
    <tr> 
      <td class=TableHeader bgcolor=#ffffff>MobilePhone</td>
      <td class=TableElement bgcolor=#ffffff><?=$MobilePhone?></td>
  </tr>
    <tr> 
      <td class=TableHeader bgcolor=#ffffff>Fax</td>
      <td class=TableElement bgcolor=#ffffff><?=$Fax?></td>
  </tr>
    <tr> 
      <td class=TableHeader bgcolor=#ffffff>Birth date</td>
      <td class=TableElement bgcolor=#ffffff><?=$BirthDate?></td>
  </tr>
    <tr> 
      <td class=TableHeader bgcolor=#ffffff>Registration date</td>
      <td class=TableElement bgcolor=#ffffff><?=$RegDate?></td>
  </tr>
    <tr> 
      <td class=TableHeader bgcolor=#ffffff>E-mail</td>
      <td class=TableElement bgcolor=#ffffff><a href="mailto:<?=$Email?>"><?=$Email?></a></td>
  </tr>
    <tr> 
      <td class=TableHeader bgcolor=#ffffff>Street</td>
      <td class=TableElement bgcolor=#ffffff><?=$Street?></td>
  </tr>
    <tr> 
      <td class=TableHeader bgcolor=#ffffff>Country</td>
      <td class=TableElement bgcolor=#ffffff><?=$Country?></td>
  </tr>
  </tbody> 
</table></td></tr></table>
<?
}
function displayAccountInfo ()
{
	global $UserID;
	
	$db = new DB ();
	$db->execute ("select LastTransferDate, LastTransfer, CurrentAmount from MoneyTransfer where UserID = $UserID");
	
	$lastTransferDate = $db->recordSet [0][0];
	$lastTransfer     = $db->recordSet [0][1];
	$currentAmount    = $db->recordSet [0][2];
?>
<table width=100% border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td bgcolor="#C0C0C0" align="left" valign="top"> 
<table width=100%  cellspacing=1 cellpadding=3 border=0>
  <tbody> 
  <tr> 
    <td class=TableHeader valign=center align=left 
                      bgcolor=#c0c0c0 colspan=3>User account</td>
  </tr>
  <tr bgcolor=#ffffff> 
    <td class=TableHeader valign=center align=left colspan=3 
                      height=1></td>
  </tr>
  <tr> 
    <td class=TableHeader valign=center align=middle 
                      bgcolor=#c0c0c0 nowrap><font color="#ffffff">Last 
      transfer</font></td>
    <td class=TableHeader valign=center align=middle 
                      bgcolor=#c0c0c0 nowrap><font color="#ffffff">Last transfer 
      date</font></td>
    <td class=TableHeader valign=center align=middle 
                      bgcolor=#c0c0c0 nowrap><font color="#ffffff">Current 
      amount</font></td>
  </tr>
  <tr> 
    <td class=TableElement bgcolor=#ffffff><?=$lastTransfer?></td>
    <td class=TableElement bgcolor=#ffffff><?=$lastTransferDate?></td>
    <td class=TableElement 
                        bgcolor=#ffffff width=100%><?=$currentAmount?></td>
  </tr>
  <tr> 
    <td class=TableElement bgcolor=#c0c0c0 colspan="2" height="35" valign=top> 
      <input type="text" name="toTransfer" size="10" maxlength="10" value="<?=$currentAmount?>">
      <input type="submit" name="Transfer" value="Transfer">
    </td>
    <td class=TableElement bgcolor=#c0c0c0 height="35" valign=top> 
      <input type="text" name="Bonus" size="10" maxlength="10" value="0.00">
      <input type="submit" name="Add" value="Add"><br>
      <input type="text" name="Minus" size="10" maxlength="10" value="0.00">
      <input type="submit" name="Subtract" value="Subtract">
      <input type="submit" name="Clear" value="Clear Account">
    </td>
  </tr>
  </tbody> 
</table></td></tr></table>
<?
}
function transferMoney ()
{
	global $toTransfer,
		   $UserID;
	
	if ($toTransfer == 0)
		return;

	$db = new DB ();
	$db->execute ("select CurrentAmount from MoneyTransfer where UserID = $UserID");
	
	$currentAmount = $db->recordSet [0][0];
	
	$remainder = $currentAmount - $toTransfer;
	if ($remainder < 0)
	{
		$remainder = 0.;
		$lastTransfer = $currentAmount;
	}
	else
		$lastTransfer = $toTransfer;

	$db->execute ("update MoneyTransfer set CurrentAmount = $remainder, LastTransfer = $lastTransfer, LastTransferDate = curdate() where UserID = $UserID");
}
function addMoney ()
{
	global $Bonus,
		   $UserID;
	
	$db = new DB ();
	$db->execute ("update MoneyTransfer set CurrentAmount = CurrentAmount + $Bonus where UserID = $UserID");
}
function subtractMoney ()
{
	global $Minus,
		   $UserID;
	
	$db = new DB ();
	$db->execute ("update MoneyTransfer set CurrentAmount = CurrentAmount - $Minus where UserID = $UserID");
}
function clearAccount ()
{
	global $UserID;
	
	$db = new DB ();
	$db->execute ("update MoneyTransfer set CurrentAmount = 0.  where UserID = $UserID");
}
function delete ($ID)
{
	global $UserID;
	
	$db = new DB ();
	$db->execute ("select ID from UserSite where UserID = $UserID");

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
	global $UserID;
	
	$db = new DB ();
	$db->execute ("select ID from UserSite where UserID = $UserID");

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
		   $UserID;

	$db = new DB ();


	$db->execute ("select ID, Title, URL, RefID from UserSite where UserID = $UserID");
	
	if ($db->getNumRows () == 0)
	{
		?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u>Warning</u>:<font color="#333333">
  </font></font></b><font size="3" color="#333333"><font size="2">User has not
  added any site currently.</font></font></font></p>		
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
<link rel=stylesheet type=text/css href=../admin.css>
</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" height=100% border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" bgcolor="#C0C0C0">
      <table width="100%" height=100% border="0" cellspacing="1" cellpadding="5">
        <tr>
          <td bgcolor="#C0C0C0" width="25" height=20>&nbsp;</td>
          <td bgcolor="#FFFFFF" colspan=2><font class=Mail>
          <?
          $hiddenLink1 = "List=on&page=$page&Letter=$Letter&UserName=$UserName&Email=$Email&URL=$URL&RefID=$RefID";
          $hiddenLink2 = "ID=$ID&List=on&page=$page&Letter=$Letter&UserName=$UserName&Email=$Email&URL=$URL&RefID=$RefID";
          
          ?>
          <a href=../index.php?<?print "UserName=$UserName&Email=$Email&URL=$URL&RefID=$RefID"?>>user search</a>
            </font> | <font class=Mail><a href=../index.php?<?=$hiddenLink1?>>user list</a></font>
            | <font class=Mail>user sites</font></td>
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
				print "\n<input type=hidden name=UserID value=$UserID>\n";
				
				if (isset ($Add))
					addMoney ();
				else if (isset ($Subtract))
					subtractMoney ();
				else if (isset ($Clear))
					clearAccount ();
				else if (isset ($Transfer))
					transferMoney ();
					
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
					displayAccountInfo ();
					displayInfo ($ID);
				}
				else if (isset ($Delete))
				{
					if (!deleteConfirm ($ID))
					{
						displayAccountInfo ();
						displayInfo ($ID);
					}
				}
				else
				{
					displayAccountInfo ();
					displayInfo ($ID);
				}
			?>
            </td>
            <td align="left" valign="top" bgcolor="#FFFFFF" width="50%">
            <?
             displayUserInfo ();
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
