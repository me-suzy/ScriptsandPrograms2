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
|   > Hauptseite Admin Center
|
|	> Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-
|	> bedingungen (read-me.html) für weitere Informationen.
|	>-----------------------------------------------------------------
|	> This script is NOT freeware! Please read the Copyright Notice
|	> (read-me_eng.html) for further information. 
|
|	> $Id: main.php 2 2005-10-08 09:40:29Z alex $
|
+--------------------------------------------------------------------------
*/

define("FILE_NAME","main.php");

include_once('adminfunc.inc.php');
include_once($_ENGINE['eng_dir']."admin/enginelib/function.".ENG_TYPE.".php");
$auth->checkEnginePerm("canaccessadmincent");

if($step == 'maincat') {
	header("Location: ".$sess->adminUrl("newscat.php?step=maincat"));
	exit;
	}

if($step == 'cat') {
	header("Location: ".$sess->adminUrl("newscat.php?step=edit"));
	exit;
	}
   
if($step == 'postnews') {
	header("Location: ".$sess->adminUrl("news.php?step=post"));
	exit;
	}
   
if($step == 'headsearch') {
	header("Location: ".$sess->adminUrl("news.php?step=cat"));
	exit;
	}
   
if($step == 'head') {
	header("Location: ".$sess->adminUrl("news.php?step=down"));
	exit;
	}

if($step == 'gen_set') {
	header("Location: ".$sess->adminUrl("settings.php?step=gen_set"));
	exit;
	}

if($step == 'col_set') {
	header("Location: ".$sess->adminUrl("settings.php?step=col_set"));
	exit;
	}
	
if($step == 'new_set') {
	header("Location: ".$sess->adminUrl("settings.php?step=new_set"));
	exit;
	}
	

if($step == 'member_add') {
	header("Location: ".$sess->adminUrl("member.php?step=add"));
	exit;
	}

if($step == 'member_change') {
	header("Location: ".$sess->adminUrl("member.php?step=change"));
	exit;
	}
	
if($step == 'member_search') {
	header("Location: ".$sess->adminUrl("member.php?step=search"));
	exit;
	}

if($step == 'avat_upload') {
	header("Location: ".$sess->adminUrl("avatar.php?step=add"));
	exit;
	}

if($step == 'avat_edit') {
	header("Location: ".$sess->adminUrl("avatar.php?step=edit"));
	exit;
	}
if($step == 'lang') {
	header("Location: ".$sess->adminUrl("language.php?step=lang"));
	exit;
	}		
if($step == 'template_edit') {
	header("Location: ".$sess->adminUrl("templates.php?step=edithtml"));
	exit;
	}		
	


$result = $db_sql->sql_query("SELECT * FROM $newscomment_table");
$kum_i = $db_sql->num_rows($result);	

$result2 = $db_sql->sql_query("SELECT * FROM $news_table");
$news_i = $db_sql->num_rows($result2);	

$result3 = $db_sql->sql_query("SELECT * FROM $user_table");
$user_i = $db_sql->num_rows($result3);
$user_i = $user_i - 1;	

if(BOARD_DRIVER == "default") {
	$result5 = $db_sql->sql_query("SELECT count(activation) as wartend FROM $user_table WHERE activation!='1' && userid!='2'");
	$unconfirmed = $db_sql->fetch_array($result5);
	$unconfirmed = $unconfirmed['wartend'];	
}

include_once($_ENGINE['eng_dir']."admin/enginelib/class.db_utils.php");
$db_utils = new engineMysqlUtils();

buildAdminHeader();

