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


//save the settings file each time the script ends. (linux only..)
//register_shutdown_function('SaveSettings');

//just load up the settings.
function GetSettings() {
	global $Cfg, $Weblogs, $pivot_path;

	// get the config file
	$fh = file($pivot_path.'pv_cfg_settings.php');

	foreach ($fh as $fh_this) {
		@list($name, $val) = split("!", $fh_this);
		$Cfg[$name] = rtrim(ltrim($val));
	}
	GetUserInfo();
	ExpandSessions();

	@$Cfg['ping_urls']=str_replace("|", "\n", $Cfg['ping_urls']);
	@$Cfg['default_introduction']=str_replace("|", "\n", $Cfg['default_introduction']);

	if (!isset($Cfg['selfreg'])) { $Cfg['selfreg']= 0; }
	
	if(!isset($Cfg['server_spam_key']))  {
  		$key = $_SERVER['SERVER_SIGNATURE'].$_SERVER['SERVER_ADDR'].$_SERVER['SCRIPT_URI'].$_SERVER['DOCUMENT_ROOT'].time();
      	$Cfg['server_spam_key'] = md5($key);
  	}

	// also get the weblogs file
	$Weblogs = load_serialize($pivot_path."pv_cfg_weblogs.php");
	unset($Weblogs['']);

}


//save the settings.
function SaveSettings() {
	global $Cfg;

	PutUserInfo();
	ContractSessions();
	
	// a hack for ping_urls.
	if (isset($Cfg['ping_urls'])) {
		$Cfg['ping_urls'] = str_replace("\n", "|", $Cfg['ping_urls']);
		$Cfg['ping_urls'] = str_replace("\r", "", $Cfg['ping_urls']);
	}

	// a hack for default_intro
	if (isset($Cfg['default_introduction'])) {
		$Cfg['default_introduction'] = str_replace("\n", "|", $Cfg['default_introduction']);
		$Cfg['default_introduction'] = str_replace("\r", "", $Cfg['default_introduction']);
	}

	// only save if there's an actual array to save.
	if ((count($Cfg)>5) && (isset($Cfg['debug'])) ) {
	
		$fh = fopen('pv_cfg_settings.php', 'w');
		fwrite($fh, "<?php\n");

		ksort($Cfg);

		foreach($Cfg as $key => $value) {
			if($key && ($value != '') && ($key!='tempsessions')) {
				fwrite($fh, "$key!$value\n");
			}
		}
		fwrite($fh, "?".">\n");
		fclose($fh);

	} else {

		debug("Cowardly refuse to write b0rken config file..");
	
	}
}


function ExpandSessions() {
	global $Cfg;
	if (isset($Cfg['sessions'])) {
		foreach(split('\|-\|' , $Cfg['sessions']) as $val){
			$vars = split('\|', $val);
			$i = 1 ;
			while( isset( $vars[$i] )) {
				$Cfg['tempsessions'][$vars[0]][] = $vars[$i];
				$i++;
			}
		}
	}
}


function ContractSessions() {
	global $Cfg;
	
	while (list($session,$meh) = @each($Cfg['tempsessions'])) {
		if(($Cfg['tempsessions'][$session][2] - time()) >= 0) {
			$tmp[] = $session.'|'.join('|', $Cfg['tempsessions'][$session]);
		}
	}
	
	if ( (isset($tmp)) && (is_array($tmp)) ) {
		$Cfg['sessions'] = join('|-|', $tmp);
	}
}


// gets the user info for everyone.
function GetUserInfo() {
	global $Cfg, $Users;
	if(isset($Cfg['users']))  {
		foreach(split('\|', trim($Cfg['users'])) as $inc => $user){
			foreach(split('\|-\|' , $Cfg['user-' . $user]) as $var => $val){
				list($Nvar, $Nval) = split('\|', $val);
				$Users[$user][$Nvar] = $Nval;
			}
		}
	}
}


function PutUserInfo() {
	global $Cfg, $Users;
	foreach($Users as $uname => $vals){
		$ahoy = array();
		foreach($vals as $varname => $value){
			$ahoy[] = "$varname|$value";
		}
		// bob notes: changed to >= 2 (otherwise it won't store username 'bob')
		// i feel neglected now :-.
		if(strlen($uname) >= 2) {
			$Cfg["user-$uname"] = join('|-|', $ahoy);
		}
	}
}


function GetUserFields() {
	global $Cfg;
	$ufields['heading']['disp'] = lang('adminbar', 'userfields');
	$ufields['heading']['size'] = 2;
	$ufields['heading']['type'] = 8;
	foreach(split('\|', trim($Cfg['userfields'])) as $inc => $field){
		foreach(split('\|-\|' , $Cfg['uf-' . $field]) as $var => $val){
			list($Nvar, $Nval) = split('\|', $val);
			$ufields[$field][$Nvar] = lang('userinfo',strtolower($Nval));
		}
	}

	return $ufields;
}


function PutUserFields($data) {
	//rebuilds all the info from user fields, which should have been taken from GetUserFields
	global $Cfg;
	foreach($data as $key => $arr) {
		$turger[] = $key;
		foreach($data[$key] as $item => $val){
			$pressure[] = "$item|$val";
		}
		$Cfg['uf-'.$key] = join("|-|", $pressure);
		unset($pressure);
	}

	$Cfg['userfields'] = join("|", $turger);
}


function ExpandBlogs(){
	global $Cfg;
	//expands just the list of weblogs.
	foreach(split('\|-\|', trim($Cfg['weblogs'])) as $inc => $still){
		foreach(split('\|' , $still) as $int => $ext){
			$lWeblogs[] = "$ext";
		}

	}
	//	print_r($lWeblogs);
	return $lWeblogs;
}



function CheckInput($input, $minlen=2, $maxlen=12) {
	//if it returns nothing, it passes.
	//0 = fail by input [not alphanumberic or _]
	//1 - fail by length [too short]
	//2 - fail by length [too long]
	if(ereg("^[a-zA-Z0-9\_\s]+$", $input)){
		if(strlen($input) < $minlen){
			return 	1;
		}
		if(strlen($input) > $maxlen){
			return 2;
		}
	}else{
		return 0;
	}
}



/*  isemail( $theAdr )

   given a chain it returns true if $theAdr conforms to RFC 2822
   it does not check the existence of the address

   JM
   r3 - 2005/01/17
------------------------------------------- */
function isemail( $theAdr ) {

 /*
 suppose a mail of the form
  addr-spec     = local-part "@" domain
  local-part    = dot-atom / quoted-string / obs-local-part
  dot-atom      = [CFWS] dot-atom-text [CFWS]
  dot-atom-text = 1*atext *("." 1*atext)
  atext         = ALPHA / DIGIT / ; Any character except controls,
          "!" / "#" /     ;  SP, and specials.
          "$" / "%" /     ;  Used for atoms
          "&" / "'" /
          "*" / "+" /
          "-" / "/" /
          "=" / "?" /
          "^" / "_" /
          "`" / "{" /
          "|" / "}" /
          "~" / "." /
 ------------------------------------------- */
 // default
 $result = FALSE;
 // go ahead
 if(( ''!=$theAdr )||( is_string( $theAdr ))) {
  $mail_array = explode( '@',$theAdr );
 }
 if( !is_array( $mail_array )) { return FALSE; }
 if( 2 == count( $mail_array )) {
  $adr_array  = explode( '.',$mail_array[1] );
 } else {
  return FALSE;
 }
 if( !is_array( $adr_array ))  { return FALSE; }
 if( 1 == count( $adr_array )) { return FALSE; }

 /* relevant info:
   $mail_array[0] contains atext
   $adr_array  contains parts of address
               and last one must be at least 2 chars
 ------------------------------------------ */
 // quick checks
 $domain = array_pop( $adr_array );
 if(( is_string( $domain ))&&( 1 < strlen( $domain ))) {
  // put back
  $adr_array[] = $domain;
  $domain = implode( '',$adr_array );
  // now we have two string to test
  // $domain and $mail_array[0]
  $domain        = ereg_replace( "[[:alnum:]]","",$domain );
  $domain        = ereg_replace( "[-|\_]","",$domain );
  $mail_array[0] = ereg_replace( "[[:alnum:]]","",$mail_array[0] );
  $mail_array[0] = ereg_replace(
    "[-.|\!|\#|\$|\%|\&|\'|\*|\+|\/|\=|\? |\^|\_|\`|\{|\||\}|\~]","",$mail_array[0] );
  // final
  if(( '' == $domain )&&( '' == $mail_array[0] )) { $result = TRUE; }
 }
 return $result;
} 
	
	

// give this function a string and it will tell you
// whether it is a url or not.. magically!!
function isurl($url) {

	return (preg_match("/((ftp|https?):\/\/)?([a-z0-9](?:[-a-z0-9]*[a-z0-9])?\.)+(com\b|edu\b|biz\b|org\b|gov\b|in(?:t|fo)\b|mil\b|net\b|name\b|museum\b|coop\b|aero\b|[a-z][a-z]\b|[0-9]{1,3})/i",$url));

}



function libchange_user($admin=0, $erred=0, $newdata='') {
	global $Pivot_Vars, $Users, $ThisUser, $Cfg;


	if($erred==0){
		$userfields = get_userfields($admin);
	}else{
		$userfields = $newdata;
	}
	if($admin==1){
		$theuser = $Pivot_Vars['edituser'];
		$ThisUser = $Users[$theuser];
	}else{
		$theuser = $Pivot_Vars['user'];
	}

	MinLevel($Users[$theuser]['userlevel']);

	
	StartForm('save_user', $admin);
	GenSetting('username', '', '', 7, $theuser, '', '');
	StartTable();
	
	// so the user can't change his name..
	$userfields[0][4] = $theuser;
	$userfields[0][6] = "disabled='disabled'";
	
	// make sure superadmin doesn't demote himself.
	if( ($admin==1) && ($Users[$theuser]['userlevel'] > 3) ) {
		foreach($userfields as $key => $userfield) {
			// this loop makes sure we don't set the wrong field..
			if ($userfield[0] == "userlevel") {
				$userfields[$key][4] = array('0' => lang('userlevels',4-$Users[$theuser]['userlevel']), '1' => $Users[$theuser]['userlevel']);
			}
		}
	}
	
	if($erred==0){
		$ufields = GetUserFields();
		foreach($ufields As $keyname => $arrg) {
			$type = 0;
			if(is_int($ufields[$keyname]['maxlen'])){
				$maxl =  'maxlength=\"'.$ufields[$keyname]['maxlen'].'\"';
			}else{
				$maxl = '';
			}
			array_push($userfields, array($keyname, $ufields[$keyname]['disp'], '', $ufields[$keyname]['type'], $Users[$theuser][$keyname], $ufields[$keyname]['size'], $maxl));
			
		}
	}


	// checkboxes for allowed categories
	if($Users[$Pivot_Vars['user']]['userlevel'] > 2){

		$userfields[] = array('heading', lang('config', 'allowed_cats'), '', 8, '', 2);

		foreach(explode("|", $Cfg['cats']) as $cat){
			if ($cat != "" ) {

				$label = sprintf('&nbsp;&nbsp;&nbsp;'.$cat);
				$allowed_users = explode("|", $Cfg['cat-'.$cat]);
				$cat_encoded = urlencode($cat);
				if (in_array($theuser, $allowed_users)) {
					$userfields[] = array('allowed['.$cat_encoded.']', $label, '', 2, 1, '', '');
				} else {
					$userfields[] = array('allowed['.$cat_encoded.']', $label, '', 2, 0, '', '');
				}
			}
		}
		$userfields[(count($userfields)-1)][2]  = lang('config', 'allowed_cats_desc');

	}
	
	DisplaySettings($userfields, 'ThisUser');
	
	if($Pivot_Vars['func']=='admin' && ($Pivot_Vars['do']=='edituser' || $Pivot_Vars['do']=='save_user') && $Users[$Pivot_Vars['user']]['userlevel'] > $Users[$theuser]['userlevel']){
		GenSetting('heading', lang('general', 'delete') , '', 8);
		GenSetting('delete_user', lang('config', 'delete_user'), lang('config', 'delete_user_desc'), 3, 'yn');
	}
	
	EndForm(lang('userinfo','edituser_button'), 1);
}



