<?php
/*****************************************************
* © copyright 1999 - 2003 Interactive Arts Ltd.
*
* All materials and software are copyrighted by Interactive Arts Ltd.
* under British, US and International copyright law. All rights reserved.
* No part of this code may be reproduced, sold, distributed
* or otherwise used in whole or in part without prior written permission.
*
*****************************************************/
######################################################################
#
# Name:              adm_mailtemplates_edit
#
# Description:
#
# Version:              7.2
#
######################################################################
include('../includes/db_connect.php');
include('../functions.php');
check_admin();

$template_id = form_get('template_id');
$sql_result = mysql_query("SELECT * FROM dir_template WHERE template_id='$template_id'", $link);
$m_template = mysql_fetch_object($sql_result);

include('../Templates/maintemplate.header.inc.php');
include('../includes/admin_header.php'); 
?>

<table width="100%"  border="0" cellspacing="3" cellpadding="3" align="center" >
    <tr> 
      <td><table width="100%"  border="0" >
        <form method="post" action="<?php echo $CONST_LINK_ROOT?>/admin/templates.php" enctype="multipart/form-data">
          <input type="hidden" name="act" value="save">
          <input type="hidden" name="template_id" value="<?=$template_id?>">
        <tr> 
          <td align="right" class="tdfoot"> 
            <input name="submit" type="submit" class="button" value="Save Template"> 
            <input name="button" type="button" class="button" onclick="window.location = '<?=$CONST_LINK_ROOT?>/admin/templates.php'" value="Templates List"> 
          </td>
        </tr>

          <tr> 
            <td align="center"> 
			
	   <table width="100%"  border="0" >
                <tr> 
                  <td colspan="2" class="tdhead">&nbsp;</td>
                </tr>
                <tr class="tdodd"> 
                  <td width="30%""> 
                    Description
                  </td>
                  <td height="25"> <?= $m_template->template_title?></td>
                </tr>
                <tr class="tdodd"> 
                  <td> 
                    Allowed Variables
                  </td>
                  <td height="25"> <?= $m_template->template_variables?></td>
                </tr>
                <tr class="tdodd"> 
                  <td> 
                    Type
                  </td>
                  <td height="25"> 
                  <select name="template_type" class="input">
                    <option value="html" <? if ($m_template->template_type == "html"){?>selected<?}?>>Html
                    <option value="text" <? if ($m_template->template_type == "text"){?>selected<?}?>>Text
                  </select>   
                  </td>
                </tr>
                <tr class="tdeven"> 
                  <td> 
                    Template
                  </td>
                  <td> <textarea style="width=450 px;" name="template_value" cols="80" rows="15" wrap="soft" class="inputl"><?=$m_template->template_value?></textarea></td>
                </tr>
              </table></td>
          </tr>
        </form>
      </table></td>
    </tr>
  </table>
  
	   
<?php include('../Templates/maintemplate.footer.inc.php');?>