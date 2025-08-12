<?

  require("../conf/sys.conf");
  require("../lib/ban.lib");
  require("../lib/mysql.lib");
  require("bots/errbot");

$db = c();

//$table="members";

if (!$table) exit;

$r=q("SHOW FIELDS FROM $table");
$fieldnr=nr($r);
$field=array();
$fieldt=array();


while ($fl=f($r)) 
	{
		$field[]=$fl["Field"];
		$fieldn[$fl["Field"]]=strtoupper($fl["Field"]);
		$fieldt[]=$fl["Type"];
	}

$file = fopen ("tables/$table", "r");
if ($file) include("tables/$table");
fclose($file);
echo "$tabdetails <br><br>";
$conds=$conditions;
$conditions=stripslashes($conditions);
if (!$id)
	{	if ($addentry) echo "<br><b><a href=sqledit.php?table=$table&modify=add&id=-1>ADD NEW</a></b><BR>";
		$r=q("select * from $table $conditions $order");
		if (e($r)) echo "No entries found ! ($conditions $order)";
			else {
				  echo "<script src='sqledit.js' type='text/javascript' language='javascript'></script><TABLE border=0 cellpadding=2 cellspacing=1 ><TR><TD></TD><TD></TD>";
				  for ($i=0;$i<$fcnr;$i++) echo "<TD></TD>";
				  for ($i=0;$i<$fieldnr;$i++) if (!$hide[$field[$i]]) echo "<TD bgcolor=#D0D0D0><B><A href=\"sqledit.php?table=$table&conditions=$conditions&order=order by $field[$i]\">".$fieldn[$field[$i]]."</A></B></TD>";
				  echo "</TR>";
				  while ($row=f($r))
						{
						
							$dellink="<B><A href='sqledit.php?id=$row[id]&modify=delete&table=$table'>Delete</A></B>";
							if ($nodelete) $dellink="";

							echo "<TR bgcolor='#EDEDED' onmouseover=\"rowCol(this, 'over', '#EDEDED', '#CCCCFF', '#EDEDED')\" onmouseout=\"rowCol(this, 'out', '#EDEDED', '#CCCCFF', '#EDEDED')\" onmousedown=\"rowCol(this, 'click', '#EDEDED', '#CCCCFF', '#EDEDED')\"><TD><B><A href='sqledit.php?id=$row[id]&modify=edit&table=$table&conditions=$conds'>Edit</A></B></TD><TD>$dellink</TD>";
			 			  	
							for ($i=0;$i<$fcnr;$i++) echo "<TD><a target=_blank href='$fc[$i]&id=$row[id]'>$fcn[$i]</A></TD>";
							for ($i=0;$i<$fieldnr;$i++)  if (!$hide[$field[$i]])  echo "<TD>".$row[$field[$i]]."</TD>";
							echo "</TR>";
						}
				   echo "</TABLE>";
			  }

	}elseif ($modify=="edit")
	{
		echo "<B>EDIT DATA</B><br><form action='sqledit.php' method=post>";
		$r=q("select * from $table where id='$id'");
		$row=f($r);
		echo "<TABLE border=0 cellpadding=2 cellspacing=1>";
		for ($i=0;$i<$fieldnr;$i++)  if ((!$hide[$field[$i]])&&$field[$i]!="id")  if (!$noedit[$field[$i]]) echo "<TR><TD>".$fieldn[$field[$i]]."</TD><TD><INPUT size=45 name=var_$field[$i] value='".$row[$field[$i]]."'></TD></TR>"; else echo "<TR><TD><INPUT type=hidden name=var_$field[$i] value='".$row[$field[$i]]."'>".$fieldn[$field[$i]]."</TD><TD>".$row[$field[$i]]."</TD></TR>";
 	                echo "<TR><TD><INPUT type=button value='Cancel' onclick=\"history.go(-1);\"></TD><TD><INPUT type=reset value='Reset to default'><INPUT type='Submit' value='Apply changes'><INPUT type=hidden name='modify' value='apply'><INPUT type=hidden name='table' value='$table'><INPUT type=hidden name='id' value='$id'></TD></TR>";
 	                echo "</TABLE></form>";
	}elseif ($modify=="add")
	{
		echo "<B>INSERT DATA</B><br><form action='sqledit.php' method=post>";
		echo "<TABLE border=0 cellpadding=2 cellspacing=1>";
		for ($i=0;$i<$fieldnr;$i++)  if ((!$hide[$field[$i]])&&$field[$i]!="id")  echo "<TR><TD>".$fieldn[$field[$i]]."</TD><TD><INPUT size=45 name=var_$field[$i]></TD></TR>";
 	                echo "<TR><TD><INPUT type=button value='Cancel' onclick=\"history.go(-1);\"></TD><TD><INPUT type=reset value='Reset to default'><INPUT type='Submit' value='Insert Data'><INPUT type=hidden name='modify' value='insert'><INPUT type=hidden name='table' value='$table'><INPUT type=hidden name='id' value='-1'></TD></TR>";
 	                echo "</TABLE></form>";
	}elseif ($modify=="apply")
	{
	echo "Updating data ...";
	$qr="UPDATE $table SET";
	for ($i=0;$i<$fieldnr;$i++) if ((!$hide[$field[$i]])&&($field[$i]!="id")) $qr.=" $field[$i]='".${"var_".$field[$i]}."',";
	$qr.=" id='$id' WHERE id='$id' ";
	q($qr);
	echo "<script>document.location='sqledit.php?table=$table&conditions=$conditions';</script>";
	}elseif ($modify=="insert")
	{
	$qr="INSERT INTO $table VALUES(''";
	for ($i=1;$i<$fieldnr;$i++) $qr.=",'".${"var_".$field[$i]}."'";
	$qr.=")";
	q($qr);
	echo "<script>document.location='sqledit.php?table=$table&conditions=$conditions';</script>";
	}elseif ($modify=="delete")
	{
		q("delete from $table where id='$id'");
		echo "<script>document.location='sqledit.php?table=$table&conditions=$conditions';</script>";
	}else
	{

	}
	
d($db);
  require("footer.html");
?>