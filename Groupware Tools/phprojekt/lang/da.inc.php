<?php  // da.inc.php, danish Version
// by Andreas Ott <anott@scriptorium.dk>
// 2003-04-16 updated by Chris Bagge

$chars = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
$name_month = array("", "jan", "feb", "mar", "apr", "maj", "jun", "jul", "aug", "sep", "okt", "nov", "dec");
$l_text31a = array("default", "15 min.", "30 min.", " 1 time", " 2 timer", " 4 timer", " 1 dag");
$l_text31b = array(0, 15, 30, 60, 120, 240, 1440);
$name_day = array("s&oslash;ndag", "mandag", "tirsdag", "onsdag", "torsdag", "fredag", "l&oslash;rdag");
$name_day2 = array("ma", "ti", "on", "to", "fr", "l&oslash;", "s&oslash;");

$_lang['No Entries Found']= "No Entries Found";
$_lang['No Todays Events']= "HNo Todays Events";
$_lang['No new forum postings']= "No new forum postings";
$_lang['in category']= "in category";
$_lang['Filtered']= "Filtered";
$_lang['Sorted by']= "Sorted by";
$_lang['go'] = "send";
$_lang['back'] = "tilbage";
$_lang['print'] = "udskriv";
$_lang['export'] = "eksporter";
$_lang['| (help)'] = "| (hj&aelig;lp/fremgangsm&aring;de)";
$_lang['Are you sure?'] = "Er du sikker?";
$_lang['items/page'] = "elementer/side";
$_lang['records'] = "elementer"; // elements
$_lang['previous page'] = "tilbage";
$_lang['next page'] = "n&aelig;ste";
$_lang['first page'] = "first page";
$_lang['last page'] = "last page";
$_lang['Move']  = "flyt";
$_lang['Copy'] = "kopier";
$_lang['Delete'] = "slet";
$_lang['Save'] = "gem";
$_lang['Directory'] = "mappe";
$_lang['Also Delete Contents'] = "slet ogs&aring; indhold";
$_lang['Sum'] = "sum";
$_lang['Filter'] = "filter";
$_lang['Please fill in the following field'] = "Venligst fyld f&oslash;lgende felt ud.";
$_lang['approve'] = "bekr&aelig;ft";
$_lang['undo'] = "annull&eacute;r";
$_lang['Please select!']="V&aelig;lg venligst!";
$_lang['New'] = "ny";
$_lang['Select all'] = "marker alt";
$_lang['Printable view'] = "udskriftsvenligt format";
$_lang['New record in module '] = "Nyt emne i modul ";
$_lang['Notify all group members'] = "Informer alle medlemmer af gruppen";
$_lang['Yes'] = "ja";
$_lang['No'] = "nej";
$_lang['Close window'] = "Close window";
$_lang['No Value'] = "No Value";
$_lang['Standard'] = "Standard";
$_lang['Create'] = "Anlegen";
$_lang['Modify'] = "Ändern";   
$_lang['today'] = "today";

// admin.php
$_lang['Password'] = "adgangskode";
$_lang['Login'] = "login";
$_lang['Administration section'] = "Administrations modul";
$_lang['Your password'] = "Indtast adgangskode for dette omr&aring;de";
$_lang['Sorry you are not allowed to enter. '] = "Du er ikke autoriseret for dette omr&aring;de! ";
$_lang['Help'] = "hj&aelig;lp";
$_lang['User management'] = "Ops&aelig;tning af brugerprofil";
$_lang['Create'] = "opret";
$_lang['Projects'] = "Ops&aelig;tning af projekter";
$_lang['Resources'] = "Ops&aelig;tning af ressourcer";
$_lang['Resources management'] = "Resources management";
$_lang['Bookmarks'] = "Ops&aelig;tning af bogm&aelig;rker/foretrukne";
$_lang['for invalid links'] = "for d&oslash;de links";
$_lang['Check'] = "tjek";
$_lang['delete Bookmark'] = "slet foretrukne";
$_lang['(multiple select with the Ctrl-key)'] = "(Tryk Ctrl-tast for at v&aelig;lge flere)";
$_lang['Forum'] = "Ops&aelig;tning af fora";
$_lang['Threads older than'] = "indl&aelig;g i fora, oprettet for mere end ";
$_lang[' days '] = " dage siden ";
$_lang['Chat'] = "chat";
$_lang['save script of current Chat'] = "gem chat-udskrift";
$_lang['Chat script'] = "chat-udskrift";
$_lang['New password'] = "ny adgangskode";
$_lang['(keep old password: leave empty)'] = "(tom for samme adgangskode)";
$_lang['Default Group<br> (must be selected below as well)'] = "standardgruppe<br> (v&aelig;lg fra ovenst&aring;ende)";
$_lang['Access rights'] = "rettighed";
$_lang['Zip code'] = "postnummer";
$_lang['Language'] = "sprog";
$_lang['schedule readable to others'] = "andre m&aring; se min kalender";
$_lang['schedule invisible to others'] = "andre m&aring; ikke se min kalender";
$_lang['schedule visible but not readable'] = "aftaler synlige, men ikke l&aelig;sbare";
$_lang['these fields have to be filled in.'] = "Disse felter skal udfyldes.";
$_lang['You have to fill in the following fields: family name, short name and password.'] = "Udfyld felterne efternavn, forkortelse og adgangskode.";
$_lang['This family name already exists! '] = "Dette efternavn er allerede oprettet. Venligst v&aelig;lg en anden. ";
$_lang['This short name already exists!'] = "Denne forkortelse er allerede oprettet. Venligst v&aelig;lg en anden. ";
$_lang['This login name already exists! Please chosse another one.'] = "Dette login bruges allerede! V&aelig;lg venligst et andet";
$_lang['This password already exists!'] = "Denne adgangskode er allerede oprettet. Venligst v&aelig;lg en anden.";
$_lang['This combination first name/family name already exists.'] = "Kombinationen fornavn/efternavn eksisterer allerede. ";
$_lang['the user is now in the list.'] = "Den nye bruger er oprettet.";
$_lang['the data set is now modified.'] = "Brugeren er blevet opdateret.";
$_lang['Please choose a user'] = "V&aelig;lg venligst bruger.";
$_lang['is still listed in some projects. Please remove it.'] = "er tildelt et eller flere projekter. Venligst slet disse f&oslash;rst.";
$_lang['All profiles are deleted'] = "Alle profiler er slettet.";
$_lang['A Profile with the same name already exists'] = "A profile with the same name already exists";
$_lang['is taken out of all user profiles'] = "er fjernet fra alle profiler.";
$_lang['All todo lists of the user are deleted'] = "Alle opgavelister er slettet.";
$_lang['is taken out of these votes where he/she has not yet participated'] = "er fjernet fra de afstemninger, hvor vedkommende endnu ikke har stemt.";
$_lang['All events are deleted'] = "Alle aftaler er slettet.";
$_lang['user file deleted'] = "Brugerdata er slettet.";
$_lang['bank account deleted'] = "Bankkonti er slettet.";
$_lang['finished'] = "f&aelig;rdig";
$_lang['Please choose a project'] = "Venligst v&aelig;lg et projekt.";
$_lang['The project is deleted'] = "Projektet er blevet slettet.";
$_lang['All links in events to this project are deleted'] = "Alle henvisninger fra kalenderen til dette projekt er slettet.";
$_lang['The duration of the project is incorrect.'] = "Projektets tidsrum er forkert.";
$_lang['The project is now in the list'] = "Projektet er blevet oprettet";
$_lang['The project has been modified'] = "Projektet er blevet opdateret.";
$_lang['Please choose a resource'] = "Venligst v&aelig;lg en ressource.";
$_lang['The resource is deleted'] = "Ressourcen er slettet.";
$_lang['All links in events to this resource are deleted'] = "Alle henvisninger fra kalenderen til denne ressource er nu slettet.";
$_lang[' The resource is now in the list.'] = " Ressourcen er blevet oprettet.";
$_lang[' The resource has been modified.'] = " Ressourcen er blevet opdateret.";
$_lang['The server sent an error message.'] = "Serveren giver f&oslash;lgende fejlmeddelelse:";
$_lang['All Links are valid.'] = "Alle links er intakte.";
$_lang['Please select at least one bookmark'] = "V&aelig;lg mindst &eacute;n foretrukken.";
$_lang['The bookmark is deleted'] = "Foretrukne er slettet.";
$_lang['threads older than x days are deleted.'] = "Bidrag til fora, der er mere end x dage gamle, er slettet.";
$_lang['All chat scripts are removed'] = "Alle chat-udskrifter er slettet.";
$_lang['or'] = "eller";
$_lang['Timecard management'] = "Tidsreg. administration";
$_lang['View'] = "vis";
$_lang['Choose group'] = "v&aelig;lg gruppe";
$_lang['Group name'] = "gruppenavn";
$_lang['Short form'] = "forkortelse";
$_lang['Category'] = "kategori";
$_lang['Remark'] = "bem&aelig;rkning";
$_lang['Group management'] = "Ops&aelig;tning af grupper";
$_lang['Please insert a name'] = "Venligst indtast navn.";
$_lang['Name or short form already exists'] = "Navn eller forkortelse findes allerede.";
$_lang['Automatic assign to group:'] = "automatisk tildeling til gruppe:";
$_lang['Automatic assign to user:'] = "automatisk tildeling til bruger:";
$_lang['Help Desk Category Management'] = "Administration af Helpdesk kategori";
$_lang['Category deleted'] = "Kategori slettet.";
$_lang['The category has been created'] = " Kategori er blevet oprettet.";
$_lang['The category has been modified'] = " Kategori er blevet opdateret.";
$_lang['Member of following groups'] = "medlem af f&oslash;lgende grupper";
$_lang['Primary group is not in group list'] = "Standardgruppen er ikke p&aring; listen.";
$_lang['Login name'] = "login navn";
$_lang['You cannot delete the default group'] = "Du kan ikke slette standardgruppen.";
$_lang['Delete group and merge contents with group'] = "Slet gruppe og l&aelig;g indhold til eksisterende gruppe.";
$_lang['Please choose an element'] = "V&aelig;lg et element.";
$_lang['Group created'] = "Gruppe oprettet.";
$_lang['File management'] = "Fil administration";
$_lang['Orphan files'] = "Filer uden tilh&oslash;rsforhold";
$_lang['Deletion of super admin root not possible'] = "super/admin/root kan ikke slettes";
$_lang['ldap name'] = "LDAP navn";
$_lang['mobile // mobile phone'] = "mobil nr";
$_lang['Normal user'] = "almindelig bruger";
$_lang['User w/Chief Rights'] = "privilegeret bruger (chef)";
$_lang['Administrator'] = "administrator";
$_lang['Logging'] = "Logging";
$_lang['Logout'] = "Logout";
$_lang['posting (and all comments) with an ID'] = "send indl&aelig;g (og kommentarer) med et ID";
$_lang['Role deleted, assignment to users for this role removed'] = "rolle slettet, tildeling af denne rolle til brugere fjernet";
$_lang['The role has been created'] = "rolle er oprettet";
$_lang['The role has been modified'] = "rolle er &aelig;ndret";
$_lang['Access rights'] = "Access rights";
$_lang['Usergroup'] = "Gruppe";
$_lang['logged in as'] = "Angemeldet als";

