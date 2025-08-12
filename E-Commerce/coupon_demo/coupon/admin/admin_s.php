<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>ADMIN PANEL</title></HEAD>
<BODY leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<TABLE width="775" border="0" align="center" cellpadding="0" cellspacing="0">
  <TR>
    <TD>
      <TABLE width="100%" border="0" cellspacing="0" cellpadding="0">
        <TR>
          <TD align="center" valign="middle"></TD>
        </TR>
      </TABLE>
	  <TABLE width="100%" border="0" cellspacing="0" cellpadding="0">
        <TR>
          
          <TD width="80%" align="center" valign="top"> <BR>
            <TABLE width="95%" height="400" border="1" cellpadding="5" cellspacing="0">
              <TR> 
                <TD height="25" valign="middle" bgcolor="#CCCCCC"><font color="#000000">&nbsp;&nbsp;<FONT size="2" face="Verdana, Arial, Helvetica, sans-serif"><STRONG>ADMIN
<center>Coupon Simple Pro Version 2.02 </STRONG><br><a href="http://repricing.com" target="_blank">Visit or site to check for a new version.</center></a></FONT></font></TD>
              </TR>
              <TR> 
                <TD valign="top">Use the below forms to personalize your coupon. </TD>
              </TR>
<TR> 
                <TD valign="top">
