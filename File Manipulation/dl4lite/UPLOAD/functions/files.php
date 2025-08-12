<?php

/*********************************************************
 * Name: files.php
 * Author: Dave Conley
 * Contact: realworld@blazefans.com
 * Description: Functions for download file control
 * Version: 4.00
 * Last edited: 23rd March, 2004
 *********************************************************/

$loader = new files();

class files
{
	var $html;
	var $output;
	
	function files()
	{
		global $IN, $OUTPUT;

		$this->html = $OUTPUT->load_template("skin_files");

        $IN['dlid'] = intval($IN['dlid']);

		switch($IN["ACT"])
		{
			case 'useradddl':
			$this->upload();
			break;

			case 'ratefile':
			$this->rateFile($IN["dlid"]);
			break;

			case 'addcomment':
			$this->addComment($IN["dlid"]);
			break;

			case 'editcomment':
			$this->edit_comment();
			break;

			case 'deletecomment':
			$this->delete_comment();
			break;

			case 'email':
			$this->email($IN["dlid"]);
			break;

			case 'report':
			$this->report($IN["blid"]);
			break;

			case 'editdl':
			case 'deleteimg':
				$this->editdl();
				break;

			case 'deletedl':
			    $this->deletedl();
			    break;
				
			case 'massmod':
				$this->files_massmod();
				break;
		}
	}

