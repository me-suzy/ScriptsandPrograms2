<?php
// jp.inc.php, Japanese version written in EUC-JP.
// Translation by Mitsuhiro Yoshida <mits@mitstek.com> - mitstek.com(http://mitstek.com/)

$chars = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
$name_month = array("", "1·î", "2·î", "3·î", "4·î", "5·î", "6·î", "7·î", "8·î", "9·î", "10·î", "11·î", "12·î");
$l_text31a = array("½é´üÃÍ", "15Ê¬", "30Ê¬", "1»þ´Ö", "2»þ´Ö", "4»þ´Ö", "1Æü");
$l_text31b = array(0, 15, 30, 60, 120, 240, 1440);
$name_day = array("ÆüÍË", "·îÍË", "²ÐÍË", "¿åÍË", "ÌÚÍË", "¶âÍË", "ÅÚÍË");
$name_day2 = array("·î", "²Ð", "¿å", "ÌÚ", "¶â", "ÅÚ","Æü");

$_lang['No Entries Found']= "No Entries Found";
$_lang['No Todays Events']= "HNo Todays Events";
$_lang['No new forum postings']= "No new forum postings";
$_lang['in category']= "in category";
$_lang['Filtered']= "Filtered";
$_lang['Sorted by']= "Sorted by";
$_lang['go'] = "¼Â¹Ô";
$_lang['back'] = "Ìá¤ë";
$_lang['print'] = "°õºþ";
$_lang['export'] = "¥¨¥¯¥¹¥Ý¡¼¥È";
$_lang['| (help)'] = "| (¥Ø¥ë¥×)";
$_lang['Are you sure?'] = "ËÜÅö¤Ë¤è¤í¤·¤¤¤Ç¤¹¤«¡©";
$_lang['items/page'] = "¹Ô¿ô/¥Ú¡¼¥¸";
$_lang['records'] = "¥ì¥³¡¼¥É";
$_lang['previous page'] = "Á°¤Î¥Ú¡¼¥¸";
$_lang['next page'] = "¼¡¤Î¥Ú¡¼¥¸";
$_lang['first page'] = "first page";
$_lang['last page'] = "last page";
$_lang['Move']  = "°ÜÆ°";
$_lang['Copy'] = "¥³¥Ô¡¼";
$_lang['Delete'] = "ºï½ü";
$_lang['Save'] = "ÊÝÂ¸";
$_lang['Directory'] = "¥Ç¥£¥ì¥¯¥È¥ê";
$_lang['Also Delete Contents'] = "ÆâÍÆ¤âºï½ü¤¹¤ë";
$_lang['Sum'] = "¹ç·×";
$_lang['Filter'] = "¥Õ¥£¥ë¥¿";
$_lang['Please fill in the following field'] = "¼¡¤Î¹àÌÜ¤ËÆþÎÏ¤·¤Æ¤¯¤À¤µ¤¤";
$_lang['approve'] = "³ÎÇ§";
$_lang['undo'] = "¤ä¤êÄ¾¤·";
$_lang['Please select!'] = "ÁªÂò¤·¤Æ¤¯¤À¤µ¤¤¡ª";
$_lang['New'] = "¿·µ¬ºîÀ®";
$_lang['Select all'] = "Á´¤Æ¤òÁªÂò";
$_lang['Printable view'] = "°õºþ²èÌÌ";
$_lang['New record in module '] = "¥â¥¸¥å¡¼¥ë¤Ë¿·¤·¤¤¥Ç¡¼¥¿¤¬ÄÉ²Ã¤µ¤ì¤Þ¤·¤¿¡£\n¥â¥¸¥å¡¼¥ëÌ¾¡§";
$_lang['Notify all group members'] = "¥°¥ë¡¼¥×¥á¥ó¥Ð¡¼Á´°÷¤ËÄÌÃÎ¤¹¤ë";
$_lang['Yes'] = "Yes";
$_lang['No'] = "No";
$_lang['Close window'] = "Close window";
$_lang['No Value'] = "No Value";
$_lang['Standard'] = "Standard";
$_lang['Create'] = "Anlegen";
$_lang['Modify'] = "Ändern";   
$_lang['today'] = "today";

// admin.php
$_lang['Password'] = "¥Ñ¥¹¥ï¡¼¥É";
$_lang['Login'] = "¥í¥°¥¤¥ó";
$_lang['Administration section'] = "´ÉÍý¥»¥¯¥·¥ç¥ó";
$_lang['Your password'] = "¤¢¤Ê¤¿¤Î¥Ñ¥¹¥ï¡¼¥É";
$_lang['Sorry you are not allowed to enter. '] = "Æþ¼¼¤òµö²Ä¤µ¤ì¤Æ¤¤¤Þ¤»¤ó¡£";
$_lang['Help'] = "¥Ø¥ë¥×";
$_lang['User management'] = "¥æ¡¼¥¶´ÉÍý";
$_lang['Create'] = "ºîÀ®";
$_lang['Projects'] = "¥×¥í¥¸¥§¥¯¥È";
$_lang['Resources'] = "¥ê¥½¡¼¥¹";
$_lang['Resources management'] = "Resources management";
$_lang['Bookmarks'] = "¥Ö¥Ã¥¯¥Þ¡¼¥¯";
$_lang['for invalid links'] = "¥ê¥ó¥¯ÀÚ¤ì";
$_lang['Check'] = "³ÎÇ§";
$_lang['delete Bookmark'] = "¥Ö¥Ã¥¯¥Þ¡¼¥¯¤Îºï½ü";
$_lang['(multiple select with the Ctrl-key)'] = "(Ê£¿ôÁªÂò Ctrl¥­¡¼)";
$_lang['Forum'] = "·Ç¼¨ÈÄ";
$_lang['Threads older than'] = "ÂÐ¾Ý¥¹¥ì¥Ã¥É";
$_lang[' days '] = " Æü°ÊÁ°";
$_lang['Chat'] = "¥Á¥ã¥Ã¥È";
$_lang['save script of current Chat'] = "¸½ºß¤Î¥Á¥ã¥Ã¥È¤òÊÝÂ¸¤¹¤ë";
$_lang['Chat script'] = "¥Á¥ã¥Ã¥ÈÊ¸¾Ï";
$_lang['New password'] = "¿·¥Ñ¥¹¥ï¡¼¥É";
$_lang['(keep old password: leave empty)'] = "(¸½ºß¤Î¥Ñ¥¹¥ï¡¼¥É¤òÊÝ»ý¤¹¤ë¾ì¹ç: ¶õÇò)";
$_lang['Default Group<br> (must be selected below as well)'] = "¥Ç¥Õ¥©¥ë¥È¥°¥ë¡¼¥×<br> (²¼µ­¤ÈÆ±¤¸¤â¤Î¤òÁªÂò)";
$_lang['Access rights'] = "¥¢¥¯¥»¥¹¸¢";
$_lang['Zip code'] = "Í¹ÊØÈÖ¹æ";
$_lang['Language'] = "¸À¸ì";
$_lang['schedule readable to others'] = "Â¾¤Î¿Í¤Ë¥¹¥±¥¸¥å¡¼¥ë¤ò¸ø³«¤¹¤ë";
$_lang['schedule invisible to others'] = "Â¾¤Î¿Í¤Ë¥¹¥±¥¸¥å¡¼¥ë¤ò¸ø³«¤·¤Ê¤¤";
$_lang['schedule visible but not readable'] = "¥¹¥±¥¸¥å¡¼¥ë¤òÉ½¼¨¤·¤Æ¤âÆÉ¤Þ¤»¤Ê¤¤";
$_lang['these fields have to be filled in.'] = "É¬¿ÜÆþÎÏ¹àÌÜ";
$_lang['You have to fill in the following fields: family name, short name and password.'] = "¼¡¤Î¹àÌÜ¤òÆþÎÏ¤·¤Æ¤¯¤À¤µ¤¤: Ì¾»ú¡¦ ¾ÊÎ¬Ì¾¡¦¥Ñ¥¹¥ï¡¼¥É";
$_lang['This family name already exists! '] = "¤³¤ÎÌ¾»ú¤Ï´û¤Ë»ÈÍÑ¤µ¤ì¤Æ¤¤¤Þ¤¹¡ª";
$_lang['This short name already exists!'] = "¤³¤Î¾ÊÎ¬Ì¾¤Ï´û¤Ë»ÈÍÑ¤µ¤ì¤Æ¤¤¤Þ¤¹¡ª";
$_lang['This login name already exists! Please chosse another one.'] = "¤³¤Î¥í¥°¥¤¥óÌ¾¤Ï´û¤Ë»ÈÍÑ¤µ¤ì¤Æ¤¤¤Þ¤¹¡ªÂ¾¤Î¥í¥°¥¤¥óÌ¾¤ò»È¤Ã¤Æ¤¯¤À¤µ¤¤¡£";
$_lang['This password already exists!'] = "¤³¤Î¥Ñ¥¹¥ï¡¼¥É¤Ï´û¤Ë»ÈÍÑ¤µ¤ì¤Æ¤¤¤Þ¤¹¡ª";
$_lang['This combination first name/family name already exists.'] = "¤³¤ÎÌ¾Á°¡¦Ì¾»ú¤ÎÁÈ¹ç¤»¤Ï´û¤Ë»ÈÍÑ¤µ¤ì¤Æ¤¤¤Þ¤¹¡£";
$_lang['the user is now in the list.'] = "¥æ¡¼¥¶¤¬ÅÐÏ¿¤µ¤ì¤Þ¤·¤¿¡£";
$_lang['the data set is now modified.'] = "¥Ç¡¼¥¿¤¬¹¹¿·¤µ¤ì¤Þ¤·¤¿¡£";
$_lang['Please choose a user'] = "¥æ¡¼¥¶¤òÁªÂò¤·¤Æ¤¯¤À¤µ¤¤";
$_lang['is still listed in some projects. Please remove it.'] = "¤ÏÂ¾¤Î¥×¥í¥¸¥§¥¯¥È¤Ë»²²Ã¤·¤Æ¤¤¤Þ¤¹¡£¥×¥í¥¸¥§¥¯¥È¤«¤éºï½ü¤·¤Æ¤¯¤À¤µ¤¤¡£";
$_lang['All profiles are deleted'] = "Á´¤Æ¤Î¥×¥í¥Õ¥£¡¼¥ë¤¬ºï½ü¤µ¤ì¤Þ¤·¤¿";
$_lang['A Profile with the same name already exists'] = "A profile with the same name already exists";
$_lang['is taken out of all user profiles'] = "¤ÏÁ´¤Æ¤Î¥æ¡¼¥¶¥×¥í¥Õ¥£¡¼¥ë¤«¤é½ü³°¤µ¤ì¤Þ¤·¤¿";
$_lang['All todo lists of the user are deleted'] = "Á´¤Æ¤Î»Å»ö¥ê¥¹¥È¤¬ºï½ü¤µ¤ì¤Þ¤·¤¿";
$_lang['is taken out of these votes where he/she has not yet participated'] = "»²²Ã¤·¤Æ¤¤¤Ê¤¤ÅÐ¾ì¤«¤é½ü³°¤µ¤ì¤Þ¤·¤¿";
$_lang['All events are deleted'] = "Á´¤Æ¤Î¥¤¥Ù¥ó¥È¤¬ºï½ü¤µ¤ì¤Þ¤·¤¿";
$_lang['user file deleted'] = "¥æ¡¼¥¶¥Õ¥¡¥¤¥ë¤¬ºï½ü¤µ¤ì¤Þ¤·¤¿";
$_lang['bank account deleted'] = "¶ä¹Ô¸ýºÂ¤¬ºï½ü¤µ¤ì¤Þ¤·¤¿";
$_lang['finished'] = "´°Î»";
$_lang['Please choose a project'] = "¥×¥í¥¸¥§¥¯¥È¤òÁªÂò¤·¤Æ¤¯¤À¤µ¤¤";
$_lang['The project is deleted'] = "¥×¥í¥¸¥§¥¯¥È¤¬ºï½ü¤µ¤ì¤Þ¤·¤¿";
$_lang['All links in events to this project are deleted'] = "¤³¤Î¥×¥í¥¸¥§¥¯¥È¤ËÂÐ¤¹¤ëÁ´¤Æ¤Î¥¤¥Ù¥ó¥È¥ê¥ó¥¯¤Ïºï½ü¤µ¤ì¤Þ¤·¤¿";
$_lang['The duration of the project is incorrect.'] = "¥×¥í¥¸¥§¥¯¥È¤Î´ü´Ö¤Ë¸í¤ê¤¬¤¢¤ê¤Þ¤¹¡£";
$_lang['The project is now in the list'] = "¥×¥í¥¸¥§¥¯¥È¤¬¥ê¥¹¥È¤ËÄÉ²Ã¤µ¤ì¤Þ¤·¤¿";
$_lang['The project has been modified'] = "¥×¥í¥¸¥§¥¯¥È¤¬¹¹¿·¤µ¤ì¤Þ¤·¤¿";
$_lang['Please choose a resource'] = "¥ê¥½¡¼¥¹¤òÁªÂò¤·¤Æ¤¯¤À¤µ¤¤";
$_lang['The resource is deleted'] = "¥ê¥½¡¼¥¹¤¬ºï½ü¤µ¤ì¤Þ¤·¤¿";
$_lang['All links in events to this resource are deleted'] = "¤³¤Î¥ê¥½¡¼¥¹¤Ë´Ø¤¹¤ë¥¤¥Ù¥ó¥ÈÆâ¤Î¥ê¥ó¥¯¤ÏÁ´¤Æºï½ü¤µ¤ì¤Þ¤·¤¿";
$_lang[' The resource is now in the list.'] = " ¥ê¥½¡¼¥¹¤Ï¥ê¥¹¥È¤ËÄÉ²Ã¤µ¤ì¤Þ¤·¤¿¡£";
$_lang[' The resource has been modified.'] = " ¥ê¥½¡¼¥¹¤¬½¤Àµ¤µ¤ì¤Þ¤·¤¿¡£";
$_lang['The server sent an error message.'] = "¥µ¡¼¥Ð¤è¤ê¥¨¥é¡¼¤¬ÄÌÃÎ¤µ¤ì¤Þ¤·¤¿¡£";
$_lang['All Links are valid.'] = "Á´¤Æ¤Î¥ê¥ó¥¯¤ÏÀµ¾ï¤Ç¤¹¡£";
$_lang['Please select at least one bookmark'] = "¾¯¤Ê¤¯¤È¤â1¤Ä¤Î¥Ö¥Ã¥¯¥Þ¡¼¥¯¤òÁªÂò¤·¤Æ¤¯¤À¤µ¤¤";
$_lang['The bookmark is deleted'] = "¥Ö¥Ã¥¯¥Þ¡¼¥¯¤¬ºï½ü¤µ¤ì¤Þ¤·¤¿";
$_lang['threads older than x days are deleted.'] = "¡§¥¹¥ì¥Ã¥Éºï½ü·ï¿ô(";
$_lang['All chat scripts are removed'] = "Á´¤Æ¤Î¥Á¥ã¥Ã¥ÈÊ¸¾Ï¤¬ºï½ü¤µ¤ì¤Þ¤·¤¿";
$_lang['or'] = "¤Þ¤¿¤Ï";
$_lang['Timecard management'] = "¥¿¥¤¥à¥«¡¼¥É´ÉÍý";
$_lang['View'] = "±ÜÍ÷";
$_lang['Choose group'] = "¥°¥ë¡¼¥×¤òÁªÂò";
$_lang['Group name'] = "¥°¥ë¡¼¥×Ì¾";
$_lang['Short form'] = "¾ÊÎ¬Ì¾";
$_lang['Category'] = "¥«¥Æ¥´¥ê";
$_lang['Remark'] = "È÷¹Í";
$_lang['Group management'] = "¥°¥ë¡¼¥×´ÉÍý";
$_lang['Please insert a name'] = "Ì¾Á°¤òÆþÎÏ¤·¤Æ¤¯¤À¤µ¤¤";
$_lang['Name or short form already exists'] = "Ì¾Á°¤Þ¤¿¤Ï¾ÊÎ¬Ì¾¤¬´û¤ËÅÐÏ¿¤µ¤ì¤Æ¤¤¤Þ¤¹";
$_lang['Automatic assign to group:'] = "¼«Æ°Åª¤Ë³äÅö¤Æ¤ë¥°¥ë¡¼¥×:";
$_lang['Automatic assign to user:'] = "¼«Æ°Åª¤Ë³äÅö¤Æ¤ë¥æ¡¼¥¶:";
$_lang['Help Desk Category Management'] = "¥Ø¥ë¥×¥Ç¥¹¥¯´ÉÍý";
$_lang['Category deleted'] = "¥«¥Æ¥´¥ê¤¬ºï½ü¤µ¤ì¤Þ¤·¤¿";
$_lang['The category has been created'] = "¥«¥Æ¥´¥ê¤¬ºîÀ®¤µ¤ì¤Þ¤·¤¿";
$_lang['The category has been modified'] = "¥«¥Æ¥´¥ê¤¬ÊÑ¹¹¤µ¤ì¤Þ¤·¤¿";
$_lang['Member of following groups'] = "½êÂ°¥°¥ë¡¼¥×";
$_lang['Primary group is not in group list'] = "¼çÍ×¥°¥ë¡¼¥×¤Ï¥°¥ë¡¼¥×¥ê¥¹¥È¤ÎÃæ¤Ë¤¢¤ê¤Þ¤»¤ó";
$_lang['Login name'] = "¥í¥°¥¤¥óÌ¾";
$_lang['You cannot delete the default group'] = "¥°¥ë¡¼¥×default¤Ïºï½ü¤Ç¤­¤Þ¤»¤ó";
$_lang['Delete group and merge contents with group'] = "¥°¥ë¡¼¥×¤Îºï½üµÚ¤Ó¥°¥ë¡¼¥×¥³¥ó¥Æ¥ó¥Ä¤ÎÊ»¹ç";
$_lang['Please choose an element'] = "¹àÌÜ¤òÁªÂò¤·¤Æ¤¯¤À¤µ¤¤";
$_lang['Group created'] = "¥°¥ë¡¼¥×¤¬ºîÀ®¤µ¤ì¤Þ¤·¤¿";
$_lang['File management'] = "¥Õ¥¡¥¤¥ë´ÉÍý";
$_lang['Orphan files'] = "½êÍ­¼ÔÌµ¤·¥Õ¥¡¥¤¥ë";
$_lang['Deletion of super admin root not possible'] = "¥ë¡¼¥È¤Î¥¹¡¼¥Ñ¡¼¥æ¡¼¥¶¤Ïºï½ü¤Ç¤­¤Þ¤»¤ó";
$_lang['ldap name'] = "LDAPÌ¾";
$_lang['mobile // mobile phone'] = "·ÈÂÓÅÅÏÃ"; // mobile phone
$_lang['Normal user'] = "°ìÈÌ¥æ¡¼¥¶";
$_lang['User w/Chief Rights'] = "¥Á¡¼¥Õ";
$_lang['Administrator'] = "´ÉÍý¼Ô";
$_lang['Logging'] = "µ­Ï¿";
$_lang['Logout'] = "¥í¥°¥¢¥¦¥È";
$_lang['posting (and all comments) with an ID'] = "Åê¹Æ(Á´¤Æ¤Î¥³¥á¥ó¥È¤ò´Þ¤à) ÂÐ¾Ý¥æ¡¼¥¶ID";
$_lang['Role deleted, assignment to users for this role removed'] = "¸¢¸ÂµÚ¤Ó¥æ¡¼¥¶¤Ø¤Î¸¢¸Â³äÅö¤Æ¤¬ºï½ü¤µ¤ì¤Þ¤·¤¿";
$_lang['The role has been created'] = "¸¢¸Â¤¬ºîÀ®¤µ¤ì¤Þ¤·¤¿";
$_lang['The role has been modified'] = "¸¢¸Â¤¬½¤Àµ¤µ¤ì¤Þ¤·¤¿";
$_lang['Access rights'] = "Access rights";
$_lang['Usergroup'] = "Gruppe";
$_lang['logged in as'] = "Angemeldet als";

