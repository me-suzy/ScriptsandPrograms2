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
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Manage Shopping Cart Products</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.style12 {font-family: Verdana, Arial, Helvetica, sans-serif; color: #FF0000; font-size: 18pt; }
.style21 {font-family: Verdana, Arial, Helvetica, sans-serif; color: #FFFFFF; }
.style22 {font-family: Verdana, Arial, Helvetica, sans-serif}
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
                <td width="99%"><p><span class="style12">Manage Shopping Cart Products </span></p>
                  <p><span class="style22">Products are added, edited, and deleted via this interface. Once added, a product can be shown across multiple sites (if multiple sites are being used) and across multiple categories.<br> 
                    </span><br>
                    <a href="add_products.php"><img src="/images/buttons/add_products.gif" width="106" height="125" border="0"><br>
                    </a></p>
                  <form name="form1" method="post" action="">
                    <table width="50%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="31%"><div align="right">SEARCH::</div></td>
                        <td width="4%">&nbsp;</td>
                        <td width="65%"><input name="searchProducts" type="text" id="searchProducts">
                        <input type="submit" name="Submit" value="Find"></td>
                      </tr>
                    </table>
                  </form>
                  <table width="100%"  border="1" cellpadding="0" cellspacing="0" bordercolor="#010556">
                    <tr bgcolor="#010556">
                      <td width="51%"><div align="center" class="style21">Product Name </div></td>
                      <td width="25%"><div align="center" class="style21">Product Price </div></td>
                      <td width="24%"><div align="center" class="style21">Deleted Product </div></td>
                    </tr>
					<?php 
						if (!$_POST['searchProducts']){
							$query="SELECT * FROM cartitems ORDER BY name";
						}else{
							$query="SELECT * FROM cartitems 
									WHERE name LIKE '%".$_POST['searchProducts']."%'
									ORDER BY name";
						}
						$result=mysql_query($query);
						while ($row=mysql_fetch_array($result)){
					?>
							<tr>
							  <td width="51%"><span class="style22"><a href="/update/shopping_cart/view_product.php?productID=<?php echo $row['id'] ?>"><?php echo $row['name'] ?></a></span></td>
							  <td width="25%"><span class="style22">$<?php echo $row['price'] ?></span></td>
							  <td width="24%"><span class="style22"><a href="/update/shopping_cart/delete_product.php?productID=<?php echo $row['id'] ?>">-DEL-</a></span></td>
							</tr>
					<?php
						}
					?>
                  </table>
                  <p><br>
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
