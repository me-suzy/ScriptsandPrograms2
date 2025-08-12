<?php
############################################################
# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #
############################################################
# AzDGDatingLite          Version 2.0.3                    #
# Writed by               AzDG (support@azdg.com)          #
# Created 03/01/03        Last Modified 05/02/03           #
# Scripts Home:           http://www.azdg.com              #
############################################################
# File name               sw.php                           #
# File purpose            Swedish language file            #
# File created by         Kaj Merstrand <kaj@kub.se>       #
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
'Säkerhetsfel - #', //1
'Den E-post adress du angav finns redan i vår databas. Välj en annan!', //2
'Namn måste vara {0} - {1} tecken', //3 - ändra inte {0} and {1} - Se regel 2 !!!
'Efternamn måste vara {0} - {1} tecken', //4
'Felaktig födelse data', //5
'Felaktigt lösenord. Lösenord måste vara mellan {0} - {1} tecken', //6
'Välj kön', //7
'Please select seeking gender', //8
'Välj vad för relation söker du', //9
'Ange ditt land', //10
'Felaktig e-post adress', //11
'Felaktig webbadress', //12
'Felaktigt ICQ nummer', //13
'Felaktig AIM', //14
'Fyll i telefonnummer', //15
'Fyll i Ort', //16
'Ange ditt civilstånd', //17
'Ange om du har barn', //18
'Ange din längd', //19
'Ange din vikt', //20
'Ange önskad längd', //21
'Ange önskad vikt', //22
'Ange din hårfärg', //23
'Ange din ögonfärg', //24
'Ange Etnisk härkomst', //25
'Ange religion', //26
'Ange önskad etnisk härkomst', //27
'Ange önskad religion', //28
'Ange om du röker', //29
'Ange alkoholvanor', //30
'Ange din utbildning', //31
'Ange yrke', //32
'Ange önskad ålder', //33
'Ange hur du hittade oss', //34
'Ange din hobby', //35
'Felaktigt ifylld hobby. Hobby får inte innehålla mer än {0} tecken', //36
'Felaktigt ifylld hobby. Hobby får inte innehålla mer än {0} tecken', //37
'Ange en beskrivning om dig själv', //38
'Felaktig beskrivning. Din beskrivning får innehålla max {0} tecken', //39
'Felaktig beskrivning. Din beskrivning får innehålla max {0} tecken', //40
'Ditt foto behövs!', //41
'Grattis! <br>Din registreringskod har skickats till din E-post adress. <br>Du måste aktivera din registrering via E-post meddelandet!', //42 - Message after register if need confirm by email
'Godkänn din registrering', //43 - E-post bekräftelse
'Tack för din registrering hos Smygis Dating
Använd denna länk för att aktivera din registrering:

