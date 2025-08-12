<?php
////////////////////////////////////////////////
// Emailing out overdue item
if ($overdue_action=="email_all") {
	// Selecting all the items that are set as out
	$sqlOverdueEmail = mysql_query("SELECT * FROM `$mysql_pre$mysql_loans` WHERE 1 AND `status` LIKE 'Out' ORDER BY `id` ASC",$db);
	
	echo "<b>Email Progress:</b><br><br>";
	// Displaying the data
	if ($resultOverdueEmail = mysql_fetch_array($sqlOverdueEmail)) {
		do {
			// Checking how many items are overdue
			$email_count = 0;
			
			// Checking input year as it should not be less that this year
			if ($resultOverdueEmail[date_in_year] < $current_year)
			{
				$email_count = true;
			}
			
			// Checking input month as it should not be less that this month
			if ($resultOverdueEmail[date_in_month] < $current_month)
			{
				$email_count = true;
			}
			
			// Checking input day as it should be greater than todays day
			if ($resultOverdueEmail[date_in_day] < $current_day)
			{
				if ($resultOverdueEmail[date_in_month] <= $current_month)
				{
					$email_count = true;
				}
			}
			
			// Send an email to each person with overdue items
			if ($email_count==true) 
			{
				$array_items = false;
				$output_items = false;
				$resultStudentEmailConvert = false;
				$display_items = false;
				
				$overdue_email_count++;
				// Student ID to Email Converter
				$sqlStudentEmailConvert = mysql_query("SELECT `email` FROM $mysql_pre$mysql_students WHERE 1 AND `id` = $resultOverdueEmail[student_id] LIMIT 0, 1");
				$resultStudentEmailConvert = mysql_fetch_array($sqlStudentEmailConvert);
				// Checking if it found anything
				if ($resultStudentEmailConvert[email]==true) {
					$to = $resultStudentEmailConvert[email];
				}
				else
				{			
					echo "$overdue_email_count: ERROR Student ID: $resultOverdueEmail[student_id] has been deleted<br>";			
				}
				// Converting Item IDs into an array
				$array_items = explode("|", $resultOverdueEmail[item_id]);
				
				// Making a list of all the overdue items they have
				while($output_items = each($array_items))
				{		
					$sqlItemConvert = mysql_query("SELECT `id`,`name` FROM $mysql_pre$mysql_items WHERE 1 AND `id` = $output_items[value] LIMIT 0, 1");
					$resultItemConvert = mysql_fetch_array($sqlItemConvert);
					// Checking to see if there is a list, if not make one
					if ($display_items==true) {
						$display_items = "$display_items\n$resultItemConvert[name]";
					}
					else
					{
						$display_items = "$resultItemConvert[name]";
					}				
				}		
										
				require "modules/templates/overdue_email.php";
				$message = "$message\n\n\nThis message was sent from: $library_name by the user: $PHPLibrary[username]";
				$extra = "From: $library_email\r\nReply-To: $library_email\r\n";

				mail ($to, $subject, $message, $extra);
				echo "$overdue_email_count: <b>$to</b><br><br>";
			}
		} while ($resultOverdueEmail = mysql_fetch_array($sqlOverdueEmail));
	echo "<html><body><script language=javascript1.1>alert('$overdue_email_count students emailed'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";			
	}
	else
	{
		//echo "<html><body><script language=javascript1.1>alert('No students with overdue items found, 0 students emailed'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
	}
}

