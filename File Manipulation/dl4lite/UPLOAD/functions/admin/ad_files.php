<?php

require_once ROOT_PATH."/functions/files.php";

class admin_files extends files
{
	var $html	= "";
	var $output = "";
	
	function admin_files()
	{
		global $IN, $OUTPUT;
		
		$this->html = $OUTPUT->load_template("skin_files");

		switch($IN["act"])
		{
			// Call the browse listing for the ACP
			case 'edit':
				$this->files_edit();
				break;
				
			case 'editcat':
				$this->files_editcat();
				break;
				
			case 'deletecat':
				$this->files_deletecat();
				break;
				
			case 'addcat':
				$this->files_addcat();
				break;

            case 'ordercat':
                $this->files_ordercat();
                break;

			case 'editdl':
				$this->files_editdl();
				break;

			case 'deletedl':
				$this->files_deletedl();
				break;
				
			case 'adddl':
				$this->files_adddl();
				break;
			
			case 'deleteimg':
				$this->files_deleteimg();
				break;
			
			case 'custom':
				$this->customFields();
				break;

			case 'massupload':
				$this->massUpload();
				break;

			case 'domassupload':
				$this->doMassUpload();
				break;

			case 'filetype':
				$this->fileTypeControl();
				break;

			case 'lookup':
				$this->typeLookup();
				break;

			case 'addmime':
				$this->addMimeType();
				break;

			case 'deltype':
				$this->files_deleteft();
				break;

			case 'unapproved':
				$this->displayUnapproved();
				break;
				
			case 'qapprove':
				$this->qApprove();
				break;
				
			case 'approveSelect':
				$this->checkedApprove();
				break;
				
			case 'batch':
				$this->files_batch();
				break;

		}
		$OUTPUT->add_output($this->output);
	}

	function files_edit()
	{
		global $IN, $std;
		$this->output .= admin_head(GETLANG("nav_files"), GETLANG("nav_editfiles"));
		// ===========================================================
		//  Display downloads in category and sub category listings
		// ===========================================================	
		if ($IN["cid"])
		{
			$std->updateNav("", $IN["cid"],1);
			$this->adDisplayLinks($IN["cid"]);
		}
		// ===========================================================
		//  Display main category listing
		// ===========================================================	
		else
		{
			$std->updateNav("", 0,1);
			$this->adDisplayCats();
		}
		$this->output .= admin_foot();
	}
	
	// ===========================================================
	//  CATEGORY FUNCTIONS
	// ===========================================================
	//  Add Category
	//	Does exactly what it says on the tin
	// ===========================================================
	function files_addcat()
	{
		global $DB, $IN, $std, $sid;
		require_once ROOT_PATH."/functions/upload.php";
        
		$this->output .= admin_head(GETLANG("nav_files"), GETLANG("nav_addcat"));

		if ( !empty($IN["submit"]) )
		{
			if ( $IN["catname"] == '' )
				$std->warning(GETLANG("warn_missing"));
			else
			{
				// If new thumbnail required then upload
				if ($_FILES["catthumb"])
				{
					$upload = new CUpload();
					$upload->uploadImage("catthumb", 0);
					if ( $upload->file_name != "file.rwd" )
						$upload->moveFile($upload->file_name, $upload->file_name);
				}
				
				$insert = array("name" => $IN["catname"],
								"description" => $IN["description"],
								"thumb" => $upload->masked_file );
				$DB->insert($insert, "dl_categories");
				
				$this->output .= GETLANG("catadd")."<br><br>";
				
				$this->output .= "+ <a href='admin.php?sid=$sid&area=files&act=addcat'>".GETLANG("backto")." ".GETLANG("nav_addcat")."</a><br>";
				$this->output .= "+ <a href='admin.php?sid=$sid&area=main'>".GETLANG("backto")." ".GETLANG("nav_adhome")."</a><br>";
				$this->output .= admin_foot();
				return;
			}
		}
		else
		{
			$function = "addcat";
			$this->files_catForm("", "", "", $function);
		}
		$this->output .= admin_foot();
	} 
	
	// ===========================================================
	//  get Latest File Info
	//	Recurses the directory tree to count the downloads 
	//	and get the latest file info
	// ===========================================================
	function getLatestFileInfo($categoryData)
    {
		global $rwdInfo,$std;

		foreach ($rwdInfo->cat_cache as $subcat)
		{
			if ($subcat['parentid'] == $categoryData['cid'])
	    	{
				$categoryData['downloads'] += $tempData['downloads'];
				
				if ( $tempData['lastDate'] > $categoryData['lastDate'] ) 
				{
				    $categoryData['lastDate'] = $tempData['lastDate'];
					$categoryData['lastid'] = $tempData['lastid'];
					$categoryData['lastTitle'] = $tempData['lastTitle'];
				}
	    	}
		}
		return $categoryData;
    }
	// ===========================================================
	//  Display Categories
	//	Displays the list of categories in ACP format
	// ===========================================================
	function adDisplayCats()
	{
		global $rwdInfo, $sid, $DB;
		
		if ( !$rwdInfo->cats_saved )
		{
			$DB->query("SELECT * FROM dl_categories ORDER BY sortorder ASC");
			if ($myrow = $DB->fetch_row($result))
			{
				do
				{
					// Add category to cache
					$rwdInfo->cat_cache[$myrow["cid"]] = $myrow;
				} while ($myrow = $DB->fetch_row($result));
			}
			$rwdInfo->cats_saved = 1;
		}
		
		$result = $DB->query("SELECT * FROM dl_categories ORDER BY sortorder");

		if ($myrow = $DB->fetch_row($result))
		{
			// Add category to cache
			$rwdInfo->cat_cache[$myrow["cid"]] = $myrow;
			
			$this->output .= $this->html->ad_cat_head();
			do
			{
				$catData = $this->getLatestFileInfo($myrow);
				$rows = $catData["downloads"]; 
				$id = $myrow["cid"];
				$cat_name = "<a href='admin.php?sid=$sid&area=files&act=edit&cid=$id'>{$myrow['name']}</a>";
				if ( $rows > 0 )
				{
					$cat_latest = "<a href='admin.php?sid=$sid&area=files&act=editdl&id={$catData['lastid']}'>{$catData['lastTitle']}</a>";
				}
				else
				{
					$cat_latest = GETLANG("nodls");
				}

				$cat_desc = $myrow["description"];

				$delete = "<a href='admin.php?sid=$sid&area=files&act=deletecat&id=$id'>".GETLANG("delete")."</a>";
				$edit = "<a href='admin.php?sid=$sid&area=files&act=editcat&id=$id'>".GETLANG("edit")."</a>";

				$data = array(
					"cat_name" => "$cat_name",
					"cat_latest" => "$cat_latest",
					"cat_desc" => "$cat_desc",
					"cat_dlcount" => "$rows",
					"cat_edit" => "$edit",
					"cat_delete" => "$delete");

				$this->output .= $this->html->ad_cat_row($data);

			} while ( $myrow = $DB->fetch_row($result) );
			$this->output .= $this->html->ad_cat_foot();
		}
	}

