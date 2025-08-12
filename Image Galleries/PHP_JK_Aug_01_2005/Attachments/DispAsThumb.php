<?php
	Require("../Includes/i_Includes.php");
	DB_OpenDomains();
	DB_OpenImageGallery();
	INIT_LoginDetect();
	
	ob_start("OutputFile");
	header( 'ContentType:image/JPEG' );
	ob_end_flush();		// the flush is only required on *nix boxes.
	DB_CloseDomains();
	exit;
	
	
	//************************************************************************************
	//*																					*
	//*	ob_start calls this and adds everything it returns to the header.				*
	//*																					*
	//************************************************************************************
	Function OutputFile()
	{
		Global $sGalleryPath;
		
		$sAccountUnq	= Trim(Request("sAccountUnq"));
		$iGalleryUnq	= Trim(Request("iGalleryUnq"));
		$sImage			= Trim(Request("sImage"));
		
		$sFilePath	= $sGalleryPath . "/" . $sAccountUnq . "/" . $iGalleryUnq . "/" . $sImage;
		$sFilePath	= str_replace("\\", "/", $sFilePath);
		$sFilePath	= str_replace("//", "/", $sFilePath);
		
		If ( file_exists($sFilePath) )
		{
			$handle = fopen($sFilePath, "rb");
			Return fread($handle, filesize($sFilePath));
		}
	}
	//************************************************************************************
?>