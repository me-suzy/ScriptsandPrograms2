/*	$Id: clseBayAppMyEbayBidder.cpp,v 1.5 1998/09/30 02:56:46 josh Exp $	*/
//
//	File:	clseBayAppMyEbayBidder.cpp
//
//	Class:	clseBayAppMyEbayBidder
//
//	Author:	Alex Poon (poon@ebay.com)
//
//	Function:
//
//		Displays the my ebay bidder detail page
//
// Modifications:
//				- 11/21/97 poon	- Created
//				- 12/03/97 charles added the password encryption
//

#include "ebihdr.h"
#include "clseBayUserBiddingDetailWidget.h"
#include "hash_map.h"



//
// MyEbay
//
void clseBayApp::MyEbayBidder(CEBayISAPIExtension *pThis,
							 char *pUserId,
							 char *pPass,
							 int sort,
							 int daysSince)
{
	clseBayUserBiddingDetailWidget *ubdw;

	SetUp();

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// Title
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	" My eBay Bidding Details Page for "
			  <<	pUserId
			  <<	"</TITLE>"
					"</HEAD>"
			  <<	mpMarketPlace->GetHeader()
			  <<	flush;


	/*
	// TEMPORARILY DISABLE EBAY
	*mpStream <<	"<h1>Sorry!</h1>"
					"My eBay, which is in testing, is currently not available!"
					"<br>"
			  <<	mpMarketPlace->GetFooter();
	return;
	*/

	// The last parameter allows the method to check if the password 
	// is the encrypted one stored in the database
	mpUser = mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream, true, NULL,
													false, false, false, true);
	if (!mpUser)
	{
		*mpStream	<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Get the user and make sure they are legitimate

	// Output bidder list
	ubdw = new clseBayUserBiddingDetailWidget(mpMarketPlace);
	ubdw->SetUser(mpUser);
	ubdw->SetSortCode((ItemListSortEnum)sort);
	ubdw->SetHeaderColor("#CCCCFF");
	ubdw->SetCellSpacing(10);
	ubdw->SetDaysSince(daysSince);
	ubdw->SetIncremental(true);
	ubdw->EmitHTML(mpStream);
	delete ubdw;

	CleanUp();

	*mpStream	<<	mpMarketPlace->GetFooter();

	return;
}

