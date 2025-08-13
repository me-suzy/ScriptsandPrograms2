/*	$Id: clsCCBatchSettle.h,v 1.3 1999/02/21 02:30:44 josh Exp $	*/
//
//	File:	clsBatchSettle.h
//
//	Class:	clsBatchSettle
//
//	Author:	Sam Paruchuri
//
//	Function:
//			Class that processes results from CC Authorizations requests to FDMS.
//
// Modifications:
//				- 06/17/98	Sam - Created
//
//
#ifndef clsBatchSettle_INCLUDED
#define clsBatchSettle_INCLUDED

#include "clsApp.h"
#include "clsUser.h"
#include "clsMarketPlace.h"
#include "clsMarketPlaces.h"
#include "clsAuthorizationQueue.h"

// consts

class clsBatchSettle
{
	public:

	// CTOR
	clsBatchSettle();

	// DTOR
	~clsBatchSettle();

	void DumpSettlementRecordsToFile();

	void SendMail(char *pTo, char *pFrom, 
				  char *pSubject, char *pBody);


	private:
		clsAuthorizationQueue	mAuthQueue;
		clsApp					mApp;
		clsMarketPlace			*pMarketPlace;
		clsMarketPlaces			*pMarketPlaces;
};


#endif // clsBatchSettle_INCLUDED
