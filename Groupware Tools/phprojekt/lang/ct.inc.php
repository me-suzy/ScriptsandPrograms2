<?php
// ct.inc.php · Paquet d'idioma Català Estàndard pel PHProjekt 5
// rev. 1.7 - Albert Alcaine-i-Peralta - albert@alcaine.com.es

$chars = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
$name_month = array("", "gener", "febrer", "març", "abril", "maig", "juny", "juliol", "agost", "setembre", "octubre", "novembre", "desembre");
$l_text31a = array("Per defecte", "15 min.", "30 min.", " 1 hora", " 2 hores", " 4 hores", " 1 dia");
$l_text31b = array(0, 15, 30, 60, 120, 240, 1440);
$name_day = array("Diumenge", "Dilluns", "Dimarts", "Dimecres", "Dijous", "Divendres", "Dissabte");
$name_day2 = array("Dil", "Dim", "Dic", "Dij", "Div", "Dis", "Diu");

$_lang['No Entries Found']= "No s'ha trobat cap registre";
$_lang['No Todays Events']= "No hi ha cap esdeveniment per avui";
$_lang['No new forum postings']= "No hi ha cap fil nou al fòrum";
$_lang['in category']= "en la categoria";
$_lang['Filtered']= "Filtrat";
$_lang['Sorted by']= "Ordenat per";
$_lang['go'] = "Envia";
$_lang['back'] = "Torna";
$_lang['print'] = "Imprimir";
$_lang['export'] = "Exporta";
$_lang['| (help)'] = "| (ajuda)";
$_lang['Are you sure?'] = "Esteu segurs de voler continuar?";
$_lang['items/page'] = "items/pàgina";
$_lang['records'] = "registres";
$_lang['previous page'] = "Pàgina anterior";
$_lang['next page'] = "Pàgina següent";
$_lang['first page'] = "Primera pàgina";
$_lang['last page'] = "Última pàgina";
$_lang['Move']  = "Mou";
$_lang['Copy'] = "Copia";
$_lang['Delete'] = "Elimina";
$_lang['Save'] = "Desa";
$_lang['Directory'] = "Directori";
$_lang['Also Delete Contents'] = "Elimina'n el contingut";
$_lang['Sum'] = "Suma";
$_lang['Filter'] = "Filtra";
$_lang['Please fill in the following field'] = "Si us plau, ompliu la casella següent";
$_lang['approve'] = "Aproba";
$_lang['undo'] = "Desfés";
$_lang['Please select!']="Trieu una opció!";
$_lang['New'] = "Nou";
$_lang['Select all'] = "Triar-los tots";
$_lang['Printable view'] = "Visió simple";
$_lang['New record in module '] = "Nou registre en el mòdul ";
$_lang['Notify all group members'] = "Notifica'n tots els membres";
$_lang['Yes'] = "Sí";
$_lang['No'] = "No";
$_lang['Close window'] = "Tanca la finestra";
$_lang['No Value'] = "No especificat";
$_lang['Standard'] = "Estàndard";
$_lang['Create'] = "Crea";
$_lang['Modify'] = "Modifica";   
$_lang['today'] = "avui";

// admin.php
$_lang['Password'] = "Contrassenya";
$_lang['Login'] = "Accés";
$_lang['Administration section'] = "Àrea d'Administració";
$_lang['Your password'] = "La vostra contrassenya";
$_lang['Sorry you are not allowed to enter. '] = "Sentim informar-vos que no hem entès correctament les vostres dades. Si us plau, intenteu-ho de nou.";
$_lang['Help'] = "Ajuda";
$_lang['User management'] = "Administra Usuaris";
$_lang['Create'] = "Crea";
$_lang['Projects'] = "Projectes";
$_lang['Resources'] = "Recursos";
$_lang['Resources management'] = "Resources management";
$_lang['Bookmarks'] = "Preferits";
$_lang['for invalid links'] = "Verifica els enllaços";
$_lang['Check'] = "Verifica";
$_lang['delete Bookmark'] = "Elimina preferits";
$_lang['(multiple select with the Ctrl-key)'] = "(selecció múltiple amb la tecla 'Ctrl')";
$_lang['Forum'] = "Fòrum";
$_lang['Threads older than'] = "Temes amb més de";
$_lang[' days '] = " dies ";
$_lang['Chat'] = "Xat";
$_lang['save script of current Chat'] = "Desa el xat actual";
$_lang['Chat script'] = "Text del xat";
$_lang['New password'] = "Nova contrassenya";
$_lang['(keep old password: leave empty)'] = "(per a mantenir la contrassenya anterior, deixeu la casella en blanc)";
$_lang['Default Group<br> (must be selected below as well)'] = "Grup per defecte<br> (Ha d'estar seleccionat a dalt)";
$_lang['Access rights'] = "Drets d'accés";
$_lang['Zip code'] = "Codi postal";
$_lang['Language'] = "Idioma";
$_lang['schedule readable to others'] = "Horari visible als altres";
$_lang['schedule invisible to others'] = "Horari invisible als altres";
$_lang['schedule visible but not readable'] = "Horari visible però no accesible";
$_lang['these fields have to be filled in.'] = "Aquests espais s'han d'omplir.";
$_lang['You have to fill in the following fields: family name, short name and password.'] = "Cal omplir aquests espais: Cognoms, Inicials i Contrassenya.";
$_lang['This family name already exists! '] = "Aquest cognom ja existeix! ";
$_lang['This short name already exists!'] = "Aquestes inicials ja existeixen!";
$_lang['This login name already exists! Please chosse another one.'] = "Aquest nom d'usuari ja existeix! Si us plau, trieu-ne un altre.";
$_lang['This password already exists!'] = "Aquesta contrassenya ja existeix!";
$_lang['This combination first name/family name already exists.'] = "La combinació Nom/Cognoms ja existeix.";
$_lang['the user is now in the list.'] = "l'usuari s'ha incorporat a la llista.";
$_lang['the data set is now modified.'] = "Dades modificades i actualitzades correctament.";
$_lang['Please choose a user'] = "Si us plau trieu un usuari";
$_lang['is still listed in some projects. Please remove it.'] = "es troba llistat en algun projecte actualment. Esborreu-lo si us plau.";
$_lang['All profiles are deleted'] = "S'han eliminat tots els perfils";
$_lang['A Profile with the same name already exists'] = "A profile with the same name already exists";
$_lang['is taken out of all user profiles'] = "s'ha exclòs de tots els perfils";
$_lang['All todo lists of the user are deleted'] = "S'han esborrat totes les tasques a fer de l'usuari";
$_lang['is taken out of these votes where he/she has not yet participated'] = "està exclòs de les votacions on encara no ha participat";
$_lang['All events are deleted'] = "S'han esborrat tots els esdeveniments.";
$_lang['user file deleted'] = "Fitxers d'usuari esborrats";
$_lang['bank account deleted'] = "Compte bancari esborrat ;-)";
$_lang['finished'] = "Acabat";
$_lang['Please choose a project'] = "Si us plau, trieu un projecte";
$_lang['The project is deleted'] = "Projecte eliminat";
$_lang['All links in events to this project are deleted'] = "Tots els enllaços als esdeveniments d'aquest projecte s'han esborrat";
$_lang['The duration of the project is incorrect.'] = "La durada del projecte no és correcta.";
$_lang['The project is now in the list'] = "El projecte es troba ara en la llista";
$_lang['The project has been modified'] = "Projecte modificat";
$_lang['Please choose a resource'] = "Seleccioneu un recurs";
$_lang['The resource is deleted'] = "Recurs eliminat";
$_lang['All links in events to this resource are deleted'] = "Tots els enllaços als esdeveniments d'aquest recurs s'han esborrat";
$_lang[' The resource is now in the list.'] = " El recurs es troba ara en llista.";
$_lang[' The resource has been modified.'] = " El recurs ha estat modificat.";
$_lang['The server sent an error message.'] = "El servidor ha donat un missatge d'error";
$_lang['All Links are valid.'] = "Tots els enllaços són correctes.";
$_lang['Please select at least one bookmark'] = "Trieu un enllaç com a mínim";
$_lang['The bookmark is deleted'] = "L'enllaç s'ha esborrat amb èxit";
$_lang['threads older than x days are deleted.'] = "S'han esborrat els temes més antics de x dies.";
$_lang['All chat scripts are removed'] = "S'han esborrat tots els textos del xat.";
$_lang['or'] = "o";
$_lang['Timecard management'] = "Administra les Assistències";
$_lang['View'] = "Veure";
$_lang['Choose group'] = "Trieu un grup";
$_lang['Group name'] = "Nom del grup";
$_lang['Short form'] = "Àlies";
$_lang['Category'] = "Categoria";
$_lang['Remark'] = "Observacions";
$_lang['Group management'] = "Administra grups";
$_lang['Please insert a name'] = "Indiqueu un nom";
$_lang['Name or short form already exists'] = "El nom o àlies ja existeix";
$_lang['Automatic assign to group:'] = "Asignació automàtica al grup:";
$_lang['Automatic assign to user:'] = "Asignació automàtica a l'usuari:";
$_lang['Help Desk Category Management'] = "Administra les categories de les Consultes";
$_lang['Category deleted'] = "Categoria eliminada";
$_lang['The category has been created'] = "S'ha creat la categoria";
$_lang['The category has been modified'] = "S'ha modificat la categoria";
$_lang['Member of following groups'] = "Membre dels grups";
$_lang['Primary group is not in group list'] = "El grup per defecte no es troba en la llista de grups";
$_lang['Login name'] = "Nom d'autenticació";
$_lang['You cannot delete the default group'] = "No és possible esborrar el grup per defecte";
$_lang['Delete group and merge contents with group'] = "Esborrar el grup i traspassar-ne el contingut al grup";
$_lang['Please choose an element'] = "Si us plau, trieu un element";
$_lang['Group created'] = "S'ha creat el grup";
$_lang['File management'] = "Administra fitxers";
$_lang['Orphan files'] = "Fitxers orfes";
$_lang['Deletion of super admin root not possible'] = "No és possible esborrar l'arrel del superadministrador";
$_lang['ldap name'] = "nom ldap";
$_lang['mobile // mobile phone'] = "mòbil";
$_lang['Normal user'] = "Usuari normal";
$_lang['User w/Chief Rights'] = "Cap del grup";
$_lang['Administrator'] = "Administrador";
$_lang['Logging'] = "Registre";
$_lang['Logout'] = "Tanca sessió";
$_lang['posting (and all comments) with an ID'] = "Publica (amb tots els comentaris) afegint-hi l'ID";
$_lang['Role deleted, assignment to users for this role removed'] = "Rol esborrat, l'assignació d'aquest rol pels usuaris també s'ha esborrat";
$_lang['The role has been created'] = "S'ha creat el rol";
$_lang['The role has been modified'] = "S'ha modificat el rol";
$_lang['Access rights'] = "Drets d'accés";
$_lang['Usergroup'] = "Grup d'usuaris";
$_lang['logged in as'] = "Identificat com";

//chat.php
$_lang['Quit chat']= "Surt del xat";

