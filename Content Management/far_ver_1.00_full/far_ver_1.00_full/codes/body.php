<?php
/* =====================================================================
*	Pagina body.php
*	Creat de Birkoff pentru proiectul FAR-PHP
*	Versiune: 1.0
*	Copyright: (C) 2004 - 2005 The FAR-PHP Group
*	E-mail: contact@far-php.ro
*	Data inceperii paginii: 28-12-2004
*	Ultima modificare: 15-05-2005
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

if (!function_exists('show_menu')) 
	{
	// se genereaza functia pentru meniuri
	function show_menu($valoare)
		{
		global $server_bd;
		global $user_bd;
		global $parola_bd;
		global $mesaj;
		global $prefix_tabel_bd;
		global $nume_bd;
		global $prefix_sesiuni;
		
		$limbaj_ales = $_SESSION[$prefix_sesiuni.'_language_far'];
		
		// se incarca in array meniurile
		$stare_far = drepturi_far();
		if ($stare_far != 6) 
			{
			$nr = 0;
			for ($st=1;$st<=6;$st++)
				{
				if ($stare_far <= $st)
					{
					//echo "<br>stare = ".$stare_far;
					$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
					mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
					$interogare = "SELECT * FROM ".$prefix_tabel_bd."menu WHERE status = '".$st."' AND language = '".$limbaj_ales."' OR  status = '".$st."' AND language = '0' OR  status = '".$st."' AND language = 'all'";
					$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
					$total = mysql_num_rows($rezultat);
					if ($total != 0)
						{		
						while ($rand = mysql_fetch_array($rezultat))
							{
							$id_meniu[$nr] = $rand['id'];
							$cod_meniu[$nr] = stripslashes($rand['html']);
							$tip_meniu[$nr] = $rand['type'];
							$prioritate_meniu[$nr] = $rand['priority'];
							$locatie_meniu[$nr] = $rand['location'];
							$limba_meniu[$nr] = $rand['language'];
							$nr++;
							}
						}	
					else // daca nu e nici un meniu 
						{
						$nr = 0;
						$id_meniu[$nr] = 0;
						$cod_meniu[$nr] = '<a href="admin.php?m=menu">Error: The is not any menu in data base Clik here for new menu.</a>
							<a href="admin.php?m=menu">Eroare: Nu exista nici un meniu in baza de date. Clik aici pentru a crea un meniu.</a>';
						$tip_meniu[$nr] = 1;
						$prioritate_meniu[$nr] = 1;
						$locatie_meniu[$nr] = 2;
						$limba_meniu[$nr] = "all";
						}
					mysql_close($conectare);
					}
				}
			}
		else // daca userul nu e logat se incarca doar meniurile pentru guest
			{
			$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
			mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
			$interogare = "SELECT * FROM ".$prefix_tabel_bd."menu WHERE status = '6' AND language = '".$limbaj_ales."' OR  status = '6' AND language = '0' OR  status = '6' AND language = 'all'";
			$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
			$total = mysql_num_rows($rezultat);
			if ($total != 0)
				{	
				$nr = 0;
				while ($rand = mysql_fetch_array($rezultat))
					{
					$id_meniu[$nr] = $rand['id'];
					$cod_meniu[$nr] = stripslashes($rand['html']);
					$tip_meniu[$nr] = $rand['type'];
					$prioritate_meniu[$nr] = $rand['priority'];
					$locatie_meniu[$nr] = $rand['location'];
					$limba_meniu[$nr] = $rand['language'];
					// echo "<br>".$nr." - ".$id_meniu[$nr];
					$nr++;			
					}
				}
			else // daca nu e nici un meniu 
				{
				$nr = 0;
				$id_meniu[$nr] = 0;
				$cod_meniu[$nr] = '<a href="admin.php?m=menu">Error: The is not any menu in data base Clik here for new menu.</a>
					<a href="admin.php?m=menu">Eroare: Nu exista nici un meniu in baza de date. Clik aici pentru a crea un meniu.</a>';
				$tip_meniu[$nr] = 1;
				$prioritate_meniu[$nr] = 1;
				$locatie_meniu[$nr] = 2;
				$limba_meniu[$nr] = "all";
				}
			mysql_close($conectare);
			}
		// se afiseaza meniurile cerute
		if ($valoare == 1) // returneaza meniu sus 1
			{
			$stare_far = drepturi_far();
			$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
			mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
			$interogare = "SELECT * FROM ".$prefix_tabel_bd."menu WHERE status >= '".$stare_far."' AND language = '".$limbaj_ales."' AND type = '1' AND location = '1'";
			$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
			$total = mysql_num_rows($rezultat);
			if ($total != 0)
				{		
				$nr_men = 1;
				while ($rand = mysql_fetch_array($rezultat))
					{				
					$cod_men_up[$nr_men] = stripslashes($rand['html']);				
					$prioritate_men_up[$nr_men] = $rand['priority'];				
					$nr_men++;		
					}
				for ($prio=1;$prio<=30;$prio++)
					{
					for ($nr_tot=1;$nr_tot<=$nr_men;$nr_tot++)
						{
						if (isset($prioritate_men_up[$nr_tot]))
							{
							if ($prioritate_men_up[$nr_tot] == $prio)
								{
								echo $cod_men_up[$nr_tot];							
								}
							}
						}
					}
				}	
			}
		if ($valoare == 2) // returneaza meniu mijloc 2
			{
			$stare_far = drepturi_far();
			$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
			mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
			$interogare = "SELECT * FROM ".$prefix_tabel_bd."menu WHERE status >= '".$stare_far."' AND language = '".$limbaj_ales."' AND type = '1' AND location = '2'";
			$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
			$total = mysql_num_rows($rezultat);
			if ($total != 0)
				{		
				$nr_men = 1;
				while ($rand = mysql_fetch_array($rezultat))
					{				
					$cod_men_mj[$nr_men] = stripslashes($rand['html']);				
					$prioritate_men_mj[$nr_men] = $rand['priority'];				
					$nr_men++;		
					}
				for ($prio=1;$prio<=30;$prio++)
					{
					for ($nr_tot=1;$nr_tot<=$nr_men;$nr_tot++)
						{
						if (isset($prioritate_men_mj[$nr_tot]))
							{
							if ($prioritate_men_mj[$nr_tot] == $prio)
								{
								echo $cod_men_mj[$nr_tot];							
								}
							}
						}
					}
				}	
			}
		if ($valoare == 3) // meniu orizontal jos
			{
			$stare_far = drepturi_far();
			$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
			mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
			$interogare = "SELECT * FROM ".$prefix_tabel_bd."menu WHERE status >= '".$stare_far."' AND language = '".$limbaj_ales."' AND type = '1' AND location = '3'";
			$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
			$total = mysql_num_rows($rezultat);
			if ($total != 0)
				{		
				$nr_men = 1;
				while ($rand = mysql_fetch_array($rezultat))
					{				
					$cod_men_js[$nr_men] = stripslashes($rand['html']);				
					$prioritate_men_js[$nr_men] = $rand['priority'];				
					$nr_men++;		
					}
				for ($prio=1;$prio<=30;$prio++)
					{
					for ($nr_tot=1;$nr_tot<=$nr_men;$nr_tot++)
						{
						if (isset($prioritate_men_js[$nr_tot]))
							{
							if ($prioritate_men_js[$nr_tot] == $prio)
								{
								echo $cod_men_js[$nr_tot];							
								}
							}
						}
					}
				}	
			}
		if ($valoare == 4) // meniu vertical dreapta
			{
			$stare_far = drepturi_far();
			$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
			mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
			$interogare = "SELECT * FROM ".$prefix_tabel_bd."menu WHERE status >= '".$stare_far."' AND language = '".$limbaj_ales."' AND type = '2' AND location = '1'";
			$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
			$total = mysql_num_rows($rezultat);
			if ($total != 0)
				{		
				$nr_men = 1;
				while ($rand = mysql_fetch_array($rezultat))
					{				
					$cod_men_dr[$nr_men] = stripslashes($rand['html']);				
					$prioritate_men_dr[$nr_men] = $rand['priority'];				
					$nr_men++;		
					}
				for ($prio=1;$prio<=30;$prio++)
					{
					for ($nr_tot=1;$nr_tot<=$nr_men;$nr_tot++)
						{
						if (isset($prioritate_men_dr[$nr_tot]))
							{
							if ($prioritate_men_dr[$nr_tot] == $prio)
								{
								echo $cod_men_dr[$nr_tot];							
								}
							}
						}
					}
				}		
			}			
		if ($valoare == 5) // meniu vertical stanga
			{	
			$stare_far = drepturi_far();
			$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
			mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
			$interogare = "SELECT * FROM ".$prefix_tabel_bd."menu WHERE status >= '".$stare_far."' AND language = '".$limbaj_ales."' AND type = '2' AND location = '2'";
			$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
			$total = mysql_num_rows($rezultat);
			if ($total != 0)
				{		
				$nr_men = 1;
				while ($rand = mysql_fetch_array($rezultat))
					{				
					$cod_men_st[$nr_men] = stripslashes($rand['html']);				
					$prioritate_men_st[$nr_men] = $rand['priority'];				
					$nr_men++;		
					}
				for ($prio=1;$prio<=30;$prio++)
					{
					for ($nr_tot=1;$nr_tot<=$nr_men;$nr_tot++)
						{
						if (isset($prioritate_men_st[$nr_tot]))
							{
							if ($prioritate_men_st[$nr_tot] == $prio)
								{
								echo $cod_men_st[$nr_tot];							
								}
							}
						}
					}
				}		
			}	
		}
		
	// se genereaza functia body_far
	function body_far($valoare = "0")
		{	
		global $mesaje_index;
		global $server_bd;
		global $user_bd;
		global $parola_bd;
		global $nume_bd;
		global $prefix_tabel_bd;	
		global $prefix_sesiuni;
		global $diferenta_de_ora;
		global $diferenta_de_ora_2;
		global $adresa_url;
		global $pagina_finala;
		global $pagina_deconectare;
		global $mesaje;
		global $functii;
		global $ip_stop;
		global $parola_criptata;
		global $nr_incercari;
		global $email_admin;
		global $email_moderator;
		global $limbaj_primar;
		global $chestii_copyright;
		global $mesaj;
		global $timp_pornire;
		
		if ($valoare == "0") // daca nu se trimite parametru
			{		
			return("<br>Functia body_far este apelata fara parametru");
			}	
	
		// se creaza fisierele cu mesaje pentru fiecare limbaj in parte
		$limbaj_ales = $_SESSION[$prefix_sesiuni.'_language_far'];
		$mesaj_aici_ro = array( // mesajele in romana
			1 => 'HOME',
			2 => 'PAGINA CURENTA',
			3 => 'TIPARIRE',
			4 => 'PAGINA FAVORITA',
			5 => 'scrie aici e-mail-ul',
			6 => 'De la proiectul FAR-PHP',
			7 => 'Salut   Uite ceva interesant aici: ',
			8 => 'E-MAIL',
			9 => 'Adresa ip: ',
			10 => 'Limbaj browser: ',
			11 => 'Data curenta: ',
			12 => 'Vizitatori astazi (real-time): ');
		$mesaj_aici_en = array( // mesajele in engleza
			1 => 'HOME',
			2 => 'CURRENT PAGE',
			3 => 'PRINT',
			4 => 'FAVOURITE PAGES',
			5 => 'your e-mail here',
			6 => 'From FAR-PHP project',
			7 => 'Hy   Check this: ',
			8 => 'E-MAIL',
			9 => 'Your ip is ',
			10 => 'Browser language is: ',
			11 => 'Curent date is ',
			12 => 'Daily visitors (real-time): ');
					
		if ($limbaj_ales == "ro")
			{
			$mesaj_aici = $mesaj_aici_ro;
			}
		if ($limbaj_ales == "en")
			{
			$mesaj_aici = $mesaj_aici_en;
			}
		if ($limbaj_ales != "ro")
			{
			if ($limbaj_ales != "en")
				{
				$mesaj_aici = $mesaj_aici_en; // daca sunt si alte limbaje si nu sunt traduse apare in en
				}
			}
				
		if ($valoare == "meta") // returneaza metatagul
			{		
			debug_mesaje(11);
			$fisier = "codes/meta.php";
			if (file_exists($fisier))
				{
				$returneaza = include_once($fisier);			
				return $returneaza;
				}
			if (!file_exists($fisier))
				{
				die($mesaje_index[3].$fisier);
				}
			}
		
		if ($valoare == "top") // returneaza top.php din themes
			{		
			debug_mesaje(13);		
			$director_template = explode(".",$_SESSION[$prefix_sesiuni.'_themes_far']);
			$fisier = "themes/".$director_template[0]."/top.php";
			if (file_exists($fisier))
				{
				$returneaza = include_once($fisier);			
				return $returneaza;
				}
			if (!file_exists($fisier))
				{
				die($mesaje_index[3].$fisier);
				}
			}
			/*
		if ($valoare == "banner") // returneaza banner.php
			{		
			debug_mesaje(14);				
			$fisier = "modules/banner.php";
			if (file_exists($fisier))
				{
				$returneaza = include_once($fisier);			
				return $returneaza;
				}
			if (!file_exists($fisier))
				{
				debug_mesaje(15); // fisierul nu exista -			
				}
			}
		*/
		if ($valoare == "css") // returneaza css.php
			{		
			debug_mesaje(16);				
			$director_template = explode(".",$_SESSION[$prefix_sesiuni.'_themes_far']);
			$fisier = "themes/".$director_template[0]."/css/css.php";
			if (file_exists($fisier))
				{
				$returneaza = include_once($fisier);			
				return $returneaza;
				}
			if (!file_exists($fisier))
				{
				die($mesaje_index[3].$fisier); // fisierul nu exista -
				}
			}		
	
		if ($valoare == "menu_1") // returneaza meniu sus 1
			{		
			debug_mesaje(17);				
			return show_menu(1);
			}
		
		if ($valoare == "menu_2") // returneaza meniu mijloc 2
			{		
			debug_mesaje(18);				
			return show_menu(2);
			}
			
		if ($valoare == "menu_3") // returneaza meniu jos 3
			{		
			debug_mesaje(19);				
			return show_menu(3);
			}
		
		if ($valoare == "menu_4") // returneaza meniu dreapta 4
			{		
			debug_mesaje(20);				
			return show_menu(4);
			}
	
		if ($valoare == "menu_5") // returneaza meniu stanga 5
			{		
			debug_mesaje(21);				
			return show_menu(5);
			}
		
		if ($valoare == "login") // includere partea de logare
			{
			debug_mesaje(22);		
			$fisier = "modules/login.php";
			if (file_exists($fisier))
				{
				$returneaza = include_once($fisier);			
				return $returneaza;
				}
			if (!file_exists($fisier))
				{
				die($mesaje_index[3].$fisier); // fisierul nu exista -			
				}
			}
		
		if ($valoare == "language") // includere partea de limbaj
			{
			debug_mesaje(23);		
			$fisier = "modules/language.php";
			if (file_exists($fisier))
				{
				$returneaza = include_once($fisier);			
				return $returneaza;
				}
			if (!file_exists($fisier))
				{
				die($mesaje_index[3].$fisier); // fisierul nu exista -			
				}
			}
		/*
		if ($valoare == "online") // returneaza online.php
			{		
			debug_mesaje(24);		
			$fisier = "modules/online.php";
			if (file_exists($fisier))
				{
				$returneaza = include_once($fisier);			
				return $returneaza;
				}
			if (!file_exists($fisier))
				{
				debug_mesaje(15); // fisierul nu exista -			
				}
			}
		*/
		if ($valoare == "status") // returneaza bara de stare partea de print si link
			{
			debug_mesaje(25);		
			$director_template = explode(".",$_SESSION[$prefix_sesiuni.'_themes_far']);
			$fisier = "themes/".$director_template[0]."/images/";
			
			//echo 'link - print - viev - etc';	
			$msj = '<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	  			<tr>
    			<td><div align="left">
				<a href="index.php"><font color="#ffffff">'.$mesaj_aici[1].'</font></a> | 
				<a href="'.$_SERVER['REQUEST_URI'].'"><font color="#ffffff">'.$mesaj_aici[2].'</font></a>
				</div></td>
    			<td><div align="right">
				<a href="'.$_SERVER['REQUEST_URI'].'" onclick="window.print();return false"><font color="#ffffff"> 
				<img src="'.$fisier.'print.gif" alt="'.$mesaj_aici[3].'" width="22" height="20" border="0"></font></a>
				<a href="'.$_SERVER['REQUEST_URI'].'" onclick="window.external.AddFavorite(top.location.href, top.document.title);return false"><font color="#ffffff">
				<img src="'.$fisier.'favpag.gif" alt="'.$mesaj_aici[4].'" width="22" height="22" border="0"></font></a> 
				<a href="mailto:'.$mesaj_aici[5].'?Subject='.$mesaj_aici[6].'&body='.$mesaj_aici[7].$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'"><font color="#ffffff">
				<img src="'.$fisier.'plic.gif" alt="'.$mesaj_aici[8].'" width="22" height="22" border="0"></font></a>
				</div></td>
  				</tr>
				</table>';		
			echo $msj;
			}		
		
		if ($valoare == "content") // returneaza pagina ceruta
			{		
			debug_mesaje(26);
			// se preia limbajul pentru afisarea continutului specific limbajului		
			$limbaj_prelucrat = $_SESSION[$prefix_sesiuni.'_language_far'];
			if (isset($_GET['p'])) // daca se cere pagina din bd
				{
				$p = $_GET['p'];			
				if (!empty($p)) // daca se cere o pagina...
					{			
					// daca se cere continutul
					if ($p == "content")
						{	
						$rezultate_afisare_pagina = 20; // specificati nr de rezultate care vor fi afisate pe pagina
						$numere_rand = 15; // specificati dupa cate numere se va trece la rand nou
						$numele_paginii = "index.php?p=content"; // numele acestei pagini in care aveti scriptul
						// se conecteaza la bd si citeste continutul
						$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
						mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
						$interogare = "SELECT * FROM ".$prefix_tabel_bd."content WHERE language_content = '".$limbaj_prelucrat."' OR  language_content = 'all' ORDER BY time_post DESC";
						$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
						$nr_total_pagini = (integer)(mysql_num_rows($rezultat)/$rezultate_afisare_pagina);
						if (isset($_GET['id']))
							{
							// daca se cere o anumita pagina 
							$id = $_GET['id'];
							if (is_numeric($id))
								{
								$interogare = "SELECT * FROM ".$prefix_tabel_bd."content WHERE language_content = '".$limbaj_prelucrat."' OR  language_content = 'all' ORDER BY time_post DESC LIMIT ".$id.",".$rezultate_afisare_pagina;
								}
							else
								{
								$interogare = "SELECT * FROM ".$prefix_tabel_bd."content WHERE language_content = '".$limbaj_prelucrat."' OR  language_content = 'all' ORDER BY time_post DESC LIMIT 0,".$rezultate_afisare_pagina;
								}
							}
						if (!isset($_GET['id']))
							{
							// daca se acceseaza pagina prima data se afiseaza ultimele inregistrari din bd 
							$interogare = "SELECT * FROM ".$prefix_tabel_bd."content WHERE language_content = '".$limbaj_prelucrat."' OR  language_content = 'all' ORDER BY time_post DESC LIMIT 0,".$rezultate_afisare_pagina;
							}
						$rezultat = mysql_query($interogare, $conectare) OR die("<br>Eroare 4 ");
						$total = mysql_num_rows($rezultat);
						if ($total != 0) // daca a fost gasita pagina ceruta o afiseaza
							{
							$un_num = 1;
							while ($rand = mysql_fetch_array($rezultat))
								{
								$data_continut = date("d-m-Y",$rand['time_post']);
								$titlu = htmlentities ($rand['title']);
								echo '<br>'.$un_num.' <a href="index.php?p='.$rand['name_content'].'">
									'.$titlu.'</a> - '.$mesaj[64].' '.$rand['user_post'].' - '.$data_continut.' - ('.$rand['language_content'].')<br>';
								$un_num++;
								}
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
							echo '<br><br><div align="center">';
							for ($s=1;$s<=$nr_total_pagini+1;$s++)
								{ 
								echo ' - <a href="'.$numele_paginii.'&id='.$id_m.'">'.$s.'</a>';
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
							echo $mesaj[121];
							}
						}
					else
						{
						// se conecteaza la bd si citeste continutul
						$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
						mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
						$interogare = "SELECT * FROM ".$prefix_tabel_bd."content WHERE name_content = '".$p."' AND language_content = '".$limbaj_prelucrat."'";
						$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
						$total = mysql_num_rows($rezultat);
						if ($total != 0) // daca a fost gasita pagina ceruta o afiseaza
							{
							while ($rand = mysql_fetch_array($rezultat))
								{
								$data_continut = date("d-m-Y",$rand['time_post']);
								echo '<p align="center"><strong>'.$rand['title'].'</strong></p><br>';
								echo '<p align="justify">'.$rand['content'].'</p><br><br>';								
								}
							}
						else // daca nu a fost gasita pagina ceruta
							{
							echo $mesaj[68]; // afiseaza mesajul de eroare 		
							}
						}
					}
				if (empty($p)) // daca nu se cere nici o pagina
					{
					echo $mesaj[68]; // afiseaza mesajul de eroare 		
					}
				}
			if (isset($_GET['c'])) // daca se cere pagina de script
				{
				$c = $_GET['c']; // se verifica daca se cere cumva o pagina cu script
				if (!empty($c)) // daca nu se cere nici asta probabil ca se incarca pagina pentru prima data si atunci se incarca pagina default
					{
					$continut = "content/".$c.".php";
					// se verifica daca pagina ceruta exista 
					if (file_exists($continut))
						{
						include_once($continut);					
						}
					else // daca pagina ceruta nu exista se afiseaza mesajul de eroare
						{
						echo $mesaj[68]; // afiseaza mesajul de eroare 		
						}	
					}
				if (empty($c)) // daca nu se cere nici asta probabil ca se incarca pagina pentru prima data si atunci se incarca pagina default
					{
					echo $mesaj[68]; // afiseaza mesajul de eroare 		
					}
				}
			if (!isset($_GET['c'])) // daca nu se cere nimic (pagina index afisata prima data)
				{
				if (!isset($_GET['p']))
					{
					// se conecteaza la bd si citeste pagina default
					$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
					mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
					$interogare = "SELECT * FROM ".$prefix_tabel_bd."content WHERE name_content LIKE 'default' AND language_content = '".$limbaj_prelucrat."'";
					$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
					$total = mysql_num_rows($rezultat);
					if ($total != 0) // daca a fost gasita pagina ceruta o afiseaza
						{
						while ($rand = mysql_fetch_array($rezultat))
							{
							echo '<p align="center"><strong>'.$rand['title'].'</strong></p><br>';
							echo '<p align="justify">'.$rand['content'].'</p><br><br>';		
							}
						}
					else // daca nu a fost gasita pagina ceruta
						{
						echo $mesaj[69];
						}
					// se afiseaza news (daca exista)
					$fisier = "modules/news.php";
					if (file_exists($fisier))
						{						
						include($fisier);
						}		
					}			
				}
			}
		
		if ($valoare == "down") // returneaza partea de jos cu logo si vizitatori
			{		
			debug_mesaje(27);
			$director_template = explode(".",$_SESSION[$prefix_sesiuni.'_themes_far']);
			
			echo '<div align="center">'.$mesaj_aici[9].$_SERVER['REMOTE_ADDR'].' - '.$mesaj_aici[10].guess_lang().'<br>
				'.$mesaj_aici[11].date("d-m-Y",time());
			// de aici asta se scoate la varianta finala
			$fisier = "modules/far_modules.php";
			if (file_exists($fisier))
				{
				$far_modules = 1;
				include($fisier);
				}		
			// pana aici se scoate
			echo '</div>';
			echo '<div align="center">';		
			echo '<a href="http://www.far-php.ro" target="_blank">
				<img src="themes/'.$director_template[0].'/images/far-php88x33en.gif" alt="The FAR-PHP project" width="88" height="31" border="0">
				</a>';
			// put here your logo
			$fisier = "modules/your_logo.php";
			if (file_exists($fisier))
				{			
				include_once($fisier);
				}
			if (!file_exists($fisier))
				{
				debug_mesaje(15); // fisierul nu exista -			
				}
			// de aici asta se scoate la varianta finala
			$fisier = "modules/far_modules.php";
			if (file_exists($fisier))
				{
				$far_modules = 2;
				include($fisier);
				}
			// pana aici se scoate
			echo '</div>';
			}
			
		if ($valoare == "copyright") // returneaza partea de jos cu copyrightul
			{		
			debug_mesaje(10);
			$fisier = "codes/end.php";
			if (file_exists($fisier))
				{			
				echo '<div align="center"><span class="style1">';
				include_once($fisier);
				echo '</span></div>';
				}
			if (!file_exists($fisier))
				{
				die($mesaje_index[3]."codes/end.php");
				}
			}		
		
		if ($valoare != "content")
			{
			if ($valoare != "meta")
				{
				if ($valoare != "top")
					{
					if ($valoare != "css")
						{
						if ($valoare != "menu_1")
							{
							if ($valoare != "menu_2")
								{
								if ($valoare != "menu_3")
									{
									if ($valoare != "menu_4")
										{
										if ($valoare != "menu_5")
											{
											if ($valoare != "login")
												{
												if ($valoare != "language")
													{
													if ($valoare != "status")
														{
														if ($valoare != "0")
															{
															if ($valoare != "down")
																{
																if ($valoare != "copyright")
																	{
																	//echo "<br>Valoare acum: ".$valoare."<br>";					
																	// debug_mesaje(24);		
																	$fisier = "modules/".$valoare.".php";
																	if (file_exists($fisier))
																		{
																		//echo $fisier;
																		$returneaza = include_once($fisier);			
																		return $returneaza;
																		}
																	if (!file_exists($fisier))
																		{
																		debug_mesaje(15); // fisierul nu exista -			
																		}
																	}
																}
															}
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
			
		}
	}

?>