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
## $Source: /home/cvsroot/xtras/page/templates/sub_page/sub_page.inc,v $
## $Revision: 1.37.2.1 $
## $Author: achadszinow $
## $Date: 2004/04/21 02:18:24 $
## Desc: An extension to the standard page, this lists subpages as well.
#######################################################################
global $INCLUDE_PATH, $SQUIZLIB_PATH;
include_once("$INCLUDE_PATH/page_template.inc");
include_once("$SQUIZLIB_PATH/bodycopy/bodycopy.inc");
require_once("$INCLUDE_PATH/parameter_set.inc");
#---------------------------------------------------------------------#

class Page_Template_Sub_Page extends Page_Template {

	var $parameters = array();
	var $_page_copy_keywords;
	var $_bodycopy_keywords;

	 ##############################
	# Constructor
	function Page_Template_Sub_Page($pageid) {
		return Page_Template::Page_Template($pageid);
	}

	 ######################################################################
	# Create a new record in the databse and load it into this object
	function create($pageid) {
		$message = Page_Template::create($pageid);
		if(!$this->id) return false;
		$db = &$this->get_db();
		# Set the title to the title of the current page.
		$page = &$this->get_page($pageid);

		# set some defaults
		$this->parameters['title']= $page->name;

		$this->parameters['popup_window']['on'] = "off";

		$db->update("UPDATE $this->table_name SET parameters = '".addslashes(serialize($this->parameters))."' WHERE pageid='$pageid'");
		$id = $this->load($pageid);
		return $message;
	}

	/**
	* Loads the template details, from cache or database
	*
	* @param   integer $pageid The ID of the page that this template is on
	* @returns mixed
	* @access  public
	*/
	function load($pageid) {
		if ($pageid <= 0 && !($pageid = $this->id)) { # Tries "re"loading
			$this->_set_error("Attempt to load $this->name page template without a valid pageid.",__FILE__,__LINE__);
		}

		 #################
		# Check the cache
		if ($this->load_from_cache($pageid)) return $pageid;

		 ########################
		# Load from the database
		$db = &$this->get_db();

		 ########################
		# Load from the database
		$db = &$this->get_db();
		list($this->id, $tmp) = $db->single_row("SELECT pageid, parameters FROM $this->table_name WHERE pageid='$pageid'");
		$this->parameters = unserialize($tmp);

		 #####################################
		# Create a new record if none exists
		if (!$this->id) {
			return $this->create($pageid);
		}

		$this->set_generated_information();
		$this->set_temp_information();

		 ###################
		# Save to the cache
		$this->save_to_cache($pageid);
		return $pageid;
	}

	 #################################################################################
	# Provides an associative array of data that other templates might like to import
	function export_data() {
		return array('title'=>$this->paramaters['title'],'bodycopy'=>$this->parameters['bodycopy']);
	}


	 #################################################################################
	# Provides an associative array of data that other templates might like to import
	function import_data(&$data) {
		foreach($data as $code => $datum) {
			$code = strtolower(eregi_replace('[^a-z]+','',$code));
			if ($code == title || $code == name) {
				$this->parameters['title'] = $datum;
			}
			if ($code == bodycopy) {
				$this->parameters['bodycopy'] = $datum;
			}
		}
		$db = &$this->get_db();
		$db->update("UPDATE $this->table_name SET parameters ='".addslashes(serialize($this->parameters))."' WHERE pageid='$this->id'");

		$this->clear_cache($this->id);
	}


	 ##############################################
	# Grabs keywords out of the title and bodycopy
	function extract_keywords() {
		$bodycopy = new BodyCopy($this->parameters['bodycopy'], 'bodycopy');
		return $this->parameters['title'].' '.strip_tags_padded($bodycopy->get_html());
	}


	 ###########################################
	# Sets/Resets info in the temporary array
	function set_temp_information() {
		$page_copy = new BodyCopy($this->parameters['page_copy'], 'page_copy');
		$this->_page_copy_keywords = $page_copy->get_keywords();
		$bodycopy = new BodyCopy ($this->parameters['bodycopy'], 'bodycopy');
		$this->_bodycopy_keywords = $bodycopy->get_keywords();
	}

