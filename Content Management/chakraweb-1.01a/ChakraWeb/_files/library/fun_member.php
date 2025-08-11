<?php
// ----------------------------------------------------------------------
// ModName: fun_member.php
// Purpose: Member related functions
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_LIBRARY')) 
    die ("You can't access [fun_member.php] file directly...");


function MemberGetInfo($uid, $m_name)
{
	global $gMemberInfo;
	global $db, $sysmember_columns;

	if (empty($uid))
	{
		if (empty($m_name))
		{
			$where = 'm_id='.$uid;
			$uid = UserGetID();
		}
		else
		{
			$where = 'm_name='.$db->qstr($m_name);
		}
	}
	else
		$where = 'm_id='.$uid;

	if (!isset($gMemberInfo))
		$gMemberInfo = array();
	else 
	{
        if (!empty($uid))
        {
            if (isset($gMemberInfo[$uid]))
                return $gMemberInfo[$uid];
        }
        else
        {
            foreach($gMemberInfo as $uid=>$fields)
            {
                if ($fields['m_name'] == $m_name)
                    return $fields;
            }
        }			
	}

	$rs = DbSqlSelect('sysmember', $sysmember_columns,  $where);
	if ($rs === false) DbFatalError('MemberGetInfo', 'Unable to get member information'); 

	if ($rs->EOF)
	{
		return false;
	}
	else
	{
		$fields = DbGetFieldValues($rs);
		$uid = $fields['m_id'];

		$gMemberInfo[$uid] = $fields;
		return $gMemberInfo[$uid];
	}
}

function MemberGetValue($uid, $varname)
{
	$m_info = &MemberGetInfo($uid, '');
	if ($m_info)
	{
		if (isset($m_info[$varname]))
			return $m_info[$varname];
	}
	return false;
}

function MemberSetValue($uid, $varname, $value)
{
	$m_info = &MemberGetInfo($uid, '');
	if ($m_info)
	{
		$m_info[$varname] = $value;
	}
}

function RenderMemberListFromRS($rs)
{
    global $gCountryList;

    InitCountryList();

    $bIsAdmin = IsUserAdmin();

    $list = "<div id=member-list><dl>\n";

    while (!$rs->EOF)
    {
        if ($rs->fields[6])
            $email = $rs->fields[3];
        else
            $email = '';

        $list .= '<dt>'.HRef('/members/'.$rs->fields[1].'.html', $rs->fields[2]).', '.$gCountryList[$rs->fields[7]].'</dt>';
        $list .= '<dd>';
        $list .= $rs->fields[5];

        if (!empty($email))
            $list .= ' <i>Email</i>: '.HRef('mailto:'.$email, $email).'.';

        if (!empty($rs->fields[4]))
            $list .= ' <i>Homepage</i>: '.HRef($rs->fields[4], $rs->fields[4]).'.';

        $list .= "&nbsp;";
        if ($bIsAdmin)
        {
            $list .= '<br><font face="verdana" size=2>';
            $list .= _FLD_ADMIN.' : ';
            $list .= HRef('/phpmod/member_info.php?uid='.$rs->fields[0], _NAV_MEMBER_INFO).' - ';
            $list .= HRef('/phpmod/member_startpage.php?uid='.$rs->fields[0], _NAV_MEMBER_STARTPAGE).' - ';
            $list .= HRef('/members/'.$rs->fields[1].'.html?op=edit', _NAV_MEMBER_PROFILE).' - ';
            $list .= HRef('/phpmod/member_sendmail.php?uid='.$rs->fields[0], _NAV_MEMBER_SENDMAIL);
            $list .= '</font>';
        }
        $list .= "&nbsp;</dd>\n";

        $rs->MoveNext();
    }
    $list .= "</dl></div>\n";

    return $list;
}

function AddDefaultMemberServices($uid, $lid)
{
    global $db;

    $sql = 'select svc_id from service where svc_default=1 and svc_lid='.$db->qstr($lid);
    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        while (!$rs->EOF)
        {
            $values  = $rs->fields[0].','.$uid;
            $columns = 'svc_id, m_id';

            DbSqlInsert('svcmember', $columns, $values);
            $rs->MoveNext();
        }
    }
}


?>
