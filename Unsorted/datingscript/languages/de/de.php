<?php
############################################################
# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #
############################################################
# AzDGDatingLite          Version 2.0.3                    #
# Writed by               AzDG (support@azdg.com)          #
# Created 03/01/03        Last Modified 05/02/03           #
# Scripts Home:           http://www.azdg.com              #
############################################################
# File name               de.php                           #
# File purpose            German language file             #
# File created by Sebastian Karkus <Sebastian@Karkus.de>   #
############################################################

define('C_HTML_DIR','ltr'); // HTML direction for this language
define('C_CHARSET', 'iso-8859-1'); // HTML charset for this language

### !!!!! Please read it: RULES for translate!!!!! ###
### 1. Be carefull in translate - don`t use ' { } characters
###    You can use them html-equivalent - &#39; &#123; &#125;
### 2. Don`t translate {some_number} templates - you can only replace it - 
###    {0},{1}... - is not number - it templates
###################################

$w=array(
'<font color=red size=3>*</font>', //0 - Symbol for requirement field
'Sicherheitsfehler - #', //1
'Diese Email ist bereits in der Datenbank vorhanden. Bitte andere!', //2
'Ungueltiger Vorname. Vorname muss zwischen {0} - {1} Zeichen lang sein', //3 - Don`t change {0} and {1} - See rule 2 !!!
'Ungueltiger Nachname. Nachname muss zwischen {0} - {1} Zeichen lang sein', //4
'Falscher Geburtstag', //5
'Ungueltiges Passwort. Passwort muss zwischen {0} - {1} Zeichen lang sein', //6
'Waehle bitte Dein Geschlecht aus', //7
'Wähle das Geschlecht aus, welches Du suchst', //8
'Dein Beziehungswunsch', //9
'Deine Herkunft', //10
'Falsche oder leere Mail', //11
'Falsche Webseite', //12
'Falsche ICQ-Nummer', //13
'Falsche AIM', //14
'Deine Telefonnummer', //15
'Deine Stadt', //16
'Dein Status', //17
'Hast Du Kinder? Wieviele?', //18
'Deine Groesse', //19
'Dein Gewicht', //20
'Wunsch-Partners optimale Groesse', //21
'Wunsch-Partners optimales Gewicht', //22
'Deine Haarfarbe', //23
'Deine Augenfarbe', //24
'Deine Ethnizitaet', //25
'Deine Religion', //26
'Wunsch-Partners Ethnizitaet', //27
'Wunsch-Partners Religion', //28
'Rauchgewohnheiten', //29
'Trinkgewohnheiten', //30
'Deine Schulbildung', //31
'Dein Job', //32
'Wunsch-Partners Alter', //33
'Wie hast Du uns gefunden?', //34
'Schreib etwas ueber Dein Hobby', //35
'Fehler im Hobby-Feld.Hobby Soll nicht groesser sein als {0} Zeichen', //36
'Falsche Hobby-Wortgroesse. Hobby-Wortgroesse sollte groesser sein als  {0} Zeichen', //37
'Beschreib Dich selbst', //38
'Fehler im Beschreibungsfeld. Beschreibung sollte mehr als {0} Zeichen lang sein.', //39
'Falsche Beschreibungs-Wortgroesse. Beschreibungs-Wortgroesse sollte nicht groesser sein als {0} Zeichen', //40
'Dein Bild ist notwendig!', //41
'Glueckwunsch! <br>Dein Aktivierungscode wurde Dir per email geschickt.<br>Du musst Deine Registrierung von da bestaetigen!', //42 - Message after register if need confirm by email
'Bestaetige deine Registrierung', //43 - Confirm mail subject
'Danke fuer die Registrierung auf dieser Seite...
Klicke bitte hier um die Registrierung zu bestaetigen:

', //44 - Confirm message
'Danke fuer die Registrierung. Dein Profil wird bald freigeschaltet. Komm bald wieder...', //45 - Message after registering if admin allowing is needed
'Glueckwunsch! <br>Dein profil wurde der Datenbank hinzugefuegt!<br><br>Deine login id:', //46
'<br>Dein Passwort:', //47
'Gib Dein Passwort erneut ein', //48
'Die Passwoerter sind nicht identisch!', //49
'Mitglied registrieren', //50
'Dein Vorname', //51
'Zeichen', //52
'Dein Nachname', //53
'Passwort', //54
'Nochmal Passwort', //55
'Dein Geburtstag', //56
'Dein Geschlecht', //57
'Beziehungswunsch', //58
'Dein Staat', //59
'Deine Email-Adresse', //60
'Deine Webseite', //61
'Deine ICQ-Nummer', //62
'Deine AIM', //63
'Deine Telefonnummer', //64
'Deine Stadt', //65
'Dein momentaner Status', //66
'Deine Kinder', //67
'Deine Groesse', //68
'Dein Gewicht', //69
'Deine Haarfarbe', //70
'Deine Augenfarbe', //71
'Deine Ethnizitaet', //72
'Deine Religion', //73
'Rauchgewohnheiten', //74
'Trinkgewohnheiten', //75
'Deine Schulbildung', //76
'Dein Beruf', //77
'Dein Hobby', //78
'Beschreibe Dich selbst und die Person, die Du suchst.', //79
'Suche nach', //80
'Gesuchte Ethnizitaet', //81
'Gesuchte Religion', //82
'Gesuchte Alter', //83
'Gesuchte Groesse', //84
'Gesuchtes Gewicht', //85
'Wie hast Du uns gefaunden?', //86
'Foto', //87
'Hauptseite', //88
'Registrieren', //89
'Mitgliederbereich', //90
'Suchen', //91
'Feedback', //92
'FAQ', //93
'Statistik', //94
'Mitglieder Menue ID#', //95
'Nachrichten ansehen', //96
'Mein Schlafzimmer', //97
'Mein Profil', //98
'Profil aendern', //99
'Passwort aendern', //100
'Profil loeschen', //101
'Ausgang', //102
'Bearbeitungszeit:', //103
'sek.', //104
'Mitglieder online:', //105
'Gaeste online:', //106
'Powered by <a href="http://www.azdg.com" target="_blank" class="desc">AzDG</a>', //107 - Don`t change link - only for translate - read GPL!!!
'Nur registrierte Mitglieder koennen die erweiterte Suche nutzen', //108
'Sorry, "Alter von" muss kleienr sein als "Alter bis"', //109
'Suche verlief ergebnislos', //110
'Keine', //111 Picture available?
'Ja', //112 Picture available?
'Kann nicht mit dem Server verbinden<br>Dein mysql login oder mysql Passwort ist falsch.<br>Ueberpruefe es im config file', //113
'Kann nicht zum Server verbinden<br>Die Datenbank existiert nicht<br>Oder aendere den Datenbanknamen im config', //114
'Seiten :', //115
'Suchergebnisse', //116
'Gesamt : ', //117 
'Mitgliedsname', //118
'Zweck', //119
'Alter', //120
'Staat', //121
'Stadt', //122
'Letzter Zugang', //123
'Registrierungsdatum', //124
'Erweiterte Suche', //125
'Mitglieds ID#', //126
'Vorname', //127
'Nachname', //128
'Sternzeichen', //129
'Groesse', //130
'Gewicht', //131
'Geschlecht', //132
'Beziehungsart', //133
'Persoenlicher Status', //134
'Kinder', //135
'Haarfarbe', //136
'Augenfarbe', //137
'Ethnizitaet', //138
'Religion', //139
'Rauchen', //140
'Trinken', //141
'Schulbildung', //142
'Suche Mitglieder mit', //143
'Webseite', //144
'ICQ-Nummer', //145
'AIM', //146
'Telefonnummer', //147
'Registriert in ', //148
'Ergebnisse sortieren nach', //149
'Ergebnisse auf Seite', //150
'Einfache Suche', //151
'Zugang für Nichtmitglieder verwehrt', //152
'Zugang verwehrt fuer das Senden von Fake-Profils', //153
'User bereits negativ aufgefallen', //154
'Danke, der Benutzer wurde in die Fakerliste eingetragen und wird bald vom Administrator geprueft.', //155
'Zugang ins Schlafzimmer verwehrt', //156
'Das Mitglied ist bereits in Deinem Schlafzimmer', //157
'Danke- Benutzer wurde in Deinem Schlafzimmer aufgenommen', //158
'Dein Profil wurde erfolgreich zum Administratorcheck hinzugefuegt!', //159
'Dein Profil wurde erfolgreich zur Datenbank hinzugefuegt', //160
'Profil-Aktivierungsfehler. Kann sein, daß es bereits aktiv ist', //161
'FAQ Datenbank ist leer', //162
'FAQ Antwort#', //163
'Alle Felder muessen ausgefuellt sein', //164
'Deine Nachricht wurde erfolgreich gesendet', //165
'Dein Betreff', //166
'Deine Nachricht', //167
'Betreff', //168
'Nachricht', //169
'Nachricht senden', //170
'Fuer Mitglieder', //171
'Login ID', //172
'Passwort vergessen', //173
'Empfehler uns', //174
'Freundes-{0} email', //175
'Heutigen Geburtstage', //176
'Keine Geburtstage', //177
'Willkommen zu unserer AzDGDating Site', //178 Welcome message header
'AzDGDatingLite - ist eine tolle Moeglichkeit, um neue Freunde oder gar Lebenspartner zu finden.Treffen von Leuten und Knüpfen neuer Kontakte macht spass und ist sicher. <br><br> Du kannst ebenfalls Freunde finden, indem Du unser emailsystem benutzt. Dieses laesst Dich viel ueber Deine neuen Freunde herausfinden und neue Beziehungen entwickeln.<br>', //179 Welcome message
'Letzten {0} registrierten Mitglieder', //180
'Schnellsuche', //181
'Erweiterte Suche', //182
'Foto des Tages', //183
'Einfache Statistik', //184
'Deine ID muss numerisch sein', //185
'Falsche Login ID# oder Passwort', //186
'Zugang verweigert um Nachrichten zur Email zu schicken', //187
'Sende eine Email zu einer User ID#', //188
'Keine Mitglieder online', //189
'Gewuenschte Seite nicht verfuegbar', //190
'Gruesse von {0}', //191 "Recommend Us" subject, {0} - username
'Hallo von {0}!

Wie geht es Dir:)

