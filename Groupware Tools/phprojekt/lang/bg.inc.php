<?php // bg.inc.php, bulgarian version - optimized for use with default skin and version 4.1 of PHProjekt
// translation by George Smilianov <smilianov.dir.bg>, <smilianov@dir.bg>
// file version 1.1

$chars = array("À","Á","Â","Ã","Ä","Å","Æ","Ç","È","É","Ê","Ë","Ì","Í","Î","Ï","Ð","Ñ","Ò","Ó","Ô","Õ","Ö","×","Ú","Þ","ß");
$name_month = array("", "ßíóàðè", "Ôåâðóàðè", "Ìàðò", "Àïðèë", "Ìàé", "Þíè", "Þëè", "Àâãóñò", "Ñåïòåìâðè", "Îêòîìâðè", "Íîåìâðè", "Äåêåìâðè");
$l_text31a = array("ïî ïîäðàçáèðàíå", "15 ìèí.", "30 ìèí.", " 1 ÷àñ", " 2 ÷àñà", " 4 ÷àñà", " 1 äåí");
$l_text31b = array(0, 15, 30, 60, 120, 240, 1440);
$name_day = array("Íåäåëÿ", "Ïîíåäåëíèê", "Âòîðíèê", "Ñðÿäà", "×åòâúðòúê", "Ïåòúê", "Ñúáîòà");
$name_day2 = array("Ïî", "Âò", "Ñð", "×å", "Ïå", "Ñú","Íå");

$_lang['No Entries Found']= "No Entries Found";
$_lang['No Todays Events']= "HNo Todays Events";
$_lang['No new forum postings']= "No new forum postings";
$_lang['in category']= "in category";
$_lang['Filtered']= "Filtered";
$_lang['Sorted by']= "Sorted by";
$_lang['go'] = "go";
$_lang['back'] = "íàçàä";
$_lang['print'] = "îòïå÷àòâàíå";
$_lang['export'] = "åêñïîðò";
$_lang['| (help)'] = "| (ïîìîù)";
$_lang['Are you sure?'] = "Ñèãóðíè ëè ñòå?";
$_lang['items/page'] = "áðîÿ/ñòðàíèöà";
$_lang['records'] = "çàïèñè";
$_lang['previous page'] = "ïðåäèøíà ñòðàíèöà";
$_lang['next page'] = "ñëåäâàùà ñòðàíèöà";
$_lang['first page'] = "first page";
$_lang['last page'] = "last page";
$_lang['Move']  = "Ìåñòåíå";
$_lang['Copy'] = "Êîïèðàíå";
$_lang['Delete'] = "Èçòðèâàíå";
$_lang['Save'] = "Çàïàçâàíå";
$_lang['Directory'] = "Äèðåêòîðèÿ";
$_lang['Also Delete Contents'] = "èçòðèâàíå è íà ñúäúðæàíèåòî ñúùî";
$_lang['Sum'] = "Ñóìà";
$_lang['Filter'] = "Ôèëòúð";
$_lang['Please fill in the following field'] = "Ìîëÿ ïîïúëíåòå ñëåäíîòî ïîëå";
$_lang['approve'] = "îäîáðÿâàíå";
$_lang['undo'] = "îòìåíÿíå";
$_lang['Please select!'] = "Ìîëÿ èçáåðåòå!";
$_lang['New'] = "Íîâ";
$_lang['Select all'] = "Èçáåðè íà âñè÷êè";
$_lang['Printable view'] = "Èçãëåä çà îòïå÷àòâàíå";
$_lang['New record in module '] = "Íîâ çàïèñ â ìîäóë ";
$_lang['Notify all group members'] = "Îñâåäîìÿâàíå íà âñè÷êè ÷ëåíîâå íà ãðóïè";
$_lang['Yes'] = "Äà";
$_lang['No'] = "Íå";
$_lang['Close window'] = "Çàòâîðè ïðîçîðåöà";
$_lang['No Value'] = "Íÿìà Ñòîéíîñò";
$_lang['Standard'] = "Ñòàíäàðòíî";
$_lang['Create'] = "Anlegen";
$_lang['Modify'] = "Ändern";   
$_lang['today'] = "today";

