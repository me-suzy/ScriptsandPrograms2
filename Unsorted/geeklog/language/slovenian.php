<?php

###############################################################################
# slovenian.php
# language file for geeklog version 1.3.8 
#
# This is the slovenian language page for GeekLog!
# Special thanks to Mischa Polivanov for his work on this project
#
# Prevedel gape@gape.org ; za pripombe, predloge ipd ... pii na email
# med plug-ini e nekaj manjka ... ker ne razumem scene
# Tudi dvojni presledki so pomekje zamnjani z enojnimi
#
# Copyright (C) 2000 Jason Whittenburg
# jwhitten@securitygeeks.com
#
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
#
###############################################################################

###############################################################################
# Array Format:
# $LANGXX[YY]:	$LANG - variable name
#		  	XX - file id number
#			YY - phrase id number
###############################################################################

###############################################################################
# USER PHRASES - These are file phrases used in end user scripts
###############################################################################

###############################################################################
# common.php

$LANG01 = array(
	1 => "Pie:",
	2 => "beri dalje",
	3 => "komentarjev.",
	4 => "uredi",
	5 => "Anketa",
	6 => "rezultati",
	7 => "Rezultati ankete",
	8 => "glasov<br>",
	9 => "Administracijske funkcije:",
	10 => "Èakajoèa vsebina",
	11 => "Èlanki",
	12 => "Bloki/okvirji",
	13 => "Teme",
	14 => "Povezave",
	15 => "Napovednik",
	16 => "Ankete",
	17 => "Uporabniki",
	18 => "SQL poizvedovanje",
	19 => "Izhod iz sistema",
	20 => "Podatki o uporabniku:",
	21 => "Uporabniko ime",
	22 => "Uporabniki ID",
	23 => "Varnostni nivo",
	24 => "Anonimni uporabnik",
	25 => "Odgovori",
	26 => "Za komentarje so odgovorni njihovi avtorji. Avtorji spletne strani na komentarje obiskovalcev nimamo nobenega vpliva.",
	27 => "Zadnjikrat komentirano",
	28 => "Izbrii",
	29 => "Ni komentarjev.",
	30 => "Stareji èlanki",
	31 => "Dovoljeni HTML ukazi:",
	32 => "Napaka, neveljavno uporabniko ime",
	33 => "Napaka, ne morem shranjevati v log datoteko",
	34 => "Napaka",
	35 => "Izhod iz sistema",
	36 => "dne",
	37 => "Ni èlankov",
	38 => "",
	39 => "Osvei",
	40 => "",
	41 => "Gostje",
	42 => "Prispeval/a:",
	43 => "Odgovori na To",
	44 => "Star",
	45 => "MySQL tevilka napake",
	46 => "MySQL sporoèilo o napaki",
	47 => "Prijava uporabnika",
	48 => "Podatki o uporabnikem raèunu",
	49 => "Nastavitve",
	50 => "Napaka v SQL ukazu",
	51 => "pomoè",
	52 => "Novo",
	53 => "Administratorske strani",
	54 => "Ne morem odpreti datoteke",
	55 => "Napaka na",
	56 => "Glasuj",
	57 => "Geslo",
	58 => "Prijava",
	59 => "Niste registriran uporabnik?  <a href=\"{$_CONF['site_url']}/users.php?mode=new\">Registrirajte se</a>.",
	60 => "Dodaj komentar",
	61 => "Nov uporabnik",
	62 => "besed",
	63 => "Nastavitve komentarjev",
	64 => "Polji èlanek prijatelju po e-poti",
	65 => "Stran prijazna za tisk",
	66 => "Osebni koledar",
	67 => "Dobrodoli na ",
	68 => "domov",
	69 => "kontakt",
	70 => "ièi",
	71 => "prispevaj",
	72 => "spletni vir",
	73 => "pretekle ankete",
	74 => "koledar",
	75 => "napredno iskanje",
	76 => "statistika spletne strani",
	77 => "Prikljuèeni moduli",
	78 => "Dogodki",
	79 => "Kaj je novega",
	80 => "Èlankov v zadnjih",
	81 => "Èlanek v zadnjih ",
	82 => "urah",
	83 => "KOMENTARJI BRALCEV<br>",
	84 => "POVEZAVE<br>",
	85 => "v zadnjih 48 urah",
	86 => "ni novih komentarjev",
	87 => "v zadnjih 2 tednih",
	88 => "ni novih povezav",
	89 => "Trenutno ni nobenih prihajajoèih dogodkov.",
	90 => "Na osnovno stran",
	91 => "Spletna stran zgenerirana v",
	92 => "sekundah",
	93 => "Copyright",
	94 => "Vsa naa koda pripada vam.",
	95 => "Gnano z",
	96 => "Uporabnike skupine",
        97 => "Seznam besed",
	98 => "Prikljuèni moduli",
	99 => "ÈLANKI",
	100 => "Ni novih èlankov",
	101 => 'Vai dogodki',
	102 => 'Najavljeno je:',
	103 => 'DB Bekapi',
	104 => 'z',
	105 => 'Email uporabniki',
	106 => 'Ogledov',
	107 => 'GL Test verzije',
	108 => 'Izprazni cache'
);

###############################################################################
# calendar.php

$LANG02 = array(
	1 => "Koledar dogodkov",
	2 => "Trenutno ni v koledar vpisanega nobenega dogodka.",
	3 => "Kdaj",
	4 => "Kje",
	5 => "Opis",
	6 => "Dodaj dogodek",
	7 => "Prihajajoèi dogodki",
	8 => 'Ko boste dodali nov dogodek v va osebni koledar, lahko te dogodke pregledate s klikom na "Osebni koledar" v meniju "Osebne nastavitve".',
	9 => "Dodaj v Osebni koledar",
	10 => "Odstrani iz Osebnega koledarja",
	11 => "Dodajanje dogodka v Osebni koledar uporabnika {$_USER['username']}",
	12 => "Dogodek",
	13 => "Zaèetek dogodka",
	14 => "Konec dogodka",
	15 => "nazaj na koledar"
);

###############################################################################
# comment.php

$LANG03 = array(
	1 => "Poiljanje komentarja",
	2 => "Oblika besedila",
	3 => "Odjava",
	4 => "Registrirajte se",
	5 => "Uporabniko ime",
	6 => "Èe elite posredovati komentar se morate registrirati. Èe e nimate svojega uporabnikega imena in gesla, kliknite spodaj.",
	7 => "Va zadnji komentar je bil pred",
	8 => " sekundami.  Med posameznimi komentarji bralcev mora preteèi vsaj {$_CONF["commentspeedlimit"]} sekund",
	9 => "Komentar",
	10 => '',
	11 => "Polji komentar",
	12 => "Èe elite posredovati komentar, vpiite vae ime, e-mail naslov, naslov komentarja in vsebino komentarja.",
	13 => "Vae informacije",
	14 => "Predogled",
	15 => "",
	16 => "Naslov",
	17 => "Napaka",
	18 => 'Pomembno',
	19 => 'Prosimo da se skuate drati teme objavljenega èlanka.',
	20 => 'Zaeleno je argumentiranje vaih trditev.',
	21 => 'Preberite komentarje ostalih - morda je kdo e napisal kaj kar nameravate napisati tudi vi.',
	22 => 'Pazite na pravilno slovnico in se izogibajte alitvam drugih.',
	23 => 'Va e-mail naslov ne bo javno objavljen.',
	24 => 'Anonimni uporabnik'
);

###############################################################################
# users.php

