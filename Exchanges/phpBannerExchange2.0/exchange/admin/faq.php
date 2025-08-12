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
		$faqs=mysql_query("select * from bannerfaq order by id asc");
		$count=mysql_num_rows($faqs);
		
		while($get_faqs=mysql_fetch_array($faqs)){	
			$fid=$get_faqs[id];	
			$question=$get_faqs[question];	
			$faqcontent.= "<tr valign=\"top\"><td class=\"tablebody\" width=\"8%\">$fid</td><td class=\"tablebody\" width=\"77%\">$question</td><td class=\"tablebody\" width=\"15%\"><center><a href=\"faqdel.php?SID=$session&fid=$fid\">$LANG_delete</a> | <a href=\"faqedit.php?SID=$session&fid=$fid\">$LANG_edit</a></center></td></tr>";
		}
		
		$page = new Page('../template/admin_faqmanager.php');
		$page->replace_tags(array(
			'css' => "$css",
			'session' => "$session",
			'baseurl' => "$baseurl",
			'title' => "$exchangename - $LANG_menu_faqmgr",
			'shorttitle' => "$LANG_menu_faqmgr",
			'add' => "$LANG_faq_add",
			'id' => "$LANG_ID",
			'question' => "$LANG_question",
			'action' => "$LANG_action",
			'faqcontent' => "$faqcontent",
			'count' => "$count",
			'faqfound' => "$LANG_faq_found",
			'msg' => "$LANG_email_senttouser",
			'back2' => "$LANG_back2",
			'menu' => 'admin_menuing.php',
			'footer' => '../footer.php'));
		$page->output();
	}
?>