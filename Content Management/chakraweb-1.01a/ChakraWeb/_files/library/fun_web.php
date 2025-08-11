<?php
// ----------------------------------------------------------------------
// ModName: fun_web.php
// Purpose: String Manipulation
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_LIBRARY')) 
    die ("You can't access [fun_web.php] file directly...");

function InitSystemVars()
{
	global $gLocalPathSeparator;
	global $gBaseLocalPath;
	global $gRequestPath;
    global $gRequestFile;
	global $gFolderId;
	global $gImageLocalPath;
    global $gWebPage;
    global $gAction;

    GetOsInfo();

	$gLocalPathSeparator = GetLocalPathSeparator();

	$sep = $gLocalPathSeparator;

	$gBaseLocalPath = ProperLocalFileName($_SERVER['DOCUMENT_ROOT'].$sep);
	$gImageLocalPath = $gBaseLocalPath."images".$sep;

	$uri = StrUnEscape($_SERVER["REQUEST_URI"]);
	if ($PhpMagicQuote)
		$uri = stripslashes($uri);

	ParseRequestPathAndFile($uri, $gRequestPath, $gRequestFile);

    $gAction = RequestGetValue('op', 'show');

	$gFolderId = RequestGetValue('cat', 0);
	if ($gFolderId == 0)
		$gFolderId = RequestGetValue("folder_id", 0);

	$gPageId = RequestGetValue('id', 0);
}

function InitWebPage()
{
    global $gFolderId;
	global $gLocalPathSeparator;

	global $gHomePageUrl;
	global $gBaseUrlPath;
	global $gBaseLocalPath;
	global $gRequestPath;
	global $gCurrentUrlPath;

	global $gTemplateUrlPath;
	global $gTemplateLocalPath;

	global $gHomePageSlogan;
	global $gHomePageHeader;
	global $gHomePageFooter;

	//start code
    GetHomePageData();

    $gWebPage = array();

    $theme = RequestGetValue('t', '');
    if (!empty($theme))
        ChangeCurrentUserTheme($theme);

    $lid = RequestGetValue('lid', '');
    if (!empty($lid))
        ChangeCurrentUserLanguage($lid);

 	$sep = $gLocalPathSeparator;
	$utheme = UserGetTheme();

	$gTemplateLocalPath = $gBaseLocalPath."_theme".$sep.$utheme.$sep;
	$gTemplateUrlPath = $gHomePageUrl."/_theme/".$utheme."/";


	$gCurrentUrlPath = RequestGetValue("url_path", "");
	if (empty($gCurrentUrlPath))
		$gCurrentUrlPath = $gBaseUrlPath.$gRequestPath;


    //PrintLine($gFolderId, 'FolderId');
    //PrintLine($gRequestPath, 'RequestPath');
    //PrintLine($gCurrentUrlPath, 'CurrentUrlPath');
}

function DoShowPageWithContent($template, $fname)
{
    global $gBaseLocalPath;
    global $gWebPage;

    $fname = $gBaseLocalPath.'_lang/'.UserGetLID().'/'.$fname;
    $content = ReadLocalFile($fname, $errmsg, true);
    if (empty($content))
        $content = $errmsg;

	$gWebPage['page_content'] = WebContentParse($content);

    DoShowPage($template);
}

function DoShowPage($template)
{
    global $gWebPage;

	global $gFolderId;
    global $gPageId;

    global $gHomePageUrl;
    global $gHomePageName;
    global $gHomePageSlogan;
    global $gHomePageVisitors;
    global $gHomePageHits;
    global $gHomePageVisitedSince;

	global $gTemplateLocalPath;
	global $gCurrentUrlPath;
	global $gTemplateUrlPath;
    global $gCurrentPageNavigation;
    global $gRequestFile;
    global $gPageNavigation;

       
	$gWebPage['hp_url']             = $gHomePageUrl;
	$gWebPage['hp_name']            = $gHomePageName;
	$gWebPage['hp_motto']           = $gHomePageSlogan;
	$gWebPage['hp_visitors']        = $gHomePageVisitors;
	$gWebPage['hp_hits']            = $gHomePageHits;
	$gWebPage['hp_visited_since']   = $gHomePageVisitedSince;

	$gWebPage['folder_id']    = $gFolderId;
	$gWebPage['page_id']      = $gPageId;

	$gWebPage['url_path']     = $gCurrentUrlPath;
	$gWebPage['page_url']     = $gHomePageUrl.$gCurrentUrlPath.$gRequestFile;
	$gWebPage['rnd_number']   = Session('rand');

    if (empty($gCurrentPageNavigation))
    {
        $gCurrentPageNavigation = GetCurrentPageNavigation();
    }

	$gWebPage['current_path'] = $gCurrentPageNavigation;
	$gWebPage['theme_path']   = $gTemplateUrlPath;

	$gWebPage['lang_select']  = GetSelectLanguageText();
	$gWebPage['theme_select'] = GetSelectThemeText();

	$gWebPage['nav_home']         = _NAV_HOME;
	$gWebPage['nav_sitemap']      = _NAV_SITEMAP;
	$gWebPage['nav_feedback']     = _NAV_FEEDBACK;
	$gWebPage['nav_linktous']     = _NAV_LINKTOUS;
	$gWebPage['nav_bookmarkus']   = _NAV_BOOKMARKUS;
	$gWebPage['nav_aboutus']      = _NAV_ABOUTUS;
	$gWebPage['nav_search_tips']  = _NAV_SEARCH_TIPS;
	$gWebPage['nav_dir_help']     = _NAV_DIR_HELP;
	$gWebPage['nav_adv_search']   = _NAV_ADV_SEARCH;

	$gWebPage['nav_term_of_use']   = _NAV_TERM_OF_USE;
	$gWebPage['nav_privacy_policy']= _NAV_PRIVACY_POLICY;

	$gWebPage["you_are_here"]     = _YOU_ARE_HERE;
	$gWebPage["web_search"]       = _WEB_SEARCH;
	$gWebPage["web_search_btn"]   = _WEB_SEARCH_BTN;

    //$gWebPage['member_fullname'] = sprintf(_USER_NAME_FMT, UserGetFullName());
    //$gWebPage['member_name'] = sprintf(_USER_NAME_FMT, UserGetName());

    //$gWebPage['m_fullname'] = UserGetFullName();
    //$gWebPage['m_name'] = UserGetName();
    //$gWebPage['m_email'] = UserGetEmail();
    //$gWebPage['m_id'] = UserGetID();

	echo TemplateLoad($template);
}

