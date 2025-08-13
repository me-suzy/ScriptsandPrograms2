<?php

/*
=====================================================
 ExpressionEngine - by pMachine
-----------------------------------------------------
 Nullified by GTT
-----------------------------------------------------
 Copyright (c) 2003 - 2004 pMachine, Inc.
=====================================================
 THIS IS COPYRIGHTED SOFTWARE
 PLEASE READ THE LICENSE AGREEMENT
=====================================================
 File: core.system.php
-----------------------------------------------------
 Purpose: This file initializes ExpressionEngine.
 It loads the system preferences and instantiates the 
 base classes. All data flows through this file.
=====================================================
*/



// --------------------------------------------------
//  Turn off magic quotes
// --------------------------------------------------

    set_magic_quotes_runtime(0);

// ----------------------------------------------
//  Instantiate the Benchmark class
// ----------------------------------------------

    $BM = new Benchmark();
    
    $BM->mark('start');  // Start the timer        
        
// --------------------------------------------------
//  Kill globals
// --------------------------------------------------
    
	foreach (array_merge($_GET, $_POST, $_COOKIE) as $key => $val)
	{
		unset($$key);
	}    
    
// --------------------------------------------------
//  Determine system path
// --------------------------------------------------
                
    if ( ! isset($system_path))
    {
        $system_path = './';

        if (@realpath($system_path) !== FALSE)
        {
            $system_path = realpath($system_path).'/';
        }
    }    
    
    $system_path = str_replace("\\", "/", $system_path);    
             
    if ( ! isset($config_file))
    {
        $config_file = $system_path .'config'.$ext;
    }

    if ( ! isset($self))
    {
        $self = 'index'.$ext ;
    }

// --------------------------------------------------
//  Security checks
// --------------------------------------------------
            
    if ( ! in_array($ext, array('.php', '.php4')))
    {
        exit('Disalowed file extension');
    }   

    if (preg_match("#^(http:\/\/|www\.)#i", $system_path))
    {
        exit('Invalid path formatting');
    }   

    if ( ! file_exists($config_file)) 
    {
        exit('Disalowed system path');
    }
    
    if (isset($uri))
    {
		if (preg_match("#(http:\/\/|www\.|\?)#i", $uri))
		{
			exit('Invalid URI');
		}   
    }
       
   
// ----------------------------------------------
//  Turn off script execution time limit
// ----------------------------------------------

    if (function_exists("set_time_limit") == 1 AND @ini_get("safe_mode") == 0)
    {
        @set_time_limit(0);
    }


// ----------------------------------------------
//  Set base system constants
// ----------------------------------------------

    define('APP_NAME'		,	'ExpressionEngine');
    define('APP_VER'    	,	'PB 2');   
    define('APP_BUILD'    	,	'20040427');   
    define('CONFIG_FILE'	,	$config_file); 
    define('PATH_THEMES'	,	$system_path.'cp_css/'); 
    define('PATH_CACHE' 	,	$system_path.'cache/'); 
    define('PATH_LANG'  	,	$system_path.'language/'); 
    define('PATH_CORE'  	,	$system_path.'core/'); 
    define('PATH_TMPL'  	,	$system_path.'templates/'); 
    define('PATH_DICT'  	,	$system_path.'dictionary/'); 
    define('PATH_MOD'   	,	$system_path.'modules/'); 
    define('PATH_DB'    	,	$system_path.'db/'); 
    define('PATH_PI'    	,	$system_path.'plugins/'); 
    define('PATH_CP'    	,	$system_path.'cp/'); 
    define('PATH'       	,	$system_path);
    define('SELF'       	,	$self);
    define('EXT'        	,	$ext);

    unset($system_path);
    unset($config_file);
    unset($pathinfo);
    unset($self);
    unset($ext);
    
    
// ----------------------------------------------
//  Set User Blog constants
// ----------------------------------------------
    
    if ( ! isset($user_blog))
    {
        define('USER_BLOG',  FALSE);
    }
    else
    {
        // Note: These variables must be added to each user's 
        // index.php file if they use the User Blogs module
    
        define('USER_BLOG'   	, $user_blog);		// The name of the user blog (directory name)
        define('UB_BLOG_ID'  	, $user_blog_id); 	// The weblog ID of the user blog
        define('UB_FIELD_GRP'	, $user_blog_fg);	// The field group of the user blog
        define('UB_CAT_GRP'  	, $user_blog_cg);	// The category group of the user blog
        define('UB_TMP_GRP'  	, $user_blog_tg);	// The template group of the user blog
    }
    

