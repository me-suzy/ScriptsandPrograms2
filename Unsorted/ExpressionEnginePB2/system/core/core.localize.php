<?php

/*
=====================================================
 ExpressionEngine - by pMachine
-----------------------------------------------------
 Nullified by GTT
-----------------------------------------------------
 Copyright (c) 2003 - 2004 pMachine, Inc.
=====================================================
 THIS IS COPYRIGHTED SOFTWARE
 PLEASE READ THE LICENSE AGREEMENT
=====================================================
 File: core.localize.php
-----------------------------------------------------
 Purpose: Date/time and localization functions
=====================================================
*/


if ( ! defined('EXT'))
{
    exit('Invalid file request');
}


class Localize {
  
  var $now 		= '';  // Local server time as GMT  
  var $ctz 		=  0;  // Current user's timezone setting
  var $zones	= array();
  
  
  
    // -------------------------------------
    //  Constructor
    // -------------------------------------
        
    // Fetch the current local server time and convert it to GMT

    function Localize()
    {    
        $this->now		= $this->set_gmt(); 
        $this->zones	= $this->zones();
    }
    // END
    
    
    // -------------------------------------
    //  Set GMT time
    // -------------------------------------
    
    // Takes a Unix timestamp as input and returns it as GMT
    
    function set_gmt($now = '')
    {    
        if ($now == '')
        {
            $now = time(); 
        }
            
        $time =  mktime( gmdate("H", $now),
                         gmdate("i", $now),
                         gmdate("s", $now),
                         gmdate("m", $now),
                         gmdate("d", $now),
                         gmdate("Y", $now)
                       );   

        // mktime() has a bug that causes it to fail during the DST "spring forward gap"
        // when clocks are offset an hour forward (around April 4).  Instead of returning a valid
        // timestamp, it returns -1.  Basically, mktime() gets caught in purgatory, not 
        // sure if DST is active or not.  As a work-around for this we'll test for "-1",
        // and if present, return the current time.  This is not a great solution, as this time
        // may not be what the user intened, but it's preferable than storing -1 as the timestamp, 
        // which correlates to: 1969-12-31 16:00:00. 

        if ($time == -1)
        {
            return $this->set_gmt();
        }
        else
        {
            return $time;
        }
    }    
    // END
    
    
    // ---------------------------------------------
    //   Convert a MySQL timestamp to GMT
    // ---------------------------------------------
    
    function timestamp_to_gmt($str = '')
    {    
        // YYYYMMDDHHMMSS
        
        return  $this->set_gmt( mktime( substr($str,8,2),
                                        substr($str,10,2),
                                        substr($str,12,2),
                                        substr($str,4,2),
                                        substr($str,6,2),
                                        substr($str,0,4)
                                      )
                               );   
    }
    // END


    // --------------------------------------------
    //  Set localized time
    // --------------------------------------------
    
    // Converts GMT time to the localized values of the current logged-in user

    function set_localized_time($now = '')
    {
        global $PREFS, $SESS;
                
        if ($now == '')
        {
            $now = $this->now;
        }
        
        // If the current user has not set localization preferences
        // we'll instead use the master server settings
        
        if ($SESS->userdata['timezone'] == '')
        {
            return $this->set_server_time($now);   
        }
             
        $now += $this->zones[$SESS->userdata['timezone']] * 3600;
    
        if ($SESS->userdata['daylight_savings'] == 'y')
        {
            $now += 3600;
        }
        
        $now = $this->set_server_offset($now);

        return $now;
    }
    // END    

 
    // --------------------------------------------
    //  Set localized server time
    // --------------------------------------------

    // Converts GMT time to the localized server timezone

    function set_server_time($now = '')
    {
        global $PREFS;
        
        if ($now == '')
        {
            $now = $this->now;
        }
        
        if ($tz = $PREFS->ini('server_timezone'))
        {
           $now += $this->zones[$tz] * 3600;
        }
        
        if ($PREFS->ini('daylight_savings') == 'y')
        {
            $now += 3600;
        }
        
        $now = $this->set_server_offset($now);
                
        return $now;
    }
    // END    
    

    // --------------------------------------------
    //  Set server offset
    // --------------------------------------------
    
    // Takes a Unix timestamp as input and adds/subtracts the number of 
    // minutes specified in the master server time offset preference
    
    // The optional second parameter lets us reverse the offset (positive number becomes negative)
    // We use the second paramter with set_localized_offset()

