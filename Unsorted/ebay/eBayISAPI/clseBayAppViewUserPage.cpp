/* $Id: clseBayAppViewUserPage.cpp,v 1.2.348.1.92.2 1999/08/05 20:42:28 nsacco Exp $ */
// View a user page.
//
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"
#include "clseBayApp.h"
#include "clsUserPage.h"
#include "clsWidgetPage.h"
#include "clsWidgetContext.h"

void clseBayApp::ViewUserPage(char *pUserId, int page)
{
    clsUserPage thePage;
//    int user;

    SetUp();

        // Title
    *mpStream  <<	"<html><head>"
			        "<title>"
		       <<	mpMarketPlace->GetCurrentPartnerName()
		       <<	" View User Page for "
		       <<	pUserId
		       <<	"</title>"
			        "</head>"
		      <<	mpMarketPlace->GetHeader();
//		      <<	mpMarketPlace->GetAboutMeHeader();

/*
    if ((user = atoi(pUserId)))
    {
        mpUser = mpUsers->GetUser(user, true);
        if (mpUser)
            pUserId = mpUser->GetUserId();
    }
*/
    mpUser = mpUsers->GetUser(pUserId, true);

    if (!mpUser)
    {
        // We'll use GetAndCheckUser to take care of the error
        // for us in a standard way.
        mpUser = mpUsers->GetAndCheckUser(pUserId, mpStream);
        CleanUp();
        return;
    }

    thePage.SetPage(page);
    thePage.SetUserId(mpUser->GetId());

    thePage.LoadPage(true);

    if (!thePage.GetDataDictionary())
    {
        // We have no page to display here.
        *mpStream <<    "<html><head>"
                        "<title>"
                  <<    mpMarketPlace->GetCurrentPartnerName()
                  <<    " View User Page for "
                  <<    pUserId
                  <<    "</title>"
                        "</head>"
                  <<    mpMarketPlace->GetHeader();

        *mpStream << "<H2>Page was not found.</H2><p>\n";
        *mpStream << mpMarketPlace->GetFooter();

        CleanUp();
        return;
    }

    // Cool. We now have a page. Let's load it up in the display thing
    // and run it through, shall we?
    clsWidgetPage thePageDraw;
    long numViews = thePage.GetNumViews();
    clsWidgetContext *pContext;

    thePageDraw.SetPage(thePage.GetDataDictionary());
    
    // Set some context up for the users.
    pContext = thePageDraw.GetContext();
    pContext->SetNumViews(&numViews);
    pContext->SetUser(mpUser);

    thePageDraw.Draw(mpStream);
    thePage.AddView();

	// Put the standard footer at the bottom of the user's page.
	*mpStream << mpMarketPlace->GetFooter();
    CleanUp();
    return;
}
