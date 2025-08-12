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
  
  if( $submitted=="delcat" )
  {
    // update category for referrers
	mysql_query( "UPDATE al_ref SET category=$newcategory WHERE category=$id" );
	
	// remove from 'accepted categories' for sites
	$res_site = mysql_query( "SELECT * FROM al_site WHERE FIND_IN_SET('$id', categories)>0" );
	while( $site = mysql_fetch_array($res_site) )
	{
	  $catarray = explode( ",", $site['categories'] );
	  
	  // go through the array and find the one to remove
	  while( list($k, $v) = each($catarray) )
	  {
	    if( $v==$id ) unset( $catarray[$k] );
	  }
	
	  $categories = implode( ",", $catarray );
	  mysql_query( "UPDATE al_site SET categories='$categories' WHERE login='{$site['login']}' LIMIT 1" );
	}
	
	// delete the category (change name to nothing)
	mysql_query( "UPDATE al_cat SET name='' WHERE id=$id LIMIT 1" );
	
	header( "Location: cat_list.php?special=delete" );
  }
  elseif( $submitted=="editcat" )
  {
	if( $name=="" )
 	{
      $notice = "Error! Some fields are incorrect or missing!";
    }
	else
	{
	  // update name
  	  $name = addslashes( $name );
	  mysql_query( "UPDATE al_cat SET name='$name', selectable=$selectable WHERE id=$id LIMIT 1" );

      $res_site = mysql_query( "SELECT * FROM al_site ORDER BY name" );
	  
	  while( $site = mysql_fetch_array($res_site) )
	  {
		$category = explode( ",", $site[categories] );
		
		// if no site selected, initialize an empty array
		if( !isset($sitelogin) ) $sitelogin = array();
		
		// site checked but category not selected for site
		if( in_array($site[login], $sitelogin) && !in_array($id, $category) )
		{
		  $category[] = $id;
		  $categories = implode( ",", $category );
		  mysql_query( "UPDATE al_site SET categories='$categories' WHERE login='{$site[login]}' LIMIT 1" );
		}
		elseif( !in_array($site[login], $sitelogin) && in_array($id, $category) )
		{
		  // go through the array and find the one to remove
		  while( list($k, $v) = each($category) )
		  {
		    if( $v==$id ) unset( $category[$k] );
		  }
			
		  $categories = implode( ",", $category );
		  mysql_query( "UPDATE al_site SET categories='$categories' WHERE login='{$site['login']}' LIMIT 1" );
		}
	  }
	
	  $notice = "Category successfully edited";
	}
  }
  
  $res_cat = mysql_query( "SELECT * FROM al_cat WHERE id=$id LIMIT 1" );
  if( mysql_num_rows($res_cat)==0 ) fatalerr( "Error! Could not find this category in the database" );
  $cat = mysql_fetch_array( $res_cat );

?>

<html>
<head>
<link rel="stylesheet" href="main.css">
</head>
<body>
<? showmessage(); ?>
<form method="post" action="<? echo($PHP_SELF); ?>">
<input type="hidden" name="submitted" value="editcat">
<input type="hidden" name="id" value="<? echo($id); ?>">
  <table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
  <tr>
    <td>
        <table cellpadding='4' cellspacing='1' border='0' width='100%'>
          <tr class='tblhead'>
            <td colspan='2'><font color="#FFFFFF" size="1">EDIT CATEGORY</font></td>
          </tr>
          
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Category Name</b><br>
              <font size="1">A description name of the category.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="name" size="35" value="<? echo($cat['name']); ?>" maxlength="32">
              </td>
          </tr>  
<?
  $res_site = mysql_query( "SELECT * FROM al_site ORDER BY name" );

  if( mysql_num_rows($res_site)>0 ):
?>
          <tr bgcolor="#F5F5F5">
            <td width="65%" valign="top">
              <p><b>Sites Accepting Category</b><br>
              <font size="1">Select which sites accept the category. When a site doesn't accept a category,  referrers under this category don't see this site and, if they happen to send hits to this site, they are not counted.</font></p>
            </td>
            <td width="35%">
<?
    for( $i=0; $i<mysql_num_rows($res_site); $i++ )
    {
      $site = mysql_fetch_array( $res_site );
	  
	  // get the category array for this site
	  $category = explode( ",", $site['categories'] );
	  
      echo( "<input type='checkbox' name='sitelogin[]' value='{$site['login']}'" );
	  if( in_array($cat['id'],$category) ) echo(" checked" );
	  echo( ">{$site['name']}<br>" );
    }
?>
            </td>
          </tr>
		  
<? endif; ?>

          <tr bgcolor="#F5F5F5">
            <td width="65%"><b>Selectable?</b><br>
              <font size="1">Do you want referres to be able to select this category when the site they come from accept it? You may select No if you want to have a special category for some referrers.</font></td>
            <td width="35%">
              <input type="radio" name="selectable" value="1" <? if($cat[selectable]) echo(" checked"); ?>>
              Yes 
              <input type="radio" name="selectable" value="0" <? if(!$cat[selectable]) echo(" checked"); ?>>
              No 
              </td>
          </tr>
        </table>
    </td>
  </tr>
</table>
<table cellpadding='4' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
  <tr>
    <td align="center">
        <input type="submit" value="  Edit Category  " name="submit">
      </td>
  </tr>
</table>
</form>
<BR>

<?
  $res_cat = mysql_query( "SELECT * FROM al_cat WHERE name!=''" );

  // only show if we can move to another category
  if( mysql_num_rows($res_cat)>1 ):
?>

<form method="post" action="<? echo($PHP_SELF); ?>">
<input type="hidden" name="submitted" value="delcat">
<input type="hidden" name="id" value="<? echo($id); ?>">
  <table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
    <tr>
      <td>
        <table cellpadding='4' cellspacing='1' border='0' width='100%'>
          <tr class='tblhead'>
            <td colspan='2'><font color="#FFFFFF" size="1">DELETE CATEGORY</font></td>
          </tr>
          
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>New Category</b><br>
              <font size="1">Where should the referrers under this category be transfered?</font></p>
            </td>
            <td width="35%">
              <select name="newcategory">

<? 		  
  $res_cat = mysql_query( "SELECT * FROM al_cat WHERE name!='' AND id!=$id ORDER BY name" );

  while( $cat = mysql_fetch_array( $res_cat ) )
  {
	echo( "<option value='{$cat['id']}'>{$cat['name']}</option>" );
  }
?>

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
        <input type="submit" value=" Delete Category " name="delete">
      </td>
    </tr>
  </table>
</form>

<? endif; ?>

</body>
</html>
