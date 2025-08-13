/*	$Id: clseBayAppAdminInvalidateList.cpp,v 1.3.706.1 1999/08/01 02:51:46 barry Exp $	*/
//
//	File:	clseBayAppAdminInvalidateList.cc
//
//	Class:	clseBayApp
//
//
//	Function:
//
//	This function just invalidates a user's seller or bidder list
//
// Modifications:
//				- 02/06/97 michael	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include "eBayDebug.h"
#include "clseBayApp.h"
#include "clsEnvironment.h"
#include "clsDatabase.h"

//
// Run
//
// Just invalidate the seller or bidder list; 
// code = 0 means seller;
// code = 1 means bidder;
//
void clseBayApp::AdminInvalidateList(CEBayISAPIExtension *pThis,
								char *pUserId, int code)
{
	// Duh.
	SetUp();

	// Blah
	*mpStream <<	"<head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Invalidating Seller/Bidder List for "
			  <<	pUserId
			  <<	"</title>"
					"</head>"
			  <<	mpMarketPlace->GetHeader();


	// Get the user
	mpUser = mpUsers->GetAndCheckUser(pUserId, mpStream);
	if (!mpUser)
	{
		return;
	}

	// reset the list
	if (code == 0)		// seller list
	{
		mpUser->ResetSellerList();

		*mpStream <<	"<br>"
						"Seller list for "
				  <<	pUserId
				  <<	" is reset. ";
		}
	else if (code == 1)		// bidder list
	{
		mpUser->ResetBidderList();

		*mpStream <<	"<br>"
						"Bidder list for "
				  <<	pUserId
				  <<	" is reset. ";
		}
	else
		*mpStream << "<br>Unknown code. No list is reset. ";

	*mpStream <<	"<br>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;
}

