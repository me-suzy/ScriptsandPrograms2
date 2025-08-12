<?php
/* ***************************
*	Modulul de afisare/monitorizare bannere (versiunea free)
*	Versiune modul: 1.0
*	Creat de Birkoff pentru proiectul FAR-PHP
*	Copyright FAR-PHP - www.far-php.ro
*	Contact: contact@far-php.ro
*	Data inceperii modulului: 26-03-2005
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

// se ferifica daca nu sunt deja create functiile
if (!function_exists('mesaje_pt_banner')) 
	{
	// se creaza fisierul cu mesaje ==============================================================	
	function mesaje_pt_banner($valoare)
		{
		global $limbaj_primar;
		global $prefix_sesiuni;
	
		$mesaje_banner_ro = array(
			1 => '<br>Eroare: Nu se pot incarca fisierele externe',
			2 => '<br>0 bannere inregistrate. Clik <a href=admin.php?m=banner&action=new>aici</a> pentru adaugare banner.',
			3 => '<br>Eroare: Nu se poate crea tabelul in baza de date. Eroare nr: ',
			4 => '<br>Tabelul pentru bannere a fost creat.',
			5 => '<br>Meniul pentru bannere a fost creat.',
			6 => '<br>Eroare: Nu se poate extrage bannerul din bd.',
			7 => '<br>0 bannere disponibile. Clik <a href=admin.php?m=banner&action=new>aici</a> pentru adaugare banner.',
			8 => 'Codul reclamei:',
			9 => 'Numele reclamei:',
			10 => 'Adresa web:',
			11 => 'Data start:',
			12 => 'Data stop:',
			13 => 'Bifati daca doriti monitorizare afisare:',
			14 => 'Nr. afisari stop:',
			15 => 'Bifati daca doriti monitorizare click:',
			16 => 'Nr. clik stop:',
			17 => 'Adauga',
			18 => '<br>Eroare: Nu a fost introdus codul pentru banner',
			19 => '<br>Eroare: Nu a fost introdus numele reclamei',
			20 => '<br>Atentie: Nu a fost introdusa adresa web. Nu se poate monitoriza nr. de clickuri',
			21 => '<br>Atentie: Nu a fost specificata data de start si in acest caz se foloseste data curenta',
			22 => '<br>Atentie: Nu a fost specificata data de stop sau nr de clik-uri pentru stop si in acest caz bannerul va fi afisat nelimitat.',
			23 => '<br>Eroare: Nivelul de acces este prea mic pentru aceasta actiune.',
			24 => '<br>Eroare: Nr. pentru afisari stop trebuie sa fie un nr intreg.',
			25 => '<br>Eroare: Nr. pentru clik stop trebuie sa fie un nr intreg.',
			26 => '<br>Bannerul a fost adaugat in baza de date. Asteptati pentru redirectare.',
			27 => '<br>Eroare: Nu se poate conecta la baza de date. Eroare nr: ',
			28 => '<br>Eroare: Nu se poate selecta baza de date. Eroare nr: ',
			29 => '<br>Eroare: Nu se poate executa interogarea la baza de date. Eroare nr: ',
			30 => 'Meniu modul banner',
			31 => 'Adaugare',
			32 => 'Modificare',
			33 => 'Stergere',
			34 => 'Log',
			35 => 'Afisare toate',
			36 => 'Nr:',
			37 => 'Id:',
			38 => 'Banner:',
			39 => 'Nume:',
			40 => 'Url:',
			41 => 'Data start:',
			42 => 'Data stop:',
			43 => 'Monitorizare afisari:',
			44 => 'Nr. afisari facute:',
			45 => 'Nr afisari stop:',
			46 => 'Monitorizare click:',
			47 => 'Nr. click facute:',
			48 => 'Nr. click stop:',
			49 => 'Nu exista banner pentru id-ul cerut.',
			50 => 'Introduceti id banner:',
			51 => 'Continuare',
			52 => '<br>Eroare: Id-ul trebuie sa fie un nr intreg si sa corespunda cu id-ul bannerului pe care doriti sa il stergeti.',
			53 => '<br>Bannerul corespunzator id-ului specificat a fost sters din baza de date.',
			54 => '<br>Eroare: Id-ul trebuie sa fie un nr intreg si sa corespunda cu id-ul bannerului pe care doriti sa il vedeti.',
			55 => '');
		$mesaje_banner_en = array(
			1 => '<br>Error: External files cannot be loaded.',
			2 => '<br>0 banners registered. Click <a href=admin.php?m=banner&action=new>here</a> to add a banner.',
			3 => '<br>Error: Cannot create the table in the database. Error nr: ',
			4 => '<br>The banner table was created.',
			5 => '<br>The banner menu was created.',
			6 => '<br>Error: Cannot display the banner from the bd.',
			7 => '<br>0 banners available. Click <a href=admin.php?m=banner&action=new>here</a> to add a banner.',
			8 => ' Advertisement code:',
			9 => 'Advertisement name:',
			10 => 'Web address:',
			11 => 'Start date:',
			12 => 'Stop date:',
			13 => 'Check if you wish to monitor the display:',
			14 => 'Stop display no:',
			15 => 'Check if you wish to monitor the clicks:',
			16 => 'Stop clicks no:',
			17 => 'Add',
			18 => '<br>Error: The code for the banner was not inserted',
			19 => '<br>Error: The advertisement name was not inserted',
			20 => '<br>Attention: It was not inserted the web address. The number of clicks cannot be monitored',
			21 => '<br>Attention: It was not specified the start date and in this case the current date is used',
			22 => '<br>Attention: It was not specified the stop date of the number of stop clicks and in this case the banner will be displayed without any limits.',
			23 => '<br>Error: Access level is too small for this action.',
			24 => '<br>Error: The stop display number must be an integer.',
			25 => '<br>Error: The click stop number must be an integer.',
			26 => '<br>The banner was added in the database. Please wait to be redirected.',
			27 => '<br>Error: Cannot connect to the database. Error no: ',
			28 => '<br>Error: Cannot select the database. Error no: ',
			29 => '<br>Error: Cannot execute the query to the database. Error no: ',
			30 => 'Banner module menu',
			31 => 'Add',
			32 => 'Modify',
			33 => 'Erase',
			34 => 'Log',
			35 => 'Display all',
			36 => 'No:',
			37 => 'Id:',
			38 => 'Banner:',
			39 => 'Name:',
			40 => 'Url:',
			41 => 'Start date:',
			42 => 'Stop date:',
			43 => 'Display monitoring:',
			44 => 'No. of performed displays:',
			45 => 'No stop displays:',
			46 => 'Click monitoring:',
			47 => 'No. of clicks:',
			48 => 'Nr. of stop clicks:',
			49 => 'There is no banner for the requested id.',
			50 => 'Insert banner Id:',
			51 => 'Carry on',
			52 => '<br>Error: Id must be an integer and match the banner id that you wish to erase.',
			53 => '<br>The banner with the requested id was erased from the database.',
			54 => '<br>Error: Id must be an integer and match the banner id that you wish to see.',
			55 => '');
		
		if (isset($_SESSION[$prefix_sesiuni.'_language_far']))
			{
			if ($_SESSION[$prefix_sesiuni.'_language_far'] == "ro")
				{
				return $mesaje_banner_ro[$valoare];
				}
			if ($_SESSION[$prefix_sesiuni.'_language_far'] == "en")
				{
				return $mesaje_banner_en[$valoare];
				}
			if ($_SESSION[$prefix_sesiuni.'_language_far'] != "en")
				{
				if ($_SESSION[$prefix_sesiuni.'_language_far'] != "ro")
					{
					return $mesaje_banner_en[$valoare];
					}
				}
			}
		if (!isset($_SESSION[$prefix_sesiuni.'_language_far']))
			{
			if ($limbaj_primar == "ro")
				{
				return $mesaje_banner_ro[$valoare];
				}
			if ($limbaj_primar == "en")
				{
				return $mesaje_banner_en[$valoare];
				}
			if ($limbaj_primar != "ro")
				{
				if ($limbaj_primar != "en")
					{
					return $mesaje_banner_en[$valoare];
					}
				}
			}
		}	
		
	// formularul de adaugat bannere - new =======================================================	
	function banner_new($nr_new = "fara")
		{	
		global $server_bd;
		global $user_bd;
		global $parola_bd;
		global $nume_bd;
		global $prefix_tabel_bd;
		global $adresa_url;
		
		if ($nr_new == "fara")
			{
			$cod_banner = '';
			$nume_reclama = '';
			$adresa_web = '';
			$data_start = '';
			$data_stop = '';
			$afisari_stop = '';
			$click_stop = '';		
			
			$formular = '<form name="form1" method="post" action="admin.php?m=banner&action=new">
				<table width="100%"  border="0" cellspacing="0" cellpadding="0">
			  	<tr>
			    <td width="50%"><div align="center">'.mesaje_pt_banner(8).'<br>
			      <textarea name="cod_banner" cols="30" rows="10" id="cod_banner">'.stripslashes($cod_banner).'</textarea> 
			      </div></td>
			    <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
			      <tr>
			        <td width="50%">'.mesaje_pt_banner(9).'</td>
			        <td><input name="nume_reclama" type="text" id="nume_reclama" size="15" value="'.stripslashes($nume_reclama).'"></td>
			      </tr>
			      <tr>
			        <td>'.mesaje_pt_banner(10).' </td>
			        <td><input name="adresa_web" type="text" id="adresa_web" size="15" value="'.stripslashes($adresa_web).'"></td>
			      </tr>
			      <tr>
			        <td>'.mesaje_pt_banner(11).' </td>
			        <td><input name="data_start" type="text" id="data_start" size="15" value="'.$data_start.'"></td>
			      </tr>
			      <tr>
			        <td>'.mesaje_pt_banner(12).'</td>
			        <td><input name="data_stop" type="text" id="data_stop" size="15" value="'.$data_stop.'"></td>
			      </tr>
			      <tr>
			        <td>'.mesaje_pt_banner(13).' </td>
			        <td><input name="mon_afisare" type="checkbox" id="mon_afisare" value="1"></td>
			      </tr>
			      <tr>
			        <td>'.mesaje_pt_banner(14).' </td>
			        <td><input name="afisari_stop" type="text" id="afisari_stop" size="15" value="'.$afisari_stop.'"></td>
			      </tr>
			      <tr>
			        <td>'.mesaje_pt_banner(15).' </td>
			        <td><input name="mon_click" type="checkbox" id="mon_click" value="1"></td>
				      </tr>
			      <tr>
			        <td>'.mesaje_pt_banner(16).' </td>
			        <td><input name="click_stop" type="text" id="click_stop" size="15" value="'.$click_stop.'"></td>
			      </tr>
			      <tr>
			        <td><div align="center">
			          <input type="submit" name="Submit" value="'.mesaje_pt_banner(17).'">
			        </div></td>
			        <td>&nbsp;</td>
			      </tr>
			    </table></td>
			  </tr>
			</table>
			</form>';
			
			echo $formular;
			}
		if ($nr_new == 2)
			{
			// preluare valori trimise
			$cod_banner = $_POST['cod_banner'];
			$nume_reclama = $_POST['nume_reclama'];
			$adresa_web = $_POST['adresa_web'];
			$data_start = $_POST['data_start'];
			$data_stop = $_POST['data_stop'];			
			$afisari_stop = $_POST['afisari_stop'];
			$click_stop = $_POST['click_stop'];	
			if (isset($_POST['mon_afisare']))
				{
				$mon_afisare = $_POST['mon_afisare'];
				$af_ch_1 = '<input name="mon_afisare" type="checkbox" id="mon_afisare" value="1" checked>';
				}
			if (!isset($_POST['mon_afisare']))
				{
				$mon_afisare = '0';
				$af_ch_1 = '<input name="mon_afisare" type="checkbox" id="mon_afisare" value="1">';
				}
			if (isset($_POST['mon_click']))
				{
				$mon_click = $_POST['mon_click'];
				$af_ch_2 = '<input name="mon_click" type="checkbox" id="mon_click" value="1" checked>';
				}
			if (!isset($_POST['mon_click']))
				{
				$mon_click = '0';
				$af_ch_2 = '<input name="mon_click" type="checkbox" id="mon_click" value="1">';
				}
			
			// verificare valori trimise
			$verificare = 0;
			if (empty($cod_banner))
				{
				$verificare = 1;
				echo mesaje_pt_banner(18);
				}
			if (empty($nume_reclama))
				{
				$verificare = 1;
				echo mesaje_pt_banner(19);
				}
			if (empty($adresa_web))
				{				
				echo mesaje_pt_banner(20);
				}
			if (empty($data_start))
				{				
				echo mesaje_pt_banner(21);
				$data_start = date("Y-m-d",time());				
				}
			if (!empty($click_stop))
				{				
				$nr_af = strlen($click_stop);				
				if (ereg("^[0-9]{1,".$nr_af."}$",$click_stop) == false)
					{
					echo mesaje_pt_banner(25);
					$verificare = 1;
					}
				}
			if (empty($data_stop))
				{
				if (empty($click_stop))
					{				
					echo mesaje_pt_banner(22);
					$click_stop = "0";
					}
				$data_stop = "0000-00-00";
				}
			if (empty($click_stop))
				{					
				$click_stop = "0";
				}
			if (empty($afisari_stop))
				{				
				$afisari_stop = '0';				
				}
			if (!empty($afisari_stop))
				{
				$nr_af = strlen($afisari_stop);				
				if (ereg("^[0-9]{1,".$nr_af."}$",$afisari_stop) == false)
					{
					echo mesaje_pt_banner(24);
					$verificare = 1;
					}
				}			
			// daca e ok se salveaza in bd
			if ($verificare == 0)
				{
				// conectarea la bd
				$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die(mesaje_pt_banner(27)."1");
				mysql_select_db($nume_bd, $conectare) OR die(mesaje_pt_banner(28)."1");
				$cod_banner = mysql_real_escape_string($cod_banner,$conectare);
				$nume_reclama = mysql_real_escape_string($nume_reclama,$conectare);
				$adresa_web = mysql_real_escape_string($adresa_web,$conectare);				
				$interogare = "INSERT INTO ".$prefix_tabel_bd."banner (cod,	nume, url,
					data_start,	data_stop, monitorizare_afisari, nr_afisari_stop,
					monitorizare_click, nr_click_stop) VALUES ('".$cod_banner."',
					'".$nume_reclama."', '".$adresa_web."', '".$data_start."', '".$data_stop."',
					'".$mon_afisare."', '".$afisari_stop."', '".$mon_click."', '".$click_stop."')";
				// echo $interogare;
				$rezultat = mysql_query($interogare, $conectare) OR die(mesaje_pt_banner(29)."1");
				echo '<META HTTP-EQUIV = "Refresh" Content = "5; URL ='.$adresa_url.'">'.mesaje_pt_banner(26);
				}
			// daca nu e ok se afiseaza formularul din nou cu datele trimise
			if ($verificare == 1)
				{				
				$formular = '<form name="form1" method="post" action="admin.php?m=banner&action=new">
						<table width="100%"  border="0" cellspacing="0" cellpadding="0">
					  	<tr>
					    <td width="50%"><div align="center">'.mesaje_pt_banner(8).'<br>
					      <textarea name="cod_banner" cols="30" rows="10" id="cod_banner">'.stripslashes($cod_banner).'</textarea> 
					      </div></td>
					    <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
					      <tr>
					        <td width="50%">'.mesaje_pt_banner(9).'</td>
					        <td><input name="nume_reclama" type="text" id="nume_reclama" size="15" value="'.stripslashes($nume_reclama).'"></td>
					      </tr>
					      <tr>
					        <td>'.mesaje_pt_banner(10).' </td>
					        <td><input name="adresa_web" type="text" id="adresa_web" size="15" value="'.stripslashes($adresa_web).'"></td>
					      </tr>
					      <tr>
					        <td>'.mesaje_pt_banner(11).' </td>
					        <td><input name="data_start" type="text" id="data_start" size="15" value="'.$data_start.'"></td>
					      </tr>
					      <tr>
					        <td>'.mesaje_pt_banner(12).'</td>
					        <td><input name="data_stop" type="text" id="data_stop" size="15" value="'.$data_stop.'"></td>
					      </tr>
					      <tr>
					        <td>'.mesaje_pt_banner(13).' </td>
					        <td>'.$af_ch_1.'</td>
					      </tr>
					      <tr>
					        <td>'.mesaje_pt_banner(14).' </td>
					        <td><input name="afisari_stop" type="text" id="afisari_stop" size="15" value="'.$afisari_stop.'"></td>
					      </tr>
					      <tr>
					        <td>'.mesaje_pt_banner(15).' </td>
					        <td>'.$af_ch_2.'</td>
						      </tr>
					      <tr>
					        <td>'.mesaje_pt_banner(16).' </td>
					        <td><input name="click_stop" type="text" id="click_stop" size="15" value="'.$click_stop.'"></td>
					      </tr>
					      <tr>
					        <td><div align="center">
					          <input type="submit" name="Submit" value="'.mesaje_pt_banner(17).'">
					        </div></td>
					        <td>&nbsp;</td>
					      </tr>
					    </table></td>
					  </tr>
					</table>
					</form>';			
					echo $formular;
				}
			}
		}
		
	// functia pentru schimbare bannere - change =================================================	
	function banner_change($nr_change = "fara")
		{
		global $server_bd;
		global $user_bd;
		global $parola_bd;
		global $nume_bd;
		global $prefix_tabel_bd;
		global $adresa_url;
		
		if ($nr_change == "fara")
			{
			// se cere id-ul bannerului pentru modificare
			echo '<form name="form1" method="POST" action="admin.php?m=banner&action=change">
  				'.mesaje_pt_banner(50).'<input name="id" type="text" id="id" size="10">
				<input type="submit" name="Submit" value="'.mesaje_pt_banner(51).'"></form>';
			}
		
		if ($nr_change == 1)
			{
			// se afiseaza datele bannerului
			$id = $_POST['id'];
			// conectarea la bd
			$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die(mesaje_pt_banner(27)."1");
			mysql_select_db($nume_bd, $conectare) OR die(mesaje_pt_banner(28)."1");
			$interogare = "SELECT * FROM ".$prefix_tabel_bd."banner WHERE id = '".$id."'";
			$rezultat = mysql_query($interogare, $conectare) OR die(mesaje_pt_banner(29)."1");
			$total = mysql_num_rows($rezultat);
			if ($total != 0)
				{				
				while ($rand = mysql_fetch_array($rezultat))
					{		
					$cod_banner = $rand['cod'];
					$nume_reclama = $rand['nume'];
					$adresa_web = $rand['url'];
					$data_start = $rand['data_start'];
					$data_stop = $rand['data_stop'];
					$afisari_stop = $rand['nr_afisari_stop'];
					$click_stop = $rand['nr_click_stop'];
					$mon_afisare = $rand['monitorizare_afisari'];
					$mon_click = $rand['monitorizare_click'];
					if ($mon_afisare == 1)
						{						
						$af_ch_1 = '<input name="mon_afisare" type="checkbox" id="mon_afisare" value="1" checked>';
						}
					if ($mon_afisare == 0)
						{						
						$af_ch_1 = '<input name="mon_afisare" type="checkbox" id="mon_afisare" value="1">';
						}
					if ($mon_click == 1)
						{						
						$af_ch_2 = '<input name="mon_click" type="checkbox" id="mon_click" value="1" checked>';
						}
					if ($mon_click == 0)
						{						
						$af_ch_2 = '<input name="mon_click" type="checkbox" id="mon_click" value="1">';
						}
					}
				$formular = '<form name="form1" method="post" action="admin.php?m=banner&action=change">
					<table width="100%"  border="0" cellspacing="0" cellpadding="0">
				  	<tr>
				    <td width="50%"><div align="center">'.mesaje_pt_banner(8).'<br>
				      <textarea name="cod_banner" cols="30" rows="10" id="cod_banner">'.stripslashes($cod_banner).'</textarea> 
				    </div></td>
				    <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
				      <tr>
				        <td width="50%">'.mesaje_pt_banner(9).'</td>
				        <td><input name="nume_reclama" type="text" id="nume_reclama" size="15" value="'.stripslashes($nume_reclama).'"></td>
				      </tr>
				      <tr>
			        <td>'.mesaje_pt_banner(10).' </td>
			        <td><input name="adresa_web" type="text" id="adresa_web" size="15" value="'.stripslashes($adresa_web).'"></td>
				      </tr>
				      <tr>
			        <td>'.mesaje_pt_banner(11).' </td>
			        <td><input name="data_start" type="text" id="data_start" size="15" value="'.$data_start.'"></td>
			    	  </tr>
				      <tr>
			        <td>'.mesaje_pt_banner(12).'</td>
			        <td><input name="data_stop" type="text" id="data_stop" size="15" value="'.$data_stop.'"></td>
			      </tr>
			      <tr>
			        <td>'.mesaje_pt_banner(13).' </td>
			        <td>'.$af_ch_1.'</td>
			      </tr>
			      <tr>
			        <td>'.mesaje_pt_banner(14).' </td>
			        <td><input name="afisari_stop" type="text" id="afisari_stop" size="15" value="'.$afisari_stop.'"></td>
			      </tr>
			      <tr>
			        <td>'.mesaje_pt_banner(15).' </td>
			        <td>'.$af_ch_2.'</td>
				      </tr>
			      <tr>
			        <td>'.mesaje_pt_banner(16).' </td>
			        <td><input name="click_stop" type="text" id="click_stop" size="15" value="'.$click_stop.'"></td>
			      </tr>
			      <tr>
			        <td><div align="center">
			          <input type="submit" name="Submit" value="'.mesaje_pt_banner(17).'">
					  <input name="id2" type="hidden" id="id2" value="'.$id.'">
			        </div></td>
			        <td>&nbsp;</td>
				      </tr>
				    </table></td>
					  </tr>
					</table>
					</form>';
			
				echo $formular;
				}
			}
		if ($nr_change == 2)
			{
			// se verifica datele modificate
			// preluare valori trimise
			$cod_banner = $_POST['cod_banner'];
			$nume_reclama = $_POST['nume_reclama'];
			$adresa_web = $_POST['adresa_web'];
			$data_start = $_POST['data_start'];
			$data_stop = $_POST['data_stop'];			
			$afisari_stop = $_POST['afisari_stop'];
			$click_stop = $_POST['click_stop'];	
			$id = $_POST['id2'];
			if (isset($_POST['mon_afisare']))
				{
				$mon_afisare = $_POST['mon_afisare'];
				$af_ch_1 = '<input name="mon_afisare" type="checkbox" id="mon_afisare" value="1" checked>';
				}
			if (!isset($_POST['mon_afisare']))
				{
				$mon_afisare = '0';
				$af_ch_1 = '<input name="mon_afisare" type="checkbox" id="mon_afisare" value="1">';
				}
			if (isset($_POST['mon_click']))
				{
				$mon_click = $_POST['mon_click'];
				$af_ch_2 = '<input name="mon_click" type="checkbox" id="mon_click" value="1" checked>';
				}
			if (!isset($_POST['mon_click']))
				{
				$mon_click = '0';
				$af_ch_2 = '<input name="mon_click" type="checkbox" id="mon_click" value="1">';
				}
			
			// verificare valori trimise
			$verificare = 0;
			if (empty($cod_banner))
				{
				$verificare = 1;
				echo mesaje_pt_banner(18);
				}
			if (empty($nume_reclama))
				{
				$verificare = 1;
				echo mesaje_pt_banner(19);
				}
			if (empty($adresa_web))
				{				
				echo mesaje_pt_banner(20);
				}
			if (empty($data_start))
				{				
				echo mesaje_pt_banner(21);
				$data_start = date("Y-m-d",time());				
				}
			if (!empty($click_stop))
				{				
				$nr_af = strlen($click_stop);				
				if (ereg("^[0-9]{1,".$nr_af."}$",$click_stop) == false)
					{
					echo mesaje_pt_banner(25);
					$verificare = 1;
					}
				}
			if (empty($data_stop))
				{
				if (empty($click_stop))
					{				
					echo mesaje_pt_banner(22);
					$click_stop = "0";
					}
				$data_stop = "0000-00-00";
				}
			if (empty($click_stop))
				{					
				$click_stop = "0";
				}
			if (empty($afisari_stop))
				{				
				$afisari_stop = '0';				
				}
			if (!empty($afisari_stop))
				{
				$nr_af = strlen($afisari_stop);				
				if (ereg("^[0-9]{1,".$nr_af."}$",$afisari_stop) == false)
					{
					echo mesaje_pt_banner(24);
					$verificare = 1;
					}
				}			
			// daca e ok se salveaza in bd
			if ($verificare == 0)
				{				
				// conectarea la bd
				$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die(mesaje_pt_banner(27)."2");
				mysql_select_db($nume_bd, $conectare) OR die(mesaje_pt_banner(28)."2");
				$cod_banner = mysql_real_escape_string($cod_banner,$conectare);
				$nume_reclama = mysql_real_escape_string($nume_reclama,$conectare);
				$adresa_web = mysql_real_escape_string($adresa_web,$conectare);				
				$interogare = "UPDATE ".$prefix_tabel_bd."banner SET 
					cod = '".$cod_banner."', nume = '".$nume_reclama."', url = '".$adresa_web."',
					data_start = '".$data_start."',	data_stop = '".$data_stop."', monitorizare_afisari = '".$mon_afisare."', 
					nr_afisari_stop = '".$afisari_stop."', monitorizare_click = '".$mon_click."', 
					nr_click_stop = '".$click_stop."' WHERE id = '".$id."'";
				$rezultat = mysql_query($interogare, $conectare) OR die(mesaje_pt_banner(29)."2");
				echo '<META HTTP-EQUIV = "Refresh" Content = "5; URL ='.$adresa_url.'">'.mesaje_pt_banner(26);
				}
			// daca nu e ok se afiseaza formularul din nou cu datele trimise
			if ($verificare == 1)
				{				
				$formular = '<form name="form1" method="post" action="admin.php?m=banner&action=new">
						<table width="100%"  border="0" cellspacing="0" cellpadding="0">
					  	<tr>
					    <td width="50%"><div align="center">'.mesaje_pt_banner(8).'<br>
					      <textarea name="cod_banner" cols="30" rows="10" id="cod_banner">'.stripslashes($cod_banner).'</textarea> 
					      </div></td>
					    <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
					      <tr>
					        <td width="50%">'.mesaje_pt_banner(9).'</td>
					        <td><input name="nume_reclama" type="text" id="nume_reclama" size="15" value="'.stripslashes($nume_reclama).'"></td>
					      </tr>
					      <tr>
					        <td>'.mesaje_pt_banner(10).' </td>
					        <td><input name="adresa_web" type="text" id="adresa_web" size="15" value="'.stripslashes($adresa_web).'"></td>
					      </tr>
					      <tr>
					        <td>'.mesaje_pt_banner(11).' </td>
					        <td><input name="data_start" type="text" id="data_start" size="15" value="'.$data_start.'"></td>
					      </tr>
					      <tr>
					        <td>'.mesaje_pt_banner(12).'</td>
					        <td><input name="data_stop" type="text" id="data_stop" size="15" value="'.$data_stop.'"></td>
					      </tr>
					      <tr>
					        <td>'.mesaje_pt_banner(13).' </td>
					        <td>'.$af_ch_1.'</td>
					      </tr>
					      <tr>
					        <td>'.mesaje_pt_banner(14).' </td>
					        <td><input name="afisari_stop" type="text" id="afisari_stop" size="15" value="'.$afisari_stop.'"></td>
					      </tr>
					      <tr>
					        <td>'.mesaje_pt_banner(15).' </td>
					        <td>'.$af_ch_2.'</td>
						      </tr>
					      <tr>
					        <td>'.mesaje_pt_banner(16).' </td>
					        <td><input name="click_stop" type="text" id="click_stop" size="15" value="'.$click_stop.'"></td>
					      </tr>
					      <tr>
					        <td><div align="center">
					          <input type="submit" name="Submit" value="'.mesaje_pt_banner(17).'">
							  <input name="id2" type="hidden" id="id2" value="'.$id.'">
					        </div></td>
					        <td>&nbsp;</td>
					      </tr>
					    </table></td>
					  </tr>
					</table>
					</form>';			
				echo $formular;
				}
			}		
		}
	
	// functia pentru stergere bannere - del =====================================================	
	function banner_del($nr_del = "fara")
		{
		global $server_bd;
		global $user_bd;
		global $parola_bd;
		global $nume_bd;
		global $prefix_tabel_bd;
		global $adresa_url;
		
		if ($nr_del == "fara")
			{
			// se cere id-ul bannerului pentru modificare
			echo '<form name="form1" method="POST" action="admin.php?m=banner&action=del">
  				'.mesaje_pt_banner(50).'<input name="id" type="text" id="id" size="10">
				<input type="submit" name="Submit" value="'.mesaje_pt_banner(51).'"></form>';
			}
		
		if ($nr_del == 1)
			{
			$verifcare =0;
			if (isset ($_POST['id']))
				{
				$id = $_POST['id'];
				if (!empty($id))
					{
					$nr_id = strlen($id);				
					if (ereg("^[0-9]{1,".$nr_id."}$",$id) == false)
						{
						echo mesaje_pt_banner(52);
						$verificare = 1;
						}
					}
				}			
			// daca e ok se salveaza in bd
			if ($verificare == 0)
				{				
				// conectarea la bd
				$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die(mesaje_pt_banner(27)."1");
				mysql_select_db($nume_bd, $conectare) OR die(mesaje_pt_banner(28)."1");
				$interogare = "DELETE FROM ".$prefix_tabel_bd."banner WHERE id = '".$id."'";
				$rezultat = mysql_query($interogare, $conectare) OR die(mesaje_pt_banner(29)."1");
				echo '<META HTTP-EQUIV = "Refresh" Content = "5; URL ='.$adresa_url.'">'.mesaje_pt_banner(53);
				}
			if ($verificare == 1)
				{
				// se cere id-ul bannerului pentru modificare
				echo '<form name="form1" method="POST" action="admin.php?m=banner&action=del">
  					'.mesaje_pt_banner(50).'<input name="id" type="text" id="id" size="10">
					<input type="submit" name="Submit" value="'.mesaje_pt_banner(51).'"></form>';
				}
			}
		}
		
	// functia pentru log bannere - log ==========================================================	
	function banner_log($nr_log = "fara")
		{
		global $server_bd;
		global $user_bd;
		global $parola_bd;
		global $nume_bd;
		global $prefix_tabel_bd;
		global $adresa_url;
		
		if ($nr_log == "fara")
			{
			// se cere id-ul bannerului pentru log
			echo '<form name="form1" method="POST" action="admin.php?m=banner&action=log">
  				'.mesaje_pt_banner(50).'<input name="id" type="text" id="id" size="10">
				<input type="submit" name="Submit" value="'.mesaje_pt_banner(51).'"></form>';
			}
		
		if ($nr_log == 1)
			{
			$verificare =0;
			if (isset ($_POST['id']))
				{
				$id = $_POST['id'];
				if (!empty($id))
					{
					$nr_id = strlen($id);				
					if (ereg("^[0-9]{1,".$nr_id."}$",$id) == false)
						{
						echo mesaje_pt_banner(54);
						$verificare = 1;
						}
					}
				}			
			// daca e ok se salveaza in bd
			if ($verificare == 0)
				{	
				// conectarea la bd
				$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die(mesaje_pt_banner(27)."1");
				mysql_select_db($nume_bd, $conectare) OR die(mesaje_pt_banner(28)."1");
				$interogare = "SELECT * FROM ".$prefix_tabel_bd."banner WHERE id = '".$id."'";
				$rezultat = mysql_query($interogare, $conectare) OR die(mesaje_pt_banner(29)."1");
				$total = mysql_num_rows($rezultat);
				if ($total != 0)
					{					
					while ($rand = mysql_fetch_array($rezultat))
						{	
						echo '<table width="100%"  border="1" cellpadding="0" cellspacing="0">
							<tr>
								<td width="15%"><div align="center">'.mesaje_pt_banner(37).'</div></td>
								<td><div align="left">'.$rand['id'].'</div></td>
							</tr>
							<tr>
								<td><div align="center">'.mesaje_pt_banner(39).'</div></td>
								<td><div align="left">'.stripslashes($rand['nume']).'</div></td>
							</tr>
							<tr>
								<td><div align="center">'.mesaje_pt_banner(40).'</div></td>
								<td><div align="left">'.stripslashes($rand['url']).'</div></td>
							</tr>
							<tr>
								<td><div align="center">'.mesaje_pt_banner(41).'</div></td>
								<td><div align="left">'.$rand['data_start'].'</div></td>
							</tr>
							<tr>
								<td><div align="center">'.mesaje_pt_banner(42).'</div></td>
								<td><div align="left">'.$rand['data_stop'].'</div></td>
							</tr>
							<tr>
								<td><div align="center">'.mesaje_pt_banner(44).'</div></td>
								<td><div align="left">'.$rand['nr_afisari_facute'].'</div></td>
							</tr>
							<tr>
								<td><div align="center">'.mesaje_pt_banner(45).'</div></td>
								<td><div align="left">'.$rand['nr_afisari_stop'].'</div></td>
							</tr>
							<tr>
								<td><div align="center">'.mesaje_pt_banner(47).'</div></td>
								<td><div align="left">'.$rand['nr_click_facute'].'</div></td>
							</tr>
							<tr>
								<td><div align="center">'.mesaje_pt_banner(48).'</div></td>
								<td><div align="left">'.$rand['nr_click_stop'].'</div></td>
							</tr>							
							</table>';
						}
					}
				}
			if ($verificare == 1)
				{
				// se cere id-ul bannerului pentru modificare
				echo '<form name="form1" method="POST" action="admin.php?m=banner&action=log">
  					'.mesaje_pt_banner(50).'<input name="id" type="text" id="id" size="10">
					<input type="submit" name="Submit" value="'.mesaje_pt_banner(51).'"></form>';
				}
			}
		}
		
	// functia pentru afisarea bannerelor - all ==================================================	
	function banner_all($nr_all = "fara")
		{
		global $server_bd;
		global $user_bd;
		global $parola_bd;
		global $nume_bd;
		global $prefix_tabel_bd;
		
		if ($nr_all == "fara")
			{
			// se afiseaza toate bannerele din bd
			$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die(mesaje_pt_banner(27)."1");
			mysql_select_db($nume_bd, $conectare) OR die(mesaje_pt_banner(28)."1");
			$interogare = "SELECT * FROM ".$prefix_tabel_bd."banner";
			$rezultat = mysql_query($interogare, $conectare) OR die(mesaje_pt_banner(29)."1");
			$total = mysql_num_rows($rezultat);
			if ($total != 0)
				{		
				$nr = 1;			
				while ($rand = mysql_fetch_array($rezultat))
					{				
					echo '<table width="100%"  border="1" cellpadding="0" cellspacing="0">
						<tr>
							<td width="15%"><div align="center">'.mesaje_pt_banner(36).'</div></td>
							<td><div align="left">'.$nr.'</div></td>
						</tr>
						<tr>
							<td><div align="center">'.mesaje_pt_banner(37).'</div></td>
							<td><div align="left">'.$rand['id'].'</div></td>
						</tr>
						<tr>
							<td><div align="center">'.mesaje_pt_banner(38).'</div></td>
							<td><div align="left">'.stripslashes($rand['cod']).'</div></td>
						</tr>
						<tr>
							<td><div align="center">'.mesaje_pt_banner(39).'</div></td>
							<td><div align="left">'.stripslashes($rand['nume']).'</div></td>
						</tr>
						<tr>
							<td><div align="center">'.mesaje_pt_banner(40).'</div></td>
							<td><div align="left">'.stripslashes($rand['url']).'</div></td>
						</tr>
						<tr>
							<td><div align="center">'.mesaje_pt_banner(41).'</div></td>
							<td><div align="left">'.$rand['data_start'].'</div></td>
						</tr>
						<tr>
							<td><div align="center">'.mesaje_pt_banner(42).'</div></td>
							<td><div align="left">'.$rand['data_stop'].'</div></td>
						</tr>
						<tr>
							<td><div align="center">'.mesaje_pt_banner(43).'</div></td>
							<td><div align="left">'.$rand['monitorizare_afisari'].'</div></td>
						</tr>
						<tr>
							<td><div align="center">'.mesaje_pt_banner(44).'</div></td>
							<td><div align="left">'.$rand['nr_afisari_facute'].'</div></td>
						</tr>
						<tr>
							<td><div align="center">'.mesaje_pt_banner(45).'</div></td>
							<td><div align="left">'.$rand['nr_afisari_stop'].'</div></td>
						</tr>
						<tr>
							<td><div align="center">'.mesaje_pt_banner(46).'</div></td>
							<td><div align="left">'.$rand['monitorizare_click'].'</div></td>
						</tr>
						<tr>
							<td><div align="center">'.mesaje_pt_banner(47).'</div></td>
							<td><div align="left">'.$rand['nr_click_facute'].'</div></td>
						</tr>
						<tr>
							<td><div align="center">'.mesaje_pt_banner(48).'</div></td>
							<td><div align="left">'.$rand['nr_click_stop'].'</div></td>
						</tr>
						<tr>
							<td><div align="center">&nbsp;</div></td>
							<td><div align="left">&nbsp;</div></td>
						</tr>
						</table>';
					$nr++;		
					}
				}
			}
		if ($nr_all != "fara")
			{
			// se afiseaza detaliile despre bannerul corespunzator id-ului respectv
			$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die(mesaje_pt_banner(27)."1");
			mysql_select_db($nume_bd, $conectare) OR die(mesaje_pt_banner(28)."1");
			$interogare = "SELECT * FROM ".$prefix_tabel_bd."banner WHERE id = '".$nr_all."'";
			$rezultat = mysql_query($interogare, $conectare) OR die(mesaje_pt_banner(29)."1");
			$total = mysql_num_rows($rezultat);
			if ($total != 0)
				{						
				while ($rand = mysql_fetch_array($rezultat))
					{
					echo '<table width="100%"  border="1" cellpadding="0" cellspacing="0">						
						<tr>
							<td><div align="center">'.mesaje_pt_banner(37).'</div></td>
							<td><div align="left">'.$rand['id'].'</div></td>
						</tr>
						<tr>
							<td><div align="center">'.mesaje_pt_banner(38).'</div></td>
							<td><div align="left">'.stripslashes($rand['cod']).'</div></td>
						</tr>
						<tr>
							<td><div align="center">'.mesaje_pt_banner(39).'</div></td>
							<td><div align="left">'.stripslashes($rand['nume']).'</div></td>
						</tr>
						<tr>
							<td><div align="center">'.mesaje_pt_banner(40).'</div></td>
							<td><div align="left">'.stripslashes($rand['url']).'</div></td>
						</tr>
						<tr>
							<td><div align="center">'.mesaje_pt_banner(41).'</div></td>
							<td><div align="left">'.$rand['data_start'].'</div></td>
						</tr>
						<tr>
							<td><div align="center">'.mesaje_pt_banner(42).'</div></td>
							<td><div align="left">'.$rand['data_stop'].'</div></td>
						</tr>
						<tr>
							<td><div align="center">'.mesaje_pt_banner(43).'</div></td>
							<td><div align="left">'.$rand['monitorizare_afisari'].'</div></td>
						</tr>
						<tr>
							<td><div align="center">'.mesaje_pt_banner(44).'</div></td>
							<td><div align="left">'.$rand['nr_afisari_facute'].'</div></td>
						</tr>
						<tr>
							<td><div align="center">'.mesaje_pt_banner(45).'</div></td>
							<td><div align="left">'.$rand['nr_afisari_stop'].'</div></td>
						</tr>
						<tr>
							<td><div align="center">'.mesaje_pt_banner(46).'</div></td>
							<td><div align="left">'.$rand['monitorizare_click'].'</div></td>
						</tr>
						<tr>
							<td><div align="center">'.mesaje_pt_banner(47).'</div></td>
							<td><div align="left">'.$rand['nr_click_facute'].'</div></td>
						</tr>
						<tr>
							<td><div align="center">'.mesaje_pt_banner(48).'</div></td>
							<td><div align="left">'.$rand['nr_click_stop'].'</div></td>
						</tr>
						<tr>
							<td><div align="center">&nbsp;</div></td>
							<td><div align="left">&nbsp;</div></td>
						</tr>
						</table>';
					}
				}
			if ($total == 0)
				{	
				echo mesaje_pt_banner(49);
				}
			}
		}
	function banner_save($nr_save)
		{
		global $server_bd;
		global $user_bd;
		global $parola_bd;
		global $nume_bd;
		global $prefix_tabel_bd;
		
		// se salveaza in bd nr afisarii daca trebuie monitorizata
		$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die(mesaje_pt_banner(27)."1");
		mysql_select_db($nume_bd, $conectare) OR die(mesaje_pt_banner(28)."1");
		$interogare = "SELECT * FROM ".$prefix_tabel_bd."banner WHERE id = '".$nr_save."'";
		$rezultat = mysql_query($interogare, $conectare) OR die(mesaje_pt_banner(29)."1");
		$verificare = 1;
		while ($rand = mysql_fetch_array($rezultat))
			{
			if ($rand['monitorizare_afisari'] == 1)
				{
				$verificare = 0;
				$tmp = $rand['timp_afisare_facuta']." - ".time();
				}
			if ($rand['monitorizare_afisari'] != 1)
				{
				$verificare = 1;
				}
			}
		if ($verificare == 0)
			{
			$interogare = "UPDATE ".$prefix_tabel_bd."banner SET nr_afisari_facute=nr_afisari_facute+1, 
				timp_afisare_facuta = '".$tmp."' WHERE id = '".$nr_save."'";
			$rezultat = mysql_query($interogare, $conectare) OR die(mesaje_pt_banner(29)."1");
			}
		}
	}
	
// se verifica daca exista tabelul in bd
$verificare = 0;
$nr_reclame = 0;
$conectare = @mysql_connect($server_bd, $user_bd, $parola_bd);
@mysql_select_db($nume_bd, $conectare);
$interogare = "SELECT * FROM ".$prefix_tabel_bd."banner";
$rezultat = @mysql_query($interogare, $conectare) OR $verificare = 1;		
if ($verificare == 0) // daca tabelul e creat
	{
	$total = @mysql_num_rows($rezultat);
	$nr_reclame = $total;
	if ($total == 0) // se verifica daca exista bannere salvate in bd
		{
		echo mesaje_pt_banner(2);
		}	
	}
	
// daca nu este creat tabelul in bd
if ($verificare == 1)
	{
	$interogare = "CREATE TABLE IF NOT EXISTS ".$prefix_tabel_bd."banner (
		id int(11) NOT NULL auto_increment,
		cod text NOT NULL,
		nume varchar(35) NOT NULL default '',
		url varchar(35) NOT NULL default '',
		data_start date NOT NULL default '0000-00-00',
		data_stop date NOT NULL default '0000-00-00',
		monitorizare_afisari int(1) NOT NULL default '0',
		nr_afisari_facute int(10) NOT NULL default '0',
		nr_afisari_stop int(10) NOT NULL default '0',
		timp_afisare_facuta text NOT NULL,
		monitorizare_click int(1) NOT NULL default '0',
		nr_click_facute int(10) NOT NULL default '0',
		ip_click_facute text NOT NULL,
		timp_click_facute text NOT NULL,
		nr_click_stop int(10) NOT NULL default '0',
		PRIMARY KEY  (id)
		) AUTO_INCREMENT=1";
	$rezultat = @mysql_query($interogare, $conectare) OR die(mesaje_pt_banner(3)."1");
	echo mesaje_pt_banner(4);
	$meniu_banner = '<table width="100%" border="0" cellspacing="1" cellpadding="0">
				<tr><td bgcolor="#000000">
				<div align="center"><font color="#FFFFFF"><strong>'.mesaje_pt_banner(30).'</strong></font></div>
				</td></tr>				
				<tr><td>
				<div align="center"><a href="admin.php?m=banner&action=new" class="glink">'.mesaje_pt_banner(31).'</a></div>
				</td></tr>
				<tr><td>
				<div align="center"><a href="admin.php?m=banner&action=change" class="glink">'.mesaje_pt_banner(32).'</a></div>
				</td></tr>
				<tr><td>
				<div align="center"><a href="admin.php?m=banner&action=del" class="glink">'.mesaje_pt_banner(33).'</a></div>
				</td></tr>
				<tr><td>
				<div align="center"><a href="admin.php?m=banner&action=log" class="glink">'.mesaje_pt_banner(34).'</a></div>
				</td></tr>
				<tr><td>
				<div align="center"><a href="admin.php?m=banner&action=all" class="glink">'.mesaje_pt_banner(35).'</a></div>
				</td></tr>
				<tr><td>&nbsp;</td></tr>
				</table>';
	$meniu_banner = mysql_real_escape_string($meniu_banner,$conectare);
	$interogare = "INSERT INTO ".$prefix_tabel_bd."menu (html, type, priority, location, language, status) 
				VALUES ('".$meniu_banner."', '2', '5', '2', '".$_SESSION[$prefix_sesiuni.'_language_far']."', '2')";
	$rezultat = @mysql_query($interogare, $conectare) OR die(mesaje_pt_banner(3)."2");
	echo mesaje_pt_banner(5);
	}

// se extrag bannerele din bd
$data_azi = date("Y-m-d", time());
$interogare = "SELECT * FROM ".$prefix_tabel_bd."banner WHERE 
	(
	 (
	  (data_start <='".$data_azi."' AND data_stop = '0000-00-00') OR 
	  (data_start <='".$data_azi."' AND data_stop <= '".$data_azi."')
	 ) AND
	 (
	  (monitorizare_afisari = 1 AND nr_afisari_facute <= nr_afisari_stop) OR	  
	  (monitorizare_afisari = 1 AND nr_afisari_stop = 0) OR
	  (monitorizare_afisari = 0)
	 ) AND
	 (
	  (monitorizare_click = 1 AND nr_click_facute <= nr_click_stop) OR
	  (monitorizare_click = 1 AND nr_click_stop = 0) OR
	  (monitorizare_click = 0)
	 )
	)";
$rezultat = @mysql_query($interogare, $conectare) OR die(mesaje_pt_banner(6));		
$total = @mysql_num_rows($rezultat);
//echo "<br>Total reclame: ".$total;
$nr_reclame = $total;
// se genereaza variabila de sesiune pentu reclame
if (!isset($_SESSION[$prefix_sesiuni.'_modul_banner']))
	{
	//echo "<br>Setare sesiune ";
	$_SESSION[$prefix_sesiuni.'_modul_banner'] = 1;
	}
if (isset($_SESSION[$prefix_sesiuni.'_modul_banner']))
	{	
	if ($_SESSION[$prefix_sesiuni.'_modul_banner'] > $nr_reclame)
		{
		//echo "<br>1 - Nr sesiune prea mare = reinitializare";
		$_SESSION[$prefix_sesiuni.'_modul_banner'] = 1;
		}
	else
		{
		//echo "<br>incrementare sesiune";
		$_SESSION[$prefix_sesiuni.'_modul_banner'] = $_SESSION[$prefix_sesiuni.'_modul_banner']+1;		
		if ($_SESSION[$prefix_sesiuni.'_modul_banner'] > $nr_reclame)
			{
			//echo "<br>2 - Nr sesiune prea mare = reinitializare";
			$_SESSION[$prefix_sesiuni.'_modul_banner'] = 1;
			}
		}
	}
	
if ($total == 0) // se verifica daca exista bannere disponibile
	{	
	$bannere_cod[1] = mesaje_pt_banner(7);
	$bannere_id[1] = 0;
	}
if ($total != 0)
	{
	$nr_baner = 1;
	while ($rand = mysql_fetch_array($rezultat))
		{
		$bannere_id[$nr_baner] = $rand['id'];
		$bannere_cod[$nr_baner] = stripslashes($rand['cod']);
		$bannere_link[$nr_baner] = stripslashes($rand['url']);
		$bannere_nume[$nr_baner] = stripslashes($rand['nume']);		
		$nr_baner++;
		}
	}

// daca se trimit parametrii se actioneaza in functie de parametrul trimis
if (!isset($_GET['action'])) // daca nu este nici o actiune
	{
	// se afiseaza bannerul curent
	//echo "<br>prima data = ".$_SESSION[$prefix_sesiuni.'_modul_banner2'];
	echo $bannere_cod[$_SESSION[$prefix_sesiuni.'_modul_banner']];	
	banner_save($bannere_id[$_SESSION[$prefix_sesiuni.'_modul_banner']]);
	$_SESSION[$prefix_sesiuni.'_modul_banner2'] = 1; // pentru a nu se afisa de 2 ori in aceeasi pagina
	//echo "<br>dupa prima afisare = ".$_SESSION[$prefix_sesiuni.'_modul_banner2'];
	// se salveaza afisarea
	}
	
if (isset($_GET['action'])) // daca este setata o actiune
	{
	if (isset($_GET['m']))
		{
		if ($_GET['m'] == "banner") // in cazul cand se cere actiune dar nu este pentru acest modul se ignora
			{
			// se verifica daca are drepturi de acces aici
			$nivel_acces = drepturi_far(); // functia care returneaza dreptul de acces al userului	
			if ($nivel_acces <= 2) // daca nivelul de acces este intre 1-2 atunci e ok
				{
				$verificare = 0;
				}
			if ($nivel_acces >= 3) // daca nivelul de acces este intre 3-6 atunci nu se afiseaza continutul pagini
				{
				$verificare = 1;
				}	
			// daca nu are se afiseaza bannerul normal si se afiseaza si avertizarea de drepturi
			if ($verificare == 1)
				{
				echo mesaje_pt_banner(23);
				}
			// daca are acces se merge mai departe	
			if ($verificare == 0)
				{
				if (isset($_SESSION[$prefix_sesiuni.'_modul_banner2'])) // adaugata in caz ca se apeleaza alt modul cu variabila action...
					{
					if ($_SESSION[$prefix_sesiuni.'_modul_banner2'] == 2)// daca s-a afisat bannerul inseamna ca = 1 iar daca s-a incarcat inca odata acest script in aceeasi pagina inseamna ca = 2
						{		
						//echo "<br>a doua oara prima faza cu actiune".$_SESSION[$prefix_sesiuni.'_modul_banner2'];
						$actiune = $_GET['action'];
						$verificare = 0;
						// se verifica ce actiune este
						if ($actiune == "new") // daca se vrea introducere baner nou
							{
							$verificare = 2;
							if (isset($_POST['cod_banner']))
								{
								banner_new(2);
								}
							if (!isset($_POST['cod_banner']))
								{
								banner_new();
								}			
							}
						if ($actiune == "change") // daca se cere schimbare
							{
							$verificare = 2;
							if (!isset($_POST['id']))
								{
								if (!isset($_POST['id2']))
									{
									banner_change(); // prima data se afiseaza formularul de id
									}
								if (isset($_POST['id2']))
									{
									banner_change(2); // a treia data se salveaza datele noi
									}
								}
							if (isset($_POST['id']))
								{
								banner_change(1); // a doua oara se afiseaza formularul cu datele originale
								}				
							}
						
						if ($actiune == "del") // daca se cere stergere
							{
							$verificare = 2;
							if (!isset($_POST['id']))
								{
								banner_del(); // prima data se cere id
								}
							if (isset($_POST['id']))
								{
								banner_del(1); // a doua oara se sterge bannerul cu id cerut
								}
							}
					
						if ($actiune == "log") // daca se cere log
							{
							$verificare = 2;
							if (!isset($_POST['id']))
								{
								banner_log(); // prima data se cere id
								}
							if (isset($_POST['id']))
								{
								banner_log(1); // a doua oara se sterge bannerul cu id cerut
								}
							}
						if ($actiune == "all") // daca se cere afisarea tuturor bannerelor
							{
							$verificare = 2;
							if (isset($_GET['id']))
								{
								$id = $_GET['id'];
								banner_all($id);
								}
							if (!isset($_GET['id']))
								{
								banner_all();
								}
							}	
						if ($verificare = 0)
							{
							echo "fara";
							}
						$_SESSION[$prefix_sesiuni.'_modul_banner2'] ++;
						//echo "<br>a doua oara dupa faza cu actiune =".$_SESSION[$prefix_sesiuni.'_modul_banner2'];
						}	
				
					if ($_SESSION[$prefix_sesiuni.'_modul_banner2'] == 1)
						{
						//echo "<br>prima data cu actiune = ".$_SESSION[$prefix_sesiuni.'_modul_banner2'];
						echo $bannere_cod[$_SESSION[$prefix_sesiuni.'_modul_banner']];
						banner_save($bannere_id[$_SESSION[$prefix_sesiuni.'_modul_banner']]);
						$_SESSION[$prefix_sesiuni.'_modul_banner2'] =2;
						//echo "<br>prima data dupa afisare cu actiune =".$_SESSION[$prefix_sesiuni.'_modul_banner2'];
						}
					if ($_SESSION[$prefix_sesiuni.'_modul_banner2'] == 3)
						{
						//echo "<br>a treia data inainte de reset = ".$_SESSION[$prefix_sesiuni.'_modul_banner2'];
						$_SESSION[$prefix_sesiuni.'_modul_banner2'] =1;
						//echo "<br>a treia data dupa de reset = ".$_SESSION[$prefix_sesiuni.'_modul_banner2'];
						}
					}
				}
			}
		else
			{
			// se afiseaza bannerul curent
			//echo "<br>prima data = ".$_SESSION[$prefix_sesiuni.'_modul_banner2'];
			echo $bannere_cod[$_SESSION[$prefix_sesiuni.'_modul_banner']];	
			banner_save($bannere_id[$_SESSION[$prefix_sesiuni.'_modul_banner']]);
			$_SESSION[$prefix_sesiuni.'_modul_banner2'] = 1; // pentru a nu se afisa de 2 ori in aceeasi pagina
			//echo "<br>dupa prima afisare = ".$_SESSION[$prefix_sesiuni.'_modul_banner2'];
			// se salveaza afisarea
			}
		}
	}
?>
