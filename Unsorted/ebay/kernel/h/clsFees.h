/*	$Id: clsFees.h,v 1.4.110.2 1999/08/03 23:41:24 nsacco Exp $	*/
//
//	File:	clsFees.h
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
//				- 06/11/99 nsacco	- Australian dollars added
//
#ifndef CLSFEES_INCLUDED

#include "eBayTypes.h"

class clsItem;
//samuel, 7/22/99
class clsExchangeRates;

class clsFees
{
public:
	clsFees(int currency);
	clsFees(clsItem *pItem);
	~clsFees();

	// Prices that are relative, based on an input price.
	double GetListingFee(double price = 0);
	double GetListingFee(double price, int category, time_t start_time=0);
	double GetInsertionFee(double price=0);
	double GetInsertionFee(double price, int category, time_t start_time=0);
	double GetBidIncrement(double price = 0);
	
	// Prices that are absolute.
	double GetFee(FeeEnum fee, double startPrice = 0);

	//special category pricing
	bool CheckForRealEstateListing(int nCategory);
	bool CheckForAutomotiveListing(int nCategory);

private:
	void SetFees();

	bool ChargeFVFforGBP();
	bool ChargeFVFforCAD();
	bool ChargeFVFforDEM();				// PH added 04/23/99
	bool ChargeFVFforAUD();				// nsacco 06/11/99

	int      mCurrencyId;
	clsItem *mpItem;
	double   mPrice;
	double   mExchangeRate;
	//samuel, 7/22/99
	int		 mBillingCurrencyId;
	clsExchangeRates *mpExchangeRates;

	double   eBayFeesUS[FeeEnumSize];
	double   eBayFeesUK[FeeEnumSize];
	double	 eBayFeesDE[FeeEnumSize];	// PH added 04/23/99
	double	 eBayFeesAU[FeeEnumSize];	// nsacco 06/11/99
	double   eBayFeesCA[FeeEnumSize];	// nsacco 06/11/99
};


#define CLSFEES_INCLUDED
#endif /* CLSFEES_INCLUDED */
