/*	$Id: clseBayAppViewDeadbeatUser.cpp,v 1.3.64.3.106.2 1999/08/05 20:42:24 nsacco Exp $	*/
//
//	File:	clseBayAppViewDeadbeatUser.cpp
//
//	Class:	clseBayApp
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//		Contains the methods used to display
//		a deadbeat user's user id and deadbeat score.
//
// Modifications:
//				- 09/23/98 mila		- Created
//				- 10/02/98 mila		- added user status and table of user's
//									  deadbeat transactions to page; changed
//									  page title; fixed memory leak; formatted
//									  transaction start and end time strings;
//									  prepared output stream to display price
//									  with 2 decimal places; added ViewItem
//									  link to transaction id; replaced seller;
//									  user id in table with seller user id 
//									  widget; for adult items, display special
//									  message instead of title
//				- 12/08/98 mila		- Modified to get clsDeadbeatItem data
//									  from clsDeadbeat rather than directly
//									  from database so as to avoid hash hell;
//									  added anchors to page
//				- 12/15/98 mila		- Changed text color for some stats; changed
//									  code so that text messages, instead of table
//									  headings, are emittedif the seller items or
//									  bidder items vector are zero-length.
//				- 12/16/98 mila		- Deleted admin user ID,password, and
//									  authorization level parameters since this
//									  page is being accessed only from support
//									  page.
//

#include "ebihdr.h"

#include "clsDeadbeatItem.h"
#include "clseBayTimeWidget.h"	// petra

// *** NOTE ***
// Only used for interim logging
// *** NOTE ***
#include <stdio.h>
#include <errno.h>

