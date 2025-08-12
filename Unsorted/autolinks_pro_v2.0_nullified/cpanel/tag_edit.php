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
  
  if( $submitted=="edittag" && isset($delete) )
  {
    mysql_query( "DELETE FROM al_tag WHERE id=$id" );
	
	// don't update sites to give time to remove tags
	
	header( "Location: tag_list.php?special=delete" );
  }
  elseif( $submitted=="edittag" )
  {
	if( $name=="" || $numlinks=="" || $position=="" || $minhits=="" || $numcolumns=="" || $padding=="" )
 	{
      $notice = "Error! Some required fields are missing!";
    }
	else
	{
  	  $name = addslashes( $name );

	  if( $minhits==0 && ($orderby=="hitsin" || $orderby=="clicks") ) $minhits = 1;
	  if( $numcolumns==0 ) $numcolumns = 1;

  	  // all correct, update tag in database
  	  mysql_query( "UPDATE al_tag SET

				name='$name',
				type='$type',

				orderby='$orderby',
				numlinks='$numlinks',
				position='$position',
				minhits='$minhits',
				category='$category',

				numcolumns='$numcolumns',
				align='$align',
				padding='$padding',
				
				showdesc='$showdesc',
				cssclass='$cssclass',
				fontsize='$fontsize',
				fonttype='$fonttype',
				mouseover='$mouseover' WHERE id=$id" );
				
	  $notice = "Tag successfully edited. The change will be visible next time the site(s) using this tag are updated.";
	}
  }

  $res_tag = mysql_query( "SELECT * FROM al_tag WHERE id=$id" );
  if( !mysql_num_rows($res_tag) ) fatalerr( "Error! No tags under this ID could be found" );
  $tag = mysql_fetch_array( $res_tag );

?>
<html>
<head>
<link rel="stylesheet" href="main.css">
</head>
<body>
<? showmessage(); ?>
<form method="post" name="tagfrm" action="<? echo($PHP_SELF); ?>">
<input type="hidden" name="submitted" value="edittag">
<input type="hidden" name="id" value="<? echo($id); ?>">
  <table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
    <tr>
      <td>
        <table cellpadding='4' cellspacing='1' border='0' width='100%'>
          <tr>
            <td colspan='2'><font color="#FFFFFF" size="1">GENERAL PROPERTIES</font></td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Tag Name</b><br>
              <font size="1">Choose a name  to define all the properties below.  Try to enter an easy-to-remember name so that you know which tag to choose when you want to edit one. For example &quot;Top Referrers on This Site&quot;, &quot;Single Banner&quot;, etc.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="name" size="35" value="<? echo($tag['name']); ?>" maxlength="75">
            </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Tag Type</b><br>
              <font size="1">Text links are a simple link to the site, with the possibility to put the description. To send more hits, you can also use images that have been previously uploaded by the referrers. Be sure enough images have been uploaded by referrers already or blanks will appear.</font></p>
            </td>
            <td width="35%">
              <select name="type">
                <option value="text"<? if($tag['type']=="text") echo(" selected"); ?>>Text Link(s)</option>
                <option value="banner"<? if($tag['type']=="banner") echo(" selected"); ?>>Banner(s) (468x60 Image)</option>
                <option value="button"<? if($tag['type']=="button") echo(" selected"); ?>>Button(s) (88x31 Image)</option>
                <option value="thumb"<? if($tag['type']=="thumb") echo(" selected"); ?>>Thumb(s) (66x100 Image)</option>
              </select>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <br>
  <br>
  <table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
  <tr>
    <td>
        <table cellpadding='4' cellspacing='1' border='0' width='100%'>
          <tr>
            <td colspan='2'><font color="#FFFFFF" size="1">REFERRERS ORDER AND SELECTION</font></td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Order By</b><br>
              <font size="1">Choose how the referrers will be ordered. <i>Referred Clicks</i> represents  the amount of links clicked by visitors referred by another site. The better quality  traffic they send, the higher this number will be.</font></p>
            </td>
            <td width="35%">
              <select name="orderby">
                <option value="hitsin"<? if($tag['orderby']=="hitsin") echo(" selected"); ?>>Hits in (&quot;Top Referrers&quot;)</option>
                <option value="added"<? if($tag['orderby']=="added") echo(" selected"); ?>>Date Added (&quot;New Links&quot;)</option>
                <option value="clicks"<? if($tag['orderby']=="clicks") echo(" selected"); ?>>Referred Clicks (&quot;Top Quality&quot;)</option>
                <option value="name"<? if($tag['orderby']=="name") echo(" selected"); ?>>Name (&quot;All Links&quot;)</option>
                <option value="random"<? if($tag['orderby']=="random") echo(" selected"); ?>>Random (&quot;Random Links)</option>
              </select>
            </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b> Number of Links</b><br>
              <font size="1">The number of text links or images to display. If you want to display all links, set this to a very high number. It will be possible to split the links in columns below.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="numlinks" size="35" value="<? echo($tag['numlinks']); ?>" maxlength="75">
            </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%"><b>Start Position</b><br>
              <font size="1">If you are, for example, separating the top referrers in 2 different lists, you can specify here at what position the links should start being displayed. Note that it starts at 0 so if you want to display the links from 11 to 20, enter 10 here.</font></td>
            <td width="35%">

              <input type="text" name="position" size="35" value="<? echo($tag['position']); ?>" maxlength="75">
            </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Minimum Hits</b><br>
              <font size="1">The minimum of hits that referrers must have sent in the past 24 hours in order to be displayed. You can't use a value under 1 if the referrers are ordered by &quot;hits in&quot; (top referrers) or &quot;referred clicks&quot; (top quality).</font></p>
            </td>
            <td width="35%">
              <input type="text" name="minhits" size="35" value="<? echo($tag['minhits']); ?>" maxlength="75">
              </td>
          </tr>
<?
  if( !multicats() ):

    // only one category, select 0 (no category)
    echo( "<input type='hidden' name='category' value='0'>" );
	
  else:
?>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Category</b><br>
              <font size="1">The category of the referrers to display. Unless you want to split   your referrers in separate categories,  leave this to <i>All Categories</i>.</font></p>
            </td>
            <td width="35%">
              <select name="category">
                <option value='0'<? if( $tag['category']==0 ) echo( " selected" ); ?>>All Categories</option>

<? 		  
  $res_cat = mysql_query( "SELECT * FROM al_cat WHERE name!='' ORDER BY name" );

  for( $i=0; $i<mysql_num_rows($res_cat); $i++ )
  {
    $cat = mysql_fetch_array( $res_cat );
	
	// check if category is accepted in 1+ site
	$res_site = mysql_query( "SELECT * FROM al_site WHERE status=1 AND FIND_IN_SET('{$cat['id']}', categories)>0" );
	
	if( mysql_num_rows($res_site)>0 )
	{
      echo( "<option value='{$cat['id']}'" );
	  if( $cat['id']==$tag['category'] ) echo( " selected" );
	  echo( ">{$cat['name']}</option>" );
	}
  }
?>

              </select>
            </td>
          </tr>
		  
<? endif; ?>
		  
        </table>
    </td>
  </tr>
</table>
  <br>
  <br>
  <table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
    <tr>
      <td>
        <table cellpadding='4' cellspacing='1' border='0' width='100%'>
          <tr>
            <td colspan='2'><font color="#FFFFFF" size="1">TABLE PROPERTIES</font></td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Number of Columns</b><br>
              <font size="1">All images/links are generated in a table. If you want the links to be split into several columns, enter here how many columns you want. Otherwise leave it to 1.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="numcolumns" size="35" value="<? echo($tag['numcolumns']); ?>" maxlength="75">
            </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Cell Padding</b><br>
              <font size="1">Specify the cell padding in pixels. Padding is like margin, it gives you a chance to give more space between the links. This is recommended if you want to display a row of images and you don't want them to be stuck.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="padding" size="35" value="<? echo($tag['padding']); ?>" maxlength="75">
            </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Alignement</b><br>
              <font size="1">How do you want the links/images to be aligned within the columns?</font></p>
            </td>
            <td width="35%">
              <select name="align">
                <option value="left"<? if($tag['align']=="left") echo(" selected"); ?>>Left Justified</option>
                <option value="center"<? if($tag['align']=="center") echo(" selected"); ?>>Center Justified</option>
                <option value="right"<? if($tag['align']=="right") echo(" selected"); ?>>Right Justified</option>
              </select>
            </td>
		  </tr>
        </table>
      </td>
    </tr>
  </table>
  <br>
  <br>
  <table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
    <tr>
      <td>
        <table cellpadding='4' cellspacing='1' border='0' width='100%'>
          <tr>
            <td colspan='2'><font color="#FFFFFF" size="1">FONT &amp; DESCRIPTION</font></td>
          </tr>
          
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Show Description?</b><br>
              <font size="1">Show the description entered by the referrers? Description are  shown next to the link for text links, below for banners, above &amp; below for thumbs. No description will be shown for buttons.</font></p>
            </td>
            <td width="35%">
              <input type="radio" name="showdesc" value="1" <? if($tag['showdesc']) echo(" checked"); ?>>
              Yes 
              <input type="radio" name="showdesc" value="0" <? if(!$tag['showdesc']) echo(" checked"); ?>>
              No </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Font class</b><br>
              <font size="1">If  you use CSS, you can define a class   (for text links and/or descriptions). You'll have to define the CSS class on the site(s) where this tag appear. If you don't know what CSS is,  leave it blank.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="cssclass" size="35" value="<? echo($tag['cssclass']); ?>" maxlength="75">
            </td>
          </tr>
          
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Font Size</b><br>
              <font size="1">The font size of the text (for text links and/or descriptions). Leave blank to   the default size or the one defined in the  CSS class.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="fontsize" size="35" value="<? echo($tag['fontsize']); ?>" maxlength="75">
            </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b> Font Type</b><br>
              <font size="1">The font type of the text  (for text links and/or descriptions). Leave blank to use the default font or the one defined in the CSS class.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="fonttype" size="35" value="<? echo($tag['fonttype']); ?>" maxlength="75">
            </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%"><b>Use Mouseover?</b><br>
              <font size="1">If you enable this, the description of the referrer as well as the number of hits in/out will appear in the status bar when the mouse goes about the text links or images.</font></td>
            <td width="35%">

              <input type="radio" name="mouseover" value="1" <? if($tag['mouseover']) echo(" checked"); ?>>
              Yes 
              <input type="radio" name="mouseover" value="0" <? if(!$tag['mouseover']) echo(" checked"); ?>>
              No </td>
          </tr>
          
          
          
          
        </table>
      </td>
    </tr>
  </table>
  <br>
  <br>
  <table cellpadding='4' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
  <tr>
    <td align="center">
        <input type="submit" value="  Edit Tag  " name="submit">
		<input type="submit" value=" Delete Tag " name="delete" onClick="return confirm('This will erase all data for this tag.\nAre you sure you want to continue?');">
      </td>
  </tr>
</table>
</form>
</body>
</html>
