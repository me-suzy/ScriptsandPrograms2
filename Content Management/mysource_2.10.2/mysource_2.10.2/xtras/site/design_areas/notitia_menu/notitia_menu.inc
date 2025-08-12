<?  ##############################################
   ### MySource ------------------------------###
  ##- Site Creator object ----- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This file is subject to version 1.0 of the
## MySource License, that is bundled with
## this package in the file LICENSE, and is
## available at through the world-wide-web at
## http://mysource.squiz.net/
## If you did not receive a copy of the MySource
## license and are unable to obtain it through
## the world-wide-web, please contact us at
## mysource@squiz.net so we can mail you a copy
## immediately.
##
## $Source: /home/cvsroot/xtras/site/design_areas/notitia_menu/notitia_menu.inc,v $
## $Revision: 1.17 $
## $Author: csmith $
## $Date: 2003/11/10 22:15:50 $
#######################################################################
include_once(dirname(__FILE__).'/../menu/menu.inc');
#---------------------------------------------------------------------#

class Site_Design_Area_Notitia_Menu extends Site_Design_Area_Menu {

	function Site_Design_Area_Notitia_Menu(&$_owner) {
		Site_Design_Area_Menu::Site_Design_Area_Menu($_owner);
	}#end constructor

	 ############################################
	# Returns a reference to the notitia system
	function &get_notitia_system() {
		$web_system = &get_web_system();
		return $web_system->get_extension('notitia');
	}


	function paint(&$tag_attributes) {
		$this->temp['menu_id'] = $tag_attributes['id'];
		$session = &get_mysource_session();
		$menu_undocked = $session->get_var('notitia_menu_undocked');
		$menu_hidden = $session->get_var('notitia_menu_hidden');

		if (trim($_REQUEST['hide_notitia_menu']) != '' && $tag_attributes['id'] && $_REQUEST['hide_notitia_menu'] == $tag_attributes['id']) {
			# hiding or showing
			if ($menu_hidden) $temp = '';
			else $temp = $tag_attributes['id'];

			$session->set_var('notitia_menu_hidden', $temp);
			$menu_hidden = $session->get_var('notitia_menu_hidden');
		}
		if (trim($_REQUEST['undock_notitia_menu']) != '' && $tag_attributes['id'] && $_REQUEST['undock_notitia_menu'] == $tag_attributes['id']) {
			# docking or undocking
			if ($menu_undocked) $temp = '';
			else $temp = $tag_attributes['id'];

			$session->set_var('notitia_menu_undocked', $temp);
			$menu_undocked = $session->get_var('notitia_menu_undocked');
		} 
		if (trim($menu_undocked) != '' && $tag_attributes['id'] && $menu_undocked == $tag_attributes['id']) {
			$page = &$this->get_page();
			$design = &$page->get_design();
			$this->temp['dock_url'] = $_SERVER['REQUEST_URI'].'?'.$_SERVER['QUERY_STRING'].'&undock_notitia_menu='.$this->temp['menu_id'];
			$stylesheet = $design->get_file_url($design->stylesheet(), true);
			ob_start();
			$this->paint_section($tag_attributes);
			$menu_contents = ob_get_contents();
			ob_end_clean();
			$menu_contents = preg_replace("/[\s]+/"," ",$menu_contents);
			$html = '<html>
						<head>
							<link rel="stylesheet" href="'.$stylesheet.'" type="text/css">
						</head>
						<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
						<table border="0" cellspacing="0" cellpadding="0" width="100%" height="100%">
							<tr>
								<td width="100%" height="100%" valign="top">'.$menu_contents.'</td>
							</tr>
						</table>
						</body>
					</html>';
			$html = str_replace('"','\\"',str_replace("\n","",$html));
			?>
			<script language="Javascript">
				undocked_notitia_menu = window.open('', 'undocked_notitia_menu_<?=trim($tag_attributes['id'])?>', 'toolbar=no,status=yes,width=200,height=400,titlebar=no,scrollbars=yes,resizable=yes');
				undocked_notitia_menu.document.close();
				undocked_notitia_menu.document.open();
				undocked_notitia_menu.document.write("<?=$html?>");
				undocked_notitia_menu.focus();
			</script>
			<?
		} else {
			$this->paint_section($tag_attributes);
		}

	}#end paint()


