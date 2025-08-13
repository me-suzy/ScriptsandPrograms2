/*	$Id: clsDeadbeatBatchEmailApp.h,v 1.2 1999/03/22 00:09:23 josh Exp $	*/
//
//	File:	clsDeadbeatBatchEmailApp.h
//
//	Class:	clsDeadbeatBatchEmailApp
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//		Generates and sends warning emails to deadbeat high bidders
//
//		*** NOTE ***
//		If we ever do e-Boxes, this would be the place
//		to invoke clsMail and "do the right thing" for
//		each report
//		*** NOTE ***
//
// Modifications:
//				- 03/04/99 mila		- Created
//
#ifndef CLSDEADBEATBATCHEMAILAPP_INCLUDED

#include "clsApp.h"
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
class clsDeadbeatItem;
class clsUsers;

class clsDeadbeatBatchEmailApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsDeadbeatBatchEmailApp();
		~clsDeadbeatBatchEmailApp();
		
		// Runner
		void Run();

	private:
		bool EmitDeadbeatEmail(clsDeadbeatItem *pItem,
							   FILE *pDeadbeatEmailLog);

		void SuspendUserViaENotes(char *pUserId,
								  char *pPass,
								  char *pTarget,
								  int type,
								  char *pText,
								  char *pEmailSubject,
								  char *pEmailText);

		clsDatabase			*mpDatabase;
		clsMarketPlaces		*mpMarketPlaces;
		clsMarketPlace		*mpMarketPlace;
		clsUsers			*mpUsers;

		strstream			*mpStream;

};

extern "C" void make_testapp(unsigned char *pRequest);

#define CLSDEADBEATBATCHEMAILAPP_INCLUDED 1
#endif /* CLSDEADBEATBATCHEMAILAPP_INCLUDED */
