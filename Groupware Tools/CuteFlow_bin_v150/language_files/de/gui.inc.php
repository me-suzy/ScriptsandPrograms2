<?php
$TITLE_1 = "CuteFlow";
$TITLE_2 = "Dokumentenumlauf";

$BTN_OK = "OK";
$BTN_CANCEL = "Abbrechen";
$BTN_NEXT = "Weiter >";
$BTN_BACK = "< Zurück";
$BTN_LOGIN = "Anmelden";
$BTN_SAVE = "Abschicken";

$BTN_ADD = "< Hinzufügen";

//--- menu.php
$GROUP_LOGOUT = "Abmelden";
$GROUP_CIRCULATION = "Umläufe";
$GROUP_ADMINISTRATION = "Verwaltung";

$MENU_LOGOUT = "Abmelden";
$MENU_TEMPLATE = "Dokumentenvorlagen";
$MENU_FIELDS = "Felder";
$MENU_ARCHIVE = "Umlaufarchiv";
$MENU_USERMNG = "Benutzer";
$MENU_CIRCULATION = "Dokumentenumläufe";
$MENU_MAILINGLIST = "Verteiler";

//--- showuser.php
$USER_MNGT_SHOWRANGE = "Anzeige Benutzer _%From-_%To";
$USER_MNGT_SORTBY = "Sortierung nach:";
$USER_MNGT_SORTBY_NAME = "Name";

$USER_MNGT_LASTNAME = "Name";
$USER_MNGT_FIRSTNAME = "Vorname";
$USER_MNGT_EMAIL = "E-Mail";
$USER_MNGT_SUBSTITUDE = "Stellvertreter";
$USER_MNGT_ADMINACCESS = "Administrator";
$USER_MNGT_ASKDELETE = "Wollen Sie diesen Benutzer wirklich löschen?";
$USER_MNGT_ADDUSER = "Neuer Benutzer";

$USER_EDIT_FORM_HEADER = "Benutzerdaten";
$USER_EDIT_FIRSTNAME = "Vorname:";
$USER_EDIT_LASTNAME = "Nachname:";
$USER_EDIT_EMAIL = "E-Mail:";
$USER_EDIT_ACCESSLEVEL = "Zugriffsrechte:";
$USER_EDIT_USERID = "Benutzername:";
$USER_EDIT_PWD = "Passwort:";
$USER_EDIT_PWDCHECK = "Passwort <br/>(Wiederholung):";
$USER_EDIT_SUBSTITUDE = "Stellvertreter:";
$USER_EDIT_ACTION = "Eintragen";

$USER_ACCESSLEVEL_ADMIN = "Administration";
$USER_ACCESSLEVEL_RECEIVER = "Empfänger";
$USER_ACCESSLEVEL_READONLY = "Nur-Lesen";

$USER_SELECT_FORM_HEADER = "Benutzer auswählen";
$USER_SELECT_NO_SELECT = "Kein Benutzer ausgewählt!";

$USER_TIP_DELETE = "Benutzer löschen";
$USER_TIP_DETAIL = "Benutzerdetails ändern";

$EDIT_NEW_ERROR_FIRSTNAME = "Ein Vorname muss eingegeben werden";
$EDIT_NEW_ERROR_LASTNAME = "Ein Nachname muss eingegeben werden";
$EDIT_NEW_ERROR_EMAIL = "Die E-Mail-Adresse ist nicht gültig";
$EDIT_NEW_ERROR_PASSWORD1 = "Ein Passwort muss eingegeben werden";
$EDIT_NEW_ERROR_PASSWORD2 = "Das Passwort muss bestätigt werden";
$EDIT_NEW_ERROR_PASSWORD3 = "Die Passwörter stimmen nicht überein";

