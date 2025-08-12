<p><font size="4" face="Arial, Helvetica, sans-serif"><strong>Calendar Settings</strong></font><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong> 
  </strong><font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"> 
  <?php
//////////////////////////////////////////////////////////////////////////////                      
//                                                                          //
//  Program Name         : Calander Now Pro                                 //
//  Program version      : 2.0                                              //
//  Program Author       : Jason VandeBoom                                  //
//  Supplied by          : drew010                                          //
//  Nullified by         : CyKuH [WTN]                                      //
//  Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                          //
//////////////////////////////////////////////////////////////////////////////                      
		  $result = mysql_query ("SELECT * FROM cnpLists
                         WHERE id LIKE '$nl'
						 limit 1
                       ");
$row = mysql_fetch_array($result)
?>
  </font></b></font></b></font></b></font></b></font></font> </p>
<p> </p>
  <?
if ($action != save){
?>
<form name="adminForm" method="post" action="main.php" ONSUBMIT="copyValue(this);">
  <table width="450" border="0" cellspacing="0" cellpadding="5">
    <tr> 
      <td width="100" bgcolor="#F3F3F3"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif">Name 
          of Calendar</font></div></td>
      <td width="350" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input name="name" type="text" id="name" value="<?php	print $row["name"];	?>">
        </font></td>
    </tr>
    <tr> 
      <td width="100"><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
      <td width="350"><p><font size="2"><font face="Arial, Helvetica, sans-serif"> 
          <br>
          <input type="submit" name="Submit2" value="Update">
          </font><font size="2" face="Arial, Helvetica, sans-serif"> 
          <input name="page" type="hidden" id="page" value="settingsm">
          <input name="action" type="hidden" id="action" value="save">
          <input name="nl" type="hidden" id="nl" value="<? print $nl; ?>">
          </font><font face="Arial, Helvetica, sans-serif"> </font></font></p></td>
    </tr>
  </table>
  </form>
<?
}
else {
?>
<font size="2" face="Arial, Helvetica, sans-serif" color="#990000">Your calendar 
has been updated.</font> <font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"> 
<?php
mysql_query("UPDATE cnpLists SET name='$name' WHERE (id='$nl')");
?>
</font> 
<?
}
?>
