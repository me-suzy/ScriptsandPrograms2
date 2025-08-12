<?php
/*
  $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
  Enterprise Shopping Cart Software
  http://www.enterprisecart.com

  Copyright (c) 2004 Enterprise Shopping Cart Software.  Portions Copyright (c) 2001-2004 osCommerce: http://www.oscommerce.com

  Released under the GNU General Public License
*/

  if ($messageStack->size > 0) {
    echo $messageStack->output();
  }
?>

<!--

<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr bgcolor="#ffffff" height="50">
            <td height="50"><?php echo '<a href="http://www.enterprisecart.com">' . escs_image(DIR_WS_IMAGES . 'ecommerce_shopping_cart_software.gif', 'Get the latest Enterprise Shopping Cart Software version here', '365', '68') . '</a>'; ?></td>
          </tr>
  <tr class="headerBar">
    <td class="headerBarContent">&nbsp;&nbsp;<?php
//Admin begin
//  echo '<a href="' . escs_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '" class="headerLink">' . HEADER_TITLE_TOP . '</a>';
  if (escs_session_is_registered('login_id')) {
    echo '<a href="' . escs_href_link(FILENAME_ADMIN_ACCOUNT, '', 'SSL') . '" class="headerLink">' . HEADER_TITLE_ACCOUNT . '</a> | <a href="' . escs_href_link(FILENAME_LOGOFF, '', 'NONSSL') . '" class="headerLink">' . HEADER_TITLE_LOGOFF . '</a>';
  } else {
    echo '<a href="' . escs_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '" class="headerLink">' . HEADER_TITLE_TOP . '</a>';
  }
//Admin end
    ?></td>
    <td class="headerBarContent" align="right"><?php echo '&nbsp;&nbsp;<a href="http://www.sourceforge.net/projects/ecommercescs" target="_blank" class="headerLink">Sourceforge Project Page</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="http://www.enterprisecart.com" target="_blank" class="headerLink">Home Page</a>&nbsp;&nbsp;|&nbsp; <a href="' . escs_catalog_href_link() . '" class="headerLink">' . HEADER_TITLE_ONLINE_CATALOG . '</a> &nbsp;|&nbsp; <a href="' . escs_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '" class="headerLink">' . HEADER_TITLE_ADMINISTRATION . '</a>'; ?>&nbsp;&nbsp;</td>
  </tr>
</table>

-->

<br>&nbsp;
<br>&nbsp;
<br>

<script type="text/javascript" src="includes/browser.js">

/***********************************************
* Jim's DHTML Menu v5.0- © Jim Salyer (jsalyer@REMOVETHISmchsi.com)
* Visit Dynamic Drive: http://www.dynamicdrive.com for script and instructions
* This notice must stay intact for use
***********************************************/

</script>
<script type="text/javascript" src="config.js"></script>

