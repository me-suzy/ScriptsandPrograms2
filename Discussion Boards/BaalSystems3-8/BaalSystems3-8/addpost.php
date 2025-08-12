<?php 
// CopyRight 2004 BaalSystems Free Software, see http://baalsystems.com for details, you may NOT redistribute or resell this free software.
include("common.php");
if (session_is_registered("whossession")) {
    if (($_SESSION['who']) == "admin" || $_SESSION['who'] == "moderator") {
        if (!empty($_POST['title'])) {
            $forumid = $_POST['forumid'];
            $result2 = db_query("select totalpost from {$tableprefix}tblforum where forumid=" . intval($forumid));
            while ($row = db_fetch_row($result2)) {
                $totalpost = $row["totalpost"] + 1;
            } 
			$result9 = db_query("select * from {$tableprefix}tblforum where forumid='" . $forumid . "'");
			$row9 = db_fetch_row($result9);	
			$forumsub = $row9['subject'];
            $result3 = db_query("update {$tableprefix}tblforum set totalpost=" . intval($totalpost) . ",lastpost=SYSDATE() where forumid=" . intval($forumid));
            $subject = $_POST['title'];
            $groupname = $_POST['groupname'];
            $author = $_POST['name'];

            $detail = "";

            $detail .= $_POST['detail'];

            $sticky = 0;
            if (isset($_POST['sticky']))
                $sticky = $_POST['sticky'];

            $result = db_query("insert into {$tableprefix}tblsubforum(forumid,subject,groupname,authorname,detail,dateposted,sticky) values('" . intval($forumid) . "','" . $subject . "','" . $groupname . "','" . $author . "','" . $detail . "',SYSDATE(),'" . intval($sticky) . "')");
            header("location:subtopic.php?fid=" . $forumid);
			$result4 = db_query("select mail from {$tableprefix}tbluser where notifypost='y'");
			if (mysql_num_rows($result4) > 0) {
				while ($email_arr = mysql_fetch_array($result4)) {
					$to = $email_arr[0];
					$body = $detail;
					$body .= "<br><br><font color='#CC0000'>You can view this thread by loging on <a href='" . $url . "'>Here</a>. It is in the Forum subject: " . $forumsub . ", the topic is: " . $subject . " and it was written by: " . $author . ".</font>";
					$headers = 'From: webmaster@' . $_SERVER['SERVER_NAME'] . "\r\n" . 
					'Content-type: text/html'; 
					$mailsend = mail($to, $emailsub, $body, $headers);
				}
			}
        } else {
            $fid = $_GET['fid'];

            $result = db_query("select groupname from {$tableprefix}tblforum where forumid=" . intval($fid));
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
<FORM action=addpost.php method=post name=y
onsubmit="return check1form('All fields are required...')">
  <INPUT name=forumid type=hidden value="<?php echo($fid);

            ?>">
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
                        <TD align=right noWrap><B><A href="<?php if ($_SESSION['who'] == 'admin') {
                echo "admin";
            } else {
                echo "mod";
            } 

            ?>.php"
style="COLOR: #ffffff">Cancel</A></B></TD>
                      </TR>
                    </TBODY>
                  </TABLE></TD>
              </TR>
              <TR class=a>
                <TD><TABLE cellSpacing=8>
                    <TBODY>
                      <TR>
                        <TD align=right noWrap width=90><B>Subject:</B></TD>
                        <TD width=320><INPUT class=ia maxLength=500 name=title size=70></TD>
                      </TR>
                      <TR>
                        <TD align=right noWrap width=90><B>Name <IMG alt="" hspace=2 name=qq src="pics/user1.gif">:</B></TD>
                        <TD><INPUT class=ia readonly name=name value="<?php echo ($_SESSION['username']);

            ?>" size=30 ></TD>
                      </TR>
                      <TR>
                        <TD><b>Group&nbsp;Name : </b></TD>
                        <TD><?php while ($row = db_fetch_row($result)) {
                echo"<input type='text' size='30' readonly name='groupname' value='" . $row["groupname"] . "'>";
            } 

            ?>
                        </TD>
                      </TR>
                      <TR>
                        <TD align=right noWrap width=90><B>Sticky Topic:</B></TD>
                        <TD width=320><INPUT class=ia type="checkbox" name=sticky value=1></TD>
                      </TR>
                      <TR>
                        <TD align=right colSpan=2><TEXTAREA class=ia cols=30 name=detail rows=15></TEXTAREA>
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
} 
ob_end_flush();

?>
