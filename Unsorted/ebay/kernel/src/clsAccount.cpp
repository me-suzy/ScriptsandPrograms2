/*	$Id: clsAccount.cpp,v 1.16.2.4 1999/07/22 02:00:23 inna Exp $	*/
//
//	File:	clsAccount.cpp
//
//	Class:	clsAccount
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				Represents an item
//
// Modifications:
//				- 02/10/97 michael	- Created
//				- 07/13/97 tini		- add chargeSuperFeaturedFee for super Featured Items
//				- 07-19-98 inna		- added description for 'u' (43) transaction
//				- 06/21/99 sam		- added codes for Collectors Universe
//
#include "eBayKernel.h"

#include "stdio.h"

//
// Set our parent user
//
void clsAccount::SetUser(clsUser *pUser)
{
	mpUser	= pUser;
// Lena
	if ( mTableIndicator > 9 ) // indicator not set
	//decide if this should go to a split of not split table
		gApp->GetDatabase()->SetTableIndicator( mpUser->GetId(), mTableIndicator );

	return;
}

double clsAccount::GetBalance()
{
	return	mBalance;
}

void clsAccount::SetBalance(double balance)
{
	mBalance	= balance;
	return;
}
// Lena
//==========================================================================

int clsAccount::GetTableIndicator()
{
	return mTableIndicator;

} // clsAccount::GetTableIndicator

//==========================================================================

bool clsAccount::UpdateIndicator( int id, int indicator )
{
	return 	gApp->GetDatabase()->UpdateIndicator( id, indicator );

}  // clsAccount::UpdateIndicator

//==========================================================================
//
// GetLastUpdate
//
time_t clsAccount::GetLastUpdate()
{
	return	mLastUpdated;
}

//
// Exists
//
//	Relies on the fact that if the last update
//	time is 0, then the account doesn't exist
//	yet. Probably wrong.
//
bool clsAccount::Exists()
{
	return	!(mLastUpdated == 0);
}

//
// ChargeInsertionFee
//	Gets the insertion fee for the item, creates
//	an account detail record, and logs it
//	If the parameter pOldItemNum is not null, it free relisting for the item
//
void clsAccount::ChargeInsertionFee(clsItem *pItem, char* pOldItemNum /*=NULL*/)
{	double				insertionFee;
	clsAccountDetail	*pDetail;
	double				price;
	// cost basis for calculating listing fees.
	double				costprice;
	char				memo[255];


	time_t				theTime;
	bool				freeListing = false;

	// check for super free relisting.
	//  FreeRelisting just means that if the item sells the 2nd time around
	//  SuperFreeRelisting means that the relisted item is free no matter what, due to system outage
	bool SuperFreeRelisting = false;

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


	// Get the insertion fee
	price	= pItem->GetReservePrice();
	costprice = pItem->GetStartPrice();

	// price = max (price, costprice) for billing purposes
	if (price == 0)
		price = costprice;
	else
	{
		// check if reserve price > start price
		if (costprice > price)
			price = costprice;
	}


	// In theory, there ARE no Dutch reserve auctions, but if
	// on slips in...For non-dutch auctions, however, the 
	// quantity is always 1, so this does nothing.
	price	= price * pItem->GetQuantity();

	insertionFee = pItem->GetInsertionFee(price);

	if (pOldItemNum)
	{
		sprintf(memo, 
			"Relisting item: %s",
			pOldItemNum);

		//inna - e104 did not have this line, e105 does, we DO charge for 
		//relistng, so comment out this insertioFee set to 0 
		if (SuperFreeRelisting) insertionFee = 0.0;
//inna-fix		pDetail	= new clsAccountDetail(time_t(0),
		pDetail	= new clsAccountDetail(
									AccountDetailFeeInsertion,
								   -insertionFee,
//inna-fix							memo, pOldItemNum );
									memo,  pItem->GetId());
//			gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator,
//										  pDetail, 0);
			gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator,
										  pDetail);
	}
	else
	{
// Lena		pDetail	= new clsAccountDetail(AccountDetailFeeInsertion,
//									   -insertionFee,
//									   NULL);
		pDetail	= new clsAccountDetail(AccountDetailFeeInsertion,
									   -insertionFee,
									   NULL, pItem->GetId());
//		gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator,
//										  pDetail, pItem->GetId());
		gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator,
										  pDetail);
	}
// Lena
//	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(),
//										  pDetail);


//	if (!pOldItemNum)
//	{
		AdjustBalance(-insertionFee);
//	}
	// Take care of cross reference
	gApp->GetDatabase()->AddAccountItemXref(pDetail->mTransactionId,
											pItem->GetId());

	if (freeListing && !pOldItemNum)
	{
		delete	pDetail;

		pDetail	= new clsAccountDetail(AccountDetailPromotionalCredit,
									   insertionFee,
									   "eBay\'s 1998 Free Listing Day!",
									   pItem->GetId());
		gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(),mTableIndicator,
											  pDetail);
		gApp->GetDatabase()->AddAccountItemXref(pDetail->mTransactionId,
											pItem->GetId());

		pItem->SetHasInsertionCredit(true);
		AdjustBalance(insertionFee);
	}
	delete	pDetail;
	return;
}

//
// ApplyInsertionFeeCredit for the reason described in pMemo
//	Gets the insertion fee for the item, creates
//	an account detail record, and logs it
//
void clsAccount::ApplyInsertionFeeCredit(clsItem *pItem, const char* pMemo)
{
	double				insertionFee;
	clsAccountDetail	*pDetail;
	double				price;
	// cost basis for calculating listing fees.
	double				costprice;

	// Get the insertion fee
	price	= pItem->GetReservePrice();
	costprice = pItem->GetStartPrice();

	// price = max (price, costprice) for billing purposes
	if (price == 0)
		price = costprice;
	else
	{
		// check if reserve price > start price
		if (costprice > price)
			price = costprice;
	}

	// In theory, there ARE no Dutch reserve auctions, but if
	// on slips in...For non-dutch auctions, however, the 
	// quantity is always 1, so this does nothing.
	price	= price * pItem->GetQuantity();

	//inna, since category and start time are not send in, 
	//it will use start time and from item to determine fees
	insertionFee = pItem->GetInsertionFee(price);

	pDetail	= new clsAccountDetail(AccountDetailCreditInsertionFee,
						   insertionFee,
						   pMemo, pItem->GetId());

	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(),mTableIndicator,
										  pDetail);

	AdjustBalance(insertionFee);

	// Take care of cross reference
	gApp->GetDatabase()->AddAccountItemXref(pDetail->mTransactionId,
											pItem->GetId());
	//also set flag in the item - inna
	pItem->SetHasInsertionCredit(true);

	delete	pDetail;

	return;
}

