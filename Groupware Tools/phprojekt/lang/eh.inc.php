<?php
// eh.inc.php, Phprojektarentzat euskeraz bertsioa
// Itzultzailea Komunika Digital <komunikadigital@komunikadigital.com>

$chars = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
$name_month = array("", "Urt", "Ots", "Mar", "Api", "Mai", "Eka", "Uzt", "Abu", "Ira", "Urr", "Aza", "Abe");
$l_text31a = array("default", "15 min.", "30 min.", " ordu 1", " 2 ordu", " 4 ordu", " egun bat");
$l_text31b = array(0, 15, 30, 60, 120, 240, 1440);
$name_day = array("Igandea", "Astelehena", "Asteartea", "Asteazkena", "Osteguna", "Ostirala", "Larunbata");
$name_day2 = array("As", "As", "As", "Os", "Os", "La", "Ig");

$_lang['No Entries Found']= "Sarrerak ez dira aurkitu";
$_lang['No Todays Events']= "Gaur Gertaerik ez";
$_lang['No new forum postings']= "Foroan mezu berriak ez daude";
$_lang['in category']= "kategorian";
$_lang['Filtered']= "Iragaztuta";
$_lang['Sorted by']= "Sortuta";
$_lang['go'] = "bidali";
$_lang['back'] = "itzuli";
$_lang['print'] = "inprimatu";
$_lang['export'] = "zerrenda-csv exportatu";
$_lang['| (help)'] = "| (laguntza)";
$_lang['Are you sure?'] = "Ziur zaude?";
$_lang['items/page'] = "itemak/orria";
$_lang['records'] = "erregistroak"; // elements
$_lang['previous page'] = "aurreko orria";
$_lang['next page'] = "hurrengo orria";
$_lang['first page'] = "lehenengo orria";
$_lang['last page'] = "azken orria";
$_lang['Move']  = "Mugitu";
$_lang['Copy'] = "Kopiatu";
$_lang['Delete'] = "Ezabatu";
$_lang['Save'] = "Gorde";
$_lang['Directory'] = "direktorioa";
$_lang['Also Delete Contents'] = "ezabatu ere edukina";
$_lang['Sum'] = "batuketa";
$_lang['Filter'] = "Iragazi";
$_lang['Please fill in the following field'] = "Bete hurrengo kampua, mesedez";
$_lang['approve'] = "Onartu";
$_lang['undo'] = "desegin";
$_lang['Please select!']="Aukeratu bat!";
$_lang['New'] = "Berria";
$_lang['Select all'] = "dena aukeratu";
$_lang['Printable view'] = "bertsio imprimitzailea";
$_lang['New record in module '] = "Erregistro berria moduluan ";
$_lang['Notify all group members'] = "Jakinarazi taldearen kide guztiei";
$_lang['Yes'] = "Bai";
$_lang['No'] = "Ez";
$_lang['Close window'] = "Itxi leihoa";
$_lang['No Value'] = "Balio Gabe";
$_lang['Standard'] = "Standard";
$_lang['Create'] = "Sortu";
$_lang['Modify'] = "Aldatu";   
$_lang['today'] = "gaur";

// admin.php
$_lang['Password'] = "Pasahitz";
$_lang['Login'] = "Sartzea";
$_lang['Administration section'] = "Administrazioaren atala";
$_lang['Your password'] = "Zure pasahitza";
$_lang['Sorry you are not allowed to enter. '] = "Barkatu, ez daukazu baimena sartzeko";
$_lang['Help'] = "Laguntza";
$_lang['User management'] = "Erabiltzaileen administrazioa";
$_lang['Create'] = "Sortu";
$_lang['Projects'] = "Proiektuak";
$_lang['Resources'] = "Baliabideak";
$_lang['Resources management'] = "Errekurtsoen Administratzailea";
$_lang['Bookmarks'] = "Faboritoak";
$_lang['for invalid links'] = "Loturen baliotasuna egiaztatu";
$_lang['Check'] = "Egiaztatu";
$_lang['delete Bookmark'] = "Faboritoak ezabatu";
$_lang['(multiple select with the Ctrl-key)'] = "(aukeraketa anizkuna 'Ctrl' teklarekin)";
$_lang['Forum'] = "Foroa";
$_lang['Threads older than'] = "Gaiak baino gehiago";
$_lang[' days '] = " egunak ";
$_lang['Chat'] = "Txat";
$_lang['save script of current Chat'] = "Oraingo txatearen testua gorde";
$_lang['Chat script'] = "Txatearen testua";
$_lang['New password'] = "Pasahitz berria";
$_lang['(keep old password: leave empty)'] = "(aurreko pasahitza eusteko: utzi huts)";
$_lang['Default Group<br> (must be selected below as well)'] = "Default taldea<br> (goian aukeratuta egon behar da)";
$_lang['Access rights'] = "Sarbidearen eskubideak";
$_lang['Zip code'] = "Posta-kodea";
$_lang['Language'] = "Hizkuntza";
$_lang['schedule readable to others'] = "Nere ordutegia ikusgai bestearentzat";
$_lang['schedule invisible to others'] = "Nere ordutegia ikusezina bestearentzat";
$_lang['schedule visible but not readable'] = "ordutegia ikusgai baina ez irakurgarri";
$_lang['these fields have to be filled in.'] = "leku hauek bete behar dira.";
$_lang['You have to fill in the following fields: family name, short name and password.'] = "Leku hauek bete behar dituzu: Abizena, Inizialak eta Pasahitza.";
$_lang['This family name already exists! '] = "Abizen honek badago! ";
$_lang['This short name already exists!'] = "Inizial hauek badaude!";
$_lang['This login name already exists! Please chosse another one.'] = "Login izen honek badago! Mesedez aukeratu beste bat.";
$_lang['This password already exists!'] = "Pasahitz honek badago!";
$_lang['This combination first name/family name already exists.'] = "Izen/abizen konbinazioa badago.";
$_lang['the user is now in the list.'] = "Erabiltzailea badago orain zerrendan.";
$_lang['the data set is now modified.'] = "datuen multzo honek aldatuta dago orain.";
$_lang['Please choose a user'] = "Mesedez aukera ezazu erabiltzaile bat";
$_lang['is still listed in some projects. Please remove it.'] = "zerrendatuta dago oraindik proiektu batzuetan. Mesedez ezabatu.";
$_lang['All profiles are deleted'] = "Profil gustiak ezabatutak daude";
$_lang['A Profile with the same name already exists'] = "Profil izen berdinarekin badago";
$_lang['is taken out of all user profiles'] = "profil gustiotik bastertuta dago";
$_lang['All todo lists of the user are deleted'] = "Erabiltzailearen egiteko zerrendak ezabatutak daude";
$_lang['is taken out of these votes where he/she has not yet participated'] = "oraindik parte hartu ez duen botuetan bastertuta dago";
$_lang['All events are deleted'] = "Gertaera guztiak ezabatutak daude";
$_lang['user file deleted'] = "erabiltzailearen artxiboa ezabatuta";
$_lang['bank account deleted'] = "banku-kontua ezabatuta";
$_lang['finished'] = "bukatuta";
$_lang['Please choose a project'] = "Mesedez proiektu bat aukeratu";
$_lang['The project is deleted'] = "Proiektua ezabatuta dago";
$_lang['All links in events to this project are deleted'] = "Proiektu honen gertaeraren loturak ezabatutak daude";
$_lang['The duration of the project is incorrect.'] = "Proiektuaren iraupena oker dago.";
$_lang['The project is now in the list'] = "Proiektua dago orain zerrendan";
$_lang['The project has been modified'] = "Proiektua aldatu da";
$_lang['Please choose a resource'] = "Mesedez aukera ezazu baliabide bat";
$_lang['The resource is deleted'] = "Baliabidea ezabatuta dago";
$_lang['All links in events to this resource are deleted'] = "Baliabide honen gertaeraren loturak ezabatutak daude";
$_lang[' The resource is now in the list.'] = "Baliabidea dago orain zerrendan.";
$_lang[' The resource has been modified.'] = "Baliabidea aldatuta dago.";
$_lang['The server sent an error message.'] = "Zerbitzaria akats mezua bidali du";
$_lang['All Links are valid.'] = "Lotura guztiak balio dute.";
$_lang['Please select at least one bookmark'] = "Mesedez aukera ezazu gutxienez faborito bat";
$_lang['The bookmark is deleted'] = "Faboritoa ezabatuta dago";
$_lang['threads older than x days are deleted.'] = "X egun baino gehiago duten mezuak ezabatu dira.";
$_lang['All chat scripts are removed'] = "Txatearen testu guztiak ezabatutak daude";
$_lang['or'] = "edo";
$_lang['Timecard management'] = "Asistentziaren administrazioa";
$_lang['View'] = "Ikusi";
$_lang['Choose group'] = "Talde bat aukeratu";
$_lang['Group name'] = "Taldearen izena";
$_lang['Short form'] = "Laburdura";
$_lang['Category'] = "Maila";
$_lang['Remark'] = "Behaketa";
$_lang['Group management'] = "Taldeen administrazioa";
$_lang['Please insert a name'] = "Mesedez sartu izen bat";
$_lang['Name or short form already exists'] = "Izena edo Inizialak badaude";
$_lang['Automatic assign to group:'] = "Taldeari esleipen automatikoa:";
$_lang['Automatic assign to user:'] = "Erabiltzaileari esleipen automatikoa:";
$_lang['Help Desk Category Management'] = "RTS kategoriaren administrazioa";
$_lang['Category deleted'] = "Kategoria ezabatuta";
$_lang['The category has been created'] = "Kategoria egin da";
$_lang['The category has been modified'] = "Kategoria aldatu da";
$_lang['Member of following groups'] = "Hurrengo taldeko kidea";
$_lang['Primary group is not in group list'] = "Default Taldea ez dago taldeen zerrendan";
$_lang['Login name'] = "Erabiltzailearen Izena";
$_lang['You cannot delete the default group'] = "Ezin duzu default taldea ezabatu";
$_lang['Delete group and merge contents with group'] = "Ezabatu taldea eta sartu taldearen edukina";
$_lang['Please choose an element'] = "Mesedez aukera ezazu elementu bat";
$_lang['Group created'] = "Taldea egina";
$_lang['File management'] = "Artxibo/Fitxategi-aren administrazioa";
$_lang['Orphan files'] = "Artxibo/Fitxategi Umezurtz";
$_lang['Deletion of super admin root not possible'] = "Ezin da posible ezabatu sustraiko super erabiltzailea";
$_lang['ldap name'] = "Izena ldap";
$_lang['mobile // mobile phone'] = "mobila"; // mobil phone
$_lang['Normal user'] = "Erabiltzaile Normala";
$_lang['User w/Chief Rights'] = "Erabiltzaile Nagusia";
$_lang['Administrator'] = "Administradorea";
$_lang['Logging'] = "Logging";
$_lang['Logout'] = "Logout";
$_lang['posting (and all comments) with an ID'] = "foru mezuak (eta komentario guztiak) ID batekin";
$_lang['Role deleted, assignment to users for this role removed'] = "Rola ezabatuta izan da, ezabatu rol honen erabiltzailearen eginkizunak";
$_lang['The role has been created'] = "Rola sortu egin da";
$_lang['The role has been modified'] = "Rola aldatu egin da";
$_lang['Access rights'] = "Sartzeko eskubideak";
$_lang['Usergroup'] = "Taldearen erabiltzailea";
$_lang['logged in as'] = "logged izena";

//chat.php
$_lang['Quit chat']= "Itxi txat";