$LANG04 = array(
	1 => "Uporabnik",
	2 => "Uporabniko ime",
	3 => "Ime",
	4 => "Geslo",
	5 => "Elektronski naslov",
	6 => "Spletna stran",
	7 => "Opis",
	8 => "PGP javni kljuè",
	9 => "Shrani podatke",
	10 => "Zadnjih 10 komentarjev uporabnika",
	11 => "Uporabnik ni prispeval nobenih komentarjev",
	12 => "Uporabnike nastavitve za",
	13 => "Email Nightly Digest",
	14 => "Geslo se generira nakljuèno. Priporoèamo vam, da geslo takoj spremenite. To storite s klikom na Informacije o raèunu v  meniju Osebne nastavitve. Za spremembe osebnih nastavitev se morate prijaviti v sistem.",
	15 => "Va uporabniki raèun na spletni strani je bil uspeno vzpostavljen. Èe ga elite uporabljati, se morate prijaviti s spodnjimi podatki. Svetujemo vam da podatke o vaem uporabnikem raèunu shranite.",
	16 => "Informacije o vaem uporabnikem raèunu",
	17 => "Uporabniki raèun ne obstaja",
	18 => "Vneeni e-mail naslov ni veljaven",
	19 => "Vneeno uporabniko ime ali e-mail naslov sta e uporabljena na tem sistemu",
	20 => "Vneeni e-mail naslov ni veljaven",
	21 => "Napaka",
	22 => "Registracija {$_CONF['site_name']}!",
	23 => "Registracija vam omogoèa èlanstvo na nai spletni strani{$_CONF['site_name']}. To pomeni da boste lahko objavljali komentarje, poiljali svoje èlanke, dostopali in dodajali zapiske in bili deleni vseh pomembnih informacij. Èe se ne registrirate, boste na spletni strani lahko sodelovali le kot anonimni uporabnik. <font color=red>Elektronski naslovi registriranih uporabnikov <b><i>ne bodo javno objavljeni</i></b>.",
	24 => "Geslo vam bomo poslali na e-mail naslov, ki ste ga vnesli.",
	25 => "Ste pozabili geslo?",
	26 => "Vnesite <em>uporabniko ime</em> <em>ali</em> email naslov s katerim ste se registrirali in kliknite spodnji gumb (Polji geslo po emailu). Navodila kako nastaviti novo geslo vam bomo sporoèili na registrirani e-mail naslov.",
	27 => "Registrirajte se!",
	28 => "Polji geslo na e-mail naslov",
	29 => "odjava",
	30 => "prijava",
	31 => "Za izvedbo te funkcije se morate prijaviti",
	32 => "Podpis",
	33 => "Never publicly displayed",
	34 => "Vae pravo ime",
	35 => "Vpii spremenjeno geslo",
	36 => "Zaène z http://",
	37 => "Nastavitve komentarjev",
	38 => "To lahko prebere vsakdo",
	39 => "PGP javni klju?",
	40 => "Brez ikone teme",
	41 => "Pripravljen moderirati",
	42 => "Format datuma",
	43 => "Najveè èlankov",
	44 => "Brez okvirjev",
	45 => "Prikai nastavitve za",
	46 => "Izkljuèeni deli",
	47 => "Nastavitve novosti za",
	48 => "Teme",
	49 => "Brez ikon v èlankih",
	50 => "Èe vas ne zanima, odznaèite",
	51 => "Samo novi èlanki",
	52 => "Privzeta nastavitev je 10",
	53 => "Prejmi nove èlanke vsako noè",
	54 => "Oznaèi teme in avtorje, katerih prispevki vas ne zanimajo",
	55 => "Èe pustite prazno, se bodo ohranile privzete nastavitve. Èe zaènete izbirati bloke/okvirje, se bodo prikazali samo tisti, ki ste jih izbrali (brez privzetih). Privzete nastavitve so <B> povdarjene</B>.",
	56 => "Avtorji",
	57 => "Naèin prikaza",
	58 => "Uredi po",
	59 => "Omejitve komentarja",
	60 => "Kaken naèin izpisa elite za svoje komentarje",
	61 => "Najprej novi ali stari?",
	62 => "Prevzeta vrednost je 100",
	63 => "Geslo je bilo poslano na va e-mail naslov. Sledite navodilom, ki jih boste dobili. Hvala ker uporabljate spletno stran " . $_CONF["site_name"],
	64 => "Nastavitve komentarjev za",
	65 => "Poizkusite se ponovno prijaviti",
	66 => "Morda ste se zmotili pri vnosu svojega uporabnikega imena ali gesla? Poizkusite se ponovno prijaviti. Ste morda e neregistriran uporabnik - v tem primeru se <a href=\"{$_CONF['site_url']}/users.php?mode=new\">registrirajte</a>.",
	67 => "Uporabnik od",
	68 => "Spomni me na",
	69 => "Kako dolgo naj si te zapomnim po zadnji prijavi?",
	70 => "Priredi izgled spletne strani {$_CONF['site_name']}",
	71 => "Ena od posebnosti spletne strani{$_CONF['site_name']} je popolna monost prilagajanja izgleda posameznemu uporabniku.  Èe elite uporabiti to monost se morate najprej <a href=\"{$_CONF['site_url']}/users.php?mode=new\">prijaviti</a> na {$_CONF['site_name']}.  Ali ste e postali registrirani uporabnik?  Prijavite se!",
	72 => "Tema",
	73 => "Jezik",
	74 => "Spremeni izgled strani!",
	75 => "Teme po e-mailu",
	76 => "Nove èlanke iz izbranih podroèij boste ob koncu vsakega dneva prejeli po e-mailu. Izberite teme, ki vas zanimajo!",
	77 => "Slika",
	78 => "Dodaj svojo sliko!",
	79 => "Izbrii izbrano sliko",
	80 => "Vpis",
 	81 => "Polji Email",
	82 => 'Zadnjih 10 èlankov uporabnika',
	83 => 'Statistika objavljanja uporabnika',
	84 => 'Skupno tevilo èlankov:',
	85 => 'Skupno tevilo komentarjev:',
	86 => 'Najdi vse objave uporabnika',
	87 => 'Vae uporabniko ime',
	88 => 'Nekdo (verjetno ti) je zahteval novo geslo za tvoj raèun  "%s" na ' . $_CONF['site_name'] . ', <' . $_CONF['site_url'] . ">.\n\nÈe res eli novo geslo, prosim klikni povezavo :\n\n",
	89 => "Èe noèe novega gesla, preprosto ignoriraj to sporoèilo in zahtevo bomo zavrgli (tvoje geslo bo ostalo nespremenjeno).\n\n",
	90 => 'Spodaj lahko vpie novo geslo za svoj raèun. Prosim, vedi, da je staro geslo e vedno veljavno, dokler ne  odpolje tega obrazca.',
	91 => 'Nastavljanje novega gesla',
	92 => 'Vpii novo geslo',
	93 => 'Tvoja zadnja zahteva za novo geslo je bila sprejeta pred %d sekundami. Te strani zahtevajo vsaj %d sekund med spremembami gesla.',
	94 => 'Izbrii raèun "%s"',
	95 => 'Klikni gumb "Izbrii raèun" spodaj za izbris svojega uporabnikega raèuna iz nae baze podatkov. Vedi, da bodo vsi prispevki, ki si jih prispeval/a pod tem raèunom <strong>ostali</strong> na naih straneh. Uporabnik, ki jih je prispeval pa bo postal anonimne.',
	96 => 'Izbrii raèun',
	97 => 'Potrdi brisanje raèuna',
	98 => 'Si preprièan da eli pobrisati svoj uporabniki raèun. Po tem prijava na nae strani ne bo veè mogoèa (razen, èe si ustvari nov raèun). Èe si preprièan/a ponovno klikni "Izbrii raèun" v spodnjem obrazcu.',
	99 => 'Nastavitve zasebnosti za',
	100 => 'Email od Admina',
	101 => 'Dovoli emaile od upravljalcev strani',
	102 => 'Email od uporabnikov',
	103 => 'Dovoli emaile od drugih uporabnikov strani',
	104 => 'Prikai Online Status',
	105 => 'Dovoli prikaz v bloku Na liniji so'

);