//contacts.php
$_lang['Contact Manager'] = "Administra Contactes";
$_lang['New contact'] = "Nou contacte";
$_lang['Group members'] = "Membres del grup";
$_lang['External contacts'] = "Contactes externs";
$_lang['&nbsp;New&nbsp;'] = "&nbsp;Nou&nbsp;";
$_lang['Import'] = "Importa";
$_lang['The new contact has been added'] = "El nou contacte s'ha afegit satisfactòriament";
$_lang['The date of the contact was modified'] = "Les dades del contacte han estat modificades";
$_lang['The contact has been deleted'] = "El contacte ha estat esborrat";
$_lang['Open to all'] = "Accessible a tots";
$_lang['Picture'] = "Imatge";
$_lang['Please select a vcard (*.vcf)'] = "Indiqueu una targeta de visita (*.vcf)";
$_lang['create vcard'] = "Crea una Vcard";
$_lang['import address book'] = "Importa llibreta d'adreces";
$_lang['Please select a file (*.csv)'] = "Trieu un fitxer (*.csv)";
$_lang['Howto: Open your outlook express address book and select file/export/other book<br>Then give the file a name, select all fields in the next dialog and finish'] = "Instruccions: obriu la llibreta d'adreces de l'Outlook i seleccioneu 'archivo'/'exportar'/'libreta de direcciones'<br> Indiqueu com a tipus de fitxer 
'valores separados por coma'<br> Nomeneu el fitxer amb l'extensió .cvs. Seleccioneu tots els camps en el següent diàleg i cliqueu a 'Finalizar'";
$_lang['Open outlook at file/export/export in file,<br>choose comma separated values (Win), then select contacts in the next form,<br>give the export file a name and finish.'] = "Obriu l'Outlook Express i seleccioneu 'archivo'/'exportar'/'libreta de direcciones',<br>
seleccioneu 'valores separados por coma (Win)', després seleccioneu 'contactos',<br>
nomeneu el fitxer i cliqueu a 'Finalizar'.";
$_lang['Please choose an export file (*.csv)'] = "Indiqueu un fitxer a exportar (*.csv)";
$_lang['Please export your address book into a comma separated value file (.csv), and either<br>1) apply an import pattern OR<br>2) modify the columns of the table with a spread sheet to this format<br>(Delete colums in you file that are not listed here and create empty colums for fields that do not exist in your file):'] = "Si us plau, exporteu la vostra llibreta d'adreces en un format de fitxer separat per comes (.csv), i a continuació<br>
1) apliqueu un patró d'importació O BÉ<br>
2) modifiqueu les columnes de la taula al patró que us indiquem<br>
(Esborreu les columnes del fitxer que no es mostrin aquí i creeu columnes noves per aquells camps que no existeixen en l'original):";
$_lang['Please insert at least the family name'] = "És necessari que indiqueu, al menys, un cognom";
$_lang['Record import failed because of wrong field count'] = "Ha fallat la importació perquè no coincideixen els camps";
$_lang['Import to approve'] = "Importa per aprovar-ho";
$_lang['Import list'] = "Importa llista";
$_lang['The list has been imported.'] = "La llista s'ha importat correctament.";
$_lang['The list has been rejected.'] = "Ho sentim, hem hagut de rebutjar la importació.";
$_lang['Profiles'] = "Perfils";
$_lang['Parent object'] = "Objecte relacionat"; 
$_lang['Check for duplicates during import'] = "Comprova'n els duplicats durant la importació";
$_lang['Fields to match'] = "Camps a coincidir";
$_lang['Action for duplicates'] = "Acció pels registres duplicats";
$_lang['Discard duplicates'] = "Descarta'n duplicats";
$_lang['Dispose as child'] = "Mostra'l com a subgrup";
$_lang['Store as profile'] = "Desa'l com un perfil";    
$_lang['Apply import pattern'] = "Aplica el patró d'importació";
$_lang['Import pattern'] = "Importa el patró";
$_lang['For modification or creation<br>upload an example csv file'] = "Trasnfereix el fitxer d'importació (csv)"; 
$_lang['Skip field'] = "Salta't el camp";
$_lang['Field separator'] = "Separador de camps";
$_lang['Contact selector'] = "Sel·lector de contactes";
$_lang['Use doublet'] = "Utilitza doblets";
$_lang['Doublets'] = "Doblets";

// filemanager.php
$_lang['Please select a file'] = "Trieu un fitxer";
$_lang['A file with this name already exists!'] = "Compte: Ja existeix un fitxer amb aquest nom!";
$_lang['Name'] = "Nom";
$_lang['Comment'] = "Comentari";
$_lang['Date'] = "Data";
$_lang['Upload'] = "Desa";
$_lang['Filename and path'] = "Nom del fitxer i ubicació";
$_lang['Delete file'] = "Esborra el fitxer";
$_lang['Overwrite'] = "Substitueix";
$_lang['Access'] = "Accés";
$_lang['Me'] = "Personal";
$_lang['Group'] = "grup";
$_lang['Some'] = "Seleccionats";
$_lang['As parent object'] = "Igual que el directori";
$_lang['All groups'] = "Tots els grups";
$_lang['You are not allowed to overwrite this file since somebody else uploaded it'] = "No podeu substituir un fitxer que no heu desat vosaltres. Demaneu a l'altre usuari que ho faci per vosaltres.";
$_lang['personal'] = "Personal";
$_lang['Link'] = "Enllaç";
$_lang['name and network path'] = "Afegeix ruta al fitxer";
$_lang['with new values'] = "amb nous valors";
$_lang['All files in this directory will be removed! Continue?'] = "Compte: Esborrareu el directori i tot el seu contingut! Segur que és això el que voleu?";
$_lang['This name already exists'] = "Aquest nom ja existeix!";
$_lang['Max. file size'] = "Mida màxima";
$_lang['links to'] = "enllaçat a";
$_lang['objects'] = "objectes";
$_lang['Action in same directory not possible'] = "No és possible aquesta acció en el mateix directori";
$_lang['Upload = replace file'] = "ALERTA: Si continueu, sobreescriureu el fitxer!";
$_lang['Insert password for crypted file'] = "Introduïu contrassenya pel fitxer protegit";
$_lang['Crypt upload file with password'] = "Protegeix el fitxer amb contrassenya:";
$_lang['Repeat'] = "Confirmeu-ho";
$_lang['Passwords dont match!'] = "Les contrassenyes no coincideixen!";
$_lang['Download of the password protected file '] = "Descarrega el fitxer protegit ";
$_lang['notify all users with access'] = "Notifica a tots els usuaris que hi tenen accés";
$_lang['Write access'] = "Drets d'escriptura";
$_lang['Version'] = "Versió";
$_lang['Version management'] = "Versió d'administració";
$_lang['lock'] = "Bloca";
$_lang['unlock'] = "Desbloca";
$_lang['locked by'] = "Blocat per";
$_lang['Alternative Download'] = "Descàrrega alternativa";
$_lang['Download'] = "Descarrega";
$_lang['Select type'] = "Tria el tipus";
$_lang['Create directory'] = "Crea directori";
$_lang['filesize (Byte)'] = "Mida del fitxer (bytes)";

// filter
$_lang['contains'] = 'conté...';
$_lang['exact'] = 'exactament';
$_lang['starts with'] = 'comença per...';
$_lang['ends with'] = 'acaba en...';
$_lang['>'] = '>';
$_lang['>='] = '>=';
$_lang['<'] = '<';
$_lang['<='] = '<=';
$_lang['does not contain'] = 'no conté...';
$_lang['Please set (other) filters - too many hits!'] = "S'han trobat massa coincidències! Utilitza (altres) filtres.";

$_lang['Edit filter'] = "Edita el filtre";
$_lang['Filter configuration'] = "Configura el filtre";
$_lang['Disable set filters'] = "Desactiva els filtres";
$_lang['Load filter'] = "Carrega un filtre";
$_lang['Delete saved filter'] = "Esborra un filtre desat";
$_lang['Save currently set filters'] = "Desa els filtres actuals";
$_lang['Save as'] = "Desa com...";
$_lang['News'] = 'Notícies';

// form designer
$_lang['Module Designer'] = "Mòdul de disseny";
$_lang['Module element'] = "Mòdul d'elements"; 
$_lang['Module'] = "Mòdul";
$_lang['Active'] = "Actiu";
$_lang['Inactive'] = "Innactiu";
$_lang['Activate'] = "Activa'l";
$_lang['Deactivate'] = "Desactiva'l"; 
$_lang['Create new element'] = "Crea un nou element";
$_lang['Modify element'] = "Modifica l'element";
$_lang['Field name in database'] = "Nom del camp a la base de dades";
$_lang['Use only normal characters and numbers, no special characters,spaces etc.'] = "Utilitza només caràcters i números normals: evita'n espais, etc.";
$_lang['Field name in form'] = "Nom del camp en el formulari";
$_lang['(could be modified later)'] = "(podeu modificar-lo posteriorment)"; 
$_lang['Single Text line'] = "Línia de text (només una)";
$_lang['Textarea'] = "Àrea de text";
$_lang['Display'] = "Mostra";
$_lang['First insert'] = "Insereix-lo";
$_lang['Predefined selection'] = "Selecció preferida";
$_lang['Select by db query'] = "Selecciona'l per un camp de la base de dades";
$_lang['File'] = "Fitxer";

$_lang['Email Address'] = "Email";
$_lang['url'] = "Adreça (URL)";
$_lang['Checkbox'] = "Quadre de verificació";
$_lang['Multiple select'] = "Selecció múltiple"; 
$_lang['Display value from db query'] = "Mostra el valor de la consulta a la BD";
$_lang['Time'] = "Hora";
$_lang['Tooltip'] = "Pista"; 
$_lang['Appears as a tipp while moving the mouse over the field: Additional comments to the field or explanation if a regular expression is applied'] = "Apareix com una descripció mentre es mou el ratolí per sobre del camp: podeu emprar aquesta opció per a aclarir o comentar el camp";
$_lang['Position'] = "Posició";
$_lang['is current position, other free positions are:'] = "és la posició actual, altres posicions lliures són:"; 
$_lang['Regular Expression:'] = "Expressió regular:";
$_lang['Please enter a regular expression to check the input on this field'] = "Si us plau, escriviu una expressió regular per a comprovar l'entrada d'aquest camp";
$_lang['Default value'] = "Valor per defecte";
$_lang['Predefined value for creation of a record. Could be used in combination with a hidden field as well'] = "Valor per defecte per a la creació del registre. Pot emprar-se també juntament amb un camp ocult.";
$_lang['Content for select Box'] = "Continguts per a la Caixa de Selecció";
$_lang['Used for fixed amount of values (separate with the pipe: | ) or for the sql statement, see element type'] = "Utilitzat per una quantitat fixa de valors (separats per la barra vertical | ) o per la comanda sql, vegeu el tipus d'element";
$_lang['Position in list view'] = "Posició a la vista de llista";
$_lang['Only insert a number > 0 if you want that this field appears in the list of this module'] = "Insereix només un número > 0 si vols que aquest camp aparegui a la llista d'aquest mòdul";
$_lang['Alternative list view'] = "Visió de llista alternativa";
$_lang['Value appears in the alt tag of the blue button (mouse over) in the list view'] = "El valor apareix en l'etiqueta ALT del botó blau (situeu-hi el ratolí a sobre) en la llista";
$_lang['Filter element'] = "Filtra l'element";
$_lang['Appears in the filter select box in the list view'] = "Apareix en la caixa de selecció del filtre";
$_lang['Element Type'] = "Tipus d'element";
$_lang['Select the type of this form element'] = "Trieu el tipus per aquest camp del formulari";
$_lang['Check the content of the previous field!'] = "Comproveu el contingut del camp anterior!";
$_lang['Span element over'] = "Distribueix els camps";
$_lang['columns'] = "columnes";
$_lang['rows'] = "files"; 
$_lang['Telephone'] = "Telèfon";
$_lang['History'] = "Historial";
$_lang['Field'] = "Camp";
$_lang['Old value'] = "Valor antic";
$_lang['New value'] = "Valor nou";
$_lang['Author'] = "Autor"; 
$_lang['Show Date'] = "Mostra la data";
$_lang['Creation date'] = "Data de creació";
$_lang['Last modification date'] = "Data de modificació";
$_lang['Email (at record cration)'] = "Email";
$_lang['Contact (at record cration)'] = "Contacte"; 
$_lang['Select user'] = "Tria l'usuari";
$_lang['Show user'] = "Mostra l'usuari";

// forum.php
$_lang['Please give your thread a title'] = "Si et plau, faciliteu un títol al fil del fòrum";
$_lang['New Thread'] = "Nou fil";
$_lang['Title'] = "Títol";
$_lang['Text'] = "Text";
$_lang['Post'] = "Publica";
$_lang['From'] = "De";
$_lang['open'] = "obert";
$_lang['closed'] = "tancat";
$_lang['Notify me on comments'] = "Avisa'm quan hagin comentaris";
$_lang['Answer to your posting in the forum'] = "Resposta al teu fil en el fòrum";
$_lang['You got an answer to your posting'] = "Heu tingut una resposta al vostre fil que vau publicar \n ";
$_lang['New posting'] = "Nou tema";
$_lang['Create new forum'] = "Crea un nou fòrum";
$_lang['down'] ='avall';
$_lang['up']= "amunt";
$_lang['Forums']= "Fòrums";
$_lang['Topics']="Temes";
$_lang['Threads']="Fils";
$_lang['Latest Thread']="Últim Fil";
$_lang['Overview forums']= "Visió general dels fòrums";
$_lang['Succeeding answers']= "Respostes";
$_lang['Count']= "Nombre";
$_lang['from']= "de";
$_lang['Path']= "Camí";
$_lang['Thread title']= "Títol del fil";
$_lang['Notification']= "Notificació";
$_lang['Delete forum']= "Esborra fòrum";
$_lang['Delete posting']= "Esborra el missatge";
$_lang['In this table you can find all forums listed']= "En aquesta taula s'hi troben tots els fòrums llistats";
$_lang['In this table you can find all threads listed']= "En aquesta taula s'hi troben tots els fils llistats";

// index.php
$_lang['Last name'] = "Cognoms";
$_lang['Short name'] = "Nom";
$_lang['Sorry you are not allowed to enter.'] = "Ho sentim, però no teniu permís per a entrar.";
$_lang['Please run index.php: '] = "Si us plau, inicieu l'index.php: ";
$_lang['Reminder'] = "Recordatori";
$_lang['Session time over, please login again'] = "Ha caducat la vostra sessió, si us plau, indentifiqueu-vos de nou";
$_lang['&nbsp;Hide read elements'] = "&nbsp;Amaga els elements llegits";
$_lang['&nbsp;Show read elements'] = "&nbsp;Mostra els elements llegits";
$_lang['&nbsp;Hide archive elements'] = "&nbsp;Amaga els elements arxivats";
$_lang['&nbsp;Show archive elements'] = "&nbsp;Mostra els elements arxivats";
$_lang['Tree view'] = "Vista en arbre";
$_lang['flat view'] = "vista plana";
$_lang['New todo'] = "Crea una tasca";
$_lang['New note'] = "Crea una nota";
$_lang['New document'] = "Crea un document";
$_lang['Set bookmark'] = "Afegeix un marcador";
$_lang['Move to archive'] = "Desplaça a l'arxiu";
$_lang['Mark as read'] = "Marca com a llegit";
$_lang['Export as csv file'] = "Exporta a un fitxer CSV";
$_lang['Deselect all'] = "Deselecciona-ho tot";
$_lang['selected elements'] = "elements seleccionats";
$_lang['wider'] = "més ampli";
$_lang['narrower'] = "més proper";
$_lang['ascending'] = "ascendent";
$_lang['descending'] = "descendent";
$_lang['Column'] = "Columa";
$_lang['Sorting'] = "Ordena per";
$_lang['Save width'] = "Desa l'ample";
$_lang['Width'] = "Ample";
$_lang['switch off html editor'] = "desactiva l'editor d'html";
$_lang['switch on html editor'] = "activa l'editor d'html";
$_lang['hits were shown for'] = "coincidències vistes per";
$_lang['there were no hits found.'] = "no s'ha trobat cap coincidència.";
$_lang['Filename'] = "Nom del fitxer";
$_lang['First Name'] = "Nom";
$_lang['Family Name'] = "Cognoms";
$_lang['Company'] = "Empresa";
$_lang['Street'] = "Carrer";
$_lang['City'] = "Ciutat";
$_lang['Country'] = "País";
$_lang['Please select the modules where the keyword will be searched'] = "Si us plau, trieu els mòduls on voleu realitzar la cerca";
$_lang['Enter your keyword(s)'] = "Escriviu la/les paraula/es clau(s)";
$_lang['Salutation'] = "Tracte/Salutació";
$_lang['State'] = "Província";
$_lang['Add to link list'] = "Afegeix a la llista d'enllaços";

// setup.php
$_lang['Welcome to the setup of PHProject!<br>'] = "Benvinguts a la instal·lació del PHProjekt!<br>";
$_lang['Please remark:<ul><li>A blank database must be available<li>Please ensure that the webserver is able to write the file config.inc.php'] = "Tingueu en compte què:<ul>
<li>Cal que hàgiu creat una base de dades en blanc
<li>Assegureu-vos de què el servidor Web permet privilegis d'escriptura en el 'config.inc.php'<br> (p.e. 'chmod 777')";
$_lang['<li>If you encounter any errors during the installation, please look into the <a href=help/faq_install.html target=_blank>install faq</a>or visit the <a href=http://www.PHProjekt.com/forum.html target=_blank>Installation forum</a></i>'] = "<li>Si us trobeu amb errors al llarg de la instal·lació, llegiu el fitxer <a href='help/faq_install.html' target=_blank>Preguntes Freqüents</a>
o visiteu el <a href='http://www.PHProjekt.com/forum.html' target=_blank>Fòrum d'Instal·lació</a></i>. Per a assistència en català contacteu amb Albert Alcaine.";
$_lang['Please fill in the fields below'] = "Ompliu els espais següents";
$_lang['(In few cases the script wont respond.<br>Cancel the script, close the browser and try it again).<br>'] = "(En algun cas, pot blocar-se la navegació.<br>
Cancel·leu el procés, tanqueu el navegador i intenteu-ho de nou).<br>";
$_lang['Type of database'] = "Tipus de la base de dades";
$_lang['Hostname'] = "Servidor";
$_lang['Username'] = "Usuari";

