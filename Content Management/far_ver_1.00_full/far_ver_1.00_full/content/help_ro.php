<?php
/* =====================================================================
* 	Manualul proiectului FAR-PHP in limba romana
*	Creat de Birkoff pentru proiectul FAR-PHP
*	Manual adaptat pentru versiunea: 1.00
*	Copyright: (C) 2004 - 2005 The FAR-PHP Group
*	E-mail: contact@far-php.ro
*	Data inceperii manualului: 05-05-2005
*	Ultima modificare: 21-05-2005
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

/* mentiune pentru toti colaboratorii 
- orice modificare noua in manual se va face cu o culoare diferita pentru a fi usor de gasit si tradus doar modificarea

--------- mentiune pentru krimket -----------
- de tradus si fisierul demo_page.php

*/

?>
<style type="text/css">
<!--
.adaugari_manual 
	{
	color: #FF0000;
	}
-->
</style>


<table width="100%"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="top"><div align="center">
      <p><strong>Manual FAR-PHP <br>
          (actualizat pentru versiunea 1.0)          </strong></p>
      <p><em>Colaboratori proiectului FAR-PHP</em></p>
      <p align="center">      Multumiri tuturor celor care au participat si participa intr-un fel sau altul prin idei, sugestii, ajutor sau colaborare. <br>
        Acest proiect fiind cu sursele deschise, oricine este binevenit sa participe.</p>
      <p>Colaboratori permanenti:</p>
      <p>Coordonator proiect: <strong>Birkoff</strong> - <strong>contact</strong> <em>at</em> <strong>far-php</strong> <em>dot</em> <strong>ro</strong> <br>
        Translator ro-en: <strong>Krimket</strong> - <strong>krimket</strong> <em>at</em> <strong>far-php</strong> <em>dot</em> <strong>ro</strong><br>
        Programator php-mysql: <strong>Dexter</strong> - <strong>dexter</strong> <em>at</em> <strong>far-php</strong> <em>dot</em> <strong>ro</strong><br>
      </p>
      <p>Alti colaboratori: </p>
      <p>- <strong>Gyzzard</strong> - <strong>gyzzard</strong> <em>at</em> <strong>yahoo</strong> <em>dot</em> <strong>com</strong> - add-on modulul &quot;contact.php&quot;<br>
        - <strong>Alin4lex</strong> - <strong>spookykid</strong> <em>at</em> <strong>4x</strong> <em>dot</em> <strong>ro</strong> - tema &quot;corp&quot; <br>
        - <strong>Cata</strong> - <strong>barosanu_catalin</strong> <em>at</em> <strong>yahoo</strong> <em>dot</em> <strong>com</strong> - tema &quot;blue&quot; + css nou <br>
        - <strong>Tudy</strong> - <strong>trd002200</strong> <em>at</em> <strong>yahoo</strong> <em>dot</em> <strong>com</strong> - ideea de creare a modulului de instalare a aplicatiei + ajutor la cod <br>
        - <strong>Aniflaviu</strong> - <strong>addyanni</strong> <em>at</em> <strong>yahoo</strong> <em>dot</em> <strong>com</strong> - idei si sugestii referitoare la proiect + colaborare la partea de mysql si php. <br>
        - <strong>Stalker</strong> - <strong>numaitu2002</strong> <em>at</em> <strong>yahoo</strong> <em>dot</em> <strong>com</strong> - designer teme &quot;clasic&quot; + css la &quot;red&quot; si &quot;mountain&quot;</p>
      <p><br>
          <br>
          Copyright: 2004, 2005 Grupul de dezvoltare FAR-PHP </p>
      <hr>
      <p>Cuprins:</p>
      <p align="justify"><a href="#Prefata">Prefata</a><br>
        I. <a href="#introducere">Introducere</a><br>
        &nbsp;&nbsp;&nbsp;<a href="#licenta">Licenta proiectului</a><br>
		&nbsp;&nbsp;&nbsp;1. <a href="#instalare">Instalare</a><br>
		&nbsp;&nbsp;&nbsp;2. <a href="#configurare">Configurare</a><br>
		&nbsp;&nbsp;&nbsp;3. <a href="#mesaje_eroare">Schimbare mesaje de eroare</a><br>
		&nbsp;&nbsp;&nbsp;4. <a href="#instal_problem">Probleme aparute la instalare</a>		<br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a) <a href="#instal_problem_a">Cerinte de sistem</a><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b) <a href="#instal_problem_b">Erori posibile la instalare</a><br>
		II.1. <a href="#lucrul_cu_far_php">Cum lucram cu FAR-PHP</a><br>
&nbsp;&nbsp;&nbsp;a. <a href="#modul_logare">Modulul de logare</a> - login.php, login_ver.php, login_new.php <br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. <a href="#login">Login</a><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. <a href="#drept_acces">Drepturi si nivele de acces</a><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. <a href="#creare_user">Creare user nou</a><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4. <a href="#schimbare_drepturi">Schimbare drepturi de acces la user</a><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5. <a href="#schimbare_parola">Schimbare parola la user</a><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;6. <a href="#stergere_user">Stergere user</a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;7. <a href="#deconectare_user">Deconectare user 		</a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;8. <a href="#viev_useri">Afisare lista useri inscrisi
</a><br>
&nbsp;&nbsp;&nbsp;b. <a href="#modul_meniuri">Modulul de meniuri 		</a>- menu.php <br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. <a href="#creare_meniu">Creare meniuri noi</a><br> 
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. <a href="#schimbare_meniu">Schimbare stare meniuri</a><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. <a href="#stergere_meniu">Stergere meniuri</a><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4. <a href="#afisare_lista_meniuri">Afisare meniuri existente</a><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5. <a href="#schimbare_meniu">Modificare meniu		</a><br>
&nbsp;&nbsp;&nbsp;c.	<a href="#modul_continut">Modulul de continut</a>	- content.php, content_2.php <br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. <a href="#modul_continut_nou">Adaugare continut nou</a><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.a) <a href="#continut_html">Adaugare continut nou in format text/html</a>		<br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.b) <a href="#continut_varianta_5">Adaugare continut nou in format php/sql</a>		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. <a href="#modul_content_2">Modificare continut existent</a><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. <a href="#modul_stergere_continut">Stergere continut existent</a><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4. <a href="#afisare_articole_limba">Afisare articole existente pentru un anumit limbaj</a><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5. <a href="#afisare_toate_articolele">Afisare toate articolele existente in baza de date</a>		<br>
&nbsp;&nbsp;&nbsp;d. <a href="#modul_stiri">Modulul de stiri 		</a>- news.php <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. Adaugare stire noua		<br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. Modificare stire<br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. Stergere stire<br>
&nbsp;&nbsp;&nbsp;e. <a href="#modul_language">Modulul de limbaj</a> - language.php, ch_language.php, language_xx.php <br> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. <a href="#setare_limbaj_principal">Setarea limbajului principal</a><br> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. <a href="#schimbare_limbaj">Schimbarea intre limbaje diferite</a><br> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. <a href="#creare_limbaj">Crearea unui nou limbaj (traducerea unuia existent) </a><br>
&nbsp;&nbsp;&nbsp;f. <a href="#cpanel">Modulul control panel</a> - cpanel.php <br>
&nbsp;&nbsp;&nbsp;g. <a href="#robots">Modulul robots</a> - robots.txt, robots.php, index.php, meta.php <br>
&nbsp;&nbsp;&nbsp;h. <a href="#install">Modulul de instalare</a> - install.php<br>
&nbsp;&nbsp;&nbsp;i. <a href="#mod_ch_template">Modulul de schimbare teme</a> - ch_template.php<br>
&nbsp;&nbsp;&nbsp;j. <a href="#blockip">Modulul pentru blocare ip</a> - blockip.php <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. <a href="#blockip_lista">Afisare lista cu ip-uri blocate</a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. <a href="#blockip_modificare">Modificare date ip</a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. <a href="#blockip_adaugare">Adaugare ip</a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4. <a href="#blockip_stergere">Stergere ip </a><br>
II.2. <a href="#addon_modules">Module aditionale</a> (add-on)<br>
&nbsp;&nbsp;&nbsp;a. <a href="#banner">Modulul de afisare bannere</a> (add-on) - banner.php <br> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. <a href="#adaugare_banner">Adaugare bannere </a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. <a href="#modificare_banner">Modificare bannere </a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. <a href="#sterg_banner">Stergere bannere </a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4. <a href="#toate_banner">Afisare toate bannerele</a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5. <a href="#log_banner">Log banner</a><br>
&nbsp;&nbsp;&nbsp;b. <a href="#online">Modulul de afisare vizitatori online</a> (add-on) - online.php <br>
&nbsp;&nbsp;&nbsp;c. <a href="#uninstall">Modulul uninstall</a> (add-on) - uninstall.php<br>
&nbsp;&nbsp;&nbsp;d. <a href="#adduserphpbb">Modulul de adaugare user in phpbb</a> (add-on) - adduserphpbb.php<br>
&nbsp;&nbsp;&nbsp;e. <a href="#newsletter">Modulul newsletter</a> (add-on) - newsletter.php <br>
II.3. <a href="#add_scripts">Scripturi aditionale</a> <br>
&nbsp;&nbsp;&nbsp;a. <a href="#contact_1">Pagina de contact</a> (add-on) - contact.php<br>
&nbsp;&nbsp;&nbsp;b. <a href="#demo_page">Pagina demo</a> - demo_page.php<br>
		III. <a href="#schimba_template">Schimbare template</a><br>
&nbsp;&nbsp;&nbsp;a. <a href="#adaug_tema">Cum adaug o tema noua</a><br>
&nbsp;&nbsp;&nbsp;b. <a href="#sterg_tema">Cum sterg o tema din site</a><br>
&nbsp;&nbsp;&nbsp;c. <a href="#creare_tema_site">Cum creez o tema pentru site. </a><br>
		IV. <a href="#specificatii_teme">Ce trebuie modificat la un template pentru a fi compatibil cu codul FAR-PHP</a><br>
		V. <a href="#variabile_ses_cook">Variabile de sesiune si cookies folosite</a>		<br>
		VI. <a href="#log_ver">Modificari de versiune</a> <br>
		VII. <a href="#faq">Intrebari frecvente</a>        <br>
        VIII <a href="#creare_config_manual">Crearea manuala a fisierului de configurare config.php </a></p>
      <hr>
      <p align="justify"><strong>Prefata<a name="prefata"></a> <br>
        <br>
        </strong>Acest manual este mai degraba un ghid care va ajuta sa intelegeti ce este un CMS (Content Management System) si cam ce face el. Proiectul FAR-PHP este un CMS, adica o interfata de lucru pentru administratorul unui site si o pagina web pentru vizitatori. In acest manual gasiti explicatii detaliate ale proiectului, precum si ale modulelor oficiale ce compun proiectul. <br>
        <br>
        Cele mai noi versiuni ale acestui manual (actualizate imediat ce continutul se modifica) pot fi vizualizate (sau descarcate) la <a href="http://www.far-php.ro" target="_blank">http://www.far-php.ro</a> <br>
        <br>
        Versiunea in limba romana a manualului este realizata, in mod voluntar, de echipa proiectului FAR-PHP.<br>
        Pentru a participa la traducerea si/sau dezvoltarea acestui manual
