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

ob_start();

include_once('pv_core.php');
include_once('modules/module_userreg.php');

LoadDefLanguage();

PageHeader(lang('commentuser','title'), 0);
echo "<div style='padding: 20px;'>";

$input = array_merge( $_POST, $_GET);
// convert encoding to UTF-8
i18n_array_to_utf8($input, $dummy_variable);

foreach ($input as $key => $value) {
	$input[$key] = trim($value);	
}

$message = "";

if (isset($_COOKIE['piv_reguser'])) {
	list($c_user, $c_hash) = explode("|", $_COOKIE['piv_reguser']);
	
	if(check_user_hash($c_user, $c_hash)) {
		$user = load_user($c_user);
		 if (!isset($input['func'])) { 
		 	$input['func'] = "options";
		 }
	//} else {
	//	echo "No";
	}

} 


switch ($input['func'] === 0 ? '' : $input['func']) {
   case 'login':
       do_login();
       break;
   case 'options':
       do_show_options();
       break;       
   case 'logout':
       do_logout();
       break;
   case 'reg_user':
       do_reg_user();
       break;       
   case 'validate_user':
       do_validate_user();
       break;       
   case 'send_pass':
       do_send_pass();
       break;     
   case 'show_pass':
       do_show_pass();
       break;       
   case 'edit_prefs':
       do_edit_prefs();
       break;  
   case 'subm_reg':
       do_subm_reg();
       break;  
   case 'subm_prefs':
       do_subm_prefs();
       break;         
    case 'send_pass':
       do_send_pass();
       break;    
    case 'verify':
       do_verify();
       break;         
   default:
       do_show_login();
       break;

	
}

echo "</div></body></html>";

ob_end_flush();


/* functions */

function do_show_login() {
	global $message, $input;
	
	$form = <<<EOM
	<h1>%header%</h1>
	<p><b>%message%</b></p>
	<form name="form1" id="form1" method="post" action="user.php?func=login"><table border="0" cellspacing="0" cellpadding="3">
	  <tr>
	    <td>%formname%:</td>
	    <td><input type="text" name="name" /></td>
	  </tr>
	  <tr>
	    <td>%formpass%:</td>
	    <td><input type="password" name="pass" /></td>
	  </tr>
	  <tr>
	    <td>&nbsp;</td>
	    <td><input type="submit" name="Submit" value="%login%" /></td>
	  </tr>
	  <tr>
           <td colspan="2"><p><br />&raquo; <a href="user.php?func=show_pass">%pass_forgot%</a></p>
             <p>&raquo; <a href="user.php?func=reg_user">%register_new%</a></p></td>
	    </tr>
	</table>
	</form>

EOM;

	$input['message'] = $message;
	
	foreach ($input as $key => $val) {
		$form = str_replace("%".$key."%", $val, $form);
	}

        $trans = array();
        $trans['header'] = lang('commentuser','header');
        $trans['formname'] = lang('userinfo','username');
        $trans['formpass'] = lang('userinfo','pass1');
        $trans['login'] = lang('commentuser','login');
        $trans['pass_forgot'] = lang('commentuser','pass_forgot');
        $trans['register_new'] = lang('commentuser','register_new');
	foreach ($trans as $key => $val) {
		$form = str_replace("%".$key."%", $val, $form);
	}
		
	echo $form;
	
	print_back();
}


function do_show_options() {
	global $message, $input, $user, $Weblogs;
	
	$form = <<<EOM
	<h1>%loggedinas% %name%</h1>
	<p>%message%</p>
	<p>&raquo; <a href="user.php?func=logout">%logout%</a></p>
	<p>&raquo; <a href="user.php?func=edit_prefs">%pref_edit%</a></p>
EOM;

	$input['message'] = $message;
	
	foreach ($input as $key => $val) {
		$form = str_replace("%".$key."%", $val, $form);
	}
	
	if (is_array($user)) {
		foreach ($user as $key => $val) {
			$form = str_replace("%".$key."%", $val, $form);
		}
	}
        $trans = array();
        $trans['loggedinas'] = lang('commentuser','loggedinas');
        $trans['logout'] = lang('commentuser','logout');
        $trans['pref_edit'] = lang('commentuser','pref_edit');
	foreach ($trans as $key => $val) {
		$form = str_replace("%".$key."%", $val, $form);
	}
		
	echo $form;

	print_back();
	
}



function do_login() {
	global $message, $input;
	
	if ($user=load_user($input['name'])) {
		
		$verify_code = md5($user['pass']."email");
		
		//echo "code: $verify_code ?= ".$input['code']."<br />";
		
		if ( ($user['pass'] == $input['pass']) && ($user['verified']==1) ) {
			//error_reporting(E_ALL);
			setcookie("piv_reguser", stripslashes($user['name']."|".md5($user['pass'])), time()+2592000, "/");
			$message = lang('commentuser','loggedin');
			do_show_options();
		} else {
			$message = lang('commentuser','pass_invalid');
			do_show_login();
		}
		
		
	} else {
		$message = lang('commentuser','nouser');
		do_show_login();
	}	
	
	
}





