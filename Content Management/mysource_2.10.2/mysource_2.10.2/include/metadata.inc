<?  ##############################################
   ### MySource ------------------------------###
  ##- Include Files ------ PHP4 --------------##
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
## File: include/site.inc
## Desc: Functions for dealing with metadata in the system
## $Source: /home/cvsroot/mysource/include/metadata.inc,v $
## $Revision: 2.11 $
## $Author: brobertson $
## $Date: 2004/01/21 12:26:24 $
#######################################################################

#---------------------------------------------------------------------#

class Metadata extends WebObject {
	
	var $siteid=0;
	var $pageid=0;
	var $keywords='';
	var $active_group='';

	function Metadata($siteid=0, $pageid=0) {
		WebObject::WebObject();
		$this->siteid = $siteid;
		$this->pageid = $pageid;
	}

	function process_backend() {
		$backend = &$this->get_backend();

		# edit group, tag, and tag value names
		$changed_values = array();
		$metadata_names = $_POST['metadata_names'];
		if (!empty($metadata_names)) {
			foreach ($metadata_names as $group => $tags) {
				if (!empty($tags)) {
					foreach ($tags as $tag => $values) {
						if ($tag == 'name') {
							if ($values == '') {
								$backend->add_message($this->delete_metadata_group($group));
								$this->active_group = 'none';
								break;
							} else if ($group != $values) {
								$backend->add_message($this->set_metadata_group_name($group, $values));
								$this->active_group = $values;
								$changed_values['groups'][$group] = $values;
								$group = $values;
							}
							continue;
						}
						if (!empty($values)) {
							foreach ($values as $value => $data) {
								if ($value == 'name') {
									if ($data == '') {
										$backend->add_message($this->delete_metadata_tag($group, $tag));
										break;
									} else if ($tag != $data) {
										$backend->add_message($this->set_metadata_tag_name($group, $tag, $data));
										$changed_values['tags'][$tag] = $data;
										$tag = $data;
									}
									continue;
								}
								if ($data['name'] == '') {
									$backend->add_message($this->delete_metadata_tag_value($group, $tag, $value));
								} else if ($value != $data['name']) {
									$backend->add_message($this->set_metadata_tag_value_name($group, $tag, $value, $data['name']));
									$changed_values['values'][$value] = $data['name'];
								}
							}
						}
					}
				}
			}
		}

		# overwrite selected groups
		$metadata_inherited = $_POST['metadata_inherited'];
		if (!empty($metadata_inherited)) {
			$inherited_groups = $this->get_inherited_metadata_array();
			foreach ($metadata_inherited as $group => $on) {
				$backend->append_message($this->create_metadata_group($group));
				$this->active_group = $group;
				if (!empty($inherited_groups[$group])) {
					foreach ($inherited_groups[$group] as $tag => $data) {
						if ($tmp_msg = $this->create_metadata_tag($group, $tag)) {
							$backend->append_message($tmp_msg);
							if (!empty($data['values'])) {
								foreach ($data['values'] as $name => $default) {
									if (trim($name) == '') $name = 'TAG-DEFAULT';
									if ($name == 'TAG-DEFAULT' || $tmp_msg = $this->create_metadata_tag_value($group, $tag, $name)) {
										$backend->append_message($tmp_msg);
										$backend->append_message($this->set_metadata_tag_value($group, $tag, $name, $default));
									}
								}
							}
							$backend->append_message($this->set_metadata_tag_scheme($group, $tag, $data['scheme']));
							$backend->append_message($this->set_metadata_tag_lang($group, $tag, $data['lang']));
						}
					}
				}
			}
		}
		
		# create new metadata group
		$new_metadata_group = trim($_POST['new_metadata_group']);
		$new_metadata_standard = trim($_POST['new_metadata_standard']);
		if ($new_metadata_group) {
			$backend->add_message($this->create_metadata_group($new_metadata_group, (($new_metadata_standard != 'none') ? $new_metadata_standard : '')));
			$backend->add_message('Site Metadata Updated');
			$this->active_group = $new_metadata_group;
		}

		# create new tags
		$new_metadata_tags = $_POST['new_metadata_tag'];
		if (!empty($new_metadata_tags)) {
			foreach ($new_metadata_tags as $group => $name) {
				if (isset($changed_values['groups'][$group])) $group = $changed_values['groups'][$group];
				$backend->add_message($this->create_metadata_tag($group, $name));
				$this->active_group = $group;
			}
		}

		# create new tag values
		$new_metadata_tag_values = $_POST['new_metadata_tag_value'];
		if (!empty($new_metadata_tag_values)) {
			foreach ($new_metadata_tag_values as $group => $tags) {
				if (isset($changed_values['groups'][$group])) $group = $changed_values['groups'][$group];
				if (!empty($tags)) {
					foreach ($tags as $tag => $name) {
						if (isset($changed_values['tags'][$tag])) $tag = $changed_values['tags'][$tag];
						$backend->add_message($this->create_metadata_tag_value($group, $tag, $name));
					}
				}
			}
		}

		# set tag value contents
		$metadata_value_values = $_POST['metadata_value_values'];
		if (!empty($metadata_value_values)) {
			foreach ($metadata_value_values as $group => $tags) {
				if (!$group) continue;
				if (isset($changed_values['groups'][$group])) $group = $changed_values['groups'][$group];
				if (!empty($tags)) {
					foreach ($tags as $tag => $values) {
						if (!$tag) continue;
						if (isset($changed_values['tags'][$tag])) $tag = $changed_values['tags'][$tag];
						if (!empty($values)) {
							foreach ($values as $value => $contents) {
								if (!$value) continue;
								if (isset($changed_values['values'][$value])) $value = $changed_values['values'][$value];
								$backend->add_message($this->set_metadata_tag_value($group, $tag, $value, $contents));
							}
						}
					}
				}
			}
		}

		# set tag scheme
		$metadata_tag_schemes = $_POST['metadata_tag_schemes'];
		if (!empty($metadata_tag_schemes)) {
			foreach ($metadata_tag_schemes as $group => $tags) {
				if (!$group) continue;
				if (isset($changed_values['groups'][$group])) $group = $changed_values['groups'][$group];
				if (!empty($tags)) {
					foreach ($tags as $tag => $contents) {
						if (!$tag) continue;
						if (isset($changed_values['tags'][$tag])) $tag = $changed_values['tags'][$tag];
						$backend->add_message($this->set_metadata_tag_scheme($group, $tag, $contents));
					}
				}
			}
		}

		# set tag language
		$metadata_tag_langs = $_POST['metadata_tag_langs'];
		if (!empty($metadata_tag_langs)) {
			foreach ($metadata_tag_langs as $group => $tags) {
				if (!$group) continue;
				if (isset($changed_values['groups'][$group])) $group = $changed_values['groups'][$group];
				if (!empty($tags)) {
					foreach ($tags as $tag => $contents) {
						if (!$tag) continue;
						if (isset($changed_values['tags'][$tag])) $tag = $changed_values['tags'][$tag];
						$backend->add_message($this->set_metadata_tag_lang($group, $tag, $contents));
					}
				}
			}
		}
		$backend->set_hidden_field('metadata_group',$this->active_group);
	}

