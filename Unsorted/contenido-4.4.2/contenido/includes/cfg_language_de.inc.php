<?php
/******************************************************************************
Description : Language Strings for "Deutsch"
Copyright   : four for business AG, 2002
Author      : Olaf Niemann
Urls        : www.contenido.de
Create date : 2002-03-02

Remarks     : For Contenido-backend only!!!
*******************************************************************************/

cInclude("includes", "functions.i18n.php");

global $lngArea, $lngLogin, $lngErr, $a_description, $lngAct;

$newslettername = ( isset($newslettername) ) ? $newslettername : '';

$lngLogin["pleaselogin"]                        = "Bitte geben Sie Ihren Benutzernamen und Ihr Paßwort ein.";
$lngLogin["username"]                        = "Benutzername";
$lngLogin["password"]                        = "Paßwort";
$lngLogin["invalidlogin"]                = "Entweder ist Ihr Benutzername oder Ihr Paßwort ungültig.<br>Bitte versuchen Sie es nochmal!";

$lngLogout["thanksforusingcontenido"]           = "Vielen Dank, dass sie Contenido benutzt haben. Bis bald.";
$lngLogout["youareloggedout"]                = "Sie sind jetzt abgemeldet.";
$lngLogout["backtologin1"]                = "Hier kommen Sie wieder zur";
$lngLogout["backtologin2"]                = "Anmeldung";

//Datas for Areas which are not in the Navigation
$lngArea["con_artlist"]                        = " - Artikelliste";
$lngArea["con_editart"]                        = " - Artikel bearbeiten";
$lngArea["lay_edit"]                            = " - Layout bearbeiten";
$lngArea["mod_edit"]                            = " - Modul bearbeiten";
$lngArea["tpl_edit"]                            = " - Template bearbeiten";
$lngArea["news_edit"]                           = " - Newsletter bearbeiten";
$lngArea["news_send"]                           = " - Newsletter versenden";




$lngCon["actionsconf"]                         = "Aktionen";
$lngCon["allarts"]                        = "Alle Artikeln";
$lngCon["artname"]                          = "Artikelnname";
$lngCon["artoff"]                        = "Artikel offline schalten";
$lngCon["arton"]                        = "Artikel online schalten";
$lngCon["artswithoutcategory"]                = "Artikeln ohne Zuordnung";
$lngCon["author"]                            = "Redakteur";
$lngCon["created"]                            = "Datum der Erstellung";
$lngCon["dateend"]                            = "Enddatum";
$lngCon["datestart"]                          = "Startdatum";
$lngCon["defaulttemplate"]                = "Template";
$lngCon["imgagedescription"]                = "Beschreibung";
$lngCon["lastmodified"]                    = "Datum der letzten Änderung";
$lngCon["moduleincontainer"]                = "Modul in Container";
$lngCon["noarts"]                            = "Es gibt keine Artikel.";
$lngCon["noartsinthiscategory"]         = "Es gibt keine Artikel in dieser Kategorie.";
$lngCon["nostartingart"]                 = "Nicht als StartArtikel konfigurieren.";
$lngCon["online"]                            = "Online";
$lngCon["preview"]                        = "Vorschau";
$lngCon["properties"]                    = "Eigenschaften";
$lngCon["startart"]                            = "StartArtikel";
$lngCon["structure"]                            = "Kategorie";
$lngCon["structureandarts"]                = "Kategorie / Artikeln";
$lngCon["summary"]                            = "Zusammenfassung";
$lngCon["redirect"]                        = "Weiterleitung";
$lngCon["redirect_url"]                        = "Weiterleitungsadresse";
$lngCon["template"]                            = "Template";
$lngCon["title"]                            = "Titel";
$lngCon["keywordart"]                       = "Keywords Artikel";
$lngCon["keywordcat"]                        = "Keywords Kategorie";
$lngCon["keywordautoart"]                   = "Automatisch generiert";
$lngCon["unconfigured"]                      = "unkonfiguriert";
$lngCon["dynhead"]                         = "Überschrift";
$lngCon["dyntext"]                         = "Text";
$lngCon["dynimg"]                         = "Bild";
$lngCon["dynlink"]                         = "Link";

