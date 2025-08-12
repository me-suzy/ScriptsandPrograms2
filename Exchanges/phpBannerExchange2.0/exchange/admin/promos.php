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

//actions (delete/edit)
if($_REQUEST[action] == '1'){
	$plan=$_REQUEST[plan];
	mysql_query("update bannerpromos set promostatus='0' where promoid='$plan'");
}

if($_REQUEST[action] == '3'){
	$plan=$_REQUEST[plan];
	mysql_query("update bannerpromos set promostatus='1' where promoid='$plan'");
}

if($_REQUEST[action] == '2'){
	$plan=$_REQUEST[plan];
	$query=mysql_query("select * from bannerpromos where promoid='$plan'");
	$get_plan=mysql_fetch_array($query);

	$productname=$get_plan[promoname];
	$codenew=$get_plan[promocode];
	$newtype=$get_plan[promotype];
	$newval=$get_plan[promovals];
	$newcredits=$get_plan[promocredits];
	$promoreuse=$get_plan[promoreuse];
	$promoreuseint=$get_plan[promoreuseint];
	$promousertype=$get_plan[promousertype];
	
		if($newtype=="1"){
			$lang_type="$LANG_promo_type1";
		}
		if($newtype=="2"){
			$lang_type="XX% $LANG_promo_type2";
		}
		if($newtype=="3"){
			$lang_type="$LANG_promo_type3";
		}
	
	if($promoreuse == "1"){
		$reuse_val="checked";
	}

	if($promousertype == "0"){
		$usertypelist="<option selected value=\"0\">$LANG_promo_newonly</option><option value=\"1\">$LANG_promo_all</option>";
	}else{
		$usertypelist="<option selected value=\"1\">$LANG_promo_all</option><option value=\"0\">$LANG_promo_newonly</option>";
	}

	$promoreuse=$get_plan[promoreuse];
	$promoreuseint=$get_plan[promoreuseint];
	$promousertype=$get_plan[promousertype];

		$promo_options.="<option selected value=\"$newtype\">$lang_type</option>";
		$promo_options.="<option value=\"1\">$LANG_promo_type1</option>";
		$promo_options.="<option value=\"2\">XX% $LANG_promo_type2";
		$promo_options.="<option value=\"3\">$LANG_promo_type3</option>";

		$addedit="promos.php?SID=$session&plan=$plan&edit=1";
}

// if we have something submitted...
if($_REQUEST[submit]){
	$productname=$_REQUEST[productname];
	$codenew=$_REQUEST[codehead];
	$newtype=$_REQUEST[type];
	$newval=$_REQUEST[pricebox];
	$newcredits=$_REQUEST[newcredits];
	$newreuse=$_REQUEST[newreuse];
	$newreuseint=$_REQUEST[newreuseint];
	$newusertype=$_REQUEST[newusertype];


// do some basic checking to make sure the form was filled out..
	$check=mysql_query("select promoid from bannerpromos where promocode='$codenew' and promostatus='1'");
	$num_check=@mysql_num_rows($check);

	if($productname==''){
		$error.="<br>$LANG_promo_noproduct";
	}
	
	if(!$_REQUEST[edit]){
		if($codenew=='' or $num_check != '0'){
			$error.="<br>$LANG_promo_badcode";
		}
	}

	if($newtype=="2" and $newval=='' or $newtype=="3" and $newval==''){
		$error.="<br>$LANG_promo_noval";
	}

	if($newtype=="1" and $newcredits=='' or $newtype=="3" and $newcredits==''){
		$error.="<br>$LANG_promo_nocreds";
	}

	if(!$error){
		$timestamp=time();
		if($_REQUEST[edit]){
			if($newreuse=='on'){
				$newreuse='1';
			}else{
				$newreuse='0';
			}

			$insert=mysql_query("update bannerpromos set promoname='$productname', promocode='$codenew',promotype='$newtype', promovals='$newval',promocredits='$newcredits',promoreuse='$newreuse',promovals='$newval',promoreuseint='$newreuseint',promousertype='$newusertype' where promoid='$_REQUEST[plan]'");
		}else{
			$insert=mysql_query("insert into bannerpromos values('','$productname','$codenew','$newtype','','$newval','$newcredits','$newreuse','$newreuseint','$newusertype','$timestamp','1')");
		}
	$productname='';
	$codenew='';
	$newtype='';
	$newval='';
	$newcredits='';
	$newreuse='';
	$newreuseint='';
	$newusertype='';

	}else{
		include("../lang/errors.php");
		$errhead="$LANG_promo_errormsg";
		$err=$error;
		if($newtype=="1"){
			$lang_type="$LANG_promo_type1";
		}
		if($newtype=="2"){
			$lang_type="XX% $LANG_promo_type2";
		}
		if($newtype=="3"){
			$lang_type="$LANG_promo_type3";
		}
		
		$promo_options.="<option selected value=\"$newtype\">$lang_type</option>";
		$promo_options.="<option value=\"1\">$LANG_promo_type1</option>";
		$promo_options.="<option value=\"2\">XX% $LANG_promo_type2";
		$promo_options.="<option value=\"3\">$LANG_promo_type3</option>";

		if($newusertype == "0"){
			$usertypelist="<option selected value=\"0\">$LANG_promo_newonly</option><option value=\"1\">$LANG_promo_all</option>";
		}else{
			$usertypelist="<option selected value=\"1\">$LANG_promo_all</option><option value=\"0\">$LANG_promo_newonly</option>";
		}

		if($promoreuse == "1"){
			$reuse_val="checked";
		}
	}
}

