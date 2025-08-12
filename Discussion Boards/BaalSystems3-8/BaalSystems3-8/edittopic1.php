<?php
include("common.php");
if ((session_is_registered("whossession") && ($_SESSION['who']) == "user")) {
    if (!empty($_POST['sbm'])) {
        $result=db_query("select count(*) from {$tableprefix}tblsubforum where authorname='" . $_SESSION['username'] . "' and subforumid=" . intval($_POST['sfid']));
        $row=db_fetch_array($result);
        if ($row[0] < 1) {
            echo "<center>Sorry&nbsp;with&nbsp;out&nbsp;login&nbsp;you&nbsp;cann't&nbsp;access&nbsp;this&nbsp;page</center>";
            exit;
        }
        $sticky = 0;
        if (isset($_POST['sticky']))
            $sticky = $_POST['sticky'];
        
        $detail = $_POST["detail"];
        $detail.="<!--EDITED--><br><span style=\\\"font: italic 10px\\\">Post was edited by <b>" . $_SESSION['username'] . "</b> at " . date("D M j G:i:s Y");

        $result = db_query("UPDATE {$tableprefix}tblsubforum SET subject='" . $_POST['subject'] . "' , authorname='" . $_POST['authorname'] . "' , detail='" . $detail . "' , groupname='" . $_POST['groupname'] . "', sticky='" . intval($sticky) . "' WHERE subforumid=" . intval($_POST['sfid']));
        // echo "Result :".$result."<br>";
        // print_r($_POST);
        header("location:subtopic1.php?fid=" . $_POST['fid']);
    } else {
        $result=db_query("select count(*) from {$tableprefix}tblsubforum where authorname='" . $_SESSION['username'] . "' and subforumid=" . intval($_GET['sfid']));
        $row=db_fetch_array($result);
        if ($row[0] < 1) {
            echo "<center>Sorry&nbsp;with&nbsp;out&nbsp;login&nbsp;you&nbsp;cann't&nbsp;access&nbsp;this&nbsp;page</center>";
            exit;
        }
        $result = db_query("SELECT * FROM {$tableprefix}tblsubforum WHERE subforumid=" . intval($_GET['sfid']));
        $row = db_fetch_row($result);
        $pos=strpos($row["detail"], "<!--EDITED-->");
        if ($pos !== false) {
            $row["detail"]=substr($row["detail"], 0, $pos);
        }
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
<!-- tinyMCE -->
<script language="javascript" type="text/javascript" src="incl/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
    tinyMCE.init({
        theme : "advanced",
        mode : "textareas",
        theme_advanced_buttons1 : "bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,fontselect,fontsizeselect,forecolor",
        extended_valid_elements : "font[face|size|color]",
        theme_advanced_disable : "anchor,code,visualaid"
    });
</script>
<!-- /tinyMCE -->
</head>
<body bgcolor="<?=$bgcolor?>">
<FORM action=edittopic1.php method=post name=y onsubmit="return check1form('All fields are required...')">
  <INPUT name=sfid type=hidden value="<?=$_GET['sfid']?>">
  <INPUT name=fid type=hidden value="<?=$_GET['fid1']?>">
  <TABLE align=center border=0 cellPadding=0 cellSpacing=0 width=550>
    <TBODY>
      <TR>
        <TD class=q><TABLE border=0 cellPadding=5 cellSpacing=1 width="100%">
            <TBODY>
              <TR class=c>
                <TD><TABLE cellPadding=1 cellSpacing=0 width="100%">
                    <TBODY>
                      <TR>
                        <TD noWrap><SPAN class=c>Add Post</SPAN></TD>
                        <TD align=right noWrap><B><A href="user.php" style="COLOR: #ffffff">Cancel</A></B></TD>
                      </TR>
                    </TBODY>
                  </TABLE></TD>
              </TR>
              <TR class=a>
                <TD><TABLE cellSpacing=8>
                    <TBODY>
                      <TR>
                        <TD align=right noWrap width=90><B>Subject:</B></TD>
                        <TD width=320><INPUT class=ia maxLength=500 name=subject size=70 value="<?=htmlspecialchars($row['subject'])?>" ></TD>
                      </TR>
                      <TR>
                        <TD align=right noWrap width=90><B>Name <IMG alt="" hspace=2 name=qq src="pics/user1.gif">:</B></TD>
                        <TD><INPUT class=ia  name=authorname value="<?=htmlspecialchars($row['authorname'])?>" size=30 ></TD>
                      </TR>
                      <TR>
                        <TD><b>Group&nbsp;Name : </b></TD>
                        <TD><input type=text size=30 name=groupname value="<?=htmlspecialchars($row["groupname"])?>" >
                        </TD>
                      </TR>
                      <TR>
                        <TD align=right noWrap width=90><B>Sticky Topic:</B></TD>
                        <TD width=320><INPUT class=ia type="checkbox" name=sticky value=1<?=(($row["sticky"]) ? " checked" : "");

        ?>></TD>
                      </TR>
                      <TR>
                        <TD align=right colSpan=2><TEXTAREA class=ia cols=30 name=detail rows=15><?=$row['detail']?>
</TEXTAREA>
                      <TR>
                        <TD colSpan=2><TABLE cellPadding=0 cellSpacing=0 width="100%">
                            <TBODY>
                              <TR>
                                <TD></TD>
                                <td align="center"><INPUT class=ib type=submit value=Submit name=sbm></TD>
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
} else {
    echo "<center>Sorry&nbsp;with&nbsp;out&nbsp;login&nbsp;you&nbsp;cann't&nbsp;access&nbsp;this&nbsp;page</center>";
} 

ob_end_flush();

?>
