<?php
$s1 = "Pomoc";
$s2 = "Kreiranje";
$s3 = "Dodaj/Obrisi korisnika";
$s4 = "Edituj listu";
$s5 = "Kreiraj/Obrisi listu";
$s6 = "Sada smo kreirali Postlister glavnu tabelu. Ovo treba da se uradi samo jednom. Izabrali ste da se glavna tabela zove <i>$mainTable</i>. Ukoliko zelite da promenite ovo ime treba da otvorite file <i>settings.php</i> i da izmenite promenljivu<i>\$mainTable</i>. U protivnom sve sto treba da uradite je da kliknete na 'dugme' i da kreirate tabelu...";
$s7 = "Kreiraj tabelu";
$s8 = "Doslo je do greske!";
$s9 = "Povretak";
$s10 = "Ime tabele je neodgovarajuce. Ime tabele moze da sadrzi samo slova i brojeve -- 'space' ili drugi specijalni karakteri nisu dozvoljeni.";
$s11 = "Postlister glavna tabela <i>$mainTable</i> je kreirana. Sada mozete poceti sa <a href=lists.php>kreiranjem mailing liste</a>.";
$s12 = "Izaberite mailing listu:";
$s13 = "OK";
$s14 = "Nema mailing lists.";
$s15 = "Kreirajte mailing listu";
$s16 = "Kreirajte mailing listu";
$s17 = "Ime mailing liste:";
$s18 = "Izaberite ime za mailing listu. Ime ne moze biti duze od 30 karaktera, i ne sme sadrzati 'space' ili druge specijalne karaktere -- dozvoljena su samo slova a-z i brojevi.";
$s19 = "Obrisi mailing listu";
$s20 = "Koju mailing listu zelite da obrisete?";
$s21 = "Brisanje";
$s22 = "Mailing lista <i>$listeOpret</i> je kreirana. Sada mozete da <a href=edit.php?liste=$listeOpret>editujete listu</a>.";
$s23 = "Da li ste sigurni da zelite da obrisete mailing listu <i>$listeSlet</i>? Ukoliko to ucinite obrisacete i sve e-mail adrese koje su sadzane u listi.";
$s24 = "Odustani";
$s25 = "Brisanje liste";
$s26 = "Mailing lista <i>$listeSletBekraeft</i> je obrisana.";
$s27 = "Adresa posiljaoca npr.. <i>vase.ime &lt;vase.ime@$SERVER_NAME&gt;</i>:";
$s28 = "Teks koji ce biti dodat na svaki e-mail koji je poslat sa mailing liste:";
$s29 = "Poruka pri prijavljivanju-- Poruka koja ce bit poslata onima koji zele da se prijave na mailing listu.";
$s30 = "Snimi promene";
$s31 = "Poruka za prijavljivanje <b>mora</b> sadrzati reci <i>[SUBSCRIBE_URL]</i>.";

# Sledeca pomenljiva ide u telo e-mail poruke. Zato, morate prelomiti svaki liniju na 72 karakteru
$s32 = "Primili ste ovaj e-mail zato sto ste se Vi
ili Vas je neko drugi prijavio na mailing listu 
$listeOpret na http://$HTTP_HOST.
Pre nego sto dodamo vasu e-mail adresu nasoj mailing 
listi moramo se uveriti da ova adresa zaista postoji
i da njen stvarni vlasnik zeli da se prijavi na nasu
mailing listu. Zato Vas molimo da svoje prijavljivanje
potvrdite tako sto cete posetiti sledeci URL:

<[SUBSCRIBE_URL]>

Hvala.";

$s33 = "Izmene u listi <i>$liste</i> su snimljene.";
$s34 = "Dodajte e-mail adresu";
$s35 = "Obrisite e-mail adresu";
$s36 = "Dodaj";
$s37 = "Unesite novu adresu koju dodajete mailing listi -- npr. <i>petar@petrovic.com</i>:";
$s38 = "<i>$epostadresseTilfoej</i> nije u odgovarajucem formatu.";
$s39 = "E-mail adresa <i>$epostadresseTilfoej</i> je dodata na listu <i>$liste</i>.";
$s40 = "E-mail adresa <i>$epostadresseTilfoej</i> vec postoji u mailing listi.";
$s41 = "Prikazi";
$s42 = "Svi korisnici";
$s43 = "Potvrdjeni";
$s44 = "Nepotvrdjeni";
$s45 = "pocevsi od";
$s46 = "koji sadrzi";
$s47 = "Nema rezulata.";
$s48 = "Potvrdjen";
$s49 = "Nepotvrdjen";
$s50 = "E-mail adresa <i>$sletDenne</i> je obrisana iz mailing liste <i>$liste</i>.";
$s51 = "Napisete poruku na mailing listu <i>$liste</i>";
$s52 = "From:";
$s53 = "Subject:";
$s54 = "Body:";
$s55 = "Prelom na 72 karakteru";
$s56 = "Pregled pre slanja";
$s57 = "Stampaj";
$s58 = "Prebroj reci";
$s59 = "Funkcije";
$s60 = "Broj karaktera:";
$s61 = "Broj reci:";
$s62 = "Da bi pristupili ovoj stranici potrebni su vam password i username.";
$s63 = "Mozete da koristite sledece promenljive u tekstu poruke:";
$s64 = "Adresa primaoca.";
$s65 = "Adresa za odjavljivanje -- URL koji je potreban korisniku da bi se odjavio sa mailing liste.";
$s66 = "To:";
$s67 = "Send";
$s68 = "Povratak -- Zalim da edituje poruku";
$s69 = "Mailing liste";
$s70 = "Prijavite se na nasu mailing listu(e):";
$s71 = "Vasa e-mail adresa:";
$s72 = "Izaberite mailing listu:";
$s73 = "Prijavljivanje";
$s74 = "Odjavljivanje";
$s75 = "<i>$email</i> nije u odgovarajucem formatu.";
$s76 = "Niste izabrali da li zelite da se prijavite ili odjavite sa mailing liste. Moguce je da je problem u formularu koji ste popunili. Molimo Vas da kontaktirate site administratora.";
$s77 = "Prijavljivanje na $list mailing listu";
$s78 = "Odjavljivanje sa $list mailing liste";
$s79 = "Hvala Vam sto ste se prijavili na <i>$list</i> mailing listu. Pre nego sto Vas prijavimo na mailing listu molimo Vas da potvrdite svoju prijavu. Za nekoliko minuda primicete e-mail koji sadrzi URL koji morate posetiti da bi potvrdili svoje prijavljivanje.";
$s80 = "Da bi Vas odjavili sa <i>$list</i> mailing liste molimo Vas da potvrdite svoj zahtev. Za nekoliko minuda primicete e-mail koji sadrzi URL koji morate posetiti da bi potvrdili svoje odjavljivanje.";

