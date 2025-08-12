<?php
/* =====================================================================
*	Pagina login_new.php (parte din modulul de login)
*	Creat de Birkoff pentru proiectul FAR-PHP
*	Versiune: 1.0
*	Copyright: (C) 2004 - 2005 The FAR-PHP Group
*	E-mail: contact@far-php.ro
*	Inceput la: 28-12-2004
*	Ultima modificare: 07-06-2005
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
* -----------------------------------------------------------------------
*
*	5 actiuni posibile 
*	new_user = creaza un user nou
*	new_pass = schimba parola la un user deja creat
*	new_pass2 = activeaza parola ceruta la new_pass
*	new_right = pentru administrator schimba starea la un user deja creat
*	new_del = pentru administrator sterge un user
*	dec = logout
*
*	valori trimise 
*	actiune cu post = "da" => se verifica daca a fost trimis formularul cu datele cerute pentru user nou
*	stare cu post = "da" => se verifica daca a fost trimis formularul cu datele cerute pentru stare noua
*	sterge cu post = "da" => se verifica daca a fost trimis formularul cu datele cerute pentru stergere user
*	schimba cu post = "da" => se verifica daca a fost trimis formularul cu datele cerute pentru schimbarea parolei
*
* ========================================================= 
 * ========================================================================
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 * ======================================================================== */
if (isset($_GET['action']))
	{
	$actiune = $_GET['action']; // se preia tipul de actiune cerut
	}
if (!isset($_GET['action']))
	{
	$actiune = '';
	}
if (empty($actiune)) // daca nu s-a trimis tipul de actiune se redirecteaza la pagina principala
	{
	echo '<META HTTP-EQUIV = "Refresh" Content = "5; URL ='.$adresa_url.'">'.$mesaj[82];
	}