	function print_backend() {
		$backend = &$this->get_backend();
		$print_group = trim($this->active_group);

		$groups = $this->get_metadata_array();
		$inherited_groups = $this->get_inherited_metadata_array();

		if (!empty($groups) || !empty($inherited_groups)) {
			# using our local groups and the ones higher up in the
			# hierarchy, work out which groups we are inheriting
			$current_groups = array_keys($groups);
			$inherited = array_keys($inherited_groups);
			$overwritten_groups = array();
			if (!empty($inherited)) {
				foreach ($inherited as $group) {
					if (in_array($group, $current_groups)) {
						$overwritten_groups[] = $group;
						unset($inherited_groups[$group]);
					}
				}
			}
		}

		if ($print_group == '' || $print_group == 'none' || !in_array($print_group, array_keys($groups))) {
			$backend->open_section('Instructions');
			$backend->open_field('Keyword Replacments');
			?>
			<span class=backend_fineprint>
			You can use these keyword replacements in all editable values on this screen.
			<ul>
			<li class=backend_fineprint>%page_name% - Full name of current page</li>
			<li class=backend_fineprint>%page_short_name% - Short name of current page</li>
			<li class=backend_fineprint>%page_description% - Description of current page</li>
			<li class=backend_fineprint>%page_keywords% - Keywords of current page</li>
			<li class=backend_fineprint>%page_create_date% - Date/Time the page was created (YYYY-MM-DD HH-MM-SS)</li>
			<li class=backend_fineprint>%page_last_update% - Date/Time the page was last updated (YYYY-MM-DD HH-MM-SS)</li>
			<li class=backend_fineprint>%page_url% - The url of the current page with virtual paths</li>
			<li class=backend_fineprint>%page_unique_url% - The url of the current page without virtual paths (<i>./?p=xx</i>)</li>
			<li class=backend_fineprint>%page_admins% - Comma seperated list of page admins</li>
			<li class=backend_fineprint>%page_editors% - Comma seperated list of page editors</li>
			</ul>
			<ul>
			<li class=backend_fineprint>%parent_name% - Full name of current page's parent page</li>
			<li class=backend_fineprint>%parent_short_name% - Short name of current page's parent page</li>
			<li class=backend_fineprint>%parent_description% - Description of current page's parent page</li>
			<li class=backend_fineprint>%parent_keywords% - Keywords of current page's parent page</li>
			<li class=backend_fineprint>%parent_create_date% - Date/Time the page's parent was created (YYYY-MM-DD HH-MM-SS)</li>
			<li class=backend_fineprint>%parent_last_update% - Date/Time the page's parent was last updated (YYYY-MM-DD HH-MM-SS)</li>
			<li class=backend_fineprint>%parent_url% - The url of the current page's parent with virtual paths</li>
			<li class=backend_fineprint>%parent_unique_url% - The url of the current page's parent without virtual paths (<i>./?p=xx</i>)</li>
			</ul>
			<ul>
			<li class=backend_fineprint>%site_name% - Name of current site</li>
			<li class=backend_fineprint>%site_url% - The url of the current site</li>
			<li class=backend_fineprint>%site_description% - Description of current site</li>
			<li class=backend_fineprint>%site_last_update% - Date/Time the site was last updated (YYYY-MM-DD HH-MM-SS)</li>
			<li class=backend_fineprint>%site_admins% - Comma seperated list of site admins</li>
			<li class=backend_fineprint>%site_editors% - Comma seperated list of site editors</li>
			</ul>
			<ul>
			<li class=backend_fineprint>%webmaster_email% - Email of the MySource webmaster</li>
			<li class=backend_fineprint>%system_name% - Name of this MySource system</li>
			<li class=backend_fineprint>%system_owner% - Name of the MySource system owner</li>
			</ul>
			</span>
			<?

			$backend->open_section('New Metadata Group');
			$backend->open_field('&nbsp;');
			$metadata_config = &get_config('metadata');
			$metadata_options = &$metadata_config->standards;
			$metadata_options['none'] = ' -- use template --';
			$metadata_options = array_reverse($metadata_options);
			echo text_box('new_metadata_group','',30);
			echo '&nbsp;';
			echo combo_box('new_metadata_standard',$metadata_options,'none');
		
			$backend->open_section('Inherited Metadata Groups');
			if (!empty($inherited_groups)) {
				foreach ($inherited_groups as $group => $tags) {
					$backend->open_field($group,'one_liner');
					?><input type="checkbox" name="metadata_inherited[<?=$group?>]"> overwrite?<?
				}
			} else {
				$backend->open_field('There are no inherited metadata groups','one_liner');
			}
			
			if (!empty($overwritten_groups)) {
				$backend->open_field('You are currently overwriting the following metadata groups:');
				foreach ($overwritten_groups as $group) {
					echo "$group<br>";
				}
				echo '<span class=backend_fineprint>You are defining your own values for these groups. To use the inherited defaults, delete the group on this screen.</span>';
			} else {
				$backend->open_field('You are not overwritting any metadata groups','one_liner');
			}
			return;
		}

		$backend->open_section("Metadata Group - $print_group");
		if (!empty($groups)) {
			foreach ($groups as $group => $tags) {
				if (trim($group) == '') continue;
				if ($group != $print_group) continue;
				$backend->open_field('&nbsp;', 'one_liner');
				echo text_box("metadata_names[$group][name]",$group,30).'<br>';
				?>
				<table border="0">
					<tr>
						<td><img src="<?=$backend->rel_path?>/images/blank.gif" width="15" height="1"></td>
						<td class="backend_fineprint"><b>Tags</b>&nbsp;New? <?= text_box("new_metadata_tag[$group]",'',30) ?></td>
					</tr>
				<?
				if (empty($tags)) {
					?>
					<tr>
						<td colspan="2">
							None.
						</td>
					</tr>
					<?
				} else {
					foreach ($tags as $tag => $data) {
						if (trim($tag) == '') continue;
						?>
						<tr>
							<td><img src="<?=$backend->rel_path?>/images/blank.gif" width="15" height="1"></td>
							<td class="backend_fineprint" valign="top">
								<?= text_box("metadata_names[$group][$tag][name]",$tag, 30) ?><br>
								<?= text_area("metadata_value_values[$group][$tag][TAG-DEFAULT]",$data['content'],45,3) ?><br>
								SCHEME: <?= text_box("metadata_tag_schemes[$group][$tag]",$data['scheme'], 10) ?>&nbsp;
								LANG: <?= text_box("metadata_tag_langs[$group][$tag]",$data['lang'], 4) ?>
								<?
								$num_per_row = 3;
								$num_done = 0;
								$started = false;
								?>
								<table border="0" cellspacing="3" cellpadding="3">
									<tr>
										<td><img src="<?=$backend->rel_path?>/images/blank.gif" width="15" height="1"></td>
										<td class="backend_fineprint" colspan="<?=$num_per_row?>"><b>Value Components</b>&nbsp;New? <?= text_box("new_metadata_tag_value[$group][$tag]",'',30) ?></td>
									</tr>
								<?
								if (!empty($data['values'])) {
									foreach ($data['values'] as $name => $val) {
										if (trim($name) == '') continue;
										if (!$started) {
											$started = true;
											?>
											<tr>
												<td><img src="<?=$backend->rel_path?>/images/blank.gif" width="15" height="1"></td>
											<?
										}
										echo '<td valign="top">';
										echo text_box("metadata_names[$group][$tag][$name][name]",$name, 30);
										echo '<br>';
										echo text_area("metadata_value_values[$group][$tag][$name]",$val, 45,3);
										echo '</td>';

										$num_done++;
										if ($num_done >= $num_per_row) {
											$num_done = 0;
											?>
											</tr>
											<tr>
												<td><img src="<?=$backend->rel_path?>/images/blank.gif" width="15" height="1"></td>
											<?
										}
									}
								}
								for ($i = $num_done; $i > 0; $i--) echo'<td>&nbsp;</td>';
								?>
								</tr>
								</table>
							</td>
						</tr>
						<?
					}
				}
				?></table><?
			}
		}
	}

