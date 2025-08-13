/*	$Id: clsCurrencyWidget.cpp,v 1.4.162.2 1999/08/03 00:52:37 phofer Exp $	*/
//
//	File:		clsCurrencyWidget.cpp
//
//	Class:		clsCurrencyWidget
//
//	Author:		Barry Boone (barry@ebay.com)
//
//	Function:
//		Show all monetary amounts appropriately for the currency they
//        are represented in.
//      Allow monetary amounts to appear in alternate currencies
//        on-the-fly.
//
//	Modifications:
//				- 2/20/99 Barry	- Created
//				- 6/09/99 petra	- don't use currency name plural in FormatMoney
//				- 07/21/99 petra	- use clsIntlLocale!
//				- 07/29/99 petra	- get rid of the exchange stuff
//
//////////////////////////////////////////////////////////////////////

#include "widgets.h"
#include "clsCurrencyWidget.h"
// petra #include "clsExchangeRates.h"

//////////////////////////////////////////////////////////////////////
// Construction/Destruction
//////////////////////////////////////////////////////////////////////

struct clsCurrencyWidgetOptionsStruct
{
	bool    mSetLimitCheck;
	bool    mSetBold;
	int		mCurrencyId;
};

void clsCurrencyWidget::SetDefaults(clsMarketPlace *pMarketPlace)
{
	mCurrencyId    = Currency_USD;
	mAmount        = 0.0;

	mSetLimitCheck       = false;
	mSetBold             = false;
}

clsCurrencyWidget::clsCurrencyWidget(clsWidgetHandler *pHandler,
                                     clsMarketPlace *pMarketPlace,
                                     clsApp *pApp)
                                 : clseBayWidget(pHandler, pMarketPlace, pApp)
{
	SetDefaults(mpMarketPlace);
}

clsCurrencyWidget::clsCurrencyWidget(clsMarketPlace *pMarketPlace,
                                     int currencyId,
									 double amount)
                                 : clseBayWidget(pMarketPlace)
{
	SetDefaults(mpMarketPlace);

	mCurrencyId		= currencyId;
	mAmount			= amount;
}

clsCurrencyWidget::~clsCurrencyWidget()
{
}

void clsCurrencyWidget::DrawTag(ostream *pStream, const char *pName, bool /* comments = true */)
{
	*pStream << "<"
			 << pName;

	*pStream << ">";
}

bool clsCurrencyWidget::EmitHTML(ostream *pStream)
{
	if (mpMarketPlace == NULL)
		return false;

	FormatMoney(mCurrencyId, mAmount, pStream);
	return true;
}


void clsCurrencyWidget::FormatMoney(int currencyId, double amount, ostream *pStream)
{
	char   pStr[64];
	double maxAmount;

	// Prepare the stream
	pStream->setf(ios::fixed, ios::floatfield);
	pStream->setf(ios::showpoint, 1);
	pStream->precision(2);

	if (mSetBold)
		*pStream << "<b>";

	maxAmount = mpMarketPlace->GetMaxAmount(currencyId);

	if (mSetLimitCheck && amount > maxAmount)
	{
		*pStream << "Error";
	}
	else
	{
		clsIntlLocale * pLocale = mpMarketPlace->GetSites()->GetCurrentSite()->GetLocale(); // petra
		pLocale->GetCurrencyAmountFormatted(pStr, amount, currencyId);	// petra
		*pStream << pStr;
	}

	if (mSetBold)
		*pStream << "</b>";
}

void clsCurrencyWidget::SetParams(vector<char *> *pvArgs)
{
	const char * pValue;		// petra

	// Let's run through our known attributes and check them out.
    if (GetParameterValue("BOLD", pvArgs))
        mSetBold = true;

    if (GetParameterValue("LIMITCHECK", pvArgs))
        mSetLimitCheck = true;

	if (pValue = GetParameterValue("CURRENCYID", pvArgs))	// petra
		mCurrencyId = atoi(pValue);					// petra
}

void clsCurrencyWidget::SetParams(const void *pData, const char *, bool)
{
    clsCurrencyWidgetOptionsStruct *pOptions;

    pOptions = (clsCurrencyWidgetOptionsStruct *) pData;

    mSetBold	     = pOptions->mSetBold != 0;
    mSetLimitCheck   = pOptions->mSetLimitCheck != 0;
	mCurrencyId		= pOptions->mCurrencyId;

    return;
}

long clsCurrencyWidget::GetBlob(clsDataPool *pDataPool, bool mReverseBytes)
{
    clsCurrencyWidgetOptionsStruct theOptions;

    theOptions.mSetBold		    = mSetBold;
    theOptions.mSetLimitCheck   = mSetLimitCheck;
	theOptions.mCurrencyId		= mCurrencyId;

	/*
    if (mReverseBytes)
    {
        theOptions.mExpansionOffset = clsUtilities::FixByteOrder32(theOptions.mExpansionOffset);
    }
	*/

    return pDataPool->AddData(&theOptions, sizeof (theOptions));
}
