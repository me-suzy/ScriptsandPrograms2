<?
include_once "sys/Conf.inc";

// functions block
function displaySearchForm ()
{
	global $UserName,
		   $Email,
		   $RefID,
		   $URL;
	?>
<table width="200" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td bgcolor="#C0C0C0" align="left" valign="top">
                    <table width="200" border="0" cellspacing="1" cellpadding="3">
                      <tr align="left" valign="middle"> 
                        <td bgcolor="#C0C0C0" colspan="2" class=TableHeader>Search 
                          user by</td>
                      </tr>
                      <tr> 
                        <td class=TableHeader bgcolor="#FFFFFF">username</td>
                        <td class=TableElement bgcolor="#FFFFFF">
                          <input type="text" name="UserName" size="40" maxlength="100" value="<?if (isset ($UserName)) print $UserName;?>">
                        </td>
                      </tr>
                      <tr> 
                        <td class=TableHeader bgcolor="#FFFFFF">E-mail</td>
                        <td class=TableElement bgcolor="#FFFFFF">
                          <input type="text" name="Email" size="40" maxlength="100" value="<?if (isset ($Email)) print $Email;?>">
                        </td>
                      </tr>
                      <tr> 
                        <td class=TableHeader bgcolor="#FFFFFF">URL</td>
                        <td class=TableElement bgcolor="#FFFFFF">
                          <input type="text" name="URL" size="40" maxlength="100" value="<?if (isset ($URL)) print $URL;?>">
                        </td>
                      </tr>
                      <tr>
                        <td class=TableHeader bgcolor="#FFFFFF" nowrap>reference 
                          id</td>
                        <td class=TableElement bgcolor="#FFFFFF">
                          <input type="text" name="RefID" size="40" maxlength="100" value="<?if (isset ($RefID)) print $RefID;?>">
                        </td>
                      </tr>
                    </table>
                </td>
              </tr>
            </table>
              <p> 
                <input type="submit" name="ListAll" value="Show all" style="font-size: 10pt;">&nbsp;
                <input type="submit" name="List" value="Search" style="font-size: 10pt;">
              </p>
	<?
}
function delete ()
{
	global $ITEMS_PER_PAGE,
		   $page;
		   
	$db = new DB ();
	
	for ($i = ($page - 1)*$ITEMS_PER_PAGE; $i < $page*$ITEMS_PER_PAGE; $i++)
	{
		global ${"DeleteID$i"};
		
		if (isset (${"DeleteID$i"}))
		{
			$db->execute ("delete from User where ID = ".${"DeleteID$i"}); 
			$db->execute ("delete from BankAccount where UserID = ".${"DeleteID$i"}); 
			$db->execute ("delete from UserSite where UserID = ".${"DeleteID$i"}); 
			$db->execute ("delete from MoneyTransfer where UserID = ".${"DeleteID$i"}); 
		}
	}
	?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u><font color="#000000">Message</font></u><font color="#000000">:
  </font></font></b><font size="3" color="#000000"><font size="2">Selected users
  have been deleted.</font></font></font></p>	
	<?
}
function displayDeleteConfirm ()
{
	global $UserName,
		   $Email,
		   $URL,
		   $RefID,
		   $page,
		   $Letter,
		   $ITEMS_PER_PAGE;
		   
	$db = new DB ();
	
	// hide info
	print "\n<input type=hidden name=UserName value=$UserName>\n";
	print "\n<input type=hidden name=Email value=$Email>\n";
	print "\n<input type=hidden name=URL value=$URL>\n";
	print "\n<input type=hidden name=page value=$page>\n";
	
	if (isset ($Letter))
		print "\n<input type=hidden name=Letter value=$Letter>\n";
	
	$buff = '';
	for ($i = ($page - 1)*$ITEMS_PER_PAGE; $i < $page*$ITEMS_PER_PAGE; $i++)
	{
		global ${"DeleteID$i"};
		
		if (isset (${"DeleteID$i"}))
		{
			$db->execute ("select FirstName, Name from User where ID = ".${"DeleteID$i"});
			if ($db->getNumRows () == 0)
				continue;
			$fname = $db->recordSet [0][0];
			$lname = $db->recordSet [0][1];
			$buff .= "<li>$fname&nbsp;$lname\n<input type=hidden name=\"DeleteID$i\" value=\"".${"DeleteID$i"}."\">";
		}
	}
	
	if (strlen ($buff) == 0)
	{
		?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u>Warning</u>:<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">Select at least one user to remove.</font></font></font></p>	
		<?	
		return false;
	}
	
	?>
	<p><font class=Dialog>Do you really want to remove these users?</font></p>
	<ul><?=$buff?></ul><br>
	<input type=button onClick="javascript:history.back()" value="No">&nbsp;<input type=submit name=DeleteConfirm value="Yes">	
	<?
	
	return true;
}
function displayUserList ()
{
	global $List,
		   $ListAll;

	if (isset ($List))
		print "\n<input type=hidden name=List value=on>\n";
	else
		print "\n<input type=hidden name=ListAll value=on>\n";
						
	global $UserName,
		   $Email,
		   $URL,
		   $RefID,
		   $page,
		   $Letter,
		   $ITEMS_PER_PAGE,
		   $ListAll,
		   $OrderBy,
		   $PrevOrderBy,
		   $UpdateStatus;
	
	if (!isset ($page))
		$page = 1;
	
	// order by 

	if (isset ($PrevOrderBy) &&  $PrevOrderBy != $OrderBy)
		$page = 1;
	
	$PrevOrderBy = $OrderBy;
	
	$link = "./index.php?List=on&RefID=$RefID&URL=$URL&PrevOrderBy=$PrevOrderBy&UserName=$UserName&Email=$Email&page=$page";		
	
	if (isset ($OrderBy) && $OrderBy == "RegDate")
	{
		$sOrderBy = " order by User.RegDate";
		$order = "[<font class=Mail><a href=$link&OrderBy=UserName>username</a></font>&nbsp;|&nbsp;".
			 	 "<font class=Mail>registration date</font>&nbsp;&nbsp;]&nbsp;";
		print "\n<input type=hidden name=OrderBy value=RegDate>\n";
	}
	else if (isset ($OrderBy) && $OrderBy == "UserName")
	{
		$sOrderBy = " order by User.UserName";
		$order = "[<font class=Mail>username</font>&nbsp;|&nbsp;".
				 "<font class=Mail><a href=$link&OrderBy=RegDate>registration date</a></font>&nbsp]&nbsp;";	
		print "\n<input type=hidden name=OrderBy value=UserName>\n";
	}
	else 
	{
		$sOrderBy = " order by User.ID";
		$order = "[<font class=Mail><a href=$link&OrderBy=UserName>username</a></font>&nbsp;|&nbsp;".
				 "<font class=Mail><a href=$link&OrderBy=RegDate>registration date</a></font>&nbsp;]&nbsp;";	
	}	
	

	
	$order = "<p><font class=Mail>Order by</font>&nbsp;$order</p>";
	
	// display abc
	$letters = array ('A', 'B', 'C', 'D', 'E', 'F', 'G', 
				      'H', 'I', 'J', 'K', 'L', 'M', 'N', 
				      'O', 'P', 'Q', 'R', 'S', 'T', 'U', 
				      'V', 'W', 'X', 'Y', 'Z');
	
	$abc = '';
	
	for ($i = 0; $i < sizeof ($letters); $i++)
	{
		$link = "./index.php?List=on&RefID=$RefID&URL=$URL&OrderBy=$OrderBy&PrevOrderBy=$PrevOrderBy&UserName=$UserName&Email=$Email&page=$page&Letter=".$letters [$i];
		
		if (isset ($Letter) && $Letter == $letters [$i])
			$abc .= "&nbsp".$letters [$i]."&nbsp;";
		else
			$abc .= "&nbsp<a href=$link>".$letters [$i]."</a>&nbsp;";
	}
	$link = "./index.php?List=on&RefID=$RefID&URL=$URL&OrderBy=$OrderBy&PrevOrderBy=$PrevOrderBy&UserName=$UserName&Email=$Email&page=$page";

	if (isset ($Letter))
		$all = "&nbsp;<a href=$link>All</a>&nbsp;";
	else
		$all = "&nbsp;All&nbsp;";
		
	$abc = "<p><font class=Mail>ABC</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[<font class=Mail>$all&nbsp;</font><font class=Mail>&nbsp;$abc</font>]</p>";
	
	print $abc;
	
	// end display letters
	
	
	$db = new DB ();
	
	$whereClause = '';
	
	if (!isset ($ListAll) && 
		(isset ($URL) && strlen ($URL) > 0) || 
		(isset ($RefID) && strlen ($RefID) > 0))
	{
		if (isset ($URL) && strlen ($URL) > 0)
		{
			$URL = addslashes ($URL);
		
			$whereClause = " where UserSite.URL LIKE \"%$URL%\" ";
			print "<input type=hidden name=URL value=\"$URL\">\n";
		}
		
		if (isset ($RefID) && strlen ($RefID) > 0)
		{
			$RefID = addslashes ($RefID);
		
			if (strlen ($whereClause) == 0)
				$whereClause = " where UserSite.RefID LIKE \"%$RefID%\" ";
			else
				$whereClause .= " and UserSite.RefID LIKE \"%$RefID%\" ";
			print "<input type=hidden name=RefID value=\"$RefID\">\n";
		}
	}
	if (strlen ($whereClause) > 0)
		$whereClause .= ' and UserSite.UserID = User.ID ';
		
	if (!isset ($ListAll) && isset ($UserName) && strlen ($UserName) > 0)
	{
		$UserName = addslashes ($UserName);
		
		if (strlen ($whereClause) == 0)
			$whereClause = " where User.UserName LIKE \"%$UserName%\" ";
		else
			$whereClause .= " and User.UserName LIKE \"%$UserName%\" ";
		print "<input type=hidden name=UserName value=\"$UserName\">\n";
	}
	if (!isset ($ListAll) && isset ($Email) && strlen ($Email) > 0)
	{
		$Email = addslashes ($Email);

		if (strlen ($whereClause) == 0)
			$whereClause = " where User.Email LIKE \"%$Email%\" ";
		else
			$whereClause .= " and User.Email LIKE \"%$Email%\" ";
		print "<input type=hidden name=Email value=\"$Email\">\n";
	}

	
	if (!isset ($ListAll) && ((isset ($URL) && strlen ($URL) > 0) || (isset ($RefID) && strlen ($RefID) > 0)))
		$query =  "select 
				   User.ID, User.UserName, User.Email, User.FirstName, User.Name, User.RegDate, User.Status, User.Password,
				   UserSite.UserID, UserSite.URL, UserSite.RefID
				   from User, UserSite 
				   $whereClause $sOrderBy";
	else
		$query =  "select 
				   User.ID, User.UserName, User.Email, User.FirstName, User.Name, User.RegDate, User.Status, User.Password 
				   from User 
				   $whereClause $sOrderBy";

	$db->execute ($query);

	if ($db->getNumRows () == 0)
	{
		?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u>Warning</u>:<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">No users have 
  been found.</font></font></font></p>	
		<?	
		return;
	}

	$tablePre = "
				<table border=0 cellspacing=0 cellpadding=0>
              	<tr>
                <td bgcolor=#C0C0C0 align=left valign=top>
                <table border=0 cellspacing=1 cellpadding=3>
                <tr> 
                 <td class=TableHeader align=left valign=middle colspan=7 bgcolor=#C0C0C0>User list </td>
                      </tr>
                      <tr bgcolor=#FFFFFF> 
                        <td class=TableHeader align=left valign=middle colspan=7 height=1></td>
                      </tr>
                      <tr> 
                        <td  class=TableHeader bgcolor=#C0C0C0 align=center valign=middle><font color=#FFFFFF>#</font></td>
                        <td  class=TableHeader bgcolor=#C0C0C0 align=center valign=middle><font color=#FFFFFF>First 
                          name, last name</font></td>
                        <td  class=TableHeader bgcolor=#C0C0C0 align=center valign=middle><font color=#FFFFFF>Username</font></td>
                        <td  class=TableHeader bgcolor=#C0C0C0 align=center valign=middle><font color=#FFFFFF>Registration date</font></td>
                        <td  class=TableHeader bgcolor=#C0C0C0 align=center valign=middle><font color=#FFFFFF>Status</font></td>
                        
                        <td  class=TableHeader bgcolor=#C0C0C0 align=center valign=middle><font color=#FFFFFF>Login as</font></td>
                        <td  class=TableHeader bgcolor=#C0C0C0 align=center valign=middle><font color=#FFFFFF>Delete</font></td>
                      </tr>";               
	
	$numOfItems = $db->getNumRows ();

	$numOfPages = ceil ($numOfItems/$ITEMS_PER_PAGE);
	$displayed = '';
	
	for ($i = 0; $i < $numOfItems; $i++)
	{
		$id    = $db->recordSet [$i][0];
		$uName = stripslashes ($db->recordSet [$i][1]);
		$fName = stripslashes ($db->recordSet [$i][3]);
		$lName = stripslashes ($db->recordSet [$i][4]);
		$rDate = stripslashes ($db->recordSet [$i][5]);
		$status = $db->recordSet [$i][6];
		$upswd = stripslashes ($db->recordSet [$i][7]);
		
		if (isset ($Letter) && strlen ($Letter) == 1 && !eregi ("^$Letter", $uName))
			continue;

		$ulink = "./user/index.php?UserName=$uName&UserPswd=$upswd&ActionGroup=Login&Action=Login";

		if ($i >= ($page - 1)*$ITEMS_PER_PAGE && $i < $page*$ITEMS_PER_PAGE)
		{
			global ${"StatusID$id"};

			if (isset ($UpdateStatus) && isset (${"StatusID$id"}))
			{
				$ldb = new DB ();
				$ldb->execute ("update User set Status = '1' where ID = $id");
				$status = 1;
			}
			else if (isset ($UpdateStatus))
			{
				$ldb = new DB ();
				$ldb->execute ("update User set Status = '0' where ID = $id");
				$status = 0;
			}
			
			if ($status == 1)
				$statusBox = "<input type=checkbox name=StatusID$id checked>&nbsp;<img src=./images/on.gif width=5 height=5 border=0>";
			else
				$statusBox = "<input type=checkbox name=StatusID$id>&nbsp;<img src=./images/off.gif width=5 height=5 border=0>";
				
			$displayed .= "<tr>\n<td  class=TableElement bgcolor=#FFFFFF>".($i + 1)."</td>".
               			  "<td  class=TableElement bgcolor=#FFFFFF><a href=./site/index.php?List=on&RefID=$RefID&URL=$URL&OrderBy=$OrderBy&PrevOrderBy=$PrevOrderBy&UserID=$id&UserName=$UserName&Email=$Email&page=$page&ID=$id>$fName&nbsp;$lName</a></td>\n".
               			  "<td  class=TableElement bgcolor=#FFFFFF>$uName</td>".
               			  "<td  class=TableElement bgcolor=#FFFFFF>$rDate</td>".
               			  "<td  class=TableElement bgcolor=#FFFFFF>$statusBox</td>".
               			  "<td  class=TableElement bgcolor=#FFFFFF><a href=$ulink>login</a></td>".
               			  "<td  class=TableElement bgcolor=#FFFFFF><input type=checkbox name=DeleteID$i value=$id></td>\n</tr>\n";            
			
		}
		else if ($i > $page*$ITEMS_PER_PAGE)
		{
			break;
		}
		else
			continue;
	}                      
                      
	$tableSuf = "
                    </table>
                </td>
              </tr>
            </table>
              <p><input type=submit name=Delete value=\"Delete checked\" style=\"font-size: 10pt;\">&nbsp;&nbsp;
              <input type=submit name=UpdateStatus value=\"Update status\" style=\"font-size: 10pt;\"></p>";

	print "\n<input type=hidden name=page value=$page>\n";
		
	if (isset ($Letter))
		print "\n<input type=hidden name=Letter value=$Letter>\n";
		
	if (strlen ($displayed) == 0)
	{
		?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u>Warning</u>:<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">No users have 
  been found.</font></font></font>
  <p><input type=button onClick="javascript:history.back()" value="Back"></p>
  </p>	
		<?	
		return;
	}
	$pages = '';

	if ($numOfItems > $ITEMS_PER_PAGE)
	{	
		// fill bottom locator templates
		for ($i = 1; $i <= $numOfPages; $i++)
		{
			if ($i < 10)
				$locator = "0$i";
			else
				$locator = "$i";
				
			if ($i == $page)
				$pages .= "&nbsp;$locator&nbsp;";
			else
			{
				$link = "./index.php?List=on&RefID=$RefID&URL=$URL&OrderBy=$OrderBy&PrevOrderBy=$PrevOrderBy&UserName=$UserName&Email=$Email&page=$i";
				if (isset ($Letter))
					$link .= "&Letter=$Letter";
					
				$pages .= "&nbsp;<a href=$link>$locator</a>&nbsp;";
			}
			if (floor ($i/20) == $i/20)
				$pages .= "<br>";
		}
		
		if ($page > 1)
		{
			$prevInd = $page - 1;
			$link = "./index.php?List=on&RefID=$RefID&URL=$URL&OrderBy=$OrderBy&PrevOrderBy=$PrevOrderBy&UserName=$UserName&Email=$Email&page=$prevInd";
			if (isset ($Letter))
				$link .= "&Letter=$Letter";
		
			$pages = "&nbsp;<a href=$link>Previous</a>&nbsp;<br>".$pages;
		}
		if ($page < $numOfPages)
		{
			$nextInd = $page + 1;
			$link = "./index.php?List=on&RefID=$RefID&URL=$URL&OrderBy=$OrderBy&PrevOrderBy=$PrevOrderBy&UserName=$UserName&Email=$Email&page=$nextInd";
				if (isset ($Letter))
					$link .= "&Letter=$Letter";
					
			$pages = $pages."<br>&nbsp;<a href=$link>Next</a>&nbsp";
		}
		$pages = "<p><font class=Mail>$pages</font></p>";
	}
	
	print $order.$tablePre.$displayed.$tableSuf.$pages;
}
?>
<html>
<head>
<title>Admin | Users | User info</title>
<link rel=stylesheet type=text/css href=./admin.css>
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
          	if (isset ($ListAll) || isset ($List) || isset ($Delete) || isset ($DeleteConfirm))
          		print "<a href=./index.php?UserName=$UserName&Email=$Email&URL=$URL&RefID=$RefID>user search</a>";
          	else
          		print "user search";
          ?>
            </font> 
          <?
          	if (isset ($ListAll) || isset ($List) || isset ($Delete) || isset ($DeleteConfirm))
          		print "| <font class=Mail>user list </font>";
          ?>
            </td>
        </tr>
        <tr> <form method=GET action=./index.php>
          <td align="left" valign="top" bgcolor="#FFFFFF" width="25">&nbsp; </td>
            <td align="left" valign="top" bgcolor="#FFFFFF" height=100%> 
			<?
			
				if (isset ($DeleteConfirm))
				{
					delete ();
					displayUserList ();	
	
				}
				else if (isset ($Delete))
				{
					if (!displayDeleteConfirm ())
						displayUserList ();
				}
				else if (isset ($List) || isset ($ListAll))
				{
					displayUserList ();
				}
				else
					displaySearchForm ();
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
