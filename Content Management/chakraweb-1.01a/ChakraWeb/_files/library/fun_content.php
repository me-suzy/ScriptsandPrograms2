<?php
// ----------------------------------------------------------------------
// ModName: fun_content.php
// Purpose: Processing macro for content.
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_LIBRARY')) 
    die ("You can't access [fun_content.php] file directly...");

function WebContentParse($content)
{
    if (!empty($content))
        $content = preg_replace_callback("'{.*?}'mi", 'WebContentParseCallBack', $content);
    
    $content = SmileyParse($content);
    return $content;
}

function WebContentParseCallBack($match)
{
    global $gWebPage;
    global $gHomePageUrl;
    global $gHomePageName;
    global $gHomePageSlogan;
    global $gHomePageDesc;
    global $gHomePageKeywords;
    global $gHomePageVisitors;
    global $gHomePageHits;
    global $gHomePageVisitedSince;
    global $gCurrentUrlPath;
    global $gSysVar, $gSysVarInt;
    global $gMgmtMenu;

    $str = $match[0];
    if (strlen($str) > 2)
        $str = substr($str, 1, strlen($str)-2);

    if (StrIsStartWith($str, "~"))
        return '&#'.'123;'.substr($str, 1, strlen($str)-1).'&#'.'125;';

    $params = '';
    $pos = strpos($str, ':');
    if (is_integer($pos))
    {
        $params = substr($str, $pos+1);
        $str    = substr($str, 0, $pos);
    }

    $str = trim(strtolower($str));

    //PrintLine($str,   "Token");
    //PrintLine($params,"Params");

    switch ($str)
    {
    case 'powered_by_chakraweb':
        $str = _POWERED_BY_CHAKRAWEB;
	    break;

    case 'made_by_chakraweb':
        $str = _MADE_BY_CHAKRAWEB;
	    break;

    case 'img':
        $str = RenderImage('center', $params);
	    break;

    case 'img_left':
        $str = RenderImage('left', $params);
	    break;

    case 'img_center':
        $str = RenderImage('center', $params);
	    break;

    case 'img_right':
        $str = RenderImage('right', $params);
	    break;

    case 'style':
        $str = RenderTextStyle($params);
	    break;

    case 'search_form':
        $str = RenderSearchForm();
        break;

    case 'advtext':
        $str = RenderAdvText($params);
        break;

    case 'advrnd':
        $str = RenderAdvRandom($params);
        break;

    case 'hp_name':
        $str = $gHomePageName;
	    break;

    case 'hp_url':
        $str = $gHomePageUrl;
	    break;

    case 'hp_slogan':
    case 'hp_motto':
        $str = $gHomePageSlogan;
	    break;

    case 'hp_desc':
        $str = $gHomePageDesc;
	    break;

    case 'hp_keywords':
        $str = $gHomePageKeywords;
	    break;

    case 'hp_visitors':
        $str = $gHomePageVisitors;
	    break;

    case 'hp_hits':
        $str = $gHomePageHits;
	    break;

    case 'hp_visited_since':
        $str = $gHomePageVisitedSince;
	    break;

    case 'hp_intvar':
        $str = $gSysVarInt[$params];
	    break;

    case 'hp_var':
        $str = $gSysVar[$params];
	    break;

    case 'see_also':
        $str = RenderSeeAlso($params);
	    break;

    case 'page_title':
        $str = $gWebPage['page_title'];
	    break;

    case 'page_desc':
        $str = $gWebPage['page_desc'];
	    break;

    case 'page_author':
        $str = $gWebPage['page_author'];
	    break;

    case 'page_source':
        $str = $gWebPage['page_source'];
	    break;

    case 'author_profile':
        $str = RenderAuthorProfile($gWebPage['page_author_db'], $params);
	    break;

    case 'subfolder_menu':
        $str = RenderSubFolderAsMenu($params);
	    break;

    case 'subfolder_list':
        $str = RenderSubFolderAsList($params);
	    break;

    case 'file_menu':
        $str = RenderFileAsMenu('page_order, page_title', $params);
	    break;

    case 'file_list':
        $str = RenderFileAsList('file-list', 'page_order, page_title', $params);
	    break;

    case 'file_list2':
        $str = RenderFileAsList2('file-list', 'page_order, page_title', $params);
	    break;

    case 'file_include':
        $str = RenderFileInclude($params);
	    break;

    case 'article_menu':
        $str = RenderFileAsMenu('page_order, update_on desc', $params);
	    break;

    case 'article_list':
        $str = RenderArticleAsList($params);
	    break;

    case 'member_articles':
        $str = RenderMemberArticleAsList($params);
	    break;

    case 'faq_list':
        $str = RenderFileAsList('faq-list', 'page_order, page_title', $params);
	    break;

    case 'faq_list2':
        $str = RenderFileAsList2('faq-list', 'page_order, page_title', $params);
	    break;

    case 'mgmt_menu':
        if ($gMgmtMenu && IsUserCanWrite())
            $str = RenderManagementMenu();
        else
            $str = "";
	    break;

    case 'login_form':
        $str = RenderLoginForm();
        break;

    case 'redirect_form':
        $str = IsUserAdmin() ? RenderRedirectForm() : '';
        break;

    case 'redirect_table':
        $str = IsUserAdmin() ? RenderRedirectTable() : '';
        break;

    case 'comment_form':
        $str = RenderCommentForm($params);
        break;

    case 'comment_list':
        $str = RenderCommentList($params);
        break;

    case 'news_list':
        $str = RenderNewsList($params);
        break;

    case 'news_list2':
        $str = RenderNewsList2($params);
        break;

    case 'news_box':
        $str = RenderNewsOnTheBox($params);
        break;

    case 'news_form':
        $str = IsUserAdmin() ? RenderNewsForm() : '';
        break;

    case 'feedback_list':
        $str = RenderFeedbackList($params);
        break;

    case 'feedback_box':
        $str = RenderFeedbackOnTheBox($params);
        break;

    case 'feedback_form':
        $str = RenderFeedbackForm();
        break;

    case 'link_list':
        $str = RenderLinkList($params);
        break;

    case 'link_list2':
        $str = RenderLinkList2($params);
        break;

    case 'link_form':
        $str = RenderLinkForm();
        break;

    case 'macro':
        $str = RenderMacroText($params);
        break;

    case 'weblog_list':
        $str = RenderFileAsList('file-list', 'page_order, page_title', $params);
	    break;

    case 'member_name':
        $str = sprintf(_USER_NAME_FMT, UserGetName());
	    break;

    case 'member_fullname':
        $str = sprintf(_USER_NAME_FMT, UserGetFullName());
	    break;

    case 'm_name':
        $str = UserGetName();
	    break;

    case 'm_fullname':
        $str = UserGetFullName();
	    break;

    case 'm_email':
        $str = UserGetEmail();
	    break;

    case 'm_id':
        $str = UserGetID();
	    break;

    case 'rating_form':
        $str = RenderRatingForm();
	    break;

    case 'sitemap':
        $str = RenderSiteMap($params);
	    break;

    case 'smiley_table':
        $str = RenderSmileyTable();
	    break;

    default:
        //not change unknown sintax
        $str = $match[0];
	    break;
    }


    return $str;
}

