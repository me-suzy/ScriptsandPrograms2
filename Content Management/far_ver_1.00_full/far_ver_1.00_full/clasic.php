<?php
/* =====================================================================
*	Pagina clasic.php (parte din modulul de teme)
*	Creat de Stalker pentru proiectul FAR-PHP
*	Versiune: 0.01
*	Copyright: (C) 2004 - 2005 The FAR-PHP Group
*	E-mail: numaitu2002@yahoo.com
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

body_far("meta"); // se afiseaza metatagul
body_far("css"); // se afiseaza style css
?>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<table width="800" height="262" border="0" align="center" cellpadding="0" cellspacing="0"">
 <tr>
  <td width="100%" valign="top">
   <table width="800" border="0">
    <tr>
      <td valign="top">
<?php 
body_far("top"); // afiseaza logo si bannere
?>
        <table width="100%"  border="0" cellpadding="0" cellspacing="0" background="themes/clasic/images/header_bg_tile.png">
         <tr>
           <td valign="top">
<?php
body_far("menu_1"); // returneaza meniu sus 1
?>
</td>
       </tr>
         <tr>
           <td valign="top">&nbsp;</td>
       </tr>
         <tr>
           <td valign="top">
<?php
body_far("menu_2"); // returneaza meniu mijloc 2
?>
</td>
         </tr>
     </table></td></tr>
   </table>
   <table width="800" border="0" cellpadding="8" cellspacing="0" bgcolor="#EFEFEF">
     <tr>
       <td width="172" valign="top">
         <table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#78580D">
           <tr>
             <td valign="top" bgcolor="#DEDDD3"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
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
body_far("login"); // includere partea de logare
?>
                 </td>
               </tr>
               <tr>
                 <td>
<?php
body_far("language"); // includere partea de limbaj
?>
</td>
               </tr>
               <tr>
                 <td>
				 <br><div align="center" class="largetext">
<?php
body_far("online"); // includere partea de vizitatori online
?>
</div>
				 </td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
               </tr>
             </table></td>
           </tr>
         </table>
         <br />
       </td>
       <td width="385" valign="top" class="boxes">
         <table width="100%"  border="0" cellspacing="0" cellpadding="0">
           <tr>
             <td bgcolor="#DEDDD3">
               <?php
body_far("status"); // includere partea de print si link
?></td>
           </tr>
           <tr>
             <td>
<?php
body_far("content"); // se incarca pagina ceruta
?>
             </td>
           </tr>
           <tr>
             <td>&nbsp;</td>
           </tr>
         </table></td>
       <!-- Show only on non content pages -->
       <td width="176" valign="top">
         <table width="100%" bgcolor="#78580D" border="0" cellspacing="1" cellpadding="3">
           <tr>
             <td valign="top" bgcolor="#DEDDD3"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
               <tr>
                 <td>
<?php
body_far("menu_5"); // returneaza meniu stanga 5
?>
                 </td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
               </tr>
               <tr>
                 <td>&nbsp;</td>
               </tr>
             </table></td>
           </tr>
       </table></td>
     </tr>
   </table></td>
 </tr>
</table>

<div align="center"><font color="#999999" size="1" face="Verdana"><a href="#top">top</a></font>
</div>

<table align="center" border="0" cellpadding="3" cellspacing="0" width="100%">
  <tr>
    <td bgcolor="#000000"> </td>
  </tr>
  <tr>
    <td valign="top" bgcolor="#DEDDD3"><table width="100%"  border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td valign="top">
<?php
body_far("menu_3"); // returneaza meniu jos 3
?>
</td>
        </tr>
      <tr>
        <td>
<?php
body_far("down"); // partea de jos cu logo si vizitatori...
?>
</td>
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
</table>
<!-- Copyright FAR-PHP 2004-2005, www.far-php.ro, contact@far-php.ro --> 
</body>