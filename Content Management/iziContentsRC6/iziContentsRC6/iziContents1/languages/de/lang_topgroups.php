<?php

//  Form Title
$GLOBALS["tFormTitle"] = 'Kopfmen&uuml; verwalten';
$GLOBALS["tFormTitle2"] = 'Kopfmen&uuml; &Uuml;bersetzung verwalten';

//  List Headings
$GLOBALS["tMenuTitle"] = 'Men&uuml;titel';
$GLOBALS["tHomepage"] = 'Homepage Men&uuml;';

//  List Functions
$GLOBALS["tAddNewMenu"] = 'Neuen Men&uuml;punkt hinzuf&uuml;gen';
$GLOBALS["tViewMenu"] = 'Men&uuml;punkt ansehen';
$GLOBALS["tEditMenu"] = 'Men&uuml;punkt &auml;ndern';
$GLOBALS["tDeleteMenu"] = 'Men&uuml;punkt l&ouml;schen';
$GLOBALS["tMakeHomepage"] = 'zur Startseite machen';
$GLOBALS["tHomepageSet"] = 'derzeitiges Homepage Men&uuml;';
$GLOBALS["tTranslate"] = 'Men&uuml;punkt &uuml;bersetzen';
$GLOBALS["tViewTranslation"] = '&Uuml;bersetzung ansehen';
$GLOBALS["tEditTranslation"] = '&Uuml;bersetzung &auml;ndern';

//  Form Block Titles
$GLOBALS["thGeneral"] = 'Generelle Referenz';
$GLOBALS["thGraphics"] = 'Grafiken';
$GLOBALS["thLinks"] = 'Module und Links';
$GLOBALS["thSequence"] = 'Men&uuml;reihenfolge';
$GLOBALS["thAccess"] = 'Zugangssicherheit';

//  Form Field Headings
$GLOBALS["tMenuRef"] = 'Interner Name';
$GLOBALS["tMenuTitle"] = 'Men&uuml;titel';
$GLOBALS["tParentMenu"] = 'Obermen&uuml;';
$GLOBALS["tMenuLink"] = 'Men&uuml; Link';
$GLOBALS["tOpenMenuLink"] = 'Men&uuml;link innerhalb der Seite &ouml;ffnen';
$GLOBALS["tMenuImage1"] = 'Men&uuml;bild';
$GLOBALS["tMenuImage2"] = 'Men&uuml;bild - mouse over';
$GLOBALS["tMenuImage3"] = 'Men&uuml;bild (ausgew&auml;hlt)';
$GLOBALS["tMenuImage4"] = 'Men&uuml;bild (ausgew&auml;hlt) - mouse over';
$GLOBALS["tMenuHover"] = 'Men&uuml; Alternativtext';
$GLOBALS["tShowMenu"] = 'Men&uuml; zeigen';
$GLOBALS["tOrderBy"] = 'ordnen nach';
$GLOBALS["tOrderDir"] = 'Sortierreihenfolge';
$GLOBALS["tMLoginReq"] = 'Login ben&ouml;tigt';
$GLOBALS["tUsergroups"] = 'User Gruppen';
$GLOBALS["tAuthor"] = 'Men&uuml;-Eigent&uuml;mer';

