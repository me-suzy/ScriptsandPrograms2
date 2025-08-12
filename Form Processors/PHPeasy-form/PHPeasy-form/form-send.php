<!-- PHP easy-form -->
<?

//   Copyright (C) 2004 CentralFloridaVA.com. All rights reserved.

//	 PHPeasy-form version 1.1
//   Released 2004-10-02

//   This file is part of PHPeasy-form.

//   PHPeasy-form is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; either version 2 of the License, or
//   (at your option) any later version.

//   PHPeasy-form is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.

//   You should have received a copy of the GNU General Public License
//   along with PHPeasy-form; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
	
//	 Contact CentralFloridaVA.com at:
//	 http://www.CentralFloridaVA.com
	
//	++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

$name = $_POST["name"];
$address = $_POST["address"];
$city = $_POST["city"];
$state = $_POST["state"];
$zip = $_POST["zip"];
$phone = $_POST["phone"];
$email = $_POST["email"];
$comments = $_POST["comments"];


$today = date("M d, Y");
$recipient = "user@foo.com";
$subject = "Web Form Results";
$forminfo =
"Name: $name\n
Address: $address\n
City: $city\n
State: $state\n
Zip: $zip\n
Phone: $phone\n
Email: $email\n
Comments: $comments\n
Form Submitted: $today\n\n";

$formsend = mail("$recipient", "$subject", "$forminfo", "From: $email\r\nReply-to:$email");
?>
<!-- end PHP easy-form -->
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
	<p align="center"><font size="1">PHPeasy-form written by <a href="http://www.centralfloridava.com" target="_blank">CentralFloridaVA.com</a></font></p></td>
</body>
</html>
