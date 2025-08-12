<?
$file_rev="041306";
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

if($use_gzhandler==1){
ob_start("ob_gzhandler");
}

require_once('../lib/template_class.php');
include("../lang/client.php");

// Begin login stuff
$db=mysql_connect("$dbhost","$dbuser","$dbpass");
mysql_select_db($dbname,$db);
$result = mysql_query("select * from banneruser where login='$login' AND pass='$pass'");
$get_userinfo=@mysql_fetch_array($result);
$id=$get_userinfo[id];
$login=$get_userinfo[login];
$pass=$get_userinfo[pass];

session_start();
$session=session_id();
$login=$_SESSION['login'];
$pass=$_SESSION['pass'];
$id=$_SESSION['id'];

if($login=="" AND $pass=="" OR $pass=="") {
	$page = new Page('../template/client_login_error.php');	
	$page->replace_tags(array(	
		'css' => "$css",
		'session' => "$session",	
		'baseurl' => "$baseurl",	
		'title' => "$exchangename - $LANG_login_error_title",	
		'shorttitle' => "$LANG_login_error_title",	
		'msg' => "$LANG_login_error",	
		'footer' => '../footer.php'));	
	$page->output();	
	session_destroy();
}else{

$begincode="&lt;!--Begin $exchangename code --&gt;<br>&lt;center&gt;";
$basecode="&lt;iframe align=top width=$bannerwidth height=$bannerheight marginwidth=0 marginheight=0 hspace=0 vspace=0 frameborder=0 scrolling=no src=&quot;$baseurl/view.php?uid=$id<b>&cat=0</b>&quot;&gt;&lt;ilayer align=top width=$bannerwidth height=$bannerheight src=&quot;$baseurl/view.php?uid=$id<b>&cat=0</b>&quot;&gt;&lt;/ILAYER&gt; &lt;/iframe&gt;";
$begintable="&lt;table border=0 cellpadding=0 cellspacing=0&gt;&lt;tr&gt;&lt;td&gt;";
$midtable="&lt;/td&gt;&lt;/tr&gt;&lt;tr&gt;&lt;td&gt;";
$breaktable="&lt;/td&gt;&lt;/tr&gt;&lt;/table&gt;";
if($referral_program == "Y"){
	$text="&lt;a href=&quot;$baseurl/index.php?ref=$id&quot;&gt;$exchangetext&lt;/a&gt;&lt;/center&gt;";
}else{
	$text="&lt;a href=&quot;$baseurl&quot;&gt;$exchangetext&lt;/a&gt;&lt;/center&gt;";
}
$endcode="<br>&lt;!--End $exchangename code --&gt;<p>";
$imagecode="&lt;img src=&quot;$imageurl&quot;&gt;";

$htmlcode.="$begincode";
if($showimage == "Y"){
	if($imagepos == "L"){
		$htmlcode.="$imagecode $basecode";
	}
	if($imagepos == "R"){
		$htmlcode.="$basecode $imagecode";
	}
	if($imagepos == "T"){
		$htmlcode.="$begintable $imagecode $midtable $basecode";
	}
	if($imagepos == "B"){
		$htmlcode.="$begintable $basecode $midtable $imagecode";
	}
}else{
	$htmlcode.="$basecode";
}
if($showtext == "Y"){
	if($imagepos == "L" OR $imagepos == "R"){
		$htmlcode.="&lt;br&gt;$text";
	}else{
		$htmlcode.="$midtable&lt;center&gt;$text&lt;/center&gt;";
	}
}else{
	$htmlcode.="";
}
if($imagepos == "T" OR $imagepos == "B"){
	$htmlcode.="$breaktable";
}
$htmlcode.="$endcode";

$cats=mysql_query("select * from bannercats order by id");
  while($get_cats=mysql_fetch_array($cats)){
	$catlisting.= "<tr><td class=\"tablebody\" width=\"70%\">$get_cats[catname]</td><td class=\"tablebody\" width=\"30%\">$get_cats[id]</td></tr>";
 }

	$page = new Page('../template/client_gethtml.php');
	$page->replace_tags(array(
	'css' => "$css",
	'session' => "$session",
	'baseurl' => "$baseurl",
	'title' => "$exchangename - $LANG_gethtml_title",
	'shorttitle' => "$LANG_gethtml_title",
	'msg' => "$LANG_gethtml_message",
	'exchangecode' => "$exchangecode",
	'htmlcode' => "$htmlcode",
	'catname' => "$LANG_gethtml_catname",
	'catlisting' => "$catlisting",
	'catid' => "$LANG_gethtml_catid",
	'footer' => '../footer.php',
	'menu' => 'client_menuing.php'));

	$page->output();
}
?>
