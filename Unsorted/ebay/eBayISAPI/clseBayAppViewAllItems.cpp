/*	$Id: clseBayAppViewAllItems.cpp,v 1.4.554.2 1999/08/05 20:42:23 nsacco Exp $	*/
//
//	File:	clseBayAppViewAllItems.cc
//
//	Class:	clseBayApp
//
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Contains the methods user to retrieve
//		and show all items listed by a user.
//
// Modifications:
//				- 02/06/97 michael	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include "hash_map.h"



//
// ViewListedItems
//
void clseBayApp::ViewAllItems(CEBayISAPIExtension *pThis,
								 char *pUserId,
								 bool completed,
								 ItemListSortEnum sort,
								 int daysSince)
{
	SetUp();


	// Title
	*mpStream <<	"<html><head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Seller and Bidder List: "
			  <<	pUserId
			  <<	"</title>"
					"</head>"
			  <<	mpMarketPlace->GetHeader();

	mpUser = mpUsers->GetAndCheckUser(pUserId, mpStream);
	if (!mpUser)
	{
		*mpStream <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		return;
	}

	if (!completed)
		daysSince	= -1;

	GetAndShowListedItems(mpUser,
						  daysSince,
						  sort,
						  "ViewAllItems");

	*mpStream <<	"<p>";

	GetAndShowBidItems(mpUser,
					   completed,
					   sort,
					   true,
					   "ViewAllItems");


	*mpStream <<	"<p>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;
}


