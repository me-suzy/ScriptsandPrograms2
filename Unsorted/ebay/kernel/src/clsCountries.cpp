/*	$Id: clsCountries.cpp	*/
//
//	File:	clsCountries.cpp
//
//	Class:	clsCountries
//
//	Author:	Barry Boone
//
//	Function:
//
//			Represents countries and some things we know about them.
//
//	Modifications:
//				  mm/dd/yy
//				- 11/21/98 barry	- Created
//				- 05/24/99 nsacco	- added Germany and Australia to GetCurrentCountryDir()
//				- 06/28/99 petra	- add GetName, remove functions that deal with mpAmericanName
//				- 07/02/99 nsacco	- changed GetCurrentCountryDir() as a result of db change.
//				- 07/19/99 nsacco	- added GetCountryDir()
//


#include "eBayKernel.h" 

const int clsCountries::COUNTRY_NONE = 0;

// Class Country first.
clsCountry::clsCountry(int id, const char *code, const char *americanName, const char *dirName, const char *slanderStrict, const int nameResId)
{
	mId = id;
	strcpy(mpCode, code);
	strcpy(mpAmericanName, americanName);
	mNameResId = nameResId;	// petra
	strcpy(mpDirName, dirName);
	strcat(mpDirName, "/");
	mSlanderStrict = (strcmp(slanderStrict, "Y") == 0);
}

clsCountry::~clsCountry()
{
}

int clsCountry::GetId()
{
	return mId;
}

bool clsCountry::GetSlanderStrict()
{
	return mSlanderStrict;
}

char *clsCountry::GetDirName()
{
	return mpDirName;
}

// petra instead of GetAmericanName()
char *clsCountry::GetName()
{
	return mpAmericanName;	// needs to go out
// 	return clsResources::GetResourceFromId (mNameResId /* , current site? */ );
}

char *clsCountry::GetCode()
{
	return mpCode;
}

//
// Default Constructor
//
clsCountries::clsCountries(clsMarketPlace *pMarketPlace)
{
	mpMarketPlace	= pMarketPlace;
	mNumCountries   = 224; // Update when populating the cache
	mCurrentCountry = clsCountries::COUNTRY_NONE;
	mpCountriesCache = NULL;

	// Create the cache.
	PopulateCountriesCache();
}

//
// Destructor
//
clsCountries::~clsCountries()
{
	// Destroy the cache.
	PurgeCountriesCache();
}

// Some getters and setters. 

int clsCountries::GetNumCountries()
{
	return mNumCountries;
}

void clsCountries::SetCurrentCountry(int id) 
{ 
	mCurrentCountry = id; 
}

int clsCountries::GetCurrentCountry() 
{ 
	return mCurrentCountry;
}

// petra void clsCountries::SetCurrentCountry(const char* americanName)
// petra {
// petra 	mCurrentCountry = GetCountryId(americanName);
// petra }

const char* clsCountries::GetCurrentCountryCode()
{
	if (mCurrentCountry == clsCountries::COUNTRY_NONE)
		return "";
	else
		return (mpCountriesCache[mCurrentCountry - 1])->GetCode();
}

const char* clsCountries::GetCurrentCountryDir()
{
	char* theCountryCode = NULL;
	char* theReturnString = NULL;

	switch (mCurrentCountry)
	{
	// NOTE: These need to be modified to use 2 letter country codes instead
	// of the full country name. ex) /aw/Canada is wrong, /aw/ca is right
	case Country_None:
		return "";
		break;
	case Country_US:
	case Country_CA:	// TODO - switch to 2 letter codes
	case Country_UK:	// TODO - switch to 2 letter codes
		return (mpCountriesCache[mCurrentCountry - 1])->GetDirName();
		break;
	// nsacco 05/24/99
	case Country_DE:
	case Country_AU:
		theCountryCode = (mpCountriesCache[mCurrentCountry - 1])->GetCode();
		theReturnString = new char[strlen(theCountryCode)];
		strcpy(theReturnString, theCountryCode);
		return strcat(theReturnString,"/");
		break;
	}

	return "";
}


int clsCountries::GetCountryIdByCode(const char *code)
{
	int i;

	// Find the name in the cache and set the code.	
	for (i = 0; i < mNumCountries; i++)
	{
		if ( strcmp((mpCountriesCache[i])->GetCode(), code) == 0)
			return (i+1);
	}
	return clsCountries::COUNTRY_NONE;
}


