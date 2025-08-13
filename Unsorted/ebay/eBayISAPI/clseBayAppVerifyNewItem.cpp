/*	$Id: clseBayAppVerifyNewItem.cpp,v 1.21.2.13.2.3 1999/08/04 16:51:27 nsacco Exp $	*/
//
//	File:		clseBayAppAddItem.cc
//
//	Class:		clseBayApp
//
//	Author:		Michael Wilson (michael@ebay.com)/yp
//
//	Function:
//
//
//	Modifications:
//				- 06/14/97 michael/yp	- Created
//				- 09/17/97 poon - accomodate new category selector
//				- 09/18/97 poon - check for dutch/reserve and dutch/private
//				- 29/29/97 wen	- added codes for free relisting
//				- 01/06/99 vicki - aded gallery 
//				- 02/23/99 anoop	- Check to see if the user verification completes properly.
//				- 04/07/99 kaz	- added check for Police Badge T&C page
//				- 04/07/99 kaz  - set the date to 4/16/99
//				- 04/12/99 soc  - added 10 and 14 day auctions
//              - 04/14/99 soc  - comment out 14 day auctions for now
//				- 04/15/99 kaz	- added check for Police Badge T&C page
//				- 05/21/99 nsacco - added Australian dollars to VerifyNewItem
//				- 07/02/99 beth - don't allow adult material in the gallery
//				- 07/08/99 beth - back out above (will be merged from E117_prod_bug_fixes
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//				- 07/27/99 nsacco - Added new params to VerifyNewItem and CheckItemData
//				- 08/03/99 nsacco - Added some bill in native currency code.

#include "ebihdr.h"

extern "C"
{
char *crypt(char *pPassword, char *pSalt);
};

// Used to reference functions in our caller.
// It's probably more "portable" to handle
// this stuff through clsEnvironment.


#define CHECKED(x)	(!strcmp(x,"on"))


