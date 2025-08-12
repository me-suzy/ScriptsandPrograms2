<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
<title><?=$site_name;?></title>
<meta http-equiv='Content-Type' content='text/html; charset=<?=$sys_charset!=''?$sys_charset:'iso-8859-1';?>'>

<style type="text/css">
<!--
BODY {
background: #f0;
color: #000;
margin: 0px;
}

A:link {
color: #000;
}

A:visited {
color: #000;
}

A:active {
color: #f00;
}

A:hover {
color: #f00;
}

P {
font: 12px/1.4 verdana, arial, helvetica, sans-serif;
}

TD {
font: 12px verdana, arial, helvetica, sans-serif;
}

H1 {
font: bold 22px verdana, arial, helvetica, sans-serif;
}

H2 {
font: bold 20px verdana, arial, helvetica, sans-serif;
}

H3 {
font: bold 18px verdana, arial, helvetica, sans-serif;
}

H4 {
font: bold 12px verdana, arial, helvetica, sans-serif;
}

B {
font-weight : bold;
}

.sig {
color: #000;
font: 11px verdana, arial, helvetica, sans-serif;
}

A.sig:link {
color: #000;
font: 11px verdana, arial, helvetica, sans-serif;
}

A.sig:visited {
color: #000;
font: 11px verdana, arial, helvetica, sans-serif;
}

A.sig:active {
color: #f00;
font: 11px verdana, arial, helvetica, sans-serif;
}

A.sig:hover {
color: #f00;
font: 11px verdana, arial, helvetica, sans-serif;
}

-->
</style>

<?php
	if ($url=="index") {
		echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
		function MM_openBrWindow(theURL,winName,features) {
			window.open(theURL,winName,features);
		}
		function decision(message, url) {
		if(confirm(message)) location.href = url;
		}
		</script>";
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
<!-- content -->
