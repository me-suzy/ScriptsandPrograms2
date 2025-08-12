<?  ##############################################
   ### MySource ------------------------------###
  ##- Site Creator object ----- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This software Copyright Squiz.net 2001
## All Rights Reserved.
##
## File: xtras/site/designs_areas/searchresults/searchresults.inc
## Desc: this class is used to print the customised search results on the page
## $Source: /home/cvsroot/xtras/site/design_areas/searchresults/searchresults.inc,v $
## $Revision: 2.10 $
## $Author: ramato $
## $Date: 2003/07/15 01:04:39 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/site_design_area.inc");
include_once(dirname(__FILE__)."/pageresult.inc");
include_once(dirname(__FILE__)."/fileresult.inc");
#---------------------------------------------------------------------#

class Site_Design_Area_SearchResults extends Site_Design_Area {

	var $results;


	function Site_Design_Area_SearchResults(&$_owner) {
		$this->Site_Design_Base($_owner);
		#$this->customisable = true;

		#Set up any default variables?

		#Sections within this design area
		$this->results = array();

	}#end Site_Design_Area_SearchBox()

	  ############################################################
	 # Reset the owner for this object, then set this as
	# the owner for the sections
	function reset_owner(&$owner) {

		for(reset($this->results);
			NULL !== ($key = key($this->results));
			next($this->results)) {

			$this->results[$key]->reset_owner($this);

		}#end for

		Site_Design_Base::reset_owner($owner);

	}#end reset_owner

	  ############################################################
	 # Unset the owner for this object, and the sections
	function &unset_owner() {

		for(reset($this->results);
			NULL !== ($key = key($this->results));
			next($this->results)) {

			$this->results[$key]->unset_owner();

		}#end for

		return Site_Design_Base::unset_owner();

	}#end unset_owner()

