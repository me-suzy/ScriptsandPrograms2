<?php
// is.inc.php, icelandic version
// translation by Örvar Sigurgeirsson <orvars@hugur.is>

$chars = array("A","Á","B","C","D","E","É","F","G","H","I","Í","J","K","L","M","N","O","Ó","P","Q","R","S","T","U","Ú","V","W","X","Y","Z","Þ","Æ","Ö");
$name_month = array("", "Jan", "Feb", "Mar", "Apr", "Maí", "Jún", "Júl", "Ágú", "Sep", "Okt", "Nóv", "Des");
$l_text31a = array("sjálfgefið", "15 mín.", "30 mín.", " 1 klst", " 2 klst", " 4 klst", " 1 dagur");
$l_text31b = array(0, 15, 30, 60, 120, 240, 1440);
$name_day = array("Sunnudagur", "Mánudagur", "Þriðjudagur", "Miðvikudagur", "Fimmtudagur", "Föstudagur", "Laugardagur");
$name_day2 = array("Má", "Þr", "Mi", "Fi", "Fö", "La","Su");

$_lang['No Entries Found']= "No Entries Found";
$_lang['No Todays Events']= "HNo Todays Events";
$_lang['No new forum postings']= "No new forum postings";
$_lang['in category']= "in category";
$_lang['Filtered']= "Filtered";
$_lang['Sorted by']= "Sorted by";
$_lang['go'] = "Staðfesta";
$_lang['back'] = "Til baka ";
$_lang['print'] = "Prenta";
$_lang['export'] = "Flytja";
$_lang['| (help)'] = "| (hjálp)";
$_lang['Are you sure?'] = "Ert þú viss?";
$_lang['items/page'] = "hlutir/síðu";
$_lang['records'] = "færslur"; // elements
$_lang['previous page'] = "fyrri síða";
$_lang['next page'] = "næsta síða";
$_lang['first page'] = "first page";
$_lang['last page'] = "last page";
$_lang['Move']  = "Færa";
$_lang['Copy'] = "Afrita";
$_lang['Delete'] = "Eyða";
$_lang['Save'] = "Vista";
$_lang['Directory'] = "Mappa/skráarsafn";
$_lang['Also Delete Contents'] = "eyða innihaldi líka";
$_lang['Sum'] = "Samtals";
$_lang['Filter'] = "Sía";
$_lang['Please fill in the following field'] = "Vinsamlegast fyllið út þennan reit";
$_lang['approve'] = "Staðfesta";
$_lang['undo'] = "Hætta við";
$_lang['Please select!']="Veljið!";
$_lang['New'] = "Nýskrá";
$_lang['Select all'] = "Velja allt";
$_lang['Printable view'] = "Prentvæn sýn";
$_lang['New record in module '] = "Ný færsla í kerfinu ";
$_lang['Notify all group members'] = "Tilkynna öllum í hópnum";
$_lang['Yes'] = "Já";
$_lang['No'] = "Nei";
$_lang['Close window'] = "Close window";
$_lang['No Value'] = "No Value"; 
$_lang['Standard'] = "Standard";
$_lang['Create'] = "Anlegen";
$_lang['Modify'] = "Ändern";   
$_lang['today'] = "today";

// admin.php
$_lang['Password'] = "Aðgangsorð";
$_lang['Login'] = "Notandi";
$_lang['Administration section'] = "Kerfisstjórnun";
$_lang['Your password'] = "Aðgangsorðið þitt";
$_lang['Sorry you are not allowed to enter. '] = "Því miður, aðgangur ekki leyfður. ";
$_lang['Help'] = "Hjálp";
$_lang['User management'] = "Aðgangsstjórnun - notendur";
$_lang['Create'] = "Nýskrá";
$_lang['Projects'] = "Verk - viðhald";
$_lang['Resources'] = "Forðar - viðhald";
$_lang['Resources management'] = "Resources management";
$_lang['Bookmarks'] = "Tenglar";
$_lang['for invalid links'] = "tengla sem virka ekki";
$_lang['Check'] = "Athuga";
$_lang['delete Bookmark'] = "Eyða tengli";
$_lang['(multiple select with the Ctrl-key)'] = "(Velja marga með 'Ctrl'-hnappinum)";
$_lang['Forum'] = "Þræðir(forum)";
$_lang['Threads older than'] = "Þræðir sem eru eldri en";
$_lang[' days '] = " daga ";
$_lang['Chat'] = "Spjalla";
$_lang['save script of current Chat'] = "Vista núverandi spjallrit";
$_lang['Chat script'] = "Spjallrit";
$_lang['New password'] = "Nýtt aðgangasorð";
$_lang['(keep old password: leave empty)'] = "(Skiljið reitinn eftir auðan til að nota núverandi aðgangsorð áfram)";
$_lang['Default Group<br> (must be selected below as well)'] = "Sjálfgefinn hópur<br> (verður að velja hér að ofan)";
$_lang['Access rights'] = "Aðgangsheimildir";
$_lang['Zip code'] = "Póstnúmer";
$_lang['Language'] = "Tungumál";
$_lang['schedule readable to others'] = "Dagbók sýnileg öðrum notendum";
$_lang['schedule invisible to others'] = "Dagbók ekki sýnileg öðrum notendum";
$_lang['schedule visible but not readable'] = "Dagbók sjánleg en ekki skrifanleg öðrum";
$_lang['these fields have to be filled in.'] = "Þessa reiti verður að fylla út.";
$_lang['You have to fill in the following fields: family name, short name and password.'] = "Það verður að setja inn í eftirfarandi reiti: notandi, stutt nafn og aðgangsorð.";
$_lang['This family name already exists! '] = "Þetta notandanafn er þegar til! ";
$_lang['This short name already exists!'] = "Þetta stutta nafn er þegar til!";
$_lang['This login name already exists! Please chosse another one.'] = "Þetta notandanafn er þegar til! Veljið annað nafn.";
$_lang['This password already exists!'] = "Þetta aðgangsorð er til!  Veljið annað";
$_lang['This combination first name/family name already exists.'] = "Þessi samsetning notandanafns/stutts nafns er þegar til.";
$_lang['the user is now in the list.'] = "Notandinn er nýskráður inn í kerfið.";
$_lang['the data set is now modified.'] = "Núverandi gögnum hefur verið breytt.";
$_lang['Please choose a user'] = "Veljið notanda";
$_lang['is still listed in some projects. Please remove it.'] = "er skráður í verk. Eyðið þeim tengingum.";
$_lang['All profiles are deleted'] = "Öllum vinahópum hefur verið eytt";
$_lang['A Profile with the same name already exists'] = "A profile with the same name already exists";
$_lang['is taken out of all user profiles'] = "hefur verið tekin(n) úr öllum vinahópum";
$_lang['All todo lists of the user are deleted'] = "Öllum fyrirliggjandi-liðum notanda hefur verið eytt";
$_lang['is taken out of these votes where he/she has not yet participated'] = "hefur verið tekin(n) úr þeim kosningum sem hún/hann hefur ekki tekið enn þátt í";
$_lang['All events are deleted'] = "Öllum atburðum hefur verið eytt";
$_lang['user file deleted'] = "Öllum skrám notanda hefur verið eytt";
$_lang['bank account deleted'] = "Bankareikningi hefur verið eytt";
$_lang['finished'] = "lokið";
$_lang['Please choose a project'] = "Veljið verk";
$_lang['The project is deleted'] = "Verkinu var eytt";
$_lang['All links in events to this project are deleted'] = "Öllum tengingum atburða í þetta verk hefur verið eytt";
$_lang['The duration of the project is incorrect.'] = "verktímabil(duration) er ekki rétt uppsett.";
$_lang['The project is now in the list'] = "Verkið er nýskráð og sjáanlegt á listum";
$_lang['The project has been modified'] = "Verkinu hefur verið breytt";
$_lang['Please choose a resource'] = "Veljið verk";
$_lang['The resource is deleted'] = "Forðanum hefur verið eytt";
$_lang['All links in events to this resource are deleted'] = "Öllum tengingum atburða í þennan forða hefur verið eytt";
$_lang[' The resource is now in the list.'] = " Forðinn er nýskráður og sýnilegur á listum.";
$_lang[' The resource has been modified.'] = " Forðanum hefur verið breytt.";
$_lang['The server sent an error message.'] = "Þjóninn sendi frá sér villuskilaboð.";
$_lang['All Links are valid.'] = "Allir tenglar virka.";
$_lang['Please select at least one bookmark'] = "Veljið minnst einn tengil";
$_lang['The bookmark is deleted'] = "Tenglinum hefur verið eytt";
$_lang['threads older than x days are deleted.'] = "Þráðum sem eru eldri en x daga hefur verið eytt.";
$_lang['All chat scripts are removed'] = "Öllum spjallritum hefur verið eytt";
$_lang['or'] = "eða";
$_lang['Timecard management'] = "Stimpilklukka - viðhald";
$_lang['View'] = "Sýn";
$_lang['Choose group'] = "Veljið hóp";
$_lang['Group name'] = "Nafn hóps";
$_lang['Short form'] = "Stutt nafn";
$_lang['Category'] = "Flokkur";
$_lang['Remark'] = "Athugasemd";
$_lang['Group management'] = "Vinna með hópa";
$_lang['Please insert a name'] = "Sláið inn nafn";
$_lang['Name or short form already exists'] = "Þetta nafn eða stutt nafn hefur þegar verið skráð í kerfi (this name or short form already exists)";
$_lang['Automatic assign to group:'] = "Sjálfvirk úthlutun á hóp:";
$_lang['Automatic assign to user:'] = "Sjálfvirk úthlutun á notanda:";
$_lang['Help Desk Category Management'] = "Beiðnaflokkar - viðhald";
$_lang['Category deleted'] = "Flokknum hefur verið eytt";
$_lang['The category has been created'] = "Flokkurinn er nýskráður";
$_lang['The category has been modified'] = "Flokkur er breyttur";
$_lang['Member of following groups'] = "Meðlimur í eftirfarandi hópum";
$_lang['Primary group is not in group list'] = "Sjálfgefinn hópur er ekki í hópalistanum";
$_lang['Login name'] = "Notandanafn";
$_lang['You cannot delete the default group'] = "Það er ekki hægt að eyða sjálfgefna hópnum";
$_lang['Delete group and merge contents with group'] = "Eyða hópa og afrita uppsetningu hans í annan hóp";
$_lang['Please choose an element'] = "Veljið viðfang";
$_lang['Group created'] = "Hópurinn var nýskráður";
$_lang['File management'] = "Skráarstjórnun";
$_lang['Orphan files'] = "Munaðarlausar skrár";
$_lang['Deletion of super admin root not possible'] = "Ekki er hægt að eyða kerfistjóranum root";
$_lang['ldap name'] = "ldap nafn";
$_lang['mobile // mobile phone'] = "farsími";
$_lang['Normal user'] = "Venjulegur notandi";
$_lang['User w/Chief Rights'] = "Verkefnisstjóri";
$_lang['Administrator'] = "Kerfisstjóri";
$_lang['Logging'] = "Notkun kerfis (Logging)";
$_lang['Logout'] = "Út";
$_lang['posting (and all comments) with an ID'] = "Þræðir (og svör) með ID";
$_lang['Role deleted, assignment to users for this role removed'] = "Réttindum og tengslum réttinda við notendur hefur verið eytt";
$_lang['The role has been created'] = "Réttindin voru nýskráð";
$_lang['The role has been modified'] = "Réttindin voru uppfærð";
$_lang['Access rights'] = "Access rights";
$_lang['Usergroup'] = "Gruppe";
$_lang['logged in as'] = "Angemeldet als";