<? 
if (file_exists("inc/data$cptype.php")) {
$current = file("inc/data$cptype.php");
}
include "myvb.php"; 
?>
<?
if ($fr=="1"){echo" 
<table border=1>
<tr><td>
This is form # 1 of 4.<br>
Coupon <u><b>$cptype</b></u> setup.
<font color=\"red\">If you want to update any information in this form then all information must be re-entered.</font>
</td></tr></table>
<p> Enter the information you want displayed on your coupon. 
<b>Text Only. No HTML / Scripts:</b> 
</p>
<p>
<form action=\"change.php\" method=\"post\" name=\"var1\">

<input type=\"hidden\" name=\"C_var\" value=\"1\">
<input type=\"hidden\" name=\"Num\" value=\"1\">
<input type=\"hidden\" name=\"cptype\" value=\"$cptype\">
<input type=\"hidden\" name=\"fr\" value=\"$fr\">


<input type=\"checkbox\" name=\"D_C\" value=\"1\" $current[3]> \"Yes\" Display coupon code.<br>
<input type=\"checkbox\" name=\"tim_er\" value=\"1\" $current[7]> \"Yes\" Turn On Timer.<br>
<input type=\"checkbox\" name=\"x_d\" value=\"1\" $current[13]> \"Yes\" Expire after so many products are sold.<br>
<input type=\"checkbox\" name=\"lateb\" value=\"1\" $current[11]> \"Yes\" Offer Late Bird Special.<br><br>
<select name=\"M_onth\" size=1>
<option value=\"$month\" Selected>$month</option>
<option value=\"February\">February</option>
<option value=\"March\">March</option>
<option value=\"April\">April</option>
<option value=\"May\">May</option>
<option value=\"June\">June</option>
<option value=\"July\">July</option>
<option value=\"August\">August</option>
<option value=\"September\" >September</option>
<option value=\"October\">October</option>
<option value=\"November\">November</option>
<option value=\"December\">December</option>
<option value=\"January\" >January</option>
</select>

<select name=\"D_ay\" size=1>
<option value=\"$sday\"SELECTED>$sday</option>
<option value=\"2,\">2,</option>
<option value=\"3,\">3,</option>
<option value=\"4,\">4,</option>
<option value=\"5,\">5,</option>
<option value=\"6,\">6,</option>
<option value=\"7,\">7,</option>
<option value=\"8,\">8,</option>
<option value=\"9,\">9,</option>
<option value=\"10,\">10,</option>
<option value=\"11,\">11,</option>
<option value=\"12,\">12,</option>
<option value=\"13,\">13,</option>
<option value=\"14,\">14,</option>
<option value=\"15,\">15,</option>
<option value=\"16,\">16,</option>
<option value=\"17,\">17,</option>
<option value=\"18,\">18,</option>
<option value=\"19,\">19,</option>
<option value=\"20\">20,</option>
<option value=\"21,\">21,</option>
<option value=\"22,\">22,</option>
<option value=\"23,\">23,</option>
<option value=\"24,\">24,</option>
<option value=\"25,\">25,</option>
<option value=\"26,\">26,</option>
<option value=\"27,\">27,</option>
<option value=\"28,\">28,</option>
<option value=\"29,\">29,</option>
<option value=\"30,\">30,</option>
<option value=\"31,\">31,</option>
<option value=\"1,\">1,</option>
</select>

<select name=\"Y_ear\" size=1>
<option value=\"$sy\" SELECTED>$sy</option>
<option value=\"2005\">2005</option>
<option value=\"2006\" >2006</option>
<option value=\"2007\">2007</option>
<option value=\"2008\">2008</option>
<option value=\"2009\">2009</option>
<option value=\"2010\">2010</option>
<option value=\"2011\">2011</option>
<option value=\"2012\">2012</option>
<option value=\"2013\">2013</option>
<option value=\"2014\">2014</option>
<option value=\"2015\">2015</option>
<option value=\"2016\">2016</option>
<option value=\"2017\">2017</option>
<option value=\"2018\">2018</option>
<option value=\"2019\">2019</option>
<option value=\"2020\">2020</option>
<option value=\"2021\">2021</option>
<option value=\"2022\">2022</option>
<option value=\"2023\">2023</option>
<option value=\"2024\">2024</option>
<option value=\"2025\">2025</option>
<option value=\"2026\">2026</option>
<option value=\"2027\">2027</option>
<option value=\"2028\">2028</option>
<option value=\"2029\">2029</option>
<option value=\"2030\">2030</option>
<option value=\"2031\">2031</option>
<option value=\"2032\">2033</option>
<option value=\"2034\">2034</option>
<option value=\"2035\">2035</option>
<option value=\"2036\">2036</option>
<option value=\"2037\">2037</option>
<option value=\"2038\">2038</option>
<option value=\"2039\">2039</option>
<option value=\"2040\">2040</option>
</select> Enter expire date if using timer.(Only valid if timer is on above).
<br><br>
<font color=\"#FF0000\"><b><u>Important:</font> Do not leave any space after your last Char in the below boxes.</u></b><br><br>
<input type=\"text\" name= x_ad size=\"4\" value=\"$current[14]\"> Enter # of products to be sold.(Only valid if \"Expire after so many products are sold\" is turned on above). <br>
<input type=\"text\" name= x_cd size=\"4\" value=\"$current[16]\"> Return link security Code. Enter any string of numbers and letters you like. (Example: Yki57s9) 
<br>

<input type=\"text\" name= C_ID size=\"10\" maxlength= 6 value=\"$current[6]\"> Enter Your Coupon Code Here. This what your customer will enter into the coupon. <br>
<input type=\"text\" name= S_p size=\"10\" value=\"$current[4]\"> Enter Your sale price here without the $ sign.<br>
<input type=\"text\" name= pdn size=\"40\" value=\"$current[5]\">Your Product Name.<br>
<input type=\"text\" name= C_t size=\"40\" value=\"$current[0]\"> Enter Your Headline.<br>
<input type=\"text\" name= C_N size=\"40\" value=\"$current[1]\"> Your Name / Company Name.<br>
<input type=\"text\" name= late_url size=\"40\" value=\"$current[12]\"> URL To \"Late Bird Special\". <br>
<input type=\"text\" name= x_url size=\"40\" value=\"$current[15]\"> URL To Your \"Thank You Page\". (Your Download page)
<input type=\"text\" name= R_url size=\"40\" value=\"$current[2]\"> Url where you want user to go if they don't have a coupon code.<br>

<br>
<input type=\"submit\" value=\"Submit\" name=\"var1.C_description\"> <input type=\"reset\" value=\"Clear Form\">
</form>

                  </TD>
              </TR>

              <TR> 
                <TD valign=\"top\"><a href=\"admin.php\">Click Here For Admin Main</a> | <a href=\"cpset.php\">Click Here For Coupon Select Menu</a>
</TD>
              </TR>
</table>";
}
?>

              <TR> 

              <TD valign="top">
