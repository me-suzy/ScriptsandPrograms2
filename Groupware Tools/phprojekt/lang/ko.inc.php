<?php
// ko.inc.php, korean version
// translation by È²Áø¼ö (jshwang@stat.inha.ac.kr) June 5, 2003 

$chars = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
$name_month = array("", "1¿ù", "2¿ù", "3¿ù", "4¿ù", "5¿ù", "6¿ù", "7¿ù", "8¿ù", "9¿ù", "10¿ù", "11¿ù", "12¿ù");
$l_text31a = array("±âº»", "15ºÐ", "30ºÐ", "1½Ã°£", "2½Ã°£", "4½Ã°£", "ÇÏ·ç");
$l_text31b = array(0, 15, 30, 60, 120, 240, 1440);
$name_day = array("ÀÏ¿äÀÏ", "¿ù¿äÀÏ", "È­¿äÀÏ", "¼ö¿äÀÏ", "¸ñ¿äÀÏ", "±Ý¿äÀÏ", "Åä¿äÀÏ");
$name_day2 = array("¿ù", "È­", "¼ö", "¸ñ", "±Ý", "Åä","ÀÏ");

$_lang['No Entries Found']= "No Entries Found";
$_lang['No Todays Events']= "HNo Todays Events";
$_lang['No new forum postings']= "No new forum postings";
$_lang['in category']= "in category";
$_lang['Filtered']= "Filtered";
$_lang['Sorted by']= "Sorted by";
$_lang['go'] = "ÁøÇà";
$_lang['back'] = "µÚ·Î";
$_lang['print'] = "ÀÎ¼â";
$_lang['export'] = "È­ÀÏ·Î Ãâ·Â";
$_lang['| (help)'] = "| (µµ¿ò¸»)";
$_lang['Are you sure?'] = "È®½ÇÇÕ´Ï±î?";
$_lang['items/page'] = "¾ÆÀÌÅÛ/ÆäÀÌÁö";
$_lang['records'] = "·¹ÄÚµå"; // ±¸¼º¿ä¼Ò
$_lang['previous page'] = "ÀÌÀü ÆäÀÌÁö";
$_lang['next page'] = "´ÙÀ½ ÆäÀÌÁö";
$_lang['first page'] = "first page";
$_lang['last page'] = "last page";
$_lang['Move']  = "ÀÌµ¿";
$_lang['Copy'] = "º¹»ç";
$_lang['Delete'] = "»èÁ¦";
$_lang['Save'] = "ÀúÀå";
$_lang['Directory'] = "µð·ºÅä¸®";
$_lang['Also Delete Contents'] = "³»¿ë¹°µµ »èÁ¦";
$_lang['Sum'] = "ÇÕ°è";
$_lang['Filter'] = "ÇÊÅÍ";
$_lang['Please fill in the following field'] = "´ÙÀ½ Ç×¸ñÀ» ½á ÁÖ¼¼¿ä.";
$_lang['approve'] = "½ÂÀÎ";
$_lang['undo'] = "Ãë¼Ò";
$_lang['Please select!']="¼±ÅÃÇØÁÖ¼¼¿ä!";
$_lang['New'] = "»õ·Î¿î";
$_lang['Select all'] = "ÀüÃ¼¼±ÅÃ";
$_lang['Printable view'] = "ÇÁ¸°Æ® ¹Ì¸®º¸±â";
$_lang['New record in module '] = "¸ðµâ¿¡ »õ·Î¿î ·¹ÄÚµå ";
$_lang['Notify all group members'] = "¸ðµç ±×·ì¿ø¿¡°Ô ¾Ë¸²";
$_lang['Yes'] = "¿¹";
$_lang['No'] = "¾Æ´Ï¿À";
$_lang['Close window'] = "Close window";
$_lang['No Value'] = "No Value"; 
$_lang['Standard'] = "Standard";
$_lang['Create'] = "Anlegen";
$_lang['Modify'] = "Ändern";   
$_lang['today'] = "today";

// admin.php
$_lang['Password'] = "ÆÐ½º¿öµå";
$_lang['Login'] = "·Î±×ÀÎ";
$_lang['Administration section'] = "°ü¸®ÀÚ ¼½¼Ç";
$_lang['Your password'] = "ºñ¹Ð¹øÈ£";
$_lang['Sorry you are not allowed to enter. '] = "±ÍÇÏ´Â »ç¿ëÇÏ½Ç ¼ö ¾ø½À´Ï´Ù. ";
$_lang['Help'] = "µµ¿ò¸»";
$_lang['User management'] = "»ç¿ëÀÚ °ü¸®";
$_lang['Create'] = "»ý¼º";
$_lang['Projects'] = "ÇÁ·ÎÁ§Æ®";
$_lang['Resources'] = "ÀÚ¿ø";
$_lang['Resources management'] = "Resources management";
$_lang['Bookmarks'] = "ºÏ¸¶Å©";
$_lang['for invalid links'] = "Àß¸øµÈ ¿¬°á È®ÀÎ";
$_lang['Check'] = "°Ë»ç";
$_lang['delete Bookmark'] = "ºÏ¸¶Å© »èÁ¦";
$_lang['(multiple select with the Ctrl-key)'] = "(¿©·¯°³¸¦ ¼±ÅÃÇÒ¶§´Â ÄÜÆ®·ÑÅ°¸¦ °°ÀÌ ´©¸£¼¼¿ä)";
$_lang['Forum'] = "Æ÷·³";
$_lang['Threads older than'] = "¿À·¡µÈ °ü·Ã±Û";
$_lang[' days '] = "³¯Â¥ ";
$_lang['Chat'] = "´ëÈ­";
$_lang['save script of current Chat'] = "ÇöÀç ´ëÈ­³»¿ëÀ» ÀúÀåÇÏ¼¼¿ä";
$_lang['Chat script'] = "´ëÈ­ ±Û";
$_lang['New password'] = "»õ ºñ¹Ð¹øÈ£";
$_lang['(keep old password: leave empty)'] = "(±¸ ºñ¹Ð¹øÈ£ À¯Áö: ºóÄ­À¸·Î µÎ¼¼¿ä)";
$_lang['Default Group<br> (must be selected below as well)'] = "±âº» ±×·ì<br> (¾Æ·¡¿¡¼­µµ ¹Ýµå½Ã ¼±ÅÃµÇ¾ßÇÕ´Ï´Ù)";
$_lang['Access rights'] = "Á¢±Ù±ÇÇÑ";
$_lang['Zip code'] = "¿ìÆí¹øÈ£";
$_lang['Language'] = "¾ð¾î";
$_lang['schedule readable to others'] = "ÀÏÁ¤À» ´Ù¸¥»ç¶÷ÀÌ ÀÐÀ» ¼ö ÀÖÀ½";
$_lang['schedule invisible to others'] = "ÀÏÁ¤À» ´Ù¸¥»ç¶÷ÀÌ ÀÐÀ» ¼ö ¾øÀ½";
$_lang['schedule visible but not readable'] = "ÀÏÁ¤ÀÌ º¸ÀÌ³ª ÀÐÀ»¼ö ¾øÀ½";
$_lang['these fields have to be filled in.'] = "ÀÌ ÇÊµåµéÀº ¹Ýµå½Ã ÀÔ·ÂÇØ¾ß ÇÕ´Ï´Ù.";
$_lang['You have to fill in the following fields: family name, short name and password.'] = "´ÙÀ½ ÇÊµåµéÀ» ¹Ýµå½Ã ÀÔ·ÂÇØ¾ß ÇÕ´Ï´Ù: ÀÌ¸§(¼º), ´ÜÃàÀÌ¸§°ú ºñ¹Ð¹øÈ£.";
$_lang['This family name already exists! '] = "¼ºÀÌ Á¸ÀçÇÕ´Ï´Ù ";
$_lang['This short name already exists!'] = "´ÜÃàÀÌ¸§ÀÌ Á¸ÀçÇÕ´Ï´Ù";
$_lang['This login name already exists! Please chosse another one.'] = "·Î±×ÀÎ ÀÌ¸§ÀÌ Á¸ÀçÇÕ´Ï´Ù! ´Ù¸¥°ÍÀ» ¼±ÅÃÇÏ¼¼¿ä.";
$_lang['This password already exists!'] = "ÆÐ½º¿öµå°¡ Á¸ÀçÇÕ´Ï´Ù";
$_lang['This combination first name/family name already exists.'] = "ÀÌ¹Ì °°Àº ÀÌ¸§(ÀÌ¸§/¼º)ÀÌ Á¸ÀçÇÕ´Ï´Ù.";
$_lang['the user is now in the list.'] = " »ç¿ëÀÚ°¡ ÀÌÁ¦ ¸®½ºÆ®¿¡ µî·ÏµÇ¾ú½À´Ï´Ù.";
$_lang['the data set is now modified.'] = "µ¥ÀÌÅ¸°¡ ¼öÁ¤µÇ¾ú½À´Ï´Ù.";
$_lang['Please choose a user'] = "»ç¿ëÀÚ¸¦ ¼±ÅÃÇÏ¼¼¿ä";
$_lang['is still listed in some projects. Please remove it.'] = "´Â ¾î´À ÇÁ·ÎÁ§Æ®¿¡ ÀÌ¹Ì µî·ÏµÇ¾î ÀÖ½À´Ï´Ù. ÀÌ¸¦ »èÁ¦ÇÏ¼¼¿ä.";
$_lang['All profiles are deleted'] = "¸ðµç ÇÁ·ÎÇÊÀÌ »èÁ¦µÇ¾ú½À´Ï´Ù.";
$_lang['A Profile with the same name already exists'] = "A profile with the same name already exists";
$_lang['is taken out of all user profiles'] = "ÀÌ ¸ðµç »ç¿ëÀÚ ÇÁ·ÎÇÊ¿¡¼­ ¾ø¾îÁ³½À´Ï´Ù.";
$_lang['All todo lists of the user are deleted'] = "»ç¿ëÀÚÀÇ ¸ðµç ÇØ¾ßÇÒ ÀÏ ¸ñ·ÏÀÌ »èÁ¦µÇ¾ú½À´Ï´Ù.";
$_lang['is taken out of these votes where he/she has not yet participated'] = "°¡ ¾ÆÁ÷ Âü¿©ÇÏÁö ¾ÊÀº ÅõÇ¥¿¡¼­ ³ª°¬½À´Ï´Ù.";
$_lang['All events are deleted'] = "¸ðµç ÀÌº¥Æ®¸¦ »èÁ¦µÇ¾ú½À´Ï´Ù.";
$_lang['user file deleted'] = "»ç¿ëÀÚ È­ÀÏÀÌ »èÁ¦µÇ¾ú½À´Ï´Ù.";
$_lang['bank account deleted'] = "ÀºÇà ±¸ÁÂ°¡ »èÁ¦µÇ¾ú½À´Ï´Ù.";
$_lang['finished'] = "Á¾·á";
$_lang['Please choose a project'] = "ÇÁ·ÎÁ§Æ®¸¦ ¼±ÅÃÇØÁÖ½Ê½Ã¿À";
$_lang['The project is deleted'] = "ÇÁ·ÎÁ§Æ®¸¦ »èÁ¦µÇ¾ú½À´Ï´Ù.";
$_lang['All links in events to this project are deleted'] = "ÀÌ ÇÁ·ÎÁ§Æ®¿¡ ´ëÇÑ ÀÌº¥Æ®ÀÇ ¸µÅ©°¡ »èÁ¦µÇ¾ú½À´Ï´Ù.";
$_lang['The duration of the project is incorrect.'] = "ÇÁ·ÎÁ§Æ®ÀÇ ±â°£ÀÌ Á¤È®ÇÏÁö ¾Ê½À´Ï´Ù";
$_lang['The project is now in the list'] = "ÇÁ·ÎÁ§Æ®°¡ µî·ÏµÇ¾úÀ½";
$_lang['The project has been modified'] = "ÇÁ·ÎÁ§Æ®°¡ ¼öÁ¤µÇ¾ú½À´Ï´Ù.";
$_lang['Please choose a resource'] = "ÀÚ¿øÀ» ¼±ÅÃÇÏ½Ê½Ã¿À";
$_lang['The resource is deleted'] = "ÀÚ¿øÀÌ »èÁ¦µÇ¾ú½À´Ï´Ù.";
$_lang['All links in events to this resource are deleted'] = "ÀÚ¿ø°ú °ü°èµÈ ¸ðµç ÀÌº¥Æ®°¡ »èÁ¦µÇ¾ú½À´Ï´Ù";
$_lang[' The resource is now in the list.'] = "ÀÚ¿øÀÌ µî·ÏµÇ¾ú½À´Ï´Ù.";
$_lang[' The resource has been modified.'] = "ÀÚ¿øÀÌ ¼öÁ¤µÇ¾ú½À´Ï´Ù.";
$_lang['The server sent an error message.'] = "¼­¹ö°¡ ¿À·ù¸Þ½ÃÁö¸¦ º¸³Â½À´Ï´Ù.";
$_lang['All Links are valid.'] = "¸ðµç ¿¬°áÀÌ À¯È¿ÇÕ´Ï´Ù.";
$_lang['Please select at least one bookmark'] = "Àû¾îµµ ÇÑ°³ÀÌ»ó ºÏ¸¶Å©¸¦ ¼±ÅÃ ÇÏ½Ê½Ã¿À";
$_lang['The bookmark is deleted'] = "ºÏ¸¶Å©°¡ »èÁ¦µÇ¾ú½À´Ï´Ù";
$_lang['threads older than x days are deleted.'] = "threads older than x days are deleted.";
$_lang['All chat scripts are removed'] = "¸ðµç ´ëÈ­¹®ÀÌ Á¦°ÅµË´Ï´Ù.";
$_lang['or'] = "±×·¸Áö ¾ÊÀ¸¸é";
$_lang['Timecard management'] = "±Ù¹«½Ã°£ ±â·ÏÇ¥ °ü¸®";
$_lang['View'] = "»ìÆìº¸±â";
$_lang['Choose group'] = "±×·ì ¼±ÅÃ";
$_lang['Group name'] = "±×·ì ÀÌ¸§";
$_lang['Short form'] = "´ÜÃà ÇüÅÂ";
$_lang['Category'] = "¹üÁÖ";
$_lang['Remark'] = "ÀÇ°ß";
$_lang['Group management'] = "±×·ì°ü¸®";
$_lang['Please insert a name'] = "ÀÌ¸§À» ¾²½Ã¿À";
$_lang['Name or short form already exists'] = "ÀÌ¸§ÀÌ³ª ´ÜÃàÇüÀÌ ÀÌ¹Ì Á¸ÀçÇÕ´Ï´Ù.";
$_lang['Automatic assign to group:'] = "ÀÚµ¿À¸·Î ±×·ì¿¡ ¹èÁ¤:";
$_lang['Automatic assign to user:'] = "ÀÚµ¿À¸·Î »ç¿ëÀÚ¿¡ ¹èÁ¤:";
$_lang['Help Desk Category Management'] = "»ç¿ëÀÚ ¿ä±¸ ÃßÀû ½Ã½ºÅÛ °ü¸®";
$_lang['Category deleted'] = "¹üÁÖ¸¦ »èÁ¦Çß½À´Ï´Ù.";
$_lang['The category has been created'] = "¹üÁÖ°¡ ¸¸µé¾î Á³½À´Ï´Ù.";
$_lang['The category has been modified'] = "¹üÁÖ°¡ ¼öÁ¤µÇ¾ú½À´Ï´Ù.";
$_lang['Member of following groups'] = "´ÙÀ½ ±×·ìÀÇ ±¸¼º¿ø";
$_lang['Primary group is not in group list'] = "ÁÖ ±×·ìÀÌ ±×·ì¸®½ºÆ®¿¡ ÀÖÁö ¾Ê½À´Ï´Ù.";
$_lang['Login name'] = "·Î±×ÀÎ ÀÌ¸§";
$_lang['You cannot delete the default group'] = "±âº»±×·ìÀº »èÁ¦ÇÏ½Ç ¼ö ¾ø½À´Ï´Ù.";
$_lang['Delete group and merge contents with group'] = "±×·ì»èÁ¦¿Í ³»¿ëÀ» ±×·ì¿¡ ÇÕÇÔ";
$_lang['Please choose an element'] = "¿ä¼Ò¸¦ ¼±ÅÃÇÏ¼¼¿ä";
$_lang['Group created'] = "±×·ìÀÌ ¸¸µé¾îÁ³½À´Ï´Ù.";
$_lang['File management'] = "ÆÄÀÏ °ü¸®";
$_lang['Orphan files'] = "¹ö·ÁÁø È­ÀÏµé";
$_lang['Deletion of super admin root not possible'] = "ÃÖ°í °ü¸®ÀÚÀÇ »èÁ¦°¡ ºÒ°¡´ÉÇÔ.";
$_lang['ldap name'] = "ldap ÀÌ¸§";
$_lang['mobile // mobile phone'] = "ÈÞ´ëÆù"; // mobil phone
$_lang['Normal user'] = "ÀÏ¹Ý »ç¿ëÀÚ";
$_lang['User w/Chief Rights'] = "´ëÀå ±ÇÇÑÀ» °¡Áø »ç¿ëÀÚ";
$_lang['Administrator'] = "°ü¸®ÀÚ";
$_lang['Logging'] = "·Î±×ÀÎÁß";
$_lang['Logout'] = "·Î±×¾Æ¿ô";
$_lang['posting (and all comments) with an ID'] = "ID¸¦ °¡Áö°í °Ô½Ã(¸ðµç ÄÚ¸àÆ®)";
$_lang['Role deleted, assignment to users for this role removed'] = "¿ªÇÒ »èÁ¦, ÀÌ ¿ªÇÒ¿¡ ¹è´çµÈ »ç¿ëÀÚ Á¦°ÅµÊ";
$_lang['The role has been created'] = "¿ªÇÒÀÌ »ý¼ºµÊ";
$_lang['The role has been modified'] = "¿ªÇÒÀÌ ¼öÁ¤µÊ";
$_lang['Access rights'] = "Access rights";
$_lang['Usergroup'] = "Gruppe";
$_lang['logged in as'] = "Angemeldet als";