//
// ChargeBoldFee
//	Gets the bold fee for the item, creates
//	an account detail record, and logs it
//
void clsAccount::ChargeBoldFee(clsItem *pItem)
{
	double				fee;
	clsAccountDetail	*pDetail;

	// Get the insertion fee
	fee	= pItem->GetBoldFee(pItem->GetStartPrice());

	pDetail	= new clsAccountDetail(AccountDetailFeeBold,
								   -fee,
								   NULL, pItem->GetId() );
// Lena
//	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(),
//										  pDetail);
//	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator,
//										  pDetail, pItem->GetId());

	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator,
										  pDetail );
	AdjustBalance(-fee);

	// Take care of cross reference
	gApp->GetDatabase()->AddAccountItemXref(pDetail->mTransactionId, pItem->GetId() );

	delete	pDetail;

	return;
}


//  Charge gift icon fee
//
void clsAccount::ChargeGiftIconFee(clsItem *pItem)
{
	double				fee;
	clsAccountDetail	*pDetail;

	// Get the insertion fee
	fee	= pItem->GetGiftIconFee(pItem->GetGiftIconType());

	pDetail	= new clsAccountDetail(AccountDetailFeeGiftIcon,
								   -fee,
								   NULL, pItem->GetId() );
	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator,
										  pDetail );
	AdjustBalance(-fee);

	// Take care of cross reference
	gApp->GetDatabase()->AddAccountItemXref(pDetail->mTransactionId, pItem->GetId() );

	delete	pDetail;

	return;
}

// credit gift icon fee
void clsAccount::CreditGiftIconFee(clsItem *pItem)
{
	double				fee;
	clsAccountDetail	*pDetail;

	// Get the gift fee
	fee	= pItem->GetGiftIconFee(pItem->GetGiftIconType());

	pDetail	= new clsAccountDetail(AccountDetailCreditGiftIcon,
								   fee,
								   NULL, pItem->GetId() );
	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator,
										  pDetail );
	AdjustBalance(fee);

	// Take care of cross reference
	gApp->GetDatabase()->AddAccountItemXref(pDetail->mTransactionId, pItem->GetId() );

	delete	pDetail;

	return;
}

//  Charge Gallery fee
//
void clsAccount::ChargeGalleryFee(clsItem *pItem)
{
	double				fee;

	// Get the insertion fee
	fee	= pItem->GetGalleryFee();

	clsAccountDetail detail(AccountDetailFeeGallery,
								   -fee,
								   NULL, pItem->GetId() );
	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator,
										  &detail );
	AdjustBalance(-fee);

	// Take care of cross reference
	gApp->GetDatabase()->AddAccountItemXref(detail.mTransactionId, pItem->GetId() );


	return;
}
//credit gallery fee
void clsAccount::CreditGalleryFee(clsItem *pItem)
{

       double                  fee;

       // Get the insertion fee
       fee     = pItem->GetGalleryFee();

       clsAccountDetail detail(AccountDetailCreditGallery,
                                fee,
                                NULL, pItem->GetId() );
       gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator, &detail );
       AdjustBalance(fee);
       // Take care of cross reference
       gApp->GetDatabase()->AddAccountItemXref(detail.mTransactionId, pItem->GetId() );

	   return;
}

//  Charge Gallery Featured fee
//
void clsAccount::ChargeFeaturedGalleryFee(clsItem *pItem)
{
	double				fee;

	// Get the insertion fee
	fee	= pItem->GetFeaturedGalleryFee();

	clsAccountDetail detail(AccountDetailFeeFeaturedGallery,
								   -fee,
								   NULL, pItem->GetId() );
	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator,
										  &detail );
	AdjustBalance(-fee);

	// Take care of cross reference
	gApp->GetDatabase()->AddAccountItemXref(detail.mTransactionId, pItem->GetId() );

	return;
}
//credit featured gallery fee
void clsAccount::CreditFeaturedGalleryFee(clsItem *pItem)
{

       double                  fee;

       // Get the insertion fee
       fee     = pItem->GetFeaturedGalleryFee();

       clsAccountDetail detail(AccountDetailCreditFeaturedGallery,
                                fee,
                                NULL, pItem->GetId() );
       gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator, &detail );
       AdjustBalance(fee);
       // Take care of cross reference
       gApp->GetDatabase()->AddAccountItemXref(detail.mTransactionId, pItem->GetId() );

	   return;
}

//
// ChargeFeaturedFee
//	Gets the insertion fee for the item, creates
//	an account detail record, and logs it
//
void clsAccount::ChargeFeaturedFee(clsItem *pItem)
{
	double				fee;
	clsAccountDetail	*pDetail;

	// Get the insertion fee
	fee	=
	// Lena - new featured price
			pItem->GetFeaturedFee();

	pDetail	= new clsAccountDetail(AccountDetailFeeFeatured,
								   -fee,
								   NULL, pItem->GetId() );
// Lena
//	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(),
//										  pDetail);
//	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator,
//										  pDetail, pItem->GetId() );

	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator,
										  pDetail );
	AdjustBalance(-fee);

	// Take care of cross reference
	gApp->GetDatabase()->AddAccountItemXref(pDetail->mTransactionId,
											pItem->GetId());

	delete	pDetail;

	return;
}

