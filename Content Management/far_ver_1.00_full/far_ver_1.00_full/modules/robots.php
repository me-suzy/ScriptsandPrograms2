<?php
/* *********************************
*	Pagina robots.php - 
*	Creat de Birkoff pentru proiectul FAR-PHP
*	Versiune: 1.0
*	Data inceperii paginii: 12-05-2005
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
	
// crearea mesajelor pentru modul
function mesaje_modul_robots($nr)
	{
	global $prefix_sesiuni;
	// se preia limbajul pentru afisarea continutului specific limbajului
	$limbaj_prelucrat = $_SESSION[$prefix_sesiuni.'_language_far'];
	
	$mesaje_fisier_ro = array(
			1 => '<br>Nivelul dvs. de acces este prea mic pentru aceste informatii.',
			2 => '<br>Nu exista in acest moment nimic salvat in baza de date.',
			3 => 'Id',
			4 => 'Ip',
			5 => 'Host',
			6 => 'Browser',
			7 => 'Referer',
			8 => 'Timp',
			9 => 'Adresa');
			
	$mesaje_fisier_en = array(
			1 => '<br>Your access level is to small',
			2 => '<br>The database is empty.',
			3 => 'Id',
			4 => 'Ip',
			5 => 'Host',
			6 => 'Browser',
			7 => 'Referer',
			8 => 'Time',
			9 => 'Address');
			
	if ($limbaj_prelucrat == "ro")
		{
		return $mesaje_fisier_ro[$nr];
		}
	if ($limbaj_prelucrat == "en")
		{
		return $mesaje_fisier_en[$nr];
		}
	if (($limbaj_prelucrat != "en") OR ($limbaj_prelucrat != "ro"))
		{
		return $mesaje_fisier_en[$nr];
		}
	}

if ($verificare == 0)
	{
	$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
	mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
	$interogare = "SELECT * FROM ".$prefix_tabel_bd."robots";
	$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
	$total = mysql_num_rows($rezultat);
	if ($total == 0)
		{
		echo mesaje_modul_robots(2);
		}
	else
		{
		echo '<table width="100%"  border="1" cellspacing="0" cellpadding="0">
  			<tr>
	    	<td><div align="center">'.mesaje_modul_robots(3).'</div></td>
    		<td><div align="center">'.mesaje_modul_robots(4).'</div></td>
    		<td><div align="center">'.mesaje_modul_robots(5).'</div></td>
    		<td><div align="center">'.mesaje_modul_robots(6).'</div></td>
    		<td><div align="center">'.mesaje_modul_robots(7).'</div></td>
    		<td><div align="center">'.mesaje_modul_robots(8).'</div></td>
    		<td><div align="center">'.mesaje_modul_robots(9).'</div></td>
  			</tr>';
		while ($rand = mysql_fetch_array($rezultat))
			{
			$host = gethostbyaddr($rand['ip']);
			$timp = date("d-m-Y H:i",$rand['timp']);
			if (empty($rand['referer']))
				{
				$rand['referer'] = '&nbsp;';
				}
			echo '<tr>
    			<td>'.$rand['id'].'</td>
			    <td>'.$rand['ip'].'</td>
			    <td>'.$host.'</td>
			    <td>'.$rand['browser'].'</td>
			    <td>'.$rand['referer'].'</td>
			    <td>'.$timp.'</td>
			    <td>'.$rand['adresa'].'</td>
			  	</tr>';
			}
		echo '</table>';
		}
	}
else
	{
	echo mesaje_modul_robots(1);
	}
?>