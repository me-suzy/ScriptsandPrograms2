<?php

/******************************************************/
/* CuteFlow Italian Language File                     */
/*                                                    */
/* Translation provided by Nello Galiano from Italy	*/
/* nellogaliano@libero.it					*/
/******************************************************/

$TITLE_1 = "CuteFlow";
$TITLE_2 = "Sistema di circolazione di documenti";

$BTN_OK = "OK";
$BTN_CANCEL = "Annulla";
$BTN_NEXT = "Avanti >";
$BTN_BACK = "< Indietro";
$BTN_LOGIN = "Login";
$BTN_SAVE = "Invia";

$BTN_ADD = "< Aggiungi";

//--- menu.php
$GROUP_LOGOUT = "Esci";
$GROUP_CIRCULATION = "Circolari";
$GROUP_ADMINISTRATION = "Amministrazione";

$MENU_TEMPLATE = "Modelli documento";
$MENU_FIELDS = "Campi";
$MENU_ARCHIVE = "Archivio circolari";
$MENU_USERMNG = "Utente";
$MENU_CIRCULATION = "Circolari";
$MENU_MAILINGLIST = "Mailing list";

//--- showuser.php
$USER_MNGT_SHOWRANGE = "Mostra utenti _%From-_%To";
$USER_MNGT_SORTBY = "Ordina per:";
$USER_MNGT_SORTBY_NAME = "Nome";

$USER_MNGT_LASTNAME = "Cognome";
$USER_MNGT_FIRSTNAME = "Nome";
$USER_MNGT_EMAIL = "E-Mail";
$USER_MNGT_SUBSTITUDE = "Sostituto";
$USER_MNGT_ADMINACCESS = "Amministratore";
$USER_MNGT_ASKDELETE = "Si è sicuri di voler eliminare questo utente?";
$USER_MNGT_ADDUSER = "Nuovo utente";

$USER_EDIT_FORM_HEADER = "Dati utente";
$USER_EDIT_FIRSTNAME = "Nome:";
$USER_EDIT_LASTNAME = "Cognome:";
$USER_EDIT_EMAIL = "E-Mail:";
$USER_EDIT_ACCESSLEVEL = "Livello di accesso:";
$USER_EDIT_USERID = "ID Utente:";
$USER_EDIT_PWD = "Password:";
$USER_EDIT_PWDCHECK = "Password <br/>(conferma):";
$USER_EDIT_SUBSTITUDE = "Sostituto:";
$USER_EDIT_ACTION = "Salva";

$USER_ACCESSLEVEL_ADMIN = "Amministrazione";
$USER_ACCESSLEVEL_RECEIVER = "Ricevente";
$USER_ACCESSLEVEL_READONLY = "Sola lettura";

$USER_SELECT_FORM_HEADER = "Selezionare utente";
$USER_SELECT_NO_SELECT = "Nessun utente selezionato!";

$USER_TIP_DELETE = "Elimina utente";
$USER_TIP_DETAIL = "Modifica dettagli utente";

$EDIT_NEW_ERROR_FIRSTNAME = "Obbligatorio inserire un Nome";
$EDIT_NEW_ERROR_LASTNAME = "Obbligatorio inserire un Cognome";
$EDIT_NEW_ERROR_EMAIL = "Indirizzo e-mail non valido";
$EDIT_NEW_ERROR_PASSWORD1 = "Obbligatorio inserire una password";
$EDIT_NEW_ERROR_PASSWORD2 = "Riscrivere la password";
$EDIT_NEW_ERROR_PASSWORD3 = "Le password non coincidono";

