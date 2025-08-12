<?php
////////////////////////////////////////////////
// When the user submits the make new student form
if ($students_action=="make_student") {
	// Converting any HTML to standard text in the user data
	$new_student_name = htmlspecialchars($new_student_name);
		$new_student_dob_day = htmlspecialchars($new_student_dob_day);
		$new_student_dob_month = htmlspecialchars($new_student_dob_month);		
		$new_student_dob_year = htmlspecialchars($new_student_dob_year);		
	$new_student_address = htmlspecialchars($new_student_address);
		$new_student_post_code1 = htmlspecialchars($new_student_post_code1);
		$new_student_post_code2 = htmlspecialchars($new_student_post_code2);
	$new_student_telephone = htmlspecialchars($new_student_telephone);
	$new_student_email = htmlspecialchars($new_student_email);
	$new_student_nus = htmlspecialchars($new_student_nus);
	$new_student_cname = htmlspecialchars($new_student_cname);
	$new_student_ctutor = htmlspecialchars($new_student_ctutor);
	$new_student_last_year = htmlspecialchars($new_student_last_year);
	$new_student_notes = htmlspecialchars($new_student_notes);
	$new_student_edited_by = htmlspecialchars($new_student_edited_by);
	
	// Triming blank spaces at the start and end of the user data
	$new_student_name = trim($new_student_name);
		$new_student_dob_day = trim($new_student_dob_day);
		$new_student_dob_month = trim($new_student_dob_month);		
		$new_student_dob_year = trim($new_student_dob_year);
	$new_student_address = trim($new_student_address);
		$new_student_post_code1 = trim($new_student_post_code1);
		$new_student_post_code2 = trim($new_student_post_code2);
	$new_student_telephone = trim($new_student_telephone);
	$new_student_email = trim($new_student_email);
	$new_student_nus = trim($new_student_nus);
	$new_student_cname = trim($new_student_cname);
	$new_student_ctutor = trim($new_student_ctutor);
	$new_student_last_year = trim($new_student_last_year);
	$new_student_notes = trim($new_student_notes);
	$new_student_edited_by = trim($new_student_edited_by);
	
	// Merging Post Codes and Converting uppercase
	$new_student_post_code = "$new_student_post_code1"."$new_student_post_code2";
	$new_student_post_code = strtoupper($new_student_post_code);
	
	// Checking inputs that should be numbers
	if (is_numeric ($new_student_telephone)==false || is_numeric ($new_student_dob_day)==false || is_numeric ($new_student_dob_month)==false || is_numeric ($new_student_dob_year)==false || is_numeric ($new_student_nus)==false || is_numeric ($new_student_last_year)==false)
	{
		echo "<html><body><script language=javascript1.1>alert('Please check you have entered a number for DOB, Telephone No, NUS No and Last Year (making sure there are no spaces in Telephone No or the NUS No)'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
		exit;
	}
	
	// Checking inputs that should be a date
	if ($new_student_dob_day < 1 || $new_student_dob_day > 31 || $new_student_dob_month < 1 || $new_student_dob_month > 12 || $new_student_dob_year < 1900 || $new_student_dob_year > 3000 || $new_student_last_year < 2000 || $new_student_last_year > 3000)
	{
		echo "<html><body><script language=javascript1.1>alert('Please enter a correct date of birth or year of leaving'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
		exit;
	}	

	// Making sure the form is not blank
	if ($new_student_name==false || $new_student_address==false || $new_student_email==false)
	{
		echo "<html><body><script language=javascript1.1>alert('Please fill out the required fields'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
		exit;
	}
	
	// Every thing is ok save details in the MySQL database
	$sqlMakeStudent = "INSERT $mysql_pre$mysql_students SET name='$new_student_name',dob_day='$new_student_dob_day',dob_month='$new_student_dob_month',dob_year='$new_student_dob_year',address='$new_student_address',post_code='$new_student_post_code',telephone='$new_student_telephone',email='$new_student_email',nus='$new_student_nus',cname='$new_student_cname',ctutor='$new_student_ctutor',last_year='$new_student_last_year',notes='$new_student_notes',edited_by='$new_student_edited_by'";
	$resultMakeStudent = mysql_query($sqlMakeStudent) or die(mysql_error()); ;
	// Show ok message and refresh
	echo "<html><body><script language=javascript1.1>alert('Student made'); window.location='$PHP_SELF?module=$module&students_action=new';</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
	exit;
}

