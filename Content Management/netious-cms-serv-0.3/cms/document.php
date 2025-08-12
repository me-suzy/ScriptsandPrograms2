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
if (!isset($sf)) $sf="document";

if (!isset($newbgfile)) $newbgfile="";
if (!isset($ext_bg)) $ext_bg="";

/* Read the values */

if (!isset($docbgcol))
	{$result=mysql_query("SELECT docbgcol, docbgim, docbgrep, docbgpos, docborstyle, docborw, docborcol FROM style WHERE active='1' limit 0,1");
	$row=mysql_fetch_row($result);
	$docbgcol=$row[0];
	$docbgim=$row[1];
	$docbgrep=$row[2];
	$docbgpos=$row[3];
	$docborstyle=$row[4];
	$docborw=$row[5];
	$docborcol=$row[6];
	}


$bgimage=$_FILES['new_docbgim']['tmp_name'];
$bgimage_name=$_FILES['new_docbgim']['name'];
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
	   document.headform.action =\"documentResponse.php\";
	  }
	  else
	  if(document.pressed == 'Update Preview')
	  {
	    document.headform.action =\"document.php\";
	  }
	  return true;
	}
	</SCRIPT>


	</td>
	<td>
	<center><h2>Control panel - document style</h2></center><br /><br />
	<b>Below the form you can see the preview of the document. After modifications you can either save the changes or update the preview first to see the effect. 	</b> <br /><br />
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
	<td colspan=\"3\" align=\"center\">
	<b>Background</b>
	</td>
	</tr>
	<tr>
	<td>
	<input type=\"hidden\" name=\"newbgfile\" value=\"$newbgfile\" />
	<input type=\"hidden\" name=\"ext_bg\" value=\"$ext_bg\" />
	<input type=\"hidden\" name=\"docbgim\" value=\"$docbgim\" />
	Background color:
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
			$thisdocbgcol="#$re$gr$bl";
			if (is_int($i/12) && $i!=0) echo "</tr><tr>";
			if ($docbgcol==$thisdocbgcol) {echo "<td><input type=\"radio\" name=\"docbgcol\" value=\"$thisdocbgcol\" checked=\"checked\" />
</td><td width=10 bgColor=\"$thisdocbgcol\"></td>";} else {echo "<td><input type=\"radio\" name=\"docbgcol\" value=\"$thisdocbgcol\" "; 

if ($i==0) echo "checked=\"checked\"";

echo" />
</td><td width=10 bgColor=\"$thisdocbgcol\"></td>";}
			$i++;
			}
		}
	}
if ($docbgcol=="#ffffff") {echo "<td><input type=radio name=\"docbgcol\" value=\"#ffffff\" checked />
</td><td width=10 bgColor=\"#ffffff\"></td>";} else {echo "<td><input type=radio name=\"docbgcol\" value=\"#ffffff\" />
</td><td width=10 bgColor=\"#ffffff\"></td>";}