$lngCon["actions"]["10"]                 = "Artikeln der gewählten Kategorie";
$lngCon["actions"]["11"]                 = "Liste aller Artikeln";
$lngCon["actions"]["12"]                 = "Liste der Artikeln ohne Zuordnung";



$lngAct["con_edittpl"]["10"]            = i18n("Configure template");
$lngAct["con_editart"]["con_newart"]           = i18n("Create article");
$lngAct["con_editart"]["35"]           = i18n("Configure article");
$lngAct["con_editart"]["con_saveart"]           = i18n("Save article");
$lngAct["con"]["con_lock"] = i18n("Lock article");
$lngAct["con_editcontent"]["15"]        = i18n("Edit article");
$lngAct["con_editart"]["con_edit"]        = i18n("Edit article properties");
$lngAct["con_editcontent"]["con_editart"]        = i18n("Edit article");
$lngAct["con_tplcfg"]["con_edddittemplate"] = i18n("Help");
$lngAct["con"]["con_makecatonline"] = i18n("Make category online");
$lngAct["con"]["con_changetemplate"] = i18n("Change template");
$lngAct["con"]["con_makestart"]                    = i18n("Set start article");
$lngAct["con"]["con_makeonline"]                    = i18n("Make article online");
$lngAct["con"]["con_makepublic"]                    = i18n("Protect category");
$lngAct["con"]["con_deleteart"]                    = i18n("Delete article");
$lngAct["con"]["con_tplcfg_edit"]   = i18n("Edit template configuration");
$lngAct["str"]["str_renamecat"]                    = i18n("Rename category");
$lngAct["str"]["str_newcat"]                    = i18n("New category");
$lngAct["str"]["str_makevisible"]                    = i18n("Set category on- or offline");
$lngAct["str"]["50"]                    = i18n("Disable category");
$lngAct["str"]["str_makepublic"]        = i18n("Protect category");
$lngAct["str"]["front_allow"]        = i18n("Frontend access");
$lngAct["str"]["str_deletecat"]            = i18n("Delete category");
$lngAct["str"]["str_moveupcat"]                    = i18n("Move category up");
$lngAct["str"]["str_movesubtree"]       = i18n("Move category");
$lngAct["str"]["str_newtree"]           = i18n("Create new tree");
$lngAct["str_tplcfg"]["str_tplcfg"]     = i18n("Configure category");
$lngAct["upl"]["upl_mkdir"]             = i18n("Create directory");
$lngAct["upl"]["upl_upload"]            = i18n("Upload files");
$lngAct["upl"]["upl_delete"]            = i18n("Delete files");
$lngAct["upl"]["upl_rmdir"]				= i18n("Remove directory");
$lngAct["lay"]["lay_delete"]                    = i18n("Delete layout");
$lngAct["lay_edit"]["lay_edit"]               = i18n("Modify layout");
$lngAct["lay_edit"]["lay_new"]          = i18n("Create layout");
$lngAct["mod"]["mod_delete"]                    = i18n("Delete module");
$lngAct["mod_edit"]["mod_edit"]               = i18n("Edit module");
$lngAct["mod_edit"]["mod_new"]          = i18n("Create module");
$lngAct["tpl"]["tpl_delete"]            = i18n("Delete template");
$lngAct["tpl_edit"]["tpl_edit"]         = i18n("Edit template");
$lngAct["tpl_edit"]["tpl_new"]          = i18n("Create template");
$lngAct["tpl"]["tpl_duplicate"]         = i18n("Duplicate template");
$lngAct["user_rights"]["10"]            = i18n("Edit rights");
$lngAct["user_overview"]["user_edit"]            = i18n("Edit user");
$lngAct["user"]["user_create"]          = i18n("Create user");
$lngAct["user"]["user_delete"]          = i18n("Delete user");
$lngAct["groups_members"]["group_deletemember"] = i18n("Delete group members");
$lngAct["groups_members"]["group_addmember"] = i18n("Add group members");
$lngAct["groups_overview"]["group_edit"]            = i18n("Edit group");
$lngAct["groups_create"]["group_create"]          = i18n("Create group");
$lngAct["groups"]["group_delete"]          = i18n("Delete group");
$lngAct["stat"]["stat_show"]                   = i18n("Show statistics");
$lngAct["news_rcp"]["news_deleterecipient"]               = i18n("Delete recipient");
$lngAct["news_rcp"]["news_activatedeactivaterecipient"]               = i18n("Activate/Deactivate recipient");
$lngAct["news"]["news_editnewsletter"]                   = i18n("Edit newsletter");
$lngAct["news"]["news_createnewsletter"] = i18n("Create newsletter");
$lngAct["news"]["news_delete"]                  = i18n("Delete newsletter");
$lngAct["news_send"]["news_send"]              = i18n("Send newsletter");
$lngAct["lang"]["lang_deletelanguage"]  = i18n("Delete language");
$lngAct["lang"]["lang_editlanguage"]    = i18n("Edit language");
$lngAct["lang"]["lang_newlanguage"] = i18n("Create language");
$lngAct["lang"]["lang_activatelanguage"] = i18n("Activate language");
$lngAct["lang"]["lang_deactivatelanguage"] = i18n("Deactivate language");
$lngAct["lang"]["lang_renamelanguage"] = i18n("Rename language");