////////////////////////////////////////////////
// When the user submits the edit student form
if ($students_action=="edit_student") {
	// Converting any HTML to standard text in the user data
	$edit_student_name = htmlspecialchars($edit_student_name);
		$edit_student_dob_day = htmlspecialchars($edit_student_dob_day);
		$edit_student_dob_month = htmlspecialchars($edit_student_dob_month);		
		$edit_student_dob_year = htmlspecialchars($edit_student_dob_year);		
	$edit_student_address = htmlspecialchars($edit_student_address);
		$edit_student_post_code1 = htmlspecialchars($edit_student_post_code1);
		$edit_student_post_code2 = htmlspecialchars($edit_student_post_code2);
	$edit_student_telephone = htmlspecialchars($edit_student_telephone);
	$edit_student_email = htmlspecialchars($edit_student_email);
	$edit_student_nus = htmlspecialchars($edit_student_nus);
	$edit_student_cname = htmlspecialchars($edit_student_cname);
	$edit_student_ctutor = htmlspecialchars($edit_student_ctutor);
	$edit_student_last_year = htmlspecialchars($edit_student_last_year);
	$edit_student_notes = htmlspecialchars($edit_student_notes);
	$edit_student_edited_by = htmlspecialchars($edit_student_edited_by);
	
	// Triming blank spaces at the start and end of the user data
	$edit_student_name = trim($edit_student_name);
		$edit_student_dob_day = trim($edit_student_dob_day);
		$edit_student_dob_month = trim($edit_student_dob_month);		
		$edit_student_dob_year = trim($edit_student_dob_year);
	$edit_student_address = trim($edit_student_address);
		$edit_student_post_code1 = trim($edit_student_post_code1);
		$edit_student_post_code2 = trim($edit_student_post_code2);
	$edit_student_telephone = trim($edit_student_telephone);
	$edit_student_email = trim($edit_student_email);
	$edit_student_nus = trim($edit_student_nus);
	$edit_student_cname = trim($edit_student_cname);
	$edit_student_ctutor = trim($edit_student_ctutor);
	$edit_student_last_year = trim($edit_student_last_year);
	$edit_student_notes = trim($edit_student_notes);
	$edit_student_edited_by = trim($edit_student_edited_by);
	
	// Merging Post Codes and Converting uppercase
	$edit_student_post_code = "$edit_student_post_code1"."$edit_student_post_code2";
	$edit_student_post_code = strtoupper($edit_student_post_code);
	
	// Checking inputs that should be numbers
	if (is_numeric ($edit_student_id)==false || is_numeric ($edit_student_telephone)==false || is_numeric ($edit_student_dob_day)==false || is_numeric ($edit_student_dob_month)==false || is_numeric ($edit_student_dob_year)==false || is_numeric ($edit_student_nus)==false || is_numeric ($edit_student_last_year)==false)
	{
		echo "<html><body><script language=javascript1.1>alert('Please check you have entered a number for DOB, Telephone No, NUS No and Last Year (making sure there are no spaces in Telephone No or the NUS No)'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
		exit;
	}
	
	// Checking inputs that should be a date
	if ($edit_student_dob_day < 1 || $edit_student_dob_day > 31 || $edit_student_dob_month < 1 || $edit_student_dob_month > 12 || $edit_student_dob_year < 1900 || $edit_student_dob_year > 3000 || $edit_student_last_year < 2000 || $edit_student_last_year > 3000)
	{
		echo "<html><body><script language=javascript1.1>alert('Please enter a correct date of birth or year of leaving'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
		exit;
	}
	
	// Making sure the form is not blank
	if ($edit_student_name==false || $edit_student_address==false || $edit_student_email==false)
	{
		echo "<html><body><script language=javascript1.1>alert('Please fill out the required fields'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
		exit;
	}
	
	// Every thing is ok save details in the MySQL database
	$sqlEditStudent = "UPDATE $mysql_pre$mysql_students SET name='$edit_student_name',dob_day='$edit_student_dob_day',dob_month='$edit_student_dob_month',dob_year='$edit_student_dob_year',address='$edit_student_address',post_code='$edit_student_post_code',telephone='$edit_student_telephone',email='$edit_student_email',nus='$edit_student_nus',cname='$edit_student_cname',ctutor='$edit_student_ctutor',last_year='$edit_student_last_year',notes='$edit_student_notes',edited_by='$edit_student_edited_by' WHERE id=$edit_student_id";
	$resultEditStudent = mysql_query($sqlEditStudent) or die(mysql_error()); ;
	// Show ok message and refresh
	echo "<html><body><script language=javascript1.1>alert('Student edited'); window.location='$PHP_SELF?module=$module';</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
	exit;
}

