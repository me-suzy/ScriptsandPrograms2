<?php
############################################################
# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #
############################################################
# AzDGDatingLite          Version 2.0.3                    #
# Writed by               AzDG (support@azdg.com)          #
# Created 03/01/03        Last Modified 05/02/03           #
# Scripts Home:           http://www.azdg.com              #
############################################################
# File name               default.php                      #
# File purpose            Default language file            #
# File created by         AzDG <support@azdg.com>          #
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
'Security error - #', //1
'Ovaj mail je veæ prodaðen u bazi. Upiite drugi!', //2
'Krivo uneseno ime. Ime mora sadravati izmeðu {0} - {1} znakova', //3 - Don`t change {0} and {1} - See rule 2 !!!
'Krivo uneseno prezime. Prezime mora sadravati izmeðu {0} - {1} znakova', //4
'Krivi datum roðenja', //5
'Kriva lozinka. Lozinka mora sadrzavati izmeðu {0} - {1} znakova', //6
'Spol', //7
'Spol koji traite', //8
'Va trenutni status', //9
'Zemlja', //10
'Krivo unesen e-mail ili nedostaje', //11
'Krivi webpage', //12
'Krivi ICQ UIN', //13
'Krivi AIM', //14
'Tel', //15
'Grad', //16
'Materijalni status', //17
'Tvoja djeca', //18
'Visina', //19
'Teina', //20
'Traite osobu visine', //21
'Traite osobu teine', //22
'Koje je boje tvoja kosa', //23
'Koje su boje tvoje oæi', //24
'Select your ethnicity', //25
'Vjerska pripadnost', //26
'Select seeking ethnicity', //27
'Traite osobu koje vjerske pripadnosti', //28
'Pui li', //29
'Pije li', //30
'Koju si kolu zavrio/la', //31
'Tvoj posao', //32
'Trai osobu kojih godina', //33
'Kako ste nas pronali', //34
'Koji su tvoji hobiji', //35
'Hobi smije sadravati maksimalno {0} znakova', //36
'Pre dugaèak hobi. Hobi smije sadravati maksimalno {0} znakova', //37
'Pii o sebi', //38
'Greka. Opis nesmije biti dui od {0} znakova', //39
'Greka u duini slova. Opis nesmije biti dui od {0} znakova', //40
'Potrebna je tvoja slika!', //41
'Èestitamo! <br>Va aktivacijski kod vam je poslan mailom. <br>Morate potvrditi registraciju mailom!', //42 - Message after register if need confirm by email
'Potvrdite registraciju', //43 - Confirm mail subject
'Zahvaljujemo na registraciji...
Kliknite na ovaj link kako biste potvrdili registraciju:

