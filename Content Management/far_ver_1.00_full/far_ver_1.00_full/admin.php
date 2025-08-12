<?php
/* =====================================================================
*	Pagina index.php
*	Creat de Birkoff pentru proiectul FAR-PHP
*	Versiune: 1.00
*	Copyright: (C) 2004 - 2005 The FAR-PHP Group
*	E-mail: contact@far-php.ro
*	Data inceperii paginii: 13-04-2005	
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

session_start();
ob_start();
error_reporting(0);
// funtia pentru depanare script
$mesaje_debug = 0;
function debug_mesaje($nr)
	{
	global $mesaje_debug;
	$mesaje_debug_index = array(
		1 => 'index.php - generarea variabilei cu mesaje',
		2 => 'index.php - incarcarea fisierului de configuare sau inceperea instalarii proiectului',
		3 => 'index.php - incarcarea fisierului cu functii pentru pagina',
		4 => 'index.php - verificare daca exista update',
		5 => 'index.php - se seteaza functia pentru afisarea timpului de generare a paginii',
		6 => 'index.php - se seteaza variabila de sesiune pentru limbaj si se incarca fisierul de mesaje',
		7 => 'index.php - se verifica daca ip-ul vizitatorului este blocat',
		8 => 'index.php - se incarca fisierul pentru continut (body)',
		9 => 'index.php - se seteaza variabila de sesiune pentru template si se incarca tema',
		10 => 'index.php - se incarca fisierul de incheiere (end)',
		11 => 'body.php - returneaza metatagul',
		12 => 'functions.php - setarea functiei de verificare ip', 
		13 => 'body.php - returneaza top.php din themes',
		14 => 'body.php - returneaza banner.php',
		15 => 'body.php - fisierul nu exista - ',
		16 => 'body.php - returneaza css.php',
		17 => 'body.php - returneaza meniu sus 1',
		18 => 'body.php - returneaza meniu mijloc 2',
		19 => 'body.php - returneaza meniu jos 3',
		20 => 'body.php - returneaza meniu dreapta 4',
		21 => 'body.php - returneaza meniu stanga 5',
		22 => 'body.php - includere partea de logare',
		23 => 'body.php - includere partea de limbaj',
		24 => 'body.php - returneaza online.php',
		25 => 'body.php - returneaza bara de stare partea de print si link',
		26 => 'body.php - returneaza pagina ceruta',
		27 => 'body.php - returneaza partea de jos cu logo si vizitatori',
		28 => 'body.php - returneaza partea de jos cu copyrightul',
		29 => 'install.php - prima parte',
		30 => 'install.php - inainte de afisare.',
		31 => 'install.php - inainte de afisare 2',
		32 => 'install.php - dupa setarea limbajului');
	if ($mesaje_debug == 1)
		{
		return "<br>".$mesaje_debug_index[$nr]."<br>";
		}
	}

// generarea variabilei cu mesaje
debug_mesaje(1);
$mesaje_index_ro = array(
	1 => '<br>Error: Wrong install - config.php missing. Reinstall FAR-PHP',
	2 => '<br>Attention! Delete install.php files',
	3 => '<br>Eroare: Nu exista pagina ceruta - ',
	4 => '<br>Atentie! Dupa actualizare stergeti fisierul update.php',
	5 => '<br>Atentie! In acest moment se fac actualizari. Reveniti mai tarziu.');
	
$mesaje_index_en = array(
	1 => '<br>Error: Wrong install - config.php missing. Reinstall FAR-PHP',
	2 => '<br>Attention! Delete install.php files',
	3 => '<br>Error: The requested page does not exists - ',
	4 => '<br>Attention! Delete update.php after update.',
	5 => '<br>Attention! At the moment this site is on update. Come back latter.');

$mesaje_index = $mesaje_index_en;

// incarcarea fisierului de configuare sau inceperea instalarii proiectului
debug_mesaje(2);
if (file_exists("config.php"))
	{
	if (filesize("config.php") != 0)
		{
		if (file_exists("install.php"))
			{
			echo $mesaje_index[2];
			}
		include_once("config.php");
		}
	if (filesize("config.php") == 0)
		{
		if (file_exists("install.php"))
			{
			include_once("install.php");
			exit;
			}
		if (!file_exists("install.php"))
			{
			die($mesaje_index[1]);
			}
		}
	}
if (!file_exists("config.php"))
	{
	if (file_exists("install.php"))
		{
		include_once("install.php");
		exit;
		}
	if (!file_exists("install.php"))
		{
		die($mesaje_index[1]);
		}
	}	
	
// incarcarea fisierului cu functii pentru pagina
debug_mesaje(3);
if (file_exists($functii))
	{
	include_once ($functii);
	}
if (!file_exists($functii))
	{
	die($mesaje_index[3].$functii);
	}

// verificare daca exista update
debug_mesaje(4);
if (file_exists("update.php"))
	{
	if (isset($_SESSION[$prefix_sesiuni.'_rights_far']))
		{
		if ($_SESSION[$prefix_sesiuni.'_rights_far'] == 1)
			{
			include_once("update.php");
			}
		if ($_SESSION[$prefix_sesiuni.'_rights_far'] != 1)
			{
			if ($_SESSION[$prefix_sesiuni.'_rights_far'] == 2)
				{
				include_once("update.php");
				}
			else
				{
				echo $mesaje_index[4];
				}			
			}
		else
			{
			echo $mesaje_index[4];
			}
		}
	else
		{
		echo $mesaje_index[5];
		}
	}

// se seteaza functia pentru afisarea timpului de generare a paginii
debug_mesaje(5);
if (!isset($timp_pornire))
	{
	$timp_pornire = getmicrotime(); 	
	}

// se seteaza variabila de sesiune pentru limbaj si se incarca fisierul de mesaje
debug_mesaje(6);
if (!isset($_SESSION[$prefix_sesiuni.'_language_far']))
	{
	if (isset($_COOKIE[$prefix_sesiuni.'_far']))
		{
		$prelucrare_cooke = stripslashes($_COOKIE[$prefix_sesiuni.'_far']);
		$valoare_cookie = unserialize($prelucrare_cooke);
		if (isset($valoare_cookie['language_far']))
			{
			if (!empty($valoare_cookie['language_far']))
				{
				$_SESSION[$prefix_sesiuni.'_language_far'] = $valoare_cookie['language_far'];
				}
			if (empty($valoare_cookie['language_far']))
				{
				$_SESSION[$prefix_sesiuni.'_language_far'] = $limbaj_primar;
				}
			}
		if (!isset($valoare_cookie['language_far']))
			{
			$_SESSION[$prefix_sesiuni.'_language_far'] = $limbaj_primar;
			}
		}
	if (!isset($_COOKIE[$prefix_sesiuni.'_far']))
		{
		$_SESSION[$prefix_sesiuni.'_language_far'] = $limbaj_primar;
		}
	}
if (isset($_SESSION[$prefix_sesiuni.'_language_far']))
	{
	$fisier_de_limbaj = "codes/language_".$_SESSION[$prefix_sesiuni.'_language_far'].".php";
	if (file_exists($fisier_de_limbaj))
		{
		include_once($fisier_de_limbaj);
		}
	if (!file_exists($fisier_de_limbaj))
		{
		include_once($mesaje);
		}
	}

// se verifica daca ip-ul vizitatorului este blocat
debug_mesaje(7);
ip_block_far();

// se incarca fisierul pentru continut (body)
debug_mesaje(8);
if (file_exists("codes/body_admin.php"))
	{
	include_once("codes/body_admin.php");
	}
if (!file_exists("codes/body_admin.php"))
	{
	die($mesaje_index[3]."codes/body_admin.php");
	}
	
// se seteaza variabila de sesiune pentru template si se incarca tema
debug_mesaje(9);
if (!isset($_SESSION[$prefix_sesiuni.'_themes_far']))
	{
	if (isset($_COOKIE[$prefix_sesiuni.'_far']))
		{
		$prelucrare_cooke = stripslashes($_COOKIE[$prefix_sesiuni.'_far']);
		$valoare_cookie = unserialize($prelucrare_cooke);
		if (isset($valoare_cookie['themes_far']))
			{
			if (!empty($valoare_cookie['themes_far']))
				{
				$_SESSION[$prefix_sesiuni.'_themes_far'] = $valoare_cookie['themes_far'];
				}
			if (empty($valoare_cookie['themes_far']))
				{
				$_SESSION[$prefix_sesiuni.'_themes_far'] = $pagina_finala;
				}
			}
		if (!isset($valoare_cookie['themes_far']))
			{
			$_SESSION[$prefix_sesiuni.'_themes_far'] = $pagina_finala;
			}
		}
	if (!isset($_COOKIE[$prefix_sesiuni.'_far']))
		{
		$_SESSION[$prefix_sesiuni.'_themes_far'] = $pagina_finala;
		}
	}
if (isset($_SESSION[$prefix_sesiuni.'_themes_far']))
	{
	$fisier_template = $_SESSION[$prefix_sesiuni.'_themes_far'];
	if (file_exists($fisier_template))
		{
		include_once($fisier_template);
		}
	if (!file_exists($fisier_template))
		{
		include_once($pagina_finala);
		}
	}
/*
// teste
// afisare valori sesiuni 
echo "<br>Versiune instalata: ".far_ver();
echo "<br>Afisare valori sesiune (pentru debug)";
foreach ($_SESSION as $nume=>$valoare)
	{
	echo "<br>".$nume." - ".$valoare;
	}

// afisare valori cooke - varianta cooke
if (isset($_COOKIE[$prefix_sesiuni.'_far']))
	{
	echo "<br>Afisare valori cooke (pentru debug)";
	echo "<br>inainte de prelucrare - ".$_COOKIE[$prefix_sesiuni.'_far'];	
	$prelucrare_cooke = stripslashes($_COOKIE[$prefix_sesiuni.'_far']);
	echo "<br>dupa prelucrare - ".$prelucrare_cooke;
	$valoare_cookie = unserialize($prelucrare_cooke);
	foreach ($valoare_cookie as $nume=>$valoare)
		{
		echo "<br>".$nume." - ".$valoare;
		}
	}
*/
ob_end_flush();
?>