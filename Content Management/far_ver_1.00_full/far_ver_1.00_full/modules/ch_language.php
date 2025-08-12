<?php
/* =====================================================================
*	Modulul de schimbat limbajul - ch_language.php
*	Creat de Birkoff pentru proiectul FAR-PHP
*	Versiune: 1.0
*	Copyright: (C) 2004 - 2005 The FAR-PHP Group
*	E-mail: contact@far-php.ro
*	Inceput la: 20-02-2005	
*	Ultima modificare: 04-05-2005
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

// se preia limbajul trimis
if (isset($_GET['language']))
	{
	$limbaj = $_GET['language'];	
	// se verifica daca exista fisierul pentru limbajul respectiv
	// se citeste structura de directoare din themes
	$director = opendir("codes");
	$verificare = 0;
	if (!$director) // daca nu se poate deschide
		{
		$verificare = 1;
		echo $mesaj[222];
		}
	$citire = readdir($director); // se citeste continutul directorului
	if (!$citire)
		{
		$verificare = 1;
		echo $mesaj[223];		
		}	
	$fisier_limbaj = "language_".$limbaj.".php";	
	$exista_limbaj = $_SESSION[$prefix_sesiuni.'_language_far'];
	// echo "<br>Limbaj existent ".$limbaj_existent[1];	
	if ($verificare == 0)
		{
		$verificare = 1;
		while ($citire)
			{
			if ($citire == $fisier_limbaj)
				{
				$verificare = 0;
				$exista_limbaj = $citire; // se memoreaza numele fisierului de limbaj
				}
			$citire = readdir($director);
			}
		}
	closedir($director);
	// se creaza variabilele de sesiune si cookie pentru limbaj
	if ($verificare == 0)
		{
		// se seteaza timpul de expirare al cookeului
		$expira = 1*60*60*24*100; // in acest caz expira dupa 100 de zile
		$timp_expirare = time()+$expira;
		if (isset($_COOKIE[$prefix_sesiuni.'_far']))
			{
			$prelucrare_cooke = stripslashes($_COOKIE[$prefix_sesiuni.'_far']);
			$valoare_cookie = unserialize($prelucrare_cooke);
			$valoare_cookie['language_far'] = $limbaj;
			$val_coke = serialize($valoare_cookie);	
			setcookie($prefix_sesiuni."_far", $val_coke, $timp_expirare, "/");
			$_SESSION[$prefix_sesiuni.'_language_far'] = $limbaj;
			}
		if (!isset($_COOKIE[$prefix_sesiuni.'_far']))
			{
			$valoare_cookie = array(
				'language_far' => $limbaj,
				'themes_far' => $_SESSION[$prefix_sesiuni.'_themes_far'],
				'user_far' => '',
				'email_far' => '',
				'password_far' => '',
				'hidden_far' => '0',
				'permanently_far' => '0');
			$val_coke = serialize($valoare_cookie);			
			setcookie($prefix_sesiuni."_far", $val_coke, $timp_expirare, "/");
			$_SESSION[$prefix_sesiuni.'_language_far'] = $limbaj;
			}
		echo '<META HTTP-EQUIV = "Refresh" Content = "0; URL ='.$adresa_url.'">'.$mesaj[224];		
		}
	}

?>