//chat.php
$_lang['Quit chat']= "Quit chat";

//contacts.php
$_lang['Contact Manager'] = "Uppsetning tengiliða";
$_lang['New contact'] = "Nýskrá tengilið";
$_lang['Group members'] = "Notendur hóps";
$_lang['External contacts'] = "Ytri tengiliður";
$_lang['&nbsp;New&nbsp;'] = "&nbsp;Nýskrá&nbsp;";
$_lang['Import'] = "Flytja inn";
$_lang['The new contact has been added'] = "Tengiliður var nýskráður";
$_lang['The date of the contact was modified'] = "Dagsetningu tengiliðar var eytt";
$_lang['The contact has been deleted'] = "Tengiliðnum hefur verið eytt";
$_lang['Open to all'] = "Sjáanlegur öllum";
$_lang['Picture'] = "Mynd";
$_lang['Please select a vcard (*.vcf)'] = "Veljið vcard (*.vcf)";
$_lang['create vcard'] = "Nýskrá vcard";
$_lang['import address book'] = "Flytja inn heimilisfangabók";
$_lang['Please select a file (*.csv)'] = "veljið skrá (*.csv)";
$_lang['Howto: Open your outlook express address book and select file/export/other book<br>Then give the file a name, select all fields in the next dialog and finish'] = "Hjálp: Opnaðu Outlook Express addressbook og veljið  'file'/'export'/'other book'<br>
Sláið síðan inn skráarheiti, veljið síðan alla reiti í næstu mynd og styðjið síðan á 'Finish' hnappinn";
$_lang['Open outlook at file/export/export in file,<br>choose comma separated values (Win), then select contacts in the next form,<br>give the export file a name and finish.'] = "Veljið 'Open Outlook' í undirvalmyndinni  'File/export/export',<br>
veljið 'comma separated values (Win)', veljið síðan 'tengiliður' í næsta glugga,<br>
gefið skránni nafn og ljúkið aðgerðinni.";
$_lang['Please choose an export file (*.csv)'] = "Veljið útflutningsskrá (*.csv)";
$_lang['Please export your address book into a comma separated value file (.csv), and either<br>1) apply an import pattern OR<br>2) modify the columns of the table with a spread sheet to this format<br>(Delete colums in you file that are not listed here and create empty colums for fields that do not exist in your file):'] = "Please export your address book into a comma separated value file (.csv), and either<br>
1) apply an import pattern OR<br>
2) modify the columns of the table with a spread sheet to this format<br>
(Delete colums in you file that are not listed here and create empty colums for fields that do not exist in your file):";
$_lang['Please insert at least the family name'] = "Setjið a.m.k inn reitinn 'family name'";
$_lang['Record import failed because of wrong field count'] = "Ekki tókst að lesa inn færslu, þar sem ekki er samræmi í fjölda reita";
$_lang['Import to approve'] = "Listi sem á að staðfesta";
$_lang['Import list'] = "Flytja inn";
$_lang['The list has been imported.'] = "Listinn var fluttur inn.";
$_lang['The list has been rejected.'] = "Hætt var við innlestur.";
$_lang['Profiles'] = "Vinahópar";
$_lang['Parent object'] = "Yfir-tengiliður";
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
$_lang['Please select a file'] = "Veljið skrá";
$_lang['A file with this name already exists!'] = "Skrá með þessu nafni er til!";
$_lang['Name'] = "Nafn";
$_lang['Comment'] = "Athugasemd";
$_lang['Date'] = "Dags";
$_lang['Upload'] = "Senda skrá(Upload)";
$_lang['Filename and path'] = "Skráarnafn og slóði";
$_lang['Delete file'] = "Eyða skrá";
$_lang['Overwrite'] = "Skrifa yfir";
$_lang['Access'] = "Aðgangur";
$_lang['Me'] = "ég";
$_lang['Group'] = "group";
$_lang['Some'] = "nokkrir";
$_lang['As parent object'] = "sami og mappa/skráarsafn";
$_lang['All groups'] = "All groups";
$_lang['You are not allowed to overwrite this file since somebody else uploaded it'] = "Þú getur ekki yfirskrifað þessa skrá þar sem annar sendi hana inn";
$_lang['personal'] = "Einka";
$_lang['Link'] = "Tengill";
$_lang['name and network path'] = "Bæta við slóða";
$_lang['with new values'] = "með nýjum gildum";
$_lang['All files in this directory will be removed! Continue?'] = "Öllum skrám í þessu skráarsvæði verður eytt! Staðfesta eyðingu?";
$_lang['This name already exists'] = "Þetta nafn er þegar til";
$_lang['Max. file size'] = "Hámarksstærð skráa";
$_lang['links to'] = "Tenglar til";
$_lang['objects'] = "hlutir";
$_lang['Action in same directory not possible'] = "Ekki er hægt að afrita innan sama skráarsvæðis";
$_lang['Upload = replace file'] = "Senda skrá og endurnýja";
$_lang['Insert password for crypted file'] = "Sláðu inn aðgangsorð fyrir brengluðu skrána";
$_lang['Crypt upload file with password'] = "Brengla skrá með aðgangsorði";
$_lang['Repeat'] = "Endurtaka";
$_lang['Passwords dont match!'] = "Aðgangsorð eru ekki eins!";
$_lang['Download of the password protected file '] = "Sækja læstu skrá ";
$_lang['notify all users with access'] = "Tilkynna öllum notendum sem hafa aðgang";
$_lang['Write access'] = "Skrif-réttindi (write access)";
$_lang['Version'] = "Útgáfa";
$_lang['Version management'] = "Útgáfustjórnun";
$_lang['lock'] = "Læsa";
$_lang['unlock'] = "Opna (aflæsa)";
$_lang['locked by'] = "læst af";
$_lang['Alternative Download'] = "Niðurhlaða á annan hátt";
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
$_lang['Welcome to the setup of PHProject!<br>'] = "Velkomin í uppsetningu PHProject!<br>";
$_lang['Please remark:<ul><li>A blank database must be available<li>Please ensure that the webserver is able to write the file config.inc.php'] = "Athugið eftirfarandi:<ul>
<li>Tómur gagnagrunnur þarf að vera til staðar
<li>Gangið úr skugga um að vefþjóninn geti skrifað í skrána 'config.inc.php'<br> (t.d. 'chmod 777')";
$_lang['<li>If you encounter any errors during the installation, please look into the <a href=help/faq_install.html target=_blank>install faq</a>or visit the <a href=http://www.PHProjekt.com/forum.html target=_blank>Installation forum</a></i>'] = "<li>Ef villur koma í uppsetningunni, skoðið þá <a href='help/faq_install.html' target=_blank>uppsetningar faq-skrána</a>
eða farið inn á <a href='http://www.PHProjekt.com/forum.html' target=_blank>uppsetningar spjallþráðinn</a></i>";
$_lang['Please fill in the fields below'] = "Fyllið í eftirfarandi reiti";
$_lang['(In few cases the script wont respond.<br>Cancel the script, close the browser and try it again).<br>'] = "(Í undantekningartilvikum bregst þessi uppsetning ekki rétt við.<br>
Hættið þá við, lokið vafranum og reynið aftur).<br>";
$_lang['Type of database'] = "Tegund gagnagrunns";
$_lang['Hostname'] = "Þjónn(Hostname)";
$_lang['Username'] = "Notandi";