//--- showcirculation.php
$CIRCULATION_MNGT_ADDCIRCULATION = "Neuer Umlauf";
$CIRCULATION_MNGT_FILTER = "Filter:";
$CIRCULATION_MNGT_NAME = "Name";
$CIRCULATION_MNGT_CURRENT_SLOT = "Aktuelle Station";
$CIRCULATION_MNGT_WORK_IN_PROCESS = "Tage in Bearbeitung";
$CIRCULATION_MNGT_SENDING_DATE = "Versandt am";
$CIRCULATION_MNGT_SHOWRANGE = "Anzeige Umlauf _%From-_%To von _%Off";
$CIRCULATION_MNGT_ASKDELETE = "Wollen Sie diesen Umlauf wirklich löschen?";
$CIRCULATION_MNGT_CIRC_DONE = "Umlauf abgeschlossen";
$CIRCULATION_MNGT_CIRC_BREAK = "Umlauf abgelehnt";
$CIRCULATION_MNGT_CIRC_STOP = "Umlauf gestoppt";
$CIRCULATION_TIP_STOP = "Umlauf stoppen";
$CIRCULATION_TIP_RESTART = "Umlauf erneut starten";
$CIRCULATION_TIP_DELETE = "Umlauf löschen";
$CIRCULATION_TIP_DETAIL = "Umlaufdetails anzeigen";
$CIRCULATION_TIP_ARCHIVE = "Umlauf archivieren";
$CIRCULATION_TIP_UNARCHIVE = "Umlauf von Archiv in \"normale\" Liste übernehmen";

$CIRCULATION_DONE_MESSSAGE_SUCCESS = "Der Umlauf wurde erfolgreich durchlaufen";
$CIRCULATION_DONE_MESSSAGE_REJECT = "Der Umlauf wurde durch einen Empfänger abgelehnt";

//--- circulation_detail.php
$CIRCDETAIL_TEMPLATE_TYPE = "Typ:";
$CIRCDETAIL_SENDER = "Versender:";
$CIRCDETAIL_SENDREV = "Revision (Datum):";
$CIRCDETAIL_SENDDATE = "Datum:";
$CIRCDETAIL_ATTACHMENT = "Anhänge";
$CIRCDETAIL_HISTORY = "Durchlaufhistorie";
$CIRCDETAIL_VALUES = "Eingetragene Werte";
$CIRCDETAIL_RECEIVE = "Erhalten am:";
$CIRCDETAIL_PROCESS_DURATION = "Bearbeitungsdauer:";
$CIRCDETAIL_DAYS = "Tag(e)";
$CIRCDETAIL_STATE_OK = "bearbeitet";
$CIRCDETAIL_STATE_WAITING = "in Bearbeitung";
$CIRCDETAIL_STATE_DENIED = "abgelehnt";
$CIRCDETAIL_STATE_SKIPPED = "übersprungen";
$CIRCDETAIL_STATE_STOP = "gestoppt";
$CIRCDETAIL_STATE_SUBSTITUTE = "an Stellvertreter verschickt";
$CIRCDETAIL_STATE = "Status:";
$CIRCDETAIL_STATION = "Station:";
$CIRCDETAIL_COMMANDS = "Aktionen:";
$CIRCDETAIL_DESCRIPTION = "Beschreibung:";

$CIRCDETAIL_TIP_SKIP = "Station überspringen";
$CIRCDETAIL_TIP_RETRY = "EMail erneut an Station versenden";

$CIRCULATION_EDIT_FORM_HEADER = "Neuer Umlauf";
$CIRCULATION_EDIT_NAME = "Umlaufname:";
$CIRCULATION_EDIT_MAILINGLIST = "Verteiler:";
$CIRCULATION_EDIT_ATTACHMENTS = "Anhänge:";
$CIRCULATION_EDIT_ADDITIONAL_TEXT = "Einleitender Text:";
$CIRCULATION_EDIT_SUCCESS_MAIL = "Nach erfolgreichem Umlauf E-Mail an Sender";
$CIRCULATION_EDIT_SUCCESS_ARCHIVE = "Nach erfolgreichem Umlauf archivieren";

$CIRCULATION_NEW_ERROR_NAME = "Ein Name für den Umlauf muss angegeben sein!";
$CIRCULATION_NEW_ERROR_MAILINGLIST = "Ein Verteiler muss ausgewählt werden!";

//--- printbar.php
$PRINTBAR_PRINT = "Drucken";
$PRINTBAR_CLOSE = "Schließen";


//--- showfield.php
$FIELD_MNGT_ADDFIELD = "Neues Feld";
$FIELD_MNGT_SHOWRANGE = "Anzeige Feld _%From-_%To von _%Off";
$FIELD_MNGT_ASKDELETE = "Wollen Sie dieses Feld wirklich löschen? \\nACHTUNG: Das Feld wird in allen Umläufen gelöscht \\n(auch bereits eingegebene Daten)";
$FIELD_TBL_HDR_NAME = "Name";
$FIELD_TBL_HDR_TYPE = "Feldtyp";
$FIELD_TBL_HDR_STDVALUE = "Standardwert";
$FIELD_TBL_HDR_READONLY = "Schreibgeschützt";