////////////////////////////////////////////////
// Displaying overdue item index
if ($overdue_action==false) {
	// Selecting all the items that are set as out
	$sqlOverdueCount = mysql_query("SELECT * FROM `$mysql_pre$mysql_loans` WHERE 1 AND `status` LIKE 'Out' ORDER BY `id` ASC",$db);
	
	// Displaying the data
	if ($resultOverdueCount = mysql_fetch_array($sqlOverdueCount)) {
		do {
			// Checking how many items are overdue
			$count_me = 0;
			
			// Checking input year as it should not be less that this year
			if ($resultOverdueCount[date_in_year] < $current_year)
			{
				$count_me = true;
			}
			
			// Checking input month as it should not be less that this month
			if ($resultOverdueCount[date_in_month] < $current_month)
			{
				$count_me = true;
			}
			
			// Checking input day as it should be greater than todays day
			if ($resultOverdueCount[date_in_day] < $current_day)
			{
				if ($resultOverdueCount[date_in_month] <= $current_month)
				{
					$count_me = true;
				}
			}
			
			// Count it only once
			if ($count_me==true) 
			{
				$overdue_count++;
			}
		} while ($resultOverdueCount = mysql_fetch_array($sqlOverdueCount));
	}
	
	
	if ($overdue_count > 0) 
	{
		// Showing link for emailing all overdue item people
		echo "<center><a href=$PHP_SELF?module=$module&header_footer=no&overdue_action=email_all><img src=images/email.gif> Email all overdue students reminders</a><br><br>";
	
		// Showing how many it found
		echo "<b>$overdue_count</b> overdue item(s)</center><br><br>";
	
		$sqlOverdue = mysql_query("SELECT * FROM `$mysql_pre$mysql_loans` WHERE 1 AND `status` LIKE 'Out' ORDER BY `id` ASC",$db);
		
		// Displaying the data
		if ($resultOverdue = mysql_fetch_array($sqlOverdue)) {
			// Checking how many items are overdue
			
			printf("<table cellpadding=2 cellspacing=0 border=1 bordercolor=#FFFFCC align=center>");
			printf("<tr><td class=color3><b><center>Loan No</a></center></b></td><td class=color3><b><center>Student</center></b></td><td class=color3><b><center>Items</center></b></td><td class=color3><b><center>Date Out</center></b></td><td class=color3><b><center>Date In</center></b></td><td class=color3><b><center>Status</center></b></td><td class=color3><b><center>Last Edited by</center></b></td><td class=color3><b><center>Days Overdue</center></b></td><td class=color3><b><center>Fine</center></b></td></tr><tr>");
			// Colour starts the table colour on gray
			$colour = "color2";
		
			// For each item that is set as out
			do {
				// Setting defaults
				$this_is_overdue = false;		
				// Checking input year as it should not be less that this year
				if ($resultOverdue[date_in_year] < $current_year)
				{
					$this_is_overdue = true;
				}
				
				// Checking input month as it should not be less that this month
				if ($resultOverdue[date_in_month] < $current_month)
				{
					$this_is_overdue = true;
				}
				
				// Checking input day as it should be greater than todays day
				if ($resultOverdue[date_in_day] < $current_day)
				{
					if ($resultOverdue[date_in_month] <= $current_month)
					{
						$this_is_overdue = true;
					}
				}
				
		
				if ($this_is_overdue==true) {
					// Student ID to Name Converter
					$sqlStudentConvert = mysql_query("SELECT `name` FROM $mysql_pre$mysql_students WHERE 1 AND `id` = $resultOverdue[student_id] LIMIT 0, 1");
					$resultStudentConvert = mysql_fetch_array($sqlStudentConvert);
					// Checking if it found anything
					if ($resultStudentConvert[name]==true) {
						$StudentConvert = $resultStudentConvert[name];
					}
					else
					{			
						$StudentConvert = "ERROR: Student deleted";
					}
							
					// Counting the number of items one loan has
					$array_items = explode("|", $resultOverdue[item_id]);
					
					// Reseting counters and lables
					$count_items = 0;
					$display_items = false;
					while($output_items = each($array_items))
					{		
						// Counting number of items they have
						$count_items++;
						// Checking to display item list or display just a number
						if ($count_items < 4) {
							// Getting an item name
							$sqlItemConvert = mysql_query("SELECT `id`,`name` FROM $mysql_pre$mysql_items WHERE 1 AND `id` = $output_items[value] LIMIT 0, 1");
							$resultItemConvert = mysql_fetch_array($sqlItemConvert);
							// Checking to see if there is a list, if not make one
							if ($display_items==true) {
								$display_items = "$display_items, $resultItemConvert[name]";
							}
							else
							{
								$display_items = "$resultItemConvert[name]";
							}				
						}
						else
						{
							$display_items = "$count_items items (click to view)";
						}
					}		
					
					// Days overdue
					$total_days_overdue_timestamp = mktime(1,0,0,$resultOverdue[date_in_month],$resultOverdue[date_in_day],$resultOverdue[date_in_year]);		
					$current_timestamp = mktime();
					$total_days_overdue = floor(($current_timestamp - $total_days_overdue_timestamp) / 86400);
					
					// Direct out put for testing
					//echo "$total_days_overdue_timestamp<br>";
					//echo "$current_timestamp<br>";
					//echo "$total_days_overdue";
							
					// Calculating the fine		
					$overdue_final_fine = ($total_days_overdue * $overdue_fine);
	
					// english notation without thousands seperator
					$overdue_final_fine = number_format($overdue_final_fine, 2, '.', '');
						
					// Outputing all the loans			
					printf("<td class=$colour><center>$resultOverdue[id]</center></td><td class=$colour><center><a href=$PHP_SELF?module=students&students_action=edit&student_id=$resultOverdue[student_id]>$StudentConvert</a></center></td><td class=$colour><center><A HREF=$PHP_SELF?module=loans&loans_action=show_details&loan_id=$resultOverdue[id]>$display_items</a></center></td><td class=$colour><center>$resultOverdue[date_out_day]/$resultOverdue[date_out_month]/$resultOverdue[date_out_year]</center></td><td class=$colour><center>$resultOverdue[date_in_day]/$resultOverdue[date_in_month]/$resultOverdue[date_in_year]</center></td><td class=$colour><center>$resultOverdue[status]</center></td><td class=$colour><center>$resultOverdue[edited_by]</center></td>");
					printf("<td class=$colour><center>$total_days_overdue</center></td><td class=$colour><center>£$overdue_final_fine</center></td>", $resultOverdue["id"], $resultOverdue["id"], $resultOverdue["id"]);
					printf("</tr>");
			
					// This checks what the table colour is and reverses it to the other colour for example grey, yellow, grey, yellow etc
					if ($colour=="color2") {
						$colour = "ref";
					}
					else
					{
						$colour = "color2";
					}
				}
			} while ($resultOverdue = mysql_fetch_array($sqlOverdue));
			// Ends the table
			printf("</td></tr></table>");
		
		}
		// If there are no items set as out
		else
		{
			echo "<center>No Overdue items found</center>";
		}
	}
	// If there are items set as out, but the counter doesn't find any that are overdue
	else
	{
		echo "<center>No Overdue items found</center>";
	}
}
?>