//chat.php
$_lang['Quit chat']= "Quit chat";

//contacts.php
$_lang['Contact Manager'] = "Ï¢ÍíÀè";
$_lang['New contact'] = "¿·µ¬Ï¢ÍíÀè";
$_lang['Group members'] = "¥°¥ë¡¼¥×¥á¥ó¥Ð¡¼";
$_lang['External contacts'] = "³°Éô¤ÎÏ¢ÍíÀè";
$_lang['&nbsp;New&nbsp;'] = "&nbsp;ºîÀ®&nbsp;";
$_lang['Import'] = "¥¤¥ó¥Ý¡¼¥È";
$_lang['The new contact has been added'] = "¿·µ¬Ï¢ÍíÀè¤¬ÄÉ²Ã¤µ¤ì¤Þ¤·¤¿";
$_lang['The date of the contact was modified'] = "Ï¢ÍíÀè¤ÎÆüÉÕ¤¬¹¹¿·¤µ¤ì¤Þ¤·¤¿";
$_lang['The contact has been deleted'] = "Ï¢ÍíÀè¤¬ºï½ü¤µ¤ì¤Þ¤·¤¿";
$_lang['Open to all'] = "Á´°÷¤Ë¸ø³«¤¹¤ë";
$_lang['Picture'] = "¼Ì¿¿";
$_lang['Please select a vcard (*.vcf)'] = "vcard¤òÁªÂò¤·¤Æ¤¯¤À¤µ¤¤ (*.vcf)";
$_lang['create vcard'] = "vcard¤ÎºîÀ®";
$_lang['import address book'] = "¥¢¥É¥ì¥¹Ä¢¤ò¥¤¥ó¥Ý¡¼¥È¤¹¤ë";
$_lang['Please select a file (*.csv)'] = "¥Õ¥¡¥¤¥ë¤òÁªÂò¤·¤Æ¤¯¤À¤µ¤¤ (*.csv)";
$_lang['Howto: Open your outlook express address book and select file/export/other book<br>Then give the file a name, select all fields in the next dialog and finish'] = "Howto: Outlook Express¤Î¥¢¥É¥ì¥¹Ä¢¤ò³«¤¤¤Æ¡¢'¥Õ¥¡¥¤¥ë'/'¥¨¥¯¥¹¥Ý¡¼¥È'/
'Â¾¤Î¥¢¥É¥ì¥¹Ä¢'¤òÁªÂò¤·¤Æ¤¯¤À¤µ¤¤¡£<br>¥Õ¥¡¥¤¥ëÌ¾¤òÆþÎÏ¤·¤Æ¡¢¼¡¤Î²èÌÌ¤ÇÁ´¤Æ¤Î¹àÌÜ¤òÁªÂò¤·¤¿¸å '´°Î»'¤ò¥¯¥ê¥Ã¥¯¤·¤Æ¤¯¤À¤µ¤¤¡£";
$_lang['Open outlook at file/export/export in file,<br>choose comma separated values (Win), then select contacts in the next form,<br>give the export file a name and finish.'] = "¥¢¥¦¥È¥ë¥Ã¥¯¤Î'¥Õ¥¡¥¤¥ë/¥¨¥¯¥¹¥Ý¡¼¥È/¼õ¿®Êí'¤ò³«¤¤¤Æ¤¯¤À¤µ¤¤<br>
'¥Æ¥­¥¹¥È¥Õ¥¡¥¤¥ë(Windows¡¢¥«¥ó¥Þ¶èÀÚ¤ê)'¤òÁªÂò¤·¤Æ¡¢'Ï¢ÍíÀè'¤òÁª¤Ó¤Þ¤¹¡£<br>
¼¡¤Î²èÌÌ¤Ç¥¨¥¯¥¹¥Ý¡¼¥È¥Õ¥¡¥¤¥ë¤ÎÌ¾Á°¤òÆþÎÏ¤·¤Æ'´°Î»'¤ò¥¯¥ê¥Ã¥¯¤·¤Æ¤¯¤À¤µ¤¤¡£";
$_lang['Please choose an export file (*.csv)'] = "¥¨¥¯¥¹¥Ý¡¼¥È¥Õ¥¡¥¤¥ë¤òÁªÂò¤·¤Æ¤¯¤À¤µ¤¤ (*.csv)";
$_lang['Please export your address book into a comma separated value file (.csv), and either<br>1) apply an import pattern OR<br>2) modify the columns of the table with a spread sheet to this format<br>(Delete colums in you file that are not listed here and create empty colums for fields that do not exist in your file):'] = "Please export your address book into a comma separated value file (.csv), and either<br>
1) apply an import pattern OR<br>
2) modify the columns of the table with a spread sheet to this format<br>
(Delete colums in you file that are not listed here and create empty colums for fields that do not exist in your file):";
$_lang['Please insert at least the family name'] = "¾¯¤Ê¤¯¤È¤âÌ¾»ú¤òÆþÎÏ¤·¤Æ¤¯¤À¤µ¤¤";
$_lang['Record import failed because of wrong field count'] = "¥Õ¥£¡¼¥ë¥É¿ô¤¬°Û¤Ê¤ë¤¿¤á¡¢¥ì¥³¡¼¥É¤Î¥¤¥ó¥Ý¡¼¥È¤¬¼ºÇÔ¤·¤Þ¤·¤¿";
$_lang['Import to approve'] = "¥¤¥ó¥Ý¡¼¥È³ÎÇ§";
$_lang['Import list'] = "¥¤¥ó¥Ý¡¼¥È¥ê¥¹¥È";
$_lang['The list has been imported.'] = "¥ê¥¹¥È¤¬¥¤¥ó¥Ý¡¼¥È¤µ¤ì¤Þ¤·¤¿¡£";
$_lang['The list has been rejected.'] = "¥ê¥¹¥È¤¬µñÈÝ¤µ¤ì¤Þ¤·¤¿¡£";
$_lang['Profiles'] = "¥×¥í¥Õ¥£¡¼¥ë";
$_lang['Parent object'] = "¿Æ¥×¥í¥¸¥§¥¯¥È";
$_lang['Check for duplicates during import'] = "Check for doublets during import";
$_lang['Fields to match'] = "Fields to match";
$_lang['Action for duplicates'] = "Action for doublets";
$_lang['Discard duplicates'] = "Discard doublet";
$_lang['Dispose as child'] = "Dispose as child";
$_lang['Store as profile'] = "Store as profile";    
$_lang['Apply import pattern'] = "Apply import pattern";
$_lang['Import pattern'] = "Import pattern";
$_lang['For modification or creation<br>upload an example csv file'] = "Upload import file (csv)"; 
$_lang['Skip field'] = "Skip field";
$_lang['Field separator'] = "Field separator"; 
$_lang['Contact selector'] = "Contact selector";
$_lang['Use doublet'] = "Use doublet";
$_lang['Doublets'] = "Doublets";

// filemanager.php
$_lang['Please select a file'] = "¥Õ¥¡¥¤¥ë¤òÁªÂò¤·¤Æ¤¯¤À¤µ¤¤";
$_lang['A file with this name already exists!'] = "Æ±°ìÌ¾¤Î¥Õ¥¡¥¤¥ë¤¬Â¸ºß¤·¤Þ¤¹¡ª";
$_lang['Name'] = "Ì¾¾Î";
$_lang['Comment'] = "È÷¹Í";
$_lang['Date'] = "ÆüÉÕ";
$_lang['Upload'] = "¥¢¥Ã¥×¥í¡¼¥É";
$_lang['Filename and path'] = "¥Õ¥¡¥¤¥ëÌ¾¤È¥Ñ¥¹";
$_lang['Delete file'] = "¥Õ¥¡¥¤¥ë¤Îºï½ü";
$_lang['Overwrite'] = "¾å½ñ¤­";
$_lang['Access'] = "¥¢¥¯¥»¥¹µö²Ä";
$_lang['Me'] = "¼«Ê¬";
$_lang['Group'] = "group";
$_lang['Some'] = "ÁªÂò";
$_lang['As parent object'] = "¥Ç¥£¥ì¥¯¥È¥ê¤ÈÆ±¤¸";
$_lang['All groups'] = "All groups";
$_lang['You are not allowed to overwrite this file since somebody else uploaded it'] = "Â¾¤Î¿Í¤¬¥¢¥Ã¥×¥í¡¼¥É¤·¤¿°Ù¡¢¤³¤Î¥Õ¥¡¥¤¥ë¤Ï¾å½ñ¤­¤Ç¤­¤Þ¤»¤ó";
$_lang['personal'] = "¸Ä¿ÍÍÑ";
$_lang['Link'] = "¥ê¥ó¥¯";
$_lang['name and network path'] = "¥Õ¥¡¥¤¥ëÌ¾¤È¥Í¥Ã¥È¥ï¡¼¥¯¥Ñ¥¹";
$_lang['with new values'] = "¥³¥Ô¡¼/°ÜÆ°Àè¤ò»ØÄê";
$_lang['All files in this directory will be removed! Continue?'] = "¥Ç¥£¥ì¥¯¥È¥êÆâ¤Î¥Õ¥¡¥¤¥ë¤âºï½ü¤µ¤ì¤Þ¤¹¡ª Â³¤±¤Þ¤¹¤«¡©";
$_lang['This name already exists'] = "¤³¤Î¥Õ¥¡¥¤¥ëÌ¾¤Ï´û¤Ë»ÈÍÑ¤µ¤ì¤Æ¤¤¤Þ¤¹";
$_lang['Max. file size'] = "ºÇÂç¥Õ¥¡¥¤¥ë¥µ¥¤¥º";
$_lang['links to'] = "¥ê¥ó¥¯";
$_lang['objects'] = "¥Õ¥¡¥¤¥ë¿ô";
$_lang['Action in same directory not possible'] = "Æ±°ì¥Ç¥£¥ì¥¯¥È¥ê¤Ø¤Î½èÍý¤Ï½ÐÍè¤Þ¤»¤ó";
$_lang['Upload = replace file'] = "¥¢¥Ã¥×¥í¡¼¥É = ¥Õ¥¡¥¤¥ë¤ÎÆþÂØ¤¨";
$_lang['Insert password for crypted file'] = "¥Õ¥¡¥¤¥ë°Å¹æ²½¤Î¥Ñ¥¹¥ï¡¼¥É¤òÆþÎÏ¤·¤Æ¤¯¤À¤µ¤¤";
$_lang['Crypt upload file with password'] = "¥¢¥Ã¥×¥í¡¼¥É¤ò¥Ñ¥¹¥ï¡¼¥É¤òÉÕ²Ã¤·¤Æ¥Õ¥¡¥¤¥ë¤ò°Å¹æ²½";
$_lang['Repeat'] = "¤â¤¦°ìÅÙ";
$_lang['Passwords dont match!'] = "¥Ñ¥¹¥ï¡¼¥É¤¬¹çÃ×¤·¤Þ¤»¤ó¡ª";
$_lang['Download of the password protected file '] = "¥Ñ¥¹¥ï¡¼¥ÉÊÝ¸î¥Õ¥¡¥¤¥ë¤ò¥À¥¦¥ó¥í¡¼¥É¤¹¤ë ";
$_lang['notify all users with access'] = "¥¢¥¯¥»¥¹¸¢¤Î¤¢¤ëÁ´¥æ¡¼¥¶¤ËÄÌÃÎ¤¹¤ë";
$_lang['Write access'] = "½ñ¹þ¤ßµö²Ä";
$_lang['Version'] = "¥Ð¡¼¥¸¥ç¥ó";
$_lang['Version management'] = "¥Ð¡¼¥¸¥ç¥ó´ÉÍý";
$_lang['lock'] = "¥í¥Ã¥¯";
$_lang['unlock'] = "¥¢¥ó¥í¥Ã¥¯";
$_lang['locked by'] = "¥í¥Ã¥¯Ãæ";
$_lang['Alternative Download'] = "ÂåÂØ¥À¥¦¥ó¥í¡¼¥É";
$_lang['Download'] = "Download";
$_lang['Select type'] = "Select type";
$_lang['Create directory'] = "Create directory";
$_lang['filesize (Byte)'] = "Filesize (Byte)";

// filter
$_lang['contains'] = 'contains';
$_lang['exact'] = 'exact';
$_lang['starts with'] = 'starts with';
$_lang['ends with'] = 'ends with';
$_lang['>'] = '>';
$_lang['>='] = '>=';
$_lang['<'] = '<';
$_lang['<='] = '<=';
$_lang['does not contain'] = 'does not contain'; 
$_lang['Please set (other) filters - too many hits!'] = "Please set (other) filters - too many hits!";

$_lang['Edit filter'] = "Edit filter";
$_lang['Filter configuration'] = "Filter configuration";
$_lang['Disable set filters'] = "Disable set filters";
$_lang['Load filter'] = "Load filter";
$_lang['Delete saved filter'] = "Delete saved filter";
$_lang['Save currently set filters'] = "Save currently set filters";
$_lang['Save as'] = "Save as";
$_lang['News'] = 'Nachrichten';

