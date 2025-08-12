<?php
// ro.inc.php, versiunea in limba romana
// traducere de Ciolacu Ioan Marius <mariusc@uab.ro> and Lia Despa <lia@idd.uab.ro>

$chars = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
$name_month = array("", "January", "February", "March", "April", "May", "June", "July", "August", "September", "Octomber", "November", "December");
$l_text31a = array("implicit", "15 min.", "30 min.", " o ora", " doua ore", " patru ore", " o zi");
$l_text31b = array(0, 15, 30, 60, 120, 240, 1440);
$name_day = array("Duminica", "Luni", "Marti", "Miercuri", "Joi", "Vineri", "Sambata");
$name_day2 = array("Lu", "Ma", "Mi", "Jo", "Vi", "Sa","Du");

$_lang['No Entries Found']= "No Entries Found";
$_lang['No Todays Events']= "HNo Todays Events";
$_lang['No new forum postings']= "No new forum postings";
$_lang['in category']= "in category";
$_lang['Filtered']= "Filtered";
$_lang['Sorted by']= "Sorted by";
$_lang['go'] = "executa";
$_lang['back'] = "inapoi";
$_lang['print'] = "tipareste";
$_lang['export'] = "exporta";
$_lang['| (help)'] = "| (ajutor)";
$_lang['Are you sure?'] = "Sunteti sigur?";
$_lang['items/page'] = "obiecte pe pagina";
$_lang['records'] = "records";
$_lang['previous page'] = "pagina anterioara";
$_lang['next page'] = "pagina urmatoare";
$_lang['first page'] = "first page";
$_lang['last page'] = "last page";
$_lang['Move']  = "Muta";
$_lang['Copy'] = "Copiaza";
$_lang['Delete'] = "Sterge";
$_lang['Save'] = "save";
$_lang['Directory'] = "Directory";
$_lang['Also Delete Contents'] = "delete also contents";
$_lang['Sum'] = "Sum";
$_lang['Filter'] = "Filter";
$_lang['Please fill in the following field'] = "Please fill in the following field";
$_lang['approve'] = "approve";
$_lang['undo'] = "undo";
$_lang['Please select!']="Please select!";
$_lang['New'] = "New";
$_lang['Select all'] = "Select all";
$_lang['Printable view'] = "Printable view";
$_lang['New record in module '] = "New record in module ";
$_lang['Notify all group members'] = "Notify all group members";
$_lang['Yes'] = "Yes";
$_lang['No'] = "No";
$_lang['Close window'] = "Close window";
$_lang['No Value'] = "No Value"; 
$_lang['Standard'] = "Standard";
$_lang['Create'] = "Anlegen";
$_lang['Modify'] = "Ändern";   
$_lang['today'] = "today";