	function create(&$tag) {
		 ########################################
		# Set up any variables
		$this->_set_variables($tag);
		$this->_set_nested_areas($tag, "searchresults");

		 ############################################
		# process the contents of the element
		foreach($tag[contents] as $index => $element)
		{
			# if we are dealing with a tag
			switch ($element[_type]) {

				case "TAG" :

					# if this is the sub section
					if ($element[attributes][section]) {

						$section_name = strtolower($element[attributes][section]);

						$class_name = get_class($this)."_".$section_name;

						# kill it because it's not needed anymore
						unset($element[attributes][section]);

						$this->results[$section_name] = new $class_name($this);
						$this->results[$section_name]->create($element);

						# append to the contents array so we know were to print this
						# only print results once, therefore only put it in the content array once.
						$this->contents[one_result] = Array("_type" => "results");

					# must be a print tag, we'll deal with it later,
					# so just append to contents
					} else {

						$this->contents[] = $element;

					}#end if

				break;

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


	function paint() {
		$mysource_site_extension = $_REQUEST['mysource_site_extension'];
		# Make sure we are searching
		if($mysource_site_extension != "search")  return;

		$site = &$this->get_site();

		 # run a security check to see if this extension has been allowed
		if (!in_array('search',$site->allowed_extensions)) {
			$this->_set_error("The 'Search' site extension has not been enabled for this site.", __FILE__, __LINE__);
			unset($this);
			return;
		}
		$session = &get_mysource_session();

		# Get the search results which we generated previously
		$search_results = &$session->get_var("search_results");
		$resultids = $search_results['ids'];

		# Js for going between pages of results
		?>
		<script language=javascript>
			function search_page(pg) {
				document.location = './?<? echo "mysource_site_extension=search&pageid=$web->current_pageid&siteid=$web->current_siteid&q=".urlencode($search_results[q])."&search_domain=$search_results[search_domain]&search_type=$search_results[search_type]&result_page=" ?>' + pg;
			}
		</script>
		<?
		foreach($this->contents as $element)
		{
			switch($element['_type'])
			{
				# plain html element just echo its contents
				case "HTML" :
					echo $element['contents'];
				break;

				# nested design area so print/paint it
				case "DESIGN_AREA" :
					$this->_print_nested_area($element);

				break;

				# taged element ... should really only be a print element
				case "TAG" :

					switch($element['operation'])
					{
						case "print" :
							$this->print_val($element,$search_results);
						break;
					}

				break;

				# sub section element
				case "results" :
					for($search_results['current_result'] = $search_results['start']; $search_results['current_result'] < $search_results['end']; $search_results['current_result']++) {
						$id = $resultids[$search_results['current_result']];
						if ($id[0] == 'P') $pageid = substr($id,1); else $pageid = "";
						if ($id[0] == 'F') $fileid = substr($id,1); else $fileid = "";

						#if the design area isn't there, better to print nothing than die...
						if ($pageid && $this->results['pageresult']) { # Print a page result
							$this->results['pageresult']->paint($pageid,$search_results);
						}
						# if the design area isn't there, better to print nothing than die...
						if ($fileid && $this->results['fileresult']) { # Print a file result
							$this->results['fileresult']->paint($fileid,$search_results);
						}

					}
					break;

				default :
					$this->_set_error("_type '$element[_type]' unknown", __FILE__, __LINE__);
					unset($this);
					return;

			}#end switch

		}#end foreach contents

	}#end paint()

	  ##########################################################
	 # Prints the backend for the site administrator to allow
	# them to customise their design
	function print_user_backend($prefix_name="") {
	}#end print_user_backend()

	  ################################################
	 # Returns a description of the design area
	function get_description() {

		return "Change the appearance of the search results.";

	}#end get_description()

	  ###############################################################
	 # this function returns values for the special variables
	# if it is not a predefined variable it uses the value of the variable in this object
	function get_val($name,&$search_results) {

		switch(strtolower($name)) {
            case "summary" :
				if ($search_results['result_count']) {
					# Print result summary
					$str = "Found $search_results[result_count] " . (($search_results['pages'] && $search_results['files'])?"matches":(($search_results['files'])?"files":"pages"));

					if ($search_results['pages'] && $search_results['files']) {
						$str .= " (";
						if ($search_results['page_count']) $str .= "$search_results[page_count] page".(($search_results['page_count']>1)?'s':'').". ";
						if ($search_results['file_count']) $str .= "$search_results[file_count] file".(($search_results['file_count']>1)?'s':'').". ";
						$str .= ")";
					}

					$str .= ' for "'.$search_results[q].'"';

				} else {
					if($search_results['use_no_results_found_page'] && $search_results['no_results_found_page']) {
						$site = &$this->get_site();
						$location = $this->web_system->get_page_url($site->id,(int)$search_results['no_results_found_page']);
						echo js_location($location);
					} else {
						$str = 'No results.<br>Sorry, your search query found no pages or files.';
					}
				}
				return $str;
			break;

			case "page_list" :
				if ($search_results['total_pages'] > 1) {
					$str = "Page $search_results[result_page] of $search_results[total_pages]<br>";
					$str .= (($search_results['prev_page'])?"<a href=\"javascript:search_page($search_results[prev_page])\" onmouseover=\"window.status='Previous Page';return true;\" onmouseoff=\"window.status='';return true;\">&lt;&lt;</a>":"&lt;&lt;");
					$str .= " [ ";
					for ($i = 1; $i <= $search_results['total_pages']; $i++) {
							$str .= (($search_results['result_page'] != $i)?" <a onmouseover=\"window.status='Results Page $i';return true;\" onmouseoff=\"window.status='';return true;\" href=\"javascript:search_page($i)\">$i</a> ":" $i ");
					}
					$str .= " ] ";
					$str .= (($search_results['next_page'])?"<a href=\"javascript:search_page($search_results[next_page])\" onmouseover=\"window.status='Next Page';return true;\" onmouseoff=\"window.status='';return true;\">>></a>":">>");
				}
				return $str;
			break;

			# lets hope they declared it themselves
			default :
				return Site_Design_Area::get_val($name);
		}#end switch
	}

	 #######################################################################################
	# print's the value for the passed name escaping as necessary
	function print_val(&$tag,&$search_results) {
		echo $this->_print_modified($tag, $this->get_val($tag['attributes']['name'],$search_results));
	}

}#end class Site_Design_Area_SearchBox

?>