function RenderTextStyle($params)
{
    list ($style, $text) = explode(':', $params);
    if (StrIsStartWith($text, '\$'))
    {
        $text = substr($text, 1, strlen($text)-1);
        $text = WebContentParseCallBack(array('{'.$text.'}'));
    }

    return '<div id="'.$style.'"><p>'.$text."</p></div>\n";
}


function RenderSearchForm()
{
    $out  = "<table cellpadding=0 cellspacing=0 border=0><tr><form name=f method=\"GET\" action=\"/phpmod/search.php\"><td>";
    $out .= "<div id=title>"._WEB_SEARCH."</div>";
    $out .= "<div id=content>";
    $out .= "<input class=\"inputbox\" type=\"text\" name=\"q\" size=\"12\" value=\"".RequestGetValue('q')."\">";
    $out .= " <input class=\"button\" type=\"submit\" value=\""._WEB_SEARCH_BTN."\">";
    $out .= "</div></td></form></tr></table>";

    return $out;
}


function RenderSubFolderAsMenu($parent_path)
{
    global $db;
    global $gFolderId;
    global $gFolder;
    global $gCurrentUrlPath;

    if (empty($parent_path))
    {
        $base_path    = $gCurrentUrlPath;
        $parent_id    = $gFolderId;
        $parent_label = $gFolder['label'];
    }
    else
    {
        list($base_path, $parent_label) = explode(':', $parent_path);
        if (empty($parent_label))
            $parent_label = _MAIN_SUBFOLDER;

        $parent_id  = FindFolderIdFromPath($base_path, false);
    }

	$sbtitle = $parent_label;
	if (empty($sbtitle))
		$sbtitle = _MAIN_SUBFOLDER;

    if ($parent_id <= 0)
	{
		$parent_id = 0;	
		$base_path = '/';
	}


    $out = "";

    $lid = UserGetLID();
    $sql = "select folder_name, folder_label from web_folder where folder_parent=$parent_id 
            and folder_lid=".$db->qstr($lid);
    if (!IsUserAdmin())
        $sql .= " and folder_show=1 and folder_active=1";
    $sql .= " and read_level <= ".UserGetLevel()." order by folder_order, folder_title";

    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        $out .= "<div id=\"sbnav\">\n";
        $out .= "<div class=\"title\">".$sbtitle."</div>\n";

        while (!$rs->EOF)
        {
            $folder_name = $rs->fields[0];

            $url = $base_path.$folder_name."/index.html";
            $title = $rs->fields[1];

            $out .= "<a class=\"lnk\" href=\"$url\">$title</a>\n";
            $rs->MoveNext();
        }

        //if (empty($parent_path))
        //    $out .= "<a class=\"lnk\" href=\"/members/index.html\">"._NAV_MEMBERS."</a>\n";

        $out .= "</div>\n";
        $out .= "<div id=\"sbspace\"></div>\n";
    } 

    return $out;
}


function RenderSubFolderAsList($params)
{
    global $db;
    global $gFolderId;
    global $gFolder;

    if (empty($params))
    {
        $base_path    = $gCurrentUrlPath;
        $parent_id    = $gFolderId;
        $title = '';
    }
    else
    {
        list ($base_path, $title) = explode(':', $params);
        $parent_id    = FindFolderIdFromPath($base_path, false);
    }

    if ($parent_id <= 0)
	{
		$parent_id = 0;	
		$base_path = '/';
	}

    $out = "";

    $lid = UserGetLID();
    $sql = "select folder_name, folder_title, folder_desc from web_folder where folder_parent=$parent_id 
            and folder_lid=".$db->qstr($lid);
    if (!IsUserAdmin())
        $sql .= " and folder_show=1 and folder_active=1";
    $sql .= " and read_level <= ".UserGetLevel();
    $sql .= " order by folder_order, folder_title";

    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        if (!empty($title))
            $out .= "<div id=\"section-title\">$title</div>\n";

        $out .= "<div id=\"subfolder-list\"><dl>\n";
        while (!$rs->EOF)
        {
            $url = $base_path.$rs->fields[0]."/index.html";
            $out .= "<dt><div class=\"title\"><a href=\"$url\">".$rs->fields[1]."</a></div></dt>\n";
            $out .= "<dd>".$rs->fields[2]."</dd>\n";
            $rs->MoveNext();
        }
        $out .= "</dl></div>\n";
    } 

    return $out;
}

function RenderFileAsMenu($order, $params)
{
    global $db;
    global $gFolder;
    global $gFolderId;

    if (empty($params))
    {
        $base_path    = $gCurrentUrlPath;
        $folder_id    = $gFolderId;
        $title 		  = $gFolder['label'];
    }
    else
    {
        list ($base_path, $title) = explode(':', $params);
        $folder_id    = FindFolderIdFromPath($base_path, false);
    }

    if ($folder_id <= 0)
	{
		$folder_id = 0;	
		$base_path = '/';
	}

	if (empty($title))
		$title = _MAIN_SUBFOLDER;

    $lid = UserGetLID();

    $sql = "select page_name, page_title from web_page where folder_id=$folder_id 
            and page_lid=".$db->qstr($lid);
    if (!IsUserAdmin())
        $sql .= " and page_show=1 and page_active=1";
    $sql .= " order by $order";

    $rs = DbExecute($sql);

    return RenderFileAsMenuFromRS($rs, $base_path, $title);
}