//chat.php
$_lang['Quit chat']= "Quit chat";

// contacts.php
$_lang['Contact Manager'] = "Administration af kontaktinformation";
$_lang['New contact'] = "ny kontaktperson";
$_lang['Group members'] = "gruppemedlemmer";
$_lang['External contacts'] = "eksterne kontakter";
$_lang['&nbsp;New&nbsp;'] = "&nbsp;&nbsp;ny&nbsp;&nbsp;";
$_lang['Import'] = "import&eacute;r";
$_lang['The new contact has been added'] = "Den nye kontaktperson er blevet oprettet.";
$_lang['The date of the contact was modified'] = "Kontaktpersonen er blevet opdateret.";
$_lang['The contact has been deleted'] = "Kontaktpersonen er slettet.";
$_lang['Open to all'] = "&aring;bn for alle";
$_lang['Picture'] = "billede";
$_lang['Please select a vcard (*.vcf)'] = "V&aelig;lg venligst et vcard (*.vcf).";
$_lang['create vcard'] = "opret vcard";
$_lang['import address book'] = "import&eacute;r adressekartotek";
$_lang['Please select a file (*.csv)'] = "V&aelig;lg venligst en fil (*.csv)";
$_lang['Howto: Open your outlook express address book and select file/export/other book<br>Then give the file a name, select all fields in the next dialog and finish'] = "S&aring;dan g&oslash;r du: &Aring;bn adressebogen i Outlook Express og v&aelig;lg 'Fil, Eksporter, Adressekartotek'<br>
Derefter v&aelig;lger du 'Tekstfil'.<br>Giv filen et navn og v&aelig;lg alle felter i den n&aelig;ste dialog. Afslut ved at trykke p&aring; 'Udf&oslash;r'.";
$_lang['Open outlook at file/export/export in file,<br>choose comma separated values (Win), then select contacts in the next form,<br>give the export file a name and finish.'] = "I Outlook, v&aelig;lg 'Fil, Eksporter, Eksporter til en fil',<br>
v&aelig;lg 'Semikolonseparerede v&aelig;rdier (DOS)', og v&aelig;lg derefter mappen med dine kontaktpersoner.<br>
Giv filen et navn og afslut.";
$_lang['Please choose an export file (*.csv)'] = "Venligst v&aelig;lg en eksportfil (*.csv)";
$_lang['Please export your address book into a comma separated value file (.csv), and either<br>1) apply an import pattern OR<br>2) modify the columns of the table with a spread sheet to this format<br>(Delete colums in you file that are not listed here and create empty colums for fields that do not exist in your file):'] = "Eksportér din adressebog som kommasepareret fil (.csv), og<br>
1) anvend et importmønster <i>eller</i><br>
2) tilpas kolonnerne ved hjælp af f.eks. et regneark til følgende format<br>
(Slet kolonner i din fil, som ikke er opført her, og indsat kolonner, som mangler i din fil.):";
$_lang['Please insert at least the family name'] = "Inds&aelig;t mindst efternavn.";
$_lang['Record import failed because of wrong field count'] = "Indl&aelig;sningen er mislykkedes pga. forkert antal felter.";
$_lang['Import to approve'] = "godkend indl&aelig;sningen";
$_lang['Import list'] = "indl&aelig;s liste";
$_lang['The list has been imported.'] = "Listen er blevet indl&aelig;st.";
$_lang['The list has been rejected.'] = "Listen er ikke blevet indl&aelig;st.";
$_lang['Profiles'] = "profiler";
$_lang['Parent object'] = "stamobjekt";
$_lang['Check for duplicates during import'] = "Kontrollér for dubletter ved import";
$_lang['Fields to match'] = "feltnavne, der skal matche";
$_lang['Action for duplicates'] = "hændelse for dubletter";
$_lang['Discard duplicates'] = "slet dubletter";
$_lang['Dispose as child'] = "indordn under første forekomst";
$_lang['Store as profile'] = "gem som profil";    
$_lang['Apply import pattern'] = "anvend importmønster"; 
$_lang['Import pattern'] = "importér mønster";
$_lang['For modification or creation<br>upload an example csv file'] = "upload importfil (csv)"; 
$_lang['Skip field'] = "spring felt over";
$_lang['Field separator'] = "feltseparator";
$_lang['Contact selector'] = "Contact selector";
$_lang['Use doublet'] = "Use doublet";
$_lang['Doublets'] = "Doublets";

// filemanager.php
$_lang['Please select a file'] = "Du skal angive en fil.";
$_lang['A file with this name already exists!'] = "Der findes allerede en fil med samme navn.";
$_lang['Name'] = "navn";
$_lang['Comment'] = "bem&aelig;rkning";
$_lang['Date'] = "dato";
$_lang['Upload'] = "upload";
$_lang['Filename and path'] = "filnavn og sti";
$_lang['Delete file'] = "slet fil";
$_lang['Overwrite'] = "overskriv fil";
$_lang['Access'] = "adgangsrettigheder";
$_lang['Me'] = "mig selv";
$_lang['Group'] = "group";
$_lang['Some'] = "andre";
$_lang['As parent object'] = "samme som for mappe";
$_lang['All groups'] = "All groups";
$_lang['You are not allowed to overwrite this file since somebody else uploaded it'] = "Du kan ikke overskrive filen, da den blev oprettet af en anden person";
$_lang['personal'] = "personlig";
$_lang['Link'] = "link";
$_lang['name and network path'] = "tilf&oslash;j sti";
$_lang['with new values'] = "med nye v&aelig;rdier";
$_lang['All files in this directory will be removed! Continue?'] = "Alle filer i denne mappe vil blive slettet! Forts&aelig;t?";
$_lang['This name already exists'] = "Dette navn findes allerede.";
$_lang['Max. file size'] = "maks. filst&oslash;rrelse";
$_lang['links to'] = "link til";
$_lang['objects'] = "objekter";
$_lang['Action in same directory not possible'] = "Denne handling er ikke mulig inden for den samme mappe.";
$_lang['Upload = replace file'] = "upload = filen bliver erstattet";
$_lang['Insert password for crypted file'] = "Indtast adgangskode for beskyttet(krypteret) fil";
$_lang['Crypt upload file with password'] = "beskyt(krypter) fil med adgangskode";
$_lang['Repeat'] = "gentag adgangskode";
$_lang['Passwords dont match!'] = "Adgangskode passer ikke";
$_lang['Download of the password protected file '] = "Download af fil beskyttet med adgangskode";
$_lang['notify all users with access'] = "besked til alle brugere med adgang";
$_lang['Write access'] = "skrive rettigheder";
$_lang['Version'] = "version";
$_lang['Version management'] = "versionstyring";
$_lang['lock'] = "l&aring;s";
$_lang['unlock'] = "l&aring;s op";
$_lang['locked by'] = "l&aring;st af";
$_lang['Alternative Download'] = "Alternativ download";
$_lang['Download'] = "Download";
$_lang['Select type'] = "Select type";
$_lang['Create directory'] = "Create directory";
$_lang['filesize (Byte)'] = "Filesize (Byte)";

