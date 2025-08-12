<?php
////////////////////////////////////////////////
// When the user submits the make new item form
if ($items_action=="make_item") {
	// Converting any HTML to standard text in the user data
	$new_item_name = htmlspecialchars($new_item_name);
	$new_item_description = htmlspecialchars($new_item_description);		
	$new_item_condition = htmlspecialchars($new_item_condition);
	$new_item_price = htmlspecialchars($new_item_price);
	$new_item_notes = htmlspecialchars($new_item_notes);
	$new_item_edited_by = htmlspecialchars($new_item_edited_by );
	
	// Triming blank spaces at the start and end of the user data
	$new_item_name = trim($new_item_name);
	$new_item_description = trim($new_item_description);		
	$new_item_condition = trim($new_item_condition);
	$new_item_price = trim($new_item_price);
	$new_item_notes = trim($new_item_notes);
	$new_item_edited_by = trim($new_item_edited_by );
	
	// Checking inputs that should be numbers
	if (is_numeric ($new_item_price)==false)
	{
		echo "<html><body><script language=javascript1.1>alert('Please check you have entered a number for the items price'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
		exit;
	}
	
	// Making sure the form is not blank
	if ($new_item_name==false || $new_item_condition==false)
	{
		echo "<html><body><script language=javascript1.1>alert('Please fill out the required fields'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
		exit;
	}
	
	// Every thing is ok save details in the MySQL database
	$sqlMakeItem = "INSERT $mysql_pre$mysql_items SET name='$new_item_name',description='$new_item_description',condition='$new_item_condition',price='$new_item_price',notes='$new_item_notes',edited_by='$new_item_edited_by'";
	$resultMakeItem = mysql_query($sqlMakeItem) or die(mysql_error()); ;
	// Show ok message and refresh
	echo "<html><body><script language=javascript1.1>alert('Item made'); window.location='$PHP_SELF?module=$module&items_action=new';</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
	exit;
}

////////////////////////////////////////////////
// When the user submits the edit item form
if ($items_action=="edit_item") {
	// Converting any HTML to standard text in the user data
	$edit_item_name = htmlspecialchars($edit_item_name);
	$edit_item_description = htmlspecialchars($edit_item_description);		
	$edit_item_condition = htmlspecialchars($edit_item_condition);
	$edit_item_price = htmlspecialchars($edit_item_price);
	$edit_item_status = htmlspecialchars($edit_item_status);
	$edit_item_notes = htmlspecialchars($edit_item_notes);
	$edit_item_edited_by = htmlspecialchars($edit_item_edited_by );
	
	// Triming blank spaces at the start and end of the user data
	$edit_item_name = trim($edit_item_name);
	$edit_item_description = trim($edit_item_description);		
	$edit_item_condition = trim($edit_item_condition);
	$edit_item_price = trim($edit_item_price);
	$edit_item_status = trim($edit_item_status);
	$edit_item_notes = trim($edit_item_notes);
	$edit_item_edited_by = trim($edit_item_edited_by );
	
	// Checking inputs that should be numbers
	if (is_numeric ($edit_item_price)==false)
	{
		echo "<html><body><script language=javascript1.1>alert('Please check you have entered a number for the items price'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
	}
	
	// Making sure the form is not blank
	if ($edit_item_name==false || $edit_item_condition==false || $edit_item_condition==false)
	{
		echo "<html><body><script language=javascript1.1>alert('Please fill out the required fields'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
		exit;
	}
	
	// Every thing is ok save details in the MySQL database
	$sqlEditItem = "UPDATE $mysql_pre$mysql_items SET name='$edit_item_name',description='$edit_item_description',condition='$edit_item_condition',price='$edit_item_price',status='$edit_item_status',notes='$edit_item_notes',edited_by='$edit_item_edited_by' WHERE id=$edit_item_id";
	$resultEditItem = mysql_query($sqlEditItem) or die(mysql_error()); ;
	// Show ok message and refresh
	echo "<html><body><script language=javascript1.1>alert('Item edited'); window.location='$PHP_SELF?module=$module';</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
	exit;
}

