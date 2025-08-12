<?PHP
if ($usernow != "admin"){
print "Invalid Permissions";
die();
}
?>
<font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_10; ?></strong></font></p>
<p><font color="#000000" size="2" face="Arial, Helvetica, sans-serif"><strong>
<?PHP 
if ($job == remove){
mysql_query ("DELETE FROM Admin
                                WHERE id = '$id'
								");
print $lang_11;
}
if ($job == add){
$cucc = 1; 
$lists = "";
foreach ($nlbox as $something) 
{
if ($something != "")
{ 
$lists = "$lists , $something ";
$cucc = $cucc + 1;
} 
}
$cucc = $cucc - 1;
$pass=base64_encode($pass);
mysql_query ("INSERT INTO Admin (user, pass, name, email, m_users, m_lists, m_cre_del, send, lists, m_dusers, m_limit, a_li, a_li2) VALUES ('$user' ,'$pass' ,'$name' ,'$email' ,'$m_users' ,'$m_lists' ,'$m_cre_del' ,'$send' ,'$lists' ,'$m_dusers' ,'$m_limit' ,'$a_li' ,'$a_li2')");  
print "$lang_12, $user, $lang_13.";
}
  
  if ($job == modify){
if ($val != final){ ?>
  <font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><font size="4" face="Arial, Helvetica, sans-serif" color="#336699"> 
  <?PHP
		  $result = mysql_query ("SELECT * FROM Admin
                         WHERE id LIKE '$id'
						 
						 limit 1
                       ");
$row = mysql_fetch_array($result)
?>
  </font></font></b></font></b></font></b></font></b></font></strong></font></p>
<form name="form1" method="post" action="main.php">
<table width="450" border="0" cellspacing="0" cellpadding="5">
  <tr> 
    <td width="100" bgcolor="#F3F3F3"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_2; ?></font></div></td>
    <td width="350" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"> 
      <?PHP	print $row["user"];	?>
      </font></td>
  </tr>
  <tr> 
    <td width="100"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_3; ?></font></div></td>
    <td width="350"><font size="2" face="Arial, Helvetica, sans-serif"> 
      <input name="pass" type="password" id="pass" value="<?PHP $passnow=base64_decode ($row["pass"]); print $passnow; ?>">
      </font></td>
  </tr>
  <tr> 
    <td width="100" bgcolor="#F3F3F3"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_4; ?></font></div></td>
    <td width="350" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"> 
      <input name="name" type="text" id="name" value="<?PHP	print $row["name"];	?>">
      </font></td>
  </tr>
  <tr> 
    <td width="100"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_5; ?></font></div></td>
    <td width="350"><font size="2" face="Arial, Helvetica, sans-serif"> 
      <input name="email" type="text" id="email" value="<?PHP	print $row["email"];	?>">
      </font></td>
  </tr>
  <tr> 
    <td colspan="2"><table width="450" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr valign="top"> 
          <td width="50%"><br> 
              <table width="200" border="0" align="center" cellpadding="5" cellspacing="0">
                <tr> 
                  <td width="0" bgcolor="#F3F3F3"><div align="left"> 
                      <input name="m_users" type="checkbox" id="m_users" value="1" <?PHP if ($row["m_users"] == 1){ print "checked"; } ?>>
                    </div></td>
                  <td width="400" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_14; ?></font></td>
                </tr>
                <tr>
                  <td><input name="m_dusers" type="checkbox" id="m_dusers" value="1" <?PHP if ($row["m_dusers"] == 1){ print "checked"; } ?>></td>
                  <td><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_468; ?></font></td>
                </tr>
                <tr> 
                  <td width="0"><div align="left"> 
                      <input name="m_lists" type="checkbox" id="m_lists" value="1" <?PHP if ($row["m_lists"] == 1){ print "checked"; } ?>>
                    </div></td>
                  <td width="400"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_15; ?></font></td>
                </tr>
              </table></td>
          <td width="50%"><br> <table width="200" border="0" align="center" cellpadding="5" cellspacing="0">
              <tr bgcolor="#F3F3F3"> 
                <td><div align="left"> 
                    <input name="m_cre_del" type="checkbox" id="m_cre_del" value="1" <?PHP if ($row["m_cre_del"] == 1){ print "checked"; } ?>>
                  </div></td>
                <td bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_16; ?></font></td>
              </tr>
              <tr> 
                <td width="0"><div align="left"> 
                    <input name="send" type="checkbox" id="send" value="1" <?PHP if ($row["send"] == 1){ print "checked"; } ?>>
                  </div></td>
                <td width="400"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_17; ?></font></td>
              </tr>
            </table></td>
        </tr>
        <tr> 
            <td colspan="2"><p>&nbsp;</p>
              <table width="425" border="0" align="center" cellpadding="5" cellspacing="0">
                <tr> 
                  <td bgcolor="#F3F3F3"> <div align="center"> 
                      <input name="m_limit" type="text" id="m_limit" value="<?PHP	print $row["m_limit"];	?>" size="5">
                    </div></td>
                  <td width="220" bgcolor="#F3F3F3"> <font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_491; ?></font></td>
                </tr>
                <tr> 
                  <td><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
                      <input name="a_li" type="text" id="a_li" value="<?PHP	print $row["a_li"];	?>" size="10">
                      </font> 
                      <select name="a_li2" id="a_li2">
                        <option value="day" <?PHP if ($row["a_li2"] == "" OR $row["a_li2"] == "day"){ print "selected"; } ?>>Per 
                        Day</option>
                        <option value="week" <?PHP if ($row["a_li2"] == "week"){ print "selected"; } ?>>Per 
                        Week</option>
                        <option value="month" <?PHP if ($row["a_li2"] == "month"){ print "selected"; } ?>>Per 
                        Month</option>
                      </select>
                      <br>
                      <font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_478; ?></font></div></td>
                  <td width="220"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_477; ?> 
                    </font></td>
                </tr>
              </table>
              
            </td>
        </tr>
        <tr> 
          <td colspan="2"><div align="center"><br>
              <font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_18; ?>:</font></div>
            <table width="400" border="0" cellspacing="1" cellpadding="1" align="center">
              <tr bgcolor="#FFFFFF"> 
                <?PHP
		$numbox = 1; 
		if (empty($offset)) {
    $offset=0;
}
		$count1 = 0;


		$finder = mysql_query ("SELECT * FROM Lists
		WHERE name != ''
                       	ORDER BY name 
						");

if ($c = mysql_num_rows($finder))
{
while($find = mysql_fetch_array($finder)){
?>
                <td width="112" bgcolor="#F3F3F3"> 
                  <div align="left"><font size="1" face="Arial, Helvetica, sans-serif"> 
                    <input type="checkbox" name="nlbox[<?PHP print $numbox; ?>]" value="<?PHP print $find["id"]; ?>" <?PHP 
					$selid = $find["id"];
					$seluser = $row["user"];
					$selid = " , $selid ";
					$selector = mysql_query ("SELECT * FROM Admin
		WHERE user LIKE '$seluser'
		AND lists LIKE '%$selid%'
						");

if ($seld = mysql_fetch_array($selector))
{
print "checked";
}
?>>
                    <?PHP print $find["name"]; ?></font></div></td>
                <?PHP
$count1 = $count1 + 1;
$numbox = $numbox + 1;

if ($count1 == 4){
?>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <?PHP
$count1 = 0;
}
}
while($count1 != 4 AND $count1 != 0) {
if ($count1 != 0){
?>
                <td width="144" bgcolor="#F3F3F3" >&nbsp; </td>
                <?PHP

$count1 = $count1 + 1;
}
}
}
else {
?>
                <font size="2" face="Arial, Helvetica, sans-serif"> 
                <?PHP
print "<?PHP print $lang_19; ?>";
?>
                </font> 
                <?PHP
}
?>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td colspan="2"> 
      <p><font size="2"><font face="Arial, Helvetica, sans-serif"> <br>
        <input type="submit" name="Submit2" value="<?PHP print $lang_6; ?>">
        <input name="val" type="hidden" id="val" value="final">
        </font><font size="2"><font face="Arial, Helvetica, sans-serif"> </font><font size="2"><font size="2"><font face="Arial, Helvetica, sans-serif"> 
        <input name="nl" type="hidden" id="nl" value="<?PHP print $nl; ?>">
        <input name="id" type="hidden" id="id" value="<?PHP print $id; ?>">
        <input name="job" type="hidden" id="job" value="modify">
		<input name="page" type="hidden" id="page" value="admin">
        </font></font></font></font><font face="Arial, Helvetica, sans-serif"> 
        </font></font></p></td>
  </tr>
</table>
  </form>
<?PHP
}
else {
?>
<p><font size="2" face="Arial, Helvetica, sans-serif" color="#990000"><?PHP print $lang_9; ?></font> <font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"> 
  <?PHP
$pass=base64_encode($pass);
$cucc = 1; 
$lists = "";
foreach ($nlbox as $something) 
{
if ($something != "")
{ 
$lists = "$lists , $something ";
$cucc = $cucc + 1;
} 
}
$cucc = $cucc - 1;
mysql_query("UPDATE Admin SET pass='$pass',name='$name',email='$email',m_users='$m_users',m_lists='$m_lists',m_cre_del='$m_cre_del',send='$send',lists='$lists',m_dusers='$m_dusers',m_limit='$m_limit',a_li='$a_li',a_li2='$a_li2' WHERE (id='$id')");
?>
  <br>
  </font> </p>
<p> <font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"> </font> 
  <?PHP }
} 

if ($job == ""){
?>
<table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#BFD2E8">
  <tr> 
    <td> <table width="100%" border="0" cellspacing="0" cellpadding="4" bordercolor="#FFFFFF" align="center" bgcolor="#FFFFFF">
        <tr bgcolor="#D5E2F0"> 
          <td bordercolor="#CCCCCC" bgcolor="#D5E2F0"> <div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_2; ?></b></font></div></td>
          <td width="250" bordercolor="#CCCCCC"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_4; ?></b></font></div></td>
          <td width="125" bordercolor="#CCCCCC"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_22; ?></b></font></div></td>
        </tr>
        <?PHP 
$result = mysql_query ("SELECT * FROM Admin
                         WHERE user != ''
                       	ORDER BY user
						");
if ($c = mysql_num_rows($result)) {

while($row = mysql_fetch_array($result)) {
?>
        <tr <?PHP if ($cpick == 0){ ?>bgcolor="#F3F3F3"<?PHP } else{ ?>bgcolor="#E9E9E9"<?PHP } ?>> 
          <td bordercolor="#CCCCCC"> <div align="left"><font size="1" face="Arial, Helvetica, sans-serif"> 
              
              <font size="2"> <font color="#000000"> <?PHP print $row["user"]; ?> 
              </font></font></font></div></td>
          <td width="250" bordercolor="#CCCCCC"> <div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><font size="2"><font color="#000000"><?PHP print $row["name"]; ?></font></font></font><font size="2" face="Arial, Helvetica, sans-serif"> 
              </font></div></td>
          <td width="125" bordercolor="#CCCCCC"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
              <?PHP if ($row["user"] != admin){ ?>
              <a href="main.php?page=admin&id=<?PHP print $row["id"]; ?>&nl=<?PHP print $nl; ?>&job=remove"><img src="media/del.gif" width="11" height="7" border="0"></a>&nbsp;&nbsp; 
              <?PHP } ?>
              <a href="main.php?page=admin&id=<?PHP print $row["id"]; ?>&nl=<?PHP print $nl; ?>&job=modify"><img src="media/edit.gif" width="11" height="7" border="0"></a>
			  <?PHP if ($row["user"] != "admin"){ ?><br>
              <a href="main.php?page=admin_pre_m&aid=<?PHP print $row["id"]; ?>"><font size="1">Default 
              list settings</font></a><?PHP } ?></font></div></td>
        </tr>
        <?PHP
				   if ($cpick == 0){
  $cpick = 1; 
  }
  else {
  $cpick = 0;
  }
}

} else {
?>
        <tr bgcolor="#FFFFFF"> 
          <td bordercolor="#CCCCCC"> <div align="left"><font size="1" face="Arial, Helvetica, sans-serif"> 
              <font size="2"> <font color="#000000">FATAL ERROR. NO ADMIN USERS FOUND. 
              <strong>SCRIPT ERROR - REINSTALL SOFTWARE</strong></font></font></font></div></td>
          <td width="250" bordercolor="#CCCCCC"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
              </font></div></td>
          <td width="125" bordercolor="#CCCCCC"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
              </font></div></td>
        </tr>
        <?PHP
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
<table width="450" border="0" cellpadding="1" cellspacing="0" bgcolor="#BFD2E8">
  <tr>
    <td><div align="center"><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"></font></div>
      <table width="100%" border="0" cellpadding="4" cellspacing="0" bgcolor="#F3F3F3">
        <tr> 
          <td bgcolor="#D5E2F0"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_20; ?></strong></font></div></td>
        </tr>
        <tr> 
          <td height="27" bgcolor="#FFFFFF"><form action="main.php" method="post" name="" id="">
              <br>
              <table width="450" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr> 
                  <td width="50%"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_2; ?> 
                      <br>
                      <input name="user" type="text" id="user">
                      </font></div></td>
                  <td width="50%"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_4; ?> 
                      <br>
                      <input name="name" type="text" id="pass3">
                      </font></div></td>
                </tr>
                <tr> 
                  <td width="50%"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_3; ?> 
                      <br>
                      <input name="pass" type="text" id="pass">
                      </font></div></td>
                  <td width="50%"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_5; ?> 
                      <br>
                      <input name="email" type="text" id="pass4">
                      </font></div></td>
                </tr>
                <tr valign="top"> 
                  <td><br> 
                    <table width="200" border="0" align="center" cellpadding="5" cellspacing="0">
                      <tr> 
                        <td width="0" bgcolor="#F3F3F3"><div align="left"> 
                            <input name="m_users" type="checkbox" id="m_users" value="1" checked>
                          </div></td>
                        <td width="400" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_14; ?></font></td>
                      </tr>
                      <tr> 
                        <td><div align="left"> 
                            <input name="m_dusers" type="checkbox" id="m_dusers" value="1" checked>
                          </div></td>
                        <td><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_468; ?></font></td>
                      </tr>
                      <tr> 
                        <td width="0" bgcolor="#F3F3F3"><div align="left"> 
                            <input name="m_lists" type="checkbox" id="m_lists" value="1" checked>
                          </div></td>
                        <td width="400" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_15; ?></font></td>
                      </tr>
                    </table></td>
                  <td><br> <table width="200" border="0" align="center" cellpadding="5" cellspacing="0">
                      <tr bgcolor="#F3F3F3"> 
                        <td><div align="left"> 
                            <input name="m_cre_del" type="checkbox" id="m_cre_del" value="1" checked>
                          </div></td>
                        <td bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_16; ?></font></td>
                      </tr>
                      <tr> 
                        <td width="0"><div align="left"> 
                            <input name="send" type="checkbox" id="send" value="1" checked>
                          </div></td>
                        <td width="400"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_17; ?></font></td>
                      </tr>
                    </table></td>
                </tr>
                <tr> 
                  <td colspan="2"><br>
                    <table width="425" border="0" align="center" cellpadding="5" cellspacing="0">
                      <tr> 
                        <td bgcolor="#F3F3F3"> <div align="center"> 
                            <input name="m_limit" type="text" id="m_limit" value="0" size="5">
                          </div></td>
                        <td width="220" bgcolor="#F3F3F3"></font> <font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_491; ?></font></td>
                      </tr>
                      <tr> 
                        <td><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
                            <input name="a_li" type="text" id="a_li" size="10">
                            </font> 
                            <select name="a_li2" id="a_li2">
                              <option value="day" <?PHP if ($row["a_pt"] == "" OR $row["a_pt"] == "day"){ print "selected"; } ?>>Per 
                              Day</option>
                              <option value="week" <?PHP if ($row["a_pt"] == "week"){ print "selected"; } ?>>Per 
                              Week</option>
                              <option value="month" <?PHP if ($row["a_pt"] == "month"){ print "selected"; } ?>>Per 
                              Month</option>
                            </select>
                            <br>
                            <font size="1" face="Arial, Helvetica, sans-serif"><?PHP print $lang_478; ?></font></div></td>
                        <td width="220"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_477; ?> 
                          </font></td>
                      </tr>
                    </table></td>
                </tr>
                <tr> 
                  <td colspan="2"><div align="center"><br>
                      <font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_18; ?>:</font></div>
                    <table width="425" border="0" cellspacing="1" cellpadding="1" align="center">
                      <tr bgcolor="#FFFFFF"> 
                        <?PHP
		$numbox = 1; 
		if (empty($offset)) {
    $offset=0;
}
		$count1 = 0;


		$finder = mysql_query ("SELECT * FROM Lists
		WHERE name != ''
                       	ORDER BY name 
						");

if ($c = mysql_num_rows($finder))
{
while($find = mysql_fetch_array($finder)) {
?>
                        <td width="106" bgcolor="#F3F3F3"> 
                          <div align="left"><font size="1" face="Arial, Helvetica, sans-serif"> 
                            <input type="checkbox" name="nlbox[<?PHP print $numbox; ?>]" value="<?PHP print $find["id"]; ?>" checked >
                            <?PHP print $find["name"]; ?></font></div></td>
                        <?PHP
$count1 = $count1 + 1;
$numbox = $numbox + 1;

if ($count1 == 4){
?>
                      </tr>
                      <tr bgcolor="#FFFFFF"> 
                        <?PHP
$count1 = 0;
}
}
while($count1 != 4 AND $count1 != 0) {
if ($count1 != 0){
?>
                        <td width="106" bgcolor="#F3F3F3" >&nbsp; </td>
                        <?PHP

$count1 = $count1 + 1;
}
}
}
else {
?>
                        <font size="2" face="Arial, Helvetica, sans-serif"> 
                        <?PHP
print "$lang_19";
?>
                        </font> 
                        <?PHP
}
?>
                    </table></td>
                </tr>
                <tr> 
                  <td colspan="2"><div align="center"><br>
                      <input type="submit" name="Submit" value="<?PHP print $lang_21; ?>">
                      <input name="page" type="hidden" id="page" value="admin">
                      <input name="nl" type="hidden" id="nl" value="<?PHP print $nl; ?>">
                      <input name="job" type="hidden" id="job" value="add">
                    </div></td>
                </tr>
              </table>
            </form>
            
          </td>
        </tr>
      </table></td>
  </tr>
</table>
<?PHP
}
?>
