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
|   > Einstellungen Farben, News etc. Admin Center
|
|	> Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-
|	> bedingungen (read-me.html) für weitere Informationen.
|	>-----------------------------------------------------------------
|	> This script is NOT freeware! Please read the Copyright Notice
|	> (read-me_eng.html) for further information. 
|
|	> $Id: settings.php 2 2005-10-08 09:40:29Z alex $
|
+--------------------------------------------------------------------------
*/

define("FILE_NAME","settings.php");

include_once('adminfunc.inc.php');
include_once($_ENGINE['eng_dir']."admin/enginelib/function.".ENG_TYPE.".php");
$auth->checkEnginePerm("canaccessadmincent");

$message = '';

if(isset ($action) && $action=='gen') {    
    while (list($key,$val)=each($setting)) {
        $db_sql->sql_query("UPDATE $set_table SET replace_value='".addslashes($val)."' WHERE find_word='$key'");
    }                   
	$message .= $a_lang['settings_mes2'];
    $config = loadEngineSetting();
    $step = 'gen_set';
}
	
if(isset ($action) && $action=='new') {

	if ($headlineno > $newsno) $headlineno = $newsno;
    while (list($key,$val)=each($setting)) {
        $db_sql->sql_query("UPDATE $set_table SET replace_value='".addslashes($val)."' WHERE find_word='$key'");
    } 
	$message .= $a_lang['settings_mes3'];
    $config = loadEngineSetting();
	$step = 'new_set';
}

if(isset ($action) && $action=='onoff') {
    $db_sql->sql_query("UPDATE $set_table SET replace_value='".addslashes($isoffline)."' WHERE find_word='isoffline'");
    $db_sql->sql_query("UPDATE $set_table SET replace_value='".addslashes($offline_why)."' WHERE find_word='offline_why'");
	$message .= $a_lang['settings_onoff'];
    $config = loadEngineSetting();
    $step = 'onoff';	
} 
    
buildAdminHeader();

if ($message != "") {
    buildMessageRow($message, array('is_top' => 1, 'next_script' => 'settings.php', 'next_action' => array('step',$step,$a_lang['afunc_proceed'])));
    buildAdminFooter();
    exit;
}

