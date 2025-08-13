/*	$Id: clsFees.cpp,v 1.5.112.5 1999/08/04 16:51:33 nsacco Exp $	*/
//
//	File:	clsFees.cpp
//
//  Class:	clsFees
//
//	Author:	Barry Boone (barry@ebay.com)
//
//	Function:
//
//				Handles calculating fees related to item info.
//
// Modifications:
//				- 03/06/99 barry	- Created
//				- 06/11/99 nsacco	- Added Australian dollars
//				- 08/02/99 petra	- decided to take exchange stuff out of clsCurrencyWidget
//				- 08/02/99 nsacco	- New fees for UK and AU. Added ListingLevelFees. 
//										Integrated the bill in native currency code.



#include "eBayKernel.h"
#include "clsFees.h"
// petra #include "clsCurrencyWidget.h"
//samuel, 7/22/99
#include "clsExchangeRates.h"

// attempt to make this a little more centralized
// check for automotive listing
bool  clsFees::CheckForAutomotiveListing(int nCategory)
{
	// return true beginning on 4/15/99
	if ((nCategory==1258 || nCategory==1259 || 
		nCategory==1260 || nCategory==2030) &&
		(clsUtilities::CompareTimeToGivenDate(time(0), 4, 24, 99, 0, 0, 0) >= 0))
	{

		return true;
	}

	return false;
}
// check for real estate listing
bool clsFees::CheckForRealEstateListing(int nCategory)
{
	// return true beginning on 4/15/99
	if ((nCategory==1607) &&		
		(clsUtilities::CompareTimeToGivenDate(time(0), 4, 24, 99, 0, 0, 0) >= 0))
	{

		return true;
	}

	return false;
}

// A couple of useful private functions.

bool clsFees::ChargeFVFforGBP()
{
	// This will be a date check eventually, used like this:
	// If the pointer to the item is not null, 
	// take the date to be the item's ending date. 
	// Otherwise, use the current date (localtime) as the ending date.
	// Return (ending date > date we started charging a FVF for GBP).

	return (clsUtilities::CompareTimeToGivenDate(mpItem->GetEndTime(), 
													9, 1, 99, 0, 0, 0) >= 0);
}

bool clsFees::ChargeFVFforCAD()
{
	// This will be a date check eventually, used like this:
	// If the pointer to the item is not null, 
	// take the date to be the item's ending date. 
	// Otherwise, use the current date (localtime) as the ending date.
	// Return (ending date > date we started charging a FVF for CAD).

	return (clsUtilities::CompareTimeToGivenDate(mpItem->GetEndTime(), 
													9, 15, 99, 0, 0, 0) >= 0);
}


// PH added 04/26/99
bool clsFees::ChargeFVFforDEM()
{
	// This will be a date check eventually, used like this:
	// If the pointer to the item is not null, 
	// take the date to be the item's ending date. 
	// Otherwise, use the current date (localtime) as the ending date.
	// Return (ending date > date we started charging a FVF for DEM).

	return (clsUtilities::CompareTimeToGivenDate(mpItem->GetEndTime(), 
													11, 1, 99, 0, 0, 0) >= 0);
}

bool clsFees::ChargeFVFforAUD()
{
	// This will be a date check eventually, used like this:
	// If the pointer to the item is not null, 
	// take the date to be the item's ending date. 
	// Otherwise, use the current date (localtime) as the ending date.
	// Return (ending date > date we started charging a FVF for DEM).

	return (clsUtilities::CompareTimeToGivenDate(mpItem->GetEndTime(), 
													9, 1, 99, 0, 0, 0) >= 0);
}

// Constructors.

clsFees::clsFees(int currency)
{
	SetFees();
	mCurrencyId = currency;
	mpItem = NULL;
	// nsacco 08/02/99
	mBillingCurrencyId = currency;
	mpExchangeRates = gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetCurrencies()->GetExchangeRates();
}

clsFees::clsFees(clsItem *pItem)
{
	SetFees();
	mpItem = pItem;
	// samuel, 7/22/99
	mCurrencyId = mpItem->GetCurrencyId();
	mBillingCurrencyId = mpItem->GetBillingCurrencyId();
	mpExchangeRates = gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetCurrencies()->GetExchangeRates();
}


