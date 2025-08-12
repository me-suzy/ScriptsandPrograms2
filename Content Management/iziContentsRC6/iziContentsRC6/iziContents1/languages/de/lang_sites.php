<?php

//  Form Title
$GLOBALS["tFormTitle"] = 'Seitenverwaltung';

//  List Headings
$GLOBALS["tSiteCode"] = 'Seiten-Code';
$GLOBALS["tSiteName"] = 'Seiten-Name';
$GLOBALS["tSiteDescription"] = 'Seitenbeschreibung';
$GLOBALS["tSiteEnabled"] = 'aktiv';

//  List Functions
$GLOBALS["tAddNewSite"] = 'Neue Seite hinzuf&uuml;gen';
$GLOBALS["tViewSite"] = 'Seitendetails ansehen';
$GLOBALS["tEditSite"] = 'Seitendetails &auml;ndern';
$GLOBALS["tDeleteSite"] = 'Seite l&ouml;schen';
$GLOBALS["tReleaseSite"] = 'Diese Seite verf&uuml;gbar machen/nicht verf&uuml;gbar machen';
$GLOBALS["tSelectSite"] = 'Diese Seite zur Verwaltung ausw&auml;hlen';

//  Form Block Titles
$GLOBALS["thSiteGeneral"] = 'Seitendetails';

//  Form Detail Comments and Help Texts
$GLOBALS["tDetails"] = 'In diesem Formlar k&ouml;nnen Sie Unterseiten mit Inhalten in Ihrer ezContents-Seite definieren.';
$GLOBALS["hSiteCode"] = 'Dies ist der Code (Zeichenfolge), der diese Unterseite identifiziert.<br />Es d&uuml;rfen keine Leerzeichen oder Sonderzeichen verwendet werden.<br /><br />Wenn Ihr Apache Webserver korrekt konfiguriert ist, um .htaccess-Dateien in Ihrem ezContents Verzeichnis zu verwenden, dann k&ouml;nnen Leser direkt diese Seite mit einer Url wie http://www.IhrServer.com/ezc_verzeichnis/<Seitecode> aufrufen.';
$GLOBALS["hSiteName"] = 'Ein Name, der f&uuml;r diese Seite in der Liste angezeigt wird (verwendet im [Seiteliste]-Kurzbefehl).';
$GLOBALS["hSiteDescription"] = 'Eine Beschreibung, die in den Listenanzeige f&uuml;r diese Seiten angezeigt werden kann.(verwendet vom [Seitelisten]-Kurzbefehl).';
$GLOBALS["hSiteEnabled"] = 'Ob diese Seite verf&uuml;gbar ist oder nicht.';

//  Error Messages
$GLOBALS["eNoCode"] = 'Sie m&uuml;ssen dieser Seite einen eindeutigen Code geben.';
$GLOBALS["eInvalidCode"] = 'Der Seiten-Code enth&auml;lt ein ung&uuml;ltiges Zeichen';
$GLOBALS["eMasterCode"] = 'Dieser Seiten-Ident-Code wird bereits f&uuml;r die Hauptseite (Master) verwendet.';
$GLOBALS["eCodeInUse"] = 'Dieser Ident-Code wird bereits f&uuml;r eine andere Seite oder Theme verwendet.';
$GLOBALS["eNoName"] = 'Das Feld Seitenname darf nicht leer gelassen werden.';
$GLOBALS["eNoDescription"] = 'Das Feld Seitenbeschreibung darf nicht leer gelassen werden.';

?>