$_lang['Name of the existing database'] = "Nom de la base de dades existent";
$_lang['config.inc.php not found! Do you really want to update? Please read INSTALL ...'] = "config.inc.php no s'ha trobat! Segur que voleu actualitzar? Us recomanem que llegiu el fitxer INSTALL...";
$_lang['config.inc.php found! Maybe you prefer to update PHProject? Please read INSTALL ...'] = "S'ha trobat el config.inc.php ! Segur que no preferiu actualitzar el PHProjekt? Llegiu el fitxer INSTALL per a més informació...";
$_lang['Please choose Installation,Update or Configure!'] = "Trieu la operació a realitzar: 'Instal·lar' o 'Actualizar'! torna...";
$_lang['Sorry, I cannot connect to the database! <br>Please close all browser windows and restart the installation.'] = "Ho sentim, no funciona! <br> Corregiu-ho i torneu-ho a intentar.";
$_lang['Sorry, it does not work! <br> Please set DBDATE to Y4MD- or let phprojekt change this environment-variable (php.ini)!'] = "Ho sentim, no funciona!<br> Establiu DBDATE a 'Y4MD-' o permeteu que el phprojekt modifiqui aquesta variable d'entorn (php.ini)!";
$_lang['Seems that You have a valid database connection!'] = "Felicitats! Teniu una connexió vàlida amb la base de dades!";
$_lang['Please select the modules you are going to use.<br> (You can disable them later in the config.inc.php)<br>'] = "Trieu a continuació els mòduls que utilitzareu.<br> (Després podreu deshabilitar-los en el config.inc.php)<br>";
$_lang['Install component: insert a 1, otherwise keep the field empty'] = "Per a instal·lar un component inseriu un '1', pel contrari, deixeu l'espai en blanc.";
$_lang['Group views'] = "Vistes per grups";
$_lang['Todo lists'] = "Llista de tasques per fer";

$_lang['Voting system'] = "Sistema de votació";


$_lang['Contact manager'] = "Administra contactes";
$_lang['Name of userdefined field'] = "Nom de camp definit per l'usuari";
$_lang['Userdefined'] = "Definit per l'usuari";
$_lang['Profiles for contacts'] = "Perfils pels contactes";
$_lang['Mail'] = "Correu ràpid";
$_lang['send mail'] = " només correu sortint";
$_lang[' only,<br> &nbsp; &nbsp; full mail client'] = " <br> &nbsp; &nbsp; suport complet";



$_lang['1 to show appointment list in separate window,<br>&nbsp; &nbsp; 2 for an additional alert.'] = "'1' per a mostrar la llista d'activitats en una finestra separada,<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'2' per a una alerta adicional.";
$_lang['Alarm'] = "Alarma";
$_lang['max. minutes before the event'] = "minuts com a màxim abans de l'esdeveniment";
$_lang['SMS/Mail reminder'] = "Recordatori per SMS/Mail";
$_lang['Reminds via SMS/Email'] = "Recordatoris via SMS/Email";
$_lang['1= Create projects,<br>&nbsp; &nbsp; 2= assign worktime to projects only with timecard entry<br>&nbsp; &nbsp; 3= assign worktime to projects without timecard entry<br>&nbsp; &nbsp; (Selection 2 or 3 only with module timecard!)'] = "'1'= Crea projectes,<br>
&nbsp; &nbsp; '2'= Assigna franges horàries als projectes només a través del calendari<br>
&nbsp; &nbsp; '3'= Assigna franges horàries sense el calendari<br> ";

$_lang['Name of the directory where the files will be stored<br>( no file management: empty field)'] = "Nom del directori on es desaràn els fitxers<br>(si no voleu aquest mòdul, deixeu l'espai en blanc)";
$_lang['absolute path to this directory (no files = empty field)'] = "Ruta d'accés absoluta a aquest directori (sense fitxers = espai en blanc)";
$_lang['Time card'] = "Assistències";
$_lang['1 time card system,<br>&nbsp; &nbsp; 2 manual insert afterwards sends copy to the chief'] = "'1' control d'assistències,<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '2' Ingrès manual posterior, en què es notifica al cap";
$_lang['Notes'] = "Notes";
$_lang['Password change'] = "Canvi de contrassenya";
$_lang['New passwords by the user - 0: none - 1: only random passwords - 2: choose own'] = "Contrassenyes dels usuaris - 0: cap - 1: al·leatòries - 2: pot triar-se";
$_lang['Encrypt passwords'] = "Encripta contrassenyes";
$_lang['Login via '] = "Entrada via ";
$_lang['Extra page for login via SSL'] = "Pàgina adicional per a entrar via SSL";
$_lang['Groups'] = "Grups";
$_lang['User and module functions are assigned to groups<br>&nbsp; &nbsp; (recommended for user numbers > 40)'] = "Funcions d'usuari i mòduls assignats als grups<br>
&nbsp;&nbsp;&nbsp;&nbsp;(recomanat si teniu més de 40 usuaris)";
$_lang['User and module functions are assigned to groups'] = "Funcions d'usuari i mòduls assignats a grups";
$_lang['Help desk'] = "Seguiment de comandes o tiquets (RT)";
$_lang['Help Desk Manager / Trouble Ticket System'] = "Administració de Taula d'Ajuda / Sistema Tiquets de problemes (RT)";
$_lang['RT Option: Customer can set a due date'] = "Opció RT: El client pot fixar una data límit";
$_lang['RT Option: Customer Authentification'] = "Opció RT: El client cal que s'autentiqui";
$_lang['0: open to all, email-address is sufficient<br>1: registered contact enter his family name<br>2: his email'] = "0: obert a tots, amb l'email és suficient, 1: el client cal que estigui a la llista de contactes i teclejar el cognom";
$_lang['RT Option: Assigning request'] = "Opción RT: Assignació de comandes";
$_lang['0: by everybody, 1: only by persons with status chief'] = "0: tothom, 1: només els caps de grup";
$_lang['Email Address of the support'] = "Adreça d'email de suport";
$_lang['Scramble filenames'] = "Barreja el nom del fitxers";
$_lang['creates scrambled filenames on the server<br>assigns previous name at download'] = "crea noms barrejats de fitxers en el servidor,<br>
assignant-li el nom en el moment de baixar-los";

