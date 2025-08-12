<?php
include("common.php");
if (session_is_registered("whossession")) {
    if (($_SESSION['who']) == "moderator") {
        $sort = $_GET["sort"];
        $order = $_GET["order"];

        $sort_modes = array("lp" => "lastpost",
            "sj" => "subject",
            "au" => "authorname",
            "tp" => "totalpost",
            "vs" => "views"
            );

        $order_modes = array("de" => "desc",
            "as" => "asc"
            );

        $sort_str = "";
        if ($sort && $sort_modes[$sort]) {
            $sort_str = ", " . $sort_modes[$sort];
            if ($order && $order_modes[$order]) $sort_str .= " " . $order_modes[$order];
            if ($order == "as") {
                $sort_img = "<img src=\"pics/arr_down_s.gif\">";
            } 
            if ($order == "de") {
                $sort_img = "<img src=\"pics/arr_up_s.gif\">";
            } 
        } 
        // Used for paging
        if (!isset($_GET['page'])) $page = 1;
        else $page = intval($_GET['page']);
        $debut = ($page - 1) * $nb;
        $result1 = db_query("select count(*) as count1 from {$tableprefix}tblforum ");
        while ($row = db_fetch_row($result1)) {
            $total = $row["count1"];
        } 
        $result = db_query("select count(*) from {$tableprefix}tblforum where position <= " . intval($debut));
        $row = db_fetch_array($result);
        $correction = $row[0];

        $result = db_query("select DATE_FORMAT(lastpost, '%m-%d-%y %H:%i') as lastpost,forumid,groupname,subject,authorname,detail,totalpost,views,sticky,position from {$tableprefix}tblforum where ISNULL(position) order by sticky desc" . $sort_str . ", lastpost desc LIMIT " . intval($debut - $correction) . "," . intval($nb));

        $result1 = db_query("select DATE_FORMAT(lastpost, '%m-%d-%y %H:%i') as lastpost,forumid,groupname,subject,authorname,detail,totalpost,views,sticky,position from {$tableprefix}tblforum where position between " . intval($debut + 1) . " and " . intval($debut + $nb));

        $unposes = array();
        while ($row = db_fetch_row($result)) {
            $unposes[] = $row;
        } 

        $poses = array();
        while ($row = db_fetch_row($result1)) {
            $poses[$row["position"]] = $row;
        } 
        $keys = array_keys($poses);
        sort($keys);

        for($i = 0; $i < count($keys); $i++) {
            $key = $keys[$i];
            $value = $poses[$keys[$i]];
            $tmp = array_slice($unposes, 0, $key-1);
            array_push($tmp, $value);
            $tmp = array_merge($tmp, array_slice($unposes, $key-1));
            $unposes = $tmp;
        } 

        $unposes = array_slice($unposes, 0, $nb);

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
<table align="center" border="0" width="690">
  <tbody>
    <tr>
      <td><table align="right" border="0" width=100%>
          <tr>
            <td align="left">Total Numbers of Posts :<?php echo($total);

        ?></td>
            <td align="left" class="s"><b>Welcome <?php echo($_SESSION['username']); ?>, to your moderator section</b></td>
            <td align="right" class="s"><li><a href="search.php" style="COLOR: #ffffff" title="SearchTopics">search</a></li></td>
            <td align="right" class="s"><li><a href="faq.php" style="COLOR: #ffffff" title="Help">FAQ</a></li></td>
            <td align="right" class="s"><li><a href="logout.php" style="COLOR: #ffffff" title="Logout"><b>LOGOUT</B></a></li></td>
            <td align="right" class="s"><li><a href="profile.php" style="COLOR: #ffffff" title="profile"><b>PROFILE</B></a></li></td>
          </tr>
        </table></td>
    </tr>
    <?php
        $usernum = $_SESSION['usernum'];
        $msgquery = "SELECT COUNT(*) AS row_count FROM {$tableprefix}msgs where {$tableprefix}msgs.toid=\"{$usernum}\" AND {$tableprefix}msgs.didread=\"n\"";
        $msgresult = db_query($msgquery);
        $msgrow = db_fetch_array($msgresult);
        if ($msgsystem == y) {

            ?>
    <tr>
      <td><b>You have <?php echo $msgrow["row_count"] ?> unread msgs. <a href="usermsgs.php">Click here </a> to read your msgs.
      </b><td>
    </tr>
    <?php } 

        ?>
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
        } else {
            echo "mod";
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
                              <td align="right" noWrap><b><a href="newtopic1.php" style="COLOR: #ffffff">New Topic</a></b></td>
                            </tr>
                          </tbody>
                        </table></td>
                    </tr>
                    <tr class="z">
                      <td class="f" noWrap width="70%">&nbsp;<a style="color: #ffffff; font-size: 10px; text-decoration: none" href="mod.php?sort=sj&order=<?=(($sort == "sj" && $order == "as") ? "de" : "as");

        ?>">Topics</a>&nbsp;
                        <?=(($sort == "sj") ? $sort_img : "");

        ?>
&nbsp;</td>
                      <td class="f" noWrap width="10%">&nbsp;<a style="color: #ffffff; font-size: 10px; text-decoration: none" href="mod.php?sort=au&order=<?=(($sort == "au" && $order == "as") ? "de" : "as");

        ?>">Author</a>&nbsp;
                        <?=(($sort == "au") ? $sort_img : "");

        ?>
&nbsp;</td>
                      <td class="f" noWrap width="10%">&nbsp;<a style="color: #ffffff; font-size: 10px; text-decoration: none" href="mod.php?sort=lp&order=<?=(($sort == "lp" && $order == "as") ? "de" : "as");

        ?>">Last Post</a>&nbsp;
                        <?=(($sort == "lp") ? $sort_img : "");

        ?>
&nbsp;</td>
                      <td class="f" noWrap width="5%">&nbsp;<a style="color: #ffffff; font-size: 10px; text-decoration: none" href="mod.php?sort=tp&order=<?=(($sort == "tp" && $order == "as") ? "de" : "as");

        ?>">Posts</a>&nbsp;
                        <?=(($sort == "tp") ? $sort_img : "");

        ?>
&nbsp;</td>
                      <td class="f" noWrap width="5%">&nbsp;<a style="color: #ffffff; font-size: 10px; text-decoration: none" href="mod.php?sort=vs&order=<?=(($sort == "vs" && $order == "as") ? "de" : "as");

        ?>">Views</a>&nbsp;
                        <?=(($sort == "vs") ? $sort_img : "");

        ?>
&nbsp;</td>
                    </tr>
                    <?php
        $i = 0;
        $bgrow = 0;
        foreach ($unposes as $key => $row) {
            if (is_integer($i / 2)) {
                $bgrow = 1;
                if ($row["sticky"]) {
                    $image = "<img align=\"left\" alt border=\"0\" src=\"pics/note.gif\" width=\"14\" height=\"14\">";
                } else {
                    $image = "<img align=\"left\" alt border=\"0\" src=\"pics/t1.gif\" width=\"19\" height=\"24\">";
                } 

                ?>
                    <tr class="a">
                      <td><a class="v" onfocus="blur();" href="subtopic.php?fid=<?php echo($row["forumid"]);

                ?>">
                        <?=$image;

                ?>
                        <b><?php echo($row["groupname"]);

                ?></b></a>
                        <div class="s"> <a class="v" onfocus="blur();" href="subtopic.php?fid=<?php echo($row["forumid"]);

                ?>"> <?php echo ($row["subject"]);

                ?> </a> </div></td>
                      <td noWrap><b><img alt border="0" hspace="2" src="pics/user1.gif" width="11" height="14"><?php echo($row["authorname"]);

                ?></b></td>
                      <td class="s" noWrap><?php echo($row["lastpost"]);

                ?></td>
                      <td class="s"><?php echo $row["totalpost"] ?></td>
                      <td class="s"><?php echo $row["views"]?></td>
                    </tr>
                    <?php } else {
                $bgrow = 2;
                if ($row["sticky"]) {
                    $image = "<img align=\"left\" alt border=\"0\" src=\"pics/note.gif\" width=\"14\" height=\"14\">";
                } else {
                    $image = "<img align=\"left\" alt border=\"0\" src=\"pics/t2.gif\" width=\"19\" height=\"24\">";
                } 

                ?>
                    <tr class="b">
                      <td><a class="v" onfocus="blur();" href="subtopic.php?fid=<?php echo($row["forumid"]);

                ?>">
                        <?=$image;

                ?>
                        <b><?php echo($row["groupname"]);

                ?></b></a>
                        <div class="s"> <a class="v" onfocus="blur();" href="subtopic.php?fid=<?php echo($row["forumid"]);

                ?>"> <?php echo ($row["subject"]);

                ?> </a> </div></td>
                      <td noWrap><b><img alt border="0" hspace="2" src="pics/user2.gif" width="11" height="14"><?php echo($row["authorname"]);

                ?></b></td>
                      <td class="s" noWrap><?php echo($row["lastpost"]);

                ?></td>
                      <td class="s"><?php echo($row["totalpost"]);

                ?></td>
                      <td class="s"><?php echo($row["views"]);

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
                      <?php } else {

            ?>
                    <tr class="b">
                      <?php } 

        ?>
                      <td class="f">&nbsp;<a href="newtopic1.php">New Topic</a></td>
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

            echo '<a href="' . $_SERVER['PHP_SELF'] . '?page=' . $i . '&total=' . $total . (($sort) ? "&sort=$sort" : "") . (($order) ? "&order=$order" : "") . '" title="topics' . $dis . '-' . $dis1 . '"> ' . $i . '</a>';
            if ($i < $nbpages) echo ' - ';
        } 
        // CopyRight 2004 BaalSystems Free Software, see http://baalsystems.com for details, you may NOT redistribute or resell this free software.
        ?>
                              <td>&nbsp;</td>
                            </tr>
                          </tbody>
                        </table></td>
                      <td align="middle"></td>
                      <td align="middle" class="f"></td>
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
