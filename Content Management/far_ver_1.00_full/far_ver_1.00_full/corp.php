<?php
/* =====================================================================
*	Pagina corp.php (parte din modulul de teme)
*	Creat de alin4lex pentru proiectul FAR-PHP
*	Versiune: 0.01
*	Copyright: (C) 2004 - 2005 The FAR-PHP Group
*	E-mail: spookykid@4x.ro
*	Data inceperii paginii: 07-04-2005
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

body_far("meta"); // se afiseaza metatagul
body_far("css"); // se afiseaza style css
?>
<body>
<table width="800" height="262" border="0" align="center" cellpadding="0" cellspacing="0"">
 <tr>
  <td width="100%" valign="top">
   <table width="800" border="0">
    <tr>
      <td valign="top" bgcolor="#A7CCE5">
<?php 
body_far("top"); // afiseaza logo si bannere
?>
        <table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#F2F9FF">
         <tr>
           <td valign="top" bgcolor="#1877B4">
<?php
body_far("menu_1"); // returneaza meniu sus 1
?>
</td>
       </tr>
         <tr>
           <td height="15" bgcolor="#1877B4" class="bara_sus">
		   <div align="right">
		   <a href="admin.php?language=ro&m=ch_language">
		   <img src="themes/corp/images/romania.gif" alt="RO" width="25" height="15" border="0">
		   </a>
		   
		   <a href="admin.php?language=en&m=ch_language">
		   <img src="themes/corp/images/usa.gif" alt="EN" width="25" height="15" border="0">
		   </a>
		   </div>
		   </td>
       </tr>
     </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td valign="top">
<?php
body_far("menu_2"); // returneaza meniu mijloc 2
?>
</td>
          </tr>
        </table></td>
    </tr>
   </table>
   <table width="797" border="0" cellpadding="8" cellspacing="0" bordercolor="#F2F9FF" bgcolor="#F2F9FF">
     <tr>
       <td width="176" valign="top">
         <table width="114%" border="0" cellspacing="1" cellpadding="3" bgcolor="#A7CCE5">
           <tr>
             <td valign="top" bgcolor="#F2F9FF"><table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#A7CCE5">
               <tr>
                 <td valign="top">
<?php
body_far("menu_4"); // returneaza meniu dreapta 4
?>
                 </td>
               </tr>
               <tr>
                 <td>
	<?php
body_far("menu_5"); // returneaza meniu stanga 5
?>
                 </td>
               </tr>
               <tr>
                 <td></td>
               </tr>
               <tr>
                 <td>
<?php
body_far("login"); // includere partea de logare
?>
</td>
               </tr>
               <tr>
                 <td><br><div align="center" class="largetext">				 
<?php
body_far("online"); // includere partea de vizitatori online
?>
</div></td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
               </tr>
             </table></td>
           </tr>
         </table>
         <br />
       </td>
       <td width="589" valign="top" bordercolor="#A7CCE5" bgcolor="#F2F9FF" class="boxes">
         <table width="100%" height="56" border="0" bordercolor="#A7CCE5" bgcolor="#A7CCE5">
           <tr>
             <td height="16" background="themes/corp/images/mid_bar.gif">
<?php
body_far("status"); // includere partea de print si link
?>
</td>
           </tr>
           <tr>
             <td height="16" bordercolor="#2780B9" bgcolor="#A7CCE5">
<?php
body_far("content"); // se incarca pagina ceruta
?>
</td>
           </tr>
           <tr>
             <td height="16" bordercolor="#2780B9" bgcolor="#A7CCE5"><div align="right"><span class="style2">
			 <?php
// se preia limbajul pentru afisarea continutului specific limbajului
$limbaj_prelucrat = $_SESSION[$prefix_sesiuni.'_language_far'];
if ($limbaj_prelucrat == "ro")
	{
	$msg = "sus";
	}
if ($limbaj_prelucrat == "en")
	{
	$msg = "top";
	}
echo $msg;
?>
			 </span> <a href="javascript:scroll(0,0);"><img src="themes/corp/images/sus.gif" width="10" height="10" border="0" align="absbottom">  </a></div></td>
           </tr>
         </table>
         </td>
       <!-- Show only on non content pages -->
       </tr>
   </table>   
   <table width="100%" height="160" border="0" align="center" cellpadding="3" cellspacing="0" bgcolor="#F2F9FF">
     <tr>
       <td valign="top" bgcolor="#F2F9FF"><table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#F2F9FF">
           <tr>
             <td valign="top">
<?php
body_far("menu_3"); // returneaza meniu jos 3
?>
</td>
           </tr>
           <tr>
             <td><span class="style1">
<?php
body_far("down"); // partea de jos cu logo si vizitatori...
?>
</span></td>
           </tr>
           <tr>
             <td><span class="style1">
<?php
body_far("copyright"); // partea de jos cu copyrightul
?>
                </span></td>
           </tr>
       </table></td>
     </tr>
   </table></td>
 </tr>
</table>
<!-- Copyright FAR-PHP 2004-2005, www.far-php.ro, contact@far-php.ro --> 
</body>