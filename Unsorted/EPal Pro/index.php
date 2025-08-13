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


include("admin/include.inc.php");  
echo "
<html>
<head>";
 if ($confirmed_x) { 
  // INSERT ALL DATA AND RETRIEVE ID TO PASS - ADD TXN NUMBER FROM IPN - MATCHED BY DB ID
  $db[id] = 345345;
  list($aa,$bb)= explode('.', $total);  
  if (strlen($bb) < 2) { $total = $total."0"; }
  echo "<meta http-equiv=refresh content='4;URL=https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&item_name=Money&nbsp;Order&business=nixiak@singnet.com.sg&amount=$total&item_number=$db[id]&cancel_return=http://test.baseheadgames.com/?cancel.php&return=http://www.yoursite.com/?ty.php'>"; 
  // ON RETURN PAGE SEND OUT AN EMAIL WITH THE TRANSACTION NUMBER ETC.
 }
 echo "
 <title>EPal Pro - Send Money Orders With PayPal</title>
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
 a:link.main {
        font:bold; 
        font-size:10;
	color: #000000;
	text-decoration: none;
}
a:visited.main {
	font:bold; 
        font-size:10;
	color: #000000;
	text-decoration: none;
}
a:active.main {
	font:bold; 
        font-size:10;
	color: #000000;
	text-decoration: none;
}
a:hover.main {
	font:bold; 
        font-size:10;
	color: #FF0000;
	text-decoration: none;
}

 
 .bottom {
 	 border-bottom:1px solid #000000;
 }     
 .both {
 	 border-bottom:1px solid #000000;
 	 border-top:1px solid #000000;
 }     
 			.verdana09 { font-size: 9px; font-family: Verdana, Arial, Helvetica, Geneva, Swiss, SunSans-Regular }
			.verdana10 { font-size: 10px; font-family: Verdana, Arial, Helvetica, Geneva, Swiss, SunSans-Regular }
</style>
</head>
<body>
 <form action=? method=post>
 <table width=100%>
  <tr>
   <td width=100% height=50>
   
   <div align=center>
     <center>
     <table border=0 cellpadding=0 cellspacing=0 style=border-collapse: collapse bordercolor=#111111 width=640 id=AutoNumber1>
       <tr>
         <td width=100%>&nbsp;<table cellSpacing=0 cellPadding=4 width=640 bgColor=white border=0>
  <tr>
    
    <td vAlign=center width=144>

    <img src=image/logo.gif border=0 width=200 height=39 align=left></td>
    <td align=right>
    <table cellSpacing=0 cellPadding=0 width=100% border=0>
      <tr>
        <td vAlign=bottom align=middle>&nbsp;</td>
        <td align=right><a class=main href=?v=about>About Us</a> |
        <a class=main href=?v=contact>Contact</a><br>
        <br>
        
&nbsp;</td>
      </tr>
    </table>
    </span></td>
</span>
  </tr>
</table>
         </td>
       </tr>
     </table>
     </center>
   </div>
   
   </td>
  </tr>
  <tr>
   <td align=center width=100% height=35 bgcolor=#FFFFFF>

<center>
<span class=verdana09>
<table cellSpacing=0 cellPadding=0 width=100% bgColor=#336699 border=0>
  <tr>
    <td align=middle>
    <table cellSpacing=0 cellPadding=0 border=0>
      <tr>
        <td>
        <csobj w=78 h=30 t=Button>

        <a href=?>

        <img src=image/home1.gif border=0 name=button width=78 height=30></a></a></td>
        <td>
                <a href=?v=send>
                <img src=image/send1.gif border=0 name=button2 width=120 height=30></a></a></td>
        <td>
        
        <a href=?view=request>
        
        <img src=image/request1.gif border=0 name=button3 width=138 height=30></a></a></td>
        <td>
                <a href=?v=shipping>
                <img src=image/price1.gif border=0 name=button4 width=156 height=30></a></a></td>
        <td>
     
        <a href=?v=sell>
     
        <img src=image/sell1.gif border=0 name=button5 width=64 height=30></a></a></td>
        <td>
 
        <a href=?v=help>
 
        <img src=image/help1.gif border=0 name=button6 width=74 height=30></a></a></td>
      </tr>
    </table>
    </td>
  </tr>
