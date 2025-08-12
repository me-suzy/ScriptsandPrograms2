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
# Name:                 adm_mailtemplates.php
#
# Description:
#
# Version:                7.2
#
######################################################################
include('../includes/db_connect.php');
include('../functions.php');
check_admin();

$template_type = form_get('template_type');
$template_value = form_get('template_value');
$template_id = form_get('template_id');

if($_POST['act'] == 'save') {
	$sql_query = "  UPDATE dir_template 
                    SET 
                        template_type = '$template_type', 
                        template_value='$template_value' 
                    WHERE 
                        template_id = $template_id";
//echo $sql_query;    
    mysql_query($sql_query, $link);
    echo mysql_error();
}

# retrieve the template

$sql_result = mysql_query("SELECT * FROM dir_template", $link);

?>
<?
include('../Templates/maintemplate.header.inc.php');
include('../includes/admin_header.php'); 
?>
<table width="500" border="0">
    <tr> 
      <td>
	<table width="100%"  border="0" >
    <tr class="tdtoprow" align="left"> 
      <td> 
        Description
      </td>
      <td > 
        Type
      </td>
      <td > 
        Edit
      </td>
     </tr>
        <?php
          while($m_template = mysql_fetch_object($sql_result)) {
        ?>
      
    <tr align=left class="tdodd" >
      <td> 
        <?=$m_template->template_title?>
      </td>
      <td> 
        <?=$m_template->template_type?>
      </td>
      <td>
        <a href="<?=$CONST_LINK_ROOT?>/admin/templates_edit.php?template_id=<?=$m_template->template_id?>">
            [Edit]
        </a>
      </td>
    </tr>
    <?php } ?>
					
	<tr> 
      <td colspan="3" align="center" class="tdfoot">&nbsp; </td>
    </tr>
     
  </table>
	  
	  </td>
    </tr>
  </table>
<?php include('../Templates/maintemplate.footer.inc.php');?>
