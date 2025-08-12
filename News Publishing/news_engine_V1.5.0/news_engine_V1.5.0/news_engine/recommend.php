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
|   > Download Empfehlung
|
|	> Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-
|	> bedingungen (read-me.html) für weitere Informationen.
|	>-----------------------------------------------------------------
|	> This script is NOT freeware! Please read the Copyright Notice
|	> (read-me_eng.html) for further information. 
|
|	> $Id: recommend.php 2 2005-10-08 09:40:29Z alex $
|
+--------------------------------------------------------------------------
*/

include_once('lib.inc.php');
include_once($_ENGINE['eng_dir']."admin/enginelib/function.".ENG_TYPE.".php");

$tpl->loadFile('main', 'recommend.html'); 
$tpl->register('title', $lang['title_recommend']);

if ($auth->user['groupid'] == 8) {
	$username = $uname;
	$usermail = $umail;
} else {
	$username = $user['username'];
	$usermail = $user['useremail'];
}

if ( isset($_GET['send']) && $_GET['send']=='mail') {
	if ( empty($_GET['uname'])) {
		rideSite($sess->url('recommend.php?newsid='.$_GET['newsid']), $lang['rec_error1']);
		exit(); 	
	}
	if ( empty($_GET['umail'])) {
		rideSite($sess->url('recommend.php?newsid='.$_GET['newsid']), $lang['rec_error2']);
		exit(); 	
	}
	if (! isEmail($_GET['umail'])) {
		rideSite($sess->url('recommend.php?newsid='.$_GET['newsid']), $lang['rec_error3']);
		exit(); 	
	}
	if ( empty($_GET['friendname'])) {
		rideSite($sess->url('recommend.php?newsid='.$_GET['newsid']), $lang['rec_error4']);
		exit(); 	
	}
	if (! isEmail($_GET['friendmail'])) {
		rideSite($sess->url('recommend.php?newsid='.$_GET['newsid']), $lang['rec_error5']);
		exit(); 	
	}
	if ( empty($_GET['friendmail'])) {
		rideSite($sess->url('recommend.php?newsid='.$_GET['newsid']), $lang['rec_error6']);
		exit(); 	
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
    
    $mail->From = $_GET['umail'];
    $mail->FromName = $_GET['uname'];
    $mail->AddAddress($_GET['friendmail'],$_GET['friendname']);
    $mail->Subject = $lang['rec_file_betreff'];
    $mail->Body = sprintf($lang['rec_file_inhalt'],$_GET['friendname'],$_GET['uname'],$_GET['headline'],$config['newsscripturl']."/news.php?newsid=".$_GET['newsid'],$_GET['uname'],$_GET['mailcomment']).sprintf($lang['mail_footer'],$config['scriptname']);  
    $mail->WordWrap = 50;  
    
    if(!$mail->Send()) {
       $ride_message = $lang['php_mailer_error'].": ".$mail->ErrorInfo;
    } else {
       $ride_message = $lang['rec_error7'];
    }  	
	rideSite($sess->url('news.php?newsid='.$_GET['newsid']), $ride_message);
	exit(); 		
} else {    
    $news = $db_sql->query_array("SELECT * FROM $news_table WHERE newsid='".intval($_GET['newsid'])."'");
    
    $tpl->register('breadcrumb', buildBreadCrumb(array($lang['php_overall_home'] => $config['mainurl'], $lang['title_news'] => $sess->url('index.php'), stripslashes(trim($news['headline'])) => $sess->url("news.php?newsid=".$news['newsid']), $lang['title_recommend'] => '')));
    $tpl->register(array('newsid' => $news['newsid'],
                        'headline' => stripslashes(trim($news['headline'])),
                        'http_referer' => $HTTP_REFERER,
                        'recommend_your_name' => $lang['recommend_your_name'],
                        'recommend_your_mail' => $lang['recommend_your_mail'],
                        'recommend_friends_name' => $lang['recommend_friends_name'],
                        'recommend_friends_email' => $lang['recommend_friends_email'],
                        'recommend_comment' => $lang['recommend_comment'],
                        'recommend_send_btn' => $lang['recommend_send_btn'],
                        'recommend_reset_btn' => $lang['recommend_reset_btn'],
                        'recommend_sender' => $lang['recommend_sender'],
                        'recommend_recipient' => $lang['recommend_recipient']));
    
    $tpl->register('query', showQueries($develope));
    $tpl->register('header', $tpl->pget('header'));
    
    $tpl->register('footer', $tpl->pget('footer'));
    $tpl->pprint('main');
}
?>