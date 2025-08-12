<?php 
/* =====================================================================
*	Pagina login_ver.php (parte din modulul de login)
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

// initializare variabile de eroare
$verificare = 0;
// verificare trimitere valori post
if(isset($_POST['logare'])) // verificare daca a fost apasat butonul din formular
	{
	// preluare valori post
	$user = $_POST['user'];
	$parola = $_POST['parola'];	
	// initializare variabile pentru bd
	$data_curenta = date ("Y-m-d", time());
	$ora_curenta = date ("H:i:s", time());
	$timp_curent = time();
	$ip = $_SERVER['REMOTE_ADDR'];
	// $host = gethostbyaddr($ip);
	$browser = $_SERVER['HTTP_USER_AGENT'];
	// verificare user si parola corecte
	if(empty($user)) // daca e fara user
		{
		echo $mesaj[41];
		$verificare = 1;
		}
	if(empty($parola)) // daca e fara parola
		{
		echo $mesaj[42];
		$verificare = 1;
		}
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
		
	// daca totul e ok se conecteaza la sql pentru a verifica user si parola din bd
	if($verificare == 0) // daca eroarea de php de logare e ok...
		{
		// accesare bd			
		$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]." 1");
		mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]." 1");
		
		if ($nr_incercari != 0) // daca numarul de logari esuate nu e infinit se verifica de cate ori a incercat locarea in ultimele 5 min
			{						
			$comanda_sql = "SELECT count(*) FROM ".$prefix_tabel_bd."connect WHERE user='".$user."'";			
			$interogare_sql = mysql_query($comanda_sql) OR die($mesaj[9]." 1");
			$tabelare_rezultat_sql = mysql_fetch_array($interogare_sql) OR die($mesaj[4]." 1");
			@$rezultat_sql = $tabelare_rezultat_sql[0].$tabelare_rezultat_sql[1].$tabelare_rezultat_sql[2];
			if ($rezultat_sql >= $nr_incercari) // daca sa incercat logarea de mai multe ori decat e setat se opreste
				{
				$verificare = 1;
				echo $mesaj[49];
				}
			$comanda_sql = "INSERT INTO ".$prefix_tabel_bd."connect (user, parola, data, ora, timp, ip, browser) VALUES 
					('".$user."', '".$parola."', '".$data_curenta."', '".$ora_curenta."', '".$timp_curent."', 
					'".$ip."', '".$browser."')";			
			// echo $comanda_sql;
			$introducere_date_sql = mysql_query($comanda_sql) OR die($mesaj[4]." 2");
			}
		if($verificare == 0)
			{			
			$comanda_sql = "SELECT * FROM ".$prefix_tabel_bd."user";
			$interogare_sql = mysql_query($comanda_sql) OR die($mesaj[4]." 3");
			if ($parola_criptata == "da") // daca parola din sql este criptata se cripteaza si parola introdusa de user pentru a o putea verifica...
				{
				$parola = md5($_POST['parola']);
				}
			// daca user si parola corespunde se verifica ip-ul
			$nr = 0;
			foreach($ip_stop as $valoare)
				{
				// echo "<br>$ip_stop[$nr]";
				if($ip == $ip_stop[$nr]) // daca ip-ul vizitatorului e oprit...
					{
					$verificare = 1;
					echo $mesaj[50];
					}
				$nr++;
				}				
			while ($rand=mysql_fetch_array($interogare_sql)) 
				{ 
				//echo "<br>Se verifica ".$rand['user']." - ".$user;
				//echo "<br>Parola: ".$rand['parola']." => ".$parola;
				if (($rand['user'] == $user) AND ($rand['parola'] == $parola)) // daca user si parola sunt la fel cu cele din sql
					{
					$verificare =0;
					$stare = $rand['stare']; // se initializeaza variabila de meniu cu meniul pentru user
					$email_user = $rand['email'];							
					break;
					}
				else // altfel da eroare de sql
					{					
					$verificare = 1;
					}
				}	
			if ($verificare == 1)
				{
				echo $mesaj[52];
				}			
			}
		}
	// daca exista erori se verifica si se revine la formularul de login
	
	// daca in sql totul e ok si celelalte erori sunt ok se creaza sesiunea
	if($verificare == 0)
		{
		// stergere incercari nereusite
		$comanda_sql = "DELETE FROM ".$prefix_tabel_bd."connect";
		$interogare_sql = mysql_query($comanda_sql) OR die($mesaj[4]." 4");
		// creare sesiune		
		$_SESSION[$prefix_sesiuni.'_cheia_far'] = session_id();		
		// adaugare la versiunea 1.0
		if (isset($_POST['tip']))
			{
			$tip = $_POST['tip'];			
			}
		if (!isset($_POST['tip']))
			{
			$tip = "0";
			}
		
		if ($tip == "permanent")
			{
			$permanent = "1";
			$ascuns = "0";
			}
		if ($tip == "ascuns")
			{
			$permanent = "0";
			$ascuns = "1";
			}
		if ($tip == "0")
			{
			$permanent = "0";
			$ascuns = "0";
			}
			
		if ($permanent == "1")
			{
			if ($ascuns == "1")
				{
				debug_mesaje(29);
				$_SESSION[$prefix_sesiuni.'_user_far'] = $mesaj[330]; // seteaza numele userului ascuns
				$_SESSION[$prefix_sesiuni.'_rights_far'] = $stare;
				$_SESSION[$prefix_sesiuni.'_email_far'] = $email_user;
				$_SESSION[$prefix_sesiuni.'_hidden_far'] = 1; // seteaza starea userului sa fie ascuns
				$_SESSION[$prefix_sesiuni.'_permanently_far'] = 1; // seteaza starea logarii permanenta
				}
			if ($ascuns != "1")
				{
				debug_mesaje(30);
				$_SESSION[$prefix_sesiuni.'_user_far'] = $user; // seteaza numele real al userului
				$_SESSION[$prefix_sesiuni.'_rights_far'] = $stare;			
				$_SESSION[$prefix_sesiuni.'_email_far'] = $email_user;
				$_SESSION[$prefix_sesiuni.'_hidden_far'] = 0; // seteaza starea userului sa fie normala
				$_SESSION[$prefix_sesiuni.'_permanently_far'] = 1; // seteaza starea logarii permanenta
				}				
			}
		if ($permanent != "1")
			{
			if ($ascuns == "1")
				{
				debug_mesaje(31);
				$_SESSION[$prefix_sesiuni.'_user_far'] = $mesaj[330]; // seteaza numele userului ascuns
				$_SESSION[$prefix_sesiuni.'_rights_far'] = $stare;			
				$_SESSION[$prefix_sesiuni.'_email_far'] = $email_user;
				$_SESSION[$prefix_sesiuni.'_hidden_far'] = 1; // seteaza starea userului sa fie ascuns
				$_SESSION[$prefix_sesiuni.'_permanently_far'] = 0; // seteaza starea logarii temporara
				}
			if ($ascuns != "1")
				{
				debug_mesaje(32);
				$_SESSION[$prefix_sesiuni.'_user_far'] = $user; // seteaza numele real al userului
				$_SESSION[$prefix_sesiuni.'_rights_far'] = $stare;			
				$_SESSION[$prefix_sesiuni.'_email_far'] = $email_user;
				$_SESSION[$prefix_sesiuni.'_hidden_far'] = 0; // seteaza starea userului sa fie normala
				$_SESSION[$prefix_sesiuni.'_permanently_far'] = 0; // seteaza starea logarii temporara
				}						
			}
		if ($parola_criptata != "da")
			{
			$parola = md5($_POST['parola']);
			}
		// se seteaza cooke in functie de datele de sesiune
		if ($_SESSION[$prefix_sesiuni.'_permanently_far'] == 1)
			{			
			// se seteaza timpul de expirare al cookeului ca fiind permanent
			$expira = 1*60*60*24*100; // in acest caz expira dupa 100 de zile
			$timp_expirare = time()+$expira;
			if (isset($_COOKIE[$prefix_sesiuni.'_far']))
				{
				debug_mesaje(33);
				$prelucrare_cooke = stripslashes($_COOKIE[$prefix_sesiuni.'_far']);
				$valoare_cookie = unserialize($prelucrare_cooke);
				$valoare_cookie['user_far'] = $_SESSION[$prefix_sesiuni.'_user_far'];
				$valoare_cookie['email_far'] = $_SESSION[$prefix_sesiuni.'_email_far'];				
				$valoare_cookie['password_far'] = $parola;
				$valoare_cookie['hidden_far'] = $_SESSION[$prefix_sesiuni.'_hidden_far'];
				$valoare_cookie['permanently_far'] = $_SESSION[$prefix_sesiuni.'_permanently_far'];
				$val_coke = serialize($valoare_cookie);	
				setcookie($prefix_sesiuni."_far", $val_coke, $timp_expirare, "/");
				}
			if (!isset($_COOKIE[$prefix_sesiuni.'_far']))
				{
				debug_mesaje(34);
				$valoare_cookie['language_far'] = $_SESSION[$prefix_sesiuni.'_language_far'];
				$valoare_cookie['themes_far'] = $_SESSION[$prefix_sesiuni.'_themes_far'];
				$valoare_cookie['user_far'] = $_SESSION[$prefix_sesiuni.'_user_far'];
				$valoare_cookie['email_far'] = $_SESSION[$prefix_sesiuni.'_email_far'];				
				$valoare_cookie['password_far'] = $parola;
				$valoare_cookie['hidden_far'] = $_SESSION[$prefix_sesiuni.'_hidden_far'];
				$valoare_cookie['permanently_far'] = $_SESSION[$prefix_sesiuni.'_permanently_far'];
				$val_coke = serialize($valoare_cookie);	
				setcookie($prefix_sesiuni."_far", $val_coke, $timp_expirare, "/");
				}
			}
		if ($_SESSION[$prefix_sesiuni.'_permanently_far'] == 0)
			{
			// se seteaza timpul de expirare al cookeului ca fiind temporara			
			$timp_expirare = time()+3600; // in acest caz expira dupa 1 ora
			if (isset($_COOKIE[$prefix_sesiuni.'_far']))
				{
				debug_mesaje(35);
				$prelucrare_cooke = stripslashes($_COOKIE[$prefix_sesiuni.'_far']);
				$valoare_cookie = unserialize($prelucrare_cooke);
				$valoare_cookie['user_far'] = $_SESSION[$prefix_sesiuni.'_user_far'];
				$valoare_cookie['email_far'] = $_SESSION[$prefix_sesiuni.'_email_far'];				
				$valoare_cookie['password_far'] = $parola;
				$valoare_cookie['hidden_far'] = $_SESSION[$prefix_sesiuni.'_hidden_far'];
				$valoare_cookie['permanently_far'] = $_SESSION[$prefix_sesiuni.'_permanently_far'];
				$val_coke = serialize($valoare_cookie);	
				setcookie($prefix_sesiuni."_far", $val_coke, $timp_expirare, "/");
				}
			if (!isset($_COOKIE[$prefix_sesiuni.'_far']))
				{
				debug_mesaje(36);
				$valoare_cookie['language_far'] = $_SESSION[$prefix_sesiuni.'_language_far'];
				$valoare_cookie['themes_far'] = $_SESSION[$prefix_sesiuni.'_themes_far'];
				$valoare_cookie['user_far'] = $_SESSION[$prefix_sesiuni.'_user_far'];
				$valoare_cookie['email_far'] = $_SESSION[$prefix_sesiuni.'_email_far'];				
				$valoare_cookie['password_far'] = $parola;
				$valoare_cookie['hidden_far'] = $_SESSION[$prefix_sesiuni.'_hidden_far'];
				$valoare_cookie['permanently_far'] = $_SESSION[$prefix_sesiuni.'_permanently_far'];
				$val_coke = serialize($valoare_cookie);	
				setcookie($prefix_sesiuni."_far", $val_coke, $timp_expirare, "/");
				}
			}
		
		echo '<META HTTP-EQUIV = "Refresh" Content = "0; URL =index.php">'.$mesaj[54];
		}
	if($verificare == 1)
		{
		include_once("login.php");
		}
	}
if(!isset($_POST['logare'])) // daca nu a fost apasat butonul din formular probabil datele au fost trimise altfel si da eroare pentru protectie
	{
	echo $mesaj[55].$mesaj[53];		
	echo "<br><br>";
	//include("login.php");
	}
?>