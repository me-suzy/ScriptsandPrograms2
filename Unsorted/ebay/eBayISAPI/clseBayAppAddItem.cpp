/*	$Id: clseBayAppAddItem.cpp,v 1.19.2.5.68.2 1999/08/04 16:51:25 nsacco Exp $	*/
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
//				- 07/13/97 tini			- added superfeatured item
//				- 07/29/97 tini			- added host and visitcount
//				- 02/23/99 anoop	- Check to see if the user verification completes properly.
//				- 04/07/99 kaz			- Check for Police Badge T&C agreement
//				- 04/08/99 kaz			- Changed PB Date to 4/16/99
//				- 04/15/99 kaz			- Check for Police Badge T&C agreement
//				- 07/15/99 nsacco		- added siteid
//				- 07/19/99 nsacco		- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//				- 07/27/99 nsacco		- Added new params to CheckItemData() and AddNewItem() and clsItem()

#include "ebihdr.h"
#include "clsCurrencyWidget.h"
#include "clseBayTimeWidget.h"

extern "C"
{
char *crypt(char *pPassword, char *pSalt);
};

#define CHECKED(x)	(!strcmp(x,"on"))

static const char eBayBlockedItemAppealEmailAddress[] = "itemapl@ebay.com";


void clseBayApp::EmitItemListingDenied(clsItem *pItem,
									   FilterVector *pvFilters,
									   ostream *pStream)
{
	clsMessage *			pMessage = NULL;
	char *					pText = NULL;
	char *					pWord = NULL;
	vector<char *>			vMessageText;
	vector<char *>::iterator	i;

	mpCategories->GetCategoryMessages()->GetMessageText(pItem->GetCategory(), 
																MessageTypeItemBlockedWhenListing,
																&vMessageText,
																true);

	*pStream	<< "<h2>Listing of Item Denied</h2>";
	
	for (i = vMessageText.begin(); i != vMessageText.end(); i++)
	{
		if ((*i) == NULL)
			continue;

		*pStream	<< *i;
	}

#if 0
	// display blocked message to seller!!!!!
	// be sure to tell them how to appeal!
	*pStream	<< "<p>"
				<< "<b>Item #"
				<< pItem->GetId()
				<< " ("
				<< pItem->GetTitle()
				<< ")</b> cannot be listed for auction because the title "
				<< "and/or description contain(s) the following word(s) "
				<< "or phrase(s):<p>";

	FilterVector::iterator	ii;
	for (ii = pvFilters->begin(); ii != pvFilters->end(); ii++)
	{
		if ((*ii) == NULL)
			continue;

		pWord = (*ii)->GetExpression();
		if (pWord == NULL)
			continue;

		*pStream	<< "<li>"
					<< pWord
					<< "</li>";
	}

	*pStream	<< "<p>"
				<< "<b>If you wish to appeal</b>, please send an email which includes "
				<< "your user ID, the item number, and the item title to "
				<< "<a href=\"mailto:"
				<< eBayBlockedItemAppealEmailAddress
				<< "\">"
				<< eBayBlockedItemAppealEmailAddress
				<< "</a>.";
#endif
}