// daca tipul de actiune este user nou se afiseaza formularul ==================================================
if ($actiune == "new_user") 
	{
	if (!isset($_POST['actiune'])) // daca nu a fost trimis deja formularul se afiseaza formularul
		{
		echo '<form name="form_new_user" method="post" action="admin.php?m=login_new&action=new_user">
			<table width="100%"  border="0" cellpadding="0" cellspacing="0">
			  <tr>
			    <td valign="top"><table width="100%"  border="0" cellpadding="0" cellspacing="3">
			      <tr>
			        <td width="50%"><div align="right">'.$mesaj[36].'</div></td>
			        <td width="50%"><input name="user" type="text" id="user" onclick="this.value=\'\';" value="'.$mesaj[84].'"></td>
			        </tr>
			    </table></td>
			  </tr>
			  <tr>
			    <td valign="top"><table width="100%"  border="0" cellpadding="0" cellspacing="3">
			      <tr>
			        <td width="50%"><div align="right">'.$mesaj[37].'</div></td>
			        <td width="50%"><input name="parola" type="password" id="parola"></td>
			      </tr>
			    </table></td>
			  </tr>
			  <tr>
			    <td valign="top"><table width="100%"  border="0" cellpadding="0" cellspacing="3">
			      <tr>
			        <td width="50%"><div align="right">'.$mesaj[83].'</div></td>
			        <td width="50%"><input name="email" type="text" id="email" onclick="this.value=\'\';" value="'.$mesaj[86].'"></td>
			      </tr>
			    </table></td>
			  </tr>
			  <tr>
			    <td valign="top"><div align="center">
			      <input name="actiune" type="hidden" id="actiune" value="da">
			      <input type="submit" name="Submit" value="'.$mesaj[39].'">
			    </div></td>
			  </tr>
			</table>
			</form>';
		}
	else // daca formularul a fost trimis se preiau datele 
		{
		@$user = $_POST['user'];
		@$parola = $_POST['parola'];
		@$email = $_POST['email'];
		$verificare = 0;
		if (empty($user)) // daca nu e scris user
			{
			echo $mesaj[41];
			$verificare = 1;
			}
		if ($user == $mesaj[84]) // daca e mesajul default
			{
			echo $mesaj[41];
			$verificare = 1;
			}
		if (!empty($user)) // daca are mai mult de 3 caractere
			{
			if ($user != $mesaj[84])
				{
				if (strlen($user) <= 2)
					{
					if (strlen($user) == 1)
						{
						$caracter = $mesaj[44];
						}
					else
						{
						$caracter = $mesaj[43];
						}
					echo $mesaj[45].strlen($user)." ".$caracter;
					echo $mesaj[46];
					$verificare = 1;
					}
				}
			}
		if (empty($parola)) // daca nu e scrisa parola
			{
			echo $mesaj[42];
			$verificare = 1;
			}
		if ($parola == $mesaj[85]) // daca e mesajul default
			{
			echo $mesaj[42];
			$verificare = 1;
			}
		if (!empty($parola)) // daca are mai mult de 4 caractere
			{
			if ($parola != $mesaj[85])
				{
				if (strlen($parola) <= 3)
					{
					if (strlen($parola) == 1)
						{
						$caracter = $mesaj[44];
						}
					else
						{
						$caracter = $mesaj[43];
						}
					echo $mesaj[47].strlen($parola)." ".$caracter;
					echo $mesaj[48];
					$verificare = 1;
					}
				}
			}
		if (empty($email)) // daca nu e scris e-mail-ul
			{
			echo $mesaj[87];
			$verificare = 1;
			}
		if ($email == $mesaj[86]) // daca e mesajul default
			{
			echo $mesaj[87];
			$verificare = 1;
			}
		if (!empty($email)) // daca e scrisa corect adresa
			{
			if ($email != $mesaj[86])
				{
				if (eregi("^[a-z0-9\._-]+@+[a-z0-9\._-]+\.+[a-z]{2,3}$",$email) != TRUE) 
				   {
				   echo $mesaj[88];
				   $verificare = 1;
				   }
				}
			}
		if ($verificare == 0) // daca verificarile de mai sus sunt corecte se merge mai departe...
			{
			// se verifica in bd daca userul sau parola exista
			$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
			mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
			$interogare = "SELECT * FROM ".$prefix_tabel_bd."user";
			$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
			$total = mysql_num_rows($rezultat);			
			while ($rand = mysql_fetch_array($rezultat))
				{
				if ($user == $rand['user']) // se verifica daca userul ales nu exista deja in bd
					{
					echo $mesaj[89];
					$verificare = 1;
					}
				if ($email == $rand['email']) // se verifica daca e-mail-ul introdus nu exista deja in bd
					{
					echo $mesaj[90];
					$verificare = 1;
					}
				}
			mysql_close($conectare);
			if ($verificare == 0) // daca verificarile in bd sunt ok se salveaza noul user in bd
				{
				$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]);
				mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]);
				// se scot caracterele speciale
				$user = mysql_real_escape_string($user,$conectare); 
				// se cripteaza parola daca asa e setat in config.php
				if ($parola_criptata == "da")
					{
					$parola_cr = md5($parola);
					}
				else
					{
					$parola_cr = $parola;
					}
				$interogare = "INSERT INTO ".$prefix_tabel_bd."user (user, parola, stare, email) 
						VALUES ('".$user."', '".$parola_cr."', '5', '".$email."')";
				// echo $interogare;
				$rezultat = mysql_query($interogare, $conectare) OR die($mesaj[4]);
				if ($rezultat == TRUE) // daca a fost salvat in bd afiseaza ok si redirecteaza
					{
					// adaugare la ver 1.0 alfa 2
					$fisier = "modules/adduserphpbb.php";
					if (file_exists($fisier))
						{
						$actiune_phpbb = "add";
						include($fisier);						
						}
					// aici ar trebui trimise datele si pe mail la user
					// echo "ok";
					$catre = "$user <$email>";
					$subiect = $mesaj[93].$adresa_url;
					$mesaj_mail = $mesaj[93].$adresa_url."<br>".$mesaj[36].$user."<br>".$mesaj[37].$parola."<br>".$mesaj[83].$email;
					$header = "From: $email_admin\r\n"."Reply-To: $email_moderator\r\n"."MIME-Version: 1.0\r\nContent-type: text/html; charset=iso-8859-2\r\n";
					@mail($catre, $subiect, $mesaj_mail, $header);
					// echo $catre."<br>".$subiect."<br>".$mesaj."<br>".$header;					
					echo '<META HTTP-EQUIV = "Refresh" Content = "5; URL ='.$adresa_url.'">'.$mesaj[91];
					}
				else // daca a dat eroare la salvarea in bd afiseaza eroarea si redirecteaza
					{
					echo '<META HTTP-EQUIV = "Refresh" Content = "5; URL ='.$adresa_url.'">'.$mesaj[92];
					}
				mysql_close($conectare);
				}
			}
		}
	}