    // List all files in this category
    function listAll($catID = 0)
    {
	    global $DB, $IN, $CONFIG, $rwdInfo, $std;
	    if (!$IN["sortvalue"])
		    $sortvalue = $CONFIG["default_sort"];
	    else
		    $sortvalue = $IN["sortvalue"];
	    if (!$IN["order"])
		    $order = $CONFIG["default_order"];
	    else
		    $order = $IN["order"];
	    if (!$IN["limit"])
		    $limit = 0;
	    else
		    $limit = $IN["limit"];
	    
		// Set category data if not already saved... which it probably should be
		if ( !$rwdInfo->cat_cache[$catID] )
		{
			$catres = $DB->query("SELECT * FROM dl_categories WHERE cid=$catID");
			$catrow = $DB->fetch_row();
			$rwdInfo->cat_cache[$catID] = $catrow;
		}
	    
	    $result = $DB->query(  "SELECT l.*
				    FROM dl_links l
					WHERE l.categoryid=$catID AND l.approved=1
				    ORDER BY pinned DESC, $sortvalue $order LIMIT $limit , {$CONFIG[links_per_page]}");

		$pages = $std->pages($rwdInfo->cat_cache[$catID]["downloads"], $CONFIG["links_per_page"], "?cid=$catID&sortvalue=$sortvalue&order=$order");
	    
		$order_box = $this->order_box($catID, $sortvalue, $order);
		$numfiles = $DB->num_rows();
		if (($filerow = $DB->fetch_row()))
	    {
			$data = array( "cat_name" => $rwdInfo->cat_cache[$catID]["name"],
						   "order_boxes" => $order_box,
						   "pages" => $pages);
				   
		    $this->output .= $this->html->cat_listing_head($data);
		    
		    do
		    {
				$data = $this->parse_file_data($filerow, NULL, 0, 0, 0);
			    $data = $std->my_stripslashes($data);
			   	$this->output .= $this->html->cat_listing_row($data);
		    } while ( $filerow = $DB->fetch_row($result) );
				    
			$data = array(	"order_boxes" => $order_box,
							"pages" => $pages );
					
			$this->output .= $this->html->cat_listing_foot($data);
						    
	    }
		return $numfiles;
    }

   	//
	// Show extended details on this file
	//

	function show($dlid)
    {
	    global $IN, $DB, $CONFIG, $OUTPUT, $std;

		$result = $DB->query(  "SELECT l.*, ft.*
					FROM dl_links l
					LEFT JOIN dl_filetypes ft ON (ft.mimetype=l.fileType)
					WHERE l.did=$dlid");

		if ( !$myrow = $DB->fetch_row($result))
		{
			$std->error(GETLANG("er_nofile"));
			return;
		}
		
		if ( $myrow['approved'] == 0 )
			$std->error(GETLANG("er_unappfile"));

		$std->updateNav(" > $myrow[name]", $myrow["categoryid"]);

		// Update view count
		$DB->query("UPDATE `dl_links` SET `views` = views+1 WHERE `did` = '$dlid'");

		$data = array();
		$data = $this->parse_file_data($myrow);

		$data = $std->my_stripslashes($data);
		
		$this->output .= $this->html->file_view($data);

	}

	function delete_comment()
	{
		global $IN, $DB, $std, $OUTPUT;

        $id = intval($IN['id']);
        $did = intval($IN['did']);
		$std->updateNavDL(" > ".GETLANG("editcomment"), $did);

        $DB->query("SELECT * FROM `dl_links` WHERE `did`='{$did}'");
		$myrow2 = $DB->fetch_row();
		
		$DB->query("SELECT * FROM dl_comments WHERE id=$id");
		if (!$myrow = $DB->fetch_row())
	    {
			$std->error(GETLANG("er_nocomment"));
			return;
	    }
		
		if ( !$std->canModerate("del_comments", $myrow2['categoryid']) &&
			 !$std->groupCanModerate("delComments", $myrow['uid']) )
		{
			$std->error(GETLANG("er_noperms"));
			return;
		}
		if ($IN["confirm"])
		{
			$DB->query("DELETE FROM `dl_comments` WHERE id=$id");
			$DB->query("UPDATE `dl_links` SET comments=comments-1 WHERE did={$did}");
			$OUTPUT->add_output(GETLANG("delcomment")."<br><br>");
				
			return;
			
		}
		else if ($IN["cancel"])
		{
			$OUTPUT->add_output(GETLANG("delcancel")."<br><br>");
				
			// TODO same as above
			$OUTPUT->add_output("+ <a href='admin.php?did={$id}'>".GETLANG("backto")." ".GETLANG("nav_editfiles")."</a><br>");
			$OUTPUT->add_output("+ <a href='admin.php?sid=$sid&area=files&act=editdl&id={$id}'>".GETLANG("backto")." ".GETLANG("nav_editdl")."</a><br>");
			$OUTPUT->add_output("+ <a href='admin.php?sid=$sid&area=main'>".GETLANG("backto")." ".GETLANG("nav_adhome")."</a><br>");
			return;
		}
		else
		{
			$std->warning (GETLANG("warn_commentdel")."<p>"
					."<form method='post' action='index.php?ACT=deletecomment'>"
					."<input type='hidden' name='id' value='{$id}'>"
					."<input type='hidden' name='did' value='{$did}'>"
					."<input type='Submit' name='confirm' value='".GETLANG("yes")."'> <input type='Submit' name='cancel' value='".GETLANG("no")."'> </form>");
		
		}
		
	}
	
	function files_dlMainForm($id, $data, $isACP, $formpost)
	{
		global $CONFIG, $DB, $IN, $OUTPUT, $rwdInfo, $std, $sid;
		
		if ( $data && $id)
			$data['title'] = GETLANG("nav_editdl");
		else
			$data['title'] = GETLANG("nav_adddl");
		
		$data['formpost'] = $formpost;
		
		if ( !$data['author'] )
			$data['author'] = "Unknown";

		if ( $data["download"] )
		{
			if ( $std->isExternalFile($data["download"]) )
				$dl_url = $data["download"];
			else
				$dl_url = $rwdInfo->url."/downloads/".$data["maskName"];
			$row['dlinfo'] = $data["download"]." - ".$data["filesize"]." ".GETLANG("with")." ".$data["downloads"]." ".GETLANG("downloads");
			$row['dlinfo'] .= "  [ <a href='admin.php?sid=$sid&area=files&act=editdl&removefile=1&id=$id'>".GETLANG("delete")."</a> ] [ <a href='$dl_url'>".GETLANG("dlview")."</a> ]";
			if ($data['fileType']) 
			{
				$DB->query("SELECT icon FROM dl_filetypes WHERE `mimetype`='{$data['fileType']}'");
				$ft = $DB->fetch_row();
				if ($ft["icon"])
					$row['typeinfo'] = "<img src='".$rwdInfo->url."/skins/skin".$CONFIG["defaultSkin"]."/mime_types/".$ft["icon"]."' align='center' border='0'> ".$data['fileType'];
				else
					$row['typeinfo'] = $data['fileType'];
			}
			else
				$row['typeinfo'] = GETLANG("unknowntype");
			$data['downloadrows'] .= $this->html->download_link($row);
		}
		else
		{
			$data['downloadrows'] .= $this->html->empty_download();
		}
		if ( $data['realName'] != "" )
		{
			$imgData = "<a href='{$rwdInfo->url}/downloads/{$data['realName']}'>
			{$data['realName']}</a> [{$data['size']}] 
			[ <a href='admin.php?sid=$sid&area=files&act=deleteimg&imgid={$data['id']}&id={$data['did']}'>".GETLANG("delete")."</a> ]";
		}
		else
		{
			$imgData = "<input name='thumb0' type='file' size='30'>";
		}
		
		$row = array();		
		$row['thumbdata'] = $imgData;
		$data['thumbrows'] .= $this->html->thumb_row($row);

		$boxName = "categoryid";
        $data['catlistbox'] = $std->catListBox($data["categoryid"], $boxName, "canUL", 1);

		$data['description'] = $std->br2nl($data['description']);
		$data['id'] = $id;
        $data['thumbs'] = 1;
		$data['sizenum'] = round($data['realsize']/1024);
		$this->output .= $this->html->add_file($data);
	}
	
	function upload()
	{
		global $CONFIG, $IN, $OUTPUT, $DB, $rwdInfo, $std;

		require_once ROOT_PATH."/functions/upload.php";

        $std->updateNav(" > ".GETLANG("nav_adddl"), 0);

		if ( $IN["confirm"] )
		{
			if (!$CONFIG['guest_uploads'])
			{
				$std->error(GETLANG("er_noperms"));
				return;
			}
			$time = date( 'Y-m-d H:i:s', time() );

			if ($CONFIG['approve_uploads'])
				$approved = 0;
			else
				$approved = 1;

			// Asign download to user
			$upload = new CUpload();
			$upload->moveFile($IN["maskName"], $IN["maskName"]);
			if ( $upload->masked_file == "file.rwd" )
				$download = $IN["download"];
			else
				$download =  $IN["download"];	// TODO: WTF???
			
			// Get rid of <br> tags from mirror list
			$mirrors = eregi_replace('<br[[:space:]]*/?[[:space:]]*>',"\n", $IN["mirrors"]);
			$mirrors = eregi_replace('&lt;br[[:space:]]*/?[[:space:]]*&gt;',"\n", $mirrors);
			// Get rid of <br> tags from mirror list
			$mirrornames = eregi_replace('<br[[:space:]]*/?[[:space:]]*>',"\n", $IN["mirrornames"]);
			$mirrornames = eregi_replace('&lt;br[[:space:]]*/?[[:space:]]*&gt;',"\n", $mirrornames);
			
			
			$realsize = $IN["realsize"] ? $IN["realsize"] : $IN["sizenum"]*1024;
			$filesize = $IN["filesize"] ? $IN["filesize"] : $std->my_filesize($realsize);
			
			$insert = array("name" => $IN["name"],
							"description" => $IN["description"],
							"author" => $IN["author"],
							"download" => $download,
							"mirrors" => $mirrors,
							"mirrornames" => $mirrornames,
							"version" => $IN["version"],
							"categoryid" => $IN["categoryid"],
							"realsize" => $realsize,
							"filesize" => $filesize,
							"fileType" => $IN["fileType"],
							"maskName" => $IN["maskName"],
							"date" => $time,
                            "approved" => $approved);
			$DB->insert($insert, "dl_links");
			$dlid = $DB->insert_id();

			for ( $i=0; $i<1; $i++)
			{
				$realName = "name".$i;
				$imgSize = "imageSize".$i;
				$type = "type".$i;

				$upload_limit = $CONFIG["uploadlimit"];

				$upload = new CUpload();

				$upload->moveImage($IN["$realName"], $IN["$realName"]);

				$insertimg = array( "realName" => $upload->masked_file,
									"dlid" => $dlid,
									"size" => $IN[$imgSize],
									"type" => $IN[$type]);
				$DB->insert($insertimg, "dl_images");
			}
			
			if ($approved)
			{
				$std->info(GETLANG("dladded").". ".GETLANG("dlindb").".<br>"."<a href='index.php?ACT=useradddl'>".GETLANG("continue")."</a>");
				$this->incrementCounter($IN["categoryid"], $dlid, $IN["name"], $guser->userid);
			}
			else
			{
				$std->info(GETLANG("dladded").". ".GETLANG("dlapp").".<br>"."<a href='index.php?ACT=useradddl'>".GETLANG("continue")."</a>");
			}
			return;
		}
		
		if ( $IN["preview"] )
		{
			if ( $IN["categoryid"] == 0 )
			{
				$std->error(GETLANG("er_basecat"));
				return;
			}

			if ( $IN["name"] == '' )
			{
				$error .= $IN["dlname"] ? "" : GETLANG("name")."<br>";
				$error .= $IN["dlcat"] ? "" : GETLANG("cat")."<br>";
				$std->warning(GETLANG("warn_missing").GETLANG("warn_fields").":<br>".$error);
				return;
			}

			// Upload thumbnails
			$thumbCount = 0;
			
			$fieldName = "thumb0";
			$upload = new CUpload();

			$upload->uploadImage($fieldName, $upload_limit);
			if ( $upload->file_name )
				$thumbCount++;
			$dlThumbs[0] = $upload;
			
			if ( $_FILES["download"]['name'] )
			{
				$newFile = new CUpload();

				$newFile->uploadFile("download", $upload_limit);
				if ($newFile->errorMsg)
				{
					$std->error($newFile->errorMsg);
					return;
				}
				$dlfile = $newFile->file_name;
			}
			// Otherwise a url was provided
			else
			{
				$dlfile = $IN["downloadurl"];
				$filesize = "unknown";
			}
			// Or was it?
			if ($dlfile == "http://")
				$dlfile = "";
			// Hope so...
			if ( $dlfile )
			{
				$newdata = $IN;
				if ( $thumbCount )
				{
					$newdata["name0"] = $dlThumbs[0]->file_name;
					$newdata["imageSize0"] = $dlThumbs[0]->image_size;
					$newdata["type0"] = $dlThumbs[0]->file_type;
					$newdata['onethumb'] = $dlThumbs[0]->file_name;
				}
				
				$newdata['description'] = $std->mynl2br($newdata['description']);
				$newdata["thumbCount"] = $thumbCount;
				$newdata["download"] = $dlfile;
				$newdata["realsize"] = $newFile->real_size;
				$newdata["filesize"] = $newFile->file_size;
				$newdata["fileType"] = $newFile->file_type;
				$newdata["maskName"] = $newFile->masked_file;
				$newdata["downloads"] = 0;
				$formdata = $this->parseFormData($newdata);
	
				$post = "index.php?ACT=useradddl";
				$this->files_dlPreviewMain($formdata, $IN["id"], $post);
				$OUTPUT->add_output($this->output);
				return;
			}
			else
			{
				// You great lemon!
				$std->error(GETLANG("er_nodl"));
			}
		}
		
		$post = "<form method=post enctype='multipart/form-data' action='index.php?ACT=useradddl'>";
		$this->files_dlMainForm("", "", 0, $post);
		$OUTPUT->add_output($this->output);
	}

    // Only to be used after new uploads
	function incrementCounter($cat, $dlid, $name, $authorid)
	{
		global $DB, $std;

		$std->resyncCats($cat);
		
		return true;
	}
	
	// ================================================================
	// These functions are shared with admin_files.php and usercp.php
	// ================================================================
	function files_dlPreviewMain($formdata=array(), $id, $post)
	{
		global $std, $sid, $DB;

		if ( $formdata["thumbCount"] )
			$std->info($formdata["thumbCount"]." ".GETLANG("thumbs_added"));

		$data = $this->parse_file_data($formdata, NULL, 1);
		$data["comments"] = "";
		$data = $std->my_stripslashes($data);
		
		$this->output .= $this->html->file_view($data);
		$this->output .=  "<form method=post enctype='multipart/form-data' action='$post'>";
		$this->output .=  "<input type='hidden' name='id' value='$id'>";
		$this->output .=  "<div align='center' style='text-align:center'>";

		foreach ($formdata as $l=>$v)
		{
			$this->output .= "<input type='hidden' name='$l' value='$v'>\n";
		}

		$this->output .= "<input type='submit' name='confirm' value='".GETLANG("confirm")."'>";

		$this->output .= "</div></form>";
	}

	// Replace empty fields with text where necessary
	function parseFormData($data)
	{
		global $DB;

		if ( $data["author"] == '' )
			$author = GETLANG("unknown");
		else
			$author = $data["author"];
		
		$formdata = $data;
		unset($formdata["preview"]);
		$formdata["author"] = $author;
		return $formdata;
	}

	/*************************************************************************
	*    Remove a file from a download but not delete from database
	*************************************************************************/
	function removeFile($path, $table, $sqlid, $id, $type)
	{
		global $sid, $std, $DB;

		$result2 = $DB->query("SELECT * FROM $table WHERE $sqlid");
		$myrow2 = $DB->fetch_row($result2);
		
		if ( $std->isExternalFile($myrow2[$type]) )
		{
			if ( $table == "dl_links" )
			{
				$update = array($type => '',
								"download" => '',
								"filesize" => '',
								"realsize" => '',
								"fileType" => '');
				$DB->update($update, $table, $sqlid);
			}
			else
			{
				$update = array($type => '');
				$DB->update($update, $table, $sqlid);
			}
			return true;
		}
		else
		{
			$file = $path."/downloads/".$myrow2[$type];

			if ( $table == "dl_links" )
				$update = array($type => '',
								"download" => '',
								"filesize" => '',
								"realsize" => '',
								"fileType" => '');
			else
				$update = array($type => '');
			$DB->update($update, $table, $sqlid);
			if(is_file($file))
			{
				unlink($file);
				return true;
			}
			else
				return false;
		}
	}
		
		
	// ===========================================================
	//  Delete Link
	//	Removes file from database ensuring all related files
	//	Are removed with it
	// ===========================================================	
	function deleteLink($id)
	{
		global $DB, $rwdInfo, $std;
		// Remove links from category
		$result2 = $DB->query("SELECT * FROM dl_links WHERE did=$id");
		$myrow2 = $DB->fetch_row($result2);
			
		require_once ROOT_PATH."/functions/gallery.php";
		$gallery = new gallery();
		$gallery->remove_thumbs($myrow2["did"]);
		
		$file2 = $rwdInfo->path."/downloads/".$myrow2["maskName"];
		$name = $myrow2["name"];
		$dlid = $myrow2["did"];
		$dlcat = $myrow2["categoryid"];
		$DB->query("DELETE FROM dl_links WHERE did=$id");
		$DB->query("DELETE FROM dl_comments WHERE did=$id");
		$std->resyncCats($dlcat);
			
		if(is_file($file2))
		{
			unlink($file2);
			
		}
		return true;

	}

	function order_box($catID, $sortvalue, $order)
	{
		// Change default sort order of links

	    $order_box .= "<form method='post' enctype='multipart/form-data' action='index.php?cid=$catID'>";
        $order_box .= "<table border='0' cellspacing='2' cellpadding='2'><tr><td align='right'>";
	    if ($sortvalue == "date")
		    $selectdate = "selected";
	    else if ($sortvalue == "author")
		    $selectauthor = "selected";
	    else if ($sortvalue == "name")
		    $selectname = "selected";
	    else if ($sortvalue == "downloads")
		    $selectdownloads = "selected";
		$order_box .= GETLANG("sortdls").":</td><td>";
	    $order_box .= "<select name=sortvalue>
		    <option value='date' $selectdate>".GETLANG("dateSub")."</option>
		    <option value='author' $selectauthor>".GETLANG("author")."</option>
		    <option value='name' $selectname>".GETLANG("name")."</option>
		    <option value='downloads' $selectdownloads>".GETLANG("nodl")."</option>
	      </select>
	    </td><td align=right>";
	    if ( $order == "ASC" )
		    $selectasc = "selected";
	    else
			$selectdesc = "selected";
	    $order_box .= GETLANG("order").": </td><td>";
	    $order_box .= "<select name=order>
		    <option value='ASC' $selectasc>".GETLANG("asc")."</option>
		    <option value='DESC' $selectdesc>".GETLANG("desc")."</option>
	      </select></td><td align=right>";
	    $order_box .= "<input name=sort type=submit value=".GETLANG("submit")."></td></tr></table></form>";

	    return $order_box;
	}

	function parse_file_data($myrow, $custom=array(), $isPreview=0, $isACP=0, $domod=0)
	{
		global $CONFIG, $OUTPUT, $rwdInfo, $std, $sid;

		require_once ROOT_PATH."/functions/gallery.php";
		$gallery = new gallery();
		$dlid = $myrow["did"];
		
		$onethumb = $gallery->getFirstThumb($dlid, $myrow["owner"]);
		//
		// Description Block
		{
			if ($myrow["description"])
			{
				$data = array(  "description" => $std->mynl2br($myrow["description"]));
			    $descblock = $this->html->desc_block($data);
			}
		}
				
		$ext = $std->GetFileExtention($myrow["maskName"]);
		if ( !$isPreview && ($ext == '.jpg' || $ext == '.jpeg' || $ext == '.gif' || $ext == '.bmp' ) )
		{
            if (file_exists($rwdInfo->path."/downloads/".$myrow["maskName"]))
            {
    			$size = getimagesize ($rwdInfo->path."/downloads/".$myrow["maskName"]);
    			$dl_size = GETLANG("imgdim").": ".$size[0]."x".$size[1];
            }
		}

		$urls = explode("\n",$myrow["mirrors"]);
		$names = explode("\n",$myrow["mirrornames"]);
		for ($i=0; $i<count($urls); $i++)
		{
			if ( !trim($urls[$i]) )
				continue;
			if ( !$names[$i] )
			{
				$url = parse_url($urls[$i]);
				$names[$i] = $url['host'];
			}
			$mirror['mirror'] = "<a href='{$urls[$i]}'>{$names[$i]}</a>";
			$mirrorlist .= $this->html->mirrorrow($mirror);
		}

		if ( $std->isRecent($myrow["date"]) )
			$new = "NEW";
		else
			$new = "";
		if ( $std->isRecent($myrow["lastEdited"]) )
			$updated = "UPDATED";
		else
			$updated = "";

		if ( !$isPreview )
		{
			$date = $std->convertDate($myrow["date"]);
			$updateddate = $std->convertDate($myrow["lastEdited"]);
		}
		if ( !$isPreview )
		{
		    if ( $myrow["download"] )
			    $download_link = $rwdInfo->url."/index.php?ACT=dl&id=$dlid";

		    if ( $isACP )
			{
				$del_link = "<a href='admin.php?sid=$sid&area=files&act=deletedl&id=$dlid'>".GETLANG("delete")."</a>";
				$edit_link = "<a href='admin.php?sid=$sid&area=files&act=editdl&id=$dlid'>".GETLANG("edit")."</a>";
		    }
		}

		if ( $isACP )
			$name = "<a href='admin.php?sid=$sid&area=files&act=editdl&id=$dlid'>".$myrow["name"]."</a>";
		else
			$name = "<a href='index.php?dlid=$dlid'>".$myrow["name"]."</a>";
			
		if ($myrow["icon"])
			$file_icon = "<img src='".$rwdInfo->url."/skins/skin".$CONFIG["defaultSkin"]."/mime_types/".$myrow["icon"]."' align='center' border='0'>";

		if ( $domod )
			$editvalue = $dlid;
		else
		 	$editvalue = "";
			
		if ( $std->isExternalFile($myrow["download"]) )
			$target = "_blank";
		else
			$target = "_self";
			
		$data = array(	"name" => "$name",
						"new" => "$new",
						"updated" => "$updated",
                        "onethumb" => "$onethumb",
						"date" => "$date",
						"updated_date" => "$updateddate",
						"views" => $myrow["views"]+1,
						"version" => $myrow["version"],
						"filesize" => $myrow["filesize"],
						"image_size" => "$dl_size",
						"author" => $myrow["author"],
						"downloads" => $myrow["downloads"],
						"download_url" => "$download_link",
						"mirrors" => "$mirrorlist",
						"description_block" => $descblock,
						"description" => $std->mynl2br($myrow["description"]),
						"short_desc" => $std->shorten_string($myrow['description']),
						"file_icon" => $file_icon,
						"edit" => $edit_link,
						"editvalue" => $editvalue,
						"delete" => $del_link,
						"target" => $target);
				
		return $data;
	}
}
?>