// filter
$_lang['contains'] = 'indeholder';
$_lang['exact'] = 'præcis';
$_lang['starts with'] = 'begynder med';
$_lang['ends with'] = 'ender med';
$_lang['>'] = '>';
$_lang['>='] = '>=';
$_lang['<'] = '<';
$_lang['<='] = '<=';
$_lang['does not contain'] = 'indeholder ikke';
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
$_lang['Module Designer'] = "moduldesigner";
$_lang['Module element'] = "modulelement"; 
$_lang['Module'] = "modul";
$_lang['Active'] = "aktiv";
$_lang['Inactive'] = "inaktiv";
$_lang['Activate'] = "aktivér";
$_lang['Deactivate'] = "deaktivér"; 
$_lang['Create new element'] = "opret nyt element";
$_lang['Modify element'] = "redigér element";
$_lang['Field name in database'] = "feltnavn i database";
$_lang['Use only normal characters and numbers, no special characters,spaces etc.'] = "Brug kun bogstaver og tal, ingen specielle tegn, mellemrum osv.";
$_lang['Field name in form'] = "feltnavn i formen";
$_lang['(could be modified later)'] = "(kan ændres senere)"; 
$_lang['Single Text line'] = "enkel tekstlinie";
$_lang['Textarea'] = "tekstfelt";
$_lang['Display'] = "vis";
$_lang['First insert'] = "første element";
$_lang['Predefined selection'] = "foruddefineret valg";
$_lang['Select by db query'] = "vælg databaseforespørgsel";
$_lang['File'] = "fil";

$_lang['Email Address'] = "e-mailadresse";
$_lang['url'] = "url";
$_lang['Checkbox'] = "afkrydsningsboks";
$_lang['Multiple select'] = "flervalgsboks";
$_lang['Display value from db query'] = "Display value from db query";
$_lang['Time'] = "Time";
$_lang['Tooltip'] = "konteksthjælp"; 
$_lang['Appears as a tipp while moving the mouse over the field: Additional comments to the field or explanation if a regular expression is applied'] = "Hjælpen vises, når musen køres over feltet: yderligere kommentarer til feltet eller en forklaring på et regulært udtryk.";
$_lang['Position'] = "position";
$_lang['is current position, other free positions are:'] = "er den nuværende position, andre ledige positioner er:"; 
$_lang['Regular Expression:'] = "regulære udtryk:";
$_lang['Please enter a regular expression to check the input on this field'] = "Indtast et regulært indtryk for at validere feltet.";
$_lang['Default value'] = "standardværdi";
$_lang['Predefined value for creation of a record. Could be used in combination with a hidden field as well'] = "Foruddefineret værdi ved oprettelsen af et element. Kan også bruges i kombination med et skjult felt.";
$_lang['Content for select Box'] = "indhold for valgboks";
$_lang['Used for fixed amount of values (separate with the pipe: | ) or for the sql statement, see element type'] = "Bruges for et fast antal værdier (afgrænset ved \"pipe\"-tegnet: | ) eller for et SQL-forespørgsel, se elementtype.";
$_lang['Position in list view'] = "position i en listevisning";
$_lang['Only insert a number > 0 if you want that this field appears in the list of this module'] = "Indtast kun en værdi > 0 hvis du vil, at feltet optræder i listen for dette modul. ";
$_lang['Alternative list view'] = "alternativ liste visning";
$_lang['Value appears in the alt tag of the blue button (mouse over) in the list view'] = "Værdien optræder som konteksthjælp for den blå knap i listevisningen.";
$_lang['Filter element'] = "filterelement";
$_lang['Appears in the filter select box in the list view'] = "Optræder i filtervalgsboksen i listevisningen.";
$_lang['Element Type'] = "elementtype";
$_lang['Select the type of this form element'] = "Vælg typen for dette formelement.";
$_lang['Check the content of the previous field!'] = "Kontrollér indholdet af de forrige felt.";
$_lang['Span element over'] = "Fordél elementet over";
$_lang['columns'] = "kolonner";
$_lang['rows'] = "rækker";
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
$_lang['Welcome to the setup of PHProject!<br>'] = "Velkommen til ops&aelig;tningen af PHProjekt";
$_lang['Please remark:<ul><li>A blank database must be available<li>Please ensure that the webserver is able to write the file config.inc.php'] = "Bem&aelig;rk:<ul>
<li>en tom database skal v&aelig;re til stede
<li>kontroll&eacute;r, at der kan skrives til konfigurationsfilen 'config.inc.php'<br> (skriverettigheder gives med 'chmod 777')";
$_lang['<li>If you encounter any errors during the installation, please look into the <a href=help/faq_install.html target=_blank>install faq</a>or visit the <a href=http://www.PHProjekt.com/forum.html target=_blank>Installation forum</a></i>'] = "<li>hvis du har problemer med installationen, se <a href='help/faq_install.html' target=_blank>'installations-FAQ'</a>
eller bes&oslash;g PHProjekts<a href='http://www.PHProjekt.com/forum.html' target=_blank>installationsforum</a></i>";
$_lang['Please fill in the fields below'] = "F&oslash;rst skal du indtaste informationer om din databaseops&aelig;tning,<br>som vil blive testet.";
$_lang['(In few cases the script wont respond.<br>Cancel the script, close the browser and try it again).<br>'] = "(Hvis skriptet ikke kommer tilbage ved denne rutine,<br>
afbryd, luk browseren og start forfra..)";
$_lang['Type of database'] = "databasetype";
$_lang['Hostname'] = "computernavn (hostname)";
$_lang['Username'] = "bruger";

$_lang['Name of the existing database'] = "den (eksistierende) databases navn";
$_lang['config.inc.php not found! Do you really want to update? Please read INSTALL ...'] = "config.inc.php blev ikke fundet! Vil du virkelig lave en opdatering. L&aelig;s venligst INSTALL...";
$_lang['config.inc.php found! Maybe you prefer to update PHProject? Please read INSTALL ...'] = "Der findes en config.inc.php i forvejen! Vil du ikke hellere lave en opdatering. L&aelig;s venligst INSTALL...";
$_lang['Please choose Installation,Update or Configure!'] = "V&aelig;lg venligst <b>installation</b> eller <b>opgradering</b>. Tilbage...";
$_lang['Sorry, I cannot connect to the database! <br>Please close all browser windows and restart the installation.'] = "Hov, der er noget galt... <br> kontroll&eacute;r venligst dine indtastninger!";
$_lang['Sorry, it does not work! <br> Please set DBDATE to Y4MD- or let phprojekt change this environment-variable (php.ini)!'] = "Beklager, men det fungerer ikke! <br> Venligst s&aelig;t DBDATE til 'Y4MD-' eller lad PHProjekt &aelig;ndre denne variable i php.ini.";
$_lang['Seems that You have a valid database connection!'] = "Det er lykkedes at etablere forbindelse til databasen.";
$_lang['Please select the modules you are going to use.<br> (You can disable them later in the config.inc.php)<br>'] = "V&aelig;lg de komponenter, som du vil installere.<br> (Komponenterne kan senere i config.inc.php deaktiveres.)<br>";
$_lang['Install component: insert a 1, otherwise keep the field empty'] = "'1' = install&eacute;r, tomt felt = install&eacute;r ikke";
$_lang['Group views'] = "gruppevisninger i<br> kalenderen";
$_lang['Todo lists'] = "opgavelister";

$_lang['Voting system'] = "afstemningssystem";


