<?php

  session_start();

  $l = "admin";

  include"top.php";

  access("10");

?>

  <table>

  <tr class='tr1'><td colspan='2'>Admin Menu</td></tr>

  <tr class='tr2'><td>Users:</td><td>[ <a href='admin_user_levels.php'>Edit Levels</a> ] [ <a href='admin_user_remove.php'>Remove</a> ]</td></tr>

  <tr class='tr2'><td>File:</td><td>[ <a href='admin_files.php?a=1'>Add</a> ] [ <a href='admin_files.php'>Edit / Delete</a> ]</td></tr>

  <tr class='tr2'><td>File Categories:</td><td>[ <a href='admin_file_categories.php?a=1'>Add</a> ] [ <a href='admin_file_categories.php'>Edit / Delete</a> ]</td></tr>

  <tr class='tr2'><td>News:</td><td>[ <a href='news_add.php'>Add</a> ] [ <a href='news_view.php?e=1'>Edit</a> ] [ <a href='news_view.php?d=1'>Delete</a> ]</td>
</tr>

  <tr class='tr2'><td>Journal:</td><td>[ <a href='journal_add.php'>Add</a> ] [ <a href='journal_view.php?e=1'>Edit</a> ] [ <a href='journal_view.php?d=1'>Delete</a> ]</td>
</tr>

  <tr class='tr2'><td>BB Code:</td><td>[ <a href='admin_bb.php?a=1'>Add</a> ] [ <a href='admin_bb.php'>Edit / Delete</a> ]</td>
</tr>

  <tr class='tr2'><td>Themes:</td><td>[ <a href='admin_themes.php?a=1'>Add</a> ] [ <a href='admin_themes.php?e=1'>Edit</a> ] [ <a href='admin_themes.php?d=1'>Delete</a> ]</td>
</tr>

  <tr class='tr2'><td>Stats:</td><td>[ <a href='admin_stats.php?a=1'>Add</a> ] [ <a href='admin_stats.php?e=1'>Edit</a> ] [ <a href='admin_stats.php?d=1'>Delete</a> ]</td>
</tr>

  <tr class='tr2'><td>Setup:</td><td>[ <a href='levels.php'>Levels</a> ] [ <a href='admin_config.php'>Config</a> ]</td></tr>

  </table>

<?php

  include"bottom.php";

?>