<?
if ($fr==2){echo" 
<table border=1>
<tr><td>
This is form # 2 of 4.<br>
Coupon <u><b>$cptype</b></u> setup.
</td></tr></table>
<p> Enter your product and savings information. This information will be displayed on the coupon.
<b>HTML or Text Only. No scripts:</b> 
</p>
<form action=\"change.php\" method=\"post\" name=\"c_des\">

<input type=\"hidden\" name=\"C_description\" value=\"1\" size=\"10\">
<input type=\"hidden\" name=\"cptype\" value=\"$cptype\">
<input type=\"hidden\" name=\"fr\" value=\"$fr\">


<textarea name=\"discription\" ROWS= 5 COLS=60>";}?>
<? if ($fr==2){
if (file_exists("inc/dis$cptype.php")){
 $dis = "inc/dis$cptype.php";

                  $disc=fopen ($dis,"r");

                     $description= fread($disc,filesize($dis));

                   fclose ($disc);

                     echo stripslashes($description);
}

}
?>
<?
if ($fr==2){echo" </textarea>
<br>
<input type=\"submit\" value=\"Submit\" name=\"c_des\"> <input type=\"reset\" value=\"Clear Form\">
</form>
                  </TD>
              </TR>

<TR> 
                <TD valign=\"top\"><a href=\"admin.php\">Click Here For Admin Main</a> | <a href=\"cpset.php\">Click Here For Coupon Select Menu</a>
</TD>
              </TR>

</table>";
}
?>
              

 <TR> 
                <TD valign="top">
<?
if ($fr==3){echo "
<table border=1>
<tr><td>
This is form # 3 of 4.<br>
Coupon <u><b>$cptype</b></u> setup.
</td></tr></table>

<p> Enter the information you want displayed after the customer has entered in the right coupon number, and is taken to the process page to select their payment.<br>
<table width =50% border=\"1\" bgcolor=\"gainsboro\"><tr><td>
<p>Example: <b>Please select one of the payment buttons on the left, for secure processing of your order.</b></p></td></tr></table>
<b>HTML or Text Only. No scripts:</b> 
</p>
<form action=\"change.php\" method=\"post\" name=\"proc\">

<input type=\"hidden\" name=\"C_proc\" value=\"1\" size=\"10\">
<input type=\"hidden\" name=\"cptype\" value=\"$cptype\">
<input type=\"hidden\" name=\"fr\" value=\"$fr\">



<textarea name=\"discription\" ROWS= 5 COLS=60>";}?>



<? if ($fr==3){
if (file_exists("inc/proc$cptype.php")){
 $dis = "inc/proc$cptype.php";

                  $disc=fopen ($dis,"r");

                     $description= fread($disc,filesize($dis));

                   fclose ($disc);

                     echo stripslashes($description);
}
}
?>


<?
if ($fr==3){echo "</textarea>
<br>
<input type=\"submit\" value=\"Submit\" name=\"proc\"> <input type=\"reset\" value=\"Clear Form\">

</form>
                  </TD>
              </TR>
<TR> 
                <TD valign=\"top\"><a href=\"admin.php\">Click Here For Admin Main</a> | <a href=\"cpset.php\">Click Here For Coupon Select Menu</a>
</TD>
</table>";
}
?>

              </TR>
<TR> 
                <TD valign="top">
