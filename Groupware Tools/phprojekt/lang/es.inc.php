<?php
// es.inc.php, versión en español para PHProjekt
// Traducido por Guillermo Cavazos <gcavazos@colegios.net>

$chars = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
$name_month = array("", "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Set", "Oct", "Nov", "Dic");
$l_text31a = array("default", "15 min.", "30 min.", " 1 hora", " 2 horas", " 4 horas", " 1 día");
$l_text31b = array(0, 15, 30, 60, 120, 240, 1440);
$name_day = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
$name_day2 = array("Lu", "Ma", "Mi", "Ju", "Vi", "Sa", "Do");

$_lang['No Entries Found']= "No Entries Found";
$_lang['No Todays Events']= "HNo Todays Events";
$_lang['No new forum postings']= "No new forum postings";
$_lang['in category']= "in category";
$_lang['Filtered']= "Filtered";
$_lang['Sorted by']= "Sorted by";
$_lang['go'] = "enviar";
$_lang['back'] = "volver";
$_lang['print'] = "imprimir";
$_lang['export'] = "exportar";
$_lang['| (help)'] = "| (ayuda)";
$_lang['Are you sure?'] = "¿Está seguro?";
$_lang['items/page'] = "elemento/página";
$_lang['records'] = "elementos"; // elements
$_lang['previous page'] = "página anterior";
$_lang['next page'] = "página siguiente";
$_lang['first page'] = "first page";
$_lang['last page'] = "last page";
$_lang['Move']  = "Mover";
$_lang['Copy'] = "Copiar";
$_lang['Delete'] = "Eliminar";
$_lang['Save'] = "Guardar";
$_lang['Directory'] = "directorio";
$_lang['Also Delete Contents'] = "eliminar también contenido";
$_lang['Sum'] = "Suma";
$_lang['Filter'] = "Filtrar";
$_lang['Please fill in the following field'] = "Llene el siguiente campo, por favor";
$_lang['approve'] = "Aprobar";
$_lang['undo'] = "deshacer";
$_lang['Please select!']="Seleccione Uno!";
$_lang['New'] = "Nuevo";
$_lang['Select all'] = "Seleccionar Todo";
$_lang['Printable view'] = "Vista Impresión";
$_lang['New record in module '] = "Nuevo registro en módulo ";
$_lang['Notify all group members'] = "Notificar a todos los miembros del Grupo";
$_lang['Yes'] = "Si";
$_lang['No'] = "No";
$_lang['Close window'] = "Cerrar Ventana";
$_lang['No Value'] = "Sin Valor"; 
$_lang['Standard'] = "Standard";
$_lang['Create'] = "Anlegen";
$_lang['Modify'] = "Ändern";   
$_lang['today'] = "today";

// admin.php
$_lang['Password'] = "Contraseña";
$_lang['Login'] = "Acceso";
$_lang['Administration section'] = "Sección de administración";
$_lang['Your password'] = "Su contraseña";
$_lang['Sorry you are not allowed to enter. '] = "Disculpe, no está autorizado a ingresar";
$_lang['Help'] = "Ayuda";
$_lang['User management'] = "Administración de usuarios";
$_lang['Create'] = "Crear";
$_lang['Projects'] = "Proyectos";
$_lang['Resources'] = "Recursos";
$_lang['Resources management'] = "Resources management";
$_lang['Bookmarks'] = "Favoritos";
$_lang['for invalid links'] = "Verificar validez de los enlaces";
$_lang['Check'] = "Verificar";
$_lang['delete Bookmark'] = "eliminar Favoritos";
$_lang['(multiple select with the Ctrl-key)'] = "(selección múltiple con la tecla 'Ctrl')";
$_lang['Forum'] = "Foro";
$_lang['Threads older than'] = "Temas con más de";
$_lang[' days '] = " días ";
$_lang['Chat'] = "Chat";
$_lang['save script of current Chat'] = "grabar texto del Chat actual";
$_lang['Chat script'] = "texto del Chat";
$_lang['New password'] = "Nueva contraseña";
$_lang['(keep old password: leave empty)'] = "(para mantener la contraseña anterior: dejar en blanco)";
$_lang['Default Group<br> (must be selected below as well)'] = "Grupo de Default<br> (debe estar seleccionado arriba)";
$_lang['Access rights'] = "Derechos de acceso";
$_lang['Zip code'] = "Código postal";
$_lang['Language'] = "Idioma";
$_lang['schedule readable to others'] = "horario visible a otros";
$_lang['schedule invisible to others'] = "horario invisible a otros";
$_lang['schedule visible but not readable'] = "horario visible pero no leible";
$_lang['these fields have to be filled in.'] = "estos espacios deben ser completados.";
$_lang['You have to fill in the following fields: family name, short name and password.'] = "Debe completar los siguientes espacios: Apellido, Iniciales y Contraseña.";
$_lang['This family name already exists! '] = "¡Este apellido ya existe! ";
$_lang['This short name already exists!'] = "¡Estas iniciales ya existen!";
$_lang['This login name already exists! Please chosse another one.'] = "Este nombre de usuario ya existe! Seleccione uno diferente.";
$_lang['This password already exists!'] = "¡Esta contraseña ya existe!";
$_lang['This combination first name/family name already exists.'] = "La combinación nombre/apellido ya existe.";
$_lang['the user is now in the list.'] = "el usuario está ahora en la lista.";
$_lang['the data set is now modified.'] = "este conjunto de datos está ahora modificado.";
$_lang['Please choose a user'] = "Por favor seleccione un usuario";
$_lang['is still listed in some projects. Please remove it.'] = "está aún listado en algunos Proyectos. Por favor, remuévalo.";
$_lang['All profiles are deleted'] = "Todos los perfiles están eliminados";
$_lang['A Profile with the same name already exists'] = "A profile with the same name already exists";
$_lang['is taken out of all user profiles'] = "está excluído de todos los perfiles";
$_lang['All todo lists of the user are deleted'] = "Todas las listas Por Hacer del usuario están eliminadas";
$_lang['is taken out of these votes where he/she has not yet participated'] = "está excluído de aquellos votos donde él/ella aún no ha participado";
$_lang['All events are deleted'] = "Todos los eventos están eliminados";
$_lang['user file deleted'] = "archivo de usuario eliminado";
$_lang['bank account deleted'] = "cuenta bancaria eliminada";
$_lang['finished'] = "terminado";
$_lang['Please choose a project'] = "Por favor seleccione un proyecto";
$_lang['The project is deleted'] = "El proyecto está eliminado";
$_lang['All links in events to this project are deleted'] = "Todos los enlaces en eventos de este proyecto están eliminados";
$_lang['The duration of the project is incorrect.'] = "La duración del proyecto es incorrecta.";
$_lang['The project is now in the list'] = "El proyecto está ahora en la lista";
$_lang['The project has been modified'] = "El proyecto ha sido modificado";
$_lang['Please choose a resource'] = "Por favor seleccione un recurso";
$_lang['The resource is deleted'] = "El recurso está eliminado";
$_lang['All links in events to this resource are deleted'] = "Todos los enlaces en eventos de este recurso están eliminados";
$_lang[' The resource is now in the list.'] = "El recurso está ahora en la lista.";
$_lang[' The resource has been modified.'] = "El recurso ha sido modificado.";
$_lang['The server sent an error message.'] = "El servidor envió un mensaje de error";
$_lang['All Links are valid.'] = "Todos los enlaces son válidos.";
$_lang['Please select at least one bookmark'] = "Por favor seleccione como mínimo un Bookmark";
$_lang['The bookmark is deleted'] = "El Favorito está eliminado";
$_lang['threads older than x days are deleted.'] = "Los temas anteriorse a x días fuerón eliminados.";
$_lang['All chat scripts are removed'] = "Todos los textos de Chat están eliminados";
$_lang['or'] = "o";
$_lang['Timecard management'] = "Administración de asistencia";
$_lang['View'] = "Ver";
$_lang['Choose group'] = "Seleccione un grupo";
$_lang['Group name'] = "Nombre del grupo";
$_lang['Short form'] = "Abreviado";
$_lang['Category'] = "Categoría";
$_lang['Remark'] = "Observación";
$_lang['Group management'] = "Administración de grupos";
$_lang['Please insert a name'] = "Por favor ingrese un nombre";
$_lang['Name or short form already exists'] = "Nombre o Iniciales ya existen";
$_lang['Automatic assign to group:'] = "Asignación automática a grupo:";
$_lang['Automatic assign to user:'] = "Asignación automática a usuario:";
$_lang['Help Desk Category Management'] = "Administración de categorías Helpdesk";
$_lang['Category deleted'] = "Categoría eliminada";
$_lang['The category has been created'] = "La categoría ha sido creada";
$_lang['The category has been modified'] = "La categoría ha sido modificada";
$_lang['Member of following groups'] = "Miembro de los siguientes grupos";
$_lang['Primary group is not in group list'] = "El Grupo Default no esta en la lista de grupos";
$_lang['Login name'] = "Nombre de Usuario";
$_lang['You cannot delete the default group'] = "No puede Ud. eliminar el grupo de default";
$_lang['Delete group and merge contents with group'] = "Eliminar grupo e insertar el contenido con el grupo";
$_lang['Please choose an element'] = "Por favor seleccione un elemento";
$_lang['Group created'] = "Grupo creado";
$_lang['File management'] = "Administración de Archivos/Ficheros";
$_lang['Orphan files'] = "Archivos/Ficheros Huerfanos";
$_lang['Deletion of super admin root not possible'] = "No es posible eliminar super usuario de raiz";
$_lang['ldap name'] = "Nombre ldap";
$_lang['mobile // mobile phone'] = "movil/celular"; // mobil phone
$_lang['Normal user'] = "Usuario Normal";
$_lang['User w/Chief Rights'] = "Usuario Jefe";
$_lang['Administrator'] = "Administrador";
$_lang['Logging'] = "Ingresando";
$_lang['Logout'] = "Salir";
$_lang['posting (and all comments) with an ID'] = "posting (y todos los comentarios) con una ID";
$_lang['Role deleted, assignment to users for this role removed'] = "Rol Eliminado, tareas a usuario para este rol removidas";
$_lang['The role has been created'] = "El rol ha sido creado";
$_lang['The role has been modified'] = "El rol ha sido modificado";
$_lang['Access rights'] = "Access rights";
$_lang['Usergroup'] = "Gruppe";
$_lang['logged in as'] = "Angemeldet als";

