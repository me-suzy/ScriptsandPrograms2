/*	$Id: clsComputePastARApp.h,v 1.2 1999/02/21 02:21:35 josh Exp $	*/

//
//	File:	clsComputePastARApp.h
//
//	Class:	clsComputePastARApp.h
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
#ifndef CLSCOMPUTEPASTARAPP_INC

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


class clsComputePastARApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsComputePastARApp(unsigned char *pRequestRec);
		~clsComputePastARApp();
		
		// Runner
		void Run();

	private:

		clsDatabase			*mpDatabase;
		clsMarketPlaces		*mpMarketPlaces;
		clsMarketPlace		*mpMarketPlace;
		clsItems			*mpItems;
		clsUsers			*mpUsers;

		strstream			*mpStream;

};

extern "C" void make_testapp(unsigned char *pRequest);

#define CLSCOMPUTEPASTARAPP_INC 1
#endif /* CLSCOMPUTEPASTARAPP_INC */
