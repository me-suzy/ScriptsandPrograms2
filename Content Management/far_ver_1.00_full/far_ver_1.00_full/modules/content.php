<?php
/* =====================================================================
*	Pagina content.php (modulul de continut)
*	Creat de Birkoff pentru proiectul FAR-PHP
*	Versiune: 1.0
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

// ****************************************************************************
// Nota 22-04-2005 - Birkoff
// functia php gethostbyaddr() incetineste rularea acestui modul cu 19 secunde
// daca este rulat din localhost, in rest merge ok
// trebuie facut ceva in privinta asta
// ****************************************************************************

// Cod si idee realizate de Birkoff ;-p
//
// ce contin formularele
//
// formularul 1 
// bifa daca sunt poze - cate poze sunt
// bifa fisiere pentru downl - cate fisiere
// bifa cod php
// 
// formularul 2
// upload poze
//
// formularul 3
// upload fisiere
// 
// formularul 4
// upload cod php
//
// formularul 5
// upload continut
//
// abordarea 1 - idee simpla de scris codul
//
// 1. verifica daca userul are acces aici
	// 2. da -> verifica daca a fost trimis formularul 1
		// 3. nu -> afiseaza formularul 1
		// 4. da -> verifica daca datele sunt corecte
			// 5. nu -> reia de la punctul 3
			// 6. da -> verifica daca sunt poze
				// 6.a. da -> afiseaza formularul 2
				// 6.b. nu -> verifica daca sunt fisiere ptr. upl.
					// 6.c. da -> afiseaza formularul 3
					// 6.d. nu -> verifica daca continutul e format php
						// 6.e. da -> afiseaza formularul 4
						// 6.f. nu -> afiseaza formularul 5 
	// 7. da -> verifica daca a fost trimis formularul 2
		// 8. da -> verifica daca datele sunt corecte
			// 9. nu -> reia de la punctul 7
			// 10. da -> afiseaza formularul 3
	// 11. da -> verifica daca a fost trimis formularul 3
		// 12. da -> verifica daca datele sunt corecte
			// 13. nu -> reia de la punctul 10
			// 14. da -> 
//
// abordarea 2 - idee avansata de scris codul
//
// cazul 1 -> verificare acces
	// da -> merge la cazul 2
	// nu -> opreste scriptul
// cazul 2 -> verifica daca a fost trimis un formular
	// da -> merge la cazul pentru formularul respectiv
	// nu -> merge la cazul 3
// cazul 3 -> afisare formular 1 - se intreaba ce tip de continut e si ce contine...
	// se verifica daca a fost trimis formularul
		// da -> se preiau datele si se verifica 
			// daca sunt ok merge la cazul 4
			// daca sunt probleme reia cazul 3
		// nu -> afiseaza formularul
// cazul 4 -> afisare formular 2 - se incarca pozele
	// se verifica daca trebuie afisat formularul 2
		// da -> se verifica daca a fost deja trimis formularul
			// da -> se preiau datele si se verifica
				// daca sunt ok face transferul 
					// daca transferul e ok salveaza in bd 
						// daca salvarea in bd e ok merge la cazul 5
						// daca sunt probleme reia cazul 4
					// daca sunt probleme reia cazul 4
				// daca sunt probleme reia cazul 4
			// nu -> se afiseaza formularul 2
		// nu -> merge la cazul 5
// cazul 5 - afisare formular 3 - se incarca fisierele pentru dwld
	// se verifica daca trebuie afisat formularul 3
		// da -> se verifica daca a fost trimis deja formularul
			// da -> se preiau datele si se verifica
				// daca sunt ok face transferul 
					// daca transferul e ok salveaza in bd
						// daca salvarea in bd e ok merge la cazul 6
						// daca sunt probleme reia cazul 5
					// daca sunt probleme reia cazul 5
				// daca sunt probleme reia cazul 5
			// nu -> se afiseaza formularul 3
		// nu -> merge la cazul 6
// cazul 6 - afisare formular 4 - upload fisiere cu script php
	// se verifica daca trebuie afisat formularul 4
		// da -> se verifica daca a fost trimis deja formularul
			// da -> se preiau datele si se verifica
				// daca sunt ok face transferul 
					// daca transferul e ok salveaza in bd
						// daca salvarea in bd e ok merge la cazul 7
						// daca sunt probleme reia cazul 6
					// daca sunt probleme reia cazul 6
				// daca sunt probleme reia cazul 6
			// nu -> se afiseaza formularul 4
		// nu -> merge la cazul 7
// cazul 7 - afisare formular 5 - upload continut text
	// se verifica daca trebuie afisat formularul 5 (daca a fost uploadat cod php nu mai trebuie fisier text)
		// da -> se verifica daca a fost trimis deja formularul
			// da -> se preiau datele si se verifica
				// daca sunt ok se verifica daca cere previev sau upload
					// previev -> afiseaza datele si formularul 5 cu datele trimise
					// upload -> se salveaza datele in bd 					
						// daca salvarea in bd e ok merge la cazul 8
						// daca sunt probleme reia cazul 7
					// daca sunt probleme reia cazul 7
				// daca sunt probleme reia cazul 7
			// nu -> se afiseaza formularul 5
		// nu -> merge la cazul 8
// cazul 8 - redirectare catre pagina cu datele trimise
	// se verifica daca totul e ok si trebuie redirectata pagina
		// nu -> reia cazul 1
		// da -> redirecteaza catre pagina cu datele trimise
//
// de aici incepe codul propriuzis dupa ideile de mai sus...
//
// cazul 1 ==========================================================================================
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
	// 
	if (isset($_POST['formular_1'])) // cazul 3 -> da
		{
		@$poze = $_POST['poze'];
		@$cate_poze = $_POST['cate_poze'];
		@$download = $_POST['download'];
		@$cate_download = $_POST['cate_download'];
		@$cod_php = $_POST['cod_php'];
		// se initializeaza variabilele pentru afisarea formularelor		
		// se verifica ce se cere
		if (!empty($poze))
			{
			$afiseaza_f2 = 0; // se afiseaza formularul 2 - cazul 4 -> da
			}
		if (empty($poze))
			{
			$afiseaza_f2 = 1; // cazul 4 -> nu
			}			
		if (!empty($download))
			{
			$afiseaza_f3 = 0; // se afiseaza formularul 3 - cazul 5 -> da
			}
		if (empty($download))
			{
			$afiseaza_f3 = 1; // cazul 5 -> nu
			}		
		if (!empty($cod_php))
			{
			$cerinte = 1; // cazul 7 -> nu
			$afiseaza_f4 = 0; // se afiseaza formularul 4 - cazul 6 -> da
			}
		if (empty($cod_php))
			{
			$cerinte = 0; // se afiseaza formularul 5 - cazul 7 -> da
			$afiseaza_f4 = 1; // cazul 6 -> nu
			}		
		if ($cerinte == 0)
			{
			$afiseaza_f5 = 0; // se afiseaza formularul 5
			}
		}
	//
	if (!isset($_POST['formular_1'])) // cazul 3 -> nu
		{
		if (!isset($_POST['formular_2']))
			{
			$afiseaza_f2 = 1;
			$afiseaza_f3 = 1;
			$afiseaza_f4 = 1;
			$afiseaza_f5 = 1;
			echo '<form action="admin.php?m=content" method="post" name="form_1" id="form_1">
				<table width="100%"  border="0" cellpadding="0" cellspacing="0">
				  <tr>
				    <td valign="top"><table width="100%"  border="0" cellpadding="0" cellspacing="3">
				      <tr>
				        <td width="50%" valign="top"><table width="100%"  border="0" cellpadding="0" cellspacing="0">
				            <tr>
				              <td><div align="right">'.$mesaj[129].'</div></td>
				              <td><input name="poze" type="checkbox" id="poze3" value="da"></td>
				            </tr>
				        </table></td>
				        <td width="50%" valign="top"><table width="100%"  border="0" cellpadding="0" cellspacing="0">
				            <tr>
				              <td width="30%"><div align="center">'.$mesaj[130].'</div></td>
				              <td><select name="cate_poze" id="select2">';
							  // se genereaza meniul pentru 10 obiecte
							  for ($qq=1;$qq<=10;$qq++)
							  	{
								echo '<option value="'.$qq.'">'.$qq.'</option>';
								}
				              // se continua afisarea formularului
							  echo '
				              </select></td>
				            </tr>
				        </table></td>
				      </tr>
				    </table></td>
				  </tr>
				  <tr>
				    <td valign="top"><table width="100%"  border="0" cellpadding="0" cellspacing="3">
				      <tr>
				        <td width="50%" valign="top"><table width="100%"  border="0" cellpadding="0" cellspacing="0">
				            <tr>
				              <td><div align="right">'.$mesaj[131].'</div></td>
				              <td><input name="download" type="checkbox" id="poze4" value="da"></td>
				            </tr>
				        </table></td>
				        <td width="50%" valign="top"><table width="100%"  border="0" cellpadding="0" cellspacing="0">
				            <tr>
				              <td width="30%"><div align="center">'.$mesaj[130].'</div></td>
				              <td><select name="cate_download" id="select3">';
				              // se genereaza meniul pentru 10 obiecte
							  for ($qq=1;$qq<=10;$qq++)
							  	{
								echo '<option value="'.$qq.'">'.$qq.'</option>';
								}
				              // se continua afisarea formularului
							  echo '
				              </select></td>
				            </tr>
				        </table></td>
				      </tr>
				    </table></td>
				  </tr>
				  <tr>
				    <td valign="top"><table width="100%"  border="0" cellpadding="0" cellspacing="3">
				      <tr>
				        <td width="50%"><div align="right">'.$mesaj[132].'</div></td>
				        <td width="50%"><input name="cod_php" type="checkbox" id="cod_php" value="da"></td>
				        </tr>
				    </table></td>
				  </tr>
				  <tr>
				    <td><div align="center">
				      <input name="formular_1" type="hidden" id="formular_1" value="trimis">
				      <input type="submit" name="Submit" value="'.$mesaj[133].'">
				    </div></td>
				  </tr>
				</table>
				</form>';
			}
		}
	// cazul 4 ======================================================================================
	if ($afiseaza_f2 == 0) // se verifica daca trebuie afisat formularul 2
		{
		if (isset($_POST['formular_2'])) // se verifica daca nu a fost deja trimis formularul 2
			{
			$afiseaza_f2 = 1;
			$afiseaza_f3 = 1;
			$afiseaza_f4 = 1;
			$afiseaza_f5 = 1;
			// preia datele din formularul 2
			@$poze = $_POST['poze'];
			@$cate_poze = $_POST['cate_poze'];
			@$download = $_POST['download'];
			@$cate_download = $_POST['cate_download'];
			@$cod_php = $_POST['cod_php'];
			// pentru fiecare fisier de upload se seteaza variabilele			
			$verificare = 0;
			for ($qq=1;$qq<=$cate_poze;$qq++)
				{
				$poza_curenta = "poza_".$qq;
				@$poza_upl_temp_nume[$qq] = $_FILES[$poza_curenta]['tmp_name']; 
				@$poza_upl_nume[$qq] = $_FILES[$poza_curenta]['name']; 
				@$poza_upl_marime[$qq] = $_FILES[$poza_curenta]['size']; 
				@$poza_ulp_tip[$qq] = $_FILES[$poza_curenta]['type']; 
				@$poza_upl_eroare[$qq] = $_FILES[$poza_curenta]['error']; 
				// echo "<br>nume = ".$poza_upl_nume[$qq];
				// se verifica daca au fost introduse toate pozele cerute
				if (empty($poza_upl_nume[$qq]))
					{
					$verificare = 1;
					echo $mesaj[134].$qq;
					}
				// se verifica daca extensia corespunde - tipul fisierelor
				if (!empty($poza_upl_nume[$qq]))
					{
					// se accepta doar jpg, jpe, jpeg, gif, png, bmp si swf
					$extensia[$qq] = explode(".", $poza_upl_nume[$qq]);
					// echo "<br>$qq - ".$extensia[$qq][1]."<br>";
					if ($extensia[$qq][1] != "jpg")
						{
						if ($extensia[$qq][1] != "Jpg")
							{
							if ($extensia[$qq][1] != "JPG")
								{
								if ($extensia[$qq][1] != "jpe")
									{
									if ($extensia[$qq][1] != "Jpe")
										{
										if ($extensia[$qq][1] != "JPE")
											{
											if ($extensia[$qq][1] != "jpeg")
												{
												if ($extensia[$qq][1] != "Jpeg")
													{
													if ($extensia[$qq][1] != "JPEG")
														{
														if ($extensia[$qq][1] != "gif")
															{
															if ($extensia[$qq][1] != "Gif")
																{
																if ($extensia[$qq][1] != "GIF")
																	{
																	if ($extensia[$qq][1] != "png")
																		{
																		if ($extensia[$qq][1] != "Png")
																			{
																			if ($extensia[$qq][1] != "PNG")
																				{
																				if ($extensia[$qq][1] != "bmp")
																					{
																					if ($extensia[$qq][1] != "Bmp")
																						{
																						if ($extensia[$qq][1] != "BMP")
																							{
																							if ($extensia[$qq][1] != "swf")
																								{
																								if ($extensia[$qq][1] != "Swf")
																									{
																									if ($extensia[$qq][1] != "SWF")
																										{
																										$verificare = 1;
																										echo "<br>$qq - ".$mesaj[135]." - ".$poza_upl_nume[$qq];
																										echo $mesaj[136];
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
								}
							}
						}				
					}
					// se fac verificarile pentru dimensiunea fisierelor					
					$dim_max_bytes = $mesaj[147]; // 150 kb					
					$dim_max_kb = $dim_max_bytes/1024;
					// echo "<br>".$poza_upl_marime[$qq]." &lt;= ".$dim_max_bytes;
					if ($poza_upl_marime[$qq] >= $dim_max_bytes)
						{
						$verificare = 1;
						echo $mesaj[137]." - ".$qq." - ".$poza_upl_nume[$qq]." - ".$poza_upl_marime[$qq];
						echo $mesaj[138].$dim_max_kb." Kb. (".$dim_max_bytes." bytes)";
						}
					// daca verificarile de mai sus sunt ok se verifica in bd daca nu exista deja pozele cu numele respective
					if ($verificare == 0)
						{
						// se conecteaza la bd si se verifica
						$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
						mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
						$interogare = "SELECT * FROM ".$prefix_tabel_bd."content_images WHERE images_name LIKE BINARY '".$poza_upl_nume[$qq]."'";
						$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
						$total = mysql_num_rows($rezultat);
						if ($total != 0)
							{
							while ($rand = mysql_fetch_array($rezultat))
								{
								if ($rand['images_name'] == $poza_upl_nume[$qq])
									{
									$verificare = 1;
									echo $mesaj[139].$poza_upl_nume[$qq];
									}
								}
							}
						mysql_close($conectare);
						}
					// daca verificarile de mai sus sunt ok se transfera pozele
					if ($verificare == 0)
						{
						// salvare poze pe server
						$upload_1 = "content/images/".$poza_upl_nume[$qq];		
						if (is_uploaded_file($poza_upl_temp_nume[$qq])) 
							{
				     		if (!move_uploaded_file($poza_upl_temp_nume[$qq], $upload_1))
			    	 			{
								$verificare = 1;
				        		echo $mesaj[140];       
			    	 			}
							else
								{
								echo $mesaj[141].$poza_upl_nume[$qq];
								}
  							} 
				  		else 
  							{
							$verificare = 1;
				    		echo $mesaj[142];    
  							}
						// daca transferul este facut ok se salveaza datele in bd
						if ($verificare == 0)
							{
							// se conecteaza la bd si se verifica
							$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
							mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
							$interogare = "INSERT INTO ".$prefix_tabel_bd."content_images (images_name, time, user) 
										VALUES ('".$poza_upl_nume[$qq]."', '".time()."', '".$_SESSION[$prefix_sesiuni.'_user_far']."')";
							// echo $interogare;
							$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
							if ($rezultat == FALSE)
								{
								$verificare = 1;
								echo $mesaj[143];
								}
							mysql_close($conectare);
							}						
						}						
				}
			if ($verificare == 0)
				{
				// daca totul e ok se continua cu afisarea formularului 3
				echo '<form name="form3" method="post" action="admin.php?m=content">
  					<table width="100%"  border="0" cellpadding="0" cellspacing="0">
    				<tr>
      				<td><div align="center">			
					<input name="formular_1" type="hidden" id="formular_1" value="trimis">		
					<input name="formular_2" type="hidden" id="formular_2" value="trimis">        			
					<input name="download" type="hidden" id="download" value="'.$download.'">
					<input name="cate_download" type="hidden" id="cate_download" value="'.$cate_download.'">
					<input name="cod_php" type="hidden" id="cod_php" value="'.$cod_php.'">
        			<input type="submit" name="Submit" value="'.$mesaj[133].'">
					</div></td>
    				</tr>
  					</table>
					</form>';
				}
			if ($verificare == 1)
				{
				// daca verificarile de mai sus nu sunt ok se afiseaza din nou formularul 2
				echo '<form name="form2" method="post" action="admin.php?m=content">
  						<table width="100%"  border="0" cellpadding="0" cellspacing="0">
    					<tr>
      					<td><div align="center">
						<input name="formular_1" type="hidden" id="formular_1" value="trimis">						
        				<input name="poze" type="hidden" id="poze" value="'.$poze.'">
						<input name="cate_poze" type="hidden" id="cate_poze" value="'.$cate_poze.'">
						<input name="download" type="hidden" id="download" value="'.$download.'">
						<input name="cate_download" type="hidden" id="cate_download" value="'.$cate_download.'">
						<input name="cod_php" type="hidden" id="cod_php" value="'.$cod_php.'">
        				<input type="submit" name="Submit" value="'.$mesaj[133].'">
						</div></td>
    					</tr>
  						</table>
					</form>';
				}				
			// echo "<br>ok";			
			}
		if (!isset($_POST['formular_2'])) // se afiseaza formularul 2
			{			
			$afiseaza_f3 = 1;
			$afiseaza_f4 = 1;
			$afiseaza_f5 = 1;
			// preia datele din primul formularul 1
			@$poze = $_POST['poze'];
			@$cate_poze = $_POST['cate_poze'];
			@$download = $_POST['download'];
			@$cate_download = $_POST['cate_download'];
			@$cod_php = $_POST['cod_php'];
			// echo "<br>1= $poze<br>2 = $cate_poze<br>3 = $download<br>4 = $cate_download<br>5 = $cod_php";
			echo '<form action="admin.php?m=content" method="post" enctype="multipart/form-data" name="form_2" id="form_2">
  					<table width="100%"  border="0" cellpadding="0" cellspacing="1">
    				<tr>
      				<td valign="top">
					<table width="100%"  border="0" cellpadding="0" cellspacing="1">';
			// se afiseaza tabelul pentru fiecare poza
			for ($qq=1;$qq<=$cate_poze;$qq++)
				{
				echo '<tr>
	          			<td width="50%"><div align="right">'.$mesaj[144].$qq.': </div></td>
    	      			<td width="50%"><input name="poza_'.$qq.'" type="file" id="poza_'.$qq.'"></td>
        	  		  </tr>';
				}
			// se continua afisarea formularului
			echo '</table>
					</td>
    				</tr>
    				<tr>
      				<td><div align="center">
					<input name="formular_1" type="hidden" id="formular_1" value="trimis">	
					<input name="formular_2" type="hidden" id="formular_2" value="trimis">
					<input name="poze" type="hidden" id="poze" value="'.$poze.'">
					<input name="cate_poze" type="hidden" id="cate_poze" value="'.$cate_poze.'">
					<input name="download" type="hidden" id="download" value="'.$download.'">
					<input name="cate_download" type="hidden" id="cate_download" value="'.$cate_download.'">
					<input name="cod_php" type="hidden" id="cod_php" value="'.$cod_php.'">
        			<input type="submit" name="Submit" value="'.$mesaj[133].'">
      				</div></td>
    				</tr>
  					</table>
					</form>';
			}
		}
	// cazul 5 ======================================================================================
	if ($afiseaza_f3 == 0)
		{
		if (isset($_POST['formular_3']))
			{			
			$afiseaza_f4 = 1;
			$afiseaza_f5 = 1;
			// preia datele din formularul 3			
			@$download = $_POST['download'];
			@$cate_download = $_POST['cate_download'];
			@$cod_php = $_POST['cod_php'];
			$verificare = 0;
			for ($qq=1;$qq<=$cate_download;$qq++)
				{
				$fisier_curent = "fisierul_".$qq;
				@$fisier_upl_temp_nume[$qq] = $_FILES[$fisier_curent]['tmp_name']; 
				@$fisier_upl_nume[$qq] = $_FILES[$fisier_curent]['name']; 
				@$fisier_upl_marime[$qq] = $_FILES[$fisier_curent]['size']; 
				@$fisier_ulp_tip[$qq] = $_FILES[$fisier_curent]['type']; 
				@$fisier_upl_eroare[$qq] = $_FILES[$fisier_curent]['error']; 
				// echo "<br>nume = ".$poza_upl_nume[$qq];
				// se verifica daca au fost introduse toate pozele cerute
				if (empty($fisier_upl_nume[$qq]))
					{
					$verificare = 1;
					echo $mesaj[145].$qq;
					}
				// se verifica daca extensia corespunde - tipul fisierelor
				if (!empty($fisier_upl_nume[$qq]))
					{					
					$extensia[$qq] = explode(".", $fisier_upl_nume[$qq]);
					// echo "<br>$qq - ".$extensia[$qq][1]."<br>";							
					}
					// se fac verificarile pentru dimensiunea fisierelor					
					$dim_max_bytes = $mesaj[146]; // 2 Mb					
					$dim_max_kb = $dim_max_bytes/1024;
					// echo "<br>".$poza_upl_marime[$qq]." &lt;= ".$dim_max_bytes;
					if ($fisier_upl_nume[$qq] <= $dim_max_bytes)
						{
						$verificare = 1;
						echo $mesaj[148]." - ".$qq." - ".$fisier_upl_nume[$qq]." - ".$fisier_upl_marime[$qq];
						echo $mesaj[149].$dim_max_kb." Kb. (".$dim_max_bytes." bytes)";
						}
					// daca verificarile de mai sus sunt ok se verifica in bd daca nu exista deja pozele cu numele respective
					if ($verificare == 0)
						{
						// se conecteaza la bd si se verifica
						$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
						mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
						$interogare = "SELECT * FROM ".$prefix_tabel_bd."content_files WHERE files_name LIKE BINARY '".$fisier_upl_nume[$qq]."'";
						$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
						$total = mysql_num_rows($rezultat);
						if ($total != 0)
							{
							while ($rand = mysql_fetch_array($rezultat))
								{
								if ($rand['files_name'] == $fisier_upl_nume[$qq])
									{
									$verificare = 1;
									echo $mesaj[150].$fisier_upl_nume[$qq];
									}
								}
							}
						mysql_close($conectare);
						}
					// daca verificarile de mai sus sunt ok se transfera pozele
					if ($verificare == 0)
						{
						// salvare poze pe server
						$upload_1 = "content/files/".$fisier_upl_nume[$qq];		
						if (is_uploaded_file($fisier_upl_temp_nume[$qq])) 
							{
				     		if (!move_uploaded_file($fisier_upl_temp_nume[$qq], $upload_1))
			    	 			{
								$verificare = 1;
				        		echo $mesaj[151];       
			    	 			}
							else
								{
								echo $mesaj[152].$fisier_upl_nume[$qq];
								}
  							} 
				  		else 
  							{
							$verificare = 1;
				    		echo $mesaj[153];    
  							}
						// daca transferul este facut ok se salveaza datele in bd
						if ($verificare == 0)
							{
							// se conecteaza la bd si se verifica
							$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
							mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
							$interogare = "INSERT INTO ".$prefix_tabel_bd."content_files (files_name, time, user) 
										VALUES ('".$fisier_upl_nume[$qq]."', '".time()."', '".$_SESSION[$prefix_sesiuni.'_user_far']."')";
							// echo $interogare;
							$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
							if ($rezultat == FALSE)
								{
								$verificare = 1;
								echo $mesaj[154];
								}
							mysql_close($conectare);
							}						
						}						
				}
			if ($verificare == 0)
				{
				// daca totul e ok se continua cu afisarea formularului 4
				echo '<form name="form4" method="post" action="admin.php?m=content">
  					<table width="100%"  border="0" cellpadding="0" cellspacing="0">
    				<tr>
      				<td><div align="center">					
					<input name="formular_1" type="hidden" id="formular_1" value="trimis">	
					<input name="formular_2" type="hidden" id="formular_2" value="trimis">  
					<input name="formular_3" type="hidden" id="formular_3" value="trimis">					
					<input name="cod_php" type="hidden" id="cod_php" value="'.$cod_php.'">
        			<input type="submit" name="Submit" value="'.$mesaj[133].'">
					</div></td>
    				</tr>
  					</table>
					</form>';
				}
			if ($verificare == 1)
				{
				// daca verificarile de mai sus nu sunt ok se afiseaza din nou formularul 3
				echo '<form name="form3" method="post" action="admin.php?m=content">
  						<table width="100%"  border="0" cellpadding="0" cellspacing="0">
    					<tr>
      					<td><div align="center">					
						<input name="formular_1" type="hidden" id="formular_1" value="trimis">					
						<input name="formular_2" type="hidden" id="formular_2" value="trimis">        				
						<input name="download" type="hidden" id="download" value="'.$download.'">
						<input name="cate_download" type="hidden" id="cate_download" value="'.$cate_download.'">
						<input name="cod_php" type="hidden" id="cod_php" value="'.$cod_php.'">
        				<input type="submit" name="Submit" value="'.$mesaj[133].'">
						</div></td>
    					</tr>
  						</table>
					</form>';
				}				
			// echo "<br>ok";
			}
		if (!isset($_POST['formular_3']))
			{
			// identic cu cazul 4 doar se schimba variabilele pentru fisiere			
			$afiseaza_f4 = 1;
			$afiseaza_f5 = 1;
			// preia datele din primul formularul 1			
			@$download = $_POST['download'];
			@$cate_download = $_POST['cate_download'];
			@$cod_php = $_POST['cod_php'];
			// echo "<br>1= $poze<br>2 = $cate_poze<br>3 = $download<br>4 = $cate_download<br>5 = $cod_php";
			echo '<form action="admin.php?m=content" method="post" enctype="multipart/form-data" name="form_3" id="form_3">
  					<table width="100%"  border="0" cellpadding="0" cellspacing="1">
    				<tr>
      				<td valign="top">
					<table width="100%"  border="0" cellpadding="0" cellspacing="1">';
			// se afiseaza tabelul pentru fiecare poza
			for ($qq=1;$qq<=$cate_download;$qq++)
				{
				echo '<tr>
	          			<td width="50%"><div align="right">'.$mesaj[155].$qq.': </div></td>
    	      			<td width="50%"><input name="fisierul_'.$qq.'" type="file" id="fisierul_'.$qq.'"></td>
        	  		  </tr>';
				}
			// se continua afisarea formularului
			echo '</table>
					</td>
    				</tr>
    				<tr>
      				<td><div align="center">					
					<input name="formular_1" type="hidden" id="formular_1" value="trimis">	
					<input name="formular_2" type="hidden" id="formular_2" value="trimis">
					<input name="formular_3" type="hidden" id="formular_3" value="trimis">					
					<input name="download" type="hidden" id="download" value="'.$download.'">
					<input name="cate_download" type="hidden" id="cate_download" value="'.$cate_download.'">
					<input name="cod_php" type="hidden" id="cod_php" value="'.$cod_php.'">
        			<input type="submit" name="Submit" value="'.$mesaj[133].'">
      				</div></td>
    				</tr>
  					</table>
					</form>';
			}
		}	
	// cazul 6 ======================================================================================
	if ($afiseaza_f4 == 0)
		{
		if (isset($_POST['formular_4']))
			{
			$afiseaza_f5 = 1;
			// preia datele din formularul 4				
			@$cod_php = $_POST['cod_php'];
			$verificare = 0;
			@$fisier_upl_temp_nume = $_FILES['fisierul_cod']['tmp_name']; 
			@$fisier_upl_nume = $_FILES['fisierul_cod']['name']; 
			@$fisier_upl_marime = $_FILES['fisierul_cod']['size']; 
			@$fisier_ulp_tip = $_FILES['fisierul_cod']['type']; 
			@$fisier_upl_eroare = $_FILES['fisierul_cod']['error']; 
			/*
			echo "<br>1 - ".$fisier_upl_temp_nume;
			echo "<br>2 - ".$fisier_upl_nume;
			echo "<br>3 - ".$fisier_upl_marime;
			echo "<br>4 - ".$fisier_ulp_tip;
			echo "<br>5 - ".$fisier_upl_eroare;
			*/
			// se verifica daca au fost introduse toate pozele cerute
			if (empty($fisier_upl_nume))
					{
					$verificare = 1;
					echo $mesaj[156];
					}
			// se verifica daca extensia corespunde - tipul fisierelor
			if (!empty($fisier_upl_nume))
				{					
				$extensia = explode(".", $fisier_upl_nume);
				// echo "<br>$qq - ".$extensia[$qq][1]."<br>";							
				if ($extensia[1] != "php")
					{
					if ($extensia[1] != "Php")
						{
						if ($extensia[1] != "PHP")
							{								
							$verificare = 1;
							echo "<br>$qq - ".$mesaj[157]." - ".$fisier_upl_nume;
							echo $mesaj[158];
							}
						}
					}
				}
			// se fac verificarile pentru dimensiunea fisierelor					
			$dim_max_bytes = $mesaj[146]; // 2 Mb					
			$dim_max_kb = $dim_max_bytes/1024;
			// echo "<br>".$poza_upl_marime[$qq]." &lt;= ".$dim_max_bytes;
			if ($fisier_upl_nume <= $dim_max_bytes)
				{
				$verificare = 1;
				echo $mesaj[148]." - ".$fisier_upl_nume." - ".$fisier_upl_marime;
				echo $mesaj[149].$dim_max_kb." Kb. (".$dim_max_bytes." bytes)";
				}
			// daca verificarile de mai sus sunt ok se verifica in bd daca nu exista deja pozele cu numele respective
			if ($verificare == 0)
				{
				// se conecteaza la bd si se verifica
				$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
				mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
				$interogare = "SELECT * FROM ".$prefix_tabel_bd."content_php WHERE files_name LIKE BINARY '".$fisier_upl_nume."'";
				$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
				$total = mysql_num_rows($rezultat);
				if ($total != 0)
					{
					while ($rand = mysql_fetch_array($rezultat))
						{
						if ($rand['files_name'] == $fisier_upl_nume)
							{
							$verificare = 1;
							echo $mesaj[150].$fisier_upl_nume;
							}
						}
					}
				mysql_close($conectare);
				}
			// daca verificarile de mai sus sunt ok se transfera pozele
			if ($verificare == 0)
				{
				// salvare poze pe server
				$upload_1 = "content/".$fisier_upl_nume;		
				if (is_uploaded_file($fisier_upl_temp_nume)) 
					{
			 		if (!move_uploaded_file($fisier_upl_temp_nume, $upload_1))
			    	 	{
						$verificare = 1;
				   		echo $mesaj[151];       
			   			}
					else
						{
						echo $mesaj[152].$fisier_upl_nume;
						}
  					} 
				else 
  					{
					$verificare = 1;
					echo $mesaj[153];    
  					}
				// daca transferul este facut ok se salveaza datele in bd
				if ($verificare == 0)
					{
					// se conecteaza la bd si se verifica
					$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
					mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
					$interogare = "INSERT INTO ".$prefix_tabel_bd."content_php (files_name, time, user) 
							VALUES ('".$fisier_upl_nume."', '".time()."', '".$_SESSION[$prefix_sesiuni.'_user_far']."')";
					// echo $interogare;
					$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
					if ($rezultat == FALSE)
						{
						$verificare = 1;
						echo $mesaj[154];
						}
					mysql_close($conectare);
					}						
				}			
			if ($verificare == 0)
				{
				// daca totul e ok se continua cu redirectarea catre prima cauza
				echo '<form name="form5" method="post" action="admin.php?m=content">
  					<table width="100%"  border="0" cellpadding="0" cellspacing="0">
	    			<tr>
    	  			<td><div align="center">					
        			<input type="submit" name="Submit" value="'.$mesaj[133].'">
					</div></td>
	    			</tr>
  					</table>
					</form>';
				}
			if ($verificare == 1)
				{
				// daca verificarile de mai sus nu sunt ok se afiseaza din nou formularul 4
				echo '<form name="form6" method="post" action="admin.php?m=content">
	  				<table width="100%"  border="0" cellpadding="0" cellspacing="0">
    				<tr>
    	  			<td><div align="center">					
					<input name="formular_1" type="hidden" id="formular_1" value="trimis">					
					<input name="formular_2" type="hidden" id="formular_2" value="trimis">  
					<input name="formular_3" type="hidden" id="formular_3" value="trimis">						
					<input name="cod_php" type="hidden" id="cod_php" value="'.$cod_php.'">
	        		<input type="submit" name="Submit" value="'.$mesaj[133].'">
					</div></td>
	    			</tr>
  					</table>
					</form>';
				}				
			// echo "<br>ok";
			}
		if (!isset($_POST['formular_4']))
			{			
			$afiseaza_f5 = 1;
			// preia datele din primul formularul 1						
			@$cod_php = $_POST['cod_php'];
			// echo "<br>1= $poze<br>2 = $cate_poze<br>3 = $download<br>4 = $cate_download<br>5 = $cod_php";
			echo '<form action="admin.php?m=content" method="post" enctype="multipart/form-data" name="form_4" id="form_4">
  					<table width="100%"  border="0" cellpadding="0" cellspacing="1">
    				<tr>
      				<td valign="top">
					<table width="100%"  border="0" cellpadding="0" cellspacing="1">
					<tr>
	          		<td width="50%"><div align="right">'.$mesaj[159].'</div></td>
    	      		<td width="50%"><input name="fisierul_cod" type="file" id="fisierul_cod"></td>
        	  		</tr></table>
					</td>
    				</tr>
    				<tr>
      				<td><div align="center">					
					<input name="formular_1" type="hidden" id="formular_1" value="trimis">	
					<input name="formular_2" type="hidden" id="formular_2" value="trimis">
					<input name="formular_3" type="hidden" id="formular_3" value="trimis">
					<input name="formular_4" type="hidden" id="formular_4" value="trimis">					
					<input name="cod_php" type="hidden" id="cod_php" value="'.$cod_php.'">
        			<input type="submit" name="Submit" value="'.$mesaj[133].'">
      				</div></td>
    				</tr>
  					</table>
					</form>';
			}
		}
	// cazul 7 ======================================================================================
	if ($afiseaza_f5 == 0)
		{
		if (isset($_POST['formular_5']))
			{
			// preluare valori trimise
			@$text_articol = stripslashes($_POST['text_articol']);
			@$titlul_pagini = $_POST['titlu'];
			@$nume_fisier = $_POST['nume_fisier'];
			@$autor_articol = $_POST['autor'];
			@$email_autor = $_POST['email_autor'];
			@$limbaj_articol = $_POST['limbaj'];
			@$verificare = $_POST['verificare'];
			@$trimitere = $_POST['trimitere'];
			
			// se verifica datele
			$verificare = 0;
			if (empty($text_articol))
				{
				$verificare = 1;
				echo $mesaj[160];
				}
			if (empty($titlul_pagini))
				{
				$verificare = 1;
				echo $mesaj[161];
				}
			if (empty($nume_fisier))
				{
				$verificare = 1;
				echo $mesaj[162];
				}
			// se verifica daca numele pagini nu exista deja in baza de date
			if (!empty($nume_fisier))
				{
				// se conecteaza la bd si se verifica
				$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
				mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
				$interogare = "SELECT name_content, title FROM ".$prefix_tabel_bd."content WHERE name_content = '".$nume_fisier."' AND language_content = '".$limbaj_articol."'";
				$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
				$total = mysql_num_rows($rezultat);
				if ($total != 0)
					{
					while ($rand = mysql_fetch_array($rezultat))
						{
						if ($rand['name_content'] == $nume_fisier)
							{
							$verificare = 1;
							echo $mesaj[163]."<strong>".$rand['title']."</strong>.".$mesaj[164].$nume_fisier;
							}
						}
					}
				mysql_close($conectare);
				}
			if (empty($autor_articol))
				{
				$verificare = 1;
				echo $mesaj[165];
				}
			if (empty($email_autor))
				{
				$verificare = 1;
				echo $mesaj[166];
				}
			if (!empty($email_autor)) // daca e scrisa corect adresa
				{			
				if (eregi("^[a-z0-9\._-]+@+[a-z0-9\._-]+\.+[a-z]{2,3}$",$email_autor) != TRUE) 
				   {
				   echo $mesaj[88];
				   $verificare = 1;
				   }
				}
			if (empty($limbaj_articol))
				{
				$verificare = 1;
				echo $mesaj[167];
				}	
			
			if ($verificare == 0)// daca ce a fost trimis e ok 
				{				
				if (empty($trimitere)) // daca se cere verificare
					{				
					$verificare = 1; // se seteaza 1 ca sa apara din nou formularul					
					echo $mesaj[168];
					echo '<br><div align="center"><strong>'.$titlul_pagini.'</strong></div><br><br>
						'.$text_articol.'<br><br>'.$mesaj[169].'
						<a href="mailto:'.$email_autor.'?Subject='.$titlul_pagini.'">'.$autor_articol.'</a><br><br>';
					}
				if (!empty($trimitere)) // daca se cere trimitere
					{				
					// se conecteaza la bd si se salveaza datele
					$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
					mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
					$nume_fisier = mysql_real_escape_string($nume_fisier,$conectare); 
					$titlul_pagini = mysql_real_escape_string($titlul_pagini,$conectare); 
					$text_articol = mysql_real_escape_string($text_articol,$conectare); 
					$limbaj_articol = mysql_real_escape_string($limbaj_articol,$conectare); 
					$autor_articol = mysql_real_escape_string($autor_articol,$conectare); 
					$interogare = "INSERT INTO ".$prefix_tabel_bd."content (name_content, title, content, language_content, 
							author_content, email_author, user_post, time_post) VALUES 
							('".$nume_fisier."', '".$titlul_pagini."', '".$text_articol."', 
							'".$limbaj_articol."', '".$autor_articol."', '".$email_autor."',
							'".$_SESSION[$prefix_sesiuni.'_user_far']."', '".time()."')";
					// echo $interogare;
					$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
					if ($rezultat == FALSE)
						{
						$verificare = 1;
						echo $mesaj[154];
						}
					else
						{
						echo '<META HTTP-EQUIV = "Refresh" Content = "5; URL ='.$adresa_url.'">'.$mesaj[170];
						}
					mysql_close($conectare);					
					}			
				}
			if ($verificare == 1) // daca ce a fost trimis nu e in regula se afiseaza din nou formularul cu datele trimise
				{
				echo '<form name="form1" method="post" action="admin.php?m=content">
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
				<input name="formular_1" type="hidden" id="formular_1" value="trimis">	
				<input name="formular_2" type="hidden" id="formular_2" value="trimis">
				<input name="formular_3" type="hidden" id="formular_3" value="trimis">
				<input name="formular_4" type="hidden" id="formular_4" value="trimis">
				<input name="formular_5" type="hidden" id="formular_5" value="trimis">
				</form>
				<br>* - '.$mesaj[178].'
				<br>** - '.$mesaj[179].'<br><br>';
				}
			}
		if (!isset($_POST['formular_5']))
			{
			echo '<form name="form1" method="post" action="admin.php?m=content">
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
    				<td width="50%" valign="top"><div align="center">'.$mesaj[171].'*<br>      
        			<textarea name="text_articol" cols="50" rows="10" id="text_articol"></textarea>
					</div></td>
    				<td width="50%" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="3">
      				<tr>
        			<td width="50%"><div align="right">'.$mesaj[172].'</div></td>
        			<td width="50%"><input name="titlu" type="text" id="titlu"></td>
        			</tr>
      				<tr>
        			<td><div align="right">'.$mesaj[173].'**</div></td>
        			<td><input name="nume_fisier" type="text" id="nume_fisier"></td>
        			</tr>
      				<tr>
        			<td><div align="right">'.$mesaj[169].'</div></td>
        			<td><input name="autor" type="text" id="autor"></td>
        			</tr>
      				<tr>
        			<td><div align="right">'.$mesaj[174].'</div></td>
        			<td><input name="email_autor" type="text" id="email_autor"></td>
        			</tr>
      				<tr>
        			<td><div align="right">'.$mesaj[175].'</div></td>
        			<td><input name="limbaj" type="text" id="limbaj"></td>
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
				<input name="formular_1" type="hidden" id="formular_1" value="trimis">	
				<input name="formular_2" type="hidden" id="formular_2" value="trimis">
				<input name="formular_3" type="hidden" id="formular_3" value="trimis">
				<input name="formular_4" type="hidden" id="formular_4" value="trimis">
				<input name="formular_5" type="hidden" id="formular_5" value="trimis">
				</form>
				<br>* - '.$mesaj[178].'
				<br>** - '.$mesaj[179].'<br><br>';
			}
		}
	}
