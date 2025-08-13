/*	$Id: clseBayAppUserItemVerification.cpp,v 1.2.348.1.26.1 1999/08/01 03:01:32 barry Exp $	*/
//
//	File:		clseBayApp.cc
//
//	Class:		clseBayApp
//
//	Author:		Vicki Shu (vicki@ebay.com)
//
//	Function:
//
//				Display pages that user can request update item
//
//	Modifications:
//				- 10/01/98 vicki	- Created
//				- 07/13/99 bill		- Modified the introduction text
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clseBayApp.h"
#include "clsEnvironment.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsUsers.h"
#include "clsUser.h"


void clseBayApp::UserItemVerification(CEBayISAPIExtension *pServer,
									  char *pItemNo)
{
	SetUp();

	// Heading, etc
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Request Update Item Info"
					"</title>"
					"</head>"
			  << mpMarketPlace->GetHeader()
			  << "\n"
			  << flush;

	// header
	*mpStream	<<	"<h2>Update Item Information</h2>\n";

	*mpStream	<<	"<font size=\"3\">\n"
				<<	"Change your mind? Or just not getting the number of bids you want? No problem. "
				<<	"<b>If your item has not received any bids, it's easy to: </b><br>\n"
				<<	"</font>\n";

	*mpStream	<<	"<UL>\n"
				<<	"<LI><font size=\"3\"> Change item titles </font>\n"
				<<  "<LI><font size=\"3\"> Modify descriptions </font>\n"
				<<	"<LI><font size=\"3\"> Add or change images </font>\n"
				<<	"<LI><font size=\"3\"> Update payment options </font>\n"
				<<	"<LI><font size=\"3\"> Change shipping terms </font>\n"
				<<	"</UL>\n";

	*mpStream	<<	"<font size=\"3\">\n"
				<<	"When you revise an item, a message on the item view page will note "
				<<	"that it has been changed. To make sure nobody else changes your listing, "
				<<	"please verify your user information.<br>\n"
				<<	"</font><br>\n";


	*mpStream	<< "<font size=\"3\">\n";

	// form
	*mpStream	<<	"<form method=\"POST\" action=\""
				<<	mpMarketPlace->GetCGIPath(PageGetItemInfo)
				<<	"eBayISAPI.dll\">\n"
					"<input TYPE=HIDDEN NAME=\"MfcISAPICommand\" "
					"VALUE=\"GetItemInfo\">\n";

	*mpStream	<<	"<table><tr><td>\n"
					"Your Item No: "
					"</td>"
					"<td><input type=\"text\" name=\"item\" size=40 ";

	if (!FIELD_OMITTED(pItemNo))
	{
		*mpStream	<<	"value=\""
					<<	pItemNo
					<<	"\"\n ";
	}

	*mpStream	<<	"</td></tr>\n"
					"<tr><td>\n"
					"Your  "
				<<	mpMarketPlace->GetLoginPrompt()
				<<	": </td>"
				<<  "<td><input type=\"text\" name=\"userid\" size=40 ";
	
	// determine whether there is requested user id
	if (!FIELD_OMITTED(pItemNo))
	{
		// Let's try and get the item
		if (!GetAndCheckItem(pItemNo))
		{
			CleanUp();
			return;
		}	 

		*mpStream	<<	"value=\""
					<<	mpItem->GetSellerUserId()
					<<	"\"\n";
	}

	*mpStream	<<	"></td></tr>\n"
					"<tr><td>Your "
				<<	mpMarketPlace->GetPasswordPrompt()
				<<	":</td>\n"
					"<td><input type=\"password\" name=\"pass\" size=40></td></tr>\n";

	// close the table
	*mpStream	<<	"</table>\n";


	// add submit button and finish the form
	*mpStream	<<	"<p><input type=\"submit\" value=\"Submit\"></p>\n"
					"</form>\n";


	// the footer
	*mpStream << mpMarketPlace->GetFooter()
				 << flush;

	CleanUp();
	return;
}