///////////////////////////////////////////////////////////
//  When the user submits to delete a item
if ($items_action=="delete") {
	$sqlDeleteUser = "DELETE FROM $mysql_pre$mysql_items WHERE id=$item_id";
	$resultDeleteUser = mysql_query($sqlDeleteUser) or die(mysql_error()); ;
	echo "<html><body><script language=javascript1.1>alert('Item deleted'); window.location='$PHP_SELF?module=$module';</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";		
	exit;
}

///////////////////////////////////////////////////////////
//  When the user wants to make a sticker
if ($items_action=="make_sticker") {
	// Fetching item details
	$sqlShowStickerItem = mysql_query("SELECT * FROM $mysql_pre$mysql_items WHERE id=$item_id",$db);
	$resultShowStickerItem = mysql_fetch_array($sqlShowStickerItem);
	// Making barcode from the item ID number
	$barcode = str_pad($resultShowStickerItem["id"], 6, "0", STR_PAD_LEFT);
	// Importing the make card
	include "modules/templates/make_item_sticker.htm";
	exit;
}

///////////////////////////////////////////////////////////
// Showing a form for the user to make a new item
if ($items_action=="new")
{
	?>

	<center>New Item<br><br>
	<a href="<?php echo "$PHP_SELF?module=$module"; ?>"><img src="images/back.gif" boarder=0> Back</a><br></center>
	<form name="form1" method="post" action="<?php echo "$PHP_SELF?module=$module"; ?>&header_footer=no&items_action=make_item">
	<table cellpadding=2 cellspacing=0 border=1 bordercolor=#FFFFCC align=center>
		<tr> 
			<td class=color3>Item Name:</td>
			<td class=color2><input class="Input" name="new_item_name" type="text" maxlength="30"></td>
		</tr>		
		<tr> 
			<td class=color3>Description:</td>
			<td class=color2><textarea name="new_item_description" rows="5" class="Input"></textarea></td>
		</tr>
		<tr> 
			<td class=color3>Condition:</td>
			<td class=color2><input class="Input" name="new_item_condition" type="text" maxlength="20"></td>
		</tr>
		<tr> 
			<td class=color3>Price when New:</td>
			<td class=color2>£<input class="Input" name="new_item_price" type="text" maxlength="4" size="4"></td>
		</tr>
		<tr> 
			<td class=color3>Notes:</td>
			<td class=color2><textarea class="Input" name="new_item_notes" rows="7"></textarea></td>
		</tr>
		<tr> 
			<td class=color3>&nbsp;</td>
			<td class=color3><input name="new_item_edited_by" type="hidden" value="<?php echo "$PHPLibrary[username] on $date"; ?>"><input class="Input" type="submit" name="make_item" value="Ok"></td>
		</tr>
	</table>
	</form>
<?php
}

