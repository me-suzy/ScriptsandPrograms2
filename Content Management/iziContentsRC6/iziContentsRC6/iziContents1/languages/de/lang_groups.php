<?php

//  Form Title
$GLOBALS["tFormTitle"] = 'Men&uuml;s verwalten';
$GLOBALS["tFormTitle2"] = 'Men&uuml;-&Uuml;bersetzung verwalten';

//  List Headings
$GLOBALS["tMenuTitle"] = 'Men&uuml;-Titel';
$GLOBALS["tHomepage"] = 'Homepage Men&uuml;';

//  List Functions
$GLOBALS["tMenuFilter"] = 'Men&uuml;filter';
$GLOBALS["tEditMenu"] = 'Men&uuml;punkt &auml;ndern';
$GLOBALS["tAddNewMenu"] = 'Men&uuml;punkt hinzuf&uuml;gen';
$GLOBALS["tViewMenu"] = 'Men&uuml;punkt ansehen';
$GLOBALS["tDeleteMenu"] = 'Men&uuml;punkt l&ouml;schen';
$GLOBALS["tMakeHomepage"] = 'zur Startseite machen';
$GLOBALS["tHomepageSet"] = 'derzeitiges Homepage Men&uuml;';
$GLOBALS["tTranslate"] = 'Men&uuml;punkt &uuml;bersetzen';
$GLOBALS["tViewTranslation"] = '&Uuml;bersetzung ansehen';
$GLOBALS["tEditTranslation"] = '&Uuml;bersetzung &auml;ndern';

//  Form Block Titles
$GLOBALS["thGeneral"] = 'General Reference';
$GLOBALS["thGraphics"] = 'Grafiken';
$GLOBALS["thLinks"] = 'Module und Links';
$GLOBALS["thSequence"] = 'Men&uuml; Reihenfolge';
$GLOBALS["thAccess"] = 'Zugriffs-Sicherheit';

//  Form Field Headings
$GLOBALS["tMenuRef"] = 'Interner Name';
$GLOBALS["tMenuTitle"] = 'Men&uuml; Titel';
$GLOBALS["tParentMenu"] = 'Top-level Eltern-Men&uuml;';
$GLOBALS["tMenuLink"] = 'Men&uuml; Link';
$GLOBALS["tOpenMenuLink"] = 'Öffne Men&uuml;-Link innerhalb Seite';
$GLOBALS["tMenuImage1"] = 'Men&uuml; Bild';
$GLOBALS["tMenuImage2"] = 'Men&uuml; Bild - mouse over';
$GLOBALS["tMenuImage3"] = 'Men&uuml; Bild (ausgew&auml;hlt)';
$GLOBALS["tMenuImage4"] = 'Men&uuml; Bild (ausgew&auml;hlt) - mouse over';
$GLOBALS["tMenuHover"] = 'Men&uuml; hover text';
$GLOBALS["tShowMenu"] = 'Men&uuml; anzeigen';
$GLOBALS["tOrderBy"] = 'Inhalte sortieren nach';
$GLOBALS["tOrderDir"] = 'Sortierrichtung';
$GLOBALS["tMLoginReq"] = 'Login ben&ouml;tigt';
$GLOBALS["tUsergroups"] = 'User Gruppen';
$GLOBALS["tAuthor"] = 'Men&uuml; Inhaber';

