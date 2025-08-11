<?php
/**************************************************************************
    FILENAME        :   mythings.php
    PURPOSE OF FILE :   Displays items that a user owns. Allows user to edit those items.
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
location("Viewing My Things", $check["uid"]);

/********************************************Check if user is allowed*****************************************/
$uname = $check["uname"];
$pagenum = 1;

if (isset($_GET['cat'])) $cat = $_GET['cat']; else $cat = "";
if (isset($_GET['action'])) $action = $_GET['action'];
if (isset($_GET['id'])) $id = $_GET['id'];

switch($cat)
{
    case "album" :
        $pagenum = 2;
        $sql = $data->select_query("album_track", "WHERE ID = '$id' AND owner='$uname'");
        if ($data->num_rows($sql) == 0) error_message("The album you are looking for either does not exist, or you are not the owner");
        $album = $data->fetch_array($sql);
        $sql = $data->select_query("photos", "WHERE album_id = '$id'");
        $numphotos = $data->num_rows($sql);
        $photos = array();
        while ($photos[] = $data->fetch_array($sql));
        $tpl->assign("album", $album);
        $tpl->assign("numphotos", $numphotos);
        $tpl->assign("photos", $photos);
        $tpl->assign("photopath", $config["photopath"] . "/");
        if($action == "add")
        {
            $pagenum=3;
            if($_POST['Submit'] == "Upload Photo")
            {
                if ($_FILES['filename']['name'] == '')
                {
                    error_message("You need to select a file to upload");
                    exit;
                }
                if (($_FILES['filename']['type'] == 'image/gif') || ($_FILES['filename']['type'] == 'image/jpeg') || ($_FILES['filename']['type'] == 'image/png') || ($_FILES['filename']['type'] == 'image/pjpeg')) 
                {
                    $filestuff = uploadpic($_FILES['filename'], $config['photox'], $config['photoy'], true);
                    $filename = $filestuff['filename'];
                    $desc = $_POST['caption'];
                    $album = $data->fetch_array($data->select_query("album_track", "WHERE ID=$id"));
                    $insert = sprintf("'', %s, %s, %s, $timestamp",
                                        safesql($filename, "text"),
                                        safesql($desc, "text"),
                                        safesql($id, "int"));
                    if($config['confirmphoto'] == 1 && $album['allowed'] == 1 && !($check['level'] == 0 || $check['level'] == 1))
                    {
                        $insert .= ", 0";
                    }
                    else
                    {
                        $insert .= ", 1";
                    }
                    $data->insert_query("photos", $insert, "", "", false) ;
                    $data->update_query("album_track", "numphotos = numphotos + 1", "ID='$id'", "", "", false);
                    if($config['confirmphoto'] == 1 && $album['allowed'] == 1 && !($check['level'] == 0 || $check['level'] == 1))
                    {
                        $extrabit = "The administrator first needs to look at the photo and publish it before it will be visible in your photo album.";
                        publish_mail($check['uname'], "Photo", $desc);
                    }
                    echo "<script> 
                    if(confirm('Your photo has been added. $extrabit Do you wish to add another photo?'))
                    {
                        window.location='index.php?page=mythings&cat=album&action=add&ex=nomenu&id=$id';
                    }
                    else
                    {
                        alert('Don\\'t forget to refresh the album page to see the new photos');
                        window.close();
                    }
                    </script>\n";
                    exit;
                } 
                else
                {
                    error_message("Sorry, we only accept .gif, .jpg, .jpeg or .png images.<br />And the file that you wish to upload is a {$_FILES['filename']['type']}");
                }
            }
        } 
        elseif($action=="modify")
        {
            $pagenum = 4;
            $albumid = $_GET['id'];
            $photoid = $_GET['pid'];
            if ($data->num_rows($data->select_query("album_track",  "WHERE ID = $id AND owner = '$uname'")))
            {
                $photo_query = $data->select_query("photos", "WHERE album_id='$albumid' AND ID='$photoid'");
            }
            else 
            {
                error_message("The photo you are looking for either does not exist, or you are not the owner");
            }
            $photo = $data->fetch_array($photo_query);
            
            $where = $config['photopath'] . '/';
            $sub = $_POST['Submit'];
            if ($sub == "Submit") 
            {
                if ($_FILES['filename']['name'] != '') 
                {
                    if (($_FILES['filename']['type'] == 'image/gif') || ($_FILES['filename']['type'] == 'image/jpeg') || ($_FILES['filename']['type'] == 'image/png') || ($_FILES['filename']['type'] == 'image/pjpeg')) 
                    {
                        $filestuff = uploadpic($_FILES['filename'], $config['photox'], $config['photoy'], true);
                        $filename = safesql($filestuff['filename'], "text");;
                        $desc = safesql($_POST['caption'], "text");
                        if($config['confirmphoto'] == 1 && $album['allowed'] == 1 && !($check['level'] == 0 || $check['level'] == 1))
                        {
                            $data->update_query("photos", "filename=$filename, date='$timestamp', allowed = 0", "ID=$photoid", "", "", false);
                        }
                        else
                        {
                            $data->update_query("photos", "filename=$filename, date='$timestamp'", "ID=$photoid", "", "", false);
                        }
                        $data->update_query("album_track", "numphotos = numphotos + 1", "ID='$id'", "", "", false);
                        if($config['confirmphoto'] == 1 && $album['allowed'] == 1 && !($check['level'] == 0 || $check['level'] == 1))
                        {
                            $extra = "The administrator first needs to look at the photo and publish it before it will be visible in your photo album";
                            publish_mail($check['uname'], "Photo", $desc);
                        }
                        echo "<script> alert('Photo updated. $extra'); window.close();</script>\n";
                        exit;

                    } 
                    else
                    {
                        error_message("Sorry, we only accept .gif, .jpg, .jpeg or .png images.<br />And the file that you wish to upload is a {$_FILES['filename']['type']}");
                    }
                } 
                $desc = safesql($_POST['caption'], "text");
                if ($desc != '')
                {
                    $data->update_query("photos", "caption='$desc' WHERE ID=$photoid", "", "", false);	
                    echo "<script> alert('Caption updated'); window.close();</script>\n";
                    exit;
                }
                $noshow = true;
            }
            $tpl->assign("photo", $photo);
        } 
        elseif($action=="delphoto")
        {
            $pid = $_GET['pid'];
            if ($data->num_rows($data->select_query("album_track",  "WHERE ID = $id AND owner = '$uname'")))
            {    
                $sql = $data->select_query("photos", "WHERE ID=$pid");
                $photo = $data->fetch_array($sql);
                unlink($config['photopath'] . '/' . $photo['filename']);
                $sqlq = $data->delete_query("photos", "ID=$pid AND album_id='$id'", "Albums", "Photo for album $aid deleted by {$uname}");
                $data->update_query("album_track", "numphotos = numphotos - 1", "ID=$id AND owner='$uname'", "", "", false);
                header("location: index.php?page=mythings&cat=album&id=$id");
            } 
            else
            {
                error_message("The photo you are looking for either does not exist, or you are not the owner");
            }
        }
        elseif ($action == "delete")
        {
		    $sqlq = $data->delete_query("album_track", "ID=$id AND owner='$uname'", "", "", false);
            if ($sqlq) 
            { 
                $sql = $data->select_query("photos", "WHERE album_id=$id");
                while ($photo = $data->fetch_array($sql))
                {
                    unlink($config['photopath'] . '/' . $photo['filename']);
                }
                $sqlq = $data->delete_query("photos","album_id=$id", 'Albums',  "$uname delete his album $id");	
                    echo "<script> alert('The album has been deleted'); window.location = 'index.php?page=mythings';</script>\n";
                    exit;                
            } 
            else error_message("The album you are looking for either does not exist, or you are not the owner");
        } 
        break;
    case "articles":
        if ($action == "edit")
        {
            $pagenum=6;
            $query = $data->select_query("patrol_articles", "WHERE ID=$id AND owner='$uname'");
            if ($data->num_rows($query) == 0)
            {
                error_message("The article you are looking for does not exist, or you are not the owner");
            }
            
            $row = $data->fetch_array($query);
            $detail = $row['detail'];
            $quer = $data->select_query("album_track");
            $res = $data->fetch_array($quer);
            $i = 0;
            $albumid = array();
            $albumname = array();
            do 
            { 
                $i++;
                $albumid[] = $res['ID'];
                $albumname[] = $res['album_name'];
            } while ($res = $data->fetch_array($quer));
            $tpl->assign('numalbum', $i);
            $tpl->assign('id', $albumid);
            $tpl->assign('albumname', $albumname);
            $tpl->assign("detail", $detail);
            $tpl->assign("article", $row);
            $submit=$_POST["Submit"];
            if ($submit == "Update") 
            {
                if ($_POST['title'] == '')
                {
                    error_message("You need to enter a title for the article");
                    exit;
                }
                if ($_POST['story'] == '')
                {
                    error_message("You need to type out the article");
                    exit;
                }
                
                $title = safesql($_POST['title'], "text");
                $dh = safesql($_POST['dh'], "date");
                $cat = safesql($_POST['cat'], "int");
                $story = safesql($_POST['story'], "text", false);
                $auth = safesql($_POST['auth'], "text");
                if ($_FILES['filename']['error'] == 0 && $_FILES['filename']['name'] != "CLEAR") 
                {
                    if (($_FILES['filename']['type'] == 'image/gif') || ($_FILES['filename']['type'] == 'image/jpeg') || ($_FILES['filename']['type'] == 'image/png') || ($_FILES['filename']['type'] == 'image/pjpeg')) 
                    {
                        $filestuff = uploadpic($_FILES['filename'], 350, 350);
                        $filename = $filestuff['filename'];
                        $desc = $_POST['caption'];
                    } 
                    else
                    {
                        error_message("Sorry, we only accept .gif, .jpg, .jpeg or .png images.<br />And the file that you wish to upload is a {$_FILES['filename']['type']}");
                    }
                }
                
                if ($_FILES['filename']['name'] == "CLEAR") $filename = NULL;
                
                if ($config['confirmarticle'] == 1 && !($check['level'] == 0 || $check['level'] == 1)) $allow = 0;
                else $allow = 1;
                
                if ($filename != "")
                {
                    $filename = safesql($filename, "text");
                    $sql = $data->update_query("patrol_articles", "title=$title, detail=$story, date_post=$timestamp, date_happen=$dh, album_id=$cat, author=$auth, pic=$filename, allowed = $allow",
                                                "ID=$id", "Admin Articles", "Edited Article $id");	
                }
                else
                {
                    $sql = $data->update_query("patrol_articles", "title=$title, detail=$story, date_post=$timestamp, date_happen=$dh, album_id=$cat, author=$auth, allowed = $allow",
                                                "ID=$id", "Admin Articles", "Edited Article $id");	            
                }
                if ($config['confirmarticle'] == 1 && !($check['level'] == 0 || $check['level'] == 1)) 
                {
                    publish_mail($check['uname'], "Article", $title);
                    $extra = "The administrator needs to republish your article now that you have edited it.";
                }
                else $extra = "";
                
                echo "<script> alert('Your Article has been updated.$extra'); window.location = 'index.php?page=mythings';</script>\n";
                exit;
            } 
            $tpl->assign("isedit", "adv");
        }
        elseif ($action == "delete")
        {
        	$sqlq = $data->delete_query("patrol_articles", "ID=$id AND owner='$uname'", "Articles", "Deleted $id");
            if ($sqlq)
            {
                echo "<script> alert('The article has been deleted'); window.location = 'index.php?page=mythings';</script>\n";
                exit;                
            }
        }
        break;
    case "events":
        if ($action == "edit")
        {
            $pagenum = 10;
            $calsql = $data->select_query("calendar_items", "WHERE id = $id");
            $calsqldetail = $data->select_query("calendar_detail", "WHERE id = $id");
            $items = $data->fetch_array($calsql);
            $numdetail = $data->num_rows($calsqldetail);
            $detail = $data->fetch_array($calsqldetail);
            $tpl->assign('detail', $detail['detail']);
            $tpl->assign('item', $items);
            
            $submit = $_POST['Submit'];
            if ($submit == "Update Item") 
            {
                if ($_POST['summary'] == '')
                {
                    error_message("You need to enter a title for the article");
                    exit;
                }
                if ($_POST['sdate'] == '')
                {
                    error_message("You need to supply a start date");
                    exit;
                }
                if (!validdate($_POST['sdate']))
                {
                    error_message("The start date you supplied is in the incorrrect format. It needs to be yyyy-mm-dd. You can use the ... button next to the text box to open a calender");
                    exit;
                }
                if ($_POST['edate'] == '')
                {
                   $_POST['edate'] = $_POST['sdate'];
                }   
                elseif (!validdate($_POST['edate']))
                {
                    error_message("The end date you supplied is in the incorrrect format. It needs to be yyyy-mm-dd. You can use the ... button next to the text box to open a calender");
                    exit;
                }       
                
                if(strtotime($_POST['edate']) < strtotime($_POST['sdate']))
                {
                    error_message("Your end date is before your start date");
                    exit;
                }
                if ($_POST['is_there_details'] == 1 && $_POST['story'] == '')
                {
                    error_message("You indicated that you want to add extra information, but you didn't.");
                    exit;
                }
                $summary = safesql($_POST['summary'], "text");
	            $startdate = safesql($_POST['sdate'], "date");
	            $enddate = safesql($_POST['edate'], "date");
                $isdetail = safesql($_POST['is_there_details'], "int");
                $detail = safesql($_POST['story'], "text", false);
                
                if ($config['confirmevent'] == 1 && !($check['level'] == 0 || $check['level'] == 1)) 
                {
                    $allow = 0;
                    $extra = "The administrator will first need to republish your event before it will be shown on the website";
                    publish_mail($check['uname'], "Article", $summary);
                }
                else $allow = 1;
                $sql = $data->update_query("calendar_items", "summary = $summary, startdate = $startdate, enddate = $enddate, detail = $isdetail, allowed = $allow", "id = $id", "Calendar Admin", "Updated item $itemid");
                if ($isdetail == 1) 
                {
                    if ($numdetail == 1) 
                    {
                        $sql2 = $data->update_query("calendar_detail", "detail = $detail", "id = $id", "", "", false);
                    } 
                    else 
                    {
                        $sql2 = $data->insert_query("calendar_detail", "$id, $detail", "", "", false);
                    }
                    if ($sql && $sql2)
                    {
                        echo "<script> alert('Your calendar event has been updated.$extra'); window.location = 'index.php?page=mythings';</script>";
                        exit; 
                    }
                    else
                    {
                        error_message("There was a problem updating the event details in the database. Please contact the webmaster");
                    }
                }
                else
                {
                    if ($sql)
                    {
                        echo "<script> alert('Your calendar event has been updated.$extra'); window.location = 'index.php?page=mythings';</script>";
                        exit; 
                    }
                    else
                    {
                        error_message("There was a problem updating the event details in the database. Please contact the webmaster");
                    }
                }

                $action = '';
            }
            $tpl->assign("isedit", "adv");
        }
        elseif ($action == "delete")
        {
        	$sqlq = $data->delete_query("calendar_items", "id=$id AND owner='$uname'", "Calendar", "Deleted $id");
        	$sqlq = $data->delete_query("calendar_detail", "id=$id", "", "", false);
            if ($sqlq)
            {
                echo "<script> alert('The event has been deleted'); window.location = 'index.php?page=mythings';</script>\n";
                exit;                
            }
        }
        break;
    case "downloads":
        if ($action == "edit")
        {
            $pagenum = 8;
            $sql = $data->select_query("downloads", "WHERE id=$id AND owner = '{$check['uname']}'");
            $down = $data->fetch_array($sql);
            $sql = $data->select_query("download_cats");
            $cats = array();
            $numcats = 0;
            while ($row = $data->fetch_array($sql))
            {
                $temp = unserialize($row['auth']);
                if($temp == "")
                {
                    $cats[] = $row;
                    $numcats++;
                }
                else
                {
                    if($temp[$check['team']] == 1)
                    {
                        $cats[] = $row;
                        $numcats++;
                    }
                }
            }
            
            if($_POST['Submit'] == 'Submit')
            {
                if ($_POST['name'] == '')
                {
                    error_message("You need to supply a name");
                    exit;
                }
                $name = safesql($_POST['name'], "text");
                $desc = safesql($_POST['desc'], "text");
                $cid = safesql($_POST['cat'], "text", false);
                $file = "";
                if ($_FILES['file']['name'] != "")
                {
                    $where = $config['downloadpath'] . "/";
                    if (!file_exists($where . $_FILES['file']['name']))
                    {
                        move_uploaded_file($_FILES['file']['tmp_name'],$where . $_FILES['file']['name']);
                    }
                    $file = safesql($_FILES['file']['name'], "text");
                }
                
                if ($config['confirmdownload'] == 1 && !($check['level'] == 0 || $check['level'] == 1))
                {
                    if ($file != "")
                    {
                        $data->update_query("downloads", "name = $name, descs = $desc, cat = $cid, file = $file, numdownloads = 0, size = '".ceil($_FILES['file']['size'] / 1024)."', allowed = 0", "id=$id", "Downloads", "Updated Download $name");
                        $extra = "You have changed the file, the administrator first needs republish the download before it will be available to download";
                        publish_mail($check['uname'], "Download", $name);
                    }
                    else
                    {
                        $data->update_query("downloads", "name = $name, descs = $desc, cat = $cid", "id=$id", "Downloads", "Updated Download $name");
                    }
                }
                else
                {
                    if ($file != "")
                    {
                        $data->update_query("downloads", "name = $name, descs = $desc, cat = $cid, file = $file, numdownloads = 0, size = '".ceil($_FILES['file']['size'] / 1024)."'", "id=$id", "Downloads", "Updated Download $name");
                    }
                    else
                    {
                        $data->update_query("downloads", "name = $name, descs = $desc, cat = $cid", "id=$id", "Downloads", "Updated Download $name");
                    }
                }
                echo "<script> alert('Your download has been updated.$extra'); window.location = 'index.php?page=mythings';</script>\n";
                exit;
            }
    
            $tpl->assign("cat", $cats);
            $tpl->assign("numcats", $numcats);
            $tpl->assign("down", $down);
            $tpl->assign("action", "edit");
        }
        elseif ($action == "delete")
        {
            $sql = $data->select_query("downloads", "WHERE ID=$id AND owner='$uname'");
            $download = $data->fetch_array($sql);
            unlink($config['downloadpath'] . '/' . $download['file']);
            
            $sqlq = $data->delete_query("downloads", "id=$id AND owner='$uname'", "Downloads", "Deleted $id");
            if ($sqlq)
            {
                echo "<script> alert('The download has been deleted'); window.location = 'index.php?page=mythings';</script>\n";
                exit;                
            }
        }
        break;
    case "newsitems":
        if ($action == "edit")
        {
            $pagenum = 9;
            $sql = $data->select_query("newscontent", "WHERE id=$id AND owner = '{$check['uname']}'");
            $shownews = $data->fetch_array($Show);
            $shownews['news'] = $shownews['news'];
            $tpl->assign("shownews", $shownews);
            
            $submit=$_POST["submit"];
            if ($submit == "Submit") 
            {
                if ($_POST['title'] == '')
                {
                    error_message("You need to supply a title");
                    exit;
                }   
                if ($_POST['story'] == '')
                {
                    error_message("You need to supply a news story");
                    exit;
                }                
                $news = safesql($_POST['story'], "text", false);
                $title = safesql($_POST['title'], "text");
                if ($config['confirmnews'] == 1 && !($check['level'] == 0 || $check['level'] == 1)) $allow = 0;
                else $allow = 1;
                $sql = $data->update_query("newscontent", "title=$title, news=$news, allowed = $allow",
                                                "id=$id", "News", "Edited News $id");		
                if ($config['confirmnews'] == 1 && !($check['level'] == 0 || $check['level'] == 1)) 
                {
                    $extra = "The administrator needs to republish your news item now that you have edited it.";
                    publish_mail($check['uname'], "News", $title);
                }
                else $extra = "";
                
                echo "<script> alert('Your news item has been updated.$extra'); window.location = 'index.php?page=mythings';</script>\n";
                exit;                                                           
            }
            $tpl->assign("isedit", "adv");
        }
        elseif ($action == "delete")
        {
        	$sqlq = $data->delete_query("newscontent", "id=$id", "News Items", "Deleted news");
            if ($sqlq)
            {
                echo "<script> alert('The news item has been deleted'); window.location = 'index.php?page=mythings';</script>\n";
                exit;                
            }
        }
        break;
}

if ($action=="owner")
{
    $pagenum=5;
    $team = safesql($check['team'], "text");
    if ($check['level'] > 2)
        $sql = $data->select_query("authuser", "WHERE team = $team OR team = 'Admin' OR team = 'Scouters' OR team='TLs' ORDER BY uname");
    else $sql = $data->select_query("authuser", "ORDER BY uname");
    $numpeople = $data->num_rows($sql);
    $people = array();
    while ($people[] = $data->fetch_array($sql));
    $tpl->assign("numpeople", $numpeople);
    $tpl->assign("people", $people);
    
    if($_POST['Submit'] == "Submit")
    {
        $owner = safesql($_POST['owner'], "text");
        $uname = safesql($uname, "text");
        if($cat=="album")
        {
            $sql = $data->update_query("album_track", "owner=$owner", "ID=$id AND owner=$uname", "Transfer Owner", "Owner for album $id transfered from $uname to $owner"); 
        }
        elseif ($cat=="articles")
        {
            $sql = $data->update_query("patrol_articles", "owner=$owner", "ID=$id AND owner=$uname", "Transfer Owner", "Owner for article $id transfered from $uname to $owner"); 
        }
        elseif($cat == "events")
        {
            $sql = $data->update_query("calendar_items", "owner=$owner", "ID=$id AND owner=$uname", "Transfer Owner", "Owner for event $id transfered from $uname to $owner"); 
        }
        elseif($cat == "downloads")
        {
            $sql = $data->update_query("downloads", "owner=$owner", "ID=$id AND owner=$uname", "Transfer Owner", "Owner for download $id transfered from $uname to $owner"); 
        }
        elseif($cat == "newsitems")
        {
            $sql = $data->update_query("newscontent", "owner=$owner", "ID=$id AND owner=$uname", "Transfer Owner", "Owner for news item $id transfered from $uname to $owner"); 
        }
        if ($sql)
        {            
            $owner = $_POST['owner'];        
            echo "<script> alert('The new owner of the item is now $owner'); window.close();</script>\n";
            exit;  
        }
    }
} 
elseif ($action=="adddown")
{
    $pagenum = 8;
    $sql = $data->select_query("download_cats");
    $cats = array();
    $numcats = 0;
    while ($row = $data->fetch_array($sql))
    {
        $temp = unserialize($row['auth']);
        if($temp == "")
        {
            $cats[] = $row;
            $numcats++;
        }
        else
        {
            if($temp[$check['team']] == 1)
            {
                $cats[] = $row;
                $numcats++;
            }
        }
    }
    
    if ($numcats > 0)
    {
        $tpl->assign("cat", $cats);
        $tpl->assign("numcats", $numcats);
    }
    else
    {
        echo "<script> alert('There are no download categories at the moment'); window.close();</script>\n";
        exit;  
    }
    
    if($_POST['Submit'] == 'Submit')
    {
        
        if ($_POST['name'] == '')
        {
            error_message("You need to supply a name");
            exit;
        }    
        $name = safesql($_POST['name'], "text");
		$desc = safesql($_POST['desc'], "text");
        $cid = safesql($_POST['cat'], "text");
		$where = $config['downloadpath'] . "/";
        if (!file_exists($where . $_FILES['file']['name']))
        {
            move_uploaded_file($_FILES['file']['tmp_name'],$where . $_FILES['file']['name']);
        }
        $file = safesql($_FILES['file']['name'], "text");
        if ($config['confirmdownload'] == 1 && !($check['level'] == 0 || $check['level'] == 1))
        {
            $data->insert_query("downloads", "'', $name, $desc, $cid, $file, '0', '".ceil($_FILES['file']['size'] / 1024)."','{$check['uname']}', 0", "Downloads", "Added Download $name");
            $addon = "The administrator first needs to publish your file before it will be available on the website";
            publish_mail($check['uname'], "Download", $name);
        }
        else
        {
            $data->insert_query("downloads", "'', $name, $desc, $cid, $file, '0', '".ceil($_FILES['file']['size'] / 1024)."','{$check['uname']}', 1", "Downloads", "Added Download $name");
        }
        echo "<script> alert('Your file has been added.$addon'); window.close();</script>\n";
        exit;
    }
}
elseif ($action=="addnews")
{
    $pagenum = 9;
    if($_POST['submit'] == "Submit")
    {
        if ($_POST['title'] == '')
        {
            error_message("You need to supply a title");
            exit;
        }   
        if ($_POST['story'] == '')
        {
            error_message("You need to supply a news story");
            exit;
        }  
        $news = safesql($_POST['story'], "text", false);
        $title = safesql($_POST['title'], "text");
        if ($config['confirmnews'] == 1 && !($check['level'] == 0 || $check['level'] == 1))
        {
            $Add = $data->insert_query("newscontent", "'', $title, $news, $timestamp, '{$check['uname']}', 0", 'News Admin', "Added news item");
            $addon = "The administrator first needs to publish your news item before it will be available on the website";
            publish_mail($check['uname'], "News", $title);
        }
        else
        {
            $Add = $data->insert_query("newscontent", "'', $title, $news, $timestamp, '{$check['uname']}', 1", 'News Admin', "Added news item");
        }
        echo "<script> alert('Your news item has been added.$addon'); window.close();</script>";
        exit;
    }
    $tpl->assign("isedit", "adv");
}

if ($action == "delete")
    header("location: index.php?page=mythings");

$uname = $check['uname'];
$sql = $data->select_query("album_track", "WHERE owner = '$uname'");
$numalbums = $data->num_rows($sql);
$album = array();
while ($album[] = $data->fetch_array($sql));

$sql = $data->select_query("patrol_articles", "WHERE owner = '$uname'");
$numart = $data->num_rows($sql);
$articles = array();
while ($articles[] = $data->fetch_array($sql));

$sql = $data->select_query("calendar_items", "WHERE owner = '$uname'");
$numevents = $data->num_rows($sql);
$events = array();
while ($events[] = $data->fetch_array($sql));

$sql = $data->select_query("downloads", "WHERE owner = '$uname'");
$numdown = $data->num_rows($sql);
$downloads = array();
while ($downloads[] = $data->fetch_array($sql));

$sql = $data->select_query("newscontent", "WHERE owner = '$uname'");
$numnews = $data->num_rows($sql);
$newsitems = array();
while ($newsitems[] = $data->fetch_array($sql));


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


$authorization = array();
$authorization['album']=0;
$authorization['article']=0;
$authorization['notice']=0;
$authorization['event']=0;
$authorization['down']=0;
$authorization['news']=0;

$authsql = $data->select_query("auth", "WHERE page='addalbum' OR page='addarticle' OR page='addnotice' OR page='addevent' OR page='adddown' OR page='addnews'");
while($authtemp = $data->fetch_array($authsql))
{
    if (isset($authtemp['level']))
    {
        $auths = unserialize($authtemp['level']);
    }
    
    $usergroup = $check['team'];
    
    if (!isset($usergroup) || $usergroup == '')
    {
        $usergroup = 'guest';
    }
    
    if (isset($auths)) 
    {
        $comviewallowed = $auths[$usergroup];
    }
    
    switch($authtemp['page'])
    {
        case "addalbum":
            if (isset($auths))
                $authorization['album']=$auths[$usergroup];
            break;
        case "addarticle":
            if (isset($auths))
                $authorization['article']=$auths[$usergroup];
            break;
        case "addnotice":
            if (isset($auths))
                $authorization['notice']=$auths[$usergroup];
            break;
        case "addevent":
            if (isset($auths))
                $authorization['event']=$auths[$usergroup];
            break;
        case "adddown":
            if (isset($auths))
                $authorization['down']=$auths[$usergroup];
            break;
        case "addnews":
            if (isset($auths))
                $authorization['news']=$auths[$usergroup];
            break;
    }
}

$tpl->assign("action", $action);
$tpl->assign("auth", $authorization);
$tpl->assign("numalbums", $numalbums);
$tpl->assign("albums", $album);
$tpl->assign("numart", $numart);
$tpl->assign("articles", $articles);
$tpl->assign("numnotes", $numnotes);
$tpl->assign("notes", $notes);
$tpl->assign("numevents", $numevents);
$tpl->assign("events", $events);
$tpl->assign("numdown", $numdown);
$tpl->assign("downloads", $downloads);
$tpl->assign("numnews", $numnews);
$tpl->assign("newsitems", $newsitems);
$tpl->assign('editFormAction', $editFormAction);

$dbpage = true;
$pagename = "mythings";
?>