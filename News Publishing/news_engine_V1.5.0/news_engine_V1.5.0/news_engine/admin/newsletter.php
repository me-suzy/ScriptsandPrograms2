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
|   > News-Funktionen Admin Center
|
|	> Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-
|	> bedingungen (read-me.html) für weitere Informationen.
|	>-----------------------------------------------------------------
|	> This script is NOT freeware! Please read the Copyright Notice
|	> (read-me_eng.html) for further information. 
|
|	> $Id: newsletter.php 2 2005-10-08 09:40:29Z alex $
|
+--------------------------------------------------------------------------
*/

define("FILE_NAME","newsletter.php");

include_once('adminfunc.inc.php');
include_once($_ENGINE['eng_dir']."admin/enginelib/function.".ENG_TYPE.".php");
$auth->checkEnginePerm("canaccessadmincent");

$message = "";
$max_fsize = "2097152";

if($action == "confdel") {
	$db_sql->sql_query("DELETE FROM $newsletter_table WHERE aboid='".$aboid."'");
	$message = $a_lang['newsletter_1'];
	$step = "mailinglist";
}

if($action == "new_abo") {
	$db_sql->sql_query("INSERT INTO $newsletter_table (abouser,abomail,abostart) VALUES ('".addslashes(htmlspecialchars($abouser))."','".addslashes($abomail)."','".time()."')");
	$message = $a_lang['newsletter_2'];
	$step = "mailinglist";
}

if($action == "edit_abo") {
	$db_sql->sql_query("UPDATE $newsletter_table SET abouser='".addslashes(htmlspecialchars($abouser))."', abomail='".addslashes($abomail)."' WHERE aboid='".$aboid."'");
	$message = $a_lang['newsletter_3'];
	$step = "mailinglist";
}

if($action == "send") {
    if($letter_type == "html") {
        $htmltemplate = "../".$_ENGINE['template_folder']."/newsletter_html.html";
    } else {
        $htmltemplate = "../".$_ENGINE['template_folder']."/newsletter_txt.txt";
    }
    
    $template= fopen($htmltemplate,"r");
    $newsletter = fread($template,filesize($htmltemplate));
    fclose($template);     
    
    $newsletter = eregi_replace("{sitename}", $config['scriptname'], $newsletter);
    $newsletter = eregi_replace("{newstext}", $lettertext, $newsletter);
    $newsletter = eregi_replace("{anrede}", $anrede, $newsletter);
    $newsletter = eregi_replace("{disclaimer}", $a_lang['disclaimer'], $newsletter);
    $newsletter = eregi_replace("{fontf}", $config['fontf'], $newsletter);
    $newsletter = eregi_replace("{stoplink}", $config['newsscripturl']."/index.php?action=newsletter&abomail=0&abouser={abouser}&abomail={abomail}", $newsletter);
    
    include_once($_ENGINE['eng_dir']."admin/enginelib/class.phpmailer.php");
    $mail = new PHPMailer();
    $mail->SetLanguage($lang['php_mailer_lang'], $_ENGINE['eng_dir']."lang/".$config['language']."/");
    if($config['use_smtp']) {
        $mail->IsSMTP();
        $mail->Host = $config['smtp_server']; 
        $mail->SMTPAuth = true;
        $mail->Username = $config['smtp_username']; 
        $mail->Password = $config['smtp_password']; 
    } else {
        $mail->IsMail();
    }      
    
    $mail->From = $config['admin_mail'];
    $mail->FromName = $config['scriptname'];  
    $mail->Subject = $subject;      
    
    $result = $db_sql->sql_query("SELECT * FROM $newsletter_table");
    $i = 0;
    $base_letter = $newsletter;
    while($letter = $db_sql->fetch_array($result)) {
        unset($newsletter);
        if($letter_type == "html") {
            $newsletter = eregi_replace("{abouser}", $letter['abouser'], $base_letter);
			$newsletter = eregi_replace("{abomail}", $letter['abomail'], $newsletter);
            $newsletter = eregi_replace("{disclaimer}", $config['newsscripturl']."/index.php?action=newsletter&abouser=".$letter['abouser']."&abomail=".$letter['abomail'], $newsletter);
            $body  = $newsletter;
            $textletter = eregi_replace("<br>", "\n", $newsletter);
            $text_body  = strip_tags($textletter);
            
            $mail->Body    = $body;
            $mail->AltBody = $text_body;            
        } else {
            $newsletter = eregi_replace("{abouser}", $letter['abouser'], $base_letter);
			$newsletter = eregi_replace("{abomail}", $letter['abomail'], $newsletter);
            $newsletter = eregi_replace("{disclaimer}", $config['newsscripturl']."/index.php?action=newsletter&abouser=".$letter['abouser']."&abomail=".$letter['abomail'], $newsletter);
            $body  = $newsletter; 
            $mail->Body = $body;                   
        }
        
        $mail->AddAddress($letter['abomail'], $letter['abouser']);
        
        if(!$mail->Send()) $error_message .= sprintf($a_lang['newsletter_4'],$letter['abomail']).$letter['abouser'];        
        $i++;
        $mail->ClearAddresses();
    }
    
    $message = sprintf($a_lang['newsletter_5'],$i,$error_message);
}
   
