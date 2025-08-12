<?

$directory="../sections/$pageid/images";
$filedirectory="../sections/$pageid/files";



echo "
<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html>
<head>
	<title>Insert Image</title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
<script language=\"JavaScript\" type=\"text/javascript\">
<!--
function AddImage() {
	var oForm = document.linkForm;
	
	//validate form
	if (oForm.url.value == '' && oForm.url1.value == '') {
		alert('Please enter a url.');
		return false;
	}

	var linkopen = '';
	var linkclose = '';
	if (oForm.url1to.value != '')
	{linkopen = '<a href=\"' + document.linkForm.url1to.value + '\" target=\"' + document.linkForm.linkTarget.options[document.linkForm.linkTarget.selectedIndex].value + '\">';
	linkclose = '</a>';}
	if (oForm.urlto.value != '')
	{linkopen = '<a href=\"' + document.linkForm.urlto.value + '\" target=\"' + document.linkForm.linkTarget.options[document.linkForm.linkTarget.selectedIndex].value + '\">';
	linkclose = '</a>';}

		
	if (oForm.url1.value != '')
	{
	var html = linkopen + '<img src=\"' + document.linkForm.url1.value + '\"border=\"0\" />' + linkclose;
	} else {var html = linkopen + '<img src=\"' + document.linkForm.url.value + '\" border=\"0\" />' + linkclose;}
	window.opener.insertHTML(html);
	window.close();
	return true;
}
//-->
</script>
</head>

<body style=\"margin: 10px; background: #EEEEEE;\">
<form name=\"linkForm\" onSubmit=\"return AddImage();\">
<table cellpadding=\"4\" cellspacing=\"0\" border=\"0\">
	<tr><td colspan=\"2\"><span style=\"font-style: italic; font-size: x-small;\"><b>Tip:</b> To insert an email link, start your URL with \"mailto:\"</span></td></tr>
	<tr>
		<td align=\"right\">URL of the image:</td>
		<td><input name=\"url\" type=\"text\" id=\"url\" size=\"40\"></td>
	</tr>
	<tr>
		<td align=\"right\">Or link to the stored file:</td>
		<td><select name=\"url1\" id=\"url1\">
		<option value=\"\">-- select --</option>
		";
		$dh=opendir($directory);
		while ($file=readdir($dh))
		{if ($file!=".." && $file!="." && $file!="index.php")
		echo "<option value=\"../sections/$pageid/images/$file\">$file</option>";

		}
		closedir();		
		echo "
		</select>
		</td>
	</tr>
	<tr>
		<td align=\"right\">Use the image as a link to:</td>
		<td><input name=\"urlto\" type=\"text\" id=\"urlto\" size=\"40\"></td>
	</tr>
	<tr>
		<td align=\"right\">Or as a link to the stored file:</td>
		<td><select name=\"url1to\" id=\"url1to\">
		<option value=\"\">-- select --</option>
		";
		$dh=opendir($filedirectory);
		while ($file=readdir($dh))
		{if ($file!=".." && $file!="." && $file!="index.php")
		echo "<option value=\"../sections/$pageid/files/$file\">$file</option>";

		}		
		closedir();
		echo "
		</select>
		</td>
	</tr>
	<tr>
		<td align=\"right\">Target:</td>
		<td align=\"left\">
			<select name=\"linkTarget\" id=\"linkTarget\">
				<option value=\"_blank\">_blank</option>
				<option value=\"_parent\">_parent</option>
				<option value=\"_self\" selected>_self</option>
				<option value=\"_top\">_top</option>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan=\"3\" align=\"center\">
			<input type=\"submit\" value=\"Insert Image\" />
			<input type=\"button\" value=\"Cancel\" onClick=\"window.close();\" />
		</td>
	</tr>
</table>

</form>

</body>
</html>
";

?>