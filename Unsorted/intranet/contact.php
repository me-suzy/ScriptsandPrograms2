<?php
include("config.php");
include("identity.php");
if($refok == 'yes')
{


$appheaderstring='Contact Log';
include("header.php");
if ($action == '')
	{
        $action='viewlist'; $limit='30'; $contactlist = 'y'; $productsummary = 'y';
	}
if($action != 'pf')
	{
	echo "<ul><li>List Callbacks (also deletes previous day's callbacks)
	<ul><li><a href='", $PHP_SELF, "?action=callbacks&next=1'>For Next 24 Hours</a> <font size='2'></font>";
	echo "|<a href='", $PHP_SELF, "?action=callbacks&next=7'>For Next Week</a> <font size='2'></font>";
	echo "|<a href='", $PHP_SELF, "?action=callbacks&next=30'>For Next 30 Days</a> <font size='2'></font>";
	echo "|<a href='", $PHP_SELF, "?action=callbacks&next=365'>For Next Year</a> <font size='2'></font>";
	echo "</ul>";
	if($action=='callbacks') { echo "<li><a href='", $PHP_SELF, "'>Normal View</a>"; }
	echo "</ul>";
	}
if($action=='callbacks')
	{
        dbconnect($dbusername,$dbuserpasswd);
 	$currenttime = time();
	$timedata = getdate( $currenttime );
	if($timedata[mon] < 10 and substr($timedata[mon],0,1) != '0') { $timedata[mon] = "0" . $timedata[mon]; }
	if($timedata[mday] < 10 and substr($timedata[mday],0,1) != '0') { $timedata[mday] = "0" . $timedata[mday]; }
	$today = $timedata[year] . $timedata[mon] . $timedata[mday];
	if($next =='') { $next=7; }
	$futuretime = time() + $next * 86400; // There are 86400 seconds in a day?
	$timedata = getdate( $futuretime );
	if($timedata[mon] < 10 and substr($timedata[mon],0,1) != '0') { $timedata[mon] = "0" . $timedata[mon]; }
	if($timedata[mday] < 10 and substr($timedata[mday],0,1) != '0') { $timedata[mday] = "0" . $timedata[mday]; }
	$tomorrow = $timedata[year] . $timedata[mon] . $timedata[mday];
//	echo $today, "<br>", $tomorrow;
	$result=mysql_query("select * from webcal_entry where cal_create_by='$setting[cal_login]' and cal_name='Call Back' and cal_date <= '$tomorrow' order by cal_date");
	echo "<blockquote><ul>";
	while($callback=mysql_fetch_array($result))
		{
		if($callback[cal_date] >= $today)
			{
			$date = $callback[cal_date];
			$callbackdate = substr($date,0,4) . "-" . substr($date,4,2) . "-" . substr($date,6,2);
			echo "<li>", $callbackdate, " -- ", $callback[cal_description];
			} else 
				{
				$target=$callback[cal_id];
				mysql_query("delete from webcal_entry where cal_id='$target'");
				mysql_query("delete from webcal_entry_user where cal_id='$target'");
				mysql_query("delete from webcal_entry_repeats where cal_id='$target'");
				}
		}
	echo "</ul>";
	}
if ($action == 'createnew')
	{

	if ($sale != 'y') { $sale ='n'; }
	
	if ($ampm == 'pm') { $hours = $hours + 12; }

	if ($month < 10 and substr($month, 0, 1) != '0') { $month = '0' . $month; }
	if ($day < 10 and substr($day, 0, 1) != '0') { $day = '0' . $day; }	
	$date = $year . "-" . $month . "-" . $day;
	$date_time = $date . " " . $hours . ":" . $minutes . ":00";
	if ($type == 'Other') { $type = $type_other; }
	$activity = addslashes($activity);

	mysql_query("insert into contactlog set date_time='$date_time', contact_id='$contact_id',
			product='$product_id', type='$type', activity='$activity', user='$setting[login]',
			sale='$sale'");
	
	if($callback=='y')
		{
		// 1. Create data to go into calendar database
		if($cbmonth < 10 and substr($cbmonth,0,1) != '0') { $cbmonth= "0" . $cbmonth; }
		if($cbday < 10 and substr($cbday,0,1) != '0') { $cbday= "0" . $cbday; }
		$callbackdate=$cbyear . $cbmonth . $cbday;
		$shortdesc = "Call Back";
		dbconnect($dbusername,$dbuserpasswd);
		$result809=mysql_query("select * from rolodex where id='$contact_id'");
		$list809=mysql_fetch_array($result809);
		$longdesc = "Call back " . $list809[firstname] . " " . $list809[lastname] . " of " . $list809[company] . " (" . $list809[phone] . ", fax: " . $list809[fax] . ") " . $cbdesc;
                // 2. Get MAX cal_id from calendar database.
		dbconnect($dbusername,$dbuserpasswd);
		$result810=mysql_query("select MAX(cal_id) as maximum from webcal_entry");
		$list810=mysql_fetch_row($result810);
		$new_cal_id=$list810[0] + 1;		
		// 3. Write data to calendar database with cal_id = MAX(cal_id)+1
		mysql_query("insert into webcal_entry set cal_id='$new_cal_id', cal_create_by='$setting[cal_login]', cal_date='$callbackdate', cal_time='-1', cal_priority='2', cal_type='E', cal_access='P', cal_name='$shortdesc', cal_description='$longdesc';");
		mysql_query("insert into webcal_entry_user set cal_id='$new_cal_id', cal_login='$setting[cal_login]', cal_status='A'");
		mysql_query("insert into webcal_entry_repeats set cal_id='$new_cal_id', cal_type='none', cal_frequency='1', cal_days='nnnnnnn'");
		}
	$action='viewlist'; $limit = 30;

	}
if ($action == 'delete')
	{
	dbconnect($dbusername,$dbuserpasswd);
	mysql_query("delete from contactlog where id='$target'");
	$action='viewlist'; $limit= 30;
	}

if ($action == 'viewlist')
	{
	echo "<center><table border='0' cellpadding='0' cellspacing='0'>";
	echo "<form method='post' action='contact.php'><input type='hidden' name='action' value='createnew'>";
	echo "<tr><td colspan='3'><font size='", $setting[heading_fontsize],"' face='", $setting[heading_fontface], "'>New Contact: </font></td></tr><tr><td colspan='3'><select name='contact_id'><option value='0'>CONTACT NAME";
	dbconnect($dbusername,$dbuserpasswd);
	$resulth=mysql_query("select * from rolodex order by lastname,firstname");
	while($row8=mysql_fetch_array($resulth))	
		{
                echo "<option value='", $row8[id], "'>", $row8[lastname], ", ", $row8[firstname], " (", $row8[title], ", ", $row8[company], ")";
		}
	echo "</select></td></tr>";

 	$currenttime = time();
	$timedata = getdate( $currenttime );
	$adjhours = $timedata[hours];
	$adjminutes = $timedata[minutes];
	$adjseconds = $timedata[seconds];
	$ampm = "am";
	if ($adjhours >12) { $adjhours = $adjhours -12; $ampm = "pm"; }
	if ($adjminutes < 10){ $adjminutes = "0" . $adjminutes; }
	if ($adjseconds < 10){ $adjseconds = "0" . $adjseconds; }

	echo "<tr><td valign='top'><select name='product_id'><option value='0'>PRODUCT NAME";
	dbconnect($dbusername,$dbuserpasswd);
	$resulth=mysql_query("select * from products order by name");
	while($row8=mysql_fetch_array($resulth))	
		{
                echo "<option value='", $row8[id], "'>", $row8[name];
		}
	echo "</select></td><td align='center'>Date: <input type='text' size='4' name='year' value='", $timedata[year], "'>-<input type='text' size='2' name='month' value='", $timedata[mon], "'>-<input type='text' size='2' name='day' value='", $timedata[mday], "'></td>";
	echo "<td align='right'>Time: <input type='text' size='2' maxlength='2' name='hours' value='", $adjhours, "'>:<input type='text' size='2' maxlength='2' name='minutes' value='", $adjminutes, "'><select name='ampm'><option>am<option";
	if ($ampm=='pm') { echo " selected"; }
	echo ">pm</select></td></tr>";

	echo "<tr><td align='right' colspan='3'>Activity Type: <select name='type'><option>E-mail<option>Telephone - Reached<option>Telephone - Left Msg.<option>Letter<option>Visit<option value='Other'>Other (please describe)</select>
		<input type='text' size='40' name='type_other'></td></tr>";

	echo "<tr><td valign='top'>Activity<br>Description:</td><td align='center' valign='top'><textarea name='activity' cols='37' rows='4' wrap='virtual'></textarea></td><td valign='bottom' align='right'>Sale: <input type='checkbox' name='sale' value='y'></td></tr>";
	echo "<tr><td colspan='3'><hr noshade></td></tr>";
	echo "<tr><td align='center' valign='top'>Call Back? <input name='callback' type='checkbox' value='y'> </td><td colspan='2' align='center'><table border='0' cellpadding='0' cellspacing='0' width='100%'><tr><td valign='top' align='center'>Date: <input type='text' size='4' name='cbyear' value='", $timedata[year], "'>-<input type='text' size='2' name='cbmonth' value='", $timedata[mon], "'>-<input type='text' size='2' name='cbday' value='", $timedata[mday] + 2, "'></td><td valign='top' align='center'><font size='2'>Additional Calendar Description:</font><br><textarea cols='30' rows='2' wrap='soft' name='cbdesc'></textarea></td></tr></table></td></tr>";
	echo "<tr><td colspan='3'><hr noshade></td></tr>";
	echo "<tr><td colspan='3' align='right'><input type='submit' value='Add'></td></tr></form>";	

// END OF NEW CONTACT CREATION FORM

	echo "<form method='post' action='contact.php'><input type='hidden' name='action' value='viewlist'>";
	echo "<tr><td colspan='3'><hr noshade color='000000'><font size='", $setting[heading_fontsize],"' face='", $setting[heading_fontface], "'>Create Report: </font></td></tr>";

	echo "<tr><td colspan='3'>";
	echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>";
	if ($search == '') { $search = 'mostrecent'; }
	echo "<tr><td colspan='2'><input type='radio' name='search' value='mostrecent'";
	if ($search == 'mostrecent') { echo " checked"; }
	echo "> Most recent <input type='text' size='3' name='limit' value='", $limit,"'> contacts.</td></tr>";
	echo "<tr><td colspan='2'>";

	echo "<table border='0' cellpadding='0' cellspacing='0'><tr><td rowspan='2'><input type='radio' name='search' value='bydate'";
	if ($search == 'bydate') { echo " checked"; }
	echo "></td><td rowspan='2'>By date: &nbsp; &nbsp; &nbsp;</td><td rowspan='2'>Start: </td><td><font size='1' face='Arial'>Year</font></td><td><font size='1' face='Arial'>Mo.</font></td><td><font size='1' face='Arial'>Day</font></td><td rowspan='2'>&nbsp; &nbsp; &nbsp; End:</td><td><font size='1' face='Arial'>Year</font></td><td><font size='1' face='Arial'>Mo.</font></td><td><font size='1' face='Arial'>Day</font></td></tr>";
	echo "<tr><td><input type='text' size='4' maxlength='4' name='startyr' value='", $timedata[year], "'></td><td><input type='text' size='2' maxlength='2' name='startmo' value='", $timedata[mon], "'></td><td><input type='text' size='2' maxlength='2' name='startda' value='1'></td><td><input type='text' size='4' maxlength='4' name='endyr' value='", $timedata[year], "'></td><td><input type='text' size='2' maxlength='2' name='endmo' value='", $timedata[mon], "'></td><td><input type='text' size='2' maxlength='2' name='endda' value='31'></td></tr>";
	echo "</table>";

	echo "</td></tr>";
	echo "<tr><td colspan='2'><select name='contact_id'><option value='%'>ANY CONTACT";
	dbconnect($dbusername,$dbuserpasswd);
	$resulth=mysql_query("select * from rolodex order by lastname,firstname");
	while($row8=mysql_fetch_array($resulth))	
		{
                echo "<option value='", $row8[id], "'>", $row8[lastname], ", ", $row8[firstname], " (", $row8[title], ", ", $row8[company], ")";
		}
	echo "</select></td></tr>";
	echo "<tr><td><select name='product_id'><option value='%'>ANY PRODUCT";
	dbconnect($dbusername,$dbuserpasswd);
	$resulth=mysql_query("select * from products order by name");
	while($row8=mysql_fetch_array($resulth))	
		{
                echo "<option value='", $row8[id], "'>", $row8[name];
		}
	echo "</select>  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <input type='checkbox' name='productsummary' value='y' checked> Product Summary</td><td align='right'><input type='checkbox' name='contactlist' value='y' checked> Contact List &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <input type='submit' value='Go'></td></tr></table>";

	echo "</td></tr></table></form>";
	dbconnect($dbusername,$dbuserpasswd);
	if ($contact_id == '') { $contact_id = '%'; }
	if ($product_id == '') { $product_id = '%'; }
	$sqlquery = "select * from contactlog where user='" . $setting[login] . "' and contact_id LIKE '" . $contact_id . "' and product LIKE '" . $product_id . "' ";
        if ($search == 'mostrecent') { $sqlquery = $sqlquery . "order by date_time desc limit 0," . $limit; }
	if ($search == 'bydate')
		{
		$startdate = $startyr . "-" . $startmo . "-" . $startda . " 00:00:00";
		$enddate = $endyr . "-" . $endmo . "-" . $endda . " 23:59:59";
		$sqlquery = $sqlquery . "and date_time >= '$startdate' and date_time <= '$enddate' ";
		$sqlquery = $sqlquery . "order by date_time desc";
		}
// ECHO $sqlquery;
	$resultm=mysql_query($sqlquery);

	echo "<table border='1' width='95%' cellpadding='2' cellspacing='0'>";	
	$thiscount =mysql_num_rows($resultm);
	$countie=mysql_query("select id from contactlog where user='$setting[login]'");
	$totalcount=mysql_num_rows($countie);
	echo "<tr><td colspan='4'><font size='", $setting[heading_fontsize], "' face='", $setting[heading_fontface], "'>You have logged ", $totalcount, " contacts. (", $thiscount, " in this search)</font> </td><td colspan='2' align='right'><a href='contact.php?action=pf&search=", $search, "&limit=", $limit, "&startyr=", $startyr, "&startmo=", $startmo, "&startda=", $startda, "&endyr=", $endyr, "&endmo=", $endmo, "&endda=", $endda, "&contact_id=", $contact_id, "&product_id=", $product_id, "' target='candy'><img src='icons/printer.gif' border='0' alt='Printer-friendly Version'></a></td></tr>";
	echo "<tr><td><b>Date/Time</b></td><td><b>Contact</b></td><td><b>Product</b></td><td><b>Activity</b></td><td><b>Sale</b></td><td> </td></tr>";
	while($row9=mysql_fetch_array($resultm))
		{
                echo "<tr><td>", $row9[date_time], "</td><td>";
		dbconnect($dbusername,$dbuserpasswd);
		$resultj=mysql_query("select * from rolodex where id = '$row9[contact_id]'");
		$contact=mysql_fetch_array($resultj);
		echo "<a href='rolodex.php?searchtype=id&contacttype=Both&searchdata=", $contact[id], "'>", $contact[firstname], " ", $contact[lastname], " <img src='icons/info2.gif' alt='", $contact[title], ", ", $contact[company], "' align='absmiddle' border='0'></a></td><td>";
		dbconnect($dbusername,$dbuserpasswd);
		$resultj=mysql_query("select * from products where id = '$row9[product]'");
		$product=mysql_fetch_array($resultj);
		$row9[activity] = str_replace('"','&quot;',$row9[activity]);		
		echo $product[name], '</td><td>', $row9[type], ' &nbsp;<img src="icons/info2.gif" alt="', stripslashes($row9[activity]), '" align="absmiddle" border="0"></td><td>';
		if ($row9[sale] == 'y') { echo "<img src='icons/green-blink-0.gif' border='0' alt='YES'>"; } else { echo "<img src='icons/green-off.gif' border='0' alt='no'>"; }
		echo "</td><td>";

                        echo " <a href='contact.php?action=delete&target=", $row9[id], "'";
?>
 onClick="if (confirm('<?php echo "You are about to delete ", $row9[id]; ?>.') == true) { return true; } else { return false; }"
<?php
			echo "><img src='icons/delete.gif' border='0' alt='Delete!'></a></td></tr>";
	}
	echo "</table></center>";
	}
if ($action == 'pf')
	{
	dbconnect($dbusername,$dbuserpasswd);
	if ($contact_id == '') { $contact_id = '%'; }
	if ($product_id == '') { $product_id = '%'; }
	$sqlquery = "select * from contactlog where user='" . $setting[login] . "' and contact_id LIKE '" . $contact_id . "' and product LIKE '" . $product_id . "' ";
        if ($search == 'mostrecent') { $queryorder = "order by date_time desc limit 0," . $limit; }
	if ($search == 'bydate')
		{
		$startdate = $startyr . "-" . $startmo . "-" . $startda . " 00:00:00";
		$enddate = $endyr . "-" . $endmo . "-" . $endda . " 23:59:59";
		$querylimit = "and date_time >= '$startdate' and date_time <= '$enddate' ";
		$queryorder = "order by date_time desc";
		}
	$sqlquery = $sqlquery . $querylimit . $queryorder;
// ECHO $sqlquery;
	$resultm=mysql_query($sqlquery);

	echo "<table width='100%' border='1' width='95%' cellpadding='2' cellspacing='0'>";	
	$thiscount =mysql_num_rows($resultm);
	$countie=mysql_query("select id from contactlog where user='$setting[login]'");
	$totalcount=mysql_num_rows($countie);
	echo "<tr><td colspan='5' bgcolor='ffffef'><font size='", $setting[heading_fontsize], "' face='", $setting[heading_fontface], "'>", $setting[firstname], " ", $setting[lastname], ": Contact Report: ";
        if ($search == 'mostrecent')
		{
		echo $limit, " Most Recent ";
		} else
			{
                        echo $startdate, " to ", $enddate;
			}
	echo " (", $thiscount, " contacts)</font> </td></tr>";

	dbconnect($dbusername,$dbuserpasswd);	
	$sqlquery = "select type, COUNT(type) as count from contactlog where user = '" . $setting[login] . "' and contact_id LIKE '" . $contact_id . "' and product LIKE '" . $product_id . "' ";
	$sqlquery = $sqlquery . $querylimit;
	$sqlquery = $sqlquery . " GROUP BY type";
	$result99=mysql_query($sqlquery);	
	echo "<tr><td colspan='5'>";

	echo "<table width='100%' bgcolor='efefef' cellpadding='0' border='0' cellspacing='0'>";
// echo $sqlquery;
	while ($typelist = mysql_fetch_array($result99))
		{
                echo "<td>", $typelist[type], ": ", $typelist[count], "</td>";
		}
	echo "</tr></table></td></tr>";
	echo "<tr><td bgcolor='efefff'><b>Date/Time</b></td><td bgcolor='efefff'><b>Contact</b></td><td bgcolor='efefff'><b>Product</b></td><td bgcolor='efefff'><b>Activity</b></td><td bgcolor='efefff'><b>Sale</b></td></tr>";
	while($row9=mysql_fetch_array($resultm))
		{
                echo "<tr><td><font size='2'>", $row9[date_time], "</font></td><td><font size='2'>";
		dbconnect($dbusername,$dbuserpasswd);
		$resultj=mysql_query("select * from rolodex where id = '$row9[contact_id]'");
		$contact=mysql_fetch_array($resultj);
		echo $contact[firstname], " ", $contact[lastname], " (", $contact[title], ", ", $contact[company], ")</font></td><td><font size='2'>";
		dbconnect($dbusername,$dbuserpasswd);
		$resultj=mysql_query("select * from products where id = '$row9[product]'");
		$product=mysql_fetch_array($resultj);
		$row9[activity] = str_replace('"','&quot;',$row9[activity]);		
		echo $product[name], '</td><td><font size="2">', $row9[type], ' (', stripslashes($row9[activity]), ')</font></td><td><font size="2">';
		if ($row9[sale] == 'y') { echo "Yes"; } else { echo "No"; }
		echo "</font></td></tr>";
	}
	echo "</table>";
	}

}
?>