function RenderFileAsMenuFromRS($rs, $base_path, $title)
{
    $out = "";

    if ($rs && !$rs->EOF)
    {
        $out .= "<div id=\"sbnav\">\n";
		if (!empty($title))
	        $out .= "<div class=\"title\">".$title."</div>\n";

        while (!$rs->EOF)
        {
            $fname = $rs->fields[0];
            if (strcasecmp($fname, 'index.html') != 0)
                $out .= "<a class=\"lnk\" href=\"$base_path$fname\">".$rs->fields[1]."</a>\n";
            $rs->MoveNext();
        }
        $out .= "</div>\n";
        $out .= "<div id=\"sbspace\"></div>\n";
    } 

    return $out;
}

function RenderFileAsList($style, $order, $params)
{
    global $db;
    global $gFolderId;
    global $gFolder;

    if (empty($params))
    {
        $base_path    = $gCurrentUrlPath;
        $folder_id    = $gFolderId;
        $title 		  = '';
    }
    else
    {
        list ($base_path, $title) = explode(':', $params);
        $folder_id    = FindFolderIdFromPath($base_path, false);
    }

    if ($folder_id <= 0)
	{
		$folder_id = 0;	
		$base_path = '/';
	}

    $out = "";

    $lid = UserGetLID();
    $sql = "select page_name, page_title, page_desc from web_page where folder_id=$folder_id 
            and page_lid=".$db->qstr($lid);
    if (!IsUserAdmin())
        $sql .= " and page_show=1 and page_active=1";
    $sql .= " order by $order";

    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        if (!empty($title))
            $out .= "<div id=\"section-title\">$title</div>\n";

        $out .= "<div id=\"$style\"><dl>\n";

        while (!$rs->EOF)
        {
            $fname = $rs->fields[0];
            if (strcasecmp($fname, 'index.html') != 0)
            {
                $out .= "<dt><div class=\"title\"><a href=\"".$base_path.$fname."\">".$rs->fields[1]."</a></div></dt>\n";
                $out .= "<dd>".$rs->fields[2]."</dd>\n";
            }
            $rs->MoveNext();
        }
        $out .= "</dl></div>\n";
    } 

    return $out;
}

function RenderFileAsList2($style, $order, $params)
{
    global $db;
    global $gFolderId;
    global $gFolder;

    if (empty($params))
    {
        $base_path    = $gCurrentUrlPath;
        $folder_id    = $gFolderId;
        $title 		  = '';
    }
    else
    {
        list ($base_path, $title) = explode(':', $params);
        $folder_id    = FindFolderIdFromPath($base_path, false);
    }

    if ($folder_id <= 0)
	{
		$folder_id = 0;	
		$base_path = '/';
	}

    $out = "";

    $lid = UserGetLID();
    $sql = "select page_name, page_title from web_page where folder_id=$folder_id 
            and page_lid=".$db->qstr($lid);
    if (!IsUserAdmin())
        $sql .= " and page_show=1 and page_active=1";
    $sql .= " order by $order";

    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        $out .= "<div id=\"$style\"><ul>\n";

        while (!$rs->EOF)
        {
            $fname = $rs->fields[0];
            if (strcasecmp($fname, 'index.html') != 0)
            {
                $out .= "<li><a href=\"$base_path$fname\">".$rs->fields[1]."</a></li>\n";
            }
            $rs->MoveNext();
        }
        $out .= "</ul></div>\n";
    } 

    return $out;
}

function RenderArticleAsList($params)
{
    global $db;
    global $gFolderId;
    global $gFolder;

    if (empty($params))
    {
        $base_path    = $gCurrentUrlPath;
        $folder_id    = $gFolderId;
        $title 		  = '';
    }
    else
    {
        list ($base_path, $title) = explode(':', $params);
        $folder_id    = FindFolderIdFromPath($base_path, false);
    }

    if ($folder_id <= 0)
	{
		$folder_id = 0;	
		$base_path = '/';
	}

    $out = "";

    $lid = UserGetLID();
    $sql = "select page_name, page_title, page_desc, page_author, page_rating, page_votes, page_hits, 
            update_on from web_page where folder_id=$folder_id and page_lid=".$db->qstr($lid);
    if (!IsUserAdmin())
        $sql .= " and page_show=1 and page_active=1";
    $sql .= " order by page_order, update_on desc";

    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        if (!empty($title))
            $out .= "<div id=\"section-title\">$title</div>\n";

        $out .= "<div id=\"article-list\"><dl>\n";

        while (!$rs->EOF)
        {
            $fname = $rs->fields[0];
            if (strcasecmp($fname, 'index.html') != 0)
            {
                $tm_update = $rs->fields[7];

                $rating = sprintf("%01.2f", $rs->fields[4]);
                $author_by = RenderAuthorByName($rs->fields[3]);
                if (!empty($author_by))
                    $author_by = ' <font face=arial size=2>'._AUTHOR_BY.': '.$author_by.'</font>';

                $desc = $rs->fields[2];//.$author_by;
                if (!empty($desc))
                    $desc .= "<br>";

                $out .= "<dt><div class=\"title\"><a href=\"$base_path$fname\">".$rs->fields[1]."</a>".$author_by;
                $out .= "</div></dt>\n";
                $out .= "<dd>".$desc;
                $out .= "<font face=arial size=1> "._PAGE_UPDATE_ON.': '.$tm_update;
                $out .= ". "._PAGE_RATING.": ".$rating." "._PAGE_VOTE_BY.": ".$rs->fields[5];
                $out .= ". "._PAGE_HITS.": ".$rs->fields[6];
                $out .= "</font></dd>\n";
            }
            $rs->MoveNext();
        }
        $out .= "</dl></div>\n";
    } 

    return $out;
}

function RenderMemberArticleAsList($params)
{
    global $db;
    global $gFolderId;
    global $gFolder;

    list ($author, $max) = explode(':', $params);

    $out = "";

    $lid = UserGetLID();
    $sql = "select folder_id, page_name, page_title, page_desc, page_author, page_rating, page_votes, page_hits, 
            update_on, page_lid from web_page where page_author=".$db->qstr($author);
    if (!IsUserAdmin())
        $sql .= " and page_active=1";
    $sql .= " order by page_order, update_on desc limit 0,".$max;

    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        $out .= "<div id=\"article-list\"><dl>\n";

        $ulid = UserGetLID();
        while (!$rs->EOF)
        {
            $lid = $rs->fields[9];
            $url = GetUrlFromFolderIdAndPageName($rs->fields[0], $rs->fields[1], $lid);
            if ($lid != $ulid)
                $url .= '?lid='.$lid;
           
            $tm_update = $rs->fields[8];
            $rating = sprintf("%01.2f", $rs->fields[5]);

            $desc = $rs->fields[3];
            if (!empty($desc))
                $desc .= "<br>";

            $out .= "<dt><div class=\"title\"><a href=\"$url\">".$rs->fields[2]."</a>";
            $out .= "</div></dt>\n";
            $out .= "<dd>".$desc;
            $out .= "<font face=arial size=1> "._PAGE_UPDATE_ON.': '.$tm_update;
            $out .= ". "._PAGE_RATING.": ".$rating." "._PAGE_VOTE_BY.": ".$rs->fields[6];
            $out .= ". "._PAGE_HITS.": ".$rs->fields[7];
            $out .= "</font></dd>\n";

            $rs->MoveNext();
        }
        $out .= "</dl></div>\n";
    } 

    return $out;
}


