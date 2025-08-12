<? include("setup.php");?>
<? login();?>
<? include("header.php");?>
<? include("account_menu.php");?>
<table border=0 width=85% cellspacing="5" cellpadding="5" align="center">
<form>
<tr> 
<td><font size="2"><br>
</font><b><font size="2">To Refer other users, please use the following URL OR 
the following banner code.</font></b><font size="2"><br>
<br>
<br>
<b>URL:</b> 
<input type="text" value="http://www.XX/pages/index.php?refid=<? user("username");?>" size="60">
<br>
<br>
<br>
<b>Banners:</b> You MUST download the following image and upload it to your web 
site to display the banner on your web site.<br>
<br>
<textarea cols="60" rows="4">
<a href="http://www.XX/pages/index.php?refid=<? user("username");?>"><img src="http://www.YourWebsiteAddress/banner.gif" border="0" alt="ZZ"></a></textarea>
<br>
<br>
<b>Example:</b><br>
<a href="http://www.XX/pages/index.php?refid=<? user("username");?>"><img src="http://www.XX/images/banner.gif" border="0" alt="ZZ"></a> 
<br>
<br>
</font> 
<hr align="center">
<font size="2"> <br>
<textarea cols="60" rows="4">
<a href="http://www.XX/pages/index.php?refid=<? user("username");?>"><img src="http://www.YourWebsiteAddress/banner1.gif" border="0" alt="ZZ"></a>
</textarea>
<br>
<br>
<b>Example:</b><br>
<a href="http://www.XX/pages/index.php?refid=<? user("username");?>"><img src="http://www.XX/images/banner1.gif" border="0" alt="ZZ"></a> 
<br>
<br>
</font></td>
</tr>
</form>
</table>
<? include("footer.php");?>