// daca userul nu are acces aici se afiseaza mesajul de eroare ================================================================
else
	{
	echo $mesaj[80];
	echo $mesaj[81].$nivel_acces;
	}

/* Partea de verificare versiune
* Echipa proiectului FAR-PHP a lucrat aproape 1 an pentru a realiza acest proiect.
* Acest proiect este dat sub licenta GNU GPL, gratuit pentru pagini non-profit,
* de aceea dorim sa ne respectati munca si sa nu modificati codul de mai jos 
* in nici o forma. Va garantam ca nu ne intereseaza decat sa ne fie respectata
* munca depusa. Codul de mai jos este folosit pentru verificarea versiunii
* pentru a putea face partea de actualizari automat. In cazul unei schimbari de
* versiune, sau a unei actualizari a versiunii curente,
* primiti automat pe e-mail un anunt. Va este de folos in cazul in care se descopera
* probleme de securitate, pentru a le putea remedia mai usor. 
*/

@$ver = $_GET['ver'];
@$codes = $_GET['codes'];
if (!empty($ver))
	{
	$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
	mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
	$interogare = "SELECT * FROM ".$prefix_tabel_bd."ver";
	$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
	$total = mysql_num_rows($rezultat);
	if ($total != 0)
		{
		while ($rand = mysql_fetch_array($rezultat))
			{
			$data_instalarii = date("d-m-Y H:i", $rand['time_install']);
			$subiect_email = explode("_._", $rand['mesages']);
			$cod = $rand['codes'];
			echo "<br><br>".$mesaj[180].$rand['ver'];
			echo '<br>'.$mesaj[181].'<a href="'.$rand['address'].'" target="_blank">'.$rand['address'].'</a>';
			echo '<br>'.$mesaj[182].$data_instalarii;
			echo '<br>'.$mesaj[183].'<a href="mailto:'.$rand['email'].'?Subject='.$subiect_email[4].'&body=Your messages">'.$rand['name'].'</a>';
			echo '<br>';
			}
		}
	else 
		{
		echo "<br><br>".$mesaj[184]."<br><br>";
		}
	mysql_close($conectare);
	if (!empty($codes))
		{
		if ($codes == $cod)
			{
			echo "<br><br>".$mesaj[185]."<br>";
			echo '<form name="form1" method="post" action="admin.php?m=content&ver=ok&action=change">
  					<div align="center">'.$mesaj[186].'
    				<select name="select">
      				<option value="0">0</option>
      				<option value="1">1</option>
      				<option value="2">2</option>
      				<option value="3">3</option>
    				</select>
					<input name="cod" type="hidden" id="cod" value="'.$codes.'">
					<input type="submit" name="Submit" value="'.$mesaj[187].'">
  					</div>
				</form>';
			}
		else
			{
			echo "<br><br>".$mesaj[188]."<br><br>";
			}
		}
	if (isset($_GET['action']))
		{
		if ($_GET['action'] == "change")
			{
			@$cod_trimis = $_POST['cod'];
			@$selectare = $_POST['select'];
			$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
			mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
			$interogare = "SELECT * FROM ".$prefix_tabel_bd."ver";
			$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
			$total = mysql_num_rows($rezultat);
			if ($total != 0)
				{
				while ($rand = mysql_fetch_array($rezultat))
					{
					$cod = $rand['codes'];
					}
				if ($cod == $cod_trimis)
					{
					$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
					mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
					$interogare = "UPDATE ".$prefix_tabel_bd."ver SET status='".$selectare."'";
					// echo "<br>".$interogare;
					$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
					if ($rezultat == TRUE)
						{
						echo "<br><br>".$mesaj[189]."<br>";
						}
					else
						{
						echo "<br><br>".$mesaj[190]."<br>";
						}
					}
				}
			else 
				{
				echo "<br><br>".$mesaj[184]."<br><br>";
				}
			mysql_close($conectare);
			}
		}
	}
