<?php  // hu.inc.php, hungarian version,
// initial translation by Sandor Feher <fehers@linux-perfect.hu>
// modified by Zoltan Fekete <fekete@mail.szivarvanynet.hu>, 2004.02.13, Budapest v1.2

$chars = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
$name_month = array("", "Jan", "Feb", "Már", "Ápr", "Máj", "Jún", "Júl", "Aug", "Szep", "Okt", "Nov", "Dec");
$l_text31a = array("alapértelmezés", "15 perc", "30 perc", " 1 óra", " 2 óra", " 4 óra", " 1 nap");
$l_text31b = array(0, 15, 30, 60, 120, 240, 1440);
$name_day = array("Vasárnap", "Hétfõ", "Kedd", "Szerda", "Csütörtök", "Péntek", "Szombat");
$name_day2 = array("Hé", "Ke", "Sze", "Csü", "Pé", "Szo","Vas");

$_lang['No Entries Found']= "No Entries Found";
$_lang['No Todays Events']= "HNo Todays Events";
$_lang['No new forum postings']= "No new forum postings";
$_lang['in category']= "in category";
$_lang['Filtered']= "Filtered";
$_lang['Sorted by']= "Sorted by";
$_lang['submit'] = "mehet";
$_lang['back'] = "vissza";
$_lang['print'] = "nyomtatás";
$_lang['export'] = "export";
$_lang['| (help)'] = "| (súgó)";
$_lang['Are you sure?'] = "Biztos benne?";
$_lang['items/page'] = "bejyegyzés/lap";
$_lang['records'] = "bejegyzés";
$_lang['previous page'] = "elõzõ lap";
$_lang['next page'] = "következõ lap";
$_lang['first page'] = "first page";
$_lang['last page'] = "last page";
$_lang['Move']  = "Mozgatás";
$_lang['Copy'] = "Másolás";
$_lang['Delete'] = "Törlés";
$_lang['Save'] = "mentés";
$_lang['Directory'] = "Könyvtár";
$_lang['Also Delete Contents'] = "törli a tartalmát is";                                                                                 
$_lang['Sum'] = "Összesítõ";
$_lang['Filter'] = "Szûrõ";
$_lang['Please fill in the following field'] = "Kérem töltse ki a következõ mezõt";
$_lang['approve'] = "jóváhagyás";
$_lang['undo'] = "visszavonás";
$_lang['Please select!']="Kérem válasszon!";
$_lang['New'] = "Új";
$_lang['Select all'] = "Összes kijelölése";
$_lang['Printable view'] = "Nyomtató nézet";
$_lang['New record in module '] = "Új sor a modulban ";
$_lang['Notify all group members'] = "Minden csoporttag értesítése";
$_lang['Yes'] = "Igen";
$_lang['No'] = "Nem";
$_lang['Close window'] = "Ablak bezárása";
$_lang['No Value'] = "Nincs érték";
$_lang['Standard'] = "Standard";
$_lang['Create'] = "Anlegen";
$_lang['Modify'] = "Ändern";   
$_lang['today'] = "today";

// admin.php
$_lang['Password'] = "Jelszó";
$_lang['Login'] = "Név";
$_lang['Administration section'] = "Adminisztrációs rész";
$_lang['Your password'] = "Az ön jelszava";
$_lang['Sorry you are not allowed to enter. '] = "Sajnálom, nem jogosult a belépésre ! ";
$_lang['Help'] = "Súgó";
$_lang['User management'] = "Felhasználókezelõ";
$_lang['Create'] = "Új...";
$_lang['Projects'] = "Projektek";
$_lang['Resources'] = "Erõforrások";
$_lang['Resources management'] = "Resources management";
$_lang['Bookmarks'] = "Könyvjelzõk";
$_lang['for invalid links'] = "az érvénytelen hivatkozásokra";
$_lang['Check'] = "Ellenõrzés";
$_lang['delete Bookmark'] = "Könyvjelzõ törlése";
$_lang['(multiple select with the Ctrl-key)'] = "(többszörös kijelölés a 'Ctrl'-gombbal)";
$_lang['Forum'] = "Fórum";
$_lang['Threads older than'] = "";
$_lang[' days '] = " napnál régebbi témák";
$_lang['Chat'] = "Csevegés";
$_lang['save script of current Chat'] = "elmenti az aktuális csevegést";
$_lang['Chat script'] = "Csevegõ";
$_lang['New password'] = "Új jelszó";
$_lang['(keep old password: leave empty)'] = "(a jelenlegi jelszó megtartásához hagyja üresen)";
$_lang['Default Group<br> (must be selected below as well)'] = "Alapértelmezett Csoport<br> (ki kell választani alább)";
$_lang['Access rights'] = "Hozzáférési jogok";
$_lang['Zip code'] = "Irányítószám";
$_lang['Language'] = "Nyelv";
$_lang['schedule readable to others'] = "mások által olvasható idõpont";
$_lang['schedule invisible to others'] = "mások által láthatatlan idõpont";
$_lang['schedule visible but not readable'] = "mások által látható, de nem olvasható idõpont";
$_lang['these fields have to be filled in.'] = "ezeket a mezõket ki kell tölteni.";
$_lang['You have to fill in the following fields: family name, short name and password.'] = "Ki kell töltenie a következõ mezõket: családnév, rövidnév és jelszó.";
$_lang['This family name already exists! '] = "Ez a családnév már létezik! ";
$_lang['This short name already exists!'] = "Ez a rövidnév már létezik!";
$_lang['This login name already exists! Please chosse another one.'] = "A megadott felhasználói azonosító már létezik! Kérem, válasszon másikat.";
$_lang['This password already exists!'] = "Ez a jelszó már létezik!";
$_lang['This combination first name/family name already exists.'] = "A keresztnév/családnév ebben a kombinációban már létezik.";
$_lang['the user is now in the list.'] = "a felhasználó bekerült a listába.";
$_lang['the data set is now modified.'] = "az adatok módosultak.";
$_lang['Please choose a user'] = "Kérem válasszon egy felhasználót";
$_lang['is still listed in some projects. Please remove it.'] = "még szerepel néhány projektben. Kérem távolítsa el.";
$_lang['All profiles are deleted'] = "Minden profil törölve";
$_lang['A Profile with the same name already exists'] = "A profile with the same name already exists";
$_lang['is taken out of all user profiles'] = "kivéve az összes profilból";
$_lang['All todo lists of the user are deleted'] = "A felhasználó összes teendõje törölve lett a listáról";
$_lang['is taken out of these votes where he/she has not yet participated'] = "kivéve azon szavazatokból amikben még nem vett részt eddig";
$_lang['All events are deleted'] = "Összes esemény törölve";
$_lang['user file deleted'] = "a felhasználó fájlai törlõdtek";
$_lang['bank account deleted'] = "a bankszámla törlõdött";
$_lang['finished'] = "kész";
$_lang['Please choose a project'] = "Kérem válasszon egy projektet";
$_lang['The project is deleted'] = "A projekt törölve";
$_lang['All links in events to this project are deleted'] = "Az összes ehhez a projekthez tartozó link törölve lett";
$_lang['The duration of the project is incorrect.'] = "A projekt idõtartama érvénytelen.";
$_lang['The project is now in the list'] = "A projekt a listában van";
$_lang['The project has been modified'] = "A projekt módosítva lett";
$_lang['Please choose a resource'] = "Kérem válasszon egy erõforrást";
$_lang['The resource is deleted'] = "Az erõforrás törölve";
$_lang['All links in events to this resource are deleted'] = "Az összes ehhez az erõforráshoz tartozó link törölve lett";
$_lang[' The resource is now in the list.'] = " Az erõforrás a listában van.";
$_lang[' The resource has been modified.'] = " Az erõforrás módosult.";
$_lang['The server sent an error message.'] = "A szerver hibaüzenetet küldött.";
$_lang['All Links are valid.'] = "Az összes link érvényes.";
$_lang['Please select at least one bookmark'] = "Kérem válasszon legalább egy könyvjelzõt";
$_lang['The bookmark is deleted'] = "A könyvjelzõ törölve";
$_lang['threads older than x days are deleted.'] = "A x napnál régebbi témák törölve lettek.";
$_lang['All chat scripts are removed'] = "Az összes csevegõ script el lett távolítva";
$_lang['or'] = "vagy";
$_lang['Timecard management'] = "Idõkártya menedzsment";
$_lang['View'] = "Megtekint";
$_lang['Choose group'] = "Válasszon csoportot";
$_lang['Group name'] = "Csoport neve";
$_lang['Short form'] = "Rövid forma";
$_lang['Category'] = "Kategória";
$_lang['Remark'] = "Megjegyzés";
$_lang['Group management'] = "Csoport menedzsment";
$_lang['Please insert a name'] = "Kérem adjon meg egy nevet";
$_lang['Name or short form already exists'] = "A név vagy a rövid forma már létezik";
$_lang['Automatic assign to group:'] = "Automatikus hozzárendelés ehhez a csoporthoz:";
$_lang['Automatic assign to user:'] = "Automatikus hozzárendelés ehhez a felhasználóhoz:";
$_lang['Help Desk Category Management'] = "Helpdesk kategória menedzsment";
$_lang['Category deleted'] = "Kategória törlve";
$_lang['The category has been created'] = "A kategória létrejött";
$_lang['The category has been modified'] = "A kategória módosult";
$_lang['Member of following groups'] = "Tagja a következõ csoportoknak";
$_lang['Primary group is not in group list'] = "Az elsõdleges csoport nincs a csoportlistában";
$_lang['Login name'] = "Login név";
$_lang['You cannot delete the default group'] = "Nem törölheti az alapértelmezett csoportot";
$_lang['Delete group and merge contents with group'] = "Törli a csoportot és összefûzi a csoport tartalmát egy másik csoporttal";
$_lang['Please choose an element'] = "Kérem válasszon egy elemet";
$_lang['Group created'] = "A csoport létrejött";
$_lang['File management'] = "Fájl menedzsment";
$_lang['Orphan files'] = "Árva fájlok";
$_lang['Deletion of super admin root not possible'] = "A szuperfelhasználó nem törölhetõ";
$_lang['ldap name'] = "ldap név";
$_lang['mobile // mobile phone'] = "mobiltelefon";
$_lang['Normal user'] = "Normál felhasználó";
$_lang['User w/Chief Rights'] = "Felhasználó fõnök jogokkal";
$_lang['Administrator'] = "Adminisztrátor";
$_lang['Logging'] = "Logging";
$_lang['Logout'] = "Logout";
$_lang['posting (and all comments) with an ID'] = "posting (and all comments) with an ID";
$_lang['Role deleted, assignment to users for this role removed'] = "Role deleted, assignment to users for this role removed";
$_lang['The role has been created'] = "The role has been created";
$_lang['The role has been modified'] = "The role has been modified";
$_lang['Access rights'] = "Access rights";
$_lang['Usergroup'] = "Gruppe";
$_lang['logged in as'] = "Angemeldet als";