// form designer
$_lang['Module Designer'] = "Module Designer";
$_lang['Module element'] = "Module element"; 
$_lang['Module'] = "Module";
$_lang['Active'] = "Activ";
$_lang['Inactive'] = "Inactiv";
$_lang['Activate'] = "Aktivate";
$_lang['Deactivate'] = "Deaktivate"; 
$_lang['Create new element'] = "Create new element";
$_lang['Modify element'] = "Modify element";
$_lang['Field name in database'] = "Field name in database";
$_lang['Use only normal characters and numbers, no special characters,spaces etc.'] = "Use only normal characters and numbers, no special characters,spaces etc.";
$_lang['Field name in form'] = "Field name in form";
$_lang['(could be modified later)'] = "(could be modified later)"; 
$_lang['Single Text line'] = "Single Text line";
$_lang['Textarea'] = "Textarea";
$_lang['Display'] = "Display";
$_lang['First insert'] = "First insert";
$_lang['Predefined selection'] = "Predefined selection";
$_lang['Select by db query'] = "Select by db query";
$_lang['File'] = "File";

$_lang['Email Address'] = "Email Address";
$_lang['url'] = "url";
$_lang['Checkbox'] = "Checkbox";
$_lang['Multiple select'] = "Multiple select"; 
$_lang['Display value from db query'] = "Display value from db query";
$_lang['Time'] = "Time";
$_lang['Tooltip'] = "Tooltip"; 
$_lang['Appears as a tipp while moving the mouse over the field: Additional comments to the field or explanation if a regular expression is applied'] = "Appears as a tipp while moving the mouse over the field: Additional comments to the field or explanation if a regular expression is applied";
$_lang['Position'] = "Position";
$_lang['is current position, other free positions are:'] = "is current position, other free positions are:"; 
$_lang['Regular Expression:'] = "Regular Expression:";
$_lang['Please enter a regular expression to check the input on this field'] = "Please enter a regular expression to check the input on this field";
$_lang['Default value'] = "Default value";
$_lang['Predefined value for creation of a record. Could be used in combination with a hidden field as well'] = "Predefined value for creation of a record. Could be used in combination with a hidden field as well";
$_lang['Content for select Box'] = "Content for select Box";
$_lang['Used for fixed amount of values (separate with the pipe: | ) or for the sql statement, see element type'] = "Used for fixed amount of values (separate with the pipe: | ) or for the sql statement, see element type";
$_lang['Position in list view'] = "Position in list view";
$_lang['Only insert a number > 0 if you want that this field appears in the list of this module'] = "Only insert a number > 0 if you want that this field appears in the list of this module";
$_lang['Alternative list view'] = "Alternative list view";
$_lang['Value appears in the alt tag of the blue button (mouse over) in the list view'] = "Value appears in the alt tag of the blue button (mouse over) in the list view";
$_lang['Filter element'] = "Filter element";
$_lang['Appears in the filter select box in the list view'] = "Appears in the filter select box in the list view";
$_lang['Element Type'] = "Element Type";
$_lang['Select the type of this form element'] = "Select the type of this form element";
$_lang['Check the content of the previous field!'] = "Check the content of the previous field!";
$_lang['Span element over'] = "Span element over";
$_lang['columns'] = "columns";
$_lang['rows'] = "rows";
$_lang['Telephone'] = "Telephone";
$_lang['History'] = "History";
$_lang['Field'] = "Field";
$_lang['Old value'] = "Old value";
$_lang['New value'] = "New value";
$_lang['Author'] = "Author"; 
$_lang['Show Date'] = "Show Date";
$_lang['Creation date'] = "Creation date";
$_lang['Last modification date'] = "Last modification date";
$_lang['Email (at record cration)'] = "Email (at record cration)";
$_lang['Contact (at record cration)'] = "Contact (at record cration)"; 
$_lang['Select user'] = "Select user";
$_lang['Show user'] = "Show user";

// forum.php
$_lang['Please give your thread a title'] = "Please give your thread a title";
$_lang['New Thread'] = "New Thread";
$_lang['Title'] = "Title";
$_lang['Text'] = "Text";
$_lang['Post'] = "Post";
$_lang['From'] = "From";
$_lang['open'] = "open";
$_lang['closed'] = "closed";
$_lang['Notify me on comments'] = "Notify me on comments";
$_lang['Answer to your posting in the forum'] = "Answer to your posting in the forum";
$_lang['You got an answer to your posting'] = "You got an answer to your posting \n ";
$_lang['New posting'] = "New posting";
$_lang['Create new forum'] = "Create new forum";
$_lang['down'] ='down';
$_lang['up']= "up";
$_lang['Forums']= "Forums";
$_lang['Topics']="Topics";
$_lang['Threads']="Threads";
$_lang['Latest Thread']="Latest Thread";
$_lang['Overview forums']= "Overview forums";
$_lang['Succeeding answers']= "Succeeding answers";
$_lang['Count']= "Count";
$_lang['from']= "from";
$_lang['Path']= "Path";
$_lang['Thread title']= "Thread title";
$_lang['Notification']= "Notification";
$_lang['Delete forum']= "Delete forum";
$_lang['Delete posting']= "Delete posting";
$_lang['In this table you can find all forums listed']= "In this table you can find all forums listed";
$_lang['In this table you can find all threads listed']= "In this table you can find all threads listed";

// index.php
$_lang['Last name'] = "Last name";
$_lang['Short name'] = "Short name";
$_lang['Sorry you are not allowed to enter.'] = "Sorry you are not allowed to enter.";
$_lang['Please run index.php: '] = "Please run index.php: ";
$_lang['Reminder'] = "Reminder";
$_lang['Session time over, please login again'] = "Session time over, please login again";
$_lang['&nbsp;Hide read elements'] = "&nbsp;Hide read elements";
$_lang['&nbsp;Show read elements'] = "&nbsp;Show read elements";
$_lang['&nbsp;Hide archive elements'] = "&nbsp;Hide archive elements";
$_lang['&nbsp;Show archive elements'] = "&nbsp;Show archive elements";
$_lang['Tree view'] = "Tree view";
$_lang['flat view'] = "flat view";
$_lang['New todo'] = "New todo";
$_lang['New note'] = "New note";
$_lang['New document'] = "New document";
$_lang['Set bookmark'] = "Set bookmark";
$_lang['Move to archive'] = "Move to archive";
$_lang['Mark as read'] = "Mark as read";
$_lang['Export as csv file'] = "Export as csv file";
$_lang['Deselect all'] = "Deselect all";
$_lang['selected elements'] = "selected elements";
$_lang['wider'] = "wider";
$_lang['narrower'] = "narrower";
$_lang['ascending'] = "Aufsteigend";
$_lang['descending'] = "descending";
$_lang['Column'] = "Column";
$_lang['Sorting'] = "Sorting";
$_lang['Save width'] = "Save width";
$_lang['Width'] = "Width";
$_lang['switch off html editor'] = "switch off html editor";
$_lang['switch on html editor'] = "switch on html editor";
$_lang['hits were shown for'] = "hits were shown for";
$_lang['there were no hits found.'] = "there were no hits found.";
$_lang['Filename'] = "Filename";
$_lang['First Name'] = "First Name";
$_lang['Family Name'] = "Family Name";
$_lang['Company'] = "Company";
$_lang['Street'] = "Street";
$_lang['City'] = "City";
$_lang['Country'] = "Country";
$_lang['Please select the modules where the keyword will be searched'] = "Please select the modules where the keyword will be searched";
$_lang['Enter your keyword(s)'] = "Enter your keyword(s)";
$_lang['Salutation'] = "Salutation";
$_lang['State'] = "State";
$_lang['Add to link list'] = "Add to link list";

// setup.php
$_lang['Welcome to the setup of PHProject!<br>'] = "PHProject¥»¥Ã¥È¥¢¥Ã¥×¤Ø¤è¤¦¤³¤½¡ª<br>";
$_lang['Please remark:<ul><li>A blank database must be available<li>Please ensure that the webserver is able to write the file config.inc.php'] = "¥»¥Ã¥È¥¢¥Ã¥×¤ÎÁ°¤Ë:<ul>
<li>¶õ¤Î¥Ç¡¼¥¿¥Ù¡¼¥¹¤òºîÀ®¤·¤Æ¤¯¤À¤µ¤¤<li>¥¦¥§¥Ö¥µ¡¼¥Ð¤Ë'config.inc.php'
¥Õ¥¡¥¤¥ë¤ò½ñ¹þ¤à¤³¤È¤¬½ÐÍè¤ë¤«³ÎÇ§¤·¤Æ¤¯¤À¤µ¤¤";
$_lang['<li>If you encounter any errors during the installation, please look into the <a href=help/faq_install.html target=_blank>install faq</a>or visit the <a href=http://www.PHProjekt.com/forum.html target=_blank>Installation forum</a></i>'] = "<li>¥¤¥ó¥¹¥È¡¼¥ëÃæ¤Ë¥¨¥é¡¼¤¬È¯À¸¤·¤¿¾ì¹ç¡¢
<a href='help/faq_install.html'>install faq</a>¤ò»²¾È¤¹¤ë¤«Installation forum¤òË¬Ìä¤·¤Æ¤¯¤À¤µ¤¤</i>";
$_lang['Please fill in the fields below'] = "²¼µ­¤Î¹àÌÜ¤ËÆþÎÏ¤·¤Æ¤¯¤À¤µ¤¤";
$_lang['(In few cases the script wont respond.<br>Cancel the script, close the browser and try it again).<br>'] = "(¤Þ¤ì¤Ë¥¹¥¯¥ê¥×¥È¤¬ÊÖÅú¤·¤Ê¤¤¾ì¹ç¤¬¤¢¤ê¤Þ¤¹¡£<br>¥¹¥¯¥ê¥×¥È¤ò¥­¥ã¥ó¥»¥ë¤·¤Æ¥Ö¥é¥¦¥¶¤òÊÄ¤¸¤¿¸å¡¢<br>
ºÆÅÙ¥¤¥ó¥¹¥È¡¼¥ëºî¶È¤ò¹Ô¤Ã¤Æ¤¯¤À¤µ¤¤¡£)<br>";
$_lang['Type of database'] = "¥Ç¡¼¥¿¥Ù¡¼¥¹¤Î¼ïÎà";
$_lang['Hostname'] = "¥Û¥¹¥ÈÌ¾";
$_lang['Username'] = "¥æ¡¼¥¶Ì¾";

$_lang['Name of the existing database'] = "¥Ç¡¼¥¿¥Ù¡¼¥¹Ì¾";
$_lang['config.inc.php not found! Do you really want to update? Please read INSTALL ...'] = "config.inc.php¤¬¸«¤Ä¤«¤ê¤Þ¤»¤ó¡ª ËÜÅö¤Ë¹¹¿·¤·¤Þ¤¹¤«¡© INSTALL¤òÆÉ¤ó¤Ç¤¯¤À¤µ¤¤ ...";
$_lang['config.inc.php found! Maybe you prefer to update PHProject? Please read INSTALL ...'] = "config.inc.php¤¬¸«¤Ä¤«¤ê¤Þ¤·¤¿¡ª PHProject¤ò¹¹¿·¤·¤Æ¤â¤è¤í¤·¤¤¤Ç¤¹¤«¡© INSTALL¤òÆÉ¤ó¤Ç¤¯¤À¤µ¤¤ ...";
$_lang['Please choose Installation,Update or Configure!'] = "Installation¡¢Update¡¢Configure¤Î¤É¤ì¤«¤òÁªÂò¤·¤Æ¤¯¤À¤µ¤¤¡ª";
$_lang['Sorry, I cannot connect to the database! <br>Please close all browser windows and restart the installation.'] = "¥Ç¡¼¥¿¥Ù¡¼¥¹¤ËÀÜÂ³¤Ç¤­¤Þ¤»¤ó¡ª<br>ÌäÂê¤ò²ò·è¤·¤ÆºÆÅÙ¥¤¥ó¥¹¥È¡¼¥ëºî¶È¤ò¹Ô¤Ã¤Æ¤¯¤À¤µ¤¤¡£";
$_lang['Sorry, it does not work! <br> Please set DBDATE to Y4MD- or let phprojekt change this environment-variable (php.ini)!'] = "Àµ¾ï¤ËÆ°ºî¤·¤Þ¤»¤ó¡ª<br>DBDATE¤ò'Y4MD-'¤ËÀßÄê¤¹¤ë¤«phprojekt¤¬´Ä¶­ÊÑ¿ô(php.ini)¤òÊÑ¹¹¤Ç¤­¤ë¤è¤¦¤Ë¤·¤Æ¤¯¤À¤µ¤¤¡ª";
$_lang['Seems that You have a valid database connection!'] = "¥Ç¡¼¥¿¥Ù¡¼¥¹¤ËÀÜÂ³½ÐÍè¤Þ¤·¤¿¡ª";
$_lang['Please select the modules you are going to use.<br> (You can disable them later in the config.inc.php)<br>'] = "»ÈÍÑ¤¹¤ë¥â¥¸¥å¡¼¥ë¤òÁªÂò¤·¤Æ¤¯¤À¤µ¤¤¡£<br>(¸å¤Çconfig.inc.php¤òÊÔ½¸¤·¤ÆÉÔ»ÈÍÑ¤Ë¤¹¤ë¤³¤È¤â½ÐÍè¤Þ¤¹)<br>";
$_lang['Install component: insert a 1, otherwise keep the field empty'] = "¥¤¥ó¥¹¥È¡¼¥ë¹½À®: »ÈÍÑ¤¹¤ë¾ì¹ç¤Ï¡¢1¤òÆþ¤ì¤Æ¤¯¤À¤µ¤¤¡£»ÈÍÑ¤·¤Ê¤¤¾ì¹ç¤Ï¡¢¶õÇò¤Ë¤·¤Æ¤¯¤À¤µ¤¤¡£";
$_lang['Group views'] = "¥°¥ë¡¼¥×¾È²ñ";
$_lang['Todo lists'] = "»Å»ö";

$_lang['Voting system'] = "ÅêÉ¼";


$_lang['Contact manager'] = "Ï¢ÍíÀè";
$_lang['Name of userdefined field'] = "¥æ¡¼¥¶ÄêµÁ¹àÌÜÌ¾";
$_lang['Userdefined'] = "¥æ¡¼¥¶ÄêµÁ";
$_lang['Profiles for contacts'] = "¥×¥í¥Õ¥£¡¼¥ë";
$_lang['Mail'] = "¥á¡¼¥ë";
$_lang['send mail'] = "1=¥á¡¼¥ëÁ÷¿®¤Î¤ß";
$_lang[' only,<br> &nbsp; &nbsp; full mail client'] = "<br>&nbsp;&nbsp; 2=¥Õ¥ë¡¦¥á¡¼¥ë¥¯¥é¥¤¥¢¥ó¥È";



$_lang['1 to show appointment list in separate window,<br>&nbsp; &nbsp; 2 for an additional alert.'] = "1:¥¢¥Ý¥¤¥ó¥È¥á¥ó¥È¡¦¥ê¥¹¥È¤òÊÌ¥¦¥¤¥ó¥É¤ËÉ½¼¨<br>&nbsp; &nbsp;2=ÄÉ²ÃÅª¥¢¥é¡¼¥È";
$_lang['Alarm'] = "ÄÌÃÎ";
$_lang['max. minutes before the event'] = "¥¤¥Ù¥ó¥È¤ÎÁ°¤ÎºÇÂç»þ´Ö(Ê¬)";
$_lang['SMS/Mail reminder'] = "SMS/¥á¡¼¥ë ¥ê¥Þ¥¤¥ó¥À";
$_lang['Reminds via SMS/Email'] = "SMS/¥á¡¼¥ë·ÐÍ³¤ÇÄÌÃÎ¤¹¤ë";
$_lang['1= Create projects,<br>&nbsp; &nbsp; 2= assign worktime to projects only with timecard entry<br>&nbsp; &nbsp; 3= assign worktime to projects without timecard entry<br>&nbsp; &nbsp; (Selection 2 or 3 only with module timecard!)'] = "1=¥×¥í¥¸¥§¥¯¥È¤òºîÀ®<br>
&nbsp; &nbsp; 2=¥¿¥¤¥à¥«¡¼¥ÉÆþÎÏ¤¬¤¢¤ë¾ì¹ç¤Î¤ßÏ«Æ¯»þ´Ö¤òÈ¿±Ç¤¹¤ë<br>
&nbsp; &nbsp; 3=¥¿¥¤¥à¥«¡¼¥ÉÆþÎÏ¤¬Ìµ¤¤¾ì¹ç¤Ç¤âÏ«Æ¯»þ´Ö¤òÈ¿±Ç¤¹¤ë<br>
&nbsp; &nbsp; (2¤Þ¤¿¤Ï3¤Ï¥¿¥¤¥à¥«¡¼¥É¤òÁªÂò¤·¤¿»þ¤Î¤ß¡ª)";

