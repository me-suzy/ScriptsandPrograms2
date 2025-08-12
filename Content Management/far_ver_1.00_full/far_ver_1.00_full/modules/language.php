<?php
/* =====================================================================
*	Pagina language.php (parte din modulul de limbaj)
*	Creat de Birkoff pentru proiectul FAR-PHP
*	Versiune: 0.01
*	Copyright: (C) 2004 - 2005 The FAR-PHP Group
*	E-mail: contact@far-php.ro
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

// se preia limbajul pentru afisarea continutului specific limbajului
$limbaj_prelucrat = $_SESSION[$prefix_sesiuni.'_language_far'];
// echo $limbaj_prelucrat;

$limbaje_existente = array(
	'ro' => 'Limbaj:,Romania',
	'en' => 'Language:,English');
$nr = 0;
foreach ($limbaje_existente as $cheie => $valoare)
	{
	$despartire = explode(",",$valoare);	
	$structura[$nr] = '<option value="'.$cheie.'">'.$despartire[1].'</option>';
	if ($limbaj_prelucrat == $cheie)
		{
		$mesaje_aici = $despartire[0];
		}
	if ($cheie == $limbaj_prelucrat)
		{
		$prima = $structura[$nr];
		}
	$nr++;
	}
/*
if ($limbaj_prelucrat == "ro")
	{
	$limbaj_default = "Romanian";
	$mesaje_aici = array(
		1 => 'Limbaj:');
	$structura = '<option value="ro">Romanian</option>
	  <option value="en">English</option>';
	}
if ($limbaj_prelucrat == "en")
	{
	$limbaj_default = "English";
	$mesaje_aici = array(
		1 => 'Language:');
	$structura = '<option value="en">English</option>
	<option value="ro">Romanian</option>';
	}
*/
?>
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="top">
	
	<form action="admin.php" method="get" name="form_limbaj" id="form_limbaj">
  <div align="center"><?php echo $mesaje_aici; ?><br> 
    <select name="language" onchange='document.form_limbaj.submit();'>
<?php
echo $prima;
for ($ww = 0;$ww<=$nr;$ww++)
	{
	if ($prima != $structura[$ww])
		{
		echo $structura[$ww]; 
		}
	}
?>
    </select>    
	<input name="m" type="hidden" id="m" value="ch_language">	  
  </div>
</form>
	</td>
  </tr>
</table>