//chat.php
$_lang['Quit chat']= "Quit chat";

//contacts.php
$_lang['Contact Manager'] = "Kapcsolatkezelõ";
$_lang['New contact'] = "Új kapcsolat";
$_lang['Group members'] = "Csoport tagjai";
$_lang['External contacts'] = "Külsõ kapcsolatok";
$_lang['&nbsp;New&nbsp;'] = "Új létrehozása";
$_lang['Import'] = "Importálás";
$_lang['The new contact has been added'] = "Az új kapcsolat hozzáadva";
$_lang['The date of the contact was modified'] = "A kapcsolat dátuma módosítva";
$_lang['The contact has been deleted'] = "A kapcsolat törölve";
$_lang['Open to all'] = "Minden felhasználó láthatja";
$_lang['Picture'] = "Kép";
$_lang['Please select a vcard (*.vcf)'] = "Kérem válasszon egy névjegyet (*.vcf)";
$_lang['create vcard'] = "névjegy létrehozása";
$_lang['import address book'] = "címjegyzék importálás";
$_lang['Please select a file (*.csv)'] = "Kérem válasszon egy fájlt (*.csv)";
$_lang['Howto: Open your outlook express address book and select file/export/other book<br>Then give the file a name, select all fields in the next dialog and finish'] = "Hogyan: Nyissa meg az ön Outlook Express-ének címjegyzékét és válassza a 'fájl'/'export'/'másik könyvet'<br>
Ezután adja meg a fájlnevet, válassza ki az összes mezõt a következõ dialógusban és 'kész'";
$_lang['Open outlook at file/export/export in file,<br>choose comma separated values (Win), then select contacts in the next form,<br>give the export file a name and finish.'] = "Nyissa meg az outlook-ot a 'fále/export/export fájlba pontnál',<br>
válassza a 'vesszõvel elválaszott értékeket (Win)', azután válassza a
'kapcsolatokat' a következõ formában,<br> adja meg az export fájl nevét és kész.";
$_lang['Please choose an export file (*.csv)'] = "Kérem válasszon egy export fájl nevet (*.csv)";
$_lang['Please export your address book into a comma separated value file (.csv), and either<br>1) apply an import pattern OR<br>2) modify the columns of the table with a spread sheet to this format<br>(Delete colums in you file that are not listed here and create empty colums for fields that do not exist in your file):'] = "Please export your address book into a comma separated value file (.csv), and either<br>
1) apply an import pattern OR<br>
2) modify the columns of the table with a spread sheet to this format<br>
(Delete colums in you file that are not listed here and create empty colums for fields that do not exist in your file):";
$_lang['Please insert at least the family name'] = "Kérem adja meg legalábba a családnevet";
$_lang['Record import failed because of wrong field count'] = "A rekord beolvasás elbukott a rossz mezõszám miatt";
$_lang['Import to approve'] = "A jóváhagyandó import";
$_lang['Import list'] = "Import lista";
$_lang['The list has been imported.'] = "A lista importálva.";
$_lang['The list has been rejected.'] = "A lista elutatítva.";
$_lang['Profiles'] = "Profilok";
$_lang['Parent object'] = "Szülõobjektum";
$_lang['Check for duplicates during import'] = "Az importálás alatt a kettõs tételek ellenõrzése";
$_lang['Fields to match'] = "Illeszkedõ mezõk";
$_lang['Action for duplicates'] = "Kettõs bejegyzések esetén";
$_lang['Discard duplicates'] = "Elutasítás";
$_lang['Dispose as child'] = "Elhelyezés, mint gyermek objektum";
$_lang['Store as profile'] = "Tárolás profilként";    
$_lang['Apply import pattern'] = "Importálási minta alkalmazása";
$_lang['Import pattern'] = "Importálási minta";
$_lang['For modification or creation<br>upload an example csv file'] = "Import fájl feltöltése (.csv)"; 
$_lang['Skip field'] = "Mezõ átugrása";
$_lang['Field separator'] = "Mezõhatároló";
$_lang['Contact selector'] = "Contact selector";
$_lang['Use doublet'] = "Use doublet";
$_lang['Doublets'] = "Doublets";