// daca tipul de actiune este noua parola se afiseaza formularul pentru scrimbare ===================================================
if ($actiune == "new_pass") 
	{
	if (!isset($_POST['schimba'])) // daca nu e trimis deja formularul il afiseaza
		{
		if (isset($_SESSION[$prefix_sesiuni.'_user_far']))
			{
			$nume_user = $_SESSION[$prefix_sesiuni.'_user_far'];
			if (isset($_SESSION[$prefix_sesiuni.'_email_far']))
				{
				$email_user = $_SESSION[$prefix_sesiuni.'_email_far'];
				}
			if (!isset($_SESSION[$prefix_sesiuni.'_email_far']))
				{
				if (isset($_COOKIE[$prefix_sesiuni.'_far']))
					{
					$prelucrare_cooke = stripslashes($_COOKIE[$prefix_sesiuni.'_far']);	
					$valoare_cookie = unserialize($prelucrare_cooke);
					if (isset($valoare_cookie['email_far']))
						{
						$_SESSION[$prefix_sesiuni.'_email_far'] = $valoare_cookie['email_far'];
						$email_user = $_SESSION[$prefix_sesiuni.'_email_far'];
						}
					if (!isset($valoare_cookie['email_far']))
						{
						$nume_user = "";
						$email_user = "";
						}	
					}
				if (!isset($_COOKIE[$prefix_sesiuni.'_far']))
					{
					$nume_user = "";
					$email_user = "";
					}
				}
			}
		else
			{
			$nume_user = "";
			$email_user = "";
			}
		echo '<form name="form_pass" method="post" action="admin.php?m=login_new&action=new_pass">
			<table width="100%"  border="0" cellpadding="0" cellspacing="0">
			  <tr>
			    <td valign="top"><table width="100%"  border="0" cellpadding="1" cellspacing="3">
			      <tr>
			        <td width="50%"><div align="right">'.$mesaj[36].'</div></td>
			        <td width="50%"><input name="user" type="text" id="user" value="'.$nume_user.'"></td>
			        </tr>
			    </table></td>
			  </tr>
			  <tr>
			    <td valign="top"><table width="100%"  border="0" cellpadding="1" cellspacing="3">
			      <tr>
			        <td width="25%"><div align="right">'.$mesaj[94].'</div></td>
			        <td width="25%"><input name="parola" type="password" id="parola"></td>
			        <td width="25%"><div align="right">'.$mesaj[95].'</div></td>
			        <td width="25%"><input name="genereaza" type="checkbox" id="genereaza" value="da"></td>
			      </tr>
			    </table></td>
			  </tr>
			  <tr>
			    <td valign="top"><table width="100%"  border="0" cellpadding="1" cellspacing="3">
			      <tr>
			        <td width="50%"><div align="right">'.$mesaj[83].'</div></td>
	        		<td width="50%"><input name="email" type="text" id="email" value="'.$email_user.'"></td>
			      </tr>
			    </table></td>
			  </tr>
			  <tr>
			    <td valign="top"><div align="center">
			      <input name="schimba" type="hidden" id="schimba" value="da">
			      <input type="submit" name="Submit" value="'.$mesaj[96].'">
			</div></td>
			  </tr>
			</table>
			</form>';
		}
	if (isset($_POST['schimba'])) // daca formularul a fost trimis prelucreaza datele
		{
		@$user = $_POST['user'];
		@$parola = $_POST['parola'];
		@$genereaza = $_POST['genereaza'];
		@$email = $_POST['email'];		
		// verificari
		$verificare = 0;
		if (empty($user))
			{
			$verificare = 1;
			echo $mesaj[41];
			}
		if (empty($parola))
			{
			if (empty($genereaza))
				{
				$verificare = 1;
				echo $mesaj[42];
				}
			}
		if (empty($email))
			{
			$verificare = 1;
			echo $mesaj[87];
			}
		if (!empty($user))
			{
			if(strlen($user) <= 2) // daca numele user-ului e mai mic sau egal de 2 caractere
				{
				$caracter = $mesaj[43];
				if(strlen($user) == 1)
					{
					$caracter = $mesaj[44];
					}			
				echo $mesaj[45].strlen($user)." ".$caracter;
				echo $mesaj[46];
				$verificare = 1;
				}
			}
		if (!empty($parola))
			{
			if(strlen($parola) <= 3) // daca parola e mai mica sau egala de 3 caractere
				{
				$caracter = $mesaj[43];
				if(strlen($parola) == 1)
					{
					$caracter = $mesaj[44];
					}			
				echo $mesaj[47].strlen($parola)." ".$caracter;
				echo $mesaj[48];
				$verificare = 1;
				}
			else
				{
				$parola_noua = $parola;
				}
			}
		if (!empty($email)) // daca e scrisa corect adresa
			{
			if ($email != $mesaj[86])
				{
				if (eregi("^[a-z0-9\._-]+@+[a-z0-9\._-]+\.+[a-z]{2,3}$",$email) != TRUE) 
				   {
				   echo $mesaj[88];
				   $verificare = 1;
				   }
				}
			}
		// se verifica daca se cere parola generata
		if ($genereaza == "da")
			{
			$parola_noua = gen_pass(6,FALSE,L);	
			}
		// echo "ok pana aici";
		if ($verificare == 0) // daca verificarea e ok
			{
			$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]." 1");
			mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]." 1");
			// se verifica daca userul si adresa de mail corespund cu cele din bd
			$user = mysql_real_escape_string($user, $conectare);
			$email = mysql_real_escape_string($email, $conectare);
			$comanda_sql = "SELECT * FROM ".$prefix_tabel_bd."user WHERE user LIKE '".$user."' AND email LIKE '".$email."'";
			// echo $comanda_sql;
			$interogare_sql = mysql_query($comanda_sql) OR die($mesaj[9]);
			$total = mysql_num_rows($interogare_sql);
			mysql_close($conectare);			
			if ($total == 0)
				{				
				echo $mesaj[97];
				}					
			else
				{				
				// se salveaza temporar in bd datele si se trimite mail
				$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]." 2");
				mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]." 2");
				$timp_acum = time();
				$ip_user = $_SERVER['REMOTE_ADDR'];
				$comanda_sql = "INSERT INTO ".$prefix_tabel_bd."temp (user, parola, email, timp, ip) 
						VALUES ('".$user."', '".$parola_noua."', '".$email."', '".$timp_acum."', '".$ip_user."')";
				// echo $comanda_sql;
				$interogare_sql = mysql_query($comanda_sql) OR die($mesaj[9]);
				mysql_close($conectare);				
				if ($interogare_sql == FALSE)
					{					
					echo $mesaj[92];
					}			
				else
					{
					// se verifica ce id are salvarea din bd pentru crearea link-ului
					$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]." 2");
					mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]." 2");
					$comanda_sql = "SELECT * FROM ".$prefix_tabel_bd."temp WHERE user LIKE '".$user."' AND email LIKE '".$email."' 
							AND parola LIKE '".$parola_noua."' AND timp LIKE '".$timp_acum."' AND ip LIKE '".$ip_user."'";
					// echo $comanda_sql;
					$interogare_sql = mysql_query($comanda_sql) OR die($mesaj[9]);
					$total = mysql_num_rows($interogare_sql);			
					if ($total == 0)
						{
						echo $mesaj[92]." 2";
						}
					else
						{
						while ($rand = mysql_fetch_array($interogare_sql))
							{
							$id_mail = $rand['id'];
							$user_mail = $rand['user'];
							$parola_mail = $rand['parola'];
							$email_mail = $rand['email'];
							$timp_mail = $rand['timp'];
							$ip_mail = $rand['ip'];
							}
						}
					mysql_close($conectare);
					// se creaza linkul pentru activare
					$link_mail = '<br><a href="'.$adresa_url.'admin.php?m=login_new&action=new_pass2&id='.$id_mail.'&user='.$user_mail.'&time='.$timp_mail.'&ip='.$ip_mail.'">'.$mesaj[104].'</a><br>';
					$data_ora_mail = date("d-m-Y H:i:s", $timp_mail);
					// echo $link_mail;
					// se trimite mail
					$catre = "$user <$email>";
					$subiect = $mesaj[93].$adresa_url;					
					$mesaj = $mesaj[99].$link_mail."<br>".$mesaj[36].$user."<br>".$mesaj[37].$parola_noua."<br>".$mesaj[83].$email."<br>".$mesaj[105].$ip_mail."<br>".$mesaj[106].$data_ora_mail;
					$header = "From: $email_admin\r\n"."Reply-To: $email_moderator\r\n"."MIME-Version: 1.0\r\nContent-type: text/html; charset=iso-8859-2\r\n";
					// echo "<br>catre= ".$catre."<br>subiect = ".$subiect."<br>mesaj = ".$mesaj."<br>header = ".$header;
					@mail ($catre, $subiect, $mesaj, $header);					
					// daca totul e ok pana aici se afiseaza mesajul de ok
					echo '<META HTTP-EQUIV = "Refresh" Content = "5; URL ='.$adresa_url.'">'.$mesaj[98];
					}				
				}
			}
		else // daca verificarea nu e ok se afiseaza din nou formularul
			{
			if (isset($_SESSION[$prefix_sesiuni.'_user_far']))
				{
				$nume_user = $_SESSION[$prefix_sesiuni.'_user_far'];
				$email_user = $_SESSION[$prefix_sesiuni.'_email_far'];
				}
			else
				{
				$nume_user == "";
				$email_user = "";
				}
			echo '<form name="form_pass" method="post" action="admin.php?m=login_new&action=new_pass">
				<table width="100%"  border="0" cellpadding="0" cellspacing="0">
				  <tr>
				    <td valign="top"><table width="100%"  border="0" cellpadding="1" cellspacing="3">
				      <tr>
				        <td width="50%"><div align="right">'.$mesaj[36].'</div></td>
				        <td width="50%"><input name="user" type="text" id="user" value="'.$nume_user.'"></td>
				        </tr>
			    	</table></td>
				  </tr>
				  <tr>
				    <td valign="top"><table width="100%"  border="0" cellpadding="1" cellspacing="3">
				      <tr>
				        <td width="25%"><div align="right">'.$mesaj[94].'</div></td>
				        <td width="25%"><input name="parola" type="password" id="parola"></td>
				        <td width="25%"><div align="right">'.$mesaj[95].'</div></td>
				        <td width="25%"><input name="genereaza" type="checkbox" id="genereaza" value="da"></td>
				      </tr>
				    </table></td>
				  </tr>
				  <tr>
				    <td valign="top"><table width="100%"  border="0" cellpadding="1" cellspacing="3">
				      <tr>
				        <td width="50%"><div align="right">'.$mesaj[83].'</div></td>
		        		<td width="50%"><input name="email" type="text" id="email" value="'.$email_user.'"></td>
				      </tr>
				    </table></td>
				  </tr>
				  <tr>
				    <td valign="top"><div align="center">
				      <input name="schimba" type="hidden" id="schimba" value="da">
				      <input type="submit" name="Submit" value="'.$mesaj[96].'">
				</div></td>
				  </tr>
				</table>
				</form>';
			}
		}
	}