//contacts.php
$_lang['Contact Manager'] = "Kontaktuen Administrazioa";
$_lang['New contact'] = "Kontaktu berria";
$_lang['Group members'] = "Taldearen kideak";
$_lang['External contacts'] = "Kanpoko kontaktuak";
$_lang['&nbsp;New&nbsp;'] = "&nbsp;Berria&nbsp;";
$_lang['Import'] = "Inportatu";
$_lang['The new contact has been added'] = "Kontaktu berria gehitu da";
$_lang['The date of the contact was modified'] = "Kontaktuaren datak aldatu izan dira";
$_lang['The contact has been deleted'] = "Kontaktua ezabatu izan da";
$_lang['Open to all'] = "Denontzat ikusgarria";
$_lang['Picture'] = "Irudia";
$_lang['Please select a vcard (*.vcf)'] = "Mesedez vcard bat aukeratu (*.vcf)";
$_lang['create vcard'] = "egin vcard";
$_lang['import address book'] = "Helbideen libreta inportatu";
$_lang['Please select a file (*.csv)'] = "Artxibo bat aukeratu (*.csv)";
$_lang['Howto: Open your outlook express address book and select file/export/other book<br>Then give the file a name, select all fields in the next dialog and finish'] = "Nola?: Ireki Outlook Express-eko helbideen libreta eta aukeratu 'artxibo'/'exportatu'/'helbideen libreta'<br> Aukeratu artxiboaren tipoa balio 
kometatik banatuta bezala<br> Deitu artxiboari .cvs fitxategi luzapenarekin. Aukeratu kampu guztiak hurrengo hizketan eta sakatu 'Bukatu'";
$_lang['Open outlook at file/export/export in file,<br>choose comma separated values (Win), then select contacts in the next form,<br>give the export file a name and finish.'] = "Ireki Outlook Expressa eta aukeratu 'artxiboa'/'exportatu'/'helbideen libreta',<br>
aukeratu balio kometatik banatuta (Win)', gero aukeratu 'kontaktuak' modu honetan,<br>
deitu artxiboari, sakatu 'Bukatu'";
$_lang['Please choose an export file (*.csv)'] = "Mesedez aukeratu artxibo bat exportatzeko (*.csv)";
$_lang['Please export your address book into a comma separated value file (.csv), and either<br>1) apply an import pattern OR<br>2) modify the columns of the table with a spread sheet to this format<br>(Delete colums in you file that are not listed here and create empty colums for fields that do not exist in your file):'] = "Please export your address book into a comma separated value file (.csv), and either<br>
1) apply an import pattern OR<br>
2) modify the columns of the table with a spread sheet to this format<br>
(Delete colums in you file that are not listed here and create empty colums for fields that do not exist in your file):";
$_lang['Please insert at least the family name'] = "Mesedez ipini gutxienez abizena";
$_lang['Record import failed because of wrong field count'] = "Huts egin du erregistroaren importazioa kanpoko kuenta oker bategatik";
$_lang['Import to approve'] = "Inportatu onartseko";
$_lang['Import list'] = "Zerrenda inportatu";
$_lang['The list has been imported.'] = "Zerrenda onartuta izan da.";
$_lang['The list has been rejected.'] = "Zerrenda errefusatuta izan da.";
$_lang['Profiles'] = "Espedienteak";
$_lang['Parent object'] = "Objektu printzipala";
$_lang['Check for duplicates during import'] = "Egiaztatu bikoiztutak inportazioan";
$_lang['Fields to match'] = "Bilatzeko eremuak";
$_lang['Action for duplicates'] = "Bikoizturen akzioa";
$_lang['Discard duplicates'] = "Baztertu bikoitzak";
$_lang['Dispose as child'] = "Prestatu semea bezain";
$_lang['Store as profile'] = "Perfilez gorde";    
$_lang['Apply import pattern'] = "Inportazio patroia aplikatu";
$_lang['Import pattern'] = "Inportatu patroia";
$_lang['For modification or creation<br>upload an example csv file'] = "Igo inportatu artxiboa (csv)"; 
$_lang['Skip field'] = "Eremua saltatu"; 
$_lang['Field separator'] = "Eremu banatzailea";
$_lang['Contact selector'] = "Kontaktuen aukeragailua";
$_lang['Use doublet'] = "erabili bikoitza";
$_lang['Doublets'] = "Bikoitzak";

// filemanager.php
$_lang['Please select a file'] = "Mesedez aukera ezazu artxibo bat";
$_lang['A file with this name already exists!'] = "Izen horrekin artxibo bat badago!";
$_lang['Name'] = "Izena";
$_lang['Comment'] = "Iruzkina";
$_lang['Date'] = "Data";
$_lang['Upload'] = "Gorde";
$_lang['Filename and path'] = "Artxiboaren izena eta kokapena";
$_lang['Delete file'] = "Ezabatu artxiboa";
$_lang['Overwrite'] = "Berridatzi";
$_lang['Access'] = "Sarbidea";
$_lang['Me'] = "Ni";
$_lang['Group'] = "taldea";
$_lang['Some'] = "Batzuk";
$_lang['As parent object'] = "Berdin Direktorioa";
$_lang['All groups'] = "Talde guztiak";
$_lang['You are not allowed to overwrite this file since somebody else uploaded it'] = "Zuk ezin duzu artxibo hau berridatzi zeren eta norbait gorde du lehen";
$_lang['personal'] = "pertsonala";
$_lang['Link'] = "Lotura";
$_lang['name and network path'] = "Artxibo/fitxero-ari gehitu bidea";
$_lang['with new values'] = "balio berriarekin";
$_lang['All files in this directory will be removed! Continue?'] = "Direktorio honen artxibo guztiak ezabatuko dira! Zegi?";
$_lang['This name already exists'] = "Izen honek badago";
$_lang['Max. file size'] = "artxibo/fitzero-aren Tamaina Max.";
$_lang['links to'] = "loturak";
$_lang['objects'] = "objetuak";
$_lang['Action in same directory not possible'] = "Direktorio berdinan ekintza ezinezkoa da";
$_lang['Upload = replace file'] = "Igo = ordeztu artxibo/fitzero";
$_lang['Insert password for crypted file'] = "Sartu pasa-hitza enkriptatuta dagoen artxiboarentzat";
$_lang['Crypt upload file with password'] = "Enkriptatu igotzen ari zaren artxiboa pasa-hitzarekin";
$_lang['Repeat'] = "Errepikatu";
$_lang['Passwords dont match!'] = "Pasa-hitza gaizki dago!";
$_lang['Download of the password protected file '] = "Pasa-hitzarekin babestuta dagoen artxiboaren deskarga ";
$_lang['notify all users with access'] = "sartzeko baimena duten erabiltzaile guztiei jakinarazi";
$_lang['Write access'] = "Idazteko baimena";
$_lang['Version'] = "Bertsioa";
$_lang['Version management'] = "Administrazioaren bertsioa";
$_lang['lock'] = "blokeatuta";
$_lang['unlock'] = "desblokeatu";
$_lang['locked by'] = "blokeoaren egilea";
$_lang['Alternative Download'] = "Deskarga Alternatiboa";
$_lang['Download'] = "Deskarga";
$_lang['Select type'] = "Aukeratu mota";
$_lang['Create directory'] = "Sortu direktorioa";
$_lang['filesize (Byte)'] = "Artxibu tamainua (Byte)";

// filter
$_lang['contains'] = 'edukiak';
$_lang['exact'] = 'zehatza';
$_lang['starts with'] = 'hasituta';
$_lang['ends with'] = 'bukatuta';
$_lang['>'] = '>';
$_lang['>='] = '>=';
$_lang['<'] = '<';
$_lang['<='] = '<=';
$_lang['does not contain'] = 'ez dauka edukirik';
$_lang['Please set (other) filters - too many hits!'] = "Mesedez aukeratu (beste) filtroak - emaitza gehiago lortzeko!";

$_lang['Edit filter'] = "Aldatu flitroa";
$_lang['Filter configuration'] = "Filtroaren konfigurazio";
$_lang['Disable set filters'] = "Desaktibatu fijatu filtroak";
$_lang['Load filter'] = "Kargatu filtroak";
$_lang['Delete saved filter'] = "Ezabatu filtroak";
$_lang['Save currently set filters'] = "Gorde filtroak";
$_lang['Save as'] = "Gorde";
$_lang['News'] = 'Berriak';

// form designer
$_lang['Module Designer'] = "Moduloaren Egilea";
$_lang['Module element'] = "Moduloaren elementoa"; 
$_lang['Module'] = "Moduloa";
$_lang['Active'] = "Aktiboa";
$_lang['Inactive'] = "Inaktiboa";
$_lang['Activate'] = "Aktibatu";
$_lang['Deactivate'] = "Desaktibatu"; 
$_lang['Create new element'] = "Sortu elementu berria";
$_lang['Modify element'] = "Aldatu elementua";
$_lang['Field name in database'] = "Kanpuaren izena databasean";
$_lang['Use only normal characters and numbers, no special characters,spaces etc.'] = "Erabili bakarrik karaktere normalak eta zenbakiak, ez karaktere espezialak,hutsuneak, etabar.";
$_lang['Field name in form'] = "kanpuaren izena formularioan";
$_lang['(could be modified later)'] = "(ezin da aldatu gero)"; 
$_lang['Single Text line'] = "Lerro bakar textua";
$_lang['Textarea'] = "Textarea";
$_lang['Display'] = "Erakutsi";
$_lang['First insert'] = "Lehenengo betea";
$_lang['Predefined selection'] = "Aurredefinitutako selekzioa";
$_lang['Select by db query'] = "Select by db query";
$_lang['File'] = "Artxiboa";

$_lang['Email Address'] = "Email";
$_lang['url'] = "url";
$_lang['Checkbox'] = "Checkbox";
$_lang['Multiple select'] = "Selekzio multiplea";  
$_lang['Display value from db query'] = "Erakutsi balorea db-ren query";
$_lang['Time'] = "Denbora";
$_lang['Tooltip'] = "Tooltip"; 
$_lang['Appears as a tipp while moving the mouse over the field: Additional comments to the field or explanation if a regular expression is applied'] = "Appears as a tipp while moving the mouse over the field: Additional comments to the field or explanation if a regular expression is applied";
$_lang['Position'] = "Posizioa";
$_lang['is current position, other free positions are:'] = "is current position, other free positions are:"; 
$_lang['Regular Expression:'] = "Regular Exprezioa:";
$_lang['Please enter a regular expression to check the input on this field'] = "Mesedez sartu regular exprezioa egiaztatzeko kanpu honetan ipinituta";
$_lang['Default value'] = "Default balorea";
$_lang['Predefined value for creation of a record. Could be used in combination with a hidden field as well'] = "Predefined value for creation of a record. Could be used in combination with a hidden field as well";
$_lang['Content for select Box'] = "Content for select Box";
$_lang['Used for fixed amount of values (separate with the pipe: | ) or for the sql statement, see element type'] = "Used for fixed amount of values (separate with the pipe: | ) or for the sql statement, see element type";
$_lang['Position in list view'] = "Zerrenadaren bistan posizioa";
$_lang['Only insert a number > 0 if you want that this field appears in the list of this module'] = "Only insert a number > 0 if you want that this field appears in the list of this module";
$_lang['Alternative list view'] = "Zerrendaren bista alternatiba";
$_lang['Value appears in the alt tag of the blue button (mouse over) in the list view'] = "Value appears in the alt tag of the blue button (mouse over) in the list view";
$_lang['Filter element'] = "Filtroaren elementua";
$_lang['Appears in the filter select box in the list view'] = "Appears in the filter select box in the list view";
$_lang['Element Type'] = "Elementu Mota";
$_lang['Select the type of this form element'] = "Aukeratu formulario elementu honen mota";
$_lang['Check the content of the previous field!'] = "Aurreko kanpuaren edukia egiaztatu!";
$_lang['Span element over'] = "Span element over";
$_lang['columns'] = "zutabeak";
$_lang['rows'] = "lerroak";
$_lang['Telephone'] = "Telefonoa";
$_lang['History'] = "Istorioa";
$_lang['Field'] = "Kanpoa";
$_lang['Old value'] = "Balore zaharra";
$_lang['New value'] = "balore berria";
$_lang['Author'] = "Egilea"; 
$_lang['Show Date'] = "Erakutsi data";
$_lang['Creation date'] = "Egitearen data";
$_lang['Last modification date'] = "Azken aldaketaren data";
$_lang['Email (at record cration)'] = "Email (erregistroan egina)";
$_lang['Contact (at record cration)'] = "Contaktua (erregistroan egina)"; 
$_lang['Select user'] = "Aukeratu erabiltzailea";
$_lang['Show user'] = "Erakutsi erabiltzailea";

