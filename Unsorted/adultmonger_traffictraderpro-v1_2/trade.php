<?php
preg_match("/^(http:\/\/)?([^\/]+)/i", $HTTP_SERVER_VARS['HTTP_REFERER'], $refff);
preg_match("/^(http:\/\/)?([^\/]+)/i", $HTTP_SERVER_VARS['SERVER_NAME'], $hosttt);

if (isset($n) && isset($e) && isset($u) && isset($su) && isset($s) && ($refff[2] == $hosttt[2])){ 
require_once("./admin/admin_max_settings.php");
require_once("./admin/db.php");

preg_match("/^(http:\/\/)?([^\/]+)/i", $u, $matches);
$host = $matches[2];

$black_c = db_query("select siteid, active from ttp_sites where locate('".urlencode($host)."',siteurl)<>0");
$black_r = mysql_fetch_array($black_c);

if (db_numrows($black_c) == 0){
$defalt_q = db_query("select dntf from ttp_settings limit 1");
$defalt_r = mysql_fetch_array($defalt_q);

db_query("insert into ttp_sites (siteid,wname,email,siteurl,furl,sitename,icqnumb,icqname,sent,force,perm,active,manage_type,send_ratio) values (NULL,'".urlencode($n)."','".urlencode($e)."','".urlencode($u)."','".urlencode($su)."','".urlencode($s)."','".urlencode($in)."','".urlencode($inu)."',0,".$defalt_r["dntf"].",0,1,0,150)");

$set_q = db_query("select furl, nemail from ttp_settings limit 1");
$set_r = mysql_fetch_array($set_q);
if ($set_r["nemail"] != ""){
mail($set_r["nemail"], "New Trade Signed Up","A new trade has been added:\n\nName: $n\nEmail: $e\nSite URL: $u\n\nThank you for using AdultMonger","From: $e\r\nReply-To: $e\r\nX-Mailer: PHP/" . phpversion());
}
?>

<html>
<head>
<title>Adult Monger Trade Setup</title>

<STYLE type=text/css>.main { FONT: 8pt Verdana, Helvetica, sans-serif; color: FFFFFF } </STYLE>
<STYLE type=text/css>.small { FONT: 7pt Verdana, Helvetica, sans-serif } </STYLE>
<STYLE type=text/css>
A:link { COLOR: #FFFFFF; TEXT-DECORATION: underline }
A:visited { COLOR: #FFFFFF; TEXT-DECORATION: underline}
A:active { COLOR: #FFFFFF; TEXT-DECORATION: underline}
A:hover { COLOR: #FFFFFF; TEXT-DECORATION: none}
</STYLE>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<body bgcolor="#425b7e" leftmargin="0" topmargin="5" marginwidth="0" marginheight="0">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><img name="amttpjoinv1_r1_c1" src="assets/amttp-joinv1_r1_c1.jpg" width="780" height="29" border="0" alt=""></td>
  </tr>
  <tr>
    <td><table border="0" cellpadding="0" cellspacing="0" width="780" class=main>
        <tr>
          <td><img name="amttpjoinv1_r2_c1" src="assets/amttp-joinv1_r2_c1.jpg" width="605" height="18" border="0" alt=""></td>
          <td width="170" height="18" background="assets/amttp-joinv1_r2_c3.jpg"><font color="#FFFFFF">
            <script>
                     document.write(Date()+".")
                    </script>
            </font></td>
          <td><img name="amttpjoinv1_r2_c4" src="assets/amttp-joinv1_r2_c4.jpg" width="5" height="18" border="0" alt=""></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td><img name="amttpjoinv1_r3_c1" src="assets/amttp-joinv1_r3_c1.jpg" width="780" height="20" border="0" alt=""></td>
  </tr>
  <tr>
    <td><table border="0" cellpadding="0" cellspacing="0" width="780">
        <tr>
          <td><img name="amttpjoinv1_r4_c1" src="assets/amttp-joinv1_r4_c1.jpg" width="4" height="18" border="0" alt=""></td>
          <td width="771" height="18" align="center" background="assets/amttp-joinv1_r4_c2.jpg" class=main>&nbsp;</td>
          <td><img name="amttpjoinv1_r4_c4" src="assets/amttp-joinv1_r4_c4.jpg" width="5" height="18" border="0" alt=""></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td><img name="amttpjoinv1_r5_c1" src="assets/amttp-joinv1_r5_c1.jpg" width="780" height="28" border="0" alt=""></td>
  </tr>
  <tr>
    <td width="780" align="center" background="assets/amttp-joinv1_r6_c1.jpg" class=main>

    <table width="726" height="280" border="0" cellpadding="0" cellspacing="0">
    <tr>
    <td valign="middle" align=center class=main>
Thank you, <?php echo $n;?> Please send all traffic to:<br>
<font size=+1><?php echo urldecode($set_r["furl"]); ?></font><br><br><br><br><br>
    <a href="http://www.adultmonger.com"><img src=assets/ambutton1.jpg border=0><br>
    <font class=small>Get Adultmonger Traffic Trader FREE</font></a>
    </td>
  </tr>
</table>

                </td>
  </tr>
  <tr>
    <td><img name="amttpjoinv1_r8_c1" src="assets/amttp-joinv1_r8_c1.jpg" width="780" height="20" border="0" alt=""></td>
  </tr>
</table>
<?php
db_close();
} elseif (db_numrows($black_c) > 0 && $black_r["active"] == -2){ echo "Your site is blacklisted."; db_close();
} else { echo "Your site is already in the database."; db_close();}
} else { echo "Please resubmit the trade form. Information Missing or Incorrect.<br>\n <font size=1>Ref:".$refff[2]." Host:".$hosttt[2]."</font>"; exit;}
?>
</body>
</html>
