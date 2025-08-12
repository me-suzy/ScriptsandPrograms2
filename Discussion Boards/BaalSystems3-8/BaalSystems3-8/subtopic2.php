<?php
include("common.php");

$result2 = db_query("select views from {$tableprefix}tblforum where forumid=\"" . intval($_GET["fid"]) . "\"");
while ($row = db_fetch_row($result2)) {
    $views = $row["views"] + 1;
} 
$result3 = db_query("update {$tableprefix}tblforum set views=" . intval($views) . " where forumid=\"" . intval($_GET["fid"]) . "\"");
// Used for paging
if (!isset($_GET['page'])) $page = 1;
else $page = intval($_GET['page']);
$debut = ($page - 1) * $nb;
$fid = $_GET['fid'];
$result1 = db_query("select count(*) as count1 from {$tableprefix}tblsubforum where forumid=\"" . intval($_GET["fid"]) . "\"");
while ($row = db_fetch_row($result1)) {
    $total = $row["count1"];
} 


$result = db_query("select DATE_FORMAT(dateposted, '%m-%d-%y %H:%i') as dateposted,subforumid,forumid,groupname,subject,authorname,detail,sticky from {$tableprefix}tblsubforum where forumid=" . intval($fid) . " order by sticky desc, subforumid " . $sorting . " LIMIT " . intval($debut) . "," . intval($nb));

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
            <td width="50%" align="left">Total Numbers of Posts :<?php echo($total);

?></td>
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
                        <td noWrap><span class="c"><a href="index.php" id="lnk" style="COLOR: #ffffff; TEXT-DECORATION: none" title="Click here to refresh the latest topics...">Index</a></span></td>
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
                        <td align="right"><b><a href="addpost1.php?fid=<?php echo($fid);

?>" style="COLOR: #ffffff" title="Add Post">Add&nbsp;Post</a></b></td>
                      </tr>
                    </tbody>
                  </table></td>
              </tr>
              <tr class="z">
                <td class="f" noWrap width="10%">&nbsp;Author&nbsp;/&nbsp;Group&nbsp;<br>
&nbsp;Subject&nbsp;</td>
                <!--<td class="f" noWrap width="9%">&nbsp;Subject&nbsp;</td>-->
                <td class="f" noWrap width="75%">&nbsp;Post&nbsp;</td>
                <td class="f" noWrap width="15%">&nbsp;Date / Time&nbsp;</td>
				<td class="f" noWrap width="15%">&nbsp;Action&nbsp;</td>
              </tr>
              <?php
$bgrow = 0;
$i = 0;
while ($row = db_fetch_row($result)) {
    if (is_integer($i / 2)) {
        $bgrow = 1;
        if ($row["sticky"]) {
            $image = "<img align=\"left\" alt border=\"0\" src=\"pics/note.gif\" width=\"14\" height=\"14\">";
        } else {
            $image = "<img align=\"left\" alt border=\"0\" src=\"pics/t1.gif\" width=\"19\" height=\"24\">";
        } 

        ?>
              <tr class="a">
                <td><b><img alt border="0" hspace="2" src="pics/user1.gif" width="11" height="14"><?php echo($row["authorname"]);

        ?><br><?php $result4 = db_query("select * from {$tableprefix}tbluser where username='" . $row["authorname"] . "'"); 
		$row4 = db_fetch_row($result4);
		echo($row4["level"]); ?><br>
		<a href="userprofile.php?username=<?php print($row["authorname"]); ?>" title="Profile">Profile</a></b>
                  <div class="s">
                    <?=$image;

        ?>
                    <b><?php echo ($row["groupname"]);

        ?></b> </div>
                  <div class="s"><?php echo($row["subject"]);

        ?></div></td>
                <!--<td class="s"><?php echo($row["subject"]);

        ?></td>-->
                <td class="post"><?php echo($row["detail"]);

        ?></td>
                <td class="s"><?php echo($row["dateposted"]);

        ?></td>
		<td class="s">
				<a href="msgreply.php?sfid=<?php echo($row["subforumid"]);

                ?>&fid1=<?php echo($row["forumid"]);

                ?>&author=<?php echo($row["authorname"]); ?>" title="reply">reply</a></td>
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
                <td><b><img alt border="0" hspace="2" src="pics/user2.gif" width="11" height="14"><?php echo($row["authorname"]);

        ?><br><?php $result4 = db_query("select * from {$tableprefix}tbluser where username='" . $row["authorname"] . "'"); 
		$row4 = db_fetch_row($result4);
		echo($row4["level"]); ?><br><a href="userprofile.php?username=<?php print($row["authorname"]); ?>" title="Profile">Profile</a></b></a>
                  <div class="s">
                    <?=$image;

        ?>
                    <b><?php echo ($row["groupname"]);

        ?></b> </div>
                  <div class="s"><?php echo($row["subject"]);

        ?></div></td>
                <!--<td class="s"><?php echo($row["subject"]);

        ?></td>-->
                <td class="post"><?php echo($row["detail"]);

        ?></td>
                <td class="s"><?php echo($row["dateposted"]);

        ?></td>
		<td class="s">
				<a href="msgreply.php?sfid=<?php echo($row["subforumid"]);

                ?>&fid1=<?php echo($row["forumid"]);

                ?>&author=<?php echo($row["authorname"]); ?>" title="reply">reply</a></td>
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
                <td class="f">&nbsp;<a href="addpost1.php?fid=<?php echo($fid);

?>">Add Post</a></td>
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
				<td>&nbsp;</td>
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
<?php ob_end_flush();

?>