	/**
	* List of design areas I conflict with - will stop the information printing out twice (once by me and once by the design area)
	*
	* @access public
	* @return array
	*/
	function conflicts_with_designs() {
		return array('subpage_list');
	}

	/**
	* Returns the popup window on setting
	*
	* @access public
	* @return boolean
	*/
	function is_popup_window_on() {
		return $this->parameters['popup_window']['on'];
	}

	/**
	* Prints the backend for the user - usually completely overwritten
	*
	* @access public
	* @return void
	*/
	function print_backend() {
		$db = &$this->get_db();
		$backend = &$this->setup_backend();
		$backend->set_hidden_field('action');
		$pset = &$this->get_pset();
		$pset->set_tabs($backend,'template_screen', $this->get_backend_href());
		if($pset->process($backend)) {
			$this->save_parameters();
		}

		# prints the rest of the tabs at the top
		$pset->print_backend($backend);
		return;
	}

	/**
	* Gets an array of sites
	*
	* @access public
	* @return array
	*/
	function backend_get_site_array() {
		$session = &get_mysource_session();
		$site = &$this->get_site();
		$sites = $this->web_system->get_editable_sites($session->user->id);
		$sites[$site->id] = '[THIS SITE] '.$sites[$site->id];
		return $sites;
	}

	/**
	* Change the root_siteid of the page
	*
	* @access public
	* @return array
	*/
	function backend_set_root_site($root_siteid, $found_in_post_vars) {
		if (!$found_in_post_vars) return;
		if ($this->parameters['root_siteid'] == $root_siteid) return;
		$this->parameters['root_siteid'] = $root_siteid;
		$this->temp['root_siteid_set'] = true;
		return 'Root site updated.';
	}

	/**
	* Gets the page array of the siteid
	*
	* @access public
	* @return array
	*/
	function backend_get_page_array() {
		$pages = $this->web_system->page_array_with_sticks($this->parameters['root_siteid']);
		return (Array('' => '[NONE - Use Current Page]', '0' => '[Site]') + $pages);
	}

	/**
	* Change the root_pageid of the page
	*
	* @access public
	* @return array
	*/
	function backend_set_root_pageid($root_pageid, $found_in_post_vars) {
		if (!$found_in_post_vars) return;
		// if they have just set the siteid, then blank out the root pageid
		if (!empty($this->temp['root_siteid_set'])) $root_pageid = '';
		if ($this->parameters['root_pageid'] == $root_pageid) return;
		$this->parameters['root_pageid'] = $root_pageid;
		return 'Root Page updated.';
	}

	function backend_print_exclude_pages() {
		# Get options only for this page's children
		$sub_pageids = $this->get_subpageids();
		$options = Array();
		foreach($sub_pageids as $pageid) {
			$page = &$this->get_page($pageid);
			$options[$pageid] = $page->short_name;
		}
		echo multiple_combo_box ('exclude_pages', $options, $this->parameters['exclude_pages'], '', 0, min(count($options), 10));
	}

	function backend_process_exclude_pages() {
		$this->parameters['exclude_pages'] = $_POST['exclude_pages'];
	}


