<?

/*
 * $Id: top.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

switch($s){
	case "f" :
		$title = "Top Girls";
		$tl_count = $girl_count;
		break;
	default : 
		$title = "Top Guys";
		$tl_count = $guy_count;
}

$sql = "
	select
		*
	from
		$tb_users
	where
		sex = '$s'
	and
		image_status = '1'
	order by
		average_rating desc
	limit
		0, $tl_count
";

$query = sql_query($sql);

$a=0; $b=1; $c=1;

$content = <<<EOF
<table cellpadding="0" cellspacing="0" border="0" width="100%">
EOF;

if(sql_num_rows($query)>0){

while($array = sql_fetch_array($query)){

$name = substr($array["username"], 0, $max_un_length);

$content .= <<<EOF
<tr>
<td class="regular"><a href="$base_url/index.php?$sn=$sid&amp;show=view&amp;s=$s&amp;sr=$a&amp;pp=$c&amp;cp=$b" target="_top">$name</a></td>
<td class="regular" align="right">$array[average_rating]</td>
</tr>
EOF;
		
$a++; $b++;
}

} else {

$content .= <<<EOF
<tr>
<td class="regular" align="right">There are none.</td>
</tr>
EOF;

}

$content .= <<<EOF
</table>
EOF;

$final_output .= table($title, $content);

/*
 * $Id: top.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>