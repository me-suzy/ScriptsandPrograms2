/*	$Id: clsebayappGetUserAboutMe.cpp,v 1.1.6.1.108.1 1999/08/01 03:01:42 barry Exp $	*/
//
//	File:		clseBayApp.cc
//
//	Class:		clseBayApp
//
//	Author:		Vicki (vicki@ebay.com)
//
//	Function:
//
//				Display pages that user can request other's about me page
//
//	Modifications:
//				- 04/12/99 vicki	- Created
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
#include "clsUserValidation.h"
#include "clsUserIdWidget.h"

// Used to reference functions in our caller.
// It's probably more "portable" to handle
// this stuff through clsEnvironment.

#include "stdafx.h"
#include <AFXISAPI.H>


bool clseBayApp::GetUserAboutMe(CEBayISAPIExtension *pServer, 
								char *pUserId,
								char *pRedirectURL)
								
{
	clsUser*	pUser;
//	bool error = false;
	
	SetUp();

	// Heading, etc
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Request User E-mail"
					"</title>"
					"</head>"
			  << mpMarketPlace->GetHeader()
			  << "\n"
			  << flush;

	// get the requested user info
	pUser = mpUsers->GetAndCheckUser(pUserId, mpStream);

	if (!pUser)
	{
		*mpStream	<<	"<p>"
					<<	mpMarketPlace->GetFooter();

		CleanUp();
		return false;
	}
	else
	{
		if(pUser->HasAboutMePage())
		{
			//redirect to about me page
			sprintf(pRedirectURL, "%saboutme/%s", mpMarketPlace->GetMembersPath(), pUserId);
		
			delete pUser;
		
			CleanUp();
			return true;

		}
		else
		{
			// tell requestor, the requested user doesn't have about me age
			*mpStream	<< "<H2>Page was not found</H2><p>\n"
						<< "Sorry, there is no About Me page for "
						<< pUserId
						<< ".";

			*mpStream	<<	"<p>"
					<<	mpMarketPlace->GetFooter();

			delete pUser;

			CleanUp();
			return false;
		}
		
	}

}