$FIELD_TYPE_TEXT = "Text";
$FIELD_TYPE_DOUBLE = "Zahl";
$FIELD_TYPE_BOOLEAN = "Ja/Nein";
$FIELD_TYPE_DATE = "Datum";

$FIELD_TIP_DELETE = "Feld löschen";
$FIELD_TIP_DETAILS = "Felddetails bearbeiten";

//--- editfield.php
$FIELD_EDIT_HEADLINE = "Eingabefeld";
$FIELD_EDIT_NAME = "Feldname:";
$FIELD_EDIT_TYPE = "Feldtyp:";
$FIELD_EDIT_STDVALUE = "Standardwert:";
$FIELD_EDIT_READONLY = "Schreibgeschützt:";
$FIELD_NEW_ERROR_NAME = "Ein Name muss angegeben sein!";

//--- showtemplates
$TEMPLATE_MNGT_ADDTEMPLATE = "Neue Dokumentenvorlage";
$TEMPLATE_MNGT_SHOWRANGE = "Anzeige Dokumentenvorlage _%From-_%To von _%Off";
$TEMPLATE_TIP_DETAILS = "Dokumentenvorlage bearbeiten";
$TEMPLATE_TIP_DELETE = "Dokumentenvorlage löschen";
$TEMPLATE_MNGT_ASKDELETE = "Wollen Sie diese Dokumentenvorlage wirklich löschen? \\nACHTUNG: Alle Umläufe mit dieser Dokumentenvorlage werden ebenfalls gelöscht\\n(auch bereits eingegebene Daten)";

$TEMPLATE_EDIT1_HEADER = "Eigenschaften Dokumentenvorlage (Schritt 1/3)";
$TEMPLATE_EDIT1_NAME = "Name der Dokumentenvolage:";

$TEMPLATE_EDIT2_HEADER = "Slots der Dokumentenvolage (Schritt 2/3):";
$TEMPLATE_EDIT2_NEWSLOT = "Neuer Slot";
$TEMPLATE_EDIT2_ASKDELETE = "Wollen Sie diesen Slot wirklich löschen \\nACHTUNG: Alle Daten dieses Slots werden bei Umläufe, die diesen Slot verwenden, gelöscht!";
$TEMPLATE_EDIT2_HEADER_NAME = "Name";
$TEMPLATE_EDIT2_TIP_DELETE = "Slot löschen";
$TEMPLATE_EDIT2_TIP_DETAIL = "Slot bearbeiten";
$TEMPLATE_EDIT2_TIP_UP = "Slot eine Position nach oben verschieben";
$TEMPLATE_EDIT2_TIP_DOWN = "Slot eine Position nach unten verschieben";

$TEMPLATE_EDIT3_HEADER = "Zuordnung von Feldern zu Slots (Schritt 3/3)";
$TEMPLATE_EDIT3_ASSIGNED_FIELDS = "Zugeordnete Felder:";
$TEMPLATE_EDIT3_AVAILABLE_FIELDS = "Verfügbare Felder:";
$TEMPLATE_EDIT3_NAME = "Name";
$TEMPLATE_EDIT3_POS = "Pos.";

$TEMPLATE_NEW_ERROR_NAME = "Ein Name für die Dokumentenvorlage muss festgelegt werden!";

//--- editslot.php
$SLOT_EDIT_HEADLINE = "Sloteigenschaften";
$SLOT_EDIT_NAME = "Slotname:";
$SLOT_NEW_ERROR_NAME = "Ein Name für den Slot muss festgelegt werden!";


//--- showmaillist.php
$MAILLIST_MNGT_ADDMAILLIST = "Neuer Verteiler";
$MAILLIST_MNGT_SHOWRANGE = "Anzeige Verteiler _%From-_%To von _%Off";
$MAILLIST_MNGT_NAME = "Name";
$MAILLIST_MNGT_ASKDELETE = "Wollen Sie diesen Verteiler wirklich löschen?";

