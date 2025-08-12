<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ############### //ADMIN PANEL FAQ MANAGER\\ ############### \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// define a few variables
$fileAction = "Logs";
$permissions = "logs_stats";

// include files
include("./../includes/config.php");
include("./../includes/functions.php");
include("./../includes/global_admin.php");
include("./../includes/functions_admin.php");


// ##### DO FAQ DELETE ##### \\
if($_GET['do'] == "delete") {
	// make sure we have a valid faq item
	$checkItem = query("SELECT * FROM faq WHERE faqid = '".$_GET['id']."' LIMIT 1");

	// uh oh...
	if(!mysql_num_rows($checkItem)) {
		construct_error("Sorry, no FAQ item exists with the given ID. <a href=\"javascript:histroy.back();\">Go back.</a>");
		exit;
	}

	// array
	$faqinfo = mysql_fetch_array($checkItem);

	// construct confirm!
	// make sure form is set..
	if($_POST['confirm']['set_form']) {
		// yes...
		if($_POST['confirm']['yes_no']) {
			// if category.. delete all childs too!
			if($faqinfo['is_category'] == 1) {
				// delete all childs
				query("DELETE FROM faq WHERE parent = '".$faqinfo['faqid']."'");

				// delete cat
				query("DELETE FROM faq WHERE faqid = '".$faqinfo['faqid']."' LIMIT 1");

				$which = "Category";
			} 

			// otherwise... just delete self
			else {
				query("DELETE FROM faq WHERE faqid = '".$faqinfo['faqid']."' LIMIT 1");

				$which = "Item";
			}

			redirect("thankyou.php?message=You have successfully deleted the FAQ ".$which.". You will now be redirected back to the FAQ Manger.&uri=faq.php?do=manager");
		}

		// no...
		else {
			redirect("faq.php?do=manager");
		}
	}

	// do a confirm page.. depending upon if it's a category or not
	if($faqinfo['is_category'] == 1) {
		construct_confirm("Are you sure you want to delete this FAQ Category? <span style=\"color: #bb0000; font-weight: bold;\">All items that have this category as a parent will be deleted as well.</span> You cannot undo this.");
	} else {
		construct_confirm("Are you sure you want to delete this FAQ Item? You cannot undo this.");
	}
}


// ##### DO EDIT CATEGORY/ITEM ##### \\
else if($_GET['do'] == "edit") {
	// make sure we have a valid faq item
	$checkItem = query("SELECT * FROM faq WHERE faqid = '".$_GET['id']."' LIMIT 1");

	// uh oh...
	if(!mysql_num_rows($checkItem)) {
		construct_error("Sorry, no FAQ item exists with the given ID. <a href=\"javascript:histroy.back();\">Go back.</a>");
		exit;
	}

	// array
	$faqinfo = mysql_fetch_array($checkItem);

	// category or item?
	if($faqinfo['is_category'] == 1) {
		// edit the category
		if(isset($faq_cat['set_form'])) {
			// form query by hand.. easier
			$query = "UPDATE faq SET title = '".addslashes($faq_cat['title'])."' , display_order = '".addslashes($faq_cat['display_order'])."' WHERE faqid = '".$faqinfo['faqid']."'";

			//print($query);

			// run query
			query($query);

			// redirect to thankyou page...
			redirect("thankyou.php?message=FAQ Category <em>".$faq_cat['title']."</em> edited successfully. You will now be redirected to the FAQ Manager.&uri=faq.php?do=manager");
		}

		// do header
		admin_header("wtcBB Admin Panel - FAQ - Edit FAQ Category");

		construct_title("Edit FAQ Category");

		construct_table("options","faq_cat","faq_submit",1);

		construct_header("Edit <em>".$faqinfo['title']."</em> <span class=\"small\">(id: ".$faqinfo['faqid'].")</span>",2);

		construct_text(1,"Title","","faq_cat","title",$faqinfo['title']);

		construct_text(2,"Display Order","","faq_cat","display_order",$faqinfo['display_order'],1);

		construct_footer(2,"faq_submit");

		construct_table_END(1);
		
		// do footer
		admin_footer();
	}

	// item...
	else {
		if($_POST['faq_item']['set_form']) {
			// form query by hand...
			$query = "UPDATE faq SET title = '".addslashes($_POST['faq_item']['title'])."' , parent = '".$_POST['faq_item']['parent']."' , display_order = '".addslashes($_POST['faq_item']['display_order'])."' , message = '".addslashes($_POST['faq_item']['message'])."' WHERE faqid = '".$faqinfo['faqid']."'";

			//print($query);

			// run query
			query($query);

			// redirect to thankyou page...
			redirect("thankyou.php?message=FAQ Item <em>".$_POST['faq_item']['title']."</em> edited successfully. You will now be redirected to the FAQ Manager.&uri=faq.php?do=manager");
		}

		// do header
		admin_header("wtcBB Admin Panel - FAQ - Edit FAQ Item");

		construct_title("Edit FAQ Item");

		construct_table("options","faq_item","faq_submit",1);

		construct_header("Edit <em>".$faqinfo['title']."</em> <span class=\"small\">(id: ".$faqinfo['faqid'].")</span>",2);

		construct_text(1,"Title","","faq_item","title",$faqinfo['title']);

		construct_select_begin(2,"Parent Category","Select the category you wish to put this item under.","faq_item","parent");
			// get parents
			$getParents = query("SELECT * FROM faq WHERE is_category = '1' ORDER BY display_order");

			// loop
			while($faq_parents = mysql_fetch_array($getParents)) {
				// get selected
				if($faq_parents['faqid'] == $faqinfo['parent']) {
					$selected = " selected=\"selected\"";
				} else { 
					$selected = "";
				}

				print("<option value=\"".$faq_parents['faqid']."\"".$selected.">".$faq_parents['title']."</option>\n");
			}

		construct_select_end(2);

		construct_text(1,"Display Order","","faq_item","display_order",$faqinfo['display_order']);

		print("\n\n\t<tr>\n\t\t<td class=\"desc2_bottom\" colspan=\"2\" style=\"text-align: center;\">\n\t\t\t<strong>Type in the text for this item. (You can use HTML.)</strong> <br /><br /><textarea rows=\"10\" cols=\"75\" name=\"faq_item[message]\">".$faqinfo['message']."</textarea>\n\t\t</td>\n\t</tr>\n\n");

		construct_footer(2,"faq_submit");

		construct_table_END(1);
		
		// do footer
		admin_footer();
	}
}


