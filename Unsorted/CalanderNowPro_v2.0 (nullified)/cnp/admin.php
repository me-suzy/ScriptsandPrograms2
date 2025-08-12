<font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong>Admin 
  Users</strong></font></p>
<p><font color="#000000" size="2" face="Arial, Helvetica, sans-serif"><strong>
<? 
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
if ($job == remove){
mysql_query ("DELETE FROM cnpAdmin
                                WHERE id = '$id'
								");
print "User removed";
}
if ($job == add){
$cucc = 1; 
$lists = "";
foreach ($nlbox as $something) 
{
if ($something != "")
{ 
$lists = "$lists , $something";
$cucc = $cucc + 1;
} 
}
$cucc = $cucc - 1;
$pass=base64_encode($pass);
mysql_query ("INSERT INTO cnpAdmin (user, pass, name, email, m_users, m_lists, m_cre_del, send, lists) VALUES ('$user' ,'$pass' ,'$name' ,'$email' ,'$m_users' ,'$m_lists' ,'$m_cre_del' ,'$send' ,'$lists')");  
print "User, $user, has been added.";
}
  
  if ($job == modify){
if ($val != final){ ?>
  <font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><font size="4" face="Arial, Helvetica, sans-serif" color="#336699"> 
  <?php
		  $result = mysql_query ("SELECT * FROM cnpAdmin
                         WHERE id LIKE '$id'
						 
						 limit 1
                       ");
$row = mysql_fetch_array($result)
?>
  </font></font></b></font></b></font></b></font></b></font></strong></font></p>
<form name="form1" method="post" action="">
<table width="450" border="0" cellspacing="0" cellpadding="5">
  <tr> 
    <td width="100" bgcolor="#F3F3F3"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif">Username</font></div></td>
    <td width="350" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"> 
      <?php	print $row["user"];	?>
      </font></td>
  </tr>
  <tr> 
    <td width="100"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif">Password</font></div></td>
    <td width="350"><font size="2" face="Arial, Helvetica, sans-serif"> 
      <input name="pass" type="password" id="pass" value="<?php $passnow=base64_decode ($row["pass"]); print $passnow; ?>">
      </font></td>
  </tr>
  <tr> 
    <td width="100" bgcolor="#F3F3F3"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif">Name</font></div></td>
    <td width="350" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"> 
      <input name="name" type="text" id="name" value="<?php	print $row["name"];	?>">
      </font></td>
  </tr>
  <tr> 
    <td width="100"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif">E-mail</font></div></td>
    <td width="350"><font size="2" face="Arial, Helvetica, sans-serif"> 
      <input name="email" type="text" id="email" value="<?php	print $row["email"];	?>">
      </font></td>
  </tr>
        <tr> 
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr> 
          <td colspan="2"><div align="center"><br>
              <font size="2" face="Arial, Helvetica, sans-serif">Select Lists 
              Available For This New User:</font></div>
            <table width="400" border="0" cellspacing="1" cellpadding="1" align="center">
              <tr bgcolor="#FFFFFF"> 
                <?
		$numbox = 1; 
		if (empty($offset)) {
    $offset=0;
}
		$count1 = 0;


		$finder = mysql_query ("SELECT * FROM cnpLists
		WHERE name != ''
                       	ORDER BY name 
						");

if ($find = mysql_fetch_array($finder))
{
do {
?>
                <td width="112" bgcolor="#F3F3F3"> 
                  <div align="left"><font size="1" face="Arial, Helvetica, sans-serif"> 
                    <input type="checkbox" name="nlbox[<? print $numbox; ?>]" value="<? print $find["id"]; ?>" <? 
					$selid = $find["id"];
					$seluser = $row["user"];
							
					$selector = mysql_query ("SELECT * FROM cnpAdmin
		WHERE user LIKE '$seluser'
		AND lists LIKE '%$selid%'
						");

if ($seld = mysql_fetch_array($selector))
{
print "checked";
}
?>>
                    <? print $find["name"]; ?></font></div></td>
                <?php
$count1 = $count1 + 1;
$numbox = $numbox + 1;

if ($count1 == 4){
?>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <?
$count1 = 0;
}
} while($find = mysql_fetch_array($finder));
do {
if ($count1 != 0){
?>
                <td width="144" bgcolor="#F3F3F3" >&nbsp; </td>
                <?

$count1 = $count1 + 1;
}
} while($count1 != 4 AND $count1 != 0);
}
else {
?>
                <font size="2" face="Arial, Helvetica, sans-serif"> 
                <?
print "There are currently 0 lists.  Please add a list.";
?>
                </font> 
                <?
}
?>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td colspan="2"> 
      <p><font size="2"><font face="Arial, Helvetica, sans-serif"> <br>
        <input type="submit" name="Submit2" value="Update">
        <input name="val" type="hidden" id="val" value="final">
        </font><font size="2"><font face="Arial, Helvetica, sans-serif"> </font><font size="2"><font size="2"><font face="Arial, Helvetica, sans-serif"> 
        <input name="nl" type="hidden" id="nl" value="<? print $nl; ?>">
        <input name="id" type="hidden" id="id" value="<? print $id; ?>">
        <input name="job" type="hidden" id="job" value="modify">
        </font></font></font></font><font face="Arial, Helvetica, sans-serif"> 
        </font></font></p></td>
  </tr>
</table>
  <br>
  <br>
  <p>&nbsp;</p>
</form>
<?
}
else {
?>
<p><font size="2" face="Arial, Helvetica, sans-serif" color="#990000">Your account 
  settings have been updated.</font> <font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"> 
  <?php
$pass=base64_encode($pass);
$cucc = 1; 
$lists = "";
foreach ($nlbox as $something) 
{
if ($something != "")
{ 
$lists = "$lists , $something";
$cucc = $cucc + 1;
} 
}
$cucc = $cucc - 1;
mysql_query("UPDATE cnpAdmin SET pass='$pass',name='$name',email='$email',lists='$lists' WHERE (id='$id')");
?>
  <br>
  </font> </p>
<p> <font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"> </font> 
  <? }
} 

if ($job == ""){
?>
<table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#EAEAEA">
  <tr> 
    <td> <table width="100%" border="0" cellspacing="0" cellpadding="4" bordercolor="#FFFFFF" align="center" bgcolor="#FFFFFF">
        <tr bgcolor="#EAEAEA"> 
          <td bordercolor="#CCCCCC"> <div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><b>Username</b></font></div></td>
          <td width="250" bordercolor="#CCCCCC"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><b>Name</b></font></div></td>
          <td width="125" bordercolor="#CCCCCC"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><b>Options</b></font></div></td>
        </tr>
        <?php 
$result = mysql_query ("SELECT * FROM cnpAdmin
                         WHERE user != ''
                       	ORDER BY user
						");
if ($row = mysql_fetch_array($result)) {

do {
?>
        <tr <? if ($cpick == 0){ ?>bgcolor="#F3F3F3"<? } else{ ?>bgcolor="#E9E9E9"<? } ?>> 
          <td bordercolor="#CCCCCC"> <div align="left"><font size="1" face="Arial, Helvetica, sans-serif"> 
              
              <font size="2"> <font color="#000000"> <?php print $row["user"]; ?> 
              </font></font></font></div></td>
          <td width="250" bordercolor="#CCCCCC"> <div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><font size="2"><font color="#000000"><?php print $row["name"]; ?></font></font></font><font size="2" face="Arial, Helvetica, sans-serif"> 
              </font></div></td>
          <td width="125" bordercolor="#CCCCCC"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif">
              <? if ($row["user"] != admin){ ?>
              <a href="main.php?page=admin&id=<?php print $row["id"]; ?>&nl=<? print $nl; ?>&job=remove"><img src="media/del.gif" width="11" height="7" border="0"></a>&nbsp;&nbsp;
              <? } ?>
              <a href="main.php?page=admin&id=<?php print $row["id"]; ?>&nl=<? print $nl; ?>&job=modify"><img src="media/edit.gif" width="11" height="7" border="0"></a></font></div></td>
        </tr>
        <?php
				   if ($cpick == 0){
  $cpick = 1; 
  }
  else {
  $cpick = 0;
  }
} while($row = mysql_fetch_array($result));

} else {
?>
        <tr bgcolor="#FFFFFF"> 
          <td bordercolor="#CCCCCC"> <div align="left"><font size="1" face="Arial, Helvetica, sans-serif"> 
              <font size="2"> <font color="#000000">ERROR. NO ADMIN USERS FOUND. 
              <strong>SCRIPT ERROR - REINSTALL SOFTWARE</strong></font></font></font></div></td>
          <td width="250" bordercolor="#CCCCCC"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
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
              A New User</strong></font></div></td>
        </tr>
        <tr> 
          <td height="27" bgcolor="#FFFFFF"><form action="" method="post" name="main.php" id="main.php">
              <br>
              <table width="450" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr> 
                  <td width="50%"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">Username 
                      <br>
                      <input name="user" type="text" id="user">
                      </font></div></td>
                  <td width="50%"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">Name 
                      <br>
                      <input name="name" type="text" id="pass3">
                      </font></div></td>
                </tr>
                <tr> 
                  <td width="50%"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">Password 
                      <br>
                      <input name="pass" type="text" id="pass">
                      </font></div></td>
                  <td width="50%"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">E-mail 
                      <br>
                      <input name="email" type="text" id="pass4">
                      </font></div></td>
                </tr>
                <tr> 
                  <td colspan="2">&nbsp;</td>
                </tr>
                <tr> 
                  <td colspan="2"><div align="center"><br>
                      <font size="2" face="Arial, Helvetica, sans-serif">Select 
                      Lists Available For This New User:</font></div>
                    <table width="400" border="0" cellspacing="1" cellpadding="1" align="center">
                      <tr bgcolor="#FFFFFF"> 
                        <?
		$numbox = 1; 
		if (empty($offset)) {
    $offset=0;
}
		$count1 = 0;


		$finder = mysql_query ("SELECT * FROM cnpLists
		WHERE name != ''
                       	ORDER BY name 
						");

if ($find = mysql_fetch_array($finder))
{
do {
?>
                        <td width="112" bgcolor="#F3F3F3"> <div align="left"><font size="1" face="Arial, Helvetica, sans-serif"> 
                            <input type="checkbox" name="nlbox[<? print $numbox; ?>]" value="<? print $find["id"]; ?>" checked >
                            <? print $find["name"]; ?></font></div></td>
                        <?php
$count1 = $count1 + 1;
$numbox = $numbox + 1;

if ($count1 == 4){
?>
                      </tr>
                      <tr bgcolor="#FFFFFF"> 
                        <?
$count1 = 0;
}
} while($find = mysql_fetch_array($finder));
do {
if ($count1 != 0){
?>
                        <td width="144" bgcolor="#F3F3F3" >&nbsp; </td>
                        <?

$count1 = $count1 + 1;
}
} while($count1 != 4 AND $count1 != 0);
}
else {
?>
                        <font size="2" face="Arial, Helvetica, sans-serif"> 
                        <?
print "There are currently 0 lists.  Please add a list.";
?>
                        </font> 
                        <?
}
?>
                    </table></td>
                </tr>
                <tr> 
                  <td colspan="2"><div align="center"><br>
                      <input type="submit" name="Submit" value="Submit">
                      <input name="page" type="hidden" id="page" value="admin">
                      <input name="nl" type="hidden" id="nl" value="<? print $nl; ?>">
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
<?
}
?>