// admin.php
$_lang['Password'] = "Ïàðîëà";
$_lang['Login'] = "Âëèçàíå";
$_lang['Administration section'] = "Àäìèíèñòðàòîðñêè îòäåë";
$_lang['Your password'] = "Âàøà ïàðîëà";
$_lang['Sorry you are not allowed to enter. '] = "Ñúæåëàâàìå, íî íÿìàòå äîñòúï. ";
$_lang['Help'] = "Ïîìîù";
$_lang['User management'] = "Óïðàâëåíèå íà ïîòðåáèòåëèòå";
$_lang['Create'] = "Ñúçäàâàíå";
$_lang['Projects'] = "Ïðîåêòè";
$_lang['Resources'] = "Ðåñóðñè";
$_lang['Resources management'] = "Resources management";
$_lang['Bookmarks'] = "Îòìåòêè";
$_lang['for invalid links'] = "çà íåâàëèäíè âðúçêè";
$_lang['Check'] = "Ïðîâåðêà";
$_lang['delete Bookmark'] = "èçòðèé Îòìåòêàòà";
$_lang['(multiple select with the Ctrl-key)'] = "(èçáèðàíå íà íÿêîëêî ñ 'Ctrl')";
$_lang['Forum'] = "Ôîðóì";
$_lang['Threads older than'] = "Íèøêè ïî-ñòàðè îò";
$_lang[' days '] = " äíè ";
$_lang['Chat'] = "×àò";
$_lang['save script of current Chat'] = "Çàïîìíÿíå íà ñêðèïòà íà íàñòîÿùèÿò ÷àò";
$_lang['Chat script'] = "×àò ñêðèïò";
$_lang['New password'] = "Íîâà ïàðîëà";
$_lang['(keep old password: leave empty)'] = "(çàïàçâàíå íà ñòàðàòà ïàðîëà: îñòàâåòå ïðàçíî)";
$_lang['Default Group<br> (must be selected below as well)'] = "Ãðóïà ïî ïîäðàçáèðàíå<br> (òðÿáâà äà áúäå èçáðàíà îòäîëó)";
$_lang['Access rights'] = "Ïðàâà íà äîñòúï";
$_lang['Zip code'] = "Zip êîä";
$_lang['Language'] = "Åçèê";
$_lang['schedule readable to others'] = "âúçìîæíîñò çà ÷åòåíå íà ïëàíà îò äðóãèòå";
$_lang['schedule invisible to others'] = "ïëàíúò å âèäèì çà äðóãèòå";
$_lang['schedule visible but not readable'] = "âúçìîæíîñò çà ÷åòåíå íà ïëàíà îò äðóãèòå íî íå ìîæå äà áúäå ÷åòåí";
$_lang['these fields have to be filled in.'] = "òåçè ïîëåòà òðÿáâà äà áúäàò ïîïúëíåíè.";
$_lang['You have to fill in the following fields: family name, short name and password.'] = "Òðÿáâà äà ïîïúëíèòå ñëåäíèòå ïîëåòà: ôàìèëèÿ, ìàëêî èìå è ïàðîëà.";
$_lang['This family name already exists! '] = "Òàçè ôàìèëèÿ âå÷å ñúùåñòâóâà! ";
$_lang['This short name already exists!'] = "Òîâà ìàëêî èìå âå÷å ñúùåñòâóâà!";
$_lang['This login name already exists! Please chosse another one.'] = "Òîâà ðåãèñòðàöèîííî èìå âå÷å ñúùåñòâóâà! Ìîëÿ èçáåðåòå äðóãî.";
$_lang['This password already exists!'] = "Òàçè ïàðîëà âå÷å ñúùåñòâóâà!";
$_lang['This combination first name/family name already exists.'] = "Òàçè êîìáèíàöèàÿ ìàëêî èìå/ôàìèëèÿ âå÷å ñúùåñòâóâà.";
$_lang['the user is now in the list.'] = "ïîòðåáèòåëÿò âå÷å å â ñïèñúêà.";
$_lang['the data set is now modified.'] = "íàáîðà îò äàííè å ïðîìåíåí.";
$_lang['Please choose a user'] = "Ìîëÿ èçáåðåòå ïîòðåáèòåë";
$_lang['is still listed in some projects. Please remove it.'] = "ïðîäúëæàâà äà áúäå âïèñàí â íÿêîè ïðîäóêòè. Ìîëÿ ïðåìàõíåòå ãî.";
$_lang['All profiles are deleted'] = "Âñè÷êè ïðîôèëè ñà èçòðèòè";
$_lang['A Profile with the same name already exists'] = "A profile with the same name already exists";
$_lang['is taken out of all user profiles'] = "is taken out of all user profiles";
$_lang['All todo lists of the user are deleted'] = "Âñè÷êè 'Äà íàïðàâÿ' ñïèñúöè íà ïîòðåáèòåëÿ ñà èçòðèòè";
$_lang['is taken out of these votes where he/she has not yet participated'] = "is taken out of these votes where he/she has not yet participated";
$_lang['All events are deleted'] = "Âñè÷êè ñúáèòèÿ ñà èçòðèòè";
$_lang['user file deleted'] = "ïîòðåáèòåëñêè ôàéë èçòðèò";
$_lang['bank account deleted'] = "áàíêîâà ñìåòêà èçòðèòà";
$_lang['finished'] = "çàâúðøåí";
$_lang['Please choose a project'] = "Ìîëÿ èçáåðåòå ïðîåêò";
$_lang['The project is deleted'] = "Òîçè ïðîåêò å èçòðèò";
$_lang['All links in events to this project are deleted'] = "Âñè÷êè âðúçêè êúì ñúáèòèÿ â òîçè ïðîåêò çà èçòðèòè";
$_lang['The duration of the project is incorrect.'] = "Ñðîêà çà âàëèäíîñò íà ïðîåêòà å ãðåøåí.";
$_lang['The project is now in the list'] = "Ïðîåêòà ñåãà å â ñïèñúêà";
$_lang['The project has been modified'] = "Òîçè ïðîåêò áåøå ïðîìåíåí";
$_lang['Please choose a resource'] = "Ìîëÿ èçáåðåòå ðåñóðñ";
$_lang['The resource is deleted'] = "Òîçè ðåñóðñ å èçòðèò";
$_lang['All links in events to this resource are deleted'] = "Âñè÷êè âðúçêè â ñúáèòèÿòà îòíîñíî òåçè ðåñóðñè ñà èçòðèòè";
$_lang[' The resource is now in the list.'] = " Ðåñóðñúò âå÷å å â ñïèñúêà.";
$_lang[' The resource has been modified.'] = " Ðåñóðñúò å ïðîìåíåí";
$_lang['The server sent an error message.'] = "Ñúðâúðúò èçïðàòî ñúîáùåíèå çà ãðåøêà.";
$_lang['All Links are valid.'] = "Âñè÷êè âðúçêè ñà âàëèäíè.";
$_lang['Please select at least one bookmark'] = "Ìîëÿ èçáåðåòå ïîíå åäíà îòìåòêà";
$_lang['The bookmark is deleted'] = "Òàçè îòìåòêà å èçòðèòà";
$_lang['threads older than x days are deleted.'] = "íèøêè ïî-ñòàðè îò x ñà èçòðèòè.";
$_lang['All chat scripts are removed'] = "Âñè÷êè ÷àò ñêðèïòîâå ñà ïðåìàõíàòè";
$_lang['or'] = "èëè";
$_lang['Timecard management'] = "Óïðàâëåíèå íà âðåìåâàòà êàðòà";
$_lang['View'] = "Âèæ";
$_lang['Choose group'] = "Èçáåðè ãðóïà";
$_lang['Group name'] = "Èìå íà ãðóïà";
$_lang['Short form'] = "Êúñà ôîðìà";
$_lang['Category'] = "Êàòåãîðèÿ";
$_lang['Remark'] = "Áåëåæêà";
$_lang['Group management'] = "Óïðàâëåíèå íà ãðóïà";
$_lang['Please insert a name'] = "Ìîëÿ âúâåäåòå èìå";
$_lang['Name or short form already exists'] = "Èìåòî íà êúñàòà ôîðìà âå÷å ñúùåñòâóâà";
$_lang['Automatic assign to group:'] = "Àâòîìàòè÷íî íàçíà÷àâàíå êúì ãðóïà:";
$_lang['Automatic assign to user:'] = "Àâòîìàòè÷íî íàçíà÷àâàíå êúì ïîòðåáèòåë:";
$_lang['Help Desk Category Management'] = "Helpdesk category management";
$_lang['Category deleted'] = "Êàòåãîðèÿòà å èçòðèòà";
$_lang['The category has been created'] = "Êàòåãîðèÿòà áåøå ñúçäàäåíà";
$_lang['The category has been modified'] = "Êàòåãîðèÿòà áåøå ïðîìåíåíà";
$_lang['Member of following groups'] = "×ëåí íà ñëåäíèòå ãðóïè";
$_lang['Primary group is not in group list'] = "Ïúðâè÷íàòà ãðóïà íå å â ñïèñúêà ñ ãðóïè";
$_lang['Login name'] = "Ðåãèñòðàöèîííî èìå";
$_lang['You cannot delete the default group'] = "Íå ìîæåòå äà èçòðèåòå ãðóïàòà ïî ïîäðàçáèðàíå";
$_lang['Delete group and merge contents with group'] = "Èçòðèâàíå íà ãðóïà è ñëèâàíå íà ñúäúðæàíèåòî ñ ãðóïà";
$_lang['Please choose an element'] = "Ìîëÿ èçáåðåòå åëåìåíò";
$_lang['Group created'] = "Ãðóïà ñúçäàäåíà";
$_lang['File management'] = "Óïðàâëåíèå íà ôàéëîâå";
$_lang['Orphan files'] = "Îñèðîòÿëè ôàéëîâå";
$_lang['Deletion of super admin root not possible'] = "Èçòðèâàíåòî íà ñóïåð àäìèíèñòðàòîðñêèÿò êîðåí íå å âúçìîæíî";
$_lang['ldap name'] = "ldap èìå";
$_lang['mobile // mobile phone'] = "ìîáèëåí òåë."; // mobile phone
$_lang['Normal user'] = "Íîðìàëåí ïîòðåáèòåë";
$_lang['User w/Chief Rights'] = "Ïîòðåáèòåë ñ äèðåêòîðñêè ïðàâà";
$_lang['Administrator'] = "Àäìèíèñòðàòîð";
$_lang['Logging'] = "Ðåãèñòðèðàíå";
$_lang['Logout'] = "Èçõîä";
$_lang['posting (and all comments) with an ID'] = "èçïðàøàíå (è âñè÷êè êîìåíòàðè) ñ ID";
$_lang['Role deleted, assignment to users for this role removed'] = "Ðîëÿòà èçòðèòà, âúçëàãàíèÿòà íà ïîòðåáèòåëè êúì òàçè ðîëÿ ñà ïðåìàõíàòè";
$_lang['The role has been created'] = "Ðîëÿòà áåøå ñúçäàäåíà";
$_lang['The role has been modified'] = "Ðîëÿòà áåøå ïðîìåíåíà";
$_lang['Access rights'] = "Access rights";
$_lang['Usergroup'] = "Gruppe";
$_lang['logged in as'] = "Angemeldet als";

//chat.php
$_lang['Quit chat']= "Quit chat";

//contacts.php
$_lang['Contact Manager'] = "Óïðàâëåíèå íà êîíòàêòè";
$_lang['New contact'] = "Íîâ êîíòàêò";
$_lang['Group members'] = "×ëåíîâå íà ãðóïà";
$_lang['External contacts'] = "Âúíøíè êîíòàêòè";
$_lang['&nbsp;New&nbsp;'] = "&nbsp;Íîâ&nbsp;";
$_lang['Import'] = "Èìïîðò";
$_lang['The new contact has been added'] = "Íîâèÿò êîíòàêò áåøå äîáàâåí";
$_lang['The date of the contact was modified'] = "Äàòàòà íà êîíòàêòà áå ïðîìåíåíà";
$_lang['The contact has been deleted'] = "Êîíòàêòà áåøå èçòðèò";
$_lang['Open to all'] = "Îòâîðè çà âñè÷êè";
$_lang['Picture'] = "Êàðòèíà";
$_lang['Please select a vcard (*.vcf)'] = "Ìîëÿ èçáåðåòå vcard (*.vcf)";
$_lang['create vcard'] = "Ñúçäàâàíå íà vcard";
$_lang['import address book'] = "èìïîðò íà àäðåñíàòà êíèãà";
$_lang['Please select a file (*.csv)'] = "Ìîëÿ èçáåðåòå ôàéë (*.csv)";
$_lang['Howto: Open your outlook express address book and select file/export/other book<br>Then give the file a name, select all fields in the next dialog and finish'] = "Êàê äà: Îòâîðåòå âàøàòà Outlook Express àäðåñíà êíèãà è èçáåðåòå 'file'/'export'/'other book'<br>
Ñëåä òîâà äàéòå íà ôàéëà èìå, èçáåðåòå âñè÷êè ïîëåòà â ñëåäâàùèÿò äèàëîã è 'ãîòîâî'";
$_lang['Open outlook at file/export/export in file,<br>choose comma separated values (Win), then select contacts in the next form,<br>give the export file a name and finish.'] = "Îòâîðåòå Îutlook â 'file/export/export in file',<br>
èçáåðåòå 'comma separated values (Win)', ñëåä òîâà èçáåðåòå êîíòàêòè 'contacts' â ñëåäâàùàòà ôîðìà,<br>
äàèòå íà åêïîðòèðàíèÿò ôàéë èìå è ãîòîâî.";
$_lang['Please choose an export file (*.csv)'] = "Ìîëÿ èçáåðåòå åêñïîðòåí ôàéë (*.csv)";
$_lang['Please export your address book into a comma separated value file (.csv), and either<br>1) apply an import pattern OR<br>2) modify the columns of the table with a spread sheet to this format<br>(Delete colums in you file that are not listed here and create empty colums for fields that do not exist in your file):'] = "Ìîëÿ åêñïîðòíåòå âàøàòà àäðåñíà êíèãà âúâ ôàéë ñúñ ñòîéíîñòè ðàçäåëåíè ñúñ çàïåòàÿ (.csv),<br>
and modify the columns of the table with a spread sheet to this format:<br> title, first name, family name, company, email, email 2, phone 1, 
phone 2, fax, mobile phone, street, zip code, city, country, state, category, remark, web address.<br>
Delete colums in you file that are not listed here and create empty colums for fields that do not exist in your file";
$_lang['Please insert at least the family name'] = "Ìîëÿ âïèøåòå ïîíå ôàìèëíî èìå";
$_lang['Record import failed because of wrong field count'] = "Èìïîðòâàíåòî íà çàïèñà ïðîïàäíà çàðàäè ãðåøåí áðîé ïîëåòà";
$_lang['Import to approve'] = "Âíàñÿíå çà  Import to approve";
$_lang['Import list'] = "Èìïîðò íà ñïèñúêà çà îäîáðÿâàíå";
$_lang['The list has been imported.'] = "Ñïèñúêà áåøå èìïîðòèðàí.";
$_lang['The list has been rejected.'] = "Ñïèñúêà áå îòõâúðëåí.";
$_lang['Profiles'] = "Ïðîôèëè";
$_lang['Parent object'] = "Ðîäèòåëñêè îáåêò";
$_lang['Check for duplicates during import'] = "Ïðîâåðåòå çà äóáëèðàùè ñå ïî âðåìå íà èìïîðòà";
$_lang['Fields to match'] = "Ïîëåòà äà ñúâïàäíàò";
$_lang['Action for duplicates'] = "Äåéñòâèå çà äóáëèðàùè ñå";
$_lang['Discard duplicates'] = "Îòêàçâàíå îò äóáëåòà";
$_lang['Dispose as child'] = "Ðàçïîëîæè êàòî child";
$_lang['Store as profile'] = "Çàïîìíè êàòî ïðîôèë";    
$_lang['Apply import pattern'] = "Ïðèëîæè âêàðàíà ìàñêà";
$_lang['Import pattern'] = "Âêàðàé ìàñêà";
$_lang['For modification or creation<br>upload an example csv file'] = "Çà ìîäèôèêàöèÿ èëè ñúçäàâàíå <br>êà÷è ïðèìåðåí csv ôàéë"; 
$_lang['Skip field'] = "Ïðîïóñíè ïîëå";
$_lang['Field separator'] = "Ðàçäåëèòåë íà ïîëå";
$_lang['Contact selector'] = "Contact selector";
$_lang['Use doublet'] = "Use doublet";
$_lang['Doublets'] = "Doublets";

