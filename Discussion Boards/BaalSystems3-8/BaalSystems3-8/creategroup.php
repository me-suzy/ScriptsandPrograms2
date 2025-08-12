<?php ob_start();
include("common.php");
if (session_is_registered("whossession")) {
    if (($_SESSION['who']) == "admin") {
        // echo($dbname);
        if (!empty($_POST['groupname'])) {
            $groupname = $_POST['groupname'];

            $result = db_query("insert into {$tableprefix}tblgroup(groupname) values('" . $groupname . "')");
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
<FORM action=creategroup.php method=post name=y>
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
            <TD noWrap><SPAN class=c>Create Group</SPAN></TD>
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
              <?php if (!empty($_POST['groupname'])) {
            // CopyRight 2004 BaalSystems Free Software, see http://baalsystems.com for details, you may NOT redistribute or resell this free software.
            ?>
              <TR>
                <TD align=left colspan=2 >Group <b><?php echo($groupname);

            ?></b> created successfully</TD>
              </TR>
              <?php } 

        ?>
              <TR>
                <TD align=right width=80><B>Group&nbsp;Name:</B></TD>
                <TD align=right width=220><INPUT class=ia maxLength=25 name=groupname
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