$_lang['Name of the directory where the files will be stored<br>( no file management: empty field)'] = "¥Õ¥¡¥¤¥ë¤¬ÊÝÂ¸¤µ¤ì¤ë¥Ç¥£¥ì¥¯¥È¥êÌ¾<br>(¥Õ¥¡¥¤¥ë´ÉÍýÌµ¤·=¶õÇò)";
$_lang['absolute path to this directory (no files = empty field)'] = "¥Ç¥£¥ì¥¯¥È¥ê¤Ø¤ÎÀäÂÐ¥Ñ¥¹ (¥¢¥Ã¥×¥í¡¼¥ÉÌµ¤·=¶õÇò)";
$_lang['Time card'] = "¥¿¥¤¥à¥«¡¼¥É";
$_lang['1 time card system,<br>&nbsp; &nbsp; 2 manual insert afterwards sends copy to the chief'] = "1=¥¿¥¤¥à¥«¡¼¥É¥·¥¹¥Æ¥à<br>
&nbsp; &nbsp; 2=¼êÆ°ÆþÎÏ¸å¡¢¥³¥Ô¡¼¤ò¥Á¡¼¥Õ¤ËÁ÷¤ë";
$_lang['Notes'] = "¥á¥â";
$_lang['Password change'] = "¥Ñ¥¹¥ï¡¼¥ÉÊÑ¹¹";
$_lang['New passwords by the user - 0: none - 1: only random passwords - 2: choose own'] = "¥æ¡¼¥¶¤Ë¤è¤ë¥Ñ¥¹¥ï¡¼¥ÉºîÀ® - 0=Ìµ¤· - 1=¥é¥ó¥À¥à¥Ñ¥¹¥ï¡¼¥É¤Î¤ß - 2=ÆÈ¼«¥Ñ¥¹¥ï¡¼¥É";
$_lang['Encrypt passwords'] = "¥Ñ¥¹¥ï¡¼¥É¤Î°Å¹æ²½";
$_lang['Login via '] = "¥í¥°¥¤¥ó»þ»ÈÍÑ¥Õ¥¡¥¤¥ë ";
$_lang['Extra page for login via SSL'] = "SSL·ÐÍ³¤Ç¤Î¥í¥°¥¤¥ó¤Ë»ÈÍÑ";
$_lang['Groups'] = "¥°¥ë¡¼¥×";
$_lang['User and module functions are assigned to groups<br>&nbsp; &nbsp; (recommended for user numbers > 40)'] = "¥æ¡¼¥¶¤È¥â¥¸¥å¡¼¥ëµ¡Ç½¤ò¥°¥ë¡¼¥×¤Ë´ØÏ¢ÉÕ¤±¤ë<br>
&nbsp; &nbsp; (¿ä¾©¥æ¡¼¥¶¿ô 40°Ê¾å)";
$_lang['User and module functions are assigned to groups'] = "¥æ¡¼¥¶¤È¥â¥¸¥å¡¼¥ëµ¡Ç½¤ò¥°¥ë¡¼¥×¤Ë´ØÏ¢ÉÕ¤±¤ë";
$_lang['Help desk'] = "¥Ø¥ë¥×¥Ç¥¹¥¯";
$_lang['Help Desk Manager / Trouble Ticket System'] = "¥Ø¥ë¥×¥Ç¥¹¥¯/¥È¥é¥Ö¥ë¥Á¥±¥Ã¥È";
$_lang['RT Option: Customer can set a due date'] = "¥ê¥¯¥¨¥¹¥È: ¸ÜµÒ¤¬´ü¸Â¤òÀßÄê²ÄÇ½";
$_lang['RT Option: Customer Authentification'] = "¥ê¥¯¥¨¥¹¥È: ¸ÜµÒ¤ÎÇ§¾Ú";
$_lang['0: open to all, email-address is sufficient<br>1: registered contact enter his family name<br>2: his email'] = "0=Á´°÷¡¢¥á¡¼¥ë¥¢¥É¥ì¥¹¤¬É¬Í×¡¢1= ¸ÜµÒ¤ÏÏ¢ÍíÀè¥ê¥¹¥È¤ËÆþ¤Ã¤Æ¤¤¤Ê¤±¤ì¤Ð¤Ê¤é¤Ê¤¤¡¢Ì¾Á°¤òÆþ¤ì¤ëÉ¬Í×¤¢¤ê";
$_lang['RT Option: Assigning request'] = "¥ê¥¯¥¨¥¹¥È: ¥ê¥¯¥¨¥¹¥È»ñ³Ê";
$_lang['0: by everybody, 1: only by persons with status chief'] = "0=Á´°÷¡¢1=¥Á¡¼¥Õ¤Î¤ß";
$_lang['Email Address of the support'] = "¥µ¥Ý¡¼¥È¤Î¥á¡¼¥ë¥¢¥É¥ì¥¹";
$_lang['Scramble filenames'] = "¥Õ¥¡¥¤¥ëÌ¾¤Î¥¹¥¯¥é¥ó¥Ö¥ë";
$_lang['creates scrambled filenames on the server<br>assigns previous name at download'] = "¥Õ¥¡¥¤¥ë¤Î¥¹¥¯¥é¥ó¥Ö¥ëÌ¾¤ò¥µ¡¼¥Ð¾å¤ËºîÀ®<br>
¥À¥¦¥ó¥í¡¼¥É»þ¤Ë¥¹¥¯¥é¥ó¥Ö¥ëÁ°¤Î¥Õ¥¡¥¤¥ëÌ¾¤ò³äÅö¤Æ¤ë";

$_lang['0: last name, 1: short name, 2: login name'] = "0=Ì¾»ú 1=¾ÊÎ¬Ì¾ 2=¥í¥°¥¤¥óÌ¾";
$_lang['Prefix for table names in db'] = "Prefix for table names in db";
$_lang['Alert: Cannot create file config.inc.php!<br>Installation directory needs rwx access for your server and rx access to all others.'] = "·Ù¹ð: config.inc.php¤òºî¤ì¤Þ¤»¤ó¡ª<br>
¥µ¡¼¥Ð¤Î¥¤¥ó¥¹¥È¡¼¥ëÀè¥Ç¥£¥ì¥¯¥È¥ê¤Ïrwx¥¢¥¯¥»¥¹¸¢¡¢Â¾¤Î¥Õ¥¡¥¤¥ë¤Ïrx¥¢¥¯¥»¥¹¸¢¤¬É¬Í×¤Ç¤¹¡£";
$_lang['Location of the database'] = "¥Ç¡¼¥¿¥Ù¡¼¥¹¤Î¥í¥±¡¼¥·¥ç¥ó";
$_lang['Type of database system'] = "¥Ç¡¼¥¿¥Ù¡¼¥¹¤Î¼ïÎà";
$_lang['Username for the access'] = "¥æ¡¼¥¶Ì¾";
$_lang['Password for the access'] = "¥Ñ¥¹¥ï¡¼¥É";
$_lang['Name of the database'] = "¥Ç¡¼¥¿¥Ù¡¼¥¹Ì¾";
$_lang['Prefix for database table names'] = "Prefix for database table names";
$_lang['First background color'] = "ÇØ·Ê¿§1";
$_lang['Second background color'] = "ÇØ·Ê¿§2";
$_lang['Third background color'] = "ÇØ·Ê¿§3";
$_lang['Color to mark rows'] = "Color to mark rows";
$_lang['Color to highlight rows'] = "Color to highlight rows";
$_lang['Event color in the tables'] = "¥Æ¡¼¥Ö¥ëÆâ¤Î¥¤¥Ù¥ó¥ÈÊ¸»ú";
$_lang['company icon yes = insert name of image'] = "²ñ¼Ò¥¢¥¤¥³¥ó »ÈÍÑ=¥Õ¥¡¥¤¥ëÌ¾¤òÆþÎÏ";
$_lang['URL to the homepage of the company'] = "²ñ¼Ò¥Û¡¼¥à¥Ú¡¼¥¸¤ÎURL";
$_lang['no = leave empty'] = "no=¶õÇò";
$_lang['First hour of the day:'] = "»Ï¶È»þ´Ö:";
$_lang['Last hour of the day:'] = "½ª¶È»þ´Ö:";
$_lang['An error ocurred while creating table: '] = "¥Æ¡¼¥Ö¥ë¤ÎºîÀ®Ãæ¤Ë¥¨¥é¡¼¤¬È¯À¸¤·¤Þ¤·¤¿: ";
$_lang['Table dateien (for file-handling) created'] = "¥Æ¡¼¥Ö¥ëdateien (file-handling)¤¬ºîÀ®¤µ¤ì¤Þ¤·¤¿";
$_lang['File management no = leave empty'] = "¥Õ¥¡¥¤¥ë´ÉÍý no=¶õÇò";
$_lang['yes = insert full path'] = "yes=¥Õ¥ë¥Ñ¥¹¤òÆþÎÏ";
$_lang['and the relative path to the PHProjekt directory'] = "µÚ¤ÓPHProjekt¥Ç¥£¥ì¥¯¥È¥ê¤ÎÁêÂÐ¥Ñ¥¹";
$_lang['Table profile (for user-profiles) created'] = "¥Æ¡¼¥Ö¥ëprofile (user-profiles)¤¬ºîÀ®¤µ¤ì¤Þ¤·¤¿";
$_lang['User Profiles yes = 1, no = 0'] = "¥×¥í¥Õ¥£¡¼¥ë yes=1, no=0";
$_lang['Table todo (for todo-lists) created'] = "¥Æ¡¼¥Ö¥ëtodo(todo-lists)¤¬ºîÀ®¤µ¤ì¤Þ¤·¤¿";
$_lang['Todo-Lists yes = 1, no = 0'] = "»Å»ö yes=1, no=0";
$_lang['Table forum (for discssions etc.) created'] = "¥Æ¡¼¥Ö¥ëforum (discssions etc.)¤¬ºîÀ®¤µ¤ì¤Þ¤·¤¿";
$_lang['Forum yes = 1, no = 0'] = "·Ç¼¨ÈÄ yes=1, no=0";
$_lang['Table votum (for polls) created'] = "¥Æ¡¼¥Ö¥ëvotum (votes)¤¬ºîÀ®¤µ¤ì¤Þ¤·¤¿";
$_lang['Voting system yes = 1, no = 0'] = "ÅêÉ¼ yes=1, no=0";
$_lang['Table lesezeichen (for bookmarks) created'] = "¥Æ¡¼¥Ö¥ëlesezeichen (bookmarks)¤¬ºîÀ®¤µ¤ì¤Þ¤·¤¿";
$_lang['Bookmarks yes = 1, no = 0'] = "¥Ö¥Ã¥¯¥Þ¡¼¥¯ yes=1, no=0";
$_lang['Table ressourcen (for management of additional ressources) created'] = "¥Æ¡¼¥Ö¥ëressourcen (management of additional ressources)¤¬ºîÀ®¤µ¤ì¤Þ¤·¤¿";
$_lang['Resources yes = 1, no = 0'] = "¥ê¥½¡¼¥¹ yes=1, no=0";
$_lang['Table projekte (for project management) created'] = "¥Æ¡¼¥Ö¥ë 'projekte' (for project management)¤¬ºîÀ®¤µ¤ì¤Þ¤·¤¿";
$_lang['Table contacts (for external contacts) created'] = "¥Æ¡¼¥Ö¥ëcontacts (for external contacts)¤¬ºîÀ®¤µ¤ì¤Þ¤·¤¿";
$_lang['Table notes (for notes) created'] = "¥Æ¡¼¥Ö¥ënotes (for notes)¤¬ºîÀ®¤µ¤ì¤Þ¤·¤¿";
$_lang['Table timecard (for time sheet system) created'] = "¥Æ¡¼¥Ö¥ëtimecard (for time sheet system)¤¬ºîÀ®¤µ¤ì¤Þ¤·¤¿";
$_lang['Table groups (for group management) created'] = "¥Æ¡¼¥Ö¥ëgroups (for group management)¤¬ºîÀ®¤µ¤ì¤Þ¤·¤¿";
$_lang['Table timeproj (assigning work time to projects) created'] = "¥Æ¡¼¥Ö¥ëtimeproj (assigning work time to projects)¤¬ºîÀ®¤µ¤ì¤Þ¤·¤¿";
$_lang['Table rts and rts_cat (for the help desk) created'] = "¥Æ¡¼¥Ö¥ërts and rts_cat (for the help desk)¤¬ºîÀ®¤µ¤ì¤Þ¤·¤¿";
$_lang['Table mail_account, mail_attach, mail_client und mail_rules (for the mail reader) created'] = "¥Æ¡¼¥Ö¥ëmail_account, mail_attach, mail_client und mail_rules (for the mail reader)¤¬ºîÀ®¤µ¤ì¤Þ¤·¤¿";
$_lang['Table logs (for user login/-out tracking) created'] = "¥Æ¡¼¥Ö¥ëlogs (for user login/-out tracking)¤¬ºîÀ®¤µ¤ì¤Þ¤·¤¿";
$_lang['Tables contacts_profiles und contacts_prof_rel created'] = "¥Æ¡¼¥Ö¥ëcontacts_profiles¤Ècontacts_prof_rel¤¬ºîÀ®¤µ¤ì¤Þ¤·¤¿";
$_lang['Project management yes = 1, no = 0'] = "¥×¥í¥¸¥§¥¯¥È´ÉÍý yes=1, no=0";
$_lang['additionally assign resources to events'] = "ÄÉ²ÃÅª¤Ë¥ê¥½¡¼¥¹¤ò¥¤¥Ù¥ó¥È¤Ë³äÅö¤Æ¤ë";
$_lang['Address book  = 1, nein = 0'] = "¥¢¥É¥ì¥¹Ä¢  yes=1, no=0";
$_lang['Mail no = 0, only send = 1, send and receive = 2'] = "¥á¡¼¥ë no=0, Á÷¿®¤Î¤ß=1, Á÷¼õ¿®=2";
$_lang['Chat yes = 1, no = 0'] = "Chat yes = 1, no = 0";
$_lang['Name format in chat list'] = "Name format in chat list";
$_lang['0: last name, 1: first name, 2: first name, last name,<br> &nbsp; &nbsp; 3: last name, first name'] = "0: last name, 1: first name, 2: first name, last name,<br> &nbsp; &nbsp; 3: last name, first name";
$_lang['Timestamp for chat messages'] = "Timestamp for chat messages";
$_lang['users (for authentification and address management)'] = "¥Æ¡¼¥Ö¥ëusers (authentification and address management)¤¬ºîÀ®¤µ¤ì¤Þ¤·¤¿";
$_lang['Table termine (for events) created'] = "¥Æ¡¼¥Ö¥ëtermine (events)¤¬ºîÀ®¤µ¤ì¤Þ¤·¤¿";
$_lang['The following users have been inserted successfully in the table user:<br>root - (superuser with all administrative privileges)<br>test - (chief user with restricted access)'] = "¼¡¤Î¥æ¡¼¥¶¤¬¥Æ¡¼¥Ö¥ë¤ËÀµ¾ï¤Ë¥¤¥ó¥µ¡¼¥È¤µ¤ì¤Þ¤·¤¿:<br>
'root' - (Á´¤Æ¤Î´ÉÍý¸¢¸Â¤ò»ý¤Ä¥¹¡¼¥Ñ¡¼¥æ¡¼¥¶)<br>
'test' - (¥¢¥¯¥»¥¹À©¸ÂÉÕ¤Î¥Á¡¼¥Õ¥æ¡¼¥¶)";
$_lang['The group default has been created'] = "¥°¥ë¡¼¥×default¤¬ºîÀ®¤µ¤ì¤Þ¤·¤¿";
$_lang['Please do not change anything below this line!'] = "¤³¤Î¹Ô°Ê²¼¤ÏÊÑ¹¹¤·¤Ê¤¤¤Ç¤¯¤À¤µ¤¤¡ª";
$_lang['Database error'] = "¥Ç¡¼¥¿¥Ù¡¼¥¹¥¨¥é¡¼r";
$_lang['Finished'] = "´°Î»";
$_lang['There were errors, please have a look at the messages above'] = "¥¨¥é¡¼¤¬È¯À¸¤·¤Þ¤·¤¿¡¢¾åµ­¥á¥Ã¥»¡¼¥¸¤ò³ÎÇ§¤·¤Æ¤¯¤À¤µ¤¤";
$_lang['All required tables are installed and <br>the configuration file config.inc.php is rewritten<br>It would be a good idea to makea backup of this file.<br>'] = "Á´¤Æ¤ÎÉ¬Í×¤Ê¥Æ¡¼¥Ö¥ë¤Ïºî
®¤µ¤ì¤Þ¤·¤¿<br>
ÀßÄê¥Õ¥¡¥¤¥ëconfig.inc.php¤Ï½ñ´¹¤¨¤é¤ì¤Þ¤·¤¿<br>
¤³¤Î¥Õ¥¡¥¤¥ë¤Î¥Ð¥Ã¥¯¥¢¥Ã¥×¤ò¼è¤ë¤³¤È¤ò¤ª¾©¤á¤·¤Þ¤¹¡£<br>";
$_lang['The administrator root has the password root. Please change his password here:'] = "´ÉÍý¼Ôroot¤Î½é´ü¥Ñ¥¹¥ï¡¼¥É¤Ïroot¤Ç¤¹¡£¤³¤³¤ÇÊÌ¤Î¥Ñ¥¹¥ï¡¼¥É¤ËÊÑ¹¹¤·¤Æ¤¯¤À¤µ¤¤:";
$_lang['The user test is now member of the group default.<br>Now you can create new groups and add new users to the group'] = "¥æ¡¼¥¶test¤Ï¥°¥ë¡¼¥×default¤Î¥á¥ó¥Ð¡¼¤Ç¤¹¡£<br>
¿·µ¬¥°¥ë¡¼¥×¤òºîÀ®¤·¤Æ¡¢ºîÀ®¤·¤¿¥æ¡¼¥¶¤òÄÉ²Ã¤¹¤ë¤³¤È¤¬½ÐÍè¤Þ¤¹¡£";
$_lang['To use PHProject with your Browser go to <b>index.php</b><br>Please test your configuration, especially the modules Mail and Files.'] = "PHProject¤ò»È¤¦¾ì¹ç¤Ï¡¢¥Ö¥é¥¦¥¶¤Ç<b>index.php</b>¤Ë¥¢¥¯¥»¥¹¤·¤Æ¤¯¤À¤µ¤¤<br>
ÀßÄê¡¢ÆÃ¤Ë¥á¡¼¥ë¤È¥Õ¥¡¥¤¥ë¤ò³ÎÇ§¤·¤Æ¤¯¤À¤µ¤¤¡£";