	function set_backend_tabs() {
		# we want post before get
		if (!$this->active_group) $this->active_group = $_POST['metadata_group'];
		if (!$this->active_group) $this->active_group = $_GET['metadata_group'];
		$backend = &$this->get_backend();
		$groups = $this->get_metadata_groups();
		if (!empty($groups)) {
			foreach ($groups as $group) {
				if (!$this->pageid) {
					$backend->set_tab("site_metadata_{$group}", 'site.php?s='.$this->siteid."&PARAMETER_SCREEN=site_metadata&metadata_group={$group}", "Metadata - $group", "Manage the Metadata for the $group group.",'page');
					if ($this->active_group == $group) $backend->set_active_tab("site_metadata_{$group}");
				} else {
					$backend->set_tab("page_metadata_{$group}", 'page.php?p='.$this->pageid."&PARAMETER_SCREEN=page_metadata&metadata_group={$group}", "Metadata - $group", "Manage the Metadata for the $group group.",'page');
					if ($this->active_group == $group) $backend->set_active_tab("page_metadata_{$group}");
				}
			}
		}
	}

	function print_frontend($print_group='all') {
		$groups = $this->prepare_group($print_group);

		if (trim($print_group) != 'all') {
			# we only want to print one group
			# but check if it exists
			if (isset($groups[$print_group])) {
				$this->print_frontend_group_tags($print_group, $groups[$print_group]);
			}
		} elseif ($print_group == 'all') {
			# we need to print all groups
			if (!empty($groups)) {
				foreach ($groups as $group => $data) {
					$this->print_frontend_group_tags($group, $data);
				}
			}
		}
	}


