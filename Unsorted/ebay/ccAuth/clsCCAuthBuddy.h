/*	$Id: clsCCAuthBuddy.h,v 1.3 1999/02/21 02:30:41 josh Exp $	*/
//
//	File:	clsCCAuthBuddy.h
//
//	Class:	clsCCAuthBuddy
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
#ifndef clsCCAuthBuddy_INCLUDED
#define clsCCAuthBuddy_INCLUDED

#include "clsApp.h"
#include "clsAuthorizationQueue.h"
#include "clsUser.h"
#include "clsMarketPlace.h"
#include "clsMarketPlaces.h"

// Email subject defines
#define	 SubFDMSApprovedCC					"eBay Credit Card Approval"
#define	 SubFDMSErrorCC						"eBay Credit Card Authorization"

// consts

class clsCCAuthBuddy
{
	public:

	// CTOR
	clsCCAuthBuddy();

	// DTOR
	~clsCCAuthBuddy();

	// Use internal vector to check on the status of the items that were sent
	// for Authorization
	int  ProcessEnqueuedRequests();
	void SendAuthorizationEmail(int CC4Id, int FDMSResp, 
								clsAuthorizationQueue *pAuthEntry);
	void CheckAndAlertAdmin(clsAuthorizationQueue *pAuthEntry, int CC4Id);
	void ProcessUserForCCAuthorization (clsAuthorizationQueue *pAuthEntry);

	private:
		clsAuthorizationQueue	mAuthQueue;
		clsApp					mApp;
		clsMarketPlace			*pMarketPlace;
		clsMarketPlaces			*pMarketPlaces;
};


#endif // clsCCAuthBuddy_INCLUDED
