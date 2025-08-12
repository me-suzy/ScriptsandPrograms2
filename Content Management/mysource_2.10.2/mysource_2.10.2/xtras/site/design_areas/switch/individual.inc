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
## File: xtras/site/design_areas/switch/switch.inc
## Desc: A switch statement for set string variabes
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/xtras.inc");
include_once("$INCLUDE_PATH/site_design_area.inc");
#---------------------------------------------------------------------#

class Site_Design_Area_Switch_Individual extends Site_Design_Area {


	var $compare;
	var $contents = Array();  # array holding the switch cases and sections


	function Site_Design_Area_Switch_Individual(&$_owner) {

		$this->Site_Design_Base($_owner);
		$this->customisable = false;
		$this->cacheable = false;

	}#end Site_Design_Area_Switch()

	function paint(&$tag_attributes) {

		#print("<br>Now we're cooking with: <br><pre>");
		#$top = &$this->get_top_owner();
		#print_r($top->_set_vars);
		#print_r($top->custom_vars);
		#print("</pre><br>");

		$in_case = FALSE;

		$compare_val = $this->get_var($this->compare);
		$compare_val = strtolower($compare_val['value']);

		foreach ($this->contents as $element) {

			switch($element['_type']) {

				case "CASE" :
					if ( $compare_val == $element['match'] )
						$in_case = $compare_val;

				break;
				case "BREAK" :
					$in_case = FALSE;

				break;
				case "TAG" :
					switch($element['operation']) {

						case "set" :
							if ( $in_case ) {

								$top = &$this->get_top_owner();
								$top->set_from_tag($element);
								#$top->set_custom_var($element['attributes']['name'],$element['attributes']['value']);
#								print("SET:<pre>");
#								print_r($top->_set_vars);
#								print_r($top->_custom_vars);
#								print("</pre><BR>");
							}

						break;
						case "print" :
							if ( $in_case ) {
								$this->print_val($element);
								#print("blah!");
								#print_r($this->get_val('branding'));
							}

						break;
						default :
							#TODO: raise error - unknown tag

					}#end switch

				break;
				case "HTML" :
					if ( $in_case ) echo $element['contents'];

				break;
				case "DESIGN_AREA" :
					if ( $in_case ) $this->_print_nested_area($element);

				break;
			}
		}

                #print("<br>Finished cooking with: <br><pre>");
                #$top = &$this->get_top_owner();
                #print_r($top->_set_vars);
		#print_r($top->_custom_vars);
                #print("</pre><br>");
	}

	function create(&$tag) {

		 ########################################
		# Set up any variables
		#$this->_set_variables($tag, true);
		$this->_set_nested_areas($tag, '');

		 ###
		# Set the variable to compare against
		# TODO: check for valid value to compare against
		$this->compare = strtolower($tag['attributes']['variable']);
		#print('Set an indi-switchs compare to '. $tag['attributes']['variable'] .'...<br>');

		 ############################################
		# process the contents of the element
		foreach($tag['contents'] as $index => $element) {

			# if we are dealing with a tag
			switch ($element['_type']) {

				case "TAG" :

					if ($element['attributes']['section']) {

						$contents_arr = Null;

						switch (strtoupper($element['attributes']['section'])) {

							case "CASE" :
								/*^^^ TODO: check for self termination */
								$contents_arr['_type'] = 'CASE';
								$contents_arr['match'] = strtolower($element['attributes']['match']);
							break;

							case "BREAK" :
								/*^^^ TODO: check for self termination */
								$contents_arr['_type'] = 'BREAK';
							break;

							#case "DEFAULT" :
								/*^^^ TODO: check for no self-termination */
								/*^^^ TODO: set $this->default */
							#break;

							default :
								$this->_set_error("section '".$element['attributes']['section']."' unknown", __FILE__, __LINE__);
						}

						if ( $contents_arr )
							$this->contents[] = $contents_arr;
					}
					elseif ($element['operation'] == 'print' ||
						$element['operation'] == 'set' ) {

						$this->contents[] = $element;
					}
					else {
						
						$this->_set_error("Tag unknown", __FILE__, __LINE__);
					}

				break;

				case "DESIGN_AREA" :

					$this->contents[] = $element;

				break;

				case "HTML"        :

					if ( trim( $element['contents'] ) )
						$this->contents[] = $element;

				break;

				default :
					$this->_set_error("_type '$element[_type]' unknown", __FILE__, __LINE__);
					unset($this);
					return;

			}#end switch

		}#end foreach

		return true;
	}

}#end class Site_Design_Area_Switch_Individual

?>