	function prepare_group($print_group='') {
		# work out keyword replacements
		$web_system = &get_web_system();
		$users_system = &get_users_system();
		$page   = &$web_system->get_page($this->pageid);
		$parent = &$web_system->get_page($page->parentid);
		$site   = &$web_system->get_site($page->siteid);

		$this->keywords = array();
		$this->keywords['page_name'] = $page->name;
		$this->keywords['page_short_name'] = $page->short_name;
		$this->keywords['page_description'] = $page->description;
		$this->keywords['page_keywords'] = $page->keywords;
		$this->keywords['page_create_date'] = $page->create_date;
		$this->keywords['page_last_update'] = $page->last_update;
		$this->keywords['page_url'] = $page->get_url();
		$this->keywords['site_name'] = $site->name;
		$this->keywords['site_description'] = $site->description;
		$this->keywords['site_last_update'] = $site->last_update;
		$this->keywords['site_url'] = $site->urls[0];

		$this->keywords['page_unique_url'] = $this->keywords['site_url'].'?p='.$page->pageid;
		$this->keywords['parent_unique_url'] = $this->keywords['site_url'].'?p='.$parent->pageid;

		$this->keywords['parent_name'] = $parent->name;
		$this->keywords['parent_short_name'] = $parent->short_name;
		$this->keywords['parent_description'] = $parent->description;
		$this->keywords['parent_keywords'] = $parent->keywords;
		$this->keywords['parent_create_date'] = $parent->create_date;
		$this->keywords['parent_last_update'] = $parent->last_update;
		$this->keywords['parent_url'] = $parent->get_url();
		if (!empty($site->adminids)) {
			foreach($site->adminids as $userid) {
				$user = &$users_system->get_user($userid);
				$this->keywords['site_admins'] .= $user->name().', ';
			}
			$this->keywords['site_admins'] = substr($this->keywords['site_admins'],0,-2);
		}

		if (!empty($site->editorids)) {
			foreach($site->editorids as $userid) {
				$user = &$users_system->get_user($userid);
				$this->keywords['site_editors'] .= $user->name().', ';
			}
			$this->keywords['site_editors'] = substr($this->keywords['site_editors'],0,-2);
		}
		if (!empty($page->adminids)) {
			foreach($page->adminids as $userid) {
				$user = &$users_system->get_user($userid);
				if (!$user->id) continue;
				$this->keywords['page_admins'] .= $user->name().', ';
			}
			$this->keywords['page_admins'] = substr($this->keywords['page_admins'],0,-2);
		}
		if (!empty($page->editorids)) {
			foreach($page->editorids as $userid) {
				$user = &$users_system->get_user($userid);
				if (!$user->id) continue;
				$this->keywords['page_editors'] .= $user->name().', ';
			}
			$this->keywords['page_editors'] = substr($this->keywords['page_editors'],0,-2);
		}


		$config = &get_config('mysource');
		$this->keywords['webmaster_email'] = $config->webmaster_email;
		$this->keywords['system_name'] = $config->system_name;
		$this->keywords['system_owner'] = $config->system_owner;

		$groups = $this->get_metadata_array();
		$inherited_groups = $this->get_inherited_metadata_array();
		if (!empty($groups) || !empty($inherited_groups)) {
			# using our local groups and the ones higher up in the
			# hierarchy, work out which groups we are inheriting
			if (is_array($groups)) {
				$current_groups = array_keys($groups);
			} else {
				$current_groups = array();
			}
			if (is_array($inherited_groups)) {
				$inherited = array_keys($inherited_groups);
			} else {
				$inherited = array();
			}
			if (!empty($inherited)) {
				foreach ($inherited as $group) {
					if (in_array($group, $current_groups)) unset($inherited_groups[$group]);
				}
			}
			$groups = array_merge($groups,$inherited_groups);
		} 
		unset($inherited_groups);

		# okay, we now have $groups which contains all the groups
		# we can print and the correct values
		return $groups;
	}