// Constructor helper.
// Fees listed in native currency
void clsFees::SetFees()
{
	//samuel, 7/22/99
	// SetFees() will now set the fee for that currency rather than in USD.
	// That means all fees in eBayFeesUK will be in GBP rather than USD, and so on.
	eBayFeesUS[FeaturedFee] = 49.95;			// unused
	eBayFeesUS[NewFeaturedFee] = 99.95;
	eBayFeesUS[CategoryFeaturedFee] = 9.95;		// unused
	eBayFeesUS[NewCategoryFeaturedFee] = 14.95;
	eBayFeesUS[BoldFee] = 2.00;
	eBayFeesUS[GiftIconFee] = 1.00;
	eBayFeesUS[GalleryFee] = 0.25;
	eBayFeesUS[GalleryFeaturedFee] = 19.95;
	eBayFeesUS[ItemMoveFee] = 0.00;
	eBayFeesUS[AutoListingFee] = 25.00;
	eBayFeesUS[RealEstateListingFee] = 50.00;
	eBayFeesUS[AutoFinalValueFee] = 25.00;
	eBayFeesUS[ListingLevel1Fee] = 0.25;
	eBayFeesUS[ListingLevel2Fee] = 0.50;
	eBayFeesUS[ListingLevel3Fee] = 1.00;
	eBayFeesUS[ListingLevel4Fee] = 2.00;
	eBayFeesUS[ListingLevel1Range] = 10.00;
	eBayFeesUS[ListingLevel2Range] = 25.00;
	eBayFeesUS[ListingLevel3Range] = 50.00;
	eBayFeesUS[FinalValueLevel1Cutoff] = 25.00;
	eBayFeesUS[FinalValueLevel2Cutoff] = 1000.00;
	

	// nsacco 08/02/99
	// updated UK fees in pounds
	eBayFeesUK[FeaturedFee] = 49.95;			// unused
	eBayFeesUK[NewFeaturedFee] = 19.95;
	eBayFeesUK[CategoryFeaturedFee] = 9.95;		// unused
	eBayFeesUK[NewCategoryFeaturedFee] = 6.95;	
	eBayFeesUK[BoldFee] = 1.00;
	eBayFeesUK[GiftIconFee] = 0.60;
	eBayFeesUK[GalleryFee] = 0.15;
	eBayFeesUK[GalleryFeaturedFee] = 9.95;
	eBayFeesUK[ItemMoveFee] = 0.00;
	eBayFeesUK[AutoListingFee] = 15.00;
	eBayFeesUK[RealEstateListingFee] = 30.00;
	eBayFeesUK[AutoFinalValueFee] = 15.00;
	eBayFeesUK[ListingLevel1Fee] = 0.15;
	eBayFeesUK[ListingLevel2Fee] = 0.30;
	eBayFeesUK[ListingLevel3Fee] = 0.60;
	eBayFeesUK[ListingLevel4Fee] = 1.20;
	eBayFeesUK[ListingLevel1Range] = 5.00;
	eBayFeesUK[ListingLevel2Range] = 15.00;
	eBayFeesUK[ListingLevel3Range] = 30.00;
	eBayFeesUK[FinalValueLevel1Cutoff] = 15.00;
	eBayFeesUK[FinalValueLevel2Cutoff] = 600.00;

	// PH added 04/26/99 >>
    eBayFeesDE[FeaturedFee] = 49.95;			// unused
	eBayFeesDE[NewFeaturedFee] = 179.95;
	eBayFeesDE[CategoryFeaturedFee] = 9.95;		// unused
	eBayFeesDE[NewCategoryFeaturedFee] = 27.95;
	eBayFeesDE[BoldFee] = 4.00;
	eBayFeesDE[GiftIconFee] = 2.00;
	eBayFeesDE[GalleryFee] = 0.45;
	eBayFeesDE[GalleryFeaturedFee] = 34.95;
	eBayFeesDE[ItemMoveFee] = 0.00;
	eBayFeesDE[AutoListingFee] = 45.00;
	eBayFeesDE[RealEstateListingFee] = 90.00;
	eBayFeesDE[AutoFinalValueFee] = 45.00;
	eBayFeesDE[ListingLevel1Fee] = 0.15;
	eBayFeesDE[ListingLevel2Fee] = 0.30;
	eBayFeesDE[ListingLevel3Fee] = 0.60;
	eBayFeesDE[ListingLevel4Fee] = 1.20;
	eBayFeesDE[ListingLevel1Range] = 10.00;
	eBayFeesDE[ListingLevel2Range] = 25.00;
	eBayFeesDE[ListingLevel3Range] = 50.00;
	eBayFeesDE[FinalValueLevel1Cutoff] = 25.00;
	eBayFeesDE[FinalValueLevel2Cutoff] = 1000.00;

	
	// nsacco 08/02/99
	eBayFeesAU[FeaturedFee] = 49.95;			// unused
	eBayFeesAU[NewFeaturedFee] = 49.95;
	eBayFeesAU[CategoryFeaturedFee] = 9.95;	// unused
	eBayFeesAU[NewCategoryFeaturedFee] = 9.95;	
	eBayFeesAU[BoldFee] = 2.00;
	eBayFeesAU[GiftIconFee] = 1.50;
	eBayFeesAU[GalleryFee] = 0.50;
	eBayFeesAU[GalleryFeaturedFee] = 14.95;
	eBayFeesAU[ItemMoveFee] = 0.00;
	eBayFeesAU[AutoListingFee] = 40.00;
	eBayFeesAU[RealEstateListingFee] = 99.95;
	eBayFeesAU[AutoFinalValueFee] = 40.00;
	eBayFeesAU[ListingLevel1Fee] = 0.50;
	eBayFeesAU[ListingLevel2Fee] = 0.75;
	eBayFeesAU[ListingLevel3Fee] = 1.50;
	eBayFeesAU[ListingLevel4Fee] = 3.00;
	eBayFeesAU[ListingLevel1Range] = 15.00;
	eBayFeesAU[ListingLevel2Range] = 50.00;
	eBayFeesAU[ListingLevel3Range] = 100.00;
	eBayFeesAU[FinalValueLevel1Cutoff] = 50.00;
	eBayFeesAU[FinalValueLevel2Cutoff] = 1000.00;

	// TODO - verify Canada fees
	// nsacco 08/02/99
	eBayFeesCA[FeaturedFee] = 49.95;			// unused
	eBayFeesCA[NewFeaturedFee] = 49.95;
	eBayFeesCA[CategoryFeaturedFee] = 9.95;	// unused
	eBayFeesCA[NewCategoryFeaturedFee] = 9.95;	
	eBayFeesCA[BoldFee] = 2.00;
	eBayFeesCA[GiftIconFee] = 1.50;
	eBayFeesCA[GalleryFee] = 0.50;
	eBayFeesCA[GalleryFeaturedFee] = 14.95;
	eBayFeesCA[ItemMoveFee] = 0.00;
	eBayFeesCA[AutoListingFee] = 40.00;
	eBayFeesCA[RealEstateListingFee] = 99.95;
	eBayFeesCA[AutoFinalValueFee] = 40.00;
	eBayFeesCA[ListingLevel1Fee] = 0.50;
	eBayFeesCA[ListingLevel2Fee] = 0.75;
	eBayFeesCA[ListingLevel3Fee] = 1.50;
	eBayFeesCA[ListingLevel4Fee] = 3.00;
	eBayFeesCA[ListingLevel1Range] = 15.00;
	eBayFeesCA[ListingLevel2Range] = 50.00;
	eBayFeesCA[ListingLevel3Range] = 100.00;
	eBayFeesCA[FinalValueLevel1Cutoff] = 50.00;
	eBayFeesCA[FinalValueLevel2Cutoff] = 1000.00;
}

