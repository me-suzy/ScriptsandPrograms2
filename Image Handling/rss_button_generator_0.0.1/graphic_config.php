<?
##################################################################################################
#                          APPLICATION DETAILS AND LICENSING INFORMATION                         #
##################################################################################################
# SOFTWARE NAME: RSS Button Generator
# VERSION: 0.0.1
# FILENAME: graphic_config.php
# AUTHOR: Nugen Software Inc.
# DETAILS: Contains form to configure Button Settings, Sticky Form Code, and Value Checking.
# URL: http://www.nugensoftware.com
##################################################################################################
#                THIS FILE IS OPEN SOURCE - PLEASE CREDIT THE ORIGINAL AUTHORS                   #
##################################################################################################



if ($_POST['action']=="make_graphic") {
	if (!empty($_POST['rss_ls_text']) && !empty($_POST['rss_ls_fontcolor']) && !empty($_POST['rss_ls_background']) && !empty($_POST['rss_rs_text']) && !empty($_POST['rss_rs_fontcolor']) && !empty($_POST['rss_ls_background']) && is_numeric($_POST['rss_vbar'])) {
			
			if ((strlen($_POST['rss_ls_fontcolor'])==6) && (strlen($_POST['rss_rs_fontcolor'])==6) && (strlen($_POST['rss_ls_background'])==6) && (strlen($_POST['rss_rs_background'])==6)) {
				$T_URL='rss_button.php?MAKE_GRAPHIC=true&LS_FONT_TEXT=' . $_POST['rss_ls_text'] . '&LS_FONTCOLOR=' . $_POST['rss_ls_fontcolor'] . '&LS_BACKGROUND=' . $_POST['rss_ls_background'] . '&RS_FONT_TEXT=' . $_POST['rss_rs_text'] . '&RS_FONTCOLOR=' . $_POST['rss_rs_fontcolor'] . '&RS_BACKGROUND=' . $_POST['rss_rs_background'] . '&VBAR=' . $_POST['rss_vbar'];
				$RSS_IMAGE=true;
				$T_IMAGE_BLOCK='<p><center><img src="' . $T_URL . '" alt="rss generated image"><br>Left Click to Save Image</center></p>';
			} else {
				//hex error
				// {INSERT_ERROR_CODE_HERE}
				echo 'hex error';
			}

			
	} else {
		//error missing field
	   // {INSERT_ERROR_CODE_HERE}
		echo 'missing';
	}
} else {
	//no action
}
?>
             <? 
			 if (!empty($T_IMAGE_BLOCK)) {
			 	echo $T_IMAGE_BLOCK . '<br>';
			 } 
			 ?> <form action="<? echo $PHP_SELF; ?>" method="post" name="generate_image" id="generate_image">
                <table width="100%"  border="0" cellpadding="2" cellspacing="0">
                  <tr>
                    <td height="28"><strong>OpenSource RSS Graphic Generator</strong></td>
                  </tr>
                  <tr>
                    <td valign="top" bgcolor="#FFFFFF"><table width="100%"  border="0" cellspacing="0" cellpadding="2">
                        <tr>
                          <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="4">
                              <tr>
                                <td width="42%">Left Side Text: </td>
                                <td width="58%"><input name="rss_ls_text" type="text" id="rss_ls_text" value="<? if (empty($_POST['rss_ls_text'])) { echo 'RSS'; } else { echo $_POST['rss_ls_text']; }?>"></td>
                            </tr>
                              <tr>
                                <td>Left Side Font Color: </td>
                                <td><input name="rss_ls_fontcolor" type="text" id="rss_ls_fontcolor" value="<? if (empty($_POST['rss_ls_fontcolor'])) { echo 'FFFFFF'; } else { echo $_POST['rss_ls_fontcolor']; }?>"></td>
                            </tr>
                              <tr>
                                <td>Left Side Background Color:</td>
                                <td><input name="rss_ls_background" type="text" id="rss_ls_background" value="<? if (empty($_POST['rss_ls_background'])) { echo 'FF6600'; } else { echo $_POST['rss_ls_background']; }?>"></td>
                            </tr>
                              <tr>
                                <td>Right Side Text:</td>
                                <td><input name="rss_rs_text" type="text" id="rss_rs_text" value="<? if (empty($_POST['rss_rs_text'])) { echo 'FEED'; } else { echo $_POST['rss_rs_text']; }?>"></td>
                            </tr>
                              <tr>
                                <td>Right Side Font Color:</td>
                                <td><input name="rss_rs_fontcolor" type="text" id="rss_rs_fontcolor" value="<? if (empty($_POST['rss_rs_fontcolor'])) { echo 'FFFFFF'; } else { echo $_POST['rss_rs_fontcolor']; }?>"></td>
                            </tr>
                              <tr>
                                <td>Right Side Background Color:</td>
                                <td><input name="rss_rs_background" type="text" id="rss_rs_background" value="<? if (empty($_POST['rss_rs_background'])) { echo '898E79'; } else { echo $_POST['rss_rs_background']; }?>"></td>
                            </tr>
                              <tr>
                                <td>Vertical Bar Position: </td>
                                <td><input name="rss_vbar" type="text" id="rss_vbar" value="<? if (empty($_POST['rss_vbar'])) { echo '30'; } else { echo $_POST['rss_vbar']; }?>"></td>
                            </tr>
                              <tr>
                                <td><input name="action" type="hidden" id="action" value="make_graphic"></td>
                                <td><input type="submit" name="Submit" value="Generate Image"></td>
                            </tr>
                          </table></td>
                        </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
                </table>
             </form>