<?php
/**************************************************************************
    FILENAME        :   calender.php
    PURPOSE OF FILE :   Displays the calender
    LAST UPDATED    :   20 November 2005
    COPYRIGHT       :   © 2005 CMScout Group
    WWW             :   www.cmscout.za.org
    LICENSE         :   GPL vs2.0
    
    

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
**************************************************************************/
?>
<?php
if (!defined('SCOUT_NUKE'))
    die("You have accessed this page illegally, please go use the main menu");
location("Calendar", $check["uid"]);
/********************************************Check if user is allowed*****************************************/

function number_days($month)
{
    switch($month)
    {
        case 1:
            $numdays = 31;
            break;
        case 2:
            if (($tempdates[0]%4 == 0) || ($tempdates[0]%100 == 0 && $tempdates[0]%400 == 0))
                $numdays =  29;
            else
                $numdays =  28;  
            break;
        case 3:
            $numdays =  31;
            break;
        case 4:
            $numdays =  30;
            break;
        case 5:
            $numdays = 31;
            break;
        case 6:
            $numdays = 30;
            break;
        case 7:
            $numdays = 31;
            break;
        case 8:
            $numdays = 31;
            break;
        case 9:
            $numdays = 30;
            break;
        case 10:
            $numdays = 31;
            break;
        case 11:
            $numdays = 30;
            break;
        case 12:
            $numdays = 31;
            break;
    }
    
    return $numdays;
}

$monthName = array(1 => "January",
                   2 => "February",
                   3 => "March",
                   4 => "April",
                   5 => "May",
                   6 => "June",
                   7 => "July",
                   8 => "August",
                   9 => "September",
                  10 => "October",
                  11 => "November",
                  12 => "December");

$monthnums = array("January" => 1,
                   "February" => 2,
                   "March" => 3,
                   "April" => 4,
                   "May" => 5,
                   "June" => 6,
                   "July" => 7,
                   "August" => 8,
                   "September" => 9,
                   "October" => 10,
                   "November" => 11,
                   "December" => 12);

$date = getdate();
$today["month"] = $date['month'];
$today["year"] = $date['year'];
$today["day"] = $date['mday'];

