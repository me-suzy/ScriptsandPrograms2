<?
require ('fct.php'); 
require ('param.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 //EN">
<html>
<head>
<title>BSoftImageTheque</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="style.css" rel="stylesheet" type="text/css">
<script type="text/javascript">window.defaultStatus="BSoftImageTheque";</script>
<script type="text/javascript" src="fct.js"></script>
</head>

<body class='vignettepage'>
<br/>
<table align='center' width='90%'>
<tr>
	<td align='<? (isset($th))?"right":"center";?>' class='titremenu'>
		<form method="get" action="diapo.php" name="choix"><?=$topic1?><select name="Schoix" class='txt'>
		<?
			$handle=opendir("image/");
			while (false !== ($filename = readdir($handle))) 
			{
		    	if ($filename != "." && $filename != ".." && $filename != "temp_vig" && $filename != "index.php" && $filename != "intro_out.jpg" && $filename != "intro_over.jpg")	echo "<option value='$filename' >$filename</option>\n";
			}
			clearstatcache();
			closedir($handle);
		?>
		</select>
		<input type="button" name="Bchoix" value="OK" onclick="javascript:form.submit(); return false;" class='bouton'/>
		 </form>
	</td>
<? if (isset($th)) { ?>
	<td align='left' class='titremenu'>
		<form method="get" action="diapoth.php" name="choix">&nbsp;&nbsp;&nbsp;<?=$topic2?><select name="Schoixth" class='txt'>
		<? foreach ($th as $t) {
			echo "<option value='",$t[0],"' >",$t[1],"</option>\n";
		} ?>
		</select>
		<input type="button" name="Bchoix" value="OK" onclick="javascript:form.submit(); return false;" class='bouton'/>
		</form>
	</td>
<? } ?>	
</tr>
<tr>
	<td colspan='2' align='center'>
		<p class='titreintro'><?=$textintrohaut?></p>
		<img src='image/intro_out.jpg' width='600' alt='' onmouseover="javascript:this.src='image/intro_over.jpg';" onmouseout="javascript:this.src='image/intro_out.jpg';">
	</td>
</tr>
<tr>
	<td colspan='2'>
		<p class='textintro'><?=$textintrobas ?></p>
	</td>
</tr>
<tr>
	<td colspan='2'>
		<p class='copy'>©<a href="http://www.bsoftco.com" target="_blank" class="copy">BSoftCo 2005</a> - <a href="http://www.bsoftco.com/php_bsoftimagetheque.php" target="_blank" class="copy">BSoftImageTheque v0.15</a></p>
	</td>
</tr>
</table>

</body>
</html>