function RenderManagementMenu()
{
    global $gFolderId, $gPageId;

    $curpage = $gRequestPath.$gRequestFile;
    $prefix  = "&cat=$gFolderId&id=$gPageId";

    $out = '';
    if (IsUserAdmin())
    {
        $out .= '<b>'._FLD_PAGE.'</b> [';
        $out .= HRef($curpage.'?op=edit'.$prefix, _NAV_EDIT).', ';
        $out .= HRef($gRequestPath.'NewPage.html?op=edit'.$prefix, _NAV_ADDNEW).', ';
        $out .= HRef($curpage.'?op=delete'.$prefix, _NAV_DELETE).', ';
        $out .= HRef($curpage.'?op=move'.$prefix, _NAV_MOVE).'] ';

        $out .= ' - <b>'._FLD_FOLDER.'</b> [';
        $out .= HRef('/phpmod/folder_attr.php?op=show'.$prefix, _NAV_ATTR).', ';
        $out .= HRef('/phpmod/folder_add.php?op=show'.$prefix, _NAV_ADDNEW).', ';
        $out .= HRef('/phpmod/folder_del.php?op=show'.$prefix, _NAV_DELETE).', ';
        $out .= HRef('/phpmod/folder_move.php?op=show'.$prefix, _NAV_MOVE).'] ';
    }
    else
    {
        $out .= '<b>'._FLD_PAGE.'</b> [';
        $out .= HRef($curpage.'?op=edit'.$prefix, _NAV_EDIT).', ';
        $out .= HRef($gRequestPath.'NewPage.html?op=edit'.$prefix, _NAV_ADDNEW).'] ';

        $out .= ' - <b>'._FLD_FOLDER.'</b> [';
        $out .= HRef('/phpmod/folder_attr.php?op=show'.$prefix, _NAV_ATTR).', ';
        $out .= HRef('/phpmod/folder_add.php?op=show'.$prefix, _NAV_ADDNEW).'] ';
    }
    return $out; 
}

function RenderLoginForm()
{
    global $gFolderId;
    global $gPageId;


    if (IsUserLogin())
    {
        $out = '<div class=title>'.UserGetFullName().'</div>
<div id=sbnav>
    <a class="lnk" href="/phpmod/cpanel.php">'._NAV_CONTROL_PANEL.'</a>
    <a class="lnk" href="/members/index.html">'._NAV_MEMBERS.'</a>
    <a class="lnk" href="/members/'.UserGetName().'.html">'._NAV_MYPROFILE.'</a>
    <a class="lnk" href="/phpmod/logout.php">'._NAV_MEMBER_LOGOUT.'</a>
</div>
<div id=sbspace></div>';
    }
    else
    {
        $out = '<div class=title>'._MEMBER_LOGIN.'</div>
<div id=sbtext>
<table border="0" cellspacing="0" cellpadding="1">
<form method="POST" action="/phpmod/login.php">
  <tr>
    <td id="inputtext">'._FLD_USERID.'</td>
    <td> : </td>
    <td><input class="inputbox" type="text" name="uid" size="14" tabindex="1"></td>
  </tr>
  <tr>
    <td id="inputtext">'._FLD_PASSWORD.'</td>
    <td> : </td>
    <td><input class="inputbox" type="password" name="psw" size="14" tabindex="2"></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td><input class="button" type="submit" value="Login" tabindex="3"></td>
  </tr>
<input type="hidden" name="cat" value="'.$gFolderId.'" />
<input type="hidden" name="id" value="'.$gPageId.'" />
<input type="hidden" name="rnd" value="'.Session('rand').'" />
</form>  
</table>
</div></div>
<div id=sbspace></div>
<div id=sbnav>
    <a class="lnk" href="/phpmod/register.php">'._MEMBER_REGISTRATION.'</a>
    <a class="lnk" href="/phpmod/lost_password.php">'._LOST_PASSWORD.'</a>
</div>
<div id=sbspace></div>';
    }
    
    return $out;
}

function RenderFileInclude($fname)
{
    global $gBaseLocalPath;

    //PrintLine('RenderFileInclude');

    $fname = $gBaseLocalPath.'_lang/'.UserGetLID().'/'.$fname;
    $content = ReadLocalFile($fname, $errmsg, true);
    if (empty($content))
        $content = $errmsg;

    return WebContentParse($content);
}


function RenderRedirectForm()
{
    global $gBaseLocalPath;

    //PrintLine('RenderFileInclude');

    $fname = $gBaseLocalPath.'_lang/'.UserGetLID().'/redirect_form.htm';
    $content = ReadLocalFile($fname, $errmsg, true);
    if (empty($content))
        $content = $errmsg;

    return $content;
}

