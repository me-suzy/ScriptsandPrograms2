/*	$Id: clsAnnouncements.h,v 1.3.518.1.66.1 1999/08/01 03:02:04 barry Exp $	*/
//
//	File:	clsAnnouncement.h
//
//	Class:	clsAnnouncement
//
//	Author:	tini (tini@ebay.com)
//
//	Function:
//
//		Represents a collection of announcements
//
// Modifications:
//				- 04/02/97 michael	- Created
//
#ifndef CLSANNOUNCEMENTS_INCLUDED

#include "eBayTypes.h"
#include "clsAnnouncement.h"
#include "vector.h"

typedef enum
{
	General				= 0,
	EndOfAuction		= 1,
	DailyStatus			= 2,
	BidNotice			= 3,
	OutBidNotice		= 4,
	ListNotice			= 5,
	ChgEmail			= 6,
	Registrn			= 7,
	PasswordReq			= 8,
	UserInfoReq			= 9,
	InvoiceAnn			= 10,
	ChgUserId			=11,
	HeaderWidget		=12,
	CCReject			=13,
	BulkUpLoad			=14,
	InactiveUsersEmail  =15,
	HeaderWidgetPrefix	=16,
	HeaderWidgetSuffix	=17
} AnnouncementEnum;
//inna added 13

typedef enum
{
	Header				= 1,
	Footer				= 2
} AnnounceLocEnum;

class clsAnnouncements
{
	public:

	clsAnnouncements(clsMarketPlace *marketplace);
	~clsAnnouncements();

	// retrieves the various header or trailer announcements
	// from the database
	clsAnnouncement *GetAnnouncement(AnnouncementEnum aType, 
				AnnounceLocEnum aLoc,
				int PartnerId, int SiteId);

	// replaces the announcements in the database
	bool UpdateAnnouncement(clsAnnouncement *pAnnounce);

	// adds a new announcement with new type
	bool AddAnnouncement(AnnouncementEnum aType, 
				AnnounceLocEnum aLoc, char *pCode, char *pDesc,
				int PartnerId, int SiteId);

	void GetAllAnnouncementsBySiteAndPartner(AnnouncementVector *pvAnnouncements, int SiteId, int PartnerId);


private:

	//
	// Parent MarketPlace
	//
	clsMarketPlace	*mpMarketPlace;

};

#define CLSANNOUNCEMENTS_INCLUDED
#endif /* CLSANNOUNCEMENTS_INCLUDED */
