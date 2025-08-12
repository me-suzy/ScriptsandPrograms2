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
if (!isset($sf)) $sf="head";

if (!isset($new_textlogo)) $new_textlogo=$textlogo;
if (!isset($newlogofile)) $newlogofile="";
if (!isset($ext_logo)) $ext_logo="";
if (!isset($newbgfile)) $newbgfile="";
if (!isset($ext_bg)) $ext_bg="";

/* Read the values */

if (!isset($hbgcol))
	{$result=mysql_query("SELECT hsize, hcol, hbgcol, hbgrep, hbgpos, hbgim FROM style WHERE active='1' limit 0,1");
	$row=mysql_fetch_row($result);
	$hsize=$row[0];
	$hcol=$row[1];
	$hbgcol=$row[2];
	$hbgrep=$row[3];
	$hbgpos=$row[4];
	$hbgim=$row[5];
	}

/* $hcol=str_replace("#","#",$hcol); */
/* $hbgcol=str_replace("#","#",$hbgcol); */


$logoimage=$_FILES['new_logo']['tmp_name'];
$logoimage_name=$_FILES['new_logo']['name'];
	if ($logoimage_name!="")
	{
	$extension=explode(".",$logoimage_name);
	$num_els=count($extension);
	$ext_logo=$extension[$num_els - 1];
	$newlogofile="../images/temp_logoimage";
	if (file_exists($newlogofile)) 
		{unlink($newlogofile);
		move_uploaded_file ($logoimage,$newlogofile);}
		else {move_uploaded_file ($logoimage,$newlogofile);}
	}




$bgimage=$_FILES['new_hbgim']['tmp_name'];
$bgimage_name=$_FILES['new_hbgim']['name'];
	if ($bgimage_name!="")
	{
	$extension=explode(".",$bgimage_name);
	$num_els=count($extension);
	$ext_bg=$extension[$num_els - 1];
	$newbgfile="../images/temp_bgimage";
	/* if (file_exists($newbgfile)) unlink($newbgfile); */
	move_uploaded_file ($bgimage,$newbgfile);
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
	   document.headform.action =\"headResponse.php\";
	  }
	  else
	  if(document.pressed == 'Update Preview')
	  {
	    document.headform.action =\"head.php\";
	  }
	  return true;
	}
	</SCRIPT>


	</td>
	<td>
	<center><h2>Control panel - edit the header style.</h2></center><br /><br />
	<b>Below the form you can see the preview of the header. After modifications you can either save the changes or update the preview first to see the effect. 	</b> <br /><br />
	<center>
	<div id=\"formular\">
	<form enctype=\"multipart/form-data\" name=\"headform\" onsubmit=\"return OnSubmitForm();\" method='post'>
	<table width=\"95%\" cellpadding=\"5\" cellspacing=\"5\" border=\"1\">
	<tr>
	<td colspan=\"3\" align=\"center\">
	<input type=\"submit\" value=\"Update Preview\" name=\"operation\" onclick=\"document.pressed=this.value\" /> or 
	<input type=\"submit\" value=\"Save\" name=\"operation\" onclick=\"document.pressed=this.value\" />
	</td>
	</tr>
	<tr>
	<td width=\"70px\">
	Logo (text, in case the image is not uploaded):
	</td>
	<td align=\"left\" width=\"500px\" colspan=\"2\">
	<input type=\"hidden\" name=\"textlogo\" value=\"$textlogo\" />
	<input type=\"hidden\" name=\"newlogofile\" value=\"$newlogofile\" />
	<input type=\"hidden\" name=\"newbgfile\" value=\"$newbgfile\" />
	<input type=\"hidden\" name=\"ext_logo\" value=\"$ext_logo\" />
	<input type=\"hidden\" name=\"ext_bg\" value=\"$ext_bg\" />
	<input type=\"hidden\" name=\"hbgim\" value=\"$hbgim\" />
	<input type=\"text\" name=\"new_textlogo\" value=\"$new_textlogo\" />
	</td>
	</tr>
	<tr>
	<td></td>
	<td width=\"70px\">
	Font size in % (used only when the logo is a text):
	</td>
	<td align=\"left\" width=\"420px\">
	<input type=\"text\" name=\"hsize\" value=\"$hsize\" /> %
	</td>
	</tr>
	<tr>
	<td></td>
	<td width=\"70px\">
	Font color (used only when the logo is a text):
	</td>
	<td align=\"left\">
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
			$thishcol="#$re$gr$bl";
			if (is_int($i/12) && $i!=0) echo "</tr><tr>";
			if ($hcol==$thishcol) {echo "<td><input type=\"radio\" name=\"hcol\" value=\"$thishcol\" checked=\"checked\" />
</td><td width=10 bgColor=\"$thishcol\"></td>";} else {echo "<td><input type=\"radio\" name=\"hcol\" value=\"$thishcol\" "; 

if ($i==0) echo "checked=\"checked\"";

echo" />
</td><td width=10 bgColor=\"$thishcol\"></td>";}
			$i++;
			}
		}
	}