function libsave_change_user($admin=0) {
	global $Pivot_Vars, $Users, $Cfg;
	
	$userfields = get_userfields($admin);
	$ufields = GetUserFields();
	
	// add userfield for wysi
	// array_push($userfields, array('wysiwyg', 'Use Wysiwyg editor?', '', '3', 'yn'));

	if($admin==1){
		$theuser = $Pivot_Vars['username'];
	}else{
		$theuser = $Pivot_Vars['user'];
	}
	if(($Pivot_Vars['delete_user']==1) && ($Users[$Pivot_Vars['user']]['userlevel'] >= 3)){
		
		if($Pivot_Vars['confirmed'] == 1){
			
			//delete him from the config file, nothing else.
			$tmp_arr = explode("|", $Cfg['users']);
			foreach($tmp_arr as $candidate){
				if($candidate != $Pivot_Vars['username']){
					$tmp_arr2[] = $candidate;
				}
			}
			$Cfg['users'] = implode( "|", $tmp_arr2);
			unset($Users[$Pivot_Vars['username']]);
			unset($Cfg['user-'.$Pivot_Vars['username']]);
			see_users();
		}else{
			$vars = array('username', $Pivot_Vars['username'], 'delete_user', 1);
			ConfirmPage(lang('ufield_main','del_title'), $vars, sprintf( lang('config', 'delete_user_confirm'), $Pivot_Vars['username']));
		}
		
	}else{
		
		foreach($ufields As $keyname => $intarr){
			array_push($userfields, array($keyname, $ufields[$keyname]['disp'], '', $ufields[$keyname]['type'], $Users[$theuser][$keyname], $ufields[$keyname]['size'], $maxl));
			
			if($ufields[$keyname]['filter'] != '' && (strlen($Pivot_Vars[$keyname]) > 0)){
				$tfunk = 'is' . $ufields[$keyname]['filter'];
				if(!($tfunk($Pivot_Vars[$keyname]))){
					$userfields[count($userfields)-1][2] = 'hey, that input doesn\'t go along with the filter in place';
					$Piverr++;
				}
			}
		}
		$arraycount = Count($userfields);

		if(strlen($Pivot_Vars['pass1']) + strlen($Pivot_Vars['pass2']) >= 1){
			if((trim($Pivot_Vars['pass1'])) != (trim($Pivot_Vars['pass2']))) {
				$userfields[2][2] = lang('userinfo','pass_dont_match');
				$Piverr++;
			}

			if( strlen($Pivot_Vars['pass1']) < 4 ){
				$userfields[1][2] = lang('userinfo','pass_too_short');
				$Piverr++;
			}
			
			
			if( $Pivot_Vars['pass1'] == $Pivot_Vars['username'] ){
				$userfields[1][2] = lang('userinfo','pass_equal_name');
				$Piverr++;
			}
			
		}
		if($Piverr==0){

			// make sure the superadmin doesn't demote himsef/herself..
			if ($Users[$theuser]['userlevel']==4){
				$Pivot_Vars['userlevel']=4;
			}

			// get confirmation if user gets admin rights
			if(($Pivot_Vars['userlevel']==3) && ($Users[$theuser]['userlevel']<3) && ($Pivot_Vars['confirmed'] != 1)){
				$vars = array();
				$arraycount = Count($userfields);
				for($i = 0; $i < $arraycount; $i++){
					array_push($vars, $userfields[$i][0], $Pivot_Vars[$userfields[$i][0]]);
				}
				ConfirmPage(lang('userinfo','c_admin_title'), $vars, sprintf(lang('userinfo','c_admin_message'), $theuser));
			}else{
				//it's all good.
				if(strlen($Pivot_Vars['pass1']) >= 6){
					$Users[$theuser]['pass'] = md5($Pivot_Vars['pass1']);
					if($_COOKIE['mode'] == 'stayloggedin' && $Pivot_Vars['user'] == $theuser){
						setcookie('pass', md5($Pivot_Vars['pass1']), time()+$Cfg['cookie_length']);
					}
					//change the session key too..
					$Cfg['tempsessions'][$Pivot_Vars['session']][1] = md5($Pivot_Vars['pass1']);
				}
				for($i = 0; $i < $arraycount; $i++){
					if(($userfields[$i][0] != 'heading') && ($userfields[$i][0] != 'pass1') && ($userfields[$i][0] != 'pass2')) {
						$Users[$theuser][$userfields[$i][0]] = $Pivot_Vars[$userfields[$i][0]];
					}
				}

				// set the categories..

				if($Users[$Pivot_Vars['user']]['userlevel'] > 2){
					foreach (explode("|", $Cfg['cats']) as $category) {

						$allowed_users = explode("|", $Cfg['cat-'.$category]);

						if (isset($Pivot_Vars['allowed'][$category])) {
							// add the user..
							if (!in_array($theuser, $allowed_users)) {
								$allowed_users[] = $theuser;
							}

						} else {
							// remove the user..
							if (in_array($theuser, $allowed_users)) {
								foreach ($allowed_users as $key => $user) {
									if ($user == $theuser) { unset ($allowed_users[$key]); }
								}
							}
						}

						$Cfg['cat-'.$category] = implode("|", $allowed_users);

					}
				}

				/**
				 * Rather crude check to prevent corrupting the file:
				 * if $Users[$Pivot_Vars['user']]['username'] is empty, we refuse to save.
				 */
				if ($Users[$Pivot_Vars['user']]['username'] != "") {
					debug("changes saved for ". $Pivot_Vars['user']);
					SaveSettings();
				} else {
					// not right..
					debug("Changes not saved..");
						
				}	
				
				
				//so we can reload the languages and such
				
				if($admin==1){
					
					redirect('index.php?session='.$Pivot_Vars['session'].'&amp;menu=admin&amp;func=admin&do=seeusers');
					die();
				}else{
					
					redirect('index.php?session='.$Pivot_Vars['session'].'&amp;menu=userinfo&amp;func=u_settings');
					die();
				}
				
			}
			
		}else{
			//reshow the spage
			$Pivot_Vars['edituser'] = $Pivot_Vars['username'];
			//regroup the old settings.
			for($i=0; $i < $arraycount; $i++){
				if($userfields[$i][3]==0){
					$userfields[$i][4] = $Pivot_Vars[$userfields[$i][0]];
				}elseif($userfields[$i][3]==3){
					$userfields[$i][6] = $Pivot_Vars[$userfields[$i][0]];
				}
			}
		}
		if($Users[$theuser]['userlevel'] > 3){
			//ARGHH!!!!!!
			$unf[0] = $userfields[4][6];
			$unf[1] = 'disabled';
			unset($userfields[4][6]);
			$userfields[4][6] = $unf;
		}
		
		if($admin==1){
			change_user(1, $userfields);
		}else{
			u_settings_screen(1, $userfields);
		}
	}
	PutUserInfo();
}



function show_image_list($fileArray,$thumbArray) {
	global $Cfg, $Pivot_Vars, $Paths;

	printf('<script language="javascript" type="text/javascript">function pop(a){window.open("modules/module_image.php?image="+a,"%s","toolbar=no,resizable=yes,scrollbars=yes,width=520,height=550");}</script>',$file['name']);

	$myurl =sprintf("index.php?session=%s&amp;menu=files&amp;doaction=1", $Pivot_Vars['session']);
	printf("<form name='form1' method='post' action='%s'>", $myurl);

	echo '<table cellspacing="0" class="tabular_border">';

	printf("<tr class='tabular_header'>\n<td>&nbsp;</td>\n<td width='200'>%s</td>\n<td>%s</td>\n<td width='100'>%s</td>\n<td>%s</td>\n<td>%s</td>\n</tr>\n\n",lang('upload','filename'),lang('upload','thumbnail'),lang('upload','date'),lang('upload','filesize'),lang('upload','dimensions'));


	foreach($fileArray as $key => $file) {

		$fullentry = urlencode(fixpath(sprintf('%s../%s%s',$Paths['pivot_url'] ,$Cfg['upload_path'],$file['name'])));
		$thumb = check_for_common($file['name']);
		
		// get the image's witdh and height
		list($file['width'],$file['height']) = getimagesize( urldecode( sprintf('../%s%s' ,$Cfg['upload_path'],$file['name'] ) ) );
		
		if (!isset($linecount)) {
			$linecount=1;
		} else {
			$linecount++;
		}

		if (($linecount % 2)==0) {
			$bg_color="tabular_line_even";
		} else {
			$bg_color="tabular_line_odd";
		}

		$url=sprintf($Paths['pivot_url']."/includes/photo.php?img=%s&amp;w=%s&amp;h=%s&amp;t=%s", base64_encode($fullentry), $file['width'], $file['height'], $file['name']);
		$view_html = sprintf("<a href='%s' onclick=\"window.open('%s', 'imagewindow', 'width=%s, height=%s, directories=no, location=no, menubar=no, scrollbars=no, status=no, toolbar=no, resizable=no');return false\" target='_self' title='%s'>", urldecode($fullentry), $url, $file['width'], $file['height'], $file['name'] );

		printf('<tr class="%s">',$bg_color);
		printf("\n<td><input type='checkbox' name='check[%s]' /></td>\n",$file['name']);
		printf('<td>%s%s</a></td>',$view_html, trimtext(strtolower(urldecode($file['name'])), 40, TRUE));

		if($thumb != $file['name']) {
			printf('<td><a href="javascript:pop(\'%s\');">' . lang('upload', 'edit') . '</a></td>',$file['name']);
		} else {
			printf('<td><a href="javascript:pop(\'%s\');">' . lang('upload', 'create') . '</a></td>',$file['name']);
		}

		printf("\n<td>%s</td>\n<td>%d KB</td>\n<td>%d x %d</td>\n</tr>\n\n",$file['date'],$file['size'],$file['width'],$file['height']);
	}

	echo '<tr class="tabular_nav"><td colspan="7"><img src="pics/arrow_ltr.gif" width="29" height="14" border="0" alt="" />';
	echo '<a href="#" onclick=\'setCheckboxes("form1", true); return false;\'>'. lang('forms', 'c_all') .'</a> / ';
	echo '<a href="#" onclick=\'setCheckboxes("form1", false); return false;\'>'. lang('forms', 'c_none') .'</a>';
	echo '&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;'. lang('forms', 'with_checked_files');
	echo '<select name="action" class="input"><option value="" selected="selected">'. lang('forms', 'choose') .'</option><option value="delete">'. lang('forms', 'delete') .'</option></select>';
	echo '&nbsp;&nbsp;<input type="submit" value="'. lang('go') .'" class="button" />';
	printf("</td>\n</tr></table>\n</form>\n\n");

	StartForm('file_upload', 0, 'enctype="multipart/form-data"');
	
	echo "<br /><table border='0'>";
	
	GenSetting('',lang('upload','thisfile'),'',8,'',6);
	printf('<tr><td><input name="%s" type="file"  class="input" /></td><td>',$Cfg['upload_file_name']);
	printf('<input type="submit" value="%s" class="button" /></td></tr></table></form>',lang('go'));

	PageFooter();
}


function show_image_preview($fileArray,$thumbArray) 	{
	global $Cfg, $base_url, $Pivot_Vars, $Paths;

	printf("\n\n<script language='javascript' type='text/javascript'>\nfunction pop(a){\nwindow.open('modules/module_image.php?image='+a,'','toolbar=no,resizable=yes,scrollbars=yes,width=520,height=550');\n}\n</script>\n\n");

	$myurl =sprintf("index.php?session=%s&amp;menu=files&amp;doaction=1", $Pivot_Vars['session']);
	printf("<form name='form1' method='post' action='%s'>", $myurl);

	echo "\n\n<div style='margin:0px; padding:0px;'>\n\n";

	foreach($fileArray as $file) {

		$ext=getextension(strtolower($file['name']));
		if ( ($ext!="gif") && ($ext!="jpg") && ($ext!="jpeg") && ($ext!="png") && ($ext!="swf") ) {
			continue;
		}

		$thumb = check_for_common($file['name']);
		$fullentry = urlencode(fixpath(sprintf('%s../%s%s',$Paths['pivot_url'] ,$Cfg['upload_path'],$file['name'])));
		$thumbententry = urldecode(fixpath(sprintf('%s../%s%s',$Paths['pivot_url'] ,$Cfg['upload_path'],$thumb)));
		
		list($thumbwidth, $thumbheight) = getimagesize(urldecode("../".$Cfg['upload_path'].$thumb));

		// Calculate rescaling factor for
		// showing thumbnail in table/div

		// for wide thumbnails, take two 'cells', otherwise one..
		if ( ($thumbwidth / $thumbheight) < 2 ) {
			$mw	= 130;
		}	else {
			$mw = 268; // * 2 , + 8
		}

		$mh		= 80;
		$scalew = $thumbwidth / $mw;
		$scaleh = $thumbheight / $mh;
		$factor = max($scalew,$scaleh);
		$dw		= $thumbwidth / $factor;
		$dh		= $thumbheight / $factor;

		// get the image's witdh and height
		list($file['width'],$file['height']) = getimagesize( urldecode( sprintf('../%s%s' ,$Cfg['upload_path'],$file['name'] ) ) );

		// if filesize is bigger than 9999 KB
		// then filesize comment is changed, this
		// is just for not fucking up the tables/divs
		if(strlen($file['size']) > 4) {
			// see!? no more than 4 chars,
			// please change size of file
			$fs = 'BIGASS';
		} else {
			// show actual filesize + string KB
			$fs = $file['size'].'KB';
		}

		echo "\n<div style='float:left; border:1px solid #2D5A5A; margin:3px;'>\n";
		printf('<table cellpadding="0" cellspacing="0" width="%d" border="0">',$mw);

		printf("\n\n<tr>\n<td align='center' valign='middle' bgcolor='#BBBBBB' height='%d' colspan='2'>",$mh);

		// make the 'view' link
		$url=sprintf($Paths['pivot_url'] . "/includes/photo.php?img=%s&amp;w=%s&amp;h=%s&amp;t=%s", base64_encode($fullentry), $file['width'], $file['height'], $file['name']);
		
		$view_html = sprintf("\n<a href='%s' onclick=\"window.open('%s', 'imagewindow', 'width=%s, height=%s, directories=no, location=no, menubar=no, scrollbars=no, status=no, toolbar=no, resizable=no');return false\" target='_self' title='%s (%s)'>", urldecode($fullentry), $url, $file['width'], $file['height'], $file['name'], $fs );

		if($thumbententry != $file['name']) {
			printf("\n%s<img src='%s' width='%d' height='%d' border='0' alt='%s' /></a>\n",$view_html,$thumbententry,$dw,$dh,$file['name']);
		} else {
			printf("\n%s<small>%s <br /><br />(No Thumbnail)</small></a>\n",$view_html, wordwrap(urldecode($file['name']),14, ' ',1));
		}
		echo "</td>\n</tr>\n\n<tr>\n<td bgcolor='#B6CCCC'>\n";
		printf("<input type='checkbox' name='check[%s]' /></td>",$file['name']);


		printf('<td align="center" bgcolor="#B6CCCC" height="17">%sview</a>&nbsp;/&nbsp;', $view_html);

		if($thumb != $file['name']) {
			printf('<a href="javascript:pop(\'%s\');">edit</a></td>',$file['name']);
		} else {
			printf('<a href="javascript:pop(\'%s\');">create</a></td>',$file['name']);
		}

		echo '</tr></table></div>';

	}

	echo "\n</div>\n<br clear='all' />\n\n";

	printf("<table cellpadding='2'><tr><td bgcolor='#B6CCCC'>");
	echo '<a href="#" onclick=\'setCheckboxes("form1", true); return false;\'>'. lang('forms', 'c_all') .'</a> / ';
	echo '<a href="#" onclick=\'setCheckboxes("form1", false); return false;\'>'. lang('forms', 'c_none') .'</a>';
	echo '&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;'. lang('forms', 'with_checked_files');
	echo '<select name="action" class="input"><option value="" selected="selected">'. lang('forms', 'choose') .'</option><option value="delete">'. lang('forms', 'delete') .'</option></select>';
	echo '&nbsp;&nbsp;<input type="submit" value="'. lang('go') .'" class="button" />';
	printf("</td></tr></table></form>");

	flush();

	StartForm('file_upload', 0, 'enctype="multipart/form-data"');
	
	echo "<br /><table border='0'>";
	
	GenSetting('',lang('upload','thisfile'),'',8,'',6);
	printf('<tr><td><input name="%s" type="file"  class="input" /></td><td>',$Cfg['upload_file_name']);
	printf('<input type="submit" value="%s" class="button" /></td></tr></table>',lang('go'));

	PageFooter();
}



