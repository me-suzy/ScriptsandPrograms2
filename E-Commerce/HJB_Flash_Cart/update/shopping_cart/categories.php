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
	
	$query="SELECT * FROM sites WHERE id='".$_GET['siteID']."'";
	$result=mysql_query($query);
	$site=mysql_fetch_array($result);
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
                <td width="99%"><span class="style12">Manage Categories For <?php echo $site['name']; ?> </span><br>
                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="4%">&nbsp;</td>
                      <td width="96%"><a href="add_category.php?siteID=<?php echo $_GET['siteID']; ?>"><br>
                        <img src="/images/buttons/add_category.gif" width="106" height="125" border="0"></a><br>
                      <br>
                      <table width="100%"  border="1" cellpadding="0" cellspacing="0" bordercolor="#010556">
                        <tr bgcolor="#010556">
                          <td width="33%"><div align="center" class="style1 style13">Category Name </div></td>
                          <td width="33%"><div align="center" class="style14">Delete Category </div></td>
                        </tr>
						
						<?php
								$query="SELECT cartcategories.* 
										FROM cartcategories, cartcategories_to_sites
										WHERE cartcategories_to_sites.cartCategoriesID=cartcategories.id
										AND cartcategories_to_sites.sitesID='".$_GET['siteID']."'
										";
								$result=mysql_query($query);
								while ($row=mysql_fetch_array($result)){
						?>
                        <tr>
                          <td><div align="left"><span class="style1"><?php echo "<a href=\"view_category.php?catID=".$row['id']."\">". $row['name']."</a>"; ?></span></div></td>
                          <td><div align="center"><span class="style1"><?php echo "<a href=\"delete_category.php?catID=".$row['id']."&siteID=".$_GET['siteID']."\">:: Delete :: </a>"; ?></span></div></td>
                        </tr>
						<?php
						
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
