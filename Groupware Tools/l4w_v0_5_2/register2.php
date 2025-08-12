<?php

   	/*=====================================================================
	// $Id: register2.php,v 1.6 2005/01/26 08:26:50 carsten Exp $
    // copyright evandor media Gmbh 2003
	//=====================================================================*/

    @session_name (SESSION_NAME);
	@session_start();
	
	// Simulate user_id 2
	if (isset ($_SESSION['user_id'])) 
	   $session_user_id = $_SESSION['user_id'];
	$_SESSION['user_id'] = 2;
	
    $config_file = "config/config.inc.php";
    include ($config_file);
	include ("connect_database.php");
    include ("inc/functions.inc.php");

    require_once("modules/common/leads4web_model.php");
    require_once("modules/users/models/users_mdl.php");
    
    $login_wanted = $_REQUEST['login_wanted'];
    $pass1        = $_REQUEST['pass1'];
    $pass2        = $_REQUEST['pass2'];
    $vorname      = $_REQUEST['vorname'];
    $nachname     = $_REQUEST['nachname'];
    $email        = $_REQUEST['email'];
    
    // === PHPGACL =====================================================
    require_once ('extern/phpgacl/gacl.class.php');
    require_once ('extern/phpgacl/gacl_api.class.php');
    require_once ('inc/acl.inc.php');

    $gacl_api = new gacl_api($gacl_options);


    function return_with_error ($error) {
      global $login_wanted, $vorname, $nachname, $email;

      ?>
          <script language='javascript'>
            document.location.href='register.php?login_wanted=<?=$login_wanted?>&email=<?=$email?>&vorname=<?=$vorname?>&nachname=<?=$nachname?>&msg=<?=$error?>';
          </script>
      <?php
      die ("");
    }

    $smarty      = null;
    $authClass   = null;
    $model       = new users_model ($smarty, $authClass);

    $error = "";

    $model->error_msg = '';
    $params = array ("users_group" => 16, // 16 = demo CEO group
                             "firstname"   => $vorname, 
                             "lastname"    => $nachname,
                             "email"       => $email,
                             "login"       => $login_wanted,
                             "pass1"       => $pass1,
                             "pass2"       => $pass2);
    $result        = $model->add_user($params);
    
    if ($result == "failure") {
        return_with_error ($model->error_msg);    
    }    
    
    // Add demo group
    $params = array ("use_user" => $model->inserted_user_id, 
                             "referrer"   => '', 
                             "command"    => 'update_users_groups',
                             "member_16"   => 'on',
                             "member_15"   => 'on',
                             "default_group" => 16);
            
    $result        = $model->update_users_groups($params);
    
    
    // Mails
    if ($email <> "") {
    	$msg = "Welcome to leads4web/4\n\n";
    	$msg .= "You can login to lead4web at and with:\n\n";
    	$msg .= "URL:      http://www.leads4web.de\n";
    	$msg .= "Login: 	  ".$login_wanted."\n";
    	$msg .= "Password: ".$pass1."\n\n";
    	$msg .= "If you have questions or suggestions about leads4web \n";
    	$msg .= "please have a look at this page\n\n";
    	$msg .= "http://217.172.179.216/phpBB2/\n\n";
        $msg .= "or write to\n\n";
        $msg .= "graef@evandor.de\n\n";
        $msg .= "If you have special wishes about leads4web's functionality\n";
        $msg .= "or extensions, just email us. We can adapt leads4web just\n";
        $msg .= "for you or your company. If you don't have access to a server\n";
        $msg .= "of your own, we can install an installation just for your\n";
        $msg .= "company.\n\n";
        $msg .= "Have fun,\n";
		$msg .= "Your evandor team\n";
		mail ($email, "Registration for leads4web", $msg);
		mail ("graef@evandor.de", "Registration for leads4web4", $msg);
	}

	// return to old user id, if set
	if (isset ($session_user_id)) 
	   $_SESSION['user_id'] = $session_user_id;

?>
  <html>
<head>
</head>
<body bgcolor='#EEEEEE'>
<br><br><br>
<center>   <font face=verdana size=2>
Thanks for registering.
<br><br>
You can now login <a href='main.php?login_given=<?=$login_wanted?>'>here</a> at your accout.
<br><br>
</font>
</body>