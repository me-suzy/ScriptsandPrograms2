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
	$found = 0;	
	$cats = mysql_query("select * from bannercats order by catname");	
	while ($get_cats=@mysql_fetch_array($cats)){
		$total_found=@mysql_num_rows($cats);
		$found=1;	
		$catid=$get_cats[id];	
		$catname=$get_cats[catname];	
		$sites = mysql_query("select * from bannerstats where category='$catid'");	
		$site_count = @mysql_num_rows($sites);
		$catstable.="<tr class=\"tablebodycenter\"><td class=\"tablebodycenter\"><a href=\"listall.php?SID=$session&catid=$catid\">$catname</a></td><td class=\"tablebodycenter\">$site_count</td><td class=\"tablebodycenter\"><a href=\"editcat.php?SID=$session&catid=$catid\">$LANG_edit</a></td><td class=\"tablebodycenter\"><a href=\"delcat.php?SID=$session&catid=$catid\">$LANG_delete</a></td></tr>";
		}

		if($found == 0){
			$total_cats="$LANG_cats_nocats";	
			} else {
				if($total_found == 1){
					$total_cats=$LANG_catsfound_singular;
					} else {
						$total_cats=$LANG_catsfound_plurl;
					}
				}

		$page = new Page('../template/admin_catmain.php');
		$page->replace_tags(array(
			'css' => "$css",
			'session' => "$session",
			'baseurl' => "$baseurl",
			'title' => "$exchangename - $LANG_catmain_title",
			'shorttitle' => "$LANG_catmain_title",
			'header' => "$LANG_catmain_header",
			'catname' => "$LANG_catmain_catname",
			'catid' => "$catid",
			'edit' => "$LANG_edit",
			'delete' => "$LANG_delete",
			'sites' => "$LANG_catmain_sites",
			'catstable' => "$catstable",
			'addcat' => "$LANG_catmain_addcat",
			'site_count' => "$site_count",
			'totalcats' => "$total_found $total_cats",
			'menu' => 'admin_menuing.php',
			'footer' => '../footer.php'));
		$page->output();
		
	}
?>