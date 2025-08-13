/* $Id: clseBayAppUserPageLogin.cpp,v 1.4.204.1.88.2 1999/08/05 20:42:21 nsacco Exp $ */
//
//	File:		clseBayAppUserPageLogin.cpp
//
//	Class:		clseBayApp
//
//	Author:		Chad, Barry
//
//	Function:
//
//				Log in to edit your user page.
//
//	Modifications:
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
// 
#include "ebihdr.h"

void clseBayApp::UserPageLogin(CEBayISAPIExtension *pThis,
							   char *pUserId,
		 					   char *pPassword)
{
	SetUp();

	*mpStream <<	"<html><head>"
                    "<title>"
              <<    mpMarketPlace->GetCurrentPartnerName()
              <<    " - About Me "
              <<    "</title>"
                    "</head>"
		      <<	mpMarketPlace->GetHeader();
//		      <<	mpMarketPlace->GetAboutMeHeader();

    mpUser = mpUsers->GetAndCheckUserAndPassword(pUserId,
        pPassword, mpStream);

    if (!mpUser)
    {
        CleanUp();
        return;
    }

	// Users must have accepted the user agreement first!
	if (!mpUser->AcceptedUserAgreement())
	{
		// Create the form part of the user agreement document.
		ProduceUserAgreementIntroForAboutMe();
		ProduceUserAgreementTopPart();

		*mpStream <<
				"<form method=\"post\" action=\""
			<<  mpMarketPlace->GetCGIPath(PageUserPageAcceptAgreement)
			<<  "eBayISAPI.dll\"> \n"
				"  <p> \n"
				"    <INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"UserPageAcceptAgreement\"> \n"
				"    <INPUT TYPE=HIDDEN NAME=\"userid\" VALUE=\""
			<< pUserId
			<< "\"> \n"
				"    <INPUT TYPE=HIDDEN NAME=\"password\" VALUE=\""
			<< pPassword
			<< "\"> \n"
				"  </p> \n";

		ProduceUserAgreementFormAfterAction();

		*mpStream << mpMarketPlace->GetFooter();

		CleanUp();
		return;

	}

	ProcessUserPageLogin(pThis, pUserId, pPassword);
	*mpStream << mpMarketPlace->GetFooter();

	CleanUp();
	return;
}

void clseBayApp::UserPageAcceptAgreement(CEBayISAPIExtension *pThis,
							   char *pUserId,
							   char *pPassword,
							   int	 agree,
						       int	 notify)
{
	int unused;

	SetUp();

	*mpStream <<	"<html><head>"
                    "<title>"
              <<    mpMarketPlace->GetCurrentPartnerName()
              <<    " - About Me "
              <<    "</title>"
                    "</head>"
		      <<	mpMarketPlace->GetHeader();
//		      <<	mpMarketPlace->GetAboutMeHeader();

    mpUser = mpUsers->GetAndCheckUserAndPassword(pUserId,
        pPassword, mpStream);

    if (!mpUser)
    {
        CleanUp();
        return;
    }

	if (!agree)
	{
		// Did not accept the agreement
		if (notify)
			mpUser->SetSomeUserFlags(true, UserFlagChangesToAgreement);

		ProduceUserAgreementFAQ();

		CleanUp();
		return;
	}

	if (notify)
		unused = mpUser->SetSomeUserFlags(true, UserFlagSignedAgreement | UserFlagChangesToAgreement);
	else
		unused = mpUser->SetSomeUserFlags(true, UserFlagSignedAgreement);

	ProcessUserPageLogin(pThis, pUserId, pPassword);
	*mpStream << mpMarketPlace->GetFooter();

	CleanUp();
	return;
}

void clseBayApp::ProcessUserPageLogin(CEBayISAPIExtension *pThis,
							   char *pUserId,
							   char *pPassword)
{

	// Either go to the preview page right away
	// in HTML editing mode if the user already
	// has an About Me page, or put the user 
	// on the path of creating one for the first time.
	if (mpUser->HasAboutMePage())
	{
		UserPageGoToHTMLPreview(pThis,
						pUserId,
                        pPassword,
                        NULL,
						false,
						0);

	}
	else
	{
		UserPageSelectTemplateStyles(pThis,
									 pUserId, 
									 pPassword,
									 NULL, 
									 false);
	}
}