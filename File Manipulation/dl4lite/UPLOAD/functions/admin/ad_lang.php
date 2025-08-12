<?php

$loader = new admin_lang();

class admin_lang
{
	var $html	= "";
	var $output = "";
	function admin_lang()
	{
		global $IN, $OUTPUT;
		
		//$this->html = $OUTPUT->load_template("skin_files");

		switch($IN["act"])
		{				
			case 'manage':
				$this->lang_manage();
				break;
			case 'edit':
				$this->lang_edit();
				break;
			case 'save':
				$this->lang_save();
				break;
			case 'export':
				$this->lang_export();
				break;
			case 'import':
				$this->lang_import();
				break;
			case 'new':
				$this->lang_new();
				break;
            case 'delete':
                $this->lang_delete();
                break;
		}
		
		$OUTPUT->add_output($this->output);
	}
	
	function lang_new()
	{
		global $DB, $IN, $std, $sid;
		
		$this->output .= admin_head(GETLANG("nav_lang"), GETLANG("nav_newlang"));
				
		if ($IN['submit']) 
		{
			$DB->query("SELECT `name`,`author` FROM `dl_langsets` WHERE lid='{$IN['langid']}'");
			if (!($myrow = $DB->fetch_row()))
			{
				$std->error(GETLANG("invalid_lang"));
				$this->output .= admin_foot();
				return;
			}
			$insert['name'] = $myrow['name']." [copy]";
			$insert['author'] = $myrow['author'];
			$DB->insert($insert, "dl_langsets");
			$newid = $DB->insert_id();
			
			$std->mycopy(ROOT_PATH."/lang/{$IN['langid']}/", ROOT_PATH."/lang/{$newid}/");
			$this->output .= GETLANG("lang_created");
			$this->output .= admin_foot();
			return;
		}
		
		$this->output .= "<form method=POST action='admin.php?sid=$sid&area=lang&act=new'>";
		$DB->query("SELECT * FROM `dl_langsets`");
		$this->output .= new_table();
		$this->output .= new_row(2, "acptablesubhead", "", "16")."&nbsp;";
		$this->output .= GETLANG("select_lang");
		$this->output .= new_row();
		$this->output .= GETLANG("baseskin");
		$this->output .= new_col();
		$this->output .= "<select name='langid'>";
		while ( $myrow = $DB->fetch_row() )
		{
			$this->output .= "<option value={$myrow['lid']}>{$myrow['name']}</option>";
		} 
		$this->output .= "</select>";
		$this->output .= " <input type='submit' name='submit' value='Submit'>";
		$this->output .= "</form>";
		$this->output .= end_table();
		$this->output .= admin_foot();
	}
	
	function lang_import()
	{
		global $CONFIG, $IN, $DB, $rwdInfo, $sid, $std;
		require_once ROOT_PATH."/functions/tar.php";
		$langpath = $CONFIG['sitepath'].'/archive_in/';
		
		$dir_handle = @opendir($langpath) or die("Unable to open $langpath");
		$this->output .= admin_head(GETLANG("nav_lang"), GETLANG("nav_importlang"));
		
		if ($IN['import']) 
		{
			$insert = array("name" => "",
							"author" => "");
			$DB->insert($insert, "dl_langsets");
			$newid = $DB->insert_id();
			if (@mkdir($CONFIG['sitepath']."/lang/{$newid}/", 0777))
			{
			    $tar = new tar();
				$tar->new_tar($langpath, $IN['import'].".tar");
				$files = $tar->list_files();
				$tar->extract_files( $CONFIG['sitepath']."/lang/{$newid}/" );
				
				$filename = $CONFIG['sitepath']."/lang/{$newid}/lang.data";
				$handle = fopen($filename, "rb");
				$contents = fread($handle, filesize($filename));
				fclose($handle);
				$types = explode("|", $contents);
				
				$update = array("name" => $types[0]." [import]",
								"author" => $types[1]);
				$DB->update($update, "dl_langsets", "lid={$newid}");
				
				$this->output .= GETLANG("import_successful");
			}
			else
			{
				$std->error(GETLANG("no_lang_directory"));
			}
			$this->output .= admin_foot();
			return;
		}
		
		if ($IN['delete']) 
		{
			
			if (@unlink($langpath.$IN['delete'].".tar"))
			{
				$this->output .= GETLANG("tar_deleted");
			}
			else
			{
				$std->error .= GETLANG("delete_failed");
			}
			$this->output .= admin_foot();
			return;
		}
		$this->output .= new_table();
		$this->output .= new_row(4, "acptablesubhead");
		$this->output .= GETLANG("lang_import");
		$counter = 0;
		while($filename = readdir($dir_handle)) 
		{
			if (($filename != ".") && ($filename != ".."))
			{
				if ( preg_match( "/\.tar$/", $filename ) )
				{
					if (strstr($filename, "lang_")) 
					{
						$counter++;
						$this->output .= new_row(-1, "", "", "16")."<img src='{$rwdInfo->skinurl}/images/edit.gif'>";
						$this->output .= new_col();
						$this->output .= $std->strip_ext($filename);
						$this->output .= new_col();
						$this->output .= "<a href='admin.php?sid=$sid&area=lang&act=import&import=".$std->strip_ext($filename)."'>".GETLANG("import")."</a>";
						$this->output .= new_col();
						$this->output .= "<a href='admin.php?sid=$sid&area=lang&act=import&delete=".$std->strip_ext($filename)."'>".GETLANG("delete")."</a>";
					}
				}
			}
		} 
		closedir($dir_handle);
		if ( $counter == 0 ) 
		{
		    $this->output .= new_row();
			$this->output .= GETLANG("no_imports");
		}
		$this->output .= end_table();
		$this->output .= admin_foot();
		return;

	}

