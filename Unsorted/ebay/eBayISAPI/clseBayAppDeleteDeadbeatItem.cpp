/*	$Id: clseBayAppDeleteDeadbeatItem.cpp,v 1.3.138.6.66.2 1999/08/05 20:42:13 nsacco Exp $	*/
//
//	File:	clseBayAppDeleteDeadbeat.cpp
//
//	Class:	clseBayApp
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//		Contains the methods used to delete a deadbeat
//		user transaction from the database.
//
// Modifications:
//				- 09/23/98 mila		- Created
//				- 12/16/98 mila		- Deleted admin user ID, password, and
//									  authorization level parameters since
//									  this functionality will be accessible
//									  only from support page, which is already
//									  protected.
//				- 05/25/99 mila		- Reinstate supended users automatically if
//									  deadbeat score after deletion is greater than
//									  indicated suspension level
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"

#include "clsDeadbeat.h"
#include "clsDeadbeatItem.h"

// *** NOTE ***
// Only used for interim logging
// *** NOTE ***
#include <stdio.h>
#include <errno.h>

static const int kTempSuspendLevel = -3;

static const char *ReinstatementNoteText = 
"Transaction backout score above -3";

//
// DeleteDeadbeat
//
void clseBayApp::DeleteDeadbeatItem(CEBayISAPIExtension *pThis,
									char *pSellerUserId,
									char *pBidderUserId,
									int itemNumber,
									int confirm)
{
	clsUser			*pSeller;
	clsUser			*pBidder;

	clsDeadbeat		*pSellerDeadbeat;
	clsDeadbeat		*pBidderDeadbeat;
	clsDeadbeatItem	*pItem;

	int				deadbeatScore;

	clsNotes		*pNotes;
	const char		*pEmailSubject;
	const char		*pEmailText;


	SetUp();

	// Title
	*mpStream <<	"<html><head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Delete Deadbeat Transaction for "
			  <<	pBidderUserId
			  <<	"</title>"
					"</head>"
			  <<	mpMarketPlace->GetHeader();

	// Make sure the seller is legit.
	clsUtilities::StringLower(pSellerUserId);
	pSeller = mpUsers->GetUser(pSellerUserId);

	if (!pSeller)
	{
		*mpStream <<	"\n"
						"<h2>Target User not found</h2>"
						"Sorry, "
				  <<	"<font color=\"green\">"
				  <<	pSellerUserId
				  <<	"</font>"
				  <<	" is not registered user. "
						"Please go back and try again. "
						"\n"
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Make sure the bidder is legit.
	clsUtilities::StringLower(pBidderUserId);
	pBidder = mpUsers->GetUser(pBidderUserId);

	if (!pBidder)
	{
		*mpStream <<	"\n"
						"<h2>Target User not found</h2>"
						"Sorry, "
				  <<	"<font color=\"green\">"
				  <<	pBidderUserId
				  <<	"</font>"
				  <<	" is not registered user. "
						"Please go back and try again. "
						"\n"
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter();
		delete pSeller;
		CleanUp();
		return;
	}

	// Make sure the transaction's deadbeat status is legit.
	pItem = new clsDeadbeatItem(pSeller->GetId(), pBidder->GetId(), itemNumber);
	if (!pItem->IsDeadbeat())
	{
		*mpStream <<	"\n"
						"<h2>Invalid transaction</h2>"
						"Sorry, the item number you entered, "
				  <<	"<font color=\"green\">"
				  <<	itemNumber
				  <<	"</font>"
				  <<	", either does not exist or does not apply to a "
				  <<	"deadbeat transaction between seller "
				  <<	"<font color=\"green\">"
				  <<	pSellerUserId
				  <<	"</font>"
				  <<	" and high bidder "
				  <<	"<font color=\"green\">"
				  <<	pBidderUserId
				  <<	"</font>"
				  <<	". Please go back and try again."
						"\n"
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter();
		delete pBidder;
		delete pSeller;
		delete pItem;
		CleanUp();
		return;
	}

	// Confirm the deletion, if not done already.
	if (confirm == 0)
	{
		DeleteDeadbeatItemConfirm(pSellerUserId,
								  pBidderUserId,
								  itemNumber);
		delete pBidder;
		delete pSeller;
		delete pItem;
		CleanUp();
		return;
	}

	// Get the bidder's deadbeat info from the database.
	pBidderDeadbeat = pBidder->GetDeadbeat();
	if (pBidderDeadbeat == NULL)
	{
		*mpStream <<	"\n"
						"<h2>Target User Not a Non-Paying Bidder</h2>"
						"Sorry, "
				  <<	"<font color=\"green\">"
				  <<	pBidderUserId
				  <<	"</font>"
				  <<	" is not a non-paying bidder. "
						"Please go back and try again. "
						"\n"
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter();
		delete pBidder;
		delete pSeller;
		delete pItem;
		CleanUp();
		return;
	}

	// Get the seller's deadbeat info from the database.
	pSellerDeadbeat = pSeller->GetDeadbeat();
	if (pSellerDeadbeat == NULL)
	{
		*mpStream <<	"\n"
						"<h2>No Credit Requests from Seller</h2>"
						"Sorry, "
				  <<	"<font color=\"green\">"
				  <<	pSellerUserId
				  <<	"</font>"
				  <<	" has not submitted any credit requests. "
						"Please go back and try again. "
						"\n"
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter();
		delete pBidder;
		delete pSeller;
		delete pItem;
		CleanUp();
		return;
	}

	// Delete the deadbeat transaction from the database.
	pBidderDeadbeat->DeleteDeadbeatItem(itemNumber,
									    pSeller->GetId(),
									    pBidder->GetId());

	// Invalidate the seller's credit request count and
	// the bidder's deadbeat count.
	pSellerDeadbeat->InvalidateCreditRequestCount();
	pBidderDeadbeat->InvalidateDeadbeatScore();
	pBidderDeadbeat->InvalidateWarningCount();

	// Get the user's new and improved deadbeat score.
	deadbeatScore = pBidderDeadbeat->GetDeadbeatScore();

	// Now, announce that we're done
	*mpStream <<	"<p>"
			  <<	"Thank you. Deadbeat transaction "
			  <<	itemNumber
			  <<	" has been deleted, and "
			  <<	"<font color=\"green\">"
			  <<	pBidderUserId
			  <<	"</font>"
			  <<	"'s Deadbeat Score is now "
			  <<	deadbeatScore
			  <<	".";

	// If the user is suspended and we just deleted their
	// third infraction, then we need to automatically reinstate
	// the user.
	// NOTE:  automatic reinstatement was requested by support.
	if (pBidder->IsSuspended() && (deadbeatScore == kTempSuspendLevel + 1))	// Both negative #'s ((-2) == (-3 + 1))
	{
		pEmailSubject = GetEmailSubjectForNoteType(eNoteTypeReinstatement);
		pEmailText = GetEmailTemplateForNoteType(eNoteTypeReinstatement);

		// if the email subject and/or text are not valid, then give them a
		// link to reinstate the user; otherwise, do the reinstatement
		// automatically
		if (pEmailSubject == NULL || pEmailText == NULL)
		{
			*mpStream <<	"<p>"
					  <<	"An error occurred getting the email template information "
					  <<	"for the bidder reinstatement email."
					  <<	"<p>"
					  <<	"Click "
					  <<	"<a href=\""
					  <<	mpMarketPlace->GetAdminPath()
					  <<	"eBayISAPI.dll?AdminReinstateUserShow"
					  <<	"&target="
					  <<	pBidderUserId
					  <<	"\">"
					  <<	"here"
					  <<	"</a>"
					  <<	" to reinstate "
					  <<	"<font color=\"green\">"
					  <<	pBidderUserId
					  <<	"</font>";
		}
		else
		{
			*mpStream <<	"<p>";

			pNotes = mpMarketPlace->GetNotes();

			if (pNotes != NULL)
			{
				AdminReinstateUserInternal(pNotes->GetSupportUser()->GetUserId(),
										   (char *)mpMarketPlace->GetSpecialPassword(),
										   pBidderUserId,
										   eNoteTypeReinstatement,
										   (char *)ReinstatementNoteText,
										   (char *)pEmailSubject,
										   (char *)pEmailText,
										   eBayISAPIAuthSupport);
			}
		}
	}

	*mpStream <<	"<p>"
			  <<	mpMarketPlace->GetFooter();

	delete pSeller;
	delete pBidder;
	delete pItem;

	CleanUp();

	return;
}

//
// DeleteDeadbeatConfirm
//
void clseBayApp::DeleteDeadbeatItemConfirm(char *pSellerUserId,
										   char *pBidderUserId,
										   int itemNumber)
{
	SetUp();

	// Create form to submit.
	*mpStream <<	"\n"
					"<form method=post action="
					"\""
			  <<	mpMarketPlace->GetAdminPath()
			  <<	"eBayISAPI.dll"
					"\""
					">"
					"<INPUT TYPE=HIDDEN "
					"NAME=\"MfcISAPICommand\" "
					"VALUE=\"DeleteDeadbeatItem\">"
					"\n"
					"<input type=hidden name=selleruserid value=\""
			  <<	pSellerUserId
			  <<	"\">"
					"\n"
					"<input type=hidden name=bidderuserid value=\""
			  <<	pBidderUserId
			  <<	"\">"
					"\n"
					"<input type=hidden name=itemno value=\""
			  <<	itemNumber
			  <<	"\">"
					"\n"
					"<input type=hidden name=confirm value=1>"
					"\n"
					"<font color=\"#FF0033\"><b><font size=+1><font size=+1>"
					"WARNING!</font></font></b></font>\n"
					"<p>\n"
					"You are about to delete a deadbeat transaction. "
					"Once deleted, this transaction <b>CANNOT BE RECOVERED</b>.\n"
					"<p>\n";

	*mpStream <<	"\n"
					"Here is the transaction you are about to delete:<p>\n";

	// Display table column headings.
	*mpStream <<	"<table border=1>\n"
					"  <tr>\n"
					"    <th>Item</th>\n"
					"    <th>Seller</th>\n"
					"    <th>High Bidder</th>\n"
					"  </tr>"
					"\n";

	// Display transaction information.
	*mpStream <<	"  <tr>\n"
					"    <td>"
			  <<	itemNumber
			  <<	"    </td>\n"
					"    <td>"
			  <<	pSellerUserId
			  <<	"    </td>\n"
					"    <td>"
			  <<	pBidderUserId
			  <<	"    </td>\n"
					"  </tr>"
					"\n";
					
	// End table.
	*mpStream <<	"</table>"
			  <<	"\n";

	// Ask user to confirm.
	*mpStream <<	"<p>Press the confirm button to delete the transaction "
					"and update "
					"<font color=\"green\">"
			  <<	pBidderUserId
			  <<	"</font>"
					"\'s deadbeat score."
					"<p>\n"
					"<input type=submit value=confirm>\n"
					"</form>\n";

	*mpStream <<	"<p>"
			  <<	mpMarketPlace->GetFooter();

	return;
}