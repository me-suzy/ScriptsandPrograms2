<?php
include("common.php");
if (session_is_registered("whossession")) {
    if (($_SESSION['who']) == "admin") {
        // echo($dbname);
        if (!empty($_POST['moderator'])) {
            $mod = $_POST['moderator'];
            $modpw = $_POST['moderatorpw'];
            $modmail = $_POST['moderatorem'];

            $result = db_query("insert into {$tableprefix}tbluser(username, password, userrole, notifypost, notifymsg, mail) values(\"{$mod}\", \"{$modpw}\", \"moderator\", \"y\", \"y\", \"{$modmail}\");");
        } 

        ?>
<html>
<head>
<title>
<?=$title?>
</title>
<LINK href="incl/style2.css" rel=stylesheet>
</head>
<body bgcolor="<?=$bgcolor?>">
<FORM action=createmod.php method=post name=y>
  <TABLE align=center border=0 cellPadding=0 cellSpacing=0 width=280>
    <TBODY>
    
    <TR>
    
    <TD class=q>
    
    <TABLE border=0 cellPadding=5 cellSpacing=1 width="100%">
      <TBODY>
      
      <TR class=c>
      
      <TD>
      
      <TABLE cellPadding=1 cellSpacing=0 width="100%">
        <TBODY>
          <TR>
            <TD noWrap><SPAN class=c>Create Moderator</SPAN></TD>
            <TD noWrap>
            <SPAN class=c>
            <td align="right"><b><a href="admin.php" style="COLOR: #ffffff" title="Goto Admin">Admin</a></b></td>
            </SPAN>
        </TD>
        
        </TR>
        </TBODY>
      </TABLE>
      </TD>
      </TR>
      
      <TR class=a>
        <TD><TABLE cellSpacing=6 width="100%">
            <TBODY>
              <?php if (!empty($_POST['moderator'])) {
            // CopyRight 2004 BaalSystems Free Software, see http://baalsystems.com for details, you may NOT redistribute or resell this free software.
            ?>
              <TR>
                <TD align=left colspan=2 >Moderator <b><?php echo($moderator);

            ?></b> created successfully<br />
                  <br /></TD>
              </TR>
              <?php } 

        ?>
              <TR>
                <TD align=right width=80><B>Moderator&nbsp;Name:</B></TD>
                <TD align=right width=220><INPUT class=ia maxLength=25 name=moderator
size=25></TD>
              </TR>
              <tr>
                <td align="right" width="80"><b>Moderator Password</b></td>
                <td align="right" width="220"><input class="ia" maxLength="25" name="moderatorpw" size="25"></td>
              </tr>
              <tr>
                <td align="right" width="80"><b>Moderator Email</b></td>
                <td align="right" width="220"><input class="ia" maxLength="25" name="moderatorem" size="25"></td>
              </tr>
              <TR>
                <TD align=right colSpan=2><INPUT class=ib type=submit value="Add Moderator">
                </TD>
              </TR>
            </TBODY>
          </TABLE></TD>
      </TR>
      </TBODY>
    </TABLE>
    </TD>
    </TR>
    </TBODY>
  </TABLE>
</FORM>
</body>
</html>
<?php } else {
        echo "<center>Sorry&nbsp;with&nbsp;out&nbsp;login&nbsp;you&nbsp;cann't&nbsp;access&nbsp;this&nbsp;page</center>";
    } 
} 
ob_end_flush();

?>
