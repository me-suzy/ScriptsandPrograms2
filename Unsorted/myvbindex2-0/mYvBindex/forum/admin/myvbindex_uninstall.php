<?php
error_reporting(7);

require ("./global.php");

$version = "2.0";

?>
<HTML><HEAD>
<link rel="stylesheet" href="../cp.css">
<title>mYvBindex version <?php echo $version; ?> Uninstall Script</title>
</HEAD>
<BODY>
<table width="100%" bgcolor="#3F3849" cellpadding="2" cellspacing="0" border="0"><tr><td>
<table width="100%" bgcolor="#524A5A" cellpadding="3" cellspacing="0" border="0"><tr>
<td><a href="http://vbulletin.com/forum" target="_blank"><img src="cp_logo.gif" width="160" height="49" border="0" alt="Click here to visit the vBulletin support forums"></a></td>
<td width="100%" align="center">
<p><font size="2" color="#F7DE00"><b>mYvBindex version <?php echo $version; ?> Uninstall Script </b></font></p>
<p><font size="2" color="#FF0000"><b>Warning! Running this script will completely remove mYvBindex from your server!</b></font></p>
</td></tr></table></td></tr></table>
<br>

<?

if ($step=="") {
  $step=1;
}

if ($step==1) {


echo "<p>I'm sorry you didn't like mYvBindex. Running this script will remove all database alterations and templates created. <b>Please be sure you have reverted all <i>index</i> templates to the original before proceeding.</b><p>";
echo "<p><a href=\"myvbindex_uninstall.php?step=".($step+1)."\"><b>Click here to begin the uninstallation process --&gt;</b></a></p>\n";
}



if ($step >= 2) {
  include("./config.php");
}

if ($step == 2) {



$DB_site->query("ALTER TABLE post DROP isnews;");
echo "Removed the News ID column for news in the post table.<P>";

$DB_site->query("DELETE FROM settinggroup WHERE settinggroupid=50;");
echo "Removed MYvBindex link from the top of your Admin CP<P>";

$DB_site->query("DELETE FROM setting WHERE settinggroupid=50;");
echo "Removed MYvBindex options from your Admin CP<P>";

$DB_site->query("DELETE FROM template WHERE title='index' AND templatesetid=-1");
echo "Template <i>index</i> removed.<p>\n";

$DB_site->query("DELETE FROM template WHERE title='index_buddy' AND templatesetid=-1");
echo "Template <i>index_buddy</i> removed.<p>\n";

$DB_site->query("DELETE FROM template WHERE title='index_buddylist' AND templatesetid=-1");
echo "Template <i>index_buddylist</i> removed.<p>\n";

$DB_site->query("DELETE FROM template WHERE title='index_buddypmlink' AND templatesetid=-1");
echo "Template <i>index_buddypmlink</i> removed.<p>\n";

$DB_site->query("DELETE FROM template WHERE title='index_calendar' AND templatesetid=-1");
echo "Template <i>index_calendar</i> removed.<p>\n";

$DB_site->query("DELETE FROM template WHERE title='index_custom1' AND templatesetid=-1");
echo "Template <i>index_custom1</i> removed.<p>\n";

$DB_site->query("DELETE FROM template WHERE title='index_custom2' AND templatesetid=-1");
echo "Template <i>index_custom2</i> removed.<p>\n";

$DB_site->query("DELETE FROM template WHERE title='index_footer' AND templatesetid=-1");
echo "Template <i>index_footer</i> removed.<p>\n";

$DB_site->query("DELETE FROM template WHERE title='index_header' AND templatesetid=-1");
echo "Template <i>index_header</i> removed.<p>\n";

$DB_site->query("DELETE FROM template WHERE title='index_logincode' AND templatesetid=-1");
echo "Template <i>index_logincode</i> removed.<p>\n";

$DB_site->query("DELETE FROM template WHERE title='index_newsbits' AND templatesetid=-1");
echo "Template <i>index_newsbits</i> removed.<p>\n";

$DB_site->query("DELETE FROM template WHERE title='index_news_adminopts' AND templatesetid=-1");
echo "Template <i>index_news_adminopts</i> removed.<p>\n";

$DB_site->query("DELETE FROM template WHERE title='index_news_commentnull' AND templatesetid=-1");
echo "Template <i>index_news_commentnull</i> removed.<p>\n";

$DB_site->query("DELETE FROM template WHERE title='index_news_comments' AND templatesetid=-1");
echo "Template <i>index_news_comments</i> removed.<p>\n";

$DB_site->query("DELETE FROM template WHERE title='index_news_readmore' AND templatesetid=-1");
echo "Template <i>index_news_readmore</i> removed.<p>\n";

$DB_site->query("DELETE FROM template WHERE title='index_polldiscuss' AND templatesetid=-1");
echo "Template <i>index_polldiscuss</i> removed.<p>\n";

$DB_site->query("DELETE FROM template WHERE title='index_polledit' AND templatesetid=-1");
echo "Template <i>index_polledit</i> removed.<p>\n";

$DB_site->query("DELETE FROM template WHERE title='index_polloption' AND templatesetid=-1");
echo "Template <i>index_polloption</i> removed.<p>\n";

$DB_site->query("DELETE FROM template WHERE title='index_polloptions' AND templatesetid=-1");
echo "Template <i>index_polloptions</i> removed.<p>\n";

$DB_site->query("DELETE FROM template WHERE title='index_polloption_multiple' AND templatesetid=-1");
echo "Template <i>index_polloption_multiple</i> removed.<p>\n";

$DB_site->query("DELETE FROM template WHERE title='index_pollresult' AND templatesetid=-1");
echo "Template <i>index_pollresult</i> removed.<p>\n";

$DB_site->query("DELETE FROM template WHERE title='index_pollresults' AND templatesetid=-1");
echo "Template <i>index_pollresults</i> removed.<p>\n";

$DB_site->query("DELETE FROM template WHERE title='index_pollresults_closed' AND templatesetid=-1");
echo "Template <i>index_pollresults_closed</i> removed.<p>\n";

$DB_site->query("DELETE FROM template WHERE title='index_pollresults_voted' AND templatesetid=-1");
echo "Template <i>index_pollresults_voted</i> removed.<p>\n";

$DB_site->query("DELETE FROM template WHERE title='index_online' AND templatesetid=-1");
echo "Template <i>index_online</i> removed.<p>\n";

$DB_site->query("DELETE FROM template WHERE title='index_search' AND templatesetid=-1");
echo "Template <i>index_search</i> removed.<p>\n";

$DB_site->query("DELETE FROM template WHERE title='index_threadbit' AND templatesetid=-1");
echo "Template <i>index_threadbit</i> removed.<p>\n";

$DB_site->query("DELETE FROM template WHERE title='index_welcometext' AND templatesetid=-1");
echo "Template <i>index_welcometext</i> removed.<p>\n";

$DB_site->query("DELETE FROM template WHERE title='index_welcometext_avatar' AND templatesetid=-1");
echo "Template <i>index_welcometext_avatar</i> removed.<p><br>\n";



echo "<p><b>All templates and database fields removed.<p>Be sure to also remove all changes to your vBulletin files!</b>";
echo "<p><a href=\"myvbindex_uninstall.php?step=".($step+1)."\"><b>Uninstall Weather --&gt;</b></a></p>\n";
echo "<p><a href=\"index.php\"><b>Log into Admin CP --&gt;</b></a>\n";

}

