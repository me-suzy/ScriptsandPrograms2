/* $Id: clseBayAppSaveUserPage.cpp,v 1.2.540.2 1999/08/05 20:42:20 nsacco Exp $ */
// Functions for saving and previewing of user pages.
//
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"
#include "clseBayApp.h"
#include "clsUserPage.h"
#include "clsTextToWidgets.h"
#include "clsWidgetContext.h"
#include "clsWidgetPage.h"

static widgetDesignator sUserOkWidgets[] =
{
    { wtCountWidget, "ViewCount", strlen("ViewCount") },
    { wtUserIdWidget, "UserId", strlen("UserId") },
    { wtFeedbackWidget, "Feedback", strlen("Feedback") },
    { wtItemListWidget, "ItemList", strlen("ItemList") },
    { wtTimeWidget, "Time", strlen("Time") },
    { wtMemberSinceWidget, "MemberSince", strlen("MemberSince") },
	{ wtParagraphWidget, "Paragraph", strlen("Paragraph") },
	{ wtLinkWidget, "Link", strlen("Link") },
	{ wtImageWidget, "Image", strlen("Image") },
	{ wtMarkedTextWidget, "MarkedText", strlen("MarkedText") },
	{ wtItemLinkWidget, "ItemLink", strlen("ItemLink") },
};

static int sNumOkWidgets = sizeof (sUserOkWidgets) / sizeof (widgetDesignator);

void clseBayApp::SaveUserPage(char *pUserId,
                              char *pPassword,
                              char *pText,
                              int page)
{
    clsTextToWidgets theWidgetParser(sUserOkWidgets, sNumOkWidgets, "<eBay");
    clsUserPage thePage;
    int length;
    const char *pData;
    char *pDataStore;
	
    SetUp();

    *mpStream <<    "<html><head>"
                    "<title>"
              <<    mpMarketPlace->GetCurrentPartnerName()
              <<    " Save User Page for "
              <<    pUserId
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
    thePage.SetPageTextSize(strlen(pText));

    theWidgetParser.SetText(pText);
    pData = theWidgetParser.GetDataDictionary(&length);

    pDataStore = new char [length];
    memcpy(pDataStore, pData, length);

    thePage.SetPageSize(length);
    thePage.SetDataDictionary(pDataStore);
    thePage.SavePage();

    mpUser->SetAboutMePage();

	/*
    *mpStream << "<h3>Page saved successfully.</h3><br>"
              << "<A HREF=\""
              << mpMarketPlace->GetCGIPath(PageViewUserPage)
              << "eBayISAPI.dll?ViewUserPage&userid="
              << pUserId
              << "\">Go there now</A><p>";
	*/

	ShowMyPlaceDonePage(pUserId, pPassword);

    *mpStream << mpMarketPlace->GetFooter();

    CleanUp();
    return;
}