function do_reg_user() {
	global $message, $input;
	
	$form= <<<EOM
	
	<h1>%register%: </h1>
	<p><b>%message%</b></p>
	<form name="form1" id="form1" method="post" action="user.php?func=subm_reg">
	<p>%register_info%</p>
 <table border="0" cellspacing="0" cellpadding="3">
    <tr>
      <td>%formname%:</td>
      <td colspan="4"><input name="name" type="text" id="name" value="%name%" /></td>
    </tr>
    <tr>
      <td>%formemail%:</td>
      <td colspan="4"><input name="email" type="text" id="email" value="%email%" /></td>
    </tr>
    <tr>
      <td>%formurl%:</td>
      <td colspan="4"><input name="url" type="text" id="url" value="%url%" /></td>
    </tr>    
    <tr>
      <td>%formpass1%:</td>
      <td colspan="4"><input name="pass" type="password" id="pass" /></td>
    </tr>
    <tr>
      <td valign="top">%formpass2%:</td>
      <td colspan="4"><input name="pass2" type="password" id="pass2" /> 
      <br />
      %pass_note%
      </td>
    </tr>
    <tr>
      <td colspan="5">
  
      
      
      <strong>%options%:</strong></td>
    </tr>
    <tr>
      <td>%show_email%: </td>
      <td><input name="show_address" type="radio" value="1" %radio1a% /> %Yes%</td>
 
      <td><input name="show_address" type="radio" value="0" %radio1b% /> %No%</td>
           <td></td><td></td>
    </tr>
    <tr>
      <td>%notify%: </td>
      <td><input name="notify_entries" type="radio" value="1" %radio2a% /> %Yes%</td>
      
      <td><input name="notify_entries" type="radio" value="0" %radio2b% /> %No%</td>
      <td></td><td></td>
    </tr>
    <tr>
      <td>%def_notify%:</td>
      <td><input name="notify_default" type="radio" value="1" %radio3a% /> %Yes%</td>
      
      <td><input name="notify_default" type="radio" value="0" %radio3b% /> %No%</td>
      <td></td><td></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="4"><br />
        <input type="submit" name="Submit" value="%register%" /></td></tr>
    <tr>
      <td colspan="5"><p><br />
        &laquo; <a href="user.php">%back_login%</a></p>
      </td>
    </tr>
  </table>
	</form> 
EOM;


	$input['message'] = $message;
	
	if (!isset($input['name'])) { $input['name'] = ""; }
	if (!isset($input['email'])) { $input['email'] = ""; }
	if (!isset($input['url'])) { $input['url'] = ""; }
	
	if ($input['show_address']==1) {
		$input['radio1a'] = "checked='checked' ";
		$input['radio1b'] = "";
	} else {
		$input['radio1a'] = "";
		$input['radio1b'] = "checked='checked' ";		
	}
			
	if ($input['notify_entries']==1) {
		$input['radio2a'] = "checked='checked' ";
		$input['radio2b'] = "";
	} else {
		$input['radio2a'] = "";
		$input['radio2b'] = "checked='checked' ";
	}
		
	if ($input['notify_default']==1) {
		$input['radio3a'] = "checked='checked' ";
		$input['radio3b'] = "";
	} else {
		$input['radio3a'] = "";
		$input['radio3b'] = "checked='checked' ";
	}
	
	foreach ($input as $key => $val) {
		$form = str_replace("%".$key."%", $val, $form);
	}

        $trans = array();
	$trans['register'] = lang('commentuser','register');
	$trans['register_info'] = lang('commentuser','register_info');
        $trans['Yes'] = lang('general','yes');
	$trans['No'] = lang('general','no');
	$trans['formname'] = lang('userinfo','username');
	$trans['formemail'] = lang('general','email');
	$trans['formurl'] = lang('general','url');
	$trans['formpass1'] = lang('userinfo','pass1');
	$trans['formpass2'] = lang('userinfo','pass2');
	$trans['pass_note'] = wordwrap(lang('commentuser','pass_note'), 50, " <br />");;
	$trans['options'] = lang('commentuser','options');
	$trans['back_login'] = lang('commentuser','back_login');
	$trans['show_email'] = lang('commentuser','show_email');
	$trans['notify'] = lang('commentuser','notify');
	$trans['def_notify'] = lang('commentuser','def_notify');
	$trans['back_login'] = lang('commentuser','back_login');
	foreach ($trans as $key => $val) {
		$form = str_replace("%".$key."%", $val, $form);
	}
		
	echo $form;
}