include_once($_ENGINE['eng_dir']."admin/enginelib/class.bbcode.php");
$bbcode = new engineBBCode(1,0,1,0,$config['smilieurl']);	
if(@file_exists('./../installer.php')) $warning = "<div align=\"center\"><font color=\"FF0000\" size=\"2\"><b>$a_lang[main_installer]</b></font></div>";
?>
<table width="100%" align="center">
	<tr valign="middle">
		<td><table cellpadding="3" cellspacing="1" width="95%" bgcolor="000000" align="center">
			<tr>
				<td class="menu_desc"><b>&raquo; <?php echo $a_lang['main_welcome']." ".$auth->user['username']; ?></b></td>
			</tr>
			<tr>
				<td class="othercolumn"><b><?php echo $a_lang['main_head']; ?></b></td>
			</tr>
			<tr>
				<td class="menu_desc"><b>&raquo; <?php echo $a_lang['main_stat']; ?></b></td>
			</tr>
			<tr>
				<td class="othercolumn">
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td width="50%"><?php echo $a_lang['main_reguser']; ?>: <b><?php echo "$user_i </b>"; ?></td>
                    <td width="50%">
                    <?php 
					if(BOARD_DRIVER == "default") {
					echo $a_lang['main_notactive']; ?>: <b>
					<?php echo $unconfirmed; ?></b> [<a href="<?php echo $sess->adminUrl("member.php?step=activation") ?>"><?php echo $a_lang['main_activate']; ?></a>]
					<?php }?>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $a_lang['main_avnews']; ?>: <b><?php echo $news_i; ?></b></td>
                    <td><?php echo $a_lang['main_comoverall']; ?>: <b><?php echo $kum_i; ?></b></td>
                </tr>
                <tr>
                    <td colspan="2"><?php echo $a_lang['main_databasesize']; ?>: <b><?php echo rebuildFileSize($db_utils->buildDatabaseSize($dbName)); ?></b></td>
                </tr>
                </table>
                <?php echo "<br>".$warning; ?>
                </td>
			</tr>
		</table></td>
	</tr>
</table>
<br>
<br>
<table width="100%" align="center">
	<tr valign="middle">
		<td><table cellpadding="3" cellspacing="1" width="95%" bgcolor="000000" align="center">
			<tr>
				<td class="menu_desc"><b>&raquo; <?php echo $a_lang['main_confirm']; ?></b></td>
			</tr>
			<tr>
				<td class="othercolumn">

<?php
echo "
<p class=\"index\"><img class=\"nav\" src=\"images/new.gif\" width=\"24\" height=\"24\" border=\"0\" align=\"absmiddle\"> <b>$a_lang[main_newcom]:</b>";
ConfirmComment();
echo "</p>
<p class=\"index\"><img class=\"nav\" src=\"images/new.gif\" width=\"24\" height=\"24\" border=\"0\" align=\"absmiddle\"> <b>$a_lang[main_newnews]:</b>";
ConfirmNews2();
?>

				</td>
			</tr>
		</table></td>
	</tr>
</table>
<br>

<table width="100%" align="center">
	<tr valign="middle">
		<td><table cellpadding="3" cellspacing="1" width="95%" bgcolor="000000" align="center">
			<tr>
				<td class="menu_desc"><b>&raquo; <?php echo $a_lang['main_imthings']; ?>:</b></td>
			</tr>
			<tr>
				<td class="othercolumn">
				<b>Homepage:</b> <a target="_blank" class="menu" href="http://www.alexscriptengine.de">http://www.alexscriptengine.de</a><br>
				<b>Support:</b> <a target="_blank" class="menu" href="http://www.alexscriptengine.de/v2/prog_engine/bug.php?pid=7">Bug-Tracker</a> | <a target="_blank" class="menu" href="http://www.alexscriptengine.de/v2/prog_engine/request.php?pid=7">Feature-Request</a> | <a target="_blank" class="menu" href="http://www.alexscriptengine.de/v2/prog_engine/faq.php?pid=7">FAQ</a><br>
				<b>PHP:</b> <a target="_blank" class="menu" href="http://www.php.net">PHP - Homepage</a><br>
				<b>MySQL:</b> <a target="_blank" class="menu" href="http://www.mysql.org">MySQL - Homepage</a></td>
			</tr>
			<tr>
				<td class="firstcolumn"><b><?php echo $a_lang['main_installed']; ?>:</b> V<?php echo APP_VERSION; ?></td>
			</tr>
		</table>
		</td>
	</tr>
</table>


<?php

buildAdminFooter();
?>