if ($hcol=="#ffffff") {echo "<td><input type=radio name=\"hcol\" value=\"#ffffff\" checked />
</td><td width=10 bgColor=\"#ffffff\"></td>";} else {echo "<td><input type=radio name=\"hcol\" value=\"#ffffff\" />
</td><td width=10 bgColor=\"#ffffff\"></td>";}

echo "
</tr>
</table>
	</td>
	</tr>
	<tr>
	<td width=\"70px\">
	Logo (image):
	</td>
	<td align=\"left\" colspan=\"2\">
	<input type=\"file\" name=\"new_logo\" />
	</td>
	</tr>";
if ($logoname!="" || $newlogofile!="")
	{
	echo "
	<tr>
	<td width=\"70px\">
	Replace the graphical logo with the text:
	</td>
	<td align=\"left\" colspan=\"2\">
	<input type=\"checkbox\" name=\"replace\" ";
	if (!isset($replace)) echo "unchecked=\"unchecked\" />";
			else echo "checked=\"checked\" />";

	echo "
	</td>
	</tr>";}

echo "
	<tr>
	<td width=\"70px\">
	Background Color:
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
			$thishbgcol="#$re$gr$bl";
			if (is_int($i/12) && $i!=0) echo "</tr><tr>";
			if ($hbgcol==$thishbgcol) {echo "<td><input type=radio name=\"hbgcol\" value=\"$thishbgcol\" checked />
</td><td width=10 bgColor=\"$thishbgcol\"></td>";} else {echo "<td><input type=radio name=\"hbgcol\" value=\"$thishbgcol\""; 

if ($i==0) echo "checked=\"checked\"";

echo" />
</td><td width=10 bgColor=\"$thishbgcol\"></td>";}
			$i++;
			}
		}
	}
if ($hbgcol=="#ffffff") {echo "<td><input type=radio name=\"hbgcol\" value=\"#ffffff\" checked />
</td><td width=10 bgColor=\"#ffffff\"></td>";} else {echo "<td><input type=radio name=\"hbgcol\" value=\"#ffffff\" />
</td><td width=10 bgColor=\"#ffffff\"></td>";}


