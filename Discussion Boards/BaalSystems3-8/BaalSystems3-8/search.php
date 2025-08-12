<?php
include("common.php");
if (!empty($_POST['search'])) {
    $search = $_POST['search'];

    if (!isset($_GET['page'])) $page = 1;
    else $page = intval($_GET['page']);
    $debut = ($page - 1) * $nb;
    $result = db_query("select count(*) as count1 from {$tableprefix}tblforum where subject like '%" . $search . "%'");
    while ($row = db_fetch_row($result)) {
        $total = $row["count1"];
    } 
    // $result1 = db_query("select DATE_FORMAT(lastpost, '%m-%d-%y %H:%i') as lastpost,forumid,groupname,subject,authorname,detail,totalpost,views from {$tableprefix}tblforum where subject like '%" . $search . "%' order by lastpost asc LIMIT ". $debut .",". $nb);
    // {$tableprefix}tblforum frm , frm.subject like '%".$search."%'  OR
    // echo " SQL : SELECT *  FROM  {$tableprefix}tblsubforum subfrm where subfrm.subject like '%".$search."%'  OR subfrm.detail like '%".$search."%' order by subfrm.subforumid DESC LIMIT ". $debut .",". $nb."<br><br>";
    $result1 = db_query("SELECT *  FROM  {$tableprefix}tblsubforum where subject like '%" . $search . "%'  OR detail like '%" . $search . "%' order by subforumid DESC LIMIT " . intval($debut) . "," . intval($nb));

    $rows = db_num_rows($result1);

    /*   $resultsub = db_query("select DATE_FORMAT(lastpost, '%m-%d-%y %H:%i') as lastpost,forumid,groupname,subject,authorname,detail,totalpost,views from {$tableprefix}tblsubforum where subject like '%" . $search . "%' OR detail like '%" . $search . "%' order by lastpost asc LIMIT ". $debut .",". $nb);
   if (!$resultsub) {     die('Invalid query: ');   }
    $subrows = mysql_num_rows($resultsub);
    $subresult="";
    if ($subrows != 0){  $subresult="yes";
    }else{ $subresult= "no";   }
*/

    $searchresult = "";
    if ($rows != 0) {
        $searchresult = "yes";
    } else {
        $searchresult = "no";
    } 
} 

?>
<html>
<head>
<title>
<?=$title?>
</title>
<LINK href="incl/style2.css" rel=stylesheet>
<SCRIPT LANGUAGE="JavaScript1.2" SRC="incl/all.js" TYPE='text/javascript'></SCRIPT>
</head>
<body bgcolor="<?=$bgcolor?>">
<FORM action=search.php method=post name=y>
  <TABLE align=center border=0 cellPadding=0 cellSpacing=0 width=600>
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
            <TD noWrap align=right><SPAN class=c>Please Enter the data for Search &nbsp;:</SPAN></TD>
            <TD align=right noWrap><INPUT class=ia maxLength=25 name=search size=25></TD>
        </TD>
        
        </TR>
        </TBODY>
      </TABLE>
      </TD>
      </TR>
      </TBODY>
    </TABLE>
    </TD>
    </TR>
    
    <TR class=a>
    
    <TD>
    
    <TABLE cellSpacing=6 width="100%">
      <TBODY>
        <TR>
          <TD align=right colSpan=2><INPUT class=ib type=submit value=Submit></td>
        </tr>
        <?php if ($searchresult == "no") {

    ?>
        <tr>
          <td class=b colspan=2>No records found for the seach, try again</td>
        </tr>
        <?php } 

?>
      </TD>
      </TR>
      </TBODY>
    </TABLE>
    </TD>
    </TR>
    </TBODY>
  </TABLE>
  </TD>
  </TR>
  </TBODY>
  </TABLE>
</FORM>
<?php if ($searchresult == "yes") {

    ?>
<table align="center" border="0" width="600">
<tbody>
  <tr>
    <td></td>
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
                          <?php
    if (session_is_registered("whossession")) {
        if (($_SESSION['who']) == "user") {

            ?>
                          <td noWrap colspan=2><span class="c"><a href="user.php" id="lnk" style="COLOR: #ffffff; TEXT-DECORATION: none" title="Click here to refresh the latest topics...">Home</a></span></td>
                          <?php } elseif ($_SESSION['who'] == "admin") {

            ?>
                          <td noWrap colspan=2><span class="c"><a href="admin.php" id="lnk" style="COLOR: #ffffff; TEXT-DECORATION: none" title="Click here to refresh the latest topics...">Home</a></span></td>
                          <?php } 
    } else {

        ?>
                          <td noWrap colspan=2><span class="c"><a href="index.php" id="lnk" style="COLOR: #ffffff; TEXT-DECORATION: none" title="Click here to refresh the latest topics...">Home</a></span></td>
                          <?php } 

    ?>
                        </tr>
                      </tbody>
                    </table></td>
                </tr>
                <tr class="z">
                  <td class="f" noWrap width="10%">&nbsp;Subject&nbsp;</td>
                  <td class="f" noWrap width="10%">&nbsp;Group&nbsp;</td>
                  <td class="f" noWrap width="10%">&nbsp;Author&nbsp;</td>
                  <td class="f" noWrap width="60%">&nbsp;Posts&nbsp;</td>
                  <td class="f" noWrap width="10%">&nbsp;Date Posted&nbsp;</td>
                </tr>
                <?php
    $i = 0;
    $bgrow = 0;
    while ($row = db_fetch_row($result1)) {
        if (is_integer($i / 2)) {
            $bgrow = 1;

            ?>
                <tr class="a">
                  <td class="s" noWrap><?=$row["subject"]?></td>
                  <td class="s" noWrap><?=$row["groupname"]?></td>
                  <td noWrap><b><img alt border="0" hspace="2" src="pics/user1.gif" width="11" height="14">
                    <?=$row["authorname"]?>
                    </b></td>
                  <td class="s" noWrap><?=$row["detail"]?></td>
                  <td class="s"><?=$row["dateposted"]?></td>
                </tr>
                <?php } else {
            $bgrow = 2;

            ?>
                <tr class="b">
                  <td class="s" noWrap><?=$row["subject"]?></td>
                  <td class="s" noWrap><?=$row["groupname"]?></td>
                  <td noWrap><b><img alt border="0" hspace="2" src="pics/user1.gif" width="11" height="14">
                    <?=$row["authorname"]?>
                    </b></td>
                  <td class="s" noWrap><?=$row["detail"]?></td>
                  <td class="s"><?=$row["dateposted"]?></td>
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
                  <td class="f">&nbsp;</td>
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

    ?>
                          <td>&nbsp;</td>
                        </tr>
                      </tbody>
                    </table></td>
                  <td align="middle"></td>
                  <td align="middle" class="f"></td>
                </tr>
              </tbody>
            </table>
            <?php if ($time_show == y) {

        ?>
            <center>
              <div style="width: 600px; text-align: right; background-color: <?=$bgcolor;

        ?>"> Page generated in
                <?=getTimeElapsed();

        ?>
                second(s) </div>
            </center>
            <?php } 

    ?>
            <div align="center" style="background-color: <?=$bgcolor;

    ?>"><br>
              <?=$footer?>
            </div>
</body>
</html>
<?php } 
ob_end_flush();

?>
