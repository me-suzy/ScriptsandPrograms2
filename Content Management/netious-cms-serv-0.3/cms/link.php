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
if (!isset($sf)) $sf="link";




/* Read the db values */

if (!isset($al))
	{$result=mysql_query("SELECT al FROM style WHERE active='1' limit 0,1");
	$row=mysql_fetch_row($result);
	$al=$row[0];
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
	<center><h2>Control panel - link properties.</h2></center><br /><br />
	<b>Choose just the basic link color - 'visited' will be made slightly darker, 'hover' slightly lighter automatically.</b>
	<br /><br />
	<center>
	<div id=\"formular\">
	<form name=\"headform\" action=\"linkResponse.php\" method='post'>
	<table width=\"95%\" cellpadding=\"5\" cellspacing=\"5\" border=\"1\">
	<tr>
	<td colspan=\"3\" align=\"center\">
	<input type=\"submit\" value=\"Save\" />
	</td>
	</tr>
	<tr>
	<td align=\"left\" width=\"70px\">
	Basic link color:
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
			$thisal="#$re$gr$bl";
			if (is_int($i/12) && $i!=0) echo "</tr><tr>";
			if ($al==$thisal) {echo "<td><input type=radio name=\"al\" value=\"$thisal\" checked=\"checked\" />
</td><td width=10 bgColor=\"$thisal\"></td>";} else {echo "<td><input type=radio name=\"al\" value=\"$thisal\""; 

if ($i==0) echo "checked=\"checked\"";

echo" />
</td><td width=10 bgColor=\"$thisal\"></td>";}
			$i++;
			}
		}
	}
if ($al=="#ffffff") {echo "<td><input type=radio name=\"al\" value=\"#ffffff\" checked=\"checked\" />
</td><td width=10 bgColor=\"#ffffff\"></td>";} else {echo "<td><input type=radio name=\"al\" value=\"#ffffff\" />
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
