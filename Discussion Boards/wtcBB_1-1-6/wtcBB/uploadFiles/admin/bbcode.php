<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################# //ADMIN PANEL BBCODE\\ ################## \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// define a few variables
$fileAction = "bbCode";
$permissions = "bbcode";

// include files
include("./../includes/config.php");
include("./../includes/functions.php");
include("./../includes/global_admin.php");
include("./../includes/functions_admin.php");
include("./../includes/functions_bbcode.php");


// ##### DO DELETE BBCODE ##### \\
if($_GET['do'] == "delete") {
	// make sure we have a valid bbcode...
	$checkBBCode = query("SELECT * FROM bbcode WHERE bbcodeid = '".$_GET['id']."' LIMIT 1");

	// uh oh...
	if(!mysql_num_rows($checkBBCode)) {
		construct_error("Sorry, but no BB Codes exist with the given ID. <a href=\"javascript:history.back();\">Go back.</a>");
		exit;
	}

	// construct confirm!!!!!
	if($_POST['confirm']['set_form']) {
		// yes...
		if($_POST['confirm']['yes_no']) {
			// delete bbcode
			query("DELETE FROM bbcode WHERE bbcodeid = '".$_GET['id']."' LIMIT 1");

			redirect("thankyou.php?message=You have successfully deleted the Custom BB Code. You will now be redirected back to the Custom BB Code manager.&uri=bbcode.php?do=manager");
		}

		// no...
		else {
			redirect("bbcode.php?do=manager");
		}
	}
	
	// do a confirm page...
	construct_confirm("Are you sure you want to delete this BB Code?");
}