// Destructor.

clsFees::~clsFees()
{
	// Don't delete the pointer to the item! We don't own it.
	//samuel, 7/22/99
	//delete mpExchangeRates;
}

// Fees relative to another price.

double clsFees::GetBidIncrement(double price)
{
	//samuel, 7/22/99
	double feeAmount;

	// *******************************************
	// * +++ The Pierre Omidiyar Memorial to +++ *
	// * +++ Perl-like C Code Formatting     +++ *
	// *******************************************

	if (price == 0)
	{
		if (mpItem)
			price = mpItem->GetPrice();
		else
			return 0;
	}
	// else price is not 0 and we're okay.

	// TODO INTERNATIONALIZE SOON!
	// NOTE: These fees should be made part of some currency object or something

	//samuel, 7/22/99
	// Changed all the return fees from USD to the corresponding local currencies
	// Though they are all the same, make sure the return amount WILL BE in the 
	// local currency rather than USD (oh well, of course except in Currency_USD).
	// And do not return right away in the switch-case statements but save the 
	// fee in a local variable for further processing.
	switch (mCurrencyId)
	{
	// nsacco 08/02/99
	case Currency_GBP:
		if (price < 1)			{ feeAmount =  0.05; }
		else if (price < 5)		{ feeAmount =  0.20; }
		else if (price < 15)	{ feeAmount =  0.50; }
		else if (price < 60)	{ feeAmount =  1; }
		else if (price < 150)	{ feeAmount =  2; }
		else if (price < 300)	{ feeAmount =  5; }
		else if (price < 600)	{ feeAmount =  10; }
		else if (price < 1500)	{ feeAmount =  20; }
		else if (price < 3000)	{ feeAmount =  50; }
		else					{ feeAmount =  100; }

		break;

	// PH added 04/26/99 >>
	case Currency_DEM:
		if (price < 1)			{ feeAmount =  0.05; }
		else if (price < 5)		{ feeAmount =  0.25; }
		else if (price < 25)	{ feeAmount =  0.50; }
		else if (price < 100)	{ feeAmount =  1; }
		else if (price < 250)	{ feeAmount =  2.50; }
		else if (price < 500)	{ feeAmount =  5; }
		else if (price < 1000)	{ feeAmount =  10; }
		else if (price < 2500)	{ feeAmount =  25; }
		else if (price < 5000)	{ feeAmount =  50; }
		else					{ feeAmount =  100; }

		break;
    
	// nsacco 06/11/99
	case Currency_AUD:
		if (price < 1)			{ feeAmount =  0.05; }
		else if (price < 5)		{ feeAmount =  0.25; }
		else if (price < 25)	{ feeAmount =  0.50; }
		else if (price < 100)	{ feeAmount =  1; }
		else if (price < 250)	{ feeAmount =  2.50; }
		else if (price < 500)	{ feeAmount =  5; }
		else if (price < 1000)	{ feeAmount =  10; }
		else if (price < 2500)	{ feeAmount =  25; }
		else if (price < 5000)	{ feeAmount =  50; }
		else					{ feeAmount =  100; }

		break;

	//samuel, 7/22/99
	case Currency_CAD:
		if (price < 1)			{ feeAmount = 0.05; }
		else if (price < 5)		{ feeAmount = 0.25; }
		else if (price < 25)	{ feeAmount = 0.50; }
		else if (price < 100)	{ feeAmount = 1; }
		else if (price < 250)	{ feeAmount = 2.50; }
		else if (price < 500)	{ feeAmount = 5; }
		else if (price < 1000)	{ feeAmount = 10; }
		else if (price < 2500)	{ feeAmount = 25; }
		else if (price < 5000)	{ feeAmount = 50; }
		else					{ feeAmount = 100; }

		break;
	
	case Currency_USD:
	default:
		if (price < 1)			{ feeAmount =  0.05; }
		else if (price < 5)		{ feeAmount =  0.25; }
		else if (price < 25)	{ feeAmount =  0.50; }
		else if (price < 100)	{ feeAmount =  1; }
		else if (price < 250)	{ feeAmount =  2.50; }
		else if (price < 500)	{ feeAmount =  5; }
		else if (price < 1000)	{ feeAmount =  10; }
		else if (price < 2500)	{ feeAmount =  25; }
		else if (price < 5000)	{ feeAmount =  50; }
		else					{ feeAmount =  100; }

		break;

	}
	
	//samuel, 7/22/99
	if (mCurrencyId != mBillingCurrencyId)
		feeAmount = mpExchangeRates->FromAmountTo(mCurrencyId, feeAmount,
													mBillingCurrencyId, mpItem->GetEndTime());

	return feeAmount;
}

