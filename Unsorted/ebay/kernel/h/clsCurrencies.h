/*	$Id: clsCurrencies.h,v 1.5.140.2 1999/08/03 22:26:47 phofer Exp $	*/
//
//	File:	clsCurrencies.h
//
//	Class:	clsCurrencies, clsCurrency
//
//	Author: Barry Boone (barry@ebay.com)
//
//	Function:
//		Maintain information pertaining to a particular currency. 
//		Used primarily for display, and used heavily by clsCurrencyWidget.
//
// Modifications:
//				- 2/21/99 barry	- Created
//				- 05/24/99 nsacco - added Australian dollars
//				- 07/20/99 nsacco - updated currerncy hex symbols
//				- 08/03/99 petra  - add nameResId, lose currency subclasses
//
#ifndef CLSCURRENCIES_INCLUDED

#include "eBayTypes.h"

class clsExchangeRates;


class clsCurrency
{
public:
	clsCurrency(int id, char *name, char *namePlural,
			    char *subName, char *subNamePlural, 
				char *symbol, char *iso4217, 
				int subCurrencyRatio,
				int nameResId);
	~clsCurrency();

	int GetId() { return mId; }
	char *GetSymbol() { return mpSymbol; }

	char *GetName(); // petra
	char *GetNamePlural() { return mpNamePlural; }

private:
	int     mId;

	char	mpName[64];
	char	mpNamePlural[64];
	char	mpSubName[64];
	char	mpSubNamePlural[64];
	char	mpSymbol[16];
	char	mpISO4217[8];

	int		mSubCurrencyRatio;
	int		mCurrencyNameResourceId;
	int		mNameResId;
};

typedef vector<clsCurrency *> CurrencyVector;

class clsCurrencies
{
public:

	clsCurrencies(clsMarketPlace *pMarketPlace);
	~clsCurrencies();
	
	// Info for all currencies.
	int GetNumCurrencies() { return mNumCurrencies; }

	void GetAllCurrencyNames(vector<char *> *pName);
	void GetAllCurrencyIds(vector<int> *pId);
	int  DetermineNumCurrencies(); // via the db

	// Get and set current currency info.
	void SetCurrentCurrency(int id) { mCurrentCurrency = id; }
	int  GetCurrentCurrency() { return mCurrentCurrency; } 

	clsExchangeRates *GetExchangeRates();
	
	clsCurrency *GetCurrency(int id);

private:

	// These methods in clsCurrencies will help manage the cache:

	//	Free the memory in the cache
	void PurgeCurrenciesCache();

	// Fetch all the entries from ebay_currencies.
	// Allocate memory for the cache (an array of Currency structures).
	// Fill up the cache (allocate each entry in the array).
	void PopulateCurrenciesCache();

	// Helper for initializing the caches.
	void GetAllCurrenciesFromDatabase(CurrencyVector *pvCurrencies);

	// Parent MarketPlace
	clsMarketPlace	*mpMarketPlace;

	// These instance variables will keep track of the cache:
	clsCurrency    **mpCurrenciesCache;
	bool			 currenciesCacheDirty;

	// Index into the cache.
	int mCurrentCurrency;

	// Number of currencies in the cache.
	int mNumCurrencies;

	// This is for doing conversions.
	clsExchangeRates *mpExchangeRates;

};

#define CLSCURRENCIES_INCLUDED
#endif /* CLSCURRENCIES_INCLUDED */