// Open images directory and splitting the
// thumbnails from the original in 2 arrays.
function getFileList() {
	global $Cfg, $fileArray, $thumbArray;

	$fileArray = Array();
	$thumbArray = Array();

	$uploadPath = sprintf('../%s',$Cfg['upload_path']);
	
	$d = opendir($uploadPath) or die(lang('error','path_open'));

	while(false !== ($f = readdir($d))) {
		$files[] = $f;
	}

	sort($files);
	
	foreach ($files as $f) {
		if(is_file($uploadPath.$f)) {
			$fileSize = round(filesize($uploadPath.$f)/1024);
			$fileDate = filemtime($uploadPath.$f);
			$fileDate = format_date(date('Y-m-d-H-i', $fileDate),'%day%-%month%-%ye% %hour24%:%minute%');
			if(!preg_match('/(\.thumb\.)/',$f)) {
				//$tmp = getImageSize($uploadPath.$f);
				$tmp = Array('name'=>urlencode($f),  'date'=>$fileDate, 'size'=>$fileSize);
				array_push($fileArray,$tmp);
			} else {
				//$tmp = getImageSize($uploadPath.$f);
				$tmp = Array('name'=>$f,'date'=>$fileDate,'size'=>$fileSize);
				array_push($thumbArray,$tmp);
			}
		}
	}
	closedir($d);

	usort($fileArray, 'filearray_sort');

	return array($fileArray, $thumbArray);


}


function filearray_sort($a, $b) {
	if (strtolower($a['name']) == strtolower($b['name'])) return 0;
	return (strtolower($a['name']) < strtolower($b['name'])) ? -1 : 1;
}



function check_for_common($str)
{
	global $thumbArray;

	// first we split up the extension from filename
	// with a simple regexp... god i love regexp :-)
	preg_match('/^(.*)\.(.*)$/i',$str,$match);
	$compare = strtolower($match[1]);

	foreach($thumbArray as $val) {

		$thumb = strtolower(substr($val['name'],0,strlen($compare)));

		if($compare == $thumb) {
			// MATCH!!!
			$str = $val['name'];
		}
	}
	return $str;
}



function getextension($file) {
	$pos=strrpos($file, ".");
	if (is_string ($pos) && !$pos) {
		// not found...
		return "";
	} else {
		$ext=substr($file, $pos+1);
		//echo "ext=$ext<br />\n";
		return $ext;
	}
}

// This will format $date, according to the passed $format
function format_date( $date="", $format="") {
	global $Cfg, $current_date, $db;

	if ($format=="") { $format="%day% %monname% '%ye%"; }

	// if format does nof contain '%' just return to save some processing time
	if (strpos($format, "%")=== FALSE) {
		return $format;
	}

	if ($date=="") {$date= date("Y-m-d-H-i", get_current_date()); }
	list($yr,$mo,$da,$ho,$mi)=split("-",$date);

	$mktime = mktime(1,1,1,$mo,$da,$yr);

	$ho12 = ($ho>11) ? $ho - 12 : $ho;
	$ampm= ($ho12==$ho) ? "am" : "pm";
	if ($ho12==0) { $ho12=12; }

	$format=str_replace("%minute%", $mi, $format);
	$format=str_replace("%hour12%", $ho12, $format);
	$format=str_replace("%ampm%", $ampm, $format);
	$format=str_replace("%hour24%", $ho, $format);
	$format=str_replace("%day%", $da, $format);
	$format=str_replace("%daynum%", @date("w",$mktime), $format);
	$format=str_replace("%dayname%", lang ("days" , @date("w",$mktime)), $format);
	$format=str_replace("%weekday%", lang ("days" , @date("w",$mktime)), $format);
	$format=str_replace("%weeknum%", @date("W",$mktime), $format);
	$format=str_replace("%month%", $mo, $format);
	$format=str_replace("%monthname%", lang('months', -1+$mo), $format);
	$format=str_replace("%monname%", lang('months_abbr', -1+$mo), $format);
	$format=str_replace("%year%", $yr, $format);
	$format=str_replace("%ye%", substr($yr,2), $format);

	// === JM - 2004/09/12 ¥¥¥
	$format=str_replace("%aye%", "&#8217;".substr($yr,2), $format);
	$format=str_replace("%ordday%", 1*$da, $format);
	$format=str_replace("%ordmonth%", 1*$mo, $format);
	// === END JM ¥¥¥

	//while not part of 'dates', we also replace %title% with the
	//entry's, suitable for use in filenames
	$format=@str_replace("%title%", safe_string(substr($db->entry['title'],0,28),TRUE) , $format);

	return $format;
}


// This will format $date, according to the passed $format
function format_date_range( $start_date, $end_date, $format) {
	global $Cfg, $current_date;

	list($st_yr,$st_mo,$st_da)=split("-",$start_date);
	list($en_yr,$en_mo,$en_da)=split("-",$end_date);

	$mktime = mktime(1,1,1,$st_mo,$st_da,$st_yr);

	$format=str_replace("%st_day%", $st_da, $format);
	$format=str_replace("%st_daynum%", @date("w",$mktime), $format);
	$format=str_replace("%st_dayname%", lang ( "days" , @date("w",$mktime)), $format);
	$format=str_replace("%st_weekday%", lang ( "days" , @date("w",$mktime)), $format);
	$format=str_replace("%st_weeknum%", @date("W",$mktime), $format);
	$format=str_replace("%st_month%", $st_mo, $format);
	$format=str_replace("%st_monthname%", lang('months', -1+$st_mo), $format);
	$format=str_replace("%st_monname%", lang('months_abbr', -1+$st_mo), $format);
	$format=str_replace("%st_year%", $st_yr, $format);
	$format=str_replace("%st_ye%", substr($st_yr,2), $format);

	// === JM - 2004/09/12 ¥¥¥
	$format=str_replace("%st_aye%", "&#8217;".substr($st_yr,2), $format);
	$format=str_replace("%st_ordday%", 1*$st_da, $format);
	$format=str_replace("%st_ordmonth%", 1*$st_mo, $format);
	// === END JM ¥¥¥
	$mktime = mktime(1,1,1,$en_mo,$en_da,$en_yr);

	$format=str_replace("%en_day%", $en_da, $format);
	$format=str_replace("%en_daynum%", @date("w",$mktime), $format);
	$format=str_replace("%en_dayname%", lang("days" , @date("w",$mktime)), $format);
	$format=str_replace("%en_weekday%", lang("days" , @date("w",$mktime)), $format);
	$format=str_replace("%en_weeknum%", @date("W",$mktime), $format);
	$format=str_replace("%en_month%", $en_mo, $format);
	$format=str_replace("%en_monthname%", lang('months',-1+$en_mo), $format);
	$format=str_replace("%en_monname%", lang('months_abbr',-1+$en_mo), $format);
	$format=str_replace("%en_year%", $en_yr, $format);
	$format=str_replace("%en_ye%", substr($en_yr,2), $format);

	// === JM - 2004/09/12 ¥¥¥
	$format=str_replace("%en_aye%", "&#8217;".substr($en_yr,2), $format);
	$format=str_replace("%en_ordday%", 1*$en_da, $format);
	$format=str_replace("%en_ordmonth%", 1*$en_mo, $format);
	// === END JM ¥¥¥

	return $format;
}




function array_size($arr) {

	ob_start();
	print_r($arr);
	$output = ob_get_contents();
	ob_end_clean();
	return (strlen($output));
}

function getmicrotime(){
	list($usec, $sec) = explode(" ",microtime());
	return ((float)$usec + (float)$sec);
}


// calculate time that was needed for execution
function timetaken($type="") {
	global $starttime;
	$endtime = getmicrotime();
	$time_taken = $endtime - $starttime;
	$time_taken= number_format($time_taken, 3);  // optional

	if ($type=="int") {
		return $time_taken;
	} else {
		return "<span class='timetaken'>$time_taken</span>";
	}
}




function start_timer($label) {
	global $timer_running_array, $timer_stopped_array, $timer_stack, $started_count;

	$started_count[] = $label;

	// if the last item on the stack is set, stop it.
	if (count($timer_stack)>0) {
		$stop = end($timer_stack);
		//debug("interrupt: ". $stop);

		if (isset($timer_running_array[$stop])) {
			$timer_stopped_array[$stop] += timetaken('int') - $timer_running_array[$stop];
			unset ($timer_running_array[$label]);
		}

	}

	//debug("start: $label");
	$timer_running_array[$label] = timetaken('int');
	$timer_stack[]=$label;

}

function stop_timer($label) {
	global $timer_running_array, $timer_stopped_array, $timer_stack, $stopped_count;

	$stopped_count[] = $label;

	if (!isset($timer_stopped_array[$label])) { $timer_stopped_array[$label] = 0; }

	if (isset($timer_running_array[$label])) {
		//debug("stop $label:" . ( timetaken('int')  - $timer_running_array[$label]));
		$timer_stopped_array[$label] += timetaken('int') - $timer_running_array[$label];
		unset ($timer_running_array[$label]);

		array_pop($timer_stack);
		$continue = end($timer_stack);
		//debug("continue: ". $continue);

		$timer_running_array[$continue] = timetaken('int');

	}

}

function print_timers() {
	global $timer_running_array, $timer_stopped_array, $timer_stack, $started_count, $stopped_count;

	if (!isset($started_count)) { return; }

	// see if we opened more than we closed..
	foreach ($started_count as $value) { $open_count[$value]++; }
	foreach ($stopped_count as $value) { $open_count[$value]--; }
	foreach ($open_count as $key => $value) {
		if ($value <> 0) {
			echo "<b>$key = $value</b><br />";
		}
	}

	arsort($timer_stopped_array);

	echo "<pre>timer array:";
	print_r($timer_stopped_array);
	echo "</pre>";

}

function getwysywigable() {

	$errorlevel= error_reporting(0);

	if (file_exists('includes/phpsniff/phpSniff.class.php')) {
		include_once('includes/phpsniff/phpSniff.class.php');
	} else {
		include_once('includes/phpsniff/phpsniff.class.php');
	}

	$client = new phpSniff('',0);
	$client->init();

	error_reporting($errorlevel);

	return ( ($client->is('b:ie5up')) && ($client->property('platform')=="win") );

}



// print a row in overview
function print_row(&$entry){
	global $db, $global_pref, $linecount, $Pivot_Vars, $Users;

	if($entry['code']=="") { return; }

	if (!isset($linecount)) {
		$linecount=1;
	} else {
		$linecount++;
	}

	if (($linecount % 2)==0) {
		$bg_color="tabular_line_even";
	} else {
		$bg_color="tabular_line_odd";
	}


	printf("	<tr class='%s'>\n", $bg_color);

	printf("<td><input type='checkbox' name='check[%s]' /></td>",  $entry['code']);

	//if ( !isset($entry['status'])) { $entry['status']="hold"; }

	if ( $entry['status']=='publish' ) {
		//$pubdate = "published on: ". format_date($entry['publish_date'], "%day%-%monname%-%ye% %hour24%:%minute%");
		printf("		<td><a href=\"javascript:open_preview('%s')\">published</a>&nbsp;&nbsp;</td>\n", $entry['code']);
	} else {
		//$pubdate = "publish on: ". format_date($entry['publish_date'], "%day%-%monname%-%ye% %hour24%:%minute%");
		printf("		<td>%s&nbsp;&nbsp;</td>\n", $entry['status']);
	}

	//


	// You can only edit your own entries or the entries of someone who has a
	// lower userlevel, or if you're 'Admin'

	// to allow same level users to edit eachother's posts, use:
	// 	if ( ($Pivot_Vars['user']==$entry['user']) || ($Users[$Pivot_Vars['user']]['userlevel']>=
	// $Users[$entry['user']]['userlevel']) || ($Users[$Pivot_Vars['user']]['userlevel']>=2) ) {

	if ( ($Pivot_Vars['user']==$entry['user']) || ($Users[$Pivot_Vars['user']]['userlevel']> $Users[$entry['user']]['userlevel']) || ($Users[$Pivot_Vars['user']]['userlevel']>=3) ) {
		$editurl=sprintf("index.php?session=%s&amp;menu=entries&amp;func=modify&amp;id=%s", $Pivot_Vars['session'], $entry['code']);
		printf("		<td width='200'><span class='tabular'><a href='%s' title='%s'>%s</a></span></td>\n", $editurl, 'edit this entry',   trimtext($entry['title'], 30, TRUE));
	} else {
		printf("		<td width='200'><span class='tabular'><b>%s</b></span></td>\n",   trimtext($entry['title'], 30, TRUE));
	}

	if (is_array($entry['category'])) {
		$mycat = implode(", ",$entry['category']);
		printf("		<td><span title='%s'>%s</span></td>\n", $mycat, trimtext($mycat,14));
	} else {
		if ($entry['category']=="") { $entry['category']="(none)"; }
		printf("		<td>%s</td>\n", $entry['category']);
	}
	printf("		<td>%s</td>\n", $entry['user']);
	$date1= format_date($entry['date'], "%day%-%monname%-%ye% %hour24%:%minute%");
	//$date2= "created on: ". format_date($entry['edit_date'], "%day%-%monname%-%ye% %hour24%:%minute%")." / last edited on: ". format_date($entry['edit_date'], "%day%-%monname%-%ye% %hour24%:%minute%");
	printf("		<td class='tabular'>%s</td>\n", $date1);
	if ($entry['commcount']>0) {

		// You're only allowed to edit comments for your own entries if you're userlevel 2,
		// or for other entries if you're admin.
		if (  ( ($Pivot_Vars['user']==$entry['user']) && ($Users[$Pivot_Vars['user']]['userlevel']>=2) ) || ($Users[$Pivot_Vars['user']]['userlevel']>=3) ) {
			$commurl=sprintf("index.php?session=%s&amp;menu=entries&amp;func=editcomments&amp;id=%s", $Pivot_Vars['session'], $entry['code']);
			printf("		<td><a href='%s' title=\"%s\">%s</a></td>\n", $commurl, $entry['cnames'], $entry['commcount']);
		} else {
			printf("		<td>%s</td>\n", $entry['commcount']);
		}


	} else {
		printf("		<td>0</td>\n");
	}
        if ($entry['trackcount']>0) {

		// You're only allowed to edit comments for your own entries if you're userlevel 2,
		// or for other entries if you're admin.
		if (  ( ($Pivot_Vars['user']==$entry['user']) && ($Users[$Pivot_Vars['user']]['userlevel']>=2) ) || ($Users[$Pivot_Vars['user']]['userlevel']>=3) ) {
			$trackurl=sprintf("index.php?session=%s&amp;menu=entries&amp;func=edittrackbacks&amp;id=%s", $Pivot_Vars['session'], $entry['code']);
			printf("		<td><a href='%s' title=\"%s\">%s</a></td>\n", $trackurl, $entry['tnames'], $entry['trackcount']);
		} else {
			printf("		<td>%s</td>\n", $entry['trackcount']);
		}


	} else {
		printf("		<td>0</td>\n");
	}
        printf("	</tr>\n\n");

}