function DoShowPageRedirect($url, $title, $desc, $keywords)
{
    if (StrIsStartWith($url, '\/'))
    {
        Header("Location: $url");
        die();
    }

    print "<html>
<head>
<title>$title</title>
<meta name=\"description\" content=\"$desc\">
<meta name=\"keywords\" content=\"$keywords\">
</head>
<frameset border=0 rows=\"100%,*\" frameborder=\"no\" marginleft=0 margintop=0 marginright=0 marginbottom=0>
<frame src=\"$url\" scrolling=\"auto\" frameborder=\"no\" border=\"0\">
<frame src=\"/phpmod/blank.htm\" frameborder=\"no\" scrolling=\"no\" marginwidth=\"0\" marginheight=\"0\" topmargin=\"0\" border=\"0\">
<body bgcolor=\"#ffffff\">
</body>
</frameset>
<noframes>
</noframes>
</html>";
}

function DoShowPageRedirect_2($url, $title, $desc, $keywords)
{
    print '<html><head><title></title>
<meta http-equiv="refresh" content="0; URL='.$url.'">
</head><body></body></html>';
}


function DBGetFolderData($folder_id)
{
	global $gCurrentUrlPath;
	global $gFolder;
	global $db;
    global $gReadLevel, $gWriteLevel;

	if ($folder_id >= 0)
	{
		$sql =  "select folder_lid, folder_id, folder_name, folder_label, folder_title, folder_desc, folder_keywords, 
				folder_robots, folder_sidebar, folder_parent, folder_show, folder_active, folder_order,
                read_level, write_level, upload_by, upload_on, update_on
				from web_folder where folder_id=$folder_id and folder_lid=".$db->qstr(UserGetLID());

		$rs = DbExecute($sql);
		if ($rs === false) DbFatalError("DBGetFolderData");
		if (!$rs->EOF)
		{
			$gFolder["lid"] 	= $rs->fields[0];
			$gFolder["id"] 	    = $rs->fields[1];
			$gFolder["name"] 	= $rs->fields[2];
			$gFolder["label"] 	= $rs->fields[3];
			$gFolder["title"] 	= $rs->fields[4];
			$gFolder["desc"] 	= $rs->fields[5];
			$gFolder["keywords"]= $rs->fields[6];

			$gFolder["robots"]  = $rs->fields[7];
			$gFolder["sidebar"] = $rs->fields[8];
			$gFolder["parent"]  = $rs->fields[9];
			$gFolder["show"]    = $rs->fields[10];
			$gFolder["active"]  = $rs->fields[11];
			$gFolder["order"]   = $rs->fields[12];
			$gFolder["read_level"]  = $rs->fields[13];
			$gFolder["write_level"] = $rs->fields[14];
			$gFolder["upload_by"] = $rs->fields[15];
			$gFolder["upload_on"] = $rs->fields[16];
			$gFolder["update_on"] = $rs->fields[17];

            $gReadLevel     = $gFolder["read_level"];
            $gWriteLevel    = $gFolder["write_level"];
			return;
		}
	}
			
	//error get database data or $folder_id < 0

	$gFolder["lid"]      = UserGetLID();
	$gFolder["id"]       = 0;
	$gFolder["name"]     = _NAV_FRONTPAGE;
	$gFolder["label"]    = _NAV_FRONTPAGE;
	$gFolder["title"]    = $gHomePageName;
	$gFolder["desc"]     = $gHomePageDesc;
	$gFolder["keywords"] = $gHomePageKeywords;
	$gFolder["robots"]   = "index, follow";
	$gFolder["sidebar"]  = "";
	$gFolder["parent"]   = -1;	
    $gFolder["show"]     = 1;
    $gFolder["active"]   = 1;
	$gFolder["order"]    = DEFAULT_ORDER;

	$gFolder["read_level"]    = GUEST_LEVEL;
	$gFolder["write_level"]   = WEBADMIN_LEVEL;

    $gFolder["upload_by"] = UserGetName();
    $gFolder["upload_on"] = time();
    $gFolder["update_on"] = time();

    $gReadLevel  = $gFolder["read_level"];
    $gWriteLevel = $gFolder["write_level"];
}

