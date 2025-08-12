<?
	$docs_dirpath = "$DOCUMENT_ROOT/";

	require_once($docs_dirpath."essential/dbc_essential.php");
	require_once($docs_dirpath."essential/globalfunctions.php");

	if(!$csr)
	$csr = new ComFunc();
	
	$i = 0;
	while(!$row_random && $i < 3)
	{
		$result     = getOneRandomPhoto();
		$row_random = mysql_fetch_array( $result );
		$i++;
	}

	if(!$row_random[0])
	NoRadomPhotoFound();
	
	else
	{
	$size   = GetImageSize( "$Config_mainurl/$Config_datapath/$row_random[uid]/tb_$row_random[pname]" );
      $picurl = "<img src='$Config_mainurl/$Config_datapath/$row_random[uid]/tb_$row_random[pname]' $size[3] border='0'>";
	$fsize  = $csr->calcSpaceVal( ($row_random[o_used]+$row_random[i_used]+$row_random[t_used]) );
	$auto_tb_size = $size[1] + 200;

	if(!$row_random[pmsg])
	$row_random[pmsg] = "...";

	else
	{
		if(strlen($row_random[pmsg]) > 40)
		$row_random[pmsg] = substr($row_random[pmsg], 0, 40)."...";
	}

	if(strlen($row_random[aname]) > 15)
	$row_random[aname] = substr($row_random[aname], 0, 12)."...";

$random_photo =<<<__HTML_END_
<style type="text/css">
<!--
.ts { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 8pt; }
.tn { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10pt; }

.album_link { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 8pt; text-decoration: none; }
.album_link:hover { text-decoration: overline; }
//-->
</style>

      <table width="$auto_tb_size" border="0" cellspacing="1" cellpadding="4" align="center" bgcolor="#EEEEEE" class="tn">
        <tr class="ts"> 
          <td colspan="2" bgcolor="#EEEEEE" align='right'><b><font color='#003366'>$strPhotoRandomName</font></b></td>
	  </tr>
        <tr> 
          <td width="110" align="center">
		<a href="$Config_mainurl/showpic.php?uuid=$row_random[uid]&aid=$row_random[aid]&pid=$row_random[pid]">$picurl<p>$strView</a>
	    </td>
          <td height="2" bgcolor="#FFFFFF" valign="top"> 
            <table width="100%" border="0" cellspacing="0" cellpadding="2" class="ts">
              <tr><td colspan="2">&nbsp;</td></tr>
		  <tr valign='top'><td>$strAlbum&nbsp;</td>
		  <td><a href="$Config_mainurl/showalbum.php?uuid=$row_random[uid]&aid=$row_random[aid]" class='album_link'>$row_random[aname]</a></td></tr>
		  <tr valign='top'><td>$strOwner&nbsp;</td>
              <td><a href="$Config_mainurl/showprofile.php?uuid=$row_random[uid]" class='album_link'>$row_random[uname]</a></td></tr>
		  <tr valign='top'><td>$strSize&nbsp;</td><td>$fsize</td></tr>
            </table>
          </td>
        </tr>
        <tr class="ts"> 
          <td colspan="2" bgcolor="#F8F8F8" align='center'>$row_random[pmsg]</td>
	  </tr>
      </table>
__HTML_END_;

	echo($random_photo);
	}


function getOneRandomPhoto()
{
global $tbl_pictures, $tbl_albumlist, $tbl_userinfo;

	$result = queryDB( "SELECT p.*,a.aname,a.uid,u.uname FROM $tbl_pictures as p, $tbl_albumlist as a, $tbl_userinfo as u WHERE p.aid=a.aid && a.private!='1' && a.uid=u.uid && p.aid!='0' ORDER BY RAND() LIMIT 0,1" );

	return( $result );
}

function NoRadomPhotoFound()
{
	global $strPhotoRandomNone;
	echo("<div align='center'>$strPhotoRandomNone</div>");
}

?>