$_lang['Alarm x minutes before the event'] = "¥¤¥Ù¥ó¥È¤ÎxÊ¬Á°¤ËÄÌÃÎ¤¹¤ë";
$_lang['Additional Alarmbox'] = "ÄÉ²ÃÅªÄÌÃÎ¥Ü¥Ã¥¯¥¹";
$_lang['Mail to the chief'] = "¥Á¡¼¥Õ¤Ë¥á¡¼¥ë¤¹¤ë";
$_lang['Out/Back counts as: 1: Pause - 0: Workingtime'] = "³°½Ð/µ¢¼Ò ¤Î¼è°·¤¤ 1=Ää»ß - 0:»Å»ö»þ´Ö";
$_lang['Passwords will now be encrypted ...'] = "¥Ñ¥¹¥ï¡¼¥É¤ò°Å¹æ²½¤·¤Æ¤¤¤Þ¤¹ ...";
$_lang['Filenames will now be crypted ...'] = "¥Õ¥¡¥¤¥ëÌ¾¤ò°Å¹æ²½¤·¤Æ¤¤¤Þ¤¹ ...";
$_lang['Do you want to backup your database right now? (And zip it together with the config.inc.php ...)<br>Of course I will wait!'] = "¥Ç¡¼¥¿¥Ù¡¼¥¹¤òÄ¾¤Á¤Ë¥Ð¥Ã¥¯¥¢¥Ã¥×¤·¤Þ¤¹¤«¡© (config.inc.php¤È°ì½ï¤Ëzip°µ½Ì¤·¤Þ¤¹ ...)<br>
¤â¤Á¤í¤óÂÔ¤Á¤Þ¤¹¡ª";
$_lang['Next'] = "¼¡¤Ø";
$_lang['Notification on new event in others calendar'] = "Â¾¤Î¥«¥ì¥ó¥À¡¼¤Ë¿·µ¬¥¤¥Ù¥ó¥È¤¬ÄÉ²Ã¤µ¤ì¤¿»þ¤ËÄÌÃÎ";
$_lang['Path to sendfax'] = "FAXÁ÷¿®Àè";
$_lang['no fax option: leave blank'] = "FAX¤ò»ÈÍÑ¤·¤Ê¤¤¾ì¹ç=¶õÇò";
$_lang['Please read the FAQ about the installation with postgres'] = "postgres¤Ç¥¤¥ó¥¹¥È¡¼¥ë¤¹¤ë¾ì¹ç¤ÏFAQ¤ò¤ªÆÉ¤ß¤¯¤À¤µ¤¤";
$_lang['Length of short names<br> (Number of letters: 3-6)'] = "¾ÊÎ¬Ì¾¤ÎÄ¹¤µ<br> (Ê¸»ú¿ô: 3-6)";
$_lang['If you want to install PHProjekt manually, you find<a href=http://www.phprojekt.com/files/sql_dump.tar.gz target=_blank>here</a> a mysql dump and a default config.inc.php'] = "PHProjekt¤ò¼êÆ°¤Ç¥¤¥ó¥¹¥È¡¼¥ë¤·¤¿¤¤¾ì¹ç¤Ï¡¢ <a href='http://www.phprojekt.com/files/sql_dump.tar.gz' target=_blank>¤³¤³</a>¤Ç
mysql¥À¥ó¥×¤È¥Ç¥Õ¥©¥ë¥Èconfig.inc.php¤òÆþ¼ê½ÐÍè¤Þ¤¹¡£";
$_lang['The server needs the privilege to write to the directories'] = "¥Ç¥£¥ì¥¯¥È¥ê½ñ¹þ¤ß¸¢¸Â¤¬É¬Í×¤Ç¤¹";
$_lang['Header groupviews'] = "¥°¥ë¡¼¥×¾È²ñ¤Î¥Ø¥Ã¥À";
$_lang['name, F.'] = "Ì¾»ú";
$_lang['shortname'] = "¾ÊÎ¬Ì¾";
$_lang['loginname'] = "¥í¥°¥¤¥óÌ¾";
$_lang['Please create the file directory'] = "¥Ç¥£¥ì¥¯¥È¥ê¤òºîÀ®¤·¤Æ¤¯¤À¤µ¤¤";
$_lang['default mode for forum tree: 1 - open, 0 - closed'] = "·Ç¼¨ÈÄ¥Ä¥ê¡¼¤Î¥Ç¥Õ¥©¥ë¥È: 1=open(+) 0=closed(-)";
$_lang['Currency symbol'] = "ÄÌ²ß¥·¥ó¥Ü¥ë (±ß¤Î¾ì¹ç ¡õ£ù£å£î)";
$_lang['current'] = "Îã";
$_lang['Default size of form elements'] = "Default size of form elements";
$_lang['use LDAP'] = "LDAP¤ò»ÈÍÑ";
$_lang['Allow parallel events'] = "Æ±»þÊÂ¹Ô¤Î¥¤¥Ù¥ó¥È¤òµö²Ä¤¹¤ë";
$_lang['Timezone difference [h] Server - user'] = "¥¿¥¤¥à¥¾¡¼¥ó³Êº¹ [»þ´Ö] ¥µ¡¼¥Ð - ¥æ¡¼¥¶";
$_lang['Timezone'] = "¥¿¥¤¥à¥¾¡¼¥ó";
$_lang['max. hits displayed in search module'] = "¸¡º÷³ºÅö¥Ç¡¼¥¿¤ÎºÇÂçÉ½¼¨·ï¿ô";
$_lang['Time limit for sessions'] = "Time limit for sessions";
$_lang['0: default mode, 1: Only for debugging mode'] = "0: default mode, 1: Only for debugging mode";
$_lang['Enables mail notification on new elements'] = "Enables mail notification on new elements";
$_lang['Enables versioning for files'] = "Enables versioning for files";
$_lang['no link to contacts in other modules'] = "no link to contacts in other modules";
$_lang['Highlight list records with mouseover'] = "Highlight list records with 'mouseover'";
$_lang['Track user login/logout'] = "Track user login/logout";
$_lang['Access for all groups'] = "Access for all groups";
$_lang['Option to release objects in all groups'] = "Option to release objects in all groups";
$_lang['Default access mode: private=0, group=1'] = "Default access mode: private=0, group=1"; 
$_lang['Adds -f as 5. parameter to mail(), see php manual'] = "Adds '-f' as 5. parameter to mail(), see php manual";
$_lang['end of line in body; e.g. \r\n (conform to RFC 2821 / 2822)'] = "end of line in body; e.g. \\r\\n (conform to RFC 2821 / 2822)";
$_lang['end of header line; e.g. \r\n (conform to RFC 2821 / 2822)'] = "end of header line; e.g. \\r\\n (conform to RFC 2821 / 2822)";
$_lang['Sendmail mode: 0: use mail(); 1: use socket'] = "Sendmail mode: 0: use mail(); 1: use socket";
$_lang['the real address of the SMTP mail server, you have access to (maybe localhost)'] = "the real address of the SMTP mail server, you have access to (maybe localhost)";
$_lang['name of the local server to identify it while HELO procedure'] = "name of the local server to identify it while HELO procedure";
$_lang['Authentication'] = "Authentication";
$_lang['fill out in case of authentication via POP before SMTP'] = "fill out in case of authentication via POP before SMTP";
$_lang['real username for POP before SMTP'] = "real username for POP before SMTP";
$_lang['password for this pop account'] = "password for this pop account"; 
$_lang['the POP server'] = "the POP server";
$_lang['fill out in case of SMTP authentication'] = "fill out in case of SMTP authentication";
$_lang['real username for SMTP auth'] = "real username for SMTP auth";
$_lang['password for this account'] = "password for this account";
$_lang['SMTP account data (only needed in case of socket)'] = "SMTP account data (only needed in case of socket)";
$_lang['No Authentication'] = "No Authentication"; 
$_lang['with POP before SMTP'] = "with POP before SMTP";
$_lang['SMTP auth (via socket only!)'] = "SMTP auth (via socket only!)"; 
$_lang['Log history of records'] = "Log history of records";
$_lang['Send'] = " Senden";
$_lang['Host-Path'] = "Host-Path";
$_lang['Installation directory'] = "Installation directory";
$_lang['0 Date assignment by chief, 1 Invitation System'] = "0 Date assignment by chief, 1 Invitation System";
$_lang['0 Date assignment by chief,<br>&nbsp;&nbsp;&nbsp;&nbsp; 1 Invitation System'] = "0 Date assignment by chief,<br>&nbsp;&nbsp;&nbsp;&nbsp; 1 Invitation System";
$_lang['Default write access mode: private=0, group=1'] = "Default write access mode: private=0, group=1";
$_lang['Select-Option accepted available = 1, not available = 0'] = "Select-Option accepted available = 1, not available = 0";
$_lang['absolute path to host, e.g. http://myhost/'] = "absolute path to host, e.g. http://myhost/";
$_lang['installation directory below host, e.g. myInstallation/of/phprojekt5/'] = "installation directory below host, e.g. myInstallation/of/phprojekt5/";

// l.php
$_lang['Resource List'] = "¥ê¥½¡¼¥¹¥ê¥¹¥È";
$_lang['Event List'] = "¥¤¥Ù¥ó¥È¥ê¥¹¥È";
$_lang['Calendar Views'] = "¥«¥ì¥ó¥À¡¼";

$_lang['Personnel'] = "¼Ò°÷";

$_lang['Create new event'] = "¥¤¥Ù¥ó¥È¤ÎºîÀ®";
$_lang['Day'] = "ÆüÉÕ";

$_lang['Until'] = "To";

$_lang['Note'] = "¥á¥â";
$_lang['Project'] = "¥×¥í¥¸¥§¥¯¥È";
$_lang['Res'] = "Res";
$_lang['Once'] = "°ì²ó¤Î¤ß";
$_lang['Daily'] = "ËèÆü";
$_lang['Weekly'] = "Ëè½µ";
$_lang['Monthly'] = "Ëè·î";
$_lang['Yearly'] = "ËèÇ¯";

$_lang['Create'] = "ºîÀ®";

$_lang['Begin'] = "³«»Ï";
$_lang['Out of office'] = "³°½Ð";
$_lang['Back in office'] = "µ¢¼Ò";
$_lang['End'] = "½ªÎ»";
$_lang['@work'] = "¶ÐÌ³Ãæ";
$_lang['We'] = "½µ";
$_lang['group events'] = "¥°¥ë¡¼¥×¥¤¥Ù¥ó¥È";
$_lang['or profile'] = "OR ¥×¥í¥Õ¥£¡¼¥ë";
$_lang['All Day Event'] = "1Æü¤ÎÁ´¥¤¥Ù¥ó¥È";
$_lang['time-axis:'] = "»þ´Ö¼´:";
$_lang['vertical'] = "¿âÄ¾";
$_lang['horizontal'] = "¿åÊ¿";
$_lang['Horz. Narrow'] = "¿åÊ¿(¾ÊÎ¬)";
$_lang['-interval:'] = "-´Ö³Ö:";
$_lang['Self'] = "¼«Ê¬";

$_lang['...write'] = "...½ñ¹þ¤à";

$_lang['Calendar dates'] = "Calendar dates";
$_lang['List'] = "List";
$_lang['Year'] = "Year";
$_lang['Month'] = "Month";
$_lang['Week'] = "Week";
$_lang['Substitution'] = "Substitution";
$_lang['Substitution for'] = "Substitution for";
$_lang['Extended&nbsp;selection'] = "Extended&nbsp;selection";
$_lang['New Date'] = "New date entered";
$_lang['Date changed'] = "Date changed";
$_lang['Date deleted'] = "Date deleted";

// links
$_lang['Database table'] = "Database table";
$_lang['Record set'] = "Record set";
$_lang['Resubmission at:'] = "Resubmission at:";
$_lang['Set Links'] = "Links";
$_lang['From date'] = "From date";
$_lang['Call record set'] = "Call record set";

//login.php
$_lang['Please call login.php!'] = "login.php¤ò¼Â¹Ô¤·¤Æ¤¯¤À¤µ¡ª";

// m1.php
$_lang['There are other events!<br>the critical appointment is: '] = "Â¾¤Î¥¤¥Ù¥ó¥È¤¬¤¢¤ê¤Þ¤¹¡ª<br>½ÅÍ×¤ÊÍ½Äê: ";
$_lang['Sorry, this resource is already occupied: '] = "¿½¤·Ìõ¤´¤¶¤¤¤Þ¤»¤ó¡¢¤³¤Î¥ê¥½¡¼¥¹¤Ï´û¤Ë»ÈÍÑ¤µ¤ì¤Æ¤¤¤Þ¤¹: ";
$_lang[' This event does not exist.<br> <br> Please check the date and time. '] = "¤³¤Î¥¤¥Ù¥ó¥È¤ÏÂ¸ºß¤·¤Æ¤¤¤Þ¤»¤ó¡£<br> <br>Æü»þ¤ò³ÎÇ§¤·¤Æ¤¯¤À¤µ¤¤¡£";
$_lang['Please check your date and time format! '] = "ÆüÉÕ¤È»þ´Ö¤Î¥Õ¥©¡¼¥Þ¥Ã¥È¤ò³ÎÇ§¤·¤Æ¤¯¤À¤µ¤¤¡ª";
$_lang['Please check the date!'] = "ÆüÉÕ¤ò³ÎÇ§¤·¤Æ¤¯¤À¤µ¤¤¡ª";
$_lang['Please check the start time! '] = "³«»Ï»þ´Ö¤ò³ÎÇ§¤·¤Æ¤¯¤À¤µ¤¤¡ª";
$_lang['Please check the end time! '] = "½ªÎ»»þ´Ö¤ò³ÎÇ§¤·¤Æ¤¯¤À¤µ¤¤¡ª";
$_lang['Please give a text or note!'] = "ÂêÌ¾¤Þ¤¿¤Ï¥á¥â¤òÆþÎÏ¤·¤Æ¤¯¤À¤µ¤¤¡ª";
$_lang['Please check start and end time! '] = "³«»Ï¡¦½ªÎ»»þ´Ö¤ò³ÎÇ§¤·¤Æ¤¯¤À¤µ¤¤¡ª";
$_lang['Please check the format of the end date! '] = "½ªÎ»Æü¤Î¥Õ¥©¡¼¥Þ¥Ã¥È¤ò³ÎÇ§¤·¤Æ¤¯¤À¤µ¤¤¡ª";
$_lang['Please check the end date! '] = "½ªÎ»Æü¤ò³ÎÇ§¤·¤Æ¤¯¤À¤µ¤¤¡ª";





$_lang['Resource'] = "¥ê¥½¡¼¥¹";
$_lang['User'] = "¥æ¡¼¥¶";

$_lang['delete event'] = "¥¤¥Ù¥ó¥È¤Îºï½ü";
$_lang['Address book'] = "¥¢¥É¥ì¥¹Ä¢";


$_lang['Short Form'] = "¾ÊÎ¬Ì¾";