// filemanager.php
$_lang['Please select a file'] = "Ìîëÿ èçáåðåòå ôàéë";
$_lang['A file with this name already exists!'] = "Ôàéë ñúñ ñúùîòî èìå âå÷å ñúùåñòâóâà!";
$_lang['Name'] = "Èìå";
$_lang['Comment'] = "Êîìåíòàð";
$_lang['Date'] = "Äàòà";
$_lang['Upload'] = "Êà÷âàíå";
$_lang['Filename and path'] = "Èìå íà ôàéë è ïúòåêà";
$_lang['Delete file'] = "Èçòðèâàíå íà ôàéë";
$_lang['Overwrite'] = "Ïðåçàïèñâàíå";
$_lang['Access'] = "Äîñòúï";
$_lang['Me'] = "àç";
$_lang['Group'] = "âñè÷êè";
$_lang['Some'] = "íÿêîëêî";
$_lang['As parent object'] = "ñúùî êàòî äèðåêòîðèÿ";
$_lang['All groups'] = "Âñè÷êè ãðóïè";
$_lang['You are not allowed to overwrite this file since somebody else uploaded it'] = "Íå âè å ðàçðåøåíî äà ïðåçàïèøåòå òîçè ôàéë äîêàòî íÿêîé äðóã íå ãî êà÷è";
$_lang['personal'] = "ëè÷íî";
$_lang['Link'] = "Âðèçêà";
$_lang['name and network path'] = "èìå è ìðåæîâà ïúòåêà";
$_lang['with new values'] = "ñ íîâè ñòîéíîñòè";
$_lang['All files in this directory will be removed! Continue?'] = "Âñè÷êè ôàéëîâå â òàçè äèðåêòîðèÿ ùå áúäàò ïðåìàõíàòè! Ïðîäúëæàâàíå?";
$_lang['This name already exists'] = "Òîâà èìå íà ôàéë âå÷å ñúùåñòâóâà";
$_lang['Max. file size'] = "Ìàêñèìàëåí ðàçìåð íà ôàéë";
$_lang['links to'] = "âðúçêè êúì";
$_lang['objects'] = "îáåêòà";
$_lang['Action in same directory not possible'] = "Äåéñòâèå â ñúùàòà äèðåêòîðèÿ å íåâúçìîæíî";
$_lang['Upload = replace file'] = "Êà÷âàíå = çàìåíÿíå íà ôàéë";
$_lang['Insert password for crypted file'] = "Ïîñòàâåòå ïàðîëà çà êðèïòèðàíèÿò ôàéë";
$_lang['Crypt upload file with password'] = "Êðèïòèðàíå ñ ïàðîëà";
$_lang['Repeat'] = "Ïîâòàðÿíå";
$_lang['Passwords dont match!'] = "Ïàðîëèòå íå ñúâïàäàò!";
$_lang['Download of the password protected file '] = "Ñâàëÿíå íà ôàéë çàùèòåí ñ ïàðîëà ";
$_lang['notify all users with access'] = "îñâåäîìÿâàíå íà âñè÷êè ïîòðåáèòåëè ñ äîñòúï";
$_lang['Write access'] = "äîñòúï çà çàïèñ";
$_lang['Version'] = "Âåðñèÿ";
$_lang['Version management'] = "Óïðàâëåíèå íà âåðñèèòå";
$_lang['lock'] = "çàêëþ÷âàíå";
$_lang['unlock'] = "îòêëþ÷âàíå";
$_lang['locked by'] = "çàêëþ÷åí îò";
$_lang['Alternative Download'] = "Àëòåðíàòèâíî ñâàëÿíå"; 
$_lang['Download'] = "Download";
$_lang['Select type'] = "Select type";
$_lang['Create directory'] = "Create directory";
$_lang['filesize (Byte)'] = "Filesize (Byte)";

// filter
$_lang['contains'] = 'ñúäúðæà';
$_lang['exact'] = 'òî÷íî';
$_lang['starts with'] = 'çàïî÷âà ñ';
$_lang['ends with'] = 'çàâúðøâà ñ';
$_lang['>'] = '>';
$_lang['>='] = '>=';
$_lang['<'] = '<';
$_lang['<='] = '<=';
$_lang['does not contain'] = 'íå ñúäúðæà'; 
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
$_lang['Module Designer'] = "Ìîäóë Äèçàéíåð";
$_lang['Module element'] = "Ìîäóë åëåìåíò"; 
$_lang['Module'] = "Ìîäóë";
$_lang['Active'] = "Àêòèâåí";
$_lang['Inactive'] = "Íåàêòèâåí";
$_lang['Activate'] = "Àêòèâèðàé";
$_lang['Deactivate'] = "Äåàêòèâèðàé"; 
$_lang['Create new element'] = "Ñúçäàé íîâ åëåìåíò";
$_lang['Modify element'] = "Ìîäèôèöèðàé åëåìåíòñ";
$_lang['Field name in database'] = "Èìå íà ïîëå(òî) â áàçà äàííè";
$_lang['Use only normal characters and numbers, no special characters,spaces etc.'] = "Èçïîëçâàéòå ñàìî íîðìàëíè çíàöè è íîìåðà, áåç ñïåöèàëíè çíàöè, ïðàçíè ìåñòà èëè äð.";
$_lang['Field name in form'] = "Èìå íà ïîëå âúâ ôîðìà";
$_lang['(could be modified later)'] = "(ìîæå äà áúäå ïðîìåíÿí ïî-êúñíî)"; 
$_lang['Single Text line'] = "Åäèí ðåä òåêñò";
$_lang['Textarea'] = "Òåêñòîâîìÿñòî";
$_lang['Display'] = "Ïîêàæè";
$_lang['First insert'] = "Ïúðâî âêàðâàíå";
$_lang['Predefined selection'] = "Ïðåäâàðèòåëíî îïðåäåëåíà ñåëåêöèÿ";
$_lang['Select by db query'] = "Èçáèðàíå ÷ðåç çàïèòâàíå êúì áàçà äàííè";
$_lang['File'] = "Ôàéë";

