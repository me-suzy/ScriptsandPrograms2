<?php

/*
+---------------------------------------------------------------------------
|   > $$INDEX.PHP
|   > Version: 1.0.5
+--------------------------------------------------------------------------
*/

// Set to '0' for release or live '1' for debug

$DEBUG = 1;
$DEBUG_INFO = 0;

// Developing:

$IS_NEED_DB = 1;

// Define ERROR reporting level

if ( $DEBUG == 1 ) {
   error_reporting  (E_ERROR | E_WARNING | E_PARSE);
// error_reporting  (E_ALL);
}

// Define usable dirs:

$DIRS = array();
$DIRS['TOP']       = './';
$DIRS['INC']       = './inc/';
$DIRS['DRIVERS']   = './inc/drivers/';
$DIRS['LIBS']      = './inc/libs/';
$DIRS['LANGS']     = './lang/';
$DIRS['MODULES']   = './modules/';
$DIRS['TPLS']      = './tpl/';
$DIRS['MAIL_TPLS'] = './tpl/mail/';


// Main nix class:

class NS {

	var $input      = array();
	var $base_url   = "";
	var $vars       = "";
	var $lang_id    = "en";
        var $dirs       = "";
	var $sess       = "";

	function NS() {
		global $std, $DB, $DIRS, $INFO, $sess, $tpl;

		$this->vars     = &$INFO;
		$this->dirs     = &$DIRS;
                $this->db       = &$DB;
		$this->sess     = &$sess;
		$this->tpl      = &$tpl;

	}

    function callClass($class_name,$main=False) {
       if ( $main ) {
         return new $class_name();
       } else {
           return new $class_name;
         }
    }
}

/*
  @ Import sources
*/

require ($DIRS['TOP'] . 'inf.php');
require ($DIRS['INC'] . 'functions.class' . $INFO['PHP_EXT']);

/*
  @ Create std
*/

$std = new functions();

/*
  @ Create DB class
*/

 if($IS_NEED_DB) {

   $INFO['SQL_DRIVER'] = !$INFO['SQL_DRIVER'] ? 'mysql' : $INFO['SQL_DRIVER'];
   $to_require = $DIRS['DRIVERS'] . $INFO['SQL_DRIVER'] . $INFO['PHP_EXT'];
   require ($to_require);

   // Create local database object
   $DB = new $INFO['SQL_DRIVER'];

   // Configure params for connection
   $DB->obj['sql_host']           =   $INFO['SQL_HOST'];
   $DB->obj['sql_port']           =   $INFO['SQL_PORT'];
   $DB->obj['sql_user']           =   $INFO['SQL_USER'];
   $DB->obj['sql_pass']           =   $INFO['SQL_PSWD'];
   $DB->obj['sql_database']       =   $INFO['SQL_NAME'];
   $DB->obj['sql_tbl_prefix']     =   $INFO['SQL_PREFIX'];

   $DB->connect();

 }

/*
  @ Init main class   //
*/
$needsecure = new NS();

/*
  @ Grab input data
*/
$needsecure->input = $std->parse_incoming();

/*
  @ Auth members
*/
require ($DIRS['INC'] . 'sessions.class' . $INFO['PHP_EXT']);
$sess = new sessions;
$needsecure->member = $sess->authMember();
$needsecure->session_id = $sess->session_id;

/*
  @ If no real member id, login it!
*/

if ( $needsecure->input['act'] != 'login' && $needsecure->input['act'] != 'reg' && $std->my_getcookie("member_id") == 0 && $needsecure->member['id'] == 0 ) {
   $needsecure->input['act'] = '';
   $needsecure->input['code'] = '';
}

/*
  @ Load Templates class
*/
require ($DIRS['INC'] . 'template.class' . $INFO['PHP_EXT']);
$tpl = new Template($DIRS['TPLS']);

/*
  @ Redefine language
  @ Load needed words:
*/
$needsecure->lang_id = !$needsecure->member['lang'] ? $INFO['DEFAULT_LANG'] : $needsecure->member['lang'];
$needsecure->words = $std->load_words( $needsecure->words , 'lng_global' , $needsecure->lang_id );

