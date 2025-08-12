<?
include_once "sys/Conf.inc";

// functions block
function displayItemEd ()
{
	global $Name,
		   $Desc,
		   $Edit,
		   $Update,
		   $ID;

	$db = new DB ();
	$db->execute ("select Name, Description from Category where ID = $ID");
	
	print "\n<input type=hidden name=ID value=$ID>\n";
	
	if (isset ($Edit))
	{
		$Name = stripslashes ($db->recordSet [0][0]);
		$Desc = stripslashes ($db->recordSet [0][1]);		
	}
	
	$errorStatus = 0;
	if (isset ($Update) && isset ($Name) && strlen ($Name) == 0)
	{
		$Desc = stripslashes ($Desc);
	?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3">-<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">Put a name of new category.</font></font></font></p>	
	<?	
		$errorStatus = 1;
	}
	if (isset ($Update) && isset ($Desc) && strlen ($Desc) == 0)
	{
		$Name = stripslashes ($Name);
	?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3">-<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">Put a description of new category.</font></font></font></p>	
	<?	
		$errorStatus = 1;
	}
	
	if (isset ($Update) && isset ($Name) && isset ($Desc) && $errorStatus == 0)
	{
		$Name = addslashes ($Name);
		$Desc = addslashes ($Desc);
		
		if ($db->execute ("update Category set Name = '$Name' , Description = '$Desc' where ID = $ID"))
		{
			?>
			<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u><font color="#000000">Message</font></u><font color="#000000">:
  </font></font></b><font size="3" color="#000000"><font size="2">Category has been updated.</font></font></font></p>	
  <p><font class=Mail><a href=./index.php>Click here to refresh data</a></font></p>	
  <?			
  			$Name = '';
			unset ($Name);
			$Desc = '';
			unset ($Desc);
			$Update = '';
			unset ($Update);
			
			return;
		}
		else
		{
	?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u>Warning</u>:<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">Category has not been updated.</font></font></font></p>	
<?			
		}
	}
?>
	
	<table border=0 cellspacing=0 cellpadding=0>
       	<tr>
          <td bgcolor=#C0C0C0 align=left valign=top>
          <table border=0 cellspacing=1 cellpadding=3>
          <tr> 
           <td class=TableHeader align=left valign=middle colspan=2 bgcolor=#C0C0C0>Category</td>
                </tr>
            
 	<td  class=TableHeader bgcolor=#FFFFFF align=left valign=middle><font color=#000000>Name</font></td>
            <td  class=TableHeader bgcolor=#FFFFFF align=left valign=top>
            <input type=text name=Name size=48 maxlength=100 value="<?if (isset ($Name)) print htmlentities ($Name);?>">
            </td><tr>
            <td  class=TableHeader bgcolor=#FFFFFF align=left valign=top><font color=#000000>Description</font></td>
    		<td  class=TableHeader bgcolor=#FFFFFF align=left valign=top>
    		<textarea rows=20 cols=40 name=Desc><?if (isset ($Desc)) print htmlentities ($Desc);?></textarea>
    		</td>
    		
    </table></td></table><p><input type=submit name=Update value="Update" style="font-size: 10pt;"></p> 
<?	
}
function displayItemAdd ()
{
	global $Name,
		   $Desc;

	$errorStatus = 0;
	if (isset ($Name) && strlen ($Name) == 0)
	{
		$Desc = stripslashes ($Desc);
	?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3">-<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">Put a name of new category.</font></font></font></p>	
	<?
		$errorStatus = 1;
	}
	if (isset ($Desc) && strlen ($Desc) == 0)
	{
		$Name = stripslashes ($Name);
	?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3">-<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">Put a description of new category.</font></font></font></p>	
	<?	
		$errorStatus = 1;
	}
	
	if (isset ($Name) && isset ($Desc) && $errorStatus == 0)
	{
		
		$db = new DB ();
		$Name = addslashes ($Name);
		$Desc = addslashes ($Desc);
		
		if ($db->execute ("insert into Category (Name, Description) values ('$Name', '$Desc')"))
		{
			?>
			<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u><font color="#000000">Message</font></u><font color="#000000">:
  </font></font></b><font size="3" color="#000000"><font size="2">New category has been added.</font></font></font></p>	
   <p><font class=Mail><a href=./index.php>Click here to refresh data</a></font></p>
  <?			
  			$Name = '';
			unset ($Name);
			$Desc = '';
			unset ($Desc);
		}
		else
		{
	?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u>Warning</u>:<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">New category has not been added.</font></font></font></p>	
<?			
		}
	}
?>
	
	<table border=0 cellspacing=0 cellpadding=0>
       	<tr>
          <td bgcolor=#C0C0C0 align=left valign=top>
          <table border=0 cellspacing=1 cellpadding=3>
          <tr> 
           <td class=TableHeader align=left valign=middle colspan=2 bgcolor=#C0C0C0>New category</td>
                </tr>
            
 	<td  class=TableHeader bgcolor=#FFFFFF align=left valign=middle><font color=#000000>Name</font></td>
            <td  class=TableHeader bgcolor=#FFFFFF align=left valign=top>
            <input type=text name=Name size=48 maxlength=100 value="<?if (isset ($Name)) print $Name;?>">
            </td><tr>
            <td  class=TableHeader bgcolor=#FFFFFF align=left valign=top><font color=#000000>Description</font></td>
    		<td  class=TableHeader bgcolor=#FFFFFF align=left valign=top>
    		<textarea rows=20 cols=40 name=Desc><?if (isset ($Desc)) print $Desc;?></textarea>
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
			$CatID = ${"DeleteID$i"};
			$db->execute ("delete from Category where ID = ".${"DeleteID$i"});  
			
			$db->execute ("select ID from Program where CatID = $CatID");
			
			for ($j = 0; $j < $db->getNumRows (); $j++)
			{
				$ProgramID = $db->recordSet [$j][0];
				$ldb = new DB ();
				$ldb->execute ("delete from Code where ProgramID = $ProgramID");				
			}
			$db->execute ("delete from Program where CatID = ".${"DeleteID$i"});
		}
	}
	?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u><font color="#000000">Message</font></u><font color="#000000">:
  </font></font></b><font size="3" color="#000000"><font size="2">Selected categories
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
			$db->execute ("select Name from Category where ID = ".${"DeleteID$i"});
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
  </font></font></b><font size="3" color="#333333"><font size="2">Select at least one category to remove.</font></font></font></p>	
		<?	
		return false;
	}
	
	?>
	<p><font class=Dialog>Do you really want to remove these categories?</font></p>
	<ul><?=$buff?></ul><br>
	<input type=button onClick="javascript:history.back()" value="No">&nbsp;<input type=submit name=DeleteConfirm value="Yes">	
	<?
	
	return true;
}
function displayItemList ()
{
	global $Name,
		   $Desc,
		   $Edit,
		   $Update,
		   $Add;

	if (!isset ($Add) && !isset ($Edit) && !isset ($Update) && isset ($Name))
		print "\n<input type=hidden name=Name value=\"$Name\">\n";
	if (!isset ($Add) && !isset ($Edit) && !isset ($Update) && isset ($Desc))
		print "\n<input type=hidden name=Name value=\"$Desc\">\n";
						
	global $page,
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
	
	$link = "./index.php?PrevOrderBy=$PrevOrderBy&page=$page";		
	
	if (isset ($OrderBy) && $OrderBy == "Name")
	{
		$sOrderBy = " order by Name";
		$order = "[<font class=Mail>name</font>&nbsp;|&nbsp;".
				 "<font class=Mail><a href=$link&OrderBy=Desc>description</a></font>&nbsp;]&nbsp;";	
		print "\n<input type=hidden name=OrderBy value=Name>\n";
	}
	else if (isset ($OrderBy) && $OrderBy == "Desc")
	{
		$sOrderBy = " order by Description";
		$order = "[<font class=Mail><a href=$link&OrderBy=Name>name</a></font>&nbsp;|&nbsp;".
			 	 "<font class=Mail>description</font>&nbsp;]&nbsp;";	
		print "\n<input type=hidden name=OrderBy value=Desc>\n";
	}
	else 
	{
		$sOrderBy = " order by ID";
		$order = "[&nbsp;<font class=Mail><a href=$link&OrderBy=Name>name</a></font>&nbsp;|&nbsp;".
			 "<font class=Mail><a href=$link&OrderBy=Desc>description</a></font>&nbsp;]&nbsp;";	
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
		$link = "./index.php?OrderBy=$OrderBy&PrevOrderBy=$PrevOrderBy&page=$page&Letter=".$letters [$i];
		
		if (isset ($Letter) && $Letter == $letters [$i])
			$abc .= "&nbsp".$letters [$i]."&nbsp;";
		else
			$abc .= "&nbsp<a href=$link>".$letters [$i]."</a>&nbsp;";
	}
	$link = "./index.php?OrderBy=$OrderBy&PrevOrderBy=$PrevOrderBy&page=$page";

	if (isset ($Letter))
		$all = "&nbsp;<a href=$link>All</a>&nbsp;";
	else
		$all = "&nbsp;All&nbsp;";
		
	$abc = "<p><font class=Mail>ABC</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[<font class=Mail>$all&nbsp;</font><font class=Mail>&nbsp;$abc</font>]</p>";
	
	print $abc;
	
	// end display letters
	
	
	$db = new DB ();
	
	$query =  "select 
			   ID, Name, Description
			   from Category 
			   $sOrderBy";

	$db->execute ($query);

	if ($db->getNumRows () == 0)
	{
		?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u>Warning</u>:<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">No categories have 
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
                 <td class=TableHeader align=left valign=middle colspan=8 bgcolor=#C0C0C0>Category list </td>
                      </tr>
                      <tr bgcolor=#FFFFFF> 
                        <td class=TableHeader align=left valign=middle colspan=8 height=1></td>
                      </tr>
                      <tr> 
                        <td  class=TableHeader bgcolor=#C0C0C0 align=center valign=middle><font color=#FFFFFF>#</font></td>
                        <td  class=TableHeader bgcolor=#C0C0C0 align=center valign=middle><font color=#FFFFFF>Name</font></td>
                        <td  class=TableHeader bgcolor=#C0C0C0 align=center valign=middle><font color=#FFFFFF>Description</font></td>
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
		$desc = stripslashes ($db->recordSet [$i][2]);
		
		if (isset ($Letter) && strlen ($Letter) == 1 && !eregi ("^$Letter", $name))
			continue;

		if ($i >= ($page - 1)*$ITEMS_PER_PAGE && $i < $page*$ITEMS_PER_PAGE)
		{
		
			$displayed .= "<tr>\n<td  class=TableElement bgcolor=#FFFFFF>".($i + 1)."</td>".
               			  "<td  class=TableElement bgcolor=#FFFFFF><a href=./program/index.php?CatID=$id>$name</a></td>\n".
               			  "<td  class=TableElement bgcolor=#FFFFFF>$desc</td>".
               			  "<td  class=TableElement bgcolor=#FFFFFF><a href=./index.php?Edit=1&ID=$id>edit</td>".
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
  </font></font></b><font size="3" color="#333333"><font size="2">No categories have 
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
          <td bgcolor="#FFFFFF" colspan=2>
          <?
          		print "<font class=Mail>categories</font>";
          ?>
            </td>
        </tr>
        <tr> <form method=GET action=./index.php>
          <td align="left" valign="top" bgcolor="#FFFFFF" width="25">&nbsp; </td>
            <td align="left" valign="top" bgcolor="#FFFFFF" height=100%> 
			<?			
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