$_lang['Email Address'] = "Email Àäðåñ";
$_lang['url'] = "url";
$_lang['Checkbox'] = "Checkbox";
$_lang['Multiple select'] = "Èçáîð íà ìíîãî";
$_lang['Display value from db query'] = "Display value from db query";
$_lang['Time'] = "Time";
$_lang['Tooltip'] = "Ïîäñêàçêà"; 
$_lang['Appears as a tipp while moving the mouse over the field: Additional comments to the field or explanation if a regular expression is applied'] = "Ïîêàçâà ñå êàòî ïîäñêàçêà, êîãàòî ìúðäàòå ìèøêàòà íàä ïîëåòî: Äîïúëíèòåëíè êîìåíòàðè îòíîíî ïîëåòî ñå ïðèëàãàò";
$_lang['Position'] = "Ïîçèöèÿ";
$_lang['is current position, other free positions are:'] = "å íàñòîÿùàòà ïîçèöèÿ, äðóãè ñâîáîäíè ïîçèöèè ñà:"; 
$_lang['Regular Expression:'] = "Ïðàâèëåí èçðàç:";
$_lang['Please enter a regular expression to check the input on this field'] = "Ìîëÿ âúâåäåòå ïðàâèëåí èçðàç, çà äà ïðîâåðèòå âõîäà íà òîâà ïîëå";
$_lang['Default value'] = "Ñòîéíîñò ïî Ïîäðàçáèðàíå";
$_lang['Predefined value for creation of a record. Could be used in combination with a hidden field as well'] = "Ïðåäâàðèòåëíî îïðåäåëåíà ñòîéíîñò çà ñúçäàâàíå íà çàïèñ. Ìîæå äà áúäå èçïîëçâàíà â êîìáèíàöèÿ ñ ñêðèòî ïîëå";
$_lang['Content for select Box'] = "Ñúäúðæàíèå çà èçáîðíàòà Êóòèÿ";
$_lang['Used for fixed amount of values (separate with the pipe: | ) or for the sql statement, see element type'] = "Èçïîëçâàíà çà ôèêñèðàí îáåì îò ñòîéíîñòè (ðàçäåëåíè ñ: | ) èëè çà sql statement -òà, âèæ òèïà íà åëåìåíòà";
$_lang['Position in list view'] = "Ïîçèöèÿ â ñïèñú÷åí èçãëåä";                                                                                     
$_lang['Only insert a number > 0 if you want that this field appears in the list of this module'] = "Ñàìî âúâåäåòå ÷èñëî > 0 àêî èñêàòå òîâà ïîëå äà ñå ïîêàçâà â ñïèñúêà íà òîçè ìîäóë";
$_lang['Alternative list view'] = "Àëòåðíàòèâåí ñïèñú÷åí èçãëåä";
$_lang['Value appears in the alt tag of the blue button (mouse over) in the list view'] = "Ñòîéíîñòòà ñå ïîÿâÿâà â alt åòèêåòà íà ñèíèÿ áóòîí â ñïèñú÷åíèÿò èçãëåä";
$_lang['Filter element'] = "Filter element";                                                                                   
$_lang['Appears in the filter select box in the list view'] = "Appears in the filter select box in the list view";
$_lang['Element Type'] = "Òèï íà åëåìåíòà";
$_lang['Select the type of this form element'] = "Èçáåðåòå òèïà íà åëåìåíòà íà òàçè ôîðìà";
$_lang['Check the content of the previous field!'] = "Ïðîâåðåòå ñúäúðæàíèåòî íà ïðåäèøíîòî ïîëå!";
$_lang['Span element over'] = "Span element over";
$_lang['columns'] = "êîëîíè";
$_lang['rows'] = "ðåäèöè";
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
$_lang['Welcome to the setup of PHProject!<br>'] = "Äîáðå äîùëè â èíñòàëàöèÿòà íà PHProject!<br>";
$_lang['Please remark:<ul><li>A blank database must be available<li>Please ensure that the webserver is able to write the file config.inc.php'] = "Ìîëÿ îòáåëåæåòå:<ul>
<li>Òðàáâà äà èìà ïðàçíà áàçà äàííè
<li>Ìîëÿ, óâåðåòå ñè, ÷å ñúðâúðúò ìîæå äà ïèøå âúâ ôàéëà 'config.inc.php'";
$_lang['<li>If you encounter any errors during the installation, please look into the <a href=help/faq_install.html target=_blank>install faq</a>or visit the <a href=http://www.PHProjekt.com/forum.html target=_blank>Installation forum</a></i>'] = "<li>Àêî çàáåëåæèòå íÿêàêâà ãðåøêà ïî âðåìå íà èíñòàëàöèÿòà, ìîëÿ ïîãëåäíåòå âúâ <a href='help/faq_install.html' target=_blank>÷åñòî 
çàäàâàíè âúïðîñè îòíîñíî èíñòàëàöèÿòà</a> èëè ïîñåòåòå <a href='http://www.PHProjekt.com/forum.html' target=_blank>Ôîðóìà îòíîñíî èíñòàëàöèÿòà</a></i>";
$_lang['Please fill in the fields below'] = "Ìîëÿ ïîïúëíåòå ïîëåòàòà äîëó";
$_lang['(In few cases the script wont respond.<br>Cancel the script, close the browser and try it again).<br>'] = "(Â íÿêîëêî ñëó÷àÿ ñêðèïòúò ìîæå äà íå îòãîâàðà, â òàêúâ ñëó÷àé.<br>
îòêàæåòå ñêðèïòà, çàòâîðåòå áðàóçúðà è îïèòàéòå ïàê).<br>";
$_lang['Type of database'] = "Òèï íà áàçàòà äàííè";
$_lang['Hostname'] = "Hostname";
$_lang['Username'] = "Ïîòðåáèòåëñêî èìå";

$_lang['Name of the existing database'] = "Èìå íà ñúøåñòâóâàùàòà áàçà äàííè";
$_lang['config.inc.php not found! Do you really want to update? Please read INSTALL ...'] = "config.inc.php íå áåøå îòêðèò! Íàèñòèíà ëè èñêàòå äà îáíîâÿâàòå? Ìîëÿ ïðî÷åòåòå ÈÍÑÒÀËÀÖÈß ...";
$_lang['config.inc.php found! Maybe you prefer to update PHProject? Please read INSTALL ...'] = "config.inc.php áåøå îòêðèò! Ìîæå áè èñêàòå äà îáíîâèòå? Ìîëÿ ïðî÷åòåòå ÈÍÑÒÀËÀÖÈß ...";
$_lang['Please choose Installation,Update or Configure!'] = "Please choose 'Installation','Update' or 'Configure'!";
$_lang['Sorry, I cannot connect to the database! <br>Please close all browser windows and restart the installation.'] = "Sorry, I cannot connect to the database! <br>Please fix it and restart the installation.";
$_lang['Sorry, it does not work! <br> Please set DBDATE to Y4MD- or let phprojekt change this environment-variable (php.ini)!'] = "Sorry, it does not work! <br> Please set DBDATE to 'Y4MD-' or let phprojekt change this environment-variable (php.ini)!";
$_lang['Seems that You have a valid database connection!'] = "Seems that You have a valid database connection!";
$_lang['Please select the modules you are going to use.<br> (You can disable them later in the config.inc.php)<br>'] = "Please select the modules you are going to use.<br> (You can disable them later in the config.inc.php)<br>";
$_lang['Install component: insert a 1, otherwise keep the field empty'] = "Install component: insert a '1', otherwise keep the field empty";
$_lang['Group views'] = "Group views";
$_lang['Todo lists'] = "'Äà Íàïðàâÿ' ñïèñúöè";

$_lang['Voting system'] = "Ñèñòåìà çà ãëàñóâàíå";


$_lang['Contact manager'] = "Ìåíèäæúð íà êîíòàêòèòå";
$_lang['Name of userdefined field'] = "Name of userdefined field";
$_lang['Userdefined'] = "Ïîòðåáèòåëñêè îïðåäåëåí";
$_lang['Profiles for contacts'] = "Profiles for contacts";
$_lang['Mail'] = "Ïîùà";
$_lang['send mail'] = " send mail";
$_lang[' only,<br> &nbsp; &nbsp; full mail client'] = " only,<br> &nbsp; &nbsp; full mail client";



$_lang['1 to show appointment list in separate window,<br>&nbsp; &nbsp; 2 for an additional alert.'] = "'1' to show appointment list in separate window,<br>
&nbsp; &nbsp; '2' for an additional alert.";
$_lang['Alarm'] = "Àëàðìà";
$_lang['max. minutes before the event'] = "ìàêñ. ìèíóòè ïðåäè ñúáèòèå";
$_lang['SMS/Mail reminder'] = "SMS/Email íàïîìíèòåë";
$_lang['Reminds via SMS/Email'] = "ÍÀïîìíÿíå ÷ðåç SMS/Email";
$_lang['1= Create projects,<br>&nbsp; &nbsp; 2= assign worktime to projects only with timecard entry<br>&nbsp; &nbsp; 3= assign worktime to projects without timecard entry<br>&nbsp; &nbsp; (Selection 2 or 3 only with module timecard!)'] = "'1'= Create projects,<br>
&nbsp; &nbsp; '2'= assign worktime to projects only with timecard entry<br>
&nbsp; &nbsp; '3'= assign worktime to projects without timecard entry<br>&nbsp; &nbsp; (Selection '2' or '3' only with module timecard!)";

$_lang['Name of the directory where the files will be stored<br>( no file management: empty field)'] = "Name of the directory where the files will be stored<br>( no file management: empty field)";
$_lang['absolute path to this directory (no files = empty field)'] = "absolute path to this directory (no files = empty field)";
$_lang['Time card'] = "Time card";
$_lang['1 time card system,<br>&nbsp; &nbsp; 2 manual insert afterwards sends copy to the chief'] = "'1' time card system,<br>
&nbsp; &nbsp; '2' manual insert afterwards sends copy to the chief";
$_lang['Notes'] = "Áåëåæêè";
$_lang['Password change'] = "Password change";
$_lang['New passwords by the user - 0: none - 1: only random passwords - 2: choose own'] = "New passwords by the user - 0: none - 1: only random passwords - 2: choose own";
$_lang['Encrypt passwords'] = "Encrypt passwords";
$_lang['Login via '] = "Login via ";
$_lang['Extra page for login via SSL'] = "Extra page for login via SSL";
$_lang['Groups'] = "Groups";
$_lang['User and module functions are assigned to groups<br>&nbsp; &nbsp; (recommended for user numbers > 40)'] = "User and module functions are assigned to groups<br>
&nbsp; &nbsp; (recommended for user numbers > 40)";
$_lang['User and module functions are assigned to groups'] = "User and module functions are assigned to groups";
$_lang['Help desk'] = "Help desk";
$_lang['Help Desk Manager / Trouble Ticket System'] = "Help Desk Manager / Trouble Ticket System";
$_lang['RT Option: Customer can set a due date'] = "RT Option: Customer can set a due date";
$_lang['RT Option: Customer Authentification'] = "RT Option: Customer Authentification";
$_lang['0: open to all, email-address is sufficient<br>1: registered contact enter his family name<br>2: his email'] = "0: open to all, email-address is sufficient, 1: customer must be in contact list and enter his family name";
$_lang['RT Option: Assigning request'] = "RT Option: Assigning request";
$_lang['0: by everybody, 1: only by persons with status chief'] = "0: by everybody, 1: only by persons with status 'chief'";
$_lang['Email Address of the support'] = "Email Address of the support";
$_lang['Scramble filenames'] = "Scramble filenames";
$_lang['creates scrambled filenames on the server<br>assigns previous name at download'] = "creates scrambled filenames on the server<br>
assigns previous name at download";

