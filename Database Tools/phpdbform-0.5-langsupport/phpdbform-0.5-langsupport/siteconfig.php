<?php
// using default access control class
require_once( "phpdbform/phpdbform_access.php" );

// using default menu class
require_once( "phpdbform/phpdbform_menu.php" );

// choose your database driver
require_once( "phpdbform/phpdbform_mysql.php" );
//require_once( "phpdbform/phpdbform_pgsql.php" );
//require_once( "phpdbform/phpdbform_oracle.php" );

// By Iko (2004-10-17): Choose your language
$lang = "de";
#$lang = "en";
require_once( "lang/lang.$lang.php" );

// database access
$phpdbform_main->db = new phpdbform_db(
	"phpdbform", 	// database name
	"localhost", 	// host
	"phpdbform", 		// username
	"12345"				// password
);

// configure here the theme you want to use
require_once( "phpdbform/themes/nt/theme.php" );
//require_once( "phpdbform/themes/simple/theme.php" );

$emptyHdr="";
$phpdbform_main->theme = new phpdbform_theme( "", $emptyHdr );

// list here the users and password you want to give access to
// the admin interface, the greater the level, more access
// to the user
$users = array(
	"adm"=> array( "password"=>"123", "level"=>99 ),
	"test"=> array( "password"=>"test", "level"=>1 )
);
// don't forget to change the 2nd parameter to something different for each application
$phpdbform_main->access = new phpdbform_access( &$users, "testphpdbform" );

// list here the menu
$phpdbform_main->menu = new phpdbform_menu();

$phpdbform_main->menu->add_group( "Contacts", 1 );
$phpdbform_main->menu->add_item( "Test 1", "test_contact.php" );
$phpdbform_main->menu->add_item( "Test 2 (owner draw)", "test_contact2.php" );
$phpdbform_main->menu->add_item( "Test 3 (no selection form)", "test_contact3.php", 2 );
$phpdbform_main->menu->add_item( "Test 4 (filter)", "test_contact4.php" );
$phpdbform_main->menu->add_item( "Test 5 (retain)", "test_contact5.php" );
$phpdbform_main->menu->add_group( "Photos", 1 );
$phpdbform_main->menu->add_item( "Photos", "test_photos.php" );
$phpdbform_main->menu->add_group( "Reports", 1 );
$phpdbform_main->menu->add_item( "Simple", "test_report.php" );
//$phpdbform_main->menu->add_item( "Extended", "test_report_wt.php" );
$phpdbform_main->menu->add_item( "Type", "test_type.php", 1, "Contacts" );
?>
