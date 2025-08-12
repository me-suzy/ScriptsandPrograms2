<?php
############################################################
# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #
############################################################
# AzDGDatingLite          Version 2.1.1                    #
# Writed by               AzDG (support@azdg.com)          #
# Created 03/01/03        Last Modified 04/05/03           #
# Scripts Home:           http://www.azdg.com              #
############################################################
# File name               nl.php                           #
# File purpose            dutch language file              #
# File created by         Erik <erik@clubpriority.nl>      #
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
'Veiligdheidsfout - #', //1
'Dit e-mailadres is reeds geregistreerd in onze database. Voer een ander e-mailadres in!', //2
'Voornaam onjuist. Voornaam dient uit {0} tot { 1} karakters te bestaan.', //3 - Don`t change {0} and {1} - See rule 2 !!!
'Achternaam onjuist. Achternaam dient uit {0} tot {1} karakters te bestaan.', //4
'Geboortedatum onjuist.', //5
'Wachtwoord onjuist. Wachtwoord dient uit {0} tot {1} karakters te bestaan', //6
'Selecteer je geslacht', //7
'Selecteer het geslacht waar je naar op zoek bent', //8
'Selecteer het type relatie', //9
'Selecteer je provincie', //10
'Onjuist of niet ingevoerd e-mailadres', //11
'Website onjuist.', //12
'ICQ nummer onjuist', //13
'AIM onjuist', //14
'Voer je telefoonnummer in', //15
'Voer je woonplaats in', //16
'Selecteer je burgelijke staat', //17
'Selecteer het antwoord mbt je kinderen', //18
'Selecteer je lengte', //19
'Selecteer je gewicht', //20
'Selecteer de lengte die je zoekt', //21
'Selecteer het gewicht dat je zoekt', //22
'Selecteer de kleur van je haar', //23
'Selecteer de kleur van je ogen', //24
'Selecteer je huidskleur', //25
'Selecteer je geloofsovertuiging', //26
'Selecteer de huidskleur die je zoekt', //27
'Selecteer de geloofsovertuiging die je zoekt', //28
'Selecteer mbt roken', //29
'Selecteer mbt drinken', //30
'Selecteer mbt je opleiding', //31
'Voer in over je baan', //32
'Voer gezochte leeftijd in', //33
'Hoe heb je deze site gevonden', //34
'Voer je hobbies in', //35
'Hobby onjuist ingevoerd. Dit veld mag niet groter zijn dan {0} karakters', //36.
'Hobby onjuist ingevoerd. Dit veld mag niet groter zijn dan {0} karakters', //37
'Vertel wat over jezelf', //38
'Onjuiste invoer omschrijving. Deze mag niet groter zijn dan {0} tekens', //39
'Onjuiste invoer omschrijving. Deze mag niet groter zijn dan {0} tekens per woord', //40
'Het is verplicht een foto te plaatsen!', //41
'Gefeliciteerd! <br>Jouw activeringscode is verzonden naar je opgegeven e-mailadres. <br>Je dient met de link in dit mailtje je inschrijving te bevestigen!', //42 - Message after register if need confirm by email
'Bevestig je registratie', //43 - Confirm mail subject
'Bedankt voor het registreren op onze site...
Please enter this link for confirm your register:

