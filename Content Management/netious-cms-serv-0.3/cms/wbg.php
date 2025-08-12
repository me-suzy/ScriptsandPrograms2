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
if (!isset($sf)) $sf="wbg";

if (!isset($new_width)) $new_width=$width;
if (!isset($new_model)) $new_model=$model;
if (!isset($new_bodyposition)) $new_bodyposition=$bodyposition;
if (!isset($new_title)) $new_title=$title;
if (!isset($new_keywords)) $new_keywords=$keywords;
if (!isset($new_description)) $new_description=$description;


if (!isset($newbgfile)) $newbgfile="";
if (!isset($ext_bg)) $ext_bg="";

/* Read the db values */

if (!isset($bgcol))
	{$result=mysql_query("SELECT bgcol, bgim, bgrep, bgpos FROM style WHERE active='1' limit 0,1");
	$row=mysql_fetch_row($result);
	$bgcol=$row[0];
	$bgim=$row[1];
	$bgrep=$row[2];
	$bgpos=$row[3];
	}

$bgimage=$_FILES['new_bgim']['tmp_name'];
$bgimage_name=$_FILES['new_bgim']['name'];
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
	   document.headform.action =\"wbgResponse.php\";
	  }
	  else
	  if(document.pressed == 'Update Preview')
	  {
	    document.headform.action =\"wbg.php\";
	  }
	  return true;
	}
	</SCRIPT>


	</td>
	<td>
	<center><h2>Control panel - general service properties.</h2></center><br /><br />
	<b>Below the form you can see the preview of the wide background. After modifications you can either save the changes or update the preview first to see the effect. 	</b> <br /><br />
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
	<td colspan=\"3\" align=\"left\">
	<b>General service properties</b>
	<input type=\"hidden\" name=\"newbgfile\" value=\"$newbgfile\" />
	<input type=\"hidden\" name=\"ext_bg\" value=\"$ext_bg\" />
	<input type=\"hidden\" name=\"bgim\" value=\"$bgim\" />
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	Title:
	</td>
	<td align=\"left\" colspan=\"2\">
	<input type=\"text\" name=\"new_title\" value=\"$new_title\" size=\"50\" />
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	Keywords:
	</td>
	<td align=\"left\" colspan=\"2\">
	<textarea cols=\"50\" rows=\"3\" name=\"new_keywords\">$new_keywords</textarea>
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	Description:
	</td>
	<td align=\"left\" colspan=\"2\">
	<textarea cols=\"50\" rows=\"3\" name=\"new_description\">$new_description</textarea>
	</td>
	</tr>
	<tr>
	<td colspan=\"3\" align=\"left\">
	<b>Document width, position, Menu model</b>
	</td>
	</tr>
<tr>
	<td align=\"left\">
	Width (the width of the document, not the wide background!):
	</td>
	<td align=\"left\" colspan=\"2\">
	<select name=\"new_width\">";
	if ($new_width=="100%") echo "<option selected=\"selected\" value=\"100%\">Entire available space (100%)</option>";
				else echo "<option value=\"100%\">Entire available space (100%)</option>";
	if ($new_width=="800px") echo "<option selected=\"selected\" value=\"800px\">Standard, adjusted to low-resolution displays (800px)</option>";
				else echo "<option value=\"800px\">Standard, adjusted to low-resolution displays (800px)</option>";
	if ($new_width=="600px") echo "<option selected=\"selected\" value=\"600px\">Narrow page - good for pages with a small number of elements (600px)</option>";
				else echo "<option value=\"600px\">Narrow page - good for pages with a small number of elements (600px)</option>";
	echo "
	</select>
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	Document position:
	</td>
	<td align=\"left\" colspan=\"2\">
	<select name=\"new_bodyposition\">";
	if ($new_bodyposition=="left") echo "<option selected=\"selected\" value=\"left\">Left - the document starts at the left edge of the browser window</option>"; else echo "<option value=\"left\">Left - the document starts at the left edge of the browser window</option>";
	if ($new_bodyposition=="center") echo "<option selected=\"selected\" value=\"center\">Center - the document is centered.</option>"; else echo "<option value=\"center\">Center - the document is centered.</option>";
	echo "
	</select>
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	Menu model (horizontal/vertical):
	</td>
	<td align=\"left\" colspan=\"2\">
	<select name=\"new_model\">";
	if ($new_model=="hh") echo "<option selected=\"selected\" value=\"hh\">Both menus horizontal (submenu under main menu)</option>"; else echo "<option value=\"hh\">Both menus horizontal (submenu under main menu)</option>";
	if ($new_model=="hv") echo "<option selected=\"selected\" value=\"hv\">Main menu horizontal, submenu vertical on the left</option>"; else echo "<option value=\"hv\">Main menu horizontal, submenu vertical on the left</option>";
	if ($new_model=="vv") echo "<option selected=\"selected\" value=\"vv\">Both menus vertical (submenu under main menu on the left)</option>"; else echo "<option value=\"vv\">Both menus vertical (submenu under main menu on the left)</option>";
	echo "
	</select>
	</td>
	</tr>
	<tr>
	<td align=\"left\">
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
			$thisbgcol="#$re$gr$bl";
			if (is_int($i/12) && $i!=0) echo "</tr><tr>";
			if ($bgcol==$thisbgcol) {echo "<td><input type=radio name=\"bgcol\" value=\"$thisbgcol\" checked=\"checked\" />
</td><td width=10 bgColor=\"$thisbgcol\"></td>";} else {echo "<td><input type=radio name=\"bgcol\" value=\"$thisbgcol\""; 

if ($i==0) echo "checked=\"checked\"";

echo" />
</td><td width=10 bgColor=\"$thisbgcol\"></td>";}
			$i++;
			}
		}
	}
