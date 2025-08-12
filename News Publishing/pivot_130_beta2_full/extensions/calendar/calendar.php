<?php 
$old_errrep = error_reporting(E_ERROR);

// lamer protection
if (strpos($pivot_path,"ttp://")>0) {	die('no');}
$scriptname = basename((isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : $_SERVER['PHP_SELF']);
if ($scriptname=="calendar.php") { die('no'); }
$checkvars = array_merge($_GET , $_POST, $_SERVER, $_COOKIE);
if ( (isset($checkvars['pivot_url'])) || (isset($checkvars['log_url'])) || (isset($checkvars['pivot_path'])) ) {
	die('no');
}
// end lamer protection



// Include the language strings:
if (function_exists('LoadLabels')) {
	LoadLabels( $Paths['extensions_path'] . "calendar/lang.php" );
}



// xhtml workaround
$target = "";

// for silly people that have <base target="_blank"> set.. 
// $target = "target='_self'";

//
// Extend the calender object (defined below) 
//
class MyCalendar extends Calendar
{

	// make next / previous links
	function getCalendarLink($month, $year) {
		// Redisplay the current page, but with some parameters
		// to set the new month and year

		$s = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : $_SERVER['PHP_SELF'];

		$output = "$s?month=$month&amp;year=$year";
		
		if (isset($_GET['id'])) {
			$output .= "&amp;id=".$_GET['id'];
		}
		
		return $output;
		
	}

	// add a date to be linked in the calendar
	function setDateLink($date, $link, $code) {
		global $my_dates_arr;

		list($year,$month,$day)=split("-",$date);
		$index=sprintf("%04d%02d%02d", $year, $month, $day);

		$my_dates_arr[$index][]=$link."|".$code."|".$date;

	}

	// Print an array of the links (for debugging)
	function printDateLinks() {
		global $my_dates_arr;

		echo "<pre>";
		print_r($my_dates_arr);
		echo "</pre>";

	}

	function getDateLink($day, $month, $year) {
		global $my_dates_arr, $Weblogs, $Current_weblog, $temp_entry, $log_url, $Cfg;

		$index=sprintf("%04d%02d%02d", $year, $month, $day);

		if (!isset($my_dates_arr[$index])) {
			return "";
		} else {

			$html="";

			foreach ($my_dates_arr[$index] as $entry) {

				list($title, $code, $date) = explode("|", $entry);

				$temp_entry['date']=$date;
				$temp_entry['title']=$title;
				$temp_entry['code']=$code;
				
				//if its 'live' we must prepend the log_url. 
				$url = make_filelink($code, "", "");


				// make a popup, if set to do so.
				if ($Weblogs[$Current_weblog]['comment_pop']==1) {
					//$quoted = "\";\n  my_html += '\"';\n  my_html += \"";
					$quoted = "\\\"";
					$popup= sprintf("onclick=%swindow.open('%s', 'popuplink', 'width=%s,height=%s,directories=no,location=no,scrollbars=yes,menubar=no,status=yes,toolbar=no,resizable=yes'); return false%s", $quoted, $url, $Weblogs[$Current_weblog]['comment_width'], $Weblogs[$Current_weblog]['comment_height'], $quoted);
				} else {
					$popup="";
				}

				// compensate for quotes in titles..
				$title = str_replace('"', '\"', $title);
				
				$html .= sprintf("&raquo; <a href='%s' %s class='calendarlink'>%s<\/a><br \/>", $url, $popup, $title);
			}

			

			echo "\nfunction showcal_$index() {\n";
			echo "  var my_html = \"".$html."\";\n";
			echo "  document.getElementById('cal_info').innerHTML = my_html;\n";
			echo "}\n";

			return "javascript:showcal_$index();";
		}
	}

}
//
// end of class
//



// If no month/year set, use current month/year
$d = getdate(time());

// set the month..
if (isset($_GET['month'])) {
	$month = $_GET['month'];
} else {
	$month = $d["mon"];
}

// set the year..
if (isset($_GET['year'])) {
	$year = $_GET['year'];
} else {
    $year = $d["year"];
}


// include pv_core, if not done already.
if (file_exists(realpath($pivot_path). '/pv_core.php')) {
	include_once( realpath($pivot_path). '/pv_core.php');
}

// override the pivot_url and log_url
if ( (!isset($Paths['pivot_url'])) || ($Paths['pivot_url']=='') ) {
	$Paths['pivot_url'] = $pivot_url;
	$Paths['log_url'] = $log_url;
}

if ( (!isset($Current_weblog)) || ($Current_weblog=='') ) {
	$Current_weblog = $weblog;
}

$db = new db();

// initialize the calendar
$cal = new MyCalendar;

// get a list of the entries for the current month
list($start_date, $stop_date) = getdaterange(sprintf("%02d-%02d-01-00-00",$year,$month), 'month' );
$list_entries = $db->getlist_range($start_date, $stop_date,"","", FALSE);


foreach ($list_entries as $list_entry) {

  
   /************* THIS SECTION ADDED BY DAVID GROSSE ON 2/16/2004 *************/

    $dg_rightblog_display_flag = false;
    $dg_these_cats = $list_entry["category"];
    $dg_blog_cats = array();

    $dg_exclude_subweblogs = array();   /* this array is where you list any
                                                      subweblogs you don't want to appear
                                                      in the calendar  */
   
    foreach($Weblogs[$Current_weblog]["sub_weblog"] as $dg_this_subweblog_key=>$dg_this_subweblog) {  //go through each subweblog
      if(!in_array($dg_this_subweblog_key, $dg_exclude_subweblogs)) {                                 //except those we're excluding
        foreach($dg_this_subweblog["categories"] as $dg_this_subweblog_cat) {                         //and go through each of their categories
          if(!in_array($dg_this_subweblog_cat, $dg_blog_cats)) {                                      //and if it's not already in our list
            $dg_blog_cats[] = $dg_this_subweblog_cat;                                                 //then add it
          }
        }
      }
    }
   
    //var_dump($dg_these_cats); var_dump($dg_blog_cats); die;
   
    foreach($dg_these_cats as $dg_this_cat) {           //now we go through the categories
      if(in_array($dg_this_cat, $dg_blog_cats)) {       //to see if any of them are part of this weblog
        $dg_rightblog_display_flag = true;              //and if they are, the blog's a keeper.
      }
    }

	if (!count($dg_blog_cats)) {
		$dg_rightblog_display_flag = true;
	}
 
    
   /************* END OF SECTION ADDED BY DAVID GROSSE ***********************/
    

	if ($list_entry['status']=="publish" && $dg_rightblog_display_flag) {
		$cal->setDateLink($list_entry['date'], trimtext($list_entry['title'],17), $list_entry['code'] );
	} 
}



global $Language;
global $CurrentLanguage;
global $Weblogs;

LoadWeblogLanguage($Weblogs[$Current_weblog]['language']);

$months_lang = Array(i18n_ucfirst(lang('months',0)), i18n_ucfirst(lang('months',1)), i18n_ucfirst(lang('months',2)), 
		i18n_ucfirst(lang('months',3)), i18n_ucfirst(lang('months',4)), i18n_ucfirst(lang('months',5)), 
		i18n_ucfirst(lang('months',6)), i18n_ucfirst(lang('months',7)), i18n_ucfirst(lang('months',8)), 
		i18n_ucfirst(lang('months',9)), i18n_ucfirst(lang('months',10)), i18n_ucfirst(lang('months',11)));

$cal->setMonthNames($months_lang);

$days_lang = Array(lang('days_calendar',0), lang('days_calendar',1), lang('days_calendar',2), lang('days_calendar',3), lang('days_calendar',4), lang('days_calendar',5), lang('days_calendar',6));

$cal->setDayNames($days_lang);

/* Omar Pulido */ 

// JM =*=*= 2004/10/03
$days_name_lang = array( lang( 'days',0 ),lang( 'days',1 ),lang( 'days',2 ),lang( 'days',3 ),lang( 'days',4 ),lang( 'days',5 ),lang( 'days',6 )) ;
$cal->setDayNamesLong( $days_name_lang ) ;
// END

// output the calendar
echo $cal->getMonthView($month, $year);


?>


<div id="cal_info"><!-- this is the empty div in which the links will be shown. --></div>


<?php

// PHP Calendar Class Version 1.4 (5th March 2001)
//  
// Copyright David Wilkinson 2000 - 2001. All Rights reserved.
// 
// This software may be used, modified and distributed freely
// providing this copyright notice remains intact at the head 
// of the file.
//
// This software is freeware. The author accepts no liability for
// any loss or damages whatsoever incurred directly or indirectly 
// from the use of this script. The author of this software makes 
// no claims as to its fitness for any purpose whatsoever. If you 
// wish to use this software you should first satisfy yourself that 
// it meets your requirements.
//
// URL:   http://www.cascade.org.uk/software/php/calendar/
// Email: davidw@cascade.org.uk


class Calendar
{
    /*
        Constructor for the Calendar class
    */
    function Calendar()
    {
    }
    
    
    /*  JM =*=*= 2004/10/04
        Set the array of strings used to label the days of the week in usual long format for
        accessibility. This array must contain seven elements, one for each day of the week. 
        The first entry in this array represents Sunday. 
    */
    function setDayNamesLong($names)
    {
        $this->dayNamesLong = $names;
    }
    /*  JM =*=*= 2004/10/04
        Get the array of strings used to label the days of the week in usual long format for
        accessibility. This array contains seven elements, one for each day of the week. 
        The first entry in this array represents Sunday. 
    */
    function getDayNamesLong()
    {
        return $this->dayNamesLong;
    }
		// END

    /*
        Get the array of strings used to display the days of the week. This array contains seven 
        elements, one for each day of the week. The first entry in this array represents Sunday. 
    */
    function getDayNames()
    {
        return $this->dayNames;
    }
    

    /*
        Set the array of strings used to display the days of the week. This array must contain seven 
        elements, one for each day of the week. The first entry in this array represents Sunday. 
    */
    function setDayNames($names)
    {
        $this->dayNames = $names;
    }
    
    /*
        Get the array of strings used to label the months of the year. This array contains twelve 
        elements, one for each month of the year. The first entry in this array represents January. 
    */
    function getMonthNames()
    {
        return $this->monthNames;
    }
    
    /*
        Set the array of strings used to label the months of the year. This array must contain twelve 
        elements, one for each month of the year. The first entry in this array represents January. 
    */
    function setMonthNames($names)
    {
        $this->monthNames = $names;
    }
    
    
    
    /* 
        Gets the start day of the week. This is the day that appears in the first column
        of the calendar. Sunday = 0.
    */
      function getStartDay()
    {
        return $this->startDay;
    }
    
    /* 
        Sets the start day of the week. This is the day that appears in the first column
        of the calendar. Sunday = 0.
    */
    function setStartDay($day)
    {
        $this->startDay = $day;
    }
    
    
    /* 
        Gets the start month of the year. This is the month that appears first in the year
        view. January = 1.
    */
    function getStartMonth()
    {
        return $this->startMonth;
    }
    
    /* 
        Sets the start month of the year. This is the month that appears first in the year
        view. January = 1.
    */
    function setStartMonth($month)
    {
        $this->startMonth = $month;
    }
    
    
    /*
        Return the URL to link to in order to display a calendar for a given month/year.
        You must override this method if you want to activate the "forward" and "back" 
        feature of the calendar.
        
        Note: If you return an empty string from this function, no navigation link will
        be displayed. This is the default behaviour.
        
        If the calendar is being displayed in "year" view, $month will be set to zero.
    */
    function getCalendarLink($month, $year)
    {
        return "";
    }
    
    /*
        Return the URL to link to  for a given date.
        You must override this method if you want to activate the date linking
        feature of the calendar.
        
        Note: If you return an empty string from this function, no navigation link will
        be displayed. This is the default behaviour.
    */
    function getDateLink($day, $month, $year)
    {
        return "";
    }


    /*
        Return the HTML for the current month
    */
    function getCurrentMonthView()
    {
        $d = getdate(time());
        return $this->getMonthView($d["mon"], $d["year"]);
    }
    

    /*
        Return the HTML for the current year
    */
    function getCurrentYearView()
    {
        $d = getdate(time());
        return $this->getYearView($d["year"]);
    }
    
    
    /*
        Return the HTML for a specified month
    */
    function getMonthView($month, $year)
    {
        return $this->getMonthHTML($month, $year);
    }
    

    /*
        Return the HTML for a specified year
    */
    function getYearView($year)
    {
        return $this->getYearHTML($year);
    }
    
    
    
    /********************************************************************************
    
        The rest are private methods. No user-servicable parts inside.
        
        You shouldn't need to call any of these functions directly.
        
    *********************************************************************************/


    /*
        Calculate the number of days in a month, taking into account leap years.
    */
    function getDaysInMonth($month, $year)
    {
        if ($month < 1 || $month > 12)
        {
            return 0;
        }
   
        $d = $this->daysInMonth[$month - 1];
   
        if ($month == 2)
        {
            // Check for leap year
            // Forget the 4000 rule, I doubt I'll be around then...
        
            if ($year%4 == 0)
            {
                if ($year%100 == 0)
                {
                    if ($year%400 == 0)
                    {
                        $d = 29;
                    }
                }
                else
                {
                    $d = 29;
                }
            }
        }
    
        return $d;
    }


    /*
        Generate the HTML for a given month
    */
    function getMonthHTML($m, $y, $showYear = 1)
    {
        $d = getdate(time());
        $mythismonth = $d["mon"];
	$mythisyear = $d["year"];

        $s = "";
        
        $a = $this->adjustDate($m, $y);
        $month = $a[0];
        $year = $a[1];        
        
    	$daysInMonth = $this->getDaysInMonth($month, $year);
    	$date = getdate(mktime(12, 0, 0, $month, 1, $year));
    	
    	$first = $date["wday"];
    	$monthName = $this->monthNames[$month - 1];
    	
    	$prev = $this->adjustDate($month - 1, $year);
    	$next = $this->adjustDate($month + 1, $year);
    	
    	if ($showYear == 1)
    	{
    	    $prevMonth = $this->getCalendarLink($prev[0], $prev[1]);
    	    $nextMonth = $this->getCalendarLink($next[0], $next[1]);
    	}
    	else
    	{
    	    $prevMonth = "";
    	    $nextMonth = "";
    	}
    	
    	$header = $monthName . (($showYear > 0) ? " " . $year : "");
    	

// changes for accessibility by JM =*=*= 2004/10/03
    	$s .= '<table summary="'.lang( 'pcalendar','calendar_summary' ).'" class="calendar" cellspacing="0" cellpadding="0">'."\n" ;
			$s .= '<thead>'."\n" ;
    	$s .= '<tr>'."\n" ;
			$s .= '<th align="center" valign="top" class="calendarHeader calendarHeaderControl">'.(( ''==$prevMonth ) ? '&nbsp;' : '<a href="'.$prevMonth.'" target="_self">&laquo;</a>' ).'</th>'."\n" ;
			$s .= '<th align="center" valign="top" scope="colgroup" class="calendarHeader" colspan="5">'.$header.'</th>'."\n";

			if(( $m==$mythismonth ) && ( $y==$mythisyear )) {
				$s .= '<th align="center" valign="top" class="calendarHeader"></th>'."\n" ;
			} else {
				$s .= '<th align="center" valign="top" class="calendarHeader calendarHeaderControl">'.(( ''==$nextMonth ) ? '&nbsp;' : '<a href="'.$nextMonth.'" target="_self">&raquo;</a>' ).'</th>'."\n" ;
			}

			$s .= '</tr>'."\n";
			$s .= '<tr>'."\n" ;
			$s .= '<th id="'.$this->dayNamesLong[( $this->startDay )%7]  .'" align="center" valign="top" class="calendarHeader">'.$this->dayNames[( $this->startDay )%7]  .'</th>'."\n" ;
			$s .= '<th id="'.$this->dayNamesLong[( $this->startDay+1 )%7].'" align="center" valign="top" class="calendarHeader">'.$this->dayNames[( $this->startDay+1 )%7].'</th>'."\n" ;
			$s .= '<th id="'.$this->dayNamesLong[( $this->startDay+2 )%7].'" align="center" valign="top" class="calendarHeader">'.$this->dayNames[( $this->startDay+2 )%7].'</th>'."\n" ;
			$s .= '<th id="'.$this->dayNamesLong[( $this->startDay+3 )%7].'" align="center" valign="top" class="calendarHeader">'.$this->dayNames[( $this->startDay+3 )%7].'</th>'."\n" ;
			$s .= '<th id="'.$this->dayNamesLong[( $this->startDay+4 )%7].'" align="center" valign="top" class="calendarHeader">'.$this->dayNames[( $this->startDay+4 )%7].'</th>'."\n" ;
			$s .= '<th id="'.$this->dayNamesLong[( $this->startDay+5 )%7].'" align="center" valign="top" class="calendarHeader">'.$this->dayNames[( $this->startDay+5 )%7].'</th>'."\n" ;
			$s .= '<th id="'.$this->dayNamesLong[( $this->startDay+6 )%7].'" align="center" valign="top" class="calendarHeader">'.$this->dayNames[( $this->startDay+6 )%7].'</th>'."\n" ;
			$s .= '</tr>'."\n";
			$s .= '</thead>'."\n" ;
// END
			// We need to work out what date to start at so that the first appears in the correct column
			$d = $this->startDay + 1 - $first ;
			while ($d > 1)
			{
					$d -= 7;
			}

			// Make sure we know when today is, so that we can use a different CSS style
			$today = getdate(time());
		
			echo "<script type=\"text/javascript\">\n";
			echo "/*<![CDATA[*/\n";
			echo "// this is where we write the javascript for the dates.\n";


    	while ($d <= $daysInMonth) {
    		$s .= "<tr>\n";       
  
				for ($i = 0; $i < 7; $i++) {
					$class = ($year == $today["year"] && $month == $today["mon"] && $d == $today["mday"]) ? "calendarToday" : "calendar" ;

					// changes for accessibility by JM =*=*= 2004/10/04
					// $s .= "<td class=\"$class\" align=\"right\" valign=\"top\">";
					$s .='<td headers="'.$this->dayNamesLong[($this->startDay+$i)%7].'" class="'.$class.'" align="right" valign="top">' ;
					// END

					if(( $d > 0 )&&( $d <= $daysInMonth )) {
						$link = $this->getDateLink($d, $month, $year);
						$s .= (($link == "") ? $d : "<a href=\"$link\" $target>$d</a>");
					} else {
						$s .= '&nbsp;' ;
					}
      	        $s .= "</td>\n";       
        	    $d++;
    	    }
				$s .= "</tr>\n";    
			}

			echo "\n".'/*]]>*/'."\n" ;
			echo '</script>'."\n" ;
			echo '<noscript>'.lang( 'pcalendar','calendar_noscript' ).'</noscript>' ;

    	$s .= "</table>\n";
    	
    	return $s;  	
    }
    
    
    /*
        Generate the HTML for a given year
    */
    function getYearHTML($year)
    {
        $s = "";
    	$prev = $this->getCalendarLink(0, $year - 1);
    	$next = $this->getCalendarLink(0, $year + 1);
        
        $s .= "<table class=\"calendar\" border=\"0\">\n";
        $s .= "<tr>";
    	$s .= "<td class=\"calendarHeader\" align=\"center\" valign=\"top\" align=\"left\">" . (($prev == "") ? "&nbsp;" : "<a href=\"$prev\">&lt;&lt;</a>")  . "</td>\n";
        $s .= "<td class=\"calendarHeader\" valign=\"top\" align=\"center\">" . (($this->startMonth > 1) ? $year . " - " . ($year + 1) : $year) ."</td>\n";
    	$s .= "<td class=\"calendarHeader\" align=\"center\" valign=\"top\" align=\"right\">" . (($next == "") ? "&nbsp;" : "<a href=\"$next\">&gt;&gt;</a>")  . "</td>\n";
        $s .= "</tr>\n";
        $s .= "<tr>";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(0 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(1 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(2 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "</tr>\n";
        $s .= "<tr>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(3 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(4 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(5 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "</tr>\n";
        $s .= "<tr>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(6 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(7 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(8 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "</tr>\n";
        $s .= "<tr>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(9 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(10 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(11 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "</tr>\n";
        $s .= "</table>\n";
        
        return $s;
    }

    /*
        Adjust dates to allow months > 12 and < 0. Just adjust the years appropriately.
        e.g. Month 14 of the year 2001 is actually month 2 of year 2002.
    */
    function adjustDate($month, $year)
    {
        $a = array();  
        $a[0] = $month;
        $a[1] = $year;
        
        while ($a[0] > 12)
        {
            $a[0] -= 12;
            $a[1]++;
        }
        
        while ($a[0] <= 0)
        {
            $a[0] += 12;
            $a[1]--;
        }
        
        return $a;
    }

    /* 
        The start day of the week. This is the day that appears in the first column
        of the calendar. Sunday = 0.
    */
    var $startDay = 0;

    /* 
        The start month of the year. This is the month that appears in the first slot
        of the calendar in the year view. January = 1.
    */
    var $startMonth = 1;

    /*
        The labels to display for the days of the week. The first entry in this array
        represents Sunday.
    */
    var $dayNames = array("S", "M", "T", "W", "T", "F", "S");
    
    /*
        The labels to display for the months of the year. The first entry in this array
        represents January.
    */
    var $monthNames = array("January", "February", "March", "April", "May", "June",
                            "July", "August", "September", "October", "November", "December");
                            
                            
    /*
        The number of days in each month. You're unlikely to want to change this...
        The first entry in this array represents January.
    */
    var $daysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    
}


error_reporting($old_errrep);
?>