///////////////////////////////////////////////////////////
//  When the user submits to delete a student
if ($students_action=="delete") {
	$sqlDeleteUser = "DELETE FROM $mysql_pre$mysql_students WHERE id=$student_id";
	$resultDeleteUser = mysql_query($sqlDeleteUser) or die(mysql_error()); ;
	echo "<html><body><script language=javascript1.1>alert('Student deleted'); window.location='$PHP_SELF?module=$module';</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";		
	exit;
}

///////////////////////////////////////////////////////////
//  When the user wants to delete the out dated students
if ($students_action=="delete_old") {
	// This is the command that selects every thing from the database
	$sqlDeleteOld = mysql_query("SELECT * FROM `$mysql_pre$mysql_students`"); 
	// If there it gets any thing back from the database
	if ($resultDeleteOld = mysql_fetch_array($sqlDeleteOld)) {
	do {
			// If it did then it will check the Last Year of the student and compare it with the 
			// current year and if its less then it will delete that student and add 1 to the 
			// total_students_deleted this will be repeated for every student that is less than
			// the current year
			if ($resultDeleteOld["last_year"] < $current_year) {
				$sqlDeleteUser = "DELETE FROM $mysql_pre$mysql_students WHERE id=$resultDeleteOld[id]";
				$resultDeleteUser = mysql_query($sqlDeleteUser) or die(mysql_error()); ;
				$total_students_deleted = ($total_students_deleted + 1);
			}

		} while ($resultDeleteOld = mysql_fetch_array($sqlDeleteOld)); 
	} 
	// If it finds no students that are less than the current year then it will be 0
	if ($total_students_deleted==false) {
		$total_students_deleted = "No";
	}
	// Show ok message with number of students deleted and refresh
	echo "<html><body><script language=javascript1.1>alert('$total_students_deleted students deleted'); window.location='$PHP_SELF?module=$module';</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";		
	exit;
}

///////////////////////////////////////////////////////////
//  When the user wants to make a card
if ($students_action=="make_card") {
	// Fetching student details
	$sqlShowCardStudent = mysql_query("SELECT * FROM $mysql_pre$mysql_students WHERE id=$student_id",$db);
	$resultShowCardStudent = mysql_fetch_array($sqlShowCardStudent);
	// Making barcode from the student ID number
	$barcode = str_pad($resultShowCardStudent["id"], 6, "0", STR_PAD_LEFT);
	// Importing the make card
	include "modules/templates/make_student_card.htm";
	exit;
}

