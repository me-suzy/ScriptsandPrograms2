<?php
/**************************************************************************
    FILENAME        :   photos.php
    PURPOSE OF FILE :   Shows photo albums
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
location("Photo Albums", $check["uid"]);
 /***********************************************************
 	Simple Photo Album V1.0
	Author: Walther Lalk <wlalk@hotpop.com>
 *************************************************************/

 /*******************Initilize Varibles*********************/
 $num_albums = 0;
 $number_photos = 0;
 if (!$inarticle) {$albumid = 0;}
 $album_name = '';
 $limit = $config['photos_per_page'];
 if ($limit == 0) $limit = 6;
 $where =  $config['photopath'] . "/";
 $pagenum=1;
 $number_of_photos = 0;
if(isset($_GET['patrol'])) $ppatrol = $_GET['patrol'];
 
 /*****************Build list of all patrol albums**************/
 if (!$inarticle) 
 {
     if (isset($ppatrol)) 
     {
        $sql = $data->select_query("album_track", "WHERE patrol = '$ppatrol' AND allowed = 1");
     } 
     else 
     {
        $sql = $data->select_query("album_track", "WHERE patrol = 'All' AND allowed = 1"); 
     }
    $num_albums = $data->num_rows($sql);
   
   if ($num_albums == 0 && isset($ppatrol))
    {
        show_message_back("There are no photo albums for this patrol yet.");
    }
    elseif ($num_albums == 0)
    {
        show_message_back("There are no photo albums.");
    }
        
     if (!$num_albums)
     {
        $albumid = 0;
     }
     else
     { 
                
        $album_array = array();
        $ranphoto = array();
        $num_albums = 0;
        while ($temp = $data->fetch_array($sql))
        {
            if ($data->num_rows($data->select_query("photos", "WHERE album_id = '{$temp['ID']}'")) > 0)
            {
                $sql2 = $data->select_query("photos", "WHERE album_id = '{$temp['ID']}'");
                $tempphoto = array();
                while($tempphoto[] = $data->fetch_array($sql2));
                $number = rand(0, $data->num_rows($sql2)-1);
                $temp['randomphoto'] = $tempphoto[$number]['ID'];
                $num_albums++;
                $album_array[] = $temp;
            }
        }
     }
     /***********Get posted varibles*************/
     if (isset($_POST['albumiden'])) 
     {
        $albumid = $_POST['albumiden'];
     }
     elseif (isset($_GET['album']) && $_GET['album'] != 0) 
     {
        $albumid = $_GET['album']; 
     }
     if (isset($_GET['start'])) $start = $_GET['start'];
     if (!isset($start))
     {
        $start = 0;
     }
 }
 
 /*************Display album on screen******************/
 if ($albumid != 0) 
 {
    $pagenum=2;
    //First get check if the album exists
    $sql = $data->select_query("album_track", " WHERE ID = $albumid");
    $number_albums = $data->num_rows($sql);
    $album_info = $data->fetch_array($sql);
    $view_album_name = $album_info['album_name'];
    if ($number_albums == 0 && !$inarticle) 
    { 
        show_message_back("No such album");
    }
    elseif ($number_albums == 0 && $inarticle)
    {
        $number_of_photos = 0;
    }
    else
    {
        $next = false;
        $prev=false;
        
        //then get photo file names and captions from database
        $photosql = $data->select_query("photos", "WHERE album_id = $albumid AND allowed = 1 ORDER BY date ASC");
        $number_of_photos = $data->num_rows($photosql);
        $pagelimit = ($number_of_photos-$start) <= $limit ? ($number_of_photos-$start) : $limit ;
        if (!$inarticle) 
        {
            $photosql = $data->select_query("photos", "WHERE album_id = $albumid AND allowed = 1 ORDER BY date ASC LIMIT $start, $pagelimit");
        }
    }
    
    //Pagenation working out
    if (!$inarticle)
    { 
        if ($number_of_photos > 0) 
        {
            $num_pages = ceil($number_of_photos / $limit);
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
        else 
        {
            show_message("There are no photos in that album yet.");
        }
    }
    
    if($number_of_photos > 0)
    {
        //display all photos
        $photo = array();
        while ($photo[] = $data->fetch_array($photosql));
    }
}
$tpl->assign('number_of_albums', $num_albums);
if (isset($album_array)) $tpl->assign('albums', $album_array);
$tpl->assign('album_id', $albumid);
if (isset($view_album_name))$tpl->assign('view_album_name', $view_album_name);
if (isset($number_of_photos))$tpl->assign('number_of_photos', $number_of_photos); 
$tpl->assign('location', $where);
if (isset($photo))$tpl->assign('photo', $photo);
if (isset($num_pages)) $tpl->assign('numpages', $num_pages);
if (isset($curr_page)) $tpl->assign('currentpage', $curr_page);
if (isset($next))$tpl->assign('next', $next);
if (isset($prev))$tpl->assign('prev', $prev);
$tpl->assign('num_per_page', $limit);
if (isset($next_start)) $tpl->assign('next_start', $next_start);
if (isset($prev_start)) $tpl->assign('prev_start', $prev_start);
$tpl->assign('limit', $pagelimit);
$tpl->assign("numphotos", $number_of_photos);
$dbpage = true;
$pagename = "photoalbum";
?>