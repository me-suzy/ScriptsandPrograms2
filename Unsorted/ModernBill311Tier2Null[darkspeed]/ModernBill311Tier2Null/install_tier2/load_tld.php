<ul>
<?
$stop=NULL;
$result = mysql_query("INSERT INTO tld_config (tld_id, tld_extension, tld_name, tld_whois_server, tld_whois_response, tld_accepted, tld_auto_search, tld_transfer, tld_1y, tld_2y, tld_3y, tld_4y, tld_5y, tld_6y, tld_7y, tld_8y, tld_9y, tld_10y, registrar_id, pack_id) VALUES (1,'com','.com (USA)','whois.internic.net','No Match',2,1,'0.00','15.00','30.00','45.00','60.00','75.00','90.00','105.00','120.00','135.00','150.00',5,3)");
$result = mysql_query("INSERT INTO tld_config (tld_id, tld_extension, tld_name, tld_whois_server, tld_whois_response, tld_accepted, tld_auto_search, tld_transfer, tld_1y, tld_2y, tld_3y, tld_4y, tld_5y, tld_6y, tld_7y, tld_8y, tld_9y, tld_10y, registrar_id, pack_id) VALUES (2,'net','.net (USA)','whois.internic.net','No Match',2,1,'0.00','10.00','20.00','30.00','40.00','50.00','60.00','70.00','80.00','90.00','100.00',5,3)");
$result = mysql_query("INSERT INTO tld_config (tld_id, tld_extension, tld_name, tld_whois_server, tld_whois_response, tld_accepted, tld_auto_search, tld_transfer, tld_1y, tld_2y, tld_3y, tld_4y, tld_5y, tld_6y, tld_7y, tld_8y, tld_9y, tld_10y, registrar_id, pack_id) VALUES (3,'org','.org (USA)','whois.internic.net','No Match',2,1,'0.00','5.00','10.00','15.00','20.00','25.00','30.00','35.00','40.00','45.00','50.00',2,3)");
$result = mysql_query("INSERT INTO tld_config (tld_id, tld_extension, tld_name, tld_whois_server, tld_whois_response, tld_accepted, tld_auto_search, tld_transfer, tld_1y, tld_2y, tld_3y, tld_4y, tld_5y, tld_6y, tld_7y, tld_8y, tld_9y, tld_10y, registrar_id, pack_id) VALUES (4,'info','.info (USA)','whois.afilias.info','NOT FOUND',2,1,'0.00','20.00','40.00','60.00','80.00','100.00','0.00','140.00','160.00','180.00','200.00',2,3)");
echo "<li>--> <font color=blue>tld_config <b>OK</b></font>";
?>
</ul>
<br>