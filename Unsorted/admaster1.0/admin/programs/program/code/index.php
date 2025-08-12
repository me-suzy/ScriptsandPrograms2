<?
include_once "sys/Conf.inc";

// functions block
function displayItemEd ()
{
	global $Name,
		   $Desc,
		   $Type,
		   $Code,
		   $ProgramID,
		   $CatID,
		   $ID,
		   $Edit,
		   $Update;

	print "\n<input type=hidden name=ID value=$ID>\n";
	
	$db = new DB ();
	$db->execute ("select Name, Description, Type, Code from Code where ID = $ID");
	
	if (isset ($Edit))
	{
		$Name = stripslashes ($db->recordSet [0][0]);
		$Desc = stripslashes ($db->recordSet [0][1]);
		$Type = stripslashes ($db->recordSet [0][2]);
		$Code = stripslashes ($db->recordSet [0][3]);
	}
	
	$errorStatus = 0;
	if (isset ($Update) && isset ($Name) && strlen ($Name) == 0)
	{
		$Desc = stripslashes ($Desc);
		$Type = stripslashes ($Type);
		$Code = stripslashes ($Code);
	?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3">-<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">Put a name of new code.</font></font></font></p>	
	<?
		$errorStatus = 1;
	}
	if (isset ($Update) && isset ($Desc) && strlen ($Desc) == 0)
	{
		$Name = stripslashes ($Name);
		$Type = stripslashes ($Type);
		$Code = stripslashes ($Code);
	?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3">-<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">Put a description of new code.</font></font></font></p>	
	<?	
		$errorStatus = 1;
	}
	if (isset ($Update) && !isset ($Type))
	{
		$Desc = stripslashes ($Desc);
		$Name = stripslashes ($Name);
		$Code = stripslashes ($Code);
	?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3">-<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">Select a type of new code.</font></font></font></p>	
	<?	
		$errorStatus = 1;
	}
	if (isset ($Update) && isset ($Code) && strlen ($Code) == 0)
	{
		$Desc = stripslashes ($Desc);
		$Type = stripslashes ($Type);
		$Name = stripslashes ($Name);
	?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3">-<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">Put a new code itself.</font></font></font></p>	
	<?	
		$errorStatus = 1;
	}
	
	
	if (isset ($Update) && isset ($Name) && isset ($Desc) && isset ($Type) && isset ($Code) && $errorStatus == 0)
	{
		
		$db        = new DB ();
		$Name      = addslashes ($Name);
		$Desc      = addslashes ($Desc);
		$Type      = addslashes ($Type);
		$Code      = addslashes ($Code);
		
		$query = "update Code set ProgramID = $ProgramID, Name = '$Name', Type = '$Type', Description = '$Desc', Code = '$Code' where ID = $ID";
		
		if ($db->execute ($query))
		{
			?>
			<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u><font color="#000000">Message</font></u><font color="#000000">:
  </font></font></b><font size="3" color="#000000"><font size="2">Code has been updated.</font></font></font></p>
  <p><font class=Mail><a href=./index.php?ProgramID=<?print $ProgramID;?>&CatID=<?print $CatID;?>>Click here to refresh data</a></font></p>	
  <?			
  			$Name = '';
			unset ($Name);
			$Desc = '';
			unset ($Desc);
			$Type = '';
			unset ($Type);
			$Code = '';
			unset ($Code);
		}
		else
		{
	?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u>Warning</u>:<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">Code has not been updated.</font></font></font></p>	
<?			
		}
	}
?>
	
	<table border=0 cellspacing=0 cellpadding=0>
       	<tr>
          <td bgcolor=#C0C0C0 align=left valign=top>
          <table border=0 cellspacing=1 cellpadding=3>
          <tr> 
           <td class=TableHeader align=left valign=middle colspan=2 bgcolor=#C0C0C0>Code</td>
                </tr>
            
 	<td  class=TableHeader bgcolor=#FFFFFF align=left valign=middle><font color=#000000>Name</font></td>
            <td  class=TableHeader bgcolor=#FFFFFF align=left valign=top>
            <input type=text name=Name size=48 maxlength=100 value="<?if (isset ($Name)) print htmlentities ($Name);?>">
            </td><tr>
            <td  class=TableHeader bgcolor=#FFFFFF align=left valign=top><font color=#000000>Description</font></td>
    		<td  class=TableHeader bgcolor=#FFFFFF align=left valign=top>
    		<textarea rows=4 cols=40 name=Desc><?if (isset ($Desc)) print htmlentities ($Desc);?></textarea>
    		</td><tr>
              <td  class=TableHeader bgcolor=#FFFFFF align=left valign=top><font color=#000000>Type</font></td>
      		<td  class=TableHeader bgcolor=#FFFFFF align=left valign=top>
      		<?
			$db = new DB ();
			$db->execute ("select Name, DefaultCode from CodeType order by ID");
			
			$input = "<select name=Type size=5 onChange=\"select ();\">\n";
			
			if ($db->getNumRows () == 0)
				return "<font color=red><b>Add at least one code type by means of <br>corresponding part of admin interface.</b></font>\n";
			$selected = "";
			$selectStatus = false;
			$hidden = '';
			for ($i = 0; $i < $db->getNumRows (); $i++)
			{	
				if ($selectStatus)
					$selected = "";
				else if ((isset ($Type) && $Type == $db->recordSet [$i][0]))
				{
					$selected = " selected";
					$selectStatus = true;
				}

				$input .= "<option value=\"".$db->recordSet [$i][0]."\"$selected>".$db->recordSet [$i][0];
				$hidden .= "<input type=hidden name=DefaultCode$i value=\"".$db->recordSet [$i][1]."\">";
			}
			$input .= "</select>\n$hidden";
			
			print $input;
      		
      		?>
    		</td><tr>
    		<td  class=TableHeader bgcolor=#FFFFFF align=left valign=top><font color=#000000>Code</font></td>
			    		<td  class=TableHeader bgcolor=#FFFFFF align=left valign=top>
			    		<textarea rows=6 cols=40 name=Code><?if (isset ($Code)) print htmlentities ($Code);?></textarea>
    		</td>
    		
    </table></td></table><p><input type=submit name=Update value="Update" style="font-size: 10pt;"></p> 
<?	
}
function displayItemAdd ()
{
	global $Name,
		   $Desc,
		   $Type,
		   $Code,
		   $ProgramID,
		   $CatID,
		   $Update;

	$errorStatus = 0;
	if (!isset ($Update) && isset ($Name) && strlen ($Name) == 0)
	{
		$Desc = stripslashes ($Desc);
		$Type = stripslashes ($Type);
		$Code = stripslashes ($Code);
	?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3">-<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">Put a name of new code.</font></font></font></p>	
	<?
		$errorStatus = 1;
	}
	if (!isset ($Update) && isset ($Desc) && strlen ($Desc) == 0)
	{
		$Code = stripslashes ($Code);
		$Type = stripslashes ($Type);
		$Name = stripslashes ($Name);
	?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3">-<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">Put a description of new code.</font></font></font></p>	
	<?	
		$errorStatus = 1;
	}
	if (!isset ($Update) && !isset ($Type))
	{
		$Desc = stripslashes ($Desc);
		$Code = stripslashes ($Code);
		$Name = stripslashes ($Name);
	?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3">-<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">Select a type of new code.</font></font></font></p>	
	<?	
		$errorStatus = 1;
	}
	if (!isset ($Update) && isset ($Code) && strlen ($Code) == 0)
	{
		$Desc = stripslashes ($Desc);
		$Type = stripslashes ($Type);
		$Name = stripslashes ($Name);
	?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3">-<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">Put a new code itself.</font></font></font></p>	
	<?	
		$errorStatus = 1;
	}
	
	
	if (isset ($Name) && isset ($Desc) && isset ($Type) && isset ($Code) && $errorStatus == 0)
	{
		
		$db        = new DB ();
		$Name      = addslashes ($Name);
		$Desc      = addslashes ($Desc);
		$Type      = addslashes ($Type);
		$Code      = addslashes ($Code);
		
		if ($db->execute ("insert into Code (ProgramID, Name, Type, Description, Code) values ($ProgramID, '$Name', '$Type', '$Desc', '$Code')"))
		{
			?>
			<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u><font color="#000000">Message</font></u><font color="#000000">:
  </font></font></b><font size="3" color="#000000"><font size="2">New code has been added.</font></font></font></p>
  <p><font class=Mail><a href=./index.php?ProgramID=<?print $ProgramID;?>&CatID=<?print $CatID;?>>Click here to refresh data</a></font></p>	
  <?			
  			$Name = '';
			unset ($Name);
			$Desc = '';
			unset ($Desc);
			$Type = '';
			unset ($Type);
			$Code = '';
			unset ($Code);
		}
		else
		{
	?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u>Warning</u>:<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">New code has not been added.</font></font></font></p>	
<?			
		}
	}
?>
	
	<table border=0 cellspacing=0 cellpadding=0>
       	<tr>
          <td bgcolor=#C0C0C0 align=left valign=top>
          <table border=0 cellspacing=1 cellpadding=3>
          <tr> 
           <td class=TableHeader align=left valign=middle colspan=2 bgcolor=#C0C0C0>New code</td>
                </tr>
            
 	<td  class=TableHeader bgcolor=#FFFFFF align=left valign=middle><font color=#000000>Name</font></td>
            <td  class=TableHeader bgcolor=#FFFFFF align=left valign=top>
            <input type=text name=Name size=48 maxlength=100 value="<?if (isset ($Name)) print htmlentities ($Name);?>">
            </td><tr>
            <td  class=TableHeader bgcolor=#FFFFFF align=left valign=top><font color=#000000>Description</font></td>
    		<td  class=TableHeader bgcolor=#FFFFFF align=left valign=top>
    		<textarea rows=4 cols=40 name=Desc><?if (isset ($Desc)) print htmlentities ($Desc);?></textarea>
    		</td><tr>
              <td  class=TableHeader bgcolor=#FFFFFF align=left valign=top><font color=#000000>Type</font></td>
      		<td  class=TableHeader bgcolor=#FFFFFF align=left valign=top>
      		<?
			$db = new DB ();
			$db->execute ("select Name, DefaultCode from CodeType order by ID");
			
			$input = "<select name=Type size=5 onChange=\"select ();\">\n";
			
			if ($db->getNumRows () == 0)
				return "<font color=red><b>Add at least one code type by means of <br>corresponding part of admin interface.</b></font>\n";
			$selected = "";
			$selectStatus = false;
			$hidden = '';
			for ($i = 0; $i < $db->getNumRows (); $i++)
			{	
				if ($selectStatus)
					$selected = "";
				else if ((isset ($Type) && $Type == $db->recordSet [$i][0]))
				{
					$selected = " selected";
					$selectStatus = true;
				}

				$input .= "<option value=\"".$db->recordSet [$i][0]."\"$selected>".$db->recordSet [$i][0];
				$hidden .= "<input type=hidden name=DefaultCode$i value=\"".$db->recordSet [$i][1]."\">";
			}
			$input .= "</select>\n$hidden";
			
			print $input;
      		
      		?>
    		</td><tr>
    		<td  class=TableHeader bgcolor=#FFFFFF align=left valign=top><font color=#000000>Code</font></td>
			    		<td  class=TableHeader bgcolor=#FFFFFF align=left valign=top>
			    		<textarea rows=6 cols=40 name=Code><?if (isset ($Code)) print htmlentities ($Code);?></textarea>
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
			$db->execute ("delete from Code where ID = ".${"DeleteID$i"});  
		}
	}
	?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u><font color="#000000">Message</font></u><font color="#000000">:
  </font></font></b><font size="3" color="#000000"><font size="2">Selected codes
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
			$db->execute ("select Name from Code where ID = ".${"DeleteID$i"});
			if ($db->getNumRows () == 0)
				continue;
			$name = $db->recordSet [0][0];

			$buff .= "<li>$name\n<input type=hidden name=\"DeleteID$i\" value=\"".${"DeleteID$i"}."\">";
		}
	}
	
	if (strlen ($buff) == 0)
	{
		?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u>Warning</u>:<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">Select at least one code to remove.</font></font></font></p>	
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
		   $Desc;

	if (isset ($Name))
		print "\n<input type=hidden name=Name value=\"$Name\">\n";
	if (isset ($Desc))
		print "\n<input type=hidden name=Desc value=\"$Desc\">\n";
						
	global $page,
		   $Letter,
		   $ITEMS_PER_PAGE,
		   $ListAll,
		   $OrderBy,
		   $PrevOrderBy,
		   $CatID,
		   $ProgramID;
		  
	if (!isset ($page))
		$page = 1;
	
	// order by 

	if (isset ($PrevOrderBy) &&  $PrevOrderBy != $OrderBy)
		$page = 1;
	
	$PrevOrderBy = $OrderBy;
	
	$link = "./index.php?CatID=$CatID&ProgramID=$ProgramID&PrevOrderBy=$PrevOrderBy&page=$page";		
	
	if (isset ($OrderBy) && $OrderBy == "Name")
	{
		$sOrderBy = " order by Name";
		$order = "[<font class=Mail>name</font>&nbsp;|&nbsp;".
				 "<font class=Mail><a href=$link&OrderBy=Desc>description</a></font>&nbsp;]&nbsp;";	
		print "\n<input type=hidden name=OrderBy value=Name>\n";
	}
	else if (isset ($OrderBy) && $OrderBy == "ShortInfo")
	{
		$sOrderBy = " order by ShortInfo";
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
		$link = "./index.php?CatID=$CatID&ProgramID=$ProgramID&OrderBy=$OrderBy&PrevOrderBy=$PrevOrderBy&page=$page&Letter=".$letters [$i];
		
		if (isset ($Letter) && $Letter == $letters [$i])
			$abc .= "&nbsp".$letters [$i]."&nbsp;";
		else
			$abc .= "&nbsp<a href=$link>".$letters [$i]."</a>&nbsp;";
	}
	$link = "./index.php?CatID=$CatID&ProgramID=$ProgramID&OrderBy=$OrderBy&PrevOrderBy=$PrevOrderBy&page=$page";

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
			   from Code
			   where ProgramID = $ProgramID
			   $sOrderBy";

	$db->execute ($query);

	if ($db->getNumRows () == 0)
	{
		?>
<p><font face="Arial, Helvetica, sans-serif" color="#FF0000"><b><font size="3"><u>Warning</u>:<font color="#333333"> 
  </font></font></b><font size="3" color="#333333"><font size="2">No codes have 
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
                 <td class=TableHeader align=left valign=middle colspan=8 bgcolor=#C0C0C0>Code list </td>
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
		$name = htmlentities (stripslashes ($db->recordSet [$i][1]), ENT_QUOTES);
		$desc = htmlentities (stripslashes ($db->recordSet [$i][2]), ENT_QUOTES);
		
		if (isset ($Letter) && strlen ($Letter) == 1 && !eregi ("^$Letter", $name))
			continue;

		if ($i >= ($page - 1)*$ITEMS_PER_PAGE && $i < $page*$ITEMS_PER_PAGE)
		{
		
			$displayed .= "<tr>\n<td  class=TableElement bgcolor=#FFFFFF>".($i + 1)."</td>".
               			  "<td  class=TableElement bgcolor=#FFFFFF><a href=./index.php?CatID=$CatID&ProgramID=$ProgramID>$name</a></td>\n".
               			  "<td  class=TableElement bgcolor=#FFFFFF>$desc</td>".
               			  "<td  class=TableElement bgcolor=#FFFFFF><a href=./index.php?CatID=$CatID&ProgramID=$ProgramID&Edit=1&ID=$id>edit</td>".
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
  </font></font></b><font size="3" color="#333333"><font size="2">No codes have 
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
				$link = "./index.php?OrderBy=$OrderBy&PrevOrderBy=$PrevOrderBy&CatID=$CatID&ProgramID=$ProgramID&page=$i";
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
			$link = "./index.php?CatID=$CatID&ProgramID=$ProgramID&OrderBy=$OrderBy&PrevOrderBy=$PrevOrderBy&page=$prevInd";
			if (isset ($Letter))
				$link .= "&Letter=$Letter";
		
			$pages = "&nbsp;<a href=$link>Previous</a>&nbsp;<br>".$pages;
		}
		if ($page < $numOfPages)
		{
			$nextInd = $page + 1;
			$link = "./index.php?CatID=$CatID&ProgramID=$ProgramID&OrderBy=$OrderBy&PrevOrderBy=$PrevOrderBy&page=$nextInd";
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
<title>Admin | Web master programs | Code</title>
<link rel=stylesheet type=text/css href=../../admin.css>
<script language=JavaScript>
function select ()
{
	document.MainForm.Code.value = eval ('document.MainForm.DefaultCode' + (document.MainForm.Type.selectedIndex) + '.value');
}
</script>
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
          		print "<font class=Mail><a href=../../index.php>categories</a></font>&nbsp;|&nbsp;";
          		print "<font class=Mail><a href=../index.php?CatID=$CatID&ProgramID=$ProgramID>programs</a></font>&nbsp;|&nbsp;";
          		print "<font class=Mail><a href=./index.php?CatID=$CatID&ProgramID=$ProgramID&ProgramID=$ProgramID>code</a></font>";
          ?>
            </td>
        </tr>
        <tr> <form name=MainForm method=POST action=./index.php>
          <td align="left" valign="top" bgcolor="#FFFFFF" width="25">&nbsp; </td>
            <td align="left" valign="top" bgcolor="#FFFFFF" height=100%> 
			<?			
				print "\n<input type=hidden name=CatID value=$CatID>\n";
				print "\n<input type=hidden name=ProgramID value=$ProgramID>\n";
				
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