//
// ChargeCategoryFeaturedFee
//	Gets the inserstion fee for the item, creates
//	an account detail record, and logs it
//
void clsAccount::ChargeCategoryFeaturedFee(clsItem *pItem)
{
	double				fee;
	clsAccountDetail	*pDetail;

	// Get the insertion fee
	fee	= pItem->GetCategoryFeaturedFee();


	pDetail	= new clsAccountDetail(AccountDetailFeeCategoryFeatured,
								   -fee,
								   NULL, pItem->GetId() );
// Lena
//	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(),
//										  pDetail);
//	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator,
//										  pDetail, pItem->GetId());

	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator,
										  pDetail);
	AdjustBalance(-fee);


	// Take care of cross reference
	gApp->GetDatabase()->AddAccountItemXref(pDetail->mTransactionId,
											pItem->GetId());

	delete	pDetail;

	return;
}

//
// ChargeListingFee
//	Gets the listing fee for the item, creates
//	an account detail record, and logs it
//
static const char *ListingMemo	=
	"Final price $%8.2f";

//
static const char *FixedListingMemo	=
	"Fixed final value fee of $%8.2f";

static const char *PoundsListingMemo	=
	"Final price of &pound;%10.2f converted at a rate of %10.4f to $%10.2f to determine the final value fee.";

// PH added 04/26/99 I'm doing this because we will get rid of FVF conversion eventually anyway..
static const char *DEMListingMemo	=	
	"Final price of %10.2f DM converted at a rate of %10.4f to $10.2f to determine the final value fee.";

void clsAccount::ChargeListingFee(clsItem *pItem)
{
	double				fee;
	clsAccountDetail	*pDetail;
	char				*pMemo;
	double				rate;
	double				dollarValue;
	
	//before charging anything see if
	//this is real estate after a cut off date - do not charge
    if (pItem->CheckForRealEstateListing() && 
		(clsUtilities::CompareTimeToGivenDate(pItem->GetStartTime(), 4, 24, 99, 0, 0, 0) >= 0))
	{
		//SET FLAG NOT TO ALLOW CREDIT
		pItem->SetHasFVFCredit(true);
		return;
	}

	// Get the Listing fee
	//it will use current price, since there is no price specified.
	//it will use sart date to determiin when to apply new charges
	fee	= pItem->GetListingFee();

	//first see if fixed fee applies
	if (pItem->CheckForAutomotiveListing())
	{
		pMemo	= new char[strlen(FixedListingMemo) +
				   16 +
				   32];

		sprintf(pMemo, FixedListingMemo, fee);
	}
	else if (pItem->GetCurrencyId() == Currency_GBP)
	{
		pMemo	= new char[strlen(PoundsListingMemo) +
						   16 + 16 + 16 +
						   32];

		rate = pItem->GetFVFConversionRate();
		dollarValue = pItem->GetPrice() * rate;
		sprintf(pMemo, PoundsListingMemo, pItem->GetPrice(), rate, dollarValue);
	}
	// PH added 04/26/99 >> 
	// we'll get in trouble with stuff like that.. not all languages have the
	// same sentence structure, we might end up having a different order of
	// variables..
	else
	{
		if (pItem->GetCurrencyId() == Currency_DEM)
		{
			pMemo = new char[strlen(DEMListingMemo) +
							 16+16+16+
							 32];  // PH what are the 32 for???

			rate = pItem->GetFVFConversionRate();
			dollarValue = pItem->GetPrice() * rate;
			sprintf(pMemo, DEMListingMemo, pItem->GetPrice(), rate, dollarValue);
		}
		// <<
		else
		{
			pMemo	= new char[strlen(ListingMemo) +
					   16 +
					   32];

			sprintf(pMemo, ListingMemo, pItem->GetPrice());
		}
	}

	pDetail	= new clsAccountDetail(AccountDetailFeeFinalValue,
								   -fee,
								   pMemo, pItem->GetId() );

	pDetail->mTime	= pItem->GetEndTime() + 1;
// Lena
//	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(),
//										  pDetail);
	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator,
										  pDetail);

	AdjustBalance(-fee);

	// Take care of cross reference
	gApp->GetDatabase()->AddAccountItemXref(pDetail->mTransactionId,
											pItem->GetId());

	delete	pDetail;
	delete	[] pMemo;

	return;
}

//
// ChargeListingFee (Dutch Auctions)
//	Gets the listing fee for the item, creates
//	an account detail record, and logs it.
//
// ** NOTE **
//	Should this be a method all on it's own?
// ** NOTE **
//
static const char *DutchListingMemo	=
	"Final price $%8.2f, %d of %d items @ %8.2f";

static const char *PoundsDutchListingMemo =
	"Final price &pound;%10.2f, %d of %d items @ %8.2f, converted at a rate of %10.4f to $%10.2f to determine the final value fee.";

// PH added 04/26/99
static const char *DEMDutchListingMemo =
	"Final price %10.2f DM, %d of %d items @ %8.2f, converted at a rate of %10.4f to determine the final value fee.";

