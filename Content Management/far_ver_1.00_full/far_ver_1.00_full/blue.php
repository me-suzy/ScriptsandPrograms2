<?php
/* =====================================================================
*	Pagina blue.php (parte din modulul de teme)
*	Creat de Catalin pentru proiectul FAR-PHP
*	Versiune: 1.0
*	Copyright: (C) 2004 - 2005 The FAR-PHP Group
*	E-mail: barosanu_catalin@yahoo.com
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
<body>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top" background="themes/blue/images/a.jpg" bgcolor="#8EB1D7">
<?php 
body_far("top"); // afiseaza logo si bannere
?>
</td>
  </tr>
  <tr>
    <td valign="top" background="themes/blue/images/b.jpg" bgcolor="#8BAAE0"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top">
<?php
body_far("menu_1"); // returneaza meniu sus 1
?>
</td>
        </tr>
      <tr>
        <td valign="top">
<?php
body_far("menu_2"); // returneaza meniu mijloc 2
?>
</td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td bgcolor="#E3ECFB"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="15%" valign="top" background="themes/blue/images/c.jpg" bgcolor="#BBCFF0"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
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
            <td><br><div align="center" class="largetext">
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
        <td width="70%" valign="top" bgcolor="#E3ECFB"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top" bgcolor="#8BAAE0">
<?php
body_far("status"); // includere partea de print si link
?>
</td>
            </tr>
          <tr>
            <td valign="top">
<?php
body_far("content"); // se incarca pagina ceruta
?>
</td>
            </tr>
        </table></td>
        <td width="15%" valign="top" background="themes/blue/images/c.jpg" bgcolor="#BBCFF0"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
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
  <tr>
    <td valign="top" bgcolor="#8BAAE0"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top">
<?php
body_far("menu_3"); // returneaza meniu jos 3
?>
</td>
        </tr>
      <tr>
        <td valign="top">
<?php
body_far("down"); // partea de jos cu logo si vizitatori...
?>
</td>
        </tr>
      <tr>
        <td>
<?php
body_far("copyright"); // partea de jos cu copyrightul
?>
</td>
        </tr>
    </table></td>
  </tr>
</table>
<!-- Copyright FAR-PHP 2004-2005, www.far-php.ro, contact@far-php.ro --> 
</body>