$lngAct["plug"]["10"]                    = i18n("Install/Remove plugins");
$lngAct["upl"]["21"]                    = i18n("Delete file");
$lngAct["upl"]["40"]                    = "Dateien hochladen";
$lngAct["upl"]["31"]                    = "Verzeichnis erstellen";
$lngAct["style"]["style_edit"]          = i18n("Modify style");
$lngAct["style"]["style_create"]        = i18n("Create style");
$lngAct["style"]["style_delete"]        = i18n("Delete style");
$lngAct["js"]["js_edit"]                = i18n("Edit script");
$lngAct["js"]["js_delete"]              = i18n("Delete script");
$lngAct["js"]["js_create"]              = i18n("Create script");

$lngAct["recipients"]["recipients_editrecipient"] = i18n("Edit recipient");
$lngAct["recipients"]["recipients_createrecipient"] = i18n("Create recipient");
$lngAct["recipients"]["recipients_delete"] = i18n("Delete recipient");

$lngAct["mycontenido_settings"]["mycontenido_editself"] = "Eigene MyContenido-Einstellungen bearbeiten";

$lngAct["client_edit"]["client_new"] = i18n("Create client");
$lngAct["client_edit"]["client_edit"] = i18n("Edit client");
$lngAct["client"]["client_delete"] = i18n("Remove client");

$lngAct["logs"]["show_log"] = "Log anzeigen";
$lngAct["login"]["login"] = "Login";

$lngStr["actions"]["10"]                 = "Neuer Baum";
$lngStr["structure"]                      = "Kategorie";
$lngStr["properties"]                          = "Aktionen";
$lngStr["makeinvisible"]                 = "Diese Kategorie offline setzen.";
$lngStr["makevisible"]                         = "Diese Kategorie online setzen.";
$lngStr["protect"]                         = "Diese Kategorie sch&uuml;tzen.";
$lngStr["makepublic"]                         = "Diese Kategorie frei zug&auml;nglich machen.";
$lngStr["moveup"]                         = "Diese Kategorie eins nachoben verschieben.";
$lngStr["tofirstlevel"]                         = "Diese Kategorie in die oberste Ebene umh&auml;ngen.";
$lngStr["movehere"]                         = "Die zuvor ausgew&auml;hlte Kategorie hierher umhaengen.";
$lngStr["movesubtree"]                         = "Diese Kategorie (und den darunterliegenden Teilbaum) umhaengen.";

$lngLay["lay"]["lay_new"]                 = "Neues Layout";
$lngLay["layoutname"]                         = "Name des Layouts";
$lngLay["description"]                         = "Beschreibung";
$lngLay["code"]                                 = "Code";
$lngLay["notemplates"]                         = "Es gibt keine Layouts.";

$lngMod["actions"]["10"]                 = "Neues Modul";
$lngMod["modulename"]                         = "Name des Moduls";
$lngMod["description"]                         = "Beschreibung";
$lngMod["input"]                                 = "Input";
$lngMod["output"]                         = "Output";
$lngMod["nomodules"]                         = "Es gibt keine Module.";
$lngMod["cmsvariables"]                        = "CMS_Variablen";