void clsAccount::ChargeListingFee(clsItem *pItem,
								  int qtySold)
{
	double				fee;
	clsAccountDetail	*pDetail;
	char				*pMemo;
	double				rate;
	double				dollarValue;
	
	//dutch should never have this but just in case:
	if (pItem->CheckForRealEstateListing() && 
		(clsUtilities::CompareTimeToGivenDate(pItem->GetStartTime(), 4, 24, 99, 0, 0, 0) >= 0))
	{
		//SET FLAG NOT TO ALLOW CREDIT
		pItem->SetHasFVFCredit(true);
		return;
	}

	// Get the Listing fee
	//it will use start date of item to determine if need new chrages
	fee	= pItem->GetListingFee(pItem->GetPrice() * qtySold);

	if (pItem->GetCurrencyId() == Currency_GBP)
	{
		pMemo	= new char[strlen(PoundsDutchListingMemo) +
						   16   +
							5	+
							5	+
							16	+
							16  + 
							16  +
						   32];

		rate = pItem->GetFVFConversionRate();
		dollarValue = pItem->GetPrice() * qtySold * rate;

		sprintf(pMemo, PoundsDutchListingMemo, pItem->GetPrice() * qtySold, 
								qtySold,
								pItem->GetQuantity(),
								pItem->GetPrice(),
								rate, 
								dollarValue);
	}
	// PH added 04/26/99 >>
	else
	{
		if (pItem->GetCurrencyId() == Currency_DEM)
		{
			pMemo	= new char[strlen(DEMDutchListingMemo) +
							   16   +
								5	+
								5	+
								16	+
								16  + 
								16  +
							   32];

			rate = pItem->GetFVFConversionRate();
			dollarValue = pItem->GetPrice() * qtySold * rate;

			sprintf(pMemo, DEMDutchListingMemo, pItem->GetPrice() * qtySold, 
									qtySold,
									pItem->GetQuantity(),
									pItem->GetPrice(),
									rate, 
									dollarValue);
		}
		// <<
		else
		{
			pMemo	= new char[strlen(DutchListingMemo) +
							   16 +
								5	+
								5	+
								16	+
							   32];

			sprintf(pMemo, DutchListingMemo, pItem->GetPrice() * qtySold, 
									qtySold,
									pItem->GetQuantity(),
									pItem->GetPrice());
		}
	}

	pDetail	= new clsAccountDetail(AccountDetailFeeFinalValue,
								   -fee,
								   pMemo, pItem->GetId() );

	pDetail->mTime	= pItem->GetEndTime() + 1;
// Lena
//	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(),
//										  pDetail);
//	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator,
//										  pDetail, pItem->GetId());
	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator,
										  pDetail);

	AdjustBalance(-fee);

	// Take care of cross reference
	gApp->GetDatabase()->AddAccountItemXref(pDetail->mTransactionId,
											pItem->GetId());

	delete	pDetail;
	delete	[] pMemo;

	return;
}


//
// ChargePartialSaleFee
//	Gets the listing fee for the item, creates
//	an account detail record, and logs it
//

void clsAccount::ChargePartialSaleFee(clsItem *pItem,
									  double fee)
{
	clsAccountDetail	*pDetail;

	pDetail	= new clsAccountDetail(AccountDetailFeePartialSale,
								   -fee,
								   NULL, pItem->GetId() );
// Lena
//	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(),
//										  pDetail);
//	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator,
//										  pDetail, pItem->GetId());
	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator,
										  pDetail);

	AdjustBalance(-fee);

	// Take care of cross reference
	gApp->GetDatabase()->AddAccountItemXref(pDetail->mTransactionId,
											pItem->GetId());

	delete	pDetail;

	return;
}

void clsAccount::ChargePartialSaleFee(char *pItemId,
									  double fee)
{
	clsAccountDetail	*pDetail;
	char				itemId[13];

	pDetail	= new clsAccountDetail(time_t(0), AccountDetailFeePartialSale,
								   -fee,
								   NULL, pItemId );
// Lena
//	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(),
//										  pDetail);
//	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator,
//										  pDetail, 0 );
	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator,
										  pDetail );
	AdjustBalance(-fee);

	// Take care of cross reference
	strcpy(itemId, pItemId);
	gApp->GetDatabase()->AddAccountAWItemXref(1,
											  &pDetail->mTransactionId,
											  itemId);

	delete	pDetail;

	return;
}



//
// ApplyMemoCredit
//
void clsAccount::ApplyCourtesyCredit(double creditAmount,
							    	 char *pMemo)
{
	clsAccountDetail	*pDetail;

	pDetail	= new clsAccountDetail(AccountDetailCreditCourtesy,
								   creditAmount,
								   pMemo );
// Lena
//	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(),
//										  pDetail);
//	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator,
//										  pDetail, 0);

	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator,
										  pDetail);
	AdjustBalance(creditAmount);

	delete	pDetail;

	return;
}


//
// ApplyNoSaleCredit
//
//	We do NOT recompute the credit amount here because we 
//	don't know if the fees have changed or what since
//	the item was origionally billed.
//	
void clsAccount::ApplyNoSaleCredit(clsItem *pItem,
								   double creditAmount)
{
	clsAccountDetail	*pDetail;

	pDetail	= new clsAccountDetail(AccountDetailCreditNoSale,
								   creditAmount,
								   NULL, pItem->GetId() );
// Lena
//	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(),
//										  pDetail);
//	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator,
//										  pDetail, pItem->GetId());
	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator,
										  pDetail);

	AdjustBalance(creditAmount);

	// Take care of cross reference
	gApp->GetDatabase()->AddAccountItemXref(pDetail->mTransactionId,
											pItem->GetId());

	delete	pDetail;

	return;
}

void clsAccount::ApplyNoSaleCredit(char *pItemId,
								   double creditAmount)
{
	clsAccountDetail	*pDetail;
	char				itemId[13];

	pDetail	= new clsAccountDetail(AccountDetailCreditNoSale,
								   creditAmount,
								   NULL);
// Lena 
//	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(),
//										  pDetail);
//	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator,
//										  pDetail, 0 );
	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator,
										  pDetail );
	AdjustBalance(creditAmount);

	// Take care of cross reference
	strcpy(itemId, pItemId);
	gApp->GetDatabase()->AddAccountAWItemXref(1,
											  &pDetail->mTransactionId,
											  pItemId);

	delete	pDetail;

	return;
}


//
// ApplyPartialSaleCredit
//
//	We do NOT recompute the credit amount here because we 
//	don't know if the fees have changed or what since
//	the item was originally billed.
//	
void clsAccount::ApplyPartialSaleCredit(clsItem *pItem,
								 	    double creditAmount)
{
	clsAccountDetail	*pDetail;

	pDetail	= new clsAccountDetail(AccountDetailCreditPartialSale,
								   creditAmount,
								   NULL, pItem->GetId());
// Lena
//	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(),
//										  pDetail);
//	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator,
//										  pDetail, pItem->GetId() );
	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator,
										  pDetail );

//	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(),
//										  pDetail);

	AdjustBalance(creditAmount);

	// Take care of cross reference
	gApp->GetDatabase()->AddAccountItemXref(pDetail->mTransactionId,
											pItem->GetId());

	delete	pDetail;

	return;
}