', //44 - Confirm message
'Zahvaljujemo na registraciji. Vai podaci æe biti brzo obraðeni. Posjetite nas uskoro...', //45 - Message after registering if admin allowing is needed
'Èestitamo! <br>Vai podaci æe biti brzo obraðeni!<br><br>Lae korisnièki ime:', //46
'<br>lozinka:', //47
'ponovite lozinku', //48
'Lozinka nije ponovljena isparavno', //49
'Registracija novih korisnika', //50
'Ime', //51
'chars', //52
'Prezime', //53
'Lozinka', //54
'Ponovite lozinku', //55
'Datum roðenja', //56
'Spol', //57
'Status', //58
'Zemlja', //59
'E-mail', //60
'Webpage', //61
'ICQ', //62
'AIM', //63
'Tel', //64
'Grad', //65
'Status', //66
'Djeca', //67
'Visina', //68
'Teina', //69
'Boja kose', //70
'Boja oæi', //71
'Ethnicity', //72
'Vjera', //73
'Puenje', //74
'Alkohol', //75
'Obrazovanje', //76
'Posao', //77
'Hobi', //78
'Opii sebe i kakovog zamilja idealnog partnera/icu.', //79
'Trei', //80
'Seeking ethnicity', //81
'Trai religiju', //82
'Trai godine', //83
'Trai visinu', //84
'Trai teinu', //85
'Kako ste nas pronali?', //86
'Slika', //87
'Poèetna', //88
'Registracija', //89
'Korisnici', //90
'Trailica', //91
'Feedback', //92
'FAQ', //93
'Statistika', //94
'Members menu ID#', //95
'Pogledaj poruke', //96
'Spavaæa soba', //97
'Moj profil', //98
'Promjeni profil', //99
'Promjeni lozinku', //100
'Obrii informacije', //101
'Izlaz', //102
'Processing time:', //103
'sec.', //104
'Trenutno korisnika online:', //105
'Quests online:', //106
'Powered by <a href="http://www.azdg.com" target="_blank" class="desc">AzDG</a>', //107 - Don`t change link - only for translate - read GPL!!!
'Samo registrirani korisnici mogu koristiti napredno pretraivanje', //108
'Gre¹ka, "godine od" moraju biti manje od "godine za"', //109
'Nema rezultata', //110
'Nedostupno', //111 Picture available?
'Dostupna', //112 Picture available?
'Can`t connect to server<br>Your mysql login or mysql password is wrong.<br>Check it in config file', //113
'Can`t connect to server<br>Database don`t exist<br>Or Change Database name in config', //114
'Strane :', //115
'Rezultati pretraivanja', //116
'Ukupno : ', //117 
'Korisnièko ime', //118
'Purposes', //119
'Godine', //120
'Zemlja', //121
'Grad', //122
'Zadnji pristup', //123
'Registriran od', //124
'Napredno pretraivanje', //125
'Korisnièki ID#', //126
'Ime', //127
'Prezime', //128
'Horoskopski znak', //129
'Visina', //130
'Teina', //131
'Spol', //132
'Vrsta veze', //133
'Marital status', //134
'Djeca', //135
'Boja kose', //136
'Boja oæi', //137
'Ethnicity', //138
'Vjera', //139
'Puaæ', //140
'Alkoholièar', //141
'Struèna sprema', //142
'Pretraga korisnika po', //143
'Webpage', //144
'ICQ', //145
'AIM', //146
'Tel', //147
'Registiran u ', //148
'Sortraj rezultate po', //149
'Rezultati', //150
'Jednostavno pretraivanje', //151
'Samo za registrirane korisnike', //152
'Krivi podatci o korisniku', //153
'Veæ je prijavljeno', //154
'Hvala, Prijavljeno je administratoru', //155
'Zabranjen ulaz u spavaæu sobu', //156
'Korisnik je veæ u sobi', //157
'Thnx, korisnik se veæ tamo nalazi', //158
'Vai podatci us poslani administratoru na provjeru!', //159
'Vai podatci su dodani u bazu', //160
'Greka prilikom aktivacije. Moda je veæ aktivno', //161
'FAQ je prazan', //162
'FAQ Odgovor#', //163
'Sva polja moraju biti ispunjena', //164
'Vaa poruka je uspjeno poslana', //165
'Unesite temu', //166
'Unesite poruku', //167
'Tema', //168
'Poruka', //169
'Poalji poruku', //170
'Za korisnike', //171
'Korisnièki ID', //172
'Izgubljena lozinka', //173
'Preporuèite nas', //174
'Prijatelj-{0} email', //175
'Dananji roðendani', //176
'Nema roðendana', //177
'Dobrodo¹li', //178 Welcome message header
'TRENUTNO U PRIPREMI - za sve infromacije javite se na ime@prezime.xxx<br>', //179 Welcome message
'Zadnjih {0} registriranih', //180
'Pretraivanje', //181
'Napredno pretraivanje', //182
'Fotografija dana', //183
'Statistike', //184
'Va ID je broj', //185
'Krivi ID# ili lozinka', //186
'Nije omoguèeno slanje mailova', //187
'Poalji poruku na email korisnika ID#', //188
'Nema korisnika online', //189
'Recommend page unavailable', //190
'Pozdrav od {0}', //191 "Preporuèite nas" tema, {0} - username
'Pozdrav od {0}!

Kako si mi danas? :)

Posjeti ovaj site - odlican je:
{1}', //192 "Recommend Us" message, {0} - username, {1} - site url
'Unesite toèan#{0} email', //193
'Unesite svoje ime i email', //194
'Vaa lozinka {0}', //195 Reming password email subject
'Racun je deaktiviran.<br>Javite se administratoru. I posaljite mu svoj ID.', //196
'Hello!