// daca tipul de actiune e activarea parolei trimise cu new_pass
if ($actiune == "new_pass2")
	{
	// se preiau datele trimise cu get
	@$id = $_GET['id'];
	@$user = $_GET['user'];
	@$timp = $_GET['time'];
	@$ip = $_GET['ip'];
	// echo "<br>id = $id<br>user = $user<br>timp = $timp<br>ip = $ip";
	// verificare
	$verificare = 0;
	if (empty($id))
		{
		$verificare = 1;		
		}
	if (empty($user))
		{
		$verificare = 1;
		}
	if (empty($timp))
		{
		$verificare = 1;
		}
	if (empty($ip))
		{
		$verificare = 1;
		}
	if ($verificare == 1)
		{
		echo $mesaj[107];
		}
	else
		{
		// se verifica acum daca datele trimise corespund cu cele din bd
		$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]." 1");
		mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]." 1");
		$comanda_sql = "SELECT * FROM ".$prefix_tabel_bd."temp WHERE id LIKE '".$id."'";
		// echo $comanda_sql;
		$interogare_sql = mysql_query($comanda_sql) OR die($mesaj[9]." 1");
		$total = mysql_num_rows($interogare_sql);			
		if ($total == 0)
			{
			echo $mesaj[107]." 1";
			}
		else
			{
			while ($rand = mysql_fetch_array($interogare_sql))
				{
				$id_mail = $rand['id'];
				$user_mail = $rand['user'];
				$parola_mail = $rand['parola'];
				$email_mail = $rand['email'];
				$timp_mail = $rand['timp'];
				$ip_mail = $rand['ip'];
				}
			}
		mysql_close($conectare);
		// se verifica daca datele extrase din bd corespund cu cele primite prin get
		if ($total != 0)
			{
			$verificare = 0;
			if ($id_mail != $id)
				{
				$verificare = 1;
				}
			if ($user_mail != $user)
				{
				$verificare = 1;
				}
			if ($timp_mail != $timp)
				{
				$verificare = 1;
				}
			if ($ip_mail != $ip)
				{
				$verificare = 1;
				}
			if ($verificare == 1)
				{
				echo $mesaj[107]." 2";
				}
			else
				{
				// totul e ok pana aici se trece la schimbarea parolei
				$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]." 1");
				mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]." 1");
				// daca setarile cer parola criptata se cripteaza
				if ($parola_criptata == "da")
					{
					$parola_finala = md5($parola_mail);
					}
				else
					{
					$parola_finala = $parola_mail;
					}
				$comanda_sql = "UPDATE ".$prefix_tabel_bd."user SET parola='".$parola_finala."' WHERE user LIKE '".$user_mail."' AND email LIKE '".$email_mail."'";
				// echo $comanda_sql;
				$interogare_sql = mysql_query($comanda_sql) OR die($mesaj[9]." 1");
				if ($interogare_sql == FALSE)
					{
					echo $mesaj[107]." 1";
					}
				else
					{
					// dupa ce a fost schimbata parola se sterge activarea din bd temporara
					$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]." 2");
					mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]." 2");
					$comanda_sql = "DELETE FROM ".$prefix_tabel_bd."temp WHERE id LIKE '".$id."'";
					// echo $comanda_sql;
					$interogare_sql = mysql_query($comanda_sql) OR die($mesaj[9]." 2");
					if ($interogare_sql == FALSE)
						{
						echo $mesaj[108];
						}
					else
						{
						mysql_close($conectare);				
						// daca totul e ok pana aici se afiseaza mesajul de ok si se redirecteaza
						echo '<META HTTP-EQUIV = "Refresh" Content = "5; URL ='.$adresa_url.'">'.$mesaj[109];
						}
					}				
				}
			}
		}
	}
