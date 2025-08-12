<?  ##############################################
   ### MySource ------------------------------###
  ##- Site Creator object ----- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## Desc: Displays an option to subscribe to an email about updates to this page
## $Source: /home/cvsroot/xtras/site/design_areas/email_page_update/email_page_update.inc,v $
## $Revision: 1.4 $
## $Author: mmcintyre $
## $Date: 2003/12/10 01:22:18 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/site_design_area.inc");
#---------------------------------------------------------------------#


	class Site_Design_Area_Email_Page_Update extends Site_Design_Area {
		

		  #######################
		 # Constructor         #
		#######################
		#                     #
		# @param - int _owner #
		# @returns void       #
		#######################

		function Site_Design_Area_Email_Page_Update(&$_owner) {
		
			$this->Site_Design_Base($_owner);
		
		} # end Site_Design_Area_Email_Page_Update()
		
		  #############################
		 # Function Create           #
		#############################
		#                           #
		# @param   - array tag      #
		# @returns - void           #
		#############################

		function create(&$tag) {
		
			# Set up any variables
			$this->_set_variables($tag, true);
			$this->_set_nested_areas($tag, "menu");

			# process the contents of the element
			foreach($tag[contents] as $index => $element) {
				# if we are dealing with a tag
				switch ($element['_type']) {
				
					case "TAG"         : # must be a print tag, we'll deal with it later
					case "DESIGN_AREA" : # dealt with above so just add to contents
					case "HTML"        : # nothing to do to this yet, so just add to contents array
						$this->contents[] = $element;

					break;

					default :
						$this->_set_error("_type '$element[_type]' unknown", __FILE__, __LINE__);
						unset($this);
						return;

				}#end switch

			}#end foreach

		}#end create()

		  #####################################
		 # Function Paint                    #
		#####################################
		#                                   #
		# @param   - array tag_attributes   #
		# @returns - void                   #
		#####################################


		function paint(&$tag_attributes) {
			$site = &$this->get_site();
			$page = &$this->get_page();
			
			$obj = &$this->web_system->get_extension('email_page_update');
			// if this page is not recieving updates, then return because we do not want
			// to show the check boxes for this page
			if (!$obj->is_in_page_list($page->id)) return false;
			
			# make sure that the painter is a page template and not a site extension
			$site_design = &$this->get_top_owner();
			if (!$site_design->painter_is_page_template()) return;
		
			// process the form if it has been submitted
			list($s_checked, $m_checked) = $this->processSubmit();

			foreach ($this->contents as $element) {
				switch($element['_type']) {

				# plain html element just echo its contents
				case "HTML" :
					echo $element[contents];

				break;

				# nested design area so print/paint it
				case "DESIGN_AREA" :
					$this->_print_nested_area($element);

				break;

				# taged element ... should really only be a print element
				case "TAG" :
					switch($element['operation']) {
						case "print" : 
							if ($element['attributes']['name'] == "single_page") {
								$this->printCheckbox(true, $s_checked);
							}
							elseif($element['attributes']['name'] == "page_tree") {
								$this->printCheckbox(false, $m_checked);
							} else {
								$this->print_val($element);
							}
						break;
					}

				break;

				default :
					$this->_set_error("_type '$element[_type]' unknown", __FILE__, __LINE__);
					unset($this);
					return;

			} # end switch

		} #end foreach contents
		?>
		</form>
		<?
	} # end paint()


	/**
	* prints the checkbox for signing up for email page updates
	*
	* @param boolean $single if this is for a single page sign up or for a page and its subpages
	*
	* @access public
	* @return boolean
	*/
	function printCheckbox($single = false, $checked=false)
	{
		$session = &get_mysource_session();
		$userid = $session->user->id;
		
		$checked = ($checked) ? ' checked' : '';

		// check to see what type of checkbox this is for
		// either its for a single page of a page and all its subpages

		$name = 'email_page_update' . (($single) ? '_single' : '_multiple');

		?>
		  <form name="form_email_page_update" action="<?=$href?>" method="GET">
			<input type="hidden" value="<?=($_GET['p']) ? $_GET['p'] : $_GET['pageid'] ?>" name="p">
			<input type="hidden" value="1" name="email_page_update_form_submit">
			<input type="hidden" value="<?php echo $checked ?>" name="<?php echo $name.'_previous' ?>">
			<input type="checkbox" name="<?php echo $name ?>" value="1" onClick="form.submit()" <?php echo $checked ?>>
		<?php
		
		return true;
	}


	/**
	* processes the checkbox submissions
	*
	* @access public
	* @return boolean
	*/
	function processSubmit()
	{
		$page = &$this->get_page();
		$href = $page->get_href(true);
	
		$session = &get_mysource_session();
		$user = $session->user;
		
		if (!$user) return false;

		$obj = &$this->web_system->get_extension('email_page_update');

		$s_checked = false;
		$m_checked = false;

		if (isset($_GET['email_page_update_form_submit'])) {
			$s_previous = ($_GET['email_page_update_single_previous']) ? true : false;
			$m_previous = ($_GET['email_page_update_multiple_previous']) ? true : false;

			$s_current = (isset($_GET['email_page_update_single'])) ? true : false;
			$m_current = (isset($_GET['email_page_update_multiple'])) ? true : false;

			if ($s_previous != $s_current) {
				if ($obj->set_email_page_update($user->id, $page->id, false)) {
					$s_checked = true;
				}
			} 
			if ($m_previous != $m_current) {
				if ($obj->set_email_page_update($user->id, $page->id, true)) {
					$m_checked = true;
				}
			// just let the web extension know that the user has been to this page today
			} 
		} elseif ($val = $obj->update_page_hit($user->id, $page->id)) {
			$s_checked = ($val == 'single') ? true : false;
			$m_checked = ($val == 'multiple') ? true : false;
		}
		return Array($s_checked, $m_checked);
	}
	  
	  #########################
	 #                       #
	#########################
	#                       #
	#                       #
	#                       #
	#########################
	
	function get_val($name) {

		switch(strtolower($name)) {

			case "email_page_update_link" : 
				$page = &$this->get_page();
				$href = $page->get_href(true);
				$href .= ereg_replace("\&?", "", $_SERVER['QUERY_STRING']);
				
				# if there is a question mark then append an '&' otherwise append a '?'
				if(preg_match("\?.+$", $href)) $href .= "&"; 
				elseif(!strstr($href, "?")) $href .= "?";

				return $href."mysource_web_extension=email_page_update";
			break;
		
			# lets hope something "higher up" knows about this one
			default :
				return Site_Design_Base::get_val($name);
		}#end switch

	}#end get_val()

} # end class Site_Design_Area_Email_Page_Update
?>