//  Form Text Description
$GLOBALS["tDetails"] = 'In diesem Formular k&ouml;nnen Sie das Kopfmen&uuml;, welches waagrecht unter Ihrem Logo angezeigt wird, erstellen und &auml;ndern. Damit steuern Sie die Kopf-Navigation auf Ihrer Seite.';
$GLOBALS["hMenuRef"] = 'Dies ist der interne Bezeichner, der von ezContents verwendet wird, um auf diesen Men&uuml;punkt zu referenzieren.<br />Wenn Sie dieses Feld frei lassen, wird automatisch eine fortlaufende Nummer als Referenz vergeben.';
$GLOBALS["hMenuTitle"] = 'Der name der Men&uuml;gruppe. Dies wird ein Link zu den Inhalten dieses Men&uuml;s.';
$GLOBALS["hMenuLink"] = 'Wenn dieses Feld ausgef&uuml;llt ist, wird ein absoluter Link zu einem Inhalt der Seite erstellt, z.B. http://www.altavista.com wird die Altaviste-Seite in den Inhaltsframe von ezContents laden. Es wird nicht m&ouml;glich sein, anderen Inhalt mit dieser Men&uuml;gruppe zu verkn&uuml;pfen.<br />Auf diese Art k&ouml;nnen Sie benutzerdefinierte Links auf eigene, vorhandene Inhalte erstellen.<br /><br />ezContents enth&auml;lt auch einige Plug-In-Module, die Sie hier verwenden k&ouml;nnen, z.B. \"Suche\", \"Neues\" oder andere Add-Ins.<ul><li>F&uuml;r eine \"Suche\" Seite erstellen Sie diesen Link: modules/search/search.php<li>F&uuml;r die \"Neues\" Seite heisst der Link: modules/whatsnew/whatsnew.php</ul>';
$GLOBALS["hOpenMenuLink"] = 'Die Men&uuml;links &ouml;ffnen innerhalb eines neuen Browser-Fensters, wenn ezContents f&uuml;r den framelosen Einsatz konfiguriert ist.<br />Das Setzen dieser Markierung wird einen externen Link zwingen, sich innerhalb des ezContents-Rahmens zu &ouml;ffnen. Dies wird aber nur korrekt erfolgen, wenn die externe Script-Seite f&uuml;r die Zusammenarbeit mit ezContents programmiert wurde.';
$GLOBALS["hMenuImage1"] = 'Das Bild, dass den Men&uuml;titel ersetzen soll.';
$GLOBALS["hMenuImage2"] = 'Das Bild, das gezeigt wirde, wenn der Mauszeiger &uuml;ber das Men&uuml;bild bewegt wird (Mouse-over-Effekt).';
$GLOBALS["hMenuImage3"] = 'Das Bild, das angezeigt werden soll, wenn dieser Men&uuml;punkt gerade ausgew&auml;hlt ist.';
$GLOBALS["hMenuImage4"] = 'Das Bild, das angezeigt werden soll, wenn dieser Men&uuml;punkt gerade ausgew&auml;hlt ist und ausserdem der Mauszeiger &uuml;ber das Bild bewegt wird.';
$GLOBALS["hMenuHover"] = 'Der Text, der angezeigt werden soll, wenn der Mauszeiger &uuml;ber dem Men&uuml;punkt schwebt. (Wenn diese M&ouml;glichkeit eingeschaltet ist).';
$GLOBALS["hShowMenu"] = 'Ob das Men&uuml; sichtbar ist oder nicht.';
$GLOBALS["hOrderBy"] = 'Hier wird die Reihenfolge gew&auml;hlt, nach der die Inhalte des Men&uuml;s sortiert werden.';
$GLOBALS["hOrderDir"] = 'W&auml;hlt die Richtung f&uuml;r die Sortierung (aufsteigend oder absteigend).';
$GLOBALS["hMLoginReq"] = 'Wenn dieser Haken gesetzt ist, wird ein Login f&uuml;r den Zugriff auf diese Men&uuml;option ben&ouml;tigt.';
$GLOBALS["hUsergroups"] = 'Der Login ist auf die Mitglieder dieser ausgew&auml;hlten User-Gruppen beschr&auml;nkt.';
$GLOBALS["hAuthor"] = 'Dies ist der  \'Eigent&uuml;mer\' von diesem Men&uuml;. Wenn Abschnitte der Webseite mit Sicherheitsbeschr&auml;nkungen versehen sind, kann nur der Eigent&uuml;mer Punkte zu diesem Men&uuml; hinzuf&uuml;gen.';

//  Error Messages
$GLOBALS["eMenuExists"] = 'Ein Kopf-Men&uuml; mit diesem Namen ist bereits vorhande, bitte w&auml;hlen Sie einen anderen Namen.';
$GLOBALS["eInvalidName"] = 'Der Men&uuml;name enth&auml;lt ung&uuml;ltige Zeichen, bitte keine Leer- oder Sonderzeichen verwenden.';
$GLOBALS["eTitleEmpty"] = 'Es muss ein Men&uuml;titel eingetragen werden.';

?>
