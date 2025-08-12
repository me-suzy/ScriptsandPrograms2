<?php
	Require("../Includes/i_Includes.php");
	Require("../Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_OPEN.php");
	Main();
	Require("../Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_CLOSE.php");
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		Global $iTableWidth;
		
		Echo "<br>";
		G_STRUCTURE_HeaderBar("SearchImagesHead.gif", "", "", "Galleries");
		Echo "<table width=" . $iTableWidth . " cellpadding = 1 cellspacing = 0 border = 0 class='Table1_Boxed'><tr><td valign=top align=center>";
		Echo "<br>Preparing Results<br><br>Please Wait...<br><br>";
		Echo "</td></tr></table><br>";

		SearchConf();	// Display the search results
	}
	//************************************************************************************

	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function SearchConf()
	{
		Global $sKeywords;
		
		$x				= 0;
		$iTtlLeaf		= 1;
		$iTtlParent		= 1;
		
		// Check the form for completeness. If not complete, then change iSearchAction to "DisplayResults"
		$sKeywords		= Request("sKeywords");
		
		If ( strtoupper(Trim($sKeywords)) == "PHP_JK_NEW" ) {
			NewImageSearch();
		}Else{
			NormalSearch();
		}
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This searches for the newest images												*
	//*																					*
	//************************************************************************************
	Function NewImageSearch()
	{
		Global $iLoginAccountUnq;
		Global $aVariables;
		Global $aValues;

		$iSearchID = 0;
		
		DB_Insert ("INSERT INTO IGSearches (AccountUnq,DateChanged) VALUES (" . $iLoginAccountUnq . ", GetDate())");

		// get the just inserted SearchID for use in the IGSearchResults table
		$rsRecordSet = DB_Query("SELECT @@IDENTITY");
		If ( $rsRow = DB_Fetch($rsRecordSet) )
			$iSearchID = $rsRow[0];
			
		$sNewDays = Trim(DOMAIN_Conf("IMAGEGALLERY_NEWIMAGE_DAYS"));
		If ( ! is_numeric($sNewDays) ) 
			$sNewDays = 2;

		$dLastDate = DateAdd("d", -$sNewDays, time());	// search for the past IMAGEGALLERY_NEWIMAGE_DAYS days
		DB_Insert ("INSERT INTO IGSearchResults SELECT " . $iLoginAccountUnq . ", IG.ImageUnq, IG.GalleryUnq, G.CategoryUnq, " . $iSearchID . " FROM ImagesInGallery IG (NOLOCK), Galleries G (NOLOCK) WHERE (IG.AddDate >= '" . DOMAIN_FormatDate($dLastDate, "L") . "') AND IG.GalleryUnq = G.GalleryUnq ORDER BY IG.AddDate Desc");
		
		If ( ! ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") )
		{
			// now check each gallery that's been added to see if the current user can access that gallery or not. delete galleries they cannot
			$sQuery			= "SELECT DISTINCT SR.GalleryUnq, G.AccountUnq FROM IGSearchResults SR (NOLOCK), Galleries G (NOLOCK) WHERE SR.AccountUnq = " . $iLoginAccountUnq . " AND SR.GalleryUnq = G.GalleryUnq";
			$rsRecordSet	= DB_Query($sQuery);
			While ( $rsRow = DB_Fetch($rsRecordSet) )
			{
				$iGalleryUnq = $rsRow["GalleryUnq"];
				If ( ! G_ADMINISTRATION_AccessLocked($iGalleryUnq, $rsRow["AccountUnq"]) )
					DB_Update ("DELETE FROM IGSearchResults WHERE GalleryUnq = " . $iGalleryUnq . " AND AccountUnq = " . $iLoginAccountUnq);
			}
		}
		?>
		<script language='JavaScript1.2' type='text/javascript'>
	
			document.location = "DisplayResults.php?<?=DOMAIN_Link("G")?>&iSearchID=<?=$iSearchID?>&sKeywords=<?=URLEncode(Request("sKeywords"))?>";

		</script>
		<?php 
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This searches normally															*
	//*																					*
	//************************************************************************************
	Function NormalSearch()
	{
		Global $iLoginAccountUnq;
		Global $aVariables;
		Global $aValues;
		Global $sKeywords;
		
		$sError = "";
		
		If ( $sKeywords == "" )
			$sError = $sError . "Please enter your keywords.<br>";
		
		If ( $sError != "" ) {
			DOMAIN_Message($sError, "ERROR");
		}Else{
			// Form the SQL Select statement			
			DB_Insert ("INSERT INTO IGSearches (AccountUnq,DateChanged) VALUES (" . $iLoginAccountUnq . ", GetDate())");
			
			$rsRecordSet = DB_Query("SELECT @@IDENTITY");
			If ( $rsRow = DB_Fetch($rsRecordSet) )
				$iSearchID = $rsRow[0];
			
			$SearchsqlText = "INSERT INTO IGSearchResults SELECT " . $iLoginAccountUnq . ", IG.ImageUnq, IG.GalleryUnq, G.CategoryUnq, " . $iSearchID . " FROM ImagesInGallery IG (NOLOCK), Galleries G (NOLOCK), Images I (NOLOCK) WHERE IG.GalleryUnq = G.GalleryUnq AND IG.ImageUnq = I.ImageUnq ";
		
			// Parse the keywords out
			$sKeywords = str_replace(" ,", ",", $sKeywords);
			$sKeywords = str_replace(", ", ",", $sKeywords);
			$sKeywords = str_replace("\"", "", $sKeywords);
			$sKeywords = SQLEncode($sKeywords) . ",";
			
			$iEndPos = strpos($sKeywords, ",");
			$iStartPos = 1;
			If ( $iEndPos > 0 )
			{
				While ( $iEndPos > 0 )
				{
					If ( $iStartPos == 1 ) {
						$SearchsqlText = $SearchsqlText . "AND ((I.Comments LIKE '%" . substr($sKeywords, $iStartPos-1, $iEndPos - $iStartPos+1) . "%' OR I.Image LIKE '%" . substr($sKeywords, $iStartPos-1, $iEndPos - $iStartPos+1) . "%' OR I.Keywords LIKE '%" . substr($sKeywords, $iStartPos-1, $iEndPos - $iStartPos+1) . "%' OR I.Title LIKE '%" . substr($sKeywords, $iStartPos-1, $iEndPos - $iStartPos+1) . "%')";
					}Else{
						$SearchsqlText = $SearchsqlText . " OR (I.Comments LIKE '%" . substr($sKeywords, $iStartPos-1, $iEndPos - $iStartPos+1) . "%' OR I.Image LIKE '%" . substr($sKeywords, $iStartPos-1, $iEndPos - $iStartPos+1) . "%' OR I.Keywords LIKE '%" . substr($sKeywords, $iStartPos-1, $iEndPos - $iStartPos+1) . "%' OR I.Title LIKE '%" . substr($sKeywords, $iStartPos-1, $iEndPos - $iStartPos+1) . "%')";
					}
					If ( $iEndPos < strlen($sKeywords) )
					{
						$iStartPos = $iEndPos + 1;
						$iEndPos = strpos($iStartPos, $sKeywords, ",");
					}Else{
						$iEndPos = 0;
					}
				}
			}Else{
				// there's just one word with no spaces
				$SearchsqlText = $SearchsqlText . "AND ((I.Comments LIKE '%" . substr($sKeywords, $iStartPos-1, $iEndPos - $iStartPos+1) . "%' OR I.Image LIKE '%" . substr($sKeywords, $iStartPos-1, $iEndPos - $iStartPos+1) . "%' OR I.Keywords LIKE '%" . substr($sKeywords, $iStartPos-1, $iEndPos - $iStartPos+1) . "%' OR I.Title LIKE '%" . substr($sKeywords, $iStartPos-1, $iEndPos - $iStartPos+1) . "%')";
			}
			$SearchsqlText = $SearchsqlText . ") ORDER BY G.CategoryUnq, G.GalleryUnq, I.Image";

			DB_Insert ($SearchsqlText);
			
			If ( ! ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) 
			{
				// now check each gallery that's been added to see if the current user can access that gallery or not. delete galleries they cannot
				$sQuery			= "SELECT DISTINCT SR.GalleryUnq, G.AccountUnq FROM IGSearchResults SR (NOLOCK), Galleries G (NOLOCK) WHERE SR.AccountUnq = " . $iLoginAccountUnq . " AND SR.GalleryUnq = G.GalleryUnq";
				$rsRecordSet	= DB_Query($sQuery);
				While ( $rsRow = DB_Fetch($rsRecordSet) )
				{
					$iGalleryUnq = $rsRow["GalleryUnq"];
					If ( ! G_ADMINISTRATION_AccessLocked($iGalleryUnq, $rsRow["AccountUnq"]) )
						DB_Fetch ("DELETE FROM IGSearchResults WHERE GalleryUnq = " . $iGalleryUnq . " AND AccountUnq = " . $iLoginAccountUnq);
				}
			}
			?>
			<script language='JavaScript1.2' type='text/javascript'>
		
				document.location = "DisplayResults.php?<?=DOMAIN_Link("G")?>&iSearchID=<?=$iSearchID?>&sKeywords=<?=URLEncode(Request("sKeywords"))?>";

			</script>
			<?php 
		}
	}
	//************************************************************************************
?>