//chat.php
$_lang['Quit chat']= "Quit chat";

//contacts.php
$_lang['Contact Manager'] = "Administración de Contactos";
$_lang['New contact'] = "Nuevo contacto";
$_lang['Group members'] = "Miembros del grupo";
$_lang['External contacts'] = "Contactos externos";
$_lang['&nbsp;New&nbsp;'] = "&nbsp;Nuevo&nbsp;";
$_lang['Import'] = "Importar";
$_lang['The new contact has been added'] = "El nuevo contacto ha sido agregado";
$_lang['The date of the contact was modified'] = "Las fechas del contacto fueron modificadas";
$_lang['The contact has been deleted'] = "El contacto ha sido eliminado";
$_lang['Open to all'] = "Accesible a todos";
$_lang['Picture'] = "Imagen";
$_lang['Please select a vcard (*.vcf)'] = "Por favor seleccione una vcard (*.vcf)";
$_lang['create vcard'] = "crear vcard";
$_lang['import address book'] = "importar libreta de direcciones";
$_lang['Please select a file (*.csv)'] = "Seleccione un archivo (*.csv)";
$_lang['Howto: Open your outlook express address book and select file/export/other book<br>Then give the file a name, select all fields in the next dialog and finish'] = "Como?: abra la libreta de direcciones de Outlook Express y seleccione 'archivo'/'exportar'/'libreta de direcciones'<br> Seleccione el tipo de archivo 
como valores separados por coma<br> Nombre el archivo con la extension .cvs. Seleccione todos los campos en siguiente dialogo y presione 'Finalizar'";
$_lang['Open outlook at file/export/export in file,<br>choose comma separated values (Win), then select contacts in the next form,<br>give the export file a name and finish.'] = "Abra Outlook Express y seleccione 'archivo'/'exportar'/'libreta de direcciones',<br>
seleccione 'valores separados por coma (Win)', despues seleccione 'contactos' en la siguiente forma,<br>
nombre el archivo, presione 'Finalizar'.";
$_lang['Please choose an export file (*.csv)'] = "Por favor seleccione un archivo a exportar (*.csv)";
$_lang['Please export your address book into a comma separated value file (.csv), and either<br>1) apply an import pattern OR<br>2) modify the columns of the table with a spread sheet to this format<br>(Delete colums in you file that are not listed here and create empty colums for fields that do not exist in your file):'] = "Please export your address book into a comma separated value file (.csv), and either<br>
1) apply an import pattern OR<br>
2) modify the columns of the table with a spread sheet to this format<br>
(Delete colums in you file that are not listed here and create empty colums for fields that do not exist in your file):";
$_lang['Please insert at least the family name'] = "Por favor ingrese cuando menos el apellido";
$_lang['Record import failed because of wrong field count'] = "Fallo la importacion del registro por una cuenta de campo erronéa";
$_lang['Import to approve'] = "Importar para aprobar";
$_lang['Import list'] = "Importar Lista";
$_lang['The list has been imported.'] = "La lista ha sido aprobada.";
$_lang['The list has been rejected.'] = "La lista ha sido rechazada.";
$_lang['Profiles'] = "Perfiles";
$_lang['Parent object'] = "Objeto Padre";
$_lang['Check for duplicates during import'] = "Revisar duplicados al importar";
$_lang['Fields to match'] = "Campos a igualar";
$_lang['Action for duplicates'] = "Acción para duplicados";
$_lang['Discard duplicates'] = "Eliminar duplicados";
$_lang['Dispose as child'] = "Eliminar con hijo";
$_lang['Store as profile'] = "Almacenar como perfil";
$_lang['Apply import pattern'] = "Aplicar reglas de importación";
$_lang['Import pattern'] = "Importar reglas";
$_lang['For modification or creation<br>upload an example csv file'] = "Subir archivo importación (csv)";
$_lang['Skip field'] = "Saltar campo";
$_lang['Field separator'] = "Separador de campo";
$_lang['Contact selector'] = "Contact selector";
$_lang['Use doublet'] = "Use doublet";
$_lang['Doublets'] = "Doublets";

// filemanager.php
$_lang['Please select a file'] = "Por favor seleccione un archivo";
$_lang['A file with this name already exists!'] = "Un archivo con este nombre ya existe!";
$_lang['Name'] = "Nombre";
$_lang['Comment'] = "Comentario";
$_lang['Date'] = "Fecha";
$_lang['Upload'] = "Guardar";
$_lang['Filename and path'] = "Nombre del archivo y localización";
$_lang['Delete file'] = "Eliminar archivo";
$_lang['Overwrite'] = "Sobreescribir";
$_lang['Access'] = "Acceso";
$_lang['Me'] = "Yo";
$_lang['Group'] = "group";
$_lang['Some'] = "Algunos";
$_lang['As parent object'] = "igual al directorios";
$_lang['All groups'] = "All groups";
$_lang['You are not allowed to overwrite this file since somebody else uploaded it'] = "Usted no puede sobreescribir este archivo ya que alguien más lo guardó";
$_lang['personal'] = "personal";
$_lang['Link'] = "Enlace";
$_lang['name and network path'] = "Agregar Ruta al archivo/fichero";
$_lang['with new values'] = "con nuevos valores";
$_lang['All files in this directory will be removed! Continue?'] = "Todos los archivos/fichero en este directorio serán eliminados! Continuar?";
$_lang['This name already exists'] = "Este nombre YA existe";
$_lang['Max. file size'] = "Tamaño Max. del archivo/fichero";
$_lang['links to'] = "enlaces a";
$_lang['objects'] = "objetos";
$_lang['Action in same directory not possible'] = "Acción dentro del mismo directorio es imposible";
$_lang['Upload = replace file'] = "Subir = remplazar archivo/fichero";
$_lang['Insert password for crypted file'] = "Insertar contraseña para archivo/fichero encriptado";
$_lang['Crypt upload file with password'] = "Criptar archivo/fichero con contraseña";
$_lang['Repeat'] = "Repetir";
$_lang['Passwords dont match!'] = "Las contraseñas no coinciden!";
$_lang['Download of the password protected file '] = "Bajar el archivo/fichero protegido con contraseña ";
$_lang['notify all users with access'] = "notificar a todos los usuarios con acceso";
$_lang['Write access'] = "Escribir acceso";
$_lang['Version'] = "Versión";
$_lang['Version management'] = "Administración de Versión";
$_lang['lock'] = "bloquear";
$_lang['unlock'] = "desbloquear";
$_lang['locked by'] = "bloqueado por";
$_lang['Alternative Download'] = "Enlace Alternativo";
$_lang['Download'] = "Download";
$_lang['Select type'] = "Select type";
$_lang['Create directory'] = "Create directory";
$_lang['filesize (Byte)'] = "Filesize (Byte)";