// daca tipul de actiune este schimbarea drepturilor unui user... ==========================================================
if ($actiune == "new_right")
	{	
	// se verifica daca userul are drept de acces 1,2,3 sau 4
	if (isset($_SESSION[$prefix_sesiuni.'_rights_far']))
		{
		$drepturi_user = $_SESSION[$prefix_sesiuni.'_rights_far'];
		$verificare = 0;
		if ($drepturi_user != 1)
			{
			if ($drepturi_user != 2)
				{
				if ($drepturi_user != 3)
					{
					if ($drepturi_user != 4)
						{
						$verificare = 1;
						echo $mesaj[80];
						echo $mesaj[81].$drepturi_user;
						}
					}
				}
			}
		if ($verificare == 0)
			{
			// daca are drept de acces se verifica daca formularul nu a fost deja trimis
			if (!isset($_POST['stare'])) // se afiseaza formularul daca nu a fost completat pana acum
				{
				echo '<form name="form_drepturi" method="post" action="admin.php?m=login_new&action=new_right">
					<table width="100%"  border="0" cellpadding="0" cellspacing="0">
				  <tr>
				    <td valign="top"><table width="100%"  border="0" cellpadding="0" cellspacing="3">
				      <tr>
				        <td width="25%"><div align="right">'.$mesaj[112].'</div></td>
					        <td width="25%"><input name="user" type="text" id="user">
				        </td>
				        </tr>
				    </table></td>
				  </tr>
				  <tr>
				    <td valign="top"><table width="100%"  border="0" cellpadding="0" cellspacing="3">
				      <tr>
			        <td width="50%"><div align="right">'.$mesaj[113].'</div></td>
			        <td width="50%"><select name="drepturi" id="drepturi">';
					// se afiseaza doar drepturile care sunt egale sau mai mici decat ale userului curent
					// mai trebuie lucrata la chestia asta ca se poate face si mai elegant da acum mi-e lene
					if ($_SESSION[$prefix_sesiuni.'_rights_far'] == 1)
						{
						echo '<option value="5" selected>'.$mesaj[114].'</option>
					          <option value="4">'.$mesaj[115].'</option>
					          <option value="3">'.$mesaj[116].'</option>
			        		  <option value="2">'.$mesaj[117].'</option>
					          <option value="1">'.$mesaj[118].'</option>';
						}
					if ($_SESSION[$prefix_sesiuni.'_rights_far'] == 2)
						{
						echo '<option value="5" selected>'.$mesaj[114].'</option>
					          <option value="4">'.$mesaj[115].'</option>
					          <option value="3">'.$mesaj[116].'</option>
			        		  <option value="2">'.$mesaj[117].'</option>';
						}
					if ($_SESSION[$prefix_sesiuni.'_rights_far'] == 3)
						{
						echo '<option value="5" selected>'.$mesaj[114].'</option>
					          <option value="4">'.$mesaj[115].'</option>
					          <option value="3">'.$mesaj[116].'</option>';
						}
					if ($_SESSION[$prefix_sesiuni.'_rights_far'] == 4)
						{
						echo '<option value="5" selected>'.$mesaj[114].'</option>
					          <option value="4">'.$mesaj[115].'</option>';
						}
					if ($_SESSION[$prefix_sesiuni.'_rights_far'] == 5)
						{
						echo '<option value="5" selected>'.$mesaj[114].'</option>';
						}
					// se continua cu afisarea formularului
					echo '</select></td>
			        </tr>
			    </table></td>
			  </tr>
			  <tr>
			    <td valign="top"><div align="center">
			      <input name="stare" type="hidden" id="stare" value="da">
			      <input type="submit" name="Submit" value="'.$mesaj[28].'">
					</div></td>
					  </tr>
					</table>
					</form>';
				}
			else // daca formularul a fost trimis se verifica datele trimise
				{
				@$user = $_POST['user'];
				@$drepturi = $_POST['drepturi'];
				// echo "<br>user = $user<br>drept = $drepturi";
				// verificare
				$verificare = 0;
				if (empty($user))
					{
					$verificare = 1;
					echo $mesaj[41];
					}
				else
					{
					// se verifica in bd daca userul exista
					$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]." 1");
					mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]." 1");
					// se face cautarea case sensitive
					$comanda_sql = "SELECT * FROM ".$prefix_tabel_bd."user WHERE user LIKE BINARY '".$user."'";
					// echo $comanda_sql;
					$interogare_sql = mysql_query($comanda_sql) OR die($mesaj[9]." 1");
					$total = mysql_num_rows($interogare_sql);			
					// echo $total;
					while ($rand = mysql_fetch_array($interogare_sql))
						{
						$id_tinta = $rand['nr'];
						$dreptu_user = $rand['stare'];
						// aici se verifica daca userul nu are drepturi mai mici decat la cel caruia vrea sa ii schimbe drepturile							
						if ($dreptu_user < $_SESSION[$prefix_sesiuni.'_rights_far']) 
							{
							$verificare = 1;
							echo $mesaj[120];
							}
						}
					mysql_close($conectare);
					if ($total == 0)
						{
						$verificare = 1;
						echo $mesaj[119];
						}										
					}
				if ($verificare == 1) // daca este vreo eroare in urma verificarilor
					{
					// se afiseaza din nou formularul
					echo '<form name="form_drepturi" method="post" action="admin.php?m=login_new&action=new_right">
					<table width="100%"  border="0" cellpadding="0" cellspacing="0">
				  	<tr>
				    <td valign="top"><table width="100%"  border="0" cellpadding="0" cellspacing="3">
				      <tr>
				        <td width="25%"><div align="right">'.$mesaj[112].'</div></td>
					        <td width="25%"><input name="user" type="text" id="user">
				        </td>
				        </tr>
				    </table></td>
				  	</tr>
				 	<tr>
				    <td valign="top"><table width="100%"  border="0" cellpadding="0" cellspacing="3">
				      <tr>
			        <td width="50%"><div align="right">'.$mesaj[113].'</div></td>
			        <td width="50%"><select name="drepturi" id="drepturi">';
					// se afiseaza doar drepturile care sunt egale sau mai mici decat ale userului curent
					// mai trebuie lucrata la chestia asta ca se poate face si mai elegant da acum mi-e lene
					if ($_SESSION[$prefix_sesiuni.'_rights_far'] == 1)
						{
						echo '<option value="5" selected>'.$mesaj[114].'</option>
					          <option value="4">'.$mesaj[115].'</option>
					          <option value="3">'.$mesaj[116].'</option>
			        		  <option value="2">'.$mesaj[117].'</option>
					          <option value="1">'.$mesaj[118].'</option>';
						}
					if ($_SESSION[$prefix_sesiuni.'_rights_far'] == 2)
						{
						echo '<option value="5" selected>'.$mesaj[114].'</option>
					          <option value="4">'.$mesaj[115].'</option>
					          <option value="3">'.$mesaj[116].'</option>
			        		  <option value="2">'.$mesaj[117].'</option>';
						}
					if ($_SESSION[$prefix_sesiuni.'_rights_far'] == 3)
						{
						echo '<option value="5" selected>'.$mesaj[114].'</option>
					          <option value="4">'.$mesaj[115].'</option>
					          <option value="3">'.$mesaj[116].'</option>';
						}
					if ($_SESSION[$prefix_sesiuni.'_rights_far'] == 4)
						{
						echo '<option value="5" selected>'.$mesaj[114].'</option>
					          <option value="4">'.$mesaj[115].'</option>';
						}
					if ($_SESSION[$prefix_sesiuni.'_rights_far'] == 5)
						{
						echo '<option value="5" selected>'.$mesaj[114].'</option>';
						}
					// se continua cu afisarea formularului
					echo '</select></td>
			        	</tr>
			    		</table></td>
			  			</tr>
			  			<tr>
			    		<td valign="top"><div align="center">
			      		<input name="stare" type="hidden" id="stare" value="da">
			      		<input type="submit" name="Submit" value="'.$mesaj[28].'">
						</div></td>
					  	</tr>
						</table>
						</form>';
					}
				if ($verificare == 0) // daca totul e ok se face modificarea
					{
					$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]." 2");
					mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]." 2");
					// se face cautarea case sensitive
					$comanda_sql = "UPDATE ".$prefix_tabel_bd."user SET stare='".$drepturi."' WHERE user LIKE BINARY '".$user."'";
					// echo $comanda_sql;
					$interogare_sql = mysql_query($comanda_sql) OR die($mesaj[9]." 2");
					if ($interogare_sql == TRUE)
						{
						echo '<META HTTP-EQUIV = "Refresh" Content = "5; URL ='.$adresa_url.'">'.$mesaj[33];						
						}
					else
						{
						echo '<META HTTP-EQUIV = "Refresh" Content = "5; URL ='.$adresa_url.'">'.$mesaj[92];						
						}
					mysql_close($conectare);
					}				
				}
			}
		}
	else
		{
		echo $mesaj[110];
		}
	// nu se poate acorda drepturi mai mari decat are userul deja conectat	
	// trebuie facut cu cautare ca la phpbb pentru user...
	}