$_lang['0: last name, 1: short name, 2: login name'] = "0: last name, 1: short name, 2: login name"; 
$_lang['Prefix for table names in db'] = "Prefix for table names in db";
$_lang['Alert: Cannot create file config.inc.php!<br>Installation directory needs rwx access for your server and rx access to all others.'] = "Alert: Cannot create file 'config.inc.php'!<br>
Installation directory needs rwx access for your server and rx access to all others.";
$_lang['Location of the database'] = "Location of the database";
$_lang['Type of database system'] = "Type of database system";
$_lang['Username for the access'] = "Username for the access";
$_lang['Password for the access'] = "Password for the access";
$_lang['Name of the database'] = "Name of the database";
$_lang['Prefix for database table names'] = "Prefix for database table names";
$_lang['First background color'] = "First background color";
$_lang['Second background color'] = "Second background color";
$_lang['Third background color'] = "Third background color";
$_lang['Color to mark rows'] = "Color to mark rows";
$_lang['Color to highlight rows'] = "Color to highlight rows";
$_lang['Event color in the tables'] = "Event color in the tables";
$_lang['company icon yes = insert name of image'] = "company icon yes = insert name of image";
$_lang['URL to the homepage of the company'] = "URL to the homepage of the company";
$_lang['no = leave empty'] = "no = leave empty";
$_lang['First hour of the day:'] = "Ïúðâè ÷àñ îò äåíÿ";
$_lang['Last hour of the day:'] = "Last hour of the day:";
$_lang['An error ocurred while creating table: '] = "An error ocurred while creating table: ";
$_lang['Table dateien (for file-handling) created'] = "Table 'dateien' (for file-handling) created";
$_lang['File management no = leave empty'] = "File management no = leave empty";
$_lang['yes = insert full path'] = "yes = insert full path";
$_lang['and the relative path to the PHProjekt directory'] = "and the relative path to the PHProjekt directory";
$_lang['Table profile (for user-profiles) created'] = "Table 'profile' (for user-profiles) created";
$_lang['User Profiles yes = 1, no = 0'] = "User Profiles yes = 1, no = 0";
$_lang['Table todo (for todo-lists) created'] = "Table 'todo' (for todo-lists) created";
$_lang['Todo-Lists yes = 1, no = 0'] = "Todo-Lists yes = 1, no = 0";
$_lang['Table forum (for discssions etc.) created'] = "Table 'forum' (for discssions etc.) created";
$_lang['Forum yes = 1, no = 0'] = "Forum yes = 1, no = 0";
$_lang['Table votum (for polls) created'] = "Table 'votum' (for votes) created";
$_lang['Voting system yes = 1, no = 0'] = "Voting system yes = 1, no = 0";
$_lang['Table lesezeichen (for bookmarks) created'] = "Table 'lesezeichen' (for bookmarks) created";
$_lang['Bookmarks yes = 1, no = 0'] = "Bookmarks yes = 1, no = 0";
$_lang['Table ressourcen (for management of additional ressources) created'] = "Table 'ressourcen' (for management of additional ressources) created";
$_lang['Resources yes = 1, no = 0'] = "Resources yes = 1, no = 0";
$_lang['Table projekte (for project management) created'] = "Table 'projekte' (for project management) created";
$_lang['Table contacts (for external contacts) created'] = "Table contacts (for external contacts) created";
$_lang['Table notes (for notes) created'] = "Table notes (for notes) created";
$_lang['Table timecard (for time sheet system) created'] = "Table timecard (for time sheet system) created";
$_lang['Table groups (for group management) created'] = "Table groups (for group management) created";
$_lang['Table timeproj (assigning work time to projects) created'] = "Table timeproj (assigning work time to projects) created";
$_lang['Table rts and rts_cat (for the help desk) created'] = "Table rts and rts_cat (for the help desk) created";
$_lang['Table mail_account, mail_attach, mail_client und mail_rules (for the mail reader) created'] = "Table mail_account, mail_attach, mail_client und mail_rules (for the mail reader) created";
$_lang['Table logs (for user login/-out tracking) created'] = "Table logs (for user login/-out tracking) created";
$_lang['Tables contacts_profiles und contacts_prof_rel created'] = "Tables contacts_profiles und contacts_prof_rel created";
$_lang['Project management yes = 1, no = 0'] = "Project management yes = 1, no = 0";
$_lang['additionally assign resources to events'] = "additionally assign resources to events";
$_lang['Address book  = 1, nein = 0'] = "Address book  = 1, nein = 0";
$_lang['Mail no = 0, only send = 1, send and receive = 2'] = "Mail no = 0, only send = 1, send and receive = 2";
$_lang['Chat yes = 1, no = 0'] = "Chat yes = 1, no = 0";  
$_lang['Name format in chat list'] = "Name format in chat list";
$_lang['0: last name, 1: first name, 2: first name, last name,<br> &nbsp; &nbsp; 3: last name, first name'] = "0: last name, 1: first name, 2: first name, last name,<br> &nbsp; &nbsp; 3: last name, first name";
$_lang['Timestamp for chat messages'] = "Timestamp for chat messages";
$_lang['users (for authentification and address management)'] = "'users' (for authentification and address management)";
$_lang['Table termine (for events) created'] = "'Table termine' (for events) created";
$_lang['The following users have been inserted successfully in the table user:<br>root - (superuser with all administrative privileges)<br>test - (chief user with restricted access)'] = "The following users have been inserted successfully in the table 'user':<br>
'root' - (superuser with all administrative privileges)<br>
'test' - (chief user with restricted access)";
$_lang['The group default has been created'] = "The group 'default' has been created";
$_lang['Please do not change anything below this line!'] = "Please do not change anything below this line!";
$_lang['Database error'] = "Database error";
$_lang['Finished'] = "Finished";
$_lang['There were errors, please have a look at the messages above'] = "There were errors, please have a look at the messages above";
$_lang['All required tables are installed and <br>the configuration file config.inc.php is rewritten<br>It would be a good idea to makea backup of this file.<br>'] = "All required tables are installed and <br>
the configuration file 'config.inc.php' is rewritten<br>
It would be a good idea to make
a backup of this file.<br>";
$_lang['The administrator root has the password root. Please change his password here:'] = "The administrator 'root' has the password 'root'. Please change his password here:";
$_lang['The user test is now member of the group default.<br>Now you can create new groups and add new users to the group'] = "The user 'test' is now member of the group 'default'.<br>
Now you can create new groups and add new users to the group";
$_lang['To use PHProject with your Browser go to <b>index.php</b><br>Please test your configuration, especially the modules Mail and Files.'] = "To use PHProject with your Browser go to <b>index.php</b><br>
Please test your configuration, especially the modules 'Mail' and 'Files'.";

$_lang['Alarm x minutes before the event'] = "Alarm x minutes before the event";
$_lang['Additional Alarmbox'] = "Additional Alarmbox";
$_lang['Mail to the chief'] = "Mail to the chief";
$_lang['Out/Back counts as: 1: Pause - 0: Workingtime'] = "Out/Back counts as: 1: Pause - 0: Workingtime";
$_lang['Passwords will now be encrypted ...'] = "Passwords will now be encrypted ...";
$_lang['Filenames will now be crypted ...'] = "Filenames will now be crypted ...";
$_lang['Do you want to backup your database right now? (And zip it together with the config.inc.php ...)<br>Of course I will wait!'] = "Do you want to backup your database right now? (And zip it together with the config.inc.php ...)<br>
Of course I will wait!";
$_lang['Next'] = "Next";
$_lang['Notification on new event in others calendar'] = "Notification on new event in others calendar";
$_lang['Path to sendfax'] = "Path to sendfax";
$_lang['no fax option: leave blank'] = "no fax option: leave blank";
$_lang['Please read the FAQ about the installation with postgres'] = "Please read the FAQ about the installation with postgres";
$_lang['Length of short names<br> (Number of letters: 3-6)'] = "Length of short names<br> (Number of letters: 3-6)";
$_lang['If you want to install PHProjekt manually, you find<a href=http://www.phprojekt.com/files/sql_dump.tar.gz target=_blank>here</a> a mysql dump and a default config.inc.php'] = "If you want to install PHProjekt manually, you find
<a href='http://www.phprojekt.com/files/sql_dump.tar.gz' target=_blank>here</a> a mysql dump and a default config.inc.php";
$_lang['The server needs the privilege to write to the directories'] = "The server needs the privilege to 'write' to the directories";
$_lang['Header groupviews'] = "Header groupviews";
$_lang['name, F.'] = "name, F.";
$_lang['shortname'] = "shortname";
$_lang['loginname'] = "loginname";
$_lang['Please create the file directory'] = "Please create the file directory";
$_lang['default mode for forum tree: 1 - open, 0 - closed'] = "default mode for forum tree: 1 - open, 0 - closed";
$_lang['Currency symbol'] = "Currency symbol";
$_lang['current'] = "current";
$_lang['Default size of form elements'] = "Default size of form elements";
$_lang['use LDAP'] = "use LDAP";
$_lang['Allow parallel events'] = "Allow parallel events";
$_lang['Timezone difference [h] Server - user'] = "Ðàçëèêà âúâ âðåìåâàòà çîíà ñúðâúð-ïîòðåáèòåë [÷.]";
$_lang['Timezone'] = "Timezone";
$_lang['max. hits displayed in search module'] = "max. hits displayed in search module";
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
$_lang['Resource List'] = "Ñïèñúê íà ðåñóðñèòå";
$_lang['Event List'] = "Ñïèñúê íà ñúáèòèÿòà";
$_lang['Calendar Views'] = "Calendar Views";

$_lang['Personnel'] = "Ïåðñîíàë";

$_lang['Create new event'] = "Ñúçäàâàíå íà ñúáèòèå";
$_lang['Day'] = "Äåí";

