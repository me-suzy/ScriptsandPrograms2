<?php
include("common.php");
if (session_is_registered("whossession")) {
    if (($_SESSION['who']) == "user" || $_SESSION['who'] == "moderator" || $_SESSION['who'] == "admin") {
        $query = "update {$tableprefix}msgs set didread=\"y\" where msgsid=\"{$_GET["mid"]}\";";
        $result3 = db_query($query);
        // Used for paging
        // $lastpost =$LastModified;
        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = intval($_GET['page']);
        } 

        $debut = ($page - 1) * $nb;
        $mid = $_GET['mid'];
    } 
    $query = "select DATE_FORMAT(dateposted, '%m-%d-%y %H:%i') as dateposted, msgsid, fromid, toid, subject, detail, didread, {$tableprefix}tbluser.username AS username from {$tableprefix}msgs INNER JOIN {$tableprefix}tbluser ON {$tableprefix}msgs.fromid={$tableprefix}tbluser.userid WHERE msgsid=\"" . intval($_GET["mid"]) . "\" order by dateposted asc LIMIT " . intval($debut) . " , " . intval($nb) . ";";
    $result = db_query($query); 
    // echo $query;
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
    <td><div>
        <table border="0">
          <tr>
            <td align="middle" class="s"><b>
              <?php if ($_SESSION['who'] == 'user') {
        echo "User";
    } else {
        echo "Moderator";
    } 

    ?>
              Section</b></td>
          </tr>
        </table>
      </div></td>
  </tr>
  <tr>
    <td colSpan="2"><table border="0" cellPadding="0" cellSpacing="0" width="100%">
        <tbody>
        
        <tr>
          <td class="q"><table border="0" cellPadding="5" cellSpacing="1" width="100%">
              <tbody>
              
              <tr class="c">
                <td colSpan="6"><table cellPadding="2" cellSpacing="0" width="100%">
                    <tbody>
                      <tr>
                        <td noWrap><span class="c"><a href="<?php if ($_SESSION['who'] == 'user') {
        echo "user";
    } else if ($_SESSION['who'] == "moderator") {
        echo "mod";
    } else {
        echo "admin";
    } 

    ?>.php" id="lnk" style="COLOR: #ffffff; TEXT-DECORATION: none" title="Click here to refresh the latest topics...">Index</a></span></td>
                        <td align="right"><b><a href="javascript:history.back();" style="COLOR: #ffffff" title="back">BACK</a></b></td>
                        <td align="right" noWrap width=50%><form action="search.php" method="POST" style="margin: 0px; padding: 0px; text-align: right">
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
                <td class="f" noWrap width="10%">&nbsp;From&nbsp;<br>
&nbsp;Subject&nbsp;</td>
                <!--<td class="f" noWrap width="9%">&nbsp;Subject&nbsp;</td>-->
                <td class="f" noWrap width="75%">&nbsp;Post&nbsp;</td>
                <td class="f" noWrap width="15%">&nbsp;Date / Time&nbsp;</td>
              </tr>
              <?php
    $bgrow = 0;
    $i = 0;
    while ($row = db_fetch_row($result)) {
        if (is_integer($i / 2)) {
            $bgrow = 1;

            ?>
              <tr class="a">
                <td><b><img alt border="0" hspace="2" src="pics/user1.gif" width="11" height="14"><?php echo($row["username"]);

            ?></b> <br />
                  <?php echo $row["subject"];

            ?> </td>
                <!--<td class="s"><?php echo($row["subject"]);

            ?></td>-->
                <td class="post"><?php echo($row["detail"]);

            ?></td>
                <td class="s"><?php echo($row["dateposted"]);

            ?></td>
              </tr>
              <?php } else {
            $bgrow = 2;

            ?>
              <tr class="b">
                <td><b><img alt border="0" hspace="2" src="pics/user2.gif" width="11" height="14"><?php echo($row["username"]);

            ?></b></a>
                  <div class="s"><?php echo($row["subject"]);

            ?></div></td>
                <!--<td class="s"><?php echo($row["subject"]);

            ?></td>-->
                <td class="post"><?php echo($row["detail"]);

            ?></td>
                <td class="s"><?php echo($row["dateposted"]);

            ?></td>
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
                <?php } 

    ?>
                <td align="right" noWrap><b><a href="newmsg.php" style="COLOR: #ffffff">New Message</a></b></td>
                <td align="middle"><table cellPadding="0" cellSpacing="0" width="100%">
                    <tbody>
                      <tr>Pages >>
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

        echo '<a href="' . $_SERVER['PHP_SELF'] . '?page=' . $i . '&fid=' . $fid . '&total=' . $total . '" title="topics' . $dis . '-' . $dis1 . '"> ' . $i . '</a>';
        if ($i < $nbpages) echo ' - ';
    } 

    ?>
                      </tr>
                    <td>&nbsp;</td>
                    </tbody>
                    
                  </table></td>
                <!--<td align="left"></td>-->
                <!--<td align="middle" class="f"></td>-->
                <td >&nbsp;</td>
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
ob_end_flush();

?>