$_lang['Contact manager'] = "kontaktmanager";
$_lang['Name of userdefined field'] = "navn p&aring; brugerdefineret felt";
$_lang['Userdefined'] = "brugerdefineret";
$_lang['Profiles for contacts'] = "profil for kontakter";
$_lang['Mail'] = "send email";
$_lang['send mail'] = " kun afsendelse";
$_lang[' only,<br> &nbsp; &nbsp; full mail client'] = "2: fuld mailklient";



$_lang['1 to show appointment list in separate window,<br>&nbsp; &nbsp; 2 for an additional alert.'] = "'1' aftaleliste i eget vindue,<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'2' for supplerende alarmboks.";
$_lang['Alarm'] = "p&aring;mindelse/alarm";
$_lang['max. minutes before the event'] = "maks. minutter f&oslash;r aftalen";
$_lang['SMS/Mail reminder'] = "SMS/Mail reminder";
$_lang['Reminds via SMS/Email'] = "Reminds via SMS/Email";
$_lang['1= Create projects,<br>&nbsp; &nbsp; 2= assign worktime to projects only with timecard entry<br>&nbsp; &nbsp; 3= assign worktime to projects without timecard entry<br>&nbsp; &nbsp; (Selection 2 or 3 only with module timecard!)'] = "'1'= opret projekter,<br>
&nbsp; &nbsp; '2'= tildel kun arbejdstid kun til projekter med tidreg.<br>
&nbsp; &nbsp; '3'= tildel ogs&aring; arbejdstid til projekter uden tidsreg.<br> ";

$_lang['Name of the directory where the files will be stored<br>( no file management: empty field)'] = "indtast mappe, i hvilken filerne skal gemmes<br>(tomt felt for ingen filstyring)";
$_lang['absolute path to this directory (no files = empty field)'] = "indtast mappens absolute sti";
$_lang['Time card'] = "tidsregistrering";
$_lang['1 time card system,<br>&nbsp; &nbsp; 2 manual insert afterwards sends copy to the chief'] = "'1' = aktiv&eacute;r tidsregistrering ,<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '2' = efterf&oslash;lgende registrering med kopi til chefen";
$_lang['Notes'] = "noter";
$_lang['Password change'] = "skift af adgangskode";
$_lang['New passwords by the user - 0: none - 1: only random passwords - 2: choose own'] = "1: bruger kan selv oprette nye adangsk - 0: nej, 1: tilf&aelig;ldige adgangsk, 2: egen indtastning";
$_lang['Encrypt passwords'] = "krypt&eacute;r adgangsk";
$_lang['Login via '] = "login via ";
$_lang['Extra page for login via SSL'] = "ved login via SSL";
$_lang['Groups'] = "grupper";
$_lang['User and module functions are assigned to groups<br>&nbsp; &nbsp; (recommended for user numbers > 40)'] = "brugere og moduler arbejder i grupper<br>
(anbefalet for brugere > 40)";
$_lang['User and module functions are assigned to groups'] = "brugere og moduler arbejder i grupper";
$_lang['Help desk'] = "Help desk";
$_lang['Help Desk Manager / Trouble Ticket System'] = "manager for supportforesp&oslash;rgsler";
$_lang['RT Option: Customer can set a due date'] = "RT ops&aelig;tning: kunden kan s&aelig;tte en frist";
$_lang['RT Option: Customer Authentification'] = "RT ops&aelig;tning: rettigheder for support";
$_lang['0: open to all, email-address is sufficient<br>1: registered contact enter his family name<br>2: his email'] = "0 = &aring;ben, e-mail adresse er tilstr&aelig;kkelig, 1 = kunde skal v&aelig;re opf&oslash;rt i kontaktlisten og indtaste efternavn";
$_lang['RT Option: Assigning request'] = "RT ops&aelig;tning: henvisning af foresp&oslash;rgsler";
$_lang['0: by everybody, 1: only by persons with status chief'] = "0 = af alle, 1 = kun af chefen";
$_lang['Email Address of the support'] = "e-mail adresse for support";
$_lang['Scramble filenames'] = "skjul filnavne";
$_lang['creates scrambled filenames on the server<br>assigns previous name at download'] = "opretter skjulte filnavne p&aring; serveren<br>
oprindelige navne bliver stadig brugt til download.";

$_lang['0: last name, 1: short name, 2: login name'] = "0: efternavn, 1: forkortelse, 2: loginnavn";
$_lang['Prefix for table names in db'] = "Prefix for table names in db";
$_lang['Alert: Cannot create file config.inc.php!<br>Installation directory needs rwx access for your server and rx access to all others.'] = "Filen config.inc.php kunne ikke gemmes!<br>
Installationsmappen skal have 'rwx' rettigheder for webserveren og 'rx' for alle andre.";
$_lang['Location of the database'] = "databasens placering";
$_lang['Type of database system'] = "databasetype";
$_lang['Username for the access'] = "bruger for databaseadgang";
$_lang['Password for the access'] = "adgangskode for databasen";
$_lang['Name of the database'] = "databasens navn";
$_lang['Prefix for database table names'] = "Prefix for database table names";
$_lang['First background color'] = "f&oslash;rste baggrundsfarve";
$_lang['Second background color'] = "anden baggrundsfarve";
$_lang['Third background color'] = "tredje baggrundsfarve";
$_lang['Color to mark rows'] = "Color to mark rows";
$_lang['Color to highlight rows'] = "Color to highlight rows";
$_lang['Event color in the tables'] = "terminfarve i <br>tabellevisning";
$_lang['company icon yes = insert name of image'] = "firmalogo ja = indtast navn";
$_lang['URL to the homepage of the company'] = "firmahjemmesidens URL";
$_lang['no = leave empty'] = "nej = tomt felt";
$_lang['First hour of the day:'] = "start p&aring; arbejdstid i kalender [kl]: ";
$_lang['Last hour of the day:'] = "slut p&aring; arbejdstid i kalender [kl]:";
$_lang['An error ocurred while creating table: '] = "fejl ved oprettelse af tabellen: ";
$_lang['Table dateien (for file-handling) created'] = "filtabel (til filstyring) oprettet";
$_lang['File management no = leave empty'] = "ingen filstyring = tomt felt";
$_lang['yes = insert full path'] = "ja = instast absolut sti";
$_lang['and the relative path to the PHProjekt directory'] = "samtidigt relativ sti til root";
$_lang['Table profile (for user-profiles) created'] = "profiltabel (for gruppevisning) oprettet";
$_lang['User Profiles yes = 1, no = 0'] = "gruppevisninger ja = 1, nej = 0";
$_lang['Table todo (for todo-lists) created'] = "opgavetabel (for opgavelisten) oprettet";
$_lang['Todo-Lists yes = 1, no = 0'] = "opgaveliste ja = 1, nej = 0";
$_lang['Table forum (for discssions etc.) created'] = "forumtabel (for forum) oprettet";
$_lang['Forum yes = 1, no = 0'] = "forum ja = 1, nej = 0";
$_lang['Table votum (for polls) created'] = "afstemningstabel (for afstemninger) oprettet";
$_lang['Voting system yes = 1, no = 0'] = "afstemninger ja = 1, nej = 0";
$_lang['Table lesezeichen (for bookmarks) created'] = "bogm&aelig;rketabel (for foretrukne) oprettet";
$_lang['Bookmarks yes = 1, no = 0'] = "foretrukne ja = 1, nej = 0";
$_lang['Table ressourcen (for management of additional ressources) created'] = "ressourcetabel oprettet";
$_lang['Resources yes = 1, no = 0'] = "ressourcer ja = 1, nej = 0";
$_lang['Table projekte (for project management) created'] = "projektetabel (for projektstyring) oprettet";
$_lang['Table contacts (for external contacts) created'] = "kontakttabel (for eksterne kontakter) oprettet";
$_lang['Table notes (for notes) created'] = "notestabel (for noter) oprettet";
$_lang['Table timecard (for time sheet system) created'] = "tidsreg-tabel (for tidsstyring) oprettet";
$_lang['Table groups (for group management) created'] = "gruppetabel (for grupper) oprettet";
$_lang['Table timeproj (assigning work time to projects) created'] = "tabellen timeproj (for time/sagstyring) oprettet";
$_lang['Table rts and rts_cat (for the help desk) created'] = "tabellerne rts og rts_cat (for help desk) oprettet";
$_lang['Table mail_account, mail_attach, mail_client und mail_rules (for the mail reader) created'] = "tabellerne mail_account, mail_attach, mail_client und mail_rules (for email program) oprettet";
$_lang['Table logs (for user login/-out tracking) created'] = "tabellen logs (for bruger login/-out registrering) oprettet";
$_lang['Tables contacts_profiles und contacts_prof_rel created'] = "tabellerne contacts_profiles og contacts_prof_rel oprettet";
$_lang['Project management yes = 1, no = 0'] = "projektstyring ja = 1, nej = 0";
$_lang['additionally assign resources to events'] = "tildel yderligere ressourcer til projekter";
$_lang['Address book  = 1, nein = 0'] = "adressebog ja = 1, nej = 0";
$_lang['Mail no = 0, only send = 1, send and receive = 2'] = "email; ingen = 0, kan kun sende = 1, b&aring;de sende og modtage = 2";
$_lang['Chat yes = 1, no = 0'] = "Chat yes = 1, no = 0";
$_lang['Name format in chat list'] = "Name format in chat list";
$_lang['0: last name, 1: first name, 2: first name, last name,<br> &nbsp; &nbsp; 3: last name, first name'] = "0: last name, 1: first name, 2: first name, last name,<br> &nbsp; &nbsp; 3: last name, first name";
$_lang['Timestamp for chat messages'] = "Timestamp for chat messages";
$_lang['users (for authentification and address management)'] = "brugertabel (for PHProjekt-brugere) oprettet";
$_lang['Table termine (for events) created'] = "aftaletabel (for gruppekalenderen) oprettet";
$_lang['The following users have been inserted successfully in the table user:<br>root - (superuser with all administrative privileges)<br>test - (chief user with restricted access)'] = "F&oslash;lgende brugere er oprettet i brugertabellen:<br>
'root' - med administrationsrettigheder<br>
'test' - med almindelige brugerrettigheder";
$_lang['The group default has been created'] = "gruppen 'default' oprettet";
$_lang['Please do not change anything below this line!'] = "Der skal ikke &aelig;ndres under denne linie.";
$_lang['Database error'] = "databasefejl";
$_lang['Finished'] = "Det var det!";
$_lang['There were errors, please have a look at the messages above'] = "Der var fejl, se fejlmeddelelserne oven for.";
$_lang['All required tables are installed and <br>the configuration file config.inc.php is rewritten<br>It would be a good idea to makea backup of this file.<br>'] = "Alle tabeller er installeret og <br>
konfigurationsfilen config.inc.php er tilpasset.<br>
Det anbefales, at filen gemmes nu.<br>
Luk alle browservinduer.<br>";
$_lang['The administrator root has the password root. Please change his password here:'] = "Adgangskoden for 'root' er 'root'. Venligst skift adgangskoden<br>";
$_lang['The user test is now member of the group default.<br>Now you can create new groups and add new users to the group'] = "Bruger 'test' er medlemm af gruppen 'default'.<br>
Opret nu nye grupper og brugere.";
$_lang['To use PHProject with your Browser go to <b>index.php</b><br>Please test your configuration, especially the modules Mail and Files.'] = "PHProjekt startes med filen <b>index.php</b> i hovedmappen.<br>
Det anbefales at teste installationen, is&aelig;r modulerne 'Email' og 'H&aring;ndtering af filer'";