///////////////////////////////////////////////////////////
// Showing a form for the user to make a new student
if ($students_action=="new")
{
	?>

	<center>New Student<br><br>
	<a href="<?php echo "$PHP_SELF?module=$module"; ?>"><img src="images/back.gif" boarder=0> Back</a><br></center>
	<form name="form1" method="post" action="<?php echo "$PHP_SELF?module=$module"; ?>&header_footer=no&students_action=make_student">
	<table cellpadding=2 cellspacing=0 border=1 bordercolor=#FFFFCC align=center>
		<tr> 
			<td class=color3>Full Name:</td>
			<td class=color2><input class="Input" name="new_student_name" type="text" maxlength="35"></td>
		</tr>		
		<tr> 
			<td class=color3>Date of Birth:</td>
			<td class=color2><input class="Input" name="new_student_dob_day" type="text" maxlength="2" size="2" value="DD" onFocus="m(this)">/<input class="Input" name="new_student_dob_month" type="text" maxlength="2" size="2" value="MM" onFocus="m(this)">/<input class="Input" name="new_student_dob_year" type="text" maxlength="4" size="4" value="YYYY" onFocus="m(this)"></td>
		</tr>
		<tr> 
			<td class=color3>Address:</td>
			<td class=color2><textarea name="new_student_address" rows="5" class="Input"></textarea></td>
		</tr>
		<tr> 
			<td class=color3>Post Code:</td>
			<td class=color2><input class="Input" name="new_student_post_code1" type="text" maxlength="4" size="4"> <input class="Input" name="new_student_post_code2" type="text" maxlength="3" size="3"></td>
		</tr>
		<tr> 
			<td class=color3>Telephone No:</td>
			<td class=color2><input class="Input" name="new_student_telephone" type="text" maxlength="12"></td>
		</tr>
		<tr> 
			<td class=color3>Email:</td>
			<td class=color2><input class="Input" name="new_student_email" type="text"></td>
		</tr>
		<tr> 
			<td class=color3>NUS No:</td>
			<td class=color2><input class="Input" name="new_student_nus" type="text" maxlength="16"></td>
		</tr>
		<tr> 
			<td class=color3>Course Name:</td>
			<td class=color2><input class="Input" name="new_student_cname" type="text"></td>
		</tr>
		<tr> 
			<td class=color3>Course Tutor:</td>
			<td class=color2><input class="Input" name="new_student_ctutor" type="text" maxlength="35"></td>
		</tr>		
		<tr> 
			<td class=color3>Last Year:</td>
			<td class=color2><input class="Input" name="new_student_last_year" type="text" maxlength="4" value="YYYY" onFocus="m(this)"></td>
		</tr>
		<tr> 
			<td class=color3>Notes:</td>
			<td class=color2><textarea class="Input" name="new_student_notes" rows="7"></textarea></td>
		</tr>
		<tr> 
			<td class=color3>&nbsp;</td>
			<td class=color3><input name="new_student_edited_by" type="hidden" value="<?php echo "$PHPLibrary[username] on $date"; ?>"><input class="Input" type="submit" name="make_student" value="Ok"></td>
		</tr>
	</table>
	</form>
<?php
}