$lngTpl["actions"]["10"]                               = "Neues Template";
$lngTpl["templatename"]                         = "Name des Templates";
$lngTpl["description"]                         = "Beschreibung";
$lngTpl["container"]                        = "Container";
$lngTpl["notemplates"]                         = "Es gibt keine Templates.";
$lngTpl["layout"]                        = "Layout";


$lngUpl["description"]                            ='Beschreibung';
$lngUpl['action']                                 = 'Aktionen';
$lngUpl['delfolder']                         = 'Verzeichnis löschen';
$lngUpl['delfile']                         = 'Datei löschen';
$lngUpl['directoriesandfiles']                 = 'Verzeichnisse / Dateien';
$lngUpl['opendirectory']                        = 'Verzeichnis öffnen';
$lngUpl['closedirectory']                   = 'Verzeichnis schließen';
$lngUpl['file']                                 = 'Datei';
$lngUpl['fileopen']                         = 'Datei öffnen';
$lngUpl['popupclose']                         = 'Fenster schliessen';
$lngUpl['renamefolder']                         = 'Verzeichnis umbenennen';
$lngUpl['renamefile']                         = 'Datei umbenennen';
$lngUpl['description']                        = 'Beschreibung';
$lngUpl['dirisempty']                        = 'Verzeichnis ist leer';
$lngUpl['upload']                                = 'Dateien hochladen';
$lngUpl['delete']                                = 'löschen';
$lngUpl["filesize"]                          ='Dateigröße';

$lngUser["username"]                        = "Benutzername";
$lngUser["password"]                         = "Paßwort";
$lngUser["level"]                         = "Berechtigungen";
$lngUser["action"]                         = "Aktionen";
$lngUser["create"]                         = "Erstellen";
$lngUser["kill"]                                 = "Löschen";
$lngUser["edit"]                         = "Bearbeiten";
$lngUser["error"]                               = "Fehler";
$lngUser["nopermissiontocreateusers"]           = "Sie haben keine Berechtigung Benutzer zu erstellen.";
$lngUser["pleasefilloutusernameandpassword"] = "Bitte füllen Sie <b>Benutzername</b> und <b>Paßwort</b> aus";
$lngUser["usernamealreadyexists"]        = "Benutzername existiert bereits";
$lngUser["failed"]                        = "<b>Fehlgeschlagen:</b>";
$lngUser["usercreated"]                        = "Benutzer erstellt";
$lngUser["nopermissiontoeditusers"]        = "Sie haben keine Berechtigung Benutzer zu bearbeiten";
$lngUser["passwordchanged1"]                = "Paßwort von";
$lngUser["passwordchanged2"]                = "geändert";
$lngUser["nopermissiontodeleteusers"]        = "Sie haben keine Berechtigung Benutzer zu löschen";
$lngUser["userdeleted"]                        = "Benutzer gelöscht";

$lngLang["language"]                            = "Sprache";
$lngLang["active"]                              = "aktiv";
$lngLang["actions"]                             = "Aktionen";
$lngLang["rename"]                              = "umbenennen";
$lngLang["delete"]                              = "löschen";
$lngLang["notactive"]                           = "deaktiviert";
$lngLang["newlanguage"]                        = "Neue Sprache";