// print a row in overview
function print_row_overview(&$entry){
	global $db, $global_pref, $linecount, $Pivot_Vars, $Users;

	if($entry['code']=="") { return; }

	if (!isset($linecount)) {
		$linecount=1;
	} else {
		$linecount++;
	}

	if (($linecount % 2)==0) {
		$bg_color="tabular_line_even";
	} else {
		$bg_color="tabular_line_odd";
	}


	printf("	<tr class='%s'>\n", $bg_color);


	// You can only edit your own entries or the entries of someone who has a
	// lower userlevel, or if you're 'Admin'
	if ( ($Pivot_Vars['user']==$entry['user']) || ($Users[$Pivot_Vars['user']]['userlevel']> $Users[$entry['user']]['userlevel']) || ($Users[$Pivot_Vars['user']]['userlevel']>=3) ) {
		$editurl=sprintf("index.php?session=%s&amp;menu=entries&amp;func=modify&amp;id=%s",
		$Pivot_Vars['session'], $entry['code']);
		printf("		<td class='tabular-small'><a href='%s' title='%s'>%s</a></td>\n",
		$editurl, 'edit this entry',   trimtext($entry['title'], 22, TRUE));
	} else {
		printf("		<td class='tabular-small'><b>%s</b></td>\n",
		trimtext($entry['title'], 22, TRUE));
	}


	printf("		<td class='tabular-small'>%s</td>\n", $entry['user']);
	$date1= format_date($entry['date'], "%day%-%monname%-%ye%");

	printf("		<td class='tabular-small'>%s</td>\n", $date1);
	if ($entry['commcount']>0) {

		// You're only allowed to edit comments for your own entries if you're userlevel 2,
		// or for other entries if you're admin.
		if (  ( ($Pivot_Vars['user']==$entry['user']) && ($Users[$Pivot_Vars['user']]['userlevel']>=2) ) || ($Users[$Pivot_Vars['user']]['userlevel']>=3) ) {
			$commurl=sprintf("index.php?session=%s&amp;menu=entries&amp;func=editcomments&amp;id=%s", $Pivot_Vars['session'], $entry['code']);
			printf("		<td class='tabular-small'><a href='%s' title=\"%s\">%s</a></td>\n", $commurl, $entry['cnames'], $entry['commcount']);
		} else {
			printf("		<td class='tabular-small'>%s</td>\n", $entry['commcount']);
		}


	} else {
		printf("		<td>0</td>\n");
	}
        if ($entry['trackcount']>0) {

		// You're only allowed to edit comments for your own entries if you're userlevel 2,
		// or for other entries if you're admin.
		if (  ( ($Pivot_Vars['user']==$entry['user']) && ($Users[$Pivot_Vars['user']]['userlevel']>=2) ) || ($Users[$Pivot_Vars['user']]['userlevel']>=3) ) {
			$trackurl=sprintf("index.php?session=%s&amp;menu=entries&amp;func=edittrackbacks&amp;id=%s", $Pivot_Vars['session'], $entry['code']);
			printf("		<td class='tabular-small'><a href='%s' title=\"%s\">%s</a></td>\n", $trackurl, $entry['tnames'], $entry['trackcount']);
		} else {
			printf("		<td class='tabular-small'>%s</td>\n", $entry['trackcount']);
		}


	} else {
		printf("		<td>0</td>\n");
	}
	printf("	</tr>\n\n");

}


function last_comments_overview() {
	global $Pivot_Vars;

	@$lastcomments =	array_reverse(load_serialize("db/ser_lastcomm.php", true, true));

	if (count($lastcomments)>0) {

		printf('<table cellspacing="0" class="tabular_border" border="0" width="320" style="margin-top:6px;"><tr class="tabular_nav">');
		printf('<td colspan="2" class="tabular-lastheader">' . lang('userbar','recent_comments') . '</td></tr>', $prevlink);
		// echo '<tr class="tabular_header"><td class="tabular-lastheader">'. lang('entries', 'name') .'</td>';
		// echo '<td class="tabular-lastheader">'. lang('entries', 'date') .'</td>';
		// echo '</tr>';

		$linecount = 1;
		foreach ($lastcomments as $lastcomment) {

			if (($linecount % 2)==0) {
				$bg_color="tabular_line_even";
			} else {
				$bg_color="tabular_line_odd";
			}

			$link=sprintf("index.php?session=%s&amp;menu=entries&amp;func=editcomments&amp;", $Pivot_Vars['session']);
			$link=sprintf("%sid=%s#%s", $link, $lastcomment['code'], $lastcomment['date']);


			$name = trimtext($lastcomment['name'], 22, FALSE);
			$date = format_date($lastcomment['date'], "%day%-%monname%-%ye% %hour24%:%minute%");
			$comm = trimtext($lastcomment['comment'], 78, FALSE);
			$comm = mywordwrap($comm, 20, "  ", 1);

			printf("<tr class='%s'><td class='tabular-small'><a href='%s'>%s</a><br /><span class='date'>%s</span></td>", $bg_color, $link, $name, $date);
			printf("<td valign='top' class='tabular-small' style='white-space: normal;'>%s</td></tr>",  $comm);

			if ( ($linecount++)>4) { break; }

		}


		echo "</table><br />";

	}


}


function getthemes() {
	
	
	$themes = array();
	$dh = opendir('theme/');
		while($fname = readdir($dh)) {
			if(preg_match('!(.*?)_theme.inc.php!', $fname, $null)){
				$themefile = file('theme/'.$fname);
				array_push($themes, trim(substr($themefile[1], 2, -1)), $null[1]);
			}
		}
		$themefile = '';
	closedir($dh);
	
	return $themes;	
}



function gettemplates($filter="") {
	$d= dir("templates");
	while ($entry=$d->read()) {
		$ext=getextension($entry);
		if (($ext=="htm")||($ext=="html")) {

			$templates[]= $entry;
			$templates[]= $entry;

		}
	}
	$d->close();

	// remove _sub_commentform.. it's evil!
	foreach ($templates as $key => $val) {
		if ($val=="_sub_commentform.html") { unset($templates[$key]); }	
	}
	
	sort($templates);

	if ($filter!="") {
		foreach ($templates as $template) {
			if (strpos($template, $filter) !== FALSE) {
				$filtered[]=$template;
			}
		}

		// only filter if there are at least two (otherwise it breaks for people who
		// do upgrades)..
		if (count($filtered)>3) {
			return $filtered;
		} else {
			return $templates;
		}

	} else {
		return $templates;

	}

}


// return a bit of HTML to insert a <select> with all available categories
function get_categories_select($type="single") {
	global $db, $Cfg, $Pivot_Vars;
	
	// if we're logged in, we select the categories in which
	// the current user is allowed to post entries.
	if (isset($Pivot_Vars['user'])) {
		$testcats = explode("|", $Cfg['cats']);
		$cats = array();
		foreach ($testcats as $cat) {
			$allowed = explode("|", $Cfg['cat-'. $cat]);
			if (in_array($Pivot_Vars['user'], $allowed)) {
				$cats[] = $cat;
			}
		}
	} else {
		// else just select them all. 
		$cats = explode("|", $Cfg["cats"]);	
	}

	
	$output="";

	if (isset($db)) {
		$this_cat=$db->entry['category'];
	} else {
		// set a default category..
		$this_cat=array($cats[0]);
	}

	if ($type=="single") {

		$output="<select name='f_catsing' class='input' id='f_catsing' onChange='syncCat1(this);'>";

		foreach ($cats as $cat) {

			$cat= trim($cat);
			@$sel = (in_array($cat, $this_cat)) ? " selected" : "";
			$output.="<option value='$cat'$sel>$cat</option>\n";


		}

		$selmul = (count($this_cat)>1) ? " selected" : "";
		$selnone = (count($this_cat)<1) ? " selected" : "";

		$output.="<option value='(multiple)' ".$selmul.">(multiple)</option>\n";
		$output.="<option value='(none)' ".$selnone.">(none)</option>\n";

		$output.="</select>&nbsp;";
	} else {

		$output="<select name='f_catmult[]' size='6' multiple='multiple' class='input' style='height: auto;' id='f_catmult' onChange='syncCat2(this);'>";
		foreach ($cats as $cat) {
			$cat= trim($cat);
			@$sel = (in_array($cat, $this_cat)) ? " selected" : "";
			$output.="<option value='$cat'$sel>$cat</option>";
		}

		$output.="</select>&nbsp;";

	}



	return $output;
}


// return the number of categories
function get_categories_num() {
	$output="";

	if (isset($this->globals['categories'])) {
		return (count($this->globals['categories']));
	} else {
		return 0;
	}
}

// 2004/10/24 =*=*= JM
// returns 'true' if it finds a category in $theEntryCats
// that isn't in $theExclusionCats, else returns false
// determines if an entry can be indexed based on current exclusion categories
function can_search_cats( $theExclusionCats,$theEntryCats ) {

	// special case - if array empty then return true
	// cannot have an exclusion in that case...

	if( 0 == count( $theExclusionCats )) { return TRUE; }
	
	foreach( $theEntryCats as $catValue ) {
		foreach( $theExclusionCats as $excluValue ) {
			if( $catValue!=$excluValue ) { return TRUE; }
		}
	}
	return FALSE;
}

// 2004/10/15 =*=*= JM
// used by 'cfg_cat_nosearchindex()' & 'cfg_cats()'
function cfg_getarray( $theArrayName ) {
	global $Cfg;

	$thisArray = explode( '|',$Cfg[$theArrayName] );
	
	natcasesort( $thisArray);

	foreach( $thisArray as $key => $value) {
		if( ''==$value ) { unset( $thisArray[$key] ); }
	}
	return $thisArray;
}

// 2004/10/15 - new JM
// return $Cfg['cats-searchexclusion'] as an array. sorted and without empty ones
function cfg_cat_nosearchindex() {
	$thisArray = cfg_getarray( 'cats-searchexclusion' );
	return $thisArray;
}


// 2004/10/15 - new JM
// return $Cfg['cats'] as an array. sorted and without empty ones
// extended by bob: get the whole shebam at once in one easy array..
function cfg_cats() {
	global $Cfg;

	$tempArray = cfg_getarray('cats');
	
	// make the array keyed..
	foreach($tempArray as $cat) {
		$thisArray[$cat]['name'] = $cat;
	}

	
	// get the array of order, and clean up the cats-order, while we're at it
	if (isset($Cfg['cats-order'])) {
		$temp_arr = explode("|-|", $Cfg['cats-order']);
		foreach($temp_arr as $key => $temp_item) {
			list ($cat, $order) = explode('|', $temp_item);
			if (isset($thisArray[$cat])) {
				$thisArray[$cat]['order'] = $order;
			} else {
				unset($temp_arr[$key]);
			}
		}
		$Cfg['cats-order'] = implode("|-|", $temp_arr);
	}	
	
	// get the searchexclusions..
	$tempArray = cfg_getarray('cats-searchexclusion');
	foreach($tempArray as $cat) {
		$thisArray[$cat]['searchexclusion'] = 1;
	}	
	
	// get the public status..
	$tempArray = cfg_getarray('cats-nonpublic');
	foreach($tempArray as $cat) {
		$thisArray[$cat]['nonpublic'] = 1;
	}		
	
	
	// get the hidden status..
	$tempArray = cfg_getarray('cats-hidden');
	foreach($tempArray as $cat) {
		$thisArray[$cat]['hidden'] = 1;
	}		
	
	
	
	// get the allowed users..
	foreach ($thisArray as $key => $cat) {
		$thisArray[$key]['allowed'] = cfg_getarray('cat-'. $key);
	}
	
	// sort them by 'order'
	usort($thisArray, "category_sort");
	
	// unfortunately, usort destroys the keys.. put back the the keys..
	foreach ($thisArray as $key => $value) {
		unset($thisArray[$key]);
		if ($value['name'] != "") {
			$thisArray[ $value['name'] ] = $value;	
		}
	}
	
	return $thisArray;
}