	function print_frontend_group_tags($group='', $format='') {
		global $INCLUDE_PATH;
		include_once("$INCLUDE_PATH/text.inc");
		$format = replace_keywords($format, $this->keywords);
		if (!empty($format)) {
			foreach ($format as $tag => $data) {
				if (trim($tag == '')) continue;
				echo '<meta NAME="'.$tag.'"';
				if (trim($data['lang']) != '') echo ' LANG="'.$data['lang'].'"';
				if (trim($data['scheme']) != '') echo ' SCHEME="'.$data['scheme'].'"';

				$content_string = '';
				$default_stirng = '';
				if (!empty($data['values'])) {
					foreach ($data['values'] as $value => $default) {
						if ($value == '') {
							$default_string = $default;
						} else {
							$content_string .= "$value=$default; ";
						}
					}
				}
				echo ' CONTENT="';
				if (trim($default_string) != '') {
					echo trim($default_string);
					if (trim($content_string) != '') echo '; ';
				}
				if (trim($content_string) != '') echo trim($content_string);
				echo "\">\n";
			}
		}
	}


	function print_frontend_group_rdf($group='', $format='') {
		global $INCLUDE_PATH;
		include_once("$INCLUDE_PATH/text.inc");
		$format = replace_keywords($format, $this->keywords);
		if (!empty($format)) {
			foreach ($format as $tag => $data) {
				if (trim($tag == '')) continue;
				echo '<'.str_replace('.',':',strtolower($tag)).'>';

				$content_string = '';
				$default_stirng = '';
				if (!empty($data['values'])) {
					foreach ($data['values'] as $value => $default) {
						if ($value == '') {
							$default_string = $default;
						} else {
							$content_string .= "$value $default, ";
						}
					}
				}
				if (trim($default_string) != '') {
					echo trim($default_string);
					if (trim($content_string) != '') echo ', ';
				}
				if (trim($content_string) != '') echo htmlentities(trim($content_string));
				echo '</'.str_replace('.',':',strtolower($tag)).">\n";
			}
		}
	}