    function set_server_offset($time, $reverse = 0)
    {
        global $PREFS;
                
        $offset = ( ! $PREFS->ini('server_offset')) ? 0 : $PREFS->ini('server_offset') * 60;
        
        if ($offset == 0)
        {
            return $time;
        }
        
        if ($reverse == 1)
        {
            $offset = $offset * -1;
        }
        
        $time += $offset;
        
        return $time;
    }
    // END


    // --------------------------------------------
    //  Set localized offset
    // --------------------------------------------
    
    // This function lets us calculate the time difference between the
    // timezone of the current user and the timezone of the server hosting
    // the site.  It solves a dilemma we face when using functions like mktime()
    // which base their output on the server's timezone.  When a weblog entry is
    // submitted, the entry date is converted to a Unix timestamp.  But since
    // the user submitting the entry might not be in the same timezone as the 
    // server we need to offset the timestamp to reflect this difference.
    
    function set_localized_offset()
    {
        global $PREFS, $SESS;
        
        $offset = 0;
                
        if ($SESS->userdata['timezone'] == '')
        {
            if ($tz = $PREFS->ini('server_timezone'))
            {
               $offset += $this->zones[$tz];
            }
            
            if ($PREFS->ini('daylight_savings') == 'y')
            {
                $offset += 1;
            }
        }
        else
        {             
            $offset += $this->zones[$SESS->userdata['timezone']];  
             
            if ($SESS->userdata['daylight_savings'] == 'y')
            {
                $offset += 1;
            }
        } 
                
        // Grab local time
        
        $time = time();
        
        // Determine the number of seconds between the local time and GMT
        
        $time -= $this->now;
        
        // Offset this number based on the server offset (if it exists)
        
        $time = $this->set_server_offset($time, 1);
        
        // Divide by 3600, making our offset into hours
        
        $time = $time/3600;
        
        // add or subtract it from our timezone offset
        
        $offset -= $time;
        
        // Multiply by -1 to invert the value (positive becomes negative and vice versa)
        
        $offset = $offset * -1;
        
        // Convert it to seconds
                     
        if ($offset != 0)
            $offset = $offset * (60 * 60);
        
        return $offset;
    }
    // END    
    
    
    
    
    // -------------------------------------
    //  Human-readable time
    // -------------------------------------
    
    // Formats Unix/GMT timestamp to the following format: 2003-08-21 11:35 PM
    
    // Will also switch to Euro time based on the user preference

    function set_human_time($now = '', $localize = TRUE, $seconds = FALSE)
    {
        global $PREFS, $SESS;
        
        $fmt = ($SESS->userdata['time_format'] != '') ? $SESS->userdata['time_format'] : $PREFS->ini('time_format');
    
        if ($localize)
        {
            $now = $this->set_localized_time($now);
        }
            
        $r  = date('Y', $now).'-'.date('m', $now).'-'.date('d', $now).' ';
            
        if ($fmt == 'us')
        {
            $r .= date('h', $now).':'.date('i', $now).' '.date('A', $now);
        }
        else
        {
            $r .= date('H', $now).':'.date('i', $now);
        }
        
        if ($seconds)
        {
            $r .= ' '.date('s', $now);
        }
            
        return $r;
    }
    // END
    
    
    // -------------------------------------------------------
    //  Convert "human" date to GMT
    // -------------------------------------------------------

    // Converts the human-readable date used in the weblog entry 
    // submission page back to Unix/GMT

