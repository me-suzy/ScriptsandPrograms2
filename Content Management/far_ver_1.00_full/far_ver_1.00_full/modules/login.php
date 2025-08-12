<?php
/* =====================================================================
*	Pagina login.php (parte din modulul de login)
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

$verificare_user = 0;
if(isset($_SESSION[$prefix_sesiuni.'_cheia_far'])) // daca e setata cheia de sesiune
	{		
	if ($_SESSION[$prefix_sesiuni.'_cheia_far'] == session_id()) // si daca cheia de sesiune corespunde cu sesiunea curenta
		{
		$nume_far = $_SESSION[$prefix_sesiuni.'_user_far'];
		$stare_far = $_SESSION[$prefix_sesiuni.'_rights_far']; 		
		$verificare_user = 0;		
		}	
	else
		{	
		$verificare_user = 1;		
		$stare_far = 6;
		}
	}
if(!isset($_SESSION[$prefix_sesiuni.'_cheia_far'])) // daca nu e setata cheia de sesiune
	{
	if (isset($_COOKIE[$prefix_sesiuni.'_far'])) // dar e setat cooke pentru sesiune
		{
		$prelucrare_cooke = stripslashes($_COOKIE[$prefix_sesiuni.'_far']);
		$valoare_cookie = unserialize($prelucrare_cooke);
		if (isset($valoare_cookie['_user_far']))
			{
			if (!empty($valoare_cookie['_user_far'])) // se verifica daca numele userului din cooke exista
				{
				if (isset($_SESSION[$prefix_sesiuni.'_rights_far']))
					{
					if ($_SESSION[$prefix_sesiuni.'_rights_far'] != 6)
						{
						$nume_far = $valoare_cookie['_user_far'];
						$stare_far = $_SESSION[$prefix_sesiuni.'_rights_far'];
						$verificare_user = 0;
						}
					else
						{
						$verificare_user = 1;
						$stare_far = 6;
						}
					}
				else
					{
					$verificare_user = 1;
					$stare_far = 6;
					}			
				}
			else
				{
				$verificare_user = 1;
				$stare_far = 6;
				}
			}
		else
			{	
			$verificare_user = 1;
			$stare_far = 6;
			}	
		}
	if (!isset($_COOKIE[$prefix_sesiuni.'_far']))
		{
		$verificare_user = 1;
		$stare_far = 6;
		}
	}
	
if ($verificare_user == 0) // daca verificarea e ok se afiseaza userul
	{
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
		if ($cheie == $stare_far)
			{
			$drept_acces = $valoare;
			}
			//echo "<br>drept = $cheie -- valoare = $valoare";		
		}
	echo '<table width="100%"  border="0" cellpadding="0" cellspacing="1">
		<tr>
		<td><div align="center" class="largetext">
		'.$mesaj[34].'<b>'.$nume_far.'</b><BR>
		'.$mesaj[56].$drept_acces.'</div>
		</td>
		</tr>
		<tr>
		<br>
		<th class="boton_buscar" scope="row"><a href="admin.php?m=login_new&action=dec">'.$mesaj[35].'</a></th>			
		</tr>
		</table>';
	}
if ($verificare_user != 0) // daca verificarea nu e ok se afiseaza partea de logare
	{
	echo '	
		<form name="form_logare" method="post" action="admin.php?m=login_ver">
		  <div align="center">'.$mesaj[36].'<br>
		    <input name="user" type="text" id="user" size="15">
		    <br>
		  '.$mesaj[37].' <br>
		  <input name="parola" type="password" id="parola" size="15">
		  <br>
		  <label><input type="radio" name="tip" value="permanent">'.$mesaj[328].'</label><br>
		  <label><input type="radio" name="tip" value="ascuns">'.$mesaj[329].'</label><br>
		  <input name="logare" type="submit" class="textareastyle" id="logare" value="'.$mesaj[38].'">
		</div>
		</form>
		 <br><div align="center" class="verysmalltext">
		- <a class="mainlink" href="admin.php?m=login_new&action=new_user"><b>'.$mesaj[39].'</b></a> - <br>
		- <a class="mainlink" href="admin.php?m=login_new&action=new_pass"><b>'.$mesaj[40].'</b></a> -</div>';
	}
?>