	function print_frontend_group_xml($group='', $format='', $xtra='') {
		global $INCLUDE_PATH;
		include_once("$INCLUDE_PATH/text.inc");
		$format = replace_keywords($format, $this->keywords);

		$group_prefix = false;
		if (!empty($format)) {
			foreach ($format as $tag => $data) {
				if (trim($tag == '')) continue;
				if (!$group_prefix) {
					list($group_prefix) = explode('.',strtolower($tag));
					echo "<$group_prefix".(($xtra) ? " $xtra" : '').">\n";
				}
				$tag_prefix = str_replace($group_prefix.'.','',strtolower($tag));
				echo "<$tag_prefix>";

				$content_string = '';
				$default_stirng = '';
				if (!empty($data['values'])) {
					foreach ($data['values'] as $value => $default) {
						if ($value == '') {
							$default_string = $default;
						} else {
							$content_string .= "$value $default, ";
						}
					}
				}
				if (trim($default_string) != '') {
					echo trim($default_string);
					if (trim($content_string) != '') echo ', ';
				}
				if (trim($content_string) != '') echo htmlentities(trim($content_string));
				echo "</$tag_prefix>\n";
			}
		}
		echo "</$group_prefix>\n";
	}


	function get_metadata_array($siteid=-1, $pageid=-1) {
		if ($siteid < 0) $siteid = $this->siteid;
		if ($pageid < 0) $pageid = $this->pageid;

		$db = &$this->get_db();
		$data = $db->associative_array3("SELECT group_name, tag_name, value_name, content FROM meta_data WHERE siteid='$siteid' AND pageid='$pageid' ORDER BY group_name, tag_name, value_name");
		$retval = $data;
		if (!empty($data)) {
			foreach ($data as $group => $tags) {
				if (!empty($tags)) {
					foreach ($tags as $tag => $values) {
						$retval[$group][$tag] = array();
						$retval[$group][$tag]['values'] = $data[$group][$tag];
						list($retval[$group][$tag]['scheme'],$retval[$group][$tag]['lang'],$retval[$group][$tag]['content']) = $db->single_row("SELECT tag_scheme, tag_lang, content FROM meta_data WHERE siteid='$siteid' AND pageid='$pageid' AND group_name='".addslashes($group)."' AND tag_name='".addslashes($tag)."'");
					}
				}
			}
		}
		return $retval;
	}

	function get_inherited_metadata_array($siteid=-1, $pageid=-1) {
		if ($siteid < 0) $siteid = $this->siteid;
		if ($pageid < 0) $pageid = $this->pageid;

		$retval = array();
		$db = &$this->get_db();
		if ($pageid <= 0) {
			if ($this->pageid > 0) $retval = $this->get_metadata_array($siteid, 0);
		} else {
			# a page
			$page = &$this->web_system->get_page($pageid);
			if (!$page->id) return array();
			if ($page->id != $this->pageid) {
				# grab some metadata
				$retval = $this->get_metadata_array($page->siteid, $page->id);
			}
			$inherited = $this->get_inherited_metadata_array($page->siteid, $page->parentid);
			if (!empty($inherited)) {
				foreach ($inherited as $group => $data) {
					if (!isset($retval[$group])) $retval[$group] = $data;
				}
			}
			$this->web_system->forget_page($pageid);
		}
		return $retval;
	}


	function update_page() {
		$web = &get_web_system();
		$page = $web->get_page($this->pageid);
		if ($page>id) $page->updated();
	}

	function create_metadata_group($name='',$standard='') {
		if (trim($name) == '') return;
		$db = &$this->get_db();
		$backend = &$this->get_backend();
		$current_groups = $this->get_metadata_groups();
		if (in_array($name,$current_groups) || !empty($current_groups)) {
			return array(MYSOURCE_ERROR_CODE_ERROR, 'Unable to create Metadata group, group already exists');
		} else {
			if (!$db->insert("INSERT INTO meta_data (siteid, pageid, group_name) VALUES ('$this->siteid','$this->pageid','".addslashes($name)."')")) {
				return array(MYSOURCE_ERROR_CODE_ERROR, 'Unable to create Metadata Group, database insert failed');
			} else {
				$backend->append_message(MYSOURCE_ERROR_CODE_NONE, "Metadata group $name created");
				$this->update_page();
				if (trim($standard) != '') {
					# populate the new group with the standards
					$this->populate_metadata_group($name, $standard);
				}
			}
		}
	}

	function set_metadata_group_name($group='', $name='') {
		if (trim($group) == '' || trim($name) == '') return;
		$db = &$this->get_db();
		if (!$db->update("UPDATE meta_data SET group_name='".addslashes($name)."' WHERE siteid='$this->siteid' AND pageid='$this->pageid' AND group_name='".addslashes($group)."'")) {
			return array(MYSOURCE_ERROR_CODE_ERROR, 'Unable to update Metadata group name, database update failed');
		} else {
			return array(MYSOURCE_ERROR_CODE_NONE, "Metadata group name change from $group to $name");
			$this->update_page();
		}
	}

