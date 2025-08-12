<?php
/* =====================================================================
*	Modulul de schimbat template-ul - ch_template.php
*	Creat de Birkoff pentru proiectul FAR-PHP
*	Versiune: 1.0
*	Copyright: (C) 2004 - 2005 The FAR-PHP Group
*	E-mail: contact@far-php.ro
*	Inceput la: 05-01-2004
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
// se afiseaza lista de teme pentru template
// se verifica daca template-ul ales exista
// se seteaza cooke cu noua tema
// se redirecteaza pentru incarcarea temei

// se verifica daca formularul nu a fost deja trimis
if (!isset($_POST['sch_tema'])) // daca nu e trimis se afiseaza
	{
	// se citeste structura de directoare din themes
	$director = opendir("themes");
	$verificare = 0;
	if (!$director) // daca nu se poate deschide
		{
		$verificare = 1;
		echo $mesaj[125];
		}
	$citire = readdir($director); // se citeste continutul directorului
	if (!$citire)
		{
		$verificare = 1;
		echo $mesaj[126];
		}
	$nr_tema = 0;
	if ($verificare == 0)
		{
		while ($citire)
			{
			$exista_tema[$nr_tema] = $citire; // se memoreaza numele temei
			$nr_tema++;	
			$citire = readdir($director);
			}
		}
	closedir($director);
	/*
	// numele incep de la 2 pentru ca 0 = . (directorul radacina) si 1 = .. (directorul themes)
	for($dd=2;$dd<=$nr_tema;$dd++)
		{
		echo "<br>".$exista_tema[$dd];
		}
	*/
	// daca citirea este efectuata cu succes
	if ($verificare == 0)
		{
		// se afiseaza formularul pentru schimbare tema la template
		echo '<form name="form_ch_template" method="post" action="admin.php?m=ch_template">
			  <table width="100%"  border="0" cellpadding="0" cellspacing="0">
			    <tr>
			      <td valign="top"><table width="100%"  border="0" cellpadding="0" cellspacing="3">
			        <tr>
			          <td width="50%"><div align="right">'.$mesaj[127].'</div></td>
			          <td width="50%"><select name="select_tema" id="select_tema">';
					  // se afiseaza numele temelor existente
					  for($dd=2;$dd<$nr_tema;$dd++)
						{
						if ($exista_tema[$dd] != "index.php")
							{
							echo '<option value="'.$exista_tema[$dd].'">'.$exista_tema[$dd].'</option>';
							}
						}
			          // se continua afisarea formularului
					  echo ' 
			          </select></td>
			          </tr>
			      </table></td>
			    </tr>
			    <tr>
			      <td valign="top"><div align="center">
			        <input name="sch_tema" type="hidden" id="sch_tema" value="da">
			        <input type="submit" name="Submit" value="'.$mesaj[28].'">
			</div></td>
			    </tr>
			  </table>
			</form>';
		}
	}
if (isset($_POST['sch_tema'])) // daca formularul a fost trimis se schimba tema in cooke
	{
	@$tema_aleasa = $_POST['select_tema'].".php";
	// echo "<br>".$tema_aleasa;
	// se seteaza timpul de expirare al cookeului
	$expira = 1*60*60*24*100; // in acest caz expira dupa 100 de zile
	$timp_expirare = time()+$expira;
	// $data_expirare = date("d-m-Y", $timp_expirare);
	if (isset($_COOKIE[$prefix_sesiuni.'_far']))
		{
		$prelucrare_cooke = stripslashes($_COOKIE[$prefix_sesiuni.'_far']);
		$valoare_cookie = unserialize($prelucrare_cooke);
		$valoare_cookie['themes_far'] = $tema_aleasa;
		$val_coke = serialize($valoare_cookie);	
		setcookie($prefix_sesiuni."_far", $val_coke, $timp_expirare, "/");
		$_SESSION[$prefix_sesiuni.'_themes_far'] = $tema_aleasa;
		}
	if (!isset($_COOKIE[$prefix_sesiuni.'_far']))
		{
		$valoare_cookie = array(
			'language_far' => $_SESSION[$prefix_sesiuni.'_language_far'],
			'themes_far' => $tema_aleasa,
			'user_far' => '',
			'email_far' => '',
			'password_far' => '',
			'hidden_far' => '0',
			'permanently_far' => '0');
		$val_coke = serialize($valoare_cookie);			
		setcookie($prefix_sesiuni."_far", $val_coke, $timp_expirare, "/");
		$_SESSION[$prefix_sesiuni.'_themes_far'] = $tema_aleasa;
		}
	// se incarca template-ul specificat in config.php
	echo '<META HTTP-EQUIV = "Refresh" Content = "0; URL ='.$adresa_url.'">'.$mesaj[128];		
	// echo "<br>Noua tema este: ".$_SESSION[$prefix_sesiuni.'_template'];		
	}

?>