//to use this method mpItem variable in clsFees must be valid
double clsFees::GetInsertionFee(double price)
{
	if (mpItem)
	{
		if (price==0)
		{	
		    //barry ? calling program must be carefull, 
			//not to call with 0 for dutch or reserve auction
			return GetInsertionFee(mpItem->GetPrice(),mpItem->GetCategory(),mpItem->GetStartTime());
		}
		else
		{
			//find fee using category from item
			//calling program must be carefull, not to call with 0 for dutch or reserve auction
			//find fee using Price and Category
			return GetInsertionFee(price,mpItem->GetCategory(), mpItem->GetStartTime());
		}
	}
	else
		return 0;

}

//this method can be used when clsFees does not have a valid pointer to mpItem
double clsFees::GetInsertionFee(double price, int category, time_t start_time)
{
	//samuel, 7/22/99
	double feeAmount;

	//return new fee if sale start AFTER cut off date
	bool startedAfter=false;
	if (start_time==0)
		start_time=time(0);
	if (clsUtilities::CompareTimeToGivenDate(start_time, 4, 24, 99, 0, 0, 0) >= 0)
		startedAfter=true;

	// TODO -  INTERNATIONALIZE!
	// TODO - use variables to store values
	//samuel, 7/22/99
	// all fees will NOT be returned right away in case-switch statement, but need 
	// to be further processed after the loop.  All fee returned in the case-switch
	// will be in native currencies.
	switch (mCurrencyId)
	{
		// This case is currently: 
		// If the price in _pounds_ is within a 
		// certain range, charge the seller in US dollars
		// the amount returned here.
		case Currency_GBP:
			if (CheckForAutomotiveListing(category) && startedAfter)
			{
				//flat US$25.00 fee for cars  
				feeAmount = eBayFeesUK[AutoListingFee];
			}
			else if (CheckForRealEstateListing(category)&& startedAfter)
			{
				//flat US$50.00 fee for real estate
				feeAmount = eBayFeesUK[RealEstateListingFee];
			}
			else if (price < eBayFeesUK[ListingLevel1Range]) { feeAmount = eBayFeesUK[ListingLevel1Fee]; }
			else if	(price < eBayFeesAU[ListingLevel2Range]) { feeAmount = eBayFeesUK[ListingLevel2Fee]; }
			else if	(price < eBayFeesAU[ListingLevel3Range]) { feeAmount = eBayFeesUK[ListingLevel3Fee]; }
			else					{ feeAmount = eBayFeesUK[ListingLevel4Fee]; }

			break;

		// This case is currently: 
		// If the price in _Canadian dollars_ is within a 
		// certain range, charge the seller in US dollars
		// the amount returned here.
		case Currency_CAD:
			if (CheckForAutomotiveListing(category) && startedAfter)
			{
				//flat US$25.00 fee for cars  
				feeAmount = eBayFeesCA[AutoListingFee];
			}
			else if (CheckForRealEstateListing(category)&& startedAfter)
			{
				//flat US$50.00 fee for real estate
				feeAmount = eBayFeesCA[RealEstateListingFee];
			}
			else if (price < eBayFeesCA[ListingLevel1Range]) { feeAmount = eBayFeesCA[ListingLevel1Fee]; }
			else if	(price < eBayFeesCA[ListingLevel2Range]) { feeAmount = eBayFeesCA[ListingLevel2Fee]; }
			else if	(price < eBayFeesCA[ListingLevel3Range]) { feeAmount = eBayFeesCA[ListingLevel3Fee]; }
			else					{ feeAmount = eBayFeesCA[ListingLevel4Fee]; }

			break;

		// PH added 04/26/99 >>
		// This case is currently: 
		// If the price in _mark_ is within a 
		// certain range, charge the seller in US dollars
		// the amount returned here.
		case Currency_DEM:
			if (CheckForAutomotiveListing(category) && startedAfter)
			{
				//flat US$25.00 fee for cars  
				feeAmount = eBayFeesDE[AutoListingFee];
			}
			else if (CheckForRealEstateListing(category)&& startedAfter)
			{
				//flat US$50.00 fee for real estate
				feeAmount = eBayFeesDE[RealEstateListingFee];
			}
			else if (price < eBayFeesDE[ListingLevel1Range])	{ feeAmount = eBayFeesDE[ListingLevel1Fee]; }
			else if	(price < eBayFeesDE[ListingLevel2Range])	{ feeAmount = eBayFeesDE[ListingLevel2Fee]; }
			else if	(price < eBayFeesDE[ListingLevel3Range])	{ feeAmount = eBayFeesDE[ListingLevel3Fee]; }
			else					{ feeAmount = eBayFeesDE[ListingLevel4Fee]; }

			break;
		
		// nsacco 06/11/99
		// This case is currently: 
		// If the price in _Australian Dollars_ is within a 
		// certain range, charge the seller in US dollars
		// the amount returned here.
		case Currency_AUD:
			if (CheckForAutomotiveListing(category) && startedAfter)
			{
				//flat US$25.00 fee for cars  
				feeAmount = eBayFeesAU[AutoListingFee];
			}
			else if (CheckForRealEstateListing(category)&& startedAfter)
			{
				//flat US$50.00 fee for real estate
				feeAmount = eBayFeesAU[RealEstateListingFee];
			}
			else if (price < eBayFeesAU[ListingLevel1Range])	{ feeAmount = eBayFeesAU[ListingLevel1Fee]; }
			else if	(price < eBayFeesAU[ListingLevel2Range])	{ feeAmount = eBayFeesAU[ListingLevel2Fee]; }
			else if	(price < eBayFeesAU[ListingLevel3Range])	{ feeAmount = eBayFeesAU[ListingLevel3Fee]; }
			else					{ feeAmount = eBayFeesAU[ListingLevel4Fee]; }

			break;

		case Currency_USD:
		default:
			if (CheckForAutomotiveListing(category)&& startedAfter)
			{
				//flat US$25.00 fee for cars 
				feeAmount = eBayFeesUS[AutoListingFee];
			}
			else if (CheckForRealEstateListing(category)&& startedAfter)
			{
				//flat $50.00 fee for real estate
				feeAmount = eBayFeesUS[RealEstateListingFee];
			}
			else if (price < eBayFeesUS[ListingLevel1Range])	{ feeAmount = eBayFeesUS[ListingLevel1Fee]; }
			else if	(price < eBayFeesUS[ListingLevel2Range])	{ feeAmount = eBayFeesUS[ListingLevel2Fee]; }
			else if	(price < eBayFeesUS[ListingLevel3Range])	{ feeAmount = eBayFeesUS[ListingLevel3Fee]; }
			else					{ feeAmount = eBayFeesUS[ListingLevel4Fee]; }

			break;
	}

	//samuel, 7/22/99
	if (mCurrencyId != mBillingCurrencyId)
		feeAmount = mpExchangeRates->FromAmountTo(mCurrencyId, feeAmount,
													mBillingCurrencyId, mpItem->GetEndTime());

	return feeAmount;
}
	
