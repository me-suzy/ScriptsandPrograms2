<?php
////////////////////////////////////////////////
// When the user submits the make new loan form
if ($loans_action=="make_loan") {
	// Checking to see how many 0's there are at the start of the string
	$zero_length = strspn($new_loan_student_barcode, "0");
		
	// Removing the number of 0's at the start
	$new_loan_student_id = substr($new_loan_student_barcode, $zero_length);
	
	// Checking that items have been added to the cookie
	if ($PHPLibrary[items]==false)
	{
		echo "<html><body><script language=javascript1.1>alert('Please add item(s) to the list'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
		exit;
	}	

	// Checking Student Barcode exists
	$sqlStudentCheck = mysql_query("SELECT `id` FROM $mysql_pre$mysql_students WHERE 1 AND `id` = $new_loan_student_id LIMIT 0, 1");
	$resultStudentsCheck = mysql_fetch_array($sqlStudentCheck);
	if ($resultStudentsCheck==false) {
		echo "<html><body text='#FFFFFF'><script language=javascript1.1>alert('ERROR: Student not found'); window.location='$PHP_SELF?module=$module&loans_action=new';</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
		exit;
	} 
	
	// Checking inputs that should be numbers
	if (is_numeric ($new_loan_student_barcode)==false || is_numeric ($new_loan_day)==false || is_numeric ($new_loan_month)==false || is_numeric ($new_loan_year)==false)
	{
		echo "<html><body><script language=javascript1.1>alert('Please check you have entered a number for the item and student ID and the due in date'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
		exit;
	}
	
	// Checking inputs that should be a date
	if ($new_loan_day < 1 || $new_loan_day > 31 || $new_loan_month < 1 || $new_loan_month > 12 || $new_loan_year < 2000 || $new_loan_year > 3000)
	{
		echo "<html><body><script language=javascript1.1>alert('Please enter a correct due in date'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
		exit;
	}
	
	// Checking input year as it should not be less that this year
	if ($new_loan_year < $current_year)
	{
		echo "<html><body><script language=javascript1.1>alert('Please enter a year that is not less than the current year'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
		exit;
	}
	
	// Checking input month as it should not be less that this month
	if ($new_loan_month < $current_month)
	{
		echo "<html><body><script language=javascript1.1>alert('Please enter a month that is not less than the current month'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
		exit;
	}
	
	// Checking input day as it should be greater than todays day
	if ($new_loan_day < $current_day)
	{
		if ($new_loan_month <= $current_month)
		{
			echo "<html><body><script language=javascript1.1>alert('Please enter a day after today'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
			exit;
		}
	}
	
	// Getting item list
	if ($PHPLibrary[items]==true) {
		$array_items = explode("|", $PHPLibrary[items]);
		$array_items = array_unique ($array_items);
			
		while($output_items = each($array_items))
		{
			// Checking to see how many 0's there are at the start of the string
			$zero_length = strspn($output_items[value], "0");
			// Removing the number of 0's at the start
			$output_items[value] = substr($output_items[value], $zero_length);
			
			// Checking if the item is already out
			$sqlItemCheck = mysql_query("SELECT `id`,`status` FROM $mysql_pre$mysql_items WHERE 1 AND `id`='$output_items[value]' AND `status`='Out' LIMIT 0, 1");
			$resultLoanCheck = mysql_fetch_array($sqlItemCheck);
			if ($resultLoanCheck==true) {
				echo "<html><body><script language=javascript1.1>alert('Item ID: $output_items[value] has already been lent out'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
				exit;
			}	
		}
		
		// Running loop twice in order not to set items status wrong
		$array_items = explode("|", $PHPLibrary[items]);
		$array_items = array_unique ($array_items);
		
		while($output_items = each($array_items))
		{
			// Checking if there is a list if there is then it will add to it if not it will make a new list
			if ($item_list==true) {
				$item_list = "$item_list|$output_items[value]";
			}
			else
			{
				$item_list = "$output_items[value]";
			}
			
			// Making sure they have a list of items
			if ($item_list==false)
			{
				echo "<html><body><script language=javascript1.1>alert('Please add item(s) to your list'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
				exit;
			}
			
			// Setting item status to out and adding the last loan ID
			$sqlItemOut = "UPDATE $mysql_pre$mysql_items SET status='Out',last_student_id='$new_loan_student_id' WHERE `id` = $output_items[value]";
			$resultItemOut = mysql_query($sqlItemOut) or die(mysql_error()); ;
		}
	}
	
	// Converting any HTML to standard text in the user data
	$new_loan_notes = htmlspecialchars($new_loan_notes);
	$new_loan_edited_by = htmlspecialchars($new_loan_edited_by );
	
	// Triming blank spaces at the start and end of the user data
	$new_loan_notes = trim($new_loan_notes);
	$new_loan_edited_by = trim($new_loan_edited_by );
	
	// Every thing is ok save details in the MySQL database
	$sqlMakeLoan = "INSERT $mysql_pre$mysql_loans SET item_id='$item_list',student_id='$new_loan_student_id',date_out_day='$current_day',date_out_month='$current_month',date_out_year='$current_year',date_in_day='$new_loan_day',date_in_month='$new_loan_month',date_in_year='$new_loan_year',status='Out',notes='$new_loan_notes',edited_by='$new_loan_edited_by'";
	$resultMakeLoan = mysql_query($sqlMakeLoan) or die(mysql_error()); ;
	// Delete the items cookie
	setcookie ("PHPLibrary[items]");
	
	// Show ok message and refresh
	echo "<html><body><script language=javascript1.1>alert('Loan made'); window.location='$PHP_SELF?module=$module';</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
	exit;
}

