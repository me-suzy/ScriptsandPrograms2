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
include("../lib/commerce/paypal.config.php");
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
		$productname=$_REQUEST['productname'];
		$credits=$_REQUEST['credits'];
		$price=$_REQUEST['price'];
		$action=$_REQUEST['action'];
		$plan=$_REQUEST['plan'];

		if($_REQUEST['submit']){
			$insert=mysql_query("insert into bannercommerce values('','$productname','$credits','$price','0')");
		}	
		
		if($action==1){	
			$delete=mysql_query("delete from bannercommerce where productid='$plan'");	
		}
		
		$query=mysql_query("select * from bannercommerce");
		$get_num=mysql_num_rows($query);
		
		if(!$get_num){
			$data="<td colspan=\"6\">$LANG_commerce_noitems</td></tr>";

		}else{
			while($get_items=@mysql_fetch_array($query)){
			
			$price=number_format($get_items[price], 2, $decimal_separator, $thousands_separator);
			$credits=number_format($get_items[credits], 0, $decimal_separator, $thousands_separator);

			$data.="<tr><td class=\"tablebody\">$get_items[productid]</a></td><td class=\"tablebody\">$get_items[productname]</td><td class=\"tablebody\">$credits</td><td class=\"tablebody\">$currency_sign$price $currency_int</td><td class=\"tablebody\">$get_items[purchased]</td><td class=\"tablebody\"><a href=\"commerce_edit.php?SID=$session&plan=$get_items[productid]\">$LANG_edit</a></td><td class=\"tablebody\"><a href=\"commerce.php?SID=$session&plan=$get_items[productid]&action=1\">$LANG_delete</a></td></tr>";
		}
	}
	$page = new Page('../template/admin_commerce.php');
	$page->replace_tags(array(
		'css' => "$css",
		'session' => "$session",
		'baseurl' => "$baseurl",
		'title' => "$exchangename - $LANG_commerce_title",
		'msg' => "$data",
		'id' => "$LANG_ID",
		'name' => "$LANG_commerce_name",
		'credits' => "$LANG_commerce_credits",
		'search' => "$LANG_commerce_view",
		'sign' => "$currency_sign",
		'int_sign' => "$currency_int",
		'price' => "$LANG_commerce_price",
		'purchased' => "$LANG_commerce_purchased",
		'options' => "$LANG_commerce_options",
		'additem' => "$LANG_commerce_add",
		'submit' => "$LANG_submit",
		'menu' => 'admin_menuing.php',
		'footer' => '../footer.php'));
	$page->output();
	}	
?>