    function convert_human_date_to_gmt($datestr = '')
    {
        global $LANG, $SESS, $PREFS;
    
        if ($datestr == '')
            return false;
                    
            $datestr = trim($datestr);
            
            $datestr = preg_replace("/\040+/", "\040", $datestr);

            if ( ! ereg("^[0-9]{2,4}\-[0-9]{1,2}\-[0-9]{1,2}\040[0-9]{1,2}:[0-9]{1,2}.*$", $datestr))
            {
                return $LANG->line('invalid_date_formatting');
            }

            $split = preg_split("/\040/", $datestr);

            $ex = explode("-", $split['0']);            
            
            $year  = (strlen($ex['0']) == 2) ? '20'.$ex['0'] : $ex['0'];
            $month = (strlen($ex['1']) == 1) ? '0'.$ex['1']  : $ex['1'];
            $day   = (strlen($ex['2']) == 1) ? '0'.$ex['2']  : $ex['2'];

            $ex = explode(":", $split['1']); 
            
            $hour = (strlen($ex['0']) == 1) ? '0'.$ex['0'] : $ex['0'];
            $min  = (strlen($ex['1']) == 1) ? '0'.$ex['1'] : $ex['1'];

            if (isset($ex['2']) AND ereg("[0-9]{1,2}", $ex['2']))
            {
                $sec  = (strlen($ex['2']) == 1) ? '0'.$ex['2'] : $ex['2'];
            }
            else
            {
                $sec = date('s');
            }
            
            if (isset($split['2']))
            {
                $ampm = strtolower($split['2']);
                
                if (substr($ampm, 0, 1) == 'p' AND $hour < 12)
                    $hour = $hour + 12;
                    
                if (substr($ampm, 0, 1) == 'a' AND $hour == 12)
                    $hour =  '00';
                    
                if (strlen($hour) == 1)
                    $hour = '0'.$hour;
            }

        if ($year < 1902 || $year > 2037)            
        {
            return $LANG->line('date_outside_of_range');
        }
                
        $time = $this->set_gmt(mktime($hour, $min, $sec, $month, $day, $year));

        // Offset the time by one hour if the user is submitting a date
        // in the future or past so that it is no longer in the same
        // Daylight saving time.
        
        if (date("I", $this->now))
        { 
            if ( ! date("I", $time))
            {
               $time -= 3600;            
            }
        }
        else
        {
            if (date("I", $time))
            {
                $time += 3600;           
            }
        }

        $time += $this->set_localized_offset();

        return $time;      
    }
    // END


    // ----------------------------------
    //   Format timespan
    // ----------------------------------  
    
    // Returns a span of seconds in this format: 10 days 14 hours 36 minutes 47 seconds

	function format_timespan($seconds = '')
	{
		global $LANG;
				
		if ($seconds == '')
			return '';	

		$str = '';
		
		$years = floor($seconds / 31536000);
		
		if ($years > 0)
		{		
			$str .= $years.' '.$LANG->line(($years	> 1) ? 'years' : 'year').', ';
		}	
		
		$seconds -= $years * 31536000;
		
		$months = floor($seconds / 2628000);
		
		if ($years > 0 || $months > 0)
		{
			if ($months > 0)
			{		
				$str .= $months.' '.$LANG->line(($months	> 1) ? 'months'	: 'month').', ';
			}	
		
			$seconds -= $months * 2628000;
		}

		$weeks = floor($seconds / 604800);
		
		if ($years > 0 || $months > 0 || $weeks > 0)
		{
			if ($weeks > 0)
			{				
				$str .= $weeks.' '.$LANG->line(($weeks > 1) ? 'weeks' : 'week').', ';
			}
			
			$seconds -= $weeks * 604800;
		}			

		$days = floor($seconds / 86400);
		
		if ($months > 0 || $weeks > 0 || $days > 0)
		{
			if ($days > 0)
			{			
				$str .= $days.' '.$LANG->line(($days > 1) ? 'days' : 'day').', ';
			}
		
			$seconds -= $days * 86400;
		}
		
		$hours = floor($seconds / 3600);
		
		if ($days > 0 || $hours > 0)
		{
			if ($hours > 0)
			{
				$str .= $hours.' '.$LANG->line(($hours > 1) ? 'hours' : 'hour').', ';
			}
			
			$seconds -= $hours * 3600;
		}
		
		$minutes = floor($seconds / 60);
		
		if ($days > 0 || $hours > 0 || $minutes > 0)
		{
			if ($minutes > 0)
			{		
				$str .= $minutes.' '.$LANG->line(($minutes	> 1) ? 'minutes' : 'minute').', ';
			}
			
			$seconds -= $minutes * 60;
		}
		
		if ($str == '')
		{
			$str .= $seconds.' '.$LANG->line(($seconds	> 1) ? 'seconds' : 'second').', ';
		}
				
		$str = substr(trim($str), 0, -1);
		           
		return $str;
	}
	// END
	


    // -------------------------------------
    //  Set partial GMT date
    // -------------------------------------
    
    // This function returns either the year (2003), month (12), or day (25) as GMT
    
    // Right now this function is used only by the weblog class so that dates
    // in the URI query string (/2003/12/) are converted to GMT before the query is run. 
    
