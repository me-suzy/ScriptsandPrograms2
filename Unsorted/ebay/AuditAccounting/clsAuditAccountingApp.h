/*	$Id: clsAuditAccountingApp.h,v 1.2 1999/02/21 02:20:58 josh Exp $	*/
//
//	File:	clsAuditAccountingApp.h
//
// Class:	clsAuditAccountingApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 02/06/97 michael	- Created
//
#ifndef CLSAUDITACCOUNTINGAPP_INCLUDED

#include "clsApp.h"

// Class forward
class clsDatabase;
class clsMarketPlaces;
class clsMarketPlace;
class clsUsers;

class clsAuditAccountingApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsAuditAccountingApp(unsigned char *pRequestRec);
		~clsAuditAccountingApp();
		
		// Runner
		void Run(int BatchId);

	private:
		clsDatabase			*mpDatabase;
		clsMarketPlaces		*mpMarketPlaces;
		clsMarketPlace		*mpMarketPlace;
		clsUsers			*mpUsers;

};

extern "C" void make_testapp(unsigned char *pRequest);

#define CLSAUDITACCOUNTINGAPP_INCLUDED 1
#endif /* CLSAUDITACCOUNTINGAPP_INCLUDED */
