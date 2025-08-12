<?php
include("common.php");
if (session_is_registered("whossession")) {
    if (($_SESSION['who']) == "admin") {
        if (!empty($_POST['sbm'])) {
            $filename = "int_std.php";
            $tl = $_POST['title'];
            $bg = $_POST['bground'];
            $foot = $_POST['footer'];
            if (!$datafile = @fopen($filename, 'w')) {
                echo "Cannot open file $filename";
                exit;
            } else {
                $strcontent = "<?php \$title='{$tl}'; \$bgcolor='{$bg}';  \$footer='{$foot}';  ?>
";
                fwrite($datafile, $strcontent , strlen($strcontent));
                fclose($datafile);
            } 
            header("location:admin.php");
        } 

        ?>
<html>
<head>
<title>Baal Smart Form</title>
<LINK href="incl/style2.css" rel=stylesheet>
</head>
<body bgcolor="<?=$bgcolor?>">
<FORM action=interface.php method=post name=y>
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
              <?php if (!empty($_POST['sbm'])) {
            // CopyRight 2004 BaalSystems Free Software, see http://baalsystems.com for details, you may NOT redistribute or resell this free software.
            ?>
              <TR>
                <TD align=left colspan=2 >Interface changes done successfully</TD>
              </TR>
              <?php } 

        ?>
              <TR>
                <TD align=right width=80><B>Title:</B></TD>
                <TD align=left width=220><INPUT class=ia maxLength=25 name="title" size=30 value="<?=$title?>"></TD>
              </TR>
              <TR>
                <TD align=right width=80><B>Background Color:</B></TD>
                <TD align=left width=220><INPUT class=ia maxLength=25 name="bground" size=25 value="<?=$bgcolor?>"></TD>
              </TR>
              <TR>
                <TD align=right width=80><B>Footer :</B></TD>
                <TD align=left width=220><textarea name="footer" class="ia" cols="25" rows="5"><?=$footer?>
</textarea></TD>
              </TR>
              <TR>
                <TD align=right colSpan=2><INPUT class=ib type=submit value=Submit name="sbm">
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