function DBGetFileName($folder_id, $page_id, $lid='')
{
    global $db;

    if (empty($lid))
        $lid = UserGetLID();

    $sql = "select page_name from web_page where folder_id=$folder_id and page_id=$page_id and page_lid=".$db->qstr($lid);
    return DbGetOneValue($sql, '');
}

function CreateFolderByName($folder_name, $parent_id)
{
	global $db;

	$folder_id = DbGetUniqueID('web_folder');
    $folder_title = ucwords($folder_name);

    $columns = 'folder_lid, folder_name, folder_label, folder_title, folder_id, folder_parent';
    $values  = $db->qstr(UserGetLID()).','.$db->qstr($folder_name);
    $values .= ','.$db->qstr($folder_title).','.$db->qstr($folder_title);
    $values .= ", $folder_id, $parent_id";

    if (!DbSqlInsert('web_folder', $columns, $values))
        $folder_id = -1;

    return $folder_id;
}

function CreateEmptyPage($folder_id, $name, $title)
{
    global $db;

    $page_id = DbGetUniqueID('web_page');
    $columns = 'folder_id, page_id, page_lid, page_name, page_title,  
                page_robots, page_content, page_author, page_order, page_show, page_active, 
                upload_by, upload_on, update_on';

    $values = $folder_id.',';
    $values .= $page_id.',';
    $values .= $db->qstr(UserGetLID()).',';
    $values .= $db->qstr($name).',';
    $values .= $db->qstr($title).',';
    $values .= $db->qstr(DEFAULT_ROBOTS).',';
    $values .= $db->qstr(DEFAULT_PAGE_CONTENT).',';
    $values .= $db->qstr(UserGetName()).',';

    if (strcmp($name, 'index.html') == 0)
        $values .= '1, 0, 1, ';
    else
        $values .= '1, 1, 1, ';

    $utime = date("YmdHis", time());
    $values .= $db->qstr(UserGetName()).',';
    $values .= $utime.','.$utime;

    if (!DbSqlInsert('web_page', $columns, $values))
        $page_id = -1;

    return $page_id;    
}

function GetFolderIdFromName($folder_name, $parent_id, $lid='')
{
	global $db;

	$folder_id = -1;

    if (empty($lid))
        $lid = UserGetLID();

	$sql =  "select folder_id from web_folder where folder_name=".$db->qstr($folder_name)." and folder_parent=$parent_id";
    $sql .= " and folder_lid=".$db->qstr($lid);
	
	$rs = DbExecute($sql);
	if ($rs && !$rs->EOF)
		$folder_id = $rs->fields[0];	

    return $folder_id;
}


function GetFolderIdAndLabelFromName($parent_id, $folder_name, &$folder_id, &$folder_label)
{
	global $db;

    //PrintLine('GetFolderIdAndLabelFromName');

	$folder_id = -1;
    $folder_label = '';

	$sql =  "select folder_id, folder_label from web_folder where folder_name=".$db->qstr($folder_name)." and folder_parent=$parent_id";
    $sql .= " and folder_lid=".$db->qstr(UserGetLID());
	
	$rs = DbExecute($sql);
	if ($rs && !$rs->EOF)
    {
		$folder_id      = $rs->fields[0];	
		$folder_label   = $rs->fields[1];	
    }
}

function GetFolderNameAndParent($folder_id, &$folder_name, &$parent_id, $lid='')
{
    global $db;

    if (empty($lid))
        $lid = UserGetLID();

	$sql =  "select folder_name, folder_parent from web_folder where folder_id=$folder_id";
    $sql .= " and folder_lid=".$db->qstr($lid);

	$folder_name ="";
	$parent_id = "";

	$rs = DbExecute($sql);
	if ($rs === false)
		return;

	if (!$rs->EOF)
	{
		$folder_name = $rs->fields[0];	
		$parent_id = $rs->fields[1];	
	}
}

function FolderUpdateAttr2($folder_id, $folder_lid, $folder_desc, $folder_keywords, $folder_robots)
{
    global $db;

    $colvalues .= 'folder_desc='.$db->qstr($folder_desc);
    $colvalues .= ', folder_keywords='.$db->qstr($folder_keywords);
    $colvalues .= ', folder_robots='.$db->qstr($folder_robots);

    $utime = date("YmdHis", time());
    $colvalues .= ', upload_by='.$db->qstr(UserGetName());
    $colvalues .= ', update_on='.$utime;

    $where =  'folder_lid='.$db->qstr($folder_lid);
    $where .= ' and folder_id='.$folder_id;

    return DbSqlUpdate('web_folder', $colvalues, $where);
}