este de ajuns sa trimiteti un e-mail pe adresa <a href="mailto:contact@far-php.ro?Subject=Inscriere la proiect FAR-PHP   ">contact at far-php dot ro</a><br>
      </p>
      <p align="justify"><strong>I. Introducere</strong><a name="introducere"></a><br>
        <br>
        <em>Ce este FAR-PHP?</em><br>
        Este un CMS (Content Management System), adica o interfata web cu ajutorul careia se poate administra continutul unui site. FAR-PHP este simplu de instalat si de configurat, si de asemenea este foarte simplu de lucrat cu aceasta interfata. Proiectul fiind modular, fiecare modul fiind indepentent de restul, se pot adauga oricand noi module cu noi facilitati,  astfel pagina dvs. de web fiind mai personalizabila si atragand mai multi vizitatori.<br>
        <br>
        <em>Licenta proiectului FAR-PHP</em> <a name="licenta"></a><br>
        Proiectul FAR-PHP este ditribuit sub termenii licentei GNU/GPL versiunea 2 a licentei (sau orice versiune ulterioara). Proiectul este open-source, si se poate folosi/modifica codul doar respectand termenii licentei.<br>
        Pentru utilizarea proiectului sau a unor portiuni de cod din proiect in scop comercial (prin comercial se intelege revanzarea codului, folosirea proiectului in cadrul unui site de catre o firma, orice alta utilizare prin care  ar rezulta un castig material) trebuie platita suma de 35/euro  pentru fiecare site unde se foloseste codul din proiect. <br>
        Pentru utilizarea proiectului sau a unor portiuni de cod din proiect in scop personal (prin personal se intelege testare, folosirea proiectului in cadrul unei firme cu scop ne-comercial, orice alta utilizare prin care nu rezulta un castig material) nu se percepe nici o taxa, ci doar respectarea termenilor de copyright mentionati in cod. <br>
        Pentru alte detalii cu privire la licenta, sau distribuirea proiectului sau utilizarea lui, trimiteti un mesaj cu subiectul &quot;FAR-PHP licence&quot; pe adresa <strong>contact</strong> <em>at</em> <strong>far-php</strong> <em>dot</em> <strong>ro</strong> in care specificati ce anume doriti.(in cazul in care subiectul nu este cel specificat este posibil ca mesajul sa fie sters automat de catre programele anti-spam) </p>
      <p align="justify"><em><strong>I.1. Instalare</strong></em><a name="instalare"></a><br>
        <br>
        Pentru a putea folosi acest proiect trebuie sa descarcati fisierul zip si sa il dezarhivati. Dupa dezarhivare, copiati pe serverul dvs. web toate fisierele (atentie, fisierele trebuie puse pe server cu numele corect  cu litere mici - toate numele fisierelor care compun aceasta aplicatie sunt scrise in original cu litere mici - serverele de web fac diferenta intre numele de fisier scrise cu litere mari sau cu litere mici) Dupa ce toate fisierele si directoarele (folderele) au fost copiate pe server deschideti browserul web si scrieti adresa unde au fost salvate fisierele (exemplu www.adresata.ro) Va apare partea de instalare a proiectului, iar de aici urmariti instructiunile scrise in browser. <br>
      </p>
      <p align="justify"><em><strong>I.2. Configurare<a name="configurare"></a><br>
        <br>
        </strong></em>a) Configurarea necesara inaintea instalarii:<br>
        Inainte de a lucra cu aceasta aplicatie, trebuie sa aveti un server web care sa respecte cerintele minime de sistem cerute (vezi punctul I.4.a). Proiectul FAR-PHP este testat destul de mult inainte de a fi distribuit, dar nu putem garanta ca va funtiona 100% pe orice configuratie de server. Daca intampinati probleme ne puteti specifica problema aparuta in forum pe adresa <a href="http://www.far-php.ro/forum/" target="_blank">www.far-php.ro/forum/</a> (in limba engleza sau romana)<br>
        <br>
        b) Configurarea proiectului la instalare:<br>
        In timpul instalarii se va incerca crearea automata a tabelelor in baza de date (baza de date trebuie sa existe deja creata), se va incerca salvarea continutului (meniurile predefinite) si se va incerca generarea si salvarea fisierului config.php. In cazul in care nu se poate scrie fisierul de configurare, datele necesare se vor afisa in browser si vor trebui copiate si salvate manual pe server, conform specificatiilor date in browser la momentul respectiv. <br>
        <br>
        c) Configurari ulterioare instalarii:<br>
        In cazul in care dupa instalarea aplicatiei doriti sa modificati unele date, cum ar fi limbajul predefinit, sau alte date, trebuie sa editati manual fisierul config.php si sa modificati acolo ce trebuie. Toate modulele si fisierele proiectului lucreaza cu datele salvate in acest fisier (este daca vreti inima aplicatiei) asa ca aveti grija ce anume modificati. Pentru siguranta aveti grija sa setati drepturile de acces la acest fisier la chmod 664, iar din setarile serverului configurati sa nu permiteti accesul din afara domeniului la acest fisier. </p>
      <p align="justify"><em><strong>I.3. Schimbare mesaje de eroare</strong></em><a name="mesaje_eroare"></a><br>
        <br>
        Fisierul cu mesaje in limba romana se numeste <em>language_ro.php</em> si se gaseste in directorul <em>content/</em>. In el sunt toate mesajele care apar in modulele aplicatiei, atat mesajele de eroare, cat si avertizarile sau textele pentru butoane si formulare. Puteti sa modificati textul in limba specifica paginii dvs. dar sa nu stergeti variabilele sau sa nu modificati sintaxa mesajului (adica daca mesajul contine cod html, este recomandat sa nu stergeti codul html ci doar mesajul propriuzis). Nu puteti redenumi fisierul cu mesaje decat in forma sa standard (<em>language_limba.php</em>), deoarece este folosit pentru a se afisa continutul paginilor in limbajul specificat si la fel si meniurile apar in limbajul specificat. Puteti in fisierul <em>config.php</em> sa specificati noul nume al fisierului cu mesaje. Acest fisier este inclus in toate paginile si modulele aplicatiei, astfel ca orice mesaj care apare in pagina web trebuie sa existe intai in acest fisier. <br>
        Incepand de la versiunea 1.0 modulele aditionale au fiecare propriile mesaje de eroare, stocate de obicei intr-o functie sau intr-un array. In cazul in care utilizati in site alte limbaje in afara celor cu care este distribuit proiectul, va trebui sa adaugati manual in fiecare modul aditional mesajele specifice pentru limba utilizata in site-ul dvs. </p>
      <p align="justify"><em><strong>I.4. Probleme aparute la instalare</strong></em> <a name="instal_problem"></a><br>
        <br>
        Este posibil sa apara erori datorita in special configurarii specifice a serverului pe care este pusa aplicatia sau datorita erorilor aparute la crearea bazei de date si a fisierului config.php. In continuare o sa descriem cerintele minime necesare pentru FAR-PHP si eventualele probleme care pot sa apara (daca eroarea care o aveti nu este descrisa mai jos ne puteti trimite pe mail detalii pentru a putea sa o rezolvam).<br>
        <br>
        a) <em>Cerinte de sistem</em><a name="instal_problem_a"></a><br>
        Server pe care este instalat suport pentru PHP minim versiunea 4.1.x + MySQL versiunea minima 4.x.<br>
        Pentru a functiona corect trebuie sa aveti active urmatoarele librarii si extensii php: <br>
        ftp support = enabled<br>
        gd support = enabled<br>
        mysql support = enabled<br>
        session support = enabled<br>
        mail support = enabled <br>
        Pe partea de client, este recomandat un browser cu java script activat (de exemplu browserul Opera se distribuie atat cu java cat si fara). In cazul in care partea de java script nu este activa (sau actualizata) nu se pot schimba limbajul si template-ul specificat dar site-ul va functiona cu setarile default. <br>
        <br>
        b) <em>Erori posibile la instalare:</em><a name="instal_problem_b"></a><br>
        - <u>Paginile se incarca greu</u><br>
        Este posibil sa nu aveti legatura la internet buna (chiar daca lucrati in localhost, se folosesc functii precum 


 gethostbyaddr sau mail care pot incetinii prelucrarea scriptului in cazul unei conexiuni slabe la internet) <br>
 - <u>Nu se redirecteaza corect</u><br>
 Adresa unde este instalat FAR-PHP este scrisa gresit in fisierul de configurare <em>config.php</em> Pentru aceasta deschideti fisierul <em>config.php</em> si modificati corect valoarea variabilei <em>$adresa_url</em> <br>
 - <u>Apare un mesaj de genul &quot;nu se poate crea tabelul ...&quot;</u><br>
 Este posibil ca datele referitoare la baza de date sa fie gresite. Porniti din nou instalarea si verificati ca toate datele cerute sa fie corecte. In cazul in care baza de date nu se afla pe localhost, verificati daca aveti acces pentru crearea tabelelor dintr-un script php de la adresa unde se afla salvat FAR-PHP (acces la baza de date din exterior).<br>
 - <u>Apare mesajul &quot;Error: Wrong install - config.php incorrect&quot;</u><br>
 Este posibil ca fisierul de configurare resultat in urma instalarii sa fie corupt (Se poate intampla pe serverele care modifica datele de iesire ale scriptului, pentru a introduce reclame sau anunturi in paginile web gazduite la pe acel server). In acest caz, trebuie sa creati manual fisierul config.php si sa il salvati pe server prin ftp. Pentru crearea manuala a fisierului config.php vedeti capitolul <a href="#creare_config_manual">Crearea manuala a fisierului de configurare config.php</a>. <br>
 - <u>Nu se pastreaza tema aleasa sau limbajul specificat</u><br>
 Aceasta problema tine de browserul cu care lucrati sau de o problema cu sistemul de operare (probabil virusi). Verificati pagina cu alt browser, de pe alt pc, sau actualizati browserul la o versiune mai noua. Verificati de asemenea daca firewalul permite accesul catre adresa de web specificata (unele setari ale firewalului pot bloca cookies sau transmiterea variabilelor de sesiune) <br>
 - <u>Nu se poate schimba limbajul sau template-ul </u><br>
 Aceasta problema tine tot de partea de client, verificati daca browserul are activata optiunea pentru java script, permite cookies si variabile de sesiune. Verificati setarile de la firewall si eventual actualizati browserul la o versiune mai noua sau incercati cu alt browser (recomandat Firefix) <br>
 - <u>Nu pot sa schimb continutul in pagina principala</u><br>
 Numele paginii principale este &quot;default&quot;. Adaugati un articol in baza de date cu numele de fisier &quot;default&quot; (mai multe detalii in capitolul <a href="#modul_continut">Modulul de continut</a> )<br>
 - <u>Nu am nici un meniu in pagina.</u><br>
 La instalare, meniurile se salveaza in limba in care sa executat instalarea, deci pentru a vedea meniurile preinstalate, selectati limba folosita in timpul instalarii si meniurile vor apare in pagina. Pentru adaugarea unor meiuri noi cititi capitolul <a href="#modul_meniuri">Modulul de meniuri</a>.<br>
 <br>
 Pentru alte erori care nu sunt trecute aici verificati capitolul <a href="#intrebari_frecvente">Intrebari frecvente</a> <br>
 </p>
      <p align="justify"><strong>II. Cum lucram cu FAR-PHP</strong><a name="lucrul_cu_far_php"></a><br>
        <br>
        Dupa instalarea si configurarea aplicatiei, primul lucru care trebuie facut este sa ne conectam cu userul si parola introduse la partea de instalare a site-ului. Dupa conectare, va apare un mesaj care va specifica ce drepturi aveti (in acest caz drepturi de administrator) si apare un meniu de unde puteti face managementul site-ului. Acest meniu permite accesul la diverse module instalate odata cu aplicatia FAR-PHP. In continuare prezentam lucrul cu fiecare modul in parte si felul cum se acceseaza diferite caracteristici ale modulelor respective. Aceste module sunt simple fisiere php care preiau datele trimise prin GET si POST si dupa prelucrare afiseaza rezultatul. Toate modulele se gasesc in directorul MODULES. Modulele se acceseaza din pagina admin.php folosind ca parametru m=nume_modul (exemplu: admin.php?m=login - va afisa starea user-ului - cum observati nu trebuie scrisa extensia fisierului, aceasta este pusa automat in interiorul scriptului)<br>
        Parametrii care se folosesc in interiorul aplicatiei FAR-PHP sunt:<br>
        1. In pagina index.php <br>
        - p= se trimite o cerere pentru afisarea unei pagini din baza de date
        <br>
        - c= se trimite o cerere pentru rularea si afisarea unei pagini php aflata in directorul CONTENT<br>
        - m= se trimite o cerere pentru rularea unui modul aflat in directorul MODULES (nu este recomandat sa incarcati module in pagina index.php ci doar in pagina admin.php)<br>
        2. In pagina admin.php<br>
        - m= se trimite o cerere pentru rularea unui modul<br>
        - c= 
        se trimite o cerere pentru rularea si afisarea unei pagini php aflata in directorul CONTENT<br>
          De asemeni, modulele pot avea diferite actiuni, in functie de parametrul primit prin GET. Aceste actiuni se apeleaza folosind urmatoarea sintaxa:<br>
          <a href="admin.php?m=nume_modul&action=tip_actiune" target="_blank">admin.php?m=nume_modul&amp;action=tip_actiune</a><br>
          Exemplu: Pentru inregistrarea unui user nou cererea va fi urmatoarea:<br>
          <a href="admin.php?m=login_new&action=new_user" target="_blank">admin.php?m=login_new&amp;action=new_user</a><br>
        Actiunile si cererile se trimit prin metoda GET pentru a putea fi folosite in link-urile si meniurile din interiorul site-ului. Rezultatul actiunilor si al cererilor din formulare se trimite prin POST pentru a nu putea fi modificate de catre orice user... </p>
      <p align="justify"><em><strong>II.a. Modulul de logare - login.php, login_ver.php si login_new.php</strong></em><a name="modul_logare"></a><br>
        <br>
        Acest modul este compus de fapt din 3 fisiere si anume:<br>
        - login.php - care contine formularul de logare si partea de verificare a sesiunii si afisare a userului si a drepturilor de acces.<br>
        - login_ver.php - care contine partea de verificare a userului si de initializare a sesiunii si a variabilelor de sesiune.<br>
        - login_new.php - care contine partea de creare useri noi, modificare drepturi useri, stergere useri, schimbare parola user si partea de deconectare si distrugere a sesiunii si a variabilelor de sesiune.<br>
        In continuare este o descriere a fiecarui fisier in parte. (Este posibil ca in viitoarele versiuni sa se inglobeze toate cele 3 fisiere intr-un singur modul)</p>
      <p align="justify"><em><strong>II.a.1. Login - (login.php)</strong></em><a name="login"></a><br>
        <br> 
         In pagina initiala a site-ului aveti un formular de logare cu user si parola. Aceasta parte este controlata de fisierul <em>login.php</em>. In cazul in care se conecteaza cineva, acest modul verifica daca a fost initiata sesiunea si cu ce nume si ce parola. In cazul in care sesiunea este initiata se preiau datele din variabilele de sesiune si se afiseaza numele user-ului conectat si drepturile de acces. In caz ca nu este initiata sesiunea, apare formularul de logare. Dupa trimiterea cererii de conectare din formular se apeleaza fisierul <em>login_ver.php</em>. In cazul in care se doreste inscrierea unui user nou, exista un link sub formular care prin parametrii trimisi apeleaza fisierul <em>login_new.php</em> care initiaza procedura de creare user nou. De asemenea, daca se doreste schimbarea unei parole, exista si un link care prin parametrii trimisi apeleaza fisierul <em>login_new.php</em> care initiaza procedura de schimbare a parolei. Dupa trimiterea datelor din formular, se verifica in baza de date userul si parola si daca corespund se initializeaza variabilele de sesiune. In cazul in care userul si parola nu corespund, se salveaza incercarea de logare in baza de date si se cere din nou userul si parola. In cazul in care in fisierul config.php este trecut un numar limita de incercari de logare, dupa acest numar vizitatorul nu va mai putea sa se logheze chiar daca trimite userul si parola corecte. Aceasta blocare este activa pana cand un alt vizitator se va loga. La logare se sterge automat din lista incercarile de logare nereusite si astfel vizitatorul care era blocat temporar din cauza incercarilor nereusite va putea sa se logheze cu succes. <br>
         Incepand de la versiunea 1.0 au fost adaugate 2 noi optiuni pentru logare si anume:<br>
         - parola permanenta si<br>
         - user ascuns<br>
         Din motive de securitate, nu se poate selecta decat una din cele 2 optiuni. Daca selectati &quot;Parola permanenta&quot; se va crea un cookies in care se vor salva datele de logare, si astfel timp de 100 zile (atat este setata valoarea pentru acest cookies) nu va mai trebui sa va logati.<br>
         Daca selectati &quot;User ascuns&quot;, puteti intra pe site cu aceleasi drepturi dar fara a se salva in variabilele de sesiune numele userului real. Se va crea de asemenea un cookies dar cu durata de 1 ora, dupa care va trebui sa va logati din nou. <br>
        Pentru siguranta, nu folositi parole simple la userii cu drepturi intre 1-4 deoarece se pot sparge foarte usor (folositi parole cat mai lungi si cu caractere diferite de gen litere mari + litere mici + caractere speciale) - o parola formata din 4 litere de exemplu toate cu litere mici se paote afla in mai putin de 30 de secunde, pe cand o parola din 4 litere utilizand si litere mici si litere mari si caractere speciale se poate afla in 3-4 zile, deci daca folositi o parola cu peste 7 caractere sunt slabe sanse sa o poata afla. Tot pentru siguranta, specificati in <em>config.php</em> nr de incercari nereusite pe care un user le poate face, astfel daca cineva incearca sa sparga o parola prin incercari succesive, sa i se blocheze accesul dupa x incercari. </p>
      <p align="justify"><em><strong>II.a.2. Drepturi si nivele de acces</strong></em><a name="drept_acces"></a><br>
        <br>
        Exista 6 nivele de acces la site, fiecare nivel avand acces la anumite module si putand executa anumite operatii in cadrul paginilor site-ului. Nivelele de acces sunt de la 1 la 6 si sunt numite sugestiv astfel:<br>
        1- Administrator<br>
        2 - Sub-Administrator<br>
        3 - Moderator<br>
        4 - Editor<br>
        5 - User<br>
        6 - Guest (sau vizitator)<br>
        Un vizitator nelogat are nivelul de acces 6. Dupa inregistrarea unui user nou, acesta primeste automat nivelul de acces 5. Incepand de la nivelul 4, fiecare user care are nivel de acces intre 1 si 4 poate da alte drepturi de acces la alti useri care au nivel de acces mai mic ca al lor. De exemplu, un Moderator (care are nivel de acces 3) poate da la oricare user un nivel de acces intre 3 si 5. La fel un Editor poate da drepturi de acces intre nivelele 4 si 5. Dupa cum observati nu se pot acorda drepturi mai mari decat are cel care vrea sa acorde drepturi si nici nu poate schimba drepturile unui user cu nivel de acces mai mare ca al lui. Astfel daca User_1 (nivel de acces 4) vrea sa modifice drepturile de acces ale lui User_2 (nivel de acces 3) nu va reusi pentru ca User_2 are drepturi mai mari ca User_1.<br>
        In functie de nivelul de acces, apar meniurile. Meniurile au nivele de acces la fel ca userii si apar doar la useri cu nivel de acces egal sau mai mare (adica un Moderator nu va vedea meniurile pentru Administrator de exemplu) Tot in functie de nivelul de acces, un user i se permite sa acceseze anumite module sau sa faca anumite actiuni...<br>
        Incepand de la versiunea 1.0 a fost creata o pagina demonstrativa care este salvata in directorul &quot;CONTENT&quot; si se numeste <em>demo_page.php</em> Folositi informatiile si variabilele existente in aceasta pagina pentru a va putea crea singuri pagini cu continut care pot fi accesate doar de useri cu un anumit nivel de acces. </p>
      <p align="justify"><em><strong>II.a.3. Creare user nou</strong></em><a name="creare_user"></a><br>
        <br>
        Pentru a crea un user nou trebuie accesat modulul login_new.php cu urmatorii parametrii:<br>
        <a href="admin.php?m=login_new&action=new_user" target="_blank">admin.php?m=login_new&amp;action=new_user</a><br>
