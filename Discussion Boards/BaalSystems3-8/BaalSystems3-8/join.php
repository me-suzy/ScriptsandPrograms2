<?php
include("common.php");
if (!empty($_POST['name'])) {
    $username = $_POST['name'];
    $password = $_POST['password'];
	$location = $_POST['location'];
	$occupation = $_POST['occupation'];
    $mail = $_POST['mail'];
    $result1 = db_query("select * from {$tableprefix}tbluser where username='" . $username . "'");
    $rows = db_num_rows($result1);
    if ($rows != 0) {
        header("location:join.php?error=idexist");
    } else {
        $result2 = db_query("insert into {$tableprefix}tbluser(username,password,level,joindate,occupation,location,mail,userrole,notifymsg,notifypost) values('" . $username . "','" . $password . "','New User',NOW(),'" . $occupation . "','" . $location . "','" . $mail . "','user','y','y')");
        print("<div align=center>you have registered successfully<br>");
        print("your usename is : <b>" . $username . "</b><br>");
        print("<a href='userlogin.php' onfocus='blur();'>click here to Login</a></div>");
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
<FORM action=join.php method=post name=y
onsubmit="return check1form2('All fields are required...')">
  <TABLE align=center border=0 cellPadding=0 cellSpacing=0 width=300>
    <TBODY>
      <TR>
        <TD class=q><TABLE border=0 cellPadding=5 cellSpacing=1 width="100%">
            <TBODY>
              <TR class=c>
                <TD><TABLE cellPadding=1 cellSpacing=0 width="100%">
                    <TBODY>
                      <TR>
                        <TD noWrap><SPAN class=c>Registration Form</SPAN></TD>
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
                        <td colspan=2><font color="red" face="verdana">user name you have entered is already selected, please try by another user name</font></td>
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
                        <TD align=right noWrap width=50%><b>Email&nbsp;:</b></TD>
                        <TD width=50%><INPUT class=ia maxLength=50  name=mail ></TD>
                      </TR>
					  <TR>
                        <TD align=right noWrap width=50%><b>Location&nbsp;:</b></TD>
                        <TD width=50%><INPUT class=ia maxLength=50  name=location ></TD>
                      </TR>
                      <TR>
                        <TD align=right noWrap width=50%><b>Occupation&nbsp;:</b></TD>
                        <TD width=50%><INPUT class=ia maxLength=50  name=occupation ></TD>
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
