<?php
include("common.php");
if (session_is_registered("whossession")) {
    if (($_SESSION['who']) == "admin") {
        if (isset($_GET['userid'])) {
            $result = db_query("delete from {$tableprefix}tbluser where userid =" . intval($_GET['userid']) . "");
        } 
		if (isset($_GET['usernum'])) {
			$result2 = db_query("update {$tableprefix}tbluser set level='" . $_POST['levelu'] . "' where userid=" . intval($_GET['usernum']) . "");
		}
        // Used for paging
        if (!isset($_GET['page'])) $page = 1;
        else $page = intval($_GET['page']);
        $debut = ($page - 1) * $nb;
        $result1 = db_query("select count(*) as count1 from {$tableprefix}tbluser where userrole='user'");
        while ($row = db_fetch_row($result1)) {
            $total = $row["count1"];
        } 
        $result = db_query("select * from {$tableprefix}tbluser where userrole='user' OR userrole='moderator' order by username asc LIMIT " . intval($debut) . "," . intval($nb));
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
<table align="center" border="0" width="450">
  <tbody>
    <tr>
      <td><table align="right" border="0" width=100%>
          <tr>
            <td align="left">Total Numbers of Users Registered :<?php echo($total);

        ?></td>
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
                              <td noWrap><span class="c"><a href="userdetail.php" id="lnk" style="COLOR: #ffffff; TEXT-DECORATION: none" title="Click here to refresh the latest ..."> User Details</a></span></td>
                              <td align="right" noWrap><b><a href="admin.php" style="COLOR: #ffffff" title="Admin">Home </a></b></td>
                            </tr>
                          </tbody>
                        </table></td>
                    </tr>
                    <tr class="z">
                      <td class="f" noWrap width="30%">&nbsp;User&nbsp;Name&nbsp;</td>
                      <td class="f" noWrap width="30%">&nbsp;Email&nbsp;</td>
                      <td class="f" noWrap width="30%">&nbsp;User Role </td>
					  <td class="f" nowrap width="30%">&nbsp;User Level</td>
                      <td class="f" noWrap width="10%">&nbsp;Action&nbsp;</td>
                    </tr>
                    <?php
        $i = 0;
        $bgrow = 0;
        while ($row = db_fetch_row($result)) {
            if (is_integer($i / 2)) {
                $bgrow = 1;

                ?>
                    <tr class="a">
                      <td><b><img alt border="0" hspace="2" src="pics/user1.gif" width="11" height="14"><?php echo($row["username"]);

                ?></b></a> </td>
                      <td noWrap><a href="mailto:<?php echo($row["mail"]); ?>"><?php echo($row["mail"]);

                ?></a></td>
                      <td noWrap><?php echo($row["userrole"]);

                ?></td>
                      <td noWrap>Current Level: <?php echo($row["level"]);

                ?><br><?php $levelu = $_POST['levelu']; ?>
			<FORM name="ulevel" ACTION="userdetail.php?usernum=<?php echo($row["userid"]); ?>&levelu=<?php echo($levelu); ?>" METHOD=POST>
			<select name="levelu">
			<OPTION VALUE="">Change user level to...
			<OPTION VALUE="New User">New User</option>
			<OPTION VALUE="Experienced User">Experienced User</option>
			<OPTION VALUE="Expert User">Expert User</option>
			<OPTION VALUE="Professional">Professional</option>
			<OPTION VALUE="Master">Master</option>
			</SELECT><input type="submit" value="Change"></form>
	</td>
                      <td class="s" noWrap><a href="userdetail.php?userid=<?php echo($row["userid"]);

                ?>" onfocus="blur();">delete</a></td>
                    </tr>
                    <?php } else {
                $bgrow = 2;

                ?>
                    <tr class="b">
                      <td><b><img alt border="0" hspace="2" src="pics/user2.gif" width="11" height="14"><?php echo($row["username"]);

                ?></b></a> </td>
                      <td noWrap><a href="mailto:<?php echo($row["mail"]); ?>"><?php echo($row["mail"]);

                ?></a></td>
                      <td noWrap><?php echo($row["userrole"]);

                ?></td>
                                            <td noWrap>Current Level: <?php echo($row["level"]);

                ?><br>
			<FORM name="ulevel" ACTION="userdetail.php?usernum=<?php echo($row["userid"]); ?>&levelu=<?php echo($levelu); ?>" METHOD=POST>
			<select name="levelu">
			<OPTION VALUE="">Change user level to...
			<OPTION VALUE="New User">New User</option>
			<OPTION VALUE="Experienced User">Experienced User</option>
			<OPTION VALUE="Expert User">Expert User</option>
			<OPTION VALUE="Professional">Professional</option>
			<OPTION VALUE="Master">Master</option>
			</SELECT><input type="submit" value="Change"></form>
	</td>
                      <td class="s" noWrap><a href="userdetail.php?userid=<?php echo($row["userid"]);

                ?>" onfocus="blur();">delete</a></td>
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
                      <td class="f" >&nbsp;<a href="admin.php" title="Admin">Home </a></td>
                      <td></td>
					  <td></td>
                      <td align="right"><font color="#000000">Pages >></font></td>
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
                    </tr>
                  </tbody>
                </table></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
  </tbody>
</table>
</form>
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
