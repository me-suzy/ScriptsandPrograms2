/*	$Id: clseBayAppUpdateItemInfo.cpp,v 1.9.22.2.34.1 1999/08/01 03:01:31 barry Exp $	*/
//
//	File:		clseBayAppUpdateItemInfo.cpp
//
//	Class:		clseBayApp
//
//	Author:		Vicki Shu (vicki@ebay.com)
//
//	Function:	Edit Unbit Items
//
//
//	Modifications:
//				- 09/18/98 vicki	- Created
//				- 07/06/99 soc      - BUG FIX # 2400 make sure we set the new category or the insertion fee will be incorrect
//				- 07/27/99 nsacco	- Added new shipping params to UpdateItemInfo()
//
#pragma warning( disable : 4786 )

#include "ebihdr.h"
#include "vector.h"

#define CHECKED(x)	(!strcmp(x,"on"))

static const char eBayBlockedItemAppealEmailAddress[] = "itemapl@ebay.com";


void clseBayApp::EmitItemUpdateInfoDenied(clsItem *pItem,
										  FilterVector *pvFilters,
										  ostream *pStream)
{
#if 1
	// Display message to tell seller that update is being denied but not why
	*pStream	<< "<h2>Item Update Denied</h2>"
				<< "Sorry, the changes you have made to item "
				<< pItem->GetId()
				<< " ("
				<< pItem->GetTitle()
				<< ") cannot be accepted.";
#else
	FilterVector::iterator	i;

	// Display message to tell seller that update is being denied and why
	*pStream	<< "<h2>Item Update Denied</h2>"
				<< "The changes you have made to item "
				<< pItem->GetId()
				<< " ("
				<< pItem->GetTitle()
				<< ") cannot be accepted because the title and/or description "
				<< "contain(s) the following word(s) and/or phrase(s):<br>";
	
	for (i = pvFilters->begin(); i != pvFilters->end(); i++)
	{
		*pStream	<< "<li>"
					<< (*i)->GetExpression()
					<< "</li>";
	}
#endif

//	*pStream	<< "<p>"
//				<< "If you wish to appeal, please send an email which includes "
//				<< "your user ID, the item number, and the item title to "
//				<< "<a href=\"mailto:"
//				<< eBayBlockedItemAppealEmailAddress
//				<< "\">"
//				<< eBayBlockedItemAppealEmailAddress
//				<< "</a>.";
}