if ($bgcol=="#ffffff") {echo "<td><input type=radio name=\"bgcol\" value=\"#ffffff\" checked=\"checked\" />
</td><td width=10 bgColor=\"#ffffff\"></td>";} else {echo "<td><input type=radio name=\"bgcol\" value=\"#ffffff\" />
</td><td width=10 bgColor=\"#ffffff\"></td>";}


echo "
</tr>
</table>
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	Background Image (optional):
	</td>
	<td align=\"left\" colspan=\"2\">
	<input type=\"file\" name=\"new_bgim\" />
	</td>
	</tr>
	<tr>
	<td></td>
	<td align=\"left\">
	Background repeat:
	</td>
	<td align=\"left\">
	<select name=\"bgrep\">";
	if ($bgrep=="no-repeat") echo "<option value=\"no-repeat\" selected=\"selected\">Do not repeat the image</option>"; else echo "<option value=\"no-repeat\">Do not repeat the image</option>";
	if ($bgrep=="repeat-x") echo "<option value=\"repeat-x\" selected=\"selected\">Repeat in horizontal (x) direction</option>"; else echo "<option value=\"repeat-x\">Repeat in horizontal (x) direction</option>";
	if ($bgrep=="repeat-y") echo "<option value=\"repeat-y\" selected=\"selected\">Repeat in vertical (y) direction</option>"; else echo "<option value=\"repeat-y\">Repeat in vertical (y) direction</option>";
	if ($bgrep=="repeat") echo "<option value=\"repeat\" selected=\"selected\">Repeat in both directions</option>"; else echo "<option value=\"repeat\">Repeat in both directions</option>";
	echo "
	</select>
	</td>
	</tr>
	<tr>
	<td></td>
	<td align=\"left\">
	Background position (if not repeated):
	</td>
	<td align=\"left\">
	<select name=\"bgpos\">";
	if ($bgpos=="0% 0%") echo "<option value=\"0% 0%\" selected=\"selected\">Left - top</option>"; else echo "<option value=\"0% 0%\">Left - top</option>";
	if ($bgpos=="0% 50%") echo "<option value=\"0% 50%\" selected=\"selected\">Left - middle</option>"; else echo "<option value=\"0% 50%\">Left - middle</option>";

	if ($bgpos=="0% 100%") echo "<option value=\"0% 100%\" selected=\"selected\">Left - bottom</option>"; else echo "<option value=\"0% 100%\">Left - bottom</option>";

	if ($bgpos=="50% 0%") echo "<option value=\"50% 0%\" selected=\"selected\">Center - top</option>"; else echo "<option value=\"50% 0%\">Center - top</option>";
	if ($bgpos=="50% 50%") echo "<option value=\"50% 50%\" selected=\"selected\">Center - middle</option>"; else echo "<option value=\"50% 50%\">Center - middle</option>";

	if ($bgpos=="50% 100%") echo "<option value=\"50% 100%\" selected=\"selected\">Center - bottom</option>"; else echo "<option value=\"50% 100%\">Center - bottom</option>";

	if ($bgpos=="100% 0%") echo "<option value=\"100% 0%\" selected=\"selected\">Right - top</option>"; else echo "<option value=\"100% 0%\">Right - top</option>";

	if ($bgpos=="100% 50%") echo "<option value=\"100% 50%\" selected=\"selected\">Right - middle</option>"; else echo "<option value=\"100% 50%\">Right - middle</option>";

	if ($bgpos=="100% 100%") echo "<option value=\"100% 100%\" selected=\"selected\">Right - bottom</option>"; else echo "<option value=\"100% 100%\">Right - bottom</option>";
	echo "
	</select>
	</td>
	</tr>";
	

if ($newbgfile!="" || $bgim!="")
	{
	echo "
	<tr>
	<td align=\"left\">
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
<div style=\"background-color: $bgcol;";
if ($newbgfile!="" && !isset($replace_bg)) {echo "background-image:url('$newbgfile'); background-repeat:$bgrep; background-position: $bgpos;";}
elseif ($bgim!="" && !isset($replace_bg)) {$bgim=str_replace("./","../",$bgim);
			echo "background-image:url('$bgim'); background-repeat:$bgrep; background-position: $bgpos;";}

echo "
\">
<table width=\"100%\">
<tr>
<td align=\"left\" valign=\"middle\" style=\"border:solid 1px black\" height=\"50px\">
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
