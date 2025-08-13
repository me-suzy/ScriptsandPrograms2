<?
mysql_query("insert into transactions values(null,$payment,$fee,$shipping,$total,'$method','$r_name','$r_address','$r_city','$r_state','$r_zip','$r_country','$r_email','$s_name','$s_email','$s_address','$s_city','$s_state','$s_zip','$c_country','$s_phone','$o_auction_site','$o_item_num','$o_id','$o_description','','','','','',$time)")or die(mysql_error());
echo "
     <table width=100% cellpadding=2 cellspacing=5 style='border:2 solid #336699;'>
      <tr bgcolor=#EBEDCC><td height=25 valign=middle><b>Processing Order....</b></td></tr>
      <tr><td>You are now being redirected to PayPal.com to process your order...<br><br>
      Please wait.</td></tr>
     </table> 
";
?>