// filemanager.php
$_lang['Please select a file'] = "Kérem válasszon egy fájlnevet";
$_lang['A file with this name already exists!'] = "Ilyen nevû fájlnév már létezik!";
$_lang['Name'] = "Név";
$_lang['Comment'] = "Megjegyzés";
$_lang['Date'] = "Dátum";
$_lang['Upload'] = "Feltöltés";
$_lang['Filename and path'] = "Fájlnév és elérési út";
$_lang['Delete file'] = "Fájlt törlése";
$_lang['Overwrite'] = "Felülírás";
$_lang['Access'] = "Hozzáférés";
$_lang['Me'] = "én";
$_lang['Group'] = "csoport";
$_lang['Some'] = "az alábbiak: ";
$_lang['As parent object'] = "a könyvtárral megegyezõen";
$_lang['All groups'] = "Minden csoport";
$_lang['You are not allowed to overwrite this file since somebody else uploaded it'] = "Nem jogosult felülírni ezt a fájlt, mert valaki más idõközben megváltoztatta";
$_lang['personal'] = "személyes";
$_lang['Link'] = "Hivatkozás";
$_lang['name and network path'] = "név és hálózati elérési út";
$_lang['with new values'] = "új értékekkel";
$_lang['All files in this directory will be removed! Continue?'] = "A könyvtár összes állománya törlõdik! Folytatja?";
$_lang['This name already exists'] = "Ez a név már létezik";
$_lang['Max. file size'] = "Max. fájlméret";
$_lang['links to'] = "hivatkozást a";
$_lang['objects'] = "objektumok";
$_lang['Action in same directory not possible'] = "Nem lehetséges azonos könyvtáron belüli mûvelet";
$_lang['Upload = replace file'] = "Feltöltés = fájlcsere";
$_lang['Insert password for crypted file'] = "Titkosítási jelszó";
$_lang['Crypt upload file with password'] = "Titkosítás";
$_lang['Repeat'] = "Jelszó mégegyszer";
$_lang['Passwords dont match!'] = "A jelszavak nem egyeznek meg!";
$_lang['Download of the password protected file '] = "Titkosított fájl letöltése ";
$_lang['notify all users with access'] = "Minden érintett felhasználó értesítése";
$_lang['Write access'] = "Írási jog";
$_lang['Version'] = "Változat";
$_lang['Version management'] = "Változatok követése";
$_lang['lock'] = "zárolás";
$_lang['unlock'] = "zárolás feloldása";
$_lang['locked by'] = "zárolja: ";
$_lang['Alternative Download'] = "Letöltés másként";
$_lang['Download'] = "Download";
$_lang['Select type'] = "Select type";
$_lang['Create directory'] = "Create directory";
$_lang['filesize (Byte)'] = "Filesize (Byte)";

// filter
$_lang['contains'] = 'tartalmazza';
$_lang['exact'] = 'pontosan';
$_lang['starts with'] = 'kezdõdik';
$_lang['ends with'] = 'végzõdik';
$_lang['>'] = '>';
$_lang['>='] = '>=';
$_lang['<'] = '<';
$_lang['<='] = '<=';
$_lang['does not contain'] = 'nem tartalmazza';
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
$_lang['Module Designer'] = "Modultervezõ";
$_lang['Module element'] = "Module elem"; 
$_lang['Module'] = "Modul";
$_lang['Active'] = "Aktív";
$_lang['Inactive'] = "Inaktív";
$_lang['Activate'] = "Aktiválás";
$_lang['Deactivate'] = "Inaktiválás"; 
$_lang['Create new element'] = "Új elem létrehozása";
$_lang['Modify element'] = "Elem módosítása";
$_lang['Field name in database'] = "Mezõ neve az adatbázisban";
$_lang['Use only normal characters and numbers, no special characters,spaces etc.'] = "Csak az angol ABC betûit és számokat használjon, a speciális jeleket, szóközöket kerülje!";
$_lang['Field name in form'] = "Mezõ neve az ûrlapon";
$_lang['(could be modified later)'] = "(késõbb nem módosítható)"; 
$_lang['Single Text line'] = "Egy sornyi szöveg";
$_lang['Textarea'] = "Szövegmezõ";
$_lang['Display'] = "Képernyõ";
$_lang['First insert'] = "Elõször beszúrás";
$_lang['Predefined selection'] = "Elõre definiált kijelölés";
$_lang['Select by db query'] = "Adatbázis lekérdezés";
$_lang['File'] = "Állomány";

$_lang['Email Address'] = "Elektronikus levélcím";
$_lang['url'] = "url";
$_lang['Checkbox'] = "Jelölõnégyzet";
$_lang['Multiple select'] = "Többszörös választás";
$_lang['Display value from db query'] = "Display value from db query";
$_lang['Time'] = "Time";
$_lang['Tooltip'] = "Eszköztipp"; 
$_lang['Appears as a tipp while moving the mouse over the field: Additional comments to the field or explanation if a regular expression is applied'] = "Ha az egérrel a mezõ fölé áll, tippet jelenít meg";
$_lang['Position'] = "Pozício";
$_lang['is current position, other free positions are:'] = "a jelenlegi pozíció, egyéb szabad pozíciók:"; 
$_lang['Regular Expression:'] = "Szabályos kifejezés:";
$_lang['Please enter a regular expression to check the input on this field'] = "Adjon meg szabályos kifejezést a mezõ ellenõrzéséhez";
$_lang['Default value'] = "Alapérték";
$_lang['Predefined value for creation of a record. Could be used in combination with a hidden field as well'] = "A mezõ elére megadott alapértéke. Rejtett mezõvel együtt is használható";
$_lang['Content for select Box'] = "Content for select Box";
$_lang['Used for fixed amount of values (separate with the pipe: | ) or for the sql statement, see element type'] = "Used for fixed amount of values (separate with the pipe: | ) or for the sql statement, see element type";
$_lang['Position in list view'] = "Pozíció a lista nézetben";
$_lang['Only insert a number > 0 if you want that this field appears in the list of this module'] = "Csak akkor adjon 0-nál nagyobb értéket, ha szeretné, hogy ez a mezõ megjelenjen a modul listájában";
$_lang['Alternative list view'] = "Alternatív lista nézet";
$_lang['Value appears in the alt tag of the blue button (mouse over) in the list view'] = "Value appears in the alt tag of the blue button (mouse over) in the list view";
$_lang['Filter element'] = "Szûrõ elem";
$_lang['Appears in the filter select box in the list view'] = "Lista nézet alatt a szûrõ kiválasztó dobozban jelenik meg";
$_lang['Element Type'] = "Elemtípus";
$_lang['Select the type of this form element'] = "Válassza ki az ûrlap típusát";
$_lang['Check the content of the previous field!'] = "Ellenõrizze az elõzõ mezõ tartalmát!";
$_lang['Span element over'] = "Ültesse az elemet a(z)";
$_lang['columns'] = "oszlopokra";
$_lang['rows'] = "sorokra";
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
$_lang['Welcome to the setup of PHProject!<br>'] = "A PHProject installáló programja üdvözli Önt!<br>";
$_lang['Please remark:<ul><li>A blank database must be available<li>Please ensure that the webserver is able to write the file config.inc.php'] = "Kérem jegyezze meg a következõket:<ul>
<li>Egy üres adatbázis kell, hogy rendelkezésre álljon
<li>Gyõzõdjön meg róla, hogy a webszerver képes írni a 'config.inc.php'<br> fájlt (e.g. 'chmod 777')";
$_lang['<li>If you encounter any errors during the installation, please look into the <a href=help/faq_install.html target=_blank>install faq</a>or visit the <a href=http://www.PHProjekt.com/forum.html target=_blank>Installation forum</a></i>'] = "<li>Ha bármilyen hibát tapasztal az installáció alatt kérem nézzen bele a <a href='help/faq_install.html' target=_blank>installációs GYIK-be</a>
vagy látogassa meg a <a href='http://www.PHProjekt.com/forum.html' target=_blank>Installációs fórumot</a></i>";
$_lang['Please fill in the fields below'] = "Kérem töltse ki az alábbi mezõket";
$_lang['(In few cases the script wont respond.<br>Cancel the script, close the browser and try it again).<br>'] = "(Néhány esetben a script nem fog reagálni.<br>
Szakítsa meg a scriptet, zárja be a böngészõt és próbálja újra).<br>";
$_lang['Type of database'] = "Az adatbázis típusa";
$_lang['Hostname'] = "Gazdanév";
$_lang['Username'] = "Felhasználónév";

$_lang['Name of the existing database'] = "A létezõ adatbázis neve";
$_lang['config.inc.php not found! Do you really want to update? Please read INSTALL ...'] = "config.inc.php nem található! Valóban frissíteni akar? Kérem olvassa el az INSTALL-t...";
$_lang['config.inc.php found! Maybe you prefer to update PHProject? Please read INSTALL ...'] = "config.inc.php megvan! Inkább szeretné frissíteni a PHProject-et? Kérem olvassa el az INSTALL-t ...";
$_lang['Please choose Installation,Update or Configure!'] = "Kérem válassza aze 'Installáció' vagy a 'Frissítés' pontokat! vissza ...";
$_lang['Sorry, I cannot connect to the database! <br>Please close all browser windows and restart the installation.'] = "Sajnálom, ez nem mûködik! <br>Kérem javítsa ki és indítsa újra az installálást.";
$_lang['Sorry, it does not work! <br> Please set DBDATE to Y4MD- or let phprojekt change this environment-variable (php.ini)!'] = "Sajnálom, ez nem mûködik! <br> Kérem állítsa a DBDATE változót 'Y4MD-'-re vagy hagyja hogy a phprojekt változtassa meg azt (php.ini)!";
$_lang['Seems that You have a valid database connection!'] = "Úgy tûnik, hogy van egy érvényes adatbázis kapcsolata!";
$_lang['Please select the modules you are going to use.<br> (You can disable them later in the config.inc.php)<br>'] = "Kérem válassza ki azokat a modulokat amiket használni akar.<br> (Kikapcsolhatja õket késõbb a config.inc.php fájlban)<br>";
$_lang['Install component: insert a 1, otherwise keep the field empty'] = "Összetevõ installálása: adjon meg egy '1'-est, vagy hagyja üresen a mezõt.";
$_lang['Group views'] = "Csoport nézetek";
$_lang['Todo lists'] = "Tennivalók listája";

