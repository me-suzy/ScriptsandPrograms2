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
|   > Verschiedene Seiten und Zusatzfunktionen
|
|	> Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-
|	> bedingungen (read-me.html) für weitere Informationen.
|	>-----------------------------------------------------------------
|	> This script is NOT freeware! Please read the Copyright Notice
|	> (read-me_eng.html) for further information. 
|
|	> $Id: misc.php 2 2005-10-08 09:40:29Z alex $
|
+--------------------------------------------------------------------------
*/

include_once('lib.inc.php');
include_once($_ENGINE['eng_dir']."admin/enginelib/function.".ENG_TYPE.".php");

if($_REQUEST['action']) $action = $_REQUEST['action'];

if ($action == "login") {
    $tpl->loadFile('main', 'login.html'); 
    $tpl->register('breadcrumb', buildBreadCrumb(array($lang['php_overall_home'] => $config['mainurl'], $lang['title_engine'] => $sess->url('index.php'), $lang['login_login'] => '')));
    $tpl->register('title', 'Login');  
    
    $tpl->register(array('login_login' => $lang['login_login'],
                        'login_login2' => $lang['login_login2'],
                        'login_use_your_username_password' => sprintf($lang['login_use_your_username_password'],definedBoardUrls("addmember")),
                        'login_username' => $lang['login_username'],
                        'login_password' => $lang['login_password'],
                        'login_click_here_to_remember' => sprintf($lang['login_click_here_to_remember'],definedBoardUrls("remember")),
                        'login_login_btn' => $lang['login_login_btn'],
                        'login_reset_btn' => $lang['login_reset_btn'])); 
                         
    if($auth->user['userid'] != 2) {
        rideSite($sess->url("index.php"), $lang['misc_1']);
    } else {
        $tpl->register('query', showQueries($develope));         
        $tpl->register('header', $tpl->pget('header'));
        
        $tpl->register('footer', $tpl->pget('footer'));
        $tpl->pprint('main');           
    }
	exit();
}

if ($action == "userlogin") {
	$info = $lang['misc_2'];
	showLoginScreen("", $info, "&nbsp;", $sess->url("index.php"));
	exit();
}

if ($action == "invalid_login") {
    rideSite($sess->url("misc.php?action=login"), $lang['misc_3']);
	exit();
}

if ($action == "logout") {
	$auth->userLogout();
	rideSite($sess->url("index.php"), $lang['misc_4']);
	exit();
}

if($action == "perm_denied") {
	$ride_url = $sess->url('index.php');
	rideSite($ride_url, $lang['misc_5']);
    exit();
}

if($action == 'formmailer') {
    if($auth->user['userid'] == 2) {
       rideSite($sess->url('index.php'), $lang['formmailer_not_allowed']);
       exit();  
    }  
    $tpl->loadFile('main', 'formmailer.html');     
    
    if($_GET['postid']) {
        $member = $db_sql->query_array("SELECT postername AS username, posteremail AS useremail FROM $guest_table WHERE postid='".$_GET['postid']."'");
        $tpl->register(array('mailid' => $_GET['postid'], 'mailkind' => 'postid'));
    } elseif($_GET['memberid']) {
        $member = holeUserID($_GET['memberid']); 
        $tpl->register(array('mailid' => $_GET['memberid'], 'mailkind' => 'memberid'));
    }
    
    $formmailer_headline = sprintf($lang['formmailer_write_email_to'],$member['username']);
    $tpl->register('title', $formmailer_headline);      
    
    $tpl->register('breadcrumb', buildBreadCrumb(array($lang['php_overall_home'] => $config['mainurl'], $lang['title_engine'] => $sess->url('index.php'), sprintf($lang['formmailer_write_email_to'],$member['username']) => '')));    
    
    $tpl->register(array('formmailer_headline' => sprintf($lang['formmailer_write_email_to'],$member['username']),
                        'formmailer_topic' => $lang['formmailer_topic'],
                        'formmailer_message' => $lang['formmailer_message'],
                        'formmailer_receipient_will_see_your_mail' => $lang['formmailer_receipient_will_see_your_mail'],
                        'formmailer_send_email_btn' => $lang['formmailer_send_email_btn'],
                        'formmailer_reset_btn' => $lang['formmailer_reset_btn']));
    
    $tpl->register('query', showQueries($develope));         
    $tpl->register('header', $tpl->pget('header'));
    
    $tpl->register('footer', $tpl->pget('footer'));
    $tpl->pprint('main');      
    
    exit();
}

if($action == "send_mail") {
    if($_POST['postid']) {
        $member = $db_sql->query_array("SELECT postername AS username, posteremail AS useremail FROM $guest_table WHERE postid='".$_POST['postid']."'");
    } else {
        $member = holeUserID($_POST['memberid']);
    }
    
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
    
    $mail->From = $auth->user['useremail'];
    $mail->FromName = $auth->user['username'];
    $mail->AddAddress($member['useremail'],$member['username']);
    $mail->Subject = $_POST['subject'];
    $mail->Body = $_POST['message'];  
    $mail->WordWrap = 50;  
    
    $mail->Send();		       

	$ride_url = $sess->url('index.php');
	rideSite($ride_url, sprintf($lang['formmailer_mail_successfully_send'],$member['useremail']));
    exit();
}

?>