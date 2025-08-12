<?PHP

include "config.php";
include "updateonline.php";

$page_name = "Printing Topic";

	$mtime = microtime();

	$mtime = explode(" ",$mtime);

	$mtime = $mtime[1] + $mtime[0];

	$starttime = $mtime;



// Need to make sure the user has access to view this page

	$top_t_result = mysql_query("select * from topics where id='". $_GET[id] ."'");

	$row = mysql_fetch_array($top_t_result);

	$top_f_id = $row['fid'];

	$top_t_title = $row['title'];


	$top_f_result = mysql_query("select * from forums where id='$top_f_id'");

	$row = mysql_fetch_array($top_f_result);

	$top_c_id = $row['cid'];

	$top_f_sub = $row['subforum'];


	if($top_f_sub == 1){

		$top_f_result = mysql_query("select * from forums where id='$top_c_id'");

		$row = mysql_fetch_array($top_f_result);

		$top_c_id = $row['cid'];

	}


	$top_f_name = $row['name'];


	$top_c_result = mysql_query("select * from categories where id='$top_c_id'");

	$row = mysql_fetch_array($top_c_result);

	$top_c_name = $row['name'];

	$cat_access_level = $row['level_limit'];

	updateonline($_GET['id'],$top_f_id,'0',$_userid,$_username,$page_name);



if((isset($_userid)) && ($_userlevel == 1)){

	$forum_mod_sql = mysql_query("SELECT * FROM users WHERE id='$_userid'");

				$mods_row = mysql_fetch_array($forum_mod_sql);

	$mod_forums = $mods_row['forum_mod'];

	$mod_forums = explode(",", $mod_forums);

	foreach($mod_forums as $fm_id){

		if($fm_id == $top_f_id){
			$is_moderator = TRUE;
		}
	}
}
//////////////////////////


?>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title>Print Topic - (Powered by EKINtemplate)</title>
		<meta name="robots" content="index,follow">
		<style type="text/css">
			body, table td, td{
				margin-top: 4px;
				color: #2F2F2F; 
				font-family: verdana,sans-serif; 
				font-size:10px;
				}
			
			a{
				margin: 0; 
				color: #005AB6; 
				font-family: verdana,sans-serif; 
				font-size:10px;
				font-weight: normal;
				text-decoration: none;
				}
			
			a:hover{
				margin: 0;
				color: #000000;
				font-family: verdana,sans-serif;
				font-size:10px;
				font-weight: normal;
				text-decoration: underline;
				}

			.grey_border {
				border: 1px solid #c3c3c3;
				padding: 0;
			}

			.grey_title {
				color: #005AB6;
				font-weight: bold;
				font-size: 16px;
			}
			
			.grey_info {
				border: none;
				font-weight:none;
				border-top: 0px;
				border-left: 0px;
				border-right: 0px;
				padding: 5;
				padding-left: 15;
				padding-top: 0;
			}
			
			.grey_content {
				padding: 5;
			}
			
			.grey_top {
				padding: 2;
				background-color: #FFFFFF;
				border: none;
				border-bottom: 1px solid #dcdcdc;
			}

			.grey_bottom {
				padding: 1;
				background-color: #fdfdfd;
				border: none;
				border-top: 1px solid #dcdcdc;
			}
			.topic_border {
				border: 1px solid #a5b5dd;
				padding: 0;
			}

			.topic_title {
				color: #005AB6;
				font-weight: bold;
				font-size: 16px;
			}
			
			.topic_info {
				border: none;
				font-weight:none;
				border-top: 0px;
				border-left: 0px;
				border-right: 0px;
				padding: 5;
				padding-left: 15;
				padding-top: 0;
			}
			
			.topic_content {
				padding: 5;
				background-color: #FFFFFF;
			}
			
			.topic_top {
				padding: 2;
				background-color: #f6f9ff;
				border: none;
				border-bottom: 1px solid #b9c6e6;
			}

			.topic_bottom {
				padding: 1;
				background-color: #f6f9ff;
				border: none;
				border-top: 1px solid #dcdcdc;
			}

			.redtable {
				background-color: #F4E1E1;
				border: 1px solid #C19999;
			}
			
			.redtable_content {
				padding: 5;
			}
			
			.redtable_header {
				border: 1px solid #C19999;
				background-color: #EBD7D7;
				border-top: 0px;
				border-left: 0px;
				border-right: 0px;
				padding: 5;
			}
			.quotetop{
				background-color: #efefef;
				border: 1px dotted #c5c5c5;
				border-bottom: 0;
				color: #000;
				font-weight: bold;
				padding: 2px;
				font-size: 10px;
			}
			.quotemain{
				border: 1px dotted #c5c5c5;
				border-top: 0;
				color: #465584;
				padding: 4px;
			}
		</style>
		<link rel="shortcut icon" href="http://www.ekinboard.com/forums/v1/favicon.ico">
	</head>
	<body>
		<center>
		<table width="95%" cellpadding="0" cellspacing="0">
			<tr>
				<td>
