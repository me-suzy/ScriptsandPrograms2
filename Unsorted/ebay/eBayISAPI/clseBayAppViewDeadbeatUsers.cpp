/*	$Id: clseBayAppViewDeadbeatUsers.cpp,v 1.3.298.2 1999/08/05 20:42:24 nsacco Exp $	*/
//
//	File:	clseBayAppViewDeadbeatUsers.cpp
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
//				- 10/13/98 mila		- Created
//				- 12/08/98 mila		- Modified to get clsDeadbeatItem data
//									  from clsDeadbeat rather than directly
//									  from database; added target names to
//									  ViewDeadbeatUser links
//				- 12/16/98 mila		- Deleted admin user ID,password, and
//									  authorization level parameters since this
//									  page is being accessed only from support
//									  page.
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

//
// ViewDeadbeatUsers
//
void clseBayApp::ViewDeadbeatUsers(CEBayISAPIExtension *pThis)
{
	// user info
	clsUser							*pUser;
	clsUserIdWidget					*pUserIdWidget;
	clsFeedback						*pFeedback;
	char							*pSafeUserId;

	// user's deadbeat info
	DeadbeatVector					vDeadbeats;
	DeadbeatVector::const_iterator	i;

	// tallies
	int								backouts;
	int								creditRequests;

	SetUp();

	// Title
	*mpStream <<	"<html><head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Transaction Backout Summary"
			  <<	"</title>"
					"</head>"
			  <<	mpMarketPlace->GetHeader();

	// Header
	*mpStream <<	"\n"
					"<h2>Transaction Backout Summary</h2>\n"
					"<p>"
					"\n";

	// Now get a list of all the deadbeat transactions.
	gApp->GetDatabase()->GetAllDeadbeats(&vDeadbeats);

	if (vDeadbeats.size() == 0)
	{
		*mpStream <<	"No users have backed out of transactions or "
						"received full or partial credits.\n";
	}
	else
	{
		// create a table with column headings to display the info.
		*mpStream <<	"\n"
				  <<	"<p>"
				  <<	"<table border=\"1\" cellpadding=\"5\">\n"
				  <<	"  <tr>\n"
				  <<	"    <th>User ID</th>\n"
				  <<	"    <th>Credit Requests</th>\n"
				  <<	"    <th>Backouts</th>\n"
				  <<	"    <th>Warnings</th>\n"
				  <<	"    <th>User Status</th>\n"
				  <<	"  </tr>\n";

		pUserIdWidget = new clsUserIdWidget(mpMarketPlace, this);

		// For each bidder in the list...
		// For each user in the table, list their info.
		for (i = vDeadbeats.begin();
			 i != vDeadbeats.end();
			 i++)
		{
			pUser = mpUsers->GetUser((*i)->GetId());
			if (pUser == NULL)
			{
				*mpStream <<	"</table>\n"
								"</p>\n"
								"Error getting clsUser object for user "
						  <<	(*i)->GetId()
						  <<	".  Please report this to engineering.\n"
								"<p>\n"
						  <<	mpMarketPlace->GetFooter();

				CleanUp();
				return;
			}

			// Get the safe user ID
			pSafeUserId = clsUtilities::MakeSafeString(pUser->GetUserId());

			// Get the user's feedback so we can include it in the UserId widget;
			// DON'T delete the pFeedback object because clsUser will do it
			pFeedback = pUser->GetFeedback();
			if (pFeedback == NULL)
			{
				*mpStream <<	"</table>\n"
								"</p>\n"
								"Error getting clsFeedback object for user "
						  <<	(*i)->GetId()
						  <<	".  Please report this to engineering.\n"
								"<p>\n"
						  <<	mpMarketPlace->GetFooter();

				CleanUp();
				return;
			}

			// Start a new table row
			*mpStream <<	"  <tr>\n";

			// Output the user id widget
			*mpStream <<	"    <td>";

			pUserIdWidget->SetShowFeedback(true);
			pUserIdWidget->SetUserInfo(pUser->GetUserId(), 
									   pUser->GetEmail(),
									   UserStateEnum(0),
									   false,
									   pFeedback->GetScore());
			pUserIdWidget->SetShowUserStatus(false);
			pUserIdWidget->SetShowStar(true);
			pUserIdWidget->SetUserIdOnly();
			pUserIdWidget->EmitHTML(mpStream);

			*mpStream <<	"</td>";

			// Output the user's credit request count
			*mpStream <<	"    <td align=\"center\">";

			creditRequests = (*i)->GetCreditRequestCount();

			if (creditRequests > 0)
			{
				// Link the user's credit request count to his/her credit request
				// profile info
				*mpStream <<	"<a href=\""
						  <<	mpMarketPlace->GetCGIPath(PageViewDeadbeatUser)
						  <<	"eBayISAPI.dll?ViewDeadbeatUser"
						  <<	"&deadbeatuserid="
						  <<	pSafeUserId
						  <<	"#credits\">";

				*mpStream <<	creditRequests;

				*mpStream <<	"</a>\n";
			}
			else
			{
				// Output the user's credit request count
				*mpStream <<	creditRequests;
			}

			*mpStream <<	"</td>\n";

			*mpStream <<	"    <td align=\"center\">";

			backouts = -(*i)->GetDeadbeatScore();
			if (backouts != 0)
			{
				// Link the user's deadbeat score to his/her deadbeat profile info
				*mpStream <<	"<a href=\""
						  <<	mpMarketPlace->GetCGIPath(PageViewDeadbeatUser)
						  <<	"eBayISAPI.dll?ViewDeadbeatUser"
						  <<	"&deadbeatuserid="
						  <<	pSafeUserId
						  <<	"#backouts\">";

				*mpStream <<	backouts;

				*mpStream <<	"</a>\n";
			}
			else
			{
				// Output the user's deadbeat score
				*mpStream <<	backouts;
			}

			*mpStream <<	"</td>\n";

			*mpStream <<	"    <td align=\"center\">";

			// Output the number of warnings issued to user
			*mpStream <<	(*i)->GetWarningCount();

			*mpStream <<	"</td>\n";

			// Output the user's current status
			*mpStream <<	"    <td align=\"center\">";

			switch (pUser->GetUserState())
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

			*mpStream <<	"    </td>\n";

			// End of row
			*mpStream <<	"  </tr>\n";

			delete pSafeUserId;
			delete (*i);
			delete pUser;
		}

		vDeadbeats.erase(vDeadbeats.begin(), vDeadbeats.end());

		delete pUserIdWidget;

		// End of table
		*mpStream <<	"</table>\n";
	}

	*mpStream <<	"<p>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;
}

