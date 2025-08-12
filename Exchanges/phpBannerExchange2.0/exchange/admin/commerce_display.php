<?
$file_rev="041305";
////////////////////////////////////////////////////////
//                 phpBannerExchange                  //
//                   by: Darkrose                     //
//              (darkrose@eschew.net)                 //
//                                                    //
// You can redistribute this software under the terms //
// of the GNU General Public License as published by  //
// the Free Software Foundation; either version 2 of  //
// the License, or (at your option) any later         //
// version.                                           //
//                                                    //
// You should have received a copy of the GNU General //
// Public License along with this program; if not,    //
// write to the Free Software Foundation, Inc., 59    //
// Temple Place, Suite 330, Boston, MA 02111-1307 USA //
//                                                    //
//     Copyright 2004 by eschew.net Productions.      //
//   Please keep this copyright information intact.   //
////////////////////////////////////////////////////////

include("../config.php");
include("../css.php");
include("../lang/admin.php");
require_once('../lib/template_class.php');

if($use_gzhandler==1){
	ob_start("ob_gzhandler");
}

// Begin loginstuff
if(!$db=@mysql_connect("$dbhost","$dbuser","$dbpass")){
	include("../lang/errors.php");
	$err="1";
	$error.="$LANG_error_header<p>";
	$error.="$LANG_error_mysqlconnect ";
	$error.=mysql_error();
}

@mysql_select_db($dbname,$db);

session_start();
header("Cache-control: private"); //IE 6 Fix 
$session=session_id(); 
$login = $_SESSION['login'];
$pass = $_SESSION['pass'];

$result = mysql_query("select * from banneradmin where adminuser='$login' AND adminpass='$pass'");
$get_userinfo=mysql_fetch_array($result);
$login=$get_userinfo[adminuser];
$pass=$get_userinfo[adminpass];

    if($login=="" AND $pass=="" OR $pass=="" OR $err=="1") {
		include("../lang/errors.php");
		$error.="$LANG_error_header<p>";
		$error.="$LANG_login_error";

		$page = new Page('../template/admin_error.php');
		$page->replace_tags(array(
			'css' => "$css",
			'session' => "$session",
			'baseurl' => "$baseurl",
			'title' => "$exchangename - $LANG_login_error_title",
			'shorttitle' => "$LANG_login_error_title",
			'error' => "$error",
			'menu' => "$menu",
			'footer' => '../footer.php'));
		$page->output();
	session_destroy();

	}else{ 

	if($_REQUEST[pos]){
		$pos=$_REQUEST[pos];
	}else{
		$pos="0";
	}

	if($_REQUEST[amt]){
		$amt=$_REQUEST[amt];
	}else{
		$amt="10";
	}

	if($_REQUEST[user] == '' or !$_REQUEST[user]){
		$dbquery="select * from bannersales order by invoice asc limit $pos,$amt";
	}else{
		$userid=$_REQUEST[user];
		$dbquery="select * from bannersales where uid='$userid' order by invoice asc limit $pos,$amt";
	}

	if($_REQUEST[status] or $_REQUEST[status] != ''){
		$dbquery="select * from bannersales where payment_status='$_REQUEST[status]' order by invoice asc limit $pos,$amt";
	}

	$query=mysql_query("$dbquery");
	$get_num=@mysql_num_rows($query);
	if($get_num == '' OR $get_num == '0'){
		$msg="<tr><td colspan=\"6\">$LANG_commerce_notrans</td></tr>";
	}else{

	while($get_data=mysql_fetch_array($query)){
		$invoice=$get_data[invoice];
		$uid=$get_data[uid];
		$item=$get_data[item_number];
		$status=$get_data[payment_status];
		$gross=$get_data[payment_gross];
		$email=$get_data[payer_email];
		$rawtime=$get_data[timestamp];

		$loginname=mysql_query("select login from banneruser where id='$uid' limit 1");
		$login_array=@mysql_fetch_array($loginname);
		$userlogin=$login_array[login];

		$itemname=mysql_query("select productname from bannercommerce where productid ='$item' limit 1");
		$item_array=@mysql_fetch_array($itemname);
		$item_name=$item_array[productname];
		$time=date("M d Y",$rawtime);

		$msg.="<tr><td class=\"tablebody\">$invoice</td><td class=\"tablebody\">$time</td><td class=\"tablebody\"><a href=\"edit.php?SID=$session&uid=$uid\">$userlogin</a></td><td class=\"tablebody\">$item_name</td><td class=\"tablebody\">$status</td><td class=\"tablebody\">$gross</td><td class=\"tablebody\"><a href=\"mailto:$email\">$email</a></td></tr>";
	}

		if($get_num > '9'){
			$nextpos=$pos+$amt;
			$nextcode="<a href=\"commerce_display.php?SID=$session&pos=$nextpos&amt=$amt&filter=$filter&user=$userid\">$LANG_next</a>";
		}else{
			$nextcode="$LANG_next";
		}

		if($pos > 0){
			$prevpos=$pos-$amt;
			$prevcode="<a href=\"commerce_display.php?SID=$session&pos=$prevpos&amt=$amt&filter=$filter&user=$userid\">$LANG_previous</a>";
		}else{
			$prevcode="$LANG_previous";
		}
	}

	$page = new Page('../template/admin_commerce_display.php');
	$page->replace_tags(array(
		'css' => "$css",
		'session' => "$session",
		'baseurl' => "$baseurl",
		'title' => "$exchangename - $LANG_commerce_title",
		'invoice' => "$LANG_commerce_invoice",
		'user' => "$LANG_commerce_user",
		'item' => "$LANG_commerce_item",
		'status' => "$LANG_commerce_status",
		'payment' => "$LANG_commerce_payment",
		'email' => "$LANG_commerce_email",
		'date' => "$LANG_commerce_date",
		'data' => "$msg",
		'amt' => "$amt",
		'filter' => "$filter",
		'pos' => "$pos",
		'clearfilters' => "$LANG_commerce_reset",
		'showamt' => "$LANG_commerce_recordsperpage",
		'uidsearch' => "$LANG_commerce_uidsearch",
		'next' => "$nextcode",
		'prev' => "$prevcode",
		'uid' => "$userid",
		'go' => "$LANG_commerce_go",
		'filter_head' => "$LANG_commerce_filterhead",
		'submit' => "$LANG_submit",
		'orderfilter' => "$LANG_commerce_filterorders",
		'menu' => 'admin_menuing.php',
		'footer' => '../footer.php'));
	$page->output();
	}	
?>