$lngNews["unkonf"]                     = "Unkonfiuriert";
$lngNews["template"]                     = "Template";
$lngNews["newnewsletter"]                = "Neuer Newsletter";
$lngNews["newrecipient"]                        = "Neuer Empfänger";
$lngNews["date"]                                = "Datum";
$lngNews["name"]                               = "Name";
$lngNews["edit"]                                = "Bearbeiten";
$lngNews["delete"]                        = "Löschen";
$lngNews["send"]                                = "Senden";
$lngNews["therearenonewsletters"]        = "Es gibt keine Newsletter";
$lngNews["email"]                        = "E-Mail";
$lngNews["name"]                                = "Name";
$lngNews["state"]                        = "Zustand";
$lngNews["actions"]                        = "Aktionen";
$lngNews["active"]                        = "aktiv";
$lngNews["notactive"]                        = "deaktiviert";
$lngNews["activate"]                        = "Aktivieren";
$lngNews["deactivate"]                        = "Deaktivieren";
$lngNews["therearenorecipients"]                = "Es gibt keine Empfänger";
$lngNews["attachment"]                        = "Dateianhang";
$lngNews["noattachment"]                        = "&nbsp;";
$lngNews["mailcouldnotbesend1"]                = "Mail an ";
$lngNews["mailcouldnotbesend2"]                = " konnte nicht gesendet werden.";
$lngNews["newslettersend1"]                = "Newsletter wurde an ";
$lngNews["newslettersend2"]                = " Empfänger versandt";
$lngNews["welcomesubject"]                = "Danke für den Eintrag in unsere Mailingliste";
$lngNews["welcomemessage"]                = "Hallo $newslettername\n\nSie haben Sich heute in unsere Mailingliste eingetragen.\n\n Wir freuen uns, Sie in unserer Liste begruessen zu duerfen\n und werden Sie von nun an regelmaessig mit aktuellen Informationen versorgen.\n\n\n\n Ansonsten wuenschen wir Ihnen auf unseren Artikeln noch viel Spass!\n\n---------------------------------------------------------------------------\nDies ist eine automatisch generierte eMail. Bitte keine Antwort\nauf diese Nachricht senden.";
$lngNews["goodbyesubject"]                = "Austrag aus der Mailingliste";
$lngNews["goodbyemessage"]                = "Hallo $newslettername\n\nSchade, dass Sie unsere Mailingliste verlassen haben.\n\n---------------------------------------------------------------------------\nDies ist eine automatisch generierte eMail. Bitte keine Antwort\nauf diese Nachricht senden.";
$lngNews["stopsubject"]                        = "Mewsletterempfang wurde ausgeschaltet";
$lngNews["stopmessage"]                        = "Hallo $newslettername\n\nSie haben Sich vorruebergehend aus der Mailingliste ausgetragen.\n Um Sich wieder fuer den Empfang des Newsletters anzumelden, klicken Sie bitte auf den folgenden Link:\nMAIL_GOON\nAustragen koennen Sie sich unter:\nMAIL_UNSUBSCRIBE\n\n---------------------------------------------------------------------------\nDies ist eine automatisch generierte eMail. Bitte keine Antwort\nauf diese Nachricht senden.";
$lngNews["goonsubject"]                        = "Mewsletterempfang wurde eingeschaltet";
$lngNews["goonmessage"]                        = "Hallo $newslettername\n\nWir begruessen Sie wieder als aktiver Teilnehmer unserer Mailingliste.\n\nabmelden: MAIL_UNSUBSCRIBE\nvorruebergehend Paussieren, wegen Urlaub etc.:\nMAIL_STOP\n\n---------------------------------------------------------------------------\nDies ist eine automatisch generierte eMail. Bitte keine Antwort\nauf diese Nachricht senden.";
$lngNews["messagefoot"]                        = "---------------------------------------------------------------------------\nDiese Mail ging an MAIL_NUMBER Mitglieder.\nDies ist eine automatisch generierte eMail. Bitte keine Antwort\nauf diese Nachricht senden. Abmelden koenen Sie Sich unter:\nMAIL_UNSUBSCRIBE\nMöchten Sie den Newsletter nur vorruebergehend abbestellen:\nMAIL_STOP";



$lngStat["structureandarts"]                 = "Kategorie / Artikeln";
$lngStat["numberofarts"]                 = "Anzahl der Artikeln";
$lngStat["total"]                         = "Total";
$lngStat["inthislanguage"]                 = "In dieser Sprache";
$lngStat["sum"]                                = "Summe";

$lngForm["nothing"]                        = "--- ".i18n("None")." ---";
$lngForm["all"]                                = "--- Alles ---";

$lngAll["yes"]                                 = "ja";
$lngAll["no"]                                 = "nein";
$lngAll["default"]                         = "default";
$lngAll["defaultdoesnotexist"]                 = "Kein Default-Template eingestellt";
$lngAll["logout"]                        = "Logout";
$lngAll["back"]                                = "Zur&uumlck";

$mod["font"]                                = "Schriftart";
$mod["errorfont"]                          = "Schriftart für Fehlermeldungen";
$mod["inputformfont"]                          = "Schriftart für die Eingabefelder";
$mod["select"]                                  = "Auswahlm&ouml;glichkeiten";
$mod["number"]                                = "Nummer";
$mod["picforsend"]                        = "Bild für den Sendebutton";

$modLink["click"]                         = "Bitte klicken Sie hier.";

