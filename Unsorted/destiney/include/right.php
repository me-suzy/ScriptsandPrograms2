<?php

$file = $cache_path . "/right.htm";

if(@filemtime($file) < mktime(date("H")-1, abs(date("i")), abs(date("s")), date("m"), date("d"), date("Y"))){

$output = <<<FO
<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
FO;

	$top_sql = "
		select
			*
		from
			$tb_user_types
		order by
	";

	$top_sql .= $ml_order_types_by_rand ? " rand()" : " order_by";

	$top_query = mysql_query($top_sql) or die(mysql_error());

	while($top_array = mysql_fetch_array($top_query)){

		$user_type_id = $top_array["id"];
		$user_type = $top_array["user_type"];

		$cml_sql = "
			select
				count(*) as count
			from
				$tb_users
			where
				user_type = '$user_type_id'
			and
				image_status = 'approved'
		";
		$cml_query = mysql_query($cml_sql) or die(mysql_error());
		
		if(mysql_result($cml_query, 0, "count") > 0){

$output .= <<<EOF
<tr>
<td valign="top">
EOF;

			$list_sql = "
				select
					*
				from
					$tb_users
				where
					user_type = '$user_type_id'
				and
					image_status = 'approved'
			";
	
			if($ml_use_min_rating){
				$list_sql .= "
					and
						average_rating >= '$ml_min_rating'
				";
			}

			$list_sql .= "
			order by
				average_rating desc
			";
			
			if($ml_use_max_count){
				$list_sql .= "
					limit
						$ml_count
					";
			}
			$list_query = mysql_query($list_sql) or die(mysql_query());

$content = <<<EOF
<table cellpadding="0" cellspacing="0" border="0" width="100%">
EOF;

			if(mysql_num_rows($list_query)>0){
				
				$i = 0;
				
				while($list_array = mysql_fetch_array($list_query)){

					$alt = $i % 2 ? "alt1" : "alt2";
					$i++;

					$name = substr($list_array["username"], 0, $max_un_length);

$content .= <<<EOF
<tr class="$alt">
<td class="smallregular"><a class="small" href="$base_url/?i=$list_array[id]" target="_top">$name</a></td>
<td class="smallregular" align="right">$list_array[average_rating]</td>
</tr>
EOF;

				}

$content .= <<<EOF
<tr>
<td colspan="2" class="smallregular" align="center"><a class="smallbold" href="$base_url/toplist.php?ut=$user_type_id">$user_type Toplist</a></td>
</tr>
EOF;

			} else {

$content .= <<<EOF
<tr>
<td class="smallregular" align="right">There are none.</td>
</tr>
EOF;

			}

$content .= <<<EOF
</table>
EOF;

			$output .= table($user_type, $content);

$output .= <<<EOF
</td>
</tr>
EOF;

		}
	}

$output .= <<<FO
</table>
FO;

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