//chat.php
$_lang['Quit chat']= "Quit chat";

//contacts.php
$_lang['Contact Manager'] = "¿¬¶ôÃ³ °ü¸®ÀÚ";
$_lang['New contact'] = "»õ·Î¿î ¿¬¶ôÃ³";
$_lang['Group members'] = "±×·ì¿ø";
$_lang['External contacts'] = "¿ÜºÎ ¿¬¶ôÃ³";
$_lang['&nbsp;New&nbsp;'] = "&nbsp; »õ·Î¿î&nbsp; ";
$_lang['Import'] = "°¡Á®¿À±â";
$_lang['The new contact has been added'] = "»õ·Î¿î ¿¬¶ôÃ³°¡ Ãß°¡µÇ¾úÀ½";
$_lang['The date of the contact was modified'] = "¿¬¶ôÃ³ÀÇ ÀÏÁ¤ÀÌ ¼öÁ¤µÇ¾úÀ½";
$_lang['The contact has been deleted'] = "¿¬¶ôÃ³°¡ »èÁ¦µÇ¾úÀ½";
$_lang['Open to all'] = "¸ðµÎ¿¡°Ô °³¹æµÊ";
$_lang['Picture'] = "±×¸²";
$_lang['Please select a vcard (*.vcf)'] = "vcard (*.vcf)¸¦ ¼±ÅÃÇÏ½Ê½Ã¿À.";
$_lang['create vcard'] = "vcard »ý¼º";
$_lang['import address book'] = "ÁÖ¼Ò·Ï °¡Á®¿À±â";
$_lang['Please select a file (*.csv)'] = "(*.csv) È­ÀÏÀ» ¼±ÅÃÇÏ½Ê½Ã¿À.";
$_lang['Howto: Open your outlook express address book and select file/export/other book<br>Then give the file a name, select all fields in the next dialog and finish'] = "»ç¿ë¹ý: ¾Æ¿ô·è ÀÍ½ºÇÁ·¹½º ÁÖ¼Ò·ÏÀ» ¿­°í, 'ÆÄÀÏ' > '³»º¸³»±â' > '´Ù¸¥ ÁÖ¼Ò·Ï' À» ¼±ÅÃÇÏ½Ê½Ã¿ä.<br>
È­ÀÏ ÀÌ¸§À» Á¤ÇÏ°í ´ÙÀ½ ´ëÈ­Ã¢¿¡¼­ ¸ðµç Ä­À»¼±ÅÃÇÑ ÈÄ 'Á¾·á'ÇÑ´Ù.";
$_lang['Open outlook at file/export/export in file,<br>choose comma separated values (Win), then select contacts in the next form,<br>give the export file a name and finish.'] = "¾Æ¿ô·èÀ» ½ÇÇàÇÑ ´ÙÀ½, 'ÆÄÀÏ' > '³»º¸³»±â' > 'È­ÀÏ¿¡¼­ ³»º¸³»±â' À» Â÷·Ê·Î ¼±ÅÃÇÏ½Ê½Ã¿ä. <br>
'ÄÞ¸¶·Î ºÐ¸®µÈ °ªµé(\in)'À» ¼±ÅÃÇÏ°í ´ÙÀ½ ¾ç½Ä¿¡¼­ '¿¬¶ôÃ³'¸¦ ¼±ÅÃ <br>
³»º¸³¾ È­ÀÏ¿¡ ÀÌ¸§À» Á¤ÇÏ°í ¸¶Ä¡¼¼¿ä";
$_lang['Please choose an export file (*.csv)'] = "³»º¸³¾ ÆÄÀÏ ÀÌ¸§À» ¼±ÅÃÇÏ¼¼¿ä. (*.csv)";
$_lang['Please export your address book into a comma separated value file (.csv), and either<br>1) apply an import pattern OR<br>2) modify the columns of the table with a spread sheet to this format<br>(Delete colums in you file that are not listed here and create empty colums for fields that do not exist in your file):'] = "Please export your address book into a comma separated value file (.csv), and either<br>
1) apply an import pattern OR<br>
2) modify the columns of the table with a spread sheet to this format<br>
(Delete colums in you file that are not listed here and create empty colums for fields that do not exist in your file):";
$_lang['Please insert at least the family name'] = "ÃÖ¼ÒÇÑ ÀÚ½ÅÀÇ ¼ºÀº ÀÔ·ÂÇØÁÖ¼¼¿ä.";
$_lang['Record import failed because of wrong field count'] = "Àß¸øµÈ ÇÊµå·Î ÀÎÇØ, ÀÚ·á°¡Á®¿À±â ½ÇÆÐ!";
$_lang['Import to approve'] = "°¡Á®¿À±â ½ÂÀÎ";
$_lang['Import list'] = "°¡Á®¿À±â ¸ñ·Ï";
$_lang['The list has been imported.'] = "¸ñ·ÏÀ» °¡Á®¿Ô½À´Ï´Ù.";
$_lang['The list has been rejected.'] = "¸ñ·ÏÀÌ °ÅºÎµÇ¾ú½À´Ï´Ù.";
$_lang['Profiles'] = "ÇÁ·ÎÇÊ";
$_lang['Parent object'] = "»óÀ§ °´Ã¼";
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
$_lang['Please select a file'] = "È­ÀÏÀ» ¼±ÅÃÇÏ½Ê½Ã¿À";
$_lang['A file with this name already exists!'] = "ÀÌ¸§ÀÌ °°Àº È­ÀÏÀÌ ÀÌ¹Ì Á¸ÀçÇÕ´Ï´Ù!";
$_lang['Name'] = "ÀÌ¸§";
$_lang['Comment'] = "ÄÚ¸àÆ®";
$_lang['Date'] = "³¯Â¥";
$_lang['Upload'] = "¿Ã¸®±â";
$_lang['Filename and path'] = "È­ÀÏÀÌ¸§°ú °æ·Î";
$_lang['Delete file'] = "È­ÀÏ»èÁ¦";
$_lang['Overwrite'] = "°ãÃÄ¾²±â";
$_lang['Access'] = "Á¢±Ù±ÇÇÑ";
$_lang['Me'] = "³ª¸¸";
$_lang['Group'] = "group";
$_lang['Some'] = "ÀÏºÎ¸¸";
$_lang['As parent object'] = "µð·ºÅä¸®¿Í °°°Ô";
$_lang['All groups'] = "All groups";
$_lang['You are not allowed to overwrite this file since somebody else uploaded it'] = "´Ù¸¥ »ç¶÷ÀÌ ¿Ã¸° ÀÚ·áÀÌ¹Ç·Î °ãÃÄ¾²±â¸¦ ÇÒ ¼ö ¾ø½À´Ï´Ù.";
$_lang['personal'] = "°³ÀÎÀûÀÎ";
$_lang['Link'] = "¸µÅ©";
$_lang['name and network path'] = "ÀÌ¸§°ú ³×Æ®¿÷ °æ·Î";
$_lang['with new values'] = "»õ·Î¿î °ªµé·Î...";
$_lang['All files in this directory will be removed! Continue?'] = "ÀÌ µð·ºÅä¸®ÀÇ ¸ðµç È­ÀÏÀÌ »èÁ¦µË´Ï´Ù! °è¼Ó ÁøÇà ÇÏ½Ã°Ú½À´Ï±î?";
$_lang['This name already exists'] = "ÀÌ ÀÌ¸§ÀÌ ÀÌ¹Ì Á¸ÀçÇÕ´Ï´Ù.";
$_lang['Max. file size'] = "ÃÖ´ë ÆÄÀÏ Å©±â";
$_lang['links to'] = "...·Î ¸µÅ©(links to)";
$_lang['objects'] = "°´Ã¼";
$_lang['Action in same directory not possible'] = "°°Àº µð·¹Åä¸®³»¿¡¼­ÀÇ ½ÇÇàÀº ºÒ°¡´ÉÇÕ´Ï´Ù.";
$_lang['Upload = replace file'] = "¿Ã¸®±â = È­ÀÏ±³Ã¼";
$_lang['Insert password for crypted file'] = "¾ÏÈ£È­µÈ È­ÀÏ¿¡ ´ëÇÑ ºñ¹Ð¹øÈ£¸¦ ÀÔ·Â";
$_lang['Crypt upload file with password'] = "¿Ã¸° È­ÀÏÀ» ºñ¹Ð¹øÈ£·Î ¾ÏÈ£È­";
$_lang['Repeat'] = "¹Ýº¹";
$_lang['Passwords dont match!'] = "ºñ¹Ð¹øÈ£°¡ Æ²¸³´Ï´Ù";
$_lang['Download of the password protected file '] = "ºñ¹Ð¹øÈ£·Î º¸È£¹Þ´Â ÆÄÀÏ´Ù¿î·Îµå ";
$_lang['notify all users with access'] = "Á¢±Ù±ÇÇÑÀ» °¡Áö´Â ¸ðµç »ç¿ëÀÚ¿¡°Ô ¾Ë¸²";
$_lang['Write access'] = "¾²±â ±ÇÇÑ";
$_lang['Version'] = "¹öÀü";
$_lang['Version management'] = "¹öÀü °ü¸®";
$_lang['lock'] = "Àá±Ý";
$_lang['unlock'] = "Àá±ÝÇØÁ¦";
$_lang['locked by'] = "locked by";
$_lang['Alternative Download'] = "´ëÃ¼ ³»·Á¹Þ±â";
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
$_lang['Welcome to the setup of PHProject!<br>'] = "ÇÁ·ÎÁ§Æ® °ü¸®ÀÚ¿¡ ¿À½Å°ÍÀ» È¯¿µÇÕ´Ï´Ù!";
$_lang['Please remark:<ul><li>A blank database must be available<li>Please ensure that the webserver is able to write the file config.inc.php'] = "ÁÖ¼®À» ´Ù¼¼¿ä:<ul>
<li>»ç¿ë°¡´ÉÇÑ ºñ¾îÀÖ´Â µ¥ÀÌÅ¸ º£ÀÌ½º°¡ ¹Ýµå½Ã ÀÖ¾î¾ß ÇÕ´Ï´Ù
<li>À¥¼­¹ö°¡ ¹Ýµå½Ã 'config.inc.php' È­ÀÏ¿¡ ´ëÇØ ÀÐ°í ¾µ ¼ö ÀÖ¾î¾ß ÇÕ´Ï´Ù.";
$_lang['<li>If you encounter any errors during the installation, please look into the <a href=help/faq_install.html target=_blank>install faq</a>or visit the <a href=http://www.PHProjekt.com/forum.html target=_blank>Installation forum</a></i>'] = "<li>¸¸¾à ¼³Ä¡½Ã ¿¡·¯¸Þ½ÃÁö°¡ ³ª¿À¸é ´ÙÀ½À» ÂüÁ¶ÇÏ¼¼¿ä. <a href='help/faq_install.html' target=_blank> ¼³Ä¡ FAQ</a>
or visit the <a href='http://www.PHProjekt.com/forum.html' target=_blank>¼³Ä¡Æ÷·³</a></i>";
$_lang['Please fill in the fields below'] = "¾Æ·¡ ÇÊµå¸¦ Ã¤¿ì¼¼¿ä";
$_lang['(In few cases the script wont respond.<br>Cancel the script, close the browser and try it again).<br>'] = "(¸î°³ÀÇ °æ¿ì ½ºÅ©¸³Æ®°¡ ¹ÝÀÀÇÏÁö 
¾Ê½À´Ï´Ù.<br> ½ºÅ©¸³Æ®¸¦ Ãë¼ÒÇÏ°í, ºê¶ó¿ìÁ®¸¦ ´Ý°í Àç½ÃÀÛÇÏ¼¼¿ä).";
$_lang['Type of database'] = "µ¥ÀÌÅ¸º£ÀÌ½º Á¾·ù";
$_lang['Hostname'] = "È£½ºÆ®ÀÌ¸§";
$_lang['Username'] = "»ç¿ëÀÚ ÀÌ¸§";

