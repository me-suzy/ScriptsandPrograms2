<?

include "../tpl/clients_top.ihtml";

$db = c();

$table="members";
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

$id=$auth;
if (!$modify) $modify="edit";

if (!$id){

	}elseif ($modify=="edit")
	{
		echo "<blockquote><B>EDIT DETAILS</B><br><form action='editdetails.php'>";
		$r=q("select * from $table where id='$id'");
		$row=f($r);
		echo "<TABLE border=0 cellpadding=2 cellspacing=1>";
		for ($i=0;$i<$fieldnr;$i++)  if ((!$hide[$field[$i]])&&$field[$i]!="id")  echo "<TR><TD>".$fieldn[$field[$i]]."</TD><TD><INPUT size=45 name=var_$field[$i] value='".$row[$field[$i]]."'></TD></TR>";
 	                echo "<TR><TD><INPUT type=button value='Cancel' onclick=\"document.location='index.php';\"></TD><TD><INPUT type=reset value='Reset to default'><INPUT type='Submit' value='Apply changes'><INPUT type=hidden name='modify' value='apply'><INPUT type=hidden name='id' value='$id'></TD></TR>";
 	                echo "</TABLE></form></blockquote>";

	}elseif ($modify=="apply")
	{
	$qr="UPDATE $table SET";
	for ($i=0;$i<$fieldnr;$i++) if ((!$hide[$field[$i]])&&($field[$i]!="id")) $qr.=" $field[$i]='".${"var_".$field[$i]}."',";
	$qr.=" id='$id' WHERE id='$id' ";
	q($qr);
	echo "<script>document.location='editdetails.php?table=$table&conditions=$conditions';</script>";
	}else
	{

	}
	
d($db);
	include "../tpl/clients_bottom.ihtml";
?>