function RenderRedirectTable()
{
    global $db;
    global $gFolderId;
    global $gFolder;

    $out = "";

    $lid = UserGetLID();
    $sql = "select page_id, page_name, page_title, page_desc, page_redirect, page_hits from web_page where folder_id=$gFolderId 
            and page_lid=".$db->qstr($lid)." and page_redirect is not null order by page_id";

    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        $total_hits = 0;
        $out .= '
<table border="1" cellspacing="0" cellpadding="2" bordercolor="#FFFFFF" bordercolorlight="#C0C0C0" bordercolordark="#FFFFFF" width="96%">
  <tr>
    <td id="tbl_title" height="24">'._FLD_ID.'</td>
    <td id="tbl_title" height="24">'._FLD_NAME.'</td>
    <td id="tbl_title" height="24">'._FLD_TITLE.'</td>
    <td id="tbl_title" height="24">'._FLD_DESCRIPTION.'</td>
    <td id="tbl_title" height="24">'._FLD_REDIRECT.'</td>
    <td id="tbl_title" height="24">'._FLD_HITS.'</td>
  </tr>';

        while (!$rs->EOF)
        {
            if ($rs->fields[1] != 'index.html')
            {
            $url_edit = $rs->fields[1].'?op=edit';
            $url_show = $rs->fields[1];

            $out .= '<tr>
    <td id="tbl_text" valign="top" align="right">'.HRef($url_edit, $rs->fields[0]).'</td>
    <td id="tbl_text" valign="top" align="left">'.HRef($url_show, $rs->fields[1]).'</td>
    <td id="tbl_text" valign="top" align="left">'.$rs->fields[2].' </td>
    <td id="tbl_text" valign="top" align="left">'.$rs->fields[3].' </td>
    <td id="tbl_text" valign="top" align="left">'.HRef($rs->fields[4], $rs->fields[4]).' </td>
    <td id="tbl_text" valign="top" align="right">'.$rs->fields[5].'</td>
  </tr>';

            $total_hits += $rs->fields[5];
            }

            $rs->MoveNext();
        }

            $out .= '<tr>
    <td id="tbl_bottom" valign="middle" align="right" colspan="5" height="20"><b>TOTAL
      HITS</b></td>
    <td id="tbl_bottom" valign="middle" align="right" height="20"><b>'.$total_hits.'</b></td>
  </tr>
</table>';

    }
    else
    {
        $out = _FLD_EMPTY;
    }

    return $out;
}

function RenderCommentForm($title)
{
    global $gBaseLocalPath;

    if (IsUserLogin())
    {
        $params = array();
        $params['fld_fullname'] = UserGetFullName();
        $params['fld_email']    = UserGetEmail();

        if (empty($title))
            $title = _COMMENT_FORM_TITLE;

        $params['comment_form_title'] = $title;
        $params['fld_content_editor'] = RenderHtmlEditor('fld_content', '', 'chmini', 400, 90);

        $fname = $gBaseLocalPath.'_lang/'.UserGetLID().'/comment.htm';

        return WebContentParse(LoadContentFile($fname, $params));
    }
    else
    {
        return '<div id=comment-promo>'._COMMENT_PROMO.'</div>';
    }
}

function RenderCommentList($params)
{
    global $db;
    global $gPageId;
    global $gRequestPath, $gRequestFile;

    $bIsAdmin = IsUserAdmin();
    $path = $gRequestPath.$gRequestFile;

    list ($start, $max, $title) = explode(':', $params);
    $title = trim($title);

    $list = '';

    $sql = 'select a.comm_id, m_name, m_fullname, m_email, m_homepage, m_view_email, m_view_profile, 
            a.comm_content, a.comm_show, a.upload_on from comment 
            as a inner join sysmember as b on a.m_id=b.m_id where 
            page_lid='. $db->qstr(UserGetLID()).' and page_id='.$gPageId;

    if (!$bIsAdmin)
        $sql .= ' and comm_show=1';

    $sql .= ' order by upload_on desc limit '.$start.','.$max;

	$rs = DbExecute($sql);
	if ($rs && !$rs->EOF)
    {
        if (!empty($title))
            $list .= '<div id=section-title>'.$title.'</div>';

        $list .= '<div id=comment-list><dl>';
        while (!$rs->EOF)
        {
            
            $list .= '<dt>'.HRefMember($rs->fields[1], $rs->fields[2], $rs->fields[3], 
                        $rs->fields[4], $rs->fields[5], $rs->fields[6]);

            $list .= '. <font face="arial" size="1">';
            $list .= $rs->fields[9];
            if ($bIsAdmin)
            {
                $list .= ' ['.HRef('/phpmod/comment.php?op=edit&id='.$rs->fields[0].'&path='.$path, _NAV_EDIT).']';
                $list .= ' ['.HRef('/phpmod/comment.php?op=delete&id='.$rs->fields[0].'&path='.$path, _NAV_DELETE).']';
                if ($rs->fields[8])
                    $list .= ' ['.HRef('/phpmod/comment.php?op=hide&id='.$rs->fields[0].'&path='.$path, _NAV_HIDE).']';
                else
                    $list .= ' ['.HRef('/phpmod/comment.php?op=show&id='.$rs->fields[0].'&path='.$path, _NAV_SHOW).']';
            }
            $list .= "</font></dt>\n";
            $list .= '<dd>'.$rs->fields[7]."</dd>\n";
            
            $rs->MoveNext();
        }
        $list .= '</dl></div>';
    }

    return $list;
}

function RenderNewsForm()
{
    global $gBaseLocalPath;

    $params = array();
    $params['fld_id']    = 0;
    $params['fld_title'] = '';
    $params['news_form_title']    = _NEWS_FORM_TITLE;
    $params['fld_desc_editor']    = RenderHtmlEditor('fld_desc', '', 'chmini', 440, 90);
    $params['fld_content_editor'] = RenderHtmlEditor('fld_content', '', 'chsmall', 440, 140);
    $params['news_attr_row'] = '';
    $params['op'] = 'add';

    $fname = $gBaseLocalPath.'_lang/'.UserGetLID().'/news.htm';
    $content = WebContentParse(LoadContentFile($fname, $params));

    return $content;
}


function RenderNewsList($params)
{
    list ($start, $max) = explode(':', $params);
    return DoRenderNewsList($start, $max);
}

function RenderNewsOnTheBox($params)
{
    list ($max, $title, $url_more) = explode(':', $params);

    $out = '';

    $the_list = DoRenderNewsList(0, $max);
    if (!empty($the_list))
    {
        $out  = "<div id=\"box\">\n";
        $out .= "<h2>".$title."</h2>\n";
        $out .= "<div id=\"content\">\n";
        $out .= "<div id=\"right-nav\">".HRef($url_more, _NAV_MORE_LIST)."</div>\n";
        $out .= $the_list;
        $out .= "</div>\n</div>\n";
    }

    return $out;
}

