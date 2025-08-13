<?  
include("include.inc.php");  
if ($admin_cookie && $admin_cookie == $control[admin_cookie]) {
 if ($report=='days') { $find = mysql_query("select * from transactions where time > $time-2592000 order by id desc"); }
 if ($report=='date') { $find = mysql_query("select * from transactions order by time desc limit 50"); }
 if ($report=='total') { $find = mysql_query("select * from transactions order by base_total desc limit 50"); }
 if ($report=='alphas') { $find = mysql_query("select * from transactions group by s_email order by s_email desc limit 50"); }
 if ($report=='alphar') { $find = mysql_query("select * from transactions group by r_name order by r_name desc limit 50"); }
 echo "
 <html>
 <head>
 <style>
  <title>Money Order's For You</title>
  <style>
  table { border-collapse:collapse; 
         font-size:10;
         font-family:Verdana;
        }
 </style>
 </head>
 <body>
 <table width=800 align=center>";
 $title=1;
 while ($list=mysql_fetch_array($find)) { 
  if ($cnt==50) { $title=1; }
  if ($title==1) { $title = 0; $cnt=0;
   echo "<tr bgcolor=#aaaaaa><td align=center>ID</td><td align=center>MO Amount</td><td align=center>MO Fee</td><td align=center>Shipping Fee</td><td align=center>Shipping Method</td><td align=center>Recipient Name</td><td align=center>Sender Name</td><td align=center>Sender Email</td><td align=center>Tran. Status</td></tr>";
  }
  echo "<tr><td align=center>$list[id]</td><td align=center>$list[base_amount]</td><td align=center>$list[base_fee]</td><td align=center>$list[base_shipping]</td><td align=center>$list[ship_method]</td><td align=center>$list[r_name]</td><td align=center>$list[s_name]</td><td align=center>$list[s_email]</td><td align=center>$list[pp_status]</td></tr>";
  $cnt++;
 }
 echo "</table>
 </html>";
}
?>