// filter
$_lang['contains'] = 'contiene';
$_lang['exact'] = 'exacto';
$_lang['starts with'] = 'inicia con';
$_lang['ends with'] = 'termina con';
$_lang['>'] = '>';
$_lang['>='] = '>=';
$_lang['<'] = '<';
$_lang['<='] = '<=';
$_lang['does not contain'] = 'no contiene';
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
$_lang['Module Designer'] = "Diseñador de Módulos";
$_lang['Module element'] = "Elementos del Módulo";
$_lang['Module'] = "Módulo";
$_lang['Active'] = "Activo";
$_lang['Inactive'] = "Inactivo";
$_lang['Activate'] = "Activar";
$_lang['Deactivate'] = "Desactivar";
$_lang['Create new element'] = "Crear nuevo elemento";
$_lang['Modify element'] = "Modificar elemento";
$_lang['Field name in database'] = "Nombre del campo en la BD";
$_lang['Use only normal characters and numbers, no special characters,spaces etc.'] = "Usar únicamente caracteres y numeros normales, sin acentos, 'ñ' o espacios etc.";
$_lang['Field name in form'] = "Nombre del campo en el Formulario";
$_lang['(could be modified later)'] = "(se puede modificar posteriormente)";
$_lang['Single Text line'] = "Línea sencilla de Texto";
$_lang['Textarea'] = "Area de Texto";
$_lang['Display'] = "Desplegar";
$_lang['First insert'] = "Primer inserto";
$_lang['Predefined selection'] = "Selección predefinida";
$_lang['Select by db query'] = "Seleccionar por consulta de BD";
$_lang['File'] = "Achivo/Fichero";

$_lang['Email Address'] = "Dirección de Email";
$_lang['url'] = "url";
$_lang['Checkbox'] = "Checkbox";
$_lang['Multiple select'] = "Selección Múltiple"; 
$_lang['Display value from db query'] = "Display value from db query";
$_lang['Time'] = "Time";
$_lang['Tooltip'] = "Tooltip";
$_lang['Appears as a tipp while moving the mouse over the field: Additional comments to the field or explanation if a regular expression is applied'] = "Aparece un tip/consejo al mover el ratón sobre el campo: Comentarios adicionales al campo o explicaciónes si se agrega una expresion regular";
$_lang['Position'] = "Posicionamiento";
$_lang['is current position, other free positions are:'] = "es la posición actual, otras posiciones libres son:";
$_lang['Regular Expression:'] = "Expresión Regular:";
$_lang['Please enter a regular expression to check the input on this field'] = "Por favor ingrese una expresión regular para verificar en contenido de este campo";
$_lang['Default value'] = "Valor por Default";
$_lang['Predefined value for creation of a record. Could be used in combination with a hidden field as well'] = "Valor predefinido para la creación de un record. También puede ser usado en combinación con un campo oculto";
$_lang['Content for select Box'] = "Contenido de la Caja de Selección";
$_lang['Used for fixed amount of values (separate with the pipe: | ) or for the sql statement, see element type'] = "Se usa con una cantidad fija de valores (separados con un : | ) o una sentencia sql, ver tipo de elemento";
$_lang['Position in list view'] = "Lugar en la Lista";
$_lang['Only insert a number > 0 if you want that this field appears in the list of this module'] = "Insertar solo un numero > 0 si desea que este campo aparezca en la lista de este módulo";
$_lang['Alternative list view'] = "Vista Alternativa de la Lista";
$_lang['Value appears in the alt tag of the blue button (mouse over) in the list view'] = "Los valores aparecen en una etiqueta alt del botón azul (mouse over) en la lista";
$_lang['Filter element'] = "Filtar elemento";
$_lang['Appears in the filter select box in the list view'] = "Aparece en el filtro de la caja de selección en la lista";
$_lang['Element Type'] = "Elemento Tipo";
$_lang['Select the type of this form element'] = "Seleccione el tipo para este elemento del formulario";
$_lang['Check the content of the previous field!'] = "Checar el contenido del campo previo!";
$_lang['Span element over'] = "Expandir elemento sobre";
$_lang['columns'] = "columnas";
$_lang['rows'] = "filas";
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
$_lang['Welcome to the setup of PHProject!<br>'] = "Bienvenido al instalador del PHProjekt!<br>";
$_lang['Please remark:<ul><li>A blank database must be available<li>Please ensure that the webserver is able to write the file config.inc.php'] = "Tome en cuenta:<ul>
<li>Debe existir una base de datos disponible en blanco
<li>Asegurese de que el servidor web permite la escritura del archivo 'config.inc.php'<br> (p.e. 'chmod 777')";
$_lang['<li>If you encounter any errors during the installation, please look into the <a href=help/faq_install.html target=_blank>install faq</a>or visit the <a href=http://www.PHProjekt.com/forum.html target=_blank>Installation forum</a></i>'] = "<li>Si se encuentra con errores durante la instalación, por favor lea el archivo <a href='help/faq_install.html' target=_blank>faq de Instalación</a>
or visit the <a href='http://www.PHProjekt.com/forum.html' target=_blank>Installation forum</a></i>";
$_lang['Please fill in the fields below'] = "Por favor llene los sigueintes espacios de abajo";
$_lang['(In few cases the script wont respond.<br>Cancel the script, close the browser and try it again).<br>'] = "(En algunos casos el script no responderá.<br>
Cancele el script, cierre el navegador e inténtelo nuevamente).<br>";
$_lang['Type of database'] = "Tipo de base de datos";
$_lang['Hostname'] = "Servidor";
$_lang['Username'] = "Usuario";

$_lang['Name of the existing database'] = "Nombre de la base de datos existente";
$_lang['config.inc.php not found! Do you really want to update? Please read INSTALL ...'] = "config.inc.php no fue encontrado!  Realmente quiere actualizar?  Por favor lea el archivo INSTALL ...";
$_lang['config.inc.php found! Maybe you prefer to update PHProject? Please read INSTALL ...'] = "config.inc.php encontrado!  Quizás prefiera actualizar PHProjekt? Por favor lea el archivo INSTALL ...";
$_lang['Please choose Installation,Update or Configure!'] = "Por favor seleccione 'Instalación' o 'Actualización'! volver ...";
$_lang['Sorry, I cannot connect to the database! <br>Please close all browser windows and restart the installation.'] = "Disculpe, no funciona! <br> Por favor corríjalo y reinicie la instalación.";
$_lang['Sorry, it does not work! <br> Please set DBDATE to Y4MD- or let phprojekt change this environment-variable (php.ini)!'] = "Discuple, no funciona!<br> Por favor establezca DBDATE a 'Y4MD-' o permita que phprojekt modifique esta variable-ambiente (php.ini)!";
$_lang['Seems that You have a valid database connection!'] = "Felicitaciones! Tiene una conexión válida a la base de datos!";
$_lang['Please select the modules you are going to use.<br> (You can disable them later in the config.inc.php)<br>'] = "Por favor seleccione los módulos que utilizará.<br> (Puede deshabilitarlos luego en config.inc.php)<br>";
$_lang['Install component: insert a 1, otherwise keep the field empty'] = "Para instalar el componente:  Inserte un '1', de lo contrario mantenga el espacio vacío";
$_lang['Group views'] = "Vistas de grupos";
$_lang['Todo lists'] = "Listas por hacer";

$_lang['Voting system'] = "Sistema de votación";


$_lang['Contact manager'] = "Administración de contactos";
$_lang['Name of userdefined field'] = "Nombre del campo definido por el usuario";
$_lang['Userdefined'] = "Definido por Usuario";
$_lang['Profiles for contacts'] = "Perfiles para contactos";
$_lang['Mail'] = "Correo rápido";
$_lang['send mail'] = " enviar mail";
$_lang[' only,<br> &nbsp; &nbsp; full mail client'] = " unicamente,<br> &nbsp; &nbsp; cliente email completo";