// forum.php
$_lang['Please give your thread a title'] = "Mesedez ipini titulo bat zure mezuari";
$_lang['New Thread'] = "Mezu berria";
$_lang['Title'] = "Tituloa";
$_lang['Text'] = "Textoa";
$_lang['Post'] = "Posta";
$_lang['From'] = "Autorea";
$_lang['open'] = "irekita";
$_lang['closed'] = "itxita";
$_lang['Notify me on comments'] = "Iruskinak adierazi";
$_lang['Answer to your posting in the forum'] = "Zure mezuari erantzun dute";
$_lang['You got an answer to your posting'] = "Zure mezua erantzun bat du \n ";
$_lang['New posting'] = "Mezu berria";
$_lang['Create new forum'] = "Sortu foro berria";
$_lang['down'] ='behera';
$_lang['up']= "gora";
$_lang['Forums']= "Foroak";
$_lang['Topics']="Gaiak";
$_lang['Threads']="Hariak";
$_lang['Latest Thread']="Azken Haria";
$_lang['Overview forums']= "Foruen dezskripzioa";
$_lang['Succeeding answers']= "Erantzun zuzunak";
$_lang['Count']= "Kontua";
$_lang['from']= "autorea";
$_lang['Path']= "Bidea";
$_lang['Thread title']= "Hariaren tituloa";
$_lang['Notification']= "Notifikazioa";
$_lang['Delete forum']= "Ezabatu foroa";
$_lang['Delete posting']= "Mezua ezabatu";
$_lang['In this table you can find all forums listed']= "Tabla honetan bila ditzakezu listatutako foru guztiak";
$_lang['In this table you can find all threads listed']= "Tabla honetan bila ditzakezu listatutako hari guztiak";

// index.php
$_lang['Last name'] = "Azken izenaLast name";
$_lang['Short name'] = "Izena";
$_lang['Sorry you are not allowed to enter.'] = "Barkatu ezin duzu sartu.";
$_lang['Please run index.php: '] = "Mesedez exekutatu index.php: ";
$_lang['Reminder'] = "Orogailua";
$_lang['Session time over, please login again'] = "Sesio denbora pasa da, mesedez login berriro";
$_lang['&nbsp;Hide read elements'] = "&nbsp;Ezkutatu irakurrita dauden elementuak";
$_lang['&nbsp;Show read elements'] = "&nbsp;Erakutsi irakurrita dauden elementuak";
$_lang['&nbsp;Hide archive elements'] = "&nbsp;Ezkutatu artxibatuta dauden elementuak";
$_lang['&nbsp;Show archive elements'] = "&nbsp;Erakutsi artxibatuta dauden elementuak";
$_lang['Tree view'] = "Zuhaitz ikusmena";
$_lang['flat view'] = "Plano ikusmena";
$_lang['New todo'] = "Tarea berriak";
$_lang['New note'] = "Nota berria";
$_lang['New document'] = "Dokumentu berria";
$_lang['Set bookmark'] = "Ipini bookmark";
$_lang['Move to archive'] = "Artxibera mugitu";
$_lang['Mark as read'] = "Irakurritaz markatu";
$_lang['Export as csv file'] = "csv artxibuz exportatu";
$_lang['Deselect all'] = "Desekzelionatu dena";
$_lang['selected elements'] = "elementu aukeratutak";
$_lang['wider'] = "zabaldu";
$_lang['narrower'] = "estuagoa";
$_lang['ascending'] = "igotzen";
$_lang['descending'] = "beheratzen";
$_lang['Column'] = "Kolumna";
$_lang['Sorting'] = "Klasifikatzen";
$_lang['Save width'] = "Gorde";
$_lang['Width'] = "Zabalera";
$_lang['switch off html editor'] = "itzali html editorea";
$_lang['switch on html editor'] = "piztu html editor";
$_lang['hits were shown for'] = "hits erakutzi dira";
$_lang['there were no hits found.'] = "ez dago hitz.";
$_lang['Filename'] = "Artxiboaren izena";
$_lang['First Name'] = "Izena";
$_lang['Family Name'] = "Familiaren izena";
$_lang['Company'] = "Enpresa";
$_lang['Street'] = "kalea";
$_lang['City'] = "Hiria";
$_lang['Country'] = "Herria";
$_lang['Please select the modules where the keyword will be searched'] = "Mesedez aukeratu moduloak hitz klabeak egingo duten bilaketa";
$_lang['Enter your keyword(s)'] = "Sartu zure hitz klabea(k)";
$_lang['Salutation'] = "Ongietorria";
$_lang['State'] = "Estatua";
$_lang['Add to link list'] = "Agregatu lotura listara";

// setup.php
$_lang['Welcome to the setup of PHProject!<br>'] = "Ongietorri Phprojekt-eren instalatzaileari!<br>";
$_lang['Please remark:<ul><li>A blank database must be available<li>Please ensure that the webserver is able to write the file config.inc.php'] = "Izan kontuan:<ul>
<li>Datu-base bat hutsik euki behar dugu egina
<li>Zihurtatu webgunearen zerbitzariaren baimana daukazu idazteko 'config.inc.php' artxiboa<br> (p.e. 'chmod 777')";
$_lang['<li>If you encounter any errors during the installation, please look into the <a href=help/faq_install.html target=_blank>install faq</a>or visit the <a href=http://www.PHProjekt.com/forum.html target=_blank>Installation forum</a></i>'] = "<li>Instalazioan akatzekin aurkitzen bazara, mesedez irakurri artxiboa <a href='doc/es/faq_install.html' target=_blank>instalazioaren faq</a>
edo bisitatu <a href='http://www.PHProjekt.com/forum.html' target=_blank>Instalazioaren foroa</a></i>";
$_lang['Please fill in the fields below'] = "Mesedez bete hurrengo atalak";
$_lang['(In few cases the script wont respond.<br>Cancel the script, close the browser and try it again).<br>'] = "(Kasu batzuetan script ez du erantzungo.<br>
kitatu scripta, itzi nabegadorea etz berriro saiatu).<br>";
$_lang['Type of database'] = "Datu-baseko mota";
$_lang['Hostname'] = "Zerbitzaria";
$_lang['Username'] = "Erabiltzailea";

$_lang['Name of the existing database'] = "Datu-baseren izena";
$_lang['config.inc.php not found! Do you really want to update? Please read INSTALL ...'] = "config.inc.php ez da aurkitu!  Benetan nahi duzu eguneratu?  Mesedez irakurri artxibo INSTALL ...";
$_lang['config.inc.php found! Maybe you prefer to update PHProject? Please read INSTALL ...'] = "config.inc.php aurkituta!  Beharbada nahiago duzu PHProjekt eguneratu? Mesedez irakurri artxibo INSTALL ...";
$_lang['Please choose Installation,Update or Configure!'] = "Mesedez aukeratu 'Instalazioa' edo 'Eguneratu'! itzuli ...";
$_lang['Sorry, I cannot connect to the database! <br>Please close all browser windows and restart the installation.'] = "Barkatu, ez dabil! <br> Mesedez zuzendu eta berrasieratu instalazioa.";
$_lang['Sorry, it does not work! <br> Please set DBDATE to Y4MD- or let phprojekt change this environment-variable (php.ini)!'] = "Barkatu, ez dabil!<br> Mesedez ezarri DBDATE  'Y4MD-' edo utzi phprojekt-eri 'variable-ambiente' alda dezan (php.ini)!";
$_lang['Seems that You have a valid database connection!'] = "Zorionak! Konexio balioduna daukazu datu-baserekin!";
$_lang['Please select the modules you are going to use.<br> (You can disable them later in the config.inc.php)<br>'] = "Mesedez aukera ezazu erabiliko dituzun moduluak.<br> (Kentzeko posibilitatea daukazu gero config.inc.php -an)<br>";
$_lang['Install component: insert a 1, otherwise keep the field empty'] = "Osagaiak instalatzeko:  '1' sartu, bestela mantendu tartea hutsik";
$_lang['Group views'] = "Taldeen bistak";
$_lang['Todo lists'] = "Egiteko zerrendak";

$_lang['Voting system'] = "Bozketa modua";


$_lang['Contact manager'] = "Kontaktuen administrazioa";
$_lang['Name of userdefined field'] = "Eremuaren izena erabiltzaileaz definituta";
$_lang['Userdefined'] = "Erabiltzailetaz definituta";
$_lang['Profiles for contacts'] = "Kontaktuen artxiboak";
$_lang['Mail'] = "Posta arina";
$_lang['send mail'] = " mezua bidali";
$_lang[' only,<br> &nbsp; &nbsp; full mail client'] = " bakarrik,<br> &nbsp; &nbsp; bezeroaren helbide elektroniko osoa";



$_lang['1 to show appointment list in separate window,<br>&nbsp; &nbsp; 2 for an additional alert.'] = "'1' beste leiho batean aktibitateen zerrenda erakusteko,<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'2' alerta gehigarri batentzat.";
$_lang['Alarm'] = "Alarma";
$_lang['max. minutes before the event'] = "minutu gehienez, gertakizuna baino lehen";
$_lang['SMS/Mail reminder'] = "SMS/Mail reminder";
$_lang['Reminds via SMS/Email'] = "Reminds via SMS/Email";
$_lang['1= Create projects,<br>&nbsp; &nbsp; 2= assign worktime to projects only with timecard entry<br>&nbsp; &nbsp; 3= assign worktime to projects without timecard entry<br>&nbsp; &nbsp; (Selection 2 or 3 only with module timecard!)'] = "'1'= Proiektuak sortu,<br>
&nbsp; &nbsp; '2'= ordutegia eman bakarrik asistentzietarako diru sarrerak dituzten proiektuei<br>
&nbsp; &nbsp; '3'= ordutegia eman asistentzietarako diru sarrerak ez dituzten proiektuei<br> ";