/*
  @ Set default url
*/
$needsecure->base_url = "{$needsecure->vars['BASE_URL']}index{$needsecure->vars['PHP_EXT']}?s={$needsecure->session_id}";

//if ( (empty($needsecure->member)) or ($needsecure->member == '') or ($needsecure->member['id'] == '0') ) {
//  $std->Error("Sorry, Your session has expired. Please <a class='news_lnk' href='{$needsecure->base_url}&act=login'>LogIn</a>");
//}

/*
  @ Final check if the system is offline
*/
if ( $INFO['IS_OFFLINE'] == 1 ) {

   $std->Error($INFO['OFFLINE_MSG']);

}

/*
  @ Get list of program modules :)
*/
$modules = array(
        'idx'		=>	'idx.mod',
	'login'		=>	'login.mod',
	'reg'		=>	'reg.mod',
        'news'		=>	'news.mod',
        'profile'	=>	'profile.mod',
	'stat'		=>	'stat.mod',
	'dirs'          =>      'dirs.mod'
);

/*
  @ Redefine input[act] if it's NULL
*/
$needsecure->input['act'] =  $needsecure->input['act'] == '' ? "login" : $needsecure->input['act'];
$needsecure->input['act'] = !$needsecure->input['act'] ? "login" : $needsecure->input['act'];

/*
  @ Sure module action exists
*/
if ( ! isset($modules[ $needsecure->input['act'] ]) ) {
   $needsecure->input['act'] = 'idx';
}

/*
  @ Define module to load
*/
$module_source = $needsecure->dirs['MODULES'] . $modules[ $needsecure->input['act'] ] . $needsecure->vars['PHP_EXT'];
$needsecure->used_module = $module_source;

/*
  @ Sure required module exists
*/
if ( !file_exists($module_source) ) {
     $err_msg = "<br><strong>Module [ {$needsecure->used_module} ] init error</strong><br><br>";

     $std->Error($err_msg);
}

/*
  @ Require and run
*/
require ($needsecure->used_module);

/*
  @ If DEBUG defined, show our debug info
*/
if ($DEBUG_INFO == 1) {
  echo show_debug_info();
}


//// THE END ///

   function show_debug_info() {
     global $needsecure;

     return "
	   <table width='100%' height='100%' align='center' cellSpacing='0' cellPadding='0' border='0'><tr>
	    <td align='center' vAlign='center'>

		<table width='500' cellSpacing='2' cellPadding='1' border='0' align='center'><tr>
		<td height='20' style='border: 1px solid black; background-color: #aab9d6;' align='center' vAlign='middle'>
		  <strong>Debug Handler :: <span style='color:red;font-weight:bold;'>Latest</span></strong>
		</td>
	   </tr><tr>
	    <td height='150' vAlign='top' style='padding: 10px; border: 1px dotted black; background-color: #FFFFFF;'>
		 <br>
		  <img src='{$needsecure->vars[IMG_URL]}warning.gif' botder='0' align='center'>
		  <strong>Latest debug info:</strong><br><br>
		  <p>
                INPUT[request_method]    =>  {$needsecure->input[request_method]}<br>
           INPUT[IP_ADDRESS]        =>  {$needsecure->input[IP_ADDRESS]}<br>
           INPUT[lang]              =>  {$needsecure->input[lang]}<br>
           INPUT[act]               =>  {$needsecure->input[act]}<br>
           INPUT[code]              =>  {$needsecure->input[code]}<br>
           INPUT[step]              =>  {$needsecure->input[step]}<br>
	   INPUT[s]                 =>  {$needsecure->session_id}<br>
           REDEFINED[lang]          =>  {$needsecure->lang_id}<br>
           MODULE_LOADED            =>  {$needsecure->used_module}<br>
		  </p>
		</td>
	   </tr><tr>
	    <td height='20' style='border: 1px solid black; background-color: #aab9d6;' align='center' vAlign='middle'>
		  <strong><a class='my_error_lnk' href='javascript:history.back()'>go back</a>&nbsp;&nbsp;</strong>
		</td>
		</tr></table>

		</td>
	   </tr></table>
	  ";
   }

?>