function WebPageUpdateAttr2($page_id, $page_lid, $page_desc, $page_keywords, $page_robots)
{
    global $db;

    $colvalues .= 'page_desc='.$db->qstr($page_desc);
    $colvalues .= ', page_keywords='.$db->qstr($page_keywords);
    $colvalues .= ', page_robots='.$db->qstr($page_robots);

    $utime = date("YmdHis", time());
    $colvalues .= ', upload_by='.$db->qstr(UserGetName());
    $colvalues .= ', update_on='.$utime;

    $where =  'page_lid='.$db->qstr($page_lid);
    $where .= ' and page_id='.$page_id;

    return DbSqlUpdate('web_page', $colvalues, $where);
}

function ExplodeFolderPath($path)
{
	if (strncmp($path, "/", 1) == 0)
		$path = substr($path, 1, strlen($path)-1);

	return explode("/", $path); 
}

function GetParentUrlPath($path)
{
	$path = substr($path, 0, strlen($path)-1);
	//PrintLine($path, "path");

	$pos = strrpos($path, "/");
	//PrintLine($pos, "pos");

	$path = substr($path, 0, $pos+1);
	return $path;
}


function GetCurrentPageNavigation()
{
	global $gRequestPath;
	global $gHomePageUrl;
	global $gBaseUrlPath;
    global $gPageNavigation;
    global $gWebPage;
	
    if (isset($gPageNavigation) && is_array($gPageNavigation))
    {
        return LinkFromArray($gPageNavigation, "", "", " - ");
    }


	$cur_path = "/";
	$arlinks = array();
	$link = $gHomePageUrl.$gBaseUrlPath.$cur_path."index.html";
	$title = _NAV_FRONTPAGE;

	$arlinks[] = array($link, $title);

	$arreq = ExplodeFolderPath($gRequestPath);
	foreach ($arreq as $path)
	{
		if (empty($path))
			break;

		$cur_path .= $path."/";
		//PrintLine($cur_path, "cur_path");

		$link = $gHomePageUrl.$gBaseUrlPath.$cur_path."index.html";
		$title = $path;
		$arlinks[] = array($link, $title);
	}

	//$out = "<font face=\"Verdana\" size=\"2\">".LinkFromArray($arlinks, "", "", " - ")."</font>";

    //$arlinks[] = array($gWebPage['page_url'], $gWebPage['page_title']);
    //$arlinks[] = array('/members/index.html', _NAV_MEMBERS);

    $out = LinkFromArray($arlinks, "", "", " - ");

	return $out;
}

function FindOrCreateFolderFromPath($path_param)
{
    //PrintLine($path_param);
    //die();

    if ($path_param == "/")
    {
        return 0;
    }

	$folder_id = -1;
	$parent_id = 0;

	$arreq = ExplodeFolderPath($path_param);
	foreach ($arreq as $path)
	{
		if (empty($path))
			break;

		$folder_id = GetFolderIdFromName($path, $parent_id);
		if ($folder_id < 0)
        {
            $folder_id = CreateFolderByName($path, $parent_id);
            if ($folder_id > 0)
                CreateEmptyPage($folder_id, 'index.html', $path);
        }

		$parent_id = $folder_id;
	}

	return $folder_id;
}

function FindPageIdFromPath($path_param)
{
    ParseRequestPathAndFile($path_param, $path, $file_name);

    $folder_id = FindFolderIdFromPath($path, false);

    return DbGetPageIdFromName($file_name, $folder_id);
}


function FindFolderIdFromPath($path_param, $bnavi=true)
{
    global $gHomePageUrl, $gBaseUrlPath;
    global $gPageNavigation;

    //we also create a page navigation
	$cur_path = "/";

    if ($bnavi)
    {
	    $gPageNavigation = array();
    	$link = $gHomePageUrl.$gBaseUrlPath.$cur_path."index.html";
	    $gPageNavigation[] = array($link, _NAV_FRONTPAGE);
    }

    if ($path_param == "/")
    {
        return 0;
    }

	$folder_id = -1;
	$parent_id = 0;

	$arreq = ExplodeFolderPath($path_param);
	foreach ($arreq as $path)
	{
		if (empty($path))
			break;

        GetFolderIdAndLabelFromName($parent_id, $path, &$folder_id, &$folder_label);

		if ($folder_id < 0)
			break;

        //page nav
		$cur_path .= $path."/";
		$link = $gHomePageUrl.$gBaseUrlPath.$cur_path."index.html";

        if ($bnavi)
    		$gPageNavigation[] = array($link, $folder_label);

		$parent_id = $folder_id;
	}

    //$gPageNavigation[] = array('/members/index.html', _NAV_MEMBERS);

	return $folder_id;
}

function FindPathFromFolderId($folder_id, $lid='')
{
    if ($folder_id == 0)
        return "/";

	$reqpath = "/";
	$parent_id = 0;
	
	GetFolderNameAndParent($folder_id, $folder_name, $parent_id, $lid);
	$reqpath = "/".$folder_name.$reqpath;

	while ($parent_id > 0)
	{
		$folder_id = $parent_id;
		GetFolderNameAndParent($folder_id, $folder_name, $parent_id, $lid);
		$reqpath = "/".$folder_name.$reqpath;
	}

	return $reqpath;
}


