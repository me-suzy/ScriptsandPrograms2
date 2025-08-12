<?php

//  Form Title
$GLOBALS["tFormTitle"] = 'Themes verwalten';

//  List Headings
$GLOBALS["tThemeCode"] = 'Theme Code';
$GLOBALS["tThemeName"] = 'Theme Name';
$GLOBALS["tThemeDescription"] = 'Theme Beschreibung';
$GLOBALS["tThemeEnabled"] = 'eingeschaltet';

//  List Functions
$GLOBALS["tAddNewTheme"] = 'Neues theme hinzuf&uuml;gen';
$GLOBALS["tViewTheme"] = 'theme Details ansehen';
$GLOBALS["tEditTheme"] = 'theme Details &auml;ndern';
$GLOBALS["tDeleteTheme"] = 'theme l&ouml;schen';
$GLOBALS["tReleaseTheme"] = 'einschalten/ausschalten von diesem theme';
$GLOBALS["tSelectTheme"] = 'W&auml;hlen Sie hier das theme f&uuml;r die Verwaltung';

//  Form Block Titles
$GLOBALS["thThemeGeneral"] = 'Theme Details';

//  Form Detail Comments and Help Texts
$GLOBALS["tDetails"] = 'In diesem Formular k&ouml;nnen Sie themes f&uuml;r Ihre ezContents-Seite definieren.';
$GLOBALS["hThemeCode"] = 'Dies ist die eindeutige Bezeichnung, dass dieses theme identifiziert.<br />Es d&uuml;rfen keine Leerzeichen oder Sonderzeichen verwendet werden.<br /><br />Wenn Ihr Apache Webserver korrekt konfiguriert ist, um .htaccess Dateien in Ihrem ezContents-Verzeichnis zu verwenden, kann k&ouml;nnen Leser dieses theme direkt aufrufen mit einer Url in dem Format: http://www.IhrServer.com/ezc_verzeichnis/<themecode>.';
$GLOBALS["hThemeName"] = 'Ein Name, der in der Liste f&uuml;r dieses theme angezeigt wird (verwendet von dem [themelist] Befehl).';
$GLOBALS["hThemeDescription"] = 'Eine Beschreibung, die in der Liste f&uuml;r dieses theme angezeigt wird (verwendet von dem [themelist] Befehl).';
$GLOBALS["hThemeEnabled"] = 'Ob dieses theme eingeschaltet ist oder nicht.';

//  Error Messages
$GLOBALS["eNoCode"] = 'Sie m&uuml;ssen diesem theme einen eindeutige Bezeichnung geben.';
$GLOBALS["eInvalidCode"] = 'Der Theme-Bezeichner  enth&auml;lt ung&uuml;ltige Zeichen, bitte keine Leer- oder Sonderzeichen verwenden';
$GLOBALS["eMasterCode"] = 'Dieser theme-Bezeichner wird bereits f&uuml;r das Haupt-theme verwendet.';
$GLOBALS["eCodeInUse"] = 'Dieser Bezeichner ist bereits f&uuml;r ein anderes theme oder eine Seite in Verwendung.';
$GLOBALS["eNoName"] = 'Der Theme-Name muss ausgef&uuml;llt werden.';
$GLOBALS["eNoDescription"] = 'Die Theme-Beschreibung muss ausgef&uuml;llt werden.';

?>