////////////////////////////////////////////////
// When the user submits the edit loan form
if ($loans_action=="edit_loan") {
	// Converting any HTML to standard text in the user data
	$edit_loan_notes = htmlspecialchars($edit_loan_notes);
	$edit_loan_edited_by = htmlspecialchars($edit_loan_edited_by );
	
	// Triming blank spaces at the start and end of the user data
	$edit_loan_notes = trim($edit_loan_notes);
	$edit_loan_edited_by = trim($edit_loan_edited_by );
	
	// Checking inputs that should be numbers
	if (is_numeric ($edit_loan_day)==false || is_numeric ($edit_loan_month)==false || is_numeric ($edit_loan_year)==false)
	{
		echo "<html><body><script language=javascript1.1>alert('Please check you have entered a number for the due in date'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
		exit;
	}
	
	// Checking inputs that should be a date
	if ($edit_loan_day < 1 || $edit_loan_day > 31 || $edit_loan_month < 1 || $edit_loan_month > 12 || $edit_loan_year < 2000 || $edit_loan_year > 3000)
	{
		echo "<html><body><script language=javascript1.1>alert('Please enter a correct due in date'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
		exit;
	}

	// Do not need to check if the due in date is greater than todays date (as they are editing it)
	
	// Every thing is ok save details in the MySQL database
	$sqlEditLoan = "UPDATE $mysql_pre$mysql_loans SET date_in_day='$edit_loan_day',date_in_month='$edit_loan_month',date_in_year='$edit_loan_year',notes='$edit_loan_notes',edited_by='$edit_loan_edited_by' WHERE id=$edit_loan_id";
	$resultEditLoan = mysql_query($sqlEditLoan) or die(mysql_error()); ;
	// Show ok message and refresh
	echo "<html><body><script language=javascript1.1>alert('Loan edited'); window.location='$PHP_SELF?module=$module';</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
	exit;
}

