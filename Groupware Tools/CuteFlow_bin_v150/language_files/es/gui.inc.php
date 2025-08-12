<?php
$TITLE_1 = "CuteFlow";
$TITLE_2 = "Workflow de documentos y Datos";

$BTN_OK = "OK";
$BTN_CANCEL = "Cancelar";
$BTN_NEXT = "Sigte. >";
$BTN_BACK = "< Anterior";
$BTN_LOGIN = "Login";
$BTN_SAVE = "Enviar";

$BTN_ADD = "< Añadir";

//--- menu.php
$GROUP_LOGOUT = "Salir";
$GROUP_CIRCULATION = "Circulaciones";
$GROUP_ADMINISTRATION = "Administracion";

$MENU_TEMPLATE = "Crear Plantillas";
$MENU_FIELDS = "Crear Campos";
$MENU_ARCHIVE = "Circulac. Finalizadas";
$MENU_USERMNG = "Crear Usuarios";
$MENU_CIRCULATION = "Circulaciones";
$MENU_MAILINGLIST = "Lista distribución";

//--- showuser.php
$USER_MNGT_SHOWRANGE = "Mostrar usuario _%From-_%To";
$USER_MNGT_SORTBY = "Ordenado por:";
$USER_MNGT_SORTBY_NAME = "Nombre";

$USER_MNGT_LASTNAME = "Apellidos";
$USER_MNGT_FIRSTNAME = "Nombre";
$USER_MNGT_EMAIL = "E-Mail";
$USER_MNGT_SUBSTITUDE = "Sustituto";
$USER_MNGT_ADMINACCESS = "Administrador";
$USER_MNGT_ASKDELETE = "Confirmas la baja de este usuario?";
$USER_MNGT_ADDUSER = "Nuevo usuario";

$USER_EDIT_FORM_HEADER = "Datos usuario";
$USER_EDIT_FIRSTNAME = "Nombre:";
$USER_EDIT_LASTNAME = "Apellidos:";
$USER_EDIT_EMAIL = "E-Mail:";
$USER_EDIT_ACCESSLEVEL = "Nivel Acceso:";
$USER_EDIT_USERID = "ID usuario:";
$USER_EDIT_PWD = "Password:";
$USER_EDIT_PWDCHECK = "Password <br/>(repetir):";
$USER_EDIT_SUBSTITUDE = "Sustituto o<br/>Suplente:";
$USER_EDIT_ACTION = "Grabar";

$USER_ACCESSLEVEL_ADMIN =    "Administrador (crear circulaciones)";
$USER_ACCESSLEVEL_RECEIVER = "Receptor      (lectura-escritura)";
$USER_ACCESSLEVEL_READONLY = "Read-Only     (solo lectura)";

$USER_SELECT_FORM_HEADER = "Selecciona usuario";
$USER_SELECT_NO_SELECT = "No hay usuarios seleccionado!";

$USER_TIP_DELETE = "Baja de Usuario";
$USER_TIP_DETAIL = "Editar detos Usuario";

$EDIT_NEW_ERROR_FIRSTNAME = "Falta nombre";
$EDIT_NEW_ERROR_LASTNAME = "Falta apellido";
$EDIT_NEW_ERROR_EMAIL = "E-mail no valido";
$EDIT_NEW_ERROR_PASSWORD1 = "Falta password";
$EDIT_NEW_ERROR_PASSWORD2 = "Re escribe password";
$EDIT_NEW_ERROR_PASSWORD3 = "Los password no coinciden";