$_lang['Until'] = "Äî";

$_lang['Note'] = "Áåëåæêà";
$_lang['Project'] = "Ïðîåêò";
$_lang['Res'] = "Ðåñ";
$_lang['Once'] = "Âåäíúæ";
$_lang['Daily'] = "Äíåâíî";
$_lang['Weekly'] = "Ñåäìè÷íî";
$_lang['Monthly'] = "Ìåñå÷íî";
$_lang['Yearly'] = "Ãîäèøíî";

$_lang['Create'] = "Ñúçäàâàíå";

$_lang['Begin'] = "Íà÷àëî";
$_lang['Out of office'] = "Èçâúí îôèñà";
$_lang['Back in office'] = "Îáðàòíî â îôèñà";
$_lang['End'] = "Êðàé";
$_lang['@work'] = "Íà ðàáîòà";
$_lang['We'] = "Íèå";
$_lang['group events'] = "ãðóïîâè ñúáèòèÿ";
$_lang['or profile'] = "èëè ïðîôèë";
$_lang['All Day Event'] = "ñúáèòèÿ çà öÿë äåí";
$_lang['time-axis:'] = "Âðåìåâà ëèíèÿ:";
$_lang['vertical'] = "âåðòèêàëíà";
$_lang['horizontal'] = "õîðèçîíòàëíà";
$_lang['Horz. Narrow'] = "hor. narrow";
$_lang['-interval:'] = "-èíòåðâàë:";
$_lang['Self'] = "Ñàìîñòîÿòåëåí";

$_lang['...write'] = "...çàïèñ";

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
$_lang['Please call login.php!'] = "Ìîëÿ âèêíåòå login.php!";

// m1.php
$_lang['There are other events!<br>the critical appointment is: '] = "Èìà äðóãè ñúáèòèÿ!<br>êðèòè÷íàòà ñðåùà å: ";
$_lang['Sorry, this resource is already occupied: '] = "Òîçè ðåñóðñ å âå÷å çàåò: ";
$_lang[' This event does not exist.<br> <br> Please check the date and time. '] = " Òîâà ñúáèòèå íå ñúøåñòâóâà.<br> <br> Ìîëÿ ïðîâåðåòå äàòàòà è âðåìåòî. ";
$_lang['Please check your date and time format! '] = "Ìîëÿ ïðîâåðåòå ôîðìàòà íà âàøèòå äàòà è âðåìå! ";
$_lang['Please check the date!'] = "Ìîëÿ ïðîâåòåòå äàòàòà!";
$_lang['Please check the start time! '] = "Ìîëÿ ïðîâåðåòå âðåìåòî çà íà÷àëî! ";
$_lang['Please check the end time! '] = "Ìîëÿ ïðîâåðåòå âðåìåòî çà êðàé! ";
$_lang['Please give a text or note!'] = "Ìîëÿ äàéòå òåêñò èëè áåëåæêà!";
$_lang['Please check start and end time! '] = "Ìîëÿ ïðîâåðåòå âðåìåòî çà íà÷àëî è êðàé! ";
$_lang['Please check the format of the end date! '] = "Ìîëÿ ïðîâåðåòå ôîðìàòà íà êðàéíàòà äàòà! ";
$_lang['Please check the end date! '] = "Ìîëÿ ïðîâåðåòå êðàéíàòà äàòà! ";





$_lang['Resource'] = "Ðåñóðñ";
$_lang['User'] = "Ïîòðåáèòåë";

$_lang['delete event'] = "èçòðèâàíå íà ñúáèòèå";
$_lang['Address book'] = "Àäðåñíà êíèãà";


$_lang['Short Form'] = "Êúñà ôîðìà";

$_lang['Phone'] = "Òåëåôîí";
$_lang['Fax'] = "Ôàêñ";



$_lang['Bookmark'] = "Îòìåòêà";
$_lang['Description'] = "Îïèñàíèå";

$_lang['Entire List'] = "Öÿë ñïèñúê";

$_lang['New event'] = "Íîâî ñúáèòèå";
$_lang['Created by'] = "Ñúçäàäåíî îò";
$_lang['Red button -> delete a day event'] = "Red button -> delete a day event";
$_lang['multiple events'] = "ìíîãîêðàòíè ñúáèòèÿ";
$_lang['Year view'] = "Ãîäèøåí èçãëåä";
$_lang['calendar week'] = "êàëåíäàðíà ñåäìèöà";

//m2.php
$_lang['Create &amp; Delete Events'] = "Ñúçäàâàíå &amp; Èçòðèâàíå íà ñúáèòèÿ";
$_lang['normal'] = "íîðìàëåí";
$_lang['private'] = "÷àñòåí";
$_lang['public'] = "ïóáëè÷åí";
$_lang['Visibility'] = "Âèäèìîñò";

//mail module
$_lang['Please select at least one (valid) address.'] = "Ìîëÿ èçáåðåòå ïîíå åäèí (âàëèäåí) àäðåñ.";
$_lang['Your mail has been sent successfully'] = "Âàøàòà ïîùà áåøå èçïðàòåíà óñïåøíî";
$_lang['Attachment'] = "Ïðèêà÷åíè ôàéëîâå";
$_lang['Send single mails'] = "Èçïðàùàíå íà åäèíè÷íè ñúîáùåíèÿ";
$_lang['Does not exist'] = "Íå ñúùåñòâóâà";
$_lang['Additional number'] = "Äîïúëíèòåëåí íîìåð";
$_lang['has been canceled'] = "áåøå îòêàçàí";

$_lang['marked objects'] = "marked objects";
$_lang['Additional address'] = "Äîïúëíèòåëíè àäðåñè";
$_lang['in mails'] = "â ñúîáùåíèå";
$_lang['Mail account'] = "Mail Konto";
$_lang['Body'] = "Òÿëî";
$_lang['Sender'] = "Èçïðàùà÷";

$_lang['Receiver'] = "Ïîëó÷àòåë";
$_lang['Reply'] = "Îòãîâîðè";
$_lang['Forward'] = "Âúðíè";
$_lang['Access error for mailbox'] = "Ãðåøêà ïðè äîñòúïà â ïîùåíñêàòà êóòèÿ";
$_lang['Receive'] = "Ïîëó÷àâàíå";
$_lang['Write'] = "Çàïèñ";
$_lang['Accounts'] = "Àáîíàìåíòè";
$_lang['Rules'] = "Ïðàâèëà";
$_lang['host name'] = "host èìå";
$_lang['Type'] = "Òèï";
$_lang['misses'] = "misses";
$_lang['has been created'] = "áåøå ñúçäàäåí";
$_lang['has been changed'] = "áåøå ïðîìåíåí";
$_lang['is in field'] = "å â ïîëåòî";
$_lang['and leave on server'] = "è îñòàâà íà ñúðâúðà";
$_lang['name of the rule'] = "èìå íà ïðàâèëîòî";
$_lang['part of the word'] = "÷àñò îò äóìàòà";
$_lang['in'] = "â";
$_lang['sent mails'] = "èçïðàòåíè ñúîáùåíèÿ";
$_lang['Send date'] = "Äàòà íà èçïðàùàíå";
$_lang['Received'] = "ïîëó÷åíî";
$_lang['to'] = "äî";
$_lang['imcoming Mails'] = "âõîäÿùà ïîùà";
$_lang['sent Mails'] = "èçïðàòåíà ïîùà";
$_lang['Contact Profile'] = "Contact Profile";
$_lang['unread'] = "íå÷åòåíè";
$_lang['view mail list'] = "ðàçãëåæäàíå íà ñïèñúêà  ïîùà";
$_lang['insert db field (only for contacts)'] = "âúâåäåòå ïîëå íà áàçàòà äàííè (ñàìî çà êîíòàêòè)";
$_lang['Signature'] = "Ïîäïèñ";

$_lang['SMS'] = "SMS";
$_lang['Single account query'] = "Çàïèòâàíå çà åäèíè÷åí àáîíàìåíò";
$_lang['Notice of receipt'] = "Ñúîáùàâàíå ïðè ïîëó÷àâàíå"; 
$_lang['Assign to project'] = "Include to the receive-list";
$_lang['Assign to contact'] = "Send";
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
$_lang['Mail note to'] = "Èçïðàùàíå íà áåëåæêàòà íà";
$_lang['added'] = "äîáàâåí";
$_lang['changed'] = "ïðîìåíåí";

// o.php
$_lang['Calendar'] = "Êàëåíäàð";
$_lang['Contacts'] = "Êîíòàêòè";


$_lang['Files'] = "Ôàéëîâå";



$_lang['Options'] = "Îïöèè";
$_lang['Timecard'] = "Âðåìåâà êàðòà";

$_lang['Helpdesk'] = "Ïîìîù";

$_lang['Info'] = "Èíôî";
$_lang['Todo'] = " 'Äà íàïðàâÿ'";
$_lang['News'] = "Íîâèíè";
$_lang['Other'] = "Äðóãè";
$_lang['Settings'] = "Íàñòðîéêè";
$_lang['Summary'] = "Êðàòêà Èíôîðìàöèÿ";