$_lang['Alarm x minutes before the event'] = "alarm x minutter f&oslash;r aftalen";
$_lang['Additional Alarmbox'] = "supplerende alarmboks";
$_lang['Mail to the chief'] = "mail til chefen";
$_lang['Out/Back counts as: 1: Pause - 0: Workingtime'] = "frav&aelig;r g&aelig;lder som: 1 = pause - 0 = arbejdstid";
$_lang['Passwords will now be encrypted ...'] = "adgangsk bliver nu krypteret";
$_lang['Filenames will now be crypted ...'] = "filnavne bliver nu krypteret";
$_lang['Do you want to backup your database right now? (And zip it together with the config.inc.php ...)<br>Of course I will wait!'] = "Vil du ikke lave en sikkerhedskopi af databasen inden opdateringen?
<br> Jeg venter gerne!";
$_lang['Next'] = "n&aelig;ste";
$_lang['Notification on new event in others calendar'] = "Meddelelse hvis andre skriver i ens kalender";
$_lang['Path to sendfax'] = "sti til sendfax";
$_lang['no fax option: leave blank'] = "lad feltet st&aring; tomt for ingen faxunderst&oslash;ttelse";
$_lang['Please read the FAQ about the installation with postgres'] = "Venligst l&aelig;s FAQ'en for installation med PostgreSQL.";
$_lang['Length of short names<br> (Number of letters: 3-6)'] = "Forkortelsernes l&aelig;ngde<br> (antal bogstaver: 3-6)";
$_lang['If you want to install PHProjekt manually, you find<a href=http://www.phprojekt.com/files/sql_dump.tar.gz target=_blank>here</a> a mysql dump and a default config.inc.php'] = "Hvis du vil installere PHProjekt manuelt kan du hente
<a href='http://www.phprojekt.com/files/sql_dump.tar.gz' target=_blank>en mysql dump og en standard konfigurationsfil</a>.";
$_lang['The server needs the privilege to write to the directories'] = "Serveren skal have skriverettigheder til mappen.";
$_lang['Header groupviews'] = "overskrifter for gruppevisninger";
$_lang['name, F.'] = "navn, initial";
$_lang['shortname'] = "kortnavn";
$_lang['loginname'] = "loginnavn";
$_lang['Please create the file directory'] = "Venligst opret denne mappe.";
$_lang['default mode for forum tree: 1 - open, 0 - closed'] = "standardvisning for tr&aring;de i fora: 1 - &aring;bne, 0 - lukkede";
$_lang['Currency symbol'] = "valutasymbol";
$_lang['current'] = "nuv&aelig;rende";
$_lang['Default size of form elements'] = "Default size of form elements";
$_lang['use LDAP'] = "brug LDAP";
$_lang['Allow parallel events'] = "Tillad samtidige aktiviteter";
$_lang['Timezone difference [h] Server - user'] = "tidszoneforskel [t] server - bruger";
$_lang['Timezone'] = "tidszone";
$_lang['max. hits displayed in search module'] = "vis maks. tr&aelig;ffer i s&oslash;gningen";
$_lang['Time limit for sessions'] = "tidsbegrænsning for sessioner";
$_lang['0: default mode, 1: Only for debugging mode'] = "0: standardmodus, 1: kun til fejlsøgning";
$_lang['Enables mail notification on new elements'] = "send en e-mail ved nye elementer";
$_lang['Enables versioning for files'] = "slå versionsstyring for filer til";
$_lang['no link to contacts in other modules'] = "ingen links til kontakter fra ander moduler";
$_lang['Highlight list records with mouseover'] = "'mouseover'-effekt i lister";
$_lang['Track user login/logout'] = "log bruger-login/logout";
$_lang['Access for all groups'] = "adgang for alle grupper";
$_lang['Option to release objects in all groups'] = "mulighed for at frigive objekter i alle grupper";
$_lang['Default access mode: private=0, group=1'] = "standard adgangsmodus: privat=0, gruppe=1"; 
$_lang['Adds -f as 5. parameter to mail(), see php manual'] = "tilføjer '-f' som 5. parameter i mail(), se php-manual";
$_lang['end of line in body; e.g. \r\n (conform to RFC 2821 / 2822)'] = "linieendelse i meddelelse; f.eks. \\r\\n (jf. RFC 2821 / 2822)";
$_lang['end of header line; e.g. \r\n (conform to RFC 2821 / 2822)'] = "linieendelse i emnelinien; f.eks. \\r\\n (jf. RFC 2821 / 2822)";
$_lang['Sendmail mode: 0: use mail(); 1: use socket'] = "sendmail-metode: 0: brug mail(); 1: brug socket";
$_lang['the real address of the SMTP mail server, you have access to (maybe localhost)'] = "SMTP-serverens adresse (måske localhost)";
$_lang['name of the local server to identify it while HELO procedure'] = "navnet på denne server til identifikation i HELO-proceduren";
$_lang['Authentication'] = "autentifikation";
$_lang['fill out in case of authentication via POP before SMTP'] = "Udfyld dette, hvis der skal bruges autentifikation via POP inden SMTP";
$_lang['real username for POP before SMTP'] = "brugernavn for POP inden SMTP";
$_lang['password for this pop account'] = "adgangskode for denne POP-konto"; 
$_lang['the POP server'] = "POP-server";
$_lang['fill out in case of SMTP authentication'] = "Udfyld for SMTP-autentificering";
$_lang['real username for SMTP auth'] = "brugernavn for SMTP-autentificering";
$_lang['password for this account'] = "SMTP-adgangskode";
$_lang['SMTP account data (only needed in case of socket)'] = "SMTP-kontooplysninger (bruges kun ved socket-metode)";
$_lang['No Authentication'] = "ingen autentificering"; 
$_lang['with POP before SMTP'] = "POP inden SMTP";
$_lang['SMTP auth (via socket only!)'] = "SMTP autentificering (kun via socket!)";
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
$_lang['Resource List'] = "ressourceliste";
$_lang['Event List'] = "aftaleliste";
$_lang['Calendar Views'] = "Visning af kalender / aftaler";

