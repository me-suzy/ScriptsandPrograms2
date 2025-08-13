/* $Id: clseBayAppUserPagePreview.cpp,v 1.3.236.2.34.2 1999/08/05 20:42:22 nsacco Exp $ */
//
//	File:		clseBayAppUserPagePreview.cpp
//
//	Class:		clseBayApp
//
//	Author:		Chad, Barry
//
//	Function:
//
//			    Preview your user page.
//
//	Modifications:
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
// 
//		Soc  Bug 2683  Move Action Buttons up before preview html incase preview html
//                     has a html bug in it 
//

#include "ebihdr.h"
#include "userpage.h"


void clseBayApp::UserPageGoToTemplatePreview(CEBayISAPIExtension *pThis,
											 char *pUserId,
											 char *pPassword,
											 TemplateElements *elements)
{
	SetUp();

	// Trying to set the expiration date so that the page doesn't
	// expire when you've been editing for a while and then decide
	// to use the forward and back buttons on the browser.

    *mpStream <<    "<html><head>";

	ExpireThePage();

	*mpStream <<    "<meta http-equiv=\"Expires\" content=\"01 Jan 2010, 00:00:00 GMT\"> \n"
                    "<title>"
              <<    mpMarketPlace->GetCurrentPartnerName()
              <<    " Preview User Page for "
              <<    pUserId
              <<    "</title>"
                    "</head>"
		      <<	mpMarketPlace->GetHeader();
//		      <<	mpMarketPlace->GetAboutMeHeader();


	*mpStream <<
		"<CENTER> \n"
		"<TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0 WIDTH=580> \n"
		"	<TR> \n"
		"      <TD VALIGN=TOP bgcolor=\"#99CCCC\"> <FONT SIZE=5><B>About Me</B></FONT> As easy as 1,  \n"
		"        2, <font color=#FF0000><b>3</b></font> <BR> \n"
		"        <B><font color=#FF0000>Step 3</font></B> Preview your About Me page.  \n"
		"        <p></P> \n"
		"		</TD> \n"
		"	</TR> \n"
		"</TABLE> \n"
		"</CENTER> \n"
		"<BR> \n";

	mpUser = mpUsers->GetAndCheckUser(pUserId, mpStream);
	if (!mpUser)
	{
		// This should never happen...
		CleanUp();
		return;
	}

	if (!elements)
	{
		// Populate the elements array from what's saved in
		// the database?
	}

	UserPageConvertTemplateToHTML(mpStream, elements, true);

	*mpStream << 
		" \n <center> \n"
		"<p>&nbsp;</p> \n"
		"		<table border=0 width=100%> \n"
		"  <tr>\n"
		"    <td bgcolor=\"#99CCCC\"> \n"
		"<hr> \n"
		"<form method=\"post\" action=\""
	<<  mpMarketPlace->GetCGIPath(PageUserPageHandleTemplatePreviewOptions)
	<<  "eBayISAPI.dll\">\n"
        "<INPUT TYPE=\"hidden\" NAME=\"MfcISAPICommand\""
        " VALUE=\"UserPageHandleTemplatePreviewOptions\"> \n "
	<<  "  <input type=\"hidden\" name=\"userid\" value=\""
	<<  pUserId
	<<  "\"> \n"
	<<  "  <input type=\"hidden\" name=\"password\" value=\""
	<<  pPassword
	<<  "\"> \n";


	WriteTemplateElementsParams(elements);

	// petra make each button name unique
	*mpStream <<
		"  <center> \n"
		"  <input type=\"submit\" name=\"action1\" value=\"Edit some more\">  &nbsp; &nbsp; &nbsp; &nbsp; \n"
		"  <input type=\"submit\" name=\"action2\" value=\"Save my page\">  &nbsp; &nbsp; &nbsp; &nbsp; \n"
		"  <input type=\"submit\" name=\"action3\" value=\"Edit using HTML\"> &nbsp; &nbsp; &nbsp; &nbsp; \n"
		"  <input type=\"submit\" name=\"action4\" value=\"Start over\"> \n"
		"  </center> \n"
		"    </td> \n"
		"  </tr> \n"
		"</table> \n"
		"  </center>\n";

	*mpStream << mpMarketPlace->GetFooter();

	CleanUp();
	return;
}

