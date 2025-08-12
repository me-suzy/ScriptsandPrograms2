<?php
include("common.php");
if (session_is_registered("whossession")) {
    if (($_SESSION['who']) == "admin") {
        // echo($dbname);
        if (!empty($_POST['viewreset'])) {
            $view = $_POST['viewreset'];
            $id = $_POST['id']; 
            // $result = db_query("insert into {$tableprefix}tblgroup(groupname) values('" . $groupname . "')");
            $result = db_query("UPDATE {$tableprefix}tblforum SET views=" . intval($viewreset) . " WHERE forumid = " . intval($id));
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
<FORM action=viewreset.php method=post name=y>
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
            <TD noWrap><SPAN class=c>View Reset</SPAN></TD>
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
              <?php
        if (!empty($_POST['id'])) {
            // CopyRight 2004 BaalSystems Free Software, see http://baalsystems.com for details, you may NOT redistribute or resell this free software.

            ?>
              <TR>
                <TD align=left colspan=2 >View reset successfully</TD>
              </TR>
              <?php } else {

            ?>
            <INPUT name=id value="<?=$_GET['fid']?>" type="hidden">
            <TR>
              <TD align=right width=80><B>View&nbsp;:</B></TD>
              <TD align=right width=220><INPUT class=ia maxLength=25 name=viewreset size=25 value="<?=$_GET['view']?>"></TD>
            </TR>
            <TR>
              <TD align=right colSpan=2><INPUT class=ib type=submit value=Submit>
              </TD>
            </TR>
            <?php } 

        ?>
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
