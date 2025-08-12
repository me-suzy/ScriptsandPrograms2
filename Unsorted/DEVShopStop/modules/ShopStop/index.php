<HTML>
<HEAD>
<TITLE>Shop Stop Shopping Center</TITLE>
<HEAD>

<?php
/************************************************************************/
/*                                                                      */
/* Copyright (c) 2001-2002 by CrossWalkCentral                          */
/* http://www.crosswalkcentral.net                                      */
/*                                                                      */
/* CrossWalkCentral                                                     */
/* You Web Hosting Community!                                           */
/*                                                                      */
/* Let us customize this script for you.                                */
/*                                                                      */
/* Please let us know what you think of this script			*/
/* at http://www.crosswalkcentral.net/modules.php?name=Forum            */
/*                                                                      */
/* 									*/
/************************************************************************/

include("header.php");
include("config.php");
require("VarCWC02.php");

// CHECK CONFIG TO SEE IF SIDE BAR IS ON OR NOT
if ($right_side==on) {
 $index = 1;
}

If (empty($service)) {

// Request info
$result = mysql_query(
"SELECT * FROM CWC_shopstopcat");
if (!$result) {
echo("<P>Error performing query: " .
mysql_error() . "</P>");
exit();
}

echo ("<center><H4>Shop Stop Shopping Center</h4></center>" );

OpenTable();
echo "<table width=\"100%\" cellpadding=\"4\" cellspacing=\"0\" border=\"0\">"
        ."<tr><td bgcolor=\"$bgcolor2\"><font class=\"option\"><b>".Categories."</b></font></td></tr><tr><td></table>";

// Display the text
while ( $row = mysql_fetch_array($result) ) {
$ccat=$row["cat"];
$cid=$row["cid"];
$cdes=$row["catdes"];

?>
<table width="95%" border="0" align="center">
  <tr> 
    <td width="33%"> 
      <a href="modules.php??op=modload&name=ShopStop&&service=<?php echo($cid); ?>">
      <li><?php echo($ccat); ?></li>
	  </a>
    </td>
    <td width="67%"> 
      <?php echo($cdes); ?>
    </td>
  </tr>
</table>
 
<?php
}
CloseTable();


} else {

echo ("<center><H4>Shop Stop Shopping Center</h4></center>" );
OpenTable();
Echo ("Category: <a href='modules.php??op=modload&name=ShopStop'>Main </a>-> $ccat");

//Display View Cart Button
?>
<a href="<?
echo("https://www.paypal.com/cart/display=1&business=$paypalid"); ?>"target="_blank">
<img src="https://www.paypal.com/images/view_cart.gif" border="0"></a>
<?

// Request info
$result = mysql_query(
"SELECT * FROM CWC_shopstop WHERE catid='$service' ORDER by name ");
if (!$result) {
echo("<P>Error performing query: " .
mysql_error() . "</P>");
exit();
}

// Display the text
while ( $row = mysql_fetch_array($result) ) {
$sid=$row["Pid"];
$sname=$row["name"];
$sdes=$row["des"];
$sprice=$row["price"];
$scatid=$row["catid"];
?>
<table width="95%" border="0" align="center">
  <tr>
    <td width="25%">
      <?
//Display Product info
Echo ("<p>");
Echo ("<li><b>$sname<br></b></li>");
Echo (" $sdes<br>");
Echo ("Price: $$sprice<br>");

//Display PayPal Purchase link and image
?>
    </td>
    <td width="40%" align="center"> 
    </td>
    <td width="10%" valign="top" align="left"> 
      <div align="left"><a href="#" onclick="window.open('https://www.paypal.com/cart/add=1&business=<? echo ($paypalid); ?>&item_name=<? echo ($sname); ?>&item_number=<? echo ($sid); ?>&amount=<? echo ($sprice); ?>&image_url=<? echo ($logourl); ?>&return=<? echo ($payreturn); ?>&cancel_return=<? echo ($paycancel); ?>&no_note=1','cartwin','width=600,height=400,scrollbars,location,resizable,status');"><img src="<? echo ("$paycart"); ?>" border="0"></a>
      </div>
    </td>
  </tr>
</table>
<p>



<?
}
CloseTable();
}


Echo ("<br><br><br><br><br><center>Stop Shop Shopping Center created by<br> <a href='Http://www.crosswalkcentral.net'>CrossWalkCentral</a><br>Version $ver</center>");


include ("footer.php");
?>