if ($step >= 3) {
  include("./config.php");
}

if ($step == 3) {



$DB_site->query("DELETE FROM template WHERE title='index_weather' AND templatesetid=-1");
echo "Template <i>index_weather</i> removed.<p>\n";

$DB_site->query("DELETE FROM template WHERE title='index_weather_main' AND templatesetid=-1");
echo "Template <i>index_weather_main</i> removed.<p>\n";

$DB_site->query("DELETE FROM template WHERE title='index_weather_redirect_updatethanks' AND templatesetid=-1");
echo "Template <i>index_weather_redirect_updatethanks</i> removed.<p>\n";

$DB_site->query("DELETE FROM template WHERE title='index_weather_select' AND templatesetid=-1");
echo "Template <i>index_weather_select</i> removed.<p><br>\n";


$DB_site->query("DROP TABLE `weather_city`");
echo "Table <i>weather_city</i> removed.<p>\n";

$DB_site->query("DROP TABLE `weather_country`");
echo "Table <i>weather_country</i> removed.<p>\n";

$DB_site->query("DROP TABLE `weather_region`");
echo "Table <i>weather_region</i> removed.<p>\n";

$DB_site->query("DROP TABLE `weather_subdiv`");
echo "Table <i>weather_subdiv</i> removed.<p>\n";

$DB_site->query("DROP TABLE `weather_userdata`");
echo "Table <i>weather_userdata</i> removed.<p>\n";

$DB_site->query("DROP TABLE `weather_usersettings`");
echo "Table <i>weather_usersettings</i> removed.<p><br>\n";


echo "<p><B>Uninstall completed successfully. You should also be sure to remove all modifications to your vBulletin files that you made when you first installed the script.</B></p><p>\n";
echo "<p><a href=\"index.php\"><b>Log into Admin CP --&gt;</b></a>\n";


}


echo "</"."body>";
echo "<"."!--";
?>

-->
</html>