<?php $urlr= sprintf("%s%s%s","http://",$HTTP_HOST,$REQUEST_URI); 
$newurl = ereg_replace ("admin/admin_s.php", "complete.php", $urlr);
$newurl2 = ereg_replace ("admin/admin_s.php", "index.php", $urlr);
?>
<?
if ($fr==4){
$filename = "inc/data$cptype.php"; 

if (file_exists($filename)) { 
  $vb = file ("inc/data$cptype.php");
} else { 
   echo "<center><font color=\"red\" size =\"4\">You must complete forms one thur three first.<br><br> 
<a href=\"admin.php\">Click Here For Admin Main</a> | <a href=\"cpset.php\">Click Here For Coupon Select Menu</a>
</font></center>"; 
  die();
} 
}
?>
<?
if ($fr==4){include "myvb.php";
}
if ($fr==4){echo "
<table border=1>
<tr><td>
This is form # 4 of 4.<br>
Coupon <u><b>$cptype</b></u> setup.
</td></tr></table>
<p>If you want to update any information in this form then all information must be re-entered.</p>
<p> Enter Payment information below.

</p>
<form action=\"change.php\" method=\"post\" name=\"p_m\">

<input type=\"hidden\" name=\"P_var\" value=\"1\" size=\"10\"><br>
<input type=\"hidden\" name=\"cptype\" value=\"$cptype\">
<input type=\"hidden\" name=\"fr\" value=\"$fr\">

<b>Select Your Payment Providers.</b><br>
<table border=0>
<tr><td width= 33%>
<input type=\"checkbox\" name=\"pp_a\" value=\"1\" $cpt[0]>Acccept PayPal 
</td>
<td width= 33%>
<input type=\"checkbox\" name=\"sp_a\" value=\"1\" $cpt[1]> Accept StormPay
</td>
<td width= 33%>
<input type=\"checkbox\" name=\"cb_a\" value=\"1\" $cpt[2]> Accept ClickBank.
</td>
</tr>
<td width= 33%>
<input type=\"checkbox\" name=\"Ch_2\" value=\"1\" $cpt[10]> Accept 2Checkout.
</td>
<td width= 33%>
<input type=\"checkbox\" name=\"e_g\" value=\"1\" $cpt[11]> Accept E-gold.
</td>
<td width= 33%>
<input type=\"checkbox\" name=\"e_b\" value=\"1\" $cpt[12]> Accept E-bullion .
</td>
</tr></table><br>
<table border=1>
<tr><td width= 50%>
<input type=\"text\" name= \"p_m_a\" size=\"20\" value=\"$cpt[6]\"> PayPal Email.
</td><td width= 50%>
<input type=\"text\" name= \"s_p_a\" size=\"20\" value=\"$cpt[7]\"> StormPay Email.
</td></tr><tr>
<td width= 50%>
<input type=\"text\" name= \"e_g_e\" size=\"20\" value=\"$cpt[16]\"> E-gold Email
</td>
<td width= 50%>
E-gold is for notification of sale.<br>
PayPal / StormPay is your account address.
</td>
</tr></table><br>
<table border=0>
<tr><td width=100%>
<p><b>Note:</b> When setting up your return link in your ClickBank / 2Checkout account the return URL should point to the \"complete.php\" script. You will need to pass on the security variable within the link so it should look something like this:<br> <br>
<b>$newurl?ck=$vb[16]&&cn=$cptype</b> <br>
<br></p>
<input type=\"text\" name= \"pp_on_a\" size=\"20\" value=\"$cpt[5]\"> PayPal Item Number.(Optional).<br>
<input type=\"text\" name= \"pp_sp_l\" size=\"50\" value=\"$newurl\"> Return URL to \"complete.php\".(http://yourdomain.com/coupon/complete.php)<br><br>
<input type=\"text\" name= pp_sp_c_l size=\"50\" value=\"$cpt[4]\"> Cancel URL if offered.<br>
(http://yourdomain.com/cancel.html)<br>
</td></tr></table><br>

<font color=\"#FF0000\">Important:</font> Do not leave any space after your last Char. <br>
<table>
<tr><td>
<input type=\"text\" name=\"ca_2\" size=\"10\" value=\"$cpt[13]\"> 2Checkout Account Number.<br>
</td></tr>
<tr><td>
<input type=\"text\" name= \"cb_N_a\" size=\"10\" value=\"$cpt[8]\"> ClickBank User Name.
</td><td>
<input type=\"text\" name= \"cb_it_a\" size=\"4\" value=\"$cpt[9]\"> ClickBank Item Number.<br>
</td></tr>
<tr><td>
<input type=\"text\" name=\"E_g\" size=\"10\" value=\"$cpt[17]\"> E-Gold Account Number. 
</td><td>
<input type=\"text\" name=\"G_p\" size=\"20\" value=\"$cpt[18]\"> E-Gold Payee Name.<br>
</td></tr>
<tr><td>
<input type=\"text\" name=\"E_b\" size=\"10\" value=\"$cpt[14]\"> E-bullion Account Number. 
</td><td>
<input type=\"text\" name=\"E_p\" size=\"20\" value=\"$cpt[15]\"> E-bullion Payee Name.
</td></tr></table>

<br><br>
<input type=\"submit\" value=\"Submit\" name=\"p_m\"> <input type=\"reset\" value=\"Clear Form\">
</form>
                  </TD>
              </TR>
<TR> 
                <TD valign=\"top\"><a href=\"admin.php\">Click Here For Admin Main</a> | <a href=\"cpset.php\">Click Here For Coupon Select Menu</a>
</TD>
              </TR>

</table>";
}
?>

<?
if ($fr==5){echo "
<table border=1>
<tr><td>
Master Payment Setup.<br>

</td></tr></table>
<p>If you want to update any information in this form then all information must be re-entered.</p>
<p> Enter Payment information below.

</p>
<form action=\"change.php\" method=\"post\" name=\"p_m\">

<input type=\"hidden\" name=\"P_var\" value=\"1\" size=\"10\"><br>
<input type=\"hidden\" name=\"cptype\" value=\"$cptype\">
<input type=\"hidden\" name=\"fr\" value=\"$fr\">

<b>Select Your Payment Providers.</b><br>
<table border=0>
<tr><td width= 33%>
<input type=\"checkbox\" name=\"pp_a\" value=\"1\">Acccept PayPal 
</td>
<td width= 33%>
<input type=\"checkbox\" name=\"sp_a\" value=\"1\"> Accept StormPay
</td>
<td width= 33%>
<input type=\"checkbox\" name=\"cb_a\" value=\"1\"> Accept ClickBank.
</td>
</tr>
<td width= 33%>
<input type=\"checkbox\" name=\"Ch_2\" value=\"1\"> Accept 2Checkout.
</td>
<td width= 33%>
<input type=\"checkbox\" name=\"e_g\" value=\"1\"> Accept E-gold.
</td>
<td width= 33%>
<input type=\"checkbox\" name=\"e_b\" value=\"1\"> Accept E-bullion .
</td>
</tr></table><br>
<table border=1>
<tr><td width= 50%>
<input type=\"text\" name= \"p_m_a\" size=\"20\"> PayPal Email.
</td><td width= 50%>
<input type=\"text\" name= \"s_p_a\" size=\"20\"> StormPay Email.
</td></tr><tr>
<td width= 50%>
<input type=\"text\" name= \"e_g_e\" size=\"20\"> E-gold Email
</td>
<td width= 50%>
E-gold is for notification of sale.<br>
PayPal / StormPay is your account address.
</td>
</tr></table><br>
<table border=0>
<tr><td width=100%>
<p><b>Note:</b> When setting up your return link in your ClickBank / 2Checkout account the return URL should point to the \"complete.php\" script. You will need to pass on the security variable within the link so it should look something like this:<br> <br>
<b>$newurl?ck=$vb[16]&&cn=$cptype</b> <br>
<br></p>
<input type=\"text\" name= \"pp_on_a\" size=\"20\"> PayPal Item Number.(Optional).<br>
<input type=\"text\" name= \"pp_sp_l\" size=\"50\" value=\"$newurl\"> Return URL to \"complete.php\".(http://yourdomain.com/coupon/complete.php)<br><br>
<input type=\"text\" name= pp_sp_c_l size=\"50\" value=\"http://\"> Cancel URL if offered.<br>
(http://yourdomain.com/cancel.html)<br>
</td></tr></table><br>

<font color=\"#FF0000\">Important:</font> Do not leave any space after your last Char. <br>
<table>
<tr><td>
<input type=\"text\" name=\"ca_2\" size=\"10\"> 2Checkout Account Number.<br>
</td></tr>
<tr><td>
<input type=\"text\" name= \"cb_N_a\" size=\"10\"> ClickBank User Name.
</td><td>
<input type=\"hidden\" name= \"cb_it_a\" value=\"\"> <br>
</td></tr>
<tr><td>
<input type=\"text\" name=\"E_g\" size=\"10\"> E-Gold Account Number. 
</td><td>
<input type=\"text\" name=\"G_p\" size=\"20\"> E-Gold Payee Name.<br>
</td></tr>
<tr><td>
<input type=\"text\" name=\"E_b\" size=\"10\"> E-bullion Account Number. 
</td><td>
<input type=\"text\" name=\"E_p\" size=\"20\"> E-bullion Payee Name.
</td></tr></table>

<br><br>
<input type=\"submit\" value=\"Submit\" name=\"p_m\"> <input type=\"reset\" value=\"Clear Form\">
</form>
                  </TD>
              </TR>
<TR> 
                <TD valign=\"top\"><a href=\"admin.php\">Click Here For Admin Main</a> | <a href=\"cpset.php\">Click Here For Coupon Select Menu</a>
</TD>
              </TR>

</table>";
}
?>

<?
if($fr==6){
$pays=file ("inc/pnmastersetup.php");
for ($i=0;$i<count($pays);$i++) { $pays[$i] = trim($pays[$i]);}
}
if($fr==6){echo"
<table border=1>
<tr><td>

Coupon <u><b>$cptype</b></u> setup.
</td></tr></table>
<br>Do you want to use your \"Master Payment Type\"?<br>
<b>Select no</b> if you have not setup your \"Master Payment Type\".<br><br>
<FORM action=\"admin_s.php\" method=\"post\">
<input type=\"hidden\" name=\"pp_a\" value=\"$pays[0]\">
<input type=\"hidden\" name=\"sp_a\" value=\"$pays[1]\">
<input type=\"hidden\" name=\"cb_a\" value=\"$pays[2]\"> 
<input type=\"hidden\" name=\"Ch_2\" value=\"$pays[10]\">
<input type=\"hidden\" name=\"e_g\" value=\"$pays[11]\">
<input type=\"hidden\" name=\"e_b\" value=\"$pays[12]\"> 
<input type=\"hidden\" name= \"p_m_a\" value=\"$pays[6]\">
<input type=\"hidden\" name= \"s_p_a\" value=\"$pays[7]\"> 
<input type=\"hidden\" name= \"e_g_e\" value=\"$pays[16]\">
<input type=\"hidden\" name=\"ca_2\" value=\"$pays[13]\"> 
<input type=\"hidden\" name= \"cb_N_a\" value=\"$pays[8]\"> 
<input type=\"hidden\" name=\"E_g\" value=\"$pays[17]\"> 
<input type=\"hidden\" name=\"G_p\" value=\"$pays[18]\"> 
<input type=\"hidden\" name=\"E_b\" value=\"$pays[14]\"> 
<input type=\"hidden\" name=\"E_p\" value=\"$pays[15]\">
<input type=\"hidden\" name=\"pp_sp_c_l\" value=\"$pays[4]\">
<input type=\"hidden\" name=\"pp_on_a\" value=\"$pays[5]\">
<input type=\"hidden\" name=\"pp_sp_l\" value=\"$pays[3]\">
<input type=\"hidden\" name=\"cptype\" value=\"$cptype\">
<INPUT TYPE=\"radio\" NAME=\"fr\" Value=\"y\" checked>Yes&nbsp;&nbsp;<br>
<INPUT TYPE=\"radio\" NAME=\"fr\" Value=\"4\">No&nbsp;&nbsp;<br>
<input type=\"text\" name=\"cb_it_a\" size=\"4\"> If Using ClickBank In your \"Master Payment\" Enter Product ID <br>

<br><input type=\"submit\" value=\"Submit\">
</FORM>";
}
?>
<?
if ($fr=="y"){
$vb = file ("inc/data$cptype.php");
include "p_update.php";
}

 if ($fr=="y"){
echo "<p>The setup is completed.</p>
<p> To call this coupon the link should look like this:</p>
<p><a href=\"$newurl2?cn=$cptype\" target=\"_blank\">$newurl2?cn=$cptype</a>
</p>
<p><b>Note:</b> When setting up your return link in your ClickBank / 2Checkout account the return URL should point to the \"complete.php\" script. You will need to pass on the security variable within the link so it should look something like this:<br> <br>
<b>$newurl?ck=$vb[16]&&cn=$cptype</b> <br>
<p> Please test your coupon before turning it loss on your customers</p>

<br>

                  </TD>
              </TR>

<TR> 
                <TD valign=\"top\"><a href=\"admin.php\">Click Here For Admin Main</a> | <a href=\"cpset.php\">Click Here For Coupon Select Menu</a>
</TD>
              </TR>";
}
?>

<?
if ($fr==select){echo" 
<table border=1>
<tr><td>
Setup Completed<br>
Coupon <u><b>$cptype</b></u>.
</td></tr></table>
<p>The setup is completed.</p>
<p> To call this coupon the link should look like this:</p>
<p><a href=\"$newurl2?cn=$cptype\" target=\"_blank\">$newurl2?cn=$cptype</a>
</p>
<p> Please test your coupon before turning it loss on your customers</p>

<br>

                  </TD>
              </TR>

<TR> 
                <TD valign=\"top\"><a href=\"admin.php\">Click Here For Admin Main</a> | <a href=\"cpset.php\">Click Here For Coupon Select Menu</a>
</TD>
              </TR>

</table>";
}
?>




<TR> 
                <TD valign="top">
<b><center>This script is &#169; 2005, by Steven Smith repricing.com</center></b>


                  </TD>
              </TR>



            </TABLE></TD>
        </TR>
      </TABLE>
      <TABLE width="100%" border="0" cellspacing="0" cellpadding="0">
        
        <TR>
          <TD align="center" valign="middle">
            
          </TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
</TABLE>
</BODY>
</HTML>