<p><font size="4" face="Arial, Helvetica, sans-serif"><strong>Select A Calendar</strong></font></p>
<table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#EAEAEA">
  <tr> 
    <td> <table width="100%" border="0" cellspacing="0" cellpadding="4" bordercolor="#FFFFFF" align="center" bgcolor="#FFFFFF">
        <tr bgcolor="#EAEAEA"> 
          <td bordercolor="#CCCCCC"> <div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><b>Calendar 
              Name </b></font></div></td>
          <td width="125" bordercolor="#CCCCCC"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><b># 
              of Events</b></font></div></td>
          <td width="125" bordercolor="#CCCCCC"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><b>Date 
              Created </b></font></div></td>
        </tr>
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
                         WHERE name != ''
                       	ORDER BY name
						");
if ($row = mysql_fetch_array($result)) {

do {
$selid = $row["id"];
$seluser = $row_admin["user"];
					$selector = mysql_query ("SELECT * FROM cnpAdmin
		WHERE user LIKE '$seluser'
		AND lists LIKE '%$selid%'
						");

if ($seld = mysql_fetch_array($selector))
{


?>
        <tr <? if ($cpick == 0){ ?>bgcolor="#F3F3F3"<? } else{ ?>bgcolor="#E9E9E9"<? } ?>> 
          <td bordercolor="#CCCCCC"> <div align="left"><font size="1" face="Arial, Helvetica, sans-serif"> 
              <a href="main.php?nl=<? print $row["id"]; ?>"> 
              <font size="2"> <font color="#000000"> <?php print $row["name"]; ?> 
              </font></font></a></font></div></td>
          <td width="125" bordercolor="#CCCCCC"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif">
              <?php 

$nlid = $row["id"];
$findcount = mysql_query ("SELECT * FROM cnpCalendar
                         WHERE nl LIKE '$nlid'
                       ");

$countdata = mysql_num_rows($findcount);	
print $countdata;
?>
              </font></div></td>
          <td width="125" bordercolor="#CCCCCC"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><? print $row["date"]; ?> 
              </font></div></td>
        </tr>
        <?php
				   if ($cpick == 0){
  $cpick = 1; 
  }
  else {
  $cpick = 0;
  }
  }
} while($row = mysql_fetch_array($result));

} else {
?>
        <tr bgcolor="#FFFFFF"> 
          <td bordercolor="#CCCCCC"> <div align="left"><font size="1" face="Arial, Helvetica, sans-serif"> 
              <font size="2"> <font color="#000000">There are no calendars to 
              choose from. Please <strong>create</strong> a calendar below.</font></font></font></div></td>
          <td width="125" bordercolor="#CCCCCC"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
              </font></div></td>
          <td width="125" bordercolor="#CCCCCC"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
              </font></div></td>
        </tr>
        <?php
				   if ($cpick == 0){
  $cpick = 1; 
  }
  else {
  $cpick = 0;
  }
}
?>
      </table></td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="450" border="0" cellpadding="1" cellspacing="0" bgcolor="#EAEAEA">
  <tr>
    <td><div align="center"><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"></font></div>
      <table width="100%" border="0" cellpadding="4" cellspacing="0" bgcolor="#F3F3F3">
        <tr> 
          <td bgcolor="#EAEAEA"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><strong>Add 
              A Calendar</strong></font></div></td>
        </tr>
        <tr> 
          <td height="27" bgcolor="#FFFFFF"><form action="" method="post" name="main.php" id="main.php">
              <br>
              <table width="90%" border="0" align="center" cellpadding="2" cellspacing="0">
                <tr valign="top"> 
                  <td width="100"><font size="2" face="Arial, Helvetica, sans-serif">Name 
                    of Calendar</font></td>
                  <td><font size="2" face="Arial, Helvetica, sans-serif"> 
                    <input name="name" type="text" id="name">
                    </font></td>
                </tr>
                <tr> 
                  <td width="100">&nbsp;</td>
                  <td><br> <input type="submit" name="Submit" value="Submit"> 
                    <input name="page" type="hidden" id="page" value="list_add"> 
                  </td>
                </tr>
              </table>
            </form>
            
          </td>
        </tr>
      </table></td>
  </tr>
</table>
<p>&nbsp;</p>
<p><strong><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"></font></strong></p>