	 ###############################################
	# Prints instructions for writing output formats
	function print_output_format_instructions() {

		$options = array(
			'%a' => 'Abbreviated weekday name according to the current locale',
			'%A' => 'Full weekday name according to the current locale',
			'%b' => 'Abbreviated month name according to the current locale',
			'%B' => 'Full month name according to the current locale',
			'%c' => 'Preferred date and time representation for the current locale',
			'%C' => 'Century number (the year divided by 100 and truncated to an integer, range 00 to 99)',
			'%d' => 'Day of the month as a decimal number (range 01 to 31)',
			'%D' => 'Same as %m/%d/%y',
			'%e' => 'Day of the month as a decimal number, a single digit is preceded by a space (range \' 1\' to \'31\')',
			'%g' => 'Like %G, but without the century.',
			'%G' => 'The 4-digit year corresponding to the ISO week number (see %V). This has the same format and value as %Y, except that if the ISO week number belongs to the previous or next year, that year is used instead.',
			'%h' => 'Same as %b',
			'%H' => 'Hour as a decimal number using a 24-hour clock (range 00 to 23)',
			'%I' => 'Hour as a decimal number using a 12-hour clock (range 01 to 12)',
			'%j' => 'Day of the year as a decimal number (range 001 to 366)',
			'%m' => 'Month as a decimal number (range 01 to 12)',
			'%M' => 'Minute as a decimal number',
			'%n' => 'Newline character',
			'%p' => 'Either \'am\' or \'pm\' according to the given time value, or the corresponding strings for the current locale',
			'%r' => 'Time in a.m. and p.m. notation',
			'%R' => 'Time in 24 hour notation',
			'%S' => 'Second as a decimal number',
			'%t' => 'Tab character',
			'%T' => 'Current time, equal to %H:%M:%S',
			'%u' => 'Weekday as a decimal number [1,7], with 1 representing Monday',
			'%U' => 'Week number of the current year as a decimal number, starting with the first Sunday as the first day of the first week',
			'%V' => 'The ISO 8601:1988 week number of the current year as a decimal number, range 01 to 53, where week 1 is the first week that has at least 4 days in the current year, and with Monday as the first day of the week. (Use %G or %g for the year component that corresponds to the week number for the specified timestamp.)',
			'%W' => 'Week number of the current year as a decimal number, starting with the first Monday as the first day of the first week',
			'%w' => 'Day of the week as a decimal, Sunday being 0',
			'%x' => 'Preferred date representation for the current locale without the time',
			'%X' => 'Preferred time representation for the current locale without the date',
			'%y' => 'Year as a decimal number without a century (range 00 to 99)',
			'%Y' => 'Year as a decimal number including the century',
			'%Z' => 'Time zone or name or abbreviation',
			'%%' => 'a literal \'%\' character'
		);

		?>
		<p>
		Define your output formats using letters to represent time information.<br>
		E.g. "%A, %B %e %Y" => "Sunday, March 9 1980"
		</p>
		<p>
		<table border="0" cellspacing="2" cellpadding="2">
		<?foreach($options as $code => $desc){?>
			<tr>
				<td valign="top" nowrap><b><?=$code?></b>&nbsp;&nbsp;</td>
				<td valign="top"><?=$desc?></td>
			</tr>
		<?}?>
		</table>
		</p>
		<p>
		<b>You need to add the % signs before each letter for this to work correctly.</b><br />
		Note that if certain elements of the date are blank, affected elements will be removed from the formatted result. Additionally, some elements will not work for dates before 1970.
		</p>
		<?

	}# end print_output_format_instructions()


	/**
	* Get the subpage page ids for the root page(no filtering)
	*
	* @return Array()	array of pageids
	* @access public
	*/
	function get_subpageids() {
		# NOTE: we don't need to do any read access checks on these pages, because the site
		#       object strips out any pages that we don't have

		if (isset($this->parameters['root_pageid']) && $this->parameters['root_pageid'] != '' && !empty($this->parameters['root_siteid'])) {
			# we are using the site, to print the top level
			if ($this->parameters['root_pageid'] == '0') {
				$root_site = &$this->get_site($this->parameters['root_siteid']);
				$sub_pageids = array_values($root_site->get_top_pageids());
			} else {
				$root_page = &$this->get_page($this->parameters['root_pageid']);
				if (!$root_page->id) $root_page = &$this->get_page();
				$sub_pageids = array_values($root_page->get_subpageids());
			}
		} else {
			$root_page = &$this->get_page();
			$sub_pageids = array_values($root_page->get_subpageids());
		}

		return $sub_pageids;

	}# end get_subpageids()