	  #############################################################
	 # return the pageids for the passed level in the page lineage
	# and if this page is a classified manager get the notitia categories
	function get_level_pageids($level, $pageid=0, $index=0) {

		$session = &get_mysource_session();
		$menu_hidden = $session->get_var('notitia_menu_hidden');
		if (trim($menu_hidden) != '' && $this->temp['menu_id'] && $menu_hidden == $this->temp['menu_id']) {
			return array($level, array());
		}

		# OK, because the $pageid can be either just a pageid, or a combination of pageid, cid and pid
		# let's try and extract the details to their own vars
		$cid = 0;
		$pid = 0;
		if ($pageid && preg_match('/([0-9]+):c:([0-9]+):p:([0-9]+)(:fav)?/', $pageid, $matches)) {
			$pageid = $matches[1]; # the page that linked to these records
			$cid    = $matches[2]; # the categoryid
			$pid    = $matches[3]; # the recordid
			$orig_cid = $cid;
			unset($matches);
		}

		$level = $this->_get_valid_level($level, $pageid);

		# if this page is a record, then it can't have any children as it's not a category
		if ($pid) return array($level, array());

		$page       = &$this->get_page($pageid);
		$site       = &$this->get_site();
		$page_index = &$site->get_page_index();

		$page_level = (int) $page_index[$page->id]['level'];

		# Because this fn will be called many times for a frontitia page 
		# (as we drill down it's categories) we need to make sure that we only get the 
		# page children of this page once, not every time this fn is called
		if ($level <= ($page_level + 1)) {
			# get the pageids as normal
			list($level, $pageids) = Site_Design_Area_Menu::get_level_pageids($level, $pageid);
		} else {
			$pageids = array();
		}

		if (!$pageid) {
			for ($i = $level; $i > 0; $i--) {
				$page = &$this->get_page($pageid);
				$pageid = $page_index[$page->id]['parentid'];
			}
		}

		# Is this menu just printing the favourites, or are we
		# printing a normal menu here?
		
		$fav_menu = $this->sections[$level][$index]->get_val('show_favourites');
		if (!$fav_menu) {

			# if this is a frontitia page then tack on the 
			# category and record id's of the page, up to the currently 
			# selected category or record
			
			$page = &$this->get_page($pageid);
			$doin_da_menu_thang = false;

			# if this is a pull content page, if it is pulling the contents of
			# a frontitia page, add all the categorys and records to the menu
			# for this page
			if ($page_index[$page->id]['template'] == 'pullcontent') {
				$page_template = &$page->get_template();
				$frontitia_page = &$this->get_page($page_template->content_pageid);
				if ($frontitia_page->id && $page_index[$frontitia_page->id]['template'] == 'frontitia') {
					$frontitia = &$frontitia_page->get_template();
					$doin_da_menu_thang = true;

					if (!$cid) {
						# something thats going to happen a bit further down the page
						# is that the notitia menu is going to try and work out the
						# root categoryid that it should start printing from - defaults
						# to root categoryid for the frontitia page. But what if we dont
						# want that? The pull content page may have manually set a categoryid
						# to start from in its submit variables string
						foreach(split("[\r\n]+",$page_template->submit_variables) as $val) {
							list($variable,$value) = explode(':',$val);
							if ($variable == 'cid') $cid = $value; break;
						}
					}
				}
			} else if ($page_index[$page->id]['template'] == 'frontitia') {
				$frontitia = &$page->get_template();
				$doin_da_menu_thang = true;
			}

			if ($doin_da_menu_thang) {

				$notitia = &$this->get_notitia_system();
				$tree    = &$notitia->get_category_tree();

				$status_attribute = &$frontitia->get_attribute($frontitia->get_value('approval_status_attributeid'));

				# if we aren't currently somewhere under the category tree, 
				# then start from the beginning (ie the root)
				if (!$cid) $cid = $frontitia->get_root_categoryid();

				$r_cid = $_REQUEST['cid'];
				# get any sub-categories
				$childids = $tree->childids($cid);
				foreach($childids as $child_cid) $pageids[] = $page->id.':c:'.$child_cid.':p:0';

				# get any records
				$cat = &$notitia->get_category($cid);
				$recordids = $frontitia->get_sorted_trimmed_category_recordids($cat->id);
				foreach($recordids as $child_pid) {
					$pageids[] = $page->id.':c:'.$cid.':p:'.$child_pid;
				}

				if ($r_cid) {
					# we need to work out the parents of the current category
					$parent_cats = array();
					$cat = &$notitia->get_category($r_cid);
					while ($cat->parentid) {
						$parent_cats[] = $cat->parentid;
						$cat = &$notitia->get_category($cat->parentid);
					}

					$parent_cats[] = $r_cid;

					for (reset($parent_cats); $parent_cid = current($parent_cats); next($parent_cats)) {
						$childids = $tree->childids($parent_cid);
						$childids[] = $r_cid;

						for (reset($childids); $child_cid = current($childids); next($childids)) {
							if (!in_array($child_cid, $parent_cats)) continue;

							$cat_string = $page->id.':c:'.$child_cid.':p:0';

							$cat = &$notitia->get_category($child_cid);
							$children = $tree->childids($child_cid);
							foreach($children as $c_cid) {
								$c_string = $page->id.':c:'.$c_cid.':p:0';
								$page_index[$cat_string]['childids'][$c_string] = $c_string;
								$page_index[$c_string]['effective_visible'] = true;
								$childids[] = $c_id;
							}
							unset($children);
							$recordids = $frontitia->get_sorted_trimmed_category_recordids($cat->id);

							foreach($recordids as $child_pid) {
								$record_string = $page->id.':c:'.$child_cid.':p:'.$child_pid;
								$page_index[$page->id]['childids'][$cat_string] = $cat_string;
								$page_index[$cat_string]['effective_visible'] = true;
								$page_index[$cat_string]['childids'][$record_string] = $record_string;
								$page_index[$record_string]['effective_visible'] = true;
							}
						}
					}
				}
			}#end if
			
		} else {

			# Hey! What about favourites?
			# Okay. Lets do them now then.
			# Okay!

			$frontitia_pageid = $this->sections[$level][$index]->get_val('frontitia_pageid');
			$root_categoryid = $this->sections[$level][$index]->get_val('root_categoryid');

			$fav_menu = array();
			$page = &$this->get_page();
			$this_pageid = $page->id;
			$pageids = array();

			$session = &get_mysource_session();
			$userid = $session->user->id;
			if ($userid > 0) {
				global $_NOTITIA_MENU;
				$frontitia_page = $this->get_page($frontitia_pageid);
				$frontitia = &$frontitia_page->get_template();

				# first of all, do we need to delete a favourite?
				if ($_REQUEST['fav_del']) {
					# is this a valid favourite?
					if (!$frontitia->delete_record($_REQUEST['fav_del'])) bam('FAV NOT DELETED');
					unset($_REQUEST['fav_del']);
				}

				if (isset($_NOTITIA_MENU['favs'])) {
					$favs = $_NOTITIA_MENU['favs'];
				} else {
					$favs = $frontitia->get_all_favourites($userid);
					$_NOTITIA_MENU['favs'] = $favs;
				}

				# if we dont have a current categoryid that the
				# favourites menu is open at, it could be because
				# we have gone to another page, so get the last
				# known categoryid that we were open to from the 
				# session
				if (!$_REQUEST['fav_cid']) $_REQUEST['fav_cid'] = $session->get_var('last_known_fav_cid');
				if ($_REQUEST['cid']) $_REQUEST['fav_cid'] = $_REQUEST['cid'];
				$session->set_var('last_known_fav_cid', $_REQUEST['fav_cid']);
				
				# so we have all the favourites, but we need to
				# construct a menu from the root categoryid all
				# the way down to where the favourites are pointing
				$notitia  = &$this->get_notitia_system();
				$tree     = &$notitia->get_category_tree();
				$childids = $tree->childids($root_categoryid);

				if (!empty($childids)) {
					foreach ($favs as $rid => $data) {
						$_NOTITIA_MENU['fav_records'][$this_pageid.':c:'.$data['categoryid'].':p:'.$data['recordid'].':fav'] = "{$level}:{$index}";
						$_NOTITIA_MENU['fav_recordids'][$this_pageid.':c:'.$data['categoryid'].':p:'.$data['recordid'].':fav'] = $rid;
						$cat = &$notitia->get_category($data['categoryid']);
						# trace this cat back up the tree until we hit
						# one of the original childids (the top level)
						$parentid = 0;
						$parentids = array();
						$i = 0;
						
						while (!in_array($parentid, $childids)) {
							$parentid = $cat->parentid;
							$parentids[] = $parentid;
							$cat = &$notitia->get_category($parentid);
							if (!$cat->parentid) break(2);
						}
						
						# now we have the top level categoryid for this favourite
						# and all its parents to get there
						$eval_string = '\$fav_menu';
						$page_string = '';
						$parentids = array_reverse($parentids);
						foreach ($parentids as $pid) $eval_string .= "['".$this_pageid.":c:".$pid.":p:0:fav']";

						$record_string = $this_pageid.":c:".$data['categoryid'].":p:".$data['recordid'].":fav";
						$page_string .= "['".$record_string."'] = '".$record_string."';";

						# save the url for this record into the session so we can get it later
						$url_page = $this->get_page($data['pageid']);
						$url = $url_page->get_url(1).'cid='.$data['categoryid'].'&pid='.$data['recordid'];
						$_NOTITIA_MENU['favourite_urls'][$this_pageid][$data['categoryid']]['records'][$data['recordid']]['url'] = $url;
						
						$eval_string .= "['".$this_pageid.":c:".$data['categoryid'].":p:0:fav']['".$record_string."'] = '1';";
						eval($eval_string);
					}
				}

				# now we need to add these 'pages' (hehe) to the
				# page index so that the normal menu will print
				# them for us

				$cid = $_REQUEST['fav_cid'];
				if (!$cid) $cid = $_REQUEST['cid'];

				# if we are showing all pages we need to populate the 
				# entire tree from this level down, otherwise we just
				# put this level into the page index
				if ($this->sections[$level][$index]->sub_section->_set_vars['show_all_pages']['value']) {
					$this->_populate_page_index_complete($fav_menu, $page_index);
					$ances = $tree->ancestorids($orig_cid);
					$ances[] = $orig_cid;
					foreach ($ances as $pid) $parents[] = $this_pageid.':c:'.$pid.':p:0:fav';
				} else {
					$ances = $tree->ancestorids($cid);
					$ances[] = $cid;
					foreach ($ances as $pid) $parents[] = $this_pageid.':c:'.$pid.':p:0:fav';
					$this->_populate_page_index($fav_menu, $page_index, $parents);
				}

				if ($level <= 0) {
					# nice easy solution, just return the top level
					$pageids = array_keys($fav_menu);
				} else {
					for ($lvl = ($level-1); $lvl > 0; $lvl--) {
						$new_fav_menu = array();
						foreach ($fav_menu as $key => $value) {
							if (in_array($key, $parents)) {
								$pageids = array_merge($pageids,array_keys($value));
							}
						}
					}
					foreach ($fav_menu as $key => $value) {
						if (in_array($key, $parents)) {
							$pageids = array_merge($pageids,array_keys($value));
						}
					}
				}
			}
		}

		return array($level, $pageids);

	}# end get_level_pageids()