$_lang['1 to show appointment list in separate window,<br>&nbsp; &nbsp; 2 for an additional alert.'] = "'1' para mostrar lista de actividades en una ventana separada,<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'2' para una alerta adicional.";
$_lang['Alarm'] = "Alarma";
$_lang['max. minutes before the event'] = "minutos como máximo antes del evento";
$_lang['SMS/Mail reminder'] = "Recordatorio SMS/Mail";
$_lang['Reminds via SMS/Email'] = "Recuerda vía SMS/Email";
$_lang['1= Create projects,<br>&nbsp; &nbsp; 2= assign worktime to projects only with timecard entry<br>&nbsp; &nbsp; 3= assign worktime to projects without timecard entry<br>&nbsp; &nbsp; (Selection 2 or 3 only with module timecard!)'] = "'1'= Crear proyectos,<br>
&nbsp; &nbsp; '2'= asignar horario a proyectos únicamente con ingresos a asistencias<br>
&nbsp; &nbsp; '3'= asignar horario a proyectos sin ingresos a asistencias<br> ";

$_lang['Name of the directory where the files will be stored<br>( no file management: empty field)'] = "Nombre del directorio donde los archivos serán guardados<br>( sin administración de archivos: espacio vacío)";
$_lang['absolute path to this directory (no files = empty field)'] = "ruta de acceso absoluta de este directorio (sin archivos = espacio vacío)";
$_lang['Time card'] = "Asistencia";
$_lang['1 time card system,<br>&nbsp; &nbsp; 2 manual insert afterwards sends copy to the chief'] = "'1' control de asistencia,<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '2' ingreso manual posterior envía una copia al jefe";
$_lang['Notes'] = "Notas";
$_lang['Password change'] = "Cambio de contraseña";
$_lang['New passwords by the user - 0: none - 1: only random passwords - 2: choose own'] = "Nuevas contraseñas por el usuario - 0: ninguna - 1: solamente contraseñas aleatorias - 2: elegir la propia";
$_lang['Encrypt passwords'] = "Encriptar contraseñas";
$_lang['Login via '] = "Ingreso vía ";
$_lang['Extra page for login via SSL'] = "Página adicional para el ingreso vía SSL";
$_lang['Groups'] = "Grupos";
$_lang['User and module functions are assigned to groups<br>&nbsp; &nbsp; (recommended for user numbers > 40)'] = "Funciones de usuario y módulos son asignadas a grupos<br>
&nbsp;&nbsp;&nbsp;&nbsp;(recomendado para un número de usuarios > 40)";
$_lang['User and module functions are assigned to groups'] = "Funciones de usuario y módulos son asignadas a grupos";
$_lang['Help desk'] = "Seguimiento de Pedidos (RT)";
$_lang['Help Desk Manager / Trouble Ticket System'] = "Administración de Mesa de Ayuda / Sistema de Tickets de Problemas (RT)";
$_lang['RT Option: Customer can set a due date'] = "Opción RT: El cliente puede fijar una fecha límite";
$_lang['RT Option: Customer Authentification'] = "Opción RT: Autentificación del cliente";
$_lang['0: open to all, email-address is sufficient<br>1: registered contact enter his family name<br>2: his email'] = "0: abierto a todos, dirección de e-mail es suficiente, 1: cliente debe estar en la lista de contactos e ingresar su Apellido";
$_lang['RT Option: Assigning request'] = "Opción RT: Asignación de pedidos";
$_lang['0: by everybody, 1: only by persons with status chief'] = "0: por todos, 1: solamente por personas con rango de 'jefe'";
$_lang['Email Address of the support'] = "Dirección de e-mail del soporte";
$_lang['Scramble filenames'] = "Revolver Nombres de Archivos";
$_lang['creates scrambled filenames on the server<br>assigns previous name at download'] = "crea nombres de archivos revueltos en el servidor<br>
asignandole nombre al momento de bajalos";

$_lang['0: last name, 1: short name, 2: login name'] = "0: Apellido, 1: Iniciales, 2: Login nombre";
$_lang['Prefix for table names in db'] = "Prefix for table names in db";
$_lang['Alert: Cannot create file config.inc.php!<br>Installation directory needs rwx access for your server and rx access to all others.'] = "Alerta: No se puede crear el archivo 'config.inc.php'!<br>
El directorio de instalación necesita acceso rwx para su servidor y acceso rx para todos los demás.";
$_lang['Location of the database'] = "Localización de la base de datos";
$_lang['Type of database system'] = "Tipo del sistema de base de datos";
$_lang['Username for the access'] = "Usuario para el acceso";
$_lang['Password for the access'] = "Contraseña para el acceso";
$_lang['Name of the database'] = "Nombre de la base de datos";
$_lang['Prefix for database table names'] = "Prefix for database table names";                                                                                               
$_lang['First background color'] = "Primer color de fondo";
$_lang['Second background color'] = "Segundo color de fondo";
$_lang['Third background color'] = "Tercer color de fondo";
$_lang['Color to mark rows'] = "Color to mark rows";
$_lang['Color to highlight rows'] = "Color to highlight rows";
$_lang['Event color in the tables'] = "Color de eventos en las tablas";
$_lang['company icon yes = insert name of image'] = "Icono de la organización Si = Ingrese nombre de la imagen";
$_lang['URL to the homepage of the company'] = "URL del sitio web de la organización";
$_lang['no = leave empty'] = "No = deje en blanco";
$_lang['First hour of the day:'] = "Primera hora del día:";
$_lang['Last hour of the day:'] = "Ultima hora del día:";
$_lang['An error ocurred while creating table: '] = "Un error ocurrió mientras se creaba la tabla: ";
$_lang['Table dateien (for file-handling) created'] = "Tabla 'dateien' (para manipulación de archivos) creada";
$_lang['File management no = leave empty'] = "Administración de archivos No = deje en blanco";
$_lang['yes = insert full path'] = "Si = Ingrese ruta de acceso completa";
$_lang['and the relative path to the PHProjekt directory'] = "y adicionalmente la ruta de acceso relativa a la PHProjekt dir";
$_lang['Table profile (for user-profiles) created'] = "Tabla 'profile' (para perfiles de usuarios) creada";
$_lang['User Profiles yes = 1, no = 0'] = "Perfiles si = 1, no = 0";
$_lang['Table todo (for todo-lists) created'] = "Tabla 'todo' (Para la lista Por Hacer) creada";
$_lang['Todo-Lists yes = 1, no = 0'] = "Lista Por Hacer si = 1, no = 0";
$_lang['Table forum (for discssions etc.) created'] = "Tabla 'forum' (para Foros de discusión) creada";
$_lang['Forum yes = 1, no = 0'] = "Foro si = 1, no = 0";
$_lang['Table votum (for polls) created'] = "Tabla 'votum' (para votaciones) creada";
$_lang['Voting system yes = 1, no = 0'] = "Sistema de votación si = 1, no = 0";
$_lang['Table lesezeichen (for bookmarks) created'] = "Tabla 'lesezeichen' (para bookmarks) creada";
$_lang['Bookmarks yes = 1, no = 0'] = "Bookmarks si = 1, no = 0";
$_lang['Table ressourcen (for management of additional ressources) created'] = "Tabla 'ressourcen' (para administración de recursos adicionales) creada";
$_lang['Resources yes = 1, no = 0'] = "Recursos si = 1, no = 0";
$_lang['Table projekte (for project management) created'] = "Tabla 'projekte' (para administración de proyectos) creada";
$_lang['Table contacts (for external contacts) created'] = "Tabla 'contacts' (para contactos externos) creada";
$_lang['Table notes (for notes) created'] = "Tabla 'notes' (para notas) creada";
$_lang['Table timecard (for time sheet system) created'] = "Tabla 'timecard' (para control de asistencia) creada";
$_lang['Table groups (for group management) created'] = "Tabla 'groups' (para administración de grupos) creada";
$_lang['Table timeproj (assigning work time to projects) created'] = "Tabla 'timeproj' (asignación de tiempo de trabajo a proyectos) creada";
$_lang['Table rts and rts_cat (for the help desk) created'] = "Tabla 'rts' y 'rts_cat' (para el sistema de seguimiento de pedidos) creada";
$_lang['Table mail_account, mail_attach, mail_client und mail_rules (for the mail reader) created'] = "Tabla mail_account, mail_attach, mail_client y mail_rules (para el lector de correo) creada";
$_lang['Table logs (for user login/-out tracking) created'] = "Tabla logs (para seguimiento de ingreos/salidas de usuarios) creada";
$_lang['Tables contacts_profiles und contacts_prof_rel created'] = "Tablas contacts_profiles y contacts_prof_rel creadas";
$_lang['Project management yes = 1, no = 0'] = "Administración de proyectos si = 1, no = 0";
$_lang['additionally assign resources to events'] = "adicionalmente asignar recursos a eventos";
$_lang['Address book  = 1, nein = 0'] = "Libreta de direcciones si = 1, no = 0";
$_lang['Mail no = 0, only send = 1, send and receive = 2'] = "Correo rápido si = 1, no = 0";
$_lang['Chat yes = 1, no = 0'] = "Chat yes = 1, no = 0";
$_lang['Name format in chat list'] = "Name format in chat list";
$_lang['0: last name, 1: first name, 2: first name, last name,<br> &nbsp; &nbsp; 3: last name, first name'] = "0: last name, 1: first name, 2: first name, last name,<br> &nbsp; &nbsp; 3: last name, first name";
$_lang['Timestamp for chat messages'] = "Timestamp for chat messages";
$_lang['users (for authentification and address management)'] = "'user' (para autentificación y administración de direcciones)";
$_lang['Table termine (for events) created'] = "Tabla 'termine' (para eventos) creada";
$_lang['The following users have been inserted successfully in the table user:<br>root - (superuser with all administrative privileges)<br>test - (chief user with restricted access)'] = "Los siguientes usuarios han sido insertados exitosamente en la tabla 'user':<br>
'root' - (superusuario con todos los privilegios administrativos)<br>
'test' - (usuario jefe con acceso restringido)";
$_lang['The group default has been created'] = "El grupo 'default' ha sido creado";
$_lang['Please do not change anything below this line!'] = "Por favor no cambie nada por debajo de esta línea!";
$_lang['Database error'] = "Error de base de datos";
$_lang['Finished'] = "Terminado";
$_lang['There were errors, please have a look at the messages above'] = "Se presentarón algunos errores, verifique los mensajes en la parte superior";
$_lang['All required tables are installed and <br>the configuration file config.inc.php is rewritten<br>It would be a good idea to makea backup of this file.<br>'] = "Todas las tablas requeridas están instaladas y <br>
el archivo de configuración 'config.inc.php' está re-escrito<br>
Sería una buena idea hacer una copia de respaldo (backup) de este archivo.<br>
Cierre todas las ventanas de su navegador ahora.<br>";
$_lang['The administrator root has the password root. Please change his password here:'] = "El administrador 'root' tiene la contraseña 'root'. Por favor cambie esta contraseña:";
$_lang['The user test is now member of the group default.<br>Now you can create new groups and add new users to the group'] = "El usuario 'test' es miembro del grupo 'default'.<br>
Ahora puede crear nuevos grupos y agregar nuevos usuarios a los grupos";
$_lang['To use PHProject with your Browser go to <b>index.php</b><br>Please test your configuration, especially the modules Mail and Files.'] = "Para usar el PHProjekt con su navegador vaya a <b>index.php</b><br>
Por favor pruebe su configuración, especialmente los módulos 'Correo rápido' y 'Archivos'.";