function do_edit_prefs() {
	global $message, $input, $user;
	
	$form= <<<EOM
	
	<h1>%pref_edit%: </h1>
	<p><b>%message%</b></p>
	<form name="form1" id="form1" method="post" action="user.php?func=subm_prefs">
	  <p>%change_info%. </p>
 <table border="0" cellspacing="0" cellpadding="3">
    <tr>
      <td>%formname%:</td>
      <td colspan="4"><input name="name" type="text" id="name" value="%name%" readonly="readonly" /></td>
    </tr>
    <tr>
      <td>%formemail%:</td>
      <td colspan="4"><input name="email" type="text" id="email" value="%email%" /></td>
    </tr>
 	<tr>
      <td>%formurl%:</td>
      <td colspan="4"><input name="url" type="text" id="url" value="%url%" /></td>
    </tr>   
    <tr>
      <td>%formpass1%:</td>
      <td colspan="4"><input name="ch_pass" type="password" id="ch_pass" value="" /></td>
    </tr>
     
    <tr>
      <td>%formpass2%:</td>
      <td colspan="4"><input name="ch_pass2" type="password" id="ch_pass2" value="" /></td>
    </tr>
    <tr>
      <td colspan="5"><strong>%options%:</strong></td>
    </tr>
    <tr>
      <td>%show_email%: </td>
      <td><input name="show_address" type="radio" value="1" %radio1a% /></td>
      <td>%Yes%</td>
      <td><input name="show_address" type="radio" value="0" %radio1b% /></td>
      <td> %No% </td>
    </tr>
    <tr>
      <td>%notify%</td>
      <td><input name="notify_entries" type="radio" value="1" %radio2a% /></td>
      <td>%Yes%</td>
      <td><input name="notify_entries" type="radio" value="0" %radio2b% /></td>
      <td>%No%</td>
    </tr>
    <tr>
      <td>%def_notify%:</td>
      <td><input name="notify_default" type="radio" value="1" %radio3a% /></td>
      <td>%Yes%</td>
      <td><input name="notify_default" type="radio" value="0" %radio3b% /></td>
      <td>%No%</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="4"><br />
        <input type="submit" name="Submit" value="%pref_change%" /></td></tr>
    <tr>
      <td colspan="5"><p><br />
        &laquo; <a href="user.php">%back_login%</a></p>
      </td>
    </tr>
  </table>
	</form> 
EOM;


	$user['message'] = $message;
	$user['pass'] = "";
	
	if ($user['show_address']==1) {
		$user['radio1a'] = "checked='checked' ";
		$user['radio1b'] = "";
	} else {
		$user['radio1a'] = "";
		$user['radio1b'] = "checked='checked' ";		
	}
			
	if ($user['notify_entries']==1) {
		$user['radio2a'] = "checked='checked' ";
		$user['radio2b'] = "";
	} else {
		$user['radio2a'] = "";
		$user['radio2b'] = "checked='checked' ";
	}
		
	if ($user['notify_default']==1) {
		$user['radio3a'] = "checked='checked' ";
		$user['radio3b'] = "";
	} else {
		$user['radio3a'] = "";
		$user['radio3b'] = "checked='checked' ";
	}
	
	foreach ($user as $key => $val) {
		$form = str_replace("%".$key."%", $val, $form);
	}

        $trans = array();
	$trans['Yes'] = lang('general','yes');
	$trans['No'] = lang('general','no');
	$trans['change_info'] = lang('commentuser','change_info');
	$trans['formname'] = lang('userinfo','username');
	$trans['formemail'] = lang('general','email');
	$trans['formurl'] = lang('general','url');
	$trans['formpass1'] = lang('userinfo','pass1');
	$trans['formpass2'] = lang('userinfo','pass2');
	$trans['options'] = lang('commentuser','options');
	$trans['show_email'] = lang('commentuser','show_email');
	$trans['notify'] = lang('commentuser','notify');
	$trans['def_notify'] = lang('commentuser','def_notify');
	$trans['pref_edit'] = lang('commentuser','pref_edit');
	$trans['pref_change'] = lang('commentuser','pref_change');
	$trans['back_login'] = lang('commentuser','back_login');
	foreach ($trans as $key => $val) {
		$form = str_replace("%".$key."%", $val, $form);
	}
	
	echo $form;
}




