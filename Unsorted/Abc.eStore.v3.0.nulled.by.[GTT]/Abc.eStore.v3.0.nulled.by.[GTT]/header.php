<?php

include_once ("includes/start.php");

$PHP_SELF = $_SERVER['PHP_SELF'];

extract( $_GET );
extract( $_POST );
	
// Hits

	// servers year/month (YY/MM)
	$monthid = date("ym");
	
	// see if row of this month exists 
	$select = mysql_query("select * from ".$prefix."store_stats where date='$monthid'");
	$result = mysql_fetch_array($select);
	$count_result = mysql_num_rows($select);
	
	// if not make row
	if( $count_result == 0 )
		$insert = mysql_query("insert into ".$prefix."store_stats (date, hits) values ('$monthid', '1')");
	
	// if it does exist update database
	if( $count_result == 1 )
	{	
		$select = mysql_query("select * from ".$prefix."store_stats where date='$monthid'");
		$row = mysql_fetch_array($select);
		if( $row )
			$old_hits = $row["hits"];
	
		$new_hits = $old_hits + 1;
		$sql_update = "update ".$prefix."store_stats set hits='$new_hits' where date='$monthid'";
	    $result = mysql_query( $sql_update );
	}
	
	// create hits variable
	$hits_total = mysql_query("SELECT sum(hits) as q FROM ".$prefix."store_stats");
	$row = mysql_fetch_array($hits_total);
	$quan_hits = $row['q'];
	
//

$lng['site_name'] = $site_name;

// Delete sessions more than 2 days old

$today_date = date("Ymd");
$expired_date = $today_date - 2;
mysql_query( "DELETE FROM ".$prefix."store_shopping WHERE date <= '$expired_date'");

$cart = new Cart;

// Todays date/time etc...

$date = date("l j, F Y");

$lng['site_url'] = $site_url;

// Processing templates

$tmpl = new Template ( "html/header.html" );

// languages

$languages = array();

if( is_array( $_languages ) ) {
	
	foreach($_languages as $key=>$value)
		$languages[] = array ( 'name'=>$value, 'value'=>$value );
	
	if ( sizeof ( $languages ) > 1 )
		$lng['languages_select'] = 1;
	else	$lng['languages_select'] = "";
		
}
else	$lng['languages_select'] = "";
	
$tagsel = new TagSelect ($languages, "new_lng", "onchange=\"document.forms['lng_form'].submit();\"");
$tagsel->SetName('name');
$tagsel->SetValue('value');
$tagsel->SetSelected( $sys_lng );
$tmpl->tag( $tagsel );

//

if ( isset ( $sys_charset ) )
	$lng['charset'] = $sys_charset;
else	$lng['charset'] = "'iso-8859-1'";

$tmpl -> param ( 'lng', array ($lng) );
$tmpl -> param ( 'design_dir', "design/" . $design_directory . "/" );

echo $tmpl -> parse();

if( $online == "N" ) {
	echo "<div align=\"center\"><br>" . $offmsg . "</div>";
	exit;
}

if( isset ( $ShoppingCart ) ) {
	if( $ShoppingCart )
		$session = $ShoppingCart;
	else	$session="";
}
else	$session="";

?>