Initial se cer 3 parametrii si anume Nume User, Parola si E-mail. <br>
Numele userului trebuie sa fie compus din minim 3 caractere iar parola din minim 4 caractere. Adresa de e-mail trebuie sa fie o adresa valida. Dupa trimiterea datelor se verifica daca Numele user-ulul sau adresa de e-mail nu exista deja in baza de date. Daca exista, atunci se afiseaza un mesaj de eroare si se cer din nou un alt nume, parola si adresa de e-mail. Daca adresa de e-mail exista deja in baza de date se afiseaza un mesaj cu recomandarea ca userul sa incerce sa isi schimbe parola in caz ca a uitat parola. Daca verificarile sunt in regula si datele trimise nu exista deja in baza de date, se salveaza datele noului utilizator si se trimite un e-mail pe adresa specificata. User-ul se poate deja loga si vizita continutul paginilor. Userii inscrisi primesc automat nivel de acces 5 (nivel User) <br>
In cazul in care nu aveti activa functia mail pe server, nu se va putea trimite mesaj la userul inscris, dar se va putea loga fara probleme. </p>
      <p align="justify"><em><strong>II.a.4. Schimbare drepturi de acces la user</strong></em><a name="schimbare_drepturi"></a><br>
        <br>
        Pentru a schimba un nivel de acces la un user trebuie accesat modulul login_new.php cu urmatorii parametrii:<br>
        <a href="admin.php?m=login_new&action=new_right" target="_blank">admin.php?m=login_new&amp;action=new_right</a><br>
        Accesul la aceasta actiune este permis doar userilor cu nivel de acces intre 1-4. Un user cu nivel de acces 4 poate acorda drepturi de acces doar pentru nivelele 4,5. Un user cu nivel de acces 3 poate acorda drepturi pentru nivelele 3,4,5. Nu se pot schimba nivelele de acces la userii cu nivel superior. <br>
        In formularul care apare la schimbarea drepturilor de acces se cere numele userului (Atentie: Cautarea userului este &quot;case sensitive&quot; adica se face diferenta intre literele mari si literele mici deci userul cu numele User_1 nu e la fel cu user_1) si se cere tipul de acces pe care doriti sa il atribuiti acelui user. Daca schimbarea a fost executata cu succes va aparea un mesaj de confirmare si se va redirecta catre pagina principala. Daca la incercarea de schimbare a drepturilor a aparut o eroare se va afisa eroarea. Este recomandat sa nu se atribuie drepturi de administrator din motive de securitate, decat la persoanele de incredere. De asemenea, cand creati meniuri, aveti grija ce nivel de acces acordati acelui meniu pentru a putea fi accesat de useri cu drepturile corespunzatoare.</p>
      <p align="justify"><em><strong>II.a.5. Schimbare parola la user</strong></em><a name="schimbare_parola"></a><br>
        <br>
        Accesul la schimbarea unei parole se face trimitand urmatoarea cerere:<br>
        <a href="admin.php?m=login_new&action=new_pass" target="_blank">admin.php?m=login_new&amp;action=new_pass</a><br>
        Pentru schimbarea parolei se cer urmatoarele date: user, parola noua si adresa de e-mail pe care a fost inregistrat userul. <br>
        In cazul in care userul este logat, in formular va apare numele lui scris in campul pentru user. In cazul in care se bifeaza campul de generare a unei parole noi, se va genera o parola aleatoare iar datele introduse in campul &quot;parola noua&quot; vor fi ignorate. Dupa trimiterea datelor cerute din formular se verifica daca adresa de e-mail introdusa corespunde cu numele userului.
      In caz afirmativ, se salveaza cererea cu noua parola intr-un camp temporar in baza de date, se genereaza un link si se trimite pe e-mail la adresa specificata pentru confirmare. Noua parola nu va fi activa pana cand userul nu acceseaza link-ul trimis in e-mail. Dupa activare, userul se poate conecta cu noua parola. In cazul in care nu aveti activa functia mail pe server, nu se va putea schimba parola userului decat de administator, prin acces direct la baza de date. </p>
      <p align="justify"><em><strong>II.a.6. Stergere user</strong></em><a name="stergere_user"></a><br>
        <br>
        Pentru stergerea unui user trebuie trimisa urmatoarea cerere:<br>
        <a href="admin.php?m=login_new&action=new_del" target="_blank">admin.php?m=login_new&amp;action=new_del</a><br>
        Stergerea unui user nu poate fi facuta decat de un user cu nivel de acces 1,2 sau 3. Se cere numele user-ului (case sensitive) si dupa verificare se sterge din baza de date.</p>
      <p align="justify"><em><strong>II.a.7. Deconectare user</strong></em> <a name="deconectare_user"></a><br>
        <br>
        In caz de log-out trebuie sa accesati modulul login_new cu urmatorii parametrii: <br>
        <a href="admin.php?m=login_new&action=dec" target="_blank">admin.php?m=login_new&amp;action=dec</a><br>
        Aceasta comanda distruge sesiunea si variabilele de sesiune si face o redirectare catre pagina principala pentru curatarea cookies-urilor. Nu exista nici o restrictie de acces la acest nivel. Este posibil ca pe unele versiuni de browser sa nu se poata sterge cookies, dar incepand de la versiunea 1.0 inainte de a se incerca stergerea cookies-ului se vor schimba datele stocate devenind astfel invalid si nemaiputand fi refolosit. Dupa o noua logare se va crea alt cookies cu noile valori. <span class="adaugari_manual"><br>
        <em><strong><br>
        </strong></em></span><em><strong>II.a.8. Afisare lista useri inscrisi</strong></em><span class="adaugari_manual"><a name="viev_useri"></a> <br>
        <br>
        </span>Pentru a vedea toti userii inscrisi si detalii despre ei folositi link-ul urmator: <br>
        <a href="index.php?c=viev_user">index.php?c=viev_user        </a><br>
        Acest fisier este de tip add-on si nu face parte din modulul de logare, este folosit doar pentru afisarea detaliilor despre useri. </p>
      <p align="justify"><em><strong>II.b. Modulul de meniuri</strong></em><a name="modul_meniuri"></a><br>
        <br>
        Modulul de meniuri este probabil cel mai important modul dupa cel de logare. Pentru accesarea lui utilizati comanda:<br>
        <a href="admin.php?m=menu" target="_blank">admin.php?m=menu</a><br>
        In acest modul se poate intra doar daca aveti unul din nivelele de acces 1-4. Puteti vedea toate meniurile existente in baza de date, chiar si cele create de alti useri, dar nu puteti edita sau sterge decat meniurile cu nivel de acces egal sau mai mic decat ale userului conectat. (Daca aveti nivel de acces 3 puteti vedea toate meniurile dar nu puteti edita sau sterge decat meniurile cu nivel de acces de la 3 la 6). Puteti crea orice tip de meniu (text/html/css/java) dar atentie la nivelul de acces al meniului (in caz ca dat nivel de acces mai mare decat aveti acces data viitoare nu veti mai putea sa il accesati. Este recomandat sa creati acelasi meniu pentru fiecare limbaj specificat in site (link-urile din meniuri vor ramane aceleasi doar limba va fi schimbata) Astfel pentru fiecare limba se va afisa meniurile specifice. Nu trebuie sa schimbati si continutul deoarece pot exista mai multe pagini cu acelasi nume dar cu limbaje diferite, iar cand se cere o anumita pagina aplicatia va afisa pagina care corespunde limbajului specificat.<br>
        Mai exact pentru pagina principala avem in meniu butonul &lt;HOME&gt; care are urmatoarea adresa &lt;index.php?p=default&gt; In caz ca site-ul este setat pentru limba romana pagina default se va afisa in romana iar daca e setat in engleza pagina default va fi in engleza (ambele avand acelasi link si aceeasi denumire, singura diferenta este limbajul) </p>
      <p align="justify"><em><strong>II.b.1. Creare meniu nou</strong></em><a name="creare_meniu"></a><br>
        <br>
        Pentru crearea unui meniu nou se acceseaza modulul de meniuri astfel:
        <br>
        <a href="admin.php?m=menu&action=new" target="_blank">admin.php?m=menu&amp;action=new</a><br>
        In pagina aparuta se cere tipul de meniu (care poate fi orizontal sau vertical) iar in functie de tipul meniului se alege locul unde va fi afisat meniul respectiv. Astfel pentru meniuri orizontale se poate afisa un meniu sus deasupra logo-ului, la mijloc (adica sub logo) si in josul pagini. Pentru meniuri verticale meniul se poate afisa pe dreapta sau pe stanga. In cazul in care un template nu perimte o anumita aranjare, meniurile se vor afisa toate in sectiunea permisa de template (astfel in pagina de administrare de exemplu, meniurile din dreapta se afiseaza tot pe stanga aranjate sub primele) <br>
        In continuare se cere prioritatea meniului. Prioritatea inseamna care meniu se va afisa primul (daca sunt mai multe meniuri si au acelasi loc de afisare, cel cu prioriate 1 se va afisa primul, dupa aceea cel cu prioritate 2 si asa mai departe. Daca 2 meniuri au setari identice (si tip si locatie si prioritate) atunci primul se va afisa cel care are id-ul mai mic in baza de date (adica cel care a fost creat primul). Se pot seta 30 de prioritati (in general nu cred ca exista site-uri web care sa afiseze mai mult de 30 de meniuri pe pagina) <br>
        Urmatorul lucru care se cere este limbajul. Daca doriti ca meniul sa se afiseze doar daca vizitatorul seteaza o anumita limba, scrieti numele limbii respective (exemplu pentru romana scrieti ro pentru engleza scrieti en pentru ambele limbi scrieti ro, en - despartite de virgula si 1 spatiu) se pot trece cate limbi doriti. Daca meniul nu are setata corect limba nu se va putea afisa (puteti incerca sa il salvati cu &lt;all&gt; pentru limbaj dar este posibil ca in vesiunile viitoare sa se renunte la aceasta conventie). De asemenea, trebuie sa creati aceleasi meniuri pentru fiecare limbaj existent in site, pastrand link-urile dar schimband limbajul. <br>
        Drepturile meniului au mai fost discutate si la sectiunea 
        <a href="#schimbare_drepturi"> Schimbare drepturi de acces</a> In functie de ce drepturi alocati meniului, doar userii care au acelasi nivel de acces sau mai mare pot accesa acel meniu. Pentru a putea fi vazut de toti userii indiferent de drepturi selectati Guest, iar daca doriti ca meniul sa poata fi accesat doar dupa logare, selectati User.<br>
        Ultimul camp este pentru introducerea codului html al meniului. In cazul in care in cadrul meniului folositi imagini, acestea trebuie salvate in prealabil pe server in locatia corespunzatoare (de preferinta in template-ul respectiv in directorul BUTONS, astfel ca pentru fiecare tema sa aveti imaginile corespunzatoare) Meniul se creaza in prealabil intr-un editor  astfel codul rezultat sa se salveze in acest camp. Meniul poate fi de orice tip (text, html, css sau java) el urmand ca dupa prelucrare sa se salveze in baza de date. Se poate folosi &lt;div&gt; in loc de tabele, style css pentru culori (in acest caz adaugati stilul in fisierul css.php din tema respectiva)<br>
        In cazul in care meniul a fost adaugat acesta va aparea in lista la afisarea meniurilor din baza de date.<br>
        Incepand de la versiunea 1.0 trebuie specificat exact limba pentru meniu altfel nu se va putea afisa (a fost scoasa optiunea &quot;all&quot;) <br>
        Codul meniului si datele cerute va fi salvat in baza de date, deci nu se permite decat cod css, html si javascript. Puteti folosi imagini pe post de butoane la meniu, de asemenea puteti crea meniuri tip drop-down dar in acest caz trebuie sa modificati manual fisierele css.php (in cazul cand creati meniuri folosind css) existente in directorul &quot;THEMES&quot; si sa salvati imaginile butoanelor in subdirectorul temei respective (exemplu &quot;themes/red/butons/&quot;) Numele stilului default pentru meniurile create in css este &quot;glink&quot;, deci pentru a modifica stilul sau culorile meniurilor existente verificati in fiecare tema fisierul css.php <br>
        Toate meniurile create se pot vedea de catre userii cu acces 1-4 din modulul de modificare meniuri, dar fiecare user poate modifica sau sterge doar meniurile care au acces mai mic sau egal cu al lor (adica un user cu drept de acces 4 nu va putea modifica sau sterge un meniu setat pe nivel 1-3 dar il va putea vedea si accesa din modulul de modificare a meniurilor) </p>
      <p align="justify"> <em><strong>II.b.2. Schimbare stare meniuri</strong></em><a name="schimbare_meniu"></a><br>
        <br> 
        Daca doriti sa schimbati un meniu deja creat, sau doriti sa ii adaugati inca un buton sau doar doriti sa faceti o modificare, puteti accesa modulul de meniuri specificand id-ul meniului respectiv si actiunea de modificare, si anume:<br> 
        <a href="admin.php?m=menu&id=1&action=m" target="_blank">admin.php?m=menu&amp;id=1&amp;action=m</a><br>
        In exemplul de mai sus, m=menu este modulul care se incarca in pagina admin.php, id=1 este id-ul meniului care se gaseste in baza de date si action=m este actiunea care va fi facuta pentru meniul ales (in acest caz m=modificare). In cazul in care id-ul cerut nu exista in baza de date va fi afisat un mesaj de eroare. De asemenea daca meniul cerut are nivelul de acces mai mare decat al userului care a solocitat modificarea, va aparea un mesaj de eroare.<br>
        In cazul in care verificarea este in regula se afiseaza un formular care are setate initial starea meniului preluata din baza de date. Dupa ce user-ul face modificarile, meniul cu noile setari se actualizeaza in baza de date (nu se scrie un meniu nou, se suprascriu setarile din bd care corespund id-ului). Atentie la nivelul de acces setat, daca il dati mai mare decat nivelul dvs de acces data viitoare nu veti mai putea sa il accesati.</p>
      <p align="justify"><em><strong>II.b.3. Stergere meniuri</strong></em><a name="stergere_meniu"></a><br>
        <br>
        Pentru stergerea unui meniu, se acceseaza modulul de meniuri cu urmatorul parametru:<br>
         <a href="admin.php?m=menu&id=1&action=s" target="_blank">admin.php?m=menu&amp;id=1&amp;action=s</a><br>
         unde m=menu este modulul care se incarca in pagina admin.php, id=1 este id-ul meniului care se gaseste in baza de date si action=s este actiunea care va fi facuta pentru meniul ales (in acest caz s=stergere). In cazul in care id-ul cerut nu exista in baza de date va fi afisat un mesaj de eroare. De asemenea, daca meniul cerut are nivelul de acces mai mare decat al user-ului care a solicitat actiunea, va aparea un mesaj de eroare. (un user cu nivel de acces 4 de exemplu nu va putea sterge sau modifica meniul setat pe nivel 1-3) <br>
        Daca verificarea de acces este in regula, meniul corespunzator id-ului cerut se va sterge din baza de date.<br>
        <em><strong><br>
        II.b.4. Afisare lista cu meniuri existente</strong></em><a name="afisare_lista_meniuri"></a><br>
        <br>
        Pentru a vedea ce meniuri sunt create in baza de date folositi link-ul urmator:<br>
        <a href="admin.php?m=menu">admin.php?m=menu </a><br>
        Va aparea o lista cu meniurile existente in baza de date create pentru nivel egal sau mai mic decat al userului curent. (Pentru a vedea toate meniurile trebuie sa fiti logat ca administrator)</p>
      <p align="justify"><em><strong>II.c. Modulul de continut</strong></em><a name="modul_continut"></a><br>
        <br>
        Acest modul se afla in directorul &quot;MODULES&quot; si se numeste <em>content.php</em>. Pentru accesarea lui se foloseste urmatorul link:<br>
        <a href="admin.php?m=content" target="_blank">admin.php?m=content</a><br>
        Dupa accesarea acestui modul, trebuie sa fiti logati ca sa puteti lucra cu acest modul, iar nivelul de acces trebuie sa fie intre 1-4. Acest modul il puteti folosii pentru a introduce continut in paginile dvs (articole, imagini, fisiere). Tot cu ajutorul acestui modul se poate verifica versiunea FAR-PHP instalata (util in cazul  unui update). Pentru a verifica versiunea instalata dati urmatoarea comanda:<br>
        <a href="admin.php?m=content&ver=ok" target="_blank">admin.php?m=content&amp;ver=ok</a><br>
        Se va afisa versiunea instalata, adresa unde este instalat, data cand a fost instalat si numele administratorului (cerut la instalarea initiala) cu adresa de e-mail. <br>
        In cazul in care doriti sa introduceti un articol, accesati acest modul si va va apare un formular. Acest formular va intreaba daca articolul va contine poze (caz in care trebuie sa bifati casuta respectiva si sa selectati nr de poze). In cazul in care selectati aceasta optiune va apare un formular pentru upload-ul pozelor pe server. (In cazul in care doriti sa le transferati prin ftp, toate imaginile continute in articole trebuie salvate in directorul <em>content/images/</em> iar calea pentru afisarea acestor imagini trebuie scrisa in articol astfel:<br>
        <em><a href="#">&lt;a href=&quot;content/images/nume_poza.extensie&quot; target=&quot;_blank&quot;&gt;<br>
&lt;img src=&quot;content/images/nume_poza.extensie&quot; alt=&quot;text alternativ&quot; width=&quot;4&quot; height=&quot;4&quot; border=&quot;0&quot;&gt;<br>
&lt;/a&gt;</a></em><br>
Urmatorul punct in formular este daca doriti sa uploadati fisiere pe server (in cazul in care articolul face referire la arhive zip sau alt tip de fisier care se vor downloada atunci trebuie selectata aceasta optiune) Va apare un formular pentru upload-ul acestor fisiere. In cazul in care doriti sa uploadati aceste fisiere prin ftp, trebuie salvate in directorul <em>content/files/</em><br>
Ultimul punct din formular este daca articolul va contine cod php care trebuie rulat pe server. In acest caz trebuie sa aveti acest fisier cu extensia php, care va fi pus pe server in directorul <em>content/</em> iar accesul la acest fisier va fi astfel:<br>
<a href="#">http://www.nume_site/index.php?c=nume_fisier</a><br>
Atentie: Pentru accesarea fisierelor care contin cod php nu trebuie sa precificati calea sau extensia, FAR-PHP va  pune automat extensia si va cauta fisierul in directorul <em>content/</em><br>
In cazul in care doriti sa puneti un articol simplu fara poze sau alte fisiere, nu selectati nimic doar apasati pe butonul de continuare. In acest caz va apare un alt formular in care trebuie sa introduceti textul articolului (textul poate fi formatat folosind cod html sau java), titlul paginii (acest titlu va apare in cazul in care se doreste afisarea tuturor articolelor existente), numele de fisier (trebuie sa specificati un nume sub care va fi salvat acest articol - numele trebuie sa fie fara spatii sau caractere speciale si fara extensie), autorul articolului (puteti sa introduceti nick-ul dvs in caz ca articolul e scris de dvs.) adresa de e-mail a autorului (trebuie sa fie o adresa valida) si limbajul articolului (in cazul in care articolul nu are setata corect limba nu se va putea afisa). Setarea limbii trebuie sa corespunda cu numele fisierelor de mesaje si anume - pentru continut in limba romana, trebuie specificat limba &lt;ro&gt;, pentru continut in engleza se specifica &lt;en&gt; si la fel si pentru celelalte limbi. Puteti verifica articolul apasand pe butonul de verificare, in acest caz se va afisa articolul exact cum va apare el pe site, urmand sa ii mai faceti eventualele modificari. In cazul selectiei butonului de trimitere, articolul se va salva in baza de date si se va putea accesa de catre useri din cadrul meniurilor sau din cadrul pagini de cuprins.<br>
Este posibil 
ca in urmatoarele versiuni sa se introduca noi facilitati pentru acest modul...<br>
<br>
<em><strong>II.c.1. Adaugare continut nou (articole noi)</strong></em><a name="modul_continut_nou"></a><br>
<br>
Se pot introduce 2 tipuri de continut, si anume: <br>
a. Continut simplu in format text (formatat html, css sau java)
<br>
b. Continut cu scripturi php care va fi rulat pe server.<br>
<br>
<em><strong>II.c.1.a. Adaugare continut in format text</strong></em><a name="continut_html"></a> <br>
<br>
Acest tip de continut poate sa fie text simplu, text formatat cu cod html sau css sau scripturi client side (java). Articolele salvate astfel pot contine imagini, link-uri sau fisiere pentru download. In continuare se va detalia felul de introducere a articolului in functie de situatie:<br>
<br>
<strong><a name="continut_varianta_1"></a>Varianta 1:</strong> Articol simplu, care nu contine imagini sau fisiere, dar poate contine formatare html<br>
Se apeleaza modulul de introducere continut si se apasa pe butonul de continuare. In formularul aparut, in campul in care se cere introducerea textului se va scrie textul articolului impreuna cu codul html pentru formatarea articolului (se poate face copy-paste in cazul in care articolul este deja scris intr-un editor de texte).<br>
In campul <em>Titlul pagini </em>se va introduce titlul articolului (preferabil maxim 256 de caractere, dar in aceasta versiune nu exista limita impusa). Titlul va fi afisat in pagina de cuprins a articolelor de pe site, si va apare si in articolul propiuzis pe post de titlu.<br>
Urmatorul camp este <em>Numele de fisier</em>. Aici trebuie introdus un nume generic sub care va fi salvat articolul in baza de date. Dupa acest nume se va putea accesa articolul din meniuri astfel:<br>
<a href="#">http://www.nume_site/index.php?p=nume_articol</a><br>
unde <em>nume_articol</em> va fi de fapt numele de fisier pe care l-ati specificat (recomandabil sa nu specificati nume de fisiere prea lungi, sau care contin spatii sau caractere speciale. De asemenea articolul fiind salvat in baza de date, nu trebuie specificata o extensie.<br>
Urmatorul camp din formular este <em>Autor</em>, unde se va introduce numele autorului articolului (in cazul in care articolul este scris de alta persoana legea privind drepturile de autor stipuleaza sa mentionati sursa articolului - in acest caz numele autorului). Daca articolul este scris de dvs. specificati numele sau nick-ul dvs, pentru a va putea fi recunoscute drepturile de autor.<br>
Urmeaza apoi campul in care se introduce adresa de e-mail a autorului (trebuie sa fie o adresa valida). In cazul in care nu se cunoaste adresa autorului, se va introduce adresa de e-mail a celui care a pus articolul, pentru a putea fi contactat in cazul in care cineva este interesat de alte detalii referitoare la acel articol. <br>
In ultimul camp se va introduce limbajul articolului. In cazul in care articolul va fi afisat indiferent de preferintele vizitatorilor, trebuie specificat 0 (zero).<br>
Daca doriti sa vedeti cum va arata articolul inainte de a fi salvat in baza de date apasati butonul &quot;Verificare&quot; si va fi afisat articolul exact cum va apare el pe site. Puteti face eventualele modificari inainte de a apasa pe butonul &quot;Trimite&quot; (in acest caz, articolul se va salva in baza de date si va putea fi accesat de catre vizitatorii site-ului)<br>
<br>
<a name="continut_varianta_2"></a><strong>Varianta 2</strong>: Articol simplu, dar care contine si imagini.<br>
Identic ca in prima varianta doar ca trebuie selectate numarul de imagini pe care doriti sa le uploadati pe server inainte de a pune si articolul. Imaginile se vor salva in directorul <em>content/images/</em> deci link-urile in articol pentru imagini vor fi de genul<br>
<a href="#">&lt;a href=&quot;content/images/nume_poza.jpg&quot; target=&quot;_blank&quot;&gt;<br> 
&lt;img src=&quot;content/images/nume_poza.jpg&quot; alt=&quot;text alternativ&quot; width=&quot;152&quot; height=&quot;240&quot; border=&quot;0&quot;&gt;<br>
&lt;/a&gt;</a><br>
Atentie! Pentru a putea incarca imagini pe server, trebuie ca serverul sa permita acest lucru si chmod-ul sa fie corect setat pentru directorul &quot;CONTENT&quot; <br> 
          <br>
          <a name="continut_varianta_3"></a><strong>Varianta 3:</strong> Articol simplu, dar care contine link-uri catre fisiere pentru download.<br>
          La fel ca in varianta 2 singura diferenta e ca in loc de poze se vor pune pe server fisiere de orice tip (deci trebuie selectat de data aceasta nr de fisiere pentru upload in loc de poze). Fisierele se uploadeaza in directorul <em>content/files/ </em><br>
          Atentie! Pentru a putea incarca fisiere pe server, trebuie ca serverul sa permita acest lucru si chmod-ul sa fie corect setat pentru directorul &quot;CONTENT&quot; <br>
          <br>
          <a name="continut_varianta_4"></a><strong>Varianta 4:</strong> Articol simplu, dar care contine si imagini si link-uri catre fisiere pentru download.<br>
          La fel ca in variantele de mai sus, doar ca trebuie selectat inainte de a posta articoleul, numarul de poze si fisiere care se vor uploada pe server odata cu articolul. In caz de erori, se pot pune si manual prin ftp pozele si fisierele in directoarele specificate, urmand ca sa postati prin intermediul modulului doar articolul propriuzis. <br>
          Atentie! Pentru a putea incarca imagini si fisiere pe server, trebuie ca serverul sa permita acest lucru si chmod-ul sa fie corect setat pentru directorul &quot;CONTENT&quot; <br>
          <br>
          <a name="continut_varianta_5"></a><strong>Varianta 5.</strong> Articol sau pagina care contine script-uri php care trebuie rulate pe server. <br>
          Daca bifati aceasta optiune textul pe care il introduceti va fi salvat sub forma de fisier cu numele specificat de dvs. in directorul <em>content/ </em>In caz de erori la upload, puteti sa salvati fisierul si manual prin ftp in directorul specificat. Atentie! Pentru a putea incarca fisiere pe server prin interfata, trebuie ca serverul sa permita acest lucru si chmod-ul sa fie corect setat pentru directorul &quot;CONTENT&quot; <br>
          <br>
          <em><strong>II.c.1.b. Adaugare pagini cu scripturi php care ruleaza pe server </strong></em><br>
          <br>
        Este de preferat ca paginile cu scripturi php sa le salvati in directorul <em>content/</em> Astfel paginile dvs vor putea fi incluse in tema site-ului prin intermediul link-ului:<br>
        <a href="#">&lt;a href=&quot;index.php?c=nume_fisier&quot;&gt;Nume fisier &lt;/a&gt;</a> <br>
        <br>
        <em><strong>II.c.2. Modificare continut existent</strong></em><a name="modul_content_2"></a><br>
        <br>
        Pentru a modifica continutul unei pagini salvate in baza de date, folositi modulul <em>content_2.php</em> astfel:<br>
        <a href="admin.php?m=content_2">admin.php?m=content_2</a><br>
        Va aparea o pagina cu un link care contine toate titlurile existente in baza de date. Selectati din lista pagina pe care doriti sa o modificati (sau stergeti) si dupa ce a fost selectata specificati actiunea dorita (modificare sau stergere) Antentie, nu puteti specifica manual numele paginii, trebuie selectata din lista. Dupa specificarea actiunii de modificare in continuare se va afisa pagina aleasa si veti avea posibilitatea sa modificati continutul. <br>
        <br>
        <em><strong>II.c.3. Stergere continut existent <a name="modul_stergere_continut"></a><br>
        <br>
        </strong></em>Pentru a sterge continutul unei pagini salvate in baza de date, folositi modulul content_2.php asa cum am precizat mai sus in capitolul <a href="#modul_content_2">Modificare continut existent</a>. Atentie, datele sterse nu mai pot fi recuperate fara un back-up facut inainte la baza de date. <br>
        <br>
        <em><strong>II.c.4. Afisare articole existente pentru un anumit limbaj</strong></em><a name="afisare_articole_limba"></a><br>
        <br>
        Pentru a putea vedea ce articole exista in baza de date pentru limbajul curent puteti crea un meniu folosind urmatorul link:<br>
        <a href="index.php?p=content%20">index.php?p=content 
        </a><br>
        Se va afisa o lista cu titlurile tuturor articolelor existente in baza de date in limba curenta. Pentru a vedea si celelalte articole scrise pentru celelalte limbi utilizate in site, trebuie sa schimbati limba curenta. <br>
        <br>
        <em><strong>II.c.5. Afisare toate articolele existente in baza de date</strong></em>        <a name="afisare_toate_articolele"></a> <br>
        <br>
        Daca doriti sa vedeti toate articolele existente in baza de date, indiferent pentru ce limba au fost scrise, puteti intra in modulul de modificare a continutului si le puteti vedea in lista. Puteti folosi si acest link care va apela modulul de modificare si va afisa lista:<br>
        <a href="admin.php?m=content_2&action=list">admin.php?m=content_2&amp;action=list </a></p>
      <p align="justify"><em><strong>II.d. Modulul de stiri</strong></em><a name="modul_stiri"></a><br>
        <br>
        Acest modul se afla in stadiul de idee in acest moment, nu va pot spune nimic despre el pana nu e finalizat.<br>
        <br>
        <em><strong>II.e. Modulul de limbaj<a name="modul_language"></a><br>
        <br>
        </strong></em>Acest modul a fost introdus ulterior creerii variantei initiale a proiectului, iar in acest moment nu functioneaza decat pentru 2 limbaje: romana si engleza. Se bazeaza in principal pe fisierele de limbaj existente in directorul <em>codes/ </em>si pe modulul <em>language.php</em> existent in dorectorul de module (<em>modules/</em>) Cu ajutorul lui se afiseaza continutul in pagina in functie de limbajul ales, astfel pot exista in baza de date 2 articole cu acelasi nume dar cu limbaj diferit. In acest fel se usureaza munca la crearea meniurilor, existand acelasi link pentru aceeasi pagina, diferenta este data de limbajul ales. <span class="adaugari_manual">Incepand de la versiunea 1.0 fiecare modul adaugat ulterior are propria functie sau variabila care contine mesajele necesare acelui modul. Modulele nu influenteaza continutul pagini dar contin mesajele de atentionare sau eroare care se afiseaza in functie de limbajul specificat. In cazul in care creati pagini pentru un limbaj care nu este inclus in versiunea originala, unele module vor afisa mesajele de eroare si atentionare in limba engleza, in cazul in care nu au fost traduse si pentru limba respectiva.<br>
        </span><br>
        <em><strong>II.e.1 Setarea limbajului principal</strong></em><a name="setare_limbaj_principal"></a><br>
        <br>
        Setarea limbajului principal se face la inceputul instalarii proiectului pe server, alegand din lista limbajul default. Daca doriti sa schimbati limbajul principal ulterior, trebuie sa editati manual fisierul config.php existent si sa modificati acolo liniile:<br>
        $mesaje = &quot;codes/language_ro.php&quot;; // aici specificati fisierul principal cu mesaje si<br>
        $limbaj_primar = &quot;ro&quot;; // aici specificati prescurtat limbajul principal (astfel daca fisierul de mesaje este language_en.php aici specificati literele en, daca este languaje_jp.php specificati literele jp)<br>
        De asemenea, la introducerea articolelor si meniurilor in baza de date trebuie sa specificati exact aceste litere pentru fiecare limbaj in parte, altfel articolul pus nu va fi afisat in pagina. <br>
        <br>
        <em><strong>II.e.2. Schimbarea intre limbaje diferite<a name="schimbare_limbaj"></a> </strong></em><br>
        <br>
        Schimbarea intre limbaje se face simplu selectand din modulul language.php limbajul preferat. Daca pagina dvs de web contine doar un limbaj, puteti renunta la afisarea acestui modul in pagini stergand efectiv din paginile de teme linia care incarca acest modul. Atentie! Nu trebuie sa stergeti modulul ci doar linia de cod care apeleaza modului, altfel pot aparea probleme.<br>
        Linia de cod care apeleaza acest modul este:<br>
        $cerere = 12;<br>
        include (&quot;codes/body.php&quot;); <br>
        Va rugam sa nu stergeti altceva pentru ca este posibil sa rezulte erori.<br>
        <span class="adaugari_manual">Incepand de la versiunea 1.0 s-a renuntat la acesta abordare si a fost simplificata printr-o singura linie de cod si anume:<br>
        <em>body_far(&quot;language&quot;); // includere partea de limbaj </em><br>
        Aceasta linie se poate sterge si nu se va mai include modulul de limbaj si in acest caz se va utiliza doar limba specificata in fisierul config.php</span><br>
        <br>
        <em><strong>II.e.3. Crearea unui nou limbaj (traducerea unuia existent) </strong></em><a name="creare_limbaj"></a><br>
        <br>
        Pentru a putea accepta un nou limbaj si a afisa articolele si meniurile specifice limbajului respectiv, trebuie tradus fisierul cu mesaje in limba noua si salvat cu un nume nou in directorul <em>codes/</em> Astfel daca traduceti fisierul cu mesaje in limba rusa de exemplu, fisierul trebuie apoi salvat in directorul <em>codes</em> sub numele <em>language_ru.php</em> si modificat modulul <em>language.php</em> din directorul <em>modules</em> pentru a putea incarca noul limbaj. In cazul in care traduceti fisierul cu mesaje il puteti trimite pe adresa proiectului si noi o sa il integram automat in proiect, dumneavoastra avand meritul traducerii acelui fisier. Veti primi urmatoarea versiune a proiectului care va contine setarile pentru noul limbaj.<span class="adaugari_manual"> Incepand de la versiunea 1.0 fiecare modul aditional contine propriile mesaje de eroare si atentionare, care trebuie tradus separat. In general fiecare modul contine o functie sau un array cu mesajele care sunt folosite de acel modul si care se poate modifica usor pentru noul limbaj. <br>
        </span><br>
        <em class="adaugari_manual"><strong>II.f. Modulul panoul de comanda</strong></em> <a name="cpanel"></a><br>
        <br>
        Panoul de comanda este de fapt un simplu fisier php care contine linkuri catre toate modulele existente, facand astfel mai usoara administrarea. In functie de ce module sunt instalate, vor apare linkurile pentru respectivul modul (daca adaugati un modul si acesta nu apare in panoul de control trebuie sa faceti un update la cpanel.php - pentru aceasta vizitati regulat pagina de download de la www.far-php.ro) <br>
        <span class="adaugari_manual"><br>
        <em><strong>II.g. Modulul pentru monitorizarea accesarilor nepermise si al botilor</strong></em></span> <a name="robots"></a><br>
        <br>
        Incepand de la versiunea 1.0 a fost adaugata si o monitorizare minimala a accesarilor nepermise. Pentru aceasta a fost creat un tabel nou in baza de date numit &quot;robots&quot; iar in fiecare subdirector a fost creat fisierul &quot;index.php&quot;. In cazul in care cineva incearca sa intre intr-unul din aceste subdirectoare scriptul existent in index.php salveaza un log in baza de date care contine data, ip, browser, referer (daca este) si adresa la pe care s-a incercat sa se intre. Dupa salvarea datelor in baza de date se redirecteaza vizitatorul catre pagina principala. Puteti vedea acest log folosind adresa <br>
        <a href="admin.php?m=robots">admin.php?m=robots</a><br>
        Pentru a bloca o adresa ip puteti folosi modulul <a href="#blockip">blockip</a>. In mod normal, robotii de cautare folosesc informatiile scrise in header si in fisierul <em>robots.txt</em> pentru a indexa paginile de pe site. Puteti edita headerul si fisierul robots.txt pentru a specifica robotilor in care directoare si fisiere nu au voie sa umble. In cazul in care unul din acesti roboti nu respecta specificatiile date il puteti vedea in log, si astfel ii puteti bloca accesul pe viitor. Pentru alte intrebari referitoare la indexarea unui site sau la robotii de cautare cititi <a href="http://www.robotstxt.org/wc/faq.html" target="_blank">http://www.robotstxt.org/wc/faq.html</a><br>
        <br>
        <em class="adaugari_manual"><strong>II.h. Modulul de instalare a proiectului FAR-PHP</strong></em> <a name="install"></a><br>
        <br>
        Dupa copierea fisierelor pe server, in browser va apare partea de instalare a proiectului. In acest modul puteti selecta limba in care se va face instalarea. Dupa selectarea limbajului, informatiile cerute in continuare vor fi folosite pentru crearea fisierului <em>config.php</em> si a tabelelor din baza de date. In functie de limba selectata pentru instalare, se vor crea in baza de date meniurile predefinite (daca limbajul e setat pe engleza meniurile vor apare in site doar in engleza, si invers). Datele pentru configurare se impart in 3 parti:<br>
        <br>
        <em>Setari pentru Baza de date MySQL</em><br>
        Gazda (Host):        = aici se cere adresa unde se afla baza de date (de obicei este <em>localhost</em>) <br>
        User: = userul cu care va conectati la baza de date (de obicei <em>root</em>) <br>
        Parola: = parola setata pentru MySQL (in cazul in care lucrati pe local si nu aveti setata o parola, instalarea nu va rula mai departe fara o parola)<br>
        Nume baza de date: = numele bazei de date unde vor fi create tabelele pentru FAR-PHP<br>
        Prefixul tabelelor din baza de date: = se cere prefixul care va fi pus inaintea tabelelor, astfel ca puteti avea mai multe proiecte FAR-PHP in aceeasi baza de date dar care difera prin prefix.<br>
        <em>Setari pentru server:        </em><br>
        Prefixul la cookies si variabilele de sesiune: = prefixul care va fi pus inaintea variabilelor de sesiune si a cookies, in cazul in care aveti mai multe proiecte FAR-PHP pe acelasi domeniu sa poata fi diferentiate prin prefix.<br>
        Diferenta de ora de pe server fata de ora locala (+ sau - x ore): = in cazul in care ora de pe server difera fata de ora locala, puteti seta aici diferenta.<br>
        Adresa web a paginii: = aici scrieti exact adresa unde este instalat proiectul FAR-PHP (exemplu http://localhost/far-php/ ) aceasta adresa este folosita pentru redirectare catre pagina principala in toate modulele proiectului.<br>
        Tema principala a site-ului: = template-ul principal pe care vreti sa il vada vizitatorii<br>
        Limbajul principal: = limbajul principal in care vor fi afisate paginile site-ului (poate diferii de limbajul selectat pentru instalare)<br>
        <em>Setari de administrare:</em><br>        
        Parola criptata: = felul cum se vor salva parolele in baza de date (este bine sa fie salvate criptate pentru mai multa siguranta)<br>
        Nr. de incercari de logare nereusite: = in cazul in care un user incearca de mai multe ori sa se logheze dar nu stie userul/parola corecte, peste nr de incercari setate i se va bloca temporar accesul. Este recomandat pentru siguranta un nr de maxim 5 incercari, astfel se evita incercarea de obtinere a accesului prin combinatii succesive de user/parola.<br>
        Numele userului cu drept de adminstrator: =         Aici se cere userul care va fi salvat in baza de date cu drepturi de administrare.<br>
        E-mail administrator: = adresa de e-mail         folosita de modulele proiectului pentru a primi/trimite mesaje.<br>
        Parola admin: = parola pentru userul cu drepturi de administrator (recomandam sa aiba peste 7 caractere inclusiv litere mici si mari si caractere speciale, pentru a fi mai greu de spart)<br>
        Numele userului cu drept de Sub-administrator (daca exista): = optional se cere userul care va fi salvat in baza de date cu drepturi de sub-admin.<br>
        E-mail sub-administrator: = optional adresa de mail pentru sub-admin<br>
        Parola sub-administrator: = optional parola pentru sub-admin<br>
        Mesaj pentru partea de jos a paginii: = mesajul care va apare in josul paginii (se accepta orice cod html)<br>
        <br>
        In cazul in care au fost completate corect campurile cerute
        se vor genera tabelele in baza de date, se vor crea meniurile pentru administrare si se va incerca generarea fisierului config.php. In cazul in care nu se reuseste generarea acestui fisier, va apare in browser textul care trebuie copiat identic in fisierul config.php si apoi salvat manual prin ftp pe server. Dupa salvarea acestui fisier pe server, proiectul este instalat corespunzator si se poate sterge fisierul <em>install.php</em> <br>
        In cazul in care apar alte mesaje de eroare la instalare, sau aplicatia nu functioneaza corect dupa instalare, cititi capitolul <a href="#instal_problem">Probleme aparute la instalare</a><br>
          <br>
          <em class="adaugari_manual"><strong>II.i. Modulul de schimbare teme (template)</strong></em> <a name="mod_ch_template"></a><br>
          <br>
          Acest modul schimba tema initiala a paginii cu o alta tema existenta pe site. Pentru accesare utilizati adresa de mai jos:<br>
          <a href="admin.php?m=ch_template">admin.php?m=ch_template</a><br>
          Acest modul citeste numele temelor existente in directorul THEMES si le afiseaza in lista. Dupa selectarea noii teme se modifica valoarea variabilei de sesiune cu noua tema si se modifica cookies. In cazul in care nu se poate salva noul cookies, data viitoare tema afisata va fi tot cea initiala. <br>
          <br>
          <em class="adaugari_manual"><strong>II.j. Modulul pentru blocare ip</strong></em> <a name="blockip"></a><br>
          <br>
          Acest modul permite blocarea accesului anumitor vizitatori pe o anumita perioada la site. Cu ajutorul acestui modul se pot adauga/modifica/sterge adrese de ip. Doar useri cu nivel de acces 1-4 pot avea acces la acest modul. <br>
          <br>
          <em class="adaugari_manual"><strong>II.j.1. Afisare lista cu ip-uri blocate</strong></em><a name="blockip_lista"></a><br>
          <br>
          Pentru a vedea ce adrese de ip sunt blocate si pe ce perioada folositi adresa de mai jos:<br>
          <a href="admin.php?m=blockip&action=show">admin.php?m=blockip&amp;action=show</a>          <br>
          Va apare o lista cu ip, data de start, data de stop, data si ora cand a fost adaugat in lista si 2 link-uri.<br>
          Ip =&gt; este ip-ul care este blocat<br>
          Data si ora =&gt; este data si ora cand acel ip a fost adaugat in lista<br>
          Data start =&gt; este data cand acel ip nu va mai avea acces pe site<br>
          Data stop =&gt; 
          este data cand blocarea pentru acel ip va fi terminata si va avea acces pe site<br>
          M =&gt; (primul link) permite modificarea informatiilor despre acel ip<br>
          D =&gt; (al doilea link) permite stergerea acelui ip din lista <br>
          <br>
          <em class="adaugari_manual"><strong>II.j.2. Modificare date ip</strong></em><a name="blockip_modificare" id="blockip_modificare"></a><br>
          <br>
          Pentru a modifica datele despre un ip, puteti folosi link-ul urmator:<br>
          <a href="admin.php?m=blockip&action=modify">admin.php?m=blockip&amp;action=modify</a>          <br>
          Introduceti ip-ul pe care doriti sa il modificati si va apare un formular cu datele existente in baza de date. Dupa modificare, datele noi se vor salva in baza de date in locul celor vechi.
          <br>
          <br>
          <em class="adaugari_manual"><strong>II.j.3. Adaugare ip</strong></em><a name="blockip_adaugare"></a><br>
          <br>
          Pentru a adauga un ip nou in baza de date, folositi urmatorul link:<br>
          <a href="admin.php?m=blockip">admin.php?m=blockip</a>          <br>
          Dupa adaugare, blocarea va deveni activa incepand de la data de start.
          <br>
          <br>
          <em class="adaugari_manual"><strong>II.j.4. Stergere ip</strong></em>          <a name="blockip_stergere"></a><br>
          <br>
          Pentru a sterge un ip din baza de date, folositi:<br>
          <a href="admin.php?m=blockip&action=del">admin.php?m=blockip&amp;action=del </a><br>
          Introduceti ip-ul pe care doriti sa il stergeti si acesta va fi sters automat din baza de date. <br>
          <br>
          <em class="adaugari_manual"><strong>II.2. Module aditionale (add-on)</strong></em> <a name="addon_modules"></a><br>
          <br>
          Modulele aditionale (add-on) sunt modulele care nu fac parte din proiectul initial, dar care adaugate in proiect, aduc diferite imbunatatiri si noi facilitati. Aceste module pot fi adaugate/sterse oricand fara a modifica codul initial al proiectului. Pentru adaugarea unui modul acesta trebuie salvat in directorul MODULES si apoi adaugat optional codul in template pentru a fi inclus in acea tema. Adaugarea modulului in tema se face scriind codul de mai jos in locul unde vreti sa se afiseze acel modul (in template) astfel:<br>
          body_far(&quot;nume_modul&quot;);<br>
          In cazul in care doriti ca un modul aditional sa fie accesat din meniu sau dintr-un link puneti acest cod in acel link:<br>
          <a href="#">admin.php?m=nume_modul 
          </a><br>
          In continuare sunt descrise modulele oficiale care se pot adauga.<br>
          <span class="adaugari_manual"><br>
          <em><strong>II.2.a. Modulul pentru control bannere</strong></em></span> <a name="banner"></a><br>
          <br>
          Acest modul este de tip add-on (separat de proiect) si se poate adauga copiind fisierul <em>banner.php</em> in directorul MODULES. Modulul este distribuit in 2 variante, varianta simpla care nu permite monitorizarea dupa click si nici afisarea bannerelor doar odata unui singur vizitator, si care este distribuita in regim GNU/GPL (gratuit pentru utilizare personala, necomerciala, non-profit) si varianta full, care permite monitorizarea bannerelor dupa clik si afisarea bannerelor selectiv (adica un banner se poate afisa aceluiasi vizitato de mai multe ori sau doar o singura data). Varianta full este distribuita doar la cerere in regim GNU/GPL si costa 5 euro/modul/site + 35 euro/site proiectul FAR-PHP (modulul nu functioneaza independent de proiect, iar proiectul este distribuit in aceleasi conditii ca si acest modul - pentru alte detalii cititi capitolul <a href="#licenta">Licenta proiectului</a>) <br>
          Pentru a se putea afisa bannerele trebuie inclus codul de mai jos in teme acolo unde doriti sa fie afisat bannerul:<br>
          body_far(&quot;banner&quot;);<br>
          Nu se poate afisa decat un banner pe pagina. Prima data cand va fi rulat acest modul va genera automat tabelele necesare in baza de date folosind datele din fisierul config.php <br>
          Acest modul afiseaza si permite adaugarea/modificarea/stergerea de bannere. Modulul banner.php genereaza 2 variabile de sesiune si anume:<br>
          prefix_modul_banner          = contine nr bannerului care trebuie afisat<br>
          prefix_modul_banner2          = contine una din cele 3 valori posibile pentru a nu se putea afisa de 2 ori in pagina bannerul (in cazul in care se acceseaza modulul cu parametrii)<br>
          <br>
          <em class="adaugari_manual"><strong>II.2.a.1. Adaugare bannere</strong></em><a name="adaugare_banner"></a><br>
          <br>
          Pentru a adauga un banner nou folositi urmatoarea comanda:<br> 
          <a href="admin.php?m=banner&action=new">admin.php?m=banner&amp;action=new</a><br>
          Va apare un formular cu urmatoarele campuri:<br>
          Codul reclamei: = aici puteti introduce codul html/java script care va afisa bannerul.<br>
          Numele reclamei: = aici scrieti numele reclamei (pentru a putea sa o identificati in lista cu bannere)<br>
          Adresa web: =           adresa web la care se va duce in cazul in care vizitatorul da clik pe banner (optional) <br>
          Data start: = data cand doriti sa inceapa afisarea bannerului (in format YYYY-mm-dd)<br>
          Data stop: =           data cand nu se va mai afisa bannerul (optional)<br>
          Bifati daca doriti monitorizare afisare: = in cazul in care se bifeaza se va salva in baza de date nr de cate ori a fost afisat acest banner (optional) <br>
          Nr. afisari stop: = in cazul in care introduceti un nr, bannerul se va afisa pana cand nr total de afisari va fi egal cu nr specificat. (optional) <br>
          Bifati daca doriti monitorizare click: =           in cazul in care aveti versiunea full, puteti selecta aceasta optiune pentru a vedea cati vizitatori au dat clik pe acest banner (optional)<br>
          Nr. clik stop: = in cazul in care aveti optiunea full, si specificati aici un nr, bannerul se va afisa pana cand numarul de clik-uri va fi egal cu nr specificat. (optional)<br>
          Deci puteti afisa un banner incepand de la data start pana la data stop, sau pana cand se va afisa de x ori, sau pana cand  x vizitatori vor da clik pe el.<br>
          <br>
          <em class="adaugari_manual"><strong>II.2.a.2. Modificare bannere</strong></em> <a name="modificare_banner"></a><br>
          <br>
          Pentru a modifica datele unui banner existent folositi urmatoarea comanda:<br>
          <a href="admin.php?m=banner&action=change">admin.php?m=banner&amp;action=change</a>          <br>
          Va apare o pagina in care sunt scrise cate bannere exista in baza de date si un formular in care se cere sa introduceti id-ul bannerului pe care doriti sa il modificati (id-ul bannerului il aflati din pagina care va afiseaza toate bannerele - vezi capitolul Afisare lista bannere) Dupa introducerea id-ului va apare un formular cu datele care exista in baza de date pentru bannerul ales si puteti modifica ceea ce doriti. <br>
          <br>
          <em class="adaugari_manual"><strong>II.2.a.3. Stergere bannere</strong></em><a name="sterg_banner"></a>          <br>
          <br>
          Pentru a sterge un anumit banner folositi urmatoarea comanda:<br>
          <a href="admin.php?m=banner&action=del">admin.php?m=banner&amp;action=del</a><br>
          Va apare aceeasi pagina ca la partea de <a href="#modificare_banner">modificare bannere</a>           unde trebuie introdus id-ul bannerului pe care doriti sa il stergeti. (Atentie! Stergerea este ireversibila) <br>
          <br>
          <em class="adaugari_manual"><strong>II.2.a.4. Afisare lista bannere</strong></em><a name="toate_banner" id="toate_banner"></a>          <br>
          <br>
          Pentru a vedea toate bannerele existente in baza de date folositi comanda:<br>
          <a href="admin.php?m=banner&action=all">admin.php?m=banner&amp;action=all</a><br>
          Va fi afisat fiecare banner in parte, id-ul lui si detalii despre el. <br>
          <br>
          <em class="adaugari_manual"><strong>II.2.a.5. Afisare log banner</strong></em><a name="log_banner"></a>          <br>
          <br>
          Pentru a putea vedea informatii detaliate despre un anumit banner comanda este urmatoarea:<br>
          <a href="admin.php?m=banner&action=log">admin.php?m=banner&amp;action=log</a><br>
          Va apare un formular in care trebuie introdus id-ul bannerului pentru care doriti informatii (id-ul il puteti afla afisand lista de bannere existente). Dupa introducerea id-ului vor fi afisate informatii detaliate despre acel banner (nr de afisari facute, nr de clik facute etc) <br> 
          <br>
          <em class="adaugari_manual"><strong>II.2.b. 
          Modulul de afisare vizitatori online</strong></em> <a name="online"></a><br>
          <br>
          Acest modul este de tip add-on (separat de proiect) si se poate adauga copiind fisierul <em>online.php</em> in directorul MODULES. Modulul are 2 moduri si anume: <br>
          - In varianta simpla afiseaza nr total de vizitatori existenti pe site in ultimele 5 minute din care nr de useri logati existenti pe site, nr de vizitatori nelogati si nr de useri logati dar ascunsi.<br>
          - In varianta
          extinsa se afiseaza detalii pentru fiecare vizitator existent pe site si anume:<br>
          Numele userului (daca nu este ascuns), timpul de cand este online, pagina pe care o viziteaza in acel moment, adresa ip si host.<br>
          Pentru a include in site acest modul introduceti in fiecare tema acolo unde doriti sa fie afisat acest modul, urmatorul cod: <br>
          body_far(&quot;online&quot;);<br>
          Pentru a afisa varianta extinsa a acestui modul folositi urmatoarea comanda:<br>
          <a href="admin.php?m=online&action=see">admin.php?m=online&amp;action=see </a><br>
          Modulul online.php genereaza o variabila de sesiune si anume:<br>
          prefix_modul_online          = contine o valoare pentru a nu se afisa eronat informatiile in cazul in care modulul este apelat cu parametrii. <br>
          <span class="adaugari_manual"><br>
          <em><strong>II.2.c. Modulul pentru dezinstalare</strong></em></span> <a name="uninstall"></a><br>
          <br>
          Acest modul este de tip add-on (separat de proiect) si se poate adauga copiind fisierul <em>uninstall.php</em> in directorul MODULES.<br>
          Pentru dezinstalarea proiectului FAR-PHP de pe server folositi urmatoarea comanda:<br>
          <a href="admin.php?m=uninstall">admin.php?m=uninstall</a><br>
          Dupa rularea acestui modul, se va incerca stergerea tabelelor din baza de date care corespund proiectului (atentie, nu se sterg si tabelele create ulterior de catre modulele aditionale), se va incerca stergerea continutului fisierului config.php si dupa aceea se va incerca stergerea fizica a tuturor fisierelor si directoarelor existente pe server in directorul unde a fost instalat proiectul (atentie, se va incerca stergerea tuturor fisierelor din acest director, indiferent ca apartin proiectului sau nu). Toate informatiile privind starea dezinstalarii vor fi afisate in browser, iar dupa terminarea dezinstalarii se va redirecta catre pagina oficiala a proiectului FAR-PHP.<br>
          <br>
          <em class="adaugari_manual"><strong>II.2.d. Modulul pentru adaugare useri in PHPBB</strong></em> <a name="adduserphpbb"></a>        <br>
          <br>
          Acest modul este de tip add-on (separat de proiect) si se poate adauga copiind fisierul <strong>adduserphpbb.php</strong> in directorul MODULES. Pentru a putea fi functional acest modul, trebuie inlocuita valoarea variabilei <br>
          $prefix_tabel_forum_phpbb = &quot;phpbb_&quot;; // modificati daca nu corespunde prefixul tablelelor phpbb<br>
          cu prefixul specificat la instalarea forumului PHPBB.<br>
          Forumul PHPBB trebuie instalat inainte de a utiliza acest modul.<br>
          (deci instalati proiectul FAR-PHP, dupa care instalati PHPBB, dupa care setati valoarea 
          variabilei din modul si copiati modulul in directorul MODULES de pe server) <br>
          In cazul in care un vizitator se inscrie in FAR-PHP, acest modul va crea acelasi user/parola si in tabelul din PHPBB, astfel vizitatorul nu va trebui sa se inscrie de 2 ori. <br>
          <br>
          <em class="adaugari_manual"><strong>II.2.e. Modulul pentru control newsletter</strong></em> <a name="newsletter"></a>        <br>
          <br>
          In lucru<br>
          <br>
          <em class="adaugari_manual"><strong>II.3. Scripturi aditionale</strong></em> <a name="add_scripts"></a><br>
          <br>
          Scripturile aditionale (add-on) sunt fisiere php care nu fac parte din proiectul initial, dar care adaugate in proiect, aduc diferite imbunatatiri si noi facilitati. Aceste fisiere pot fi adaugate/sterse oricand fara a modifica codul initial al proiectului. Pentru adaugarea unui script acesta trebuie salvat in directorul CONTENT si apoi adaugat  codul in template pentru a fi inclus in acea tema. Unele scripturi se pot folosi si ca module, dar in acest caz trebuie salvate in directorul MODULES. In cazul in care doriti ca un script aditional sa fie accesat din meniu sau dintr-un link puneti acest cod in acel link:<br>
          <a href="#">index.php?c=nume_script</a><br>
In continuare sunt descrise scripturile oficiale care se pot adauga.<br>
          <br>
          <em class="adaugari_manual"><strong>II.3.a. Pagina de contact</strong></em> <a name="contact_1"></a>        <br>
          <br>
        Aceasta pagina este de tipul add-on (separat de proiect) si se poate pune pe server ulterior instalarii. Acest script trimite un mesaj prin intermediul paginii php catre adresa specificata. Pentru a putea fi functional, trebuie modificate corespunzator urmatoarele variabile:<br>
        <em>$adresa_pagina_contact = 'index.php?c=contact'; // adresa unde se afla acest fisier<br>
        $adresa_de_trimis = $email_admin; // adresa de e-mail folosita</em><br>
        In cazul in care scriptul va fi salvat in directorul CONTENT valoarea variabilei <em>$adresa_pagina_contact</em> va fi:<br> 
        <em>'index.php?c=contact'</em><br>
        In cazul in care se doreste folosit ca modul, si va fi salvat in directorul MODULES, valoarea variabilei <em>$adresa_pagina_contact </em>va fi:<br>
        <em>'admin.php?m=contact'</em><br>
        Pentru cealalta variabila <em>$adresa_de_trimis</em> in mod implicit se foloseste adresa de e-mail a administratorului, care a fost specificata la instalarea proiectului. In cazul in care doriti sa folositi alta adresa de e-mail unde vor veni mesajele, modificati valoarea variabilei <em>$adresa_de_trimis</em> in mod corespunzator:<br>
        <em>$adresa_de_trimis = 'adresa@domeniu.com'; </em><br>
        Dupa modificarile de mai sus puteti copia fisierul pe server si puteti crea un meniu sau un link catre el. <br>
          <br>
          <em class="adaugari_manual"><strong>II.3.b. Pagina pentru demonstratii</strong></em> <a name="demo_page"></a><br>
          <br>
          Aceasta pagina este de tipul add-on (separat de proiect) si se poate pune pe server ulterior instalarii. Acest script este folosit pentru demonstrarea accesului la o pagina si pentru demonstrarea afisarii mesajelor specifice in functie de limbajul ales. Prima parte verifica nivelul de acces, dupa care se creaza o variabila care contine toate mesajele care pot aparea in acea pagina in ambele limbi (romana si engleza) dupa care in functie de drepturile de acces ale vizitatorului afiseaza informatia ceruta in limba specificata. Puteti testa aceasta pagina inainte de logare si dupa logare, in ambele limbi ca sa vedeti ce anume se afiseaza si cum. Puteti folosi variabilele si informatiile din aceasta pagina pentru a va crea propriile scripturi si module pentru pagina voastra. <br>
          <br> 
          <em><strong>III. Schimbare template</strong></em><a name="schimba_template"></a><br>
          <br>
          Initial, tema site-ului este salvata in fisierul config.php. Pentru a schimba un template (o tema)
  se acceseaza modulul ch_template.php astfel:<br>
    <a href="admin.php?m=ch_template" target="_blank">admin.php?m=ch_template</a><br>
    Se acceseaza fara nici un parametru deoarece modulul citeste structura de directoare din directorul THEMES
    si afiseaza in formular temele gasite. Dupa selectarea temei, se sterg vechile variabile (variabilele de sesiune si cele de cookies) si se initializeaza unele noi care contin numele noii teme. Daca vizitatorul nu accepta cookies, tema aleasa nu poate fi selectata, sau daca va fi selectata, dupa inchiderea browserului setarile dispar. (se initializeaza prima data valoarea unei variabile de sesiune care citeste template-ul salvat in fisierul config.php. Dupa initializarea acestei variabile se verifica daca exista un cookies. <br>
    Daca exista sa rescrie valoarea variabilei cu noua valoare din cookies. <br>
    Daca nu exista, se creaza un cookies cu valoarea temei default. <br>
    Dupa setarea variabilei de sesiune si a cookies-ului, se redirecteaza pentru reinitializarea sesiuni. <br>
    <br>
    <em><strong>III.a. Cum adaug o tema noua</strong></em><a name="adaug_tema"></a><br>
    <br>
    In primul rand tema aleasa trebuie sa fie compatibila cu specificatiile proiectului referitoare la teme. Daca respecta specificatiile, puteti copia pur si simplu fisierele noii teme pe server si doar adaugati propriul cod css la cel existent in tema pentru a se potrivii cu specificul paginii dvs. In cazul in care ati creat o tema si nu stiti sa o faceti compatibila cu specificatiile proiectului, puteti sa o trimiteti pe adresa proiectului si noi o sa o modificam pentru a se potrivii cu proiectul dupa care o sa puteti sa o descarcati gratuit din pagina de download a proiectului. <span class="adaugari_manual">In cazul in care ati descarcat o tema si doar doriti sa o adaugati la pagina dvs. dupa dezarhivare copiati fisierele temei pe server (incluziv continutul directorului &quot;themes&quot;) dupa care tema noua va apare in lista de teme din modulul ch_template.php</span> <span class="adaugari_manual">In cazul in care doriti ca noua tema sa fie tema principala a site-ului, modificati fisierul config.php specificand numele noi teme in locul celei initiale. Daca aveti module aditionale instalate, nu uitati sa adaugati codul php respectiv pentru acele module in tema noua. La fel adaugati si codul css in cazul in care la celelalte teme a fost modificat fata de cel original. </span><br>
    <br>
    <em><strong>III.b. Cum sterg o tema din site</strong></em><a name="sterg_tema"></a><br>
    <br>
    Pentru a sterge o tema din cadrul site-ului, trebuie sa va asigurati intai ca nu este tema principala a site-ului. (Verificati linia 
    $pagina_finala = &quot;red.php&quot;;
    din fisierul config.php) Daca nu este tema principala, puteti sterge fara griji fisierul cu numele temei existent in radacina serverului, precum si directorul cu acelasi nume din themes. Dupa stergere, tema respectiva nu va mai apare in lista de teme iar orice user care a avut salvata acea tema va revenii la tema principala a paginii.<br>
    <br>
    <em><strong>III.c. Cum creez o tema pentru site. </strong></em><a name="creare_tema_site"></a><br>
    <br>
    Pentru a creea o tema pentru site si a fi compatibila cu proiectul trebuie sa respectati specificatiile existente la punctul IV. Puteti crea un template si sa il trimiteti pe adresa proiectului si o sa incercam noi sa il adaptam pentru proiect. Toate temele trimise vor fi distribuite gratuit in urmatoarele versiuni ale proiectului. Drepturile de copyright asupra temei vor apartine persoanei care a trimis template-ul si vor fi mentionate in pagina de download. <span class="adaugari_manual">Incepand de la versiunea 1.0 a fost simplificata procedura de creare template, astfel codul care trebuie introdus in template putand fi copiat de la alta tema deja existenta. In principiu, creati o tema noua, iar acolo unde doriti sa apara meniurile si continutul doar introduceti codul php necesar. Nu uitati sa introduceti si codul pentru afisarea copyright-ului. </span><br>
    <br>
    <em class="adaugari_manual"><strong>IV. Ce trebuie modificat la un template pentru a fi compatibil cu codul FAR-PHP</strong></em><a name="specificatii_teme"></a><br>
    <br>
    Daca doriti sa va creati propria tema pentru site, puteti crea orice tema doriti cu conditia ca acel template sa respecte cateva reguli generale, si anume:<br>
    a) numele temei trebuie sa fie acelasi cu numele directorului<br>
    b) pozele si imaginile folosite in template trebuie sa se afle in subdirectorul &quot;themes/nume_tema/images/&quot;<br>
    c) daca folositi butoane tip imagine trebuiesc salvate in subdirectorul &quot;themes/nume_tema/butons/&quot;<br>
    d) codul css pentru tema creata trebuie salvat in fisierul cu numele css.php in subdirectorul &quot;themes/nume_tema/css/&quot;<br>
    In mod normal la un template clasic, pagina principala se numeste index.php si in ea se integreaza toate imaginile, pozele si butoanele pentru tema respectiva. Deoarece proiectul FAR-PHP are posibilitatea de a schimba tema in functie de preferintele vizitatorului, dupa creerea template-ului, fisierul index.php se va redenumi cu numele_temei pentru a putea fi integrat in proiect.<br>
    Mai jos va este aratata o schema de comparatie intre un template clasic si un template compatibil FAR-PHP:</p>
      <table width="100%"  border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><div align="center">Template clasic </div></td>
          <td><div align="center">Template FAR-PHP </div></td>
          </tr>
        <tr>
          <td valign="top">index.php<br>
            &nbsp;&nbsp;+ BUTONS<br>              - css.php<br>
            &nbsp;&nbsp;+ IMAGES<br></td>
          <td valign="top">nume_tema.php<br>
            + THEMES<br>
            &nbsp;&nbsp;+ NUME_TEMA            <br>
            &nbsp;&nbsp;&nbsp;&nbsp;- top.php<br>
            &nbsp;&nbsp;&nbsp;&nbsp;+ BUTONS<br>
            &nbsp;&nbsp;&nbsp;&nbsp;+ CSS<br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- css.php<br>
            &nbsp;&nbsp;&nbsp;&nbsp;+ IMAGES </td>
          </tr>
      </table>
      <p align="justify"> Pentru alte detalii studiati una din temele distribuite impreuna cu proiectul, sau una din temele distribuite ca add-on. In cazul in care doriti sa colaborati la proiect, puteti trimite template-ul vostru si noi o sa il adaptam pentru proiect. <br>
          <br>
          <em class="adaugari_manual"><strong>V. Variabile de sesiune si cookies folosite</strong></em> <a name="variabile_ses_cook"></a><br>
          <br>
          In versiunea 1.0 exista doar un singur cookies generat care contine informatiile despre limbajul setat, tema selectata, numele userului, adresa de e-mail a userului, suma MD5 a parolei, starea userului si tipul de logare efectual de user. Numele cookies-ului este salvat cu prefix si anume:<br>
          <em>prefix_far</em><br>
          Valorile din cookies sunt salvate in forma serializata (pentru a se evita crearea mai multor cookies cu o singura valoare) <br>
          In continuare este detaliat mai pe larg continutul cookies-ului:<br>
          Cookies-ul este generat doar daca user-ul executa una din urmatoarele comenzi:<br>
          - se logheaza<br>
          - schimba tema site-ului<br>
          - schimba limbajul<br>
          In functie de actiunea executata, in cookies sunt salvate doar informatiile specifice actiunii respective astfe:<br>
          in cazul logarii se genereaza urmatoarele valori in cookies:<br>
          - user_far          = contine numele userului<br>
          - email_far = contine adresa de e-mail a userului<br>
          - password_far = contine suma MD5 a parolei<br>
          - hidden_far = contine starea userului - 0 daca este logat normal si 1 daca este user ascuns<br>
          - permanently_far = 
          contine tipul de logare - 0 daca este logare normala (1 ora) si 1 daca este logare permanenta (100 zile)<br>
          in cazul schimbarii limbajului se genereaza urmatoarea valoare:<br>
          - language_far = contine limbajul setat - &quot;ro&quot;
          pentru romana si &quot;en&quot; pentru engleza<br>
          in cazul schimbarii temei se genereaza urmatoarea valoare:<br>
          - themes_far = contine numele temei selectate (exemplu &quot;corp.php&quot;
          sau &quot;red.php&quot;)<br> 
          <br>
          In cazul in care exista cookies creat, variabilele de sesiune vor contine valorile din cookies, altfel vor contine valorile din fisierul config.php. Variabilele de sesiune care se creaza sunt create cu prefix (la fel si numele cookies-ului):<br>
          prefix_language_far = contine limbajul setat<br>
          prefix_themes_far =           contine tema selectata<br>
          prefix_rights_far =           contine drepturile userului (care sunt specificate in baza de date)<br>
          prefix_cheia_far          = contine cheia unica de sesiune<br>
          prefix_user_far          = contine numele userului<br>
          prefix_email_far =           contine adresa de email a userului<br>
          <br>
          In cazul in care este salvat cookies si contine datele de logare, ele sunt comparate cu informatiile existente in baza de date si daca corespund atunci se creaza cheia de sesiune si se salveaza valorile in variabilele de sesiune, iar in caz contrar se incearca distrugerea cookies-ului si se folosesc valorile din fisierul config.php.<br>
          Unele module aditionale pot crea alte cookies si variabile de sesiune, care sunt specificate pentru fiecare modul in parte. <br>
          <br>
          <em class="adaugari_manual"><strong>VI. Modificari de versiune </strong></em><a name="log_ver"></a><br>
          <br>
          Proiectul FAR-PHP a fost initiat in anul 2004 si a fost conceput ca o alternativa la managementul unui site. Pentru a vedea toate modificarile de versiune existente, puteti apela fisierul de log folosind comanda:<br>
          <a href="index.php?c=ver">index.php?c=ver</a>          <br>
          In cazul in care doriti sa vedeti modificarile de versiune actualizate zilnic (doar in limba romana in acest moment) puteti sa urmati link-ul de mai jos: <br>
          <a href="http://www.far-php.ro/index.php?c=ver%20" target="_blank">http://www.far-php.ro/index.php?c=ver
          </a><br>
          <br>
          <em class="adaugari_manual"><strong>VII. Intrebari frecvente </strong></em><a name="faq"></a><br>
          <br>
          <strong>Nu pot da disconect</strong><br>
          - Este o problema de la cookies. Inchideti browserul si stergeti cookies-urile. Este posibil ca numele de domeniu sa fie specificat incorect la instalare (exemplu pentru instalare a fost folosita adresa http://localhost/test-far/ in loc sa se specifice adresa corecta si anume http://192.168.xxx.xxx/test-far/ (unde xxx este adresa reala de ip atribuita pentru acel nume de domeniu)<br>
          <br>
          <strong>Cum adaug un articol nou? </strong><br>
          Cititi capitolul
          <a href="#modul_continut">Modulul de continut</a><br>
          <br>
          <strong>Nu se instaleaza sau da erori la instalare.</strong><br>
          Cititi capitolul 
          <a href="#creare_config_manual">Crearea manuala a fisierului de configurare config.php</a><br>
          <br>
          <em><strong>VIII. Crearea manuala a fisierului de configurare config.php</strong></em><a name="creare_config_manual"></a><br>
          <br>
        Pentru a crea manual fisierul de configurare trebuie sa folositi modulul install.php deoarece doar el genereaza tabelele in baza de date. In cazul in care doriti sa modificati fisierul de configurare aveti mai jos variabilele existente in acest fisier:</p>
      <p align="left">// Setari pentru Baza de date MySQL<br>
        $server_bd = &quot;localhost&quot;; // numele serverului sql<br>
        $user_bd = &quot;root&quot;; // numele de conectare la bd sql<br>
        $parola_bd = &quot;parola&quot;; // parola pentru conectarea la sql<br>
        $nume_bd = &quot;far_sql&quot;; // numele bazei de date sql<br>
        $prefix_tabel_bd = &quot;prefix_&quot;; // prefixul la numele tabelelor din bd</p>
      <p align="left">// Setari pentru server<br>
        $prefix_sesiuni = &quot;prefix&quot;; // prefixul pentru numele sesiunilor si cookies<br>
        $diferenta_de_ora = &quot;0&quot;; // diferenta de ora de pe server fata de ora reala<br>
        $diferenta_de_ora_2 = &quot;+&quot;; // diferenta in + sau in -<br>
        $adresa_url = &quot;http://www.adresata.com/&quot;; // adresa unde se gaseste pagina web (nu uitati sa puneti / la sfarsit)<br>
        $pagina_finala = &quot;blue.php&quot;; // tema default pentru site<br>
        $pagina_deconectare = &quot;index.php&quot;; // pagina la care se va face redirectarea dupa logout (disconect) <br>
        $mesaje = &quot;codes/language_ro.php&quot;; // fisierul de mesaje de eroare</p>
      <p align="left">// Setari de administrare<br>
        $functii = &quot;codes/functions.php&quot;; // adresa unde se afla fisierul cu functii<br>
        $ip_stop = array(&quot;0.0.0.0&quot;, &quot;255.255.255.255&quot;, &quot;0.0.0.1&quot;); // adresele de ip pe care doriti sa le blocati - ip block<br>
        $parola_criptata = &quot;da&quot;; // puneti &quot;da&quot; daca parola este criptata in sql cu md5, sau &quot;nu&quot; daca este salvata ca text<br>
        $nr_incercari = &quot;5&quot;; // nr de incercari in caz de logare nereusita - 0 pentru infinit<br>
        $email_admin = &quot;adresata@domeniu.com&quot;; // adresa de e-mail a administratorului site-ului<br>
        $email_moderator = &quot;&quot;; // adresa pe care se primesc mesajele pentru moderatorul site-ului<br>
        $limbaj_primar = &quot;ro&quot;; // limba default pentru site<br>
        $chestii_copyright = '&lt;br&gt;&lt;strong&gt;Copyright eu&lt;/strong&gt;&lt;br&gt;'; // partea de jos a pagini pentru chestii de copyright<br>
      </p>
      <p align="left">        <br> 
        </p>
    </div></td>
  </tr>
</table>