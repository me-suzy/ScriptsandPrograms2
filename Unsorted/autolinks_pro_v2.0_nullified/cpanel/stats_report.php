<?
/////////////////////////////////////////////////////////////
// Program Name         : Autolinks Professional            
// Program Version      : 2.0                               
// Program Author       : ScriptsCenter                     
// Supplied by          : CyKuH [WTN] , Stive [WTN]         
// Nullified by         : CyKuH [WTN]                       
// Distribution         : via WebForum and Forums File Dumps
//                   (c) WTN Team `2002
/////////////////////////////////////////////////////////////

  include( "cp_initialize.php" );
  
  if( $submitted=="checkstats" )
  {
	if( $alldays )
	  $day = "_all_";
	else
	  $day = "$year-$month-$day";
	
	header( "Location: stats_show.php?listby=$listby&ref=$ref&site=$site&day=$day" );
  }

  $info = "All statistics are delayed 24 hours. The hits of the past 24 hours can be found in the <a href='logs_report.php'>IP logs</a>";

?>
<html>
<head>
<link rel="stylesheet" href="main.css">
</head>
<body>
<? showmessage(); ?>
<form method="post" action="<? echo($PHP_SELF); ?>">
<input type="hidden" name="submitted" value="checkstats">
  <table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
  <tr>
    <td>
        <table cellpadding='4' cellspacing='1' border='0' width='100%'>
          <tr class='tblhead'>
            <td colspan='2'><font color="#FFFFFF" size="1">GENERATE REPORT</font></td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Referrer(s)</b><br>
              <font size="1">Referrers who sent the hits to the website(s). </font></p>
            </td>
            <td width="35%">
              <select name="ref">
			    <option value="_all_">All Referrers</option>
                <?
  $res_ref = mysql_query( "SELECT * FROM al_ref" );

  while( $ref = mysql_fetch_array($res_ref) )
  {
    echo( "<option value='{$ref['login']}'>{$ref['name']}</option>" );
  }
?>
              </select>
            </td>
          </tr>

<?
  $res_site = mysql_query( "SELECT * FROM al_site" );

  if( mysql_num_rows($res_site)==1 ):

    $site = mysql_fetch_array( $res_site );
	echo( "<input type='hidden' name='site' value='{$site[login]}'>" );
    
  else:
?>
		  
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Website(s)</b><br>
              <font size="1">Websites who received the hits sent by the referrer(s)</font></p>
            </td>
            <td width="35%">
              <select name="site">
			    <option value="_all_">All Websites</option>
<?
  $res_site = mysql_query( "SELECT * FROM al_site" );

  while( $site = mysql_fetch_array($res_site) )
  {
    echo( "<option value='{$site['login']}'>{$site['name']}</option>" );
  }
?>
              </select>
            </td>
          </tr>
		  
<? endif; ?>
		  
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Day(s)</b><br>
              <font size="1">Day when the hits have been sent. Leave to <i>All Days</i> to show hits sent since the beginning.</font></p>
            </td>
            <td width="35%">
              <table width="240" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td>
                    <input type="radio" name="alldays" value="1" checked>
                    &nbsp;All Days<br>
                    <input type="radio" name="alldays" value="0">
                    <select name="day">
                      <option value="01">01</option>
                      <option value="02">02</option>
                      <option value="03">03</option>
                      <option value="04">04</option>
                      <option value="05">05</option>
                      <option value="06">06</option>
                      <option value="07">07</option>
                      <option value="08">08</option>
                      <option value="09">09</option>
                      <option value="10">10</option>
                      <option value="11">11</option>
                      <option value="12">12</option>
                      <option value="13">13</option>
                      <option value="14">14</option>
                      <option value="15">15</option>
                      <option value="16">16</option>
                      <option value="17">17</option>
                      <option value="18">18</option>
                      <option value="19">19</option>
                      <option value="20">20</option>
                      <option value="21">21</option>
                      <option value="22">22</option>
                      <option value="23">23</option>
                      <option value="24">24</option>
                      <option value="25">25</option>
                      <option value="26">26</option>
                      <option value="27">27</option>
                      <option value="28">28</option>
                      <option value="29">29</option>
                      <option value="30">30</option>
					  <option value="31">31</option>
                    </select>
                    <select name="month">
                      <option value="01">January</option>
                      <option value="02">February</option>
                      <option value="03">March</option>
                      <option value="04">April</option>
                      <option value="05">May</option>
                      <option value="06">June</option>
                      <option value="07">July</option>
                      <option value="08">August</option>
                      <option value="09">September</option>
                      <option value="10">October</option>
                      <option value="11">November</option>
                      <option value="12">December</option>
                    </select>
                    <select name="year">
                      <option value="2000">2000</option>
                      <option value="2001">2001</option>
                      <option value="2002">2002</option>
                      <option value="2003">2003</option>
                      <option value="2004">2004</option>
                      <option value="2005">2005</option>
                      <option value="2006">2006</option>
                      <option value="2007">2007</option>
                      <option value="2008">2008</option>
                      <option value="2009">2009</option>
                      <option value="2010">2010</option>
                    </select>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%"><b>List By</b><br>
              <font size="1">Choose how you want the stastics to appear.</font></td>
            <td width="35%">
              <select name="listby">
                <option value="day">Days</option>
                <option value="site">Websites</option>
                <option value="ref">Referrers</option>
              </select>
              </td>
          </tr>
        </table>
    </td>
  </tr>
</table>
  <table cellpadding='4' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
  <tr>
    <td align="center">
        <input type="submit" value=" Generate Report " name="submit">
      </td>
  </tr>
</table>
</form>
</body>
</html>
