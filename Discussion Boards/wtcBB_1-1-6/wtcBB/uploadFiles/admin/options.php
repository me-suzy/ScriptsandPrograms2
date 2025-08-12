<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################# //ADMIN PANEL CONTENT\\ ################# \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// define a few variables
$fileAction = "wtcBB Options";
$permissions = "wtcBBoptions";

// include files
include("./../includes/config.php");
include("./../includes/functions.php");
include("./../includes/global_admin.php");
include("./../includes/functions_admin.php");


if($_GET['do'] == "options") {	
	// only do the below if the form is set...
	if($_POST['options']['set_form']) {

		print("<br /><br />");

		// set counter
		$i = 0;

		// intialize the $query var
		$query = "UPDATE wtcBBoptions SET ";

		foreach($_POST['options'] as $option_key => $option_value) {
			// check to make sure we don't input the "set_form"
			if($option_key != "set_form") {
				// should we use comma?
				if($i == 0) {
					$comma = "";
				} else {
					$comma = ", ";
				}

				// form the update query...
				$query .= $comma;
				$query .= $option_key." = '".htmlspecialchars(addslashes($option_value))."'";

				// increment $i
				$i++;
			}
		} 

		// update the DB
		query($query);

		// redirect to thankyou page...
		redirect("thankyou.php?message=Thank you for updating your options. You will now be redirected back.&uri=options.php?do=options");

		/*print("<br /><br />");

		print($query);

		print("<br /><br />"); */
	}

	// do header
	admin_header("wtcBB Admin Panel - Options");

	construct_title("wtcBB Admin Options");

	?>

	<table border="0" cellspacing="0" cellpadding="4" class="options">
		<tr>
			<td class="header">Navigation</td>
		</tr>

		<tr>
			<td class="desc1_bottom" style="text-align: center; padding: 7px;">
				<form style="margin: 0px; padding: 0px;" method="post" action="" name="myForm">
					<select name="navi" onChange="location.href=(myForm.navi.options[myForm.navi.selectedIndex].value)">
						<option value="#" selected="selected">---NAVIGATION---</option>
						<option value="options.php?do=options#on_or_off">Turn bulletin board on or off</option>
						<option value="options.php?do=options#bbinfo">Bulletin Board Information</option>
						<option value="options.php?do=options#gen_info">General Information</option>
						<option value="options.php?do=options#date_time">Date and Time Options</option>
						<option value="options.php?do=options#cookies">Cookies</option>
						<option value="options.php?do=options#censors">Censors</option>
						<option value="options.php?do=options#email_opt">Email Options</option>
						<option value="options.php?do=options#user_reg">User Registration Options</option>
						<option value="options.php?do=options#user_prof">User Profile Options</option>
						<option value="options.php?do=options#guestbook">Guestbook Config</option>
						<option value="options.php?do=options#warn">Warning System Config</option>
						<option value="options.php?do=options#user_default">Default BB Code Settings</option>
						<option value="options.php?do=options#avatar">Avatar Options</option>
						<option value="options.php?do=options#memberlist">Memberlist Options</option>
						<option value="options.php?do=options#banning_opt">Banning Options</option>
						<option value="options.php?do=options#post_opt">Posting Options</option>
						<option value="options.php?do=options#post_display">Posting Display Options</option>
						<option value="options.php?do=options#search_opt">Searching Options</option>
						<option value="options.php?do=options#forum_home">Forums Homepage Settings</option>
						<option value="options.php?do=options#forum_settings">Forums Settings</option>
						<option value="options.php?do=options#in_forum_settings">In-Forum Settings</option>
						<option value="options.php?do=options#thread_settings">Thread Settings</option>
						<option value="options.php?do=options#personal_opt">Personal Message Options</option>
						<option value="options.php?do=options#online_settings">Who's Online Settings</option>
					</select>

					<button type="button" onClick="location.href=(myForm.navi.options[myForm.navi.selectedIndex].value)" style="margin: 2px; margin-bottom: 0px;" <?php print($submitbg); ?>>Go</button>
				</form>
			</td>
		</tr>

		<tr>
			<td class="footer" colspan="0">&nbsp;</td>
		</tr>

	</table>

	<br />

	<?php

	// ##### START "Turn bulletin board on or off" ##### \\
	
	a_name("on_or_off");

	construct_table("options","options","option_submit",1);
	construct_header("Turn bulletin board on or off",2);

	construct_text(1,"Floodcheck (seconds)","This will allow you to set a \"Flood Check\" sort of speak. It will take effect globally, and anyone try to perform an action under the given amount of time, will be given an error. Actions include posting, personal messaging, emailing, etc.","options","floodcheck",$bboptions['floodcheck']);

	construct_text(2,"Number of sessions limit","This will allow you to control how many people are visiting your site to keep the server less busy. Set to <strong>0</strong> to disable.","options","server_sessionlimit",$bboptions['server_sessionlimit']);

	construct_input(1,"Bulletin board active?","Selecting 'No' will disable all access to your bulletin to board to everybody but administrators.","options","active");

	construct_textarea(2,"Reason message:","The message displayed here is what will be displayed to users who aren't administators when the above option is set to no.","options","active_reason",$bboptions['active_reason'],1);

	construct_footer(2,"option_submit");
	construct_table_END();


	print "<br /><br />";


	// ##### START "Bulletin board information" ##### \\

	a_name("bbinfo");

	construct_table("options","options","option_submit");
	construct_header("Bulletin Board Information",2);

	construct_text(2,"Board name","Put the name of your message board here.","options","details_boardname",$bboptions['details_boardname']);

	construct_text(1,"Board URL","Put the URL of your message board here. Notice that there is no trailing \"/\". This is imperative for virtually all emails to work as they are intended.","options","details_boardurl",$bboptions['details_boardurl']);

	construct_text(2,"Homepage name","Put the name of your homepage here. It will be displayed in the footer, which is on every page.","options","details_homepage",$bboptions['details_homepage']);

	construct_text(1,"Homepage URL","Put the URL of your homepage here. This will link to your homepage in the footer of every page using the above display text.","options","details_homepageurl",$bboptions['details_homepageurl']);

	construct_text(2,"Contact Email","You can put the \"Contact Us\" email here. It will be displayed at the bottom of each page.","options","details_contact",$bboptions['details_contact'],1);

	// meh don't need these:
	/*construct_text(1,"Privacy Statement URL","You can put the link to your privacy statement if you have one. It will be displayed at the bottom of each page.","options","details_privacy",$bboptions['details_privacy']);

	construct_text(2,"Copyright","You can put your copyright text here. It will be displayed at the bottom of each page.","options","details_copyright",$bboptions['details_copyright']);

	construct_text(1,"Company Name:","","options","details_company",$bboptions['details_company'],1);*/

	construct_footer(2,"option_submit1");
	construct_table_END();


	print "<br /><br />";


	// ##### START "General Information" ##### \\

	a_name("gen_info");

	construct_table("options","options","option_submit");
	construct_header("General Information",2);

	construct_text(1,"Meta Keywords","Put the keywords for your website. They will be inputted into the &#60meta&#62 tags.","options","general_keywords",$bboptions['general_keywords']);

	construct_text(2,"Meta Description","Put the description of your web site here. It will be inputted into the &#60meta&#62 tags.","options","general_description",$bboptions['general_description']);


	construct_select_begin(1,"Default Style","Select a default style for this message board.","options","general_style");
			// get styles...
			$checkStyles = mysql_query("SELECT * FROM styles ORDER BY display_order, title");

			// loop through styles
			while($styleinfo = mysql_fetch_array($checkStyles)) {
				// get selected...
				if($bboptions['general_style'] == $styleinfo['styleid']) {
					$selected = " selected=\"selected\"";
				} else {
					$selected = "";
				}

				print("<option value=\"".$styleinfo['styleid']."\"".$selected.">".$styleinfo['title']."</option>\n");
			}

	construct_select_end(1);


	construct_input(2,"Use forum jump?","Selecting yes here will enable the forum jump on all pages, which will display a drop down menu with links to every forum. On boards with a large amount of forums, it could slow down performance.","options","general_forumjump");

	construct_text(1,"Word Wrap","This will chop this amount of characters with a new line when there is no spaces. Set to <strong>0</strong> to disable. However, it is highly recommended that you keep this above 50.","options","general_wordwrap",$bboptions['general_wordwrap']);

	construct_text(2,"Number of page links","If a thread requires more pages to see all posts, page numbers will be listed at the bottom and top of each thread (with the default wtcBB template set). This number is the amount of page links displayed on both sides of the current page link. Setting this to 0 will display <strong>all</strong> page links.","options","general_pagelinks",$bboptions['general_pagelinks']);

	construct_input(1,"Comments with template name","This is useful if you want to change the display of anything on your forum. The template name will be commented in the beginning and end of it.","options","general_templatename");

	construct_input(2,"External CSS?","Selecting yes will use an external CSS file to store all the CSS. Keeping this as no will put all the CSS in each document.","options","css_in_file");

	construct_input(1,"Enable Moderator Control Panel","Enabling this will allow moderators to have access to a 'dumbed down' version of the administrator control panel. You can restrict certain areas per moderator as well.","options","general_modcp",1);

	construct_footer(2,"option_submit1");
	construct_table_END();

	

	print "<br /><br />";


	// ##### START "Date and Time Options" ##### \\

	a_name("date_time");

	construct_table("options","options","option_submit");
	construct_header("Date and Time Options",2);

	construct_select_begin(2,"Default time zone offset","This is the default time zone offset for guests and new users. Do not take into daylight savings time, instead look at the below option.","options","date_timezone");

		$items = "(GMT -12:00)*(GMT -11:00)*(GMT -10:00)*(GMT -9:00)*(GMT -8:00)*(GMT -7:00)*(GMT -6:00)*(GMT -5:00)*(GMT -4:00)*(GMT -3:00)*(GMT -2:00)*(GMT -1:00)*(GMT) *(GMT +1:00)*(GMT +2:00)*(GMT +3:00)*(GMT +4:00)*(GMT +5:00)*(GMT +6:00)*(GMT +7:00)*(GMT +8:00)*(GMT +9:00)*(GMT +10:00)*(GMT +11:00)*(GMT +12:00)";

		$option_select = split("\*",$items);

		foreach($option_select as $option_key => $option_value) {
			$option_key -= 12;

			if($option_key == $bboptions['date_timezone']) {
				$check_select = " selected=\"selected\"";
			} else {
				$check_select = "";
			}

			print("<option value=\"".$option_key."\"".$check_select.">".$option_value."</option>\n");		
		}

	construct_select_end(2);

	construct_input(1,"DST enabled?","This will only effect users that are not logged in, or guests. This should relate to the default time zone set above. This will have no effect on registered users, as they can set their own DST in their user control panel.","options","date_dst");

	construct_select_begin(2,"Default thread view age","This is the default thread view age. This will cut off any threads older than this time period.","options","date_default_thread_age");

		$items = "Show threads from the last day,Show threads from the last two days,Show threads from the last week,Show threads from the last two weeks,Show threads from the last month,Show threads from the last 45 days,Show threads from the last two months,Show threads from the last 75 days,Show threads from the last 100 days,Show threads from the last six months,Show threads from the last year,Show all threads";

		// do default thread view age for the options...
		$option_select = split(",",$items);

		foreach($option_select as $option_key => $option_value) {
			if($option_key == $bboptions['date_default_thread_age']) {
				$check_select = " selected=\"selected\"";
			} else {
				$check_select = "";
			}

			print("<option value=\"".$option_key."\"".$check_select.">".$option_value."</option>\n");
		}

	construct_select_end(1);

	construct_input(1,"Use Today/Yesterday instead of date?","If the date is today, it will use \"Today\" instead of the actual date. The same goes for \"Yesterday\"","options","date_todayYesterday");

	construct_text(2,"Format for date","This is the format in which the date will be presented on all wtcBB pages. This uses the PHP <strong>date()</strong> function. So the format is a bit weird. <br /><br />See <a href=\"http://us4.php.net/manual/en/function.date.php\" target=\"_blank\">http://us4.php.net/manual/en/function.date.php</a> for more details.","options","date_formatted",$bboptions['date_formatted']);

	construct_text(1,"Format for time","This is the format in which the time will be presented on all wtcBB pages. This uses the PHP <strong>date()</strong> function. The same method as the way above. So the format is a bit weird. <br /><br />See <a href=\"http://us4.php.net/manual/en/function.date.php\" target=\"_blank\">http://us4.php.net/manual/en/function.date.php</a> for more details.","options","date_time_format",$bboptions['date_time_format']);

	construct_text(2,"Format for user registration date","This is the format in which the user registration date will be shown. You can use time and/or date combined. This works the same as the above two formats. <br /><br />See <a href=\"http://us4.php.net/manual/en/function.date.php\" target=\"_blank\">http://us4.php.net/manual/en/function.date.php</a> for more details.","options","date_register_format",$bboptions['date_register_format']);

	construct_text(1,"Format for user birthday","This is the format for showing a users birthday when their year is specified. <br /><br /> See <a href=\"http://us4.php.net/manual/en/function.date.php\" target=\"_blank\">http://us4.php.net/manual/en/function.date.php</a> for more details.","options","date_birthday_year",$bboptions['date_birthday_year']);

	construct_text(2,"Format for user birthday with no year","This is the format for showing a users birthday when their year is <strong>not</strong> specified. <br /><br /> See <a href=\"http://us4.php.net/manual/en/function.date.php\" target=\"_blank\">http://us4.php.net/manual/en/function.date.php</a> for more details.","options","date_birthday_noyear",$bboptions['date_birthday_noyear'],1);

	construct_footer(2,"option_submit1");
	construct_table_END();



	print "<br /><br />";


	// ##### START "Cookie" ##### \\

	a_name("cookies");

	construct_table("options","options","option_submit");
	construct_header("Cookies",2);

	construct_text(1,"Cookie timeout","Put the cookie timeout here, in which this is the amount of time a user must remain inactive before all posts are marked as read, and how long a user will stay in the \"Who's Online\" after their last activity.","options","cookie_timeout",$bboptions['cookie_timeout']);

	construct_text(2,"Cookie Path","If you are using more than one message board in one domain, it is necessary to specify a different cookie domain where the cookies are saved to.","options","cookie_path",$bboptions['cookie_path']);

	construct_text(1,"Cookie Domain","This is the cookie domain, which is simply the domain of your site. Take notice the prepending dot.","options","cookie_domain",$bboptions['cookie_domain'],1);

	construct_footer(2,"option_submit1");
	construct_table_END();
	

	print "<br /><br />";


	// ##### START "Censors" ##### \\

	a_name("censors");

	construct_table("options","options","option_submit");
	construct_header("Censors",2);

	construct_input(1,"Enable censor?","By enabling this option it will censor every word that you specify below, in posts, personal messages, and other various information.","options","censor_enabled");

	construct_text(2,"Replacement Character","This is the character used to replace all characters in a censored word.","options","censor_replace",$bboptions['censor_replace']);
	
	construct_textarea(1,"Censored Words","Just input a censored word separated by a space. Each word here will be censored.","options","censor_words",$bboptions['censor_words'],1);

	construct_footer(2,"option_submit1");
	construct_table_END();


	print "<br /><br />";


	// ##### START "Email Options" ##### \\

	a_name("email_opt");

	construct_table("options","options","option_submit");
	construct_header("Email Options",2);

	construct_input(1,"Enable email options","Enabling this will allow notification emails to be sent.","options","enable_email");

	construct_input(2,"Enable user email","You can use this to disable all emailing by users, which will override individual user settings.","options","enable_user_email",1);

	construct_footer(2,"option_submit1");
	construct_table_END();


	print "<br /><br />";


	// ##### START "User Registration Options" ##### \\

	a_name("user_reg");

	construct_table("options","options","option_submit");
	construct_header("User Registration Options",2);

	construct_input(2,"Allow new registrations","By disabling this you will disallow anymore members to join the message board.","options","allow_new_registrations");

	construct_select(1,"Use COPPA","Use the COPPA form. It is required by law to get parental consent when collecting information from children under the age of 13. <br /><br /> See <a href=\"http://www.ftc.gov/bcp/conline/pubs/buspubs/coppa.htm\" target=\"_blank\">http://www.ftc.gov/bcp/conline/pubs/buspubs/coppa.htm</a> for more details.","options","use_coppa","Enable COPPA form,Disable COPPA form,Block registrations to users under 13");


	construct_select_begin(2,"Default COPPA Usergroup","Select the usergroup that you wish COPPA user's to be automatically added to after they register.","options","usergroup_coppa_redirect");

		// get all usergroups
		$usergroup_select = mysql_query("SELECT * FROM usergroups ORDER BY name ASC");

		// loop
		while($usergroup = mysql_fetch_array($usergroup_select)) {
			if($usergroup['usergroupid'] == $bboptions['usergroup_coppa_redirect']) {
				$selected_usergroup = " selected=\"selected\"";
			} else {
				$selected_usergroup = "";
			}

			print("<option value=\"".$usergroup['usergroupid']."\"".$selected_usergroup.">".$usergroup['name']."</option>\n");
		}

	construct_select_end(2);


	construct_input(1,"Send welcome email","Enable this option to send a welcome email to each user's registration. If you require users' emails to be verified, the welcome email will not be sent until after the user has activated his/her account.","options","send_welcome_email");

	construct_text(2,"New member email notification","If you want a notification when a new user registers, put an email address here. Leave it blank if you do not want to receive this notification.","options","notify_email_new",$bboptions['notify_email_new']);


	construct_select_begin(1,"Default Usergroup","Select the usergroup that you wish user's to be automatically added to after they register. Or if you require e-mail validation, they would be added after they validate their e-mail, this will not interfere with COPPA users.","options","usergroup_redirect");

		// get all usergroups
		$usergroup_select = mysql_query("SELECT * FROM usergroups ORDER BY name ASC");

		// loop
		while($usergroup = mysql_fetch_array($usergroup_select)) {
			if($usergroup['usergroupid'] == $bboptions['usergroup_redirect']) {
				$selected_usergroup = " selected=\"selected\"";
			} else {
				$selected_usergroup = "";
			}

			print("<option value=\"".$usergroup['usergroupid']."\"".$selected_usergroup.">".$usergroup['name']."</option>\n");
		}

	construct_select_end(1);


	construct_input(2,"Verify email address","Selecting yes will enable the option to verify a user's email address upon registration. An email will be sent to the user with a link to activate his/her account. If this is disabled, email addresses will not be verified.","options","verify_email");

	construct_input(1,"Require unique email address","Enabling this option will disallow any two users to have the same email address. Disabling this option will allow multiple users to have one email address.","options","require_unique_email");

	construct_text(2,"Minimum characters for username","Input here the minimum characters that a username must contain.","options","minimum_username",$bboptions['minimum_username']);

	construct_text(1,"Maximum characters for username","Input here the maximum amount of characters that a username must be under.","options","maximum_username",$bboptions['maximum_username']);

	construct_textarea(2,"Illegal usernames","List illegal usernames that you do not want members to have here, separated by a space.","options","illegal_username",$bboptions['illegal_username'],1);

	construct_footer(2,"option_submit1");
	construct_table_END();


	print "<br /><br />";


	// ##### START "User Profile Options" ##### \\

	a_name("user_prof");

	construct_table("options","options","option_submit");
	construct_header("User Profile Options",2);

	construct_text(1,"Usertitle maximum characters","Input the maximum amount of characters allowed in a user's usertitle here.","options","usertitle_maximum",$bboptions['usertitle_maximum']);

	construct_textarea(2,"Censored usertitle words","Input here a list of all the words you would like to censor from a user's usertitle.","options","usertitle_censored",$bboptions['usertitle_censored']);

	construct_text(1,"Minimum number of posts for user title:","You can input here a minimum number of posts that is required in order to have a custom title. If you enabled the use of a custom title in a usergroup, this will not effect users in that usergroup. If you do not want to allow users to have a custom title after a certain amount of posts, enter <strong>0</strong>. If you enter <strong>0</strong> the field below will can still be used if it is not <strong>0</strong>.","options","customTitle_posts",$bboptions['customTitle_posts']);

	construct_text(2,"Minimum number of days registered for user title:","This is the same as above, except it's the number of days registered. If you input <strong>0</strong> here, only the field above will be used. If you enter <strong>0</strong> in both, this feature will not be enabled.","options","customTitle_days",$bboptions['customTitle_days']);

	construct_input(1,"Number of Posts <em>or</em> number of days registered?","This option can modify the two options above. If you select <strong>yes</strong> here, a user would only need to meet one of the requirements above to use a custom title. If this option is off, a user will have to meet both of the requirements above in order to use a user title.","options","customTitle_or");

	construct_input(1,"Excuse moderators from censor","Enabling this option will excuse moderators from the above censored words.","options","exempt_mods");

	construct_input(2,"Allow signatures","Enabling this option will allow registered users to have signatures.","options","allow_signatures");

	construct_text(1,"Signature maximum characters","Input the maximum amount of character you want allowed in a signature. Enter <strong>0</strong> to disable, and allow unlimited characters.","options","maximum_signature",$bboptions['maximum_signature']);

	construct_input(2,"Allow wtcBB code in signatures","Enabling this option will allow users to use wtcBB code in their signatures.","options","allow_wtcBB_sig");

	construct_input(1,"Allow smilies in signatures","Enabling this option will allow users to use smilies in their signatures.","options","allow_smilies_sig");

	construct_input(2,"Allow [img] code in signatures","Enabling this option will allow users to use the [img] code to insert pictures into their signatures.","options","allow_img_sig");

	construct_input(1,"Allow HTML in signatures","Enabling this option will allow users to use HTML in their signatures. <strong>It is strongly recommended that you keep this option disabled for security issues.</strong>","options","allow_html_sig");

	construct_input(2,"Allow users to change styles","Enabling this option will allow users to change between the different styles that you provide.","options","allow_change_styles",1);

	construct_footer(2,"option_submit1");
	construct_table_END();


	print "<br /><br />";


	// ##### START "Guestbook Config" ##### \\

	a_name("guestbook");

	construct_table("options","options","option_submit");
	construct_header("Guestbook",2);

	construct_input(1,"Enable Guestbook","If this is enabled, each user will have their own guestbook where other users can post comments. If this is disabled, the guestbook system will be completely hidden from view from everyone. You may also use this as a system of notes for each user that is used by the staff. You can configure the permissions of each usergroup so only staff are able to post in it and view it.","options","enableGuestbook");

	construct_input(2,"Guestbook E-mail Notification","If this is enabled, users will receive email notification if someone has posted an entry in their guestbook. You might want to disable this if you were using the guestbook system for your staff to keep user notes. This will override user settings.","options","guestbookNotify");

	construct_text(1,"Guestbook Entries Per Page","Simply enter the number of guestbook entries will be shown per page.","options","guestbookPerPage",$bboptions['guestbookPerPage'],1);

	construct_footer(2,"option_submit1");
	construct_table_END();


	print "<br /><br />";


	// ##### START "Warning System Config" ##### \\

	a_name("warn");

	construct_table("options","options","option_submit");
	construct_header("Warning System Config",2);

	construct_input(1,"Enable Warning System","If this is disabled, the viewing and warning of others' warning levels will be disabled. This will not reset user's warning levels to 0 on a global scale. If you would like to do so, please see the <a href=\"warn.php?do=edit\">Warn Type Manager</a> here in the Admin CP.","options","enableWarn");

	construct_text(2,"Automatic Ban","The warning system doesn't just exist as a label. Here you can set a warning level in which a user will automatically be banned. If this is set to 0, automatic banning will be disabled.","options","warnAutoBan",$bboptions['warnAutoBan']);

	construct_select_begin(1,"Auto Ban Usergroup","Select the usergroup you wish to have members automatically be banned to.","options","autoBanGroup");

		// get all usergroups
		$usergroup_select = mysql_query("SELECT * FROM usergroups ORDER BY name ASC");

		// loop
		while($usergroup = mysql_fetch_array($usergroup_select)) {
			if($usergroup['usergroupid'] == $bboptions['autoBanGroup']) {
				$selected_usergroup = " selected=\"selected\"";
			} else {
				$selected_usergroup = "";
			}

			print("<option value=\"".$usergroup['usergroupid']."\"".$selected_usergroup.">".$usergroup['name']."</option>\n");
		}

	construct_select_end(1);

	construct_input(2,"Send Email Notification","Enabling this option will send an email notification to the user being warned, which will include the reason for warning, and who warned the user (this won't show the note contents).","options","sendWarnNotify",1);

	construct_footer(2,"option_submit1");
	construct_table_END();


	print "<br /><br />";


	// ##### START "Default BB Code Settings" ##### \\

	a_name("user_default");

	construct_table("options","options","option_submit");
	construct_header("Default BB Code Settings",2);

	construct_input(1,"Enable Default BB Code Globally","You can individually allow or disallow this per usergroup, but this serves an option to globally enable or disable this feature. If it's disabled, nobody can use it, even if you allow it in a certain usergroup.","options","defaultBBCode");

	construct_textarea(2,"Default Fonts List","A list of fonts that users can select from when chosing their default font. Each font must be on it's own line.","options","defaultFontsList",$bboptions['defaultFontsList']);

	construct_textarea(1,"Default Colors List","A list of colors that users can select from when chosing their default color. Each color must be on it's own line. This may be a hex code, or a regular color name.","options","defaultColorsList",$bboptions['defaultColorsList']);

	construct_textarea(2,"Default Size List","A list of font sizes that users can select from when chosing their default font size. Each font size must be on it's own line. These font sizes are in the <strong>pt</strong> measurement, meaning they work the same way as font sizes do in Microsoft Word.","options","defaultSizeList",$bboptions['defaultSizeList'],1);

	construct_footer(2,"option_submit1");
	construct_table_END();


	print "<br /><br />";


	// ##### START "Avatar Options" ##### \\

	a_name("avatar");

	construct_table("options","options","option_submit");
	construct_header("Avatar Options",2);

	construct_input(1,"Enable avatars","Enabling this option will allow overall usage of avatars on the message board.","options","avatar_enabled");

	construct_text(2,"Avatar display width","Input the number of columns you wish to display to the user when they are selecting a pre-defined avatar.","options","avartar_display_width",$bboptions['avartar_display_width']);

	construct_text(1,"Avatars per-page","Input the number of avatars you wish to display page to page when a user is selecting a pre-defined avatar.","options","avatars_per_page",$bboptions['avatars_per_page'],1);

	construct_footer(2,"option_submit1");
	construct_table_END();


	print "<br /><br />";


	// ##### START "Memberlist Options" ##### \\

	a_name("memberlist");

	construct_table("options","options","option_submit");
	construct_header("Memberlist Options",2);

	construct_input(1,"Enable memberlist","Enabling this option will allow users to view a memberlist of your message board.","options","memberlist_enabled");

	construct_text(2,"Members per-page","Input the number of members you wish to display per-page.","options","members_per_page",$bboptions['members_per_page'],1);

	construct_footer(2,"option_submit1");
	construct_table_END();


	print "<br /><br />";


	// ##### START "Banning Options" ##### \\

	a_name("banning_opt");

	construct_table("options","options","option_submit");
	construct_header("Banning Options",2);

	construct_input(1,"Enable banning","Enabling this option will enable all banning options.","options","enable_banning");

	construct_textarea(2,"Blocked IP Addresses","List here all the IP addresses you would like to block. Separate them by a comma. You may also put an <strong>IP range</strong> here as well. For example, if you put \"192.168\" as an IP address, all user's IP addresses that start with \"192.168\" will be disallowed access to the message board.","options","blocked_ip",$bboptions['blocked_ip']);

	construct_textarea(1,"Blocked email addresses","List here all the email addresses you wish to ban. Anyone currently using the email address, or anyone who trys to sign up with this email address will be denied access. As above, separate each email address with a comma.","options","blocked_email",$bboptions['blocked_email'],1);

	construct_footer(2,"option_submit1");
	construct_table_END();


	print "<br /><br />";


	// ##### START "Posting Options" ##### \\

	a_name("post_opt");

	construct_table("options","options","option_submit");
	construct_header("Posting Options",2);

	construct_input(1,"Enable quick reply","Enabling this option will allow users to respond to posts faster with a quick reply.","options","enable_quick_reply");

	construct_text(2,"Minimum characters in post","Input here the minimum amount of characters allowed in a post.","options","minimum_chars_post",$bboptions['minimum_chars_post']);

	construct_text(1,"Maximum characters in post","Input here the maximum amount of characters allowed in a post.","options","maximum_chars_post",$bboptions['maximum_chars_post']);

	construct_text(2,"Maximum amount of images","Input here the maximum amount of images allowed per post. This includes smilies.","options","maximum_images",$bboptions['maximum_images']);

	construct_text(1,"Poll Timeout","This is the amount of time, in seconds, that a user has to make a poll after the thread has been submitted. <br />300 Seconds = 5 Minutes","options","poll_timeout",$bboptions['poll_timeout']);

	construct_input(2,"Show edited message?","When this is enabled, a small message at the bottom of a post will be displayed if the post has been edited.","options","show_edit_message");

	construct_text(1,"Edit Timeout","This is the amount of time, in seconds (86400 = 1 day), that the owner of a post has until that owner cannot edit it from the time of posting. Set to <strong>0</strong> to disable.","options","edit_timeout",$bboptions['edit_timeout']);

	construct_select_begin(2,"Log IP Address","If you want to log a user's IP address when he/she posts, select the method you wish to go about doing it.","options","logip",1);

		$items = "Do not log IP address,Log IP and require admin or mod to view,Log IP and display publically";

		$option_select = split(",",$items);

		foreach($option_select as $option_key => $option_value) {
			if($option_key == $bboptions['logip']) {
				$check_select = " selected=\"selected\"";
			} else {
				$check_select = "";
			}

			print("<option value=\"".$option_key."\"".$check_select.">".$option_value."</option>\n");
		}

	construct_select_end(2);

	construct_footer(2,"option_submit1");
	construct_table_END();


	print "<br /><br />";


	// ##### START "Posting Display Options" ##### \\

	a_name("post_display");

	construct_table("options","options","option_submit");
	construct_header("Posting Display Options",2);

	construct_text(1,"Total smilies in smiliebox","Input here the number of total smilies you wish to display in the smiliebox when posting. Set this to <strong>0</strong> to completely hide the smiliebox.","options","clickable_smilies_total",$bboptions['clickable_smilies_total']);

	construct_text(2,"Smilies in each smiliebox row","Input here the number of smilies you wish to display per-row in the smiliebox when posting.","options","clickable_smilies_row",$bboptions['clickable_smilies_row']);

	construct_input(1,"Allow attachments","Enable this to allow users to upload attachments when posting.","options","allow_attachments");

	construct_text(2,"Number of attachments allowed","Input here the number of attachments you wish to allow users to attach on any given post. Set to <strong>0</strong> to not have a limit.","options","attachments_per_post",$bboptions['attachments_per_post']);

	construct_text(1,"Maximum poll options","Input here the maximum amount of poll options you want to limit the user to.","options","maximum_poll_options",$bboptions['maximum_poll_options']);

	construct_input(2,"Enable Toolbar?","If you disable this, nobody will be able to use the toolbar when posting, regardless of their user selection.","options","toolbar");

	construct_input(1,"Show Topic Review","This will show the last 15 posts in the thread that the user is reply to.","options","topicReview",1);

	construct_footer(2,"option_submit1");
	construct_table_END();


	print "<br /><br />";


	// ##### START "Searching Options" ##### \\

	a_name("search_opt");

	construct_table("options","options","option_submit");
	construct_header("Searching Options",2);

	construct_input(1,"Enable search for posts","Enabling this option will allow users to search for posts using a keyword. This can be an intensive process.","options","search_enabled");

	construct_text(2,"Search character minimum","Input here the minimum amount of characters to allow in any given search.","options","search_minimum",$bboptions['search_minimum']);

	construct_text(1,"Search character maximum","Input here the maximum amount of characters to allow in any given search.","options","search_maximum",$bboptions['search_maximum']);

	construct_text(2,"Search results per-page","Input here the number of results to show per page.","options","num_of_search_page",$bboptions['num_of_search_page']);

	construct_text(1,"Maximum search results","Any search results over this number will be discarded. Set this to 0 to disable a limit on a search.","options","maximum_search_results",$bboptions['maximum_search_results'],1);

	construct_footer(2,"option_submit1");
	construct_table_END();


	print "<br /><br />";


	// ##### START "Forums Homepage Settings" ##### \\

	a_name("forum_home");

	construct_table("options","options","option_submit");
	construct_header("Forums Homepage Settings",2);

	/* Reserved for later use?
	construct_select_begin(1,"Forum Stats Level","Select a level for the forum stats. The higher the level, the more resourceful it can become.","options","forumStatsLevel");
			$stats0 = "";
			$stats1 = "";
			$stats2 = "";
			
			if($bboptions['forumStatsLevel'] == 0) {
				$stats0 = ' selected="selected"';
			} else if($bboptions['forumStatsLevel'] == 2) {
				$stats2 = ' selected="selected"';
			} else {
				$stats1 = ' selected="selected"';
			}

			print("<option value=\"0\"".$stats0.">No Stats</option>\n");
			print("<option value=\"1\"".$stats1.">Lite Stats</option>\n");
			print("<option value=\"2\"".$stats2.">Heavy Stats</option>\n");

	construct_select_end(1); */

	construct_input(1,"Show forum stats?","Disabling this will not show the forum stats on the home page.","options","forumStatsLevel");

	construct_input(2,"Enable logged in users","This will display all logged in users who were active in the last <strong>X</strong> (where X is the cookie timeout specified earlier in these options). It will be displayed on the homepage.","options","display_loggedin_users");

	construct_input(1,"Enable birthdays","Enabling this option will list all users whose birthday is today on the forums homepage.","options","display_birthdays",1);

	construct_footer(2,"option_submit1");
	construct_table_END();


	print "<br /><br />";


	// ##### START "Forums Settings" ##### \\

	a_name("forum_settings");

	construct_table("options","options","option_submit");
	construct_header("Forums Settings",2);

	construct_text(1,"Depth of forums on the forums homepage","This is the depth the forums will go to. For example, if you set this value to <strong>2</strong>, it will show the current forum, and any child forums beneath it. Setting this too high may cause peformance issues depending on the amount of forums you have. If you set this option above 2, you will manually need to add the appropriate templates.","options","depth_forums",$bboptions['depth_forums']);

	construct_text(2,"Depth of forums not on homepage","This is the same as above, except it applies to all other areas where forums are displayed, other than the homepage.","options","other_depth_forums",$bboptions['other_depth_forums']);

	construct_input(1,"Show sub-forums","Enable this option to list any direct sub-forums not shown on the forums homepage.","options","show_subforums");

	construct_input(2,"Show sub-forums not on homepage","This is the same as above, except it applies to all other areas where forums are displayed, other than the homepage.","options","other_show_subforums");

	construct_input(1,"Show forum descriptions","Enable this option to show your inputted description for each forum.","options","show_forum_descriptions");

	construct_input(2,"Show forum descriptions not on homepage","This is the same as above, except it applies to all other areas where forums are displayed, other than the homepage.","options","other_show_forum_descriptions");

	construct_input(1,"Hide private forums","Enable this option to hide forums to users without access to them. If you disable this option, users will be able to see forums they don't have access too, but they won't be able to read any of the threads.","options","hide_private");

	construct_input(2,"Hide private forums not on homepage","This is the same as above, except it applies to all other areas where forums are displayed, other than the homepage.","options","other_hide_private");

	construct_input(1,"Show moderator column","Enable this option to show the moderator column on the forums homepage.","options","show_mod_column");

	construct_input(2,"Show moderator column not on homepage","This is the same as above, except it applies to all other areas where forums are displayed, other than the homepage.","options","other_show_mod_column",1);

	construct_footer(2,"option_submit1");
	construct_table_END();


	print "<br /><br />";


	// ##### START "In-Forum Settings" ##### \\

	a_name("in_forum_settings");

	construct_table("options","options","option_submit");
	construct_header("In-Forum Settings",2);

	construct_input(1,"Show users browsing each forum","Enable this option to show the users browsing the current forum.","options","show_users_browsing_forum");

	construct_text(2,"Threads per-page","Input here the default amount of threads you want to be displayed per-page.","options","maximum_threads",$bboptions['maximum_threads']);

	construct_input(1,"Show all announcements?","If this is enabled, all announcements will be shown in the thread list. If it is disabled, only one, the most recent, will be shown in the thread list. However, when you click on it, all announcements for that forum and global announcements will be displayed.","options","show_all_announcements");

	construct_input(2,"Show stickys on all pages","Enable this option to put stickys on all pages, instead of just the first.","options","show_sticky_all");

	construct_text(1,"Number of views to qualify as \"Hot Thread\"","Input here the number of views a thread needs to qualify as hot.","options","hot_views",$bboptions['hot_views']);

	construct_text(2,"Number of replies to qualify as a \"Hot Thread\"","Input here the number of replies a thread needs to qualitfy as hot.","options","hot_replies",$bboptions['hot_replies']);

	construct_input(1,"Threadlist page links?","Enable this option to show links to the different pages in the thread list.","options","multi_thread_links");

	construct_text(2,"Threadlist maximum page links","Input here the maximum amount of links to link to, if the above option is set to yes.","options","multi_thread_max_links",$bboptions['multi_thread_max_links']);

	construct_text(1,"Maximum characters for thread preview","Input here the maximum amount of characters to be inserted into the \"title\" attribute of a thread title to preview it. Don't set this too high for performance reasons.","options","thread_preview_max",$bboptions['thread_preview_max']);

	construct_text(2,"Prefix For Sticky Threads","This is the word that will come before thread names that are stuck. You may use HTML.","options","pre_sticky",$bboptions['pre_sticky']);

	construct_text(1,"Prefix For Closed Threads","This is the word that will come before thread names that are closed. You may use HTML.","options","pre_closed",$bboptions['pre_closed']);

	construct_text(2,"Prefix For Moved Threads","This is the word that will come before thread names that have been moved to a different forum. You may use HTML.","options","pre_moved",$bboptions['pre_moved']);

	construct_text(1,"Prefix For Threads with Polls","This is the word that will come before thread names that have a poll. You may use HTML.","options","pre_poll",$bboptions['pre_poll'],1);

	construct_footer(2,"option_submit1");
	construct_table_END();


	print "<br /><br />";


	// ##### START "Thread Settings" ##### \\

	a_name("thread_settings");

	construct_table("options","options","option_submit");
	construct_header("Thread Settings",2);

	construct_input(1,"Show users browsing each thread","Enable this option to show the users browsing the current thread. This could definitely have an effect on performance if their are many simultaneous users.","options","show_users_browsing_thread");

	construct_select_begin(2,"Default amount of posts per-page","Input here the default amount of posts per-page.","options","max_posts");

		// split the user settable posts per page...
		$option_select = split(",",$bboptions['user_set_max_posts']);

		sort($option_select);
		reset($option_select);

		foreach($option_select as $option_key => $option_value) {
			if($option_value == $bboptions['max_posts']) {
				$check_select = " selected=\"selected\"";
			} else {
				$check_select = "";
			}

			print("<option value=\"".$option_value."\"".$check_select.">".$option_value."</option>\n");

			// let's also construct a sorted list for the user settable posts per page below.. killing two birds with one stone...
			if($option_key == 0) {
				$sorted_settable = $option_value;
			} else {
				$sorted_settable .= ",".$option_value;
			}
		}

	construct_select_end(2);

	construct_text(1,"User settable posts per-page","Input here the user settable amounts to set as posts per-page. Separate each by a comma. These will appear in the user control panel where users can select their own preference.","options","user_set_max_posts",$sorted_settable);

	construct_input(2,"Check thread subscriptions","If this is enabled, subscribed threads will have a small icon next to them.","options","check_thread_subscribe",1);

	construct_footer(2,"option_submit1");
	construct_table_END();


	print "<br /><br />";


	// ##### START "Personal Message Options" ##### \\

	a_name("personal_opt");

	construct_table("options","options","option_submit");
	construct_header("Personal Message Options",2);

	construct_input(1,"Enable personal messaging","This turns the whole entire personal messaging system on and off.","options","personal_enabled");

	construct_input(2,"Enable checking for new messages","This will check for new personal messages in a users inbox everytime the user loads a page. If there is a new message, a visual representation will show them so if they have enabled that feature.","options","personal_check"); 

	construct_text(1,"Maximum characters for personal message","Input here the maximum amount of characters allowed in a personal message.","options","personal_max_chars",$bboptions['personal_max_chars']);

	construct_text(2,"Personal messages per-page","Input here the number of personal messages you wish to display per-page.","options","personal_messages_per_page",$bboptions['personal_messages_per_page']);

	construct_input(1,"Allow wtcBB code in personal messages","Enabling this option will allow users to use wtcBB code in their personal messages.","options","allow_wtcBB_personal");

	construct_input(2,"Allow smilies in personal messages","Enabling this option will allow users to use smilies in their personal messages.","options","allow_smilies_personal");

	construct_input(1,"Allow [img] code in personal messages","Enabling this option will allow users to use the [img] code to insert pictures into their personal messages.","options","allow_img_personal");

	construct_input(2,"Allow HTML in personal messages","Enabling this option will allow users to use HTML in their personal messages. <strong>It is strongly recommended that you keep this option disabled for security reasons.</strong>","options","allow_html_personal",1);

	construct_footer(2,"option_submit1");
	construct_table_END();


	print "<br /><br />";


	// ##### START "Who's Online Settings" ##### \\

	a_name("online_settings");

	construct_table("options","options","option_submit");
	construct_header("Who's Online Settings",2);

	construct_input(1,"Enable Who's Online","Enabling this option will show a more detailed location of where each user is, and what the user is doing.","options","online_enabled");

	construct_text(2,"Number of seconds until the page refreshes","In seconds, input the time in which the Who's Online page should refresh.","options","online_refresh",$bboptions['online_refresh']);

	construct_input(1,"Show guests","Enabling this option will show guests, which are unregistered users, in the Who's Online.","options","online_guest");

	construct_input(2,"Resolve IP Addresses","Enabling this option with resolve all IP addresses on the who's online for users who have access to view IP Addresses.","options","online_resolveIP");

	construct_textarea(1,"Robot Detection","You may enter in more robots to detect. You must have each robot on its own line.","options","robots",$bboptions['robots']);

	construct_textarea(2,"Robot Detection Description","For each of the above robots you may put in a short description for each that will be displayed if the robot is detected. Remember, the descriptions <strong>must</strong> be in the same order as the robot detection input above.","options","robots_desc",$bboptions['robots_desc'],1);

	construct_footer(2,"option_submit1");
	construct_table_END(1);

	// do footer
	admin_footer();

}

?>