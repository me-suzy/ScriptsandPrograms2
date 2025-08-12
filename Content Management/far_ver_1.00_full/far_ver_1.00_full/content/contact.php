<?php
/* =====================================================================
* 	Pagina contact.php
*	Creat de Gyzzard pentru proiectul FAR-PHP
*	Versiune: 1.00
*	Copyright: (C) 2004 - 2005 The FAR-PHP Group
*	E-mail: gyzzard@yahoo.com
*	Data inceperii paginii: 28-04-2005
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

// adresa unde se afla acest fisier
// daca se afla in directorul content scrieti 'index.php?c=contact'
// daca se afla in directorul modules scrieti 'admin.php?m=contact'
$adresa_pagina_contact = 'index.php?c=contact';
$adresa_de_trimis = $email_admin; // in caz ca nu vreti sa se trimita pe adresa de admin adaugati aici adresa noua

if (!function_exists('mailvers')) 
	{
	//aici e functia de verificare email... 0-invalida si 1-valida
	function mailvers($emm) 
		{    
		if (eregi("^[a-z0-9\._-]+@+[a-z0-9\._-]+\.+[a-z]{2,3}$",$emm) == TRUE) 
			{
			return 1;			
			}
		else
			{
			return 0;
			}			
		}
	}
	
// se preiau drepturile userului
$rtdrept = drepturi_far();
if ($rtdrept<5) 
	{
	$rtnume=$_SESSION[$prefix_sesiuni.'_user_far'];
	$rtemail=$_SESSION[$prefix_sesiuni.'_email_far'];
	}

// se seteaza fisierul cu mesaje
$limbaj_prelucrat = $_SESSION[$prefix_sesiuni.'_language_far'];
if ($limbaj_prelucrat != "ro")
	{
	if ($limbaj_prelucrat != "en")
		{
		$limbaj_prelucrat = "en";
		}
	}
if ($limbaj_prelucrat == "ro")
	{
	$mesaje_fisier = array(
		3 => '<br>Ai introdus o adresa de email invalida.',
		4 => '<br>Mesajul dumneavoastra a fost trimis.',
		5 => '<br>Nu ati completat campurile:',
		6 => ' e-mail',
		7 => ' nume',
		8 => ' mesaj',
		9 => ' subiect',
		10 => 'E-mail: ',
		11 => 'Nume: ',
		12 => 'Mesaj: ',
		13 => 'Subiect: ',
		14 => 'Trimite');
	}
if ($limbaj_prelucrat == "en")
	{
	$mesaje_fisier = array(
		3 => '<br>You entered an invalid email. ',
		4 => '<br>Your message was sent.',
		5 => '<br>You didn\'t complete the fields:',
		6 => ' e-mail',
		7 => ' name',
		8 => ' message',
		9 => ' subject',
		10 => 'E-mail: ',
		11 => 'Name: ',
		12 => 'Message: ',
		13 => 'Subject: ',
		14 => 'Send');
	}

// aici incepe afisarea formularului sau a mesajului final
echo '<table width="100%">
	<tr>
	<td align="center">';
	
// setare variabile
$rtj[1]=0;
$rtj[2]=0;
$rtj[3]=0;
$rtj[4]=0;
$rtj[5]=0;
$ytr = 0;

// daca formularul e trimis se verifica valorile trimise
if (isset($_REQUEST['contact_trim'])) 
	{
	if (isset($_REQUEST['contact_nume'],$_REQUEST['contact_email'],$_REQUEST['contact_titlu'],$_REQUEST['contact_mesaj']) and 
		($_REQUEST['contact_nume']!='')and($_REQUEST['contact_email']!='')and($_REQUEST['contact_titlu']!='')and
		($_REQUEST['contact_mesaj']!='')and(mailvers($_REQUEST['contact_email'])==1)) 
		{
    	$num=$_REQUEST['contact_nume'];
        $maikl=$_REQUEST['contact_email'];
		// daca totul e ok se trimite mail 		
		$subiect_de_trimis = $_REQUEST['contact_titlu'];
		$continut_de_trimis = $mesaje_fisier[11].$num."<br>".$mesaje_fisier[10].$maikl."<br><br>".$_REQUEST['contact_mesaj'];
		$headere_de_trimis = "From: $num <$maikl>\r\n".
			"Reply-To: $adresa_de_trimis\r\n".
			"MIME-Version: 1.0\r\nContent-type: text/html; charset=iso-8859-2\r\n";
       	@mail($adresa_de_trimis, $subiect_de_trimis, $continut_de_trimis, $headere_de_trimis);
		$rtj[1]=1;
		echo '<META HTTP-EQUIV = "Refresh" Content = "5; URL ='.$adresa_url.'">'.$mesaje_fisier[4];		
		} 
	else 
		{
		if ((!isset($_REQUEST['contact_nume']))or($_REQUEST['contact_nume']=='')) 
			{
            if ($ytr==0) 
				{
				echo $mesaje_fisier[5];
                $ytr=1;
	        	}
			echo $mesaje_fisier[7];
			$rtj[1]=2;
			$rtj[2]=1;
			} 
		else
			{
	        $rtj[2]=2;
    	    }
		if ((!isset($_REQUEST['contact_email']))or($_REQUEST['contact_email']=='')) 
			{
            if ($ytr==0) 
				{
				echo $mesaje_fisier[5];
                $ytr=1;
	        	}
			if ($rtj[1]==2) 
				{
				echo ",";
				}
			echo $mesaje_fisier[6];
			$rtj[1]=2;
			$rtj[3]=1;
			} 
		else 
			{
        	$rtj[3]=2;
        	}
		if ((!isset($_REQUEST['contact_titlu']))or($_REQUEST['contact_titlu']=='')) 
			{
            if ($ytr==0) 
				{
				echo $mesaje_fisier[5];
                $ytr=1;
	        	}
			if ($rtj[1]==2) 
				{
				echo ",";
				}
			echo $mesaje_fisier[9];
			$rtj[1]=2;
			$rtj[4]=1;
			} 
		else 
			{
        	$rtj[4]=2;
        	}
		if ((!isset($_REQUEST['contact_mesaj']))or($_REQUEST['contact_mesaj']=='')) 
			{
            if ($ytr==0) 
				{
				echo $mesaje_fisier[5];
                $ytr=1;
	        	}
			if ($rtj[1]==2) 
				{
				echo ",";
				}
			echo $mesaje_fisier[8];
			$rtj[1]=2;
			$rtj[5]=1;
			} 
		else 
			{
        	$rtj[5]=2;
        	}
        if ((mailvers($_REQUEST['contact_email'])==0)and($_REQUEST['contact_email']!='')) 
			{
			if ($rtj[1]==2) 
				{
				echo ",";
				}
			echo $mesaje_fisier[3];
			$rtj[1]=2;
			$rtj[3]=0;
			}
		}
	}
if ($rtj[1]!=1) 
	{
	echo '<form action="'.$adresa_pagina_contact.'" method="post">
		<input type="hidden" name="contact_trim" value="1">
		<table style="font-face: Verdana;font-size: 12px">
		<tr>
		<td width="40">'.$mesaje_fisier[11].'</td>
		<td>
		<input type="text" size="26" name="contact_nume" style="font-face: Verdana;font-size: 12px"';
	if ($rtdrept<5) 
		{
        echo ' value ="'.$rtnume.'" readonly';
        }
	else 
		{
        if ($rtj[2]==2) 
			{
			echo ' value="'.$_REQUEST['contact_nume'].'"';
			}
        }
	echo '></td>
		</tr>
		<tr>
		<td width="40">'.$mesaje_fisier[10].'</td>
		<td>
		<input type="text" size="26" name="contact_email" style="font-face: Verdana;font-size: 12px"';
	if ($rtdrept<5) 
		{
        echo ' value ="'.$rtemail.'" readonly';
        } 
	else 
		{
		if ($rtj[3]==2) 
			{
			echo ' value="'.$_REQUEST['contact_email'].'"';
			}
        }
	echo '></td>
		</tr>
		<tr>
		<td width="40">'.$mesaje_fisier[13].'</td>
		<td>
		<input type="text" size="26" name="contact_titlu" style="font-face: Verdana;font-size: 12px"';
	if ($rtj[2]==2) 
		{
		echo ' value="'.$_REQUEST['contact_titlu'].'"';
		}
	echo '></td>
		</tr>
		<tr>
		<td width="40" valign="top">'.$mesaje_fisier[12].'</td>
		<td>
		<textarea cols="34" rows="6" name="contact_mesaj" style="font-face: Verdana;font-size: 12px">';
	if ($rtj[2]==2) 
		{
		echo $_REQUEST['contact_mesaj'];
		}
	echo '</textarea>
		</td>
		</tr>
		<tr><td colspan="2" align="center"><input type="submit" value="'.$mesaje_fisier[14].'"></td></tr>
		</table>
		</form>';
	}
echo '</td>
	</tr>
	</table>';

?>