function DoRenderNewsList($start, $max)
{
    global $gFolderId;
    global $db;

    $bIsAdmin = IsUserAdmin();
    
    $list = '';
    $sql = 'select news_id, news_title, upload_on, news_desc, news_show from news where news_lid='. $db->qstr(UserGetLID());
    if (!$bIsAdmin)
        $sql .= " and news_show=1";
    $sql .= " order by upload_on desc limit $start, $max";

	$rs = DbExecute($sql);
	if ($rs && !$rs->EOF)
    {
        $list .= '<div id=news-list><dl>';
        while (!$rs->EOF)
        {
            $list .= '<dt>'.HRef('/phpmod/news.php?op=detail&id='.$rs->fields[0].'&cat='.$gFolderId, $rs->fields[1]).'. ';
            $list .= '<font face="arial" size="1">';
            $list .= $rs->fields[2];
            if ($bIsAdmin)
            {
                $list .= ' ['.HRef('/phpmod/news.php?op=edit&id='.$rs->fields[0].'&cat='.$gFolderId, _NAV_EDIT).']';
                $list .= ' ['.HRef('/phpmod/news.php?op=delete&id='.$rs->fields[0].'&cat='.$gFolderId, _NAV_DELETE).']';
                if ($rs->fields[4])
                    $list .= ' ['.HRef('/phpmod/news.php?op=hide&id='.$rs->fields[0].'&cat='.$gFolderId, _NAV_HIDE).']';
                else
                    $list .= ' ['.HRef('/phpmod/news.php?op=show&id='.$rs->fields[0].'&cat='.$gFolderId, _NAV_SHOW).']';
            }
            $list .= "</font></dt>\n";
            $list .= '<dd>'.$rs->fields[3]."</dd>\n";
            
            $rs->MoveNext();
        }
        $list .= '</dl></div>';
    }

    return $list;
}

function RenderNewsList2($params)
{
    global $gFolderId;
    global $db;

    list ($start, $max) = explode(':', $params);

    $list = '';

    $sql = 'select news_id, news_title from news where news_lid='. $db->qstr(UserGetLID())." and news_show=1 order by upload_on desc limit $start, $max";
	$rs = DbExecute($sql);
	if ($rs && !$rs->EOF)
    {
        $list .= '<div id=news-list><ul>';
        while (!$rs->EOF)
        {
            $list .= '<li>'.HRef('/phpmod/news.php?op=detail&id='.$rs->fields[0].'&cat='.$gFolderId, $rs->fields[1]);
            $rs->MoveNext();
        }
        $list .= '</li></div>';
    }

    return $list;
}

function RenderFeedbackList($params)
{
    list ($start, $max) = explode(':', $params);
    return DoRenderFeedbackList($start, $max);
}

function RenderFeedbackOnTheBox($params)
{
    list ($max, $title, $url_more) = explode(':', $params);

    $out = '';
    $the_list = DoRenderFeedbackList(0, $max);
    if (!empty($the_list))
    {
        $out  = "<div id=\"box\">\n";
        $out .= "<h2>".$title."</h2>\n";
        $out .= "<div id=\"content\">\n";
        $out .= "<div id=\"right-nav\">".HRef($url_more, _NAV_MORE_LIST)."</div>\n";
        $out .= $the_list; 
        $out .= "</div>\n</div>\n";
    }

    return $out;
}

function DoRenderFeedbackList($start, $max)
{
    global $gFolderId;
    global $db;

    $bIsAdmin = IsUserAdmin();
    
    $list = '';
    $sql = 'select fb_id, fb_email, fb_fullname, fb_content, fb_show, upload_on from feedback where fb_lid='. $db->qstr(UserGetLID());
    if (!$bIsAdmin)
        $sql .= " and fb_show=1";
    $sql .= "  order by upload_on desc limit $start, $max";

	$rs = DbExecute($sql);
	if ($rs && !$rs->EOF)
    {
        $list .= '<div id=feedback-list><dl>';
        while (!$rs->EOF)
        {
            $list .= '<dt><a href="mailto:'.$rs->fields[1].'">'.$rs->fields[2].'</a>, ';
            $list .= '<font face="arial" size="1">';
            $list .= $rs->fields[5];
            if ($bIsAdmin)
            {
                $list .= ' ['.HRef('/phpmod/feedback.php?op=edit&id='.$rs->fields[0].'&cat='.$gFolderId, _NAV_EDIT).']';
                $list .= ' ['.HRef('/phpmod/feedback.php?op=delete&id='.$rs->fields[0].'&cat='.$gFolderId, _NAV_DELETE).']';
                if ($rs->fields[4])
                    $list .= ' ['.HRef('/phpmod/feedback.php?op=hide&id='.$rs->fields[0].'&cat='.$gFolderId, _NAV_HIDE).']';
                else
                    $list .= ' ['.HRef('/phpmod/feedback.php?op=show&id='.$rs->fields[0].'&cat='.$gFolderId, _NAV_SHOW).']';
            }
            $list .= "</font></dt>\n";
            $list .= '<dd>'.$rs->fields[3]."</dd>\n";
            
            $rs->MoveNext();
        }
        $list .= '</dl></div>';
    }

    return $list;
}

function RenderFeedbackForm()
{
    global $gBaseLocalPath;

    $params = array();

    if (UserGetID() == GUEST_UID)
    {
        $params['fld_name']    = '';
        $params['fld_email']   = '';
    }
    else
    {
        $params['fld_name']    = UserGetFullName();
        $params['fld_email']   = UserGetEmail();
    }

    $params['fld_id'] = 0;

    $params['fld_content_editor'] = RenderHtmlEditor('fld_content', '', 'chmini', 440, 140);
    $params['op'] = 'add';
    $params['chk_show'] = CheckBox('fld_show', '1', 1);
    $params['chk_testimonial'] = CheckBox('fld_testimonial', '1', 0);
    $params['page_message'] = '';

    $fname = $gBaseLocalPath.'_lang/'.UserGetLID().'/feedback.htm';
    $content = WebContentParse(LoadContentFile($fname, $params));

    return $content;
}