Korisnièko ime ID#:{0}
Lozinka:{1}

_________________________
{2}', //197 Remind password email message, Where {0} - ID, {1} - password, {2} - C_SNAME(sitename)
'Vaa lozinka vam je uspjeno poslana na va e-mail.', //198
'Unoseite svoj ID', //199
'Po¹alji poruku', //200
'Nedozvoljeno slanje poruka', //201
'Poalji poruku korisniku ID#', //202
'Obavijesti me kad korisnik procita poruku', //203
'Nema korisnika u bazi', //204
'Statistike nisu dostupne', //205
'ID nije aktivan', //206
'Profil ID#', //207
'Ime', //208
'Prezime', //209
'Rodendan', //210
'Email', //211
'Poruka od matcha.net', //212 - Subject for email
'Posao', //213
'Hobi', //214
'O meni', //215
'Popularity', //216
'Poalji email', //217
'Nepotpun profil', //218
'Dodaj u moji spavacu sobu', //219
'Nema uploudanog file, <br>ili je va file veci od {0} Kb limita. Va file je velik {1} Kb', //220
'File je irok {0} px vii {1} px od limita.', //221
'Neispravna ekstenzija file koji pouavate uploudati (samo jpg, gif i png). Va tip je - ', //222
'(Max. {0} Kb)', //223
'Statistike po zemljama', //224
'Nemate poruka', //225
'Ukupno poruka - ', //226
'Bro', //227 Number
'Od', //228
'Datum', //229
'Obr', //230 Delete
'<sup>Nova</sup>', //231 New messages
'Obrii oznacene poruke', //232
'Poruka od - ', //233
'Odgovori', //234
'Hi, ti si poslao {0}:\n\n_________________\n{1}\n\n_________________', //235 Reply to message {0} - date, {1} - message
'Vaa poruka je procitana', //236
'Vaa poruka:<br><br><span class=dat>{0}</span><br><br>je osvjedio {1} [ID#{2}] u {3}', //237 {0} - message, {1} - Username, {2} - UserID, {3} - Date and Time
'{0} Poruka uspjeno obrisana!', //238
'Unesite staru lozinku', //239
'Unesite novu lozinku', //240
'Ponovite novu lozinku', //241
'Promjeni lozinku', //242
'Stara lozinka', //243
'Nova lozinka', //244
'Ponovite novu lozinku', //245
'Nemate nikoga u spavacoj sobi', //246
'Datum dodavanja', //247
'Obrii oznacene korisnike', //248
'Jesi li siguran da deli obrisati vlastiti profil?<br>Sve tvoje poruke i slike ce biti izbrisane iz baze.', //249
'Korisnik ID#={0} je uspjeno obrisan iz baze', //250
'Va profil ce biti obrisan nakon to ga provjeri administrator', //251
'{0} korisnici uspjeno izbaceni iz spavace sobe!', //252
'Lozinke nisu identicne ili ste koristili nedozvoljene znakove', //253
'Nemate mogucnost promjene lozinke', //254
'Krivo unesena stara lozinka. Molim ponovite unos!', //255
'Lozinka je uspje¹no promjenjena!', //256
'Nije moguæe obrisati sve slike', //257
'Spremljene su promjene', //258
' - Obri¹i sliku', //259
'Podatci su izgubljeni. Mo¾ete ugasiti browser', //260
'Slika nije dostupna', //261
'Jezici', //262
'Ulaz', //263
'Login [3-16 znakova [A-Za-z0-9_]]', //264
'Login', //265
'Korisnièko ime mora sadr¾avani izmeðu 3-16 znakova i samo A-Za-z0-9_ te znakove mo¾ete koristiti', //266
'Korisnièko ime veæ postoji. Odaberite drugi!', //267
'Ukupno korisnika - {0}', //268
'The messages are not visible. You should be the privileged user see the messages.<br><br>You can purshase from <a href="'.C_URL.'/members/index.php?l=default&a=r" class=head>here</a>', //269 change l=default to l=this_language_name
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
