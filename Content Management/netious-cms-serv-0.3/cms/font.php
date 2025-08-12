<?
require("../db.php");
require("include.php");
DBinfo();

mysql_connect("$DBHost","$DBUser","$DBPass");
mysql_select_db("$DBName");



$SUID=f_ip2dec($REMOTE_ADDR);
if (!session_id($SUID))
session_start();

$username=$_SESSION['uname'];
$password=$_SESSION['pass'];

$result=mysql_query("SELECT AdminId FROM mycmsadmin WHERE username='$username' and password='".sha1($password)."'");
$row=mysql_fetch_row($result);
$num_rows = mysql_num_rows($result);
$id=$row[0];



if ($_SESSION['signed_in']!='indeed' || $num_rows!=1 || $id!=1){
Header( "Location: index.php?action=2");
}else{



if (!isset($f)) $f="style";
if (!isset($sf)) $sf="font";




/* Read the db values */

if (!isset($font))
	{$result=mysql_query("SELECT font, fsize, fcol FROM style WHERE active='1' limit 0,1");
	$row=mysql_fetch_row($result);
	$font=$row[0];
	$fsize=$row[1];
	$fcol=$row[2];
	}

commonheader();
bodybegin();
logobar($logoname,$textlogo);
mainmenu($f);


echo "
<br><br>
<table>
<tr>
	<td valign=\"top\" width=\"20%\">";

submenu($f,$sf);

echo "

	</td>
	<td>
	<center><h2>Control panel - dominant font properties.</h2></center><br /><br />
	<b>The font you define here will be the default font for displaying text. You can still specify a different one for the content edition in the Content Management.</b> <br /><br />
	<center>
	<div id=\"formular\">
	<form name=\"headform\" action=\"fontResponse.php\" method='post'>
	<table width=\"95%\" cellpadding=\"5\" cellspacing=\"5\" border=\"1\">
	<tr>
	<td colspan=\"3\" align=\"center\">
	<input type=\"submit\" value=\"Save\" />
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	Font family:
	</td>
	<td align=\"left\">
	<select name=\"font\">";
	if ($font=="helvetica, arial, sans-serif") echo "<option selected=\"selected\" value=\"helvetica, arial, sans-serif\">Helvetica, arial, sans-serif</option>"; else echo "<option value=\"helvetica, arial, sans-serif\">Helvetica, arial, sans-serif</option>";
	if ($font=="times, serif") echo "<option selected=\"selected\" value=\"times, serif\">Times, serif</option>"; else echo "<option value=\"times, serif\">Times, serif</option>";
	if ($font=="'Trebuchet MS', sans-serif") echo "<option selected=\"selected\" value=\"'Trebuchet MS', sans-serif\">Trebuchet MS, sans-serif</option>"; else echo "<option value=\"'Trebuchet MS', sans-serif\">Trebuchet MS, sans-serif</option>";

	echo "
	</select>
	</td>
	<td align=\"left\">
	<ol>
	<li style=\"font-family:helvetica, arial, sans-serif\">Helvetiva, Arial, Sans-Serif</li>
	<li style=\"font-family:times, serif\">Times, Serif</li>
	<li style=\"font-family:'Trebuchet MS', sans-serif\">Trebuchet MS, Sans-Serif</li>
	</ol>
	</td>
	</tr>
	<tr>
	<tr>
	<td align=\"left\">
	Font size:
	</td>
	<td align=\"left\">
	<select name=\"fsize\">";
	if ($fsize=="80") echo "<option selected=\"selected\" value=\"80\">80%</option>"; else echo "<option value=\"80\">80%</option>";
if ($fsize=="85") echo "<option selected=\"selected\" value=\"85\">85%</option>"; else echo "<option value=\"85\">85%</option>";
if ($fsize=="90") echo "<option selected=\"selected\" value=\"90\">90%</option>"; else echo "<option value=\"90\">90%</option>";
if ($fsize=="95") echo "<option selected=\"selected\" value=\"95\">95%</option>"; else echo "<option value=\"95\">95%</option>";
if ($fsize=="100") echo "<option selected=\"selected\" value=\"100\">100%</option>"; else echo "<option value=\"100\">100%</option>";
if ($fsize=="110") echo "<option selected=\"selected\" value=\"110\">110%</option>"; else echo "<option value=\"110\">110%</option>";
if ($fsize=="120") echo "<option selected=\"selected\" value=\"120\">120%</option>"; else echo "<option value=\"120\">120%</option>";
	echo "
	</select>
	</td>
	<td style=\"font-size:100%\" align=\"left\">
	<ol>
	<li style=\"font-size:80%\">80%</li>
	<li style=\"font-size:85%\">85%</li>
	<li style=\"font-size:90%\">90%</li>
	<li style=\"font-size:95%\">95%</li>
	<li style=\"font-size:100%\">100%</li>
	<li style=\"font-size:110%\">110%</li>
	<li style=\"font-size:120%\">120%</li>
	</ol>
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	Font color:
	</td>
	<td colspan=\"2\">
	<table>
	<tr>
	";
	$i=0;
for($r=0;$r<=255;$r+=63)
	{for ($g=0;$g<=255;$g+=63)
		{for ($b=0;$b<=255;$b+=63)
			{$re=dechex($r);
			$gr=dechex($g);
			$bl=dechex($b);
			if ($r<=15) $re="0$re";
			if ($g<=15) $gr="0$gr";
			if ($b<=15) $bl="0$bl";
			$thisfcol="#$re$gr$bl";
			if (is_int($i/12) && $i!=0) echo "</tr><tr>";
			if ($fcol==$thisfcol) {echo "<td><input type=radio name=\"fcol\" value=\"$thisfcol\" checked=\"checked\" />
</td><td width=10 bgColor=\"$thisfcol\"></td>";} else {echo "<td><input type=radio name=\"fcol\" value=\"$thisfcol\""; 

if ($i==0) echo "checked=\"checked\"";

echo" />
</td><td width=10 bgColor=\"$thisfcol\"></td>";}
			$i++;
			}
		}
	}
if ($fcol=="#ffffff") {echo "<td><input type=radio name=\"fcol\" value=\"#ffffff\" checked=\"checked\" />
</td><td width=10 bgColor=\"#ffffff\"></td>";} else {echo "<td><input type=radio name=\"fcol\" value=\"#ffffff\" />
</td><td width=10 bgColor=\"#ffffff\"></td>";}


echo "
</tr>
</table>
	</td>
	</tr>
	<tr>
	<td colspan=\"3\" align=\"center\">
	<input type=\"submit\" value=\"Save\" />
	</td>
	</tr>	
	</table>
	</form>
	</div>
	</center>
	<br /><br />
	</td>
	</tr>
	</table>
 ";

bodyend();
commonfooter();



}
?>
