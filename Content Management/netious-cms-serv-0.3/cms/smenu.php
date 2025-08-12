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
if (!isset($sf)) $sf="smenu";




/* Read the db values */

if (!isset($smbgcol))
	{$result=mysql_query("SELECT smbgcol, smborstyle, smborw, smborcol, smfcol FROM style WHERE active='1' limit 0,1");
	$row=mysql_fetch_row($result);
	$smbgcol=$row[0];
	$smborstyle=$row[1];
	$smborw=$row[2];
	$smborcol=$row[3];
	$smfcol=$row[4];
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
<!-- Dynamically switch the action of the form between delete/rename -->

	 <script language=\"JavaScript\" type=\"text/javascript\">
	function OnSubmitForm()
	{
	  if(document.pressed == 'Save')
	  {
	   document.headform.action =\"smenuResponse.php\";
	  }
	  else
	  if(document.pressed == 'Update Preview')
	  {
	    document.headform.action =\"smenu.php\";
	  }
	  return true;
	}
	</SCRIPT>


	</td>
	<td>
	<center><h2>Control panel - main menu properties.</h2></center><br /><br />
	<b>Next to the form you can see the preview of the menu. After modifications you can either save the changes or update the preview first to see the effect. </b> <br /><br />
	<center>
	<form name=\"headform\" onsubmit=\"return OnSubmitForm();\" method='post'>
<table>
<tr>
<td width=\"500\">
	<div id=\"formular\">
	<table width=\"100%\" cellpadding=\"5\" cellspacing=\"5\" border=\"1\">
	<tr>
	<td colspan=\"3\" align=\"center\">
	<input type=\"submit\" value=\"Update Preview\" name=\"operation\" onclick=\"document.pressed=this.value\" /> or 
	<input type=\"submit\" value=\"Save\" name=\"operation\" onclick=\"document.pressed=this.value\" />
	</td>
	</tr>
	<tr>
	<td colspan=\"3\" align=\"left\">
	<b>Background</b>
	</td>
	</tr>
	<tr>
	<td align=\"left\" width=\"70px\">
	Background color:
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
			$thissmbgcol="#$re$gr$bl";
			if (is_int($i/12) && $i!=0) echo "</tr><tr>";
			if ($smbgcol==$thissmbgcol) {echo "<td><input type=radio name=\"smbgcol\" value=\"$thissmbgcol\" checked=\"checked\" />
</td><td width=10 bgColor=\"$thissmbgcol\"></td>";} else {echo "<td><input type=radio name=\"smbgcol\" value=\"$thissmbgcol\""; 

if ($i==0) echo "checked=\"checked\"";

echo" />
</td><td width=10 bgColor=\"$thissmbgcol\"></td>";}
			$i++;
			}
		}
	}
if ($smbgcol=="#ffffff") {echo "<td><input type=radio name=\"smbgcol\" value=\"#ffffff\" checked=\"checked\" />
</td><td width=10 bgColor=\"#ffffff\"></td>";} else {echo "<td><input type=radio name=\"smbgcol\" value=\"#ffffff\" />
</td><td width=10 bgColor=\"#ffffff\"></td>";}


