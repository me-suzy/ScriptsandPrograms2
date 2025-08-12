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

require_once('../lib/template_class.php');
include("../config.php");
include("../lang/admin.php");
$session=session_id();

$db=mysql_connect("$dbhost","$dbuser","$dbpass");
mysql_select_db($dbname,$db); 
	$status = mysql_query("select * from bannerconfig where name='exchangestate'");
	$get_status=mysql_fetch_array($status);
	$status=$get_status[data];

if($status==0){
$exchangestate="<a href=\"$baseurl/admin/pause.php?SID=$session\">$LANG_menu_pause</a>";
	}
if($status==1){
$exchangestate="<a href=\"$baseurl/admin/pause.php?SID=$session\">$LANG_menu_unpause</a>";
	}

if($sellcredits=='1'){
	$commerce="<a href=\"$baseurl/admin/commerce.php?SID=$session\">$LANG_commerce_title</a>";
	}

$waiting = mysql_query("select approved from bannerstats where approved='0'");
	if(!$get_num=mysql_num_rows($waiting)){
		$get_num="0";
	}

$total = mysql_query("select approved from bannerstats where approved='1'");
	if(!$get_total=mysql_num_rows($total)){
		$get_total="0";
	}

$validate="$LANG_menu_valacct (<b>$get_num</b>)";
$listall="$LANG_menu_listacct (<b>$get_total</b>)";

$page = new Page('../template/admin_menuing.php');
$page->replace_tags(array(
'baseurl' => "$baseurl",
'session' => "$session",
'acct_head' => "$LANG_menu_acct",
'admin_head' => "$LANG_menu_administration",
'tools_head' => "$LANG_menu_tools",
'nav_head' => "$LANG_menu_nav",
'editcss' => "$LANG_menu_editcss",
'valacct' => "$validate",
'addacct' => "$LANG_menu_addacct",
'listacct' => "$listall",
'changedefault' => "$LANG_menu_changedefault",
'mailer' => "$LANG_menu_mailer",
'categories' => "$LANG_menu_categories",
'addadmin' => "$LANG_menu_addadmin", 
'editpass' => "$LANG_menu_editpass",
'dbtools' => "$LANG_menu_dbtools",
'templates' => "$LANG_menu_templates",
'vars' => "$LANG_editvars_title",
'faqmgr' => "$LANG_menu_faqmgr",
'checkbanners' => "$LANG_menu_checkbanners",
'editcou' => "$LANG_menu_editcou",
'editrules' => "$LANG_menu_editrules",
'promo' => "$LANG_promo_title",
'update' => "$LANG_updatemgr_title",
'commerce' => "$commerce",
'exchangestate' => "$exchangestate",
'logout' => "$LANG_menu_logout",
'help' => "$LANG_menu_help",
'home' => "$LANG_menu_home"));

$page->output();
?>