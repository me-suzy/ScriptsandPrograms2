<?php
include("header.inc.php");

                        $user = $userid;
                        $zeit = time ();
                        $nichtmehrgueltig = $zeit-19;
                        $query = "DELETE FROM demo_a_klicksp WHERE timefeld <= ".$nichtmehrgueltig;
                        mysql_query($query);
                        $query = "SELECT * FROM demo_a_klicksp WHERE user = '".$user."'";
                        $result = mysql_query($query);
                        $rows = mysql_num_rows($result);
                        if ($rows >= 1)
                                {
require('./prependb.inc.php');
$data = viewpage()
;$url=$data[url];
$id=$data[id];

}
                        else
                                {
require('./prepend.inc.php');
$data = viewpage()
;$url=$data[url];
$id=$data[id];
$gamble=autogamble();

$query = "INSERT INTO demo_a_klicksp VALUES (\"$user\", $zeit)";
                                        mysql_query($query);
                                }



$result2 = mysql_query("SELECT points FROM `demo_a_gamble`");
$row2 = mysql_fetch_row($result2);
$poi = $row2[0];

$user = mysql_num_rows(mysql_query("SELECT user FROM `demo_a_klicksp`"));
$surfer = $user + 0;

$result22 = mysql_query("SELECT points FROM `demo_a_accounts` WHERE `id` = '$userid'");
$row22 = mysql_fetch_row($result22);
$num22 = $row22[0];

$pun = bcmul($num22,10,1);
$punktz = $pun / 10;
?>

<html>
<head>
        <title>Surfbar</title>

<style type="text/css">
SELECT, option, textarea, input {   FONT-FAMILY:verdana,arial;color:#000000; FONT-SIZE: 9px; background-color:#eeeeee  }
a:link,a:visited,a:active {text-decoration:none; color:#080076; font-weight:plain;}
a:hover {text-decoration: underline; color:#99A7F6; font-weight: plain;}
.bottom { vertical-align: bottom }
.top { vertical-align: top }
.poster { FONT-SIZE: 9px }
</style>
<body bgcolor=#FFFFFF topmargin=2 leftmargin=2 rightmargin=2 bottommargin=2 marginwidth=2 marginheight=2>

<script type="text/javascript">
<!--
var counttime=<?php echo $showup_time; ?>;
function starttimer()
{parent.bottom.location.href="<?php echo $url; ?>";
timeleft();
}
function timeleft()
{
document.countdown.counter.value=counttime;
if(counttime == 0)
{
location.href="frame_top.php?userid=<?php echo $userid; ?>";
}else{
counttime = counttime - 1;
reloadtimer=setTimeout("timeleft()", 1000);
}
}
function stop()
{
clearTimeout(reloadtimer);
}
function play()
{
clearTimeout(reloadtimer)
reloadtimer=setTimeout("timeleft()", 1000);
}
//-->
</script>

<table width=95% height=50 align="center" cellspacing="1" cellpadding="2"  border="0" bgcolor=#29395A>
        <tr>
                <td bgcolor=#DDDDDD valign=top>

                        <table border=0 cellspacing=0 cellpadding=0 width=100%>
                                <tr>
                                        <td>

                                                <table border=0 width=100% height=100%>

                                                        <tr>
                                                                <td valign=top>
                                                                <font face=verdana color=#333333 size=1>
                                                                <b><a href="javascript:stop()"><IMG SRC="bilder/stop.gif" HEIGHT="20" WIDTH="20" border="0" ALT="ZÄHLER ANHALTEN">&nbsp;&nbsp;
<a href="javascript:play()"><IMG SRC="bilder/weiter.gif" HEIGHT="20" WIDTH="20" border="0" ALT="RESTART COUNTER"></a></b>&nbsp;&nbsp;<?php if($id){ ?><a href="./report.php?id=<?php echo $id; ?>&&userid=<?php echo $userid; ?>"><IMG SRC="bilder/melden.gif" HEIGHT="20" WIDTH="20" border="0" ALT="ANNOUNCE CHEATER"></a><?php } ?>
                                                                <b><a href="<?php echo $url_index; ?>" target="_blank"><IMG SRC="bilder/pause.gif" HEIGHT="20" WIDTH="20" border="0" ALT="BACK TO MAINPAGE"></a></b>&nbsp;&nbsp;<?php if($gamble){ ?><a href="./gamble.php?sid=<?php echo $gamble; ?>&userid=<?php echo $userid; ?>"><IMG SRC="bilder/smile.gif" HEIGHT="15" WIDTH="15" border="0" ALT="GET BONUS POINTS"></a><?php } ?></td>
                                                                </font>
                                                                </td>

                                                                <body onload="starttimer()">
                                                                <form name="countdown">

                                                                <td valign=top>

                                                                        <font face=verdana color=#333333 size=1>
                                                                        <img src="spacer.gif" width=10 height=1>
                                                                        Z&auml;hler:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input name="counter" size=1 value=<?php echo $showup_time; ?>><br>
                                                                        &nbsp;&nbsp;&nbsp;Points:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo "$punktz"; ?><br>
                                                                        &nbsp;&nbsp;&nbsp;In Jackpot:&nbsp;&nbsp;<? if ($poi > 0){ echo"$poi"; } else { echo"$gamble_points"; }?><br>
                                                                        &nbsp;&nbsp;&nbsp;User Online: <?php echo $surfer; ?>
                                                                </td>
                                                                </form>
                                                        </tr>

                                                </table>

                                        </td><td width=468 valign=top><?php banner_view(); ?></td>
                                </tr>
                        </table>
                </td>


        </tr>
</table>
</head>
</body>

</html>