<?php
// +------------------------------------------------------------------+
// | evoArticles 
// | Copyright (c) 2003-2004 Evo-Dev
// +------------------------------------------------------------------+

class addon_Article
{
	
	function addon_Article()
	{
		global $settings;
		$this->upload_dir = OUT_FOLDER."images/";
		$this->support_dir = OUT_FOLDER."support/";
		
		// cache
		$this->cache_dir = OUT_FOLDER."cache/";

		$this->kill_all = 1; //delete subcats and articles of a category upon deletion?
		$this->init();


		$this->makenav = 1; // make nav
		$this->search_noopt = 0;

		$this->main_file = "index.php";
		$this->push_dir = MISC_FOLDER;

		//search results perpage
		$this->perpage = "15";
		$this->perpage_opt = "5"; // gandaan ape?

		$this->force_ses = 0; // dont enable this
	
	}
	
	

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
							INIT
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function init()
	{
		global $settings,$root;
		$this->def_folder = $root."templates/styles/".$settings['defstyle'];
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
						MISC. FUNCTIONS
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function check_jscode($code)
	{
		//$code = htmlspecialchars($code); <- BODO! TAK WORK

		//this a workaround for javascript escape character
		$translate_back = array('\\' => '\\\\' );
		foreach($translate_back as $key => $value)
		{
			$code = str_replace($key,$value,$code);
		}
		
		return $code;
	} 

	function make_breadcrumbs($cid='')
	{
		global $database,$udb,$_SERVER,$admin,$evoLANG,$settings;
		if (is_array($this->cat_cache)) $cat_cache = &$this->cat_cache;

		$cinfo = $this->get_category($cid);
		$cinfo['name'] = $admin->strip($cinfo['name']);
		$ccat['pid'] = $cinfo['pid'];
		
		do
		{
			unset($a);
			$ccat = $this->get_category($ccat['pid']);
			$ccat['name'] = $admin->strip($ccat['name']);
			
			if(!$ccat['cid']) break;
			
			if ($ccat['name'] !='')
			{
				$a = $settings['navsplit'];
			}

			$out = " <a href=\"".$this->link_cat($ccat['cid'])."\">".htmlspecialchars($ccat['name'])."</a> ".$a.$out;	
		}
		
		while($ccat['pid'] != 0);

		$end = " <a href=\"".$this->link_cat($cinfo['cid'])."\">".htmlspecialchars($cinfo['name'])."</a> ";
		$home = $this->nav_showhome == 1 ? $admin->makelink($evoLANG['home'],"index.php").$settings['navsplit']:'';
		
		if (is_array($this->nav_artarray) && $this->nav_showart == 1)
		{
			$artinfo = &$this->nav_artarray;
			$article = $settings['navsplit']." ".$admin->makelink( htmlspecialchars($artinfo['subject']),$this->link_art($artinfo['id']) );
		}
	
		return $home.$out.$end.$article;
	}


	function check_adminfolder($what)
	{
		global $_SERVER,$root;

		if (preg_match("#/admin/#",$_SERVER['PHP_SELF']))
		{
			return "";
		}
		else
		{
			return $root."admin/";
		}
	}

	function wrap_confirm($text,$loc)
	{
		global $evoLANG;

		$js = "var tanya = confirm('".$evoLANG['confirm']."'); if (tanya == true) { window.location = '".$loc."'}";
		return "<span style=\"cursor: hand; border-bottom: 1px dashed #333\" onclick=\"".$js."\">".$text."</span>";
	}

	function get_uploadinfo()
	{
		global $evoLANG,$settings,$admin;
		
		$stifler .= $evoLANG['allowedfiletype']." : <i>".$settings['supportfiletype']."</i> <br />";
		$stifler .= $evoLANG['maxsize']." : <i>".$admin->file_size($settings['supportmaxsize'])."</i>";
		
		// geez, we all love American Pie Trilogy dont we? 
		return $stifler;
	}
	

	// this will create the funny options of article dropdown selector
	function makeoptions($pid='0',$selected='',$depth=1,$not='')
	{
		global $cat_cache,$udb,$database,$admin;
		
		if ( !is_array($cat_cache) )
		{
			$sql = $udb->query("SELECT * FROM $database[article_cat] ORDER by cid ASC");
			while ($row = $udb->fetch_array($sql))
			{
				$row = $admin->strip_array($row);
				$cat_cache[$row['pid']][$row['cid']] = $row;
			}
		}
		
		$cache = $cat_cache;

		$xaccess = explode(",",$not);
			
			if(!isset($cache[$pid])) return;
		
			while (list($parent,$category) = each($cache[$pid]))
			{
					unset($sel);
					if ($category[cid] == $selected)
					{
						$sel = "selected=\"selected\"";
					}				
					
						if ( !in_array($category['cid'],$xaccess) )
						{
							$a .= "<option value=\"".$category[cid]."\"".$sel.">";
										
							if ($depth > 1)
							{ 
								$a .= str_repeat("-",$depth-1)." ".htmlspecialchars($category['name'])."</option>";
							}
							else
							{
								$a .= htmlspecialchars($category['name'])."</option>";						
							}
						}

					$a .= $this->makeoptions($category[cid],$selected,$depth+1,$not);
			} 
		
		$udb->free_result($sql);
		return $a;
	}

	function makeselectbox($name,$selected='',$not='')
	{
		global $udb,$admin,$evoLANG,$cat_cache,$userinfo;
		$this->parent_name = $this->parent_name != "" ? $this->parent_name:$evoLANG['noparent'];
		$not = $userinfo['access'];
			//$name = $admin->strip_array($name);
			$a .= "<select name=\"".$name."\">\n";
				$a .= "<option value=''> ".$this->parent_name." </option>\n";
				$a .= $this->makeoptions("0",$selected,1,$not);
			$a .= "</select>";

		return $a;
	}
	
	//yep, you've guessed it, it makes the style selector. haha
	function makestyleselect($name,$sel='')
	{
		global $udb,$database,$admin,$evoLANG;
		$sql = $udb->query("SELECT * FROM $database[article_styles]	ORDER BY id	");

		while($s_row = $udb->fetch_array($sql))
		{
			$s_row = $admin->strip_array($s_row);
			$style_opt .= $s_row['tplfolder']."|&nbsp;- ".$s_row['name'].",";
		}

		return $admin->form_select($name,$style_opt,$sel,$evoLANG['s_defaultstyle']);
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
							ADMIN MAIN
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	
		
	function main() 
	{
		global $settings_db,$udb,$settings,$tpl;
			
		// i dont even know what this is for. damn
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
						MISC => REDIRECT
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function redirect($loc)
	{
		if ($loc != "")
		{
			header("location: ".$loc);
		}
	}
	
	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
					ADMIN: erm... MASS MOVE
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	// let see, move to category (by date or all)
	function do_massmove()
	{
		global $tpl,$_GET,$admin,$_SERVER,$evoLANG,$settings,$_POST,$database,$udb;
		
		if ($_POST['move'])
		{
			switch ($_POST['type'])
			{
				case "simple":
					$get_sql = $udb->query("SELECT id FROM $database[article_article] WHERE pid='".$_POST['from']."'");
					$get_total = $udb->num_rows($get_sql);
					
					if ($get_total > 0)
					{
						$udb->query("UPDATE $database[article_article] SET pid = '".$_POST['to']."' WHERE pid='".$_POST['from']."'");
						return $evoLANG['totalmoved']." :".$get_total.$admin->redirect('index.php');
					}
					else
					{
						return $admin->warning($evoLANG['massmove_error1']);
					}

				break;
				/* ----------------------------------------------------------------------------------------------------*/
				case "bydate":
					$time_start = mktime (0,0,0,$_POST['start_date']['month'],$_POST['start_date']['day'],$_POST['start_date']['year']);
					$time_end =   mktime (0,0,0,$_POST['end_date']['month'],$_POST['end_date']['day'],$_POST['end_date']['year']);

					$get_sql = $udb->query("SELECT id FROM $database[article_article] WHERE pid='".$_POST['from']."' AND date > $time_start AND date < $time_end");
					$get_total = $udb->num_rows($get_sql);
					
					if ($get_total > 0)
					{
						$udb->query("UPDATE $database[article_article] SET pid = '".$_POST['to']."' WHERE pid='".$_POST['from']."' AND date > $time_start AND date < $time_end");
						return $evoLANG['totalmoved']." :".$get_total;
					}
					else
					{
						return $admin->warning($evoLANG['massmove_error1']);
					}

				break;
			}
		}
		
		$admin->row_width = "30%";
		$admin->row_align = "left";
		
		/* ------------------------------------------------ */
		$admin->do_check = 1; // enable js validating
		$admin->check_element = array(
										"from"      => $evoLANG['from'],
										"to"	    => $evoLANG['to']
	        						 );
		/* ------------------------------------------------ */
		$content .= $evoLANG['d_massmove'];
		
		$content .= $admin->form_start("simple",$_SERVER['PHP_SELF']."?do=massmove").$admin->form_hidden("type","simple");
		$simple_html .= $admin->add_spacer($evoLANG['massmove']." : ".$evoLANG['simple']);
		$this->parent_name = $evoLANG['selectcat'];
		$simple_html .= $admin->add_row( $evoLANG['from'],$this->makeselectbox("from") );
		$simple_html .= $admin->add_row( $evoLANG['to'],$this->makeselectbox("to") );
		$simple_html .= $admin->form_submit("move");
		$simple_table = $admin->add_table($simple_html,"60%");
		$content .= $simple_table;
		
		$content .= "</form><br />";
		
		/* ------------------------------------------------ */
		$admin->do_check = 1; // enable js validating
		$admin->check_element = array(
										"from"      => $evoLANG['from'],
										"to"	    => $evoLANG['to']
	        						 );
		/* ------------------------------------------------ */
		 
		$this->date_prefix = "start_";
		$date1 = $this->dateselect(time()-2592000);
		
		$this->date_prefix = "end_";
		$date2 = $this->dateselect();

		$content .= $admin->form_start("bydate",$_SERVER['PHP_SELF']."?do=massmove").$admin->form_hidden("type","bydate");
		$date_html .= $admin->add_spacer($evoLANG['massmove']." : ".$evoLANG['date']);
		$this->parent_name = $evoLANG['selectcat'];
		$date_html .= $admin->add_row( $evoLANG['from'],$this->makeselectbox("from") );
		$date_html .= $admin->add_row( $evoLANG['submittedbetween'] ,$date1."<br />".$evoLANG['and']."<br />".$date2 );
		$date_html .= $admin->add_row( $evoLANG['to'],$this->makeselectbox("to") );
		$date_html .= $admin->form_submit("move");
		$date_table = $admin->add_table($date_html,"60%");
		$content .= $date_table;

		$content .= "</form><br />";

		return $content;
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
					ADMIN: IMPORT ART
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function do_importart()
	{
		global $tpl,$_GET,$admin,$_SERVER,$evoLANG,$settings,$_POST,$_FILES;
		

		if ($_POST['importfile'])
		{
			if ($_FILES['file']['size'] > 0)
			{
				$content = $admin->get_file($_FILES['file']['tmp_name']);
				$content2 = preg_replace("/(\<)(body)(.*)(>)(\n)*(.*)(\<\/body\>)/esiU","\$this->importvalue('\\6')",$content);
				
				return $this->addarticle( $this->defvalue );
			}
		}
		$admin->row_align = "left";
		$admin->row_width = "30%";
		/* ------------------------------------------------ */
		$admin->do_check = 1; // enable js validating
		$admin->check_element = array(
										"file"        => $evoLANG['catname']
									 );
		// element condition
		/* ------------------------------------------------ */
		
		$html .= $admin->form_start("",$_SERVER['PHP_SELF']."?do=importart","1");

		$table .= $admin->add_spacer($evoLANG['importart']);
		$table .= $admin->add_row($evoLANG['file'],$admin->form_input("file","","file"),$evoLANG['d_importart'] );
		$table .= $admin->form_submit("importfile");
		
		$html .= $admin->add_table($table,"90%");
		
		return $html;
	}

	function importvalue($val)
	{
		//echo $this->addarticle( stripslashes($val) );
		$this->defvalue = stripslashes($val);
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
					ADMIN: ADD CATEGORY
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function addcat()
	{
		global $tpl,$_GET,$admin,$_SERVER,$evoLANG,$settings;
		
		$this->formname = 'form_addcat';
		$admin->row_align = "left";
		$admin->row_width = "30%";
		/* ------------------------------------------------ */
		$admin->do_check = 1; // enable js validating
		$admin->check_element = array(
										"name"        => $evoLANG['catname'],
										"description" => $evoLANG['description']
									 );
		// element condition
		/* ------------------------------------------------ */
		$parent = $this->makeselectbox("pid",$_GET['fid']);
		$customfields = $this->cat_make_fields();

		$html .= $admin->form_start($this->formname,$_SERVER['PHP_SELF']."?do=submit");

		$table .= $admin->add_spacer($evoLANG['addcat']);
		$table .= $admin->add_row($evoLANG['parent'],$parent);
		$table .= $admin->add_row($evoLANG['catname'],$admin->form_input("name") );
		$table .= $admin->add_row($evoLANG['description'],$admin->form_textarea("description") );
		$table .= $admin->add_row($evoLANG['cat_image'],$admin->form_input("cat_image",$row['cat_image']),$evoLANG['d_cat_image'] );
		if ($settings['usecomment'] == 1)
		{
			$table .= $admin->add_row($evoLANG['usecomment'],$admin->form_radio_yesno("usecomment",1),$evoLANG['d_usecomment_cat']);
		}
		else
		{
			$nocomment = $admin->form_hidden("usecomment",0);
		}

		$table .= $admin->add_row($evoLANG['assignstyle'],$nocomment.$this->makestyleselect("style",str_replace("-1","",$row['style']) ) );
		
		

		if ($settings['allowmeta_cat'] == 1)
		{
			$table .= $admin->add_spacer($evoLANG['custommeta']);
			$table .= $admin->add_row( $evoLANG['meta_key'],$admin->form_input("meta_key") );
			$table .= $admin->add_row( $evoLANG['meta_desc'],$admin->form_textarea("meta_desc") );
		}

		$table .= $customfields;

		$table .= $admin->form_submit("addcat");
		
		$html .= $admin->add_table($table,"90%");
		
		return $html;
	}

	function sql_addcat()
	{
		global $udb,$settings,$tpl,$_POST,$admin,$database;
			
			$_POST = $admin->slash_array($_POST);
			$style = $_POST['style'] == "-1" ? "":$_POST['style'];
			
			// process custom fields
			$sql = $udb->query("SELECT * FROM ".$database['article_catfield']);
			while ($row = $udb->fetch_array($sql) )
			{
				$additional .= "custom_".$row['fieldid']." = '".$_POST['custom_'.$row['fieldid']]."',";
			}

			$udb->query("INSERT INTO $database[article_cat] SET ".$additional." pid='".$_POST['pid']."',name='".$_POST['name']."',description='".$_POST['description']."',cat_image='".$_POST['cat_image']."',style='".$style."',meta_key='".$_POST['meta_key']."',meta_desc='".$_POST['meta_desc']."',usecomment='".$_POST['usecomment']."'");

			$this->redirect($_SERVER['PHP_SELF']."?do=managecat");
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
					ADMIN: DELETE CATEGORY
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function deletecat($id)
	{
		global $database,$udb,$_SERVER,$admin,$settings,$evoLANG,$_POST,$_GET;

		if ($id == '') $this->redirect($_SERVER['PHP_SELF']);
		
		$gettotal = $udb->query_once("SELECT COUNT(cid) AS total_cat FROM ".$database['article_cat']);
		if ( $gettotal['total_cat'] == "1")
		{
			return $evoLANG['notenoughcat'];
		}
		if ($_GET['confirm'] != 1)
		{
			// make move page
			/* ------------------------------------------------ */
			$admin->do_check = 0; // enable js validating
			$admin->check_element = array(
											"pid"        => $evoLANG['movetocat']
										 );
			// element condition
			/* ------------------------------------------------ */
			//$this->parent_name = '-------';

			$parent = $this->makeselectbox("pid",$_GET['fid'],$id);
			$html .= $admin->form_start("",$_SERVER['PHP_SELF']."?do=deletecat&amp;cid=".$id."&amp;confirm=1");

			$table .= $admin->add_spacer($evoLANG['delcat']);
			$table .= $admin->add_row($evoLANG['movetocat'],$parent,$evoLANG['d_movetocat']);
			$table .= $admin->form_submit("deletecat");
			$html .= $admin->add_table($table,"90%");
		
			return $html;
		}
		else
		{
			if ( $_POST['deletecat'])
			{
				// just making sure
				
				$sql = $udb->query("SELECT cid,pid FROM ".$database['article_cat']." WHERE pid='".$_GET[cid]."'");
				while ($row = $udb->fetch_array($sql) )
				{
					//echo $row['cid'];
						$udb->query("UPDATE ".$database['article_cat']." SET pid = '".$_POST['pid']."' WHERE pid='$_GET[cid]'");
						$udb->query("UPDATE ".$database['article_article']." SET pid = '$row[cid]' WHERE pid='$_GET[cid]'");

				}
			}
			$udb->query("DELETE FROM $database[article_cat] WHERE cid = '".$_GET['cid']."'");
			$this->redirect($_SERVER['PHP_SELF']."?do=managecat");
		}
			
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
					ADMIN: LIST CATEGORY
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function list_category($pid='0')
	{
		global $database,$udb,$admin,$tpl,$evoLANG,$settings,$exp,$ad_settings,$_SERVER,$_GET,$_COOKIE;
		$_SERVER[PHP_SELF] = $_SERVER['PHP_SELF'];
		
		$query = $udb->query("SELECT * FROM $database[article_cat] WHERE pid='$pid' ORDER BY cid ASC");
		$settings['allowsub'] = 1;
		
		if ($pid != 0) $b = "<ul>\n"; 
		
		while( $row = $udb->fetch_array($query) )
		{
			if ( $settings[allowsub] == 1 )
			{
					if (!$_GET[exp] and $row[pid]==0 && $_COOKIE['expand'] != "1")
					{
						$expand = "<a href=\"$_SERVER[PHP_SELF]?do=managecat&amp;exp=$row[cid]\"><img src=\"$settings[imgfolder]/plus.gif\" alt=\"\" />";
					}
					elseif ($_COOKIE['expand'] == "1" || ($_GET[exp] != '' and $_GET[exp]==$row[cid]) )
					{
						$expand = "<a href=\"$_SERVER[PHP_SELF]?do=managecat\"><img src=\"$settings[imgfolder]/minus.gif\" alt=\"\" />";
					}
					else
					{
						$expand = "<a href=\"$_SERVER[PHP_SELF]?do=managecat&amp;exp=$row[cid]\"><img src=\"$settings[imgfolder]/plus.gif\" alt=\"\" />";
					}
				}

				if ( $row['pid'] == "0" )
				{
					$row['name'] = $expand."<span style=\"font-size:12px\"> $row[name]</span></a>";
				}
				
				$addsub = ($settings[allowsub]==1) ? "[<a href=\"$_SERVER[PHP_SELF]?do=addcat&amp;fid=$row[cid]\">Add Sub</a>]":'';
				$b .= "<li> <a href=\"$_SERVER[PHP_SELF]?do=listfiles&amp;pid=$row[cid]\" title=\"$row[description]\"> <b>$row[name]</b></a> [<a href=\"$_SERVER[PHP_SELF]?do=make_edit&amp;cid=$row[cid]\">Edit</a>] [".$this->wrap_confirm("delete","$_SERVER[PHP_SELF]?do=deletecat&amp;cid=$row[cid]")."] $addsub </li>\n";
				
				
				if($_GET['exp'] == "all")
				{
					$admin->makecookie("expand",1);
					$this->redirect($_SERVER['PHP_SELF']."?do=managecat");
					exit;
				}
				
				if($_GET['ctrt'] == "1")
				{
					$admin->clearcookie("expand");
					$this->redirect($_SERVER['PHP_SELF']."?do=managecat");
					exit;
				}

				$_GET['exp'] = $_COOKIE['expand'] == 1 ? "all":$_GET['exp'];


				if ($_GET['exp'] !='' && $_GET['exp'] == $row['cid'] && $settings['allowsub']==1 || $_GET['exp'] == "all" && $settings['allowsub']==1 )
				{
					$_GET['exp'] = $_GET['exp'] == "all" ? $row['cid']:$_GET['exp'];
					$b .= $this->list_subcat($_GET['exp']);
				}
		}

		$udb->free_result($query);
		
		if ($pid != 0) $b .= "</ul>\n";
		return $b;
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
					ADMIN: LIST SUBCAT
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	
	function list_subcat($pid='')
	{
		global $settings,$udb,$admin,$tpl,$evoLANG,$settings,$exp,$database,$_SERVER;

		$_SERVER[PHP_SELF] = $_SERVER[PHP_SELF];
		$settings['allowsub'] = 1;
		
		if ($pid != '' && $pid != 0)
		{
			$query = $udb->query("SELECT * FROM $database[article_cat] WHERE pid='$pid' ORDER BY cid ASC");
		
			if ($pid != 0)  $b = "<ul>\n"; 
		
			while($row = $udb->fetch_array($query))
			{
				$row = $admin->strip_array($row);
							
				$addsub = ($settings[allowsub]==1) ? "[<a href=\"$_SERVER[PHP_SELF]?do=addcat&amp;fid=$row[cid]\">Add Sub</a>]":'';

				$b .= "<li> <a href=\"$_SERVER[PHP_SELF]?do=listfiles&amp;pid=$row[cid]\" title=\"$row[description]\"> <b>$row[name]</b></a> [<a href=\"$_SERVER[PHP_SELF]?do=make_edit&amp;cid=$row[cid]\">Edit</a>] [".$this->wrap_confirm("delete","$_SERVER[PHP_SELF]?do=deletecat&amp;cid=$row[cid]")."] $addsub </li>\n";
				
				$b .= $this->list_subcat($row[cid]);
			}
		}

		$udb->free_result($query);
		
		if ($pid != 0) $b .= "</ul>\n";
		return $b;
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
					ADMIN: LIST CAT
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function listcat($expid)
	{
		global $database,$udb,$admin,$tpl,$evoLANG,$_SERVER,$expand;
		$_SERVER[PHP_SELF] = $_SERVER[PHP_SELF];

		$q1 = $udb->query("select * from $database[article_cat]");
		$udb->free_result($q1);


		$q2 = $udb->query("select * from $database[article_article]");
		$udb->free_result($q2);
		
		$content .= $admin->makelink($evoLANG['expandall'],$_SERVER['PHP_SELF']."?do=managecat&amp;exp=all") ." | ";
		$content .= $admin->makelink($evoLANG['contractall'],$_SERVER['PHP_SELF']."?do=managecat&amp;ctrt=1") ." <br />";
		$content .= "<ul>".$this->list_category()."</ul>";
		return $content;
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
					ADMIN: EDIT CATEGORY
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function edit_cat($cid)
	{
		global $tpl,$_GET,$admin,$_SERVER,$evoLANG,$database,$udb,$settings;
		
		$this->formname = 'form_editcat';
		$admin->row_align = "left";
		$admin->row_width = "30%";
		/* ------------------------------------------------ */
		$admin->do_check = 1; // enable js validating
		$admin->check_element = array(
										"name"        => $evoLANG['catname'],
										"description" => $evoLANG['description']
									 );
		// element condition
		/* ------------------------------------------------ */

		$row = $udb->query_once("SELECT * FROM $database[article_cat] WHERE cid='".$cid."'");
		$parent = $this->makeselectbox("pid",$row['pid']);
		$customfields = $this->cat_make_fields($row);
		
		$html .= $admin->form_start($this->formname,$_SERVER['PHP_SELF']."?do=submit").$admin->form_hidden("id",$cid);

		$table .= $admin->add_spacer($evoLANG['editcat']);
		$table .= $admin->add_row($evoLANG['parent'],$parent);
		$table .= $admin->add_row($evoLANG['catname'],$admin->form_input("name",$row['name']) );
		$table .= $admin->add_row($evoLANG['description'],$admin->form_textarea("description",$row['description']) );
		$table .= $admin->add_row($evoLANG['cat_image'],$admin->form_input("cat_image",$row['cat_image']),$evoLANG['d_cat_image'] );
		
		if ($settings['usecomment'] == 1)
		{
			$table .= $admin->add_row($evoLANG['usecomment'],$admin->form_radio_yesno("usecomment",$row['usecomment']),$evoLANG['d_usecomment_cat']);
		}
		else
		{
			$nocomment = $admin->form_hidden("usecomment",0);
		}

		$table .= $admin->add_row($evoLANG['assignstyle'],$nocomment.$this->makestyleselect("style",str_replace("-1","",$row['style']) ) );
		
		
		
		if ($settings['allowmeta_cat'] == 1)
		{
			$table .= $admin->add_spacer($evoLANG['custommeta']);
			$table .= $admin->add_row( $evoLANG['meta_key'],$admin->form_input("meta_key",$row['meta_key']) );
			$table .= $admin->add_row( $evoLANG['meta_desc'],$admin->form_textarea("meta_desc",$row['meta_desc']) );
		}
		
		$table .= $customfields;

		$table .= $admin->form_submit("editcat");		
		$html .= $admin->add_table($table,"90%");
		
		return $html;
	}

	function sql_editcat()
	{
		global $udb,$settings,$tpl,$_POST,$database,$admin,$_SERVER;

		$_POST = $admin->slash_array($_POST);
		$style = $_POST['style'] == "-1" ? "":$_POST['style'];
		
		$sql = $udb->query("SELECT * FROM ".$database['article_catfield']);
		while ($row = $udb->fetch_array($sql) )
		{
			$additional .= " custom_".$row['fieldid']." = '".$_POST['custom_'.$row['fieldid']]."',";
		}

		$udb->query("UPDATE $database[article_cat] SET ".$additional."cat_image='".$_POST['cat_image']."', pid='".$_POST['pid']."',name='".$_POST[name]."',description='".$_POST[description]."',style='".$style."',meta_key='".$_POST['meta_key']."',meta_desc='".$_POST['meta_desc']."',usecomment='".$_POST['usecomment']."' WHERE cid='".$_POST[id]."'");
		$this->redirect($_SERVER['PHP_SELF']."?do=managecat");
	}

	/*--------------------------------------------------------------------------------------------------*/
	// Articles ADD/DELETE/VALIDATE/EDIT section
	/*--------------------------------------------------------------------------------------------------*/
	
	//id
	//pid
	//author
	//subject
	//summary
	//article
	//views
	//numvotes
	//totalvotes
	//totalcomments
	//uniqueid
	//validated
	//featured
	
	//related links
	//support files
	//images

	//-- custom --
	// source

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
							DELETE ARTICLE
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function delete_article($id)
	{
		global $tpl,$settings,$evoLANG,$admin,$udb,$userinfo,$root,$_SERVER,$database;
		$row = $this->get_article($id);
		
		$imgsql = $udb->query("SELECT * FROM $database[article_image] WHERE aid='".$row['uniqid']."'");
		while ($imgrow = $udb->fetch_array($imgsql) )
		{
			@unlink($root.$imgrow['loc']);
		}

		$udb->query("DELETE FROM $database[article_image] WHERE aid='".$row['uniqid']."'");
		
		$suprow = $udb->query_once("SELECT * FROM $database[article_support] WHERE aid='$id'");
		@unlink($suprow['loc']);

		$udb->query("DELETE FROM $database[article_support] WHERE aid='".$id."'");
		$udb->query("DELETE FROM $database[article_article] WHERE id='$id'");
		$udb->query("DELETE FROM $database[article_comment] WHERE artid='$id'");
		$this->delete_cache($_POST['id']);

		$this->redirect($_SERVER['PHP_SELF']."?do=manageart");
		
		//delete support
		//delete images
		//delete article
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
							EDIT ARTICLE
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function delete_image($id)
	{
		global $_POST,$udb,$database,$admin,$root;
		$row = $udb->query_once("SELECT loc,align FROM $database[article_image] WHERE id='$id'");
		@unlink($root.$row['loc']);
		$artrow = $this->get_article($_GET['aid']);
		$artrow = $admin->strip_array($artrow);

		$artrow['article'] = str_replace("{attached:".$row['loc']."::".$row['align']."}","",$artrow['article']);
		$udb->query("UPDATE $database[article_article] SET article='".$admin->slash($artrow['article'])."' WHERE id='".$_GET['aid']."'");

		$udb->query("DELETE FROM $database[article_image] WHERE id='".$id."'");
		$this->redirect($_SERVER['PHP_SELF']."?do=editart&amp;id=$_GET[aid]");
	}

	function edit_article($id)
	{
		global $tpl,$settings,$evoLANG,$admin,$udb,$userinfo,$root,$_SERVER,$database,$usr;
		$admin->row_align="left";

		if ($id == "") return $evoLANG['xid'];
		$artrow = $this->get_article($id);
		if (!is_array($artrow)) return $evoLANG['xid'];
		
		if ( $usr->checkperm('',"editall",1) == false && $usr->checkperm('',"editown",1) == true )
		{
			if ($artrow['author'] != $userinfo['id']) return $evoLANG['noperm'];
		}

		$art['formname'] = "editart";

		$this->formname = $art['formname'];
		$custom_fields = $this->make_fields($artrow);
		
		if ($settings['allowmeta_art'] == 1)
		{
			$custom_meta .= $admin->add_spacer($evoLANG['custommeta']);
			$custom_meta .= $admin->add_row( $evoLANG['meta_key'],$admin->form_input("meta_key",$artrow['meta_key']) );
			$custom_meta .= $admin->add_row( $evoLANG['meta_desc'],$admin->form_textarea("meta_desc",$artrow['meta_desc']) );
		}

		//$art['article'] = "<textarea name=\"article\" style=\"width:100%;height:300px\">".$artrow['article']."</textarea>";
		//$settings['usewysiwyg'] = 0; //force disabled
		
		$artrow['article'] = $settings['usewysiwyg'] == 1 ?$artrow['article'] : htmlspecialchars($artrow['article']);

		$art['article'] = $settings['usewysiwyg'] == 1 ? $this->wysiwyg_load($artrow['article']):"<textarea name=\"article\" style=\"width:100%;height:300px\">$artrow[article]</textarea>";

		$art['uniqid'] = $artrow['uniqid'];
		$art['views'] = $admin->form_input("views",$artrow['views']);
		$art['status'] = $admin->form_select("validated","0|".$evoLANG['pending'].",1|".$evoLANG['live'],$artrow['validated'],"");
		$art['date'] = $this->dateselect($artrow['date']);
		
		if ($settings['usecomment'] == 1)
		{
			$art['usecomment'] = $admin->add_row($evoLANG['usecomment'],$admin->form_radio_yesno("usecomment",$artrow['usecomment']),$evoLANG['d_usecomment_art']);
		}
		else
		{
			$hidden_art['usecomment'] = $admin->form_hidden("usecomment",0);
		}
	

		$art['userating'] = $admin->add_row($evoLANG['userating'],$hidden_art['usecomment'].$admin->form_radio_yesno("userating",$artrow['userating']));
		
		
		$imgsql = $udb->query("SELECT * FROM $database[article_image] WHERE aid='".$art['uniqid']."'");
		while ($imgrow = $udb->fetch_array($imgsql) )
		{
			$uploaded_images .= "<div style=\"border:1px solid black;padding:4px;width:100%;border-collapse:collapse\" align=\"left\" class=\"firstalt\"><b> $evoLANG[image] </b> : $imgrow[loc] <br /> <b>$evoLANG[imageplaceholder]</b> : {attached:$imgrow[loc]::$imgrow[align]}<br /><div align=\"right\"> ".$this->wrap_confirm("delete","$_SERVER[PHP_SELF]?do=deleteimage&amp;id=$imgrow[id]&amp;aid=$id")."</div></div>";
		}
		
		$uploaded_images = $uploaded_images != "" ? $uploaded_images:$evoLANG['noimgattached'];

		$suprow = $udb->query_once("SELECT * FROM $database[article_support] WHERE aid='$id'");
		$suprow['loc'] = $suprow['loc'] == "" ? $evoLANG['none'].$admin->form_hidden("nosupport","1"):$suprow['loc'];
		$supportfile = "<b>".$evoLANG['supportfile']."</b>: ".$suprow['loc'];
		

		$admin->buttons_add = array(
										array(
												"name" => "PageBreak",
												"js"   => "addBreak();",
												"desc" => "{LANG:d_pagebreak}"
											 )
										,
										array(
												"name" => "{LANG:attachimage}",
												"js"   => 	"window.open(\\\"".$_SERVER['PHP_SELF']."?do=addimage&amp;for=".$art['formname'].".article&amp;id=".$art['uniqid']."\\\",\\\"1\\\",\\\"width=600,height=300,toolbar=no,statusbar=yes,scrollbars=yes\\\")",
												"desc" => "{LANG:d_attachimage}"
											 )

									);

		if ($settings['usewysiwyg'] != 1)
		{
			$check_article = "if (document.addart.article.value == \"\" )
								{
									alert(\"Invalid Value: $evoLANG[article]\");
									return false;
								}";
			$tpl->layout['head'] = $admin->load_buttons($art['formname'],'article');
			$art['buttons'] = $admin->get_buttons();
		}
		else
		{
			$art['buttons'] = $this->wysiwyg_buttons($art['formname'],$art['uniqid']);
		}
	
		$tpl->layout['bodyadditional'] = "";
		$art['author'] = $this->get_user($artrow['author'],"username");

		$this->parent_name = $evoLANG['selectcat'];
		$art['category'] = $this->makeselectbox("pid",$artrow['pid']);		
		$art['featured'] = $admin->form_radio_yesno("featured",$artrow['featured']);
		$art['autobr'] = $admin->form_radio_yesno("autobr",$artrow['autobr']);

		$art['assignstyle'] = $this->makestyleselect("style",str_replace("-1","",$artrow['style']) ) ;

		if (isset($admin->additional_validation))
		{
			$more_validation = $admin->additional_validation;
		}

		if ($settings['art_useimage'] == '1')
		{
			$art_img = $artrow['artimg'] != '' ? $admin->makelink($root.$artrow['artimg'],$root.$artrow['artimg']).$admin->form_hidden("old_img",$artrow['artimg'])."<br />":'';
			
			$art['articleimage'] = $admin->add_row($evoLANG['artimg'],$art_img.$admin->form_input("artimg","","file"),$this->get_artimginfo() );
		}

		if ($settings['use_relatedart'] == '1')
		{
			$art['relatedart'] .= $admin->add_spacer($evoLANG['relatedart']);
			$sql_rel = $udb->query("SELECT id,subject FROM $database[article_article] WHERE id != $id");
			
			while ($relrow = $udb->fetch_array($sql_rel) )
			{
				$rel_opt .= $relrow['id']."|".stripslashes($relrow['subject']).",";
			}
			$udb->free_result($sql_rel);
			
			$admin->form_select_array = 1;
			$rel_select = $admin->form_select("related[]",$rel_opt,$artrow['related'],'','multiple size="10"');
			$art['relatedart'] .= $admin->add_row($evoLANG['relatedart'],$rel_select,$evoLANG['multiselect']);
		}
		
		eval("\$content .= \"".$tpl->gettemplate("editarticle",0,$this->def_folder)."\";");

		return $content;
	}

	function process_editarticle()
	{
		global $_POST,$settings,$admin,$udb,$tpl,$_GET,$_SERVER,$_FILES,$database;
		
		if ($this->process_artimg() == false)
		{
			return $this->error;
		}

		$_POST = $admin->slash_array($_POST);
		
		$sql = $udb->query("SELECT * FROM ".$database['article_field']);
		while ($row = $udb->fetch_array($sql) )
		{
			$csql .= " custom_".$row['fieldid']." = '".$_POST['custom_'.$row['fieldid']]."',";
		}

		$time = mktime (0,0,0,$_POST['date']['month'],$_POST['date']['day'],$_POST['date']['year']); 
		//$csql = substr($csql,0,-1); // not needed;
		
		if ($settings['use_relatedart'] == '1')
		{
			if (is_array($_POST['related']))
			{
				foreach ($_POST['related'] as $rel)
				{
					$related .= $rel;
					$related .= $rel != end($_POST['related']) ? ",":"";
				}
			}
		}

		$udb->query("UPDATE $database[article_article]
							SET
								$csql
								uniqid = '".$_POST['uniqueid']."',
								pid = '".$_POST['pid']."',
								subject = '".$_POST['subject']."',
								summary = '".$_POST['summary']."',
								featured = '".$_POST['featured']."',
								autobr = '".$_POST['autobr']."',
								style = '".str_replace("-1","",$_POST['style'])."',
								article = '".$_POST['article']."',
								views = '".$_POST['views']."',
								validated = '".$_POST['validated']."',
								date = '".$time."',
								meta_key = '".$_POST['meta_key']."',
								meta_desc = '".$_POST['meta_desc']."',
								usecomment = '".$_POST['usecomment']."',
								userating = '".$_POST['userating']."',
								artimg = '".$this->artimg."',
								related = '".$related."'
									
									WHERE
										id = '".$_POST['id']."'
					");
								
		//echo 'bleh';
		//exit;
		$this->process_support($_POST['id']);
		$this->delete_cache($_POST['id']);
		
		$this->redirect($_SERVER['PHP_SELF']."?do=manageart");
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
							ADD ARTICLE
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function wysiwyg_load($value='')
	{
		global $settings,$root,$tpl;
		
		$thefolder = MISC_FOLDER."wysiwyg/";
		
		/*if ($settings['inadmin'] == 1)
		{
			$tpl->layout['head'] = "
			<script language=\"javascript\" src=\"".$thefolder."a_fckeditor.js\"></script>
			";
		}
		else
		{
			$tpl->layout['head'] = "
			<script language=\"javascript\" src=\"".$thefolder."fckeditor.js\"></script>
			";
		}*/

		include $thefolder. 'fckeditor.php';
		$oFCKeditor = new FCKeditor ;
		$oFCKeditor->Value = $this->check_jscode($value);
		$oFCKeditor->FCKeditorBasePath = $thefolder;
		ob_start();
		$oFCKeditor->CreateFCKeditor( 'article', '100%', 400 ) ;
		$wysiwyg = ob_get_contents();
		ob_end_clean();
		return $wysiwyg;
		
		/*return "
		<script language=\"javascript\">
		<!--
		var oFCKeditor ;
		oFCKeditor = new FCKeditor('article') ;
		oFCKeditor.Value = '".$this->check_jscode($value)."';
		
		oFCKeditor.Create() ;
		//-->
			</script>";*/
	}

	function wysiwyg_get()
	{

	}
	
	function wysiwyg_buttons($for,$uniqid)
	{
		global $evoLANG,$_SERVER;
		$thefolder = MISC_FOLDER."wysiwyg/";

		$js = "	<script language=\"javascript\">
					
					function popImage()
					{
						var html = window.open(\"".$_SERVER['PHP_SELF']."?do=addimage&amp;for=".$for.".article&amp;id=".$uniqid."\",\"1\",\"width=600,height=300,toolbar=no,statusbar=yes,scrollbars=yes\");
					}
				</script>
				";
		return $js."<input type=\"button\" value=\"$evoLANG[attachimage]\" onclick=\"popImage()\" />";
	}

	function addarticle($defval='')
	{
		global $tpl,$settings,$evoLANG,$admin,$udb,$userinfo,$root,$_SERVER,$database;
		
		$admin->row_align = "left";
		if ($defval != "")
		{
			$settings['usewysiwyg'] = 0;
		}

		$art['article'] = $settings['usewysiwyg'] == 1 ? $this->wysiwyg_load():"<textarea name=\"article\" style=\"width:100%;height:300px\">$defval</textarea>";
		
		$art['formname'] = "addart";

		$this->formname = $art['formname'];
		$custom_fields = $this->make_fields();


		$art['uniqid'] = $admin->randomizer();
		$art['date'] = $this->dateselect();

		$admin->buttons_add = array(
										array(
												"name" => "PageBreak",
												"js"   => "addBreak();",
												"desc" => "{LANG:d_pagebreak}"
											 )
										,
										array(
												"name" => "{LANG:attachimage}",
												"js"   => 	"window.open(\\\"".$_SERVER['PHP_SELF']."?do=addimage&amp;for=".$art['formname'].".article&amp;id=".$art['uniqid']."\\\",\\\"1\\\",\\\"width=600,height=300,toolbar=no,statusbar=yes,scrollbars=yes\\\")",
												"desc" => "{LANG:d_attachimage}"
											 )

									);

		
		
		if ($settings['usewysiwyg'] != 1)
		{
			$check_article = "if (document.addart.article.value == \"\" )
								{
									alert(\"Invalid Value: $evoLANG[article]\");
									return false;
								}";
			$tpl->layout['head'] = $admin->load_buttons($art['formname'],'article');
			$art['buttons'] = $admin->get_buttons();
		}
		else
		{
			$art['buttons'] = $this->wysiwyg_buttons($art['formname'],$art['uniqid']);
		}

		if ($settings['allowmeta_art'] == 1)
		{
			$custom_meta .= $admin->add_spacer($evoLANG['custommeta']);
			$custom_meta .= $admin->add_row( $evoLANG['meta_key'],$admin->form_input("meta_key",$art['meta_key']) );
			$custom_meta .= $admin->add_row( $evoLANG['meta_desc'],$admin->form_textarea("meta_desc",$art['meta_desc']) );
		}
		
		$tpl->layout['bodyadditional'] = "";
		$art['author'] = $userinfo['username'].$admin->form_hidden("author",$userinfo['id']);
		//$art['author'] = $this->select_author('author',$userinfo['id']);
		$this->parent_name = $evoLANG['selectcat'];
		$art['category'] = $this->makeselectbox("pid");		
		$art['featured'] = $admin->form_radio_yesno("featured");
		$art['autobr'] = $admin->form_radio_yesno("autobr",$settings['usewysiwyg'] == 1 ? 0:1);
		$art['assignstyle'] = $this->makestyleselect("style",$artrow['style'] ) ;

		if ($settings['usecomment'] == 1)
		{
			$art['usecomment'] = $admin->add_row($evoLANG['usecomment'],$admin->form_radio_yesno("usecomment",1),$evoLANG['d_usecomment_art']);
		}
		else
		{
			$hidden_art['usecomment'] = $admin->form_hidden("usecomment",0);
		}

		$art['userating'] = $admin->add_row($evoLANG['userating'],$hidden_art['usecomment'].$admin->form_radio_yesno("userating",1));
		

		if (isset($admin->additional_validation))
		{
			$more_validation = $admin->additional_validation;
		}

		$more_validation .= $settings['usewysiwyg'] == 1 ? $this->wysiwyg_get():"";

		if ($settings['art_useimage'] == '1')
		{
			$art['articleimage'] = $admin->add_row($evoLANG['artimg'],$admin->form_input("artimg","","file"),$this->get_artimginfo() );
		}

		if ($settings['use_relatedart'] == '1')
		{
			$art['relatedart'] .= $admin->add_spacer($evoLANG['relatedart']);
			$sql_rel = $udb->query("SELECT id,subject FROM $database[article_article]");
			
			while ($relrow = $udb->fetch_array($sql_rel) )
			{
				$rel_opt .= $relrow['id']."|".stripslashes($relrow['subject']).",";
			}
			$udb->free_result($sql_rel);
			
			$admin->form_select_array = 1;
			$rel_select = $admin->form_select("related[]",$rel_opt,$artrow['related'],'','multiple size="10"');
			$art['relatedart'] .= $admin->add_row($evoLANG['relatedart'],$rel_select,$evoLANG['multiselect']);
		}
		
		eval("\$content .= \"".$tpl->gettemplate("addarticle",0,$this->def_folder)."\";");

		return $content;
	}
	
	function get_artimginfo()
	{
		global $evoLANG,$settings,$admin;
		
		$stifler .= $evoLANG['alloweddimension']." : ".$settings['img_maxdimension']."<br />";
		$stifler .= $evoLANG['allowedfiletype']." : <i>".$settings['img_allowedmime']."</i> <br />";
		$stifler .= $evoLANG['maxsize']." : <i>".$admin->file_size($settings['img_maxsize'])."</i>";
		
		return $stifler;
	}

	function do_addimage()
	{
		global $_POST,$_FILES,$_GET,$_SERVER;
		global $admin,$udb,$database,$tpl,$settings,$userinfo,$evoLANG;
		
		$site['title'] = $evoLANG['attachimage'];

		/* --- 
		 the process is like this:
		 1) upload image,reload this function, then add a value on <textarea>
		 2) [image:upload/articles/time()_image.ext::left]
		 3) pandai2 la... wakakakakakaka

		--- */

		$wysiwyg = $settings['usewysiwyg'] == 1 ? "1":"0";
		

		if ($_POST['addimage'])
		{
			// ok, mula2 make sure directory ade dulu, karang tak pasal
			@mkdir($this->upload_dir,0777);

			//get the stupid file extension :D
			if ($_FILES['image']['size'] > 0)
			{
				// valid extension 
				$valid_extension = array("jpg","gif","png","JPG","GIF","PNG","jpeg");				
				$ext = $admin->get_ext($_FILES['image']['name']);
				
				//check if extension is valid
				if (in_array($ext,$valid_extension) == FALSE)
				{
					$info = $admin->add_spacer($evoLANG['invalidtype']);
									
					eval("\$content .= \"".$tpl->gettemplate("addimage",0,$this->def_folder)."\";");
					eval("echo(\"".$tpl->gettemplate("popup",0,$this->def_folder)."\");");
					exit;
				}


				@copy($_FILES['image']['tmp_name'], $this->upload_dir.time()."_".$_FILES['image']['name']);	
				$loc = $admin->remove_root($this->upload_dir).time()."_".$_FILES['image']['name'];
				//now try to add the thing to the <textarea> like so [image:upload/articles/time()_image.ext::left]
				
				// ready : [image:$loc::$_POST[align]]
				$admin->row_align = "left";

				$imagename = basename($loc);
				$info .= $admin->add_spacer($evoLANG['imgattached']);
				$info .= $admin->add_row($evoLANG['imgattached'],$imagename);
				$info .= $admin->add_row($evoLANG['note'],$evoLANG['note_placeholder']."<br /><b>".$evoLANG['imageplaceholder']."</b><br /><input style=\"width:100%\" onFocus=\"alert('".$evoLANG['copyplaceholder']."');\" type=\"text\" value=\"{attached:$loc::$_POST[align]}\" name=\"\" />");
				
				$_POST = $admin->slash_array($_POST);
				
				$udb->query("INSERT INTO $database[article_image] SET align='".$_POST['align']."',description='".$_POST['desc']."',aid='$_POST[id]',loc='$loc'");
				
				//
				$info .= $admin->add_spacer("<input type=\"button\" onClick=\"insert('{attached:$loc::$_POST[align]}\\n');window.close();\" value=\"Continue\" style=\"font-weight:bold\" />");			

			}
		}
		else
		{
			$align_array = array( "left"   => $evoLANG['left'],
								  "middle" => $evoLANG['center'],
								  "right"  => $evoLANG['right'],
								  "top"    => $evoLANG['top'],
								  "bottom" => $evoLANG['bottom']
								
								);

			foreach ($align_array as $alignment => $text)
			{
				$align_loop .= $alignment."|".$text.",";
			}

			$aligner = $admin->form_select("align",$align_loop,"","");
			
			$content .= $evoLANG['autothumb_note'];
			$form = $admin->form_start("",$_SERVER['PHP_SELF']."?do=addimage&amp;for=$_GET[for]",1);
			$info .= $admin->add_spacer($evoLANG['attachimage']);
			$info .= $admin->add_row($evoLANG['imgalign'],$aligner);
			$info .= $admin->add_row($evoLANG['upload'],$admin->form_input("image","","file"));
			//$info .= $admin->add_row($evoLANG['shortdesc'],$admin->form_textarea("desc","","40|3"),$evoLANG['d_shortdesc']);
			$info .= $admin->form_hidden("id",$_GET['id']);
			$info .= $admin->form_submit("addimage");
		}

		eval("\$content .= \"".$tpl->gettemplate("addimage",0,$this->def_folder)."\";");
		eval("echo(\"".$tpl->gettemplate("popup",0,$this->def_folder)."\");");
		exit;
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
						MANAGE CUSTOM FIELDS
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function fields_main()
	{
		global $admin,$_SERVER,$udb,$database,$evoLANG;

		$html .= $admin->link_button($evoLANG['field_addnew'],$_SERVER['PHP_SELF']."?do=addfield")          .  "<br />";
		$html .= "<br />";
		
		$sql = $udb->query("SELECT * FROM ".$database['article_field']);
		
		$admin->row_width = "60%";
		$a = $admin->add_spacer($evoLANG['field_custom_art']); 

		while ( $row = $udb->fetch_array($sql) )
		{
			$row = $admin->strip_array($row);
			$a .= $admin->add_row($row['name'],"[ ".$admin->makelink($evoLANG['word_edit'],$_SERVER['PHP_SELF']."?do=editfield&amp;id=$row[fieldid]")."]  [".$this->wrap_confirm($evoLANG['word_delete'],$_SERVER['PHP_SELF']."?do=deletefield&amp;id=".$row['fieldid'])."]",$row['description']);
		}
		
		$html .= $admin->add_table($a,"90%");

		return $html;
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
						MANAGE CATEGORY CUSTOM FIELDS
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function cat_fields_main()
	{
		global $admin,$_SERVER,$udb,$database,$evoLANG;

		$html .= $admin->link_button($evoLANG['field_addnew'],$_SERVER['PHP_SELF']."?do=cat_addfield")          .  "<br />";
		$html .= "<br />";
		
		$sql = $udb->query("SELECT * FROM ".$database['article_catfield']);
		
		$admin->row_width = "60%";
		$a = $admin->add_spacer($evoLANG['field_custom_cat']); 

		while ( $row = $udb->fetch_array($sql) )
		{
			$row = $admin->strip_array($row);
			$a .= $admin->add_row($row['name'],"[ ".$admin->makelink($evoLANG['word_edit'],$_SERVER['PHP_SELF']."?do=cat_editfield&amp;id=$row[fieldid]")."]  [".$this->wrap_confirm($evoLANG['word_delete'],$_SERVER['PHP_SELF']."?do=deletecatfield&amp;id=".$row['fieldid'])."]",$row['description']);
		}
		
		$html .= $admin->add_table($a,"90%");

		return $html;
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
						ADD NEW CUSTOM FIELD
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function addfield()
	{
		global $udb,$_SERVER,$admin,$evoLANG;
		
		$admin->row_align = "left";
		$admin->row_width = "30%";
		/* ------------------------------------------------ */
		$admin->do_check = 1; // enable js validating
		$admin->check_element = array(
										"name"        => "Name"
									 );
		// element condition
		/* ------------------------------------------------ */

		$html  .= $admin->form_start("asg",$_SERVER['PHP_SELF']."?do=submit");
		$table .= $admin->add_spacer($evoLANG['field_addnew']);
		
		$opt = "text|".$evoLANG['textfield'].",textarea|".$evoLANG['textarea'].",yesno|".$evoLANG['radiobtn_yesno'];
		$table .= $admin->add_row($evoLANG['type'],$admin->form_select("type",$opt,"",""));
		
		$table .= $admin->add_row($evoLANG['name'],$admin->form_input("name"));
		$table .= $admin->add_row($evoLANG['description'],$admin->form_textarea("description"));
		$table .= $admin->add_row($evoLANG['required'],$admin->form_select_yesno("required"));
		$table .= $admin->add_row($evoLANG['order'],$admin->form_input("order","0"));


		$table .= $admin->form_submit("addfield");
		$html  .= $admin->add_table($table,"80%");

		return $html;
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
						ADD NEW CUSTOM CAT FIELD
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function cat_addfield()
	{
		global $udb,$_SERVER,$admin,$evoLANG;
		
		$admin->row_align = "left";
		$admin->row_width = "30%";
		/* ------------------------------------------------ */
		$admin->do_check = 1; // enable js validating
		$admin->check_element = array(
										"name"        => "Name"
									 );
		// element condition
		/* ------------------------------------------------ */

		$html  .= $admin->form_start("asg",$_SERVER['PHP_SELF']."?do=submit");
		$table .= $admin->add_spacer($evoLANG['field_addnew']);
		
		$opt = "text|".$evoLANG['textfield'].",textarea|".$evoLANG['textarea'].",yesno|".$evoLANG['radiobtn_yesno'];
		$table .= $admin->add_row($evoLANG['type'],$admin->form_select("type",$opt,"",""));
		
		$table .= $admin->add_row($evoLANG['name'],$admin->form_input("name"));
		$table .= $admin->add_row($evoLANG['description'],$admin->form_textarea("description"));
		$table .= $admin->add_row($evoLANG['required'],$admin->form_select_yesno("required"));
		$table .= $admin->add_row($evoLANG['order'],$admin->form_input("order","0"));


		$table .= $admin->form_submit("cat_addfield");
		$html  .= $admin->add_table($table,"80%");

		return $html;
	}
	
	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
					PROCESS ADD ART FIELD
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function process_addfield()
	{
		global $_POST,$udb,$admin,$database,$evoLANG;
		
		$_POST = $admin->slash_array($_POST);

		$udb->insert_into = $database[article_field];
		$udb->insert_data = array (
										"name"        => $_POST['name'],
										"description" => $_POST['description'],
										"orders"      => $_POST['order'],
										"required"    => $_POST['required'],
										"type"		  => $_POST['type']
								   );
		$udb->query_insert();

		//cari plak latest id
		$row = $udb->query_once("SELECT * FROM ".$database[article_field]." ORDER BY fieldid DESC LIMIT 1");
		
		switch($_POST['type'])
		{
			case "text":
				$type = "ADD `custom_".$row['fieldid']."` VARCHAR(255) NOT NULL";
			break;
			case "textarea":
				$type = "ADD `custom_".$row['fieldid']."` TEXT NOT NULL";
			break;
			case "yesno":
				$type = "ADD `custom_".$row['fieldid']."` TINYINT(1) NOT NULL";
			break;
		}

		$udb->query('ALTER TABLE '.$database[article_article].' '.$type);

		$this->redirect($_SERVER['PHP_SELF']."?do=managefields");
	}
	
	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
						PROCESS ADD NEW CUSTOM CAT FIELD
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function cat_process_addfield()
	{
		global $_POST,$udb,$admin,$database,$evoLANG;
		
		$_POST = $admin->slash_array($_POST);

		$udb->insert_into = $database[article_catfield];
		$udb->insert_data = array (
										"name"        => $_POST['name'],
										"description" => $_POST['description'],
										"orders"      => $_POST['order'],
										"required"    => $_POST['required'],
										"type"		  => $_POST['type']
								   );
		$udb->query_insert();

		//cari plak latest id
		$row = $udb->query_once("SELECT * FROM ".$database[article_catfield]." ORDER BY fieldid DESC LIMIT 1");
		
		switch($_POST['type'])
		{
			case "text":
				$type = "ADD `custom_".$row['fieldid']."` VARCHAR(255) NOT NULL";
			break;
			case "textarea":
				$type = "ADD `custom_".$row['fieldid']."` TEXT NOT NULL";
			break;
			case "yesno":
				$type = "ADD `custom_".$row['fieldid']."` TINYINT(1) NOT NULL";
			break;
		}

		$udb->query('ALTER TABLE '.$database['article_cat'].' '.$type);

		$this->redirect($_SERVER['PHP_SELF']."?do=managecatfields");
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
						EDIT USER FIELD
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function editfield($id)
	{
		global $udb,$_SERVER,$admin,$database,$evoLANG;
		
		$admin->row_align = "left";
		$admin->row_width = "30%";
		/* ------------------------------------------------ */
		$admin->do_check = 1; // enable js validating
		$admin->check_element = array(
										"name"        => "Name"
									 );
		// element condition
		/* ------------------------------------------------ */
		$row = $udb->query_once("SELECT * FROM ".$database[article_field]." WHERE fieldid='".$id."'");
		$html  .= $admin->form_start("asg",$_SERVER['PHP_SELF']."?do=submit").$admin->form_hidden("id",$row['fieldid']);
		$table .= $admin->add_spacer($evoLANG['field_editcustom_art']." : ".$row['name']);
		
		$opt = "text|".$evoLANG['textfield'].",textarea|".$evoLANG['textarea'].",yesno|".$evoLANG['radiobtn_yesno'];
		$table .= $admin->add_row($evoLANG['type'],$admin->form_select("type",$opt,$row['type'],""));
		
		$table .= $admin->add_row($evoLANG['name'],$admin->form_input("name",$row['name']));
		$table .= $admin->add_row($evoLANG['description'],$admin->form_textarea("description",$row['description']));
		$table .= $admin->add_row($evoLANG['required'],$admin->form_select_yesno("required",$row['required']));
		$table .= $admin->add_row($evoLANG['order'],$admin->form_input("order",$row['orders']));


		$table .= $admin->form_submit("editfield");
		$html  .= $admin->add_table($table,"80%");

		return $html;
	}

	function process_editfield()
	{
		global $_SERVER,$admin,$udb,$database,$evoLANG;

		$_POST = $admin->slash_array($_POST);
		
		$udb->query("UPDATE ".$database[article_field]." SET name='".$_POST['name']."',type='".$_POST['type']."',description='".$_POST['description']."',required='".$_POST['required']."',orders='".$_POST['order']."' WHERE fieldid='".$_POST['id']."'");

		$this->redirect($_SERVER['PHP_SELF']."?do=managefields");
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
						EDIT CUSTOM CAT FIELD
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function cat_editfield($id)
	{
		global $udb,$_SERVER,$admin,$database,$evoLANG;
		
		$admin->row_align = "left";
		$admin->row_width = "30%";
		/* ------------------------------------------------ */
		$admin->do_check = 1; // enable js validating
		$admin->check_element = array(
										"name"        => "Name"
									 );
		// element condition
		/* ------------------------------------------------ */
		$row = $udb->query_once("SELECT * FROM ".$database['article_catfield']." WHERE fieldid='".$id."'");
		$html  .= $admin->form_start("asg",$_SERVER['PHP_SELF']."?do=submit").$admin->form_hidden("id",$row['fieldid']);
		$table .= $admin->add_spacer("Edit Custom Category Field : ".$row['name']);
		
		$opt = "text|".$evoLANG['textfield'].",textarea|".$evoLANG['textarea'].",yesno|".$evoLANG['radiobtn_yesno'];
		$table .= $admin->add_row($evoLANG['type'],$admin->form_select("type",$opt,$row['type'],""));
		
		$table .= $admin->add_row($evoLANG['name'],$admin->form_input("name",$row['name']));
		$table .= $admin->add_row($evoLANG['description'],$admin->form_textarea("description",$row['description']));
		$table .= $admin->add_row($evoLANG['required'],$admin->form_select_yesno("required",$row['required']));
		$table .= $admin->add_row($evoLANG['order'],$admin->form_input("order",$row['orders']));


		$table .= $admin->form_submit("cat_editfield");
		$html  .= $admin->add_table($table,"80%");

		return $html;
	}

	function cat_process_editfield()
	{
		global $_SERVER,$admin,$udb,$database,$evoLANG;

		$_POST = $admin->slash_array($_POST);
		
		$udb->query("UPDATE ".$database[article_catfield]." SET name='".$_POST['name']."',type='".$_POST['type']."',description='".$_POST['description']."',required='".$_POST['required']."',orders='".$_POST['order']."' WHERE fieldid='".$_POST['id']."'");

		$this->redirect($_SERVER['PHP_SELF']."?do=managecatfields");
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
						DELETE ART FIELD
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function deletefield($id)
	{
		global $udb,$database;
		if ($id == "") $this->redirect($_SERVER['PHP_SELF']);

		$udb->query("DELETE FROM ".$database[article_field]." WHERE fieldid='".$id."'");
		$udb->query("ALTER TABLE ".$database['article_article']." DROP `custom_".$id."`");
		$this->redirect($_SERVER['PHP_SELF']."?do=managefields");
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
						DELETE CAT FIELD
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function cat_deletefield($id)
	{
		global $udb,$database;
		if ($id == "") $this->redirect($_SERVER['PHP_SELF']);

		$udb->query("DELETE FROM ".$database['article_catfield']." WHERE fieldid='".$id."'");
		$udb->query("ALTER TABLE ".$database['article_cat']." DROP `custom_".$id."`");
		$this->redirect($_SERVER['PHP_SELF']."?do=managecatfields");
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
						MAKE FIELD ROWS
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function main_show_fields($array='')
	{
		global $admin,$evoLANG,$udb,$database,$tpl,$parser,$settings;
		
		$sql = $udb->query("SELECT * FROM ".$database['article_field']);
		while ( $row = $udb->fetch_array($sql) )
		{
			$custom['value'] = trim( $array['custom_'.$row['fieldid']] ) == "" ? $evoLANG['xavail']: $parser->do_parse($array['custom_'.$row['fieldid']]);
			if ($settings['art_showcustom'] != '' && trim($array['custom_'.$row['fieldid']]) != '')
			{
				eval("\$html .= \"".$tpl->gettemplate("bits_customfields")."\";");
			}
		}

		return $html;
	}


	function make_fields($array='')
	{
		global $admin,$evoLANG,$udb,$database;
		$sql = $udb->query("SELECT * FROM ".$database['article_field']);
		while ( $row = $udb->fetch_array($sql) )
		{
			$array['custom_'.$row['fieldid']] = htmlspecialchars($array['custom_'.$row['fieldid']]);
			if( $row['required'] == 1)
			{
				$admin->additional_validation .= $admin->form_make_element('custom_'.$row['fieldid'],$row['name'],$this->formname);
			}
			$html .= $admin->add_row($row['name'],$this->convert_type($row['type'],$row['fieldid'],$array['custom_'.$row['fieldid']]),$row['description']);
		}

		return $html;
	}
	
	function cat_make_fields($array='')
	{
		global $admin,$evoLANG,$udb,$database;
		$sql = $udb->query("SELECT * FROM ".$database['article_catfield']);
		while ( $row = $udb->fetch_array($sql) )
		{
			$array['custom_'.$row['fieldid']] = htmlspecialchars($array['custom_'.$row['fieldid']]);
			if( $row['required'] == 1)
			{
				$admin->additional_validation .= $admin->form_make_element('custom_'.$row['fieldid'],$row['name'],$this->formname);
			}

			$html .= $admin->add_row($row['name'],$this->convert_type($row['type'],$row['fieldid'],$array['custom_'.$row['fieldid']]),$row['description']);
		}

		return $html;
	}

	function convert_type($type,$varname,$value)
	{
		global $admin;

		$admin->row_align = "left";
		$varname = "custom_".$varname."";
		switch($type)
		{
			case "text":
				$newtype = $admin->form_input($varname,$value);
			break;
			/* ----------------- */
			case "textarea":
				$newtype = $admin->form_textarea($varname,$value);
			break;
			/* ----------------- */
			case "yesno":
				$newtype = $admin->form_radio_yesno($varname,$value);
			break;
		}

		return $newtype;
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
					PROCESS ARTICLE IMG
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function process_artimg($id='')
	{
		global $_FILES,$admin,$tpl,$database,$udb,$_POST,$site,$userinfo,$settings,$evoLANG;
		
		if ($_FILES['artimg'])
		{
			if ($_FILES['artimg']['size'] > 0)
			{	
				@unlink($_POST['old_img']);
				@mkdir($this->upload_dir,0777);
				$ext = $admin->get_ext($_FILES['artimg']['name']);
				
				if ( !in_array($ext,explode(",",$settings['img_allowedmime'])) )
				{
					$this->error = $evoLANG['invalidtype'];
					return false;
				}
				
				$this->img_url = $this->upload_dir.time()."_".$_FILES['artimg']['name'];
				@copy($_FILES['artimg']['tmp_name'], $this->img_url);
				@chmod ($this->img_url, 0666);

				if(filesize($this->img_url) > $settings['img_maxsize'])
				{
					$this->error = $evoLANG['toobig'];
					unlink($this->img_url);
					return false;
				}

				$size = getimagesize($this->img_url);
				
				if ($size[0] > $settings['img_maxdimension'])
				{
					$this->error = $evoLANG['toobig_dimension'];
					unlink($this->img_url);
					return false;
				}

				if ($size[1] > $settings['img_maxdimension'])
				{
					$this->error = $evoLANG['toobig_dimension'];
					unlink($this->img_url);
					return false;
				}

			}
		}
		
		$this->artimg = $admin->remove_root($this->img_url);
		return true;
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
					PROCESS ARTICLE / SUPPORT
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function process_addarticle()
	{
		global $_POST,$settings,$admin,$udb,$tpl,$_GET,$_SERVER,$database,$_FILES,$usr,$cmt;
		
		//print_r($_POST);
		//exit;
		if ($this->process_artimg() == false)
		{
			return $this->error;
		}

		$validated = $usr->checkperm('',"reqvalidation",1) == true ? "0":"1";
		$time = mktime (0,0,0,$_POST['date']['month'],$_POST['date']['day'],$_POST['date']['year']); 
		
		$_POST = $admin->slash_array($_POST);
		
		if ($settings['use_relatedart'] == '1')
		{
			if (is_array($_POST['related']))
			{
				foreach ($_POST['related'] as $rel)
				{
					$related .= $rel;
					$related .= $rel != end($_POST['related']) ? ",":"";
				}
			}
		}

		$udb->insert_into = $database['article_article'];
		$udb->insert_data = array (
										"pid"        => $_POST['pid'],
										"author"     => $_POST['author'],
										"subject"    => $_POST['subject'],
										"summary"    => $_POST['summary'],
										"article"	 => $_POST['article'],
										"uniqid"	 => $_POST['uniqueid'],
										"featured"   => $_POST['featured'],
										"autobr"     => $_POST['autobr'],
										"meta_key"   => $_POST['meta_key'],
										"meta_desc"  => $_POST['meta_desc'],
										"usecomment" => $_POST['usecomment'],
										"userating"  => $_POST['userating'],
										"validated"	 => $validated,
										"style"      => str_replace("-1","",$_POST['style']),
										"date"       => $time,
										"artimg"     => $this->artimg,
										"related"    => $related
								   );

		// process custom fields
		$sql = $udb->query("SELECT * FROM ".$database['article_field']);
		while ($row = $udb->fetch_array($sql) )
		{
			$udb->insert_data['custom_'.$row['fieldid']] = $_POST['custom_'.$row['fieldid']];
		}

		$udb->query_insert();

		$this->process_support();
		
		//comment processing
		if (intval($_POST['usecomment']) == '1')
		{
			$cmt->process_addarticle(  $settings['siteurl']."/".$this->link_art( $udb->insert_id() ) , $_POST['pid'] , stripslashes($_POST['subject']) , $udb->insert_id() );
		}
		
		$this->redirect($_SERVER['PHP_SELF'].'?do=manageart&amp;search=1amp;sort=date&amp;order=desc');
	}
	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
					    PROCESS SUPPORT FILE
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function process_support($id='')
	{
		global $_FILES,$admin,$tpl,$database,$udb,$_POST,$site,$userinfo;
		
		if ($_FILES['support'])
		{
			if ($id != '' && $_POST['nosupport'] != '1') $this->support_remove = 1;

			if ($_FILES['support']['size'] > 0)
			{
				if ($this->support_remove)
				{
					$row = $udb->query_once("SELECT id FROM $database[article_support] WHERE aid='$id'");
					@unlink($row['loc']);
				}

				if ($id == '') 
				{
					$latest = $udb->query_once("SELECT id FROM $database[article_article] ORDER BY id DESC LIMIT 1");
					$theid = $latest['id'];
				}
				else
				{
					$theid = $id;
				}
				
				@mkdir($this->support_dir,0777);
				$ext = $admin->get_ext($_FILES['support']['name']);

				@copy($_FILES['support']['tmp_name'], $this->support_dir."art_".$theid.".".$ext);	
				$data = $this->support_dir."art_".$theid.".".$ext;

				if ($this->support_remove)
				{
					$udb->query("UPDATE $database[article_support] SET mime='".$_FILES['support']['type']."',loc='".$data."',aid='".$theid."' WHERE aid='".$theid."'");
				}
				else
				{
					$udb->query("INSERT INTO $database[article_support] SET mime='".$_FILES['support']['type']."',loc='".$data."',aid='".$theid."'");
				}
			}
		}
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
					    MANAGE ARTICLES AND SEARCH
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function listarticles()
	{
		global $database,$admin,$settings,$udb,$_SERVER,$evoLANG,$_REQUEST,$usr,$userinfo;
		
		if($usr->checkperm('',"editown",1) == true && $usr->checkperm('',"editall",1) == false)
		{
			$where = " WHERE author='".$userinfo['id']."'";
			$this->editown = 1;
		}

		if($usr->checkperm('',"editown",1) == false && $usr->checkperm('',"editall",1) == false)
		{
			return $evoLANG['noperm'];
		}


		$html .= $this->search_table();
		$html .= "<br />";
		$html .= $this->search();
		
		if (!isset($_REQUEST['search']))
		{
			$where = " ORDER BY date DESC";
			$query = "SELECT * FROM ".$database['article_article'].$where;
			$sql = $udb->query($query);
			$results = $udb->num_rows($sql);
			$admin->row_align = "center";
			
			$rows .= $admin->add_spacer($evoLANG['articles']);
			while ($row = $udb->fetch_array($sql) )
			{
				$row = $admin->strip_array($row);

				$rows2 .= $admin->add_row($row['subject']." ".$this->check_status($row['validated'],$row['id'])." <br /><span style=\"font-weight:normal\">$evoLANG[author]: ".$this->get_user($row['author'],"username")."<br />$evoLANG[date]: ".date($settings['dateformat'],$row['date'])."</span>","[ ".$admin->makelink("edit",$_SERVER['PHP_SELF']."?do=editart&amp;id=$row[id]")."]  [".$this->wrap_confirm("delete",$_SERVER['PHP_SELF']."?do=deleteart&amp;id=".$row['id'])."]");
			}
			$rows .= $rows2 != "" ? $rows2:$admin->add_row('',$evoLANG['nofound']);
			$html .= "<br />".$admin->add_table($rows,"90%");
		}

		return $html;
	}
	
	function search_table($act='')
	{
		global $admin,$database,$settings,$evoLANG,$_POST,$_REQUEST;
		
		$admin->row_align = "left";
		
		/* ------------------------------------------------ */
		$admin->do_check = 1; // enable js validating
		$admin->check_element = array(
										"sort"      => "Sort",
										"order"	    => "Order"
	        						 );
		// element condition
		$admin->element_condition["sort"] = array("-1");
		$admin->element_condition["order"] = array("-1");
		/* ------------------------------------------------ */
		
		for ($x=1;$x < $this->perpage_opt+1;$x++)
		{	
			unset($mult);
			$mult = $x * $this->perpage_opt;

			$ddown .= $mult."|".$mult;
			$ddown .= ",";
			
		}

		//if (!isset($_REQUEST['pp'])) $this->perpage = $this->perpage_opt;
		$perpage = $admin->form_select("pp",$ddown,$_REQUEST['pp'],"");

		$_REQUEST['sort'] = $_REQUEST['sort'] != "" ? $_REQUEST['sort'] : "date";
		$_REQUEST['order'] = $_REQUEST['order'] != "" ? $_REQUEST['order'] : "desc";
		$_REQUEST['status'] = $_REQUEST['status'] != "" ? $_REQUEST['status'] : "";
		
		$status = $settings['inadmin'] == 1 ? "<b>".$evoLANG['status']."</b>".$admin->form_select("status","0|- ".$evoLANG['pending'].",1|- ".$evoLANG['live'],$_REQUEST['status'],$evoLANG['all']) : "<input type=\"hidden\" name=\"status\" value=\"1\" />";		
		
		$act = $act != '' ? $act:$_SERVER['PHP_SELF']."?do=manageart";
		$html .= $admin->form_start("",$act);	
		
		$this->parent_name = $evoLANG['allcat'];
		$category = $this->makeselectbox("pid",$_POST['pid']);

		$search .= $admin->add_spacer($evoLANG['search']);
		$search .= $admin->add_row($evoLANG['keywords'],$admin->form_input("word",$_REQUEST['word'])." ".$evoLANG['category']." ".$category);
		$search .= $admin->add_row($evoLANG['options'],"<b>".$evoLANG['sortby']."</b>".$admin->form_select("sort","date|- ".$evoLANG['date'].",author|- $evoLANG[author],subject|- $evoLANG[title]",$_REQUEST['sort'],$evoLANG['sortby'])." "."<b>".$evoLANG['orderby']."</b>".$admin->form_select("order","asc|- $evoLANG[asc],desc|- $evoLANG[desc]",$_REQUEST['order'],"Order").$status." <b>".$evoLANG['perpage']."</b>".$perpage );
		$search .= $admin->form_submit("search");	
	
		$html .= $admin->add_table($search,"90%").'</form>';
		return $html;
	}

	function simple_searchbox()
	{
		global $admin,$database,$settings,$evoLANG,$_POST,$_REQUEST;
		$admin->inputsize = "18";
		$html .= $admin->form_start("","index.php"."?search");		
		$html .= $admin->form_input("word",$_REQUEST['word']);
		$html .= $admin->form_hidden("sort","date");
		$html .= $admin->form_hidden("order","desc");
		$html .= $admin->form_hidden("status","1");
		
		$this->parent_name = $evoLANG['allcat'];
		$html .=  $this->makeselectbox("pid",$_POST['pid']);
		$html .= "<input type=\"submit\" value=\"$evoLANG[search]\" name=\"search\" />\n</form>";
		$html .= "".$admin->makelink($evoLANG['advsearch'],$_SERVER['PHP_SELF']."?advsearch");

		return $html;
	}
	
	function search()
	{
		global $udb,$evoLANG,$_SERVER,$admin,$database,$settings,$_REQUEST,$userinfo,$usr,$_GET;
		
		if (isset($_REQUEST['search']))
		{
			$pg = trim($_GET['pg']) == '' ? '':$_GET['pg'];
			$pg = (isset($pg)) && $pg != '' ? $pg:"1";

			$_REQUEST['status'] = $_REQUEST['status'] == "-1" ? "":$_REQUEST['status'];
			$joiner = " OR ";
			
			$this->table_lookup = array (
											"date"     => "Date",
											"author"   => "Author",
											"subject"  => "Subject"
										);
		
			if ($_REQUEST['word'] != "")
			{
				foreach ($this->table_lookup as $rowname => $rowdesc)
				{
					if ($this->editown)
					{
						if ($rowname != "author")
						{
							$count++;
						}
					}
					else
					{
						$words = explode(" ",$_REQUEST['word']);
						foreach($words as $single)
						{
							if (trim($single) != "")
							{
								$count++;
							}
						}
					}
				}

				unset($words,$single,$i);
				foreach ($this->table_lookup as $rowname => $rowdesc)
				{
					if ($this->editown)
					{
						if ($rowname != "author")
						{
							$i++;
							$lookup .= $rowname." LIKE '%".$_REQUEST['word']."%'";

							if ($i < $count) $lookup .= $joiner;
						}
					}
					else
					{
						
						$words = explode(" ",$_REQUEST['word']);
						foreach($words as $single)
						{
							if (trim($single) != "")
							{
								$i++;
								$lookup .= $rowname." LIKE '%".$single."%'";
								if ($i < $count) $lookup .= $joiner;
							}							
						}
						
					}
				}
			}
			
			$where = $lookup == "" ? "":" WHERE ";
			$sort = trim($_REQUEST['sort']) == '' ? "date":$_REQUEST['sort'];
			$orders = trim($_REQUEST['order']) == '' ? "desc":$_REQUEST['order'];

			$order = " ORDER BY ".$sort." ".$orders ;

			if ($_REQUEST['status'] != "") $where = " WHERE ";
			if ($_REQUEST['word'] != "") $and = " AND ";
			
			if($this->editown)
			{
				if ($where == '')
				{
					$own .= " WHERE ";
				}
				//
				if (trim($and) == '' && $_REQUEST['status']) $and = " AND ";
				$own .= " author='".$userinfo['id']."' ".$and." ";
			}

			if ($_REQUEST['word'] == '') $and = '';

			$parent = $_REQUEST[pid] != '' ? "pid='".$_REQUEST[pid]."'":"";
			$parent = $and == '' && $_REQUEST[pid] != '' ? "AND ".$parent:$parent;

			$status = $_REQUEST['status'] != "" ? "validated='".$_REQUEST['status']."' ".$and.$parent:"";
			
			if ($where == '' && $status == '' && number_format($_REQUEST[pid]) != '' && $_REQUEST[pid] != '')
			{
				$where = " WHERE pid='$_REQUEST[pid]' ";
			}

			$query = "SELECT subject,id,pid,summary,validated,author,date FROM ".$database['article_article'].$where.$own.$status.$lookup.$order;

			$sql = $udb->query($query);
			$results = $udb->num_rows($sql);
			$admin->row_align = "center";
			
			while ($row = $udb->fetch_array($sql) )
			{
				$found[] = $row;
			}
			

			$total = &$results;
			$this->perpage = $_REQUEST['pp'] != '' ? $_REQUEST['pp']:$this->perpage;
			if ($total > $this->perpage)
			{
				$totalpage = ceil($total/$this->perpage);
			}
					
			$totalpage = ($totalpage=='') ? $pg:$totalpage;
			$num = $pg + 1;
			$offset = ($pg-1) * $this->perpage;
			$prev = $pg-1;
			$curpage = $pg;
			$do = $settings['inadmin'] == 1 ? "manageart":"search";

			$query = "do=".$do."&amp;search=1&amp;sort=".$_REQUEST['sort']."&amp;order=".$_REQUEST['order']."&amp;status=".$_REQUEST['status'];	

			if ($pg < $totalpage) $nextpage = "<a href=\"$_SERVER[PHP_SELF]?".$query."&amp;pg=$num\"> ></a> ";
			if ($pg > 1) $prevpage = " <a href=\"$_SERVER[PHP_SELF]?".$query."&amp;pg=".$prev."\">< </a>";
			
			for ($i = 1; $i <= $totalpage; $i++)
			{
				if ($i == $curpage)
				{
					$pgloop .= "<b> [".$i."]</b> ";
				}
				else
				{
					$pgloop .= " <b><a href=\"$_SERVER[PHP_SELF]?".$query."&amp;pg=".$i."\">".$i."</a></b> ";
				}
			}

			unset($row);
			$counter = $offset;
			while ( $counter < ( $this->perpage * $pg ) )
			{
				if (!is_array($found[$counter])) break;
				$row = $found[$counter];
				
					$row = $admin->strip_array($row);
					
					if ($_GET['rss'] == '1')
					{

						$rss_results .= "<item>\n";
						$rss_results .= "<title>".utf8_encode(htmlspecialchars($row['subject']))."</title>\n";
						$rss_results .= "<link>".$settings['siteurl']."/".$this->link_art($row['id'])."</link>\n";
						$rss_results .= "<description>".utf8_encode(htmlspecialchars($row['summary']))."</description>\n";
						$rss_results .= "</item>";
					}

						if ($this->search_noopt != 1)
						{
							$options = "[ ".$admin->makelink("edit",$_SERVER['PHP_SELF']."?do=editart&amp;id=$row[id]")."]  [".$this->wrap_confirm("delete",$_SERVER['PHP_SELF']."?do=deleteart&amp;id=".$row['id'])."]";

							$art_status = $this->check_status($row['validated'],$row['id']);
						}
						else
						{
							$row['subject'] = $admin->makelink($row['subject'],$_SERVER['PHP_SELF']."?art/id:$row[id]");
						}
						

						$search_row = $admin->add_row($row['subject']." ".$art_status." <br /><span style=\"font-weight:normal\">$evoLANG[author]: ".$this->get_user($row['author'],"username")."<br />$evoLANG[date]: ".date($settings['dateformat'],$row['date'])."</span>",$options);

						if ($this->editown)
						{
							if ($row['author'] == $userinfo['id'])
							{
								$rows .= $search_row;
							}
						}
						else
						{
							$rows .= $search_row;
						}

					$counter++;
			}
			

			if ($_GET['rss'] == '1')
			{
				header("Content-Type: text/xml");

				$rss .= '<?xml version="1.0" ?> 
							<rss version="2.0">';
				$rss .= "
						<channel>
						<title>".utf8_encode(htmlspecialchars($settings['sitename']))."</title>
						<link>".$settings['siteurl']."</link>
						<description>php/mySQL Article Management System</description>
						";
				$rss .= $rss_results;
				$rss .= "\n</channel>\n\n</rss>";
			    echo $rss;
			    exit;
			}

			
			$rows = $results == 0 ? $admin->add_row("",$evoLANG['tryagain']):$rows;
			$html = $admin->add_table($admin->add_spacer($evoLANG['searchresults'].": ".$results).$rows,"90%");
			$html .= "<br /><div align=\"right\">".$prevpage.$pgloop.$nextpage."</div>";
		}

		return $html;
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
						CHECK ARTICLE STATUS
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function check_status($validated,$id)
	{
		global $evoLANG,$admin,$_SERVER;

		if ($validated == 1)
		{
			return "(<span class=\"green\">$evoLANG[live]</span>)";
		}
		else
		{
			return "(<span class=\"red\">$evoLANG[pending]</span>) <span style=\"font-weight:normal\">".$admin->makelink("[validate]",$_SERVER['PHP_SELF']."?do=validate&amp;id=$id")."</span>";
		}
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
						FETCH AUTHOR CACHE
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function get_user($id,$return='')
	{
		global $udb,$database,$admin,$authorinfo;

		if (number_format($id) != "")
		{
			if (!is_array($authorinfo[$id]))
			{
				// complicated
				/*
												"SELECT
													$database[article_user].*,
													$database[article_usergroup].*
														FROM $database[article_user]
															LEFT JOIN $database[article_usergroup]
																ON ($database[article_user].groupid = $database[article_usergroup].gid)

																	WHERE $database[article_user].id='$id'"
				*/

				$authorinfo[$id] = $udb->query_once("SELECT * FROM $database[article_user] WHERE id='$id'");
			}

			if ($return != '')
			{
				return $authorinfo[$id][$return];
			}
			else
			{
				return $authorinfo[$id];
			}
		}
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
						FETCH ARTICLE CACHE
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function get_article($id,$return='',$join=0)
	{
		global $udb,$database,$admin,$artinfo;

		if (number_format($id) != "")
		{
			if (!is_array($artinfo))
			{
				$sql = $join ? "SELECT 	$database[article_article].*,
										$database[article_support].loc,
										$database[article_support].downloads,
										$database[article_cat].usecomment AS cat_usecomment

										FROM $database[article_article]

											LEFT JOIN $database[article_support] ON ($database[article_support].aid = $database[article_article].id)
											LEFT JOIN $database[article_cat] ON ($database[article_article].pid = $database[article_cat].cid)
											
												WHERE $database[article_article].id='$id'"
								:

								"SELECT * FROM $database[article_article] WHERE id='$id'";

				$artinfo = $udb->query_once($sql);
				//print_r($artinfo);
				//exit;
				$artinfo = $admin->strip_array($artinfo);
			}

			if ($return != '')
			{
				return $artinfo[$return];
			}
			else
			{
				return $artinfo;
			}
		}
	}

	function make_artcache($pid,$opt=0)
	{
		global $udb,$database,$admin,$artcache,$_REQUEST;

		if ($pid != '')
		{
			if (!is_array($artcache))
			{
				$featured = $this->get_featured == 1 ? " featured DESC,":"";
				if ($opt)
				{
					$sql = $udb->query("SELECT *,IF(numvote>0,totalvotes/numvote,0) AS avg FROM $database[article_article] WHERE validated=1 AND pid='$pid' AND date < ".time()." ORDER BY $featured ".$_REQUEST['sort']." ".$_REQUEST['order']);
				}
				else
				{
					$sql = $udb->query("SELECT * FROM $database[article_article] WHERE validated=1 AND pid='$pid' AND date < ".time()." ORDER BY $featured date DESC");
				}

				while($row = $udb->fetch_array($sql))
				{
					$row = $admin->strip_array($row);
					$artcache[$row['id']] = $row;
				}
			}

			return $artcache;
		}
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
						FETCH CATEGORY CACHE
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function get_category($id='',$return='')
	{
		global $udb,$database,$admin,$cat_cache;
		
		if (!is_array($this->cat_cache))
		{
			if ( !is_array($cat_cache) )
			{
				$sql = $udb->query("SELECT * FROM $database[article_cat] ORDER by cid ASC");
				while ($row = $udb->fetch_array($sql))
				{
					$row = $admin->strip_array($row);
					$cat_cache[$row['pid']][$row['cid']] = $row;
				}				
			}

			$this->cache_count++;
			$this->cat_cache = $cat_cache;
		}
		else
		{
			$cat_cache = $this->cat_cache;
		}

		if (number_format($id) != "")
		{
			foreach ($cat_cache as $parent => $cat)
			{
				foreach ($cat as $category)
				{
					if ($category['cid'] == $id)
					{
						$cat_info = $category;
					}
				}
			}

			if ($return != '')
			{
				return $cat_info[$return];
			}
			else
			{
				return $cat_info;
			}
		}
	}
	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
							VALIDATION
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */


	function validate_article($id)
	{
		global $udb,$database,$admin,$evoLANG,$_SERVER,$_POST;
		
		if ($_POST['validate'])
		{
			$udb->query("UPDATE $database[article_article] SET validated=1 WHERE id='".$id."'");
			$content .= $evoLANG['artapproved'];
		}
		else
		{
			$content .= $admin->form_start("",$_SERVER['PHP_SELF']."?do=validate&amp;id=$id");
			$html .= $admin->add_spacer($evoLANG['artapproval']);
			$html .= $admin->add_row($evoLANG['name'],$this->get_article($id,"subject"));
			$html .= $admin->add_row($evoLANG['confirm'],"<input type=\"submit\" name=\"validate\" value=\"".$evoLANG['approve']."\" />");
			$content .= $admin->add_table($html,"60%");
		}	

		return $content;
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
							DATE THINGY
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function dateselect($curtime='')
	{
		global $admin,$evoLANG;
		
		$curtime = $curtime == '' ? time():$curtime;
		
		$today = date("j",$curtime);
		for($i=0;$i<=31;$i++)
		{
			$days .= $i."|".$i.",";
		}

		$thismonth = date("n",$curtime);
		for($j=0;$j<=12;$j++)
		{
			$months .= $j."|".$admin->intercap($evoLANG['months'][$j]).",";
		}

		$this->maxyear = 2010;
		$this->minyear = 2000;

		$thisyear = date("Y",$curtime);
		$totalyear = substr($this->maxyear,-2) - substr($this->minyear,-2);
		for($k=0;$k<=$totalyear;$k++)
		{
			$curyear = $this->minyear + $k;
			$years .= $curyear."|".$curyear.",";
		}

		$prefix = $this->date_prefix == '' ? '':$this->date_prefix;

		$html .= $admin->form_select($prefix."date[day]",$days,$today,"");
		$html .= $admin->form_select($prefix."date[month]",$months,$thismonth,"");
		$html .= $admin->form_select($prefix."date[year]",$years,$thisyear,"");

		return $html;
	}
	
	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
						MAIN - SHOW INDEX
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function main_showindex()
	{
		global $udb,$evoLANG,$_SERVER,$admin,$database,$settings,$_REQUEST,$usr,$tpl,$script,$layout;
		$this->makenav = 1;

		$latest_articles .= $this->main_getlatest($settings['showhowmany']);

		eval("\$content .= \"".$tpl->gettemplate("home_index")."\";");
		return $content;
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
						MAIN - SHOW CATEGORY
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	
	function main_showcat($cid)
	{
		global $udb,$evoLANG,$_SERVER,$admin,$database,$settings,$usr,$tpl,$script,$cat_cache,$artcache,$page,$_REQUEST;
		$this->makenav = 1;

		$category = $this->get_category($cid);
		$category['name'] = htmlspecialchars($category['name']);
		
		$page->site_title = $category['name'];


		$this->nav_showhome = 1;
		$this->nav_showart = 0;
		$breadcrumbs_nav = $this->make_breadcrumbs($cid);
		//echo $breadcrumbs_nav;

		if ($category['cat_image'] != '')
		{
			eval("\$category[image] .= \"".$tpl->gettemplate("bits_catimage")."\";");
		}
				
		$page->init($category['style']);
		$subcat = $this->main_showcat_sub($cid);

		//article sort thingy
		//sort by
		//sort way

		$_REQUEST['sort'] = $_REQUEST['sort'] == '' ? "date":$_REQUEST['sort'];
		$_REQUEST['order'] = $_REQUEST['order'] == '' ? "desc":$_REQUEST['order'];

		//$article_sort = $admin->form_start("sorting",$this->link_cat($cid));
		$link = $this->link_cat($cid);
		$article_sort .= "<b>$evoLANG[sortby]</b> ".$admin->form_select("sort","subject|$evoLANG[alphabet],date|$evoLANG[datepublished],views|$evoLANG[views],avg|$evoLANG[rating]",$_REQUEST['sort'],"");
		$article_sort .= " <b>".$evoLANG['in']."</b>".$admin->form_select("order","asc|$evoLANG[asc],desc|$evoLANG[desc]",$_REQUEST['order'],"");
		$article_sort .= "<input type=\"submit\" value=\"".$evoLANG['button_go']."\" />";
		
		// ------------------

		//make article loop
		$this->get_featured = 1;
		$artcache = $this->make_artcache($cid,1);
		if (is_array($artcache))
		{
			foreach ($artcache as $article)
			{
				$article['date'] = $this->makedate($article['date']);
				$article['author'] = $this->get_user($article['author'],"username");
				//echo $article['userating'];
				$article['rating'] = $article['userating'] == 1 ? $this->process_rating($article['numvote'],$article['totalvotes']):$evoLANG['disabled'];
				$article['views'] = number_format($article['views']);

				$article['summary'] = $settings['canhtmlsummary'] == 0 ? $admin->nohtml($article['summary']):$article['summary'];
				$featured = $article['featured'] == 1 ? "<img src=\"$script[imgfolder]featured.gif\" alt=\"$evoLANG[isfeatured]\" />":"";
				$art_link = $this->link_art($article['id']);
			
				if ($settings['art_useimage'] == 1 && $article['artimg'] != '')
				{
					eval("\$article[artimg] = \"".$tpl->gettemplate("bits_articleimage")."\";");
				}

				eval("\$art_loop .= \"".$tpl->gettemplate("home_artloop")."\";");
			}
		}
		else
		{
			$art_loop = $evoLANG['noarts'];
		}

		//meta-tags crap
		if ($settings['allowmeta_cat'] == 1)
		{
			if ($category['meta_key'] != '' || $category['meta_desc'] != '')
			{
				$page->meta_key = $category['meta_key'];
				$page->meta_desc = $category['meta_desc'];
			}
		}

		eval("\$content .= \"".$tpl->gettemplate("home_category")."\";");
		return $content;
	}
	
	function subcat_totalart($cid)
	{
		global $cat_cache,$database,$udb,$art_cache;
		
		if ($cid != '')
		{
			if (!is_array($art_cache))
			{
				$sql = $udb->query("SELECT pid,id FROM $database[article_article] WHERE validated=1");
				while ($artrow = $udb->fetch_array($sql) )
				{
					$art_cache[$artrow['pid']][$artrow['id']] = $artrow;
				}
			}
			
			$total = 0;

			if (is_array($art_cache[$cid]))
			{
				foreach ($art_cache[$cid] as $start)
				{
					$total++;
				}
			}			
			
			$total = number_format($this->subcat_totalart_count($cid)) > 0 ? $total+$this->subcat_totalart_count($cid):$total;
			return $total;
		}
	}

	function subcat_totalart_count($cid)
	{
		global $art_cache,$cat_cache;
		
		// oh for the love of god.. how to start..

		//kena dapatkan anak dia punya id
		if (is_array($cat_cache[$cid]))
		{
			foreach ($cat_cache[$cid] as $parent => $sub)
			{
				//ni anak dia -> $sub['cid']
				if (is_array($art_cache[$sub['cid']]))
				{
					foreach ($art_cache[$sub['cid']] as $start)
					{
						$total++;
					}
				}
				// article dalam anak dia dah dapat
				// kalau anak dia ade anak gak?
				$total = number_format($this->subcat_totalart_count($sub['cid'])) > 0 ? $total+$this->subcat_totalart_count($sub['cid']):$total;
			}
		}
		
		return $total;
	}

	function main_showcat_sub($cid)
	{
		global $udb,$evoLANG,$_SERVER,$admin,$database,$settings,$_REQUEST,$usr,$tpl,$script,$cat_cache;
		$this->makenav = 1;
		$this->get_category();
		
		if (is_array($cat_cache[$cid]))
		{
			foreach ($cat_cache[$cid] as $category)
			{
				$category['name'] = htmlspecialchars($category['name']);
				//print_r($category);
				if ($category['cat_image'] != '')
				{
					eval("\$category[image] = \"".$tpl->gettemplate("bits_catimage")."\";");
				}

				$totalart = number_format($this->subcat_totalart($category['cid']));
				
				$cat_link = $this->link_cat($category['cid']);				
				eval("\$subcat_loop .= \"".$tpl->gettemplate("home_subcat_loop")."\";");
			}
		}

		eval("\$content .= \"".$tpl->gettemplate("home_subcat")."\";");
		if( !empty($subcat_loop) ) return $content;
	}
	
	function link_cat($cid)
	{
		global $settings;
		
		if ($settings['useses'] == 1)
		{
			return "cid_".$cid.".html";
		}
		else
		{
			return $this->main_file."?cat/cid:".$cid;
		}
	}

	function link_art($id,$offset=0)
	{
		global $settings;
		
		if ($settings['useses'] == 1 || $this->force_ses == 1)
		{
			$offset = $offset != 0 ? ",".$offset:'';
			return $id.$offset.".html";
		}
		else
		{
			$offset = $offset != 0 ? ",offset:".$offset:'';
			return $this->main_file."?art/id:".$id.$offset;
		}
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	     			GENERATE CATEGORY LIST FOR NAV
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function main_getcat($pid='0',$selected='',$depth='1',$not='')
	{
		global $udb,$cat_cache,$database,$admin,$_GET,$script,$evoLANG,$tpl;

		if ( !is_array($cat_cache) )
		{
			$sql = $udb->query("SELECT * FROM $database[article_cat] ORDER by cid ASC");
			while ($row = $udb->fetch_array($sql))
			{
				$row = $admin->strip_array($row);
				$cat_cache[$row['pid']][$row['cid']] = $row;
			}
		}
		
		if(!isset($cat_cache[$pid])) return;

			while (list($parent,$category) = each($cat_cache[$pid]))
			{
				unset($sel,$flagged);
				if ($category['cid'] == $selected)
				{
					//
				}		
				$category['name'] = htmlspecialchars($category['name']);

				if ($_GET['cid'] == $category['cid']) $flagged = " <img src=\"".$script['imgfolder']."flagged.gif\" alt=\"".$evoLANG['viewingcat']."\" /> ";
				
				if ($depth > 1)
				{ 
					$move = str_repeat("&nbsp;",$depth);
				}
					
				
				$catlink = $this->link_cat($category[cid]);
				eval("\$img = \"".$tpl->gettemplate("home_nav_catloop")."\";");
				$a .= $move.$img;
						
				$a .= "<br />";

				//if ($_GET['cid'] == $category['cid']) $a .= $this->main_getcat($category[cid],$selected,$depth+1,$not);
			}

		return $a;
	}

	function make_leftnav()
	{
		global $udb,$cat_cache,$database,$admin,$_GET,$script,$evoLANG,$tpl,$layout;
		
		$leftnav_bit = $this->main_getcat();
		$search_box = $this->simple_searchbox();
		
		eval("\$a = \"".$tpl->gettemplate("home_leftnav")."\";");

		if ($this->makenav == 1) return $a;
	}

	function makedate($date)
	{
		global $admin,$settings;
		if ($date != '')
		{
			return date($settings['dateformat'],$date);
		}
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	     			GET LATEST ARTICLES
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function main_getlatest($total='',$from='')
	{
		global $settings,$udb,$cat_cache,$database,$admin,$_GET,$script,$evoLANG,$tpl,$artcache;

		if ($settings['showlatest'] == 1)
		{
			$from = $from == '' ? '':' AND pid = \''.$from.'\' ';
			$sql = $udb->query("SELECT date,author,pid,id,summary,subject,numvote,totalvotes,views,userating,validated,artimg
									FROM $database[article_article]
										WHERE validated=1 AND date < ".time()."$from
											ORDER BY id DESC LIMIT ".$settings['showhowmany']);
			
			while ($article = $udb->fetch_array($sql) )
			{
				$article = $admin->strip_array($article);

				$article['date'] = $this->makedate($article['date']);
				$article['author'] = $this->get_user($article['author'],"username");
				$article['category'] = $admin->makelink("<span class=\"category\">".htmlspecialchars($this->get_category($article['pid'],"name"))."</span>",$this->link_cat($this->get_category($article['pid'],"cid")));
				
				$article['rating'] = $article['userating'] == '1' ? $this->process_rating($article['numvote'],$article['totalvotes']):$evoLANG['disabled'];
				$article['summary'] = $settings['canhtmlsummary'] == 0 ? $admin->nohtml($article['summary']):$article['summary'];
				$article['views'] = number_format($article['views']);
				$art_link = $this->link_art($article['id']);
				
				if ($settings['art_useimage'] == 1 && $article['artimg'] != '')
				{
					eval("\$article[artimg] = \"".$tpl->gettemplate("bits_articleimage")."\";");
				}

				eval("\$content .= \"".$tpl->gettemplate("home_artloop")."\";");
			}
		}
		return $content;
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	     			GET TOP ARTICLES
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function main_showtopart($total='')
	{
		global $settings,$udb,$cat_cache,$database,$admin,$_GET,$script,$evoLANG,$tpl,$artcache;
			$content .= "<h1>".$evoLANG['mostpop']."</h1>";

			$sql = $udb->query("SELECT date,author,pid,id,summary,subject,numvote,totalvotes,views,userating
									FROM $database[article_article]
										WHERE date < ".time()." AND validated=1
											ORDER BY views DESC LIMIT ".$total);
			
			while ($article = $udb->fetch_array($sql) )
			{
				$article = $admin->strip_array($article);

				$article['date'] = $this->makedate($article['date']);
				$article['author'] = $this->get_user($article['author'],"username");
				$article['category'] = $admin->makelink("<span class=\"category\">".htmlspecialchars($this->get_category($article['pid'],"name"))."</span>",$_SERVER['PHP_SELF']."?cat/cid:".$this->get_category($article['pid'],"cid"));

				$article['rating'] = $article['userating'] == '1' ? $this->process_rating($article['numvote'],$article['totalvotes']):$evoLANG['disabled'];
				$article['summary'] = $settings['canhtmlsummary'] == 0 ? $admin->nohtml($article['summary']):$article['summary'];
				$article['views'] = number_format($article['views']);
				$art_link = $this->link_art($article['id']);

				eval("\$content .= \"".$tpl->gettemplate("home_artloop")."\";");
			}

		return $content;
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	     			GET TOP RATED ARTICLES
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function main_showtoprated($total='')
	{
		global $settings,$udb,$cat_cache,$database,$admin,$_GET,$script,$evoLANG,$tpl,$artcache;
			$content .= "<h1>".$evoLANG['toprated']."</h1>";

			$sql = $udb->query("SELECT numvote,totalvotes,id,subject,author,IF(numvote>0,totalvotes/numvote,0) AS avg,date,views,summary,pid,userating
									FROM $database[article_article]
										WHERE date < ".time()." AND validated=1
											ORDER BY avg DESC, numvote DESC LIMIT ".$total);
			
			while ($article = $udb->fetch_array($sql) )
			{
				//echo $article['avg']."<br />";
				$article = $admin->strip_array($article);

				$article['date'] = $this->makedate($article['date']);
				$article['author'] = $this->get_user($article['author'],"username");
				$article['category'] = $admin->makelink("<span class=\"category\">".htmlspecialchars($this->get_category($article['pid'],"name"))."</span>",$_SERVER['PHP_SELF']."?cat/cid:".$this->get_category($article['pid'],"cid"));

				$article['rating'] = $article['userating'] == '1' ? $this->process_rating($article['numvote'],$article['totalvotes']):$evoLANG['disabled'];
				$article['summary'] = $settings['canhtmlsummary'] == 0 ? $admin->nohtml($article['summary']):$article['summary'];
				$article['views'] = number_format($article['views']);
				$art_link = $this->link_art($article['id']);

				
				eval("\$content .= \"".$tpl->gettemplate("home_artloop")."\";");
			}

		return $content;
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	     			PROCESS RATING
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */


	function process_rating($numvotes,$totalvote)
	{
		global $evoLANG,$tpl,$script;

		$average = $totalvote != 0 ? sprintf ("%.2f", ($totalvote / $numvotes)):0;		
		$number = $average;
		
		if ($number >= 8) $pips = "5";
		elseif ($number >= 6) $pips = "4";
		elseif ($number >= 4) $pips = "3";
		elseif ($number > 2) $pips = "2";
		elseif (($number <= 2) && ($number > 0)) $pips = "1";
		else $pips = "0";
		
		if ($pips == "0")
		{
			$rating = $evoLANG['word_notrated'];
		}
		else
		{
			eval("\$rating = \"".$tpl->gettemplate("bits_star")."\";");
		}
		return $rating;
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	     				MAIN- Search
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function main_search()
	{
		global $_REQUEST,$_POST,$admin,$_GET,$_SERVER;
		$this->search_noopt = 1;

		$admin->row_once = 1;

		return $this->search();
	}

	
	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	     				MAIN- AUTHOR BOX
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function make_authorbox($id)
	{
		global $evoLANG,$admin,$udb,$authorinfo,$usr,$tpl;

		if ($id == '') return $evoLANG['xid'];
		$author = $this->get_user($id);
		
		//check freekin perm
		$usr->for = $author['groupid'];
		if ($usr->checkperm('',"showbox",1) == true)
		{
			//celup dalam coklat
			if ($author['avatar'] != '') 
			{
				$author['avatar'] = $admin->remove_root($author['avatar']);
				eval("\$author[avatar] = \"".$tpl->gettemplate("bits_avatar")."\";");
			}

			eval("\$content = \"".$tpl->gettemplate("article_authorbox")."\";");
			return $content;
		}
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	     				MAIN- RATING
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function article_rating()
	{
		global $_POST,$udb,$admin,$_COOKIE,$evoLANG,$database;
		
		$_POST = $admin->slash_array($_POST);
		$cookie = "article".$_POST['id'];

		if ($_COOKIE[$cookie] == "yes")
		{
			$this->error = $evoLANG['rated'];
			return false;
		}
		else
		{
			$row = $udb->query_once("SELECT numvote,totalvotes FROM $database[article_article] WHERE id='".$_POST['id']."'");
			$numvotes = $row['numvote'];
			$totalrate = $row['totalvotes'];
			
			$numvotes = $numvotes+1;
			$totalrate = $totalrate + $_POST['rate'];

			$udb->query("UPDATE $database[article_article] SET numvote = '$numvotes', totalvotes = '$totalrate' WHERE id='".$_POST['id']."'");
			
			$admin->makecookie($cookie, "yes");  // oh yea!			
			$this->message = $evoLANG['rated_thank'];
			$this->delete_cache($_POST['id']);
			return true;
		}
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	     				MAIN- DUMP Support File
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function main_supportfile($id='')
	{
		global $database,$udb,$admin;

		if ($id != '')
		{
			$this->delete_cache($id);
			$row = $udb->query_once("SELECT mime,loc FROM $database[article_support] WHERE aid='$id'");
			$udb->query("UPDATE $database[article_support] SET downloads=downloads+1 WHERE aid='$id'");
			
			$filedata = $admin->get_file( $admin->remove_root($row['loc']),"rb" );
			$filename = basename($admin->remove_root($row['loc']));
			$filelength = strlen($filedata);

			header("Content-Disposition: attachment; filename=".$filename."" );
			header("Content-Type: ".$row['mime']."\n");
			//Header("Content-Type: application/x-octet-stream");
			header("Content-Length: ".$filelength."\n\n");
			echo $filedata;
		}
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	     					CACHE SYSTEM
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function cache_make($id,$content,$offset)
	{
		global $admin,$settings;
		
		if ($settings['usecache'] == 1)
		{
			if ($id != '' && $content != '')
			{
				@mkdir($this->cache_dir,0777);
				$this->cache_name = $id."_".$offset.".txt";
				$this->cache_name = $this->cache_print == 1 ? "print_".$this->cache_name:$this->cache_name;
				

				$admin->write_file($this->cache_dir.$this->cache_name,$content);
			}
		}
	}
	
	function check_cache($id,$offset)
	{
		global $admin;
		
		$this->cache_name = $id."_".$offset.".txt";
		$this->cache_name = $this->cache_print == 1 ? "print_".$this->cache_name:$this->cache_name;

		if ( file_exists($this->cache_dir.$this->cache_name) )
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function get_cache($id,$offset)
	{
		global $admin;

		$this->cache_name = $id."_".$offset.".txt";
		$this->cache_name = $this->cache_print == 1 ? "print_".$this->cache_name:$this->cache_name;

		return $admin->get_file($this->cache_dir.$this->cache_name);
	}

	function delete_cache($id)
	{
		global $admin;

		$this->cache_name = $id.".txt";
		$this->cache_name = $this->cache_print == 1 ? "print_".$this->cache_name:$this->cache_name;
		
		@mkdir($this->cache_dir,0777);
		$handle = opendir($this->cache_dir);
		while ($file=readdir($handle))
		{
			if ($file != "." && $file != "..")
			{
				if (is_file($this->cache_dir.$file))
				{
					if ( preg_match("/".$id."_/",$file) || preg_match("/print_".$id."/",$file) )
					{
						@unlink($this->cache_dir.$file);
					}
				}
			}
		}
		closedir($handle);
		@unlink($this->cache_dir.$this->cache_name);
		
	}

	function delete_allcache()
	{
		global $admin,$_SERVER,$evoLANG;
		
		if ($settings['usecache'] == 1)
		{
			$handle = opendir($this->cache_dir);
			while ($file=readdir($handle))
			{
				if ($file != "." && $file != "..")
				{
					if (is_file($this->cache_dir.$file))
					{					
						@unlink($this->cache_dir.$file);
					}
				}
			}
			closedir($handle);
			@unlink($this->cache_dir.$this->cache_name);
		}
		
		//$this->redirect($_SERVER['PHP_SELF']);
		return $evoLANG['cache_cleared'].$admin->redirect('index.php',1);
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	     				MAIN- SHOW ARTICLE
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function main_showart($id='')
	{
		global $admin,$tpl,$udb,$database,$evoLANG,$script,$settings,$page,$parser,$cmt;
		global $_SERVER,$_GET,$_REQUEST,$_COOKIE;

		if ($id == '')
		{
			return $evoLANG['xid'];
		}

		//if ($_GET['cache'] == "clear") $this->delete_cache($id);
		$offset = $_GET['offset'] != '' ? $_GET['offset'] : 0;
		
		// cache-stuff
		if ($settings['usecache'] == 1)
		{
			if ($this->check_cache($id,$offset) == true )
			{
				return $this->get_cache($id,$offset);
			}
		}
		else
		{
			$this->delete_cache($id);
		}
		
		//get article array
		$article = $this->get_article($id,'',1);

		//style processing
		$page->init($article['style']);
		$page->site_title = htmlspecialchars( $this->get_category($article['pid'],'name') )." - ".$article['subject'];
		
		//breadcrumbs stuff
		$this->nav_showhome = 1;
		$this->nav_showart = 1;
		$this->nav_artarray = $article;
		$breadcrumbs_nav = $this->make_breadcrumbs($article['pid']);


		if (!is_array($article))
		{
			return $evoLANG['xid'];
		}

		//meta-tags crap
		if($settings['allowmeta_art'] == 1)
		{
			if ($article['meta_key'] != '' || $article['meta_desc'] != '')
			{
				$page->meta_key = $article['meta_key'];
				$page->meta_desc = $article['meta_desc'];
			}
		}

		//views
		if ($settings['uniqueonly'] == 1)
		{
			if (!isset($_COOKIE[$id."_viewed"]))
			{
				$admin->makecookie($id."_viewed","1");
				$udb->query("UPDATE $database[article_article] SET views=views+1 WHERE id='".$id."'");
			}
		}
		else
		{
			$udb->query("UPDATE $database[article_article] SET views=views+1 WHERE id='".$id."'");
		}


		//author box
		$author = $this->get_user($article['author']);
		$author_box = $offset == 0 ? $this->make_authorbox($article['author']):'';

		//upon unpublished
		if ($article['date'] > time())
		{
			return $evoLANG['xpublished'];
		}
		
		$parser->doAutoBR = $article['autobr'] == '1' ? 1:0;
		$parser->allowBBCode = 1;
		$parser->allowHTML= 1;
		
		$article['article'] = $parser->do_parse($article['article']);

		$article['article'] = preg_replace("/{attached:([^\"]*)::([^\"]*)}/siU","<div id=\"support_images\"><img src=\"\\1\" align=\"\\2\" /><br /></div>",$article['article']);
		$article['date'] = date($settings['dateformat'],$article['date']);
		
		
		// join to top of article
		

		$article['article'] = str_replace("[pagebreak","\n[pagebreak",$article['article']);

		$article['article'] = preg_replace("#\[pagebreak title=(['\"]?)(.*)(['\"])\]#iS","[pagebreak title=\"".htmlspecialchars("\\2")."\"]\n",$article['article']);

		//echo $article['article'];

		$article['article'] = str_replace("[pagebreak","(pagebreak",$article['article']);
		$article['article'] = str_replace("[","|---|LEFT|---|",$article['article']);
		$article['article'] = str_replace("(pagebreak","[pagebreak",$article['article']);

		$article['article'] = " [pagebreak title='".$evoLANG['intro']."'] ".$article['article']; 

		preg_match_all("/\[pagebreak title=(['\"]?)(.*)\\1]([^\[]+)/i", $article['article'], $matches);
		
		for($i = 0; $i < sizeof($matches[0]); $i++ )
		{
			$pages[] = array('title' => $matches[2][$i], 'content' => $matches[3][$i]);
		}

		$totalpage = count($pages);
		$subject = $article['subject'];
		$subtitle = $totalpage > 1 ? $admin->intercap($pages[$offset]['title']):'';
		$article['article'] = $pages[$offset]['content'];
		$article['article'] = str_replace("|---|LEFT|---|","[",$article['article']);
		
		if (isset($_GET['highlight']))
		{
			$article['article'] = $parser->highlight($_GET['highlight'],$article['article']);
		}

		$article['rating'] = $this->process_rating($article['numvote'],$article['totalvotes']);
		
		
		//only appears on last page
		if ($offset == ( count($pages)-1) )
		{
			if ($settings['use_relatedart'] == '1' && $article['related'] != '')
			{
				$sql_rel = $udb->query("SELECT id,subject FROM $database[article_article] WHERE id IN (".$article['related'].")");
				
				while ($relrow = $udb->fetch_array($sql_rel) )
				{
					$rel_link = $this->link_art($relrow['id']);
					eval("\$related_loop .= \"".$tpl->gettemplate("article_relatedloop")."\";");
				}

				eval("\$article[related] = \"".$tpl->gettemplate("article_related")."\";");
			}

			if ($article['loc'] != '' && file_exists($admin->remove_root($article['loc'])) )
			{
				$article['downloads'] = number_format($article['downloads']);
				$article['support_size'] = $admin->file_size(filesize($admin->remove_root($article['loc'])) );
				eval("\$article[support] = \"".$tpl->gettemplate("bits_supportfile")."\";");
			}
			else
			{
				/*
				$article['support'] = "<div id=\"border_bottom\"></div>
									   <h3>$evoLANG[supportfile]</h3>".$evoLANG['nosupportfile']."<br /><br />";
				*/
			}

			//comments system
			if ($settings['usecomment'] == 1)
			{	
				if ( $article['cat_usecomment'] == 1)
				{
					if ($article['usecomment'] == 1)
					{
						if ($settings['commentsystem'] == "internal")
						{
							//$article[commentform] <- ni dia punya aku bubuh kat article_view
							//$article[comments] <- ni kat dalam bits_comments

							$article['comments_loop'] = $cmt->showcomments( $article['id'] );
							$article['comments'] = $cmt->showform($article['id']);
							eval("\$article[commentsform] = \"".$tpl->gettemplate("bits_comments")."\";");
						}
						else
						{
							$article['comments'] = $cmt->showform($article['id']);
							eval("\$article[commentsform] = \"".$tpl->gettemplate("bits_forumlink")."\";");
						}
						
					}
				}

			}
			
			$article['customfields'] = $this->main_show_fields($article);
		}
		else
		{
			$article['related'] = '';
		}

		if (count($pages) > 1)
		{
			if($offset+1 <= count($pages))
			{
				$next = $offset+1;
				$page_next = $this->link_art($article['id'],$next);
				$next_name = $admin->intercap($pages[$offset+1]['title']);
				$next_name = $admin->partial($next_name,"25");

				eval("\$prevnext = \"".$tpl->gettemplate("bits_pagenext")."\";");
			}

			if($offset+1 == count($pages))
			{
				$next = $offset-1;
				$next_name = $admin->intercap($pages[$offset-1]['title']);
				$next_name = $admin->partial($next_name,"25");

				eval("\$prevnext = \"".$tpl->gettemplate("bits_pageprev")."\";");
			}
		}

		$pagetitle = ($totalpage == 1) ? '':$pagetitle;
		if ($totalpage > 1)
		{
			for($i=1;$i<=$totalpage;$i++)
			{
				unset($sel);
				$sel = ($i==$offset+1)? "selected=\"selected\"":'';
				$i2 = $i-1;
				$a3 = $admin->intercap($pages[$i-1]['title']); 
				$a3 = $admin->partial($a3,"25",0);
				$pagenav_loop .= "<option value=\"$i2.html\" $sel> $evoLANG[page] $i: ".$a3." </option>\n";
			}
			
			$article_link = $settings['useses'] == 1 ? "'$article[id],'+this.options[this.selectedIndex].value)":"'$_SERVER[PHP_SELF]?art/id:$article[id],offset:'+this.options[this.selectedIndex].value)";
			eval("\$pagenav = \"".$tpl->gettemplate("bits_pagenav")."\";");
		}
		
		if ($article['userating'] == 1)
		{
			eval("\$rating_selector = \"".$tpl->gettemplate("bits_rate")."\";");
		}
		else
		{
			$article['rating'] = $evoLANG['disabled'];
		}

		//if (preg_match("/MSIE/i",$_SERVER['HTTP_USER_AGENT']) )
		//{
			eval("\$bookmark = \"".$tpl->gettemplate("bits_bookmark")."\";");
		//}				

		eval("\$content = \"".$tpl->gettemplate("article_view")."\";");
		//process smart cache
		$this->cache_make($id,$content,$offset);
		return $content;
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	     				MAIN- PRINT ARTICLE
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function main_printart($id='')
	{
		global $admin,$tpl,$udb,$database,$evoLANG,$script,$settings,$page,$parser,$site,$cssfile;
		global $_SERVER,$_GET,$_REQUEST,$_COOKIE;

		if ($id == '')
		{
			return $evoLANG['xid'];
		}

		$offset = $_GET['offset'] != '' ? $_GET['offset'] : 0;
		
		$this->cache_print = 1;
		if ($settings['usecache'] == 1)
		{
			if ($this->check_cache($id,$offset) == true )
			{
				echo $this->get_cache($id,$offset);
				exit; 
			}
		}
		else
		{
			$this->delete_cache($id);
		}

		$article = $this->get_article($id,'',1);

		if (!is_array($article))
		{
			return $evoLANG['xid'];
		}	

		$page->init($article['style']);
		$page->site_title = $article['subject'];
		
		
				
		//author box
		$author = $this->get_user($article['author']);
		$author_box = $offset == 0 ? $this->make_authorbox($article['author']):'';

		//upon unpublished
		if ($article['date'] > time())
		{
			return $evoLANG['xpublished'];
		}
		
		$parser->doAutoBR = $article['autobr'] == '1' ? 1:0;
		$parser->allowBBCode = 1;
		$parser->allowHTML= 1;
		
		$article['article'] = $parser->do_parse($article['article']);
		$article['article'] = preg_replace("/{attached:([^\"]*)::([^\"]*)}/siU","<div id=\"support_images\"><img src=\"\\1\" align=\"\\2\" /><br /></div>",$article['article']);
		$article['date'] = date($settings['dateformat'],$article['date']);
		
		$article['article'] = str_replace("[pagebreak","\n[pagebreak",$article['article']);
		$article['article'] = preg_replace("#\[pagebreak title=(['\"]?)(.*)(['\"])\]#iS","[pagebreak title=\"".htmlspecialchars("\\2")."\"]",$article['article']);

		$article['article'] = preg_replace("/\[pagebreak title=(['\"]?)(.*)(['\"])]/i","<br /><h3 class=\"pagebreak_title\">\\2</h3><br /><br />", $article['article']);

		$subject = $article['subject'];
		$article['rating'] = $this->process_rating($article['numvote'],$article['totalvotes']);
		$article['customfields'] = $this->main_show_fields($article);
		
		if ($article['userating'] == '0')
		{
			$article['rating'] = $evoLANG['disabled'];
		}

		//only appears on last page
		/*
			if ($article['loc'] != '' && file_exists($admin->remove_root($article['loc'])) )
			{
				$article['downloads'] = number_format($article['downloads']);
				$article['support_size'] = $admin->file_size(filesize($admin->remove_root($article['loc'])) );
				eval("\$article[support] = \"".$tpl->gettemplate("bits_supportfile")."\";");
			}
			else
			{
				$article['support'] = "<div id=\"border_bottom\"></div>
									   <h3>$evoLANG[supportfile]</h3>".$evoLANG['nosupportfile']."<br /><br />";
			}
		*/	

		$cssfile = $tpl->process_style($article['style']);
		eval("\$content = \"".$tpl->gettemplate("article_print")."\";");
		//process smart cache
		$this->cache_make($id,$content,$offset);

		echo $content;
		exit;
		//return $content;
	}
	
	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	     				MAIN- SEND to FRIEND
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function main_email($id='')
	{
		global $tpl,$admin,$settings,$udb,$evoLANG,$_POST;
		
		if ($_POST['sendfriend'])
		{
			if ($admin->check_fields( array ("name","email","to_name","to_email","subject","message" ) ) == false )
			{
				return $admin->error_message;
			}

			$mailer = new mailer;
			$mailer->to = $_POST['to_name']."|||".$_POST['to_email'];
			$mailer->subject = $_POST['subject'];
			$mailer->usehtml = $_POST['usehtml'];
			$mailer->from = "$_POST[name]|||".$_POST['email'];
			$mailer->message = $_POST['message'];
			$mailer->sendmail();

			return $evoLANG['emailsent'];
		}
		else
		{
			$article_name = $admin->strip( $this->get_article($id,"subject") );
			$article_url = $settings['siteurl']."/".$this->link_art($id);

			eval("\$content = \"".$tpl->gettemplate("article_sendmail")."\";");
		}
		return $content;
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	     				Control Panel Login
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */


	function cp_login($error='',$return=0)
	{
		global $tpl,$evoLANG,$settings,$_GET,$_POST,$_SESSION,$page,$content;		
	
		eval("\$content = \"".$tpl->gettemplate("cp_login")."\";");
		if ($return)
		{
			$page->generate();
			exit;
		}
		else
		{
			return $content;
		}
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	     				MAIN- Get Author
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function main_getauthor($id='')
	{
		global $tpl,$admin,$settings,$udb,$evoLANG,$_POST,$database,$page;

		if ($id != '')
		{
			$author = $this->get_user($id);
			if (!is_array($author)) return $evoLANG['xid'];
			$page->site_title = $evoLANG['authorprofile']." : ".$author['username'];
			$author['avatar'] = $admin->remove_root($author['avatar']);
			

			$sql = $udb->query("SELECT * FROM ".$database['article_userfield']);
			while ( $row = $udb->fetch_array($sql) )
			{
				$author['customfields'] .= "<b>$row[name]</b>:<br /> ".$author["custom_".$row['fieldid']]." <br /><br />";
			}

			//articles by author
			$sql = $udb->query("SELECT id,subject,summary FROM $database[article_article] WHERE author='$id' ORDER BY date DESC");
			while ($arts = $udb->fetch_array($sql))
			{				
				eval("\$article_links .= \"".$tpl->gettemplate("bits_authorarticle")."\";");
			}

			eval("\$content = \"".$tpl->gettemplate("author_profile")."\";");
			return $content;
		}
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	     				MAIN - Export RSS 
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function main_exportrss()
	{
		global $admin, $database, $udb,$settings,$_GET;
		
		header("Content-Type: text/xml");

		$html .= '<?xml version="1.0" ?> 
					<rss version="2.0">';
        $html .= "
				<channel>
				<title>".utf8_encode(htmlspecialchars($settings['sitename']))."</title>
				<link>".$settings['siteurl']."</link>
				<description>php/mySQL Article Management System</description>
				";
	   
	   $cat = $_GET['cat'] != '' ? 'AND pid = \''.$_GET['cat'].'\'':'';

       $sql = $udb->query("SELECT * FROM ".$database['article_article']." WHERE validated=1 $cat ORDER BY id DESC LIMIT 0 , ".$settings['rss_total']."");
	   while ( $row = $udb->fetch_array($sql) )
	   {
			$row = $admin->strip_array($row);

			$html .= "<item>\n";
			$html .= "<title>".utf8_encode(htmlspecialchars($row['subject']))."</title>\n";
			$html .= "<link>".$settings['siteurl']."/".$this->link_art($row['id'])."</link>\n";
			$html .= "<description>".utf8_encode(htmlspecialchars($row['summary']))."</description>\n";
			$html .= "</item>";
       }

	   $html .= "\n</channel>\n\n</rss>";
	   echo $html;
	   exit;
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    				MAIN- mod_rewrite functions
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function rewrite_make()
	{
		global $_SERVER,$admin,$settings;
		
		if ($settings['useses'] == 1)
		{
			$content = "
						RewriteEngine On

						RewriteRule ^([0-9]+),([0-9]).html <<path>>index.php?art/id:$1,offset:$2
						RewriteRule ^([0-9]+).html <<path>>index.php?art/id:$1
												
						
						RewriteRule ^cid_([0-9]+),([a-z]+),([a-z]+).html <<path>>index.php?do=cat&cid=$1&sort=$2&order=$3
						RewriteRule ^cid_([0-9]+),([a-z]+).html <<path>>index.php?do=cat&cid=$1&sort=$2
						RewriteRule ^cid_([0-9]+).html <<path>>index.php?do=cat&cid=$1
											
					  ";

			$content = preg_replace("'([\n])[\s]+'","\\1",$content);

			//replace path
			$path =  str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
			
			$content = str_replace("<<path>>",$path,$content);

			$admin->write_file(".htaccess",$content);
		}
	}

	function rewrite_delete()
	{
		@unlink(".htaccess");
	}
	
	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    					PREVIEW ARTICLE
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function process_preview()
	{
		global $admin,$tpl,$udb,$database,$evoLANG,$script,$settings,$page,$parser,$site,$cssfile;
		global $_SERVER,$_GET,$_REQUEST,$_COOKIE,$_POST;

		$offset = $_GET['offset'] != '' ? $_GET['offset'] : 0;
		$_POST = $admin->strip_array($_POST);
		$article = $_POST;
		
		$parser->doAutoBR = $article['autobr'] == '1' ? 1:0;
		$parser->allowBBCode = 1;
		$parser->allowHTML= 1;
		
		$article['article'] = $parser->do_parse($article['article']);
		$article['article'] = preg_replace("/{attached:([^\"]*)::([^\"]*)}/siU","<div id=\"support_images\"><img src=\"\\1\" align=\"\\2\" /><br /></div>",$article['article']);
		$article['date'] = date($settings['dateformat'],$article['date']);
		
		$article['article'] = str_replace("[pagebreak","\n[pagebreak",$article['article']);
		$article['article'] = preg_replace("/\[pagebreak title=(['\"]?)(.*)(['\"])]/i","<br /><h3 class=\"pagebreak_title\">\\2</h3><br /><br />", $article['article']);

		$subject = $article['subject'];
		$article['rating'] = $article['userating'] == '0' ? $evoLANG['disabled'] : $this->process_rating($article['numvote'],$article['totalvotes']);
		$article['customfields'] = $this->main_show_fields($article);

		eval("\$content = \"".$tpl->gettemplate("article_preview",'',$this->def_folder)."\";");

		return $content;
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    				Main - Site Map
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function main_sitemap()
	{
		global $udb,$database,$admin,$tpl,$evoLANG;
		
		// lists category
		$categories = $this->main_sitemap_make('0');
		eval("\$content = \"".$tpl->gettemplate("home_sitemap")."\";");

		return $content;
	}
	
	function main_sitemap_make($pid='0',$count=0)
	{
		global $cat_cache,$udb,$database,$admin,$settings;
		
		if ( !is_array($cat_cache) )
		{
			$sql = $udb->query("SELECT * FROM $database[article_cat] ORDER by cid ASC");
			while ($row = $udb->fetch_array($sql))
			{
				$row = $admin->strip_array($row);
				$cat_cache[$row['pid']][$row['cid']] = $row;
			}
		}
		
		$cache = $cat_cache;
	
		if(!isset($cache[$pid])) return;
		
		$a .= "<ul>";

		while (list($parent,$category) = each($cache[$pid]))
		{
			$a .= "<li>".$admin->makelink('<b>'.htmlspecialchars($category['name']).'</b>', $this->link_cat($category['cid']) )."</li>";
			
			if ( $settings['site_listarticles'] == 1) 
			{
				$sql2 = $udb->query("SELECT subject,pid,id FROM ".$database['article_article']." WHERE validated=1");
				while ( $row2 = $udb->fetch_array($sql2) )
				{
					$row2 = $admin->strip_array($row2);
					$art_cache[$row2['pid']][$row2['id']] = $row2;
				}

				$a .= $this->main_sitemap_articles($category['cid']);
			}
			$a .= $this->main_sitemap_make($category['cid'],$count+1);
		} 

		$a .= "</ul>";
		$a .= $count == '1' ? "<br />":'';

		$udb->free_result($sql);
		return $a;
	}
	
	function main_sitemap_articles($category='')
	{
		if ($category != '')
		{
			global $admin,$udb,$art_cache,$database;

			if (!is_array($art_cache))
			{
				$sql2 = $udb->query("SELECT pid,id,subject FROM ".$database['article_article']." WHERE validated=1");
				while ( $row2 = $udb->fetch_array($sql2) )
				{
					$row2 = $admin->strip_array($row2);
					$art_cache[$row2['pid']][$row2['id']] = $row2;
				}
			}
			
			if (is_array($art_cache[$category]))
			{
				$a .= '<br /><ul style="padding-left:10px">';

				foreach ($art_cache[$category] as $art)
				{
					$a .= '<li> '.$admin->makelink(htmlspecialchars($art['subject']),$this->link_art($art['id'])).' </li>';
				}
				$a .= '</ul>';
			}
		}
		return $a;
	}
}	
?>