///////////////////////////////////////////////////////////
// Showing a form for the user to edit a item
if ($items_action=="edit")
{
	// Fetching item details
	$sqlShowEditItem = mysql_query("SELECT * FROM $mysql_pre$mysql_items WHERE id=$item_id",$db);
	$resultShowEditItem = mysql_fetch_array($sqlShowEditItem);
	
	// Converting Post Code in to the boxes
	$post_code1 = substr($resultShowEditItem["post_code"], 0, 4);
	$post_code2 = substr($resultShowEditItem["post_code"], 4, 7);
	?>

	<center>Edit Item<br><br>
	<a href="javascript:history.back()"><img src="images/back.gif" boarder=0> Back</a><br></center>
	<form name="form1" method="post" action="<?php echo "$PHP_SELF?module=$module"; ?>&header_footer=no&items_action=edit_item">
	<table cellpadding=2 cellspacing=0 border=1 bordercolor=#FFFFCC align=center>
		<tr> 
			<td class=color3>Item Name:</td>
			<td class=color2><input class="Input" name="edit_item_name" type="text" maxlength="30" value="<?php echo $resultShowEditItem["name"]; ?>"></td>
		</tr>		
		<tr> 
			<td class=color3>Description:</td>
			<td class=color2><textarea name="edit_item_description" rows="5" class="Input"><?php echo $resultShowEditItem["description"]; ?></textarea></td>
		</tr>
		<tr> 
			<td class=color3>Condition:</td>
			<td class=color2><input class="Input" name="edit_item_condition" type="text" maxlength="20" value="<?php echo $resultShowEditItem["condition"]; ?>"></td>
		</tr>
		<tr> 
			<td class=color3>Price when New:</td>
			<td class=color2>£<input class="Input" name="edit_item_price" type="text" maxlength="4" size="4" value="<?php echo $resultShowEditItem["price"]; ?>"></td>
		</tr>
		<tr> 
			<td class=color3>Status:</td>
			<td class=color2>
			<?php // These PHP commands below work out if its selected as lost and can change it back to In if they need to 
			if ($resultShowEditItem[status]=="Out") {
				echo "$resultShowEditItem[status]*";
				echo "<input name='edit_item_status' type='hidden' value='$resultShowEditItem[status]'>";
			}
			else
			{
				?>
				<select name="edit_item_status" class="Input">
				<option value="<?php if ($resultShowEditItem["status"]=="Lost") { echo "In"; } else { echo $resultShowEditItem["status"]; } ?>"><?php if ($resultShowEditItem["status"]=="Lost") {echo "In"; } else { echo $resultShowEditItem["status"]; } ?></option>
				<option <?php if ($resultShowEditItem["status"]=="Lost") { echo "selected"; } ?> value="Lost">Lost/Stolen</option>
				</select><?php
			}
			?>
			</td>
		</tr>
		<tr> 
			<td class=color3>Notes:</td>
			<td class=color2><textarea class="Input" name="edit_item_notes" rows="7"><?php echo $resultShowEditItem["notes"]; ?></textarea></td>
		</tr>
		<tr> 
			<td class=color3>&nbsp;</td>
			<td class=color3><input name="edit_item_id" type="hidden" value="<?php echo $resultShowEditItem["id"]; ?>"><input name="edit_item_edited_by" type="hidden" value="<?php echo "$PHPLibrary[username] on $date"; ?>"><input class="Input" type="submit" name="edit_item" value="Ok"></td>
		</tr>
	</table>
	</form>
	<?php
	if ($resultShowEditItem[status]=="Out") {
		echo "<center><font size=1>*To mark this as lost first change the loan of this item to 'in'</font></center>";
	}

}