function do_show_pass() {
	global $message;
	
	$form = <<<EOM
	<h1>%pass_send%</h1>
	<p>%message%</p>
	<form name="form1" id="form1" method="post" action="user.php?func=send_pass">
	  <p>%pass_send_desc%</p>
	  <table border="0" cellspacing="0" cellpadding="3">
	    <tr>
	      <td>%name%:</td>
	      <td><input name="name" type="text" id="name" /></td>
	    </tr>
	    <tr>
	      <td>%email%:</td>
	      <td><input name="email" type="text" id="email" /></td>
	    </tr>
	    <tr>
	      <td>&nbsp;</td>
	      <td><input type="submit" name="Submit" value="%pass_send%" /></td>
	    </tr>
	    <tr>
	      <td colspan="2"><p><br />&laquo; <a href="user.php">%back_login%</a></p>
	          <p>&raquo; <a href="user.php?func=reg_user">%register_new%</a></p></td>
	    </tr>
	  </table>
	</form>
	
EOM;
	

	$input['message'] = $message;
	
	foreach ($input as $key => $val) {
		$form = str_replace("%".$key."%", $val, $form);
	}
	
        $trans = array();	
	$trans['name'] = lang('userinfo','username');
	$trans['email'] = lang('general','email');
	$trans['pass_send'] = lang('commentuser','pass_send');
	$trans['pass_send_desc'] = lang('commentuser','pass_send_desc');
	$trans['back_login'] = lang('commentuser','back_login');
	$trans['register_new'] = lang('commentuser','register_new');
	foreach ($trans as $key => $val) {
		$form = str_replace("%".$key."%", $val, $form);
	}

	echo $form;
}



function do_subm_reg() {

	global $input, $message;
	
	if (is_user($input['name'])) {	
		$message .= lang('commentuser','user_exists');
		do_reg_user();
	} else if (strlen($input['pass'])<4) {
		$message .= lang('userinfo','pass_too_short');
		do_reg_user();		
	} else if ($input['pass'] != $input['pass2']) {
		$message .= lang('userinfo','pass_dont_match');
		do_reg_user();		
	} else if (!isemail($input['email'])) {
		$message .= lang('commentuser','email_note');
		do_reg_user();		
	} else {
	
		
		$user = array(
		 'name' => $input['name'],
		 'email' => $input['email'],
		 'url' => $input['url'],
		 'pass' => $input['pass'],
		 'show_address' => $input['show_address'],
		 'notify_entries' => $input['notify_entries'],
		 'notify_default' => $input['notify_default'],
		 'verified' => 0,
		 );
		
		reg_user($user);
	}
}



function do_subm_prefs() {
	global $input, $message, $user;

		
	if ((strlen($input['pass'])>0) && (strlen($input['pass'])<4)) {
		$message .= lang('userinfo','pass_too_short');
		do_edit_prefs();		
	} else if ($input['ch_pass'] != $input['ch_pass2']) {
		$message .= lang('userinfo','pass_dont_match');
		do_edit_prefs();		
	} else {
	
		$message = lang('commentuser','stored');
		
		if ( (strlen($input['ch_pass'])>3) && ($input['ch_pass'] == $input['ch_pass2']) ) {
			$user['pass'] = $input['ch_pass'];	
			setcookie("piv_reguser", stripslashes($user['name']."|".md5($user['pass'])), time()+2592000, "/");
		}
		
		$user['email'] = $input['email'];
		$user['url'] = $input['url'];
		$user['show_address'] = $input['show_address'];
		$user['notify_entries']  = $input['notify_entries'];
		$user['notify_default']  = $input['notify_default'];
		
		unset($user['message']);
		save_user($user);
		
		do_show_options();
		 
	}
}


function do_verify() {
	global $input, $message;

	if ($user=load_user($input['name'])) {
		
		$verify_code = md5($user['pass']."email");
		
		//echo "code: $verify_code ?= ".$input['code']."<br />";
		
		if ($verify_code == $input['code']) {
			
			$user['verified'] = 1;
			save_user($user);
			
			$message = lang('commentuser','verified');
			do_show_login();
		} else {
			echo lang('commentuser','not_verfied');
		}
		
	} else {
		echo lang('commentuser','oops');
	}
	
}


function do_send_pass() {
	global $input, $message;
	
	if ($user=load_user($input['name'])) {
			
		if ( ($user['name'] == $input['name']) && ($user['email'] == $input['email']) )  {
			
			$message = lang('commentuser','pass_sent');
			mail_pass($user);
			do_show_login();
		} else {
                        $message = lang('commentuser','user_pass_nomatch');
			do_show_login();
		}
		
	} else {
		$message = lang('commentuser','user_pass_nomatch');
		do_show_login();
	}
	
}





function do_logout() {
	global $message, $user;
	
	setcookie("piv_reguser", "", time()-1000, "/");
	unset ($user);
	$message = lang('commentuser','loggedout');
	do_show_login();
}


function print_back() {
	global $Weblogs;
	
	echo "<br />\n";
	
	foreach ($Weblogs as $weblog) {
		
		printf("<p>&laquo; ".lang('commentuser','back')." <a href=\"%s%s\">%s</a></p>", $weblog['front_path'], $weblog['front_filename'], $weblog['name'] );
		
	}
}


?>