Besuche diese Seite - einfach toll:
{1}', //192 "Recommend Us" message, {0} - username, {1} - site url
'Schreibe eine Adresse eines Freundes ein fuer eine #{0} email', //193
'Gib Deinen namen und Deine Emailadresse ein', //194
'Dein Passwort von', //195 Reming password email subject
'Dieses Konto wurde deaktiviert oder ist nicht in der Datenbank.<br>Schreibe bitte dem Administrator und fuege Deine ID ein.', //196
'Hallo!

Deine login ID#:{0}
Dein Passwort:{1}

_________________________
{2}', //197 Remind password email message, Where {0} - ID, {1} - password, {2} - C_SNAME(sitename)
'Dein Passwort wurde erfolgreich an Deine Emailadresse geschickt.', //198
'Gebe bitte Deine ID ein', //199
'Passwort senden', //200
'Zugang geschlossen um Nachrichten zu versenden', //201
'Schicke eine Mail an das Mitglied mit der ID#', //202
'Benachrichtige mich, wenn die Nachricht gelesen wurede', //203
'Kein Mitglied in der Datenbank', //204
'Statistik nicht verfuegbar', //205
'Diese ID existiert nicht', //206
'Profil ID#', //207
'Mitglieds Vorname', //208
'Mitglieds Nachname', //209
'Geburtstag', //210
'Email', //211
'Nachricht von AzDGDating', //212 - Subject for email
'Beruf', //213
'Hobby', //214
'Ueber', //215
'Beliebtheit', //216
'Schicke Nachricht', //217
'Falsches Profil', //218
'In mein Schlafzimmer', //219
'Entweder wurde keine Datei uebertragen, <br>oder die Datei war groesser als das {0} Kb Limit. Deine Datei ist {1} Kb gross', //220
'Die Datei, die Du uebertragen wolltest war breiter als das {0} pixel oder hoeher als das {1} pixel Limit.', //221
'Die Datei, die Du uebertragen wolltest war falsch. (nur jpg, gif und png ist moeglich). Deine Datei - ', //222
'(Max. {0} Kb)', //223
'Statistik nach Staat', //224
'Du hast keine Nachrichten', //225
'Insgesamt Nachrichten - ', //226
'Nummer', //227 Number
'Von', //228
'Datum', //229
'Loeschen', //230 Delete
'<sup>Neu</sup>', //231 New messages
'Loesche ausgewaehlte Nachrichten', //232
'Nachricht von - ', //233
'Antworten', //234
'Hallo, Du hast geschrieben {0}:\n\n_________________\n{1}\n\n_________________', //235 Reply to message {0} - date, {1} - message
'Deine Nachricht wurde gelesen', //236
'Deine Nachricht:<br><br><span class=dat>{0}</span><br><br>wurde gelesen von {1} [ID#{2}] in {3}', //237 {0} - message, {1} - Username, {2} - UserID, {3} - Date and Time
'{0} Nachrichten erfolgreich geloescht!', //238
'Bitte altes Passwort eingeben', //239
'Bitte neues Passwort eingeben', //240
'Neues passwort erneut eingeben', //241
'Passwort aendern', //242
'Altes Passwort', //243
'Neues Passwort', //244
'Neues Passwort erneut eingeben', //245
'Du hast keine Mitglieder im Schlafzimmer', //246
'Zugangsdatum', //247
'Loesche ausgewaehlte Mitglieder', //248
'Bist Du sicher, Dein eigenes Profil zu loeschen?<br>Alle Deine Nachrichten und Bilder werden aus der Datenbank geloescht.', //249
'Mitglied mit der  ID#={0} wurde erfolgreich aus der Datenbank entfernt', //250
'Dein profil wird nach der Ueberpruefung durch den Admin entfernt', //251
'{0} Mitglieder erfolgreich aus Deinem Schlafzimmer entfernt!', //252
'Waehle nicht das gleiche Passwort oder Dein Passwort enthaellt Sonderzeichen', //253
'Du hast keinen Zugang, umd as Passowort zu aendern', //254
'Ungeueltiges altes Passwort. Gehe zurueck und gib es neu ein!', //255
'Passwort wurde erfolgreich geaendert!', //256
'Es ist nicht moeglich, alle Bilder zu entfernen', //257
'Dein profil wurde erfolgreich geaendert!', //258
' - Bild loeschen', //259
'Deine Session wurde geloescht. Du kannst das Browserfenster schliessen.', //260
'Flagbilder nicht verfuegbar', //261
'Sprachen', //262
'Enter', //263
'Login [3-16 chars [A-Za-z0-9_]]', //264
'Login', //265
'Your login must consist of 3-16 chars and only A-Za-z0-9_ chars is available', //266
'This is login already in database. Please select another!', //267
'Total users - {0}', //268
'The messages are not visible. You should be the privileged user see the messages.<br><br>You can purshase from <a href="'.C_URL.'/members/index.php?l=de&a=r" class=head>here</a>', //269 change l=default to l=this_language_name
'User type', //270
'Purshase date', //271
'Search results position', //272
'Price', //273
'month', //274
'Purshase Last date', //275
'Higher than', //276
'Purshase', //277
'Purshase with', //278
'PayPal', //279
'Thanks for your registration. Payment has been succesfully send and will be checked by admin in short time.', //280
'Incorrect error. Please try again, or contact with admin!', //281
'Send congratulation letter about privilegies activating', //282
'User type has successfully changed.', //283
'Email with congratulations has been send to user.', //284
'ZIP',// 285 Zip code
'Congratulations, 

Your status is changed to {0}. This privilegies will be available in next {1} month.

Now you can check your messages in your box.

__________________________________
{2}', //286 {0} - Ex:Gold member, {1} - month number, {2} - Sitename from config
'Congratulations', //287 Subject
'ZIP code must be numeric', //288
'Keywords', //289
'We are sorry, but the following error occurred:', //290
'', //291
'', //292
'', //293
'', //294
'', //295
'', //296
'', //297
'', //298
'' //299
); 
?>