$_lang['Name of the existing database'] = "ÇöÁ¸ÇÏ´Â DB ÀÌ¸§";
$_lang['config.inc.php not found! Do you really want to update? Please read INSTALL ...'] = "config.inc.php ¸¦ Ã£À» ¼ö ¾ø½À´Ï´Ù! Á¤¸» ¾÷µ¥ÀÌÆ®¸¦ ¿øÇÏ½Ê´Ï±î? INSTALL¹®À» ÂüÁ¶ÇÏ¼¼¿ä...";
$_lang['config.inc.php found! Maybe you prefer to update PHProject? Please read INSTALL ...'] = "config.inc.php ¸¦ Ã£¾Ò½À´Ï´Ù! PHProjectÀÇ ¾÷µ¥ÀÌÆ®¸¦ ¿øÇÏ½Ê´Ï±î?INSTALL¹®À» ÂüÁ¶ÇÏ¼¼¿ä...";
$_lang['Please choose Installation,Update or Configure!'] = "'¼³Ä¡','°»½Å', ¶Ç´Â 'ÀüÃ¼±¸¼º'À»  ¼±ÅÃÇÏ¼¼¿ä!";
$_lang['Sorry, I cannot connect to the database! <br>Please close all browser windows and restart the installation.'] = "ÁË¼ÛÇÕ´Ï´Ù, DB¿¡ ¿¬°áÇÒ ¼ö ¾ø½À´Ï´Ù! <br> ¼öÁ¤ÇÏ½Ã°í ¼³Ä¡¸¦ ´Ù½Ã ½ÃÀÛÇÏ½Ã¿À.";
$_lang['Sorry, it does not work! <br> Please set DBDATE to Y4MD- or let phprojekt change this environment-variable (php.ini)!'] = "ÁË¼ÛÇÕ´Ï´Ù. Á¤»óÀûÀ¸·Î ÀÛµ¿ÇÏÁö ¾Ê½À´Ï´Ù.! <br> DBDATE¸¦ 'Y4MD-' ·Î ¸ÂÃß°Å³ª phprojekt°¡ ÀÌ È¯°æº¯¼ö¸¦ (php.ini)¿¡¼­ º¯°æÇÏµµ·Ï ÇÏ¼¼¿ä!";
$_lang['Seems that You have a valid database connection!'] = "µ¥ÀÌÅÍ º£ÀÌ½ºÀÇ ¿¬°áÀÌ ÀÌ·ç¾îÁ³½À´Ï´Ù.";
$_lang['Please select the modules you are going to use.<br> (You can disable them later in the config.inc.php)<br>'] = "´ç½ÅÀÇ »ç¿ëÇÏ°íÀÚ ÇÏ´Â ¸ðµâÀ» ¼±ÅÃÇÏ¼¼¿ä.<br>(³ªÁß¿¡ config.inc.php¿¡¼­ º¯°æÇÒ ¼ö ÀÖ½À´Ï´Ù.)";
$_lang['Install component: insert a 1, otherwise keep the field empty'] = "±¸¼º¿ä¼Ò¸¦ ¼³Ä¡ÇÏ¼¼¿ä: '1'À» ÀÔ·ÂÇÏ°Å³ª °ø¶õÀ¸·Î µÎ½Ê½Ã¿À";
$_lang['Group views'] = "±×·ìº¸±â";
$_lang['Todo lists'] = "ÀÛ¾÷ ¸®½ºÆ®";

$_lang['Voting system'] = "ÅõÇ¥½Ã½ºÅÛ";


$_lang['Contact manager'] = "¿¬¶ôÃ³°ü¸®ÀÚ";
$_lang['Name of userdefined field'] = "»ç¿ëÀÚ°¡ Á¤ÇÑ ÇÊµå¸í";
$_lang['Userdefined'] = "»ç¿ëÀÚ Á¤ÀÇ";
$_lang['Profiles for contacts'] = "¿¬¶ôÃ³ ÇÁ·ÎÇÊ";
$_lang['Mail'] = "¸ÞÀÏ";
$_lang['send mail'] = " ¸ÞÀÏ ¹ß¼Û¸¸";
$_lang[' only,<br> &nbsp; &nbsp; full mail client'] = " <br> &nbsp; &nbsp; ¸ðµç ±â´É ¸ÞÀÏ";



$_lang['1 to show appointment list in separate window,<br>&nbsp; &nbsp; 2 for an additional alert.'] = "'1'Àº º°µµÀÇ Ã¢¿¡ ¾à¼Ó¸ñ·Ï º¸¿©ÁÖ±â, <br>&nbsp; &nbsp; '2'´Â Ãß°¡ ¾Ë¸²"; 
$_lang['Alarm'] = "¾Ë¸²";
$_lang['max. minutes before the event'] = "ÃÖ´ë ¸îºÐÀü¿¡ ÀÏÁ¤ ¾Ë¸²";
$_lang['SMS/Mail reminder'] = "SMS/Mail ¾Ë¸²";
$_lang['Reminds via SMS/Email'] = "SMS/Email ·Î »ó±â½ÃÅ´";
$_lang['1= Create projects,<br>&nbsp; &nbsp; 2= assign worktime to projects only with timecard entry<br>&nbsp; &nbsp; 3= assign worktime to projects without timecard entry<br>&nbsp; &nbsp; (Selection 2 or 3 only with module timecard!)'] = "'1'= ÇÁ·ÎÁ§Æ® »ý¼º,<br>
&nbsp; &nbsp; '2'= ½Ã°£Ç¥¿Í °°ÀÌ ¿öÅ©Å¸ÀÓ¿¡ ÇÁ·ÎÁ§Æ®¸¦ ÇÒ´ç<br>
&nbsp; &nbsp; '3'= ½Ã°£Ç¥ ¾øÀÌ ÀÛ¾÷ÀÏÁ¤À» ÇÁ·ÎÁ§Æ®¿¡ ÇÒ´ç.<br>&mbsp;
&nbsp; ('2'¿Í '3'Àº Å¸ÀÓÄ«µå ¸ðµâ¿¡¼­ ¼±ÅÃ!) ";

