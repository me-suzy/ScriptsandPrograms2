<?php 
Class Content {

	function Content() {
		$this->config = $GLOBALS["config"];
		$this->funcs = $GLOBALS["funcs"];
	}

	function pixel($breite=1, $hoehe=1) {
		echo "<img src=\"".$this->config->home."img/pixel.gif\" width=\"".$breite."\" height=\"".$hoehe."\" border=\"0\">";
	}

	function pixel_r($breite=1, $hoehe=1) {
		return "<img src=\"".$this->config->home."img/pixel.gif\" width=\"".$breite."\" height=\"".$hoehe."\" border=\"0\">";
	}

	function pfeil_link($link, $text, $color="blau") {
		return "<a href='".$link."'><img src='".$this->config->home."img/kl_pfeil_".$color.".gif' width='9' height='8' border='0' alt=''>&nbsp;".$text."</a>";;
	}

function topbox($title, $picture, $line1, $line2) {
	$font = "txtblaufettgr";
	if (strlen($title)>45) $font = "txtblaufett";
	
	$datefuncs = new DateFuncs();
?>
<table width="<?php echo $this->config->breite_inhalt;?>" border="0" cellspacing="0" cellpadding="0" bgcolor="#ebebeb">
<tr>
	<td colspan="2"><img src="<?php echo $this->config->home;?>img/pfeil_<?php echo $this->config->maincolor2;?>.gif" width="16" height="15" alt="" border="0" align="absmiddle"></td>
	<td bgcolor="Black"><?php $this->pixel($this->config->breite_inhalt-160-16);?></td>
	<td bgcolor="Black" width="149" class="txtgelbkl" align="right" height="15"><?php echo $datefuncs->get_date();?></td>
	<td bgcolor="Black"><?php $this->pixel(10);?></td>
	<td bgcolor="Black"><?php $this->pixel();?></td>
</tr>
<tr>
	<td bgcolor="<?php echo $this->config->maincolor;?>"><?php $this->pixel(1);?></td>
	<td bgcolor="#ebebeb" background="./img/bg2.gif"><?php $this->pixel(15);?></td>
	<td class="<?php echo $font;?>" height="30" bgcolor="#ebebeb" background="./img/bg2.gif"><?php echo $title;?></td>
	<td colspan="3" rowspan="5" bgcolor="#ebebeb"><img src="<?php echo $this->config->home.$picture;?>" width="160" height="92" border="0" alt=""></td>
</tr>
<tr><td colspan="3" bgcolor="#b5b5b5" height="1"><?php $this->pixel();?></td></tr>
<tr>
	<td bgcolor="<?php echo $this->config->maincolor;?>"><?php $this->pixel();?></td>
	<td bgcolor="#ebebeb"><?php $this->pixel();?></td>
	<td class="txtkl" height="30" bgcolor="#ebebeb"><?php echo $line1; $this->pixel();?></td>
</tr>
<tr><td colspan="3" bgcolor="#b5b5b5" height="1"><?php $this->pixel();?></td></tr>
<tr>
	<td bgcolor="<?php echo $this->config->maincolor;?>"><?php $this->pixel();?></td>
	<td bgcolor="White"><?php $this->pixel();?></td>
	<td class="txtfettkl" height="30" bgcolor="White"><?php echo $line2; $this->pixel();?></td>
</tr>
<tr><td colspan="6" bgcolor="#b5b5b5" height="1"><?php $this->pixel();?></td></tr>
</table>
<br>
<?php 
}

function html_black($text="&nbsp;") {
?>
	<table width="<?php echo $this->config->breite_inhalt;?>" border="0" cellspacing="0" cellpadding="0" bgcolor="#ebebeb">
	<tr>
		<td><img src="<?php echo $this->config->home;?>img/pfeil_<?php echo $this->config->maincolor2;?>.gif" width="16" height="15" alt="" border="0" align="absmiddle"></td>
		<td bgcolor="Black" width="<?php echo $this->config->breite_inhalt-27;?>" class="txtgelbkl" align="right" height="15" bgcolor="Black"><?php echo $text;?></td>
		<td bgcolor="Black"><?php $this->pixel(10);?></td>
		<td bgcolor="Black"><?php $this->pixel();?></td>
	</tr>
	</table>
<?php 
}

function html_black_send($text="&nbsp;", $function="") {
?>
	<table width="<?php echo $this->config->breite_inhalt;?>" border="0" cellspacing="0" cellpadding="0" bgcolor="#ebebeb">
	<tr bgcolor="Black">
		<td width="<?php echo $this->config->breite_inhalt;?>" class="txtgelbkl" align="right" height="15" bgcolor="Black"><a class="txtgelbkl" href="<?php echo $function;?>"><?php echo $text;?></a>&nbsp;&nbsp;&nbsp;<a href="<?php echo $function;?>"><img src="<?php echo $this->config->home;?>img/pfeil_<?php echo $this->config->maincolor2;?>_rechteck.gif" height="15" alt="" border="0" align="absmiddle"></a></td>
	</tr>
	</table>
<?php 
}

function html_headtext($text, $font="txtblaufett") {
?>
	<table background="./img/bg2.gif" width="<?php echo $this->config->breite_inhalt;?>" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td bgcolor="<?php echo $this->config->maincolor;?>"><?php $this->pixel();?></td>
		<td colspan="2"><?php $this->pixel(1,4);?></td>
		<td bgcolor="#b5b5b5"><?php $this->pixel();?></td>
	</tr>
	<tr>
		<td bgcolor="<?php echo $this->config->maincolor;?>"><?php $this->pixel();?></td>
		<td><?php $this->pixel(15);?></td>
		<td width="<?php echo $this->config->breite_inhalt-17;?>" class="<?php echo $font;?>"><?php echo $text;?></td>
		<td bgcolor="#b5b5b5"><?php $this->pixel();?></td>
	</tr>
	<tr>
		<td bgcolor="<?php echo $this->config->maincolor;?>"><?php $this->pixel();?></td>
		<td colspan="2"><?php $this->pixel(1,4);?></td>
		<td bgcolor="#b5b5b5"><?php $this->pixel();?></td>
	</tr>
	<tr><td colspan="4" bgcolor="#b5b5b5" height="1"><?php $this->pixel();?></td></tr>
	</table>
<?php 
}

function html_headtext_ori($text, $font="txtblaufett") {
?>
	<table width="<?php echo $this->config->breite_inhalt;?>" border="0" cellspacing="0" cellpadding="0" bgcolor="#ebebeb">
	<tr>
		<td bgcolor="<?php echo $this->config->maincolor;?>"><?php $this->pixel();?></td>
		<td colspan="2"><?php $this->pixel(1,4);?></td>
		<td bgcolor="#b5b5b5"><?php $this->pixel();?></td>
	</tr>
	<tr bgcolor="<?php echo $this->config->bgcolor;?>">
		<td bgcolor="<?php echo $this->config->maincolor;?>"><?php $this->pixel();?></td>
		<td><?php $this->pixel(15);?></td>
		<td width="<?php echo $this->config->breite_inhalt-17;?>" class="<?php echo $font;?>"><?php echo $text;?></td>
		<td bgcolor="#b5b5b5"><?php $this->pixel();?></td>
	</tr>
	<tr>
		<td bgcolor="<?php echo $this->config->maincolor;?>"><?php $this->pixel();?></td>
		<td colspan="2"><?php $this->pixel(1,4);?></td>
		<td bgcolor="#b5b5b5"><?php $this->pixel();?></td>
	</tr>
	<tr><td colspan="4" bgcolor="#b5b5b5" height="1"><?php $this->pixel();?></td></tr>
	</table>
<?php 
}

function html_text($text, $font="txtkl") {
	$zraum = 8;
?>
	<table width="<?php echo $this->config->breite_inhalt;?>" border="0" cellspacing="0" cellpadding="0" bgcolor="#ebebeb">
	<tr bgcolor="<?php echo $this->config->bgcolor;?>">
		<td bgcolor="<?php echo $this->config->maincolor;?>"><?php $this->pixel();?></td>
		<td colspan="3" height="<?php echo $zraum;?>"><?php $this->pixel();?></td>
		<td bgcolor="#b5b5b5"><?php $this->pixel();?></td>
	</tr>
	<tr bgcolor="<?php echo $this->config->bgcolor;?>">
		<td bgcolor="<?php echo $this->config->maincolor;?>"><?php $this->pixel();?></td>
		<td><?php $this->pixel(15);?></td>
		<td width="<?php echo $this->config->breite_inhalt-27;?>" class="<?php echo $font;?>"><?php echo $text;?></td>
		<td><?php $this->pixel(10);?></td>
		<td bgcolor="#b5b5b5"><?php $this->pixel();?></td>
	</tr>
	<tr bgcolor="<?php echo $this->config->bgcolor;?>">
		<td bgcolor="<?php echo $this->config->maincolor;?>"><?php $this->pixel();?></td>
		<td colspan="3" height="<?php echo $zraum;?>"><?php $this->pixel();?></td>
		<td bgcolor="#b5b5b5"><?php $this->pixel();?></td>
	</tr>
	<tr><td colspan="5" bgcolor="#b5b5b5" height="1"><?php $this->pixel();?></td></tr>
	</table>
<?php 
}

function html_link($link, $text, $target=0, $font="txtfettkl") {
	$zraum = 8;
?>
	<table width="<?php echo $this->config->breite_inhalt;?>" border="0" cellspacing="0" cellpadding="0" bgcolor="#ebebeb">
	<tr bgcolor="<?php echo $this->config->bgcolor;?>">
		<td bgcolor="<?php echo $this->config->maincolor;?>"><?php $this->pixel();?></td>
		<td colspan="4" height="<?php echo $zraum;?>"><?php $this->pixel();?></td>
		<td bgcolor="#b5b5b5"><?php $this->pixel();?></td>
	</tr>
	<tr bgcolor="<?php echo $this->config->bgcolor;?>">
		<td bgcolor="<?php echo $this->config->maincolor;?>"><?php $this->pixel();?></td>
		<td><?php $this->pixel(15);?></td>
		<td width="30"><a href="<?php echo $link;?>" <?php if ($target) echo "target=_blank";?>><img src="<?php echo $this->config->home;?>img/pfeil_<?php echo $this->config->maincolor2;?>_rund.gif" width="15" height="15" border="0" alt=""></a></td>
		<td width="<?php echo $this->config->breite_inhalt-57;?>" class="<?php echo $font;?>"><a href="<?php echo $link;?>" <?php if ($target) echo "target=_blank";?> class="<?php echo $font;?>"><?php echo $text;?></a></td>
		<td><?php $this->pixel(10);?></td>
		<td bgcolor="#b5b5b5"><?php $this->pixel();?></td>
	</tr>
	<tr bgcolor="<?php echo $this->config->bgcolor;?>">
		<td bgcolor="<?php echo $this->config->maincolor;?>"><?php $this->pixel();?></td>
		<td colspan="4" height="<?php echo $zraum;?>"><?php $this->pixel();?></td>
		<td bgcolor="#b5b5b5"><?php $this->pixel();?></td>
	</tr>
	<tr><td colspan="6" bgcolor="#b5b5b5" height="1"><?php $this->pixel();?></td></tr>
	</table>
<?php 
}

function html_link_zurueck($link, $text, $target=0, $font="txtfettkl") {
	$zraum = 8;
?>
	<table width="<?php echo $this->config->breite_inhalt;?>" border="0" cellspacing="0" cellpadding="0" bgcolor="#ebebeb">
	<tr bgcolor="<?php echo $this->config->bgcolor;?>">
		<td bgcolor="<?php echo $this->config->maincolor;?>"><?php $this->pixel();?></td>
		<td colspan="4" height="<?php echo $zraum;?>"><?php $this->pixel();?></td>
		<td bgcolor="#b5b5b5"><?php $this->pixel();?></td>
	</tr>
	<tr bgcolor="<?php echo $this->config->bgcolor;?>">
		<td bgcolor="<?php echo $this->config->maincolor;?>"><?php $this->pixel();?></td>
		<td><?php $this->pixel(15);?></td>
		<td width="<?php echo $this->config->breite_inhalt-57;?>" class="<?php echo $font;?>" align="right"><a href="<?php echo $link;?>" <?php if ($target) echo "target=_blank";?> class="<?php echo $font;?>"><?php echo $text;?></a></td>
		<td width="30" align="right"><a href="<?php echo $link;?>" <?php if ($target) echo "target=_blank";?>><img src="<?php echo $this->config->home;?>img/pfeil_<?php echo $this->config->maincolor2;?>_rund_li.gif" width="15" height="15" border="0" alt=""></a></td>
		<td><?php $this->pixel(10);?></td>
		<td bgcolor="#b5b5b5"><?php $this->pixel();?></td>
	</tr>
	<tr bgcolor="<?php echo $this->config->bgcolor;?>">
		<td bgcolor="<?php echo $this->config->maincolor;?>"><?php $this->pixel();?></td>
		<td colspan="4" height="<?php echo $zraum;?>"><?php $this->pixel();?></td>
		<td bgcolor="#b5b5b5"><?php $this->pixel();?></td>
	</tr>
	<tr><td colspan="6" bgcolor="#b5b5b5" height="1"><?php $this->pixel();?></td></tr>
	</table>
<?php 
}

function html_punkt_text($bild, $bildbreite, $text, $font="txtkl") {
	$zraum = 8;
?>
	<table width="<?php echo $this->config->breite_inhalt;?>" border="0" cellspacing="0" cellpadding="0" bgcolor="#ebebeb">
	<tr bgcolor="<?php echo $this->config->bgcolor;?>">
		<td bgcolor="<?php echo $this->config->maincolor;?>"><?php $this->pixel();?></td>
		<td colspan="4" height="<?php echo $zraum;?>"><?php $this->pixel();?></td>
		<td bgcolor="#b5b5b5"><?php $this->pixel();?></td>
	</tr>
	<tr bgcolor="<?php echo $this->config->bgcolor;?>">
		<td bgcolor="<?php echo $this->config->maincolor;?>"><?php $this->pixel();?></td>
		<td><?php $this->pixel(15);?></td>
		<td width="<?php echo $bildbreite;?>" valign="top"><img src="<?php echo $bild?>" border="0" alt="" align="absmiddle"></td>
		<td width="<?php echo $this->config->breite_inhalt-27-$bildbreite;?>" class="<?php echo $font?>"><?php echo $text;?></td>
		<td><?php $this->pixel(10);?></td>
		<td bgcolor="#b5b5b5"><?php $this->pixel();?></td>
	</tr>
	<tr bgcolor="<?php echo $this->config->bgcolor;?>">
		<td bgcolor="<?php echo $this->config->maincolor;?>"><?php $this->pixel();?></td>
		<td colspan="4" height="<?php echo $zraum;?>"><?php $this->pixel();?></td>
		<td bgcolor="#b5b5b5"><?php $this->pixel();?></td>
	</tr>
	<tr><td colspan="6" bgcolor="#b5b5b5" height="1"><?php $this->pixel();?></td></tr>
	</table>
<?php 
}

function html_box($breite, $text_l, $text_r, $font="txtkl") {
?>
	<table width="<?php echo $this->config->breite_inhalt;?>" border="0" cellspacing="0" cellpadding="0" bgcolor="#ebebeb">
	<tr bgcolor="#b5b5b5">
		<td bgcolor="<?php echo $this->config->maincolor;?>"><?php $this->pixel();?></td>
		<td bgcolor="#ebebeb"><?php $this->pixel(10);?></td>
		<td width="<?php echo $breite;?>" align="right" bgcolor="#ebebeb" class="txtkl" valign="top"><?php $this->pixel(1,5); echo "<br>".$text_l."<br>"; $this->pixel(1,5);?></td>
		<td bgcolor="#ebebeb"><?php $this->pixel(10);?></td>
		<td><?php $this->pixel();?></td>
		<td bgcolor="White"><?php $this->pixel(10);?></td>
		<td width="<?php echo $this->config->breite_inhalt-$breite-43;?>" bgcolor="White" valign="top"><?php $this->pixel(1,5); echo "<br>".$text_r."<br>"; $this->pixel(1,5);?></td>
		<td bgcolor="White"><?php $this->pixel(10);?></td>
		<td><?php $this->pixel();?></td>
	</tr>
	<tr bgcolor="#b5b5b5"><td colspan="9"><?php $this->pixel();?></td></tr>
	</table>
<?php 
}

function html_br() {
	echo "<br>\n";
}

function html_text_ohne_rahmen($text, $font="txtkl", $color="") {
	if ($color<>"") {
		$font1 = "<font color=\"".$color."\">";
		$font2 = "</font>";
	} else {
		$font1 = "";
		$font2 = "";
	}
?>
	<table width="<?php echo $this->config->breite_inhalt-10;?>" border="0" cellspacing="0" cellpadding="0" bgcolor="#ebebeb">
	<tr>
		<td width="16"><spacer type=block width=10></td>
		<td width="<?php echo $this->config->breite_inhalt-26;?>"><font class="<?php echo $font;?>"><?php echo $font1.$text.$font2;?></font></td>
	</tr>	
	</table>
<?php 
}

function html_link_ohne_rahmen($link, $text, $font="txtkl", $color="") {
	unset($font1); unset($font2);
	if ($color<>"") {
		$font1 = "<font color=\"".$color."\">";
		$font2 = "</font>";
	}
?>
	<table width="<?php echo $this->config->breite_inhalt-10;?>" border="0" cellspacing="0" cellpadding="0" bgcolor="#ebebeb">
	<tr>
		<td width="16"><spacer type=block width=10></td>
		<td width="<?php echo $this->config->breite_inhalt-26;?>"><a href="<?php echo $link;?>"><font class="<?php echo $font;?>"><?php echo $font1.$text.$font2;?></font></a></td>
	</tr>	
	</table>
<?php 
}

function html_weiter($link, $text, $link2, $text2, $font="txtfettkl") {
?>
	<table width="<?php echo $this->config->breite_inhalt;?>" border="0" cellspacing="0" cellpadding="0" bgcolor="#ebebeb">
	<tr bgcolor="<?php echo $this->config->bgcolor;?>">
		<td bgcolor="<?php echo $this->config->maincolor;?>"><?php $this->pixel();?></td>
		<td><?php $this->pixel(15);?></td>
		<td width="30"><?php if ($link) {?><a href="<?php echo $link;?>"><img src="<?php echo $this->config->home;?>img/pfeil_<?php echo $this->config->maincolor2;?>_rund_li.gif" width="15" height="15" border="0" alt=""></a><?php } else echo "&nbsp;";?></td>
		<td width="<?php echo ceil(($this->config->breite_inhalt-87)/2)?>" class="<?php echo $font;?>" height="30"><?php if ($link) {?><a href="<?php echo $link;?>" class="<?php echo $font;?>"><?php echo $text;?></a><?php } else echo "&nbsp;";?></td>
		<td width="<?php echo floor(($this->config->breite_inhalt-87)/2)?>" align="right" class="<?php echo $font;?>" height="30"><?php if ($link2) {?><a href="<?php echo $link2;?>" class="<?php echo $font;?>"><?php echo $text2;?></a><?php } else echo "&nbsp;";?></td>
		<td width="30" align="right"><?php if ($link2) {?><a href="<?php echo $link2;?>"><img src="<?php echo $this->config->home;?>img/pfeil_<?php echo $this->config->maincolor2;?>_rund.gif" width="15" height="15" border="0" alt=""></a><?php } else echo "&nbsp;";?></td>
		<td><?php $this->pixel(10);?></td>
		<td bgcolor="#b5b5b5"><?php $this->pixel();?></td>
	</tr>
	<tr><td colspan="8" bgcolor="#b5b5b5" height="1"><?php $this->pixel();?></td></tr>
	</table>
<?php 
}

}
?>