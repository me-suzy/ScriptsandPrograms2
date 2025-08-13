<?
/*****************************************************************/
/* Program Name         : EPal Pro                               */
/* Program Version      : 1.0                                    */
/* Program Author       : BlizSoft                               */
/* Site                 : http://www.BlizSoft.com                */
/* Email                : software@BlizSoft.com                  */
/*                                                               */
/* Copyright (c) 2003 BlizSoft.com All rights reserved.          */
/* Do NOT remove any of the copyright notices in the script.     */
/* This script can not be distributed or resold by anyone else   */
/* than the author, unless special permisson is given.           */
/*                                                               */
/*****************************************************************/

include("include.inc.php");  

if ($auser && $apass) { 
 if ($control[user] == $auser && $control[pass] == $apass) {
  setcookie("admin_cookie","$control[admin_cookie]",$time+86400); 
  echo "<script language=JavaScript>  parent.location.href='index.php?'; </script>";  
 }
}

if ($pp == 'Yes') { mysql_query("update control set paypal=1 where id=$control[id]"); } 
if ($pp == 'No') { mysql_query("update control set paypal=0 where id=$control[id]"); } 
if ($pp_addy) { mysql_query("update control set paypal_addy='$pp_addy' where id=$control[id]"); } 
if ($about_text) { mysql_query("update control set about_text = '$about_text' where id = $control[id]"); }
if ($fees_text) { mysql_query("update control set fees_text = '$fees_text' where id = $control[id]"); }
if ($legal_text) { mysql_query("update control set legal_text = '$legal_text' where id = $control[id]"); }
if ($privacy_text) { mysql_query("update control set privacy_text = '$privacy_text' where id = $control[id]"); }
if ($contact_text) { mysql_query("update control set contact_text = '$contact_text' where id = $control[id]"); }
if ($send_money_text) { mysql_query("update control set send_money_text = '$send_money_text' where id = $control[id]"); }
if ($sell_text) { mysql_query("update control set sell_text = '$sell_text' where id = $control[id]"); }
if ($shipping_text) { mysql_query("update control set shipping_text = '$shipping_text' where id = $control[id]"); }
if ($help_text) { mysql_query("update control set help_text = '$help_text' where id = $control[id]"); }
$control = mysql_fetch_array(mysql_query("select * from control order by id desc limit 1"));
echo "
<html>
<head>";
 echo "
 <title>Money Order's For You</title>
 <style>
 table { border-collapse:collapse; 
        font-size:10;
        font-family:Verdana;
       }
 a:link {
         font:bold; 
         font-size:10;
 	 color: #FFFFFF;
 	 text-decoration: none;
 }
 a:visited {
 	 font:bold; 
         font-size:10;
 	 color: #FFFFFF;
 	 text-decoration: none;
 }
 input {
 	 font:bold; 
         font-size:10;
 	 color: #000000;
 	 background:#ffffff;
 }
 a:active {
 	 font:bold; 
         font-size:10;
 	 color: #000000;
 	 text-decoration: none;
 }
 a:hover {
 	 font:bold; 
         font-size:10;
 	 color: #FF0000;
 	 text-decoration: none;
 }
 a.main:link {
        font:bold; 
        font-size:10;
	color: #000000;
	text-decoration: none;
}
a.main:visited {
	font:bold; 
        font-size:10;
	color: #000000;
	text-decoration: none;
}
a.main:active {
	font:bold; 
        font-size:10;
	color: #000000;
	text-decoration: none;
}
a.main:hover {
	font:bold; 
        font-size:10;
	color: #FF0000;
	text-decoration: none;
}

 
 .bottom {
 	 border-bottom:1 solid #000000;
 }     
 .both {
 	 border-bottom:1 solid #000000;
 	 border-top:1 solid #000000;
 }     
 </style>
