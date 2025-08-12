<?php
/* *********************************
*	Pagina demo_page.php - pentru demonstrarea accesului la o pagina
*	Creat de Birkoff pentru proiectul FAR-PHP
*	Versiune: 1.0
*	Data inceperii paginii: 23-03-2005
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

/* Ce face aceasta pagina?
1. se verifica ce drepturi are userul si se seteaza variabila pentru drepturi
	- in acest caz accesul la aceasta pagina este permin doar pentru nivelele 1-4
2. se seteaza variabila pentru limbaj
3. se creaza variabila cu mesajele care pot aparea in aceasta pagina in functie de limbajul ales
4. daca vizitatorul nu are acces in aceasta pagina se ruleaza instructiunile corespunzatoare
5. daca vizitatorul are drept de acces la aceasta pagina se ruleaza instructiunile corespunzatoare
Puteti folosi aceste variabile din aceasta pagina pentru a va crea propriile pagini sau module.
paginile trebuiesc salvate in directorul "content/" si vor fi accesate folosind un link asemanator 
cu acesta "index.php?c=nume_pagina" iar modulele trebuiesc salvate in directorul "modules/"
si vor fi accesate folosind un link asemanator ca acesta "index.php?m=nume_modul?action=actiuni_specifice"
sau "admin.php?m=nume_modul?action=actiuni_specifice"

In continuare fiecare sectiune este explicata mai jos:
*/

// 1. se verifica ce drepturi are userul si se seteaza variabila pentru drepturi
$nivel_acces = drepturi_far(); // functia care returneaza dreptul de acces al userului
if ($nivel_acces <= 4) // daca nivelul de acces este intre 1-4 atunci e ok
	{
	$verificare = 0;
	}
if ($nivel_acces >= 5) // daca nivelul de acces este intre 5-6 atunci nu se afiseaza continutul pagini
	{
	$verificare = 1;
	}	

// 2. se seteaza variabila pentru limbaj ============================================================================================
function mesaje_demo_page($nr) // puteti seta numele functiei cu numele paginii pentru a nu se incura mesajele cu alte pagini
	{
	global $prefix_sesiuni;
	// se preia limbajul pentru afisarea continutului specific limbajului
	$limbaj_prelucrat = $_SESSION[$prefix_sesiuni.'_language_far'];

	// 3. se creaza variabila cu mesajele care pot aparea in aceasta pagina in functie de limbajul ales =================================
	$mesaje_fisier_ro = array(  // crearea mesajelor pentru romana
			1 => '<br>Nivelul dvs. de acces este prea mic pentru aceste informatii.',
			2 => '<br>Limbajul ales este ',
			3 => '<br>Nivelul de acces actual este ',
			4 => '<br>Aici puteti pune mesajele si codul dvs.');
			
	$mesaje_fisier_en = array(  // crearea mesajelor pentru engleza
			1 => '<br>Your access level is to small',
			2 => '<br>Your languages is ',
			3 => '<br>Your access level is ',
			4 => '<br>Here put your codes and mesages');
			
	if ($limbaj_prelucrat == "ro")
		{
		return $mesaje_fisier_ro[$nr]; // se afiseaza mesajele in romana
		}			
	if ($limbaj_prelucrat == "en")
		{
		return $mesaje_fisier_en[$nr]; // se afiseaza mesajele in engleza
		}	
	if ($limbaj_prelucrat != "en")
		{
		if ($limbaj_prelucrat != "ro")
			{
			return $mesaje_fisier_en[$nr]; // se afiseaza mesajele in engleza (in cazul in care nu e tradus)
			}
		}
	}
// 4. daca vizitatorul nu are acces in aceasta pagina se ruleaza instructiunile corespunzatoare =======================================
// daca userul nu are acces aici
if ($verificare == 1)
	{
	// aici puneti codul vostru care va rula in cazul in care vizitatorul nu are acces aici
	// atentie la mesajele afisate, trebuie sa le scrieti intai separat pentru fiecare limbaj in parte
	echo mesaje_demo_page(1);
	}
	
// 5. daca vizitatorul are drept de acces la aceasta pagina se ruleaza instructiunile corespunzatoare ==================================
// daca userul are acces aici ...
if ($verificare == 0)
	{
	// aici puneti codul vostru care va rula in cazul in care vizitatorul are acces in aceasta pagina
	// atentie la mesajele afisate, trebuie sa le scrieti intai separat pentru fiecare limbaj in parte
	echo mesaje_demo_page(2).$limbaj_prelucrat;
	echo mesaje_demo_page(3).$nivel_acces;
	echo mesaje_demo_page(4);
	}
?>