', //44 - Confirm message
'Bedankt voor het registreren. Jowu profiel zal op korte termijn worden goedgekeurd. Kom op een later moment terug...', //45 - Message after registering if admin allowing is needed
'Gefeliciteerd! <br>Jouw profiel is toegevoegd aan onze database!<br><br>Jouw gebruikers id:', //46
'<br>Jouw wachtwoord:', //47
'Voer je wachtwoord nogmaals in', //48
'De wachtwoorden zijn niet gelijk', //49
'Registreer gebruiker', //50
'Jouw voornaam', //51
'tekens', //52
'Jouw achternaam', //53
'Wachtwoord', //54
'Nogmaals je wachtwoord', //55
'Geboortedatum', //56
'Geslacht', //57
'Type relatie', //58
'Provincie', //59
'E-mailadres', //60
'Website', //61
'ICQ', //62
'AIM', //63
'Telefoonnummer', //64
'Plaats', //65
'Burgelijke staat', //66
'Kinderen', //67
'Lengte', //68
'Gewicht', //69
'Kleur haar', //70
'Kleur ogen', //71
'Huidskleur', //72
'Geloofsovertuiging', //73
'Roken', //74
'Drinken', //75
'Opleiding', //76
'Baan', //77
'Hobbies', //78
'Beschrijf jezelf en het type persoon waar je naar op zoek bent.', //79
'Op zoek naar', //80
'Gewenste huidskleur', //81
'Gewenste geloofsovertuiging', //82
'Gewenste leeftijd', //83
'Gewenste lengte', //84
'Gewenste gewicht', //85
'Hoe heb je deze site gevonden?', //86
'Foto', //87
'Start', //88
'Registreer', //89
'Leden Xclusief', //90
'Zoek', //91
'Respons', //92
'FAQ', //93
'Statistieken', //94
'Leden Xclusief menu ID#', //95
'Bekijk berichten', //96
'Mijn slaapkamer', //97
'Mijn profiel', //98
'Bewerk profiel', //99
'Wijzig wachtwoord', //100
'Verwijder profiel', //101
'Afsluiten', //102
'Verwerkingstijd:', //103
'sec.', //104
'Gebruikers online:', //105
'Gasten online:', //106
'Powered by <a href="http://www.azdg.com" target="_blank" class="desc">AzDG</a>', //107 - Don`t change link - only for translate - read GPL!!!
'Alleen geregistreerde gebruikers kunnen uitgebreid zoeken', //108
'Sorry, "Leeftijd vanaf" dient minder te zijn dan "Leeftijd tot"', //109
'Zoeken heeft geen resultaten opgeleverd', //110
'Geen', //111 Picture available?
'Ja', //112 Picture available?
'Kan geen verbinding maken met de server<br>Het is niet mogelijk geweest in te loggen op de mySQL database.<br>Controleer de gegevens in het config bestand', //113
'Kan geen verbinding maken met de server<br>Database bestaat niet (meer)<br>Wijzig de database in het config bestand', //114
'Pagina&#39;s :', //115
'Zoekresultaten', //116
'Totaal : ', //117 
'Gebruikersnaam', //118
'Doel', //119
'Leeftijd', //120
'Provincie', //121
'Plaats', //122
'Laatste bezoek', //123
'Registratiedatum', //124
'Uitgebreid zoeken', //125
'Gebruikers ID#', //126
'Voornaam', //127
'Achternaam', //128
'Horoscoop', //129
'Lengte', //130
'Gewicht', //131
'Geslacht', //132
'Type relatie', //133
'Burgelijke staat', //134
'Kinderen', //135
'Kleur haar', //136
'Kleur ogen', //137
'Huidskleur', //138
'Geloofsovertuiging', //139
'Roken', //140
'Drinken', //141
'Opleiding', //142
'Zoek gebruikers met', //143
'Website', //144
'ICQ', //145
'AIM', //146
'Telefoonnummer', //147
'Geregistreerd in ', //148
'Sorteer resultaten op', //149
'Resultaten per pagina', //150
'Snel zoeken', //151
'Geen toegang voor niet-leden', //152
'Geen toegang voor het versturen van bericht. Gebruiker is stout.', //153
'Gebruiker reeds in "stoute profielen" database geplaatst.', //154
'Bedankt, gebruiker is toegevoegd aan "stoute profielen" en zal op korte termijn door de webmaster worden aangesproken', //155
'Geen toegang tot de slaapkamer', //156
'Gebruiker is reeds in jouw slaapkamer', //157
'Bedankt, gebruiker is succesvol toegevoegd aan je slaapkamer.', //158
'Jouw profiel is succesvol toegevoegd aan onze database voor controle door de webmaster!', //159
'Jouw profiel is succesvol toegevoegd aan onze database', //160
'Fout tijdens activeren profiel. Mogelijk is deze reeds actief.', //161
'FAQ lijst is leeg', //162
'FAQ antwoord#', //163
'Alle velden dienen te zijn ingevuld', //164
'Jouw bericht is succesvol verzonden', //165
'Voer het onderwerp in', //166
'Voer het bericht in', //167
'Onderwerp', //168
'Bericht', //169
'Verstuur bericht', //170
'Voor leden', //171
'Gebruikers ID', //172
'Wachtwoord vergeten', //173
'Raad ons aan', //174
'Vriend(in)-{0} e-mailadres', //175
'Jarig vandaag', //176
'Geen verjaardagen', //177
'Welkom op onze dating site', //178 Welcome message header
'Dating bij ons is de beste manier om online nieuwe vrienden te maken. Voor lol, daten en echte relaties. Mensen ontmoeten en met ze praten is leuk.<br><br>Je kan ook nieuwe vrienden vinden via onze eigen mailsysteem. Zo kun je meer van elkaar te weten komen om een relatie op te bouwen.<br>', //179 Welcome message
'Laatste {0} geregistreerde gebruikers', //180
'Snel zoeken', //181
'Uitgebreid zoeken', //182
'Foto van de dag', //183
'Simpele statistieken', //184
'Jouw ID moet numeriek zijn', //185
'Onjuiste ID# of wachtwoord', //186
'Toegang voor het versturen van e-mail is momenteel afgesloten', //187
'Verstuur een e-mailtje naar ID#', //188
'Geen gebruikers online', //189
'Raad-ons-aan pagina niet beschikbaar', //190
'Groeten van {0}', //191 "Recommend Us" subject, {0} - username
'Berichtje van {0}!

Hoe gaat het met jou:)

