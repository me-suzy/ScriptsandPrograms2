<?php
/* =====================================================================
*	Pagina ver.txt - Log si modificari de versiune
*	Creat de Birkoff pentru proiectul FAR-PHP
*	Versiune: 1.0
*	Copyright: (C) 2004 - 2005 The FAR-PHP Group
*	E-mail: contact@far-php.ro
*	Data inceperii paginii: 13-02-2005
*	
*	Acest program este gratuit pentru utilizare necomerciala (non profit)
*	si este distribuit sub termenii licentei GNU General Public License
*	asa cum sunt publicati de Free Software Foundation; versiunea 2 a licentei,
*	sau (la alegerea dvs) orice versiune ulterioara.	
*
*	This programs it is for non-comercial use (non-profit)
*	and is share on GNU GPL licence agreement
*	publish by Free Software Foundation; version 2,
*	or (your option) any later version.
* ======================================================================== */
echo '<pre>
-------------------------------------------------------------------------
09-06-2005
- corectari - corectat o eroare in modulul adduserphpbb.php (nu se genera automat
	id in tabelul corespunzator din forum)
-------------------------------------------------------------------------
07-06-2005
- corectari - corectat o eroare la schimbarea parolei (login_new.php)
-------------------------------------------------------------------------
29-05-2005
- actualizari - actualizat modului cpanel.php (apar meniurile in functie de
	modulele existente)
-------------------------------------------------------------------------
27-05-2005
- adaugari - adaugat afisarea continutului pe mai multe pagini (pagina content
	daca are mai mult de 20 titluri se va imparte pe mai multe pagini - 
	body.php)
-------------------------------------------------------------------------
26-05-2005
- corectari - corectat o eroare in modulul online.php (verificare initializare
	variabile de sesiune)
-------------------------------------------------------------------------
24-05-2005
- corectari - corectat o eroare in modulul banner.php (stripslashes la bd)
- teste - testat proiectul pentru prevenirea mysql injection (testare ok,
	creat pentru siguranta functia block_mysql_injection() in functions.php)
-------------------------------------------------------------------------
23-05-2005
- corectari - corectat o eroare in modulul install.php (afisa ca tema si
	pagina index.php creata pentru robots.php)
-------------------------------------------------------------------------
21-05-2005
- adaugari - adaugat modulul blockip.php (adaugare/modificare/stergere ip)
- corectari - corectat o eroare in modulul ch_template.php (afisa ca tema 
	si pagina index.php creata pentru robots.php)
- corectari - corectat la modulele cu mesaje proprii, afisarea mesajelor
	default in engleza (in cazul in care limba specificata difera de "ro" sau "en")
	= robots.php, online.php, blockip.php, banner.php, uninstall.php, 
		adduserphpbb.php, viev_user.php, demo_page.php, contact.php, cpanel.php