// nsacco 07/27/99 added new shipping params plus siteid and desclang	
void clseBayApp::AddNewItem(CEBayISAPIExtension *pServer,
						  char *pUserId,
						  char *pPassword,
						  char *pItemNo,
						  char *pTitle,
						  char *pReserve,
						  char *pStartPrice,
						  char *pQuantity,
						  char *pDuration,
						  char *pLocation,
						  char *pBold,
						  char *pFeatured,
						  char *pSuperFeatured,
						  char *pPrivate,
						  char *pDesc,
						  char *pPicUrl,
						  char *pCategory,
						  char *pCryptedItemNo,
						  char *pOldItemNo,
						  char *pOldKey,
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
						  CHttpServerContext* pCtxt,
						  char *pGiftIcon,
						  int  gallery,
						  char *pGalleryUrl,
						  int	countryId,
						  int   currencyId,
						  char *pZip
							)
{
	bool		error		= false;
	int			nQuantity	= atoi(pQuantity);
	int			nCategory	= atoi(pCategory);
	int			nItemNo;
	int			nDuration	= atoi(pDuration);
	double		dStartPrice = atof(pStartPrice);
	double		dReserve	= atof(pReserve);
	double		dPrice;
	time_t		tStart,tEnd;

	bool		isFeatured;
	bool		isSuperFeatured;
	bool		isBold;
	bool		isPrivate;
	char		*giftIcon;
	char		*pPictureURL;
	char		*pImageURL;
	char		*pHost;
	char		*pRemoteHost;

	clsItem		*pItem;
	clsAccount	*pAccount;	

	AuctionTypeEnum		auctionType;

	char		*pNewDescription = NULL;
	const char *pSafeDescription = NULL;
	char		*pNewTitle = NULL;
	char		*cleanTitle = NULL; //for display

	// Just so we can email the user
	clsMail		*pMail;
	ostrstream	*pMailStream;
	clsAnnouncement			*pAnnouncement;

	// Date conversions -- ick
// petra	time_t		endTime;
	//struct tm	*pTimeAsTm;
// petra	char		cEndDate[32];
// petra	char		cEndTime[32];
	clseBayTimeWidget	endTimeWidget (mpMarketPlace, -1, -1);
// petra	TimeZoneEnum		timeZone;

	char		subject[512];

	int			mailRc;

	bool		FreeRelisting=false;
	int			password=0;
	clsItem		*pOldItem;

	time_t		theTime;
	bool		freeListing = false;
	
	char*		pTemp;

	clsCategory *pCategoryObj = NULL;

	// nsacco 07/27/99
	unsigned long lShippingRegions = ShipRegion_None;
	int nShippingOptions = SiteOnly;

	// for possible redirect for adult signin
	unsigned long lLength;
	char newURL[255];

	// check for super free relisting.
	//  FreeRelisting just means that if the item sells the 2nd time around
	//  SuperFreeRelisting means that the relisted item is free no matter what, due to system outage
	bool SuperFreeRelisting = false;

	// check for free gallery listing promotion period
	bool FreeGallery = false;

	// check for free UK sellers free insertion fee period
	bool FreeUKInsertionFee = false;
	// zip
	char	NewZip[EBAY_MAX_ZIP_SIZE + 1];
	char*	pTempZip = pZip;

	memset(NewZip, 0, sizeof(NewZip));

	// eNotes
	clsNotes *	pNotes = NULL;
	clsNote *	pNote = NULL;
	char *		pText = NULL;
	
	// Setup
	SetUp();
	// Dynamic Cobrand

	// Get the current time
	theTime	= time(0);
	
	//
	//	** NOTE **
	//	FREE GALLERY PERIOD (a gift to our users)
	//	** NOTE **
	// FREEGALLERY
	if ((clsUtilities::CompareTimeToGivenDate(theTime, 5, 19, 99, 0, 0, 0) >= 0) &&
		(clsUtilities::CompareTimeToGivenDate(theTime, 6, 3, 99, 23, 59, 59) <= 0))
		FreeGallery = true;

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

#ifdef FREE_RELISTING_NEEDED
	if (pNowTimeTM->tm_mon == 11 &&
		pNowTimeTM->tm_mday == 20)
	{
		freeListing	= true;
	}
	else
	{
		freeListing	= false;
	}
#endif

	time(&tStart);
	tEnd = tStart + nDuration*24*60*60;

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);


	// Usual Title and Header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" New Item Confirmation"
			  <<	"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader();

	// Before we do anything, check the user again
	mpUser	= mpUsers->GetAndCheckUserAndPassword(pUserId, pPassword, mpStream);

	if (!mpUser)
	{
		*mpStream  <<	"<p>"
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

	// 02/24/99 Alex Poon
	// Block firearms listing beginning on Sat 2/27/99
	if (CheckForFirearmsListing(nCategory))
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// get the category from the cache -- do NOT delete the memory,
	// cuz it's NOT a copy of the cached object
	pCategoryObj = mpCategories->GetCategory(nCategory, true);
	if (pCategoryObj == NULL)
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
	if (pCategoryObj->isAdult() || pCategoryObj->NoBidAndListForMinor())
	{
		// check whether the user is adult
		if (!HasAdultCookie())
		{
			// calculate URL of adult login page
			strcpy(newURL, mpMarketPlace->GetCGIPath(PageAdultLoginShow));
			strcat(newURL, "eBayISAPI.dll?AdultLoginShow");
			if (pCategoryObj->isAdult())
				strcat(newURL, "&t=1");	// erotica
			else
				if (pCategoryObj->NoBidAndListForMinor())
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

	// kaz: 4/15/99: Support for Police Badge T&C page
	if (nCategory == kPoliceBadgeCatID)
		if (clsUtilities::CompareTimeToGivenDate(time(0),4,16,99,0,0,0) >= 0) 
			if (! mpUser->AcceptedPoliceBadgeAgreement())
	{
		PoliceBadgeAgreementForSelling(pUserId,pPassword);
		*mpStream <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// See if the user can list
	if (!mpMarketPlace->UserCanList(mpUser, mpStream, true))
	{
		*mpStream <<	"<br>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// check whether the itemno has been modified by the user
	if (pItemNo == NULL || !ValidateItemNo(pItemNo, pCryptedItemNo, mpUser->GetId()))
	{
		*mpStream <<	"<h2>Errors in Input Data</h2>"
				  <<	"Please try again.<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}


	nItemNo = atoi(pItemNo);

	// check whether the same item # has been used
	pItem = mpItems->GetItem(nItemNo, false);
	if (pItem)
	{
		cleanTitle = clsUtilities::StripHTML(pItem->GetTitle());
		*mpStream <<	"<h2>The item \""
				  <<	cleanTitle
				  <<	"\" ("
				  <<	pItemNo
				  <<	") has been inserted</h2>"
				  <<	"This might be caused by hitting BACK and submitting again.<p>"
				  <<	mpMarketPlace->GetFooter();
		delete [] cleanTitle;
		delete pItem;
		CleanUp();

		return;
	}


	// ** SPECIAL DAILY ITEM LIMIT CHECK ***
	if (gApp->GetDatabase()->GetDailyItemCount(mpMarketPlace->GetId()) > 2000)
	{
		*mpStream <<	"<h2>Daily Item Limit Exceeded</h2>"
						"Sorry, we have reached the daily limit of items "
						"which may be added to the eBay Beta System. Please watch the Construction "
						"News for information on when we\'ll be expanding the "
						"number of listings on the system."
						"<p>"
						"Of course, you may still "
						"<A HREF="
						"\""
					<<  mpMarketPlace->GetHTMLPath()
					<<	"sell/items/newitem.html"
						"\""
						">"
						"list items"
						"</a>"
						" on eBay!"
						"<p>"
						"Meanwhile, you might want to browse around and look for "
						"bargins in the eBay Beta system!"
						"<p>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}


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
				*mpStream	<<	"<h2>Zip (or Postal) Code Too Long</h2>"
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

	// Let's revalidate the data
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
							pCategory,
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

	// Rounding
	dStartPrice	= RoundToCents(dStartPrice);
	dReserve	= RoundToCents(dReserve);

	if (dReserve > 0 && nQuantity > 1)
	{
		*mpStream <<	"<h2>"
						"Invalid combination: Dutch auction with reserve price"
						"</h2>"
						"A Dutch auction cannot have a reserve price. Please go "
						"back and either change the quantity to 1 or remove the "
						"reserve price."
						"<p>"
					<<	mpMarketPlace->GetFooter();
	}

	// Let's get the next item number
//	nItemNo	= mpItems->GetNextItemId();

	// Various states
	if (strcmp(pBold, "on") == 0)
		isBold	= true;
	else
		isBold	= false;

	if (strcmp(pFeatured, "on") == 0)
		isFeatured	= true;
	else
		isFeatured	= false;

	if (strcmp(pSuperFeatured, "on") == 0)
		isSuperFeatured	= true;
	else
		isSuperFeatured	= false;

	//if we need support mutiple icons,
	if (strcmp(pGiftIcon, "0"))
	{
		giftIcon = new char [4];
		//the old code only show gift icon when giftFlag is 'g'
		//so we need save 'g' in DB until we rollout all cgi machines, then remove this line code
		//if (strcmp(pGiftIcon, "1") == 0)
		//	strcpy(giftIcon, "g");
		//else  -- comment out 5/13/99
		strncpy(giftIcon, pGiftIcon, 3);
		giftIcon[3] = '\0';
	}
	else
		giftIcon	= NULL;

	if (strcmp(pPrivate, "on") == 0)
		isPrivate	= true;
	else
		isPrivate	= false;

	if (!FIELD_OMITTED(pPicUrl) &&
		strcmp(pPicUrl, "http://") != 0)
		pPictureURL	= pPicUrl;
	else
		pPictureURL	= NULL;

	if (nQuantity > 1)
		auctionType	= AuctionDutch;
	else
		auctionType	= AuctionChinese;

	//galleryUrl
	if (gallery == FeaturedGallery || gallery == Gallery)
	{
		if (!FIELD_OMITTED(pGalleryUrl) &&
			strcmp(pGalleryUrl, "http://") != 0)
			pImageURL	= pGalleryUrl;
		else
			pImageURL	= pPicUrl;
	}
	else
		pImageURL = NULL;


	// Fix up description. We shouldn't have to do this,
	// but you never know...
	pNewDescription	= ChangeHTMLQuoteToQuote(pDesc);
//	pSafeDescription = clsUtilities::DrawSafeHTML(pNewDescription);
	pNewTitle		= UnstripHTML(pTitle);

	// calculate the price of the item
	if (dStartPrice < dReserve)
	{
		// in case dutch with reserve is allowed as well
		dPrice = dReserve;

	}
	else
	{
		dPrice = dStartPrice * nQuantity;
	}

	// verfication if there is old item
	if (pOldItemNo && strcmp(pOldItemNo, "default") != 0)
	{
		// get the old item
		if ((pItem = mpItems->GetItem(atoi(pOldItemNo), false)) != NULL)
		{
			// check whether there old itemno has been modified
			if (!ValidateItemNo(pOldItemNo, pOldKey, pItem->GetSeller()))
			{
				// old item # has been modified
				*mpStream <<	"<h2>Errors in Input Data</h2>"
						  <<	"Please try again.<p>"
						  <<	mpMarketPlace->GetFooter();

				CleanUp();
				delete pItem;
				return;
			}
			delete pItem;
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

		// Check whether the item is still free for relisting
		FreeRelisting = IsRelistFree(pOldItemNo, nQuantity, dPrice);
		if (!FreeRelisting)
		{
			// other item information has been modified
			*mpStream <<	"<h2>Errors in Input Data</h2>"
					  <<	"Please try again.<p>"
					  <<	mpMarketPlace->GetFooter();

			CleanUp();
			return;
		}
	}

	// Set 'relisted' for both old item and new item if it is
	// a relisting item
	if (FreeRelisting)
	{
		// update the old item
		pOldItem = mpItems->GetItem(atoi(pOldItemNo));
		password = pOldItem->GetPassword();
		password |= ItemRelisted;
		pOldItem->SetPassword(password);
		mpDatabase->UpdateItemPassword(pOldItem);
		delete pOldItem;

		// set the password for the new item
		password = ItemRelisting;
	}

	//
	// Host 
	//

	pHost	= gApp->GetEnvironment()->GetRemoteAddr();
	pRemoteHost = new char [strlen(pHost)+1]; // clsItem will release the memory
	strcpy(pRemoteHost, pHost);

	// nsacco 07/27/99
	// set shipping options
	if (strcmp(pShippingInternationally, "siteonly") == 0)
	{
		nShippingOptions = SiteOnly;
	}
	else if (strcmp(pShippingInternationally, "siteplusregions") == 0)
	{
		nShippingOptions = SitePlusRegions;
	}
	else if (strcmp(pShippingInternationally, "worldwide") == 0)
	{
		nShippingOptions = Worldwide;
	}
	else if (strcmp(pShippingInternationally, "on") == 0)
	{
		// for old items
		nShippingOptions = Worldwide;
	}
	else
	{
		// for old items
		nShippingOptions = SiteOnly;
	}

	// nsacco 07/27/99 new params for shipping options, region, language
	pItem	= new clsItem
				(
				   gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetId(),
				   nItemNo,				// Item Number
				   auctionType,			// Auction Type
				   pNewTitle,			// Title
//				   (char *) pSafeDescription,	// Description
				   (char *) pNewDescription,	// Description
				   pLocation,			// Location
				   mpUser->GetId(),		// Seller
				   mpUser->GetId(),		// Owner
				   nCategory,			// Category
				   nQuantity,			// Quantity
				   (long)tStart,		// Sale Start
				   (long)tEnd,			// Sale End
				   0L,					// Sale Status
				   dStartPrice,			// Start Price
				   dReserve,			// Reserve Price
				   isFeatured,			// Featured?
				   isSuperFeatured,		// SuperFeatured?
				   isBold,				// Bold?
				   isPrivate,			// Private?
				   true,				// Restricted to registered users
				   pRemoteHost,				// Host 
				   pPictureURL,			// Picture URL
				   0L,
				   password,
				   NULL,				// Rowid
				   0,					// Delta
				   giftIcon,
				   pImageURL,
				   (GalleryTypeEnum) gallery,
				   GalleryResultCode(kGalleryNotProcessed),
				   countryId,
				   currencyId,
				   false,				// ended
				   pTempZip,
				   Currency_USD,		// billing currency
				   nShippingOptions,	// where to ship options 07/27/99
				   lShippingRegions,	// Shipping region 07/27/99
				   descLang,			// item language 07/27/99
				   siteId // nsacco 07/27/99
				 );

// Lena - T's and C's	
	pItem->SetPaymentMOCashiers(CHECKED(pMoneyOrderAccepted));
	pItem->SetPaymentPersonalCheck(CHECKED(pPersonalChecksAccepted));
	pItem->SetPaymentVisaMaster(CHECKED(pVisaMasterCardAccepted));
	pItem->SetPaymentDiscover(CHECKED(pDiscoverAccepted));
	pItem->SetPaymentAmEx(CHECKED(pAmExAccepted));
	pItem->SetPaymentOther(CHECKED(pOtherAccepted));
    pItem->SetPaymentEscrow(CHECKED(pOnlineEscrowAccepted));
    pItem->SetPaymentCOD(CHECKED(pCODAccepted));
	pItem->SetPaymentSeeDescription(CHECKED(pPaymentSeeDescription));
	pItem->SetSellerPaysShipping(CHECKED(pSellerPaysShipping));
	pItem->SetBuyerPaysShippingActual(CHECKED(pBuyerPaysShippingActual));
	pItem->SetBuyerPaysShippingFixed(CHECKED(pBuyerPaysShippingFixed));
	pItem->SetShippingSeeDescription(CHECKED(pShippingSeeDescription));
	// nsacco 07/27/99 removed pItem->SetShippingInternationally

	// nsacco 07/27/99 set shipping regions
	pItem->SetShipToRegion(ShipRegion_NorthAmerica, CHECKED(pShipToNorthAmerica));
	pItem->SetShipToRegion(ShipRegion_Europe, CHECKED(pShipToEurope));
	pItem->SetShipToRegion(ShipRegion_Oceania, CHECKED(pShipToOceania));
	pItem->SetShipToRegion(ShipRegion_Asia, CHECKED(pShipToAsia));
	pItem->SetShipToRegion(ShipRegion_SouthAmerica, CHECKED(pShipToSouthAmerica));
	pItem->SetShipToRegion(ShipRegion_Africa, CHECKED(pShipToAfrica));
	

	FilterVector vFilters;
	ActionType action = ActionTypeDoNothing;
	clsItem		*pBlockedItem;

	// Do we need to screen items in this category?
	if (pCategoryObj->GetScreenItems())
	{
		// Check to see if item exists first
		pBlockedItem = mpItems->GetItem(nItemNo, false, NULL, 0, true);
		if (pBlockedItem)
		{
			// Item # already there, cleanup and exit
			cleanTitle = clsUtilities::StripHTML(pItem->GetTitle());
			*mpStream <<	"<h2>The item \""
					  <<	cleanTitle
					  <<	"\" ("
					  <<	pItemNo
					  <<	") has been blocked.</h2>"
					  <<	"This might be caused by hitting BACK and submitting again.<p>"
					  <<	mpMarketPlace->GetFooter();
			delete [] cleanTitle;
			delete pItem;
			delete pBlockedItem;
			CleanUp();

			return;
		}

		// Screen the item against filters for new category
		action = AdminScreenItem(pItem,
								 mpUser,
								 &vFilters,
								 ScreenItemOnListing,
								 mpStream);

		// Does the listing need to be blocked?
		if (action & ActionTypeBlockListing)
		{

			
			// Get the current time so we can set the end of auction time
			theTime	= time(0);

			// Set the time to now before we add it to the blocked table.
			pItem->SetEndTime(theTime);
			
			// Be sure to add the item to the BLOCKED ITEMS table!!
			mpMarketPlace->GetItems()->AddItem(pItem, true);

			// Display a message to the seller which includes what words/phrases
			// caused the category change to be disallowed
			EmitItemListingDenied(pItem, &vFilters, mpStream);

			*mpStream	<< "<p>"
						<< mpMarketPlace->GetFooter() << flush;

			// Clean Filter
			vFilters.erase(vFilters.begin(), vFilters.end());

			// Clean up and return cuz we're done
			CleanUp();
			return;
		}
#if 0
		else if (action & ActionTypeFlagListing)
		{
			*mpStream	<< "Item #"
						<<	pItem->GetId()
						<< " ("
						<< pItem->GetTitle()
						<< ") has been flagged for review.";
		}
#endif
	}

	// Item got past screening, so let's continue...

	// Now we can add it to the active items table.
	mpMarketPlace->GetItems()->AddItem(pItem);

	// Add an entry so we get it picked up in the Gallery.
	if (gallery == Gallery || gallery == FeaturedGallery)
	{
		clsGalleryChangedItem item;
		
		item.mID = pItem->GetId();
		item.mSequenceID = gApp->GetDatabase()->GetNextGallerySequence();
		strcpy(item.mURL, pImageURL);
		item.mState = kGalleryNotProcessed;
		item.mStartTime = pItem->GetStartTime();
		item.mEndTime = pItem->GetEndTime();
		item.mAttempts = -1;
		item.mLastAttempt = time(NULL);

		bool appendResult = gApp->GetDatabase()->AppendGalleryChangedItem(item);
	}

	pAccount	= mpUser->GetAccount();
	if (FreeRelisting)
	{
		pAccount->ChargeInsertionFee(pItem, pOldItemNo);	
	}
	else
	{
		pAccount->ChargeInsertionFee(pItem);
	}

	//samuel au, 4/7/99
	// When UK site first launches, waive insertion fee
	// So, need the following to credit user's account
	// NOTE: this is just for promotional purpose
	
	if (mpUser->GetCountryId() == Country_UK) // check if user is from UK
	{
		// check to see if this offer expires, which
		// will be on July 1st, 1999 0:00
		time_t	now;
		
		now = time(0);
		// compare if now is before the expiration date
		if (clsUtilities::CompareTimeToGivenDate(now, 
			7, 1, 99, 0, 0, 0) == -1)
		{
			FreeUKInsertionFee = true;
			pAccount->ApplyInsertionFeeCredit(pItem, 
				"Promotional Offer for UK sellers: No Insertion Fee!");
		}
	}
	//end

	if (isBold)
		pAccount->ChargeBoldFee(pItem);

	if (isFeatured)
		pAccount->ChargeCategoryFeaturedFee(pItem);

	if (isSuperFeatured)
		pAccount->ChargeFeaturedFee(pItem);

	if (giftIcon != NULL)
		pAccount->ChargeGiftIconFee(pItem);

	if (gallery	== FeaturedGallery)
	{
		pAccount->ChargeFeaturedGalleryFee(pItem);
	}

	if (gallery	== Gallery)
	{
		pAccount->ChargeGalleryFee(pItem);

		//promotion period for gallery 00:00:00 Feb-21-99
		if (FreeGallery )
		{
			pAccount->CreditGalleryFee(pItem);
			//mark it, then we won't give user credit twice
			pItem->SetHasGalleryCredit(true);
		}
	}

	// Let's try and mail out the confirmation notice FIRST
// petra	endTime		= pItem->GetEndTime();

// petra	timeZone = mpMarketPlace->GetCurrentTimeZone();
// petra	endTimeWidget.SetTime(endTime);
// petra	endTimeWidget.SetTimeZone(timeZone);
// petra	endTimeWidget.BuildDateString(cEndDate);
// petra	endTimeWidget.BuildTimeString(cEndTime);
	
	pMail	= new clsMail;

	pMailStream	= pMail->OpenStream();

	// Prepare the stream
	pMailStream->setf(ios::fixed, ios::floatfield);
	pMailStream->setf(ios::showpoint, 1);
	pMailStream->precision(2);

	// Now, the goods!
	// emit general announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(General,Header,
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMailStream << pTemp;
		*pMailStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	};

	// emit list notice announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(ListNotice,Header,
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMailStream << pTemp;
		*pMailStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	};
	
	*pMailStream <<	"\n\n"
					"Dear "
				 <<	mpUser->GetUserId()
				 <<	",\n"
					"\n"
					"Let the trading begin--your item is listed!\n\n";

	*pMailStream <<	mpMarketPlace->GetCGIPath(PageViewItem)
				 << "eBayISAPI.dll?ViewItem&item="
				 << pItem->GetId();
				 
	*pMailStream << "\n\nTitle of item:                   "
				 <<	pItem->GetTitle()
				 <<	"\n"
					"Minimum bid:                     ";

	clsCurrencyWidget currencyWidget(mpMarketPlace, currencyId, pItem->GetStartPrice());
// petra	currencyWidget.SetForMail(true);
	currencyWidget.EmitHTML(pMailStream);

	*pMailStream <<	"\n"
					"Reserve price (if any):          ";

	currencyWidget.SetNativeAmount(pItem->GetReservePrice());
	currencyWidget.EmitHTML(pMailStream);
					 
	*pMailStream <<	"\n"
				 <<	"Quantity:                        "
				 <<	pItem->GetQuantity()
				 <<	"\n"
				 <<	"Auction Ends on:                 ";

	endTimeWidget.SetDateTimeFormat (2, -1);		// petra
	endTimeWidget.SetTime (pItem->GetEndTime() );	// petra
	endTimeWidget.EmitHTML (pMailStream);			// petra
// petra				 <<	cEndDate
	*pMailStream <<	" at ";							// petra
	endTimeWidget.SetDateTimeFormat (-1, 2);		// petra
	endTimeWidget.EmitHTML (pMailStream);			// petra
// petra				 <<	cEndTime
	*pMailStream <<	"\n"							// petra
					"\n"
					"Your item number is:             "
				 <<	pItem->GetId()
				 <<	"\n"
					"\n"
					"Fees:"
					"\n";
	//samuel au, 4/13/99
	// temporary here
	if (FreeUKInsertionFee)
	{
		*pMailStream <<	"UK Free Insertion Fee Promotion (until June 30th, 1999)"
						"\n";
	}
	//end
	*pMailStream << "Insertion Fee:                   ";

	currencyWidget.SetNativeAmount(pItem->GetInsertionFee(dPrice));
	currencyWidget.SetNativeCurrencyId(Currency_USD); // all fees in dollars for now
	currencyWidget.EmitHTML(pMailStream);

	//samuel, 4/9/99
	// this is going to be temporarily, to waive insertion fee for all UK users
	if (FreeUKInsertionFee)
	{
		*pMailStream << "\n"
					 << "Insertion Fee Credit:           -";
		currencyWidget.SetNativeAmount(pItem->GetInsertionFee(dPrice));
		currencyWidget.SetNativeCurrencyId(Currency_USD); // all fees in dollars for now
		currencyWidget.EmitHTML(pMailStream);
	}
	//end

	if (FreeRelisting)
	{
		if (SuperFreeRelisting)
			*pMailStream <<	" (Insertion Fee waived for temporary free relisting due to recent system outage!)";// ALEX for free relisting
		else
			*pMailStream <<	" (It will be refunded if the item is sold!)";
	}
	else
	{
		if (freeListing)
		{
			*pMailStream << " (Fee Refunded! eBay\'s 1998 Free Listing Day!)";
		}
	}
	*pMailStream <<	"\n";

	if (isBold)
	{
		*pMailStream <<	"Boldfacing Fee:                  ";

		currencyWidget.SetNativeAmount(pItem->GetBoldFee(dPrice));
		currencyWidget.SetNativeCurrencyId(Currency_USD); // all fees in dollars for now
		currencyWidget.EmitHTML(pMailStream);

		*pMailStream <<	"\n";
	}

	if (isFeatured)
	{
		*pMailStream <<	"Featured Category Auction Fee:   ";

		currencyWidget.SetNativeAmount(pItem->GetCategoryFeaturedFee());
		currencyWidget.SetNativeCurrencyId(Currency_USD); // all fees in dollars for now
		currencyWidget.EmitHTML(pMailStream);

		*pMailStream <<	"\n";
	}

	if (isSuperFeatured)
	{
		*pMailStream <<	"Featured Auction Fee:            ";

		currencyWidget.SetNativeAmount(pItem->GetFeaturedFee());
		currencyWidget.SetNativeCurrencyId(Currency_USD); 
		currencyWidget.EmitHTML(pMailStream);

		*pMailStream <<	"\n";
	}

	if (giftIcon != NULL)
	{
		*pMailStream <<	"Great Gift Fee:            ";

		currencyWidget.SetNativeAmount(pItem->GetGiftIconFee(pItem->GetGiftIconType()));
		currencyWidget.SetNativeCurrencyId(Currency_USD); // all fees in dollars for now
		currencyWidget.EmitHTML(pMailStream);

//			*pMailStream <<	mpMarketPlace->GetGiftIconFee(pItem->GetGiftIconType());

		*pMailStream <<	"\n";
	}

	if (gallery	== Gallery)
	{
		*pMailStream <<	"Gallery Fee:                     ";

		currencyWidget.SetNativeAmount(pItem->GetGalleryFee());
		currencyWidget.SetNativeCurrencyId(Currency_USD); // all fees in dollars for now
		currencyWidget.EmitHTML(pMailStream);

		*pMailStream <<	"\n";

		// FREEGALLERY
		if (FreeGallery)
		{
			*pMailStream <<	"Gallery Refund:                     ";

			currencyWidget.SetNativeAmount(-pItem->GetGalleryFee());
			currencyWidget.SetNativeCurrencyId(Currency_USD); // all fees in dollars for now
			currencyWidget.EmitHTML(pMailStream);

			*pMailStream <<	"\n";
		}
	}

	if (gallery	== FeaturedGallery)
	{
		*pMailStream <<	"Featured Gallery Fee:         ";

		currencyWidget.SetNativeAmount(pItem->GetFeaturedGalleryFee());
		currencyWidget.SetNativeCurrencyId(Currency_USD); // all fees in dollars for now
		currencyWidget.EmitHTML(pMailStream);

		*pMailStream <<	"\n";
	}

	*pMailStream <<	"\n\n"
					"*It's important that you make a note of these details, "
					"especially the item number; you will need it in any "
					"correspondence with us about your listing, and in order "
					"to make any changes to your listing.\n"
					"\n"
					"*You can change your item listing as long as no one has "
					"bid on it yet. Just go to eBay.com, click on your listing, "
					"and choose Update Item. If you can't find your listing, "
					"enter its title in the search window.\n"
					"\n"
					"*For more tools to help you manage your listing, including "
					"adding information or ending the auction early, check out "
					"the services available in eBay's Buying and Selling Tools section, at\n"
					"http://pages.ebay.com/services/buyandsell/index.html\n"
					"\n"
					"Best of luck!\n";

	// emit general footer announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(General,Footer,
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMailStream << pTemp;
		*pMailStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	};

	// emit list notice footer announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(ListNotice,Footer,
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMailStream << pTemp;
		*pMailStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	};

	if (mpUser->SendList()) { 
		sprintf(subject,
				"%s Listing Confirmation - Item %d: %s",
				mpMarketPlace->GetCurrentPartnerName(),
				pItem->GetId(),
				pItem->GetTitle());

		mailRc =	pMail->Send(mpUser->GetEmail(),
								(char *)mpMarketPlace->GetConfirmEmail(),
								subject);

		// We don't need no mail now
		delete	pMail;

		if (!mailRc)
		{
			*mpStream <<	"<h2>Warning: Unable to send listing confirmation notice</h2>"
							"Sorry, we could not send you your listing confirmation "
							"notice via electronic mail. This is probably a temporary "
							"problem, but if it persists, or you are having problems sending "
							"or receiving mail, you may wish to contact your service provider. "
							"Since "
					  <<	mpMarketPlace->GetCurrentPartnerName()
					  <<	" cannot send this e-mail, this is the <b>only</b> confirmation "
							"you will receive for your listing."
							"<br>";
		}
	}

	if (freeListing)
	{
		*mpStream <<	"<h2>eBay Free Listing Day 1998!</h2>"
						"<font color=red><blink>Insertion fee refunded!</blink></font>"
						"<br>";
	}

	cleanTitle = clsUtilities::StripHTML(pTitle);
	*mpStream <<	"<h2>Auction has begun!</h2>"
					"Your new ad has been saved. <strong>IMPORTANT: "
					"your ad will not show up right away!</strong> Listings are updated throughout the day, "
					"so your ad will be added at the next update."
					"<pre>"
					"Title of item:                 <strong>"
			  <<	cleanTitle
			  <<	"</strong><p>"
					"Your item number is:           <strong>"
			  <<	nItemNo
			  <<	"</strong></pre>"
					"<p>"
					"<strong>Keep these numbers!</strong> You will use this "
					"information, along with the e-mail address you entered, to "
					"identify yourself as the owner of this item. A confirmation "
					"message has been sent to you with the same information. "
					"<p>"
					"You may also use the following URL to refer to your auction "
					"item directly:"
					"<blockquote><samp><a href=\"";
	*mpStream <<	mpMarketPlace->GetCGIPath(PageViewItem)
			  <<	"eBayISAPI.dll?ViewItem&item="
			  <<	nItemNo
			  <<	"\">"
			  <<	mpMarketPlace->GetCGIPath(PageViewItem)
			  <<	"eBayISAPI.dll?ViewItem&item="
			  <<	nItemNo
			  <<	"</a></samp></blockquote>"
					"To increase traffic to your listing, you can post to appropriate newsgroups, "
					"listing the above URL. Please review newsgroup rules before you post.  "
					"Some newsgroups do not allow advertisements."
					"<p>";
	delete [] cleanTitle;
	*mpStream <<	"After adding this item, your account balance is now: ";


			currencyWidget.SetNativeAmount(pAccount->GetBalance());
			currencyWidget.SetNativeCurrencyId(Currency_USD); // all balances in dollars for now
			currencyWidget.SetBold(true);
			currencyWidget.EmitHTML(mpStream);

	*mpStream <<	"<p>";

/*	
	// file reference need to be fixed!!!
	if (!isFeatured)
	{
		*mpStream <<	"If you want your ad to stand out and be listed at the very top of the Category "
						"page, you can do so for an additional fee. Please visit our page on "
						"<a href=\""
				  <<	mpMarketPlace->GetCGIPath(PageFeatured)
				  <<	"eBayISAPI.dll?Featured\">Category Featured Auctions</a> for more information.  ";
	}

	if (!isSuperFeatured)
	{
		*mpStream <<	"If you want your ad to stand out and rotate at random intervals on the eBay homepage, "
						"you can do so for an additional fee. Please visit our page on "
						"<a href=\""
				  <<	mpMarketPlace->GetCGIPath(PageFeatured)
				  <<	"eBayISAPI.dll?Featured\">Featured Auctions</a> for more information. ";
	}
*/
	*mpStream <<	"<p>You can edit your item as long as no one has left a bid. "
					"Choose the \"Update Item\" option on the View Item "
					"page for this item.";
	*mpStream <<	"<p>"
					"If you find you need to end your auction prior to the closing time, you may "
					"do so by using the <strong>End auction</strong> option from the <strong>Seller "
					"Services Menu.</strong>"
					"<p>";

	time_t when = time(0);

	*mpStream <<	mpMarketPlace->GetFooter()
			  <<	flush;

	// Done with the account
	delete		pAccount;
	pAccount	= NULL;

	if (pNewTitle != NULL)
		delete [] pNewTitle;

	if (pNewDescription != NULL)
		delete	[] pNewDescription;

	if (pSafeDescription != NULL)
		delete [] (char *)pSafeDescription;

	if (giftIcon != NULL) 
		delete [] giftIcon;

	delete	pItem;

	CleanUp();

}

bool clseBayApp::ValidateItemNo(char* pItemNo, char* pCryptedItemNo, int UserId)
{
	char	cSalt[10];
	char*	pNewCryptedItemNo;

	int		nItemNo;
	bool	Valid;

	if (pItemNo == NULL && pCryptedItemNo == NULL)
	{
		return true;
	}

	// get the true item#
	nItemNo = atoi(pItemNo);

	// get the new crypted
	sprintf(cSalt, "%d", UserId + nItemNo + 3);
	pNewCryptedItemNo = crypt(pItemNo, cSalt);

	Valid = (strcmp(pNewCryptedItemNo, pCryptedItemNo) == 0);

	free(pNewCryptedItemNo);

	return Valid;
}