$_lang['Name of the existing database'] = "Nafn tóma gagnagrunnsins";
$_lang['config.inc.php not found! Do you really want to update? Please read INSTALL ...'] = "config.inc.php fannst ekki! Viltu örugglega uppfæra? Lesið INSTALL ...";
$_lang['config.inc.php found! Maybe you prefer to update PHProject? Please read INSTALL ...'] = "config.inc.php fannst! Kannski viltu frekar uppfæra PHProject? Lesið INSTALL ...";
$_lang['Please choose Installation,Update or Configure!'] = "Veljið 'Uppsetningu' eða 'uppfærslu'! til baka ...";
$_lang['Sorry, I cannot connect to the database! <br>Please close all browser windows and restart the installation.'] = "Því miður, þetta virkar ekki! <br>Lagfærið og endurræsið uppsetningarforritið.";
$_lang['Sorry, it does not work! <br> Please set DBDATE to Y4MD- or let phprojekt change this environment-variable (php.ini)!'] = "Því miður, þetta virkar ekki! <br> Setjið DBDATE sem 'Y4MD-' eða látið PHProjekt breyta þessari breyta (php.ini)!";
$_lang['Seems that You have a valid database connection!'] = "Tengingin við gagnagrunninn virðist vera í lagi!";
$_lang['Please select the modules you are going to use.<br> (You can disable them later in the config.inc.php)<br>'] = "Veljið þá kerfishluta sem á að nota.<br> (Hægt er að breyta þessu vali síðar í config.inc.php skránni)<br>";
$_lang['Install component: insert a 1, otherwise keep the field empty'] = "Til að velja kerfishluta: sláið inn '1', annars skal skilja reitinn eftir auðann";
$_lang['Group views'] = "Hópasýn(Group views)";
$_lang['Todo lists'] = "Fyrirliggjandi(todo)";

$_lang['Voting system'] = "Kosningakerfi";


$_lang['Contact manager'] = "Tengiliðir";
$_lang['Name of userdefined field'] = "Heiti notandaskilgreinda reits";
$_lang['Userdefined'] = "Notandaskilgreindur";
$_lang['Profiles for contacts'] = "Vinahópar fyrir tengiliði";
$_lang['Mail'] = "Tölvupóstur";
$_lang['send mail'] = " einungis senda";
$_lang[' only,<br> &nbsp; &nbsp; full mail client'] = " póst,<br> &nbsp; &nbsp; full póstvirkni (senda, fá)";



$_lang['1 to show appointment list in separate window,<br>&nbsp; &nbsp; 2 for an additional alert.'] = "'1' til að sýna fundi í nýjum glugga,<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'2' auk þess birta nánari áminningar.";
$_lang['Alarm'] = "Birta skilaboð";
$_lang['max. minutes before the event'] = "mínútum fyrir atburð";
$_lang['SMS/Mail reminder'] = "SMS/tölvupósts áminning";
$_lang['Reminds via SMS/Email'] = "Áminningar með SMS/Email";
$_lang['1= Create projects,<br>&nbsp; &nbsp; 2= assign worktime to projects only with timecard entry<br>&nbsp; &nbsp; 3= assign worktime to projects without timecard entry<br>&nbsp; &nbsp; (Selection 2 or 3 only with module timecard!)'] = "'1'= búa til verk og breyta stöðu.<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'2'= auk þess tengja atburði við verk";
$inst_text23 = "'1'= Búa til verk,<br>&nbsp; &nbsp; '2'= Skrá tíma einungis með stimpilklukku<br>
&nbsp; &nbsp; '3'= Skrá tíma með stimpilklukku og handvirkt<br> ";

