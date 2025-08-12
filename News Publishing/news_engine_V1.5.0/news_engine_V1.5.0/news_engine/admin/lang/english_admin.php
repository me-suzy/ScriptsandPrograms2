<?php
/*
+--------------------------------------------------------------------------
|   Alex News Engine
|   ========================================
|   by Alex Höntschel
|   (c) 2002 AlexScriptEngine
|   http://www.alexscriptengine.de
|   ========================================
|   Web: http://www.alexscriptengine.de
|   Email: info@alexscriptengine.de
+---------------------------------------------------------------------------
|
|   > Beschreibung
|   > englische Sprachdatei für's AdminCenter
|
|	> Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-
|	> bedingungen (read-me.html) für weitere Informationen.
|	>-----------------------------------------------------------------
|	> This script is NOT freeware! Please read the Copyright Notice
|	> (read-me_eng.html) for further information. 
|
|	> $Id: english_admin.php 22 2005-10-15 12:38:44Z alex $
|
+--------------------------------------------------------------------------
*/

$a_lang['info1'] = array("Memberlist", "All members, who have registrated adequat, will be named in the list. In doing so, activated an non- activated users will be named.<br><br>Administrators can only be deleated, when there is more than one administrator existing.");
$a_lang['info2'] = array("Create Members", "Users which are created here, have to be informed about the correlative informations (username, password) before creating them.<br><br>
                            There will be no automatic notification to the users.  It is necessary, to name a proper e- mail adress. Accounts which are created here, don't have to be activated seperatly by confirming an e- mail.<br><br>
                            Much attention should be used, when the particular belonging group has to be selected.<br><br>
                            Users, who are created as administrators, can enter the AdminCenter and are allowed to do te same operations like you.");
$a_lang['info3'] = array("Update Informations", "The number of your version, will be adjusted with the update- server. If there is a newer version than the used one, this will be displayed.<br><br>Because of security reasons, the newest version should always be used. Furthermore there is  no support for older versions.");
$a_lang['info4'] = array("Email", "Here it is possible, to send text- mails to an optional number of users.<br><br>
                            Subject and text must only be entered into the contemplated fields. Than, the registered users which should receive the mail, can be choosen in the part below.<br><br>
                            With pushed Shift- resp. pushed Strg- Key, it is possible to choose several users simultaneously.");	
$a_lang['info5'] = array("Data Base Backup", "It is possible, to bild a <b>backup of the database</b>. In doing so, it is possible to choose all charts, or to select only a few ones for the backup.<br><br>
                            The backup files will then be dropped into a directory and can be downloaded if this is wished. We recommend to safe the database in constant periods, so that it is possible to restore it,  if it's necessary.<br><br>
                            Using the option <br>'only structure', only the configuration of the database, not the information,will be safed. If this data file is restored, probably existing inputs will be deleted.<br><br>
                            <b>Included the inputs, which are essential for the running of the engine.</b><br><br>
                            By clicking the option <b>'restore file'</b>, already made cutouts will be transferred into the database.");	    
$a_lang['info6'] = array("Usergroups Overview","All defined usergroups are listed here.<br><br>As a standard, the groups 'administrator', 'guest' and 'member' are placed. They could be changed, but <b>not deleted</b>.<br><br>The number of the additional groups ist not restricted and can be extended by need.");    
$a_lang['info7'] = array("Create Usergroups","Here you can create own usergroups for the user- section and define, who is allowed to enter the admin center, and who is not.<br><br>Users, who have the permission for the AdminCenter, can change all settings and edit inputs, as well as those may be not existing in the user- section. <b>This option should be used very carefull. The administrator rights should only be given to familiar people.</b>");
$a_lang['info8'] = array("Delete Members","Deleted members can not be reactivated. The erased accounts will be taken from the database and won't be availlable any more.<br><br>Restoring is not possible.");
$a_lang['info9'] = array("User Activation","In this list users who have registered, but <b>haven't</b> confirmed the registration mail, are named.<br><br>It is the administrators decision, to activate or delete the accounts. In this list inputs are only possible, when the option for the registration by confirming- mail is activated.<br><br>By clicking the checkbox at the beginning of each line, all marked users can be deleted or activated.");
$a_lang['info10'] = array("Main Settings","These settings are the fundament for the whole engine. The settings should all be filled with correlative declarations.<br><br>The named <b>Urls</b> have to be correct, because perhaps files can't be uploaded by formular or grafics can't be displayed.<br><br>
                            The <b>registration-options</b> determine, wether the engine should work as a community - platform or not.<br><br>The <b>Gzip- option</b> causes problems on some servers. If problems appear in the display of the user- section (for example empty pages), the configuration has to be changed.<br><br>
                            Configurations belonging <b>date and time</b> arrange the the accurate design, when the server is placed in a different time zone (for example a site for german users, which runs on a server in the USA).<br><br>The <b>standard registration group</b> controls, which group the new user is sorted to, after his registration. Wether if the engine runs by oneself or together with another one, no change is necessary. This should be changed, if for example a forum is connected.<br><br>
                            To simplfy the work with the templates for the user section, the tempalte- names can be added to the source code with the option <b>add template names</b>. The particular page can be called then. In the source code (for example IE - right mouse, show source code) the particular template name is shown as a comment.<br><br>Furthermore it is important, that each configuration is logical and fits to each other. In theory it is possible to choose absurd configurations , which might reduce the efficiency of the engine.");		
$a_lang['info11'] = array("Colour Setup","The given colours will later be used in the script.<br><br>
                            If new colours are safed, the CSS-file should be created new, so that the colours will be applicated in the right way and will be displayed correct on the page. If the place holders for the colours ( all files in cambered anchors {}) in the templates are replaced with usual colour settings (f.E. #000000), the changing of colours in the formular won't have consequences for this CSS-section.<br><br>
                            Other or own CSS-files can be changed and entered at discretion.<br><br>To safe changes in the CSS-classes, or the at the colours inside the CSS-classes, it is recommended to create the CSS-file new.");		        
$a_lang['info12'] = array("Switch Engine online/offline","If the engine is offline, only persons who have the requested group rights,can enter it.  This function is useful when you want to make updates and something similiar");        
$a_lang['info13'] = array("Edit Templates","Templates, which are  responsible for the desgin of the user- section, can be prepared with the editor below.<br><br>
                            It must be considerd, that <b>all</b> template files an the folder template are equiped with CHMOD0777. Otherwise the script can't save the changed template.<br><br>
                            Clicking the button <b>preview</b> , a preview of the template will be shown in a new window. Because of missing CSS-informations, the colours may probably be displayed wrong.");		
$a_lang['info14'] = array("Im Verzeichnis gefundene Bilder in die Datenbank eintragen","Neu gefundene Bilder k&ouml;nnen hier den jeweiligen Kategorien zugeordnet werden. Die detaillierten Angaben m&uuml;ssen jedoch im nachhinein <b>pro Bild</b> einzeln erg&auml;nzt werden");							                            

// Datei index.php ok
$a_lang['index_er1'] = "You have to fill in all fields.<br>";
$a_lang['index_er2'] = "Wrong Password ! <br>";
$a_lang['index_er3'] = "You don't have permission to access these Area !";
$a_lang['index_head'] = "Login Admin Area";
$a_lang['index_login'] = "Login";
$a_lang['index_pw'] = "Password";

// Datei head.php
$a_lang['head_logged_in_as'] = "&raquo; Logged in as %s";
$a_lang['head_logout'] = "Logout";

// Datei main.php ok
$a_lang['main_welcome'] = "Welcome";
$a_lang['main_head'] = "Welcome to the NewsEngine Admin Area";
$a_lang['main_stat'] = "Statistics";
$a_lang['main_reguser'] = "Registered User";
$a_lang['main_avnews'] = "No. of News";
$a_lang['main_comoverall'] = "Total Comments";
$a_lang['main_confirm'] = "Waiting for Confirmation";
$a_lang['main_newnews'] = "new News";
$a_lang['main_newcom'] = "new Comments";
$a_lang['main_imthings'] = "Useful Things";
$a_lang['main_installed'] = "Installed Version";
$a_lang['main_installer'] = "IMPORTANT: Installer-File  (installer.php / beta.php) already exists!";
$a_lang['main_notactive'] = "Number of users waiting for activation";
$a_lang['main_activate'] = "activate";
$a_lang['main_databasesize'] = "Size database";

// Datei navi.php ok
$a_lang['navi_cat1'] = "Menu";
$a_lang['navi_p1'] = "AdminCenter Start";
$a_lang['navi_p2'] = "NewsEngine Home";
$a_lang['navi_cat2'] = "Categories";
$a_lang['navi_p3'] = "Add new Category";
$a_lang['navi_p4'] = "Edit Categories";
$a_lang['navi_cat3'] = "News";
$a_lang['navi_p5'] = "Add new News";
$a_lang['navi_p6'] = "Edit News";
$a_lang['navi_p7'] = "Search for News";
$a_lang['navi_cat4'] = "Users";
$a_lang['navi_p8'] = "Add new User";
$a_lang['navi_p9'] = "Edit Users";
$a_lang['navi_p10'] = "Search for Users";
$a_lang['navi_cat5'] = "Avatars";
$a_lang['navi_p11'] = "Add Avatar";
$a_lang['navi_p12'] = "Edit Avatars";
$a_lang['navi_cat6'] = "Language / Templates";
$a_lang['navi_p13'] = "Language";
$a_lang['navi_p14'] = "Edit Templates";
$a_lang['navi_cat7'] = "Configuration";
$a_lang['navi_p15'] = "Engine Options";
$a_lang['navi_p16'] = "Colorsettings";
$a_lang['navi_p17'] = "News Options";
$a_lang['navi_p18'] = "Engine online/offline";
$a_lang['navi_cat8'] = "Usergroups";
$a_lang['navi_p19'] = "Edit Usergroups";
$a_lang['navi_p20'] = "Add Usergroup";
$a_lang['navi_p23'] = "Send Email";
$a_lang['navi_p24'] = "Database Update";
$a_lang['navi_p25'] = "Update Check";
$a_lang['navi_cat9'] = "Newsletter";
$a_lang['navi_p26'] = "Send Newsletter";
$a_lang['navi_p27'] = "Mailinglist";
$a_lang['navi_p28'] = "Url and Pathinfo";
$a_lang['navi_cat10'] = "Style";
$a_lang['navi_p29'] = "Add new Style";
$a_lang['navi_p30'] = "Edit Style";
$a_lang['navi_cat11'] = "Info &amp; Maintenance";

// Datei avatar.php ok
$a_lang['avatar_mes1'] = "Avatar successfully added";
$a_lang['avatar_mes2'] = "Avatar successfully edited";
$a_lang['avatar_mes3'] = "Avatar deleted - ID";
$a_lang['avatar_del1'] = "Do you really want to delete the chosen Avatar";
$a_lang['avatar_del2'] = "irrevocable?";
$a_lang['avatar_yes'] = "Yes";
$a_lang['avatar_here'] = "here";
$a_lang['avatar_mainp'] = ", to go back to the Mainpage";
$a_lang['avatar_new'] = "Add new Avatar";

// Datei bbhelp.php
$a_lang['bbhelp_1'] = "BBCode Guide";
$a_lang['bbhelp_2'] = "Information about BBCodes";
$a_lang['bbhelp_3'] = "What is BBCode?";
$a_lang['bbhelp_4'] = "BBCode is a special implementation of HTML. BBCode itself is similar in style to HTML, tags are enclosed in square braces [ and ] rather than < and > and it offers greater control over what and how something is displayed. Depending on the template you are using you may find adding BBCode to your news is made much easier through a clickable interface above the message area on the posting form. Even with this you may find the following guide useful.";
$a_lang['bbhelp_5'] = "BBCode";
$a_lang['bbhelp_6'] = "Displaying on screen";
$a_lang['bbhelp_7'] = "some text";
$a_lang['bbhelp_8'] = "italic text";
$a_lang['bbhelp_9'] = "underlined text";
$a_lang['bbhelp_10'] = "Hyperlinks";
$a_lang['bbhelp_11'] = "Email";
$a_lang['bbhelp_12'] = "add source code";
$a_lang['bbhelp_13'] = "a quotation";

// Datei newscat.php ok
$a_lang['newscat_mes1'] = "Category successfully added";
$a_lang['newscat_mes2'] = "Category deleted";
$a_lang['newscat_mes3'] = "Category successfully edited";
$a_lang['newscat_del1'] = "Do you really want to delete the chosen Category";
$a_lang['newscat_del2'] = "irrevocable?";
$a_lang['newscat_yes'] = "Yes";
$a_lang['newscat_not'] = "If not, click";
$a_lang['newscat_here'] = "here";
$a_lang['newscat_mainp'] = ", to go back to the Mainpage.";
$a_lang['newscat_name'] = "Name (ID)";
$a_lang['newscat_picture_name'] = "Picture-Name";
$a_lang['newscat_options'] = "Options";

// Datei newsletter.php
$a_lang['newsletter_1'] = "Subscriber deleted";
$a_lang['newsletter_2'] = "Subscriber added";
$a_lang['newsletter_3'] = "Subscriber data stored";
$a_lang['newsletter_4'] = "<br>Error reported at user %s ";
$a_lang['newsletter_5'] = "Totally %s Newsletter sended %s";
$a_lang['newsletter_6'] = "Send Newsletter";
$a_lang['newsletter_7'] = "Newsletter data<br><span class=\"smalltext\">The newsletter will use the templates newsletter_html.html and newsletter_txt.txt located in the directory 'templates'. The following placeholders can be used within the newsletter template and within the newsletter:<br>
    {abouser} = Receipients name<br>{abomail} = Receipients email address<br>{abostart} = Data when the user subscribed the newsletter<br>{stoplink} = Link to stop the newsletter<br>{disclaimer} = Disclaimer</span>";
$a_lang['newsletter_8'] = "<b>Subject</b>";
$a_lang['newsletter_9'] = "<b>Salutation</b><br><span class=\"smalltext\">The users name will be added automatically; Placeholder is not necessary</span>";
$a_lang['newsletter_10'] = "<b>Newsletter Text</b><br><span class=\"smalltext\">Edit the text here. Use normal &lt;img&gt; Tags with the full URL to the image (in HTML mode) to include images. The image must be located on your server.</span>";
$a_lang['newsletter_11'] = "Send Newsletter";
$a_lang['newsletter_12'] = "Reset";
$a_lang['newsletter_13'] = "Send Newsletter";
$a_lang['newsletter_14'] = "Newsletter Configuration";
$a_lang['newsletter_14b'] = "Important, the newsletter is currently deactivated. Activate this before you start to send newsletters.";
$a_lang['newsletter_15'] = "Assign Newsletter data";
$a_lang['newsletter_16'] = "HTML - Newsletter";
$a_lang['newsletter_17'] = "only Text - Newsletter";
$a_lang['newsletter_18'] = "<b>Kind of the Newsletter</b><br><span class=\"smalltext\">Within HTML-Newsletters you can use all HTML-Tags.</span>";
$a_lang['newsletter_19'] = "alle Categories";
$a_lang['newsletter_20'] = "<b>Category</b><br><span class=\"smalltext\">If you have chossen a category news from this will be used only. Multiple selection possible (STRG+)</span>";
$a_lang['newsletter_21'] = "<b>Startdate</b>";
$a_lang['newsletter_22'] = "<b>Enddate</b>";
$a_lang['newsletter_23'] = "Write Newsletter";
$a_lang['newsletter_24'] = "Edit Mailingliste";
$a_lang['newsletter_25'] = "Newsletter subscribers";
$a_lang['newsletter_26'] = "write Email";
$a_lang['newsletter_27'] = "Add Subscriber";
$a_lang['newsletter_28'] = "edit Subscriber";
$a_lang['newsletter_29'] = "Subscriber data";
$a_lang['newsletter_30'] = "<b>Name</b><br><span class=\"smalltext\">The Tag {abouser} within the newsletter template will be replaced with this</span>";
$a_lang['newsletter_31'] = "<b>Email-Address</b>";
$a_lang['newsletter_32'] = "Save Data";
$a_lang['newsletter_33'] = "Back";
$a_lang['newsletter_34'] = "Do you really want to delete the Subscriber (ID: %s) irrevocable";

// Datei comment.php ok
$a_lang['comment_mes1'] = "The Comment is confirmed and published";
$a_lang['comment_mes2'] = "The Comment is deleted";
$a_lang['comment_det'] = "Commentdetails";
$a_lang['comment_main'] = "General Commentdatas";
$a_lang['comment_news'] = "to the News-Article";
$a_lang['comment_categ'] = "in Category";
$a_lang['comment_written'] = "written by";
$a_lang['comment_at'] = "at";
$a_lang['comment_headcomment'] = "Headline and Comment";
$a_lang['comment_headline'] = "Headline";
$a_lang['comment_close'] = "Click here to close the window";

// Datei language.php ok
$a_lang['language_nopacks'] = "No Language-Packs available";
$a_lang['language_done'] = "Language changed.";
$a_lang['language_nothing'] = "Es wurde kein Einstelltyp gewählt. Bitte wähle links aus der Navigation die gewünschte Option aus.";
$a_lang['language_head'] = "Choose Language";
$a_lang['language_choose'] = "<b>Choose a Language-Pack</b>";
$a_lang['language_exist'] = "Available Language-Packs";
$a_lang['language_current'] = "Currently chosen Language-Pack";
$a_lang['language_button'] = "Use Language";

// Datei member.php ok
$a_lang['member_mes1a'] = "The Login";
$a_lang['member_mes1b'] = "is already in use. Please try again.<br>";
$a_lang['member_mes2'] = "Please insert your Email-Adress<br>";
$a_lang['member_mes3'] = "Please insert a valid Email-Adress<br>";
$a_lang['member_mes4'] = "Member successfully added";
$a_lang['member_mes5'] = "Member deleted";
$a_lang['member_mes6'] = "Member successfully edited";
$a_lang['member_u_search'] = "Search by username";
$a_lang['member_insert'] = "Please insert the Username here";
$a_lang['member_search'] = "Search";
$a_lang['member_del1'] = "Do you really want to delete the User";
$a_lang['member_del2'] = "?";
$a_lang['member_not1'] = "If not, click";
$a_lang['member_here'] = "here";
$a_lang['member_not2'] = ", to go back to the Mainpage.";
$a_lang['member_infos'] = "Information about Group-permissions";
$a_lang['member_group'] = "Groupname";
$a_lang['member_q1'] = "Can access Admin-Area";
$a_lang['member_q2'] = "Can post news (if News-Engine is installed)";
$a_lang['member_q3'] = "Can edit comments";
$a_lang['member_q4'] = "Can delete comments";
$a_lang['member_q5'] = "Can post comments";
$a_lang['member_admin'] = "Administrator";
$a_lang['member_coadmin'] = "Co-Administrator";
$a_lang['member_newsposter'] = "News-Poster";
$a_lang['member_supermod'] = "Super-Moderator";
$a_lang['member_mod'] = "Moderator";
$a_lang['member_member'] = "Member";
$a_lang['member_guest'] = "Guest";
$a_lang['member_choosen'] = "if chosen";
$a_lang['member_desc'] = "all other not listed Groups are only for program internal usage";
$a_lang['member_close'] = "Click here, to close the window";
$a_lang['member_yes'] = "Yes";
$a_lang['member_del_success'] = "Marked users deleted";
$a_lang['member_active_success'] = "Marked users activated";
$a_lang['member_activation'] = "User waiting for activation";
$a_lang['member_actname'] = "Username";
$a_lang['member_actmail'] = "Email";
$a_lang['member_actsince'] = "Registered since";
$a_lang['member_actyes'] = "Activate";
$a_lang['member_actdel'] = "Delete user";
$a_lang['member_avatars'] = "Avatars";
$a_lang['member_use_avatar'] = "Use Avatar";
$a_lang['member_available_avatars'] = "Available Avatars";
$a_lang['member_choose_avatar'] = "Choose Avatar";

// Datei news.php ok
$a_lang['news_mes1'] = "Link successfully edited";
$a_lang['news_mes2'] = "News successfully edited";
$a_lang['news_mes3'] = "News deleted";
$a_lang['news_mes4'] = "News successfully added to the database";
$a_lang['news_mes5'] = "News successfully confirmed and published";
$a_lang['news_mes6'] = "Link deleted";
$a_lang['news_mes7'] = "Link successfully added to the database";
$a_lang['news_mes8'] = "An error occured. Please try again.";
$a_lang['news_click'] = "Click";
$a_lang['news_addlink'] = "to add a link";
$a_lang['news_addmorelinks'] = "to add another link";
$a_lang['news_search_f'] = "Search by Headline";
$a_lang['news_inserthead'] = "Insert the Headline here";
$a_lang['news_search'] = "Search";
$a_lang['news_del1'] = "Do you really want to delete the News";
$a_lang['news_del2'] = "irrevocable?";
$a_lang['news_yes'] = "Yes";
$a_lang['news_not1'] = "If not, click";
$a_lang['news_here'] = "here";
$a_lang['news_not2'] = ", to go back to the Mainpage.";
$a_lang['search_define'] = "<b>Define search</b>";
$a_lang['search_button1'] = "Search";
$a_lang['search_note1'] = "<b>Enter Searchstring</b><br><span class=\"smalltext\">Enter the search string</span>";	
$a_lang['prog_no_result'] = "Note";
$a_lang['prog_no_result1'] = "No results found for";
$a_lang['prog_no_result2'] = ". Please try again.";
$a_lang['search_in_headline'] = "in the headline";
$a_lang['search_in_newstext'] = "in the newstext";
$a_lang['pic_right_of_news'] = "Pic right of the news";
$a_lang['pic_left_of_news'] = "Pic left of the news";
$a_lang['pic_in_front_of_news'] = "Pic in front of the newsheadline";
$a_lang['news_in_category'] = "in %s";
$a_lang['news_really_delete_link'] = "Do you really want to delete the Link-ID %s?";
$a_lang['news_pictures'] = "Pictures";
$a_lang['news_size'] = "Size:";
$a_lang['news_insert_image'] = "Insert Image";
$a_lang['news_delete_image'] = "Delete Image";
$a_lang['news_delete_image_not_possible'] = "Not possible to delete this image!";
$a_lang['news_pic_success_deleted'] = "Image successfully removed";
$a_lang['news_pic_not_deleted'] = "Not possible to remove Image";
$a_lang['news_do_you_really_want_delete'] = "Do you really want to delete this image?";

// Datei settings.php ok
$a_lang['settings_mes1'] = "Color Settings successfully changed";
$a_lang['settings_mes2'] = "Main Settings successfully changed";
$a_lang['settings_mes3'] = "News Settings successfully changed";
$a_lang['settings_onoff'] = "Status of the engine successfully changed";
$a_lang['settings_mes4'] = "CSS-Template successfully written";
$a_lang['settings_mes5'] = "CSS-Template not saved!!!";
$a_lang['settings_mes6'] = "CSS-File successfully saved";
$a_lang['settings_mes7'] = "CSS-File not saved!!!";
$a_lang['settings_css1'] = "Create CSS-File";
$a_lang['settings_css2'] = "<b>CSS-File:</b><br><span class=\"smalltext\">If you save a new CSS-File, the old file will be overwritten. If you changed the file dlengine.css manually, all changes will be deleted. The CSS-Template will be created new with your changes.<br><br>Following placeholder can be used within the file and will be replaced if you save the file:<br>{fontf} = Font<br>{maincol} = Maincolor<br>{primcol} = Second Color<br>{backcol} = Background Color<br>{bordercol} = Bordercolor<br>{textcol1} =Textcolor1<br>{textcol2} = Textcolor2<br>{hovercol} = Textcolor3<br>{postcol1} = Comment-Color 1<br>{postcol2} = Comment-Color 2</span>";
$a_lang['settings_css3'] = "Save CSS-File";
$a_lang['settings_1'] = "Time and Date Settings";
$a_lang['settings_2'] = "hours";
$a_lang['settings_3'] = "<b>Timezoneoffset</b><br><span class=\"smalltext\">Time (in hours) that the server is offset from GMT</span>";
$a_lang['settings_4'] = "<b>Short Dateformat:</b><br><span class=\"smalltext\">See PHP-Function <a target=\"_blank\" href=\"http://www.php.net/date\">date</a></span>";
$a_lang['settings_5'] = "<b>Long Dateformat:</b><br><span class=\"smalltext\">See PHP-Function <a target=\"_blank\" href=\"http://www.php.net/date\">date</a></span>";
$a_lang['settings_6'] = "<b>Timeformat:</b><br><span class=\"smalltext\">See PHP-Function <a target=\"_blank\" href=\"http://www.php.net/date\">date</a></span>";
$a_lang['settings_7'] = "<b>Use SMTP-Server</b><br><span class=\"smalltext\">Mostly used at Windows IIS Server</span>";    
$a_lang['settings_8'] = "<b>SMTP-Server address</b><br><span class=\"smalltext\">Only needed if you use SMTP otherwise leave this blank</span>";
$a_lang['settings_9'] = "<b>SMTP Username</b><br><span class=\"smalltext\">Only needed if you use SMTP otherwise leave this blank</span>";
$a_lang['settings_10'] = "<b>SMTP Passwort</b><br><span class=\"smalltext\">Only needed if you use SMTP otherwise leave this blank</span>";
$a_lang['settings_11'] = "<b>Activate Slideshow</b><br><span class=\"smalltext\">Activate one slideshow for every category. The slideshow only works, if a new size of thumbnails was created, please choose the menu point 'Create thumbnails' to create the needed pictures - otherwise it is not possible to see the slideshow for the whole category.<br><i>Be aware, to use this option you need the GD library installed.</i></span>";
$a_lang['wysiwyg_settings'] = "WYSIWYG Settings";
$a_lang['wysiwyg_editor_in_userarea'] = "<b>User WYSIWYG-Editor</b><br><span class=\"smalltext\">For all newsposters will the standard BBCode-Editor replaced by the WYSIWYG-Editor.<br><b>PLEASE NOTE: SECURITY RISK</b>. User can post HTML and Javascript Code. This can damage your website!</span>";
$a_lang['wysiwyg_editor_in_admincenter'] = "<b>Admin WYSIWYG-Editor</b><br><span class=\"smalltext\">The standard BBCode-Editor in the AdminCenter will be replaced by the WYSIWYG-Editor. It is not possible to edit a News-Item with the BBCode-Editor if it was started with the WYSIWYG-Editor.<br><b>PLEASE NOTE: SECURITY RISK</b>. User can post HTML and Javascript Code. This can damage your website!</span>";
$a_lang['path_and_url_external_use'] = "Path and Url's for external use";
$a_lang['path'] = "Path";
$a_lang['path_to_the_engine'] = "<b>Path to the Engine</b><br><span class=\"smalltext\">Important to use with newsinfo.php</span>";
$a_lang['url_to_display_category'] = "<b>%s</b><br><span class=\"smalltext\">Display only category %s</span>";
$a_lang['archive_splitting'] = "<b>Archive-Splitting</b><br><span class=\"smalltext\">Split the view within the archive by month or by year/month</span>";
$a_lang['by_month_year'] = "by month/year";
$a_lang['by_year'] = "by year";
$a_lang['alternating_news_color'] = "<b>Alternating News Color</b><br><span class=\"smalltext\">If yes the list-colors will be used, otherwise the second color will be used</span>";
$a_lang['mail_a_friend'] = "<b>News-Recommendadtion</b><br><span class=\"smalltext\">Activates a link to recommend the news by mail</span>";
$a_lang['display_categorie_names'] = "<b>Display Category Names</b><br><span class=\"smalltext\">If yes the category names will be displayed in front of the newsheadline.</span>";
$a_lang['settings_newsdisplay'] = "Settings Newsdisplay";
$a_lang['date_n_timesettings_news'] = "<b>News Date and Time Options</b><br><span class=\"smalltext\">Will change the date and time settings for the newsoverview</span>";
$a_lang['settings_newsdisplay_date'] = "Date";
$a_lang['settings_newsdisplay_date_time'] = "Date - Time";
$a_lang['settings_newsdisplay_day_date'] = "Day, Date";
$a_lang['settings_newsdisplay_day_date-time'] = "Day, Date - Time";
$a_lang['settings_category_start_tags'] = "<b>Category Start-Tags</b><br><span class=\"smalltext\">These HTML-Tags will be used in front of the category name, if 'Display Category Name' is enabled</span>";
$a_lang['settings_category_end_tags'] = "<b>Category End-Tags</b><br><span class=\"smalltext\">These HTML-Tags will be used after the category name, if 'Display Category Name' is enabled</span>";

// Datei style.php
$a_lang['no_template_folder_found'] = "No style found in subfolder /templates";
$a_lang['style_successfully_created'] = "Style successfully added<br>";
$a_lang['style_changed'] = "Style edited<br>";
$a_lang['style_changed2'] = "Style edited";
$a_lang['edit_style_sets'] = "Edit Style-Set";
$a_lang['available_styles'] = "Available Styles";
$a_lang['style_set_in_use'] = "Style-Set in use";
$a_lang['style_edit'] = "edit";
$a_lang['style_delete'] = "delete";
$a_lang['use_style_set'] = "Use Style";
$a_lang['no_style_set_available'] = "No Style-Set available";
$a_lang['add_style_set'] = "New Style-Set";
$a_lang['body_data'] = "General Data<br><span class=\"smalltext\">Data for the whole page or the whole Style-Set.</span>";
$a_lang['style_set_name'] = "<b>Name of the Style-Set</b>";
$a_lang['style_templat_folder_name'] = "<b>Template-Folder</b>";
$a_lang['body_font_face'] = "<b>Font-Face</b>";
$a_lang['body_font_color'] = "<b>Font-Color</b>";
$a_lang['body_font_size'] = "<b>Font-Size</b>";
$a_lang['background_color'] = "<b>Background-Color</b>";
$a_lang['border_color'] = "<b>Border-Color</b>";
$a_lang['design_row_top'] = "Top Line<br><span class=\"smalltext\">Line with Welcome-Message, MemberList-Link and other Links</span>";
$a_lang['breadcrumb_row'] = "Breadcrumb Line";
$a_lang['font_color_mouseover'] = "<b>Font-Color Mouseover</b>";
$a_lang['design_main_area'] = "Main Part<br><span class=\"smalltext\">Compl. Content Area</span>";
$a_lang['alternating_bg_color1'] = "<b>Background-Color 1</b><br><span class=\"smalltext\">e. g. Comments, Search results etc.; all lists with alternating Colours</span>";
$a_lang['alternating_bg_color2'] = "<b>Background-Color 2</b><br><span class=\"smalltext\">e. g. Comments, Search results etc.; all lists with alternating Colours</span>";
$a_lang['design_row_bottom'] = "Line Bottom<br><span class=\"smalltext\">Contains e. g. Quick-Search</span>";
$a_lang['background_highlighted_area'] = "<b>Background-Color for highlighted Area</b><br><span class=\"smalltext\">e. g. list headlines</span>";
$a_lang['font_color_highlighted_area'] = "<b>Font-Color for highlighted Area</b>";
$a_lang['font_color_hover_highlighted_area'] = "<b>Font-Color Mouseover for highlighted Area</b>";
$a_lang['edit_css_file_directly'] = "Edit CSS-File directly";
$a_lang['css_description'] = "<b>CSS-Data</b><br><span class=\"smalltext\">If you save the Style-Set, the placeholders will be replaced by the colors. You can also change the CSS information directly in the text.<br /><br />If you add a new CSS-File copy and paste the CSS-information into this textarea.</span>";
$a_lang['save_style_set'] = "Save Style-Set";
$a_lang['reset_style_set'] = "Reset";
$a_lang['delete_style_set'] = "Delete Style-Set:";
$a_lang['confirm_delete_style_set'] = "Do you really want to delete these Style-Set irrevocable?";
$a_lang['style_set_deleted'] = "Style-Set deleted";
$a_lang['style_del_not_possible'] = "It is not possible to delete the active Style-Set";

// Datei templates.php ok
$a_lang['templates_mes1'] = "Template saved";
$a_lang['templates_mes2'] = "An Error has occured. Please try again";
$a_lang['templates_mes3'] = "No Template found!";
$a_lang['templates_nochoosen'] = "Es wurde kein Einstelltyp gewählt. Bitte wähle links aus der Navigation die gewünschte Option aus.";
$a_lang['templates_info'] = "Within the Templates you can use standard HTML-Tags. Tags with leading \$ and instructions in curved clips {} are variables which will be replaced by the script";
$a_lang['templates_edittpl'] = "Edit Templates";
$a_lang['templates_existtpl'] = "Edit existing Templates";
$a_lang['templates_choosetpl'] = "<b>Choose Template</b>";
$a_lang['templates_loadtpl'] = "Load Template";
$a_lang['templates_htmltpl'] = "Template Source code";
$a_lang['templates_savetpl'] = "Save Template";

// Datei uploads.php ok
$a_lang['uploads_url'] = "Url to the Download";
$a_lang['uploads_dat_cat'] = "Filename of the Category-/Newspicture";
$a_lang['uploads_dat_av'] = "Filename of the Avatars";
$a_lang['uploads_extens'] = "File extension invalid, Upload not possible";
$a_lang['uploads_size'] = "File to big. Max Filesize";
$a_lang['uploads_success'] = "Upload successfully!";
$a_lang['uploads_copy'] = "File upload was not possible";
$a_lang['uploads_stillexist'] = "Filename already exist, Upload was not possible!";
$a_lang['uploads_fileupload'] = "Fileupload";
$a_lang['uploads_categupload'] = "Upload Category-/Newspicture";
$a_lang['uploads_avatupload'] = "Avatarupload";
$a_lang['uploads_new'] = "File/Pic Upload";
$a_lang['uploads_note1'] = "Please note, it is only possible to Upload a max Filesize of";
$a_lang['uploads_note2'] = "Bytes (or max Filesize of Serversettings). If you want to upload bigger Files, please use your FTP-Program.";
$a_lang['uploads_search'] = "<b>Search by</b>";
$a_lang['uploads_upload'] = "Upload";
$a_lang['uploads_reset'] = "Back";
$a_lang['uploads_close'] = "Click here to close the window";
$a_lang['uploads_ok1'] = "File-Upload successfully !";
$a_lang['uploads_ok2'] = "Transfer the Name into the field";
$a_lang['uploads_ok3'] = "Data transfered, Fieldname";
$a_lang['uploads_ok4'] = "Transfer the data into the field <u>Filesize in Bytes</u>";
$a_lang['uploads_ok5'] = "Transfer the data into the field Size of the Download in Bytes";
$a_lang['uploads_changename'] = "<b>If the file-name already exist, do you want to change it automatically?</b>";
$a_lang['uploads_nopermission'] = "You can not write into this folder, no writing permission!";
$a_lang['uploads_ok6'] = "Transfer File";
$a_lang['uploads_ok7'] = "Click the following link to transfer the file (Filename: ";
$a_lang['uploads_ok8'] = ") into the form field. This window will be closed when you transfer the information.";
$a_lang['uploads_message'] = "Search the file at your own computer";
$a_lang['uploads_h1'] = "Picture";
$a_lang['uploads_button1'] = "Send file";

// Datei groups.php
$a_lang['groups_1'] = "Group successfully added";
$a_lang['groups_2'] = "Group deleted";
$a_lang['groups_3'] = "Group successfully edited";
$a_lang['groups_4'] = "Choose a group to edit";
$a_lang['groups_5'] = "Following groups found";
$a_lang['groups_6'] = "Delete group";
$a_lang['groups_7'] = "Edit permissions";
$a_lang['groups_8'] = "Edit group permissions";
$a_lang['groups_9'] = "Generall Permissions";
$a_lang['groups_10'] = "<b>Title</b>";
$a_lang['groups_11'] = "<b>Can access AdminCenter</b>";
$a_lang['groups_12'] = "<b>Can access Engine during offline</b>";
$a_lang['groups_13'] = "<b>Can use Engine search</b>";
$a_lang['groups_14'] = "<b>Can modify own profile</b>";
$a_lang['groups_15'] = "<b>Can see Members</b>";
$a_lang['groups_16'] = "<b>Can write comments:</b><br><span class=\"smalltext\">The displayed name can be chosen within the generall settings</span>";
$a_lang['groups_17'] = "Moderator Permissions";
$a_lang['groups_18'] = "<b>Can edit comments</b>";
$a_lang['groups_19'] = "<b>Can delete comments</b>";
$a_lang['groups_20'] = "Enginespecific permissions";
$a_lang['groups_21'] = "<b>Can write News</b><br><span class=\"smalltext\">Allows the user to upload images for the news to the server</span>";
$a_lang['groups_22'] = "Can see Top-List:<br><span class=\"smalltext\">The Toplist must be activated within the page settings</span>";
$a_lang['groups_23'] = "Can use advanced stats:<br><span class=\"smalltext\">The advanced stats must be activated within the page settings</span>";
$a_lang['groups_24'] = "Can download files which are marked for registered members";
$a_lang['groups_25'] = "Delete user-group";
$a_lang['groups_26'] = "Do you really want to delete this group irrevocable?<br><span class=\"smalltext\">All user will be moved to standard registered members</span>";
$a_lang['groups_28'] = "Create new group";

// Datei adminutil.php
$a_lang['adminutil_1'] = "Can not load this file!";
$a_lang['adminutil_2'] = "Restore data";
$a_lang['adminutil_3'] = "Download";
$a_lang['adminutil_4'] = "Delete";
$a_lang['adminutil_5'] = "No filename!";
$a_lang['adminutil_6'] = "Backup done!";
$a_lang['adminutil_7'] = "Can not add data!";
$a_lang['adminutil_8'] = "File deleted";
$a_lang['adminutil_9'] = "Can not delete file";
$a_lang['adminutil_10'] = "Can not send mail!<br>";
$a_lang['adminutil_11'] = "Mail Error";
$a_lang['adminutil_12'] = "Mail sended!";
$a_lang['adminutil_13'] = "Send email to user";
$a_lang['adminutil_14'] = "Please add your text and choose a user";
$a_lang['adminutil_15'] = "<b>Email-Subject</b>";
$a_lang['adminutil_16'] = "<b>Email-Text</b>";
$a_lang['adminutil_17'] = "<b>Choose Receipient</b>";
$a_lang['adminutil_18'] = "Send Mail";
$a_lang['adminutil_19'] = "Reset";
$a_lang['adminutil_20'] = "Please choose a table";
$a_lang['adminutil_21'] = "Backupfile restored";
$a_lang['adminutil_22'] = "An error occured during restore process!";
$a_lang['adminutil_23'] = "Database Backup done";
$a_lang['adminutil_24'] = "Backup Settings";
$a_lang['adminutil_25'] = "Select all";
$a_lang['adminutil_26'] = "Deselect all";
$a_lang['adminutil_27'] = "<b>Choose tables</b><br><span class=\"smalltext\">You can choose one or more tables. The Backupfile will be saved in the folder backup.</span>";
$a_lang['adminutil_28'] = "<b>Structure only</b><br><span class=\"smalltext\">If yes the database content will not be saved, only the structure of the tables!</span>";
$a_lang['adminutil_29'] = "Do Backup";
$a_lang['adminutil_30'] = "Available Backups";
$a_lang['adminutil_31'] = "Delete Backupfile";
$a_lang['adminutil_32'] = "Delete";
$a_lang['adminutil_33'] = "Do you really want to delete the file?";
$a_lang['adminutil_34'] = "Update-Check";
$a_lang['adminutil_35'] = "No newer version available. The version";
$a_lang['adminutil_36'] = "is the current version.";
$a_lang['adminutil_37'] = "Database Size";

// Datei adminfunc.inc.php
$a_lang['afunc_1'] = "ID";
$a_lang['afunc_2'] = "News-Headline/Category";
$a_lang['afunc_3'] = "Title";
$a_lang['afunc_4'] = "Author / Date";
$a_lang['afunc_5'] = "Options";
$a_lang['afunc_6'] = "GUEST";
$a_lang['afunc_7'] = "Confirm";
$a_lang['afunc_8'] = "Delete";
$a_lang['afunc_9'] = "Details";
$a_lang['afunc_10'] = "Introtext";
$a_lang['afunc_11'] = "confirm News";
$a_lang['afunc_12'] = "delete News";
$a_lang['afunc_13'] = "edit News";
$a_lang['afunc_14'] = "Headline";
$a_lang['afunc_15'] = "to the News";
$a_lang['afunc_16'] = "Category";
$a_lang['afunc_17'] = "Hits / Rating";
$a_lang['afunc_18'] = "<b>no</b> Rating";
$a_lang['afunc_19'] = "Votes";
$a_lang['afunc_20'] = "no comment";
$a_lang['afunc_21'] = "Comments";
$a_lang['afunc_22'] = "<b>Background Color:</b><br><span class=\"smalltext\">Backgroundcolor for all Pages</span>";
$a_lang['afunc_23'] = "";
$a_lang['afunc_24'] = "Email / HP";
$a_lang['afunc_25'] = "Choose a Category";
$a_lang['afunc_26'] = "Following Categories are available";
$a_lang['afunc_27'] = "";
$a_lang['afunc_28'] = "edit";
$a_lang['afunc_29'] = "delete";
$a_lang['afunc_30'] = "";
$a_lang['afunc_31'] = "Add a new Category";
$a_lang['afunc_32'] = "Data to the new Category";
$a_lang['afunc_33'] = "<b>Category-Name</b>";
$a_lang['afunc_34'] = "<b>Category-Picture</b><br><span class=\"smalltext\">(do not use the complete url, e. g. &quot;pic.gif&quot;)";
$a_lang['afunc_35'] = "Upload - Category-Picture";
$a_lang['afunc_36'] = "Add Category";
$a_lang['afunc_37'] = "Back";
$a_lang['afunc_38'] = "None (new Maincategory)";
$a_lang['afunc_39'] = "Edit Category";
$a_lang['afunc_40'] = "Data to the selected category";
$a_lang['afunc_41'] = "Category Name";
$a_lang['afunc_42'] = "Choose a Picture (optional)<br><span class=\"smalltext\">(do not use the complete url, e. g. &quot;pic.gif&quot;)";
$a_lang['afunc_43'] = "";
$a_lang['afunc_44'] = "Change Category";
$a_lang['afunc_45'] = "Colorsettings and Screenoptions";
$a_lang['afunc_46'] = "General Colorsettings";
$a_lang['afunc_47'] = "<b>Maincolor:</b><br><span class=\"smalltext\">Background-Color of the Headlines</span>";
$a_lang['afunc_48'] = "<b>Second Color:</b><br><span class=\"smalltext\">Color of the Downloads and Categories</span>";
$a_lang['afunc_49'] = "<b>Bordercolor</b>";
$a_lang['afunc_50'] = "<b>List-Color 1</b><br><span class=\"smalltext\">e. g. Comments, Search-Results etc.; all Lists with alternating colors</span>";
$a_lang['afunc_51'] = "<b>List-Color 2</b><br><span class=\"smalltext\">e. g. Comments, Search-Results etc.; all Lists with alternating colors</span>";
$a_lang['afunc_52'] = "Textcolor";
$a_lang['afunc_53'] = "<b>Textcolor1:</b><br><span class=\"smalltext\">Textcolor within Categories and Files</span>";
$a_lang['afunc_54'] = "<b>Textcolor2:</b><br><span class=\"smalltext\">for Headlines</span>";
$a_lang['afunc_55'] = "<b>Textcolor3:</b><br><span class=\"smalltext\">for Higlighted Text</span>";
$a_lang['afunc_56'] = "<b>Font</b>";
$a_lang['afunc_57'] = "Save Settings";
$a_lang['afunc_58'] = "Newssettings";
$a_lang['afunc_59'] = "Global settings for the News";
$a_lang['afunc_60'] = "<b>No. of News displayed on Mainpage</b>";
$a_lang['afunc_61'] = "Yes";
$a_lang['afunc_62'] = "No";
$a_lang['afunc_63'] = "<b>Do you want to display a Picture besides the News</b><br><span class=\"smalltext\">if there is no Picture for the News available the Picture for the category will be displayed</span>";
$a_lang['afunc_64'] = "Settings for external Newsposter functionality";
$a_lang['afunc_65'] = "<b>Do you want to publish articles written by a Newsposter immediately?</b><br><span class=\"smalltext\">No, if every article must be confirmed by an administrator</span>";
$a_lang['afunc_66'] = "<b>Are the Newsposter allowed to use Click-Smilies?</b><br><span class=\"smalltext\">Using Click-Smilies with the WYSIWYG Editor is not possible</span>";
$a_lang['afunc_67'] = "Headlines, Categorysearch";
$a_lang['afunc_68'] = "<b>Do you want to display the Headlines at the top of the Mainpage?</b>";
$a_lang['afunc_69'] = "<b>If you want to display the Headlines, how many?</b>";
$a_lang['afunc_70'] = "<b>Display the Categorybox?</b><br><span class=\"smalltext\">To select the News by Category</span>";
$a_lang['afunc_71'] = "Archiv-Settings";
$a_lang['afunc_72'] = "<b>Should the overview by month sorted ascended or descended?</b>";
$a_lang['afunc_73'] = "ascended";
$a_lang['afunc_74'] = "descended";
$a_lang['afunc_75'] = "Back";
$a_lang['afunc_76'] = "";
$a_lang['afunc_77'] = "";
$a_lang['afunc_78'] = "";
$a_lang['afunc_79'] = "";
$a_lang['afunc_80'] = "";
$a_lang['afunc_81'] = "";
$a_lang['afunc_82'] = "";
$a_lang['afunc_83'] = "";
$a_lang['afunc_84'] = "Mainsettings";
$a_lang['afunc_85'] = "Url's for the NewsEngine";
$a_lang['afunc_86'] = "<b>Url to your Homepage</b><br><span class=\"smalltext\">Url to your homepage, used in the breadcrumbs</span>";
$a_lang['afunc_87'] = "<b>Mainurl to the Script</b>";
$a_lang['afunc_88'] = "<b>Url to the Smilies</b>";
$a_lang['afunc_89'] = "<b>Url to the Graphics</b>";
$a_lang['afunc_90'] = "<b>Url to the Avatars</b>";
$a_lang['afunc_91'] = "<b>Url to the Category-Pictures</b><br><span class=\"smalltext\">Folder under your Engine root dir; Complete Url with http://</span>";
$a_lang['afunc_92'] = "<b>Add template name?</b><br><span class=\"smalltext\">you can see the name of the template within the html-source as a html-comment</span>";
$a_lang['afunc_93'] = "Name, Email-Adress and Width";
$a_lang['afunc_94'] = "<b>Name of your News-Engine</b>";
$a_lang['afunc_95'] = "<b>Mail-Adress of the Admin:</b><br><span class=\"smalltext\">Shown in all Mails which were send by the script</font>";
$a_lang['afunc_96'] = "<b>Width for all Pages:</b><br><span class=\"smalltext\">absolut or relativ</span>";
$a_lang['afunc_97'] = "Comment-Setup";
$a_lang['afunc_98'] = "<b>Mailinformation if a new Comment is posted?</b>";
$a_lang['afunc_99'] = "<b>Can Guests post Comments?</b>";
$a_lang['afunc_100'] = "Guestposting not allowed";
$a_lang['afunc_101'] = "allowed, with name Guest";
$a_lang['afunc_102'] = "allowed";
$a_lang['afunc_103'] = "<b>Confirm Comments before publish?</b><br><span class=\"smalltext\">see Mainpage AdminCenter</span>";
$a_lang['afunc_104'] = "Settings for compression";
$a_lang['afunc_105'] = "<b>Activate GZIP?</b><br><span class=\"smalltext\">This feature requires the ZLIB-Library (if not known please ask your hoster). It reduces the bandwith requirement. This will be only used on clients that support it, and are HTTP 1.1 compliant.</span>";
$a_lang['afunc_106'] = "<b>GZIP compression level</b><br><span class=\"smalltext\">Set the level of GZIP compression . 0=none; 9=max.</span>";
$a_lang['afunc_107'] = "More Settings";
$a_lang['afunc_108'] = "<b>Can guests see all Details of a registered Member?</b>";
$a_lang['afunc_109'] = "<b>Activate Registration?</b>";
$a_lang['afunc_110'] = "<b>Activate Login?</b><br><span class=\"smalltext\">Yes makes only sense with activated Registration</span>";
$a_lang['afunc_111'] = "Please choose a Category";
$a_lang['afunc_112'] = "Following Categories are available";
$a_lang['afunc_113'] = "Delete or Edit news from these category";
$a_lang['afunc_114'] = "Following news found:";
$a_lang['afunc_115'] = "Comments not allowed";
$a_lang['afunc_116'] = "ACTIVE";
$a_lang['afunc_117'] = "no Links available";
$a_lang['afunc_118'] = "Links available";
$a_lang['afunc_119'] = "Published";
$a_lang['afunc_120'] = "News activated and published";
$a_lang['afunc_121'] = "News not published";
$a_lang['afunc_122'] = "must be enabled";
$a_lang['afunc_123'] = "";
$a_lang['afunc_124'] = "";
$a_lang['afunc_125'] = "dtd.";
$a_lang['afunc_126'] = "use picture from the category";
$a_lang['afunc_127'] = "use own picture for these article";
$a_lang['afunc_128'] = "don't use a picture";
$a_lang['afunc_129'] = "active comments";
$a_lang['afunc_130'] = "Edit News";
$a_lang['afunc_131'] = "Edit Links";
$a_lang['afunc_132'] = "Delete News";
$a_lang['afunc_133'] = "Edit News, Healine";
$a_lang['afunc_134'] = "Headline, Category, Newspicture";
$a_lang['afunc_135'] = "<b>Headline</b>";
$a_lang['afunc_136'] = "<b>Category</b>";
$a_lang['afunc_137'] = "Location of the picture";
$a_lang['afunc_138'] = "<b>Newspicture:</b><br><span class=\"smalltext\">Please insert the filename if you want to use an own picture (not URL!) <a href=\"JavaScript:Uploadimage()\"><img src=\"images/upload.gif\" alt=\"Upload - Newspicture\" width=\"16\" height=\"16\" border=\"0\" align=\"middle\"></a></span>";
$a_lang['afunc_139'] = "Intro- und Maintext";
$a_lang['afunc_140'] = "Introtext<br><span class=\"smalltext\">displayed at the Mainpage, BBCode allowed, HTML-Tags not allowed</span>";
$a_lang['afunc_141'] = "Maintext <a href=\"JavaScript:Helpfile()\" class=\"smalltext\">[Help]</a><br><span class=\"smalltext\">(will be display under 'read more')<br>insert a new line with the enter-key";
$a_lang['afunc_142'] = "Comments, Publishing";
$a_lang['afunc_143'] = "<b>Published?</b>";
$a_lang['afunc_144'] = "<b>Comments allowed?</b>";
$a_lang['afunc_145'] = "<b>Link Setup</b>";
$a_lang['afunc_146'] = "<b>Display links at the mainpage?</b>";
$a_lang['afunc_147'] = "Following news are available";
$a_lang['afunc_148'] = "active comments";
$a_lang['afunc_149'] = "Comments not allowed";
$a_lang['afunc_150'] = "no Links available";
$a_lang['afunc_151'] = "Links available";
$a_lang['afunc_152'] = "Published";
$a_lang['afunc_153'] = "News activated and published";
$a_lang['afunc_154'] = "must be enabled";
$a_lang['afunc_155'] = "News not published";
$a_lang['afunc_156'] = "Edit News";
$a_lang['afunc_157'] = "Edit Links";
$a_lang['afunc_158'] = "Delete News";
$a_lang['afunc_159'] = "No news found with the headline";
$a_lang['afunc_160'] = "Email of the Authors";
$a_lang['afunc_161'] = "more Options to the File";
$a_lang['afunc_162'] = "Upload the File for these Download to the Server";
$a_lang['afunc_163'] = "Upload a Thumbnail for these Download to the Server";
$a_lang['afunc_164'] = "Following Downloads are located";
$a_lang['afunc_165'] = "No File located with the given search item. Search Item";
$a_lang['afunc_166'] = "Please choose the wished Member";
$a_lang['afunc_167'] = "Following Members are located";
$a_lang['afunc_168'] = "Username";
$a_lang['afunc_169'] = "<b>Email</b>";
$a_lang['afunc_170'] = "Group";
$a_lang['afunc_171'] = "active<br>Comments";
$a_lang['afunc_172'] = "blocked?";
$a_lang['afunc_173'] = "Options";
$a_lang['afunc_174'] = "deletion impossible";
$a_lang['afunc_175'] = "delete";
$a_lang['afunc_176'] = "edit";
$a_lang['afunc_177'] = "No member found. Search Item";
$a_lang['afunc_178'] = "Edit User-Data";
$a_lang['afunc_179'] = "Data of";
$a_lang['afunc_180'] = "<b>Username</b>";
$a_lang['afunc_181'] = "<b>Registered since</b>";
$a_lang['afunc_182'] = "<b>last visit</b>";
$a_lang['afunc_183'] = "<b>Homepage</b>";
$a_lang['afunc_184'] = "<b>Group</b>";
$a_lang['afunc_185'] = "Information about Group-permissions";
$a_lang['afunc_186'] = "<b>AvatarID</b>";
$a_lang['afunc_187'] = "<b>Always show the Email Address?</b>";
$a_lang['afunc_188'] = "<b>Is the user blocked</b>";
$a_lang['afunc_189'] = "<b>Can upload Files?</b>";
$a_lang['afunc_190'] = "Change Userdata";
$a_lang['afunc_191'] = "Add new user";
$a_lang['afunc_192'] = "Please insert the new data";
$a_lang['afunc_193'] = "<b>Password</b>";
$a_lang['afunc_194'] = "Change Avatardata.";
$a_lang['afunc_195'] = "Following Avatars are available";
$a_lang['afunc_196'] = "change";
$a_lang['afunc_197'] = "delete";
$a_lang['afunc_198'] = "Please insert a new Name for the Avatar";
$a_lang['afunc_199'] = "Change Avatar";
$a_lang['afunc_200'] = "Change Data";
$a_lang['afunc_201'] = "Show a list with all available Avatars ";
$a_lang['afunc_202'] = "Edit the links for";
$a_lang['afunc_203'] = "";
$a_lang['afunc_204'] = "located links";
$a_lang['afunc_205'] = "external";
$a_lang['afunc_206'] = "internal";
$a_lang['afunc_207'] = "Link";
$a_lang['afunc_208'] = "edit";
$a_lang['afunc_209'] = "delete";
$a_lang['afunc_210'] = "Add a new link";
$a_lang['afunc_211'] = "Edit Link";
$a_lang['afunc_212'] = "Editable link";
$a_lang['afunc_213'] = "<b>Link description</b>";
$a_lang['afunc_214'] = "<b>Link URL</b>";
$a_lang['afunc_215'] = "<b>Link Target</b>";
$a_lang['afunc_216'] = "Link to the NewsID";
$a_lang['afunc_217'] = "insert new linkdata's";
$a_lang['afunc_218'] = "Save Link";
$a_lang['afunc_219'] = "New Newspost";
$a_lang['afunc_220'] = "Headline, Catgory, Newspicture";
$a_lang['afunc_221'] = "<b>Headline</b>";
$a_lang['afunc_222'] = "<b>Category</b>";
$a_lang['afunc_223'] = "<b>Location of the picture</b>";
$a_lang['afunc_224'] = "use picture from the category";
$a_lang['afunc_225'] = "use own picture for these article";
$a_lang['afunc_226'] = "don't use a picture";
$a_lang['afunc_227'] = "<b>Newspicture:</b><br><span class=\"smalltext\">Please insert the filename if you want to use an own picture (not URL!)</span>";
$a_lang['afunc_228'] = "Upload - Newspicture";
$a_lang['afunc_229'] = "Introtext and Maintext";
$a_lang['afunc_230'] = "Click-Smilies";
$a_lang['afunc_231'] = "<b>Links to the News</b>";
$a_lang['afunc_232'] = "After posting the news you will get a confirmation and a new link where you can post links for this news";
$a_lang['afunc_233'] = "Post News";
$a_lang['afunc_234'] = "Back";
$a_lang['afunc_235'] = "small";
$a_lang['afunc_236'] = "medium";
$a_lang['afunc_237'] = "big";
$a_lang['afunc_238'] = "very big";
$a_lang['afunc_239'] = "sup";
$a_lang['afunc_240'] = "sub";
$a_lang['afunc_241'] = "superscript";
$a_lang['afunc_242'] = "subscript";
$a_lang['afunc_243'] = "Add Hyperlink";
$a_lang['afunc_244'] = "Add Email address";
$a_lang['afunc_245'] = "Add source-code";
$a_lang['afunc_246'] = "Line";
$a_lang['afunc_247'] = "List";
$a_lang['afunc_248'] = "Add list";
$a_lang['afunc_249'] = "Quote";
$a_lang['afunc_250'] = "Add quote";
$a_lang['afunc_251'] = "Add Image";
$a_lang['afunc_252'] = "close current tag";
$a_lang['afunc_253'] = "close all tags";
$a_lang['afunc_254'] = "Use buttons to format the text";
$a_lang['afunc_255'] = "standard Mode";
$a_lang['afunc_256'] = "advanced Mode";
$a_lang['afunc_257'] = "<b>Max. length of comments:</b>";
$a_lang['afunc_258'] = "_us";
$a_lang['afunc_259'] = "Delete";
$a_lang['afunc_260'] = "News Date:<br><span class=\"smalltext\">Format yyyy-mm-dd hh:mm:ss<br>leave blank for today</span>";
$a_lang['afunc_280'] = "<b>Verify Email address in registration</b><br><span class=\"smalltext\">The activation-code is emailed to the new member after they submit their registration to confirm their identity and email address</span>";
$a_lang['afunc_282'] = "Switch Engine online/offline";
$a_lang['afunc_283'] = "Status and reasoning";
$a_lang['afunc_284'] = "<b>Is your Engine offline or online</b>";
$a_lang['afunc_285'] = "Engine online";
$a_lang['afunc_286'] = "Engine offline";
$a_lang['afunc_287'] = "<b>Reason for offline status:</b><br><span class=\"smalltext\">The users will see this when they try to enter the engine. It works only if both settings are right. That means that you have to choose offline and give a reason to set it offline.</span>";
$a_lang['afunc_288'] = "<b>Startdate</b>";
$a_lang['afunc_289'] = "Timesettings<br><span class=\"smalltext\">The startdate is the date when the news will be published. The Enddate is the date when the news will be expired. If you set an enddate this newspost will only be displayed within the news-archiv. Format <b>yyyy-mm-dd hh:mm:ss</b></span>";
$a_lang['afunc_290'] = "<b>Enddate</b>";
$a_lang['afunc_291'] = "Page";
$a_lang['afunc_292'] = "edit";
$a_lang['afunc_293'] = "delete";
$a_lang['afunc_294'] = "Page";
$a_lang['afunc_314'] = "<b>Standardusergroup:</b><br><span class=\"smalltext\">This group will be used if a new member registers. For standard case you can use ID 7. If you combine the Engines with the WBB Board you should use the ID 3, because this is the standard usergroup of the WBB.</span>";
$a_lang['afunc_315'] = "No new comments available";
$a_lang['afunc_316'] = "No new newspost available";
$a_lang['afunc_318'] = "Create new user";
$a_lang['afunc_319'] = "Upload file/Browser";
$a_lang['afunc_320'] = "go to the category";
$a_lang['post_news_js1'] = "Enter the text you want to format:";
$a_lang['post_news_js2'] = "Enter the text to be displayed for the link (optional)";
$a_lang['post_news_js3'] = "Enter the full URL for the link";
$a_lang['post_news_js4'] = "Enter the email address for the link";
$a_lang['disclaimer'] = "You received this message because your email address is in our mailing list.
If that happened against your will or if you don't want to receive further newsletters, 
click at the following link to have your email address be removed from our mailing list:";
$a_lang['afunc_321'] = "<b>Acivate RSS Newsfeed</b><br><span class=\"smalltext\">News from this category will be available in the RSS Newsfeed</span>";
$a_lang['afunc_322'] = "<b>Provide RSS Newsfeed</b><br><span class=\"smalltext\">If you have activated this your visitors can use the file rss.php to display your news on their own website. RSS is a XML based format</span>";
$a_lang['afunc_323'] = "<b>Activate Newsletter</b><br><span class=\"smalltext\">Will display a small box, which allows user to subscribe or unsubscribe your newsletter.</span>";
$a_lang['afunc_proceed'] = "Proceed";
$lang['php_fu_day_0'] = "Sunday";
$lang['php_fu_day_1'] = "Monday";
$lang['php_fu_day_2'] = "Tuesday";
$lang['php_fu_day_3'] = "Wednesday";
$lang['php_fu_day_4'] = "Thursday";
$lang['php_fu_day_5'] = "Friday";
$lang['php_fu_day_6'] = "Saturday";
$lang['php_fu_month_1'] = "January";
$lang['php_fu_month_2'] = "February";
$lang['php_fu_month_3'] = "March";
$lang['php_fu_month_4'] = "April";
$lang['php_fu_month_5'] = "May";
$lang['php_fu_month_6'] = "June";
$lang['php_fu_month_7'] = "July";
$lang['php_fu_month_8'] = "August";
$lang['php_fu_month_9'] = "September";
$lang['php_fu_month_10'] = "October";
$lang['php_fu_month_11'] = "November";
$lang['php_fu_month_12'] = "Dezember";
$lang['php_mailer_lang'] = "en";
$lang['php_mailer_error'] = "Mailerror: ";
?>