// admin.php
$_lang['Password'] = "Parola";
$_lang['Login'] = "Login";
$_lang['Administration section'] = "Sectiunea de administrare";
$_lang['Your password'] = "Parola dumneavoastra";
$_lang['Sorry you are not allowed to enter. '] = "Ne pare rau, dar nu aveti permisiunea de a intra. ";
$_lang['Help'] = "Ajutor";
$_lang['User management'] = "Administrarea userilor";
$_lang['Create'] = "Creeaza";
$_lang['Projects'] = "Proiecte";
$_lang['Resources'] = "Resurse";
$_lang['Resources management'] = "Resources management";
$_lang['Bookmarks'] = "Bookmark-uri";
$_lang['for invalid links'] = "pentru link-uri invalide";
$_lang['Check'] = "Verifica";
$_lang['delete Bookmark'] = "sterge Bookmark";
$_lang['(multiple select with the Ctrl-key)'] = "(selectare multipla cu tasta 'Ctrl')";
$_lang['Forum'] = "Forum";
$_lang['Threads older than'] = "Tematica mai veche decat";
$_lang[' days '] = " zile ";
$_lang['Chat'] = "Chat";
$_lang['save script of current Chat'] = "salveaza scriptul Chatului actual";
$_lang['Chat script'] = "script Chat";
$_lang['New password'] = "Noua parola";
$_lang['(keep old password: leave empty)'] = "(pastrarea vechii parole: lasati gol)";
$_lang['Default Group<br> (must be selected below as well)'] = "Grupul implicit<br> (trebuie selectat mai jos de asemenea)";
$_lang['Access rights'] = "Drepturi de acces";
$_lang['Zip code'] = "Codul postal";
$_lang['Language'] = "Limba";$_lang['schedule readable to others'] = "orar citibil de ceilalti";
$_lang['schedule readable to others'] = "schedule readable to others";
$_lang['schedule invisible to others'] = "schedule invisible to others";
$_lang['schedule visible but not readable'] = "schedule visible but not readable";
$_lang['these fields have to be filled in.'] = "aceste campuri trebuie completate.";
$_lang['You have to fill in the following fields: family name, short name and password.'] = "Trebuie sa completati urmatoarele campuri: numele de familie, nume prescurtat si parola.";
$_lang['This family name already exists! '] = "Acest nume de famile exista deja! ";
$_lang['This short name already exists!'] = "Acest nume prescurtat exista deja!";
$_lang['This login name already exists! Please chosse another one.'] = "This login name already exists! Please chosse another one.";
$_lang['This password already exists!'] = "Aceasta parola exista deja!";
$_lang['This combination first name/family name already exists.'] = "Aceasta combinatie prenume/nume exista deja.";
$_lang['the user is now in the list.'] = "user-ul a fost adaugat in lista.";
$_lang['the data set is now modified.'] = "data a fost modificata.";
$_lang['Please choose a user'] = "Va rugam alegeti un user";
$_lang['is still listed in some projects. Please remove it.'] = "se afla inca in unele din proiecte. Va rugam, stergeti-l.";
$_lang['All profiles are deleted'] = "Toate profilele sunt sterse";
$_lang['A Profile with the same name already exists'] = "A profile with the same name already exists";
$_lang['is taken out of all user profiles'] = "este scos din toate profilele";
$_lang['All todo lists of the user are deleted'] = "Toate listele cu ce are utilizatorul de facut au fost sterse";
$_lang['is taken out of these votes where he/she has not yet participated'] = "este scos din acele votari unde el/ea inca nu a participat";
$_lang['All events are deleted'] = "Toate evenimentele au fost sterse";
$_lang['user file deleted'] = "fisierele utilizatorului au fost sterse";
$_lang['bank account deleted'] = "conturile de banca au fost sterse";
$_lang['finished'] = "terminat";
$_lang['Please choose a project'] = "Va rugam alegeti un proiect";
$_lang['The project is deleted'] = "Proiectul a fost sters";
$_lang['All links in events to this project are deleted'] = "Toate legaturile dintre evenimentele acestui proiect au fost sterse";
$_lang['The duration of the project is incorrect.'] = "Durata proiectului este incorecta.";
$_lang['The project is now in the list'] = "Proiectul a fost adaugat in lista";
$_lang['The project has been modified'] = "Proiectul a fost modificat";
$_lang['Please choose a resource'] = "Va rugam alegeti o resursa";
$_lang['The resource is deleted'] = "Resursa a fost stearsa stearsa";
$_lang['All links in events to this resource are deleted'] = "Toate legaturile dintre evenimentele acestei resurse au fost sterse";
$_lang[' The resource is now in the list.'] = " Resursa a fost adaugata in lista.";
$_lang[' The resource has been modified.'] = " Resursa a fost modificata.";
$_lang['The server sent an error message.'] = "Serverul a trimis un mesaj de eroare.";
$_lang['All Links are valid.'] = "Toate Link-urile sunt active.";
$_lang['Please select at least one bookmark'] = "Va rugam sa alegeti cel putin un bookmark";
$_lang['The bookmark is deleted'] = "Bookmark-ul a fost sters";
$_lang['threads older than x days are deleted.'] = "tematicile mai vechi decat x zile sunt sterse.";
$_lang['All chat scripts are removed'] = "Toate scripturile de chat au fost sterse";
$_lang['or'] = "sau";
$_lang['Timecard management'] = "Administrarea condicii";
$_lang['View'] = "Afiseaza";
$_lang['Choose group'] = "Alege grup";
$_lang['Group name'] = "Numele grupului";
$_lang['Short form'] = "Nume prescurtat";
$_lang['Category'] = "Categoria";
$_lang['Remark'] = "Remarca";
$_lang['Group management'] = "Administrarea grupului";
$_lang['Please insert a name'] = "Va rugam introduceti un nume";
$_lang['Name or short form already exists'] = "Numele sau numele prescurtat exista";
$_lang['Automatic assign to group:'] = "Adauga automat la grup:";
$_lang['Automatic assign to user:'] = "Adauga automat la user:";
$_lang['Help Desk Category Management'] = "managementul categoriei Helpdesk";
$_lang['Category deleted'] = "Categoria a fost stearsa";
$_lang['The category has been created'] = "Categoria a fost creata";
$_lang['The category has been modified'] = "Categoria a fost modificata";
$_lang['Member of following groups'] = "Membru al urmatoarelor grupuri";
$_lang['Primary group is not in group list'] = "Grupul primar nu este in lista grupurilor";
$_lang['Login name'] = "Numele de conectare (login)";
$_lang['You cannot delete the default group'] = "Nu puteti sterge grupul implicit";
$_lang['Delete group and merge contents with group'] = "Sterge grupul si combina continutul cu grupul";
$_lang['Please choose an element'] = "Va rugam alegeti un element";
$_lang['Group created'] = "Grupul a fost creat";
$_lang['File management'] = "Administrarea fisierelor";
$_lang['Orphan files'] = "Fisiere fara proprietar";
$_lang['Deletion of super admin root not possible'] = "Stergerea root-ului nu este posibila";
$_lang['ldap name'] = "ldap name";
$_lang['mobile // mobile phone'] = "mobil"; // mobil phone
$_lang['Normal user'] = "Normal user";
$_lang['User w/Chief Rights'] = "User with chief rights";
$_lang['Administrator'] = "Administrator";
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
$_lang['Contact Manager'] = "Administrator de contact";
$_lang['New contact'] = "Stabileste un nou contact";
$_lang['Group members'] = "Membrii grupului";
$_lang['External contacts'] = "Contacte externe";
$_lang['&nbsp;New&nbsp;'] = "&nbsp;Nou&nbsp;";
$_lang['Import'] = "Importa";
$_lang['The new contact has been added'] = "Noul contact a fost adaugat";
$_lang['The date of the contact was modified'] = "Data contactului a fost modificata";
$_lang['The contact has been deleted'] = "Contactul a fost sters";
$_lang['Open to all'] = "Deschis tuturor";
$_lang['Picture'] = "Imagine";
$_lang['Please select a vcard (*.vcf)'] = "Selectati un vcard (*.vcf)";
$_lang['create vcard'] = "Creeaza vcard";
$_lang['import address book'] = "Importa lista de adrese";
$_lang['Please select a file (*.csv)'] = "Va rugam selectati un fisier (*.csv)";
$_lang['Howto: Open your outlook express address book and select file/export/other book<br>Then give the file a name, select all fields in the next dialog and finish'] = "Cum sa: Deschideti in  outlook express lista de adrese si selectati 'file'/'export'/'other book'<br>
apoi dati fisierului un nume, selectati toate campurile in dialogul urmator si 'finish'";
$_lang['Open outlook at file/export/export in file,<br>choose comma separated values (Win), then select contacts in the next form,<br>give the export file a name and finish.'] = "Deschideti outlook la 'file/export/export in file',<br>
alegeti 'comma separated values (Win)', apoi selectati 'contacts' sub forma urmatoare,<br>
dati fisierului exportat un nume si apoi finish.";
$_lang['Please choose an export file (*.csv)'] = "Va rugam selectati un fisier exportat  (*.csv)";
$_lang['Please export your address book into a comma separated value file (.csv), and either<br>1) apply an import pattern OR<br>2) modify the columns of the table with a spread sheet to this format<br>(Delete colums in you file that are not listed here and create empty colums for fields that do not exist in your file):'] = "Please export your address book into a comma separated value file (.csv), and either<br>
1) apply an import pattern OR<br>
2) modify the columns of the table with a spread sheet to this format<br>
(Delete colums in you file that are not listed here and create empty colums for fields that do not exist in your file):";
$_lang['Please insert at least the family name'] = "Va rod introduceti cel putin numele de familie";
$_lang['Record import failed because of wrong field count'] = "Inscrierea inregistrarii nu s-a facut deoarece numarul de campuri este gresit";
$_lang['Import to approve'] = "Import to approve";
$_lang['Import list'] = "Import list";
$_lang['The list has been imported.'] = "The list has been imported.";
$_lang['The list has been rejected.'] = "The list has been rejected.";
$_lang['Profiles'] = "Profiles";
$_lang['Parent object'] = "Parent object";
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