$_lang['Name of the directory where the files will be stored<br>( no file management: empty field)'] = "È­ÀÏÀÌ ÀúÀåµÉ µð·ºÅä¸®¸í<br>(ÆÄÀÏ °ü¸® ¾øÀ½: ºó ÇÊµå)";
$_lang['absolute path to this directory (no files = empty field)'] = "ÀÌ µð·ºÅä¸®¿¡ ´ëÇÑ Àý´ë°æ·Î (ÆÄÀÏÀÌ ¾øÀ½= ºó ÇÊµå)";
$_lang['Time card'] = "Å¸ÀÓÄ«µå(±Ù¹«½Ã°£ ±â·ÏÇ¥)";
$_lang['1 time card system,<br>&nbsp; &nbsp; 2 manual insert afterwards sends copy to the chief'] = "'1' Å¸ÀÓÄ«µå ½Ã½ºÅÛ,<br>&nbsp; &nbsp; 
'2' Á÷Á¢ ±âÀÔÈÄ ´ëÀå¿¡°Ô °á°ú ¼ÛºÎ";
$_lang['Notes'] = "³ëÆ®";
$_lang['Password change'] = "ºñ¹Ð¹øÈ£ º¯°æ";
$_lang['New passwords by the user - 0: none - 1: only random passwords - 2: choose own'] = "»ç¿ëÀÚ¿¡ ÀÇÇÑ ºñ¹Ð¹øÈ£ º¯°æ - 0: ºÒ°¡´É - 1: ·£´ýÇÑ ºñ¹Ð¹øÈ£ - 2: º»ÀÎ ¼±ÅÃ";
$_lang['Encrypt passwords'] = "ºñ¹Ð¹øÈ£¸¦ ¾ÏÈ£È­";
$_lang['Login via '] = "·Î±×ÀÎ via ";
$_lang['Extra page for login via SSL'] = "SSL ·Î±×ÀÎ¿¡ ´ëÇÑ º°µµ ÆäÀÌÁö";
$_lang['Groups'] = "±×·ì";
$_lang['User and module functions are assigned to groups<br>&nbsp; &nbsp; (recommended for user numbers > 40)'] = "»ç¿ëÀÚ¿Í ¸ðµâ ÇÔ¼ö´Â ±×·ì¿¡ ¹èÁ¤<br>
&nbsp; &nbsp; (»ç¿ëÀÚ¼ö°¡ 40º¸´Ù Å« °æ¿ì¿¡ ÃßÃµµÊ)"; 
$_lang['User and module functions are assigned to groups'] = "»ç¿ëÀÚ¿Í ¸ðµâÇÔ¼ö´Â ±×·ì¿¡ ¹èÁ¤";
$_lang['Help desk'] = "µµ¿òÃ³";
$_lang['Help Desk Manager / Trouble Ticket System'] = "µµ¿òÃ³ °ü¸® / ¹®Á¦Ç¥ ½Ã½ºÅÛ";
$_lang['RT Option: Customer can set a due date'] = "RT ¿É¼Ç: °í°´ÀÌ ¸¸±âÀÏÀ» Á¤ÇÒ ¼ö ÀÖÀ½";
$_lang['RT Option: Customer Authentification'] = "RT ¿É¼Ç: °í°´ ÀÎÁõ";
$_lang['0: open to all, email-address is sufficient<br>1: registered contact enter his family name<br>2: his email'] = "0: ¸ðµÎ¿¡°Ô °³¹æµÊ, ÀÌ ¸ÞÀÏ ÁÖ¼Ò¸é ÃæºÐÇÔ, 1: °í°´ÀÌ ¿¬¶ôÃ³ ¸ñ·Ï¿¡ ÀÖ¾î¾ßÇÏ°í ¼ºÀ» ±âÀÔÇÏ½Ã¿À. ";
$_lang['RT Option: Assigning request'] = "RT ¿É¼Ç: ¹èÁ¤ ¿ä±¸";
$_lang['0: by everybody, 1: only by persons with status chief'] = "0: ¸ðµÎ¿¡ ÀÇÇØ, 1: ´ëÀå±ÇÇÑÀÇ »ç¶÷¿¡ ÀÇÇØ";
$_lang['Email Address of the support'] = "´ã´çÀÚÀÇ ÀÌ¸ÞÀÏ ÁÖ¼Ò";
$_lang['Scramble filenames'] = "ÆÄÀÏÀÌ¸§ µÚ¼¯À½";
$_lang['creates scrambled filenames on the server<br>assigns previous name at download'] = "¼­¹ö¿¡¼­  µÚ¼¯ÀÎ ÆÄÀÏÀÌ¸§À» »ý¼º<br>
³»·Á¹ÞÀ»½Ã ÀÌÀü ÀÌ¸§ ÇÒ´ç";

$_lang['0: last name, 1: short name, 2: login name'] = "0: ¼º, 1: ´ÜÃà¸í, 2: ·Î±×ÀÎ ÀÌ¸§";
$_lang['Prefix for table names in db'] = "Prefix for table names in db";
$_lang['Alert: Cannot create file config.inc.php!<br>Installation directory needs rwx access for your server and rx access to all others.'] = "°æ°í:'config.inc.php'ÆÄÀÏÀ»
 ¸¸µé ¼ö ¾ø½À´Ï´Ù!<br>¼³Ä¡ µð·ºÅä¸®¿¡ ÀÐ±â/¾²±â/½ÇÇàÇÏ±â ±ÇÇÑÀÌ ÀÖ¾î¾ßÇÏ°í ´Ù¸¥ »ç¶÷¿¡°Ô´Â ÀÐ±â/½ÇÇàÇÏ±â ±ÇÇÑÀÌ ÀÖ¾î¾ß ÇÑ´Ù.";
$_lang['Location of the database'] = "µ¥ÀÌÅÍº£ÀÌ½º À§Ä¡";
$_lang['Type of database system'] = "µ¥ÀÌÅÍº£ÀÌ½º ½Ã½ºÅÛÀÇ Á¾·ù";
$_lang['Username for the access'] = "Á¢±ÙÀ» À§ÇÑ »ç¿ëÀÚ ÀÌ¸§";
$_lang['Password for the access'] = "Á¢±ÙÀ» À§ÇÑ ºñ¹Ð¹øÈ£";
$_lang['Name of the database'] = "µ¥ÀÌÅÍ º£ÀÌ½ºÀÇ ÀÌ¸§";
$_lang['Prefix for database table names'] = "Prefix for database table names";
$_lang['First background color'] = "Ã¹¹øÂ° ¹è°æ»ö";
$_lang['Second background color'] = "µÎ¹øÂ° ¹è°æ»ö";
$_lang['Third background color'] = "¼¼¹øÂ° ¹è°æ»ö";
$_lang['Color to mark rows'] = "Color to mark rows";
$_lang['Color to highlight rows'] = "Color to highlight rows";
$_lang['Event color in the tables'] = "Å×ÀÌºíÀÇ ÀÌº¥Æ®»ö";
$_lang['company icon yes = insert name of image'] = "'¿¹' È¸»ç±âÈ£ = ÀÌ¹ÌÁöÀÇ ÀÌ¸§À» ÀÔ·ÂÇÏ¼¼¿ä";
$_lang['URL to the homepage of the company'] = "È¸»ç È¨ÆäÀÌÁö ÁÖ¼Ò";
$_lang['no = leave empty'] = "'¾Æ´Ï¿À' = ºñ¾îµÎ¼¼¿ä";
$_lang['First hour of the day:'] = "ÇÏ·çÀÇ Ã¹¹øÂ° ½Ã°£:";
$_lang['Last hour of the day:'] = "ÇÏ·çÀÇ ¸¶Áö¸· ½Ã°£:";
$_lang['An error ocurred while creating table: '] = "Å×ÀÌºíÀ» ¸¸µå´Â µ¿¾È ¿¡·¯°¡ ¹ß»ýÇß½À´Ï´Ù.: ";
$_lang['Table dateien (for file-handling) created'] = "ÆÄÀÏ Á¶ÀÛ Å×ÀÌºí('dateien')À» ¸¸µé¾ú½À´Ï´Ù.";
$_lang['File management no = leave empty'] = "ÆÄÀÏ °ü¸® ¾øÀ½=ºñ¾îµÎ¼¼¿ä";
$_lang['yes = insert full path'] = "¿¹= ÀüÃ¼ °æ·Î¿Í";
$_lang['and the relative path to the PHProjekt directory'] = "±×¸®°í PHProjekt µð·ºÅä¸®¿¡ ´ëÇÑ »ó´ë°æ·Î¸¦ ±â·ÏÇÏ¼¼¿ä";
$_lang['Table profile (for user-profiles) created'] = "'profile' Å×ÀÌºí(»ç¿ëÀÚ ÇÁ·ÎÇÊ)ÀÌ »ý¼ºµÊ";
$_lang['User Profiles yes = 1, no = 0'] = "ÇÁ·ÎÇÊ ¿¹ = 1, ¾Æ´Ï¿À = 0";
$_lang['Table todo (for todo-lists) created'] = "'todo' Å×ÀÌºí(ÇØ¾ßÇÒÀÏ ¸ñ·ÏÀ» À§ÇÑ)ÀÌ »ý¼ºµÊ";
$_lang['Todo-Lists yes = 1, no = 0'] = "ÇØ¾ßÇÒ ÀÏ ¸ñ·Ï ¿¹ = 1, ¾Æ´Ï¿À = 0";
$_lang['Table forum (for discssions etc.) created'] = "'forum' Å×ÀÌºí(¿©·¯ ÅäÀÇ¸¦ À§ÇÑ)ÀÌ »ý¼ºµÊ";
$_lang['Forum yes = 1, no = 0'] = "´ëÈ­ÀÇ ±¤Àå  ¿¹ = 1, ¾Æ´Ï¿À = 0";
$_lang['Table votum (for polls) created'] = "Å×ÀÌºí 'votum'(ÅõÇ¥¸¦ À§ÇÑ)À» ¸¸µé¾ú½À´Ï´Ù ";
$_lang['Voting system yes = 1, no = 0'] = "ÅõÇ¥½Ã½ºÅÛ ¿¹ = 1, ¾Æ´Ï¿À = 0";
$_lang['Table lesezeichen (for bookmarks) created'] = "Å×ÀÌºí 'lesezeichen' À» ¸¸µé¾ú½À´Ï´Ù.(ºÏ¸¶Å©)";
$_lang['Bookmarks yes = 1, no = 0'] = "ºÏ¸¶Å© ¿¹ = 1, ¾Æ´Ï¿À = 0";
$_lang['Table ressourcen (for management of additional ressources) created'] = "Å×ÀÌºí 'ressourcen' À» ¸¸µé¾ú½À´Ï´Ù.(ÀÚ¿øÀ» Ãß°¡ °ü¸®)";
$_lang['Resources yes = 1, no = 0'] = "ÀÚ¿ø ¿¹ = 1, ¾Æ´Ï¿À = 0";
$_lang['Table projekte (for project management) created'] = "Å×ÀÌºí 'projekte'¸¸µé¾ú½À´Ï´Ù (ÇÁ·ÎÁ§Æ® °ü¸®)";
$_lang['Table contacts (for external contacts) created'] = "¿¬¶ôÃ³ Å×ÀÌºíÀ» ¸¸µé¾ú½À´Ï´Ù. (¿ÜºÎ ¿¬°á)";
$_lang['Table notes (for notes) created'] = "³ëÆ® Å×ÀÌºíÀ» ¸¸µé¾ú½À´Ï´Ù.";
$_lang['Table timecard (for time sheet system) created'] = "±Ù¹«½Ã°£±â·ÏÇ¥ Å×ÀÌºíÀ» ¸¸µé¾ú½À´Ï´Ù. (½Ã°£±â·Ï ½Ã½ºÅÛ)";
$_lang['Table groups (for group management) created'] = "Å×ÀÌºí ±×·ìÀ» ¸¸µé¾ú½À´Ï´Ù.(±×·ì °ü¸®)";
$_lang['Table timeproj (assigning work time to projects) created'] = "Å×ÀÌºí timeproj¸¦ ¸¸µé¾ú½À´Ï´Ù.(ÇÁ·ÎÁ§Æ®¸¦ ÀÛ¾÷½Ã°£¿¡ ¹èºÐ)";
$_lang['Table rts and rts_cat (for the help desk) created'] = "rts ¿Í rts_cat Å×ÀÌºí (help desk ½Ã½ºÅÛÀ» À§ÇØ¼­)ÀÌ »ý¼ºµÇ¾ú½À´Ï´Ù.";
$_lang['Table mail_account, mail_attach, mail_client und mail_rules (for the mail reader) created'] = "mail_account, mail_attach, mail_client, and mail_rules Å×ÀÌºí(¸ÞÀÏ ÀÐ±â¸¦ À§ÇØ)ÀÌ »ý¼ºµÊ.";
$_lang['Table logs (for user login/-out tracking) created'] = "Å×ÀÌºí log ( »ç¿ëÀÚÀÇ ·Î±×ÀÎ ·Î±×¾Æ¿ô ÃßÀû ) »ý¼ºµÊ";
$_lang['Tables contacts_profiles und contacts_prof_rel created'] = "contacts_profiles °ú contacts_prof_rel Å×ÀÌºíÀÌ »ý¼ºµÊ";
$_lang['Project management yes = 1, no = 0'] = "ÇÁ·ÎÁ§Æ® °ü¸® ¿¹ = 1, ¾Æ´Ï¿À = 0";
$_lang['additionally assign resources to events'] = "ÀÚ¿øÀ» ÀÌº¥Æ®¿¡ ¼±Á¤ÇÏ¿© Ãß°¡ÇÏ¼¼¿ä";
$_lang['Address book  = 1, nein = 0'] = "ÁÖ¼Ò·Ï = 1, ³²ÀÚÀÌ¸§ = 0";
$_lang['Mail no = 0, only send = 1, send and receive = 2'] = "¸ÞÀÏ ¿¹ = 1, ¾Æ´Ï¿À = 0";
$_lang['Chat yes = 1, no = 0'] = "Chat yes = 1, no = 0";
$_lang['Name format in chat list'] = "Name format in chat list";
$_lang['0: last name, 1: first name, 2: first name, last name,<br> &nbsp; &nbsp; 3: last name, first name'] = "0: last name, 1: first name, 2: first name, last name,<br> &nbsp; &nbsp; 3: last name, first name";
$_lang['Timestamp for chat messages'] = "Timestamp for chat messages";
$_lang['users (for authentification and address management)'] = "'»ç¿ëÀÚ' (ÀÎÁõ°ú ÁÖ¼Ò°ü¸®¸¦ À§ÇÏ¿©)";
$_lang['Table termine (for events) created'] = "'Table termine'À» ¸¸µé¾ú½À´Ï´Ù.(ÀÌº¥Æ®¿¡ ´ëÇØ¼­)";
$_lang['The following users have been inserted successfully in the table user:<br>root - (superuser with all administrative privileges)<br>test - (chief user with restricted access)'] = "´ÙÀ½ÀÇ »ç¿ëÀÚ´Â 'user'Å×ÀÌºí¿¡ ¼º°øÀûÀ¸·Î Ãß°¡µÇ¾ú½À´Ï´Ù:<br>
'root' - (°ü¸®ÀÇ ¸ðµç ±ÇÇÑÀ» °¡Áø °í±Þ »ç¿ëÀÚ)<br>
'test' - (Á¦ÇÑµÈ Á¢±Ù±ÇÇÑÀ» °¡Áø ´ëÀå »ç¿ëÀÚ)";
$_lang['The group default has been created'] = "'default' ±×·ìÀÌ ¸¸µé¾îÁ³½À´Ï´Ù";
$_lang['Please do not change anything below this line!'] = "ÀÌ ÁÙ ¾Æ·¡ÀÖ´Â °ÍÀº ¹Ù²ÙÁö ¸¶¼¼¿ä!";
$_lang['Database error'] = "µ¥ÀÌÅÍº£ÀÌ½º ¿À·ù";
$_lang['Finished'] = "ÀÛ¾÷Á¾·á";
$_lang['There were errors, please have a look at the messages above'] = "¿¡·¯°¡ ¹ß»ýÇÏ¿´À¸´Ï, ¸Þ½ÃÁö¸¦ È®ÀÎÇÏ½Ê½Ã¿À.";
$_lang['All required tables are installed and <br>the configuration file config.inc.php is rewritten<br>It would be a good idea to makea backup of this file.<br>'] = "¸ðµç ÇÊ¿äÇÑ Å×ÀÌºíÀÌ ¼³Ä¡µÇ¾ú°í <br>
±¸Á¶º¯°æÈ­ÀÏ 'config.inc.php'°¡ ÀçÀÛ¼º µÇ¾úÀ½<br>
ÀÌ È­ÀÏÀÇ º¹»çº»À» ¸¸µé¾î 
³õ´Â°ÍÀÌ ÁÁÀ½.<br>";
$_lang['The administrator root has the password root. Please change his password here:'] = "'root' °ü¸®ÀÚÀÇ ¾ÏÈ£°¡ 'root'ÀÔ´Ï´Ù. ¿©±â¿¡¼­ ¾ÏÈ£¸¦ ¹Ù²Ù¼¼¿ä.";
$_lang['The user test is now member of the group default.<br>Now you can create new groups and add new users to the group'] = "»ç¿ëÀÚ 'test'´Â 'default'±×·ìÀÇ È¸¿øÀÔ´Ï´Ù.<br>
Áö±Ý ´ç½ÅÀº »õ·Î¿î ±×·ìÀ» ¸¸µé°í ±× ±×·ì¿¡ »ç¿ëÀÚ¸¦ Ãß°¡ÇÒ ¼ö ÀÖ½À´Ï´Ù.";
$_lang['To use PHProject with your Browser go to <b>index.php</b><br>Please test your configuration, especially the modules Mail and Files.'] = "PHProject ¸¦ ´ç½ÅÀÇ ºê¶ó¿ìÁ®¿¡¼­ »ç¿ëÇÏ·Á¸é <b>index.php</b> ÆäÀÌÁö·Î °¡¼¼¿ä.<br>
´ç½ÅÀÇ ±¸¼º¿ä¼Ò Áß Æ¯È÷ '¸ÞÀÏ'°ú 'ÆÄÀÏ'ÀÇ ¸ðµâÀ» °Ë»çÇÏ¼¼¿ä.";

