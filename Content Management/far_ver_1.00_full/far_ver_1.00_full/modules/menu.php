<?php 
/* =====================================================================
*	Modulul de administrare a meniurilor = menu.php
*	Creat de Birkoff pentru proiectul FAR-PHP
*	Versiune: 1.0
*	Copyright: (C) 2004 - 2005 The FAR-PHP Group
*	E-mail: contact@far-php.ro
*	Inceput la: 25-12-2004
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
* ======================================================================== 

Ce face modulul asta?
1. verifica daca userul care a intrat aici are drepturi pentru acest modul
2. creaza meniuri noi
3. modifica meniuri deja create
4. sterge meniuri
--------------------------------------------------

 * ========================================================================
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 * ======================================================================== */

// se verifica daca userul are drepturi de acces aici
$verificare = 0;
if (!isset($_SESSION[$prefix_sesiuni.'_cheia_far'])) // daca nu exista cheia de sesiune
	{
	$verificare = 1;
	$nivel_acces = 6;
	}
else // daca exista cheia de sesiune...
	{
	if ($_SESSION[$prefix_sesiuni.'_cheia_far'] != session_id()) // se verifica daca cheia corespunde cu sesiunea curenta
		{
		$verificare = 1;
		}
	else // daca cheia corespunde cu sesiunea...
		{
		// se verifica daca userul are acces de nivel 1,2,3 sau 4
		if ($_SESSION[$prefix_sesiuni.'_rights_far'] != 1)
			{
			if ($_SESSION[$prefix_sesiuni.'_rights_far'] != 2)
				{
				if ($_SESSION[$prefix_sesiuni.'_rights_far'] != 3)
					{
					if ($_SESSION[$prefix_sesiuni.'_rights_far'] != 4)
						{
						$verificare = 1;
						$nivel_acces = $_SESSION[$prefix_sesiuni.'_rights_far'];
						}
					else // daca nivelul de acces e altul decat 4 se seteaza variabila de nivel
						{
						$nivel_acces = $_SESSION[$prefix_sesiuni.'_rights_far'];
						}
					}
				else // daca nivelul de acces e altul decat 3 se seteaza variabila de nivel
					{
					$nivel_acces = $_SESSION[$prefix_sesiuni.'_rights_far'];
					}
				}
			else // daca nivelul de acces e altul decat 2 se seteaza variabila de nivel
				{
				$nivel_acces = $_SESSION[$prefix_sesiuni.'_rights_far'];
				}
			}
		else // daca nivelul de acces e altul decat 1 se seteaza variabila de nivel
			{
			$nivel_acces = $_SESSION[$prefix_sesiuni.'_rights_far'];
			}
		}
	}
	
