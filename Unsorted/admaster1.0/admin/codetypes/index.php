<html>
<head>
<title>Admin interface</title>
<link rel=stylesheet type=text/css href=./admin.css>
</head>
<body bgcolor=white>
<?
include_once "sys/Conf.inc";
include_once "sys/admin/DataObject.inc";
include_once "sys/admin/CodeType.inc";

$dataObject = new CodeType ();

?>
<table width=500 cellpadding=0 cellspacing=0>
<td width=100 align=left valign=top>&nbsp;<font class=Locator>Position:</font></td>
<td align=left valign=top class=TableElement>
&nbsp;<font class=Locator><a href=./index.php>code type</a></font></td><tr>
<?	


if (isset ($Add))
{
	?>
	<td width=100 align=left valign=top>&nbsp;<font class=Locator>Action:</font></td>
	<td align=left valign=top>&nbsp;<font class=Locator>New code type addition</font></td></table>
	<hr width=500 align=left noshadow size=1>
	<?
	
	$dataObject->add ();
	
	print "&nbsp;<font class=Message>".$dataObject->message ()."</font>";	
}
else if (isset ($Update))
{
	?>
	<td width=100 align=left valign=top>&nbsp;<font class=Locator>Action:</font></td>
	<td align=left valign=top>&nbsp;<font class=Locator>The code type updating</font></td></table>
	<hr width=500 align=left noshadow size=1>
	<?
	
	if ($dataObject->update ($ID))
	{
		if (isset ($Edit))
			unset ($Edit);
		if (isset ($Update))
			unset ($Update);
	}	
	
	print "&nbsp;<font class=Message>".$dataObject->message ()."</font>";	
}
else if (isset ($Delete))
{
	?>
	<td width=100 align=left valign=top>&nbsp;<font class=Locator>Action:</font></td>
	<td align=left valign=top>&nbsp;<font class=Locator>The code type deleting</font></td></table>
	<hr width=500 align=left noshadow size=1>
	<?
	
	$id = $dataObject->listByField ();
	
	$deleteResult = true;
	for ($i = 0; $i < sizeof ($id); $i++)
		if (isset (${"ID".$id [$i]}))
			if (!$dataObject->delete ($id [$i]))
				$deleteResult = true;
	
	if (!$deleteResult)
		print "&nbsp;<font class=Message>".$dataObject->deleteError."</font>";
	else
		print "&nbsp;<font class=Message>".$dataObject->deleteSuccess."</font>";
}
else
{
	?>
	<td width=100 align=left valign=top>&nbsp;<font class=Locator>Action:</font></td>
	<td align=left valign=top>&nbsp;<font class=Locator>no action</font></td></table>
	<hr width=500 align=left noshadow size=1>
	<?
}

?>
<form method=GET>
<?
	if (isset ($Edit) || isset ($Update))
	{
		$dataObject->displayInput ($ID);
		?>
		<table width=500 cellpadding=5 cellspacing=0>
		<td width=200 align=right valign=top>&nbsp;</td>
		<td align=left valign=top>&nbsp;<input type=submit name=Update value="Update">
		&nbsp;&nbsp;</td></table>
		<?
		print "\n<input type=hidden name=ID value=$ID>\n";
	}
	else 
	{
		$id = $dataObject->listByField ();
		
		if (sizeof ($id) > 0)
		{
			?>	
			<table width=500 cellpadding=0 cellspacing=0>
			<td bgcolor=white>
			<table width=500 cellpadding=5 cellspacing=1>
			<td width=100 class=TableHeader bgcolor=#D3D3D3>Edit</td>
			<td class=TableHeader bgcolor=#D3D3D3>Name</td>
			<td class=TableHeader bgcolor=#D3D3D3>Description</td>
			<td class=TableHeader bgcolor=#D3D3D3>Delete</td><tr>
			<?
				for ($i = 0; $i < sizeof ($id); $i++)
				{
					print "<td class=TableElement bgcolor=#D3D3D3><a href=./index.php?Edit=yes&ID=".$id [$i].">edit</a></td>";
					print "<td class=TableElement bgcolor=#D3D3D3><a href=./index.php>".$dataObject->field ("Name", $id [$i])."</a></td>";
					print "<td class=TableElement bgcolor=#D3D3D3>".$dataObject->field ("Description", $id [$i])."</td>";
					print "<td class=TableElement bgcolor=#D3D3D3><input type=checkbox name=ID".$id [$i]."></td><tr>";
				}
			?>
			<td width=100 class=TableHeader bgcolor=white>&nbsp;</td>
			<td class=TableHeader bgcolor=white>&nbsp;</td>
			<td class=TableHeader bgcolor=white>&nbsp;</td>
			<td class=TableHeader bgcolor=white><input type=submit name=Delete value="Delete selected"></td>
			</table></td></table>
			<hr width=500 align=left noshadow size=1><br>
		<? 
		}
		
		$dataObject->displayInput ();
		
		?>	
			<table width=500 cellpadding=5 cellspacing=0>
			<td width=200 align=right valign=top>&nbsp;</td>
			<td align=left valign=top>&nbsp;<input type=submit name=Add value="Add">
			&nbsp;&nbsp;</td></table><br>
		<?
	}
	?>
<hr width=500 align=left noshadow size=1>
&nbsp;<font class=Mail>All comments to: <a href=mailto:locihome@yahoo.com>locihome@yahoo.com</a></font>
</form>
</body></html>