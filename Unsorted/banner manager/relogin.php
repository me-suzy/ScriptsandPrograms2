<?php 

$html = '<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">';
$html .= '<html><head><title>Banner Manager</title><meta name="Author" content="">';
$html .= '<meta name="Keywords" content="">';
$html .= '<meta name="Description" content="">';
$html .= '<style type="text/css">';
$html .= '.copyright {font: 8pt arial}';
$html .= '.tips {font: italic 8pt arial}';
$html .= '.copyrightsite {font: bold 8pt verdana}';
$html .= '.header {font: bold 10pt verdana}';
$html .= '.label {font: 9pt arial}';
$html .= '.error {font: italic 8pt arial; color: red}';
$html .= 'body {font: 8pt arial}';
$html .= 'td {font: 8pt arial}';
$html .= 'input {font: 8pt arial}';
$html .= '</style>';
$html .= '</head><body bgcolor="white"><center><br><br><br>';
$html .= '<table width="70%" cellpadding="1" cellspacing="0" border="0" bgcolor="black">';
$html .= '<tr><td><table width="100%" height="100%" cellpadding="1" cellspacing="0" border="0" bgcolor="#eeeeee">';
$html .= '<tr><td align="center"><br><br>Your have not login or your login session has expired. Please re-login. <br>Thank you.<br><br></td></tr><tr><td>&nbsp;</td></tr><tr><td align="center"><a href="../index.php"><b>Home</b></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php"><b>Login</b></a><br><br></td></tr></table></td></tr></table>';
$html .= '<br><br></body></html>';
print($html);
exit;

?>