	/**
	* get the subpage page ids filtered by exclude types and exclude pages
	*
	* @return Array()	array of pageids
	* @access public
	*/
	function get_valid_subpageids() {

		$sub_pageids = $this->get_subpageids();

		$check_pageids = (!empty($this->parameters['exclude_pages']) && is_array($this->parameters['exclude_pages']));
		$check_status  = (!empty($this->parameters['exclude_types']) && is_array($this->parameters['exclude_types']));

		if ($check_pageids || $check_status) {
			$out_pageids = array();
			foreach ($sub_pageids as $sub_pageid) {
				if ($check_pageids && in_array($sub_pageid, $this->parameters['exclude_pages'])) continue;
				if ($check_status) {
					$sub_page = &$this->get_page($sub_pageid);
					$sub_status = $sub_page->get_last_status();
					if (in_array($sub_status, $this->parameters['exclude_types'])) continue;
				}
				$out_pageids[] = $sub_pageid;
			}
			return $out_pageids;

		} else {
			return $sub_pageids;
		}
	}# end get_valid_subpageids()

	 ###########################################################
	# Returns the title as it should be printed on the frontend
	function get_title() {
		return $this->parameters['title'];
	}

	/**
	* Print the frontend - we don't want to print the design
	*@access public
	*/
	function print_frontend() {
		if($_REQUEST['action'] == 'rss_feed') $this->print_rss_feed();
		else Page_Template::print_frontend();
	}