// daca tipul de actiune este stergerea unui user... ======================================================================
if ($actiune == "new_del")
	{	
	// se verifica daca userul are drept de acces 1,2 sau 3	
	if (isset($_SESSION[$prefix_sesiuni.'_rights_far']))
		{
		$drepturi_user = $_SESSION[$prefix_sesiuni.'_rights_far'];
		$verificare = 0;
		if ($drepturi_user != 1)
			{
			if ($drepturi_user != 2)
				{
				if ($drepturi_user != 3)
					{					
					$verificare = 1;
					echo $mesaj[111];
					echo $mesaj[81].$drepturi_user;						
					}
				}
			}
		if ($verificare == 0)
			{
			// daca are drept de acces se verifica daca formularul nu a fost deja trimis
			if (!isset($_POST['sterge'])) // se afiseaza formularul daca nu a fost completat pana acum
				{
				echo '<form name="form_sterge" method="post" action="admin.php?m=login_new&action=new_del">
					<table width="100%"  border="0" cellpadding="0" cellspacing="0">
					  <tr>
					    <td valign="top"><table width="100%"  border="0" cellpadding="0" cellspacing="3">
					      <tr>
					        <td width="50%"><div align="right">'.$mesaj[112].'</div></td>
					        <td width="50%"><input name="user" type="text" id="user"></td>
					        </tr>
					    </table></td>
					  </tr>
					  <tr>
					    <td valign="top"><div align="center">
					      <input name="sterge" type="hidden" id="sterge" value="da">
					      <input type="submit" name="Submit" value="'.$mesaj[122].'">
					</div></td>
					  </tr>
					</table>
					</form>';
				}
			else // daca formularul a fost trimis se verifica datele
				{
				@$user = $_POST['user'];
				$verificare = 0;
				if (empty($user))
					{
					$verificare = 1;
					echo $mesaj[41];
					}
				else
					{
					// se verifica daca userul exista in baza de date
					$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]." 1");
					mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]." 1");
					// se face cautarea case sensitive
					$comanda_sql = "SELECT * FROM ".$prefix_tabel_bd."user WHERE user LIKE BINARY '".$user."'";
					// echo $comanda_sql;
					$interogare_sql = mysql_query($comanda_sql) OR die($mesaj[9]." 1");
					$total = mysql_num_rows($interogare_sql);			
					// echo $total;
					while ($rand = mysql_fetch_array($interogare_sql))
						{
						$id_tinta = $rand['nr'];
						$dreptu_user = $rand['stare'];
						// aici se verifica daca userul nu are drepturi mai mici decat la cel caruia vrea sa il stearga						
						if ($dreptu_user < $_SESSION[$prefix_sesiuni.'_rights_far']) 
							{
							$verificare = 1;
							echo $mesaj[123];
							}
						}
					mysql_close($conectare);
					if ($total == 0)
						{
						$verificare = 1;
						echo $mesaj[119];
						}					
					}
				if ($verificare == 1) // daca e o eroare in urma verificarilor afiseaza din nou formularul
					{
					echo '<form name="form_sterge" method="post" action="admin.php?m=login_new&action=new_del">
					<table width="100%"  border="0" cellpadding="0" cellspacing="0">
					  <tr>
					    <td valign="top"><table width="100%"  border="0" cellpadding="0" cellspacing="3">
					      <tr>
					        <td width="50%"><div align="right">'.$mesaj[112].'</div></td>
					        <td width="50%"><input name="user" type="text" id="user"></td>
					        </tr>
					    </table></td>
					  </tr>
					  <tr>
					    <td valign="top"><div align="center">
					      <input name="sterge" type="hidden" id="sterge" value="da">
					      <input type="submit" name="Submit" value="'.$mesaj[122].'">
					</div></td>
					  </tr>
					</table>
					</form>';
					}
				else // daca nu e nici o eroare in urma verificarilor sterge userul
					{
					$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]." 2");
					mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]." 2");
					// se face cautarea case sensitive
					$comanda_sql = "DELETE FROM ".$prefix_tabel_bd."user WHERE user LIKE BINARY '".$user."'";
					// echo $comanda_sql;
					$interogare_sql = mysql_query($comanda_sql) OR die($mesaj[9]." 2");
					if ($interogare_sql == TRUE)
						{
						// adaugare la ver 1.0 alfa 2
						$fisier = "modules/adduserphpbb.php";
						if (file_exists($fisier))
							{
							$actiune_phpbb = "del";
							include($fisier);						
							}
						echo '<META HTTP-EQUIV = "Refresh" Content = "5; URL ='.$adresa_url.'">'.$mesaj[33];	
						}
					else
						{
						echo '<META HTTP-EQUIV = "Refresh" Content = "5; URL ='.$adresa_url.'">'.$mesaj[124];	
						}
					}	
				}
			}
		}
	// nu se pot sterge userii cu drepturi mai mari decat ale userului conectat
	// daca are drept de acces se afiseaza formularul 
	// trebuie facut cu cautare ca la phpbb pentru user...
	}