$_lang['Alarm x minutes before the event'] = "ÀÌº¥Æ® 00ºÐ Àü¿¡ ¾Ë¸²";
$_lang['Additional Alarmbox'] = "Ãß°¡ ¾Ë¸²»óÀÚ";
$_lang['Mail to the chief'] = "ÁÖ°ü¸®ÀÚ¿¡°Ô ¸ÞÀÏ ¹ß¼Û";
$_lang['Out/Back counts as: 1: Pause - 0: Workingtime'] = "³ª°¡±â/µ¹¾Æ°¡±â Ä«¿îµå: 1: ÀÏ½ÃÁ¤Áö - 0: ¾÷¹«½Ã°£";
$_lang['Passwords will now be encrypted ...'] = "ºñ¹Ð¹øÈ£°¡ Áö±ÝºÎÅÍ ¾ÏÈ£È­ µÉ °ÍÀÔ´Ï´Ù.";
$_lang['Filenames will now be crypted ...'] = "È­ÀÏ¸íµéÀÌ Áö±Ý ¾ÏÈ£È­µÉ °ÍÀÔ´Ï´Ù ...";
$_lang['Do you want to backup your database right now? (And zip it together with the config.inc.php ...)<br>Of course I will wait!'] = "´ç½ÅÀÇ µ¥ÀÌÅÍº£ÀÌ½º°¡ Áö±Ý ¹é¾÷µÇ´Â °ÍÀ» ¿øÇÕ´Ï±î?
(±×¸®°í config.inc.php ¿ÍÇÔ²² zipÀ¸·Î ¾ÐÃàÇÔ...)<br> ¹°·Ð ³ª´Â ±â´Ù¸± °ÍÀÔ´Ï´Ù!";
$_lang['Next'] = "´ÙÀ½";
$_lang['Notification on new event in others calendar'] = "´Ù¸¥ ´Þ·ÂÀÇ »õ·Î¿î ÀÌº¥Æ®°¡ °øÁöµË´Ï´Ùr";
$_lang['Path to sendfax'] = "ÆÑ½º °æ·Î";
$_lang['no fax option: leave blank'] = "ÆÑ½º¿É¼Ç¾øÀ½: °ø¹éÀ¸·Î ºñ¿ì¼¼¿ä";
$_lang['Please read the FAQ about the installation with postgres'] = "postgres¸¦ »ç¿ëÇÒ °æ¿ì´Â ¼³Ä¡ FAQ¸¦ ÀÐ¾îº¸½Ê½Ã¿À";
$_lang['Length of short names<br> (Number of letters: 3-6)'] = "´ÜÃàÀÌ¸§Àº <br> (´Ü¾î¼ö: 3-6)";
$_lang['If you want to install PHProjekt manually, you find<a href=http://www.phprojekt.com/files/sql_dump.tar.gz target=_blank>here</a> a mysql dump and a default config.inc.php'] = "PHProjekt ¸¦ ¼öµ¿À¸·Î ¼³Ä¡ÇÒ·Á¸é
<a href='http://www.phprojekt.com/files/sql_dump.tar.gz' target=_blank>¿©±â</a>¿¡¼­ mysql dump È­ÀÏ°ú ±âº» config.inc.php¸¦ Ã£´Â´Ù.";
$_lang['The server needs the privilege to write to the directories'] = "¼­¹öÀÇ µð·ºÅä¸®¿¡ ´ëÇÑ ¾²±â±ÇÇÑ(w)ÀÌ ÀÖ¾î¾ß ÇÕ´Ï´Ù.";
$_lang['Header groupviews'] = "±×·ìº¸±â Çì´õ";
$_lang['name, F.'] = "name, F.";
$_lang['shortname'] = "´ÜÃà¸í";
$_lang['loginname'] = "·Î±×ÀÎÀÌ¸§";
$_lang['Please create the file directory'] = "ÆÄÀÏ µð·ºÅä¸®¸¦ ¸¸µå¼¼¿ä";
$_lang['default mode for forum tree: 1 - open, 0 - closed'] = "Æ÷·³ Æ®¸®¸¦ À§ÇÑ ±âº»¸ðµå: 1 - ¿­¸², 0 - ´ÝÈû";
$_lang['Currency symbol'] = "ÅëÈ­ ±âÈ£";
$_lang['current'] = "½ÇÁ¦";
$_lang['Default size of form elements'] = "Default size of form elements";
$_lang['use LDAP'] = "LDAP ÀÌ¿ë";
$_lang['Allow parallel events'] = "º´·Ä ÀÌº¥Æ® Çã¿ë";
$_lang['Timezone difference [h] Server - user'] = "Å¸ÀÓÁ¸ Â÷ÀÌ [h] ¼­¹ö - »ç¿ëÀÚ";
$_lang['Timezone'] = "Å¸ÀÓÁ¸";
$_lang['max. hits displayed in search module'] = "°Ë»ö¸ðµâ¿¡ Ç¥½ÃµÈ ÃÖ°í hits";
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
$_lang['Resource List'] = "ÀÚ¿ø¸ñ·Ï";
$_lang['Event List'] = "ÀÌº¥Æ® º¸±â";
$_lang['Calendar Views'] = "´Þ·Â º¸±â";

$_lang['Personnel'] = "ÀÎ»ç±â·Ï";

$_lang['Create new event'] = "ÀÌº¥Æ® »ý¼º";
$_lang['Day'] = "³¯Â¥";

$_lang['Until'] = "±îÁö";

$_lang['Note'] = "³ëÆ®";
$_lang['Project'] = "ÇÁ·ÎÁ§Æ®";
$_lang['Res'] = "Res";
$_lang['Once'] = "ÇÑ¹ø";
$_lang['Daily'] = "¸ÅÀÏ";
$_lang['Weekly'] = "¸ÅÁÖ";
$_lang['Monthly'] = "¸Å¿ù";
$_lang['Yearly'] = "¸Å³â";

$_lang['Create'] = "»ý¼º";