///////////////////////////////////////////////////////////////////////////////////////////////
// Store Barcode or Item ID as cookie
if ($loans_action=="cookie_loan") {
	// Checking to see if they want to delete the list
	if ($clear==true) {
		setcookie ("PHPLibrary[items]");
		echo "<html><body><script language=javascript1.1>alert('Item list cleared'); window.location='$PHP_SELF?module=$module&loans_action=new';</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
		exit;
	}
	else
	{	
		// else they are adding items into the cookie
		// Checking to see how many 0's there are at the start of the string
		$zero_length = strspn($new_loan_item_barcode, "0");
		
		// Removing the number of 0's at the start
		$new_loan_item_id = substr($new_loan_item_barcode, $zero_length);
		
		// Checking Item Barcode exists
		$sqlItemCheck = mysql_query("SELECT `id` FROM $mysql_pre$mysql_items WHERE 1 AND `id` = $new_loan_item_id LIMIT 0, 1");
		$resultItemCheck = mysql_fetch_array($sqlItemCheck);
		if ($resultItemCheck==false) {
			echo "<html><body text='#FFFFFF'><script language=javascript1.1>alert('ERROR: Item not found'); window.location='$PHP_SELF?module=$module&loans_action=new';</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
			exit;
		} 

		
		// Item checks out ok
		// If they have more than 1 item
		if ($PHPLibrary[items]==true) {
			$new_cookie = "$PHPLibrary[items]|$new_loan_item_barcode";
			setcookie ("PHPLibrary[items]", $new_cookie);
		}
		else
		{
			setcookie ("PHPLibrary[items]", $new_loan_item_barcode);
		}
	}
	// Refresh the page
	echo "<html><body><script language=javascript1.1>alert('Item added'); window.location='$PHP_SELF?module=$module&loans_action=new';</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";		
	exit;
}

///////////////////////////////////////////////////////////
//  When the user submits to change the status of a loan
if ($loans_action=="status_change") {
	// Checking what its status is and reversing it
	$sqlLoanStatus = mysql_query("SELECT `id`,`item_id`,`status` FROM $mysql_pre$mysql_loans WHERE 1 AND `id`='$loan_id' LIMIT 0, 1");
	$resultLoanStatus = mysql_fetch_array($sqlLoanStatus);
	// Changing the status
	if ($resultLoanStatus[status]=='Out') {
		$in_or_out = "In";
	}
	else
	{			
		$in_or_out = "Out";
	}
	
	// Checking the status for the Items
	$array_items = explode("|", $resultLoanStatus[item_id]);
	$array_items = array_unique ($array_items);
		
	while($output_items = each($array_items))
	{	
		// Checking if the item is already out
		$sqlItemCheck = mysql_query("SELECT `id`,`status` FROM $mysql_pre$mysql_items WHERE 1 AND `id`='$output_items[value]' LIMIT 0, 1");
		$resultLoanCheck = mysql_fetch_array($sqlItemCheck);
		if ($resultLoanCheck[status]!=$resultLoanStatus[status]) {
			echo "<html><body><script language=javascript1.1>alert('Item ID: $output_items[value] has already been lent out or has been lost'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
			exit;
		}
	}
	
	// Saving the status for the Items (running the loop twice because it may set 1 or more items status wrong)
	$array_items = explode("|", $resultLoanStatus[item_id]);
	$array_items = array_unique ($array_items);
		
	while($output_items = each($array_items))
	{
		// Checking to see how many 0's there are at the start of the string
		$zero_length = strspn($output_items[value], "0");
		// Removing the number of 0's at the start
		$output_items[value] = substr($output_items[value], $zero_length);
			
		// Setting item status
		$sqlItemStatusChange = "UPDATE $mysql_pre$mysql_items SET status='$in_or_out' WHERE id=$output_items[value]";
		$resultItemStatusChange = mysql_query($sqlItemStatusChange) or die(mysql_error()); ;
	}	
	
	// Saving the status for the Loans
	$sqlLoanStatusChange = "UPDATE $mysql_pre$mysql_loans SET status='$in_or_out',edited_by='$PHPLibrary[username] on $date' WHERE id=$loan_id";
	$resultLoanStatusChange = mysql_query($sqlLoanStatusChange) or die(mysql_error()); ;
	echo "<html><body><script language=javascript1.1>alert('Loan status changed'); window.location='$PHP_SELF?module=$module&show_status=$show_status';</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";		
	exit;
}

