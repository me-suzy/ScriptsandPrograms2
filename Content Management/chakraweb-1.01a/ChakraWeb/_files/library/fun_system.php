<?php
// ----------------------------------------------------------------------
// ModName: fun_system.php
// Purpose: System Related Functions
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_LIBRARY')) 
    die ("You can't access [fun_system.php] file directly...");

function SystemFatalError($section, $msg)
{
    SetDynamicContent();

	$out =  '<html><head><title>Fatal System Error</title>';
	$out .= '</head><body>';
	$out .= '<hr noshade size=2>';
	$out .= 'Fatal System Error On <b>'.$section.':</b><br> '.$msg;
	$out .= '<hr noshade size=2>';
	$out .= 'Sorry for this unconvenience. Please report to the webmaster of this homepage.';
	$out .= '</body></html>';

    echo $out;

	die();
}

function SystemGetCurrentPath()
{
	$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

	$pos = strpos($url, '/');
	$x = strlen($url);
	while ($pos)
	{
		$x = $pos;
		$pos = strpos($url, '/', $x+1);
	}
	return substr($url, 0, $x+1);
}

function CheckSystemMainenanceTime()
{
    global $gMaintenanceTime;

    if ($gMaintenanceTime && !IsUserAdmin())
    {
        SetDynamicContent();

        print '
<html>
    <head>
    <title>Maintenance Time</title>
    </head>
<body>
<table>
  <tr>
    <td vAlign="top" align="middle" width="70"><img src="/images/info_big.gif" width="36" height="48">
    <td width="400">
      <h1><font face="Verdana" size="5" color="#666666">Maintenance
      Time</font>
      </h1>
      We are on maintenance time. Please come back again some hour later.
      <br>
      <br>
      <hr color="blue" SIZE="1">
		If you still experience the problem, try contacting the Web site administrator.
    </td>
  </table>
</body>
</html>';
        die();
    }
}

function SystemGetConstant()
{
    global $gSysConstant;

    $gSysConstant['DB_TYPE']     = DB_TYPE;
    $gSysConstant['DB_HOST']     = DB_HOST;
    $gSysConstant['DB_NAME']     = DB_NAME;
    $gSysConstant['DB_USER']     = DB_USER;
    $gSysConstant['DB_PASSWORD'] = DB_PASSWORD;

    $gSysConstant['MAIL_TYPE'] = MAIL_TYPE;
    $gSysConstant['SMTP_HOST'] = SMTP_HOST;
    $gSysConstant['SMTP_HELO'] = SMTP_HELO;
    $gSysConstant['SMTP_PORT'] = SMTP_PORT;

    $gSysConstant['DBLOG_PREFIX'] = DBLOG_PREFIX;
    $gSysConstant['STATLOG_PREFIX'] = STATLOG_PREFIX;

    $gSysConstant['DEFAULT_THEME'] = DEFAULT_THEME;
    $gSysConstant['DEFAULT_LID'] = DEFAULT_LID;
    $gSysConstant['DEFAULT_ORDER'] = DEFAULT_ORDER;
    $gSysConstant['DEFAULT_ROBOTS'] = DEFAULT_ROBOTS;

    $gSysConstant['MAX_ITEM_PERPAGE'] = MAX_ITEM_PERPAGE;
    $gSysConstant['MAX_PAGEPOS_SHOW'] = MAX_PAGEPOS_SHOW;

}