///////////////////////////////////////////////////////////
// Showing a form for the user to edit a student
if ($students_action=="edit")
{
	// Fetching student details
	$sqlShowEditStudent = mysql_query("SELECT * FROM $mysql_pre$mysql_students WHERE id=$student_id",$db);
	$resultShowEditStudent = mysql_fetch_array($sqlShowEditStudent);
	
	// Converting Post Code in to the boxes
	$post_code1 = substr($resultShowEditStudent["post_code"], 0, 4);
	$post_code2 = substr($resultShowEditStudent["post_code"], 4, 7);
	?>

	<center>Edit Student<br><br>
	<a href="javascript:history.back()"><img src="images/back.gif" boarder=0> Back</a><br></center>
	<form name="form1" method="post" action="<?php echo "$PHP_SELF?module=$module"; ?>&header_footer=no&students_action=edit_student">
	<table cellpadding=2 cellspacing=0 border=1 bordercolor=#FFFFCC align=center>
		<tr> 
			<td class=color3>Full Name:</td>
			<td class=color2><input class="Input" name="edit_student_name" type="text" maxlength="35" value="<?php echo $resultShowEditStudent["name"]; ?>"><br><a href="<?php echo "$PHP_SELF?module=loans&search_student=true&student_id=$resultShowEditStudent[id]"; ?>">View Items Borrowed</a></td>
		</tr>		
		<tr> 
			<td class=color3>Date of Birth:</td>
			<td class=color2><input class="Input" name="edit_student_dob_day" type="text" maxlength="2" size="2" value="<?php echo $resultShowEditStudent["dob_day"]; ?>">/<input class="Input" name="edit_student_dob_month" type="text" maxlength="2" size="2" value="<?php echo $resultShowEditStudent["dob_month"]; ?>">/<input class="Input" name="edit_student_dob_year" type="text" maxlength="4" size="4" value="<?php echo $resultShowEditStudent["dob_year"]; ?>"><br>Age: <b>
			<?php 
			// Work out a correct age
			if ($resultShowEditStudent["dob_day"] <= $current_day && $resultShowEditStudent["dob_month"] <= $current_month) {
				$student_age = ($current_year - $resultShowEditStudent["dob_year"]); 
			}
			else
			{
				$student_age = (($current_year - $resultShowEditStudent["dob_year"]) - 1); 
			}
			echo $student_age; ?></b>
			</td>
		</tr>
		<tr> 
			<td class=color3>Address:</td>
			<td class=color2><textarea name="edit_student_address" rows="6" class="Input"><?php echo $resultShowEditStudent["address"]; ?></textarea></td>
		</tr>
		<tr> 
			<td class=color3>Post Code:</td>
			<td class=color2><input class="Input" name="edit_student_post_code1" type="text" maxlength="4" size="4" value="<?php echo $post_code1; ?>"> <input class="Input" name="edit_student_post_code2" type="text" maxlength="3" size="3" value="<?php echo $post_code2; ?>"><?php if ($post_code1==true) { ?><br><a href="http://www.multimap.co.uk/map/browse.cgi?client=public&db=pc&pc=<?php echo $resultShowEditStudent["post_code"]; ?>" target="_blank">Map of <?php echo $resultShowEditStudent["post_code"]; ?></a><?php } ?></td>
		</tr>
		<tr> 
			<td class=color3>Telephone No:</td>
			<td class=color2><input class="Input" name="edit_student_telephone" type="text" maxlength="12" value="<?php echo $resultShowEditStudent["telephone"]; ?>"></td>
		</tr>
		<tr> 
			<td class=color3>Email:</td>
			<td class=color2><input class="Input" name="edit_student_email" type="text" value="<?php echo $resultShowEditStudent["email"]; ?>"></td>
		</tr>
		<tr> 
			<td class=color3>NUS No:</td>
			<td class=color2><input class="Input" name="edit_student_nus" type="text" maxlength="16" value="<?php echo $resultShowEditStudent["nus"]; ?>"></td>
		</tr>
		<tr> 
			<td class=color3>Course Name:</td>
			<td class=color2><input class="Input" name="edit_student_cname" type="text" value="<?php echo $resultShowEditStudent["cname"]; ?>"></td>
		</tr>
		<tr> 
			<td class=color3>Course Tutor:</td>
			<td class=color2><input class="Input" name="edit_student_ctutor" type="text" maxlength="35" value="<?php echo $resultShowEditStudent["ctutor"]; ?>"></td>
		</tr>		
		<tr> 
			<td class=color3>Last Year:</td>
			<td class=color2><input class="Input" name="edit_student_last_year" type="text" maxlength="4" value="<?php echo $resultShowEditStudent["last_year"]; ?>"></td>
		</tr>
		<tr> 
			<td class=color3>Notes:</td>
			<td class=color2><textarea class="Input" name="edit_student_notes" rows="7"><?php echo $resultShowEditStudent["notes"]; ?></textarea></td>
		</tr>
		<tr> 
			<td class=color3>&nbsp;</td>
			<td class=color3><input name="edit_student_id" type="hidden" value="<?php echo $resultShowEditStudent["id"]; ?>"><input name="edit_student_edited_by" type="hidden" value="<?php echo "$PHPLibrary[username] on $date"; ?>"><input class="Input" type="submit" name="edit_account" value="Ok"></td>
		</tr>
	</table>
	</form>
<?php
}