Bezoek snel deze site - hij is erg goed:
{1}', //192 "Raad ons aan" message, {0} - username, {1} - site url
'Voer het correcte #{0} e-mailadres in', //193
'Voer je naam en e-mailadres in', //194
'Jouw wachtwoord van {0}', //195 Reming password email subject
'Dit profiel is niet actief of bestaat niet in de database.<br>Stuur een berichtje naar de webmaster over dit probleem. Vul hierbij ook je ID in.', //196
'Hallo!

Jouw gebruikers ID#:{0}
Jouw wachtwoord:{1}

_________________________
{2}', //197 Remind password email message, Where {0} - ID, {1} - password, {2} - C_SNAME(sitename)
'Jouw wachtwoord is succesvol naar je e-mailadres verzonden.', //198
'Voer je ID in', //199
'Verstuur wachtwoord', //200
'Toegang afgesloten voor het versturen van berichten', //201
'Stuur een bericht naar gebruikers-ID#', //202
'Geef een leesbevestiging van dit bericht', //203
'Geen gebruiker in de database', //204
'Statistieken niet beschikbaar', //205
'Dit ID bestaat niet', //206
'Gebruikers-ID#', //207
'Voornaam gebruiker', //208
'Achternaam gebruiker', //209
'Geboortedatum', //210
'E-mailadres', //211
'Bericht van de datingbox', //212 - Subject for email
'Baan', //213
'Hobbies', //214
'Over', //215
'Populariteit', //216
'Stuur e-mail', //217
'Stout profiel', //218
'Voeg toe aan mijn slaapkamer', //219
'Er is geen bestand geupload of <br>het bestand dat je wil uploaden is groter dan de {0} Kb limiet. Jouw bestand is {1} Kb', //220
'Het bestand dat je wil uploaden is groter dan de {0} px of de hoogte was groter dan de {1} px limiet.', //221
'Het type bestand dat je wil uploaden is niet toegestaan (alleen jpg, gif en png zijn toegestaan). Jouw type - ', //222
'(Max. {0} Kb)', //223
'Statistieken per provincie', //224
'Je hebt geen berichten', //225
'Alle berichten - ', //226
'Aantal', //227 Number
'Van', //228
'Datum', //229
'Verwijder', //230 Delete
'<sup>Nieuw</sup>', //231 New messages
'Verwijder geselecteerde berichten', //232
'Bericht van - ', //233
'Beantwoorden', //234
'Hallo, jij schreef {0}:\n\n_________________\n{1}\n\n_________________', //235 Reply to message {0} - date, {1} - message
'Jouw bericht is gelezen', //236
'Jouw bericht:<br><br><span class=dat>{0}</span><br><br>is gelezen door {1} [ID#{2}] in {3}', //237 {0} - message, {1} - Username, {2} - UserID, {3} - Date and Time
'{0} berichten succesvol verwijderd!', //238
'Voer je oude wachtwoord in', //239
'Voer je nieuwe wachtwoord in', //240
'Voor je nieuwe wachtwoord nogmaals in', //241
'Wijzig wachtwoord', //242
'Oude wachtwoord', //243
'Nieuwe wachtwoord', //244
'Nogmaals nieuwe wachtwoord', //245
'Je hebt geen enkele gebruiker in je slaapkamer', //246
'Datum toevoeging', //247
'Verwijder geselecteerde gebruikers', //248
'Weet je zeker dat je je eigen profiel wenst te verwijderen?<br>Al je berichten en plaatjes worden verwijderd!', //249
'Gebruiker met ID#={0} is succesvol verwijderd uit onze database', //250
'Jouw profiel zal worden verwijderd na controle van de webmaster', //251
'{0} gebruikers succesvol verwijderd uit je slaapkamer!', //252
'Geen identieke wachtwoorden of ongeldige karakters gebruikt', //253
'Je hebt geen rechten om je wachtwoord te wijzigen', //254
'Huidig wachtwoord is onjuist. Ga terug en corrigeer dit!', //255
'Wachtwoord is succesvol gewijzigd!', //256
'Het is niet mogelijk alle fotos te verwijderen', //257
'Jouw profiel is succesvol gewijzigd', //258
' - Verwijder plaatje', //259
'Jouw sessie is beëindigd. Je kan je browser sluiten.', //260
'Vlag afbeelding(en) niet beschikbaar', //261
'Talen', //262
'Ga!', //263
'Login [3-16 chars [A-Za-z0-9_]]', //264
'Login', //265
'Your login must consist of 3-16 chars and only A-Za-z0-9_ chars is available', //266
'This is login already in database. Please select another!', //267
'Total users - {0}', //268
'The messages are not visible. You should be the privileged user see the messages.<br><br>You can purshase from <a href="'.C_URL.'/members/index.php?l=nl&a=r" class=head>here</a>', //269 change l=default to l=this_language_name
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
