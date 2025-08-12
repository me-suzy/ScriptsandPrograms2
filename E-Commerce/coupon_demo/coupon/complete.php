<html>

<head>

<!--
<script language="JavaScript">

if (window != top) top.location.href = location.href;
//-->
</script> 

<?php
$data_f= file ("admin/inc/data$cn.php");
?>
<?php if ($ck!=$data_f[16]){

echo "You can access this page direct.";
die();
}
?>

<?php
$data_f[15] = ereg_replace("\n","",$data_f[15]); 
?>
<SCRIPT LANGUAGE="JavaScript">

redirTime = "5000";
redirURL = "<?php echo$data_f[15]?>";
function redirTimer() { self.setTimeout("self.location.href = redirURL;",redirTime); }
//  End -->
</script>


</head>

<body onLoad="redirTimer()">


<?php
$fp = fopen("admin/inc/c_downl$cn.php", "r");
if (!$fp) die("Could NOT open file(r)");
$number = fread($fp, filesize("admin/inc/c_downl$cn.php"));

$number++;

fclose($fp);
$fp = fopen("admin/inc/c_downl$cn.php", "w");
if (!$fp) die("Could NOT open file(w)");
fwrite($fp, $number);
fclose($fp);
?>

<center><table width = 50%><tr><td>
Processing your order if you are not redirected within 5 seconds please<br>
<FORM METHOD="link" ACTION="<?php echo "$data_f[15]";?>">
<INPUT TYPE="submit" VALUE=" Click Here.">
</FORM>


</tr></td></table>
</center>
</body></html>