//--- showcirculation.php
$CIRCULATION_MNGT_ADDCIRCULATION = "Nueva circulación";
$CIRCULATION_MNGT_FILTER = "Filtro:";
$CIRCULATION_MNGT_NAME = "Nombre";
$CIRCULATION_MNGT_CURRENT_SLOT = "Estacion actual";
$CIRCULATION_MNGT_SENDING_DATE = "Enviado fecha";
$CIRCULATION_MNGT_WORK_IN_PROCESS = "Días en proceso";
$CIRCULATION_MNGT_SHOWRANGE = "Mostrar circulaciones _%From-_%To off _%Off";
$CIRCULATION_MNGT_ASKDELETE = "Confirmas borrar esta circulación?";
$CIRCULATION_MNGT_CIRC_DONE = "Circulación completada";
$CIRCULATION_MNGT_CIRC_BREAK = "Circulación declinada";
$CIRCULATION_MNGT_CIRC_STOP = "Circulation parada";
$CIRCULATION_TIP_STOP = "Parar circulación";
$CIRCULATION_TIP_RESTART = "Reiniciar circulación";
$CIRCULATION_TIP_DELETE = "Borrar Circulación";
$CIRCULATION_TIP_DETAIL = "Ver detalles de la circulación";
$CIRCULATION_TIP_ARCHIVE = "Archivar circulación";
$CIRCULATION_TIP_UNARCHIVE = "Mover circulación de archivo a la lista activa";


//--- circulation_detail.php
$CIRCDETAIL_TEMPLATE_TYPE = "Tipo:";
$CIRCDETAIL_SENDER = "Emsor:";
$CIRCDETAIL_SENDREV = "Revisión (Fecha):";
$CIRCDETAIL_SENDDATE = "Fecha:";
$CIRCDETAIL_ATTACHMENT = "Anexos";
$CIRCDETAIL_HISTORY = "Historia del ciclo";
$CIRCDETAIL_VALUES = "Datos aportados";
$CIRCDETAIL_RECEIVE = "Recibido el:";
$CIRCDETAIL_PROCESS_DURATION = "Días en proceso:";
$CIRCDETAIL_DAYS = "Día(s)";
$CIRCDETAIL_STATE_OK = "hecho";
$CIRCDETAIL_STATE_WAITING = "en proceso";
$CIRCDETAIL_STATE_DENIED = "declinada";
$CIRCDETAIL_STATE_SKIPPED = "omitida";
$CIRCDETAIL_STATE_STOP = "parada";
$CIRCDETAIL_STATE_SUBSTITUTE = "enviar a sustituto";
$CIRCDETAIL_STATE = "Status:";
$CIRCDETAIL_STATION = "Estación:";
$CIRCDETAIL_COMMANDS = "Acciones:";
$CIRCDETAIL_DESCRIPTION = "Descripción:";

$CIRCDETAIL_TIP_SKIP = "Omitir estación";
$CIRCDETAIL_TIP_RETRY = "Reenviar email a la estacion";

$CIRCULATION_EDIT_FORM_HEADER = "Nueva circulación";
$CIRCULATION_EDIT_NAME = "Nombre Circulación:";
$CIRCULATION_EDIT_MAILINGLIST = "Lista destinatarios:";
$CIRCULATION_EDIT_ATTACHMENTS = "Anexos:";
$CIRCULATION_EDIT_ADDITIONAL_TEXT = "Descripción texto:";
$CIRCULATION_EDIT_SUCCESS_MAIL = "Mensaje a enviar si la circulación se completa con éxito";
$CIRCULATION_EDIT_SUCCESS_ARCHIVE = "Archivar circulaciones terminadas automáticamente";

$CIRCULATION_NEW_ERROR_NAME = "Introduzca nuevo nombbre de circulación!";
$CIRCULATION_NEW_ERROR_MAILINGLIST = "Debe seleccionar una lista de destinatarios!";

$CIRCULATION_DONE_MESSSAGE_SUCCESS = "La circulación se completó satisfactoriamente";
$CIRCULATION_DONE_MESSSAGE_REJECT = "La circulación fue rechadara por el receptor";


//--- printbar.php
$PRINTBAR_PRINT = "Imprimir";
$PRINTBAR_CLOSE = "Cerrar";