$_lang['Phone'] = "ÅÅÏÃÈÖ¹æ";
$_lang['Fax'] = "FAX";



$_lang['Bookmark'] = "¥Ö¥Ã¥¯¥Þ¡¼¥¯";
$_lang['Description'] = "ÀâÌÀ";

$_lang['Entire List'] = "Á´¥ê¥¹¥È";

$_lang['New event'] = "¿·µ¬¥¤¥Ù¥ó¥È";
$_lang['Created by'] = "ºîÀ®¼Ô:";
$_lang['Red button -> delete a day event'] = "ÀÖ¤¤¥Ü¥¿¥ó -> ¥¤¥Ù¥ó¥È¤òºï½ü";
$_lang['multiple events'] = "·«ÊÖ¤·";
$_lang['Year view'] = "Ç¯É½¼¨";
$_lang['calendar week'] = "½µ";

//m2.php
$_lang['Create &amp; Delete Events'] = "¥¤¥Ù¥ó¥È¤ÎºîÀ®¤Èºï½ü";
$_lang['normal'] = "ÄÌ¾ï";
$_lang['private'] = "¥×¥é¥¤¥Ù¡¼¥È";
$_lang['public'] = "¸øÍÑ";
$_lang['Visibility'] = "É½¼¨";

//mail module
$_lang['Please select at least one (valid) address.'] = "¾¯¤Ê¤¯¤È¤â1¤Ä¤Î(Àµ¤·¤¤)¥á¡¼¥ë¥¢¥É¥ì¥¹¤òÁªÂò¤·¤Æ¤¯¤À¤µ¤¤";
$_lang['Your mail has been sent successfully'] = "¥á¡¼¥ë¤¬Á÷¿®¤µ¤ì¤Þ¤·¤¿";
$_lang['Attachment'] = "ÅºÉÕ";
$_lang['Send single mails'] = "1ÄÌ¤Î¥á¡¼¥ë¤Ë¼õ¿®¼Ô¤Î¥¢¥É¥ì¥¹¤òÏ¢µ­";
$_lang['Does not exist'] = "Â¸ºß¤·¤Þ¤»¤ó";
$_lang['Additional number'] = "ÄÉ²ÃÈÖ¹æ";
$_lang['has been canceled'] = "¤¬¥­¥ã¥ó¥»¥ë¤µ¤ì¤Þ¤·¤¿";

$_lang['marked objects'] = "¥Á¥§¥Ã¥¯¤·¤¿¥á¡¼¥ë¤ò";
$_lang['Additional address'] = "ÄÉ²Ã¥á¡¼¥ë¥¢¥É¥ì¥¹";
$_lang['in mails'] = "¥á¡¼¥ë";
$_lang['Mail account'] = "¥á¡¼¥ë¥¢¥«¥¦¥ó¥È";
$_lang['Body'] = "ËÜÊ¸";
$_lang['Sender'] = "Á÷¿®¥á¡¼¥ë¥¢¥É¥ì¥¹";

$_lang['Receiver'] = "¼õ¿®¼Ô";
$_lang['Reply'] = "ÊÖ¿®";
$_lang['Forward'] = "Å¾Á÷";
$_lang['Access error for mailbox'] = "¥á¡¼¥ë¥Ü¥Ã¥¯¥¹¤Î¥¢¥¯¥»¥¹¥¨¥é¡¼";
$_lang['Receive'] = "¼õ¿®";
$_lang['Write'] = "¿·µ¬ºîÀ®";
$_lang['Accounts'] = "¥¢¥«¥¦¥ó¥È";
$_lang['Rules'] = "¥ë¡¼¥ë";
$_lang['host name'] = "¥Û¥¹¥ÈÌ¾";
$_lang['Type'] = "Type";
$_lang['misses'] = "misses";
$_lang['has been created'] = "¤¬ºîÀ®¤µ¤ì¤Þ¤·¤¿";
$_lang['has been changed'] = "¤¬ÊÑ¹¹¤µ¤ì¤Þ¤·¤¿";
$_lang['is in field'] = "¤¬¼¡¤Î¥Õ¥£¡¼¥ë¥É¤Ë´Þ¤Þ¤ì¤ë";
$_lang['and leave on server'] = "¼õ¿®¡§¥µ¡¼¥Ð¤Ë»Ä¤¹";
$_lang['name of the rule'] = "¥ë¡¼¥ëÌ¾";
$_lang['part of the word'] = "";
$_lang['in'] = "¢ª";
$_lang['sent mails'] = "Á÷¿®ºÑ¥á¡¼¥ë";
$_lang['Send date'] = "Á÷¿®Æü»þ";
$_lang['Received'] = "¼õ¿®Æü»þ";
$_lang['to'] = "to";
$_lang['imcoming Mails'] = "¼õ¿®¥á¡¼¥ë";
$_lang['sent Mails'] = "Á÷¿®ºÑ¥á¡¼¥ë";
$_lang['Contact Profile'] = "Ï¢ÍíÀè¥×¥í¥Õ¥£¡¼¥ë";
$_lang['unread'] = "Ì¤ÆÉ";
$_lang['view mail list'] = "¼õ¿®Êí";
$_lang['insert db field (only for contacts)'] = "¥Ç¡¼¥¿¥Ù¡¼¥¹¥Õ¥£¡¼¥ë¥É¤òÁÞÆþ (only for contacts)";
$_lang['Signature'] = "½ðÌ¾";

$_lang['SMS'] = "SMS";
$_lang['Single account query'] = "¥á¡¼¥ë³ÎÇ§";
$_lang['Notice of receipt'] = "³«ÉõÄÌÃÎ";
$_lang['Assign to project'] = "Assign to project";
$_lang['Assign to contact'] = "Assign to contact";  
$_lang['Assign to contact according to address'] = "Assign to contact according to address";
$_lang['Include account for default receipt'] = "Include account for default receipt";
$_lang['Your token has already been used.<br>If it wasnt you, who used the token please contact your administrator.'] = "Your token has already been used.<br>If it wasn't you, who used the token please contact your administrator";
$_lang['Your token has already been expired.'] = "Your token has already been expired";
$_lang['Unconfirmed Events'] = "Unconfirmed Events";
$_lang['Visibility presetting when creating an event'] = "Voreinstellung der Sichtbarkeit beim Anlegen eines Termins";
$_lang['Subject'] = "Subject";
$_lang['Content'] = "Inhalt";
$_lang['answer all'] = "answer to all";
$_lang['Create new message'] = "Create new message";
$_lang['Attachments'] = "Attachments";
$_lang['Recipients'] = "Recipients";
$_lang['file away message'] = "file away message";
$_lang['Message from:'] = "Message from:";

//notes.php
$_lang['Mail note to'] = "¥á¥â¤ò¥á¡¼¥ë¤¹¤ë";
$_lang['added'] = "ÄÉ²Ã";
$_lang['changed'] = "½¤Àµ";

// o.php
$_lang['Calendar'] = "¥«¥ì¥ó¥À¡¼";
$_lang['Contacts'] = "Ï¢ÍíÀè";


$_lang['Files'] = "¥Õ¥¡¥¤¥ë";



$_lang['Options'] = "¥ª¥×¥·¥ç¥ó";
$_lang['Timecard'] = "¥¿¥¤¥à¥«¡¼¥É";

$_lang['Helpdesk'] = "¥Ø¥ë¥×¥Ç¥¹¥¯";

$_lang['Info'] = "¾ðÊó";
$_lang['Todo'] = "»Å»ö";
$_lang['News'] = "News";
$_lang['Other'] = "¤½¤ÎÂ¾";
$_lang['Settings'] = "ÀßÄê";
$_lang['Summary'] = "³µÍ×";

// options.php
$_lang['Description:'] = "ÀâÌÀ:";
$_lang['Comment:'] = "¥³¥á¥ó¥È:";
$_lang['Insert a valid Internet address! '] = "Àµ¤·¤¤¥á¡¼¥ë¥¢¥É¥ì¥¹¤òÆþÎÏ¤·¤Æ¤¯¤À¤µ¤¤¡ª";
$_lang['Please specify a description!'] = "ÀâÌÀ¤òÆþÎÏ¤·¤Æ¤¯¤À¤µ¤¤¡ª";
$_lang['This address already exists with a different description'] = "¤³¤Î¥á¡¼¥ë¥¢¥É¥ì¥¹¤Ï´û¤ËÂ¾¤ÎÌ¾¾Î¤Ç»ÈÍÑ¤µ¤ì¤Æ¤¤¤Þ¤¹";
$_lang[' already exists. '] = " ´û¤ËÅÐÏ¿¤µ¤ì¤Æ¤¤¤Þ¤¹¡£ ";
$_lang['is taken to the bookmark list.'] = " ¤¬¥Ö¥Ã¥¯¥Þ¡¼¥¯¤ËÄÉ²Ã¤µ¤ì¤Þ¤·¤¿¡£";
$_lang[' is changed.'] = " ¤¬ÊÑ¹¹¤µ¤ì¤Þ¤·¤¿¡£";
$_lang[' is deleted.'] = " ¤¬ºï½ü¤µ¤ì¤Þ¤·¤¿¡£";
$_lang['Please specify a description! '] = "ÀâÌÀ¤òÆþÎÏ¤·¤Æ¤¯¤À¤µ¤¤¡ª";
$_lang['Please select at least one name! '] = "¾¯¤Ê¤¯¤È¤â1¤Ä¤ÎÌ¾Á°¤òÁªÂò¤·¤Æ¤¯¤À¤µ¤¤¡ª ";
$_lang[' is created as a profile.<br>'] = " ¤¬¥×¥í¥Õ¥£¡¼¥ë¤È¤·¤ÆºîÀ®¤µ¤ì¤Þ¤·¤¿¡£<br>";
$_lang['is changed.<br>'] = "¤¬ÊÑ¹¹¤µ¤ì¤Þ¤·¤¿¡£<br>";
$_lang['The profile has been deleted.'] = "¥×¥í¥Õ¥£¡¼¥ë¤¬ºï½ü¤µ¤ì¤Þ¤·¤¿¡£";
$_lang['Please specify the question for the poll! '] = "ÅêÉ¼¤Î¼ÁÌä¤òÆþÎÏ¤·¤Æ¤¯¤À¤µ¤¤¡ª";
$_lang['You should give at least one answer! '] = "¾¯¤Ê¤¯¤È¤â1¤Ä°Ê¾å¤ÎÅú¤¨¤òÆþÎÏ¤·¤Æ¤¯¤À¤µ¤¤¡ª";
$_lang['Your call for votes is now active. '] = "¤¢¤Ê¤¿¤ÎÅêÉ¼¤Ï¸½ºß¼Â»ÜÃæ¤Ç¤¹¡£";
$_lang['<h2>Bookmarks</h2>In this section you can create, modify or delete bookmarks:'] = "<h2>¥Ö¥Ã¥¯¥Þ¡¼¥¯</h2>¤³¤³¤Ç¤Ï¥Ö¥Ã¥¯¥Þ¡¼¥¯¤ÎºîÀ®¡¦½¤Àµ¡¦ºï½ü¤ò¹Ô¤¤¤Þ¤¹:";
$_lang['Create'] = "ºîÀ®";


$_lang['<h2>Profiles</h2>In this section you can create, modify or delete profiles:'] = "<h2>¥×¥í¥Õ¥£¡¼¥ë</h2>¤³¤³¤Ç¤Ï¥×¥í¥Õ¥£¡¼¥ë¤ÎºîÀ®¡¦½¤Àµ¡¦ºï½ü¤ò¹Ô¤¤¤Þ¤¹:";
$_lang['<h2>Voting Formula</h2>'] = "<h2>ÅêÉ¼</h2>";
$_lang['In this section you can create a call for votes.'] = "¤³¤³¤Ç¤ÏÅêÉ¼¤ÎºîÀ®¤ò¹Ô¤¤¤Þ¤¹¡£";
$_lang['Question:'] = "¼ÁÌä:";
$_lang['just one <b>Alternative</b> or'] = "Âò°ì";
$_lang['several to choose?'] = "Ê£¿ôÁªÂò";

$_lang['Participants:'] = "»²²Ã¼Ô:";

$_lang['<h3>Password Change</h3> In this section you can choose a new random generated password.'] = "<h3>¥Ñ¥¹¥ï¡¼¥É¤ÎÊÑ¹¹</h3> ¤³¤³¤Ç¤Ï¥é¥ó¥À¥à¤ËºîÀ®¤µ¤ì¤¿¥Ñ¥¹¥ï¡¼¥É¤òÁªÂò¤Ç¤­¤Þ¤¹¡£";
$_lang['Old Password'] = "µì¥Ñ¥¹¥ï¡¼¥É";
$_lang['Generate a new password'] = "¿·¥Ñ¥¹¥ï¡¼¥É¤òÀ¸À®¤¹¤ë";
$_lang['Save password'] = "¥Ñ¥¹¥ï¡¼¥É¤òÊÝÂ¸¤¹¤ë";
$_lang['Your new password has been stored'] = "¿·¥Ñ¥¹¥ï¡¼¥É¤¬ÊÝÂ¸¤µ¤ì¤Þ¤·¤¿";
$_lang['Wrong password'] = "Àµ¤·¤¤¥Ñ¥¹¥ï¡¼¥É¤òÆþÎÏ¤·¤Æ¤¯¤À¤µ¤¤";
$_lang['Delete poll'] = "ÅêÉ¼¤Îºï½ü";
$_lang['<h4>Delete forum threads</h4> Here you can delete your own threads<br>Only threads without a comment will appear.'] = "<h4>Åê¹Æ¤Îºï½ü</h4> ¤³¤³¤Ç¤Ï¤¢¤Ê¤¿¤ÎÅê¹Æ¤òºï½ü¤¹¤ë¤³¤È¤¬½ÐÍè¤Þ¤¹¡£<br>
¥³¥á¥ó¥È¤¬É½¼¨¤µ¤ì¤Æ¤¤¤Ê¤¤Åê¹Æ¤Î¤ßºï½ü½ÐÍè¤Þ¤¹¡£";

$_lang['Old password'] = "µì¥Ñ¥¹¥ï¡¼¥É";
$_lang['New Password'] = "¿·¥Ñ¥¹¥ï¡¼¥É";
$_lang['Retype new password'] = "¿·¥Ñ¥¹¥ï¡¼¥É¤òºÆÆþÎÏ";
$_lang['The new password must have 5 letters at least'] = "¿·¥Ñ¥¹¥ï¡¼¥É¤Ï¾¯¤Ê¤¯¤È¤â5Ê¸»ú°Ê¾å¤Ç¤Ê¤±¤ì¤Ð¤Ê¤ê¤Þ¤»¤ó";
$_lang['You didnt repeat the new password correctly'] = "¿·¥Ñ¥¹¥ï¡¼¥É¤¬¹çÃ×¤·¤Þ¤»¤ó";

$_lang['Show bookings'] = "Í½Ìó¤òÉ½¼¨";
$_lang['Valid characters'] = "Í­¸úÊ¸»úÎó";
$_lang['Suggestion'] = "À¸À®¥Ñ¥¹¥ï¡¼¥É";
$_lang['Put the word AND between several phrases'] = "Ê£¿ô¥­¡¼¥ï¡¼¥É¡§ AND ¤Ç¶èÀÚ¤Ã¤Æ¤¯¤À¤µ¤¤"; // translators: please leave the word AND as it is
$_lang['Write access for calendar'] = "¥«¥ì¥ó¥À¡¼¤Ø¤Î½ñ¹þ¤ß¸¢";
$_lang['Write access for other users to your calendar'] = "¤¢¤Ê¤¿¤Î¥«¥ì¥ó¥À¡¼¤Ë½ñ¹þ¤ß²ÄÇ½¤Ê¥æ¡¼¥¶";
$_lang['User with chief status still have write access'] = "¥Á¡¼¥Õ¤Ë¤Ï½ñ¹þ¤ß¸¢¤¬¤¢¤ê¤Þ¤¹";

// projects
$_lang['Project Listing'] = "¥×¥í¥¸¥§¥¯¥È¥ê¥¹¥È";
$_lang['Project Name'] = "¥×¥í¥¸¥§¥¯¥ÈÌ¾";