echo "
</tr>
</table>
	</td>
	</tr>
	<tr>
	<td>
	Background Image (optional):
	</td>
	<td align=\"left\" colspan=\"2\">
	<input type=\"file\" name=\"new_docbgim\" />
	</td>
	</tr>
	<tr>
	<td></td>
	<td>
	Background repeat:
	</td>
	<td align=\"left\">
	<select name=\"docbgrep\">";
	if ($docbgrep=="no-repeat") echo "<option value=\"no-repeat\" selected=\"selected\">Do not repeat the image</option>"; else echo "<option value=\"no-repeat\">Do not repeat the image</option>";
	if ($docbgrep=="repeat-x") echo "<option value=\"repeat-x\" selected=\"selected\">Repeat in horizontal (x) direction</option>"; else echo "<option value=\"repeat-x\">Repeat in horizontal (x) direction</option>";
	if ($docbgrep=="repeat-y") echo "<option value=\"repeat-y\" selected=\"selected\">Repeat in vertical (y) direction</option>"; else echo "<option value=\"repeat-y\">Repeat in vertical (y) direction</option>";
	if ($docbgrep=="repeat") echo "<option value=\"repeat\" selected=\"selected\">Repeat in both directions</option>"; else echo "<option value=\"repeat\">Repeat in both directions</option>";
	echo "
	</select>
	</td>
	</tr>
	<tr>
	<td></td>
	<td>
	Background position (if not repeated):
	</td>
	<td align=\"left\">
	<select name=\"docbgpos\">";
	if ($docbgpos=="0% 0%") echo "<option value=\"0% 0%\" selected=\"selected\">Left - top</option>"; else echo "<option value=\"0% 0%\">Left - top</option>";
	if ($docbgpos=="0% 50%") echo "<option value=\"0% 50%\" selected=\"selected\">Left - middle</option>"; else echo "<option value=\"0% 50%\">Left - middle</option>";

	if ($docbgpos=="0% 100%") echo "<option value=\"0% 100%\" selected=\"selected\">Left - bottom</option>"; else echo "<option value=\"0% 100%\">Left - bottom</option>";

	if ($docbgpos=="50% 0%") echo "<option value=\"50% 0%\" selected=\"selected\">Center - top</option>"; else echo "<option value=\"50% 0%\">Center - top</option>";
	if ($docbgpos=="50% 50%") echo "<option value=\"50% 50%\" selected=\"selected\">Center - middle</option>"; else echo "<option value=\"50% 50%\">Center - middle</option>";

	if ($docbgpos=="50% 100%") echo "<option value=\"50% 100%\" selected=\"selected\">Center - bottom</option>"; else echo "<option value=\"50% 100%\">Center - bottom</option>";

	if ($docbgpos=="100% 0%") echo "<option value=\"100% 0%\" selected=\"selected\">Right - top</option>"; else echo "<option value=\"100% 0%\">Right - top</option>";

	if ($docbgpos=="100% 50%") echo "<option value=\"100% 50%\" selected=\"selected\">Right - middle</option>"; else echo "<option value=\"100% 50%\">Right - middle</option>";

	if ($docbgpos=="100% 100%") echo "<option value=\"100% 100%\" selected=\"selected\">Right - bottom</option>"; else echo "<option value=\"100% 100%\">Right - bottom</option>";
	echo "
	</select>
	</td>
	</tr>";
	

if ($newbgfile!="" || $docbgim!="")
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
	<b>Border</b>
	</td>
	</tr>
	<tr>
	<td>
	Border style:
	</td>
	<td colspan=\"2\">
	<select name=\"docborstyle\">";
	if ($docborstyle=="solid") echo "<option selected=\"selected\" value=\"solid\">Solid</option>"; else echo "<option value=\"solid\">Solid</option>";
	if ($docborstyle=="dashed") echo "<option selected=\"selected\" value=\"dashed\">Dashed</option>"; else echo "<option value=\"dashed\">Dashed</option>";
	if ($docborstyle=="dotted") echo "<option selected=\"selected\" value=\"dotted\">Dotted</option>"; else echo "<option value=\"dotted\">Dotted</option>";
	
	
	echo "
	</select>
	</td>
	</tr>
	<tr>
	<td>
	Border width:
	</td>
	<td colspan=\"2\">
	<input type=\"text\" size=\"4\" name=\"docborw\" value=\"$docborw\" />
	</tr>
	

	<tr>
	<td>
	<input type=\"hidden\" name=\"newbgfile\" value=\"$newbgfile\" />
	<input type=\"hidden\" name=\"ext_bg\" value=\"$ext_bg\" />
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
			$thisdocborcol="#$re$gr$bl";
			if (is_int($i/12) && $i!=0) echo "</tr><tr>";
			if ($docborcol==$thisdocborcol) {echo "<td><input type=\"radio\" name=\"docborcol\" value=\"$thisdocborcol\" checked=\"checked\" />
</td><td width=10 bgColor=\"$thisdocborcol\"></td>";} else {echo "<td><input type=\"radio\" name=\"docborcol\" value=\"$thisdocborcol\" "; 

if ($i==0) echo "checked=\"checked\"";

echo" />
</td><td width=10 bgColor=\"$thisdocborcol\"></td>";}
			$i++;
			}
		}
	}
if ($docborcol=="#ffffff") {echo "<td><input type=radio name=\"docborcol\" value=\"#ffffff\" checked />
</td><td width=10 bgColor=\"#ffffff\"></td>";} else {echo "<td><input type=radio name=\"docborcol\" value=\"#ffffff\" />
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
<table width=\"100%\">
<tr>
<td height=\"100px\" align=\"left\" valign=\"middle\" style=\"background-color: $docbgcol;";
if ($newbgfile!="" && !isset($replace_bg)) {echo "background-image:url('$newbgfile'); background-repeat:$docbgrep; background-position: $docbgpos;";}
elseif ($docbgim!="" && !isset($replace_bg)) {$docbgim=str_replace("./","../",$docbgim); echo "background-image:url('$docbgim'); background-repeat:$docbgrep; background-position: $docbgpos;";}
echo "border: $docborstyle $docborw $docborcol\">
<br />
Here goes the document!!
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
