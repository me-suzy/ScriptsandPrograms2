<?php
  if ($banner = escs_banner_exists('dynamic', '125X600'))
  {
  ?>
  <tr>
  <td width="100%">
  <table border="1" width="100%" cellspacing="0" cellpadding="0">
  <tr>
   <td align="center"> <br><?php echo escs_display_banner('static',
$banner); ?><br><br></td>
  </tr>
  </table>
  </td>
  </tr>
  <?php
  }
?>         