bool clsCountries::CountryIsSlanderStrict(int id)
{
	if (id == clsCountries::COUNTRY_NONE)
		return false;
	else
		return (mpCountriesCache[id - 1])->GetSlanderStrict();
}

// nsacco 06/14/99
// TODO - need a get native name!!! or this returns the name in the correct language
void clsCountries::GetCountryName(int id, char *pCountry)
{
	if (id == clsCountries::COUNTRY_NONE)
		pCountry[0] = '\0';
	else if (id > mNumCountries)
		pCountry[0] = '\0';
	else
		strcpy(pCountry, (mpCountriesCache[id - 1])->GetName());	// petra
}

// Create the Vector object in the caller; 
// size it in this method; 
// delete it in caller.
void clsCountries::GetAllCountryNames(vector<char *> *pName, bool american)
{
	int i;

	// Find the name in the cache and set the code.
	for (i = 0; i < mNumCountries; i++)
	{
		pName->push_back((mpCountriesCache[i])->GetName());	// petra
	}
}

// Create the Vector object in the caller; 
// size it in this method; 
// delete it in caller.
void clsCountries::GetAllCountryIds(vector<int> *pId)
{
	int i;

	// Find the name in the cache and set the code.
	for (i = 0; i < mNumCountries; i++)
	{
		pId->push_back((mpCountriesCache[i])->GetId());
	}
}


// Private member functions.
// These methods in clsCountries will help manage the cache:

// Helper for initializing the cache.
void clsCountries::GetAllCountriesFromDatabase(CountryVector *pvCountries)
{
	gApp->GetDatabase()->GetAllCountries(pvCountries);
}

// Set the number of countries.
int clsCountries::DetermineNumCountries()
{
	return gApp->GetDatabase()->DetermineNumCountries();
}

void clsCountries::FillScrollingSelection(ScrollingSelection *selection)
{
	int i;

	// Be sure to allocate selection in the caller.
	// It can be allocated by: 
	// selection = new ScrollingSelection[pCountries->GetNumCountries() + 1];

	// Go through all the countries in id order and fill 
	// in the scrolling list.
	for (i = 0; i < mNumCountries; i++)
	{
		selection[i].value = mpCountriesCache[i]->GetId();
		strcpy(selection[i].pLabel, mpCountriesCache[i]->GetName());	// petra
	}

	// At the end, put -1 and NULL.
	selection[mNumCountries].value  = -1;
	strcpy(selection[mNumCountries].pLabel, "\0"); 
}

//	Free the memory in the cache.
void clsCountries::PurgeCountriesCache()
{
	int i;

	// Clear it.
	if (mpCountriesCache)
	{
		for (i = 0; i < mNumCountries; i++)
		{
			if (mpCountriesCache[i])
			{
				delete (mpCountriesCache[i]);
				mpCountriesCache[i] = NULL;
			}
		}
		delete [] mpCountriesCache;
	}

	mpCountriesCache = NULL;

	// Cache is dirty now.
	countriesCacheDirty = true;
}

// Fetch all the entries from ebay_countries.
// Allocate memory for the cache (an array of Country structures).
// Fill up the cache (allocate each entry in the array).
void clsCountries::PopulateCountriesCache()
{
	int						 i;
	CountryVector::iterator  iter;
	CountryVector			 vCountries;
 
	// Just in case someone is calling this without first having
	// purged the cache.
	PurgeCountriesCache();

	mNumCountries = DetermineNumCountries();
	mpCountriesCache = new clsCountry*[mNumCountries];	
	
	// We know how many entries we're going to have, so let's 
	// lend a helping hand.
	vCountries.reserve(mNumCountries);

	// Fetch all countries from the database.
	GetAllCountriesFromDatabase(&vCountries);

	// Initialize.
	for (i = 0, iter = vCountries.begin(); iter != vCountries.end(); iter++, i++)
	{
		mpCountriesCache[i] = *iter; // in order of ids
	}

	// Cache is now fresh.
	countriesCacheDirty = false;

}

// nsacco 07/19/99
const char* clsCountries::GetCountryDir(int id)
{
	int i;

	// Find the name in the cache and set the code.	
	for (i = 0; i < mNumCountries; i++)
	{
		if (mpCountriesCache[i]->GetId() == id)
			return mpCountriesCache[i]->GetDirName();
	}

	return "/";
}