// ##### DO EDIT BBCODE ##### \\
else if($_GET['do'] == "edit") {
	// make sure we have a valid bbcode...
	$checkBBCode = query("SELECT * FROM bbcode WHERE bbcodeid = '".$_GET['id']."' LIMIT 1");

	// uh oh...
	if(!mysql_num_rows($checkBBCode)) {
		construct_error("Sorry, but no BB Codes exist with the given ID. <a href=\"javascript:history.back();\">Go back.</a>");
		exit;
	}

	// array
	$bbcodeinfo = mysql_fetch_array($checkBBCode);

	// make sure form is set...
	if($_POST['edit_bbcode']['set_form']) {
		// form query by hand.. small enough
		$query = "UPDATE bbcode SET name = '".addslashes($_POST['edit_bbcode']['name'])."' , tag = '".addslashes($_POST['edit_bbcode']['tag'])."' , replacement = '".addslashes($_POST['edit_bbcode']['replacement'])."' , example = '".addslashes($_POST['edit_bbcode']['example'])."' , description = '".addslashes($_POST['edit_bbcode']['description'])."' , use_option = '".$_POST['edit_bbcode']['use_option']."' WHERE bbcodeid = '".$bbcodeinfo['bbcodeid']."'";

		//print($query);

		// run query
		query($query);

		// redirect to thankyou page...
		redirect("thankyou.php?message=Custom BB Code edited successfully. You will now be redirected to the Custom BB Code manager.&uri=bbcode.php?do=manager");
	}

	// do header
	admin_header("wtcBB Admin Panel -Custom BB Code - Edit Custom BB Code");

	construct_title("Edit Custom BB Code");

	construct_table("options","edit_bbcode","bbcode_submit",1);

	construct_header("Edit <em>".$bbcodeinfo['name']."</em> <span class=\"small\">(id: ".$bbcodeinfo['bbcodeid'].")</span>",2);

	construct_text(1,"Title","This is the title of the code.","edit_bbcode","name",$bbcodeinfo['name']);

	construct_text(2,"Tag","This is the <em>name</em> of the BB Code you are adding.","edit_bbcode","tag",$bbcodeinfo['tag']);

	construct_textarea(1,"Replacement","This is the actual HTML used to replace the BB Code. Make sure to use <strong>{param}</strong> or it won't work!","edit_bbcode","replacement",htmlspecialchars($bbcodeinfo['replacement']));

	construct_text(2,"Example","Use the BB Code above to generate an example.","edit_bbcode","example",$bbcodeinfo['example']);

	construct_textarea(1,"Description","","edit_bbcode","description",$bbcodeinfo['description']);

	construct_input(2,"Use Option?","If you are using the <strong>{option}</strong> in your replacement, than make sure to set this to yes!","edit_bbcode","use_option",1,0,$bbcodeinfo);

	construct_footer(2,"bbcode_submit");

	construct_table_END(1);

	
	print("\n\n<br /><br />\n\n");

	
	construct_table("options","something","nothing");

	construct_header("Explanations",1);

	print("\n\n\t<tr>\n\t\t<td class=\"desc1\" style=\"padding: 4px;\">\n\t\t\t<strong>Title:</strong> This is simply the title of the Custom BB Code you are making. It does not matter what you use here, but something of relevance as to what the Custom BB Code does is reccommended.\n\t\t</td>\n\t</tr>\n\n");

	print("\n\n\t<tr>\n\t\t<td class=\"desc2\" style=\"padding: 4px;\">\n\t\t\t<strong>Tag:</strong> This is the name of the BB Code you are adding, which goes inside the square brackets. For example, you would use \"b\" (without the quotes) for [b] tags, and \"url\" (without the quotes) for [url] tags.\n\t\t</td>\n\t</tr>\n\n");

	print("\n\n\t<tr>\n\t\t<td class=\"desc1\" style=\"padding: 4px;\">\n\t\t\t<strong>Replacement:</strong> This is the actual HTML that will replace the Custom BB Code when it's used. You <em>must</em> use the <strong>{param}</strong> in this part. For example, if you wanted to added a Custom BB Code that made things bold, than in the replacement section you would put: <strong>&#60b&#62{param}&#60/b&#62</strong>, where {param} is the text to be formatted. You may also make use of the <strong>{option}</strong> feature, which will allow you to add a Custom BB Code with one HTML attribute. So if you wanted to make a Custom BB Code that allowed users to make a link, you would use this as a replacement: <strong>&#60a href=\"{option}\"&#62{param}&#60/a&#62</strong>, and the BB Code itself would look like: <strong>[url=xxxx]Click Here![/url]</strong> if of course you named the Custom BB Code \"url\".\n\t\t</td>\n\t</tr>\n\n");

	print("\n\n\t<tr>\n\t\t<td class=\"desc2\" style=\"padding: 4px;\">\n\t\t\t<strong>Example:</strong> You would simply put an example of the BB Code at work.\n\t\t</td>\n\t</tr>\n\n");

	print("\n\n\t<tr>\n\t\t<td class=\"desc1\" style=\"padding: 4px;\">\n\t\t\t<strong>Description:</strong> This is simply a place where you can put a description of what the primary use of the Custom BB Code is.\n\t\t</td>\n\t</tr>\n\n");

	print("\n\n\t<tr>\n\t\t<td class=\"desc2_bottom\" style=\"padding: 4px;\">\n\t\t\t<strong>Use Option:</strong> Like explained above, when you use the <strong>{option}</strong> in your HTML replacement, you must set the <strong>Use Option</strong> to yes, or it will not work. Remember, you <em>always</em> use <strong>{param}</strong>, but you do not always use <strong>{option}</strong>.\n\t\t</td>\n\t</tr>\n\n");

	print("\t<tr><td class=\"footer\">&nbsp;</td></tr>\n");

	construct_table_END();

	// do footer
	admin_footer();
}

// ##### DO BBCODE MANAGER ##### \\