///////////////////////////////////////////////////////////
// Showing a form for the user to make a new loan
if ($loans_action=="new")
{
	?>
	<center>New Loan<br><br>
	<a href="<?php echo "$PHP_SELF?module=$module"; ?>"><img src="images/back.gif" boarder=0> Back</a><br></center>
	<form name="form1" method="post" action="<?php echo "$PHP_SELF?module=$module"; ?>&header_footer=no&loans_action=cookie_loan">
  
	  <table width="100%" border="0">
	  <tr> 
		<td><table cellpadding=2 cellspacing=0 border=1 bordercolor=#FFFFCC align=center>
				<?php
	if ($PHPLibrary[items]==true) {
		$array_items = explode("|", $PHPLibrary[items]);
		$array_items = array_unique ($array_items);
		

		$no_of_items_selected = 0;
		while($output_items = each($array_items))
		{
			echo "<tr>";
			// Giving them numbers
			$no_of_items_selected++;
			echo "<td class=color3>Item $no_of_items_selected:</td>";
			// Get item name
			$sqlItemCheck = mysql_query("SELECT `id`,`name` FROM $mysql_pre$mysql_items WHERE 1 AND `id` = $output_items[value] LIMIT 0, 1");
			$resultItemCheck = mysql_fetch_array($sqlItemCheck);
			
			// Checking to see how many 0's there are at the start of the string
			$zero_length = strspn($resultItemCheck[name], "0");
			// Removing the number of 0's at the start
			$resultItemCheck[name] = substr($resultItemCheck[name], $zero_length);
			
			echo "<td class=color2><a href=$PHP_SELF?module=items&items_action=edit&item_id=$output_items[value]>$resultItemCheck[name]</a> (ID: $resultItemCheck[id])</td>";
			echo "</tr>";
		}
	}
	
	?>
			<tr> 
			  <td class=color3>Item Barcode:</td>
			  <td class=color2><input class="Input" name="new_loan_item_barcode" type="text" maxlength="30"></td>
			</tr>
			<tr> 
			  <td class=color3>&nbsp;</td>
			  <td class=color3><input class="Input" type="submit" name="cookie_loan" value="Add"> 
				<?php if ($PHPLibrary[items]==true){ echo " <input class='Input' type='submit' name='clear' value='Clear List'>"; } ?>
				</td>
			</tr>
		  </table>
		  </form>
		</td>
		<td>
		<form name="form2" method="post" action="<?php echo "$PHP_SELF?module=$module"; ?>&header_footer=no&loans_action=make_loan">
		  <table cellpadding=2 cellspacing=0 border=1 bordercolor=#FFFFCC align=center>
			<tr> 
			  <td class=color3>Student Barcode:</td>
			  <td class=color2><input name="new_loan_student_barcode" type="text" class="Input" id="new_loan_student_barcode" maxlength="6" 
			  value="<?php 
			  if ($input_student_id==true) {
			  // Making barcode from the student ID number 
			  $input_student_barcode = str_pad($input_student_id, 6, "0", STR_PAD_LEFT);
			  echo "$input_student_barcode";}?>">
			  </td>
			</tr>
			<tr> 
			  <td class=color3>Due in Date:</td>
			  <td class=color2><input name="new_loan_day" type="text" class="Input" id="new_loan_day" value="DD" size="2" maxlength="2" onFocus="m(this)">
				/ <input name="new_loan_month" type="text" class="Input" id="new_loan_month" value="MM" size="2" maxlength="2" onFocus="m(this)">
				/ <input name="new_loan_year" type="text" class="Input" id="new_loan_year" value="YYYY" size="4" maxlength="4" onFocus="m(this)"></td>
			</tr>
			<tr> 
			  <td class=color3>Notes:</td>
			  <td class=color2><textarea name="new_loan_notes" rows="7" class="Input" id="new_loan_notes"></textarea></td>
			</tr>
			<tr> 
			  <td class=color3>&nbsp;</td>
			  <td class=color3><input name="new_loan_edited_by" type="hidden" value="<?php echo "$PHPLibrary[username] on $date"; ?>"><input class="Input" type="submit" name="make_loan" value="Ok"></td>
			</tr>
		  </table></td>
	  </tr>
	</table>
   	</form>
	<?
}