$_lang['Name of the directory where the files will be stored<br>( no file management: empty field)'] = "Artxiboak gordeko diren direktorioaren izena<br>( artxiboen administrazio barik: tartea hutsik )";
$_lang['absolute path to this directory (no files = empty field)'] = "direktorio honen sarbide osoa (artxibo barik = tartea hutsik)";
$_lang['Time card'] = "Asistentzia";
$_lang['1 time card system,<br>&nbsp; &nbsp; 2 manual insert afterwards sends copy to the chief'] = "'1' asistentziaren kontrola,<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '2' eskuz egindako geroko sarrera arduradunari bidali kopia bat";
$_lang['Notes'] = "Oharra";
$_lang['Password change'] = "Pasahitzaren aldaketa";
$_lang['New passwords by the user - 0: none - 1: only random passwords - 2: choose own'] = "Erabiltzaileagatik pasahitz berriak - 0: batere - 1: pasahitz aleatorioak bakarrik - 2: berezko erabakia";
$_lang['Encrypt passwords'] = "Pasahitzak enkriptatu";
$_lang['Login via '] = "Sarrera bidea ";
$_lang['Extra page for login via SSL'] = "Horri gehigarria SSLren bitartez sartzeko";
$_lang['Groups'] = "Taldeak";
$_lang['User and module functions are assigned to groups<br>&nbsp; &nbsp; (recommended for user numbers > 40)'] = "Erabiltzailearen eginkizunak eta moduluak taldeei zuzentzen zaizkie<br>
&nbsp;&nbsp;&nbsp;&nbsp;(gomendatuta erabiltzaileak 40 baino gehiago direnean)";
$_lang['User and module functions are assigned to groups'] = "Erabiltzailearen eginkizunak eta moduluak taldeei zuzentzen zaizkie";
$_lang['Help desk'] = "Eskaeren segimendua (RT)";
$_lang['Help Desk Manager / Trouble Ticket System'] = "Laguntza Mahaiaren administrazioa / Arazoen Tiketen sistema ";
$_lang['RT Option: Customer can set a due date'] = "RT Aukera: Bezeroak data muga zehaztu dezake";
$_lang['RT Option: Customer Authentification'] = "RT Aukera: Bezeroaren baimentzea";
$_lang['0: open to all, email-address is sufficient<br>1: registered contact enter his family name<br>2: his email'] = "0: denontzat irekita, helbide elektronikoarekin nahikoa da, 1: bezeroa kontaktuen zerrendan agertu behar da eta bere abizena sartu";
$_lang['RT Option: Assigning request'] = "RT Aukera: Eskabideen esleitzea";
$_lang['0: by everybody, 1: only by persons with status chief'] = "0: guztiongatik, 1: bakarrik 'arduradun' maila daukaten pertsonentzat";
$_lang['Email Address of the support'] = "Euskarriaren helbide elektronikoa";
$_lang['Scramble filenames'] = "Artxiboen izenak nahastu";
$_lang['creates scrambled filenames on the server<br>assigns previous name at download'] = "serbidorean artxiboen izen nahastuak eratzen ditu<br>
izenak emanez jeitsi itzazu momentuan";

$_lang['0: last name, 1: short name, 2: login name'] = "0: Abizena, 1: Inizialak, 2: Login izena";
$_lang['Prefix for table names in db'] = "Prefix for table names in db";
$_lang['Alert: Cannot create file config.inc.php!<br>Installation directory needs rwx access for your server and rx access to all others.'] = "Alerta: Ezin da artxiboa sortu 'config.inc.php'!<br>
Instalazio direktorioa sarrera rwx behar du zure serbidorearentzat eta sarrera rx beste guztientzat.";
$_lang['Location of the database'] = "Datu basearen lokalizazioa";
$_lang['Type of database system'] = "Datu base sistemaren mota";
$_lang['Username for the access'] = "Sartzeko erabiltzailea";
$_lang['Password for the access'] = "Sartzeko pasahitza";
$_lang['Name of the database'] = "Datu basearen izena";
$_lang['Prefix for database table names'] = "Prefix for database table names";
$_lang['First background color'] = "Atzealdearen lehenengo kolorea";
$_lang['Second background color'] = "Atzealdearen bigarren kolorea";
$_lang['Third background color'] = "Atzealdearen hirugarren kolorea";
$_lang['Color to mark rows'] = "Filak markatzeko kolorea";
$_lang['Color to highlight rows'] = "Filak remarkatzeko kolorea";
$_lang['Event color in the tables'] = "Ekintzen koloreak tauletan";
$_lang['company icon yes = insert name of image'] = "Erakundearen ikonoa Bai = Irudiaren izena sartu";
$_lang['URL to the homepage of the company'] = "Erakundearen web gurearen URLa";
$_lang['no = leave empty'] = "Ez = zuri utzi";
$_lang['First hour of the day:'] = "Egunaren lehenengo ordua:";
$_lang['Last hour of the day:'] = "Egunaren azkenengo ordua:";
$_lang['An error ocurred while creating table: '] = "Akats bat gertatu da taula sortzen zen bitartean: ";
$_lang['Table dateien (for file-handling) created'] = "Taula 'dateien' (artxiboen manipulaziorako) sortuta";
$_lang['File management no = leave empty'] = "Artxiboen administrazioa Ez = zuri utzi";
$_lang['yes = insert full path'] = "Bai = Sarbide osoa sartu";
$_lang['and the relative path to the PHProjekt directory'] = "Taula 'profile' (erabiltzaileen profilentzat) sortuta";
$_lang['Table profile (for user-profiles) created'] = "Taula 'profile' (erabiltzaileen profilentzat) sortuta";
$_lang['User Profiles yes = 1, no = 0'] = "Profilak Bai = 1, ez = 0";
$_lang['Table todo (for todo-lists) created'] = "Taula 'dena' (Egiteko zerrendarentzat) sortuta";
$_lang['Todo-Lists yes = 1, no = 0'] = "Egiteko zerrenda bai = 1, ez = 0";
$_lang['Table forum (for discssions etc.) created'] = "Taula 'forum' (eztabaida Foroentzat) sortuta";
$_lang['Forum yes = 1, no = 0'] = "Foro bai = 1, ez = 0";
$_lang['Table votum (for polls) created'] = "Taula 'votum' (bozketentzat) sortuta";
$_lang['Voting system yes = 1, no = 0'] = "Bozketa sistema bai = 1, ez = 0";
$_lang['Table lesezeichen (for bookmarks) created'] = "Taula 'lesezeichen' (bookmark-entzat) sortuta";
$_lang['Bookmarks yes = 1, no = 0'] = "Bookmark bai = 1, ez = 0";
$_lang['Table ressourcen (for management of additional ressources) created'] = "Taula 'ressourcen' (baliabide gehigarrien administraziorako) sortuta";
$_lang['Resources yes = 1, no = 0'] = "Baliabideak bai = 1, ez = 0";
$_lang['Table projekte (for project management) created'] = "Taula 'projekte' (proiektuen administraziorako) sortuta";
$_lang['Table contacts (for external contacts) created'] = "Taula 'contacts' (kanpoko kontaktuentzat) sortuta";
$_lang['Table notes (for notes) created'] = "Taula 'notes' (oharrentzat) sortuta";
$_lang['Table timecard (for time sheet system) created'] = "Taula 'timecard' (asistentziaren kontrolarentzat) sortuta";
$_lang['Table groups (for group management) created'] = "Taula 'groups' (taldeen administraziorako) sortuta";
$_lang['Table timeproj (assigning work time to projects) created'] = "Taula 'timeproj' (proiektuei lan denboraren esleipena) sortuta";
$_lang['Table rts and rts_cat (for the help desk) created'] = "Taula 'rts' y 'rts_cat' (eskabideen segimendu sistemarentzat) sortuta";
$_lang['Table mail_account, mail_attach, mail_client und mail_rules (for the mail reader) created'] = "Taula mail_account, mail_attach, mail_client und mail_rules (mezuen irakurleentzat) sortuta";
$_lang['Table logs (for user login/-out tracking) created'] = "Taula logs (erabiltzaileen login/-out miaketarentzat) sortuta";
$_lang['Tables contacts_profiles und contacts_prof_rel created'] = "Taulak contacts_profiles eta contacts_prof_rel sortutak";
$_lang['Project management yes = 1, no = 0'] = "Proiektuen administrazioa bai = 1, ez = 0";
$_lang['additionally assign resources to events'] = "gehigarri moduan baliabideak gertakizuneei esleitu";
$_lang['Address book  = 1, nein = 0'] = "Helbide-libreta bai = 1, ez = 0";
$_lang['Mail no = 0, only send = 1, send and receive = 2'] = "Posta arina bai = 1, ez = 0";
$_lang['Chat yes = 1, no = 0'] = "Chat yes = 1, no = 0";
$_lang['Name format in chat list'] = "Name format in chat list";
$_lang['0: last name, 1: first name, 2: first name, last name,<br> &nbsp; &nbsp; 3: last name, first name'] = "0: last name, 1: first name, 2: first name, last name,<br> &nbsp; &nbsp; 3: last name, first name";
$_lang['Timestamp for chat messages'] = "Timestamp for chat messages";
$_lang['users (for authentification and address management)'] = "'user' (helbideen autentifikazio eta administraziorako";
$_lang['Table termine (for events) created'] = "Taula 'termine' (gertakizunentzat) sortuta";
$_lang['The following users have been inserted successfully in the table user:<br>root - (superuser with all administrative privileges)<br>test - (chief user with restricted access)'] = "Hurrengo erabiltzaileak ondo sartu dira 'user' Taulan:<br>
'root' - (abantail administratibo guztiak dituen super-erabiltzailea)<br>
'test' - (chefe erabiltzailea sarrera murriztuarekin)";
$_lang['The group default has been created'] = "'default' taldea sortuta izan da";
$_lang['Please do not change anything below this line!'] = "Lerro honen azpitik ez aldatu ezer, eskerrik asko!";
$_lang['Database error'] = "Datu basearen akatsa";
$_lang['Finished'] = "Bukatuta";
$_lang['There were errors, please have a look at the messages above'] = "Akats batzuk agertu dira, goiko partean agertzen diren mezuak egiaztatu";
$_lang['All required tables are installed and <br>the configuration file config.inc.php is rewritten<br>It would be a good idea to makea backup of this file.<br>'] = "Eskatutako taula guztiak instalatuak daude eta <br>
konfigurazioaren artxioba 'config.inc.php' berridatzita dago<br>
Artxibo honen seguritate kopia (backup) bat egitea interesgarria litzake.<br>
Zure nabegatzailearen leiho guztiak itxi orain.<br>";
$_lang['The administrator root has the password root. Please change his password here:'] = "'root' administratzailea 'root' pasahitza dauka. Aldatu pasahitza mesedez:";
$_lang['The user test is now member of the group default.<br>Now you can create new groups and add new users to the group'] = "'test' erabiltzailea 'default' taldearen kidea da.<br>
Orai talde berriak sortu ditzakezu eta erabiltzaile berriak gehitu taldeei";
$_lang['To use PHProject with your Browser go to <b>index.php</b><br>Please test your configuration, especially the modules Mail and Files.'] = "PHProjekt erabiltzeko zure nabegatzailearekin <b>index.php</b> joan zaitez <br>.
Zure konfigurazio frogatu mesedez, batez ere 'Posta arina' eta 'Artxiboak' moduluak.";

