<?php
include("common.php");
if (session_is_registered("whossession")) {
    if (($_SESSION['who']) == "admin") {
        if (!empty($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $userrole = "admin";
			$level = "superadmin";
            $mail = $_POST['mail'];
			$notifymsg = "y";
			$notifypost = "y";
			$result = db_query("delete from {$tableprefix}tbluser where userrole='superadmin'");
            $result = db_query("insert into {$tableprefix}tbluser(username,password,userrole,level,mail,notifymsg,notifypost,joindate) values('" . $username . "','" . $password . "','" . $userrole . "','" . $level . "','" . $mail . "','" . $notifymsg . "','" . $notifypost . "',now())");

            if (session_is_registered("whossession")) {
                $_SESSION['who'] = "admin";
                $_SESSION['level'] = "superadmin";
				$_SESSION['username'] = $username;
                header('location:admin.php');
            } else {
                session_register("whossession");
                $_SESSION['who'] = "admin";
                $_SESSION['level'] = "superadmin";
            	$_SESSION['username'] = $username;
                header('location:admin.php');
            } 
        } 

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
<FORM action=regadmin.php method=post name=y>
  <INPUT name="db1" type=hidden value="smart">
  <TABLE align=center border=0 cellPadding=0 cellSpacing=0 width=280>
    <TBODY>
      <TR>
        <TD class=q><TABLE border=0 cellPadding=5 cellSpacing=1 width="100%">
            <TBODY>
              <TR class=c>
                <TD><TABLE cellPadding=1 cellSpacing=0 width="100%">
                    <TBODY>
                      <TR>
                        <TD noWrap><SPAN class=c>Admin Registration</SPAN></TD>
                      </TR>
                    </TBODY>
                  </TABLE></TD>
              </TR>
              <TR class=a>
                <TD><TABLE cellSpacing=6 width="100%">
                    <TBODY>
                      <TR>
                        <TD align=left colspan=2 >Please Fill Out all information to Register</TD>
                      </TR>
                      <TR>
                        <TD align=right width=80><B>Username:</B></TD>
                        <TD align=right width=220><INPUT class=ic maxLength=25 name=username
size=25></TD>
                      </TR>
                      <TR>
                        <TD align=right width=80><B>Password:</B></TD>
                        <TD align=right width=220><INPUT class=ia type="password" maxLength=25 name=password
size=25></TD>
                      </TR>
					  <TR>
                        <TD align=right width=80><B>Email:</B></TD>
                        <TD align=right width=220><INPUT class=ia maxLength=25 name=mail
size=25></TD>
                      </TR>
                      <TR>
                        <TD align=right colSpan=2><INPUT class=ib type=submit value=Submit>
                        </TD>
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
} else {
    echo "<center>Sorry&nbsp;with&nbsp;out&nbsp;login&nbsp;you&nbsp;cann't&nbsp;access&nbsp;this&nbsp;page</center>";
} 
ob_end_flush();

?>
