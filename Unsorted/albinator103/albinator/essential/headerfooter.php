<?php

// your site's design header part
$head_header=<<<__HEADER_END

<!-- Header Start Here -->

<body bgcolor="#FFFFFF" link="#000000" vlink="#606420">
<basefont face=verdana size=2>

<!-- Header End Here -->

__HEADER_END;



// your site's design footer part
$head_footer=<<<__FOOTER_END

<!-- Footer Start Here -->

</body>
</html>

<!-- Footer End Here -->

__FOOTER_END;




// Anything you want to be put in the <head></head> tag's of the albinator generated pages
$headcontent=<<<__HEAD_END

<!-- Head Content Start Here -->

<!-- Head Content End Here -->

  <link rel="stylesheet" HREF="{$dirpath}essential/{$Config_LangLoad}_default.css" type="text/css">
  <script language="JavaScript" src="{$dirpath}essential/default.js"></script>

__HEAD_END;



// Following are Top Bar and Bottom Bar Settings for Panel when user is NOT logged in...
$topbar_first_out=<<<__TOPBAR_END

&nbsp;:: <a href="{$dirpath}register.php" class="ts">$strSignup</a>
 ~ <a href="{$dirpath}show.php" class="ts">$strView</a>
 ~ <a href="{$dirpath}login.php" class="ts">$strLogin</a> ::

__TOPBAR_END;


$bottom_bar_out=<<<__BOTTOM_BAR

	<table width="{$Config_table_size}%" border="0" cellspacing="0" cellpadding="2" align="center">
	<form method=post action="{$dirpath}showlist.php">
                <tr class="ts" bgcolor="#333333"> 
                  <td> 
                    <div align="right"><a name=view></a><font color="#CCCCCC">$strView $strAlbum$strPuralS&nbsp;</font>&nbsp;</div>
                  </td>
                  <td width="170"> <input type="hidden" name="dowhat" value="email">
                    <input type="text" name="email_id" class="fieldse" value="$strFriend$strPuralS $strEmail" onFocus="if(this.value == '$strFriend$strPuralS $strEmail') this.value = ''" onBlur="if(this.value == '') this.value = '$strFriend$strPuralS $strEmail'">
                  </td>
                  <td width="200" bgcolor="#333333"> 
                    <input type="submit" name="Submit" value=" $strGo " class="butfield">&nbsp;&nbsp;
                    <span class="wts">[<a href="{$dirpath}show.php" class=noundertsb>$strOtherWays</a>]</span></td>
                  <td bgcolor="#333333"> 
<div align="right"><a href="#"><img src="{$dirpath}{$Config_imgdir}/design/icon_top.gif" width="16" height="16" border="0" alt="top of page"></a>&nbsp;
            </div>
          </td>
        </tr>
     </form>
    </table>

__BOTTOM_BAR;




// Following are Top Bar and Bottom Bar Settings for Panel when user is LOGGED IN...

$topbar_first=<<<__TOPBAR_END

&nbsp;<a href="{$dirpath}user/album_view.php" class="bts">$strMenusMyAlbums</a>
 : <a href="{$dirpath}user/index.php" class="bts">$strMenusAddAlbum</a>
 : <a href="{$dirpath}user/upload.php" class="bts">$strMenusAddphotos</a>
 : <a href="{$dirpath}user/album_edit.php" class="bts">$strMenusChanges</a>
 : <a href="{$dirpath}user/ecards.php" class="bts">$strMenusEcards</a>
 : <a href="{$dirpath}logout.php" class="bts">$strMenusLogout</a>

__TOPBAR_END;


$topbar_second=<<<__TOPBAR_END

&nbsp;<a href="{$dirpath}user/userprofile.php?dowhat=show" class="ts">$strMenusMyprofile</a> 
&nbsp;&nbsp;<a href="{$dirpath}user/remind.php" class="ts">$strMenusReminders</a>
&nbsp;&nbsp;<a href="{$dirpath}user/manipulate.php" class="ts">$strMenusManipulate</a>
&nbsp;&nbsp;<a href="{$dirpath}user/album_view.php" class="ts">$strMenusTell</a> 
&nbsp;&nbsp;<a href="{$dirpath}user/settings.php" class="ts">$strMenusSettings</a> 
&nbsp;&nbsp;<a href="{$dirpath}user/feedback.php" class="ts">$strMenusFeedback</a>
&nbsp;&nbsp;<a href="{$dirpath}user/help.php" class="ts">$strMenusHelp</a>

__TOPBAR_END;


$bottom_bar=<<<__BOTTOM_BAR

	<table width="{$Config_table_size}%" border="0" cellspacing="0" cellpadding="2" align="center">
	<form method=post action="{$dirpath}showlist.php">
                <tr class="ts" bgcolor="#333333"> 
                  <td> 
                    <div align="right"><a name=view></a><font color="#CCCCCC">$strView $strAlbum$strPuralS&nbsp;</font>&nbsp;</div>
                  </td>
                  <td width="170"> <input type="hidden" name="dowhat" value="email"> 
                    <input type="text" name="email_id" class="fieldse" value="$strFriend$strPuralS $strEmail" onFocus="if(this.value == '$strFriend$strPuralS $strEmail') this.value = ''" onBlur="if(this.value == '') this.value = '$strFriend$strPuralS $strEmail'">
                  </td>
                  <td width="200" bgcolor="#333333"> 
                    <input type="submit" name="Submit" value=" $strGo " class="butfield">&nbsp;&nbsp;
                    <span class="wts">[<a href="{$dirpath}show.php" class=noundertsb>$strOtherWays</a>]</span></td>
                  <td bgcolor="#333333"> 
<div align="right"><a href="#"><img src="{$dirpath}{$Config_imgdir}/design/icon_top.gif" width="16" height="16" border="0" alt="top of page"></a>&nbsp;
            </div>
          </td>
        </tr>
     </form>
    </table>

__BOTTOM_BAR

?>