$_lang['Alarm x minutes before the event'] = "Alarma x minutu gertakizuna baino lehen";
$_lang['Additional Alarmbox'] = "Alarma gehigarrirako leihoa";
$_lang['Mail to the chief'] = "Arduradunari mezua";
$_lang['Out/Back counts as: 1: Pause - 0: Workingtime'] = "Kanpora/bueltan balio du: 1: Pausa - 0: Lan denbora";
$_lang['Passwords will now be encrypted ...'] = "Pasahitzak enkriptatuak izango dira orain";
$_lang['Filenames will now be crypted ...'] = "Artxiboen izenak orain izango dira enkriptatuak ...";
$_lang['Do you want to backup your database right now? (And zip it together with the config.inc.php ...)<br>Of course I will wait!'] = "Zure datu basearen seguritate kopia (backup) bat egin nahi duzu orain?<br> (Eta artxibo zip batean konprimitu config.inc.php-rekin batera ...)<br>
Jakina itxarongo dudala!";
$_lang['Next'] = "Hurrengoa";
$_lang['Notification on new event in others calendar'] = "Beste egutegi bateko gertakizun berri baten notifikazioa";
$_lang['Path to sendfax'] = "Sendfaxentzat bidea";
$_lang['no fax option: leave blank'] = "faxaren aukera barik : zuri utzi";
$_lang['Please read the FAQ about the installation with postgres'] = "Postgres instalatzeko FAQ irakurri mesedez";
$_lang['Length of short names<br> (Number of letters: 3-6)'] = "Zenbat hizki inizialentzat?<br> (Hizki zenbakia: 3-6)";
$_lang['If you want to install PHProjekt manually, you find<a href=http://www.phprojekt.com/files/sql_dump.tar.gz target=_blank>here</a> a mysql dump and a default config.inc.php'] = "PHProjekt eskuz instalatu nahi baduzu, aurkituko duzu
<a href='http://www.phprojekt.com/files/sql_dump.tar.gz' target=_blank>hemen</a> mysqlren dump eta default config.inc.php";
$_lang['The server needs the privilege to write to the directories'] = "Serbidorea 'write' pribilegioak izan behar ditu direktorioan";
$_lang['Header groupviews'] = "Taldeentzat goiburuak";
$_lang['name, F.'] = "izena, F.";
$_lang['shortname'] = "inizialak";
$_lang['loginname'] = "erabiltzailearen izena";
$_lang['Please create the file directory'] = "Artxiboen direktorioa sortzeko faborea egin";
$_lang['default mode for forum tree: 1 - open, 0 - closed'] = "foroaren zuhaitzarentza default modua: 1 - irekita, 0 - itxita";
$_lang['Currency symbol'] = "Diruaren sinboloa";
$_lang['current'] = "oraingo";
$_lang['Default size of form elements'] = "Formulario elementuen default tamainua";
$_lang['use LDAP'] = "LDAP erabili";
$_lang['Allow parallel events'] = "Gertakizun paraleloak laga";
$_lang['Timezone difference [h] Server - user'] = "Ordu-eremuen ezberdintasuna [h] Serbidorea - erabiltzailea";
$_lang['Timezone'] = "Ordu-eremua";
$_lang['max. hits displayed in search module'] = "bilatzaile moduluan zabaldutako hits-en zk. max.";
$_lang['Time limit for sessions'] = "Sesioiko denbora limitea";
$_lang['0: default mode, 1: Only for debugging mode'] = "0: default modua, 1: bakarrik frogatzeko modua";
$_lang['Enables mail notification on new elements'] = "Aktibatu email abisuak elementu berriak daudenean";
$_lang['Enables versioning for files'] = "Aktibatu artxiborako bertsioak";
$_lang['no link to contacts in other modules'] = "ez loturak kontaktuentzako beste modulotan";
$_lang['Highlight list records with mouseover'] = "Highlight list records with 'mouseover'";
$_lang['Track user login/logout'] = "Track user login/logout";
$_lang['Access for all groups'] = "Talde osorako sarrera";
$_lang['Option to release objects in all groups'] = "Objetuak ipintzeko talde osoan opzioa";
$_lang['Default access mode: private=0, group=1'] = "Default sartzeko modua: pribatua=0, taldea=1"; 
$_lang['Adds -f as 5. parameter to mail(), see php manual'] = "Adds '-f' as 5. parameter to mail(), see php manual";
$_lang['end of line in body; e.g. \r\n (conform to RFC 2821 / 2822)'] = "end of line in body; e.g. \\r\\n (conform to RFC 2821 / 2822)";
$_lang['end of header line; e.g. \r\n (conform to RFC 2821 / 2822)'] = "end of header line; e.g. \\r\\n (conform to RFC 2821 / 2822)";
$_lang['Sendmail mode: 0: use mail(); 1: use socket'] = "Sendmail mode: 0: use mail(); 1: use socket";
$_lang['the real address of the SMTP mail server, you have access to (maybe localhost)'] = "the real address of the SMTP mail server, you have access to (maybe localhost)";
$_lang['name of the local server to identify it while HELO procedure'] = "name of the local server to identify it while HELO procedure";
$_lang['Authentication'] = "Autentifikazioa";
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
$_lang['Resource List'] = "Baliabideen zerrenda";
$_lang['Event List'] = "Gertakizunen zerrenda";
$_lang['Calendar Views'] = "Taldeen bista";

$_lang['Personnel'] = "Personal";

$_lang['Create new event'] = "Sortu &amp; Gertakizunak ezabatu";
$_lang['Day'] = "Eguna";

$_lang['Until'] = "Nora";

$_lang['Note'] = "Oharra";
$_lang['Project'] = "Proiektua";
$_lang['Res'] = "Balia";
$_lang['Once'] = "Bat";
$_lang['Daily'] = "Egunero";
$_lang['Weekly'] = "Astero";
$_lang['Monthly'] = "Hilero";
$_lang['Yearly'] = "Urtero";

$_lang['Create'] = "Sortu";

$_lang['Begin'] = "Sarrera";
$_lang['Out of office'] = "Bulegotik kanpo";
$_lang['Back in office'] = "Berriro bulegoan";
$_lang['End'] = "Amaiera";
$_lang['@work'] = "@lana";
$_lang['We'] = "Sem";
$_lang['group events'] = "talde gertakizunak";
$_lang['or profile'] = "edo profila";
$_lang['All Day Event'] = "egun osorako gertakizuna";
$_lang['time-axis:'] = "denbora-ardatza:";
$_lang['vertical'] = "bertikala";
$_lang['horizontal'] = "horizontala";
$_lang['Horz. Narrow'] = "hor.estua";
$_lang['-interval:'] = "-tarte:";
$_lang['Self'] = "Nirea";

$_lang['...write'] = "...idatzi";

$_lang['Calendar dates'] = "Kalendario datak";
$_lang['List'] = "Lista";
$_lang['Year'] = "Urtea";
$_lang['Month'] = "Hilabete";
$_lang['Week'] = "Astea";
$_lang['Substitution'] = "Ordeztea";
$_lang['Substitution for'] = "Ordeztatu";
$_lang['Extended&nbsp;selection'] = "Selekzio&nbsp;zabalkortasuna";
$_lang['New Date'] = "Data berria sartuta";
$_lang['Date changed'] = "Data aldatuta";
$_lang['Date deleted'] = "Data ezabatuta";

// links
$_lang['Database table'] = "Databaseren tabla";
$_lang['Record set'] = "Registro mota";
$_lang['Resubmission at:'] = "Resubmission at:";
$_lang['Set Links'] = "Loturak";
$_lang['From date'] = "Data";
$_lang['Call record set'] = "Deitu registro motara";

//login.php
$_lang['Please call login.php!'] = "¡Mesedez, login.php-ren bitartez sar zaitez!";

// m1.php
$_lang['There are other events!<br>the critical appointment is: '] = "Beste gertakizun bat dago!<br>gatazka sortzen duen ekintza: ";
$_lang['Sorry, this resource is already occupied: '] = "Barkatu, baliabide hau hartuta dago jadanik: ";
$_lang[' This event does not exist.<br> <br> Please check the date and time. '] = " Gertakizun hau ez da esistitzen.<br> <br> Eguna eta ordua egiaztatu. ";
$_lang['Please check your date and time format! '] = "Eguna eta orduaren formatoa egiaztatu mesedez! ";
$_lang['Please check the date!'] = "Data egiaztatu mesedez!";
$_lang['Please check the start time! '] = "Hasteko ordua egiaztatu mesedez! ";
$_lang['Please check the end time! '] = "Amaitzeko ordua egiaztatu mesedez! ";
$_lang['Please give a text or note!'] = "Texto edo oharra sartu mesedez!";
$_lang['Please check start and end time! '] = "Hasiera eta amaiera orduak egiaztatu mesedez! ";
$_lang['Please check the format of the end date! '] = "Amaitzeko orduaren formatoa egiaztatu mesedez! ";
$_lang['Please check the end date! '] = "Amaitzeko data egiaztatu mesedez! ";





$_lang['Resource'] = "Baliabidea";
$_lang['User'] = "Erabiltzailea";

$_lang['delete event'] = "gertakizuna ezabatu";
$_lang['Address book'] = "Helbideen libreta";


$_lang['Short Form'] = "Inizialal";

$_lang['Phone'] = "Telefonoa";
$_lang['Fax'] = "Faxa";



$_lang['Bookmark'] = "Faborito";
$_lang['Description'] = "Azalpena";

$_lang['Entire List'] = "zerrenda osoa";

$_lang['New event'] = "Gertaera berria";
$_lang['Created by'] = "sortu du";
$_lang['Red button -> delete a day event'] = "Botoi gorria -> egunaren gertakizuna ezabatzen du";
$_lang['multiple events'] = "gertakizun anitzak";
$_lang['Year view'] = "Urteroko bista";
$_lang['calendar week'] = "Aste egutegia";

//m2.php
$_lang['Create &amp; Delete Events'] = "Sortu &amp; ezabatu Gertakizunak";
$_lang['normal'] = "arrunta";
$_lang['private'] = "pribatua";
$_lang['public'] = "publikoa";
$_lang['Visibility'] = "Ikusgaitasuna";

//mail module
$_lang['Please select at least one (valid) address.'] = "Gutxienez helbide (balioduna) BAT aukeratu mesedez.";
$_lang['Your mail has been sent successfully'] = "Zure mezua ondo bidali da";
$_lang['Attachment'] = "Erantsi";
$_lang['Send single mails'] = "banakako mezu bidali";
$_lang['Does not exist'] = "ez dago";
$_lang['Additional number'] = "zenbakia gehitu";
$_lang['has been canceled'] = "ezeztatuta";

$_lang['marked objects'] = "Mezuak";
$_lang['Additional address'] = "Helbide gehigarria";
$_lang['in mails'] = "mezuetan";
$_lang['Mail account'] = "Mail Konto";
$_lang['Body'] = "Gorputza";
$_lang['Sender'] = "Bidaltzailea";

$_lang['Receiver'] = "Hartzailea";
$_lang['Reply'] = "Erantzun";
$_lang['Forward'] = "Berbidali";
$_lang['Access error for mailbox'] = "Errorea postontzira sartzerakoan";
$_lang['Receive'] = "Jasota";
$_lang['Write'] = "Bidalita";
$_lang['Accounts'] = "Helbideak";
$_lang['Rules'] = "Arauak";
$_lang['host name'] = "serbidorearen izena";
$_lang['Type'] = "Mota";
$_lang['misses'] = "akatsak";
$_lang['has been created'] = "sortuta izan da";
$_lang['has been changed'] = "aldatuta izan da";
$_lang['is in field'] = "eremuan";
$_lang['and leave on server'] = "Mezuak jaso eta serbidorean utzi";
$_lang['name of the rule'] = "arauaren izena";
$_lang['part of the word'] = "hitzaren zatia";
$_lang['in'] = "-n";
$_lang['sent mails'] = "bidalitako mezuak";
$_lang['Send date'] = "Bidaltze data";
$_lang['Received'] = "Jasota";
$_lang['to'] = "-rentzat";
$_lang['imcoming Mails'] = "Sartutako mezuak";
$_lang['sent Mails'] = "mezuak bidali";
$_lang['Contact Profile'] = "Kontaktuaren Profile-a";
$_lang['unread'] = "irakurtezina";
$_lang['view mail list'] = "ikusi posta lista";
$_lang['insert db field (only for contacts)'] = "sartu db kanpua (bakarrik kontaktuarentzat)";
$_lang['Signature'] = "sinadura";

