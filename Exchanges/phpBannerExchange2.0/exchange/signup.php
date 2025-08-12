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

include("config.php");
include("css.php");
if($use_gzhandler==1){
ob_start("ob_gzhandler");
}

session_start();
session_register(ref);

require_once('lib/template_class.php');
include("config.php");
include("lang/common.php");
$session=session_id();

$db=mysql_connect("$dbhost","$dbuser","$dbpass");
mysql_select_db($dbname,$db);
$get_cats=mysql_query("select * from bannercats");

while($get_rows=mysql_fetch_array($get_cats)){
	$get_row_id=$get_rows[id];
	$get_row_category=eregi_replace("_"," ",$get_rows[catname]);
	$catselect.= "<option value=\"".$get_row_id."\">".$get_row_category."</option>";
} 

if($allow_upload =="N"){ 
$banner_url.= "<tr><td width=\"22%\"> $LANG_bannerurl:</td><td width=\"78%\"><input class=\"formbox\" type=\"text\" name=\"bannerurl\" size=\"40\" value=\"http://\"> </td></tr>";
$banner_url.= "<tr><td width=\"22%\"> $LANG_siteurl:</td><td width=\"78%\"><input class=\"formbox\" type=\"text\" name=\"targeturl\" size=\"40\" value=\"http://\"> </td></tr>";
}else{
$banner_url="";
}

$page = new Page('template/signupform.php');
$page->replace_tags(array(
'css' => "$css",
'session' => "$session",
'baseurl' => "$baseurl",
'title' => "$exchangename - $LANG_signupwords",
'shorttitle' => "$LANG_signupwords",
'name' => "$LANG_realname",
'login' => "$LANG_login",
'pass' => "$LANG_pw",
'pass2' => "$LANG_pw_again",
'category' => "$LANG_cat",
'catdefault' => "$LANG_catstuff",
'catarray' => "$catselect",
'email' => "$LANG_email",
'bannerurl' => "$banner_url",
'newsletter' => "$LANG_newsletter",
'yes' => "$LANG_yes",
'no' => "$LANG_no",
'coupon' => "$LANG_coupon",
'submit' => "$LANG_signsub",
'reset' => "$LANG_signres",
'menu' => 'common_menuing.php',
'footer' => 'footer.php'));

$page->output();

?>