// ##### DO FAQ MANAGER ##### \\
else if($_GET['do'] == "manager") {
	// make sure there are categories.. if there no categories, there is no FAQ
	$checkCategories = query("SELECT * FROM faq WHERE is_category = '1' ORDER BY display_order");

	// uh oh...
	if(!mysql_num_rows($checkCategories)) {
		construct_error("Sorry, no FAQ items exist. Use the <a href=\"faq.php?do=add_faq_category\">Add FAQ Category</a> page to add FAQ categories.");
		exit;
	}

	// update display order
	if($_POST['faq_manager']['set_form']) {
		// loop through order array
		foreach($_POST['order'] as $key => $value) {
			// run query
			query("UPDATE faq SET display_order = '".$value."' WHERE faqid = '".$key."'");
		}

		// redirect to thankyou page...
		redirect("thankyou.php?message=FAQ Display Order saved successfully. You will now be redirected to the FAQ Manager.&uri=faq.php?do=manager");
	}

	// do header
	admin_header("wtcBB Admin Panel - FAQ - FAQ Manager");

	construct_title("FAQ Manager");

	construct_table("options","faq_manager","faq_submit",1);
	construct_header("Select a FAQ Item to Edit",3);

	print("\n\n\t<tr>\n");

		print("\t\t<td class=\"cat\">\n");
		print("\t\t\tTitle\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tDisplay Order\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tOptions\n");
		print("\t\t</td>\n");

	print("\t</tr>\n\n");

	while($faqinfo = mysql_fetch_array($checkCategories)) {
		print("\t<tr>\n");

			print("\t\t<td class=\"desc1\" style=\"white-space: nowrap; width: 15%; padding: 5px;\">\n");
				print("\t\t\t<a href=\"faq.php?do=edit&id=".$faqinfo['faqid']."\">".$faqinfo['title']."</a>\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"desc2\" style=\"font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
				print("\t\t\t<input type=\"text\" class=\"text\" style=\"width: 20px;\" value=\"".$faqinfo['display_order']."\" name=\"order[".$faqinfo['faqid']."]\" />\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"desc2\" style=\"text-align: right; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
				print("\t\t\t<button type=\"button\" onclick=\"location.href='faq.php?do=edit&id=".$faqinfo['faqid']."';\" ".$submitbg.">Edit</button> &nbsp;&nbsp; <button type=\"button\" onclick=\"location.href='faq.php?do=delete&id=".$faqinfo['faqid']."';\" ".$submitbg.">Delete</button> &nbsp;&nbsp; <button type=\"button\" onclick=\"location.href='faq.php?do=add_faq_item&id=".$faqinfo['faqid']."';\" ".$submitbg.">Add Child Item</button>\n");
			print("\t\t</td>\n");

		print("\t</tr>\n\n");

		// now get all childs...
		$getChilds = query("SELECT * FROM faq WHERE parent = '".$faqinfo['faqid']."' ORDER BY display_order");

		// make sure we have rows
		if(mysql_num_rows($getChilds) > 0) {
			while($childinfo = mysql_fetch_array($getChilds)) {
				print("\t<tr>\n");

					print("\t\t<td class=\"desc1\" style=\"white-space: nowrap; width: 15%; padding: 5px;\">\n");
						print("\t\t\t-- -- <a href=\"faq.php?do=edit&id=".$childinfo['faqid']."\">".$childinfo['title']."</a>\n");
					print("\t\t</td>\n");

					print("\t\t<td class=\"desc2\" style=\"font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
						print("\t\t\t<input type=\"text\" class=\"text\" style=\"width: 20px;\" value=\"".$childinfo['display_order']."\" name=\"order[".$childinfo['faqid']."]\" />\n");
					print("\t\t</td>\n");

					print("\t\t<td class=\"desc2\" style=\"text-align: right; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
						print("\t\t\t<button type=\"button\" onclick=\"location.href='faq.php?do=edit&id=".$childinfo['faqid']."';\" ".$submitbg.">Edit</button> &nbsp;&nbsp; <button type=\"button\" onclick=\"location.href='faq.php?do=delete&id=".$childinfo['faqid']."';\" ".$submitbg.">Delete</button>\n");
					print("\t\t</td>\n");

				print("\t</tr>\n\n");
			}
		}
	}

	print("\t<tr><td class=\"footer\" style=\"border-top: none;\" colspan=\"3\"><button type=\"button\" onclick=\"location.href='faq.php?do=add_faq_category';\" ".$submitbg.">Add FAQ Category</button> &nbsp;&nbsp;&nbsp; <button type=\"submit\" ".$submitbg.">Save Display Order</button></td></tr>\n");
	construct_table_END(1);

	// do footer
	admin_footer();
}


// ##### DO ADD ITEM ##### \\
else if($_GET['do'] == "add_faq_item") {
	// make sure we have categories...
	$checkCategories = query("SELECT * FROM faq WHERE is_category = '1' ORDER BY display_order");

	// uh oh...
	if(!mysql_num_rows($checkCategories)) {
		construct_error("There are no FAQ Categories to add items to. Please use the <a href=\"faq.php?do=add_faq_category\">Add FAQ Category</a> page to add one.");
		exit;
	}

	if($_POST['faq_item']['set_form']) {
		// form query by hand...
		$query = "INSERT INTO faq (title,parent,display_order,message,is_category) VALUES ('".addslashes($_POST['faq_item']['title'])."','".$_POST['faq_item']['parent']."','".addslashes($_POST['faq_item']['display_order'])."','".addslashes($_POST['faq_item']['message'])."','-1')";

		//print($query);

		// run query
		query($query);

		// redirect to thankyou page...
		redirect("thankyou.php?message=FAQ Item added successfully. You will now be redirected to the FAQ Manager.&uri=faq.php?do=manager");
	}

	// do header
	admin_header("wtcBB Admin Panel - FAQ - Add FAQ Item");

	construct_title("Add FAQ Item");

	construct_table("options","faq_item","faq_submit",1);

	construct_header("Add FAQ Item",2);

	construct_text(1,"Title","","faq_item","title");

	construct_select_begin(2,"Parent Category","Select the category you wish to put this item under.","faq_item","parent");

		// loop
		while($faq_parents = mysql_fetch_array($checkCategories)) {
			// get selected
			if(isset($_GET['id'])) {
				if($_GET['id'] == $faq_parents['faqid']) {
					$selected = " selected=\"selected\"";
				} else {
					$selected = "";
				}
			} else { 
				$selected = "";
			}

			print("<option value=\"".$faq_parents['faqid']."\"".$selected.">".$faq_parents['title']."</option>\n");
		}

	construct_select_end(2);

	construct_text(1,"Display Order","","faq_item","display_order");

	print("\n\n\t<tr>\n\t\t<td class=\"desc2_bottom\" colspan=\"2\" style=\"text-align: center;\">\n\t\t\t<strong>Type in the text for this item. (You can use HTML.)</strong> <br /><br /><textarea rows=\"10\" cols=\"75\" name=\"faq_item[message]\"></textarea>\n\t\t</td>\n\t</tr>\n\n");

	construct_footer(2,"faq_submit");

	construct_table_END(1);
	
	// do footer
	admin_footer();
}

// ##### DO ADD CATEGORY ##### \\
else if($_GET['do'] == "add_faq_category") {
	// add the category
	if($_POST['faq_cat']['set_form']) {
		// form query by hand.. easier
		$query = "INSERT INTO faq (title,display_order,is_category,parent) VALUES ('".addslashes($_POST['faq_cat']['title'])."','".$_POST['faq_cat']['display_order']."','1','-1')";

		//print($query);

		// run query
		query($query);

		// redirect to thankyou page...
		redirect("thankyou.php?message=FAQ Category added successfully. You will now be redirected to the FAQ Manager.&uri=faq.php?do=manager");
	}

	// do header
	admin_header("wtcBB Admin Panel - FAQ - Add FAQ Category");

	construct_title("Add FAQ Category");

	construct_table("options","faq_cat","faq_submit",1);

	construct_header("Add FAQ Category",2);

	construct_text(1,"Title","","faq_cat","title");

	construct_text(2,"Display Order","","faq_cat","display_order","",1);

	construct_footer(2,"faq_submit");

	construct_table_END(1);
	
	// do footer
	admin_footer();
}

// otherwise we have an error on our hands.. invalid page...
else {
	construct_error("Invalid page");
}

?>