$_lang['SMS'] = "SMS";
$_lang['Single account query'] = "Kontu bakarren kontsulta";
$_lang['Notice of receipt'] = "Harrera jakinarazi";
$_lang['Assign to project'] = "Projektura asignatu";
$_lang['Assign to contact'] = "Kontaktura asignatu";  
$_lang['Assign to contact according to address'] = "Kontaktura asgnatu direkzioa akordez";
$_lang['Include account for default receipt'] = "Barnetu kuenta default reziborentzako";
$_lang['Your token has already been used.<br>If it wasnt you, who used the token please contact your administrator.'] = "Zure sinboloa badago erabiltzen.<br>Zuk ez baduzu erabili, mesedez kontaktuan jarri administradorearekin";
$_lang['Your token has already been expired.'] = "Zure sinboloa kadukatuta dago";
$_lang['Unconfirmed Events'] = "Konfirmatu gabe Ebentuak";
$_lang['Visibility presetting when creating an event'] = "Ikusgaitasuna ebentu bat sortzen";
$_lang['Subject'] = "Gaia";
$_lang['Content'] = "Edukiera";
$_lang['answer all'] = "erantzun denoi";
$_lang['Create new message'] = "Mezu berri bat sortu";
$_lang['Attachments'] = "Adjuntuak";
$_lang['Recipients'] = "Rezipienteak";
$_lang['file away message'] = "artxibu gabeko mezua";
$_lang['Message from:'] = "Bidaltzailea:";

//notes.php
$_lang['Mail note to'] = "Oharra bidali";
$_lang['added'] = "gehituta";
$_lang['changed'] = "aldatuta";

// o.php
$_lang['Calendar'] = "Egutegia";
$_lang['Contacts'] = "Kontaktuak";


$_lang['Files'] = "Artxiboak";



$_lang['Options'] = "Aukerak";
$_lang['Timecard'] = "Asistentzia";

$_lang['Helpdesk'] = "RTS";

$_lang['Info'] = "Info";
$_lang['Todo'] = "Egiteko";
$_lang['News'] = "Berriak";
$_lang['Other'] = "Beste";
$_lang['Settings'] = "Ezaugarriak";
$_lang['Summary'] = "Aurkibidea";

// options.php
$_lang['Description:'] = "Azalpena:";
$_lang['Comment:'] = "Komentarioa:";
$_lang['Insert a valid Internet address! '] = "Internet helbide balioduna sartu! ";
$_lang['Please specify a description!'] = "Mesedez azalpen bat zehaztu!";
$_lang['This address already exists with a different description'] = "Helbide hau beste azalpen batekin dago";
$_lang[' already exists. '] = " badago. ";
$_lang['is taken to the bookmark list.'] = "Faboritoen zerrendan gehitu da";
$_lang[' is changed.'] = " aldatuta dago.";
$_lang[' is deleted.'] = " ezabatuta dago.";
$_lang['Please specify a description! '] = "Helbide bat zehaztu mesedez! ";
$_lang['Please select at least one name! '] = "Gutxienez izen bat aukeratu mesedez! ";
$_lang[' is created as a profile.<br>'] = "sortu da profila bezala.<br> Behin egutegia eguneratu ondoren profila aktiboa egongo da.";
$_lang['is changed.<br>'] = "aldatuta dago.<br> Behin egutegia eguneratu ondoren profila aktiboa egongo da.";
$_lang['The profile has been deleted.'] = "Profila ezabatuta izan da.";
$_lang['Please specify the question for the poll! '] = "Bozketarako galdera zehaztu mesedez! ";
$_lang['You should give at least one answer! '] = "Gutxienez erantzun bat eman beharko zenuke! ";
$_lang['Your call for votes is now active. '] = "Bozketarako deia aktiboa dago jadanik. ";
$_lang['<h2>Bookmarks</h2>In this section you can create, modify or delete bookmarks:'] = "<h2>Bookmarks</h2>Atal honetan Bookmark desberdinak sortu, aldatu eta ezabatu ditzakezu:";
$_lang['Create'] = "sortu";


$_lang['<h2>Profiles</h2>In this section you can create, modify or delete profiles:'] = "<h2>Profilak</h2> Atal honetan profilak sortu, aldatu eta ezabatu ditzakezu:";
$_lang['<h2>Voting Formula</h2>'] = "<h2>Bozketa formula</h2>";
$_lang['In this section you can create a call for votes.'] = "Atal honetan bozketarako deia sortu dezakezu.";
$_lang['Question:'] = "Galdera:";
$_lang['just one <b>Alternative</b> or'] = "bat bakarrik <b>aukera</b> o";
$_lang['several to choose?'] = "aukera bat baino gehiago?";

$_lang['Participants:'] = "Partehartzaileak:";

$_lang['<h3>Password Change</h3> In this section you can choose a new random generated password.'] = "<h3>Pasahitzaren aldaketa</h3> Atal honetan aleatorioki sortutako pasahitz berria aukeratu dezakezu.";
$_lang['Old Password'] = "Aurreko pasahitza";
$_lang['Generate a new password'] = "Pasahitz berria sortu";
$_lang['Save password'] = "Pasahitza grabatu";
$_lang['Your new password has been stored'] = "Zure pasahitz berria gordeta dago";
$_lang['Wrong password'] = "Pasahitz okerra";
$_lang['Delete poll'] = "Botoa ezabatu";
$_lang['<h4>Delete forum threads</h4> Here you can delete your own threads<br>Only threads without a comment will appear.'] = "<h4>Foroaren gaiak ezabatu</h4> Hemen zure gaiak ezabatu ditzakezu<br>
Bakarrik agertuko dira azalpen gabeko gaiak.";

$_lang['Old password'] = "Aurreko pasahitza";
$_lang['New Password'] = "Pasahitz berria";
$_lang['Retype new password'] = "Berridatzi pasahitz berria";
$_lang['The new password must have 5 letters at least'] = "Pasahitz berri bost hizki gutxienez izan behar ditu";
$_lang['You didnt repeat the new password correctly'] = "Pasahitz berria ez duzu ondo errepikatu";

$_lang['Show bookings'] = "Erreserbak erakutsi";
$_lang['Valid characters'] = "Karaktere baliogarriak";
$_lang['Suggestion'] = "Iradokizuna";
$_lang['Put the word AND between several phrases'] = "Ipini AND hainbat esaldien artean"; // translators: please leave the word AND as it is
$_lang['Write access for calendar'] = "Idatzi baimena egutegira";
$_lang['Write access for other users to your calendar'] = "Idatzi zure egutegiaren baimena beste erabiltzaileentzat";
$_lang['User with chief status still have write access'] = "Erabiltzailea nagusi estatusarekin oraindik idazteko baimena dauka";

// projects
$_lang['Project Listing'] = "Proiektuen zerrenda";
$_lang['Project Name'] = "Proiektuaren izena";


$_lang['o_files'] = "Artxiboak";
$_lang['o_notes'] = "Notak";
$_lang['o_projects'] = "Projektuak";
$_lang['o_todo'] = "Todo";
$_lang['Copyright']="Copyright";
$_lang['Links'] = "Loturak";
$_lang['New profile'] = "Perfil Berria";
$_lang['In this section you can choose a new random generated password.'] = "Sekzio honetan pasahitz berri bat sortu desakezu.";
$_lang['timescale'] = "denbora eskala";
$_lang['Manual Scaling'] = "Eskala manuala";
$_lang['column view'] = "kolumna bista";
$_lang['display format'] = "erakutzi formatoa";
$_lang['for chart only'] = "Estadistika grafikoentzako bakarrik:";
$_lang['scaling:'] = "eskalatzen:";
$_lang['colours:'] = "koloreak";
$_lang['display project colours'] = "erakutzi projekt koloreak";
$_lang['weekly'] = "asteroko";
$_lang['monthly'] = "hilabeteroko";
$_lang['annually'] = "urteko";
$_lang['automatic'] = "automatiko";
$_lang['New project'] = "Projektu berria";
$_lang['Basis data'] = "Data basikoa";
$_lang['Categorization'] = "kategorizazioa";
$_lang['Real End'] = "Bukaera erreala";
$_lang['Participants'] = "Partehartzaileak";
$_lang['Priority'] = "Lehentasuna";
$_lang['Status'] = "Egoera";
$_lang['Last status change'] = "Azkenengo <br>aldaketa";
$_lang['Leader'] = "Lider";
$_lang['Statistics'] = "Estatistikak";
$_lang['My Statistic'] = "Nire estatistikak";

$_lang['Person'] = "Pertsona";
$_lang['Hours'] = "Orduak";
$_lang['Project summary'] = "Proiektuaren laburpena";
$_lang[' Choose a combination Project/Person'] = "Aukeratu proiektu/persona konbinazioa";
$_lang['(multiple select with the Ctrl/Cmd-key)'] = "(aukeraketa anitza 'Ctrl' teklarekin)";

$_lang['Persons'] = "Pertsonak";
$_lang['Begin:'] = "Hasiera:";
$_lang['End:'] = "Amaiera:";
$_lang['All'] = "Denak";
$_lang['Work time booked on'] = "Lan denbora esleituta hemen";
$_lang['Sub-Project of'] = "Azpiproiektua";
$_lang['Aim'] = "Aim";
$_lang['Contact'] = "Kontaktatu";
$_lang['Hourly rate'] = "orduko kostua";
$_lang['Calculated budget'] = "kalkulatutako aurrekontua";
$_lang['New Sub-Project'] = "Azpiproiektu berria";
$_lang['Booked To Date'] = "Orain arte erreserbatuta";
$_lang['Budget'] = "Aurrekontua";
$_lang['Detailed list'] = "Zerrenda zehaztuta";
$_lang['Gantt'] = "Denbora lerroa";
$_lang['offered'] = "eskeinita";
$_lang['ordered'] = "ordenatu";
$_lang['Working'] = "lanean";
$_lang['ended'] = "bukatuta";
$_lang['stopped'] = "geldituta";
$_lang['Re-Opened'] = "Berriz ireki";
$_lang['waiting'] = "itxaroten";
$_lang['Only main projects'] = "Proiektu nagusiak bakarrik";
$_lang['Only this project'] = "Proiektu hau bakarrik";
$_lang['Begin > End'] = "Hasiera > Bukaera";
$_lang['ISO-Format: yyyy-mm-dd'] = "ISO-Formatua: uuuu-hh-ee";
$_lang['The timespan of this project must be within the timespan of the parent project. Please adjust'] = "Proiektu honen betearazpen denbora ez da baterakide proiektu nagusiarekin. Mesedez egokitu";
$_lang['Please choose at least one person'] = "Mesedez aukeratu pertsona bat gutxienez";
$_lang['Please choose at least one project'] = "Mesedez aukeratu proiektu bat gutxienez";
$_lang['Dependency'] = "Menpekotasuna";
$_lang['Previous'] = "Aurrekoa";

$_lang['cannot start before the end of project'] = "Ezin da hasi proiektuaren bukaera arte";
$_lang['cannot start before the start of project'] = "Ezin da hasi proiektua hasi arte";
$_lang['cannot end before the start of project'] = "Ezin da bukatu proiektua hasi arte";
$_lang['cannot end before the end of project'] = "Ezin da bukatu proiektuaren bukaera arte";
$_lang['Warning, violation of dependency'] = "Kontuz, menpekotasunaren haustea";
$_lang['Container'] = "Ontzia";
$_lang['External project'] = "Kanpoko proiektua";
$_lang['Automatic scaling'] = "Igoera automatikoa";
$_lang['Legend'] = "Legenda";
$_lang['No value'] = "Balio barik";
$_lang['Copy project branch'] = "Copiatu projektuaren rama";
$_lang['Copy this element<br> (and all elements below)'] = "Kopiatu elementu hinek<br> (eta beheran dauden elementuak)";
$_lang['And put it below this element'] = "Eta beheran jarri elementu honek";
$_lang['Edit timeframe of a project branch'] = "Aldatu projektu ramaren timeframe "; 

