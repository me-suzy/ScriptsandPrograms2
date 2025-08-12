<?php
/* =====================================================================
*	Pagina end.php
*	Creat de Birkoff pentru proiectul FAR-PHP
*	Versiune: 0.01
*	Copyright: (C) 2004 - 2005 The FAR-PHP Group
*	E-mail: contact@far-php.ro
*	Data inceperii paginii: 28-12-2004	
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

$verificare = 0;
$data_copy = date("Y",time());
$link_far_ro = "http://www.far-php.ro/index.php";
$link_far_en = "http://www.far-php.ro/index.php";
$link_gpl_ro = "http://www.far-php.ro/index.php?p=gnu_gpl";
$link_gpl_en = "http://www.far-php.ro/index.php?p=gnu_gpl";
$link_autori_ro = "http://www.far-php.ro/index.php?p=credit";
$link_autori_en = "http://www.far-php.ro/index.php?p=credit";
$link_drepturi_ro = "http://www.far-php.ro/index.php?p=donation";
$link_drepturi_en = "http://www.far-php.ro/index.php?p=donation";
$timp_terminare = getmicrotime(); 
$timp_total = $timp_terminare-$timp_pornire;
// se preia limbajul pentru afisarea continutului specific limbajului
$limbaj_prelucrat = $_SESSION[$prefix_sesiuni.'_language_far'];
// echo $limbaj_prelucrat;
@$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
@mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
$interogare = "SELECT * FROM ".$prefix_tabel_bd."ver";
@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;
@$total = mysql_num_rows($rezultat);
if ($total != 0)
	{
	while ($rand = mysql_fetch_array($rezultat))
		{		
		$subiect_email = explode("_._", $rand['mesages']);
		$stare_instalare = $rand['status'];
		}	
	}
else
	{
	$verificare = 2;
	}
mysql_close($conectare);

$chestii_en = 'The logos and trademarks used on this site are the property of their respective owners<br>
        We are not responsible for comments posted by our users, as they are the property of the poster<br>
		Web site engine\'s code is copyright &copy; 2004 - '.$data_copy.' by <a href="'.$link_far_en.'" target="_blank">FAR-PHP</a><br>
		Released under the <a href="'.$link_gpl_en.'">GNU GPL License</a> - <a href="'.$link_autori_en.'">Code Credits</a> - <a href="'.$link_drepturi_en.'">Privacy Policy</a><br>';
$timp_en = 'Page generation in: '.round($timp_total,3).' seconds - 
		<SCRIPT>document.write("Page loaded in: " +loadtime+ " seconds");</SCRIPT>';
$chestii_ro = 'Imaginile si marcile folosite in aceste pagini sunt proprietatea lor, respectiv proprietarilor acelor imagini si marci<br>
		Nu suntem responsabili pentru comentariile puse de catre utilizatori, ele sunt proprietatea acelor utilizatori<br>
		Codul de baza al acestui site este protejat de drepturile de autor &copy; 2004 - '.$data_copy.' si este scris de comunitatea <a href="'.$link_far_ro.'" target="_blank">FAR-PHP</a><br>
		Codul este distribuit conform licentei <a href="'.$link_gpl_ro.'">GNU GPL</a> - <a href="'.$link_autori_ro.'">Autorii codului</a> - <a href="'.$link_drepturi_ro.'">Alte drepturi</a><br>';
$timp_ro = 'Pagina generata in: '.round($timp_total,3).' secunde - 
		<SCRIPT>document.write("Pagina incarcata in: " +loadtime+ " secunde");</SCRIPT>';
$chestii_rele = 'This pages used code from <a href="'.$link_far_en.'" target="_blank">FAR-PHP</a> project, but it\'s not consideration
		of any legals term, because do not respect original copyright.<br>Please send e-mail to 
		<a href="mailto:'.$subiect_email[3].'?Subject='.$subiect_email[4].'&body=Your messages">FAR-PHP</a> team,
		and tell as about this pages.';
$chestii_bune = far_ver("ver");
// $limbaj = guess_lang();
if ($verificare == 0)
	{
	if ($stare_instalare == 0)
		{
		if ($limbaj_prelucrat != "ro")
			{			
			echo $chestii_en;
			$kk = 1;
			}
		else
			{			
			echo $chestii_ro;
			$kk = 1;
			}
		}
	if ($stare_instalare == 1)
		{
		if ($limbaj_prelucrat != "ro")
			{			
			echo $chestii_en;
			$kk = 1;
			}
		else
			{			
			echo $chestii_ro;			
			$kk = 1;
			}
		}
	if ($stare_instalare == 2)
		{
		echo $subiect_email[1];
		$kk = 1;
		}
	if ($stare_instalare == 3)
		{
		echo $chestii_bune;
		$kk = 1;		
		}	
	}
if ($verificare != 0)
	{
	echo $chestii_rele;
	$kk = 1;
	}
// se afiseaza timpul de incarcare a paginii
if ($limbaj_prelucrat != "ro")
	{
	echo $timp_en;	
	}
else
	{
	echo $timp_ro;	
	}
if (!isset($kk))
	{
	echo far_ver("ver");
	}
if (isset($kk))
	{
	if ($kk != 1)
		{
		echo far_ver("ver");
		}
	}
?>