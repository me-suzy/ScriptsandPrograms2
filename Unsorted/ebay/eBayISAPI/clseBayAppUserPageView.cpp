/* $Id: clseBayAppUserPageView.cpp,v 1.5.198.2.92.3 1999/08/06 20:31:53 nsacco Exp $ */
//
//	File:		clseBayAppUserPageView.cpp
//
//	Class:		clseBayApp
//
//	Author:		Chad
//
//	Function:
//
//			    View a user page.
//
//	Modifications:
//     Barry    Added some display stuff.
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()

#include "ebihdr.h"
#include "userpage.h"

// This is the function that gets called when the user displays
// a particular user's page.
void clseBayApp::ViewUserPage(char *pUserId, 
							  int   page)
{
    clsUserPage		 thePage;
	clsUserIdWidget* pUserIdWidget;

    SetUp();

    // Title
    *mpStream <<	"<html><head>"
			        "<title>"
	          <<	mpMarketPlace->GetCurrentPartnerName()
	          <<	" View About Me for "
	          <<	pUserId
	          <<	"</title>"
			        "</head>"
		      <<	mpMarketPlace->GetHeader();
//		      <<	mpMarketPlace->GetAboutMeHeader();

	// mpUser is becoming the person who's page we're viewing,
	// not the person who invoked this function...

	mpUser = mpUsers->GetUser(pUserId, true);

    if (!mpUser)
    {
        // We'll use GetAndCheckUser to take care of the error
        // for us in a standard way.
        mpUser = mpUsers->GetAndCheckUser(pUserId, mpStream);
        CleanUp();
        return;
    }

	if (!mpUser->HasAboutMePage())
	{
        // We have no page to display here.
        *mpStream << "<H2>Page was not found</H2><p>\n"
				  << "Sorry, there is no About Me page for "
				  << pUserId
				  << ".";

        *mpStream << mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}
    thePage.SetPage(page);
    thePage.SetUserId(mpUser->GetId());

    thePage.LoadPage(true);

    if (!thePage.GetDataDictionary())
    {
		mpUser->SetAboutMePage(false);
        // We have no page to display here.
        *mpStream << "<H2>Page was not found</H2><p>\n"
				  << "Sorry, there is no About Me page for "
				  << pUserId
				  << ".";

        *mpStream << mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	pUserIdWidget = new clsUserIdWidget(mpMarketPlace, this);
	pUserIdWidget->SetUserInfo(mpUser->GetUserId(), 
							   mpUser->GetEmail(),
							   mpUser->GetUserState(),
							   mpMarketPlace->UserIdRecentlyChanged(mpUser->GetUserIdLastModified()));
	pUserIdWidget->SetUser(mpUser);
	pUserIdWidget->SetShowUserStatus(false);

	*mpStream << "<center><br><b>This page is maintained by ";

	pUserIdWidget->EmitHTML(mpStream);

	*mpStream << "</b><br><br></center>\n";

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

	*mpStream << "<br>"
		      << "<hr align=center width=50%>"
			  << "<p font=-1>To create your own About Me page, "
			  << "<a href=\""
//			  << mpMarketPlace->GetMembersPath()
//			  << "services/aboutme/"
			  << mpMarketPlace->GetHTMLPath()
			  << "services/aboutme/"
			  << "aboutme-login.html\">click here</a>. </p> \n";

	// Put the standard footer at the bottom of the user's page.
	*mpStream << mpMarketPlace->GetFooter();

	delete pUserIdWidget;

    CleanUp();
    return;
}