$_lang['Personnel'] = "personer";
$_lang['Create new event'] = "Opret &amp; slet aftale";
$_lang['Day'] = "dag";

$_lang['Until'] = "til";

$_lang['Note'] = "note";
$_lang['Project'] = "projekt";
$_lang['Res'] = "ressource";
$_lang['Once'] = "&eacute;n gang";
$_lang['Daily'] = "daglig";
$_lang['Weekly'] = "1x/ugen";
$_lang['Monthly'] = "1x/m&aring;neden";
$_lang['Yearly'] = "1x/&aring;ret";

$_lang['Create'] = "opret";

$_lang['Begin'] = "start";
$_lang['Out of office'] = "ude af huset";
$_lang['Back in office'] = "tilbage";
$_lang['End'] = "slut";
$_lang['@work'] = "p&aring; arbejde";
$_lang['We'] = "uge";
$_lang['group events'] = "gruppeaftaler";
$_lang['or profile'] = "eller profiler";
$_lang['All Day Event'] = "heldagsarrangement";
$_lang['time-axis:'] = "tidsakse:";
$_lang['vertical'] = "lodret";
$_lang['horizontal'] = "vandret";
$_lang['Horz. Narrow'] = "vandr. smal";
$_lang['-interval:'] = "-interval:";
$_lang['Self'] = " egen ";

$_lang['...write'] = "(gruppe)aftale";

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
$_lang['Please call login.php!'] = "Start venligst med index.php!";

// m1.php
$_lang['There are other events!<br>the critical appointment is: '] = "Der er et sammenfald af datoer! <br>Dato: ";
$_lang['Sorry, this resource is already occupied: '] = "F&oslash;lgende ressource er allerede optaget: ";
$_lang[' This event does not exist.<br> <br> Please check the date and time. '] = " Denne aftale eksisterer ikke.<br> <br>Venligst kontroll&eacute;r din indtastning.";
$_lang['Please check your date and time format! '] = "Kontroll&eacute;r talformatet! ";
$_lang['Please check the date!'] = "Kontroll&eacute;r datoen!";
$_lang['Please check the start time! '] = "Kontroll&eacute;r start tidspunkt! ";
$_lang['Please check the end time! '] = "Kontroll&eacute;r slut tidspunkt! ";
$_lang['Please give a text or note!'] = "Indtast tekst eller anm&aelig;rkning!";
$_lang['Please check start and end time! '] = "Fejl i tidsangivelsen! ";
$_lang['Please check the format of the end date! '] = "Kontroll&eacute;r slutdatoens talformat! ";
$_lang['Please check the end date! '] = "Kontroll&eacute;r slutdatoen! ";





$_lang['Resource'] = "ressource";
$_lang['User'] = "bruger";

$_lang['delete event'] = "slet aftale";
$_lang['Address book'] = "adressebog";


$_lang['Short Form'] = "forkortelse";

$_lang['Phone'] = "tlf";
$_lang['Fax'] = "fax";



$_lang['Bookmark'] = "bogm&aelig;rke";
$_lang['Description'] = "betegnelse";

$_lang['Entire List'] = "komplet liste";

$_lang['New event'] = "ny aftale";
$_lang['Created by'] = "oprettet af";
$_lang['Red button -> delete a day event'] = "r&oslash;d knap -> slet en heldagsaftale";
$_lang['multiple events'] = "flere aftaler";
$_lang['Year view'] = "vis hele &aring;ret";
$_lang['calendar week'] = "kalender uge";

//m2.php
$_lang['Create &amp; Delete Events'] = "opret &amp; slet aftaler";
$_lang['normal'] = "normal";
$_lang['private'] = "privat";
$_lang['public'] = "offentlig";
$_lang['Visibility'] = "synlighed";

//mail module
$_lang['Please select at least one (valid) address.'] = "V&aelig;lg mindst &eacute;n korrekt adresse.";
$_lang['Your mail has been sent successfully'] = "Din mail er blevet sendt.";
$_lang['Attachment'] = "vedh&aelig;ftede filer";
$_lang['Send single mails'] = "send som enkelt beskeder";
$_lang['Does not exist'] = "findes ikke";
$_lang['Additional number'] = "supplerende nummer";
$_lang['has been canceled'] = "er blevet annulleret.";

$_lang['marked objects'] = "markerede objekter";
$_lang['Additional address'] = "supplerende adresse";
$_lang['in mails'] = "i e-mails";
$_lang['Mail account'] = "email konto";
$_lang['Body'] = "tekst";
$_lang['Sender'] = "afsender";

$_lang['Receiver'] = "modtager";
$_lang['Reply'] = "svar";
$_lang['Forward'] = "videresend";
$_lang['Access error for mailbox'] = "fejl ved l&aelig;sning af postkasse";
$_lang['Receive'] = "hent";
$_lang['Write'] = "send";
$_lang['Accounts'] = "konti";
$_lang['Rules'] = "regler";
$_lang['host name'] = "host navn";
$_lang['Type'] = "type";
$_lang['misses'] = "mangler";
$_lang['has been created'] = "er oprettet";
$_lang['has been changed'] = "er &aelig;ndret";
$_lang['is in field'] = "er i felt";
$_lang['and leave on server'] = "hent post og lad en kopi ligge p&aring; serveren";
$_lang['name of the rule'] = "reglens navn";
$_lang['part of the word'] = "del af ordet";
$_lang['in'] = "i";
$_lang['sent mails'] = "sendt post";
$_lang['Send date'] = "forsendelsesdato";
$_lang['Received'] = "modtaget";
$_lang['to'] = "til";
$_lang['imcoming Mails'] = "indkommende beskeder";
$_lang['sent Mails'] = "sendte beskeder";
$_lang['Contact Profile'] = "kontakt profil";
$_lang['unread'] = "ul&aelig;st(e)";
$_lang['view mail list'] = "se post liste";
$_lang['insert db field (only for contacts)'] = "ins&aelig;t db felt (kun for kontakter)";
$_lang['Signature'] = "underskrift";

$_lang['SMS'] = "SMS";
$_lang['Single account query'] = "Foresp&oslash;rgsel p&aring; enkelt konto";
$_lang['Notice of receipt'] = "bekr&aelig;telse af modtagelsen";
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
$_lang['Mail note to'] = "send noten til";
$_lang['added'] = "tilf&oslash;jet";
$_lang['changed'] = "&aelig;ndret";

// o.php
$_lang['Calendar'] = "kalender";
$_lang['Contacts'] = "kontakter";


$_lang['Files'] = "filer";



$_lang['Options'] = "indstillinger";
$_lang['Timecard'] = "tidsreg.";

$_lang['Helpdesk'] = "helpdesk";

$_lang['Info'] = "info";
$_lang['Todo'] = "opgaver";
$_lang['News'] = "nyheder";
$_lang['Other'] = "andre";
$_lang['Settings'] = "ops&aelig;tning";
$_lang['Summary'] = "oversigt";

// options.php
$_lang['Description:'] = "betegnelse:";
$_lang['Comment:'] = "bem&aelig;rkning:";
$_lang['Insert a valid Internet address! '] = "Indtast en e-mailadresse. ";
$_lang['Please specify a description!'] = "Indtast en betegnelse.";
$_lang['This address already exists with a different description'] = "Den samme adresse eksisterer allerede under denne betegnelse.";
$_lang[' already exists. '] = " er allerede brugt som betegnelse. ";
$_lang['is taken to the bookmark list.'] = " er blevet tilf&oslash;jet dine foretrukne.";
$_lang[' is changed.'] = " er blevet opdateret.";
$_lang[' is deleted.'] = " er blevet slettet.";
$_lang['Please specify a description! '] = "Indtast en betegnelse. ";
$_lang['Please select at least one name! '] = "V&aelig;lg mindst &eacute;t navn. ";
$_lang[' is created as a profile.<br>'] = " er oprettet som profil.<br> Du kan kalde profilen, efter du har genindl&aelig;st hovedsiden eller ved at du &aring;bner en aftale.";
$_lang['is changed.<br>'] = "er opdateret.<br> &AElig;ndringen bliver f&oslash;rst synlig efter du har genindl&aelig;st hovedsiden eller ved at du &aring;bner en aftale.";
$_lang['The profile has been deleted.'] = "Profilen er slettet.";
$_lang['Please specify the question for the poll! '] = "Indtast et sp&oslash;rgsm&aring;l. ";
$_lang['You should give at least one answer! '] = "Indtast mindst &eacute;n valgmulighed. ";
$_lang['Your call for votes is now active. '] = "Dit sp&oslash;rgsm&aring;l kommer nu til afstemmning. ";
$_lang['<h2>Bookmarks</h2>In this section you can create, modify or delete bookmarks:'] = "<h4>foretrukne</h4>Her kan du oprette, redigere og slette foretrukne (bogm&aelig;rker):";
$_lang['Create'] = "opret ny";