///////////////////////////////////////////////////////////////////////////////////////////////
// If they want to show details on a loan
if ($loans_action=="show_details") {

	// Fetching item details
	$sqlShowEditLoan = mysql_query("SELECT * FROM $mysql_pre$mysql_loans WHERE id=$loan_id",$db);
	$resultShowEditLoan = mysql_fetch_array($sqlShowEditLoan);
	?>

	<center>Edit Loan<br><br>
	<a href="<?php echo "$PHP_SELF?module=$module"; ?>"><img src="images/back.gif" boarder=0> Back</a><br></center>
	<form name="form1" method="post" action="<?php echo "$PHP_SELF?module=$module"; ?>&header_footer=no&loans_action=cookie_loan">
  
	  <table width="100%" border="0">
	  <tr> 
		<td><table cellpadding=2 cellspacing=0 border=1 bordercolor=#FFFFCC align=center>
				<?php
	if ($resultShowEditLoan[item_id]==true) {
		$array_items = explode("|", $resultShowEditLoan[item_id]);
		$array_items = array_unique ($array_items);
		

		$no_of_items_selected = 0;
		while($output_items = each($array_items))
		{
			echo "<tr>";
			// Giving them numbers
			$no_of_items_selected++;
			echo "<td class=color3>Item $no_of_items_selected:</td>";
			// Get item name
			$sqlItemCheck = mysql_query("SELECT `id`,`name` FROM $mysql_pre$mysql_items WHERE 1 AND `id` = $output_items[value] LIMIT 0, 1");
			$resultItemCheck = mysql_fetch_array($sqlItemCheck);
			
			// Checking to see how many 0's there are at the start of the string
			$zero_length = strspn($resultItemCheck[id], "0");
			// Removing the number of 0's at the start
			$resultItemCheck[id] = substr($resultItemCheck[id], $zero_length);
			
			// Student ID to Name Converter
			$sqlStudentConvert = mysql_query("SELECT `name` FROM $mysql_pre$mysql_students WHERE 1 AND `id` = $resultShowEditLoan[student_id] LIMIT 0, 1");
			$resultStudentConvert = mysql_fetch_array($sqlStudentConvert);
			// Checking if it found anything
			if ($resultStudentConvert[name]==true) {
			$StudentConvert = $resultStudentConvert[name];
			}
			else
			{			
			$StudentConvert = "ERROR: Student deleted";
			}
			
			echo "<td class=color2><a href=$PHP_SELF?module=items&items_action=edit&item_id=$output_items[value]>$resultItemCheck[name]</a> (ID: $resultItemCheck[id])</td>";
			echo "</tr>";
		}
	}
	
	?>
		  </table>
		  </form>
		</td>
		<td>
		<form name="form2" method="post" action="<?php echo "$PHP_SELF?module=$module"; ?>&header_footer=no&loans_action=edit_loan">
		  <table cellpadding=2 cellspacing=0 border=1 bordercolor=#FFFFCC align=center>
			<tr> 
			  <td class=color3>Student:</td>
			  <td class=color2><a href="<?php echo "$PHP_SELF?module=students&students_action=edit&student_id=$resultShowEditLoan[student_id]"; ?>"><?php echo $StudentConvert; ?></a> (ID: <?php echo $resultShowEditLoan[student_id]; ?>)</td>
			</tr>
			<tr> 
			  <td class=color3>Out Date:</td>
			  <td class=color2><?php echo "$resultShowEditLoan[date_out_day]/$resultShowEditLoan[date_out_month]/$resultShowEditLoan[date_out_year]"; ?></td>
			</tr>			<tr> 
			  <td class=color3>Due in Date:</td>
			  <td class=color2><input name="edit_loan_day" type="text" class="Input" id="edit_loan_day" value="<?php echo $resultShowEditLoan[date_in_day]; ?>" size="2" maxlength="2">
				/ <input name="edit_loan_month" type="text" class="Input" id="edit_loan_month" value="<?php echo $resultShowEditLoan[date_in_month]; ?>" size="2" maxlength="2">
				/ <input name="edit_loan_year" type="text" class="Input" id="edit_loan_year" value="<?php echo $resultShowEditLoan[date_in_year]; ?>" size="4" maxlength="4"></td>
			</tr>
			<tr> 
			  <td class=color3>Status:</td>
			  <td class=color2><?php echo $resultShowEditLoan[status]; ?> <A HREF="<?php echo "$PHP_SELF?module=$module&loans_action=status_change&loan_id=$resultShowEditLoan[id]"; ?>"><img src=images/changestatus.gif border=0 alt='In/Out Change'></a></td>
			</tr>
			<tr> 
			  <td class=color3>Notes:</td>
			  <td class=color2><textarea name="edit_loan_notes" rows="7" class="Input" id="edit_loan_notes"><?php echo $resultShowEditLoan[notes]; ?></textarea></td>
			</tr>
			<tr> 
			  <td class=color3>&nbsp;</td>
			  <td class=color3><input name="edit_loan_id" type="hidden" value="<?php echo $resultShowEditLoan[id]; ?>"><input name="edit_loan_edited_by" type="hidden" value="<?php echo "$PHPLibrary[username] on $date"; ?>"><input class="Input" type="submit" name="make_loan" value="Ok"></td>
			</tr>
		  </table></td>
	  </tr>
	</table>
   	</form>

<?php

}