$_lang['0: last name, 1: short name, 2: login name'] = "0: Cognom, 1: Inicials, 2: Login nom";
$_lang['Prefix for table names in db'] = "Prefix per la taula de noms a la base de dades";
$_lang['Alert: Cannot create file config.inc.php!<br>Installation directory needs rwx access for your server and rx access to all others.'] = "Alerta: No puc crear el fitxer 'config.inc.php'!<br>
El directori d'instal·lació cal que tingui accés rwx pel servidor i accés rx per la resta.";
$_lang['Location of the database'] = "Ubicació de la base de dades";
$_lang['Type of database system'] = "Tipus del sistema de la base de dades";
$_lang['Username for the access'] = "Usuari per l'accés";
$_lang['Password for the access'] = "Clau d'accés";
$_lang['Name of the database'] = "Nom de la base de dades";
$_lang['Prefix for database table names'] = "Prefix per la taula de noms de la base de dades";
$_lang['First background color'] = "Primer color de fons";
$_lang['Second background color'] = "Segon color de fons";
$_lang['Third background color'] = "Tercer color de fons";
$_lang['Color to mark rows'] = "Color per marcar les files";
$_lang['Color to highlight rows'] = "Color per destacar les files";
$_lang['Event color in the tables'] = "Color dels esdeveniments en les taules";
$_lang['company icon yes = insert name of image'] = "Icona de l'entitat, Si = teclegeu el nom de la imatge";
$_lang['URL to the homepage of the company'] = "URL de l'entitat (pàgina web)";
$_lang['no = leave empty'] = "No = deixeu-ho en blanc";
$_lang['First hour of the day:'] = "Primera hora del dia:";
$_lang['Last hour of the day:'] = "Última hora del dia:";
$_lang['An error ocurred while creating table: '] = "Hi ha hagut un error en crear la taula: ";
$_lang['Table dateien (for file-handling) created'] = "Taula 'dateien' (per la manipulació de fitxers) creada";
$_lang['File management no = leave empty'] = "Administra fitxers, No = deixeu-ho en blanc";
$_lang['yes = insert full path'] = "Si = Teclegeu la ruta completa";
$_lang['and the relative path to the PHProjekt directory'] = "i la ruta d'accés relativa a l'arrel";
$_lang['Table profile (for user-profiles) created'] = "Taula 'profile' (perfils d'usuari) creada";
$_lang['User Profiles yes = 1, no = 0'] = "Perfils sí = 1, no = 0";
$_lang['Table todo (for todo-lists) created'] = "Taula 'todo' (tasques per fer) creada";
$_lang['Todo-Lists yes = 1, no = 0'] = "Llista de tasques per a fer, sí = 1, no = 0";
$_lang['Table forum (for discssions etc.) created'] = "Taula 'forum' (dels fòrums) creada";
$_lang['Forum yes = 1, no = 0'] = "Fòrum intern, sí = 1, no = 0";
$_lang['Table votum (for polls) created'] = "Taula 'votum' (votacions) creada";
$_lang['Voting system yes = 1, no = 0'] = "Sistema de votació, sí = 1, no = 0";
$_lang['Table lesezeichen (for bookmarks) created'] = "Taula 'lesezeichen' (preferits) creada";
$_lang['Bookmarks yes = 1, no = 0'] = "Preferides, sí = 1, no = 0";
$_lang['Table ressourcen (for management of additional ressources) created'] = "Taula 'ressourcen' (administració de recursos) creada";
$_lang['Resources yes = 1, no = 0'] = "Recursos, sí = 1, no = 0";
$_lang['Table projekte (for project management) created'] = "Taula 'projekte' (administra projectes) creada";
$_lang['Table contacts (for external contacts) created'] = "Taula 'contacts' (contactes externs) creada";
$_lang['Table notes (for notes) created'] = "Taula 'notes' (notes) creada";
$_lang['Table timecard (for time sheet system) created'] = "Taula 'timecard' (control d'assistència) creada";
$_lang['Table groups (for group management) created'] = "Taula 'groups' (administra grups) creada";
$_lang['Table timeproj (assigning work time to projects) created'] = "Taula 'timeproj' (assignació de temps de treball en els projectes) creada";
$_lang['Table rts and rts_cat (for the help desk) created'] = "Taula 'rts' i 'rts_cat' (seguiment de comandes i tiquets) creada";
$_lang['Table mail_account, mail_attach, mail_client und mail_rules (for the mail reader) created'] = "Taula mail_account, mail_attach, mail_client i mail_rules (lector de correu) creada";
$_lang['Table logs (for user login/-out tracking) created'] = "Taula logs (registre d'entrada i sortida) creada";
$_lang['Tables contacts_profiles und contacts_prof_rel created'] = "Taula contacts_profiles i contacts_prof_rel creada";
$_lang['Project management yes = 1, no = 0'] = "Administració de projectes, sí = 1, no = 0";
$_lang['additionally assign resources to events'] = "a més a més, assignar recursos a esdeveniments";
$_lang['Address book  = 1, nein = 0'] = "Llibreta d'adreces, sí = 1, no = 0";
$_lang['Mail no = 0, only send = 1, send and receive = 2'] = "Correu ràpid, sí = 1, no = 0";
$_lang['Chat yes = 1, no = 0'] = "Xat sí = 1, no = 0";
$_lang['Name format in chat list'] = "Mostra el noms en el xat seguint aquest format";
$_lang['0: last name, 1: first name, 2: first name, last name,<br> &nbsp; &nbsp; 3: last name, first name'] = "0: cognoms, 1: nom, 2: nom, cognoms,<br> &nbsp; &nbsp; 3: cognoms, nom";
$_lang['Timestamp for chat messages'] = "Mostra la cronologia en el xat";
$_lang['users (for authentification and address management)'] = "'user' (autenticació i administració)";
$_lang['Table termine (for events) created'] = "Taula 'termine' (esdeveniments) creada";
$_lang['The following users have been inserted successfully in the table user:<br>root - (superuser with all administrative privileges)<br>test - (chief user with restricted access)'] = "Els usuaris següents s'han introduït a la taula 'user':<br>
'root' - (superusuari)<br>
'test' - (usuari normal amb accés restringit)";
$_lang['The group default has been created'] = "S'ha creat el grup 'default' (per omissió)";
$_lang['Please do not change anything below this line!'] = "Si us plau, no canvieu res per sota d'aquesta línia!";
$_lang['Database error'] = "Error a la base de dades";
$_lang['Finished'] = "Finalitzat";
$_lang['There were errors, please have a look at the messages above'] = "Hi han hagut errors.";
$_lang['All required tables are installed and <br>the configuration file config.inc.php is rewritten<br>It would be a good idea to makea backup of this file.<br>'] = "Les taules necessàries s'han instal·lat i<br>
el fitxer de configuració 'config.inc.php' s'ha actualitzat<br>
Us recomanem que en feu una còpia de seguretat.<br>
Tanqueu totes les finestres del vostre navegador ARA.<br>";
$_lang['The administrator root has the password root. Please change his password here:'] = "L'administrador 'root' té la contrassenya 'root'. CANVIEU-LA:";
$_lang['The user test is now member of the group default.<br>Now you can create new groups and add new users to the group'] = "L'usuari 'test' és membre del grup 'default'.<br>
Ahora puede crear nuevos grupos y agregar nuevos usuarios a los grupos";
$_lang['To use PHProject with your Browser go to <b>index.php</b><br>Please test your configuration, especially the modules Mail and Files.'] = "Per a començar, accediu amb el navegador al fitxer <b>index.php</b><br>
Comproveu el bon funcionament, especialment del correu electrònic i els fitxers.";