	function lang_export()
	{
		global $std, $sid, $IN, $DB, $CONFIG;
		require_once ROOT_PATH."/functions/tar.php";
		
		$this->output .= admin_head(GETLANG("nav_lang"), GETLANG("nav_exportlang"));
		
		if ( $IN['submit'] )
		{
			$DB->query("SELECT * FROM `dl_langsets` WHERE `lid`={$IN['langid']}");
			$myrow = $DB->fetch_row();
			$fileName = $CONFIG['sitepath']."/lang/{$IN['langid']}/lang.data";
			if ( $fp = @fopen( $fileName, 'w' ) )
			{
				$langdata = "{$myrow['name']}|{$myrow['author']}";
				fwrite($fp, $langdata, strlen($langdata) );
				fclose($fp);
				$tar = new tar();
				$tar->new_tar($CONFIG['sitepath']."/archive_out/" , "lang_{$IN['langid']}.tar");
				$std->error .= "<p>".$tar->error;
				$tar->current_dir($CONFIG['sitepath']);
				$std->error .= "<p>".$tar->error;
				$tar->add_directory($CONFIG['sitepath']."/lang/{$IN['langid']}");
				$std->error .= "<p>".$tar->error;
				$tar->write_tar();
				$std->error .= "<p>".$tar->error;
				$this->output .= GETLANG("export_complete");
				$this->output .= "<p>".GETLANG("click_download")." [ <a href='{$CONFIG['siteurl']}/archive_out/lang_{$IN['langid']}.tar'>".GETLANG("download")."</a> ]";
				$this->output .= admin_foot();
				return;
			}
			else
		    {
				$this->error(GETLANG("nodatafile"));
				return false;
		    }   
			
		}
		
		$this->output .= "<form method=POST action='admin.php?sid=$sid&area=lang&act=export'>";
		$DB->query("SELECT * FROM `dl_langsets`");
		$this->output .= new_table();
		$this->output .= new_row(-1, "acptablesubhead", "", "16")."&nbsp;";
		$this->output .= GETLANG("select_lang");
		$this->output .= new_row();
		$this->output .= "<select name='langid'>";
		while ( $myrow = $DB->fetch_row() )
		{
			$this->output .= "<option value={$myrow['lid']}>{$myrow['name']}</option>";
		} 
		$this->output .= "</select>";
		$this->output .= " <input type='submit' name='submit' value='".GETLANG("submit")."'>";
		$this->output .= "</form>";
		$this->output .= end_table();

		$this->output .= admin_foot();
	}
	function lang_save()
	{
		global $IN, $OUTPUT, $std, $sid;
		$this->output .= admin_head(GETLANG("nav_lang"), GETLANG("nav_editlang"));
		$langpath = ROOT_PATH.'/lang/'.$IN['lid'];
		
		if ( ! is_readable($langpath) )
		{
			$std->error("Cannot write into '$langpath', please check the CHMOD value, and if needed, CHMOD to 0777 via FTP.");
			return 0;
		}
		
		$output  = "<?php \n\n// {$IN['langfile']}.php\n";
		$output .= "// Language file auto generated by RW::Download {$version}\n";
		$output .= "// It is highly recommended you do NOT edit this file\n";
		$output .= "// Doing so could break the language engine and the script\n";
		$output .= "// This language can be edited by using the Language Controls in the Admin CP\n\n";
		
		$output .= "\$lang = array(\n\n";
		ksort($IN['postlang']);
		foreach($IN['postlang'] as $k => $v)
		{
			$v = stripslashes($v);
			
			$content = str_replace( "&quot;", 	"\"", 	$v );
			$content = str_replace( "&#39;", 	"'", 	$content );
			$content = str_replace( "&amp;", 	"&", 	$content );
			$content = str_replace( "&#036;", 	"$", 	$content );
	        $content = str_replace( "&#60;script",  "<script ", $content);
			$content = str_replace( "&#60;&#33;--", "<!--", $content );
			$content = str_replace( "--&#62;",	"-->",	$content );
			$content = str_replace( "&#33;",	"!",	$content );
			$content = str_replace( "&gt;", 	">", 	$content );
			$content = str_replace( "&lt;", 	"<", 	$content );
			$content = addslashes($content);
			$output .= "'{$k}' => \"{$content}\",\n";
		}
		
		$output .= "\n)\n\n?>";
		
		$fileName = $langpath."/".$IN['langfile'].".php";
		if ( $fp = fopen( $fileName, 'w' ) )
		{
			fwrite($fp, $output, strlen($output) );
			fclose($fp);
			$this->output .= GETLANG("langupdated")."<br><br>";
				
			$this->output .= "+ <a href='admin.php?sid=$sid&area=lang&act=manage'>".GETLANG("backto")." ".GETLANG("nav_editlang")."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=lang&act=edit&lid={$IN['lid']}&langset={$IN['langfile']}'>".GETLANG("backto")." ".GETLANG("nav_editlang")." ".$IN['langfile']."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=main'>".GETLANG("backto")." ".GETLANG("nav_adhome")."</a><br>";
		}
		else
	    {
			$std->error(GETLANG("no_lang_files")."<br>".$fileName);
	    }
		
		$this->output .= admin_foot();
		
	}
	