// Sort the categories array by order..
function category_sort($a, $b) {
   if ($a['order'] == $b['order']) { 
        return strcasecmp($a['name'], $b['name']) ; 
   } 
   return ($a['order'] < $b['order']) ? -1 : 1; 
}

function category_simplesort($a, $b) {
	global $allcats;
	
	if ($allcats[$a]['order'] == $allcats[$b]['order']) { 
        return strcasecmp($a, $b) ; 
   } 
   return ($allcats[$a]['order'] < $allcats[$b]['order']) ? -1 : 1; 
}
	


// This function is used to find which weblogs publish a certain category
function find_weblogs_with_cat($cats) {
	global $Weblogs;

	// $cats might be a string with one cat, if so, convert to array
	if (is_string($cats)) {
		$cats= array($cats);
	}

	
	$res=array();

	// search every weblog for cat.
	foreach ($Weblogs as $key => $weblog) {

		// search each weblogs' sublogs
		foreach ($weblog['sub_weblog'] as $subweblog) {

			// search each element of the passed argument $cats
			if (is_array($cats)) {
				foreach ($cats as $cat) {
					if (in_array($cat, $subweblog['categories'])) {
						$res[]=$key;
					}
				}
			}
		}

	}
	return array_unique($res);

}


// merge a split date
function fix_date($date, $time) {
	
	//debug("date: $date , $time " );

	list($month, $day, $year)=split('[ /.:-]',$date);
	@list($hour,$minute,$sec)=split('[ /.:-]',$time);

	//$reverse_month=array_flip ($lang['Months']);

	//$month=1+$reverse_month[$month];

	return sprintf("%04d-%02d-%02d-%02d-%02d", $year, $month, $day, $hour, $minute);

}

// will return an absolute path to and index or archive page..
function get_log_url($type) {
	global $Weblogs, $Current_weblog, $db, $Paths;

	if ($type=='index') {
		if ($Weblogs[$Current_weblog]['front_path'][0] == '/') {
			$path = $Weblogs[$Current_weblog]['front_path'] . $Weblogs[$Current_weblog]['front_filename'];
		} else {
			$path = $Paths['pivot_url'] . $Weblogs[$Current_weblog]['front_path'] . $Weblogs[$Current_weblog]['front_filename'];
		}
		$path = fixPath($path);
	}

	//debug ("path: ".$path);

	return $path;

}

// fixPath fixes a relative path eg. '/site/pivot/../index.php' becomes '/site/index.php';
function fixPath($path) {
	$path = str_replace("\/", "/", $path);
	$path      = ereg_replace('/+', '/', $path);
	$patharray = explode('/', $path);
	foreach ($patharray as $item) {
		if ($item == "..") {
			// remove the previous element
			@array_pop($new_path);
		} else if ( ($item != ".") ) {
			$new_path[]=$item;
		}
	}
	return implode("/", $new_path);
}


// an easy function to recursively create chmodded directory's
function makedir($name) {

	// if it exists, just return.
	if (file_exists($name)) {
		return;
	}

	// if more than one level, try parent first..
	if (dirname($name) != ".") {
		makedir(dirname($name));
	}

	$oldumask = umask(0);
	@mkdir ($name, 0777);
	@chmod ($name, 0777);
	umask($oldumask);

}



function buildfrontpage_function() {
	global $db, $Pivot_Vars, $Cfg, $VerboseGenerate, $pivot_path, $Weblogs;
	
	$db = new db();
	
	$amount = max(4, 3*(count($Weblogs)));
	$overview_arr = $db->getlist(-$amount,0,"","", FALSE);
	
	$VerboseGenerate = TRUE;

	foreach ($overview_arr as $entry) {
		generate_pages($entry['code'], TRUE, TRUE, TRUE, FALSE);
	}

}