echo "
</tr>
</table>
	</td>
	</tr>
	<tr>
	<td width=\"70px\">
	Background Image (optional):
	</td>
	<td align=\"left\" colspan=\"2\">
	<input type=\"file\" name=\"new_hbgim\" />
	</td>
	</tr>
	<tr>
	<td></td>
	<td width=\"70px\">
	Background repeat:
	</td>
	<td align=\"left\">
	<select name=\"hbgrep\">";
	if ($hbgrep=="no-repeat") echo "<option value=\"no-repeat\" selected=\"selected\">Do not repeat the image</option>"; else echo "<option value=\"no-repeat\">Do not repeat the image</option>";
	if ($hbgrep=="repeat-x") echo "<option value=\"repeat-x\" selected=\"selected\">Repeat in horizontal (x) direction</option>"; else echo "<option value=\"repeat-x\">Repeat in horizontal (x) direction</option>";
	if ($hbgrep=="repeat-y") echo "<option value=\"repeat-y\" selected=\"selected\">Repeat in vertical (y) direction</option>"; else echo "<option value=\"repeat-y\">Repeat in vertical (y) direction</option>";
	if ($hbgrep=="repeat") echo "<option value=\"repeat\" selected=\"selected\">Repeat in both directions</option>"; else echo "<option value=\"repeat\">Repeat in both directions</option>";
	echo "
	</select>
	</td>
	</tr>
	<tr>
	<td></td>
	<td width=\"70px\">
	Background position (if not repeated):
	</td>
	<td align=\"left\">
	<select name=\"hbgpos\">";
	if ($hbgpos=="0% 0%") echo "<option value=\"0% 0%\" selected=\"selected\">Left - top</option>"; else echo "<option value=\"0% 0%\">Left - top</option>";
	if ($hbgpos=="0% 50%") echo "<option value=\"0% 50%\" selected=\"selected\">Left - middle</option>"; else echo "<option value=\"0% 50%\">Left - middle</option>";

	if ($hbgpos=="0% 100%") echo "<option value=\"0% 100%\" selected=\"selected\">Left - bottom</option>"; else echo "<option value=\"0% 100%\">Left - bottom</option>";

	if ($hbgpos=="50% 0%") echo "<option value=\"50% 0%\" selected=\"selected\">Center - top</option>"; else echo "<option value=\"50% 0%\">Center - top</option>";
	if ($hbgpos=="50% 50%") echo "<option value=\"50% 50%\" selected=\"selected\">Center - middle</option>"; else echo "<option value=\"50% 50%\">Center - middle</option>";

	if ($hbgpos=="50% 100%") echo "<option value=\"50% 100%\" selected=\"selected\">Center - bottom</option>"; else echo "<option value=\"50% 100%\">Center - bottom</option>";

	if ($hbgpos=="100% 0%") echo "<option value=\"100% 0%\" selected=\"selected\">Right - top</option>"; else echo "<option value=\"100% 0%\">Right - top</option>";

	if ($hbgpos=="100% 50%") echo "<option value=\"100% 50%\" selected=\"selected\">Right - middle</option>"; else echo "<option value=\"100% 50%\">Right - middle</option>";

	if ($hbgpos=="100% 100%") echo "<option value=\"100% 100%\" selected=\"selected\">Right - bottom</option>"; else echo "<option value=\"100% 100%\">Right - bottom</option>";
	echo "
	</select>
	</td>
	</tr>";
	

if ($newbgfile!="" || $hbgim!="")
	{
	echo "
	<tr>
	<td>
	Replace the backgroung image with the selected color:
	</td>
	<td align=\"left\" colspan=\"2\">
	<input type=\"checkbox\" name=\"replace_bg\" ";
	if (!isset($replace_bg)) echo "unchecked=\"unchecked\" />";
			else echo "checked=\"checked\" />";
	}

echo "
	<tr>
	<td colspan=\"3\" align=\"center\">
	<input type=\"submit\" value=\"Update Preview\" name=\"operation\" onclick=\"document.pressed=this.value\" /> or 
	<input type=\"submit\" value=\"Save\" name=\"operation\" onclick=\"document.pressed=this.value\" />
	</td>
	</tr>	
	</table>
	</form>
	</div>
	</center>
	</td>
	</tr>
	</table>

<table width=\"100%\">
<tr>
<td>
<center>
<b> Preview: </b><br />";

echo "
<div style=\"font-size:$hsize%; color:$hcol; background-color: $hbgcol;";
if ($newbgfile!="" && !isset($replace_bg)) {echo "background-image:url('$newbgfile'); background-repeat:$hbgrep; background-position: $hbgpos;";}
elseif ($hbgim!="" && !isset($replace_bg)) {$hbgim=str_replace("./","../",$hbgim);
			echo "background-image:url('$hbgim'); background-repeat:$hbgrep; background-position: $hbgpos;";}

echo "
\">
<table width=\"100%\">
<tr>
<td align=\"left\" valign=\"middle\" style=\"border:solid 1px black\">
<br />
&nbsp; &nbsp;
<a href=\"index.php\" title=\"Home page\">";
if ($newlogofile!="" && !isset($replace)) echo "<img src=\"$newlogofile\" border=\"0\" alt=\"Home page\" />";
elseif ($logoname!="" && !isset($replace)) echo "<img src=\"../images/$logoname\" border=\"0\" alt=\"Home page\" />";
else echo "<font style=\"color:$hcol\">$new_textlogo</font>";
echo "
</a>
<br /><br />
</td>
</tr>
</table>
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
