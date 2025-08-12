<?php
$s1 = "Hjælp";
$s2 = "Skriv et ebrev til listen";
$s3 = "Tilføj/slet abonnenter";
$s4 = "Rediger listen";
$s5 = "Opret/slet lister";
$s6 = "Nu skal Postlisters hovedtabel oprettes. Det skal kun gøres &eacute;n gang. Du har valgt at give hovedtabellen navn <i>$mainTable</i>. Hvis du vil ændre dette navn, så åbn filen <i>settings.php</i> og ændr variablen <i>\$mainTable</i>. Ellers skal du bare trykke på nedenstående knap for at oprette tabellen.";
$s7 = "Opret tabellen";
$s8 = "Der er opstået en fejl";
$s9 = "Tilbage";
$s10 = "Navnet på tabellen er ugyldigt. Det må kun indeholde bogstaver og tal - ingen mellemrum eller specialtegn.";
$s11 = "Postlisters hovedtabel <i>$mainTable</i> er nu oprettet. Du kan nu begynde at <a href=lists.php>oprette postlister</a>.";
$s12 = "Vælg en postliste:";
$s13 = "Okay";
$s14 = "Der er ikke oprettet nogen postlister.";
$s15 = "Opret postlisten";
$s16 = "Opret en postliste";
$s17 = "Postlistenavn:";
$s18 = "Du skal nu vælge et navn til den nye postliste. Navnet må højst være på 20 bogstaver, og det må ikke indeholde mellemrum eller andre specialtegn - kun bogstaverne a-z og eventuelt tal.";
$s19 = "Slet en postliste";
$s20 = "Hvilken postliste skal slettes?";
$s21 = "Slet";
$s22 = "Postlisten <i>$listeOpret</i> er nu oprettet. Du kan nu <a href=edit.php?liste=$listeOpret>redigere listen</a>.";
$s23 = "Er du sikker på, at du vil slette listen <i>$listeSlet</i>? Hvis du sletter den, mister du alle epostadresser, der er gemt i den.";
$s24 = "Annuller";
$s25 = "Slet listen";
$s26 = "Listen <i>$listeSletBekraeft</i> er nu slettet.";
$s27 = "Afsenderadresse, f.eks. <i>Dit navn &lt;dit.navn@$SERVER_NAME&gt;</i>:";
$s28 = "Signaturen, der skal indsættes i bunden af ebrevene, der bliver udsendt til postlisten:";
$s29 = "Tilmeldingsbeskeden - den besked, der skal sendes til dem, der tilmelder sig listen.";
$s30 = "Gem ændringerne";
$s31 = "Tilmeldingsteksten <b>skal</b> indeholde ordet <i>[SUBSCRIBE_URL]</i>.";

# The following variable will go into an email body. Therfore, you need to break all lines after 72 characters.
$s32 = "Du har modtaget dette ebrev, fordi du eller en anden har
tilmeldt dig postlisten $listeOpret på http://$HTTP_HOST.
Før du kan blive endeligt opskrevet på listen beder vi dig om at
bekræfte din tilmelding for at sikre os, at din epostadresse virker,
samt at du rent faktisk er interesseret i at blive opskrevet på
postlisten.

For at bekræfte din tilmelding skal du gå til følgende adresse:

<[SUBSCRIBE_URL]>

Mange tak.";

$s33 = "Ændringerne i listen <i>$liste</i> er gemt.";
$s34 = "Tilmeld epostadresser";
$s35 = "Slet epostadresser";
$s36 = "Tilmeld";
$s37 = "Skriv den nye epostadressse, der skal tilmeldes - f.eks. <i>jens.hansen@eksempel.dk</i>:";
$s38 = "<i>$epostadresseTilfoej</i> er ikke en gyldig epostadresse.";
$s39 = "Epostadressen <i>$epostadresseTilfoej</i> er nu tilmeldt listen <i>$liste</i>.";
$s40 = "Det ser ud til, at epostadressen <i>$epostadresseTilfoej</i> allerede findes på listen.";
$s41 = "Vis";
$s42 = "alle abonnenter";
$s43 = "godkendte";
$s44 = "ikke-godkendte";
$s45 = "der begynder med";
$s46 = "der indeholder";
$s47 = "Intet resultat.";
$s48 = "godkendt";
$s49 = "ikke godkendt";
$s50 = "Epostadressen <i>$sletDenne</i> er slettet af postlisten <i>$liste</i>.";
$s51 = "Skriv et ebrev til postlisten <i>$liste</i>";
$s52 = "Fra:";
$s53 = "Emne:";
$s54 = "Tekst:";
$s55 = "Linjebrydning efter hvert 72. tegn";
$s56 = "Gennemse ebrevet før afsendelsen";
$s57 = "Udskriv";
$s58 = "Ordoptælling";
$s59 = "Funktioner";
$s60 = "Antal tegn:";
$s61 = "Antal ord:";
$s62 = "Du skal indtaste det rigtige brugernavn og adgangskode, hvis du vil have adgang til denne side.";
$s63 = "Følgende variabler kan indsættes i ebrevets tekst:";
$s64 = "Modtagerens epostadresse.";
$s65 = "Afmeldingsadressen - den adresse, modtageren skal gå til for at afmelde sig listen.";
$s66 = "Til:";
$s67 = "Send ebrevet";
$s68 = "Tilbage - ebrevet skal rettes";
$s69 = "Postlister";
$s70 = "Tilmeld dig vores postliste(r):";
$s71 = "Din epostadresse:";
$s72 = "Vælg en postliste:";
$s73 = "Tilmeld";
$s74 = "Frameld";
$s75 = "<i>$email</i> er ikke en gyldig epostadresse.";
$s76 = "Du har ikke angivet, hvorvidt du ønsker at tilmelde eller afmelde dig postlisten. Det skyldes sandsynligvis, at der er fejl i den formular, du har udfyldt. Kontakt venligst netstedets administrator om dette.";
$s77 = "Tilmelding til postlisten $list";
$s78 = "Afmelding af postlisten $list";
$s79 = "Tak for din tilmelding til postlisten <i>$list</i>. Før du kan blive endeligt opskrevet på listen, beder vi dig om at bekræfte din tilmelding. Du vil inden for nogle minutter modtage et ebrev. I det ebrev er der en adresse, som du skal gå til for at bekræfte din tilmelding.";
$s80 = "Før du kan blive endeligt afmeldt postlisten <i>$list</i>, beder vi dig om at bekræfte din afmelding. Du vil inden for et par minutter modtage et ebrev. I det ebrev er der en adresse, som du skal gå til for at bekræfte din afmelding.";