// ----------------------------------------------------------------------
// Utilities Functiions
// ----------------------------------------------------------------------
function ParseRequestPathAndFile($url, &$reqPath, &$reqFile)
{
	global $gBaseUrlPath;

    $url = StrUnEscape($url);

    $url = preg_replace('|http://[a-z.A-Z:0-9]+/|mi', '/', $url);
 
	$pos = strpos($url, "/");
	$x = strlen($url);
	
	while (is_integer($pos))
	{
		$x = $pos;
		$pos = strpos($url, "/", $x+1);
	}

	$reqPath = substr($url, 0, $x+1);
    $reqFile = substr($url, $x+1);

	if (strlen($gBaseUrlPath) > 0)
	{
		$prefix = substr($reqPath, 0, strlen($gBaseUrlPath));
		if (strcasecmp($prefix, $gBaseUrlPath) == 0)
			$reqPath = substr($reqPath, strlen($gBaseUrlPath));
	}

    $pos = strpos($reqFile, "?");
    if (is_integer($pos))
        $reqFile = substr($reqFile, 0, $pos);

    $reqFile = strtolower(trim($reqFile));
    if (empty($reqFile))
        $reqFile = "index.html";
}


function ChangeCurrentUserLanguage($lid)
{
    global $gBaseLocalPath;
    global $gLocalPathSeparator;

	$sep = $gLocalPathSeparator;

	$chkFile = $gBaseLocalPath.'_lang'.$sep.$lid.$sep.'global.php';
    //PrintLine($chkFile, "chkFile");
    //die();

    if (file_exists($chkFile))
    {
        //Change language successfull
        //PrintLine("Change Language Success");

        SessionSetValue('lid', $lid);
        if (UserGetLevel() == GUEST_LEVEL)
        {
            SessionDelete('uname');
            SessionDelete('ufname');
        }
    }
}

function ChangeCurrentUserTheme($theme)
{
    global $gBaseLocalPath;
    global $gLocalPathSeparator;

	$sep = $gLocalPathSeparator;

	$chkFile = $gBaseLocalPath.'_theme'.$sep.$theme.$sep.TPL_WEB_PAGE;
    //PrintLine($chkFile, "chkFile");

    if (file_exists($chkFile))
    {
        //Change theme successfull
        //PrintLine("Change Theme Success");

        SessionSetValue('theme', $theme);
    }
}

function GetSelectLanguageText()
{
    global $gFolderId, $gPageId;
    global $gLanguageList;
    global $gHomePageUrl,$gCurrentUrlPath,$gRequestFile;

    //$path = $gCurrentUrlPath$gRequestFile;
    $path = StrEscape($_SERVER["REQUEST_URI"]);

    $out = '';

    if (isset($gLanguageList) && is_array($gLanguageList) && count($gLanguageList) > 1)
    {
        $out .= '<select name="sellid" onchange="changePage(this.form.sellid)" class="inputbox">';
        $out .= '<option value = "" selected>'._LANG_CHANGE.'</option>';

        foreach($gLanguageList as $lid => $lang)
        {
            //$out .= "<option value = \"/phpmod/lang_chg.php?lid=$lid&cat=$gFolderId&id=$gPageId\">$lang</option>";
            $out .= "<option value = \"/phpmod/lang_chg.php?lid=$lid&path=$path\">$lang</option>";
        }

        $out .= '</select>';
    }

    return $out;
}

function GetSelectThemeText()
{
    global $gFolderId, $gPageId;
    global $gThemeList;
    global $gHomePageUrl,$gCurrentUrlPath,$gRequestFile;

    //$path = $gCurrentUrlPath$gRequestFile;
    $path = StrEscape($_SERVER["REQUEST_URI"]);

    $out = '';

    if (isset($gThemeList) && is_array($gThemeList) &&  count($gThemeList) > 1)
    {
        $out .= '<select name="seltheme" onchange="changePage(this.form.seltheme)" class="inputbox">';
        $out .= '<option value = "" selected>'._THEME_CHANGE.'</option>';

        foreach($gThemeList as $theme)
        {
            //$out .= "<option value = \"/phpmod/theme_chg.php?t=$theme&cat=$gFolderId&id=$gPageId\">$theme</option>";
            //$out .= '<option value = "/phpmod/theme_chg.php?t='.$theme.'&cat='.$gFolderId.'"> '.$theme.' </option>';
            $out .= "<option value = \"/phpmod/theme_chg.php?t=$theme&path=$path\">$theme</option>";
        }
        
        $out .= '</select>';
    }

    return $out;
}

function GetHomePageData()
{
    global $gHomePageName;
    global $gHomePageSlogan;
    global $gHomePageDesc;
    global $gHomePageKeywords;
    global $gHomePageVisitors;
    global $gHomePageHits;
    global $gHomePageVisitedSince;
    global $gHomePageHeader;
    global $gHomePageFooter;
    global $gHomePageSidebar;
    global $gSysVar, $gSysVarInt;

    //ReadSystemVarTable();
    ReadSystemVarIntTable();

    $gHomePageName      = $gSysVar['hp_name'];
    $gHomePageSlogan    = $gSysVar['hp_slogan'];
    $gHomePageDesc      = $gSysVar['hp_desc'];
    $gHomePageKeywords  = $gSysVar['hp_keywords'];

	$gHomePageHeader    = $gSysVar['hp_header'];
	$gHomePageFooter    = $gSysVar['hp_footer'];
	$gHomePageSidebar   = $gSysVar['hp_sidebar'];

	$gHomePageVisitors  = $gSysVarInt['hp_visitors'];
	$gHomePageHits      = $gSysVarInt['hp_hits'];
    $tmVisitedSince     = $gSysVarInt['hp_visited_since'];

    $gHomePageVisitedSince = date("M, d Y", $tmVisitedSince);
}

