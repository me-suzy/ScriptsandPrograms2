<?php

$filter="N";

if ($_SERVER["HTTP_X_FORWARDED_FOR"]){
    $proxy=$_SERVER["REMOTE_ADDR"];
    $filter_ip=$_SERVER["HTTP_X_FORWARDED_FOR"];
}
else {
    $filter_ip=$_SERVER["REMOTE_ADDR"];
    $proxy="";
}

$filter_url="";
$filter_cntry="";
$filter_method=$_SERVER["REQUEST_METHOD"];
$filter_client=$_SERVER["HTTP_USER_AGENT"];

$sql=@mysql_query("SELECT filter_url_default FROM cjoverkill_settings") OR
  print_error(mysql_error());
$tmp=@mysql_fetch_array($sql);
extract($tmp);

// Stage 1 --> Filter per IP and request method
$sql1=@mysql_query("SELECT COUNT(*) AS cnt FROM
		     cjoverkill_filter_method, cjoverkill_filter_ip WHERE
		     (INET_ATON('$filter_ip')>=cjoverkill_filter_ip.ip_from AND
		      INET_ATON('$filter_ip')<=cjoverkill_filter_ip.ip_to) OR
		     (cjoverkill_filter_method.method LIKE '%$filter_method%' AND
		      cjoverkill_filter_method.allow!='1')
		     ") OR
  print_error(mysql_error());
$tmp=@mysql_fetch_array($sql1);
extract($tmp);
if ($cnt!=0){
    header("Location: $filter_url_default");
    exit;
}

// Stage 2 --> Filter by client
$sql2=@mysql_query("SELECT client FROM cjoverkill_filter_client") OR
  print_error(mysql_error());
while ($tmp2=@mysql_fetch_array($sql2)){
    extract($tmp2);
    $val= ".?".$client.".?";
    if (eregi ($val, $filter_client)) {
	header("Location: $filter_url_default");
	exit;
    }
}

// Stage 3 --> Filter per country
$sql3=@mysql_query("SELECT cjoverkill_filter_country.url AS filter_url
		     FROM cjoverkill_filter_country, cjoverkill_filter_base WHERE
		     INET_ATON('$filter_ip')>=cjoverkill_filter_base.ip_from AND
		     INET_ATON('$filter_ip')<=cjoverkill_filter_base.ip_to AND
		     cjoverkill_filter_base.c2code=cjoverkill_filter_country.c2code AND
		     cjoverkill_filter_country.filter='1'
		     ") OR
  print_Error(mysql_error());
$tmp=@mysql_fetch_array($sql3);
if (@mysql_num_rows($sql3)>0) {
    extract($tmp);
    header("Location: $filter_url");
    exit;
}
    

?>
