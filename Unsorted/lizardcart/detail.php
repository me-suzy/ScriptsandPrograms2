<?php
include ("config.inc.php");
$dbResult = mysql_query("select * from products where id='$id'");
$prow=mysql_fetch_object($dbResult);
?>

<? include ("header.php");?>

    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#3366CC">
      <tr> 
        <td width="50?"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Product 
          Details</b></font></td>
        <td>
          <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><a href="index.php"><font color="white">Back 
            to products</font></a></font></div>
        </td>
      </tr>
    </table>
    <table width="500" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td> 
          <font face="Verdana, Arial, Helvetica, sans-serif" size="2">
	  <b><?echo "$prow->item_name"?></b> <b><font color="#336699">$<? echo "$prow->item_price"?></font></b><br>
          <?echo "$prow->item_descde"?><br>
          <br>
        </td>
      </tr>
    </table>
	  <!--Shopping Cart Begin-->
	  
      <P><FORM NAME=order ACTION="managecart.php" onSubmit="AddToCart(this);">
      Quantity: <input type=text size=2 maxlength=3 name=QUANTITY onChange='this.value=CKquantity(this.value)' value="1">
	  <input type="image" src="graphics/buynow.gif" border=0 value="Add to Cart" align=top>
      <input type=hidden name=PRICE value="<? echo "$prow->item_price"?>">
      <input type=hidden name=NAME value="<?echo "$prow->item_name"?>">
      <input type=hidden name=ID_NUM value="<? echo "$prow->id" ?>">
      <input type=hidden name=SHIPPING value="<? echo "$prow->item_ship" ?>">
	  
      </FORM>
      <!--Shopping Cart End  -->
    <br>
  </div>
</div>
<? include ("footer.php");?>
