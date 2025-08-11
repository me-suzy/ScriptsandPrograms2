<?php 
// ----------------------------------------------------------------------
// ModName: search.php
// Purpose: Process searching data
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("../_files/library/_config.php");

DBGetFolderData($gFolderId);

$gRequestPath = FindPathFromFolderId($gFolderId);
$gCurrentUrlPath = $gBaseUrlPath.$gRequestPath;
$gRequestFile = 'index.html';

$q = RequestGetValue('q', '');
$p = RequestGetValue('p', 1);


$title = "<h1>"._HPAGE_SEARCH_TITLE."</h1>\n";
$content = "<p>".sprintf(_HPAGE_SEARCH_MESSAGE, $q)."</p>\n";

$content .= WebPageSearch($q, $p);

$gWebPage['page_header'] = WebContentParse($gHomePageHeader);
$gWebPage['page_footer'] = WebContentParse($gHomePageFooter);
$gWebPage['page_sidebar']   = RenderPageSidebar();
$gWebPage['page_title']  = _HPAGE_SEARCH_TITLE;
$gWebPage['page_desc']   = '';
$gWebPage['page_keywords'] = '';
$gWebPage['page_content'] = $title.$content;

DoShowPage(TPL_WEB_PAGE);

function WebPageSearch($query, $page)
{
	global $db;

    $lid = UserGetLID();
	$bwhere =  WebPageQueryBuilder($query, $query_list);
    $where = "($bwhere) and (page_lid=".$db->qstr($lid)." and page_active=1)";

	$offset = ($page-1)*MAX_ITEM_PERPAGE;

	$sql =  'select count(page_id) from web_page where ( '.$where.' ) order by page_title';
	$row_count = DbGetOneValue($sql);

	$sql =  'select folder_id, page_name, page_title, page_desc ';
	$sql .= ' from web_page where ( '.$where.' ) order by page_title';
	$sql .= ' limit '.$offset.', '.MAX_ITEM_PERPAGE;

	$rs = DbExecute($sql);
	if ($rs === false) DbFatalError('WebPageSearch'); 

	$cgi = "/phpmod/search.php?q=$query";
	$pos = PagePosition($cgi, $row_count, $page);
	$list = WebPageListFromRecordset($rs, $item_count);
	$content = $pos.$list.$pos;

    return $content;
}


function WebPageQueryBuilder($query)
{
	$query_list = array();

	if (substr($query,1,1) == ':')
	{
		$code   = substr($query,0,1);
		$query = substr($query,2);

		switch ($code)
		{
		case 'n':
			$where =  SearchQueryBuilder('page_name', $query, $query_list);
			break;
		case 't':
			$where =  SearchQueryBuilder('page_title', $query, $query_list);
			break;
		case 'd':
			$where =  SearchQueryBuilder('page_desc', $query, $query_list);
			break;
		case 'k':
			$where =  SearchQueryBuilder('page_keywords', $query, $query_list);
			break;
		default:
			$where = 'page_id = 0'; //no item will be shown
			break;
		}
	}
	else
	{
		$where =  SearchQueryBuilder('page_keywords', $query, $query_list);
		$where .= ' or '.SearchQueryBuilder('page_title', $query, $query_list);
		$where .= ' or '.SearchQueryBuilder('page_desc', $query, $query_list);
	}

	return $where;
}

function WebPageListFromRecordset($rs, $item_count)
{
    $i = 1;
    
    $out .= "<div id=\"file-list\"><dl>\n";

    while (!$rs->EOF)
    {
        $url = GetUrlFromFolderIdAndPageName($rs->fields[0], $rs->fields[1]);
        $out .= "<dt><div class=\"title\"><a href=\"$url\">".$rs->fields[2]."</a></div></dt>\n";
        //$out .= "<dd>".$rs->fields[3]."-----".$rs->fields[0]."</dd>\n";
        $out .= "<dd>".$rs->fields[3]."</dd>\n";

        $i++;
        $rs->MoveNext();
    }

    $out .= "</dl></div>\n";

    return $out;
}


function PagePosition($ref_page, $row_count, $page)
{
	$img_path = "/images/"; //SystemGetImagePath();

	$ItemPerPage = MAX_ITEM_PERPAGE; //UserGetMaxItemPerPage();

	$out = '';

	$pages = (int)($row_count / $ItemPerPage);
	//print 'rowCount:'.$row_count."<br>\r\n";
	//print 'pages:'.$pages."<br>\r\n";

	if ( ($row_count % $ItemPerPage) > 0)
		$pages++;

	if ($pages < 2)
		return $out;

	//print 'pages:'.$pages."<br>\r\n";
	$xstart = $page - (int)(MAX_PAGEPOS_SHOW/2);
	if ($xstart < 1)
		$xstart = 1;
	$xend = $xstart + MAX_PAGEPOS_SHOW - 1;
	if ($xend > $pages)
		$xend = $pages;

	if ($page > 1)
		$out .= '<a href="'.$ref_page.'&p='.($page-1).'"><img src="'.$img_path.'previous.gif" border=0 align=top></a>&nbsp;';

	$i = 1;
	if ($i < $xstart)
	{
		$add = 0;
		while ($i < $xstart && $add<5)
		{
			$out .= '<a href="'.$ref_page.'&p='.$i.'">'.$i.'</a>..';
			$add++;
			$i += 5;
		}
	}

	for ($i=$xstart;$i<=$xend;$i++)
	{
		if ($i == $page)
			$out .= '<b>'.$i.'</b>&nbsp';	
		else
			$out .= '<a href="'.$ref_page.'&p='.$i.'">'.$i.'</a>&nbsp;';	
	}

	$i_temp = $i;
	$add = 0;
	while ($i < $pages && $add<5)
	{
		$add++;
		$i += 5;
		if ($i < $pages)
			$out .= '..<a href="'.$ref_page.'&p='.$i.'">'.$i.'</a>';		
	}

	if ($i_temp < $pages)
		$out .= '..<a href="'.$ref_page.'&p='.$pages.'">'.$pages.'</a>';		

	if ($page < $pages)
		$out .= '&nbsp;<a href="'.$ref_page.'&p='.($page+1).'"><img src="'.$img_path.'next.gif" border=0 align=top></a>';

	$out = '<div align=right><table border=0 cellpadding=0 cellspacing=0 width=100%><tr><td align=right bgcolor=#EBEBEB height=24><font size=2>'.$out.'</font></td></tr></table></div>';
	return $out;
}



?>