function RenderLinkList($params)
{
    global $db;
    global $gFolderId, $gPageId;

    list ($path, $title) = explode(':', $params);
    $path = trim($path);
    $title = trim($title);

    if (empty($path))
    {
        $page_id = $gPageId;
    }
    else
    {
        $page_id = FindPageIdFromPath($path);
    }


    $bIsAdmin = IsUserAdmin();

    $list = '';
    $sql = 'select link_id, link_url, link_title, link_desc, link_note, link_great from link where 
            page_lid='. $db->qstr(UserGetLID()).' and page_id='.$page_id;
    if (!$bIsAdmin)
        $sql .= " and link_show=1 and link_active=1";
    $sql .= ' order by link_order, link_title';

	$rs = DbExecute($sql);
	if ($rs && !$rs->EOF)
    {
        if (!empty($title))
            $list .= '<div id=section-title>'.$title.'</div>';

        $list .= '<div id=link-list><dl>';
        while (!$rs->EOF)
        {
            $list .= '<dt>'.HRef($rs->fields[1], $rs->fields[2]);
            if ($rs->fields[5])
            {
                $list .= '<img src="/images/great.gif" border="0">';
            }
            if ($bIsAdmin)
            {
                $list .= '<font face=verdana size=1>';
                $list .= ' ['.HRef('/phpmod/link.php?op=edit&cat='.$gFolderId.'&fld_id='.$rs->fields[0], _NAV_EDIT);
                $list .= '] ['.HRef('/phpmod/link.php?op=del&cat='.$gFolderId.'&fld_id='.$rs->fields[0], _NAV_DELETE);
                $list .= ']</font>';
            }
            $list .= "</dt>\n<dd>".$rs->fields[3];
            $note = $rs->fields[4];
            if (!empty($note))
            {
                $list .= "<font face=verdana size=1>[$note]</font>";
            }
            $list .= "</dd>\n";
            
            $rs->MoveNext();
        }
        $list .= '</dl></div>';
    }

    return $list;
}

function RenderLinkList2($params)
{
    global $db;
    global $gFolderId, $gPageId;

    list ($path, $title) = explode(':', $params);
    $path = trim($path);
    $title = trim($title);

    if (empty($path))
    {
        $page_id = $gPageId;
    }
    else
    {
        $page_id = FindPageIdFromPath($path);
    }


    $bIsAdmin = IsUserAdmin();

    $list = '';
    $sql = 'select link_id, link_url, link_title, link_desc from link where 
            page_lid='. $db->qstr(UserGetLID()).' and page_id='.$page_id;

    if (!$bIsAdmin)
        $sql .= " and link_show=1 and link_active=1";
    $sql .= ' order by link_order, link_title';

	$rs = DbExecute($sql);
	if ($rs && !$rs->EOF)
    {
        if (!empty($title))
            $list .= '<div id=title>'.$title.'</div>';

        $list .= '<div id=content><ul>';
        while (!$rs->EOF)
        {
            $list .= '<li>'.HRef($rs->fields[1], $rs->fields[2]);
            if ($bIsAdmin)
            {
                $list .= '<font face=verdana size=1>';
                $list .= ' ['.HRef('/phpmod/link.php?op=edit&cat='.$gFolderId.'&fld_id='.$rs->fields[0], _NAV_EDIT);
                $list .= '] ['.HRef('/phpmod/link.php?op=del&cat='.$gFolderId.'&fld_id='.$rs->fields[0], _NAV_DELETE);
                $list .= ']</font>';
            }
            $list .= "<br>".$rs->fields[3];
            $list .= "</li>\n";
            
            $rs->MoveNext();
        }
        $list .= '</ul></div>';
    }

    return $list;
}


function RenderLinkForm()
{
    global $gBaseLocalPath;
    global $gFolder;

    if (!IsUserLogin())
    {
        $out = _LINK_ADDNEW_MESSAGE;
    }
    else
    {
        if (!IsUserAdmin())
        {
            $fld_order_row = '';
            $fld_attr_row = '';
        }
        else
        {
            $fld_order_row = "
<tr>
<td valign=\"top\" align=\"right\">"._FLD_ORDER."</td>
<td valign=\"top\" align=\"left\">:</td>
<td valign=\"top\" align=\"left\"><input class=\"inputbox\" type=\"text\" name=\"fld_order\" size=\"8\" value=\"".DEFAULT_ORDER."\"> "._FLD_ORDER_NOTE."</td>
</tr>";

            $chk_link_show   = CheckBox('fld_show', '1', 1).' '._FLD_SHOW;
            $chk_link_active = CheckBox('fld_active', '1', 1).' '._FLD_ACTIVE;
            $chk_link_great = CheckBox('fld_great', '1', 0).' '._FLD_GREAT;

            $fld_attr_row   = "
<tr>
<td valign=\"top\" align=\"right\">"._FLD_MISC_ATTR."</td>
<td valign=\"top\" align=\"left\">:</td>
<td valign=\"top\" align=\"left\">$chk_link_show $chk_link_active $chk_link_great</td>
</tr>";

        }

        $params = array();
        $params['link_form_title']    = _LINK_FORM_TITLE;
        $params['page_message']       = '';

        $params['fld_id']       = 0;
        $params['fld_url']      = '';
        $params['fld_title']    = '';
        $params['fld_desc']     = '';
        $params['fld_note']     = '';
        $params['fld_keywords'] = $gFolder['keywords'];
        $params['fld_op']       = 'add';
        $params['fld_order_row']= $fld_order_row;
        $params['fld_attr_row'] = $fld_attr_row;

        $fname = $gBaseLocalPath.'_lang/'.UserGetLID().'/link.htm';
        $out = LoadContentFile($fname, $params);
        if (IsUserAdmin())
        {
            $fname = $gBaseLocalPath.'_lang/'.UserGetLID().'/link_some.htm';
            $out .= LoadContentFile($fname, $params);
        }
    }

    return WebContentParse($out);
}

function RenderMacroText($params)
{
    global $db;

    list($key, $title) = explode(':', $params);

    $sql = 'select mac_title, mac_content, mac_active from macrotext where mac_key='.$db->qstr($key).' and mac_lid='.$db->qstr(UserGetLID());
    
    $out = '';
    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        if ($rs->fields[2])
        {
            if (empty($title))
                $title = $rs->fields[0];
    
            $content = WebContentParse($rs->fields[1]);

            $out = "<div id=title>$title</div>\n<div id=content>$content</div>";
        }
    }

    return $out;
}

function RenderAuthorProfile($name, $fmt)
{
    global $db;

    $out = "";

    $sql = 'select m_fullname, m_desc, m_photo from sysmember where m_name='.$db->qstr($name);
    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        if ($rs->fields[2])
            $content = '<center>'.HRef('/members/'.$name.'.html', '<img src="/members/images/'.$name.'.jpg" border=0>').'</center><br clear=all>';
        $content .= HRef('/members/'.$name.'.html', $rs->fields[0]).'<br>'.$rs->fields[1];

        $out .= '<div id=title>'._ABOUT_AUTHOR."</div>\n";
        $out .= '<div id=content>'.$content."</div>";

        if ($fmt == 'right')
        {
            $out = '<div id=right>'.$out.'</div>';
        }
    }

    return $out;
}