$_lang['o_files'] = "Files";
$_lang['o_notes'] = "Notes";
$_lang['o_projects'] = "Projects";
$_lang['o_todo'] = "Todo";
$_lang['Copyright']="Copyright";
$_lang['Links'] = "Links";
$_lang['New profile'] = "Neuer Verteiler";
$_lang['In this section you can choose a new random generated password.'] = "In this section you can choose a new random generated password.";
$_lang['timescale'] = "timescale";
$_lang['Manual Scaling'] = "Manual scaling";
$_lang['column view'] = "column view";
$_lang['display format'] = "display format";
$_lang['for chart only'] = "For chart only:";
$_lang['scaling:'] = "scling:";
$_lang['colours:'] = "colours";
$_lang['display project colours'] = "display project colours";
$_lang['weekly'] = "weekly";
$_lang['monthly'] = "monthly";
$_lang['annually'] = "annually";
$_lang['automatic'] = "automatic";
$_lang['New project'] = "New project";
$_lang['Basis data'] = "Basis data";
$_lang['Categorization'] = "Categorization";
$_lang['Real End'] = "Real End";
$_lang['Participants'] = "»²²Ã¼Ô";
$_lang['Priority'] = "Í¥ÀèÅÙ";
$_lang['Status'] = "¾õÂÖ";
$_lang['Last status change'] = "ºÇ½ª¹¹¿·Æü";
$_lang['Leader'] = "¥ê¡¼¥À¡¼";
$_lang['Statistics'] = "Åý·×";
$_lang['My Statistic'] = "¼«¸ÊÅý·×";

$_lang['Person'] = "»áÌ¾";
$_lang['Hours'] = "»þ´Ö";
$_lang['Project summary'] = "¥×¥í¥¸¥§¥¯¥È³µÍ×";
$_lang[' Choose a combination Project/Person'] = "¥×¥í¥¸¥§¥¯¥È/¿Í¤ÎÁÈ¹ç¤»¤òÁªÂò¤·¤Æ¤¯¤À¤µ¤¤";
$_lang['(multiple select with the Ctrl/Cmd-key)'] = "(Ê£¿ôÁªÂò Ctrl¥­¡¼)";

$_lang['Persons'] = "¿Í";
$_lang['Begin:'] = "³«»Ï:";
$_lang['End:'] = "½ªÎ»:";
$_lang['All'] = "Á´¤Æ¤Î";
$_lang['Work time booked on'] = "Í½ÌóºÑ¤ßºî¶È»þ´Ö";
$_lang['Sub-Project of'] = "¿Æ¥×¥í¥¸¥§¥¯¥È";
$_lang['Aim'] = "ÌÜÅª";
$_lang['Contact'] = "Ï¢ÍíÀè";
$_lang['Hourly rate'] = "»þµë";
$_lang['Calculated budget'] = "Í½»»";
$_lang['New Sub-Project'] = "¥µ¥Ö¡¦¥×¥í¥¸¥§¥¯¥È¤ÎºîÀ®";
$_lang['Booked To Date'] = "¸½¶·";
$_lang['Budget'] = "Í½»»";
$_lang['Detailed list'] = "¾ÜºÙ¥ê¥¹¥È";
$_lang['Gantt'] = "¥®¥ã¥ó¥È¥Á¥ã¡¼¥È";
$_lang['offered'] = "ÃíÊ¸ºÑ";
$_lang['ordered'] = "Äó°ÆÃæ";
$_lang['Working'] = "½èÍýÃæ";
$_lang['ended'] = "½ªÎ»";
$_lang['stopped'] = "Ãæ»ß";
$_lang['Re-Opened'] = "ºÆ½èÍýÃæ";
$_lang['waiting'] = "ÂÔµ¡Ãæ";
$_lang['Only main projects'] = "¥á¥¤¥ó¥×¥í¥¸¥§¥¯¥È¤Î¤ß";
$_lang['Only this project'] = "¥×¥í¥¸¥§¥¯¥È»ØÄê";
$_lang['Begin > End'] = "³«»Ï > ½ªÎ»";
$_lang['ISO-Format: yyyy-mm-dd'] = "ISO-¥Õ¥©¡¼¥Þ¥Ã¥È: yyyy-mm-dd";
$_lang['The timespan of this project must be within the timespan of the parent project. Please adjust'] = "¤³¤Î¥×¥í¥¸¥§¥¯¥È¤Î»þ´ÖÈÏ°Ï¤Ï¿Æ¥×¥í¥¸¥§¥¯¥È¤ÎÈÏ°ÏÆâ¤Ë¼ý¤Þ¤ëÉ¬Í×¤¬¤¢¤ê¤Þ¤¹¡£ÊÑ¹¹¤·¤Æ¤¯¤À¤µ¤¤";
$_lang['Please choose at least one person'] = "¾¯¤Ê¤¯¤È¤â1¿ÍÁªÂò¤·¤Æ¤¯¤À¤µ¤¤";
$_lang['Please choose at least one project'] = "¾¯¤Ê¤¯¤È¤â1¤Ä¤Î¥×¥í¥¸¥§¥¯¥È¤òÁªÂò¤·¤Æ¤¯¤À¤µ¤¤";
$_lang['Dependency'] = "°ÍÂ¸ÅÙ";
$_lang['Previous'] = "Á°¤Ø";

$_lang['cannot start before the end of project'] = "¥×¥í¥¸¥§¥¯¥È¤Î½ªÎ»ÆüÁ°¤Ë³«»ÏÉÔ²Ä";
$_lang['cannot start before the start of project'] = "¥×¥í¥¸¥§¥¯¥È¤Î³«»ÏÆüÁ°¤Ë³«»ÏÉÔ²Ä";
$_lang['cannot end before the start of project'] = "¥×¥í¥¸¥§¥¯¥È¤Î³«»ÏÆüÁ°¤Ë½ªÎ»ÉÔ²Ä";
$_lang['cannot end before the end of project'] = "¥×¥í¥¸¥§¥¯¥È¤Î½ªÎ»ÆüÁ°¤Ë½ªÎ»ÉÔ²Ä";
$_lang['Warning, violation of dependency'] = "·Ù¹ð:°ÍÂ¸ÅÙ°ãÈ¿";
$_lang['Container'] = "Container";
$_lang['External project'] = "³°Éô¥×¥í¥¸¥§¥¯¥È";
$_lang['Automatic scaling'] = "¼«Æ°¥¹¥±¡¼¥ê¥ó¥°";
$_lang['Legend'] = "ÍúÎò";
$_lang['No value'] = "Ìµ»ØÄê";
$_lang['Copy project branch'] = "Copy project branch";
$_lang['Copy this element<br> (and all elements below)'] = "Copy this element<br> (and all elements below)";
$_lang['And put it below this element'] = "And put it below this element";
$_lang['Edit timeframe of a project branch'] = "Edit timeframe of a project branch"; 

$_lang['of this element<br> (and all elements below)'] = "of this element<br> (and all elements below)";  
$_lang['by'] = "by";
$_lang['Probability'] = "Probability";
$_lang['Please delete all subelements first'] = "Please delete all subprojects first";
$_lang['Assignment'] ="Assignment";
$_lang['display'] = "Display";
$_lang['Normal'] = "Normal";
$_lang['sort by date'] = "Sort by date";
$_lang['sort by'] = "Sort by";
$_lang['Calculated budget has a wrong format'] = "Calculated budget has a wrong format";
$_lang['Hourly rate has a wrong format'] = "Hourly rate has a wrong format";

// r.php
$_lang['please check the status!'] = "¾õÂÖ¤ò³ÎÇ§¤·¤Æ¤¯¤À¤µ¤¤¡ª";
$_lang['Todo List: '] = "»Å»ö: ";
$_lang['New Remark: '] = "¿·µ¬È÷¹Í: ";
$_lang['Delete Remark '] = "È÷¹Í¤Îºï½ü ";
$_lang['Keyword Search'] = "¸¡º÷";
$_lang['Events'] = "¥¤¥Ù¥ó¥È";
$_lang['the forum'] = "·Ç¼¨ÈÄ";
$_lang['the files'] = "¥Õ¥¡¥¤¥ë";
$_lang['Addresses'] = "¥¢¥É¥ì¥¹";
$_lang['Extended'] = "¾ÜºÙ";
$_lang['all modules'] = "Á´¤Æ¤Î¥â¥¸¥å¡¼¥ë";
$_lang['Bookmarks:'] = "¥Ö¥Ã¥¯¥Þ¡¼¥¯:";
$_lang['List'] = "¥ê¥¹¥È";
$_lang['Projects:'] = "¥×¥í¥¸¥§¥¯¥È:";

$_lang['Deadline'] = "ºÇ½ª´ü¸Â";

$_lang['Polls:'] = "ÅêÉ¼:";

$_lang['Poll created on the '] = "ÅêÉ¼À½ºîÆü ";


// reminder.php
$_lang['Starts in'] = "³«»ÏÍ½Äê";
$_lang['minutes'] = "Ê¬";
$_lang['No events yet today'] = "º£Æü¤Î¥¤¥Ù¥ó¥È¤Ï¤¢¤ê¤Þ¤»¤ó";
$_lang['New mail arrived'] = "¿·¤·¤¤¥á¡¼¥ë¤¬ÅþÃå¤·¤Þ¤·¤¿";

//ress.php

$_lang['List of Resources'] =  "¥ê¥½¡¼¥¹¥ê¥¹¥È";
$_lang['Name of Resource'] = "¥ê¥½¡¼¥¹Ì¾";
$_lang['Comments'] =  "¥³¥á¥ó¥È";


// roles
$_lang['Roles'] = "¸¢¸Â";
$_lang['No access'] = "¥¢¥¯¥»¥¹¸¢Ìµ¤·";
$_lang['Read access'] = "ÆÉ¼è¸¢";

$_lang['Role'] = "¸¢¸Â";

// helpdesk - rts
$_lang['Request'] = "¥ê¥¯¥¨¥¹¥È";

$_lang['pending requests'] = "Ì¤½èÍý¤Î¥ê¥¯¥¨¥¹¥È";
$_lang['show queue'] = "¥­¥å¡¼¤òÉ½¼¨";
$_lang['Search the knowledge database'] = "ÃÎ¼±¥Ç¡¼¥¿¥Ù¡¼¥¹¤ò¸¡º÷";
$_lang['Keyword'] = "¥­¡¼¥ï¡¼¥É";
$_lang['show results'] = "·ë²Ì¤òÉ½¼¨";
$_lang['request form'] = "¥ê¥¯¥¨¥¹¥È¥Õ¥©¡¼¥à";
$_lang['Enter your keyword'] = "¥­¡¼¥ï¡¼¥É¤òÆþÎÏ¤·¤Æ¤¯¤À¤µ¤¤";
$_lang['Enter your email'] = "¥á¡¼¥ë¥¢¥É¥ì¥¹¤òÆþÎÏ¤·¤Æ¤¯¤À¤µ¤¤";
$_lang['Give your request a name'] = "¥ê¥¯¥¨¥¹¥È¤ÎÂêÌ¾¤òÆþÎÏ¤·¤Æ¤¯¤À¤µ¤¤";
$_lang['Describe your request'] = "¥ê¥¯¥¨¥¹¥È";

$_lang['Due date'] = "½èÍý´ü¸Â";
$_lang['Days'] = "Æü";
$_lang['Sorry, you are not in the list'] = "¿½¤·Ìõ¤´¤¶¤¤¤Þ¤»¤ó¡¢¤¢¤Ê¤¿¤Ï¥ê¥¹¥È¤ËÆþ¤Ã¤Æ¤¤¤Þ¤»¤ó";
$_lang['Your request Nr. is'] = "¤¢¤Ê¤¿¤Î¥ê¥¯¥¨¥¹¥ÈÈÖ¹æ¤Ï";
$_lang['Customer'] = "¸ÜµÒ";


$_lang['Search'] = "¸¡º÷";
$_lang['at'] = "ÂÐ¾Ý";
$_lang['all fields'] = "Á´¥Õ¥£¡¼¥ë¥É";


$_lang['Solution'] = "²ò·è";
$_lang['AND'] = "AND";

$_lang['pending'] = "Ì¤½èÍý";
$_lang['stalled'] = "Ì¤²ò·è";
$_lang['moved'] = "°ÜÆ°ºÑ";
$_lang['solved'] = "²ò·èºÑ";
$_lang['Submit'] = "°ÍÍêÆü";
$_lang['Ass.'] = "³äÅö¤Æ";
$_lang['Pri.'] = "Í¥ÀèÅÙ";
$_lang['access'] = "¥¢¥¯¥»¥¹";
$_lang['Assigned'] = "³äÅö¤Æ";

$_lang['update'] = "¹¹¿·";
$_lang['remark'] = "¥³¥á¥ó¥È";
$_lang['solve'] = "²ò·è";
$_lang['stall'] = "Ì¤²ò·è";
$_lang['cancel'] = "¥­¥ã¥ó¥»¥ë";
$_lang['Move to request'] = "°ÜÆ°Àè¥ê¥¯¥¨¥¹¥È";
$_lang['Dear customer, please refer to the number given above by contacting us.Will will perform your request as soon as possible.'] = "¤ªµÒÍÍ¡¢»ä¶¦¤ËÏ¢Íí¤µ¤ì¤ë¤È¤­¤Ï¾åµ­
¤ÎÈÖ¹æ¤ò»²¾È¤·¤Æ¤¯¤À¤µ¤¤¡£²ÄÇ½¤Ê¸Â¤ê¤ªµÒÍÍ¤Î¥ê¥¯¥¨¥¹¥È¤ò¿×Â®¤Ë½èÍý¤µ¤»¤ÆÄº¤­¤Þ¤¹¡£";
$_lang['Your request has been added into the request queue.<br>You will receive a confirmation email in some moments.'] = "¤¢¤Ê¤¿¤Î¥ê¥¯¥¨¥¹¥È¤Ï¥ê¥¯¥¨¥¹¥È¥­¥å¡¼¤ËÄÉ²Ã¤µ¤ì¤Þ¤·¤¿¡£<br>
³ÎÇ§¥á¡¼¥ë¤òÁ÷ÉÕ¤·¤Þ¤·¤¿¡£";
$_lang['n/a'] = "n/a";
$_lang['internal'] = "ÆâÉô";

$_lang['has reassigned the following request'] = "¤Ï¼¡¤Î¥ê¥¯¥¨¥¹¥È¤òºÆ³äÅö¤Æ¤·¤Þ¤·¤¿";
$_lang['New request'] = "¿·µ¬¥ê¥¯¥¨¥¹¥È";
$_lang['Assign work time'] = "ºî¶È»þ´Ö¤ò³äÅö¤Æ¤ë";
$_lang['Assigned to:'] = "³äÅö¤Æ:";

$_lang['Your solution was mailed to the customer and taken into the database.'] = "¤¢¤Ê¤¿¤Î²ò·èÊýË¡¤Ï¸ÜµÒ°¸¤Ë¥á¡¼¥ëÁ÷¿®¸å¡¢¥Ç¡¼¥¿¥Ù¡¼¥¹¤ËÄÉ²Ã¤µ¤ì¤Þ¤¹¡£";
$_lang['Answer to your request Nr.'] = "¤¢¤Ê¤¿¤Î¥ê¥¯¥¨¥¹¥È Nr.¤ËÂÐ¤¹¤ë²óÅú";
$_lang['Fetch new request by mail'] = "¿·µ¬¥ê¥¯¥¨¥¹¥È¤ò¥á¡¼¥ë¤Ç¼èÆÀ";
$_lang['Your request was solved by'] = "¥ê¥¯¥¨¥¹¥È²óÅú¼Ô¡§";

$_lang['Your solution was mailed to the customer and taken into the database'] = "¤¢¤Ê¤¿¤Î²ò·èÊýË¡¤Ï¸ÜµÒ°¸¤Ë¥á¡¼¥ëÁ÷¿®¸å¡¢¥Ç¡¼¥¿¥Ù¡¼¥¹¤ËÄÉ²Ã¤µ¤ì¤Þ¤¹";
$_lang['Search term'] = "Search term";
$_lang['Search area'] = "Search area";
$_lang['Extended search'] = "Extended search";
$_lang['knowledge database'] = "knowledge database";
$_lang['Cancel'] = "Cancel";
$_lang['New ticket'] = "New ticket";
$_lang['Ticket status'] ="Ticket status";

// please adjust this states as you want -> add/remove states in helpdesk.php
$_lang['unconfirmed'] = 'unconfirmed';
$_lang['new'] = 'new';
$_lang['assigned'] = 'assigned';
$_lang['reopened'] = 'reopened';
$_lang['resolved'] = 'resolved';
$_lang['verified'] = 'verified';

