<?php 
// ----------------------------------------------------------------------
// ModName: todo.php
// Purpose: To Do List for Administrator
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("../_files/library/_config.php");

SetDynamicContent();

if (!IsUserAdmin())
	RedirectToPreviousPage();

$gFolderId = 0;
$gRequestPath = '/'; //FindPathFromFolderId($gFolderId);
$gCurrentUrlPath = $gBaseUrlPath.$gRequestPath;
$gRequestFile = 'index.html';
$gPageId = RequestGetValue('id', 0);

DBGetFolderData(0);

$gPageNavigation = array();
$gPageNavigation[] = array($gHomePageUrl.$gBaseUrlPath."/index.html", _NAV_FRONTPAGE);
$gPageNavigation[] = array($gHomePageUrl."/phpmod/cpanel.php", _NAV_CONTROL_PANEL);
$gPageNavigation[] = array($gHomePageUrl."/phpmod/todo.php", _NAV_TODO_LIST);

$gWebPage['page_sidebar']   = RenderPageSidebar();
$gWebPage['page_header']    = WebContentParse($gHomePageHeader);
$gWebPage['page_footer']    = WebContentParse($gHomePageFooter);

$op = RequestGetValue('op', 'show');
switch ($op)
{
case 'link':
    TodoLinkOtorization();
    break;
case 'feedback':
	FeedbackShowList();
	break;
case 'comment':
	CommentShowList();
	break;
case 'reset_feedback_time':
	ResetFeedbackTime();
	break;
case 'reset_comment_time':
	ResetCommentTime();
	break;

case 'show':
default:
    TodoShow();
    break;
}

function TodoShow()
{
    global $gWebPage, $gSysVarInt;

    $gWebPage['page_title']     = _TODO_LIST_TITLE;
    $gWebPage['link_oto_count'] = GetLinkOtorizationCount();

    $fb_lasttime = $gSysVarInt['feedback_last_time'];
    $gWebPage['feedback_count'] = GetFeedbackCount($fb_lasttime);
    $gWebPage['feedback_last_time'] = date("Y-m-d H:i:s", $fb_lasttime);

    $cm_lasttime = $gSysVarInt['comment_last_time'];
    $gWebPage['comment_count'] = GetCommentCount($cm_lasttime);
    $gWebPage['comment_last_time'] = date("Y-m-d H:i:s", $cm_lasttime);


    $gWebPage['setup_folder_check'] = SetupFolderCheck();

    DoShowPageWithContent(TPL_WEB_PAGE, 'todo.htm');
}


function TodoLinkOtorization()
{
    global $db;
    global $gWebPage;
    global $gPageNavigation;

    $gPageNavigation[] = array($gHomePageUrl."/phpmod/todo.php?op=link", _NAV_TODO_LINK);
    $gWebPage['page_title']     = _TODO_LINK_TITLE;
    
    $title = sprintf("<h1>%s</h1>\n", _TODO_LINK_TITLE);

    $list = '';

    $sql = 'select link_id, link_url, link_title, link_desc from link where link_show=0 and link_active=0 order by link_title';
	$rs = DbExecute($sql);
	if ($rs && !$rs->EOF)
    {
        $list .= '<div id=link-list><dl>';
        while (!$rs->EOF)
        {
            $list .= '<dt>'.HRef($rs->fields[1], $rs->fields[2]);
            $list .= '<font face=verdana size=1>';
            $list .= ' ['.HRef('/phpmod/link.php?op=edit&from=todo&fld_id='.$rs->fields[0], _NAV_EDIT);
            $list .= '] ['.HRef('/phpmod/link.php?op=delete&from=todo&fld_id='.$rs->fields[0], _NAV_DELETE);
            $list .= '] ['.HRef('/phpmod/link.php?op=approve&from=todo&fld_id='.$rs->fields[0], _NAV_APPROVE);
            $list .= ']</font>';
            $list .= "</dt>\n<dd>".$rs->fields[3];
            $list .= "</dd>\n";
            
            $rs->MoveNext();
        }
        $list .= '</dl></div>';
    }

    $gWebPage['page_content'] = $title._TODO_LINK_MESSAGE.$list;
    DoShowPage(TPL_WEB_PAGE);
}