	function _populate_page_index(&$fav_menu, &$page_index, $parents) {
		$pages = $fav_menu;
		foreach ($pages as $page => $children) {
			$page_index[$page]['effective_visible'] = true;
			foreach ($children as $child => $child_data) {
				if (in_array($page, $parents)) $page_index[$page]['childids'][$child] = $child;
				if (in_array($child, $parents)) {
					$this->_populate_page_index($fav_menu[$page], $page_index, $parents);
				} else if (in_array($page, $parents)) {
					$page_index[$child]['effective_visible'] = true;
					$fav_menu[$page][$child] = $child;
				} else {
					unset($fav_menu[$page][$child]);
				}
			}
		}
	}


	function _populate_page_index_complete(&$fav_menu, &$page_index) {
		$pages = $fav_menu;
		foreach ($pages as $page => $children) {
			$page_index[$page]['effective_visible'] = true;
			foreach ($children as $child => $child_data) {
				$page_index[$page]['childids'][$child] = $child;
				$this->_populate_page_index_complete($fav_menu[$page], $page_index);
			}
		}
	}


	  ########################################################################################
	 # this fn returns a boolean indicating whether the page is in the current page lineage
	# this pageid can also be a categoryid or recordid from notitia
	function current_page($pageid) {

		static $cache = array(); # cache the results to stop the need to process each time called
								 # NOTE : this cache seems to be available over all instances of 
								 #        an object.
		if (!$pageid) return false;

		if (!isset($cache[$pageid])) {

			# if there is a current category id then and the pageid is for a notitia element
			if (preg_match('/^([0-9]+):c:([0-9]+):p:([0-9]+)(:fav)?/', $pageid, $matches)) {

				# if there is no current category id then we have not current 
				# category lineage to work with, therefore this isn't a current page
				if (empty($_REQUEST['cid']) && empty($_REQUEST['fav_cid'])) {
					$cache[$pageid] = false;
				
				# else check the matched cid is in the current category lineage
				} else {

					$cid    = $matches[2]; # the categoryid
					$rid    = $matches[3]; # the recordid
					unset($matches);

					$notitia = &$this->get_notitia_system();
					$tree    = &$notitia->get_category_tree();
					
					if ($rid && $_REQUEST['pid']) {
						if ($rid == $_REQUEST['pid']) $cache[$pageid] = true;
						else $cache[$pageid] = false;
					} else if (!$rid && ($cid == $_REQUEST['cid'] || $cid == $_REQUEST['fav_cid'])) {
						$cache[$pageid] = true;
					} else {

						$r_cid = $_REQUEST['cid'];
						if (!$r_cid) $r_cid = $_REQUEST['fav_cid'];
						$current_cat_lineage = $tree->ancestorids($r_cid);

						# if the passed category id is in the current categories lineage
						# then $page is a current "page"
						$cache[$pageid] = in_array($cid, $current_cat_lineage);

					}#end if

				}#endif

			# else just normal page so go find it
			} else {
				$cache[$pageid] = Site_Design_Area_Menu::current_page($pageid);
			}
		}
		
		return $cache[$pageid];

	}#end current_page()



