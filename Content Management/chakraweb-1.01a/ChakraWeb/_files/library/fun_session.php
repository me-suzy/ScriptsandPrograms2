<?php
// ----------------------------------------------------------------------
// ModName: fun_session.php
// Purpose: Session Management
// Author:  mdwiyono@yahoo.com
//
// Notes: We use our self session management, because
// POST-NUKE session management (base on PHP session management) does'nt work.
// when integrated with our code
// The POST-NUKE code can be see on file fun_session_nuke.php
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_LIBRARY')) 
    die ("You can't access [fun_session.php] file directly...");


define('SESS_NAME', 'MXPHP');       //Session Name, use cookie or cgi
define('SESS_TABLE', 'syssessions'); //Session table name
define('SESS_LIFE', 1);             //Session life time in days
define('SESS_GCTIME', 15);          //Session garbage collection check time in minutes
define('SESS_INACTIVE', 60);        //Session inactive in minutes


$gSessionData = array();

set_time_limit(0);
register_shutdown_function('SessionEnd');

// ----------------------------------------------------------------------
// SessionBegin
// ----------------------------------------------------------------------
function SessionBegin()
{
    $ipaddr = GetActualClientIpAddress();
    $sessid = RequestGetValue(SESS_NAME, '');

    //PrintLine($ipaddr, 'IP Address');
    //PrintLine($sessid, 'User SessionID');

    if (empty($sessid))
        return SessionNew($sessid, $ipaddr);
    else
        return SessionRead($sessid, $ipaddr);
}

// ----------------------------------------------------------------------
// SessionNew
// ----------------------------------------------------------------------
function SessionNew($sessid, $ipaddr)
{
    global $gSessionData;
	global $db;

    $send_cookie = false;
    if (empty($sessid))
    {
        $sessid = md5(uniqid(rand(),1)); 
        $send_cookie = true;
    }

    //PrintLine('Enter SessionNew');

	$colums = 'sess_id, ip_addr, user_id, first_used, last_used';
	$values = $db->qstr($sessid).','.$db->qstr($ipaddr).','.GUEST_UID.','.time().','.time();

	if (!DbSqlInsert(SESS_TABLE, $colums, $values))
	{
        //PrintLine('Leave SessionNew1');

		DbFatalError('SessionNew', 'Unable to save session information'); 
	}
    
    //PrintLine('Leave SessionNew2');

    if ($send_cookie)
    {
        $expired = time()+SESS_LIFE*86400;
        @setcookie(SESS_NAME, $sessid, $expired, '/');
    }

    //initiate session data
    $gSessionData['id'] = $sessid;
    $gSessionData['uid'] = GUEST_UID;
    $gSessionData['lid'] = DEFAULT_LID;
    $gSessionData['theme'] = DEFAULT_THEME;

    srand((double)microtime()*1000000);
    $gSessionData['rand'] = rand();

    //Increment the website visitors
    DbIncIntVar('hp_visitors');

    return true;
}

// ----------------------------------------------------------------------
// Get the session value
// ----------------------------------------------------------------------
function Session($var_name, $default=false)
{
    global $gSessionData;

	if (isset($gSessionData[$var_name]))
		$out = 	$gSessionData[$var_name];
	else
		$out = $default;

	return $out;
}

// ----------------------------------------------------------------------
// Set a session variable
// ----------------------------------------------------------------------
function SessionSetValue($var, $value)
{
    global $gSessionData;

    $gSessionData[$var] = $value;

    return true;
}

// ----------------------------------------------------------------------
// Delete a session variable
// ----------------------------------------------------------------------
function SessionDelete($var)
{
    global $gSessionData;

	unset($gSessionData[$var]); 

    return true;
}



// ----------------------------------------------------------------------
// Continue a current session by updating the life time
// ----------------------------------------------------------------------
function SessionCurrent($sessid)
{
	global $db;

    // Touch the last used time
    $sql = "UPDATE ".SESS_TABLE."
              SET last_used = " . time() . "
              WHERE sess_id = " . $db->qstr($sessid);

    $rs = $db->Execute($sql);
	if ($rs === false) 
		DbFatalError('SessionCurrent', 'Unable to get session information'); 

    return true;
}