', //44 - Confirm message
'Tack för din registrering. Din profil kommer att kontroleras inom 24 timmar. Besök oss och kontrollera att du kommit med. ', //45 - Din registrering är nu godkänd.
'Grattis! <br>Dina uppgifter har registrerats i vår databas!<br><br>Ditt login id:', //46
'<br>Ditt lösenord:', //47
'Upprepa ditt lösenord', //48
'Lösenorden är inte lika', //49
'Registrering', //50
'Ditt namn', //51
'tecken', //52
'Efternamn', //53
'Lösenord', //54
'Skriv lösenord igen', //55
'Födelsedata', //56
'Kön', //57
'Förhållande du söker', //58
'Land', //59
'E-post', //60
'Webbsida', //61
'ICQ', //62
'AIM', //63
'Telefon', //64
'Ort', //65
'Civilstånd', //66
'Barn', //67
'Längd', //68
'Vikt', //69
'Hår färg', //70
'Ögon Färg', //71
'Etnisk härkomst', //72
'Religion', //73
'Rökare', //74
'Alkoholvanor', //75
'Utbildning', //76
'Arbete', //77
'Hobby', //78
'Beskriv dig själv och vad du har för önskemål hos en blivande partner.', //79
'Jag söker', //80
'Önskad etnisk härkomst', //81
'Önskad religion', //82
'Önskad ålder', //83
'Önskad längd', //84
'Önskad vikt', //85
'Hur hittade du oss?', //86
'Bild', //87
'Hem', //88
'Registrera', //89
'Medlems sida', //90
'Sök', //91
'Skicka åsikter till oss', //92
'Hjälp', //93
'Statistik', //94
'Medlems sida ID#', //95
'Läs meddelande', //96
'Mitt sovrum', //97
'Min profil', //98
'Ändra profil', //99
'Ändra lösenord', //100
'Radera profil', //101
'Logga ut', //102
'Senaste uppdatering tog:', //103
'sek.', //104
'Användare online:', //105
'Gäster online:', //106
'Powered by <a href="http://www.azdg.com" target="_blank" class="desc">AzDG</a>', //107 - Don`t change link - only for translate - read GPL!!!
'Avancerad sök funktion är endast för registrerade användare', //108
'Tyvärr, "Ålder från" måste vara lägre än "Ålder till"', //109
'Tyvärr hittade inget som motsvarar din sökning', //110
'Ingen', //111 Picture available?
'Ja', //112 Picture available?
'Kan inte logga in till servern,<br>ditt mysql namn eller mysql lösenord är felaktigt.<br>Kontrollera Konfigurations filen', //113
'Kan inte logga in till servern<br>Databasen finns inte<br>Kontrollera Konfigurations filen', //114
'Sidor :', //115
'Sök resultat', //116
'Totalt : ', //117 
'Användarnamn', //118
'Syfte', //119
'Ålder', //120
'Land', //121
'Ort', //122
'Senaste tillträde', //123
'Registrerad den', //124
'Avancerad sökning', //125
'Användar ID#', //126
'Nam', //127
'Efternamn', //128
'Stjärntecken', //129
'Längd', //130
'Vikt', //131
'Kön', //132
'Typ av relation', //133
'Civilstånd', //134
'Barn', //135
'Hårfärg', //136
'Ögonfärg', //137
'Etnisk härkomst', //138
'Religion', //139
'Röker', //140
'Dricker', //141
'Utbildning', //142
'Sök person med', //143
'Webbsida', //144
'ICQ', //145
'AIM', //146
'Telefon', //147
'Registerad i ', //148
'Sortera resultat efter', //149
'Resultat per sida', //150
'Standard sökning', //151
'Stängt, endast för medlemmar', //152
'Stängt för att skicka dåliga profiler', //153
'Användaren finns redan i dåliga "svarta" listan', //154
'Tack, användaren har placerats i dåliga "svarta" listan och kommer att kontrolleras inom kort', //155
'Stängt för att använda sovrum', //156
'Personen finns redan i ditt sovrum', //157
'Personen har lagts till i ditt sovrum', //158
'Din profile lagts in för kontroll hos administratören!', //159
'Din profil har lagts till i vår databas', //160
'Fel vid aktivering av profil. Kanske den redan är aktiverad', //161
'FAQ databasen är tom', //162
'FAQ svar#', //163
'Alla fält måste vara ifyllda', //164
'Ditt meddelande har skickats', //165
'Ange din rubrik', //166
'Skriv ditt meddelande', //167
'Rubrik', //168
'Meddelande', //169
'Skicka meddelande', //170
'För medlemmar', //171
'Login ID', //172
'Glömt lösenord', //173
'Rekommendera oss', //174
'Vän-{0} E-post', //175
'Dagens födelsedagar', //176
'Inga födelsedagar', //177
'Välkommen AzDGDating Site', //178 Välkommen
'AzDGDatingLite - är ett roligt sätt att hitta nya vänner eller partners, bara på skoj, dating eller kanske till och med en långvarig relation. Möta och diskutera med olika människor är alltid lika roligt. Men tänk alltid på att vara väldigt noga innan du möter en okänd person ansikte mot ansikte för första gången..<br><br>Du kan lugnt och säkert kommunicera med personer via vårt skyddade E-post system, din riktiga E-post adress blir aldrig visad till någon annan. Detta ger dig bra möjligheter att lära känna din nya vän innan du lämnar några privata uppgifter.<br>', //179 Hjärtligt välkommen till oss
'Senaste {0} registrerade användare', //180
'Snabb sök', //181
'Avancerad sökning', //182
'Dagens bild', //183
'Enkel statistik', //184
'Ditt ID måste bestå av siffror', //185
'Felaktigt Login ID# eller lösenord', //186
'Stängt för att skicka meddelanden till E-post', //187
'Skicka meddelande till E-post för användar ID#', //188
'Inga användare online', //189
'Rekommenderad sida är inte tillgänglig', //190
'Hälsningar från {0}', //191 "Recommend Us" subject, {0} - username
'Hej från {0}!

Hur mår du:)