function getdaterange($date, $unit) {

	$numdaysin = array(0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

	list($yr,$mo,$da,$ho,$mi)=split("-",$date);

	$yr_min = $yr_max = $yr;
	$mo_min = $mo_max = $mo;

	if ($unit=='day') {

		$range_min = date("Y-m-d-00-00", mktime(0,0,0,$mo,$da,$yr));
		$range_max = date("Y-m-d-23-59", mktime(0,0,0,$mo,$da,$yr));

	} else if ($unit=='week') {

		$dow = ((@date("w", mktime(0,0,0,$mo,$da,$yr)) + 6) % 7);
		$range_min = @date("Y-m-d-00-00", mktime(0,0,0,$mo,($da-$dow),$yr));
		$range_max = @date("Y-m-d-23-59", mktime(0,0,0,$mo,($da-$dow+6),$yr));

	} else if ($unit=='month'){

		$da_min = '01';
		$da_max = $numdaysin[(0+$mo_max)];

		// put the ranges back together.
		$range_min = sprintf("%02d-%02d-%02d-00-00", $yr_min,$mo_min,$da_min);
		$range_max = sprintf("%02d-%02d-%02d-23-59", $yr_max,$mo_max,$da_max);

	} else {

		$mo_min = '01';
		$mo_max = '12';
		$da_min = '01';
		$da_max = '31';

		// put the ranges back together.
		$range_min = sprintf("%02d-%02d-%02d-00-00", $yr_min,$mo_min,$da_min);
		$range_max = sprintf("%02d-%02d-%02d-23-59", $yr_max,$mo_max,$da_max);

	}

	return array($range_min, $range_max);

}


function entries_action ($do_action, $do_entries) {

	// if deleting, this is serialized
	if (!is_array($do_entries)) {
		$do_entries = unserialize(stripslashes($do_entries));
	}


	foreach ($do_entries as $do_entry => $dummy) {
		if ($do_action=="delete") { entries_action_delete($do_entry); }
		if ($do_action=="hold") { entries_action_hold($do_entry); }
		if ($do_action=="publish") { entries_action_publish($do_entry); }
		if ($do_action=="generate") { entries_action_generate($do_entry); }
	}

	
	buildfrontpage_function();
	
}

// 2004/12/28 =*=*= JM - bug report 0056 - problems if $last_comms isn't an array on windows servers 
function entries_action_delete( $code ) {
    global $db, $Pivot_Vars, $Users;

    $db = new db();
    
    if ($db->get_entries_count()<2) {
     	echo "<p>".lang('general', 'cantdeletelast')."</p>";
    	return;	
    }
    
      
    $db->read_entry( $code );
    // checking to see whether user is allowed to delete the entry goes here
    if(( $Pivot_Vars['user'] == $db->entry['user'] ) || ( $Users[$Pivot_Vars['user']]['userlevel'] >= 3 )) {
        @$last_comms = load_serialize( "db/ser_lastcomm.php",true,true );
        if(( count( $last_comms ) > 0 ) && ( is_array( $last_comms ))) {
            foreach( $last_comms as $key => $last_comm ) {
                if(( $last_comm['code'] == $entry['code'] )) {
                    unset( $last_comms[$key] );
                    save_serialize( "db/ser_lastcomm.php", $last_comms );
                }
            }
        }

        $db->delete_entry();
    } else {

        $msg = "<p>".lang('general', 'cantdelete')."</p>";
        $msg = str_replace( "%title%","'".$db->entry['title']."'",$msg );
        echo $msg;
    }

} 


function entries_action_hold($code) {
	global $db, $Pivot_Vars, $Users;

	$db = new db();
	$db->read_entry($code);

	// checking to see whether user is allowed to delete the entry goes here
	if ( ($Pivot_Vars['user'] == $db->entry['user']) || ($Users[$Pivot_Vars['user']]['userlevel'] >= 3) ) {
		$entry= $db->entry;
		$entry['status']="hold";

		$db->set_entry($entry);
		$db->save_entry();

	} else {
		$msg = lang('general', 'cantdothat');
		$msg = str_replace("%title%", "'".$db->entry['title']."'", $msg);
		echo $msg."<br />";
	}
}

function entries_action_publish($code) {
	global $db, $Pivot_Vars, $Users;

	$db = new db();
	$db->read_entry($code);

	// checking to see whether user is allowed to delete the entry goes here
	if ( ($Pivot_Vars['user'] == $db->entry['user']) || ($Users[$Pivot_Vars['user']]['userlevel'] >= 3) ) {
		$entry= $db->entry;
		$entry['status']="publish";
		$db->set_entry($entry);
		$db->save_entry();

	} else {
		$msg = lang('general', 'cantdothat');
		$msg = str_replace("%title%", "'".$db->entry['title']."'", $msg);
		echo $msg."<br />";
	}
}

function entries_action_generate($code) {
	global $db, $Pivot_Vars, $Users;

	$db = new db();
	$db->read_entry($code);

	// checking to see whether user is allowed to delete the entry goes here
	if ( ($Pivot_Vars['user'] == $db->entry['user']) || ($Users[$Pivot_Vars['user']]['userlevel'] >= 3) ) {
		$entry= $db->entry;
		if ($entry['status']!="publish") {
			$entry['status']="publish";
			$db->set_entry($entry);
			$db->save_entry();
		}
		generate_pages($entry['code'], TRUE, TRUE, TRUE, FALSE);
	} else {
		$msg = lang('general', 'cantdothat');
		$msg = str_replace("%title%", "'".$db->entry['title']."'", $msg);
		echo $msg."<br />";
	}
}


function files_action ($do_action, $do_files) {


	foreach ($do_files as $do_file => $dummy) {
		// echo "$do_action - $do_file<br />";
		if ($do_action=="delete") { files_action_delete($do_file); }

	}

}

function files_action_delete($name) {
	global $Cfg, $Pivot_Vars;

	if($Pivot_Vars['do'] == 'templates') {
		// NOTE: 13-4-2003
		// Must be changed to $Cfg['templates_path'] !!!!!!
		chdir('templates/');
		unlink($name);
		chdir('../');
	} else {
		$file = "../".$Cfg['upload_path'] . $name;
		//echo "file: ".$file."<br />";
		unlink($file);
	}

	// also force the archive index file to be updated
	@unlink('db/ser-archives.php');

}


function init_emoticons() {
	global $emot, $emot_path, $base_url, $Paths, $emoticon_window, $emoticon_window_width, $emoticon_window_height;

	if(!defined('_EMOTICONS_INCLUDED'))  {
		define('_EMOTICONS_INCLUDED',1);

		if (file_exists($Paths['extensions_path']."emoticons/config.inc.php")) {
			include ($Paths['extensions_path']."emoticons/config.inc.php");
			
			$path = fixpath(sprintf("%semoticons/%s", $Paths['extensions_url'], $emoticon_images));
			$align = set_target("", " align='middle'");			
			foreach ($emot as $emot_code => $emot_file) {
				$emot[$emot_code]=sprintf("<img src='%s/%s' alt='%s'%s/>", $path, $emot_file, addslashes($emot_code), $align);
			}
		}
	}
	

}


function emoticonize($text) {
	global $emot;

	init_emoticons();
		
	foreach ($emot as $emot_code => $emot_html) {
		$text=str_replace($emot_code, $emot_html, $text);
	}
	
	return $text;
	
}



function targetblank($text, $autoredirect=FALSE) {
	global $Weblogs, $Current_weblog, $Cfg, $pivot_url;
	
	if ( $Weblogs[$Current_weblog]['target_blank']==1 ) {
		if (strpos($text, "target='_blank'") === FALSE) {
			$text = preg_replace("'<a href=[\"|\'](.*[^>])[\"|\']([^<>]*)>(.*)</a>'iUs", "<a href=\"\\1\" \\2 target='_blank'>\\3</a>",$text);
		}
		
		if ($autoredirect) {
			$text = preg_replace("'<a href=[\"|\'](.*[^>])[\"|\']([^<>]*)>(.*)</a>'iUs", "<a href=\"\\1\" \\2 rel='nofollow'>\\3</a>",$text);
		}
		
	} else {

		$rel = "";
		
		if ( $Weblogs[$Current_weblog]['target_blank']==2 ) {	
			$rel="external";
		}
		
		if ($autoredirect) {
			$rel.=" nofollow";
			$rel = trim($rel);
		}
		
		if ((strpos($text, "rel='") === FALSE) && (strlen($rel)>1) ) {
			$text = preg_replace("'<a href=[\"|\'](.*[^>])[\"|\']([^<>]*)>(.*)</a>'iUs", "<a href=\"\\1\" \\2 rel='".$rel."'>\\3</a>",$text);
		}
	}


	return $text;

}



// this function will replace a portion of a string $text
// with x's starting at position $leftpos through to position $rightpos
// $text is passed by reference...
function blankout(&$text, $leftpos, $rightpos) {
	while ($leftpos<=$rightpos) {
		$text[$leftpos]="x";
		$leftpos++;
	}
}


// get_attr_value
//
// This function will search $attributes for the value for the
// the attribute in $att_name.
// for example, after calling:
// $my_value=get_attr_value('size', 'color="green" size="12"');
// my_value will contain 12
function get_attr_value($att_name, $attributes) {
	// first, we need do do some tricks to find out where we'll have
	// to split the $attributes string

	$attributes=stripslashes(str_replace("&quot;",'"', $attributes));
	$this_attr=substr($attributes, strpos($attributes,$att_name));
	$pos=strpos($attributes,$att_name);

	if (preg_match("/$att_name=\"([^\"]*)\"/i", $attributes, $parts)) {
		return $parts[1];
	} else {
		return "";
	}
}


function init_encode_table() {
	global $encode_html,$encode_html_slim, $decode_html;

	// To encode funny characters like é to &eacute; we use
	// the standard php-functions.
	$encode_html=get_html_translation_table(HTML_ENTITIES);

	// unfortunately, these are not complete, so we add some more..
	$encode_html['']="&#0145;";
	$encode_html['']="&#0146;";
	$encode_html['']="&#0130;";
	$encode_html['']="&#0147;";
	$encode_html['']="&#0148;";
	$encode_html['']="&#0153;";
	$encode_html['¶']="&oelig;";
	$encode_html['']="&euro;";
	$encode_html['']="&hellip;";
	//$encode_html['']="";

	// The array to translate entities to funny characters..
	$decode_html=array_flip($encode_html);

	$encode_html_slim =  $encode_html;

	unset($encode_html_slim['"']);
	unset($encode_html_slim["'"]);
	unset($encode_html_slim['<']);
	unset($encode_html_slim['>']);

	/* echo "<pre>";
	print_r($encode_html_slim);
	echo "</pre>";

	echo(count($encode_html)."<BR>");
	echo(count($encode_html_slim)."<br>");
	*/
}


function escape($i) {
	//global $encode_html, $decode_html;

	$i = stripslashes($i);
	//$i = strtr($i, $decode_html);

	// according to the php manual, these have to be translated back
	// in order to output proper html
	// '&' (ampersand) becomes '&amp;'
	// '"' (double quote) becomes '&quot;' when ENT_NOQUOTES is not set.
	// ''' (single quote) becomes '&#039;' only when ENT_QUOTES is set.
	// '<' (less than) becomes '&lt;'
	// '>' (greater than) becomes '&gt;'

	$i = str_replace ('&lt;', '<', $i);
	$i = str_replace ('&gt;', '>', $i);
	$i = str_replace ('&quot;', '"', $i);
	$i = str_replace ('&#039;', "'", $i);
	$i = str_replace ('&amp;', "&", $i);

	return $i;
}



// This function makes &eacute; out of é, but leaves html tags alone..
// useful for storing comments..
function entify($i) {
	global $encode_html_slim, $i18n_use;

	if ($i18n_use) {
		return i18n_entify($i);
	}

	if (!isset($encode_html_slim)) {
		init_encode_table();
	}

	$i = strtr($i, $encode_html_slim);

	// these have to be translated back in order to output html-tags
	//$i = str_replace ('&lt;', '<', $i);
	//$i = str_replace ('&gt;', '>', $i);
	//$i = str_replace ('&quot;', '"', $i);
	//$i = str_replace ('&#039;', "'", $i);
	//$i = str_replace ('&amp;', "&", $i);

	return $i;
}


// This function makes é out of &eacute;
function unentify($i) {
	global $encode_html, $decode_html, $i18n_use;

	if ($i18n_use) {
		return i18n_unentify($i);
	}

	if (!isset($encode_html)) {
		init_encode_table();
	}

	$i = strtr($i, $decode_html);

	return $i;
}




function xml2html($i) {

	$i=htmlspecialchars($i);

	$i = str_replace ('&lt;', '<', $i);
	$i = str_replace ('&gt;', '>', $i);
	$i = str_replace ('&quot;', '"', $i);
	$i = str_replace ('&#039;', "'", $i);
	$i = str_replace ('&amp;', "&", $i);

	return $i;

}


function addquotes($i) {

	$i= str_replace("'", '&#039;', $i);
	$i= str_replace('"', '&quot;', $i);

	return $i;

}


function addltgt($i) {

	$i= str_replace('&gt;', '&amp;gt;', $i);
	$i= str_replace('&lt;', '&amp;lt;', $i);

	return $i;

}


// check if $text is word-html-crap (ie. pasted from word)
function is_word_html($text) {

	$a = strpos($text, "<o:p></o:p>");
	$b = strpos($text, "MsoNormal");
	$c = strpos($text, "mso-bidi");
	$d = strpos($text, "xml:namespace");

	//echo "$a - $b - $c - $d";

	return ($a || $b || $c || $d);
}



// make sure n00b users cannot post < ? echo "pom"; ? > and the like..
function strip_scripting($text) {
	global $Pivot_Vars, $Users;

	if($Users[$Pivot_Vars['user']]['userlevel'] < 3) {
		// all users that are not administrator..
		$text = str_replace("<?", "&lt;?", $text);
		$text = str_replace("?".">", "?&gt;", $text);
		$text = str_replace("<%", "&lt;%", $text);
		$text = str_replace("%".">", "%&gt;", $text);
		return $text;
	} else {
		return $text;
	}

}



// clean up word html..
function strip_word_html($text) {

	//	echo "\n\n\n\n<hr>$text<hr>";

	$text = stripslashes($text);
	$text = str_replace("<?xml:namespace", "<pom", $text);
	$text = strip_tags($text,'<b><i><u><a><br><p><em><strong>');
	$text = ereg_replace ("<a[^>]+href *= *([^ ]+)[^>]*>", "<a href=\\1>", $text);
	$text = ereg_replace ("<([b|i|u|br|p|em|strong])[^>]*>","<\\1>", $text);

	return $text;
}


// rewrite of php's wordwrap function, so it won't break entities like &eacute;
function mywordwrap($i, $width=25, $break="  ", $cut=1) {
	global $i18n_use;

	$i = unentify($i);
	if ($i18n_use) {
		$i = i18n_wordwrap($i, $width, $break, $cut);
	} else {
		$i = wordwrap($i, $width, $break, $cut);
	}
	$i = entify($i);

	return $i;

}



/**
 * Trim a text to a given length, taking html entities into account.
 *
 * @param string $str string to trim
 * @param int $length position where to trim
 * @param boolean $nbsp whether to replace spaces by &nbsp; entities
 *
 * @return string trimmed string
 */ 
function trimtext($str, $length, $nbsp=false) {

	$str = strip_tags($str);

	if (function_exists('mb_strwidth')) {
		if (mb_strwidth($str)>$length) {
			$str = unentify($str);
			$my_encoding = mb_detect_encoding($str);
			if ($my_encoding != "") {
				$str = mb_strimwidth($str,0,$length+1, '', $my_encoding);
			} else {
				$str = mb_strimwidth($str,0,$length+1);
			}

			$str = entify($str)."&hellip;";
		}
	} else {
		if (strlen($str)>$length) {
			$str = unentify($str);
			$str = substr($str,0,$length+1);
			$str = entify($str)."&hellip;";
		}
	}

	if ($nbsp==true) {
		$str=str_replace(" ", "&nbsp;", $str);
	}

	$str=str_replace("http://", "", $str);

	return $str;

}



function encodemail_link($mail, $nick, $title="") {
	global $Weblogs , $Current_weblog;

	if ($mail=="") {
		return $nick;
	} else {
		$mail=strip_tags($mail);
	}

	if ($title=="") {
		$title=$nick;
	} else {
		$title=strip_tags($title);
	}

	$title= str_replace("'", '&#039;', $title);
	$title= str_replace('"', '&quot;', $title);

	if ($Weblogs[$Current_weblog]['encode_email_addresses']) {
		include_once "modules/safeaddress.inc.php";
		$mail = safeAddress($mail, $nick, "email $title", 1, 0);
		return $mail;
	} else {
		return "<a href='mailto:$mail' title='email ".addslashes($title)."'>$nick</a>";
	}

}


// Wrapper for Textile.. Use textile 2, but if it's not there, use textile 1
function pivot_textile($str) {
	global $textile;
	
	if (isset($textile)) {
		
		$output = $textile->TextileThis($str);
		return $output;
		
	} else 	if (file_exists("includes/textile/classtextile.php")) {

		include_once "includes/textile/classtextile.php";
		
		$textile = new Textile;
		
		$output = $textile->TextileThis($str);
		return $output;

	} else if (file_exists("includes/textile/textile.php")) {

		include_once 'includes/textile/textile.php';
		return textile($str);

	} else {

		return $str;

	}

}


function pivot_markdown($str, $with_smartypants=0) {
	global $Cfg; 
	
	if (file_exists("includes/markdown/markdown.php")) {

		include_once "includes/markdown/markdown.php";
		
		$output = markdown($str);
		
		if ($with_smartypants == 4) {
			include_once 'includes/markdown/smartypants.php';
			$output = SmartyPants($output);
		}
		
		return $output;

	} else {

		debug("couldn't find includes/markdown/markdown.php");
		
		return $str;
		
	}
	
}


// for the comments we use this function..
// we strip _all_ tags except <b> and <i> and after that
// we convert everything that looks like a url or mail-adress
// to the equivalent link.. very nifty..

function comment_format( $text ) {
    global $Cfg,$Weblogs,$Current_weblog,$Paths;

    $text = trim( strip_tags( $text,'<b>,<i>,<em>,<strong>' ));
    $text = preg_replace('/<([^\s>]*)(\s[^<]*)>/i',"<\\1>", $text);

    // did user request '_blank' target rerouting?
    if(( 1 == $Weblogs[$Current_weblog]['target_blank'] )) {
        $target = ' target="_blank"';
    } else {
        $target = '';
    }

    // 2004/11/30 - JM - ???
    // shouldn't we check that we cut no urls or accents here???
    if(( isset( $Cfg['comment_wrap'] )) && ( $Cfg['comment_wrap'] > 0 ) &&  check_php_version( '4.0.3' )) {
        $text = wordwrap( $text,$Cfg['comment_wrap'],' ',1 );
    }

    if( 1 == $Weblogs[$Current_weblog]['comment_textile'] ) {
        // the old-style automatic links are converted to textile links.
        // (bit of an ugly fix, but it works..
        
        $text = preg_replace("/([ \t]|^)www\./Ui"," http://www.",$text);
        $text = preg_replace("/(http:\/\/[^  )\r\n]+)/i","\"[[\\1]]\":\\1",$text);
        
        
        // fix wrongfully matched links
        $text = preg_replace("/:\"\[\[(.*)\]\]\":/Ui", ":", $text);
        $text = preg_replace("/!\"\[\[(.*)!\]\]\":/Ui", "!", $text);
        $text = preg_replace("/\"\"\[\[(.*)\]\]\":/Ui", '"', $text);
                
        $text =  eregi_replace("([-a-z0-9_]+(\.[_a-z0-9-]+)*@([a-z0-9-]+(\.[a-z0-9-]+)+))","<a href=\"mailto:\\1\">\\1</A>",$text);
        
        $text = pivot_textile( $text );
          
        // when textiled, we only want <br />, and not <p>.. Clean <p> and  </p>
        $text = preg_replace("/<br \/>/sUi", "", $text);
        $text = preg_replace("/<p([^>]*)>/sUi", "", $text);
        $text = preg_replace("/<\/p>/sUi", "", $text);       
        $text = nl2br(trim($text));   
        
    } else {

        $text = eregi_replace("([ \t]|^)www\."," http://www.",$text);
        $text = eregi_replace("([ \t]|^)ftp\."," ftp://ftp.",$text);
        $text = eregi_replace("(http://[^ )\r\n]+)","<a  href=\"\\1\"$target>[[\\1]]</a>",$text);
        $text = eregi_replace("(https://[^ )\r\n]+)","<a  href=\"\\1\"$target>[[\\1]]</a>",$text);
        $text = eregi_replace("(ftp://[^ )\r\n]+)","<a  href=\"\\1\"$target>[[\\1]]</a>",$text);
       

        // 2004/11/30 =*=*= JM - clear up messed ftp links with '@' in
        preg_match_all ("|\[\[(.*)\]\]|U", $text, $match, PREG_PATTERN_ORDER);

        // do we need to do changes?
        if(( is_array( $match )) && ( count( $match ) > 0 )) {
            foreach( $match[1] as $url ) {
                $url2 = str_replace( '@',  '%40', $url );
                $text = str_replace( $url, $url2, $text );
            }
        }

        $text =  eregi_replace("([-a-z0-9_]+(\.[_a-z0-9-]+)*@([a-z0-9-]+(\.[a-z0 -9-]+)+))","<a href=\"mailto:\\1\">\\1</a>",$text);
        $text = nl2br( trim( $text ));

        // now change the '@' back...
        $text = str_replace( '%40','@',$text );
    }

    // then make long urls into short urls, with correct link..
    preg_match_all ("|\[\[(.*)\]\]|U", $text, $match, PREG_PATTERN_ORDER);

    foreach( $match[1] as $url ) {
        if( strlen( $url ) > 40 ) {
            $s_url = substr( $url,0,40 ).'..';
        } else {
            $s_url = $url;
        }
        $text = str_replace( '[['.$url.']]',$s_url,$text );
    }

    // perhaps redirect the link..
    if( isset( $Weblogs[$Current_weblog]['lastcomm_redirect'] ) && ( 1 ==  $Weblogs[$Current_weblog]['lastcomm_redirect'] )) {
        //$text = str_replace(  'href="http://','href="'.$Paths['pivot_url'].'includes/re.php?http://',$text );
        $text = preg_replace("'<a href=[\"|\'](.*[^>])[\"|\']([^<>]*)>(.*)</a>'iUs", "<a href=\"\\1\" \\2 rel='nofollow'>\\3</a>",$text);
    }

    if ($Weblogs[$Current_weblog]['emoticons']==1) {
		$text=emoticonize($text);
	}

	return (stripslashes($text));
}

// for the trackbacks we use this function..
// we strip _all_ tags except <b> and <i> and after that
// we convert everything that looks like a url or mail-adress
// to the equivalent link.. very nifty..

function trackback_format( $text ) {
    global $Cfg,$Weblogs,$Current_weblog,$Paths;

    $text = trim( strip_tags( $text,'<b>,<i>,<em>,<strong>' ));
    
    // did user request '_blank' target rerouting?
    if(( 1 == $Weblogs[$Current_weblog]['target_blank'] )) {
        $target = ' target="_blank"';
    } else {
        $target = '';
    }

    $text = eregi_replace("([ \t]|^)www\."," http://www.",$text);
    $text = eregi_replace("([ \t]|^)ftp\."," ftp://ftp.",$text);
    $text = eregi_replace("(http://[^ )\r\n]+)","<a  href=\"\\1\"$target>[[\\1]]</a>",$text);
    $text = eregi_replace("(https://[^ )\r\n]+)","<a  href=\"\\1\"$target>[[\\1]]</a>",$text);
    $text = eregi_replace("(ftp://[^ )\r\n]+)","<a  href=\"\\1\"$target>[[\\1]]</a>",$text);
       

    // 2004/11/30 =*=*= JM - clear up messed ftp links with '@' in
    preg_match_all ("|\[\[(.*)\]\]|U", $text, $match, PREG_PATTERN_ORDER);

    // do we need to do changes?
    if(( is_array( $match )) && ( count( $match ) > 0 )) {
        foreach( $match[1] as $url ) {
            $url2 = str_replace( '@',  '%40', $url );
            $text = str_replace( $url, $url2, $text );
        }
    }

    $text =  eregi_replace("([-a-z0-9_]+(\.[_a-z0-9-]+)*@([a-z0-9-]+(\.[a-z0 -9-]+)+))","<a href=\"mailto:\\1\">\\1</a>",$text);
    $text = nl2br( trim( $text ));

    // now change the '@' back...
    $text = str_replace( '%40','@',$text );

    // then make long urls into short urls, with correct link..
    preg_match_all ("|\[\[(.*)\]\]|U", $text, $match, PREG_PATTERN_ORDER);

    foreach( $match[1] as $url ) {
        if( strlen( $url ) > 40 ) {
            $s_url = substr( $url,0,40 ).'..';
        } else {
            $s_url = $url;
        }
        $text = str_replace( '[['.$url.']]',$s_url,$text );
    }

    // perhaps redirect the link..
    if( isset( $Weblogs[$Current_weblog]['lastcomm_redirect'] ) && ( 1 ==  $Weblogs[$Current_weblog]['lastcomm_redirect'] )) {
        //$text = str_replace(  'href="http://','href="'.$Paths['pivot_url'].'includes/re.php?http://',$text );
        $text = preg_replace("'<a href=[\"|\'](.*[^>])[\"|\']([^<>]*)>(.*)</a>'iUs", "<a href=\"\\1\" \\2 rel='nofollow'>\\3</a>",$text);
    }

    if ($Weblogs[$Current_weblog]['emoticons']==1) {
		$text=emoticonize($text);
	}

	return (stripslashes($text));
}


// tests a file, and outputs some information about that file.
function testfile($name) {
	global $testfile_array;

	if (file_exists($name)) {
		echo "- This file does exist";
		if (is_writable($name)) {
			echo " and is writable!";
		} else {
			echo " and is <b>not</b> writable";
		}
	} else {

		if (strpos("$name", "%")>0) {
			// special case for archives and entries
			if (is_writable(dirname($name))) {
				echo "- These files <b>can</b> be created by Pivot!";
			} else {
				echo "- These files can <b>not</b> be created either. <br />&nbsp;&nbsp;Please check the rights of the following folder using your FTP program :<br />&nbsp;&nbsp;<span class='filename'>". dirname(realpath($name))."</span>";
			}

		} else {
			// all other cases
			echo "- This file does <b>not</b> exist";
			if (is_writable(dirname($name))) {
				echo " but <b>can</b> be created by Pivot! (no action required)";
			} else {
				echo " and can <b>not</b> be created either. <br />&nbsp;&nbsp;Please check the rights of the following folder using your FTP program :<br />&nbsp;&nbsp;<span class='filename'>". dirname(realpath($name))."</span>";
			}
		}
	}

	echo "<br />\n";

	if (!isset($testfile_array[$name])) {
		$testfile_array[$name] = TRUE;
	} else {
		$str = "Another Weblog also writes to this file!! You should really change the settings on this weblog or on the other one!";
		echo "- <b>".wordwrap($str, 80, "<br />&nbsp;\n")."</b><br />\n";
	}

}


// This function loads a serialized file, unserializes it, and returns it
function load_serialize($filename, $silent=FALSE, $force=FALSE) {
	global $serialize_cache, $pivot_path;

	/*
	if(function_exists("debug")) {
		debug("load serialize $filename");
	}
	*/
	
	$filename = fixpath($filename);

	/*
	if ($data = serialize_getcache($filename)) {
		
		return $data;
	
	} else {
	*/
		if (!is_readable($filename)) {
		
			if (is_readable($pivot_path.$filename)) {
				$filename = $pivot_path.$filename;
			} else {
				$filename = "../".$filename;
			}
		}
		
		if (!is_readable($filename)) {
			
			// If $silent is true, fail gracefully..
			if ($silent) { return ""; }
			
			echo "<p>pad: ".getcwd()."</p>";
			$message = str_replace("%name%", $filename, "A needed file ('%name%') does exist, but pivot is not allowed to read it. <br /><br />Try logging in with your ftp-client and check to see if it is chmodded to be readable by the webuser (ie: 766). Else go <a href='javascript:history.go(-1)'>back</a> to the last page.");
			piv_error("File is not readable!", $message, 0);
		}
	
		$serialized_data = trim(implode("", file($filename)));
		
		$serialized_data = str_replace("<?php /* pivot */ die(); ?>", "", $serialized_data);
		
		if (@$data = unserialize($serialized_data)) {
//			serialize_setcache($filename,$data);
			return $data;
		} else {
			$temp_serialized_data = preg_replace("/\r\n/", "\n", $serialized_data);
			if (@$data = unserialize($temp_serialized_data)) {
//				serialize_setcache($filename,$data);
				return $data;
			} else {
				$temp_serialized_data = preg_replace("/\n/", "\r\n", $serialized_data);
				if (@$data = unserialize($temp_serialized_data)) {
//					serialize_setcache($filename,$data);
					return $data;
				} else {
					//debug("could not deserialize $filename!");
					return FALSE;
				}
			}
		}
//	}
}

// This function serializes some data and then saves it.
function save_serialize($filename, &$data) {
	global $Cfg;
		
	$filename = fixPath($filename);
		
//	serialize_uncache($filename);
	
	$ser_string = "<?php /* pivot */ die(); ?>".serialize($data);

	// disallow user to interrupt
	ignore_user_abort(TRUE);

	$old_umask = umask(0111);

	if (($Cfg['unlink'] == 1) && (file_exists($filename))) {
		/* unlinking is good for some safe_mode users */
		/* and bad for some others.. i hate safe_mode */
		@unlink($filename);
	}
	
	// open the file and lock it.
	if($fp=fopen($filename, "w")) {
		flock( $fp, LOCK_EX );

		// write it
		if (fwrite($fp, $ser_string)) {
			flock( $fp, LOCK_UN );
			fclose($fp);
		} else {
			flock( $fp, LOCK_UN );
			fclose($fp);
			piv_error("Error writing file",  "The file <b>$filename</b> could not be written! Current path: ".getcwd()."." );
			return FALSE;
		}
	} else {
		piv_error("Error opening file",  "The file <b>$filename</b> could not be opened for writing! Current path: ".getcwd()."." );
		
		return FALSE;
	}
	umask($old_umask);

	// reset the users ability to interrupt the script
	ignore_user_abort(FALSE);


	return TRUE;

}

/**
 * Get a serialized file from the cached files array..
 *
 * @param string $filename
 * @return mixed $data
 *
 */
function serialize_getcache($filename) {
	global 	$serialize_cache;
	
	if ( isset($serialize_cache[$filename]) ) { 
		// debug("FROM serialize cache: ".$filename. "(".count($serialize_cache).")");
		return $serialize_cache[$filename];
	} else {	
		return false;	
	}
	
}


/**
 * Add a serialized file to the cached files array..
 *
 * @param string $filename
 * @param mixed $data
 *
 */
function serialize_setcache($filename, &$data) {
	global 	$serialize_cache;
	
	// cache no more than this amount of items, to keep memory from going insane..
	$max_cache = 10;
	
	// Initialize the cache, if necessary..
	if (!isset($serialize_cache)) {
		$serialize_cache = array();
	}

	// cache no more than 10 items, to keep memory from going insane..
	if (count($serialize_cache) > $max_cache ) {
		// we remove the first entry, assuming that is the one that is least likely to be needed again.
		reset($serialize_cache);
		list($key) = each($serialize_cache);
		unset($serialize_cache[$key]);
	}
	
	$serialize_cache[$filename] = $data;
	// debug("added to cache: ".$filename. "(".count($serialize_cache).")");

}

/**
 * Remove a file from the serialize cache. A filename is passed, or the 
 * special case 'ALL', to clear the entire cache. 
 *
 * @param string $filename
 *
 */
function serialize_uncache($filename) {
	global 	$serialize_cache;
	
	
	if ($filename == "ALL") {
		unset($GLOBALS['serialize_cache']);
		//$serialize_cache = array();
	} else if ( (isset($serialize_cache[$filename])) && ($serialize_cache[$filename] != "") ) { 
		unset($GLOBALS['serialize_cache'][$filename]);
		unset($serialize_cache[$filename]);
	} 
	
	// debug("*REMOVED* from cache: ".$filename. "(".count($serialize_cache).")");

}

// saves a file, and outputs some feedback, if wanted..
function write_file($filename, $output) {
	global $VerboseGenerate, $Cfg;

	if ($VerboseGenerate) { echo lang('general', 'write').": ".$filename."<br />\n"; }

	// open up..
	if(!$fh = fopen( $filename, 'w' )) {
		if ($VerboseGenerate) { echo lang('general', 'write_open_error').": ".$filename."<br />\n"; }
	}

	// wrrrriting!
	if(!fwrite($fh, $output)) {
		if ($VerboseGenerate) { echo lang('general', 'write_write_error').": ".$filename."<br />\n"; }
	}


	fclose( $fh );
	$oldumask = umask(0);
	// to avoid typecasting misery, just use some ugly hardcoded if's
	if ($Cfg['chmod']=='0777') {
		@chmod ($filename, 0777);
	} else if ($Cfg['chmod']=='0755') {
		@chmod ($filename, 0755);
	} else if ($Cfg['chmod']=='0666') {
		@chmod ($filename, 0666);
	} else if ($Cfg['chmod']=='0655') {
		@chmod ($filename, 0655);
	} else {
		@chmod ($filename, 0644);
	}
	umask($oldumask);

}




// redirect. first through header, otherwise by javascript..
function redirect($url, $rand = 0, $die=1) {
	global $Cfg, $Current_weblog;

	// to make sure the visitor gets to see the new page, we redirect with a dummy parameter..
	if ($rand==1) {
		srand ((double) microtime() * 1000000);
		$randval = rand();
		$url.="?r=".$randval;
	}

	$url= str_replace('&amp;', '&', $url);
			
	header("Location: ".$url);
	echo "<script>self.location='$url';</script>";
	if ($die==1) { die(); } else { echo "don't die"; }


}

// wrapped by a 'function_exists', since it might already be defined..
if (!function_exists('safe_string')) {
	function safe_string($str, $strict=FALSE) {
		global $i18n_use;
		if ($i18n_use) {
			return i18n_safe_string($str, $strict);
		}

		$str = strip_tags($str);

		$str = strtr (
				strtr($str,
					'©®¹¾¼ÀÁÂÃÅÇÈÉÊËÌÍÎÏÑÒÓÔÕØÙÚÛÝàáâãäåçèéêëìíîïñòóôõøùúûýÿ',
					'SZszYAAAAACEEEEIIIINOOOOOUUUYaaaaaaceeeeiiiinooooouuuyy'),
				array(
					'Þ' => 'TH', 
					'þ' => 'th', 
					'Ð' => 'DH', 
					'ð' => 'dh', 
					'ä' => 'ae', 
					'ü' => 'ue', 
					'ö' => 'oe', 
					'Ä' => 'AE', 
					'Ü' => 'UE', 
					'Ö' => 'OE', 
					'ß' => 'ss', 
					'¦' => 'OE', 
					'¶' => 'oe', 
					'Æ' => 'AE', 
					'æ' => 'ae', 
					'µ' => 'mu'
				)
			);

		$str=str_replace("&amp;", "", $str);

		if ($strict) {
			$str=str_replace(" ", "_", $str);
			$str=strtolower(ereg_replace("[^a-zA-Z0-9_]", "", $str));
		} else {
			$str=ereg_replace("[^a-zA-Z0-9 _.,-]", "", $str);
		}
		return $str;
	}
}



function strip_trailing_space($text) {
	global $db, $Cfg;

	$text=trim($text)."[[end]]";
	$end_p = preg_match("~</p>\[\[end\]\]$~mi", $text);
	// $text = preg_replace("~(&nbsp;|<br>|<br>|<P>|</P>|\n|\r| )*\[\[end\]\]$~mi", "\\2", $text);
	$text = preg_replace("~(&nbsp;|<br>|<br />|<p>|</p>|\n|\r| )*\[\[end\]\]$~mi", "", $text);
	if ($end_p) { $text.="</p>"; }

	return $text;
}


function convert_br($text) {

	debug("convert_br: ". $text);
	// first of all, convert <p> tags with an align property to a temporary tag..
	$text = preg_replace("~<p align=(.*)>(.*)</p>~mUi", "<XX align=\\1>\\2</XX>", $text);

	//convert linebreaks to <br />
	$text = nl2br($text);

	// convert <XX> back, and strip the extra <br />..
	$text = str_replace("<XX ", "<p ", $text);
	$text = str_replace("</XX>", "</p>", $text);
	$text = str_replace("</p><br />", "</p>", $text);
	$text = str_replace("<br />\n<p ", "<p ", $text);
	$text = str_replace("<br />\r\n<p ", "<p ", $text);

	return $text;

}


// Returns a string, based on whether we're generating output for
// strict XHTML or plain old HTML
function set_target($forxhtml, $forhtml) {
	global $Weblogs, $Current_weblog;

	// xhtml workaround
	if ($Weblogs[$Current_weblog]['target_blank']==2) {
		return $forxhtml;
	} else {
		// for silly people that have <base target="_blank"> set..
		return $forhtml;
	}

}


function convert_linebreaks($text) {

	/*
	// first of all, convert <p> tags with an align property to a temporary tag..
	$text = preg_replace("~<p align=(.*)>(.*)</p>~mUi", "<XX align=\\1>\\2</XX>", $text);

	// convert <br />'s etc to linebreaks
	$repl_from = array("\r", "\n", "<br>", "<br />", "<BR>", "<BR />", "<p>", "<P>", "</p>", "</P>");
	$repl_to = array("", "", "\n", "\n", "\n", "\n", "", "", "\n\n", "\n\n");

	$text = str_replace($repl_from, $repl_to, $text);

	// convert <XX> back, and strip the extra <br />..
	$text = str_replace("<XX ", "\n<P ", $text);
	$text = str_replace("</XX>", "</P>\n", $text);
	*/

	return strip_trailing_space(nl2br($text));

}



// adapted from an article by Allan Kent on phpbuilder.com
// this function takes the current system time and date, and offsets
// it to get the time and date we want to output to our users.
function get_current_date() {
	global $Cfg;

	//debug("GCD: ".$Cfg['timeoffset_unit']." x ".$Cfg['timeoffset']);

	$date_time_array  = getdate();

	$hours =  $date_time_array["hours"];
	$minutes =  $date_time_array["minutes"];
	$seconds =  $date_time_array["seconds"];
	$month =  $date_time_array["mon"];
	$day =  $date_time_array["mday"];
	$year =  $date_time_array["year"];

	$timestamp =  mktime($hours ,$minutes, $seconds,$month ,$day, $year);

	switch ($Cfg['timeoffset_unit']) {

		case "y": $year +=$Cfg['timeoffset']; break;
		case "m": $month +=$Cfg['timeoffset']; break;
		case "d": $day+=$Cfg['timeoffset']; break;
		case "h": $hours+=$Cfg['timeoffset']; break;
		case "i": $minutes+=$Cfg['timeoffset']; break;

	}
	$timestamp =  mktime($hours ,$minutes, $seconds,$month ,$day, $year);
	return $timestamp;

}

function open_ping_window($weblogname, $filename) {
	global $Cfg, $Weblogs, $Pivot_Vars;

	if (isset($Cfg['ping']) && ($Cfg['ping'] == 1) ) {
		$pingurl= "includes/ping.php?session=". $Pivot_Vars['session']."&title=".$weblogname."&file=".$filename;
		$rand=rand(10000,99999);
		printf("<div id='note'></div>");
		printf("<script>setTimeout(\"open_win('%s','ping_%s', 'location=no, status=yes, scrollbars=yes, resizable=yes, width=300, height=200')\",  1000);\n</script>\n", $pingurl, $rand);
	}

}



function get_entry_from_post() {
	global $Users, $Pivot_Vars, $useWysiwyg, $db, $Cfg;


	if ( (isset($Pivot_Vars['f_code'])) && ($Pivot_Vars['f_code']!=""))  {
		$ping=FALSE;
		$entry = $db->read_entry($Pivot_Vars['f_code']);
		$entry['date'] = fix_date($Pivot_Vars['f_createdate_1'], $Pivot_Vars['f_createdate_2']);		
	} else {
		$ping=TRUE;
		$entry['code'] = ">";
		
		$entry['date'] = date("Y-m-d-H-i", get_current_date());
	}


	if (isset($Pivot_Vars['f_introduction_text'])) {
		$entry['introduction'] = strip_trailing_space(stripslashes($Pivot_Vars['f_introduction_text']));
		$entry['body'] = strip_trailing_space(stripslashes($Pivot_Vars['f_body_text']));

	} else {
		$entry['introduction'] = strip_trailing_space(stripslashes($Pivot_Vars['f_introduction']));
		$entry['body'] = strip_trailing_space(stripslashes($Pivot_Vars['f_body']));
	}

	
	$entry['introduction'] = tidy_html($entry['introduction'], TRUE);
	$entry['body'] = tidy_html($entry['body'], TRUE);

	$entry['category'] = @$Pivot_Vars['f_catmult'];
	$entry['publish_date'] = fix_date($Pivot_Vars['f_publishdate_1'], $Pivot_Vars['f_publishdate_2']);
	$entry['edit_date'] = date("Y-m-d-H-i");
	$entry['title'] = strip_trailing_space(stripslashes($Pivot_Vars['f_title']));
	$entry['subtitle'] = strip_trailing_space(stripslashes($Pivot_Vars['f_subtitle']));
	$entry['user'] = $Pivot_Vars['f_user'];
	$entry['convert_lb'] = $Pivot_Vars['convert_lb'];
	$entry['status'] =  $Pivot_Vars['f_status'];
	$entry['allow_comments'] =  $Pivot_Vars['f_allowcomments'];
	$entry['keywords'] =  strip_tags($Pivot_Vars['f_keywords']);
	$entry['vialink'] =  strip_tags($Pivot_Vars['f_vialink']);
	$entry['viatitle'] =  strip_tags($Pivot_Vars['f_viatitle']);
	

	return $entry;

}



// Function used by the 'backup config' option:
// adds a file to the zip.
function addfiletozip($filename) {
	global $zipfile;
	
	$data = implode("\n", file($filename));
	$zipfile -> addFile($data, $filename);
}



// piv_error prints a fancy error message.. for dweebs that try to do
// stuff they shouldn't
function piv_error($name, $message, $endpage=0){

	echo "<h2>$name</h2>\n\n";
	echo "<div class='extrapadding2' style='width:400px;'>$message</div>";
	if($endpage==1){
		PageFooter();
	}
	
	//debug_trace();
	
	exit();
}


// attempts to fetch a webpage, and returns the <title>..
function get_webpage_title($url, $timeout, $size) {

	$url_stuff = parse_url($url);

	if ( (!isset($url_stuff['path'])) || ($url_stuff['path']=="") ) {
		$url_stuff['path'] = "/";
	}

	if ( ($url_stuff['scheme'] != "http") || ($url_stuff['host'] == "") ) {
		return "";
	} else {
		$fp = @fsockopen($url_stuff['host'], 80, $errno, $errstr, $timeout);
		if(!$fp) {
			return "";
		} else {
			fputs($fp, "GET " . $url_stuff['path'] . " HTTP/1.0\r\nHost: " .
			$url_stuff['host'] . "\r\n\r\n");
			$start = time();
			stream_set_timeout($fp, $timeout);
			$res = fread($fp, $size);
			fclose($fp);
			if ( (preg_match ("~<title>(.*)</title>~is", $res, $match)) && (strlen($match[1])>3) ) {

				$title=str_replace("|", "-", $match[1]);
				$title=strip_tags(str_replace("\n", "", $title));

				$number = substr($title,0,3);

				if (is_numeric($number) && ($number>=100) && ($number<=600)) {
					return "";
				} else {
					return $title;
				}
			}
		}
		return "";
	}
}



// returns the browser, extracted from the $agent string..
function getbrowser($agent) {


	if (file_exists('includes/phpsniff/phpSniff.class.php')) {
		include_once('includes/phpsniff/phpSniff.class.php');
	} else {
		include_once('includes/phpsniff/phpsniff.class.php');
	}

	$client = new phpSniff($agent,0);
	$client->init();

	if ($client->property('long_name')!="") {
		return ucfirst($client->property('long_name'))." ".$client->property('version')." (".$client->property('platform').")";
	} else {
		return trimtext($agent,20);
	}

}

// convert relative URL's to absolute URL's
function RelativeToAbsoluteURLS($data) {

	$host = "http://".$_SERVER['HTTP_HOST'];

	$data = preg_replace("/a href=(['\"])(?!http)/mUi", "a href=\\1$host\\2", $data);
	$data = preg_replace("/img src=(['\"])(?!http)/mUi", "img src=\\1$host\\2", $data);

	//$data = preg_replace("/a/mUi", "EE", $data);

	return ($data);
}


function add_log($msg) {
	global $Cfg, $Pivot_Vars;

	if ($Pivot_Vars['user']=="") {
		$user= "(visitor)";
	} else {
		$user= $Pivot_Vars['user'];
	}


	if (isset($Cfg['log'])  && ($Cfg['log']==1) ) {
		$filename = "log_".date("Ymd").".txt";
		$date = date("[ Ymd - H:i:s (". timetaken('int') .") ] ");
		$msg = $date . $Pivot_Vars['REMOTE_ADDR'] ." - ". $Pivot_Vars['user'] ." - ". $msg;

		if ($fp=@fopen("db/$filename", "a")) {
			fwrite($fp, $msg."\n");
			fclose ($fp);
		}

	}

}

function inweblogcheck() {
	global $pivot_path, $pivot_url, $log_url, $Paths;
	
	// store the current dir, and change to the pivot dir.
	$olddir = getcwd();
	chdir($pivot_path);
	
	// start input buffering..
	ob_start(); 

 	if (timedpublishcheck()) {
 		if (!headers_sent()) {
 			header("location: ". $_SERVER['REQUEST_URI']);
 		} else {
 			// else the next visitor will just see the updated page..
 		}
 	}

 	// stop buffering and flush the output..
 	ob_end_flush(); 
	
 	// change back to the original dir.
	chdir($olddir);

	
}

function timedpublishcheck() {
	global $serialize_cache, $Cfg, $Paths;
		
 	$temp_db = new db();

 	$entries = $temp_db->getlist(-10,0,"", "", FALSE, "date");
 	
 	$date = date("Y-m-d-H-i", get_current_date());
 	
	$regen = FALSE;
			
	foreach ($entries as $entry) {

		
		if (($entry['status'] == "timed" ) ) {
					
					
			$entry = $temp_db->read_entry($entry['code']);

			if ($entry['publish_date'] <= $date) {
				
				debug("toggle");
				
				$entry['date'] = $entry['publish_date'];
				$entry['status'] = "publish";
				
				$temp_db->set_entry($entry);
				$temp_db->save_entry(TRUE);
		
				$regen = TRUE;		
				
				// 2004/10/17 =*=*= JM
				// can only do this if we know Cfg['search_index'] status...
				if( isset( $Cfg )) {
					// if the global index as they are made var is set - can continue
					// the rest is copied from 'entrysubmit_screen()' in pv_core.php
					if(( '1'==$Cfg['search_index'] )&&( can_search_cats( cfg_cat_nosearchindex(),$entry['category'] ))) {
						include_once( 'modules/module_search.php' );
						update_index( $temp_db->entry );
						debug('update search index: '.$temp_db->entry['code']);
					}
				}				
			}	
		}
	} 
	
	if ($regen) {
		debug("yes!");
		serialize_uncache("ALL");
		buildfrontpage_function();
		return TRUE;
	} else {
		return FALSE;
	}
		
}



/**
 * add_hook adds a hook into pivot functionality. It will look for the 
 * matching file in extensions/hooks/, and include_once it, it it is present.
 * the first time it is included, the _init function will be called.
 *
 * @param string hook_name
 * @param string hook_type
 *
 */
function add_hook($hook_name="", $hook_type="pre") {
	global $Paths;
	
	if ($hook_name=="") {
		return;
	} 
	
	$hook = $hook_type."_".$hook_name;
	
	if (file_exists($Paths['extensions_path']."hooks/".$hook.".php")) {
		
		if(!function_exists($hook."_init")) {
			include_once($Paths['extensions_path']."hooks/".$hook.".php");
			$init_function = $hook."_init";
			if(function_exists($init_function)) {
				$init_function();
			}
		}
	} else {
		debug("no ".$Paths['extensions_path']."hooks/".$hook.".php");
	}
		
	return;
	
}


/**
 * execute_hook runs the code in the main function in the hook. It will check
 * if the matching finction is present, and then execute it.
 *
 *
 * @param string hook_name
 * @param string hook_type
 * @param variable params
 *
 */
function execute_hook($hook_name="", $hook_type="pre", &$param1, &$param2, &$param3 ) {
	global $Paths;
	
	if ($hook_name=="") {
		return;
	} 
	
	$hook = $hook_type."_".$hook_name;
	
	if(function_exists($hook)) {
		debug("exec_hook: $hook");
		$hook($param1, $param2, $param3);
		return true;
	} else {
		debug("hook not present: $hook");
		return false;
	}
		
	
}






/**
 * Replace str_word_count()
 *
 * @category    PHP
 * @package     PHP_Compat
 * @link        http://php.net/function.str_word_count
 * @author      Aidan Lister <aidan@php.net>
 * @version     $Revision: 1.2 $
 * @since       PHP 4.3.0
 * @require     PHP 4.0.1 (trigger_error)
 */
if (!function_exists('str_word_count'))
{
    function str_word_count($string, $format = null)
    {
        if ($format != 1 && $format != 2 && $format !== null) {
            trigger_error("str_word_count() The specified format parameter, '$format' is invalid", E_USER_WARNING);
            return false;
        }

        $word_string = preg_replace('/[0-9]+/', '', $string);
        $word_array  = preg_split('/[^A-Za-z0-9_\']+/', $word_string, -1, PREG_SPLIT_NO_EMPTY);

        switch ($format) {
            case null:
                return count($word_array);
                break;

            case 1:
                return $word_array;
                break;

            case 2:
                $lastmatch = 0;
                $word_assoc = array();
                foreach ($word_array as $word) {
                    $word_assoc[$lastmatch = strpos($string, $word, $lastmatch)] = $word;
                }
                return $word_assoc;
                break;
        }
    }
}



if (!defined('CASE_LOWER')) {
    define('CASE_LOWER', 0);
}

if (!defined('CASE_UPPER')) {
    define('CASE_UPPER', 1);
}


/**
 * Replace array_change_key_case()
 *
 * @category    PHP
 * @package     PHP_Compat
 * @link        http://php.net/function.array_change_key_case
 * @author      Stephan Schmidt <schst@php.net>
 * @author      Aidan Lister <aidan@php.net>
 * @version     $Revision: 1.2 $
 * @since       PHP 4.2.0
 * @require     PHP 4.0.1 (trigger_error)
 */
if (!function_exists('array_change_key_case'))
{
    function array_change_key_case($input, $case = CASE_LOWER)
    {
        if (!is_array($input)) {
            trigger_error('array_change_key_case(): The argument should be an array', E_USER_WARNING);
            return false;
        }

        $output   = array ();
        $keys     = array_keys($input);
        $casefunc = ($case == CASE_LOWER) ? 'strtolower' : 'strtoupper';

        foreach ($keys as $key) {
            $output[$casefunc($key)] = $input[$key];
        }

        return $output;
    }
}


?>