// dateien.php
$_lang['Please select a file'] = "Va rugam selectati fisier-ul";
$_lang['A file with this name already exists!'] = "Un fisier cu acest nume exista deja!";
$_lang['Name'] = "Numele";
$_lang['Comment'] = "Comentariu";
$_lang['Date'] = "Data";
$_lang['Upload'] = "Pune pe server";
$_lang['Filename and path'] = "Numele si calea fisierului";
$_lang['Delete file'] = "Sterge fisierul";
$_lang['Overwrite'] = "Scrie peste";
$_lang['Access'] = "Acces";
$_lang['Me'] = "mie";
$_lang['Group'] = "group";
$_lang['Some'] = "catorva";
$_lang['As parent object'] = "same as directory";
$_lang['All groups'] = "All groups";
$_lang['You are not allowed to overwrite this file since somebody else uploaded it'] = "Nu ai dreptul sa inlocuiesti acest fisier in timp ce o alta persoana il reactualizeaza";
$_lang['personal'] = "personal";
$_lang['Link'] = "Link";
$_lang['name and network path'] = "numele si calea retelei";
$_lang['with new values'] = "cu valori noi";
$_lang['All files in this directory will be removed! Continue?'] = "Toate fisierele din acest director vor fi sterse! Continuati?";
$_lang['This name already exists'] = "Acest nume exista deja";
$_lang['Max. file size'] = "dimensiunea maxima a fisierului";
$_lang['links to'] = "legatura spre";
$_lang['objects'] = "obiecte";
$_lang['Action in same directory not possible'] = "Actiune imposibila in acelasi director";
$_lang['Upload = replace file'] = "Upload = replace file";
$_lang['Insert password for crypted file'] = "Insert password for crypted file";
$_lang['Crypt upload file with password'] = "Crypt file with password";
$_lang['Repeat'] = "Repeat";
$_lang['Passwords dont match!'] = "Passwords don't match!";
$_lang['Download of the password protected file '] = "Download of the password protected file ";
$_lang['notify all users with access'] = "notify all users with access";
$_lang['Write access'] = "Write access";
$_lang['Version'] = "Version";
$_lang['Version management'] = "Version management";
$_lang['lock'] = "lock";
$_lang['unlock'] = "unlock";
$_lang['locked by'] = "locked by";
$_lang['Alternative Download'] = "Alternative Download";
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
$_lang['Welcome to the setup of PHProject!<br>'] = "Bine ati venit in setup-ul PHProject!<br>";
$_lang['Please remark:<ul><li>A blank database must be available<li>Please ensure that the webserver is able to write the file config.inc.php'] = "Va rugam remarcati:<ul>
<li>Trebuie sa fie disponibila o baza de date goala
<li>Va rugam asigurati-va ca serverul de web poate scrie fisierul 'config.inc.php'<br> (e.g. 'chmod 777')";
$_lang['<li>If you encounter any errors during the installation, please look into the <a href=help/faq_install.html target=_blank>install faq</a>or visit the <a href=http://www.PHProjekt.com/forum.html target=_blank>Installation forum</a></i>'] = "<li>Daca intimpinati erori in timpul instalarii, va rugam verificati <a href='help/faq_install.html' target=_blank>install faq</a>
 sau vizitati <a href='http://www.PHProjekt.com/forum.html' target=_blank>Installation forum</a></i>";
$_lang['Please fill in the fields below'] = "Va rugam completati campurile de mai jos";
$_lang['(In few cases the script wont respond.<br>Cancel the script, close the browser and try it again).<br>'] = "(In unele cazuri script-ul nu raspunde.<br>
Anulati script-ul, inchideti browser-ul si incercati din nou).<br>";
$_lang['Type of database'] = "Tipul bazei de date";
$_lang['Hostname'] = "Numele gazdei";
$_lang['Username'] = "Numele utilizatorului";

$_lang['Name of the existing database'] = "Numele bazei de date existente";
$_lang['config.inc.php not found! Do you really want to update? Please read INSTALL ...'] = "config.inc.php nu a fost gasit! Doriti intradevar sa reactualizati? Va rugam cititi INSTALL ...";
$_lang['config.inc.php found! Maybe you prefer to update PHProject? Please read INSTALL ...'] = "config.inc.php a fost gasit! Poate doriti sa actualizati PHProject? Va rugam cititi INSTALL ...";
$_lang['Please choose Installation,Update or Configure!'] = "Va rugam alegeti 'Installation' sau 'Update'! back ...";
$_lang['Sorry, I cannot connect to the database! <br>Please close all browser windows and restart the installation.'] = "Ne pare rau, nu functioneaza! <br>Va rugam reparati si reinstalati.";
$_lang['Sorry, it does not work! <br> Please set DBDATE to Y4MD- or let phprojekt change this environment-variable (php.ini)!'] = "Ne pare rau, nu functioneaza! <br> Va rugam setati DBDATE la 'Y4MD-' sau lasati phprojekt sa modifice aceasta variabila (php.ini)!";
$_lang['Seems that You have a valid database connection!'] = "Se pare ca aveti o conexiune la baza de date valida!";
$_lang['Please select the modules you are going to use.<br> (You can disable them later in the config.inc.php)<br>'] = "Va rugam selectati modulele pe care le veti utiliza.<br> (Le puteti dezactiva mai tarziu in fisierul config.inc.php)<br>";
$_lang['Install component: insert a 1, otherwise keep the field empty'] = "Instalati componenta: introduceti valoarea '1', altfel pastrati campul gol";
$_lang['Group views'] = "Vizualizarea grupurilor";
$_lang['Todo lists'] = "Lista de actiuni";

$_lang['Voting system'] = "Sistemul de votare";


$_lang['Contact manager'] = "Managerul de contacte";
$_lang['Name of userdefined field'] = "Name of userdefined field";
$_lang['Userdefined'] = "Userdefined";
$_lang['Profiles for contacts'] = "Profiles for contacts";
$_lang['Mail'] = "Mail";
$_lang['send mail'] = " send mail";
$_lang[' only,<br> &nbsp; &nbsp; full mail client'] = " only,<br> &nbsp; &nbsp; full mail client";



$_lang['1 to show appointment list in separate window,<br>&nbsp; &nbsp; 2 for an additional alert.'] = "'1' pentru a vizualiza lista cu programarile intr-o fereastra separata,<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'2' pentru o alerta in plus.";
$_lang['Alarm'] = "Alarma";
$_lang['max. minutes before the event'] = "Numarul maxim de minute inaintea evenimentului";
$_lang['SMS/Mail reminder'] = "SMS/Mail reminder";
$_lang['Reminds via SMS/Email'] = "Reminds via SMS/Email";
$_lang['1= Create projects,<br>&nbsp; &nbsp; 2= assign worktime to projects only with timecard entry<br>&nbsp; &nbsp; 3= assign worktime to projects without timecard entry<br>&nbsp; &nbsp; (Selection 2 or 3 only with module timecard!)'] = "'1'= Create projects,<br>
&nbsp; &nbsp; '2'= assign worktime to projects only with timecard entry<br>
&nbsp; &nbsp; '3'= assign worktime to projects without timecard entry<br> ";