$_lang['Voting system'] = "Szavazórendszer";


$_lang['Contact manager'] = "Kapcsolat menedzser";
$_lang['Name of userdefined field'] = "Name of userdefined field";
$_lang['Userdefined'] = "Userdefined";
$_lang['Profiles for contacts'] = "Profiles for contacts";
$_lang['send mail'] = " csak levelet";
$_lang[' only,<br> &nbsp; &nbsp; full mail client'] = " küld,<br> &nbsp; &nbsp; teljes levelezõ kliens";
$_lang['Mail'] = "Gyorslevél";



$_lang['1 to show appointment list in separate window,<br>&nbsp; &nbsp; 2 for an additional alert.'] = "'1', hogy különbözõ ablakokban lássa a találkozók listáját,<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'2' egy további emlékeztetõhöz.";
$_lang['Alarm'] = "Riasztás";
$_lang['max. minutes before the event'] = "max. percek száma az esemény elõtt";
$_lang['SMS/Mail reminder'] = "SMS/Mail reminder";
$_lang['Reminds via SMS/Email'] = "Reminds via SMS/Email";
$_lang['1= Create projects,<br>&nbsp; &nbsp; 2= assign worktime to projects only with timecard entry<br>&nbsp; &nbsp; 3= assign worktime to projects without timecard entry<br>&nbsp; &nbsp; (Selection 2 or 3 only with module timecard!)'] = "'1'= Projekteket hoz létre,<br>
&nbsp; &nbsp; '2'= csak az idõkártyákon szereplõ idõ rendelhetõ a projekthez<br>
&nbsp; &nbsp; '3'= az idõkártya bejegyzésen kívüli idõ is hozzárendelhetõ a projekthez<br> ";

$_lang['Name of the directory where the files will be stored<br>( no file management: empty field)'] = "A könyvtár neve, ahol a fájlok tárolva lesznek<br>( nincs fájl menedzsment: üres mezõ)";
$_lang['absolute path to this directory (no files = empty field)'] = "abszolut elérési út ehhez a könyvtárhoz (nincsenek fájlok = üres mezõ)";
$_lang['Time card'] = "Idõkátya";
$_lang['1 time card system,<br>&nbsp; &nbsp; 2 manual insert afterwards sends copy to the chief'] = "'1' idõkártya rendszer,<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '2' kézi beillesztés miután másolatot küldött a séfnek";
$_lang['Notes'] = "Megjegyzések";
$_lang['Password change'] = "Jelszóváltás";
$_lang['New passwords by the user - 0: none - 1: only random passwords - 2: choose own'] = "A felhasználó által adható új jelszavak - 0: nincs - 1: véletlen jelszavak - 2: sajátot választ";
$_lang['Encrypt passwords'] = "Jelszavak titkosítása";
$_lang['Login via '] = "Belép ";
$_lang['Extra page for login via SSL'] = "Extra lap az SSL-en keresztüli bejelentkezéshez";
$_lang['Groups'] = "Csoport";
$_lang['User and module functions are assigned to groups<br>&nbsp; &nbsp; (recommended for user numbers > 40)'] = "Felhasználói és modul funkciók csoportokhoz vannak rendelve<br>
&nbsp;&nbsp;&nbsp;&nbsp;(javasolt 40 feletti felhasználószám esetén)";
$_lang['User and module functions are assigned to groups'] = "Felhasználói és modul funkciók csoportokhoz vannak rendelve";
$_lang['Help desk'] = "Kérés Követés (RT)";
$_lang['Help Desk Manager / Trouble Ticket System'] = "Help Desk Menedzser / Trouble Ticket Rendszer";
$_lang['RT Option: Customer can set a due date'] = "RT Opció: Az ügyfél beállíthatja a határidõt";
$_lang['RT Option: Customer Authentification'] = "RT Opció: Ügyfél azonosítás";
$_lang['0: open to all, email-address is sufficient<br>1: registered contact enter his family name<br>2: his email'] = "0: nyitva mindenkinek, email-cím elégséges, 1: ügyfél rajta kell, hogy legyen a kapcsolat listán és meg kell adnia a vezetéknevét";
$_lang['RT Option: Assigning request'] = "RT Opció: Kérések hozzárendelése";
$_lang['0: by everybody, 1: only by persons with status chief'] = "0: mindenki által, 1: csak a 'séf' státuszú személyek által";
$_lang['Email Address of the support'] = "A support email címe";
$_lang['Scramble filenames'] = "Összekeveri a fájlneveket";
$_lang['creates scrambled filenames on the server<br>assigns previous name at download'] = "összekevert fájlneveket hoz létre a szerveren<br>
az eredeti nevet hozzárendeli letöltéskor";

