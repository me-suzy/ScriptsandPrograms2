/*	$Id: clsCalculatePastDueApp.h,v 1.2 1999/02/21 02:21:21 josh Exp $	*/

//
//	File:	clsCalculatePastDueApp.h
//
//	Class:	clsCalculatePastDueApp
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
#ifndef CLSCALCULATEPATHDUEAPP_INCLUDED

#include "clsApp.h"
#include "fstream.h"

// Class forward
class clsDatabase;
class clsMarketPlaces;
class clsMarketPlace;
class clsItems;
class clsUsers;


class clsCalculatePastDueApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsCalculatePastDueApp(unsigned char *pRequestRec);
		~clsCalculatePastDueApp();
		
		// Runner
		void Run(char *pUserId);

	private:

		clsDatabase			*mpDatabase;
		clsMarketPlaces		*mpMarketPlaces;
		clsMarketPlace		*mpMarketPlace;
		clsItems			*mpItems;
		clsUsers			*mpUsers;

		ofstream			*mpStream;

};

extern "C" void make_testapp(unsigned char *pRequest);

#define CLSCALCULATEPATHDUEAPP_INCLUDED 1
#endif /* CLSCALCULATEPATHDUEAPP_INCLUDED */
