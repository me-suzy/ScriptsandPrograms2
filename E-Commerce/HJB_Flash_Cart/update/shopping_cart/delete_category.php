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
	
	$query="DELETE FROM cartcategories WHERE id='".$_GET['catID']."'";
	$result=mysql_query($query);
	
	$query2="DELETE FROM cartcategories_to_sites WHERE cartCategoriesID='".$_GET['catID']."'";
	$result2=mysql_query($query2);
	
	$query3="DELETE FROM cartitems_to_cartcategories WHERE cartCategoriesID='".$_GET['catID']."'";
	$result3=mysql_query($query3);
	
	if ($result){
		header ("location: categories.php?siteID=".$_GET['siteID']);
	}
?>