void clsAccount::ApplyPartialSaleCredit(char *pItemId,
									double creditAmount)
{
	clsAccountDetail	*pDetail;
	char				itemId[13];


	pDetail	= new clsAccountDetail(time_t(0), AccountDetailCreditPartialSale,
								   creditAmount,
								   NULL, pItemId );
// Lena
//	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(),
//										  pDetail);
//	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator,
//										  pDetail, 0 );
	gApp->GetDatabase()->AddAccountDetail(mpUser->GetId(), mTableIndicator,
										  pDetail );
	AdjustBalance(creditAmount);

	// Take care of cross reference
	strcpy(itemId, pItemId);
	gApp->GetDatabase()->AddAccountAWItemXref(1, 
											  &pDetail->mTransactionId,
											  itemId);

	delete	pDetail;

	return;
}


//
// AddRawAccountDetail
//	Just slam in a prepared detail record
//
//  Lena void clsAccount::AddRawAccountDetail(clsAccountDetail *pDetail,
//									 int migrationBatchId)
void clsAccount::AddRawAccountDetail(clsAccountDetail *pDetail,
									 int migrationBatchId, int /* batchId */)
{
// Lena
//	gApp->GetDatabase()->AddRawAccountDetail(mpUser->GetId(),
//											 pDetail,
//											 migrationBatchId);
//	gApp->GetDatabase()->AddRawAccountDetail(mpUser->GetId(), mTableIndicator,
//											 pDetail,
//											 migrationBatchId, batchId);
	gApp->GetDatabase()->AddRawAccountDetail(mpUser->GetId(), mTableIndicator,
											 pDetail,
											 migrationBatchId );
	return;
}


//==============================================================================

void clsAccount::AddInterimBalance( int id, time_t theTime, double amount )
{
	gApp->GetDatabase()->AddInterimBalance( id, theTime, amount );
	return;

}  // clsAccount::AddInterimBalance

//============================================================================

bool clsAccount::GetInterimBalance( int id, time_t &theTime, double &amount, bool first )
{
	return gApp->GetDatabase()->GetInterimBalance( id, theTime, amount, first );

}  // clsAccount::GetInterimBalance

//============================================================================

bool clsAccount::GetInterimBalanceForMonth( int id, time_t theTime )
{
	return gApp->GetDatabase()->GetInterimBalanceForMonth( id, theTime );

}  // clsAccount::GetInterimBalance

//============================================================================

void clsAccount::GetInterimBalances(InterimBalanceList *plBalances)
{
	gApp->GetDatabase()->GetInterimBalances( mpUser->GetId(), plBalances);

}  // clsAccount::GetInterimBalances


//============================================================================

void clsAccount::GetUsersWithAccountsNotInvoiced( vector<unsigned int> *pvIds,
                                                        time_t tInvoiceDate,
														int idStart, int idEnd )
{
	gApp->GetDatabase()->GetUsersWithAccountsNotInvoiced( pvIds, tInvoiceDate, idStart, idEnd );
	return;

}  // clsAccount::GetUsersWithAccountsNotInvoiced

//=============================================================================
//
// UpdateCCDetails
// 	update customers credit card details into db.
void clsAccount::UpdateCCDetails(int id, int CCId, time_t ccExpDate, time_t ccUpdateTime)
{
 
	gApp->GetDatabase()->UpdateCCDetails(id, CCId, ccExpDate, ccUpdateTime);
}


//
// AdjustBalance
//
void clsAccount::AdjustBalance(double delta)
{
	gApp->GetDatabase()->AdjustAccountBalance(mpUser->GetId(),
											  delta);

	mBalance	+=	delta;

	//
	// Adjust Past due, too, if appropriate
	//
	if (delta > 0 &&
		(mPastDue30Days < 0 || mPastDue60Days < 0 ||
		 mPastDue90Days < 0 || mPastDue120Days < 0 ||
		 mPastDueMoreThan120Days < 0))
	{
		mPastDue30Days			+=	delta;
		mPastDue60Days			+=	delta;
		mPastDue90Days			+=	delta;
		mPastDue120Days			+=	delta;
		mPastDueMoreThan120Days	+=	delta;

		if (mPastDue30Days > 0)
			mPastDue30Days	= 0;

		if (mPastDue60Days > 0)
			mPastDue60Days	= 0;

		if (mPastDue90Days > 0)
			mPastDue90Days	= 0;

		if (mPastDue120Days > 0)
			mPastDue120Days	= 0;

		if (mPastDueMoreThan120Days > 0)
			mPastDueMoreThan120Days	= 0;

		// Do it to ourselves
		SetPastDue(mPastDueBase,
				   mPastDue30Days,
				   mPastDue60Days,
				   mPastDue90Days,
				   mPastDue120Days,
				   mPastDueMoreThan120Days);
	}

	return;
}


//
// GetAccountDetail
//
void clsAccount::GetAccountDetail(AccountDetailVector *pvDetail)
{
	// Lena
//	gApp->GetDatabase()->GetAccountDetail(mpUser->GetId(),
//  pvDetail);
	gApp->GetDatabase()->GetAccountDetail(mpUser->GetId(), mTableIndicator,
										  pvDetail);
	return;
}

//=============================================================================

void clsAccount::GetAccountDetailUntil(AccountDetailVector *pvDetail, time_t until)
{
// Lena
//	gApp->GetDatabase()->GetAccountDetailUntil(mpUser->GetId(),
//										  pvDetail, until);
	gApp->GetDatabase()->GetAccountDetailUntil(mpUser->GetId(), mTableIndicator,
										  pvDetail, until );
	return;
}

//=============================================================================

void clsAccount::GetAccountDetail( AccountDetailVector *pvDetail, 
									   time_t since, time_t until )
{
// Lena
//	gApp->GetDatabase()->GetAccountDetail(mpUser->GetId(),
//										  pvDetail, since, until );
	gApp->GetDatabase()->GetAccountDetail(mpUser->GetId(), mTableIndicator,
										  pvDetail, since, until );
	return;

}  //  clsAccount::GetAccountDetail

//==============================================================================

