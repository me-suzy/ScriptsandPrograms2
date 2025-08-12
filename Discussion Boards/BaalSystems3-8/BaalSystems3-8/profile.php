<?php
include("common.php");
session_is_registered("whossession");
$username = $_SESSION['username'];
$result = db_query("select * from {$tableprefix}tbluser where username='" . $username . "'");
$row = db_fetch_row($result);
if (!empty($_POST['profile'])) {
    $username = $_SESSION['username'];
    $password = $_POST['password'];
	$location = $_POST['location'];
	$occupation = $_POST['occupation'];
    $mail = $_POST['mail'];
	$notifymsg = $_POST['notifymsg'];
	$notifypost = $_POST['notifypost'];
    $result2 = db_query("UPDATE {$tableprefix}tbluser set password='" . $password . "', occupation='" . $occupation . "', location='" . $location . "', mail='" . $mail . "', notifymsg='" . $notifymsg . "', notifypost='" . $notifypost . "' WHERE username='" . $username . "'");
	header("location:profile.php?updated=yes");
} else {

    ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>
<?=$title?>
</title>
<LINK href="incl/style2.css" rel=stylesheet>
<SCRIPT LANGUAGE="JavaScript1.2" SRC="incl/all.js" TYPE='text/javascript'></SCRIPT>
</head>
<body bgcolor="<?=$bgcolor?>">
<FORM action=profile.php method=post name=y
onsubmit="return check1form2('All fields are required...')">
  <TABLE align=center border=0 cellPadding=0 cellSpacing=0 width=340>
    <TBODY>
      <TR>
        <TD class=q><TABLE border=0 cellPadding=5 cellSpacing=1 width="100%">
            <TBODY>
              <TR class=c>
                <TD><TABLE cellPadding=1 cellSpacing=0 width="100%">
                    <TBODY>
                      <TR>
                        <TD noWrap><SPAN class=c>Profile Edit Page</SPAN></TD>
                        <TD align=right noWrap><B><A href="<?php if($_SESSION['who'] == "admin") {
						print(admin);
						}
						if($_SESSION['who'] == "moderator") {
						print(mod);
						}
						if($_SESSION['who'] == "user") {
						print(user);
						} ?>.php" style="COLOR: #ffffff">HOME</A></B></TD>
                      </TR>
                    </TBODY>
                  </TABLE></TD>
              </TR>
              <TR class=a>
                <TD><TABLE cellSpacing=8>
                    <TBODY>
                      <?php if (!empty($_GET['updated'])) {

        ?>
                      <tr>
                        <td colspan=2><center><font color="red" face="verdana">Profile Updated Successfully!</font></center></td>
                      </tr>
                      <?php } 

    ?>
                      <TR>
                        <TD align=right noWrap width=50%><B> User Name <IMG alt="" hspace=2 name=qq src="pics/user1.gif">&nbsp;:</B></TD>
                        <TD width=50%><?php echo($_SESSION['username']); ?></TD>
                      </TR>
                      <TR>
                        <TD align=right noWrap width=50%><B>Password&nbsp;:</B></TD
                        ><TD width=50%><INPUT class=ia type=password  maxLength=15  name=password value="<?php echo($row["password"]); ?>"></TD>
                      </TR>
                      <TR>
                        <TD align=right noWrap width=50%><b>Email&nbsp;:</b></TD>
                        <TD width=50%><INPUT class=ia maxLength=50  name=mail value="<?php echo($row["mail"]); ?>"></TD>
                      </TR>
					  <TR>
                        <TD align=right noWrap width=50%><b>Location&nbsp;:</b></TD>
                        <TD width=50%><INPUT class=ia maxLength=50  name=location value="<?php echo($row["location"]); ?>"></TD>
                      </TR>
                      <TR>
                        <TD align=right noWrap width=50%><b>Occupation&nbsp;:</b></TD>
                        <TD width=50%><INPUT class=ia maxLength=50  name=occupation value="<?php echo($row["occupation"]); ?>"></TD>
                      </TR>
					  <tr>
					  <td align="right"><b>Notify of Mesages&nbsp;:</b></td>
					  <td width="50%">yes:&nbsp;<?php if($row['notifymsg'] == "y") {
					  ?><input type="radio" value="y" name="notifymsg" checked><?php
					  } else {
					  ?><input type="radio" value="y" name="notifymsg"><?php
					  } ?>
					  &nbsp;no:&nbsp;<?php if($row['notifymsg'] == "n") {
					  ?><input type="radio" value="n" name="notifymsg" checked><?php
					  } else {?>
					  <input type="radio" value="n" name="notifymsg"><?php
					  } ?>
					  </td>
					  </tr>
					  <tr>
					  <td align="right"><b>Notify of all Posts&nbsp;:</b></td>
					  <td width="50%">yes:&nbsp;<?php if($row['notifypost'] == "y") {
					  ?><input type="radio" value="y" name="notifypost" checked><?php
					  } else {
					  ?><input type="radio" value="y" name="notifypost"><?php
					  } ?>
					  &nbsp;no:&nbsp;<?php if($row['notifypost'] == "n") {
					  ?><input type="radio" value="n" name="notifypost" checked><?php
					  } else {?>
					  <input type="radio" value="n" name="notifypost"><?php
					  } ?>
					  </td>
					  </tr>
                      <TR>
                        <TD colSpan=2><TABLE cellPadding=0 cellSpacing=0 width="100%">
                            <TBODY>
                              <TR>
                                <TD width="4%"></TD>
                                <td width="96%" align="center"><INPUT class=ib type=submit value=Submit name="profile"></TD>
                              </TR>
                            </TBODY>
                          </TABLE></TD>
                      </TR>
                    </TBODY>
                  </TABLE></TD>
              </TR>
            </TBODY>
          </TABLE></TD>
      </TR>
    </TBODY>
  </TABLE>
</FORM>
</body>
</html>
<?php } 
ob_end_flush();

?>