$_lang['Name of the directory where the files will be stored<br>( no file management: empty field)'] = "Nafn skráarsvæði þar sem skrár verða geymdar<br>( Sleppa skráarstjórakerfi: tómur reitur)";
$_lang['absolute path to this directory (no files = empty field)'] = "Slóði þessa skráarsvæði (absolute path to this directory (no files = empty field)";
$_lang['Time card'] = "Stimpilklukka";
$_lang['1 time card system,<br>&nbsp; &nbsp; 2 manual insert afterwards sends copy to the chief'] = "'1' Stimpilklukkukerfi,<br>
&nbsp;&nbsp;'2' handvirk tímaskráning eftir á sem sendir skilaboð á verkefnisstjóra";
$_lang['Notes'] = "Minnisblöð";
$_lang['Password change'] = "Breyta aðgangsorðum";
$_lang['New passwords by the user - 0: none - 1: only random passwords - 2: choose own'] = "Aðgangsorðaskilgreining notanda - 0: ekkert - 1: nota aðeins slembigerð aðgangsorð - 2: skilgreina handvirkt aðgangsorð";
$_lang['Encrypt passwords'] = "Dulkóða aðgangsorð";
$_lang['Login via '] = "Innskráning í gegnum ";
$_lang['Extra page for login via SSL'] = "Innskráning í gegnum SSL-síðu";
$_lang['Groups'] = "Hópar";
$_lang['User and module functions are assigned to groups<br>&nbsp; &nbsp; (recommended for user numbers > 40)'] = "Hópar fá notenda- og kerfishlutaeiginleika<br>
&nbsp;&nbsp;&nbsp;&nbsp;(ráðlagt þegar fjöldi notenda > 40)";
$_lang['User and module functions are assigned to groups'] = "Hópar fá notenda- og kerfishlutaeiginleika(User and module functions are assigned to groups)";
$_lang['Help desk'] = "Beiðnakerfi (BK)";
$_lang['Help Desk Manager / Trouble Ticket System'] = "Þjónustuborð / Trouble Ticket System";
$_lang['RT Option: Customer can set a due date'] = "BK stilling: Viðskiptavinur getur sett eindaga";
$_lang['RT Option: Customer Authentification'] = "BK stilling: Tegund viðskiptavina sem mega senda inn beiðni";
$_lang['0: open to all, email-address is sufficient<br>1: registered contact enter his family name<br>2: his email'] = "0: opið öllum, einungis þarf að skrá tölvupóstfang, 1: viðskiptavinur þarf að vera tengiliður og skrá inn notandanafn sitt";
$_lang['RT Option: Assigning request'] = "BK stilling: Úthlutun beiðna";
$_lang['0: by everybody, 1: only by persons with status chief'] = "0: allir notendur, 1: aðeins notendur af gerðinni 'chief'";
$_lang['Email Address of the support'] = "Tölvupóstur þjónustuborðs";
$_lang['Scramble filenames'] = "Brengla skráarnöfn";
$_lang['creates scrambled filenames on the server<br>assigns previous name at download'] = "býr til brengluð skráarnöfn á miðlara (server)<br>
sækir upprunaleg nöfn þegar skrár eru sóttar (download)";

$_lang['0: last name, 1: short name, 2: login name'] = "0: föðurnafn, 1: stutt nafn";
$_lang['Prefix for table names in db'] = "Prefix for table names in db";
$_lang['Alert: Cannot create file config.inc.php!<br>Installation directory needs rwx access for your server and rx access to all others.'] = "Viðvörun: ekki er hægt að búa til skrána 'config.inc.php'!<br>
Skráarsafn fyrir innsetningarforrit þarf rwx aðgang fyrir miðlarann og rw fyrir alla aðra (installation directory needs rwx and rx access to all others).";
$_lang['Location of the database'] = "Staðsetning gagnagrunns";
$_lang['Type of database system'] = "Tegund gagnagrunns";
$_lang['Username for the access'] = "Notandanafn inn á gagnagrunn";
$_lang['Password for the access'] = "Aðgangsorð inn á gagnagrunn";
$_lang['Name of the database'] = "Heiti gagnagrunns";
$_lang['Prefix for database table names'] = "Prefix for database table names";
$_lang['First background color'] = "Fyrsti bakgrunnslitur";
$_lang['Second background color'] = "Annar bakgrunnslitur";
$_lang['Third background color'] = "Þriðji bakgrunnslitur";
$_lang['Color to mark rows'] = "Color to mark rows";
$_lang['Color to highlight rows'] = "Color to highlight rows";
$_lang['Event color in the tables'] = "Litur atburðar í töflum";
$_lang['company icon yes = insert name of image'] = "Fyrirtækis-lógó: setjið inn nafn á skrá til að gera virkt";
$_lang['URL to the homepage of the company'] = "URL á heimasíðu fyrirtækis";
$_lang['no = leave empty'] = "nei = ekkert";
$_lang['First hour of the day:'] = "Tillaga að fyrsta innstimplunartíma dags:";
$_lang['Last hour of the day:'] = ":Tillaga að útstimplunartíma dags";
$_lang['An error ocurred while creating table: '] = "Villa kom fram þegar reynt var að búa til töfluna: ";
$_lang['Table dateien (for file-handling) created'] = "Taflan 'dateien' (fyrir skráarkerfi) búin til";
$_lang['File management no = leave empty'] = "Skráarkerfi: nei = skiljið eftir autt";
$_lang['yes = insert full path'] = "já = setjið inn fullan slóða";
$_lang['and the relative path to the PHProjekt directory'] = "og að auki the relative path to the root";
$_lang['Table profile (for user-profiles) created'] = "Taflan 'profile' (fyrir vina-hópa) búin til";
$_lang['User Profiles yes = 1, no = 0'] = "vinahópar: já = 1, nei = 0";
$_lang['Table todo (for todo-lists) created'] = "Taflan 'todo' (fyrir Fyrirliggjandi-liði (todo-lists)) búin til";
$_lang['Todo-Lists yes = 1, no = 0'] = "Fyrirliggjandi (todo): já = 1, nei = 0";
$_lang['Table forum (for discssions etc.) created'] = "Taflan 'forum' (fyrir spjallþræði) búin til";
$_lang['Forum yes = 1, no = 0'] = "Þræðir: já = 1, nei = 0";
$_lang['Table votum (for polls) created'] = "Taflan 'votum' (fyrir kosningar) búin til";
$_lang['Voting system yes = 1, no = 0'] = "Kosningakerfi: já = 1, nei = 0";
$_lang['Table lesezeichen (for bookmarks) created'] = "Taflan 'lesezeichen' (fyrir tengla) búin til";
$_lang['Bookmarks yes = 1, no = 0'] = "Tenglar: já = 1, nei = 0";
$_lang['Table ressourcen (for management of additional ressources) created'] = "Taflan 'ressourcen' (fyrir forðastjórnun) búin til";
$_lang['Resources yes = 1, no = 0'] = "Forðar já = 1, nei = 0";
$_lang['Table projekte (for project management) created'] = "Taflan 'projekte' (fyrir verk) búin til";
$_lang['Table contacts (for external contacts) created'] = "Taflan contacts (fyrir tengiliði) búin til";
$_lang['Table notes (for notes) created'] = "Taflan notes (fyrir minnisblöð) búin til";
$_lang['Table timecard (for time sheet system) created'] = "Taflan timecard (fyrir tímaskráningu) búin til";
$_lang['Table groups (for group management) created'] = "Taflan groups (viðhald hópa) búin til";
$_lang['Table timeproj (assigning work time to projects) created'] = "Taflan timeproj (tímaskráning verka) búin til";
$_lang['Table rts and rts_cat (for the help desk) created'] = "Taflan rts and rts_cat (beiðnakerfi) búin til";
$_lang['Table mail_account, mail_attach, mail_client und mail_rules (for the mail reader) created'] = "Töflurnar mail_account, mail_attach, mail_client og mail_rules (fyrir tölvupóst) búnar til";
$_lang['Table logs (for user login/-out tracking) created'] = "Taflan logs (fyrir vistun á inn- og útskráningum notanda) búin til";
$_lang['Tables contacts_profiles und contacts_prof_rel created'] = "Töflurnar contacts_profiles og contacts_prof_rel búnar til";
$_lang['Project management yes = 1, no = 0'] = "Verk: já = 1, nei = 0";
$_lang['additionally assign resources to events'] = "Gera kleift að skrá forða í atburði dagbókar";
$_lang['Address book  = 1, nein = 0'] = "Dagbók: já = 1, nein = 0";
$_lang['Mail no = 0, only send = 1, send and receive = 2'] = "Tölvupóstur: nei = 0, einungis senda = 1, senda og taka við = 2";
$_lang['Chat yes = 1, no = 0'] = "Chat yes = 1, no = 0";
$_lang['Name format in chat list'] = "Name format in chat list";
$_lang['0: last name, 1: first name, 2: first name, last name,<br> &nbsp; &nbsp; 3: last name, first name'] = "0: last name, 1: first name, 2: first name, last name,<br> &nbsp; &nbsp; 3: last name, first name";
$_lang['Timestamp for chat messages'] = "Timestamp for chat messages";
$_lang['users (for authentification and address management)'] = "'Users' (fyrir aðgangsheimildir og viðhald notendaupplýsinga)";
$_lang['Table termine (for events) created'] = "'Taflan termine' (fyrir atburði) búin til";
$_lang['The following users have been inserted successfully in the table user:<br>root - (superuser with all administrative privileges)<br>test - (chief user with restricted access)'] = "Eftirfarandi notendur hafa verið settir í töfluna 'user':<br>
'root' - (yfirnotandi með öll kerfisstjórnunarréttindi)<br>
'test' - (staðlaður notandi með skertan aðgang)";
$_lang['The group default has been created'] = "Hópurinn 'demo' búinn til";
$_lang['Please do not change anything below this line!'] = "Athugið að breyta engu fyrir neðan þessa línu!";
$_lang['Database error'] = "Villa í gagnagrunni";
$_lang['Finished'] = "Vinnslu lokið";
$_lang['There were errors, please have a look at the messages above'] = "Það komu upp villur, skoðið villuskilaboðin hér að ofan";
$_lang['All required tables are installed and <br>the configuration file config.inc.php is rewritten<br>It would be a good idea to makea backup of this file.<br>'] = "Allar töflur eru komnar inn og <br>
uppsetningarskráin 'config.inc.php' var endurskrifuð<br>
Til öryggis er gott að eiga afrit af þessari skrá.<br>
Lokið öllur vöfrum núna.<br>";
$_lang['The administrator root has the password root. Please change his password here:'] = "kerfisstjórinn 'root' er með aðgangsorðið 'root'. Breytið aðgangsorðinu sem fyrst.<br>";
$_lang['The user test is now member of the group default.<br>Now you can create new groups and add new users to the group'] = "Notendurnir 'root' og 'test' er í hópnum 'demo'.<br>
Nú er hægt að búa til nýja hópa og tengja notendur við þá";
$_lang['To use PHProject with your Browser go to <b>index.php</b><br>Please test your configuration, especially the modules Mail and Files.'] = "Til að nota PHProject í vafra keyrið þá upp <b>index.php</b><br>
Prufið uppsetningu kerfisins, sérstaklega kerfishlutana 'Tölvupóst' og 'Skrár'.";