$_lang['Begin'] = "½ÃÀÛ";
$_lang['Out of office'] = "ÀÚ¸® ºñ¿ò";
$_lang['Back in office'] = "¾÷¹«º¹±Í";
$_lang['End'] = "Á¾·á";
$_lang['@work'] = "@work";
$_lang['We'] = "We";
$_lang['group events'] = "±×·ì ÀÌº¥Æ®";
$_lang['or profile'] = "¶Ç´Â ÇÁ·ÎÇÊ";
$_lang['All Day Event'] = "ÇÏ·çÁ¾ÀÏÀÇ ÀÌº¥Æ®";
$_lang['time-axis:'] = "½Ã°£-Ãà:";
$_lang['vertical'] = "¼öÁ÷";
$_lang['horizontal'] = "¼öÆò";
$_lang['Horz. Narrow'] = "hor. ±ÙÁ¢";
$_lang['-interval:'] = "-°£°Ý:";
$_lang['Self'] = "ÀÚ½Å";

$_lang['...write'] = "...¾²±â";

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
$_lang['Please call login.php!'] = "'login.php'À» ºÒ·¯¿À¼¼¿ä!";

// m1.php
$_lang['There are other events!<br>the critical appointment is: '] = "´Ù¸¥ ÀÌº¥Æ®°¡ Á¸ÀçÇÕ´Ï´Ù!<br>Áß¿äÇÑ ¾à¼ÓÀº: ";
$_lang['Sorry, this resource is already occupied: '] = "ÁË¼ÛÇÕ´Ï´Ù. ÀÌ ÀÚ¿øÀº ÀÌ¹Ì »ç¿ëÁßÀÔ´Ï´Ù: ";
$_lang[' This event does not exist.<br> <br> Please check the date and time. '] = "ÀÌ ÀÌº¥Æ®°¡ Á¸ÀçÇÏÁö ¾Ê½À´Ï´Ù.<br><br>³¯Â¥¿Í ½Ã°£À» È®ÀÎÇÏ½Ê½Ã¿À. ";
$_lang['Please check your date and time format! '] = "³¯Â¥¿Í ½Ã°£ÀÇ Çü½ÄÀ» È®ÀÎÇÏ¼¼¿ä! ";
$_lang['Please check the date!'] = "³¯Â¥¸¦ È®ÀÎÇÏ¼¼¿ä!";
$_lang['Please check the start time! '] = "½ÃÀÛ½Ã°£À» È®ÀÎÇÏ¼¼¿ä! ";
$_lang['Please check the end time! '] = "Á¾·á½Ã°£À» È®ÀÎÇÏ¼¼¿ä! ";
$_lang['Please give a text or note!'] = "º»¹®ÀÌ³ª ÂªÀº ³ëÆ®¸¦ ±â·ÏÇÏ¼¼¿ä!";
$_lang['Please check start and end time! '] = "½ÃÀÛ°ú Á¾·á ½Ã°£À» È®ÀÎÇÏ¼¼¿ä! ";
$_lang['Please check the format of the end date! '] = "Á¾·áÀÏÀÇ Çü½ÄÀ» È®ÀÎÇÏ¼¼¿ä! ";
$_lang['Please check the end date! '] = "Á¾·áÀÏÀ» È®ÀÎÇÏ¼¼¿ä! ";





$_lang['Resource'] = "ÀÚ¿ø";
$_lang['User'] = "»ç¿ëÀÚ";

$_lang['delete event'] = "ÀÌº¥Æ® »èÁ¦";
$_lang['Address book'] = "ÁÖ¼Ò·Ï";


$_lang['Short Form'] = "´ÜÃà ÇüÅÂ";

$_lang['Phone'] = "ÀüÈ­";
$_lang['Fax'] = "ÆÑ½º";



$_lang['Bookmark'] = "ºÏ¸¶Å©";
$_lang['Description'] = "¼³¸í";

$_lang['Entire List'] = "ÀüÃ¼¸ñ·Ï";

$_lang['New event'] = "»õ·Î¿î ÀÌº¥Æ®";
$_lang['Created by'] = "Created by";
$_lang['Red button -> delete a day event'] = "»¡°£¹öÆ° -> ÇÏ·ç ÀÌº¥Æ® Áö¿ò";
$_lang['multiple events'] = "´ÙÁß ÀÌº¥Æ®";
$_lang['Year view'] = "ÀÏ³âº¸±â";
$_lang['calendar week'] = "ÁÖ ´Þ·Â"; 

//m2.php
$_lang['Create &amp; Delete Events'] = "»ý¼º &amp; ÀÌº¥Æ®»èÁ¦";
$_lang['normal'] = "ÀÏ¹Ý";
$_lang['private'] = "°³ÀÎ";
$_lang['public'] = "°ø°³";
$_lang['Visibility'] = "º¸¿©ÁÖ±â";

//mail module
$_lang['Please select at least one (valid) address.'] = "Àû¾îµµ ÇÑ»ç¶÷ÀÌ»ó ¼±ÅÃÇÏ¼¼¿ä.";
$_lang['Your mail has been sent successfully'] = "¸ÞÀÏÀÌ ¼º°øÀûÀ¸·Î ¹ß¼ÛµÇ¾ú½À´Ï´Ù";
$_lang['Attachment'] = "Ã·ºÎÈ­ÀÏ";
$_lang['Send single mails'] = "´ÜÀÏ¸ÞÀÏ ¹ß¼Û";
$_lang['Does not exist'] = "Á¸ÀçÇÏÁö ¾Ê½À´Ï´Ù";
$_lang['Additional number'] = "Ãß°¡ ¼ýÀÚ";
$_lang['has been canceled'] = "Ãë¼ÒµÇ¾ú½À´Ï´Ù";

$_lang['marked objects'] = "Ç¥½ÃµÈ °´Ã¼";
$_lang['Additional address'] = "Ãß°¡ÁÖ¼Ò";
$_lang['in mails'] = "¸ÞÀÏ¿¡¼­";
$_lang['Mail account'] = "¸ÞÀÏ Konto";
$_lang['Body'] = "º»¹®";
$_lang['Sender'] = "º¸³»´Â»ç¶÷";

$_lang['Receiver'] = "¹Þ´Â»ç¶÷";
$_lang['Reply'] = "È¸½Å";
$_lang['Forward'] = "Àü´Þ";
$_lang['Access error for mailbox'] = "¸ÞÀÏ¹Ú½º Á¢±Ù½ÇÆÐ";
$_lang['Receive'] = "¸ÞÀÏ¹Þ±â";
$_lang['Write'] = "¾²±â";
$_lang['Accounts'] = "°èÁ¤";
$_lang['Rules'] = "±ÔÄ¢";
$_lang['host name'] = "È£½ºÆ®ÀÌ¸§";
$_lang['Type'] = "Å¸ÀÔ";
$_lang['misses'] = "³õÄ§";
$_lang['has been created'] = "»ý¼ºµÊ";
$_lang['has been changed'] = "¹Ù²ñ";
$_lang['is in field'] = "ÇÊµå¿¡¼­";
$_lang['and leave on server'] = "¼­¹ö¿¡ ³²°ÜµÒ";
$_lang['name of the rule'] = "±ÔÄ¢ÀÇ ÀÌ¸§";
$_lang['part of the word'] = "´Ü¾îÀÇ ºÎºÐ";
$_lang['in'] = "in";
$_lang['sent mails'] = "¸ÞÀÏ ¹ß¼Û";
$_lang['Send date'] = "³¯ÀÚ ¹ß¼Û";
$_lang['Received'] = "¹Þ¾ÒÀ½";
$_lang['to'] = "¹Þ´Â»ç¶÷";
$_lang['imcoming Mails'] = "¹ÞÀº¸ÞÀÏ";
$_lang['sent Mails'] = "º¸³½¸ÞÀÏ";
$_lang['Contact Profile'] = "¿¬¶ôÃ³ ÇÁ·ÎÇÊ";
$_lang['unread'] = "ÀÐÁö¾ÊÀ½";
$_lang['view mail list'] = "¸ÞÀÏ ¸ñ·Ïº¸±â";
$_lang['insert db field (only for contacts)'] = "db ÇÊµå¿¡ »ðÀÔ(¿¬¶ôÃ³¸¸À» À§ÇØ)";
$_lang['Signature'] = "»çÀÎ";

$_lang['SMS'] = "SMS";
$_lang['Single account query'] = "´ÜÀÏ°èÁ¤ ÁúÀÇ";
$_lang['Notice of receipt'] = "¼ö½ÅÈ®ÀÎ";
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
$_lang['Mail note to'] = "³ëÆ®¸¦ ¸ÞÀÏ·Î º¸³¾ »ç¶÷";
$_lang['added'] = "Ãß°¡µÊ";
$_lang['changed'] = "¹Ù²ñ";

// o.php
$_lang['Calendar'] = "´Þ·Â";
$_lang['Contacts'] = "¿¬¶ôÃ³";


$_lang['Files'] = "È­ÀÏ";



$_lang['Options'] = "¿É¼Ç";
$_lang['Timecard'] = "½Ã°£±â·Ï";

$_lang['Helpdesk'] = "Helpdesk";

$_lang['Info'] = "Á¤º¸";
$_lang['Todo'] = "ÇØ¾ßÇÒÀÏ";
$_lang['News'] = "»õ¼Ò½Ä";
$_lang['Other'] = "Other";
$_lang['Settings'] = "±¸¼º";
$_lang['Summary'] = "¿ä¾à";

// options.php
$_lang['Description:'] = "¼³¸í:";
$_lang['Comment:'] = "ÀÇ°ß:";
$_lang['Insert a valid Internet address! '] = "À¯È¿ÇÑ ÀÎÅÍ³Ý ÁÖ¼Ò¸¦ ÀÔ·ÂÇÏ½Ê½Ã¿À! ";
$_lang['Please specify a description!'] = "¼³¸íÀ» ±¸Ã¼ÀûÀ¸·Î ÇØ ÁÖ½Ê½Ã¿À!";
$_lang['This address already exists with a different description'] = "ÀÌ ÁÖ¼Ò°¡ ´Ù¸¥ ¼³¸íÀ¸·Î Á¸ÀçÇÕ´Ï´Ù";
$_lang[' already exists. '] = " ÀÌ¹Ì Á¸ÀçÇÕ´Ï´Ù. ";
$_lang['is taken to the bookmark list.'] = "ºÏ¸¶Å© ¸ñ·ÏÀ¸·Î °¡Á®°¬½À´Ï´Ù.";
$_lang[' is changed.'] = "º¯°æµÇ¾ú½À´Ï´Ù.";
$_lang[' is deleted.'] = "»èÁ¦µÇ¾ú½À´Ï´Ù.";
$_lang['Please specify a description! '] = "±¸Ã¼ÀûÀ¸·Î ¼³¸íÇÏ¼¼¿ä! ";
$_lang['Please select at least one name! '] = "Àû¾îµµ ÇÏ³ªÀÇ ÀÌ¸§À» ¼±ÅÃÇØ¾ß ÇÕ´Ï´Ù! ";
$_lang[' is created as a profile.<br>'] = " ÇÁ·ÎÇÊ·Î ¸¸µé¾ú½À´Ï´Ù.<br>";
$_lang['is changed.<br>'] = "º¯°æµÇ¾ú½À´Ï´Ù.<br>";
$_lang['The profile has been deleted.'] = "ÇÁ·ÎÇÊÀÌ »èÁ¦µÇ¾ú½À´Ï´Ù.";
$_lang['Please specify the question for the poll! '] = "ÅõÇ¥¸¦ À§ÇÑ Áú¹®À» ÀÔ·ÂÇÏ½Ê½Ã¿À! ";
$_lang['You should give at least one answer! '] = "Àû¾îµµ ÇÏ³ªÀÇ ´ë´äÀ» ¼±ÅÃÇØ¾ß ÇÕ´Ï´Ù. ";
$_lang['Your call for votes is now active. '] = "ÅõÇ¥ ¿äÃ»ÀÌ È°¼ºÈ­ µÇ¾ú½À´Ï´Ù ";
$_lang['<h2>Bookmarks</h2>In this section you can create, modify or delete bookmarks:'] = "<h2>ºÏ¸¶Å©</h2> ÀÌ ¼½¼Ç¿¡¼­´Â ºÏ¸¶Å©¸¦ ¸¸µé°í, ¼öÁ¤ÇÏ°Å³ª »èÁ¦ÇÒ ¼ö ÀÖ½À´Ï´Ù:";
$_lang['Create'] = "»ý¼º";


