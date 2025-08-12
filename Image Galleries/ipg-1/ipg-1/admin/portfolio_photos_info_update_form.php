<?php
/******************************************************************************
* IPG: Instant Photo Gallery                                                  *
* =========================================================================== *
* Software Version:             IPG 1.0                                       *
* Copyright 2005 by:            Verosky Media - Edward Verosky                *
* Support, News, Updates at:    http://www.instantphotogallery.com            *
*******************************************************************************
* This program is free software; you may redistribute it and/or modify it     *
* under the terms of the GNU General Public License as published by the Free  * 
* Software Foundation; either version 2 of the License, or (at your option)   *
* any later version.                                                          *                                                                             *
* This program is distributed WITHOUT ANY WARRANTIES; without even any        *
* implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.    *
*                                                                             *
* See www.gnu.org  for details of the GPL license.                            *
******************************************************************************/
?>

<form name="form1" method="post" action="<?php print $PHP_SELF ?>" enctype="multipart/form-data">
  <table width="70%" class="admin_form_box">
    <tr> 
      <td colspan="2"> 
        <p class="admin_form_header">&nbsp;UPDATE PHOTO INFO&nbsp;</p>
      </td>
    </tr>
    <tr valign="middle"> 
      <td align="left" class="admin_form_label">Image: </td>
      <td align="left"> 
        <?php /* using same var (image_to_delete) for this display */ print $_POST['image_to_handle'] ?>
      </td>
    <tr valign="middle" align="left"> 
      <td class="admin_form_label">Title: </td>
      <td> 
        <input type="text" maxlength="64" name="title" value="<?php print htmlentities($updateRow['title']) ?>">
      </td>
    </tr>
    <tr valign="middle" align="left"> 
      <td class="admin_form_label">Caption: </td>
      <td> 
        <input type="text" maxlength="64" name="imageCaption" value="<?php print  htmlentities($updateRow['caption']) ?>">
      </td>
    </tr>
    <tr valign="middle" align="left"> 
      <td  class="admin_form_label">Credit: </td>
      <td nowrap> 
        <input type="text" maxlength="64" name="photographer" value="<?php print  htmlentities($updateRow['photographer']) ?>">
      </td>
    </tr>
    <tr valign="middle" align="left"> 
      <td class="admin_form_label">Copyright: </td>
      <td> 
        <input type="text" maxlength="64" name="copyright" value="<?php print  htmlentities($updateRow['copyright']) ?>">
      </td>
    </tr>
    <tr valign="middle" align="left"> 
      <td class="admin_form_label">Place In Category:</td>
      <td class="admin_form_label"> 
        <?php print make_selectbox('port_cat', $selPortfolioCats, $updateRow['cat_id'],'',0); ?>
      </td>
    </tr>
    <tr> 
      <td align="left" valign="top">&nbsp;</td>
      <td align="left" valign="middle"> 
        <input type="hidden" name="cat_id" value="<?php print $theCatId; ?>">
        <input type="hidden" name="image_id" value="<?php print $_POST['image_id']?>">
        <input type="submit" name="photo_info_update" value="Update Info">
      </td>
    </tr>
  </table>
  <br>
</form>