// check for firearms listing
bool clseBayApp::CheckForFirearmsListing(int nCategory)
{
	// 02/24/99 Alex Poon
	// Block firearms listing beginning on Sat 2/27/99
	if ((nCategory == 2037) && 
		(clsUtilities::CompareTimeToGivenDate(time(0), 2, 27, 99, 0, 0, 0) >= 0))
	{
		*mpStream <<	"<p>eBay is no longer accepting new listings of firearms or ammunition on the "
						"site. You may still list firearm <b>accessories</b>, such as holsters or scopes. "
						"Appropriate categories in which to list these accessories include "
						"Collectibles:Militaria, Collectibles:Western Americana, or "
						"Miscellaneous:Sporting Goods:Hunting."
						"<p>For more information about the firearms policy, please click "
						"<a href=\""
				  <<	mpMarketPlace->GetHTMLPath()
				  <<	"help/community/png-firearms.html\">"
						"here</a>.";


		return true;
	}

	return false;
}
//
// CheckItemData
//
// Common routine (also used in clseBayAppAddItem.cpp) to
// validate item data.
//
// nsacco 07/27/99 added new params
bool clseBayApp::CheckItemData(	char *pTitle,
								char *pLocation,
								char *pReserve,
								char *pStartPrice,
								char *pQuantity,
								char *pDuration,
								char *pBold,
								char *pFeatured,
								char *pSuperFeatured,
								char *pPrivate,
								char *pDesc,
								char *pPicUrl,
								char *pCategory,
							    char *pMoneyOrderAccepted,
								char *pPersonalChecksAccepted,
								char *pVisaMasterCardAccepted,
								char *pDiscoverAccepted,
								char *pAmExAccepted,
								char *pOtherAccepted,
								char *pOnlineEscrowAccepted,
								char *pCODAccepted,
								char *pPaymentSeeDescription,
								char *pSellerPaysShipping,
								char *pBuyerPaysShippingFixed,
								char *pBuyerPaysShippingActual,
								char *pShippingSeeDescription,
								char *pShippingInternationally,
								char *pShipToNorthAmerica,
								char *pShipToEurope,
								char *pShipToOceania,
								char *pShipToAsia,
								char *pShipToSouthAmerica,
								char *pShipToAfrica,
								int	 siteId,
								int  descLang,
								int  gallery,
								char *pGalleryUrl,
								int  currencyId
							  )
{
	bool		error	= false;
	int			nQuantity;
	clsCategory	*pRealCategory = NULL;
	int			nDuration;
	double		dStartPrice;
	double		dReservePrice;

	double p;
	char		*pI;
	bool		foundNonBlank;
	int			countingIso;

	clsCurrencyWidget currencyWidget(mpMarketPlace, currencyId, 0); // set below
	clsCurrency* cur = mpMarketPlace->GetCurrencies()->GetCurrency(currencyId); // PH 05/05/99
	char decimalSymbol = '.'; // cur->GetDecimalSymbol();				// PH 05/05/99
	//char * pDecimalSymbol;										// PH 05/05/99


	// title is empty?
	if (FIELD_OMITTED(pTitle))
	{
		*mpStream <<	"<h2>"
						"Empty Title"
						"</h2>"
						"You must enter a title for your item. "
						"Please go back and try again.<p>\n ";

		error	= true;
	}

	countingIso = 0;

	// title too long?
	if ( strlen(pTitle) > 45)
	{
		countingIso = clsUtilities::CountIsoInTitle(pTitle);

		if (strlen(pTitle) > 250 || (strlen(pTitle) - (countingIso * 5)) > 45)
		{
			*mpStream <<	"<h2>"
							"Title too long"
							"</h2>"
							"Listing titles are limited to 45 characters."
							" Please go back and shorten your title<p>\n";

			error	= true;
		}
	}

	// All blanks?
	foundNonBlank	= false;
	for (pI	= pTitle;
		 *pI != '\0';
		 pI++)
	{
		if (*pI != ' ')
		{
			foundNonBlank	= true;
			break;
		}
	}

	if (!foundNonBlank)
	{
		*mpStream <<	"<h2>Title blank</h2>"
						"The title you entered was all blank. You must "
						"enter a title for your item. Please go back and try "
						"again."
						"<p>";
		error	= true;
	}


	// Location?
	if (FIELD_OMITTED(pLocation))
	{
		*mpStream <<	"<h2>Location missing </h2>"
						"Your item\'s location was not filled in. The location "
						"field helps buyers determine the shipping cost(s) for "
						"the item, and should always be included. Please go back "
						"and try again."
						"<p>";

		error	= true;
	}
    //Location too long ?
	if (strlen(pLocation) > 254)
	{
		*mpStream <<	"<h2>Location too long</h2>"
						"Your item\'s location can not be more than 254 characters."
						"Please go back and try again."
						"<p>";

		error	= true;
	}

	// All blanks?
	foundNonBlank	= false;
	for (pI	= pLocation;
		 *pI != '\0';
		 pI++)
	{
		if (*pI != ' ')
		{
			foundNonBlank	= true;
			break;
		}
	}

	if (!foundNonBlank)
	{
		*mpStream <<	"<h2>Location blank</h2>"
						"The location you entered was all blank. The location "
						"field helps buyers determine the shipping cost(s) for "
						"the item, and should always be included. Please go back "
						"and try again."
						"<p>";

		error	= true;
	}

	// petra
	clsIntlLocale * pLocale = mpMarketPlace->GetSites()->GetCurrentSite()->GetLocale();	// petra

	// Bad reserve price
	if (!FIELD_OMITTED(pReserve))
	{	
		// PH 05/05/99 added
		// atof can only interpret decimal point - so we replace any other
		// symbol with a point
		// petra: changed to use clsIntlLocale
		pReserve = pLocale->GetNormalizedCurrencyAmount(pReserve);	// petra
		int n = sscanf(pReserve, "%f", &p);
		if (n == 0)
		{
			// PH 04/29/99 changed to work for all currencies
			*mpStream	<< "<h2>Error in reserve price</h2>"
						<< "Please enter only numbers and the decimal symbol. "
						<< "Do not include currency symbols such as $ or ";
			*mpStream	<< cur->GetSymbol();
			*mpStream	<< ". "	
						<< "For relist item, you need update the reserve price. <p> "
						<< "Please go back and check it again. <p>";
			error	= true;
		}

		dReservePrice	= atof(pReserve);

		if (dReservePrice > mpMarketPlace->GetMaxAmount(currencyId))
		{
			*mpStream <<	"<h2>Error in reserve price</h2>"
							"The reserve price of ";

			currencyWidget.SetNativeAmount(dReservePrice); 
			currencyWidget.EmitHTML(mpStream);

			*mpStream <<	" seems to be too large to be legitimate. Please go back "
					  <<	"and check it again."
							"<p>";
			error	= true;
		}

		if (dReservePrice < 0)
		{
			*mpStream <<	"<h2>Error in reserve price</h2>"
							"The reserve prices of ";

			currencyWidget.SetNativeAmount(dReservePrice); 
			currencyWidget.EmitHTML(mpStream);

			*mpStream  <<	" seems to be negative. Please go back and check it again."
							"<p>";
			error	= true;
		}
	}

	// bad or zero starting price?
	if (!FIELD_OMITTED(pStartPrice))
	{
		// PH 05/05/99 added
		// atof can only interpret decimal point - so we replace any other
		// symbol with a point
		// petra changed to use clsIntlLocale
		dStartPrice	= atof(pLocale->GetNormalizedCurrencyAmount(pStartPrice));	// petra
	}

	if (FIELD_OMITTED(pStartPrice) || dStartPrice == 0)
	{
		*mpStream <<	"<h2>"
						"Error in minimum bid"
						"</h2>"
						"You must enter a valid minimum bid. Please go back and try again."
						"<p>";
		error	= true;
	}

	if (!FIELD_OMITTED(pStartPrice))
	{
		if (dStartPrice > mpMarketPlace->GetMaxAmount(currencyId))
		{
			*mpStream <<	"<h2>"
							"Error in minimum bid"
							"</h2>"
							"The minimum bid of ";

			currencyWidget.SetNativeAmount(dStartPrice); 
			currencyWidget.EmitHTML(mpStream);

			*mpStream  <<	" seems to be too large to be legitimate. Please go back and "
							"check it again. "
							"<p>";
			error	= true;
		}

		if (dStartPrice < 0)
		{
			*mpStream <<	"<h2>"
							"Error in minimum bid"
							"</h2>"
							"The minimum bid of ";

			currencyWidget.SetNativeAmount(dStartPrice); 
			currencyWidget.EmitHTML(mpStream);

			*mpStream  <<	" appears to be negative. Please go back and "
							"check it again. "
							"<p>";
			error	= true;
		}	
	}

	// Quantity
	if (!FIELD_OMITTED(pQuantity))
		nQuantity	= atoi(pQuantity);
	else
		nQuantity	= 1;

	if (!FIELD_OMITTED(pQuantity) && 
		(nQuantity < 1 || nQuantity > EBAY_MAX_QUANTITY_AMOUNT))
	{
		*mpStream <<	"<h2>"
						"Error in quantity"
						"</h2>"
						"The quantity was either missing, invalid, or zero. Please go "
						"back and enter a valid quantity."
						"<p>";
		error	= true;
	}

	// Check for featured or superfeatured
	if ((CHECKED(pFeatured)) || (CHECKED(pSuperFeatured)))
	{
		// Let's see if the user can do this
		if ((mpUser) && (!mpMarketPlace->UserCanFeature(mpUser, mpStream)))
		{
			*mpStream <<	"Please go back and remove the featured auction option."
							"<p>";
			error	= true;
		}
	}


	// Check for reserve/dutch
	if ((nQuantity > 1) && (!FIELD_OMITTED(pReserve)) && (dReservePrice > 0))
	{
		*mpStream <<	"<h2>"
						"Invalid combination: Dutch auction with reserve price"
						"</h2>"
						"A Dutch auction cannot have a reserve price. Please go "
						"back and either change the quantity to 1 or remove the "
						"reserve price."
						"<p>";
		error	= true;
	}

	// Check for private/dutch
	if ((nQuantity > 1) && (CHECKED(pPrivate)))
	{
		*mpStream <<	"<h2>"
						"Invalid combination: Private Dutch auction"
						"</h2>"
						"Dutch auctions cannot be private. Please go "
						"back and either change the quantity to 1 or remove "
						"the private auction option."
						"<p>";
		error	= true;
	}

	// Duration
	if (FIELD_OMITTED(pDuration))
	{
		*mpStream <<	"<h2>"
						"No duration"
						"</h2>"
						"The auction duration was no properly transmitted to our server. "
						"If problem is persistant, it may be due to a problem with your "
						"browser or incorrect use of our service. "
						"<p>";
		error	= true;
	}

	nDuration	= atoi(pDuration);

	// for a limited time, offer 14 day auctions
	if ((clsUtilities::CompareTimeToGivenDate(time(0), 12, 16, 98, 0, 0, 0) >= 0) &&
		(clsUtilities::CompareTimeToGivenDate(time(0), 1, 1, 99, 23, 59, 59) <= 0))
	{
		if (/* !mpMarketPlace->CheckDuration(nDuration) */
			nDuration != 5 &&
			nDuration != 3 &&
			nDuration != 7 &&
			nDuration != 10 &&
			nDuration != 14)	// added by Alex 12/17/98

		{
			*mpStream <<	"<h2>"
							"Invalid duration"
							"</h2>"
							"The auction duration was not properly transmitted to our server. "
							"If problem is persistant, it may be due to a problem with your "
							"browser or incorrect use of our service. "
							"<p>";
			error	= true;

		}
	}
	else
	{
		if (/* !mpMarketPlace->CheckDuration(nDuration) */
			nDuration != 5 &&
			nDuration != 3 &&
			nDuration != 7 &&
			nDuration != 10)
			//nDuration != 14) not using 14 day auctions yet, Soc 4/14/99

		{
			*mpStream <<	"<h2>"
							"Invalid duration"
							"</h2>"
							"The auction duration was not properly transmitted to our server. "
							"If problem is persistant, it may be due to a problem with your "
							"browser or incorrect use of our service. "
							"<p>";
			error	= true;

		}
	}

	// Description
	if (FIELD_OMITTED(pDesc))
	{
		*mpStream <<	"<h2>"
						"No description"
						"</h2>"
						"There was no description provided. You must provide a description "
						"for your item. Please go back and try again."
						"<p>";
		
		error	= true;
	}

	// PicURL
	if (!FIELD_OMITTED(pPicUrl) &&
		strncmp(pPicUrl, "http://", 7) != 0 &&
		strncmp(pPicUrl, "HTTP://", 7) != 0 &&
		strncmp(pPicUrl, "ftp://", 6)  != 0 &&
		strncmp(pPicUrl, "FTP://", 6)  != 0)
	{
		*mpStream <<	"<h2>"
						"Error in Picture URL"
						"</h2>"
						"The URL you supplied for a picture of your item: "
				  <<	pPicUrl
				  <<	", does not begin with a valid http reference. "
						"Please go back and check it again."
						"<p>";

		error	= true;
	}

	// Category checks
	if (!FIELD_OMITTED(pCategory))
		pRealCategory	= mpCategories->GetCategory(atoi(pCategory), true);

	if (FIELD_OMITTED(pCategory) || (!pRealCategory))
	{
		*mpStream <<	"<h2>"
						"Invalid category"
						"</h2>"
						"The category was not properly transmitted to our server. "
						"If problem is persistant, it may be due to a problem with your "
						"browser or incorrect use of our service. "
						"<p>";

		error	= true;
	}
	
	// Check for special pricing category and Dutch, this is a quick hack
	// we need something better here
	if (((nQuantity > 1) && pRealCategory->CheckForAutomotiveListing())
		 ||((nQuantity > 1) && pRealCategory->CheckForRealEstateListing()))
	{	
		*mpStream <<	"<h2>"
						"Invalid combination: Dutch auction and Category"
						"</h2>"
						"We are sorry, but eBay does not allow Dutch Auctions in "
						"the category you have selected. Please go back and modify "
						"your listing.  Thank you."
						"<p>";
		error	= true;
	}

/*	if (!CHECKED(pMoneyOrderAccepted) &&
		!CHECKED(pPersonalChecksAccepted) &&
		!CHECKED(pVisaMasterCardAccepted) &&
		!CHECKED(pDiscoverAccepted) &&
		!CHECKED(pAmExAccepted) &&
		!CHECKED(pOtherAccepted) &&
		!CHECKED(pOnlineEscrowAccepted) &&
		!CHECKED(pCODAccepted) &&
		!CHECKED(pPaymentSeeDescription) )
	{
		*mpStream <<	"<h2>"
						"No payment methods. "
						"</h2>"
						"No payment methods specified. Please go back and make your selection."
						"<p>";

		error	= true;
	}
	
	if (!CHECKED(pSellerPaysShipping) &&
		!CHECKED(pBuyerPaysShippingFixed) &&
		!CHECKED(pBuyerPaysShippingActual) &&
		!CHECKED(pShippingSeeDescription))
	{
		*mpStream <<	"<h2>"
						"No shipping terms."
						"</h2>"
						"No shipping terms specified. Please go back and make your selection."
						"<p>";
		error	= true;
	}
	*/
	if ((((CHECKED(pSellerPaysShipping)) && (CHECKED(pBuyerPaysShippingFixed))) ||
		(CHECKED(pSellerPaysShipping)) && (CHECKED(pBuyerPaysShippingActual)))	||
		(CHECKED(pBuyerPaysShippingFixed)) && (CHECKED(pBuyerPaysShippingActual)))
	{
		*mpStream <<	"<h2>"
						"Contradictory shipping terms."
						"</h2>"
						"Contradictory shipping terms specified. Please go back and make your selection."
						"<p>";
		error	= true;
	}

	// nsacco 07/27/99
	// check new shipping terms
	// check that regions were not selected unless siteplusregions
	if (strcmp(pShippingInternationally, "siteplusregions") != 0)	 
	{
		if (CHECKED(pShipToNorthAmerica) ||
			CHECKED(pShipToEurope) ||
			CHECKED(pShipToOceania) ||
			CHECKED(pShipToAsia) ||
			CHECKED(pShipToSouthAmerica) ||
			CHECKED(pShipToAfrica)
			)
		{
		
			*mpStream <<	"<h2>"
							"Contradictory shipping locations."
							"</h2>"
							"Contradictory shipping locations were specified. Individual "
							"shipping regions can not be selected when choosing the single "
							"country only or ship internationally options."
							"<p>";
			error = true;
		}
	}
	else
	{
		// check that a region was selected if siteplusregions
		if (!(CHECKED(pShipToNorthAmerica) ||
			CHECKED(pShipToEurope) ||
			CHECKED(pShipToOceania) ||
			CHECKED(pShipToAsia) ||
			CHECKED(pShipToSouthAmerica) ||
			CHECKED(pShipToAfrica)
			))
		{
		
			*mpStream <<	"<h2>"
							"Missing shipping region"
							"</h2>"
							"The ship to a country and regions option was selected but "
							"no region was selected from the list."
							"<p>";
			error = true;
		}
	}
	// end check new shipping terms

	if (pRealCategory && !pRealCategory->isLeaf())
	{
		*mpStream <<	"<h2>"
						"Invalid category"
						"</h2>"
						"The category is not a leaf category. "
						"<p>";

		error	= true;
	}

	//gallery
	if (gallery != 0)
	{
		// Check that item is not in adult category!
		if ((mpCategories->GetCategory(atoi(pCategory), true))->isAdult())
		{
			*mpStream << "<h2>"
				         "You Can Not Show This Item in the Gallery"
						 "</h2>"
						 "Items in the Adult category can not be shown in the gallery.  "
						 "Please go back and select \"Do not include my item in the Gallery.\"";

			error = true;
		}
		else
		{

			// GalleryURL
			if (!FIELD_OMITTED(pGalleryUrl) &&
				strncmp(pGalleryUrl, "http://", 7) != 0 &&
				strncmp(pGalleryUrl, "HTTP://", 7) != 0
			   )
			{
				*mpStream <<	"<h2>"
								"Error in Your Gallery Image URL"
								"</h2>"
								"The URL you supplied for a picture of your item: "
						  <<	pGalleryUrl
						  <<	", does not begin with a valid http reference. "
								"Please go back and check it again. (Also, remember to use either a .jpg, .bmp, or .tif image only for the Gallery!)"
								"<p>";

				error	= true;
			}
			//check if no image and picture URL
			if ((FIELD_OMITTED(pPicUrl) || strcmp(pPicUrl, "http://") == 0) &&
				(FIELD_OMITTED(pGalleryUrl) || strcmp(pGalleryUrl, "http://") == 0))
			{
				*mpStream <<	"<h2>No Gallery Picture Provided</h2>\n"
								"You have not supplied an image for the Gallery.  Please go back and enter "
								"a URL for a picture either in the Pic URL field or the Gallery Image URL "
								"field. Remember: please supply a Gallery picture in either the .jpg, .bmp, or .tif formats -- but not .gif!";
								"<p>";
				error	= true;
			}
		}
	}

	// New as of Feb 2, 1999
	// Check if user is allowed to list dutch auctions
	if ((nQuantity > 1) && (mpUser) && (!mpMarketPlace->UserCanListDutchAuction(mpUser)))
	{
		*mpStream <<	"<h2>"
						"Requirements for Dutch Auction Have Not Been Met"
						"</h2>"
						"As of February 3, 1999, in order to sell items using the Dutch Auction "
						"format, you must:"
						"<ol>"
						"<li>Have a Feedback Rating of 10 or above; and"
						"<li>Be a member of eBay for 60 days or more."
						"</ol>"
//						"If either of the above conditions does not hold, you can still use the Dutch Auction format by first "
//					<<	"<a href=\""
//					<<	mpMarketPlace->GetSecureHTMLPath()
//					<<	"cc-update.html\">"
//					<<	"placing a credit card on file"
//					<<	"</a>"
//						"." 
						"<p>"
						"We believe that this will help prevent unscrupulous people from taking "
						"advantage of honest, trustworthy individuals in our community. We understand "
						"that this may inconvenience a few sellers, but we hope you understand our "
						"concern for protecting bidders and for maintaining an open, honest and safe "
						"environment for online trading."
						"<p>";
		error	= true;
	}

	return	error;
}

