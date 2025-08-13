/*	$Id: clseBayAppAdminSuspendUserNoShow.cpp,v 1.2 1999/03/22 00:09:33 josh Exp $	*/
//
//	File:		clseBayAppAdminSuspendUser.cpp
//
//	Class:		clseBayApp
//
//	Author:		Mila Bird (mila@ebay.com)
//
//	Function:
//				This does what AdminSuspendUser does, minus the emission
//				of HTML.
//
//
//	Modifications:
//				- 03/04/99 mila		- Created
//

#include "ebihdr.h"
#include <time.h>
								  
bool clseBayApp::AdminSuspendUserNoShow(char *pUserId,
									    char *pPass,
									    char *pSuspendee,
									    int type,
									    char *pText,
									    char *pEmailSubject,
									    char *pEmailText,
									    eBayISAPIAuthEnum authLevel)
{
	clsUser		*pSuspendeeUser	= NULL;
	clsUser		*pUser = NULL;

	// For mailing the suspendee
	clsMail		*pMail;
	ostrstream	*pMailStream;


	char		*pUserInfoText;
	char		*pTextWithUserInfo;
	time_t		nowTime;
	clsNotes	*pNotes;
	clsNote		*pNote;

	// Setup
	SetUp();

	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp();
		return false;
	}

	// Get user to suspend
	pUser = mpUsers->GetAndCheckUser(pUserId, NULL);
	if (pUser == NULL)
	{
		CleanUp();
		return false;
	}

	// All is well! Let's file an enote!
	pSuspendeeUser			= mpUsers->GetAndCheckUser(pSuspendee, NULL);
	if (pSuspendeeUser == NULL)
	{
		CleanUp();
		return false;
	}

	nowTime	= time(0);

	pUserInfoText		= clsNote::GetUserInfo(0, pSuspendeeUser);
	pTextWithUserInfo	= new char[strlen(pUserInfoText) + 8 + strlen(pText) + 1];
	strcpy(pTextWithUserInfo, pUserInfoText);
	strcat(pTextWithUserInfo, "<br><br>");
	strcat(pTextWithUserInfo, pText);

	pNotes	= mpMarketPlace->GetNotes();
	if (pNotes == NULL)
	{
		CleanUp();
		return false;
	}

	pNote	= new clsNote(pNotes->GetSupportUser()->GetId(),
						  pUser->GetId(),
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
				pUser->GetEmail(),
				pEmailSubject,
				NULL,
				(char **)AutomatedSupportEmailBccList);

	delete	pMail;
	delete	pSuspendeeUser;
	delete	pUser;

	CleanUp();

	return true;
}

	