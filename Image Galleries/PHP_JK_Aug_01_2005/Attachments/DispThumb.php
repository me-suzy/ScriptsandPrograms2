<?php
	Require("../Includes/i_Includes.php");
	DB_OpenDomains();
	DB_OpenImageGallery();
	INIT_LoginDetect();

	// make sure the referrer is one of the domains in the database
	$bDisplay				= False;
	$sGalleryDomainCheck	= strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_DOMAINCHECK")));
	
	// get the domain name of the referrer
	If ( isset($_SERVER['HTTP_REFERER_http']) )
	{
		$sTemp = $_SERVER['HTTP_REFERER_http'];
	}ElseIf ( isset($_SERVER['HTTP_REFERER']) )
	{
		$sTemp = $_SERVER['HTTP_REFERER'];
	}Else{
		$sTemp = "NONE";
	}
	If ( strrpos($sTemp, "/") == strlen($sTemp)-1 )		// if there is a trailing "/" remove it
		$sTemp = substr($sTemp, 0, strlen($sTemp)-1);
		
	$iPos = strpos($sTemp, "/");
	If ( $iPos !== False )
	{
		$iPos	= strpos($sTemp, "/", $iPos+1);	// get the second one
		$sTemp	= substr($sTemp, $iPos+1, strlen($sTemp)-($iPos+1));
		$iPos	= strpos($sTemp, "/");
		If ($iPos !== False ) {
			$sTemp	= substr($sTemp, 0, $iPos);
		}Else{
			$sTemp = "NONE";
		}
	}
	// End getting the domain
	
	If ( $sGalleryDomainCheck == "NO" ) {
		// do it this way so ANYTHING Else will perform the check
		$bDisplay = TRUE;
	}Else{
		$sQuery			= "SELECT DomainUnq FROM DomainInfo (NOLOCK) WHERE (Domain = '" . $sTemp . "' OR Domain = '" . "WWW." . $sTemp . "')";
		$rsRecordSet	= DB_Query($sQuery);
		If ( $rsRow = DB_Fetch($rsRecordSet) )
			$bDisplay = TRUE;
	}

	If ( $bDisplay )
	{
		// it's ok - we found this IP address in the list of this Domains IP servers addresses
		ob_start("OutputFile");
		header( 'ContentType:image/JPEG' );
		ob_end_flush();		// the flush is only required on *nix boxes.
		DB_CloseDomains();
		exit;
		//OutputFile();
		//exit;
	}Else{
		echo "Unable to display images from bookmarks or other websites.";
		DB_CloseDomains();
	}
	
	
	//************************************************************************************
	//*																					*
	//*	ob_start calls this and adds everything it returns to the header.				*
	//*																					*
	//************************************************************************************
	Function OutputFile()
	{
		Global $sGalleryPath;

		$sAccountUnq		= Trim(Request("sAccountUnq"));
		$iGalleryUnq		= Trim(Request("iGalleryUnq"));
		$sThumbnail			= Trim(Request("sThumbnail"));

		$sFilePath	= $sGalleryPath . "/" . $sAccountUnq . "/" . $iGalleryUnq . "/Thumbnails/" . $sThumbnail;
		$sFilePath	= str_replace("\\", "/", $sFilePath);
		$sFilePath	= str_replace("//", "/", $sFilePath);
		$sThumbnail	= strtoupper($sThumbnail);

		If ( ( file_exists($sFilePath) ) && ( $sThumbnail != "" ) && ( ( strpos($sThumbnail, ".JPG") !== false ) || ( strpos($sThumbnail, ".GIF") !== false ) || ( strpos($sThumbnail, ".PNG") !== false ) || ( strpos($sThumbnail, ".BMP") !== false ) ) )
		{
			$handle = fopen($sFilePath, "rb");
			Return fread($handle, filesize($sFilePath));
		}Else{
			$sFilePath = DOMAIN_Conf("PHP_JK_WEBROOT") . DOMAIN_Conf("IMAGEGALLERY_MISSING_THUMBNAIL");
			$sFilePath	= str_replace("\\", "/", $sFilePath);
			$sFilePath	= str_replace("//", "/", $sFilePath);
			If ( file_exists($sFilePath) )
			{
				$handle = fopen($sFilePath, "rb");
				Return fread($handle, filesize($sFilePath));
			}
		}
	}
	//************************************************************************************
?>