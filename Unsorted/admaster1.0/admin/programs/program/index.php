<?
include_once "sys/Conf.inc";

// functions block
function displayItemEd ()
{
	global $Name,
		   $ShortInfo,
		   $Info,
		   $Edit,
		   $Update,
		   $ID,
		   $CatID;

	$db = new DB ();
	$db->execute ("select Name, ShortInfo, Info from Program where ID = $ID");
	
	print "\n<input type=hidden name=ID value=$ID>\n";
	
	if (isset ($Edit))
	{
		$Name      = stripslashes ($db->recordSet [0][0]);
		$ShortInfo = stripslashes ($db->recordSet [0][1]);
		$Info      = stripslashes ($db->recordSet [0][2]);
	}
	
	$errorStatus = 0;
	if (isset ($Update) && isset ($Name) && strlen ($Name) == 0)
	{
		$ShortInfo = stripslashes ($ShortInfo);
		$Info      = stripslashes ($Info);
	?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3">-<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">Put a name of the program.</font></font></font></p>	
	<?	
		$errorStatus = 1;
	}
	if (isset ($Update) && isset ($ShortInfo) && strlen ($ShortInfo) == 0)
	{
		$Name = stripslashes ($Name);
		$Info = stripslashes ($Info);
	?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3">-<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">Put a short info of the program.</font></font></font></p>	
	<?	
		$errorStatus = 1;
	}

	if (isset ($Update) && isset ($Info) && strlen ($Info) == 0)
	{
		$ShortInfo = stripslashes ($ShortInfo);
		$Name      = stripslashes ($Name);
	?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3">-<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">Put a info of the program.</font></font></font></p>	
	<?	
		$errorStatus = 1;
	}
	
	if (isset ($Update) && isset ($Name) && isset ($ShortInfo) && isset ($Info) && $errorStatus == 0)
	{
		$Name 	   = addslashes ($Name);
		$ShortInfo = addslashes ($ShortInfo);
		$Info      = addslashes ($Info);
		
		if ($db->execute ("update Program set Name = '$Name' , ShortInfo = '$ShortInfo', Info = '$Info' where ID = $ID"))
		{
			?>
			<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u><font color="#000000">Message</font></u><font color="#000000">:
  </font></font></b><font size="3" color="#000000"><font size="2">Program has been updated.</font></font></font></p>	
  <p><font class=Mail><a href=./index.php?CatID=<?print $CatID;?>>Click here to refresh data</a></font></p>	
  <?			
  			$Name = '';
			unset ($Name);
			$ShortInfo = '';
			unset ($ShortInfo);
			$Info = '';
			unset ($Info);
			$Update = '';
			unset ($Update);
			
			return;
		}
		else
		{
	?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u>Warning</u>:<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">Program has not been updated.</font></font></font></p>	
<?			
		}
	}
?>
	
	<table border=0 cellspacing=0 cellpadding=0>
       	<tr>
          <td bgcolor=#C0C0C0 align=left valign=top>
          <table border=0 cellspacing=1 cellpadding=3>
          <tr> 
           <td class=TableHeader align=left valign=middle colspan=2 bgcolor=#C0C0C0>Program</td>
                </tr>
            
 	<td  class=TableHeader bgcolor=#FFFFFF align=left valign=middle><font color=#000000>Name</font></td>
            <td  class=TableHeader bgcolor=#FFFFFF align=left valign=top>
            <input type=text name=Name size=48 maxlength=100 value="<?if (isset ($Name)) print htmlentities ($Name);?>">
            </td><tr>
            <td  class=TableHeader bgcolor=#FFFFFF align=left valign=top><font color=#000000>Short info</font></td>
    		<td  class=TableHeader bgcolor=#FFFFFF align=left valign=top>
    		<textarea rows=4 cols=40 name=ShortInfo><?if (isset ($ShortInfo)) print htmlentities ($ShortInfo);?></textarea>
    		</td><tr>
    		<td  class=TableHeader bgcolor=#FFFFFF align=left valign=top><font color=#000000>Detailed info</font></td>
			    		<td  class=TableHeader bgcolor=#FFFFFF align=left valign=top>
			    		<textarea rows=25 cols=40 name=Info><?if (isset ($Info)) print htmlentities ($Info);?></textarea>
    		</td>
    		
    </table></td></table><p><input type=submit name=Update value="Update" style="font-size: 10pt;"></p> 
<?	
}
function displayItemAdd ()
{
	global $Name,
		   $ShortInfo,
		   $Info,
		   $CatID,
		   $Update;

	$errorStatus = 0;
	if (!isset ($Update) && isset ($Name) && strlen ($Name) == 0)
	{
		$ShortInfo = stripslashes ($ShortInfo);
		$Info      = stripslashes ($Info);
	?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3">-<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">Put a name of new program.</font></font></font></p>	
	<?
		$errorStatus = 1;
	}
	if (!isset ($Update) && isset ($ShortInfo) && strlen ($ShortInfo) == 0)
	{
		$Name = stripslashes ($Name);
		$Info = stripslashes ($Info);
	?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3">-<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">Put a short info of new program.</font></font></font></p>	
	<?	
		$errorStatus = 1;
	}
	if (!isset ($Update) && isset ($Info) && strlen ($Info) == 0)
	{
		$ShortInfo = stripslashes ($ShortInfo);
		$Name      = stripslashes ($Name);
	?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3">-<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">Put a detailed info of new program.</font></font></font></p>	
	<?	
		$errorStatus = 1;
	}
	
	if (isset ($Name) && isset ($ShortInfo) && isset ($Info) && $errorStatus == 0)
	{
		
		$db        = new DB ();
		$Name      = addslashes ($Name);
		$ShortInfo = addslashes ($ShortInfo);
		$Info      = addslashes ($Info);
		
		if ($db->execute ("insert into Program (CatID, Name, ShortInfo, Info) values ($CatID, '$Name', '$ShortInfo', '$Info')"))
		{
			?>
			<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u><font color="#000000">Message</font></u><font color="#000000">:
  </font></font></b><font size="3" color="#000000"><font size="2">New program has been added.</font></font></font></p>
  <p><font class=Mail><a href=./index.php?CatID=<?print $CatID;?>>Click here to refresh data</a></font></p>	
  <?			
			unset ($Name);
			unset ($ShortInfo);
			unset ($Info);
		}
		else
		{
	?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u>Warning</u>:<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">New program has not been added.</font></font></font></p>	
<?			
		}
	}
?>
	
	<table border=0 cellspacing=0 cellpadding=0>
       	<tr>
          <td bgcolor=#C0C0C0 align=left valign=top>
          <table border=0 cellspacing=1 cellpadding=3>
          <tr> 
           <td class=TableHeader align=left valign=middle colspan=2 bgcolor=#C0C0C0>New program</td>
                </tr>
            
 	<td  class=TableHeader bgcolor=#FFFFFF align=left valign=middle><font color=#000000>Name</font></td>
            <td  class=TableHeader bgcolor=#FFFFFF align=left valign=top>
            <input type=text name=Name size=48 maxlength=100 value="<?if (isset ($Name)) print $Name;?>">
            </td><tr>
            <td  class=TableHeader bgcolor=#FFFFFF align=left valign=top><font color=#000000>Short info</font></td>
    		<td  class=TableHeader bgcolor=#FFFFFF align=left valign=top>
    		<textarea rows=4 cols=40 name=ShortInfo><?if (isset ($ShortInfo)) print $ShortInfo;?></textarea>
    		</td><tr>
    		<td  class=TableHeader bgcolor=#FFFFFF align=left valign=top><font color=#000000>Detailed info</font></td>
			    		<td  class=TableHeader bgcolor=#FFFFFF align=left valign=top>
			    		<textarea rows=25 cols=40 name=Info><?if (isset ($Info)) print $Info;?></textarea>
    		</td>
    		
    </table></td></table><p><input type=submit name=Add value="Add" style="font-size: 10pt;"></p> 
<?	
}
function deleteItem ()
{
	global $ITEMS_PER_PAGE,
		   $page;
		   
	$db = new DB ();
	
	for ($i = ($page - 1)*$ITEMS_PER_PAGE; $i < $page*$ITEMS_PER_PAGE; $i++)
	{
		global ${"DeleteID$i"};
		
		if (isset (${"DeleteID$i"}))
		{			
			$db->execute ("delete from Program where ID = ".${"DeleteID$i"});  
			$db->execute ("delete from Code where ProgramID = ".${"DeleteID$i"});
		}
	}
	?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u><font color="#000000">Message</font></u><font color="#000000">:
  </font></font></b><font size="3" color="#000000"><font size="2">Selected programs
  have been deleted.</font></font></font></p>	
	<?
}
function displayDeleteConfirm ()
{
	global $page,
		   $Letter,
		   $ITEMS_PER_PAGE;
		   
	$db = new DB ();
	
	// hide info
	print "\n<input type=hidden name=page value=$page>\n";
	
	if (isset ($Letter))
		print "\n<input type=hidden name=Letter value=$Letter>\n";
	
	$buff = '';
	for ($i = ($page - 1)*$ITEMS_PER_PAGE; $i < $page*$ITEMS_PER_PAGE; $i++)
	{
		global ${"DeleteID$i"};
		
		if (isset (${"DeleteID$i"}))
		{
			$db->execute ("select Name from Program where ID = ".${"DeleteID$i"});
			if ($db->getNumRows () == 0)
				continue;
			$name = stripslashes ($db->recordSet [0][0]);

			$buff .= "<li>$name\n<input type=hidden name=\"DeleteID$i\" value=\"".${"DeleteID$i"}."\">";
		}
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
function displayItemList ()
{
	global $Name,
		   $ShortInfo;

	if (isset ($Name))
		print "\n<input type=hidden name=Name value=\"$Name\">\n";
	if (isset ($ShortInfo))
		print "\n<input type=hidden name=ShortInfo value=\"$ShortInfo\">\n";
						
	global $page,
		   $Letter,
		   $ITEMS_PER_PAGE,
		   $ListAll,
		   $OrderBy,
		   $PrevOrderBy,
		   $CatID;
		  
	if (!isset ($page))
		$page = 1;
	
	// order by 

	if (isset ($PrevOrderBy) &&  $PrevOrderBy != $OrderBy)
		$page = 1;
	
	$PrevOrderBy = $OrderBy;
	
	$link = "./index.php?CatID=$CatID&PrevOrderBy=$PrevOrderBy&page=$page";		
	
	if (isset ($OrderBy) && $OrderBy == "Name")
	{
		$sOrderBy = " order by Name";
		$order = "[<font class=Mail>name</font>&nbsp;|&nbsp;".
				 "<font class=Mail><a href=$link&OrderBy=ShortInfo>short info</a></font>&nbsp;]&nbsp;";	
		print "\n<input type=hidden name=OrderBy value=Name>\n";
	}
	else if (isset ($OrderBy) && $OrderBy == "ShortInfo")
	{
		$sOrderBy = " order by ShortInfo";
		$order = "[<font class=Mail><a href=$link&OrderBy=Name>name</a></font>&nbsp;|&nbsp;".
			 	 "<font class=Mail>short info</font>&nbsp;]&nbsp;";	
		print "\n<input type=hidden name=OrderBy value=ShortInfo>\n";
	}
	else 
	{
		$sOrderBy = " order by ID";
		$order = "[&nbsp;<font class=Mail><a href=$link&OrderBy=Name>name</a></font>&nbsp;|&nbsp;".
			 "<font class=Mail><a href=$link&OrderBy=ShortInfo>short info</a></font>&nbsp;]&nbsp;";	
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
		$link = "./index.php?CatID=$CatID&OrderBy=$OrderBy&PrevOrderBy=$PrevOrderBy&page=$page&Letter=".$letters [$i];
		
		if (isset ($Letter) && $Letter == $letters [$i])
			$abc .= "&nbsp".$letters [$i]."&nbsp;";
		else
			$abc .= "&nbsp<a href=$link>".$letters [$i]."</a>&nbsp;";
	}
	$link = "./index.php?CatID=$CatID&OrderBy=$OrderBy&PrevOrderBy=$PrevOrderBy&page=$page";

	if (isset ($Letter))
		$all = "&nbsp;<a href=$link>All</a>&nbsp;";
	else
		$all = "&nbsp;All&nbsp;";
		
	$abc = "<p><font class=Mail>ABC</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[<font class=Mail>$all&nbsp;</font><font class=Mail>&nbsp;$abc</font>]</p>";
	
	print $abc;
	
	// end display letters
	
	
	$db = new DB ();
	
	$query =  "select 
			   ID, Name, ShortInfo
			   from Program
			   where CatID = $CatID
			   $sOrderBy";

	$db->execute ($query);

	if ($db->getNumRows () == 0)
	{
		?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u>Warning</u>:<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">No programs have 
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
                 <td class=TableHeader align=left valign=middle colspan=8 bgcolor=#C0C0C0>Program list </td>
                      </tr>
                      <tr bgcolor=#FFFFFF> 
                        <td class=TableHeader align=left valign=middle colspan=8 height=1></td>
                      </tr>
                      <tr> 
                        <td  class=TableHeader bgcolor=#C0C0C0 align=center valign=middle><font color=#FFFFFF>#</font></td>
                        <td  class=TableHeader bgcolor=#C0C0C0 align=center valign=middle><font color=#FFFFFF>Name</font></td>
                        <td  class=TableHeader bgcolor=#C0C0C0 align=center valign=middle><font color=#FFFFFF>Short Info</font></td>
                        <td  class=TableHeader bgcolor=#C0C0C0 align=center valign=middle><font color=#FFFFFF>Edit</font></td>
                        <td  class=TableHeader bgcolor=#C0C0C0 align=center valign=middle><font color=#FFFFFF>Delete</font></td>
                      </tr>";               
	
	$numOfItems = $db->getNumRows ();

	$numOfPages = ceil ($numOfItems/$ITEMS_PER_PAGE);
	$displayed = '';

	for ($i = 0; $i < $numOfItems; $i++)
	{
		$id   = $db->recordSet [$i][0];
		$name = stripslashes ($db->recordSet [$i][1]);
		$info = stripslashes ($db->recordSet [$i][2]);
		
		if (isset ($Letter) && strlen ($Letter) == 1 && !eregi ("^$Letter", $name))
			continue;

		if ($i >= ($page - 1)*$ITEMS_PER_PAGE && $i < $page*$ITEMS_PER_PAGE)
		{
		
			$displayed .= "<tr>\n<td  class=TableElement bgcolor=#FFFFFF>".($i + 1)."</td>".
               			  "<td  class=TableElement bgcolor=#FFFFFF><a href=./code/index.php?CatID=$CatID&ProgramID=$id>$name</a></td>\n".
               			  "<td  class=TableElement bgcolor=#FFFFFF>$info</td>".
               			  "<td  class=TableElement bgcolor=#FFFFFF><a href=./index.php?CatID=$CatID&Edit=1&ID=$id>edit</td>".
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
              <p><input type=submit name=Delete value=\"Delete checked\" style=\"font-size: 10pt;\"></p>";

	print "\n<input type=hidden name=page value=$page>\n";
		
	if (isset ($Letter))
		print "\n<input type=hidden name=Letter value=$Letter>\n";
		
	if (strlen ($displayed) == 0)
	{
		?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u>Warning</u>:<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">No programs have 
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
				$link = "./index.php?OrderBy=$OrderBy&PrevOrderBy=$PrevOrderBy&CatID=$CatID&page=$i";
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
			$link = "./index.php?CatID=$CatID&OrderBy=$OrderBy&PrevOrderBy=$PrevOrderBy&page=$prevInd";
			if (isset ($Letter))
				$link .= "&Letter=$Letter";
		
			$pages = "&nbsp;<a href=$link>Previous</a>&nbsp;<br>".$pages;
		}
		if ($page < $numOfPages)
		{
			$nextInd = $page + 1;
			$link = "./index.php?CatID=$CatID&OrderBy=$OrderBy&PrevOrderBy=$PrevOrderBy&page=$nextInd";
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
<title>Admin | Web master programs | Programs</title>
<link rel=stylesheet type=text/css href=../admin.css>
</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" height=100% border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" bgcolor="#C0C0C0">
      <table width="100%" height=100% border="0" cellspacing="1" cellpadding="5">
        <tr>
          <td bgcolor="#C0C0C0" width="25" height=20>&nbsp;</td>
          <td bgcolor="#FFFFFF" colspan=2>
          <?
          		print "<font class=Mail><a href=../index.php>categories</a></font>&nbsp;|&nbsp;";
          		print "<font class=Mail><a href=./index.php?CatID=$CatID>programs</a></font>";
          ?>
            </td>
        </tr>
        <tr> <form method=POST action=./index.php>
          <td align="left" valign="top" bgcolor="#FFFFFF" width="25">&nbsp; </td>
            <td align="left" valign="top" bgcolor="#FFFFFF" height=100%> 
			<?			
				print "\n<input type=hidden name=CatID value=$CatID>\n";
				
				if (isset ($DeleteConfirm))
				{
					deleteItem ();
					displayItemList ();	
				}
				else if (isset ($Delete))
				{
					if (!displayDeleteConfirm ())
						displayItemList ();
				}
				else
					displayItemList ();
			?>
            </td>
            <td align="left" valign="top" bgcolor="#FFFFFF" width="50%">
            <?
            
            if (!empty ($Update) || !empty ($Edit))
            {
            	displayItemEd ();
            	unset ($Name);
            	unset ($Desc);
            }
            
            if ((empty ($Edit) || strlen ($Edit) == 0) && 
            	(empty ($Update) || strlen ($Update) == 0))
            	displayItemAdd ();
            ?>
            </td>
          </form>
        </tr>
        <tr>
          <td bgcolor="#C0C0C0" colspan="2" height=20><font class=Mail>Comments to: <a href=mailto:locihome@yahoo.com>locihome@yahoo.com</a></font></td>
        </tr>
      </table>
  </tr>
</table>
</body>
</html>
