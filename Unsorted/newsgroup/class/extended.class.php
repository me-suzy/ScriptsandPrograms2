<?

// ---------------------------------------------------------------------------- //
// MyNewsGroups :) 'Share your knowledge'
// Copyright (C) 2002 Carlos Sánchez Valle (yosoyde@bilbao.com)

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
// ---------------------------------------------------------------------------- //


//------------------------------------------------------------------//
// extended.classes.php
// Author: Carlos Sánchez
// Created: 23/06/01
//
// Description: We extend the necessary classes in this script.
//
//
//------------------------------------------------------------------//
?>
<?




// PHPLib DB abstraction class
class My_db extends DB_Sql {
	
	var $Host;
	var $Database;
	var $User;
	var $Password;

	function My_db() {
		
		global $myng_db;
		$this->Host = $myng_db['host'];
		$this->Database = $myng_db['database'];
		$this->User = $myng_db['user'];
		$this->Password = $myng_db['password'];

		$this->DB_Sql();
	}
}


// Calendar Class
class MyNGCalendar extends Calendar
{
    function getDateLink($day, $month, $year)
    {
    	
    	global $grp_name;
    	
        $db = new My_db;
        $db->connect();

        // Get the UNIX_TIMESTAMPS for the dates of the calendar.    
        //echo $month.$day.$year;
        $time_stamp = mktime (0,0,0,$month,$day,$year);
        if($time_stamp != -1){
        	$date = date("Y-m-d",$time_stamp);
        }
        //echo $date."<br>";
        
        //$query = "SELECT grp_id,COUNT(*) FROM `myng_".real2table($grp_name)."`,myng_newsgroup
        //         WHERE FROM_UNIXTIME(UNIX_TIMESTAMP('".$date."'),'%d%m') = FROM_UNIXTIME(date,'%d%m') AND grp_name = newsgroup GROUP BY grp_name";
        
        // Convert to Unix timestamp a single time inside PHP, instead of 
        // making MySQL do it for every row.  This will make the query run
        // faster, especially if the table is indexed on the date, since MySQL
 		// can use a date index to do most of the calculations we need.
        $date_min = strtotime($date); 
        $date_max = strtotime("$date +1 day");
 
        $query = "SELECT grp_id,COUNT(*) FROM `myng_".real2table($grp_name)."`,myng_newsgroup
                 WHERE date>='$date_min' AND date<='$date_max' AND grp_name = newsgroup GROUP BY grp_name";
        
        //echo $query."<br>";   
             
        $db->query($query);
        $db->next_record();

        $num_articles = $db->Record[1]; 
        //echo "Date:".$date."Num:".$num_articles."<br>";
        
        if ($num_articles > 1)
        {
            $link = "tree.php?grp_id=".$db->Record[0]."&date=".urlencode($date);
        }else{
        	$link = "";	
        }
        return $link;
    }
    
    function getCalendarLink($month, $year)
    {
        // Redisplay the current page, but with some parameters
        // to set the new month and year
        $script_name = $_SERVER['SCRIPT_NAME'];
        $query_string = $_SERVER['QUERY_STRING'];
        //echo $query_string;
        
        return $script_name."?".$query_string."&month=$month&year=$year";
    }
}

?>