$_lang['<h2>Profiles</h2>In this section you can create, modify or delete profiles:'] = "<h4>profiler</h4>Her kan du oprette, redigere og slette profiler:";
$_lang['<h2>Voting Formula</h2>'] = "<h4>afstemningsformular</h4>";
$_lang['In this section you can create a call for votes.'] = "Her kan du starte en foresp&oslash;rgsel eller en afstemning. Du kan definere op til tre valgmuligheder.";
$_lang['Question:'] = "sp&oslash;rgsm&aring;l:";
$_lang['just one <b>Alternative</b> or'] = "V&aelig;lg &eacute;t <b>alternativ</b> eller";
$_lang['several to choose?'] = "flere?";

$_lang['Participants:'] = "deltagende personer:";

$_lang['<h3>Password Change</h3> In this section you can choose a new random generated password.'] = "<h4>skift adgangskode</h4>Her kan du skifte din adgangskode.";
$_lang['Old Password'] = "Indtast gammel adgangskode:";
$_lang['Generate a new password'] = "Generer ny adgangskode:";
$_lang['Save password'] = "Gem adgangskode:";
$_lang['Your new password has been stored'] = "Din ny adgangskode er gemt.";
$_lang['Wrong password'] = "Forkert adgangskode.";
$_lang['Delete poll'] = "Slet afstemning.";
$_lang['<h4>Delete forum threads</h4> Here you can delete your own threads<br>Only threads without a comment will appear.'] = "<h4>slet diskussionsindl&aelig;g</h4>Her kan du slette egne indl&aelig;g i systemets diskussionsforum.<br>
Der vises kun indl&aelig;g uden svar.";

$_lang['Old password'] = "gammel adgangskode";
$_lang['New Password'] = "ny adgangskode";
$_lang['Retype new password'] = "bekr&aelig;ft";
$_lang['The new password must have 5 letters at least'] = "Den nye adgangskode skal mindst v&aelig;re 5 tegn.";
$_lang['You didnt repeat the new password correctly'] = "De to indtastede adgangsk er forskellige.";

$_lang['Show bookings'] = "vis reservationerne";
$_lang['Valid characters'] = "gyldige tegn";
$_lang['Suggestion'] = "forslag";
$_lang['Put the word AND between several phrases'] = "s&aelig;t ordet AND mellem flere udtryk"; // translators: please leave the word AND as it is
$_lang['Write access for calendar'] = "skriverettighed til kalender";
$_lang['Write access for other users to your calendar'] = "skriverettighed for andre til din kalender";
$_lang['User with chief status still have write access'] = "brugere med chefstatus har stadig skriverettighed";

// projects
$_lang['Project Listing'] = "projektliste";
$_lang['Project Name'] = "projektnavn";


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
$_lang['Real End'] = "afsluttet";
$_lang['Participants'] = "personer";
$_lang['Priority'] = "prioritet";
$_lang['Status'] = "status";
$_lang['Last status change'] = "sidste <br>&aelig;ndring";
$_lang['Leader'] = "leder";
$_lang['Statistics'] = "projektstatistik";
$_lang['My Statistic'] = "min statistik";

$_lang['Person'] = "person";
$_lang['Hours'] = "timer";
$_lang['Project summary'] = "projektresumee";
$_lang[' Choose a combination Project/Person'] = "V&aelig;lg venligst projekt/personerkombination";
$_lang['(multiple select with the Ctrl/Cmd-key)'] = "(v&aelig;lg flere ved hj&aelig;lp af 'Ctrl'-tast)";

$_lang['Persons'] = "person(er)";
$_lang['Begin:'] = "start:";
$_lang['End:'] = "slut:";
$_lang['All'] = "alle";
$_lang['Work time booked on'] = "arbejdstid reserveret hos";
$_lang['Sub-Project of'] = "underprojekt af";
$_lang['Aim'] = "m&aring;l";
$_lang['Contact'] = "kontaktperson";
$_lang['Hourly rate'] = "timepris";
$_lang['Calculated budget'] = "beregnet budget";
$_lang['New Sub-Project'] = "ny underprojekt";
$_lang['Booked To Date'] = "reserveret indtil nu";
$_lang['Budget'] = "budget";
$_lang['Detailed list'] = "detaljeret liste";
$_lang['Gantt'] = "tidslinie";
$_lang['offered'] = "tilbudt";
$_lang['ordered'] = "bestilt";
$_lang['Working'] = "i gang";
$_lang['ended'] = "afsluttet";
$_lang['stopped'] = "afbrudt";
$_lang['Re-Opened'] = "fortsat";
$_lang['waiting'] = "venter";
$_lang['Only main projects'] = "kun hovedprojekter";
$_lang['Only this project'] = "kun  dette projekt";
$_lang['Begin > End'] = "start > slut";
$_lang['ISO-Format: yyyy-mm-dd'] = "ISO-format: yyyy-mm-dd";
$_lang['The timespan of this project must be within the timespan of the parent project. Please adjust'] = "perioden for dette projekt skal v&aelig;re indenfor perioden for hovedprojektet. Juster tidspunkterne";
$_lang['Please choose at least one person'] = "v&aelig;lg mindst en person";
$_lang['Please choose at least one project'] = "v&aelig;lg mindst et projekt";
$_lang['Dependency'] = "afh&aelig;ngighed";
$_lang['Previous'] = "foreg&aring;ende";

$_lang['cannot start before the end of project'] = "kan ikke begynde f&oslash;r afslutningen af projekt";
$_lang['cannot start before the start of project'] = "kan ikke begynde f&oslash;r starten p&aring; projekt";
$_lang['cannot end before the start of project'] = "kan ikke slutte f&oslash;r starten p&aring projekt";
$_lang['cannot end before the end of project'] = "kan ikke slutte f&oslash;r afslutningen af projekt";
$_lang['Warning, violation of dependency'] = "Advarsel! overtr&aelig;delse af afh&aelig;ngighed";
$_lang['Container'] = "container";
$_lang['External project'] = "eksternt projekt";
$_lang['Automatic scaling'] = "automatisk skalering";
$_lang['Legend'] = "symbol";
$_lang['No value'] = "ikke bestemt";
$_lang['Copy project branch'] = "kopi af projektgrem";
$_lang['Copy this element<br> (and all elements below)'] = "kopier dette element<br> (og alle elementer herunder)";
$_lang['And put it below this element'] = "og sæt det ind under dette element";
$_lang['Edit timeframe of a project branch'] = "redigér tidslinien for denne projektgren"; 

$_lang['of this element<br> (and all elements below)'] = "af dette element<br> (og alle elementer herunder)";  
$_lang['by'] = "af";
$_lang['Probability'] = "sandsynlighed";
$_lang['Please delete all subelements first'] = "Please delete all subprojects first";
$_lang['Assignment'] ="Assignment";
$_lang['display'] = "Display";
$_lang['Normal'] = "Normal";
$_lang['sort by date'] = "Sort by date";
$_lang['sort by'] = "Sort by";
$_lang['Calculated budget has a wrong format'] = "Calculated budget has a wrong format";
$_lang['Hourly rate has a wrong format'] = "Hourly rate has a wrong format";

// r.php
$_lang['please check the status!'] = "Kontroll&eacute;r statustal!";
$_lang['Todo List: '] = "opgaveliste: ";
$_lang['New Remark: '] = "opret ny note: ";
$_lang['Delete Remark '] = "slet note ";
$_lang['Keyword Search'] = "fuldteksts&oslash;gning: ";
$_lang['Events'] = "i aftaler";
$_lang['the forum'] = "i fora";
$_lang['the files'] = "i filer";
$_lang['Addresses'] = "adresser";
$_lang['Extended'] = "udvidet";
$_lang['all modules'] = "alle moduler";
$_lang['Bookmarks:'] = "bogm&aelig;rker:";
$_lang['List'] = "liste";
$_lang['Projects:'] = "projekter:";

$_lang['Deadline'] = "aftale";