	function delete_metadata_group($group='') {
		if (trim($group) == '') return;
		$db = &$this->get_db();
		if (!$db->delete("DELETE FROM meta_data WHERE siteid='$this->siteid' AND pageid='$this->pageid' AND group_name='".addslashes($group)."'")) {
			return array(MYSOURCE_ERROR_CODE_ERROR, "Unable to delete Metadata group $group, database update failed");
		} else {
			return array(MYSOURCE_ERROR_CODE_NONE, "Metadata group $group deleted");
			$this->update_page();
		}
	}


	function create_metadata_tag($group='', $name='') {
		if (trim($name) == '' || trim($group) == '') return;
		$db = &$this->get_db();
		$current_tags = $this->get_metadata_tags($group);
		if (in_array($name,$current_tags)) {
			return array(MYSOURCE_ERROR_CODE_ERROR, 'Unable to create Metadata tag, tag already exists');
		} else {
			if (!$db->insert("INSERT INTO meta_data (siteid, pageid, group_name, tag_name, tag_scheme, tag_lang) VALUES ('$this->siteid','$this->pageid','".addslashes($group)."','".addslashes($name)."','','')")) {
				return array(MYSOURCE_ERROR_CODE_ERROR, "Unable to create Metadata tag $name, database insert failed");
			} else {
				return array(MYSOURCE_ERROR_CODE_NONE, "Metadata tag $name created");
				$this->update_page();
			}
		}
	}

	function delete_metadata_tag($group='', $tag='') {
		if (trim($group) == '' || trim($tag) == '') return;
		$db = &$this->get_db();
		if (!$db->delete("DELETE FROM meta_data WHERE siteid='$this->siteid' AND pageid='$this->pageid' AND group_name='".addslashes($group)."' AND tag_name='".addslashes($tag)."'")) {
			return array(MYSOURCE_ERROR_CODE_ERROR, "Unable to delete Metadata tag $tag, database update failed");
		} else {
			return array(MYSOURCE_ERROR_CODE_NONE, "Metadata tag $tag deleted");
			$this->update_page();
		}
	}

	function set_metadata_tag_name($group='', $tag='', $name='') {
		if (trim($group) == '' || trim($tag) == '' || trim($name) == '') return;
		$db = &$this->get_db();
		if (!$db->update("UPDATE meta_data SET tag_name='".addslashes($name)."' WHERE siteid='$this->siteid' AND pageid='$this->pageid' AND group_name='".addslashes($group)."' AND tag_name='".addslashes($tag)."'")) {
			return array(MYSOURCE_ERROR_CODE_ERROR, 'Unable to update Metadata tag name, database update failed');
		} else {
			return array(MYSOURCE_ERROR_CODE_NONE, "Metadata tag name change from $tag to $name");
			$this->update_page();
		}
	}


	function create_metadata_tag_value($group='', $tag='', $name='') {
		if (trim($name) == '' || trim($group) == '' || trim($tag) == '') return;
		$db = &$this->get_db();
		$current_values = $this->get_metadata_tag_values($group, $tag);
		if (in_array($name,$current_values)) {
			return array(MYSOURCE_ERROR_CODE_ERROR, 'Unable to create Metadata tag value, tag value already exists');
		} else {
			if (!$db->insert("INSERT INTO meta_data (siteid, pageid, group_name, tag_name, value_name, content) VALUES ('$this->siteid','$this->pageid','".addslashes($group)."','".addslashes($tag)."','".addslashes($name)."','')")) {
				return array(MYSOURCE_ERROR_CODE_ERROR, 'Unable to create Metadata tag value, database insert failed');
			} else {
				return array(MYSOURCE_ERROR_CODE_NONE, "Metadata tag value $name created");
				$this->update_page();
			}
		}
	}

	function delete_metadata_tag_value($group='', $tag='', $value='') {
		if (trim($group) == '' || trim($tag) == '' || trim($value) == '') return;
		$db = &$this->get_db();
		if (!$db->delete("DELETE FROM meta_data WHERE siteid='$this->siteid' AND pageid='$this->pageid' AND group_name='".addslashes($group)."' AND tag_name='".addslashes($tag)."' AND value_name='".addslashes($value)."'")) {
			return array(MYSOURCE_ERROR_CODE_ERROR, "Unable to delete Metadata tag value $value, database update failed");
		} else {
			return array(MYSOURCE_ERROR_CODE_NONE, "Metadata tag value $value deleted");
			$this->update_page();
		}
	}