$_lang['Name of the directory where the files will be stored<br>( no file management: empty field)'] = "Numele directorului unde fisierele vor fi stocate<br>( no file management: camp gol)";
$_lang['absolute path to this directory (no files = empty field)'] = "calea absoluta spre acest director (nici un fisier = camp gol)";
$_lang['Time card'] = "Time card";
$_lang['1 time card system,<br>&nbsp; &nbsp; 2 manual insert afterwards sends copy to the chief'] = "'1' time card system,<br>
&nbsp;&nbsp;'2' manual insert afterwards sends copy to the chief";
$_lang['Notes'] = "Notite";
$_lang['Password change'] = "Schimbarea parolei";
$_lang['New passwords by the user - 0: none - 1: only random passwords - 2: choose own'] = "Noile parole de utilizator - 0: niciuna - 1: numai parole aleatoare - 2: choose own";
$_lang['Encrypt passwords'] = "Decriptarea parolei";
$_lang['Login via '] = "Login via ";
$_lang['Extra page for login via SSL'] = "Extra page for login via SSL";
$_lang['Groups'] = "Gruppen";
$_lang['User and module functions are assigned to groups<br>&nbsp; &nbsp; (recommended for user numbers > 40)'] = "User and module functions are assigned to groups<br>
&nbsp;&nbsp;&nbsp;&nbsp;(recommended for user numbers > 40)";
$_lang['User and module functions are assigned to groups'] = "User and module functions are assigned to groups";
$_lang['Help desk'] = "Help desk";
$_lang['Help Desk Manager / Trouble Ticket System'] = "Help Desk Manager / Trouble Ticket System";
$_lang['RT Option: Customer can set a due date'] = "RT Option: Customer can set a due date";
$_lang['RT Option: Customer Authentification'] = "RT Option: Autentificarea clientului";
$_lang['0: open to all, email-address is sufficient<br>1: registered contact enter his family name<br>2: his email'] = "0: deschis tuturor, adresa de email este suficienta, 1: clientul trebuie sa fie in lista de contacte si sa introduca numele de familie";
$_lang['RT Option: Assigning request'] = "RT Option: Alocarea cererii";
$_lang['0: by everybody, 1: only by persons with status chief'] = "0: tuturor, 1: numai persoanelor cu statutul de 'administrator'";
$_lang['Email Address of the support'] = "Email Adress of the support";
$_lang['Scramble filenames'] = "Nume de fisiere aleatoare";
$_lang['creates scrambled filenames on the server<br>assigns previous name at download'] = "Creati nume de fisiere aleatoare pe server<br>
asignati numele anterior pentru download";

$_lang['0: last name, 1: short name, 2: login name'] = "0: last name, 1: short name, 2: login name";
$_lang['Prefix for table names in db'] = "Prefix for table names in db";
$_lang['Alert: Cannot create file config.inc.php!<br>Installation directory needs rwx access for your server and rx access to all others.'] = "Alerta: Nu puteti crea fisierul 'config.inc.php'!<br>
Instalarea directorului are nevoie de acces la citire, scriere si executie pentru serverul dvs. si acces citire/executie la toate celelalte.";
$_lang['Location of the database'] = "Calea spre baza de date";
$_lang['Type of database system'] = "Tipul bazei de date";
$_lang['Username for the access'] = "Numele utilizatorului pentru acces";
$_lang['Password for the access'] = "Parola pentru acces";
$_lang['Name of the database'] = "Numele bazei de date";
$_lang['Prefix for database table names'] = "Prefix for database table names";
$_lang['First background color'] = "Prima culoare pentru background";
$_lang['Second background color'] = "A doua culoare pentru background";
$_lang['Third background color'] = "A treia culoare pentru background";
$_lang['Color to mark rows'] = "Color to mark rows";
$_lang['Color to highlight rows'] = "Color to highlight rows";
$_lang['Event color in the tables'] = "Culori eveniment in tabele";
$_lang['company icon yes = insert name of image'] = "logo-ul pentru companie da = introduceti numele imaginii";
$_lang['URL to the homepage of the company'] = "URL to the homepage of the company";
$_lang['no = leave empty'] = "nu = lasa gol";
$_lang['First hour of the day:'] = "Prima ora din zi:";
$_lang['Last hour of the day:'] = "Ultima ora din zi:";
$_lang['An error ocurred while creating table: '] = "A aparut o eroare in timpul crearii tabelei: ";
$_lang['Table dateien (for file-handling) created'] = "Tabel'dateien' (for file-handling) created";
$_lang['File management no = leave empty'] = "File management no = leave empty";
$_lang['yes = insert full path'] = "da = introduce calea in intregime";
$_lang['and the relative path to the PHProjekt directory'] = "and additionally the relative path to the root";
$_lang['Table profile (for user-profiles) created'] = "Tabel 'profil' (pentru profile de utilizator) creat";
$_lang['User Profiles yes = 1, no = 0'] = "profile da = 1, nu = 0";
$_lang['Table todo (for todo-lists) created'] = "Tabel 'planificari' (pentru lista de planificari) creat";
$_lang['Todo-Lists yes = 1, no = 0'] = "Listele de planificari da = 1, nu = 0";
$_lang['Table forum (for discssions etc.) created'] = "Tabel 'forum' (pentru discutii etc.) creat";
$_lang['Forum yes = 1, no = 0'] = "Forum da = 1, nu = 0";
$_lang['Table votum (for polls) created'] = "Tabel 'votare' (pentru voturi) creat";
$_lang['Voting system yes = 1, no = 0'] = "Sistemul de votare da = 1, nu = 0";
$_lang['Table lesezeichen (for bookmarks) created'] = "Tabel 'bookmark-uri' (pentru bookmark-uri) creat";
$_lang['Bookmarks yes = 1, no = 0'] = "Bookmark-uri da = 1, nu = 0";
$_lang['Table ressourcen (for management of additional ressources) created'] = "Tabel 'resurse' (pentru managementul resurselor aditionale) creat";
$_lang['Resources yes = 1, no = 0'] = "Resurse da= 1, nu = 0";
$_lang['Table projekte (for project management) created'] = "Tabel 'proiecte' (pentru managementul de proiect) creat";
$_lang['Table contacts (for external contacts) created'] = "Tabel 'contacte' (pentru contacte externe)creat";
$_lang['Table notes (for notes) created'] = "Tabel 'notite' (pentru notite) creat";
$_lang['Table timecard (for time sheet system) created'] = "Tabel 'timecard' (pentru sistemul foii de timp) creat";
$_lang['Table groups (for group management) created'] = "Tabel 'grupuri' (pentru managementul de grup) creat";
$_lang['Table timeproj (assigning work time to projects) created'] = "Tabel timeproj (alocarea timpului de lucru la proiecte) creat";
$_lang['Table rts and rts_cat (for the help desk) created'] = "Tabel 'rts' si 'rts_cat' (pentru sistemul de cereri) creat";
$_lang['Table mail_account, mail_attach, mail_client und mail_rules (for the mail reader) created'] = "Table mail_account, mail_attach, mail_client und mail_rules (for the mail reader) created";
$_lang['Table logs (for user login/-out tracking) created'] = "Table logs (for user login/-out tracking) created";
$_lang['Tables contacts_profiles und contacts_prof_rel created'] = "Tables contacts_profiles und contacts_prof_rel created";
$_lang['Project management yes = 1, no = 0'] = "Managementul de proiect da = 1, nu = 0";
$_lang['additionally assign resources to events'] = "alocarea aditionala a resurselor la evenimente";
$_lang['Address book  = 1, nein = 0'] = "Lista de adrese da = 1, nu = 0";
$_lang['Mail no = 0, only send = 1, send and receive = 2'] = "Mail-uri da = 1, nu = 0";
$_lang['Chat yes = 1, no = 0'] = "Chat yes = 1, no = 0";
$_lang['Name format in chat list'] = "Name format in chat list";
$_lang['0: last name, 1: first name, 2: first name, last name,<br> &nbsp; &nbsp; 3: last name, first name'] = "0: last name, 1: first name, 2: first name, last name,<br> &nbsp; &nbsp; 3: last name, first name";
$_lang['Timestamp for chat messages'] = "Timestamp for chat messages";
$_lang['users (for authentification and address management)'] = "'utilizatori' (pentru autentificarea si managementul adreselor)";
$_lang['Table termine (for events) created'] = "'Tabel de final' (pentru evenimente) creat";
$_lang['The following users have been inserted successfully in the table user:<br>root - (superuser with all administrative privileges)<br>test - (chief user with restricted access)'] = "Urmatorii utilizatori au fost introdusi cu succes in tabel 'utilizator':<br>
'root' - (utilizator cu toate drepturile de administrator)<br>
'test' - (utilizator cu acces restrictiv)";
$_lang['The group default has been created'] = "Grupul 'Implicit' a fost creat";
$_lang['Please do not change anything below this line!'] = "Va rugam nu schimbati nimic pana la acesta linie!";
$_lang['Database error'] = "Eroare de baza de date";
$_lang['Finished'] = "Terminat";
$_lang['There were errors, please have a look at the messages above'] = "Au fost erori, va rugam verificati mesajele de deasupra";
$_lang['All required tables are installed and <br>the configuration file config.inc.php is rewritten<br>It would be a good idea to makea backup of this file.<br>'] = "Toate tabelele cerute sunt instalate si <br>
configurarea fisierului 'config.inc.php' este rescrisa<br>
Ar fi o idee buna daca ati face o copie la acest fisier.<br>
Inchideti toate browser-ele de sub Windows acum.<br>";
$_lang['The administrator root has the password root. Please change his password here:'] = "The administrator 'root' has the password 'root'. Please change his password here:";
$_lang['The user test is now member of the group default.<br>Now you can create new groups and add new users to the group'] = "Utilizatorul 'test' este acum membrul grupului 'implicit'.<br>
acum puteti crea grupuri noi si sa adaugati noi utilizatori grupului";
$_lang['To use PHProject with your Browser go to <b>index.php</b><br>Please test your configuration, especially the modules Mail and Files.'] = "Pentru a utiliza PHProject cu browser-ul dvs. du-te la  <b>index.php</b><br>
Testati configuratia dvs., in special modulele 'Quickmail' si 'Fisierele'.";