###############################################################################
# index.php

$LANG05 = array(
	1 => "Podroèje je trenutno prazno",
	2 => "Trenutno v tem tematskem podroèju ni nobenega èlanka, ali pa so vae uporabnike nastavitve takne da nimate dostopa do tega podroèja",
	3 => ".",
	4 => "Danaji udarni èlanek",
	5 => "naslednja stran",
	6 => "prejnja stran"
);

###############################################################################
# links.php

$LANG06 = array(
	1 => "Zanimive povezave",
	2 => "V bazo podatkov ni vpisana nobena povezava.",
	3 => "Predlagaj povezavo"
);

###############################################################################
# pollbooth.php

$LANG07 = array(
	1 => "Anketa shranjena",
	2 => "Va glas je bil shranjen",
	3 => "Glasuj",
	4 => "Seznam anket na spletni strani",
	5 => "glasov",
	6 => "Ostale ankete"
);

###############################################################################
# profiles.php

$LANG08 = array(
	1 => "Napaka!!! Poskusite znova.",
	2 => "Sporoèilo je bilo poslano",
	3 => "Uporabite pravi e-mail naslov!",
	4 => "Izpolnite polja: Ime, e-mail za odgovor, Zadeva in vpiite sporoèilo",
	5 => "Napaka. Neznan uporabnik!",
	6 => "Upsss... Napaka ;-).",
	7 => "Uporabnik",
	8 => "Uporabniko ime",
	9 => "URL naslov",
	10 => "e-mail naslov",
	11 => "Ime:",
	12 => "e-mail za odgovor:",
	13 => "Zadeva:",
	14 => "Sporoèilo:",
	15 => "HTML ukazi ne bodo upotevani (HTML will not be translated).",
	16 => "Polji sporoèilo",
	17 => "Polji èlanek prijatelju po e-mailu",
	18 => "Ime prijatelja",
	19 => "Elektronski naslov prijatelja",
	20 => "Vae ime",
	21 => "Va e-mail naslov",
	22 => "Izpolniti je potrebno vsa polja!",
	23 => "To sporoèilo vam je poslal na obiskovalec $from ($fromemail). Upravitelji spletne strani {$_CONF["site_url"]} od koder je bilo sporoèilo poslano, vaih podatkov nismo shranili v nobeno bazo podatkov.",
	24 => "Èlanek se nahaja na spletnem naslovu: ",
	25 => "Èe elite poslati èlanek po e-poti, se morate predhodno registrirati. Registracija je potrebna da prepreèimo morebitne zlorabe sistema.",
	26 => "S pomoèjo tega obrazca boste poslali e-mail izbranemu uporabniku. Izpolniti je potrebno vsa polja!",
	27 => "Kratko spremno sporoèilo",
	28 => "Obiskovalec $from je napisal sledeèe spremno sporoèilo: $shortmsg",
	29 => "Dnevni pregled strani {$_CONF['site_name']} za ",
	30 => "Dnevne novice za",
	31 => "Naslov",
	32 => "Datum",
	33 => "Celotno besedilo:",
    	34 => "Konec sporoèila",
    	35 => 'al, ta uporabnik ne eli prejemati emailov.'
);

###############################################################################
# search.php

$LANG09 = array(
	1 => "Napredno iskanje",
	2 => "Kljuène besede",
	3 => "Tematsko podroèje",
	4 => "vse",
	5 => "Nasvet",
	6 => "Èlanki",
	7 => "komentarji bralcev",
	8 => "Avtorji",
	9 => "vse",
	10 => "Ièi",
	11 => "Rezultati iskanja",
	12 => "zadetki",
	13 => "Iskanje med èlanki: niè zadetkov",
	14 => "Za iskalni pojem",
	15 => "ni nobenega zadetka. Prosimo poizkusite ponovno.",
	16 => "Naslov",
	17 => "datum",
	18 => "avtor",
	19 => "Iskalnik celotne baze podatkov (vsi èlanki in vsi komentarji bralcev)",
	20 => "Datum",
	21 => "do",
	22 => "(format datuma YYYY-MM-DD)",
	23 => "t. zadetkov",
 	24 => "Nael sem %d zadetkov",
 	25 => "Iskal sem",
 	26 => "zadetkov ",
	27 => "sekund.",
	28 => "Za vpisani iskalni pogoj ni bilo najdenega nobenega èlanka niti nobenega komentarja",
	29 => "Rezultati iskanja",
	30 => "Nobena povezava ne ustreza vaemu iskalnemu pogoju. Poizkusite znova!",
	31 => "Ni zadetkov za ta plug-in. Poizkusite znova!",
	32 => "Dogodek",
	33 => "URL",
	34 => "Lokacia",
	35 => "Celodnevno dogajanje",
	36 => "Noben dogodek ne ustreza vaemu iskalnemu pogoju. Poizkusite znova!",
	37 => "Rezultati za dogodke",
	38 => "Rezultati za povezave",
	39 => "Povezave",
	40 => "Dogodki",
	41 => 'Iskalni niz mora imeti vsaj tri znake.',
	42 => 'Datum naj bo formatiran na naèin: YYYY-MM-DD (leto-mesec-dan).',
	43 => 'toèna fraza',
	44 => 'vse besede',
	45 => 'katerakoli beseda',
	46 => 'Naslednji',
	47 => 'Prejnji',
	48 => 'Avtor',
	49 => 'Datum',
	50 => 'Zadetki',
	51 => 'Povezava',
	52 => 'Lokacija',
	53 => 'Rezultati èlankov',
	54 => 'Rezultati komentarjev',
	55 => 'fraza',
	56 => 'AND',
	57 => 'OR'
    
);

###############################################################################
# stats.php

$LANG10 = array(
	1 => "Statistike spletne strani",
	2 => "tevilo vseh obiskov strani",
	3 => "tevilo objavljenih èlankov (komentarjev)",
	4 => "tevilo anket (odgovorov)",
	5 => "tevilo povezav (obiskov)",
	6 => "tevilo napovedanih dogodkov",
	7 => "Deset najbolj branih èlankov",
	8 => "Naslov èlanka",
	9 => "t. ogledov",
	10 => "Na spletni strani ni objavljenih èlankov, ali pa nihèe ni prebral nobenega èlanka.",
	11 => "Deset najbolj komentiranih èlankov",
	12 => "t. komentarjev",
	13 => "Na spletni strani ni objavljenih komentarjev, ali pa nihèe ni objavil nobenega komentarja.",
	14 => "Deset najbolj obiskanih anket",
	15 => "Anketno vpraanje",
	16 => "t. glasov",
	17 => "Na spletni strani ni objavljena nobena anketa, ali pa nihèe ni glasoval pri nobeni anketi.",
	18 => "Deset najbolj obiskanih povezav",
	19 => "Povezave",
	20 => "t. ogledov",
	21 => "Na spletni strani ni objavljena nobena povezava ali pa nihèe ni obiskal nobene povezave.",
	22 => "Deset najbolj po e-mailu posredovanih èlankov",
	23 => "t. posredovanj",
	24 => "Nihèe ni posredoval nobenega èlanka po e-mailu."
);

###############################################################################
# article.php

$LANG11 = array(
	1 => "Sorodne povezave",
	2 => "Polji èlanek po e-poti",
	3 => "Stran prijazna za tisk",
	4 => "Dodatne monosti"
);

###############################################################################
# submit.php

