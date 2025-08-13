//
// File Name: clsSite.cpp
//
// Description: Class the contains information about a particular
// site - country or regional.
//
// Authors: Nathan Sacco (nathan@ebay.com)
//
//			05/25/99	nsacco	- Created
//			07/15/99	nsacco	- Added timezone and listing currency
//			08/02/99	nsacco	- replaced mpPartners with calls to GetPartners().
//			08/02/99	petra	- get rid of default parameters in constructor.
//

#include "eBayKernel.h"
#include "clseBayHeaderAnnounceWidget.h"
#include "clsPartners.h"
#include "clsSite.h"

// Default constructor
clsSite::clsSite() :
		mpName(NULL),
		mId(SITE_EBAY_MAIN),
		mpPartners(NULL),
		mpParsedString(NULL),
		mTimeZoneId(0),
		mpLocale(NULL),					// petra
		mDefaultListingCurrency(Currency_USD),
		mResourceId(1),
		mpSiteResource(NULL)
{
	// determine the site from the server name
}

// Constructor
// nsacco 07/15/99
clsSite::clsSite(char *pName, unsigned int id, char *pParsedString,
				 unsigned int TimeZoneId, unsigned int localeId, unsigned int ListingCurrency) :
		mpName(NULL),
		mId(id),
		mpPartners(NULL),
		mpParsedString(NULL),
		mTimeZoneId(TimeZoneId),
		mDefaultListingCurrency(ListingCurrency),
		mResourceId(1),
		mpSiteResource(NULL)
{
	int length = 0;

	// initialize site name
	if (pName != NULL)
	{
		length = strlen((char *)pName) + 1;
		mpName = new char[length];
		strcpy(mpName, (char *)pName);
	}

	mpLocale = new clsIntlLocale(localeId, TimeZoneId);	// petra
	mpPartners = new clsPartners(mId);
	mpSiteResource = new clsIntlResource(mResourceId);
	// kakiyama 06/23/99
	if (pParsedString != NULL)
	{
		length = strlen((char *)pParsedString + 1);
		mpParsedString = new char[length];
		strcpy(mpParsedString, (char *)pParsedString);
	}

}


clsPartners* clsSite::GetPartners()
{ 
	if (mpPartners == NULL)
	{
		mpPartners = new clsPartners(mId);
	}
	return mpPartners; 
}

// Get the HTML path
const char* clsSite::GetHTMLPath(PageEnum ePage)
{
	// get the current partner
	clsPartner* thePartner = GetPartners()->GetCurrentPartner();

	return thePartner->GetHTMLPath(ePage);
}

// Get the CGI path
const char* clsSite::GetCGIPath(PageEnum ePage)
{
	// get the current partner
	clsPartner* thePartner = GetPartners()->GetCurrentPartner();

	return thePartner->GetCGIPath(ePage);
}

// Get the pic path
const char* clsSite::GetPicsPath(PageEnum ePage)
{
	// get the current partner
	clsPartner* thePartner = GetPartners()->GetCurrentPartner();

	return thePartner->GetPicsPath(ePage);
}

// Get the listing path
const char* clsSite::GetListingPath(PageEnum ePage)
{
	// get the current partner
	clsPartner* thePartner = GetPartners()->GetCurrentPartner();

	return thePartner->GetListingPath();
}

// Get the header for the page type.
const char* clsSite::GetHeader(PageTypeEnum pageType, PageTypeEnum secondaryPageType)
{
	const char *pRet;
	int	dwLen;
	ostrstream *pStream;	
	int headerLen;
	clseBayHeaderAnnounceWidget *pHeaderAnnounce;
	int i;

	// new ui didn't have a good place for the announcement headers yet, so....
	// return GetHeaderWithoutAnnouncement(pageType, secondaryPageType);

	// anything less than zero is PageTypeUnknown
	if ((pageType < 0) || (secondaryPageType < 0))
		return "";	

	// get the current partner
	clsPartner* thePartner = GetPartners()->GetCurrentPartner();

	i = thePartner->GetUniqueIndex(pageType, secondaryPageType);

	// load if not yet in cache
	if (!thePartner->IsLoaded())
		thePartner->LoadPartner();

	if (i >= thePartner->GetHeaders()->size())
		return "";

	pRet = (*thePartner->GetHeaders())[i];

	// safety
	if (!pRet)
		return "";

	// The header announcements
	if( thePartner->GetHeaderWithAnnouncements()  )
	{		
		if( !(*thePartner->GetHeaderWithAnnouncements())[i] )
		{
			pStream	= new ostrstream;
			pHeaderAnnounce = new clseBayHeaderAnnounceWidget;
			pHeaderAnnounce->EmitHTML(pStream);		
			headerLen = strlen(pRet);
			dwLen = headerLen + 
					strlen(pAnnouncementHeaderPrefix) +		
					pStream->pcount() + 
					strlen(pAnnouncementHeaderSuffix) + 	
					1;		
			(*thePartner->GetHeaderWithAnnouncements())[i] = new char[dwLen];
			if( (*thePartner->GetHeaderWithAnnouncements())[i] )
			{			
				memset((*thePartner->GetHeaderWithAnnouncements())[i], '\0', dwLen);
				strcat((*thePartner->GetHeaderWithAnnouncements())[i], pRet);
				strcat((*thePartner->GetHeaderWithAnnouncements())[i], pAnnouncementHeaderPrefix);	// AlexP 05/14/99
				strncat((*thePartner->GetHeaderWithAnnouncements())[i], pStream->str(), pStream->pcount());					
				strcat((*thePartner->GetHeaderWithAnnouncements())[i], pAnnouncementHeaderSuffix);	// AlexP 05/14/99
				pRet = (*thePartner->GetHeaderWithAnnouncements())[i]; 
			}
			else if (!(*thePartner->GetHeaderWithAnnouncements())[i])
			{
				delete pHeaderAnnounce;
				delete pStream;
				return "";		
			}
			delete pStream;
			delete pHeaderAnnounce;
			return pRet;
		}
		else
		{
			pRet = (*thePartner->GetHeaderWithAnnouncements())[i];	
			if( !pRet )
				return "";
			else
				return pRet;
		}
	}		
	else
		return "";
}


// Get the footer for the page type.
const char* clsSite::GetFooter(PageTypeEnum pageType, PageTypeEnum secondaryPageType)
{
	const char *pRet;
	int i;

	// anything less than zero is PageTypeUnknown
	if ((pageType < 0) || (secondaryPageType < 0))
		return "";	

	// get the current partner
	clsPartner* thePartner = GetPartners()->GetCurrentPartner();

	i = thePartner->GetUniqueIndex(pageType, secondaryPageType);

	// load if not yet in cache
	if (!thePartner->IsLoaded())
		thePartner->LoadPartner();

	if (i >= thePartner->GetFooters()->size())
		return "";

	pRet = (*thePartner->GetFooters())[i];

	// safety
	if (!pRet)
		return "";
	
	return pRet;
}
	
void clsSite::SetName(char *pName)
{
	delete [] mpName;
	mpName = NULL;

	if (pName != NULL)
	{
		mpName = new char[strlen(pName) + 1];
		strcpy(mpName, pName);
	}
}

void clsSite::SetParsedString(char *pParsedString)
{
	delete [] mpParsedString;
	mpParsedString = NULL;

	if (pParsedString != NULL)
	{
		mpParsedString = new char [strlen(pParsedString) + 1];
		strcpy(mpParsedString, pParsedString);
	}
}
