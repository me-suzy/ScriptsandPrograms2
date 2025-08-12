<?php

// ---------------------------------------------------------------------------
//
// PIVOT - LICENSE:
//
// This file is part of Pivot. Pivot and all its parts are licensed under 
// the GPL version 2. see: http://www.pivotlog.net/help/help_about_gpl.php
// for more information.
//
// ---------------------------------------------------------------------------

// don't access directly..
if(!defined('INPIVOT')){ exit('not in pivot'); }


global $Pivot_Vars, $Cfg, $Users;
$setupstepn = 5;
PageHeader('setup', 0);

	$Setupstep1 = array(
	array('heading','Set up the Administrator User','', 8, '', '2', ''),
	array('step', '', '', 7, '1', '', ''),
	array('username', 'Username', '', 0, '', '', ''),
	array('pass1', 'Password', '', 1, '', '', 'maxlength="15"'),
	array('pass2', 'Password (confirm)', '', 1, '', '', 'maxlength="15"'),
	array('email', 'Email', '', 0, '', '', ''),
	array('nick', 'Nickname', '', 0, '', '', ''),	
	array('heading','Site Info','', 8, '', '2', ''),
	array('sitename', 'Site Name', '', 0, 'Pivot Powered', '', ''),
	array('deflang', 'Language', '', 3, GetLangs(), '', ''),
);
	
/*		$Setupstep2 = array(
	array('heading', 'News File Info', '', 8, '', '2', ''),	
	array('step', '', '', 7, '2', '', ''),
	array('localpath', 'Local Path', '', 0, '../', '', ''),	
	array('indexfile', 'Index File', '', 0, 'index.html', '', ''),
	array('file_format', 'File Format', '', 0, 'entry_%1.html', '', ''),
	array('heading', 'Archives', '', 8, '', '2', ''),
	array('archive_amount', 'Archive Amount', '', 0, '1', '', ''),
	array('archive_unit', 'Archive Unit', '', 3, array('week', 'week', 'month', 'month'), '', ''),	
	array('archive_format', 'Archive Format', '', 0, 'archive_%1.html', '', '')
	);
	
	$Setupstep3 = array(
	array('heading', 'Final Config', '', 8, '', '2', ''),
	array('step', '', '', 7, '3', '', ''),
	array('wysiwyg_editor', 'Use WYSIWYG?', '', 3, 'yn', '', '1'),	
	array('allow_paragraphs', 'Allow Paragraphs', '', 3, 'yn', '', '0'),
	array('ping_weblogs', 'Ping weblogs.com', '', 3, 'yn', '', '0'),
	array('targetblank', 'Target _blank', 'Open links in new windows?', 3, 'yn', '', '1'),
	array('rss', 'RSS Filename', '', 0, 'rss.xml', '', ''),
	array('rss_entry_length', 'RSS Entry Length', 'The length in characters of a entry in the RSS file', 0, '300', '', ''),	
	array('emoticons', 'Use emoticons', '', 3, 'yn', '', '1'),
	array('encode_email_addresses', 'Encode Email?', 'Use Javascript to encode email addresses against spambots?', 3, 'yn', '', '0')	
	);			
*/						
if(!isset($Pivot_Vars['step'])){
	$setupstepn = 1;	
}elseif($Pivot_Vars['step'] == 1){
	if(strlen($Pivot_Vars['username']) < 2){
		$Setupstep1[2][2] = 'Name needs to be 2 letters or longer';
		$Piverr++;
	}
	
	if((trim(strtolower($Pivot_Vars['pass1']))) != (trim(strtolower($Pivot_Vars['pass2'])))) {
		$Setupstep1[4][2] = 'Passwords do not match';
		$Piverr++;		
	}

	if( (trim(strtolower($Pivot_Vars['pass1']))) == (trim(strtolower($Pivot_Vars['username'])))) {
		$Setupstep1[4][2] = 'Password can\'t be the same as username.';
		$Piverr++;		
	}
	
	if((strlen($Pivot_Vars['pass1'])) < 4 ){
		$Setupstep1[3][2] = 'Password must be at least 4 letters long.';
		$Piverr++;		
	}	
	
	if((strlen($Pivot_Vars['pass1'])) > 14 ){
		$Setupstep1[3][2] = 'Password can be 14 characters at most. You know you will have to remember this, right?';
		$Piverr++;		
	}	
	
	if(!(isemail($Pivot_Vars['email']))) {
		$Setupstep1[5][2] = 'That\'s not a valid email address';
		$Piverr++;		
	}
	
	if((strlen($Pivot_Vars['sitename']) < 2) ) {
		$Pivot_Vars['sitename'] = "Pivot Weblog";
			
	}	
	
	if($Piverr > 0){
		$setupstepn = 1;
		$Setupstep1[2][4] = $Pivot_Vars['username'];
		$Setupstep1[5][4] = $Pivot_Vars['email'];
		$Setupstep1[6][5] = $Pivot_Vars['nick'];
				
	
	}else{
		
		$setupstepn = 2;
		$Cfg['users'] = $Pivot_Vars['username'];
		
		$Cfg['cats'] = 'default|linkdump';	
		$Cfg['cat-default'] = $Pivot_Vars['username'];
		$Cfg['cat-linkdump'] = $Pivot_Vars['username'];
		$Cfg['userfields'] = 'email|nick';
		$Cfg['uf-email'] = 'type|0|-|size|35|-|maxlen|35|-|disp|Email|-|minlevel|1';
		$Cfg['uf-nick'] = 'type|0|-|size|15|-|maxlen|35|-|disp|Nickname|-|minlevel|1';			
			
		$Users[$Pivot_Vars['username']]['pass'] = md5($Pivot_Vars['pass1']);
		$Users[$Pivot_Vars['username']]['email'] = $Pivot_Vars['email'];
		$Users[$Pivot_Vars['username']]['nick'] = $Pivot_Vars['nick'];
		$Users[$Pivot_Vars['username']]['userlevel'] = 4;
		$Users[$Pivot_Vars['username']]['language'] = $Pivot_Vars['deflang'];
		
		// SUPERADMIN!
		PutUserInfo();
	}
}

