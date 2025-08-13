/*	$Id: clseBayAppAdminReinstateUser.cpp,v 1.9.22.1.90.2 1999/08/05 20:42:03 nsacco Exp $	*/
//
//	File:		clseBayAppAdminReinstateUser.cpp
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
//				- 05/25/99 mila		- Put guts of code into AdminReinstateUserInternal()
//									  so we can reinstate users programmatically
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include <time.h>

void clseBayApp::AdminReinstateUserInternal(char *pUserId,
										    char *pPass,
										    char *pTarget,
										    int type,
										    char *pText,
										    char *pEmailSubject,
										    char *pEmailText,
										    eBayISAPIAuthEnum authLevel)
{
	clsUser			*pTargetUser			= NULL;
	clsFeedback		*pTargetUserFeedback	= NULL;
	clsUserIdWidget	*pUserIdWidget			= NULL;

	// For mailing the suspendee
	clsMail			*pMail;
	ostrstream		*pMailStream;


	char		*pUserInfoText;
	char		*pTextWithUserInfo;
	time_t		nowTime;
	clsNotes	*pNotes;
	clsNote		*pNote;


	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp();

		return;
	}

	// Check the input
	if (!ValidateReinstateInput(pUserId, pPass, pTarget, type, pText))
	{
		*mpStream <<	"<p>";
		ReinstateUserShow(pUserId, pPass, pTarget, type, pText);
		CleanUp();

		return;
	}

	// All is well! Let's get the user!
	pTargetUser			= mpUsers->GetAndCheckUser(pTarget, mpStream);
	pTargetUserFeedback	= pTargetUser->GetFeedback();


	// Do them!
	pTargetUser->SetConfirmed();

	// update them!
	pTargetUser->UpdateUser();

	nowTime	= time(0);

	pUserInfoText		= clsNote::GetUserInfo(0, pTargetUser);
	pTextWithUserInfo	= new char[strlen(pUserInfoText) + 8 + strlen(pText) + 1];
	strcpy(pTextWithUserInfo, pUserInfoText);
	strcat(pTextWithUserInfo, "<br><br>");
	strcat(pTextWithUserInfo, pText);

	pNotes	= mpMarketPlace->GetNotes();

	pNote	= new clsNote(pNotes->GetSupportUser()->GetId(),
						  mpUser->GetId(),
						  0,
						  pTargetUser->GetId(),
						  eClsNoteFromTypeAutoAdminPost,
						  type,
						  eClsNoteVisibleSupportOnly,
						  nowTime,
						  (time_t)0,
						  pEmailSubject,
						  pTextWithUserInfo);

	pNotes->AddNote(pNote);

	delete pTextWithUserInfo;
	delete pUserInfoText;
 	delete pNote;

	// Now, mail them!
	pMail	= new clsMail();

	pMailStream	= pMail->OpenStream();

	// Prepare the stream
	pMailStream->setf(ios::fixed, ios::floatfield);
	pMailStream->setf(ios::showpoint, 1);
	pMailStream->precision(2);

	*pMailStream <<	pEmailText;

	pMail->Send(pTargetUser->GetEmail(), 
				"support@ebay.com",
				pEmailSubject,
				NULL,
				(char **)AutomatedSupportEmailBccList);

	delete	pMail;

	// Indicate there's a note about this user
	pTargetUser->SetHasANote(true);

	// Tell them it worked!
	pUserIdWidget			= new clsUserIdWidget(mpMarketPlace, this);
	pUserIdWidget->SetUserInfo(pTargetUser->GetUserId(), 
							   pTargetUser->GetEmail(),
							   pTargetUser->GetUserState(),
							   pTargetUser->UserIdRecentlyChanged(),
							   pTargetUserFeedback->GetScore());
	pUserIdWidget->SetShowUserStatus(true);
	pUserIdWidget->SetIncludeEmail(true);

	*mpStream <<	"<font color=green>"
					"User ";

	pUserIdWidget->EmitHTML(mpStream);

	*mpStream <<	" Reinstated!"
					"</font>";

	delete	pTargetUser;
	delete	pUserIdWidget;

	return;
}


void clseBayApp::AdminReinstateUser(char *pUserId,
								    char *pPass,
								    char *pTarget,
								    int type,
								    char *pText,
								    char *pEmailSubject,
								    char *pEmailText,
								    eBayISAPIAuthEnum authLevel)
{
	// Setup
	SetUp();

	// Title
	*mpStream <<	"<html><head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Administrative Reinstatement for "
			  <<	pUserId
			  <<	"</title>"
					"</head>"
			  <<	mpMarketPlace->GetHeader()
			  <<	"<p>";

	AdminReinstateUserInternal(pUserId, pPass, pTarget, type, pText,
							   pEmailSubject, pEmailText, authLevel);

	*mpStream <<	"<p>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;
}	