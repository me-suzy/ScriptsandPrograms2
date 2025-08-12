<? if($author[about]){ 
?>
<table width="100%" cellspacing="0" cellpadding="0" style='border: #CCCCCC dotted; border-width: 1px 1px 1px 1px; padding: 15px 15px 15px 15px;' class="text">
<tr>
  <td class="text"><? echo "$author[about]";  ?></td>
  </tr>
</table><br />
<?
}
?>

<table width="100%" cellspacing="0" cellpadding="0" style='border: #CCCCCC dotted; border-width: 1px 1px 1px 1px; padding: 15px 15px 15px 15px;' class="text">
      <tr>
        <td class="title"><? echo "$lang_search"; ?></td>
      </tr>
      <tr>
        <td class="title"><form method="GET" action="<?php echo "index.php?search=$search" ?>">
<table width="22%"  border="0" cellspacing="0">
          <tr> 
            <td width="45%" class='text'><input name='search' type='text' size="15" value="<? echo "$_GET[search]"; ?>"></td>
            <td width="22%"><input name="submit" type="submit" value="search"></td>
          </tr>
        </table>
</form></td>
      </tr>
	        <tr>
        <td class="text">&nbsp;</td>
      </tr>
      <tr>
        <td class="title"><? echo "$lang_archive"; ?></td>
      </tr>
      <tr>
        <td><? echo "<a href='archive.php'>$lang_archive</a>"; ?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="title"><? echo "$lang_categories"; ?></td>
      </tr>
      <tr>
        <td><? displayCategories('<br />'); ?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="title"><? echo "$lang_authors"; ?></td>
      </tr>
      <tr>
        <td><? displayAuthors('<br />'); ?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
	   <tr>
        <td class="title"><? echo "$lang_pages"; ?></td>
      </tr>
      <tr>
        <td><? displayPages('<br />'); ?></td>
      </tr>
      <tr>
        <td class="title">&nbsp;</td>
      </tr>
      <tr>
        <td class="title"><? echo "$lang_rss_feed"; ?></td>
      </tr>
      <tr>
        <td><a href="xml.php" target="_blank">xml</a></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="title"><? echo "$lang_admin"; ?></td>
      </tr>
      <tr>
        <td><a href="login.php"><? echo "$lang_login"; ?></a><? if ($settings[use_join]){ echo "<br /><a href='join.php'>$lang_register</a>"; } ?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><span class="title"><? echo "$lang_powered"; ?></span></td>
      </tr>
      <tr>
        <td><a href="http://zomplog.zomp.nl" target="_blank">zomplog</a></td>
      </tr>
    </table>