    function set_partial_gmt($fmt = 'y', $year = '', $month = '', $day = '')
    {        
        $year  = ($year == '')  ? date('Y') : $year;
        $month = ($month == '') ? date('m') : $month;
        $day   = ($day == '')   ? date('d') : $day;
            
        $time = $this->set_gmt(mktime(date("H"), date("i"), date("s"), $month, $day, $year));
            
        if ($fmt == 'y')
        {
            return gmdate("Y", $time);
        }
        elseif ($fmt == 'm')
        {
            return gmdate("m", $time);
        }
        elseif ($fmt == 'd')
        {
            return gmdate("d", $time);
        }
    }
    // END
   

    // -------------------------------------------------
    //  Decode date string (via template parser)
    // -------------------------------------------------
    // This function takes a string containing text and
    // date codes and extracts only the codes.  Then,
    // the codes are converted to their actual timestamp 
    // values and the string is reassembled.

    function decode_date($datestr = '', $unixtime = '')
    {
        if ($datestr == '')
            return;

        if ( ! preg_match_all("/(%\S)/", $datestr, $matches))
               return;

        foreach ($matches['1'] as $val)
        {
            $datestr = str_replace($val, $this->convert_timestamp($val, $unixtime), $datestr);
        }
                 
        return $datestr;
    }
    // END



    // -------------------------------------
    //  Localize month name
    // -------------------------------------
    
    // Helper function used to translate month names.

    function localize_month($month = '')
    {
        $months = array(
                            '01' => array('Jan', 'January'),
                            '02' => array('Feb', 'February'),
                            '03' => array('Mar', 'March'),
                            '04' => array('Apr', 'April'),
                            '05' => array('May', 'May'),
                            '06' => array('Jun', 'June'),
                            '07' => array('Jul', 'July'),
                            '08' => array('Aug', 'August'),
                            '09' => array('Sep', 'September'),
                            '10' => array('Oct', 'October'),
                            '11' => array('Nov', 'November'),
                            '12' => array('Dec', 'December')
                        );
                        
        if (isset($months[$month]))
        {
            return $months[$month];
        }
    }
    // END
    
    
    
    // -------------------------------------
    //  Convert timestamp codes
    // -------------------------------------
    
    // All text codes are converted to the user-specified language.

    function convert_timestamp($which = '', $time = '')
    {
        global $LANG, $SESS;
    
        if ($which == '')
            return;
            
        if ($this->ctz == 0)
        {
            $this->ctz = $this->set_localized_timezone();
        }
            
        $time = $this->set_localized_time($time);        
        
        $ts = array(
                    '%a' 	=> $LANG->line(date('a', $time)), // am/pm
                    '%A' 	=> $LANG->line(date('A', $time)), // AM/PM
                    '%B' 	=> date('B', $time),
                    '%d' 	=> date('d', $time),
                    '%D' 	=> $LANG->line(date('D', $time)), // Mon, Tues
                    '%F' 	=> $LANG->line(date('F', $time)), // January, February
                    '%g' 	=> date('g', $time),
                    '%h' 	=> date('h', $time),
                    '%H' 	=> date('H', $time),
                    '%i' 	=> date('i', $time),
                    '%I' 	=> date('I', $time),
                    '%j' 	=> date('j', $time),
                    '%l' 	=> $LANG->line(date('l', $time)), // Monday, Tuesday
                    '%L' 	=> date('L', $time), 
                    '%m' 	=> date('m', $time),    
                    '%M' 	=> $LANG->line(date('M', $time)), // Jan, Feb
                    '%n' 	=> date('n', $time),
                    '%O' 	=> date('O', $time),
                    '%r' 	=> $LANG->line(date('D', $time)).date(', t ', $time).$LANG->line(date('M', $time)).date(' Y H:i:s O', $time),
                    '%s' 	=> date('s', $time),
                    '%S' 	=> date('S', $time),
                    '%t' 	=> date('t', $time),
                    '%T' 	=> $this->ctz,
                    '%U' 	=> date('U', $time),
                    '%w' 	=> date('w', $time),
                    '%W' 	=> date('W', $time),
                    '%y' 	=> date('y', $time),
                    '%Y' 	=> date('Y', $time),
                    '%Q'	=> $this->zone_offset($SESS->userdata['timezone']),
                    '%z' 	=> date('z', $time),
                    '%Z'	=> date('Z', $time)
                    );

        if (isset($ts[$which]))
        {
            return $ts[$which];
        }       
    }
    // END 



