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
## File: xtras/site/design_areas/date/date_time.inc
## Desc: this class is used to print a date in different formats
## to be called like a MySource_AREA tag
## $Source: /home/cvsroot/xtras/site/design_areas/date_time/date_time.inc,v $
## $Revision: 1.1 $
## $Author: csmith $
## $Date: 2003/12/31 02:33:59 $
#######################################################################
#---------------------------------------------------------------------#

class Site_Design_Area_Date_Time extends Site_Design_Area {

	function Site_Design_Area_Date_Time(&$_owner) {
		$this->Site_Design_Base($_owner);
		$this->customisable = true;

		# set up some default variables,
		# so unless they write over them we have something to play with
		#editable
		$this->_set_var('show_date',        '1',          'boolean',   'Whether to show date or not.');

		$this->_set_var('date_format',      'YYYY_MM_DD', 'set',       'Set the date format.<br><i>NOTE: Only effective if date is enabled.</i>',
			Array('YYYY_MM_DD' => 'YYYY_MM_DD', 'DD_MM_YYYY' => 'DD_MM_YYYY', 'YYYY' => 'YYYY', 'DDDD, DD_MM_YYYY' => 'DDDD, DD_MM_YYYY', 'DD_MMMM_YYYY' => 'DD_MMMM_YYYY')
		);

		$this->_set_var('date_divider',     '.',          'set',       'Character between date numbers.<br><i>NOTE: Only effective if date is enabled.</i>',
			Array('.' => '.', '/' => '/', '-' => '-', ' ' => 'space')
		);

		$this->_set_var('show_time',        '0',          'boolean',   'Whether to show time or not.');

		$this->_set_var('time_format',      'HH_MM_24',   'set',       'Set the time format.<br><i>NOTE: Only effective if time is enabled.</i>',
				Array('HH_MM_SS_24' => 'HH_MM_SS (24h)', 'HH_MM_24' => 'HH_MM (24h)', 'HH_MM_SS_12' => 'HH_MM_SS (12h)', 'HH_MM_12' => 'HH_MM (12h)')
		);

		$this->_set_var('time_divider',     ':',          'set',       'Character between time numbers.<br><i>NOTE: Only effective if time is enabled.</i>',
				Array('.' => '.', ':' => ':', ',' => ',', '-' => '-', ' ' => 'space')
		);

		$this->_set_var('class',            '',           'text',      'Stylesheet class for the date/time');
		$this->_set_var('date_time_order',	'date_time',  'set',	   'How should the date and time be arranged?<br><i>NOTE: Only effective if date AND time are enabled.</i>',
				Array('date_time' => 'Date Time', 'time_date' => 'Time Date', 'date_nl_time' => 'Date_*newline*_Time', 'time_nl_date' => 'Time_*newline*_Date')
		);
	} #end Site_Design_Area_Date_Time()

	function create(&$tag) {

		# init all vars
		$this->_set_variables($tag, true);

		# no additional attributes needed
		return;

	}#end create()

	function paint(&$tag_attributes) {
		$show_date = $this->get_val('show_date');
		$date_format = $this->get_val('date_format');
		$date_divider = $this->get_val('date_divider');
		$show_time = $this->get_val('show_time');
		$time_format = $this->get_val('time_format');
		$time_divider = $this->get_val('time_divider');
		$class = $this->get_val('class');
		$date_time_order = $this->get_val('date_time_order');

		#if they want the date to show up, this handles the formating of the date string
		if ($show_date){
			switch ($date_format){
				case 'YYYY_MM_DD':
					$date_print='Y' . $date_divider . 'm' . $date_divider . 'd';
					break;

				case 'DD_MM_YYYY':
					$date_print='d' . $date_divider . 'm' . $date_divider . 'Y';
					break;

				case 'YYYY':
					$date_print='Y';
					break;

				case 'DDDD, DD_MM_YYYY':
					$date_print='l, d' . $date_divider . 'm' . $date_divider . 'Y';
					break;

				case 'DD_MMMM_YYYY':
					$date_print='d. F Y';
					break;

				default:
					echo 'Error in date format';
			}
		} else {
			$date_print = '';
		}

		#if they want the time to show up, this handles the formating of the time string
		if ($show_time){
			switch ($time_format){
				case 'HH_MM_SS_24':
					$time_print = 'H' . $time_divider . 'i' . $time_divider . 's';
					break;

				case 'HH_MM_24':
					$time_print = 'H' . $time_divider . 'i';
					break;

				case 'HH_MM_SS_12':
					$time_print = 'h' . $time_divider . 'i' . $time_divider . 's a';
					break;

				case 'HH_MM_12':
					$time_print = 'h' . $time_divider . 'i a';
					break;

				default:
					echo 'Error in time format';
			}
		} else {
			$time_print = '';
		}

		#let's put it in order...
		switch ($date_time_order){
			case 'date_time':
				$date_time_print = date($date_print . ' ' . $time_print);
				break;

			case 'time_date':
				$date_time_print = date($time_print . ' ' . $date_print);
				break;

			case 'date_nl_time':
				$date_time_print = date($date_print . '<b\\r>' . $time_print);
				break;

			case 'time_nl_date':
				$date_time_print = date($time_print . '<b\\r>' . $date_print);
				break;

			default:
				echo 'Error in the date time order';
		}


		#if they want a special class, they can have it
		if ($class) $date_time_print = "<span class='" . $class . "'>" . $date_time_print . "</span>";

		#is it better to return this variable first, and then print it to the page from outside the function?
		#who cares, it works...
		echo $date_time_print;

	}#end paint()

	  ##########################################
	 # Returns a description of the design area
	function get_description() {
		return 'Change the date and time display options.';
	}#end get_desription()

}#end class Site_Design_Area_Date_Time

?>