void clseBayApp::PreviewUserPage(char *pUserId,
                                 char *pPassword,
                                 char *pText,
								 bool  textSupplied /* = true */)
{
    const char *pOriginalText;
    char       *pCleanText;

    SetUp();

    *mpStream <<    "<html><head>"
                    "<title>"
              <<    mpMarketPlace->GetCurrentPartnerName()
              <<    " Preview User Page for "
              <<    pUserId
              <<    "</title>"
                    "</head>"
              <<    mpMarketPlace->GetHeader();

    mpUser = mpUsers->GetAndCheckUserAndPassword(pUserId,
        pPassword, mpStream, false, (char *) "Preview User Page");

    if (!mpUser)
    {
        CleanUp();
        return;
    }

	*mpStream <<
		"<table border=0 width=100%> \n"
		"  <tr> \n"
		"    <td bgcolor=\"#FFFFCC\">  \n"
		"<h2>Preview Your <i>About Me</i> Page</h2> "
		"<hr> "
		"    </td> \n"
		"  </tr> \n"
		"</table> \n"
		"<p>&nbsp;</p>";

	if (textSupplied)
	{
		// I'm sorry but I can't bring myself to let these be defined
		// at the top of this code, where the object is unnecessarily
		// created if we have supplied text.
		clsTextToWidgets theWidgetParser(sUserOkWidgets, sNumOkWidgets, "<eBay");
		int length;
		const char *pData;
		char *pDataStore;

	    theWidgetParser.SetText(pText);
	    pData = theWidgetParser.GetDataDictionary(&length);

	    pDataStore = new char [length];
	    memcpy(pDataStore, pData, length);

	    // Now that we've parsed it for preview, draw it.
	    clsWidgetPage thePageDraw;
	    long numViews = 0;
	    clsWidgetContext *pContext;

	    thePageDraw.SetPage(pDataStore);
    
		// Set some context up for the users.
	    pContext = thePageDraw.GetContext();
		pContext->SetNumViews(&numViews);
	    pContext->SetUser(mpUser);

	    thePageDraw.Draw(mpStream);

		pCleanText = clsUtilities::StripHTML(pText);

		// DO WE NEED TO FREE pData and/or pContext and/or pDataStore?
	}
	else // Get the page from the database
	{
		clsUserPage thePage;
		thePage.SetPage(0);
		thePage.SetUserId(mpUser->GetId());

		thePage.LoadPage(true);

		if (!thePage.GetDataDictionary())
		{
			// We have no page to display here.
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

        pOriginalText = thePageDraw.GetOriginalText();
        pCleanText = clsUtilities::StripHTML((char *) pOriginalText);

//		*mpStream <<
//			pText;

		// FREE???? pContext?

	}

	*mpStream << 
		"<p>&nbsp;</p> \n"
		"		<table border=0 width=100%> \n"
		"  <tr>\n"
		"    <td bgcolor=\"#FFFFCC\"> \n"
		"<hr> \n"
		"<p>If you click &quot;Return to editing,&quot; you will have the chance to make  \n"
  		" further changes to your About Me page. If you click &quot;Save my page,&quot;  \n"
  		" you will save your page so others can see it, too. (You can always return later  \n"
		" to make adjustments to your page, but you will need to know HTML to do so.)</p> \n"
		"<form method=\"post\" action=\""
	<<  mpMarketPlace->GetCGIPath(PageUserPageHandlePreviewOptions)
	<<  "eBayISAPI.dll\">\n"
        "<INPUT TYPE=\"hidden\" NAME=\"MfcISAPICommand\""
        " VALUE=\"UserPageHandlePreviewOptions\"> \n "
	<<  "  <input type=\"hidden\" name=\"userid\" value=\""
	<<  pUserId
	<<  "\"> \n"
	<<  "  <input type=\"hidden\" name=\"password\" value=\""
	<<  pPassword
	<<  "\"> \n"
		"  <input type=\"submit\" name=\"action\" value=\"Return to editing\"> \n"
		"  <input type=\"submit\" name=\"action\" value=\"Save my page\"> \n";

	// Now store another hidden form so we can make an edit link.

	*mpStream 
		<< "  <input type=\"hidden\" name=\"html\" value=\""
		<< pCleanText
		<< "\">"
		   "</form> \n"
		"	</td> \n"
		"  </tr> \n"
		"</table> \n";		   


	/*
	*mpStream << "<FORM ACTION=\""
			  << mpMarketPlace->GetCGIPath(PageEditUserPage)
			  << "eBayISAPI.dll\" METHOD=POST>\n"
			     "<INPUT TYPE=\"hidden\" NAME=\"MfcISAPICommand\" "
				 "VALUE=\"EditUserPageFromText\">\n"
				 "<INPUT TYPE=\"hidden\" NAME=\"userid\" "
				 "VALUE=\""
			  << pUserId
			  << "\"><INPUT TYPE=\"hidden\" NAME=\"password\" "
				 "VALUE=\""
			  << pPassword
			  << "\"><INPUT TYPE=\"hidden\" NAME=\"text\" "
				"VALUE=\""
			  << pCleanText
			  << "\"><INPUT TYPE=\"hidden\" NAME=\"page\" "
			    "VALUE=\"0\">"
				"<INPUT TYPE=\"Submit\" VALUE=\"Go to Editing\"></FORM>";
	*/

	delete [] pCleanText;

	*mpStream << mpMarketPlace->GetFooter();

    CleanUp();
    return;
}

void clseBayApp::UserPageConfirmChoice(char *pUserId,
							       char *pPassword,
								   char *pHTML,
							       UserPageEnum which)
{
    SetUp();

    *mpStream <<    "<html><head>"
                    "<title>"
              <<    mpMarketPlace->GetCurrentPartnerName()
              <<    " Confirm Your Selection "
              <<    pUserId
              <<    "</title>"
                    "</head>"
              <<    mpMarketPlace->GetHeader();

	*mpStream << 
		"<h2>Are You Sure?</h2> \n"
		"<p>You are about to delete your existing About Me page. If you click &quot;Delete  \n"
		"  and start over&quot; below, you will have the chance to create a new page. If  \n"
		"  you do not really want to delete your existing About Me page, click &quot;Keep  \n"
		"  my page for now.&quot;</p> \n"
		"<form method=\"post\" action=\""
	<<  mpMarketPlace->GetCGIPath(PageUserPageHandleConfirmedChoice)
	<<  "eBayISAPI.dll\">\n"
        "<INPUT TYPE=\"hidden\" NAME=\"MfcISAPICommand\""
        " VALUE=\"UserPageHandleConfirmedChoice\"> \n "
	<<  "  <input type=\"hidden\" name=\"userid\" value=\""
	<<  pUserId
	<<  "\"> \n"
	<<  "  <input type=\"hidden\" name=\"password\" value=\""
	<<  pPassword
	<<  "\"> \n"
	<<  "  <input type=\"hidden\" name=\"which\" value=\""
	<<  which
	<<  "\"> \n"
		"  <table border=0 width=75%> \n"
		"    <tr> \n"
		"      <td> \n"
		"        <input type=\"submit\" name=\"action\" value=\"Delete and start over\"> \n"
		"      </td> \n"
		"      <td> \n"
		"        <input type=\"submit\" name=\"action\" value=\"Keep my page for now\"> \n"
		"      </td> \n"
		"    </tr> \n"
		"  </table> \n"
		"</form> \n";

	*mpStream << mpMarketPlace->GetFooter();

    CleanUp();
    return;
}