<?

/*
 * $Id: rate.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

$content = "";

if($s == "m") $sex = "Guys";
else $sex = "Girls";

$title = "View the " . $sex;

$total_users = 1;

if(!isset($sing)) $total_users = get_total_users($s);

if($total_users > 0){

$nav_url = "$base_url/index.php?s=" . $s . "&amp;show=view&amp;";

$nav = nav_links($total_users, $pp, $np, $cp, $nav_url) . " " . $sex;

$nav = <<<EOF
<span class="regular">$nav</span>
EOF;

} else {

$nav = <<<EOF
<table cellpadding="15" cellspacing="0" border="0" width="100%">
<tr>
	<td align="center" class="regular">Sorry there aren't any yet.</td>
</tr>
</table>
EOF;

}

$final_output .= table($title, $nav);

$content = "";

if(isset($user_id)) $id = $user_id;

$rate_sql = "
	select
		*
	from
		$tb_users
	where
		id = '$id'
";

$rate_query = sql_query($rate_sql);
$rate_array = sql_fetch_array($rate_query);

$title = "Rate " . $rate_array["username"];

if(session_is_registered("userid")) $rater_id = $userid;
else $rater_id = 0;

if(isset($submit_rating)){
	if($rating > 10) $rating = 0;
	$check_ip_sql = "
		select
			*
		from
			$tb_ratings
		where
			user_id = '$user_id'
		order by
			timestamp desc
		limit
			0, 1
	";

	$check_ip_query = sql_query($check_ip_sql);
	$last_rater_ip = sql_result($check_ip_query, "0", "rater_ip");
	$last_rater_id = sql_result($check_ip_query, "0", "rater_id");
	$last_rated = sql_result($check_ip_query, "0", "timestamp");

	$yesterday = date("YmdHis",mktime(date("H"),date("i"),date("s"),date("m"),date("d")-1,date("Y")));

	$same_ip = false;
	$too_soon = false;
	$same_user = false;

	if($last_rater_ip == $REMOTE_ADDR) $same_ip = true;
	if($last_rated > $yesterday) $too_soon = true;
	if($user_id == $rater_id) $same_user = true;

	if(!$same_user && (!$same_ip || !$too_soon)){

		$rating_accepted = true;
			
		$is_sql = "
			insert into $tb_ratings (
				id,
				user_id,
				rating,
				rater_id,
				rater_ip
			) values (
				'',
				'$user_id',
				'$rating',
				'$rater_id',
				'$REMOTE_ADDR'
			)
		";

		if($is_query = sql_query($is_sql)){
			
			$gs_sql = "
				select
					total_ratings,
					total_points,
					average_rating
				from
					$tb_users
				where
					id = '$user_id'
			";

			$gs_query = sql_query($gs_sql);
			$total_ratings = sql_result($gs_query, 0, "total_ratings");
			$total_points = sql_result($gs_query, 0, "total_points");

			$total_ratings++;
			$total_points += $rating;
			$average_rating = $total_points / $total_ratings;

			$ps_sql = "
				update
					$tb_users
				set
					total_ratings = '$total_ratings',
					total_points = '$total_points',
					average_rating = '$average_rating'
				where
					id = '$user_id'
			";

			$ps_query = sql_query($ps_sql);
			$message = "Rating Accepted, Thank You!";
		} else $message = "Rating was not recorded.  Database error.";
	}
	
	if(!isset($rating_accepted))
		$message = "Your rating was not recorded.  Our records indicate you have<br />rated this picture recently.  Come back later to rate it again.";

	if($user_id == $rater_id)
		$message = "Your rating was not recorded.  You are not allowed to rate your own picture.";

$content .= <<<EOF
<table border="0" cellpadding="5" cellspacing="0" width="100%">
<tr>
<td width="100%" class="regular" align="center"><br />$message<br /><br />
EOF;

if(isset($sing) and $sing > 0){
$content .= <<<EOF
<a href="$base_url/index.php?s=$s&amp;show=view&amp;$sn=$sid&amp;sing=$sing">Click Here to Continue</a>
EOF;
} else {
$content .= <<<EOF
<a href="$base_url/index.php?s=$s&amp;show=view&amp;$sn=$sid&amp;sr=$sr&amp;pp=$pp&amp;cp=$cp">Click Here to Continue</a>
EOF;
}

$content .= <<<EOF
<br /><br /></td>
</tr>	
</table>
EOF;

} else {

$img_src = get_image($id);

$content .= <<<EOF
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
	<td class="regular">
EOF;

if(!isset($sing)) $sing = 0;

$content .= profile_bar($show, $sing, $s, $rate_array["id"]);

$content .= <<<EOF
</td>
</tr>
</table>
<table cellpadding="1" cellspacing="0" border="0" bgcolor="black">
<tr>
<td bgcolor="black"><img src="$img_src"></td>
</tr>
</table>
EOF;

$final_output .= table($title, $content);

$title = "Please choose a rating";

$content = <<<EOF
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr><form method="post" action="$base_url/index.php?$sn=$sid&amp;show=rate">
<td width="100%" class="bold">
<br />
<blockquote>
<table cellpadding="3" cellspacing="0" border="0">
<tr>
<td class="regular" colspan="2"><a name="rate" class="regular">Rating:</a></td>
</tr>
EOF;

$x=10;
while($x >= 0){

$content .= <<<EOF
<tr>
<td valign="middle" class="regular">
<input type="radio" name="rating" value="$x"
EOF;

if($x==5){
$content .= <<<EOF
 checked="checked"
EOF;
}

$content .= <<<EOF
 />
</td>
<td valign="middle" class="regular">$x
EOF;

if($x==10) $content .= " - Awesome!";
if($x==5) $content .= " - Fair...";
if($x==0) $content .= " - Worst";

$content .= <<<EOF
</td>
</tr>
EOF;

$x--;
}

$content .= <<<EOF
<tr>
<td align="center" colspan="2">
<br />
<input type="submit" name="submit_rating" value=" Submit Rating -> " />
</td>
</tr>
</table>
</blockquote>
</td>
</tr>
<input type="hidden" name="rater_id" value="$rater_id">
<input type="hidden" name="user_id" value="$id">
<input type="hidden" name="cp" value="$cp">
<input type="hidden" name="pp" value="$pp">
<input type="hidden" name="sr" value="$sr">
<input type="hidden" name="s" value="$s">
EOF;

if($sing > 0){
$content .= <<<EOF
<input type="hidden" name="sing" value="$sing">
EOF;
}

$content .= <<<EOF
</form>
</table>
EOF;
}

$final_output .= table($title, $content);

/*
 * $Id: rate.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>