$_lang['Alarm x minutes before the event'] = "Alarma de x minuts abans de l'esdeveniment";
$_lang['Additional Alarmbox'] = "Finestra d'alarma adicional";
$_lang['Mail to the chief'] = "Correu el cap";
$_lang['Out/Back counts as: 1: Pause - 0: Workingtime'] = "Fora/Tornada com: 1: Pausa - 0: Temps de treball";
$_lang['Passwords will now be encrypted ...'] = "Les contrassenyes s'encriptaran.";
$_lang['Filenames will now be crypted ...'] = "Els fitxers s'encriptaran ...";
$_lang['Do you want to backup your database right now? (And zip it together with the config.inc.php ...)<br>Of course I will wait!'] = "Voleu fer una còpia de seguretat de la base de dades?<br>
(tot comprimint-lo en un fitxer ZIP juntament amb el config.inc.php ...)<br>
Tranquils que us esperaré ;D!";
$_lang['Next'] = "Següent";
$_lang['Notification on new event in others calendar'] = "Notificar d'un nou esdeveniment en un altre calendari";
$_lang['Path to sendfax'] = "Ruta pel sendfax";
$_lang['no fax option: leave blank'] = "sense opció de fax: deixeu-ho en blanc";
$_lang['Please read the FAQ about the installation with postgres'] = "Llegir la FAQ per a la instal·lació si instal·leu amb postgres";
$_lang['Length of short names<br> (Number of letters: 3-6)'] = "Quantes lletres voleu per les inicials?<br> (3-6)";
$_lang['If you want to install PHProjekt manually, you find<a href=http://www.phprojekt.com/files/sql_dump.tar.gz target=_blank>here</a> a mysql dump and a default config.inc.php'] = "Si voleu instal·lar el PHProjekt manualment, trobareu
<a href='http://www.phprojekt.com/files/sql_dump.tar.gz' target=_blank>aquí</a> l'estructura de la base de dades mysql i un fitxer de configuració estàndard";
$_lang['The server needs the privilege to write to the directories'] = "El servidor necessita privilegis d'escriptura (coneguts com a 'write') en el directori";
$_lang['name, F.'] = "cognom, F.";
$_lang['shortname'] = "àlies";
$_lang['loginname'] = "nom d'usuari (login)";
$_lang['Please create the file directory'] = "Si us plau, creeu el directori dels fitxers";
$_lang['default mode for forum tree: 1 - open, 0 - closed'] = "Tipus de l'arbre en el fòrum: 1 - obert, 0 - tancat";
$_lang['Currency symbol'] = "Símbol de moneda";
$_lang['current'] = "actual";      
$_lang['Default size of form elements'] = "Mida actual pels camps dels formularis";
$_lang['use LDAP'] = "empra LDAP";
$_lang['Allow parallel events'] = "Permetre esdeveniments paral·lels";
$_lang['Timezone difference [h] Server - user'] = "Diferència horària [h] servidor - usuari";
$_lang['Timezone'] = "Zona horària";
$_lang['max. hits displayed in search module'] = "Nombre màxim de coincidències a mostrar en les cerques";
$_lang['Time limit for sessions'] = "Límit de temps per les sessions";
$_lang['0: default mode, 1: Only for debugging mode'] = "0: per defecte, 1: només en el mode de depuració";
$_lang['Enables mail notification on new elements'] = "Activa la notificació per email pels elements nous";
$_lang['Enables versioning for files'] = "Activa el mode de revisions pels fitxers";
$_lang['no link to contacts in other modules'] = "cap enllaç als contactes en altres mòduls";
$_lang['Highlight list records with mouseover'] = "Destaca els registres en les llistes en passar-hi per sobre el ratolí";
$_lang['Track user login/logout'] = "Registra les entrades i sortides dels usuaris";
$_lang['Access for all groups'] = "Accés per a tots els grups";
$_lang['Option to release objects in all groups'] = "Opció per alliberar els objectes en tots els grups";
$_lang['Default access mode: private=0, group=1'] = "Modalitat d'accés per defecte: privat=0, grup=1"; 
$_lang['Adds -f as 5. parameter to mail(), see php manual'] = "Afegeix '-f' com 5. paràmetre a mail(), mireu el manual del php";
$_lang['end of line in body; e.g. \r\n (conform to RFC 2821 / 2822)'] = "final de la línia al cos; e.g. \\r\\n (conform to RFC 2821 / 2822)";
$_lang['end of header line; e.g. \r\n (conform to RFC 2821 / 2822)'] = "final dels encapçalaments; e.g. \\r\\n (conform to RFC 2821 / 2822)";
$_lang['Sendmail mode: 0: use mail(); 1: use socket'] = "Tipus d'enviament de correu: 0: utilitza mail(); 1: utilitza socket";
$_lang['the real address of the SMTP mail server, you have access to (maybe localhost)'] = "l'adreça real del servidor de correu SMTP (potser localhost)";
$_lang['name of the local server to identify it while HELO procedure'] = "nom del servidor local per a identificar-se mitjançant el HELO";
$_lang['Authentication'] = "Autenticació";
$_lang['fill out in case of authentication via POP before SMTP'] = "omple en cas d'autenticació via POP abans que SMTP";
$_lang['real username for POP before SMTP'] = "nom d'usuari real pel servidor POP abans que SMTP";
$_lang['password for this pop account'] = "contrassenya"; 
$_lang['the POP server'] = "servidor POP";
$_lang['fill out in case of SMTP authentication'] = "omple en cas d'autenticació via SMTP";
$_lang['real username for SMTP auth'] = "nom real pel servidor SMTP";
$_lang['password for this account'] = "contrassenya";
$_lang['SMTP account data (only needed in case of socket)'] = "Dades del servidor SMTP";
$_lang['No Authentication'] = "Cap autenticació"; 
$_lang['with POP before SMTP'] = "amb POP abans que SMTP";
$_lang['SMTP auth (via socket only!)'] = "autenticació SMTP (només a través dels sockets!)"; 
$_lang['Log history of records'] = "Historial d'accesos";
$_lang['Send'] = " Envia";
$_lang['Host-Path'] = "Camí del servidor (Host-Patch)";
$_lang['Installation directory'] = "Directori d'instal·lació";
$_lang['0 Date assignment by chief, 1 Invitation System'] = "0 Data assignada pel cap, 1 Sistema d'invitacions";
$_lang['0 Date assignment by chief,<br>&nbsp;&nbsp;&nbsp;&nbsp; 1 Invitation System'] = "0 Data assignada pel cap,<br>&nbsp;&nbsp;&nbsp;&nbsp; 1 Sistema d'invitacions";
$_lang['Default write access mode: private=0, group=1'] = "Mode d'escriptura per defecte:: privat=0, grup=1";
$_lang['Select-Option accepted available = 1, not available = 0'] = "Opcions de selecció disponibles = 1, no disponibles = 0";
$_lang['absolute path to host, e.g. http://myhost/'] = "camí complet al servidor, p.ex. http://elmeuservidor/";
$_lang['installation directory below host, e.g. myInstallation/of/phprojekt5/'] = "directori d'instal·lació dintre del servidor, p.ex. gestordegrups/privat/phprojekt5/";

// l.php
$_lang['Resource List'] = "Llista de recursos";
$_lang['Event List'] = "Llista d'esdeveniments";
$_lang['Calendar Views'] = "Visió de grups";

$_lang['Personnel'] = "Personal";
$_lang['Create new event'] = "Gestiona esdeveniments";
$_lang['Day'] = "Dia";

$_lang['Until'] = "Fins";

$_lang['Note'] = "Nota";
$_lang['Project'] = "Projecte";
$_lang['Res'] = "Recurs";
$_lang['Once'] = "Un cop";
$_lang['Daily'] = "Diàriament";
$_lang['Weekly'] = "Setmanalment";
$_lang['Monthly'] = "Mensualment";
$_lang['Yearly'] = "Anualment";

$_lang['Create'] = "Crea";

$_lang['Begin'] = "Inici";
$_lang['Out of office'] = "Fora del despatx";
$_lang['Back in office'] = "De tornada al despatx";
$_lang['End'] = "Final";
$_lang['@work'] = "@treball";
$_lang['We'] = "Set";
$_lang['group events'] = "esdeveniments de grup";
$_lang['or profile'] = "o perfil";
$_lang['All Day Event'] = "esdeveniment per a cada dia";
$_lang['time-axis:'] = "eix horari:";
$_lang['vertical'] = "vertical";
$_lang['horizontal'] = "horitzontal";
$_lang['Horz. Narrow'] = "hor. marcat";
$_lang['-interval:'] = "-interval:";
$_lang['Self'] = "Propi";

$_lang['...write'] = "...escriu";

$_lang['Calendar dates'] = "Dates de calendari";
$_lang['List'] = "Llista";
$_lang['Year'] = "Any";
$_lang['Month'] = "Mes";
$_lang['Week'] = "Setmana";
$_lang['Substitution'] = "Substitució";
$_lang['Substitution for'] = "Substitució per";
$_lang['Extended&nbsp;selection'] = "Selecció&nbsp;extesa";
$_lang['New Date'] = "S'ha introduït una nova data";
$_lang['Date changed'] = "Data canviada";
$_lang['Date deleted'] = "Data esborrada";

// links
$_lang['Database table'] = "Taula de la base de dades";
$_lang['Record set'] = "Registre assignat";
$_lang['Resubmission at:'] = "Actualitzat el:";
$_lang['Set Links'] = "Enllaços";
$_lang['From date'] = "Des de la data";
$_lang['Call record set'] = "Crida uns registres";


//login.php
$_lang['Please call login.php!'] = "Si us plau autentiqueu-vos via SSL a través de: login.php!";

// m1.php
$_lang['There are other events!<br>the critical appointment is: '] = "Existeixen altres esdeveniments!<br>L'activitat en conflicte és: ";
$_lang['Sorry, this resource is already occupied: '] = "Ho sentim, aquest recurs ja es troba reservat: ";
$_lang[' This event does not exist.<br> <br> Please check the date and time. '] = " Aquest esdeveniment no existeix.<br> <br> Verifiqueu-ne la data i hora. ";
$_lang['Please check your date and time format! '] = "Comproveu el format de la data i l'hora! ";
$_lang['Please check the date!'] = "Comproveu la data!";
$_lang['Please check the start time! '] = "Comproveu l'hora d'inici! ";
$_lang['Please check the end time! '] = "Comproveu l'hora de finalització! ";
$_lang['Please give a text or note!'] = "Escriviu un text o una nota!";
$_lang['Please check start and end time! '] = "Comproveu l'hora d'inici i finalització! ";
$_lang['Please check the format of the end date! '] = "Comproveu el format de la data de finalització! ";
$_lang['Please check the end date! '] = "Comproveu la data de finalització! ";





$_lang['Resource'] = "Recurs";
$_lang['User'] = "Usuari";

$_lang['delete event'] = "Esborrar esdeveniment";
$_lang['Address book'] = "Llibreta d'adreces";


$_lang['Short Form'] = "Inicials";

$_lang['Phone'] = "Telèfon";
$_lang['Fax'] = "Fax";



$_lang['Bookmark'] = "Preferides";
$_lang['Description'] = "Descripció";

$_lang['Entire List'] = "Llista completa";

$_lang['New event'] = "Nou esdeveniment";
$_lang['Created by'] = "Creat per";
$_lang['Red button -> delete a day event'] = "Botó vermell -> esborra un esdeveniment";
$_lang['multiple events'] = "Múltiples esdeveniments";
$_lang['Year view'] = "Visió anual";
$_lang['calendar week'] = "Visió setmanal";

//m2.php
$_lang['Create &amp; Delete Events'] = "Crea &amp; esborra esdeveniments";
$_lang['normal'] = "Normal";
$_lang['private'] = "Privat";
$_lang['public'] = "Públic";
$_lang['Visibility'] = "Privadesa";

//mail module
$_lang['Please select at least one (valid) address.'] = "És necessari que escriviu al menys UNA adreça de correu vàlida.";
$_lang['Your mail has been sent successfully'] = "Operació realitzada - El vostre missatge s'ha enviat correctament.";
$_lang['Attachment'] = "Adjunts...";
$_lang['Send single mails'] = "Envia correus individualment";
$_lang['Does not exist'] = "No existeix";
$_lang['Additional number'] = "Afegeix número";
$_lang['has been canceled'] = "Ha estat cancel·lada";

$_lang['marked objects'] = "els objectes marcats";
$_lang['Additional address'] = "Adreça adicional";
$_lang['in mails'] = "en els mails";
$_lang['Mail account'] = "Compte de Correu";
$_lang['Body'] = "Cos";
$_lang['Sender'] = "Emissor";

$_lang['Receiver'] = "Destinataris";
$_lang['Reply'] = "Contesta";
$_lang['Forward'] = "Reenvia";
$_lang['Access error for mailbox'] = "S'ha produït un error d'accés a la bústia";
$_lang['Receive'] = "[DESCARREGA EL CORREU] ";
$_lang['Write'] = "Envia";
$_lang['Accounts'] = "Bústies";
$_lang['Rules'] = "Regles";
$_lang['host name'] = "Nom del servidor";
$_lang['Type'] = "Tipus";
$_lang['misses'] = "Manca";
$_lang['has been created'] = "S'ha creat";
$_lang['has been changed'] = "S'ha canviat";
$_lang['is in field'] = "És al camp";
$_lang['and leave on server'] = " [Descarrega còpies del servidor]";
$_lang['name of the rule'] = "Nom de la regla";
$_lang['part of the word'] = "Part de la paraula";
$_lang['in'] = "en";
$_lang['sent mails'] = "Emails enviats";
$_lang['Send date'] = "Data d'enviament";
$_lang['Received'] = "Rebut";
$_lang['to'] = "A";
$_lang['imcoming Mails'] = "Emails entrants";
$_lang['sent Mails'] = "Emails enviats";
$_lang['Contact Profile'] = "Contacta amb el Perfil";
$_lang['unread'] = "no llegits";
$_lang['view mail list'] = "Mira llista d'emails";
$_lang['insert db field (only for contacts)'] = "Insereix un camp de la base de dades (només pels contactes)";
$_lang['Signature'] = "Signatura";