$_lang['of this element<br> (and all elements below)'] = "elementu honen<br> (eta beheran daunden elementuak)";  
$_lang['by'] = "egilea";
$_lang['Probability'] = "Probabilitatea";
$_lang['Please delete all subelements first'] = "Mesedez ezabatu subprojektu guztiak lehen";
$_lang['Assignment'] ="Asignatu";
$_lang['display'] = "Erakutsi";
$_lang['Normal'] = "Normala";
$_lang['sort by date'] = "Dataz zerrendatu";
$_lang['sort by'] = "zerrendatu";
$_lang['Calculated budget has a wrong format'] = "Kalkulatutako presupuestoa formato okerra dauka";
$_lang['Hourly rate has a wrong format'] = "Horduko prezioa formato okerra dauka";

// r.php
$_lang['please check the status!'] = "estatus egiaztatu mesedez!";
$_lang['Todo List: '] = "Egiteko: ";
$_lang['New Remark: '] = "Ohar berria: ";
$_lang['Delete Remark '] = "Oharra ezabau ";
$_lang['Keyword Search'] = "Bilatzailea: ";
$_lang['Events'] = "Gertakizunak";
$_lang['the forum'] = "foroan";
$_lang['the files'] = "artxiboetan";
$_lang['Addresses'] = "Helbideak";
$_lang['Extended'] = "Zabalduta";
$_lang['all modules'] = "modulo guztiak";
$_lang['Bookmarks:'] = "Bookmark:";
$_lang['List'] = "Zerrenda";
$_lang['Projects:'] = "Proiektuak:";

$_lang['Deadline'] = "Epea";

$_lang['Polls:'] = "Botoak:";

$_lang['Poll created on the '] = "Bozketa sortutako data ";


// reminder.php
$_lang['Starts in'] = "hasten da";
$_lang['minutes'] = "minutuak";
$_lang['No events yet today'] = "Gaurkorako oraindik ez daude gertakizunik";
$_lang['New mail arrived'] = "Mezu berri bat jaso duzu";

//ress.php

$_lang['List of Resources'] =  "Baliabideen zerrenda";
$_lang['Name of Resource'] = "Baliabideen izena";
$_lang['Comments'] =  "Azalpenak";


// roles
$_lang['Roles'] = "Rolak";
$_lang['No access'] = "Baimena gabe";
$_lang['Read access'] = "Irakurtzeko baimena";

$_lang['Role'] = "Rola";

//rts
$_lang['Request'] = "Eskaera";

$_lang['pending requests'] = "bete gabeko eskaerak";
$_lang['show queue'] = "errenkada erakutsi";
$_lang['Search the knowledge database'] = "Ezagupenaren artxiboan bilatu";
$_lang['Keyword'] = "Hitz klabea";
$_lang['show results'] = "emaitzak erakutsi";
$_lang['request form'] = "eskaeraren formularioa";
$_lang['Enter your keyword'] = "zure hitz klabea sartu";
$_lang['Enter your email'] = "zure helbide elektronikoa sartu";
$_lang['Give your request a name'] = "Zure eskaera izendatu";
$_lang['Describe your request'] = "zure eskaera azaldu";

$_lang['Due date'] = "Epemuga";
$_lang['Days'] = "Egunak";
$_lang['Sorry, you are not in the list'] = "Ez zaude zerrendan";
$_lang['Your request Nr. is'] = "Zure eskaera zenbakia";
$_lang['Customer'] = "Bezeroa";


$_lang['Search'] = "Bilatu";
$_lang['at'] = "a";
$_lang['all fields'] = "eremu guztiak";


$_lang['Solution'] = "Irtengidea";
$_lang['AND'] = "Eta";

$_lang['pending'] = "pendiente";
$_lang['stalled'] = "izoztuta";
$_lang['moved'] = "mugitua";
$_lang['solved'] = "konponduta";
$_lang['Submit'] = "Bidali";
$_lang['Ass.'] = "Esl.";
$_lang['Pri.'] = "Lehent.";
$_lang['access'] = "sarrera";
$_lang['Assigned'] = "Esleituta";

$_lang['update'] = "eguneratu";
$_lang['remark'] = "oharra eman";
$_lang['solve'] = "konpondu";
$_lang['stall'] = "izoztu";
$_lang['cancel'] = "kantzelatu";
$_lang['Move to request'] = "Eskaera mugitu";
$_lang['Dear customer, please refer to the number given above by contacting us.Will will perform your request as soon as possible.'] = "Bezero agurgarria, kontaktatzerakoan gogoratu goian emandako zenbakia mesedez.
Zure eskaera aurrera eramango dugu ahal dugun bezain laster.";
$_lang['Your request has been added into the request queue.<br>You will receive a confirmation email in some moments.'] = "Zure eskaera eskeren errenkadara mugitzen dugu.<br>
Baieztapen mezu elektroniko bat jasoko duzu.";
$_lang['n/a'] = "n/d";
$_lang['internal'] = "barruko";

$_lang['has reassigned the following request'] = "hurrengo eskera berresleitu duzu";
$_lang['New request'] = "Eskaera berria";
$_lang['Assign work time'] = "Lan debora esleitu:";
$_lang['Assigned to:'] = "honi esleituta:";

$_lang['Your solution was mailed to the customer and taken into the database.'] = "Zure erabakia postaz bidali zitzaion bezeroari eta datu basean gordeta dago.";
$_lang['Answer to your request Nr.'] = "Zure eskaerari erantzuna EZ.";
$_lang['Fetch new request by mail'] = "Eskaera berriak postaren bitartez eskuratu";
$_lang['Your request was solved by'] = "Zure eskaria erantzun du:";

$_lang['Your solution was mailed to the customer and taken into the database'] = " Konponbidea postaz bidali zitzaion bezeroari eta datu basean gordeta dago";
$_lang['Search term'] = "Bilatzeko terminoa";
$_lang['Search area'] = "Bilaketaren area";
$_lang['Extended search'] = "Bilaketa zabala";
$_lang['knowledge database'] = "database ezaguera";
$_lang['Cancel'] = "Kitatu";
$_lang['New ticket'] = "Ticket berria";
$_lang['Ticket status'] ="Ticket estatua";

// please adjust this states as you want -> add/remove states in helpdesk.php
$_lang['unconfirmed'] = 'baiztetu gabe';
$_lang['new'] = 'berria';
$_lang['assigned'] = 'asignatu';
$_lang['reopened'] = 'berriro irekita';
$_lang['resolved'] = 'konponduta';
$_lang['verified'] = 'egiaztatuta';

// settings.php
$_lang['The settings have been modified'] = "Ezaugarriak aldatu egin dira";
$_lang['Skin'] = "Skin";
$_lang['First module view on startup'] = "Hasierako moduloa";
$_lang['none'] = "batere";
$_lang['Check for mail'] = "mail berriak bilatu";
$_lang['Additional alert box'] = "Alerta adizionala";
$_lang['Horizontal screen resolution <br>(i.e. 1024, 800)'] = "Pop-Up gertakizuna izan aurretik";
$_lang['Chat Entry'] = "Txat Sarrera";
$_lang['single line'] = "lerro bakarra";
$_lang['multi lines'] = "lerro anitz";
$_lang['Chat Direction'] = "Txat Direkzioa";
$_lang['Newest messages on top'] = "Mezu berrienak goian";
$_lang['Newest messages at bottom'] = "Mezu berrienak beheran";
$_lang['File Downloads'] = "Artxiboen deskargak";

$_lang['Inline'] = "Inline";
$_lang['Lock file'] = "lock artxiboa";
$_lang['Unlock file'] = "nlock artxiboa";
$_lang['New file here'] = "Artxibo berria hemen";
$_lang['New directory here'] = "Direktorio berria hemen";
$_lang['Position of form'] = "Formularioaren posizioa";
$_lang['On a separate page'] = "Beste orrian";
$_lang['Below the list'] = "Lista beheran";
$_lang['Treeview mode on module startup'] = "Zuhaitz bista hasierako moduloan";
$_lang['Elements per page on module startup'] = "Elementuz orri hasierako moduloan";
$_lang['General Settings'] = "Ajuste Generalak";
$_lang['First view on module startup'] = "Lehenengo bista hasierako moduloan";
$_lang['Left frame width [px]'] = "Ezkerraldemo frame zabalera [px]";
$_lang['Timestep Daywiew [min]'] = "Denbora pasa Egun bista [min]";
$_lang['Timestep Weekwiew [min]'] = "Denbora pasa Aste bista [min]";
$_lang['px per char for event text<br>(not exact in case of proportional font)'] = "px karaktere ebentuen textoan<br>(ez da exaktua proporzional fuenteekin)";
$_lang['Text length of events will be cut'] = "Ebentuen luzera mostuko da";
$_lang['Standard View'] = "Bista standard";
$_lang['Standard View 1'] = "Bista standard 1";
$_lang['Standard View 2'] = "Bista standard 2";
$_lang['Own Schedule'] = "Ordutegi Personala";
$_lang['Group Schedule'] = "Taldearen Ordutegia";
$_lang['Group - Create Event'] = "Taldea - Ebentua sortu";
$_lang['Group, only representation'] = "taldea, bakarrik only representazioa";
$_lang['Holiday file'] = "Oporrako artxiboa";

// summary
$_lang['Todays Events'] = "Gaurko eginkizunak";
$_lang['New files'] = "Artxibu berriak";
$_lang['New notes'] = "Nota berriak";
$_lang['New Polls'] = "Galdeketa berriak";
$_lang['Current projects'] = "Oraingo Proiektuak";
$_lang['Help Desk Requests'] = "Helpdesk Petizioak";
$_lang['Current todos'] = "Laister egiteko";
$_lang['New forum postings'] = "Ekarpen berriak foruan";
$_lang['New Mails'] = "Mezu berriak";

//timecard

$_lang['Theres an error in your time sheet: '] = "Akats bat dago zure asistentzi plantillan. Egiaztatu mesedez.";




$_lang['Consistency check'] = "Trinkotasunaren egiaztatzea";
$_lang['Please enter the end afterwards at the'] = "Mesedez sartu irteera honen ostean";
$_lang['insert'] = "sartu";
$_lang['Enter records afterwards'] = "Erregistroak gero sartu";
$_lang['Please fill in only emtpy records'] = "Bakarrik hutsuneak bete mesedez";

$_lang['Insert a period, all records in this period will be assigned to this project'] = "Epe bat sartu, epe honen barruan egiten diren erregistro guztiak proiektu honi esleituko zaie";
$_lang['There is no record on this day'] = "Ez daude egun horretarako erregistrorik";
$_lang['This field is not empty. Please ask the administrator'] = "Eremu hau ez dago hutsik. Galdetu administratzaileari mesedez.";
$_lang['There is no open record with a begin time on this day!'] = "Emandako datak gaizki daude! Egiaztatu mesedez.";
$_lang['Please close the open record on this day first!'] = "Sartu lehenengo hasiera ordua mesedez";
$_lang['Please check the given time'] = "Egiaztatu emandako ordua mesedez";
$_lang['Assigning projects'] = "Proiektuen esleipena";
$_lang['Select a day'] = "Egun bat aukeratu";
$_lang['Copy to the boss'] = "Arduradunari kopia bat";
$_lang['Change in the timecard'] = "Asistentziaren txartela aldaketa";
$_lang['Sum for'] = "Batuketa dagokio";

$_lang['Unassigned time'] = "Denbora esleitu barik";
$_lang['delete record of this day'] = "ezabatu egun honetako errekorda";
$_lang['Bookings'] = "Erreserbatzen";