function ReadSystemVarTable()
{
    global $gSysVar;

    $gSysVar = array();
    
    $sql = "select var_key, var_data from sysvar order by var_key";
    $rs = DbExecute($sql);

    if ($rs && !$rs->EOF)
    {
        while (!$rs->EOF)
        {
            $gSysVar[$rs->fields[0]] = $rs->fields[1];
            $rs->MoveNext();
        }
    }
}

function ReadSystemVarIntTable()
{
    global $gSysVarInt;

    $gSysVarInt = array();
    
    $sql = "select var_key, var_data from sysvarint order by var_key";
    $rs = DbExecute($sql);

    if ($rs && !$rs->EOF)
    {
        while (!$rs->EOF)
        {
            $gSysVarInt[$rs->fields[0]] = $rs->fields[1];
            $rs->MoveNext();
        }
    }
}

function RedirectToPreviousPage()
{
    global $gHomePageUrl;

    $referer = $_SERVER['HTTP_REFERER'];
    if (strncasecmp($gHomePageUrl, $referer, strlen($gHomePageUrl)) == 0)
    {
        $url = $referer;
        $pos = strpos($url, '?');
        if (is_integer($pos))
            $url = substr($url, 0, $pos);
    }
    else
    {
        //from outside or empty referer. Jump to our homepage
        $url = $gHomePageUrl;
    }

    //PrintLine("RedirectToPreviousPage: $url");
	Header("Location: $url");
	die();
}


function WebPageNotFound($filename='')
{
    global $gWebPage;
    global $gRequestPath, $gRequestFile;
    global $gHomePageHeader, $gHomePageFooter;
    global $gBaseUrlPath, $gCurrentUrlPath;
	global $gFolderId;

    if (StrIsStartWith($gRequestPath, '\/phpmod\/') or StrIsStartWith($gRequestPath,'\/members\/'))
    {
        $gRequestPath = '/'; 
		$gRequestFile = 'index.html';
        $gCurrentUrlPath = $gBaseUrlPath.$gRequestPath;
		$gFolderId = 0;
    }

    if (empty($filename))
        $filename = $gRequestPath.$gRequestFile;

    $message = sprintf(_PAGE_NOTFOUND_MESSAGE, $filename);
    if (IsUserAdmin())
        $message .= sprintf(_PAGE_EDIT_MESSAGE, $filename.'?op=edit');


    $gWebPage['page_notfound_title'] = _PAGE_NOTFOUND_TITLE;
    $gWebPage['page_notfound_message'] = $message;

    $gWebPage['page_title']     = _PAGE_NOTFOUND_TITLE;
    $gWebPage['page_sidebar']   = RenderPageSidebar();
    $gWebPage['page_header']    = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer']    = WebContentParse($gHomePageFooter);

    $gWebPage['page_desc']    = '';
    $gWebPage['page_keywords']    = '';

    DoShowPageWithContent(TPL_WEB_PAGE, 'wpage_notfound.htm');
    die();
}

function WebPageError($title, $message)
{
    global $gWebPage;
    global $gRequestPath, $gRequestFile;
    global $gHomePageHeader, $gHomePageFooter;
    global $gBaseUrlPath, $gCurrentUrlPath;
	global $gFolderId;

    if (StrIsStartWith($gRequestPath, '\/phpmod\/') or StrIsStartWith($gRequestPath,'\/members\/'))
    {
        $gRequestPath = '/'; 
		$gRequestFile = 'index.html';
        $gCurrentUrlPath = $gBaseUrlPath.$gRequestPath;
		$gFolderId = 0;
    }


    $gWebPage['page_title'] = $title;
    $gWebPage['page_message'] = $message;

    $gWebPage['page_title']     = $title;
    $gWebPage['page_sidebar']   = RenderPageSidebar();
    $gWebPage['page_header']    = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer']    = WebContentParse($gHomePageFooter);

    $gWebPage['page_desc']    = '';
    $gWebPage['page_keywords']    = '';

    DoShowPageWithContent(TPL_WEB_PAGE, 'wpage_error.htm');
    die();
}



function RenderPageAuthor($uname)
{
    $out = RenderAuthorByName($uname);
    if (empty($out))
        $out = $uname;

    $out = '<div id="author">'._AUTHOR.':&nbsp;'.$out.'</div>';;
    return $out;
}

function RenderAuthorByID($uid)
{
    global $db;

    $out = "";

    $sql = "select m_name, m_fullname, m_email, m_view_email from sysmember where m_id = $uid";

    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        $fullname   = $rs->fields[1];
        if (empty($fullname))
            $fullname = $rs->fields[0];

        $email      = $rs->fields[2];
        $view_email = $rs->fields[3];

        if ($view_email > 0)
        {
            $out = HRef("mailto:".$email, $fullname);
        }
        else
        {
            $out = $fullname;
        }
    }

    return $out;
}

