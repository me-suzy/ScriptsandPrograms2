/*	$Id: clsCurrencyWidget.h,v 1.4.164.2 1999/08/03 00:52:37 phofer Exp $	*/
//
//	File:		clsCurrencyWidget.h
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
//				- 07/21/99	petra	- use clsIntlLocale!
//				- 07/29/99	petra	- get rid of the exchange stuff
//
// Usage:
//			clsCurrencyWidget* pCurrencyWidget = new clsCurrencyWidget(mMarketPlace, nativeCurrencyId, nativeAmount);
//			pCurrencyWidget->EmitHTML(pStream);
//			delete pCurrencyWidget;
//
//		Options:
//			Whether to convert the amount shown to a different currency.
//////////////////////////////////////////////////////////////////////

#ifndef CLSCURRENCYWIDGET_INCLUDED
#define CLSCURRENCYWIDGET_INCLUDED

#include "clseBayWidget.h"

//class clsCurrency;
//class clsCurrencies;

class clsCurrencyWidget : public clseBayWidget  
{
public:
	clsCurrencyWidget(clsWidgetHandler *pHandler, clsMarketPlace *pMarketPlace, clsApp *pApp);
	clsCurrencyWidget(clsMarketPlace *pMarketPlace, int currencyId, double amount);
	virtual ~clsCurrencyWidget();

	// The heart of it.
	bool   EmitHTML(ostream *pStream);	// Must be implemented, it's a pure virtual function above

	// Some getters and setters.
	void   Set(int currencyId, double amount);
	void   SetNativeAmount (double amount) { mAmount = amount; }
	void   SetNativeCurrencyId (int currencyId) { mCurrencyId = currencyId; }

	void   SetLimitCheck(bool check=true) {mSetLimitCheck = check;}
	void   SetBold(bool bold=true) {mSetBold = bold;}

	int    GetCurrencyId() { return mCurrencyId; }
	double GetAmount() { return mAmount; }

    // For translation to and from text.
	void   SetParams(vector<char *> *pvArgs);
    void   SetParams(const void *pData, const char *pStringBase, bool mFixBytes);
    long   GetBlob(clsDataPool *pDataPool, bool mReverseBytes);

	static clseBayWidget *MakeWidget(clsWidgetHandler *pHandler,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clsCurrencyWidget(pHandler, pMarketPlace, pApp); }

	void DrawTag(ostream *pStream, const char *pName, bool comments = true);

private:
	void			SetDefaults(clsMarketPlace *pMarketPlace);
	void			FormatMoney(int currencyId, double amount, ostream *pStream);

	double			mAmount;
	int				mCurrencyId;

	bool			mSetLimitCheck;
	bool			mSetBold;

};

#endif // ifndef CLSCURRENCYWIDGET_INCLUDED