$_lang['insert additional working time'] = "sartu lan egiteko denbora adizionala";
$_lang['Project assignment']= "Projektuaren asignatua";
$_lang['Working time stop watch']= "Lan egiteko denboraz gelditu erlojua";
$_lang['stop watches']= "gelditu erlojuak";
$_lang['Project stop watch']= "Gelditu proiektuaren erlojua";
$_lang['Overview my working time']= "Nire lan denbora deskribatu";
$_lang['GO']= "JOAN";
$_lang['Day view']= "Egun bista";
$_lang['Project view']= "Projektu bista";
$_lang['Weekday']= "Aste eguna";
$_lang['Start']= "Hasi";
$_lang['Net time']= "Net denbora";
$_lang['Project bookings']= "Projektuaren erreserbak";
$_lang['save+close']= "gorde+itxi";
$_lang['Working times']= "Lan denborak";
$_lang['Working times start']= "Lan denborak hasi";
$_lang['Working times stop']= "lan denborak gelditu";
$_lang['Project booking start']= "Projektuaren erreserbak hasi";
$_lang['Project booking stop']= "Projektuaren erreserbak gelditu";
$_lang['choose day']= "aukeratu eguna";
$_lang['choose month']= "aukeratu hilabetea";
$_lang['1 day back']= "egun 1 atzera";
$_lang['1 day forward']= "egun 1 aurera";
$_lang['Sum working time']= "lan denbora gehitu";
$_lang['Time: h / m']= "denbora: h / m";
$_lang['activate project stop watch']= "aktibatuta dauden proiektuen erlojua gelditu";
$_lang['activate']= "aktibatu";
$_lang['project choice']= "projektua aukeratu";
$_lang['stop stop watch']= "gelditu gelditatuta dagoen erlojua";
$_lang['still to allocate:']= "asignatuta gabe:";
$_lang['You are not allowed to delete entries from timecard. Please contact your administrator']= "You are not allowed to delete entries from timecard. Please contact your administrator";
$_lang['You cannot delete entries at this date. Since there have been %s days. You just can edit entries not older than %s days.']= "You cannot delete entries at this date. Since there have been %s days. You just can edit entries not older than %s days.";
$_lang['You cannot delete bookings at this date. Since there have been %s days. You just can edit bookings of entries not older than %s days.']= "You cannot delete bookings at this date. Since there have been %s days. You just can edit bookings of entries not older than %s days.";
$_lang['You cannot add entries at this date. Since there have been %s days. You just can edit entries not older than %s days.']= "You cannot add entries at this date. Since there have been %s days. You just can edit entries not older than %s days.";
$_lang['You cannot add  bookings at this date. Since there have been %s days. You just can add bookings for entries not older than %s days.']= "You cannot add  bookings at this date. Since there have been %s days. You just can add bookings for entries not older than %s days.";
$_lang['activate+close']="aktibatu+itxi";

// todos
$_lang['accepted'] = "onartuta";
$_lang['rejected'] = "ez onartuta";
$_lang['own'] = "nirea";
$_lang['progress'] = "eginda";
$_lang['delegated to'] = "nori delegatua";
$_lang['Assigned from'] = "norengandik";
$_lang['done'] = "done";
$_lang['Not yet assigned'] = "oraindik asinatuta gabe";
$_lang['Undertake'] = "hasi";
$_lang['New todo'] = "todo berria"; 
$_lang['Notify recipient'] = "Jakinarazi edontziari";

// votum.php
$_lang['results of the vote: '] = "bozketaren emaitza: ";
$_lang['Poll Question: '] = "bozketaren galdera: ";
$_lang['several answers possible'] = "posible da erantzun ezberdinak";
$_lang['Alternative '] = "Alternatiba ";
$_lang['no vote: '] = "boto barik: ";
$_lang['of'] = "-rena";
$_lang['participants have voted in this poll'] = "partehartzaileak hautatu dute";
$_lang['Current Open Polls'] = "Bozketa irekiak";
$_lang['Results of Polls'] = "Bozketa guztien emaitzen zerrenda";
$_lang['New survey'] ="Asterketa berria";
$_lang['Alternatives'] ="Alternatibak";
$_lang['currently no open polls'] = "Momentu honetan ez daude bozketak irekitak";

// export_page.php
$_lang['export_timecard']       = "Esportatu Timecard";
$_lang['export_timecard_admin'] = "Esportatu admin Timecard";
$_lang['export_users']          = "Esportatu talde honen erabiltzaileak";
$_lang['export_contacts']       = "Esportatu kontaktuak";
$_lang['export_projects']       = "Esportatu projektuaren data";
$_lang['export_bookmarks']      = "Esportatu bookmarks";
$_lang['export_timeproj']       = "Esportatu time-to-project data";
$_lang['export_project_stat']   = "Esportatu proiektuen estadistikak";
$_lang['export_todo']           = "Esportatu todos";
$_lang['export_notes']          = "Esportatu notak";
$_lang['export_calendar']       = "Esportatu kalendario ebentu guztiak";
$_lang['export_calendar_detail']= "Esportatu kalendario ebentu bat";
$_lang['submit'] = "bidali";
$_lang['Address'] = "Helbidea";
$_lang['Next Project'] = "Projektu Berria";
$_lang['Dependend projects'] = "Proiektu depedienteak";
$_lang['db_type'] = "Database mota";
$_lang['Log in, please'] = "Log in, mesedez";
$_lang['Recipient'] = "Rezipietea";
$_lang['untreated'] = "untreated";
$_lang['Select participants'] = "Aukeratu partaideak";
$_lang['Participation'] = "Eskuharmena ";
$_lang['not yet decided'] = "erabaki gabe";
$_lang['accept'] = "onartu";
$_lang['reject'] = "arbuiatu";
$_lang['Substitute for'] = "ordeztu";
$_lang['Calendar user'] = "kalendario erabiltzailea";
$_lang['Refresh'] = "Refresh";
$_lang['Event'] = "Ebentoa";
$_lang['Upload file size is too big'] = "Igo nahi duzun artxiboaren tamainua handia da";
$_lang['Upload has been interrupted'] = "Igoera eten da";
$_lang['view'] = "bista";
$_lang['found elements'] = "aurkitutako elementuak";
$_lang['chosen elements'] = "aukeratutako elementuak";
$_lang['too many hits'] = "Erresultadoa handiegia da erakutziteko.";
$_lang['please extend filter'] = "Mesedez handitu zure filtroak.";
$_lang['Edit profile'] = "Aldatu perfila";
$_lang['add profile'] = "Gehi perfila";
$_lang['Add profile'] = "Gehitu perfila";
$_lang['Added profile'] = "Gehitutako perfila(k).";
$_lang['No profile found'] = "Ez dago perfilak.";
$_lang['add project participants'] = "gehitu partaideak projektuari";
$_lang['Added project participants'] = "Proiektuan gehitutako partaideak.";
$_lang['add group of participants'] = "gehitu partaide talde bat";
$_lang['Added group of participants'] = "Partaide talde bat gehituta.";
$_lang['add user'] = "gehitu erabiltzailea";
$_lang['Added users'] = "Erabiltzailea (k) gehituta(s).";
$_lang['Selection'] = "Aukeraketa";
$_lang['selector'] = "aukeratzaile";
$_lang['Send email notification']= "Email&nbsp;notifikazioa";
$_lang['Member selection'] = "Miembro&nbsp;aukeraketa";
$_lang['Collision check'] = "Kolisioa txekea";
$_lang['Collision'] = "Kolisioa";
$_lang['Users, who can represent me'] = "Erabiltzaileak, errepresentatu ditzakena";
$_lang['Users, who can see my private events'] = "Erabiltzaileak, ikusi ditzakenak<br />nere ebentu pribadoak";
$_lang['Users, who can read my normal events'] = "Erabiltzaileak, irakurri ditzakenak<br />nere ebentu normalak";
$_lang['quickadd'] = "Quickadd";
$_lang['set filter'] = "Filtroa ipini";
$_lang['Select date'] = "Data aukeratu";
$_lang['Next serial events'] = "Hurrengo ebentu seriala";
$_lang['All day event'] = "Egun guztiko ebentua";
$_lang['Event is canceled'] = "Ebentua&nbsp;is&nbsp;kitatu";
$_lang['Please enter a password!'] = "Mesedez sartu pasahitza bat!";
$_lang['You are not allowed to create an event!'] = "Ezin duzu ebentu bat sortu!";
$_lang['Event successfully created.'] = "Ebentu sortu da.";
$_lang['You are not allowed to edit this event!'] = "Ezin duzu ebentua modifikatu!";
$_lang['Event successfully updated.'] = "Ebentua modifikatu da.";
$_lang['You are not allowed to remove this event!'] = "Ezin duzu ezabatu ebentua!";
$_lang['Event successfully removed.'] = "Ebentua ezabatu da.";
$_lang['Please give a text!'] = "Mesedez ipini testua!";
$_lang['Please check the event date!'] = "Mesedez berifikatu ebentoaren data!";
$_lang['Please check your time format!'] = "Mesedez berifikatu denboraren formatoa!";
$_lang['Please check start and end time!'] = "Mesedez berifikatu hasi eta bukaeraren denbora!";
$_lang['Please check the serial event date!'] = "Mesedez berifikatu serial ebentuaren data!";
$_lang['The serial event data has no result!'] = "Serial ebentuaren data oker dago!";
$_lang['Really delete this event?'] = "Ezabatu ebentoa?";
$_lang['use'] = "Use";
$_lang[':'] = ":";
$_lang['Mobile Phone'] = "Mobile telefonoa";
$_lang['submit'] = "Bidali";
$_lang['Further events'] = "Weitere Termine";
$_lang['Remove settings only'] = "Remove settings only";
$_lang['Settings removed.'] = "Settings removed.";
$_lang['User selection'] = "User selection";
$_lang['Release'] = "Jaurtiketa";
$_lang['none'] = "inor";
$_lang['only read access to selection'] = "bakarrik aukeratzen idatz lorpidea";
$_lang['read and write access to selection'] = "aukeratzen irakurri eta idatz lorpidea";
$_lang['Available time'] = "Denbora baliagarria";
$_lang['flat view'] = "Lista bista";
$_lang['o_dateien'] = "Filemanager";
$_lang['Location'] = "Kokaera";
$_lang['date_received'] = "date_received";
$_lang['subject'] = "gaia";
$_lang['kat'] = "Kategoria";
$_lang['projekt'] = "Projektua";
$_lang['Location'] = "Kokaera";
$_lang['name'] = "Tituloa";
$_lang['contact'] = "Kontaktoa";
$_lang['div1'] = "Erstellung";
$_lang['div2'] = "Änderung";
$_lang['kategorie'] = "Kategoria";
$_lang['anfang'] = "Hasi";
$_lang['ende'] = "Bkatu";
$_lang['status'] = "Estatua";
$_lang['filename'] = "Tituloa";
$_lang['deadline'] = "Bukaera";
$_lang['ext'] = "an";
$_lang['priority'] = "Prioritatea";
$_lang['project'] = "Projektua";
$_lang['Accept'] = "Onartu";
$_lang['Please enter your user name here.'] = "Mesedez sartu zure izena hemen.";
$_lang['Please enter your password here.'] = "Mesedez sartu zure pasahitza hemen.";
$_lang['Click here to login.'] = "Sakatu hemen sartzeko.";
$_lang['No New Polls'] = "Enkuesta berririk ez";
$_lang['&nbsp;Hide read elements'] = "&nbsp;Eskutatu irakurritako elementuak";
$_lang['&nbsp;Show read elements'] = "&nbsp;Erakutzi irakurritako elementuak";
$_lang['&nbsp;Hide archive elements'] = "&nbsp;Ezkutatu artxibatutako elementuak";
$_lang['&nbsp;Show archive elements'] = "&nbsp;Erakutzi artxibatutako elementuak";
?>