$_lang['Alarm x minutes before the event'] = "Áminning birtist x mínútum fyrir atburð";
$_lang['Additional Alarmbox'] = "Nánari áminningar";
$_lang['Mail to the chief'] = "Tölvupóstur til yfirverkefnisstjóra";
$_lang['Out/Back counts as: 1: Pause - 0: Workingtime'] = "Er ekki inni á skrifstofu/Er inni á skrifstofu telst sem: 1: frá vinnu - 0: í vinnu";
$_lang['Passwords will now be encrypted ...'] = "Aðgangsorð verða brengluð";
$_lang['Filenames will now be crypted ...'] = "Skráarnöfn verða brengluð ...";
$_lang['Do you want to backup your database right now? (And zip it together with the config.inc.php ...)<br>Of course I will wait!'] = "Viltu afrita gagnagrunninn? (og þjappa honum saman við config.inc.php ...)<br>
Að sjálfsögðu!";
$_lang['Next'] = "Næst";
$_lang['Notification on new event in others calendar'] = "Áminning til annarra þegar settur er atburður í dagbókina þeirra";
$_lang['Path to sendfax'] = "Slóðinn til Sendfax";
$_lang['no fax option: leave blank'] = "Sleppa fax-uppsetningu: skiljið reitinn eftir auðann";
$_lang['Please read the FAQ about the installation with postgres'] = "Lesið FAQ-ið um uppsetninguna með Postgres-gagnagrunninum";
$_lang['Length of short names<br> (Number of letters: 3-6)'] = "Lengd stuttra nafna<br> (Fjöldi stafa: 3-6)";
$_lang['If you want to install PHProjekt manually, you find<a href=http://www.phprojekt.com/files/sql_dump.tar.gz target=_blank>here</a> a mysql dump and a default config.inc.php'] = "Til að setja inn PHProjekt handvirkt er hægt að skoða
<a href='http://www.phprojekt.com/files/sql_dump.tar.gz' target=_blank>hérna</a> 'a mysql dump and a default config.inc.php'";
$_lang['The server needs the privilege to write to the directories'] = "Miðlarinn (The Server) þarf skrif-aðgang á skráarsafnið ('write' to the directory)";
$_lang['Header groupviews'] = "Titill hópasýnar";
$_lang['name, F.'] = "nafn, F.";
$_lang['shortname'] = "stutt nafn";
$_lang['loginname'] = "notandanafn";
$_lang['Please create the file directory'] = "Búið til skráarsvæðið (Please create the file directory)";
$_lang['default mode for forum tree: 1 - open, 0 - closed'] = "Sjálfgefin stilling þráða: 1 - opnir, 0 - lokaðir";
$_lang['Currency symbol'] = "Gjaldmiðilstákn";
$_lang['current'] = "Birtist sem";
$_lang['Default size of form elements'] = "Default size of form elements";
$_lang['use LDAP'] = "nota LDAP";
$_lang['Allow parallel events'] = "Leyfa atburði hlið við hlið (Allow parallel events)";
$_lang['Timezone difference [h] Server - user'] =  "Mismunur á tímabeltum [klst] Þjónn - notandi";
$_lang['Timezone'] = "Tímabelti";
$_lang['max. hits displayed in search module'] = "Hámarks fjöldi skilagilda í leitarkerfi (max. hits displayed in search module)";
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
$_lang['Resource List'] = "Forðalisti";
$_lang['Event List'] = "Atburðalisti";
$_lang['Calendar Views'] = "Hópasýn";

$_lang['Personnel'] = "Personal";
$_lang['Create new event'] = "Búa til &amp; eyða atburðum";
$_lang['Day'] = "Dagur";

$_lang['Until'] = "Til";

$_lang['Note'] = "Minnisblað";
$_lang['Project'] = "Verk";
$_lang['Res'] = "Forði";
$_lang['Once'] = "Einu sinni";
$_lang['Daily'] = "Daglega";
$_lang['Weekly'] = "Vikulega";
$_lang['Monthly'] = "Mánaðarlega";
$_lang['Yearly'] = "Árlega";

$_lang['Create'] = "Nýskrá";

$_lang['Begin'] = "Byrja";
$_lang['Out of office'] = "Er ekki á skrifstofu";
$_lang['Back in office'] = "Er inni á skrifstofu";
$_lang['End'] = "Endir";
$_lang['@work'] = "Í vinnu(@work)";
$_lang['We'] = "Vika";
$_lang['group events'] = "Hóp-atburður";
$_lang['or profile'] = "eða vinahóp";
$_lang['All Day Event'] = "atburdur yfir heilan dag";
$_lang['time-axis:'] = "tíma-ásar:";
$_lang['vertical'] = "lóðréttur";
$_lang['horizontal'] = "láréttur";
$_lang['Horz. Narrow'] = "mjór láréttur";
$_lang['-interval:'] = "-bil:";
$_lang['Self'] = "Ég";

