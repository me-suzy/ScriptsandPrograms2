<?
// Binary Clock
// script copyright(C) 2002 Andreas Tscharnuter
// questions? contact: psychodad@psychodad.at || http://www.psychodad.at/clock/
// free to use, copy and modify but leave comments untouched ;)
// just include this file where your binary clock should appear
// version 1.2   03 September 2003

// below you can change different settings
// and remember to drink m000re milk!


$size = 	"40";		//size of one square in pixel (height & width)
$hourdiff = 	"0";		//difference between server time and local time + or -
$bgon = 	"#cc0000";	//set color in hex for on (#cc0000 = darkred) ; check google.com for "hex color table" if you dont know how to use hex colors
$bgoff = 	"#000000";	//set color in hex for off (#000000 = black) ; check google.com for "hex color table" if you dont know how to use hex colors 
$enableclock =	"1";		//switch the "real clock" beneath binary clock on(1) or off(0)


/*************************************
nothing needs to be changed below here
*************************************/
$std = str_pad((date("H") + $hourdiff + 24) % 24,2,"0",STR_PAD_LEFT);
$min = date("i");
$sec = date("s");

if ($std > 24 || $std < 0) {
	die("<b><font color=\"#cc0000\">$std:$min no way... =)</font></b>");
}
$std1 = decbin(substr($std,0,1));
$std2 = decbin(substr($std,1,1));
$min1 = decbin(substr($min,0,1));
$min2 = decbin(substr($min,1,1));
$sec1 = decbin(substr($sec,0,1));
$sec2 = decbin(substr($sec,1,1));
function reihe($kette,$anfang,$ende) {		//background output
	global $size,$bgon,$bgoff;
	$pad = str_pad($kette,4,"0",STR_PAD_LEFT);
	if(substr($pad,$anfang,$ende)==1) {
		echo "<td width=\"$size\" height=\"$size\" bgcolor=\"$bgon\">&nbsp;</td>";
	} else {
		echo "<td width=\"$size\" height=\"$size\" bgcolor=\"$bgoff\">&nbsp;</td>";
	}
}
?>
<table cellpadding="0" cellspacing="1" border="0" bgcolor="#000000">
	<tr>
		<td>
<table cellpadding="0" cellspacing="1" border="0" bgcolor="#cccccc">
<tr>
	<td colspan="2" align="center"><i><font face="verdana" size="1">hour</font></i></td>
	<td colspan="2" align="center"><i><font face="verdana" size="1">min</font></i></td>
	<td colspan="2" align="center"><i><font face="verdana" size="1">sec</font></i></td>	
</tr>
<tr><?
	reihe($std1,0,1);
	reihe($std2,0,1);
	reihe($min1,0,1);
	reihe($min2,0,1);
	reihe($sec1,0,1);
	reihe($sec2,0,1);
?></tr>
<tr><?
	reihe($std1,1,1);
	reihe($std2,1,1);
	reihe($min1,1,1);
	reihe($min2,1,1);
	reihe($sec1,1,1);
	reihe($sec2,1,1);
?></tr>
<tr><?
	reihe($std1,2,1);
	reihe($std2,2,1);
	reihe($min1,2,1);
	reihe($min2,2,1);
	reihe($sec1,2,1);
	reihe($sec2,2,1);
?></tr>
<tr><?
	reihe($std1,3,1);
	reihe($std2,3,1);
	reihe($min1,3,1);
	reihe($min2,3,1);
	reihe($sec1,3,1);
	reihe($sec2,3,1);
?>
</tr>
<?
if ($enableclock == 1) {	//disables,enables bottom clock
?>
<tr>
	<td align="center" colspan="2"><font face="verdana" size="1"><? echo $std ?></font></td>
	<td align="center" colspan="2"><font face="verdana" size="1"><? echo $min ?></font></td>
	<td align="center" colspan="2"><font face="verdana" size="1"><? echo $sec ?></font></td>
</tr>
<? } ?>
</table>
		</td>
	</tr>
</table>