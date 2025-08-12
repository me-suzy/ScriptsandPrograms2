<?
$ti = microtime();
require ('fct.php'); 
require ('param.php');
if ($_GET[Schoix] != "")
	$Schoix = $_GET[Schoix];
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
		$nimapage = $Pnimapage;
		$chemin1 = "image/".$Schoix;
		if (!is_dir($chemin1)) die("Arghhhhh!!??");
		$handle=opendir($chemin1);
		while (false !== ($filename = readdir($handle))) 
		{
		    if (verifima($filename))
		    {
	    		$file[] = $filename;
			}
		}
		$nbimatotal = count($file);
		
		(empty($_GET[page]))?$page=1:$page=$_GET[page];
		if ($nbimatotal > $nimapage)
		{
			echo "\n<div align='left' class='Anoir10_notunderl' id='Dmenu_page'>Pages ";
			for ($i=1;$i<=ceil($nbimatotal/$nimapage);$i++)
			{
				if ($i==$page)
					echo $i," ";
				else
					echo "<a href='?Schoix=$Schoix&page=$i' class='Anoir10_underl'>$i</a> "; 
			}
			echo "</div>\n";
		}
		echo "<div>\n";
		for ($i=0;$i<$nimapage;$i++)
		{
			$num = $i + $nimapage*($page-1);
			if ($file[$num] != "")
			{
		   		$affich_ima = "<img ".redimage($chemin1."/".$file[$num],"image/temp_vig/".$Schoix."_".$file[$num],150,113)." border='0' alt='".$file[$num]."' ".$gris.">\n";
				
				echo "\n<div class='survol' ",$change_couleur_td," id='v_",$file[$num],"'>\n<a href='photo.php?p=",$Schoix,"&f=",$file[$num],"' target='_blank'>$affich_ima</a>\n";
				echo "<br/>",$file[$num],"<br/>",round (filesize($chemin1."/".$file[$num])/1024,0)," Ko";
				list($width, $height, $type, $attr) = getimagesize($chemin1."/".$file[$num]);
				echo " ",$width, "x",$height; 
				echo "\n</div>\n";
			}
		}
		echo "</div>\n";
	clearstatcache();
	closedir($handle);
?>
</tr>
</td>
<tr>
<td>
	<?
	/*
	$size = getimagesize("image/Papa/DSCN0676.JPG", $info);
	if (isset($info["APP13"])) {
	  echo "ok";
	  $iptc = iptcparse($info["APP13"]);
	  var_dump($iptc);
	}
	*/
	echo "<div class='blocbas'>\n";
	if ($nbimatotal > $nimapage)
	{
		echo "\n\n<div align='left' class='Anoir10_notunderl' id='Dmenu_pagebas'>Pages ";
		for ($i=1;$i<=ceil($nbimatotal/$nimapage);$i++)
		{
			if ($i==$page)
				echo $i," ";
			else
				echo "<a href='?Schoix=$Schoix&page=$i' class='Anoir10_underl'>$i</a> "; 
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