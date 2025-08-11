<?php
// ----------------------------------------------------------------------
// ModName: fun_dbvars.php
// Purpose: Get and Set sysvar and sysvarint table
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_LIBRARY')) 
    die ("You can't access [fun_dbvars.php] file directly...");


// ----------------------------------------------------------------------
// DbGetVar
// ----------------------------------------------------------------------
function DbGetVar($var_key)
{
	global $db;

	$sql = 'select var_data from sysvar where var_key='.$db->qstr($var_key);

	//PrintLine($sql, 'DbGetVarValue');

	$rs = &$db->Execute($sql);
	if ($rs === false) DbFatalError('DbGetVar'); 

	if ($rs->EOF)
	{
		//the var name not yet inserted
		$sql = 'insert into sysvar (var_key, var_data) values ('.$db->qstr($var_key).', '."'')";

		//PrintLine($sql, 'DbGetVarValue Insert');
		$db->Execute($sql);
		$var_data = "";
	}
	else
	{
		$var_data = $rs->fields[0];
	}

	//PrintLine($var_data, 'var_data');
	return $var_data;
}

 
// ----------------------------------------------------------------------
// DbSetVar
// ----------------------------------------------------------------------
function DbSetVar($var_key, $var_data)
{
	global $db;
	
	$sql = 'insert into sysvar (var_key, var_data) values ('.$db->qstr($var_key).', '.$db->qstr($var_data).')';
	//print $sql."\r\n";;
	if ($db->Execute($sql) === false)
	{
		$sql = 'update sysvar set var_data ='.$db->qstr($var_data).' where var_key='.$db->qstr($var_key);
		//print $sql."\r\n";;

		$result = &$db->Execute($sql);
		return ($result !== false);
	}
	return true;
}

// ----------------------------------------------------------------------
// DbGetIntVar
// ----------------------------------------------------------------------
function DbGetIntVar($var_key)
{
	global $db;

	$sql = 'select var_data from sysvarint where var_key='.$db->qstr($var_key);
	//PrintLine($sql, 'DbGetIntVar');

	$rs = &$db->Execute($sql);
	if ($rs === false) DbFatalError('DbGetIntVar', 'Unable to get sysvarint data'); 

	if ($rs->EOF)
	{
		//the var name not yet inserted
		$sql = 'insert into sysvarint (var_key, var_data) values ('.$db->qstr($var_key).', 0)';
		
        //PrintLine($sql, 'DbGetIntVar Insert');
		$db->Execute($sql);
		$var_data = 0;
	}
	else
	{
		$var_data = $rs->fields[0];
	}

	return $var_data;

}


// ----------------------------------------------------------------------
// DbSetIntVar
// ----------------------------------------------------------------------
function DbSetIntVar($var_key, $var_data)
{
	global $db;
	
	$sql = 'insert into sysvarint (var_key, var_data) values ('.$db->qstr($var_key).', '.$var_data.')';
    //PrintLine($sql, 'DbSetIntVar');

    if ($db->Execute($sql) === false)
	{
		$sql = 'update sysvarint set var_data ='.$var_data.' where var_key='.$db->qstr($var_key);
        //PrintLine($sql, 'DbSetIntVar');

		$result = &$db->Execute($sql);
		return ($result !== false);
	}
	return true;
}

// ----------------------------------------------------------------------
// DbIncIntVar
// ----------------------------------------------------------------------
function DbIncIntVar($var_key)
{
	global $db;

	$sql = 'select var_data from sysvarint where var_key='.$db->qstr($var_key);
	//PrintLine($sql, 'DbIncIntVar');

	$rs = &$db->Execute($sql);

	if ($rs === false) DbFatalError('DbIncIntVar', 'Unable to read sysvarint data'); 
	if ($rs->EOF)
	{
	    $sql = 'insert into sysvarint (var_key, var_data) values ('.$db->qstr($var_key).', 1)';
        //PrintLine($sql, 'DbIncIntVar');

        if ($db->Execute($sql) === false)
            return false;
    }
    else
    {
		$sql = 'update sysvarint set var_data=var_data+1 where var_key='.$db->qstr($var_key);
        //PrintLine($sql, 'DbIncIntVar');

        if ($db->Execute($sql) === false)
            return false;
    }

    return true;
}


?>
