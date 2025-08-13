/*	$Id: clsEndOfAuctionNoticeApp.h,v 1.5 1999/04/07 05:42:15 josh Exp $	*/
//
//	File:	clsEndOfAuctionNoticeApp.h
//
//	Class:	clsEndOfAuctionNoticeApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Generates End of auction notices
//
//		*** NOTE ***
//		If we ever do e-Boxes, this would be the place
//		to invoke clsMail and "do the right thing" for
//		each report
//		*** NOTE ***
//
// Modifications:
//				- 02/06/97 michael	- Created
//
#ifndef CLSENDOFAUCTIONNOTICEAPP_INCLUDED

#include "clsApp.h"
// #include "fstream.h"
#include <stdio.h>
#include "clsApp.h"
#ifdef _MSC_VER
#include "strstrea.h"
#else
#include "strstream.h"
#endif

// Class forward
class clsDatabase;
class clsMarketPlaces;
class clsMarketPlace;
class clsItems;
class clsItem;
class clsUsers;
class clsAnnouncements;
class clsAnnouncement;

class clsEndOfAuctionNoticeApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsEndOfAuctionNoticeApp();
		~clsEndOfAuctionNoticeApp();
		
		// Runner
		void Run(time_t today, time_t StartTime, time_t EndTime);

	private:
		void EmitEndOfAuctionNotice(clsItem *pItem,
						FILE			*pDailyStatusLog);
		void EmitItemText(ostrstream *pM, clsItem *pItem, int pass = 0);
		void EmitItemBlurb(ostrstream *pM, clsItem *pItem);
		void EmitBillNotice(clsItem *pItem,
						FILE			*pDailyStatusLog,
						FILE			*pWackoItemsLog);

		clsDatabase			*mpDatabase;
		clsMarketPlaces		*mpMarketPlaces;
		clsMarketPlace		*mpMarketPlace;
		clsItems			*mpItems;
		clsUsers			*mpUsers;
		clsAnnouncements	*mpAnnouncements;

//		ofstream			*mpStream;
		strstream			*mpStream;

};

extern "C" void make_testapp(unsigned char *pRequest);

#define CLSENDOFAUCTIONNOTICEAPP_INCLUDED 1
#endif /* CLSENDOFAUCTIONNOTICEAPP_INCLUDED */