$modNews["inputname"]                        = "Feld für Namen";
$modNews["email"]                        = "E-Mail Adresse";
$modNews["name"]                                = "Name (freiwillig)";
$modNews["subcribe"]                          = "anmelden";
$modNews["unsubcribe"]                        = "abmelden";
$modNews["both"]                                = "beides";
$modNews["headline"]                        = "Stets die neusten Informationen per eMail.";
$modNews["subcribemessage"]                = "Wir haben Ihre Daten in unsere Datenbank aufgenommen.";
$modNews["unsubcribemessage"]                = "Wir haben Sie aus unserem Newsletterverteiler gel&ouml;scht.";
$modNews["stopmessage"]                        = "Der Newsletterempfang wurde deaktiviert.";
$modNews["goonmessage"]                        = "Der Newsletterempfang wurde aktiviert.";

$modLogin["error"]                        = "Logindaten sind nicht korrekt.";
$modLogin["send"]                        = "Login now";
$modLogin["sendout"]                        = "logout";
$modLogin["name"]                        = "Bitte Login-Namen eintragen";
$modLogin["password"]                        = "Bitte Passwort eintragen";
$modLogin["login"]                        = "Bitte klicken um einzuloggen";
$modLogin["logout"]                        = "Bitte klicken um auszuloggen";
$modLogin["picforlogout"]                = "Bild für Logout";

$lngErr["0"]                                 = "Test: Error 0";
//******** first two digits for area, second two for error
// 01xx = Con
$lngErr["0101"]                                = "Bitte wählen Sie einen Stukturpunkt aus unter dem der Artikel gespeichert werden soll";

// 02xx = Str
$lngErr["0201"]                                = "Die zu löschende Kategorie hat noch Unterkategorien. Löschen nicht möglich.";
$lngErr["0202"]                                = "Es gibt noch Artikeln in dieser Kategorie. Löschen nicht möglich.";

// 03xx = Lay
$lngErr["0301"]                                = "Layout wird verwendet. Löschen nicht möglich.";

// 04xx = Mod
$lngErr["0401"]                                = "Modul wird verwendet. Löschen nicht möglich.";

// 05xx = Tpl
$lngErr["0501"]                                = "Template wird verwendet. Löschen nicht möglich.";

// 06xx = Dis
$lngErr["0601"]                                = "Code konnte nicht erzeugt werden, weil noch unkonfigurierte Kategorien vorliegen. Weisen Sie diesen Kategorien ein Template zu und konfigurieren Sie es.";

// 07xx = Upl
$lngErr['0701']                                = 'Datei wird verwendet. Löschen nicht möglich.';
$lngErr['0702']                                = 'Dieses Verzeichnis existiert bereits.';
$lngErr['0703']                                = 'Datei konnte nicht auf den Server geladen werden.';
$lngErr['0704']                                = 'Dieser Name ist nicht g&uuml;ltig.';
$lngErr['0705']                                = 'Dieser Dateityp ist nicht zugelassen.';





// 08xx = Lang
//$lngErr["0801"]                                = "Es existiert bereits eine Sprache mit diesem Namen. Es wurde keine Sprache angelegt.";
$lngErr["0802"]                                = "Es gibt noch Artikeln, die online sind oder Kategorien, die sichtbar sind. Wenn sie diese Sprache bei diesem Mandanten wirklich löschen wollen, dann setzten sie alle Artikeln offline und schalten sie alle Kategorien auf unsichtbar.<br> VORSICHT/BEMERKUNG: Löschen ist nicht rückgängig zu machen.";

// 09xx = Tplinput
$lngErr["0901"]                                = "Input Template wird verwendet. Löschen nicht möglich.";

// 10xx = News
$lngErr["1001"]                                = "Es wurde keine E-Mail Adresse eingegeben.";
$lngErr["1002"]                                = "Die E-Mail Adresse hat nicht das richtige Format.";
$lngErr["1003"]                                = "Diese E-Mail Adresse ist schon registriert.";
$lngErr["1004"]                                = "Konnte E-Mail Adresse nicht finden.";

// CMS_Link Beschreibungen
$a_description["CMS_LINK"]                     = "Link";
$a_description["CMS_LINKDESCR"]                = "Beschreibung";
$a_description["CMS_LINKTARGET"]               = "Zielfenster";

?>