///////////////////////////////////////////////////////////////////////////////////////////////
// If they are not doing anything then they will be taken to the display all the students page
if ($students_action==false) {
	// JavaScript for check if they are sure they want to delete them
	?>
	<script language='javascript'>
	<!--
	function delete_user(theURL) {
		if (confirm('Are you sure you want to delete this student?')) {
		window.location.href=theURL;
		}
		else {
		alert ('Ok, no action has been taken');
		} 
	}	
	function delete_old(theURL) {
		if (confirm('Are you sure you want to delete all the old students?')) {
		window.location.href=theURL;
		}
		else {
		alert ('Ok, no action has been taken');
		} 
	}
	//-->
	</script>
	<?php
	// JavaScript to make a popup window to print an ID card
	?>
	<script language="JavaScript">
	<!-- Idea by:  Nic Wolfe (Nic@TimelapseProductions.com) -->
	<!-- Web URL:  http://fineline.xs.mw -->
	
	<!-- This script and many more are available free online at -->
	<!-- The JavaScript Source!! http://javascript.internet.com -->
	
	<!-- Begin
	function popUp(URL) {
	day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=240,height=240,left = 462,top = 134');");
	}
	// End -->
	</script>
	
	<center><a href=<?php echo "$PHP_SELF?module=$module"; ?>&students_action=new><img src='images/new.gif' alt='Create New Student'> Create New Student</a> | <a href=javascript:delete_old('<?php echo "$PHP_SELF?module=$module"; ?>&header_footer=no&students_action=delete_old')><img src='images/delete.gif' alt='Delete old Students'> Delete old Students</a><br><br>
	
	<form action='<?php echo "$PHP_SELF?module=$module"; ?>' method='POST'>
	Search for <input class='Input' type='text' size='25' name='search_input' value=''> in 
	<select class='Input' name='search_by'>
	<option value='name' selected>Name</option>
   	<option value='email'>Email</option>
   	<option value='nus'>NUS Number</option>
   	<option value='ctutor'>Tutor</option>
   	<option value='cname'>Course</option>
   	<option value='last_year'>Last Year</option>
	<option value='barcode'>Barcode</option>	
   	</select>
	<input type='submit' value='Search' class='Input'></form>
	</center>
	
	<?php
	// Barcode search
	if ($search_by=="barcode") {
		// Checking to see how many 0's there are at the start of the string
		$zero_length = strspn($search_input, "0");
		
		// Removing the number of 0's at the start
		$search_input = substr($search_input, $zero_length);
		
		// Searching by ID
		$search_by = "id";
	}
	
	// Setting sorting and limiting options if there is no value set
	if ($sort_by==false) {
		$sort_by = "name";
	}

	if ($sort_method==false) {
		$sort_method = "ASC";
	}
	
	if ($from==false) {
		$from = "0";
	}
	
	if ($limit==false) {
		$limit = "10";
	}
	
	// Checking to see if the user is searching or just displaying
	// Gets the data from the MySQL database from the prefixes in the config file, sortby is what the data is being sorted by and the sortmethod is if its being sorted up or down
	if ($search_input==true) {
		// Checking if they are sorting by the barcode if they are it will need to be strict search
		if ($search_by=="id") {
		$sqlStudents = mysql_query("SELECT * FROM $mysql_pre$mysql_students WHERE 1 AND `$search_by` LIKE '$search_input'",$db);
		}
		else
		{
		$sqlStudents = mysql_query("SELECT * FROM $mysql_pre$mysql_students WHERE 1 AND `$search_by` LIKE '%$search_input%'",$db);
		}
	echo "<center>Search Results:</center><br>";
	}
	else
	{
		$sqlStudents = mysql_query("SELECT * FROM $mysql_pre$mysql_students ORDER BY `$sort_by` $sort_method LIMIT $from, $limit",$db);
	}
	
	// Displaying the data
	if ($resultStudents = mysql_fetch_array($sqlStudents)) {
		printf("<table cellpadding=2 cellspacing=0 border=1 bordercolor=#FFFFCC align=center>");
		printf("<tr><td class=color3><b><center>Name</a></center></b></td><td class=color3><b><center>Email</center></b></td><td class=color3><b><center>Course</center></b></td><td class=color3><b><center>Tutor</center></b></td><td class=color3><b><center>Last Edited by</center></b></td><td class=color3><b><center>Make Loan</center></b></td><td class=color3><b><center>ID Card</center></b></td><td class=color3><b><center>Edit</center></b></td><td class=color3><b><center>Delete</center></b></td></tr><tr>");
		// Starts the table colour on gray
		$colour = "color2";
		do {
						
			printf("<td class=$colour>$resultStudents[name]</td><td class=$colour><a href=mailto:$resultStudents[email]>$resultStudents[email]</a></td><td class=$colour><center>$resultStudents[cname]</center></td><td class=$colour><center>$resultStudents[ctutor]</center></td><td class=$colour><center>$resultStudents[edited_by]</center></td>");
			printf("<td class=$colour><center><A HREF=$PHP_SELF?module=loans&loans_action=new&input_student_id=$resultStudents[id]><img src=images/makeloan.gif border=0 alt='Make Loan with $resultStudents[name]'></a></center></td><td class=$colour><center><A HREF=javascript:popUp('$PHP_SELF?module=$module&students_action=make_card&header_footer=no&student_id=$resultStudents[id]')><img src=images/idcard.gif border=0 alt=Card></a></center></td><td class=$colour><center><A HREF=$PHP_SELF?module=$module&students_action=edit&student_id=$resultStudents[id]><img src=images/edit.gif border=0 alt=Edit></a></center></td><td class=$colour><center><A HREF=javascript:delete_user('$PHP_SELF?module=$module&header_footer=no&students_action=delete&student_id=$resultStudents[id]')><img src=images/delete.gif border=0 alt=Delete></a></center></td>");
			printf("</tr>");
			
			// This checks what the table colour is and reverses it to the other colour for example grey, yellow, grey, yellow etc
			if ($colour=="color2") {
				$colour = "ref";
			}
			else
			{
				$colour = "color2";
			}
		
		} while ($resultStudents = mysql_fetch_array($sqlStudents));
		// Ends the table
		printf("</td></tr></table>");
		
		// Works out the next and previous page that should be next viewed
		$next_page = ($from + $limit);
		$previous_page = ($from - $limit);
		
		if ($search_input==false) {
			// Makes sure that it doesn't display a link for -10 or lower
			if ($previous_page >= 0) {
				$previous_page_link = "<a href=$PHP_SELF?module=$module&sort_by=$sort_by&sort_method=$sort_method&limit=$limit&from=$previous_page>&lt;Previous</a>";
			}
			
			echo "<table width=50% border=0 align=center><tr><td>$previous_page_link</td><td><div align=right><a href=$PHP_SELF?module=$module&sort_by=$sort_by&sort_method=$sort_method&limit=$limit&from=$next_page>Next&gt;</a></div></td></tr></table>";
	
			// This is a case statment it checks which item it is sorting by and selects the option from drop down boxes
			switch ($sort_by) {
				case "name":
					$name_selected = "selected";
					break;
				case "email":
					$email_selected = "selected";
					break;
				case "nus":
					$nus_selected = "selected";
					break;
				case "ctutor":
					$ctutor_selected  = "selected";
					break;
				case "cname":
					$cname_selected  = "selected";
					break;
				case "last_year":
					$last_year_selected  = "selected";
					break;
				case "id":
					$id_selected  = "selected";
					break;
			}
			
			// Case statment to check which way it sorted
			switch ($sort_method) {
				case "desc":
					$desc_selected  = "selected";
					break;
				case "asc":
					$asc_selected  = "selected";
					break;
			}
			
			// Case statment to check how many students per page
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
					Sort by <select name='sort_by' class='Input'>		
					<option value='name' $name_selected>Name</option>
					<option value='email' $email_selected>Email</option>
					<option value='nus' $nus_selected>NUS Number</option>
					<option value='ctutor' $ctutor_selected>Tutor</option>
					<option value='cname' $cname_selected>Course</option>
					<option value='last_year' $last_year_selected>Last Year</option>
					<option value='id' $id_selected>Barcode</option>
					</select> in <select name='sort_method' class='Input'>
					<option value='asc' $asc_selected>Ascending Order</option>
					<option value='desc' $desc_selected>Descending Order</option>
					</select> with <select name='limit' class='Input'>
					<option value='10' $ten_selected>10</option>
					<option value='20' $twenty_selected>20</option>
					<option value='30' $thirty_selected>30</option>
					<option value='40' $forty_selected>40</option>
					<option value='50' $fifty_selected>50</option>
					</select> students per page&nbsp;
					<input type='submit' value='Ok' class='Input'></form></center><br>";
		}
		else
		{
			echo "<br><br>";
		}
	}
	else
	{
	// If there are no more students found in the database or there has been some type of error
	echo "<center>No more students found</center>";
	}
}
?>