/*	$Id: clseBayAppAdminSuspendUser.cpp,v 1.8.64.1.34.2 1999/08/05 20:42:05 nsacco Exp $	*/
//
//	File:		clseBayAppAdminSuspendUser.cpp
//
//	Class:		clseBayApp
//
//	Author:		Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
//	Modifications:
//				- 06/14/97 michael	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include <time.h>

void clseBayApp::AdminSuspendUser(char *pUserId,
								  char *pPass,
								  char *pSuspendee,
								  int type,
								  char *pText,
								  char *pEmailSubject,
								  char *pEmailText,
								  eBayISAPIAuthEnum authLevel)
{
	clsUser			*pSuspendeeUser			= NULL;
	clsFeedback		*pSuspendeeUserFeedback	= NULL;
	clsUserIdWidget	*pUserIdWidget			= NULL;

	// For mailing the suspendee
	clsMail							*pMail;
	ostrstream						*pMailStream;


	char		*pUserInfoText;
	char		*pTextWithUserInfo;
	time_t		nowTime;
	clsNotes	*pNotes;
	clsNote		*pNote;

	// Setup
	SetUp();

	// Title
	*mpStream <<	"<html><head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Administrative Suspension for "
			  <<	pUserId
			  <<	"</title>"
					"</head>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"<p>";

	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp();

		return;
	}

	// Check the input
	if (!ValidateSuspensionInput(pUserId, pPass, pSuspendee, type, pText))
	{
		*mpStream <<	"<p>";
		SuspendUserShow(pUserId,
						pPass,
						pSuspendee,
						type,
						pText);
		CleanUp();

		return;
	}

	// All is well! Let's file an enote!
	pSuspendeeUser			= mpUsers->GetAndCheckUser(pSuspendee, mpStream);
	pSuspendeeUserFeedback	= pSuspendeeUser->GetFeedback();

	nowTime	= time(0);

	pUserInfoText		= clsNote::GetUserInfo(0, pSuspendeeUser);
	pTextWithUserInfo	= new char[strlen(pUserInfoText) + 8 + strlen(pText) + 1];
	strcpy(pTextWithUserInfo, pUserInfoText);
	strcat(pTextWithUserInfo, "<br><br>");
	strcat(pTextWithUserInfo, pText);

	pNotes	= mpMarketPlace->GetNotes();

	pNote	= new clsNote(pNotes->GetSupportUser()->GetId(),
						  mpUser->GetId(),
						  0,
						  pSuspendeeUser->GetId(),
						  eClsNoteFromTypeAutoAdminPost,
						  type,
						  eClsNoteVisibleSupportOnly,
						  nowTime,
						  (time_t)0,
						  pEmailSubject,
						  pTextWithUserInfo);

	pNotes->AddNote(pNote);

	delete pTextWithUserInfo;

	// Indicate there's a note about this user!
	pSuspendeeUser->SetHasANote(true);


	// Do them!
	pSuspendeeUser->SetSuspended();

	// update them!
	pSuspendeeUser->UpdateUser();

	// Now, mail them!
	pMail	= new clsMail();

	pMailStream	= pMail->OpenStream();

	// Prepare the stream
	pMailStream->setf(ios::fixed, ios::floatfield);
	pMailStream->setf(ios::showpoint, 1);
	pMailStream->precision(2);

	*pMailStream <<	pEmailText;

	pMail->Send(pSuspendeeUser->GetEmail(), 
		        "support@ebay.com",	 
			    pEmailSubject,
				NULL,
				(char **)AutomatedSupportEmailBccList);

	delete	pMail;



	// Tell them it worked!
	pUserIdWidget			= new clsUserIdWidget(mpMarketPlace, this);
	pUserIdWidget->SetUserInfo(pSuspendeeUser->GetUserId(), 
							   pSuspendeeUser->GetEmail(),
							   pSuspendeeUser->GetUserState(),
							   pSuspendeeUser->UserIdRecentlyChanged(),
							   pSuspendeeUserFeedback->GetScore());
	pUserIdWidget->SetShowUserStatus(true);
	pUserIdWidget->SetIncludeEmail(true);

	*mpStream <<	"<font color=green>"
					"User ";

	pUserIdWidget->EmitHTML(mpStream);

	*mpStream <<	" Suspended! Any feedback this user has left for others "
					"is now valued at neutral."
					"</font>"
			  <<	"<p>"
			  <<	mpMarketPlace->GetFooter();

	delete	pSuspendeeUser;
	delete	pUserIdWidget;
	CleanUp();

	return;
}

	