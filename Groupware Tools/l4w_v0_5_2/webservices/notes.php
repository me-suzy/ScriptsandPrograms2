<?php

    // === Base Configuration ==========================================
    include_once ("../config/config.inc.php");
    if (!defined('EASY_FRAMEWORK_DIR'))
        define ("EASY_FRAMEWORK_DIR", substr (dirname (__FILE__),0,-30)."/inc/easy_framework");   
	require_once (EASY_FRAMEWORK_DIR."/easy_framework.inc.php");
    //$logger->set_logging_level (7);
    //$logger->set_ident ("webservice");

    // === Includes ====================================================
    require_once('../extern/nusoap/lib/nusoap.php');
	require_once EASY_FRAMEWORK_DIR.'/classes/extern/pear/PHPUnit/PHPUnit.php';
	require_once '../modules/common/leads4web_model.php';
    require_once '../modules/notes/models/notes_mdl.php';
    require_once '../inc/functions.inc.php';
    require_once '../inc/events.inc.php';
    
    // === PHPGACL =====================================================
    require_once ('../extern/phpgacl/gacl.class.php');
    require_once ('../extern/phpgacl/gacl_api.class.php');
    require_once ('../inc/acl.inc.php');
    
    $gacl_api = new gacl_api($gacl_options);

    //session_start();

    // Create the server instance
    $server = new soap_server();

    // Initialize WSDL support
    $server->configureWSDL('notes', 'urn:notes');

    // Register the method to expose
    $server->register('updateNoteOnServer',				// method name
    	array('login'       => 'xsd:string',
    	      'md5passwd'   => 'xsd:string',
    	      'headline'    => 'xsd:string',
    	      'content'     => 'xsd:string',
    	      'sync_with'   => 'xsd:string',
    	      'identifier'  => 'xsd:string',
    	      'last_change' => 'xsd:string',			
    	      'timeoffset'  => 'xsd:string',
             ),		// input parameters
    	array('return' => 'xsd:string'),	// output parameters
    	'urn:notes',					// namespace
    	'urn:notes#updateNoteOnServer',				// soapaction
    	'rpc',								// style
    	'encoded',							// use
    	'Updates note in leads4web, creates if not existent'			// documentation
    );

    // Define the method as a PHP function
    function updateNoteOnServer(
        $login, 
        $md5passwd, 
        $headline, 
        $content, 
        $sync_with, 
        $identifier, 
        $last_change, 
        $timeoffset) {
        
        global $easy;
        
        $smarty    = null;
        $authClass = null;
        $model     = new notes_model ($smarty, $authClass);
        
        // test legitimation,
        // set UserID
        //$_SESSION['user_id'] = 2;
        $query   = "SELECT * FROM ".TABLE_PREFIX."users WHERE login='".$login."'";
        
		$result  = mysql_query($query);
		logDBError(__FILE__, __LINE__, mysql_error(), true);
		$perm	 = mysql_fetch_array ($result);

		if ($md5passwd <> $perm['password']) {
			$logger->log ("Security_check_core failed for Login $login (runnding as webservice)",2);
            return "legitimation error";
		}

        // overwrite SESSION information...
        if (isset($_SESSION['user_id']))
            $backup_user_id      = $_SESSION['user_id'];
    
        $_SESSION['user_id'] = $perm['id'];

        $params = array ("headline"        => $headline,
                         "content"         => $content,
                         "sync_with"       => $sync_with,
                         "identifier"      => $identifier,
                         "last_change"     => $last_change,		
                         "timeoffset"      => $timeoffset,
                         "object_type"     => 'note',
                         "use_group"       => get_main_group($_SESSION['user_id']));

        $result              = $model->sync_entry($params);

        // ... and return to backup
        if (isset($backup_user_id))
            $_SESSION['user_id'] = $backup_user_id;

        return $result;
    }
    
    /*$server->register('updateNoteOnClient',				// method name
    	array('login'       => 'xsd:string',
    	      'md5passwd'   => 'xsd:string',
    	      'headline'    => 'xsd:string',
    	      'content'     => 'xsd:string',
    	      'sync_with'   => 'xsd:string',
    	      'identifier'  => 'xsd:string',
    	      'last_change' => 'xsd:string',
    	      'timeoffset'  => 'xsd:string',
             ),		// input parameters
    	array('return' => 'xsd:string'),	// output parameters
    	'urn:notes',					// namespace
    	'urn:notes#updateNoteOnClient',				// soapaction
    	'rpc',								// style
    	'encoded',							// use
    	'Updates note in leads4web, creates if not existent'			// documentation
    );

    // Define the method as a PHP function
    function updateNoteOnClient($login, $md5passwd, $headline, $content, $sync_with, $identifier, $last_change, $timeoffset) {
        global $easy;
        
        $smarty    = null;
        $authClass = null;
        $model     = new notes_model ($smarty, $authClass);
        
        // test legitimation,
        // set UserID
        //$_SESSION['user_id'] = 2;
        $query   = "SELECT * FROM users WHERE login='".$login."'";
		$result  = mysql_query($query);
		logDBError(__FILE__, __LINE__, mysql_error(), true);
		$perm	 = mysql_fetch_array ($result);

		if ($md5passwd <> $perm['password']) {
			$logger->log ("Security_check_core failed for Login $login (runnding as webservice)",2);
            return "legitimation error";
		}

        $_SESSION['user_id'] = $perm['id'];

        $params = array ("headline"        => $headline,
                         "content"         => $content,
                         "sync_with"       => $sync_with,
                         "identifier"      => $identifier,
                         "last_change"     => $last_change,
                         "timeoffset"      => $timeoffset,
                         "use_group"       => get_main_group($_SESSION['user_id']));
        $result        = $model->sync_entry($params);

        return $result;
    }*/

    // Use the request to (try to) invoke the service
    $HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
    $server->service($HTTP_RAW_POST_DATA);

?>
