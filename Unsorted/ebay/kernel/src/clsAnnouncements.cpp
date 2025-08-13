/*	$Id: clsAnnouncements.cpp,v 1.5.550.1 1999/08/01 03:02:16 barry Exp $	*/
//
//	File:	clsAnnouncements.cpp
//
//	Class:	clsAnnouncements
//
//	Author:	tini (tini@ebay.com)
//
//	Function:
//
//				Represents announcements
//
// Modifications:
//				- 10/24/97 tini	- Created
//
#include "eBayKernel.h"
#include "clsAnnouncements.h"

//
// Get Announcement
//
clsAnnouncements::clsAnnouncements(clsMarketPlace *pMarketplace)
{
	mpMarketPlace = pMarketplace;

};

//
// Destructor
//
clsAnnouncements::~clsAnnouncements()
{
	mpMarketPlace = NULL;
}

clsAnnouncement *clsAnnouncements::GetAnnouncement(AnnouncementEnum aType, 
												   AnnounceLocEnum aLoc,
												   int PartnerId,
												   int SiteId)
{
	return 
		gApp->GetDatabase()->GetAnnouncement(mpMarketPlace->GetId(), 
						aType, aLoc, 
						PartnerId,
						SiteId);

};


// replaces the announcements in the database
bool clsAnnouncements::UpdateAnnouncement(clsAnnouncement *pAnnounce)
{
	return gApp->GetDatabase()->UpdateAnnouncement(pAnnounce);
	
};

// adds new announcement to the database
// replaces the announcements in the database
bool clsAnnouncements::AddAnnouncement(AnnouncementEnum aType, 
								AnnounceLocEnum aLoc, char *pCode, char *pDesc,
								int PartnerId, int SiteId)
{
	return gApp->GetDatabase()->AddAnnouncement(mpMarketPlace->GetId(), 
			aType, aLoc, pCode, pDesc, PartnerId, SiteId);
	
};

void clsAnnouncements::GetAllAnnouncementsBySiteAndPartner(AnnouncementVector *pvAnnouncements, int SiteId, int PartnerId)
{
	gApp->GetDatabase()->GetAllAnnouncementsBySiteAndPartner(mpMarketPlace->GetId(), pvAnnouncements, SiteId, PartnerId);
	return;
};