// display the current promos

		//list only active.
		if($_REQUEST[status] == '1'){
		$query=mysql_query("select * from bannerpromos where promostatus='1'");
		}
		
		//list only deleted.
		if($_REQUEST[status] == '0'){
		$query=mysql_query("select * from bannerpromos where promostatus='0'");
		}

		//list all.
		if($_REQUEST[status] == '2' or $_REQUEST[status] == ''){
		$query=mysql_query("select * from bannerpromos");
		}

		$get_num=@mysql_num_rows($query);
		
		if(!$get_num){
			$data="<td colspan=\"8\">$LANG_promo_noitems</td></tr>";

		}else{
			while($get_items=@mysql_fetch_array($query)){
				//check promo type
				if($get_items[promotype] == 1){
					$promotype="$LANG_promo_type1";
				}
				if($get_items[promotype] == 2){
					$promotype="$get_items[promovals]% $LANG_promo_type2";
				}
				if($get_items[promotype] == 3){
					$promotype="$LANG_promo_type3";
				}

				if($get_items[promoend] == '0'){
					$promoend="Never";
				}else{
					$promoend=$get_items[promoend];
				}

				if($date_format == '1'){
					$time_formatted=date("m/d/y",$get_items[ptimestamp]);
				}

				if($date_format == '0' or $date_format == ''){
					$time_formatted=date("d/m/y",$get_items[ptimestamp]);
				}
				
				$promoname=$get_items[promoname];
				$delete=$LANG_delete;
				$delaction="1";
				if($get_items[promostatus] == "0"){
					$promoname="<b>[$LANG_promo_deleted]</b> $get_items[promoname]";
					$delete=$LANG_reactivate;
					$delaction="3";
				}

				$data.="<tr><td class=\"tablebody\"><a href=\"promodetails.php?SID=$session&promoid=$get_items[promoid]\">$promoname</a></td><td class=\"tablebody\">$get_items[promocode]</td><td class=\"tablebody\">$promotype</td><td class=\"tablebody\">$get_items[promocredits]</td><td class=\"tablebody\">$time_formatted</td><td class=\"tablebody\"><center><a href=\"promos.php?SID=$session&plan=$get_items[promoid]&action=2&status=$_REQUEST[promostatus]\">$LANG_edit</a> | <a href=\"promos.php?SID=$session&plan=$get_items[promoid]&action=$delaction&status=$_REQUEST[promostatus]\">$delete</a></center></td></tr>";
		}
	}
if(!$_REQUEST[action]){
	$promo_options.="<option value=\"1\">$LANG_promo_type1</option>";
	$promo_options.="<option value=\"2\">XX% $LANG_promo_type2";
	$promo_options.="<option value=\"3\">$LANG_promo_type3</option>";

	$addedit="promos.php?SID=$session";

	$usertypelist="<option selected value=\"1\">$LANG_promo_all</option><option value=\"0\">$LANG_promo_newonly</option>";
}

	$page = new Page('../template/admin_promos.php');
	$page->replace_tags(array(
		'css' => "$css",
		'session' => "$session",
		'baseurl' => "$baseurl",
		'title' => "$exchangename - $LANG_promo_title",
		'msg' => "$data",
		'id' => "$LANG_ID",
		'name' => "$LANG_promo_name",
		'credits' => "$LANG_promo_credits",
		'codehead' => "$LANG_promo_code",
		'type' => "$LANG_promo_type",
		'options' => "$LANG_promo_status",
		'additem' => "$LANG_promo_add",
		'value' => "$LANG_promo_value",
		'submit' => "$LANG_submit",
		'errhead' => "$errhead",
		'err' => "$err",
		'timestamp' => "$LANG_promo_timestamp",
		'productname' => "$productname",
		'codenew' => "$codenew",
		'listall' => "$LANG_promo_listall",
		'listdel' => "$LANG_promo_listdel",
		'listact' => "$LANG_promo_listact",
		'newtype' => "$newtype",
		'newval' => "$newval",
		'addedit' => "$addedit",
		'newcredits' => "$newcredits",
		'infinfo' => "$LANG_promo_dateinf",
		'promo_options' => "$promo_options",
		'reuse' => "$LANG_promo_reuse",
		'reuse_val' => "$reuse_val",
		'days' => "$LANG_promo_reusedays",
		'reuseint' => "$LANG_promo_reuseint",
		'reuseint_val' => "$promoreuseint",
		'usertype' => "$LANG_promo_usertype",
		'user_types' => "$usertypelist",
		'menu' => 'admin_menuing.php',
		'footer' => '../footer.php'));
	$page->output();
	}	
?>