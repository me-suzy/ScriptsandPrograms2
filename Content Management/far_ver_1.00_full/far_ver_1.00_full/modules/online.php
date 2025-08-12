<?php
/* =====================================================================
*	Pagina online.php
*	Creat de Dexter pentru proiectul FAR-PHP
*	Versiune: 1.00
*	Copyright: (C) 2004 - 2005 The FAR-PHP Group
*	E-mail: dexter@far-php.ro
*	Data inceperii paginii: 4-04-2005
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

// se verifica daca sunt declarate functiile pentru ca
// in cazul in care se include scriptul de 2 ori in pagina
// sa nu raporteze erori 
if (!function_exists('verificare_tabela')) 
	{
	function mesaje_modul_online($nr)
		{
		global $prefix_sesiuni;
		global $adresa_url;
		//verificare sesiune limba
		if(isset($_SESSION[$prefix_sesiuni.'_language_far']))
			{	
			$limbaj_prelucrat = $_SESSION[$prefix_sesiuni.'_language_far'];
			}
		else
			{
			$limbaj_prelucrat = 'en';
			}
	
		//mesaje limba ro
		$mesaje_fisier_ro = array(
				0 => 'In <a href="'.$adresa_url.'admin.php?m=online&action=see">total</a> exista<br>',		
				1 => '<b>un</b> vizitator:<br>',
				2 => 'vizitatori:<br>',
				3 => '<b>un</b> membru,<br>',
				4 => 'membri,<br>',
				5 => '<b>unul</b> ascuns.<br>',
				6 => 'ascunsi.<br>',
				7 => '<b>un</b> invitat,<br>',
				8 => 'invitati,<br>',
				9 => 'Utilizator',
				10 => 'Timp online',
				11 => 'Pagina',
				12 => 'Adresa IP',
				13 => 'Host',
				14 => 'un invitat');
		
		// crearea mesajelor pentru engleza
		$mesaje_fisier_en = array(
				0 => '<a href="'.$adresa_url.'admin.php?m=online&action=see">Total</a> here are<br>',		
				1 => '<b>one</b> visitor:<br>',
				2 => 'visitors:<br>',
				3 => '<b>one</b> member,<br>',
				4 => 'members,<br>',
				5 => '<b>one</b> hidden.<br>',
				6 => 'hidden.<br>',
				7 => '<b>one</b> guest,<br>',
				8 => 'guests,<br>',
				9 => 'User',
				10 => 'Time online',
				11 => 'Page',
				12 => 'IP',
				13 => 'Host',
				14 => 'one guest');
				
		if ($limbaj_prelucrat == "ro")
			{
			$mesaje_fisier = $mesaje_fisier_ro;
			}			
		if ($limbaj_prelucrat == "en")
			{
			$mesaje_fisier = $mesaje_fisier_en;
			}
		if ($limbaj_prelucrat != "ro")
			{
			if ($limbaj_prelucrat != "en")
				{
				$mesaje_fisier = $mesaje_fisier_en;
				}
			}
		return($mesaje_fisier[$nr]);
		}
		
	function verificare_tabela($ipnum)
		{ //functie de verificare ip exista
		global $prefix_tabel_bd;
		$res=mysql_query("SELECT * FROM ".$prefix_tabel_bd."online WHERE ip='".$ipnum."'");
		if(mysql_num_rows($res)=="0")
			{
			return false;
			}
		else
			{
			return true;
			}
		}
	
	function adaugaredb($ipnum)
		{
		global $prefix_tabel_bd,$prefix_sesiuni;
		$pagina=$_SERVER['REQUEST_URI']; /// setare pagina curenta
		$timp=strtotime("now"); // timpul acuma
		//de verificat daca e utilizatorul inregistrat
		if(isset($_SESSION[$prefix_sesiuni.'_cheia_far'])) // daca e setata cheia de sesiune
			{
			if ($_SESSION[$prefix_sesiuni.'_cheia_far'] == session_id()) // si daca cheia de sesiune corespunde cu sesiunea curenta
				{
				if (isset($_SESSION[$prefix_sesiuni.'_user_far']))
					{
					$nume_far = $_SESSION[$prefix_sesiuni.'_user_far'];
					}
				if (!isset($_SESSION[$prefix_sesiuni.'_user_far']))
					{
					$nume_far="0";
					}
				if (isset($_SESSION[$prefix_sesiuni.'_hidden_far']))
					{
					$ascuns=$_SESSION[$prefix_sesiuni.'_hidden_far'];
					}
				if (!isset($_SESSION[$prefix_sesiuni.'_hidden_far']))
					{
					$ascuns="0";
					}				
				}
			else
				{	
				$nume_far="0";
				$ascuns="0";
				}
			}
		else
			{
			$nume_far="0";
			$ascuns="0";
			}
		mysql_query("INSERT INTO ".$prefix_tabel_bd."online(ip,user,timp,reload,pagina,ascuns) VALUES('$ipnum','$nume_far','$timp','$timp','$pagina','$ascuns')");
		}
		
	function modificaredb($ipnum)
		{
		global $prefix_tabel_bd,$prefix_sesiuni;
		$pagina=$_SERVER['REQUEST_URI']; /// setare pagina curenta
		$timp=strtotime("now"); // timpul acuma
		// in cazul in care s-a logat userul
		if(isset($_SESSION[$prefix_sesiuni.'_user_far'])) // daca e setata userul logat
			{		
			$nume_far = $_SESSION[$prefix_sesiuni.'_user_far'];
			if (isset($_SESSION[$prefix_sesiuni.'_hidden_far'])) // daca e setata userul logat
				{		
				$ascuns = $_SESSION[$prefix_sesiuni.'_hidden_far'];
				}
			if (!isset($_SESSION[$prefix_sesiuni.'_hidden_far'])) // daca e setata userul logat
				{		
				$ascuns="0";
				}
			$res=mysql_query("UPDATE ".$prefix_tabel_bd."online SET user='$nume_far' WHERE ip='$ipnum'");
			$res=mysql_query("UPDATE ".$prefix_tabel_bd."online SET ascuns='$ascuns' WHERE ip='$ipnum'");
			}
		else
			{	
			$nume_far="0";
			$ascuns="0";
			$res=mysql_query("UPDATE ".$prefix_tabel_bd."online SET user='$nume_far' WHERE ip='$ipnum'");
			$res=mysql_query("UPDATE ".$prefix_tabel_bd."online SET ascuns='$ascuns' WHERE ip='$ipnum'");
			}	
		$res=mysql_query("UPDATE ".$prefix_tabel_bd."online SET reload ='$timp' WHERE ip='$ipnum'");
		}
		
	//stergere din baza de date useri inactivi
	function sterge()
		{
		global $prefix_tabel_bd;
		$timp=strtotime("now")-300;
		mysql_query("DELETE FROM ".$prefix_tabel_bd."online WHERE reload < $timp");
		}
	
	//afisare grafica simpla
	function afisarescurt($ip)
		{
		global $prefix_tabel_bd;
		
		$ipnum=$ip;//atribuire ip
		if(verificare_tabela($ipnum)==false)
			{// in cazul in care utilizatorul nu exista in db
			adaugaredb($ipnum);
			}
		else
			{
			modificaredb($ipnum);
			}
		sterge();
		$total=mysql_num_rows(mysql_query("SELECT * FROM ".$prefix_tabel_bd."online"));
		$membri=mysql_num_rows(mysql_query("SELECT * FROM ".$prefix_tabel_bd."online WHERE user NOT LIKE '0'"));
		$invitati=$total-$membri;
		$ascuns=mysql_num_rows(mysql_query("SELECT * FROM ".$prefix_tabel_bd."online WHERE ascuns='1'"));
		$vizibil=$membri-$ascuns;
		//afisare vizitatori  totali
		$mesaj = mesaje_modul_online(0).' ';
		if($total=="1")	
			{
			$mesaj.=mesaje_modul_online(1);
			}
		else
			{
			$mesaj.='<b>'.$total.'</b> '.mesaje_modul_online(2);
			}
		$mesaj.=' ';
		if($membri=="1")
			{
			$mesaj.=mesaje_modul_online(3);
			}
		else
			{
			$mesaj.='<b>'.$membri.'</b> '.mesaje_modul_online(4);
			}
		$mesaj.=' ';
		if($invitati=="1")
			{
		$mesaj.=mesaje_modul_online(7);
			}
		else
			{
			$mesaj.='<b>'.$invitati.'</b> '.mesaje_modul_online(8);
			}
		$mesaj.=' ';
		if($ascuns=="1")
			{
			$mesaj.=mesaje_modul_online(5);
			}
		else	
			{
			$mesaj.='<b>'.$ascuns.'</b> '.mesaje_modul_online(6);
			}
		print($mesaj);
		}
		
	// functie afisare detalii
	function afisaredetalii($ip)
		{
		global $prefix_tabel_bd;
		$ipnum=$ip;//atribuire ip
		if(verificare_tabela($ipnum)==false)
			{// in cazul in care utilizatorul nu exista in db
			adaugaredb($ipnum);
			}
		else
			{
			modificaredb($ipnum);
			}
		sterge();
		$res=mysql_query("SELECT * FROM ".$prefix_tabel_bd."online WHERE ascuns NOT LIKE '1'");
		echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td width="10%"><div align="center"><strong>'.mesaje_modul_online(9).'</strong></div></td>
			<td width="20%"><div align="center"><strong>'.mesaje_modul_online(10).'</strong></div></td>
			<td width="35%"><div align="center"><strong>'.mesaje_modul_online(11).'</strong></div></td>
			<td width="15%"><div align="center"><strong>'.mesaje_modul_online(12).'</strong></div></td>
			<td width="20%"><div align="center"><strong>'.mesaje_modul_online(13).'</strong></div></td>
			</tr>';
		for($i=0;$i<mysql_num_rows($res);$i++)
			{
			$ii=$i+1;
			$a1=mysql_result($res,$i,"user");
			if($a1=='0')
				{			
				$a1=mesaje_modul_online(14);
				}
			$a2=mysql_result($res,$i,"timp");
			$a3=mysql_result($res,$i,"reload");
			$a4=mysql_result($res,$i,"pagina");
			$a5=mysql_result($res,$i,"ip");		
			if (ereg("[192]{3}",$a5) == true)
				{
				$host = "localhost";
				}
			if (ereg("[192]{3}",$a5) == false)
				{
				$host = gethostbyaddr($a5); // asta pe localhost ori da eroare fatala ori incetineste scriptul cu 19 secunde
				}
			$online=$a3-$a2;
			$ore=floor($online/3600);
			if($ore<>"0")
				{
				$online=$online-$ore*3600;
				}
			else
				{
				$ore="00";
				}
			$minute=floor($online/60);
			if($minute<>"0")
				{
				$online=$online-$minute*60;
				}
			else
				{
				$minute="00";
				}
			$online=$ore.':'.$minute.':'.$online;
			echo '<tr bgcolor="#000099">
	   			 <td  height="1" bgcolor="#000099"></td>
			    <td  height="1" bgcolor="#000099"></td>
			    <td  height="1" bgcolor="#000099"></td>
			    <td  height="1" bgcolor="#000099"></td>
			    <td  height="1" bgcolor="#000099"></td>
			  </tr>
			  <tr bgcolor="#CCCCCC">
			    <td  height="1" bgcolor="#CCCCCC"></td>
			    <td  height="1" bgcolor="#CCCCCC"></td>
			    <td  height="1" bgcolor="#CCCCCC"></td>
			    <td  height="1" bgcolor="#CCCCCC"></td>
			    <td  height="1" bgcolor="#CCCCCC"></td>
			  </tr>
			  <tr bgcolor="#000099">
			    <td  height="1" bgcolor="#000099"></td>
			    <td  height="1" bgcolor="#000099"></td>
			    <td  height="1" bgcolor="#000099"></td>
			    <td  height="1" bgcolor="#000099"></td>
			    <td  height="1" bgcolor="#000099"></td>
			  </tr>';
			echo '<tr>
				<td>'.$a1.'</td>
				<td><div align="center">'.$online.'</div></td>
				<td>'.$a4.'</td>
				<td><div align="center">'.$a5.'</div></td>
				<td>'.$host.'</td>
				</tr>';
			}
		echo '</table>';
		}
		
	//verificare dak exista tabel
	function tabel_verificare()
		{
    	global $prefix_tabel_bd;
		$exists = mysql_query("SELECT * FROM ".$prefix_tabel_bd."online LIMIT 0");
	    if (!$exists)
			{
			 mysql_query("CREATE TABLE ".$prefix_tabel_bd."online (
	  			id int(10) NOT NULL auto_increment,
				  ip varchar(25) NOT NULL default '0',
				  user varchar(25) NOT NULL default '',
				  timp varchar(20) NOT NULL default '',
				  reload varchar(20) NOT NULL default '',
				  pagina varchar(255) NOT NULL default '',
				  ascuns int(1) NOT NULL default '0',
				  PRIMARY KEY  (id)
					) AUTO_INCREMENT=1");
			print("a fost creat tabelul");
			}
		}
	} // aici se incheie definirea functiilor
	
/// asta e pus asa...... ca sa vad daca mere scriptul
$dbase = @mysql_connect($server_bd, $user_bd, $parola_bd); 
@mysql_select_db($nume_bd);
tabel_verificare();

//rulare script propriu zis
$ip = $_SERVER["REMOTE_ADDR"]; // gasit ip
//rulare script
if (isset($_GET['action']))
	{
	if ($_GET['action'] == "see")
		{
		if (isset($_SESSION[$prefix_sesiuni.'_modul_online']))
			{
			if ($_SESSION[$prefix_sesiuni.'_modul_online'] == 2)
				{
				// afiseaza detalii
				afisaredetalii($ip);
				$_SESSION[$prefix_sesiuni.'_modul_online'] = 1;
				echo "<br><br>";
				}
			if ($_SESSION[$prefix_sesiuni.'_modul_online'] != 2)
				{
				// prima data se afiseaza mic
				afisarescurt($ip);
				$_SESSION[$prefix_sesiuni.'_modul_online'] = 2;
				}
			}	
		if (!isset($_SESSION[$prefix_sesiuni.'_modul_online']))
			{
			// prima data se afiseaza mic
			afisarescurt($ip);
			$_SESSION[$prefix_sesiuni.'_modul_online'] = 1;
			}
		}
	if ($_GET['action'] != "see")
		{
		afisarescurt($ip);
		}
	}
if (!isset($_GET['action']))
	{
	afisarescurt($ip);
	$_SESSION[$prefix_sesiuni.'_modul_online'] = 1;
	}
?>