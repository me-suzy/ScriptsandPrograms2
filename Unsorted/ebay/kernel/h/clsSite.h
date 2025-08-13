//	File:		clsSite.h
//
// Class:		clsSite
//
//	Author:		Nathan Sacco (nathan@ebay.com)
//
//	Function:
//
//				Represents a site for a country or region
//
// Modifications:
//				- 05/25/99 nsacco		- Created
//				- 07/15/99 nsacco		- Added timezone and listing currency
//				- 08/02/99 petra		- don't use default parameters in constructors
//										  if it's not necessary. it leads to errors that
//										  might otherwise be detected by the compiler..
//

#ifndef CLSSITE_INCLUDED
#define CLSSITE_INCLUDED

#include "clsPartners.h"
#include "clsIntlLocale.h"			// petra
#include "clsIntlResource.h"

class clsSite
{
public:

	// Default constructor
	clsSite();

	// Destructor
	virtual ~clsSite()
	{
		delete [] mpName;
		delete mpPartners;
		delete [] mpParsedString;
		delete mpLocale;			// petra
 	}

	// Constructor
	// nsacco 07/15/99
	// TimeZone = 0 is SanFrancisco - US Pacific Time
	// petra 08/02/99 get rid of default parameters
	clsSite(char *pName, unsigned int id, char *pParsedString, 
		unsigned int TimeZoneId, unsigned int localeId, unsigned int ListingCurrency);


	// member functions
	const char* GetHTMLPath(PageEnum ePage);
	const char* GetCGIPath(PageEnum ePage);
	const char* GetPicsPath(PageEnum ePage);
	const char* GetListingPath(PageEnum ePage);
	const char* GetHeader(PageTypeEnum pageType, PageTypeEnum secondaryPageType);
	const char* GetFooter(PageTypeEnum pageType, PageTypeEnum secondaryPageType);
	
	clsPartners* GetPartners();
	char* GetName() { return mpName; }
	clsIntlResource * GetSiteResource() { return mpSiteResource; }
	int GetId() { return mId; }
	// kakiyama 06/23/99
	char* GetParsedString() { return mpParsedString; }

	// nsacco 07/15/99
	int GetTimeZoneId() { return mTimeZoneId; }

	clsIntlLocale * GetLocale() { return mpLocale; }	// petra
	
	int GetDefaultListingCurrency() { return mDefaultListingCurrency; }

	// used to fill in values when default constructor is used
	void SetId(int id) { mId = id; }
	void SetName(char *pName);
	// kakiyama 06/23/99
	void SetParsedString(char *pParsedString);
	// nsacco 07/15/99
	void SetTimeZoneId(int TimeZoneId) {mTimeZoneId = TimeZoneId;}
	void SetDefaultListingCurrency(int ListingCurrency) {mDefaultListingCurrency = ListingCurrency;}


private:

	clsPartners* mpPartners;	// partner handles
	int mId;					// the site id
	char* mpName;				// the site name
	// kakiyama 06/23/99
	char* mpParsedString;		// the sites string identifier for urls (uk, de, au, etc.)
	// nsacco 07/15/99
	int mTimeZoneId;				// the timezone 
	int mDefaultListingCurrency;// the default currency for listing items for auction

	clsIntlLocale * mpLocale;	// petra: locale 

	int mResourceId;
	clsIntlResource	*	mpSiteResource;
};

#endif /* CLSSITE_INCLUDED */

