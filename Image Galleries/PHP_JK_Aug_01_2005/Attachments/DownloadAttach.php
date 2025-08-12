<?php
	Require("../Includes/i_Includes.php");
	Global $sImageName;
	
	DB_OpenDomains();
	DB_OpenImageGallery();
	INIT_LoginDetect();
	
	set_time_limit(200000);		// so when people download long videos, etc. it doesn't time-out

	// make sure the referrer is one of the domains in the database
	$bDisplay				= FALSE;
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

//OutputFile();
//exit;

	If ( $bDisplay )
	{
		// it's ok - we found this IP address in the list of this Domains IP servers addresses
		ob_start("OutputFile");
		header( 'Content-Disposition","attachment; filename=\"' . $sImageName . '\"' );
		header( 'ContentType:application/unknown' );
		ob_end_flush();		// the flush is only required on *nix boxes.
		DB_CloseDomains();
		exit;
		//OutputFile();
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
		Global $sImageName;

		$sAccountUnq		= Trim(Request("sAccountUnq"));
		$iGalleryUnq		= Trim(Request("iGalleryUnq"));
		$iImageUnq			= Trim(Request("iImageUnq"));
		$sImageNum			= Trim(Request("sImageNum"));	// this is the number of the Alternate View image to get - rather than the normal image
		
		If ( $iImageUnq != "" )
		{
			If ( $sImageNum == "" ) {
				$sQuery = "SELECT Image, FileType FROM Images (NOLOCK) WHERE ImageUnq = " . $iImageUnq;
			}Else{
				// get the Alternate View image
				$sQuery = "SELECT Image" . $sImageNum . " FROM Images (NOLOCK) WHERE ImageUnq = " . $iImageUnq;
			}
			If ( $GLOBALS["sUseDB"] == "MYSQL" ) {
				// I don't like spending a lot of time on fickle tools -- MySQL requires resetting of the DB connection here -- this is the
				//	fastest (albiet dirty) way
				DB_OpenDomains();
			}
			$rsRecordSet = DB_Query($sQuery);
			If ( $rsRow = DB_Fetch($rsRecordSet) )
			{
				If ( $sImageNum == "" )
				{
					// get the extension - the FileType
					$sExtension = "." . $rsRow["FileType"];
					$sImageName = Trim($rsRow["Image"]);
				}Else{
					$sExtension = ".jpg";
					$sImageName = Trim($rsRow["Image" . $sImageNum]);
				}

				$sFilePath	= $sGalleryPath . "/" . $sAccountUnq . "/" . $iGalleryUnq . "/" . $sImageName;
				$sFilePath	= str_replace("\\", "/", $sFilePath);
				$sFilePath	= str_replace("//", "/", $sFilePath);
				$sFilePath	= str_replace("//", "/", $sFilePath);

				If ( file_exists($sFilePath) ) {
					$handle = fopen($sFilePath, "rb");
					Return fread($handle, filesize($sFilePath));
				}Else{
					Echo "Unable to find the file: " . $sFilePath;
				}
			}			
		}
	}
	//************************************************************************************
?>