function RenderAuthorByName($uname)
{
    $minfo = MemberGetInfo(0, $uname);

    if ($minfo)
    {
        $fullname   = $minfo['m_fullname'];
        if (empty($fullname))
            $fullname = $minfo['m_name'];

        $email      = $minfo['m_email'];
        $view_email = $minfo['m_view_email'];

        if ($view_email > 0)
        {
            $out = HRef("mailto:".$email, $fullname);
        }
        else
        {
            $out = $fullname;
        }
    }

    return $out;
}

function RenderPageSource($src_title, $src_url, $src_home, $src_homeurl)
{
    $doc    = "<a href=\"$src_url\">$src_title</a>";
    $dochp  = "<a href=\"$src_homeurl\">$src_home</a>";
    $txt    = sprintf(_PAGE_SOURCE_FMT, $doc, $dochp);

    $out = "<div id=footnote>$txt</div>";

    return $out;
}

function RenderPageSidebar()
{
    global $gFolder;
    global $gHomePageSidebar;

    $out = $gFolder['sidebar'];
    if (empty($out))
        $out = $gHomePageSidebar;

    return WebContentParse($out);
}

function AddPageRedirect($parent_id, $page_name, $page_redirect, $page_title='', $page_desc='', $page_keywords='')
{
    global $db;
    global $gLanguageList;

    $page_id     = DbGetUniqueID('web_page');

    if (empty($page_title))
        $page_title  = GetPageTitleFromName($page_name);

    $page_author = UserGetName();
    $upload_on   = date("YmdHis", time());

    if (isset($gLanguageList) && is_array($gLanguageList))
    {
        foreach($gLanguageList as $lid => $lang)
        {
            $columns = 'folder_id, page_id, page_lid, page_name, page_title, page_desc, page_keywords, page_redirect, page_author, upload_by, upload_on, update_on';
            $values  = "$parent_id, $page_id, ".$db->qstr($lid);
            $values .= ','.$db->qstr($page_name).','.$db->qstr($page_title);
            $values .= ','.$db->qstr($page_desc).','.$db->qstr($page_keywords);
            $values .= ','.$db->qstr($page_redirect);
            $values .= ','.$db->qstr($page_author).','.$db->qstr($page_author);
            $values .= ",$upload_on, $upload_on";

            if (!DbSqlInsert('web_page', $columns, $values))
                return false;
        }
        return true;
    }

    return false;
}

function GetPageTitleFromName($page_name)
{
    $page_title = substr($page_name, 0, strlen($page_name)-5);
    $page_title = ucwords(str_replace('_', ' ', $page_title));
    return $page_title;
}

function IsWebPageExist($url)
{
    global $db;

	ParseRequestPathAndFile($url, $path, $file);

    $path = strtolower($path);
    if ($path = 'members' || $path = 'phpmod')
        return true;

    //PrintLine($path, 'Path');
    //PrintLine($file, 'FileName');
    //die();

    $folder_id = FindFolderIdFromPath($path, false);
    if ($folder_id < 0)
        return false;

    $page_id = DbGetPageIdFromName($file, $folder_id);
    return $page_id>0;
}

function DbGetPageNameFromId($page_id)
{
    global $db;

    $sql = "select page_name from web_page where page_id=$page_id";
    return DbGetOneValue($sql, '');
}

function DbGetPageIdFromName($page_name, $folder_id, $page_lid='')
{
    global $db;

    if (empty($page_lid))
        $page_lid = UserGetLID();

    $sql = "select page_id from web_page where folder_id=$folder_id and page_name=".$db->qstr($page_name)." and page_lid=".$db->qstr($page_lid);
    return DbGetOneValue($sql, 0);
}

function DbGetPageTitleFromName($page_name, $folder_id, $page_lid='')
{
    global $db;

    if (empty($page_lid))
        $page_lid = UserGetLID();

    $sql = "select page_title from web_page where folder_id=$folder_id and page_name=".$db->qstr($page_name)." and page_lid=".$db->qstr($page_lid);
    return DbGetOneValue($sql, '');
}

function GetParentPath($path)
{
    $path = substr($path, 0, strlen($path)-1);

    $pos = strpos($path, '/');
    $xpos = 0;
    while (is_integer($pos))
    {
        $xpos = $pos;
        $pos = strpos($path, '/', $pos+1);
    }

    return substr($path, 0, $xpos+1);
}

function CheckRequestRandom()
{
    $rnd = RequestGetValue('rnd', 0);
    $sesrnd = Session('rand');

    if (($rnd == 0) || ($rnd != $sesrnd))
    {
        //PrintLine($rnd);
        //PrintLine($sesrnd);
        WebPageError(_ERR_OPR_DENIED_TITLE, _ERR_NOT_USE_BROWSER);
    }
}

function RenderTextArea($name, $value, $cols, $rows)
{
    return "<textarea class=\"inputbox\" rows=\"$rows\" cols=\"$cols\" name=\"$name\">$value</textarea>";
}