	function lang_edit()
	{
		global $IN, $sid;
		$this->output .= admin_head(GETLANG("nav_lang"), GETLANG("nav_editlang"));
		
		$langpath = ROOT_PATH.'/lang/'.$IN['lid'];
		
		if ( ! file_exists( $langpath ) )
		{
			$std->error(GETLANG("langsetmissing"));
			return 0;
		}
		
		if ( ! is_readable($langpath) )
		{
			$std->error(GETLANG("no_lang_files")."<br>".$fileName);
			return 0;
		}
		$this->output .= "<form method=POST action='admin.php?sid=$sid&area=lang&act=save'>";
		$this->output .= new_table();
		$this->output .= new_row(-1, "acptablesubhead", "", "150");
		$this->output .= GETLANG("skin_tag");
		$this->output .= new_col();
		$this->output .= GETLANG("content");
		
		require ROOT_PATH.'/lang/'.$IN['lid']."/".$IN['langset'].".php";
		foreach($lang as $k => $v)
		{
			$this->output .= new_row();
			$this->output .= "{lang.".$k."}";
			$this->output .= new_col();
			$this->output .= "<textarea style='width:100%' rows=3 name='postlang[$k]'>{$v}</textarea>";
		}
		$this->output .= end_table();
		$this->output .= "<center><input type='submit' name='saveskin' value='".GETLANG("save_changes")."'> ";
		$this->output .= "<input type='reset' name='reset' value='".GETLANG("reset")."'></center>";
		$this->output .= "<input type='hidden' name='langfile' value='{$IN['langset']}'>";
		$this->output .= "<input type='hidden' name='lid' value='{$IN['lid']}'>";
		$this->output .= admin_foot();

	}
	function lang_manage()
	{
		global $sid, $std, $rwdInfo, $IN, $DB;
				
		$this->output .= admin_head(GETLANG("nav_lang"), GETLANG("nav_editlang"));
		
		if ( $IN['lid'] )
		{
			
			$langpath = ROOT_PATH.'/lang/'.$IN['lid'];
			$dir_handle = @opendir($langpath) or die("Unable to open $langpath");
			if ($IN['submit']) 
			{
			    $update = array("name" => $IN['name'],
								"author" => $IN['author']);
				$DB->update($update, "dl_langsets", "lid={$IN['lid']}");
				$this->output .= GETLANG("details_saved");
			}
			$DB->query("SELECT * FROM `dl_langsets` WHERE `lid`='{$IN['lid']}'");
			$myrow2 = $DB->fetch_row();
			$this->output .= "<form method=POST action='admin.php?sid=$sid&area=lang&act=manage&lid={$IN['lid']}'>";
			$this->output .= new_table();
			$this->output .= new_row(2, "acptablesubhead", "", "16");
			$this->output .= GETLANG("edit_details");
			$this->output .= new_row();
			$this->output .= GETLANG("name");
			$this->output .= new_col();
			$this->output .= "<input type='text' name='name' value='{$myrow2['name']}' size='30'>";
			$this->output .= new_row();
			$this->output .= GETLANG("author");
			$this->output .= new_col();
			$this->output .= "<input type='text' name='author' value='{$myrow2['author']}' size='30'>";
			$this->output .= new_row();
			$this->output .= new_col();
			$this->output .= "<input type='submit' name='submit' value='Submit'>";
			$this->output .= end_table();
			$this->output .= "</form>";
			
			$this->output .= new_table();
			$this->output .= new_row(-1, "acptablesubhead", "", "16")."&nbsp;";
			$this->output .= new_col();
			$this->output .= GETLANG("langpack");
			
			while($filename = readdir($dir_handle)) 
			{
				if (($filename != ".") && ($filename != ".."))
				{
					if ( preg_match( "/\.php$/", $filename ) )
					{
						$this->output .= new_row(-1, "", "", "16")."<img src='{$rwdInfo->skinurl}/images/edit.gif'>";
						$this->output .= new_col();
						$this->output .= "<a href='admin.php?sid=$sid&area=lang&act=edit&lid={$IN['lid']}&langset=".$std->strip_ext($filename)."'>".$std->strip_ext($filename)."</a>";
					}
				}
			} 
			closedir($dir_handle);
			$this->output .= end_table();	
			$this->output .= admin_foot();
			return;
		}
		
		$DB->query("SELECT * FROM `dl_langsets`");
		$this->output .= new_table();
		$this->output .= new_row(-1, "acptablesubhead", "", "16")."&nbsp;";
		$this->output .= new_col();
		$this->output .= GETLANG("langname");
        $this->output .= new_col();
		$this->output .= GETLANG("delete");
		while ( $myrow = $DB->fetch_row() )
		{
			$this->output .= new_row(-1, "", "", "16")."<img src='{$rwdInfo->skinurl}/images/closed.gif'>";
			$this->output .= new_col();
			$this->output .= "<a href='admin.php?sid=$sid&area=lang&act=manage&lid={$myrow['lid']}'>{$myrow['name']}</a>";
            $this->output .= new_col();
			$this->output .= "[ <a href='admin.php?sid=$sid&area=lang&act=delete&id={$myrow['lid']}'>".GETLANG("delete")."</a> ]";
		}
		$this->output .= end_table();
		
		$this->output .= admin_foot();
	}