	// ===========================================================
	//  Delete Category
	//	Displays the confirm dialog and handles the response
	// ===========================================================
	function files_deletecat()
	{
		global $IN, $DB, $sid, $std;
		$this->output .= admin_head(GETLANG("nav_files"), GETLANG("nav_editfiles"));
		if ($IN["confirm"])
		{
			if ( $this->categoryDelete($IN["id"]) )
			{
				// resync the remaining categories
				$cats = $DB->query("SELECT * FROM dl_categories");
			    if ($myrow = $DB->fetch_row($cats)) 
				{
					do
					{
						$std->resyncCats($myrow['cid']);
					} while($myrow = $DB->fetch_row($cats));
			    }
				$this->output .= GETLANG("catdel")."<br><br>";
			
				$this->output .= "+ <a href='admin.php?sid=$sid&area=files&act=edit'>".GETLANG("backto")." ".GETLANG("nav_editfiles")."</a><br>";
				$this->output .= "+ <a href='admin.php?sid=$sid&area=main'>".GETLANG("backto")." ".GETLANG("nav_adhome")."</a><br>";
			}
			else
			{
				$std->error(GETLANG("er_catdel"));
				$this->output .= "+ <a href='admin.php?sid=$sid&area=files&act=edit'>".GETLANG("backto")." ".GETLANG("nav_editfiles")."</a><br>";
				$this->output .= "+ <a href='admin.php?sid=$sid&area=main'>".GETLANG("backto")." ".GETLANG("nav_adhome")."</a><br>";
			}
			
		}
		else if ($IN["cancel"])
		{
			$this->output .= GETLANG("delcancel")."<br><br>";
			
			$this->output .= "+ <a href='admin.php?sid=$sid&area=files&act=edit'>".GETLANG("backto")." ".GETLANG("nav_editfiles")."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=files&act=editcat&id=".$IN["id"]."'>".GETLANG("backto")." ".GETLANG("nav_editcat")."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=main'>".GETLANG("backto")." ".GETLANG("nav_adhome")."</a><br>";
		}
		else
		{
			$std->warning(GETLANG("warn_catdel")."<p>"
					."<form method='post' action='admin.php?sid=$sid&area=files&act=deletecat'>"
					."<input type='hidden' name='id' value='".$IN[id]."'>"
					."<input type='Submit' name='confirm' value='".GETLANG("yes")."'> <input type='Submit' name='cancel' value='".GETLANG("no")."'> </form>");
		
		}
		$this->output .= admin_foot();
		return;
	}
	
	// ===========================================================
	//  Category Delete
	//	Main delete function. 
	// ===========================================================
	function categoryDelete($id)
	{
		global $rwdInfo, $DB;
		
		$return = false;
		// Remove category
		$return = $this->removeCategory($id);
				
		return $return;
	}

	// ===========================================================
	//  Remove Category
	//	Be gone evil one!
	// ===========================================================
	function removeCategory($id)
	{
		global $rwdInfo, $sid, $DB;
		if ( $this->categoryClear($id) )
		{
			// Remove category
			// Set category data if not already saved... which it probably should be
			if ( !$rwdInfo->cat_cache[$id] )
			{
				$catres = $DB->query("SELECT * FROM dl_categories WHERE cid=$id");
				$catrow = $DB->fetch_row();
				$rwdInfo->cat_cache[$id] = $catrow;
			}

			$name = $rwdInfo->cat_cache[$id]["name"];

			$file = $rwdInfo->path."/downloads/".$rwdInfo->cat_cache[$id]["thumb"];
			if(is_file($file))
				unlink($file);
			$result = $DB->query("DELETE FROM dl_categories WHERE cid=$id");
			return true;
		}
		else
			return false;
	}
	
	// ===========================================================
	//  Clear Category
	//	Remove all files from category
	// ===========================================================
	function categoryClear($id)
	{
		global $DB, $rwdInfo;
		// Remove links from category
		$result2 = $DB->query("SELECT * FROM dl_links WHERE categoryid=$id");
		if ($myrow2 = $DB->fetch_row($result2))
		{
			do
			{
				include_once ROOT_PATH."/functions/gallery.php";
				$gallery = new gallery();
				$gallery->remove_thumbs($myrow2["did"]);
				$file2 = $rwdInfo->path."/downloads/".$myrow2["maskName"];
				$name = $myrow2["name"];
				$dlid = $myrow2["did"];
				$dlcat = $myrow2["categoryid"];
				$DB->query("DELETE FROM dl_links WHERE did=$id");
				$DB->query("DELETE FROM dl_comments WHERE did=$id");
				if(is_file($file2))
					unlink($file2);
			
			} while ($myrow2 = $DB->fetch_row($result2));
		}
		return true;
	}

	// ===========================================================
	//  Edit Category
	//	I dont like your face
	// ===========================================================	
	function files_editcat()
	{
		global $IN, $DB, $rwdInfo, $sid;
		
		require_once ROOT_PATH."/functions/upload.php";

		$this->output .= admin_head(GETLANG("nav_files"), GETLANG("nav_editfiles"));

		// Remove thumbnail
		if ( $IN["removethumb"] )
		{
			$table = "dl_categories";
			$sqlid = "cid=".$IN["id"];
			$type = "thumb";
			if ( $this->removeFile($rwdInfo->path, $table, $sqlid, $IN["id"], $type) )
				$this->output .= GETLANG("inf_filedl")."<br><br>";
			else
				$this->output .= GETLANG("er_unlink")."<br><br>";
			
			$this->output .= "+ <a href='admin.php?sid=$sid&area=files&act=edit'>".GETLANG("backto")." ".GETLANG("nav_editfiles")."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=files&act=editcat&id=".$IN["id"]."'>".GETLANG("backto")." ".GETLANG("nav_editcat")."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=main'>".GETLANG("backto")." ".GETLANG("nav_adhome")."</a><br>";
			$this->output .= admin_foot();
			return;
		}
		// Data posted
		if ( !empty($IN["submit"]) )
		{
			// Check for required fields
			if ( $IN["catname"] == '' )
			{
				$std->error(GETLANG("warn_missing"));
				return;
			}

			if ( !$this->validateCat($IN["id"], $IN["parent"]) )
			{
				$std->error(GETLANG("er_movecat") );
				return;
			}
			// If new thumbnail required then upload
			if ($_FILES["catthumb"])
			{
				$upload = new CUpload();
				$upload->uploadImage("catthumb", 0);
				$upload->moveFile($upload->file_name, $upload->file_name);
			}
			
			// If new thumb uploaded then change database to reflect change
			if ( $upload->masked_file && $upload->masked_file != "file.rwd" )
				$result = $DB->query("UPDATE dl_categories SET thumb='$upload->masked_file' WHERE cid=$IN[id]");

			$result = $DB->query("UPDATE dl_categories SET name='$IN[catname]', description='$IN[description]' WHERE cid=$IN[id]");
			$this->output .= GETLANG("catedit")."<br><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=files&act=edit'>".GETLANG("backto")." ".GETLANG("nav_editfiles")."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=files&act=editcat&id=".$IN["id"]."'>".GETLANG("backto")." ".GETLANG("nav_editcat")." ".$IN[catname]."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=files&act=edit&cid=".$IN["id"]."'>".GETLANG("browse")." ".$IN["catname"]."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=main'>".GETLANG("backto")." ".GETLANG("nav_adhome")."</a><br>";
            $this->output .= admin_foot();
            return;
		}
	
		// If we're here then we must be about to view the form
		$table = "dl_categories";
		$id = $IN['id'];
		$sqlid = "cid=".$id;
		$function = "editcat";
		$this->files_catForm($id, $table, $sqlid, $function);

		$this->output .= admin_foot();
	}