</table>
</span></center>

   </td>
  </tr>
   <td align=center>
    <br>
    <table width=600>
     <tr>
      <td colspan=2 align=center bgcolor=#DEEDFD class=bottom>
       <font size=2><b>Send Money Orders to anyone with a mailing address in just moments!</b></font>
      </td>
     </tr>
     <tr>
      <td width=150 valign=top>
       <br>
       <table width=100%>
        <tr bgcolor=#336699><td><b><font color=#FFFFFF>Order Tracking</b></font></td></tr>
        <tr bgcolor=#DEEDFD><td valign=top><br><small>Order Number</small><br><input type=text size=10 name=order_num><input type=image name=check src=image/confirm.gif><br><br></td></tr>
       </table>
       <br><br><br>
       <table width=100% style='border:2 solid #ffffff;'>
        <tr bgcolor=#336699><td style='border:2 solid #ffffff;'><b><font color=#FFFFFF>News</b></font></td></tr>
        <tr bgcolor=#EBEDCC><td style='border:2 solid #ffffff;'><small>Info Item<br><br><br></small></td></tr>
        <tr bgcolor=#F3F5E3><td style='border:2 solid #ffffff;'><small>Info Item<br><br><br></small></td></tr>
        <tr bgcolor=#EBEDCC><td style='border:2 solid #ffffff;'><small>Info Item<br><br><br></small></td></tr>
        <tr bgcolor=#F3F5E3><td style='border:2 solid #ffffff;'><small>Info Item<br><br><br></small></td></tr>
        <tr bgcolor=#EBEDCC><td style='border:2 solid #ffffff;'><small>Info Item<br><br><br></small></td></tr>
       </table>
      </td>
      <td width=450 valign=top>
       <br>";
       if ($v) {echo "
        <table width=100% cellpadding=2 cellspacing=5 style='border:2 solid #336699;'>
         <tr bgcolor=#EBEDCC><td height=25 valign=middle><b>";
          if ($v == 'send')     { echo "Send Money"; }
          if ($v == 'shipping') { echo "Pricing and Shipping"; }
          if ($v == 'sell')     { echo "Sell Online"; }
          if ($v == 'help')     { echo "Help Center"; }
          if ($v == 'about')    { echo "About Us"; }
          if ($v == 'fees')     { echo "Fees"; }
          if ($v == 'legal')    { echo "Service Agreement"; }
          if ($v == 'privacy')  { echo "Privacy Statement"; }
          if ($v == 'contact')  { echo "Contact Information"; }
          echo "</b>
         </td></tr>
         <tr><td>";
          if ($v == 'send') { echo $control[send_money_text]; }
          if ($v == 'shipping') { echo $control[shipping_text]; }
          if ($v == 'sell') { echo $control[sell_text]; }
          if ($v == 'help') { echo $control[help_text]; }
          if ($v == 'about') { echo $control[about_text]; }
          if ($v == 'fees') { echo $control[fees_text]; }
          if ($v == 'legal') { echo $control[legal_text]; }
          if ($v == 'privacy') { echo $control[privacy_text]; }
          if ($v == 'contact') { echo $control[contact_text]; }
          echo "
         </td></tr>
        </table>";
       } else {
        if ($confirmed_x) { $view = 'process'; }
        if ($check_x) { $view = 'query'; }
        elseif ($request_x) { $view = 'request'; }
        elseif ($check_entry_x) { $view = 'check'; }
        elseif ($send && $payment) { $view = 'order'; }
        elseif (!$view) { $view = 'main'; }
        include("$view.php");
       } echo "
      </td>
    </table>
   </td>
  </tr>
  <tr>
   <td width=100% height=20>
   
   </td>
  </tr>
  <tr bgcolor=#336699>
   <td width=100% align=center height=10>
   </td>
  </tr>
  <tr>
   <td width=100% align=center>
   "; include("footer.php"); echo "
   </td>
  </tr>
 </table>
 </form>
</body>
</html>";
function convert($val) {
 $val = number_format($val,2);
 echo "$val";
}
?>