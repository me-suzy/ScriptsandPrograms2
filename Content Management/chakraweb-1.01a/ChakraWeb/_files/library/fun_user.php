<?php
// ----------------------------------------------------------------------
// ModName: fun_user.php
// Purpose: Get/Set user information
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_LIBRARY')) 
    die ("You can't access [fun_user.php] file directly...");

/**
 * Log the user in
 * @param uname the name of the user logging in
 * @param pass the password of the user logging in
 * @param whether or not to remember this login
 * @returns bool
 * @return true if the user successfully logged in, false otherwise
 */
function UserLogin($uname, $password)
{
	global $db;

	if (IsUserLogin())
		return true;

	$minfo = &MemberGetInfo('', $uname);
	if ($minfo == false)
		return false;

	//check password
	$md5psw = md5($password);
	if ($md5psw != $minfo['m_password'])
		return false;

    $sql = "update ".SESS_TABLE." SET user_id = " .$minfo['m_id']. " WHERE sess_id = " . $db->qstr(Session('id'));
    if (!DbExecute($sql))
        return false;

    //PrintLine($minfo['m_id'], 'm_id');

	SessionSetValue('uid', $minfo['m_id']);
	SessionSetValue('level', $minfo['m_level']);
	SessionSetValue('lid', $minfo['m_lid']);
	SessionSetValue('uname', $minfo['m_name']);
	SessionSetValue('ufname', $minfo['m_fullname']);
	SessionSetValue('theme', $minfo['m_theme']);
	SessionSetValue('email', $minfo['m_email']);

    $sql = "update sysmember set m_visit=m_visit+1 where m_id=".$minfo['m_id'];
    $db->Execute($sql);

	return true;
}

function UserLogout()
{
	global $db;

    $sql = "update ".SESS_TABLE." SET user_id = " .GUEST_UID. " WHERE sess_id = " . $db->qstr(Session('id'));
    if (!DbExecute($sql))
        return false;

	SessionSetValue('uid', GUEST_UID);
	SessionSetValue('level', GUEST_LEVEL);
	SessionSetValue('uname', _GUEST_NAME);
	SessionSetValue('ufname', _GUEST_FULLNAME);
	SessionSetValue('email', '');

}

function IsUserLogin()
{
	return (Session('uid') != GUEST_UID);
}

function IsUserAdmin()
{
    return UserGetLevel() == WEBADMIN_LEVEL;
}

function IsUserCanRead()
{
    global $gReadLevel;

    //PrintLine(UserGetLevel(), 'UserLevel');
    //PrintLine($gReadLevel, 'ReadLevel');

    return UserGetLevel() >= $gReadLevel;
}

function IsUserCanWrite()
{
    global $gWriteLevel;

    //PrintLine(UserGetLevel(), 'UserLevel');
    //PrintLine($gWriteLevel, 'WriteLevel');

    return UserGetLevel() >= $gWriteLevel;
}

function UserGetID()
{
	$uid = Session('uid', -1);
	if ($uid < 0)
    {
		$uid = GUEST_UID;
        SessionSetValue('uid', $uid);
    }

	return $uid;
}

function UserGetLevel()
{
	$level = Session('level', -1);
	if ($level < 0)
    {
		$level = GUEST_LEVEL;
        SessionSetValue('level', $level);
    }

	return $level;
}

function UserGetName()
{
	$uname = Session('uname', '');
	if (empty($uname) || $uname == '_GUEST_NAME')
    {
		$uname = _GUEST_NAME;
        SessionSetValue('uname', $uname);
    }

	return $uname;
}

function UserGetFullName()
{
	$fullname = Session('ufname', '');
	if (empty($fullname))
    {
		$fullname = _GUEST_FULLNAME;
        SessionSetValue('ufname', $fullname);
    }

	//PrintLine($fullname); die();

	return $fullname;
}

function UserGetEmail()
{
	$email = Session('email', '');
	if (empty($email))
    {
		$email = '';
        SessionSetValue('email', $email);
    }

	return $email;
}

function UserGetTheme()
{
	//use the session value
	$theme = Session('theme', '');
	if (empty($theme))
	{
		//use user option
		$uid = UserGetID();
		if ($uid != GUEST_UID)
			$theme = MemberGetValue($uid, 'm_theme');
		
		if (empty($theme))
			$theme = DEFAULT_THEME;

		SessionSetValue('theme', $theme);
	}

	return $theme; 
}

function UserGetLID()
{
	$lid = Session('lid', '');
	if (empty($lid))
	{
		$uid = UserGetID();
		$lid = MemberGetValue($uid, 'm_lid');

		if (empty($lid))
			$lid = DEFAULT_LID;

		SessionSetValue('lid', $lid);
	}

	return $lid;
}

function UserSetLID($newLid)
{
	SessionSetValue('lid', $newLid);
}

function UserGetLangName()
{
    global $gLanguageList;
    
    $ulid = UserGetLID();
    foreach($gLanguageList as $lid => $lname)
    {
        if ($ulid == $lid)
            return $lname;
    }

    return 'Unknown Language';
}


?>