//--- showfield.php
$FIELD_MNGT_ADDFIELD = "Nuevo Campo";
$FIELD_MNGT_SHOWRANGE = "Mostrar campos _%From-_%To off _%Off";
$FIELD_MNGT_ASKDELETE = "Confirma borrar campo? \\nAtención: El campo se va a borrar en todas las circulaciones\\n(incluidos los datos del campo)";
$FIELD_TBL_HDR_NAME = "Campo";
$FIELD_TBL_HDR_TYPE = "Tipo";
$FIELD_TBL_HDR_STDVALUE = "Valor por defecto";
$FIELD_TBL_HDR_READONLY = "Read-only";

$FIELD_TYPE_TEXT = "Texto";
$FIELD_TYPE_DOUBLE = "Decimal";
$FIELD_TYPE_BOOLEAN = "Si/No";
$FIELD_TYPE_DATE = "Fecha";

$FIELD_TIP_DELETE = "Borrar campo";
$FIELD_TIP_DETAILS = "Editar detalles del campo";

//--- editfield.php
$FIELD_EDIT_HEADLINE = "Entrar campos";
$FIELD_EDIT_NAME = "Nombre campo:";
$FIELD_EDIT_TYPE = "Tipo campo:";
$FIELD_EDIT_STDVALUE = "Valor por defecto:";
$FIELD_EDIT_READONLY = "Read-Only:";
$FIELD_NEW_ERROR_NAME = "Debe entrar un nombre de campo!";

//--- showtemplates
$TEMPLATE_MNGT_ADDTEMPLATE = "Nueva plantilla de Documento";
$TEMPLATE_MNGT_SHOWRANGE = "Mostrar plantilla _%From-_%To off _%Off";
$TEMPLATE_TIP_DETAILS = "Editar plantilla";
$TEMPLATE_TIP_DELETE = "Borrar plantilla";
$TEMPLATE_MNGT_ASKDELETE = "Confirma borrar plantilla? \\nAtención: Todas las circulaciones con esta plantilla serán eliminadas\\n(incluyendo sus datos)";

$TEMPLATE_EDIT1_HEADER = "Plantilla: detalles (Paso 1/3)";
$TEMPLATE_EDIT1_NAME = "Nombre de la Plantilla del Documento:";

$TEMPLATE_EDIT2_HEADER = "Asignar Usuarios/Fases de la plantilla de documento (Paso 2/3):";
$TEMPLATE_EDIT2_NEWSLOT = "Nuevo Usuario/Fase";
$TEMPLATE_EDIT2_ASKDELETE = "Confirma borrar plantilla\\nAtención: Todas las circulaciones que usan esta plantilla perderán los datos asignados!";
$TEMPLATE_EDIT2_HEADER_NAME = "Nombre";
$TEMPLATE_EDIT2_TIP_DELETE = "Borrar plantilla";
$TEMPLATE_EDIT2_TIP_DETAIL = "Editar plantilla";
$TEMPLATE_EDIT2_TIP_UP = "Mover plantilla arriba";
$TEMPLATE_EDIT2_TIP_DOWN = "Mover plantilla abajo";

$TEMPLATE_EDIT3_HEADER = "Asignar Campos a Usuarios/Fases de la plantilla (Paso 3/3)";
$TEMPLATE_EDIT3_ASSIGNED_FIELDS = "Campos asignados:";
$TEMPLATE_EDIT3_AVAILABLE_FIELDS = "Campos disponibles:";
$TEMPLATE_EDIT3_NAME = "Nombre";
$TEMPLATE_EDIT3_POS = "Pos.";

$TEMPLATE_NEW_ERROR_NAME = "Debe introducir un nombre para la plantilla!";

//--- editslot.php
$SLOT_EDIT_HEADLINE = "Detalles Plantilla";
$SLOT_EDIT_NAME = "Nombre plantilla:";
$SLOT_NEW_ERROR_NAME = "Debe introducir un nombre para la plantilla!";


//--- showmaillist.php
$MAILLIST_MNGT_ADDMAILLIST = "Nueva lista distribución";
$MAILLIST_MNGT_SHOWRANGE = "Mostrar lista distribución _%From-_%To off _%Off";
$MAILLIST_MNGT_NAME = "Nombre";
$MAILLIST_MNGT_ASKDELETE = "Confirma borrado lista distribución?";

