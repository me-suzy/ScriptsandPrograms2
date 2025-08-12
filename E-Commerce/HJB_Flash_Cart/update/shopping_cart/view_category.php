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
	
	$query="SELECT * FROM cartcategories where ID='".$_GET['catID']."'
			";
	$result=mysql_query($query);
	$category=mysql_fetch_array($result);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Manage Shopping Cart</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.style1 {font-family: Verdana, Arial, Helvetica, sans-serif}
.style12 {font-family: Verdana, Arial, Helvetica, sans-serif; color: #FF0000; font-size: 18pt; }
.style13 {color: #FFFFFF}
.style14 {font-family: Verdana, Arial, Helvetica, sans-serif; color: #FFFFFF; }
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
                <td width="99%"><span class="style12">Manage <?php echo $category['name']; ?> </span><br>
                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="4%">&nbsp;</td>
                      <td width="96%"><a href="add_category.php?siteID=<?php echo $_GET['siteID']; ?>"><br>
                        </a><br>
                        <span class="style1">Products Assigned To This Category: </span><br>
                      <table width="100%"  border="1" cellpadding="0" cellspacing="0" bordercolor="#010556">
                        <tr bgcolor="#010556">
                          <td width="33%"><div align="center" class="style1 style13">Product Name </div></td>
                          <td width="33%"><div align="center" class="style14">Price </div></td>
						  <td width="33%"><div align="center" class="style14">Delete From Category </div></td>
                        </tr>
						
						<?php
								$query2="SELECT cartitems.* 
										FROM cartitems, cartitems_to_cartcategories
										WHERE cartitems.id=cartitems_to_cartcategories.cartItemsID
										AND cartitems_to_cartcategories.cartCategoriesID='".$_GET['catID']."'
										ORDER BY cartitems.name
										";
								$result2=mysql_query($query2);
								$i=0;
								while ($row2=mysql_fetch_array($result2)){
									$i++;
									$assignedProducts[$i]=$row2['id'];
						?>
                        <tr>
                          <td><div align="left"><span class="style1"><?php echo "<a href=\"view_product.php?productID=".$row2['id']."\">". $row2['name']."</a>"; ?></span></div></td>
                          <td><div align="center"><span class="style1"><?php echo "$".$row2['price']; ?></span></div></td>
						  <td><div align="center"><span class="style1"><?php echo "<a href=delete_product_from_category.php?productID=".$row2['id']."&catID=".$_GET['catID'].">:: DEL ::</a>"; ?></span></div></td>
                        </tr>
						<?php
						
						}
						?>
                      </table>
                      <br>
                      <span class="style1"><br>
                      </span>
                      <p></p>
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
                      <span class="style1">                      Products Not Assigned To This Category: </span><br>
                      <table width="100%"  border="1" cellpadding="0" cellspacing="0" bordercolor="#010556">
                        <tr bgcolor="#010556">
                          <td width="33%"><div align="center" class="style1 style13">Product Name </div></td>
                          <td width="33%"><div align="center" class="style14">Price </div></td>
                          <td width="33%"><div align="center" class="style14">Add To  Category </div></td>
                        </tr>
                        <?php
								//we want the user to be able to search products.  we need to save their search
								//in a session, in the event that they add a product, so that they return to the
								//search results, and not to a listing of all products.
								//the following code first checks to see if there are any registered session
								//variables for the search, or any search terms.  If not, it displays all products.
								//if there are, then it checks to see if it is a new search.
								//if it is a new search, then it saves the new search into the session, and displays
								//the results.
								//if it is not a new search, then it pulls the last saved search from the session.
								if (!$_POST['searchProducts'] && !$_SESSION['view_category_search_query']){
									$query3="SELECT * FROM cartitems ORDER BY name";
								}else{
									if($_POST['searchProducts']){
										$query3="SELECT * FROM cartitems 
												WHERE name LIKE '%".$_POST['searchProducts']."%'
												ORDER BY name";
										$_SESSION['view_category_search_query']=$query3;
									}else{
										$query3=$_SESSION['view_category_search_query'];
									}
								}
								$result3=mysql_query($query3);
								while ($row3=mysql_fetch_array($result3)){
									//lets see if this is an active product.
									$i2=1;
									$isActive=false;
									while ($i2<=$i){
										if ($assignedProducts[$i2]==$row3['id']){
											$isActive=true;
										}
									$i2++;
									}
									if ($isActive!=true){
									
									
						?>
											<tr>
											  <td><div align="left"><span class="style1"><?php echo "<a href=\"view_product.php?productID=".$row3['id']."\">". $row3['name']."</a>"; ?></span></div></td>
											  <td><div align="center"><span class="style1"><?php echo "$".$row3['price']; ?></span></div></td>
											  <td><div align="center"><span class="style1"><?php echo "<a href=add_product_from_category.php?productID=".$row3['id']."&catID=".$_GET['catID'].">:: ADD ::</a>"; ?></span></div></td>
											</tr>
                        <?php
									}
						}
						?>
                      </table></td>
                    </tr>
                  </table>                  
                <br></td>
              </tr>
          </table></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>