//--- showcirculation.php
$CIRCULATION_MNGT_ADDCIRCULATION = "Nuova circolare";
$CIRCULATION_MNGT_FILTER = "Filtro:";
$CIRCULATION_MNGT_NAME = "Nome";
$CIRCULATION_MNGT_CURRENT_SLOT = "Postazione corrente";
$CIRCULATION_MNGT_SENDING_DATE = "Data invio";
$CIRCULATION_MNGT_WORK_IN_PROCESS = "In elaborazione da giorni";
$CIRCULATION_MNGT_SHOWRANGE = "Mostra circolari _%From-_%To di _%Off";
$CIRCULATION_MNGT_ASKDELETE = "Si è sicuri di voler eliminare questa circolare?";
$CIRCULATION_MNGT_CIRC_DONE = "Trasmissione completata";
$CIRCULATION_MNGT_CIRC_BREAK = "Trasmissione rifiutata";
$CIRCULATION_MNGT_CIRC_STOP = "Trasmissione interrotta";
$CIRCULATION_TIP_STOP = "Interrompi trasmissione";
$CIRCULATION_TIP_RESTART = "Riprendi trasmissione dall'inizio ";
$CIRCULATION_TIP_DELETE = "Elimina circolare";
$CIRCULATION_TIP_DETAIL = "Mostra dettagli circolare";
$CIRCULATION_TIP_ARCHIVE = "Archivia circolare";
$CIRCULATION_TIP_UNARCHIVE = "Sposta circolare dall'archivio alla Lista \"regolare\" ";


//--- circulation_detail.php
$CIRCDETAIL_TEMPLATE_TYPE = "Tipo:";
$CIRCDETAIL_SENDER = "Mittente:";
$CIRCDETAIL_SENDREV = "Revisione (Data):";
$CIRCDETAIL_SENDDATE = "Data:";
$CIRCDETAIL_ATTACHMENT = "Allegati";
$CIRCDETAIL_HISTORY = "Cronologia ciclo";
$CIRCDETAIL_VALUES = "Dati inseriti";
$CIRCDETAIL_RECEIVE = "Ricevuto da:";
$CIRCDETAIL_PROCESS_DURATION = "Durata in giorni:";
$CIRCDETAIL_DAYS = "Giorno(i)";
$CIRCDETAIL_STATE_OK = "fatto";
$CIRCDETAIL_STATE_WAITING = "in elaborazione";
$CIRCDETAIL_STATE_DENIED = "rifiutata";
$CIRCDETAIL_STATE_SKIPPED = "ignorata";
$CIRCDETAIL_STATE_STOP = "fermata";
$CIRCDETAIL_STATE_SUBSTITUTE = "inviata al sostituto";
$CIRCDETAIL_STATE = "Stato:";
$CIRCDETAIL_STATION = "Postazione:";
$CIRCDETAIL_COMMANDS = "Azioni:";
$CIRCDETAIL_DESCRIPTION = "Descrizione:";

$CIRCDETAIL_TIP_SKIP = "Salta postazione";
$CIRCDETAIL_TIP_RETRY = "Reinvia email alla postazione";

$CIRCULATION_EDIT_FORM_HEADER = "Nuova circolare";
$CIRCULATION_EDIT_NAME = "Nome circolare:";
$CIRCULATION_EDIT_MAILINGLIST = "Mailing list:";
$CIRCULATION_EDIT_ATTACHMENTS = "Allegati:";
$CIRCULATION_EDIT_ADDITIONAL_TEXT = "Testo descrizione:";
$CIRCULATION_EDIT_SUCCESS_MAIL = "Messaggio di conferma al mittente al termine della trasmissione";
$CIRCULATION_EDIT_SUCCESS_ARCHIVE = "Archivia automaticamente le circolari trasmesse con successo";

$CIRCULATION_NEW_ERROR_NAME = "Occorre inserire un nome per la circolare!";
$CIRCULATION_NEW_ERROR_MAILINGLIST = "Occorre selezionare una mailing list!";

$CIRCULATION_DONE_MESSSAGE_SUCCESS = "Trasmissione completata con successo";
$CIRCULATION_DONE_MESSSAGE_REJECT = "Trasmissione rifiutata da un ricevente";


//--- printbar.php
$PRINTBAR_PRINT = "Stampa";
$PRINTBAR_CLOSE = "Chiudi";