$_lang['<h2>Profiles</h2>In this section you can create, modify or delete profiles:'] = "<h2>ÇÁ·ÎÇÊ</h2> ÀÌ ¼½¼Ç¿¡¼­´Â ÇÁ·ÎÇÊÀ» ¸¸µé°í, ¼öÁ¤ÇÏ°Å³ª »èÁ¦ÇÒ ¼ö ÀÖ½À´Ï´Ù:";
$_lang['<h2>Voting Formula</h2>'] = "<h2>ÅõÇ¥ ¿øÄ¢</h2> ";
$_lang['In this section you can create a call for votes.'] = "ÀÌ°÷¿¡¼­ ÅõÇ¥¿äÃ»À» »ý¼ºÇÒ ¼ö ÀÖ½À´Ï´Ù";
$_lang['Question:'] = "Áú¹®:";
$_lang['just one <b>Alternative</b> or'] = "<h2>¾çÀÚÅÃÀÏ</h2> ¶Ç´Â";
$_lang['several to choose?'] = "¿©·¯°³ Áß ¼±ÅÃ?";

$_lang['Participants:'] = "Âü°¡ÀÚ:";

$_lang['<h3>Password Change</h3> In this section you can choose a new random generated password.'] = "<h3>ºñ¹Ð¹øÈ£ º¯°æ</h3> ÀÌ°÷¿¡¼­ ´ç½ÅÀº »õ·Î¿î ·£´ý ºñ¹Ð¹øÈ£¸¦ ¼±ÅÃÇÒ ¼ö ÀÖ½À´Ï´Ù.";
$_lang['Old Password'] = "±¸ ºñ¹Ð¹øÈ£";
$_lang['Generate a new password'] = "»õ·Î¿î ºñ¹Ð¹øÈ£¸¦ ¸¸µå¼¼¿ä";
$_lang['Save password'] = "ºñ¹Ð¹øÈ£ ÀúÀå";
$_lang['Your new password has been stored'] = "»õ·Î¿î ºñ¹Ð¹øÈ£°¡ ÀúÀåµÇ¾ù½À´Ï´Ù";
$_lang['Wrong password'] = "ºñ¹Ð¹øÈ£°¡ Àß¸øµÇ¾ú½À´Ï´Ù";
$_lang['Delete poll'] = "ÅõÇ¥ »èÁ¦";
$_lang['<h4>Delete forum threads</h4> Here you can delete your own threads<br>Only threads without a comment will appear.'] = "<h4>°ü·Ã±Û»èÁ¦</h4> °ø°³ Åä·ÐÀå¿¡¼­ ´ç½ÅÀº °ü·Ã±ÛÀ»
 »èÁ¦ÇÒ ¼ö ÀÖ½À´Ï´Ù.<br>ÀÇ°ß¾ø´Â °ü·Ã±Û¸¸ ³ªÅ¸³¯ °Í ÀÔ´Ï´Ù.";

$_lang['Old password'] = "±âÁ¸ ÆÐ½º¿öµå";
$_lang['New Password'] = "½Å±Ô ÆÐ½º¿öµå";
$_lang['Retype new password'] = "½Å±ÔÆÐ½º¿öµå ÀçÀÔ·Â";
$_lang['The new password must have 5 letters at least'] = "½Å±Ô ÆÐ½º¿öµå´Â ÃÖ¼Ò 5ÀÚ ÀÌ»óÀÔ´Ï´Ù.";
$_lang['You didnt repeat the new password correctly'] = "¹Ýº¹ÇÑ ÆÐ½º¿öµå°¡ ¸ÂÁö ¾Ê½À´Ï´Ù.";

$_lang['Show bookings'] = "¿¹¾à º¸±â";
$_lang['Valid characters'] = "À¯È¿ÇÑ ¹®ÀÚ";
$_lang['Suggestion'] = "Á¦¾È";
$_lang['Put the word AND between several phrases'] = "´Ü¾î AND ¸¦ ¿©·¯ ±¸¹®»çÀÌ¿¡ ³ÖÀ¸½Ã¿À."; // translators: please leave the word AND as it is
$_lang['Write access for calendar'] = "´Þ·Â¿¡ ¾²±â±ÇÇÑ";
$_lang['Write access for other users to your calendar'] = "´ç½ÅÀÇ ´Þ·Â¿¡ ´Ù¸¥ »ç¶÷ÀÇ ¾²±â±ÇÇÑ";
$_lang['User with chief status still have write access'] = "i´ëÀåÁöÀ§¿¡ ÀÖ´Â »ç¶÷Àº ¾²±â±ÇÇÑÀÌ ÀÖÀ½";

// projects
$_lang['Project Listing'] = "ÇÁ·ÎÁ§Æ® ¸®½ºÆ®";
$_lang['Project Name'] = "ÇÁ·ÎÁ§Æ®¸í";


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
$_lang['Participants'] = "Âü¿©ÀÚ";
$_lang['Priority'] = "¿ì¼±¼øÀ§";
$_lang['Status'] = "ÁøÇà»óÅÂ";
$_lang['Last status change'] = "ÃÖÁ¾<br>¼öÁ¤";
$_lang['Leader'] = "ÁöÈÖÀÚ";
$_lang['Statistics'] = "Åë°è";
$_lang['My Statistic'] = "³ª¸¸ÀÇ Åë°è";

$_lang['Person'] = "ÆÀ¿ø";
$_lang['Hours'] = "½Ã°£";
$_lang['Project summary'] = "ÇÁ·ÎÁ§Æ® ¿ä¾à";
$_lang[' Choose a combination Project/Person'] = " ÇÁ·ÎÁ§Æ®¿Í ÆÀ¿øÀ» Á¶ÇÕÇØ ¼±ÅÃÇÏ½Ê½Ã¿À.";
$_lang['(multiple select with the Ctrl/Cmd-key)'] = "(ÄÜÆ®·ÑÅ°¸¦ ´©¸£¸é ¿©·¯°³¸¦ ¼±ÅÃÇÒ¼ö ÀÖ½À´Ï´Ù.)";

$_lang['Persons'] = "ÆÀ¿ø";
$_lang['Begin:'] = "½ÃÀÛ";
$_lang['End:'] = "Á¾·á";
$_lang['All'] = "¸ðµÎ";
$_lang['Work time booked on'] = "¾÷¹«½Ã°¢ ¿¹¾à";
$_lang['Sub-Project of'] = "ºÎÇÁ·ÎÁ§Æ®";
$_lang['Aim'] = "¸ñÇ¥";
$_lang['Contact'] = "¿¬¶ôÃ³";
$_lang['Hourly rate'] = "½Ã°£´ç ºñÀ²";
$_lang['Calculated budget'] = "°è»êµÈ ¿¹»êºñ¿ë";
$_lang['New Sub-Project'] = "½Å±Ô ºÎÇÁ·ÎÁ§Æ®";
$_lang['Booked To Date'] = "ÇöÀç±îÁö ¿¹¾àµÊ";
$_lang['Budget'] = "¿¹»ê";
$_lang['Detailed list'] = "»ó¼¼ ¸ñ·Ï";
$_lang['Gantt'] = "½Ã°£¼±";
$_lang['offered'] = "Á¦°øµÊ";
$_lang['ordered'] = "ÁÖ¹®µÊ";
$_lang['Working'] = "ÀÛ¾÷Áß";
$_lang['ended'] = "Á¾·á";
$_lang['stopped'] = "ÁßÁöµÊ";
$_lang['Re-Opened'] = "»õ·Î ¿­¸²";
$_lang['waiting'] = "´ë±â";
$_lang['Only main projects'] = "ÁÖ ÇÁ·ÎÁ§Æ®¸¸";
$_lang['Only this project'] = "ÀÌ ÇÁ·ÎÁ§Æ®¸¸";
$_lang['Begin > End'] = "½ÃÀÛ > ³¡";
$_lang['ISO-Format: yyyy-mm-dd'] = "ISO-Æ÷¸ä: yyyy-mm-dd";
$_lang['The timespan of this project must be within the timespan of the parent project. Please adjust'] = "ÀÌ ÇÁ·ÎÁ§Æ®ÀÇ ±â°£Àº ¸ð ÇÁ·ÎÁ§Æ®ÀÇ ±â°£³» ÀÌ¾î¾ß ÇÑ´Ù. ÀúÁ¤ÇÏ½Ã¿À.";
$_lang['Please choose at least one person'] = "Àû¾îµµ ÇÑ »ç¶÷Àº ¼±ÅÃÇÏ½Ã¿À";
$_lang['Please choose at least one project'] = "Àû¾îµµ ÇÑ °úÁ¦´Â ¼±ÅÃÇÏ½Ã¿À";
$_lang['Dependency'] = "Á¾¼Ó¼º";
$_lang['Previous'] = "ÀÌÀü";

$_lang['cannot start before the end of project'] = "°úÁ¦°¡ ³¡³ª±â Àü¿¡ ½ÃÀÛÇÒ  ¼ö ¾øÀ½";
$_lang['cannot start before the start of project'] = "°úÁ¦°¡ ½ÃÀÛÇÏ±âÀü¿¡ ½ÃÀÛÇÒ ¼ö ¾øÀ½";
$_lang['cannot end before the start of project'] = "°úÁ¦°¡ ½ÃÀÛÇÏ±âÀü¿¡ ³¡³¯ ¼ö ¾øÀ½";
$_lang['cannot end before the end of project'] = "°úÁ¦°¡ ³¡³ª±â Àü¿¡ ³¡³¯ ¼ö ¾øÀ½";
$_lang['Warning, violation of dependency'] = "ÁÖÀÇ Á¾¼Ó¼º À§¹Ý";
$_lang['Container'] = "ÄÜÅ×ÀÌ³Ê";
$_lang['External project'] = "¿ÜºÎ °úÁ¦";
$_lang['Automatic scaling'] = "ÀÚµ¿ ½ºÄÉÀÏ¸µ";
$_lang['Legend'] = "Legend";
$_lang['No value'] = "°ªÀÌ ¾øÀ½";
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
$_lang['please check the status!'] = "»óÅÂ¸¦ Ã¼Å©ÇÏ¼¼¿ä!";
$_lang['Todo List: '] = "ÇØ¾ßÇÒÀÏ ¸ñ·Ï: ";
$_lang['New Remark: '] = "»õ·Î¿î ÀÇ°ß: ";
$_lang['Delete Remark '] = "ÀÇ°ß »èÁ¦ ";
$_lang['Keyword Search'] = "Å°¿öµå °Ë»ö: ";
$_lang['Events'] = "ÀÌº¥Æ®";
$_lang['the forum'] = "Æ÷·³";
$_lang['the files'] = "È­ÀÏ";
$_lang['Addresses'] = "ÁÖ¼Ò·Ï";
$_lang['Extended'] = "È®ÀåµÈ";
$_lang['all modules'] = "¸ðµç ¸ðµâ";
$_lang['Bookmarks:'] = "ºÏ¸¶Å©:";
$_lang['List'] = "¸®½ºÆ®";
$_lang['Projects:'] = "ÇÁ·ÎÁ§Æ®:";

$_lang['Deadline'] = "¿Ï·á½ÃÇÑ";

$_lang['Polls:'] = "ÅõÇ¥:";

$_lang['Poll created on the '] = "'ÅõÇ¥ÇÏ±â'¸¦ ¸¸µé¾ú½À´Ï´Ù. ";


// reminder.php
$_lang['Starts in'] = "½ÃÀÛÇÕ´Ï´Ù.";
$_lang['minutes'] = "ºÐ";
$_lang['No events yet today'] = "¿À´Ã ÀÌº¥Æ®°¡ ¾ø½À´Ï´Ù";
$_lang['New mail arrived'] = "»õ·Î¿î ¸ÞÀÏ µµÂø";

//ress.php

$_lang['List of Resources'] = "ÀÚ¿ø ¸®½ºÆ®";
$_lang['Name of Resource'] = "ÀÚ¿ø¸í";
$_lang['Comments'] = "ÄÚ¸àÆ®";


