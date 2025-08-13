/*	$Id: clsCurrencies.cpp	*/
//
//	File:	clsCurrencies.cpp
//
//	Class:	clsCurrencies, clsCurrency and subclasses
//
//	Author:	Barry Boone
//
//	Function:
//		Maintain information pertaining to a particular currency. 
//		Used primarily for display, and used heavily by clsCurrencyWidget.
//
//	Modifications:
//				- 2/21/99 barry	- Created
//				- 6/09/99 petra	- translatable strings are loaded on demand
//								  from resources, not table
//				- 07/13/99 nsacco - increased the number of currencies
//				- 07/21/99 petra  - use clsIntlLocale!
//				- 08/02/99 nsacco - set the currency based on the site.
//				- 08/03/99 petra	- add name_res_id



#include "eBayKernel.h" 
#include "clsExchangeRates.h"

// Class Currency first.
clsCurrency::clsCurrency(int id, char *name, char *namePlural,
						 char *subName, char *subNamePlural, 
						 char *symbol, char *iso4217, 
						 int subCurrencyRatio,
						 int nameResId)
{
	mId = id;
	strcpy(mpName, name);
	strcpy(mpNamePlural, namePlural);
	strcpy(mpSubName, subName);
	strcpy(mpSubNamePlural, subNamePlural);
	mCurrencyNameResourceId = 0; // petra replace this with the #defined symbol!!!
	strcpy(mpSymbol, symbol);
	strcpy(mpISO4217, iso4217);
	mSubCurrencyRatio = subCurrencyRatio;
	mNameResId = nameResId;

}

clsCurrency::~clsCurrency()
{
}

// petra get name when needed from resource
char * clsCurrency::GetName()
{
	return mpName;
//	return clsResources::GetResourceFromId (mNameResourceId 
	/*, 2nd parm is current site - does the function assume that?? */
//	);
}

//
// Default Constructor
//
clsCurrencies::clsCurrencies(clsMarketPlace *pMarketPlace)
{
	mpExchangeRates   = NULL;
	mpMarketPlace	  = pMarketPlace;
	// nsacco 07/13/99
	mNumCurrencies    = 14; // Update when populating the cache
	// nsacco 08/02/99
	// set currency based on the site (previous default was Currency_USD
	mCurrentCurrency  = mpMarketPlace->GetSites()->GetCurrentSite()->GetDefaultListingCurrency();
	mpCurrenciesCache = NULL;
	
	// Create the cache.
	PopulateCurrenciesCache();

	mpExchangeRates = new clsExchangeRates;
}

//
// Destructor
//
clsCurrencies::~clsCurrencies()
{
	delete mpExchangeRates;

	// Destroy the cache.
	PurgeCurrenciesCache();
}

// nsacco 06/15/99
// fixed so this is not assuming that the currency ids
// are the same as their index in the cache
clsCurrency *clsCurrencies::GetCurrency(int id)
{
	// original code
	// return mpCurrenciesCache[id - 1];

	// TODO check for invalid id?
	
	// Find the id in the cache
	// All currencies should be in the cache
	for (int i = 0; i < mNumCurrencies; i++)
	{
		if (mpCurrenciesCache[i]->GetId() == id)
			return mpCurrenciesCache[i];
	}

	// TODO - return a default currency
	return mpCurrenciesCache[0];
}

// Create the Vector object in the caller; 
// size it in this method; 
// delete it in caller.
void clsCurrencies::GetAllCurrencyNames(vector<char *> *pName)
{
	int i;

	// Find the name in the cache and set the code.
	for (i = 0; i < mNumCurrencies; i++)
	{
		pName->push_back((mpCurrenciesCache[i])->GetName());
	}
}

// Create the Vector object in the caller; 
// size it in this method; 
// delete it in caller.
void clsCurrencies::GetAllCurrencyIds(vector<int> *pId)
{
	int i;

	// Find the name in the cache and set the code.
	for (i = 0; i < mNumCurrencies; i++)
	{
		pId->push_back((mpCurrenciesCache[i])->GetId());
	}
}

clsExchangeRates *clsCurrencies::GetExchangeRates()
{
	return mpExchangeRates;
}

// Private member functions.
// These methods in clsCurrencies will help manage the cache:

// Helper for initializing the cache.
void clsCurrencies::GetAllCurrenciesFromDatabase(CurrencyVector *pvCurrencies)
{
	gApp->GetDatabase()->GetAllCurrencies(pvCurrencies);
}

// Set the number of currencies.
int clsCurrencies::DetermineNumCurrencies()
{
	return gApp->GetDatabase()->DetermineNumCurrencies();
}


//	Free the memory in the cache.
void clsCurrencies::PurgeCurrenciesCache()
{
	int i;

	// Clear it.
	if (mpCurrenciesCache)
	{
		for (i = 0; i < mNumCurrencies; i++)
		{
			if (mpCurrenciesCache[i])
			{
				delete (mpCurrenciesCache[i]);
				mpCurrenciesCache[i] = NULL;
			}
		}
		delete [] mpCurrenciesCache;
	}

	mpCurrenciesCache = NULL;

	// Cache is dirty now.
	currenciesCacheDirty = true;
}

// Fetch all the entries from ebay_currencies.
// Allocate memory for the cache (an array of Currency structures).
// Fill up the cache (allocate each entry in the array).
void clsCurrencies::PopulateCurrenciesCache()
{
	int						 i;
	CurrencyVector::iterator iter;
	CurrencyVector			 vCurrencies;
 
	// Just in case someone is calling this without first having
	// purged the cache.
	PurgeCurrenciesCache();

	mNumCurrencies = DetermineNumCurrencies();
	mpCurrenciesCache = new clsCurrency*[mNumCurrencies];	
	
	// We know how many entries we're going to have, so let's 
	// lend a helping hand.
	vCurrencies.reserve(mNumCurrencies);

	// Fetch all currencies from the database.
	GetAllCurrenciesFromDatabase(&vCurrencies);

	// Initialize.
	for (i = 0, iter = vCurrencies.begin(); iter != vCurrencies.end(); iter++, i++)
	{
		mpCurrenciesCache[i] = *iter; // in order of ids
	}

	// Cache is now fresh.
	currenciesCacheDirty = false;

}