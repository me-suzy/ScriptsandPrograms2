<?php
	Require("Includes/i_Includes.php");
	Require("Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_OPEN.php");
	Echo "<BR><center>";
	Main();
	Echo "<BR>";
	Require("Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_CLOSE.php");
	
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		Global $bAdmin;
		Global $bCanCreate;
		Global $sCatBGColor;
		Global $iTableWidth;
		Global $iLevel;
		
		$bAdmin			= ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL");
		$bCanCreate		= ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY");
		$sCatBGColor	= $GLOBALS["BGColor1"];	
		$iLevel			= 0;
		?>
		<?php G_STRUCTURE_HeaderBar_ReallySpecific("CategoryMapHead.gif", "", "", "", "Galleries");?>
		<table cellpadding = 5 cellspacing=0 border=0 width=<?=$iTableWidth?> class='TablePage_Boxed'>
			<tr><td>
				<table width=100%>
					<?php DisplayMap(-1);?>
				</table>
			</td></tr>
		</table>
		<?php 
	}
	//************************************************************************************
	
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function DisplayMap($iCategoryUnq)
	{
		Global $iLevel;
		Global $bAdmin;
		Global $sCatBGColor;
		Global $sSiteURL;
		
		// display the galleries in the category "Galleries not in a category"
		If ( $iCategoryUnq == -1 ) 
		{
			Echo "<tr><td bgcolor=" . $sCatBGColor . " colspan=2><img src='Images/Blank.gif' border=0 height=3 width=0><a href='/PHPJK/index.php?iParentUnq=0' class='MediumNav1'>Galleries not in a category</a></td></tr>";
			$iLevel = 1;
			DispGalleries(0);
			$iLevel = 0;
			$iCategoryUnq = 0;
		}
		
		$sQuery			= "SELECT Name, CategoryUnq FROM IGCategories (NOLOCK) WHERE Parent = " . $iCategoryUnq . " ORDER BY Position";
		$rsRecordSet	= DB_Query($sQuery);
		While ( $rsRow = DB_Fetch($rsRecordSet) )
		{
			If ( AnyChildWGallery($rsRow["CategoryUnq"]) ) 
			{
				Echo "<tr><td bgcolor=" . $sCatBGColor . " colspan=2><img src='Images/Blank.gif' border=0 height=3 width=" . ($iLevel * 25 ) . "><a href='" . $sSiteURL . "/index.php?iParentUnq=" . $rsRow["CategoryUnq"] . "' class='MediumNav1'>" . $rsRow["Name"] . "</a></td></tr>";
				$iLevel++;
				// see if there are any galleries and list them.
				DispGalleries($rsRow["CategoryUnq"]);
				DisplayMap($rsRow["CategoryUnq"]);
				$iLevel--;
			}
		}
	}
	//************************************************************************************



	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function DispGalleries($iCategoryUnq)
	{
		Global $bCanCreate;
		Global $iLevel;
		Global $bAdmin;
		Global $iLoginAccountUnq;
		Global $PUBLIC_GALLERIES;
		Global $sSiteURL;
		
		If ( $bAdmin ) {
			// they can see ALL galleries for this domain
			$sQuery = "SELECT DISTINCT G.GalleryUnq, G.AccountUnq, G.Name, G.Position FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE G.CategoryUnq = " . $iCategoryUnq . " AND IG.GalleryUnq = G.GalleryUnq ORDER BY G.Position";
		}ElseIf ( $bCanCreate ) {
			// they can see their and public galleries
			$sQuery = "SELECT DISTINCT G.GalleryUnq, G.AccountUnq, G.Name, G.Position FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE (G.AccountUnq = " . $iLoginAccountUnq . " OR G.Visibility = '" . $PUBLIC_GALLERIES . "') AND G.CategoryUnq = " . $iCategoryUnq . " AND IG.GalleryUnq = G.GalleryUnq ORDER BY G.Position";
		}Else{
			// they can only see public galleries
			$sQuery = "SELECT DISTINCT G.GalleryUnq, G.AccountUnq, G.Name, G.Position FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE G.Visibility = '" . $PUBLIC_GALLERIES . "' AND G.CategoryUnq = " . $iCategoryUnq . " AND IG.GalleryUnq = G.GalleryUnq ORDER BY G.Position";
		}
		$rsRecordSet = DB_Query($sQuery);
		While ( $rsRow = DB_Fetch($rsRecordSet) )
		{
			Echo "<tr>";
			$iGalleryUnq = $rsRow["GalleryUnq"];
			If ( G_ADMINISTRATION_AccessLocked($iGalleryUnq, $rsRow["AccountUnq"]) )
			{
				$sQuery			= "SELECT COUNT(*) FROM ImagesInGallery (NOLOCK) WHERE GalleryUnq = " . $iGalleryUnq;
				$rsRecordSet2	= DB_Query($sQuery);
				If ( $rsRow2 = DB_Fetch($rsRecordSet2) )
					$iNumImages = $rsRow2[0];

				If ( $iNumImages > 0 )
					Echo "<td width=50%><img src='Images/Blank.gif' border=0 height=3 width=" . ($iLevel * 25 ) . "><a href='" . $sSiteURL . "/ThumbnailView.php?iGalleryUnq=" . $iGalleryUnq . "' class='MediumNavPage'>" . $rsRow["Name"] . " (" . $iNumImages . ")</a></td>";
			}

			If ( $rsRow = DB_Fetch($rsRecordSet) )
			{
				$iGalleryUnq = $rsRow["GalleryUnq"];
				If ( G_ADMINISTRATION_AccessLocked($iGalleryUnq, $rsRow["AccountUnq"]) )
				{
					$sQuery			= "SELECT COUNT(*) FROM ImagesInGallery (NOLOCK) WHERE GalleryUnq = " . $iGalleryUnq;
					$rsRecordSet2	= DB_Query($sQuery);
					If ( $rsRow2 = DB_Fetch($rsRecordSet2) )
						$iNumImages = $rsRow2[0];

					If ( $iNumImages > 0 )
						Echo "<td width=50%><img src='Images/Blank.gif' border=0 height=3 width=" . ($iLevel * 25 ) . "><a href='" . $sSiteURL . "/ThumbnailView.php?iGalleryUnq=" . $iGalleryUnq . "' class='MediumNavPage'>" . $rsRow["Name"] . " (" . $iNumImages . ")</a></td>";
				}
			}Else{
				Echo "<td></td>";
			}
			Echo "</tr>";
		}
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This is a recursive function to search for any category or it's children who	*
	//*		have a gallery -- stops at the first gallery it finds and returns true.		*
	//*																					*
	//************************************************************************************
	Function AnyChildWGallery($iCategoryUnq)
	{
		Global $bCanCreate;
		Global $bAdmin;
		Global $iLoginAccountUnq;
		Global $PUBLIC_GALLERIES;
	
		$sQuery = "SELECT CategoryUnq FROM IGCategories (NOLOCK) WHERE Parent = " . $iCategoryUnq;
		$rsRecordSet3 = DB_Query($sQuery);
		If ( DB_NumRows($rsRecordSet3) > 0 )
		{
			While ( $rsRow3 = DB_Fetch($rsRecordSet3) )
			{
				If ( $bAdmin ) {
					// they can see ALL galleries for this domain
					$sQuery = "SELECT G.GalleryUnq FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE G.CategoryUnq = " . $rsRow3["CategoryUnq"] . " AND IG.GalleryUnq = G.GalleryUnq";
				}ElseIf ( $bCanCreate ) {
					// they can see their and public galleries
					$sQuery = "SELECT G.GalleryUnq FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE (G.AccountUnq = " . $iLoginAccountUnq . " OR G.Visibility = '" . $PUBLIC_GALLERIES . "') AND G.CategoryUnq = " . $rsRow3["CategoryUnq"] . " AND IG.GalleryUnq = G.GalleryUnq";
				}Else{
					// they can only see public galleries
					$sQuery = "SELECT G.GalleryUnq FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE G.Visibility = '" . $PUBLIC_GALLERIES . "' AND G.CategoryUnq = " . $rsRow3["CategoryUnq"] . " AND IG.GalleryUnq = G.GalleryUnq";
				}
				DB_Query("SET ROWCOUNT 1");
				$rsRecordSet4 = DB_Query($sQuery);
				DB_Query("SET ROWCOUNT 0");
				If ( $rsRow4 = DB_Fetch($rsRecordSet4) )
					Return True;

				AnyChildWGallery($rsRow3["CategoryUnq"]);
			}
		}Else{
			// no more child categories, so just check the current (leaf) one
			$sQuery = "SELECT G.GalleryUnq FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE G.CategoryUnq = " . $iCategoryUnq . " AND IG.GalleryUnq = G.GalleryUnq";
			DB_Query("SET ROWCOUNT 1");
			$rsRecordSet4 = DB_Query($sQuery);
			DB_Query("SET ROWCOUNT 0");
			If ( $rsRow4 = DB_Fetch($rsRecordSet4) )
				Return True;
		}
		Return False;
	}
	//************************************************************************************
?>