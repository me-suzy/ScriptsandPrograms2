/*	$Id: clsBetaCreditsApp.h,v 1.2 1999/02/21 02:21:08 josh Exp $	*/
//
//	File:	clsBetaCreditsApp.h
//
//	Class:	clsBetaCreditsApp
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
#ifndef CLSBETACREDITSAPP_INCLUDED

#include "clsApp.h"
#include "fstream.h"

// Class forward
class clsDatabase;
class clsMarketPlaces;
class clsMarketPlace;
class clsItems;
class clsUsers;
class clsUserListings;

class clsBetaCreditsApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsBetaCreditsApp(unsigned char *pRequestRec);
		~clsBetaCreditsApp();
		
		// Runner
		void Run();

	private:

		clsDatabase			*mpDatabase;
		clsMarketPlaces		*mpMarketPlaces;
		clsMarketPlace		*mpMarketPlace;
		clsItems			*mpItems;
		clsUsers			*mpUsers;

		ofstream			*mpStream;

};

extern "C" void make_testapp(unsigned char *pRequest);

#define CLSBETACREDITSAPP_INCLUDED 1
#endif /* CLSBETACREDITSAPP_INCLUDED */