$_lang['...write'] = "...nýskrá";

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
$_lang['Please call login.php!'] = "Keyrið login.php!";

// m1.php
$_lang['There are other events!<br>the critical appointment is: '] = "Aðrir atburðir eru til!<br>Athugið eftirfarandi atburð: ";
$_lang['Sorry, this resource is already occupied: '] = "Því miður, þessi forði er þegar bókaður: ";
$_lang[' This event does not exist.<br> <br> Please check the date and time. '] = " Þessi atburður er ekki til.<br> <br> Athugið dagsetningu og tíma ";
$_lang['Please check your date and time format! '] = "Athugið sniðið á dagsetningunni og tímanum! ";
$_lang['Please check the date!'] = "Athugið dagsetninguna!";
$_lang['Please check the start time! '] = "Athugið byrjunartímann! ";
$_lang['Please check the end time! '] = "Athugið lokatímann! ";
$_lang['Please give a text or note!'] = "Sláið inn texta eða minnisblað!";
$_lang['Please check start and end time! '] = "Athugið byrjunar- og lokatíma! ";
$_lang['Please check the format of the end date! '] = "Athugið sniðið á lokadagsetningunni! ";
$_lang['Please check the end date! '] = "Athugið lokadagsetninguna! ";





$_lang['Resource'] = "Forði";
$_lang['User'] = "Notandi";

$_lang['delete event'] = "Eyða atburði";
$_lang['Address book'] = "Heimilisfangabók";


$_lang['Short Form'] = "Kenninafn";

$_lang['Phone'] = "Sími";
$_lang['Fax'] = "Fax";



$_lang['Bookmark'] = "Tengill";
$_lang['Description'] = "Lýsing";

$_lang['Entire List'] = "Allir";

$_lang['New event'] = "Nýr atburður";
$_lang['Created by'] = "Höfundur";
$_lang['Red button -> delete a day event'] = "Rauður hnappur -> eyða dags-atburði (delete a day event)";
$_lang['multiple events'] = "marga atburði";
$_lang['Year view'] = "Skoða árið";
$_lang['calendar week'] = "vika";

//m2.php
$_lang['Create &amp; Delete Events'] = "Búa til &amp; eyða atburðum";
$_lang['normal'] = "venjulegt";
$_lang['private'] = "einka";
$_lang['public'] = "öllum sjáanlegt";
$_lang['Visibility'] = "Sjáanlegt";

//mail module
$_lang['Please select at least one (valid) address.'] = "Veljið að minnsta kosti eitt (fullgilt) netfang.";
$_lang['Your mail has been sent successfully'] = "Skeytið var sent";
$_lang['Attachment'] = "Viðhengi";
$_lang['Send single mails'] = "Senda stök skeyti";
$_lang['Does not exist'] = "Er ekki til";
$_lang['Additional number'] = "Aukanúmer";
$_lang['has been canceled'] = "var fellt niður";

$_lang['marked objects'] = "völdum liðum";
$_lang['Additional address'] = "Auka netfang";
$_lang['in mails'] = "Tölvupóstur";
$_lang['Mail account'] = "Tölvupóstfang";
$_lang['Body'] = "Efni";
$_lang['Sender'] = "Sendandi";

$_lang['Receiver'] = "Viðtakanda";
$_lang['Reply'] = "Svara";
$_lang['Forward'] = "Senda áfram";
$_lang['Access error for mailbox'] = "Ekki hægt að tengjast pósthólfi";
$_lang['Receive'] = "Sækja póst";
$_lang['Write'] = "Senda";
$_lang['Accounts'] = "Pósthólf";
$_lang['Rules'] = "Reglur";
$_lang['host name'] = "nafn þjóns (host name)";
$_lang['Type'] = "Tegund";
$_lang['misses'] = "bilanir";
$_lang['has been created'] = "var nýskráð";
$_lang['has been changed'] = "var breytt";
$_lang['is in field'] = "er í reitnum";
$_lang['and leave on server'] = "Lesa póst og skilja hann eftir á póstþjóni (Recieve mails and leave them on the server)";
$_lang['name of the rule'] = "Nafn reglu";
$_lang['part of the word'] = "Textinn";
$_lang['in'] = "í";
$_lang['sent mails'] = "send bréf";
$_lang['Send date'] = "Sendingardagsetning";
$_lang['Received'] = "Viðtekið";
$_lang['to'] = "til";
$_lang['imcoming Mails'] = "inn-póstur";
$_lang['sent Mails'] = "út-póstur";
$_lang['Contact Profile'] = "Vinahópur tengiliðs";
$_lang['unread'] = "ólesið";
$_lang['view mail list'] = "skoða tölvupóst";
$_lang['insert db field (only for contacts)'] = "Sækja texta úr reit tengiliða-töflu";
$_lang['Signature'] = "Undirskrift";

$_lang['SMS'] = "SMS";
$_lang['Single account query'] = "- sækja úr einu tölvupóstfangi";
$_lang['Notice of receipt'] = "Tilkynning um viðtöku"; 
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
$_lang['Mail note to'] = "Senda minnisblað til";
$_lang['added'] = "bætt við";
$_lang['changed'] = "breytt";

// o.php
$_lang['Calendar'] = "Dagbók";
$_lang['Contacts'] = "Tengiliðir";


$_lang['Files'] = "Skrár";



$_lang['Options'] = "Stillingar";
$_lang['Timecard'] = "Stimpilklukka";

$_lang['Helpdesk'] = "Beiðnir";

$_lang['Info'] = "Upplýsingar";
$_lang['Todo'] = "Fyrirliggjandi";
$_lang['News'] = "Fréttir";
$_lang['Other'] = "Annað";
$_lang['Settings'] = "Stillingar";
$_lang['Summary'] = "Yfirlit";

// options.php
$_lang['Description:'] = "Lýsing:";
$_lang['Comment:'] = "Athugasemd:";
$_lang['Insert a valid Internet address! '] = "Setjið inn gilda (valid) internet slóð! ";
$_lang['Please specify a description!'] = "Sláið inn lýsingu!";
$_lang['This address already exists with a different description'] = "Þessi slóð(tengill) er þegar til en með aðra lýsingu";
$_lang[' already exists. '] = " er þegar til. ";
$_lang['is taken to the bookmark list.'] = "hefur verið skráð í tenglalistann.";
$_lang[' is changed.'] = " hefur verið breytt.";
$_lang[' is deleted.'] = " hefur verið eytt.";
$_lang['Please specify a description! '] = "Tilgreinið lýsingu! ";
$_lang['Please select at least one name! '] = "Veljið a.m.k. eitt nafn! ";
$_lang[' is created as a profile.<br>'] = " Nýskráist sem vinahópur.<br> vinahópurinn verður virkur eftir að dagbókin er uppfærð.";
$_lang['is changed.<br>'] = "hefur verið breytt.<br> Vinahópurinn verður virkur eftir að dagbókin er uppfærð.";
$_lang['The profile has been deleted.'] = "Vinahópnum hefur verið eytt.";
$_lang['Please specify the question for the poll! '] = "Skráið spurningu kosningarinnar! ";
$_lang['You should give at least one answer! '] = "Skrá verður a.m.k eitt svar! ";
$_lang['Your call for votes is now active. '] = "Kosningin er orðin virk. ";
$_lang['<h2>Bookmarks</h2>In this section you can create, modify or delete bookmarks:'] = "<h2>Tenglar</h2>Hér er hægt að nýskrá, breyta og eyða tenglum:";
$_lang['Create'] = "Nýskrá";