    function lang_delete()
    {
        global $std, $sid, $rwdInfo, $IN, $DB;

        $id = $IN['id'];

		$this->output .= admin_head(GETLANG("nav_lang"), GETLANG("nav_deletelang"));
        if ($IN["confirm"])
		{
            $DB->query("DELETE FROM `dl_langsets` WHERE `lid`='{$id}'");
            if ( $std->rmdirr($rwdInfo->path."/lang/{$id}/") )
            	$this->output .= GETLANG("langdeleted")."<br><br>";
            else
                $this->output .= GETLANG("langdelfail")."<br><br>";

			$this->output .= "+ <a href='admin.php?sid=$sid&area=lang&act=manage'>".GETLANG("backto")." ".GETLANG("nav_editlang")."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=main'>".GETLANG("backto")." ".GETLANG("nav_adhome")."</a><br>";

		}
		else if ($IN["cancel"])
		{
			$this->output .= GETLANG("delcancel")."<br><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=lang&act=manage'>".GETLANG("backto")." ".GETLANG("nav_editlang")."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=lang&act=manage&lid=".$IN['id']."'>".GETLANG("backto")." ".GETLANG("nav_editlangset")."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=main'>".GETLANG("backto")." ".GETLANG("nav_adhome")."</a><br>";
		}
		else
		{
			$std->warning (GETLANG("warn_dldel")."<p>"
					."<form method='post' action='admin.php?sid=$sid&area=lang&act=delete'>"
					."<input type='hidden' name='id' value='{$id}'>"
					."<input type='Submit' name='confirm' value='".GETLANG("yes")."'> <input type='Submit' name='cancel' value='".GETLANG("no")."'> </form>");
		}
		$this->output .= admin_foot();
    }
}

?>