<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_161; ?></strong></font></p>
<p><font face="Arial, Helvetica, sans-serif" size="2"> 
<?PHP 
  if ($val == yes){
	mysql_query ("DELETE FROM Lists
                                WHERE id = '$nl'
								");
	mysql_query ("DELETE FROM Messages
                                WHERE nl = '$nl'
								");
	mysql_query ("DELETE FROM ListMembers
                                WHERE nl = '$nl'
								");
	mysql_query ("DELETE FROM MessagesU
                                WHERE nl = '$nl'
								");
	mysql_query ("DELETE FROM Templates
                                WHERE nl = '$nl'
								");
	mysql_query ("DELETE FROM Links
                                WHERE nl = '$nl'
								");
	mysql_query ("DELETE FROM 12all_LinksD
                                WHERE nl = '$nl'
								");
	mysql_query ("DELETE FROM 12all_Bounce
                                WHERE nl = '$nl'
								");
?>
  <font size="2" face="Arial, Helvetica, sans-serif" color="#990000"> <?PHP print $lang_162; ?> </font><font size="2" face="Arial, Helvetica, sans-serif" color="#990000"><?PHP print $lang_163; ?></font> </font></p>
<p><font face="Arial, Helvetica, sans-serif" size="2"><b><font size="2" face="Arial, Helvetica, sans-serif"><a href="main.php"><font color="#FF0000">&lt;&lt; 
  <?PHP print $lang_164; ?> &gt;&gt;</font></a></font></b> 
  <?PHP } else { ?>
  <b><?PHP print $lang_165; ?></b></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_166; ?></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><b><i><?PHP print $lang_167; ?></i></b></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_168; ?></font></p>
<font size="2" face="Arial, Helvetica, sans-serif"><b><a href="main.php?page=list_del&nl=<?PHP print $nl; ?>&val=yes"><?PHP print $lang_169; ?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="main.php?nl=<?PHP print $nl; ?>"><?PHP print $lang_170; ?></a></b></font> 
<?PHP } ?>
