<?php
include("common.php");
if (session_is_registered("whossession")) {
    if (($_SESSION['who']) == "admin") {
        // echo($dbname);
        if (!empty($_POST['submit'])) {
            $msgs = $_POST['msgs'];
            $pst = $_POST['pst'];
            $sort = $_POST['sort'];
            $gen_time = $_POST['gen_time'];
            $nb = $_POST["nb"];
			$emailsub = $_POST["emailsub"];
			$url = $_POST["url"];

            $filename = "incl/prefs.php";
            if (!$datafile = @fopen($filename, 'w')) {
                echo "Cannot open file $filename";
                exit;
            } else {
                $strcontent = "<?php \$msgsystem='{$msgs}'; \$posting='{$pst}';  \$sorting='{$sort}'; \$time_show='{$gen_time}'; \$nb='{$nb}'; \$emailsub='{$emailsub}'; \$url='{$url}';?>";
                fwrite($datafile, $strcontent , strlen($strcontent));
                fclose($datafile);
            } 
            header("location:admin.php");
        } 

        ?>
<html>
<head>
<title>Baal Smart Form</title>
<LINK href="incl/style2.css" rel=stylesheet>
</head>
<body bgcolor="<?=$bgcolor?>">
<FORM action=pref.php method=post name=y>
  <TABLE align=center border=0 cellPadding=0 cellSpacing=0 width=550>
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
            <TD noWrap><SPAN class=c>Admin Preferences</SPAN></TD>
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
              <TR>
                <TD align=right width=200><B>Messaging System : </B></TD>
                <TD align=left width=50><select class=ia name=msgs>
                    <option value="y" <?php if ($msgsystem == y) echo("selected");

        ?>>On</option>
                    <option value="n" <?php if ($msgsystem == n) echo("selected");

        ?>>Off</option>
                  </select>
                </td>
              </TR>
              <TR>
                <TD align=right width=200><B>Free Posting Mode : </B></TD>
                <TD align=left width=50><select class=ia name=pst>
                    <option value="n" <?php if ($posting == n) echo("selected");

        ?>>Off</option>
                    <option value="y" <?php if ($posting == y) echo("selected");

        ?>>On</option>
                  </select>
                </td>
              </TR>
              <TR>
                <TD align=right width=200><B>Sorting Posts Mode : </B></TD>
                <TD align=left width=50><select class=ia name=sort>
                    <option value="ASC" <?php if ($sorting == "ASC") echo("selected");

        ?>>ASCENDING</option>
                    <option value="DESC" <?php if ($sorting == "DESC") echo("selected");

        ?>>DESCENDING</option>
                  </select>
                </td>
              </TR>
              <TR>
                <TD align=right width=200><B>Show page generation time : </B></TD>
                <TD align=left width=50><select class=ia name=gen_time>
                    <option value="n" <?php if ($time_show == n) echo("selected");

        ?>>Off</option>
                    <option value="y" <?php if ($time_show == y) echo("selected");

        ?>>On</option>
                  </select>
                </td>
              </TR>
              
              <TR>
                <TD align=right width=200><B>Number of items on the page : </B></TD>
                <TD align=left width=50><select class=ia name=nb>
                    <?
                        for ($i=1; $i<=10; $i++) {
                            echo("<option value=$i" . (($nb == $i) ? " selected" : "") . ">" . $i . "\n");
                        }
                    ?>
                  </select>
                </td>
              </TR>
              <TR>
                <TD align=right width=200><B>Subject of email notifications : </B></TD>
                <TD align=left width=50><input type="text" size="35" name="emailsub" value="<?php echo($emailsub); ?>">
                </td>
              </TR>
              <TR>
                <TD align=right width=200><B>URL for outside links (email notification) Example(http://www.test.com/BaalSystems/index.php): </B></TD>
                <TD align=left width=50><input type="text" size="35" name="url" value="<?php echo($url); ?>">
                </td>
              </TR>
              <TR>
                <TD align=right colSpan=2><INPUT class=ib type=submit value=Submit name=submit>
                </TD>
              </TR>
              <tr>
                <td colspan="3"><br>
                  <a href="showdb.php">Show DB Structure</a><br></td>
              </tr>
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