$_lang['SMS'] = "SMS";
$_lang['Single account query'] = "Consulta d'un compte en concret";
$_lang['Notice of receipt'] = "Sol·licita acusada de rebuda";
$_lang['Assign to project'] = "Assigna al projecte";
$_lang['Assign to contact'] = "Assigna al contacte";  
$_lang['Assign to contact according to address'] = "Assigna al contacte d'acord amb l'andreça";
$_lang['Include account for default receipt'] = "Inclou el compte per la recepció";
$_lang['Your token has already been used.<br>If it wasnt you, who used the token please contact your administrator.'] = "El vostre tiquet ja ha estat utilitzat.<BR>Si no ha estat el cas, és a dir, si esteu segurs de no haver utilitzat el tiquet, contacteu amb l'Administrador";
$_lang['Your token has already been expired.'] = "El vostre tiquet ha caducat";
$_lang['Unconfirmed Events'] = "Esdeveniments no confirmats";
$_lang['Visibility presetting when creating an event'] = "Visibilitat per defecte en crear un esdeveniment";
$_lang['Subject'] = "Tema";
$_lang['Content'] = "Contingut";
$_lang['answer all'] = "respondre a tots";
$_lang['Create new message'] = "Crea un missatge nou";
$_lang['Attachments'] = "Adjunts";
$_lang['Recipients'] = "Destinataris";
$_lang['file away message'] = "missatge del fitxer fora de línia";
$_lang['Message from:'] = "Missatge d'en/na:";

//notes.php
$_lang['Mail note to'] = "Envia nota a";
$_lang['added'] = "Afegit";
$_lang['changed'] = "Canviat";

// o.php
$_lang['Calendar'] = "Calendari";
$_lang['Contacts'] = "Agenda";


$_lang['Files'] = "Fitxers";



$_lang['Options'] = "Opcions";
$_lang['Timecard'] = "E/S";

$_lang['Helpdesk'] = "Consultes";

$_lang['Info'] = "Info";
$_lang['Todo'] = "Tasques";
$_lang['News'] = "Notícies";
$_lang['Other'] = "Altres";
$_lang['Settings'] = "Paràmetres";
$_lang['Summary'] = "Sumari";

// options.php
$_lang['Description:'] = "Descripció:";
$_lang['Comment:'] = "Comentari:";
$_lang['Insert a valid Internet address! '] = "Escriviu una adreça correcta! ";
$_lang['Please specify a description!'] = "Escriviu-ne una descripció!";
$_lang['This address already exists with a different description'] = "Aquesta adreça ja existeix amb una descripció diferent";
$_lang[' already exists. '] = " ja existeix. ";
$_lang['is taken to the bookmark list.'] = "s'ha inclòs a la llista de preferides.";
$_lang[' is changed.'] = " s'ha canviat.";
$_lang[' is deleted.'] = " s'ha esborrat.";
$_lang['Please specify a description! '] = "Escriviu una adreça! ";
$_lang['Please select at least one name! '] = "Trieu al menys un nom! ";
$_lang[' is created as a profile.<br>'] = " s'ha creat com a Perfil.<br> Un cop s'actualitzi el calendari apareixerà com a Perfil.";
$_lang['is changed.<br>'] = "ha estat canviat.<br> Un cop s'actualizi el Calendari estarà com a Perfil actiu.";
$_lang['The profile has been deleted.'] = "Perfil esborrat.";
$_lang['Please specify the question for the poll! '] = "Escriviu una pregunta per a l'enquesta! ";
$_lang['You should give at least one answer! '] = "Hauríeu de facilitar al menys una resposta! ";
$_lang['Your call for votes is now active. '] = "Operació realitzada - L'enquesta s'ha activat correctament pels usuaris que heu indicat. ";
$_lang['<h2>Bookmarks</h2>In this section you can create, modify or delete bookmarks:'] = "<h2>Preferides</h2>En aquesta secció podeu crear, modificar o afegir adreces web d'interès:";
$_lang['Create'] = "Crea";


$_lang['<h2>Profiles</h2>In this section you can create, modify or delete profiles:'] = "<h2>Perfils</h2>En aquesta secció podeu crear, modificar o eliminar perfils:";
$_lang['<h2>Voting Formula</h2>'] = "<h2>Fòrmula d'enquestes</h2>";
$_lang['In this section you can create a call for votes.'] = "En aquesta secció podeu activar les enquestes.";
$_lang['Question:'] = "Pregunta:";
$_lang['just one <b>Alternative</b> or'] = "només una <b>Alternativa</b> o";
$_lang['several to choose?'] = "vàries opcions?";

$_lang['Participants:'] = "Participants:";

$_lang['<h3>Password Change</h3> In this section you can choose a new random generated password.'] = "<h2>Canvi de la Contrassenya</h2> En aquesta secció podeu triar una nova contrassenya generada al·leatòriament.";
$_lang['Old Password'] = "Contrassenya anterior";
$_lang['Generate a new password'] = "Genera nova contrassenya";
$_lang['Save password'] = "Desa contrassenya";
$_lang['Your new password has been stored'] = "La nova contrassenya s'ha desat a la vostra configuració correctament.";
$_lang['Wrong password'] = "Contrassenya incorrecta";
$_lang['Delete poll'] = "Esborra vot de l'enquesta";
$_lang['<h4>Delete forum threads</h4> Here you can delete your own threads<br>Only threads without a comment will appear.'] = "<h2>Esborrar temes del Fòrum</h2> Aquí podeu esborrar els temes que heu proposat<br>
Només apareixeran temes sense comentaris.";

$_lang['Old password'] = "Contrassenya anterior";
$_lang['New Password'] = "Nova contrassenya";
$_lang['Retype new password'] = "Repetiu la nova contrassenya";
$_lang['The new password must have 5 letters at least'] = "La nova contrassenya ha de tenir 5 caràcters com a mínim";
$_lang['You didnt repeat the new password correctly'] = "Les contrassenyes no coincideixen!";

$_lang['Show bookings'] = "Veure reserves";
$_lang['Valid characters'] = "Caràcters que es permeten";
$_lang['Suggestion'] = "Suggereix";
$_lang['Put the word AND between several phrases'] = "Escriviu la paraula AND (tal com està) al mig de vàries frases"; // translators: please leave the word AND as it is
$_lang['Write access for calendar'] = "Accés d'escriptura pel calendari";
$_lang['Write access for other users to your calendar'] = "Accés d'escriptura al teu calendari pels altres usuaris";
$_lang['User with chief status still have write access'] = "El cap de grup tindrà dret d'escriptura";

// projects
$_lang['Project Listing'] = "Llista de projectes";
$_lang['Project Name'] = "Nom del Projecte";


$_lang['o_files'] = "Fitxers";
$_lang['o_notes'] = "Notes";
$_lang['o_projects'] = "Projectes";
$_lang['o_todo'] = "Tasques";
$_lang['Copyright']="Copyright";
$_lang['Links'] = "Enllaços";
$_lang['New profile'] = "Crea un perfil";
$_lang['In this section you can choose a new random generated password.'] = "En aquesta secció podeu triar una contrassenya generada al·leatòriament.";
$_lang['timescale'] = "escala de temps";
$_lang['Manual Scaling'] = "Escala manual";
$_lang['column view'] = "visió en columnes";
$_lang['display format'] = "format de mostra";
$_lang['for chart only'] = "només pels gràfics:";
$_lang['scaling:'] = "escala:";
$_lang['colours:'] = "colors";
$_lang['display project colours'] = "mostra els colors del projecte";
$_lang['weekly'] = "setmanalment";
$_lang['monthly'] = "mensualment";
$_lang['annually'] = "anualment";
$_lang['automatic'] = "automàtic";
$_lang['New project'] = "Crea un projecte";
$_lang['Basis data'] = "Dades de base";
$_lang['Categorization'] = "Categorització";
$_lang['Real End'] = "Finalització Real";
$_lang['Participants'] = "Participants";
$_lang['Priority'] = "Prioritat";
$_lang['Status'] = "Estat";
$_lang['Last status change'] = "Darrer canvi en l'estat";
$_lang['Leader'] = "Responsable";
$_lang['Statistics'] = "Estadístiques";
$_lang['My Statistic'] = "Les meves estadístiques";

$_lang['Person'] = "Persona";
$_lang['Hours'] = "Hores";
$_lang['Project summary'] = "Resum del Projecte";
$_lang[' Choose a combination Project/Person'] = "Trieu una combinació Projecte/Persona";
$_lang['(multiple select with the Ctrl/Cmd-key)'] = "(selecció múltiple amb la tecla 'Ctrl')";

$_lang['Persons'] = "Persones";
$_lang['Begin:'] = "Inici:";
$_lang['End:'] = "Final:";
$_lang['All'] = "Tots";
$_lang['Work time booked on'] = "Temps de treball assignat a";
$_lang['Sub-Project of'] = "Sots-projecte de";
$_lang['Aim'] = "Aim";
$_lang['Contact'] = "Contacte";
$_lang['Hourly rate'] = "Despesa per hora";
$_lang['Calculated budget'] = "Pressupost estimat";
$_lang['New Sub-Project'] = "Nou sots-projecte";
$_lang['Booked To Date'] = "Reservat fins ara";
$_lang['Budget'] = "Pressupost";
$_lang['Detailed list'] = "Llista detallada";
$_lang['Gantt'] = "Franja horària";
$_lang['offered'] = "Ofert";
$_lang['ordered'] = "Ordenat";
$_lang['Working'] = "Treballant-hi";
$_lang['ended'] = "Acabat";
$_lang['stopped'] = "Aturat";
$_lang['Re-Opened'] = "Reobert";
$_lang['waiting'] = "En espera";
$_lang['Only main projects'] = "Només projectes principals";
$_lang['Only this project'] = "Només aquest projecte";
$_lang['Begin > End'] = "Inici > Fi";
$_lang['ISO-Format: yyyy-mm-dd'] = "Formatatge ISO: aaaa-mm-dd";
$_lang['The timespan of this project must be within the timespan of the parent project. Please adjust'] = "La franja horària d'aquest projecte ha d'estar dintre de la banda horària del projecte 'pare'. Si us plau, ajusteu";
$_lang['Please choose at least one person'] = "Si us plau, trieu al menys una persona";
$_lang['Please choose at least one project'] = "Si us plau, trieu al menys un projecte";
$_lang['Dependency'] = "Dependències";
$_lang['Previous'] = "Anterior";

$_lang['cannot start before the end of project'] = "no pot començar abans de que acabi el projecte";
$_lang['cannot start before the start of project'] = "no pot començar abans que comenci el projecte";
$_lang['cannot end before the start of project'] = "no pot acabar abans que comenci el projecte";
$_lang['cannot end before the end of project'] = "no pot acabar abans que acabi el projecte";
$_lang['Warning, violation of dependency'] = "Atenció, es violen les dependències";
$_lang['Container'] = "Contenidor";
$_lang['External project'] = "Projecte extern";
$_lang['Automatic scaling'] = "Escala automàtica";
$_lang['Legend'] = "Llegenda";
$_lang['No value'] = "Sense valors";
$_lang['Copy project branch'] = "Copia la branca del projecte";
$_lang['Copy this element<br> (and all elements below)'] = "Copia aquest element<br> (i tots els subelements que té a continuació)";
$_lang['And put it below this element'] = "I posa'l a sota d'aquest element";
$_lang['Edit timeframe of a project branch'] = "Edita la línia de temps per la branca del projecte"; 