// daca userul are acces aici ...
if ($verificare == 0)
	{
	// se verifica daca se cere ceva sau se incarca pagina prima data
	@$id = $_GET['id'];
	@$actiune = $_GET['action'];
	// in caz ca se incara pagina prima data ========================================================
	if (empty($id)) // daca se incarca pagina prima data...
		{
		if (empty($actiune) OR ($actiune != "new"))
			{
			// se verifica cate meniuri sunt in bd			
			$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
			mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
			$interogare = "SELECT * FROM ".$prefix_tabel_bd."menu WHERE status>='".$nivel_acces."'";
			$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
			$total = mysql_num_rows($rezultat);
			$nr_meniuri = 0;
			while ($rand = mysql_fetch_array($rezultat))
				{
				// id meniu
				$id_meniuri[$nr_meniuri] = $rand['id'];
				// codul html
				$cod_meniuri[$nr_meniuri] = $rand['html'];
				// tipul meniului
				$tip_meniuri[$nr_meniuri] = $rand['type'];
				if ($tip_meniuri[$nr_meniuri] == 1)
					{
					$tipul[$nr_meniuri] = $mesaj[8]; // orizontal
					}
				if ($tip_meniuri[$nr_meniuri] == 2)
					{
					$tipul[$nr_meniuri] = $mesaj[9]; // vertical
					}
				// prioritatea meniului
				$prioritate_meniuri[$nr_meniuri] = $rand['priority'];	
				// locatia meniului
				$locatie_meniuri[$nr_meniuri] = $rand['location'];
				// meniu orizontal
				if (($locatie_meniuri[$nr_meniuri] == 1) & ($tip_meniuri[$nr_meniuri] == 1))
					{
					$locatia[$nr_meniuri] = $mesaj[13]; // sus
					}
				if (($locatie_meniuri[$nr_meniuri] == 2) & ($tip_meniuri[$nr_meniuri] == 1))
					{
					$locatia[$nr_meniuri] = $mesaj[14]; // mijloc
					}
				if (($locatie_meniuri[$nr_meniuri] == 3) & ($tip_meniuri[$nr_meniuri] == 1))
					{
					$locatia[$nr_meniuri] = $mesaj[15]; // jos
					}
				// meniu vertical
				if (($locatie_meniuri[$nr_meniuri] == 1) & ($tip_meniuri[$nr_meniuri] == 2))
					{
					$locatia[$nr_meniuri] = $mesaj[11]; // dreapta
					}
				if (($locatie_meniuri[$nr_meniuri] == 2) & ($tip_meniuri[$nr_meniuri] == 2))
					{
					$locatia[$nr_meniuri] = $mesaj[12]; // stanga
					}
				// limbajul pentru meniu
				$limba_meniuri[$nr_meniuri] = $rand['language'];
				// echo "<br>".$nr_meniuri." - ".$rand['language'];
				$limbaj[$nr_meniuri] = $rand['language'];
				/*if ($limba_meniuri[$nr_meniuri] == "all")
					{
					$limbaj[$nr_meniuri] = $mesaj[16]; // default sau nespecificat
					}*/
				// drepturi la meniuri
				$drepturi_meniuri[$nr_meniuri] = $rand['status'];
				if ($drepturi_meniuri[$nr_meniuri] == 1)
					{
					$drepturi[$nr_meniuri] = $mesaj[74]; // administrator
					}
				if ($drepturi_meniuri[$nr_meniuri] == 2)
					{
					$drepturi[$nr_meniuri] = $mesaj[75]; // sub-administrator
					}
				if ($drepturi_meniuri[$nr_meniuri] == 3)
					{
					$drepturi[$nr_meniuri] = $mesaj[76]; // moderator
					}
				if ($drepturi_meniuri[$nr_meniuri] == 4)
					{
					$drepturi[$nr_meniuri] = $mesaj[77]; // editor
					}
				if ($drepturi_meniuri[$nr_meniuri] == 5)
					{
					$drepturi[$nr_meniuri] = $mesaj[78]; // user
					}
				if ($drepturi_meniuri[$nr_meniuri] == 6)
					{
					$drepturi[$nr_meniuri] = $mesaj[79]; // guest (nelogat)
					}
				$nr_meniuri++;
				}
			mysql_close($conectare);
			// se afiseaza partea de adaugare meniu nou
			echo '<a href="admin.php?m=menu&action=new">'.$mesaj[70].'</a>';
			if ($total == 0)
				{
				echo " - ".$mesaj[5]; // daca nu e nici un meniu in bd
				}
			if ($total != 0)
				{
				if ($total == 1)
					{
					echo " - ".$mesaj[6];
					}
				if ($total != 1)
					{
					echo " - ".$total.$mesaj[7];
					}		
				// afisarea meniurilor
				echo '<br><br><table width="100%"  border="1" cellspacing="1" cellpadding="0">
					<tr>
					<td width="5%">'.$mesaj[17].'</td>
					<td width="50%"><div align="center">'.$mesaj[18].'</div></td>
					<td width="25%"><div align="center">'.$mesaj[19].'</div></td>
					<td width="20%"><div align="center">'.$mesaj[20].'</div></td>
			  		</tr>';
				$nr_meniuri = 0;
				foreach ($id_meniuri as $valoare)
					{	
					// ******************************************************************************************************
					
					echo '<tr>
						<td width="5%">'.$id_meniuri[$nr_meniuri].'</td>
			    		<td valign="top" width="50%">'.stripslashes($cod_meniuri[$nr_meniuri]).'</td>
			    		<td width="25%"><div align="center">'.$mesaj[24].$tipul[$nr_meniuri].'
						<br>'.$mesaj[25].$locatia[$nr_meniuri].'
						<br>'.$mesaj[26].$prioritate_meniuri[$nr_meniuri].'
						<br>'.$mesaj[27].$limbaj[$nr_meniuri].'
						<br>'.$mesaj[73].$drepturi[$nr_meniuri].'</div></td>';
					 // daca drepturile userului sunt mai mici nu are acces la stergere sau modificare
					if ($_SESSION[$prefix_sesiuni.'_rights_far'] <= $drepturi_meniuri[$nr_meniuri])
						{
			    		echo '<td width="20%"><div align="center"><a href="admin.php?m=menu&id='.$id_meniuri[$nr_meniuri].'&action=m">'.$mesaj[28].'</a> - <a href="admin.php?m=menu&id='.$id_meniuri[$nr_meniuri].'&action=s">'.$mesaj[29].'</a> </div></td>';
						}
					else
						{
						echo '<td width="20%"><div align="center">'.$mesaj[100].'</div></td>';
						}
		  			echo '</tr>'; 
				  	$nr_meniuri++;
			  		}
				echo '</table>';	
				}
			}
		}

	// daca se trimite o cerere sau un id ===========================================================================
	if (!empty($id)) // daca sa trimis vreun id...
		{
		if (!isset($_POST['modificare'])) // se verifica daca sa trimis cererea de modificare
			{
			// daca nu sa trimis cererea de modificare inseamna ca ori trebuie sters 
			// ori trebuie afisate informatiile din bd pentru modificare
			// daca actiunea ceruta este modificare se ruleaza comenzile de mai jos altfel
			// daca actiunea ceruta este de stergere se sare la linia 253
			// partea care executa modificarea unui meniu ales =======================================================
			if ($_GET['action'] == "m") // se verifica daca a fost trimisa cererea pentru afisarea datelor pentru a fi modificate
				{
				// se citeste meniul de la id-ul cerut
				$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
				mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
				$interogare = "SELECT * FROM ".$prefix_tabel_bd."menu WHERE id LIKE '".$id."'";
				$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
				$total = mysql_num_rows($rezultat);
				if ($total == 0)
					{
					echo $mesaj[23]; // nu exista meniu la id cerut
					}
				if ($total == 1)
					{
					while ($rand = mysql_fetch_array($rezultat))
						{
						$id_meniu = $rand['id'];
						$cod_meniu = stripslashes($rand['html']);
						$tip_meniu = $rand['type'];
						if ($tip_meniu == 1)
							{
							$tipul = $mesaj[8]; // orizontal
							}
						if ($tip_meniu == 2)
							{
							$tipul = $mesaj[9]; // vertical
							}
						$prioritate_meniu = $rand['priority'];	
						$locatie_meniu = $rand['location'];
						$drepturi_meniu = $rand['status'];
						// se verifica ce drepturi are pentru a fi afisate
						$drepturi = array (
							1 => $mesaj[57],
							2 => $mesaj[58],
							3 => $mesaj[59],
							4 => $mesaj[60],
							5 => $mesaj[61],
							6 => $mesaj[62]);	
						foreach ($drepturi as $cheie => $valoare)
							{
							if ($cheie == $drepturi_meniu)
								{
								$drept_acces = $valoare;
								}
							//echo "<br>drept = $cheie -- valoare = $valoare";		
							}
						// meniu orizontal
						if (($locatie_meniu == 1) & ($tip_meniu == 1))
							{
							$locatia = $mesaj[13]; // sus
							}
						if (($locatie_meniu == 2) & ($tip_meniu == 1))
							{
							$locatia = $mesaj[14]; // mijloc
							}
						if (($locatie_meniu == 3) & ($tip_meniu == 1))
							{
							$locatia = $mesaj[15]; // jos
							}
						// meniu vertical
						if (($locatie_meniu == 1) & ($tip_meniu == 2))
							{
							$locatia = $mesaj[11]; // dreapta
							}
						if (($locatie_meniu == 2) & ($tip_meniu == 2))
							{
							$locatia = $mesaj[12]; // stanga
							}
						$limba_meniu = $rand['language'];
						if ($limba_meniu == 0)
							{
							$limbaj = $mesaj[16]; // default sau nespecificat
							}			
						}				
					}
				mysql_close($conectare);
				// se verifica daca are drepturi de modificare la meniul cerut
				if ($_SESSION[$prefix_sesiuni.'_rights_far'] <= $drepturi_meniu) // daca drepturile sunt mai mici nu are acces la stergere sau modificare
					{
		    		// daca are drept
					// se afiseaza tabelul pentru modificare
					// aici e partea de script pentru meniu orizontal sau vertical
					// (ar mai trebui lucrat aici pentru ca poate da erori pe unele browsere)
					// poate o noua idee ar fi mai buna...
					echo '<script language="JavaScript" type="text/javascript"> 
							<!-- 
							function modifica(){ 
						   selectat = document.form_meniu.tip_meniu.selectedIndex; 
						   document.form_meniu.locatie_meniu.selectedIndex = null; 
						   document.form_meniu.locatie_meniu.innerHTML = ""; 
						   switch(selectat){ 
						      case 2: 
						         document.form_meniu.locatie_meniu.options[document.form_meniu.locatie_meniu.options.length] = new Option("'.$mesaj[11].'", "1"); 
						         document.form_meniu.locatie_meniu.options[document.form_meniu.locatie_meniu.options.length] = new Option("'.$mesaj[12].'", "2");          
						      break; 
						      case 1: 
						      default: 
						         document.form_meniu.locatie_meniu.options[document.form_meniu.locatie_meniu.options.length] = new Option("'.$mesaj[13].'", "1"); 
				        		 document.form_meniu.locatie_meniu.options[document.form_meniu.locatie_meniu.options.length] = new Option("'.$mesaj[14].'", "2"); 
						         document.form_meniu.locatie_meniu.options[document.form_meniu.locatie_meniu.options.length] = new Option("'.$mesaj[15].'", "3");          
						      break; 
						   } 
						} 
						//--> 
						</script> ';
					// aici se afiseaza form-ul cu datele extrase din bd pentru a fi modificate
					echo '<form name="form_meniu" method="post" action="admin.php?m=menu&id='.$id_meniu.'">
						<div align="center">
						'.$mesaj[31].'<br>
						'.$mesaj[24].'<select name="tip_meniu" onchange="modifica()"> 
						<option value="'.$tip_meniu.'">'.$tipul.'</option> 
						<option value="1">'.$mesaj[8].'</option> 
						<option value="2">'.$mesaj[9].'</option> 
						</select><br>
						'.$mesaj[25].'
						<select name="locatie_meniu">
						<option value="'.$locatie_meniu.'">'.$locatia.'</option> 
						</select> <br>
						'.$mesaj[26].'
						<select name="prioritate_meniu">
						<option value="'.$prioritate_meniu.'">'.$prioritate_meniu.'</option>';
					// se genereaza nr pentru prioritate
					// in mod normal nu cred ca sunt mai mult de 30 de submeniuri nu?
					for ($s=1;$s<=30;$s++)
						{
						echo '<option value="'.$s.'">'.$s.'</option>';
						}
					// se continua afisarea formularului
					echo '
						</select><br>
						'.$mesaj[27].'
						<input name="limbaj_meniu" type="text" value="'.$limba_meniu.'"><br>				
						'.$mesaj[73].' 
						<select name="stare" id="stare">
						<option value="'.$drepturi_meniu.'" selected>'.$drept_acces.'</option>
						  <option value="1">'.$mesaj[74].'</option>
						  <option value="2">'.$mesaj[75].'</option>
						  <option value="3">'.$mesaj[76].'</option>
						  <option value="4">'.$mesaj[77].'</option>
						  <option value="5">'.$mesaj[78].'</option>
						  <option value="6">'.$mesaj[79].'</option>
						  </select>
						<br>'.$mesaj[30].'
						<textarea name="cod_meniu" cols="50" rows="10">'.$cod_meniu.'</textarea><br>
						<input name="modificare" type="hidden" value="'.$id_meniu.'"><br>
						<input name="Submit" type="Submit" value="'.$mesaj[28].'">
						</div>
						</form>';
					}
				else
					{
					echo '<br>'.$mesaj[100];
					}			
				}			
			}
		// aici se preiau datele trimise pentru modificare ============================================================
		if (isset($_POST['modificare'])) // daca formularul pentru modificare cu noile date a fost trimis
			{
			// preluare date trimise
			@$tip_meniu = $_POST['tip_meniu'];
			@$locatie_meniu = $_POST['locatie_meniu'];
			@$prioritate_meniu = $_POST['prioritate_meniu'];
			@$limbaj_meniu = $_POST['limbaj_meniu'];
			@$cod_meniu = $_POST['cod_meniu'];
			@$id_meniu = $_POST['modificare'];
			@$stare = $_POST['stare'];
			/* verificare
			echo "<br>tip = $tip_meniu
				<br>loc = $locatie_meniu
				<br>prio = $prioritate_meniu
				<br>limb = $limbaj_meniu
				<br>cod = $cod_meniu
				<br>id = $id_meniu<br>"; */
			// se modifica datele de la meniul de la id-ul cerut
			$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
			mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
			// aici se scot caracterele speciale din cod pentru a putea fi introduse in bd
			$cod_meniu = mysql_real_escape_string($cod_meniu,$conectare);
			$limbaj_meniu = mysql_real_escape_string($limbaj_meniu,$conectare);
			$interogare = "UPDATE ".$prefix_tabel_bd."menu SET html='".$cod_meniu."', type='".$tip_meniu."', priority='".$prioritate_meniu."',
				location='".$locatie_meniu."', language='".$limbaj_meniu."', status='".$stare."' WHERE id LIKE '".$id_meniu."'";
			// echo $interogare;
			$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
			mysql_close($conectare);
			if ($rezultat == TRUE)
				{
				echo '<META HTTP-EQUIV = "Refresh" Content = "5; URL ='.$adresa_url.'">'.$mesaj[33];	// ok
				}
			else
				{
				echo '<META HTTP-EQUIV = "Refresh" Content = "5; URL ='.$adresa_url.'">'.$mesaj[92]; // eroare
				}
			}
		// aici incepe partea de stergere a unui meniu =========================================================================
		if (isset($_GET['action']))
		{
		if ($_GET['action'] == "s") // in caz ca actiunea ceruta este de stergere
			{
			// se preia valorile trimise
			@$id = $_GET['id'];
			// se verifica daca exista in bd si se preiau datele despre el pentru 
			// verificarea drepturilor de acces
			// se citeste meniul de la id-ul cerut
			$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
			mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
			$interogare = "SELECT * FROM ".$prefix_tabel_bd."menu WHERE id LIKE '".$id."'";
			$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
			$total = mysql_num_rows($rezultat);
			$verificare = 0;
			if ($total == 0)
				{
				$verificare = 1;
				echo $mesaj[23]; // nu exista meniu la id
				}
			if ($total == 1)
				{
				while ($rand = mysql_fetch_array($rezultat))
					{
					$id_meniu = $rand['id'];
					$cod_meniu = $rand['html'];
					$tip_meniu = $rand['type'];
					$prioritate_meniu = $rand['priority'];
					$locatie_meniu = $rand['location'];
					$limbaj_meniu = $rand['language'];
					$drepturi_meniu = $rand['status'];
					}
				if ($_SESSION[$prefix_sesiuni.'_rights_far'] <= $drepturi_meniu) // daca drepturile sunt mai mici nu are acces la stergere sau modificare
					{
		    		echo ""; // are drepturi
					}
				else
					{
					$verificare = 1;
					echo '<br>'.$mesaj[100]; // nu are drepturi
					}
				}
			// daca totul e ok se sterge
			if ($verificare == 0)
				{
				$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
				mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
				$interogare = "DELETE FROM ".$prefix_tabel_bd."menu WHERE id LIKE '".$id."'";
				// echo $interogare;
				// se sterge din bd inregistrarea corespunzatoare id-ului trimis
				// aici cred ca ar fi utila inca o interogare catre user inainte de a sterge datele din bd...
				$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);			
				mysql_close($conectare);
				echo '<META HTTP-EQUIV = "Refresh" Content = "5; URL ='.$adresa_url.'">'.$mesaj[33];				
				}
			}	
		}
		}
	// aici incepe partea de creare a unui meniu nou ========================================================================
	if ($actiune == "new") // daca se doreste crearea unui meniu nou
		{
		if (!isset($_POST['adaugare'])) // daca nu s-a trimis deja datele pentru adaugarea meniului
			{
			// aici e partea de script pentru meniu orizontal sau vertical
			// (ar mai trebui lucrat aici pentru ca poate da erori pe unele browsere)
			// poate o noua idee ar fi mai buna...
			echo '<script language="JavaScript" type="text/javascript"> 
				<!-- 
				function modifica(){ 
				selectat = document.form_meniu.tip_meniu.selectedIndex; 
				document.form_meniu.locatie_meniu.selectedIndex = null; 
				document.form_meniu.locatie_meniu.innerHTML = ""; 
				switch(selectat){ 
				case 2: 
				document.form_meniu.locatie_meniu.options[document.form_meniu.locatie_meniu.options.length] = new Option("'.$mesaj[11].'", "1"); 
				document.form_meniu.locatie_meniu.options[document.form_meniu.locatie_meniu.options.length] = new Option("'.$mesaj[12].'", "2");          
				break; 
				case 1: 
				default: 
				document.form_meniu.locatie_meniu.options[document.form_meniu.locatie_meniu.options.length] = new Option("'.$mesaj[13].'", "1"); 
				document.form_meniu.locatie_meniu.options[document.form_meniu.locatie_meniu.options.length] = new Option("'.$mesaj[14].'", "2"); 
				document.form_meniu.locatie_meniu.options[document.form_meniu.locatie_meniu.options.length] = new Option("'.$mesaj[15].'", "3");          
				break; 
					} 
				} 
				//--> 
				</script>';
			// se afiseaza formularul pentru adaugarea meniului nou
			echo '<table width="100%"  border="0" cellpadding="0" cellspacing="0">
	  			<tr>
			    <td valign="top">
				<form name="form_meniu" method="post" action="admin.php?m=menu&action=new">
					<div align="center"><br>				
					'.$mesaj[24].'<select name="tip_meniu" onchange="modifica()"> 
					<option value="0">'.$mesaj[71].'</option> 
				<option value="1">'.$mesaj[8].'</option> 
				<option value="2">'.$mesaj[9].'</option> 
				</select><br>
				'.$mesaj[25].'
				<select name="locatie_meniu">				
				</select> <br>
				'.$mesaj[26].'
				<select name="prioritate_meniu">';
			// se genereaza nr pentru prioritate
			// in mod normal nu cred ca sunt mai mult de 30 de submeniuri nu?
			for ($s=1;$s<=30;$s++)
				{
				echo '<option value="'.$s.'">'.$s.'</option>';
				}
			// se continua afisarea formularului
			echo '
				</select><br>
				'.$mesaj[27].'
				<input name="limbaj_meniu" type="text" value="all"><br>
				'.$mesaj[73].' 
				<select name="stare" id="stare">
				  <option value="1">'.$mesaj[74].'</option>
				  <option value="2">'.$mesaj[75].'</option>
				  <option value="3">'.$mesaj[76].'</option>
				  <option value="4">'.$mesaj[77].'</option>
				  <option value="5">'.$mesaj[78].'</option>
				  <option value="6" selected>'.$mesaj[79].'</option>
				  </select>
				<br>
				'.$mesaj[30].'
				<textarea name="cod_meniu" cols="50" rows="10"></textarea><br>
				<input name="adaugare" type="hidden" value="da"><br>
				<input name="Submit" type="Submit" value="'.$mesaj[72].'">
				</div>				
  		  		</form></td>
				  </tr>	
				</table>';
			}
		// aici se preiau datele pentru noul meniu si se salveaza in bd =============================================================
		else // daca au fost trimise datele noului meniu se introduc in bd
			{
			// se preiau valorile trimise
			@$tip_meniu = $_POST['tip_meniu'];
			@$locatie_meniu = $_POST['locatie_meniu'];
			@$prioritate_meniu = $_POST['prioritate_meniu'];
			@$limbaj_meniu = $_POST['limbaj_meniu'];
			@$stare = $_POST['stare'];
			@$cod_meniu = $_POST['cod_meniu'];
			// echo "<br>tip = $tip_meniu<br>loc = $locatie_meniu<br>prioritate = $prioritate_meniu<br>limbaj = $limbaj_meniu<br>stare = $stare<br>cod = $cod_meniu<br>";
			// se verifica daca au fost trimise valorile corect
			$verificare = 0;
			if (empty($locatie_meniu))
				{
				$verificare = 1;
				echo $mesaj[101]; // locatie incorecta
				}
			if (empty($cod_meniu))
				{
				$verificare = 1;
				echo $mesaj[102]; // lipsa cod html
				}			
			// se conecteaza la bd
			if ($verificare == 0)
				{
				$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
				mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
				// aici se scot caracterele speciale din cod pentru a putea fi introduse in bd
				$cod_meniu = mysql_real_escape_string($cod_meniu,$conectare);
				$limbaj_meniu = mysql_real_escape_string($limbaj_meniu,$conectare);
				$interogare = "INSERT INTO ".$prefix_tabel_bd."menu (html, type, priority, location, language, status) 
						VALUES ('".$cod_meniu."', '".$tip_meniu."', '".$prioritate_meniu."', '".$locatie_meniu."', 
						'".$limbaj_meniu."', '".$stare."')";
				// echo $interogare;
				$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);			
				mysql_close($conectare);
				echo '<META HTTP-EQUIV = "Refresh" Content = "1; URL ='.$adresa_url.'">'.$mesaj[103];
				}		
			}
		}
	} // paranteza de la verificare = 0
// daca userul nu are acces aici se afiseaza mesajul de eroare ================================================================
else
	{
	echo $mesaj[80];
	echo $mesaj[81].$nivel_acces;
	}
?>