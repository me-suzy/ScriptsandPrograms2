<?php
/* =====================================================================
*	Pagina functions.php
*	Creat de Birkoff pentru proiectul FAR-PHP
*	Versiune: 0.01
*	Copyright: (C) 2004 - 2005 The FAR-PHP Group
*	E-mail: contact@far-php.ro
*	Data inceperii paginii: 28-12-2004	
*	Ultima modificare: 24-05-2005
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

// functie pentru prevenirea Mysql Injection
function block_mysql_injection()
	{
	global $adresa_url;
	$host = parse_url($adresa_url);
	$url_formular = parse_url($_SERVER['HTTP_REFERER']); 
	if ($url_formular['host'] == $host['host']) 
		{
		return 0;
		}
	else
		{
		return 1;
		}
	}
	
// setarea functiei care returneaza dreptul userului
function drepturi_far()
	{
	global $prefix_sesiuni;
	global $server_bd;
	global $user_bd;
	global $parola_bd;
	global $nume_bd;
	global $prefix_tabel_bd;
	global $parola_criptata;
	
	if (isset($_COOKIE[$prefix_sesiuni.'_far']))
		{
		$prelucrare_cooke = stripslashes($_COOKIE[$prefix_sesiuni.'_far']);
		$valoare_cookie = unserialize($prelucrare_cooke);
		// user
		if (isset($valoare_cookie['user_far']))
			{
			if (!empty($valoare_cookie['user_far']))
				{
				$user = $valoare_cookie['user_far'];
				}
			if (empty($valoare_cookie['user_far']))
				{
				$user = '';
				}
			}
		if (!isset($valoare_cookie['user_far']))
			{
			$user = '';
			}
		// email
		if (isset($valoare_cookie['email_far']))
			{
			if (!empty($valoare_cookie['email_far']))
				{
				$email = $valoare_cookie['email_far'];
				}
			if (empty($valoare_cookie['email_far']))
				{
				$email = '';
				}
			}
		if (!isset($valoare_cookie['email_far']))
			{
			$email = '';
			}
		// parola
		if (isset($valoare_cookie['password_far']))
			{
			if (!empty($valoare_cookie['password_far']))
				{
				$password = $valoare_cookie['password_far'];
				}
			if (empty($valoare_cookie['password_far']))
				{
				$password = '';
				}
			}
		if (!isset($valoare_cookie['password_far']))
			{
			$password = '';
			}
		// verificare		
		if (!empty($user))
			{
			if (!empty($email))
				{
				if (!empty($password))
					{					
					// interogare bd
					$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]." 1");
					mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]." 1");
					$comanda_sql = "SELECT * FROM ".$prefix_tabel_bd."user WHERE user='".$user."' AND email='".$email."'";
					$interogare_sql = mysql_query($comanda_sql) OR die($mesaj[4]." 3");
					while ($rand=mysql_fetch_array($interogare_sql)) 
						{
						if ($parola_criptata != "da") // daca parola din sql este criptata se cripteaza si parola introdusa de user pentru a o putea verifica...
							{
							$rand['parola'] = md5($rand['parola']);
							}
						if ($rand['parola'] == $password)
							{
							$_SESSION[$prefix_sesiuni.'_rights_far'] = $rand['stare'];
							$_SESSION[$prefix_sesiuni.'_cheia_far'] = session_id();
							$_SESSION[$prefix_sesiuni.'_user_far'] = $rand['user'];
							$_SESSION[$prefix_sesiuni.'_email_far'] = $rand['email'];
							
							if (isset($valoare_cookie['hidden_far']))
								{
								if ($valoare_cookie['hidden_far'] == 1)
									{
									$_SESSION[$prefix_sesiuni.'_user_far'] = $mesaj[330];
									}
								if ($valoare_cookie['hidden_far'] == 0)
									{
									$_SESSION[$prefix_sesiuni.'_user_far'] = $rand['user'];
									}
								}
							if (!isset($valoare_cookie['hidden_far']))
								{
								$_SESSION[$prefix_sesiuni.'_user_far'] = $rand['user'];
								}
							}
						if ($rand['parola'] != $password)
							{							
							$valoare_cookie['language_far'] = '';
							$valoare_cookie['themes_far'] = '';
							$valoare_cookie['user_far'] = '';
							$valoare_cookie['email_far'] = '';
							$valoare_cookie['password_far'] = '';							
							if ($valoare_cookie['permanently_far'] == 1)
								{
								// se seteaza timpul de expirare al cookeului ca fiind permanent
								$expira = 1*60*60*24*100; // in acest caz expira dupa 100 de zile
								$timp_expirare = time()+$expira;
								}
							if ($valoare_cookie['permanently_far'] == 0)
								{
								// se seteaza timpul de expirare al cookeului ca fiind temporara			
								$timp_expirare = time()+3600; // in acest caz expira dupa 1 ora
								}
							$val_coke = serialize($valoare_cookie);	
							unset($_COOKIE[$prefix_sesiuni."_far"]);
							setcookie($prefix_sesiuni."_far", $val_coke, $timp_expirare, "/");
							}
						} 
					}
				}
			}
		}
	
	if (isset($_SESSION[$prefix_sesiuni.'_rights_far']))
		{		
		return ($_SESSION[$prefix_sesiuni.'_rights_far']);
		}
	if (!isset($_SESSION[$prefix_sesiuni.'_rights_far']))
		{
		$_SESSION[$prefix_sesiuni.'_rights_far'] = "6";
		return ($_SESSION[$prefix_sesiuni.'_rights_far']);
		}	
	}
debug_mesaje(12);
// ==========================================================
// setarea functiei de verificare ip
function ip_block_far($ip_bl=0)
	{
	global $server_bd;
	global $user_bd;
	global $parola_bd;
	global $nume_bd;
	global $prefix_tabel_bd;
	
	if($ip_bl != 0)
		{
		$arata_detalii = 1;
		$model= "^([0-9]{1,3})[.]{1,1}([0-9]{1,3})[.]{1,1}([0-9]{1,3})[.]{1,1}([0-9]{1,3})$";
		if (!ereg($model,$ip_bl))
			{
			die("<br>Adresa de ip ".$ip_bl." nu este corecta.");
			}
		}
	if($ip_bl == 0)
		{
		$arata_detalii = 0;		
		$ip_bl = $_SERVER['REMOTE_ADDR'];	
		}
	$data_bd = date("Y-m-d",time());
	$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die("1");
	$selectare = mysql_select_db($nume_bd, $conectare) OR die("2");
	$comanda_sql = "SELECT * FROM ".$prefix_tabel_bd."ip_block WHERE 
		ip='".$ip_bl."' AND data_start <= '".$data_bd."' AND data_stop >= '".$data_bd."' OR
		ip='".$ip_bl."' AND data_start <= '".$data_bd."' AND data_stop = '0000-00-00'";		
	$interogare_sql = mysql_query($comanda_sql) OR die("3");
	$total = mysql_num_rows($interogare_sql);
	if ($total != 0)
		{
		if ($arata_detalii == 0)
			{
			die("<br>Your ip is blocked.");
			}
		if ($arata_detalii == 1)
			{
			while ($rand = mysql_fetch_array($rezultat))
				{				
				if ($rand['data_stop'] == "0000-00-00")
					{
					$rand['data_stop'] = "infinit";
					}
				echo "<br>Adresa ip ".$ip_bl." este blocata incepand de la ".$rand['data_start']." pana la ".$rand['data_stop'];
				}
			}
		}
	if ($total == 0)
		{
		if ($arata_detalii == 1)
			{
			echo "<br>Adresa ip ".$ip_bl." nu este in lista de adrese blocate.";
			}
		}	
	}
// ==========================================================
// setarea functiei pentru limbaj
function language_far($limba_far = "all")
	{
	global $prefix_sesiuni;
	global $limbaj_primar;
	if ($limba_far == "all")
		{
		$limba_far = $limbaj_primar;
		}
	$_SESSION[$prefix_sesiuni.'_language_far'] = $limba_far;	
	}
// ==========================================================
// setarea functiei pentru generarea paginii
function getmicrotime()
	{ 
    list($usec, $sec) = explode(" ",microtime()); 
    return ((float)$usec + (float)$sec); 
    }
	
// ===========================================================	
function far_ver($valoare = "versiune")
	{
	global $server_bd;
	global $user_bd;
	global $parola_bd;
	global $mesaj;
	global $prefix_tabel_bd;
	global $nume_bd;
	global $prefix_sesiuni;	
	
	if ($valoare == "versiune")
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
				return ($rand['ver']);
				}
			}
		}
		
	if ($valoare == "ver")
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
				$mesaj = $rand['mesages'];
				$msg = explode ("_._",$mesaj);
				if ($rand['status'] == "1")
					{
					return ($msg[0]);
					}
				if ($rand['status'] == "2")
					{
					return ($msg[1]);
					}
				if ($rand['status'] == "3")
					{
					return ($msg[2]);
					}
				}
			}
		}
	
	if ($valoare == "full")
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
				$versiunea = "<br>Ver. ".$rand['ver']."
					<br>Install date: ".date("d-m-Y H:i",$rand['time_install'])."
					<br>Web address: <a href='".$rand['address']."' target='_blank'>".$rand['address']."</a>
					<br>Admin: <a href='mailto:".$rand['email']."?Subject=From my Web Site'>".$rand['name']."</a>
					<br>Default language: ".$rand['language'];
				return ($versiunea);
				}
			}
		}
	}
// ===========================================================	
// functia de la phpbb pentru limbaj
// Guess an initial language ... borrowed from phpBB 2.2 it's not perfect, 
// really it should do a straight match first pass and then try a "fuzzy"
// match on a second pass instead of a straight "fuzzy" match.
function guess_lang()
{
	global $HTTP_SERVER_VARS;

	// The order here _is_ important, at least for major_minor
	// matches. Don't go moving these around without checking with
	// me first - psoTFX
	$match_lang = array(
		'arabic'					=> 'ar([_-][a-z]+)?', 
		'bulgarian'					=> 'bg', 
		'catalan'					=> 'ca', 
		'czech'						=> 'cs', 
		'danish'					=> 'da', 
		'german'					=> 'de([_-][a-z]+)?',
		'english'					=> 'en([_-][a-z]+)?', 
		'estonian'					=> 'et', 
		'finnish'					=> 'fi', 
		'french'					=> 'fr([_-][a-z]+)?', 
		'greek'						=> 'el', 
		'spanish_argentina'			=> 'es[_-]ar', 
		'spanish'					=> 'es([_-][a-z]+)?', 
		'gaelic'					=> 'gd', 
		'galego'					=> 'gl', 
		'gujarati'					=> 'gu', 
		'hebrew'					=> 'he', 
		'hindi'						=> 'hi', 
		'croatian'					=> 'hr', 
		'hungarian'					=> 'hu', 
		'icelandic'					=> 'is', 
		'indonesian'				=> 'id([_-][a-z]+)?', 
		'italian'					=> 'it([_-][a-z]+)?', 
		'japanese'					=> 'ja([_-][a-z]+)?', 
		'korean'					=> 'ko([_-][a-z]+)?', 
		'latvian'					=> 'lv', 
		'lithuanian'				=> 'lt', 
		'macedonian'				=> 'mk', 
		'dutch'						=> 'nl([_-][a-z]+)?', 
		'norwegian'					=> 'no', 
		'punjabi'					=> 'pa', 
		'polish'					=> 'pl', 
		'portuguese_brazil'			=> 'pt[_-]br', 
		'portuguese'				=> 'pt([_-][a-z]+)?', 
		'romanian'					=> 'ro([_-][a-z]+)?', 
		'russian'					=> 'ru([_-][a-z]+)?', 
		'slovenian'					=> 'sl([_-][a-z]+)?', 
		'albanian'					=> 'sq', 
		'serbian'					=> 'sr([_-][a-z]+)?', 
		'slovak'					=> 'sv([_-][a-z]+)?', 
		'swedish'					=> 'sv([_-][a-z]+)?', 
		'thai'						=> 'th([_-][a-z]+)?', 
		'turkish'					=> 'tr([_-][a-z]+)?', 
		'ukranian'					=> 'uk([_-][a-z]+)?', 
		'urdu'						=> 'ur', 
		'viatnamese'				=> 'vi',
		'chinese_traditional_taiwan'=> 'zh[_-]tw',
		'chinese_simplified'		=> 'zh', 
	);

	if (isset($HTTP_SERVER_VARS['HTTP_ACCEPT_LANGUAGE']))
	{
		$accept_lang_ary = explode(',', $HTTP_SERVER_VARS['HTTP_ACCEPT_LANGUAGE']);
		for ($i = 0; $i < sizeof($accept_lang_ary); $i++)
		{
			@reset($match_lang);
			while (list($lang, $match) = each($match_lang))
			{
				if (preg_match('#' . $match . '#i', trim($accept_lang_ary[$i])))
				{
					//if (file_exists(@phpbb_realpath($phpbb_root_path . 'language/lang_' . $lang)))
					//{
						return $lang;
					//}
				}
			}
		}
	}

	return 'english';
	
}
//
// FUNCTIONS

// ===========================================================
// functia de generare parola preluata de la www.phpromania.net
// $digits = numarul de caractere pe care parola poate sa il contina (intre 4 si 29) 
// $c = daca true, I,i,L,l va fi schimbat in 1 sau O sau o va fi schimbat 0 (Zero) pentru a preveni completarea eronata a parolei 
// $st = pentru "U" = upper, "L" = lower, null=casesensitive 
function gen_pass($digits,$c,$st) 
	{ 
    if(!ereg("^([4-9]|((1|2){1}[0-9]{1}))$",$digits)) // 4-29 chars allowed 
   	    $digits=4; 
	    for( ; ; )
   			{ 
	        $pwd=null; $o=null; 
       		// generare parola .... 
	         for ($x=0; $x<$digits; ) 
       			{ 
                $y = rand(1,1000); 
               	if($y>350 && $y<601) $d=chr(rand(48,57)); 
               	if($y<351) $d=chr(rand(65,90)); 
               	if($y>600) $d=chr(rand(97,122)); 
               	if($d!=$o) 
               		{ 
                   	$o=$d; $pwd.=$d; $x++; 
                	} 
         		} 
         	// daca doriti ca utilizatorul sa nu confunda O sau 0 ("Of" sau "Zero") 
         	// sau 1 sau l ("Unu" sau "L"), seteaza $c=true; 
         	if($c) 
         		{ 
                $pwd=eregi_replace("(l|i)","1",$pwd); 
                $pwd=eregi_replace("(o)","0",$pwd); 
         		} 
         	// daca PW se incadreaza scopului (e.g. aceasta regexpression) returneaza valoarea, altfel genereaza una noua 
         	// (puteti schimba aceasta expresie regulata oricum doriti ....) 
         	if(ereg("^[a-zA-Z]{1}([a-zA-Z]+[0-9][a-zA-Z]+)+",$pwd)) 
                break; 
    		} 
    	if($st=="L") $pwd=strtolower($pwd); 
    	if($st=="U") $pwd=strtoupper($pwd); 
    	return $pwd; 
		}
// ======================================
?>