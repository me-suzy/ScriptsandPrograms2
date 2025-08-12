<?
/* =====================================================================
*	Pagina blockip.php (parte din modulul de login)
*	Creat de Birkoff pentru proiectul FAR-PHP
*	Versiune: 1.0
*	Copyright: (C) 2004 - 2005 The FAR-PHP Group
*	E-mail: contact@far-php.ro
*	Data inceperii paginii: 16-05-2005
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

$nivel_acces = drepturi_far(); 
if ($nivel_acces <= 4)
	{
	$verificare = 0;
	}
if ($nivel_acces >= 5) 
	{
	$verificare = 1;
	}	

// crearea functiilor specifice modulului
function mesaje_blockip($nr) // functia pentru mesaje
	{
	global $prefix_sesiuni;
	
	$limbaj_prelucrat = $_SESSION[$prefix_sesiuni.'_language_far'];

	$mesaje_fisier_ro = array(  // crearea mesajelor pentru romana
			1 => '<br>Nivelul dvs. de acces este prea mic pentru aceste informatii.',
			2 => 'IP-ul blocat: ',
			3 => 'Data de start: ',
			4 => 'Data de stop: ',
			5 => 'Adauga',
			6 => '(specificati 0000-00-00 pentru permanent)',
			7 => '<br>Eroare: Adresa de ip nu este corecta',
			8 => '<br>Eroare: Data de start este incorecta',
			9 => '<br>Eroare: Anul pentru data de start este mai mic decat anul curent',
			10 => '<br>Eroare: Specificarea lunii pentru data de start este gresita (maxim 12 luni/an)',
			11 => '<br>Eroare: Luna specificata pentru data de start este mai mica decat luna curenta',
			12 => '<br>Eroare: Ziua specificata pentru data de start nu poate fi mai mare de 31 zile',
			13 => '<br>Eroare: Ziua specificata pentru data de start nu poate fi mai mica decat data curenta.',
			14 => '<br>Eroare: Data de stop este incorecta',
			15 => '<br>Eroare: Anul pentru data de stop este mai mic decat anul curent',
			16 => '<br>Eroare: Specificarea lunii pentru data de stop este gresita (maxim 12 luni/an)',
			17 => '<br>Eroare: Luna specificata pentru data de stop este mai mica decat luna curenta',
			18 => '<br>Eroare: Ziua specificata pentru data de stop nu poate fi mai mare de 31 zile',
			19 => '<br>Eroare: Ziua specificata pentru data de stop nu poate fi mai mica decat data curenta.',
			20 => '<br>Eroare: Data de stop trebuie sa fie mai mare decat data de start',
			21 => '<br>Adresa ip a fost adaugata in baza de date. Asteptati pentru redirectare.',
			22 => '<br>Nu exista in baza de date nici un rezultat.',
			23 => 'Nr',
			24 => 'Id',
			25 => 'Ip',
			26 => 'Timp',
			27 => 'Start',
			28 => 'Stop',
			29 => 'Adauga',
			30 => 'M',
			31 => 'S',
			32 => 'Ip-ul introdus exista deja in baza de date.',
			33 => 'Introduceti adresa de ip la care doriti sa  modificati datele: ',
			34 => 'Continuare',
			35 => 'Modifica',
			36 => 'Introduceti adresa de ip pe care doriti sa o stergeti: ',
			37 => 'Adresa de ip specificata a fost stearsa din baza de date. Asteptati pentru redirectare.');
			
	$mesaje_fisier_en = array(  // crearea mesajelor pentru engleza
			1 => '<br>Your access level is to small',
			2 => 'IP to block: ',
			3 => 'Start date: ',
			4 => 'Stop date: ',
			5 => 'Submit',
			6 => '0000-00-00 for permanently block',
			7 => '<br>Error: Wrong ip address',
			8 => '<br>Error: Wrong start date',
			9 => '<br>Error: The year from start date is wrong',
			10 => '<br>Error: The date format for start date is wrong (max. 12 month/year)',
			11 => '<br>Error: The month for start date must elder than current date',
			12 => '<br>Error: The day for start date must <= 31 day',
			13 => '<br>Error: The day for start date must elder than current date',
			14 => '<br>Error: Wrong stop date',
			15 => '<br>Error: The year from stop date is wrong',
			16 => '<br>Error: The date format for stop date is wrong (max. 12 month/year)',
			17 => '<br>Error: The month for stop date must elder than current date',
			18 => '<br>Error: The day for stop date must <= 31 day',
			19 => '<br>Error: The day for stop date must elder than current date',
			20 => '<br>Error: The stop date must elder than start date',
			21 => 'The ip successfuly inserted in database. Wait for redirect.',
			22 => '<br>It is not result in database.',
			23 => 'No',
			24 => 'Id',
			25 => 'Ip',
			26 => 'Time',
			27 => 'Start',
			28 => 'Stop',
			29 => 'Add',
			30 => 'M',
			31 => 'D',
			32 => 'There are inserted ip in database.',
			33 => 'Insert ip address to modify: ',
			34 => 'Carry on',
			35 => 'Modify',
			36 => 'Insert ip address to erase: ',
			37 => 'The ip successfuly erase from database. Wait for redirect.');
			
	if ($limbaj_prelucrat == "ro")
		{
		return $mesaje_fisier_ro[$nr];
		}		
	if ($limbaj_prelucrat == "en")
		{
		return $mesaje_fisier_en[$nr];
		}	
	if ($limbaj_prelucrat != "en")
		{
		if ($limbaj_prelucrat != "ro")
			{
			return $mesaje_fisier_en[$nr];
			}	
		}
	}
	
function sterge_ip($nr = "0") // functia pentru formularul de stergere ip
	{
	global $prefix_tabel_bd;
	global $server_bd;
	global $user_bd;
	global $parola_bd;
	global $mesaj;
	global $nume_bd;
	
	if ($nr == "0")
		{
		$ip = $_SERVER['REMOTE_ADDR'];
		// se afiseaza formularul pentru ip
		$form1 = '<div align="center"><form name="form1" method="post" action="admin.php?m=blockip&action=del">
  			'.mesaje_blockip(36).'
    		<input name="ip" type="text" id="ip" size="20" maxlength="15" value="'.$ip.'" onClick="this.value=\'\'">
    		<br>
		    <input name="stergere" type="hidden" id="stergere" value="1">
		    <input type="submit" name="Submit" value="'.mesaje_blockip(34).'">  
			</form>
			</div>';
		return $form1;
		}
	if ($nr != "0")
		{
		$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]." 1");
		mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]." 1");
		$comanda_sql = "SELECT * FROM ".$prefix_tabel_bd."ip_block WHERE ip='".$nr."'";	
		$rezultat = mysql_query($comanda_sql) OR die($mesaj[4]." 2");
		$total = mysql_num_rows($rezultat);
		if ($total == 0)
			{
			return mesaje_blockip(22);
			}
		else
			{
			$comanda_sql = "DELETE FROM ".$prefix_tabel_bd."ip_block WHERE ip='".$nr."'";	
			$rezultat = mysql_query($comanda_sql) OR die($mesaj[4]." 2");
			return '<META HTTP-EQUIV = "Refresh" Content = "5; URL =index.php">'.mesaje_blockip(37);
			}
		}	
	}
function modifica_ip($nr = "0") // functia pentru formularul de modificare ip
	{
	global $prefix_tabel_bd;
	global $server_bd;
	global $user_bd;
	global $parola_bd;
	global $mesaj;
	global $nume_bd;
	
	if ($nr == "0")
		{
		$ip = $_SERVER['REMOTE_ADDR'];
		// se afiseaza formularul pentru ip
		$form1 = '<div align="center"><form name="form1" method="post" action="admin.php?m=blockip&action=modify">
  			'.mesaje_blockip(33).'
    		<input name="ip" type="text" id="ip" size="20" maxlength="15" value="'.$ip.'" onClick="this.value=\'\'">
    		<br>
		    <input name="modificare" type="hidden" id="modificare" value="1">
		    <input type="submit" name="Submit" value="'.mesaje_blockip(34).'">  
			</form>
			</div>';
		return $form1;
		}	
		
	if ($nr != "0")
		{
		if ($nr != 1)
			{			
			// aici se verifica daca este ip-ul in bd si se afiseaza datele pentru modificare						
			$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]." 1");
			mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]." 1");
			$comanda_sql = "SELECT * FROM ".$prefix_tabel_bd."ip_block WHERE ip='".$nr."'";	
			$rezultat = mysql_query($comanda_sql) OR die($mesaj[4]." 2");
			$total = mysql_num_rows($rezultat);
			if ($total == 0)
				{
				return mesaje_blockip(22);
				}
			else
				{
				while ($rand = mysql_fetch_array($rezultat))
					{
					$ip = $rand['ip'];
					$start = $rand['data_start'];
					$stop = $rand['data_stop'];
					}
				$form = '<form name="form1" method="post" action="admin.php?m=blockip&action=modify">
					<table width="100%"  border="0" cellspacing="0" cellpadding="0">
			  		<tr>
	    			<td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
		    	  	<tr>
		   			<td width="50%"><div align="right">'.mesaje_blockip(2).'</div></td>
				    <td width="50%"><input name="ip" type="text" id="ip" value="'.$ip.'" size="15" maxlength="15" readonly=""></td>
	      			</tr>
			    	</table></td>
	  				</tr>
		  			<tr>
		    		<td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
			      	<tr>
	    		    <td width="50%"><div align="right">'.mesaje_blockip(3).'</div></td>
			        <td width="15%"><input name="start" type="text" id="start" value="'.$start.'" size="15" maxlength="10" onClick="this.value=\'\'"></td>
	    		    <td>&nbsp;</td>
		    	  	</tr>
		    		</table></td>
					</tr>
			  		<tr>	
    				<td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
		    	  	<tr>
		   			<td width="50%"><div align="right">'.mesaje_blockip(4).'</div></td>
			        <td width="15%"><input name="stop" type="text" id="stop" value="'.$stop.'" size="15" maxlength="10" onClick="this.value=\'\'"></td>
    			    <td>'.mesaje_blockip(6).'</td>
			      	</tr>
    				</table></td>
		  			</tr>
		  			<tr>
			    	<td valign="top"><div align="center">
					<input name="modificare" type="hidden" id="modificare" value="1">
			      	<input type="submit" name="Submit" value="'.mesaje_blockip(35).'">
    				</div></td>
		  			</tr>
					</table>
					</form>';		
				return $form;
				}
			}
		}
	if ($nr == 1)
		{
		// aici se salveaza in bd modificarea
		$timp = time();
		if(empty($_POST['stop']))
			{
			$stop = "0000-00-00";
			}
		else
			{
			$stop = $_POST['stop'];
			}
		$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]." 1");
		mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]." 1");
		$comanda_sql = "UPDATE ".$prefix_tabel_bd."ip_block SET ip='".$_POST['ip']."', 
		timp='".$timp."', data_start='".$_POST['start']."', data_stop='".$stop."' WHERE ip='".$_POST['ip']."'";			
		$introducere_date_sql = mysql_query($comanda_sql) OR die($mesaj[4]." 2");
		return '<META HTTP-EQUIV = "Refresh" Content = "5; URL =index.php">'.mesaje_blockip(21);
		}
	}
function afisare_ip($nr = "0") // functia pentru formularul de adaugare ip
	{
	global $prefix_tabel_bd;
	global $server_bd;
	global $user_bd;
	global $parola_bd;
	global $mesaj;
	global $nume_bd;
				
	$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]." 1");
	mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]." 1");
	if ($nr == "0")
		{
		$comanda_sql = "SELECT * FROM ".$prefix_tabel_bd."ip_block";	
		}
	if ($nr != "0")
		{
		$comanda_sql = "SELECT * FROM ".$prefix_tabel_bd."ip_block WHERE ip='".$nr."'";	
		}				
	$rezultat = mysql_query($comanda_sql) OR die($mesaj[4]." 2");
	$total = mysql_num_rows($rezultat);
	if ($total == 0)
		{
		return mesaje_blockip(22);
		}
	else
		{
		$nr_ip = 1;
		echo '<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  			<tr>
    		<td width="5%">'.mesaje_blockip(23).'</td>
    		<td width="5%">'.mesaje_blockip(24).'</td>
    		<td width="15%">'.mesaje_blockip(25).'</td>
    		<td width="25%">'.mesaje_blockip(26).'</td>
    		<td width="20%">'.mesaje_blockip(27).'</td>
    		<td width="20%">'.mesaje_blockip(28).'</td>
			<td width="10%"><a href="admin.php?m=blockip">'.mesaje_blockip(29).'</a></td>
  			</tr>
			<tr bgcolor="#000099">
			    <td height="1"></td>
			    <td height="1"></td>
			    <td height="1"></td>
			    <td height="1"></td>
			    <td height="1"></td>
			    <td height="1"></td>
				<td height="1"></td>
			  </tr>
			  <tr bgcolor="#999999">
			    <td height="1"></td>
			    <td height="1"></td>
			    <td height="1"></td>
			    <td height="1"></td>
			    <td height="1"></td>
			    <td height="1"></td>
				<td height="1"></td>
			  </tr>
			  <tr bgcolor="#000099">
			    <td height="1"></td>
			    <td height="1"></td>
			    <td height="1"></td>
			    <td height="1"></td>
			    <td height="1"></td>
			    <td height="1"></td>
				<td height="1"></td>
			  </tr>';
		while ($rand = mysql_fetch_array($rezultat))
			{
			echo '<tr>
    			<td>'.$nr_ip.'</td>
			    <td>'.$rand['id'].'</td>
			    <td>'.$rand['ip'].'</td>
			    <td>'.date("d-m-Y H:i",$rand['timp']).'</td>
			    <td>'.$rand['data_start'].'</td>
			    <td>'.$rand['data_stop'].'</td>
				<td><a href="admin.php?m=blockip&action=modify&ip='.$rand['ip'].'">
				'.mesaje_blockip(30).'
				</a> / 
				<a href="admin.php?m=blockip&action=del&ip='.$rand['ip'].'">
				'.mesaje_blockip(31).'
				</a></td>
			  </tr>
			  <tr bgcolor="#000099">
			    <td height="1"></td>
			    <td height="1"></td>
			    <td height="1"></td>
			    <td height="1"></td>
			    <td height="1"></td>
			    <td height="1"></td>
				<td height="1"></td>
			  </tr>
			  <tr bgcolor="#999999">
			    <td height="1"></td>
			    <td height="1"></td>
			    <td height="1"></td>
			    <td height="1"></td>
			    <td height="1"></td>
			    <td height="1"></td>
				<td height="1"></td>
			  </tr>
			  <tr bgcolor="#000099">
			    <td height="1"></td>
			    <td height="1"></td>
			    <td height="1"></td>
			    <td height="1"></td>
			    <td height="1"></td>
			    <td height="1"></td>
				<td height="1"></td>
			  </tr>';
			$nr_ip++;
			}
		echo '</table>';
		}
	}
function adaugare_ip($nr = "0") // functia pentru formularul de adaugare ip
	{
	if ($nr == "0")
		{
		$ip = $_SERVER['REMOTE_ADDR'];
		$start = date("Y-m-d",time());
		$stop = date("Y-m-d",time()+1*60*60*24*30);				
		}
	if ($nr == "1")
		{
		$ip = $_POST['ip'];
		$start = $_POST['start'];
		$stop = $_POST['stop'];					
		}
	if ($nr != "2")
		{
		$form = '<form name="form1" method="post" action="admin.php?m=blockip">
			<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	  		<tr>
    		<td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
	      	<tr>
   			<td width="50%"><div align="right">'.mesaje_blockip(2).'</div></td>
		    <td width="50%"><input name="ip" type="text" id="ip" value="'.$ip.'" size="15" maxlength="15" onClick="this.value=\'\'"></td>
      		</tr>
	    	</table></td>
  			</tr>
	  		<tr>
    		<td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
	      	<tr>
    	    <td width="50%"><div align="right">'.mesaje_blockip(3).'</div></td>
	        <td width="15%"><input name="start" type="text" id="start" value="'.$start.'" size="15" maxlength="10" onClick="this.value=\'\'"></td>
    	    <td>&nbsp;</td>
	      	</tr>
    		</table></td>
			</tr>
	  		<tr>
    		<td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
	      	<tr>
   			<td width="50%"><div align="right">'.mesaje_blockip(4).'</div></td>
	        <td width="15%"><input name="stop" type="text" id="stop" value="'.$stop.'" size="15" maxlength="10" onClick="this.value=\'\'"></td>
    	    <td>'.mesaje_blockip(6).'</td>
	      	</tr>
    		</table></td>
	  		</tr>
  			<tr>
	    	<td valign="top"><div align="center">
			<input name="adaugare" type="hidden" id="adaugare" value="1">
	      	<input type="submit" name="Submit" value="'.mesaje_blockip(5).'">
    		</div></td>
	  		</tr>
			</table>
			</form>';		
		return $form;
		}
	
	if ($nr == "2")
		{
		// adaugare in bd
		global $prefix_tabel_bd;
		global $server_bd;
		global $user_bd;
		global $parola_bd;
		global $mesaj;
		global $nume_bd;
		
		if(empty($_POST['stop']))
			{
			$stop = "0000-00-00";
			}
		else
			{
			$stop = $_POST['stop'];
			}
		
		$timp = time();		
		$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die($mesaj[2]." 1");
		mysql_select_db($nume_bd, $conectare) OR die($mesaj[3]." 1");
		$comanda = "SELECT * FROM ".$prefix_tabel_bd."ip_block WHERE ip='".$_POST['ip']."'";
		$rezultat = mysql_query($comanda) OR die($mesaj[4]." 2");
		$total = mysql_num_rows($rezultat);
		if ($total == 0)
			{
			$comanda_sql = "INSERT INTO ".$prefix_tabel_bd."ip_block (ip, timp, data_start, data_stop) VALUES 
					('".$_POST['ip']."', '".$timp."', '".$_POST['start']."', '".$stop."')";			
			$introducere_date_sql = mysql_query($comanda_sql) OR die($mesaj[4]." 2");
			return '<META HTTP-EQUIV = "Refresh" Content = "5; URL =index.php">'.mesaje_blockip(21);
			}
		else
			{
			return mesaje_blockip(32);
			}
		}
	}
		
if ($verificare == 1)
	{
	echo mesaje_blockip(1);
	}	

if ($verificare == 0)
	{
	// default adaugare ip
	if (!isset($_POST['adaugare']))
		{
		if (!isset($_POST['modificare']))
			{
			if (!isset($_POST['stergere']))
				{				
				if (!isset($_GET['action']))
					{
					echo adaugare_ip();
					}					
				}
			}		
		}
	if (isset($_POST['adaugare'])) // daca se cere adaugare ip
		{
		$ip = $_POST['ip'];
		$start = $_POST['start'];
		$stop = $_POST['stop'];
		// verificare date trimise
		$verificare = 0;
		// verificare ip
		if (!ereg("([0-9]{1,3})([.]{1})([0-9]{1,3})([.]{1})([0-9]{1,3})([.]{1})([0-9]{1,3})",$ip)) // ip de forma xxx.xxx.xxx.xxx
			{
			$verificare = 1;	
			echo mesaje_blockip(7);
			}
		// verificare data start
		if (!ereg("([0-9]{4})([-]{1})([0-9]{2})([-]{1})([0-9]{2})",$start))
			{
			$verificare = 1;	
			echo mesaje_blockip(8);
			}
		if (ereg("([0-9]{4})([-]{1})([0-9]{2})([-]{1})([0-9]{2})",$start))
			{
			$dta = explode("-",$start);
			if ($dta[0] < date("Y"))
				{
				$verificare = 1;
				echo mesaje_blockip(9);
				}
			if ($dta[1] == "00")
				{
				$verificare = 1;
				echo mesaje_blockip(10);
				}	
			if ($dta[1] > 12)
				{
				$verificare = 1;
				echo mesaje_blockip(10);
				}		
			if ($dta[0] == date("Y") AND $dta[1] < date("m"))
				{
				$verificare = 1;
				echo mesaje_blockip(11);
				}
			if ($dta[2] == "00")
				{
				$verificare = 1;
				echo mesaje_blockip(12);
				}
			if ($dta[2] > 31)
				{
				$verificare = 1;
				echo mesaje_blockip(12);
				}
			if ($dta[0] == date("Y") AND $dta[1] == date("m") AND $dta[2] < date("d"))
				{
				$verificare = 1;
				echo mesaje_blockip(13);
				}
			}
		// verificare data stop
		if (!empty($stop))
			{
			if (!ereg("([0-9]{4})([-]{1})([0-9]{2})([-]{1})([0-9]{2})",$stop))
				{
				$verificare = 1;	
				echo mesaje_blockip(14);
				}
			if (ereg("([0-9]{4})([-]{1})([0-9]{2})([-]{1})([0-9]{2})",$stop))
				{
				if ($stop != "0000-00-00")
					{
					$dta = explode("-",$stop);
					if ($dta[0] < date("Y"))
						{
						$verificare = 1;
						echo mesaje_blockip(15);
						}
					if ($dta[1] > 12)
						{
						$verificare = 1;
						echo mesaje_blockip(16);
						}		
					if ($dta[0] == date("Y") AND $dta[1] < date("m"))
						{
						$verificare = 1;
						echo mesaje_blockip(17);
						}
					if ($dta[2] > 31)
						{
						$verificare = 1;
						echo mesaje_blockip(18);
						}
					if ($dta[0] == date("Y") AND $dta[1] == date("m") AND $dta[2] < date("d"))
						{
						$verificare = 1;
						echo mesaje_blockip(19);
						}				
					if ($start > $stop)
						{
						$verificare = 1;
						echo mesaje_blockip(20);
						}
					}
				}
			}
		if (empty($stop))
			{
			$stop = "0000-00-00";
			}
		if ($verificare == 0)
			{			
			echo adaugare_ip(2);
			}
		if ($verificare == 1)
			{			
			echo adaugare_ip(1);
			}
		
		}
	
	if (isset($_GET['action']))
		{
		$actiune = $_GET['action'];
		if ($actiune == "show") // afisare lista ip
			{
			if (isset($_GET['ip'])) // daca se cere date despre un anumit ip
				{
				echo afisare_ip($_GET['ip']);
				}
			else
				{
				echo afisare_ip();
				}
			}
		if ($actiune == "del") // stergere ip
			{		
			if (isset($_GET['ip'])) // daca se cere date despre un anumit ip
				{
				echo sterge_ip($_GET['ip']);
				}
			else
				{				
				if (isset($_POST['stergere']))
					{
					echo sterge_ip($_POST['ip']);
					}	
				else
					{
					echo sterge_ip();
					}
				}			
			}
		if ($actiune == "modify") // modificare date ip
			{			
			if (isset($_GET['ip'])) // daca se cere date despre un anumit ip
				{
				echo modifica_ip($_GET['ip']);
				}
			else
				{
				if (isset($_POST['modificare']))
					{
					if (isset($_POST['stop']))
						{
						$ip = $_POST['ip'];
						$start = $_POST['start'];
						$stop = $_POST['stop'];
						// verificare date trimise
						$verificare = 0;
						// verificare ip
						if (!ereg("([0-9]{1,3})([.]{1})([0-9]{1,3})([.]{1})([0-9]{1,3})([.]{1})([0-9]{1,3})",$ip)) // ip de forma xxx.xxx.xxx.xxx
							{
							$verificare = 1;	
							echo mesaje_blockip(7);
							}
						// verificare data start
						if (!ereg("([0-9]{4})([-]{1})([0-9]{2})([-]{1})([0-9]{2})",$start))
							{
							$verificare = 1;	
							echo mesaje_blockip(8);
							}
						if (ereg("([0-9]{4})([-]{1})([0-9]{2})([-]{1})([0-9]{2})",$start))
							{
							$dta = explode("-",$start);
							if ($dta[0] < date("Y"))
								{
								$verificare = 1;
								echo mesaje_blockip(9);
								}
							if ($dta[1] == "00")
								{
								$verificare = 1;
								echo mesaje_blockip(10);
								}	
							if ($dta[1] > 12)
								{
								$verificare = 1;
								echo mesaje_blockip(10);
								}		
							if ($dta[0] == date("Y") AND $dta[1] < date("m"))
								{
								$verificare = 1;
								echo mesaje_blockip(11);
								}
							if ($dta[2] == "00")
								{
								$verificare = 1;
								echo mesaje_blockip(12);
								}
							if ($dta[2] > 31)
								{
								$verificare = 1;
								echo mesaje_blockip(12);
								}
							if ($dta[0] == date("Y") AND $dta[1] == date("m") AND $dta[2] < date("d"))
								{
								$verificare = 1;
								echo mesaje_blockip(13);
								}
							}
						// verificare data stop
						if (!empty($stop))
							{
							if (!ereg("([0-9]{4})([-]{1})([0-9]{2})([-]{1})([0-9]{2})",$stop))
								{
								$verificare = 1;	
								echo mesaje_blockip(14);
								}
							if (ereg("([0-9]{4})([-]{1})([0-9]{2})([-]{1})([0-9]{2})",$stop))
								{
								if ($stop != "0000-00-00")
									{
									$dta = explode("-",$stop);
									if ($dta[0] < date("Y"))
										{
										$verificare = 1;
										echo mesaje_blockip(15);
										}
									if ($dta[1] > 12)
										{
										$verificare = 1;
										echo mesaje_blockip(16);
										}		
									if ($dta[0] == date("Y") AND $dta[1] < date("m"))
										{
										$verificare = 1;
										echo mesaje_blockip(17);
										}
									if ($dta[2] > 31)
										{
										$verificare = 1;
										echo mesaje_blockip(18);
										}
									if ($dta[0] == date("Y") AND $dta[1] == date("m") AND $dta[2] < date("d"))
										{
										$verificare = 1;
										echo mesaje_blockip(19);
										}				
									if ($start > $stop)
										{
										$verificare = 1;
										echo mesaje_blockip(20);
										}
									}
								}
							}
						if (empty($stop))
							{
							$stop = "0000-00-00";
							}
						if ($verificare == 0)
							{			
							echo modifica_ip(1);							
							}
						if ($verificare == 1)
							{			
							echo modifica_ip($ip);
							}			
						}
					else
						{
						$ip = $_POST['ip'];
						echo modifica_ip($ip);
						}
					}
				else
					{
					echo modifica_ip();
					}
				}
			}
		}			
	}
?>