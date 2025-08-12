<?php
	Require("Includes/i_Includes.php");
	DB_OpenDomains();
	DB_OpenImageGallery();
	INIT_LoginDetect();
	
	$iImageUnq		= Trim(Request("iImageUnq"));
	$iGalleryUnq	= Trim(Request("iGalleryUnq"));
	
	Main();
	
	If ( isset($_SERVER['HTTP_REFERER_http']) ){
		header( 'location:' . $_SERVER["HTTP_REFERER_http"] );
	}ElseIf ( isset($_SERVER['HTTP_REFERER']) ){
		header( 'location:' . $_SERVER["HTTP_REFERER"] );
	}Else{
		If ( ( $iImageUnq != "" ) && ( $iGalleryUnq != "" ) ) {
			header( 'location:/PHPJK/ImageDetail.php?iImageUnq=' . $iImageUnq . '&iGalleryUnq=' . $iGalleryUnq );
		}Else{
			header( 'location:/PHPJK/' );
		}
	}
	DB_CloseDomains();
	
	
	//************************************************************************************
	//*																					*
	//*	This updates the rating and returns them to the page they came from.			*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		Global $iImageUnq;
		Global $iGalleryUnq;
		Global $iLoginAccountUnq;

		$iRating	= Request("iRating");	// this is what the person is giving the image
		
		If ( ( $iImageUnq != "" ) && ( $iGalleryUnq != "" ) )
		{			
			$sQuery			= "SELECT Rating, NumRaters FROM ImagesInGallery (NOLOCK) WHERE ImageUnq = " . $iImageUnq . " AND GalleryUnq = " . $iGalleryUnq;
			$rsRecordSet	= DB_Query($sQuery);
			If ( $rsRow = DB_Fetch($rsRecordSet) )
			{
				$iCurRating	= Trim($rsRow["Rating"]) + $iRating;
				$iNumRaters	= Trim($rsRow["NumRaters"]) + 1;
			}Else{
				$iCurRating	= $iRating;
				$iNumRaters	= 1;
			}
			
			DB_Update ("UPDATE ImagesInGallery SET Rating = " . $iCurRating . ", NumRaters = " . $iNumRaters . " WHERE ImageUnq = " . $iImageUnq . " AND GalleryUnq = " . $iGalleryUnq);
			DB_Insert ("INSERT INTO IGRaters VALUES (" . $iImageUnq . ", " . $iLoginAccountUnq . ", " . $iRating . ", GetDate(), " . $iGalleryUnq . ")" );
		}
	}
	//************************************************************************************
?>