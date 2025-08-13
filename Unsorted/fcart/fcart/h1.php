<html>
<head><? include "meta.php" ?>
<title>Help</title>
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<? include "cssstyle.php" ?>
<? include "config.php" ?>
</head>
<body bgcolor="<? echo $cl_tab_top ?>" text="#000080">

<font size="+2"><b>Help</b></font>
<hr>
<br>

<font color=#CC6600 face=verdana,arial,helvetica><b>
Ordering
</b></font><br>

<ul><li> 
<a href="<? echo "http://$http_location/"; ?>h2.php?topic=newcustomers">Assistance for New Customers</a> -- placing your first order is simple<li> 
<a href="<? echo "http://$http_location/"; ?>h2.php?topic=registering">Registering at our shop</a> -- benefits you get upon registering and the procedure<li> 
<a href="<? echo "http://$http_location/"; ?>h2.php?topic=usingshopcart">Using the Shopping Cart</a> -- shop to your heart's content<li> 
<a href="<? echo "http://$http_location/"; ?>h2.php?topic=wishlists">Wish Lists</a> -- putting a purchase until later if you can't afford it now<li> 
<a href="<? echo "http://$http_location/"; ?>h2.php?topic=orderpage">Order page</a> -- hints that will help you with the order page<li> 
<a href="<? echo "http://$http_location/"; ?>h2.php?topic=shopguarantee">Safe Shopping Guarantee</a> -- protects you while you shop</ul> 

<font color=#CC6600 face=verdana,arial,helvetica><b>
Issuing &amp; Redeeming gift certificates
</b></font><br>

<ul><li> 
<a href="<? echo "http://$http_location/"; ?>h2.php?topic=giftissue">Issuing a Gift Certificate</a> -- a good solution for gifts<li> 
<a href="<? echo "http://$http_location/"; ?>h2.php?topic=giftredeem">Redeeming a Gift Certificate</a> -- put that gift to good use
</ul>

<font color=#CC6600 face=verdana,arial,helvetica><b>
Shipping &amp; Returns
</b></font><br>

<ul><li> 
<a href="<? echo "http://$http_location/"; ?>h2.php?topic=whenarrive">When Will My Order Arrive?</a> -- Availability + Shipping = Delivery Time<li> 
<a href="<? echo "http://$http_location/"; ?>h2.php?topic=internationalorders">International Shipping Rates</a> -- ship anywhere cheaply and quickly<li> 
<a href="<? echo "http://$http_location/"; ?>h2.php?topic=returnpolicy">Returns Policy</a> -- what we accept and where you can send it
</ul>

<font color=#CC6600 face=verdana,arial,helvetica><b>
Using Your Account
</b></font><br>

<ul><li> 
<a href="<? echo "http://$http_location/"; ?>h2.php?topic=editdata">Changing Your Name, E-mail Address, Password and other data</a> -- how to keep your account current<li> 
<a href="<? echo "http://$http_location/"; ?>h2.php?topic=orders">Viewing your buying activity</a> -- see your previous orders history<li> 
<a href="<? echo "http://$http_location/"; ?>h2.php?topic=cancelling">Canceling an Order</a> -- the way to change your mind
</ul>

<font color=#CC6600 face=verdana,arial,helvetica><b>
Forgot your password?
</b></font>
<form action="send_password.php" method="POST">
<font size="-1">You can retrieve your password by giving your username or e-mail address:</font><br>
Username or e-mail:&nbsp;&nbsp;<input type="text" size="24" name="uname" maxlength="128">&nbsp;&nbsp;<input type="submit" value="Send">
</form>
<font color=#CC6600 face=verdana,arial,helvetica><b>
Still need help?
</b></font>
Mail to <a href="mailto:<? echo ereg_replace(".*<","",ereg_replace(">.*","",$support_email)) ?>"><? echo ereg_replace(".*<","",ereg_replace(">.*","",$support_email)) ?></a>
<hr>
<center><form><input type="button" value="Close" onClick="self.close()"></form></center>

</body></html>
