<?php
/* =====================================================================
*	Modulul de instalare a proiectului FAR-PHP - install.php
*	Creat de Birkoff pentru proiectul FAR-PHP
*	Versiune: 1.0
*	Copyright: (C) 2004 - 2005 The FAR-PHP Group
*	E-mail: contact@far-php.ro
*	Inceput la: 18.04.2005
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
debug_mesaje(29);
// se include fisierul cu mesaje
if (!isset($_SESSION['limbaj_temporar_far']))
	{
	$limbaj_de_lucru = "codes/language_en.php";
	if (file_exists($limbaj_de_lucru))
		{
		include_once ($limbaj_de_lucru);
		}
	if (!file_exists($limbaj_de_lucru))
		{
		die ("<br>Error: File 'codes/language_en.php' not exist");
		}
	$_SESSION['limbaj_temporar_far'] = "en";	
	}
if (isset($_SESSION['limbaj_temporar_far']))
	{	
	$limbaj_de_lucru = "codes/language_".$_SESSION['limbaj_temporar_far'].".php";
	if (file_exists($limbaj_de_lucru))
		{
		include_once ($limbaj_de_lucru);
		}
	if (!file_exists($limbaj_de_lucru))
		{
		die ("<br>Error: File 'codes/language_en.php' not exist");
		}
	}
debug_mesaje(32);
// se creaza functia pentru formular
function formular ($valoare)
	{
	// se cere fisierul de mesaje	
	global $mesaj;
	// se seteaza limbajul default	
	$limbaj_formatat = $_SESSION['limbaj_temporar_far'];
	// echo $limbaj_formatat;
	
	// se citeste structura de directoare din themes
	if (is_dir("themes"))
		{
		$director = opendir("themes");
		}
	if (!is_dir("themes"))
		{
		die ("<br>Error: The folder 'themes' not exist");
		}
	
	$verificare = 0;
	if (!$director) // daca nu se poate deschide
		{
		$verificare = 1;
		die ($mesaj[125]);
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
			if ($citire != ".")
				{
				if ($citire != "..")
					{
					if ($citire != "index.php")
						{
						$exista_tema[$nr_tema] = $citire; // se memoreaza numele temei				
						// echo "<br>$nr_tema -".$exista_tema[$nr_tema];			
						$nr_tema++;				
						$citire = readdir($director);
						}
					else
						{
						$citire = readdir($director);
						}
					}
				else
					{
					$citire = readdir($director);
					}
				}
			else
				{
				$citire = readdir($director);
				}
			}
		}
	closedir($director);
	if ($nr_tema == 0)
		{
		die ("<br>Error: There are not template in 'themes' folders.");
		}
	$action = $_SERVER['PHP_SELF'];
	$locatie = "http://www.";
	$java = "this.value=''";
	
	// se verifica daca trebuie afisat formularul cu valori sau fara
	if ($valoare == "1")
		{
		// se afiseaza fara valori				
		$sql_host = 'localhost';
		$sql_user = 'user_sql';
		$sql_parola_1 = '';
		$sql_parola_2 = '';
		$sql_bd = '';
		$sql_prefix = 'far_';
		$server_prefix = 'far';
		$server_ora = '0';
		$server_ora_dif = '+';
		$server_web = $locatie;
		$server_tema = 'red';
		$server_limbaj = 'ro';
		$admin_cript = 'da';
		$admin_log = '5';
		$admin_user_admin = '';
		$admin_email_admin = '';
		$admin_parola_admin_1 = '';
		$admin_parola_admin_2 = '';
		$admin_user_subadmin = '';
		$admin_email_subadmin = '';
		$admin_parola_subadmin_1 = '';
		$admin_parola_subadmin_2 = '';
		$admin_mesaj = '&lt;br&gt;&lt;strong&gt;Copyright &lt;/strong&gt;&lt;br&gt;';
		$form_trimis = 'da';
		}
	if ($valoare == "2")
		{
		// se afiseaza cu valori				
		global $limbaj_lucru;
		global $sql_host;
		global $sql_user;		
		global $sql_parola_1;
		global $sql_parola_2;
		global $sql_bd;
		global $sql_prefix;
		global $server_prefix;
		global $server_ora;
		global $server_ora_dif;
		global $server_web;
		global $server_tema;
		global $server_limbaj;
		global $admin_cript;
		global $admin_log;
		global $admin_user_admin;
		global $admin_email_admin;
		global $admin_parola_admin_1;
		global $admin_parola_admin_2;
		global $admin_user_subadmin;
		global $admin_email_subadmin;
		global $admin_parola_subadmin_1;
		global $admin_parola_subadmin_2;
		global $admin_mesaj;
		global $form_trimis;
		}	
		
	// se afiseaza formularul pentru selectarea limbajului
	debug_mesaje(30);
	echo '<form name="form_instal_limbaj" method="post" action="'.$action.'">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FF0000">
	   		<tr>
        		<td width="50%">'.$mesaj[229].'</td>
        		<td>
				<select name="limbaj_lucru" onchange="document.form_instal_limbaj.submit()">';
				if ($limbaj_formatat == "ro")
					{
					echo '<option value="ro">Romanian</option>
        				<option value="en">English</option>';
					}
				if ($limbaj_formatat == "en")
					{
					echo '<option value="en">English</option>
						<option value="ro">Romanian</option>';
					}
				echo '</select>
				</td>
        	</tr>
   		</table></form>';
	echo '<form name="form_instal" method="post" action="'.$action.'">
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
    	<td valign="top" bgcolor="#0099CC"><div align="center">
	   	<p>'.$mesaj[227].'</p>
      	<p align="justify">'.$mesaj[228];
	if (!extension_loaded('session'))
		{
		if (!dl('session'))
			{        
    		echo "<br>session extension error";
			}
		}
	if (!extension_loaded('mysql'))
		{
		if (!dl('mysql'))
			{        
    		echo "<br>mysql extension error";
			}
		}
	if (!extension_loaded('ftp'))
		{
		if (!dl('ftp'))
			{        
    		echo "<br>ftp extension error";
			}
		}
	
	echo '</p></div>
		</td>
  		</tr>	  		
		<tr>
    	<td bgcolor="#CCCCCC"><div align="center">'.$mesaj[230].'</div></td>
		</tr>
  		<tr>
   		<td valign="top" bgcolor="#F0F0F0"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      	<tr>
   	    <td width="50%">'.$mesaj[231].'</td>
        <td><input name="sql_host" type="text" id="sql_host" value="'.$sql_host.'" size="15" onClick="'.$java.'"></td>
   	  	</tr>
      	<tr>
   	    <td>'.$mesaj[232].'</td>
        <td><input name="sql_user" type="text" id="sql_user" onClick="'.$java.'" value="'.$sql_user.'" size="15"></td>
   	  	</tr>
      	<tr>
   	    <td>'.$mesaj[233].'</td>
        <td><input name="sql_parola_1" type="password" id="sql_parola_1" size="15"> 
   	    '.$mesaj[234].'
        <input name="sql_parola_2" type="password" id="sql_parola_2" size="15"></td>
   	  	</tr>
      	<tr>
   	    <td>'.$mesaj[235].'</td>
        <td><input name="sql_bd" type="text" id="sql_bd" size="15" value="'.$sql_bd.'"></td>
   	  	</tr>
      	<tr>
   	    <td>'.$mesaj[236].'</td>
        <td><input name="sql_prefix" type="text" id="sql_prefix" onClick="'.$java.'" value="'.$sql_prefix.'" size="15"></td>
   	  	</tr>
    	</table></td>
		</tr>
  		<tr>
   		<td bgcolor="#CCCCCC"><div align="center">'.$mesaj[237].'</div></td>
  		</tr>
		<tr>
    	<td valign="top" bgcolor="#F0F0F0"><table width="100%" border="0" cellspacing="0" cellpadding="0">
   	  	<tr>
        <td width="50%">'.$mesaj[238].'</td>
   	    <td><input name="server_prefix" type="text" id="server_prefix" onClick="'.$java.'" value="'.$server_prefix.'" size="15"></td>
      	</tr>
   	  	<tr>
        <td>'.$mesaj[239].'</td>
   	    <td><input name="server_ora" type="text" id="server_ora" onClick="'.$java.'" value="'.$server_ora.'" size="15">
        <select name="server_ora_dif" id="server_ora_dif">
   	    <option value="+" selected>+</option>
        <option value="-">-</option>
   	    </select></td>
      	</tr>
   	  	<tr>
        <td>'.$mesaj[240].'</td>
   	    <td><input name="server_web" type="text" id="server_web" value="'.$server_web.'"></td>
      	</tr>
   	  	<tr>
        <td>'.$mesaj[241].'</td>
   	    <td>
		<select name="server_tema" id="server_tema">';
	// se afiseaza numele temelor existente
	for($dd=0;$dd<$nr_tema;$dd++)
		{
		echo '<option value="'.$exista_tema[$dd].'">'.$exista_tema[$dd].'</option>';
		}
	// se continua afisarea formularului
	echo '
        </select></td>
   	  	</tr>
      	<tr>
   	    <td>'.$mesaj[242].'</td>
        <td><select name="server_limbaj" id="server_limbaj">
   	    <option value="ro">Romanian</option>
        <option value="en">English</option>
   	    </select></td>
      	</tr>
   		</table></td>
  		</tr>
		<tr>
    	<td bgcolor="#CCCCCC"><div align="center">'.$mesaj[243].'</div></td>
		</tr>
  		<tr>
   		<td valign="top" bgcolor="#F0F0F0"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      	<tr>
   	    <td width="50%">'.$mesaj[244].'</td>
        <td><select name="admin_cript" id="admin_cript">
   	    <option value="da" selected>'.$mesaj[245].'</option>
        <option value="nu">'.$mesaj[246].'</option>
   	    </select></td>
      	</tr>
   	  	<tr>
        <td>'.$mesaj[247].'</td>
   	    <td><input name="admin_log" type="text" id="admin_log" onClick="'.$java.'" value="'.$admin_log.'" size="15"> 
        '.$mesaj[248].'</td>
   	  	</tr>
      	<tr>
   	    <td>'.$mesaj[249].'</td>
        <td><input name="admin_user_admin" type="text" id="admin_user_admin" size="15" value="'.$admin_user_admin.'"></td>
   	  	</tr>
      	<tr>
   	    <td>'.$mesaj[250].'</td>
        <td><input name="admin_email_admin" type="text" id="admin_email_admin" size="15" value="'.$admin_email_admin.'"></td>
   	  	</tr>
      	<tr>
   	    <td>'.$mesaj[251].'</td>
        <td><input name="admin_parola_admin_1" type="password" id="admin_parola_admin_1" size="15">
   	    '.$mesaj[234].'
        <input name="admin_parola_admin_2" type="password" id="admin_parola_admin_2" size="15"></td>
   	  	</tr>
      	<tr>
   	    <td>'.$mesaj[252].'</td>
        <td><input name="admin_user_subadmin" type="text" id="admin_user_subadmin" size="15" value="'.$admin_user_subadmin.'"></td>
   	  	</tr>
      	<tr>
   	    <td>'.$mesaj[253].'</td>
        <td><input name="admin_email_subadmin" type="text" id="admin_email_subadmin" size="15" value="'.$admin_email_subadmin.'"></td>
   	  	</tr>
      	<tr>
   	    <td>'.$mesaj[254].'</td>
        <td><input name="admin_parola_subadmin_1" type="password" id="admin_parola_subadmin_1" size="15"> 
   	    '.$mesaj[234].'
        <input name="admin_parola_subadmin_2" type="password" id="admin_parola_subadmin_2" size="15"></td>
   	  	</tr>
      	<tr>
   	    <td>'.$mesaj[255].'</td>
        <td><textarea name="admin_mesaj" cols="30" rows="5" id="admin_mesaj">'.$admin_mesaj.'</textarea></td>
   	  	</tr>
    	</table></td>
		</tr>
  		<tr>
   		<td bgcolor="#CCCCCC"><div align="center">
      	<input name="form_trimis" type="hidden" id="form_trimis" value="da">
   	  	<input type="submit" name="Submit" value="'.$mesaj[256].'">
    	</div></td>
		</tr>
  		<tr>
   		<td bgcolor="#0099CC">&nbsp;</td>
  		</tr>
		</table>
		</form>';
	}

// se trimite head-ul
/*
echo '
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
	<html>
	<head>
	<title>'.$mesaj[226].'</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	</head>
	<body>';
*/
// se verifica daca unu din formulare a fost trimis
if (!isset($_POST['form_trimis'])) // daca nu a fost trimis formularul cu setari
	{
	if (!isset($_POST['limbaj_lucru'])) // si nici cel cu limbaj
		{
		debug_mesaje(31);
		formular(1); // atunci afiseaza formularul pentru prima data
		}
	else // daca a fost trimis formularul de limbaj
		{
		$limbaj_lucru = $_POST['limbaj_lucru']; // se preia valoarea trimisa
		if ($limbaj_lucru == "en") // daca limba aleasa e engleza
			{
			// se schimba limbajul
			$_SESSION['limbaj_temporar_far'] = "en";
			$limbaj_de_lucru = "codes/language_en.php";
			if (file_exists($limbaj_de_lucru))
				{
				include_once ($limbaj_de_lucru);
				}
			if (!file_exists($limbaj_de_lucru))
				{
				die ("<br>Error: File 'codes/language_en.php' not exist");
				}			
			formular(1); // se afiseaza din nou formularul in limbajul ales
			}
		if ($limbaj_lucru == "ro") // daca limba aleasa e romana
			{
			// se schimba limbajul
			$_SESSION['limbaj_temporar_far'] = "ro";
			$limbaj_de_lucru = "codes/language_ro.php";
			if (file_exists($limbaj_de_lucru))
				{
				include_once ($limbaj_de_lucru);
				}
			if (!file_exists($limbaj_de_lucru))
				{
				die ("<br>Eroare: Fisierul 'codes/language_ro.php' nu exista");
				}
			formular(1); // se afiseaza din nou formularul in limbajul ales
			}
		}
	}
	
