<?
$check = mysql_fetch_array(mysql_query("select * from transactions where pp_trans_id = '$order_num'"));
echo "
<table width=100% cellpadding=2 cellspacing=5 style='border:2 solid #336699;'>
 <tr bgcolor=#EBEDCC><td height=25 valign=middle><b>Order Confirmation</b></td></tr>";
 if ($check[id] > 0) { echo "
 <tr><td><b>Your order has been successfully processed and is on route.</b><br><br>
 Order Details:<br>
 Transaction ID: #$check[pp_trans_id]<br>
 Money Order Amount: $$check[base_amount]<br>
 Total Payment Amount: $$check[base_total]<br>
 Send to:<br>$check[r_name]<br>$check[r_address]<br>$check[r_city]<br>$check[r_state]<br>$check[r_zip]<br>$check[r_country]<br><br>
 Send From:<br>$check[s_name] ($check[s_email])<br>$check[s_address]<br>$check[s_city]<br>$check[s_state]<br>$check[s_zip]<br>$check[s_country]<br>$check[s_phone]<br><br>
 Extras:<br>Auction Info: $check[o_auction_site]<br>Auction Item Info: $check[o_item_num]<br>Sender Auction ID: $check[o_id]<br>Auction Description: $check[o_description]
 </td></tr>";
 } else { echo "
 <tr><td><b>There are no orders with that transaction ID.
 </td></tr>";
 } echo "
</table>";
?>