//  Form Detail Comments and Help Texts
$GLOBALS["tDetails"] = 'In diesem Formular k&ouml;nnen Sie Men&uuml;gruppen erstellen oder &auml;ndern, um innerhalb Ihrer Inhalte zu navigieren.';
$GLOBALS["hMenuRef"] = 'Dies ist die interne Identifizierung innerhalb von ezContents, um diesen Men&uuml;punkt zu referenzieren. <br />Wenn Sie dieses Feld frei lassen, wird automatisch eine fortlaufende Nummer als Referenz vergeben.';
$GLOBALS["hMenuTitle"] = 'Der Name der Men&uuml;gruppe. Dies wird ein Link zu den Inhalten in diesem Men&uuml;.';
$GLOBALS["hParentMenu"] = 'Das Top-Level Men&uuml; von diesem Men&uuml;.';
$GLOBALS["hMenuLink"] = 'Wenn dieses Feld ausgef&uuml;llt ist, wird ein absoluter Link zum Link im Men&uuml; erstellt, d.h. http:{groups';
$GLOBALS["hOpenMenuLink"] = 'Men&uuml;-Links &ouml;ffnen in einem neuen Browser-Fenster, wenn der Tabellen-Modus (nicht im Frame-Modus) gew&auml;hlt ist.<br />Das Setzen des Hakens wird einen externen Link zwingen, sich innerhalb von ezContents-Inhalten zu &ouml;ffnen. Funktioniert nur korrekt, wenn die externe Script-Seite entsprechend f&uuml;r die Zusammenarbeit mit ezContents programmiert wurde.';
$GLOBALS["hMenuImage1"] = 'Das Bild, das den Men&uuml;titel ersetzen soll. (Es sollte kleiner als die definierte Breite Ihrer linke Spalte sein.)';
$GLOBALS["hMenuImage2"] = 'Das Bild, das angezeigt wird, wenn der Mauscursor &uuml;ber das Men&uuml;bild bewegt wird (Mouse-over-Effekt).<br />(Es sollte kleiner als die definierte Breite Ihrer linke Spalte sein.)';
$GLOBALS["hMenuImage3"] = 'Das Bild f&uuml;r den derzeitig ausgew&auml;hlten Men&uuml;punkt.<br />(Es sollte kleiner als die definierte Breite Ihrer linke Spalte sein.)';
$GLOBALS["hMenuImage4"] = 'Das Bild f&uuml;r den derzeitig ausgew&auml;hlten Men&uuml;punkt, wenn der Mauscursor dar&uuml;ber bewegt wird.<br />(Es sollte kleiner als die definierte Breite Ihrer linke Spalte sein.)';
$GLOBALS["hMenuHover"] = 'Der angezeigte Text, wenn der Cursor &uuml;ber einen Men&uuml;punkt bewegt wird, falls Sie diese M&ouml;glichkeit eingeschaltet haben.';
$GLOBALS["hShowMenu"] = 'Ob das Men&uuml; angezeigt wird oder nicht.';
$GLOBALS["hOrderBy"] = 'W&auml;hlt die Sortierrichtung, in der die Inhalte von diesem Men&uuml; sortiert werden.';
$GLOBALS["hOrderDir"] = 'W&auml;hlt die Richtung f&uuml;r die Sortierung (aufsteigend oder absteigend).';
$GLOBALS["hMLoginReq"] = 'Wenn dieser Haken gesetzt ist, wird ein Login f&uuml;r den Zugriff auf diese Men&uuml;option ben&ouml;tigt.';
$GLOBALS["hUsergroups"] = 'Login ist auf die Mitglieder dieser ausgew&auml;hlten User-Gruppen beschr&auml;nkt.';
$GLOBALS["hAuthor"] = 'Dies ist der  \'Eigent&uuml;mer\' von diesem Men&uuml;. Wenn Abschnitte der Webseite mit Sicherheitsbeschr&auml;nkungen versehen sind, kann nur der Eigent&uuml;mer Punkte zu diesem Men&uuml; hinzuf&uuml;gen.';

//  Error Messages
$GLOBALS["eMenuExists"] = 'Ein Men&uuml; mit diesem Namen ist bereits vorhanden';
$GLOBALS["eInvalidName"] = 'Der Men&uuml; Name enth&auml;lt ung&uuml;ltige Zeichen.';
$GLOBALS["eTitleEmpty"] = 'Der Men&uuml;titel muss eingegeben werden.';

?>