function RenderHtmlEditor($name, $value, $toolbar, $width=550, $height=90)
{
    global $spaw_root, $spaw_dir;
    global $spaw_dropdown_data;
    global $gBaseLocalPath;
    global $gTemplateUrlPath;

    $spaw_root = $gBaseLocalPath.'spaw/';
    $spaw_root = str_replace('\\', '/', $spaw_root);

    //PrintLine($spaw_root);die();

    // include the control file
    require_once($spaw_root.'spaw_control.class.php');

    // make a copy of $spaw_dropdown_data array
    //$editor_data = $spaw_dropdown_data;
    // unset current styles
    //unset($editor_data['style']);

    // set new styles
    //$editor_data['style']['default'] = 'Default';
    //$editor_data['style']['quote'] = 'Quote';
    //$editor_data['style']['crazystyle2'] = 'Crazy style no. 2';
    //$editor_data['style']['crazystyle3'] = 'Crazy style no. 3';

    $css_file = $gTemplateUrlPath.'editor.css';

    // pass $demo_array to the constructor
    //$sw = new SPAW_Wysiwyg($name, $value, 'en', $toolbar, 'default', $width.'px', $height.'px', $css_file, $editor_data);

    $sw = new SPAW_Wysiwyg($name, $value, 'en', $toolbar, 'default', $width.'px', $height.'px', $css_file, '');
    return $sw->getHtml();
}


function RenderWebPageHitsTableFromRS($rs)
{
    $out = '';

    if ($rs && !$rs->EOF)
    {
        $total_hits = 0;
        $out .= '<div align="center">
  <center>
<table border="1" cellspacing="0" cellpadding="2" bordercolor="#FFFFFF" bordercolorlight="#C0C0C0" bordercolordark="#FFFFFF" width="90%">
  <tr>
    <td id="tbl_title" height="24">'._FLD_ID.'</td>
    <td id="tbl_title" height="24">'._FLD_NAME.'</td>
    <td id="tbl_title" height="24">'._FLD_TITLE.'</td>
    <td id="tbl_title" height="24">'._FLD_DESCRIPTION.'</td>
    <td id="tbl_title" height="24">'._FLD_HITS.'</td>
  </tr>';

        while (!$rs->EOF)
        {
            $url_edit = '/phpmod/find_page.php?op=edit&cat='.$rs->fields[0].'&name='.$rs->fields[2];
            $url_show = '/phpmod/find_page.php?op=show&cat='.$rs->fields[0].'&name='.$rs->fields[2];

            $out .= '<tr>
    <td id="tbl_text" valign="top" align="right">'.HRef($url_edit, $rs->fields[1]).'</td>
    <td id="tbl_text" valign="top" align="left">'.HRef($url_show, $rs->fields[2]).'</td>
    <td id="tbl_text" valign="top" align="left">'.$rs->fields[3].'&nbsp;</td>
    <td id="tbl_text" valign="top" align="left">'.$rs->fields[4].'&nbsp;</td>
    <td id="tbl_text" valign="top" align="right">'.$rs->fields[5].'</td>
  </tr>';

            $total_hits += $rs->fields[5];

            $rs->MoveNext();
        }

            $out .= '<tr>
    <td id="tbl_bottom" valign="middle" align="right" colspan="4" height="20"><b>TOTAL
      HITS</b></td>
    <td id="tbl_bottom" valign="middle" align="right" height="20"><b>'.$total_hits.'</b></td>
  </tr>
</table></center></div>';


    }
    else
    {
        $out = _FLD_EMPTY;
    }

    return $out;
}

function CheckOpForAdminOnly()
{
    if (!IsUserAdmin())
	{
        WebPageError(_UNAUTHORISIZED_ACCESS_TITLE, _UNAUTHORISIZED_ACCESS_MESSAGE);
	}
}

function CheckOpForMemberOnly()
{
    if (!IsUserLogin())
        WebPageError(_MEMBER_ONLY_TITLE, _MEMBER_ONLY_MESSAGE);
}

function HRefMember($name, $fullname, $email, $homepage, $view_email, $view_profile)
{
    if ($view_profile)
        return "<a href=\"/members/".$name.".html\">".$fullname."</a>";

    if ($view_email)
        return "<a href=\"mailto:".$email."\">".$fullname."</a>";

    if (!empty($homepage))
        return "<a href=\"".$homepage."\">".$fullname."</a>";

    return $fullname;
}


function RenderHelpBox($url_help)
{
    if (empty($url_help))
        return '';
    else
        return '
<table bordercolor="#CC9900" bordercolorlight="#CC9900" bordercolordark="#CC9900" bgcolor="#FFFFEB" cellspacing="0" cellpadding="2" border="1" align="right">
  <tr>
    <td valign="middle"><a href="'.$url_help.'">'._NAV_HELP.'</a>&nbsp;<a href="'.$url_help.'"><img border="0" src="/images/smicons/question.gif" width="16" height="16" align="absmiddle"></a></td>
  </tr>
</table>';

}

function GetUrlFromFolderIdAndPageName($folder_id, $page_name, $lid='')
{
    $path = FindPathFromFolderId($folder_id, $lid);

    return $path.$page_name;
}


?>
