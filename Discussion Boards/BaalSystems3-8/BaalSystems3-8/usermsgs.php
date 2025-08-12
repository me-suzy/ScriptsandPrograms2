<?php
include("common.php");
if (session_is_registered("whossession")) {
    if (($_SESSION['who']) == "user" || $_SESSION['who'] == "moderator" || $_SESSION['who'] == "admin") {
        // Used for paging
        if (!isset($_GET['page'])) $page = 1;
        else $page = intval($_GET['page']);
        $debut = ($page - 1) * $nb;
        $usernum = $_SESSION["usernum"];
        $gfsquery = "select count(*) as count1 from {$tableprefix}msgs WHERE toid=\"{$usernum}\";"; 
        // echo $gfsquery;
        $result1 = db_query($gfsquery);
        $row1 = db_fetch_array($result1);
        $total = $row1["count1"]; 
        // echo $total;
        $result = db_query("select DATE_FORMAT(dateposted, '%m-%d-%y %H:%i') as dateposted, msgsid, fromid, toid, subject, detail, didread, {$tableprefix}tbluser.username AS username from {$tableprefix}msgs INNER JOIN {$tableprefix}tbluser ON {$tableprefix}msgs.toid={$tableprefix}tbluser.userid WHERE toid=\"{$usernum}\" order by dateposted asc LIMIT " . intval($debut) . "," . intval($nb));

        if (isset($_GET['action'])) {
            $delquery = "DELETE FROM {$tableprefix}msgs WHERE msgsid='" . intval($_GET['mid']) . "';";
            db_query($delquery);
            header("location:usermsgs.php");
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
</head>
<body bgcolor="<?=$bgcolor?>">
<table align="center" border="0" width="600">
  <tbody>
    <tr>
      <td><table align="right" border="0" width=100%>
          <tr>
            <td align="left">Total Numbers of Msgs :<?php echo($total);

        ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td align="right" class="s"><li><a href="profile.php" style="COLOR: #ffffff" title="Profile">Profile</a></li></td>
            <td align="right" class="s"><li><a href="search.php" style="COLOR: #ffffff" title="SearchTopics">search</a></li></td>
            <td align="right" class="s"><li><a href="faq.php" style="COLOR: #ffffff" title="Help">FAQ</a></li></td>
            <td align="right" class="s"><li><a href="logout.php" style="COLOR: #ffffff" title="Logout"><b>LOGOUT</B></a></li></td>
          </tr>
		  <tr>
		              <td><b>Welcome <?php echo($_SESSION['username']);

        ?>, to your
              <?php if ($_SESSION['who'] == 'user') {
            echo "User";
        } else if ($_SESSION['who'] == "moderator") {
            echo "Moderator";
        } else {
            echo "Admin";
        } 

        ?>
              section</b></td>
			  </tr>
        </table></td>
    </tr>
    <tr>
      <td colSpan="2"><table border="0" cellPadding="0" cellSpacing="0" width="100%">
          <tbody>
            <tr>
              <td class="q"><table border="0" cellPadding="5" cellSpacing="1" width="100%">
                  <tbody>
                    <tr class="c">
                      <td colSpan="5"><table cellPadding="2" cellSpacing="0" width="100%">
                          <tbody>
                            <tr>
                              <td noWrap><span class="c"><a href="<?php if ($_SESSION['who'] == 'user') {
            echo "user";
        } else if ($_SESSION['who'] == "moderator") {
            echo "mod";
        } else {
            echo "admin";
        } 

        ?>.php" id="lnk" style="COLOR: #ffffff; TEXT-DECORATION: none" title="Click here to refresh the latest topics..."> Index</a></span></td>
                              <td align="right" noWrap width=70%><form action="search.php" method="POST" style="margin: 0px; padding: 0px; text-align: right">
                                  <table cellpadding=0 cellspacing=0>
                                    <tr>
                                      <td><INPUT type="text" maxLength=25 name=search size=25 class="searchtxt">&nbsp;</td>
                                      <td><input type="submit" title="Search" value="Search" class="searchbut">
                                      </td>
                                    </tr>
                                  </table>
                                </form></td>
                              <td align="right" noWrap><b><a href="newmsg.php" style="COLOR: #ffffff">New Message</a></b></td>
                            </tr>
                          </tbody>
                        </table></td>
                    </tr>
                    <tr class="z">
                      <td class="f" noWrap width="70%">&nbsp;Subject&nbsp;</td>
                      <td class="f" noWrap width="10%">&nbsp;From&nbsp;</td>
                      <td class="f" noWrap width="10%">&nbsp;Sent&nbsp;</td>
                      <td></td>
                    </tr>
                    <?php
        $i = 0;
        $bgrow = 0;
        while ($row = db_fetch_row($result)) {
            if (is_integer($i / 2)) {
                $bgrow = 1;

                $frquery = "SELECT username FROM {$tableprefix}tbluser WHERE userid=\"" . intval($row["fromid"]) . "\";";
                $fresult = db_query($frquery);
                $frow = db_fetch_array($fresult);

                ?>
                    <tr class="a">
                      <td><a class="v" onfocus="blur();" href="viewmsg.php?mid=<?php echo($row["msgsid"]);

                ?>"><img align="left" alt border="0" src="pics/t1.gif" width="19" height="24"></a>
                        <div class="s"> <a class="v" onfocus="blur();" href="viewmsg.php?mid=<?php echo($row["msgsid"]);

                ?>">
                          <?php
                if ($row["didread"] == n) {
                    echo "<b>" . $row["subject"] . "</b>";
                } else
                    echo $row["subject"];

                ?>
                          </a> </div></td>
                      <td noWrap><b><img alt border="0" hspace="2" src="pics/user1.gif" width="11" height="14"><?php echo($frow["username"]);

                ?></b></td>
                      <td class="s" noWrap><?php echo($row["dateposted"]);

                ?></td>
                      <td class="s" noWrap><a href="usermsgs.php?action=delete&mid=<?php echo($row["msgsid"]);

                ?>">Delete</a></td>
                    </tr>
                    <?php } else {
                $bgrow = 2;

                ?>
                    <tr class="b">
                      <td><a class="v" onfocus="blur();" href="viewmsg.php?mid=<?php echo($row["msgsid"]);

                ?>"><img align="left" alt border="0" src="pics/t2.gif" width="19" height="24"></a>
                        <div class="s"> <a class="v" onfocus="blur();" href="viewmsg.php?mid=<?php echo($row["msgsid"]);

                ?>">
                          <?php
                if ($row["didread"] == n)
                    echo "<b>" . $row["subject"] . "</b>";
                else
                    echo $row["subject"];

                ?>
                          </a> </div></td>
                      <td noWrap><b><img alt border="0" hspace="2" src="pics/user2.gif" width="11" height="14"><?php echo($frow["username"]);

                ?></b></td>
                      <td class="s" noWrap><?php echo($row["dateposted"]);

                ?></td>
                      <td class="s" noWrap><a href="usermsgs.php?action=delete&mid=<?php echo($row["msgsid"]);

                ?>">Delete</a></td>
                    </tr>
                    <?php } 
            $i = $i + 1;
        } 
        if ($bgrow == 1) {

            ?>
                    <tr class="b">
                      <?php } elseif ($bgrow == 2) {

            ?>
                    <tr class="a">
                      <?php } else {

            ?>
                    <tr class="b">
                      <?php } 

        ?>
                      <td class="f">&nbsp;<a href="newmsg.php">New Message</a></td>
                      <td align="middle" noWrap>Pages >></td>
                      <td align="middle"><table cellPadding="0" cellSpacing="0" width="100%">
                          <tbody>
                            <tr>
                              <?php
        $nbpages = ceil($total / $nb);
        for($i = 1;$i <= $nbpages;$i ++) {
            if ($i > 1) {
                $dis = $dis1 + 1;
            } else {
                $dis = 1;
            } 
            $dis1 = $nb * $i;
            if ($dis1 >= $total) {
                $dis1 = ($dis1 - ($dis1 - $total));
            } 

            echo '<a href="' . $_SERVER['PHP_SELF'] . '?page=' . $i . '&total=' . $total . '" title="topics' . $dis . '-' . $dis1 . '"> ' . $i . '</a>';
            if ($i < $nbpages) echo ' - ';
        } 
        // CopyRight 2004 BaalSystems Free Software, see http://baalsystems.com for details, you may NOT redistribute or resell this free software.
        ?>
                            </tr>
                          </tbody>
                        </table></td>
                      <td></td>
                    </tr>
                  </tbody>
                </table></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
  </tbody>
</table>
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
</body>
</html>
<?php } else {
        echo "<center>Sorry&nbsp;with&nbsp;out&nbsp;login&nbsp;you&nbsp;cann't&nbsp;access&nbsp;this&nbsp;page</center>";
    } 
} 
ob_end_flush();

?>
