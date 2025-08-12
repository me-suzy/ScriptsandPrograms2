<?
include "config.php";

include "functions.php";

mysql_connect(STATS_MYSQL_HOSTNAME, STATS_MYSQL_USERNAME, STATS_MYSQL_PASSWORD) or die (mysql_error());
mysql_select_db(STATS_MYSQL_DATABASE) or die (mysql_error()); 

?>
<html>
<head>
<title>RFX-Stats for <? echo STATS_SITE_URL; ?></title>
</head>
<link href="styles.css" rel="stylesheet">
<body>
 <table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
   <tr>
     <td width="210" rowspan="2" class="main_boxes"><p class="title">This month (<? echo date("F", time()) ?>):</p>
       <table width="200" border="0" align="center" cellpadding="0" cellspacing="0">
         <tr>
           <td width="50" class="titles">Date</td>
           <td width="75" class="titles">Uniques</td>
           <td width="75" class="titles">Total</td>
         </tr>
         <?
	for($i = 1; $i <= date("t", time()); $i++) {
		$day_query = mysql_query("SELECT * FROM days WHERE Day = '".$i."' AND Site = '".STATS_SITE_URL."' LIMIT 1");
		if(mysql_num_rows($day_query) == 0) {
?>
         <tr>
           <td class="data"><? echo $i; ?></td>
           <td class="data">-</td>
           <td class="data">-</td>
         </tr>
         <?
		} else {
?>
         <tr>
           <td class="data"><? echo $i; ?></td>
           <td class="data"><? echo mysql_result($day_query, 0, "Uniques"); ?></td>
           <td class="data"><? echo mysql_result($day_query, 0, "Total"); ?></td>
         </tr>
         <?
			$total += mysql_result($day_query, 0, "Total");
			$unique += mysql_result($day_query, 0, "Uniques");
		}
	}
?>
         <tr>
           <td colspan="3" height="10" class="gap"></td>
         </tr>
         <tr>
           <td class="titles">Totals:</td>
           <td class="data"><? echo $unique; ?></td>
           <td class="data"><? echo $total; ?></td>
         </tr>
       </table></td>
     <td width="270" class="main_boxes"><p class="title">Months this year (<? echo date("Y", time()) ?>):</p>
       <table width="260" border="0" align="center" cellpadding="0" cellspacing="0">
         <tr>
           <td width="75" class="titles">Date</td>
           <td width="85" class="titles">Uniques</td>
           <td width="100" class="titles">Total</td>
         </tr>
         <?
		 $total = 0;
		 $unique = 0;
	for($i = 1; $i <= 12; $i++) {
		$month_query = mysql_query("SELECT * FROM months WHERE Month = '".$i."' AND Site = '".STATS_SITE_URL."' LIMIT 1");
		if(mysql_num_rows($month_query) == 0) {
?>
         <tr>
           <td class="data"><? echo month($i); ?></td>
           <td class="data">-</td>
           <td class="data">-</td>
         </tr>
         <?
		} else {
?>
         <tr>
           <td class="data"><? echo month($i); ?></td>
           <td class="data"><? echo mysql_result($month_query, 0, "Uniques"); ?></td>
           <td class="data"><? echo mysql_result($month_query, 0, "Total"); ?></td>
         </tr>
         <?
			$total += mysql_result($month_query, 0, "Total");
			$unique += mysql_result($month_query, 0, "Uniques");
		}
	}
?>
         <tr>
            <td colspan="3" height="10" class="gap"></td>
         </tr>
         <tr>
           <td class="titles">Totals:</td>
           <td class="data"><? echo $unique; ?></td>
           <td class="data"><? echo $total; ?></td>
         </tr>
       </table>       </td>
     <td width="220" class="main_boxes"><p class="title">Top Refferers :</p>
       <table width="210" border="0" cellspacing="0" cellpadding="0">
         <tr>
           <td width="100" class="titles">URL</td>
           <td width="110" class="titles">Total</td>
         </tr><?
		 $refer_query = mysql_query("SELECT * FROM refferals WHERE Site = '".STATS_SITE_URL."' ORDER BY Total DESC LIMIT 12");
		 while($refer = mysql_fetch_array($refer_query)) {
		 ?>
         <tr>
           <td class="data"><? echo $refer['Refferer']; ?></td>
           <td class="data"><? echo $refer['Total']; ?></td>
         </tr>
		 <?
		 }
		 ?>
       </table>       
     <p class="title">&nbsp;</p></td>
   </tr>
   <tr>
     <td colspan="2" class="main_boxes"><div align="center">RFX-Stats coded by <a href="http://www.radiantfx.com" target="_blank" class="linkage">RadiantFX.com</a></div></td>
   </tr>
 </table>
</body>
</html>
<?

mysql_close();

?>