if (isset($_GET['item'])) $itemid = $_GET['item'];
if (isset($itemid))
{
	$calsql = $data->select_query("calendar_items", "WHERE id = $itemid AND allowed = 1");
	$calsqldetail = $data->select_query("calendar_detail", "WHERE id = $itemid");
	$item = $data->fetch_array($calsql);
	$detail = $data->fetch_array($calsqldetail);
	$show_detail = true;
	$tpl->assign("item", $item);
	$tpl->assign("detail", $detail);
} 
else 
{
    if (isset($_POST['monthbutton'])) 
    {
        $monthbutton = $_POST['monthbutton'];
	}
    
    if (isset($_POST['yearbutton'])) 
    {
        $yearbutton = $_POST['yearbutton'];
	}
    
    if (isset($_POST['month']) && $_POST['month'] != "") 
    {
        $month = $_POST["month"];
	}
    else 
    {
        $month = $today["month"];
    }
 
    if (isset($_POST['year']) && $_POST['year'] != "") 
    {
        $year = $_POST["year"];
	}
    else 
    {
        $year = $today["year"];
    } 

    if (isset($_POST['today'])) 
    {
        $year = $today['year'];
        $month = $today['month'];
	}


	$calendar = "";
	$found = false;
	$monthnum = 1;
	do 
    {
		if ($month == $monthName[$monthnum]) 
        {
            $found = true; 
            break; 
        }
		$monthnum++;
		if ($monthnum > 11) 
        {
            break;
        }
	} while ((!$found));
    
    
	if (isset($monthbutton) && $monthbutton == "Previous Month") 
    {
        $monthnum--;
        if ($monthnum < 1)
        {
            $year--;
            $monthnum = 12;
        }
        $month = $monthName[$monthnum];
    }
    
	if (isset($monthbutton) && $monthbutton == "Next Month")
    {
        $monthnum++;
        if ($monthnum > 12)
        {
            $year++;
            $monthnum = 1;
        }
        $month = $monthName[$monthnum];
    }

	if (isset($yearbutton) && $yearbutton == "Previous Year") 
    {
        $year--;
    }
    
	if (isset($yearbutton) && $yearbutton == "Next Year")
    {
        $year++;
    }

    $calsql = $data->select_query("calendar_items", "WHERE allowed = 1");
	$numitems = $data->num_rows($calsql);
	$items = array();
	while ($temp = $data->fetch_array($calsql))
    {
        $temp['startdate'] = explode('-', $temp['startdate']);
        $temp['startdate'][0] = (int)$temp['startdate'][0];
        $temp['startdate'][1] = (int)$temp['startdate'][1];
        $temp['startdate'][2] = (int)$temp['startdate'][2];
        $temp['enddate'] = explode('-', $temp['enddate']);
        $temp['enddate'][0] = (int)$temp['enddate'][0];
        $temp['enddate'][1] = (int)$temp['enddate'][1];
        $temp['enddate'][2] = (int)$temp['enddate'][2];
        $items[] = $temp;
    }

	$calendar = "<h1 align=\"center\">$month - $year</h1>
	<form name=\"form1\" method=\"post\" action=\"\">
	  <table width=\"100%\"  border=\"0\">
		<tr>
		  <td><div align=\"center\">
			<input type=\"submit\" name=\"yearbutton\" value=\"Previous Year\" class=\"button\" />
			<input type=\"submit\" name=\"monthbutton\" value=\"Previous Month\" class=\"button\" />
		  </div></td>
		  <td><div align=\"center\">Month: 
			  <select name=\"month\" onChange=\"form1.submit()\" class=\"inputbox\">";
			for ($i=1;$i<=12;$i++) 
            {
				$calendar .= "<option value=\"{$monthName[$i]}\"";
				if ($month == $monthName[$i]) 
                {
                    $calendar .= "selected";
                }
				$calendar .= ">{$monthName[$i]}</option>";
			}
            
		$calendar .= "  </select>
            Year: <select name=\"year\" onChange=\"form1.submit()\" class=\"inputbox\">";
			for ($i=2004;$i<=2015;$i++) 
            {
				$calendar .= "<option value=\"$i\"";
				if ($year == $i) 
                {
                    $calendar .= "selected";
                }
				$calendar .= ">$i</option>";
			}
            
		$calendar .= "  </select>
		  </div></td>
		  <td><div align=\"center\">
			<input type=\"submit\" name=\"monthbutton\" value=\"Next Month\" class=\"button\" />
            <input type=\"submit\" name=\"yearbutton\" value=\"Next Year\" class=\"button\" />
		  </div></td>
		</tr>
        <tr>
        <td colspan=\"3\">
        <div align=\"center\">
            <input type=\"submit\" name=\"today\" value=\"Goto Today\" class=\"button\" />            
        </div>
        </td>
        </tr>
	  </table>
	</form>
	<table width=\"100%\" class=\"table\" border=\"0\" cellspacing=\"1\" cellpadding=\"4\">
	  <tr>
		<th width=\"14%\">Sunday</th>
		<th width=\"14%\">Monday</th>
		<th width=\"14%\">Tuesday</th>
		<th width=\"14%\">Wednesday</th>
		<th width=\"14%\">Thursday</th>
		<th width=\"14%\">Friday</th>
		<th width=\"14%\">Saturday</th>
	  </tr>	  
	  <tr style=\"height: 100%\">";
      
	  $days = 0;
      $dateArray = getdate(mktime(0,0,0,$monthnum,1,$year));
	  for ($i=1;$i<$dateArray['wday']+1;$i++) {
	  	$days++;
		$calendar .= "<td valign=\"top\">&nbsp;</td>";
	  }
	  for ($i=1;$i<=number_days($monthnum);$i++)
      {
	  	$days++;
		if ($today["day"] != $i || $today["month"] != $month || $today['year'] != $year) 
        {
	 		$calendar .= "<td valign=\"top\" class=\"row\"><table width=\"100%\"><tr><td width=\"10%\"><div class=\"calendar_day\">$i</div></td><td>&nbsp;</td></tr><tr style=\"height: 80px\"><td colspan=\"2\" valign=\"top\">";
		}
        else
        {
	 		$calendar .= "<td valign=\"top\" class=\"row\"><table width=\"100%\"><tr><td width=\"10%\"><div class=\"calendar_today\">$i</div></td><td>&nbsp;</td></tr><tr style=\"height: 80px\"><td colspan=\"2\" valign=\"top\">";
		}
		for ($j=0;$j<=$numitems-1;$j++) 
        {           
            $printed = false;
            if (($items[$j]["enddate"][1] == $items[$j]["startdate"][1] && $items[$j]["enddate"][0] == $items[$j]["startdate"][0] && ($monthnums[$month] == $items[$j]["enddate"][1] || $monthnums[$month] == $items[$j]["startdate"][1]) && ($year == $items[$j]["enddate"][0] || $year == $items[$j]["startdate"][0]))) 
            {
                if ($items[$j]["startdate"][2] == $i || $items[$j]["enddate"][2] == $i || ($i > $items[$j]["startdate"][2] && $i < $items[$j]["enddate"][2])) 
				{
					if ($items[$j]["detail"]) 
                    {
						$calendar .= " * <span class=\"calendar_items\"><a href=\"index.php?page=calender&amp;item={$items[$j]["id"]}\">{$items[$j]["summary"]}</a></span><br />";
                        $printed = true;
					} 
                    else 
                    {
						$calendar .= " * <span class=\"calendar_items\">{$items[$j]["summary"]}</span><br />";
                        $printed = true;
					}
				}
                if (isset($items[$j+1]))
                {
                    $notlast = true;
                }
                else
                {
                    $notlast = false;
                }
			} 
            else 
            {               
                if (($year == $items[$j]["startdate"][0] && $items[$j]["enddate"][0] > $year && $monthnums[$month] > $items[$j]["startdate"][1]) || 
                ($year == $items[$j]["enddate"][0] && $items[$j]["startdate"][0] < $year && $monthnums[$month] < $items[$j]["enddate"][1]) || 
                ($year < $items[$j]["enddate"][0] && $year > $items[$j]["startdate"][0]) || 
                (($monthnums[$month] < $items[$j]["enddate"][1] && $monthnums[$month] > $items[$j]["startdate"][1]) && $year == $items[$j]["startdate"][0]) || 
                ($items[$j]["startdate"][1] != $items[$j]["enddate"][1] && ((($monthnums[$month] == $items[$j]["startdate"][1]) && $year == $items[$j]["startdate"][0] && ($i >= $items[$j]["startdate"][2])) ||
                ($monthnums[$month] == $items[$j]["enddate"][1] && $year == $items[$j]["enddate"][0] && ($i <= $items[$j]["enddate"][2])))) ||
                (($i >= $items[$j]["startdate"][2] && $i <= $items[$j]["enddate"][2]) && ($items[$j]["enddate"][0]==$items[$j]["startdate"][0] && $items[$j]["enddate"][0] == $year) && ($items[$j]["enddate"][1]==$items[$j]["startdate"][1] && $items[$j]["enddate"][1] == $monthnums[$month])))
                {
                	if ($items[$j]["detail"]) 
                    {
						$calendar .= " * <span class=\"calendar_items\"><a href=\"index.php?page=calender&item={$items[$j]["id"]}\">{$items[$j]["summary"]}</a></span><br />";
                        $printed = true;
					} 
                    else 
                    {
						$calendar .= " * <span class=\"calendar_items\">{$items[$j]["summary"]}</span><br />";
                        $printed = true;
					}
                    
                    if (isset($items[$j+1]))
                    {
                        $notlast = true;
                    }
                    else
                    {
                        $notlast = false;
                    }
                }
            }
            if($printed && $notlast)
            {
                $calendar .= "<hr>";
            }
		}
		$calendar .= "</td></tr></table></td>";
		if ($days == 7) {$calendar .= "</tr><tr style=\"height: 100%\">"; $days = 0;}
	  }
	  
	$calendar .= "</tr>
	 </table>";
	$tpl->assign("calendar", $calendar);
	$show_detail = false;
}
$tpl->assign("show_detail", $show_detail);
$pagename = "Calender";
$dbpage = true;
?>