// daca tipul de actiune este deconectarea userului ======================================================================
if ($actiune == "dec")
	{
	//setcookie($prefix_sesiuni."_nume_far", "", time()-3600, "/");
	//setcookie($prefix_sesiuni."_stare_far", "", time()-3600, "/");
	// resetarea variabilelor de sesiune
	unset($_SESSION[$prefix_sesiuni.'_language_far']);
	unset($_SESSION[$prefix_sesiuni.'_themes_far']);
	unset($_SESSION[$prefix_sesiuni.'_user_far']);
	unset($_SESSION[$prefix_sesiuni.'_email_far']);				
	unset($_SESSION[$prefix_sesiuni.'_rights_far']);	
	unset($_SESSION[$prefix_sesiuni.'_hidden_far']);
	unset($_SESSION[$prefix_sesiuni.'_permanently_far']);
	unset($_SESSION[$prefix_sesiuni.'_cheia_far']);
	$timp_expirare = time()-3600;
	$prelucrare_cooke = stripslashes($_COOKIE[$prefix_sesiuni.'_far']);
	$valoare_cookie = unserialize($prelucrare_cooke);
	$valoare_cookie['language_far'] = $limbaj_primar;
	$valoare_cookie['themes_far'] = $pagina_finala;
	$valoare_cookie['user_far'] = '';
	$valoare_cookie['email_far'] = '';
	$valoare_cookie['password_far'] = '';
	$valoare_cookie['hidden_far'] = 0;
	$valoare_cookie['permanently_far'] = 0;
	$val_coke = serialize($valoare_cookie);	
	//setcookie($prefix_sesiuni."_far", $val_coke, $timp_expirare, "/");
	unset($_COOKIE[session_name()]);
	session_unset();
	// distrugerea sesiunii
	session_destroy();
	// redirectarea pentru curatarea sesiunii
	setcookie($prefix_sesiuni."_far", $val_coke, $timp_expirare, "/");
	echo '<META HTTP-EQUIV = "Refresh" Content = "0; URL ='.$adresa_url.'">'.$mesaj[63];
	}
// daca tipul de actiune difera de cele specificate se redirecteaza la pagina principala
if ($actiune != "new_user")
	{
	if ($actiune != "new_pass")
		{
		if ($actiune != "new_pass2")
			{
			if ($actiune != "new_right")
				{
				if ($actiune != "new_del")
					{
					if ($actiune != "dec")
						{						
						echo '<META HTTP-EQUIV = "Refresh" Content = "3; URL ='.$adresa_url.'">'.$mesaj[82];
						}
					}
				}
			}
		}
	}
?>