function GetLinkOtorizationCount()
{
    $sql = 'select count(link_id) from link where link_active=0 and link_show=0';
    return DbGetOneValue($sql);
}

function GetFeedbackCount($last_time)
{
    $sql = 'select count(fb_id) from feedback where upload_on>='.date("YmdHis", $last_time);
    return DbGetOneValue($sql);
}

function GetCommentCount($last_time)
{
    $sql = 'select count(comm_id) from comment where upload_on>='.date("YmdHis", $last_time);
    return DbGetOneValue($sql);
}


function SetupFolderCheck()
{
    $out = '';

    if (is_file(realpath('../setup').'/index.php'))
        $out = '<li>'._SETUP_FOLDER_EXIST;

    return $out;
}

function FeedbackShowList()
{
    global $gWebPage;
    global $gPageNavigation;

    $start = RequestGetValue('start', 0);
    $max   = RequestGetValue('max', 10);

    $gPageNavigation[] = array($gHomePageUrl."/phpmod/todo.php?op=feedback&start=$start&max=$max", _NEW_FEEDBACK_LIST_TITLE);
    $gWebPage['page_title']     = _NEW_FEEDBACK_LIST_TITLE;

    $list = DoRenderFeedbackList($start, $max);

    $gWebPage['page_content'] = sprintf("<h1>%s</h1>\n%s\n", _NEW_FEEDBACK_LIST_TITLE, $list);
    DoShowPage(TPL_WEB_PAGE);
}


function CommentShowList()
{
    global $gWebPage;
    global $gPageNavigation;

    $start = RequestGetValue('start', 0);
    $max   = RequestGetValue('max', 10);

    $gPageNavigation[] = array($gHomePageUrl."/phpmod/todo.php?op=comment&start=$start&max=$max", _NEW_COMMENT_LIST_TITLE);
    $gWebPage['page_title']     = _NEW_COMMENT_LIST_TITLE;

    $list = DoRenderCommentList($start, $max);

    $gWebPage['page_content'] = sprintf("<h1>%s</h1>\n%s\n", _NEW_COMMENT_LIST_TITLE, $list);
    DoShowPage(TPL_WEB_PAGE);
}

function ResetFeedbackTime()
{
    DbSetIntVar('feedback_last_time', time());

    Header("Location: /phpmod/todo.php");
}

function ResetCommentTime()
{
    DbSetIntVar('comment_last_time', time());

    Header("Location: /phpmod/todo.php");
}


function DoRenderCommentList($start, $max)
{
    global $db;

    $path = StrEscape("/phpmod/todo.php?op=comment&start=$start&max=$max");

    $list = '';

    $sql = 'select a.comm_id, m_name, m_fullname, m_email, m_homepage, m_view_email, m_view_profile, 
            a.comm_content, a.comm_show, a.upload_on, a.page_id, a.page_lid from comment 
            as a inner join sysmember as b on a.m_id=b.m_id 
            order by upload_on desc limit '.$start.','.$max;

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

            $list .= ' ['.HRef('/phpmod/comment.php?op=edit&id='.$rs->fields[0].'&path='.$path, _NAV_EDIT).']';
            $list .= ' ['.HRef('/phpmod/comment.php?op=delete&id='.$rs->fields[0].'&path='.$path, _NAV_DELETE).']';
            if ($rs->fields[8])
               $list .= ' ['.HRef('/phpmod/comment.php?op=hide&id='.$rs->fields[0].'&path='.$path, _NAV_HIDE).']';
            else
               $list .= ' ['.HRef('/phpmod/comment.php?op=show&id='.$rs->fields[0].'&path='.$path, _NAV_SHOW).']';

            $list .= ' ['.HRef('/phpmod/find_page.php?op=show&id='.$rs->fields[10].'&lid='.$rs->fields[11], _NAV_GOTO_PAGE).']';

            $list .= "</font></dt>\n";
            $list .= '<dd>'.$rs->fields[7]."</dd>\n";
            
            $rs->MoveNext();
        }
        $list .= '</dl></div>';
    }

    return $list;
}


?>