// This is the final value fee!
double clsFees::GetListingFee(double price)
{
	if (mpItem)
	{
		if (price==0)
		{	//find fee using Price and Category
		    //barry ? calling program must be carefull, 
			//not to call with 0 for dutch or reserve auction
			return GetListingFee(mpItem->GetPrice(),mpItem->GetCategory(),mpItem->GetStartTime());//find fee using category from item
			
		}
		else
		{
		//calling program must be carefull, not to call with 0 for dutch or reserve auction
			return GetListingFee(price,mpItem->GetCategory(),mpItem->GetStartTime());
		}
	}
	else
		return 0;
}

//called most of the time, besides price now takes in category as well
double clsFees::GetListingFee(double price, int category, time_t start_time)
{
	double  fee;
	if (start_time==0)
		start_time=time(0);
   //return new fee if sale start AFTER cut off date
	bool startedAfter=false;
	if (clsUtilities::CompareTimeToGivenDate(start_time, 4, 24, 99, 0, 0, 0) >= 0)
		startedAfter=true;

	// nsacco 08/02/99 
	// billing with non-USD
	// TODO - make objects!!!
	//samuel, 7/22/99
	// all fees will NOT be returned right away in case-switch statement, but need 
	// to be further processed after the loop.  All fee returned in the case-switch
	// will be in native currencies.
	// Switch using LISTING currency ID, which is in mCurrencyId
	switch (mCurrencyId)
	{
		// This final value fee is calculated in the
		// currency in which the item is listed.
		
		case Currency_GBP:
		if (CheckForAutomotiveListing(category)&& startedAfter)
		{
			//flat US$25.00 fee for cars  
			fee = eBayFeesUK[AutoFinalValueFee];
		}
		
		else if (ChargeFVFforGBP())
		{

			if (price >= eBayFeesUK[FinalValueLevel2Cutoff])	// US$1000+ level
			{
				fee	= ((eBayFeesUK[FinalValueLevel2Cutoff] - eBayFeesUK[FinalValueLevel1Cutoff]) * 0.025) + 
						  (eBayFeesUK[FinalValueLevel1Cutoff] * 0.05) + 
						  ((price - eBayFeesUK[FinalValueLevel2Cutoff]) * 0.0125);
			}
			else if (price >= eBayFeesUK[FinalValueLevel1Cutoff])
			{
				fee		= (eBayFeesUK[FinalValueLevel1Cutoff] * 0.05) + 
						  ((price - eBayFeesUK[FinalValueLevel1Cutoff]) * 0.025);
			}
			else
			{
				fee = price * 0.05;
			}
		}
		else
		{
			fee = 0.0;
		}

		break;

	case Currency_CAD:
		if (CheckForAutomotiveListing(category)&& startedAfter)
		{
			//flat US$25.00 fee for cars  
			fee = eBayFeesCA[AutoFinalValueFee];
		}

		else if (ChargeFVFforCAD())
		{
			if (price >= eBayFeesCA[FinalValueLevel2Cutoff])
			{
				fee	= ((eBayFeesCA[FinalValueLevel2Cutoff] - eBayFeesCA[FinalValueLevel1Cutoff]) * 0.025) + 
						  (eBayFeesCA[FinalValueLevel1Cutoff] * 0.05) + 
						  ((price - eBayFeesCA[FinalValueLevel2Cutoff]) * 0.0125);
			}
			else if (price >= eBayFeesCA[FinalValueLevel1Cutoff])
			{
				fee		= (eBayFeesCA[FinalValueLevel1Cutoff] * 0.05) + 
						  ((price - eBayFeesCA[FinalValueLevel1Cutoff]) * 0.025);
			}
			else
			{
				fee = price * 0.05;
			}
		}
		else
		{
			fee = 0.0;
		}

		break;

	// PH added 04/26/99
	case Currency_DEM:
		if (CheckForAutomotiveListing(category)&& startedAfter)
		{
			//flat US$25.00 fee for cars  
			fee = eBayFeesDE[AutoFinalValueFee];
		}

		else if (ChargeFVFforDEM())
		{

			if (price >= eBayFeesDE[FinalValueLevel2Cutoff])
			{
				fee	= ((eBayFeesDE[FinalValueLevel2Cutoff] - eBayFeesDE[FinalValueLevel1Cutoff]) * 0.025) + 
						  (eBayFeesDE[FinalValueLevel1Cutoff] * 0.05) + 
						  ((price - eBayFeesDE[FinalValueLevel2Cutoff]) * 0.0125);
			}
			else if (price >= eBayFeesDE[FinalValueLevel1Cutoff])
			{
				fee		= (eBayFeesDE[FinalValueLevel1Cutoff] * 0.05) + 
						  ((price - eBayFeesDE[FinalValueLevel1Cutoff]) * 0.025);
			}
			else
			{
				fee = price * 0.05;
			}
		}
		else
		{
			fee = 0.0;
		}

		break;
	
	// nsacco 06/11/99
	case Currency_AUD:
		if (CheckForAutomotiveListing(category)&& startedAfter)
		{
			fee = eBayFeesAU[AutoFinalValueFee];
		}

		else if (ChargeFVFforAUD())
		{

			if (price >= eBayFeesAU[FinalValueLevel2Cutoff])	// US $1000+ level
			{
				fee	= ((eBayFeesAU[FinalValueLevel2Cutoff] - eBayFeesAU[FinalValueLevel1Cutoff]) * 0.025) + 
						  (eBayFeesAU[FinalValueLevel1Cutoff] * 0.05) + 
						  ((price - eBayFeesAU[FinalValueLevel2Cutoff]) * 0.0125);
			}
			else if (price >= eBayFeesAU[FinalValueLevel1Cutoff])
			{
				fee		= (eBayFeesAU[FinalValueLevel1Cutoff] * 0.05) + 
						  ((price - eBayFeesAU[FinalValueLevel1Cutoff]) * 0.025);
			}
			else
			{
				fee = price * 0.05;
			}
		}
		else
		{
			fee = 0.0;
		}

		break;
		
	case Currency_USD:
	default:
		if (CheckForAutomotiveListing(category)&& startedAfter)
		{
			//flat $25.00 fee for cars 
			fee = eBayFeesUS[AutoFinalValueFee];
		}
		else if (price >= eBayFeesUS[FinalValueLevel2Cutoff])	// 1000
		{
			fee	= ((eBayFeesUS[FinalValueLevel2Cutoff] - eBayFeesUS[FinalValueLevel1Cutoff]) * 0.025) + 
				   (eBayFeesUS[FinalValueLevel1Cutoff] * 0.05) + 
				   ((price - eBayFeesUS[FinalValueLevel2Cutoff]) * 0.0125);
		}
		else if (price >= eBayFeesUS[FinalValueLevel1Cutoff])	// 25
		{
			fee		= (eBayFeesUS[FinalValueLevel1Cutoff] * 0.05) + 
					  ((price - eBayFeesUS[FinalValueLevel1Cutoff]) * 0.025);
		}
		else
		{
			fee = price * 0.05;
		}

		break;
	}

	if (mCurrencyId != mBillingCurrencyId)
		fee = mpExchangeRates->FromAmountTo(mCurrencyId, fee,
												mBillingCurrencyId, mpItem->GetEndTime());
	return fee;
}

