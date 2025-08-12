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

  function loadrefarea()
  {
    $res_refarea = mysql_query( "SELECT * FROM al_refarea LIMIT 1" );
	if( !mysql_num_rows($res_refarea) ) return false;
    return mysql_fetch_array( $res_refarea );
  }
  
  function checkrefarea( $refarea )
  {
    while( list($k, $v) = each($refarea) )
	{
	  if( $v=="" ) return false;
	}
	return true;
  }
  
  function saverefarea( $refarea )
  {
    while( list($k, $v) = each($refarea) )
	{
	  $v = addslashes( $v );
	  mysql_query( "UPDATE al_refarea SET $k='$v' LIMIT 1" ); 
	}
  }

  if( $submitted=="customrefarea" )
  {
    if( checkrefarea($refarea) )
	{
      saverefarea( $refarea );
	  $notice = "Referrers Area properties successfully edited";
	}
	else
	{
	  $notice = "Some fields are missing or incorrect!";
	}
  }

  $refarea = loadrefarea( $id );
  
?>

<html>
<head>
<link rel="stylesheet" href="main.css">
</head>
<body>
<? showmessage(); ?>
<form method="post" action="<?=$PHP_SELF?>">
<input type="hidden" name="submitted" value="customrefarea">
<table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor="#9999CC">
  <tr>
    <td>
        <table cellpadding='4' cellspacing='1' border='0' width='100%'>
          <tr> 
            <td colspan='2'><font color="#FFFFFF" size="1">CUSTOMIZATION</font></td>
          </tr>
          <tr bgcolor="#F5F5F5"> 
            <td width="65%" valign="top"> 
              <p><b> Header Code</b><br>
                <font size="1">The header will appear immediately above the page-specific code of the referrer's area. You may include all you want in it, even images as long as you make sure they will be linked correctly.</font></p>
            </td>
            <td width="35%">
              <textarea name="refarea[header]" cols="44" rows="7"><?=$refarea[header]?></textarea>
              </td>
          </tr>
          <tr bgcolor="#F5F5F5"> 
            <td width="65%" valign="top"> 
              <p><b>Footer Code</b><br>
                <font size="1">The footer will appear immediately below the page-specific code of the referrer's area. Just like for the footer, include what you want and don't forget to close the HTML tags you may have opened on the header (especially for tables.</font></p>
            </td>
            <td width="35%">
              <textarea name="refarea[footer]" cols="44" rows="7"><?=$refarea[footer]?></textarea>
              </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%" valign="top"><b>Signup Information</b><br>
              <font size="1">The  information will appear above the registration form. You may want to include specific rules that you wish to apply on your link exchanges as well as general information. HTML is allowed.</font></td>
            <td width="35%">
              <textarea name="refarea[reginfo]" cols="44" rows="7"><?=$refarea[reginfo]?></textarea>
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
            <td colspan='2'><font color="#FFFFFF" size="1"> FONT PROPERTIES</font></td>
          </tr>
        
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Font Face</b><br>
              <font size="1">The face of the font. You may include several fonts name separated by a comma in case of an user doesn't have a particular font.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="refarea[mainfontface]" value="<?=$refarea[mainfontface]?>" size="35" maxlength="100">
            </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Font Size</b><br>
                <font size="1">The size of the font. It can be defined in pt (points) or px (pixels).</font></p>
            </td>
            <td width="35%">
            
              <input type="text" name="refarea[mainfontsize]" value="<?=$refarea[mainfontsize]?>" size="35" maxlength="7">
            </td>
          </tr>
        
        
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Main Font Color</b><br>
              <font size="1">The main color used for informations, forms and tables fonts.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="refarea[mainfontcol]" value="<?=$refarea[mainfontcol]?>" size="35" maxlength="7">
            </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Header Font Color</b><br>
              <font size="1">The color  used for the menu as well as for the header of forms/tables.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="refarea[headerfontcol]" value="<?=$refarea[headerfontcol]?>" size="35" maxlength="7">
            </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Highlight Font Color</b><br>
              <font size="1">A bright color to display important messages for the referrer.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="refarea[highlightfontcol]" value="<?=$refarea[highlightfontcol]?>" size="35" maxlength="7">
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
            <td colspan='2'><font color="#FFFFFF" size="1">BACKGROUND COLORS</font></td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b> Body Back Color</b><br>
              <font size="1">The background color of the pages in the referrers area.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="refarea[bodybackcol]" value="<?=$refarea[bodybackcol]?>" size="35" maxlength="7">
            </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b> Area Back Color</b><br>
              <font size="1">The color of the main table within the referrer's area.</font></p>
            </td>
            <td width="35%" bgcolor="#F5F5F5">
              <input type="text" name="refarea[areabackcol]" value="<?=$refarea[areabackcol]?>" size="35" maxlength="7">
            </td>
          </tr>
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Forms Back Color</b><br>
              <font size="1">The background color of the forms/tables in the area.</font></p>
            </td>
            <td width="35%">
              <input type="text" name="refarea[formbackcol]" value="<?=$refarea[formbackcol]?>" size="35" maxlength="7">
            </td>
          </tr>
          
          <tr bgcolor="#F5F5F5">
            <td width="65%">
              <p><b>Forms Front Color</b><br>
              <font size="1">The front color of the forms/tables (for borders and headers)</font></p>
            </td>
            <td width="35%">
              <input type="text" name="refarea[formfrontcol]" value="<?=$refarea[formfrontcol]?>" size="35" maxlength="7">
            </td>
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
        <input type="submit" value="  Edit Properties  " name="update">
      </td>
  </tr>
</table>
</form>
</body>
</html>
