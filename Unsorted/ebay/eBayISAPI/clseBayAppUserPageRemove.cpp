/* $Id: clseBayAppUserPageRemove.cpp,v 1.2.348.1.88.2 1999/08/05 20:42:22 nsacco Exp $ */
//
//	File:		clseBayAppUserPagePreview.cpp
//
//	Class:		clseBayApp
//
//	Author:		Chad
//
//	Function:
//
//			    Just a function to nuke your user page.
//
//	Modifications:
//     Barry    Some changes to the display.
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"
#include "clseBayApp.h"
#include "clsUserPage.h"
#include "clsWidgetPage.h"

// Remove a user's page
void clseBayApp::RemoveUserPage(char *pUserId,
                                char *pPassword,
                                int   page /* =0 */,
								bool  display /* = false */)
{
    clsUserPage thePage;
	
    SetUp();

	if (display)
	{
		*mpStream <<    "<html><head>"
			            "<title>"
				  <<    mpMarketPlace->GetCurrentPartnerName()
	              <<    " Remove User Page for "
		          <<    pUserId
			      <<    "</title>"
				        "</head>"
		      <<	mpMarketPlace->GetHeader();
//		      <<	mpMarketPlace->GetAboutMeHeader();
	}

    mpUser = mpUsers->GetAndCheckUserAndPassword(pUserId,
        pPassword, mpStream);

    if (!mpUser)
    {
		// Shouldn't ever happen...
        CleanUp();
        return;
    }

    thePage.SetPage(page);
    thePage.SetUserId(mpUser->GetId());
    thePage.RemovePage();

    // BIG NOTE: This _assumes_ that users may only have one page.
    // If you change that condition, change this code!
    mpUser->SetAboutMePage(false);

	if (display)
	{
		// Right now, I totally branch around this, all the time.
		// However, TO DO: Need a nicer page here.
		*mpStream << "<h3>Page removed successfully.</h3><br>";

		*mpStream << mpMarketPlace->GetFooter();
	}

    CleanUp();
    return;
}

