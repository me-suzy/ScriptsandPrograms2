<?php
/****************************************************************/
/*                       phphg Guestbook                        */
/*                         sign.php file                        */
/*                      (c)copyright 2003                       */
/*                       By hinton design                       */
/*                 http://www.hintondesign.org                  */
/*                  support@hintondesign.org                    */
/*                                                              */
/* This program is free software. You can redistrabute it and/or*/
/* modify it under the terms of the GNU General Public Licence  */
/* as published by the Free Software Foundation; either version */
/* 2 of the license.                                            */
/*                                                              */
/****************************************************************/
$phphg_real_path = "./";
include($phphg_real_path . 'common.php');

$ip = $HTTP_SERVER_VARS['REMOTE_ADDR'];

$sql = "SELECT * FROM ".$prefix."_banned WHERE ip='$ip'";
$result = $db->query($sql);

$num = $db->num($result);

if($num > 0) {
	include($phphg_real_path . 'includes/page_header.php');
	$display = "You have been banned. Please contact the admin.";
	$template->getFile(array(
			   'error' => 'error.tpl')
	);
	$template->add_vars(array(
                           'L_ERROR' => $lang['error'],
			   'DISPLAY' => $display)
	);
	$template->parse("error");
	include($phphg_real_path . 'includes/page_footer.php');
	exit();
} else {
	$sql = "SELECT * FROM ".$prefix."_smilies";
	$result = $db->query($sql);
	
	$smilie_dir = "images/smilies";
	
	$smilie = "";
	$smilie_img = 0;
	
	while($row = $db->fetch($result)) {
	      $smilie_code = $row['code'];
	      $smilie_url = $row['url'];
	      $smilie_name = $row['name'];
	      $smilie .= "<a href=\"#\" onClick=\"DoSmilie('" . $smilie_code . "');\"><img src=\"$smilie_dir/$smilie_url\" border=\"0\" title=\"$smilie_name\" alt=\"$smilie_name\"></a>&nbsp;";
		  
	      $smilie_img++;
	      if($smilie_img == 4) {
		 $smilie .= "<br>";
		 $smilie_img = 0;
	      }
        }
	
	include($phphg_real_path . 'includes/page_header.php');
	$template->getFile(array(
			   'sign' => 'sign.tpl')
	);
	$template->add_vars(array(
                           'L_SMILE' => $lang['smilies'],
                           'L_INFO' => $lang['info'],
                           'L_EMAIL' => $lang['email'],
                           'L_NAME' => $lang['names'],
                           'L_WEBSITE' => $lang['website'],
                           'L_MESSAGE' => $lang['message'],
                           'L_LOCATION' => $lang['loc'],

			   'SMILIES' => $smilie)
        );
        $template->parse("sign");
        include($phphg_real_path . 'includes/page_footer.php');
}
?>