$_lang['Alarm x minutes before the event'] = "Alarma x minutos antes del evento";
$_lang['Additional Alarmbox'] = "Ventana de alarma adicional";
$_lang['Mail to the chief'] = "Correo al jefe";
$_lang['Out/Back counts as: 1: Pause - 0: Workingtime'] = "Fuera/De vuelta cuenta como: 1: Pausa - 0: Tiempo de trabajo";
$_lang['Passwords will now be encrypted ...'] = "Las contraseñas ahora serán encriptadas";
$_lang['Filenames will now be crypted ...'] = "Los nombres de archivos ahora serán encriptados ...";
$_lang['Do you want to backup your database right now? (And zip it together with the config.inc.php ...)<br>Of course I will wait!'] = "Desea hacer una copia de seguridad (backup) de su base de datos ahora?<br> (Y comprimirlo en un archivo zip junto con el config.inc.php ...)<br>
Por supuesto que esperaré!";
$_lang['Next'] = "Siguiente";
$_lang['Notification on new event in others calendar'] = "Notificación de un nuevo evento en otro calendario";
$_lang['Path to sendfax'] = "Ruta para sendfax";
$_lang['no fax option: leave blank'] = "sin opción de fax : dejar en blanco";
$_lang['Please read the FAQ about the installation with postgres'] = "Por favor lea el FAQ para instalación con postgres";
$_lang['Length of short names<br> (Number of letters: 3-6)'] = "Cuantas letras para inicales?<br> (Número de letras: 3-6)";
$_lang['If you want to install PHProjekt manually, you find<a href=http://www.phprojekt.com/files/sql_dump.tar.gz target=_blank>here</a> a mysql dump and a default config.inc.php'] = "Si desea instalar PHProjekt manualmente, Ud encontrará
<a href='http://www.phprojekt.com/files/sql_dump.tar.gz' target=_blank>aquí</a> el dump de mysql y un default config.inc.php";
$_lang['The server needs the privilege to write to the directories'] = "El servidor necesita tener prvilegios de 'escritura' en el directorio";
$_lang['Header groupviews'] = "Encabezados para Grupos";
$_lang['name, F.'] = "nombre, F.";
$_lang['shortname'] = "iniciales";
$_lang['loginname'] = "nombre de usuario";
$_lang['Please create the file directory'] = "Favor de crear el directorio de archivos";
$_lang['default mode for forum tree: 1 - open, 0 - closed'] = "modo default para el árbol del foro: 1 - abierto, 0 - cerrado";
$_lang['Currency symbol'] = "Símbolo de dinero";
$_lang['current'] = "current";
$_lang['Default size of form elements'] = "Default size of form elements";
$_lang['use LDAP'] = "usar LDAP";
$_lang['Allow parallel events'] = "Permitir eventos paralelos";
$_lang['Timezone difference [h] Server - user'] = "Diferencia de usos horarios [h] Servidor - usuario";
$_lang['Timezone'] = "Uso horario";
$_lang['max. hits displayed in search module'] = "max. no. de hits desplegados en el modulo de busqueda";
$_lang['Time limit for sessions'] = "Tiempo límite para sessiones";
$_lang['0: default mode, 1: Only for debugging mode'] = "0: modo default, 1: Solo para modo debugeo";
$_lang['Enables mail notification on new elements'] = "Habilita notificaciones de mail en nuevos elementos";
$_lang['Enables versioning for files'] = "Habilita versionamiento para archivos/ficheros";
$_lang['no link to contacts in other modules'] = "sin enlace a contactos en otros modulos";
$_lang['Highlight list records with mouseover'] = "Iluminar lista de records con 'mouseover'";
$_lang['Track user login/logout'] = "Seguimiento de ingresos/salidas de usuarios";
$_lang['Access for all groups'] = "Acceso a todos los grupos";
$_lang['Option to release objects in all groups'] = "Opción para liberar objetos en todos los grupos";
$_lang['Default access mode: private=0, group=1'] = "Modo de acceso por default: privado=0, groupo=1";
$_lang['Adds -f as 5. parameter to mail(), see php manual'] = "Agrega '-f' como 5. parametro para mail(), ver manual de php";
$_lang['end of line in body; e.g. \r\n (conform to RFC 2821 / 2822)'] = "fin de la línea body; e.g. \\r\\n (conform to RFC 2821 / 2822)";
$_lang['end of header line; e.g. \r\n (conform to RFC 2821 / 2822)'] = "fin de la línea header; e.g. \\r\\n (conform to RFC 2821 / 2822)";
$_lang['Sendmail mode: 0: use mail(); 1: use socket'] = "Modo Sendmail : 0: usar mail(); 1: usar socket";
$_lang['the real address of the SMTP mail server, you have access to (maybe localhost)'] = "la dirección real del servidor SMTP (puede ser localhost)";
$_lang['name of the local server to identify it while HELO procedure'] = "nombre del servidor local para identificarlo durante el proceso HELO";
$_lang['Authentication'] = "Autenticación";
$_lang['fill out in case of authentication via POP before SMTP'] = "llenar en caso de autenticación vía POP antes de SMTP";
$_lang['real username for POP before SMTP'] = "nombre de usuario real para POP antes de SMTP";
$_lang['password for this pop account'] = "contraseña para esta cuenta POP";
$_lang['the POP server'] = "el servidor POP";
$_lang['fill out in case of SMTP authentication'] = "llenar en caso de auth SMTP";
$_lang['real username for SMTP auth'] = "nombre de usuario real para auth SMTP";
$_lang['password for this account'] = "contraseña para esta cuenta";
$_lang['SMTP account data (only needed in case of socket)'] = "datos de cuenta SMTP (necesario solo en caso de socket)";
$_lang['No Authentication'] = "Sin Autenticación";
$_lang['with POP before SMTP'] = "con POP antes de SMTP";
$_lang['SMTP auth (via socket only!)'] = "auth SMTP (via socket únicamente!)";
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
$_lang['Resource List'] = "Lista de recursos";
$_lang['Event List'] = "Lista de eventos";
$_lang['Calendar Views'] = "Vista de grupos";