///////////////////////////////////////////////////////////////////////////////////////////////
// If they are not doing anything then they will be taken to the display all the loans page
if ($loans_action==false) {
	?>
	
	<center><a href=<?php echo "$PHP_SELF?module=$module"; ?>&loans_action=new><img src='images/new.gif' alt='Create New Loan'> Create New Loan</a><br><br></center>
	
	<?php
	// Setting sorting and limiting options if there is no value set
	if ($show_status==false) {
		$show_status = "Out";
	}

	if ($from==false) {
		$from = "0";
	}
	
	if ($limit==false) {
		$limit = "10";
	}
	
	// Checking to see if the user is searching or just displaying
	// Gets the data from the MySQL database from the prefixes in the config file
	if ($search_student==true) {
 
		$sqlLoans = mysql_query("SELECT * FROM `$mysql_pre$mysql_loans` WHERE 1 AND `student_id` = $student_id ORDER BY `id` DESC",$db);
			// Student ID to Name Converter
			$sqlStudentConvert = mysql_query("SELECT `name` FROM $mysql_pre$mysql_students WHERE 1 AND `id` = $student_id LIMIT 0, 1");
			$resultStudentConvert = mysql_fetch_array($sqlStudentConvert);
			// Checking if it found anything
			if ($resultStudentConvert[name]==true) {
			$StudentConvert = "<a href=$PHP_SELF?module=students&students_action=edit&student_id=$student_id>$resultStudentConvert[name]</a>";
			}
			else
			{			
			$StudentConvert = "ERROR: Student deleted";
			}
		echo "<center>Search Results for $StudentConvert (ID: $student_id):</center><br>";
	}
	else
	{
		if ($show_status=="Both") {
			$sqlLoans = mysql_query("SELECT * FROM `$mysql_pre$mysql_loans` ORDER BY `id` DESC LIMIT $from, $limit",$db);
		}
		else
		{
			$sqlLoans = mysql_query("SELECT * FROM `$mysql_pre$mysql_loans` WHERE 1 AND `status` LIKE '$show_status' ORDER BY `id` DESC LIMIT $from, $limit",$db);
		}
	}

	// Displaying the data
	if ($resultLoans = mysql_fetch_array($sqlLoans)) {
		printf("<table cellpadding=2 cellspacing=0 border=1 bordercolor=#FFFFCC align=center>");
		printf("<tr><td class=color3><b><center>Loan No</a></center></b></td><td class=color3><b><center>Student</center></b></td><td class=color3><b><center>Items</center></b></td><td class=color3><b><center>Date Out</center></b></td><td class=color3><b><center>Date In</center></b></td><td class=color3><b><center>Status</center></b></td><td class=color3><b><center>Last Edited by</center></b></td><td class=color3><b><center>Details</center></b></td><td class=color3><b><center>In/Out Change</center></b></td></tr><tr>");
		// Starts the table colour on gray
		$colour = "color2";
		do {	
			// Student ID to Name Converter
			$sqlStudentConvert = mysql_query("SELECT `name` FROM $mysql_pre$mysql_students WHERE 1 AND `id` = $resultLoans[student_id] LIMIT 0, 1");
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
			$array_items = explode("|", $resultLoans[item_id]);
			
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
							
			// Outputing all the loans			
			printf("<td class=$colour><center>$resultLoans[id]</center></td><td class=$colour><center><a href=$PHP_SELF?module=students&students_action=edit&student_id=$resultLoans[student_id]>$StudentConvert</a></center></td><td class=$colour><center><A HREF=$PHP_SELF?module=$module&loans_action=show_details&loan_id=$resultLoans[id]>$display_items</a></center></td><td class=$colour><center>$resultLoans[date_out_day]/$resultLoans[date_out_month]/$resultLoans[date_out_year]</center></td><td class=$colour><center>$resultLoans[date_in_day]/$resultLoans[date_in_month]/$resultLoans[date_in_year]</center></td><td class=$colour><center>$resultLoans[status]</center></td><td class=$colour><center>$resultLoans[edited_by]</center></td>");
			printf("<td class=$colour><center><A HREF=$PHP_SELF?module=$module&loans_action=show_details&loan_id=%s><img src=images/edit.gif border=0 alt='Show Details'></a></center></td><td class=$colour><center><A HREF=$PHP_SELF?module=$module&header_footer=no&show_status=$show_status&loans_action=status_change&loan_id=%s><img src=images/changestatus.gif border=0 alt='In/Out Change'></a></center></td>", $resultLoans["id"], $resultLoans["id"], $resultLoans["id"]);
			printf("</tr>");
			
			// This checks what the table colour is and reverses it to the other colour for example grey, yellow, grey, yellow etc
			if ($colour=="color2") {
				$colour = "ref";
			}
			else
			{
				$colour = "color2";
			}
		
		} while ($resultLoans = mysql_fetch_array($sqlLoans));
		// Ends the table
		printf("</td></tr></table>");
		
		// Works out the next and previous page that should be next viewed
		$next_page = ($from + $limit);
		$previous_page = ($from - $limit);
		
		if ($search_student==false) {
			// Makes sure that it doesn't display a link for -10 or lower
			if ($previous_page >= 0) {
				$previous_page_link = "<a href=$PHP_SELF?module=$module&show_status=$show_status&limit=$limit&from=$previous_page>&lt;Previous</a>";
			}
			echo "<table width=50% border=0 align=center><tr><td>$previous_page_link</td><td><div align=right><a href=$PHP_SELF?module=$module&show_status=$show_status&limit=$limit&from=$next_page>Next&gt;</a></div></td></tr></table>";
		}
			
	}
	else
	{
		// If there are no more loans found in the database or there has been some type of error
		echo "<center>No more loans found</center>";
	}
	if ($search_student==false) {
	// This is a case statment it checks which loan it is sorting by and selects the option from drop down boxes
	switch ($show_status) {
		case "Out":
			$out_selected = "selected";
			break;
		case "In":
			$in_selected = "selected";
			break;
		case "Both":
			$both_selected = "selected";
			break;
	}
			
	// Case statment to check how many loans per page
	switch ($limit) {
		case 10:
			$ten_selected  = "selected";
			break;
		case 20:
			$twenty_selected  = "selected";
			break;
		case 30:
			$thirty_selected  = "selected";
			break;
		case 40:
			$forty_selected  = "selected";
			break;
		case 50:
			$fifty_selected  = "selected";
			break;
	}	
	// Drop down options		
	echo "	<center><br><form action='$PHP_SELF?module=$module' method='POST'>
			Showing <select name='show_status' class='Input'>		
			<option value='Out' $out_selected>Out Items</option>
			<option value='In' $in_selected>In Items</option>
			<option value='Both' $both_selected>Both</option>
			</select> only with <select name='limit' class='Input'>
			<option value='10' $ten_selected>10</option>
			<option value='20' $twenty_selected>20</option>
			<option value='30' $thirty_selected>30</option>
			<option value='40' $forty_selected>40</option>
			<option value='50' $fifty_selected>50</option>
			</select> loans per page&nbsp;
			<input type='submit' value='Ok' class='Input'></form></center><br>";
	}
	else
	{
		echo "<br><br>";
	}
}
?>