// ----------------------------------------------
//  Fetch config file
// ----------------------------------------------

    require CONFIG_FILE;
    
    if ( ! isset($conf))
    {
        exit("The system does not appear to be installed.");
    }
    
    // Set error reporting to "ALL" if specified in the config file

    if ($conf['debug'] == 2 AND ! isset($conf['demo_date']))
    {
        error_reporting(E_ALL);
    }
    
    // These are configuration exceptions.  In some cases a user might want
    // to manually override a config file setting by adding a variable in
    // the index.php or path.php file.  This loop permits this to happen.
    
    $config_exceptions = array('site_url', 'site_index');

	foreach ($config_exceptions as $exception)
	{
		if (isset($$exception) AND $$exception != '')
		{
			$conf[$exception] = $$exception;
		}
	}
    
// ----------------------------------------------
//  Instantiate the Preferences class
// ---------------------------------------------- 
    
    require PATH_CORE.'core.prefs'.EXT;        
                
    $PREFS = new Preferences();

    // Assign the config file array to the preferences
    // class so we can transport it as an object
    
    $PREFS->core_ini = &$conf;
         
    unset($conf);


// ----------------------------------------------
//  Instantiate the regular expressions class
// ---------------------------------------------- 
    
    require PATH_CORE.'core.regex'.EXT;    
    
    $REGX = new Regex();


// ----------------------------------------------
//  Fetch input data: GET, POST, COOKIE, SERVER
// ----------------------------------------------

    require PATH_CORE.'core.input'.EXT;
        
    $IN = new Input();

	$IN->trim_input = (isset($uri)) ? TRUE : FALSE;
    
    $IN->fetch_input_data();
    
    // Parse URI string if it's not a control panel request.
    // The $uri variable is not set during CP requests
        
    if (isset($uri))
    {
		if (isset($qstr))
		{
			$IN->QSTR = $qstr;
		}
		else
		{
			$IN->parse_uri($uri);
		}
    }



// ----------------------------------------------
//  Determine the request type
// ----------------------------------------------

    // There are three possible request types:
    // 1. A control panel request
    // 2. An "action" request
    // 3. A publicly accessed page (template) request

    if ( ! isset($uri))
    {
        define('REQ', 'CP');        
    }
    else
    {
        if (isset($_GET['ACT']) || isset($_POST['ACT']))
        {
            define('REQ', 'ACTION'); 
        }
        else
        {
            define('REQ', 'PAGE');
        }
    }
    
     
// ----------------------------------------------
//  Connect to the database
// ----------------------------------------------

    require PATH_DB.'db.'.$PREFS->ini('db_type').EXT;
        
    $db_config = array(
                        'hostname'  	=> $PREFS->ini('db_hostname'),
                        'username'  	=> $PREFS->ini('db_username'),
                        'password'  	=> $PREFS->ini('db_password'),
                        'database'  	=> $PREFS->ini('db_name'),
                        'prefix'    	=> $PREFS->ini('db_prefix'),
                        'conntype'  	=> $PREFS->ini('db_conntype'),
                        'debug'			=> ($PREFS->ini('debug') != 0) ? 1 : 0,
                        'show_queries'	=> ($PREFS->ini('show_queries') == 'y') ? TRUE : FALSE,
                        'enable_cache'	=> ($PREFS->ini('enable_db_caching') == 'y') ? TRUE : FALSE
                      );

    $DB = new DB($db_config);
        
    // Connect to DB and turn off caching if it's a CP or ACTION request
    // The DB is connected to automatically on PAGE requests
    
    if (REQ == 'CP' OR REQ == 'ACTION')
    {
        $DB->db_connect();
    
        $DB->enable_cache = FALSE;
    }

// ----------------------------------------------
//  Is this a stylesheet request?
// ----------------------------------------------

// If so, we'll fetch it and exit.  No need to go further.

    if (isset($_GET['css'])) 
    {
        require PATH_CORE.'core.style'.EXT;    
        
        $SS = new Style();
        
        exit;
    }
    
