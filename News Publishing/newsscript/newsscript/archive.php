<?
include ("settings.php");
$data = file("news.dat");
for($i = 0;$i <= count ($data);$i++)
{
$del = explode ("<~>", $data[$i]);
	$overskrift = $del[0];
	$katvalg = $del[1];
	$bilde = $del[2];
	$innledning = $del[3];
	$hoveddel = $del[4];
	$bruker = $del[5];
	$navn = $del[6];
 	$tal = $del[7];
	$tal = ereg_replace("]","",$tal);

if($katvalg== "arkiv")
{	
echo "<a href=\"news/$tal.php\" target=\"";
echo $target;
echo "\">";
echo $overskrift;
echo "</a><br>\n";
}
}
?>