// settings.php
$_lang['The settings have been modified'] = "ÀßÄê¤¬ÊÑ¹¹¤µ¤ì¤Þ¤·¤¿";
$_lang['Skin'] = "¥¹¥­¥ó";
$_lang['First module view on startup'] = "³«»Ï»þ¤ËÉ½¼¨¤¹¤ë¥â¥¸¥å¡¼¥ë";
$_lang['none'] = "Ìµ¤·";
$_lang['Check for mail'] = "¥á¡¼¥ëÄÌÃÎ";
$_lang['Additional alert box'] = "¥¢¥é¡¼¥È¥Ü¥Ã¥¯¥¹";
$_lang['Horizontal screen resolution <br>(i.e. 1024, 800)'] = "¿åÊ¿²òÁüÅÙ <br>(Îã 1024, 800)";
$_lang['Chat Entry'] = "¥Á¥ã¥Ã¥È¤ÎÊ¸»ú";
$_lang['single line'] = "Ã±°ì¹Ô";
$_lang['multi lines'] = "Ê£¿ô¹Ô";
$_lang['Chat Direction'] = "Chat Direction";
$_lang['Newest messages on top'] = "Newest messages on top";
$_lang['Newest messages at bottom'] = "Newest messages at bottom";
$_lang['File Downloads'] = "¥Õ¥¡¥¤¥ë¤Î¥À¥¦¥ó¥í¡¼¥É";

$$_lang['Inline'] = "Inline";
$_lang['Lock file'] = "Lock file";
$_lang['Unlock file'] = "nlock file";
$_lang['New file here'] = "New file here";
$_lang['New directory here'] = "New directory here";
$_lang['Position of form'] = "Position of form";
$_lang['On a separate page'] = "On a separate page";
$_lang['Below the list'] = "Below the list";
$_lang['Treeview mode on module startup'] = "Treeview mode on module startup";
$_lang['Elements per page on module startup'] = "Elements per page on module startup";
$_lang['General Settings'] = "General Settings";
$_lang['First view on module startup'] = "First view on module startup";
$_lang['Left frame width [px]'] = "Left frame width [px]";
$_lang['Timestep Daywiew [min]'] = "Timestep Dayview [min]";
$_lang['Timestep Weekwiew [min]'] = "Timestep Weekview [min]";
$_lang['px per char for event text<br>(not exact in case of proportional font)'] = "px per char for event text<br>(not exact in case of proportional font)";
$_lang['Text length of events will be cut'] = "Text length of events will be cut";
$_lang['Standard View'] = "Standard View";
$_lang['Standard View 1'] = "Standard View 1";
$_lang['Standard View 2'] = "Standard View 2";
$_lang['Own Schedule'] = "Own Schedule";
$_lang['Group Schedule'] = "Group Schedule";
$_lang['Group - Create Event'] = "Group - Create Event";
$_lang['Group, only representation'] = "Group, only representation";
$_lang['Holiday file'] = "Holiday file";

// summary
$_lang['Todays Events'] = "º£Æü¤Î¥¤¥Ù¥ó¥È";
$_lang['New files'] = "¿·Ãå¥Õ¥¡¥¤¥ë";
$_lang['New notes'] = "¿·Ãå¥á¥â";
$_lang['New Polls'] = "¿·ÃåÅêÉ¼";
$_lang['Current projects'] = "¸½ºß¤Î¥×¥í¥¸¥§¥¯¥È";
$_lang['Help Desk Requests'] = "¥Ø¥ë¥×¥Ç¥¹¥¯¤Ø¤ÎÌä¹ç¤»";
$_lang['Current todos'] = "¸½ºß¤Î»Å»ö";
$_lang['New forum postings'] = "·Ç¼¨ÈÄ¤Ø¤Î¿·µ¬Åê¹Æ";
$_lang['New Mails'] = "¿·Ãå¥á¡¼¥ë";

//timecard

$_lang['Theres an error in your time sheet: '] = "¥¿¥¤¥à¥·¡¼¥È¤Ë¥¨¥é¡¼¤¬¤¢¤ê¤Þ¤¹: ";




$_lang['Consistency check'] = "ÂÅÅöÀ­¥Á¥§¥Ã¥¯";
$_lang['Please enter the end afterwards at the'] = "Â³¤¤¤Æ½ªÎ»»þ´Ö¤òÆþÎÏ¤·¤Æ¤¯¤À¤µ¤¤¡§";
$_lang['insert'] = "ÁÞÆþ";
$_lang['Enter records afterwards'] = "¼¡¤Î¹àÌÜ¤Ë¥Ç¡¼¥¿¤òÆþÎÏ¤·¤Æ¤¯¤À¤µ¤¤";
$_lang['Please fill in only emtpy records'] = "¶õ¤Î¥ì¥³¡¼¥É¤Î¤ßÆþÎÏ¤·¤Æ¤¯¤À¤µ¤¤";

$_lang['Insert a period, all records in this period will be assigned to this project'] = "´ü´Ö¤ÎÁÞÆþ¤Ë¤è¤ê¡¢¤³¤Î´ü´Ö¤ÎÁ´¥ì¥³¡¼¥É¤Ï¤³¤Î¥×¥í¥¸¥§¥¯¥È¤Ë³äÅö¤Æ¤é¤ì¤Þ¤¹";
$_lang['There is no record on this day'] = "¥ì¥³¡¼¥É¤¬¤¢¤ê¤Þ¤»¤ó";
$_lang['This field is not empty. Please ask the administrator'] = "¤³¤Î¥Õ¥£¡¼¥ë¥É¤Ï¶õ¤Ç¤Ï¤¢¤ê¤Þ¤»¤ó¡£´ÉÍý¼Ô¤Ë³ÎÇ§¤·¤Æ¤¯¤À¤µ¤¤";
$_lang['There is no open record with a begin time on this day!'] = "¤³¤ÎÆü¤Î»Ï¶È»þ´Ö¤Ë³«¤¤¤Æ¤¤¤ë¥ì¥³¡¼¥É¤Ï¤¢¤ê¤Þ¤»¤ó¡ª";
$_lang['Please close the open record on this day first!'] = "ºÇ½é¤Ë³«¤¤¤Æ¤¤¤ë¥ì¥³¡¼¥É¤òÊÄ¤¸¤Æ¤¯¤À¤µ¤¤¡ª";
$_lang['Please check the given time'] = "»þ´Ö¤ò³ÎÇ§¤·¤Æ¤¯¤À¤µ¤¤";
$_lang['Assigning projects'] = "»²²ÃÃæ¤Î¥×¥í¥¸¥§¥¯¥È";
$_lang['Select a day'] = "ÆüÉÕ¤òÁªÂò";
$_lang['Copy to the boss'] = "¾å»Ê¤Ë¥³¥Ô¡¼¤¹¤ë";
$_lang['Change in the timecard'] = "¥¿¥¤¥à¥«¡¼¥É¤ÎÊÑ¹¹";
$_lang['Sum for'] = "¹ç·×";

$_lang['Unassigned time'] = "Ì¤»ØÄê»þ´Ö";
$_lang['delete record of this day'] = "¥ì¥³¡¼¥É¤òºï½ü¤¹¤ë";
$_lang['Bookings'] = "Í½Ìó";

$_lang['insert additional working time'] = "insert additional working time";
$_lang['Project assignment']= "Project assignment";
$_lang['Working time stop watch']= "Working time stop watch";
$_lang['stop watches']= "stop watches";
$_lang['Project stop watch']= "Project stop watch";
$_lang['Overview my working time']= "Overview my working time";
$_lang['GO']= "GO";
$_lang['Day view']= "Day view";
$_lang['Project view']= "Project view";
$_lang['Weekday']= "Weekday";
$_lang['Start']= "Start";
$_lang['Net time']= "Net time";
$_lang['Project bookings']= "Project bookings";
$_lang['save+close']= "save+close";
$_lang['Working times']= "Working times";
$_lang['Working times start']= "Working times start";
$_lang['Working times stop']= "Working times stop";
$_lang['Project booking start']= "Project booking start";
$_lang['Project booking stop']= "Project booking stop";
$_lang['choose day']= "choose day";
$_lang['choose month']= "choose month";
$_lang['1 day back']= "1 day back";
$_lang['1 day forward']= "1 day forward";
$_lang['Sum working time']= "Sum working time";
$_lang['Time: h / m']= "Time: h / m";
$_lang['activate project stop watch']= "activate project stop watch";
$_lang['activate']= "activate";
$_lang['project choice']= "project choice";
$_lang['stop stop watch']= "stop stop watch";
$_lang['still to allocate:']= "still to allocate:";
$_lang['You are not allowed to delete entries from timecard. Please contact your administrator']= "You are not allowed to delete entries from timecard. Please contact your administrator";
$_lang['You cannot delete entries at this date. Since there have been %s days. You just can edit entries not older than %s days.']= "You cannot delete entries at this date. Since there have been %s days. You just can edit entries not older than %s days.";
$_lang['You cannot delete bookings at this date. Since there have been %s days. You just can edit bookings of entries not older than %s days.']= "You cannot delete bookings at this date. Since there have been %s days. You just can edit bookings of entries not older than %s days.";
$_lang['You cannot add entries at this date. Since there have been %s days. You just can edit entries not older than %s days.']= "You cannot add entries at this date. Since there have been %s days. You just can edit entries not older than %s days.";
$_lang['You cannot add  bookings at this date. Since there have been %s days. You just can add bookings for entries not older than %s days.']= "You cannot add  bookings at this date. Since there have been %s days. You just can add bookings for entries not older than %s days.";
$_lang['activate+close']="activate+close";

// todos
$_lang['accepted'] = "¼õÉÕºÑ";
$_lang['rejected'] = "µñÈÝºÑ";
$_lang['own'] = "¼«Ê¬";
$_lang['progress'] = "¿ÊÄ½";
$_lang['delegated to'] = "»ØÌ¾Áê¼ê";
$_lang['Assigned from'] = "°ÍÍê¼ç";
$_lang['done'] = "´°Î»";
$_lang['Not yet assigned'] = "Not yet assigned";
$_lang['Undertake'] = "Undertake";
$_lang['New todo'] = "New todo"; 
$_lang['Notify recipient'] = "Notify recipient";

// votum.php
$_lang['results of the vote: '] = "ÅêÉ¼·ë²Ì: ";
$_lang['Poll Question: '] = "ÅêÉ¼¼ÁÌä: ";
$_lang['several answers possible'] = "Ê£¿ôÁªÂò²Ä";
$_lang['Alternative '] = "ÁªÂò»è ";
$_lang['no vote: '] = "ÌµÅêÉ¼: ";
$_lang['of'] = ":";
$_lang['participants have voted in this poll'] = "»²²Ã¼ÔÅêÉ¼Î¨";
$_lang['Current Open Polls'] = "¼Â»ÜÃæÅêÉ¼";
$_lang['Results of Polls'] = "Á´ÅêÉ¼·ë²Ì¥ê¥¹¥È";
$_lang['New survey'] ="New survey";
$_lang['Alternatives'] ="Alternatives";
$_lang['currently no open polls'] = "Currently there are no open polls";

// export_page.php
$_lang['export_timecard']       = "Export Timecard";
$_lang['export_timecard_admin'] = "Export Timecard";
$_lang['export_users']          = "Export users of this group";
$_lang['export_contacts']       = "Export contacts";
$_lang['export_projects']       = "Export projectdata";
$_lang['export_bookmarks']      = "Export bookmarks";
$_lang['export_timeproj']       = "Export time-to-project data";
$_lang['export_project_stat']   = "Export projectstats";
$_lang['export_todo']           = "Export todos";
$_lang['export_notes']          = "Export notes";
$_lang['export_calendar']       = "Export all calendarevents";
$_lang['export_calendar_detail']= "Export one calendarevent";
$_lang['submit'] = "submit";
$_lang['Address'] = "Address";
$_lang['Next Project'] = "Next Project";
$_lang['Dependend projects'] = "Dependend projects";
$_lang['db_type'] = "Database type";
$_lang['Log in, please'] = "Log in, please";
$_lang['Recipient'] = "Recipient";
$_lang['untreated'] = "untreated";
$_lang['Select participants'] = "Select participants";
$_lang['Participation'] = "Participation";
$_lang['not yet decided'] = "not yet decided";
$_lang['accept'] = "accept";
$_lang['reject'] = "reject";
$_lang['Substitute for'] = "Substitute for";
$_lang['Calendar user'] = "Kalenderbenutzer";
$_lang['Refresh'] = "Refresh";
$_lang['Event'] = "Event";
$_lang['Upload file size is too big'] = "Upload file size is too big";
$_lang['Upload has been interrupted'] = "Upload has been interrupted";
$_lang['view'] = "view";
$_lang['found elements'] = "found elements";
$_lang['chosen elements'] = "chosen elements";
$_lang['too many hits'] = "The result is bigger than we're able to display.";
$_lang['please extend filter'] = "Please extend your filters.";
$_lang['Edit profile'] = "Edit profile";
$_lang['add profile'] = "add profile";
$_lang['Add profile'] = "Add profile";
$_lang['Added profile'] = "Added profile(s).";
$_lang['No profile found'] = "No profile found.";
$_lang['add project participants'] = "add project participants";
$_lang['Added project participants'] = "Added project participants.";
$_lang['add group of participants'] = "add group of participants";
$_lang['Added group of participants'] = "Added group of participants.";
$_lang['add user'] = "add user";
$_lang['Added users'] = "Added user(s).";
$_lang['Selection'] = "Selection";
$_lang['selector'] = "selector";
$_lang['Send email notification']= "Send&nbsp;email&nbsp;notification";
$_lang['Member selection'] = "Member&nbsp;selection";
$_lang['Collision check'] = "Collision check";
$_lang['Collision'] = "Collision";
$_lang['Users, who can represent me'] = "Users, who can represent me";
$_lang['Users, who can see my private events'] = "Users, who can see<br />my private events";
$_lang['Users, who can read my normal events'] = "Users, who can read<br />my normal events";
$_lang['quickadd'] = "Quickadd";
$_lang['set filter'] = "Set filter";
$_lang['Select date'] = "Select date";
$_lang['Next serial events'] = "Next serial events";
$_lang['All day event'] = "All day event";
$_lang['Event is canceled'] = "Event&nbsp;is&nbsp;canceled";
$_lang['Please enter a password!'] = "Please enter a password!";
$_lang['You are not allowed to create an event!'] = "You are not allowed to create an event!";
$_lang['Event successfully created.'] = "Event successfully created.";
$_lang['You are not allowed to edit this event!'] = "You are not allowed to edit this event!";
$_lang['Event successfully updated.'] = "Event successfully updated.";
$_lang['You are not allowed to remove this event!'] = "You are not allowed to remove this event!";
$_lang['Event successfully removed.'] = "Event successfully removed.";
$_lang['Please give a text!'] = "Please give a text!";
$_lang['Please check the event date!'] = "Please check the event date!";
$_lang['Please check your time format!'] = "Please check your time format!";
$_lang['Please check start and end time!'] = "Please check start and end time!";
$_lang['Please check the serial event date!'] = "Please check the serial event date!";
$_lang['The serial event data has no result!'] = "The serial event data has no result!";
$_lang['Really delete this event?'] = "Really delete this event?";
$_lang['use'] = "Use";
$_lang[':'] = ":";
$_lang['Mobile Phone'] = "Mobile Phone";
$_lang['submit'] = "Submit";
$_lang['Further events'] = "Weitere Termine";
$_lang['Remove settings only'] = "Remove settings only";
$_lang['Settings removed.'] = "Settings removed.";
$_lang['User selection'] = "User selection";
$_lang['Release'] = "Release";
$_lang['none'] = "none";
$_lang['only read access to selection'] = "only write access to selection";
$_lang['read and write access to selection'] = "read and write access to selection";
$_lang['Available time'] = "Available time";
$_lang['flat view'] = "List View";
$_lang['o_dateien'] = "Filemanager";
$_lang['Location'] = "Location";
$_lang['date_received'] = "date_received";
$_lang['subject'] = "Subject";
$_lang['kat'] = "Category";
$_lang['projekt'] = "Project";
$_lang['Location'] = "Location";
$_lang['name'] = "Titel";
$_lang['contact'] = "Kontakt";
$_lang['div1'] = "Erstellung";
$_lang['div2'] = "Änderung";
$_lang['kategorie'] = "Kategorie";
$_lang['anfang'] = "Beginn";
$_lang['ende'] = "Ende";
$_lang['status'] = "Status";
$_lang['filename'] = "Titel";
$_lang['deadline'] = "Termin";
$_lang['ext'] = "an";
$_lang['priority'] = "Priorität";
$_lang['project'] = "Projekt";
$_lang['Accept'] = "Übernehmen";
$_lang['Please enter your user name here.'] = "Please enter your user name here.";
$_lang['Please enter your password here.'] = "Please enter your password here.";
$_lang['Click here to login.'] = "Click here to login.";
$_lang['No New Polls'] = "No New Polls";
$_lang['&nbsp;Hide read elements'] = "&nbsp;Hide read elements";
$_lang['&nbsp;Show read elements'] = "&nbsp;Show read elements";
$_lang['&nbsp;Hide archive elements'] = "&nbsp;Hide archive elements";
$_lang['&nbsp;Show archive elements'] = "&nbsp;Show archive elements";
?>