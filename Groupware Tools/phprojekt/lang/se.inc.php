<?php
// se.inc.php, Swedish version, rev.25 june. 2005 /Tib
// translation by Sven-Erik Tiberg <set@dc.luth.se> and Andrzej Szyszkiewicz <Andrzej.Szyszkiewicz@data.slu.se> 18 dec. 2000

$chars = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","Å","Ä","Ö");
$name_month = array("", "Jan", "Feb", "Mar", "Apr", "Maj", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dec");
$l_text31a = array("standard", "15 min.", "30 min.", " 1 timme", " 2 timmar", " 4 timmar", " 1 dag");
$l_text31b = array(0, 15, 30, 60, 120, 240, 1440);
$name_day = array("Söndag", "Måndag", "Tisdag", "Onsdag", "Torsdag", "Fredag", "Lördag");
$name_day2 = array("Må", "Ti", "On", "To", "Fr", "Lö", "Sö");

$_lang['No Entries Found']= "Inga svar har hittats";
$_lang['No Todays Events']= "Ingen händelse idag";
$_lang['No new forum postings']= "Inga nya inlägg i forumet";
$_lang['in category']= "i kategorier";
$_lang['Filtered']= "Filtererad";
$_lang['Sorted by']= "Sorterad efter";
$_lang['go'] = "utför";
$_lang['back'] = "åter";
$_lang['print'] = "Skriv ut";
$_lang['export'] = "exportera";
$_lang['| (help)'] = "| (hjälp/anvisningar)";
$_lang['Are you sure?'] = "är du säker?";
$_lang['items/page'] = "punkter per sida";
$_lang['records'] = "poster"; // elements
$_lang['previous page'] = "föregående sida";
$_lang['next page'] = "nästa sida";
$_lang['first page'] = "första sidan";
$_lang['last page'] = "sista sidan";
$_lang['Move']  = "Flytta";
$_lang['Copy'] = "Kopiera";
$_lang['Delete'] = "Ta bort";
$_lang['delete'] = "delete";
$_lang['Save'] = "spara";
$_lang['Directory'] = "mapp";
$_lang['Also Delete Contents'] = "ta bort innehållet";
$_lang['Sum'] = "Summa";
$_lang['Filter'] = "Filtrera";
$_lang['Please fill in the following field'] = "Fyll i följande fält";
$_lang['approve'] = "bekräfta";
$_lang['undo'] = "återkalla";
$_lang['Please select!']="Välj!";
$_lang['New'] = "Ny";
$_lang['Select all'] = "markera alla";
$_lang['Printable view'] = "Skrivarvänligt format";
$_lang['New record in module '] = "Ny post i modul ";
$_lang['Notify all group members'] = "Meddela alla gruppmedlemmar";
$_lang['Yes'] = "Ja";
$_lang['No'] = "Nej";
$_lang['Close window'] = "Stäng fönster";
$_lang['No Value'] = "Inget värde";
$_lang['Standard'] = "Standard";
$_lang['Create'] = "Skapa";
$_lang['Modify'] = "Ändra";   
$_lang['today'] = "Idag";

// admin.php
$_lang['Password'] = "Lösenord";
$_lang['Login'] = "Logga in";
$_lang['Administration section'] = "Administrativa delar";
$_lang['Your password'] = "Ditt lösenord för dessa funktioner";
$_lang['Sorry you are not allowed to enter. '] = " Du har tyvärr inte rättighet att logga in";
$_lang['Help'] = "Hjälp";
$_lang['User management'] = "Användar hantering";
$_lang['Create'] = "ny";
$_lang['Projects'] = "Projekt hantering";
$_lang['Resources'] = "Resurs hantering";
$_lang['Resources management'] = "Resources management";
$_lang['Bookmarks'] = "Bokmärke hantering";
$_lang['for invalid links'] = "efter tappade länkar";
$_lang['Check'] = "Kontrollera";
$_lang['delete Bookmark'] = "ta bort bokmärken";
$_lang['(multiple select with the Ctrl-key)'] = "( möjlighet till flera val med 'Ctrl'-tangenten)";
$_lang['Forum'] = "Forum";
$_lang['forum'] = "Forum";
$_lang['Threads older than'] = "ärenden äldre än";
$_lang[' days '] = " dagar ";
$_lang['Chat'] = "E-samtal";
$_lang['save script of current Chat'] = "Spara inlägg av aktuellt e-samtal";
$_lang['Chat script'] = "inlägg i e-samtal";
$_lang['New password'] = "Nytt lösenord";
$_lang['(keep old password: leave empty)'] = "(lämna tomt för att behålla nuvarande lösenord)";
$_lang['Default Group<br> (must be selected below as well)'] = "Standard Grupp<br> (måste väljas  nedan också)";
$_lang['Access rights'] = "Användar rättigheter";
$_lang['Zip code'] = "Postnummer";
$_lang['Language'] = "Språk";
$_lang['schedule readable to others'] = "punkter i kalendern som är publika";
$_lang['schedule invisible to others'] = "punkter i kalendern som är privata";
$_lang['schedule visible but not readable'] = "kalendern är synlig för andra men kan inte läsas";
$_lang['these fields have to be filled in.'] = "dessa fält måste fyllas i.";
$_lang['You have to fill in the following fields: family name, short name and password.'] = "du måste fylla i fälten: efternamn, signatur och lösenord";
$_lang['This family name already exists! '] = "Detta efternamn är upptaget!, lägg till ett andra efternamn eller en bokstav/siffra ";
$_lang['This short name already exists!'] = "Denna signatur är upptagen!";
$_lang['This login name already exists! Please chosse another one.'] = "Detta inloggningsnamn finns redan! Vänligen välj ett annat.";
$_lang['This password already exists!'] = "Detta lösenord är upptaget!";
$_lang['This combination first name/family name already exists.'] = "Denna kombination av för och efternamn finns redan.";
$_lang['the user is now in the list.'] = "användaren är registrerad i systemet.";
$_lang['the data set is now modified.'] = "informationen för användaren";
$_lang['Please choose a user'] = "Välj en användare";
$_lang['is still listed in some projects. Please remove it.'] = " ingår i några projekt, var snäll och ta bort användaren från projekt-listan";
$_lang['All profiles are deleted'] = "Alla profiler är raderade";
$_lang['A Profile with the same name already exists'] = "Det finns redan en profil med samma namn";
$_lang['is taken out of all user profiles'] = "är bortagna ur alla profiler";
$_lang['All todo lists of the user are deleted'] = "Användarens \"att göra\" listor är raderade";
$_lang['is taken out of these votes where he/she has not yet participated'] = "är bortagan från detta val, då han/hon ännu inte anmält sig som deltagare";
$_lang['All events are deleted'] = "Alla aktiviter är borttagna";
$_lang['user file deleted'] = "användarens data är borttagna";
$_lang['bank account deleted'] = "bankkontot är borttaget";
$_lang['finished'] = "klart";
$_lang['Please choose a project'] = "Välj ett projekt";
$_lang['The project is deleted'] = "Projektet är avslutat och borttaget";
$_lang['All links in events to this project are deleted'] = "All länkar till aktiviteter i detta projekt är borttagna";
$_lang['The duration of the project is incorrect.'] = "Projekts varatighet är inte korrekt.";
$_lang['The project is now in the list'] = "Projekt är infört i projektlistan";
$_lang['The project has been modified'] = "Projektet har modifierats";
$_lang['Please choose a resource'] = "Välj ut en resurs";
$_lang['The resource is deleted'] = "Resursen är borttagen";
$_lang['All links in events to this resource are deleted'] = "Alla länkar som var kopplade till händelser i detta projekt är borttagna";
$_lang[' The resource is now in the list.'] = " Resursen finns nu i resurslistan.";
$_lang[' The resource has been modified.'] = " Resursen är modifierad.";
$_lang['The server sent an error message.'] = "Servern har skickat följande felmeddelande";
$_lang['All Links are valid.'] = "All länkar är aktiva.";
$_lang['Please select at least one bookmark'] = "Var vänlig och välj minst ett bokmärke";
$_lang['The bookmark is deleted'] = "Bokmärket är borttaget";
$_lang['threads older than x days are deleted.'] = "Alla bidrag som är äldre än x har tagits bort.";
$_lang['All chat scripts are removed'] = "Alla e-samtal är borttagna";
$_lang['or'] = "eller";
$_lang['Timecard management'] = "Tidkorts hantering";
$_lang['View'] = "Visa";
$_lang['Choose group'] = "Välj grupp";
$_lang['Group name'] = "Gruppnamn";
$_lang['Short form'] = "Signatur ";
$_lang['Category'] = "Kategori";
$_lang['Remark'] = "Kommentar";
$_lang['Group management'] = "Grupphantering";
$_lang['Please insert a name'] = "Ange ett namn";
$_lang['Name or short form already exists'] = "Namn eller signatur finns redan";
$_lang['Automatic assign to group:'] = "Automatiskt tilldelning till gruppen:";
$_lang['Automatic assign to user:'] = "Automatiskt tilldelning till användare:";
$_lang['Help Desk Category Management'] = "ärende system (RTS) kategori hantering";
$_lang['Category deleted'] = "Kategori borttagen";
$_lang['The category has been created'] = "Kategorin har skapats";
$_lang['The category has been modified'] = "Kategorin har modifierats";
$_lang['Member of following groups'] = "Medlem av följande grupper";
$_lang['Primary group is not in group list'] = "Standard gruppen finns inte i grupplistan";
$_lang['Login name'] = "Inloggningsnamn";
$_lang['You cannot delete the default group'] = "Du kan inte ta bort standardgruppen";
$_lang['Delete group and merge contents with group'] = "Ta bort en grupp och slå samman innehållet till gruppen";
$_lang['Please choose an element'] = "Välj ett element";
$_lang['Group created'] = "Gruppen är skapad";
$_lang['File management'] = "Fil hantering";
$_lang['Orphan files'] = "Filer som har raderad ägare";
$_lang['Deletion of super admin root not possible'] = "Du kan inte ta bort ( super admin root )";
$_lang['ldap name'] = "ldap namn";
$_lang['mobile // mobile phone'] = "mobil nummer"; // mobil phone
$_lang['Normal user'] = "Normal användare";
$_lang['User w/Chief Rights'] = "Användare med chefs rättigheter";
$_lang['Administrator'] = "Administratör";
$_lang['Logging'] = "Logga";
$_lang['Logout'] = "Logga ut";
$_lang['posting (and all comments) with an ID'] = "posta (inkl. alla kommentarer) med ett ID";
$_lang['Role deleted, assignment to users for this role removed'] = "Roll borttagen, användare med denna roll har modifierats";
$_lang['The role has been created'] = "Roll skapad";
$_lang['The role has been modified'] = "Rollen har modifierats";
$_lang['Access rights'] = "Användar rättigheter";
$_lang['Usergroup'] = "Användargrupp";
$_lang['logged in as'] = "Inloggad som";

//chat.php
$_lang['Quit chat']= "Lämna chat";

//contacts.php
$_lang['Contact Manager'] = "Kontakt hanterare";
$_lang['New contact'] = "Ny kontakt";
$_lang['Group members'] = "Gruppmedlemmar";
$_lang['External contacts'] = "Externa kontakter";
$_lang['&nbsp;New&nbsp;'] = "&nbsp;Ny&nbsp;";
$_lang['Import'] = "Import";
$_lang['The new contact has been added'] = "Den nya kontakten har lagts till";
$_lang['The date of the contact was modified'] = "Kontakten har ändrats";
$_lang['The contact has been deleted'] = "Kontakten har tagits bort";
$_lang['Open to all'] = "öppen för alla";
$_lang['Picture'] = "Bild";
$_lang['Please select a vcard (*.vcf)'] = "Välj ett e-visitkort / vcard (*.vcf)";
$_lang['create vcard'] = "Skapa ett e-visitkort / vcard";
$_lang['import address book'] = "importera adressboken";
$_lang['Please select a file (*.csv)'] = "Välj en fil (*.csv)";
$_lang['Howto: Open your outlook express address book and select file/export/other book<br>Then give the file a name, select all fields in the next dialog and finish'] = 
"Hur gör man: öppna din outlook adressbok och välj 'file'/'export'/'other book'<br> Välj 'text' som filtyp och markera alla fälten i nästa dialogruta och välj 'finish'";
$_lang['Open outlook at file/export/export in file,<br>choose comma separated values (Win), then select contacts in the next form,<br>give the export 
file a name and finish.'] = "öppna i outlook 'file/export/export in file',<br> välj 'komma separated values (WIN)', välj sedan 'kontakter' i 
nästa formruta,<br> ge exportfilen ett namn och slutför.";
$_lang['Please choose an export file (*.csv)'] = "Välj en exportfil (*.csv)";
$_lang['Please export your address book into a comma separated value file (.csv), and either<br>1) apply an import pattern OR<br>2) modify the 
columns of the table with a spread sheet to this format<br>(Delete colums in you file that are not listed here and create empty colums for fields that do not exist in your file):'] = "Vänligen exportera din adressbok till en kommaseparerad fil (.csv), Och antingen<br> 
1) Använd en importmall ELLER<br> 
2) modifiera filen i excel för att passa detta format<br> (Ta bort kolumner som inte finns här och lägg till tomma kolumner för dom som saknas i din fil):";
$_lang['Please insert at least the family name'] = "Ange minst ett efternamnet";
$_lang['Record import failed because of wrong field count'] = "Importen av filen gick inte att göra då det är fel antal kolumner";
$_lang['Import to approve'] = "Importera för godkännande";
$_lang['Import list'] = "Importera listan";
$_lang['The list has been imported.'] = "Listan har importerats.";
$_lang['The list has been rejected.'] = "Listan har inte importerats p.g.a. fel i listan.";
$_lang['Profiles'] = "Profiler";
$_lang['Parent object'] = "Föräldra objekt";
$_lang['Check for duplicates during import'] = "Kontrollera dubbletter vid import";
$_lang['Fields to match'] = "Fält att matcha";
$_lang['Action for duplicates'] = "Händelse vid dubbletter";
$_lang['Discard duplicates'] = "Avvisa dubblett";
$_lang['Dispose as child'] = "Ange som underobjekt";
$_lang['Store as profile'] = "Spara som profil";    
$_lang['Apply import pattern'] = "Applicera import specifikation";
$_lang['Import pattern'] = "Import specifikation";
$_lang['For modification or creation<br>upload an example csv file'] = "Ladda upp import fil (csv)";
$_lang['Skip field'] = "Hoppa över fält";
$_lang['Field separator'] = "Fält separator";
$_lang['Contact selector'] = "Kontakt väljare";
$_lang['Use doublet'] = "Använd dublett";
$_lang['Doublets'] = "Dubletter";

// filemanager.php
$_lang['Please select a file'] = "Välj en fil";
$_lang['A file with this name already exists!'] = "En fil med detta namn finns redan!";
$_lang['Name'] = "Namn";
$_lang['Comment'] = "Kommentar";
$_lang['Date'] = "Datum";
$_lang['Upload'] = "Spara";
$_lang['Filename and path'] = "Filnamn och sökväg";
$_lang['Delete file'] = "Radera filen";
$_lang['Overwrite'] = "Skriv över";
$_lang['Access'] = "rättigheter";
$_lang['Me'] = "jag";
$_lang['Group'] = "grupp";
$_lang['Some'] = "några";
$_lang['As parent object'] = "samma som bibliotek";
$_lang['All groups'] = "Alla grupper";
$_lang['You are not allowed to overwrite this file since somebody else uploaded it'] = "Du kan inte skriva ned denna fil just nu eftersom någon annan använder den";
$_lang['personal'] = "personlig";
$_lang['Link'] = "Länk";
$_lang['name and network path'] = "Ange nätverks sökväg och filnamn";
$_lang['with new values'] = "med nya värden";
$_lang['All files in this directory will be removed! Continue?'] = "Alla filer i denna folder kommer att tas bort, vill du göra det?";
$_lang['This name already exists'] = "Namnet är upptaget av en annan fil";
$_lang['Max. file size'] = "Max. fil storlek";
$_lang['links to'] = "länk till";
$_lang['objects'] = "objekt";
$_lang['Action in same directory not possible'] = "Du kan inte ändra i samma folder";
$_lang['Upload = replace file'] = "Upload = ersätt filen";
$_lang['Insert password for crypted file'] = "Ange lösenord för krypterad fil";
$_lang['Crypt upload file with password'] = "kryptera fil med lösenord";
$_lang['Repeat'] = "Repetera";
$_lang['Passwords dont match!'] = "lösenorden stämmer inte överens!";
$_lang['Download of the password protected file '] = "Ladda ned den lösenordskyddade filen ";
$_lang['notify all users with access'] = "meddela alla användare med rättigheter ";
$_lang['Write access'] = "Skriv rättighet";
$_lang['Version'] = "Version";
$_lang['Version management'] = "Versions hantering";
$_lang['lock'] = "lås";
$_lang['unlock'] = "lås upp";
$_lang['locked by'] = "låst av";
$_lang['Alternative Download'] = "Alternativ Nedladdning";
$_lang['Download'] = "Ladda ned";
$_lang['Select type'] = "Välj typ";
$_lang['Create directory'] = "Skapa mapp ";
$_lang['filesize (Byte)'] = "Fil storlek (Byte)";

// filter
$_lang['contains'] = 'Innehåller';
$_lang['exact'] = 'exakt';
$_lang['starts with'] = 'Börjar med';
$_lang['ends with'] = 'slutar med';
$_lang['>'] = '>';
$_lang['>='] = '>=';
$_lang['<'] = '<';
$_lang['<='] = '<=';
$_lang['does not contain'] = 'innehåller inte'; 
$_lang['Please set (other) filters - too many hits!'] = "Vänligen ange ett mer exakt filer - för många träffar!";

$_lang['Edit filter'] = "Ändra filtret";
$_lang['Filter configuration'] = "Konfigurera filter ";
$_lang['Disable set filters'] = "Inaktivera set av filter";
$_lang['Load filter'] = "Ladda filter";
$_lang['Delete saved filter'] = "Ta bort sparat filter";
$_lang['Save currently set filters'] = "Spara aktuellt set av filter";
$_lang['Save as'] = "Spara som";
$_lang['News'] = 'Nyheter';

// form designer
$_lang['Module Designer'] = "Modul Designer";
$_lang['Module element'] = "Modul element";
$_lang['Module'] = "Modul";
$_lang['Active'] = "Aktiv";
$_lang['Inactive'] = "Inaktiv";
$_lang['Activate'] = "Aktivera";
$_lang['Deactivate'] = "Inaktivera";
$_lang['Create new element'] = "Skapa nytt element";
$_lang['Modify element'] = "Modifiera element";
$_lang['Field name in database'] = "Fältnamn i databas";
$_lang['Use only normal characters and numbers, no special characters,spaces etc.'] = "Använd endast normala tecken och siffror, inga specialtecken och mellanrum mm.";
$_lang['Field name in form'] = "Fältnamn på formulär";
$_lang['(could be modified later)'] = "(kan modifieras senare)"; 
$_lang['Single Text line'] = "Enkelradig text";
$_lang['Textarea'] = "Textarea";
$_lang['Display'] = "Visa";
$_lang['First insert'] = "Första fält";
$_lang['Predefined selection'] = "Fördefinierat urval";
$_lang['Select by db query'] = "Välj med databasfråga";
$_lang['File'] = "Fil";

$_lang['Email Address'] = "Epost adress";
$_lang['url'] = "url";
$_lang['Checkbox'] = "Kryssruta";
$_lang['Multiple select'] = "Multipelt val";
$_lang['Display value from db query'] = "Visa värde från databas fråga";
$_lang['Time'] = "Tid";
$_lang['Tooltip'] = "Verktygstips";
$_lang['Appears as a tipp while moving the mouse over the field: Additional comments to the field or explanation if a regular expression is applied'] = "Visas som ett tips när musen hålls över fältet: Extra kommenterar till fältet eller förklaring om en formel används";
$_lang['Position'] = "Position";
$_lang['is current position, other free positions are:'] = "är aktuell position, andra fria positioner är:";
$_lang['Regular Expression:'] = "Reguljärt uttryck:";
$_lang['Please enter a regular expression to check the input on this field'] = "Vänligen ange en formel för att testa detta fält";
$_lang['Default value'] = "Standard värde";
$_lang['Predefined value for creation of a record. Could be used in combination with a hidden field as well'] = "Standardvärde för skapande av post. Kan också användas i samband med ett dolt fält";
$_lang['Content for select Box'] = "Innehåll i urvalsbox";
$_lang['Used for fixed amount of values (separate with the pipe: | ) or for the sql statement, see element type'] = "Används för ett fixed antal värden (separerade med teknet: | ) eller för sql kommandot, se element typ";
$_lang['Position in list view'] = "Postition i list vy";
$_lang['Only insert a number > 0 if you want that this field appears in the list of this module'] = "Ange endast ett nummer > 0 om du vill att detta fält ska visas i listan för denna modul";
$_lang['Alternative list view'] = "Alternativ listvy";
$_lang['Value appears in the alt tag of the blue button (mouse over) in the list view'] = "Värdet visas i alt taggen för den blå knappen (mouse over) i listvyn";
$_lang['Filter element'] = "Filtrera element";
$_lang['Appears in the filter select box in the list view'] = "Visas i filter urvalsboxen i listvyn";
$_lang['Element Type'] = "Element Typ";
$_lang['Select the type of this form element'] = "Välj typ för detta formulär element";
$_lang['Check the content of the previous field!'] = "Kontrollera värdet i föregående fält!";
$_lang['Span element over'] = "Utöka fält över";
$_lang['columns'] = "kolumner";
$_lang['rows'] = "rader";
$_lang['Telephone'] = "Telefon";
$_lang['History'] = "Historia";
$_lang['Field'] = "Fält";
$_lang['Old value'] = "Gammalt värde";
$_lang['New value'] = "Nytt värde";
$_lang['Author'] = "Författare"; 
$_lang['Show Date'] = "Visa datum";
$_lang['Creation date'] = "Datum för skapande";
$_lang['Last modification date'] = "Senast modifierad";
$_lang['Email (at record cration)'] = "Epost (Vid postens skapande)";
$_lang['Contact (at record cration)'] = "kontakt (Vid postens skapande)";
$_lang['Select user'] = "Välj användare";
$_lang['Show user'] = "Visa användare";

// forum.php
$_lang['Please give your thread a title'] = "Ge den nya tråden en rubrik";
$_lang['New Thread'] = "Ny tråd";
$_lang['Title'] = "Titel";
$_lang['Text'] = "Text";
$_lang['Post'] = "Post";
$_lang['From'] = "Från";
$_lang['open'] = "öppen";
$_lang['closed'] = "stängd";
$_lang['Notify me on comments'] = "Meddela mig om det kommer svar";
$_lang['Answer to your posting in the forum'] = "Svar på ditt inlägg i forumet";
$_lang['You got an answer to your posting'] = "Du har fått ett svar på ditt inlägg \n ";
$_lang['New posting'] = "Nya inlägg";
$_lang['Create new forum'] = "Skapa ett nytt forum";
$_lang['down'] ='ner';
$_lang['up']= "upp";
$_lang['Forums']= "Forum";
$_lang['Topics']="Ämne";
$_lang['Threads']="Tråd";
$_lang['Latest Thread']="Senaste tråden";
$_lang['Overview forums']= "Överblick av forumen";
$_lang['Succeeding answers']= "Efterföljande svar";
$_lang['Count']= "Räkna";
$_lang['from']= "frän";
$_lang['Path']= "Path";
$_lang['Thread title']= "Titel för tråden";
$_lang['Notification']= "Meddela";
$_lang['Delete forum']= "Ta bort forum";
$_lang['Delete posting']= "Ta bort inlägg";
$_lang['In this table you can find all forums listed']= "Alla forum finns listade i denna tabell";
$_lang['In this table you can find all threads listed']= "Alla trådar finns listade i denna tabell";

// index.php
$_lang['Last name'] = "Efternamn";
$_lang['Short name'] = "Signatur";
$_lang['Sorry you are not allowed to enter.'] = "Du har inte tillåtelse att öppna detta.";
$_lang['Please run index.php: '] = "Kör index.php: ";
$_lang['Reminder'] = "Påminnare";
$_lang['Session time over, please login again'] = "Sessions tiden har löpt ut, logga in igen";
$_lang['&nbsp;Hide read elements'] = "&nbsp;Göm lästa element";
$_lang['&nbsp;Show read elements'] = "&nbsp;Visa lästa element";
$_lang['&nbsp;Hide archive elements'] = "&nbsp;Hide arkiverade element";
$_lang['&nbsp;Show archive elements'] = "&nbsp;Visa arkiverade element";
$_lang['Tree view'] = "Träd vy";
$_lang['flat view'] = "Platt vy";
$_lang['New todo'] = "Ny att göra";
$_lang['New note'] = "Ny notering";
$_lang['New document'] = "Nytt dokument";
$_lang['Set bookmark'] = "Skapa bokmärke";
$_lang['Move to archive'] = "Flytta till arkhiv";
$_lang['Mark as read'] = "Markera som läst";
$_lang['Export as csv file'] = "Exportera som csv fil";
$_lang['Deselect all'] = "Ångra urvalet allt";
$_lang['selected elements'] = "valda element";
$_lang['wider'] = "bredare";
$_lang['narrower'] = "smalare";
$_lang['ascending'] = "Stigande";
$_lang['descending'] = "Fallande";
$_lang['Column'] = "Kolumn";
$_lang['Sorting'] = "Sortera";
$_lang['Save width'] = "Spara bredd";
$_lang['Width'] = "Bredd";
$_lang['switch off html editor'] = "stäng av html editor";
$_lang['switch on html editor'] = "slå på html editor";
$_lang['hits were shown for'] = "träffar visas för";
$_lang['there were no hits found.'] = "det gav inga träffar.";
$_lang['Filename'] = "Filenamn";
$_lang['First Name'] = "Förnamn";
$_lang['Family Name'] = "Efternamn";
$_lang['Company'] = "Företag";
$_lang['Street'] = "Gatuadress";
$_lang['City'] = "Ort/Stad";
$_lang['Country'] = "Land";
$_lang['Please select the modules where the keyword will be searched'] = "Ange vilka moduler som skall sökas i";
$_lang['Enter your keyword(s)'] = "Ange nyckelord";
$_lang['Salutation'] = "Hälsning";
$_lang['State'] = "Län";
$_lang['Add to link list'] = "Lägg till i länk listan";

// setup.php
$_lang['Welcome to the setup of PHProject!<br>'] = "Välkommen att sätta upp PHProjekt!<br>";
$_lang['Please remark:<ul><li>A blank database must be available<li>Please ensure that the webserver is able to write the file config.inc.php'] = "Notera att:<ul> 
<li>Det måste finnas en tom databas. 
<li>Konfigurera webservern att tillåta skrivning av filen 'config.inc.php' för webserverns publika konto<br> (e.g. 'chmod 777')";
$_lang['<li>If you encounter any errors during the installation, please look into the <a href=help/faq_install.html target=_blank>install faq
</a>or visit the <a href=http://www.PHProjekt.com/forum.html target=_blank>Installation forum</a></i>'] = "<li>Om du får problem under installationen, ta en titt på <a href='help/faq_install.html' target=_blank>install faq</a> eller besök <a href='http://www.PHProjekt.com/forum.html' target=_blank>Installations forum</a></i>";
$_lang['Please fill in the fields below'] = "Fyll i fälten nedan";
$_lang['(In few cases the script wont respond.<br>Cancel the script, close the browser and try it again).<br>'] = "(Det kan hända att scriptet inte körs).<br> 
Avsluta scriptet och web-läsaren, prova sedan igen<br>";
$_lang['Type of database'] = "Databas typ";
$_lang['Hostname'] = "namnet på servern som kör databasen";
$_lang['Username'] = "Användarnamn";

$_lang['Name of the existing database'] = "Namnet på en existernade databas";
$_lang['config.inc.php not found! Do you really want to update? Please read INSTALL ...'] = "Hittar inte filen config.inc.php! Vill du fortsätta med uppgraderingen av PHProject? Läs igenom INSTALL ...";
$_lang['config.inc.php found! Maybe you prefer to update PHProject? Please read INSTALL ...'] = "Hittat filen config.inc.php! Vi du fortsätta med uppgraderingen av PHProject? Läs igenom INSTALL ...";
$_lang['Please choose Installation,Update or Configure!'] = "Välj \"Installtion\" eller \"uppdatering\"! sen åter.....";
$_lang['Sorry, I cannot connect to the database! <br>Please close all browser windows and restart the installation.'] = "Det har blivit fel på installationen <br>åtgärda felet och prova att installera igen.";
$_lang['Sorry, it does not work! <br> Please set DBDATE to Y4MD- or let phprojekt change this environment-variable (php.ini)!'] = "Det blev ett fel under installationen! <br> Sätt DBDATE till 'Y4MD-' eller låt phprojekt modifiera denna inställnings-variabel (php.ini)!";
$_lang['Seems that You have a valid database connection!'] = "Gratulerar, du har en aktiv databasanslutning!";
$_lang['Please select the modules you are going to use.<br> (You can disable them later in the config.inc.php)<br>'] = "Välj de moduler som du vill använda.<br> (Du kan avaktivera dom senare i config.inc.php)<br>";
$_lang['Install component: insert a 1, otherwise keep the field empty'] = "Installera modulerna: infoga '1', eller lämna fältet tomt";
$_lang['Group views'] = "Grupp vy i kalendern";
$_lang['Todo lists'] = "Att göra lista";

$_lang['Voting system'] = "Omröstnings system";


$_lang['Contact manager'] = "Kontakthanterare";
$_lang['Name of userdefined field'] = "Namn på använda definerade fält";
$_lang['Userdefined'] = "Defienerad av användare";
$_lang['Profiles for contacts'] = "Profil för kontakter";
$_lang['Mail'] = "epost";
$_lang['send mail'] = " skicka epost";
$_lang[' only,<br> &nbsp; &nbsp; full mail client'] = " bara,<br> &nbsp; &nbsp; epost läsare (skicka &amp; läsa)";



$_lang['1 to show appointment list in separate window,<br>&nbsp; &nbsp; 2 for an additional alert.'] = 
"'1' för att visa lista över bokade tider,<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'2' för ytterligare påminnelser.";
$_lang['Alarm'] = "Påminnelse/Larm";
$_lang['max. minutes before the event'] = "max. minuter till påminnelse";
$_lang['SMS/Mail reminder'] = "SMS/Mail påminnelse";
$_lang['Reminds via SMS/Email'] = "Påminnelse via SMS/Email";
$_lang['1= Create projects,<br>&nbsp; &nbsp; 2= assign worktime to projects only with timecard entry<br>
&nbsp; &nbsp; 3= assign worktime to projects without timecard entry<br>&nbsp; &nbsp; 
(Selection 2 or 3 only with module timecard!)'] = "'1'= Skapa projekt,ändra status<br> &nbsp; &nbsp; '2'= ange arbetstid i projekt med endast tidkort<br> &nbsp; &nbsp; '3'= ange arbetstid i projekt utan att använda tidkort<br> ";

$_lang['Name of the directory where the files will be stored<br>( no file management: empty field)'] = "Sökvägen till mappen där filerna lagras<br>( Ingen filhaterare: tom sökväg)";
$_lang['absolute path to this directory (no files = empty field)'] = "absolut sökväg till denna mapp( inga filer = tomt fält)";
$_lang['Time card'] = "Tidkort";
$_lang['1 time card system,<br>&nbsp; &nbsp; 2 manual insert afterwards sends copy to the chief'] = "'1' aktivera tidkort ,<br> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '2' manual lägg till ett tidkort manuellt med kopia till projektledaren";
$_lang['Notes'] = "Noteringar";
$_lang['Password change'] = "ändra lösenord";
$_lang['New passwords by the user - 0: none - 1: only random passwords - 2: choose own'] = "Nytt lösenord för användaren - 0: inget - 1: slumpvis skapade - 2: manuellt skapade";
$_lang['Encrypt passwords'] = "Krypterat lösenord";
$_lang['Login via '] = "Login via ";
$_lang['Extra page for login via SSL'] = "Extra sida for login via SSL";
$_lang['Groups'] = "Gruppen";
$_lang['User and module functions are assigned to groups<br>&nbsp; &nbsp; (recommended for user numbers > 40)'] = 
"Användare och modul funktioner är kopplade till grupper<br> &nbsp;&nbsp;&nbsp;&nbsp;(recommenderas för > 40 användare)";
$_lang['User and module functions are assigned to groups'] = "Användare och funktioner är kopplade till grupper";
$_lang['Help desk'] = "ärende hantering (Help desk)";
$_lang['Help Desk Manager / Trouble Ticket System'] = "Help desk operatör / Fel hanterings system";
$_lang['RT Option: Customer can set a due date'] = "RT Tillägg: Användaren kan sätta senaste giltigt datum";
$_lang['RT Option: Customer Authentification'] = "RT Tillägg: Användar identifiering";
$_lang['0: open to all, email-address is sufficient<br>1: registered contact enter his family name<br>2: his email'] = "0: öppen för alla, email-adress är tillräckligt, 1: kunden måste finnas i systemets användar-lista och ange sig med efternamnet";
$_lang['RT Option: Assigning request'] = "RT Tillägg: Aktivera request";
$_lang['0: by everybody, 1: only by persons with status chief'] = "0: av alla, 1: bara av person med arbetsledaruppgift 'chief'";
$_lang['Email Address of the support'] = "Epostadress till support";
$_lang['Scramble filenames'] = "Kodade fil namn";
$_lang['creates scrambled filenames on the server<br>assigns previous name at download'] = "skapa tillfälliga fil namn på servern<br> 
tilldelar riktiga namnet på filen vid nedladdning";

$_lang['0: last name, 1: short name, 2: login name'] = "0: Efternamn, 1: Signartur, 2: Inloggnings namn";
$_lang['Prefix for table names in db'] = "Prefix för tabellnamnen i databasen";
$_lang['Alert: Cannot create file config.inc.php!<br>Installation directory needs rwx access for your server and rx access to all others.'] = 
"Varning: kan inte skapa filen 'config.inc.php'!<br> det måste vara komplett rättighet för din administrator/server och rx för alla andra.";
$_lang['Location of the database'] = "Placering av databasen";
$_lang['Type of database system'] = "Typ av databassystem";
$_lang['Username for the access'] = "Användare för databaskopplingen";
$_lang['Password for the access'] = "Lösenord för databaskopplingen";
$_lang['Name of the database'] = "Namnet på databasen";
$_lang['Prefix for database table names'] = "Prefix för tabellnamn i databasen ";
$_lang['First background color'] = "Primär bakgrundsfärg";
$_lang['Second background color'] = "Sekundär bakgrundsfärg";
$_lang['Third background color'] = "Tredjevalet av bakgrundsfärg";
$_lang['Color to mark rows'] = "Färg för att markera rader";
$_lang['Color to highlight rows'] = "Färg för överstrykningsmarkering";
$_lang['Event color in the tables'] = "Färg på händelser i tabellen";
$_lang['company icon yes = insert name of image'] = "Företags logo: ja = skriv in filnamnet på logon";
$_lang['URL to the homepage of the company'] = "URL till företagets hemsida";
$_lang['no = leave empty'] = "nej = lämna tomt";
$_lang['First hour of the day:'] = "Arbetsdagen börjar:";
$_lang['Last hour of the day:'] = "Arbetsdagen slutar:";
$_lang['An error ocurred while creating table: '] = "Ett fel uppstod vid skapande av tabellen: ";
$_lang['Table dateien (for file-handling) created'] = "Tabellen 'dateien' (för fil hantering) har skapats";
$_lang['File management no = leave empty'] = "Fil hanterare - ingen = lämna tomt";
$_lang['yes = insert full path'] = "ja = fyll in hela sökvägen";
$_lang['and the relative path to the PHProjekt directory'] = "fyll också i relativ sök till root-mappen";
$_lang['Table profile (for user-profiles) created'] = "Tabellen 'profile' (för användar profiler) har skapats";
$_lang['User Profiles yes = 1, no = 0'] = "Grupper ja = 1, nej = 0";
$_lang['Table todo (for todo-lists) created'] = "Tabellen 'todo' (för att-göra listan ) har skapats";
$_lang['Todo-Lists yes = 1, no = 0'] = "Att-göra lista ja = 1, nej = 0";
$_lang['Table forum (for discssions etc.) created'] = "Tabellen 'forum' (för diskussioner m.m.) har skapats";
$_lang['Forum yes = 1, no = 0'] = "Forum ja = 1, nej = 0";
$_lang['Table votum (for polls) created'] = "Tabellen 'votum' (för omröstning) har skapats";
$_lang['Voting system yes = 1, no = 0'] = "Omröstnings system ja = 1, nej = 0";
$_lang['Table lesezeichen (for bookmarks) created'] = "Tabellen 'lesezeichen' (för bokmärken) har skapats";
$_lang['Bookmarks yes = 1, no = 0'] = "Bokmärken ja = 1, nej = 0";
$_lang['Table ressourcen (for management of additional ressources) created'] = "Tabellen 'ressourcen' (för hantering av nya resurser ) har skapats";
$_lang['Resources yes = 1, no = 0'] = "Resurser ja = 1, nej = 0";
$_lang['Table projekte (for project management) created'] = "Tabellen 'projekte' (för projekt hantering) har skapats";
$_lang['Table contacts (for external contacts) created'] = "Tabellen 'contacts' (för externa kontakter) har skapats";
$_lang['Table notes (for notes) created'] = "Tabellen 'notes' (för noteringar) har skapats";
$_lang['Table timecard (for time sheet system) created'] = "Tabellen 'timecard' (for tidkort systemet) har skapats";
$_lang['Table groups (for group management) created'] = "Tabellen groups (för grupp hantering) har skapats";
$_lang['Table timeproj (assigning work time to projects) created'] = "tabellen timeproj (tilldeling av arbetstid till projekt) har skapats";
$_lang['Table rts and rts_cat (for the help desk) created'] = "Tabellerna rts och rts_cat (för help desk) har skapats";
$_lang['Table mail_account, mail_attach, mail_client und mail_rules (for the mail reader) created'] = "Tabellerna mail_account, mail_attach, mail_client och mail_rules (för epost läsaren) har skapats";
$_lang['Table logs (for user login/-out tracking) created'] = "Tabellen logs (för loggin av in- och utloggong av användare) har skapats";
$_lang['Tables contacts_profiles und contacts_prof_rel created'] = "Tabellen contacts_profiles och contacts_prof_rel har skapats";
$_lang['Project management yes = 1, no = 0'] = "Projekthantering ja = 1, nej = 0";
$_lang['additionally assign resources to events'] = "för att lägga in ytterligare resurser i projekt";
$_lang['Address book  = 1, nein = 0'] = "Adressbok  ja = 1, nej = 0";
$_lang['Mail no = 0, only send = 1, send and receive = 2'] = "Epost nej=0, bara skicka = 1, skicka och ta emot = 2";
$_lang['Chat yes = 1, no = 0'] = "Chat Ja = 1, nej = 0";
$_lang['Name format in chat list'] = "Namnformat i chat listan";
$_lang['0: last name, 1: first name, 2: first name, last name,<br> &nbsp; &nbsp; 3: last name, first name'] = "0: efternamn, 1: förnamn, 2: förnamn, efternamn,<br> &nbsp; &nbsp; 3: efternamn, förnamn";
$_lang['Timestamp for chat messages'] = "Tidsmarkering för chat meddelanden";
$_lang['users (for authentification and address management)'] = "Tabellen 'users' (för identifiering och adress hantering) har skapats";
$_lang['Table termine (for events) created'] = "Tabellen 'termine' (för händelser) har skapats";
$_lang['The following users have been inserted successfully in the table user:<br>root - (superuser with all administrative privileges)<br>
test - (chief user with restricted access)'] = "Följande användare har laggts till i tabellen 'user':
<br> 'root' - (administratörer med fullständiga rättiheter)<br> 'test' - (normalänvändare med begränsade rättigheter)";
$_lang['The group default has been created'] = "Gruppen 'default' har skapats";
$_lang['Please do not change anything below this line!'] = "ändra ingenting efter denna rad!";
$_lang['Database error'] = "Fel i databasen";
$_lang['Finished'] = "Klart";
$_lang['There were errors, please have a look at the messages above'] = "Det finns fel, felen är listade i meddelandet ovan";
$_lang['All required tables are installed and <br>the configuration file config.inc.php is rewritten<br>
It would be a good idea to makea backup of this file.<br>'] = "Alla tabeller är installerade och <br> 
konfigurationsfilen 'config.inc.php' är skapad/uppdaterad<br>Du bör nu göra en säkerhetskopia på denna fil.<br> 
Stäng alla web-fönster<br>";
$_lang['The administrator root has the password root. Please change his password here:'] = "Administratorn 'root' har lösenordet 'root'. Byt ut detta lösenord så snart som möjligt.<br>";
$_lang['Please define here a password for the administrator "root":'] = "Please define here a password for the administrator 'root':";
$_lang['The user test is now member of the group default.<br>Now you can create new groups and add new users to the group'] = 
"Användarna 'test' är medlem av gruppen 'default'.<br> Du kan nu skapa nya grupper och lägga till nya medlemmar till grupperna";
$_lang['To use PHProject with your Browser go to <b>index.php</b><br>Please test your configuration, especially the modules Mail and Files.'] = 
"För att använda PHProject i din web-läsare klicka på <b><a href='index.php'>index.php</a></b><br> Prova din konfiguration och speciellt modulerna 'epost' och 'filer'.";

$_lang['Alarm x minutes before the event'] = "Larm x minuter före påminnelse";
$_lang['Additional Alarmbox'] = "Ytterligare påminnelser";
$_lang['Mail to the chief'] = "Post till arbetsledaren";
$_lang['Out/Back counts as: 1: Pause - 0: Workingtime'] = "Ute/Åter sätts till: 1: Paus - 0: Arbetstid";
$_lang['Passwords will now be encrypted ...'] = "Lösenordet kommer nu att krypteras";
$_lang['Filenames will now be crypted ...'] = "Filnamen kommer nu att krypteras ...";
$_lang['Do you want to backup your database right now? (And zip it together with the config.inc.php ...)<br>Of course I will wait!'] = 
"Vill du säkerhets kopiera din database nu? (Och packa den tillsammans med config.inc.php zip-fil...)<br> Jag gör det senare!";
$_lang['Next'] = "Nästa";
$_lang['Notification on new event in others calendar'] = "Skapa nya händelser i andras kalendrar";
$_lang['Path to sendfax'] = "Sökväg till sendfax";
$_lang['no fax option: leave blank'] = "ingen faxfunktion: lämna tom";
$_lang['Please read the FAQ about the installation with postgres'] = "Läs FAQ för installation med postgres";
$_lang['Length of short names<br> (Number of letters: 3-6)'] = "Antal bokstäver i signatur<br> (minst 3, max 6)";
$_lang['If you want to install PHProjekt manually, you find<a href=http://www.phprojekt.com/files/sql_dump.tar.gz target=_blank>here</a> 
a mysql dump and a default config.inc.php'] = "Om du vill installera PHProjekt manuellt, så finns instruktioner på <a href='http://www.phprojekt.com/files/sql_dump.tar.gz' target=_blank>here</a> a mysql dump and a default config.inc.php";
$_lang['The server needs the privilege to write to the directories'] = "Servern måsta ha skrivrättigheter till foldern";
$_lang['Header groupviews'] = "Huvudteman visade i grupper";
$_lang['name, F.'] = "efternamn, F.";
$_lang['shortname'] = "signatur";
$_lang['loginname'] = "användar namn";
$_lang['Please create the file directory'] = "Skapa foldern för filerna";
$_lang['default mode for forum tree: 1 - open, 0 - closed'] = "standard inställning för forum trädet: 1 - expanderad, 0 - minimerad";
$_lang['Currency symbol'] = "Valutasymbol";
$_lang['current'] = "aktuell";
$_lang['Default size of form elements'] = "Standardstorlek för formelement";
$_lang['use LDAP'] = "använd LDAP";
$_lang['Allow parallel events'] = "Tillåt parallella händelser";
$_lang['Timezone difference [h] Server - user'] = "Skillnader i tidzon [timmar] mellan Server och användare";
$_lang['Timezone'] = "Tidszon";
$_lang['max. hits displayed in search module'] = "max. antal träffar som syns i sökmodulen";
$_lang['Time limit for sessions'] = "Tidsgräns för sessioner";
$_lang['0: default mode, 1: Only for debugging mode'] = "0: Standard läge, 1: Felsöknings läge";
$_lang['Enables mail notification on new elements'] = "Aktiverar epost meddelande av nya element";
$_lang['Enables versioning for files'] = "Aktiverar versionskontroll för filer";
$_lang['no link to contacts in other modules'] = "ingen länk till kontakter i andra moduler";
$_lang['Highlight list records with mouseover'] = "Markera listporter med 'mouseover'";
$_lang['Track user login/logout'] = "Logga användar inlogging/utloggning";
$_lang['Access for all groups'] = "Tillgänglig för alla grupper";
$_lang['Option to release objects in all groups'] = "Val för att släppa objekt i alla grupper";
$_lang['Default access mode: private=0, group=1'] = "Standard access läge: privat=0, grupp=1";
$_lang['Adds -f as 5. parameter to mail(), see php manual'] = "Lägger till '-f' som 5. parameter för mail(), se php manualen";
$_lang['end of line in body; e.g. \r\n (conform to RFC 2821 / 2822)'] = "radslut i meddelandekropp; e.g. \\r\\n (i enlighet med RFC 2821 / 2822)";
$_lang['end of header line; e.g. \r\n (conform to RFC 2821 / 2822)'] = "Slut på header rad; e.g. \\r\\n (i enlighet med RFC 2821 / 2822)";
$_lang['Sendmail mode: 0: use mail(); 1: use socket'] = "Sendmail läge: 0: använd mail(); 1: använd socket";
$_lang['the real address of the SMTP mail server, you have access to (maybe localhost)'] = "den riktiga adressen till SMTP mail servern, du har access till (möjligtvis localhost)";
$_lang['name of the local server to identify it while HELO procedure'] = "namn på den lokala servern för att identifiera den under HELO proceduren";
$_lang['Authentication'] = "Autenticiering";
$_lang['fill out in case of authentication via POP before SMTP'] = "fyll i om autenticiering via POP före SMTP ska ske";
$_lang['real username for POP before SMTP'] = "användarnamn för POP före SMTP";
$_lang['password for this pop account'] = "lösenord för detta pop konto";
$_lang['the POP server'] = "POP servern";
$_lang['fill out in case of SMTP authentication'] = "fylls i om SMTP autenticiering ska användas";
$_lang['real username for SMTP auth'] = "Användare för SMTP auth";
$_lang['password for this account'] = "lösenord för kontot";
$_lang['SMTP account data (only needed in case of socket)'] = "SMTP konto data (behövs endast om socketläge används)";
$_lang['No Authentication'] = "Ingen autenticiering"; 
$_lang['with POP before SMTP'] = "med POP före SMTP";
$_lang['SMTP auth (via socket only!)'] = "SMTP auth (via socket endast!)";
$_lang['Log history of records'] = "Loggga historiken för poster";
$_lang['Send'] = " Skicka";
$_lang['Host-Path'] = "Host-Path";
$_lang['Installation directory'] = "Installations folder";
$_lang['0 Date assignment by chief, 1 Invitation System'] = "0 Tid fördelad av chef, 1 Invitera deltagare";
$_lang['0 Date assignment by chief,<br>&nbsp;&nbsp;&nbsp;&nbsp; 1 Invitation System'] = "0 Tid fördelad av chef,<br>&nbsp;&nbsp;&nbsp;&nbsp; 1 Invitera deltagare";
$_lang['Default write access mode: private=0, group=1'] = "Standard mode för skrivrättigheter: privat=0, grupp=1";
$_lang['Select-Option accepted available = 1, not available = 0'] = "Select-Option funktionen tillgänglig = 1, inte tillgänglig = 0";
$_lang['absolute path to host, e.g. http://myhost/'] = "absolut sökväg till host, t.ex. http://myhost/";
$_lang['installation directory below host, e.g. myInstallation/of/phprojekt5/'] = "installation folder, t.ex. myInstallation/of/phprojekt5/";

// l.php
$_lang['Resource List'] = "Resurslista";
$_lang['Event List'] = "Händelselista";
$_lang['Calendar Views'] = "Gruppvy";

$_lang['Personnel'] = "Personal";
$_lang['Create new event'] = "Skapa/ta bort händelse";
$_lang['Day'] = "Dag";

$_lang['Until'] = "till";

$_lang['Note'] = "Notis";
$_lang['Project'] = "Projekt";
$_lang['Res'] = "Resurs";
$_lang['Once'] = "en gång";
$_lang['Daily'] = "dagligen";
$_lang['Weekly'] = "veckovis";
$_lang['Monthly'] = "månadsvis";
$_lang['Yearly'] = "årsvis";

$_lang['Create'] = "skapa";

$_lang['Begin'] = "Början";
$_lang['Out of office'] = "Ute på tjänsteärende";
$_lang['Back in office'] = "Åter från tjänsteärende";
$_lang['End'] = "Slut";
$_lang['@work'] = "@jobb";
$_lang['We'] = "Var";
$_lang['group events'] = "Grupp händelser";
$_lang['or profile'] = "eller profil";
$_lang['All Day Event'] = "händelser under hela dagen";
$_lang['time-axis:'] = "tids-axel:";
$_lang['vertical'] = "vertikal";
$_lang['horizontal'] = "horisontell";
$_lang['Horz. Narrow'] = "hor. begränsad";
$_lang['-interval:'] = "-interval:";
$_lang['Self'] = "Privat";

$_lang['...write'] = "...skriv";

$_lang['Calendar dates'] = "Kalender datum";
$_lang['List'] = "Lista";
$_lang['Year'] = "År";
$_lang['Month'] = "Månad";
$_lang['Week'] = "Vecka";
$_lang['Substitution'] = "Substitution";
$_lang['Substitution for'] = "Substitution for";
$_lang['Extended&nbsp;selection'] = "Extended&nbsp;selection";
$_lang['New Date'] = "New date entered";
$_lang['Date changed'] = "Date changed";
$_lang['Date deleted'] = "Date deleted";

// links
$_lang['Database table'] = "Databas tabell";
$_lang['Record set'] = "Record set";
$_lang['Resubmission at:'] = "Resubmission at:";
$_lang['Set Links'] = "Linkar";
$_lang['From date'] = "Från dag";
$_lang['Call record set'] = "Call record set";


//login.php
$_lang['Please call login.php!'] = "Starta login.php!";

// m1.php
$_lang['There are other events!<br>the critical appointment is: '] = "Flera händelse finns<br>Kritiskt möte: ";
$_lang['Sorry, this resource is already occupied: '] = "Tyvärr, resursen är upptagen: ";
$_lang[' This event does not exist.<br> <br> Please check the date and time. '] = "Händelsen finns ej<br> <br> Kontrollera datum och tid. ";
$_lang['Please check your date and time format! '] = "Kontrollera formatet för datum, och tid! ";
$_lang['Please check the date!'] = "Kontrollera datum!";
$_lang['Please check the start time! '] = "Kontrollera starttid! ";
$_lang['Please check the end time! '] = "Kontrollera sluttid! ";
$_lang['Please give a text or note!'] = "Ange text eller kommentar!";
$_lang['Please check start and end time! '] = "kontrollera start- och sluttid! ";
$_lang['Please check the format of the end date! '] = "Kontrollera slutdatumsformat! ";
$_lang['Please check the end date! '] = "Kontrollera slutdatum! ";



$_lang['Resource'] = "Resurs";
$_lang['User'] = "Användare";

$_lang['delete event'] = "Ta bort händelse";
$_lang['Address book'] = "Adressbok";
$_lang['Short Form'] = "Signatur";
$_lang['Phone'] = "Telefon";
$_lang['Fax'] = "Fax";

$_lang['Bookmark'] = "Bokmärke";
$_lang['Description'] = "Beskrivning";
$_lang['Entire List'] = "Komplett lista";

$_lang['New event'] = "Ny händelse";
$_lang['Created by'] = "skapad av";
$_lang['Red button -> delete a day event'] = "Radera en daglig händelse med den röda knappen";
$_lang['multiple events'] = "flera händelser";
$_lang['Year view'] = "Årsvis vy";
$_lang['calendar week'] = "kalendervecka"; 

//m2.php
$_lang['Create &amp; Delete Events'] = "Skapa &amp; ta bort händelser";
$_lang['normal'] = "normal";
$_lang['private'] = "privat";
$_lang['public'] = "allmän";
$_lang['Visibility'] = "Synlighet";

//mail module
$_lang['Please select at least one (valid) address.'] = "Ange minst en (giltig) epost adress.";
$_lang['Your mail has been sent successfully'] = "Epost meddelandet har skickats";
$_lang['Attachment'] = "Bilaga";
$_lang['Send single mails'] = "skicka enstaka e-post";
$_lang['Does not exist'] = "existerar inte";
$_lang['Additional number'] = "tilläggs nummer";  //not. origin "additional numbers" kan vara "ytterligare adresser"
$_lang['has been canceled'] = "har inte skickat";

$_lang['marked objects'] = "markerat objekt";
$_lang['Additional address'] = "Ytterligare mottagare";
$_lang['in mails'] = "i epost";
$_lang['Mail account'] = "Epost konto";
$_lang['Body'] = "Meddelande";
$_lang['Sender'] = "Sändare";

$_lang['Receiver'] = "Mottagare";
$_lang['Reply'] = "Svar";
$_lang['Forward'] = "Vidarebefodra";
$_lang['Access error for mailbox'] = "Fel i rättigheterna till epost-lådan";
$_lang['Receive'] = "Ta emot";
$_lang['Write'] = "Sänd";
$_lang['Accounts'] = "Konton";
$_lang['Rules'] = "Regler";
$_lang['host name'] = "värd namn (host name)";
$_lang['Type'] = "Typ";
$_lang['misses'] = "saknas";
$_lang['has been created'] = "har skapats";
$_lang['has been changed'] = "har ändrats";
$_lang['is in field'] = "är i fältet";
$_lang['and leave on server'] = "Ta emot epost och spara dom på servern";
$_lang['name of the rule'] = "namn på regeln";
$_lang['part of the word'] = "delar av ordet";
$_lang['in'] = "i";
$_lang['sent mails'] = "skickat epost";
$_lang['Send date'] = "Avsänt den";
$_lang['Received'] = "Mottaget";
$_lang['to'] = "till";
$_lang['imcoming Mails'] = "inkommande epost";
$_lang['sent Mails'] = "skickad epost";
$_lang['Contact Profile'] = "Kontakt Profil";
$_lang['unread'] = "ej läst";
$_lang['view mail list'] = "visa post lista";
$_lang['insert db field (only for contacts)'] = "lägg in db fält (bara för kontakter)";
$_lang['Signature'] = "Signatur";

$_lang['SMS'] = "SMS";
$_lang['Single account query'] = "Enkel konto fråga";
$_lang['Notice of receipt'] = "Meddelande om kvitto";
$_lang['Assign to project'] = "Koppla till projekt";
$_lang['Assign to contact'] = "Koppla till kontakt";  
$_lang['Assign to contact according to address'] = "Koppla till kontakt i enlighet med adress";
$_lang['Include account for default receipt'] = "Inkludera konto för standard kvitto";
$_lang['Your token has already been used.<br>If it wasnt you, who used the token please contact your administrator.'] = "Denna token är upptagen.<br>Om du inte använder den, kontakta din administrator";
$_lang['Your token has already been expired.'] = "Din token har passerat slutdatumet";
$_lang['Unconfirmed Events'] = "Ej bekräftade händelser";
$_lang['Visibility presetting when creating an event'] = "Synlighet återställd när man skapar en ny händelse";
$_lang['Subject'] = "Ämne";
$_lang['Content'] = "Innehåll";
$_lang['answer all'] = "svara alla";
$_lang['Create new message'] = "Skapa nytt meddelande";
$_lang['Attachments'] = "Bifogade dokument";
$_lang['Recipients'] = "Mottagare";
$_lang['file away message'] = "meddelande om borttagen fil";
$_lang['Message from:'] = "Meddelande från:";

//notes.php
$_lang['Mail note to'] = "Notis";
$_lang['added'] = "laggt till";
$_lang['changed'] = "ändrad";

// o.php
$_lang['Calendar'] = "Kalender";
$_lang['Contacts'] = "Kontakter";

$_lang['Files'] = "Filer";

$_lang['Options'] = "övrigt";
$_lang['Timecard'] = "Tidkort"; /* Kontrollkort ??? */

$_lang['Helpdesk'] = "Helpdesk";

$_lang['Info'] = "Info";
$_lang['Todo'] = "Uppgifter";
$_lang['News'] = "Nyheter";
$_lang['Other'] = "Övrigt";
$_lang['Settings'] = "Inställningar";
$_lang['Summary'] = "Översikt";

// options.php
$_lang['Description:'] = "Beskrivning:";
$_lang['Comment:'] = "Kommentar:";
$_lang['Insert a valid Internet address! '] = "Fyll i giltig Internetadress! ";
$_lang['Please specify a description!'] = "Fyll i beskrivning!";
$_lang['This address already exists with a different description'] = "denna adress finns redan i annan beskrivning";
$_lang[' already exists. '] = " finns redan. ";
$_lang['is taken to the bookmark list.'] = " lagt till bokmärke.";
$_lang[' is changed.'] = " ändrades.";
$_lang[' is deleted.'] = " togs bort.";
$_lang['Please specify a description! '] = "Fyll i beskrivning! ";
$_lang['Please select at least one name! '] = "Fyll i minst ett namn! ";
$_lang[' is created as a profile.<br>'] = " profilen skapades.<br> Profilen syns efter att du uppdaterat kalender- eller huvudsidan.";
$_lang['is changed.<br>'] = " ändrades.<br> Profilen syns efter att du uppdaterat kalender- eller huvudsidan.";
$_lang['The profile has been deleted.'] = "Profil borttagen.";
$_lang['Please specify the question for the poll! '] = "Fyll i fråga för omröstning! ";
$_lang['You should give at least one answer! '] = "Minst ett svar krävs! ";
$_lang['Your call for votes is now active. '] = "Omröstnings upprop har aktiveras. ";
$_lang['<h2>Bookmarks</h2>In this section you can create, modify or delete bookmarks:'] = "<h4>Bokmärken</h4>Här kan du skapa, ändra och ta bort bokmärken:";
$_lang['Create'] = "skapa";

$_lang['<h2>Profiles</h2>In this section you can create, modify or delete profiles:'] = "<h4>Profil</h4>Här kan du skapa, ändra och ta bort profil:";
$_lang['<h2>Voting Formula</h2>'] = "<h4>Formulär för omröstning</h4>";
$_lang['In this section you can create a call for votes.'] = "Här kan du skapa ärende för omröstning.";
$_lang['Question:'] = "Fråga:";
$_lang['just one <b>Alternative</b> or'] = "endast ett <b>alternativ</b> eller";
$_lang['several to choose?'] = "fler att välja?";

$_lang['Participants:'] = "Deltagare:";

$_lang['<h3>Password Change</h3> In this section you can choose a new random generated password.'] = "<h3>ändra Lösenord</h3> Här kan du generera nytt tillfälligt lösenord.";
$_lang['Old Password'] = "Gammalt lösenord";
$_lang['Generate a new password'] = "Generera nytt lösenord";
$_lang['Save password'] = "Spara lösenord";
$_lang['Your new password has been stored'] = "Ditt nya lösenord har sparats";
$_lang['Wrong password'] = "Felaktigt lösenord";
$_lang['Delete poll'] = "Radera röst";
$_lang['<h4>Delete forum threads</h4> Here you can delete your own threads<br>Only threads without a comment will appear.'] = "<h4>Radera diskussion</h4> du kan bara ta bort dom diskussioner som du startat<br> Endast diskussioner utan kommentarer visas.";

$_lang['Old password'] = "Nuvarande lösenord";
$_lang['New Password'] = "Nytt lösenord";
$_lang['Retype new password'] = "Skriv in det nya lösenordet igen";
$_lang['The new password must have 5 letters at least'] = "Det nya lösenordet måste ha minst 5 bokstäver";
$_lang['You didnt repeat the new password correctly'] = "Skrev du in det nya lösenordet korrekt båda gångerna";

$_lang['Show bookings'] = "Visa bokningar";
$_lang['Valid characters'] = "Tillåtna tecken";
$_lang['Suggestion'] = "Förslag";
$_lang['Put the word AND between several phrases'] = "Använd ordet AND mellan olika fraser"; // translators: please leave the word AND as it is
$_lang['Write access for calendar'] = "Skrivrättigheter för kalender";
$_lang['Write access for other users to your calendar'] = "Skrivrättigheter för andra användare i din kalender";
$_lang['User with chief status still have write access'] = "Användare med chefsrättigheter har fortfarande skrivrättighet";

// projects
$_lang['Project Listing'] = "Projektlista";
$_lang['Project Name'] = "Projektnamn";

$_lang['o_files'] = "Files";
$_lang['o_notes'] = "Notes";
$_lang['o_projects'] = "Projekt";
$_lang['o_todo'] = "Att göra";
$_lang['Copyright']="Copyright";
$_lang['Links'] = "Länkar";
$_lang['New profile'] = "Ny profil";
$_lang['In this section you can choose a new random generated password.'] = "I denna sektion så kan du välja ett slumpmässigt genererat lösenord.";
$_lang['timescale'] = "tidsskala";
$_lang['Manual Scaling'] = "Manuell skalning";
$_lang['column view'] = "kolumn vy";
$_lang['display format'] = "display format";
$_lang['for chart only'] = "Bara för chart:";
$_lang['scaling:'] = "skalning:";
$_lang['colours:'] = "färger";
$_lang['display project colours'] = "visa färgerna för projektet";
$_lang['weekly'] = "veckovis återkommande";
$_lang['monthly'] = "månadsvis återkommande";
$_lang['annually'] = "årligt";
$_lang['automatic'] = "automatiskt";
$_lang['New project'] = "Nytt projekt";
$_lang['Basis data'] = "Grund data";
$_lang['Categorization'] = "kategorisering";
$_lang['Real End'] = "Verkligt slut";
$_lang['Participants'] = "Deltagare";
$_lang['Priority'] = "Prioritet";
$_lang['Status'] = "Status";
$_lang['Last status change'] = "Senaste ändring";
$_lang['Leader'] = "Projektledare";
$_lang['Statistics'] = "Statistik";
$_lang['My Statistic'] = "Min statistik";

$_lang['Person'] = "Person";
$_lang['Hours'] = "Timmar";
$_lang['Project summary'] = "Projektsummering";
$_lang[' Choose a combination Project/Person'] = " Välj kombination Projekt/Person";
$_lang['(multiple select with the Ctrl/Cmd-key)'] = "(fler val med 'Ctrl')";

$_lang['Persons'] = "Person(er)";
$_lang['Begin:'] = "Början:";
$_lang['End:'] = "Slut:";
$_lang['All'] = "Alla";
$_lang['Work time booked on'] = "Arbetstiden bokad för";
$_lang['Sub-Project of'] = "Underprojekt till";
$_lang['Aim'] = "Målsättning";
$_lang['Contact'] = "Kontakt";
$_lang['Hourly rate'] = "timkostnad";
$_lang['Calculated budget'] = "beräknad budget";
$_lang['New Sub-Project'] = "Nytt underprojekt";
$_lang['Booked To Date'] = "Bokad just nu";
$_lang['Budget'] = "Budget";
$_lang['Detailed list'] = "Detaljerad lista";
$_lang['Gantt'] = "Tidslinje";
$_lang['offered'] = "erbjuden";
$_lang['ordered'] = "avdelad";
$_lang['Working'] = "arbetar";
$_lang['ended'] = "avslutat";
$_lang['stopped'] = "stannad";
$_lang['Re-Opened'] = "öppnad igen";
$_lang['waiting'] = "väntar";
$_lang['Only main projects'] = "Bara huvud projekt";
$_lang['Only this project'] = "Bara för detta projekt";
$_lang['Begin > End'] = "Början > Slut";
$_lang['ISO-Format: yyyy-mm-dd'] = "ISO-Format: åååå-mm-dd";
$_lang['The timespan of this project must be within the timespan of the parent project. Please adjust'] = "Tidsomfånget på detta projekt måste ligga inom tidsomfången för huvud projektet.  Korrigera tidsomfången ";
$_lang['Please choose at least one person'] = "Välj minst en person";
$_lang['Please choose at least one project'] = "Välj minst ett projekt";
$_lang['Dependency'] = "Beroende";
$_lang['Previous'] = "Föregående";

$_lang['cannot start before the end of project'] = "Kan inte börja innan slutet på projekt";
$_lang['cannot start before the start of project'] = "Kan inte starta före starten av projekt";
$_lang['cannot end before the start of project'] = "Kan inte sluta innan starten av projekt";
$_lang['cannot end before the end of project'] = "Kan inte sluta innan slutet av projekt";
$_lang['Warning, violation of dependency'] = "Varning, brott mot beroenden";
$_lang['Container'] = "kontainer";
$_lang['External project'] = "Externa projekt";
$_lang['Automatic scaling'] = "Automatisk skalning";
$_lang['Legend'] = "Historia";
$_lang['No value'] = "Inget värde";
$_lang['Copy project branch'] = "Kopiera projekt gren";
$_lang['Copy this element<br> (and all elements below)'] = "Kopiera detta element<br> (och alla underliggande element)";
$_lang['And put it below this element'] = "Och infoga under detta element";
$_lang['Edit timeframe of a project branch'] = "Ändra tidsramen för en projektgren"; 

$_lang['of this element<br> (and all elements below)'] = "av detta element<br> (och alla underliggande element)";
$_lang['by'] = "av";
$_lang['Probability'] = "Sannolikhet";
$_lang['Please delete all subelements first'] = "Vänligen radera alla underprojekt först";
$_lang['Assignment'] ="Tilldelning";
$_lang['display'] = "Display";
$_lang['Normal'] = "Normal";
$_lang['sort by date'] = "Sortera per dag";
$_lang['sort by'] = "Sortera per";
$_lang['Calculated budget has a wrong format'] = "Den beräknade budgeten har ett felaktigt format";
$_lang['Hourly rate has a wrong format'] = "Timkostnaden har ett felaktigt format";

// r.php
$_lang['please check the status!'] = "Kontrollera status!";
$_lang['Todo List: '] = "Att Göra: ";
$_lang['New Remark: '] = "Ny minneslapp: ";
$_lang['Delete Remark '] = "Ta bort minneslapp";
$_lang['Keyword Search'] = "Fulltextsökning: ";
$_lang['Events'] = "Händelse";
$_lang['the forum'] = "i forum";
$_lang['the files'] = "i filer";
$_lang['Addresses'] = "Adresser";
$_lang['Extended'] = "Utökat";
$_lang['all modules'] = "alla moduler";
$_lang['Bookmarks:'] = "Bokmärke:";
$_lang['List'] = "Lista";
$_lang['Projects:'] = "Projekt:";

$_lang['Deadline'] = "Tid";

$_lang['Polls:'] = "Röster:";

$_lang['Poll created on the '] = "Ömröstning skapad ";


// reminder.php
$_lang['Starts in'] = "börjar om";
$_lang['minutes'] = "minut(er)";
$_lang['No events yet today'] = "Ingen händelse för tillfället bokad idag";
$_lang['New mail arrived'] = "Ny post har anlänt";

//ress.php

$_lang['List of Resources'] =  "Resurslista";
$_lang['Name of Resource'] = "Resursnamn";
$_lang['Comments'] =  "Kommentar";


// roles
$_lang['Roles'] = "Roller";
$_lang['No access'] = "Inga rättigheter";
$_lang['Read access'] = "Läsrättighet";
$_lang['Role'] = "Roll";

// helpdesk - rts
$_lang['Request'] = "ärende";
$_lang['pending requests'] = "ärenden i väntelista";
$_lang['show queue'] = "visa ärendekö";
$_lang['Search the knowledge database'] = "Sök i kunskaps databasen";
$_lang['Keyword'] = "Nyckelord";
$_lang['show results'] = "visa resultatet";
$_lang['request form'] = "ärende formulär";
$_lang['Enter your keyword'] = "skriv in nyckelord";
$_lang['Enter your email'] = "Skriv in din email-adress";
$_lang['Give your request a name'] = "Ge ditt ärende ett namn";
$_lang['Describe your request'] = "Beskriv ditt ärende";
$_lang['Due date'] = "Giltig till datum";
$_lang['Days'] = "Dagar";
$_lang['Sorry, you are not in the list'] = "Du är finns tyvärr inte i listan";
$_lang['Your request Nr. is'] = "Ditt ärendenummer är";
$_lang['Customer'] = "Kund";
$_lang['Search'] = "Sök";
$_lang['at'] = "hos";
$_lang['all fields'] = "alla fält";
$_lang['Solution'] = "Svar";
$_lang['AND'] = "OCH";
$_lang['pending'] = "i väntekön";
$_lang['stalled'] = "vilande";
$_lang['moved'] = "flyttat";
$_lang['solved'] = "klart";
$_lang['Submit'] = "Datum";
$_lang['Ass.'] = "hos";
$_lang['Pri.'] = "Pri.";
$_lang['access'] = "tillgång";
$_lang['Assigned'] = "Ansvarig";
$_lang['update'] = "uppdatera";
$_lang['remark'] = "Kommetar";
$_lang['solve'] = "Svar";
$_lang['stall'] = "vilande";
$_lang['cancel'] = "tillbaka";
$_lang['Move to request'] = "Flytta till ärende";
$_lang['Dear customer, please refer to the number given above by contacting us.Will will perform your request as soon as possible.'] = "Kan du vara vänlig och ange detta ärende nummer vid frågor till oss. Vi kommer att börja med ditt ärende inom kort.";
$_lang['Your request has been added into the request queue.<br>You will receive a confirmation email in some moments.'] = "Din ärende finns nu i ärende kön.<br> Du kommer inom kort att få ett e-mail som bekräftelse.";
$_lang['n/a'] = "inte tillgänglig";
$_lang['internal'] = "internt";
$_lang['has reassigned the following request'] = "har skickat följande ärende till kollega";
$_lang['New request'] = "Nytt ärende";
$_lang['Assign work time'] = "Boka arbetstid";
$_lang['Assigned to:'] = "Tilldelad till:";
$_lang['Your solution was mailed to the customer and taken into the database.'] = "Ditt svar har epostats till kunden och sparats i databasen.";
$_lang['Answer to your request Nr.'] = "Svar på ditt ärende nummer";
$_lang['Fetch new request by mail'] = "Hämta ärende med epost";
$_lang['Your request was solved by'] = "Ditt ärende har utförts av";
$_lang['Your solution was mailed to the customer and taken into the database'] = "Ditt förslag på lösning har skickats till kunden som har detta problem och sparats i informations databasen. Tack för din hjälp";
$_lang['Search term'] = "Sök term";
$_lang['Search area'] = "Sök område ";
$_lang['Extended search'] = "Utökad sökning";
$_lang['knowledge database'] = "kunskapsdatabas";
$_lang['Cancel'] = "Ångra";
$_lang['New ticket'] = "Nytt ärende";
$_lang['Ticket status'] ="Ärendet status";

// please adjust this states as you want -> add/remove states in helpdesk.php
$_lang['unconfirmed'] = 'inte bekräftad';
$_lang['new'] = 'ny';
$_lang['assigned'] = 'tilldelad';
$_lang['reopened'] = 'åter öppnad';
$_lang['resolved'] = 'avklarad';
$_lang['verified'] = 'verifierad';

// settings.php
$_lang['The settings have been modified'] = "Inställningarna har modifierats";
$_lang['Skin'] = "Skinn";
$_lang['First module view on startup'] = "Standard modulvy vid uppstart";
$_lang['none'] = "Ingen";
$_lang['Check for mail'] = "Kontollera nya Email";
$_lang['Additional alert box'] = "Extra alert box";
$_lang['Horizontal screen resolution <br>(i.e. 1024, 800)'] = "Horisontell skärm upplösning<br>(i.e. 1024, 800)";
$_lang['Chat Entry'] = "Chat skrivbox";
$_lang['single line'] = "Enkel rad";
$_lang['multi lines'] = "Flerradig";
$_lang['Chat Direction'] = "Chat sortering";
$_lang['Newest messages on top'] = "Nyaste inlägg överst";
$_lang['Newest messages at bottom'] = "Nyaste inlägg nederst";
$_lang['File Downloads'] = "Nedladdningar av filer";

$_lang['Inline'] = "Inline";
$_lang['Lock file'] = "Lås filen";
$_lang['Unlock file'] = "Lås upp filen";
$_lang['New file here'] = "Ny fil skapas här";
$_lang['New directory here'] = "Ny folder skapas här";
$_lang['Position of form'] = "Position för form";
$_lang['On a separate page'] = "På en separat sida";
$_lang['Below the list'] = "Nedanför listan";
$_lang['Treeview mode on module startup'] = "Modulerna visas i träd struktur vid start";
$_lang['Elements per page on module startup'] = "Antal elementen per sida när modulerna startas";
$_lang['General Settings'] = "Allmänna inställningar";
$_lang['First view on module startup'] = "Första vyn vid modulestart";
$_lang['Left frame width [px]'] = "Vänster frame bredd [px]";
$_lang['Timestep Daywiew [min]'] = "Tidssteg i dags vyn [min]";
$_lang['Timestep Weekwiew [min]'] = "Tidssteg i vecko vyn [min]";
$_lang['px per char for event text<br>(not exact in case of proportional font)'] = "px per charaktär för händelse text<br>(inte exact för proportionella fonter)";
$_lang['Text length of events will be cut'] = "Textens längd för händelser kan klippas av";
$_lang['Standard View'] = "Standard vy";
$_lang['Standard View 1'] = "Standard vy 1";
$_lang['Standard View 2'] = "Standard vy 2";
$_lang['Own Schedule'] = "Egen tidsplan";
$_lang['Group Schedule'] = "Gruppens tidsplan";
$_lang['Group - Create Event'] = "Grupp - Skapa händelse";
$_lang['Group, only representation'] = "Grupp, representation";
$_lang['Holiday file'] = "Helgdags file";

// summary
$_lang['Todays Events'] = "Händelser idag";
$_lang['New files'] = "Nya filer";
$_lang['New notes'] = "Nya notiser";
$_lang['New Polls'] = "Nya ömröstningar";
$_lang['Current projects'] = "Aktuella projekt";
$_lang['Help Desk Requests'] = "Helpdesk frågor";
$_lang['Current todos'] = "Aktuella uppgifter";
$_lang['New forum postings'] = "Nya forum inlägg";
$_lang['New Mails'] = "Nya Email";

//timecard

$_lang['Theres an error in your time sheet: '] = "Fel i ditt tidkort! Kontrollera tidkortet.";




$_lang['Consistency check'] = "Rimlighetskontroll";
$_lang['Please enter the end afterwards at the'] = "Fyll i sluttidpunkt";
$_lang['insert'] = "lägg i";
$_lang['Enter records afterwards'] = "Skriv in tiden";
$_lang['Please fill in only emtpy records'] = "Fyll i endast tomma noteringar";

$_lang['Insert a period, all records in this period will be assigned to this project'] = "Fyll i period, samtliga tidsangivelser inom perioden kommer att tilldelas det aktuella projektet";
$_lang['There is no record on this day'] = "Detta datum finns inte";
$_lang['This field is not empty. Please ask the administrator'] = "Fältet är inte tomt, kontakta administratoren";
$_lang['There is no open record with a begin time on this day!'] = "Felaktiga datum! V.g. kontrollera.";
$_lang['Please close the open record on this day first!'] = "Fyll i starttiden först";
$_lang['Please check the given time'] = "Kontrollera tiden";
$_lang['Assigning projects'] = "Tilldelning till flera projekt";
$_lang['Select a day'] = "Välj en dag";
$_lang['Copy to the boss'] = "Kopia till chefen";
$_lang['Change in the timecard'] = "ändra tidkort ";
$_lang['Sum for'] = "Summa för";

$_lang['Unassigned time'] = "Icke tilldelad tid";
$_lang['delete record of this day'] = "Ta bort tilldelningar för denna dag";
$_lang['Bookings'] = "Bokningar";

$_lang['insert additional working time'] = "infoga icke ordinarie arbetstid";
$_lang['Project assignment']= "Projekt tilldelning";
$_lang['Working time stop watch']= "Stopptid för arbetstid";
$_lang['stop watches']= "stopptid";
$_lang['Project stop watch']= "Stopptid för projekt";
$_lang['Overview my working time']= "Överblick av arbetstid";
$_lang['GO']= "GO";
$_lang['Day view']= "Visning per dag";
$_lang['Project view']= "Projekt vy";
$_lang['Weekday']= "Veckodag";
$_lang['Start']= "Start";
$_lang['Net time']= "Nättid";
$_lang['Project bookings']= "Projekt bokningar";
$_lang['save+close']= "spara och stäng";
$_lang['Working times']= "Arbetstid";
$_lang['Working times start']= "Start av arbetsdag";
$_lang['Working times stop']= "Slut på arbetsdag";
$_lang['Project booking start']= "Start för projekt bokning";
$_lang['Project booking stop']= "Stop för projekt bokning";
$_lang['choose day']= "välj dag";
$_lang['choose month']= "välj månad";
$_lang['1 day back']= "i går";
$_lang['1 day forward']= "i morgon";
$_lang['Sum working time']= "Summa arbetstid";
$_lang['Time: h / m']= "Tid: tim / minut";
$_lang['activate project stop watch']= "aktivera projektets stoppur";
$_lang['activate']= "aktivera";
$_lang['project choice']= "valt projekt";
$_lang['stop stop watch']= "stanna stoppuret";
$_lang['still to allocate:']= "lediga för tilldelning:";
$_lang['You are not allowed to delete entries from timecard. Please contact your administrator']= "Du har inte tillåtelse att ta bort entries från tidkortet. Kontakta din administrator";
$_lang['You cannot delete entries at this date. Since there have been %s days. You just can edit entries not older than %s days.']= "Du kan inte ta bort entries, då dom funnits i %s dagar. Du kan bara ändra entries som är yngre än %s dagar.";
$_lang['You cannot delete bookings at this date. Since there have been %s days. You just can edit bookings of entries not older than %s days.']= "Du kan inte radera bokningar jst nu. Då det gått %s dagar. Du kan bara ändra bokningar of entries som är yngre än %s dagar.";
$_lang['You cannot add entries at this date. Since there have been %s days. You just can edit entries not older than %s days.']= "Du kan inte lägga till entries då entries funnits i %s dagar. Du kan bara ändra entries som är yngre än %s dagar.";
$_lang['You cannot add  bookings at this date. Since there have been %s days. You just can add bookings for entries not older than %s days.']= "Du kan inte lägga till bokningar just nu. Då det har gått %s dagar. Du kan bara lägga till bokningar för entries som är yngre än %s dagar.";
$_lang['activate+close']="aktivera och stäng";

// todos
$_lang['accepted'] = "Accepterad";
$_lang['rejected'] = "Avvisad";
$_lang['own'] = "egen";
$_lang['progress'] = "framgång";
$_lang['delegated to'] = "delegerad till";
$_lang['Assigned from'] = "tilldelad från";
$_lang['done'] = "klar";
$_lang['Not yet assigned'] = "Ännu ej tilldelad";
$_lang['Undertake'] = "Utför";
$_lang['New todo'] = "Ny uppgift"; 
$_lang['Notify recipient'] = "Meddela mottagare";

// votum.php
$_lang['results of the vote: '] = "Resultat av omröstningen: ";
$_lang['Poll Question: '] = "Rösta om frågan: ";
$_lang['several answers possible'] = "(flera val är möjliga)";
$_lang['Alternative '] = "Alternativ ";
$_lang['no vote: '] = "Röst saknas: ";
$_lang['of'] = "av";
$_lang['participants have voted in this poll'] = "deltagarna har röstat";
$_lang['Current Open Polls'] = "Aktuella omröstningar";
$_lang['Results of Polls'] = "Visa resultat av alla röster";
$_lang['New survey'] ="Ny undersökning";
$_lang['Alternatives'] ="Alternativ";
$_lang['currently no open polls'] = "Det finns inga omröstningar för tillfället";

// export_page.php
$_lang['export_timecard']       = "Exportera Tidkort";
$_lang['export_timecard_admin'] = "Exportera Tidkort";
$_lang['export_users']          = "Exportera användare i denna grupp";
$_lang['export_contacts']       = "Exportera kontakter";
$_lang['export_projects']       = "Exportera projektdata";
$_lang['export_bookmarks']      = "Exportera bokmärken";
$_lang['export_timeproj']       = "Exportera tid-för-projekt data";
$_lang['export_project_stat']   = "Exportera projekt status";
$_lang['export_todo']           = "Exportera att göra";
$_lang['export_notes']          = "Exportera noteringar";
$_lang['export_calendar']       = "Exportera alla kalenerhändelser";
$_lang['export_calendar_detail']= "Exportera en kalendarhändelse";
$_lang['submit'] = "acceptera";
$_lang['Address'] = "Adress";
$_lang['Next Project'] = "Nästa Projekt";
$_lang['Dependend projects'] = "Beronende mellan projekt";
$_lang['db_type'] = "Database typ";
$_lang['Log in, please'] = "Logga in";
$_lang['Recipient'] = "Mottagare";
$_lang['untreated'] = "inte behandlade";
$_lang['Select participants'] = "Välj deltagare";
$_lang['Participation'] = "Deltagare";
$_lang['not yet decided'] = "ännu inte beslutat";
$_lang['accept'] = "accepterat";
$_lang['reject'] = "avvisa";
$_lang['Substitute for'] = "Ersättare för";
$_lang['Calendar user'] = "Användare av Kalendern";
$_lang['Refresh'] = "Uppdatera";
$_lang['Event'] = "Händelse";
$_lang['Upload file size is too big'] = "Uppladdad fil är för stor size";
$_lang['Upload has been interrupted'] = "Uppladdning av filen har avbrutits";
$_lang['view'] = "vy";
$_lang['found elements'] = "hittat elementen";
$_lang['chosen elements'] = "välj elementen";
$_lang['too many hits'] = "Resultate är för stort för att visas.";
$_lang['please extend filter'] = "Expandera dina filer.";
$_lang['Edit profile'] = "Ändra profilen";
$_lang['add profile'] = "lägg till profil";
$_lang['Add profile'] = "Lägg till profil";
$_lang['Added profile'] = "Nya profil(er).";
$_lang['No profile found'] = "Hittade inga profiler.";
$_lang['add project participants'] = "lägg till projekt deltagare";
$_lang['Added project participants'] = "Laggt till projekt deltagare.";
$_lang['add group of participants'] = "lägg till grupp av deltagare";
$_lang['Added group of participants'] = "Laggt till grupp av deltagare.";
$_lang['add user'] = "lägg till användare ";
$_lang['Added users'] = "Laggt till användare.";
$_lang['Selection'] = "Urval";
$_lang['selector'] = "urval";
$_lang['Send email notification']= "Meddelande med epost";
$_lang['Member selection'] = "Val av deltagare";
$_lang['Collision check'] = "kollisions kontroll";
$_lang['Collision'] = "Kollisioon";
$_lang['Users, who can represent me'] = "Deltagare som kan representera mig";
$_lang['Users, who can see my private events'] = "Deltagare som kan se<br />mina privata händelser";
$_lang['Users, who can read my normal events'] = "Deltagare som kan läsa<br />mina normala händelser";
$_lang['quickadd'] = "Lägga till, förenklat";
$_lang['set filter'] = "Set filter";
$_lang['Select date'] = "Välj datum";
$_lang['Next serial events'] = "Nästa återkommande händelse";
$_lang['All day event'] = "Alla händelser idag";
$_lang['Event is canceled'] = "Händelsen&nbsp;är&nbsp;inställd";
$_lang['Please enter a password!'] = "Skriv in ditt lösenord";
$_lang['You are not allowed to create an event!'] = "Du har inte rättiheter att lägga in en ny händelse!";
$_lang['Event successfully created.'] = "Händelsen har skapats.";
$_lang['You are not allowed to edit this event!'] = "Du har inte rättighet att ändra i denna händelse!";
$_lang['Event successfully updated.'] = "Händelsen har ändrats.";
$_lang['You are not allowed to remove this event!'] = "Du har inte rättigheter att ta bort denna händelse!";
$_lang['Event successfully removed.'] = "Händelsen har tagits bort.";
$_lang['Please give a text!'] = "Skriv in en text!";
$_lang['Please check the event date!'] = "Kontrollera datumet för händelsen!";
$_lang['Please check your time format!'] = "Kontrollera formatet på händelsens tisdangivelse!";
$_lang['Please check start and end time!'] = "Kontrolelra start och sluttiden för händelsen!";
$_lang['Please check the serial event date!'] = "Kontrollera datumet för den återkommande händelsen!";
$_lang['The serial event data has no result!'] = "Innehållet i den återkomamnde händelsen är tomt!";
$_lang['Really delete this event?'] = "Vill du verkligen ta bort denna händelse?";
$_lang['use'] = "Använda";
$_lang[':'] = ":";
$_lang['Mobile Phone'] = "Mobil";
$_lang['submit'] = "utför";
$_lang['Further events'] = "Flera händelser";
$_lang['Remove settings only'] = "Ta bara bort inställningarna";
$_lang['Settings removed.'] = "Inställningarna borttagna.";
$_lang['User selection'] = "Användarens val";
$_lang['Release'] = "Frigör";
$_lang['none'] = "ingen";
$_lang['only read access to selection'] = "bara skrivrättigheter på urvalet";
$_lang['read and write access to selection'] = "läs och skrivrättigheter på urvalet";
$_lang['Available time'] = "Tillgänglig tid";
$_lang['flat view'] = "List Vy";
$_lang['o_dateien'] = "Filhanterare";
$_lang['Location'] = "Placering";
$_lang['date_received'] = "datum_motttaget";
$_lang['subject'] = "Ämne";
$_lang['kat'] = "Kategori";
$_lang['projekt'] = "Projekt";
$_lang['Location'] = "Placering";
$_lang['name'] = "Titel";
$_lang['contact'] = "Kontakt";
$_lang['div1'] = "Framställning";
$_lang['div2'] = "Ändring";
$_lang['kategorie'] = "Kategori";
$_lang['anfang'] = "Start";
$_lang['ende'] = "Slut";
$_lang['status'] = "Status";
$_lang['filename'] = "Filnamn";
$_lang['deadline'] = "Termin";
$_lang['ext'] = "i";
$_lang['priority'] = "Prioritet";
$_lang['project'] = "Projekt";
$_lang['Accept'] = "Acceptera";
$_lang['Please enter your user name here.'] = "Skriv in ditt användarnamn här.";
$_lang['Please enter your password here.'] = "Skriv in ditt lösenord här.";
$_lang['Click here to login.'] = "klicka här för att logga in.";
$_lang['No New Polls'] = "Ingen ny oomröstning";
$_lang['&nbsp;Hide read elements'] = "&nbsp;Dölj lästa element";
$_lang['&nbsp;Show read elements'] = "&nbsp;Visa lästa element";
$_lang['&nbsp;Hide archive elements'] = "&nbsp;Dölj arkiverade element";
$_lang['&nbsp;Show archive elements'] = "&nbsp;Visa arkiverade element";
?>