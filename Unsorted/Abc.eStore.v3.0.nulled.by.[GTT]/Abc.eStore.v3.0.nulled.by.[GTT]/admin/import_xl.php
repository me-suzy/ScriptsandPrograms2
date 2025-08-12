<?php

//------------------------------------------------------------------------
// This script is a part of ABC eStore
//------------------------------------------------------------------------

session_start();

//
// Global includes

include ("config.php");
include ("settings.inc.php");
$url = "index";
include_once ("header.inc.php");

//
// if session is not registered go to login page

if( !isset( $_SESSION["admin"] ) && !isset( $_SESSION["demo"] ) )
	abcPageExit("<Script language=\"javascript\">window.location=\"login.php\"</script>");

echo "<h2>".$lng[627]."</h2>";

if ( $_SESSION['demo'] )
echo "<p><font color='red'>".$lng[628]."</font></p>";

//
// This script utility functions

require_once ("import_xl_util.php");

//
// Exclusive access maintenance

if( $lockFile->IsLocked() && !$lockFile->IsOwnLock() )
{
	$lockIP = $lockFile->GetLockData();
	
	if( $_SERVER['REQUEST_METHOD'] == 'POST' && !$_SESSION["demo"] )
		if( abcRemoveLock() )
		{
			echo "<script language=\"javascript\">window.location=\"import_xl.php\"</script>";
			abcPageExit();
		}
		else
			abcPageError($lng[629]);

echo <<<BLOCK
	<p>$lng[630]
	
	<p>$lng[631] <b>$lockIP</b>.
	$lng[632]
	
	<p>$lng[633]
	
	<form action="" method="post">
	<input type="submit" name="submit" value="$lng[634]">
	</form>
BLOCK;

	abcPageExit();
}

//
// Handle file upload

if( $_SERVER['REQUEST_METHOD'] == 'POST' && !$_SESSION["demo"] )
{
	if( isset( $_POST['submitUpload'] ) )
	{
		//
		// Upload new file
		
		$file = $_FILES['excelFile'];
		if( !is_uploaded_file( $file['tmp_name'] ) )
			abcPageError($lng[635]);
		elseif( $file['size'] <= 0 )
			abcPageError($lng[636]);
		elseif( abcStartLock( $file ) ) {
			if( !abcParseWorksheets() )
				abcPageError($lng[637]);
			abcPageActionComplete($lng[638]);
		}
		else
			abcPageError($lng[639]);
	
			
	}
	elseif( isset( $_POST['submitRemove'] ) )
		if( abcRemoveLock() )
			abcPageActionComplete($lng[640]);
		else
			abcPageError($lng[641]);


}

//
// Getting basic uploaded file info

if( file_exists( 'xlupload/uploaded.xls' ) )
{
	$fileSize = filesize( 'xlupload/uploaded.xls' );
	$uploadedFile = '<a href="xlupload/uploaded.xls">xlupload/uploaded.xls</a>';
	$uploadedFile .= "&nbsp;&nbsp;&nbsp;[ Size: $fileSize ]";
	$uploadedFile .= '&nbsp;&nbsp;&nbsp;<input name="submitRemove" type="submit" id="submitRemove" value="'.$lng[642].'">';

	
	// Info obout worksheets
	
	if( !$res = abcLoadWorksheets() )
		abcPageError($lng[643]);
	
	$cat = abcFetchCategoryList();
	if(is_array($cat)) 	
		foreach ($cat as $c)
			$cat_select .= "<option value='$c[1]'>$c[0]</option>";
		
	
	$worksheets=""; // Inserting select operator
	
	foreach ($res as $r) {
				
		$worksheets .= "<tr><td valign='top'><b>" . $r[0] . "</b></td>";  
		
		$r[2]=(int)$r[2];
		
		if ($r[1] == 1 ) {
			
			$worksheets .= "<td valign='top'><select name='s[$r[2]]'><option value='-1'>----------</option>";
			$worksheets .= $cat_select;
			$worksheets .= "</select></td>";
		}
		else 	$worksheets .= "<td valign='top'>empty</td>";
		
		$worksheets .= "</tr>";
	}
	
}
else
	$uploadedFile = '<b>'.$lng[644].'</b>';


//
// Upload form

echo <<<UPLOADFORM
<br>
<table border="1" bordercolor="#e6e6e6" cellpadding="4" cellspacing="0" width="95%">
	<tr bgcolor="#e0e0e0">
		<td align="center" width="50%"><b>$lng[647]</b></td>
	</tr>
	<tr>
		<td><br>
		<form action="" method="post" enctype="multipart/form-data" name="uploadFile">
			<p>Uploaded file: $uploadedFile
			</p>
			<p>
				<input name="excelFile" type="file" id="excelFile" size="50" class="file">
				<input name="submitUpload" type="submit" id="submitUpload" value="$lng[645]" class="submit">
			</p>
		</form>
		</td>
	</tr>
</table>
UPLOADFORM;


if( file_exists( 'xlupload/uploaded.xls' ) )
echo <<<WSINFO
<br><br>
<table border="1" bordercolor="#e6e6e6" cellpadding="4" cellspacing="0" width="95%">
	<tr bgcolor="#e0e0e0">
		<td align="center" width="50%"><b>$lng[648]</b></td>
	</tr>
	<tr>
		<form action="import_xl_proc.php" method="post">
		<td align='center'><br>
		<table width="100%" border='0'>
		<tr><td><i>$lng[649]</td><td><i>$lng[650]</td></tr>
		$worksheets
		<tr><td colspan='1' align="center"></td><td><br><input type="submit" name="submit" value="$lng[646]" class="submit"></td></tr>
		</table><br>
		</td>
		</form>
	</tr>
	
</table>
WSINFO;

include ("footer.inc.php");

?>