$LANG12 = array(
	1 => "Èe elite objaviti $type se morate prijaviti. e niste registrirani uporabnik? <a href=\"{$_CONF['site_url']}/users.php?mode=new\"> Registrirajte se</a>.",
	2 => "Prijava",
	3 => "Nov uporabnik",
	4 => "Polji dogodek",
	5 => "Polji povezavo",
	6 => "Èlanek",
	7 => "Potrebno se je prijaviti!",
	8 => "Polji",
	9 => "Pri poiljanju informacij se drite naslednjih priporoèil:<ul><li>izpolnite vsa polja,<li>poskrbite da bo informacija, ki jo poiljate toèna in ustrezna,<li>pazite na slovnico,<li>preverite da delujejo vse povezave,<li><font color=red>prosimo da poiljate <b>samo èlanke</b>, komentarje vpisujte pod posamezen èlanek, za splone debate ali mnenja pa uporabite forum.</font></ul>",
	10 => "Naslov",
	11 => "Povezava",
	12 => "Datum zaèetka",
	13 => "Datum zakljuèka",
	14 => "Lokacija",
	15 => "Opis",
	16 => "Èe 'drugo', vpii ime",
	17 => "Kategorija",
	18 => "Drugo",
	19 => "Prosimo, preberite",
	20 => "Napaka: ni doloèena kategorija",
	21 => "Kadar izberete \"Drugo\" vpiite tudi ime kategorije",
	22 => "Napaka: niso izpolnjena vsa polja",
	23 => "Prosimo izpolnite vsa polja obrazca.",
	24 => "Shranjeno!",
	25 => "Va $type prispevek je bil shranjen.",
	26 => "Omejitev hitrosti",
	27 => "Uporabniko ime",
	28 => "Tematsko podroèje",
	29 => "Vsebina èlanka",
	30 => "Vaa zadnja objava je bila poslana pred ",
	31 => " sekundami. Nastavitve te spletne strani pa zahtevajo da med dvema objavama istega avtorja mine vsaj {$_CONF["speedlimit"]} sekund",
	32 => "Predogled",
	33 => "Predogled èlanka",
	34 => "izhod",
	35 => "HTML ukazi ne bodo upotevani",
	36 => "Oblika besedila",
	37 => "Dogodek bo dodan na skupni koledar, od koder ga registrirani uporabniki lahko dodajo na svoj osebni koledar. Ta aplikacija zato <b>NI</b> namenjena shranjevanju osebnih dogodkov (obletnice, rojstni dnevi, zabave...) èe nas ne mislite nanje povabiti ;-)<br><br>Preden bo dogodek objavljen na skupnem koledarju dogodkov, ga bo pregledal administrator!",
	38 => "Dodaj dogodek v",
	39 => "Skupni koledar",
	40 => "Osebni koledar",
	41 => "Èas zakljuèka",
	42 => "Èas zaèetka",
	43 => "Celodnevni dogodek",
	44 => 'Naslov 1',
	45 => 'Naslov 2',
	46 => 'Mesto',
	47 => 'Drava',
	48 => 'Potna tevilka',
	49 => 'Tip dogodka',
	50 => 'Uredi tipe dogodkov',
	51 => 'Lokacija',
	52 => 'Odstrani',
        53 => 'Naredi raèun'
);


###############################################################################
# ADMIN PHRASES - These are file phrases used in end admin scripts
###############################################################################

###############################################################################
# auth.inc.php

$LANG20 = array(
	1 => "Obvezna prijava",
	2 => "Dostop zavrnjen! Vnesli ste napaène podatke.",
	3 => "Napaèno geslo za uporabnika",
	4 => "Uporabniko ime:",
	5 => "Geslo:",
	6 => "Vsi dostopi do administracijske strani se beleijo.<br>Ta del spletne strani lahko uporabljajo samo pooblaèene osebe.<p>",
	7 => "prijava"
);

###############################################################################
# block.php

$LANG21 = array(
	1 => "Ups... ne bo lo. Nimate zahtevanih administratorskih pravic ;-)",
	2 => "Za urejanje tega bloka/okvirja nimate zahtevanih administratorskih pravic",
	3 => "Urejanje blokov/okvirjev",
	4 => "",
	5 => "Ime bloka/okvirja",
	6 => "Tema",
	7 => "Vse",
	8 => "Varnostna raven bloka/okvirja ",
	9 => "Pravila bloka/okvirja ",
	10 => "Tip bloka/okvirja ",
	11 => "Blok/okvir na portalu",
	12 => "Obièajni blok/okvir",
	13 => "Nastavitve bloka/okvirja na portalu",
	14 => "RDF URL",
	15 => "Zadnja RDF osveitev",
	16 => "Nastavitve obièajnega bloka/okvirja ",
	17 => "Komentar",
	18 => "Vnesite ime, varnostno raven in vsebino bloka/okvirja ",
	19 => "Administracija bloka/okvirja ",
	20 => "Ime bloka/okvirja ",
	21 => "Varnostna raven",
	22 => "Tip bloka/okvirja ",
	23 => "Pravila bloka/okvirja ",
	24 => "Tema bloka/okvirja ",
	25 => "Izberite blok/okvir ki ga elite urediti ali odstraniti. Èe elite ustvariti nov blok/okvir, kliknite zgoraj.",
	26 => "Izgled",
	27 => "PHP blok/okvir",
	28 => "Nastavitve PHP bloka/okvirja",
	29 => "Funkcije bloka/okvirja",
	30 => "If you would like to have one of your blocks use PHP code, enter the name of the function above.  Your function name must start with the prefix \"phpblock_\" (e.g. phpblock_getweather).  If it does not have this prefix, your function will NOT be called.  We do this to keep people who may have hacked your Geeklog installation from putting arbitrary function calls that may be harmful to your system.  Be sure not to put empty parenthisis \"()\" after your function name.  Finally, it is recommended that you put all your PHP Block code in /path/to/geeklog/system/lib-custom.php.  That will allow the code to stay with you even when you upgrade to a newer version of Geeklog.",
	31 => "Napaka v PHP bloku/okvirju.  Funkcija $function, ne obstaja.",
	32 => "Napaka. Manjkajoèi podatki",
	33 => "Vnesite URL naslov v datoteko .rdf za blok na portalu",
	34 => "Vnesite naslov in funkcijo PHP bloka/okvirja",
	35 => "Vnesite naslov in vsebino bloka/okvirja",
	36 => "Vnesite vsebino in izberite izgled bloka/okvirja",
	37 => "Napaèno ime funkcije PHP bloka/okvirja",
	38 => "Funkcije PHP bloka/okvirja morajo imeti predpono 'phpblock_' (npr. phpblock_imefunkcije).  Predpona 'phpblock_' je potrebna zaradi varnosti!",
	39 => "Stran",
	40 => "Levo",
	41 => "Desno",
	42 => "Vnesite nastavitve za privzete GeekLog bloke/okvirje",
	43 => "Samo domaèa stran",
	44 => "Dostop zavrnjen",
	45 => "Do tega bloka/okvirja nimate dostopa. Va poskus je bil zabeleen v bazo podatkov! Prosimo, vrnite se na <a href=\"{$_CONF["site_url"]}/admin/block.php\"> stran za administracijo</a>!",
	46 => 'Nov blok/okvir',
	47 => 'Administratorske strani',
	48 => 'Ime bloka/okvirja',
	49 => ' (Presledki niso dovoljeni. Imena blokov/okvirjev ne smejo biti podvojena',
	50 => 'URL za pomoè',
	51 => 'Zaène z http://',
	52 => 'Èe pustite prazno, se ikona za pomoè ne bo izpisala!',
	53 => "Omogoèeno",
	54 => 'Shrani',
	55 => 'Preklièi',
	56 => 'Izbrii'
);

###############################################################################
# event.php