$MAILLIST_EDIT_ERROR = "La lista está en uso!<br>Cambios en la lista van a afectar a las circulaciones en curso que la utilizan.<br>En el peor caso la circulación no continuará/finalizará correctamente.!";

$MAILLIST_EDIT_FORM_HEADER = "Detalles lista distribución";
$MAILLIST_EDIT_FORM_HEADER_STEP2 = "Asignar receptores a las plantillas";
$MAILLIST_EDIT_FORM_TEMPLATE = "Plantilla:";
$MAILLIST_EDIT_FORM_SLOT = "Fase";

$MAILLIST_NEW_ERROR_NAME = "Entre un nombre para la lista de distribución!";
$MAILLIST_NEW_ERROR_TEMPLATE = "Debe seleccionar una plantilla!";

$MAILINGLIST_SELECT_NO_SELECT = "No hay listas de distribución seleccionadas!";
$MAILINGLIST_SELECT_FORM_HEADER = "Seleccionar lista de distribución";

$MAILINGLIST_TIP_DELETE = "Borrar lista distibución";
$MAILINGLIST_TIP_DETAILS = "Editar lista distibución";

$MAILINGLIST_EDIT_ATTACHED_USER = "Usuario asignado:";
$MAILINGLIST_EDIT_POS = "Pos.";
$MAILINGLIST_EDIT_NAME = "Nombre";
$MAILINGLIST_EDIT_AVAILABLE_USER = "Usuario disponible:";

$TEMPLATE_SELECT_NO_SELECT = "No se ha seleccionado plantilla!";
$TEMPLATE_SELECT_FORM_HEADER = "Seleccione plantilla";

$LOGIN_FAILURE = "El proceso de registro ha fallado. Por favor compruebe la identificación y la contraseña utilizadas.";
$LOGIN_ERROR_PASSWORD = "Por favor entra un contraseña!";
$LOGIN_ERROR_USERID = "Por favor entra una identificación!";

$MAIL_HEADER_PRE = "Circulación: ";
$MAIL_VALUES_HEADER = "Informaciónes aportadas";

$MAIL_ENDACTION_DONE_SUCCESS = " - completada satisfactoriamente";
$MAIL_ENDACTION_DONE_REJECTED = " - completada satisfactoriamente";

$MAIL_CLOSE_WINDOW = "Cerrar ventana";

$MAIL_CONTENT_ATTETION = "Atención!";
$MAIL_CONTENT_ATTETION_TEXT = "Usted ha editado esta circulación y enviado sus datos. El contenido de este correo no puede ser cambiado. Los datos indicados muestran el estado actual de la circulación.";
$MAIL_CONTENT_STOPPED_TEXT = "La circulación ha sido parada por otro usuario. Ud no puede cambiar los datos.";
$MAIL_CONTENT_SENT_ALREADY = "Usted ya ha editado esta circulación y enviado sus datos.";

$MAIL_CONTENT_RADIO_NACK = "Yo declino el contenido de esta circulación!";
$MAIL_CONTENT_RADIO_ACK = "Yo acepto el contenido de esta circulación!";

$MAIL_CONTENT_PRINTVIEW = "Vista preliminar";

$MAIL_ACK = "Sus datos han sido transferidos satisfactoriamente y la plantilla en circulación se ha enviado al siguiente usuario de la lista de distribución<br><br>Por favor cierre el correo.";
$MAIL_NACK = "Su respuesta se ha grabado.<br><br>Por favor cierre el correo.";

//--- login
$LOGIN_HEADLINE = "Entrada al sistema de circulación de documentos";
$LOGIN_USERID = "Usuario";
$LOGIN_PWD = "Password";
$LOGIN_LOGIN = "Entrar";

//--- restarting circulation
$CIRCULATION_RESTART_FORM_HEADER = "Reiniciar la circulación";
?>
