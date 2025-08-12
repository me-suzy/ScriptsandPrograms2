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
//   Copyright 2003-2005 by eschew.net Productions.   //
//   Please keep this copyright information intact.   //
////////////////////////////////////////////////////////

include("../config.php");
include("../css.php");
require_once('../lib/template_class.php');
include("../lang/client.php");

if($use_gzhandler==1){
	ob_start("ob_gzhandler");
}

// Begin login stuff
if(!$db=@mysql_connect("$dbhost","$dbuser","$dbpass")){
	include("../lang/errors.php");
	$err="1";
	$error.="$LANG_error_header<p>";
	$error.="$LANG_error_mysqlconnect<p> ";
	$error.=mysql_error();
}
	@mysql_select_db($dbname,$db);
	session_start();
	$session=session_id();
	header("Cache-control: private"); //IE 6 Fix 
	$login=$_SESSION['login'];
	$pass=$_SESSION['pass'];
	$id=$_SESSION['id'];

	$result = mysql_query("select * from banneruser where login='$login' AND pass='$pass'");
	$get_userinfo=@mysql_fetch_array($result);
	$id=$get_userinfo[id];
	$login=$get_userinfo[login];
	$pass=$get_userinfo[pass];
    
	if($login=="" AND $pass=="" OR $pass=="") {
		if(!$err){
			include("../lang/errors.php");
			$error.="$LANG_error_header<p>";
			$error.="$LANG_login_error_client";
		}
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
	$commerce_config_path="../lib/commerce/$commerce_service.config.php";
	include("$commerce_config_path");
	$query=mysql_query("select * from bannercommerce order by price");
	$get_num=mysql_num_rows($query);
	if($get_num == '0'){
		$data="<tr class=\"tablebodycenter\"><td colspan=\"6\">$LANG_commerce_noitems</td></tr>";
	}else{
		if($_REQUEST[coupon]){
			// check to see if this is a valid coupon.
			$query=mysql_query("select * from bannerpromos where promocode='$_REQUEST[coupon]' and promostatus='1' limit 1");
			$num=@mysql_num_rows($query);
			if($num=='0' or !$num){
				include("../lang/errors.php");
				$err='1';
				$error_html.="$LANG_coupon_nocoup";
			}else{
				$get_coup_details=@mysql_fetch_array($query);
				if($get_coup_details[promotype] == '1'){
					include("../lang/errors.php");
					$err='1';
					$error_html.="$LANG_coupon_wrongtype";
				}
				
				if($get_coup_details[promotype] == '2'){
					$query=mysql_query("select * from bannercommerce order by price");
					while($get_items=@mysql_fetch_array($query)){
						$percentint=$get_coup_details[promovals] / 100;
						$discamt=$get_items[price] * $percentint;
						$discounted=$get_items[price] - $discamt;
						$price=number_format($discounted, $places, $decimal_separator, $thousands_separator);
						$data.="<tr><td class=\"tablebodycenter\">$get_items[productname]</td><td class=\"tablebodycenter\">$credits</td><td class=\"tablebodycenter\">$currency_sign$price $currency_int</td><td class=\"tablebodycenter\"><form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\"><input type=\"hidden\" name=\"currency_code\" value=\"$payment_currency\"><input type=\"hidden\" name=\"cmd\" value=\"_xclick\"><input type=\"hidden\" name=\"business\" value=\"$businessname\"><input type=\"hidden\" name=\"undefined_quantity\" value=\"1\"><input type=\"hidden\" name=\"item_name\" value=\"$get_items[productname] from  $exchangename\"><input type=\"hidden\" name=\"item_number\" value=\"$get_items[productid]\"><input type=\"hidden\" name=\"amount\" value=\"$price\"><input type=\"hidden\" name=\"return\" value=\"$baseurl\">  <input type=\"hidden\" name=\"notify_url\" value=\"$baseurl/lib/commerce/$ipn_page\"><input type=\"hidden\" name=\"cancel_return\" value=\"$baseurl\"><input type=\"hidden\" name=\"custom\" value=\"$id\"><input class=\"button\" type=\"submit\" name=\"submit\" value=\"$LANG_commerce_buynow_button $propername\"></form>";
						$coupcode="";
					}
				}
					if($get_coup_details[promotype] == '3'){
						$price=number_format($get_coup_details[promovals], $places, $decimal_separator, $thousands_separator);
						$credits=number_format($get_coup_details[promocredits], 0, $decimal_separator, $thousands_separator);

						$data.="<tr><td class=\"tablebodycenter\">$get_coup_details[promoname]</td><td class=\"tablebodycenter\">$credits</td><td class=\"tablebodycenter\">$currency_sign$price $currency_int</td><td class=\"tablebodycenter\"><form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\"><input type=\"hidden\" name=\"currency_code\" value=\"$payment_currency\"><input type=\"hidden\" name=\"cmd\" value=\"_xclick\"><input type=\"hidden\" name=\"business\" value=\"$businessname\"><input type=\"hidden\" name=\"undefined_quantity\" value=\"1\"><input type=\"hidden\" name=\"item_name\" value=\"$get_coup_details[promoname] from  $exchangename\"><input type=\"hidden\" name=\"item_number\" value=\"$coupon\"><input type=\"hidden\" name=\"amount\" value=\"$price\"><input type=\"hidden\" name=\"return\" value=\"$baseurl\">  <input type=\"hidden\" name=\"notify_url\" value=\"$baseurl/lib/commerce/$ipn_page\"><input type=\"hidden\" name=\"cancel_return\" value=\"$baseurl\"><input type=\"hidden\" name=\"custom\" value=\"$id\"><input class=\"button\" type=\"submit\" name=\"submit\" value=\"$LANG_commerce_buynow_button $propername\"></form>";
						$coupcode="";
					}
				}
			}

	while($get_items=@mysql_fetch_array($query)){
	$price=number_format($get_items[price], $places, $decimal_separator, $thousands_separator);
	$credits=number_format($get_items[credits], 0, $decimal_separator, $thousands_separator);
	$data.="<tr><td class=\"tablebodycenter\">$get_items[productname]</td><td class=\"tablebodycenter\">$credits</td><td class=\"tablebodycenter\">$currency_sign$price $currency_int</td><td class=\"tablebodycenter\"><form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\"><input type=\"hidden\" name=\"currency_code\" value=\"$payment_currency\"><input type=\"hidden\" name=\"cmd\" value=\"_xclick\"><input type=\"hidden\" name=\"business\" value=\"$businessname\"><input type=\"hidden\" name=\"undefined_quantity\" value=\"1\"><input type=\"hidden\" name=\"item_name\" value=\"$get_items[productname] from  $exchangename\"><input type=\"hidden\" name=\"item_number\" value=\"$get_items[productid]\"><input type=\"hidden\" name=\"amount\" value=\"$price\"><input type=\"hidden\" name=\"return\" value=\"$baseurl\">  <input type=\"hidden\" name=\"notify_url\" value=\"$baseurl/lib/commerce/$ipn_page\"><input type=\"hidden\" name=\"cancel_return\" value=\"$baseurl\"><input type=\"hidden\" name=\"custom\" value=\"$id\"><input class=\"button\" type=\"submit\" name=\"submit\" value=\"$LANG_commerce_buynow_button $propername\"></form>";

	$coupcode="<form method=\"post\" action=\"commerce.php?SID=$session\">$LANG_commerce_couponhead: <input class=\"formbox\" type=\"text\" name=\"coupon\" size=\"30\"> <input class=\"button\" type=\"submit\" value=\"$LANG_commerce_coupon_button\"></form>";
	}		
}

if($sellcredits=='1'){
	$sellcode="<table border=\"1\" cellpadding=\"2\" cellspacing=\"2\" style=\"border-collapse: collapse\" width=\"60%\" align=\"center\"><tr><td colspan=\"4\"><center><b>$LANG_commerce_history</b></center></td></tr><td class=\"tablehead\"><center><b>$LANG_commerce_date</b></center></td><td class=\"tablehead\"><center><b>$LANG_commerce_invoice</b></center></td><td class=\"tablehead\"><center><b>$LANG_commerce_item</b></center></td><td class=\"tablehead\"><center><b>$LANG_commerce_purchaseprice</b></center></td></tr>";
	$hist_query=mysql_query("select * from bannersales where uid='$id'");
	while($get_hist=@mysql_fetch_array($hist_query)){
		if($date_format=="0"){
		$date=date("M d Y", $get_hist[timestamp]);
		}else{
		$date=date("d M Y", $get_hist[timestamp]);
		}

		$itemname=mysql_query("select productname from bannercommerce where productid='$get_hist[item_number]' limit 1");
		$get_itemname=mysql_fetch_array($itemname);
		$name=$get_itemname[productname];
		$payment=number_format($get_hist[payment_gross], 2, $decimal_separator, $thousands_separator);
		$history.="<tr><td class=\"tablebodycenter\">$date</td><td class=\"tablebodycenter\">$get_hist[invoice]</td><td class=\"tablebodycenter\">$name</td><td class=\"tablebodycenter\">$currency_sign$payment $currency_int</td></tr>";
	}
}else{
	$sellcode="";
	$history.="<tr><td colspan=\"4\">$LANG_commerce_nohist</td></tr>";
}
	if($err){
		$error="$LANG_error_header<p>$error_html<p>$LANG_tryagain";
		$page = new Page('../template/admin_error.php');
		$page->replace_tags(array(
			'css' => "$css",
			'session' => "$session",
			'baseurl' => "$baseurl",
			'title' => "$exchangename - $LANG_commerce_title",
			'shorttitle' => "$LANG_login_error_title",
			'error' => "$error",
			'menu' => 'client_menuing.php',
			'footer' => '../footer.php'));
		$page->output();
	}else{

	$page = new Page('../template/client_commerce.php');
	$page->replace_tags(array(
	'css' => "$css",
	'session' => "$session",
	'baseurl' => "$baseurl",
	'title' => "$exchangename - $LANG_menu_commerce",
	'shorttitle' => "$LANG_menu_commerce",
	'msg' => "$data",
	'purchase_hist' => "$LANG_commerce_history",
	'date' => "$LANG_commerce_date",
	'item' => "$LANG_commerce_item",
	'purchaseprice' => "$LANG_commerce_purchaseprice",
	'name' => "$LANG_commerce_name",
	'history' => "$history",
	'credits' => "$LANG_commerce_credits",
	'price' => "$LANG_commerce_price",
	'histcode' => "$sellcode",
	'couperror' => "$coup_error",
	'purchasehistory' => "$history",
	'couponcode' => "$coupcode",
	'purchase' => "$LANG_commerce_buynow",
	'footer' => '../footer.php',
	'menu' => 'client_menuing.php'));

	$page->output();
	}
}
?>