	// ===========================================================
	//  Category Form
	//	The user interface to edit this category. Also used
	//	when adding a NEW categroy. Clever eh?
	// ===========================================================	
	function files_catForm($id, $table, $sqlid, $function)
	{
		global $sid, $std, $DB;
	
		// Check if data for edit is being sent
		if ($table)
		{
			$result2 = $DB->query("SELECT * FROM $table WHERE $sqlid");
			$myrow2 = $DB->fetch_row($result2);
		}	
		
		$this->output .= "<form method='post' enctype='multipart/form-data' action='admin.php?sid=$sid&area=files&act=$function' name='editcat'>";
		$this->output .= new_table();
		$this->output .= new_row(2, "acptablesubhead");
			$this->output .= GETLANG("nav_editcat");
		$this->output .= new_row();
			$this->output .= GETLANG("cap_catname").":";
			$this->output .= new_col();
			$this->output .= "<input name='catname' type='text' size='30' value='".$myrow2["name"]."'>";
    	$this->output .= new_row();
			if ($myrow2["thumb"])
			{
				$this->output .= GETLANG("cap_catimg").":";
				$this->output .= new_col();
				$this->output .= "<img src='downloads/".$myrow2["thumb"]."'> [ <a href='admin.php?sid=$sid&area=files&act=editcat&removethumb=1&id=$id'>".GETLANG("delete")."</a> ]";
			}
			else
			{
				$this->output .= GETLANG("cap_catimg2").":";
				$this->output .= new_col();
				$this->output .= "<input name='catthumb' type='file' size='30'>";
			}
		
		$this->output .= new_row();
			$this->output .= GETLANG("cap_catdesc").":";
			$this->output .= new_col();
			$this->output .= "<textarea name='description' cols='24' rows='5'>".$myrow2["description"]."</textarea>";
		
		$this->output .= end_table();
		$this->output .= "<input type='hidden' name='id' value='$id'>";
		$this->output .= "<input type='submit' name='submit' value='".GETLANG("submit")."'>";
		$this->output .= "<input type='reset' name='reset' value='".GETLANG("reset")."'>";
    	$this->output .= "</form>";
	}

	function validateCat($id, $parent)
	{
		global $DB;

		if ( $id == $parent )
			return false;

		return true;
	}

    function files_ordercat()
    {
        global $IN, $DB, $rwdInfo, $sid;

        $this->output .= admin_head(GETLANG("nav_files"), GETLANG("nav_ordercats"));

        if ( !empty($IN["update"]) )
        {
            $orders = $IN["c"];
            foreach ($orders as $i=>$v)
            {
                $update = array("sortorder" => $v);
                $DB->update($update, "dl_categories", "cid=$i");

            }
            $this->output .= "Done";
            $this->output .= admin_foot();
            return;
        }
        if ( !$rwdInfo->cats_saved )
		{
			$DB->query("SELECT * FROM dl_categories");
			if ($myrow = $DB->fetch_row($result))
			{
				do
				{
					// Add category to cache
					$rwdInfo->cat_cache[$myrow["cid"]] = $myrow;
				} while ($myrow = $DB->fetch_row($result));
			}
			$rwdInfo->cats_saved = 1;
		}

        ( $IN[cid] ) ? $cid = $IN[cid] : $cid = 0;
		$result = $DB->query("SELECT * FROM dl_categories ORDER BY sortorder");
        $num_cats = $DB->num_rows();

		if ($myrow = $DB->fetch_row($result))
		{
			// Add category to cache
			$rwdInfo->cat_cache[$myrow["cid"]] = $myrow;
            $this->output .= "<form method='post' enctype='multipart/form-data' action='admin.php?sid=$sid&area=files&act=ordercat&cid=$cid'>";
			
			$this->output .= $this->html->ad_cat_order_head();
			do
			{
				$rows = $myrow["downloads"];
				$id = $myrow["cid"];
                $subcatlist = "";
                $orderbox = "<select id='realbutton' name='c[{$myrow[cid]}]'>\n";
                for ( $i=1; $i <= $num_cats; $i++ )
                {
                    $orderbox .= "<option value='$i'";
                    if ( $myrow["sortorder"] == $i )
                        $orderbox .= " selected";
                    $orderbox .= ">$i</option>\n";
                }
                $orderbox .= "</select>\n";
                $cat_name = $myrow['name'];
				
				$cat_desc = $myrow["description"];

				$data = array(
					"cat_name" => "$cat_name",
					"cat_desc" => "$cat_desc",
					"cat_order" => "$orderbox");

				$this->output .= $this->html->ad_cat_order_row($data);

			} while ( $myrow = $DB->fetch_row($result) );
			$this->output .= $this->html->ad_cat_order_foot();
            $this->output .= "<input type='submit' name='update' value='".GETLANG("update")."'>";
		}
        $this->output .= admin_foot();
    }
	
	// ===========================================================
	//  FILES FUNCTIONS
	// ===========================================================
	//  Display Files
	//	Lists all files in this category with ACP edit delete 
	//	links etc
	// ===========================================================
	function adDisplayLinks($catID = 0)
	{
		global $DB, $IN, $CONFIG, $rwdInfo, $std, $sid;
		
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
					ORDER BY $sortvalue $order LIMIT $limit , {$CONFIG[links_per_page]}");
				    
		$order_box = $this->order_box($catID, $sortvalue, $order);
		$pages = $std->pages($rwdInfo->cat_cache[$catID]["downloads"], $CONFIG["links_per_page"], "admin.php?sid=$sid&area=files&act=edit&cid=$catID&sortvalue=$sortvalue&order=$order");
		
		$result = $DB->query("SELECT * FROM dl_links WHERE categoryid=$catID AND approved=1 ORDER BY $sortvalue $order LIMIT $limit , $CONFIG[links_per_page]");

		if ($myrow = $DB->fetch_row($result)) 
		{		
			$batchpost = "admin.php?sid=$sid&area=files&act=batch";
			$data = array(	"batchpost" => $batchpost,
							"cat_name" => $rwdInfo->cat_cache[$catID]["name"],
							"order_boxes" => $order_box,
							"pages" => "$pages");
			$this->output .= $this->html->ad_cat_listing_head($data);

			do
			{
				$data = $this->parse_file_data($myrow,NULL,0,1);
				$data["id"] = $myrow["did"];
				$this->output .= $this->html->ad_cat_listing_row($data);
				
			} while ( $myrow = $DB->fetch_row($result) );
			
			$data = array(	"order_boxes" => $order_box,
							"pages" => $pages,
							"cid" => $catID );
			$this->output .= $this->html->ad_cat_listing_foot($data);
		}
		else
			$this->output .= GETLANG("nodls");
	}
	