echo "
</tr>
</table>
	</td>
	</tr>
	<tr>
	<td align=\"left\" width=\"70px\">
	Border style:
	</td>
	<td colspan=\"2\" align=\"left\">
	<select name=\"smborstyle\">";
	if ($smborstyle=="solid") echo "<option selected=\"selected\" value=\"solid\">Solid</option>"; else echo "<option value=\"solid\">Solid</option>";
	if ($smborstyle=="dashed") echo "<option selected=\"selected\" value=\"dashed\">Dashed</option>"; else echo "<option value=\"dashed\">Dashed</option>";
	if ($smborstyle=="dotted") echo "<option selected=\"selected\" value=\"dotted\">Dotted</option>"; else echo "<option value=\"dotted\">Dotted</option>";
	
	
	echo "
	</select>
	</td>
	</tr>
	<tr>
	<td align=\"left\" width=\"70px\">
	Border width:
	</td>
	<td colspan=\"2\" align=\"left\">
	<input type=\"text\" size=\"4\" name=\"smborw\" value=\"$smborw\" />
	</tr>
	

	<tr>
	<td align=\"left\" width=\"70px\"> 
	Border color:
	</td>
	<td align=\"left\" colspan=\"2\">
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
			$thissmborcol="#$re$gr$bl";
			if (is_int($i/12) && $i!=0) echo "</tr><tr>";
			if ($smborcol==$thissmborcol) {echo "<td><input type=\"radio\" name=\"smborcol\" value=\"$thissmborcol\" checked=\"checked\" />
</td><td width=10 bgColor=\"$thissmborcol\"></td>";} else {echo "<td><input type=\"radio\" name=\"smborcol\" value=\"$thissmborcol\" "; 

if ($i==0) echo "checked=\"checked\"";

echo" />
</td><td width=10 bgColor=\"$thissmborcol\"></td>";}
			$i++;
			}
		}
	}
if ($smborcol=="#ffffff") {echo "<td><input type=radio name=\"smborcol\" value=\"#ffffff\" checked />
</td><td width=10 bgColor=\"#ffffff\"></td>";} else {echo "<td><input type=radio name=\"smborcol\" value=\"#ffffff\" />
</td><td width=10 bgColor=\"#ffffff\"></td>";}

echo "
</tr>
</table>
	</td>
	</tr>
	<tr>
	<td align=\"left\" width=\"70px\">
	Font (link) color:
	</td>
	<td align=\"left\" colspan=\"2\">
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
			$thissmfcol="#$re$gr$bl";
			if (is_int($i/12) && $i!=0) echo "</tr><tr>";
			if ($smfcol==$thissmfcol) {echo "<td><input type=\"radio\" name=\"smfcol\" value=\"$thissmfcol\" checked=\"checked\" />
</td><td width=10 bgColor=\"$thissmfcol\"></td>";} else {echo "<td><input type=\"radio\" name=\"smfcol\" value=\"$thissmfcol\" "; 

if ($i==0) echo "checked=\"checked\"";

echo" />
</td><td width=10 bgColor=\"$thissmfcol\"></td>";}
			$i++;
			}
		}
	}
if ($smfcol=="#ffffff") {echo "<td><input type=radio name=\"smfcol\" value=\"#ffffff\" checked />
</td><td width=10 bgColor=\"#ffffff\"></td>";} else {echo "<td><input type=radio name=\"smfcol\" value=\"#ffffff\" />
</td><td width=10 bgColor=\"#ffffff\"></td>";}

echo "
</tr>
</table>
	</td>
	</tr>
	<tr>
	<td colspan=\"3\" align=\"center\">
	<input type=\"submit\" value=\"Update Preview\" name=\"operation\" onclick=\"document.pressed=this.value\" /> or 
	<input type=\"submit\" value=\"Save\" name=\"operation\" onclick=\"document.pressed=this.value\" />
	</td>
	</tr>	
	</table>
	</div>
	</td>
	<td width=\"150\" valign=\"top\" align=\"center\">
	Preview:
	<table width=\"90%\">
	<tr>
	<td style=\"background-color:$smbgcol; border: $smborstyle $smborw $smborcol; color:$smfcol\">
	Element 1
	</td>
	</tr>
	<tr>
	<td style=\"background-color:$smbgcol; border: $smborstyle $smborw $smborcol; color:$smfcol\">
	<b>Element 2 active</b>
	</td>	
	</tr>
	<tr>
	<td  style=\"background-color:$smbgcol; border: $smborstyle $smborw $smborcol; color:$smfcol\">
	<u>Element 3 hover </u>
	</td>
	</tr>
	</table>
	</td>
	</tr>
	</table>
	</form>
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