function RenderRatingForm()
{
    global $gBaseLocalPath;

    $params = array();
    //$params['page_rating']   = sprintf("%3.2f", $gWebPage['page_rating']);
    //$params['page_votes']    = $gWebPage['page_votes'];

    $fname = $gBaseLocalPath.'_lang/'.UserGetLID().'/rating.htm';
    $content = WebContentParse(LoadContentFile($fname, $params));

    return $content;
}

function RenderSeeAlso($fmt)
{
    global $gWebPage;

    $out = '';

    if (!empty($gWebPage['see_also_text']))
    {
        $out .=  '<div id=title>'.$gWebPage['see_also_title']."</div>\n";
        $out .= '<div id=content>'.$gWebPage['see_also_text']."</div>\n";

        if ($fmt == 'right')
        {
            $out = '<div id=right>'.$out.'</div>';
        }
    }

    return $out;
}

function RenderSeeAlso2($title, $content)
{
    $out = "<table cellpadding=\"0\" cellspacing=\"0\" id=\"sbox\" align=\"right\">
<tr><td><img src=\"http://localhost:892/_theme/StdGrey/images/sbox_top.gif\"></td></tr>
<tr><td id=\"bdy\">
<div class=\"title\">$title</div>
<div class=\"content\">$content</div>
</td></tr>
<tr><td><img src=\"http://localhost:892/_theme/StdGrey/images/sbox_bottom.gif\"></tr>
</table>";

    return $out;
}


function RenderAdvText($params)
{
    global $db;

    list($adv_key, $adv_title) = explode(':', $params);

    $out = '';

    $sql = 'select adv_title, adv_text, adv_active from advtext where 
            adv_key='.$db->qstr($adv_key).' and adv_lid='.$db->qstr(UserGetLID());

    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        if ($rs->fields[2])
        {
            if (empty($adv_title))
                $adv_title = $rs->fields[0];

            $out =  '<div id=title>'.$adv_title."</div>\n";
            $out .= '<div id=content>'.$rs->fields[1]."</div>\n";

            $sql = 'update advtext set adv_hits=adv_hits+1 where 
                    adv_key='.$db->qstr($adv_key).' and adv_lid='.$db->qstr(UserGetLID());
            DbExecute($sql);
        }
    }

    return $out;
}

function RenderAdvRandom($params)
{
    global $db;

    list($adv_key, $adv_title) = explode(':', $params);

    $out = '';

    $sql = 'select count(adv_id) from advrnd where 
            adv_key='.$db->qstr($adv_key).' and adv_lid='.$db->qstr(UserGetLID());

    $max = DbGetOneValue($sql);
    $idx = RandValue(0, $max-1);

    $sql = 'select adv_id, adv_title, adv_text, adv_active from advrnd where 
            adv_key='.$db->qstr($adv_key).' and adv_lid='.$db->qstr(UserGetLID())
            ." limit $idx, 1";

    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        if ($rs->fields[3])
        {
            $adv_id = $rs->fields[0];

            if (empty($adv_title))
                $adv_title = $rs->fields[1];

            $out =  '<div id=title>'.$adv_title."</div>\n";
            $out .= '<div id=content>'.$rs->fields[2]."</div>\n";

            $sql = "update advrnd set adv_hits=adv_hits+1 where adv_id=$adv_id";
            DbExecute($sql);
        }
    }

    return $out;
}

function RenderImage($align, $params)
{
    list($src, $title, $url1, $url2) = explode(':', $params);

    if (empty($url2))
        $url = $url1;
    else
        $url = $url1.':'.$url2;

    $img = '<img src="'.$src.'" title="'.$title.'"border="0">';
    if (!empty($url))
        $img = HRef($url, $img);

    if ($align=='center')
    {
        $out = '<div align="center"><center>';
        $out .= '<table border=0 class="image-table">';
    }
    else
        $out .= '<table border=0 class="image-table" align="'.$align.'">';


    $out .= '<tr><td>'.$img.'</td></tr>';
    if (!empty($title))
        $out .= '<tr><td>'.$title.'</td></tr>';

    if ($align=='center')
        $out .= '</table></center></div>';
    else
        $out .= '</table>';

    return $out;
}

function RenderSiteMap($params)
{
    list ($level, $detail) = explode(':', $params);

    return DoRenderSiteMap('/', 0, $level, $detail, 0);
}

function DoRenderSiteMap($base_path, $parent_id, $level, $detail, $width)
{
    global $db;

    $out = "";

    $lid = UserGetLID();
    $sql = "select folder_id, folder_name, folder_title, folder_desc from web_folder where folder_parent=$parent_id 
            and folder_lid=".$db->qstr($lid);
    if (!IsUserAdmin())
        $sql .= " and folder_show=1 and folder_active=1";
    $sql .= " and read_level <= ".UserGetLevel();
    $sql .= " order by folder_order, folder_title";

    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        $out .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"2\">\n";
        while (!$rs->EOF)
        {
            $out .= "<tr>";
            if ($width > 0)
                $out .= "<td><img src=\"/images/space.gif\" border=\"0\" width=\"$width\"></td>";

            $out .= "<td valign=\"top\" align=\"left\">\n";

            $url = $base_path.$rs->fields[1]."/index.html";

            if ($detail)
                $out .= "<b><a href=\"$url\">".$rs->fields[2]."</a></b>. ".$rs->fields[3];
            else
                $out .= "<a href=\"$url\">".$rs->fields[2]."</a>";
            
            if ($level > 1)
                $out .= DoRenderSiteMap($base_path.$rs->fields[1].'/', $rs->fields[0], $level-1, $detail, 16);

            $rs->MoveNext();

            $out .= "</td></tr>\n";
        }
        $out .= "</table>\n";
    } 

    return $out;

}

function RenderSmileyTable()
{
    $out  = '<table width="428" bordercolor="#c0c0c0" border="1" cellspacing="0" cellpadding="4" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0"><tbody>';
    $out .= '<tr>
<td width="97" id="tbl_title">CODE</td>
<td width="102" id="tbl_title">SMILEY</td>
<td width="209" id="tbl_title">EMOTION</td></tr>
<tr>';


    $arSmiley = SmileyGetArray();

    foreach($arSmiley as $code => $smiley)
    {

        $out .= '
<tr>
<td width="97" id="tbl_text">'.SmileyEscape($code).'</td>
<td width="102" id="tbl_text" align="center">'.$smiley[1].'</td>
<td width="209" id="tbl_text">'.$smiley[0].'</td></tr>
<tr>';

    }


    $out .= '</tbody></table>';

    return $out;
}


?>