</head>
<body>
 <table width=600 align=center><tr><td width=100%>
  <table width=100% cellpadding=2 cellspacing=5 style='border:2 solid #336699;'>
   <tr bgcolor=#EBEDCC>
    <td colspan=2 height=25 valign=middle><b>Administrator Options</b></td>
   </tr>
   <tr>";
   if ($admin_cookie && $admin_cookie == $control[admin_cookie]) { echo "
    <td align=center width=100%> 
      <fieldset><legend><small><b>PayPal Options</legend>
      <table width=100% align=center> 
       <form action=index.php method=post>
         <tr><td align=center colspan=2>If PayPal is active, be sure to turn on your IPN options in PayPal.<br><br>Point your IPN to this URL: <b>http://www.site-name/admin/ipn_form_rfc1024.php</b></td></tr>
         <tr><td><b>PayPal Active</b> - <small>Turns PayPal on or off.</td><td align=center>";
          if ($control[paypal] == 0) { echo "ACTIVE<input type=radio name=pp value=Yes class=2> DISABLED<input type=radio name=pp value=No class=2 checked>"; }
          else { echo "ACTIVE<input type=radio name=pp value=Yes class=2 checked> DISABLED<input type=radio name=pp value=No class=2>"; }
         echo "</td></tr>
         <tr><td align=center colpsan=2>PayPal Email Address:&nbsp;<input type=text size=15 name=pp_addy value=$control[paypal_addy]></td></tr>
         <tr><td align=center colspan=2><input type=submit value=Update></td></tr>
       </form>
      </table>
     </fieldset>

     <fieldset><legend><small><b>Reporting Options</legend>
      <table width=100% align=center> 
        <tr><td align=center colspan=2>All reports are limited to 50 results unless otherwise noted, and are opened on a new page in a printable format.</td></tr>
        <tr><td align=center colspan=2><a class=main target=_blank href=report.php?report=days>Transactions for Last 30 Days</a></td></tr>
        <tr><td align=center colspan=2><a class=main target=_blank href=report.php?report=date>Transactions Ordered by Date</a></td></tr>
        <tr><td align=center colspan=2><a class=main target=_blank href=report.php?report=total>Transactions Ordered by Total</a></td></tr>
        <tr><td align=center colspan=2><a class=main target=_blank href=report.php?report=alphas>Transactions Ordered Alphabetically by Sender</a></td></tr>
        <tr><td align=center colspan=2><a class=main target=_blank href=report.php?report=alphar>Transactions Ordered Alphabetically by Receiver</a></td></tr>
      </table>
     </fieldset>

     <fieldset><legend><small><b>About Us</legend>
      <table width=100% align=center> 
       <form action=index.php method=post>
        <tr><td align=center colspan=2><xmp>TEXT EDITING -->   <br> = newline   <u> = underline   <b> = bold   &bull; = bullet</xmp></td></tr>
        <tr><td align=center colspan=2><textarea name=about_text rows=10 cols=75>$control[about_text]</textarea></td></tr>
        <tr><td align=center colspan=2><input type=submit value=Update></td></tr>
       </form>
      </table>
     </fieldset>
     
     <fieldset><legend><small><b>Fees</legend>
      <table width=100% align=center> 
       <form action=index.php method=post>
        <tr><td align=center colspan=2><xmp>TEXT EDITING -->   <br> = newline   <u> = underline   <b> = bold   &bull; = bullet</xmp></td></tr>
        <tr><td align=center colspan=2><textarea name=fees_text rows=10 cols=75>$control[fees_text]</textarea></td></tr>
        <tr><td align=center colspan=2><input type=submit value=Update></td></tr>
       </form>
      </table>
     </fieldset>

     <fieldset><legend><small><b>Pricing and Shipping</legend>
      <table width=100% align=center> 
       <form action=index.php method=post>
        <tr><td align=center colspan=2><xmp>TEXT EDITING -->   <br> = newline   <u> = underline   <b> = bold   &bull; = bullet</xmp></td></tr>
        <tr><td align=center colspan=2><textarea name=shipping_text rows=10 cols=75>$control[shipping_text]</textarea></td></tr>
        <tr><td align=center colspan=2><input type=submit value=Update></td></tr>
       </form>
      </table>
     </fieldset>

     <fieldset><legend><small><b>Service Agreement</legend>
      <table width=100% align=center> 
       <form action=index.php method=post>
        <tr><td align=center colspan=2><xmp>TEXT EDITING -->   <br> = newline   <u> = underline   <b> = bold   &bull; = bullet</xmp></td></tr>
        <tr><td align=center colspan=2><textarea name=legal_text rows=10 cols=75>$control[legal_text]</textarea></td></tr>
        <tr><td align=center colspan=2><input type=submit value=Update></td></tr>
       </form>
      </table>
     </fieldset>

     <fieldset><legend><small><b>Privacy Statement</legend>
      <table width=100% align=center> 
       <form action=index.php method=post>
        <tr><td align=center colspan=2><xmp>TEXT EDITING -->   <br> = newline   <u> = underline   <b> = bold   &bull; = bullet</xmp></td></tr>
        <tr><td align=center colspan=2><textarea name=privacy_text rows=10 cols=75>$control[privacy_text]</textarea></td></tr>
        <tr><td align=center colspan=2><input type=submit value=Update></td></tr>
       </form>
      </table>
     </fieldset>

     <fieldset><legend><small><b>Contact Information</legend>
      <table width=100% align=center> 
       <form action=index.php method=post>
        <tr><td align=center colspan=2><xmp>TEXT EDITING -->   <br> = newline   <u> = underline   <b> = bold   &bull; = bullet</xmp></td></tr>
        <tr><td align=center colspan=2><textarea name=contact_text rows=10 cols=75>$control[contact_text]</textarea></td></tr>
        <tr><td align=center colspan=2><input type=submit value=Update></td></tr>
       </form>
      </table>
     </fieldset>

     <fieldset><legend><small><b>Send Money Information</legend>
      <table width=100% align=center> 
       <form action=index.php method=post>
        <tr><td align=center colspan=2><xmp>TEXT EDITING -->   <br> = newline   <u> = underline   <b> = bold   &bull; = bullet</xmp></td></tr>
        <tr><td align=center colspan=2><textarea name=send_money_text rows=10 cols=75>$control[send_money_text]</textarea></td></tr>
        <tr><td align=center colspan=2><input type=submit value=Update></td></tr>
       </form>
      </table>
     </fieldset>
     
     <fieldset><legend><small><b>Sell Information</legend>
      <table width=100% align=center> 
       <form action=index.php method=post>
        <tr><td align=center colspan=2><xmp>TEXT EDITING -->   <br> = newline   <u> = underline   <b> = bold   &bull; = bullet</xmp></td></tr>
        <tr><td align=center colspan=2><textarea name=sell_text rows=10 cols=75>$control[sell_text]</textarea></td></tr>
        <tr><td align=center colspan=2><input type=submit value=Update></td></tr>
       </form>
      </table>
     </fieldset>
     
     <fieldset><legend><small><b>Pricing and Shipping</legend>
      <table width=100% align=center> 
       <form action=index.php method=post>
        <tr><td align=center colspan=2><xmp>TEXT EDITING -->   <br> = newline   <u> = underline   <b> = bold   &bull; = bullet</xmp></td></tr>
        <tr><td align=center colspan=2><textarea name=shipping_text rows=10 cols=75>$control[shipping_text]</textarea></td></tr>
        <tr><td align=center colspan=2><input type=submit value=Update></td></tr>
       </form>
      </table>
     </fieldset>

     <fieldset><legend><small><b>Help</legend>
      <table width=100% align=center> 
       <form action=index.php method=post>
        <tr><td align=center colspan=2><xmp>TEXT EDITING -->   <br> = newline   <u> = underline   <b> = bold   &bull; = bullet</xmp></td></tr>
        <tr><td align=center colspan=2><textarea name=help_text rows=10 cols=75>$control[help_text]</textarea></td></tr>
        <tr><td align=center colspan=2><input type=submit value=Update></td></tr>
       </form>
      </table>
     </fieldset>
    </td>
   </tr>";
  } else { echo "
  <form action=index.php method=post>
       
   <tr><td align=center colspan=2>Please enter your username and password to continue.<br></td></tr>
   <tr><td align=right width=45%>Username: </td><td><input size=20 type=text name=auser></td></tr>
   <tr><td align=right width=45%>Password: </td><td><input size=20 type=password name=apass></td></tr>
   <tr><td align=center colspan=2><br><input type=submit value=Login></td></tr>
  </form>";
   
  } echo "
  </table>
 </td></tr></table>
</body>
</html>";
?>