<?php
// ----------------------------------------------------------------------
// ModName: fun_session.php
// Purpose: Session Management
// Author:  POST-NUKE. Modified by Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_LIBRARY')) 
    die ("You can't access [fun_session.php] file directly...");

//Security Level, maintain the life time of our cookie
$gSecurityLevel             = "High";   //Options: Low, Medium, High
$gSecurityMedDays           = 1;        //Session set number of days
$gSecurityInactiveInMinutes = 15;       //inactivity timeout for user sessions


/**
 * Set up session handling
 *
 * Set all PHP options for PostNuke session handling
 */
function SessionSetup()
{
    global $HTTP_SERVER_VARS;
    global $gSecurityLevel;
    global $gSecurityMedDays;
    global $gIsIntranet;
    global $gSecurityInactiveInMinutes;

    $path = "/"; //GetBaseURI();

    if (empty($path)) {
        $path = '/';
    }
    $host = $HTTP_SERVER_VARS['HTTP_HOST'];
    if (empty($host)) {
        $host = getenv('HTTP_HOST');
    }
    $host = preg_replace('/:.*/', '', $host);

    // PHP configuration variables

    // Stop adding SID to URLs
    ini_set('session.use_trans_sid', 0);

    // User-defined save handler
    ini_set('session.save_handler', 'user');

    // How to store data
    ini_set('session.serialize_handler', 'php');

    // Use cookie to store the session ID
    ini_set('session.use_cookies', 1);

    // Name of our cookie
    ini_set('session.name', SESSION_NAME);

    // Lifetime of our cookie
    $seclevel = $gSecurityLevel;

    switch ($seclevel) {
        case 'High':
            // Session lasts duration of browser
            $lifetime = 0;
            // Referer check
            //ini_set('session.referer_check', "$host$path");
            ini_set('session.referer_check', "$host");
            break;
        case 'Medium':
            // Session lasts set number of days
            $lifetime = $gSecurityMedDays * 86400;
            break;
        case 'Low':
            // Session lasts unlimited number of days (well, lots, anyway)
            // (Currently set to 25 years)
            $lifetime = 788940000;
            break;
    }
    ini_set('session.cookie_lifetime', $lifetime);
    
    if (!$gIsIntranet) 
    {
        // Cookie path
        ini_set('session.cookie_path', $path);

        // Cookie domain
        // only needed for multi-server multisites - adapt as needed
        //$domain = preg_replace('/^[^.]+/','',$host);
        //ini_set('session.cookie_domain', $domain);
    }

    // Garbage collection
    ini_set('session.gc_probability', 1);

    // Inactivity timeout for user sessions
    ini_set('session.gc_maxlifetime', $gSecurityInactiveInMinutes * 60);

    // Auto-start session
    ini_set('session.auto_start', 1);

    // Session handlers
    session_set_save_handler("SessionOpen",
                             "SessionClose",
                             "SessionRead",
                             "SessionWrite",
                             "SessionDestroy",
                             "SessionGC");
    return true;
}

/*
 * Session variables here are a bit 'different'.  Because they sit in the
 * global namespace we use a couple of helper functions to give them their
 * own prefix, and also to force users to set new values for them if they
 * require.  This avoids blatant or accidental over-writing of session
 * variables.
 *
*/

/**
 * Get a session variable
 *
 * @param name name of the session variable to get
 */
function SessionGetValue($name)
{
    global $HTTP_SESSION_VARS;

    $var = "SV$name";

    global $$var;
    if (!empty($HTTP_SESSION_VARS[$var])) {
        return $HTTP_SESSION_VARS[$var];
    }

    return NULL;
}

function Session($name)
{
    global $HTTP_SESSION_VARS;

    $var = "SV$name";

    global $$var;
    if (!empty($HTTP_SESSION_VARS[$var])) 
	{
        return $HTTP_SESSION_VARS[$var];
    }

    return NULL;
}

/** 
 * Set a session variable
 * @param name name of the session variable to set
 * @param value value to set the named session variable
 */
function SessionSetValue($name, $value)
{
	global $HTTP_SESSION_VARS;
   	$var = "SV$name";

   	global $$var;
	$$var = $value;

	$HTTP_SESSION_VARS[$var] = $value;
	session_register($var);

   	return true;
}

/**
 * Delete a session variable
 * @param name name of the session variable to delete
 */
function SessionDelVar($name)
{
    $var = "SV$name";

    global $$var;
	// Fix for PHP >4.0.6 By John Barnett (johnpb)
    //unset($$var);	
	unset($GLOBALS[$var]); 

    session_unregister($var);

    return true;
}

/**
 * Initialise session
 */