	  ##########################################################################
	 # Define print_body() - this *should* get called by the site's design xtra
	function print_body() {
		$page = &$this->get_page();
		$db = &$this->get_db();
		$site = &$this->get_site();

		# Create the bodycopies and add the universal keyword replacements
		$keyword_replacements = $this->get_universal_replacements();

		$bodycopy  = new BodyCopy($this->parameters['bodycopy'], 'bodycopy');
		$bodycopy->add_replace_keywords($keyword_replacements);

		$page_copy = new BodyCopy($this->parameters['page_copy'], 'page_copy');
		$page_copy->add_replace_keywords($keyword_replacements);

		# get the sub pages, do the array values to make sure we have a proper indexed array
		$sub_pageids = $this->get_valid_subpageids();



		# are we printing more than one page?
		$start_printing_at =  $_REQUEST['start_printing_at'];
		if (!$start_printing_at) $start_printing_at = 0;

		if (!$this->parameters['number_per_page']) {
			$stop_printing_at = count($sub_pageids);
		} else {
			$stop_printing_at = $start_printing_at + $this->parameters['number_per_page'];
			if ($stop_printing_at > count($sub_pageids)) $stop_printing_at = count($sub_pageids);
		}

		if (is_array($this->_bodycopy_keywords)) {
			$keyword_replacements = Array();
			foreach($this->_bodycopy_keywords as $keyword) { 
				switch($keyword) {
					case 'page_number' :
						$keyword_replacements[$keyword] = ($start_printing_at / $this->parameters['number_per_page']) + 1;
						break;
					case 'page_count' :
						$keyword_replacements[$keyword] = count($sub_pageids);
						break;
					case 'previous_link' :
						if ($start_printing_at >= $this->parameters['number_per_page']) {
							$keyword_replacements[$keyword] = '<a href="'.$page->get_href(true).'start_printing_at='.($start_printing_at - $this->parameters['number_per_page']).'">'.$this->parameters['previous_link'].'</a>';
						} else {
							$keyword_replacements[$keyword] = '';
						}
						break;
					case 'next_link' :
						if (($start_printing_at + $this->parameters['number_per_page']) < count($sub_pageids)) {
							$keyword_replacements[$keyword] = '<a href="'.$page->get_href(true).'start_printing_at='.($start_printing_at + $this->parameters['number_per_page']).'">'.$this->parameters['next_link'].'</a>';
						} else {
							$keyword_replacements[$keyword] = '';
						}
						break;
					case 'rss_url' :
						$keyword_replacements[$keyword] = $page->get_url(true).'action=rss_feed';
						break;
				}
			}
			$bodycopy->add_replace_keywords($keyword_replacements);
		}

		# if the subpage list is below the body copy, print the bodycopy first.
		if ($this->parameters['position'] == 'b') {
			$bodycopy->paint();
			if ($this->parameters['horizontal_divider']) {
				echo "<center><hr size=\"1\" noshade\"></center>";
			}
		}


		$cell_width = floor(100 / $this->parameters['number_per_row']);

		$pages_printed = 0;

		#start the caching if your using keyword replacements
		if ($this->parameters['position'] == 'k')
			ob_start();
		?><table border="0" cellspacing="0" cellpadding="0" width="100%"><tr><?
		for($i = $start_printing_at; $i < $stop_printing_at; $i++) {
			$subpage = &$this->get_page($sub_pageids[$i]);
			if (!$subpage->id) continue;

			$url = $subpage->get_href();

			echo '<td width="'.$cell_width.'%" valign="top">';

			# if we want to print anchors and the page has a dir
			if ($this->parameters['use_anchors'] && $subpage->dirs[0]) {
				echo '<a name="'.$subpage->dirs[0].'"></a>';
			}

			$link_colour = ($this->parameters['link_colour']) ? ' style="color: #'.$this->parameters['link_colour'].'"' : '';

			$subpage_href = ($this->parameters['popup_window']['on']) ? '#" onclick="javascript:window.open(\''.$url.'\', \'subpage\', \'toolbar='.$this->parameters['popup_window']['tool'].',width='.$this->parameters['popup_window']['width'].',height='.$this->parameters['popup_window']['height'].',scrollbars='.$this->parameters['popup_window']['scroll'].',resizable='.$this->parameters['popup_window']['resize'].',menubar='.$this->parameters['popup_window']['menu'].',status='.$this->parameters['popup_window']['status'].',location='.$this->parameters['popup_window']['location'].'\'); return false"' : $url ;

			if (is_array($this->_page_copy_keywords)) {
				$keyword_replacements = Array();
				foreach ($this->_page_copy_keywords as $keyword) {
					switch($keyword) {
						case 'name' :
							$keyword_replacements['name'] = '<a href="'.$subpage_href.'"'.$link_colour.'>'.$subpage->name.'</a>';
						break;

						case 'short_name' :
							$keyword_replacements['short_name'] = '<a href="'.$subpage_href.'"'.$link_colour.'>'.$subpage->short_name.'</a>';
						break;

						case 'thumbnail' :
							$temp = $subpage->image_tag("thumbs/page_thumb", $subpage->name , 0, 0, 'border="0"');
							#Try to see if you can extract both an image tag and caption so you can print seperately
							if (ereg("<img src=\"[^\\\"]+\"[^\>]*\>", $temp, $imgtag) && preg_match("/\<td class=fineprint\>(.*)\<\/td\>/i",$temp,$captiontag))
								$keyword_replacements['thumbnail'] = '<table><tr><td><a href="'.$subpage_href.'">'.$imgtag[0].'</a></td></tr><tr>'.$captiontag[0].'</tr></table>';
							else #otherwise just do it like it used to be done
								$keyword_replacements['thumbnail'] = '<a href="'.$subpage_href.'">'.$subpage->image_tag("thumbs/page_thumb", $subpage->name , 0, 0, 'border="0"').'</a>';
						break;

						case 'description' :
							$keyword_replacements['description'] = $subpage->description;
						break;

						case 'content' :
							$template = &$subpage->get_template();
							$keyword_replacements['content'] = $template->get_html();
						break;

						case 'publish_date' :
							if (!$subpage->live()) {
								$keyword_replacements['publish_date'] = '';
							} else {

								$timestamp = mysql_to_timestamp($subpage->get_last_action_date('status'));

								if (!empty($this->parameters['date_format'])) {
									$switch_back = false;
									if (trim($this->parameters['locale']) != '' && strtolower($this->parameters['locale']) != 'c') {
										$switch_back = true;
										setlocale(LC_TIME, array($this->parameters['locale'], 'C'));
									}
									$keyword_replacements['publish_date'] = strftime($this->parameters['date_format'], $timestamp);
									if ($switch_back) setlocale(LC_TIME, 'C');
								} else {
									if (!empty($this->parameters['locale'])) {
										$keyword_replacements['publish_date'] = readable_locale_datetime($timestamp, $this->parameters['locale']);
									} else {
										$keyword_replacements['publish_date'] = readable_datetime($timestamp);
									}
								}
							}# endif
						break;


						case 'publish_date_easy' :
							if (!$subpage->live()) {
								$keyword_replacements['publish_date_easy'] = '';
							} else {
								$timestamp = mysql_to_timestamp($subpage->get_last_action_date('status'));
								$keyword_replacements['publish_date_easy'] = easy_locale_datetime($timestamp, $this->parameters['locale']);
							}# endif
						break;

					}
				}
				$page_copy->add_replace_keywords($keyword_replacements);
			}
			$page_copy->paint();

			echo '</td>';
			$pages_printed++;

			if ($pages_printed == $this->parameters['number_per_row']) {
				$pages_printed = 0;
				echo '</tr>';

				if ($this->parameters['horizontal_divider'] && ($i < count($sub_pageids) - 1)) {
					$colspan = $this->parameters['number_per_row'];
					# if we are using a vertical divider,
					# add one less than the number of rows because we don't put a divider after the last cell
					if ($this->parameters['vertical_divider']) $colspan += $this->parameters['number_per_row'] - 1;
					echo '<tr><td colspan="'.$colspan.'" align="center">'.'<hr size="1" noshade></td></tr>';
				}

				echo '<tr>';

			} else {
				echo ($this->parameters['vertical_divider']) ? '<td>&nbsp;|&nbsp;</td>' : '';
			}

		} # end foreach

		echo '</tr></table>';

		if ($this->parameters['number_per_page'] && empty($this->parameters['hide_default_page_nav'])) {
			?>
			<br><br>
			<table border="0" width="100%" align="center">
				<tr>
					<td align="right" width="50%">
					<?
					if ($start_printing_at >= $this->parameters['number_per_page']) {
						?><a href="<?=$page->get_href(true)?>start_printing_at=<?=($start_printing_at - $this->parameters['number_per_page'])?>"><< Previous</a><?
					}
					?>&nbsp;</td><td align="left" width="50%">&nbsp;<?
					if (($start_printing_at + $this->parameters['number_per_page']) < count($sub_pageids)) {
						?><a href="<?=$page->get_href(true)?>start_printing_at=<?=($start_printing_at + $this->parameters['number_per_page'])?>">Next >></a><?
					}
					?>
					</td>
				</tr>
			</table>
			<?
		}

		#use the cache as what to fill the keyword replacement in the bodycopy
		if ($this->parameters['position'] == 'k') {
			$buffer = ob_get_contents();
			ob_end_clean();
			$replace['subpage_list'] = $buffer;
			$bodycopy->add_replace_keywords($replace);
			$bodycopy->paint();
		}

		# if the page listing is above the body copy...
		if ($this->parameters['position'] == 'a') {
			$bodycopy->paint();
		}

	}# end print_body()

