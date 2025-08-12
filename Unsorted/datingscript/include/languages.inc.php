<?php
############################################################
# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #
############################################################
# AzDGDatingLite          Version 2.1.1                    #
# Writed by               AzDG (support@azdg.com)          #
# Created 03/01/03        Last Modified 04/05/03           #
# Scripts Home:           http://www.azdg.com              #
############################################################
# File name               languages.inc.php                #
# File purpose            File for Language select         #
# File created by         AzDG <support@azdg.com>          #
############################################################

if ((isset($l) && ($l == '') && (C_SHOW_LANG == '2')) || (C_SHOW_LANG == '3')) {
   $handle=opendir(C_PATH.'/images/flags');
   $fnm = 0;
   while (false!==($file = readdir($handle))) { 
      if (ereg(".gif$", $file)) {
	  $langfile[$fnm] = substr($file,0,strpos($file,'.'));
      $fnm++;
      } 
   }
			closedir($handle); 
if ($fnm == 0) echo $w[261];
elseif ($fnm > 1) {
?>
<Tr bgcolor="<?=COLOR2?>" class=mes><Td colspan=5><?=$w[262]?> :
<? 
for ($i = 0; $i < $fnm; $i++) {
echo '<a href='.C_URL.'/index.php?l='.$langfile[$i].'><img src="'.C_URL.'/images/flags/'.$langfile[$i].'.gif" width="20" height="13" border="0"></a>&nbsp;';
}
echo '</Td></Tr>';
}
}
?>