// options.php
$_lang['Description:'] = "Îïèñàíèå:";
$_lang['Comment:'] = "Êîìåíòàð:";
$_lang['Insert a valid Internet address! '] = "Ñëîæåòå âàëèäåí Èíòåðíåò àäðåñ! ";
$_lang['Please specify a description!'] = "Ìîëÿ îïðåäåëåòå îïèñàíèå!";
$_lang['This address already exists with a different description'] = "Òîçè àäðåñ âå÷å ñúùåñòâóâà ñ ðàçëè÷íî îïèñàíèå";
$_lang[' already exists. '] = " âå÷å ñúùåñòâóâà. ";
$_lang['is taken to the bookmark list.'] = "å îòíåñåí â ñïèñúêà ñ îòìåòêè.";
$_lang[' is changed.'] = " å ïðîìåíåí.";
$_lang[' is deleted.'] = " å èçòðèò.";
$_lang['Please specify a description! '] = "Ìîëÿ îïðåäåëåòå îïèñàíèå! ";
$_lang['Please select at least one name! '] = "Ìîëÿ èçáåðåòå ïîíå åäíî èìå! ";
$_lang[' is created as a profile.<br>'] = " å ñúçäàäåí êàòî ïðîôèë.<br>";
$_lang['is changed.<br>'] = "å ïðîìåíåí.<br>";
$_lang['The profile has been deleted.'] = "Ïðîôèëúò áåøå èçòðèò.";
$_lang['Please specify the question for the poll! '] = "Ìîëÿ îïðåäåëåòå âúïðîñúò çà ãëàñóâàíåòî! ";
$_lang['You should give at least one answer! '] = "Òðÿáâà äà äàäåòå ïîíå åäèí îòãîâîð! ";
$_lang['Your call for votes is now active. '] = "Your call for votes is now active. ";
$_lang['<h2>Bookmarks</h2>In this section you can create, modify or delete bookmarks:'] = "<h2>Îòìåòêè</h2>Â òàçè ñåêöèÿ ìîæåòå äà ñúçäàâàòå, ïðîìåíÿòå èëè èçòðèâàòå îòìåòêè:";
$_lang['Create'] = "Ñúçäàâàíå";


$_lang['<h2>Profiles</h2>In this section you can create, modify or delete profiles:'] = "<h2>Ïðîôèëè</h2> Òóê ìîæåòå äà ñúçäàâàòå, ïðîìåíÿòå èëè èçòðèâàòå ïðîôèëè:";
$_lang['<h2>Voting Formula</h2>'] = "<h2>Îäèïêâü þü æâüÿêóüõå</h2>";
$_lang['In this section you can create a call for votes.'] = "In this section you can create a call for votes.";
$_lang['Question:'] = "Âúïðîñ:";
$_lang['just one <b>Alternative</b> or'] = "ñàìî åäèí <b>Àëòåðíàòèâåí</b> èëè";
$_lang['several to choose?'] = "íÿêîëêî çà èçáèðàíå?";

$_lang['Participants:'] = "Ó÷àñòíèöè:";

$_lang['<h3>Password Change</h3> In this section you can choose a new random generated password.'] = "<h3>Ñìÿíà íà ïàðîëà</h3> Òóê ìîæåòå äà èçáåðåòå íîâà ïðîèçâîëíî ãåíèðèðàíà ïàðîëà.";
$_lang['Old Password'] = "Ñòàðà ïàðîëà";
$_lang['Generate a new password'] = "Ãåíåðèðàíå íà íîâà ïàðîëà";
$_lang['Save password'] = "Çàïîìíÿíå íà ïàðîëàòà";
$_lang['Your new password has been stored'] = "Âàøàòà íîâà ïàðîëà áåøå çàïîìíåíà";
$_lang['Wrong password'] = "Ãðåøíà ïàðîëà";
$_lang['Delete poll'] = "Èçòðèâàíå íà ãëàñóâàíåòî";
$_lang['<h4>Delete forum threads</h4> Here you can delete your own threads<br>Only threads without a comment will appear.'] = "<h4>Èçòðèâàíå íà íèøêèòå îò ôîðóìà</h4> Here you can delete your own threads<br>
Only threads without a comment will appear.";

$_lang['Old password'] = "Ñòàðà ïàðîëà";
$_lang['New Password'] = "Íîâà ïàðîëà";
$_lang['Retype new password'] = "Íàïèøåòå îòíîâî íîâàòà ïàðîëà";
$_lang['The new password must have 5 letters at least'] = "Íîâàòà ïàðîëà òðÿáâà äà èìà ïîíå 5 áóêâè";
$_lang['You didnt repeat the new password correctly'] = "Íå ïîâòîðèõòå íîâàòà ïàðîëà ïðàâèëíî";

$_lang['Show bookings'] = "Show bookings";
$_lang['Valid characters'] = "Âàëèäíè áóêâè";
$_lang['Suggestion'] = "Ïðåäëîæåíèå";
$_lang['Put the word AND between several phrases'] = "Ñëîæåòå AND ìåæäó íÿêîëêî ôðàçè"; // translators: please leave the word AND as it is
$_lang['Write access for calendar'] = "Äîñòúï çà ïèñàíå äî êàëåíäàðà";
$_lang['Write access for other users to your calendar'] = "Äîñòúï çà ïèñàíå âúâ âàøèÿò êàëåíäàð íà äðóãèòå ïîòðåáèòåëè";
$_lang['User with chief status still have write access'] = "Ïîòðåáèòåë ñ äèðåêòîðñêè äîñòúï ïðîäúëæàâàò äà èìàò äîñòúï çà ïèñàíå";

// projects
$_lang['Project Listing'] = "Ñïèñúê íà ïðîåêòè";
$_lang['Project Name'] = "Èìå íà ïðîåêò";


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
$_lang['Participants'] = "Ó÷àñòíèöè";
$_lang['Priority'] = "Ïðèîðèòåò";
$_lang['Status'] = "Ñòàòóñ";
$_lang['Last status change'] = "Ïîñëåäíà <br>ïðîìÿíà";
$_lang['Leader'] = "Ëèäåð";
$_lang['Statistics'] = "Ñòàòèñòèêà";
$_lang['My Statistic'] = "Ìîÿ ñòàòèñòèêà";

$_lang['Person'] = "×îâåê";
$_lang['Hours'] = "×àñîâå";
$_lang['Project summary'] = "Èíôîðìàöèÿ çà ïðîåêòà";
$_lang[' Choose a combination Project/Person'] = " Èçáåðåòå êîìáèíàöèÿ Ïðîåêò/×îâåê";
$_lang['(multiple select with the Ctrl/Cmd-key)'] = "(èçáèðàíå íà íÿêîëêî ñ 'Ctrl')";

$_lang['Persons'] = "Õîðà";
$_lang['Begin:'] = "Íà÷àëî:";
$_lang['End:'] = "Êðàé:";
$_lang['All'] = "Âñè÷êè";
$_lang['Work time booked on'] = "Work time booked on";
$_lang['Sub-Project of'] = "Ïîäïðîåêò íà";
$_lang['Aim'] = "Öåë";
$_lang['Contact'] = "Êîíòàêò";
$_lang['Hourly rate'] = "Íîðìà çà ÷àñ";
$_lang['Calculated budget'] = "Ñìåòíàò áþäæåò";
$_lang['New Sub-Project'] = "Íîâ ïîäïðîåêò";
$_lang['Booked To Date'] = "Booked until now";
$_lang['Budget'] = "Áþäæåò";
$_lang['Detailed list'] = "Ñïèñúê ñ äåòàéëè";
$_lang['Gantt'] = "Gantt";
$_lang['offered'] = "îôåðèðàí";
$_lang['ordered'] = "ïîðú÷àí";
$_lang['Working'] = "ðàáîòåù";
$_lang['ended'] = "ïðèêëþ÷èë";
$_lang['stopped'] = "ñïðÿí";
$_lang['Re-Opened'] = "îòâîðåí îòíîâî";
$_lang['waiting'] = "÷àêàù";
$_lang['Only main projects'] = "Ñàìî ãëàâíè ïðîåêòè";
$_lang['Only this project'] = "Ñàìî òîçè ïðîåêò";
$_lang['Begin > End'] = "Íà÷àëî > Êðàé";
$_lang['ISO-Format: yyyy-mm-dd'] = "ISO ôîðìàò: ãããã-ìì-ää";
$_lang['The timespan of this project must be within the timespan of the parent project. Please adjust'] = "The timespan of this project must be within the timespan of the parent project. Please adjust";
$_lang['Please choose at least one person'] = "Ìîëÿ èçáåðåòå ïîíå åäèí ÷îâåê";
$_lang['Please choose at least one project'] = "Ìîëÿ èçáåðåòå ïîíå åäèí ïðîåêò";
$_lang['Dependency'] = "Çàâèñèìîñò";
$_lang['Previous'] = "Ïðåäèøåí";

$_lang['cannot start before the end of project'] = "íåìîæå äà çàïî÷íå ïðåäè ñâúðøâàíåòî íà ïðîåêòà";
$_lang['cannot start before the start of project'] = "íåìîæå äà çàïî÷íå ïðåäè íà÷àëîòî íà ïðîåêòà";
$_lang['cannot end before the start of project'] = "íåìîæå äà ñâúðøè ïðåäè çàïà÷âàíåòî íà ïðîåêòà";
$_lang['cannot end before the end of project'] = "íåìîæå äà ñâúðøè ïðåäè ñâúðøâàíåòî íà ïðîåêòà";
$_lang['Warning, violation of dependency'] = "Âíèìàíèå, íàðóøåíèå íà çàâèñèìîñòòà";
$_lang['Container'] = "Êîíòåèíåð";
$_lang['External project'] = "Âúíøíè ïðîåêòè";
$_lang['Automatic scaling'] = "Automatic scaling";
$_lang['Legend'] = "Ëåãåíäà";
$_lang['No value'] = "Íÿìà ñòîèíîñò"; 
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
$_lang['please check the status!'] = "ìîëÿ ïðîâåðåòå ñòàòóñà!";
$_lang['Todo List: '] = "Ñïèñúê 'Äà íàïðàâÿ': ";
$_lang['New Remark: '] = "Íîâà çàáåëåæêà: ";
$_lang['Delete Remark '] = "èçòðèâàíå íà çàáåëåæêàòà ";
$_lang['Keyword Search'] = "Òúðñåíå íà êëþ÷îâà äóìà";
$_lang['Events'] = "Ñúáèòèÿ";
$_lang['the forum'] = "ôîðóìúò";
$_lang['the files'] = "ôàéëîâåòå";
$_lang['Addresses'] = "Àäðåñè";
$_lang['Extended'] = "Ðàçøèðåí";
$_lang['all modules'] = "âñè÷êè ìîäóëè";
$_lang['Bookmarks:'] = "Îòìåòêè:";
$_lang['List'] = "Ñïèñúöè";
$_lang['Projects:'] = "Ïðîåêòè:";

