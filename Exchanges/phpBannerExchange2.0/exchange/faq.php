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
$faqs=mysql_query("select * from bannerfaq");
while($get_faqs=mysql_fetch_array($faqs)){
$title=$get_faqs[question];
$link=$get_faqs[id];
$topics.= "<li><a href=\"faq.php#$link\">$title</a></li>";
}
$faqs=mysql_query("select * from bannerfaq");
while($get_faqs=mysql_fetch_array($faqs)){
	$title=$get_faqs[question];
	$answer=$get_faqs[answer];
	$link=$get_faqs[id];
$details.= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" width=\"100%\" ><tr><td class=\"tablehead\"><div class=\"lefthead\"><a name=\"$link\">$title</a></div></td></tr><tr><td class=\"tablebody\">$answer<br>[<a href=\"faq.php#top\">{top}</a>]</td></tr></table><p>";
}

$page = new Page('template/faq.php');
$page->replace_tags(array(
'css' => "$css",
'session' => "$session",
'baseurl' => "$baseurl",
'title' => "$exchangename - FAQ",
'topics' => "$LANG_topics",
'topics_top' => "$topics",
'details' => "$details",
'top' => "$LANG_top",
'menu' => 'common_menuing.php',
'footer' => 'footer.php'));

$page->output();

?>