$_lang['Alarm x minutes before the event'] = "Alarma x minute inainte de eveniment";
$_lang['Additional Alarmbox'] = "Additional Alarmbox";
$_lang['Mail to the chief'] = "Mail spre administrator";
$_lang['Out/Back counts as: 1: Pause - 0: Workingtime'] = "Afara/Inapoi contorizat ca: 1: Pauza - 0: Timp de lucru";
$_lang['Passwords will now be encrypted ...'] = "Parolele vor fi decriptate acum";
$_lang['Filenames will now be crypted ...'] = "Filenames will now be crypted ...";
$_lang['Do you want to backup your database right now? (And zip it together with the config.inc.php ...)<br>Of course I will wait!'] = "Doriti sa salvati baza de date acum? (si arhivati-o cu config.inc.php ...)<br>
Voi astepta binenteles!";
$_lang['Next'] = "Urmatorul";
$_lang['Notification on new event in others calendar'] = "Notificarea unui nou eveniment in alte calendare";
$_lang['Path to sendfax'] = "Calea spre trimiterea unui fax";
$_lang['no fax option: leave blank'] = "nici o optiune de fax: lasa-l gol";
$_lang['Please read the FAQ about the installation with postgres'] = "Va rugam cititi FAQ despre instalarea cu postgres";
$_lang['Length of short names<br> (Number of letters: 3-6)'] = "Lungimea numelor scurte<br> (Numarul de litere: 3-6)";
$_lang['If you want to install PHProjekt manually, you find<a href=http://www.phprojekt.com/files/sql_dump.tar.gz target=_blank>here</a> a mysql dump and a default config.inc.php'] = "Daca doriti sa instalati PHProjekt manual, gasiti
<a href='http://www.phprojekt.com/files/sql_dump.tar.gz' target=_blank>here</a> a mysql dump and a default config.inc.php";
$_lang['The server needs the privilege to write to the directories'] = "Serverul are nevoie de dreptul de a 'scrie' in director";
$_lang['Header groupviews'] = "Header groupviews";
$_lang['name, F.'] = "nume, Fisier";
$_lang['shortname'] = "numele scurt";
$_lang['loginname'] = "Numele de acces";
$_lang['Please create the file directory'] = "Creati directorul";
$_lang['default mode for forum tree: 1 - open, 0 - closed'] = "modul implicit pentru arborele de forum: 1 - deschis, 0 - inchis";
$_lang['Currency symbol'] = "Currency symbol";
$_lang['current'] = "current";
$_lang['Default size of form elements'] = "Default size of form elements";
$_lang['use LDAP'] = "use LDAP";
$_lang['Allow parallel events'] = "Allow parallel events";
$_lang['Timezone difference [h] Server - user'] = "Timezone difference [h] Server - user";
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
$_lang['Resource List'] = "Lista de resurse";
$_lang['Event List'] = "Lista de evenimente";
$_lang['Calendar Views'] = "Vizualizarea grupurilor";

$_lang['Personnel'] = "Personal";
$_lang['Create new event'] = "Creeaza &amp; Sterge evenimente";
$_lang['Day'] = "ziua";

$_lang['Until'] = "pana la";

$_lang['Note'] = "Nota";
$_lang['Project'] = "Proiect";
$_lang['Res'] = "Res";
$_lang['Once'] = "o data";
$_lang['Daily'] = "in fiecare zi";
$_lang['Weekly'] = "in fiecare saptamana";
$_lang['Monthly'] = "in fiecare luna";
$_lang['Yearly'] = "in fiecare an";

$_lang['Create'] = "Creeaza";

