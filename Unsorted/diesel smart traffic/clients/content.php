<?
   require "../conf/sys.conf";
   require "../lib/mysql.lib";
   require "../lib/group.lib";
   $db = c();

if (!$uid){
   if (!e(q("select id from campaigns where user_id='$auth'"))) $cm=f(q("select id from campaigns where user_id='$auth' ORDER BY RAND()"));
  $uid=$cm["id"];
}


   include "../tpl/clients_top.ihtml";
    if (!$uid) echo "<br><b>ERROR: No campaigns available to use this feature on !</b><br>";

?> 
<p><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><b>PROVIDE 
  CONTENT FOR YOUR WEBSITE </b><br>You can add dynamic content to your site provided by our system, just by placing some simple html code on your pages. You also earn credits when visitors use this content.</font></p>
<p>&nbsp;</p>
<p><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><b>TOP 25 
  Sites</b></font><font color="#333333"><br>
  <font face="Arial, Helvetica, sans-serif" size="2"> 
  <script language=JavaScript src="<? echo $ROOT_HOST; ?>inc.top25.php?uid=<?php echo $uid;?>"></script>
  <br>
  Copy and paste this HTML code to display the top in your pages:<br>
  <input type="text" name="textfield" size="120" value="&lt;script language=JavaScript src=&quot;<? echo $ROOT_HOST; ?>inc.top25.php?uid=<?php echo $uid;?>&quot;&gt;&lt;/script&gt;">
  </font></font></p>
<p>&nbsp;</p>
<p><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><b>PPC Search 
  Box </b></font></p>
<table  border="0" cellspacing="0" cellpadding="0">
<form action="<? echo $ROOT_HOST; ?>ppc.php" method=post>
    <tr> 
      <td><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><B><font color="#000000">SEARCH 
        </font>&nbsp;</B> </font></td>
      <td align="center"> <font face="Arial, Helvetica, sans-serif" size="2" color="#333333"> 
        <input class=small type="text" name="keys" size=40>
        </font></td>
      <td><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"> 
        <input class=small type="submit" name="Submit" value="GO">
        <input type="hidden" name="other" value="on">
        <input type="hidden" name="uid" value="<?php echo $uid;?>">
       </font></td>
    </tr>
  </form>
</table>
<font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><br>
Copy and paste this HTML code to put the search form in your pages : <br>
<textarea name="textfield2" cols="100" rows="5">            <table  border="0" cellspacing="0" cellpadding="0">
              <form action="<? echo $ROOT_HOST; ?>ppc.php" method=post>
                <tr>
                  <td><B>SEARCH &nbsp;</B> </td>
                  <td align="center">
                    <input class=small type="text" name="keys" size=40 value="<?php echo $keys; ?>">
                  </td>
                  <td><input class=small type="submit" name="Submit" value="GO">
	  <input type="hidden" name="other" value="on">
	  <input type="hidden" name="uid" value="<?php echo $uid;?>">
	</td>
                </tr>
              </form>
            </table>
</textarea>
<br>
</font> 
<p>&nbsp;</p>
<p><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><b>PORTAL 
  GROUPS </b></font><font color="#333333"><br>
  <font face="Arial, Helvetica, sans-serif" size="2"> 
  <script language=JavaScript src="<? echo $ROOT_HOST; ?>inc.groups.php?uid=<?php echo $uid;?>"></script>
  <br>
  Copy and paste this HTML code to display the portal categories in your pages:<br>
  <input type="text" name="textfield3" size="120" value="&lt;script language=JavaScript src=&quot;<? echo $ROOT_HOST; ?>inc.groups.php?uid=<?php echo $uid;?>&quot;&gt;&lt;/script&gt;">
  </font></font></p>
<p>&nbsp;</p>

<p><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><b>TRAFFIC SYSTEM INFORMATION 
 </b></font><font color="#333333"><br>
  <font face="Arial, Helvetica, sans-serif" size="2"> 
  <script language=JavaScript src="<? echo $ROOT_HOST; ?>inc.sysinfo.php?uid=<?php echo $uid;?>"></script>
  <br>
  Copy and paste this HTML code to display the traffic system information in your pages:<br>
  <input type="text" name="textfield3" size="120" value="&lt;script language=JavaScript src=&quot;<? echo $ROOT_HOST; ?>inc.sysinfo.php?uid=<?php echo $uid;?>&quot;&gt;&lt;/script&gt;">
  </font></font></p>

<p> <font face="Arial, Helvetica, sans-serif" size="2" color="#333333"> 
  <?
   include "../tpl/clients_bottom.ihtml";
   d($db);
?>
  </font></p>
<p>&nbsp;</p>
