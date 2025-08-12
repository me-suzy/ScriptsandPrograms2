<?
$user = loadUser($_SESSION['login'],$link,$table_users);
  ?>

<table width="253"  align="right" style='border: #CCCCCC dotted; border-width: 1px 1px 1px 1px; padding: 5px 5px 5px 5px;' class="text">
  <tr>
    <td width="18%" rowspan="3" valign="top" class="title"><img src="icons/entry.jpg" width="33" height="40"></td>
    <td width="6%" class="title">&nbsp;</td>
    <td width="76%" class="title"><? echo "$lang_entries"; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><a href="newentry.php"><? echo "$lang_newentry"; ?></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><a href="entry.php"><? echo "$lang_edit_delete"; ?></a> </td>
  </tr>
  <? if($user[admin]){ ?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="18%" rowspan="3" valign="top" class="title"><img src="icons/page.jpg" width="33" height="41"></td>
    <td width="6%" class="title">&nbsp;</td>
    <td width="76%" class="title"><? echo "$lang_pages"; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><a href="newpage.php"><? echo "$lang_newpage"; ?></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><a href="page.php"><? echo "$lang_edit_delete_page"; ?></a> </td>
  </tr>
  <? }
	  elseif(!$settings[pages]){
	  ?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="18%" rowspan="3" valign="top" class="title"><img src="icons/page.jpg" width="33" height="41"></td>
    <td width="6%" class="title">&nbsp;</td>
    <td width="76%" class="title"><? echo "$lang_pages"; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><a href="newpage.php"><? echo "$lang_newpage"; ?></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><a href="page.php"><? echo "$lang_edit_delete_page"; ?></a> </td>
  </tr>
  <? } ?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><img src="icons/upload.jpg" width="33" height="40"></td>
    <td>&nbsp;</td>
    <td><b><? echo "$lang_upload_images"; ?></b><br><a href="customupload.php" onclick='OpenLarge(this.href); return false'><? echo "$lang_custom_upload_tool"; ?></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td rowspan="2" valign="top" class="title"><img src="icons/profile.jpg" width="42" height="45"></td>
    <td class="title">&nbsp;</td>
    <td class="title"><? echo "$lang_profile"; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td valign="top"><a href="profile.php"><? echo "$lang_editprofile"; ?></a> </td>
  </tr>
    <?
  if($user[admin]){
  ?>
  <tr>
    <td class="title">&nbsp;</td>
    <td class="title">&nbsp;</td>
    <td class="title">&nbsp;</td>
  </tr>
  <tr>
    <td rowspan="3" valign="top" class="title"><img src="icons/admin.jpg" width="33" height="40"></td>
    <td class="title">&nbsp;</td>
    <td class="title"><? echo "$lang_controlpanel"; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><a href="settings.php"><? echo "$lang_settings"; ?></a> </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><a href='settings_moblog.php'><? echo "$lang_moblog_settings"; ?></a> </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td rowspan="3" valign="top" class="title"><img src="icons/content.jpg" width="40" height="45"></td>
    <td class="title">&nbsp;</td>
    <td class="title"><? echo "$lang_content_management"; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><a href="users.php"><? echo "$lang_manage_users"; ?></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><a href="category.php"><? echo "$lang_manage_categories"; ?></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><?
	  if($settings[comments]){
	  ?>
        <a href="comments.php"><? echo "$lang_manage_comments"; ?></a>
        <?
	  }
	  ?>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  <?
  }
  ?>
</table>