-------------------------------------------------------------------------
19-05-2005
- corectari - corectat o eroare in banner.php (nu se verifica daca actiunea
	ceruta este pentru modulul banner sau pentru alt modul
-------------------------------------------------------------------------
15-05-2005
- corectari - corectat o eroare in body.php si body_admin.php (nu se puteau
	include automat module noi)
- actualizari - actualizat manualul in limba romana cu modificarile facute.
- corectari - corectat o eroare la install.php (se crea meniu in romana cu
	link catre manualul in engleza)
-------------------------------------------------------------------------
13-05-2005
- modificari - schimbat afisarea meniurilor in modulul menu.php
	(acum nu se mai afiseaza decat meniurile >= cu drepturile userului curent, pentru
	ca userii cu drepturi mai mici sa nu mai vada/accesa meniurile create de altii useri
	cu drepturi mai mari)
- add-on - modificat viev_user.php pentru a se putea vedea userii inscrisi si drepturile lor
	in ver 1.x
- actualizari - actualizat manualul in limba romana cu modificarile facute.
- corectari - corectat o eroare de afisare la logare gresita 
	(afisa mesajul de eroare pentru fiecare user din bd)
-------------------------------------------------------------------------
12-05-2005
- adaugari - adaugat modulul robots.php pentru a vedea accesarile
- add-on - adaugat modulul adduserphpbb.php
- actualizari - actualizat login_new.php pentru a se putea 
	adauga/sterge user si in forum phpbb automat (daca exista instalat 
	forum-ul phpbb si exista modulul add-on)
-------------------------------------------------------------------------
09-05-2005 * lansare ver 1.0 alfa 2
- alfa - lansare versiunea 1.0 alfa 2 cu modulele corectate
-------------------------------------------------------------------------
07-05-2005
- add-on - adaugat modulul uninstall.php 
- adaugari - adaugat fisierul index.php (in subdirectoare) si robots.txt pentru 
	monitorizarea acceselor si a robotilor
- adaugari - adaugat tabelul robots in modulul install.php
- corectari - corectat o eroare de securitate la modulul login_new.php si functions.php
	(nu se verifica daca e setat si e-mailul)
- add-on - adaugat modulul cpanel.php
- actualizari - actualizat manualul in limba romana pentru versiunea 1.0
-------------------------------------------------------------------------
06-05-2005
- corectari - corectat preluarea datelor din cooke si adaptat pentru serverele cu 
	directiva magic_quotes_gpc = on 
-------------------------------------------------------------------------
05-05-2005
- corectari - corectat cateva mici probleme la modulul install.php
- corectari - corectat setarea adresei pentru cooke in modulele care creaza cookes
-------------------------------------------------------------------------
04-05-2005 * lansare ver 1.0 alfa 1 cu add-on si teme
- adaugari - adaugat cateva functionalitati la modulul banner.php
- corectari - corectat cateva mici probleme la modulul banner.php
- add-on - adaugat modulul contact.php (multumiri Gyzzard)
- alfa - lansare versiunea 1.0 alfa 1 si trimis la testeri 
	(contine toate temele existente in acest moment + add-on:
	online.php, contact.php, banner.php)
-------------------------------------------------------------------------
26-04-2005
- actualizari - actualizat varianta in engleza a manualului pentru versiunea 0.2
-------------------------------------------------------------------------
22-04-2005
- update - adaptat modulul de login pentru ver 1.x si adaugat optiunea 
	hidden si user permanent
	= din motive de securitate datorita salvarilor datelor in coockie 
		nu se permite decat una din cele 3 optiuni (ori ascuns si temporar, 
		ori normal si permanent - ori normal si temporar)
	= a fost marit timpul pentru cooke de la 5 min la 1 ora 
		(pentru coockie temporar) si la 100 zile (pentru coockie permanent)
- update - adaptat modulul online.php pentru versiunea 1.x
- update - adaptat modulul banner.php pentru versiunea 1.x
- update - adaptat modulul de continut pentru versiunea 1.x
- update - adaptat modulul de meniuri pentru versiunea 1.x
-------------------------------------------------------------------------
21-04-2005
- update - adaptat modulul de template pentru ver 1.x si actualizat temele
-------------------------------------------------------------------------
20-04-2005
- update - adaptat modulul de limbaj pentru versiunea 1.x
-------------------------------------------------------------------------
19-04-2005
- update - au fost incepute actualizarile la module pentru a fi compatibile cu ver 1.x
	= template-ul ramane acelasi doar codul php existent in teme pentru afisarea meniurilor 
		si a continutului se modifica (se afiseaza datele cerute pe baza unei singure 
		functii care se scrie in locul unde trebuie sa apara logo, sau meniul,
		sau continutul... - functia se apeleaza cu parametru si returneaza datele 
		in functie de parametrul specificat)
	= a fost introdusa o functie proprie pentru depanarea erorilor din proiect
	= pe baza fisierelor update.php, body.php si end.php se face actualizarea pentru 
		noile module sau update
	= a fost finalizat modulul install.php pentru noua versiune (a fost corectata si 
		eroarea in cazul in care nu se putea genera fisierul config.php)
	= a fost actualizata tema "blue" (compatibilitate cu ver 1.x)
-------------------------------------------------------------------------
18-04-2005
- modificari - a fost inceput lucrul la versiunea 1.x
	= securitate mai buna
	= posibilitate de a bloca accesul la site dupa ip
	= control pannel pentru accesul modulelor
	= a fost introdus un singur cooke pentru toate operatiile site-ului
	= variabilele de sesiune au fost redenumite in limba engleza si a fost adaugata 
		extensia "_far" la numele variabilelor globale
	= au fost introduse functii noi pentru limbaj, continut si securitate
	= a fost modificata schema logica a paginii
	= a fost introdusa posibilitatea de afisare a meniurilor aditionale in 
		functie de numele paginii
	= au fost corectate erorile de instalare (generarea fisierului config.php)
	= posibilitatea de a include automat module aditionale
	= a fost introdus modulul de update automat	
-------------------------------------------------------------------------
11-04-2005
- teste - testare ver 0.3 alfa
-------------------------------------------------------------------------
08-04-2005
- add-on - adaugat tema "corp" (multumiri alin4lex)
-------------------------------------------------------------------------
07-04-2005
- corectari - corectat o eroare la fisierul language_en.php ($mesaj[277])
- teste - se testeaza varianta modulul de bannere (varianta free)
	dintre facilitati: afisare, adaugare, modificare, stergere, log.
	(varianta full - monitorizare afisari, clikuri, afisare pe perioade...)
-------------------------------------------------------------------------
06-04-2005
- corectari - corectat cateva erori la modulul online.php
-------------------------------------------------------------------------
05-04-2005
- add-on - adaugat modulul de vizitatori online (multumiri Dexter)
- corectari - corectat temele pentru a include acest add-on in caz ca exista
-------------------------------------------------------------------------
01-04-2005 * lansare ver 0.02
-------------------------------------------------------------------------
30-03-2005
- corectari - corectat o eroare la afisarea timpului de incarcare a paginii (fisierul end.php)
-------------------------------------------------------------------------
29-03-2005 * lansare add-on - blue_theme
- corectari - corectat modulele pentru a se potrivii cu versiunea 0.02
- adaugari - adaugat variante de meniuri predefinite si pentru limba engleza in modulul 
	install.php
- corectari - corectat mici erori la modulul install.php
- corectari - corectat afisarea textului dupa instalare in index.php
- corectari - corectat erorile in cazul in care nu exista meniuri in fisierul body.php si 
	admin.php
-------------------------------------------------------------------------
28-03-2005
- add-on - adaugat tema blue (multumiri Cata)
- corectari - corectat o eroare la redirectarea din modulul menu.php
- add-on - adaugat modulul care afiseaza si monitorizeaza banerele de reclama.
-------------------------------------------------------------------------
25-03-2005
- modificari - implementat un cod nou la css.php din tema clasic (multumiri lui Cata) 
	pentru afisarea meniului
- adaugari - adaugat bannere si logo
-------------------------------------------------------------------------
24-03-2005
- adaugari - adaugat meniul home | pagina curenta | print | pagina favorita | e-mail pagina
- corectari - corectat la tema clasic culoarea pentru meniul adaugat
- adaugari - adaugat favorite icon
- corectari - corectat o eroare la modulul install.php (nu pastra limbajul specificat in 
	caz de eroare)
-------------------------------------------------------------------------
23-03-2005
- adaugari - adaugat o pagina demo pentru cei care vor sa isi creeze singuri scripturile 
	folosind acest proiect (pagina demo contine 2 scripturi: 
	- scriptul de verificare drepturi acces la pagina si 
	- scriptul de verificare si afisare continut in functie de limbajul ales)
- corectare - corectat eroarea care aparea la selectarea limbajului.
- corectare - corectat afisarea mesajelor din partea de jos a paginii in limba selectata
-------------------------------------------------------------------------
22-03-2005 * se lucreaza pe versiunea 0.02 alfa
- corectare - corectat afisarea casutelor in modulul de login
- corectare - corectat afisarea textului cu link la tema RED (se vedea prea slab) - modificat 
	css...
- actualizari - actualizat manualul proiectului
- corectare - corectat afisarea articolelor din bd in ordine descrescatoare dupa data 
	aparitiei (cand se afiseaza lista)
-------------------------------------------------------------------------
21-03-2005 * lansare prima versiune 0.01
- corectare - corectat modulul install.php (afisare mesaje de eroare + corectare limbaj 
	la meniuri)
- corectare - corectat modulul admin.php
-------------------------------------------------------------------------
10-03-2005
- adaugari - adaugat fisierul complet cu mesaje in engleza
-------------------------------------------------------------------------
09-03-2005
- corectare - corectat o eroare la index.php - header info la cooke...
- corectare - corectat o eroare la install.php - verificare adresa web
-------------------------------------------------------------------------
07-03-2005
- teste - a fost terminat modulul de instalare, testat pe server local (windows)
-------------------------------------------------------------------------
03-03-2005
- lucru - se lucreaza la un nou modul pentru instalare (cel vechi continea erori)
-------------------------------------------------------------------------
01-03-2005
- adaugari - adaugat modulul de instalare
- modificari - modificat index.php pentru a se face automat instalarea
-------------------------------------------------------------------------
22-02-2005
- corectare - corectat o eroare la accesarea pagini index.php (nu se crea sesiunea de limbaj)
- corectare - corectat o eroare in admin.php (nu afisa corect meniurile in functie de limbaj)
-------------------------------------------------------------------------
21-02-2005
- modificari - modificat fisierele (body) pentru a incarca meniurile specifice limbajului ales
- modificari - modificat fisierele pentru a incarca continutul specific limbajului ales
- modificari - modificat modulul de meniu pentru a se potrivi cu noile modificari de limbaj 
	(in loc de 0 a fost introdus all)
- modificari - modificat modulul de continut pentru a permite articole cu acelasi nume dar 
	in limbi diferite
- corectare - corectat o eroare in end.php
- corectare - corectat o eroare in modulul de modificare/stergere continut (adaugat 
	stripslashes la modificarea continutului)
- corectare - corectat aceeasi eroare si in modulul de adaugare continut.
-------------------------------------------------------------------------
20-02-2005
- adaugari - adaugat la modulul de continut fisierul content_2.php (pentru modificare/stergere)
- actualizari - actualizat fisierul de mesaje cu textul din modulul nou
- corectare - corectat fisierul de mesaje in engleza
- modificari - mutat fisierele de mesaje in codes (in radacina au mai ramas doar fisierele 
	de teme si index.php)
- adaugari - creat modulul de schimbat limbajul language.php si ch_language.php
- modificari - modificat toate fisierele ca sa incarce fisierul de mesaje in functie de 
	limba aleasa
-------------------------------------------------------------------------
19-02-2005
- corectare - corectat o eroare in language_en.php
- actualizari - adaugat mesajele din content.php in language_ro.php 
- corectare - corectat in modulul ch_template.php textul care se afisa in buton
-------------------------------------------------------------------------
18-02-2005
- corectare - corectat o eroare la incarcarea logo-ului din fiecare tema in parte si 
	din pagina de admin
-------------------------------------------------------------------------
17-02-2005
- verificari - verificat fisierul de mesaje (language_en.php) - stare: ok 
- verificari - verificat modulul de instalare a proiectului (install.php) - stare: nu e bun - 
	trimis inapoi la colaborator
- actualizari - actualizat paginile on-line ale proiectului - adaugat link la credit
- testari - testat proiectul pe inca 2 servere (in total 6 cu versiuni diferite de 
	php/apache/mysql) - stare - ok
- adaugari - adaugat manualul si ca fisier in directorul content
--------------------------------------------------------------------------
16-02-2005
- corectare - adaugat stripslashes la codul de meniu pentru a se evita afisarea codului 
	cu \ inainte de "
--------------------------------------------------------------------------
15-02-2005
- corectare - adaugat prefix la cooke si sesiuni pentru a repara erorile in cazul in care 
	se lucreaza la 2 proiecte...
--------------------------------------------------------------------------
14-02-2005
- corectare - scos ghilimelele din tabele... eroare la interogare...
--------------------------------------------------------------------------
13-02-2005
- Corectat partea de tabele - introdus prefix la far_
- Corectat config.php
--------------------------------------------------------------------------
</pre>';
?>