//
// ViewDeadbeat
//
void clseBayApp::ViewDeadbeatUser(CEBayISAPIExtension *pThis,
								  char *pDeadbeatUserId)
{
	int					deadbeatScore;
	int					creditRequests;

	bool				bHasCookie = false;

	// time
// petra    time_t				startTime;
// petra    time_t				endTime;
// petra    struct tm			*pTheTime;
	
// petra    char				cStartTime[32];
// petra    char				cEndTime[32];

	// user id widget info
	clsUserIdWidget		*pDeadbeatUserIdWidget;
	clsUserIdWidget		*pOtherUserIdWidget;
	clsFeedback			*pFeedback;
	int					feedbackScore;

	// deadbeat info
	clsUser				*pDeadbeatUser;
	clsDeadbeat			*pDeadbeatInfo;
	clsFeedback			*pDeadbeatFeedback;

	// item list and its iterator
	DeadbeatItemVector	*pvItems;
	DeadbeatItemVector::iterator	iItems;

	// seller info
	clsUser				*pSeller;
	char				*pSellerUserId = NULL;

	// bidder info
	clsUser				*pBidder;
	char				*pBidderUserId = NULL;


	SetUp();

    // Prepare the stream
    mpStream->setf(ios::fixed, ios::floatfield);
    mpStream->setf(ios::showpoint, 1);
    mpStream->precision(2);
	
	// Title
	*mpStream <<	"<html><head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Transaction Backout Profile for "
			  <<	pDeadbeatUserId
			  <<	"</title>"
					"</head>"
			  <<	mpMarketPlace->GetHeader();

	// check given user id
	if (pDeadbeatUserId == NULL)
	{
		*mpStream <<	"\n"
						"<h2>Invalid User ID</h2>"
						"Sorry, the user ID is invalid. "
						"Please go back and try again. "
						"\n"
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Now check the person we're looking for.
	// We can't use GetAndCheckUser because
	// the error message doesn't make any sense

	clsUtilities::StringLower(pDeadbeatUserId);
	pDeadbeatUser = mpUsers->GetUser(pDeadbeatUserId);

	if (pDeadbeatUser == NULL)
	{
		*mpStream <<	"\n"
						"<h2>Target User not found</h2>"
						"Sorry, "
				  <<	pDeadbeatUserId
				  <<	", is not a registered user. "
						"Please go back and try again. "
						"\n"
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

#if 0
	// Check their state
	if (!pDeadbeatUser->IsConfirmed() &&
		(pDeadbeatUser->GetUserState() != UserGhost))
	{
		*mpStream <<	"\n"
						"<h2>Not a Registered User</h2>"
						"Sorry, "
				  <<	pDeadbeatUserId
				  <<	", is not currently a registered user. "
						"Please go back and try again. "
						"\n"
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter();
		delete	pDeadbeatUser;
		CleanUp();
		return;
	}
#endif

	// don't delete the clsDeadbeat object 'cuz the clsUser destructor
	// will do it!!  (mila - 12/09/98)
	pDeadbeatInfo = pDeadbeatUser->GetDeadbeat();
	if (pDeadbeatInfo == NULL)
	{
		*mpStream <<	"\n"
						"<h2>User Not in Deadbeat Transaction</h2>"
						"Sorry, "
				  <<	pDeadbeatUserId
				  <<	" is not involved in any deadbeat transactions, "
						"either as a high bidder or a seller."
						"\n"
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter();
		delete	pDeadbeatUser;
		CleanUp();
		return;
	}

	deadbeatScore = pDeadbeatInfo->GetDeadbeatScore();
	creditRequests = pDeadbeatInfo->GetCreditRequestCount();

	// Get the user's feedback
	// DON'T delete the pFeedback object because clsUser will do it
	pDeadbeatFeedback = pDeadbeatUser->GetFeedback();
	if (pDeadbeatFeedback == NULL)
	{
		*mpStream <<	"\n"
						"<h2>Error Getting Feedback</h2>"
						"Sorry, an error occurred while retrieving feedback for "
				  <<	pDeadbeatUserId
				  <<	"."
						"\n"
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter();
		delete	pDeadbeatUser;
		CleanUp();
		return;
	}

	// display the header
	*mpStream <<	"\n"
			  <<	"<h2>"
			  <<	"Transaction Backout Profile for ";

	pDeadbeatUserIdWidget = new clsUserIdWidget(mpMarketPlace, this);
	pOtherUserIdWidget = new clsUserIdWidget(mpMarketPlace, this);

	pDeadbeatUserIdWidget->SetShowFeedback(true);
	pDeadbeatUserIdWidget->SetUserInfo(pDeadbeatUser->GetUserId(), 
								pDeadbeatUser->GetEmail(),
								pDeadbeatUser->GetUserState(),
								mpMarketPlace->UserIdRecentlyChanged(pDeadbeatUser->GetUserIdLastModified()),
								pDeadbeatFeedback->GetScore());
	pDeadbeatUserIdWidget->SetShowUserStatus(false);
	pDeadbeatUserIdWidget->EmitHTML(mpStream);

	*mpStream <<	"</h2>"
			  <<	"\n";

	// Let's put the deadbeat score and user status in a small table.
	*mpStream <<	"<p>\n"
			  <<	"<table border=\"0\">\n"
			  <<	"  <tr>\n"
			  <<	"    <td>"
			  <<	"<b>Credit Requests:</b>"
			  <<	"    </td>\n"
			  <<	"    <td>"
			  <<	creditRequests
			  <<	"    </td>\n"
			  <<	"  </tr>\n";

	*mpStream <<	"  <tr>\n"
			  <<	"    <td>"
			  <<	"<b>Deadbeat Score:</b>"
			  <<	"    </td>\n";

	if (deadbeatScore != 0)
	{
		*mpStream <<	"    <td>"
				  <<	"<font color=\"red\">"
				  <<	deadbeatScore
				  <<	"</font>"
				  <<	"    </td>\n";
	}
	else
	{
		*mpStream <<	"    <td>"
				  <<	deadbeatScore
				  <<	"    </td>\n";
	}

	*mpStream <<	"  </tr>\n";

	*mpStream <<	"  <tr>\n"
			  <<	"    <td>"
			  <<	"<b>Warnings Issued:</b>"
			  <<	"    </td>\n"
			  <<	"    <td>"
			  <<	pDeadbeatInfo->GetWarningCount()
			  <<	"    </td>\n"
			  <<	"  </tr>\n";

	*mpStream <<	"  <tr>\n"
			  <<	"    <td>"
			  <<	"<b>User Status:</b>"
			  <<	"    </td>\n"
			  <<	"    <td>";

	switch (pDeadbeatUser->GetUserState())
	{
		case UserConfirmed:
			*mpStream <<	"Confirmed";
			break;

		case UserUnconfirmed:
			*mpStream <<	"Unconfirmed";
			break;

		case UserSuspended:
			*mpStream <<	"<font color=\"red\">"
					  <<	"Suspended"
					  <<	"</font>";
			break;

		case UserInMaintenance:
			*mpStream <<	"In maintenance";
			break;

		case UserDeleted:
			*mpStream <<	"<font color=\"red\">"
					  <<	"Deleted"
					  <<	"</font>";
			break;

		case UserCCVerify:
			*mpStream <<	"<font color=\"red\">"
					  <<	"NEEDS CREDIT CARD VARIFICATION!"
					  <<	"</font>";
			break;

		default:
			*mpStream <<	"Unknown";
			break;
	}

	*mpStream <<	"    </td>\n"
			  <<	"  </tr>\n"
			  <<	"</table>\n";

	// display the list of credit requests for this user, if any.
	if (creditRequests == 0)
	{
		*mpStream <<	"<p>"
				  <<	"<a name=\"credits\">";
		
		pDeadbeatUserIdWidget->EmitHTML(mpStream);

		*mpStream <<	" has not received any partial or full credits due to "
						"bidders backing out.";
	}
	else
	{
		// check for the adult cookie
		bHasCookie = HasAdultCookie();
	
		pvItems = pDeadbeatInfo->GetSellerItems();

		if (pvItems == NULL || pvItems->size() == 0)
		{
			*mpStream << "<p><i>Sorry, an error occurred while retrieving information "
						 "about auctions that ";

			pDeadbeatUserIdWidget->EmitHTML(mpStream);

			*mpStream << " requested full or partial credits for.</i>";
		}
		else
		{
			*mpStream <<	"<p>"
					  <<	"<a name=\"credits\">"
					  <<	"Here is the list of items that ";
			
			pDeadbeatUserIdWidget->EmitHTML(mpStream);

			*mpStream <<	" has received full or partial credit for:";

			*mpStream <<	"<p>"
					  <<	"<table width=\"100%\" border=\"1\">\n"
					  <<	"  <tr>"
					  <<	"    <th>Code</th>\n"
					  <<	"    <th>Item</th>\n"
					  <<	"    <th>Start</th>\n"
					  <<	"    <th>End</th>\n"
					  <<	"    <th>Price</th>\n"
					  <<	"    <th>Title</th>\n"
					  <<	"    <th>Bidder</th>\n"
					  <<	"  </tr>\n";

			for (iItems = pvItems->begin();
				 iItems != pvItems->end();
				 iItems++)
			{
				feedbackScore = 0;

				// skip if the item pointer is null
				if ((*iItems) == NULL)
					continue;

				// extract item seller's userid
				pBidder = mpUsers->GetUser((*iItems)->GetBidder());
				if (pBidder != NULL)
				{
					pBidderUserId = pBidder->GetUserId();
					pFeedback = pBidder->GetFeedback();
					if (pFeedback != NULL)
						feedbackScore = pFeedback->GetScore();
				}

				// start new row
				*mpStream <<	"  <tr>\n";

				// output reason code
				*mpStream <<	"    <td align=\"center\">";

				if ((*iItems)->GetReasonCode() != NULL)
					*mpStream <<	(*iItems)->GetReasonCode();
				else
					*mpStream <<	"<font color=\"red\">"
							  <<	"??"
							  <<	"</font>";

				*mpStream <<	"</td>\n";

				// output item number
				*mpStream <<	"    <td>"
						  <<	"<a href="
						  <<	"\""
						  <<	mpMarketPlace->GetCGIPath(PageViewItem)
						  <<	"eBayISAPI.dll?ViewItem&item="
						  <<	(*iItems)->GetId()
						  <<	"\""
						  <<	">"
						  <<	(*iItems)->GetId()
						  <<	"</a>"
						  <<	"</td>\n";

				// format and output start time
// petra				startTime	= (*iItems)->GetStartTime();
// petra				pTheTime	= localtime(&startTime);
// petra				strftime(cStartTime, sizeof(cStartTime), 
// petra						 "%m/%d/%y", pTheTime);
				clseBayTimeWidget timeWidget (mpMarketPlace, 1, -1, (*iItems)->GetStartTime());	// petra
				
				*mpStream <<	"    <td>";
				timeWidget.EmitHTML (mpStream);	// petra
// petra						  <<	cStartTime
				*mpStream <<	"</td>\n";

				// format and output end time
// petra				endTime		= (*iItems)->GetEndTime();
// petra				pTheTime	= localtime(&endTime);
// petra				strftime(cEndTime, sizeof(cEndTime),
// petra						 "%m/%d/%y %H:%M:%S", pTheTime);
				timeWidget.SetDateTimeFormat (1, 1);			// petra
				timeWidget.SetTime ( (*iItems)->GetEndTime() );	// petra

				*mpStream <<	"    <td>";
				timeWidget.EmitHTML (mpStream);		// petra
// petra						  <<	cEndTime
				*mpStream <<	"</td>\n";

				// output item price
				*mpStream <<	"    <td align=\"right\">"
						  <<	(*iItems)->GetPrice()
						  <<	"</td>\n";

				// output item title
				*mpStream <<	"    <td>";

				if ((*iItems)->GetTitle() != NULL)
					*mpStream <<	(*iItems)->GetTitle();

				*mpStream <<	"</td>\n";

				// output seller's info
				*mpStream <<	"    <td>";

				if (pBidder != NULL)
				{
					pOtherUserIdWidget->SetShowFeedback(true);
					pOtherUserIdWidget->SetUserInfo(pBidderUserId, 
											   pBidder->GetEmail(),
											   UserStateEnum(0),
											   false,
											   feedbackScore);
					pOtherUserIdWidget->SetShowUserStatus(false);
					pOtherUserIdWidget->SetShowStar(true);
					pOtherUserIdWidget->SetUserIdOnly();
					pOtherUserIdWidget->EmitHTML(mpStream);
				}
				else
				{
					*mpStream <<	"<font color=\"red\">"
									"Unknown!"
									"</font>";
				}

				*mpStream <<	"</td>";

				// end of row
				*mpStream <<	"  </tr>\n";

				// Clean up
				delete pBidder;	// this will delete the clsFeedback object as well
			}

			*mpStream <<	"</table>\n";

			// delete the seller items vector
			for (iItems = pvItems->begin();
    			 iItems != pvItems->end();
    			 iItems++)
			{
    			delete (*iItems);
			}

			pvItems->erase(pvItems->begin(), pvItems->end());
		}
	}

	// Now display the list of backouts for this user, if any.
	if (deadbeatScore == 0)
	{
		*mpStream <<	"<p>"
				  <<	"<a name=\"backouts\">";
		
		pDeadbeatUserIdWidget->EmitHTML(mpStream);

		*mpStream <<	" has not backed out of any transactions.";
	}
	else
	{
		// check for the adult cookie
		bHasCookie = HasAdultCookie();
	
		pvItems = pDeadbeatInfo->GetBidderItems();

		if (pvItems == NULL || pvItems->size() == 0)
		{
			*mpStream << "<p><i>Sorry, an error occurred while retrieving information "
						 "about transactions that ";

			pDeadbeatUserIdWidget->EmitHTML(mpStream);

			*mpStream << " has backed out of.</i>";
		}
		else
		{
			*mpStream <<	"<p><p>"
					  <<	"<a name=\"backouts\">"
					  <<	"Here is the list of transactions that ";
			
			pDeadbeatUserIdWidget->EmitHTML(mpStream);

			*mpStream <<	" has backed out of:";

			*mpStream <<	"<p>"
					  <<	"<table width=\"100%\" border=\"1\">\n"
					  <<	"  <tr>"
					  <<	"    <th>Code</th>\n"
					  <<	"    <th>Item</th>\n"
					  <<	"    <th>Start</th>\n"
					  <<	"    <th>End</th>\n"
					  <<	"    <th>Price</th>\n"
					  <<	"    <th>Title</th>\n"
					  <<	"    <th>Seller</th>\n"
					  <<	"  </tr>\n";

			for (iItems = pvItems->begin();
				 iItems != pvItems->end();
				 iItems++)
			{
				feedbackScore = 0;

				// skip if the item pointer is null
				if ((*iItems) == NULL)
					continue;

				// extract item seller's userid
				pSeller = mpUsers->GetUser((*iItems)->GetSeller());
				if (pSeller != NULL)
				{
					pSellerUserId = pSeller->GetUserId();
					pFeedback = pSeller->GetFeedback();
                    if (pFeedback != NULL)
					    feedbackScore = pFeedback->GetScore();
				}
				*mpStream <<	"  <tr>\n";

				// output reason code
				*mpStream <<	"    <td align=\"center\">";

				if ((*iItems)->GetReasonCode() != NULL)
					*mpStream <<	(*iItems)->GetReasonCode()
							  <<	" ";
				else
					*mpStream <<	"<font color=\"red\">"
							  <<	"?? "
							  <<	"</font>";

				// Make sure we got a valid seller
				if ( pSellerUserId != NULL )
				{
					// Add Link to remove this deadbeat tick
					*mpStream	<<	"<a href="
								<<	"\""
								<<	mpMarketPlace->GetCGIPath(PageDeleteDeadbeatItem)
								<<	"eBayISAPI.dll?DeleteDeadbeatItem&selleruserid="
								<<	pSellerUserId
								<<	"&bidderuserid="
								<<	pDeadbeatUser->GetUserId()
								<<	"&itemno="
								<<	(*iItems)->GetId()
								<<	"\""
								<<	">"
								<<	"Remove Tick"
								<<	"</a>";
				}

				*mpStream <<	"</td>\n";

				// output item number
				*mpStream	<<	"    <td>"
							<<	"<a href="
							<<	"\""
							<<	mpMarketPlace->GetCGIPath(PageViewItem)
							<<	"eBayISAPI.dll?ViewItem&item="
							<<	(*iItems)->GetId()
							<<	"\""
							<<	">"
							<<	(*iItems)->GetId()
							<<	"</a>"
							<<	"</td>\n";

				// format and output start time
// petra				startTime	= (*iItems)->GetStartTime();
// petra				pTheTime	= localtime(&startTime);
// petra				strftime(cStartTime, sizeof(cStartTime), 
// petra						 "%m/%d/%y", pTheTime);
				clseBayTimeWidget timeWidget (mpMarketPlace, 1, -1, (*iItems)->GetStartTime() );	// petra
				
				*mpStream <<	"    <td>";
				timeWidget.EmitHTML (mpStream);		// petra
// petra						  <<	cStartTime
				*mpStream <<	"</td>\n";

				// format and output end time
// petra				endTime		= (*iItems)->GetEndTime();
// petra				pTheTime	= localtime(&endTime);
// petra				strftime(cEndTime, sizeof(cEndTime),
// petra						 "%m/%d/%y %H:%M:%S", pTheTime);

				*mpStream <<	"    <td>";
				timeWidget.SetDateTimeFormat (1, 1);			// petra
				timeWidget.SetTime ((*iItems)->GetEndTime() );	// petra
				timeWidget.EmitHTML (mpStream);					// petra
// petra						  <<	cEndTime
				*mpStream <<	"</td>\n";

				// output item price
				*mpStream <<	"    <td align=\"right\">"
						  <<	(*iItems)->GetPrice()
						  <<	"</td>\n";

				// output item title
				*mpStream <<	"    <td>";

				if ((*iItems)->GetTitle() != NULL)
					*mpStream <<	(*iItems)->GetTitle();

				*mpStream <<	"</td>\n";

				// output seller's info
				*mpStream <<	"    <td>";

				if (pSeller != NULL)
				{
					pOtherUserIdWidget->SetShowFeedback(true);
					pOtherUserIdWidget->SetUserInfo(pSellerUserId, 
											   pSeller->GetEmail(),
											   UserStateEnum(0),
											   false,
											   feedbackScore);
					pOtherUserIdWidget->SetShowUserStatus(false);
					pOtherUserIdWidget->SetShowStar(true);
					pOtherUserIdWidget->SetUserIdOnly();
					pOtherUserIdWidget->EmitHTML(mpStream);
				}
				else
				{
					*mpStream <<	"<font color=\"red\">"
									"Unknown!"
									"</font>";
				}

				*mpStream <<	"</td>";

				// end of row
				*mpStream <<	"  </tr>\n";

				delete pSeller;	// this will delete the clsFeedback object as well

				// Reset the pointers for the buyer and sellers user ids
				pSellerUserId = NULL;
			}

			*mpStream <<	"</table>\n";

			// delete the bidder items vector
			for (iItems = pvItems->begin();
    			 iItems != pvItems->end();
    			 iItems++)
			{
    			delete (*iItems);
			}

			pvItems->erase(pvItems->begin(), pvItems->end());
		}
	}

	*mpStream <<	"<p>"
			  <<	mpMarketPlace->GetFooter();

    // Clean up
	delete pDeadbeatUserIdWidget;
	delete pOtherUserIdWidget;

	CleanUp();

	return;
}