$_lang['Polls:'] = "afstemninger:";

$_lang['Poll created on the '] = "afstemning er oprettet d. ";


// reminder.php
$_lang['Starts in'] = "begynder om";
$_lang['minutes'] = "minutter";
$_lang['No events yet today'] = "ingen opgaver/m&oslash;der i dag";
$_lang['New mail arrived'] = "ny email modtaget";

//ress.php

$_lang['List of Resources'] =  "ressourceliste";
$_lang['Name of Resource'] = "ressourcennavn";
$_lang['Comments'] =  "bem&aelig;rkning";


// roles
$_lang['Roles'] = "Ops&aelig;tning af roller";
$_lang['No access'] = "ingen adgang";
$_lang['Read access'] = "l&aelig;seadgang";

$_lang['Role'] = "rolle";

// helpdesk - rts
$_lang['Request'] = "foresp&oslash;rgsel";

$_lang['pending requests'] = "&aring;bne foresp&oslash;rgsler";
$_lang['show queue'] = "vis liste";
$_lang['Search the knowledge database'] = "s&oslash;g i vidensdatabase";
$_lang['Keyword'] = "stikord";
$_lang['show results'] = "vis resultater";
$_lang['request form'] = "stil foresp&oslash;rgsel";
$_lang['Enter your keyword'] = "indtast stikord";
$_lang['Enter your email'] = "indtast e-mail-adresse";
$_lang['Give your request a name'] = "emne p&aring; foresp&oslash;rgsel";
$_lang['Describe your request'] = "beskrivelse";

$_lang['Due date'] = "frist";
$_lang['Days'] = "dage";
$_lang['Sorry, you are not in the list'] = "du er ikke registreret.";
$_lang['Your request Nr. is'] = "dit foresp&oslash;rgselsnr. er ";
$_lang['Customer'] = "kunde";


$_lang['Search'] = "s&oslash;g";
$_lang['at'] = "i";
$_lang['all fields'] = "alle felter";


$_lang['Solution'] = "svar";
$_lang['AND'] = "OG";

$_lang['pending'] = "under udarbejdelse";
$_lang['stalled'] = "standset";
$_lang['moved'] = "udsat";
$_lang['solved'] = "besvaret";
$_lang['Submit'] = "dato";
$_lang['Ass.'] = "i";
$_lang['Pri.'] = "pri.";
$_lang['access'] = "adgang";
$_lang['Assigned'] = "tildeldt";

$_lang['update'] = "opdatering";
$_lang['remark'] = "bem&aelig;rkning";
$_lang['solve'] = "svar";
$_lang['stall'] = "stoppe";
$_lang['cancel'] = "tilbage";
$_lang['Move to request'] = "flyt til foresp&oslash;rgsel";
$_lang['Dear customer, please refer to the number given above by contacting us.Will will perform your request as soon as possible.'] = "Venligst oplys dette nummer, hvis du har sp&oslash;rgsm&aring;l.
Vi vil svare s&aring; hurtigt som muligt.";
$_lang['Your request has been added into the request queue.<br>You will receive a confirmation email in some moments.'] = "Din foresp&oslash;rgsel er registreret.<br>
om lidt f&aring;r du en bekr&aelig;ftelse pr. e-mail.";
$_lang['n/a'] = "ikke tilg&ealig;ngelig";
$_lang['internal'] = "intern";

$_lang['has reassigned the following request'] = "har tildelt foresp&oslash;rgslen p&aring; ny.";
$_lang['New request'] = "ny foresp&oslash;rgsel";
$_lang['Assign work time'] = "tildel arbejdstid";
$_lang['Assigned to:'] = "tildelt til:";

$_lang['Your solution was mailed to the customer and taken into the database.'] = "Din l&oslash;sning er blevet sendt til kunden og tilf&oslash;jet i databasen.";
$_lang['Answer to your request Nr.'] = "Svar p&aring; foresp&oslash;rgsel nr. ";
$_lang['Fetch new request by mail'] = "Hent nye foresp&oslash;rgsler via mail.";
$_lang['Your request was solved by'] = "Dit foresp&oslash;rgsel blev behandlet af";

$_lang['Your solution was mailed to the customer and taken into the database'] = "Din l&oslash;sning blev sendt til kunden og optaget i databasen.";
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
$_lang['The settings have been modified'] = "ops&aelig;tning er &aelig;ndret";
$_lang['Skin'] = "skin";
$_lang['First module view on startup'] = "modul der vises ved opstart";
$_lang['none'] = "intet/ingen";
$_lang['Check for mail'] = "se efter ny email";
$_lang['Additional alert box'] = "ekstra p&aring;mindelses vindue";
$_lang['Horizontal screen resolution <br>(i.e. 1024, 800)'] = "vandret opl&oslash;sning p&aring; sk&aelig;rmen (i pixel) <br>(f.eks. 1024, 800)";
$_lang['Chat Entry'] = "chat indl&aelig;g";
$_lang['single line'] = "enkelt linie";
$_lang['multi lines'] = "flere linier";
$_lang['Chat Direction'] = "chat-retning";
$_lang['Newest messages on top'] = "nyeste meddelelser i toppen";
$_lang['Newest messages at bottom'] = "nyeste meddelelser i bunden";
$_lang['File Downloads'] = "download af filer";

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
$_lang['Todays Events'] = "aftaler idag";
$_lang['New files'] = "nye filer";
$_lang['New notes'] = "nye noter";
$_lang['New Polls'] = "nye afstemningsresultater";
$_lang['Current projects'] = "igangv&aelig;rende projekter";
$_lang['Help Desk Requests'] = "helpdesk foresp&oslash;rgsler";
$_lang['Current todos'] = "udest&aring;ende opgaver";
$_lang['New forum postings'] = "nye indl&aelig;g i fora";
$_lang['New Mails'] = "nye emails";

//timecard

$_lang['Theres an error in your time sheet: '] = "Fejl i arbejdstidstabel. Venligst kontroll&eacute;r din tidsreg..";




$_lang['Consistency check'] = "konsistenskontrol";
$_lang['Please enter the end afterwards at the'] = "Husk at indtaste arbejdstid slut";
$_lang['insert'] = "indtast";
$_lang['Enter records afterwards'] = "Opdat&eacute;r tidsangivelser";
$_lang['Please fill in only emtpy records'] = "Venligst udfyld kun tomme felter";

$_lang['Insert a period, all records in this period will be assigned to this project'] = "Indtast et tidsrum, alle tidsangivelser tilskrives det valgte projekt.";
$_lang['There is no record on this day'] = "Forkert dato.";
$_lang['This field is not empty. Please ask the administrator'] = "Feltet er ikke tomt. Henvend dig til administratoren.";
$_lang['There is no open record with a begin time on this day!'] = "Fejl i datoangivelsen! Venligst kontroll&eacute;r.";
$_lang['Please close the open record on this day first!'] = "Indtast f&oslash;rst starttiden";
$_lang['Please check the given time'] = "Venligst kontroll&eacute;r tidsangivelsen.";
$_lang['Assigning projects'] = "Tildeling til flere projekter";
$_lang['Select a day'] = "valg af dag";
$_lang['Copy to the boss'] = "kopi til chefen";
$_lang['Change in the timecard'] = "tidskortmodifikation";
$_lang['Sum for'] = "sum for";

$_lang['Unassigned time'] = "ikke tildelt tid";
$_lang['delete record of this day'] = "slet angivelsen for denne dag";
$_lang['Bookings'] = "reservationer";

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
$_lang['accepted'] = "godkendt";
$_lang['rejected'] = "afvist";
$_lang['own'] = "egen";
$_lang['progress'] = "gennemf&oslash;rsel";
$_lang['delegated to'] = "overgivet til";
$_lang['Assigned from'] = "tildelt af";
$_lang['done'] = "udf&oslash;";
$_lang['Not yet assigned'] = "ikke tildelt endnu";
$_lang['Undertake'] = "foretag";
$_lang['New todo'] = "ny opgave"; 
$_lang['Notify recipient'] = "underret modtager";

// votum.php
$_lang['results of the vote: '] = "afstemningsresultater: ";
$_lang['Poll Question: '] = "afstemningssp&oslash;rgsm&aring;l: ";
$_lang['several answers possible'] = "(flere valg muligt)";
$_lang['Alternative '] = "mulighed ";
$_lang['no vote: '] = "ingen stemme: ";
$_lang['of'] = "af";
$_lang['participants have voted in this poll'] = "adspurgte har afgivet svar.";
$_lang['Current Open Polls'] = "ikke afgivne stemmer";
$_lang['Results of Polls'] = "resultatlist for alle stemmer";
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