$_lang['Begin'] = "Incepe";
$_lang['Out of office'] = "ia afara biroului";
$_lang['Back in office'] = "intors in birou";
$_lang['End'] = "Sfarsit";
$_lang['@work'] = "@work";
$_lang['We'] = "Noi";
$_lang['group events'] = "Evenimente de grup";
$_lang['or profile'] = "sau profil";
$_lang['All Day Event'] = "Eveniment pentru intreaga zi";
$_lang['time-axis:'] = "axe de timp:";
$_lang['vertical'] = "vertical";
$_lang['horizontal'] = "orizontal";
$_lang['Horz. Narrow'] = "ingustare pe orizontala";
$_lang['-interval:'] = "-interval:";
$_lang['Self'] = "Self";

$_lang['...write'] = "...write";

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
$_lang['Please call login.php!'] = "Va rugam deschideti login.php!";

// m1.php
$_lang['There are other events!<br>the critical appointment is: '] = "Exista alte evenimente!<br>intalnirea critica este: ";
$_lang['Sorry, this resource is already occupied: '] = "Ne pare rau, aceasta resursa este deja ocupata: ";
$_lang[' This event does not exist.<br> <br> Please check the date and time. '] = " Acest eveniment nu exista.<br> <br> Va rugam verificati data si ora. ";
$_lang['Please check your date and time format! '] = "Va rugam verificati formatul datei si orei! ";
$_lang['Please check the date!'] = "Va rugam verificati data!";
$_lang['Please check the start time! '] = "Va rugam verificati ora de inceput! ";
$_lang['Please check the end time! '] = "Va rugam verificati ora de incheiere! ";
$_lang['Please give a text or note!'] = "Va rugam introduceti un text sau o notita!";
$_lang['Please check start and end time! '] = "Va rugam verificati ora de inceput si de sfarsit! ";
$_lang['Please check the format of the end date! '] = "Va rugam verificati formatul datei de terminare! ";
$_lang['Please check the end date! '] = "Va rugam verificati data de terminare! ";





$_lang['Resource'] = "Resursa";
$_lang['User'] = "Utilizator";

$_lang['delete event'] = "Sterge eveniment";
$_lang['Address book'] = "Lista de adrese";


$_lang['Short Form'] = "Forma scurta pentru nume";

$_lang['Phone'] = "Telefon";
$_lang['Fax'] = "Fax";



$_lang['Bookmark'] = "Semn de carte";
$_lang['Description'] = "Descriere";

$_lang['Entire List'] = "Intreaga lista";

$_lang['New event'] = "Eveniment nou";
$_lang['Created by'] = "Creat de";
$_lang['Red button -> delete a day event'] = "Butonul rosu -> Sterge eveniment de zi";
$_lang['multiple events'] = "multiple events";
$_lang['Year view'] = "Year view";
$_lang['calendar week'] = "calendar week"; 

//m2.php
$_lang['Create &amp; Delete Events'] = "Create &amp; Delete Events";
$_lang['normal'] = "normal";
$_lang['private'] = "private";
$_lang['public'] = "public";
$_lang['Visibility'] = "Visibility";

//mail module
$_lang['Please select at least one (valid) address.'] = "Va rugam selectati cel putin o adresa valida.";
$_lang['Your mail has been sent successfully'] = "Mail-ul d-vs a fost trimis cu succes";
$_lang['Attachment'] = "Atasament";
$_lang['Send single mails'] = "Trimite un singur mail";
$_lang['Does not exist'] = "Nu exista";
$_lang['Additional number'] = "Numar aditional";
$_lang['has been canceled'] = "a fost anulata";

$_lang['marked objects'] = "marked objects";
$_lang['Additional address'] = "Additional adress";
$_lang['in mails'] = "in mails";
$_lang['Mail account'] = "Mail Konto";
$_lang['Body'] = "Body";
$_lang['Sender'] = "Sender";

$_lang['Receiver'] = "Receiver";
$_lang['Reply'] = "Reply";
$_lang['Forward'] = "Forward";
$_lang['Access error for mailbox'] = "Access error for mailbox";
$_lang['Receive'] = "Receive";
$_lang['Write'] = "Write";
$_lang['Accounts'] = "Accounts";
$_lang['Rules'] = "Rules";
$_lang['host name'] = "host name";
$_lang['Type'] = "Type";
$_lang['misses'] = "misses";
$_lang['has been created'] = "has been created";
$_lang['has been changed'] = "has been changed";
$_lang['is in field'] = "is in field";
$_lang['and leave on server'] = "and leave on server";
$_lang['name of the rule'] = "name of the rule";
$_lang['part of the word'] = "part of the word";
$_lang['in'] = "in";
$_lang['sent mails'] = "sent mails";
$_lang['Send date'] = "Send date";
$_lang['Received'] = "Received";
$_lang['to'] = "to";
$_lang['imcoming Mails'] = "imcoming Mails";
$_lang['sent Mails'] = "sent Mails";
$_lang['Contact Profile'] = "Contact Profile";
$_lang['unread'] = "unread";
$_lang['view mail list'] = "view mail list";
$_lang['insert db field (only for contacts)'] = "insert db field (only for contacts)";
$_lang['Signature'] = "Signature";

$_lang['SMS'] = "SMS";
$_lang['Single account query'] = "Single account query";
$_lang['Notice of receipt'] = "Notice of receipt";
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
$_lang['Mail note to'] = "Notita mail pentru";
$_lang['added'] = "added";
$_lang['changed'] = "changed";

// o.php
$_lang['Calendar'] = "Calendar";
$_lang['Contacts'] = "Contacte";


$_lang['Files'] = "Fisiere";



$_lang['Options'] = "Optiuni";
$_lang['Timecard'] = "Timecard";

$_lang['Helpdesk'] = "Helpdesk";

$_lang['Info'] = "Informatii";
$_lang['Todo'] = "Todo";
$_lang['News'] = "News";
$_lang['Other'] = "Other";
$_lang['Settings'] = "Settings";
$_lang['Summary'] = "Summary";

// optionen.php
$_lang['Description:'] = "Descriere:";
$_lang['Comment:'] = "Comentariu:";
$_lang['Insert a valid Internet address! '] = "Introduceti o adresa de Internet valida! ";
$_lang['Please specify a description!'] = "Specificati o descriere!";
$_lang['This address already exists with a different description'] = "Aceasta adresa exista deja cu o descriere diferita";
$_lang[' already exists. '] = "deja exista. ";
$_lang['is taken to the bookmark list.'] = "Este luata in lista cu bokmark-uri.";
$_lang[' is changed.'] = " s-a modificat.";
$_lang[' is deleted.'] = " s-a sters.";
$_lang['Please specify a description! '] = "Va rugam specificati o descriere! ";
$_lang['Please select at least one name! '] = "Selectati cel putin un nume! ";
$_lang[' is created as a profile.<br>'] = " s-a creat ca un profil.<br> Dupa ce calendarul este actualizat profilul va fi activat.";
$_lang['is changed.<br>'] = "a fost modificat.<br>";
$_lang['The profile has been deleted.'] = "Profilul a fost sters.";
$_lang['Please specify the question for the poll! '] = "Specificati intrebarea pentru vot! ";
$_lang['You should give at least one answer! '] = "Trebuie sa dati cel putin un raspuns! ";
$_lang['Your call for votes is now active. '] = "Cererea pentru vot este activata. ";
$_lang['<h2>Bookmarks</h2>In this section you can create, modify or delete bookmarks:'] = "<h2>Bookmarks</h2>In aceasta sectiune puteti crea, modifica sau sterge semne de carte:";
$_lang['Create'] = "Creeaza";