$_lang['0: last name, 1: short name, 2: login name'] = "0: keresztnév, 1: rövidnév, 2: login név";
$_lang['Prefix for table names in db'] = "Prefix for table names in db";
$_lang['Alert: Cannot create file config.inc.php!<br>Installation directory needs rwx access for your server and rx access to all others.'] = "Figyelem: Nem hozható létre a 'config.inc.php' fájl!<br>
Az installációs könyvtárnak rwx engedélyekre van szüksége a szerveren és rx engedélyekre minden egyébre.";
$_lang['Location of the database'] = "Az adatbázis helye";
$_lang['Type of database system'] = "Az adatbázis típusa";
$_lang['Username for the access'] = "Felhasználónév az adatbázishoz";
$_lang['Password for the access'] = "Jelszó az adatbázishoz";
$_lang['Name of the database'] = "Az adatbázis neve";
$_lang['Prefix for database table names'] = "Prefix for database table names";
$_lang['First background color'] = "Elsõ háttérszín";
$_lang['Second background color'] = "Második háttérszín";
$_lang['Third background color'] = "Harmadik háttérszín"; 
$_lang['Color to mark rows'] = "Color to mark rows";
$_lang['Color to highlight rows'] = "Color to highlight rows";
$_lang['Event color in the tables'] = "Eseményszínek a táblákban";
$_lang['company icon yes = insert name of image'] = "céges ikon igen = adja meg a kép nevét";
$_lang['URL to the homepage of the company'] = "URL a cég honlapjára";
$_lang['no = leave empty'] = "nincs = hagyja üresen";
$_lang['First hour of the day:'] = "A nap elsõ órája:";
$_lang['Last hour of the day:'] = "A nap utolsó órája:";
$_lang['An error ocurred while creating table: '] = "Hiba a következõ tábla létrehozása közben: ";
$_lang['Table dateien (for file-handling) created'] = "A 'dateien' tábla (fájlkezelésre) létrejött";
$_lang['File management no = leave empty'] = "Fájl menedzsment nincs = hagyja üresen";
$_lang['yes = insert full path'] = "igen = adja meg a teljes elérési utat";
$_lang['and the relative path to the PHProjekt directory'] = "és járulékosan a gyökér könyvtár elérési útját";
$_lang['Table profile (for user-profiles) created'] = "A 'profile' könyvtár (a felhasználói profilok részére) létrejött";
$_lang['User Profiles yes = 1, no = 0'] = "Profilok igen = 1, nem = 0";
$_lang['Table todo (for todo-lists) created'] = "A 'todo' tábla (a tennivalók listájához) létrejött";
$_lang['Todo-Lists yes = 1, no = 0'] = "Tennivalók listája igen = 1, nem = 0";
$_lang['Table forum (for discssions etc.) created'] = "A 'forum' tábla (a vitafórumokhoz stb.) létrejött";
$_lang['Forum yes = 1, no = 0'] = "Fórum igen = 1, nem = 0";
$_lang['Table votum (for polls) created'] = "A 'votum' tábla (a szavazásokhoz) létrejött";
$_lang['Voting system yes = 1, no = 0'] = "Szavazórendszer igen = 1, nem = 0";
$_lang['Table lesezeichen (for bookmarks) created'] = "A 'lesezeichen' tábla (a könyvjelzõknek) létrejött";
$_lang['Bookmarks yes = 1, no = 0'] = "Könyvjelzõk igen = 1, nem = 0";
$_lang['Table ressourcen (for management of additional ressources) created'] = "A 'ressourcen'tábla (a járulékos erõforrások menedzseléséhez) létrejött";
$_lang['Resources yes = 1, no = 0'] = "Erõforrások igen = 1, nem = 0";
$_lang['Table projekte (for project management) created'] = "A 'projekte' tábla (a projekt menedzsmenthez) létrejött";
$_lang['Table contacts (for external contacts) created'] = "A contacts tábla (külsõ kapcsolatok kezeléséhez) létrejött";
$_lang['Table notes (for notes) created'] = "A notes tábla (a megjegyzésekhez) létrejött";
$_lang['Table timecard (for time sheet system) created'] = "A timecard tábla (az idõkezelõ rendszerhez) létrejött";
$_lang['Table groups (for group management) created'] = "A groups tábla (a csoport menedzsmenthez) létrejött";
$_lang['Table timeproj (assigning work time to projects) created'] = "A timeproj tábla (munkaidõ hozzárendelés projektekhez) létrejött";
$_lang['Table rts and rts_cat (for the help desk) created'] = "Az rts és rts_cat táblák (a kéréskezelõ rendszerhez) létrejöttek";
$_lang['Table mail_account, mail_attach, mail_client und mail_rules (for the mail reader) created'] = "A mail_account, mail_attach, mail_client és mail_rules táblák (a levél olvasóhoz) létrejöttek";
$_lang['Table logs (for user login/-out tracking) created'] = "Table logs (for user login/-out tracking) created";
$_lang['Tables contacts_profiles und contacts_prof_rel created'] = "Tables contacts_profiles und contacts_prof_rel created";
$_lang['Project management yes = 1, no = 0'] = "Projekt menedzsment igen = 1, nem = 0";
$_lang['additionally assign resources to events'] = "járulékos erõforrások hozzárendelése eseményekhez";
$_lang['Address book  = 1, nein = 0'] = "Címjegyzék igen = 1, nem = 0";
$_lang['Mail no = 0, only send = 1, send and receive = 2'] = "Gyorslevél igen = 1, nem = 0";
$_lang['Chat yes = 1, no = 0'] = "Chat yes = 1, no = 0";
$_lang['Name format in chat list'] = "Name format in chat list";
$_lang['0: last name, 1: first name, 2: first name, last name,<br> &nbsp; &nbsp; 3: last name, first name'] = "0: last name, 1: first name, 2: first name, last name,<br> &nbsp; &nbsp; 3: last name, first name";
$_lang['Timestamp for chat messages'] = "Timestamp for chat messages";
$_lang['users (for authentification and address management)'] = "'users' tábla (az azonosításhoz és címkezeléshez)";
$_lang['Table termine (for events) created'] = "A 'termine' tábla (az eseményekhez) létrejött";
$_lang['The following users have been inserted successfully in the table user:<br>root - (superuser with all administrative privileges)<br>test - (chief user with restricted access)'] = "A következõ felhasználók sikeresen bekerültek a következõ táblába 'user':<br>
'root' - (szuperfelhasználó az összes adminisztációs joggal)<br>
'test' - (séf felhasználó szabályozott hozzáféréssel";
$_lang['The group default has been created'] = "A 'default' csoport sikeresen létrejött";
$_lang['Please do not change anything below this line!'] = "Kérem semmit ne változtasson meg ezalatt a vonal alatt!";
$_lang['Database error'] = "Adatbázis hiba";
$_lang['Finished'] = "Kész";
$_lang['There were errors, please have a look at the messages above'] = "Hibák fordultak elõ, kérem vessen egy pillantást az alábbi üzenetre";
$_lang['All required tables are installed and <br>the configuration file config.inc.php is rewritten<br>It would be a good idea to makea backup of this file.<br>'] = "Az összes szükséges tábla sikeresen installálva lett és <br>
a 'config.inc.php' konfigurációs fájl újra lett írva<br>
Jó ötlet, ha csinál egy másolatot errõl a fájlról.<br>
Most zárja be az összes böngészõablakot.<br>";
$_lang['The administrator root has the password root. Please change his password here:'] = "Az adminisztrátor 'root' jelszava 'root' lesz. Kérem cserélje le ezt a jelszót.<br>";
$_lang['The user test is now member of the group default.<br>Now you can create new groups and add new users to the group'] = "A 'test' felhasználó a 'default' csoport tagja lett.<br>
Most létre tud hozni új csoportokat és hozzá tud adni új felhasználókat a csoportokhoz";
$_lang['To use PHProject with your Browser go to <b>index.php</b><br>Please test your configuration, especially the modules Mail and Files.'] = "A PHProject használatához a böngészõjével menjen az <b>index.php</b>-ra<br>
Kérem, tesztelje a konfigurációt különös tekintettel a 'Gyorslevél' és 'Fájlok' modulokra.";

$_lang['Alarm x minutes before the event'] = "Riasztás x perccel az esemény elõtt";
$_lang['Additional Alarmbox'] = "Járulékos Riasztódoboz";
$_lang['Mail to the chief'] = "Levél a séfnek";
$_lang['Out/Back counts as: 1: Pause - 0: Workingtime'] = "Ki/Vissza számol, mint: 1: Szünet - 0: Munkaidõ";
$_lang['Passwords will now be encrypted ...'] = "A jelszavak most már titkosítva vannak";
$_lang['Filenames will now be crypted ...'] = "A fájlnevek mostantól titkosítva lesznek ...";
$_lang['Do you want to backup your database right now? (And zip it together with the config.inc.php ...)<br>Of course I will wait!'] = "Szeretne most biztonsági másolatot készíteni az adatbázisról?
(És zip-elje õket össze a config.inc.php -vel...)<br>Persze, hogy várok!";
$_lang['Next'] = "Következõ";
$_lang['Notification on new event in others calendar'] = "A mások naptárában létrejövõ új eseményekrõl értesítés";
$_lang['Path to sendfax'] = "A sendfax elérési útja";
$_lang['no fax option: leave blank'] = "nincs fax opció: hagyja üresen";
$_lang['Please read the FAQ about the installation with postgres'] = "Kérem olvassal el a GYIK a postgres installációról";
$_lang['Length of short names<br> (Number of letters: 3-6)'] = "A rövidnevek hossza<br> (Betûk száma: 3-6)";
$_lang['If you want to install PHProjekt manually, you find<a href=http://www.phprojekt.com/files/sql_dump.tar.gz target=_blank>here</a> a mysql dump and a default config.inc.php'] = "Ha kézzel installálja a PHProjekt-et, megtalálja a
<a href='http://www.phprojekt.com/files/sql_dump.tar.gz' target=_blank>címen</a> egy mysql dump és egy alapértelmezett config.inc.php fájl is megtalálható";
$_lang['The server needs the privilege to write to the directories'] = "A szervernek szüksége van 'írási'jogosultságra ehhez a könyvtárhoz";
$_lang['Header groupviews'] = "A csoport nézetek fejléce";
$_lang['name, F.'] = "név, F.";
$_lang['shortname'] = "rövidnév";
$_lang['loginname'] = "loginnév";
$_lang['Please create the file directory'] = "Kérem hozza létre a fájl könyvtárat";
$_lang['default mode for forum tree: 1 - open, 0 - closed'] = "az alapértelmezett mód a fórum fához: 1 - nyitva, 0 - zárva";
$_lang['Currency symbol'] = "Valuta szimbólum";
$_lang['current'] = "aktuális";
$_lang['Default size of form elements'] = "Default size of form elements";
$_lang['use LDAP'] = "LDAP-t használ";
$_lang['Allow parallel events'] = "Megengedi a párhuzamos eseményeket";
$_lang['Timezone difference [h] Server - user'] = "Idõzóna eltérés [h] Szerver - felhasználó";
$_lang['Timezone'] = "Idõzóna";
$_lang['max. hits displayed in search module'] = "max. megjelenített találatok száma a keresési modulban";
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
$_lang['Resource List'] = "Erõforráslista";
$_lang['Event List'] = "Eseménylista";
$_lang['Calendar Views'] = "Csoport nézet";

$_lang['Personnel'] = "Személyes";
$_lang['Create new event'] = "Események létrehozása és törlése";
$_lang['Day'] = "Nap";

$_lang['Until'] = "Vége";

$_lang['Note'] = "Feljegyzés";
$_lang['Project'] = "Projekt";
$_lang['Res'] = "Er.f.";
$_lang['Once'] = "Egyszer";
$_lang['Daily'] = "Naponta";
$_lang['Weekly'] = "Hetente";
$_lang['Monthly'] = "Havonta";
$_lang['Yearly'] = "Évente";

$_lang['Create'] = "Létrehozás";

$_lang['Begin'] = "Kezdete";
$_lang['Out of office'] = "Irodán kívül";
$_lang['Back in office'] = "Vissza az irodába";
$_lang['End'] = "Vége";
$_lang['@work'] = "munkában";
$_lang['We'] = "Hét";
$_lang['group events'] = "csoportos események";
$_lang['or profile'] = "vagy profil";
$_lang['All Day Event'] = "esemény egész napra";
$_lang['time-axis:'] = "idõtengely:";
$_lang['vertical'] = "vízszintes";
$_lang['horizontal'] = "függõleges";
$_lang['Horz. Narrow'] = "függ. nyíl";
$_lang['-interval:'] = "-szünet:";
$_lang['Self'] = "Saját";

$_lang['...write'] = "Kiterjesztett mód";

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
$_lang['Please call login.php!'] = "Kérem futtassa a login.php-t!";

// m1.php
$_lang['There are other events!<br>the critical appointment is: '] = "Több esemény is van!<br>a kritikus találkozó: ";
$_lang['Sorry, this resource is already occupied: '] = "Sajnálom ez az erõforrás már foglalt: ";
$_lang[' This event does not exist.<br> <br> Please check the date and time. '] = " Ez az esemény nem létezik.<br> <br> Kérem ellenõrizze a dátumot és az idõt. ";
$_lang['Please check your date and time format! '] = "Kérem ellenõrizze a dátum és idõ formátumát! ";
$_lang['Please check the date!'] = "Kérem ellenõrizze a dátumot!";
$_lang['Please check the start time! '] = "Kérem ellenõrizze a kezdési idõt! ";
$_lang['Please check the end time! '] = "Kérem ellenõrizze a befejezési idõt! ";
$_lang['Please give a text or note!'] = "Kérem adjon meg egy szöveget vagy megjegyzést!";
$_lang['Please check start and end time! '] = "Kérem ellenõrizze a kezdõ és a befejezési idõt! ";
$_lang['Please check the format of the end date! '] = "Kérem ellenõrizze a befejezési dátum formátumát! ";
$_lang['Please check the end date! '] = "Kérem ellenõrizze a befejezési dátumot! ";





$_lang['Resource'] = "Erõforrás";
$_lang['User'] = "Felhasználó";

$_lang['delete event'] = "esemény törlése";
$_lang['Address book'] = "Címjegyzék";


$_lang['Short Form'] = "Rövid név";

$_lang['Phone'] = "Telefon";
$_lang['Fax'] = "Fax";



$_lang['Bookmark'] = "Könyvjelzõ";
$_lang['Description'] = "Leírás";

$_lang['Entire List'] = "Teljes lista";

$_lang['New event'] = "Új esemény";
$_lang['Created by'] = "Létrehozta";
$_lang['Red button -> delete a day event'] = "Piros gomb -> egynapi esemény törlése";
$_lang['multiple events'] = "Ismétlõdõ események";
$_lang['Year view'] = "Nézet években";
$_lang['calendar week'] = "Naptári hét";

//m2.php
$_lang['Create &amp; Delete Events'] = "Események létrehozása és törlése";
$_lang['normal'] = "alap";
$_lang['private'] = "sajátom";
$_lang['public'] = "nyilvános";
$_lang['Visibility'] = "Láthatóság";

//mail module
$_lang['Please select at least one (valid) address.'] = "Kérem válasszon legalább egy (érvényes) címet.";
$_lang['Your mail has been sent successfully'] = "A levél postázva";
$_lang['Attachment'] = "Csatolás";
$_lang['Send single mails'] = "Küldés egyenként (rejtett címzettek)";
$_lang['Does not exist'] = "Nem létezik";
$_lang['Additional number'] = "Járulékos szám";
$_lang['has been canceled'] = "visszavonva";

$_lang['marked objects'] = "Kijelöltek törlése";
$_lang['Additional address'] = "További címek";
$_lang['in mails'] = "a levelekben";
$_lang['Mail account'] = "";
$_lang['Body'] = "Levéltözs";
$_lang['Sender'] = "Feladó";

$_lang['Receiver'] = "Címzett";
$_lang['Reply'] = "Válasz";
$_lang['Forward'] = "Továbbítás";
$_lang['Access error for mailbox'] = "HIBA: A postafiók nem elérhetõ !";
$_lang['Receive'] = "Levelek letöltése...";
$_lang['Write'] = "Új levél szerkesztése... ";
$_lang['Accounts'] = "Fiókok";
$_lang['Rules'] = "Szabályok";
$_lang['host name'] = "Kiszolgáló gép neve";
$_lang['Type'] = "Típusa";
$_lang['misses'] = "hiányzik";
$_lang['has been created'] = "létrejött";
$_lang['has been changed'] = "megváltozott";
$_lang['is in field'] = "a mezõben van";
$_lang['and leave on server'] = " ";
$_lang['name of the rule'] = "a szabály neve";
$_lang['part of the word'] = "a szó része";
$_lang['in'] = "az";
$_lang['sent mails'] = "elküldött levelekben";
$_lang['Send date'] = "Küldés dátuma";
$_lang['Received'] = "Érkezett";
$_lang['to'] = "Címzett";
$_lang['imcoming Mails'] = "bejövõ Levelek";
$_lang['sent Mails'] = "elküldött Levelek";
$_lang['Contact Profile'] = "Kapcsolat profil";
$_lang['unread'] = "olvasatlan";
$_lang['view mail list'] = "Új levelek listája..";
$_lang['insert db field (only for contacts)'] = "Adatmezõ beszúrása a kapcsolatokból";
$_lang['Signature'] = "Aláírás";

$_lang['SMS'] = "SMS";
$_lang['Single account query'] = "Egyedi postafiók lekérdezése";
$_lang['Notice of receipt'] = "Tértivevény kérése";
$_lang['Assign to project'] = "Levelek fogadása a többi postafiókkal együtt";
$_lang['Assign to contact'] = "Küldés"; 
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
$_lang['Mail note to'] = "Megjegyzés elküldése";
$_lang['added'] = "hozzáadva";
$_lang['changed'] = "megváltoztatva";

// o.php
$_lang['Calendar'] = "Naptár";
$_lang['Contacts'] = "Kapcsolatok";


$_lang['Files'] = "Fájlok";



$_lang['Options'] = "Beállítások";
$_lang['Timecard'] = "Idõkártya";

$_lang['Helpdesk'] = "helpdesk";

$_lang['Info'] = "Infó";
$_lang['Todo'] = "Teendõk";
$_lang['News'] = "Újdonságok";
$_lang['Other'] = "Egyebek";
$_lang['Settings'] = "Beállítások";
$_lang['Summary'] = "Összesítõ";

// options.php
$_lang['Description:'] = "Leírás:";
$_lang['Comment:'] = "Megjegyzés:";
$_lang['Insert a valid Internet address! '] = "Adjon meg egy érvényes Internet címet! ";
$_lang['Please specify a description!'] = "Kérem adjon meg egy leírást!";
$_lang['This address already exists with a different description'] = "Ez a cím már létezik egy másik leírással";
$_lang[' already exists. '] = " már létezik. ";
$_lang['is taken to the bookmark list.'] = "a címlistáról véve.";
$_lang[' is changed.'] = " megváltozott.";
$_lang[' is deleted.'] = " törölve lett.";
$_lang['Please specify a description! '] = "Kérem adjon meg egy leírást! ";
$_lang['Please select at least one name! '] = "Kérem válasszon legalább egy nevet! ";
$_lang[' is created as a profile.<br>'] = " mint profil jött létre.<br> Miután a naptár szekció frissítve lesz a profil azután lesz aktív.";
$_lang['is changed.<br>'] = "megváltozott.<br> Miután a naptár szekció frissítve lesz a profil azután lesz aktív.";
$_lang['The profile has been deleted.'] = "A profil törlõdött.";
$_lang['Please specify the question for the poll! '] = "Kérem adjon meg egy kérdést a szavazáshoz! ";
$_lang['You should give at least one answer! '] = "Legalább egy válaszlehetõséget meg kell adnia! ";
$_lang['Your call for votes is now active. '] = "Az ön szavazógépe most már aktív. ";
$_lang['<h2>Bookmarks</h2>In this section you can create, modify or delete bookmarks:'] = "<h2>Könyvjelzõk</h2>Ebben a szekcióban létrehozhat, módosíthat, törölhet könyvjelzõket:";
$_lang['Create'] = "Létrehoz";


$_lang['<h2>Profiles</h2>In this section you can create, modify or delete profiles:'] = "<h2>Profilok</h2>Ebben a szekcióban létrehozhat, módosíthat, törölhet profilokat:";
$_lang['<h2>Voting Formula</h2>'] = "<h2>Szavazó Képlet</h2>";
$_lang['In this section you can create a call for votes.'] = "Ebben a szekcióban létrehozhat egy szavazógépet.";
$_lang['Question:'] = "Kérdés:";
$_lang['just one <b>Alternative</b> or'] = "csak egy alternatíva vagy";
$_lang['several to choose?'] = "több választási lehetõség?";

$_lang['Participants:'] = "Résztvevõk:";

$_lang['<h3>Password Change</h3> In this section you can choose a new random generated password.'] = "<h3>Jelszóváltás</h3>Ebben a szekcióban választhat magának egy új véletlenül generált jelszót.";
$_lang['Old Password'] = "Régi jelszó";
$_lang['Generate a new password'] = "Új jelszó generálása";
$_lang['Save password'] = "Elmenti a jelszót";
$_lang['Your new password has been stored'] = "Az ön új jelszava elmentésre került";
$_lang['Wrong password'] = "Hibás jelszó";
$_lang['Delete poll'] = "Törli a szavazatot";
$_lang['<h4>Delete forum threads</h4> Here you can delete your own threads<br>Only threads without a comment will appear.'] = "<h4>Törli a fórum témákat</h4> Itt tudja törölni a saját témáit<br>
Csak hozzászólások nélküli témák jelennek meg.";

$_lang['Old password'] = "Régi jelszó";
$_lang['New Password'] = "Új jelszó";
$_lang['Retype new password'] = "Adja meg újra az új jelszót";
$_lang['The new password must have 5 letters at least'] = "Az új jelszó legalább 5 hosszú kell, hogy legyen";
$_lang['You didnt repeat the new password correctly'] = "Nem ismételte meg az új jelszót helyesen";

$_lang['Show bookings'] = "Mutatja a foglalásokat";
$_lang['Valid characters'] = "Érvényes karakterek";
$_lang['Suggestion'] = "Javaslat";
$_lang['Put the word AND between several phrases'] = "Put the word AND between several phrases"; // translators: please leave the word AND as it is
$_lang['Write access for calendar'] = "Write access for calendar";
$_lang['Write access for other users to your calendar'] = "Write access for other users to your calendar";
$_lang['User with chief status still have write access'] = "User with chief status still have write access";

// projects
$_lang['Project Listing'] = "Projektek Listája";
$_lang['Project Name'] = "Projekt neve";


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
$_lang['Participants'] = "Résztvevõk";
$_lang['Priority'] = "Prioritás";
$_lang['Status'] = "Állapot";
$_lang['Last status change'] = "Legutóbbi <br>változás";
$_lang['Leader'] = "Vezetõ";
$_lang['Statistics'] = "Statisztika";
$_lang['My Statistic'] = "My Statistic";

$_lang['Person'] = "Személy";
$_lang['Hours'] = "óra";
$_lang['Project summary'] = "Projekt összegzés";
$_lang[' Choose a combination Project/Person'] = " Válasszon egy Projekt/Személy összerendelést";
$_lang['(multiple select with the Ctrl/Cmd-key)'] = "(többszörös kiválasztás a 'Ctrl'-billentyûvel)";

$_lang['Persons'] = "Személyek";
$_lang['Begin:'] = "Kezdete:";
$_lang['End:'] = "Vége:";
$_lang['All'] = "Összes";
$_lang['Work time booked on'] = "Bejegyzett munkaidõ";
$_lang['Sub-Project of'] = "Alprojekt";
$_lang['Aim'] = "Cél";
$_lang['Contact'] = "Kapcsolat";
$_lang['Hourly rate'] = "Óradíj";
$_lang['Calculated budget'] = "Kalkulált költségvetés";
$_lang['New Sub-Project'] = "Új alprojekt";
$_lang['Booked To Date'] = "Bejegyezve mostanáig";
$_lang['Budget'] = "Költségvetés";
$_lang['Detailed list'] = "Részletes lista";
$_lang['Gantt'] = "Határidõ";
$_lang['offered'] = "javasolt";
$_lang['ordered'] = "megrendelt";
$_lang['Working'] = "munkában töltött";
$_lang['ended'] = "véget ért";
$_lang['stopped'] = "leállított";
$_lang['Re-Opened'] = "megnyitva újra";
$_lang['waiting'] = "várakozó";
$_lang['Only main projects'] = "Csak a fõ projektek";
$_lang['Only this project'] = "Only this project";
$_lang['Begin > End'] = "Begin > End";
$_lang['ISO-Format: yyyy-mm-dd'] = "A kívánt dátumformátum: éééé-hh-nn";
$_lang['The timespan of this project must be within the timespan of the parent project. Please adjust'] = "The timespan of this project must be within the timespan of the parent project. Please adjust";
$_lang['Please choose at least one person'] = "Please choose at least one person";
$_lang['Please choose at least one project'] = "Please choose at least one project";
$_lang['Dependency'] = "Dependency";
$_lang['Previous'] = "Previous";

$_lang['cannot start before the end of project'] = "cannot start before the end of project";
$_lang['cannot start before the start of project'] = "cannot start before the start of project";
$_lang['cannot end before the start of project'] = "cannot end before the start of project";
$_lang['cannot end before the end of project'] = "cannot end before the end of project";
$_lang['Warning, violation of dependency'] = "Attention, violation of dependency";
$_lang['Container'] = "Container";
$_lang['External project'] = "External project";
$_lang['Automatic scaling'] = "Automatic scaling";
$_lang['Legend'] = "Legend";
$_lang['No value'] = "No value";
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
$_lang['please check the status!'] = "kérem ellenõrizze az állapotot!";
$_lang['Todo List: '] = "Teendõk listája: ";
$_lang['New Remark: '] = "Új bejegyzés: ";
$_lang['Delete Remark '] = "Megjegyzést töröl ";
$_lang['Keyword Search'] = "Keresés kulcsszóra: ";
$_lang['Events'] = "Események";
$_lang['the forum'] = "a fórum";
$_lang['the files'] = "a fájlok";
$_lang['Addresses'] = "Címek";
$_lang['Extended'] = "Részletes nézet";
$_lang['all modules'] = "összes modul";
$_lang['Bookmarks:'] = "Könyvjelzõk:";
$_lang['List'] = "Lista";
$_lang['Projects:'] = "Projektek:";

$_lang['Deadline'] = "Határidõ";

$_lang['Polls:'] = "Szavazatok:";

$_lang['Poll created on the '] = "Szavazatok létrehozva a  ";


// reminder.php
$_lang['Starts in'] = "Indulás";
$_lang['minutes'] = "perc";
$_lang['No events yet today'] = "Ma még nincs esemény";
$_lang['New mail arrived'] = "Új levele érkezett !";

//ress.php

$_lang['List of Resources'] =  "Erõforrások listája";
$_lang['Name of Resource'] = "Erõforrás neve";
$_lang['Comments'] =  "Megjegyzések";


// roles
$_lang['Roles'] = "Szerepek";
$_lang['No access'] = "Nincs jogosultság";
$_lang['Read access'] = "Olvasási jog";

$_lang['Role'] = "Szerep";

// helpdesk - rts
$_lang['Request'] = "Kérés";

$_lang['pending requests'] = "függõ kérések";
$_lang['show queue'] = "sor megjelenítése";
$_lang['Search the knowledge database'] = "Keresés a tudásbázisban";
$_lang['Keyword'] = "Kulcsszó";
$_lang['show results'] = "Eredmények megjelenítése";
$_lang['request form'] = "kérés forma";
$_lang['Enter your keyword'] = "Adja meg a kulcsszót";
$_lang['Enter your email'] = "Adja meg az email-jét";
$_lang['Give your request a name'] = "Adjon nevet a kérésnek";
$_lang['Describe your request'] = "Irja le a kérést";

$_lang['Due date'] = "Határidõ";
$_lang['Days'] = "Napok";
$_lang['Sorry, you are not in the list'] = "Sajnálom, ön nincs a listán";
$_lang['Your request Nr. is'] = "Az ön kérésének a száma";
$_lang['Customer'] = "Ügyfél";


$_lang['Search'] = "Keresés";
$_lang['at'] = "az";
$_lang['all fields'] = "összes mezõben";


$_lang['Solution'] = "Megoldás";
$_lang['AND'] = "ÉS";

$_lang['pending'] = "függõ";
$_lang['stalled'] = "álló";
$_lang['moved'] = "mozgó";
$_lang['solved'] = "megoldott";
$_lang['Submit'] = "Rendben";
$_lang['Ass.'] = "Hren.";
$_lang['Pri.'] = "Pri.";
$_lang['access'] = "hozzáférés";
$_lang['Assigned'] = "Hozzárendelt";

$_lang['update'] = "frissít";
$_lang['remark'] = "bejegyez";
$_lang['solve'] = "megold";
$_lang['stall'] = "megállít";
$_lang['cancel'] = "töröl";
$_lang['Move to request'] = "Áthelyezi a kérést";
$_lang['Dear customer, please refer to the number given above by contacting us.Will will perform your request as soon as possible.'] = "Kedves ügyfelünk, kérem nézze meg az alábbi számot, hogy kapcsolatba lépjen velünk.
Foglalkozunk a kérésével amilyen gyorsan csak lehet.";
$_lang['Your request has been added into the request queue.<br>You will receive a confirmation email in some moments.'] = "Az ön kérése hozzá lett adva a kérések sorához.<br>
Meg fogja kapni a jóváhagyó levelet pár percen belül.";
$_lang['n/a'] = "n/a";
$_lang['internal'] = "belsõ";

$_lang['has reassigned the following request'] = "újrarendeli a következõ kérést";
$_lang['New request'] = "Új kérés";
$_lang['Assign work time'] = "Munkaidõt rendel hozzá";
$_lang['Assigned to:'] = "Hozzárendelve:";

$_lang['Your solution was mailed to the customer and taken into the database.'] = "A megoldása el lett küldve az ügyfélnek és bekerült az adatbázisba.";
$_lang['Answer to your request Nr.'] = "Válasz a kérdésére.";
$_lang['Fetch new request by mail'] = "Kigyûjti az új kéréseket levélben";
$_lang['Your request was solved by'] = "Az ön problémáját megoldotta";

$_lang['Your solution was mailed to the customer and taken into the database'] = "Az ön kérése/problémája e-mail formában továbbításra került Önhöz és bekerült az adatbázisunkba";
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
$_lang['The settings have been modified'] = "A beállítások módosítva";
$_lang['Skin'] = "Stílus";
$_lang['First module view on startup'] = "Bejelentkezéskor megjelenõ modul";
$_lang['none'] = "semmi";
$_lang['Check for mail'] = "Új levelek letöltése";
$_lang['Additional alert box'] = "További emlékeztetõ doboz";
$_lang['Horizontal screen resolution <br>(i.e. 1024, 800)'] = "Vízszintes képfelbontás <br>(pl. 1024, 800)";
$_lang['Chat Entry'] = "Csevegés mezõ";
$_lang['single line'] = "Egysoros";
$_lang['multi lines'] = "Többsoros";
$_lang['Chat Direction'] = "Csevegés iránya";
$_lang['Newest messages on top'] = "Legújabb üzenet felül";
$_lang['Newest messages at bottom'] = "Legújabb üzenet alul";
$_lang['File Downloads'] = "Állományok letöltése";

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
$_lang['Todays Events'] = "Mai események";
$_lang['New files'] = "Legújabb fájlok";
$_lang['New notes'] = "Legújabb feljegyzések";
$_lang['New Polls'] = "Legújabb szavazatok";
$_lang['Current projects'] = "Futó projektek";
$_lang['Help Desk Requests'] = "HelpDesk kérdések";
$_lang['Current todos'] = "Aktuális teendõk";
$_lang['New forum postings'] = "Legújabb fórum témák";
$_lang['New Mails'] = "Legújabb levelek.";

//timecard

$_lang['Theres an error in your time sheet: '] = "Hiba van az idõbeosztásában! Kérem nézze meg az idõkártyáját.";




$_lang['Consistency check'] = "Konzisztencia ellenõrzés";
$_lang['Please enter the end afterwards at the'] = "Kérem adja meg a végét a(z)";
$_lang['insert'] = "beilleszt";
$_lang['Enter records afterwards'] = "Adjon meg rekordokat ezután";
$_lang['Please fill in only emtpy records'] = "Kérem csak az üres rekordokat töltse ki";

$_lang['Insert a period, all records in this period will be assigned to this project'] = "Adjon meg egy idõszakot, az összes rekord ebben az idõszakban ehhez a projekthez lesz rendelve";
$_lang['There is no record on this day'] = "Nincs rekord ezen a napon!";
$_lang['This field is not empty. Please ask the administrator'] = "Ez a mezõ nem üres. Kérem kérje meg az adminisztrátort!";
$_lang['There is no open record with a begin time on this day!'] = "A megadott adatok rosszak! Kérem ellenõrizze õket.";
$_lang['Please close the open record on this day first!'] = "Kérem adja meg a kezdõ idõt";
$_lang['Please check the given time'] = "Kérem ellenõrizze a megadott idõt";
$_lang['Assigning projects'] = "Projektekhez való hozzárendelés";
$_lang['Select a day'] = "Válasszon egy napot";
$_lang['Copy to the boss'] = "Másolat a fõnöknek";
$_lang['Change in the timecard'] = "Változtassa meg az idõkártyán";
$_lang['Sum for'] = "Összegzés";

$_lang['Unassigned time'] = "Nem hozzárendelt idõ";
$_lang['delete record of this day'] = "törli ennek a napnak a rekordját";
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
$_lang['accepted'] = "elfogadva";
$_lang['rejected'] = "visszautasítva";
$_lang['own'] = "saját";
$_lang['progress'] = "feldolgozás";
$_lang['delegated to'] = "kiadva: ";
$_lang['Assigned from'] = "küldte: ";
$_lang['done'] = "kész";
$_lang['Not yet assigned'] = "Még nincs kiadva";
$_lang['Undertake'] = "Undertake";
$_lang['New todo'] = "Új teendõ"; 
$_lang['Notify recipient'] = "Notify recipient";

// votum.php
$_lang['results of the vote: '] = "a szavazás eredménye: ";
$_lang['Poll Question: '] = "a szavazás kérdése: ";
$_lang['several answers possible'] = "több válasz is lehetséges";
$_lang['Alternative '] = "Alternatív ";
$_lang['no vote: '] = "nincs szavazat: ";
$_lang['of'] = "a(z)";
$_lang['participants have voted in this poll'] = "a résztvevõ szavazott";
$_lang['Current Open Polls'] = "Nyitott szavazatok";
$_lang['Results of Polls'] = "Eredmények összegzése";
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