void clsAccount::GetAccountDetail( AccountDetailVector *pvDetail, time_t since )
{
// Lena
//	gApp->GetDatabase()->GetAccountDetail(mpUser->GetId(),
//										  pvDetail, since );
	gApp->GetDatabase()->GetAccountDetail(mpUser->GetId(), mTableIndicator,
										  pvDetail, since );
	return;

}  //  clsAccount::GetAccountDetail

//==============================================================================

void clsAccount::CombineInterimBalanceForUsers( int oldId, int newId )
{
	gApp->GetDatabase()->CombineInterimBalanceForUsers( oldId, newId );
	return;

}  // clsAccount::CombineInterimBalanceForUsers

//=============================================================================
//
// GetAccountDetail (LIST Version)
//
void clsAccount::GetAccountDetail(AccountDetailList *plDetail)
{
// Lena
//	gApp->GetDatabase()->GetAccountDetail(mpUser->GetId(),
//										  plDetail);
	gApp->GetDatabase()->GetAccountDetail(mpUser->GetId(), mTableIndicator,
										  plDetail);
	return;
}

//
// GetAccountDetailForItem
//
void clsAccount::GetAccountDetailForItem(int itemId,
										 AccountDetailVector *pvDetail)
{
// Lena
//	gApp->GetDatabase()->GetAccountDetailForItem(mpUser->GetId(),
//												 itemId,
//												 pvDetail);
	gApp->GetDatabase()->GetAccountDetailForItem(mpUser->GetId(), mTableIndicator,
												 itemId,
												 pvDetail);
	return;
}


void clsAccount::GetAccountDetailForItem(char *pItemId,
										 AccountDetailVector *pvDetail)
{
// Lena - check what's going on to update later!!!!
	gApp->GetDatabase()->GetAccountDetailForAWItem(mpUser->GetId(),
												   pItemId,
												   pvDetail);
	return;
}

//
// GetAccountDetailByType
//
void clsAccount::GetAccountDetailByType(AccountDetailTypeEnum type,
										 AccountDetailVector *pvDetail)
{
// Lena
//	gApp->GetDatabase()->GetAccountDetailByType(mpUser->GetId(),
//												type,
//												 pvDetail);
	gApp->GetDatabase()->GetAccountDetailByType(mpUser->GetId(), mTableIndicator,
												type,
												 pvDetail);
	return;
}

//
// GetDetailDescriptor
//
//	This is HERE so that we don't get a copy for each account 
//	detail record. I think there's another way to do this using
//	statics, but I don't know what
//
//
const char *clsAccount::GetAccountDetailDescriptor(AccountDetailTypeEnum it)
{
	switch (it)
	{
		case	AccountDetailUnknown:
			return	"";
		case	AccountDetailFeeInsertion:
			return	"Insertion fee";
		case	AccountDetailFeeBold:
			return	"Bold listing fee";
		case	AccountDetailFeeFeatured:
			return	"Super Featured listing fee";
		case	AccountDetailFeeCategoryFeatured:
			return	"Category Featured listing fee";
		case	AccountDetailFeeFinalValue:
			return	"Final Auction Value fee";
		case	AccountDetailPaymentCheck:
			return	"Payment - Check -- Thank You!";
		case	AccountDetailPaymentCC:
			return	"Payment - Credit Card -- Thank You!";
		case	AccountDetailCreditCourtesy:
			return	"Courtesy Credit";
		case	AccountDetailCreditNoSale:
			return	"No-Sale Credit";
		case	AccountDetailCreditPartialSale:
			return	"Partial Sale Credit";
		case	AccountDetailRefundCC:
			return	"Credit Card Charge Reversal";
		case	AccountDetailRefundCheck:
			return	"Check Refund";
		case	AccountDetailFinanceCharge:
			return	"Finance Charge";
		case	AccountDetailAWDebit:
			return	"AuctionWeb Debit";
		case	AccountDetailAWCredit:
			return	"AuctionWeb Credit";
		case	AccountDetailAWMemo:
			return	"AuctionWeb Memo";
		case	AccountDetailCreditDuplicateListing:
			return	"Duplicate listing credit";
		case	AccountDetailFeePartialSale:
			return	"Partial Sale Final Value Fee";
		case	AccountDetailPaymentCCOnce:
			return	"Credit card payment -- Thank you!";
		case	AccountDetailFeeReturnedCheck:
			return	"Returned check fee";
		case	AccountDetailFeeRedepositCheck:
			return	"Redeposit check fee";
		case	AccountDetailPaymentCash:
			return	"Cash payment -- thank you!";
		case	AccountDetailCreditInsertion:
			return	"Courtesy Credit -- Insertion fee";
		case	AccountDetailCreditBold:
			return	"Courtesy Credit -- Bold listing fee";
		case	AccountDetailCreditFeatured:
			return	"Courtesy Credit -- Featured auction fee";
		case	AccountDetailCreditCategoryFeatured:
			return	"Courtesy Credit -- Category Featured auction fee";
		case	AccountDetailCreditFinalValue:
			return	"Courtesy Credit -- Final Value fee";
		case	AccountDetailFeeNSFCheck:
			return	"Check returned - NSF fee";
		case	AccountDetailFeeReturnCheckClose:
			return	"Check returned - Account Closed Fee";
		case	AccountDetailMemo:
			return	"Memo Entry";
		case	AccountDetailPaymentMoneyOrder:
			return	"Payment - Money Order -- Thank You!";
		case	AccountDetailCreditCardOnFile:
			return	"Credit Card on file -- Thank You!";
		case	AccountDetailCreditCardNotOnFile:
			return	"Credit Card no longer on file";
		case	AccountDetailInvoiced:
			return	"Account Invoice";
		case	AccountDetailInvoicedCreditCard:
			return	"Account Invoice - Will be charged to credit card";
		case	AccountDetailCreditTransferFrom:
			return	"Balance transfer Credit";
		case	AccountDetailDebitTransferTo:
			return	"Balance transfer Debit";
		case	AccountDetailInvoiceCreditBalance:
			return	"Account Invoice - Credit balance do not pay";
		case	AccountDetaileBayDebit:
			return	"eBay Debit";
		case	AccountDetaileBayCredit:
			return	"eBay Credit";
		case	AccountDetailPromotionalCredit:
			return	"Promotional Credit";
			//inna
		case	AccountDetailCCNotOnFilePerCustReq:
			return	"Credit Card no longer on file Per Customer's Request";
		case	AccountDetailCreditInsertionFee:
			return	"Insertion fee credit";
		case	AccountDetailCCPaymentRejected:
			return	"Credit Card Declined";
		case AccountDetailFeeGiftIcon:
			return  "Gift icon fee";
		case AccountDetailCreditGiftIcon:
			return "Courtesy Credit -- Gift Icon fee";
		case AccountDetailFeeGallery:
			return  "Gallery fee";
		case AccountDetailFeeFeaturedGallery:
			return  "Gallery Featured fee";
		case AccountDetailCreditGallery:
			return	"Credit Gallery fee";
		case AccountDetailCreditFeaturedGallery:
			return	"Credit Featured Gallery fee";
		case AccountDetailItemMoveFee:
			return	"Item listed in inappropriate category -- moved by Support";
		//inna-outtage 6/99
		case AccountDetailOutageCredit:
			return	"Outage Credit";
		case AccountDetailCreditPSA:							// Sam
			return "Professional Sports Authenticator";
		case AccountDetailCreditPCGS:							// Sam
			return "Professional Coin Grading Service";
		default:
			return	" ";
	}

}

