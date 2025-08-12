<?php
include("./admin/config.php");
include("$include_path/common.php");

mt_srand(make_seed());

$user_id = 0;

if(isset($_POST['submit_rating']) && isset($_POST['user_id']) && 
	($_POST['submit_rating'] >= 0 && $_POST['submit_rating'] <= 10)){
	
	$user_id = (int) $_POST['user_id'];

	if(isset($_SESSION['ra'])){
		$_SESSION['ra'] .= $user_id . ",";
	} else {
		$_SESSION['ra'] = $user_id . ",";
	}

	$rating = (int) $_POST['submit_rating'];
	$rater_id = isset($_SESSION['userid']) ? $_SESSION['userid'] : 0;

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

	$check_ip_query = mysql_query($check_ip_sql) or die(mysql_error());
	$last_rater_ip = @mysql_result($check_ip_query, "0", "rater_ip");
	$last_rater_id = @mysql_result($check_ip_query, "0", "rater_id");
	$last_rated = @mysql_result($check_ip_query, "0", "timestamp");

	$yesterday = date("YmdHis",
			mktime(date("H"), date("i"), date("s"), date("m"), date("d")-1, date("Y")));

	$same_ip = false;
	$too_soon = false;
	$same_user = false;

	if($last_rater_ip == $HTTP_SERVER_VARS['REMOTE_ADDR']) $same_ip = true;
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
				'$_SERVER[REMOTE_ADDR]'
			)
		";

		$is_query = mysql_query($is_sql) or die(mysql_error());

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

		$gs_query = mysql_query($gs_sql) or die(mysql_error());
		$total_ratings = mysql_result($gs_query, 0, "total_ratings");
		$total_points = mysql_result($gs_query, 0, "total_points");

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

		$ps_query = mysql_query($ps_sql) or die(mysql_error());

	}
}

clean_ratings();

if(isset($_POST['page']) && $_POST['page'] == "index"){
	header("Location: $base_url/");
	exit();
} else {
	header("Location: $base_url/?v=$user_id");
	exit();
}

?>