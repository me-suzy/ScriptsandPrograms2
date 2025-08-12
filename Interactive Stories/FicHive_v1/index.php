<?php
ob_start();

/* 
This is the controlling display area, it handles what gets shown and who it gets shown
to. If you're wanting to change how the output actually looks, chances are you're after
the skins folder and the templates within. 

If you're pretty sure this is where you want to be, scroll on down to find the thrilling
user checks, rollercoaster class calls and template stuffing as done by 1000 elephants.
*/

// ##### CONFIG FILE OR BUST ##### // 

	require_once( "config.inc.php" );

// ##### DATABASE CLASS ##### //

	require_once( $conf['path']."sources/db.class.php");

		$db = new db();
		$db->connect() or print($db->getError());
		$db->debug = FALSE;

// ##### WHO HERE? ##### //

	$ck = $conf['cookie']."user";

	$fa = DBPRE;

	if( !$_COOKIE[$ck] ) { 						// GUEST ACCOUNT

		$cookie = $db->select( "uid, uname, ulang, uskin, gid, gname" , 
		"users LEFT JOIN {$fa}groups ON gid=ugroup" , array('uid'=>1), 
		"GROUP BY uid, uname, ulang, uskin, gid, gname LIMIT 0,1" );

		$user = $cookie[0];

	} else {							// USER ACCOUNT

		if( $_COOKIE[$ck]['uid'] != 1 ) {

			$cookie = unserialize( stripslashes( $_COOKIE[$ck] ) );

			$cookie = $db->select( "uid, uname, ulang, uskin, gid, gname, upass, uemail", 
			"users LEFT JOIN {$fa}groups ON gid=ugroup", 
			array('uemail'=>$cookie['uemail'], 'upass'=>$cookie['upass']), "GROUP BY uid LIMIT 0,1");

			if( !$cookie ) {				// BAD USER, NO BISCUIT

				setcookie( $ck , "" , time()-5000000 , $conf['cookie_path'] , $conf['cookie_domain']);
				header( "Location: " . $conf['url'] );
				die();

			} else $user = $cookie[0];			// CHECKS OUT, KEEP GOING

		}

	}

// ##### GET DISPLAY FUNCTIONS ##### //

	require_once( $conf['path']."sources/dp.class.php");

		$dp = new dp();
		
// ##### GET AREA ##### //

	require_once( $conf['path']."language/".$user['ulang']."/system.lang.php" );

		$title[] = $conf['title'];

		$crumb[] = "<a href='{$conf['url']}'>{$conf['title']}</a>";

	if( $conf['open'] == 0 && $user['gid'] != 4 ) {			// GO AWAY, WE'RE CLOSED

		$content = LANG_SYS_CLOSED;		

	} else {							// WE'RE OPEN FOR BUSINESS

		switch( $_GET['go'] ) {

			default:					// FICTION, SEARCH & LATEST - LANGUAGE AND CLASS

				require_once( $conf['path']."language/".$user['ulang']."/fiction.lang.php" );

				require_once( $conf['path']."sources/fiction.class.php" );

				$class = new fiction();
		
			break;

			case $lang['sys']['nav']['cpanel']:		// USER PANEL - LANGUAGE AND CLASS

				require_once( $conf['path']."language/".$user['ulang']."/controlpanel.lang.php" );

				require_once( $conf['path']."sources/controlpanel.class.php" );

				$class = new controlpanel();

			break;

			case $lang['sys']['nav']['apanel']:		// ADMIN PANEL - LANGUAGE AND CLASS
	
				require_once( $conf['path']."language/".$user['ulang']."/adminpanel.lang.php" );

				require_once( $conf['path']."sources/adminpanel.class.php" );

				$class = new adminpanel();

			break;

		}

		$class->user = $user;

		$class->skin = "skins/{$user['uskin']}/";

		$content = $class->makePage();

		if( in_array( $_GET['go'] , $lang['sys']['nav'] ) ) { 

			$title[] = $_GET['go'];

			if( $class->title ) $title[] = $class->title;

			$crumb[] = "<a href='{$conf['url']}index.php?go={$_GET['go']}'>{$_GET['go']}</a>";

			if( $class->crumb) $crumb[] = $class->crumb;

		}

	}

// ##### MAKE NAVIGATION ##### //

	foreach( $lang['sys']['nav'] as $key=>$is ) {

		$key == "cpanel" && $user['gid'] == 4 ? 
		$ad = " (<a href='{$conf['url']}index.php?go={$lang['sys']['nav']['apanel']}'>{$lang['sys']['nav']['apanel']}</a>)" : 
		$ad = ""; 

		if( $key != "apanel" ) $nav[] = "<a href='{$conf['url']}index.php?go={$is}'>{$is}</a>{$ad}";

	}

	if( $nav ) $nav = implode( $conf['sep_navig'] , $nav );

// ##### STUFF TEMPLATE ##### //

	// PLEASE DO NOT CHANGE, REMOVE OR RENDER INVISIBLE THE POWERED BY NOTICE. IT'S NOT BIG, IT'S NOT CLEVER AND YOU WILL BE MOCKED. //

	$pb = "<div class='pb'>Powered by <a href='http://www.alter-idem.com/scripts/fichive/' target='_blank'>FicHive</a> v1.0 &copy; 
	".date("Y")."</div>";

	$file = $dp->grabFile( $conf['path']."skins/".$user['uskin']."/layout.tmpl.php" );

	$categories = $db->select( "cname, cparent, cid, cread" , "categories" , array('cactive'=>1) , "ORDER BY corder" );

	if( $categories ) {

		$gen = "document.location='index.php?go={$lang['sys']['nav']['fiction']}&category=";

		$quicknav = "<select onChange=\"{$gen}'+this.value\"><option value=''>" . LANG_SYS_QUICKNAV . "</option>" . 
		$dp->makeParent( $categories , $_GET['category'] , $user['gid'] ) . "</select>";

	}

	if( !strstr( $file, "<%POWEREDBY%>" ) ) {

		print $pb;

		die();

	}

	$title = stripslashes( implode( $conf['sep_title'] , $title ) );

	$crumb = stripslashes( implode( $conf['sep_crumb'] , $crumb ) );

	$orig = array("<%CONTENT%>", "<%TITLE%>", "<%CRUMBS%>" , "<%NAVIGATION%>", "<%QUICKNAV%>", "<%POWEREDBY%>");

	$repl = array($content, $title , $crumb , $nav, $quicknav, $pb );

	!$_GET['print'] ? print str_replace( $orig , $repl , $file ) : print $content;

ob_end_flush();
?>