$_lang['Deadline'] = "Êðàåí ñðîê";

$_lang['Polls:'] = "Âîò:";

$_lang['Poll created on the '] = "Ãëàñóâàíåòî ñúçäàäåíî íà ";


// reminder.php
$_lang['Starts in'] = "Çàïî÷âà â";
$_lang['minutes'] = "ìèíóòè";
$_lang['No events yet today'] = "Äíåñ âñå îùå íÿìà ñúáèòèÿ";
$_lang['New mail arrived'] = "Ïðèñòèãíà  íîâà ïîùà";

//ress.php

$_lang['List of Resources'] =  "Ñïèñúê íà ðåñóðñè";
$_lang['Name of Resource'] = "Èìå íà ðåñóðñ";
$_lang['Comments'] =  "Êîìåíòàðè";


// roles
$_lang['Roles'] = "Ðîëè";
$_lang['No access'] = "Íÿìà äîñòúï";
$_lang['Read access'] = "Äîñòúï çà ÷åòåíå";

$_lang['Role'] = "Ðîëÿ";

// helpdesk - rts
$_lang['Request'] = "Èçèñêâàíå";

$_lang['pending requests'] = "íåðåøåíè èñêàíèÿ";
$_lang['show queue'] = "ïîêàçâàíå íà îïàøêàòà";
$_lang['Search the knowledge database'] = "Òúðñåíå íà áàçàòà äàííè îò çíàíèÿ";
$_lang['Keyword'] = "êëþ÷îâà äóìà";
$_lang['show results'] = "ïîêàçâàíå íà ðåçóëòàò";
$_lang['request form'] = "ôîðìà çà èñêàíå";
$_lang['Enter your keyword'] = "Âúâåäåòå âàøàòà êëþ÷îâà äóìà";
$_lang['Enter your email'] = "Âúâåäåòå âàøèÿò email";
$_lang['Give your request a name'] = "Äàéòå èìå íà âàøåòî èñêàíå";
$_lang['Describe your request'] = "Îïèøåòå âàøåòî èñêàíå";

$_lang['Due date'] = "Due date";
$_lang['Days'] = "Äíè";
$_lang['Sorry, you are not in the list'] = "Ñúæåëÿâàìå, íî âèå íå ñòå â ñïèñúêà";
$_lang['Your request Nr. is'] = "Íîìåðà íà âàøàòà çàÿâêà å";
$_lang['Customer'] = "Êëèåíò";


$_lang['Search'] = "Òúðñåíå";
$_lang['at'] = "â";
$_lang['all fields'] = "âñè÷êè ïîëåòà";


$_lang['Solution'] = "Ðåøåíèå";
$_lang['AND'] = "È";

$_lang['pending'] = "íåðåøåí";
$_lang['stalled'] = "stalled";
$_lang['moved'] = "ïðåìåñòåí";
$_lang['solved'] = "ðåçðåøåí";
$_lang['Submit'] = "Ïðåäàâàíå";
$_lang['Ass.'] = "Íàçí.";
$_lang['Pri.'] = "Ïðèîðèòåò";
$_lang['access'] = "äîñòúï";
$_lang['Assigned'] = "Íàçíà÷åí";

$_lang['update'] = "îáíîâÿâàíå";
$_lang['remark'] = "çàáåëåæêà";
$_lang['solve'] = "solve";
$_lang['stall'] = "stall";
$_lang['cancel'] = "îòêàç";
$_lang['Move to request'] = "Move to request";
$_lang['Dear customer, please refer to the number given above by contacting us.Will will perform your request as soon as possible.'] = "Dear customer, please refer to the number given above by contacting us.
Will will perform your request as soon as possible.";
$_lang['Your request has been added into the request queue.<br>You will receive a confirmation email in some moments.'] = "Your request has been added into the request queue.<br>
You will receive a confirmation email in some moments.";
$_lang['n/a'] = "n/a";
$_lang['internal'] = "âúòðåøåí";

$_lang['has reassigned the following request'] = "has reassigned the following request";
$_lang['New request'] = "Íîâà çàÿâêà";
$_lang['Assign work time'] = "Assign work time";
$_lang['Assigned to:'] = "Íàçíà÷åí íà:";

$_lang['Your solution was mailed to the customer and taken into the database.'] = "Your solution was mailed to the customer and taken into the database.";
$_lang['Answer to your request Nr.'] = "Answer to your request Nr.";
$_lang['Fetch new request by mail'] = "Fetch new request by mail";
$_lang['Your request was solved by'] = "Your request was solved by";

$_lang['Your solution was mailed to the customer and taken into the database'] = "Your solution was mailed to the customer and taken into the database";
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
$_lang['The settings have been modified'] = "Íàñòðîéêèòå áÿõà ïðîìåíåíè";
$_lang['Skin'] = "Èçãëåä";
$_lang['First module view on startup'] = "Ïúðâè ìîäóë ïðè ñòàðòèðàíå";
$_lang['none'] = "íÿìà";
$_lang['Check for mail'] = "Ïðîâåðêà çà ïîùà";
$_lang['Additional alert box'] = "Additional alert box";
$_lang['Horizontal screen resolution <br>(i.e. 1024, 800)'] = "Õîðèçîíòàëíà ðàçäåëèòåëíà ñïîñîáíîñò<br>(ïð. 1024, 800)";
$_lang['Chat Entry'] = "Chat Entry";
$_lang['single line'] = "single line";
$_lang['multi lines'] = "multi lines";
$_lang['Chat Direction'] = "Chat Direction";
$_lang['Newest messages on top'] = "Newest messages on top";
$_lang['Newest messages at bottom'] = "Newest messages at bottom";
$_lang['File Downloads'] = "Ñâàëÿíå íà ôàéëîâå";

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
$_lang['Todays Events'] = "Ñúáèòèÿ çà äíåñ";
$_lang['New files'] = "Íîâè ôàéëîâå";
$_lang['New notes'] = "Íîâè áåëåæêè";
$_lang['New Polls'] = "Íîâè ãëàñîâå";
$_lang['Current projects'] = "Íàñòîÿùè ïðîåêòè";
$_lang['Help Desk Requests'] = "Èñêàíèÿ çà ïîìîù";
$_lang['Current todos'] = "Íàñòîÿùè 'Äà íàïðàâÿ'";
$_lang['New forum postings'] = "Íîâè ñúîáùåíèÿ âúâ ôîðóìà";
$_lang['New Mails'] = "New Mails";

//timecard

$_lang['Theres an error in your time sheet: '] = "There's an error in your time sheet: ";




$_lang['Consistency check'] = "Consistency check";
$_lang['Please enter the end afterwards at the'] = "Please enter the end afterwards at the";
$_lang['insert'] = "insert";
$_lang['Enter records afterwards'] = "Enter records afterwards";
$_lang['Please fill in only emtpy records'] = "Please fill in only emtpy records";

$_lang['Insert a period, all records in this period will be assigned to this project'] = "Insert a period, all records in this period will be assigned to this project";
$_lang['There is no record on this day'] = "There is no record on this day";
$_lang['This field is not empty. Please ask the administrator'] = "This field is not empty. Please ask the administrator";
$_lang['There is no open record with a begin time on this day!'] = "There is no open record with a begin time on this day!";
$_lang['Please close the open record on this day first!'] = "Please close the open record on this day first!";
$_lang['Please check the given time'] = "Please check the given time";
$_lang['Assigning projects'] = "Íàçíà÷àâàíå íà ïðîåêòè";
$_lang['Select a day'] = "Èçáåðåòå äåí";
$_lang['Copy to the boss'] = "Copy to the boss";
$_lang['Change in the timecard'] = "Change in the timecard";
$_lang['Sum for'] = "Ñóìà çà";

$_lang['Unassigned time'] = "Íåíàçíà÷åíî âðåìå";
$_lang['delete record of this day'] = "èçòðèâàíå íà çàïèñà";
$_lang['Bookings'] = "Bookings";

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
$_lang['accepted'] = "ïðèåò";
$_lang['rejected'] = "îòõâúðëåí";
$_lang['own'] = "own";
$_lang['progress'] = "progress";
$_lang['delegated to'] = "delegated to";
$_lang['Assigned from'] = "assigend from";
$_lang['done'] = "ãîòîâ";
$_lang['Not yet assigned'] = "Not yet assigned";
$_lang['Undertake'] = "Undertake";
$_lang['New todo'] = "New todo"; 
$_lang['Notify recipient'] = "Notify recipient";

// votum.php
$_lang['results of the vote: '] = "results of the vote: ";
$_lang['Poll Question: '] = "question of the vote: ";
$_lang['several answers possible'] = "several answers possible";
$_lang['Alternative '] = "Alternative ";
$_lang['no vote: '] = "no vote: ";
$_lang['of'] = "of";
$_lang['participants have voted in this poll'] = "participants have voted";
$_lang['Current Open Polls'] = "Current open votes";
$_lang['Results of Polls'] = "Result list of all votes";
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