	// ===========================================================
	//  Mass Move/Delete
	//	Performs a batch operation on the selected files
	// ===========================================================
	function files_batch()
	{
		global $IN, $DB, $sid;
		
		$this->output .= admin_head(GETLANG("nav_files"), GETLANG("nav_editfiles"));
		
		$this->files_dobatch("admin.php?sid=$sid&area=files&act=batch", $IN['cid']);
		
		$this->output .= admin_foot();
	}
	
	// ===========================================================
	//  Edit Download
	//	Calls functions from parent class functions/files.php
	// ===========================================================
	function files_editdl()
	{
		global $IN, $DB, $rwdInfo, $std, $sid;

		$id = $IN['id'];

		$std->updateNavDl("", $id, 1);
		
		$DB->query("SELECT l.*, img.* 
					FROM dl_links l
					LEFT JOIN dl_images img  ON (img.dlid=l.did)
					WHERE l.did={$id}");
		$myrow = $DB->fetch_row();

		$approvePage=$IN["approvePage"];

		if ($approvePage)
			$this->output .= admin_head(GETLANG("nav_files"), GETLANG("nav_approvefiles"));
		else
			$this->output .= admin_head(GETLANG("nav_files"), GETLANG("nav_editdl"));
		
	    if ( $IN["removefile"] )
	    {
			$table = "dl_links";
			$sqlid = "did=".$IN["id"];
			$type = "maskName";

			$DB->query("SELECT * FROM dl_links WHERE did={$IN['id']}");
			$myrow = $DB->fetch_row();
			
			if ( $this->removeFile($rwdInfo->path, $table, $sqlid, $IN["id"], $type) )
			{
				$this->output .= GETLANG("filedl")."<br><br>";
			}
			else
			{
				$this->output .= GETLANG("er_unlink")."<br><br>";
			}
			if ( !$approvePage )
				$this->output .= "+ <a href='admin.php?sid=$sid&area=files&act=edit'>".GETLANG("backto")." ".GETLANG("nav_editfiles")."</a><br>";
			else
				$this->output .= "+ <a href='admin.php?sid=$sid&area=unapproved&act=edit'>".GETLANG("backto")." ".GETLANG("nav_approvefiles")."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=files&act=editdl&id=".$IN["id"]."'>".GETLANG("backto")." ".GETLANG("nav_editdl")." {$myrow['name']}</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=main'>".GETLANG("backto")." ".GETLANG("nav_adhome")."</a><br>";
			$this->output .= admin_foot();
			return;
	    }

	    if ( $IN["confirm"] )
	    {
			if ( !$this->saveEdit() )
				$std->error(GETLANG("er_editdl"));

			if ( !$approvePage )
				$this->output .= "+ <a href='admin.php?sid=$sid&area=files&act=edit'>".GETLANG("backto")." ".GETLANG("nav_editfiles")."</a><br>";
			else
				$this->output .= "+ <a href='admin.php?sid=$sid&area=unapproved&act=edit'>".GETLANG("backto")." ".GETLANG("nav_approvefiles")."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=files&act=editdl&id=".$IN["id"]."'>".GETLANG("backto")." ".GETLANG("nav_editdl")." ".$IN["name"]."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=main'>".GETLANG("backto")." ".GETLANG("nav_adhome")."</a><br>";
			$this->output .= admin_foot();
			return;
	    }

	    if ( $IN["preview"] && !$IN["updateform"] )
	    {
			$post = "admin.php?sid=$sid&area=files&act=editdl";
			$this->previewEdit($post);
			$this->output .= admin_foot();
            return;
	    }
	
		// Else display the edit form

	    $post = "<form method='post' enctype='multipart/form-data' action='admin.php?sid=$sid&area=files&act=editdl&id=$id'>";
		$this->files_dlMainForm($id, $myrow, 1, $post);
		$this->output .= admin_foot();
	}

	// ===========================================================
	//  Remove gallery thumbnail
	//	Calls remove function in gallery class
	// ===========================================================
	function files_deleteimg()
	{
		global $sid, $IN;
		include ROOT_PATH."/functions/gallery.php";
		$gallery = new gallery();
		$dlid=$IN["id"];
		$gallery->removeThumb("admin.php?sid=$sid&area=files&act=deleteimg&id=$dlid", "admin.php?sid=$sid&area=files&act=editdl&id=$dlid");
	}
	
	// ===========================================================
	//  Add File
	//	Handles the users input to add new file
	// ===========================================================
	function files_adddl()
	{
		global $DB, $IN, $CONFIG, $sid, $std;
		require_once ROOT_PATH."/functions/upload.php";

		$this->output .= admin_head(GETLANG("nav_files"), GETLANG("nav_adddl"));
		
		if ( $IN["preview"] )
		{
			if ( $IN["categoryid"] == 0 )
			{ 
				$std->error(GETLANG("er_basecat"));
			}
			elseif ( $IN["name"] == '' )
			{
				$error .= $IN["name"] ? "" : GETLANG("name")."<br>";
				$std->warning(GETLANG("warn_missing").GETLANG("warn_fields").":<br>".$error);
			}
			else
			{
				$upload_limit = 0;
					
				// Upload thumbnails
				$thumbCount = 0;
				for ( $i=0; $i<1; $i++)
				{
					$fieldName = "thumb".$i;
					$upload = new CUpload();
					
					$upload->uploadImage($fieldName, $upload_limit);
					if ( !$upload->file_name )
						continue;
					$thumbCount++;
					$dlThumbs[] = $upload;
				}
				// If a file was uploaded
				if ( $_FILES["download"]['name'] )
				{
					$newFile = new CUpload();
					
					$newFile->uploadFile("download", $upload_limit);
					if ( !$newFile->file_name )
						$std->error("Failed to upload");
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
					$newdata['description'] = stripslashes($newdata['description']);
					$newdata['description'] = nl2br($newdata['description']);
					// Save thumbnails as post data
					if ($thumbCount)
					{
						$newdata['name0'] = $dlThumbs[0]->file_name;
						$newdata['imageSize0'] = $dlThumbs[0]->image_size;
						$newdata['type0'] = $dlThumbs[0]->file_type;
					}
					$newdata["thumbCount"] = $thumbCount;
					$newdata["download"] = $dlfile;
					$newdata["realsize"] = $newFile->real_size;
					$newdata["filesize"] = $newFile->file_size;
					$newdata["fileType"] = $newFile->file_type;
					$newdata["maskName"] = $newFile->masked_file;
					$newdata["imagesize"] = $newFile->image_size;
					$newdata["downloads"] = 0;
					$newdata["userrating"] = "n/a";
					$formdata = $this->parseFormData($newdata);

					$post = "admin.php?sid=$sid&area=files&act=adddl";
					$this->files_dlPreviewMain($formdata, $id, $post);

				}
				else
				{
					// You great lemon!
					$std->error(GETLANG("er_nodl"));
				}
			}

		}
		elseif ( $IN["confirm"] )
		{
			$time = date( 'Y-m-d H:i:s', time() );
			$approved = 1;

			$newdata['description'] = stripslashes($newdata['description']);
//			$newdata['description'] = str_replace( "<br>"            , "&amp;"         , $val );
			$newdata['description'] = str_replace( "&lt;br /&gt;", "<br>", $newdata['description'] );
			// Asign download to user
			$owner = $guser->userid;
			$upload = new CUpload();
			$upload->moveFile($IN["maskName"], $IN["maskName"]);
			if ( $upload->masked_file == "file.rwd" )
				$download = $IN["download"];
			else
				$download =  $IN["download"];
				
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
					"owner" => $owner,
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

			$this->incrementCounter($IN["categoryid"], $dlid, $IN["name"], $guser->userid);
			// Add custom data
			// Add id number reference first
			$insert2 = array("uid" => $dlid );
			
		
			$realName = "name0";
			$imgSize = "imageSize0";
			$type = "type0";

			$upload = new CUpload();

			$upload->moveImage($IN["$realName"], $IN["$realName"]);

			$insertimg = array( "realName" => $upload->masked_file,
								"dlid" => $dlid,
								"size" => $IN[$imgSize],
								"type" => $IN[$type]);
			$DB->insert($insertimg, "dl_images");
			

			$this->output .= GETLANG("dladded").". ".GETLANG("dlindb")."<br><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=files&act=adddl'>".GETLANG("backto")." ".GETLANG("nav_adddl")."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=main'>".GETLANG("backto")." ".GETLANG("nav_adhome")."</a><br>";

		}
		else
		{
			$post = "<form method=post enctype='multipart/form-data' action='admin.php?sid=$sid&area=files&act=adddl'>";
			$this->files_dlMainForm("", "", 1, $post);
		}
		$this->output .= admin_foot();
	}
	
	// ===========================================================
	//  Mass Move/Delete
	//	Performs a batch operation on the selected files
	// ===========================================================
	function files_dobatch($post, $isAdmin=0)
	{
		global $IN, $DB, $OUTPUT, $sid, $std;
		
		$ids = count($IN["dlid"]);

		if ( $ids == 0 )
		{
			$std->error(GETLANG("er_noselect"));
			return;
		}
		if ( $IN["mode"] == "move" )
		{
			if ($IN["confirm"] && $IN["catid"])
			{
				$DB->query("SELECT categoryid FROM dl_links WHERE did=".$IN["dlid"]["0"]);
				$row = $DB->fetch_row();
				$oldcat = $row["categoryid"];
				$cid = $IN["catid"];
				if ( $oldcat == $cid )
				{
					$std->error(GETLANG("er_samecat"));
					return;
				}
				for ( $i=0; $i<$ids; $i++ )
				{
					$id = $IN["dlid"]["$i"];
					$DB->query("UPDATE dl_links SET categoryid='$cid' WHERE did=$id");
				}
				$std->resyncCats($cid);
            	$std->resyncCats($oldcat);
				$std->info (GETLANG("movedok")."<p>"."<a href='index.php?cid={$oldcat}'>".GETLANG("continue")."</a>");
			}
			else
			{
				$this->output .= sprintf( GETLANG("massmove"), $ids);
				$this->output .= "<form method='post' action='$post'>";
				for ( $j=0; $j<$ids; $j++ )
					$this->output .= "<input type='hidden' name='dlid[]' value='".$IN["dlid"][$j]."'>\n";
				$this->output .= "<input type='hidden' name='mode' value='move'>";
                if ( $isAdmin )
                    $this->output .= $std->catListBox(-1, "catid", "canMove", 1);
                else
                    $this->output .= $std->catListBox(-1, "catid", "canMove");
				$warntext .= "<input type='hidden' name='cid' value='{$IN['cid']}'>";
				$this->output .= "<input type='submit' name='confirm' value='".GETLANG("submit")."'></form>";
				$OUTPUT->add_output($this->output);
			}
		}
		elseif ( $IN["mode"] == "del" )
		{
			if ($IN["confirm"])
			{
				$oldcat = $IN["cid"];
				for ( $i=0; $i<$ids; $i++ )
				{
					$id = $IN["dlid"]["$i"];
					if ( !$this->deleteLink($id) )
						$std->error(GETLANG("er_stderrprefix"));
				}
				$std->resyncCats($oldcat);
				$std->info (GETLANG("deldl")."<p>"."<a href='index.php?cid={$oldcat}'>".GETLANG("continue")."</a>");
			}
			else if ($IN["cancel"])
			{
				$std->info (GETLANG("delcancel")."<p>"."<a href='index.php?cid={$oldcat}'>".GETLANG("continue")."</a>");
			}
			else
			{
				$warntext = "<p><form method='post' action='$post'>";
				for ( $j=0; $j<$ids; $j++ )
					$warntext .= "<input type='hidden' name='dlid[]' value='{$IN['dlid'][$j]}'>";
				$warntext .= "<input type='hidden' name='cid' value='{$IN['cid']}'>";
				$warntext .= "<input type='hidden' name='mode' value='del'>";
				$warntext .= "<input type='hidden' name='deleteChecked' value='1'>";
				$warntext .= "<input type='Submit' name='confirm' value='".GETLANG("yes")."'> <input type='Submit' name='cancel' value='".GETLANG("no")."'> </form>";
				$std->warning (GETLANG("warn_dldel").$warntext);

			}
		}
		else
		{
			$std->error(GETLANG("er_unknownop"));
		}
		//$OUTPUT->add_output($this->output);
	}
	
	// Called when edit dl is confirmed
	function saveEdit()
	{
		global $DB, $IN, $guser, $std;
	    
	    $realName = "name0";
	    $imgSize = "imageSize0";
	    $type = "type0";
		$dlid = $IN['id'];

	    $upload_limit = 0;

		require_once ROOT_PATH."/functions/upload.php";
	    $upload = new CUpload();

        if ( $IN[$realName] )
        {
    	    $upload->moveImage($IN["$realName"], $IN["$realName"]);
    	    $insert = array("realName" => $IN[$realName],
    					    "dlid" => $dlid,
    					    "size" => $IN[$imgSize],
    					    "type" => $IN[$type]);
    	    $DB->insert($insert, "dl_images");
        }

	    if ( $IN["download"] )
		{
			require_once ROOT_PATH."/functions/upload.php";

		    $upload = new CUpload();
		    $upload->moveFile($IN["maskName"], $IN["maskName"]);
		    $update = array("download" => $IN["download"],
						    "maskName" => $IN["maskName"],
						    "realsize" => $IN["realsize"],
						    "filesize" => $IN["filesize"],
						    "fileType" => $IN["fileType"]);
		    $DB->update($update, "dl_links", "did=$dlid");
	    }
	    else if ( $IN["downloadurl"] )
	    {
		    if ($IN["downloadurl"] == "http://")
			    $dlfile = "";
		    else
			    $dlfile = $IN["downloadurl"];
		    $update = array("download" => $dlfile,
						    "maskName" => $dlfile,
							"realsize" => $IN["realsize"],
						    "filesize" => $IN["filesize"]);
		    $DB->update($update, "dl_links", "did=$dlid");
	    }
		else if ( $IN['oldfile'] && $std->isExternalFile($IN['oldfile']) )
		{
			// update filesize for external files only
			$update = array("realsize" => $IN["realsize"],
						    "filesize" => $IN["filesize"]);
		    $DB->update($update, "dl_links", "did=$dlid");
		}
		

		$time = date( 'Y-m-d H:i:s', time() );

		$mirrors = eregi_replace('<br[[:space:]]*/?[[:space:]]*>',"\n", $IN["mirrors"]);
		$mirrors = eregi_replace('&lt;br[[:space:]]*/?[[:space:]]*&gt;',"\n", $mirrors);
		
	    $update = array("name" => $IN["name"],
					    "description" => $std->br2nl($IN["description"]),
					    "author" => $IN["author"],
					    "version" => $IN["version"],
					    "categoryid" => $IN["categoryid"],
					    "mirrors" => $mirrors,
						"mirrornames" => $IN["mirrornames"],
					    "realsize" => $IN["realsize"],
					    "filesize" => $IN["filesize"],
					    "lastEdited" => $time,
                        "pinned" => $IN["pinned"]);
	    $DB->update($update, "dl_links", "did=$dlid");

        // Make sure that categories are synced
        if ( $IN["oldcat"] != $IN["categoryid"] )
        {
            $std->resyncCats($IN["categoryid"]);
            $std->resyncCats($IN["oldcat"]);
        }

    	return true;
	}
	
	// This function is called to display the post preview
	function previewEdit($postlink)
	{
		global $IN, $OUTPUT, $std;

		if ( $IN["categoryid"] == 0 )
		{
			$std->error(GETLANG("er_basecat"));
			return false;
		}

		if ( $IN["name"] == '' )
		{
			$error .= $IN["dlname"] ? "" : GETLANG("name")."<br>";
			$error .= $IN["dlcat"] ? "" : GETLANG("cat")."<br>";
			$std->warning(GETLANG("warn_missing").GETLANG("warn_fields").":<br>".$error);
			return false;
		}

		// Upload thumbnails
		$thumbCount = 0;
		
		$fieldName = "thumb0";
		require_once ROOT_PATH."/functions/upload.php";
		$upload = new CUpload();

		$upload->uploadImage($fieldName, $upload_limit);
		if ( $upload->file_name )
			$thumbCount++;
		$dlThumbs[0] = $upload;
		
		$newdata = $IN;
		
		if ( $_FILES["download"]['name'] )
		{
			$newFile = new CUpload();
			$newFile->uploadFile("download", $upload_limit);
			if ( !$newFile->file_name )
				$std->error($newFile->errorMsg);
			$dlfile = $newFile->file_name;

			$newdata["download"] = $dlfile;
			$newdata["realsize"] = $newFile->real_size;
			$newdata["filesize"] = $newFile->file_size;
			$newdata["fileType"] = $newFile->file_type;
			$newdata["maskName"] = $newFile->masked_file;
		}
		if ($thumbCount)
		{
			$newdata["name0"] = $dlThumbs[0]->file_name;
			$newdata["imageSize0"] = $dlThumbs[0]->image_size;
			$newdata["type0"] = $dlThumbs[0]->file_type;
		}
		$newdata["thumbCount"] = $thumbCount;
		$newdata["downloads"] = 0;
		$newdata["userrating"] = "n/a";

		if ( ($IN['oldfile'] && $std->isExternalFile($IN['oldfile'])) || ( $IN['downloadurl'] != "" && $IN['downloadurl'] != "http://" ) )
		{
			$newdata["realsize"] = $IN['sizenum']*1024;
			$newdata["filesize"] = $std->my_filesize($IN['sizenum']*1024);
		}
		$formdata = $this->parseFormData($newdata);

		$this->files_dlPreviewMain($formdata, $IN["id"], $postlink);

		return true;
	}
	
	function modoptions($cid)
	{
		global $guser, $std;
		
		$options = 0;
		$output .= "<select name='mode'>";
		$output .= "<option value='del'>".GETLANG("delselect")."</option>";
		$options++;
		$output .= "<option value='move'>".GETLANG("moveselect")."</option>";
		$options++;
		$output .= "</select>";
		$output .= "<input type='submit' value='".GETLANG("inf_go")."' name='domod'>";
		$output .= "<input type='hidden' value='$cid' name='cid'>";
		if ( $options )
			return $output;
		else 
			return "";
	}

	// ===========================================================
	//  Delete File
	//	The confirm dialog for removing a file
	// ===========================================================	
	function files_deletedl()
	{	
		global $IN, $std, $sid;
		
		$this->output .= admin_head(GETLANG("nav_files"), GETLANG("nav_editfiles"));
		if ($IN["confirm"])
		{
			if ( $this->deleteLink($IN["id"]) )
			{
				$this->output .= GETLANG("deldl")."<br><br>";
				
				$this->output .= "+ <a href='admin.php?sid=$sid&area=files&act=edit'>".GETLANG("backto")." ".GETLANG("nav_editfiles")."</a><br>";
				$this->output .= "+ <a href='admin.php?sid=$sid&area=main'>".GETLANG("backto")." ".GETLANG("nav_adhome")."</a><br>";
			}
		}
		else if ($IN["cancel"])
		{
			$this->output .= GETLANG("delcancel")."<br><br>";				
			$this->output .= "+ <a href='admin.php?sid=$sid&area=files&act=edit'>".GETLANG("backto")." ".GETLANG("nav_editfiles")."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=files&act=editdl&id=".$IN["id"]."'>".GETLANG("backto")." ".GETLANG("nav_editdl")."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=main'>".GETLANG("backto")." ".GETLANG("nav_adhome")."</a><br>";
		}
		else
		{
			$std->warning (GETLANG("warn_dldel")."<p>"
					."<form method='post' action='admin.php?sid=$sid&area=files&act=deletedl'>"
					."<input type='hidden' name='id' value='".$IN["id"]."'>"
					."<input type='Submit' name='confirm' value='".GETLANG("yes")."'> <input type='Submit' name='cancel' value='".GETLANG("no")."'> </form>");
		}
		$this->output .= admin_foot();
	}

	// ===========================================================
	//  Display Unapproved
	//      Displays all files awaiting approval
	// ===========================================================
	function displayUnapproved()
	{
		global $sid, $DB, $std;

		$this->output .= admin_head(GETLANG("nav_files"), GETLANG("nav_approvefiles"));

		$result = $DB->query("SELECT * FROM dl_links WHERE approved=0");
		if ($myrow = $DB->fetch_row($result))
		{

			$forminput = "?sid=$sid&area=files&act=approveSelect";
			$data = array("cat_name"         => GETLANG("unapp"),
						  "form_approve"     => "$forminput" );

			$this->output .= $this->html->ad_file_approve_head($data);

			do
			{
				$id = $myrow["did"];

				$date = $std->convertDate($myrow["date"]);

				$name = $myrow[name];
				$approve = "<a href='admin.php?sid=$sid&area=files&act=qapprove&id=$id&'>".GETLANG("approve")."</a>";
				$delete = "<a href='admin.php?sid=$sid&area=files&act=deletedl&id=$id'>".GETLANG("delete")."</a>";
				$edit = "<a href='admin.php?sid=$sid&area=files&act=editdl&id=$id&approvePage=1'>".GETLANG("edit")."</a>";
				$data = array(    "name" => $name,
								"id" => $id,
								"author" => $myrow[author],
								"date" => $date,
								"counter" => $myrow[downloads],
								"approve" => "$approve",
								"edit" => "$edit",
								"delete" => "$delete" );

				$this->output .= $this->html->ad_file_approve_row($data);

			} while ( $myrow = $DB->fetch_row($result) );

			$this->output .= $this->html->ad_file_approve_foot();

		}
		else
			$std->info(GETLANG("nounapp"));

		$this->output .= admin_foot();
	}

	// ===========================================================
	//  Approve / Delete
	//    Quickly approve or delete all checked files
	// ===========================================================
	function checkedApprove()
	{
		global $DB, $IN, $std, $sid;
		$ids = count($IN["dlid"]);
		
		if ( $ids == 0 )
		{
			$std->error(GETLANG("er_noselect"));
			return;
		}
		if ( $IN["deleteChecked"] )
		{
			if ($IN["confirm"])
			{
				for ( $i=0; $i<$ids; $i++ )
				{
					$id = $IN["dlid"]["$i"];
					if ( !$this->deleteLink($id) )
						$std->error(GETLANG("er_stderrprefix"));
				}
				$std->info (GETLANG("deldl")."<p>"."<a href='admin.php?area=files&act=approvedl&sid=$sid'>".GETLANG("continue")."</a>");
			}
			else if ($IN["cancel"])
			{
				$std->info (GETLANG("delcancel")."<p>"."<a href='admin.php?area=files&act=modify&sid=$sid'>".GETLANG("continue")."</a>");
			}
			else
			{
				$warntext = "<p><form method='post' action='admin.php?area=files&act=approveSelect&sid=$sid'>";
				for ( $j=0; $j<$ids; $j++ )
					$warntext .= "<input type='hidden' name='dlid[]' value='".$IN["dlid"][$j]."'>";
				$warntext .= "<input type='hidden' name='deleteChecked' value='1'>";
				$warntext .= "<input type='Submit' name='confirm' value='".GETLANG("yes")."'> <input type='Submit' name='cancel' value='".GETLANG("no")."'> </form>";
				$std->warning (GETLANG("warn_dldel").$warntext);

			}
		}
		else
		{
			for ( $i=0; $i<$ids; $i++ )
			{
				$id = $_POST["dlid"]["$i"];
				$DB->query("UPDATE dl_links SET approved='1' WHERE did=$id");

				$result = $DB->query("SELECT * FROM dl_links WHERE did=$id");
				$myrow = $DB->fetch_row($result);

				$this->incrementCounter($myrow["categoryid"], $id, $myrow['name'], $myrow['owner']);

			}
			$std->info(GETLANG("dlapproved").".<br>"."<a href='index.php?area=files&act=unapproved&sid=$sid'>".GETLANG("continue")."</a>");
		}
	}

	// ===========================================================
	//  Quick Approve
	//      Approve selected file
	// ===========================================================
	function qApprove()
	{
		global $DB, $IN, $sid;
		$id = $IN["id"];

		$this->output .= admin_head(GETLANG("nav_files"), GETLANG("nav_approvefiles"));
		$DB->query("UPDATE dl_links SET approved='1' WHERE did=$id");

		$result = $DB->query("SELECT * FROM dl_links WHERE did=$id");
		$myrow = $DB->fetch_row($result);

		$this->incrementCounter($myrow["categoryid"], $id, $myrow['name'], $myrow['owner']);

		$this->output .= GETLANG("dlapproved")."<br><br>";
		$this->output .= "+ <a href='admin.php?sid=$sid&area=files&act=unapproved'>".GETLANG("backto")." ".GETLANG("nav_approvefiles")."</a><br>";
		$this->output .= "+ <a href='admin.php?sid=$sid&area=main'>".GETLANG("backto")." ".GETLANG("nav_adhome")."</a><br>";
		$this->output .= admin_foot();
	}

	function fileTypeControl()
	{
		global $IN, $DB, $CONFIG, $rwdInfo, $guser, $std, $sid;
		$this->output .= admin_head(GETLANG("nav_files"), GETLANG("nav_filetypes"));

		if ( $IN["submit"] )
		{
			require_once ROOT_PATH."/functions/upload.php";
			foreach ( $IN as $element )
			{
				if ( is_array($element) )
				{
					$update = array("mimetype" => $element["mimetype"],
									"icon" => $element["icon"],
									"allowed" => $element["allowed"],
									"maxsize" => $element["maxsize"]*1024);
					$id = $element["id"];
					$DB->update($update, "dl_filetypes", "fid=$id");
				}
			}

			$CONFIG["allowunknown"] = $IN["allowunknown"];
			$std->saveConfig($CONFIG);

			$this->output .= GETLANG("typesupdated")."<br><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=files&act=filetype'>".GETLANG("backto")." ".GETLANG("nav_filetypes")."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=main'>".GETLANG("backto")." ".GETLANG("nav_adhome")."</a><br>";
			$this->output .= admin_foot();
			return;
		}

		$this->output .= "<a href=\"\" onClick=\"window.open('admin.php?sid=$sid&area=files&act=lookup','lookup','width=400,height=150,resizable=no,scrollbars=no,toolbar=no,location=no')\">".GETLANG("mimelookup")."</a>";
		$mime_path = $rwdInfo->url."/skins/skin".$CONFIG["defaultSkin"]."/mime_types";
		$DB->query("SELECT * FROM `dl_filetypes`");
		$this->output .= "<form method=post enctype='multipart/form-data' action='admin.php?sid=$sid&area=files&act=filetype'>";
        $this->output .= new_table(-1, "acptablesubhead", "", "100%", "50");
        $this->output .= "";
		$this->output .= new_col();
		$this->output .= GETLANG("mimetype");
		$this->output .= new_col();
		$this->output .= GETLANG("icon");
		$this->output .= new_col();
		$this->output .= "<center>".GETLANG("allowed")."</center>";
		$this->output .= new_col();
		$this->output .= GETLANG("maxupload");
		$this->output .= new_col();
		$this->output .= GETLANG("currently");
		$this->output .= new_col();
		$this->output .= "&nbsp;";
        if ( $myrow = $DB->fetch_row() )
		{
			do
			{
				$maxsize = $myrow['maxsize'] / 1024;
			$this->output .= new_row();
				$this->output .= "<input name='{$myrow[mimetype]}[id]' type='hidden' value='{$myrow[fid]}'>";
				$this->output .= "<center><img src='{$mime_path}/{$myrow[icon]}'></center>";
				$this->output .= new_col();
				$this->output .= "<input name='{$myrow[mimetype]}[mimetype]' type='text' size='30' value='{$myrow[mimetype]}'>";
				$this->output .= new_col();
				$this->output .= "<input name='{$myrow[mimetype]}[icon]' type='text' size='30' value='{$myrow[icon]}'>";
				$this->output .= new_col();
				$checked = $myrow["allowed"] ? "checked" : "";
				$this->output .= "<center><input type='checkbox' name='{$myrow[mimetype]}[allowed]' value='1' $checked></center>";
				$this->output .= new_col();
				$this->output .= "<input name='{$myrow[mimetype]}[maxsize]' type='text' size='30' value='{$maxsize}'> k ";
				$this->output .= new_col();
				$this->output .= $std->my_filesize($myrow['maxsize']);
				$this->output .= new_col();
				$this->output .= "[ <a href='admin.php?sid=$sid&area=files&act=deltype&id={$myrow[fid]}'>".GETLANG("delete")."</a> ]";
			} while ( $myrow = $DB->fetch_row() );

		}
        $this->output .= new_row();
			$checked = $CONFIG["allowunknown"] ? "checked" : "";
			$this->output .= "<center><input type='checkbox' name='allowunknown' value='1' $checked></center>";
			$this->output .= new_col(4);
			$this->output .= GETLANG("allowunlisted")."<br>";
		$this->output .= end_table();
		$this->output .= "<center><input type='submit' name='submit' value='".GETLANG("submit")."'></center>";
		$this->output .= "</form>";
        $this->output .= "<center><a href='admin.php?sid=$sid&area=files&act=addmime'>".GETLANG("addnewfiletype")."</a></center>";
		$this->output .= admin_foot();
	}

	// ===========================================================
	//  Delete File type;
	//    The confirm dialog for removing a file type
	// ===========================================================
	function files_deleteft()
	{
		global $IN, $DB, $sid, $std;

		$this->output .= admin_head(GETLANG("nav_files"), GETLANG("nav_filetypes"));
		if ($IN["confirm"])
		{
			$id = $IN["id"];
			$DB->query("DELETE FROM dl_filetypes WHERE fid=$id");

			$this->output .= GETLANG("deltype")."<br><br>";

			$this->output .= "+ <a href='admin.php?sid=$sid&area=files&act=filetype'>".GETLANG("backto")." ".GETLANG("nav_filetypes")."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=main'>".GETLANG("backto")." ".GETLANG("nav_adhome")."</a><br>";
			$this->output .= admin_foot();
			return;

		}
		else if ($IN["cancel"])
		{
			$this->output .= $GETLANG("delcancel")."<br><br>";

			$this->output .= "+ <a href='admin.php?sid=$sid&area=files&act=filetype'>".GETLANG("backto")." ".GETLANG("nav_filetypes")."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=main'>".GETLANG("backto")." ".GETLANG("nav_adhome")."</a><br>";
			return;
		}
		else
		{
			$std->warning (GETLANG("warn_dldel")."<p>"
					."<form method='post' action='admin.php?sid=$sid&area=files&act=deltype'>"
					."<input type='hidden' name='id' value='".$IN["id"]."'>"
					."<input type='Submit' name='confirm' value='".GETLANG("yes")."'> <input type='Submit' name='cancel' value='".GETLANG("no")."'> </form>");

		}
		$this->output .= admin_foot();
	}

	function addMimeType()
	{
		global $IN, $DB, $sid;
		$this->output .= admin_head(GETLANG("nav_files"), GETLANG("nav_filetypes"));

		if ( $IN["submit"] )
		{
			$insert = array("mimetype" => $IN["mimetype"],
							"icon" => $IN["icon"],
							"allowed" => $IN["allowed"],
							"maxsize" => $IN["maxsize"]);
			$DB->insert($insert, "dl_filetypes");


			$this->output .= GETLANG("addtype")."<br><br>";

			$this->output .= "+ <a href='admin.php?sid=$sid&area=files&act=filetype'>".GETLANG("backto")." ".GETLANG("nav_filetypes")."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=files&act=addmime'>".GETLANG("backto")." ".GETLANG("nav_addtypes")."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=main'>".GETLANG("backto")." ".GETLANG("nav_adhome")."</a><br>";

			$this->output .= admin_foot();
			return;
		}
		$this->output .= "<a href=\"\" onClick=\"window.open('admin.php?sid=$sid&area=files&act=lookup','lookup','width=400,height=150,resizable=no,scrollbars=no,toolbar=no,location=no')\">Mime Type Lookup</a>";
		$this->output .= "<form method=post enctype='multipart/form-data' action='admin.php?sid=$sid&area=files&act=addmime'>";
		
		$this->output .= new_table(-1, "acptablesubhead", "", "100%", "50");
			$this->output .= GETLANG("mimetype");
			$this->output .= new_col();
			$this->output .= GETLANG("icon");
			$this->output .= new_col();
			$this->output .= "<center>".GETLANG("allowed")."</center>";
			$this->output .= new_col();
			$this->output .= GETLANG("maxupload");

		$this->output .= new_row();
			$this->output .= "<input name='mimetype' type='text' size='30'>";
			$this->output .= new_col();
			$this->output .= "<input name='icon' type='text' size='30'>";
			$this->output .= new_col();
			$this->output .= "<center><input type='checkbox' name='allowed' value='1' checked></center>";
			$this->output .= new_col();
			$this->output .= "<input name='maxsize' type='text' size='30'>";

        $this->output .= end_table();
		$this->output .= "<input type='submit' name='submit' value='".GETLANG("submit")."'>";
		$this->output .= "</form>";

		$this->output .= admin_foot();

	}

	function typeLookup()
	{
		global $IN, $sid, $rwdInfo;

		$this->output .= admin_head(GETLANG("nav_files"), GETLANG("nav_typeLookup"));
		if ( $IN["submit"] )
		{
			if (!is_file("/usr/local/apache/conf/mime.types"))
				$mimePath = $rwdInfo->path."/mime.types";
			else
				$mimePath = "/usr/local/apache/conf/mime.types";
			require_once ROOT_PATH."/functions/mime_types.php";
			$mime = new Mime_Types($mimePath);
			$file_type = $mime->get_type($IN["ext"]);
			if ( !$file_type )
				$file_type = "application/octet-stream";
		}
		
		$this->output .= "<form method=post enctype='multipart/form-data' action='admin.php?sid=$sid&area=files&act=lookup'>";
		$this->output .= new_table(3);
			$this->output .= GETLANG("mime_usethisform");
			if ($file_type)
				$this->output .= "<br><b>$IN[ext] = $file_type</b>";
		$this->output .= new_row();
			$this->output .= GETLANG("extension");
			$this->output .= new_col();
			$this->output .= "<input name='ext' type='text' size='10' value='$IN[ext]'>";
			$this->output .= new_col();
			$this->output .= "<input type='submit' name='submit' value='".GETLANG("submit")."'>";
			$this->output .= "</form>";
		$this->output .= end_table();
		$this->output .= admin_foot();

	}
}

$loader = new admin_files();
?>