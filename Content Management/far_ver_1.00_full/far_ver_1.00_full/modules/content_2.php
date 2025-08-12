<?php
/* =====================================================================
*	Pagina content_2.php (modulul de modificare/stergere continut)
*	Creat de Birkoff pentru proiectul FAR-PHP
*	Versiune: 1.0
*	Copyright: (C) 2004 - 2005 The FAR-PHP Group
*	E-mail: contact@far-php.ro
*	Data inceperii paginii: 19-02-2005
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
	if (!isset($_POST['formular_2']))
		{
		if (!isset($_POST['formular_3']))
			{
	// se verifica daca nu se cere lista
	if (isset($_GET['action']))
		{
		$actiune = $_GET['action'];
		// daca se cere altceva in afara de ce trebuie afiseaza formularul 1
		if ($actiune != "list")
			{
			if ($actiune != "select")
				{
				echo '<form name="continut_2" method="post" action="admin.php?m=content_2">
		  				<div align="center">'.$mesaj[201].' 
    					<br><br>'.$mesaj[202].'						
						<input type="text" name="nume_pagina">
						'.$mesaj[203].'.<br><br>
						'.$mesaj[204].' 
						<select name="selectare_actiune">
	  					<option value="1">'.$mesaj[205].'</option>
		  				<option value="2">'.$mesaj[206].'</option>
  						<option value="0" selected>'.$mesaj[207].'</option>
						</select>
						<br><br>
						<input name="form_content_2" type="hidden" id="form_content_2" value="trimis">
						<input type="submit" name="Submit" value="'.$mesaj[133].'"></div>
						</form>';
				}
			}		
		// daca se cere listare se afiseaza lista cu titluri
		if ($actiune == "list")
			{
			echo '<div align="center">'.$mesaj[201].'<br><br></div>';			
			// se conecteaza la bd si citeste continutul
			$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
			mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
			$interogare = "SELECT * FROM ".$prefix_tabel_bd."content";
			$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
			$total = mysql_num_rows($rezultat);
			if ($total != 0) // daca a fost gasita pagina ceruta o afiseaza
				{
				echo $mesaj[208];
				$un_num = 1;
				while ($rand = mysql_fetch_array($rezultat))
					{
					$data_continut = date("d-m-Y",$rand['time_post']);
					$titlu = htmlentities ($rand['title']);
					echo '<br>'.$un_num.' <a href="admin.php?m=content_2&action=select&id='.$rand['id'].'&name='.$rand['name_content'].'">
						'.$titlu.'</a> - '.$mesaj[64].' '.$rand['user_post'].' - '.$data_continut.' - ('.$rand['language_content'].')<br>';
					$un_num++;
					}
				}
			else
				{
				echo $mesaj[121];
				}
			}
		// daca a fost selectat din lista articolul se afiseaza formularul 1 pentru precizarea actiunii
		if ($actiune == "select")
			{			
			// se verifica daca este trimis un nume pentru titlu
			if (isset($_GET['id']))
				{
				// se verifica daca numele exista in bd
				$id_articol = $_GET['id'];
				$nume_articol = $_GET['name'];
				// se conecteaza la bd si citeste continutul
				$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
				mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
				$interogare = "SELECT * FROM ".$prefix_tabel_bd."content WHERE id = '".$id_articol."'";
				$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
				$total = mysql_num_rows($rezultat);
				if ($total != 0) // daca a fost gasita pagina ceruta se afiseaza formularul 1
					{					
					echo '<form name="continut_2" method="post" action="admin.php?m=content_2">
		  				<div align="center">'.$mesaj[201].'
    					<br><br>'.$mesaj[202].'						
						<input type="text" name="nume_pagina" value="'.$nume_articol.'">
						'.$mesaj[203].'<br><br>
						'.$mesaj[204].'
						<select name="selectare_actiune">
	  					<option value="1">'.$mesaj[205].'</option>
		  				<option value="2">'.$mesaj[206].'</option>
  						<option value="0" selected>'.$mesaj[207].'</option>
						</select>
						<br><br>
						<input name="id_articol" type="hidden" id="id_articol" value="'.$id_articol.'">
						<input name="form_content_2" type="hidden" id="form_content_2" value="trimis">
						<input type="submit" name="Submit" value="'.$mesaj[133].'"></div>
						</form>';
					}
				else
					{
					echo $mesaj[209];
					echo '<form name="continut_2" method="post" action="admin.php?m=content_2">
		  				<div align="center">'.$mesaj[201].'
    					<br><br>'.$mesaj[202].'						
						<input type="text" name="nume_pagina">
						'.$mesaj[203].'.<br><br>
						'.$mesaj[204].'
						<select name="selectare_actiune">
			  			<option value="1">'.$mesaj[205].'</option>
  						<option value="2">'.$mesaj[206].'</option>
		  				<option value="0" selected>'.$mesaj[207].'</option>
						</select>
						<br><br>
						<input name="form_content_2" type="hidden" id="form_content_2" value="trimis">
						<input type="submit" name="Submit" value="'.$mesaj[133].'"></div>
					</form>';
					}
				}
			if (!isset($_GET['name']))
				{
				// probabil se incearca hack
				echo '<form name="continut_2" method="post" action="admin.php?m=content_2">
		  				<div align="center">'.$mesaj[201].'
    					<br><br>'.$mesaj[202].'						
						<input type="text" name="nume_pagina">
						'.$mesaj[203].'.<br><br>
						'.$mesaj[204].'
						<select name="selectare_actiune">
			  			<option value="1">'.$mesaj[205].'</option>
  						<option value="2">'.$mesaj[206].'</option>
		  				<option value="0" selected>'.$mesaj[207].'</option>
						</select>
						<br><br>
						<input name="form_content_2" type="hidden" id="form_content_2" value="trimis">
						<input type="submit" name="Submit" value="'.$mesaj[133].'"></div>
					</form>';
				}
			}
		}
	// daca se incarca prima data pagina si nu a fost trimisa nici o actiune
	if (!isset($_GET['action']))
		{
		if (!isset($_POST['form_content_2']))
			{
			// atunci se afiseaza formularul 1
			echo '<form name="continut_2" method="post" action="admin.php?m=content_2">
  					<div align="center">'.$mesaj[201].'
    				<br><br>'.$mesaj[202].'					
					<input type="text" name="nume_pagina">
					'.$mesaj[203].'.<br><br>
					'.$mesaj[204].'
					<select name="selectare_actiune">
		  			<option value="1">'.$mesaj[205].'</option>
	  				<option value="2">'.$mesaj[206].'</option>
  					<option value="0" selected>'.$mesaj[207].'</option>
					</select>
					<br><br>
					<input name="form_content_2" type="hidden" id="form_content_2" value="trimis">
					<input type="submit" name="Submit" value="'.$mesaj[133].'"></div>
				</form>';
			}		
		// daca a fost trimis primul formular
		if (isset($_POST['form_content_2']))
			{
			// se verifica ce a fost trimis
			$nume_pagina = $_POST['nume_pagina'];
			$selectare_actiune = $_POST['selectare_actiune'];
			if (isset($_POST['id_articol']))
				{
				$id_articol = $_POST['id_articol'];
				}
			if (!isset($_POST['id_articol']))
				{
				$id_articol = '';
				}
			$verificare = 0;
			if (empty($id_articol))
				{
				$verificare = 1;
				echo $mesaj[225];
				}
			if (empty($nume_pagina))
				{
				$verificare = 1;
				echo $mesaj[210];
				}
			if ($selectare_actiune != 1)
				{
				if ($selectare_actiune != 2)
					{
					if (!empty($selectare_actiune))
						{
						$verificare = 1;
						echo $mesaj[211];
						}
					}
				}
			if ($selectare_actiune == 0)
				{
				$verificare = 1;
				echo $mesaj[212];
				}	
			if ($verificare == 0)
				{
				// se verifica daca numele exista in bd				
				// se conecteaza la bd si citeste continutul
				$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
				mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
				$interogare = "SELECT * FROM ".$prefix_tabel_bd."content WHERE name_content = '".$nume_pagina."' AND id = '".$id_articol."'";
				$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
				$total = mysql_num_rows($rezultat);
				if ($total == 0) // daca a fost gasita pagina ceruta se afiseaza formularul 1
					{
					$verificare = 1;
					echo $mesaj[209];
					}
				}		
			if ($verificare == 0)
				{
				// daca verificarile sunt ok se verifica de trebuie facut mai departe
				if ($selectare_actiune == 1) // daca se cere modificare
					{
					// se afiseaza pagina si formularul de modificare
					// afisare pagina
					// se conecteaza la bd si citeste continutul
					$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
					mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
					$interogare = "SELECT * FROM ".$prefix_tabel_bd."content WHERE name_content = '".$nume_pagina."' AND id = '".$id_articol."'";
					$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
					$total = mysql_num_rows($rezultat);
					if ($total != 0) // daca a fost gasita pagina ceruta o afiseaza
						{
						while ($rand = mysql_fetch_array($rezultat))
							{
							$data_continut = date("d-m-Y",$rand['time_post']);
							$text_articol = stripslashes($rand['content']);
							$titlul_pagini = $rand['title'];
							$nume_fisier = $nume_pagina;
							$autor_articol = $rand['author_content'];
							$email_autor = $rand['email_author'];
							$limbaj_articol = $rand['language_content'];
							$user_articol = $rand['user_post'];
							echo '<p align="center"><strong>'.$titlul_pagini.'</strong></p><br>';
							echo '<p align="justify">'.$text_articol.'</p><br><br>';			
							echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>
				 				 <tr>
								    <td>".$mesaj[64].$rand['user_post']."</td>
								    <td>".$mesaj[65].$data_continut."</td>
								    <td>".$mesaj[66]."
									<a href='mailto:".$email_autor."
									?Subject=".$mesaj[67]."
									&BCC=".$email_moderator."
									'>".$autor_articol."
									</a></td>
								  </tr>
								</table>";
							}
						}
					else // daca nu a fost gasita pagina ceruta
						{
						$verificare = 1;
						echo $mesaj[68]; // afiseaza mesajul de eroare 		
						}
					// afisare formular
					echo '<hr><div align="center">'.$mesaj[201].'</div>
	    					<br><br>
							<form name="form2_content_2" method="post" action="admin.php?m=content_2">
							<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
    						<td width="50%" valign="top"><div align="center">'.$mesaj[171].'*<br>      
		        			<textarea name="text_articol" cols="50" rows="10" id="text_articol">'.$text_articol.'</textarea>
							</div></td>
		    				<td width="50%" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="3">
      						<tr>
		        			<td width="50%"><div align="right">'.$mesaj[172].'</div></td>
        					<td width="50%"><input name="titlu" type="text" id="titlu" value="'.$titlul_pagini.'"></td>
		        			</tr>
      						<tr>
		        			<td><div align="right">'.$mesaj[173].'**</div></td>
        					<td><input name="nume_fisier" type="text" id="nume_fisier" value="'.$nume_fisier.'"></td>
		        			</tr>
      						<tr>
		        			<td><div align="right">'.$mesaj[169].'</div></td>
        					<td><input name="autor" type="text" id="autor" value="'.$autor_articol.'"></td>
		        			</tr>
      						<tr>
		        			<td><div align="right">'.$mesaj[174].'</div></td>
        					<td><input name="email_autor" type="text" id="email_autor" value="'.$email_autor.'"></td>
		        			</tr>
      						<tr>
		        			<td><div align="right">'.$mesaj[175].'</div></td>
        					<td><input name="limbaj" type="text" id="limbaj" value="'.$limbaj_articol.'"></td>
		        			</tr>
      						<tr>
		        			<td><div align="center">
        		  			<input type="submit" name="verificare" value="'.$mesaj[176].'">
		        			</div></td>
		        			<td><input type="submit" name="trimitere" value="'.$mesaj[177].'"></td>
        					</tr>
		    				</table></td>
 							</tr>
							</table>
							<input name="id_articol" type="hidden" id="id_articol" value="'.$id_articol.'">
							<input name="user_articol" type="hidden" id="user_articol" value="'.$user_articol.'">	
							<input name="form_content_2" type="hidden" id="form_content_2" value="trimis">	
							<input name="formular_2" type="hidden" id="formular_2" value="trimis">							
							</form>
							<br>* - '.$mesaj[178].'
							<br>** - '.$mesaj[179].'<br><br>';
					}
				if ($selectare_actiune == 2) // daca se cere stergere
					{
					$nume_pagina = $_POST['nume_pagina'];
					$id_articol = $_POST['id_articol'];
					// se afiseaza pagina si formularul de stergere
					// afisare pagina
					// se conecteaza la bd si citeste continutul
					$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
					mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
					$interogare = "SELECT * FROM ".$prefix_tabel_bd."content WHERE name_content = '".$nume_pagina."' AND id = '".$id_articol."'";
					$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
					$total = mysql_num_rows($rezultat);
					if ($total != 0) // daca a fost gasita pagina ceruta o afiseaza
						{
						while ($rand = mysql_fetch_array($rezultat))
							{
							$data_continut = date("d-m-Y",$rand['time_post']);
							$text_articol = stripslashes($rand['content']);
							$titlul_pagini = $rand['title'];
							$nume_fisier = $nume_pagina;
							$autor_articol = $rand['author_content'];
							$email_autor = $rand['email_author'];
							$limbaj_articol = $rand['language_content'];
							$user_articol = $rand['user_post'];
							echo '<p align="center"><strong>'.$titlul_pagini.'</strong></p><br>';
							echo '<p align="justify">'.$text_articol.'</p><br><br>';			
							echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>
				 				 <tr>
								    <td>".$mesaj[64].$rand['user_post']."</td>
								    <td>".$mesaj[65].$data_continut."</td>
								    <td>".$mesaj[66]."
									<a href='mailto:".$email_autor."
									?Subject=".$mesaj[67]."
									&BCC=".$email_moderator."
									'>".$autor_articol."
									</a></td>
								  </tr>
								</table>";
							}
						}
					else // daca nu a fost gasita pagina ceruta
						{
						$verificare = 1;
						echo $mesaj[68]; // afiseaza mesajul de eroare 		
						}
					// afisare formular
					echo '<hr><div align="center"><form name="form2" method="post" action="admin.php?m=content_2">
  							<input name="nume_pagina" type="hidden" id="nume_pagina" value="'.$nume_pagina.'">
							<input name="id_articol" type="hidden" id="id_articol" value="'.$id_articol.'">
							<input name="formular_3" type="hidden" id="formular_3" value="da">
  							<input type="submit" name="Submit" value="'.$mesaj[213].'">
						</form></div>';
					}
				}
			if ($verificare == 1) // daca verificarea a esuat se afiseaza din nou formularul 1 cu datele trimise
				{
				echo '<form name="continut_2" method="post" action="admin.php?m=content_2">
		  				<div align="center">'.$mesaj[201].'
    					<br><br>'.$mesaj[202].'
						<input type="text" name="nume_pagina" value="'.$nume_pagina.'">
						'.$mesaj[203].'.<br><br>
						'.$mesaj[204].'
						<select name="selectare_actiune">
	  					<option value="1">'.$mesaj[205].'</option>
		  				<option value="2">'.$mesaj[206].'</option>
  						<option value="0" selected>'.$mesaj[207].'</option>
						</select>
						<br><br>
						<input name="id_articol" type="hidden" id="id_articol" value="'.$id_articol.'">
						<input name="form_content_2" type="hidden" id="form_content_2" value="trimis">
						<input type="submit" name="Submit" value="'.$mesaj[133].'"></div>
						</form>';
				}
			}
		}
		}
		}
	if (isset($_POST['formular_2'])) // daca a fost trimis formularul 2
		{
		// se verifica ce se cere verificare sau modificare
		@$verificare = $_POST['verificare'];
		@$trimitere = $_POST['trimitere'];		
		if (empty($trimitere)) // daca se cere verificare
			{
			$nume_pagina = $_POST['nume_fisier'];
			$titlul_pagini = $_POST['titlu'];
			$text_articol = stripslashes($_POST['text_articol']);
			$nume_fisier = $nume_pagina;
			$autor_articol = $_POST['autor'];
			$email_autor = $_POST['email_autor'];
			$limbaj_articol = $_POST['limbaj'];
			$data_continut = date("d-m-Y",time());
			$user_articol = $_POST['user_articol'];
			$id_articol = $_POST['id_articol'];
			// se afiseaza pagina si formularul de modificare
			// afisare pagina			
			echo '<p align="center"><strong>'.$titlul_pagini.'</strong></p><br>';
			echo '<p align="justify">'.$text_articol.'</p><br><br>';			
			echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>
				 	<tr>
				    <td>".$mesaj[64].$user_articol."</td>
				    <td>".$mesaj[65].$data_continut."</td>
				    <td>".$mesaj[66]."
					<a href='mailto:".$email_autor."
					?Subject=".$mesaj[67]."
					&BCC=".$email_moderator."
					'>".$autor_articol."
					</a></td>
				  </tr>
				</table>";			
			// afisare formular
			echo '<hr><div align="center">'.$mesaj[201].'</div>
	    			<br><br>
					<form name="form2_content_2" method="post" action="admin.php?m=content_2">
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
    				<td width="50%" valign="top"><div align="center">'.$mesaj[171].'*<br>      
		        	<textarea name="text_articol" cols="50" rows="10" id="text_articol">'.$text_articol.'</textarea>
					</div></td>
		    		<td width="50%" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="3">
      				<tr>
		        	<td width="50%"><div align="right">'.$mesaj[172].'</div></td>
        			<td width="50%"><input name="titlu" type="text" id="titlu" value="'.$titlul_pagini.'"></td>
		        	</tr>
      				<tr>
		        	<td><div align="right">'.$mesaj[173].'**</div></td>
        			<td><input name="nume_fisier" type="text" id="nume_fisier" value="'.$nume_fisier.'"></td>
		        	</tr>
      				<tr>
		        	<td><div align="right">'.$mesaj[169].'</div></td>
        			<td><input name="autor" type="text" id="autor" value="'.$autor_articol.'"></td>
		        	</tr>
      				<tr>
		        	<td><div align="right">'.$mesaj[174].'</div></td>
        			<td><input name="email_autor" type="text" id="email_autor" value="'.$email_autor.'"></td>
		        	</tr>
      				<tr>
		        	<td><div align="right">'.$mesaj[175].'</div></td>
        			<td><input name="limbaj" type="text" id="limbaj" value="'.$limbaj_articol.'"></td>
		        	</tr>
      				<tr>
		        	<td><div align="center">
        		  	<input type="submit" name="verificare" value="'.$mesaj[176].'">
		        	</div></td>
		   			<td><input type="submit" name="trimitere" value="'.$mesaj[177].'"></td>
        			</tr>
					</table></td>
 					</tr>
					</table>
					<input name="id_articol" type="hidden" id="id_articol" value="'.$id_articol.'">
					<input name="user_articol" type="hidden" id="user_articol" value="'.$user_articol.'">
					<input name="form_content_2" type="hidden" id="form_content_2" value="trimis">	
					<input name="formular_2" type="hidden" id="formular_2" value="trimis">							
					</form>
					<br>* - '.$mesaj[178].'
					<br>** - '.$mesaj[179].'<br><br>';
			}
		if (!empty($trimitere)) // daca se cere modificare
			{
			// se preiau datele trimise
			$text_articol = stripslashes($_POST['text_articol']);
			$titlu = $_POST['titlu'];
			$titlul_pagini = $titlu;
			$nume_fisier = $_POST['nume_fisier'];
			$autor = $_POST['autor'];
			$autor_articol = $autor;
			$email_autor = $_POST['email_autor'];
			$limbaj = $_POST['limbaj'];
			$limbaj_articol = $limbaj;
			$user_articol = $_POST['user_articol'];
			$data_continut = date("d-m-Y",time());
			$id_articol = $_POST['id_articol'];
			// se verifica daca sunt corecte
			$verificare = 0;
			if (empty($id_articol))
				{
				$verificare = 1;
				echo $mesaj[225];
				}
			if (empty($text_articol))
				{
				$verificare = 1;
				echo $mesaj[214];
				}
			if (empty($titlu))
				{
				$verificare = 1;
				echo $mesaj[215];
				}
			if (empty($nume_fisier))
				{
				$verificare = 1;
				echo $mesaj[216];
				}
			if (empty($autor))
				{
				$verificare = 1;
				echo $mesaj[217];
				}
			if (empty($email_autor))
				{
				$verificare = 1;
				echo $mesaj[218];
				}
			if (!empty($email_autor)) // daca e scrisa corect adresa
				{			
				if (eregi("^[a-z0-9\._-]+@+[a-z0-9\._-]+\.+[a-z]{2,3}$",$email_autor) != TRUE) 
				   {
				   echo $mesaj[88];
				   $verificare = 1;
				   }
				}
			if ($limbaj != 0)
				{
				if (empty($limbaj))
					{
					$verificare = 1;					
					echo $mesaj[219];
					}
				}
			if ($verificare == 1) // daca exista erori se afiseaza din nou articolul si formularul
				{
				// afisare pagina			
				echo '<p align="center"><strong>'.$titlul_pagini.'</strong></p><br>';
				echo '<p align="justify">'.$text_articol.'</p><br><br>';			
				echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>
					 	<tr>
					    <td>".$mesaj[64].$user_articol."</td>
					    <td>".$mesaj[65].$data_continut."</td>
					    <td>".$mesaj[66]."
						<a href='mailto:".$email_autor."
						?Subject=".$mesaj[67]."
						&BCC=".$email_moderator."
						'>".$autor_articol."
						</a></td>
						  </tr>
					</table>";			
				// afisare formular
				echo '<hr><div align="center">'.$mesaj[201].'</div>
		    			<br><br>
						<form name="form2_content_2" method="post" action="admin.php?m=content_2">
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
	    				<td width="50%" valign="top"><div align="center">'.$mesaj[171].'*<br>      
			        	<textarea name="text_articol" cols="50" rows="10" id="text_articol">'.$text_articol.'</textarea>
						</div></td>
			    		<td width="50%" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="3">
	      				<tr>
			        	<td width="50%"><div align="right">'.$mesaj[172].'</div></td>
	        			<td width="50%"><input name="titlu" type="text" id="titlu" value="'.$titlul_pagini.'"></td>
			        	</tr>
	      				<tr>
			        	<td><div align="right">'.$mesaj[173].'**</div></td>
	        			<td><input name="nume_fisier" type="text" id="nume_fisier" value="'.$nume_fisier.'"></td>
			        	</tr>
	      				<tr>
			        	<td><div align="right">'.$mesaj[169].'</div></td>
	        			<td><input name="autor" type="text" id="autor" value="'.$autor_articol.'"></td>
			        	</tr>
	      				<tr>
			        	<td><div align="right">'.$mesaj[174].'</div></td>
	        			<td><input name="email_autor" type="text" id="email_autor" value="'.$email_autor.'"></td>
			        	</tr>
	      				<tr>
			        	<td><div align="right">'.$mesaj[175].'</div></td>
	        			<td><input name="limbaj" type="text" id="limbaj" value="'.$limbaj_articol.'"></td>
			        	</tr>
	      				<tr>
			        	<td><div align="center">
	        		  	<input type="submit" name="verificare" value="'.$mesaj[176].'">
			        	</div></td>
			   			<td><input type="submit" name="trimitere" value="'.$mesaj[177].'"></td>
    	    			</tr>
						</table></td>
 						</tr>
						</table>
						<input name="id_articol" type="hidden" id="id_articol" value="'.$id_articol.'">
						<input name="user_articol" type="hidden" id="user_articol" value="'.$user_articol.'">
						<input name="form_content_2" type="hidden" id="form_content_2" value="trimis">	
						<input name="formular_2" type="hidden" id="formular_2" value="trimis">							
						</form>
						<br>* - '.$mesaj[178].'
						<br>** - '.$mesaj[179].'<br><br>';
				}
			if ($verificare == 0) // daca totul e ok se salveaza articolul in bd
				{
				// se conecteaza la bd si se salveaza datele
				$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
				mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
				$nume_fisier = mysql_real_escape_string($nume_fisier,$conectare); 
				$titlul_pagini = mysql_real_escape_string($titlul_pagini,$conectare); 
				$text_articol = mysql_real_escape_string($text_articol,$conectare); 
				$limbaj_articol = mysql_real_escape_string($limbaj_articol,$conectare); 
				$autor_articol = mysql_real_escape_string($autor_articol,$conectare); 
				$interogare = "UPDATE ".$prefix_tabel_bd."content SET 						
						title = '".$titlul_pagini."', 
						content = '".$text_articol."', 
						language_content = '".$limbaj_articol."', 
						author_content = '".$autor_articol."', 
						email_author = '".$email_autor."', 
						user_post = '".$_SESSION[$prefix_sesiuni.'_user_far']."', 
						time_post = '".time()."'
						WHERE name_content = '".$nume_fisier."' AND id = '".$id_articol."'";
				// echo $interogare;
				$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
				if ($rezultat == FALSE)
					{
					$verificare = 1;
					echo '<META HTTP-EQUIV = "Refresh" Content = "3; URL ='.$adresa_url.'">'.$mesaj[154];
					}
				else
					{
					echo '<META HTTP-EQUIV = "Refresh" Content = "3; URL ='.$adresa_url.'">'.$mesaj[170];
					}
				mysql_close($conectare);			
				}
			}			
		}
	if (isset($_POST['formular_3'])) // daca a fost trimis formularul de stergere
		{
		// se preia numele paginii
		$nume_pagina = $_POST['nume_pagina'];
		$id_articol = $_POST['id_articol'];
		// se conecteaza la bd si se salveaza sterge pagina
		$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
		mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
		$interogare = "DELETE FROM ".$prefix_tabel_bd."content WHERE name_content = '".$nume_pagina."' AND id = '".$id_articol."'";
		// echo $interogare;
		$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
		if ($rezultat == FALSE)
			{
			$verificare = 1;
			echo '<META HTTP-EQUIV = "Refresh" Content = "3; URL ='.$adresa_url.'">'.$mesaj[220];
			}
		else
			{
			echo '<META HTTP-EQUIV = "Refresh" Content = "3; URL ='.$adresa_url.'">'.$mesaj[221];
			}
		mysql_close($conectare);			
		}
	}
// daca userul nu are acces aici se afiseaza mesajul de eroare ================================================================
else
	{
	echo $mesaj[80];
	echo $mesaj[81].$nivel_acces;
	}