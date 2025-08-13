/*	$Id: clsEmitCreditCardChargesApp.h,v 1.3 1999/02/21 02:21:40 josh Exp $	*/
//
//	File:	clsEmitCreditCardCharges.h
//
//	Class:	clsEmitCreditCardCharges
//
//	Author:	Michael Wilson (michael@ebay.com)
//	
//  for online CC billing 
//	Function:
//
//	Generates the "ICVerify" input for the monthly
//	invoiced credit card charges. 
//
// Modifications:
//				- 02/06/97 michael	- Created
//				- 06/29/98 lena - updated to enqueue the 
//									entries to Authorization table
#ifndef CLSEMITCREDITCARDCHARGES_INC

#include "clsApp.h"
#ifdef _MSC_VER
#include "strstrea.h"
#else
#include "strstream.h"
#endif
#include "clsAuthorizationQueue.h"

// Class forward
class clsDatabase;
class clsMarketPlaces;
class clsMarketPlace;
class clsItems;
class clsUsers;
class clsUser;


class clsEmitCreditCardCharges : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsEmitCreditCardCharges(unsigned char *pRequestRec);
		~clsEmitCreditCardCharges();
		// to put the stuff into Authorization table for online cc billing
		void Enqueue( clsUser *pUser, float amount );
		
		// Runner
//		void Run();
		void Run(int userStart, int userEnd, vector<unsigned int> &requestedUsers);

	private:

		clsDatabase			*mpDatabase;
		clsMarketPlaces		*mpMarketPlaces;
		clsMarketPlace		*mpMarketPlace;
		clsUsers			*mpUsers;
		clsAuthorizationQueue mQueue;
};

extern "C" void make_testapp(unsigned char *pRequest);

#define CLSEMITCREDITCARDCHARGES_INC 1
#endif /* CLSEMITCREDITCARDCHARGES_INC */
