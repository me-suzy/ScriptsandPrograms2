<?php
include("common.php");
if (!empty($_POST['name'])) {
    $username = $_POST['name'];
    $password = $_POST['password'];
    $result1 = db_query("select * from {$tableprefix}tbluser where username='" . $username . "' and password='" . $password . "'");
    $rows = db_num_rows($result1);
    $row = db_fetch_array($result1);
    if ($rows != 0) {
        if (session_is_registered("whossession")) {
            $_SESSION['who'] = "user";
            $_SESSION['username'] = $username;
            $_SESSION['usernum'] = $row["userid"];
            header("location:user.php");
        } else {
            session_register("whossession");
            $_SESSION['who'] = "user";
            $_SESSION['username'] = $username;
            $_SESSION['usernum'] = $row["userid"];
            header("location:user.php");
        } 
    } else {
        header("location:userlogin.php?error=yes");
    } 
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
<FORM action=userlogin.php method=post name=y
onsubmit="return check1form1('All fields are required...')">
  <TABLE align=center border=0 cellPadding=0 cellSpacing=0 width=300>
    <TBODY>
      <TR>
        <TD class=q><TABLE border=0 cellPadding=5 cellSpacing=1 width="100%">
            <TBODY>
              <TR class=c>
                <TD><TABLE cellPadding=1 cellSpacing=0 width="100%">
                    <TBODY>
                      <TR>
                        <TD noWrap><SPAN class=c>Login </SPAN></TD>
                        <TD align=right noWrap><B><A href="index.php"
style="COLOR: #ffffff">Cancel</A></B></TD>
                      </TR>
                    </TBODY>
                  </TABLE></TD>
              </TR>
              <TR class=a>
                <TD><TABLE cellSpacing=8>
                    <TBODY>
                      <?php if (!empty($_GET['error'])) {

        ?>
                      <tr>
                        <td colspan=2><font color="red" face="verdana">wrong user name and password ,<br>
                          please try again</font></td>
                      </tr>
                      <?php } 

    ?>
                      <TR>
                        <TD align=right noWrap width=50%><B> User Name <IMG alt="" hspace=2 name=qq src="pics/user1.gif">&nbsp;:</B></TD>
                        <TD width=50%><INPUT class=ia maxLength=20 name=name></TD>
                      </TR>
                      <TR>
                        <TD align=right noWrap width=50%><B>Password&nbsp;:</B></TD>
                        <TD width=50%><INPUT class=ia type=password  maxLength=15  name=password ></TD>
                      </TR>
					  <TR>
                        <TD colSpan=2><TABLE cellPadding=0 cellSpacing=0 width="100%">
                            <TBODY>
                              <TR>
                                <TD></TD>
                                <td align="center"><INPUT class=ib type=submit value=Submit></TD>
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