// ----------------------------------------------
//  Instantiate the Functions class
// ----------------------------------------------

    require PATH_CORE.'core.functions'.EXT;    
    
    $FNS = new Functions();
    

// ----------------------------------------------
//  Instantiate the Output class
// ----------------------------------------------

    require PATH_CORE.'core.output'.EXT;    
    
    $OUT = new Output();


// ----------------------------------------------
//  Instantiate the Localization class
// ----------------------------------------------

    require PATH_CORE.'core.localize'.EXT;    
    
    $LOC = new Localize();


// ----------------------------------------------
//  Initialize a session
// ----------------------------------------------

    require PATH_CORE.'core.session'.EXT;

    $SESS = new Session();
    
    // If error reporting is only displayed for Super Admins, we'll enable it
    
    if ($PREFS->ini('debug') == 1 AND $SESS->userdata['group_id'] == 1 AND $PREFS->ini('demo_date') != FALSE)
    {
        error_reporting(E_ALL);
    }
     
     
// ----------------------------------------------
//  Update system statistics
// ----------------------------------------------

    require PATH_MOD.'stats/mcp.stats'.EXT;    
    
    $STAT = new Stats_CP();

	$STAT->update_stats();


// ----------------------------------------------
//  Instantiate language class
// ----------------------------------------------    
        
    require PATH_CORE.'core.language'.EXT;

    $LANG = new Language();
    
    // Fetch core language file
    
    $LANG->fetch_language_file('core');

    
// ----------------------------------------------
//  Is the system turned on?
// ----------------------------------------------
        
    // Note: super-admins can always view the system
        
    if ($SESS->userdata['group_id'] != 1  AND REQ != 'CP')
    {            
        if ($PREFS->ini('is_system_on') == 'y')
        {
        	if ($SESS->userdata['can_view_online_system'] == 'n')
        	{
				$OUT->system_off_msg();
        
            	exit;
            }
        }
        else
        {
        	if ($SESS->userdata['can_view_offline_system'] == 'n')
        	{
				$OUT->system_off_msg();
        
            	exit;
            }        
        }
    }
  
// ----------------------------------------------
//  Process the request
// ----------------------------------------------

