<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?=$site_name;?></title>
<meta http-equiv='Content-Type' content='text/html; charset=<?=$sys_charset!=''?$sys_charset:'iso-8859-1';?>'>
<link rel=stylesheet href="style.css" type="text/css">
	  
<?php
if ($url=="index") {
	echo "
<script language=\"JavaScript\" type=\"text/JavaScript\">
	function MM_openBrWindow(theURL,winName,features) {
		window.open(theURL,winName,features);
	}
	function decision(message, url){
		if(confirm(message)) location.href = url;
	}
</script>\n";
}
		
if($url=="index"){
if($date_style=="1")
				{
			$ship_date_1=date("m/d/Y");}
			// EU date format
			if($date_style=="0")
				{
			$ship_date_1=date("d/m/Y");}
?>
<script language="JavaScript">
<!--
function setToToday(){
	if(document.orders.shippedtoday.checked == true){
	document.orders.ship_date.value = "<? echo $ship_date_1;?>";
	}
}

// -->
</script>
<?}?>
</head>

<body>
<a name="toppage"></a>

<table width="100%" border="0" cellpadding="5" cellspacing="0">
<tr bgcolor="#cccccc">
<td width="30%"><a href="<?php echo"$site_url"; ?>" target="_blank"><img src="images/admin_logo_250.gif" width="250" height="50" border="0" hspace="0" vspace="0"></a></td>
<form action="?" method="get" name="lng_form"><td width="50%"><font class="favorite"><?=$lng[858];?>: </font><select name="new_lng" onchange="document.forms['lng_form'].submit();"><?
if(is_array($_languages))
	foreach($_languages as $key=>$value)
	{?>
		<option<?=$sys_lng==$value?" selected":'';?>><?=$value;?>
	<?}?></select></td><input type="Hidden" name="_action" value="change_language"></form>
<td width="20%" align="right"><b><?echo date("l j, F Y");?></b></td>
</tr>
</table>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr><td height="2"><img src="images/z.gif" width="1" height="1" border="0" hspace="0" vspace="0"></td></tr>
<tr><td height="2" bgcolor="#000000"><img src="images/z.gif" width="1" height="1" border="0" hspace="0" vspace="0"></td></tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td valign="top" width="200">
<!-- admin menu -->
<table border="0" cellspacing="0" cellpadding="5" width="200">
<tr><td height="25"><a href="index.php"><b><?=$lng[604];?></b></a>/ <a href="<?php echo"$site_url"; ?>" target="_blank"><b><?=$lng[605];?></b></a>/ 
<? if( session_is_registered(admin) || session_is_registered(demo) ){?>
<a href="logout.php"><b><?=$lng[606];?></b></a>
<?}?>
</td></tr>
<tr><td bgcolor="#e0e0e0" height="25"><b><?=$lng[607];?></b></td></tr>
<tr><td><b>&#187;&nbsp;</b><a href="add_category.php"><?=$lng[608];?></a></td></tr>
<tr><td><b>&#187;&nbsp;</b><a href="categories.php"><?=$lng[609];?></a></td></tr>
<tr><td bgcolor="#e0e0e0"><b><?=$lng[610];?></b></td></tr>
<tr><td><b>&#187;&nbsp;</b><a href="add_product.php"><?=$lng[611];?></a></td></tr>
<tr><td><b>&#187;&nbsp;</b><a href="products.php"><?=$lng[612];?></a></td></tr>
<tr><td><b>&#187;&nbsp;</b><a href="edit_atributes.php"><?=$lng[613];?></a></td></tr>
<tr><td bgcolor="#e0e0e0"><b><?=$lng[614];?></b></td></tr>
<tr><td><b>&#187;&nbsp;</b><a href="customers.php"><?=$lng[615];?></a></td></tr>
<tr><td><b>&#187;&nbsp;</b><a href="orders.php"><?=$lng[616];?></a></td></tr>
<tr><td><b>&#187;&nbsp;</b><a href="statistics.php"><?=$lng[617];?></a></td></tr>
<tr><td><b>&#187;&nbsp;</b><a href="email.php"><?=$lng[618];?></a></td></tr>
<tr><td><b>&#187;&nbsp;</b><a href="../help/ABC_eStore_users_guide.htm" target="_blank"><?=$lng[619];?></a></td></tr>
<tr><td bgcolor="#e0e0e0"><b><?=$lng[864];?></b></td></tr>
<tr><td><b>&#187;&nbsp;</b><a href="add_country.php"><?=$lng[865];?></a></td></tr>
<tr><td><b>&#187;&nbsp;</b><a href="countries.php"><?=$lng[864];?></a></td></tr>
<tr><td><b>&#187;&nbsp;</b><a href="add_delivery.php"><?=$lng[876];?></a></td></tr>
<tr><td><b>&#187;&nbsp;</b><a href="deliveries.php"><?=$lng[877];?></a></td></tr>
<tr><td><b>&#187;&nbsp;</b><a href="add_tax.php"><?=$lng[890];?></a></td></tr>
<tr><td><b>&#187;&nbsp;</b><a href="taxes.php"><?=$lng[891];?></a></td></tr>
<tr><td bgcolor="#e0e0e0"><b><?=$lng[620];?></b></td></tr>
<tr><td><b>&#187;&nbsp;</b><a href="edit_settings.php"><?=$lng[621];?></a></td></tr>
<tr><td><b>&#187;&nbsp;</b><a href="manage_languages.php"><?=$lng[822];?></a></td></tr>
<tr><td><b>&#187;&nbsp;</b><a href="backup.php"><?=$lng[623];?></a></td></tr>
<tr><td bgcolor="#e0e0e0"><b><?=$lng[624];?></b></td></tr>
<tr><td><b>&#187;&nbsp;</b><a href="import_xl.php"><?=$lng[625];?></a></td></tr>
<tr><td>&nbsp;</td></tr>
</table>
<!-- /admin menu -->
</td>
<td width="1" bgcolor="#cccccc"><img src="images/z.gif" width="1" height="1" border="0" hspace="0" vspace="0"></td>
<td width="19"><img src="images/z.gif" width="1" height="1" border="0" hspace="0" vspace="0"></td>
<td valign="top">
<!-- content -->
	