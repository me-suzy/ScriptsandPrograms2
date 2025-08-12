<?
$ti = microtime();
require ('fct.php'); 
require ('param.php');
if ($_GET[Schoixth] != "")
	$Schoixth = $_GET[Schoixth];
else
	die("Arghhhhh??");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 //EN">
<html>
<head>
<title>BSoftImageTheque</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="style.css" rel="stylesheet" type="text/css">
<script type="text/javascript">window.defaultStatus="BSoftImageTheque";</script>
<script language="JavaScript" type="text/javascript" src="fct.js"></script>
</head>

<body class='vignettepage' onload="javascript:tps_charge();">
<table align='center' width='95%'>
<tr>
<td>
	<div align='left'><a href="." class='Anoir10_underl'>Home</a></div>
	<br/>
	<?
	$nimapage = $PnimapageTH;
	$handle=opendir("image/");
	while (false !== ($filename = readdir($handle))) 
	{
    	if ($filename != "." && $filename != ".." && $filename != "temp_vig" && $filename != "index.php" && $filename != "intro_out.jpg" && $filename != "intro_over.jpg")	
    		$Tresu[] = chercheiptc($Schoixth,$filename);
	}
	clearstatcache();
	closedir($handle);

	foreach ($Tresu as $soustab)
	{
		if (count($soustab) >=1)
		{
			foreach ($soustab as $chem)
			{
				$Tima[] = $chem;
			}
		}
	}
	$nbimatotal = count($Tima);


	(empty($_GET[page]))?$page=1:$page=$_GET[page];
	if ($nbimatotal > $nimapage)
	{
		echo "\n<div align='left' class='Anoir10_notunderl' id='Dmenu_page'>Pages ";
		for ($i=1;$i<=ceil($nbimatotal/$nimapage);$i++)
		{
			if ($i==$page)
				echo $i," ";
			else
				echo "<a href='?Schoixth=$Schoixth&page=$i' class='Anoir10_underl'>$i</a> "; 
		}
		echo "</div>\n";
	}
	echo "<div>\n";
	
	for ($i=0;$i<$nimapage;$i++)
	{
		$num = $i + $nimapage*($page-1);
		if ($Tima[$num] != "")
		{
	   		$nom_ima = explode("/", $Tima[$num]);
	   		$affich_ima = "<img ".redimage($Tima[$num],"image/temp_vig/".$nom_ima[1]."_".$nom_ima[2],150,113)." border='0' alt='".$file[$num]."' ".$gris.">\n";
			
			echo "\n<div class='survol' ",$change_couleur_td," id='v_",$nom_ima[2],"'>\n<a href='photo.php?p=",$nom_ima[1],"&f=",$nom_ima[2],"' target='_blank'>$affich_ima</a>\n";
			echo "<br/>",$nom_ima[2],"<br/>",round (filesize($Tima[$num])/1024,0)," Ko";
			list($width, $height, $type, $attr) = getimagesize($Tima[$num]);
			echo " ",$width, "x",$height; 
			echo "\n</div>\n";
		}
	}
	echo "</div>\n";
?>
</tr>
</td>
<tr>
<td>
	<?
	echo "<div class='blocbas'>\n";
	if ($nbimatotal > $nimapage)
	{
		echo "\n\n<div align='left' class='Anoir10_notunderl' id='Dmenu_pagebas'>Pages ";
		for ($i=1;$i<=ceil($nbimatotal/$nimapage);$i++)
		{
			if ($i==$page)
				echo $i," ";
			else
				echo "<a href='?Schoixth=$Schoixth&page=$i' class='Anoir10_underl'>$i</a> "; 
		}
		echo "\n</div>\n";
	}
	$T = microtime()-$ti; $generation = "Calcul : ".abs(round($T,3))."s  ";
	?>
	<p class='pnormal'><input type='text' name='tps_charg' id='tps_charg' size='28' class="txt" value='<? echo $generation;?>'  readonly></p> </div>
</td>
</tr>
</table>

</body>
</html>