$_lang['of this element<br> (and all elements below)'] = "d'aquest element<br> (i els subelements)";  
$_lang['by'] = "per";
$_lang['Probability'] = "Probabilitat";
$_lang['Please delete all subelements first'] = "Si us plau, esborreu primer tots els subprojectes";
$_lang['Assignment'] ="Assignació";
$_lang['display'] = "Mostra";
$_lang['Normal'] = "Normal";
$_lang['sort by date'] = "Ordena per data";
$_lang['sort by'] = "Ordena per";
$_lang['Calculated budget has a wrong format'] = "El càlcul d'honoraris té un format erroni";
$_lang['Hourly rate has a wrong format'] = "El preu per hora té un format erroni";

// r.php
$_lang['please check the status!'] = "verifiqueu l'estat!";
$_lang['Todo List: '] = "Per a fer: ";
$_lang['New Remark: '] = "Nova observació: ";
$_lang['Delete Remark '] = "Esborrar observació ";
$_lang['Keyword Search'] = "Cerca: ";
$_lang['Events'] = "en els esdeveniments";
$_lang['the forum'] = "en el Fòrum";
$_lang['the files'] = "en els fitxers";
$_lang['Addresses'] = "Adreces";
$_lang['Extended'] = "Més opcions";
$_lang['all modules'] = "en tots els mòduls";
$_lang['Bookmarks:'] = "Preferits:";
$_lang['List'] = "Llista";
$_lang['Projects:'] = "Projectes:";

$_lang['Deadline'] = "Termini";

$_lang['Polls:'] = "Vots:";

$_lang['Poll created on the '] = "Enquesta creada el ";


// reminder.php
$_lang['Starts in'] = "començarà d'aquí a";
$_lang['minutes'] = "minuts";
$_lang['No events yet today'] = "De moment, no teniu cap esdeveniment fixat per avui";
$_lang['New mail arrived'] = "Teniu missatges pendents de llegir a la vostra bústia";

//ress.php

$_lang['List of Resources'] =  "Llista de Recursos";
$_lang['Name of Resource'] = "Nom dels Recursos";
$_lang['Comments'] =  "Comentaris";


// roles
$_lang['Roles'] = "Rols";
$_lang['No access'] = "Sense accés";
$_lang['Read access'] = "Accés de lectura";

$_lang['Role'] = "Rol";

// helpdesk - rts
$_lang['Request'] = "Tema";

$_lang['pending requests'] = "Consultes pendents";
$_lang['show queue'] = "Veure cua de consultes";
$_lang['Search the knowledge database'] = "Cercar en l'arxiu de consultes obertes";
$_lang['Keyword'] = "Paraula clau";
$_lang['show results'] = "Veure resultats";
$_lang['request form'] = "Formulari de consulta";
$_lang['Enter your keyword'] = "Especifiqueu la vostra paraula clau";
$_lang['Enter your email'] = "Escriviu el vostre e-mail";
$_lang['Give your request a name'] = "Asigneu un nom a la vostra consulta";
$_lang['Describe your request'] = "Descripció de la consulta";

$_lang['Due date'] = "Data límit";
$_lang['Days'] = "Dies";
$_lang['Sorry, you are not in the list'] = "Ho sentim, però s'ha negat l'operació. Si us plau, contacteu l'administrador";
$_lang['Your request Nr. is'] = "Preneu nota del número de la consulta:";
$_lang['Customer'] = "Client";


$_lang['Search'] = "Cercar";
$_lang['at'] = "a";
$_lang['all fields'] = "Tots els espais i camps";


$_lang['Solution'] = "Resposta";
$_lang['AND'] = "I";

$_lang['pending'] = "Pendent";
$_lang['stalled'] = "Congelat";
$_lang['moved'] = "Desplaçat";
$_lang['solved'] = "Solucionat";
$_lang['Submit'] = "Envia";
$_lang['Ass.'] = "Asign.";
$_lang['Pri.'] = "Imp.";
$_lang['access'] = "Accés";
$_lang['Assigned'] = "Assignat";

$_lang['update'] = "Actualitza";
$_lang['remark'] = "Comenta";
$_lang['solve'] = "Soluciona";
$_lang['stall'] = "Congela";
$_lang['cancel'] = "Anul·la";
$_lang['Move to request'] = "Moure a consulta";
$_lang['Dear customer, please refer to the number given above by contacting us.Will will perform your request as soon as possible.'] = "Moltes gràcies per haver realitzat la vostra consulta. Si us plau, recordeu el número que us hem assignat i que us indiquem en el tema d'aquest missatge. 
Aquesta referència cal indicar-la sempre que us poseu en contacte amb nosaltres. Us contestarem el més aviat possible. Per a qualsevol dubte, contesteu aquest missatge.";
$_lang['Your request has been added into the request queue.<br>You will receive a confirmation email in some moments.'] = "La vostra consulta s'ha afegit a la cua de consultes.<br>
Tot seguit us n'enviarem la confirmació per email.";
$_lang['n/a'] = "n/d";
$_lang['internal'] = "Interna";

$_lang['has reassigned the following request'] = "Ha reassignat la consulta:";
$_lang['New request'] = "Nova consulta";
$_lang['Assign work time'] = "Assigna temps de treball a:";
$_lang['Assigned to:'] = "Assignat a:";

$_lang['Your solution was mailed to the customer and taken into the database.'] = "La vostra solució s'ha enviat a l'usuari i registrat a la base de dades.";
$_lang['Answer to your request Nr.'] = "Resposta a la vostra consulta número";
$_lang['Fetch new request by mail'] = "Recolliu la consulta a la vostra bústia de correu electrònic.";
$_lang['Your request was solved by'] = " La vostra consulta ha estat atesa per";

$_lang['Your solution was mailed to the customer and taken into the database'] = "La vostra resposta s'ha enviat a l'usuari i registrat a la base de dades.";
$_lang['Search term'] = "Terme de cerca";
$_lang['Search area'] = "Àrea de cerca";
$_lang['Extended search'] = "Cerca extesa";
$_lang['knowledge database'] = "base de dades del coneixement";
$_lang['Cancel'] = "Cancel·la";
$_lang['New ticket'] = "Nou tiquet";
$_lang['Ticket status'] ="Estat del tiquet";

// please adjust this states as you want -> add/remove states in helpdesk.php
$_lang['unconfirmed'] = 'no confirmat';
$_lang['new'] = 'nou';
$_lang['assigned'] = 'assignat';
$_lang['reopened'] = 'reobert';
$_lang['resolved'] = 'resolt';
$_lang['verified'] = 'verificat';

// settings.php
$_lang['The settings have been modified'] = "Les vostres preferències s'han desat correctament.";
$_lang['Skin'] = "Aparença";
$_lang['First module view on startup'] = "Mòdul a veure en iniciar l'aplicació";
$_lang['none'] = "cap";
$_lang['Check for mail'] = "Comprova el correu";
$_lang['Additional alert box'] = "Missatge addicional";
$_lang['Horizontal screen resolution <br>(i.e. 1024, 800)'] = "Resolució de pantalla horitzontal <br>(per exemple 1024, 800)";
$_lang['Chat Entry'] = "Entrada del xat";
$_lang['single line'] = "una sola línia";
$_lang['multi lines'] = "vàries línies";
$_lang['Chat Direction'] = "Direcció del Xat";
$_lang['Newest messages on top'] = "Missatges més nous a dalt";
$_lang['Newest messages at bottom'] = "Missatges més nous a baix";
$_lang['File Downloads'] = "Descàrrega de fitxers";

$_lang['Inline'] = "En línia";
$_lang['Lock file'] = "Bloca el fitxer";
$_lang['Unlock file'] = "Desbloca el fitxer";
$_lang['New file here'] = "Crea aquí un fitxer";
$_lang['New directory here'] = "Crea aquí un directori";
$_lang['Position of form'] = "Posició del formulari";
$_lang['On a separate page'] = "En una pàgina a part";
$_lang['Below the list'] = "A sota de la llista";
$_lang['Treeview mode on module startup'] = "Vista en arbre en el mòdul d'inici";
$_lang['Elements per page on module startup'] = "Elements per pàgina en el mòdul d'inici";
$_lang['General Settings'] = "Preferències Generals";
$_lang['First view on module startup'] = "Mostra primer en el mòdul d'inici";
$_lang['Left frame width [px]'] = "Ample del marc de l'esquerra [px]";
$_lang['Timestep Daywiew [min]'] = "Interval horari en la visió diària [min]";
$_lang['Timestep Weekwiew [min]'] = "Interval horari en la visió setmanal [min]";
$_lang['px per char for event text<br>(not exact in case of proportional font)'] = "px per caràcter en el text dels esdeveniments<br>(no és exacte en el cas de lletres proporcionals)";
$_lang['Text length of events will be cut'] = "La longitud del text dels esdeveniments es tallarà";
$_lang['Standard View'] = "Visió Estàndard";
$_lang['Standard View 1'] = "Visió Estàndard 1";
$_lang['Standard View 2'] = "Visió Estàndard 2";
$_lang['Own Schedule'] = "Planificació Pròpia";
$_lang['Group Schedule'] = "Planificació del Grup";
$_lang['Group - Create Event'] = "Grup - Crea un Esdeveniment";
$_lang['Group, only representation'] = "Grup, només representació";
$_lang['Holiday file'] = "Fitxer de Vacances";

// summary
$_lang['Todays Events'] = "Esdeveniments d'avui";
$_lang['New files'] = "Fitxers nous";
$_lang['New notes'] = "Notes noves";
$_lang['New Polls'] = "Votacions noves";
$_lang['Current projects'] = "Projectes vigents";
$_lang['Help Desk Requests'] = "Consultes pendents";
$_lang['Current todos'] = "Tasques per fer";
$_lang['New forum postings'] = "Entrades noves al fòrum";
$_lang['New Mails'] = "Missatges nous";

//timecard

$_lang['Theres an error in your time sheet: '] = "Existeix un error a la vostra plantilla d'assistències! Si us plau comproveu les dades o consulteu l'administrador.";




$_lang['Consistency check'] = "Verificació de consistència";
$_lang['Please enter the end afterwards at the'] = "Si us plau especifiqueu la sortida després de";
$_lang['insert'] = "Afegeix";
$_lang['Enter records afterwards'] = "Afegiu els registres després";
$_lang['Please fill in only emtpy records'] = "Si us plau, ompliu només els registres buits";

$_lang['Insert a period, all records in this period will be assigned to this project'] = "Especifiqueu un període, tots els registres en aquest període seran assignats a aquest projecte";
$_lang['There is no record on this day'] = "No hi ha cap registre per a aquest dia";
$_lang['This field is not empty. Please ask the administrator'] = "Aquest espai no es troba buit. Consulteu a l'administrador";
$_lang['There is no open record with a begin time on this day!'] = "Les dates especificades son errònies! Si us plau, verifiqueu-ho.";
$_lang['Please close the open record on this day first!'] = "Si us plau especifiqueu primer l'hora d'entrada";
$_lang['Please check the given time'] = "Si us plau verifiqueu l'hora especificada";
$_lang['Assigning projects'] = "Asignació de projectes";
$_lang['Select a day'] = "Trieu un dia";
$_lang['Copy to the boss'] = "Còpia al director";
$_lang['Change in the timecard'] = "Canvi en la tarja d'assistència";
$_lang['Sum for'] = "Suma corresponent a:";

$_lang['Unassigned time'] = "Tema no assignat";
$_lang['delete record of this day'] = "Esborra registre d'aquest dia";
$_lang['Bookings'] = "Reserves";