	/**
	* Print the rss xml document
	*@access public
	*/
	function print_rss_feed() {
		$page = &$this->get_page();
		$sub_pageids = $this->get_valid_subpageids();

		global $SQUIZLIB_PATH;
		include_once($SQUIZLIB_PATH.'/xml/xml.inc');

		$xml = new Xml();

		$xml->tag_open('rss',array('version'=>'0.91'));

		$xml->tag_open('channel');

		$xml->tag_open('title');

		$xml->add_data($page->name);

		$xml->tag_close();

		$xml->tag_open('link');

		$xml->add_data($page->get_url());

		$xml->tag_close();

		$xml->tag_open('description');

		$xml->add_data($page->description);

		$xml->tag_close();

		if($page->default_languages) {
			$xml->tag_open('language');

			$xml->add_data($page->default_languages);

			$xml->tag_close();
		}

		foreach($sub_pageids as $pageid) {
			$sub_page = &$this->get_page($pageid);
			if (!$sub_page->id) continue;
			$xml->tag_open('item');

			$xml->tag_open('title');

			$xml->add_data($sub_page->name);

			$xml->tag_close();

			$xml->tag_open('link');

			$xml->add_data($sub_page->get_url());

			$xml->tag_close();

			$xml->tag_open('description');

			$xml->add_data($sub_page->description);

			$xml->tag_close();

			$xml->tag_close();

		}

		$xml->tag_close();

		$xml->tag_close();

		$xml->export();
	}

}#end class


?>