$LANG22 = array(
	1 => "Urejanje dogodkov",
	2 => "",
	3 => "Naslov dogodka",
	4 => "URL dogodka",
	5 => "Zaèetek dogodka",
	6 => "Zakljuèek dogodka",
	7 => "Kraj dogodka",
	8 => "Opis dogodka",
	9 => "(Zaène z http://)",
	10 => "Izpolniti morate vsa polja!",
	11 => "Urejevalnik dogodkov",
	12 => "Izberite dogodek, ki ga elite urediti ali odstraniti. Èe elite objaviti nov dogodek, kliknite na nov dogodek spodaj. Kliknite na [C] èe elite narediti kopijo e obstojeèega dogodka.",
	13 => "Naslov dogodka",
	14 => "Zaèetek",
	15 => "Konec",
	16 => "Dostop zavrnjen",
	17 => "Do tega dogodka nimate dostopa. Va poskus je bil zabeleen v bazo podatkov. Prosimo, vrnite se na <a href=\"{$_CONF["site_url"]}/admin/event.php\"> urejanje dogodkov</a>!",
	18 => 'Nov dogodek',
	19 => 'Administratorske strani',
	20 => 'Shrani',
	21 => 'Preklièi',
	22 => 'Izbrii'
);

###############################################################################
# link.php

$LANG23 = array(
	1 => "Urejanje povezav",
	2 => "",
	3 => "Naslov povezave",
	4 => "URL",
	5 => "Kategorija",
	6 => "(Zaène z http://)",
	7 => "Drugo",
	8 => "Zadetki povezave",
	9 => "Opis povezave",
	10 => "Vnesti morate Naslov povezave, URL in opis.",
	11 => "Urejevalnik povezav",
	12 => "Izberite povezavo, ki ga elite urediti ali odstraniti. Èe elite ustvariti novo povezavo, kliknite zgoraj.",
	13 => "Naslov povezave",
	14 => "Kategorija",
	15 => "URL",
	16 => "Dostop zavrnjen!",
	17 => "Do te povezave nimate dostopa. Va poskus je bil zabeleen v bazo podatkov. Prosimo, vrnite se na <a href=\"{$_CONF["site_url"]}/admin/link.php\"> urejanje povezav</a>.",
	18 => 'Nova povezava',
	19 => 'Administratorske strani',
	20 => 'Èe drugo, kaj',
	21 => 'Shrani',
	22 => 'Preklièi',
	23 => 'Izbrii'
);

###############################################################################
# story.php

$LANG24 = array(
	1 => "Prejnji èlanki",
	2 => "Naslednji èlanki",
	3 => "Nastavitve èlanka",
	4 => "Oblikovanje èlanka",
	5 => "Urejanje èlanka",
	6 => "Ni èlankov v sistemu",
	7 => "Avtor",
	8 => "Shrani",
	9 => "Predogled",
	10 => "Preklièi",
	11 => "Izbrii",
	12 => "",
	13 => "Naslov",
	14 => "tematsko podroèje",
	15 => "datum",
	16 => "Uvodno besedilo",
	17 => "Razirjeno besedilo",
	18 => "t. branj",
	19 => "Komentarjev",
	20 => "",
	21 => "",
	22 => "Seznam èlankov",
	23 => "Èe elite spreminjati ali izbrisati èlanek, kliknite na njegovo tevilko. Èe elite èlanek pregledati, kliknite na njegov naslov. Èe elite objaviti nov èlanek, kliknite na zgornjo povezavo.",
	24 => "",
	25 => "",
	26 => "Predogled èlanka",
	27 => "",
	28 => "",
	29 => "",
	30 => 'Napake pri shranjevanju datoteke',
 	31 => "Prosimo izpolnite polji Naslov in Uvodno besedilo",
	32 => "Udarni èlanek",
	33 => "Udarni èlanek je lahko smo eden",
	34 => "Draft",
	35 => "Da",
	36 => "Ne",
	37 => "Veè od avtorja",
	38 => "Veè iz podroèja",
	39 => "t. posredovanj po e-mailu",
	40 => "Dostop zavrnjen",
	41 => "Poizkuate dostopati do èlanka do katerega nimate dostopa. Ta poizkus neveljavnega dostopa je bil zabeleen in shranjen. èlanek lahko samo preberete, ne morete pa ga urejati. Èe elite lahko vstopite <a href=\"{$_CONF["site_url"]}/admin/story.php\"> na administracijo èlanka</a>.",
	42 => "Poizkuate dostopati do èlanka do katerega nimate dostopa. Ta poizkus neveljavnega dostopa je bil zabeleen in shranjen. Èe elite lahko vstopite <a href=\"{$_CONF["site_url"]}/admin/story.php\">na administracijo èlankov</a>.",
	43 => 'Nov èlanek',
	44 => 'Administracijska stran',
	45 => 'Dostop',
	46 => "<b>POZOR:</b> va prispevek ne bo objavljen do izbranega datuma.Do tega datuma tudi na bo v vaem RDF in ne bo vkljuèn v iskalnik.",
	47 => 'Slike',
	48 => 'slika',
	49 => 'desno',
	50 => 'levo',
	51 => "Sliko vstavite v èlanek s posebnim ukazom [slikaX], [slikaX_desno] or [slikaX_levo], kjer je X tevilka slike v prilogi. POZOR: uporabite lahko samo slike iz priloge. V nasprotnem primeru èlanka ne bo mogoèe objaviti. <BR><P><B>PREDOGLED</B>: Predogled èlanka s slikami najenostavneje opraviter tako, da èlanek shranite kot draft in ne uporabite gumba Predogled. Funkcija Predogled deluje deluje le, èe èlanek brez slik.",
	52 => "Odstrani",
	53 => "ni bila uporabljena. Sliko morate pred shranjevanjem vkljuèiti v uvod ali med besedilo èlanka.",
	54 => "Priloene slike niso bile uporabljene",
	55 => "Napaka pri shranjevanju èlanka. Prosimo popravite napake na spodnjem seznamu:",
	56 => "Prikai ikono teme",
	57 => 'Oglej si nezmanjano sliko'
);

###############################################################################
# poll.php

$LANG25 = array(
	1 => "Naèin",
	2 => 'Prosim vpii vpraanje in vsaj en odgovor.',
	3 => "Ustvarjena anketa",
	4 => "Anketa $qid shranjena",
	5 => "Urejanje anketa",
	6 => "ID ankete",
	7 => "(ne uporabljajte presledkov)",
	8 => "Objavi anketo",
	9 => "Vpraanje",
	10 => "Odgovorov / Glasov",
	11 => "Napaka pri odgovoru na anketo $qid",
	12 => "Napaka pri vpraanju na anketo $qid",
	13 => "Ustvari anketo",
	14 => 'Shrani',
	15 => 'Preklièi',
	16 => 'Izbrii',
	17 => 'Prosim vpii ID ankete',
	18 => "Seznam anket",
	19 => "Izberite anketo, ki jo elite urediti ali odstraniti. Èe elite ustvariti novo anketo, kliknite zgoraj.",
	20 => "Glasov",
	21 => "Prepovedan dostop!",
	22 => "Do te povezave nimate dostopa. Va poskus je bil zabeleen in shranjen. Prosimo, vrnite se na <a href=\"{$_CONF["site_url"]}/admin/poll.php\"> administracijo anket.</a>.",
	23 => 'Nova anketa',
	24 => 'Administratorske strani',
	25 => 'Da',
	26 => 'Ne'
);

###############################################################################
# topic.php

