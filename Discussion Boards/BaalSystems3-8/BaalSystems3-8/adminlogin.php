<?php
include("common.php");
if (!empty($_POST['password'])) {
	$username = $_POST['username'];
    $password = $_POST['password'];

    $query = "select * from {$tableprefix}tbluser where username='" . $username . "' and password='" . $password . "' and userrole='admin';";
    $result1 = db_query($query);
    $rows = db_num_rows($result1);
    $row = db_fetch_array($result1);
    if ($rows != 0) {
        if (session_is_registered("whossession")) {
            $_SESSION['who'] = "admin";
            $_SESSION['userrole'] = "admin";
            $_SESSION['username'] = $username;
            $_SESSION['usernum'] = $row["userid"];
            header("location:admin.php");
        } else {
            session_register("whossession");
            $_SESSION['who'] = "admin";
            $_SESSION['userrole'] = "admin";
            $_SESSION['username'] = $username;
            $_SESSION['usernum'] = $row["userid"];
            header("location:admin.php");
        } 
    } else {
        header("location:adminlogin.php?error=yes");
    } 
} else {

    ?>
<html>
<head>
<title>
<?=$title?>
</title>
<LINK href="incl/style2.css" rel=stylesheet>
<SCRIPT LANGUAGE="JavaScript1.2" SRC="incl/all.js" TYPE='text/javascript'></SCRIPT>
</head>
<body bgcolor="<?=$bgcolor?>">
<FORM action=adminlogin.php method=post name=y>
  <TABLE align=center cellPadding=0 cellSpacing=0 width=280>
    <TBODY>
      <TR>
        <TD class=q><TABLE border=0 cellPadding=5 cellSpacing=1 width="100%">
            <TBODY>
              <TR class=c>
                <TD><TABLE cellPadding=1 cellSpacing=0 width="100%">
                    <TBODY>
                      <TR>
                        <TD noWrap><SPAN class=c>Admin Login</SPAN></TD>
                        <TD align=right noWrap><B><A href="index.php"
style="COLOR: #ffffff">Cancel</A></B></TD>
                      </TR>
                    </TBODY>
                  </TABLE></TD>
              </TR>
            </TBODY>
          </TABLE></TD>
      </TR>
      <TR class=a>
        <TD><TABLE cellSpacing=6 width="100%">
            <TBODY>
              <?php if (!empty($_GET['error'])) {
        // CopyRight 2004 BaalSystems Free Software, see http://baalsystems.com for details, you may NOT redistribute or resell this free software.
        ?>
              <tr>
                <td colspan=2><font color="red" face="verdana">password wrong, please try again</font></td>
              </tr>
              <?php } 

    ?>
              <TR>
                <TD align=right width=80><B>Username:</B></TD>
                <TD align=right width=220><INPUT class=ia maxLength=25 name=username size=25></TD>
              </TR>
			  <TR>
                <TD align=right width=80><B>Password:</B></TD>
                <TD align=right width=220><INPUT class=ia maxLength=25 name=password size=25 type="password"></TD>
              </TR>
              <TR>
                <TD align=right colSpan=2><INPUT class=ib type=submit value=Submit>
                </TD>
              </TR>
            </TBODY>
          </TABLE></TD>
      </TR>
    </TBODY>
  </TABLE>
  <?php if ($time_show == y) {

        ?>
  <center>
    <div style="width: 600px; text-align: right"> Page generated in
      <?=getTimeElapsed();

        ?>
      second(s) </div>
  </center>
  <?php } 

    ?>
  <div align="center"><br>
    <?=$footer?>
  </div>
</FORM>
</body>
</html>
<?php } 
ob_end_flush();

?>
