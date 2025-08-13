/*	$Id: clseBayAppMultipleEmails.cpp,v 1.5.352.1 1999/08/01 03:01:19 barry Exp $	*/
//
//	File:		clseBayApp.cc
//
//	Class:		clseBayApp
//
//	Author:		Wen Wen (wwen@ebay.com)
//
//	Function:
//
//				Display page returning reuested email addresses for multiple
//				users
//
//	Modifications:
//				- 2/3/97 Wen	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"
#include "clsNameValue.h"

// Used to reference functions in our caller.
// It's probably more "portable" to handle
// this stuff through clsEnvironment.


const char Seps[] = " \t,;\r\n";
const char ErrorMsgTooManyRequest[] = "<h2>Too Many E-mail Addresses Requested </h2>"
"You have requested too many e-mail addresses today. ";


void clseBayApp::MultipleEmails(CEBayISAPIExtension *pServer, 
							 char *pRequestedUserIds,
							 char *pRequestorUserId,
							 char *pRequestorPass)
{
	char*		pOneUserId;
	clsUser*	pRequestedUser;
	int			CurrentReq;
	int			ThisReq = 0;
	clsUserValidation*		pUserValidation;

	SetUp();

	// check cookie
	pUserValidation = mpUsers->GetUserValidation();
	if (pUserValidation->IsSoftValidated() == false && 
		strcmp(pRequestorUserId, "default") == 0)
	{
		char Action[255];
		clsNameValuePair theNameValuePairs[2];

		// Create the actions tring
		sprintf(Action, "%seBayISAPI.dll", mpMarketPlace->GetCGIPath(PageMultipleEmails));

		// create the name value pairs
		theNameValuePairs[0].SetName("MfcISAPICommand");
		theNameValuePairs[0].SetValue("MultipleEmails");
		theNameValuePairs[1].SetName("userids");
		theNameValuePairs[1].SetValue(pRequestedUserIds);

		// show login page
		LoginDialog(Action, 2, theNameValuePairs);

		CleanUp();

		return;
	}

	// Heading, etc
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Multiple User Id and E-mail Information"
					"</title>"
					"</head>"
			  << mpMarketPlace->GetHeader()
			  << "\n"
			  << flush;

	// validate the requestor
	if (pUserValidation->IsSoftValidated())
	{
		mpUser = mpUsers->GetAndCheckUser((char*)pUserValidation->GetValidatedUserId(), mpStream);
	}
	else
	{
		mpUser	= mpUsers->GetAndCheckUserAndPassword(pRequestorUserId, 
												  pRequestorPass, 
												  mpStream);
	}

	// If we didn't get the user, we're done
	if (!mpUser)
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter()
				  <<	flush;

		CleanUp();
		return;
	}

	CurrentReq = mpUser->GetReqEmailCount();

	if (CurrentReq >= EBAY_EMAILS_REQUEST_PER_DAY)
	{
		*mpStream <<	ErrorMsgTooManyRequest
				  <<	"<p>"
				  <<	mpMarketPlace->GetFooter()
				  <<	flush;

		CleanUp();
		return;
	}

	// header
	*mpStream	<<	"<h2>Multiple User ID and E-mail Information</h2>\n";

	// begin table
	*mpStream	<<	"<p><table border=1>"
				<<	"<tr><th>Requested</th><th>User ID</th><th>E-mail</th></tr>\n";

	// get the requested user info
	strlwr(pRequestedUserIds);
	pOneUserId = strtok(pRequestedUserIds, Seps);

	while (pOneUserId != NULL)
	{
		ThisReq++;
		if (ThisReq+CurrentReq > EBAY_EMAILS_REQUEST_PER_DAY)
		{
			// over the limit, stop
			*mpStream	<<	"<tr><td colspan=3 rowspan=4>"
						<<	ErrorMsgTooManyRequest
						<<	"</td></tr>\n";
			break;
		}

		pRequestedUser = mpUsers->GetUser(pOneUserId);

		// display the requested
		*mpStream	<<	"<tr><td>"
					<<	pOneUserId
					<<	"</td>";

		// display user id
		if (pRequestedUser && pRequestedUser->GetUserState() == UserConfirmed)
		{
			*mpStream	<<	"<td>"
						<<	pRequestedUser->GetUserId()
						<<	"</td>";

			*mpStream	<<	"<td><a href=\"mailto:"
						<<	pRequestedUser->GetEmail()
						<<	"\">"
						<<	pRequestedUser->GetEmail()
						<<	"</a></td>";
		}
		else
		{
			*mpStream	<<	"<td colspan=2>User not found</td>";
		}
		*mpStream	<<	"</tr>\n";

		delete pRequestedUser;
		pOneUserId = strtok(NULL, Seps);
	}

	// end table
	*mpStream	<<	"</table>\n";

	// the footer
	*mpStream	<<	"</p>"
				<<	mpMarketPlace->GetFooter()
				<<	flush;

	mpUser->AddReqEmailCount(ThisReq);

	CleanUp();
	return;
}

//
// GetMultipleEmails - 
//
void clseBayApp::GetMultipleEmails(CEBayISAPIExtension *pServer)
{
	SetUp();

	// Heading, etc
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Request Multiple E-mails"
					"</title>"
					"</head>"
			  << mpMarketPlace->GetHeader()
			  << "\n"
			  << flush;

	// header
	*mpStream	<<	"<h2>Multiple E-mail Address Request Form</h2>\n";

	// legal, rule
	*mpStream	<< "<font size=\"3\">" 
		        << "Please type in the User IDs for which you are requesting e-mail addresses. "
				<< "The User IDs should be separated by one space, e.g. skippy woody dale.</font>";

	// form
	*mpStream	<<	"<form method=\"POST\" action=\""
				<<	mpMarketPlace->GetCGIPath(PageMultipleEmails)
				<<	"eBayISAPI.dll\">\n"
					"<input TYPE=HIDDEN NAME=\"MfcISAPICommand\" "
					"VALUE=\"MultipleEmails\">\n"
					"<TEXTAREA NAME=\"userids\" ROWS=5 COLS=50>"
					"</TEXTAREA><p>";
	
	if (mpUsers->GetUserValidation()->IsSoftValidated() == false)
	{
		// display the login fields only
		LoginDialog(NULL, 0, NULL, true);
	}
	else
	{
		*mpStream	<<	"<p><input type=\"submit\" value=\"Submit\"></p>\n";
	}

	// close the form
	*mpStream << "</form>\n";

	// the footer
	*mpStream << mpMarketPlace->GetFooter()
			  << flush;

	CleanUp();
	return;
}