$_lang['insert additional working time'] = "insereix una hora de treball addicional";
$_lang['Project assignment']= "Assignació de projectes";
$_lang['Working time stop watch']= "Aturada del cronòmetre del treball";
$_lang['stop watches']= "atura els cronòmetres";
$_lang['Project stop watch']= "Atura el cronòmetre del projecte";
$_lang['Overview my working time']= "Visió general del temps treballat";
$_lang['GO']= "SOM-HI";
$_lang['Day view']= "Visió del dia";
$_lang['Project view']= "Visió del projecte";
$_lang['Weekday']= "Dia de la setmana";
$_lang['Start']= "Comença";
$_lang['Net time']= "Temps net";
$_lang['Project bookings']= "Reserves del projecte";
$_lang['save+close']= "desa i tanca";
$_lang['Working times']= "Temps treballats";
$_lang['Working times start']= "Inicia del temps treballat";
$_lang['Working times stop']= "Atura el temps treballat";
$_lang['Project booking start']= "Inicia la reserva del projecte";
$_lang['Project booking stop']= "Atura la reserva del projecte";
$_lang['choose day']= "tria el dia";
$_lang['choose month']= "tria el mes";
$_lang['1 day back']= "1 dia enrera";
$_lang['1 day forward']= "1 dia endavant";
$_lang['Sum working time']= "Suma el temps treballat";
$_lang['Time: h / m']= "Durada: h / m";
$_lang['activate project stop watch']= "activa el cronòmetre del projecte";
$_lang['activate']= "activa";
$_lang['project choice']= "tria el projecte";
$_lang['stop stop watch']= "atura el cronòmetre";
$_lang['still to allocate:']= "manquen per ubicar:";
$_lang['You are not allowed to delete entries from timecard. Please contact your administrator']= "No teniu permís per esborrar registres d'aquesta taula horària. Si us plau, contacteu amb l'Administrador";
$_lang['You cannot delete entries at this date. Since there have been %s days. You just can edit entries not older than %s days.']= "No podeu esborrar registres en aquesta data. Donat que han passat %s days, només podeu editar registres que no siguin més antics de %s dies.";
$_lang['You cannot delete bookings at this date. Since there have been %s days. You just can edit bookings of entries not older than %s days.']= "No podeu esborrar aquestes reserves en aquesta data. Donat que han passat %s dies, només podeu editar reserves que no siguin més antigues de %s dies.";
$_lang['You cannot add entries at this date. Since there have been %s days. You just can edit entries not older than %s days.']= "No podeu afegir registres en aquesta data. Donat que han passat %s dies, només podeu editar registres que no siguin més antics de %s dies.";
$_lang['You cannot add  bookings at this date. Since there have been %s days. You just can add bookings for entries not older than %s days.']= "No podeu afegir reserves en aquesta data. Donat que han passat %s dies, només podeu afegir reserves per registres que no siguin més antics de %s dies.";
$_lang['activate+close']="activa i tanca";

// todos
$_lang['accepted'] = "acceptat";
$_lang['rejected'] = "refusat";
$_lang['own'] = "propi";
$_lang['progress'] = "progrès";
$_lang['delegated to'] = "delegat a";
$_lang['Assigned from'] = "assignat per";
$_lang['done'] = "fet";
$_lang['Not yet assigned'] = "manca assignar-lo";
$_lang['Undertake'] = "Pren-lo";
$_lang['New todo'] = "Nova tasca a fer"; 
$_lang['Notify recipient'] = "Avisa'n el receptor";

// votum.php
$_lang['results of the vote: '] = "Resultat de l'enquesta: ";
$_lang['Poll Question: '] = "Pregunta de l'enquesta: ";
$_lang['several answers possible'] = "Són possibles vàries respostes";
$_lang['Alternative '] = "Alternativa ";
$_lang['no vote: '] = "Sense vot: ";
$_lang['of'] = "de";
$_lang['participants have voted in this poll'] = "participants han votat";
$_lang['Current Open Polls'] = "Vots oberts actualment";
$_lang['Results of Polls'] = "Llista de resultats de tots els vots";
$_lang['New survey'] ="Nova enquesta";
$_lang['Alternatives'] ="Alternatives";
$_lang['currently no open polls'] = "En aquest moment no hi ha enquestes obertes per a votar";

// export_page.php
$_lang['export_timecard']       = "Exporta el plànning horari";
$_lang['export_timecard_admin'] = "Exporta plànning horari";
$_lang['export_users']          = "Exporta usuaris d'aquest grup";
$_lang['export_contacts']       = "Exporta contactes";
$_lang['export_projects']       = "Exporta dades del projecte";
$_lang['export_bookmarks']      = "Exporta marcadors";
$_lang['export_timeproj']       = "Exporta dades de l'organització horària del projecte";
$_lang['export_project_stat']   = "Exporta estadístiques del projecte";
$_lang['export_todo']           = "Exporta tasques";
$_lang['export_notes']          = "Exporta notes";
$_lang['export_calendar']       = "Exporta tots els esdeveniments del calendari";
$_lang['export_calendar_detail']= "Exporta un esdeveniment del calendari";
$_lang['submit'] = "processa";
$_lang['Address'] = "Adreça";
$_lang['Next Project'] = "Projecte següent";
$_lang['Dependend projects'] = "Projectes dependents projects";
$_lang['db_type'] = "Tipus de base de dades";
$_lang['Log in, please'] = "Identifiqueu-vos, si us plau";
$_lang['Recipient'] = "Destinatari";
$_lang['untreated'] = "untreated";
$_lang['Select participants'] = "Trieu els participants";
$_lang['Participation'] = "Participació";
$_lang['not yet decided'] = "encara no s'ha decidit";
$_lang['accept'] = "accepta";
$_lang['reject'] = "rebutja";
$_lang['Substitute for'] = "Substitut per ";
$_lang['Calendar user'] = "Usuari del Calendari";
$_lang['Refresh'] = "Actualitza";
$_lang['Event'] = "Esdeveniment";
$_lang['Upload file size is too big'] = "La mida del fitxer a transferir és massa gran";
$_lang['Upload has been interrupted'] = "La transferència s'ha aturat";
$_lang['view'] = "veure";
$_lang['found elements'] = "elements trobats";
$_lang['chosen elements'] = "elements triats";
$_lang['too many hits'] = "El resultat és més gran del que és possible mostrar.";
$_lang['please extend filter'] = "Si us plau, afegiu filtres.";
$_lang['Edit profile'] = "Ediat perfil";
$_lang['add profile'] = "afegeix perfil";
$_lang['Add profile'] = "Afegeix perfil";
$_lang['Added profile'] = "S'ha(n) afegit el(s) perfil(s).";
$_lang['No profile found'] = "No s'ha trobat cap perfil.";
$_lang['add project participants'] = "afegeix participants al projecte";
$_lang['Added project participants'] = "S'han afegit participants al projecte.";
$_lang['add group of participants'] = "afegeix un grup als participants";
$_lang['Added group of participants'] = "S'ha afegit un grup als participants.";
$_lang['add user'] = "afegeix usuari";
$_lang['Added users'] = "S'ha(n) afegit el(s) usuari(s).";
$_lang['Selection'] = "Selecció";
$_lang['selector'] = "selector";
$_lang['Send email notification']= "Notificació&nbsp;per&nbsp;email";
$_lang['Member selection'] = "Selecció&nbsp;d'usuari";
$_lang['Collision check'] = "Comprova col·lisions";
$_lang['Collision'] = "Col·lisions";
$_lang['Users, who can represent me'] = "Usuaris, que poden representar-me";
$_lang['Users, who can see my private events'] = "Usuaris, que poden veure<br />els meus esdeveniments privats";
$_lang['Users, who can read my normal events'] = "Usuaris, que poden llegir<br />els meus esdeveniments normals";
$_lang['quickadd'] = "Addició ràpida";
$_lang['set filter'] = "Estableix un filtre";
$_lang['Select date'] = "Tria una data";
$_lang['Next serial events'] = "Pròxims esdeveniments seriats";
$_lang['All day event'] = "Esdeveniment de tot el dia";
$_lang['Event is canceled'] = "L'esdeveniment&nbsp;s'ha&nbsp;cancel·lat";
$_lang['Please enter a password!'] = "Si us plau, introduïu la contrassenya!";
$_lang['You are not allowed to create an event!'] = "No teniu permís per a crear un esdeveniment!";
$_lang['Event successfully created.'] = "L'esdeveniment s'ha creat amb èxit.";
$_lang['You are not allowed to edit this event!'] = "No teniu permís per editar aquest esdeveniment!";
$_lang['Event successfully updated.'] = "L'esdeveniment s'ha actualitzat amb èxit.";
$_lang['You are not allowed to remove this event!'] = "No teniu permís per esborrar aquest esdeveniment!";
$_lang['Event successfully removed.'] = "L'esdeveniment s'ha esborrat amb èxit.";
$_lang['Please give a text!'] = "Si us plau, escriviu un text!";
$_lang['Please check the event date!'] = "Comproveu la data de l'esdeveniment!";
$_lang['Please check your time format!'] = "Comproveu el format amb que heu introduït l'hora!";
$_lang['Please check start and end time!'] = "Comproveu l'hora d'inici i finalització!";
$_lang['Please check the serial event date!'] = "Comproveu la data de l'esdeveniment seriat!";
$_lang['The serial event data has no result!'] = "L'esdeveniment seriat no té cap resultat!";
$_lang['Really delete this event?'] = "Esteu segurs de voler esborrar aquest esdeveniment?";
$_lang['use'] = "utilitza";
$_lang[':'] = ":";
$_lang['Mobile Phone'] = "Telèfon mòbil";
$_lang['submit'] = "Accepta";
$_lang['Further events'] = "Més esdeveniments";
$_lang['Remove settings only'] = "Esborra només les preferències";
$_lang['Settings removed.'] = "Les preferències s'han esborrat correctament.";
$_lang['User selection'] = "Selecció d'usuaris";
$_lang['Release'] = "Desvincula";
$_lang['none'] = "cap";
$_lang['only read access to selection'] = "només accés d'escriptura a la selecció";
$_lang['read and write access to selection'] = "accés de lectura i escriptura a la selecció";
$_lang['Available time'] = "Temps disponible";
$_lang['flat view'] = "Visió en Llista";
$_lang['o_dateien'] = "Administrador de fitxers";
$_lang['Location'] = "Ubicació";
$_lang['date_received'] = "data_rebuda";
$_lang['subject'] = "Tema";
$_lang['kat'] = "Categoria";
$_lang['projekt'] = "Projecte";
$_lang['Location'] = "Ubicació";
$_lang['name'] = "Títol";
$_lang['contact'] = "Contacte";
$_lang['div1'] = "En lloc de";
$_lang['div2'] = "Canviat per";
$_lang['kategorie'] = "Categoria";
$_lang['anfang'] = "Comença";
$_lang['ende'] = "Acaba";
$_lang['status'] = "Estat";
$_lang['filename'] = "Nom del fitxer";
$_lang['deadline'] = "Termini";
$_lang['ext'] = "a";
$_lang['priority'] = "Prioritat";
$_lang['project'] = "Projecte";
$_lang['Accept'] = "Accepta";
$_lang['Please enter your user name here.'] = "Si us plau, escriviu aquí el vostre nom.";
$_lang['Please enter your password here.'] = "Si us plau, escriviu aquí la vostra contrassenya.";
$_lang['Click here to login.'] = "Feu clic aquí per començar.";
$_lang['No New Polls'] = "No hi ha cap enquesta nova.";
$_lang['&nbsp;Hide read elements'] = "&nbsp;Amaga els elements llegits";
$_lang['&nbsp;Show read elements'] = "&nbsp;Mostra els elements llegits";
$_lang['&nbsp;Hide archive elements'] = "&nbsp;Amaga els elements arxivats";
$_lang['&nbsp;Show archive elements'] = "&nbsp;Mostra els elements arxivats";
?>