<?php

$file = $cache_path . "/site_menu.htm";

if(@filemtime($file) < mktime(date("H")-1, abs(date("i")), abs(date("s")), date("m"), date("d"), date("Y"))){

$content = <<<EOF
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
  <td class="regular" align="right" valign="top" nowrap><a href="$base_url/?s=-1" target="_top">Show All</a>&nbsp;<br>
EOF;

	$f_and = "and ( " . get_gender_types_sql("f") . ")";
	$f_sql = "
		select
			count(*) as count
		from
			$tb_users
		where
			image_status = 'approved'
		$f_and
	";
	$f_query = mysql_query($f_sql) or die(mysql_error());
	if(mysql_result($f_query, 0, "count") > 0){

$content .= <<<EOF
<a href="$base_url/?s=f" target="_top">Show Babes</a>&nbsp;<br>
EOF;
	
	}

	$m_and = "and ( " . get_gender_types_sql("m") . ")";
	$m_sql = "
		select
			count(*) as count
		from
			$tb_users
		where
			image_status = 'approved'
		$m_and
	";
	$m_query = mysql_query($m_sql) or die(mysql_error());
	if(mysql_result($m_query, 0, "count") > 0){

$content .= <<<EOF
<a href="$base_url/?s=m" target="_top">Show Dudes</a>&nbsp;<br>
EOF;
	
	}

	$sm_sql = "
		select
			*
		from
			$tb_user_types
		order by
	";

	$sm_sql .= $ml_order_types_by_rand ? " rand()" : "order_by";

	$sm_query = mysql_query($sm_sql) or die(mysql_error());
	if(mysql_num_rows($sm_query)){
		while($sm_array = mysql_fetch_array($sm_query)){

			$c_sql = "
				select
					count(*) as count
				from
					$tb_users
				where
					user_type = '$sm_array[id]'
				and
					image_status = 'approved'
			";
			$c_query = mysql_query($c_sql) or die(mysql_error());

			if(mysql_result($c_query, 0, "count") > 0){
			
$content .= <<<EOF
<a href="$base_url/?s=$sm_array[id]">$sm_array[user_type]</a>&nbsp;<br>
EOF;

			}
		}
	}

$content .= <<<EOF
<br>
<a href="$base_url/forums.php">Web Forums</a>&nbsp;
<br>
<a href="$base_url/comments.php">View Comments</a>&nbsp;
EOF;

if($show_graphs){
$content .= <<<EOF
<br>
<a href="$base_url/stats/index.php">Site Statistics</a>&nbsp;
EOF;
}

$content .= <<<EOF
<br>
<a href="$base_url/search.php">Member Search</a>&nbsp;
<br>
<a href="$base_url/lost.php" target="_top">Lost Password</a>&nbsp;
<br>
<a href="$base_url/faq.php">Site FAQ</a>&nbsp;
<br><br>
EOF;

if(isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == "destiney"){
$content .= <<<EOF
<a class="bold" href="$base_url/buy.php">Buy Destiney.com!</a>&nbsp;<br><br>
<span class="bold">Other Cool Sites:&nbsp;</span><br>
<a href="http://www.esimetrics.com/affiliates.php?aid=2" target="_blank">>>> Esimetrics <<<</a>&nbsp;
<br>
<a href="http://www.stockfun.com/r.php?key=f1597633fc" target="_blank">>>> Stock Fun <<<</a>&nbsp;
<br>	
&nbsp;<a href="http://www.jockstocks.com?refid=855" target="_blank">>>> Jock Stocks <<<</a>&nbsp;
<br>	
<a href="http://ramalion.org/" target="_blank">>>> Rama Lion <<<</a>&nbsp;
<br>	
EOF;
}

$content .= <<<EOF
<br>
</td>
</tr>
</table>
EOF;

	$output = table("Site Menu", $content);

	$output = eregi_replace("\n", "", $output);
	$output = eregi_replace("\t", "", $output);

	if($fp = fopen($file, 'w')) fwrite($fp, $output);
	fclose($fp);

} else {

	if($fp = fopen($file, 'r')){
		$output = fread($fp, filesize ($file));
		fclose ($fp);
	}

}

$final_output .= final_output($output);

?>