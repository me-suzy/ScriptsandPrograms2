<?php
include("common.php");
if (session_is_registered("whossession")) {
    if (($_SESSION['who']) == "admin") {
        if (!empty($_POST['groupname'])) {
            $groupname = $_POST['groupname'];

            $result = db_query("delete from {$tableprefix}tblgroup where groupname='" . $groupname . "'");
        } 

        ?>
<?php

        $result = db_query("select * from {$tableprefix}tblgroup");

        // mysql_free_result($result);
        // mysql_close();
        // CopyRight 2004 BaalSystems Free Software, see http://baalsystems.com for details, you may NOT redistribute or resell this free software.
        ?>
<html>
<head>
<title>
<?=$title?>
</title>
<LINK href="incl/style2.css" rel=stylesheet>
</head>
<body bgcolor="<?=$bgcolor?>">
<FORM action=deletegroup.php method=post name=y>
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
            <TD noWrap><SPAN class=c>Delete Group</SPAN></TD>
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

            ?>
              <TR>
                <TD align=left colspan=2 >Group <b><?php echo($groupname);

            ?></b> deleted successfully</TD>
              </TR>
              <?php } 

        ?>
              <TR>
                <!--<TD align=right width=50%><B>Select&nbsp;Group:</B></TD>-->
                <TD align=center><select name="groupname">
                    <option selected value="">--Select Group--</option>
                    <?php
        while ($row = db_fetch_row($result)) {
            echo"<option value='" . $row["groupname"] . "'>" . $row["groupname"] . "</option>";
        } 

        ?>
                  </select>
                </TD>
              </TR>
              <TR>
                <TD align=right colSpan=2><INPUT class=ib type=submit value=Delete>
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