$_lang['Personnel'] = "Personal";
$_lang['Create new event'] = "Crear &amp; Eliminar Eventos";
$_lang['Day'] = "Día";

$_lang['Until'] = "Hasta";

$_lang['Note'] = "Nota";
$_lang['Project'] = "Proyecto";
$_lang['Res'] = "Recur";
$_lang['Once'] = "Una";
$_lang['Daily'] = "Diaria";
$_lang['Weekly'] = "Semanal";
$_lang['Monthly'] = "Mensual";
$_lang['Yearly'] = "Anual";

$_lang['Create'] = "Crear";

$_lang['Begin'] = "Inicio";
$_lang['Out of office'] = "Fuera de la oficina";
$_lang['Back in office'] = "De vuelta a la oficina";
$_lang['End'] = "Final";
$_lang['@work'] = "@trabajo";
$_lang['We'] = "Sem";
$_lang['group events'] = "eventos de grupo";
$_lang['or profile'] = "o perfil";
$_lang['All Day Event'] = "evento para todo el día";
$_lang['time-axis:'] = "eje-tiempo:";
$_lang['vertical'] = "vertical";
$_lang['horizontal'] = "horizontal";
$_lang['Horz. Narrow'] = "hor.estrecho";
$_lang['-interval:'] = "-intervalo:";
$_lang['Self'] = "Uno Mismo";

$_lang['...write'] = "...escribe";

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
$_lang['Please call login.php!'] = "¡Por favor ingrese a través de login.php!";

// m1.php
$_lang['There are other events!<br>the critical appointment is: '] = "Existen otros eventos!<br>la actividad en conflicto es: ";
$_lang['Sorry, this resource is already occupied: '] = "Disculpe, este recurso ya se encuentra reservado: ";
$_lang[' This event does not exist.<br> <br> Please check the date and time. '] = " Este evento no existe.<br> <br> Por favor verifique la fecha y hora. ";
$_lang['Please check your date and time format! '] = "Por favor verifique el formato de la fecha y hora! ";
$_lang['Please check the date!'] = "Por favor verifique la fecha!";
$_lang['Please check the start time! '] = "Por favor verifique la hora de inicio! ";
$_lang['Please check the end time! '] = "Por favor verifique la hora de culminación! ";
$_lang['Please give a text or note!'] = "Por favor ingrese un texto o nota!";
$_lang['Please check start and end time! '] = "Por favor verifique hora de inicio y culminación! ";
$_lang['Please check the format of the end date! '] = "Por favor verifique el formato de la fecha de culminación! ";
$_lang['Please check the end date! '] = "Por favor verifique la fecha de culminación! ";





$_lang['Resource'] = "Recurso";
$_lang['User'] = "Usuario";

$_lang['delete event'] = "eliminar evento";
$_lang['Address book'] = "Libreta de direcciones";


$_lang['Short Form'] = "Iniciales";

$_lang['Phone'] = "Teléfono";
$_lang['Fax'] = "Fax";



$_lang['Bookmark'] = "Favorito";
$_lang['Description'] = "Descripción";

$_lang['Entire List'] = "lista completa";

$_lang['New event'] = "Nuevo evento";
$_lang['Created by'] = "creado por";
$_lang['Red button -> delete a day event'] = "Botón Rojo -> elimina evento de día";
$_lang['multiple events'] = "multiples eventos";
$_lang['Year view'] = "Vista Anual";
$_lang['calendar week'] = "calendario semanal";

//m2.php
$_lang['Create &amp; Delete Events'] = "Crear &amp; Eliminar Eventos";
$_lang['normal'] = "normal";
$_lang['private'] = "privado";
$_lang['public'] = "público";
$_lang['Visibility'] = "Visibilidad";

//mail module
$_lang['Please select at least one (valid) address.'] = "Por favor seleccione cuando menos UNA dirección de correo(válida).";
$_lang['Your mail has been sent successfully'] = "Su correo ha sido enviado exitosamente";
$_lang['Attachment'] = "Adjuntar";
$_lang['Send single mails'] = "enviar correos individuales";
$_lang['Does not exist'] = "no existe";
$_lang['Additional number'] = "agregar número";
$_lang['has been canceled'] = "ha sido cancelada";

$_lang['marked objects'] = "objetos marcados";
$_lang['Additional address'] = "Dirección Adicional";
$_lang['in mails'] = "en mails";
$_lang['Mail account'] = "Mail Konto";
$_lang['Body'] = "Cuerpo";
$_lang['Sender'] = "Remitente";

$_lang['Receiver'] = "Destinatario";
$_lang['Reply'] = "Contestar";
$_lang['Forward'] = "Reenviar";
$_lang['Access error for mailbox'] = "Error de acceso del buzón";
$_lang['Receive'] = "Recibido";
$_lang['Write'] = "Enviado";
$_lang['Accounts'] = "Cuentas";
$_lang['Rules'] = "Reglas";
$_lang['host name'] = "nombre del servidor";
$_lang['Type'] = "Tipo";
$_lang['misses'] = "fallos";
$_lang['has been created'] = "ha sido creado";
$_lang['has been changed'] = "ha sido modificado";
$_lang['is in field'] = "en el campo";
$_lang['and leave on server'] = "Recibir mails y dejarlos en el servidor";
$_lang['name of the rule'] = "nombre de la regla";
$_lang['part of the word'] = "parte de la palabra";
$_lang['in'] = "en";
$_lang['sent mails'] = "mails enviados";
$_lang['Send date'] = "Fecha envio";
$_lang['Received'] = "Recibido";
$_lang['to'] = "para";
$_lang['imcoming Mails'] = "Mails entrantes";
$_lang['sent Mails'] = "enviar Mails";
$_lang['Contact Profile'] = "Perfil de Contactos";
$_lang['unread'] = "sin leer";
$_lang['view mail list'] = "lista de mails";
$_lang['insert db field (only for contacts)'] = "insertar campo de BD (únicamente para contactos)";
$_lang['Signature'] = "Firma";

$_lang['SMS'] = "SMS";
$_lang['Single account query'] = "Consulta en Cuenta";
$_lang['Notice of receipt'] = "Acuse de Recibo";
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
$_lang['Mail note to'] = "Enviar nota a";
$_lang['added'] = "agregado";
$_lang['changed'] = "modificado";

// o.php
$_lang['Calendar'] = "Calendario";
$_lang['Contacts'] = "Contactos";


$_lang['Files'] = "Archivos";



$_lang['Options'] = "Opciones";
$_lang['Timecard'] = "Asistencia";

$_lang['Helpdesk'] = "Helpdesk";

$_lang['Info'] = "Info";
$_lang['Todo'] = "Todo";
$_lang['News'] = "Noticias";
$_lang['Other'] = "Otros";
$_lang['Settings'] = "Configuración";
$_lang['Summary'] = "Resumen";

