<?php
include("common.php");
session_is_registered("whossession");
$username = $_GET['username'];
$result = db_query("select * from {$tableprefix}tbluser where username='" . $username . "'");
$row = db_fetch_row($result);
if (!empty($_POST['profile'])) {
    $username = $_SESSION['username'];
	$location = $_POST['location'];
	$occupation = $_POST['occupation'];
    $result2 = db_query("UPDATE {$tableprefix}tbluser set password='" . $password . "', occupation='" . $occupation . "', location='" . $location . "', mail='" . $mail . "', notify='" . $notify . "' WHERE username='" . $username . "'");
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
<body bgcolor="<?=$bgcolor?>"><br>
<br>
<br>
<br>
<br>

  <TABLE align=center border=0 cellPadding=0 cellSpacing=0 width=340>
    <TBODY>
      <TR>
        <TD class=q><TABLE border=0 cellPadding=5 cellSpacing=1 width="100%">
            <TBODY>
              <TR class=c>
                <TD><TABLE cellPadding=1 cellSpacing=0 width="100%">
                    <TBODY>
                      <TR>
                        <TD width="49%" noWrap><SPAN class=c>User Profile</SPAN></TD>
                        <TD width="37%" align=right noWrap><B><A href="<?php if($_SESSION['who'] == "admin") {
						print(admin);
						}
						if($_SESSION['who'] == "moderator") {
						print(mod);
						}
						if($_SESSION['who'] == "user") {
						print(user);
						} ?>.php" style="COLOR: #ffffff">HOME</A></B></TD>
						<td width="14%" align="right" nowrap><b><b><a href="javascript:history.back();" style="COLOR: #ffffff" title="back">BACK</a></b></td>
                      </TR>
                    </TBODY>
                  </TABLE></TD>
              </TR>
              <TR class=a>
                <TD><TABLE align="center" cellSpacing=8>
                    <TBODY>
                      <TR>
                        <TD align=right noWrap width=50%><B> User Name <IMG alt="" hspace=2 name=qq src="pics/user1.gif">&nbsp;:</B></TD>
                        <TD width=50%><?php echo($row["username"]); ?></TD>
                      </TR>
					  <TR>
                        <TD align=right noWrap width=50%><b>Location&nbsp;:</b></TD>
                        <TD width=50%><?php echo($row["location"]); ?></TD>
                      </TR>
					  <TR>
                        <TD align=right noWrap width=50%><b>User Level&nbsp;:</b></TD>
                        <TD width=50%><?php echo($row["level"]); ?></TD>
                      </TR>
                      <TR>
                        <TD align=right noWrap width=50%><b>Occupation&nbsp;:</b></TD>
                        <TD width=50%><?php echo($row["occupation"]); ?></TD>
                      </TR>
                      <TR>
                        <TD colSpan=2><TABLE cellPadding=0 cellSpacing=0 width="100%">
                            <TBODY>
                              <TR>
                                <TD width="4%"></TD>
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
</body>
</html>
<?php } 
ob_end_flush();

?>