// Emit category-specific message(s) to seller
void clseBayApp::EmitSellerMessages(CategoryId categoryId, ostream *pStream)
{
	vector<char *>				vMessages;
	vector<char *>::iterator	i;

	mpCategories->GetCategoryMessages()->GetMessageText(categoryId,
														MessageTypeCategorySellerWhenListing,
														&vMessages,
														true);

	if (vMessages.size() > 0)
	{
		*pStream	<< "<b><font color=\"red\">\n"
					<< "<h3>Attention Sellers:</h3>\n"
					<< "<p>\n";

		for (i = vMessages.begin(); i != vMessages.end(); ++i)
		{
			if (*i != NULL)
			{
				*pStream	<< (*i)
							<< "<hr width=\"5%\" align=\"left\">";
				delete (*i);
			}
		}

		*pStream	<< "</font></b><p>\n";

		vMessages.erase(vMessages.begin(), vMessages.end());
	}
}

// nsacco 07/27/99 added new params	
void clseBayApp::VerifyNewItem(CEBayISAPIExtension *pServer,
						  char *pUserId,
						  char *pPass,
						  char *pTitle,
						  char *pLocation,
						  char *pReserve,
						  char *pStartPrice,
						  char *pQuantity,
						  char *pDuration,
						  char *pBold,
						  char *pFeatured,
						  char *pSuperFeatured,
						  char *pPrivate,
						  char *pDesc,
						  char *pPicUrl,
						  char *pCategory1,
						  char *pCategory2,
						  char *pCategory3,
						  char *pCategory4,
						  char *pCategory5,
						  char *pCategory6,
						  char *pCategory7,
						  char *pCategory8,
						  char *pCategory9,
						  char *pCategory10,
						  char *pCategory11,
						  char *pCategory12,
						  char *pOldItemNo,
						  char *pOldKey,
						  UAChoice uaChoice,
						  char *pMoneyOrderAccepted,
						  char *pPersonalChecksAccepted,
						  char *pVisaMasterCardAccepted,
						  char *pDiscoverAccepted,
						  char *pAmExAccepted,
						  char *pOtherAccepted,
						  char *pOnlineEscrowAccepted,
						  char *pCODAccepted,
						  char *pPaymentSeeDescription,
						  char *pSellerPaysShipping,
						  char *pBuyerPaysShippingFixed,
						  char *pBuyerPaysShippingActual,
						  char *pShippingSeeDescription,
						  char *pShippingInternationally,
						  char *pShipToNorthAmerica,
						  char *pShipToEurope,
						  char *pShipToOceania,
						  char *pShipToAsia,
						  char *pShipToSouthAmerica,
						  char *pShipToAfrica,
						  int  siteId,
						  int  descLang,
						  CHttpServerContext *pCtxt,
						  char *pGiftIcon,
						  int  gallery,
						  char *pGalleryUrl,
						  int  countryId,
						  int  currencyId,
						  char *pZip
							)
{
	clsAccount	*pAccount;
	bool		error	= false;
	int			nQuantity;
	int			nCategory;
	double		dStartPrice;
	double		dPrice;
	double		dReserve;

	double		dFees;

	clsCategory	*pCategory;
	clsCategory	*pDefaultCategory;

	char		*pNewTitle;
	char		*pNewDescription;

	int			nItemNo;
	char		cItemNo[10];
	char*		pCryptedItemNo;
	char		cSalt[10];
	bool		FreeRelisting = false;
	clsItem*	pOldItem;

	time_t				theTime;
	bool				freeListing = false;
	const char			*pSafeDescription = NULL;

	bool		FreeGallery = false; 

	int         unused; // unused flags returned from ebay_user

	// In the future, the account's currency id will be based on the user's origin.
	// For now, it is simply US dollars.
	// nsacco 06/16/99
	// TODO - account currency should be based on the user's country or preference and will
	// possibly require some lookup. This is the billing currency for the user.
	int			accountCurrencyId = Currency_USD;	// US dollars for now
	int			feeCurrencyId	  = currencyId;		// the auction currency
	
	int			iconType = 0;

 	clsCurrencyWidget accountCurrencyWidget(mpMarketPlace, accountCurrencyId, 0); // set below

	// Use this to display amounts in the auction currency.
	clsCurrencyWidget currencyWidget(mpMarketPlace, currencyId, 0); // updated below 

	// for possible redirect for adult signin
	unsigned long lLength;
	char newURL[255];

	char pCountry[65];
	clsCountries    *pCountries = mpMarketPlace->GetCountries();

	// nsacco 08/03/99
	// fees are charged in the auction (site) currency and then converted 
	// to the user's account currency using the daily exchange rate
	clsFees objFees(currencyId);

	// check for super free relisting.
	//  FreeRelisting just means that if the item sells the 2nd time around
	//  SuperFreeRelisting means that the relisted item is free no matter what, due to system outage
	bool SuperFreeRelisting = false;

	// zip
	char	NewZip[EBAY_MAX_ZIP_SIZE + 1];
	char*	pTempZip = pZip;

	memset(NewZip, 0, sizeof(NewZip));

	
	// Setup
	SetUp();

	// Get the current time
	theTime	= time(0);
	
	//
	//	** NOTE **
	//	FREE LISTING PERIOD (a gift to our users)
	//	** NOTE **
	if ((clsUtilities::CompareTimeToGivenDate(theTime, 12, 22, 98, 0, 0, 0) >= 0) &&
		(clsUtilities::CompareTimeToGivenDate(theTime, 12, 22, 98, 23, 59, 59) <= 0))
		freeListing = true;

	//
	//	** NOTE **
	//	FREE RELISTING PERIOD (due to system outage)
	//	** NOTE **
	if ((clsUtilities::CompareTimeToGivenDate(theTime, 12, 9, 98, 0, 0, 0) >= 0) &&
		(clsUtilities::CompareTimeToGivenDate(theTime, 12, 31, 98, 23, 59, 59) <= 0))
		SuperFreeRelisting = true;

	// ** NOTE **
	// Free gallery period ( new feature promotion)
	// ** NOTE **
	//FREEGALLERY
	if ((clsUtilities::CompareTimeToGivenDate(theTime, 5, 19, 99, 0, 0, 0) >= 0) &&
		(clsUtilities::CompareTimeToGivenDate(theTime, 6, 3, 99, 23, 59, 59) <= 0))
		FreeGallery = true;


	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// Usual Title and Header
	*mpStream <<	"<HTML>"
					"<head>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" New Item Verification"
			  <<	"</TITLE>"
			  		"</head>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<br>";

	mpUser	= mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream);

	// If we didn't get the user, we're done
	if (!mpUser)
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}
	//only rosie can use rosie icon
	if (strcmp(mpUser->GetUserId(), "4allkids") && strcmp(pGiftIcon, "2") ==0 )
	{
		*mpStream <<	"<H2>Only Rosie can use Rosie Icon </H2>"
						"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}


	// New from Mar 15, 1999
	// Check to see if the user verification completes properly.
	if (ValidateOrBlockAction() == FALSE)
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// If we got passed the default, make sure the code does
	// not think we actually have an item.
	if (pOldItemNo && strcmp(pOldItemNo, "default") == 0)
		pOldItemNo = NULL;

	// check whether the itemno has been modified by the user
	if (pOldItemNo)
	{
		// get the old item
		if ((pOldItem = mpItems->GetItem(atoi(pOldItemNo), false)) != NULL)
		{
			if (!ValidateItemNo(pOldItemNo, pOldKey, pOldItem->GetSeller()))
			{
				*mpStream <<	"<h2>Errors in Input Data</h2>"
						  <<	"Please try again.<p>"
						  <<	mpMarketPlace->GetFooter();

				CleanUp();
				delete pOldItem;

				return;
			}
			delete pOldItem;
		}
		else
		{
			// invalid old item #
			*mpStream <<	"<h2>Errors in Input Data</h2>"
					  <<	"Please try again.<p>"
					  <<	mpMarketPlace->GetFooter();

			CleanUp();

			return;
		}
	}

	// convere Quantity
	if (!FIELD_OMITTED(pQuantity))
		nQuantity	= atoi(pQuantity);
	else
		nQuantity	= 1;

	// Let's see if the user can list
	if (!mpMarketPlace->UserCanList(mpUser, mpStream, true))
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	//temp remove because we temp remove your home country from listing page
/*	//check the item's country
	if (countryId == 0)
	{
		if (mpUser->GetCountryId()) //check user who already have countryId
			countryId = mpUser->GetCountryId();
		else //old user
		{
			strcpy(pCountry, mpUser->GetCountry());
			countryId = pCountries->GetCountryIdByCode(pCountry);
		}
	}
*/
	//translate countryId to country name for display
	pCountries->GetCountryName(countryId, pCountry);

	if (pCountry[0] =='\0')
	{
		*mpStream << "<h2>Invalid Country Id</h2>"
					 "Please go back and try again!";
		error = true;
	}

	// make an array for the category string variables to make
	//  the next block of code nicer
	char* categories[12];
	categories[0] = pCategory1;
	categories[1] = pCategory2;
	categories[2] = pCategory3;
	categories[3] = pCategory4;
	categories[4] = pCategory5;
	categories[5] = pCategory6;
	categories[6] = pCategory7;
	categories[7] = pCategory8;
	categories[8] = pCategory9;
	categories[9] = pCategory10;
	categories[10] = pCategory11;
	categories[11] = pCategory12;


	// check to make sure exactly one category was selected.
	int numSelectedCategories = 0;
	char *selectedCategory = NULL;
	for (int j=0; j<12; j++)
	{
		if ((!FIELD_OMITTED(categories[j])) && (atoi(categories[j]) != 0))
		{
			numSelectedCategories++;
			selectedCategory = categories[j];
		}
	}

	// now do the checks
	if (numSelectedCategories < 1)
	{
		*mpStream <<	"<h2>"
						"No category selected"
						"</h2>"
						"You must choose a category in which to list your item."
						"<p>";
		error = true;
	}
	if (numSelectedCategories > 1)
	{
		*mpStream <<	"<h2>"
						"More than one category selected"
						"</h2>"
						"You can choose only one category in which to list your item."
						"<p>";
		error = true;
	}

	// Let's see if we need to leave now
	if (error)
	{
		*mpStream	<<	"<p>"
					<<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	nCategory	= atoi(selectedCategory);

	// 02/24/99 Alex Poon
	// Block firearms listing beginning on Sat 2/27/99
	if (CheckForFirearmsListing(nCategory))
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	pCategory = mpCategories->GetCategory(nCategory, true);
	if (pCategory == NULL)
	{
		*mpStream <<	"<h2>Category does not exist</h2>"
						"The category you have selected does "
						"not exist.  Please go back and select "
						"another category."
						"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// check whether the category is not for Minor
	if (pCategory->isAdult() || pCategory->NoBidAndListForMinor())
	{
		// check whether the user is adult
		if (!HasAdultCookie())
		{
			// calculate URL of adult login page
			strcpy(newURL, mpMarketPlace->GetCGIPath(PageAdultLoginShow));
			strcat(newURL, "eBayISAPI.dll?AdultLoginShow");
			if (pCategory->isAdult())
				strcat(newURL, "&t=1");	// erotica
			else
				if (pCategory->NoBidAndListForMinor())
					strcat(newURL, "&t=2");	// firearms


			// Just in case the redirect doesn't work, tell user where to go
			*mpStream << "<p>Click <b>refresh</b> or <b>reload</b> button on your browser now.";

			CleanUp();

			// redirect to adult sign-in page
			pServer->EbayRedirect(pCtxt, newURL);
			lLength = strlen(newURL);
			// pCtxt->ServerSupportFunction(HSE_REQ_SEND_URL_REDIRECT_RESP, newURL, &lLength, NULL);
			return;
		}
	}
	
	// kaz: 4/7/99: Support for Police Badge T&C page
	if (nCategory == kPoliceBadgeCatID)
		if (clsUtilities::CompareTimeToGivenDate(time(0),4,16,99,0,0,0) >= 0) 
			if (! mpUser->AcceptedPoliceBadgeAgreement())
	{
		PoliceBadgeAgreementForSelling(pUserId,pPass);
		*mpStream <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}	//

	// check zip
	if (countryId == Country_US)
	{
		// zip is mandatory for item in USA
		if (FIELD_OMITTED(pZip))
		{
			*mpStream <<	"<h2>Zip Code Missing</h2>"
							"Your item\'s zip code was not filled in. It is required. "
							"Please go back and try again."
							"<p>"
					  <<	mpMarketPlace->GetFooter();
			CleanUp();
			return;
		}

		// The first 5 has too be digits
		if (!clsUtilities::AreDigits(pZip, 5))
		{
			*mpStream <<	"<h2>Invalid Zip Code</h2>"
							"Your item\'s zip code is invalid. "
							"Please go back and try again."
							"<p>"
					  <<	mpMarketPlace->GetFooter();
			CleanUp();
			return;
		}

		// make a copy of zip
		strncpy(NewZip, pZip, 5);
		pTempZip = NewZip;
	}
	else
	{
		// for none us item, zip is limited to 12 (EBAY_MAX_ZIP_SIZE)
		if (!FIELD_OMITTED(pZip))
		{
			if (strlen(pZip) > EBAY_MAX_ZIP_SIZE)
			{
				*mpStream	<<	"Zip (or Postal) Code Too Long"
								"Zip (or Postal) code can not be more than "
							<<	EBAY_MAX_ZIP_SIZE
							<<	" characters. Please go back and try again."
								"<p>"
						  <<	mpMarketPlace->GetFooter();
				CleanUp();
				return;
			}
		}
	}


	// nsacco 07/27/99 added new params
	error	= CheckItemData(pTitle,
							pLocation,
							pReserve,
							pStartPrice,
							pQuantity,
							pDuration,
							pBold,
							pFeatured,
							pSuperFeatured,
							pPrivate,
							pDesc,
							pPicUrl,
							selectedCategory,
							pMoneyOrderAccepted,
							pPersonalChecksAccepted,
							pVisaMasterCardAccepted,
							pDiscoverAccepted,
							pAmExAccepted,
							pOtherAccepted,
							pOnlineEscrowAccepted,
							pCODAccepted,
							pPaymentSeeDescription,
							pSellerPaysShipping,
							pBuyerPaysShippingFixed,
							pBuyerPaysShippingActual,
							pShippingSeeDescription,
							pShippingInternationally,
							pShipToNorthAmerica,
							pShipToEurope,
							pShipToOceania,
							pShipToAsia,
							pShipToSouthAmerica,
							pShipToAfrica,
							siteId,
							descLang,
						    gallery,
							pGalleryUrl,
							currencyId
							);

	// Let's see if we need to leave now
	if (error)
	{
		*mpStream	<<	"<p>"
					<<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// If the user has not yet accepted the user agreement, then
	// make sure we stear the user towards it.
	uaChoice = UAAcceptedWithNotify;
	if (!mpUser->AcceptedUserAgreement())
	{

		switch (uaChoice)
		{
		case UAAcceptedWithNotify:
			unused = mpUser->SetSomeUserFlags(true, UserFlagSignedAgreement | UserFlagChangesToAgreement);
			break;

		case UAAcceptedWithoutNotify:
			unused = mpUser->SetSomeUserFlags(true, UserFlagSignedAgreement);
			break;

		case UADeclined:
			ProduceUserAgreementFAQ();

			*mpStream <<	"<br>"
					  <<	mpMarketPlace->GetFooter();

			CleanUp();
			return;

			// break;

		default:
			// nsacco 07/27/99 added new params
			UserAgreementForSelling(pUserId,
									pPass,
									pTitle,
									pLocation,
									pReserve,
									pStartPrice,
									pQuantity,
									pDuration,
									pBold,
									pFeatured,
									pSuperFeatured,
									pPrivate,
									pDesc,
									pPicUrl,
									pCategory1,
									pCategory2,
									pCategory3,
									pCategory4,
									pCategory5,
									pCategory6,
									pCategory7,
									pCategory8,
									pCategory9,
									pCategory10,
									pCategory11,
									pCategory12,
									pOldItemNo,
									pOldKey,
									pMoneyOrderAccepted,
									pPersonalChecksAccepted,
									pVisaMasterCardAccepted,
									pDiscoverAccepted,
									pAmExAccepted,
									pOtherAccepted,
									pOnlineEscrowAccepted,
									pCODAccepted,
									pPaymentSeeDescription,
									pSellerPaysShipping,
									pBuyerPaysShippingFixed,
									pBuyerPaysShippingActual,
									pShippingSeeDescription,
									pShippingInternationally,
									pShipToNorthAmerica,
									pShipToEurope,
									pShipToOceania,
									pShipToAsia,
									pShipToSouthAmerica,
									pShipToAfrica,
									siteId,
									descLang,
									pGiftIcon,
									gallery,
									pGalleryUrl,
									countryId,
									currencyId,
									pZip);

			*mpStream <<	"<br>"
					  <<	mpMarketPlace->GetFooter();

			CleanUp();
			return;
		} // the choice
	} // The user had not yet accepted the user agreement.

	// If we're here, the user has now accepted the user agreement and
	// we can continue.

	if (!FIELD_OMITTED(pReserve))
	{
		dReserve	= atof(pReserve);
		dReserve	= RoundToCents(dReserve);

		// msg about reserve rule changes - REMOVED 8/27/98 - TINI
/*		*mpStream	<< "<p>"
					<< "Please note, we recently changed the format "
					<< "for reserve auctions.  Please see "
					<< "<a href="
						"\""
					<<	mpMarketPlace->GetHTMLPath()
					<<	"help/buyerguide/bidding-type.html#reserve"
						"\""
						">"
						"reserve price auction rules"
						"</a>"
					    " for more information."
					<<  "<p>\n";
*/	}
	else
		dReserve	= 0;

	dStartPrice	= atof(pStartPrice);
	dStartPrice	= RoundToCents(dStartPrice);

	// Get the category object (but don't delete it 'cuz it points
	// directly into the category cache!!!
	pCategory	= mpCategories->GetCategory(atoi(selectedCategory), true);

	// First we must check the category for screening and, if necessary,
	// emit one or more messages for/to the seller
	if (pCategory != NULL && pCategory->GetScreenItems())
	{
		EmitSellerMessages(pCategory->GetId(), mpStream);
	}

	pNewDescription	= CleanUpDescription(pDesc);
	pNewTitle		= CleanUpTitle(pTitle);;

	pAccount	= mpUser->GetAccount();

	*mpStream <<	"Current <a href=\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"services/buyandsell/account-status.html\">"
			  <<	"account balance</a> before adding this item: ";

	accountCurrencyWidget.SetNativeAmount(pAccount->GetBalance()); 
	accountCurrencyWidget.SetBold(true);
	accountCurrencyWidget.EmitHTML(mpStream);

	*mpStream <<	".<p>\n"; 


	delete	pAccount;
	pAccount = NULL;

	*mpStream <<	"<p>Please verify your entry as it appears below. If there are any "
					"errors, please use the back button on your browser to go back and "
					"correct your entry. Once you are satisfied with the entry, please "
					"press the submit button.\n";

	if (objFees.CheckForAutomotiveListing(pCategory->GetId()))
	{
		// nsacco 06/16/99
		*mpStream <<	"<font color=GREEN><strong><p>Fees in this category are fixed.  eBay charges sellers a ";
		
		currencyWidget.SetNativeAmount(objFees.GetFee(AutoListingFee));
		currencyWidget.SetNativeCurrencyId(feeCurrencyId);
		currencyWidget.EmitHTML(mpStream);	

		*mpStream <<	"insertion fee and a ";

		currencyWidget.SetNativeAmount(objFees.GetFee(AutoFinalValueFee));
		currencyWidget.SetNativeCurrencyId(feeCurrencyId);
		currencyWidget.EmitHTML(mpStream);	

		*mpStream <<	"final value fee, regardless of the listing "
						"or selling price.  Please see "
						"<a href=\""
					<<	mpMarketPlace->GetHTMLPath()
					<<	"help/sellerguide/selling-fees.html\">"
						" eBay fees</A>"
						" for details.</strong></font>\n";
	}
	if (objFees.CheckForRealEstateListing(pCategory->GetId()))
	{
		//let do real estate legal verbage in a table with border
		*mpStream <<	"<p>"
						"<table border=\"1\"><tr><td>"
						"<p>The offer and sale of real estate is a complex area, and may be governed "
						"by a variety of local, state and federal laws and private party contractual "
						"arrangements. Buyers and sellers are advised to consult with qualified "
						"professionals as to legal sufficiency, legal effect and tax consequences "
						"when involved in any transactions in real estate."
						"</td></tr></table>";

		*mpStream <<	"<font color=GREEN><strong><p>Fees in this category are fixed.  eBay charges sellers a ";
		
		currencyWidget.SetNativeAmount(objFees.GetFee(RealEstateListingFee));
		currencyWidget.SetNativeCurrencyId(feeCurrencyId);
		currencyWidget.EmitHTML(mpStream);

		*mpStream <<	"insertion fee and no final value fee, regardless of the listing or "
						"selling price.  Please see "
						"<a href=\""
					<<	mpMarketPlace->GetHTMLPath()
					<<	"help/sellerguide/selling-fees.html\">"
						" eBay fees</A>"
						" for details.</strong></font>\n";
	}


	*mpStream <<	"<hr width=50%><pre>"
					"Your ";
	*mpStream <<	mpMarketPlace->GetLoginPrompt()
			  <<	":                 <strong>"
              <<    mpUser->GetUserId()
			  <<	"</strong>\n"
					"The title of the item:        <strong>"
			  <<	pNewTitle
			  <<	"</strong>\n";
	

	if (CHECKED(pBold))
		*mpStream <<	"Optional boldface title:      <strong>yes</strong>\n";
	else
		*mpStream <<	"Optional boldface title:      <strong>no</strong>\n";

	if (CHECKED(pSuperFeatured))
		*mpStream <<	"Featured auction:             <strong>yes</strong>\n";
	else
		*mpStream <<	"Featured auction:             <strong>no</strong>\n";

	if (CHECKED(pFeatured))
		*mpStream <<	"Featured category auction:    <strong>yes</strong>\n";
	else
		*mpStream <<	"Featured category auction:    <strong>no</strong>\n";

	//check whether user is roies, item listed by rosie, gifticon will be 2 "Rosie Icon"
//	if (strcmp(mpUser->GetUserId(), "ebay-bid") == 0) // test
	if (strcmp(mpUser->GetUserId(), "4allkids") == 0)
	{
		iconType = RosieIcon;
		*mpStream <<	"Great Gift auction:           <strong>yes</strong>\n";
	}
	else
	{
		int giftIcon = atoi(pGiftIcon);
		if (giftIcon > 0)
		{
			iconType = giftIcon;

			*mpStream <<	"Great Gift auction:           <strong>yes</strong> &nbsp; &nbsp;"
					  <<	"<img src=\"" 
					  <<	mpMarketPlace->GetImagePath()
					  <<	mpMarketPlace->GetGiftIconImage(iconType)
					  <<	"\" border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\">\n";
		}
		else
		{
			*mpStream <<	"Great Gift auction:           <strong>no</strong>\n";
			iconType = GiftIconUnknown;
		}
	}

	//Gallery 
	if (gallery == Gallery)
		*mpStream <<	"Optional Gallery:             <strong>yes</strong>\n";
	else
		*mpStream <<	"Optional Gallery:             <strong>no</strong>\n";

	if (gallery == FeaturedGallery)
		*mpStream <<	"Optional Featured Gallery:    <strong>yes</strong>\n";
	else
		*mpStream <<	"Optional Featured Gallery:    <strong>no</strong>\n";

	
	*mpStream <<	"The category of the item:     <strong>";
	mpCategories->EmitHTMLQualifiedName(mpStream, pCategory);
	*mpStream <<	"</strong>\n";

	if (nQuantity >= 1 && dReserve)
	{
		*mpStream <<	"Optional reserve price:       ";
		
		currencyWidget.SetNativeAmount(dReserve); 
		currencyWidget.SetBold(true);
		currencyWidget.EmitHTML(mpStream);

		*mpStream <<	"\n";
	}
	else
	{
		*mpStream <<	"Optional reserve price:       <strong>no</strong>\n";
	}

	if (nQuantity >= 1 && CHECKED(pPrivate))
	{
		*mpStream <<	"Optional private auction:     <strong>yes</strong>\n";
	}
	else
	{
		*mpStream <<	"Optional private auction:     <strong>no</strong>\n";
	}

	// nsacco 06/17/99
	*mpStream <<	"Auction currency:             <strong>"
			  <<	mpMarketPlace->GetCurrencies()->GetCurrency(currencyId)->GetNamePlural()
			  <<	"</strong>\n";

	*mpStream <<	"Bidding starts at:            <strong>";

	currencyWidget.SetNativeAmount(dStartPrice);
	currencyWidget.EmitHTML(mpStream);

	*mpStream <<	"</strong>\n";
	*mpStream <<	"Quantity being offered:       <strong>"
			  <<	nQuantity
			  <<	"</strong>\n";
	*mpStream <<	"Auction duration in days:     <strong>"
			  <<	pDuration
			  <<	" days</strong>\n";

	*mpStream <<	"Location of item:             <strong>"
			  <<	pLocation
			  <<	"</strong>\n";
	*mpStream <<	"Location Zip Code of item:    <strong>"
			  <<	pTempZip
			  <<	"</strong>\n";
	*mpStream <<	"Country location of item:     <strong>"
			  <<	pCountry
			  <<	"</strong>\n";

	if (CHECKED(pMoneyOrderAccepted) )
		*mpStream <<	"Money order/Cashiers checks:              <strong>yes</strong>\n";
	else
		*mpStream <<	"Money order/Cashiers checks:              <strong>no</strong>\n";

	if (CHECKED(pPersonalChecksAccepted))
		*mpStream <<	"Personal checks:                          <strong>yes</strong>\n";
	else
		*mpStream <<	"Personal checks:                          <strong>no</strong>\n";

	if (CHECKED(pVisaMasterCardAccepted))
		*mpStream <<	"Visa/MasterCard:                          <strong>yes</strong>\n";
	else   
		*mpStream <<	"Visa/MasterCard:                          <strong>no</strong>\n";

	if (CHECKED(pDiscoverAccepted))
		*mpStream <<	"Discover:                                 <strong>yes</strong>\n";
	else
		*mpStream <<	"Discover:                                 <strong>no</strong>\n";

	if (CHECKED(pAmExAccepted))
		*mpStream <<	"American Express:                         <strong>yes</strong>\n";
	else
		*mpStream <<	"American Express:                         <strong>no</strong>\n";

	if (CHECKED(pOtherAccepted))
		*mpStream <<	"Other:                                    <strong>yes</strong>\n";
	else
		*mpStream <<	"Other:                                    <strong>no</strong>\n";
 
	if (CHECKED(pOnlineEscrowAccepted))
		*mpStream <<	"Online Escrow:                            <strong>yes</strong>\n";
	else
		*mpStream <<	"OnlineEscrow:                             <strong>no</strong>\n";

	if (CHECKED(pCODAccepted))
		*mpStream <<	"COD (collect on delivery):                <strong>yes</strong>\n";
	else
		*mpStream <<	"COD (collect on delivery):                <strong>no</strong>\n";

	if (CHECKED(pPaymentSeeDescription))
		*mpStream <<	"See Item Description for payment methods: <strong>yes</strong>\n";
	else
		*mpStream <<	"See Item Description for payment methods: <strong>no</strong>\n";

	if (CHECKED(pSellerPaysShipping))
		*mpStream <<	"Seller pays for shipping:                 <strong>yes</strong>\n";
	else
		*mpStream <<	"Seller pays for shipping:                 <strong>no</strong>\n";

	if (CHECKED(pBuyerPaysShippingFixed))
		*mpStream <<	"Buyer pays fixed amount for shipping:     <strong>yes</strong>\n";
	else
		*mpStream <<	"Buyer pays fixed amount for shipping:     <strong>no</strong>\n";

	if (CHECKED(pBuyerPaysShippingActual))
		*mpStream <<	"Buyer pays actual shipping cost:          <strong>yes</strong>\n";
	else
		*mpStream <<	"Buyer pays actual shipping cost:          <strong>no</strong>\n";

	if (CHECKED(pShippingSeeDescription))
		*mpStream <<	"See item description for shipping costs:  <strong>yes</strong>\n";
	else
		*mpStream <<	"See item description for shipping costs:  <strong>no</strong>\n";

	// nsacco 07/27/99 new shipping conditions
	*mpStream <<		"\n"
						"<strong>Where you will ship:</strong>\n";

	if (strcmp(pShippingInternationally, "worldwide") == 0)
	{
		*mpStream <<	"Will Ship Internationally (worldwide)\n";
	}
	else if (strcmp(pShippingInternationally, "siteonly") == 0)
	{
		*mpStream <<	"Will Ship to ";

		switch (siteId)
		{
		case SITE_EBAY_DE:
			*mpStream << "Germany";
			break;
		case SITE_EBAY_AU:
			*mpStream << "Australia";
			break;
		case SITE_EBAY_UK:
			*mpStream << "United Kingdom";
			break;
		case SITE_EBAY_CA:
			*mpStream << "Canada";
			break;
		case SITE_EBAY_US:
		case SITE_EBAY_MAIN:
			*mpStream << "United States";
			break;
		}
				  
		*mpStream <<	" Only\n";
	}
	else if (strcmp(pShippingInternationally, "siteplusregions") == 0)
	{
		*mpStream <<	"Will Ship to ";

		switch (siteId)
		{
		case SITE_EBAY_DE:
			*mpStream << "Germany";
			break;
		case SITE_EBAY_AU:
			*mpStream << "Australia";
			break;
		case SITE_EBAY_UK:
			*mpStream << "United Kingdom";
			break;
		case SITE_EBAY_CA:
			*mpStream << "Canada";
			break;
		case SITE_EBAY_US:
		case SITE_EBAY_MAIN:
			*mpStream << "United States";
			break;
		}
				  
		*mpStream <<	" and the following regions:\n";

		if (CHECKED(pShipToNorthAmerica))
			*mpStream << "  North America\n";

		if (CHECKED(pShipToEurope))
			*mpStream << "  Europe\n";

		if (CHECKED(pShipToOceania))
			*mpStream << "  Australia / NZ\n";

		if (CHECKED(pShipToAsia))
			*mpStream << "  Asia\n";

		if (CHECKED(pShipToSouthAmerica))
			*mpStream << "  South America\n";

		if (CHECKED(pShipToAfrica))
			*mpStream << "  Africa\n";

		*mpStream << "\n";
	}
	// end new shipping conditions

// Lena Ts and Cs
	*mpStream <<	"The description of the item:\n"
					"</pre>";

	*mpStream << "<blockquote>\n";

//	pSafeDescription = clsUtilities::DrawSafeHTML(pDesc);
//	if (pSafeDescription)
//		*mpStream << pSafeDescription;
//	else
		*mpStream << pDesc;
//	delete (char *) pSafeDescription;

	*mpStream <<	"\n</blockquote>\n";

	// nsacco 07/27/99
	// TODO - add this in later
	//*mpStream <<		"Description language:						<strong>";
	// switch (descLang)
	//{
	//	case German:
	//		*mpStream << "German";
	//		break;
	//	case English:
	//	default:
	//		*mpStream << "English";
	//	break;
	//}
	//*mpStream <<		"</strong>\n";

	*mpStream <<	"<hr width=50%>\n";

	if (!FIELD_OMITTED(pPicUrl) && 
		strcmp(pPicUrl, "http://") != 0)
	{
		*mpStream <<	"<blockquote>"
						"<h3>Picture URL</h3>"
						"You have provided a URL to a picture of the item, which is shown below. "
						"If it shows up as a \'broken image\', the URL may be incorrect, and "
						"you should go back and correct it. This can also occur if the image "
						"hasn't been loaded yet. "
						"<p>"
						"<b>Picture URL:</b> "
				  <<	pPicUrl
				  <<	"</blockquote>"
				  <<	"<p>"
						"<center>"
						"<img src="
						"\""
				  <<	pPicUrl
				  <<	"\""
						">"
						"</center>"
						"<p>";
	}
	if (gallery != 0)
	{
		if (FIELD_OMITTED(pGalleryUrl) ||
			strcmp(pGalleryUrl, "http://") == 0)
		{
			pGalleryUrl = pPicUrl;
		}
	
		*mpStream <<	"<blockquote>"
						"<h3>Gallery Image URL</h3>"
						"You have provided a URL to a picture of your item for the Gallery, which is shown below. "
						"If your image only shows up as a \'broken image\', the URL is most likely incorrect, and "
						"you should go back and make sure you typed your image URL correctly."
						"<p>"
						"<b>Gallery Image URL:</b> "
				  <<	pGalleryUrl
				  <<	"</blockquote>"
				  <<	"<p>"
						"<center>"
						"<img src="
						"\""
				  <<	pGalleryUrl
				  <<	"\""
						">"
						"</center>"
						"<p>";
		
	}



/*	if (nQuantity > 1 && CHECKED(pPrivate) && CHECKED(pReserve))
	{
		*mpStream <<	"<h3><blink>!</blink> Changed your specifications</h3>"
				  <<	"<strong><a href=\"";
		*mpStream <<	mpMarketPlace->GetHTMLPath();
		*mpStream <<	"help/buyerguide/bidding-type.html#private\">Private auctions</a> and <a href=\"";
		*mpStream <<	mpMarketPlace->GetHTMLPath();
		*mpStream <<	"help/buyerguide/bidding-type.html#reserve\">reserve price</a> auctions are not available "
				  <<	"with the <a href=\"";
		*mpStream <<	mpMarketPlace->GetHTMLPath();
		*mpStream <<	"help/buyerguide/bidding-type.html#dutch\">Dutch auction</a> format. If you wish "
						"to use a private auction or a reserve price auction, you must specify a "
						"quantity of one item only.</strong><p>\n";

		pPrivate = "";
		pReserve = "";

	}
*/

	// Category things
	pDefaultCategory	= mpCategories->GetCategoryDefault(true);
	if (nCategory  == pDefaultCategory->GetId())
	{
		*mpStream <<	"<h3>Miscellaneous?</h3>"
				  <<	"You are listing your item in the Miscellaneous category. Are you sure you can't "
				  <<	"find a more appropriate category? You might want to check again by going back "
				  <<	"and looking at the categories available in the menu. Otherwise, you buyers "
						"may not see your listing.<p>\n";
	}

	if (nQuantity > 1)
	{
		dPrice = nQuantity * dStartPrice;
	}
	else
	{
		if (dReserve > dStartPrice)
		{
			dPrice = dReserve;
		}
		else
		{
			dPrice = dStartPrice;
		}
	}

	// Get the insertion fee
	*mpStream <<	"If this information is correct, please press the submit button "
					"to start the auction. Otherwise, please go back and correct it."
					"<p>"
					"<h3>Fees:</h3>\n\n";

	dFees	= 0.0;

	FreeRelisting = IsRelistFree(pOldItemNo, nQuantity, dPrice);

	// Check whether the user gets the free re-listing
	if (FreeRelisting)
	{
		if (SuperFreeRelisting)	// check for system outage
		{
			*mpStream <<	"<ul><li><b>Relisted Item: </b>Insertion fee of ";

			//inna-LA currencyWidget.SetNativeAmount(objFees.GetInsertionFee(dPrice));
			currencyWidget.SetNativeAmount(objFees.GetInsertionFee(dPrice,nCategory));
			currencyWidget.SetNativeCurrencyId(feeCurrencyId);
			currencyWidget.SetBold(true);
			currencyWidget.EmitHTML(mpStream);
				  
			*mpStream <<	" for relisting this item will be <b>waived</b>, "
					  <<	"due to a recent system outage.\n";
		}
		else
		{
			*mpStream <<	"<ul><li><b>Relisted Item: </b>Insertion fee of ";
					  //inna-LA <<	objFees.GetInsertionFee(dPrice)

			currencyWidget.SetNativeAmount(objFees.GetInsertionFee(dPrice,nCategory));
			currencyWidget.SetNativeCurrencyId(feeCurrencyId);
			currencyWidget.SetBold(true);
			currencyWidget.EmitHTML(mpStream);

			*mpStream <<	" for relisting this item will be refunded "
					  <<	"if the relisted item is sold the second time around.\n";
		}
	}
	else
	{
		// Alex added 12/09/98
		// Check to see if the pOldItemNo was specified. If so, then the user actually
		//  was trying to make this a relisted item. Let's explain to him/her that it
		//  actually won't be considered a relisted item.
		if (pOldItemNo)
		{
			// user should be advised that this item won't be refunded as a relisting
			// because it didn't qualify
			*mpStream <<	"<ul>"
					  <<	"<li>"
					  <<	"<b><font color=red>Note:</font></b> "
					  <<	"This listing does not qualify as a refundable relisted item. "
					  <<	"Please see the <a href=\""
					  <<	mpMarketPlace->GetHTMLPath()
					  <<	"faq.html#29\">Seller FAQ</a> for more details.";
					
		}

		// Non refundable...
		*mpStream <<	"<ul>"
				  <<	"<li>"
				  <<	"A non-refundable insertion fee of ";

		//inna-LA currencyWidget.SetNativeAmount(objFees.GetInsertionFee(dPrice));
		currencyWidget.SetNativeAmount(objFees.GetInsertionFee(dPrice, nCategory));
		currencyWidget.SetNativeCurrencyId(feeCurrencyId);
		currencyWidget.SetBold(true);
		currencyWidget.EmitHTML(mpStream);
				  
		*mpStream <<    " will apply to this listing immediately. "
						"This fee is due even if your item does not sell.";


		if (freeListing)
		{
			// nsacco 06/16/99
			// TODO - remove
			*mpStream <<	"<li>eBay Free Listing Day 1999! "
							"<font color=red>Insertion fee will be refunded!</font>";
		}

	}
	//inna-LA dFees	+=	objFees.GetInsertionFee(dPrice);
	dFees	+=	objFees.GetInsertionFee(dPrice,nCategory);

	if (CHECKED(pBold))
	{
		dFees	+=	objFees.GetFee(BoldFee, dPrice);

		*mpStream <<	"<li>"
						"You requested a boldface listing, which will be charged a "
						"non-refundable ";

		currencyWidget.SetNativeAmount(objFees.GetFee(BoldFee, dPrice));
		currencyWidget.SetNativeCurrencyId(feeCurrencyId);
		currencyWidget.SetBold(true);
		currencyWidget.EmitHTML(mpStream);

		*mpStream <<	" fee at this time.<p>\n";
	}

	if (CHECKED(pSuperFeatured))
	{
		dFees	+=	objFees.GetFee(NewFeaturedFee);

		*mpStream <<	"<li>"
						"You requested a featured auction, which will be charged a "
						"non-refundable ";

		currencyWidget.SetNativeAmount(objFees.GetFee(NewFeaturedFee));
		currencyWidget.SetNativeCurrencyId(feeCurrencyId);
		currencyWidget.SetBold(true);
		currencyWidget.EmitHTML(mpStream);

		*mpStream <<	" fee at this time.\n";
	}

	if (CHECKED(pFeatured))
	{
		dFees	+=	objFees.GetFee(NewCategoryFeaturedFee);

		*mpStream <<	"<li>"
						"You requested a featured category auction, which will be charged a "
						"non-refundable ";

		currencyWidget.SetNativeAmount(objFees.GetFee(NewCategoryFeaturedFee));
		currencyWidget.SetNativeCurrencyId(feeCurrencyId);
		currencyWidget.SetBold(true);
		currencyWidget.EmitHTML(mpStream);
		
		*mpStream <<	" fee at this time.\n";
	}
	
	//gallery
	if (gallery == Gallery)
	{
		dFees	+=	objFees.GetFee(GalleryFee);

		//promotion period for gallery 00:00:00 Feb-21-99
		if (FreeGallery)
		{
			*mpStream <<	"<li>"
							"You requested to be included in the Gallery. The ";

			currencyWidget.SetNativeAmount(objFees.GetFee(GalleryFee));
			currencyWidget.SetNativeCurrencyId(feeCurrencyId);
			currencyWidget.SetBold(false);
			currencyWidget.EmitHTML(mpStream);

			// FREEGALLERY
			*mpStream  <<	" fee will be waived through June 3rd, 1999.</strong>\n";

			dFees	-=	objFees.GetFee(GalleryFee);
		}

		else

		{
			*mpStream <<	"<li>"
							"You requested to be included in the Gallery for ";

			currencyWidget.SetNativeAmount(objFees.GetFee(GalleryFee));
			currencyWidget.SetNativeCurrencyId(feeCurrencyId);
			currencyWidget.SetBold(true);
			currencyWidget.EmitHTML(mpStream);

			*mpStream << ". ";
		}
				
	}

	//featured gallery
	if (gallery == FeaturedGallery)
	{
		dFees	+=	objFees.GetFee(GalleryFeaturedFee);

		*mpStream <<	"<li>"
						"You requested to be featured in the Gallery for an additional ";

		currencyWidget.SetNativeAmount(objFees.GetFee(GalleryFeaturedFee));
		currencyWidget.SetNativeCurrencyId(feeCurrencyId);
		currencyWidget.SetBold(true);
		currencyWidget.EmitHTML(mpStream);

		*mpStream << 	". \n";
	}


// Lena - gift icon

	if (iconType > 0 && iconType != RosieIcon)
	{
		dFees	+=	objFees.GetFee(GiftIconFee);

		*mpStream <<	"<li>"
						"You requested a Great Gift auction, which will be charged a "
						"non-refundable ";

		currencyWidget.SetNativeAmount(objFees.GetFee(GiftIconFee));
		currencyWidget.SetNativeCurrencyId(feeCurrencyId);
		currencyWidget.SetBold(true);
		currencyWidget.EmitHTML(mpStream);

		*mpStream <<	" fee at this time.\n";
	}

	*mpStream	  <<	"</ul>"
						"<blockquote>"
						"<b>Total Fees</b>: ";

	currencyWidget.SetNativeAmount(dFees);
	currencyWidget.SetBold(false);
	currencyWidget.SetNativeCurrencyId(feeCurrencyId);
	currencyWidget.EmitHTML(mpStream);

	*mpStream	  <<	"</blockquote>";

	// nsacco 08/03/99
	if (currencyId != accountCurrencyId)
	{
		*mpStream << "<p><font color=\"#FF0000\"><strong>"
					 "Since your item is listed in a currency different from "
					 "the one you will be billed in, your item's fees "
					 "will be converted to your billing currency using our daily exchange "
					 "rate."
					 "</strong></font>\n";
	}

	if  (objFees.CheckForAutomotiveListing(pCategory->GetId()))
	{
		*mpStream	  << "<p>"
						"If your item receives bids you will be "
						"charged a fixed final value fee of ";

		currencyWidget.SetNativeAmount(objFees.GetFee(AutoFinalValueFee));
		currencyWidget.SetNativeCurrencyId(feeCurrencyId);
		currencyWidget.EmitHTML(mpStream);
		
	}
	else if (objFees.CheckForRealEstateListing(pCategory->GetId()))
	{
				// nsacco 08/02/99 remove
				//*mpStream	  <<	"</blockquote>";
	}
	else
    {
		*mpStream	  << "<p>"
						"If your item receives bids, you will be charged a final value fee based "
						"on the closing value of the auction. This fee is ";

		currencyWidget.SetNativeCurrencyId(currencyId);
		// old:
		//currencyWidget.SetNativeCurrencyId(Currency_USD); // all charges are in $


		// nsacco 06/16/99
		// TODO use variables to store the fees
		// TODO - a single statement should work since the objFees is constructed with the
		// currencyId equal to the auction currency.
		switch (currencyId)
		{
		case Currency_GBP:
		*mpStream << 	"5.0% of the value up to ";

		currencyWidget.SetNativeAmount(objFees.GetFee(FinalValueLevel1Cutoff));	
		currencyWidget.EmitHTML(mpStream);

		*mpStream << ", 2.5% of the value from ";
		
		currencyWidget.EmitHTML(mpStream);
		
		*mpStream << " up to ";
		
		currencyWidget.SetNativeAmount(objFees.GetFee(FinalValueLevel2Cutoff)); 
		currencyWidget.EmitHTML(mpStream);

		*mpStream <<	", and 1.25% of the value above ";
		
		currencyWidget.EmitHTML(mpStream);
		
		*mpStream <<   ". Complete information "
					   "is in the <a href=\""
				  <<	mpMarketPlace->GetHTMLPath()
				  <<	"agreement-fees.html\">"		
				  <<	"Fees and Credits</a> page.";

		break;
    // PH was about to add Currency_DEM here.. maybe we can put the threshold values
	// in the Fees class as well??

	
	// todo - add Currency_DEM
	
	// nsacco 08/03/99
	case Currency_CAD:
		*mpStream << 	"5.0% of the value up to ";

		currencyWidget.SetNativeAmount(objFees.GetFee(FinalValueLevel1Cutoff)); 
		currencyWidget.EmitHTML(mpStream);

		*mpStream << ", 2.5% of the value from ";
		
		currencyWidget.EmitHTML(mpStream);
		
		*mpStream << " up to ";
		
		currencyWidget.SetNativeAmount(objFees.GetFee(FinalValueLevel2Cutoff)); 
		currencyWidget.EmitHTML(mpStream);

		*mpStream <<	", and 1.25% of the value above ";
		
		currencyWidget.EmitHTML(mpStream);
		
		*mpStream <<   ". Complete information "
					   "is in the <a href=\""
				  <<	mpMarketPlace->GetHTMLPath()
				  <<	"agreement-fees.html\">"		
				  <<	"Fees and Credits</a> page.";
		break;

	case Currency_AUD:
		*mpStream << 	"5.0% of the value up to ";

		currencyWidget.SetNativeAmount(objFees.GetFee(FinalValueLevel1Cutoff)); 
		currencyWidget.EmitHTML(mpStream);

		*mpStream << ", 2.5% of the value from ";
		
		currencyWidget.EmitHTML(mpStream);
		
		*mpStream << " up to ";
		
		currencyWidget.SetNativeAmount(objFees.GetFee(FinalValueLevel2Cutoff)); 
		currencyWidget.EmitHTML(mpStream);

		*mpStream <<	", and 1.25% of the value above ";
		
		currencyWidget.EmitHTML(mpStream);
		
		*mpStream <<   ". Complete information "
					   "is in the <a href=\""
				  <<	mpMarketPlace->GetHTMLPath()
				  <<	"agreement-fees.html\">"		
				  <<	"Fees and Credits</a> page.";
		break;
		
	case Currency_USD:
	default:
		*mpStream << 	"5.0% of the value up to ";

			currencyWidget.SetNativeAmount(objFees.GetFee(FinalValueLevel1Cutoff)); 
			currencyWidget.EmitHTML(mpStream);

			*mpStream << ", 2.5% of the value from ";
		
			currencyWidget.EmitHTML(mpStream);
		
			*mpStream << " up to ";
		
			currencyWidget.SetNativeAmount(objFees.GetFee(FinalValueLevel2Cutoff)); 
			currencyWidget.EmitHTML(mpStream);

			*mpStream <<	", and 1.25% of the value above ";
		
			currencyWidget.EmitHTML(mpStream);
		
		*mpStream <<   ". Complete information "
					   "is in the <a href=\""
				  <<	mpMarketPlace->GetHTMLPath()
				  <<	"help/sellerguide/selling-fees.html\">"
				  <<	"Fees and Credits</a> page.";
		break;
		}

	}

	// If the item is listed in something other than the account currency, 
	// indicate (for now) that we'll 
	// use the current, daily exchange rate to convert from pounds to dollars
	// to calculate the FVF.

	// nsacco 06/17/99
	if (currencyId != accountCurrencyId)
	{
		*mpStream << "<p><font color=\"#FF0000\"><strong>"
					 "Since your item is listed in a currency different from "
					 "the one you will be billed in, your item's final value fee will "
					 "be calculated using the auction currency. The final value fee "
					 "will then be converted to your billing currency using our daily exchange "
					 "rate."
					 "</strong></font>\n";

		*mpStream << "<p><center><table border=\"1\" cellpadding=\"5\">\n"
				  << "<tr>\n"
				  << "<td align=\"center\"><strong>Auction Currency</strong></td>\n"
				  << "<td align=\"center\"><strong>Billing Currency</strong></td>\n"
				  << "</tr>\n"
				  << "<tr>\n"
				  << "<td align=\"center\">\n"
					 // the auction currency
				  << mpMarketPlace->GetCurrencies()->GetCurrency(currencyId)->GetNamePlural()
				  << "</td>\n"
				  << "<td align=\"center\">\n"
					 // the billing currency
				  << mpMarketPlace->GetCurrencies()->GetCurrency(accountCurrencyId)->GetNamePlural()
				  << "</td>\n"
				  << "</tr>\n"
				  << "</table></center>\n";


		// TODO - add a row to display the exchange rate 
	}

	// OK, get new item #
	nItemNo	= mpItems->GetNextItemId();

	// crypt it
	sprintf(cItemNo, "%d", nItemNo);
	sprintf(cSalt, "%d", mpUser->GetId() + nItemNo + 3);
	pCryptedItemNo = crypt(cItemNo, cSalt);


	*mpStream <<	"<p><form method=post action=\"eBayISAPI.dll?AddNewItem\">"
					"<input type=hidden name=userid value=\""
			  <<	pUserId
			  <<	"\">"
					"<input type=hidden name=pass value=\""
			  <<	pPass
			  <<	"\">"
			 		"<input type=hidden name=itemno value=\""
			  <<	cItemNo
			  <<	"\">"
			 		"<input type=hidden name=title value=\""
			  <<	pNewTitle
			  <<	"\">"
					"<input type=hidden name=reserve value=\""
			  <<	dReserve
			  <<	"\">"
					"<input type=hidden name=startprice value=\""
			  <<	dStartPrice
			  <<	"\">"
					"<input type=hidden name=quant value=\""
			  <<	nQuantity
			  <<	"\">"
					"<input type=hidden name=duration value=\""
			  <<	pDuration
			  <<	"\">"
					"<input type=hidden name=location value=\""
			  <<	pLocation
			  <<	"\">"
					"<input type=hidden name=countryid value=\""
			  <<	countryId
			  <<	"\">"
					"<input type=hidden name=bold value=\""
			  <<	pBold
			  <<	"\">"
					"<input type=hidden name=featured value=\""
			  <<	pFeatured
			  <<	"\">"
					"<input type=hidden name=giftIcon value=\""
			  <<	iconType
			  <<	"\">"
					"<input type=hidden name=moneyOrderAccepted value=\""
			  <<	pMoneyOrderAccepted
			  <<	"\">"
					"<input type=hidden name=personalChecksAccepted value=\""
			  <<	pPersonalChecksAccepted
			  <<	"\">"
					"<input type=hidden name=visaMasterCardAccepted value=\""
			  <<	pVisaMasterCardAccepted
			  <<	"\">"
					"<input type=hidden name=discoverAccepted value=\""
			  <<	pDiscoverAccepted
			  <<	"\">"
					"<input type=hidden name=amExAccepted value=\""
			  <<	pAmExAccepted
			  <<	"\">"
					"<input type=hidden name=otherAccepted value=\""
			  <<	pOtherAccepted
			  <<	"\">"
					"<input type=hidden name=onlineEscrow value=\""
			  <<	pOnlineEscrowAccepted
			  <<	"\">"
					"<input type=hidden name=paymentCOD value=\""
			  <<	pCODAccepted
			  <<	"\">"
					"<input type=hidden name=paymentSeeDescription value=\""
			  <<	pPaymentSeeDescription
			  <<	"\">"
					"<input type=hidden name=sellerPaysShipping value=\""
			  <<	pSellerPaysShipping
			  <<	"\">"
					"<input type=hidden name=buyerPaysShippingFixed value=\""
			  <<	pBuyerPaysShippingFixed
			  <<	"\">"
					"<input type=hidden name=buyerPaysShippingActual value=\""
			  <<	pBuyerPaysShippingActual
			  <<	"\">"
					"<input type=hidden name=shippingSeeDescription value=\""
			  <<	pShippingSeeDescription
			  <<	"\">"
					"<input type=hidden name=shippingInternationally value=\""
			  <<	pShippingInternationally
			  <<	"\">"
			  // nsacco 07/27/99 
			  // new shipping options and params
					"<input type=\"hidden\" name=\"northamerica\" value=\""
			  <<	pShipToNorthAmerica
			  <<	"\">"
					"<input type=\"hidden\" name=\"europe\" value=\""
			  <<	pShipToEurope
			  <<	"\">"
					"<input type=\"hidden\" name=\"oceania\" value=\""
			  <<	pShipToOceania
			  <<	"\">"
					"<input type=\"hidden\" name=\"asia\" value=\""
			  <<	pShipToAsia
			  <<	"\">"
					"<input type=\"hidden\" name=\"southamerica\" value=\""
			  <<	pShipToSouthAmerica
			  <<	"\">"
					"<input type=\"hidden\" name=\"africa\" value=\""
			  <<	pShipToAfrica
			  <<	"\">"
					"<input type=\"hidden\" name=\"siteid\" value=\""
			  <<	siteId
			  <<	"\">"
					"<input type=\"hidden\" name=\"language\" value=\""
			  <<	descLang
			  <<	"\">"
			  // end new shipping options and params
					"<input type=hidden name=superfeatured value=\""
			  <<	pSuperFeatured
			  <<	"\">"
			  		"<input type=hidden name=gallery value=\""
			  <<	gallery
			  <<	"\">"
					"<input type=hidden name=private value=\""
			  <<	pPrivate
			  <<	"\">"
					"<input type=hidden name=desc value=\""
			  <<	pNewDescription
			  <<	"\">"
			  <<	"<input type=hidden name=picurl value=\""
			  <<	pPicUrl
			  <<	"\">"
			  <<	"<input type=hidden name=galleryurl value=\""
			  <<	pGalleryUrl
			  <<	"\">"
			  <<	"<input type=hidden name=category value=\""
			  <<	selectedCategory
			  <<	"\">"
					"<input type=hidden name=key value=\""
			  <<	pCryptedItemNo
			  <<	"\">"
					"<input type=hidden name=currencyid value=\""
			  <<	currencyId
			  <<	"\">"
					"<input type=hidden name=zip value=\""
			  <<	pTempZip
			  <<	"\">"
			  <<    "\n";

	// if it is free relisting, added information about the old item
	if (FreeRelisting)
	{
		*mpStream	<<	"<input type=hidden name=olditem value=\""
					<<	pOldItemNo
					<<	"\">\n"
						"<input type=hidden name=oldkey value=\""
					<<	pOldKey
					<<	"\">";
					"<p><p>\n\n";
	}

	*mpStream <<	"Click this button to submit your listing. <a href=\"";
	*mpStream <<	mpMarketPlace->GetHTMLPath();
	*mpStream <<	"help/basics/n-selling.html\">Click here to cancel.</a><br>\n"
			  <<	"<blockquote><input type=submit value=\"submit my listing\"></blockquote><p>";

	*mpStream 	  <<	"</form>";

	*mpStream <<	"<p>"
			  <<	mpMarketPlace->GetFooter()
			  <<	flush;


	free(pCryptedItemNo);

	delete [] pNewDescription;
	delete [] pNewTitle;

	CleanUp();

}

// Check whether relist will be free
bool clseBayApp::IsRelistFree(const char* pOldItemNo, int nQuantity, double dPrice)
{
	clsItem*	pOldItem;
	double		OldPrice;
	bool IsNoFree = false;

/*
	// Testing phase, only the following users can be free
	if (mpUser->GetId() != 48978 && 
		mpUser->GetId() != 65618 &&
		mpUser->GetId() != 158320 &&
		mpUser->GetId() != 230851 &&
		mpUser->GetId() != 81708)
	{
		return IsNoFree;
	}
*/

	// the old item should exist
	if (pOldItemNo == NULL || (pOldItem = mpItems->GetItem(atoi(pOldItemNo), false)) == NULL)
	{
		return IsNoFree;
	}

	// the  old item should not be listed ealier than 12/01/97
	if (pOldItem->GetStartTime() < 880963200 )
	{
		delete pOldItem;
		return IsNoFree;
	}

	// The item should belong to the same user
	if (pOldItem->GetSeller() != mpUser->GetId())
	{
		delete pOldItem;
		return IsNoFree;
	}

	// the item should have zero bid
	if (pOldItem->GetPrice() > pOldItem->GetReservePrice())
	{
		delete pOldItem;
		return IsNoFree;
	}

	// the item should not have been relisted
	if (pOldItem->GetPassword() & ItemRelisted || 
		pOldItem->GetPassword() & ItemRelisting)
	{
		delete pOldItem;
		return IsNoFree;
	}

	// It should not be a dutch auction
	if (nQuantity > 1 || pOldItem->GetQuantity() > 1)
	{
		delete pOldItem;
		return IsNoFree;
	}

	// OldPrice = max(StartPrice, ReservePrice
	OldPrice = pOldItem->GetStartPrice();
	if (OldPrice < pOldItem->GetReservePrice())
	{
		OldPrice = pOldItem->GetReservePrice();
	}

	// The new insertion fee should not be higher than the old one
	if (pOldItem->GetInsertionFee(dPrice) > 
		pOldItem->GetInsertionFee(OldPrice))
	{
		delete pOldItem;
		return IsNoFree;
	}

/*	// the new reserve price should not be higher than the old one
	// if we decide to use this, we should not use the insertion check above.
	// The caller of this function (in clseBayAppAddItem.cpp and
	// clseBayAppVerifyNewItem.cpp) should pass in the Reserve Price.
	if (dPrice > pOldItem->GetReservePrice())
	{
		delete pOldItem;
		return IsNoFree;
	}
*/
/*	
	// Check for earlier ending
	if (difftime(pOldItem->GetStartTime(), pOldItem->GetEndTime()) != 3 * ONE_DAY &&
		difftime(pOldItem->GetStartTime(), pOldItem->GetEndTime()) != 5 * ONE_DAY &&
		difftime(pOldItem->GetStartTime(), pOldItem->GetEndTime()) != 7 * ONE_DAY)
	{
		delete pOldItem;
		return IsNoFree;
	}
*/
	delete pOldItem;

	return true;
}		
