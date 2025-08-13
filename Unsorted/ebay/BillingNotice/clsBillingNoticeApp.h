/*	$Id: clsBillingNoticeApp.h,v 1.3 1999/02/21 02:21:14 josh Exp $	*/
//
//	File:	clsBillingNoticeApp.h
//
//	Class:	clsBillingNoticeApp
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
#ifndef CLSBILLINGNOTICEAPP_INCLUDED

#include "clsApp.h"
#include "fstream.h"
#include <stdio.h>

// Class forward
class clsDatabase;
class clsMarketPlaces;
class clsMarketPlace;
class clsItems;
class clsUsers;
class clsUserListings;

class clsBillingNoticeApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsBillingNoticeApp(unsigned char *pRequestRec);
		~clsBillingNoticeApp();
		
		// Runner
		void Run();

	private:

		// 
		// Emit the status for a given user
		//
		void EmitUserStatus(int itemId, FILE	*pWackoItemsLog);

		clsDatabase			*mpDatabase;
		clsMarketPlaces		*mpMarketPlaces;
		clsMarketPlace		*mpMarketPlace;
		clsItems			*mpItems;
		clsUsers			*mpUsers;

		ofstream			*mpStream;

};

extern "C" void make_testapp(unsigned char *pRequest);

#define CLSBILLINGNOTICEAPP_INCLUDED 1
#endif /* CLSBILLINGNOTICEAPP_INCLUDED */
