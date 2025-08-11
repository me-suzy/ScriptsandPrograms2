<?php
// ----------------------------------------------------------------------
// ModName: fun_stats.php
// Purpose: Collection of functions to maintain the web statistics
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_LIBRARY')) 
    die ("You can't access [fun_stats.php] file directly...");


register_shutdown_function('LogVisitor');


function LogVisitor()
{
    global $gLogVisitor;
    global $db;
	global $gBaseLocalPath;
    global $gFolderId, $gPageId;
    global $gAction;
    global $PhpReferer;

    if (!$gLogVisitor)
        return;

    //PrintLine('LogVisitor');
    //PrintLine($gFolderId, 'gFolderId');
    //PrintLine($gPageId, 'gPageId');

    $time       = date('H:i:s', time());
    $uid        = UserGetName();
    $raddr      = $_SERVER['REMOTE_ADDR'];
	$url        = $_SERVER['REQUEST_URI'];
	$uagent		= $_SERVER['HTTP_USER_AGENT'];


    //log visitor
    $logmsg = "$time\t$uid\t$raddr\t$gPageId\t$url\t$PhpReferer\t$uagent\n";

	$sep = GetLocalPathSeparator();
	$filename = $gBaseLocalPath.'logs'.$sep.STATLOG_PREFIX.date('Y-m-d', time()).'.log';

	$fh = @fopen($filename, 'a');
    if ($fh)
    {
		@fputs($fh, $logmsg);
		@fclose($fh);
    }


    //increment hits
    //if ($gAction == 'show')
    {    
        //we only count on showing the page, not edit or other actions
        DbIncIntVar("hp_hits");

        $lid = UserGetLID();

		$sql = "update web_page set page_hits=page_hits+1 where page_id=$gPageId and page_lid=".$db->qstr($lid);
        $db->Execute($sql);
    }

    $sql = "update sysmember set m_hits=m_hits+1 where m_id=".UserGetID();
    $db->Execute($sql);
}


?>