$_lang['<h2>Profiles</h2>In this section you can create, modify or delete profiles:'] = "<h2>Vinahópur</h2>Hér er hægt að nýskrá, breyta og eyða vinahópum:";
$_lang['<h2>Voting Formula</h2>'] = "<h2>Kosningar</h2>";
$_lang['In this section you can create a call for votes.'] = "Hér er hægt að skrá kosningar.";
$_lang['Question:'] = "Spurningar:";
$_lang['just one <b>Alternative</b> or'] = "Einungis eitt <b>svar</b> eða";
$_lang['several to choose?'] = "fleiri en eitt?";

$_lang['Participants:'] = "Þátttakendur:";

$_lang['<h3>Password Change</h3> In this section you can choose a new random generated password.'] = "<h3>Breyta aðgangsorði</h3> Hér er hægt að fá nýtt, slembiskráð aðgangsorð.";
$_lang['Old Password'] = "Núverandi aðgangsorð";
$_lang['Generate a new password'] = "Nýskrá nýtt aðgangsorð";
$_lang['Save password'] = "Vista aðgangsorð";
$_lang['Your new password has been stored'] = "Nýja aðgangsorðið þitt er orðið gilt";
$_lang['Wrong password'] = "Rangt aðgangsorð";
$_lang['Delete poll'] = "Eyða kosningu";
$_lang['<h4>Delete forum threads</h4> Here you can delete your own threads<br>Only threads without a comment will appear.'] = "<h4>Eyða spjallþráðum</h4> Hérna er getur þú eytt þínum þráðum<br>
Einungis þræðir með athugasemdum birtast.";

$_lang['Old password'] = "Gamla aðgangsorð";
$_lang['New Password'] = "Nýtt aðgangsorð";
$_lang['Retype new password'] = "Endurskrifið aðgangsorð";
$_lang['The new password must have 5 letters at least'] = "Aðgangsorðið verður að vera minnst 5 stafir";
$_lang['You didnt repeat the new password correctly'] = "Þú endurskrifaðir aðgangsorðið ekki rétt";

$_lang['Show bookings'] = "Sýna tímaskráningar á verk";
$_lang['Valid characters'] = "Gildir stafir";
$_lang['Suggestion'] = "Tillaga";
$_lang['Put the word AND between several phrases'] = "Setjið orðið AND á milli orða"; // translators: please leave the word AND as it is
$_lang['Write access for calendar'] = "Skrif-réttindi á dagbók";
$_lang['Write access for other users to your calendar'] = "Skrif-réttindi fyrir aðra notendur á þína dagbók";
$_lang['User with chief status still have write access'] = "Verkefnisstjórar hafa enn skrif-réttindi";

// projects
$_lang['Project Listing'] = "Verklisti";
$_lang['Project Name'] = "Nafn verks";


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
$_lang['Participants'] = "Þátttakendur";
$_lang['Priority'] = "Forgangur";
$_lang['Status'] = "Staða";
$_lang['Last status change'] = "Síðast <br>breytt";
$_lang['Leader'] = "Verkstjóri";
$_lang['Statistics'] = "Tölfræði";
$_lang['My Statistic'] = "Mín tölfræði";

$_lang['Person'] = "Þátttakandi";
$_lang['Hours'] = "Klukkustundir";
$_lang['Project summary'] = "Niðurstaða verks(summary)";
$_lang[' Choose a combination Project/Person'] = " Veljið samsetningu á Verki / þátttakendum";
$_lang['(multiple select with the Ctrl/Cmd-key)'] = "(Fjöldaval (multiple select): haldið inni 'Ctrl'-hnappnum)";

$_lang['Persons'] = "Þátttakendur";
$_lang['Begin:'] = "Byrja:";
$_lang['End:'] = "Enda:";
$_lang['All'] = "Allt/Allur";
$_lang['Work time booked on'] = "Vinnutími þann";
$_lang['Sub-Project of'] = "undirverk verksins";
$_lang['Aim'] = "Tilgangur";
$_lang['Contact'] = "Tengiliður";
$_lang['Hourly rate'] = "Söluverð (klst)";
$_lang['Calculated budget'] = "Útreiknaður verkkostnaður";
$_lang['New Sub-Project'] = "Nýtt undirverk";
$_lang['Booked To Date'] = "Bókað fram til þessa";
$_lang['Budget'] = "Áætlaður kostnaður (budget)";
$_lang['Detailed list'] = "Ítarlegur listi";
$_lang['Gantt'] = "Framvinda";
$_lang['offered'] = "tilboð";
$_lang['ordered'] = "pantað";
$_lang['Working'] = "í vinnslu";
$_lang['ended'] = "lokið";
$_lang['stopped'] = "stöðvað";
$_lang['Re-Opened'] = "opnað aftur";
$_lang['waiting'] = "í bið";
$_lang['Only main projects'] = "Aðeins yfirverk";
$_lang['Only this project'] = "Aðeins þetta verk";
$_lang['Begin > End'] = "Byrjun > Lok";
$_lang['ISO-Format: yyyy-mm-dd'] = "ISO-snið: yyyy-mm-dd";
$_lang['The timespan of this project must be within the timespan of the parent project. Please adjust'] = "Verktími þessa verks verður að vera innan verktíma yfirverksins. Vinsamlegast leiðréttið";
$_lang['Please choose at least one person'] = "Veljið a.m.k. einn starfsmann";
$_lang['Please choose at least one project'] = "Veljið a.m.k eitt verk";
$_lang['Dependency'] = "Skilyrði";
$_lang['Previous'] = "Á eftir";

$_lang['cannot start before the end of project'] = "getur ekki byrjað fyrir lok verks";
$_lang['cannot start before the start of project'] = "getur ekki byrjað fyrir upphaf verks";
$_lang['cannot end before the start of project'] = "Getur ekki lokið fyrir upphaf verks";
$_lang['cannot end before the end of project'] = "Getur ekki lokið fyrir lok verks";
$_lang['Warning, violation of dependency'] = "Athugið, skilyrði er ekki uppfyllt";
$_lang['Container'] = "Geymsla(Container)";
$_lang['External project'] = "Ytra verk";
$_lang['Automatic scaling'] = "Sjálfvirk skölun";
$_lang['Legend'] = "Tákn";
$_lang['No value'] = "Engin staða";
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
$_lang['please check the status!'] = "Athugaðu stöðuna!";
$_lang['Todo List: '] = "Fyrirliggjandi: ";
$_lang['New Remark: '] = "Nýr liður: ";
$_lang['Delete Remark '] = "Eyða lið ";
$_lang['Keyword Search'] = "Kenniorðaleit: ";
$_lang['Events'] = "Atburðir";
$_lang['the forum'] = "Þræðir";
$_lang['the files'] = "Skrár";
$_lang['Addresses'] = "Heimilsföng";
$_lang['Extended'] = "ítarleg";
$_lang['all modules'] = "Allar einingar kerfis";
$_lang['Bookmarks:'] = "Tenglar:";
$_lang['List'] = "Listi";
$_lang['Projects:'] = "Verk:";

$_lang['Deadline'] = "Verklok:";

$_lang['Polls:'] = "Kosningar:";

$_lang['Poll created on the '] = "Kosning nýskráð þann ";


// reminder.php
$_lang['Starts in'] = "byrjar eftir";
$_lang['minutes'] = "minútur";
$_lang['No events yet today'] = "Ekkert í dagbókinni dag";
$_lang['New mail arrived'] = "Nýr tölvupóstur";

//ress.php

$_lang['List of Resources'] =  "Forðalisti";
$_lang['Name of Resource'] = "Nafn forða";
$_lang['Comments'] =  "Athugasemdir";


// roles
$_lang['Roles'] = "Aðgangsstjórnun - réttindi";
$_lang['No access'] = "Enginn réttindi";
$_lang['Read access'] = "Les-réttindi";

$_lang['Role'] = "Réttindi";

