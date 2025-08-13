/*	$Id: clsCountries.h,v 1.4.160.1 1999/08/01 03:02:05 barry Exp $	*/
//
//	File:	clsCountries.h
//
//	Class:	clsCountries
//
//	Author: Barry Boone (barry@ebay.com)
//
//	Function:
//
//		Keep information about countries in a ca
//
// Modifications:
//				- 12/02/98 barry	- Created
//				- 06/28/99 petra	- add GetName. remove functions that
//							return something based on the country name
//				- 07/19/99 nsacco	- added GetCountryDir() to clsCountries
//
#ifndef CLSCOUNTRIES_INCLUDED
#include "eBayTypes.h"

// The original enum type CountryCodes here was moved to eBayTypes.h --- Steve Yan


typedef struct
{
	int		 value;
	char	 pLabel[64];
} ScrollingSelection;

class clsCountry
{
public:
	clsCountry(int id, const char *code, const char *americanName, const char *dirName, const char *slanderStrict, const int nameResId);	// petra
	~clsCountry();

	int   GetId();
	bool  GetSlanderStrict();
	char *GetDirName();
	char *GetCode();
// petra	char *GetAmericanName();
	char *GetName();	// petra

private:
	int     mId;
	bool    mSlanderStrict;
	char	mpCode[3];
	char	mpAmericanName[64];
	int 	mNameResId;	// petra
	char	mpDirName[32];
};

typedef vector<clsCountry *> CountryVector;

class clsCountries
{
public:

	clsCountries(clsMarketPlace *pMarketPlace);
	~clsCountries();
	
	// Info for all countries.
	int GetNumCountries();

	// Get and set current country info.
	void SetCurrentCountry(int id);
// petra	void SetCurrentCountry(const char* name);
	int  GetCurrentCountry(); 
	const char* GetCurrentCountryCode();
	const char* GetCurrentCountryDir();

	// Get information for any country.
// petra	bool CountryIsSlanderStrict(const char *americanName);
	bool CountryIsSlanderStrict(int countryId);
	void GetAllCountryNames(vector<char *> *pName, bool american);
	void GetAllCountryIds(vector<int> *pId);
	int  DetermineNumCountries(); // from the DB
// petra	int  GetCountryId(const char* name);
	void GetCountryName(int id, char* pCountry);
	int  GetCountryIdByCode(const char* code);
	// nsacco 07/19/99
	const char* GetCountryDir(int id);
	

	// Some utilities.
	void FillScrollingSelection(ScrollingSelection *Selection);

	static const int COUNTRY_NONE;

private:

	// These methods in clsCountries will help manage the cache:

	//	Free the memory in the cache
	void PurgeCountriesCache();

	// Fetch all the entries from ebay_countries.
	// Allocate memory for the cache (an array of Country structures).
	// Fill up the cache (allocate each entry in the array).
	void PopulateCountriesCache();

	// Helper for initializing the caches.
	void GetAllCountriesFromDatabase(CountryVector *pvCountries);

	// Parent MarketPlace
	clsMarketPlace	*mpMarketPlace;

	// These instance variables will keep track of the cache:
	clsCountry    **mpCountriesCache;
	bool			countriesCacheDirty;

	// Index into the cache.
	int mCurrentCountry;

	// Number of countries in the cache.
	int mNumCountries;

};

#define CLSCOUNTRIES_INCLUDED
#endif /* CLSCOUNTRIES_INCLUDED */