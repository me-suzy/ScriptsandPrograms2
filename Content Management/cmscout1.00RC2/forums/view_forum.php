<?php
/**************************************************************************
    FILENAME        :   view_forum.php
    PURPOSE OF FILE :   Displays main forum view, and seperate forum views
    LAST UPDATED    :   21 November 2005
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

if (isset($_GET['f'])) $fid = $_GET['f'];

//If forum number is empty, get all forums, else only forum that were looking at
if (empty($fid))
{
    $sql = $data->select_query("forumscats", "ORDER BY pos ASC");
    $numcats = $data->num_rows($sql);
    $cats = array();
    while($temp = $data->fetch_array($sql))
    {
        $sqls = $data->select_query("forums", "WHERE cat={$temp['id']} ORDER BY pos ASC");
        $temp['numforums'] = $data->num_rows($sqls);
        $temp['forums'] = array();
        while($temp3 = $data->fetch_array($sqls))
        {
            $sql2 = $data->select_query("forumauths", "WHERE forum_id={$temp3['id']}");
            $auth = $data->fetch_array($sql2);
            
            $access = array('admin_level', 'scouter_level','tl_level','pl_level','second_level');
    
            if ($check['level'] != 5)
            {
                $useraccess = $access[$check['level']];
            }
            
            $currentauth = unserialize($auth['view_forum']);
            $viewauth = (($currentauth[$usergroup] == 1) || ($currentauth[$useraccess] == 1)) ? 1 : 0;

            $currentauth = unserialize($auth['read_topics']);
            $readauth = (($currentauth[$usergroup] == 1) || ($currentauth[$useraccess] == 1)) ? 1 : 0;
            
            if($viewauth == 1)
            {
                $i = 0;
                $j = 0;
                $sql2 = $data->select_query("forumtopics", "WHERE forum={$temp3["id"]}");
                $temp3["numtopics"] = $data->num_rows($sql2);
                while($temp2 = $data->fetch_array($sql2))
                {
                    $i += $temp2["numviews"];
                    $sql3 = $data->select_query("forumposts", "WHERE topic={$temp2["id"]}");
                    $j += $data->num_rows($sql3);
        
                    if ($temp2['id'] == $temp3['lasttopic'])
                    {
                        $temp3['lastsubject'] = $temp2['subject'];
                    }
                }
                $temp3["numviews"] = $i;
                $temp3["numposts"] = $j;
                $sql3 = $data->select_query("forumnew", "WHERE uname='{$check['uname']}' AND forum={$temp3["id"]}");
                if ($data->num_rows($sql3) > 0)
                {
                    $temp3["new"] = 1;
                }
                else
                {
                    $temp3["new"] = 0;
                }
                $temp3['allowed'] = 1;
                $temp3['read'] = $readauth;
                $temp['forums'][] = $temp3;
            }
            else
            {
                $temp2['name'] = "You do not have access to the \"{$temp3['name']}\" forum";
                $temp2['allowed'] = 0;
                if ($check['uname'] != "Guest") 
                {
                    $temp2['desc'] = "If you think you should have access, contact the administrator";
                }
                else
                {
                    $temp2['desc'] = "";                
                }
                $temp['forums'][] = $temp2;
            }
        }
        $cats[] = $temp;
    }
    
    $tpl->assign("numcats", $numcats);
    $tpl->assign("cats", $cats);
    $pagenum = 1;
}
else
{
    $limit = $config['postcount'];
    $start = isset($_GET['start']) ? $_GET['start'] : 0;
    
    $sql = $data->select_query("forums", "WHERE id=$fid");
    $forum = $data->fetch_array($sql);
    
    $sql = $data->select_query("forumtopics", "WHERE forum=$fid");
    $numtopics = $data->num_rows($sql);
    
    $pagelimit = ($numtopics-$start) <= $limit ? ($numtopics-$start) : $limit;
    $sql = $data->select_query("forumtopics", "WHERE forum=$fid ORDER BY lastdate DESC LIMIT $start, $pagelimit");
    $topics = array();
    while($temp = $data->fetch_array($sql))
    {
        $sql3 = $data->select_query("forumposts", "WHERE topic={$temp["id"]}");
        $temp['numposts'] = $data->num_rows($sql3);
        
        $sql3 = $data->select_query("forumnew", "WHERE uname='{$check['uname']}' AND topic={$temp["id"]}");
        if ($data->num_rows($sql3) > 0)
        {
            $temp["new"] = 1;
        }
        else
        {
            $temp["new"] = 0;
        }
        
        $topics[] = $temp;
    }
    
    
    //Pagenation working out
    if ($numtopics > 0) 
    {
        $num_pages = ceil($numtopics / $limit);
        $curr_page = floor($start/$limit) + 1;
        if ($curr_page < $num_pages)
        {
            $next = true; 
            $next_start=(($curr_page-1)*$limit) + $limit;
        }
        if ($curr_page > 1) 
        {
            $prev = true; 
            $prev_start=(($curr_page-1)*$limit)- $limit;
        }
    } 
    
    if (isset($num_pages)) $tpl->assign('numpages', $num_pages);
    if (isset($curr_page)) $tpl->assign('currentpage', $curr_page);
    if (isset($next))$tpl->assign('next', $next);
    if (isset($prev))$tpl->assign('prev', $prev);
    $tpl->assign('num_per_page', $limit);
    if (isset($next_start)) $tpl->assign('next_start', $next_start);
    if (isset($prev_start)) $tpl->assign('prev_start', $prev_start);
    $tpl->assign("forum", $forum);
    $tpl->assign("topics", $topics);
    $tpl->assign("limit", $pagelimit);
    $tpl->assign("numtopics", $numtopics);
    $pagenum = 2;
}


?>