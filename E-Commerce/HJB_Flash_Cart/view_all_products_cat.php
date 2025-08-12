<?php 
	include_once("$DOCUMENT_ROOT/library/db.php");
	
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
	
	//connect to database
	$connect=mysql_connect($host_default, $login_default, $pw_default);
	$select_db=mysql_select_db($db_default);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $siteName; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.style1 {font-family: Verdana, Arial, Helvetica, sans-serif}
.style9 {
	color: #A6673C;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10pt;
	text-decoration: none;
}
body {
	background-color: #CCCCCC;
}
.style10 {font-family: Verdana, Arial, Helvetica, sans-serif; text-decoration: none; color: #A6673C;}
-->
</style>
</head>

<body>
<div align="center">  <br>
  <table width="850" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
    <tr>
      <td>        <table width="850"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td bgcolor="#CCDFEC"><div align="left">
              <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><a href="/index.php"><img src="/images/logo_cart.gif" border="0"></a></td>
                  <td><div align="right">
                      <table width="300" border="1" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
                        <tr>
                          <td width="300" bgcolor="F6D48A"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td width="33%">&nbsp;</td>
                                <td width="67%"><div align="center" class="style1">Search Our Catalog </div></td>
                              </tr>
                            </table>
                              <form name="form1" method="post" action="/cart.php" style="display:inline">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input name="searchTerm" type="text" id="searchTerm">
                <input type="submit" name="Submit" value="Go">
                <input name="thisIsASearch" type="hidden" id="thisIsASearch" value="true">
                            </form></td>
                        </tr>
                      </table>
                  </div></td>
                </tr>
              </table> 
              </div>              
            <div align="right">
              </div></td>
          </tr>
        </table>
        <div align="left"><br>
          <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="20%" valign="top"><img src="images/catalog.gif" width="166" height="194"></td>
              <td width="80%" valign="top" class="style1"><p>Our catalog allows you to view and print information about our high quality products. To buy these products online, please visit our <a href="cart.php" class="style10">online shopping cart</a>. <br>
                  <br>
              Please Select A Product </p>
                <ul>
				<?php
						$query="SELECT * FROM cartcategories";
						$result=mysql_query($query);
						while ($row=mysql_fetch_array($result)){
				?>
                  <li><?php echo "<a href=\"view_all_products_cat.php?catID=".$row['id']."\">".$row['name']."</a>"; 
				  			if ($_GET['catID']==$row['id']){
								$query2=   "SELECT cartitems.name, cartitems.id 
											FROM cartitems, cartitems_to_cartcategories
											WHERE cartitems_to_cartcategories.cartCategoriesID='".$row['id']."'
											AND cartitems.id=cartitems_to_cartcategories.cartItemsID
											AND cartitems.status='1'";
								$result2=mysql_query($query2);
								while ($row2=mysql_fetch_array($result2)){
				  ?>
							<ul>
							  <li><?php echo "<a href=\"view_product_cat.php?productID=".$row2['id']."&catID=".$row['id']."\">".$row2['name']."</a>"; ?></li>
							</ul>
				  </li>
				  
                  <?php }}}?>
              </ul></td>
            </tr>
          </table>
          <br>
          <br>
        </div></td>
    </tr>
  </table>
  <br>
  <span class="style9">  <a href="http://www.mans-garage.com/hjb" class="style9">HJB Flash Cart</a> - The All Flash Shopping Cart -<a href="all_products.php" class="style9">Product Catalog </a><br>
  Copyright &copy; 1997-<?php echo date("Y"); ?> <a href="http://www.nationwideshelving.com" class="style9">NWS</a> All Rights Reserved </span><br>
</div>
</body>
</html>