$MAILLIST_EDIT_ERROR = "Der Ausgewählte Verteiler ist im Moment in Verwendung!<br>Wenn Sie Änderungen daran vornehmen, betrifft das auch im Umlauf befindliche Dokumente. <br>Im Extremfall kann dies dazu führen, dass ein Umlauf nicht korrekt fortgeführt wird!";

$MAILLIST_EDIT_FORM_HEADER = "Verteilerdaten";
$MAILLIST_EDIT_FORM_HEADER_STEP2 = "Zuordnung von Empfängern zu Slots";
$MAILLIST_EDIT_FORM_TEMPLATE = "Dokumentenvorlage:";
$MAILLIST_EDIT_FORM_SLOT = "Slot";

$MAILLIST_NEW_ERROR_NAME = "Ein Name für den Verteiler muss festgelegt werden!";
$MAILLIST_NEW_ERROR_TEMPLATE = "Eine Dokumentenvorlage muss ausgewählt sein!";

$MAILINGLIST_SELECT_NO_SELECT = "Kein Verteiler ausgewählt!";
$MAILINGLIST_SELECT_FORM_HEADER = "Verteiler auswählen";

$MAILINGLIST_TIP_DELETE = "Verteiler löschen";
$MAILINGLIST_TIP_DETAILS = "Verteilerdetails ändern";

$MAILINGLIST_EDIT_ATTACHED_USER = "Zugeordnete Benutzer:";
$MAILINGLIST_EDIT_POS = "Pos.";
$MAILINGLIST_EDIT_NAME = "Name";
$MAILINGLIST_EDIT_AVAILABLE_USER = "Verfügbare Benutzer:";

$TEMPLATE_SELECT_NO_SELECT = "Keine Dokumentenvorlage ausgewählt!";
$TEMPLATE_SELECT_FORM_HEADER = "Dokumentenvorlage auswählen";

$LOGIN_FAILURE = "Die Anmeldung ist fehlgeschlagen. Bitte überprüfen Sie Ihr Passwort und Benutzernamen.";
$LOGIN_ERROR_PASSWORD = "Bitte ein gültiges Passwort eingeben!";
$LOGIN_ERROR_USERID = "Bitte einen gültigen Benutzernamen eingeben!";

$MAIL_HEADER_PRE = "Umlauf: ";
$MAIL_VALUES_HEADER = "Zusätzliche auszufüllende Informationen";

$MAIL_ENDACTION_DONE_SUCCESS = " - erfolgreich beendet";
$MAIL_ENDACTION_DONE_REJECT = " - abgelehnt";

$MAIL_CLOSE_WINDOW = "Fenster schließen";

$MAIL_CONTENT_ATTETION = "Achtung!";
$MAIL_CONTENT_ATTETION_TEXT = "Sie haben die Daten bereits bearbeitet. Die Inhalte können daher nicht mehr von Ihnen verändert werden. Die untenstehenden Werte sind die aktuellen Inhalte des Umlaufes.";
$MAIL_CONTENT_STOPPED_TEXT = "Dieser Umlauf wurde von einem anderen Benutzer gestoppt. Sie können daher keine Werte mehr ändern.";
$MAIL_CONTENT_SENT_ALREADY = "Sie haben die Daten bereits bearbeitet. Die Inhalte können daher nicht mehr von Ihnen verändert werden.";

$MAIL_CONTENT_RADIO_NACK = "Ich lehne die Inhalte dieses Umlaufes ab!";
$MAIL_CONTENT_RADIO_ACK = "Ich stimme den Inhalten dieses Umlaufes zu!";

$MAIL_CONTENT_PRINTVIEW = "Druckansicht";

$MAIL_ACK = "Ihre Daten wurde erfolgreich übermittelt und der Umlauf an den nächsten in der Liste weitergeleitet.<br><br>Bitte schließen Sie die E-Mail.";
$MAIL_NACK = "Ihre Antwort wurde gespeichert.<br><br>Bitte schließen Sie die E-Mail.";


//--- login
$LOGIN_HEADLINE = "Login zum Dokumentenumlaufsystem";
$LOGIN_USERID = "Benutzername";
$LOGIN_PWD = "Passwort";
$LOGIN_LOGIN = "Anmelden";

//--- restarting circulation
$CIRCULATION_RESTART_FORM_HEADER = "Umlauf erneut starten";