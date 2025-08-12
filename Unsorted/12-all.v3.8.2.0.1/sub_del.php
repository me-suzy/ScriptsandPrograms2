<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_361; ?>: <?PHP print $emaildel; ?></strong></font></p>
<p><font face="Arial, Helvetica, sans-serif" size="2"> 
  <?PHP 
  if ($val == yes){
mysql_query ("DELETE FROM ListMembers
                                WHERE id = '$id'
								");
								?>
  <font size="2" face="Arial, Helvetica, sans-serif" color="#990000"> <?PHP print $lang_362; ?></font></font><font face="Arial, Helvetica, sans-serif" size="2">
  <?PHP } else { ?>
  <b><?PHP print $lang_363; ?></b></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_364; ?></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><b><i><?PHP print $lang_365; ?></i></b></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_366; ?></font></p>
<font size="2" face="Arial, Helvetica, sans-serif"><b><a href="main.php?page=sub_del&nl=<?PHP print $nl; ?>&id=<?PHP print $id; ?>&val=yes"><?PHP print $lang_169; ?> </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:window.history.go(-1);"><?PHP print $lang_170; ?></a></b></font> 
<?PHP } ?>