buildAdminHeader();

if ($message != "") buildMessageRow($message);

if($step == 'fullfill_letter') {
    if($date_start || $date_end || $catid) $where = " WHERE ";
    
    if($catid) {
		$cat_array = count($catid)-1;
		
		foreach($catid as $key=>$val) {
			if($key >= 1) {
				$where .= $val;	
				if($cat_array != $key) {
					$where .= ",";
				} elseif($cat_array == $key) {
					$where .= ")";
				}					
			} else {
				$where .= " catid IN(".$val;	
				if($cat_array != $key) {
					$where .= ",";
				} else {
					$where .= ")";
				}								
			}
		}		
    }
	
	if($catid && ($date_start || $date_end)) $where .= " AND ";
	
    if($date_end) {
        if(eregi("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})",$date_end, $end)) {
            $stopdate = mktime(23,59,59,$end[2],$end[3],$end[1]);
        }    
        if($date_start) {
            $where .= "(newsdate < '".$stopdate."'";    
        } else {
            $where .= "newsdate > '".$stopdate."'";        
        }
    }
    
    if($date_start) {
        if(eregi("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})",$date_start, $start)) {
            $startdate = mktime(0,0,0,$start[2],$start[3],$start[1]);
        }       
        if($date_end) {
            $where .= " AND newsdate > '".$startdate."')";
        } else {
            $where .= "newsdate > '".$startdate."'";        
        }
    }    	

    $result = $db_sql->sql_query("SELECT * FROM $news_table $where ORDER BY newsdate");
    $i = 0;
    
    if($letter_type == "html") {
        include_once($_ENGINE['eng_dir']."admin/enginelib/class.bbcode.php");
        $bbcode = new engineBBCode(1,0,1,0,$config['smilieurl']);    
        $news['hometext'] = $bbcode->rebuildText($news['hometext']);		
    }
    
    while($news = $db_sql->fetch_array($result)) {
		$news = stripslashes_array($news);
        if($i!=0) $newstext .= "\n\n";
        if($letter_type == "html") $newstext .= "<p>\n<b>";
        $newstext .= "[".aseDate($config['shortdate'],$news['newsdate'])."]  ".$news['headline'];    
        if($letter_type == "html") $newstext .= "</b>";
        if($letter_type == "html") {
            $newstext .= "\n<hr>";
        } else {
            $newstext .= "\n----------------------------------------------------\n";
        }
        
        if($letter_type == "html") {
            $newstext .= "\n".$bbcode->rebuildText($news['hometext'])."\n";
            $newstext .= $bbcode->rebuildText($news['newstext']);
        } else {
            $newstext .= $news['hometext']."\n";
            $newstext .= $news['newstext']."\n";
        }        
        if($letter_type == "html") $newstext .= "</p>";
        $i++;
    }
    
    buildHeaderRow($a_lang['newsletter_6'],"newsletter.gif");
    buildFormHeader("newsletter.php", "post", "send");
    buildHiddenField("letter_type",$letter_type);
    buildTableHeader($a_lang['newsletter_7']);
    buildInputRow($a_lang['newsletter_8'], "subject");
    buildInputRow($a_lang['newsletter_9'], "anrede");
    buildTextareaRow($a_lang['newsletter_10'], "lettertext",$newstext,75,20);
    buildFormFooter($a_lang['newsletter_11'], $a_lang['newsletter_12']);
} 

if($step == 'newsletter') {
    buildHeaderRow($a_lang['newsletter_13'],"newsletter.gif");
    if(!$config['enable_newsletter']) buildHint($a_lang['newsletter_14'],$a_lang['newsletter_14b']);
    buildFormHeader("newsletter.php", "post", "");
    buildHiddenField("step","fullfill_letter");
    buildTableHeader($a_lang['newsletter_15']);
    $cat_row = "<select class=\"input\" name=\"letter_type\">\n<option value=\"html\">".$a_lang['newsletter_16']."</option>\n<option value=\"text\">".$a_lang['newsletter_17']."</option>\n</select>";
    buildStandardRow($a_lang['newsletter_18'], $cat_row);
    
    $cat_row2 = "<select class=\"input\" name=\"catid[]\" multiple size=\"4\">\n<option value=\"0\">--- ".$a_lang['newsletter_19']." ---</option>\n".GetCateg()."</select>";
    buildStandardRow($a_lang['newsletter_20'], $cat_row2);
	
	include_once($_ENGINE['eng_dir']."admin/enginelib/class.calendar.php");
	$calendar = new DHTML_Calendar($config['newsscripturl'].'/admin/includes/calendar/', $lang['php_mailer_lang']);
	$calendar->load_files();
	$calendar->make_input_field(
	           array('firstDay'=>1,'showsTime'=>true,'showOthers'=>true,'ifFormat'=>'%Y-%m-%d %H:%M:%S','timeFormat'=> '24'),
	           array('size'=>'40','name'=>'date_start','value'=>''),
			   $a_lang['newsletter_21']);
	$calendar->make_input_field(
	           array('firstDay'=>1,'showsTime'=>true,'showOthers'=>true,'ifFormat'=>'%Y-%m-%d %H:%M:%S','timeFormat'=> '24'),
	           array('size'=>'40','name'=>'date_end','value'=>''),
			   $a_lang['newsletter_22']);			   
    buildFormFooter($a_lang['newsletter_23'], $a_lang['newsletter_12']);
} 