//--- showfield.php
$FIELD_MNGT_ADDFIELD = "Nuovo campo";
$FIELD_MNGT_SHOWRANGE = "Mostra Campi _%From-_%To di _%Off";
$FIELD_MNGT_ASKDELETE = "Si è certi di voler eliminare questo campo? \\nATTENZIONE: Il campo verrà cancellato da tutte le circolari\\n(compresi i dati inseriti nel campo)";
$FIELD_TBL_HDR_NAME = "Nome";
$FIELD_TBL_HDR_TYPE = "Tipo campo";
$FIELD_TBL_HDR_STDVALUE = "Valore di default";
$FIELD_TBL_HDR_READONLY = "Sola lettura";

$FIELD_TYPE_TEXT = "Testo";
$FIELD_TYPE_DOUBLE = "Decimale";
$FIELD_TYPE_BOOLEAN = "Vero/Falso";
$FIELD_TYPE_DATE = "Data";

$FIELD_TIP_DELETE = "Elimina campo";
$FIELD_TIP_DETAILS = "Modifica dettagli campo";

//--- editfield.php
$FIELD_EDIT_HEADLINE = "Campo di input";
$FIELD_EDIT_NAME = "Nome campo:";
$FIELD_EDIT_TYPE = "Tipo campo:";
$FIELD_EDIT_STDVALUE = "Valore di default:";
$FIELD_EDIT_READONLY = "Sola lettura:";
$FIELD_NEW_ERROR_NAME = "Occorre inserire un nome per il campo!";

//--- showtemplates
$TEMPLATE_MNGT_ADDTEMPLATE = "Nuovo modello di documento";
$TEMPLATE_MNGT_SHOWRANGE = "Mostra modelli _%From-_%To di _%Off";
$TEMPLATE_TIP_DETAILS = "Modifica modello";
$TEMPLATE_TIP_DELETE = "Elimina modello";
$TEMPLATE_MNGT_ASKDELETE = "Si è certi di voler eliminare questo modello? \\nATTENZIONE: Tutte le circolari che usano questo modello verranno eliminate\\n(inclusi i dati)";

$TEMPLATE_EDIT1_HEADER = "Dettagli modello (Step 1/3)";
$TEMPLATE_EDIT1_NAME = "Nome del modello di documento:";

$TEMPLATE_EDIT2_HEADER = "Slot del modello di documento (Step 2/3):";
$TEMPLATE_EDIT2_NEWSLOT = "Nuovo Slot";
$TEMPLATE_EDIT2_ASKDELETE = "Si è certi di voler eliminare questo slot?\\nATTENZIONE: Tutte le circolari che usano questo slot perderanno tutti i dati assegnati a questo slot!";
$TEMPLATE_EDIT2_HEADER_NAME = "Nome";
$TEMPLATE_EDIT2_TIP_DELETE = "Elimina slot";
$TEMPLATE_EDIT2_TIP_DETAIL = "Modifica slot";
$TEMPLATE_EDIT2_TIP_UP = "Sposta slot sopra";
$TEMPLATE_EDIT2_TIP_DOWN = "Sposta slot sotto";

$TEMPLATE_EDIT3_HEADER = "Assegnazione dei campi agli slots (Step 3/3)";
$TEMPLATE_EDIT3_ASSIGNED_FIELDS = "Campi assegnati:";
$TEMPLATE_EDIT3_AVAILABLE_FIELDS = "Campi disponibili:";
$TEMPLATE_EDIT3_NAME = "Nome";
$TEMPLATE_EDIT3_POS = "Pos.";

$TEMPLATE_NEW_ERROR_NAME = "Occorre inserire un nome per il modello di documento!";

//--- editslot.php
$SLOT_EDIT_HEADLINE = "Dettagli Slot";
$SLOT_EDIT_NAME = "Nome Slot:";
$SLOT_NEW_ERROR_NAME = "Occorre inserire un nome per lo slot!";


//--- showmaillist.php
$MAILLIST_MNGT_ADDMAILLIST = "Nuova mailing-list";
$MAILLIST_MNGT_SHOWRANGE = "Mostra mailing-list _%From-_%To di _%Off";
$MAILLIST_MNGT_NAME = "Nome";
$MAILLIST_MNGT_ASKDELETE = "Si è certi di voler eliminare questa lista?";