function SessionInit()
{
    global $db, $HTTP_SERVER_VARS;

    // First thing we do is ensure that there is no attempted pollution
    // of the session namespace
    foreach($GLOBALS as $k=>$v) {
        if (preg_match('/^SV/', $k)) {
            return false;
        }
    }

    // Kick it
    session_start();

    // Have to re-write the cache control header to remove no-save, this
    // allows downloading of files to disk for application handlers
    // adam_baum - no-cache was stopping modules (andromeda) from caching the playlists, et al.
    // any strange behaviour encountered, revert to commented out code.
    //Header('Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0');
    Header('Cache-Control: cache');

    $sessid = session_id();

    // Get (actual) client IP addr
    $ipaddr = $HTTP_SERVER_VARS['REMOTE_ADDR'];
    if (empty($ipaddr)) {
        $ipaddr = getenv('REMOTE_ADDR');
    }
    if (!empty($HTTP_SERVER_VARS['HTTP_CLIENT_IP'])) {
        $ipaddr = $HTTP_SERVER_VARS['HTTP_CLIENT_IP'];
    }
    $tmpipaddr = getenv('HTTP_CLIENT_IP');
    if (!empty($tmpipaddr)) {
        $ipaddr = $tmpipaddr;
    }
    if  (!empty($HTTP_SERVER_VARS['HTTP_X_FORWARDED_FOR'])) {
        $ipaddr = preg_replace('/,.*/', '', $HTTP_SERVER_VARS['HTTP_X_FORWARDED_FOR']);
    }
    $tmpipaddr = getenv('HTTP_X_FORWARDED_FOR');
    if  (!empty($tmpipaddr)) {
        $ipaddr = preg_replace('/,.*/', '', $tmpipaddr);
    }

    $sql = "SELECT ip_addr FROM syssessions WHERE sess_id = " . $db->qstr($sessid);

    $rs = $db->Execute($sql);
	if ($rs === false) 
		DbFatalError('SessionInit', 'Unable to get session information'); 

    if (!$rs->EOF) {
// jgm - this has been commented out so that the nice AOL people
//       can view PN pages, will examine full implications of this
//       later
//        list($dbipaddr) = $rs->fields;
        $rs->Close();
   
//        if ($ipaddr == $dbipaddr) {
            SessionCurrent($sessid);
//        } else {
//          // Mismatch - destroy the session
//          session_destroy();
//          pnRedirect('index.php');
//          return false;
//        }
    } else {

        SessionNew($sessid, $ipaddr);
        
        // Generate a random number, used for
        // some authentication
        srand((double)microtime()*1000000);
        SessionSetValue('rand', rand());

		SessionSetValue('uid', GUEST_UID);

    }

    return true;
}

/**
 * Continue a current session
 * @private
 * @param sessid the session ID
 */
function SessionCurrent($sessid)
{
	global $db;

    // Touch the last used time
    $sql = "UPDATE syssessions
              SET last_used = " . time() . "
              WHERE sess_id = " . $db->qstr($sessid);

    $rs = $db->Execute($sql);
	if ($rs === false) 
		DbFatalError('SessionCurrent', 'Unable to get session information'); 

    return true;
}

/**
 * Create a new session
 * @private
 * @param sessid the session ID
 * @param ipaddr the IP address of the host with this session
 */
function SessionNew($sessid, $ipaddr)
{
	global $db;

	$colums = 'sess_id, ip_addr, user_id, first_used, last_used';
	$values = $db->qstr($sessid).','.$db->qstr($ipaddr).','.GUEST_UID.','.time().','.time();

	if (!DbSqlInsert('syssessions', $colums, $values))
	{
		PrintLine("Failed");
		DbFatalError('SessionNew', 'Unable to save session information'); 
	}


    return true;
}

/**
 * PHP function to open the session
 * @private
 */
function SessionOpen($path, $name)
{
    // Nothing to do - database opened elsewhere
    return true;
}

/**
 * PHP function to close the session
 * @private
 */
function SessionClose()
{
    // Nothing to do - database closed elsewhere
    return true;
}

/**
 * PHP function to read a set of session variables
 * @private
 */
function SessionRead($sessid)
{
	global $db;

    $sql = "SELECT sess_data
              FROM syssessions
              WHERE sess_id = " . $db->qstr($sessid);
    $rs = $db->Execute($sql);
	if ($rs === false) 
		DbFatalError('SessionRead', 'Unable to get session information'); 

    if (!$rs->EOF) {
        list($value) = $rs->fields;
    } else {
        $value = '';
    }
    $rs->Close();

    return($value);
}

/**
 * PHP function to write a set of session variables
 * @private
 */
function SessionWrite($sessid, $vars)
{
	global $db;

    $sql = "UPDATE syssessions SET sess_data = " . $db->qstr($vars). " WHERE sess_id = " . $db->qstr($sessid);
    $db->Execute($sql);

    if ($db->ErrorNo() != 0) {
        return false;
    }

    return true;
}

/**
 * PHP function to destroy a session
 * @private
 */
function SessionDestroy($sessid)
{
	global $db;

    $sql = "DELETE FROM syssessions WHERE sess_id = " . $db->qstr($sessid);
    $db->Execute($sql);

    if ($db->ErrorNo() != 0) {
        return false;
    }

    return true;
}

/**
 * PHP function to garbage collect session information
 * @private
 */
function SessionGC($maxlifetime)
{
	global $db;
    global $gSecurityLevel;
    global $gSecurityInactiveInMinutes;
    global $gSecurityMedDays;

    switch ($gSecurityLevel) 
    {
        case 'Low':
            // Low security - delete session info if user decided not to
            //                remember themself
            $where = "WHERE sess_data NOT LIKE '%SVrememberme|%'
                      AND last_used < " . (time() - ($gSecurityInactiveInMinutes * 60));
            break;
        case 'Medium':
            // Medium security - delete session info if session cookie has
            //                   expired or user decided not to remember
            //                   themself
            $where = "WHERE (sess_data NOT LIKE '%SVrememberme|%'
                        AND last_used < " . (time() - ($gSecurityInactiveInMinutes * 60)) . ")
                      OR first_used < " . (time() - ($gSecurityMedDays * 86400));
            break;
        case 'High':
        default:
            // High security - delete session info if user is inactive
            $where = "WHERE last_used < " . (time() - ($gSecurityInactiveInMinutes * 60));
            break;
    }
    $sql = "DELETE FROM syssessions $where";
    $db->Execute($sql);

    if ($db->ErrorNo() != 0) {
        return false;
    }

    return true;
}
?>