else
	{
	$verificare = 0;
	$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
	mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
	$interogare = "SELECT * FROM ".$prefix_tabel_bd."ver LIMIT 1";
	$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;
	@$total = mysql_num_rows($rezultat);	
	if ($total != 0)
		{		
		while ($rand = mysql_fetch_array($rezultat))
			{
			$data_instalarii = date("d-m-Y H:i", $rand['time_install']);
			$subiect_email = explode("_._", $rand['mesages']);
			$cod_versiune = $rand['codes'];
			$versiune_far = $rand['ver'];
			$data_acum = date("d-m-Y H:i", time());
			$adresa_pagina = $rand['address'];
			$email_admin = $rand['email'];
			$nume_admin = $rand['name'];
			$stare_instalare = $rand['status'];
			$ip_acum = $_SERVER['REMOTE_ADDR'];
			// $host_acum = $_SERVER['REMOTE_HOST'];
			// $host_acum = gethostbyaddr($ip_acum); // asta incetineste timpul de executie cu 19 secunde
			$host_acum = "scos temporar";
			$browser_acum = $_SERVER['HTTP_USER_AGENT'];
			$limbaj_browser = $rand['language'];
			}
		mysql_close($conectare);		
		}
	else
		{
		$data_instalarii = $mesaj[191];
		$subiect_email[3] = "contact@far-php.ro";
		$cod_versiune = $mesaj[192];
		$versiune_far = $mesaj[193];
		$data_acum = date("d-m-Y H:i", time());
		$adresa_pagina = $adresa_url;		
		@$nume_admin = $_SESSION[$prefix_sesiuni.'_nume_far'];
		if (empty($user_bd))
			{
			$nume_admin = $mesaj[194];
			}		
		$stare_instalare = 2;
		$ip_acum = $_SERVER['REMOTE_ADDR'];
		$host_acum = $_SERVER['REMOTE_HOST'];
		$browser_acum = $_SERVER['HTTP_USER_AGENT'];
		$limbaj_browser = $limbaj_primar;
		
		$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
		mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
		$interogare = "DROP TABLE IF EXISTS ".$prefix_tabel_bd."ver";
		// echo "<br>".$interogare;
		$rezultat = mysql_query($interogare, $conectare) OR $verificare = 2;
		if ($rezultat == TRUE)
			{		
			$interogare = "CREATE TABLE ".$prefix_tabel_bd."ver 
				(`ver` varchar(15) NOT NULL default '',
				`time_install` varchar(11) NOT NULL default '',
  				`address` varchar(100) NOT NULL default '',
				`name` varchar(35) NOT NULL default '',
  				`email` varchar(35) NOT NULL default '',
  				`language` varchar(15) NOT NULL default '',
  				`status` int(2) NOT NULL default '0',
  				`mesages` text NOT NULL,
  				`codes` varchar(10) NOT NULL default '')";
			// echo "<br>".$interogare;
			$rezultat = mysql_query($interogare, $conectare) OR $verificare = 3;
			if ($rezultat == TRUE)
				{				
				$timp_acum = time();				
				$cod_nou = gen_pass(10,FALSE,null);	
				$data_copy = date("Y",time());
				$link_far_en = "http://www.far-php.ro";
				$mesaj_bd = "The logos and trademarks used on this site are the property of their respective owners
					<br>We are not responsible for comments posted by our users, as they are the property of the poster
					<br>Web site engine's code is copyright &copy; 2004 - ".$data_copy." by <a href='".$link_far_en."' target='_blank'>FAR-PHP</a>
					<br>Released under the GNU GPL License.
					<br>
					_._
					This pages used code from <a href='".$link_far_en."' target='_blank'>FAR-PHP</a> project, but it's not consideration
					of any legals term, because do not respect original copyright.<br>Please send e-mail to 
					<a href='mailto:".$subiect_email[3]."?Subject=".$subiect_email[4]."&body=Your messages'>FAR-PHP</a> team,
					and tell as about this pages.
					_._
					".$chestii_copyright."
					_._
					contact@far-php.ro
					_._
					A fost instalat FAR-PHP";
				@$user_bd = $_SESSION[$prefix_sesiuni.'_nume_far'];
				if (empty($user_bd))
					{
					$user_bd = $mesaj[194];
					}
				$adresa_url = mysql_real_escape_string($adresa_url, $conectare);
				$email_admin = mysql_real_escape_string($email_admin, $conectare);
				$limbaj_primar = mysql_real_escape_string($limbaj_primar, $conectare);				
				$user_bd = mysql_real_escape_string($user_bd, $conectare);
				$mesaj_bd = mysql_real_escape_string($mesaj_bd, $conectare);
				
				$interogare = "INSERT INTO ".$prefix_tabel_bd."ver (ver, time_install, 
				address, name, email, language, 
				status, mesages, codes) VALUES 
				('".$versiune_far."'', '".$timp_acum."', '".$adresa_url."', 
				'".$user_bd."', '".$email_admin."', '".$limbaj_primar."', 0, 
				'".$mesaj_bd."', '".$cod_nou."')";
				// echo "<br>".$interogare;
				$rezultat = mysql_query($interogare, $conectare) OR $verificare = 4;
				if ($rezultat == TRUE)
					{
					$verificare = 5;
					}
				else
					{
					echo $mesaj[4];
					}
				}
			else
				{
				echo $mesaj[4];
				}
			}	
		else
			{
			echo $mesaj[4];
			}	
		}
	// echo "<strong>ok pana aici</strong>";
	if ($verificare == 0)
		{		
		$mesaj_mail = $mesaj[195];		
		}
	if ($verificare == 1)
		{		
		$mesaj_mail = $mesaj[196];
		}
	if ($verificare == 2)
		{		
		$mesaj_mail = $mesaj[197];
		}
	if ($verificare == 3)
		{		
		$mesaj_mail = $mesaj[198];
		}
	if ($verificare == 4)
		{		
		$mesaj_mail = $mesaj[199];
		}
	if ($verificare == 5)
		{		
		$mesaj_mail = $mesaj[200];
		}
	$mail_adres = trim($subiect_email[3]);
	$header_mail = "MIME-Version: 1.0\r\nContent-type: text/html; charset=iso-8859-2\r\n";		
	$detalii_mail = "<br>
		<br>Versiune program: ".$versiune_far."
		<br>Data instalarii: ".$data_instalarii."
		<br>Adresa: ".$adresa_pagina."
		<br>Nume admin: ".$nume_admin."
		<br>E-mail admin: ".$email_admin."
		<br>Stare program: ".$stare_instalare."
		<br>Verificare: ".$cod_versiune."
		<br>Adresa IP: ".$ip_acum."
		<br>Gazda server: ".$host_acum."
		<br>Browser: ".$browser_acum."
		<br>Limbaj: ".$limbaj_browser."
		<br>Data mesajului: ".$data_acum."
		<br><br>";
	//echo "<br>Adresa: ".$mail_adres."<br>Subiect: ".$mesaj_mail."<br>Continut: ".$detalii_mail."<br>Headere: ".$header_mail;
	// echo "test";
	if ($stare_instalare == 0)
		{
		//echo "<br>ok";
		@mail($mail_adres, $mesaj_mail, $detalii_mail, $header_mail);
		}
	}
	
?>