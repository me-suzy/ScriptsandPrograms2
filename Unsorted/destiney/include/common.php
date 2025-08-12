<?php

include("$include_path/functions.php");
include("$include_path/session.php");

if(isset($_COOKIE['keep_me_logged_in'])){
	if(!isset($_SESSION['rc'])){
		$c_sql = "
			select
				userid
			from
				$tb_cookies
			where
				cookie = '$_COOKIE[keep_me_logged_in]'
		";
		$c_query = mysql_query($c_sql) or die(mysql_error());
		if(mysql_num_rows($c_query)){
			$c_array = mysql_fetch_array($c_query);
			$sql = "
				select
					username,
					id
				from
					$tb_users
				where
					id = '$c_array[userid]'
			";
			$query = mysql_query($sql) or die(mysql_error());
			if(mysql_num_rows($query)){
				$_SESSION['username'] = mysql_result($query, 0, "username");
				$_SESSION['userid'] = (int) mysql_result($query, 0, "id");
				$_SESSION['sl'] = false;
			}
			$_SESSION['rc'] = true;
			header("Location: $_SERVER[PHP_SELF]");
			exit();
		}
	}
}

$small_font = ($base_font_size - 2) . "px";
$medium_font =	$base_font_size . "px";
$large_font = ($base_font_size + 1) . "px";
$x_large_font = ($large_font + 15) . "px";
$pp = 1;
$cpp = 10;

$total_width = $left_col_width + $right_col_width + $main_col_width;

$sr = isset($_GET['sr']) ? $_GET['sr'] : 0;
$cp = isset($_GET['cp']) ? $_GET['cp'] : 1;
$csr = isset($_GET['csr']) ? $_GET['csr'] : 0;
$ccp = isset($_GET['ccp']) ? $_GET['ccp'] : 1;

$uo_total = users_online($online_expire);

mt_srand(make_seed());

$states_array = array (
	"Alabama",
	"Alaska",
	"Arizona",
	"Arkansas",
	"California",
	"Colorado",
	"Connecticut",
	"Delaware",
	"Florida",
	"Georgia",
	"Hawaii",
	"Idaho",
	"Illinois",
	"Indiana",
	"Iowa",
	"Kansas",
	"Kentucky",
	"Louisiana",
	"Maine",
	"Maryland",
	"Massachusetts",
	"Michigan",
	"Minnesota",
	"Mississippi",
	"Missouri",
	"Montana",
	"Nebraska",
	"Nevada",
	"New Hampshire",
	"New Jersey",
	"New Mexico",
	"New York",
	"North Carolina",
	"North Dakota",
	"Ohio",
	"Oklahoma",
	"Oregon",
	"Pennsylvania",
	"Rhode Island",
	"South Carolina",
	"South Dakota",
	"Tennessee",
	"Texas",
	"Utah",
	"Vermont",
	"Virginia",
	"Washington",
	"West Virginia",
	"Wisconsin",
	"Wyoming"
);

?>