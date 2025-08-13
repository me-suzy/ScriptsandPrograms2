/*	$Id: clsPSSearch.cpp,v 1.2.2.1.4.1 1999/08/05 18:58:49 nsacco Exp $	*/
//
//	File:	clsPSSearch.cpp
//
//	Class:	clsPSSearch
//
//	Author:	Wen Wen
//
//	Function:
//		This class is to hold the information for a personal shopper search
//							
// Modifications:
//				- 2/9/99	Wen - Created
//
#include "ebihdr.h"


#include "clsPSSearch.h"

#define PSSTRING_METHODS(variable)				\
const char *clsPSSearch::Get##variable()		\
{												\
	if (!mp##variable)							\
	{											\
		return mEmpty;								\
	}											\
	return mp##variable;						\
}												\
void clsPSSearch::Set##variable(const char *pNew)	\
{												\
	delete [] mp##variable;						\
	mp##variable = NULL;						\
	if (!pNew)									\
		return;									\
	mp##variable = new char[strlen(pNew) + 1];	\
	strcpy(mp##variable, pNew);					\
	return;										\
}

PSSTRING_METHODS(Email);
PSSTRING_METHODS(Password);
PSSTRING_METHODS(Query);
PSSTRING_METHODS(SearchDesc);
PSSTRING_METHODS(MinPrice);
PSSTRING_METHODS(MaxPrice);
PSSTRING_METHODS(EmailFrequency);
PSSTRING_METHODS(EmailDuration);
PSSTRING_METHODS(Reg);
PSSTRING_METHODS(StartingDate);

clsPSSearch::clsPSSearch(const char* pEmail,
							  const char* pPassword,
							  const char* pQuery,
							  const char* pSearchDesc,
							  const char* pMinPrice,
							  const char* pMaxPrice,
							  const char* pEmailFrequency,
							  const char* pEmailDuration,
							  const char* pReg)
{
	mpEmail = NULL;
	mpPassword = NULL;
	mpQuery = NULL;
	mpSearchDesc = NULL;
	mpMinPrice = NULL;
	mpMaxPrice = NULL;
	mpEmailFrequency = NULL;
	mpEmailDuration = NULL;
	mpReg = NULL;
	mpStartingDate = NULL;

	mEmpty[0] = 0;

	SetEmail(pEmail);
	SetPassword(pPassword);
	SetQuery(pQuery);
	SetSearchDesc(pSearchDesc);
	SetMinPrice(pMinPrice);
	SetMaxPrice(pMaxPrice);
	SetEmailFrequency(pEmailFrequency);
	SetEmailDuration(pEmailDuration);
	SetReg(pReg);
}

clsPSSearch::~clsPSSearch()
{
	delete mpEmail;
	delete mpPassword;
	delete mpQuery;
	delete mpSearchDesc;
	delete mpMinPrice;
	delete mpMaxPrice;
	delete mpEmailFrequency;
	delete mpEmailDuration;
	delete mpReg;
	delete mpStartingDate;
}

//
// create the search URL based on the information
//
const char* clsPSSearch::GetURLForSearch()
{
	// HOW TO SEPARATE THE URL????????
	// create the search url


//	sprintf(mURL, 
//		"http://search.ebay.com/cgi-bin/texis/ebay/results.html?"
//		"maxRecordsPerPage=50&SortProperty=MetaStartSort&SortOrder=[d]&query=%s", 
//		mpMarketPlace->GetSearchPath(), mpQuery);

// kakiyama 08/02/99
// resourced using GetSearchPath()
clsMarketPlaces *pMarketPlaces;
clsMarketPlace  *pMarketPlace;

	pMarketPlaces = gApp->GetMarketPlaces();
	pMarketPlace  = pMarketPlaces->GetCurrentMarketPlace();

	sprintf(mURL, 
		"%stexis/ebay/results.html?"
		"maxRecordsPerPage=50&SortProperty=MetaStartSort&SortOrder=[d]&query=%s", 
		(pMarketPlace->GetSearchPath()), mpQuery);
	
	// append search desc if needed
	if (mpSearchDesc && (mpSearchDesc[0] == 'y' || mpSearchDesc[0] == 'Y'))
	{
		strcat(mURL, "&srchdesc=y");
	}

	// append minimum price if needed
	if (mpMinPrice && mpMinPrice[0] != '\0' && strcmp(mpMinPrice, "default"))
	{
		strcat(mURL, "&minPrice=");
		strcat(mURL, mpMinPrice);
	}

	// append minimum price if needed
	if (mpMaxPrice && mpMaxPrice[0] != '\0' && strcmp(mpMaxPrice, "default"))
	{
		strcat(mURL, "&maxPrice=");
		strcat(mURL, mpMaxPrice);
	}

	return mURL;
}

//
// receive the URL and parse it
//
void clsPSSearch::SetURLForSearch(const char* pURL)
{
	char	Value[256];

	// parse the URL to get the values
	if (GetValue(pURL, "query=", Value))
	{
		SetQuery(Value);
	}

	if (GetValue(pURL, "srchdesc=", Value))
	{
		SetSearchDesc(Value);
	}

	if (GetValue(pURL, "minPrice=", Value))
	{
		SetMinPrice(Value);
	}

	if (GetValue(pURL, "maxPrice=", Value))
	{
		SetMaxPrice(Value);
	}
}

//
// retreive the value for the name in the URL
//
bool clsPSSearch::GetValue(const char* pURL, const char* pName, char* pValue)
{
	char*	p1;
	char*	p2;

	// sanity check
	if (!pName || !pValue)
		return false;

	// looking for the name
	p1 = strstr(pURL, pName);
	if (p1)
	{
		// Yep, found it
		// looking for separator '&'
		p1 += strlen(pName);
		p2 = strchr(p1, '&');
		if (p2)
		{
			// found '&'
			strncpy(pValue, p1, p2 - p1);
			pValue[p2-p1] = '\0';
		}
		else
		{
			// no '&'
			strcpy(pValue, p1);
		}
		return true;
	}

	// zero length string
	*pValue = '0';

	return false;
}