$MAILLIST_EDIT_ERROR = "La mailing-list selezionata è correntemente in uso!<br>Le modifiche a questa mailing-list influenzeranno le circolari che usano questa mailing-list.<br>Nella peggiore delle ipotesi il processo di trasmissione potrebbe non procedere con successo!";

$MAILLIST_EDIT_FORM_HEADER = "Dettagli Mailing-list";
$MAILLIST_EDIT_FORM_HEADER_STEP2 = "Assegnazione destinatari agli slots";
$MAILLIST_EDIT_FORM_TEMPLATE = "Modello documento:";
$MAILLIST_EDIT_FORM_SLOT = "Slot";

$MAILLIST_NEW_ERROR_NAME = "Occorre inserire un nome per la mailing-list!";
$MAILLIST_NEW_ERROR_TEMPLATE = "Occorre selezionare un modello di documento!";

$MAILINGLIST_SELECT_NO_SELECT = "Nessuna mailing-list selezionata!";
$MAILINGLIST_SELECT_FORM_HEADER = "Selezione mailing-list";

$MAILINGLIST_TIP_DELETE = "ELimina mailing-list";
$MAILINGLIST_TIP_DETAILS = "Modifica mailing-list";

$MAILINGLIST_EDIT_ATTACHED_USER = "Utente assegnato:";
$MAILINGLIST_EDIT_POS = "Pos.";
$MAILINGLIST_EDIT_NAME = "Nome";
$MAILINGLIST_EDIT_AVAILABLE_USER = "Utente disponibile:";

$TEMPLATE_SELECT_NO_SELECT = "Nessun modello di documento selezionato!";
$TEMPLATE_SELECT_FORM_HEADER = "Selezionare un modello di documento";

$LOGIN_FAILURE = "Autenticazione non riuscita. Prego controllare id utente e password.";
$LOGIN_ERROR_PASSWORD = "Inserire una password valida, prego!";
$LOGIN_ERROR_USERID = "Inserire un id utente valido, prego!";

$MAIL_HEADER_PRE = "Circolare: ";
$MAIL_VALUES_HEADER = "Informazioni aggiunte";

$MAIL_ENDACTION_DONE_SUCCESS = " - completata con successo";
$MAIL_ENDACTION_DONE_REJECTED = " - completata con successo";

$MAIL_CLOSE_WINDOW = "Chiudi finestra";

$MAIL_CONTENT_ATTETION = "Attenzione!";
$MAIL_CONTENT_ATTETION_TEXT = "Hai già modificato questa circolare e inviato i tuoi dati. I contenuti di questa mail non possono essere modificati. I valori seguenti mostrano lo stato corrente della circolare.";
$MAIL_CONTENT_STOPPED_TEXT = "La circolare è stata bloccata da un altro utente. Non puoi più cambiare i valori.";
$MAIL_CONTENT_SENT_ALREADY = "Hai già modificato questa circolare e inviato i tuoi dati.";

$MAIL_CONTENT_RADIO_NACK = "Non sono d'accordo con il contenuto di questa circolare!";
$MAIL_CONTENT_RADIO_ACK = "Accetto il contenuto di questa circolare!";

$MAIL_CONTENT_PRINTVIEW = "Visualizza per stampa";

$MAIL_ACK = "I tuoi dati sono stati trasmessi con successo ed il documento trasmesso è stato inviato all'utente successivo nella lista.<br><br>Chiudere l'e-mail, prego.";
$MAIL_NACK = "La tua risposta è stata salvata.<br><br>Prego chiudere l'e-mail.";

//--- login
$LOGIN_HEADLINE = "Autenticazione al sistema di trasmissione documenti";
$LOGIN_USERID = "ID utente";
$LOGIN_PWD = "Password";
$LOGIN_LOGIN = "Login";

//--- restarting circulation
$CIRCULATION_RESTART_FORM_HEADER = "Riprendi dall'inizio la trasmissione";
?>