// nsacco 07/27/99 added new params	
bool clseBayApp::UpdateItemInfo(CEBayISAPIExtension *pThis,
									const char *pItemNo,
									const char *pUserId,
									const char *pPass,
									const char *pTitle,  
									const char *pDesc,
									const char *pPicUrl,
									const char *pCategory,
									const char *pMoneyOrderAccepted,
									const char *pPersonalChecksAccepted,
									const char *pVisaMasterCardAccepted,
									const char *pDiscoverAccepted,
									const char *pAmExAccepted,
									const char *pOtherAccepted,
									const char *pOnlineEscrowAccepted,
									const char *pCODAccepted,
									const char *pPaymentSeeDescription,
									const char *pSellerPaysShipping,
									const char *pBuyerPaysShippingFixed,
									const char *pBuyerPaysShippingActual,
									const char *pShippingSeeDescription,
									const char *pShippingInternationally,
									const char *pShipToNorthAmerica,
									const char *pShipToEurope,
									const char *pShipToOceania,
									const char *pShipToAsia,
									const char *pShipToSouthAmerica,
									const char *pShipToAfrica,
									int siteId,
									int descLang,
									char *pRedirectURL
									)
{

    bool					error = false;
	int						item = atoi(pItemNo);
	int						password;

	clsUser					*pSeller = NULL; //need for pricing changes
	clsAccount				*pSellerAccount = NULL; //need for pricing changes
	const char				*pPictureURL = NULL;
	clsCategory				*pCategoryObj = NULL;

	FilterVector			vFilters;
	int						OldCat;

	// nsacco 07/27/99
	unsigned long lShippingRegions = ShipRegion_None;
	int nShippingOption = SiteOnly;


	SetUp();
	//let's get header first
	EmitHeader("Update Your Item Information");

	// Let's get the item
 	if (item && !GetAndCheckItem(item))
	{
	    CleanUp();
	    return false;
	}

	//let's see the num of bid count include conceled and retracted
	if(mpItem->GetAllBidCount() > 0)
	{
		
		// Spacer
		*mpStream <<	"<br>";
		*mpStream <<	"<H2>The item  ("
			<<	pItemNo
			<<	") has received bids</H2>"
			"<p>"
			"You're not allowed to update the item information, "
			"if the item has received any bid even the bid is canceled. <p>"
			<<	mpMarketPlace->GetFooter()
			<<	flush;
		
		
		CleanUp();
		return false;;
	}

	// check the category
	pCategoryObj = mpCategories->GetCategory(atoi(pCategory), true);
	if (pCategoryObj == NULL)
	{
		*mpStream <<	"<p>"
						"The category designated for this item is invalid."
						"<p>"
				  <<	mpMarketPlace->GetFooter()
				  <<	flush;

		CleanUp();
		return false;
	}

	// Set the information needed for screen
	mpItem->SetTitle((char *)pTitle);
    mpItem->SetNewDescription((char *)pDesc);
	OldCat = mpItem->GetCategory();					// keep before override
	mpItem->SetCategory(atoi(pCategory));
	pSeller	= mpUsers->GetUser(mpItem->GetSeller());
   
	// Do we need to screen items in this category?
	if (pCategoryObj->GetScreenItems())
	{
		// Screen the item against filters for new category
		ActionType action = AdminScreenItem(mpItem,
											pSeller,
											&vFilters,
											ScreenItemOnChangeCategory,
											mpStream);
     
		// Does the listing need to be blocked?
		if (action == ActionTypeBlockListing)
		{
			// Display a message to the seller about the category change
			// being denied
			EmitChangeCategoryDenied(mpItem, &vFilters, mpStream);

			*mpStream	<< "<p>"
						<< mpMarketPlace->GetFooter();

			// Clear vector
			vFilters.erase(vFilters.begin(), vFilters.end());

			if (pSeller)
				delete pSeller;

			// Clean up and return cuz we're done
			CleanUp();
			return false;
		}

#if 0   // don't display message to seller by default

		// BUG FIX: 2180 S.Forgaard.  Allow flagged item to be revised.
		else if (action == ActionTypeFlagListing)
		{
			*mpStream	<< "Item #"
						<<	mpItem->GetId()
						<< " ("
						<< mpItem->GetTitle()
						<< ") has been flagged for review.";
		}
#endif
	}

	//if change in or out of cars, we need some accounting
 	if (   (mpItem->CheckForAutomotiveListing() &&  !mpItem->CheckForAutomotiveListing(OldCat)) 
		|| (!mpItem->CheckForAutomotiveListing() && mpItem->CheckForAutomotiveListing(OldCat))
		|| (mpItem->CheckForRealEstateListing() && !mpItem->CheckForRealEstateListing(OldCat)) 
		|| (!mpItem->CheckForRealEstateListing() && mpItem->CheckForRealEstateListing(OldCat))
		)
	{
		//credit first, pItem still has old category
		pSellerAccount	= pSeller->GetAccount();
		mpItem->SetCategory(OldCat);							// set it back for fee
		pSellerAccount->ApplyInsertionFeeCredit(mpItem,NULL);
		// BUG FIX # 2400 7/6/99 Soc -make sure we set the new category or the insertion fee will be incorrect
		mpItem->SetCategory(atoi(pCategory));					// set the new category
		pSellerAccount->ChargeInsertionFee(mpItem);
		delete pSellerAccount;
	}

	// Change got past screening, so let's continue...

	if (!FIELD_OMITTED(pPicUrl) && stricmp(pPicUrl, "http://") != 0)
       pPictureURL = pPicUrl;
	else
       pPictureURL = "";


	mpItem->SetPictureURL((char *)pPictureURL);

	//terms and conditions
	mpItem->SetPaymentMOCashiers(CHECKED(pMoneyOrderAccepted));
	mpItem->SetPaymentPersonalCheck(CHECKED(pPersonalChecksAccepted));
	mpItem->SetPaymentVisaMaster(CHECKED(pVisaMasterCardAccepted));
	mpItem->SetPaymentDiscover(CHECKED(pDiscoverAccepted));
	mpItem->SetPaymentAmEx(CHECKED(pAmExAccepted));
	mpItem->SetPaymentOther(CHECKED(pOtherAccepted));
    mpItem->SetPaymentEscrow(CHECKED(pOnlineEscrowAccepted));
    mpItem->SetPaymentCOD(CHECKED(pCODAccepted));
	mpItem->SetPaymentSeeDescription(CHECKED(pPaymentSeeDescription));
	mpItem->SetSellerPaysShipping(CHECKED(pSellerPaysShipping));
	mpItem->SetBuyerPaysShippingActual(CHECKED(pBuyerPaysShippingActual));
	mpItem->SetBuyerPaysShippingFixed(CHECKED(pBuyerPaysShippingFixed));
	mpItem->SetShippingSeeDescription(CHECKED(pShippingSeeDescription));
	// nsaco 07/27/99 removed old call to SetShippingInternationally

	// nsacco 07/27/99
	// set shipping options
	if (strcmp(pShippingInternationally, "siteonly") == 0)
	{
		nShippingOption = SiteOnly;
	}
	else if (strcmp(pShippingInternationally, "siteplusregions") == 0)
	{
		nShippingOption = SitePlusRegions;
	}
	else if (strcmp(pShippingInternationally, "worldwide") == 0)
	{
		nShippingOption = Worldwide;
	}
	else if (strcmp(pShippingInternationally, "on") == 0)
	{
		// for old items
		nShippingOption = Worldwide;
	}
	else
	{
		// for old items
		nShippingOption = SiteOnly;
	}

	mpItem->SetShippingOption(nShippingOption);
	// end set shipping options

	// nsacco 07/27/99 set shipping regions
	mpItem->SetShipToRegion(ShipRegion_NorthAmerica, CHECKED(pShipToNorthAmerica));
	mpItem->SetShipToRegion(ShipRegion_Europe, CHECKED(pShipToEurope));
	mpItem->SetShipToRegion(ShipRegion_Oceania, CHECKED(pShipToOceania));
	mpItem->SetShipToRegion(ShipRegion_Asia, CHECKED(pShipToAsia));
	mpItem->SetShipToRegion(ShipRegion_SouthAmerica, CHECKED(pShipToSouthAmerica));
	mpItem->SetShipToRegion(ShipRegion_Africa, CHECKED(pShipToAfrica));
	// end set shipping regions

	// set the password for the updated item	
	password = mpItem->GetPassword();
	password |= ItemUpdated;

	mpItem->SetPassword(password);
		
	mpItem->UpdateItem();
  	 	
	//Redirection for production :
	sprintf(pRedirectURL,
		"%seBayISAPI.dll?ViewItem&item=%d",
		 mpMarketPlace->GetCGIPath(PageViewItem),
		 mpItem->GetId());

	if (pSeller)
		delete pSeller;

	CleanUp();
	return true;
}

