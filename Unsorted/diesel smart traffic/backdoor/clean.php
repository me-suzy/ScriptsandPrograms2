<?
  require("../conf/sys.conf");
  require("../lib/ban.lib");
  require("../lib/mysql.lib");
  $db = c();
	
   $ev=f(q("select count(id) as n from event"));
   echo "<br><b>$ev[n]</b> messages, notifications and events in database.";
   $ev=f(q("select count(id) as n from previews"));
   echo "<br><b>$ev[n]</b> website visits in database.";
   $ev=f(q("select count(id) as n from clicks"));
   echo "<br><b>$ev[n]</b> website hits in database.";

if ($cleanevents)
   {
   if (!$expdays) $expdays=90;
   $exptime=60*60*24*$expdays;

   $sqlexp="DELETE FROM event WHERE ((";

	if ($all) $sqlexp .= "1 OR ";
	if ($news) $sqlexp .= "type='news' OR";
	if ($email) $sqlexp .= "type='ppemail' OR";
	if ($refer) $sqlexp .= "type='refer' OR";
	if ($payment) $sqlexp .= "type='payment' OR";
	if ($withdraw) $sqlexp .= "type='withdraw' OR";

   $sqlexp .= " 0) and (rdate < ".(time()-$exptime)."))";
   q($sqlexp);
   echo "<br> Events cleaned from database!";
   
   q("delete from clicks where idate< ".(time()-$exptime));
   q("delete from previews where idate< ".(time()-$exptime));

   $ev=f(q("select count(id) as n from event"));
   echo "<br><b>$ev[n]</b> messages, notifications and events in database.";
   $ev=f(q("select count(id) as n from previews"));
   echo "<br><b>$ev[n]</b> website visits in database.";
   $ev=f(q("select count(id) as n from clicks"));
   echo "<br><b>$ev[n]</b> website hits in database.";
 };

  d($db);

?>
<p><b><font size="2" face="Arial, Helvetica, sans-serif"> </font></b></p>
<form name="form1" method="post" action="clean.php">
  <b><font size="2" face="Arial, Helvetica, sans-serif">CLEAN &gt;</font></b> 
  <table border="0" cellspacing="1" cellpadding="1" align="center" bgcolor="#CCCCCC" width="300">
    <tr bgcolor="#FFFFFF"> 
      <td colspan="2"><strong>Events</strong></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td><font size="2" face="Arial, Helvetica, sans-serif">News and messages</font></td>
      <td> <font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="checkbox" name="news" value="1">
        </font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td><font size="2" face="Arial, Helvetica, sans-serif">Paid emails copies</font></td>
      <td> <font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="checkbox" name="email" value="1" checked>
        </font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td><font size="2" face="Arial, Helvetica, sans-serif">Referral notifications</font></td>
      <td> <font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="checkbox" name="refer" value="1">
        </font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td><font size="2" face="Arial, Helvetica, sans-serif">Payment notifications</font></td>
      <td> <font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="checkbox" name="payment" value="1">
        </font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td><font size="2" face="Arial, Helvetica, sans-serif">Withdrawal requests</font></td>
      <td> <font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="checkbox" name="withdraw" value="1">
        </font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td colspan="2"><strong>Traffic logging</strong></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td><font size="2" face="Arial, Helvetica, sans-serif">Visitors</font></td>
      <td> <font size="2" face="Arial, Helvetica, sans-serif"> 
        <input name="visitors" type="checkbox" id="visitors" value="1">
        </font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td><font size="2" face="Arial, Helvetica, sans-serif">Hits</font></td>
      <td> <font size="2" face="Arial, Helvetica, sans-serif"> 
        <input name="hits" type="checkbox" id="hits" value="1">
        </font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td colspan="2"><strong>Timeframe</strong></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td><font size="2" face="Arial, Helvetica, sans-serif">Older than</font></td>
      <td> <font size="2" face="Arial, Helvetica, sans-serif"> 
        <select name="expdays">
          <option value="2">48 hours</option>
          <option value="3">3 Days</option>
          <option value="7" selected>1 Week</option>
          <option value="14">2 Weeks</option>
          <option value="30">1 Month</option>
          <option value="60">2 Months</option>
          <option value="120">4 Months</option>
          <option value="365">1 Year</option>
        </select>
        </font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td colspan="2"> <div align="center"> 
          <input type="hidden" name="cleanevents" value="1">
          <input type="reset" name="Reset" value="Reset form">
          <input type="submit" name="Submit" value="Clean !">
        </div></td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
<p>&nbsp; </p>
<?
  require("footer.html");
?>