	  ###############################################################
	 # this function returns values for the special variables
	# if it is not a predefined variable it uses the value of the variable in this object
	function get_val($name, $pageid=0) {

		if (!$this->_get_val_cache) $this->_get_val_cache = array();

		$session = &get_mysource_session();
		$menu_undocked = $session->get_var('notitia_menu_undocked');

		if ($pageid && preg_match('/([0-9]+):c:([0-9]+):p:([0-9]+)(:fav)?/', $pageid, $matches)) {

			$in_pageid = $pageid;
			$pageid = $matches[1]; # the page that linked to these records
			$cid    = $matches[2]; # the categoryid 
			$pid    = $matches[3]; # the recordid
			$xtras  = $matches[4]; # possible fav tag
			unset($matches);

			$notitia = &$this->get_notitia_system();
			
			# check if we are being asked to get an attribute value
			if ($name && preg_match('/^attribute_([0-9]+)_value_(.*)/', $name, $name_matches)) {
				if ($pid) {
					$record = &$notitia->get_record($pid);
					return $record->get_attribute_formatted_value($name_matches[1], $name_matches[2]);
				}
				return '';
			} else {
				# check to see if this link needs to be popped up, else just let 'page_onclick' handle it
				
				if ($name && preg_match('/isopen_attribute_([0-9]+)_value_(.*)_(.+)_(.+)/', $name, $open_matches)) {
					if($pid) {
						$record = &$notitia->get_record($pid);
						if ($record->get_attribute_formatted_value($open_matches[1], $open_matches[2])) {
						
							$return_link = $this->get_val('page_link', $in_pageid);
							return "Javascript: window.open('".str_replace("'","\\'",$return_link)."', 'favourites', 'width=".$open_matches[3].", height=".$open_matches[4].", scrollbars=yes'); return false;";
						} else {
							$name = 'page_onclick';
						}
					} else {
						$name = 'page_onclick';
					}
				}
			}


			switch($name) {
				case 'page_name' :
				case 'page_short_name' :
					if ($pid) {
						$record = &$notitia->get_record($pid);
						return $record->name();
					} else {
						$cat = &$notitia->get_category($cid);
						return $cat->name;
					}
				break;

				case 'pageid' :
					# we need a unique id for each entry
					return "{$pageid}:c:{$cid}:p:{$pid}{$xtras}";
					break;

				case 'page_link' :
					$return_link = '';
					if ($xtras == ':fav') {
						global $_NOTITIA_MENU;
						if (!isset($_NOTITIA_MENU['favourite_urls'][$pageid][$cid]['records'][$pid]['url'])) {
							$page = &$this->get_page($pageid);
							$return_link = $page->get_href(1).'fav_cid='.$cid.(($pid) ? '&fav_pid='.$pid : '');
						} else {
							$return_link = $_NOTITIA_MENU['favourite_urls'][$pageid][$cid]['records'][$pid]['url'];
						}
					} else {
						$page_link = Site_Design_Area_Menu::get_val('page_link', $pageid);
						$return_link = $page_link.((strchr($page_link, '?')) ? '&' : '?').'cid='.$cid.(($pid) ? '&pid='.$pid : '');
					}
					
					return $return_link;

				break;

				case 'page_onclick' :
					$return_link = $this->get_val('page_link', $in_pageid);

					if ($menu_undocked == $this->temp['menu_id']) {
						return "Javascript: window.opener.location='".str_replace("'","\\'",$return_link)."'; return false;";
					} else {
						return "Javascript: window.location='".str_replace("'","\\'",$return_link)."'; return false;";
					}

				break;

				case 'delete_link' :
					$page = &$this->get_page();
					global $_NOTITIA_MENU;
					$favs = $_NOTITIA_MENU['fav_recordids'];
					return $page->get_href(1).'cid='.$cid.(($pid) ? '&pid='.$pid : '').'&fav_del='.$favs[$in_pageid];
				break;

				case 'delete_onclick' :
					$return_link = $this->get_val('delete_link', $in_pageid);
					if ($menu_undocked == $this->temp['menu_id']) {
						return "Javascript: window.opener.location='".str_replace("'","\\'",$return_link)."'; return false;";
					} else {
						return "Javascript: window.location='".str_replace("'","\\'",$return_link)."'; return false;";
					}

				break;

				case 'remove_favourite' :
					global $_NOTITIA_MENU;
					$favs = $_NOTITIA_MENU['fav_records'];
					if (isset($favs[$in_pageid])) {
						list($level, $index) = split(':',$favs[$in_pageid]);
						return $this->sections[$level][$index]->get_val('remove_favourite_icon');
					} else {
						return '';
					}
				break;

			}
		} else {

			# they might be looking for generic
			# values for the menu - not a page
			switch($name) {
				case 'dock_link' :
					if ($menu_undocked) {
						# a link to dock the menu
						return "Javascript: window.opener.location = '".str_replace("'","\\'",$this->temp['dock_url'])."'; window.close();";
					} else {
						# refresh the page with the current href
						# and tell the menu to undock itself
						return $_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING'].'&undock_notitia_menu='.$this->temp['menu_id'];
					}
				break;
				case 'show_link' :
				$QUERY_STRING = preg_replace('/hide_notitia_menu\=[^&]+/','',$_SERVER['QUERY_STRING']);
					return trim($_SERVER['SCRIPT_NAME'],' ?').'?'.$QUERY_STRING.((trim($QUERY_STRING != '')) ? '&' : '').'hide_notitia_menu='.$this->temp['menu_id'];
				break;
			}
		}

		# lets hope they declared it themselves
		return Site_Design_Area_Menu::get_val($name, $pageid);

	}#end get_val()

	  ################################################
	 # Returns a description of the design area 
	function get_description() {

		$desc = 'Change the appearance of the Notitia Enabled menu system';
		return $desc;

	}#end get_description()

}#end class

?>