// options.php
$_lang['Description:'] = "Descripción:";
$_lang['Comment:'] = "Comentario:";
$_lang['Insert a valid Internet address! '] = "Ingrese una dirección de Internet válida! ";
$_lang['Please specify a description!'] = "Por favor detalle una descripción!";
$_lang['This address already exists with a different description'] = "Esta dirección ya existe con una descripción diferente";
$_lang[' already exists. '] = " ya existe. ";
$_lang['is taken to the bookmark list.'] = "ha sido incluído en la lista de Favoritos.";
$_lang[' is changed.'] = " está cambiado.";
$_lang[' is deleted.'] = " está eliminado.";
$_lang['Please specify a description! '] = "Por favor especifique una dirección! ";
$_lang['Please select at least one name! '] = "Por favor seleccione al menos un nombre! ";
$_lang[' is created as a profile.<br>'] = "es creado como un perfíl.<br> Después que la sección calendario se actualice el perfil estará activo.";
$_lang['is changed.<br>'] = "está cambiado.<br> Después que la sección calendario se actualice el perfil estará activo.";
$_lang['The profile has been deleted.'] = "El perfil ha sido eliminado.";
$_lang['Please specify the question for the poll! '] = "Por favor especifique la pregunta para la votación! ";
$_lang['You should give at least one answer! '] = "Debería dar al menos una respuesta! ";
$_lang['Your call for votes is now active. '] = "Su llamada a votación está ahora activa. ";
$_lang['<h2>Bookmarks</h2>In this section you can create, modify or delete bookmarks:'] = "<h2>Bookmarks</h2>En esta sección puede crear, modificar o eliminar Bookmarks:";
$_lang['Create'] = "crear";


$_lang['<h2>Profiles</h2>In this section you can create, modify or delete profiles:'] = "<h2>Perfiles</h2>En esta sección puede crear, modificar o eliminar perfiles:";
$_lang['<h2>Voting Formula</h2>'] = "<h2>Fórmula de votación</h2>";
$_lang['In this section you can create a call for votes.'] = "En esta sección puede crear una llamada a votación.";
$_lang['Question:'] = "Pregunta:";
$_lang['just one <b>Alternative</b> or'] = "solo una <b>Alternativa</b> o";
$_lang['several to choose?'] = "varias opciones?";

$_lang['Participants:'] = "Participantes:";

$_lang['<h3>Password Change</h3> In this section you can choose a new random generated password.'] = "<h3>Cambio de contraseña</h3> En esta sección puede elegir una nueva contraseña generada aleatoriamente.";
$_lang['Old Password'] = "Contraseña anterior";
$_lang['Generate a new password'] = "Generar una nueva contraseña";
$_lang['Save password'] = "Grabar contraseña";
$_lang['Your new password has been stored'] = "Su nueva contraseña ha sido guardada";
$_lang['Wrong password'] = "Contraseña incorrecta";
$_lang['Delete poll'] = "Eliminar voto";
$_lang['<h4>Delete forum threads</h4> Here you can delete your own threads<br>Only threads without a comment will appear.'] = "<h4>Eliminar temas del foro</h4> Aquí puede eliminar sus propios temas<br>
Solo temas sin comentarios aparecerán.";

$_lang['Old password'] = "Contraseña anterior";
$_lang['New Password'] = "Nueva contraseña";
$_lang['Retype new password'] = "Repita nueva contraseña";
$_lang['The new password must have 5 letters at least'] = "La nueva contraseña debe tener al menos 5 letras";
$_lang['You didnt repeat the new password correctly'] = "Usted no ha repetido la nueva contraseña correctamente";

$_lang['Show bookings'] = "Mostrar Reservaciones";
$_lang['Valid characters'] = "Caracteres Válidos";
$_lang['Suggestion'] = "Sugerencia";
$_lang['Put the word AND between several phrases'] = "Ponga la palabra AND entre frases"; // translators: please leave the word AND as it is
$_lang['Write access for calendar'] = "Permiso de Escritura para calendario";
$_lang['Write access for other users to your calendar'] = "Permiso de Escritura para otros usuarios a su calendario";
$_lang['User with chief status still have write access'] = "El usuario jege de Grupo tendrá permiso de escritura";

// projects
$_lang['Project Listing'] = "Lista de Proyectos";
$_lang['Project Name'] = "Nombre del Proyecto";


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
$_lang['Participants'] = "Participantes";
$_lang['Priority'] = "Prioridad";
$_lang['Status'] = "Estado";
$_lang['Last status change'] = "Ultimo <br>cambio";
$_lang['Leader'] = "Lider";
$_lang['Statistics'] = "Estadísticas";
$_lang['My Statistic'] = "My Statistic";

$_lang['Person'] = "Persona";
$_lang['Hours'] = "Horas";
$_lang['Project summary'] = "Resumen del Proyecto";
$_lang[' Choose a combination Project/Person'] = "Seleccione una combinación Proyecto/Persona";
$_lang['(multiple select with the Ctrl/Cmd-key)'] = "(selección múltiple con la tecla 'Ctrl')";

$_lang['Persons'] = "Personas";
$_lang['Begin:'] = "Inicio:";
$_lang['End:'] = "Final:";
$_lang['All'] = "Todos";
$_lang['Work time booked on'] = "Tiempo de trabajo asignado en";
$_lang['Sub-Project of'] = "Subproyecto de";
$_lang['Aim'] = "Aim";
$_lang['Contact'] = "Contact";
$_lang['Hourly rate'] = "costo por hora";
$_lang['Calculated budget'] = "presupuesto calculado";
$_lang['New Sub-Project'] = "Nuevo subproyecto";
$_lang['Booked To Date'] = "Reservado hasta ahora";
$_lang['Budget'] = "Presupuesto";
$_lang['Detailed list'] = "Lista Detallada";
$_lang['Gantt'] = "Linea de Tiempo";
$_lang['offered'] = "ofrecido";
$_lang['ordered'] = "ordenado";
$_lang['Working'] = "trabajando";
$_lang['ended'] = "finalizado";
$_lang['stopped'] = "detenido";
$_lang['Re-Opened'] = "reabrir";
$_lang['waiting'] = "esperando";
$_lang['Only main projects'] = "Unicamente proyectos principales";
$_lang['Only this project'] = "Solo este proyecto";
$_lang['Begin > End'] = "Inicio > Fin";
$_lang['ISO-Format: yyyy-mm-dd'] = "Formato-ISO: aaaa-mm-dd";
$_lang['The timespan of this project must be within the timespan of the parent project. Please adjust'] = "El plazo de este proyecto debe estar dentro del plazo del proyecto padre. Por favor ajunste";
$_lang['Please choose at least one person'] = "Por favor seleccione al menos una persona";
$_lang['Please choose at least one project'] = "Por favor seleccione al menos un proyecto";
$_lang['Dependency'] = "Dependencia";
$_lang['Previous'] = "Previo";

$_lang['cannot start before the end of project'] = "imposible iniciar antes del fin del proyecto";
$_lang['cannot start before the start of project'] = "imposible iniciar antes del inicio del proyecto";
$_lang['cannot end before the start of project'] = "imposible terminar antes del inicio del proyecto";
$_lang['cannot end before the end of project'] = "imposible terminar antes del fin del proyecto";
$_lang['Warning, violation of dependency'] = "ATENCION, violación de dependencia";
$_lang['Container'] = "Contenedor";
$_lang['External project'] = "Proyecto Externo";
$_lang['Automatic scaling'] = "Escalamiento Automático";
$_lang['Legend'] = "Leyenda";
$_lang['No value'] = "Sin Valor";
$_lang['Copy project branch'] = "Copiar rama de elemento";
$_lang['Copy this element<br> (and all elements below)'] = "Copiar este elemento<br> (y todos los elementos debajo)";
$_lang['And put it below this element'] = "Y ponerlo debajo de este elemento";
$_lang['Edit timeframe of a project branch'] = "Editar marco de tiempo de una rama de proyecto";

$_lang['of this element<br> (and all elements below)'] = "de este elemento<br> (y todos los elementos debajo)";
$_lang['by'] = "por";
$_lang['Probability'] = "Probabilidad";
$_lang['Please delete all subelements first'] = "Please delete all subprojects first";
$_lang['Assignment'] ="Assignment";
$_lang['display'] = "Display";
$_lang['Normal'] = "Normal";
$_lang['sort by date'] = "Sort by date";
$_lang['sort by'] = "Sort by";
$_lang['Calculated budget has a wrong format'] = "Calculated budget has a wrong format";
$_lang['Hourly rate has a wrong format'] = "Hourly rate has a wrong format";

// r.php
$_lang['please check the status!'] = "por favor verifique el estatus!";
$_lang['Todo List: '] = "Por hacer: ";
$_lang['New Remark: '] = "Nueva observación: ";
$_lang['Delete Remark '] = "Eliminar observación ";
$_lang['Keyword Search'] = "Búsqueda: ";
$_lang['Events'] = "Eventos";
$_lang['the forum'] = "en el foro";
$_lang['the files'] = "en los archivos";
$_lang['Addresses'] = "Direcciones";
$_lang['Extended'] = "Extendido";
$_lang['all modules'] = "todos los módulos";
$_lang['Bookmarks:'] = "Bookmarks:";
$_lang['List'] = "Lista";
$_lang['Projects:'] = "Proyectos:";