const int clsAccount::GetAccountDetailDescriptorMaxLength()
{
	return	32;
}


//
// DeleteAccountDetailByTime
//
void clsAccount::DeleteAccountDetailByTime(time_t theTime)
{
// Lena
//	gApp->GetDatabase()->DeleteAccountDetailByTime(mpUser->GetId(),
//												   theTime);
	gApp->GetDatabase()->DeleteAccountDetailByTime(mpUser->GetId(), 
													mTableIndicator,
												   theTime);
	return;
}


//
// DeleteAccountBalance
//
void clsAccount::DeleteAccountBalance()
{
	gApp->GetDatabase()->DeleteAccountBalance(mpUser->GetId());
	return;
}

//
// Rebalance
//
void clsAccount::RebalanceAccount()
{
// Lena
//	gApp->GetDatabase()->RebalanceAccount(mpUser->GetId());
	gApp->GetDatabase()->RebalanceAccount(mpUser->GetId(), mTableIndicator );
	return;
}

//
// GetAWAccountId
//
int clsAccount::GetAWAccountId()
{
	int		awId;

	gApp->GetDatabase()->GetAWAccountCrossReference(mpUser->GetId(),
													&awId);

	return awId;
}

//
// SetPastDue
//
void clsAccount::SetPastDue(time_t pastDueBase,
						double pastDue30Days,
						double pastDue60Days,
						double pastDue90Days,
						double pastDue120Days,
						double pastDueMoreThan120Days)
{
	mPastDueBase			= pastDueBase;
	mPastDue30Days			= pastDue30Days;
	mPastDue60Days			= pastDue60Days;
	mPastDue90Days			= pastDue90Days;
	mPastDue120Days			= pastDue120Days;
	mPastDueMoreThan120Days	= pastDueMoreThan120Days;

	gApp->GetDatabase()->UpdateAccountPastDue(mpUser->GetId(),
											  pastDueBase,
											  pastDue30Days,
											  pastDue60Days,
											  pastDue90Days,
											  pastDue120Days,
											  pastDueMoreThan120Days);

	return;
}

time_t clsAccount::GetPastDueBase()
{
	return	mPastDueBase;
}

float clsAccount::GetPastDue30Days()
{
	return mPastDue30Days;
}

float clsAccount::GetPastDue60Days()
{
	return mPastDue60Days;
}

float clsAccount::GetPastDue90Days()
{
	return mPastDue90Days;
}

float clsAccount::GetPastDue120Days()
{
	return mPastDue120Days;
}

float clsAccount::GetPastDueOver120Days()
{
	return mPastDueMoreThan120Days;
}



//
// GetCCIdForUser
//
short clsAccount::GetCCIdForUser()
{

	return mCCId;

}

//
// SetCCIdForUser
//
void clsAccount::SetCCIdForUser(short CCId)
{
	mCCId = CCId;
	return;
}

//
// GetCCExpiryDate
//
time_t clsAccount::GetCCExpiryDate()
{
	return mCCExpiryDate;
}

//
// SetCCExpiryDate
//
void clsAccount::SetCCExpiryDate(time_t CCExpiryDate)
{
	mCCExpiryDate = CCExpiryDate;
	return;
}


//
// GetLastCCUpdate
//
time_t clsAccount::GetLastCCUpdate()
{
	return mCCLastUpdated;
}

//
// SetLastCCUpdate
//
void clsAccount::SetLastCCUpdate(time_t ccUpdateTime)
{
	mCCLastUpdated = ccUpdateTime;
	return;
}

//
// GetLastCCNoticeSent
//
time_t clsAccount::GetLastCCNoticeSent()
{
	return mCCLastCCNoticeSent;
}

//
// SetLastCCNoticeSent
//
void clsAccount::SetLastCCNoticeSent(time_t CCLastNoticeSent)
{
	mCCLastCCNoticeSent = CCLastNoticeSent;
	return;
}

