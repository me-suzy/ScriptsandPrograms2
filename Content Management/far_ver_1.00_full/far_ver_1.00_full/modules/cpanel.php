<?php
/* =====================================================================
*	Modulul control panel a proiectului FAR-PHP - cpanel.php
*	Creat de Birkoff pentru proiectul FAR-PHP
*	Versiune: 1.0
*	Copyright: (C) 2004 - 2005 The FAR-PHP Group
*	E-mail: contact@far-php.ro
*	Inceput la: 07-05-2005
*	Ultima modificare: 29-05-2005
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

// setarea fisierului cu mesaje
function mesaje_modul_cpanel($nr)
	{
	global $prefix_sesiuni;
	$mesaje_ro = array(
		1 => '<br>Eroare: Doar utilizatorii cu drepturi de nivel 1-4 au acces aici.',
		2 => 'Modulul administrare continut',
		3 => 'Adaugare articol',
		4 => 'Modificare articol',
		5 => 'Stergere articol',
		6 => 'Afisare articole RO',
		7 => 'Afisare articole EN',
		8 => 'Afisare toate articolele',
		9 => 'Upload fisiere php',
		10 => 'Upload imagini jpg, gif, etc.',
		11 => 'Modulul administrare ip',
		12 => 'Afisare lista cu ip-uri blocate',
		13 => 'Modificare date ip',
		14 => 'Adaugare ip',
		15 => 'Stergere ip',
		16 => 'Modulul administrare meniuri',
		17 => 'Afisare meniuri existente',
		18 => 'Creare meniu nou',
		19 => 'Modificare meniu',
		20 => 'Stergere meniu',
		21 => 'Modulul schimbare limbaj',
		22 => 'Schimbare limbaj curent in RO',
		23 => 'Schimbare limbaj curent in EN',
		24 => 'Afisare limbaje',
		25 => 'Modulul robots',
		26 => 'Detalii vizite',
		27 => 'Modulul administrare login',
		28 => 'Detalii user curent',
		29 => 'Afisare useri inscrisi',
		30 => 'Inregistrare user nou',
		31 => 'Schimbare parola user',
		32 => 'Schimbare drepturi user',
		33 => 'Stergere user',
		34 => 'Deconectare',
		35 => 'Modulul administrare bannere',
		36 => 'Afisare lista bannere',
		37 => 'Modificare banner',
		38 => 'Adaugare banner',
		39 => 'Stergere banner',
		40 => 'Detalii banner',
		41 => 'Modulul vizitatori online',
		42 => 'Afisare vizitatori online',
		43 => 'Detalii vizitatori',
		44 => 'Modulul schimbare template',
		45 => 'Schimbare tema curenta',
		46 => 'Modulul administrare newsletter',
		47 => 'Adaugare e-mail',
		48 => 'Stergere e-mail',
		49 => 'Trimitere newsletter',
		50 => '');
	$mesaje_en = array(
		1 => '<br>Error: Only users level 1-4 is permited.',
		2 => 'Content modules',
		3 => 'Add new content',
		4 => 'Modify content',
		5 => 'Erase content',
		6 => 'Show content RO languages',
		7 => 'Show content EN languages',
		8 => 'Show all content',
		9 => 'Upload php files',
		10 => 'Upload images jpg, gif, etc.',
		11 => 'Block ip modules',
		12 => 'Show all ip blocked',
		13 => 'Modify ip data',
		14 => 'Add new ip',
		15 => 'Erase ip',
		16 => 'Menu modules',
		17 => 'Show all menu',
		18 => 'Add new menu',
		19 => 'Modify menu',
		20 => 'Erase meniu',
		21 => 'Language modules',
		22 => 'Change language RO',
		23 => 'Change language EN',
		24 => 'Show languages',
		25 => 'Robots modules',
		26 => 'Visit details',
		27 => 'Login modules',
		28 => 'Current user details',
		29 => 'Show all users',
		30 => 'Add new user',
		31 => 'Change user password',
		32 => 'Change user rights',
		33 => 'Erase user',
		34 => 'Disconnecting',
		35 => 'Banner modules',
		36 => 'Show all banners',
		37 => 'Modify banner',
		38 => 'Add new banner',
		39 => 'Erase banner',
		40 => 'Banner details',
		41 => 'Online modules',
		42 => 'Show online visitors',
		43 => 'Visitors detail',
		44 => 'Template modules',
		45 => 'Change template',
		46 => 'Newsletter modules',
		47 => 'Signing up e-mail',
		48 => 'Unsubscribe e-mail',
		49 => 'Send newsletter',
		50 => '');
		
	if ($_SESSION[$prefix_sesiuni.'_language_far'] == "ro")
		{
		return $mesaje_ro[$nr];
		}
	if ($_SESSION[$prefix_sesiuni.'_language_far'] == "en")
		{
		return $mesaje_en[$nr];
		}
	if (($_SESSION[$prefix_sesiuni.'_language_far'] !== "ro") OR ($_SESSION[$prefix_sesiuni.'_language_far'] !== "en"))
		{
		return $mesaje_en[$nr];
		}
	}
if (drepturi_far() <= 4) // acces nivele 1-4
	{
	// limbaj manual help
	$lg = $_SESSION[$prefix_sesiuni.'_language_far'];
	// style
	$style_cp = '<style type="text/css">
		<!--
		.cpanel_titlu 
			{
			color: #FFFFFF;
			font-weight: bold;
			}
		.cpanel_link 
			{
			color: #000000;	
			}
		.cpanel_help 
			{
			color: #FF0000;
			font-weight: bold;
			background-color:#FFFF00;
			border-color:#0000FF;
			cursor:help;
			text-decoration:none;	
			}
		a.cpanel_help:link
			{
			color: #FF0000;
			font-weight: bold;
			background-color:#FFFF00;
			border-color:#0000FF;
			cursor:help;
			text-decoration:none;
			}
		a.cpanel_help:visited,active 
			{
			color: #FF0000;
			font-weight: bold;
			background-color:#FFFF00;
			border-color:#0000FF;
			cursor:help;
			text-decoration:none;
			}
		a.cpanel_help:hover
			{
			color: #FF0000;
			font-weight: bold;	
			font-size: larger;
			background-color:#FFFF00;
			border-color:#0000FF;
			cursor:help;
			text-decoration:none;
			}
		.cpanel_tabel_1 
			{	
			background-color:#000066;
			}
		.cpanel_tabel_2
			{
			background-color: #000000;	
			}
		.cpanel_tabel_3
			{
			background-color: #FFFFFF;	
			}	
		-->
		</style>';
	
	// module 
	$modul_content = '<table width="100%"  border="0" cellpadding="1" cellspacing="1" class="cpanel_tabel_1">
      <tr>
        <td><table width="100%"  border="0" cellpadding="0" cellspacing="1" class="cpanel_tabel_3">
          <tr>
            <td class="cpanel_tabel_2"><div align="center" class="cpanel_titlu">'.mesaje_modul_cpanel(2).' - <a href="index.php?c=help_'.$lg.'#modul_continut" class="cpanel_help">?</a> - </div></td>
          </tr>
          <tr>
            <td><div align="center"><a href="admin.php?m=content" class="cpanel_link">'.mesaje_modul_cpanel(3).'</a> - <a href="index.php?c=help_'.$lg.'#modul_continut_nou" class="cpanel_help">?</a> - </div></td>
          </tr>
          <tr>
            <td><div align="center"><a href="admin.php?m=content_2" class="cpanel_link">'.mesaje_modul_cpanel(4).'</a> - <a href="index.php?c=help_'.$lg.'#modul_content_2" class="cpanel_help">?</a> - </div></td>
          </tr>
          <tr>
            <td><div align="center"><a href="admin.php?m=content_2" class="cpanel_link">'.mesaje_modul_cpanel(5).'</a> - <a href="index.php?c=help_'.$lg.'#modul_stergere_continut" class="cpanel_help">?</a> - </div></td>
          </tr>
          <tr>
            <td><div align="center"><a href="index.php?p=content" class="cpanel_link">'.mesaje_modul_cpanel(6).'</a> - <a href="index.php?c=help_'.$lg.'#afisare_articole_limba" class="cpanel_help">?</a> - </div></td>
          </tr>
          <tr>
            <td><div align="center"><a href="index.php?p=content" class="cpanel_link">'.mesaje_modul_cpanel(7).'</a> - <a href="index.php?c=help_'.$lg.'#afisare_articole_limba" class="cpanel_help">?</a> - </div></td>
          </tr>
          <tr>
            <td><div align="center"><a href="admin.php?m=content_2&action=list" class="cpanel_link">'.mesaje_modul_cpanel(8).'</a> - <a href="index.php?c=help_'.$lg.'#afisare_toate_articolele" class="cpanel_help">?</a> - </div></td>
          </tr>
          <tr>
            <td><div align="center"><a href="admin.php?m=content" class="cpanel_link">'.mesaje_modul_cpanel(9).'</a> - <a href="index.php?c=help_'.$lg.'#continut_varianta_5" class="cpanel_help">?</a> - </div></td>
          </tr>
          <tr>
            <td><div align="center"><a href="admin.php?m=content" class="cpanel_link">'.mesaje_modul_cpanel(10).'</a> - <a href="index.php?c=help_'.$lg.'#continut_varianta_2" class="cpanel_help">?</a> - </div></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
    </table>';
	$modul_blockip = '<table width="100%"  border="0" cellpadding="1" cellspacing="1" class="cpanel_tabel_1">
      <tr>
        <td><table width="100%"  border="0" cellpadding="1" cellspacing="1" class="cpanel_tabel_3">
          <tr>
            <td class="cpanel_tabel_2"><div align="center" class="cpanel_titlu">'.mesaje_modul_cpanel(11).' - <a href="index.php?c=help_'.$lg.'#blockip" class="cpanel_help">?</a> - </div></td>
            </tr>
          <tr>
            <td><div align="center"><a href="admin.php?m=blockip&action=show" class="cpanel_link">'.mesaje_modul_cpanel(12).'</a> - <a href="index.php?c=help_'.$lg.'#blockip_lista" class="cpanel_help">?</a> - </div></td>
            </tr>
          <tr>
            <td><div align="center"><a href="admin.php?m=blockip&action=modify" class="cpanel_link">'.mesaje_modul_cpanel(13).'</a> - <a href="index.php?c=help_'.$lg.'#blockip_modificare" class="cpanel_help">?</a> - </div></td>
            </tr>
          <tr>
            <td><div align="center"><a href="admin.php?m=blockip" class="cpanel_link">'.mesaje_modul_cpanel(14).'</a> - <a href="index.php?c=help_'.$lg.'#blockip_adaugare" class="cpanel_help">?</a> - </div></td>
            </tr>
          <tr>
            <td><div align="center"><a href="admin.php?m=blockip&action=del" class="cpanel_link">'.mesaje_modul_cpanel(15).'</a> - <a href="index.php?c=help_'.$lg.'#blockip_stergere" class="cpanel_help">?</a> - </div></td>
            </tr>
          <tr>
            <td><div align="center">&nbsp;</div></td>
            </tr>
        </table></td>
        </tr>
    </table>';
	$modul_menu = '<table width="100%"  border="0" cellpadding="1" cellspacing="1" class="cpanel_tabel_1">
      <tr>
        <td><table width="100%"  border="0" cellpadding="1" cellspacing="1" class="cpanel_tabel_3">
          <tr>
            <td class="cpanel_tabel_2"><div align="center" class="cpanel_titlu">'.mesaje_modul_cpanel(16).' - <a href="index.php?c=help_'.$lg.'#modul_meniuri" class="cpanel_help">?</a> - </div></td>
            </tr>
          <tr>
            <td><div align="center"><a href="admin.php?m=menu" class="cpanel_link">'.mesaje_modul_cpanel(17).'</a> - <a href="index.php?c=help_'.$lg.'#afisare_lista_meniuri" class="cpanel_help">?</a> - </div></td>
            </tr>
          <tr>
            <td><div align="center"><a href="admin.php?m=menu&action=new" class="cpanel_link">'.mesaje_modul_cpanel(18).'</a> - <a href="index.php?c=help_'.$lg.'#creare_meniu" class="cpanel_help">?</a> - </div></td>
            </tr>
          <tr>
            <td><div align="center"><a href="admin.php?m=menu&id=0&action=m" class="cpanel_link">'.mesaje_modul_cpanel(19).'</a> - <a href="index.php?c=help_'.$lg.'#schimbare_meniu" class="cpanel_help">?</a> - </div></td>
            </tr>
          <tr>
            <td><div align="center"><a href="http://birkoff/far-php_ver1/admin.php?m=menu&id=0&action=s" class="cpanel_link">'.mesaje_modul_cpanel(20).'</a> - <a href="index.php?c=help_'.$lg.'#stergere_meniu" class="cpanel_help">?</a> - </div></td>
            </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
        </table></td>
        </tr>
    </table>';
	$modul_language = '<table width="100%"  border="0" cellpadding="1" cellspacing="1" class="cpanel_tabel_1">
      <tr>
        <td><table width="100%"  border="0" cellpadding="1" cellspacing="1" class="cpanel_tabel_3">
          <tr>
            <td class="cpanel_tabel_2"><div align="center" class="cpanel_titlu">'.mesaje_modul_cpanel(21).' - <a href="index.php?c=help_'.$lg.'#modul_language" class="cpanel_help">?</a> - </div></td>
          </tr>
          <tr>
            <td><div align="center"><a href="admin.php?language=ro&m=ch_language" class="cpanel_link">'.mesaje_modul_cpanel(22).'</a> - <a href="index.php?c=help_'.$lg.'#schimbare_limbaj" class="cpanel_help">?</a> - </div></td>
          </tr>
          <tr>
            <td><div align="center"><a href="http://birkoff/far-php_ver1/admin.php?language=en&m=ch_language" class="cpanel_link">'.mesaje_modul_cpanel(23).'</a> - <a href="index.php?c=help_'.$lg.'#schimbare_limbaj" class="cpanel_help">?</a> - </div></td>
          </tr>
          <tr>
            <td><div align="center"><a href="admin.php?m=language" class="cpanel_link">'.mesaje_modul_cpanel(24).'</a> - <a href="index.php?c=help_'.$lg.'#modul_language" class="cpanel_help">?</a> - </div></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
    </table>';
	$modul_robots = '<table width="100%"  border="0" cellpadding="1" cellspacing="1" class="cpanel_tabel_1">
      <tr>
        <td><table width="100%"  border="0" cellpadding="1" cellspacing="1" class="cpanel_tabel_3">
          <tr>
            <td class="cpanel_tabel_2"><div align="center" class="cpanel_titlu">'.mesaje_modul_cpanel(25).' - <a href="index.php?c=help_'.$lg.'#robots" class="cpanel_help">?</a> - </div></td>
          </tr>
          <tr>

            <td><div align="center"><a href="admin.php?m=robots" class="cpanel_link">'.mesaje_modul_cpanel(26).'</a> - <a href="index.php?c=help_'.$lg.'#robots" class="cpanel_help">?</a> - </div></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
    </table>';
	$modul_login = '<table width="100%"  border="0" cellpadding="1" cellspacing="1" class="cpanel_tabel_1">
      <tr>
        <td><table width="100%"  border="0" cellpadding="0" cellspacing="1" class="cpanel_tabel_3">
            <tr>
              <td class="cpanel_tabel_2"><div align="center" class="cpanel_titlu">'.mesaje_modul_cpanel(27).' - <a href="index.php?c=help_'.$lg.'#modul_logare" class="cpanel_help">?</a> - </div></td>
            </tr>
            <tr>
              <td><div align="center"><a href="admin.php?m=login" class="cpanel_link">'.mesaje_modul_cpanel(28).'</a> - <a href="index.php?c=help_'.$lg.'#login" class="cpanel_help">?</a> - </div></td>
            </tr>
            <tr>
              <td><div align="center"><a href="index.php?c=viev_user" class="cpanel_link">'.mesaje_modul_cpanel(29).'</a> - <a href="index.php?c=help_'.$lg.'#viev_useri" class="cpanel_help">?</a> - </div></td>
            </tr>
            <tr>
              <td><div align="center"><a href="admin.php?m=login_new&action=new_user" class="cpanel_link">'.mesaje_modul_cpanel(30).'</a> - <a href="index.php?c=help_'.$lg.'#creare_user" class="cpanel_help">?</a> - </div></td>
            </tr>
            <tr>
              <td><div align="center"><a href="admin.php?m=login_new&action=new_pass" class="cpanel_link">'.mesaje_modul_cpanel(31).'</a> - <a href="index.php?c=help_'.$lg.'#schimbare_parola" class="cpanel_help">?</a> - </div></td>
            </tr>
            <tr>
              <td><div align="center"><a href="admin.php?m=login_new&action=new_right" class="cpanel_link">'.mesaje_modul_cpanel(32).'</a> - <a href="index.php?c=help_'.$lg.'#schimbare_drepturi" class="cpanel_help">?</a> - </div></td>
            </tr>
            <tr>
              <td><div align="center"><a href="admin.php?m=login_new&action=new_del" class="cpanel_link">'.mesaje_modul_cpanel(33).'</a> - <a href="index.php?c=help_'.$lg.'#stergere_user" class="cpanel_help">?</a> - </div></td>
            </tr>
            <tr>
              <td><div align="center"><a href="admin.php?m=login_new&action=dec" class="cpanel_link">'.mesaje_modul_cpanel(34).'</a> - <a href="index.php?c=help_'.$lg.'#deconectare_user" class="cpanel_help">?</a> - </div></td>
            </tr>
            <tr>
              <td><div align="center">&nbsp;</div></td>
            </tr>
        </table></td>
      </tr>
    </table>';
	$modul_banner = '<table width="100%"  border="0" cellpadding="1" cellspacing="1" class="cpanel_tabel_1">
      <tr>
        <td><table width="100%"  border="0" cellpadding="1" cellspacing="1" class="cpanel_tabel_3">
            <tr>
              <td class="cpanel_tabel_2"><div align="center" class="cpanel_titlu">'.mesaje_modul_cpanel(35).' - <a href="index.php?c=help_'.$lg.'#banner" class="cpanel_help">?</a> - </div></td>
            </tr>
            <tr>
              <td><div align="center"><a href="admin.php?m=banner&action=all" class="cpanel_link">'.mesaje_modul_cpanel(36).'</a> - <a href="index.php?c=help_'.$lg.'#toate_banner" class="cpanel_help">?</a> - </div></td>
            </tr>
            <tr>
              <td><div align="center"><a href="admin.php?m=banner&action=change" class="cpanel_link">'.mesaje_modul_cpanel(37).'</a> - <a href="index.php?c=help_'.$lg.'#modificare_banner" class="cpanel_help">?</a> - </div></td>
            </tr>
            <tr>
              <td><div align="center"><a href="admin.php?m=banner&action=new" class="cpanel_link">'.mesaje_modul_cpanel(38).'</a> - <a href="index.php?c=help_'.$lg.'#adaugare_banner" class="cpanel_help">?</a> - </div></td>
            </tr>
            <tr>
              <td><div align="center"><a href="admin.php?m=banner&action=del" class="cpanel_link">'.mesaje_modul_cpanel(39).'</a> - <a href="index.php?c=help_'.$lg.'#sterg_banner" class="cpanel_help">?</a> - </div></td>
            </tr>
            <tr>
              <td><div align="center"><a href="admin.php?m=banner&action=log" class="cpanel_link">'.mesaje_modul_cpanel(40).'</a> - <a href="index.php?c=help_'.$lg.'#log_banner" class="cpanel_help">?</a> - </div></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
        </table></td>
      </tr>
    </table>';
	$modul_online = '<table width="100%"  border="0" cellpadding="1" cellspacing="1" class="cpanel_tabel_1">
      <tr>
        <td><table width="100%" height="100%"  border="0" cellpadding="1" cellspacing="1" class="cpanel_tabel_3">
            <tr>
              <td class="cpanel_tabel_2"><div align="center" class="cpanel_titlu">'.mesaje_modul_cpanel(41).' - <a href="index.php?c=help_'.$lg.'#online" class="cpanel_help">?</a> - </div></td>
            </tr>
            <tr>
              <td><div align="center"><a href="admin.php?m=online" class="cpanel_link">'.mesaje_modul_cpanel(42).'</a> - <a href="index.php?c=help_'.$lg.'#online" class="cpanel_help">?</a> - </div></td>
            </tr>
            <tr>
              <td><div align="center"><a href="admin.php?m=online&action=see" class="cpanel_link">'.mesaje_modul_cpanel(43).'</a> - <a href="index.php?c=help_'.$lg.'#online" class="cpanel_help">?</a> - </div></td>
            </tr>
            <tr>
              <td><div align="center">&nbsp;</div></td>
            </tr>
        </table></td>
      </tr>
    </table>';
	$modul_template = '<table width="100%"  border="0" cellpadding="1" cellspacing="1" class="cpanel_tabel_1">
      <tr>
        <td><table width="100%" height="100%"  border="0" cellpadding="1" cellspacing="1" class="cpanel_tabel_3">
            <tr>
              <td class="cpanel_tabel_2"><div align="center" class="cpanel_titlu">'.mesaje_modul_cpanel(44).' - <a href="index.php?c=help_'.$lg.'#mod_ch_template" class="cpanel_help">?</a> - </div></td>
            </tr>
            <tr>
              <td><div align="center"><a href="admin.php?m=ch_template" class="cpanel_link">'.mesaje_modul_cpanel(45).'</a> - <a href="index.php?c=help_'.$lg.'#mod_ch_template" class="cpanel_help">?</a> - </div></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
        </table></td>
      </tr>
    </table>';
	$modul_newsletter = '<table width="100%"  border="0" cellpadding="1" cellspacing="1" class="cpanel_tabel_1">
      <tr>
        <td><table width="100%"  border="0" cellpadding="1" cellspacing="1" class="cpanel_tabel_3">
            <tr>
              <td class="cpanel_tabel_2"><div align="center" class="cpanel_titlu">'.mesaje_modul_cpanel(46).' - <a href="index.php?c=help_'.$lg.'#newsletter" class="cpanel_help">?</a> - </div></td>
            </tr>
            <tr>
              <td><div align="center">&nbsp;<a href="#" class="cpanel_link">'.mesaje_modul_cpanel(47).'</a> - <a href="#" class="cpanel_help">?</a> - </div></td>
            </tr>
            <tr>
              <td><div align="center"><a href="#" class="cpanel_link">'.mesaje_modul_cpanel(48).'</a> - <a href="#" class="cpanel_help">?</a> - </div></td>
            </tr>
            <tr>
              <td><div align="center"><a href="#" class="cpanel_link">'.mesaje_modul_cpanel(49).'</a> - <a href="#" class="cpanel_help">?</a> - </div></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
        </table></td>
      </tr>
    </table>';
	$module = array(
		'content' => $modul_content,
		'blockip' => $modul_blockip,
		'menu' => $modul_menu,
		'language' => $modul_language,
		'robots' => $modul_robots,
		'login' => $modul_login,
		'banner' => $modul_banner,
		'online' => $modul_online,
		'ch_template' => $modul_template,
		'newsletter' => $modul_newsletter);
		
	// afisare module
	echo $style_cp;
	echo '<table width="100%"  border="0" cellpadding="1" cellspacing="1">';
	foreach ($module as $cheie => $valoare)
		{
		if (file_exists('modules/'.$cheie.'.php'))
			{
			echo '<tr><td valign="top">'.$valoare.'</td></tr>';
			}
		}
	echo '</table>';	
	}
else
	{
	echo mesaje_modul_cpanel(1);
	}
?>