// ----------------------------------------------------------------------
// Read session data from database
// ----------------------------------------------------------------------
function SessionRead($sessid, $ipaddr)
{
	global $db;
    global $gSessionData;

    //PrintLine('Enter SessionRead');

    $sql = "SELECT ip_addr, user_id, sess_data FROM ".SESS_TABLE." WHERE sess_id = " . $db->qstr($sessid);
    //PrintLine($sql, 'SessionRead Sql');

    $rs = $db->Execute($sql);
	if ($rs === false) 
		DbFatalError('SessionRead', 'Unable to get session information'); 

    //PrintLine($rs->fields[0], 'Field0');

    if (!$rs->EOF)
    {
        //PrintLine('SessionRead: Read Database');

        $dbipaddr = $rs->fields[0];
        $user_id  = $rs->fields[1];
        $sessdata = $rs->fields[2];
    } 
    else 
    {
        $dbipaddr   = "";
        $user_id    = "";
        $sessdata   = "";
    }
    $rs->Close();

    //PrintLine($dbipaddr, 'dbipaddr');
    //PrintLine($ipaddr, 'ipaddr');

    if ($dbipaddr != $ipaddr)
    {
        //The actual ip address is not same with ip address on database
        //Delete the session from database and create a new one

        SessionDestroy($sessid);
        return SessionNew($sessid, $ipaddr);
    }

    //use the database values
    $gSessionData['id'] = $sessid;
    $gSessionData['uid'] = $user_id;
    $gSessionData['ip'] = $ipaddr;

    $ardata = unserialize($sessdata);    
    if (is_array($ardata))
    {
        $gSessionData = array_merge($gSessionData, $ardata);
    }

    //update the life time
    SessionCurrent($sessid);

    return true;
}

// ----------------------------------------------------------------------
// Write the session data to database
// ----------------------------------------------------------------------
function SessionWrite()
{
	global $db;
    global $gSessionData;

    $sessid = $gSessionData['id'];

    if (!empty($sessid))
    {
        //unset($gSessionData['id']);
        //unset($gSessionData['uid']);
        //unset($gSessionData['ip']);

        $sessdata = serialize($gSessionData);

        $sql = "UPDATE ".SESS_TABLE." SET sess_data = " . $db->qstr($sessdata). " WHERE sess_id = " . $db->qstr($sessid);
        $db->Execute($sql);

        if ($db->ErrorNo() != 0) 
        {
            return false;
        }
    }

    return true;
}


// ----------------------------------------------------------------------
// Destroy the session data to database
// ----------------------------------------------------------------------
function SessionDestroy($sessid)
{
	global $db;

    $sql = "DELETE FROM ".SESS_TABLE." WHERE sess_id = " . $db->qstr($sessid);
    $db->Execute($sql);

    if ($db->ErrorNo() != 0) {
        return false;
    }

    return true;
}


// ----------------------------------------------------------------------
// Clean the session database
// ----------------------------------------------------------------------
function SessionGC()
{
    global $db, $gSysVarInt;
    
    $bsavelasttime = false;
    $bresult = true;

    $curtime = time();
    $lasttime = $gSysVarInt['session_gc'];
    
    if ($lasttime == 0)
    {
        $lasttime = $curtime;
        $bsavelasttime = true;
    }

    //PrintLine($curtime - $lasttime, "CUR-LAST");
    //PrintLine(SESS_GCTIME*60, "GCTIME");

    if ( ($curtime - $lasttime) > (SESS_GCTIME*60))
    {
        //It is the time to cleanup database
        
        //PrintLine('It is the time to cleanup database', 'SessionGC');

        $bsavelasttime = true;

        $where = "WHERE (last_used < " . ($curtime - (SESS_INACTIVE * 60)) . ")
                      OR (first_used < " . ($curtime - (SESS_LIFE * 86400)).')';
        
        $sql = "DELETE FROM ".SESS_TABLE." $where";
        //PrintLine($sql, "SQL");

        $db->Execute($sql);
        if ($db->ErrorNo() != 0) 
        {
            $bresult = false;
        }
    }

    if ($bsavelasttime)
    {
        //PrintLine('bsavelasttime');
        DbSetIntVar('session_gc', $curtime);
    }
    
    return $bresult;
}


// ----------------------------------------------------------------------
// End current session. Call automatically when the program end
// ----------------------------------------------------------------------
function SessionEnd()
{
    //PrintLine('SessionEnd');

    SessionWrite();
    SessionGC();
}


// ----------------------------------------------------------------------
// Get the actual client ip address
// ----------------------------------------------------------------------
function GetActualClientIpAddress()
{
    $ipaddr = $HTTP_SERVER_VARS['REMOTE_ADDR'];

    if (empty($ipaddr))
        $ipaddr = getenv('REMOTE_ADDR');

    if (empty($ipaddr) && !empty($HTTP_SERVER_VARS['HTTP_CLIENT_IP'])) 
        $ipaddr = $HTTP_SERVER_VARS['HTTP_CLIENT_IP'];

    if (empty($ipaddr))
    {
        $tmpipaddr = getenv('HTTP_CLIENT_IP');
        if (!empty($tmpipaddr))
            $ipaddr = $tmpipaddr;
    }

    if  (empty($ipaddr) && !empty($HTTP_SERVER_VARS['HTTP_X_FORWARDED_FOR']))
        $ipaddr = preg_replace('/,.*/', '', $HTTP_SERVER_VARS['HTTP_X_FORWARDED_FOR']);

    if (empty($ipaddr))
    {
        $tmpipaddr = getenv('HTTP_X_FORWARDED_FOR');
        if  (!empty($tmpipaddr))
            $ipaddr = preg_replace('/,.*/', '', $tmpipaddr);
    }

    return $ipaddr;
}


?>