$_lang['<h2>Profiles</h2>In this section you can create, modify or delete profiles:'] = "<h2>Profile</h2>In aceasta sectiune puteti crea, modifica sau sterge profile:";
$_lang['<h2>Voting Formula</h2>'] = "<h2>Modalitatea de votare</h2>";
$_lang['In this section you can create a call for votes.'] = "In aceasta sectiune puteti crea o cerere pentru votare.";
$_lang['Question:'] = "Intrebare:";
$_lang['just one <b>Alternative</b> or'] = "doar una <b>Alternativ</b> sau";
$_lang['several to choose?'] = "mai multe de ales?";

$_lang['Participants:'] = "Participanti:";

$_lang['<h3>Password Change</h3> In this section you can choose a new random generated password.'] = "<h3>Schimbarea parolei</h3> In aceasta sectiune puteti alege o noua parola generata aleator.";
$_lang['Old Password'] = "Vechea parola";
$_lang['Generate a new password'] = "Generati o noua parola";
$_lang['Save password'] = "Salveaza parola";
$_lang['Your new password has been stored'] = "Noua parola a fost salvata";
$_lang['Wrong password'] = "Parola gresita";
$_lang['Delete poll'] = "Sterge votul";
$_lang['<h4>Delete forum threads</h4> Here you can delete your own threads<br>Only threads without a comment will appear.'] = "<h4>Sterge subiectele forumului</h4> Aici puteti sterge propriile subiecte<br>
Vor apare doar subiecte fara comentariu.";

$_lang['Old password'] = "Vechea parola";
$_lang['New Password'] = "Noua parola";
$_lang['Retype new password'] = "Retastati noua parola";
$_lang['The new password must have 5 letters at least'] = "Noua parola trebuie sa aiba cel putin 5 caractere";
$_lang['You didnt repeat the new password correctly'] = "Nu ati tasta corect noua parola";

$_lang['Show bookings'] = "Show bookings";
$_lang['Valid characters'] = "Caractere valide";
$_lang['Suggestion'] = "Suggestion";
$_lang['Put the word AND between several phrases'] = "Put the word AND between several phrases"; // translators: please leave the word AND as it is
$_lang['Write access for calendar'] = "Write access for calendar";
$_lang['Write access for other users to your calendar'] = "Write access for other users to your calendar";
$_lang['User with chief status still have write access'] = "User with chief status still have write access";

// proj.php
$_lang['Project Listing'] = "Lista de proiecte";
$_lang['Project Name'] = "Numele proiectului";


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
$_lang['Participants'] = "Participanti";
$_lang['Priority'] = "Prioritate";
$_lang['Status'] = "Statut";
$_lang['Last status change'] = "Ultima <br>schimbare";
$_lang['Leader'] = "Lider";
$_lang['Statistics'] = "Statistici";
$_lang['My Statistic'] = "My Statistic";

$_lang['Person'] = "Persoana";
$_lang['Hours'] = "Ore";
$_lang['Project summary'] = "Rezumatul proiectului";
$_lang[' Choose a combination Project/Person'] = " Alegeti o combinatie Proiect/Persoana";
$_lang['(multiple select with the Ctrl/Cmd-key)'] = "(selectare multipla cu tasta 'Ctrl')";

$_lang['Persons'] = "Persoane";
$_lang['Begin:'] = "Inceput:";
$_lang['End:'] = "Sfarsit:";
$_lang['All'] = "Toti";
$_lang['Work time booked on'] = "Work time booked on";
$_lang['Sub-Project of'] = "Subproiect pentru";
$_lang['Aim'] = "Scop";
$_lang['Contact'] = "Contact";
$_lang['Hourly rate'] = "Rata orara";
$_lang['Calculated budget'] = "Buget calculat";
$_lang['New Sub-Project'] = "Subproiect nou";
$_lang['Booked To Date'] = "Inregistrat pana acum";
$_lang['Budget'] = "Buget";
$_lang['Detailed list'] = "Lista detaliata";
$_lang['Gantt'] = "Timeline";
$_lang['offered'] = "offered";
$_lang['ordered'] = "ordered";
$_lang['Working'] = "working";
$_lang['ended'] = "ended";
$_lang['stopped'] = "stopped";
$_lang['Re-Opened'] = "opened again";
$_lang['waiting'] = "waiting";
$_lang['Only main projects'] = "Only main projects";
$_lang['Only this project'] = "Only this project";
$_lang['Begin > End'] = "Begin > End";
$_lang['ISO-Format: yyyy-mm-dd'] = "ISO-Format: yyyy-mm-dd";
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
$_lang['please check the status!'] = "Va rugam verificati statutul!";
$_lang['Todo List: '] = "Todo List: ";
$_lang['New Remark: '] = "Remarca noua: ";
$_lang['Delete Remark '] = "Sterge remarca ";
$_lang['Keyword Search'] = "Cautare dupa cuvinte cheie: ";
$_lang['Events'] = "Evenimente";
$_lang['the forum'] = "forumul";
$_lang['the files'] = "Fisierele";
$_lang['Addresses'] = "Adrese";
$_lang['Extended'] = "Extins";
$_lang['all modules'] = "all modules";
$_lang['Bookmarks:'] = "Bookmark-uri:";
$_lang['List'] = "Lista";
$_lang['Projects:'] = "Proiecte:";

$_lang['Deadline'] = "Termenul limita:";

$_lang['Polls:'] = "Voturi:";

$_lang['Poll created on the '] = "Votare creata pe ";


// reminder.php
$_lang['Starts in'] = "Incepe in";
$_lang['minutes'] = "minute";
$_lang['No events yet today'] = "Nici un eveniment pentru astazi";
$_lang['New mail arrived'] = "New mail arrived";

//ress.php

$_lang['List of Resources'] =  "Lista de resurse";
$_lang['Name of Resource'] = "Numele resursei";
$_lang['Comments'] =  "Comentarii";


// roles
$_lang['Roles'] = "Roles";
$_lang['No access'] = "No access";
$_lang['Read access'] = "Read access";