switch (REQ)
{
    case 'ACTION' :
                          
            require PATH_CORE.'core.actions'.EXT;
            
            $ACT = new Action();
    break;
    case 'PAGE' :  
    
            // Instantiate the template parsing class
            // and parse the requested template
                
            require PATH_CORE.'core.template'.EXT;
            
            $TMPL = new Template();
            
            // Templates and Template Groups can be hard-coded
            // within either the main triggering file or via an include.
                        
            if ( ! isset($template_group)) $template_group = '';
            if ( ! isset($template)) $template = '';
            
            // Parse the template
            
            $TMPL->run_template_engine($template_group, $template);
            
            // Clear expired spam prevention id numbers
            
            $FNS->clear_spam_hashes();            
			  
            // Log referrers
            
            $FNS->log_referrer();
				
    break;
    case 'CP' :
    
            // Define our base URL, making links easier to build
    
            $s = ($PREFS->ini('admin_session_type') != 'c') ? $SESS->userdata['session_id'] : 0;
            
            define('BASE', SELF.'?S='.$s);  
                               

            // Fetch control panel language file
            
            $LANG->fetch_language_file('cp');
            

            // Instantiate control panel "display" class.
            // This class contains all the HTML elements that
            // are used to create the CP
        
            require PATH_CP.'cp.display'.EXT;
        
            $DSP = new Display();            
            
            //  Instantiate log file class
            
            require PATH_CP.'cp.log'.EXT;
            
            $LOG = new Logger();

            
            // Map available classes against the query string and
            // require and instantiate the class and/or method associated with it

            $class_map = array(
                               // 'query str'   => array('class name', 'method name)
                               
                                  'default'     => array('Home'),
                                  'login'       => array('Login'),
                                  'reset'       => array('Login',   'reset_password'),
                                  'logout'      => array('Login',   'logout'),
                                  'publish'     => array('Publish', 'request_handler'),   
                                  'edit'        => array('Publish', 'request_handler'),
                                  'templates'   => array('Templates'),
                                  'communicate' => array('Communicate'),
                                  'modules'     => array('Modules'),
                                  'members'     => array('Members'),
                                  'myaccount'   => array('MyAccount'),
                                  'admin'       => array('Admin')
                               );
              
                           
            // No admin session exists?  Show login screen
            
            if ($SESS->userdata['admin_sess'] == 0 AND $IN->GBL('C', 'GET') != 'reset')
            {
                $C = $class_map['login']['0'];
                $M = '';
            }
            else
            {
                // If the query string is not in the $class_map array, show default page
                
                if ( ! in_array ($IN->GBL('C'), array_keys($class_map)))
                {
                    $C = $class_map['default']['0'];
    
                    $M = ( ! isset($class_map['default']['1'])) ? '' : $class_map['default']['1'];
                }
                else
                {
                    $C =  $class_map[$IN->GBL('C')]['0'];
                    
                    $M = ( ! isset($class_map[$IN->GBL('C')]['1'])) ? '' : $class_map[$IN->GBL('C')]['1'];
                }
            }                 
            
            // Load the language file for the particular class
            // Note: Language files must be named the same as the class (lowercase)
                        
            $LANG->fetch_language_file(strtolower($C));


            // Require and instantiate the class
                        
            require PATH_CP.'cp.'.strtolower($C).EXT;
        
            $EE = new $C;
     
            // If there is a method, call it.
            
            if ($M != '')
            {
                if (method_exists($EE, $M))
                {
                    $EE->$M();
                }
            }
            
        
            // Assemble the control panel.
        
            // No session? Show restricted version.
            // We use this primarily for the login page
            
            if ($SESS->userdata['admin_sess'] == 0)
            {
                $DSP->show_restricted_control_panel();
            }
            else
            {
                // Is user banned?
                // Before rendering the full control panel we'll make sure the user isn't banned
                // But only if they are not a Super Admin, as they can not be banned

                if ($SESS->userdata['group_id'] != 1)
                {
                    if ($SESS->ban_check('ip'))
                    {
                        return $OUT->fatal_error($LANG->line('not_authorized'));
                    }
                }
            
                // The 'Z' GET variable indicates that we need to show the simplified
                // version of the control panel.  We use this mainly for pop-up pages in
                // which we don't need the navigation.
            
                if ($IN->GBL('Z'))
                {
                    $DSP->show_restricted_control_panel();
                }
                else
                {
                    $DSP->show_full_control_panel();
                }
            }
    break;    
}
// END SWITCH



// ----------------------------------
//  Render the final browser output
// -----------------------------------

    $OUT->display_final_output();
     
// ----------------------------------
//  Garbage Collection
// -----------------------------------

// Every 20th page load we'll clear expired 
// query cache files and spam prevention hashes.

	if (REQ != 'CP')
	{
		srand(time());
	
		if ((rand() % 100) < 5) 
		{                 
			$FNS->delete_expired_files(PATH_CACHE.'db_cache');
			
			$FNS->clear_spam_hashes();
		}
	}


// ----------------------------------
//  Close database connection
// -----------------------------------
    
    $DB->db_close();


// END OF SYSTEM EXECUTION



/*
=====================================================
 Benchmark Class
-----------------------------------------------------
 Purpose: This class can calculate
 the time difference between any two marked points.
 Multiple mark points can be captured.
=====================================================

 CODE EXAMPLE:
    
 $BM = new Benchmark();

 $BM->mark('FIRST_MARK');

 // Some code happens here

 $BM->mark('SECOND_MARK');

 echo $BM->elapsed('FIRST_MARK', 'SECOND_MARK');
    
 Note: "FIRST_MARK" and "SECOND_MARK" are arbitrary names.
 You can call the mark points anything you want - and you
 can define as many marks as you need without instantiating
 a new object.

*/


class Benchmark {

    var $marker = array();
    
    //---------------------------------------------
    //  Set a marker
    //---------------------------------------------

    function mark($name)
    {
        $this->marker[$name] = microtime();
    }
  
    //---------------------------------------------
    //  Calculate elapsed time between two points
    //---------------------------------------------

    function elapsed($point1, $point2, $decimals = 4)
    {
        list($sm, $ss) = explode(' ', $this->marker[$point1]);
        list($em, $es) = explode(' ', $this->marker[$point2]);
                        
        return number_format(($em + $es) - ($sm + $ss), $decimals);
    }
}
// END CLASS
?>