# The following variable will go into an email body. Therfore, you need to break all lines after 72 characters.
$s81 = "Du har modtaget dette ebrev, fordi du eller en anden har
afmeldt dig postlisten $listeOpret på http://$HTTP_HOST.
Før du kan blive endeligt fjernet fra listen beder vi dig om at
bekræfte din afmelding for at sikre os, at du rent faktisk er
interesseret i at blive fjernet fra postlisten.

For at bekræfte din afmelding skal du gå til følgende adresse:

<[UNSUBSCRIBE_URL]>

Mange tak.";

$s82 = "Afmeldingsteksten <b>skal</b> indeholde ordet <i>[UNSUBSCRIBE_URL]</i>.";
$s83 = "Afmeldingsbeskeden - den besked, der skal sendes til dem, der afmelder sig listen.";
$s84 = "Det ser ud til, at epostadressen <i>$email</i> allerede findes på listen.";
$s85 = "Sådan! Ebrevet er nu udsendt til alle personer på listen.";
$s86 = "Postlister udsender nu ebrev nr.";
$s87 = "til";
$s88 = "Luk IKKE dette browservindue! Rør ikke ved noget, mens programmet udsender de resterende ebreve.";
$s89 = "Epostadressen <i>$email</i> findes desværre ikke på listen, og den kan derfor ikke afmeldes.";
$s90 = "Der er ikke angivet nogen epostadresse.";
$s91 = "Du har ikke angivet hvorvidt du ønsker at tilmelde eller framelde dig listen.";
$s92 = "Epostadressens ID er ikke angivet.";
$s93 = "Du har ikke angivet en postliste.";
$s94 = "Du har ikke angivet det rigtige ID for epostadressen <i>$epost</i>.";
$s95 = "Sådan! Du er nu tilmeldt postlisten <i>$liste</i>.";
$s96 = "Du er nu afmeldt postlisten <i>$liste</i> og vil ikke modtage flere ebreve derfra.";
$s97 = "af";
$s98 = "Importér epostadresser";
$s99 = "Åbn og importér";
$s100 = "Filen <i>$importfil</i> findes ikke.";
$s101 = "Sådan! Alle epostadresserne i filen <i>$importfil</i> er nu opskrevet på postlisten <i>$liste</i>.";
$s102 = "Hvis du har en fil, der indeholder en række epostadresser, så kan du importere adresserne ind i postlisten <i>$liste</i>. Det kræver dog, at filen kun indeholder én epostadresse per linje, og at den ikke indeholder andet end epostadresser. Filen skal med andre ord have et format noget lignende dette:<p><i>jens.hansen@eksempel.dk<br>Joe Johnson &lt;joe.johnson@example.com&gt;<br>php@php.net</i>";
$s103 = "Fil:";
$s104 = "Tilbage til Postlisters hovedside";
$s105 = "Importér/eksportér";
$s106 = "Eksportér epostadresser";
$s107 = "Eksportér";
$s108 = "Med denne funktion kan du eksportere alle epostadresserne i <i>$liste</i>. Det vil sige, at alle epostadresserne bliver opskrevet i en tekstfil - én adresse per linje. Filen kommer til at hedde <i>postlister-$liste.txt</i> og vil blive placeret i den mappe, du angiver nedenfor. <b>Det er meget vigtigt, at mappen, som filen skal placeres i, har de rette filrettigheder. Det betyder, at du bliver nødt til vha. FTP eller SSH/telnet at ændre mappens rettigheder (chmod'e mappen) til 777.</b>";
$s109 = "Mappen, hvori filen skal placeres:";
$s110 = "<i>$eksport</i> er ikke en mappe. Du skal angive den mappe, som filen med epostadresserne skal placeres i.";
$s111 = "Sådan! Alle epostadresserne på listen <i>$liste</i> er nu opskrevet i filen <i>$eksport/postliste-$liste.txt</i>.";
?>
