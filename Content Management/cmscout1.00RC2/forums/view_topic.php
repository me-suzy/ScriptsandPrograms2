<?php
/**************************************************************************
    FILENAME        :   view_topic.php
    PURPOSE OF FILE :   Allows viewing of a topic
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

if (isset($_GET['t'])) $tid = $_GET['t'];
$limit = $config['postcount'];
$start = isset($_GET['start']) ? $_GET['start'] : 0;
    
$sql = $data->select_query("forumtopics", "WHERE id=$tid");
$topic = $data->fetch_array($sql);

$sql = $data->select_query("forums", "WHERE id={$topic['forum']}");
$forum = $data->fetch_array($sql);

$sql = $data->select_query("forumposts", "WHERE topic=$tid");
$numposts = $data->num_rows($sql);

$pagelimit = ($numposts-$start) <= $limit ? ($numposts-$start) : $limit;
$sql = $data->select_query("forumposts", "WHERE topic=$tid ORDER BY dateposted DESC LIMIT $start, $pagelimit");
$posts = array();
while($temp = $data->fetch_array($sql))
{
    $sql2 = $data->select_query("records", "WHERE uname='{$temp['userposted']}'");
    $temp2 = $data->fetch_array($sql2);
    $temp['useravy'] = $temp2['avyfile'];
    $temp['sig'] = $temp2['sig'];
    $posts[] = $temp;
}

$data->delete_query("forumnew", "uname='{$check['uname']}' AND topic=$tid", "", "", false);

$data->update_query("forumtopics", "numviews = numviews + 1", "id=$tid", "", "", false);

//Pagenation working out
if ($numposts > 0) 
{
    $num_pages = ceil($numposts / $limit);
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
$tpl->assign("posts", $posts);
$tpl->assign("topic", $topic);
$tpl->assign("numposts", $numposts);
$tpl->assign("limit", $pagelimit);
$tpl->assign("forum", $forum);
$pagenum = 3;
?>