/*	$Id: clsDailyFinance.cpp,v 1.5 1999/04/07 05:42:39 josh Exp $	*/
//
//	File:		clsDailyFinance.cc
//
// Class:	clsDailyFinance
//
//	Author:	Wen Wen (wen@ebay.com)
//
//	Function:
//
//				Information for Daily Finance
//
// Modifications:
//				- 10/06/97 Wen	- Created
//

#include "eBayKernel.h"

clsDailyFinance::clsDailyFinance(int MaxAction)
{
	mMaxAction = MaxAction;
	mpAmount = new float[MaxAction + 1];
	memset(mpAmount, 0, (MaxAction + 1) * sizeof(float));
	mTheDay = 0L;
}

// set the data
void clsDailyFinance::SetData(clsDailyFinanceRaw *pDailyFinanceRaw)
{
	mpAmount[pDailyFinanceRaw->GetAction()] = pDailyFinanceRaw->GetAmount();
}

// Retrieve the dollar amount for specified action
float clsDailyFinance::GetAmount(int Action)
{
	if (Action > mMaxAction)
		return 0.0;

	return mpAmount[Action];
}

// Get the credits (except Courtesy Credit)
float clsDailyFinance::GetNoSaleCredits()
{
	return GetAmount(AccountDetailCreditNoSale) +
		   GetAmount(AccountDetailCreditPartialSale) +
		   GetAmount(AccountDetailCreditInsertion) +
		   GetAmount(AccountDetailCreditBold) +
		   GetAmount(AccountDetailCreditFeatured) +
		   GetAmount(AccountDetailCreditCategoryFeatured) +
		   GetAmount(AccountDetailCreditFinalValue) +
		   GetAmount(AccountDetailCreditGiftIcon) +
		   GetAmount(AccountDetailCreditGallery) + 
		   GetAmount(AccountDetailCreditFeaturedGallery);
}

// Get Debit
float clsDailyFinance::GetOtherCRDR()
{
	return GetAmount(AccountDetailAWCredit) +
		   GetAmount(AccountDetailAWDebit) +
		   GetAmount(AccountDetaileBayDebit) + 
		   GetAmount(AccountDetailCreditDuplicateListing) +
		   GetAmount(AccountDetaileBayCredit) +
		   GetAmount(AccountDetailPromotionalCredit);
}