// Absolute fees.

double clsFees::GetFee(FeeEnum fee, double startPrice)
{
	//samuel, 7/22/99
	double feeAmount;

	// startPrice is currently unused.

	//samuel, 7/22/99
	// instead of returning right after getting the fee,
	// a local variable will be storing the fee for further
	// processing below.
	switch (mCurrencyId)
	{
	case Currency_GBP:
		feeAmount = eBayFeesUK[fee];
		break;

	// PH added 04/26/99 >>
	case Currency_DEM:
		feeAmount = eBayFeesDE[fee];
		break;

	// nsacco 06/11/99
	case Currency_AUD:
		feeAmount = eBayFeesAU[fee];
		break;

	case Currency_CAD:
		feeAmount = eBayFeesCA[fee];
		break;

	case Currency_USD:
	default:
		feeAmount = eBayFeesUS[fee];
	}

	//samuel, 7/22/99
	if (mCurrencyId != mBillingCurrencyId)
		feeAmount = mpExchangeRates->FromAmountTo(mCurrencyId, feeAmount,
													mBillingCurrencyId, mpItem->GetEndTime());

	return feeAmount;
}

//samuel, 7/24/99
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!NOTE!!!!!!!!!!!!!!!!!!!!
// Make sure to keep both old and new pricings for USD and GBP!!!