// daca formularul cu setari a fost trimis
if (isset($_POST['form_trimis']))
	{
	// se preiau valorile trimise	
	$sql_host = $_POST['sql_host'];
	$sql_user = $_POST['sql_user'];
	$sql_parola_1 = $_POST['sql_parola_1'];
	$sql_parola_2 = $_POST['sql_parola_2'];
	$sql_bd = $_POST['sql_bd'];
	$sql_prefix = $_POST['sql_prefix'];
	$server_prefix = $_POST['server_prefix'];
	$server_ora = $_POST['server_ora'];
	$server_ora_dif = $_POST['server_ora_dif'];
	$server_web = $_POST['server_web'];
	$server_tema = $_POST['server_tema'];
	$server_limbaj = $_POST['server_limbaj'];
	$admin_cript = $_POST['admin_cript'];
	$admin_log = $_POST['admin_log'];
	$admin_user_admin = $_POST['admin_user_admin'];
	$admin_email_admin = $_POST['admin_email_admin'];
	$admin_parola_admin_1 = $_POST['admin_parola_admin_1'];
	$admin_parola_admin_2 = $_POST['admin_parola_admin_2'];
	$admin_user_subadmin = $_POST['admin_user_subadmin'];
	$admin_email_subadmin = $_POST['admin_email_subadmin'];
	$admin_parola_subadmin_1 = $_POST['admin_parola_subadmin_1'];
	$admin_parola_subadmin_2 = $_POST['admin_parola_subadmin_2'];
	$admin_mesaj = $_POST['admin_mesaj'];
	$form_trimis = $_POST['form_trimis'];
	
	// se verifica datele trimise
	$verificare = 0;
	if (empty($sql_host))
		{
		$verificare = 1;
		echo $mesaj[257];
		}
	if (empty($sql_user))
		{
		$verificare = 1;
		echo $mesaj[258];
		}
	if ($sql_user == "user_sql")
		{
		$verificare = 1;
		echo $mesaj[259];
		}
	if (empty($sql_parola_1))
		{
		$verificare = 1;
		echo $mesaj[260];
		}
	if (empty($sql_parola_2))
		{
		$verificare = 1;
		echo $mesaj[261];
		}
	if(!empty($sql_parola_1))
		{
		if(!empty($sql_parola_2))
			{
			if ($sql_parola_1 != $sql_parola_2)
				{
				$verificare = 1;
				echo $mesaj[262];
				}
			}
		}
	if (empty($sql_bd))
		{
		$verificare = 1;
		echo $mesaj[263];
		}
	if (empty($sql_prefix))
		{
		$verificare = 1;
		echo $mesaj[264];
		}
	if (empty($server_prefix))
		{
		$verificare = 1;
		echo $mesaj[265];
		}
	if (empty($server_ora))
		{
		$server_ora = 0;
		}
	if (!empty($server_ora))
		{
		if (!preg_match('/^[0-9]{'.strlen($server_ora).'}$/', $server_ora))
			{
			$verificare = 1;
			echo $mesaj[266];
			}
		}
	if (empty($server_web))
		{
		$verificare = 1;
		echo $mesaj[267];
		}
	if (!empty($server_web))
		{
		if ($server_web == "http://www.")
			{
			$verificare = 1;
			echo $mesaj[267];
			}
		else
			{
			if (eregi("^http://+[a-z0-9\._/-]+/$",$server_web) == FALSE)
				{
				$verificare = 1;
				echo $mesaj[268];				
				}
			}
		}
	if (empty($admin_log))
		{
		$admin_log = 0;
		}
	if (!empty($admin_log))
		{
		if (!preg_match('/^[0-9]{'.strlen($admin_log).'}$/', $admin_log))
			{
			$verificare = 1;
			echo $mesaj[269];
			}
		}
	if (empty($admin_user_admin))
		{
		$verificare = 1;
		echo $mesaj[270];
		}
	if (!empty($admin_user_admin))
		{
		if (strlen($admin_user_admin) <= 2)
			{
			$verificare = 1;
			echo $mesaj[271];
			}
		}
	if (empty($admin_email_admin))
		{
		$verificare = 1;
		echo $mesaj[272];
		}
	if (!empty($admin_email_admin))
		{
		if (eregi("^[a-z0-9\._-]+@+[a-z0-9\._-]+\.+[a-z]{2,3}$",$admin_email_admin) != TRUE) 
			{			
			$verificare = 1;
			echo $mesaj[273];
			}		
		}
	if (empty($admin_parola_admin_1))
		{
		$verificare = 1;
		echo $mesaj[274];
		}
	if (empty($admin_parola_admin_2))
		{
		$verificare = 1;
		echo $mesaj[275];
		}
	if(!empty($admin_parola_admin_1))
		{
		if(strlen($admin_parola_admin_1) <= 3) // daca parola e mai mica sau egala de 3 caractere
			{
			$verificare = 1;
			echo $mesaj[276];
			}
		if(!empty($admin_parola_admin_2))
			{
			if ($admin_parola_admin_1 != $admin_parola_admin_2)
				{
				$verificare = 1;
				echo $mesaj[277];
				}
			}
		}	
	if (!empty($admin_user_subadmin))
		{
		if (strlen($admin_user_admin) <= 2)
			{
			$verificare = 1;
			echo $mesaj[278];
			}
		if (empty($admin_email_subadmin))
			{
			$verificare = 1;
			echo $mesaj[279];
			}
		if (!empty($admin_email_subadmin))
			{
			if (eregi("^[a-z0-9\._-]+@+[a-z0-9\._-]+\.+[a-z]{2,3}$",$admin_email_subadmin) != TRUE) 
				{			
				$verificare = 1;
				echo $mesaj[280];
				}	
			}
		if (empty($admin_parola_subadmin_1))
			{
			$verificare = 1;
			echo $mesaj[281];
			}
		if (empty($admin_parola_subadmin_2))
			{
			$verificare = 1;
			echo $mesaj[282];
			}
		if(!empty($admin_parola_subadmin_1))
			{
			if(strlen($admin_parola_subadmin_1) <= 3) // daca parola e mai mica sau egala de 3 caractere
				{
				$verificare = 1;
				echo $mesaj[283];
				}
			if(!empty($admin_parola_subadmin_2))
				{
				if ($admin_parola_subadmin_1 != $admin_parola_subadmin_2)
					{
					$verificare = 1;
					echo $mesaj[284];
					}
				}
			}
		}
	if (!empty($admin_mesaj))
		{
		if ($admin_mesaj == '&lt;br&gt;&lt;strong&gt;Copyright &lt;/strong&gt;')
			{
			$verificare = 1;
			echo $mesaj[285];
			}
		}		
	if ($verificare == 0) // daca pana aici e ok
		{
		// se verifca daca datele pentru sql sunt corecte		
		@$conectare = mysql_connect($sql_host, $sql_user, $sql_parola_1) OR $verificare = 1;			
		@mysql_select_db($sql_bd, $conectare) OR $verificare = 1;
		@mysql_close($conectare);		
		if ($verificare == 1)
			{
			echo $mesaj[286];
			}
		}
	// daca sunt erori
	if ($verificare == 1)
		{
		// se afiseaza din nou formularul cu datele trimise
		formular(2);
		}	
	// daca nu sunt erori
	if ($verificare == 0)
		{		
		// se genereaza tabelele in baza de date
		@$conectare = mysql_connect($sql_host, $sql_user, $sql_parola_1) OR $verificare = 1;			
		@mysql_select_db($sql_bd, $conectare) OR $verificare = 1;
		// se creaza tabelul 'connect'
		$interogare = "DROP TABLE IF EXISTS ".$sql_prefix."connect";
		@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;				
		$interogare = "CREATE TABLE IF NOT EXISTS ".$sql_prefix."connect (
			  nr int(11) NOT NULL auto_increment,
			  user text NOT NULL,
			  parola text NOT NULL,
			  data date NOT NULL default '0000-00-00',
			  ora time NOT NULL default '00:00:00',
			  timp text NOT NULL,
			  ip text NOT NULL,
			  host text NOT NULL,
			  browser text NOT NULL,
			  PRIMARY KEY  (`nr`)
				) AUTO_INCREMENT=1";
		@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;		
		if ($verificare == 1)
			{
			echo $mesaj[287].$sql_prefix."connect'".$mesaj[288];
			}
		else
			{
			echo $mesaj[287].$sql_prefix."connect'".$mesaj[289];
			}
		// se creaza tabelul 'content'
		$interogare = "DROP TABLE IF EXISTS ".$sql_prefix."content";
		@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;				
		$interogare = "CREATE TABLE IF NOT EXISTS ".$sql_prefix."content (
  			id int(11) NOT NULL auto_increment,
			name_content varchar(35) NOT NULL default '',
			title text NOT NULL,
			content text NOT NULL,
			language_content varchar(35) NOT NULL default '',
			author_content varchar(35) NOT NULL default '',
			email_author varchar(35) NOT NULL default '',
			user_post varchar(35) NOT NULL default '',
			time_post varchar(14) NOT NULL default '',
			PRIMARY KEY (id)
			) AUTO_INCREMENT=1";
		@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;		
		if ($verificare == 1)
			{
			echo $mesaj[287].$sql_prefix."content'".$mesaj[288];
			}
		else
			{
			echo $mesaj[287].$sql_prefix."content'".$mesaj[289];
			}
		// se creaza tabelul 'content_files'
		$interogare = "DROP TABLE IF EXISTS ".$sql_prefix."content_files";
		@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;				
		$interogare = "CREATE TABLE IF NOT EXISTS ".$sql_prefix."content_files (
			id int(11) NOT NULL auto_increment,
			files_name varchar(35) NOT NULL default '',
			id_content int(11) NOT NULL default '0',
			time varchar(14) NOT NULL default '',
			user varchar(35) NOT NULL default '',
			PRIMARY KEY (id)
			) AUTO_INCREMENT=1";
		@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;		
		if ($verificare == 1)
			{
			echo $mesaj[287].$sql_prefix."content_files'".$mesaj[288];
			}
		else
			{
			echo $mesaj[287].$sql_prefix."content_files'".$mesaj[289];
			}
		// se creaza tabelul 'content_images'
		$interogare = "DROP TABLE IF EXISTS ".$sql_prefix."content_images";
		@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;				
		$interogare = "CREATE TABLE IF NOT EXISTS ".$sql_prefix."content_images (
			id int(11) NOT NULL auto_increment,
			images_name varchar(35) NOT NULL default '',
			id_content int(11) NOT NULL default '0',
			time varchar(14) NOT NULL default '',
			user varchar(35) NOT NULL default '',
			PRIMARY KEY  (id)
			) AUTO_INCREMENT=1";
		@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;		
		if ($verificare == 1)
			{
			echo $mesaj[287].$sql_prefix."content_images'".$mesaj[288];
			}
		else
			{
			echo $mesaj[287].$sql_prefix."content_images'".$mesaj[289];
			}
		// se creaza tabelul 'content_php'
		$interogare = "DROP TABLE IF EXISTS ".$sql_prefix."content_php";
		@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;				
		$interogare = "CREATE TABLE IF NOT EXISTS ".$sql_prefix."content_php (
			id int(11) NOT NULL auto_increment,
			files_name varchar(35) NOT NULL default '',
			id_content int(11) NOT NULL default '0',
			time varchar(14) NOT NULL default '',
			user varchar(35) NOT NULL default '',
			PRIMARY KEY  (id)
			) AUTO_INCREMENT=1";
		@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;		
		if ($verificare == 1)
			{
			echo $mesaj[287].$sql_prefix."content_php'".$mesaj[288];
			}
		else
			{
			echo $mesaj[287].$sql_prefix."content_php'".$mesaj[289];
			}
		// se creaza tabelul 'ip_block'
		$interogare = "DROP TABLE IF EXISTS ".$sql_prefix."ip_block";
		@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;				
		$interogare = "CREATE TABLE ".$sql_prefix."ip_block (
			id int(11) NOT NULL auto_increment,
			ip varchar(15) NOT NULL default '',
			timp varchar(14) NOT NULL default '',
			data_start date NOT NULL default '0000-00-00',
			data_stop date NOT NULL default '0000-00-00',
			PRIMARY KEY  (id)
			) AUTO_INCREMENT=1";
		@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;		
		if ($verificare == 1)
			{
			echo $mesaj[287].$sql_prefix."ip_block'".$mesaj[288];
			}
		else
			{
			echo $mesaj[287].$sql_prefix."ip_block'".$mesaj[289];
			}
		// se creaza tabelul 'robots'
		$interogare = "DROP TABLE IF EXISTS ".$sql_prefix."robots";
		@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;				
		$interogare = "CREATE TABLE IF NOT EXISTS ".$sql_prefix."robots (  			
			id int(11) NOT NULL auto_increment,
			ip varchar(15) NOT NULL default '',
			browser varchar(255) NOT NULL default '',
			referer varchar(255) NOT NULL default '',
			timp varchar(11) NOT NULL default '',
			adresa varchar(255) NOT NULL default '',
			PRIMARY KEY  (id)
			) AUTO_INCREMENT=1";
		@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;		
		if ($verificare == 1)
			{
			echo $mesaj[287].$sql_prefix."robots'".$mesaj[288];
			}
		else
			{
			echo $mesaj[287].$sql_prefix."robots'".$mesaj[289];
			}
		// se creaza tabelul 'menu'
		$interogare = "DROP TABLE IF EXISTS ".$sql_prefix."menu";
		@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;				
		$interogare = "CREATE TABLE IF NOT EXISTS ".$sql_prefix."menu (
			id int(11) NOT NULL auto_increment,
			html text NOT NULL,
			type int(11) NOT NULL default '0',
			priority int(11) NOT NULL default '0',
			location int(11) NOT NULL default '0',
			language varchar(30) NOT NULL default '',
			status char(2) NOT NULL default '',
			PRIMARY KEY  (id)
			) AUTO_INCREMENT=1";
		@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;		
		if ($verificare == 1)
			{
			echo $mesaj[287].$sql_prefix."menu'".$mesaj[288];
			}
		else
			{
			echo $mesaj[287].$sql_prefix."menu'".$mesaj[289];
			}
		// se salveaza meniurile predefinite
		if ($_SESSION['limbaj_temporar_far'] == "ro")
			{
			$m0 = mysql_real_escape_string('<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  					<tr><td><div align="center">Pentru a modifica sau sterge pagina principala intrati in modulul de modificare 
					a continutului si selectati din lista pagina cu numele &quot;default&quot;.<br>
      				Pentru alte modificari cititi manualul. <br>
      				Atentie! Pagina &quot;default&quot; trebuie sa existe pentru fiecare limbaj si in &quot;ro&quot; si in &quot;en&quot;
					 cu acelasi nume. </div></td>
  					</tr></table>',$conectare); 
			$m1 = mysql_real_escape_string('<table width="100%" border="0" cellspacing="1" cellpadding="0">
				<tr><td width="20%"><div align="center"><a href="index.php" class="glink">HOME</a></div></td>
				<td width="20%"><div align="center"><a href="admin.php?m=menu" class="glink">MENIURI</a></div></td>
				<td width="20%"><div align="center"><a href="index.php?p=content" class="glink">CONTINUT</a></div></td>
				<td width="20%"><div align="center"><a href="admin.php?m=ch_template" class="glink">TEMPLATE</a></div></td>
				<td width="20%"><div align="center"><a href="admin.php?m=cpanel" class="glink">PANOU CONTROL</a></div></td>
				</table>',$conectare); 				
			$m2 = mysql_real_escape_string('<table width="100%" border="0" cellspacing="1" cellpadding="0">
				<tr><td bgcolor="#000000"><div align="center"><font color="#FFFFFF"><strong>Schimba tema</strong></font></div></td>
				</tr><tr><td><div align="center"><a href="admin.php?m=ch_template" class="glink">Alege alt desing la site</a></div></td>
				</tr><tr><td></td></tr></table>',$conectare);
			$m3 = mysql_real_escape_string('<table width="100%" border="0" cellspacing="1" cellpadding="0">
				<tr><td bgcolor="#000000"><div align="center"><font color="#FFFFFF"><strong>Continut</strong></font></div></td></tr>				
				<tr><td><div align="center"><a href="index.php?p=content" class="glink">Cuprins</a></div></td></tr>
				<tr><td><div align="center"><a href="index.php?c=help_ro" class="glink">Manualul FAR-PHP</a></div></td></tr>
				<tr><td><div align="center"><a href="index.php?c=demo_page" class="glink">Pagina demo</a></div></td></tr>
				<tr><td><div align="center"><a href="index.php?c=viev_user" class="glink">Useri inscrisi</a></div></td></tr>
				<tr><td><div align="center"><a href="index.php?c=contact" class="glink">Contact</a></div></td></tr>
				</table>',$conectare);
			$m4 = mysql_real_escape_string('<table width="100%" border="0" cellspacing="1" cellpadding="0">
				<tr><td bgcolor="#000000"><div align="center"><font color="#FFFFFF"><strong>Meniu Modul login</strong></font></div></td></tr>
				<tr><td><div align="center"><a href="admin.php?m=login_new&action=new_user" class="glink">User nou </a></div></td></tr>
				<tr><td><div align="center"><a href="admin.php?m=login_new&action=new_pass" class="glink">Schimbare parola </a></div></td></tr>
				<tr><td><div align="center"><a href="admin.php?m=login_new&action=new_right" class="glink">Schimbare drepturi </a></div></td></tr>
				<tr><td><div align="center"><a href="admin.php?m=login_new&action=new_del" class="glink">Stergere user </a></div></td></tr>
				<tr><td><div align="center"><a href="admin.php?m=login_new&action=dec" class="glink">Deconectare</a></div></td></tr>
				</table>',$conectare);
			$m5 = mysql_real_escape_string('<table width="100%" border="0" cellspacing="1" cellpadding="0">
				<tr><td bgcolor="#000000"><div align="center"><font color="#FFFFFF"><strong>Meniu Module </strong></font></div></td></tr>
				<tr><td><div align="center"><a href="admin.php?m=login" class="glink">Modulul de logare </a></div></td></tr>
				<tr><td><div align="center"><a href="admin.php?m=menu" class="glink">Modulul de meniuri </a></div></td></tr>
				<tr><td><div align="center"><a href="admin.php?m=content" class="glink">Modulul de continut </a></div></td></tr>
				<tr><td><div align="center"><a href="admin.php?m=content_2" class="glink">Modulul de modificat continut</a></div></td></tr>
				<tr><td><div align="center"><a href="admin.php?m=ch_template" class="glink">Modulul de teme </a></div></td></tr>
				<tr><td><div align="center"><a href="admin.php?m=cpanel" class="glink">PANOU CONTROL</a></div></td></tr>
				</table>',$conectare);
			$interogare = 'INSERT INTO '.$sql_prefix.'content (name_content, title, content, language_content, author_content, email_author,
				user_post, time_post) VALUES ("default", "Pagina principala", "'.$m0.'", "ro", "Echipa FAR-PHP", "contact@far-php.ro", 
				"'.$admin_user_admin.'", "'.time().'")';
			$interogare = 'INSERT INTO '.$sql_prefix.'menu (id, html, type, priority, location, language, status) 
				VALUES (1, "'.$m1.'", 1, 1, 2, "ro", 6)';
			@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;				
			$interogare = 'INSERT INTO '.$sql_prefix.'menu (id, html, type, priority, location, language, status) 
				VALUES (2, "'.$m2.'", 2, 1, 2, "ro", 6)';
			@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;	
			$interogare = 'INSERT INTO '.$sql_prefix.'menu (id, html, type, priority, location, language, status) 
				VALUES (3, "'.$m3.'", 2, 1, 1, "ro", 6)';
			@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;	
			$interogare = 'INSERT INTO '.$sql_prefix.'menu (id, html, type, priority, location, language, status) 
				VALUES (4, "'.$m4.'", 2, 2, 2, "ro", 5)';
			@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;	
			$interogare = 'INSERT INTO '.$sql_prefix.'menu (id, html, type, priority, location, language, status) 
				VALUES (5, "'.$m5.'", 2, 3, 2, "ro", 5)';
			@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;
			}
		if ($_SESSION['limbaj_temporar_far'] == "en")
			{
			$m0 = mysql_real_escape_string('<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  					<tr><td><div align="center">This is &quot;default&quot; pages. 
					To modify/erase go to on <a href="admin.php?m=content_2">admin.php?m=content_2</a><br>
      				For other read FAR-PHP manual. <br>
      				Attention! Page &quot;default&quot; must exist on each language (&quot;ro&quot; and &quot;en&quot;) 
					 with same name. </div></td>
  					</tr></table>',$conectare); 
			$m1 = mysql_real_escape_string('<table width="100%" border="0" cellspacing="1" cellpadding="0">
				<tr><td width="20%"><div align="center"><a href="index.php" class="glink">HOME</a></div></td>
				<td width="20%"><div align="center"><a href="admin.php?m=menu" class="glink">MENU</a></div></td>
				<td width="20%"><div align="center"><a href="index.php?p=content" class="glink">ARTICLES</a></div></td>
				<td width="20%"><div align="center"><a href="admin.php?m=ch_template" class="glink">THEMES</a></div></td>
				<td width="20%"><div align="center"><a href="admin.php?m=cpanel" class="glink">CONTROL PANEL</a></div></td>
				</table>',$conectare); 				
			$m2 = mysql_real_escape_string('<table width="100%" border="0" cellspacing="1" cellpadding="0">
				<tr><td bgcolor="#000000"><div align="center"><font color="#FFFFFF"><strong>Change themes</strong></font></div></td>
				</tr><tr><td><div align="center"><a href="admin.php?m=ch_template" class="glink">Choose other design</a></div></td>
				</tr><tr><td></td></tr></table>',$conectare);
			$m3 = mysql_real_escape_string('<table width="100%" border="0" cellspacing="1" cellpadding="0">
				<tr><td bgcolor="#000000"><div align="center"><font color="#FFFFFF"><strong>Content</strong></font></div></td></tr>				
				<tr><td><div align="center"><a href="index.php?p=content" class="glink">Included</a></div></td></tr>
				<tr><td><div align="center"><a href="index.php?c=help_en" class="glink">Help manual</a></div></td></tr>
				<tr><td><div align="center"><a href="index.php?c=demo_page" class="glink">Demo pages</a></div></td></tr>
				<tr><td><div align="center"><a href="index.php?c=viev_user" class="glink">See users</a></div></td></tr>
				<tr><td><div align="center"><a href="index.php?c=contact" class="glink">Contact</a></div></td></tr>
				</table>',$conectare);
			$m4 = mysql_real_escape_string('<table width="100%" border="0" cellspacing="1" cellpadding="0">
				<tr><td bgcolor="#000000"><div align="center"><font color="#FFFFFF"><strong>Login modules </strong></font></div></td></tr>
				<tr><td><div align="center"><a href="admin.php?m=login_new&action=new_user" class="glink">New users</a></div></td></tr>
				<tr><td><div align="center"><a href="admin.php?m=login_new&action=new_pass" class="glink">Change password</a></div></td></tr>
				<tr><td><div align="center"><a href="admin.php?m=login_new&action=new_right" class="glink">Change user right</a></div></td></tr>
				<tr><td><div align="center"><a href="admin.php?m=login_new&action=new_del" class="glink">Delete user</a></div></td></tr>
				<tr><td><div align="center"><a href="admin.php?m=login_new&action=dec" class="glink">Disconnect</a></div></td></tr>
				</table>',$conectare);
			$m5 = mysql_real_escape_string('<table width="100%" border="0" cellspacing="1" cellpadding="0">
				<tr><td bgcolor="#000000"><div align="center"><font color="#FFFFFF"><strong>All modules</strong></font></div></td></tr>
				<tr><td><div align="center"><a href="admin.php?m=login" class="glink">Login modules</a></div></td></tr>
				<tr><td><div align="center"><a href="admin.php?m=menu" class="glink">Menu modules</a></div></td></tr>
				<tr><td><div align="center"><a href="admin.php?m=content" class="glink">Content modules</a></div></td></tr>
				<tr><td><div align="center"><a href="admin.php?m=content_2" class="glink">Change content modules</a></div></td></tr>
				<tr><td><div align="center"><a href="admin.php?m=ch_template" class="glink">Template modules</a></div></td></tr>
				<tr><td><div align="center"><a href="admin.php?m=cpanel" class="glink">CONTROL PANEL</a></div></td></tr>
				</table>',$conectare);
			$interogare = 'INSERT INTO '.$sql_prefix.'content (name_content, title, content, language_content, author_content, email_author,
				user_post, time_post) VALUES ("default", "Index pages", "'.$m0.'", "en", "The FAR-PHP team", "contact@far-php.ro", 
				"'.$admin_user_admin.'", "'.time().'")';
			$interogare = 'INSERT INTO '.$sql_prefix.'menu (id, html, type, priority, location, language, status) 
				VALUES (1, "'.$m1.'", 1, 1, 2, "en", 6)';
			@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;				
			$interogare = 'INSERT INTO '.$sql_prefix.'menu (id, html, type, priority, location, language, status) 
				VALUES (2, "'.$m2.'", 2, 1, 2, "en", 6)';
			@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;	
			$interogare = 'INSERT INTO '.$sql_prefix.'menu (id, html, type, priority, location, language, status) 
				VALUES (3, "'.$m3.'", 2, 1, 1, "en", 6)';
			@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;	
			$interogare = 'INSERT INTO '.$sql_prefix.'menu (id, html, type, priority, location, language, status) 
				VALUES (4, "'.$m4.'", 2, 2, 2, "en", 5)';
			@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;	
			$interogare = 'INSERT INTO '.$sql_prefix.'menu (id, html, type, priority, location, language, status) 
				VALUES (5, "'.$m5.'", 2, 3, 2, "en", 5)';
			@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;
			}
		if ($verificare == 1)
			{			
			echo $mesaj[290];
			}
		else
			{
			echo $mesaj[291];
			}
		// se creaza tabelul 'temp'
		$interogare = "DROP TABLE IF EXISTS ".$sql_prefix."temp";
		@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;				
		$interogare = "CREATE TABLE IF NOT EXISTS ".$sql_prefix."temp (
				id int(11) NOT NULL auto_increment,
				user varchar(15) NOT NULL default '',
				parola varchar(35) NOT NULL default '',
				email varchar(25) NOT NULL default '',
				timp varchar(14) NOT NULL default '',
				ip varchar(15) NOT NULL default '',
				PRIMARY KEY  (id)
				) AUTO_INCREMENT=1";
		@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;		
		if ($verificare == 1)
			{
			echo $mesaj[287].$sql_prefix."temp'".$mesaj[288];
			}
		else
			{
			echo $mesaj[287].$sql_prefix."temp'".$mesaj[289];
			}
		// se creaza tabelul 'user'
		$interogare = "DROP TABLE IF EXISTS ".$sql_prefix."user";
		@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;				
		$interogare = "CREATE TABLE IF NOT EXISTS ".$sql_prefix."user (
				nr int(11) NOT NULL auto_increment,
				user varchar(15) NOT NULL default '',
				parola varchar(35) NOT NULL default '',
				stare int(2) NOT NULL default '0',
				email varchar(25) NOT NULL default '',
				PRIMARY KEY  (nr)
				) AUTO_INCREMENT=1";
		@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;		
		if ($verificare == 1)
			{
			echo $mesaj[287].$sql_prefix."user'".$mesaj[288];
			}
		else
			{
			echo $mesaj[287].$sql_prefix."user'".$mesaj[289];
			}
		// se salveaza userii definiti
		// se salveaza userul cu drepturi de admin
		if ($admin_cript == "da")
			{
			$parola_admin = md5($admin_parola_admin_1);
			}
		else
			{
			$parola_admin = $admin_parola_admin_1;
			}
		$interogare = "INSERT INTO ".$sql_prefix."user (nr, user, parola, stare, email) VALUES 
				(1, '".$admin_user_admin."', '".$parola_admin."', 1, '".$admin_email_admin."')";
		@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;				
		if ($verificare == 1)
			{
			echo $mesaj[292];
			}
		else
			{
			echo $mesaj[293];
			}
		// se salveaza userul cu drepturi de sub-admin
		if (!empty($admin_user_subadmin))
			{
			if ($admin_cript == "da")
				{
				$parola_subadmin = md5($admin_parola_subadmin_1);
				}
			else
				{
				$parola_subadmin = $admin_parola_subadmin_1;
				}
			$interogare = "INSERT INTO ".$sql_prefix."user (nr, user, parola, stare, email) VALUES 
				(2, '".$admin_user_subadmin."', '".$parola_subadmin."', 1, '".$admin_email_subadmin."')";
			@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;				
			if ($verificare == 1)
				{
				echo $mesaj[294];
				}
			else
				{
				echo $mesaj[295];
				}
			}
		// se creaza tabelul 'ver'
		$interogare = "DROP TABLE IF EXISTS ".$sql_prefix."ver";
		@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;				
		$interogare = "CREATE TABLE IF NOT EXISTS ".$sql_prefix."ver (
				ver varchar(15) NOT NULL default '',
				time_install varchar(11) NOT NULL default '',
				address varchar(100) NOT NULL default '',
				name varchar(35) NOT NULL default '',
				email varchar(35) NOT NULL default '',
				language varchar(15) NOT NULL default '',
				status int(2) NOT NULL default '0',
				mesages text NOT NULL,
				codes varchar(10) NOT NULL default '')";
		@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;		
		if ($verificare == 1)
			{
			echo $mesaj[287].$sql_prefix."ver'".$mesaj[288];
			}
		else
			{
			echo $mesaj[287].$sql_prefix."ver'".$mesaj[289];
			}
		// se salveaza datele de versiune
		$versiune_far = "1.0";
		$timp_instalare = time();
		$data_instalarii = date("d-m-Y H:i", $timp_instalare);
		$stare_far = "0";
		$mesaj_far = 'The logos and trademarks used on this site are the property of their respective owners
				<br>We are not responsible for comments posted by our users, as they are the property of the poster
				<br>Web site engine\'s code is copyright &copy; 2004 - 2005 by <a href="http://www.far-php.ro" target="_blank">FAR-PHP</a>
				<br>Released under the GNU GPL License.<br>
				 _._ This pages used code from <a href="http://www.far-php.ro" target="_blank">FAR-PHP</a> project, but it\'s not consideration
				 of any legals term, because do not respect original copyright.<br>
				 Please send e-mail to <a href="mailto:contact@far-php.ro?Subject=For FAR-PHP project&body=Your messages">FAR-PHP</a> team,
				 and tell as about this pages.
				  _._ <br>'.$admin_mesaj.'
				  _._ contact@far-php.ro
				  _._ A fost instalat FAR-PHP';
		$mesaj_far = mysql_real_escape_string($mesaj_far, $conectare);		
		// incarcarea fisierului cu functii pentru pagina
		if (file_exists("codes/functions.php"))
			{
			include_once ("codes/functions.php");
			}
		if (!file_exists("codes/functions.php"))
			{
			die($mesaje_index[3]."codes/functions.php");
			}
		$cod_far = gen_pass(10,FALSE,null);
		$interogare = "INSERT INTO ".$sql_prefix."ver (ver, time_install, address, name, email, language, status, mesages, codes) 
			VALUES ('".$versiune_far."', '".$timp_instalare."', '".$server_web."', '".$admin_user_admin."', '".$admin_email_admin."', 
			'".$server_limbaj."', '".$stare_far."', '".$mesaj_far."', '".$cod_far."')";
		@$rezultat = mysql_query($interogare, $conectare) OR $verificare = 1;
		if ($verificare == 1)
			{
			echo $mesaj[296];
			}
		else
			{
			echo $mesaj[297];
			}
		mysql_close($conectare);
		
		$mail_adres = "contact@far-php.ro";
		$ip_acum = $_SERVER['REMOTE_ADDR'];
		$host_acum = @$_SERVER['REMOTE_HOST'];
		$browser_acum = $_SERVER['HTTP_USER_AGENT'];
		$mesaj_mail = $mesaj[195];
		$header_mail = "MIME-Version: 1.0\r\nContent-type: text/html; charset=iso-8859-2\r\n";		
		$detalii_mail = "<br>
				<br>Versiune program: ".$versiune_far."
				<br>Data instalarii: ".$data_instalarii."
				<br>Adresa: ".$server_web."
				<br>Nume admin: ".$admin_user_admin."
				<br>E-mail admin: ".$admin_email_admin."
				<br>Stare program: ".$stare_far."
				<br>Verificare: ".$cod_far."
				<br>Adresa IP: ".$ip_acum."	
				<br>Gazda server: ".$host_acum."
				<br>Browser: ".$browser_acum."
				<br>Limbaj: ".$server_limbaj."
				<br>Data mesajului: ".$data_instalarii."
				<br><br>";
		@mail($mail_adres, $mesaj_mail, $detalii_mail, $header_mail);
		if ($verificare == 1)
			{
			echo '<META HTTP-EQUIV = "Refresh" Content = "5; URL ='.$server_web.'">'.$mesaj[298];
			}
		if ($verificare == 0) // daca totul e ok pana aici
			{		
			// se incearca scrierea fisierului config.php
			$continut = '<?php
'.$mesaj[299].'

'.$mesaj[300].'
$server_bd = "'.$sql_host.'"; '.$mesaj[301].'
$user_bd = "'.$sql_user.'"; '.$mesaj[302].'
$parola_bd = "'.$sql_parola_1.'"; '.$mesaj[303].'
$nume_bd = "'.$sql_bd.'"; '.$mesaj[304].'
$prefix_tabel_bd = "'.$sql_prefix.'"; '.$mesaj[305].'

'.$mesaj[313].'
$prefix_sesiuni = "'.$server_prefix.'"; '.$mesaj[306].'
$diferenta_de_ora = "'.$server_ora.'"; '.$mesaj[307].'
$diferenta_de_ora_2 = "'.$server_ora_dif.'"; '.$mesaj[308].'
$adresa_url = "'.$server_web.'"; '.$mesaj[309].'
$pagina_finala = "'.$server_tema.'.php"; '.$mesaj[310].'
$pagina_deconectare = "index.php"; '.$mesaj[311].'
$mesaje = "codes/language_'.$server_limbaj.'.php"; '.$mesaj[312].'

'.$mesaj[314].'
$functii = "codes/functions.php"; '.$mesaj[315].'
$ip_stop = array("0.0.0.0", "255.255.255.255", "0.0.0.1"); '.$mesaj[316].'
$parola_criptata = "'.$admin_cript.'"; '.$mesaj[317].'
$nr_incercari = "'.$admin_log.'"; '.$mesaj[318].'
$email_admin = "'.$admin_email_admin.'"; '.$mesaj[319].'
$email_moderator = "'.$admin_email_subadmin.'";	'.$mesaj[320].'
$limbaj_primar = "'.$server_limbaj.'"; '.$mesaj[321].'
$chestii_copyright = \''.$admin_mesaj.'\'; '.$mesaj[322].'

?>';
			// se verifica daca exista fisierul de configurare			
			if (file_exists("config.php"))
				{	
				if (is_writable("config.php"))
					{					
					$fisier = fopen("config.php", "w+") OR $verificare = 1;
					$scriere = fwrite($fisier, $continut) OR $verificare = 1;
					fclose($fisier);
					if ($verificare == 0)
						{
						echo '<META HTTP-EQUIV = "Refresh" Content = "5; URL ='.$server_web.'">'.$mesaj[323];
						}
					if ($verificare != 0)						
						{
						echo $mesaj[324];
						echo $mesaj[325];
						echo $mesaj[326];				
						echo "<pre>".htmlspecialchars($continut)."</pre>";
						}
					}
				if (!is_writable("config.php"))
					{
					if (@chmod("config.php", 777) == TRUE)
						{
						if (is_writable("config.php"))
							{
							$fisier = fopen("config.php", "w+") OR $verificare = 1;
							$scriere = fwrite($fisier, $continut) OR $verificare = 1;
							fclose($fisier);
							if ($verificare == 0)
								{
								@chmod("config.php", 664);
								echo '<META HTTP-EQUIV = "Refresh" Content = "5; URL ='.$server_web.'">'.$mesaj[323];
								}
							if ($verificare != 0)
								{
								echo $mesaj[324];
								echo $mesaj[325];
								echo $mesaj[326];				
								echo "<pre>".htmlspecialchars($continut)."</pre>";
								/*
								@header('Content-type: application/txt');
								@header('Content-Disposition: attachment; filename="config.php.txt"');
								@readfile($continut);
								*/
								}
							}
						if (!is_writable("config.php"))
							{
							echo $mesaj[324];
							echo $mesaj[325];
							echo $mesaj[326];				
							echo "<pre>".htmlspecialchars($continut)."</pre>";
							}
						}
					if (@chmod("config.php", 774) == FALSE)
						{
						echo $mesaj[324];
						echo $mesaj[325];
						echo $mesaj[326];				
						echo "<pre>".htmlspecialchars($continut)."</pre>";
						}
					}
				}
			if (!file_exists("config.php"))
				{	
				echo $mesaj[324];
				echo $mesaj[325];
				echo $mesaj[326];				
				echo "<pre>".htmlspecialchars($continut)."</pre>";
				}
			}
		// daca e ok se afiseaza mesaj de ok si se redirecteaza
		// daca nu e ok se genereaza fisierul config.php
		// se asteapta confirmarea punerii fisierului
		// se redirecteaza
		}
	}	
?>