<?PHP

if(($_userlevel >= 2) || ($is_moderator == TRUE) || ($cat_access_level <= $_userlevel)){

$page_result = mysql_query("select * from topics where id='". $_GET[id] ."'"); 

$numrows = mysql_num_rows($page_result);

if($numrows!=0){

	$t_result = mysql_query("select * from topics where id='". $_GET[id] ."'"); 

	$t_count = mysql_num_rows($t_result);

	$row=mysql_fetch_array($t_result);



	$t_id = $row['id']; 

	$t_poll = $row['poll']; 

	$p_question = $row['poll_question'];

	$t_title = $row['title'];

	$t_description = $row['description'];

	$t_message = ekincode($row['message'],$user['theme']);

	$t_poster = $row['poster']; 

	$t_date = $row['date'];

	$t_date = date("l, F jS, Y", strtotime($t_date, "\n"));

	$t_views = $row['views'];

	$t_views = $t_views + 1;

	$update_result = mysql_query("UPDATE topics SET views='". $t_views ."' WHERE id='". $_GET[id] ."'");

		$q1 = mysql_query("SELECT * FROM replies WHERE poster='". $t_poster ."'");

		$q2 = mysql_query("SELECT * FROM topics WHERE poster='". $t_poster ."'");

    $poster_posts = mysql_num_rows($q1) + mysql_num_rows($q2);

	$t_locked = $row['locked'];

	$t_result = mysql_query("select * from replies where tid='". $t_id ."'"); 

	$r_count = mysql_num_rows($t_result); 



	$getuser_result = mysql_query("SELECT * FROM users WHERE username='". $t_poster ."'");

	$row = mysql_fetch_array($getuser_result);

	$poster_level = $row['level'];

	$poster_id = $row['id'];

	$poster_sig = ekincode($row['sig'],$user['theme']);

	$member_title = $row['title'];

	$member_joined = date("M jS, Y", strtotime($row["signup_date"], "\n"));

	$poster_aim = $row['aim'];

	$poster_msn = $row['msn'];

	$poster_yahoo = $row['yahoo'];

	$poster_icq = $row['icq'];

	$poster_www = $row['website_url'];


	switch ($poster_level) {

		case 0:

		   $poster_level = "Test Account";

		   break;

		case 1:

		   $poster_level = "Member";

   			break;

		case 2:

		   $poster_level = "Moderator";

		   break;

		case 3:

		   $poster_level = "Administrator";

		   break;
	}
?>
<a href='viewtopic.php?id=<?PHP echo $_GET[id];?>' class='link2'>View this topic normally</a>
<table width=100%>
	<tr>
		<td height="5">
		</td>
	</tr>
</table>
<table width=100% class=grey_border>
	<tr>
		<td class=grey_content>
			<?PHP echo $top_c_name;?> - <?PHP echo $top_f_name;?> - <?PHP echo $top_t_title;?>
		</td>
	</tr>
</table>
<table width=100%>
	<tr>
		<td height="5">
		</td>
	</tr>
</table>
<table width=100% class=topic_border>
	<tr>
		<td class=topic_top>
			<img src="templates/default/images/arrow.gif"> <span class=topic_title><?PHP echo $t_title;?></span><br>
			By <a href="profile.php?id=<?PHP echo $poster_id;?>"><?PHP echo $t_poster;?></a> on <?PHP echo $t_date;?>
		</td>
	</tr>
	<tr>
		<td class="topic_content">
			<?PHP echo $t_message;?>
		</td>
	</tr>
	<tr>
		<td class=topic_bottom>
			<table width=100%>
				<tr>
					<td width=100>
						Replies: <?PHP echo number_format($r_count);?>
					</td>
					<td width=100>
						Views: <?PHP echo number_format($t_views);?>
					</td>
					<td></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<?PHP
} else {
?>
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="redtable">
	<tr>
		<td class="redtable_header">
			<b>Notice!</b>
		</td>
	</tr>
	<tr>
		<td class="redtable_content">
			This topic does not exist.  Please return to the previous page and try again.
		</td>
	</tr>
</table>
<?PHP
}

$evenchoice_count = 0;

if($t_poll == '1'){


	$poll_voted_check_result = mysql_query("select * from poll_votes where pid='". $_GET[id] ."' AND voter='$_userid'");

	$poll_voted_check = mysql_num_rows($poll_voted_check_result);



	if(($_username != NULL) && ($poll_voted_check == 0)){

			if($_GET[poll] == "results"){
			

				$p_result = mysql_query("select * from poll_choices where pid='". $_GET[id] ."' ORDER BY id"); 

				while($poll_row=mysql_fetch_array($p_result)){

	

					if(!($evenchoice_count % 2) == TRUE){

						$evenchoice = 1;

					} else {

						$evenchoice = 2;

					}

					

					$evenchoice_count++;

	

					$poll_choice_value = $poll_row['id'];

					$poll_choice = $poll_row['choice'];



					$c_total_result = mysql_query("select * from poll_votes where pid='". $_GET[id] ."'"); 

					$c_total_count = mysql_num_rows($c_total_result);



					$c_result = mysql_query("select * from poll_votes where choice_id='". $poll_choice_value ."'"); 

					$c_count = mysql_num_rows($c_result);

					

					if(($c_total_count!=0) && ($c_count!=0)){

						$count_percentage = $c_count / $c_total_count * 100;

					} else {

						$count_percentage = 0;

					}



					$poll_bar_width = round($count_percentage, 0) * 2;



					$poll_choice_percent = round($count_percentage, 2);


				}

	

				$template->end_loop ("poll_results", $template->end_loop ("poll_results_choice", $final_poll_html, $poll_results_str));

				$template->end_loop ("poll_vote", "");

			} else {

				$poll_choice_str = $template->get_loop ("poll_vote_choice");

			

				$p_result = mysql_query("select * from poll_choices where pid='". $_GET[id] ."' ORDER BY id"); 

				while($poll_row=mysql_fetch_array($p_result)){

	

					if(!($evenchoice_count % 2) == TRUE){

						$evenchoice = 1;

					} else {

						$evenchoice = 2;

					}

					

					$evenchoice_count++;

	

					$poll_choice_value = $poll_row['id'];

					$poll_choice = $poll_row['choice'];

	

					$c_result = mysql_query("select * from poll_votes where choice_id='". $poll_choice_value ."'"); 

					$c_count = mysql_num_rows($c_result);

				}

		

				$template->end_loop ("poll_vote", $template->end_loop ("poll_vote_choice", $final_poll_html, $poll_vote_str));

				$template->end_loop ("poll_results", "");

			}

	} else {


		$p_result = mysql_query("select * from poll_choices where pid='". $_GET[id] ."' ORDER BY id"); 

		while($poll_row=mysql_fetch_array($p_result)){

	

					if(!($evenchoice_count % 2) == TRUE){

						$evenchoice = 1;

					} else {

						$evenchoice = 2;

					}

					

					$evenchoice_count++;

	

					$poll_choice_value = $poll_row['id'];

					$poll_choice = $poll_row['choice'];



					$c_total_result = mysql_query("select * from poll_votes where pid='". $_GET[id] ."'"); 

					$c_total_count = mysql_num_rows($c_total_result);



					$c_result = mysql_query("select * from poll_votes where choice_id='". $poll_choice_value ."'"); 

					$c_count = mysql_num_rows($c_result);

					

					if(($c_total_count!=0) && ($c_count!=0)){

						$count_percentage = $c_count / $c_total_count * 100;

					} else {

						$count_percentage = 0;

					}



					$poll_bar_width = round($count_percentage, 0) * 2;



					$poll_choice_percent = round($count_percentage, 2);

			if(($poll_voted_check != 0) && ($showed != TRUE)){

				echo "( You have already voted! )";

				$showed = TRUE;
			}

		}

	}

}



$page_result = mysql_query("select * from replies where tid='". $_GET[id] ."'"); 

$numrows = mysql_num_rows($page_result);


$query_rows = "SELECT * FROM replies where tid='". $_GET[id] ."'";

$result_rows = @mysql_query ($query_rows);

$num_rows = @mysql_num_rows ($result_rows);


if($numrows!=0){
	$r_result = mysql_query("select * from replies where tid='". $_GET[id] ."' ORDER BY datesort");

	$r_count = mysql_num_rows($r_result);

	while($row=mysql_fetch_array($r_result)){



	$r_id = $row['id']; 

	$r_message = ekincode($row['message'],$user['theme']);

	$r_poster = $row['poster']; 

	$r_date = $row['date'];

	$r_date = date("l, F jS, Y", strtotime($r_date, "\n"));


	$tmp_num = $tmp_num + 1;
	
	$post_number = $page_page_num - 1;

	$post_number = $display_num * $post_number;

	$post_number = $post_number + $tmp_num;


	$getuser_result = mysql_query("select * from users where username='". $r_poster ."'");

	$row = mysql_fetch_array($getuser_result);

	$poster_id = $row['id'];

	$poster_joined = $row['signup_date'];

	$poster_joined = date("M jS, Y", strtotime($poster_joined, "\n"));

	$poster_www = $row['website_url'];

	$poster_aim = $row['aim'];

	$poster_msn = $row['msn'];

	$poster_yahoo = $row['yahoo'];

	$poster_icq = $row['icq'];

	$poster_sig = ekincode($row['sig'],$user['theme']);

	$poster_ava = $row['avatar'];

	$poster_level = $row['level'];

	$member_title = $row['title'];

		$q1 = mysql_query("SELECT * FROM replies WHERE poster='". $r_poster ."'");

		$q2 = mysql_query("SELECT * FROM topics WHERE poster='". $r_poster ."'");

	$poster_posts = mysql_num_rows($q1) + mysql_num_rows($q2);



	switch ($poster_level) {

		case 0:

		   $poster_level = "Test Account";

		   break;

		case 1:

		   $poster_level = "Member";

   			break;

		case 2:

		   $poster_level = "Moderator";

		   break;

		case 3:

		   $poster_level = "Administrator";

		   break;

	}
?>
<table width=100%>
	<tr>
		<td height="5">
		</td>
	</tr>
</table>
<table width=100% class=grey_border>
	<tr>
		<td class=grey_top>
			By <a href="profile.php?id=<?PHP echo $poster_id;?>"><?PHP echo $r_poster;?></a> on <?PHP echo $r_date;?>
		</td>
	</tr>
	<tr>
		<td class="grey_content">
			<?PHP echo $r_message;?>
		</td>
	</tr>
	<tr>
		<td class=grey_bottom>
			<table width=100%>
				<tr>
					<td></td>
					<td align=right width=100>
						<a href="print.php?id=<?PHP echo $_GET[id];?>">Top</a>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<?PHP
}

}
} else {
?>
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="redtable">
	<tr>
		<td class="redtable_header">
			<b>Notice!</b>
		</td>
	</tr>
	<tr>
		<td class="redtable_content">
			This topic does not exist.  Please return to the previous page and try again.
		</td>
	</tr>
</table>
<?PHP
}
?>
<table width=100%>
	<tr>
		<td height="5">
		</td>
	</tr>
</table>
			</td>
				</tr>
				<tr>
					<td align='center'>
						<a href='http://www.ekinboard.com' target='_blank'>EKINboard</a> v1.0.0 © 2005 <a href='http://www.ekindesigns.com' target='_blank'>EKINdesigns</a>
					</td>
				</tr>
				</td>
			</td>
		</table>
<?PHP
$mtime = microtime();

$mtime = explode(" ",$mtime);

$mtime = $mtime[1] + $mtime[0];

$endtime = $mtime;

$totaltime = ($endtime - $starttime); 

$totaltime = number_format($totaltime,3);

//echo "[ Script Execution time: $totaltime ]";

//var_dump (get_defined_vars ());

?>