$_lang['Role'] = "Role";

// helpdesk - rts
$_lang['Request'] = "Cerere";

$_lang['pending requests'] = "Cereri in asteptare";
$_lang['show queue'] = "arata coada";
$_lang['Search the knowledge database'] = "Cauta in baza de date";
$_lang['Keyword'] = "Cuvant cheie";
$_lang['show results'] = "arata rezultatele";
$_lang['request form'] = "formularul cererii";
$_lang['Enter your keyword'] = "Introduceti cuvantul cheie";
$_lang['Enter your email'] = "Introduceti mail-ul";
$_lang['Give your request a name'] = "Dati cererii d-vs un nume";
$_lang['Describe your request'] = "Descrieti cererea d-vs";

$_lang['Due date'] = "Data limita";
$_lang['Days'] = "Zile";
$_lang['Sorry, you are not in the list'] = "Ne pare rau, nu sunteti trecut in lista";
$_lang['Your request Nr. is'] = "Numarul cererii d-vs. este";
$_lang['Customer'] = "Client";


$_lang['Search'] = "Cautare";
$_lang['at'] = "la";
$_lang['all fields'] = "toate campurile";


$_lang['Solution'] = "Solutie";
$_lang['AND'] = "SI";

$_lang['pending'] = "in asteptare";
$_lang['stalled'] = "oprit/blocat";
$_lang['moved'] = "mutat";
$_lang['solved'] = "rezolvat";
$_lang['Submit'] = "Inscrie";
$_lang['Ass.'] = "Ass.";
$_lang['Pri.'] = "Pri.";
$_lang['access'] = "acces";
$_lang['Assigned'] = "Alocat";

$_lang['update'] = "actualizare";
$_lang['remark'] = "remarca";
$_lang['solve'] = "rezolvare";
$_lang['stall'] = "Opriti";
$_lang['cancel'] = "anulare";
$_lang['Move to request'] = "Mutat la cerere";
$_lang['Dear customer, please refer to the number given above by contacting us.Will will perform your request as soon as possible.'] = "Draga client, va rugam referiti-va la numarul primit cand ne contactati.
Vom onora cererea d-vs cat de curand posibil.";
$_lang['Your request has been added into the request queue.<br>You will receive a confirmation email in some moments.'] = "Cererea d-vs a fost adaugata in coada de asteptare.<br>
Veti primi un email pentru confirmare in cateva momente.";
$_lang['n/a'] = "Da/Nu";
$_lang['internal'] = "Intern";

$_lang['has reassigned the following request'] = "au fost realocate urmatoarele cereri";
$_lang['New request'] = "Cerere noua";
$_lang['Assign work time'] = "Alocarea timpului de lucru";
$_lang['Assigned to:'] = "Alocat la:";

$_lang['Your solution was mailed to the customer and taken into the database.'] = "Solutia a fost trimisa clientului si introdusa in baza de date.";
$_lang['Answer to your request Nr.'] = "Raspunsul la cererea dvs. numarul.";
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
$_lang['The settings have been modified'] = "The settings have been modified";
$_lang['Skin'] = "Skin";
$_lang['First module view on startup'] = "First module view on startup";
$_lang['none'] = "none";
$_lang['Check for mail'] = "Check for new mails";
$_lang['Additional alert box'] = "Additional alert box";
$_lang['Horizontal screen resolution <br>(i.e. 1024, 800)'] = "Horizotal screen resolution <br>(i.e. 1024, 800)";
$_lang['Chat Entry'] = "Chat Entry";
$_lang['single line'] = "single line";
$_lang['multi lines'] = "multi lines";
$_lang['Chat Direction'] = "Chat Direction";
$_lang['Newest messages on top'] = "Newest messages on top";
$_lang['Newest messages at bottom'] = "Newest messages at bottom";
$_lang['File Downloads'] = "File Downloads";

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
$_lang['Todays Events'] = "Events today";
$_lang['New files'] = "New files";
$_lang['New notes'] = "New notes";
$_lang['New Polls'] = "New votes";
$_lang['Current projects'] = "Current projects";
$_lang['Help Desk Requests'] = "Helpdesk Anfragen";
$_lang['Current todos'] = "Current todos";
$_lang['New forum postings'] = "New forum postings";
$_lang['New Mails'] = "New Mails";

//timecard

$_lang['Theres an error in your time sheet: '] = "Exista o eroare in foaia de timp! Va rugam verificati timecard-ul.";




$_lang['Consistency check'] = "Verificare consecventa";
$_lang['Please enter the end afterwards at the'] = "Introduceti sfarsitul la";
$_lang['insert'] = "Introduce";
$_lang['Enter records afterwards'] = "Introduceti inregistrarile";
$_lang['Please fill in only emtpy records'] = "Introduceti numai in inregistrarile goale";

$_lang['Insert a period, all records in this period will be assigned to this project'] = "Introduceti o perioada, toate inregistrarile din acesta perioada vor fi alocate acestui proiect";
$_lang['There is no record on this day'] = "Nu exista nici o inregistrare pe acesta zi";
$_lang['This field is not empty. Please ask the administrator'] = "Acest camp nu este gol. Va rugam intrebati administratorul";
$_lang['There is no open record with a begin time on this day!'] = "Datele introduse sunt gresite! Va rugam verificati-le.";
$_lang['Please close the open record on this day first!'] = "Va rugam introduceti mai intai timpul de inceput";
$_lang['Please check the given time'] = "Verificati timpul introdus";
$_lang['Assigning projects'] = "Alocarea proiectelor";
$_lang['Select a day'] = "Selectati o zi";
$_lang['Copy to the boss'] = "Copie spre administrator";
$_lang['Change in the timecard'] = "Schimbati in timecard";
$_lang['Sum for'] = "Total";

$_lang['Unassigned time'] = "Timp nealocat";
$_lang['delete record of this day'] = "stergeti inregistrarea pe aceasta zi";
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
$_lang['accepted'] = "accepted";
$_lang['rejected'] = "rejected";
$_lang['own'] = "own";
$_lang['progress'] = "progress";
$_lang['delegated to'] = "delegated to";
$_lang['Assigned from'] = "assigned from";
$_lang['done'] = "done";
$_lang['Not yet assigned'] = "Not yet assigned";
$_lang['Undertake'] = "Undertake";
$_lang['New todo'] = "New todo"; 
$_lang['Notify recipient'] = "Notify recipient";

// votum.php
$_lang['results of the vote: '] = "rezultatele votului: ";
$_lang['Poll Question: '] = "intrebare pentru vot: ";
$_lang['several answers possible'] = "mai multe raspunsuri posibile";
$_lang['Alternative '] = "Alternativ ";
$_lang['no vote: '] = "nici un vot: ";
$_lang['of'] = "de";
$_lang['participants have voted in this poll'] = "participanti care au votat";
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