Besök denna sida - Kanon bra:
{1}', //192 "Recommend Us" message, {0} - username, {1} - site url
'Skriv rätt namn på din vän#{0} email', //193
'Skriv ditt namn och E-post adress', //194
'Ditt lösenord från {0}', //195 Reming password email subject
'Detta konto är deaktiverat eller finns inte i vår databas.<br>Skicka ett brev till administratören via "skicka åsikt". Vänligen skriv med ditt ID också.', //196
'Hej!

Ditt login ID#:{0}
Ditt lösenord:{1}

_________________________
{2}', //197 Remind password email message, Where {0} - ID, {1} - password, {2} - C_SNAME(sitename)
'Ditt lösenord har skickats till din E-post adress.', //198
'Skriv ditt ID', //199
'Skicka lösenord', //200
'Tillgång stängd för att skicka meddelande', //201
'Skicka meddelande till användar ID#', //202
'Meddela mig när mottagaren har läst sitt meddelande', //203
'Ingen med det ID du angav i databasen', //204
'Statistik inte tillgänglig', //205
'Detta ID existerar inte', //206
'Profil ID#', //207
'Användarens förstanamn', //208
'Användarens efternamn', //209
'Födelsedag', //210
'E-post', //211
'Besked från AzDGDating', //212 - Subject for email
'Arbete', //213
'Hobby', //214
'Om', //215
'Popularitet', //216
'Skicka E-post', //217
'Dålig profil', //218
'Lägg till i mitt sovrum', //219
'Antingen så var det ingen bild skickad, <br>eller så var bilden du försökte skicka större än {0} Kb gränsen. Din bild är {1} Kb', //220
'Filen du försökte skicka är bredden större än {0} px eller höjden större än {1} px gränsen.', //221
'Den bild du försökte skicka är i fel format (endast jpg, gif och png är tillåtet). Din bild är - ', //222
'(Max. {0} Kb)', //223
'Statistik över länder', //224
'Du har inga meddelanden', //225
'Total antal meddelanden - ', //226
'Nummer', //227 Number
'Från', //228
'Datum', //229
'Radera', //230 Delete
'<sup>Ny</sup>', //231 New messages
'Radera valda meddelanden', //232
'Meddelande från - ', //233
'Svara', //234
'Hej, du skrev {0}:\n\n_________________\n{1}\n\n_________________', //235 Reply to message {0} - date, {1} - message
'Ditt meddelande har lästs av mottagaren', //236
'Ditt meddelande:<br><br><span class=dat>{0}</span><br><br>lästs av {1} [ID#{2}] in {3}', //237 {0} - message, {1} - Username, {2} - UserID, {3} - Date and Time
'{0} Meddelande raderat!', //238
'Ange ditt gamla lösenord', //239
'Skriv nytt lösenord', //240
'Skriv lösenordet igen', //241
'Ändra lösenord', //242
'Gammalt lösenord', //243
'Nytt lösenord', //244
'Skriv det nya lösenorder igen', //245
'Du har inga användare i sovrummet', //246
'Tillföljelse datum', //247
'Radera valda användare', //248
'Är du säker på att du vill radera din egen profil?<br>Alla dina meddelande och bilder kommer att raderas ur vår databas, kan inte återskapas.', //249
'Användare med ID#={0} har raderats ur vår databas', //250
'Din  profil kommer att raderas när den kontrollerats av administratören', //251
'{0} användare raderad från ditt sovrum!', //252
'Inte identiska lösenord eller lösenordet innehåller felaktiga tecken', //253
'Du har inte behörighet att ändra lösenord', //254
'Gamla lösenordet är felaktigt. Gå tillbaka och försök igen!', //255
'Lösenordet har ändrats!', //256
'Inte möjligt att radera alla bilder', //257
'Din profil är ändrad', //258
' - Radera bilder', //259
'Din session har avslutats. Du kan stänga din browser', //260
'Flaggbilder inte tillgängliga', //261
'Språk', //262
'Enter', //263
'Login [3-16 chars [A-Za-z0-9_]]', //264
'Login', //265
'Your login must consist of 3-16 chars and only A-Za-z0-9_ chars is available', //266
'This is login already in database. Please select another!', //267
'Total users - {0}', //268
'The messages are not visible. You should be the privileged user see the messages.<br><br>You can purshase from <a href="'.C_URL.'/members/index.php?l=se&a=r" class=head>here</a>', //269 change l=default to l=this_language_name
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