$_lang['Deadline'] = "Plazo";

$_lang['Polls:'] = "Votos:";

$_lang['Poll created on the '] = "Votación creada el ";


// reminder.php
$_lang['Starts in'] = "comienza en";
$_lang['minutes'] = "minutos";
$_lang['No events yet today'] = "Aún no existen eventos hoy";
$_lang['New mail arrived'] = "Tiene nuevo Mail";

//ress.php

$_lang['List of Resources'] =  "Lista de Recursos";
$_lang['Name of Resource'] = "Nombre del Recursos";
$_lang['Comments'] =  "Comentarios";


// roles
$_lang['Roles'] = "Roles";
$_lang['No access'] = "Sin Permiso";
$_lang['Read access'] = "Permiso Lectura";

$_lang['Role'] = "Rol";

//helpdesk - rts
$_lang['Request'] = "Pedido";

$_lang['pending requests'] = "pedidos pendientes";
$_lang['show queue'] = "mostrar cola";
$_lang['Search the knowledge database'] = "Buscar en el archivo del conocimiento";
$_lang['Keyword'] = "Palabra clave";
$_lang['show results'] = "mostrar resultados";
$_lang['request form'] = "formulario de pedido";
$_lang['Enter your keyword'] = "ingrese su palabra clave";
$_lang['Enter your email'] = "Ingrese su e-mail";
$_lang['Give your request a name'] = "Asigne un nombre a su pedido";
$_lang['Describe your request'] = "Describa su pedido";

$_lang['Due date'] = "Fecha límite";
$_lang['Days'] = "Días";
$_lang['Sorry, you are not in the list'] = "Disculpe, Ud. no está en la lista";
$_lang['Your request Nr. is'] = "Su Nro. de pedido es";
$_lang['Customer'] = "Cliente";


$_lang['Search'] = "Buscar";
$_lang['at'] = "a";
$_lang['all fields'] = "todos los espacios";


$_lang['Solution'] = "Solución";
$_lang['AND'] = "Y";

$_lang['pending'] = "pendiente";
$_lang['stalled'] = "congelado";
$_lang['moved'] = "movido";
$_lang['solved'] = "solucionado";
$_lang['Submit'] = "Enviar";
$_lang['Ass.'] = "Asig.";
$_lang['Pri.'] = "Prio.";
$_lang['access'] = "acceso";
$_lang['Assigned'] = "Asignado";

$_lang['update'] = "actualizar";
$_lang['remark'] = "dar observación";
$_lang['solve'] = "resolver";
$_lang['stall'] = "congelar";
$_lang['cancel'] = "cancelar";
$_lang['Move to request'] = "Mover a pedido";
$_lang['Dear customer, please refer to the number given above by contacting us.Will will perform your request as soon as possible.'] = "Estimado cliente, por favor recuerde el número asignado arriba cuando nos contacte.
Procesaremos su pedido tan pronto como nos sea posible.";
$_lang['Your request has been added into the request queue.<br>You will receive a confirmation email in some moments.'] = "Su pedido es llevado a la cola de pedidos.<br>
Usted recibirá un email de confirmación en un momento.";
$_lang['n/a'] = "n/d";
$_lang['internal'] = "interno";

$_lang['has reassigned the following request'] = "ha reasignado el siguiente pedido";
$_lang['New request'] = "Nuevo pedido";
$_lang['Assign work time'] = "Asignar tiempo de trabajo a:";
$_lang['Assigned to:'] = "asignado a:";

$_lang['Your solution was mailed to the customer and taken into the database.'] = "La resolucion fue enviada por mail al cliente y guardado en la base de datos.";
$_lang['Answer to your request Nr.'] = "Contestación a su solicitud No.";
$_lang['Fetch new request by mail'] = "Recabar nuevas solicitudes por mail";
$_lang['Your request was solved by'] = "Su solicitud fue resuelta por:";

$_lang['Your solution was mailed to the customer and taken into the database'] = "La solución fue enviada al cliente vía mail y archivada en la base de datos";
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
$_lang['The settings have been modified'] = "La configuración ha sido modificaca";
$_lang['Skin'] = "Skin";
$_lang['First module view on startup'] = "Módulo a mostrarse al Inicio";
$_lang['none'] = "ninguno";
$_lang['Check for mail'] = "Check for new mails";
$_lang['Additional alert box'] = "Additional alert box";
$_lang['Horizontal screen resolution <br>(i.e. 1024, 800)'] = "Pop-Up antes del evento";
$_lang['Chat Entry'] = "Ingrear al Chat";
$_lang['single line'] = "una línea";
$_lang['multi lines'] = "multilíneas";
$_lang['Chat Direction'] = "Dirección del Chat";
$_lang['Newest messages on top'] = "Mensajes nuevos arriba";
$_lang['Newest messages at bottom'] = "Mensajes nuevos abajo";
$_lang['File Downloads'] = "Descargar Archivos";

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
$_lang['Todays Events'] = "Eventos de Hoy";
$_lang['New files'] = "Archivos Nuevos";
$_lang['New notes'] = "Notas Nuevas";
$_lang['New Polls'] = "Nueas Votos";
$_lang['Current projects'] = "Proyectos Actuales";
$_lang['Help Desk Requests'] = "Consultas al Helpdesk";
$_lang['Current todos'] = "Pendientes Actuales";
$_lang['New forum postings'] = "Publiciaciones en Foros";
$_lang['New Mails'] = "Correo Nuevo";

//timecard

$_lang['Theres an error in your time sheet: '] = "Existe un error en su planilla de asistencia! Por favor verifique.";




$_lang['Consistency check'] = "Verificación de consistencia";
$_lang['Please enter the end afterwards at the'] = "Por favor ingrese la salida después de";
$_lang['insert'] = "insertar";
$_lang['Enter records afterwards'] = "Ingrese registros después";
$_lang['Please fill in only emtpy records'] = "Por favor complete solamente los registros vacíos";

$_lang['Insert a period, all records in this period will be assigned to this project'] = "Ingrese un período, todos los registros en este período serán asignados a este proyecto";
$_lang['There is no record on this day'] = "No existen registros para este día";
$_lang['This field is not empty. Please ask the administrator'] = "Este espacio no está vacío. Por favor pregunte al Administrador";
$_lang['There is no open record with a begin time on this day!'] = "Las fechas brindadas están erróneas! Por favor verifíquelas.";
$_lang['Please close the open record on this day first!'] = "Por favor ingrese la hora de entrada primero";
$_lang['Please check the given time'] = "Por favor verifique la hora brindada";
$_lang['Assigning projects'] = "Asignación de proyectos";
$_lang['Select a day'] = "Seleccione un día";
$_lang['Copy to the boss'] = "Copia al jefe";
$_lang['Change in the timecard'] = "Cambio en la tarjeta de asistencia";
$_lang['Sum for'] = "Suma correspondiente a";

$_lang['Unassigned time'] = "Sin asignación de tiempo";
$_lang['delete record of this day'] = "eliminar record de éste día";
$_lang['Bookings'] = "Registros";

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
$_lang['accepted'] = "aceptado";
$_lang['rejected'] = "rechazado";
$_lang['own'] = "propio";
$_lang['progress'] = "progreso";
$_lang['delegated to'] = "delegado a";
$_lang['Assigned from'] = "asignado por";
$_lang['done'] = "hecho";
$_lang['Not yet assigned'] = "Aún no asignado";
$_lang['Undertake'] = "Tomado por";
$_lang['New todo'] = "Nuevo ToDor";
$_lang['Notify recipient'] = "Modificar destinatario";

// votum.php
$_lang['results of the vote: '] = "resultado de la votación: ";
$_lang['Poll Question: '] = "pregunta de la votación: ";
$_lang['several answers possible'] = "es posible varias respuestas";
$_lang['Alternative '] = "Alternativa ";
$_lang['no vote: '] = "sin voto: ";
$_lang['of'] = "de";
$_lang['participants have voted in this poll'] = "participantes ha votado";
$_lang['Current Open Polls'] = "Votaciones Abiertas";
$_lang['Results of Polls'] = "Resultados de todas las Votaciones";
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