void clsAccount::GetBadAccountDetail(AccountDetailVector *pvDetail)
{
	// Lena
//	gApp->GetDatabase()->GetAccountDetail(mpUser->GetId(),
//  pvDetail);
	gApp->GetDatabase()->GetBadAccountDetail(mpUser->GetId(), pvDetail);
	return;
}
// inna-start methods needed for new aging process
//
// GetPaymentsSince 
//
void clsAccount::GetPaymentsSince(time_t tSinceDate, double &amount)
{
	gApp->GetDatabase()->GetPaymentsSince(mpUser->GetId(), tSinceDate, amount,
												mTableIndicator);
}
// GetPaymentsByDate
//
void clsAccount::GetPaymentsByDate(time_t tSinceDate, time_t tEndDate, double &amount)
{
	gApp->GetDatabase()->GetPaymentsByDate(mpUser->GetId(), tSinceDate, tEndDate, amount,
												mTableIndicator);
}
//caluculate correct Past Due
double clsAccount::CalculateXPastDue(int period, int id, 
									 InterimBalanceList *plBalances, 
									 time_t tStartDate, time_t tEndDate)
{
	InterimBalanceList::iterator	i;

	int nodeNumber;
	int count;

	double		balance;

	//get a node number form the period requested
	switch (period)
	{
		case	30:
			nodeNumber = 1;
			break;
		case	60:
			nodeNumber = 2;
			break;
		case	90:
			nodeNumber = 3;
			break;
		case	120:
			nodeNumber = 4;
			break;
		case	121:
			nodeNumber = 5;
			break;
		case	150:
			nodeNumber = 6;
			break;
		default:
			return 0;//no need to calculate if not real period

	}

	//see if the list passed to us is good, is it populated?
	if (plBalances->empty())
		gApp->GetDatabase()->GetInvoices(tStartDate,tEndDate,plBalances,
										id, true);
	else
	{
		i = plBalances->begin(); 
		//is user correct? is first date ok?
		if ((*i)->mId != id && (*i)->mWhen != tEndDate)
			gApp->GetDatabase()->GetInvoices(tStartDate,tEndDate, plBalances,
											id, true);
	}
	//at this point we have a good list or empty list

	//can we stop here? is node there?  
	if ( plBalances->size() == 0 || plBalances->size() < nodeNumber)
		return 0;

	//pick a node by period - traverse list until found
	count=0;
	for (i = plBalances->begin();
			i != plBalances->end();
			i++)
	{ 
		count++;
		if (count == nodeNumber)
			break;

	}

	//can we stop here? is amount>0?
	if ((*i)->mBalance >= 0)
		return 0;

	//ok,user has interim balance, lets add payments since
	double amount=0;
	GetPaymentsSince((*i)->mWhen, amount);
	balance = (*i)->mBalance + amount;

	//let see what we got calculated and return
	if (balance < 0) 
		return balance;
	else
		return 0;
}
void clsAccount::Rebalance()
{
	time_t		lastInvoiceTimeForUser;
	double		currentBalance;
	float		newBalance;


	struct tm	*pStartDateAsTm;
	int			fromMonth;
	time_t		tStartDate;
	struct tm	*pEndDateAsTm;
	int			toMonth;
	time_t		tEndDate;

	AccountDetailVector				vDetail;
	AccountDetailVector::iterator	j;

	double	PastDue30,PastDue60,PastDue90,PastDue120,PastDue121;

	InterimBalanceList				lInterimBalances;
	InterimBalanceList::iterator	i;

	//start with rebalancing current balance
	if (GetInterimBalance(mpUser->GetId(), 
			lastInvoiceTimeForUser, currentBalance, 0))
	{
		//we need to get transactions since invoice time
		GetAccountDetail(&vDetail, lastInvoiceTimeForUser + 1);
		newBalance=currentBalance;

		for (j = vDetail.begin();
			 j != vDetail.end();
			 j++)
			newBalance += (*j)->mAmount;

		SetAccountBalance(newBalance);

		for (j= vDetail.begin(); 
			 j != vDetail.end(); 
			 j++)	
		{
			delete (*j);
		}
		//we can not trust just currnet balance being ok
		//we must test first 30 days

		//if past due base for some reson is currupted, skip 
		if (mPastDueBase)
		{
			//dates for our past dues based on the PastDueBase 
			pStartDateAsTm	= localtime(&mPastDueBase);
			fromMonth = pStartDateAsTm->tm_mon - 5;
			if (fromMonth < 0 )
			{
				fromMonth = (pStartDateAsTm->tm_mon - 5) + 12;
				pStartDateAsTm->tm_year --; 
			}
			//update tm structre 
			pStartDateAsTm->tm_mon = fromMonth;
			//find last invoice for this date
			gApp->GetDatabase()->InvoiceTime((*pStartDateAsTm),fromMonth);
			//refotmat tm into time_t????
			tStartDate = mktime(pStartDateAsTm);

			pEndDateAsTm	= localtime(&mPastDueBase);
			toMonth = pEndDateAsTm->tm_mon - 1;
			if (toMonth < 0 )
			{
				toMonth = (pEndDateAsTm->tm_mon - 1) + 12;
				pEndDateAsTm->tm_year --; 
			}
			//update tm structre 
			pEndDateAsTm->tm_mon = toMonth;
			//find last invoice for this date
			gApp->GetDatabase()->InvoiceTime((*pEndDateAsTm),toMonth);
			//refotmat tm into time_t????
			tEndDate = mktime(pEndDateAsTm);

			PastDue30 = CalculateXPastDue(30, mpUser->GetId(), 
									&lInterimBalances, tStartDate, tEndDate);

			if (PastDue30 != mPastDue30Days)
			{
				PastDue60 = CalculateXPastDue(60, mpUser->GetId(), 
										&lInterimBalances, tStartDate, tEndDate);
				PastDue90 = CalculateXPastDue(90, mpUser->GetId(), 
										&lInterimBalances, tStartDate, tEndDate);
				PastDue120 = CalculateXPastDue(120, mpUser->GetId(), 
										&lInterimBalances, tStartDate, tEndDate);
				PastDue121 = CalculateXPastDue(121, mpUser->GetId(), 
										&lInterimBalances, tStartDate, tEndDate);
				SetPastDue(mPastDueBase, PastDue30, PastDue60, PastDue90, PastDue120, PastDue121);
			}	
			for (i = lInterimBalances.begin(); 
			 i != lInterimBalances.end(); 
			 i++)	
			{
				delete (*i);
			}
		}
	}
	else
	{
		//no interim balances: use sum(amount) from ebay_accounts
		RebalanceAccount();
		//if there is no interim balance there are no past due 30 +!
	}
	return;

} 

void clsAccount::SetAccountBalance(float balance)
{
	gApp->GetDatabase()->SetAccountBalance(balance, mpUser->GetId());
	return;
}

//inna-end 
