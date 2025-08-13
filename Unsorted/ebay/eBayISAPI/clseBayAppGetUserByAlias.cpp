/*	$Id: clseBayAppGetUserByAlias.cpp,v 1.3.700.1 1999/08/01 03:01:15 barry Exp $	*/
//
//	File:		clseBayApp.cc
//
//	Class:		clseBayApp
//
//	Author:		vicki Shu (vicki@ebay.com)
//
//	Function:
//
//				Display pages that user can request a User Id which will disply
//              all users who used this alias before and current
//
//	Modifications:
//				- 4/20/98	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
//
#include "ebihdr.h"
#include "clsUserIdWidget.h"

// Used to reference functions in our caller.
// It's probably more "portable" to handle
// this stuff through clsEnvironment.



void clseBayApp::GetUserByAlias(CEBayISAPIExtension *pServer, 
							 char *pRequestedUserId,
							 char *pRequestorUserId,
							 char *pRequestorPass)
{
	// The vector 
    UserIdAliasHistoryVector 	vUsers;
	// Itcherator
	UserIdAliasHistoryVector::iterator	vI;

	clsUser*	pRequestedUser;
	clsUser*	pTempUser;

	char				TimeString[15];
	struct tm*			pTimeTm;
	time_t				TheTime;
	bool				colorSwitch	= false;
	clsUserIdWidget*	pUserIdWidget;

    SetUp();

	pUserIdWidget = new clsUserIdWidget(mpMarketPlace, this);
	pUserIdWidget->SetShowFeedback(false);
	pUserIdWidget->SetShowUserStatus(false);
	pUserIdWidget->SetShowMask(false);
	pUserIdWidget->SetShowStar(false);

	// Heading, etc
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" History of the User ID"
					"</title>"
					"</head>"
			  << mpMarketPlace->GetHeader()
			  << "\n"
			  << flush;

	// validate the requestor
	mpUser	= mpUsers->GetAndCheckUserAndPassword(pRequestorUserId, pRequestorPass, mpStream);

	// If we didn't get the user, we're done
	if (!mpUser)
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter()
				  <<	flush;

		delete pUserIdWidget;
		CleanUp();
		return;
	}

	strlwr(pRequestedUserId);

	// get the requested user info	
	pRequestedUser = mpUsers->GetAndCheckUser(pRequestedUserId, NULL);

	//check this userid used before
	mpUsers->GetIdByAlias(pRequestedUserId, &vUsers);

	// header
	*mpStream	<<	"<h2>History for the User ID: "
				<<	pRequestedUserId
				<<	" </h2>\n";
	*mpStream	<<  "The list below shows the history of a User ID, "
				<<	"including all users who have participated on eBay "
				<<	"with the User ID you requested."
				<<	"<p>";

	if (!pRequestedUser && vUsers.size() <1) {
		*mpStream << "There were no users that used \""
				  << pRequestedUserId
				  << "\" as their User ID.";

		*mpStream	<<	mpMarketPlace->GetFooter()
						<<	flush;

		delete pUserIdWidget;
		CleanUp();
		return;
		}
				     
    *mpStream <<	"<TABLE BORDER=1 CELLPADDING=2 CELLSPACING=2 BGCOLOR=\"#FFFFFF\">"
					"<TR>"
					"<TH BGCOLOR=\"#FFFFCC\">Current User ID</TH>"
					"<TH BGCOLOR=\"#FFFFCC\">Current User Email</TH>"
					"<TH BGCOLOR=\"#FFFFCC\">Ending Date</TH>"
					"</TR>"
					"\n";

	
	//let's check if this userid is using now
	if (pRequestedUser && (pRequestedUser->GetUserState() == UserConfirmed 
		|| pRequestedUser->GetUserState() == UserSuspended))
	{
		//clean up User Id
		pRequestedUserId = clsUtilities::CleanUpUserId(pRequestedUserId);

		if (strcmp(pRequestedUserId, pRequestedUser->GetUserId() )== 0 ||
			strcmp(pRequestedUserId, pRequestedUser->GetEmail() )== 0) 
		{
			pUserIdWidget->SetUser(pRequestedUser);

			// user id is currently used
			*mpStream <<	"<TR>"
							"<TD> ";
			
			pUserIdWidget->EmitHTML(mpStream);

			*mpStream <<	"</TD>"
							"<TD>"
							"<A HREF=\"mailto: "
					  <<	pRequestedUser->GetEmail()
					  <<	"\">"
					  <<	pRequestedUser->GetEmail()
					  <<	"</TD>"	
			    			"<TD "
				 			">"
							"Current"
							" "
							"</TD>"
							"</TR>\n";
		}
		delete [] pRequestedUserId;
    } 
	

	//check this id used before?
	if (vUsers.size() < 1)
	{
		*mpStream   << "</TABLE>\n";

		*mpStream	<<	mpMarketPlace->GetFooter()
					<<	flush;
	
		delete pRequestedUser;
		delete pUserIdWidget;

		CleanUp();
		return;
	}	

    //if have return
	for (vI = vUsers.begin();
		     vI	!= vUsers.end();
		     vI++)
	{
		pTempUser = mpUsers->GetUser((*vI)->mId);
		TheTime  = (*vI)->mModified;

		pTimeTm = localtime(&TheTime);
		strftime(TimeString, sizeof(TimeString), "%b %d, %Y", pTimeTm);
		pUserIdWidget->SetUser(pTempUser);
	
		*mpStream <<	"<TR>"
						"<TD "
						">";

		pUserIdWidget->EmitHTML(mpStream);

		*mpStream  <<	"</TD>"
				   <<	"<TD>"
				   <<   "<A HREF=\"mailto: "
				   <<	pTempUser->GetEmail()
				   <<	"\">"
				   <<	pTempUser->GetEmail()
				   <<	"</TD>"	
			    		"<TD "
						">"
				   <<	TimeString
		           <<	" "
						"</TD>"
					    "</TR>\n";

		delete pTempUser;
	}

	*mpStream <<	"</TABLE>\n"
			  <<	flush;

	*mpStream <<	"<p>Note: the ending date is the date when the users changed their IDs to something else.";

	// the footer
	*mpStream <<	mpMarketPlace->GetFooter()
			  <<	flush;

// Clean up the list
	for (vI = vUsers.begin();
	     vI != vUsers.end();
	     vI++)
	{
		// Delete the Alias
		delete	(*vI);
	}

	vUsers.erase(vUsers.begin(), vUsers.end());

	delete pRequestedUser;
	delete pUserIdWidget;

	CleanUp();

	return;
}