function SystemSaveVariables()
{
    global $gBaseLocalPath;
    global $gLogDBase;
    global $gLogVisitor;
    global $gBaseUrlPath;
    global $gHomePageUrl;
    global $gMaintenanceTime;
    global $gSysVar, $gSysConstant;

    $filename = $gBaseLocalPath.'_files/library/_defgenerate.php';

    $fh = @fopen($filename, 'wb');
    if ($fh <= 0)
        return false;

    $content  = "<?php\n//Auto generated header. Don't change by hand\n\n";
    $content .= "//database\n";
    $content .= "define(DB_TYPE, '".$gSysConstant['DB_TYPE']."');\n";
    $content .= "define(DB_HOST, '".$gSysConstant['DB_HOST']."');\n";
    $content .= "define(DB_NAME, '".$gSysConstant['DB_NAME']."');\n";
    $content .= "define(DB_USER, '".$gSysConstant['DB_USER']."');\n";
    $content .= "define(DB_PASSWORD, '".$gSysConstant['DB_PASSWORD']."');\n\n";

    $content .= "//for sending email\n";
    $content .= "define(MAIL_TYPE, '".$gSysConstant['MAIL_TYPE']."'); //mail or smtp\n";
    $content .= "define(SMTP_HOST, '".$gSysConstant['SMTP_HOST']."');\n";
    $content .= "define(SMTP_HELO, '".$gSysConstant['SMTP_HELO']."');\n";
    $content .= "define(SMTP_PORT, '".$gSysConstant['SMTP_PORT']."');\n\n";

    $content .= "//logging\n";
    $content .= "\$gLogDBase      = ".($gLogDBase?'true':'false').";\n";
    $content .= "\$gLogVisitor    = ".($gLogVisitor?'true':'false').";\n\n";

    $content .= "//prefix use on log files\n";
    $content .= "define(DBLOG_PREFIX, '".$gSysConstant['DBLOG_PREFIX']."');\n";
    $content .= "define(STATLOG_PREFIX, '".$gSysConstant['STATLOG_PREFIX']."');\n\n";

    $content .= "//base url path\n";
    $content .= "\$gBaseUrlPath = '".$gBaseUrlPath."';\n\n";

    $content .= "//homepage url\n";
    $content .= "\$gHomePageUrl = '".$gHomePageUrl."';\n\n";

    $content .= "//maintenance time\n";
    $content .= "\$gMaintenanceTime = ".($gMaintenanceTime?'true':'false').";\n\n";

    $content .= "//default values\n";
    $content .= "define(DEFAULT_THEME, '".$gSysConstant['DEFAULT_THEME']."');\n";
    $content .= "define(DEFAULT_LID, '".$gSysConstant['DEFAULT_LID']."');\n";
    $content .= "define(DEFAULT_ORDER, ".$gSysConstant['DEFAULT_ORDER'].");\n";
    $content .= "define(DEFAULT_ROBOTS, '".$gSysConstant['DEFAULT_ROBOTS']."');\n\n";

    $content .= "//search result\n";
    $content .= "define(MAX_ITEM_PERPAGE, ".$gSysConstant['MAX_ITEM_PERPAGE'].");\n";
    $content .= "define(MAX_PAGEPOS_SHOW, ".$gSysConstant['MAX_PAGEPOS_SHOW'].");\n\n";

    $content .= "//system var\n";
    $content .= "\$gSysVar = array (\n";
    $content .= "    'hp_name'           => '".str_replace("'", "\'", $gSysVar['hp_name'])."',\n";
    $content .= "    'hp_desc'           => '".str_replace("'", "\'", $gSysVar['hp_desc'])."',\n";
    $content .= "    'hp_slogan'         => '".str_replace("'", "\'", $gSysVar['hp_slogan'])."',\n";
    $content .= "    'hp_keywords'       => '".str_replace("'", "\'", $gSysVar['hp_keywords'])."',\n";
    $content .= "    'hp_header'         => '".str_replace("'", "\'", $gSysVar['hp_header'])."',\n";
    $content .= "    'hp_footer'         => '".str_replace("'", "\'", $gSysVar['hp_footer'])."',\n";
    $content .= "    'hp_sidebar'        => '".str_replace("'", "\'", $gSysVar['hp_sidebar'])."',\n";
    $content .= "    'svc_email_from'    => '".str_replace("'", "\'", $gSysVar['svc_email_from'])."',\n";
    $content .= "    'svc_email_replay'  => '".str_replace("'", "\'", $gSysVar['svc_email_replay'])."',\n";
    $content .= "    'svc_email_subject' => '".str_replace("'", "\'", $gSysVar['svc_email_subject'])."',\n";
    $content .= ");\n";

    $content .= "\n?>\n";

    @fputs($fh, $content);
    @fclose($fh);
    
    return true;
}



?>
