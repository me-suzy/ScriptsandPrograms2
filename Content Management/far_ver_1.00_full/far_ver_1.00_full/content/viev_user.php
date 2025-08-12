<?php
/* *********************************
*	Pagina demo_page.php - pentru demonstrarea accesului la o pagina
*	Creat de Birkoff pentru proiectul FAR-PHP
*	Versiune: 1.0
*	Data inceperii paginii: 23-03-2005
*	Ultima modificare: 30-05-2005
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

// se verifica daca userul are drepturi de acces aici
$nivel_acces = drepturi_far(); // functia care returneaza dreptul de acces al userului
if ($nivel_acces <= 4)
	{
	$verificare = 0;	
	}
else
	{
	$verificare = 1;
	}

// se creaza variabila cu mesajele care pot aparea in aceasta pagina in functie de limbajul ales =================================
function mesaje_viev_user($nr)
	{
	global $prefix_sesiuni;
	// se seteaza variabila pentru limbaj ============================================================================================
	// se preia limbajul pentru afisarea continutului specific limbajului
	$limbaj_prelucrat = $_SESSION[$prefix_sesiuni.'_language_far'];
	// crearea mesajelor pentru romana
	$mesaje_fisier_ro = array(
		1 => '<br>Nivelul dvs. de acces este prea mic pentru aceste informatii.',
		2 => 'Id',
		3 => 'User',
		4 => 'Stare',
		5 => 'Email',
		6 => 'Drepturi',
		7 => 'Admin',
		8 => 'Sub-admin',
		9 => 'Moderator',
		10 => 'Editor',
		11 => 'User',
		12 => 'Guest',
		13 => 'Afisare doar userii cu drepturi de:',
		14 => 'Toti userii',
		15 => '');
	// crearea mesajelor pentru engleza
	$mesaje_fisier_en = array(
		1 => '<br>Your access level is to small',
		2 => 'Id',
		3 => 'User',
		4 => 'Status',
		5 => 'Email',
		6 => 'Rights',
		7 => 'Admin',
		8 => 'Under-admin',
		9 => 'Moderator',
		10 => 'Publisher',
		11 => 'User',
		12 => 'Guest',
		13 => 'Show only users with right of:',
		14 => 'All users',
		15 => '');
		
	if ($limbaj_prelucrat == "ro")
		{
		return $mesaje_fisier_ro[$nr];
		}
	if ($limbaj_prelucrat == "en")
		{
		return $mesaje_fisier_en[$nr];
		}
	if ($limbaj_prelucrat != "en")
		{
		if ($limbaj_prelucrat != "ro")
			{
			return $mesaje_fisier_en[$nr];
			}
		}
	}
	
// daca vizitatorul nu are acces in aceasta pagina se ruleaza instructiunile corespunzatoare =======================================
// daca userul nu are acces aici
if ($verificare == 1)
	{
	// aici puneti codul vostru care va rula in cazul in care vizitatorul nu are acces aici
	// atentie la mesajele afisate, trebuie sa le scrieti intai separat pentru fiecare limbaj in parte
	echo mesaje_viev_user(1);
	}
	
// daca vizitatorul are drept de acces la aceasta pagina se ruleaza instructiunile corespunzatoare ==================================
// daca userul are acces aici ...
if ($verificare == 0)
	{
	// aici puneti codul vostru care va rula in cazul in care vizitatorul are acces in aceasta pagina
	// atentie la mesajele afisate, trebuie sa le scrieti intai separat pentru fiecare limbaj in parte	
	if (isset($_GET['stare']))
		{		
		if (!empty($_GET['stare']))
			{
			if (is_numeric($_GET['stare']))
				{
				if (strlen($_GET['stare']) == 1)
					{
					if ($_GET['stare'] <= 6)
						{
						$stare = $_GET['stare'];
						}
					}
				}
			}
		}
	else
		{
		$stare = "";
		}
	$rezultate_afisare_pagina = 10; // specificati nr de rezultate care vor fi afisate pe pagina
	$numere_rand = 15; // specificati dupa cate numere se va trece la rand nou
	$numele_paginii = "index.php?c=viev_user"; // numele acestei pagini in care aveti scriptul
	
	$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
	mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
	if (!empty($stare))
		{
		$interogare = "SELECT * FROM ".$prefix_tabel_bd."user WHERE stare = '".$stare."'";
		}
	else
		{
		$interogare = "SELECT * FROM ".$prefix_tabel_bd."user";
		}	
	$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
	$nr_total_pagini = (integer)(mysql_num_rows($rezultat)/$rezultate_afisare_pagina);
	if (isset($_GET['id']))
		{
		if (is_numeric($_GET['id']))
			{
			// daca se cere o anumita pagina 
			$id = $_GET['id'];		
			}
		else
			{
			$id = 0;
			}
		}
	if (!isset($_GET['id']))
		{
		$id = 0;
		}
	if (!empty($stare))
		{
		$interogare = "SELECT * FROM ".$prefix_tabel_bd."user WHERE stare = '".$stare."' LIMIT ".$id.",".$rezultate_afisare_pagina;
		}
	else
		{
		$interogare = "SELECT * FROM ".$prefix_tabel_bd."user LIMIT ".$id.",".$rezultate_afisare_pagina;
		}
	$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
	$total = mysql_num_rows($rezultat);			
	if ($total != 0)
		{
		echo '<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  				<tr>
    			<td width="5%"><div align="left"><strong>'.mesaje_viev_user(2).'</strong></div></td>
    			<td width="20%"><div align="left"><strong>'.mesaje_viev_user(3).'</strong></div></td>
    			<td width="10%"><div align="left"><strong>'.mesaje_viev_user(4).'</strong></div></td>
    			<td width="35%"><div align="left"><strong>'.mesaje_viev_user(5).'</strong></div></td>
    			<td width="30%"><div align="left"><strong>'.mesaje_viev_user(6).'</strong></div></td>
  				</tr>';
		while ($rand = mysql_fetch_array($rezultat))
			{
			$drepturi = $rand['stare'];
			if ($drepturi == 1)
				{
				$user_stare = mesaje_viev_user(7);
				}
			if ($drepturi == 2)
				{
				$user_stare = mesaje_viev_user(8);
				}
			if ($drepturi == 3)
				{
				$user_stare = mesaje_viev_user(9);
				}
			if ($drepturi == 4)
				{
				$user_stare = mesaje_viev_user(10);
				}
			if ($drepturi == 5)
				{
				$user_stare = mesaje_viev_user(11);
				}
			if ($drepturi == 6)
				{
				$user_stare = mesaje_viev_user(12);
				}
			echo '<tr bgcolor="#000099">
    			<td  height="1" bgcolor="#000099"></td>
    			<td  height="1" bgcolor="#000099"></td>
    			<td  height="1" bgcolor="#000099"></td>
    			<td  height="1" bgcolor="#000099"></td>
    			<td  height="1" bgcolor="#000099"></td>
  				</tr>
  				<tr bgcolor="#CCCCCC">
    			<td  height="1" bgcolor="#CCCCCC"></td>
    			<td  height="1" bgcolor="#CCCCCC"></td>
    			<td  height="1" bgcolor="#CCCCCC"></td>
    			<td  height="1" bgcolor="#CCCCCC"></td>
    			<td  height="1" bgcolor="#CCCCCC"></td>
  				</tr>
  				<tr bgcolor="#000099">
    			<td  height="1" bgcolor="#000099"></td>
    			<td  height="1" bgcolor="#000099"></td>
    			<td  height="1" bgcolor="#000099"></td>
    			<td  height="1" bgcolor="#000099"></td>
    			<td  height="1" bgcolor="#000099"></td>
  				</tr>';
	  		echo '<tr>
    			<td>'.$rand['nr'].'</td>
    			<td>'.$rand['user'].'</td>
    			<td>'.$rand['stare'].'</td>
    			<td>'.$rand['email'].'</td>
    			<td>'.$user_stare.'</td>
  				</tr>';				
			}
		echo '</table>';		
		
		$randuri = (integer)($nr_total_pagini / $numere_rand);
		$nrr = 0;
		if ($randuri >= $numere_rand)
			{
			for ($ee=1;$ee<=$randuri;$ee++)
				{
				$nrr = $nrr+$numere_rand;
				$rnd[$ee] = $nrr; 
				}
			}
		else
			{
			$rnd[1] = $numere_rand;
			}
		$id_m = 0;
		echo '<br><div align="center">';
		for ($s=1;$s<=$nr_total_pagini+1;$s++)
			{
			if (empty($stare))
				{ 
				echo ' - <a href="'.$numele_paginii.'&id='.$id_m.'">'.$s.'</a>';
				}
			else
				{
				echo ' - <a href="'.$numele_paginii.'&stare='.$stare.'&id='.$id_m.'">'.$s.'</a>';
				}
			foreach($rnd as $val)
				{
				if ($s == $val)
					{
					echo "<br>";
					}
				}
			$id_m = $id_m + $rezultate_afisare_pagina;
			}
		echo ' - <br><br></div>';

		}
	else
		{
		echo $mesaj[119];
		}	
	@mysql_close($conectare);
	echo '<br><br><br>'.mesaje_viev_user(13).'<br>
 		<a href="'.$_SERVER['PHP_SELF'].'?c=viev_user&stare=1">'.mesaje_viev_user(7).'</a><br>
 		<a href="'.$_SERVER['PHP_SELF'].'?c=viev_user&stare=2">'.mesaje_viev_user(8).'</a><br>
 		<a href="'.$_SERVER['PHP_SELF'].'?c=viev_user&stare=3">'.mesaje_viev_user(9).'</a><br>
 		<a href="'.$_SERVER['PHP_SELF'].'?c=viev_user&stare=4">'.mesaje_viev_user(10).'</a><br>
		<a href="'.$_SERVER['PHP_SELF'].'?c=viev_user&stare=5">'.mesaje_viev_user(11).'</a><br>
 		<a href="'.$_SERVER['PHP_SELF'].'?c=viev_user&stare=6">'.mesaje_viev_user(12).'</a><br>
		<a href="'.$_SERVER['PHP_SELF'].'?c=viev_user">'.mesaje_viev_user(14).'</a><br>';
	}
?>