# Sledeca pomenljiva ide u telo e-mail poruke. Zato, morate prelomiti svaki liniju na 72 karakteru
$s81 = "Primili ste ovaj e-mail zato sto ste se Vi
ili Vas je neko drugi odjavio sa mailing liste
$listeOpret na http://$HTTP_HOST.
Pre nego sto odjavimo vasu e-mail adresu na nasoj 
mailing listi moramo se uveriti da stvarni vlasnik 
ove adrese zeli da se odjavi sa nase mailing liste. 
Zato Vas molimo da svoje odjavlivanje potvrdite 
tako sto cete posetiti sledeci URL:

<[UNSUBSCRIBE_URL]>

Hvala.";

$s82 = "Poruka za odjavljivanje <b>mora</b> sadrzati rec <i>[UNSUBSCRIBE_URL]</i>.";
$s83 = "Poruka za odjavlivanje -- poruka koja se salje onima koji zele da se odjave sa mailing liste.";
$s84 = "E-mail adresa<i>$email</i> vec postoji u listi.";
$s85 = "Poslato. E-mail je upravo poslat na sve adrese i mailing liste.";
$s86 = "Postlister salje poruku broj";
$s87 = "kroz";
$s88 = "Nemojte zatvarati ovaj prozor vaseg browsera! Nemojte nista pritiskati dok Postlister ne zavrsi sa salanjem preostalih poruka.";
$s89 = "E-mail adresa <i>$email</i> ne postoji u listi. Stoga je ne mozete odjaviti.";
$s90 = "Nije navedena e-mail adresa.";
$s91 = "Niste naveli da li zelite da prijavite ili odjavite sa liste.";
$s92 = "Niste naveli ID za e-mail adresu.";
$s93 = "Niste naveli mailing listu.";
$s94 = "Niste naveli odgovarajuci ID za e-mail adresu <i>$epost</i>.";
$s95 = "Gotovo! Prijavljeni ste na <i>$liste</i> mailing listu.";
$s96 = "Odjavljeni ste sa <i>$liste</i> mailing liste.";
$s97 = "iz";
$s98 = "Importuj e-mail addrese";
$s99 = "Otvori i importuj";
$s100 = "File <i>$importfil</i> nije pronadjen.";
$s101 = "To je to! Sve adrese iz file-a <i>$importfil</i> su importovane u <i>$liste</i> mailing listu.";
$s102 = "Ukoliko imate file koji sadrzi e-mail adrese, mozete ih importovati u <i>$liste</i> mailing listu. Ali, neophodno je da file sadrzi samo jednu adresu po liniji, i da ta linija ne sadrzi nista drugo osim e-mail adrese. Drugim recima format file bi trebao da bude nesto kao:<p><i>petar@petrovic.yu<br>Petar petrovic &lt;petar.petrivic@njegos.com&gt;<br>php@php.net</i>";
$s103 = "File:";
$s104 = "Povratak na Postlister glavnu stranu";
$s105 = "Import/eksport";
$s106 = "Eksportuj e-mail adrese";
$s107 = "Eksportuj";
$s108 = "Koristeci ovu funkciju mozete eksportovati e-mail adrese iz <i>$liste</i> mailing liste. Odnosno sve adrese ce biti snimljene u file - jedna adresa po liniji. Ime file-a ce biti <i>postlister-$liste.txt</i>, i bice snimljen u dolenavedeni direktorijum. 
$s109 = "Direktorijum u koji ce biti snimljen file:";
$s110 = "<i>$eksport</i> nije direktorijum. Morate navesti direktorijum u koji ce biti snimljen file sa e-mail adresama.";
$s111 = "To je to! Sve adrese iz <i>$liste</i> mailing liste su snimljene u <i>$eksport/postliste-$liste.txt</i>.";
?>