$LANG27 = array(
	1 => "Urejanje tem",
	2 => "ID teme",
	3 => "Naslov teme",
	4 => "Slika teme",
	5 => "(ne uporabljajte presledkov)",
	6 => "Èe odstranite temo odstranite tudi vse èlanke in bloke/okvirje ki so z njo povezani",
	7 => "Izpolnite ID teme in naslov teme.",
	8 => "Urejevalnik tem",
	9 => "Izberite temo, ki jo elite urediti ali odstraniti. Èe elite ustvariti novo temo, kliknite gumb Nova tema, na levi. V vsaki temi najde, v oklepajih, svoj nivo dostopa. Zvazdica (*) oznaèuje privzeto temo.",
	10=> "Uredi po",
	11 => "Èlankov/Strani",
	12 => "Dostop zavrnjen!",
	13 => "Do te teme nimate dostopa. Va poskus je bil zabeleen in shranjen. Prosimo, vrnite se na <a href=\"{$_CONF["site_url"]}/admin/topic.php\"> administracijo tem</a>!",
	14 => "Uredi po",
	15 => "abecedi",
	16 => "privzeto",
	17 => "Nova tema",
	18 => "Administratorske strani",
	19 => 'Shrani',
	20 => 'Preklièi',
	21 => 'Izbrii',
	22 => 'Privzeto',
	23 => 'Ustvari to temo privzeto za novo oddane èlanke',
	24 => '(*)'
);

###############################################################################
# user.php


$LANG28 = array(
	1 => "Urejanje uporabnika",
	2 => "ID uporabnika",
	3 => "uporabniko ime",
	4 => "ime uporabnika",
	5 => "Geslo",
	6 => "Varnostni nivo",
	7 => "e-mail naslov",
	8 => "Vaa spletna stran",
	9 => "(ne uporabljajte presledkov)",
	10 => "Prosimo vpiite uporabniko ime in e-mail.",
	11 => "Administracija uporabnikov",
	12 => "Èe elite spremeniti ali izbrisati uporabnika, kliknite na njegovo uporabniko ime spodaj. Èe elite ustvariti novega uporabnika, kliknite gumb Nov uporabnik na levi. V spodnje okence vpiite iskalni niz, ièete lahko po imenu, delu imena, emailu ali celotnem imenu (npr. *sin* ali *.si*).",
	13 => "Varnostni nivo",
	14 => "Datum registracije",
	15 => 'nov uporabnik',
	16 => 'Administratorske strani',
	17 => 'spremeni geslo',
	18 => 'prekini',
	19 => 'izbrii',
	20 => 'shrani',
	18 => 'prekini',
	19 => 'izbrii',
	20 => 'shrani',
	21 => 'Uporabniko ime, ki ga elite hraniti e obstaja.',
	22 => 'Napaka',
	23 => 'Zaporedno dodajanje',
	24 => 'Zaporedno dodajanje uporabnikov',
	25 => 'Uvozi lahko zaporedje uporabnikov v Geekloga.  Datoteka za uvoz mora biti razdeljena s tabi in mora biti navadna tekstovna datoteka. Polja naj si sledijo v naslednjem vrstnem redu: polno ime, uporabniko ime, email naslov.  Vsak uporabnik bo prejel email z nakljuènim geslom. Vsak uporabnik naj bo vpisan v svoji vrstici. Èe ne upoteva teh navodil, lahko nastanejo precejnje teave, ki bodo zahtevale roèno delo, zato, e enkrat preveri vpise v datoteki preden jo zaène uvaati!',
	26 => "Ièi",
	27 => "Omejitve iskanja",
	28 => "Izbrii sliko",
        29 => 'Pot',
	30 => 'Uvozi',
	31 => 'Novi uporabniki',
	32 => 'Konèano. Uvoeno $successes in $failures napak',
	33 => 'polji',
	34 => 'Napaka: Mora doloèiti datoteko.',
	35 => 'Zadnji vpis',
	36 => '(nikoli)'


);


###############################################################################
# moderation.php

$LANG29 = array(
	1 => "Objavi",
	2 => "Izbrii",
	3 => "Uredi",
	4 => 'Profil',
	10 => "Naslov",
	11 => "Datum zaèetka",
 	12 => "URL",
	13 => "Kategorija",
 	14 => "Datum",
    	15 => "Tema",
    	16 => 'Uporabniko ime',
    	17 => 'Popolno ime',
    	18 => 'E-mail',
	34 => "Administratorske strani",
	35 => "Èakajoèi èlanki",
	36 => "Èakajoèe povezave",
	37 => "Èakajoèi dogodki",
	38 => "Potrdi",
	39 => "Trenutno v tem podroèju ni nobene èakajoèe vsebine.",
    	40 => "Uporabniki so poslali"
);

###############################################################################
# calendar.php

$LANG30 = array(
	1 => "nedelja",
	2 => "ponedeljek",
	3 => "torek",
	4 => "sreda",
	5 => "èetrtek",
	6 => "petek",
	7 => "sobota",
	8 => "Dodaj dogodek",
	9 => '%s Dogodek',
	10 => "Dogodki za",
	11 => "Skupni koledar",
	12 => "Osebni koledar",
	13 => "januar",
	14 => "februar",
	15 => "marec",
	16 => "april",
	17 => "maj",
	18 => "junij",
	19 => "julij",
	20 => "avgust",
	21 => "september",
	22 => "oktober",
	23 => "november",
	24 => "december",
	25 => "Nazaj na ",
	26 => "Ves dan",
    	27 => "teden",
	28 => "Osebni koledar za uporabnika",
	29 => "Skupni koledar",
	30 => "izbrii dogodek",
	31 => "Dodaj",
	32 => "Dogodek",
	33 => "Datum",
	34 => "Èas",
	35 => "Hitro dodajanje",
	36 => "Polji",
	37 => "al ta spletna stran ne omogoèa upravljanja osebnih koledarjev",
	38 => "Urejanje osebnega koledarja",
        39 => 'Dan',
	40 => 'Teden',
	41 => 'Mesec'
);

###############################################################################
# admin/mail.php

$LANG31 = array(
 	1 => 
 	$_CONF['site_name'] . " Mail Utility",
 	2 => "Od:",
 	3 => "Naslov za odgovor:",
 	4 => "Zadeva:",
 	5 => "Sporoèilo:",
 	6 => "Naslov:",
 	7 => "Vsem uporabnikom",
 	8 => "Admin",
	9 => "Nastavitve",
	10 => "HTML",
 	11 => "Nujno sporoèilo!",
 	12 => "Polji",
 	13 => "Zbrii",
 	14 => "Prezri uporabnike nastavitve",
 	15 => "Napaka pri poiljanju: ",
	16 => "Sporoèilo je bilo uspeno poslano na naslov: ",
	17 => "<a href=" . $_CONF["site_url"] . "/admin/mail.php>Polji novo sporoèilo</a>",
	18 => "Za:",
	19 => "POZOR: èe elite poslati sporoèilo vsem uporabnikom strani, izberite skupino Prijavljeni uporabniki iz menija Uporabniki",
	20 => "Uspeno poslana sporoèila: <successcount>;<BR>Neuspeno poslana sporoèila: <failcount>. Podrobnosti o neuspeno poslanih sporoèilh najdete spodaj.<BR><BR>elite poslati e kakno <a href=\"" . $_CONF['site_url'] . "/admin/mail.php\">sporoèilo</a>? <BR><BR>Nazaj na <a href=\"" . $_CONF['site_url'] . "/admin/moderation.php\">administratorkse strani</a>.",
	21 => "Neuspeno poslana sporoèila",
	22 => "Uspeno poslana sporoèila",
	23 => "Nobenega neuspeno poslanega sporoèila",
	24 => "Nobenega uspeno poslanega sporoèila",
	25 => '-- Izberi skupino --',
	26 => "Prosim izpolni vsa polja v obrazcu in izberi skupino uporabnikov iz menija."
);


###############################################################################
# confirmation and error messages

