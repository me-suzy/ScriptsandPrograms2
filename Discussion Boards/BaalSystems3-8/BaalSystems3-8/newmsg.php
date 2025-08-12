<?php
include("common.php");
if (session_is_registered("whossession")) {
    if (($_SESSION['who']) == "user" || $_SESSION['who'] == "moderator" || $_SESSION['who'] == "admin") {
        if (!empty($_POST['title'])) {
            $result1 = db_query("select max(forumid) as forumid from {$tableprefix}tblforum ");
            while ($row = db_fetch_row($result1)) {
                $forumid = $row["forumid"] + 1;
            } 
			$result9 = db_query("select * from {$tableprefix}tblforum where forumid='" . $forumid . "'");
			$row9 = db_fetch_row($result9);	
			$forumsub = $row9['subject'];
            $subject = $_POST['title'];
            $to = $_POST['msgto'];
            $author = $_POST['name'];

            $details = "";

            $details .= $_POST['detail'];

            $details = eregi_replace("<?php" , "", $details);

            $query = "SELECT userid, COUNT(*) as count1 FROM {$tableprefix}tbluser WHERE username=\"{$to}\" GROUP BY userid;";
            $result = db_query($query); 
            // echo $query;
            // echo $result['count1'] . "<br>";
            // echo $result['userid'];
            $row = db_fetch_array($result); 
            // echo $row['count1];
            if ($row['count1'] == 0) {
                echo "<center>Sorry, User not found, please try again.</center>";
            } else {
                $msgtoid = $row["userid"]; 
                // echo $detail;
                $query = "insert into {$tableprefix}msgs (subject,fromid,toid,detail,dateposted) values(\"{$subject}\",\"{$_SESSION['usernum']}\",\"{$msgtoid}\", '{$details}' , NOW())"; 
                // echo $query;
                $result = db_query($query);
				$touser = $_GET['to'];
				$query2 = db_query("select * from {$tableprefix}tbluser where username='" . $to . "'");
				$row2 = db_fetch_row($query2);
				$email = $row2['mail'];
				$body = $details;
				$body .= "<br><br><font color='#CC0000'>You can view this message by loging on <a href='" . $url . "'>Here</a>. Click on the link titles Click Here on the top of your home page. It was written by: " . $author . ".</font>";
				$headers = 'From: webmaster@' . $_SERVER['SERVER_NAME'] . "\r\n" .
   'X-Mailer: PHP/' . phpversion() . "\r\n";
  				$headers .= 'Content-type: text/html'; 
				if ($row2['notifymsg'] == "y") {
					$mailsent = mail($email, $emailsub, $body, $headers);
				}
                if ($_SESSION['who'] == "moderator")
                    header('location:mod.php');
                else if ($_SESSION['who'] == "user")
                    header('location:user.php');
                else
                    header('location:admin.php');
            } 
        } else {
            $result = db_query("select * from {$tableprefix}tblgroup");

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
<FORM action=newmsg.php method=post name=y
onsubmit="return check1form('All fields are required...')">
  <TABLE align=center border=0 cellPadding=0 cellSpacing=0 width=550>
    <TBODY>
    
    <TR>
      <TD class=q><TABLE border=0 cellPadding=5 cellSpacing=1 width="100%">
          <TBODY>
          
          <TR class=c>
            <TD><TABLE cellPadding=1 cellSpacing=0 width="100%">
                <TBODY>
                  <TR>
                    <TD noWrap><SPAN class=c>New Message</SPAN></TD>
                    <TD align=right noWrap><B><A href="<?php if ($_SESSION['who'] == 'user') {
                echo "user";
            } else if ($_SESSION['who'] == "moderator") {
                echo "mod";
            } else {
                echo "admin";
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
                <td align="right" noWrap width="90"><b>To:</b></td>
                  <td width="320"><input class="ia" maxlength="50" name="msgto" size="70" value="<?=$_GET['to']?>"></td>
                </tr>
                <TR>
                  <TD align=right noWrap width=90><B>Name <IMG alt="" hspace=2 name=qq src="pics/user1.gif">:</B></TD>
                  <TD width=320><INPUT class=ia maxLength=50 readonly name=name value="<?php echo($_SESSION['username']);

            ?>" size=30></TD>
                </TR>
                <TR>
                  <TD align=right colSpan=2><TEXTAREA class=ia cols=30 name=detail rows=15></TEXTAREA>
                <TR>
                  <TD colSpan=2><TABLE cellPadding=0 cellSpacing=0 width="100%">
                      <TBODY>
                        <TR>
                          <TD></TD>
                          <td align="center"><?php echo($email); ?><INPUT class=ib type=submit value=Submit></TD>
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
    } 
} else {
    echo "<center>Sorry&nbsp;with&nbsp;out&nbsp;login&nbsp;you&nbsp;cann't&nbsp;access&nbsp;this&nbsp;page</center>";
} 
ob_end_flush();

?>