// Preview the page when editing in HTML mode.
void clseBayApp::UserPageGoToHTMLPreview(CEBayISAPIExtension *pThis,
									 char *pUserId,
								     char *pPassword,
								     char *pHTML,
									 bool  htmlSupplied /* = true */,
								     int   page /* = 0 */)
{
    const char *pOriginalText;
    char       *pCleanText;

    SetUp();

	if (htmlSupplied)
	{
    *mpStream <<    "<html><head>";

	ExpireThePage();

	*mpStream <<    "<title>"
              <<    mpMarketPlace->GetCurrentPartnerName()
              <<    " Preview User Page for "
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
        CleanUp();
        return; 
    }

	// Soc 2683 -add start over button at the top too
    clsWidgetPage thePageDraw; 
	bool bDataDictionary = false;
	if (htmlSupplied)
	{
		pCleanText = clsUtilities::StripHTML(pHTML);
	}
	else // Get the page from the database
	{
		clsUserPage thePage;
		thePage.SetPage(0);
		thePage.SetUserId(mpUser->GetId());

		thePage.LoadPage(true);

		if (thePage.GetDataDictionary())
		{
			bDataDictionary = true;
			// Cool. We now have a page. Let's load it up in the display thing
			// and run it through, shall we?
			long numViews = thePage.GetNumViews();
			clsWidgetContext *pContext;

			thePageDraw.SetPage(thePage.GetDataDictionary());
    
			// Set some context up for the users.
			pContext = thePageDraw.GetContext();
			pContext->SetNumViews(&numViews);
			pContext->SetUser(mpUser);
	
			////thePageDraw.Draw(mpStream);

			pOriginalText = thePageDraw.GetOriginalText();
			pCleanText = clsUtilities::StripHTML((char *) pOriginalText);
		}
		else
			pCleanText = NULL;

	}

	*mpStream <<	"<form method=\"post\" action=\""
	<<  mpMarketPlace->GetCGIPath(PageUserPageHandleHTMLPreviewOptions)
	<<  "eBayISAPI.dll\">\n"
        "<INPUT TYPE=\"hidden\" NAME=\"MfcISAPICommand\""
        " VALUE=\"UserPageHandleHTMLPreviewOptions\"> \n "
	<<  "  <input type=\"hidden\" name=\"userid\" value=\""
	<<  pUserId
	<<  "\"> \n"
	<<  "  <input type=\"hidden\" name=\"password\" value=\""
	<<  pPassword
	<<  "\"> \n";

	if (htmlSupplied ||
		bDataDictionary)
	{
	*mpStream <<
		"<table border=0 width=100%> \n"
		"  <tr> \n"
		"    <td bgcolor=\"#99CCCC\">  \n"
				"<center>"
				"<h2>Preview Your <i>About Me</i> Page</h2>"
				"<hr> " //2683
				// 2683 add buttons at the top of page too
				"  <input type=\"submit\" name=\"action\" value=\"Edit some more\">  &nbsp; &nbsp;&nbsp; &nbsp; \n"
				"  <input type=\"submit\" name=\"action\" value=\"Save my page\">  &nbsp; &nbsp; &nbsp; &nbsp; \n"
				"  <input type=\"submit\" name=\"action\" value=\"Start over\">  \n"
				"<hr> " //2683
				"</center>"
		"    </td> \n"
		"  </tr> \n"
		"</table> \n";

	// Now store another hidden form so we can make an edit link.
	*mpStream 
		<< "  <input type=\"hidden\" name=\"html\" value=\""
		<< pCleanText
		<< "\">"
		"</form> \n" // 2683 
		"<p>&nbsp;</p>"; 
	}
	else
	{
	*mpStream <<
		"<table border=0 width=100%> \n"
		"  <tr> \n"
		"    <td bgcolor=\"#99CCCC\">  \n"
				"<center>"
				"<h2>Preview Your <i>About Me</i> Page</h2>"
				"<hr> " //2683
				// 2683 add buttons at the top of page too
				"  <input type=\"submit\" name=\"action\" value=\"Start over\">  \n"
				"<hr> " //2683
				"</center>"
		"    </td> \n"
		"  </tr> \n"
		"</table> \n" 
		"</form> \n" // 2683 
		"<p>&nbsp;</p>"; 
	}

	if (htmlSupplied)
	{
		DrawPageFromHTML(pHTML);  
	}
	else // Get the page from the database
	{
		if (!bDataDictionary)
		{
			// We have no page to display here.
			// TO DO: Better page needs to be output here!

	        *mpStream << "<H2>Page was not found.</H2><p>\n";
		    *mpStream << mpMarketPlace->GetFooter();

			CleanUp();
			return;
		}

		thePageDraw.Draw(mpStream);
	}

	// petra make button names unique
	*mpStream << 
		"<p>&nbsp;</p> \n"
		"		<table border=0 width=100%> \n" 
		"  <tr>\n"
		"    <td bgcolor=\"#99CCCC\"> \n"
		"<hr> \n"
		"<form method=\"post\" action=\""
	<<  mpMarketPlace->GetCGIPath(PageUserPageHandleHTMLPreviewOptions)
	<<  "eBayISAPI.dll\">\n"
        "<INPUT TYPE=\"hidden\" NAME=\"MfcISAPICommand\""
        " VALUE=\"UserPageHandleHTMLPreviewOptions\"> \n "
	<<  "  <input type=\"hidden\" name=\"userid\" value=\""
	<<  pUserId
	<<  "\"> \n"
	<<  "  <input type=\"hidden\" name=\"password\" value=\""
	<<  pPassword
	<<  "\"> \n"
	    " <center> \n"
		"  <input type=\"submit\" name=\"action1\" value=\"Edit some more\">  &nbsp; &nbsp;&nbsp; &nbsp; \n"
		"  <input type=\"submit\" name=\"action2\" value=\"Save my page\">  &nbsp; &nbsp; &nbsp; &nbsp; \n"
		"  <input type=\"submit\" name=\"action3\" value=\"Start over\">  \n"
		"  </center> \n";

	// Now store another hidden form so we can make an edit link.
	*mpStream 
		<< "  <input type=\"hidden\" name=\"html\" value=\""
		<< pCleanText
		<< "\">"
		<< "</form> \n"
		"   <hr> \n"
		"	</td> \n"
		"  </tr> \n"
		"</table> \n";		   

	*mpStream << "</form> \n";

	delete [] pCleanText;


	if (htmlSupplied)
		*mpStream << mpMarketPlace->GetFooter();

    CleanUp();
    return;
}

