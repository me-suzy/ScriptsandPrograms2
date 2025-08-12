<?php

// some global initialisation stuff
$Pivot_Vars = array_merge($_GET , $_POST, $_SERVER);

if (substr($Pivot_Vars['img'],0,1)!="/") {
	$img = urldecode(base64_decode($Pivot_Vars['img']));
} else {
	$img = urldecode($Pivot_Vars['img']);
}

if (base64_decode($Pivot_Vars['t'])) {
	$title = base64_decode($Pivot_Vars['t']);
} else {
	$title = $Pivot_Vars['t'];
}

?>
<html>
<head>
<title><?php echo $title; ?></title>

</head>
<body bgcolor="#000000" leftmargin=0 topmargin=0  marginwidth=0 marginheight=0 onblur="self.close();" onload="self.focus();">

<img src="<?php echo $img; ?>" width=<?php echo $Pivot_Vars['w']; ?> height=<?php echo $Pivot_Vars['h']; ?> border='0'>
</body>
<script language="javaScript">
document.onkeypress = function esc(e) {	
	if(typeof(e) == "undefined") { e=event; }
	if (e.keyCode == 27) { self.close(); }
}
</script>
</html>