if(!isset ($step) && $change == '' && $action == '') {
  echo " <p><b>Es wurde kein Einstelltyp gewählt. Bitte wähle links aus der Navigation die gewünschte Option aus. $step</b></p>";
} else {
    if($step == 'gen_set') {
        buildHeaderRow($a_lang['afunc_84'],"setting.gif",1);
        buildInfo($a_lang['info10'][0],$a_lang['info10'][1]);	
        buildFormHeader("settings.php", "post", "gen");
        buildHiddenField("styleid","1");
        buildTableHeader($a_lang['afunc_85']);		
		buildInputRow($a_lang['afunc_86'], "setting[mainurl]", $config['mainurl']);
		buildInputRow($a_lang['afunc_87'], "setting[newsscripturl]", $config['newsscripturl']);
		//buildInputRow($a_lang['afunc_88'], "setting[smilieurl]", $config['smilieurl']);
		//buildInputRow($a_lang['afunc_89'], "setting[grafurl]", $config['grafurl']);
		buildInputRow($a_lang['afunc_90'], "setting[avaturl]", $config['avaturl']);
		buildTableSeparator($a_lang['afunc_93']);
		buildInputRow($a_lang['afunc_94'], "setting[scriptname]", $config['scriptname']);
		buildInputRow($a_lang['afunc_95'], "setting[admin_mail]", $config['admin_mail']);
        buildInputYesNo($a_lang['settings_7'], "setting[use_smtp]", $config['use_smtp']);
        buildInputRow($a_lang['settings_8'], "setting[smtp_server]", $config['smtp_server'], "40");
        buildInputRow($a_lang['settings_9'], "setting[smtp_username]", $config['smtp_username'], "40");
        buildInputRow($a_lang['settings_10'], "setting[smtp_password]", $config['smtp_password'], "40");        
		buildInputRow($a_lang['afunc_96'], "setting[mainwidth]", $config['mainwidth']);
		buildTableSeparator($a_lang['afunc_97']);
		buildInputYesNo($a_lang['afunc_98'], "setting[commentmail]", $config['commentmail']);
		if ($config['guestpost'] == 1) {
			$check8 = "selected";
		} else {
			$check9 = "selected";
		}
        $visitor = $db_sql->query_array("SELECT canpostcomments FROM $group_table WHERE groupid='4'");
        $guest_option = "<select class=\"input\" name=\"setting[guestpost]\" size=\"2\" $add>\n<option value=\"1\" $check8>$a_lang[afunc_101]\n<option value=\"2\" $check9>$a_lang[afunc_102]\n</select>";	
		
		if($visitor['canpostcomments']) buildStandardRow($a_lang['afunc_99'], $guest_option);
		buildInputYesNo($a_lang['afunc_103'], "setting[directpost]", $config['directpost']);
		buildInputRow($a_lang['afunc_257'], "setting[max_comment_length]", $config['max_comment_length']);
		buildTableSeparator($a_lang['afunc_104']);
		buildInputYesNo($a_lang['afunc_105'], "setting[activategzip]", $config['activategzip']);
		buildInputRow($a_lang['afunc_106'], "setting[gziplevel]", $config['gziplevel']);
		buildTableSeparator($a_lang['settings_1']);
		$timeoffset_select .= "<select class=\"input\" name=\"setting[timeoffset]\">\n";
		for($i = -12; $i <= 12; $i++) {
			$timeoffset_select .= "<option value=\"$i\"";
			if($i == $config['timeoffset']) $timeoffset_select .= " selected";
			$timeoffset_select .= ">GMT $i:00 $a_lang[settings_2]</option>\n";
		}	
		$timeoffset_select .= "</select>\n";
			
		buildStandardRow($a_lang['settings_3'], $timeoffset_select);
		buildInputRow($a_lang['settings_4'], "setting[longdate]", $config['longdate']);
		buildInputRow($a_lang['settings_5'], "setting[shortdate]", $config['shortdate']);
		buildInputRow($a_lang['settings_6'], "setting[timeformat]", $config['timeformat']);		
		buildTableSeparator($a_lang['afunc_107']);
		buildInputRow($a_lang['afunc_314'], "setting[std_group]", $config['std_group']);
		//buildInputYesNo($a_lang['afunc_92'], "addtplname", $config['addtplname']);
		buildInputYesNo($a_lang['afunc_108'], "setting[showvisitorinfo]", $config['showvisitorinfo'],1);
		buildInputYesNo($a_lang['afunc_109'], "setting[userreg]", $config['userreg'],1);
		buildInputYesNo($a_lang['afunc_110'], "setting[userlogin]", $config['userlogin'],1);
		buildInputYesNo($a_lang['afunc_280'], "setting[reg_withmail]", $config['reg_withmail']);
		buildFormFooter($a_lang['afunc_57'], "");

    }
    
    if($step == 'info') {
        buildHeaderRow($a_lang['path_and_url_external_use'],"setting.gif");
        buildTableHeader($a_lang['path']);
        buildStandardRow($a_lang['path_to_the_engine'], $_ENGINE['eng_dir']);
        buildTableSeparator("Url's");
        $result = $db_sql->sql_query("SELECT * FROM $newscat_table");
        while($cat = $db_sql->fetch_array($result)) {
            $cat = stripslashes_array($cat);
            buildStandardRow(sprintf($a_lang['url_to_display_category'],$cat['titel'],$cat['titel']), $config['newsscripturl']."/index.php?showcat=jump&f=".$cat['catid']);
        }
        buildTableFooter();
    }

	if($step == 'new_set') {
        buildHeaderRow($a_lang['afunc_58'],"setting.gif");
        buildInfo($a_lang['info10'][0],$a_lang['info10'][1]);	
        buildFormHeader("settings.php", "post", "new");
        buildHiddenField("styleid","1");
        buildTableHeader($a_lang['afunc_59']);
		buildInputRow($a_lang['afunc_60'], "setting[newsno]", $config['newsno']);
		buildInputYesNo($a_lang['afunc_322'], "setting[activate_rss]", $config['activate_rss']);
        buildInputYesNo($a_lang['afunc_323'], "setting[enable_newsletter]", $config['enable_newsletter']);
        buildTableSeparator($a_lang['settings_newsdisplay']);
        buildInputYesNo($a_lang['afunc_63'], "setting[cat_pics]", $config['cat_pics']);
		//buildInputYesNo($a_lang['alternating_news_color'], "alternating_bg", $config['alternating_bg']);
        buildInputYesNo($a_lang['mail_a_friend'], "setting[activate_recommendation]", $config['activate_recommendation']);
		buildInputYesNo($a_lang['display_categorie_names'], "setting[categorie_before_headline]", $config['categorie_before_headline']);        
		buildInputRow($a_lang['settings_category_start_tags'], "setting[start_category_html]", $config['start_category_html']);		
		buildInputRow($a_lang['settings_category_end_tags'], "setting[end_category_html]", $config['end_category_html']);				
		$date_option = "<select class=\"input\" name=\"setting[newsdate]\">\n";
        $date_option .= "<option value=\"1\" ";
        if($config['newsdate'] == 1) $date_option .= "selected";
        $date_option .= ">".$a_lang['settings_newsdisplay_date']."</option>\n";
        $date_option .= "<option value=\"2\" ";
        if($config['newsdate'] == 2) $date_option .= "selected";
        $date_option .= ">".$a_lang['settings_newsdisplay_date_time']."</option>\n";
        $date_option .= "<option value=\"3\" ";
        if($config['newsdate'] == 3) $date_option .= "selected";
        $date_option .= ">".$a_lang['settings_newsdisplay_day_date']."</option>\n";    
        $date_option .= "<option value=\"4\" ";
        if($config['newsdate'] == 4) $date_option .= "selected";
        $date_option .= ">".$a_lang['settings_newsdisplay_day_date-time']."</option>\n";            
        $date_option .= "</select>";	           
        buildStandardRow($a_lang['date_n_timesettings_news'], $date_option);
		buildTableSeparator($a_lang['afunc_64']);		
		buildInputYesNo($a_lang['afunc_65'], "setting[direct_news]", $config['direct_news']);
		buildInputYesNo($a_lang['afunc_66'], "setting[click_smilies]", $config['click_smilies']);	
		buildTableSeparator($a_lang['afunc_67']);		
		buildInputYesNo($a_lang['afunc_68'], "setting[show_headline]", $config['show_headline']);
		buildInputRow($a_lang['afunc_69'], "setting[headlineno]", $config['headlineno']);
		buildInputYesNo($a_lang['afunc_70'], "setting[show_catlink]", $config['show_catlink']);
        buildTableSeparator($a_lang['wysiwyg_settings']);
        buildInputYesNo($a_lang['wysiwyg_editor_in_userarea'], "setting[wysiwyg_user]", $config['wysiwyg_user']);
        buildInputYesNo($a_lang['wysiwyg_editor_in_admincenter'], "setting[wysiwyg_admin]", $config['wysiwyg_admin']);
		buildTableSeparator($a_lang['afunc_71']);	
		($config['archivsort'] == 1) ?	$check11 = "selected" : $check12 = "selected";
		$archiv_option = "<select class=\"input\" name=\"setting[archivsort]\">\n<option value=\"1\" $check11>$a_lang[afunc_73]\n<option value=\"2\" $check12>$a_lang[afunc_74]\n</select>";	
		buildStandardRow($a_lang['afunc_72'], $archiv_option);
		$archiv_option2 = "<select class=\"input\" name=\"setting[archive_view]\">\n";
        $archiv_option2 .= "<option value=\"1\" ";
        if($config['archive_view'] == 1) $archiv_option2 .= "selected";
        $archiv_option2 .= ">".$a_lang['by_month_year']."</option>\n";
        $archiv_option2 .= "<option value=\"0\" ";
        if($config['archive_view'] != 1) $archiv_option2 .= "selected";
        $archiv_option2 .= ">".$a_lang['by_year']."</option>\n";
        $archiv_option2 .= "</select>";	        
        buildStandardRow($a_lang['archive_splitting'], $archiv_option2);
		buildFormFooter($a_lang['afunc_57'], "");
		
		
		
	}
  
    if($step == 'onoff') {
        buildHeaderRow($a_lang['afunc_282'],"setting.gif",1);
        buildInfo($a_lang['info12'][0],$a_lang['info12'][1]);	
        buildFormHeader("settings.php", "post", "onoff");
        buildHiddenField("styleid","1");
        buildTableHeader($a_lang['afunc_283']);
        
        echo "
        <tr class=\"".switchBgColor()."\">
        <td>
        $a_lang[afunc_284]:
        </td>
        <td>
        <select class=\"input\" name=\"isoffline\">
        <option value=\"0\""; if($config['isoffline'] == 0) echo "selected";
        echo "
        >$a_lang[afunc_285]</option>
        <option style=\"color: red;\" value=\"1\""; if($config['isoffline'] == 1) echo "selected";
        echo "
        >$a_lang[afunc_286]</option>
        </select>
        </td>
        </tr>";
        
        buildTextareaRow($a_lang['afunc_287'], "offline_why", $config['offline_why'], "80","10","","wrap=\"off\"");
        buildFormFooter($a_lang['afunc_57'], "");
    } 
}

buildAdminFooter();
?>