	function set_metadata_tag_value_name($group='', $tag='', $value='', $name='') {
		if (trim($group) == '' || trim($tag) == '' || trim($value) == '' || trim($name) == '') return;
		$db = &$this->get_db();
		if (!$db->update("UPDATE meta_data SET value_name='".addslashes($name)."' WHERE siteid='$this->siteid' AND pageid='$this->pageid' AND group_name='".addslashes($group)."' AND tag_name='".addslashes($tag)."' AND value_name='".addslashes($value)."'")) {
			return array(MYSOURCE_ERROR_CODE_ERROR, 'Unable to update Metadata tag value name, database update failed');
		} else {
			return array(MYSOURCE_ERROR_CODE_NONE, "Metadata tag value name change from $value to $name");
			$this->update_page();
		}
	}

	function set_metadata_tag_value($group='', $tag='', $value='', $content='') {
		if (trim($group) == '' || trim($tag) == '' || trim($value) == '') return;
		$db = &$this->get_db();
		if (!$db->update("UPDATE meta_data SET content='".addslashes($content)."' WHERE siteid='$this->siteid' AND pageid='$this->pageid' AND group_name='".addslashes($group)."' AND tag_name='".addslashes($tag)."' AND value_name='".(($value != 'TAG-DEFAULT') ? addslashes($value) : '')."' AND content<>'".addslashes($content)."'")) {
			return;
		} else {
			return array(MYSOURCE_ERROR_CODE_NONE, "Metadata tag value $value content updated");
			$this->update_page();
		}
	}

	function set_metadata_tag_scheme($group='', $tag='', $content='') {
		if (trim($group) == '' || trim($tag) == '') return;
		$db = &$this->get_db();
		if (!$db->update("UPDATE meta_data SET tag_scheme='".addslashes($content)."' WHERE siteid='$this->siteid' AND pageid='$this->pageid' AND group_name='".addslashes($group)."' AND tag_name='".addslashes($tag)."' AND value_name=''")) {
			return;
		} else {
			return array(MYSOURCE_ERROR_CODE_NONE, "Metadata tag scheme updated");
			$this->update_page();
		}
	}

	function set_metadata_tag_lang($group='', $tag='', $content='') {
		if (trim($group) == '' || trim($tag) == '') return;
		$db = &$this->get_db();
		if (!$db->update("UPDATE meta_data SET tag_lang='".addslashes($content)."' WHERE siteid='$this->siteid' AND pageid='$this->pageid' AND group_name='".addslashes($group)."' AND tag_name='".addslashes($tag)."' AND value_name=''")) {
			return;
		} else {
			return array(MYSOURCE_ERROR_CODE_NONE, "Metadata tag language updated");
			$this->update_page();
		}
	}





	function populate_metadata_group($group='', $format='') {
		if (trim($group) == '' || trim($format) == '') return;
		# get the metadata
		$metadata_config = &get_config('metadata');
		$metadata_config->type = $format;

		$metadata_config->load();
		$standard = $metadata_config->format;

		$backend = &$this->get_backend();
		if (!empty($standard)) {
			foreach ($standard as $tag => $data) {
				if ($tmp_msg = $this->create_metadata_tag($group, $tag)) {
					$backend->append_message($tmp_msg);
					if (is_array($data) && !empty($data)) {
						foreach ($data as $name => $default) {
							if ($name == 'TAG-DEFAULT' || $tmp_msg = $this->create_metadata_tag_value($group, $tag, $name)) {
								$backend->append_message($tmp_msg);
								if (!empty($default)) {
									foreach ($default as $value => $temp) {
										if ($tmp_msg = $this->set_metadata_tag_value($group, $tag, $name, $value)) {
											$backend->append_message($tmp_msg);
										}
										break;
									}
								}
							}
						}
					}
				}
			}
		}
	}

	function get_all_metadata_groups() {
		$db = &$this->get_db();
		return $db->single_column("SELECT DISTINCT(group_name) FROM meta_data");
	}

	function get_metadata_groups() {
		$db = &$this->get_db();
		return $db->single_column("SELECT DISTINCT(group_name) FROM meta_data WHERE siteid='$this->siteid' AND pageid='$this->pageid'");
	}

	function get_metadata_tags($group='') {
		$db = &$this->get_db();
		return $db->single_column("SELECT DISTINCT(tag_name) FROM meta_data WHERE siteid='$this->siteid' AND pageid='$this->pageid' AND group_name='".addslashes($group)."'");
	}

	function get_metadata_tag_values($group='', $tag='') {
		$db = &$this->get_db();
		return $db->single_column("SELECT DISTINCT(value_name) FROM meta_data WHERE siteid='$this->siteid' AND pageid='$this->pageid' AND group_name='".addslashes($group)."' AND tag_name='".addslashes($tag)."'");
	}
}
?>