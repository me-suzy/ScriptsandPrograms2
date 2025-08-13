/*	$Id: clsDailyStatusApp.h,v 1.5 1999/02/21 02:21:38 josh Exp $	*/
//
//	File:	clsDailyStatusApp.h
//
//	Class:	clsDailyStatusApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Generates the input for the "daily status" 
//		mailer app. 
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
#ifndef CLSDAILYSTATUSAPP_INCLUDED

#include "clsApp.h"
//#include "fstream.h"
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
class clsUsers;
class clsUserListAndBid;
class clsItem;
class clsAnnouncements;
class clsAnnouncement;

class clsDailyStatusApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsDailyStatusApp(unsigned char *pRequestRec);
		~clsDailyStatusApp();
		
		// Runner
		void Run();

	private:

		// 
		// Emit the status for a given user
		//
		void EmitUserStatus(clsUserListAndBid *pUserStatus,
			FILE			*pDailyStatusLog, time_t currentTime);
		void EmitItemBlurb(ostrstream *pM, clsItem *pItem);

		clsDatabase			*mpDatabase;
		clsMarketPlaces		*mpMarketPlaces;
		clsMarketPlace		*mpMarketPlace;
		clsItems			*mpItems;
		clsUsers			*mpUsers;
		clsAnnouncements	*mpAnnouncements;

		strstream			*mpStream;

};

extern "C" void make_testapp(unsigned char *pRequest);

#define CLSDAILYSTATUSAPP_INCLUDED 1
#endif /* CLSDAILYSTATUSAPP_INCLUDED */