///////////////////////////////////////////////////////////////////////////////////////////////
// If they are not doing anything then they will be taken to the display all the items page
if ($items_action==false) {
	// JavaScript for check if they are sure they want to delete them
	?>
	<script language='javascript'>
	<!--
	function delete_user(theURL) {
		if (confirm('Are you sure you want to delete this item?')) {
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
	eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=250,height=170,left = 462,top = 134');");
	}
	// End -->
	</script>
	
	<center><a href=<?php echo "$PHP_SELF?module=$module"; ?>&items_action=new><img src='images/new.gif' alt='Create New Item'> Create New Item</a><br><br>
	
	<form action='<?php echo "$PHP_SELF?module=$module"; ?>' method='POST'>
	Search for <input class='Input' type='text' size='25' name='search_input' value=''> in 
	<select class='Input' name='search_by'>
	<option value='name' selected>Name</option>
   	<option value='description'>Description</option>
   	<option value='condition'>Condition</option>
   	<option value='price'>Price</option>
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
		$sqlItems = mysql_query("SELECT * FROM $mysql_pre$mysql_items WHERE 1 AND `$search_by` LIKE '$search_input'",$db);
		}
		else
		{
		$sqlItems = mysql_query("SELECT * FROM $mysql_pre$mysql_items WHERE 1 AND `$search_by` LIKE '%$search_input%'",$db);
		}
	echo "<center>Search Results:</center><br>";
	}
	else
	{
		$sqlItems = mysql_query("SELECT * FROM $mysql_pre$mysql_items ORDER BY `$sort_by` $sort_method LIMIT $from, $limit",$db);
	}
	
	// Displaying the data
	if ($resultItems = mysql_fetch_array($sqlItems)) {
		printf("<table cellpadding=2 cellspacing=0 border=1 bordercolor=#FFFFCC align=center>");
		printf("<tr><td class=color3><b><center>Name</a></center></b></td><td class=color3><b><center>Condition</center></b></td><td class=color3><b><center>Price</center></b></td><td class=color3><b><center>Status</center></b></td><td class=color3><b><center>Last Borrowed by</center></b></td><td class=color3><b><center>Last Edited by</center></b></td><td class=color3><b><center>Loan Item</a></center></b></td><td class=color3><b><center>ID Sticker</center></b></td><td class=color3><b><center>Edit</center></b></td><td class=color3><b><center>Delete</center></b></td></tr><tr>");
		// Starts the table colour on gray
		$colour = "color2";
		do {
						
			// Checks to see if there is a last borrower
			if ($resultItems[last_student_id]==true) {
				// Student ID to Name Converter
				$sqlStudentConvert = mysql_query("SELECT `name` FROM $mysql_pre$mysql_students WHERE 1 AND `id` = $resultItems[last_student_id] LIMIT 0, 1");
				$resultStudentConvert = mysql_fetch_array($sqlStudentConvert);
				// Checking if it found anything
				if ($resultStudentConvert[name]==true) {
				$StudentConvert = $resultStudentConvert[name];
				}
				else
				{			
				$StudentConvert = "ERROR: Student deleted";
				}
				
				$last_borrower = "<a href=$PHP_SELF?module=students&students_action=edit&student_id=$resultItems[last_student_id]>$StudentConvert</a>";
			}
			else
			{
				$last_borrower = "No one";
			}
			
			printf("<td class=$colour>$resultItems[name]</td><td class=$colour>$resultItems[condition]</td><td class=$colour><center>£$resultItems[price]</center></td><td class=$colour><center>$resultItems[status]</center></td><td class=$colour><center>$last_borrower</center></td><td class=$colour><center>$resultItems[edited_by]</center></td>");
			if ($resultItems[status]=="In") {
				echo "<td class=$colour><center><A HREF=$PHP_SELF?module=loans&loans_action=cookie_loan&header_footer=no&new_loan_item_barcode=$resultItems[id]><img src=images/makeloan.gif border=0 alt='Loan Item $resultItems[name]'></a></center></td>";
			}
			else
			{
				echo "<td class=$colour><center><img src=images/out.gif alt='This item is already out'></center></td>";
			}
			printf("<td class=$colour><center><A HREF=javascript:popUp('$PHP_SELF?module=$module&items_action=make_sticker&header_footer=no&item_id=%s')><img src=images/idcard.gif border=0 alt=Sticker></a></center></td><td class=$colour><center><A HREF=$PHP_SELF?module=$module&items_action=edit&item_id=%s><img src=images/edit.gif border=0 alt=Edit></a></center></td><td class=$colour><center><A HREF=javascript:delete_user('$PHP_SELF?module=$module&header_footer=no&items_action=delete&item_id=%s')><img src=images/delete.gif border=0 alt=Delete></a></center></td>", $resultItems["id"], $resultItems["id"], $resultItems["id"]);
			printf("</tr>");
			
			// This checks what the table colour is and reverses it to the other colour for example grey, yellow, grey, yellow etc
			if ($colour=="color2") {
				$colour = "ref";
			}
			else
			{
				$colour = "color2";
			}
		
		} while ($resultItems = mysql_fetch_array($sqlItems));
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
				case "condition":
					$condition_selected = "selected";
					break;
				case "price":
					$price_selected = "selected";
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
			
			// Case statment to check how many items per page
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
					<option value='condition' $condition_selected>Condition</option>
					<option value='price' $price_selected>Price</option>
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
					</select> items per page&nbsp;
					<input type='submit' value='Ok' class='Input'></form></center><br>";
		}
		else
		{
			echo "<br><br>";
		}
	}
	else
	{
	// If there are no more items found in the database or there has been some type of error
	echo "<center>No more items found</center>";
	}
}
?>