    // ----------------------------------------------
    //  GMT Offset - Ouputs:  +01:00
    // ----------------------------------------------   
    
	function zone_offset($tz = '')
	{
		if ($tz == '')
		{
			return '+00:00';
		}	
			
		$zone = trim($this->zones[$tz]);
		
		if ( ! strstr($zone, '.'))
		{
			$zone .= ':00';
		}
		
		$zone = str_replace(".5", ':30', $zone);
		
		if (substr($zone, 0, 1) != '-')
		{
			$zone = '+'.$zone;
		}
				
		$zone = preg_replace("/^(.{1})([0-9]{1}):(\d+)$/", "\\1D\\2:\\3", $zone);
		$zone = str_replace("D", '0', $zone);
     
		return $zone;        
	}
	// END



    // ----------------------------------------------
    //  Create timezone localization pull-down menu
    // ----------------------------------------------   
        
    function timezone_menu($default = '')
    {
        global $LANG, $DSP;
                
        $r  = "<div class='default'>";
        $r .= "<select name='server_timezone' class='select'>";
        
        foreach ($this->zones as $key => $val)
        {
            $selected = ($default == $key) ? " selected='selected'" : '';

			$r .= "<option value='{$key}'{$selected}>".$LANG->line($key)."</option>\n";
        }

        $r .= "</select>";
        $r .= "</div>";

        return $r;
    }
    // END


    // -------------------------------------
    //  Timezones
    // -------------------------------------
    
    // This array is used to render the localization pull-down menu

    function zones()
    {
        // Note: Don't change the order of these even though 
        // some items appear to be in the wrong order
            
        return array( 
                        'UM12' => -12,
                        'UM11' => -11,
                        'UM10' => -10,
                        'UM9'  => -9,
                        'UM8'  => -8,
                        'UM7'  => -7,
                        'UM6'  => -6,
                        'UM5'  => -5,
                        'UM4'  => -4,
                        'UM25' => -2.5,
                        'UM3'  => -3,
                        'UM2'  => -2,
                        'UM1'  => -1,
                        'UTC'  => 0,
                        'UP1'  => +1,
                        'UP2'  => +2,
                        'UP3'  => +3,
                        'UP25' => +2.5,
                        'UP4'  => +4,
                        'UP35' => +3.5,
                        'UP5'  => +5,
                        'UP45' => +4.5,
                        'UP6'  => +6,
                        'UP7'  => +7,
                        'UP8'  => +8,
                        'UP9'  => +9,
                        'UP85' => +8.5,
                        'UP10' => +10,
                        'UP11' => +11,
                        'UP12' => +12                    
                     );
    }
    // END



    // --------------------------------------------
    //  Set localized timezone
    // --------------------------------------------
    
    function set_localized_timezone()
    {
        global $PREFS, $SESS;
        
        $zones = array( 
                        'UM12' => 'MHT',
                        'UM11' => 'AKST',
                        'UM10' => 'HAW',
                        'UM9'  => 'ALA',
                        'UM8'  => 'PST',
                        'UM7'  => 'MST',
                        'UM6'  => 'CST',
                        'UM5'  => 'EST',
                        'UM4'  => 'AST',
                        'UM25' => 'NST',
                        'UM3'  => 'ADT',
                        'UM2'  => 'MAST',
                        'UM1'  => 'AZOT',
                        'UTC'  => 'GMT',
                        'UP1'  => 'MET',
                        'UP2'  => 'EET',
                        'UP3'  => 'BT',
                        'UP25' => 'IRT',
                        'UP4'  => 'ZP4',
                        'UP35' => 'AFT',
                        'UP5'  => 'ZP5',
                        'UP45' => 'IST',
                        'UP6'  => 'ZP6',
                        'UP7'  => 'WAST',
                        'UP8'  => 'CCT',
                        'UP9'  => 'JST',
                        'UP85' => 'CST',
                        'UP10' => 'EAST',
                        'UP11' => 'MAGT',
                        'UP12' => 'IDLE'                 
                     ); 
    
        
        if ($SESS->userdata['timezone'] == '')
        {
            $zone = $PREFS->ini('server_timezone');
        }
        else
        {
            $zone = $SESS->userdata['timezone'];
        }
        
        if (isset($zones[$zone]))
        {
            return $zones[$zone];        
        }        
    }
    // END    
        
}
// END CLASS
?>