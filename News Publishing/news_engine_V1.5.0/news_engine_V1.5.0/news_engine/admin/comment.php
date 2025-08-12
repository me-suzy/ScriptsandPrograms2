<?php
/*
+--------------------------------------------------------------------------
|   Alex News Engine
|   ========================================
|   by Alex Höntschel
|   (c) 2002 AlexScriptEngine
|   http://www.alexscriptengine.de
|   ========================================
|   Web: http://www.alexscriptengine.de
|   Email: info@alexscriptengine.de
+---------------------------------------------------------------------------
|
|   > Beschreibung
|   > Kommentar-Funktionen Admin Center
|
|	> Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-
|	> bedingungen (read-me.html) für weitere Informationen.
|	>-----------------------------------------------------------------
|	> This script is NOT freeware! Please read the Copyright Notice
|	> (read-me_eng.html) for further information. 
|
|	> $Id: comment.php 2 2005-10-08 09:40:29Z alex $
|
+--------------------------------------------------------------------------
*/

define("FILE_NAME","comment.php");

include_once('adminfunc.inc.php');
include_once($_ENGINE['eng_dir']."admin/enginelib/function.".ENG_TYPE.".php");
$auth->checkEnginePerm("canaccessadmincent");

buildAdminHeader();

if ($step == 'conf') {
   $db_sql->sql_query("UPDATE $newscomment_table SET com_status='1' WHERE comid='$comid'");
   $message = $a_lang['comment_mes1'];
   }
   
if ($action == 'conf_multi') {
    if($_POST['public']) {
    	foreach($comid as $key=>$wert) {
    		$db_sql->sql_query("UPDATE $newscomment_table SET com_status='1' WHERE comid='$key'");
    	}	
        $message = $a_lang['comment_mes1'];        
    } elseif($_POST['delete']) {
    	foreach($comid as $key=>$wert) {
            $db_sql->sql_query("DELETE FROM $newscomment_table WHERE comid='$key'");
    	}	
        $message = $a_lang['comment_mes2'];     
    }
}   
   
if ($step == 'del') {
   $db_sql->sql_query("DELETE FROM $newscomment_table WHERE comid='$comid'");
   $message = $a_lang['comment_mes2'];
   }
   
if ($message != "") {
    buildMessageRow($message, array('auto_redirect' => 'main.php', 'is_top' => 1, 'next_script' => 'main.php', 'next_action' => array('','',$a_lang['afunc_proceed'])));
    buildAdminFooter();
    exit;
}

if ($step == 'details') {
    include_once($_ENGINE['eng_dir']."admin/enginelib/class.bbcode.php");
    $bbcode = new engineBBCode(1,0,1,0,$config['smilieurl']);	
	$result = $db_sql->sql_query(" SELECT $newscomment_table.*, $news_table.headline, $newscat_table.* FROM $newscomment_table 
							LEFT JOIN $news_table ON $news_table.newsid = $newscomment_table.newsid
							LEFT JOIN $newscat_table ON $news_table.catid = $newscat_table.catid
							WHERE comid='$comid'");
							
    $com = $db_sql->fetch_array($result);
			 
	if ($com['user_comname'] == '0') {
		$writer = CheckUserID($com['userid']);
		$author = "<a class=\"menu\" href=\"mailto:$writer[useremail]\">$writer[username]</a>";
	} elseif ($com['user_comname'] == "") {
		$writer = CheckUserID($com['userid']);
		$author = "<a class=\"menu\" href=\"mailto:$writer[useremail]\">$writer[username]</a>";
	} else {
		$author = "$com[user_comname] - GAST";
	}
        			 
	$com_date = $com['com_date'];
	$date = getdate($com_date);
					 
	if ($com['posticon'] == "") {
		$picon = "";
	} else {
		$picon = "<img src=\"$com[posticon]\">";
	}
	$post_comment = $bbcode->rebuildText($com['post_comment']);
	$post_comment = trim($post_comment);
	
	$com['com_headline'] = trim(stripslashes($com['com_headline']));
	buildHeaderRow($a_lang['comment_det'],"search.gif");
	buildTableHeader($a_lang['comment_main']);
	buildStandardRow($a_lang['comment_news'], "$com[headline] (ID: $com[newsid])");
	buildStandardRow($a_lang['comment_categ'], "$com[titel] (ID: $com[catid])");
	buildStandardRow($a_lang['comment_written'], "$author $a_lang[comment_at] $date[mday].$date[mon].$date[year]");
	buildTableSeparator($a_lang['comment_headcomment']);
	buildStandardRow($a_lang['comment_headline'], "$picon $com[com_headline]");
	buildStandardRow($a_lang['comment'], $post_comment);
	buildTableFooter();
	closeWindowRow();
	}


buildAdminFooter();
?> 