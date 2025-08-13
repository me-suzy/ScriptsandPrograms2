/* $Id: clseBayAppRemoveUserPage.cpp,v 1.2.540.2 1999/08/05 20:42:19 nsacco Exp $ */
// Just a function to nuke a user's page.
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"
#include "clseBayApp.h"
#include "clsUserPage.h"
#include "clsWidgetPage.h"

void clseBayApp::RemoveUserPage(char *pUserId,
                                char *pPassword,
                                int   page /* =0 */,
								bool  display)
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
	              <<    mpMarketPlace->GetHeader();
	}

    mpUser = mpUsers->GetAndCheckUserAndPassword(pUserId,
        pPassword, mpStream);

    if (!mpUser)
    {
        CleanUp();
        return;
    }

    thePage.SetPage(page);
    thePage.SetUserId(mpUser->GetId());
    thePage.RemovePage();

    // BIG NOTE: This _assumes_ that users may only have one page.
    // If you change that condition, change this code!
    mpUser->SetAboutMePage(false);

	if (display) {
		*mpStream << "<h3>Page removed successfully.</h3><br>";
		*mpStream << mpMarketPlace->GetFooter();
	}

    CleanUp();
    return;
}

void clseBayApp::RevertUserPage(char *pUserId,
                                char *pPassword,
                                int page /* =0 */)
{
    clsWidgetPage theWidgetPage;
    clsUserPage   thePage;
    const char   *pOriginalText;
    char		 *pCleanedText;
	
    SetUp();

    *mpStream <<    "<html><head>"
                    "<title>"
              <<    mpMarketPlace->GetCurrentPartnerName()
              <<    " Edit Reverted User Page "
              <<    "</title>"
                    "</head>"
              <<    mpMarketPlace->GetHeader();

    mpUser = mpUsers->GetAndCheckUserAndPassword(pUserId,
        pPassword, mpStream);

    if (!mpUser)
    {
        CleanUp();
        return;
    }

    thePage.SetPage(page);
    thePage.SetUserId(mpUser->GetId());
    thePage.LoadPage(true);

    if (!thePage.GetDataDictionary())
    { 
        pCleanedText = NULL;
    }
    else
    {
        theWidgetPage.SetPage(thePage.GetDataDictionary());
        pOriginalText = theWidgetPage.GetOriginalText();
        pCleanedText = clsUtilities::StripHTML((char *) pOriginalText);
    }

	ShowEditingPage(pUserId, pPassword, pCleanedText);

    *mpStream << flush;
    *mpStream << mpMarketPlace->GetFooter();

    CleanUp();
    return; 
}
