<?php
	include_once("$DOCUMENT_ROOT/library/auth.php");
	include_once("$DOCUMENT_ROOT/library/db.php");
	include_once("$DOCUMENT_ROOT/library/menus.php");

/*
***************************************************************************************************************************
*****************************************COPYRIGHT 2005 YOU MAY NOT USE THIS WITHOUT PERMISSION****************************

HJB IS PROVIDED "As Is" FOR USE ON WEBSITES WHERE A LICENSE FOR SUCH USE WAS PURCHASED.  IT MAY ONLY BE USED ON ONE SITE PER LICENSING
FEE.  IN ORDER TO USE ON ADDITIONAL SITES, ADDITIONAL LICENSES MUST BE PURCHASED.  


THE PHP SCRIPTS MAY BE ALTERED, AS LONG AS THE CREDIT LINE AND LINKS AT THE BOTTOM OF EACH PAGE REMAIN. THE FLASH MAY NOT IN ANY
WAY BE CHANGED OR ALTERED.  ANY VIOLATION OF THESE TERMS WILL RESULT IN THE FORFEITING OF YOUR RIGHT TO USE THIS SOFTWARE.

NationWideShelving.com does not guarantee this software in anyway.  You use this at your own risk.  NationWideShelving or any of its
employees or subsidiaries are not responsible for any damage, and / or loss of business, reputation, or other damages of any kind
which are caused whether actual or not, by the use of this product.  By using this product you agree to hold NationWideShelving, its
employees, and all subsidiaries harmless for any and all reasons associated with your use of this product.

Your installation of this software consititues an agreement to these terms.

****************************************************************************************************************************
	*/

	session_start();
	
	
	//connect to database
	$connect=mysql_connect($host_default, $login_default, $pw_default);
	$select_db=mysql_select_db($db_default);
	
	//is this a submission?
	if ($_POST['submitted']=="true"){
		$query="UPDATE orders SET orderStatus='".$_POST['orderStatus']."' WHERE id='".$_POST['id']."'";
		
		$result=mysql_query($query);
		$productID=$_POST['productID'];
		
	}
	
	$query="SELECT * FROM orders WHERE id='".$_GET['id']."'";
	$result=mysql_query($query);
	$row=mysql_fetch_array($result);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Manage Shopping Cart Products</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.style1 {font-family: Verdana, Arial, Helvetica, sans-serif}
.style12 {font-family: Verdana, Arial, Helvetica, sans-serif; color: #FF0000; font-size: 18pt; }
-->
</style>
</head>

<body>
<?php //INCLUDE HEADER
include ("$DOCUMENT_ROOT/library/header.php"); ?>
<table width="100%"  border="1" cellpadding="0" cellspacing="0" bordercolor="#010556">
  <tr>
    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="18%" valign="top" bgcolor="#010556"><div align="center"><br>
              <br>
              <br>
              <br>
              <?php menu_shopping_cart(); ?>          
              <br>              
          </div></td>
          <td width="82%" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="1%">&nbsp;</td>
                <td width="99%" valign="top"><p><span class="style12">View Orders </span></p>                
                  <form action="" method="post" enctype="multipart/form-data" name="form1">
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td valign="top"><div align="right"><span class="style1">Customer Name: </span><br>
                          <br>
                        </div></td>
                        <td>&nbsp;</td>
                        <td valign="top"><span class="style1"><?php echo $row['firstName']." ".$row['lastName']; ?></span></td>
                      </tr>
                      <tr>
                        <td width="19%" valign="top"><div align="right" class="style1"> Phone:<br>
                          <br> 
                          </div></td>
                        <td width="2%">&nbsp;</td>
                        <td width="79%" valign="top"><span class="style1"><?php echo $row['phone']; ?></span>                            <br></td></tr>
                      <tr>
                        <td valign="top"><div align="right" class="style1">Email:<br>
                          <br>
                        </div></td>
                        <td>&nbsp;</td>
                        <td valign="top"><span class="style1"><?php echo $row['email']; ?></span>                            <br></td></tr>
                      <tr>
                        <td valign="top"><div align="right" class="style1">Address:</div></td>
                        <td>&nbsp;</td>
                        <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="50%" valign="top"><span class="style1">Shipping:<br>
                                <?php echo $row['shippingAddress']; ?><br>
                                <?php echo $row['shippingCity']; ?>, <?php echo $row['shippingState']; ?>, <?php echo $row['shippingZip']; ?></span><br></td>
                            <td width="3%">&nbsp;</td>
                            <td width="47%" valign="top"><span class="style1">Billing:<br>
                                <?php echo $row['billingFirstName']." ".$row['billingLastName']; ?><br>
                                <?php echo $row['billingAddress']; ?><br>
                                <?php echo $row['billingCity']; ?>, <?php echo $row['billingState']; ?>, <?php echo $row['billingZip']; ?></span></td>
                          </tr>
                        </table>                          <br></td></tr>
                      <tr>
                        <td valign="top"><div align="right" class="style1">Credit Card:</div></td>
                        <td>&nbsp;</td>
                        <td><span class="style1"><?php echo $row['creditCardNumber']; ?><br>
                        </span></td>
                      </tr>
                      <tr>
                        <td valign="top"><div align="right" class="style1">Expiration Date:<br>
                          <br> 
                          </div></td>
                        <td>&nbsp;</td>
                        <td valign="top"><span class="style1"><?php echo $row['expDate']; ?></span></td>
                      </tr>
                      <tr>
                        <td class="style1"><div align="right">Sub-Total:<br>
                          <br>
                        </div></td>
                        <td>&nbsp;</td>
                        <td valign="top"><span class="style1"><?php echo $row['totalPrice']; ?></span></td>
                      </tr>
                      <tr>
                        <td class="style1"><div align="right">Tax:<br>
                          <br>
                        </div></td>
                        <td>&nbsp;</td>
                        <td valign="top"><span class="style1"><?php echo $row['taxPrice']; ?></span></td>
                      </tr>
                      <tr>
                        <td class="style1"><div align="right">Total:<br>
                          <br>
                        </div></td>
                        <td>&nbsp;</td>
                        <td valign="top"><span class="style1"><?php echo $row['finalPrice']; ?></span></td>
                      </tr>
                      <tr>
                        <td class="style1"><div align="right">Cart Contents:<br>
                          <br> 
                        </div></td>
                        <td>&nbsp;</td>
                        <td valign="top"><span class="style1"><?php echo $row['cartContents']; ?></span></td>
                      </tr>
                      <tr>
                        <td><div align="right" class="style1">Order Status:<br>
                          <br> 
                        </div></td>
                        <td>&nbsp;</td>
                        <td valign="top"><select name="orderStatus" id="orderStatus">
                          <option value="new" <?php if ($row['orderStatus']=="new"){ echo "selected";} ?>>New Order</option>
                          <option value="progress" <?php if ($row['orderStatus']=="progress"){ echo "selected";} ?>>In Progress</option>
                          <option value="completed" <?php if ($row['orderStatus']=="completed"){ echo "selected";} ?>>Completed</option>
                        </select></td>
                      </tr>
                      <tr>
                        <td colspan="3"><div align="center"><br>
                                <input type="submit" name="Submit" value="Add Product To Shopping Carts">
                                <input name="submitted" type="hidden" id="submitted" value="true">
                                <input name="id" type="hidden" id="id" value="<?php echo $_GET['id'];  ?>">
                        </div></td>
                      </tr>
                    </table>
                  </form>
                  <p><span class="style12">                    </span><a href="add_products.php"><br>
                    <br>
                    </a><br>
                    <br>
                  </p></td>
              </tr>
          </table></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>