if($step == 'mailinglist') {
    buildHeaderRow($a_lang['newsletter_24'],"newsletter.gif");
	
    $result = $db_sql->sql_query("SELECT Count(*) as total FROM $newsletter_table");
    $over_all = $db_sql->fetch_array($result);    
    
    if(!class_exists(Nav_Link)) include_once($_ENGINE['eng_dir']."admin/enginelib/class.nav.php");
    if(!isset($start)) $start = 0;
    $nav = new Nav_Link();
    $nav->overAll = $over_all['total'];
    $nav->DisplayLast = 1;
    $nav->DisplayFirst = 1;
    $nav->perPage = 20;
    $nav->MyLink = $sess->adminUrl("newsletter.php?step=mailinglist")."&";
    $nav->LinkClass = "smalltext";
    $nav->start = $start;
    $pagecount = $nav->BuildLinks();
    $pages = intval($over_all['total'] / 20);
    if($over_all['total'] % 20) $pages++;				
    if(!$pagecount) $pagecount = "<b>1</b>";	
	
	buildTableHeader($a_lang['newsletter_25']."<br><span class=\"smalltext\">&nbsp;$a_lang[afunc_294] ($pages): $pagecount</span>");
	$result2 = $db_sql->sql_query("SELECT * FROM $newsletter_table ORDER BY abostart LIMIT $start,20");
	while($letter = $db_sql->fetch_array($result2)) {
		$letter = stripslashes_array($letter);
		buildStandardRow("(ID: <b>".$letter['aboid']."</b>) ".$letter['abouser'], "<a class=\"menu\" href=\"".$sess->adminUrl("newsletter.php?step=create&aboid=".$letter['aboid'])."\"><img src=\"images/edit.gif\" alt=\"".$a_lang['afunc_292']."\" width\"16\" height=\"16\" border=\"0\" align=\"absmiddle\">".$a_lang['afunc_292']."</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class=\"menu\" href=\"".$sess->adminUrl("newsletter.php?step=del&aboid=".$letter['aboid'])."\"><img src=\"images/delart.gif\" alt=\"".$a_lang['afunc_293']."\" width\"16\" height=\"16\" border=\"0\" align=\"absmiddle\">".$a_lang['afunc_293']."</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"mailto:".$letter['abomail']."\"><img src=\"images/mail.gif\" alt=\"".$a_lang['newsletter_26']."\" width\"16\" height=\"16\" border=\"0\" align=\"absmiddle\">".$a_lang['newsletter_26']."</a>");
	}
	buildTableFooter();
	buildExternalItems($a_lang['newsletter_27'],"newsletter.php?step=create","add.gif");
}   

if($step == 'create') {
	buildHeaderRow($a_lang['newsletter_28'],"add.gif");
	if($aboid) {
		$letter = $db_sql->query_array("SELECT * FROM $newsletter_table WHERE aboid='".$aboid."'");
		$action = "edit_abo";
	} else {
		$action = "new_abo";
	}
	buildFormHeader("newsletter.php", "post", $action);
	buildTableHeader($a_lang['newsletter_29']);
	if($aboid) buildHiddenField("aboid",$aboid);
	buildInputRow($a_lang['newsletter_30'], "abouser", $letter['abouser']);
	buildInputRow($a_lang['newsletter_31'], "abomail", $letter['abomail']);
	buildFormFooter($a_lang['newsletter_32'], "", 2, $a_lang['newsletter_33']);
}

if($step == 'del') {
    $del = $db_sql->query_array("SELECT * FROM $newsletter_table WHERE aboid='".$aboid."'");
    
    buildHeaderRow($a_lang['adminutil_4'],"delart.gif");
    buildInfo($a_lang['info8'][0],$a_lang['info8'][1]);	
    buildFormHeader("newsletter.php","post","confdel"); 
    buildHiddenField("aboid",$aboid);
    buildTableHeader("$a_lang[afunc_259]: <u>$del[abouser]</u>");
    buildDarkColumn(sprintf($a_lang['newsletter_34'],$aboid),1,1,2);
    buildFormFooter($a_lang['afunc_61'], "", 2, $a_lang['afunc_62']);  
}

buildAdminFooter();
?>