$MESSAGE = array (
	1 => "Geslo smo vam poslali na va e-mail naslov. Elektronsko sporoèilo vsebuje tudi vsa ustrezna navodila.",
	2 => "Za poslani èlanek se vam najlepe zahvaljujemo. Pred objavo ga bo pregledal eden izmed urednikov. Èe bo odobren, bo objavljen na spletni strani.",
	3 => "Za poslano povezavo se vam najlepe zahvaljujemo. Pred objavo jo bo pregledal eden izmed urednikov. Èe bo odobrena, bo objavljena na strani <a href={$_CONF["site_url"]}/links.php>povezav</a>.",
	4 => "Za poslani dogodek se vam najlepe zahvaljujemo. Pred objavo ga bo pregledal eden izmed urednikov. Èe bo odobren, bo objavljen v <a href={$_CONF["site_url"]}/calendar.php>koledarju dogodkov</a>.",
	5 => "Podatki o vaem uporabnikem raèunu so bili shranjeni.",
	6 => "Nastavitve vaega zaslona so bile uspeno shranjene.",
	7 => "Vae nastavitve komentarjev so bile uspeno shranjene.",
	8 => "Odjava je bila uspena.",
	9 => "èlanek je bil uspeno shranjen.",
	10 => "èlanek je bil izbrisan.",
	11 => "Vai bloki/okvirji so bili uspeno shranjeni.",
	12 => "Blok/okvir je bil izbrisan.",
	13 => "Tematsko podroèje je bilo uspeno shranjeno.",
	14 => "Tematsko podroèje skupaj z vsemi èlanki v njem z je bilo izbrisano.",
	15 => "Povezava je bila uspeno shranjena v bazo povezav.",
	16 => "Povezava je bila izbrisana.",
	17 => "Dogodek je bil uspeno shranjen v bazo dogodkov.",
	18 => "Dogodek je bil izbrisan.",
	19 => "Anketa je bila uspeno shranjena.",
	20 => "Anketa je bila izbrisana.",
	21 => "Uporabnike nastavitve za novega uporabnika so bile uspeno shranjene.",
	22 => "Uporabnik je bil izbrisan.",
	23 => "Napaka pri dodajanju dogodka v koledar dogodkov. Vnesite ID dogodka.",
	24 => "Dogodek je bil uspeno shranjen v va koledar dogodkov",
	25 => "Dostop do osebnega koledarja je mogoè le èe ste prijavljeni.",
	26 => "Dogodek je bil odstranjen iz vaega osebnega koledarja dogodkov",
	27 => "Sporoèilo je bilo poslano.",
	28 => "Vtiènik je bil uspeno shranjen",
	29 => "al ta stran ne podpira osebnega koledarja.",
	30 => "Dostop zavrnjen",
	31 => "al nimate dostopa do administracije èlankov. Vsi poizkusi nedovoljenega dostopa se beleijo!",
	32 => "al nimate dostopa do administracije tematskih podroèij. Vsi poizkusi nedovoljenega dostopa se beleijo!",
	33 => "al nimate dostopa do administracije blokov/okvirjev. Vsi poizkusi nedovoljenega dostopa se beleijo!",
	34 => "al nimate dostopa do administracije povezav. Vsi poizkusi nedovoljenega dostopa se beleijo1",
	35 => "al nimate dostopa do administracije dogodkov. Vsi poizkusi nedovoljenega dostopa se beleijo!",
	36 => "al nimate dostopa do administracije anket. Vsi poizkusi nedovoljenega dostopa se beleijo!",
	37 => "al nimate dostopa do administracije uporabnikov. Vsi poizkusi nedovoljenega dostopa se beleijo!",
	38 => "al nimate dostopa do administracije prikljuènih modulov. Vsi poizkusi nedovoljenega dostopa se beleijo!",
	39 => "al nimate dostopa do administracije elektronske pote. Vsi poizkusi nedovoljenega dostopa se beleijo!",
	40 => "Sistemsko sporoèilo",
	41 => "al nima dostopa do strani za menjavo besed. Vsi poizkusi nedovoljenega dostopa se beleijo!",
	42 => "Beseda je bila uspeno shranjena.",
	43 => "Beseda je bila uspeno pobrisana.",
	44 => 'Vtiènik je bil uspeno nameèen!',
	45 => 'Vtiènik je bil odstranjen.',
	46 => "al nimate dostopa do bekapa baze. Vsak poskus nedovoljenega dostopa se belei",
	47 => "To deluje samo pod operacijskim sistemom *nix.  Èe uporablja *nix za svoj operacijski sistem, potem je bil cache uspeno izpraznjen. Èe uporablja Windows OS, bo moral poiskati datoteke po imenu adodb_*.php in jih pobrisati roèno.",
	48 => 'Hvala ker ste se prijavili za èlanstvo v ' . $_CONF['site_name'] . '. Nae osebje vam bo sporoèilo odgovor v kratkem. Èe boste sprejeti, vam bomo poslali vae geslo email naslov, ki te ga ravnokar napisali.',
	49 => "Skupina je bila uspeno shranjena.",
	50 => "Skupina je bila uspeno izbrisana.",
	51 => 'To uporabniko ime je e zasedeno. Prosim izberi drugo.',
	52 => 'Vpisani email ne izgleda kot pravi email naslov.',
	53 => 'Tvoje novo geslo je sprejeto. Prosim uporabi to novo geslo spodaj za vpis v sistem.',
	54 => 'Tvoja zahteva za novo geslo je potekla. Prosim poizkusi ponovno spodaj.',
	55 => 'Poslal sem Email. Na tvoj naslov bi moral priti takoj. Prosim upotevaj navodila v tem emailu, za izbiro novega gesla za svoj raèun.',
	56 => 'Vpisani email je e uporabljen za enega od raèunov na nai bazi podatkov.',
	57 => 'Tvoj raèun je bil uspeno pobrisan.'
);



// for plugins.php


$LANG32 = array (
	1 => "Instalacija vtiènikov (plugin) lahko povzroèi kodo tvoji instalaciji Geekloga, lahko tudi tvojemu sistemu. Pomemno je, da instalira samo vtiènike, ki jih pretoèi z <a href=\"http://www.geeklog.net\" target=\"_blank\">Geeklogove domaèe strani</a> saj smo samo te dodobra preizkusili na razliènih operacijskih sistemih. Pomembno je razumeti, da je instalacija vtiènikov proces, ki zahteva izvajanje nekaterih komand na nivoju datoteènega sistema. Te lahko vodijo do varnostnih lukenj, posebno èe uporablja vtiènik iz strani tretjih oseb. Tudi s tem opozorilom, ne garantiramo uspeh katerekoli instalacije niti nismo odgovorni za kodo povzroèeno z instalacijo vtiènika za Geeklog. Z drugimi besedami instalira na svojo lastno odgovornost. Navodila za roèno instalacijo vtiènika so vkljuèena v paketu vsakega vtiènika.",
	2 => "Plug-in Installation Disclaimer",
	3 => "Plug-in Installation Form",
	4 => "Plug-in File",
	5 => "Spisek vticnikov",
	6 => "Opozorilo: Plug-in je e nameèen!",
	7 => "Vtiènik ki ga skua namestiti je e nameèen. Prosim pobrii stari vtiènik pred ponovno instalacijo",
	8 => "Test kompatibilnosti vtiènika ni uspel",
	9 => "Ta vtiènik zahteva novejo verzijo Geekloga. Namesti novo razlièico <a href=\"http://www.geeklog.net\">Geekloga</a> ali najdi novejo verzijo vtiènika.",
	10 => "<br><b>Trenutno ni nameèenih nobenih vtiènikov.</b><br><br>",
	11 => "Za spremembo ali izbris vtiènika, klikni njegovo tevilko spodaj. Za veè informacij o vtièniku, klikni njegovo ime in preusmerjen bo na njegovo domaèo stran. Za instalacijo ali nadgradnjo vtiènika, klikni na gumb Nov vtiènik zgoraj.",
	12 => 'nobeno ime vtiènika ni bilo poslano v plugineditor()',
	13 => 'Urejevalnik vtiènikov(Plugin Editor)',
	14 => 'Nov vtiènik',
	15 => 'Administratorske strani',
	16 => 'Ime vtiènika',
	17 => 'Verzija vtiènika',
	18 => 'Verzija Geekloga',
	19 => 'Omogoèen',
	20 => 'Da',
	21 => 'Ne',
	22 => 'Namesti',
	23 => 'Shrani',
	24 => 'Preklièi',
	25 => 'Odstrani',
	26 => 'Ime Plug-ina',
	27 => 'Plug-in Homepage',
	28 => 'Verzija Plug-in',
	29 => 'Verzija Geekloga',
	30 => 'Odstrani Plug-in?',
	31 => 'Ali ste preprièani da elite odstraniti ta plug-in?  S tem bodo iz baze izbrisani tudi vsi podatki, ki jih uporablja ta plug-in.'
);

