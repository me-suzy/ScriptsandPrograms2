<!-- PHP easy-form -->
<?
/*
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
   Copyright (C) 2004-2005 SunFrogServices.com. All rights reserved.

   PHPeasy-form version 2.0
   Released 2005-05-16

   This file is part of PHPeasy-form.

   PHPeasy-form is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

    PHPeasy-form is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with PHPeasy-form; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
	
	Contact SunFrogServices.com at:
	http://www.SunFrogServices.com
	
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/
// write form results to file

$fp = fopen("form-results.txt", "a"); 
fwrite($fp, $_POST['name'] . "," . 
			$_POST['address'] . "," . 
			$_POST['city'] . "," . 
			$_POST['state'] . "," . 
			$_POST['zip'] . "," . 
			$_POST['phone'] . "," . 
			$_POST['email'] . "," . 
			$_POST['comments'] . "," . 
			date("M-d-Y") . "\n");
fclose($fp);

// send form results through email
$recipient = "user@foo.com";
$subject = "Web Form Results";
$forminfo = 
($_POST['name'] . "\r" .
$_POST['address'] . "\r" .
$_POST['city'] . "\r" .
$_POST['state']  . "\r" .
$_POST['zip']  . "\r" .
$_POST['phone']  . "\r" .
$_POST['email'] . "\r" .
$_POST['comments'] . "\r\n" .
date("M-d-Y") . "\r\n\n");

$formsend = mail("$recipient", "$subject", "$forminfo", "From: $email\r\nReply-to:$email");
?>
<!-- end PHPeasy-form -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr> 
    <td> <p align="left">Thank you. You have successfully submitted the following information:</p>
	<p><? echo nl2br($forminfo); ?></p>
  </tr>
</table>
	<p align="center"><font size="1">PHPeasy-form written by <a href="http://www.sunfrogservices.com" target="_blank">SunFrogServices.com</a></font></p></td>
</body>
</html>