// helpdesk - rts
$_lang['Request'] = "Beiðni";

$_lang['pending requests'] = "Fyrirliggjandi beiðnir";
$_lang['show queue'] = "Sýna beiðnabiðröð";
$_lang['Search the knowledge database'] = "Leita í þekkingargrunni";
$_lang['Keyword'] = "Kenniorð(leitarorð)";
$_lang['show results'] = "Sýna niðurstöður";
$_lang['request form'] = "Beiðni - eyðublað";
$_lang['Enter your keyword'] = "Sláið inn kenniorð(keyword)";
$_lang['Enter your email'] = "Sláið inn tölvupóstfang";
$_lang['Give your request a name'] = "Nefnið beiðnina";
$_lang['Describe your request'] = "Lýsing";

$_lang['Due date'] = "Búið innan";
$_lang['Days'] = "Dag(a)";
$_lang['Sorry, you are not in the list'] = "Því miður, þú ert ekki á listanum";
$_lang['Your request Nr. is'] = "Beiðnarnúmer þitt er";
$_lang['Customer'] = "Viðskiptavinur";


$_lang['Search'] = "Leita";
$_lang['at'] = "í";
$_lang['all fields'] = "öllum reitum";


$_lang['Solution'] = "Niðurstaða";
$_lang['AND'] = "og";

$_lang['pending'] = "Ekki byrjað";
$_lang['stalled'] = "Hætt við";
$_lang['moved'] = "Flutt";
$_lang['solved'] = "Lokið";
$_lang['Submit'] = "Nýskrá";
$_lang['Ass.'] = "Úthl.";
$_lang['Pri.'] = "Forg.";
$_lang['access'] = "Aðgangur";
$_lang['Assigned'] = "Úthlutun";

$_lang['update'] = "Uppfæra";
$_lang['remark'] = "Athugasemd";
$_lang['solve'] = "Lausn";
$_lang['stall'] = "Frysta";
$_lang['cancel'] = "Hætta við";
$_lang['Move to request'] = "Færð á beiðni:";
$_lang['Dear customer, please refer to the number given above by contacting us.Will will perform your request as soon as possible.'] = "Kæri 
viðskiptavinur, notið beiðnarnúmerið hér að ofan þegar haft er sambandi okkur. Beiðnin verður afgreidd sem fyrst.";
$_lang['Your request has been added into the request queue.<br>You will receive a confirmation email in some moments.'] = "Beiðnin þín er komin í beiðna-biðröðina.<br>
Þú munt fá tölvupóst með staðfestingu innan skamms.";
$_lang['n/a'] = "Ekki fáanlegt";
$_lang['internal'] = "innanhús";

$_lang['has reassigned the following request'] = "hefur endurúthlutað eftirfarandi beiðni";
$_lang['New request'] = "Ný beiðni";
$_lang['Assign work time'] = "Úthluta vinnutíma";
$_lang['Assigned to:'] = "Úthlutað á starfsm.:";

$_lang['Your solution was mailed to the customer and taken into the database.'] = "Lausnin var send til viðskiptavinar og skráð í gagnagrunninn.";
$_lang['Answer to your request Nr.'] = "Svar vegna beiðnar þinnar nr.:";
$_lang['Fetch new request by mail'] = "Sækja nýja beiðni með tölvupósti";
$_lang['Your request was solved by'] = "Beiðnin þín var unnin af";

$_lang['Your solution was mailed to the customer and taken into the database'] = "Niðurstaðan þín var send til viðskiptavinarins og vistuð";
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
$_lang['The settings have been modified'] = "Stillingar hafa verið uppfærðar";
$_lang['Skin'] = "Skinn";
$_lang['First module view on startup'] = "Kerfishluti sem birtist við ræsingu";
$_lang['none'] = "óvirkt";
$_lang['Check for mail'] = "Pop-Up";
$_lang['Additional alert box'] = "SMS/Email";
$_lang['Horizontal screen resolution <br>(i.e. 1024, 800)'] = "Mínútur sem áminning birtist fyrir atburð";
$_lang['Chat Entry'] = "Spjallinnsláttur";
$_lang['single line'] = "ein lína";
$_lang['multi lines'] = "margar lína";
$_lang['Chat Direction'] = "Chat Direction";
$_lang['Newest messages on top'] = "Newest messages on top";
$_lang['Newest messages at bottom'] = "Newest messages at bottom";
$_lang['File Downloads'] = "Niðurhalning skráa";

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
$_lang['Todays Events'] = "Atburðir í dag";
$_lang['New files'] = "Nýjar skrár";
$_lang['New notes'] = "Ný minnisblöð";
$_lang['New Polls'] = "Nýjar kosningar";
$_lang['Current projects'] = "Verk í vinnslu";
$_lang['Help Desk Requests'] = "Beiðnakerfi";
$_lang['Current todos'] = "Fyrirliggjandi(Todos)";
$_lang['New forum postings'] = "Nýir spjallþræðir";
$_lang['New Mails'] = "Ný tölvupóstsskeyti";

//timecard

$_lang['Theres an error in your time sheet: '] = "Það er villa í tímaskráningunni! Athugaðu hana.";




$_lang['Consistency check'] = "Gloppuprófun";
$_lang['Please enter the end afterwards at the'] = "Sláið inn út-tíma hjá";
$_lang['insert'] = "Nýskrá";
$_lang['Enter records afterwards'] = "Eftiráskráning tíma";
$_lang['Please fill in only emtpy records'] = "Skráið einungis í óskráða daga";

$_lang['Insert a period, all records in this period will be assigned to this project'] = "Sláið inn tímabil, öllum tímaskráningar innan þessa tímabils verður úthlutað á þetta verk";
$_lang['There is no record on this day'] = "Það er engin tímaskráning á þessum degi";
$_lang['This field is not empty. Please ask the administrator'] = "Þessi reitur er ekki auður. Hafið samband við kerfisstjóra";
$_lang['There is no open record with a begin time on this day!'] = "Dagsetningarnar sem voru valdar eru ekki réttar, athugið þær betur.";
$_lang['Please close the open record on this day first!'] = "Setjið fyrst inn inn-tíma";
$_lang['Please check the given time'] = "Athugið tímasetninguna";
$_lang['Assigning projects'] = "Úthlutun verka";
$_lang['Select a day'] = "Veljið dag";
$_lang['Copy to the boss'] = "Senda verkefnastjóra afrit";
$_lang['Change in the timecard'] = "Breyting í stimplun";
$_lang['Sum for'] = "Summa";

$_lang['Unassigned time'] = "Óúthlutaður tími";
$_lang['delete record of this day'] = "Eyða færslum þessa dags";
$_lang['Bookings'] = "Tímaskráning á verk";

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
$_lang['accepted'] = "samþykkt";
$_lang['rejected'] = "hafnað";
$_lang['own'] = "ég";
$_lang['progress'] = "Framvinda";
$_lang['delegated to'] = "úthlutað til";
$_lang['Assigned from'] = "úthlutað af";
$_lang['done'] = "done";
$_lang['Not yet assigned'] = "Not yet assigned";
$_lang['Undertake'] = "Undertake";
$_lang['New todo'] = "New todo"; 
$_lang['Notify recipient'] = "Notify recipient";

// votum.php
$_lang['results of the vote: '] = "Niðurstaða kosningar: ";
$_lang['Poll Question: '] = "spurning kosningar: ";
$_lang['several answers possible'] = "nokkrar spurningar";
$_lang['Alternative '] = "val: ";
$_lang['no vote: '] = "ekkert val: ";
$_lang['of'] = "af";
$_lang['participants have voted in this poll'] = "hafa kosið";
$_lang['Current Open Polls'] = "Opnar kosningar";
$_lang['Results of Polls'] = "Niðurstöður kosninga";
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