$LANG_ACCESS = array(
	access => "Dostop",
    	ownerroot => "Lastnik/Root",
    	group => "Skupina",
    	readonly => "Samo za branje",
	accessrights => "Pravice dostopa",
	owner => "Lastnik",
	grantgrouplabel => "Dovoli zgornji skupini pravice spreminjanja",
	permmsg => "OBVESTILO: uporabniki so vsi prijavljeni èlani, anonimni uporabniki in vsi trenutni neprijavljeni obiskovalci strani.",
	securitygroups => "Varnostne skupine",
	editrootmsg => "Èeprav ste administrator, ne morete spreminjati nastavitev root uporabnika, èe sami niste root uporabnik. Lahko pa urejate nastavitve vseh ostalih uporabnikov. Vsi poizkusi nedovoljenega dostopa se beleijo. Vrnite se na <a href=\"{$_CONF["site_url"]}/admin/users.php\">Administratorske strani</a>.",
	securitygroupsmsg => "Izberite skupine, katerim naj pripada uporabnik",
	groupeditor => "Urejanje skupin",
	description => "Opis",
	name => "Ime",
 	rights => "Pravice",
	missingfields => "Manjkajoèa polja",
	missingfieldsmsg => "Vpiite ime in opis skupine!",
	groupmanager => "Administracija skupin",
	newgroupmsg => "Izberite skupinpo, ki jo eleite odstraniti skupino ali spremeniti njene nastavitve. Èe elite oblikovati novo skupino, kliknite na Nova skupina zgoraj! Osnovnih skupin ni mogoèe odstraniti!",
	groupname => "Ime skupine",
	coregroup => "Osnovna skupina",
	yes => "Da",
	no => "Ne",
	corerightsdescr => "Ta skupina je osnovna skupina {$_CONF["site_name"]}. Pravice te skupine ne morejo biti spremenjene. Pravice, ki pripadajo tej skupini so na spodnjem seznamu!",
	groupmsg => "Varnost skupin na tej strnai je urejena hierarhièno. Vsaka podskupina ima enake pravice kot njena nadskupina. Zato je priporoèljiva uporaba podskupin. Èe skupina potrebuje posebne pravice, jih izberite v meniju 'Pravice'.",
	coregroupmsg => "Ta skupina je osnovna skupina strani {$_CONF["site_name"]}. Nastavitve skupin, ki ji pripadajo zato ne morejo biti spremenjene. Spodnji seznam vsebuje imena skupin, ki ji pripadajo.",
	rightsdescr => "Pravice, ki jih doloèite skupini, so avtomatsko dodeljene tudi vsem njenim podskupinam. Pravice lahko dodajate tako, da jih izberete iz spodnjega seznama. Èe doloèena pravica nima 'checkboxa' pomeni, da ji je ta pravica dodeljena, ker je podskupina neke druge skupine.",
	lock => "Zakleni",
	members => "èlani",
	anonymous => "Anonimni uporabnik",
	permissions => "Pravice",
	permissionskey => "R = branje, E = spreminjanje, pravice spreminjanja predpostavljajo pravice branja",
	edit => "Uredi",
	none => "Nihèe",
	accessdenied => "Dostop zavrnjen!",
	storydenialmsg => "al nimate dostopa do tega èlanka. Morda zato, ker niste registrirani uporabnik {$_CONF["site_name"]}? <a href=users.php?mode=New> Registrirajte se!</a> in dobili boste popoln dostop do strani {$_CONF["site_name"]}",
	eventdenialmsg => "al nimate dostopa do tega dogodka. Morda zato, ker niste registrirani uporabnik {$_CONF["site_name"]}? <a href=users.php?mode=New> Registrirajte se!</a> in dobili boste popoln dostop do strani {$_CONF["site_name"]}",
	nogroupsforcoregroup => "Ta skupina ne pripada nobeni od preostalih skupin",
	grouphasnorights => "Ta skupina nima nobenih administratorskih pravic.",
	Newgroup => 'Nova Skupina',
	adminhome => 'Administratorske strani',
	save => 'Shrani',
	cancel => 'Preklièi',
	delete => 'Izbrii',
	canteditroot => 'Poizkusili ste urejati nastavitve glavne skupine. Ker niste èlan glavne skupine je va dostop zavrnjen. Èe mislite, da je to napaka, kontatkirajte administratorja sistema.',
	listusers => 'Izpii uporabnike',
	listthem => 'Izpis',
	usersingroup => 'Uporabniki v skupini %s'
);

#admin/word.php
$LANG_WORDS = array(
    	editor => "Urejevalnik zamenjave besed",
    	wordid => "ID besede",
    	intro => "Za spreminjanje ali brisanje besede, kliknite na to besedo. Za ustvarjanje nove nadomestitvene besede kliknite gumb Nova beseda na levi.",
    	wordmanager => "Urejevalnik besed",
    	word => "Beseda",
    	replacmentword => "Nadomestna beseda",
    	Newword => "Nova beseda"
);

$LANG_DB_BACKUP = array(
    	last_ten_backups => "Zadnjih 10 backupov",
    	do_backup => 'Naredi backup',
    	backup_successful => 'Bravo. Baza podatkov se je uspeno bekapirala ;-).',
    	no_backups => 'Sistem nima backupa',
    	db_explanation => "Èe elite narediti backup vaega Geeklog sistema, kliknite gumb spodaj",
    	not_found => "Napaèna pot ali mysqldump utility nima izvrevalnih pravic.<br>Preveri <strong>\$_DB_mysqldump_path</strong> definicijo v config.php.<br>Spremenljivka je trenutno definirana kot: <var>{$_DB_mysqldump_path}</var>",
    	zero_size => 'Backup neuspeen: Velikost datoteka je bila 0 bytov',
    	path_not_found => "{$_CONF['backup_path']} ne obstaja ali pa ni direktorij",
    	no_access => "NAPAKA: Direktorij {$_CONF['backup_path']} ni dosegljiv.",
    	backup_file => 'Backup datoteka',
    	size => 'Velikost',
    	bytes => 'Bytov',
	total_number => 'Skupno tevilo bekapov: %d'

);

$LANG_BUTTONS = array(
    	1 => "Domov",
    	2 => "Kontakt",
    	3 => "Objavi",
    	4 => "Povezave",
    	5 => "Ankete",
    	6 => "Koledar",
    	7 => "Statistika strani",
    	8 => "Osebne nastavitve",
    	9 => "Ièi",
    	10 => "Napredno iskanje"
);

$LANG_404 = array(
    	1 => "Napaka 404",
    	2 => "Ups, pogledal sem povsod, ampak ne najdem <b>%s</b>.",
    	3 => "<p>al mi je, ampak, datoteka ki jo zahteva ne obstaja. Prosim preveri <a href=\"{$_CONF['site_url']}\">glavno stran</a> ali <a href=\"{$_CONF['site_url']}/search.php\">iskalno stran</a> in poglej èe lahko najde kar si izgubil."
);

$LANG_LOGIN = array (
    	1 => 'Zahtevan je vpis',
    	2 => 'al, za ogled tega dela strani, mora biti logiran kot uporabnik.',
    	3 => 'Logiraj se',
    	4 => 'Nov uporabnik'
);

?>
