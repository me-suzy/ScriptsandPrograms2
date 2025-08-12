<?php 
Class Page {

	//global $config;
	var $bgcolor = "#ebebeb";
	var $maincolor = "#0F5F9F";
	var $maincolor2 = "blau";

	function Page() {
		$this->config = $GLOBALS["config"];
		$this->funcs = $GLOBALS["funcs"];
	}

	function pixel($breite=1, $hoehe=1) {
		echo "<img src=\"".$this->config->home."img/pixel.gif\" width=\"".$breite."\" height=\"".$hoehe."\" border=\"0\">";
	}

function kopf() {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>MySQL Commander</title>
	<link rel="stylesheet" href='./ressourcen/standard.css' type="text/css">
	<meta name="author" content="Oliver Kührig">
	<meta name="keywords" content="Tippliga, Tippen, Tippspiel, MySQL Commander, Forum, Euro, Euromünzen, Euroscheine, Wetter, Information, Rezepte, Rezeptdatenbank, Software, PHP">
	<meta name="language" content="german, deutsch">
	<script language="JavaScript" type="text/javascript">
		function action_log_popup(dbs) {
			window.open('actionlog.php?dbs=' + dbs, "ActionLogWindow","scrollbars=yes,width=780,height=540");
		}
		function popup(num) {
			url = "popup.php?id=" + num;
			helpwindow = window.open(url,"Fenster1","width=310,height=400,screenX=0,screenY=0");
			helpwindow.focus();
		}
	</script>
</head>

<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 bgcolor="#ebebeb" link="#9b0b0b" vlink="#9b0b0b" alink="#9b0b0b">
<?php 
$this->pixel(1, $this->config->breite_rand);
}

function page_start() {
	$breite = ($this->config->breite_rand*2) + $this->config->breite_menu +$this->config->breite_inhalt;
?>
<table width="<?php echo $breite;?>" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td rowspan="2000"><?php $this->pixel($this->config->breite_rand);?></td>
	<td><a name="to_top"></a><?php $this->pixel($this->config->breite_menu);?></td>
	<td rowspan="2000"><?php $this->pixel($this->config->breite_rand);?></td>
	<td><?php $this->pixel($this->config->breite_inhalt);?></td>
</tr>
<tr>
	<td valign="top">
<?php 
}

function page_pic() {
?>
		<table width="<?php echo $this->config->breite_menu;?>" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td><img src="<?php echo $this->config->home;?>img/commander.jpg" width="150" height="160" alt="" border="0"></td>
		</tr>
		</table>
<?php 
	$this->pixel(1, $this->config->breite_rand);
}

function page_email() {
?>
		<table width="<?php echo $this->config->breite_menu;?>" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td align="center" class="txtkl"><br><?php echo $this->funcs->text("Verbesserungsvorschläge, Kritik, Lob und Tadel sind sehr erwünscht!<br>Schaut Euch im ", "Suggestion for improvement, criticism, praise and bad mark are very welcome.<br>Visit my ");?><a target="_blank" class="txtfettkl" href="http://www.bitesser.de/community/forum/">Forum</a> <?php echo $this->funcs->text("um oder per Mail an:", "or mail to:");?> <a href="mailto:oliver@bitesser.de" class="txtfettkl">Oliver Kührig</a></td>
		</tr>
		</table>
<?php 
	$this->pixel(1, $this->config->breite_rand);
}

function build_menu($link, $text, $pixel, $backcolor="White", $textcolor="txtfettkl") {
?>
		<tr>
			<td bgcolor="#9b0b0b"><?php $this->pixel(1);?></td>
			<td bgcolor="<?php echo $backcolor;?>"><?php $this->pixel(15);?></td>
			<td height="20" bgcolor="<?php echo $backcolor;?>"><a href="<?php echo $this->config->home.$link;?>" class="<?php echo $textcolor;?>"><?php $this->pixel($pixel); echo $text;?></a></td>
			<td bgcolor="#b5b5b5"><?php $this->pixel();?></td>
		</tr>
		<tr><td colspan="4" bgcolor="#b5b5b5"><?php $this->pixel();?></td></tr>
<?php 
}

function page_menu($menu, $filename="") {
	global $HTTP_SERVER_VARS;
?>
		<table width="<?php echo $this->config->breite_menu;?>" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td colspan="2"><img src="<?php echo $this->config->home;?>img/pfeil_rot.gif" width="16" height="15" alt="" border="0"></td>
			<td bgcolor="black"><?php $this->pixel($this->config->breite_menu - 17);?></td>
			<td bgcolor="#0F5F9F"><?php $this->pixel();?></td>
		</tr>
		<?php 
		if ($filename=="") $filename = basename($HTTP_SERVER_VARS["PATH_TRANSLATED"]);
		$zeile = -1; $spalte = -1;
		for ($i=0; $i<count($menu); $i++) {
			for ($j=1; $j<count($menu[$i]); $j=$j+2) {
				if (ereg(trim(basename($menu[$i][$j])), trim($filename))) {
					$zeile = $i; $spalte = $j;
				}
			}
		}
		//echo "z: ".$zeile." s: ".$spalte."<br>";
		for ($i=0; $i<count($menu); $i++) {
			$backcolor = "White";
			$textcolor = "txtfettkl";
			if ($i==$zeile) {
				$backcolor = "#ebebeb";
				if ($spalte==1) $textcolor = "txtblaufettkl";
			}
			$this->build_menu($menu[$i][1], $menu[$i][0], 1,  $backcolor, $textcolor);  // Hauptmenue
			if ($i==$zeile) {
				for ($j=2; $j<count($menu[$i]); $j++) {
					$textcolor = "txtfettkl";
					if (($spalte-1)==$j) $textcolor = "txtblaufettkl";
					if (!($j%2)) $this->build_menu($menu[$i][$j+1], $menu[$i][$j], 6, "#ebebeb", $textcolor);  // Untermenue
				}
			}
		}?>
		</table>
<?php 
	$this->pixel(1,$this->config->breite_rand);
}

function page_mitte() {
	$this->pixel(1,15);?>
	</td>
	<td valign="top" width="<?php echo $this->config->breite_inhalt;?>">
<?php 
}

function page_stop() {
?>
	</td>
</tr>
</table>
<?php 
}

function fuss() {
	$breite = ($this->config->breite_rand*2) + $this->config->breite_menu +$this->config->breite_inhalt;
	$this->pixel(1,15);?>
<table width="<?php echo $breite;?>" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td height="1"><?php $this->pixel();?></td>
	<td class="txtkl"></td>
	<td align="right"><a href="#to_top"><img src="<?php echo $this->config->home;?>img/pfeil_<?php echo $this->config->maincolor2;?>_oben.gif" width="15" height="15" alt="" border="0"></a></td>
</tr>
<tr>
	<td height="1"><?php $this->pixel($this->config->breite_rand);?></td>
	<td bgcolor="Black"><?php $this->pixel(ceil((($breite-$this->config->breite_rand)/2)));?></td>
	<td bgcolor="Black"><?php $this->pixel(floor((($breite-$this->config->breite_rand)/2)));?></td>
</tr>
<tr><td colspan="3"><?php $this->pixel(1,3);?></td></tr>
<tr>
	<td height="1"><?php $this->pixel();?></td>
	<td><a class="txtblaukl" href="http://www.bitesser.de" target="_blank">www.bitesser.de</a></td>
	<td align="right" class="txtkl">&copy; 2000-2005 bitesser</td>
</tr>
</table>
<br>
</body>
</html>
<?php 
}

}
?>