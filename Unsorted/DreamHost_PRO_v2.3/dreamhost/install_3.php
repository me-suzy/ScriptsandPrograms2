<html>
<head>
<title>DreamHost Installer v2.3</title>
</head>
<body>
<?
/* This software is developed & licensed by Dreamcost.com.
Unauthorized distribution, sales, or use of any of the code, in part or in whole, is
strictly prohibited and will be prosecuted to the full extent of the law. */

require("setup.php");
define("DB_HOST", "$host");
define("DB_NAME", "$database");
define("DB_USER", "$user");
define("DB_PWD", "$pass");
require("db.conf");



$db = new ps_DB;
$q = "UPDATE setup SET
		setup_path = '$path', 
		setup_url  = '$url',
		setup_login  = '$login',
		setup_password  = '$password',
		setup_superuser  = '$superuser',
		setup_email  = '$email',
		setup_company  = '$company' 
		WHERE setup_id='1'";
$db->query($q);


 ?>
<p align="center"><font face="Verdana, Arial, Helvetica, sans-serif"><b><font size="4" color="#990000">DreamHost 
  Installer Program, v.2.3 </font></b></font> 
<hr width="450" size="1" noshade>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p><font face="Verdana, Arial, Helvetica, sans-serif"><b><font size="3" color="#990000">Installation 
  Completed!</font></b></font></p>
<p><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Your copy of DreamHost 
  is now installed!</font></p>
<p><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b><a href="index.php?" target="_blank">Members 
  Section &amp; Shopping Cart</a></b><br>
  <br>
  <a href="admin/?" target="_blank"><br>
  <b>Administration Section</b></a><br>
  Login: 
  <?echo $login;?>
  <br>
  Pass: 
  <?echo $password;?>
  </font></p>
<p>&nbsp;</p>
<p><font face="Verdana, Arial, Helvetica, sans-serif" size="2">If you want to 
  install support for your payment processor, you will need to install <a href="http://curl.haxx.se" target="_blank">Curl</a>, 
  which is used to setup the SSL connection with your payment gateway. Instructions 
  are below...</font></p>
<p><font face="Verdana, Arial, Helvetica, sans-serif" size="2">The only payment 
  processor that does not require Curl is Verisign Payflow Pro. Verisign has their 
  own SDK you can install. To download and install the Verisign SDK, log into 
  your Verisign account and download the correct version for your OS. A nice PDF 
  install file is included in the download, it will tell you how to install the 
  SDK. Once you have it installed, go the the 'Admin Options' in the DreamHost 
  admin area and select Verisign as your payment gateway, enter your Verisign 
  userid, partner, and the path to the /bin/pfpro file and the path to the /certs/ 
  path. (all these are directorys are installed by the Verisign SDK) Then update 
  and test...</font></p>
<p><font face="Verdana, Arial, Helvetica, sans-serif" size="2">For all other supported 
  processors, you will need to install <a href="http://curl.haxx.se" target="_blank">Curl</a>. 
  Curl is a free program, and installation is a fairly simple process.<br>
  <br>
  First, you may want to check if it came packaged with you OS, some fine Linix, 
  BSD and Debian Operating Systems include it as a standard. <a href="http://curl.haxx.se/docs/osdistribs.html" target="_blank">Check 
  here-&gt;</a></font></p>
<p><font face="Verdana, Arial, Helvetica, sans-serif" size="2">If it is not already 
  installed, you must <a href="http://curl.haxx.se/download.html" target="_blank">download 
  the latest release with SSL support</a>. It is of the utmost importance that 
  it is the SSL version!</font></p>
<p><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Curl does a fine 
  job of documenting the installation procedure, so here is the link.</font></p>
<p><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Once you have completed 
  the install, go the the 'Admin Option' in the DreamHost admin area and select 
  the payment gateway you have an account with, and enter your userid, and the 
  path to curl. (Unix/linux example: \usr\local\curl-7.8\src\curl | Windows example: 
  c:\curl\curl) Then update and test...</font></p>
<p><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Congratulations, 
  you are 100% done with DreamHost setup and installation! </font></p>
<p><font face="Verdana, Arial, Helvetica, sans-serif" size="2">You will of course 
  want to edit the hosting plans you offer, the TLDs you want to accept, your 
  tax, email, template, font, color, and custom page and checkout settings from 
  the Dreamhost admin area. But we hope that our product makes this enjoyable! 
  (Who are we kidding, work is work, right?)</font></p>
<p>&nbsp;</p>
<p><font face="Verdana, Arial, Helvetica, sans-serif" size="2">If need any technical 
  assistance, please email <a href="mailto:mail@dreamcost.com">mail@dreamcost.com</a>. 
  We do not offer telephone support at this time, as most our staff are being 
  used for futher development of DreamHost and our other products.<br>
  <br>
  However, we are dedicated to you, and we will serve you promptly during business 
  hours with a speedy resolution to any technical issues you may have. Also, we 
  urge you to send us any features you wish to be added in future version to <a href="mailto:design@dreamcost.com">design@dreamcost.com</a>.</font></p>
<p><font face="Verdana, Arial, Helvetica, sans-serif" size="2">We wish you the 
  best of luck with you hosting business! </font></p>
<p><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Once again, 
  thank you for supporting our project.<br>
  The Design Team<br>
  Dreamcost.com</b></font></p>
<p>&nbsp;</p>
</body>
</html>