/*elseif($Pivot_Vars['step'] == 2){
	if(strlen($Pivot_Vars['indexfile']) < 3) {
		$Setupstep2[3][2] = 'I don\'t trust filenames less than 3 characters long';
		$Piverr++;			
	}
	if(strlen($Pivot_Vars['file_format']) < 5) {
		$Setupstep2[4][2] = 'I don\'t trust file formats less than 5 characters long';
		$Piverr++;			
	}
	if($Pivot_Vars['archive_amount'] <= 0 ) {
		$Setupstep2[6][2] = 'Must be greater than 0';
		$Piverr++;			
	}
	if(!is_numeric($Pivot_Vars['archive_amount'])) {
		$Setupstep2[6][2] = 'Must be a number';
		$Piverr++;			
	}
	if(strlen($Pivot_Vars['archive_format']) < 5) {
		$Setupstep2[8][2] = 'I don\'t trust archive file formats less than 5 characters long';
		$Piverr++;			
	}	
	if($Piverr > 0){
		$setupstepn = 2;
		$Setupstep2[2][4] = $Pivot_Vars['localpath'];
		$Setupstep2[3][4] = $Pivot_Vars['indexfile'];
		$Setupstep2[4][4] = $Pivot_Vars['file_format'];
		$Setupstep2[6][4] = $Pivot_Vars['archive_amount'];
		$Setupstep2[7][6] = $Pivot_Vars['archive_unit'];	
		$Setupstep2[8][4] = $Pivot_Vars['archive_format'];
	}else{	
		$Cfg['localpath'] = $Pivot_Vars['localpath'];
		$Cfg['indexfile'] = $Pivot_Vars['indexfile'];
		$Cfg['file_format'] = $Pivot_Vars['file_format'];
		$Cfg['archive_amount'] = $Pivot_Vars['archive_amount'];
		$Cfg['archive_unit'] = $Pivot_Vars['archive_unit'];	
		$Cfg['archive_format'] = $Pivot_Vars['archive_format'];	
		$setupstepn = 3;
	}				
}elseif($Pivot_Vars['step'] == 3){
	$Cfg['wysiwyg_editor'] = $Pivot_Vars['wysiwyg_editor'];
	$Cfg['allow_paragraphs'] = $Pivot_Vars['allow_paragraphs'];
	$Cfg['ping_weblogs'] = $Pivot_Vars['ping_weblogs'];
	$Cfg['targetblank'] = $Pivot_Vars['targetblank'];
	$Cfg['rss'] = $Pivot_Vars['rss'];
	$Cfg['rss_entry_length'] = $Pivot_Vars['rss_entry_length'];
	$Cfg['emoticons'] = $Pivot_Vars['emoticons'];
	$Cfg['encode_email_addresses'] = $Pivot_Vars['encode_email_addresses'];
	
}	
*/

$var = "Setupstep{$setupstepn}";
if($setupstepn==1){
	$next = 'proceed to login';
}else{
	$Cfg['installed'] = 1;
	$Cfg['sitename'] = $Pivot_Vars['sitename'];
	$Cfg['deflang'] = $Pivot_Vars['deflang'];

	SaveSettings();
	redirect('index.php');

}

//it's nice to use it as a variable so we can look at $BigAssArray[$i][0] for a list of variables to save in config.
StartForm('setup');
StartTable($caption);
DisplaySettings($$var);
EndForm($next, 1);
PageFooter();	

?>

