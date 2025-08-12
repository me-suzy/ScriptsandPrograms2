<?php

    /*=====================================================================
	// $Id: check_login.php,v 1.22 2005/08/01 14:55:12 carsten Exp $
    // copyright evandor media Gmbh 2003
	//=====================================================================*/


	include ("config/config.inc.php");
	include ("connect_database.php");
	include ("inc/functions.inc.php");

	// =====================================================================
	// Error Message: Problems loging in
	function handle_login_problem() {
		global $login, $logger;

		$logger->log ("Failed login ".$_REQUEST['login'],4);
		//@session_destroy();
		?>
			<script language=javascript type=text/javascript>
				document.location.href='main.php?msg=login_fehler';
			</script>
		<?php
		die ("</body></html>");
	}

	// =====================================================================
	// Error Message: already logged in

	function handle_logged_in_problem() {
		global $group, $login, $easy;

		$logger->log ("Already logged in: ".$_SESSION['login'],4);
		//@session_destroy();
		?>
			<script language=javascript type=text/javascript>
				document.location.href='main.php?msg=logged_in_fehler';
			</script>
		<?php
		die ("</body></html>");
	}

	// ==============================================================
	// Error Message: No Group assigned
	function handle_no_group_error() {
		global $easy;
		$logger->log ("no group for user with login ".$_REQUEST['login'],3);
		//@session_destroy();
		?>
			<script language=javascript type=text/javascript>
				document.location.href='main.php?msg=not_a_groupmember';
			</script>
		<?php
		die ("</body></html>");
	}    
	
	// =====================================================================
	// Error Message: Not allowed to use mandator
	function handle_mandator_problem() {
		global $login, $easy;

		$logger->log ("User ".$_REQUEST['login']." may not use mandator ".$_REQUEST['mandator'],4);
		?>
			<script language=javascript type=text/javascript>
				document.location.href='main.php?msg=mandator_error';
			</script>
		<?php
		die ("</body></html>");
	}
    // --- get data for given login ---------------------------------
	$query = "SELECT * FROM ".TABLE_PREFIX."users WHERE login='".$_REQUEST['login']."'";
	$result = mysql_query($query);
	logDBError(__FILE__, __LINE__, mysql_error(), true);
	$login_count  = mysql_num_rows ($result);

	// --- first check: does login exist? ---------------------------
	if ($login_count == 0) {
		// unknown login
		handle_login_problem();
		exit();
	}

	// get users default group
	$myrow = mysql_fetch_array($result);
	//$_SESSION['group'] = $myrow['grp'];

	// === check password ===========================================
	// --- first of all: check password -----------------------------
	if (md5($_REQUEST['passwort']) <> $myrow['password']) {
		// wrong password given
		handle_login_problem();
		exit;
	}
	
	// --- login exists, and so does user.id, passwd is ok ----------
	// --- delete old entries from useronline table -----------------
	// --- but dont delete anything younger than 3 minutes -----------
    $old_time = time() - ($PING_TIMER + 60); 
	$res = mysql_query ("DELETE FROM ".TABLE_PREFIX."useronline 
	                     WHERE user_id='".$myrow['id']."'
						 AND timestamp<".$old_time);
	logDBError (__FILE__, __LINE__, mysql_error());

	// === The user must be member of at least one group! ===========
	$member_of_array = get_all_groups ($myrow['id']);
	if (count($member_of_array) == 0) {
	    handle_no_group_error();
	}

	// === user may only login once at a time =======================
    if (!ALLOW_GUEST_USER || $_REQUEST['login'] != 'guest') {
    	$query      = "SELECT COUNT(*) FROM ".TABLE_PREFIX."useronline 
    	               WHERE user_id='".$myrow['id']."' AND object_type=0";
    	$logins_res = mysql_query($query);
    	//die ($query);
    	$logins_row = mysql_fetch_array ($logins_res);
    	logDBError(__FILE__, __LINE__, mysql_error(), true);
    	// user can login serveral time now
    	/*if ($logins_row[0] > 0) {
    		handle_logged_in_problem();
    		exit;	    
    	} */   
    }
    
    // === user must be allowed to use mandator =====================
    if (!userMayUseMandator ($myrow['id'], $_REQUEST['mandator']))
    	handle_mandator_problem ();
    
	// === all checks passed, so start session ======================
    @session_name (SESSION_NAME);
    @session_unset();
    @session_destroy();
    @session_start();
    //@session_regenerate_id();

    // --- clear session --------------------------------------------
	$_SESSION ['passwort']          = '';
	$_SESSION ['login']             = '';
	$_SESSION ['user_id']           = '';
	$_SESSION ['language']          = '';
	$_SESSION ['mandator']          = '1'; // default mandator
	
	$_SESSION ["use_my_group"] = '';
	$_SESSION ["use_my_owner"] = '';
	$_SESSION ["use_my_state"] = '';
	
	$_SESSION ['current_views']     = array (); // array of open, i.e. locked pages

	// --- set login / password -------------------------------------
	$_SESSION ['login']	   = $_REQUEST["login"];
	$_SESSION ['passwort'] = $_REQUEST["passwort"];
	$_SESSION ['mandator'] = $_REQUEST['mandator'];
	$_SESSION ['user_id']  = $myrow['id'];
	
	setcookie ("l4wuser",     $_SESSION['login'],time()+(3600*24*30));
	setcookie ("l4wmandator", $_SESSION['mandator'],time()+(3600*24*30));

	// Sprache
	$lang_res = mysql_query ("SELECT lang FROM ".TABLE_PREFIX."user_details WHERE user_id='".$_SESSION['user_id']."'");
	logDBError(__FILE__, __LINE__, mysql_error());
	$lang_row = mysql_fetch_array ($lang_res);
	$_SESSION['language'] = $lang_row['lang'];
	if ($_SESSION['language'] == "") $_SESSION['language'] = 1;

	//Last-Login updaten
	$res = mysql_query ("UPDATE ".TABLE_PREFIX."user_details SET last_login=now() WHERE user_id='".$_SESSION['user_id']."'", $db) or die ("Error: 60".mysql_error());
	logDBError(__FILE__, __LINE__, mysql_error());
    
	$result=mysql_query("INSERT INTO ".TABLE_PREFIX."useronline VALUES ('".time()."','".$_SESSION['user_id']."','main','0')");
    array_push ($_SESSION['current_views'], array ('main', 0));

	// Login_count erhöhen
	mysql_query ("UPDATE ".TABLE_PREFIX."user_details SET login_count=login_count+1 WHERE user_id='".$_SESSION['user_id']."'");
	logDBError(__FILE__, __LINE__, mysql_error());

	// page_stats setzen:
	set_page_stats(__FILE__);

    // login history
    // !!!!update db: Object add "user"
    /*$hist_sql = "INSERT INTO history (user_id, datum, id, Objekt, bemerkung)
                 VALUES ('".$_SESSION['user_id']."', now(), 0, 'user', 'login')";
	mysql_query ($hist_sql);
	logDBError(__FILE__, __LINE__, mysql_error());
    */
                 
	?>
	<script language=javascript>
		document.location.href='<?=FRAME_PAGE?>';
	</script>