// roles
$_lang['Roles'] = "¿ªÇÒ";
$_lang['No access'] = "±ÇÇÑ ¾øÀ½";
$_lang['Read access'] = "ÀÐ±â ±ÇÇÑ";

$_lang['Role'] = "¿ªÇÒ";

// helpdesk - rts
$_lang['Request'] = "¿äÃ»";

$_lang['pending requests'] = "¹Ì°á »çÇ×";
$_lang['show queue'] = "´ë±â»óÅÂ º¸±â";
$_lang['Search the knowledge database'] = "Áö½Äµ¥ÀÌÅÍº£ÀÌ½º °Ë»ö";
$_lang['Keyword'] = "Å°¿öµå";
$_lang['show results'] = "°á°ú º¸±â";
$_lang['request form'] = "¿äÃ» Çü½Ä";
$_lang['Enter your keyword'] = "Å°¿öµå¸¦ ÀÔ·Â";
$_lang['Enter your email'] = "ÀÌ¸ÞÀÏ ÀÔ·Â";
$_lang['Give your request a name'] = "´ç½ÅÀÇ ¿äÃ»¿¡ ÀÌ¸§À» ºÎ¿©ÇÏ½Ã¿À";
$_lang['Describe your request'] = "¿äÃ»¿¡ ´ëÇÑ ¼³¸í";

$_lang['Due date'] = "¸¸±âÀÏ";
$_lang['Days'] = "ÀÏ";
$_lang['Sorry, you are not in the list'] = "ÁË¼ÛÇÕ´Ï´Ù, ´ç½ÅÀº ¸ñ·Ï¿¡ ÀÖÁú ¾Ê½À´Ï´Ù.";
$_lang['Your request Nr. is'] = "´ç½ÅÀÇ ¿äÃ» ¹øÈ£´Â ";
$_lang['Customer'] = "°í°´";


$_lang['Search'] = "Ã£±â";
$_lang['at'] = "at";
$_lang['all fields'] = "¸ðµç ÇÊµå";


$_lang['Solution'] = "ÇØ°áÃ¥";
$_lang['AND'] = "AND";

$_lang['pending'] = "¹Ì°á";
$_lang['stalled'] = "Á¤Ã¼µÊ";
$_lang['moved'] = "¿Å°ÜÁü";
$_lang['solved'] = "ÇØ°áµÊ";
$_lang['Submit'] = "Á¦Ãâ";
$_lang['Ass.'] = "Ass.";
$_lang['Pri.'] = "Pri.";
$_lang['access'] = "Á¢±Ù";
$_lang['Assigned'] = "¹èÁ¤µÈ";

$_lang['update'] = "°»½Å";
$_lang['remark'] = "ÀÇ°ß";
$_lang['solve'] = "ÇØ°á";
$_lang['stall'] = "Á¤Ã¼";
$_lang['cancel'] = "Ãë¼Ò";
$_lang['Move to request'] = "¿äÃ»À¸·Î ÀÌµ¿";
$_lang['Dear customer, please refer to the number given above by contacting us.Will will perform your request as soon as possible.'] = "°í°´´Ô, À§ÀÇ ¹øÈ£¸¦ ÂüÁ¶ÇÏ¿© ¿¬¶ôÀ» ÁÖ½Ê½Ã¿ä. ´ç½ÅÀÇ ¿äÃ»À» °¡´ÉÇÑÇÑ »¡¸® Ã³¸®ÇÏ°Ú½À´Ï´Ù.
";
$_lang['Your request has been added into the request queue.<br>You will receive a confirmation email in some moments.'] = "´ç½ÅÀÇ ¿äÃ»Àº ¿äÃ»´ë±â¿­¿¡ Æ÷ÇÔµÇ¾úÀ½´Ï´Ù.<br>
´ç½ÅÀº Àá½ÃÈÄ¿¡ È®ÀÎ ¸ÞÀÏÀ» ¹ÞÀ» °ÍÀÔ´Ï´Ù.";
$_lang['n/a'] = "n/a";
$_lang['internal'] = "³»ºÎÀÇ";

$_lang['has reassigned the following request'] = "´ç½ÅÀÇ ¿äÃ»À» Àç¹èÁ¤ Çß½À´Ï´Ù.";
$_lang['New request'] = "»õ·Î¿î ¿äÃ»";
$_lang['Assign work time'] = "ÀÛ¾÷½Ã°£ ÇÒ´ç";
$_lang['Assigned to:'] = "ÇÒ´çµÊ:";

$_lang['Your solution was mailed to the customer and taken into the database.'] = "´ç½ÅÀÇ ÇØ°áÃ¥ÀÎ °í°´¿¡°Ô ¸ÞÀÏ·Î º¸³»Á³°í µ¥ÀÌÅ¸º£ÀÌ½º¿¡ Ãß°¡µÇ¾ú½À´Ï´Ù.";
$_lang['Answer to your request Nr.'] = "´ç½ÅÀÇ ´ÙÀ½ ¿äÃ»¹øÈ£¿¡ ´ëÇÑ ´äº¯ Nr.";
$_lang['Fetch new request by mail'] = "¸ÞÀÏ·Î »õ·Î¿î ¿äÃ» °¡Á®¿È";
$_lang['Your request was solved by'] = "´ç½ÅÀÇ ¿äÃ»ÀÌ ´ÙÀ½ »ç¶÷¿¡ ÀÇÇØ ÇØ°áµÇ¾úÀ½";

$_lang['Your solution was mailed to the customer and taken into the database'] = "´ç½ÅÀÇ ÇØ°áÃ¥ÀÌ µ¥ÀÌÅ¸º£ÀÌ½º¿¡ Ãß°¡µÇ¾ú°í °í°´¿¡°Ô ¸ÞÀÏÀÌ ¹ß¼ÛµÇ¾ú½À´Ï´Ù";
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
$_lang['The settings have been modified'] = "¼¼ÆÃÀÌ ¼öÁ¤µÇ¾úÀ½";
$_lang['Skin'] = "Skin";
$_lang['First module view on startup'] = "½ÃÀÛ½Ã Ã³À½ ¸ðµâº¸±â";
$_lang['none'] = "¾øÀ½";
$_lang['Check for mail'] = "»õ ¸ÞÀÏ Ã¼Å©";
$_lang['Additional alert box'] = "Ãß°¡ ¾Ë¸² »óÀÚ";
$_lang['Horizontal screen resolution <br>(i.e. 1024, 800)'] = "¼öÆòÈ­¸é ÇØ»óµµ <br>(¿¹, 1024, 800)";
$_lang['Chat Entry'] = "´ëÈ­ ÀÔÀå";
$_lang['single line'] = "´ÜÀÏ ÁÙ";
$_lang['multi lines'] = "¿©·¯ ÁÙ";
$_lang['Chat Direction'] = "Chat Direction";
$_lang['Newest messages on top'] = "Newest messages on top";
$_lang['Newest messages at bottom'] = "Newest messages at bottom";
$_lang['File Downloads'] = "È­ÀÏ ³»·Á¹Þ±â";

$_lang['Inline'] = "Inline";
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
$_lang['Todays Events'] = "¿À´ÃÀÇ ÀÌº¥Æ®";
$_lang['New files'] = "»õ È­ÀÏ";
$_lang['New notes'] = "»õ ³ëÆ®";
$_lang['New Polls'] = "»õ ÅõÇ¥";
$_lang['Current projects'] = "Çö °úÁ¦";
$_lang['Help Desk Requests'] = "Helpdesk Anfragen";
$_lang['Current todos'] = "ÇöÀç ÇØ¾ßÇÒ ÀÏ";
$_lang['New forum postings'] = "»õ Æ÷·ëÀÇ °Ô½Ã";
$_lang['New Mails'] = "»õ ¸ÞÀÏ";

//timecard

$_lang['Theres an error in your time sheet: '] = "½Ã°£±â·ÏÇ¥¿¡ ¿À·ù°¡ ¹ß»ýÇß½À´Ï´Ù: ";




$_lang['Consistency check'] = "ÀÏ°ü¼º Ã¼Å©";
$_lang['Please enter the end afterwards at the'] = "´ÙÀ½ ÈÄ¿¡´Â Á¾·á¸¦ ÇÏ½Ê½Ã¿ä";
$_lang['insert'] = "ÀÔ·Â";
$_lang['Enter records afterwards'] = "ÈÄ¿¡ ±â·ÏÇÏ½Ê½Ã¿À.";
$_lang['Please fill in only emtpy records'] = "´ÜÁö ºóÄ­¸¸ Ã¤¿ì¼¼¿ä";

$_lang['Insert a period, all records in this period will be assigned to this project'] = "±â°£À» ±â·ÏÇÏ½Ã¿À, ÀÌ ±â°£³» ¸ðµç ±â·ÏÀº ÀÌ °úÁ¦¿¡ ÇÒ´çµÈ´Ù.";
$_lang['There is no record on this day'] = "ÀÌ³¯¿¡´Â ±â·ÏµÈ °ÍÀÌ ¾ø½À´Ï´Ù";
$_lang['This field is not empty. Please ask the administrator'] = "°ø°£ÀÌ ºñ¾îÀÖÁö ¾Ê½À´Ï´Ù. °ü¸®ÀÚ¿¡°Ô ¹®ÀÇÇÏ½Ê½Ã¿À";
$_lang['There is no open record with a begin time on this day!'] = "ÀÌ³¯ ½ÃÀÛµÈ ¿­¸° ±â·ÏÀº ¾ø´Ù.";
$_lang['Please close the open record on this day first!'] = "¸ÕÀú ¿­¸° ±â·ÏÀ» ´ÝÀ¸¼¼¿ä";
$_lang['Please check the given time'] = "ÁÖ¾îÁø ½Ã°£À» Ã¼Å©ÇÏ¼¼¿ä";
$_lang['Assigning projects'] = "°úÁ¦¸¦ ÇÒ´ç";
$_lang['Select a day'] = "³¯Â¥ ¼±ÅÃ";
$_lang['Copy to the boss'] = "º¸½º¿¡°Ô »çº» Á¦Ãâ";
$_lang['Change in the timecard'] = "Å¸ÀÓÄ«µå¿¡¼­ º¯°æ";
$_lang['Sum for'] = "ÇÕ°è";

$_lang['Unassigned time'] = "ÇÒ´çµÇÁö ¾ÊÀº ½Ã°£";
$_lang['delete record of this day'] = "ÀÌ³¯ÀÇ ±â·Ï Áö¿ò";
$_lang['Bookings'] = "±âÀÔ";

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
$_lang['accepted'] = "¼ö¶ôµÊ";
$_lang['rejected'] = "±â°¢µÊ";
$_lang['own'] = "ÀÚ½Å";
$_lang['progress'] = "ÁøÇà»çÇ×";
$_lang['delegated to'] = "delegated to";
$_lang['Assigned from'] = "´ÙÀ½À¸·ÎºÎÅÍ ¹è´çµÈ";
$_lang['done'] = "¿Ï·á";
$_lang['Not yet assigned'] = "Not yet assigned";
$_lang['Undertake'] = "Undertake";
$_lang['New todo'] = "New todo"; 
$_lang['Notify recipient'] = "Notify recipient";

// votum.php
$_lang['results of the vote: '] = "ÅõÇ¥°á°ú: ";
$_lang['Poll Question: '] = "ÅõÇ¥ÀÇ Áú¹®: ";
$_lang['several answers possible'] = "¿©·¯ ´äÀÌ °¡´É";
$_lang['Alternative '] = "´ë¾È ";
$_lang['no vote: '] = "ÅõÇ¥ ÇÏÁö ¾ÊÀ½: ";
$_lang['of'] = "of";
$_lang['participants have voted in this poll'] = "Âü°¡ÀÚµéÀÌ ÅõÇ¥Çß½À´Ï´Ù";
$_lang['Current Open Polls'] = "ÇöÀç ½ÃÇàÁßÀÎ ÅõÇ¥";
$_lang['Results of Polls'] = "¸ðµç ÅõÇ¥ÀÇ °á°ú¸ñ·Ï";
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