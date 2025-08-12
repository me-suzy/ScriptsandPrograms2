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
  
  if( isset($name) )
  {
	if( !$name )
 	{
      $notice = "Error! Some fields are incorrect or missing!";
    }
	else
	{
	  // search for 1 free entry
	  $res_cat = mysql_query( "SELECT * FROM al_cat WHERE name='' LIMIT 1" );
	  
	  // no left entry (maximum is 30)
	  if( mysql_num_rows($res_cat)==0 )
	  {
	    $notice = "Sorry, you've already added the maximum of categories";
	  }
	  else
	  {
	    $cat = mysql_fetch_array( $res_cat );

		// add to database
		$name = addslashes( $name );
  	    mysql_query( "UPDATE al_cat SET name='$name', selectable=$selectable WHERE id={$cat[id]}" );

	    // only if a site already exist
	    if( isset($sitelogin) )
	    {
	      // go through all sites in array and add category
		  while( list($k, $v) = each($sitelogin) )
		  {
		    $res_site = mysql_query( "SELECT * FROM al_site WHERE login='$v' LIMIT 1" );
		    $site = mysql_fetch_array( $res_site );
		  
		    $category = explode( ",", $site['categories'] );
		    $category[] = $cat['id'];
		    $categories = implode( ",", $category );
		  
		    mysql_query( "UPDATE al_site SET categories='$categories' WHERE login='$v' LIMIT 1" );
		  }
	    }
	
	    header( "Location: cat_list.php?special=new" );
	  }
	}
  }
  else
  {
    $selectable = true;
  }

?>

<html>
<head>
<link rel="stylesheet" href="main.css">
</head>
<body>
<? showmessage(); ?>
<form method="post" action="<? echo($PHP_SELF); ?>">
<input type="hidden" name="submitted" value="addcat">
  <table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
  <tr>
    <td>
        <table cellpadding='4' cellspacing='1' border='0' width='100%'>
          <tr class='tblhead'>
            <td colspan='2'><font color="#FFFFFF" size="1">ADD NEW CATEGORY</font></td>
          </tr>
          
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Category Name</b><br>
              <font size="1">A description name of the category.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="name" size="35" value="<? echo($name); ?>" maxlength="32">
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
      echo( "<input type='checkbox' name='sitelogin[]' value='{$site['login']}'" );
	  if( !isset($submit) || in_array($site['login'],$sitelogin) ) echo(" checked" );
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
              <input type="radio" name="selectable" value="1" <? if($selectable) echo(" checked"); ?>>
              Yes 
              <input type="radio" name="selectable" value="0" <? if(!$selectable) echo(" checked"); ?>>
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
        <input type="submit" value=" Add New Category " name="submit">
      </td>
  </tr>
</table>
</form>
</body>
</html>
