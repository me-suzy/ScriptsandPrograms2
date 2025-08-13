/*	$Id: clsAnnouncement.cpp,v 1.5.550.1 1999/08/01 03:02:16 barry Exp $	*/
//
//	File:	clsAnnouncement.cpp
//
//	Class:	clsAnnouncement
//
//	Author:	tini (tini@ebay.com)
//
//	Function:
//
//				Represents an announcement
//
// Modifications:
//				- 10/24/97 tini	- Created
//
#include "eBayKernel.h"
#include "clsAnnouncements.h"
#include "clseBayTimeWidget.h"			// petra

#define ANSTRING_METHODS(variable)				\
char *clsAnnouncement::Get##variable()			\
{												\
	return mp##variable;						\
}												\
void clsAnnouncement::Set##variable(char *pNew)	\
{												\
	delete[] mp##variable;						\
	mp##variable = new char[strlen(pNew) + 1];	\
	strcpy(mp##variable, pNew);					\
	return;										\
}

#define ANINT_METHODS(variable)					\
int clsAnnouncement::Get##variable()			\
{												\
	return m##variable;							\
}												\
void clsAnnouncement::Set##variable(int newval)	\
{												\
	m##variable	= newval;						\
	return;										\
}

#define ANLONG_METHODS(variable)				\
long clsAnnouncement::Get##variable()			\
{												\
	return m##variable;							\
}												\
void clsAnnouncement::Set##variable(long newval) \
{												\
	m##variable	= newval;						\
	return;										\
}

//
// Get Announcement
//
clsAnnouncement::clsAnnouncement(MarketPlaceId marketplace, int id, 
							 int where, long moddate, char *pType, char *pDesc,
							 int partnerId, int siteId)
{
	mMarketPlaceId = marketplace;
	mId = id;
	mLocation = where;
	mLastModified = moddate;
	mpCode = new char[strlen(pType) + 1];
	strcpy(mpCode, pType);
	mpDesc = new char[strlen(pDesc) + 1];
	strcpy(mpDesc, pDesc);
	mPartnerId = partnerId;
	mSiteId = siteId;
	return;

};

clsAnnouncement::~clsAnnouncement()
{
	delete []	mpDesc;
	mpDesc	= NULL;
	delete []	mpCode;
	mpCode = NULL;
	return;
};

char *clsAnnouncement::GetModDateAsString()
{
// petra	struct tm	*pTheTime;
	char		cDate[16];
	char		cTime[16];
	char		*cDateTime;

// petra	pTheTime	= localtime(&mLastModified);
// petra	strftime(cDate, sizeof(cDate), "%m/%d/%y", pTheTime);
// petra	strftime(cTime, sizeof(cTime), "%H:%M:%S PDT", pTheTime);
	// petra: ooooohhhhh.. and who deletes the memory??
	cDateTime = new char[strlen(cDate) + strlen(cTime)];

// petra	strcpy(cDateTime, cDate);
// petra	strcat(cDateTime, " ");
// petra	strcat(cDateTime, cTime);

	clseBayTimeWidget timeWidget (gApp->GetMarketPlaces()->GetCurrentMarketPlace(), // petra
									1, 2, mLastModified);	// petra
	timeWidget.EmitString (cDateTime);	// petra

	return cDateTime;
};

ANINT_METHODS(MarketPlaceId);
ANINT_METHODS(Id);
ANINT_METHODS(Location);
ANLONG_METHODS(LastModified);
ANSTRING_METHODS(Code);
ANSTRING_METHODS(Desc);
ANINT_METHODS(SiteId);
ANINT_METHODS(PartnerId);



