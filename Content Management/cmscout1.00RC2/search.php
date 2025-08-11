<?php
/**************************************************************************
    FILENAME        :   search.php
    PURPOSE OF FILE :   Searches database for given term
    LAST UPDATED    :   08 June 2005
    COPYRIGHT       :   © 2005 CMScout Group
    WWW             :   www.cmscout.za.org
    LICENSE         :   GPL vs2.0
    
    

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
**************************************************************************/
?>
<?php
if (!defined('SCOUT_NUKE'))
    die("You have accessed this page illegally, please go use the main menu");
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if(isset($_POST['Submit'])) $submit = $_POST['Submit']; else $submit = "";
if (isset($_POST['search'])) $search = stripslashes($_POST['search']);
if (isset($_POST['what'])) $what = $_POST['what']; else $what = 0;
if (isset($_POST['type'])) $type = $_POST['type']; else $type = 0;

$tpl->assign("type", $type);

switch($type)
{
    case 1:
        $type="AND";
        break;
    case 2:
        $type="OR";
        break;
    default:
        $type="AND";
}

$tpl->assign("option", $what);

if ($submit == "Search")
{
    $extra = false;
    switch($what)
    {
        case 1:
            $table="patrol_articles";
            $field="detail";
            $tfield="title";
            $dfield="detail";
            $ifield="ID";
            $whatpage="patrolarticle&amp;id=";
            $searching = "Articles";
            $showdate = 1;
            $datefield = "date_post";
            break;
        case 3:
            $table="newscontent";
            $field="news";
            $tfield="title";
            $dfield="news";
            $ifield="id";
            $whatpage="news&amp;id=";
            $searching = "News Items";
            $showdate = 1;
            $datefield = "event";
            break;
        case 4:
            $table="forumposts";
            $field="posttext";
            $tfield="subject";
            $dfield="posttext";
            $ifield="topic";
            $whatpage="forums&amp;action=topic&amp;t=";
            $searching = "Forum Posts";
            $showdate = 1;
            $datefield = "dateposted";
            break;
        case 5:
            $table="pms";
            $field="text";
            $tfield="subject";
            $dfield="text";
            $ifield="id";
            $whatpage="pmmain&amp;action=readpm&amp;id=";
            $extra = "((touser='{$check['uname']}' AND (type=1 OR type=3)) OR (fromuser='{$check['uname']}' AND (type=2 OR type=4)))";
            $searching = "Your Personal Messages";
            break;
            $showdate = 1;
            $datefield = "date";
        case 6:
            $table="static_content";
            $field="content";
            $tfield="name";
            $dfield="content";
            $ifield="name";
            $whatpage="";
            $searching = "Website Content";
            $showdate = 0;
            break;
        case 7:
            $table="patrolcontent";
            $field="content";
            $tfield="name";
            $dfield="content";
            $ifield="name";
            $whatpage="patrolpages&amp;patrol={\$results[results].patrol}&amp;content=";
            $searching = "Patrol Page Content";
            $showdate = 0;
            break;
        case 8:
            $table="subcontent";
            $field="content";
            $tfield="name";
            $dfield="content";
            $ifield="name";
            $whatpage="subsite&amp;site={\$results[results].site}&amp;content=";
            $searching="Sub Site Content";
            $showdate = 0;
            break;
    }
    #
    # do the search here...
    #
    
    $results = search_perform($search, $table, $field, $type, $extra);
    $term_list = search_pretty_terms(search_html_escape_terms(search_split_terms($search)));
    
    #
    # of course, we're using smarty ;)
    #
    
    $tpl->assign('term_list', $term_list);
    $tpl->assign('terms', HtmlSpecialChars(serialize(search_html_escape_terms(search_split_terms($search)))));
    $tpl->assign("searching", $searching);
    $tpl->assign("searched", 1);
    $tpl->assign("search", $search);
    
    if (count($results))
    {   
        $tpl->assign('results', $results);
        $tpl->assign("tfield", $tfield);
        $tpl->assign("dfield", $dfield);
        $tpl->assign("ifield", $ifield);
        $tpl->assign("whatpage", $whatpage);
        $tpl->assign("showdate", $showdate);
        $tpl->assign("datefield", $datefield);
    }
}
$tpl->assign("editFormAction", $editFormAction);
$pagename = "search";
$dbpage = true;
?>