else if($_GET['do'] == "manager") {
	// make sure there are bbcodes...
	$checkBBcodes = query("SELECT * FROM bbcode ORDER BY name");

	// uh oh...
	if(!mysql_num_rows($checkBBcodes)) {
		construct_error("Sorry, no Custom BB Codes exist for you to edit.");
		exit;
	}
	
	if($_POST['test_bbcode']['set_form']) {
		// get test bbcode...  loop through all bbcodes
		$formatted_code .= parseAllBBCode($_POST['test_bbcode']['text']);
	}

	// do header
	admin_header("wtcBB Admin Panel - Custom BB Codes - Custom BB Code Manager");

	construct_title("Select a BB Code to Edit");

	if($_POST['test_bbcode']['set_form']) {
		construct_table("options","test_bbcode","testbbcode_submit",1);

		construct_header("Test BB Code",1);
		
		print("\n\n\t<tr>\n\t\t<td class=\"desc1\" style=\"padding: 4px;\">\n\t\t\t<strong>Formatted BB Code:</strong> <br /><br />".$formatted_code."\n\t\t</td>\n\t</tr>\n\n");

		print("\n\n\t<tr>\n\t\t<td class=\"desc2_bottom\" style=\"padding: 4px; text-align: center;\">\n\t\t\t<strong>Type In BB Code to test:</strong> <br /><br /><textarea rows=\"10\" cols=\"75\" name=\"test_bbcode[text]\">".$_POST['test_bbcode']['text']."</textarea>\n\t\t</td>\n\t</tr>\n\n");

		construct_footer("testbbcode_submit",1);

		construct_table_END(1);

		print("<br /><br />");
	}

	construct_table("options","bbcode_form","bbcode_submit",1);
	construct_header("Custom BB Code Manager",5);

	print("\n\n\t<tr>\n");

		print("\t\t<td class=\"cat\">\n");
		print("\t\t\tTitle\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tBB Code\n");
		print("\t\t</td>\n\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tHTML\n");
		print("\t\t</td>\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tReplacement\n");
		print("\t\t</td>\n");

		print("\t\t<td class=\"cat2\">\n");
		print("\t\t\tOptions\n");
		print("\t\t</td>\n");

	print("\t</tr>\n\n");

	while($theseCodes = mysql_fetch_array($checkBBcodes)) {
		print("\t<tr>\n");

			// parse bbcode
			$example = parseAllBBCode($theseCodes['example']);

			print("\t\t<td class=\"desc1\" style=\"text-align: center; white-space: nowrap; width: 15%; padding: 5px;\">\n");
				print("\t\t\t<strong>".$theseCodes['name']."</strong>\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
				print("\t\t\t<textarea cols=\"25\" rows=\"6\" style=\"overflow: auto;\" onkeydown=\"return false;\">".$theseCodes['example']."</textarea>\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"desc1\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
				print("\t\t\t<textarea cols=\"25\" rows=\"6\" style=\"overflow: auto;\" onkeydown=\"return false;\">".$example."</textarea>\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
				print("\t\t\t".$example."\n");
			print("\t\t</td>\n");

			print("\t\t<td class=\"desc2\" style=\"text-align: center; font-size: 8pt; white-space: nowrap; border-left: none; width: 15%; padding: 5px;\">\n");
				print("\t\t\t<button type=\"button\" onClick=\"location.href='bbcode.php?do=edit&id=".$theseCodes['bbcodeid'].";'\" ".$submitbg." style=\"margin: 0px; margin-right: 4px;\">Edit</button> <button type=\"button\" onClick=\"location.href='bbcode.php?do=delete&id=".$theseCodes['bbcodeid']."';\" ".$submitbg." style=\"margin: 0px;\">Delete</button>\n");
			print("\t\t</td>\n");

		print("\t</tr>\n\n");
	}

	print("\t<tr><td class=\"footer\" style=\"border-top: none;\" colspan=\"5\"><button type=\"button\" onclick=\"location.href='bbcode.php?do=add_bbcode';\" ".$submitbg.">Add new Custom BB Code</button></td></tr>\n");
	construct_table_END(1);

	
	if(!$_POST['test_bbcode']['set_form']) {
		print("<br /><br />");

		construct_table("options","test_bbcode","testbbcode_submit",1);

		construct_header("Test BB Code",1);
		
		print("\n\n\t<tr>\n\t\t<td class=\"desc2_bottom\" style=\"padding: 4px; text-align: center;\">\n\t\t\t<strong>Type In BB Code to test:</strong> <br /><br /><textarea rows=\"10\" cols=\"75\" name=\"test_bbcode[text]\"></textarea>\n\t\t</td>\n\t</tr>\n\n");

		construct_footer("testbbcode_submit",1);

		construct_table_END(1);
	}


	// do footer
	admin_footer();
}

// ##### DO ADD BBCODE ##### \\

else if($_GET['do'] == "add_bbcode") {
	// make sure form is set...
	if($_POST['add_bbcode']['set_form']) {
		// form query by hand.. small enough
		$query = "INSERT INTO bbcode (name,tag,replacement,example,description,use_option) VALUES ('".addslashes($_POST['add_bbcode']['name'])."','".addslashes($_POST['add_bbcode']['tag'])."','".addslashes($_POST['add_bbcode']['replacement'])."','".addslashes($_POST['add_bbcode']['example'])."','".addslashes($_POST['add_bbcode']['description'])."','".$_POST['add_bbcode']['use_option']."')";

		//print($query);

		// run query
		query($query);

		// redirect to thankyou page...
		redirect("thankyou.php?message=Custom BB Code added successfully. You will now be redirected to the Custom BB Code manager.&uri=bbcode.php?do=manager");
	}

	// do header
	admin_header("wtcBB Admin Panel - Custom BB Code - Add Custom BB Code");

	construct_title("Add Custom BB Code");

	construct_table("options","add_bbcode","bbcode_submit",1);

	construct_header("Add Custom BB Code",2);

	construct_text(1,"Title","This is the title of the code.","add_bbcode","name");

	construct_text(2,"Tag","This is the <em>name</em> of the BB Code you are adding.","add_bbcode","tag");

	construct_textarea(1,"Replacement","This is the actual HTML used to replace the BB Code. Make sure to use <strong>{param}</strong> or it won't work!","add_bbcode","replacement");

	construct_text(2,"Example","Use the BB Code above to generate an example.","add_bbcode","example");

	construct_textarea(1,"Description","","add_bbcode","description");

	construct_input(2,"Use Option?","If you are using the <strong>{option}</strong> in your replacement, than make sure to set this to yes!","add_bbcode","use_option",1,2);

	construct_footer(2,"bbcode_submit");

	construct_table_END(1);

	
	print("\n\n<br /><br />\n\n");

	
	construct_table("options","something","nothing");

	construct_header("Explanations",1);

	print("\n\n\t<tr>\n\t\t<td class=\"desc1\" style=\"padding: 4px;\">\n\t\t\t<strong>Title:</strong> This is simply the title of the Custom BB Code you are making. It does not matter what you use here, but something of relevance as to what the Custom BB Code does is reccommended.\n\t\t</td>\n\t</tr>\n\n");

	print("\n\n\t<tr>\n\t\t<td class=\"desc2\" style=\"padding: 4px;\">\n\t\t\t<strong>Tag:</strong> This is the name of the BB Code you are adding, which goes inside the square brackets. For example, you would use \"b\" (without the quotes) for [b] tags, and \"url\" (without the quotes) for [url] tags.\n\t\t</td>\n\t</tr>\n\n");

	print("\n\n\t<tr>\n\t\t<td class=\"desc1\" style=\"padding: 4px;\">\n\t\t\t<strong>Replacement:</strong> This is the actual HTML that will replace the Custom BB Code when it's used. You <em>must</em> use the <strong>{param}</strong> in this part. For example, if you wanted to added a Custom BB Code that made things bold, than in the replacement section you would put: <strong>&#60b&#62{param}&#60/b&#62</strong>, where {param} is the text to be formatted. You may also make use of the <strong>{option}</strong> feature, which will allow you to add a Custom BB Code with one HTML attribute. So if you wanted to make a Custom BB Code that allowed users to make a link, you would use this as a replacement: <strong>&#60a href=\"{option}\"&#62{param}&#60/a&#62</strong>, and the BB Code itself would look like: <strong>[url=xxxx]Click Here![/url]</strong> if of course you named the Custom BB Code \"url\".\n\t\t</td>\n\t</tr>\n\n");

	print("\n\n\t<tr>\n\t\t<td class=\"desc2\" style=\"padding: 4px;\">\n\t\t\t<strong>Example:</strong> You would simply put an example of the BB Code at work.\n\t\t</td>\n\t</tr>\n\n");

	print("\n\n\t<tr>\n\t\t<td class=\"desc1\" style=\"padding: 4px;\">\n\t\t\t<strong>Description:</strong> This is simply a place where you can put a description of what the primary use of the Custom BB Code is.\n\t\t</td>\n\t</tr>\n\n");

	print("\n\n\t<tr>\n\t\t<td class=\"desc2_bottom\" style=\"padding: 4px;\">\n\t\t\t<strong>Use Option:</strong> Like explained above, when you use the <strong>{option}</strong> in your HTML replacement, you must set the <strong>Use Option</strong> to yes, or it will not work. Remember, you <em>always</em> use <strong>{param}</strong>, but you do not always use <strong>{option}</strong>.\n\t\t</td>\n\t</tr>\n\n");

	print("\t<tr><td class=\"footer\">&nbsp;</td></tr>\n");

	construct_table_END();

	// do footer
	admin_footer();
}

// otherwise we have an error on our hands.. invalid page...
else {
	construct_error("Invalid page");
}

?>