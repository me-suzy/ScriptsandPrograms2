/* $Id: clseBayAppUserPageUtilities.cpp,v 1.4.204.2 1999/06/10 07:14:39 poon Exp $ */
//
//	File:		clseBayAppUserPageUtilities.cpp
//
//	Class:		clseBayApp
//
//	Author:		Barru
//
//	Function:
//
//			    Helper functions for user pages.
//
//	Modifications:
// 
//
#include "ebihdr.h"
#include "userpage.h" 

// I need to carry around all of the HTML parameters for the
// forms from call to call, between pages. So we pack up
// and unload as we move from page to page. Here, we're 
// unloading, so that we will collect them all again
// when the user posts the form data.
void clseBayApp::WriteTemplateElementsParams(TemplateElements *elements, bool layout /* = true */)
{
	char *pStrippedHTML;

	if (layout)
		*mpStream 
			<<  "<input type=\"hidden\" name=\"templateLayout\" value=\""
			<<  elements->templateLayout
			<<  "\"> \n";

	if (elements->pPageTitle)
	{

		pStrippedHTML = clsUtilities::StripHTML(elements->pPageTitle);

		*mpStream 
			<<  "<input type=\"hidden\" name=\"pageTitle\" value=\""
			<<  pStrippedHTML
			<<  "\"> \n";

		delete [] pStrippedHTML;
	}

	if (elements->pTextAreaTitle1)
	{

		pStrippedHTML = clsUtilities::StripHTML(elements->pTextAreaTitle1);

		*mpStream 
			<<  "<input type=\"hidden\" name=\"textAreaTitle1\" value=\""
			<<  pStrippedHTML
			<<  "\"> \n";

		delete [] pStrippedHTML;
	}

	if (elements->pTextArea1)
	{

		pStrippedHTML = clsUtilities::StripHTML(elements->pTextArea1);

		*mpStream 
			<<  "<input type=\"hidden\" name=\"textArea1\" value=\""
			<<  pStrippedHTML
			<<  "\"> \n";

		delete [] pStrippedHTML;
	}

	if (elements->pTextAreaTitle2)
	{

		pStrippedHTML = clsUtilities::StripHTML(elements->pTextAreaTitle2);

		*mpStream 
			<<  "<input type=\"hidden\" name=\"textAreaTitle2\" value=\""
			<<  pStrippedHTML
			<<  "\"> \n";

		delete [] pStrippedHTML;

	}

	if (elements->pTextArea2)
	{

		pStrippedHTML = clsUtilities::StripHTML(elements->pTextArea2);

		*mpStream 
			<<  "<input type=\"hidden\" name=\"textArea2\" value=\""
			<<  pStrippedHTML
			<<  "\"> \n";

		delete [] pStrippedHTML;
	
	}	

	if (elements->pPictureCaption)
	{

		pStrippedHTML = clsUtilities::StripHTML(elements->pPictureCaption);

		*mpStream 
			<<  "<input type=\"hidden\" name=\"pictureCaption\" value=\""
			<<  pStrippedHTML
			<<  "\"> \n";

		delete [] pStrippedHTML;
	
	}

	*mpStream 
			<<  "<input type=\"hidden\" name=\"pictureURL\" value=\""
			<<  elements->pPictureURL
			<<  "\"> \n";

	if (elements->showUserIdEmail)
		*mpStream 
			<<  "<input type=\"hidden\" name=\"showUserIdEmail\" value=\"yes\"> \n";


	*mpStream 
		<<  "<input type=\"hidden\" name=\"feedbackNumComments\" value=\""
		<<  elements->feedbackNumComments
		<<  "\"> \n";

	*mpStream 
		<<  "<input type=\"hidden\" name=\"itemlistNumItems\" value=\""
		<<  elements->itemlistNumItems
		<<  "\"> \n";

	if (elements->pItemlistCaption)
	{
		pStrippedHTML = clsUtilities::StripHTML(elements->pItemlistCaption);

		*mpStream 
			<<  "<input type=\"hidden\" name=\"itemlistCaption\" value=\""
			<<  pStrippedHTML
			<<  "\"> \n";

		delete [] pStrippedHTML;
	}

	if (elements->pFavoritesDescription1)
	{
		pStrippedHTML = clsUtilities::StripHTML(elements->pFavoritesDescription1);
		*mpStream 
			<<  "<input type=\"hidden\" name=\"favoritesDescription1\" value=\""
			<<  pStrippedHTML
			<<  "\"> \n";

		delete [] pStrippedHTML;
	}

	if (elements->pFavoritesName1)
	{
		pStrippedHTML = clsUtilities::StripHTML(elements->pFavoritesName1);
		*mpStream 
			<<  "<input type=\"hidden\" name=\"favoritesName1\" value=\""
			<<  pStrippedHTML
			<<  "\"> \n";

		delete [] pStrippedHTML;
	}

	if (elements->pFavoritesLink1)
		*mpStream 
			<<  "<input type=\"hidden\" name=\"favoritesLink1\" value=\""
			<<  elements->pFavoritesLink1
			<<  "\"> \n";

	if (elements->pFavoritesDescription2)
	{
		pStrippedHTML = clsUtilities::StripHTML(elements->pFavoritesDescription2);
		*mpStream 
			<<  "<input type=\"hidden\" name=\"favoritesDescription2\" value=\""
			<<  pStrippedHTML
			<<  "\"> \n";

		delete [] pStrippedHTML;
	}

	if (elements->pFavoritesName2)
	{
		pStrippedHTML = clsUtilities::StripHTML(elements->pFavoritesName2);
		*mpStream 
			<<  "<input type=\"hidden\" name=\"favoritesName2\" value=\""
			<<  pStrippedHTML
			<<  "\"> \n";

		delete [] pStrippedHTML;
	}

	if (elements->pFavoritesLink2)
		*mpStream 
			<<  "<input type=\"hidden\" name=\"favoritesLink2\" value=\""
			<<  elements->pFavoritesLink2
			<<  "\"> \n";

	if (elements->pFavoritesDescription3)
	{
		pStrippedHTML = clsUtilities::StripHTML(elements->pFavoritesDescription3);
		*mpStream 
			<<  "<input type=\"hidden\" name=\"favoritesDescription3\" value=\""
			<<  pStrippedHTML
			<<  "\"> \n";

		delete [] pStrippedHTML;
	}
	
	if (elements->pFavoritesName3)
	{
		pStrippedHTML = clsUtilities::StripHTML(elements->pFavoritesName3);
		*mpStream 
			<<  "<input type=\"hidden\" name=\"favoritesName3\" value=\""
			<<  pStrippedHTML
			<<  "\"> \n";

		delete [] pStrippedHTML;
	}

	if (elements->pFavoritesLink3)
		*mpStream 
			<<  "<input type=\"hidden\" name=\"favoritesLink3\" value=\""
			<<  elements->pFavoritesLink3
			<<  "\"> \n";

	*mpStream 
			<<  "<input type=\"hidden\" name=\"item1CaptionChoice\" value=\""
			<<  elements->item1CaptionChoice
			<<  "\"> \n";

	if (elements->item1 > 0)
		*mpStream 
			<<  "<input type=\"hidden\" name=\"item1\" value=\""
			<<  elements->item1
			<<  "\"> \n";

	*mpStream 
			<<  "<input type=\"hidden\" name=\"item2CaptionChoice\" value=\""
			<<  elements->item2CaptionChoice
			<<  "\"> \n";

	if (elements->item2 > 0)
		*mpStream 
			<<  "<input type=\"hidden\" name=\"item2\" value=\""
			<<  elements->item2
			<<  "\"> \n";

	*mpStream 
			<<  "<input type=\"hidden\" name=\"item3CaptionChoice\" value=\""
			<<  elements->item3CaptionChoice
			<<  "\"> \n";

	if (elements->item3 > 0)
		*mpStream 
			<<  "<input type=\"hidden\" name=\"item3\" value=\""
			<<  elements->item3
			<<  "\"> \n";

	if (elements->pageCount)
		*mpStream 
			<<  "<input type=\"hidden\" name=\"pageCount\" value=\"yes\"> \n";

	if (elements->dateTime)
		*mpStream 
			<<  "<input type=\"hidden\" name=\"dateTime\" value=\"yes\"> \n";

	*mpStream 
		<<  "<input type=\"hidden\" name=\"bgPattern\" value=\""
		<<  elements->bgPattern
		<<  "\"> \n";

	return;
}

// When the user is all done, we congratulate them.
void clseBayApp::UserPageShowDonePage(char *pUserId, char *pPassword)
{
	*mpStream <<
		"<h2>Your <i>About Me</i> Page is Ready for Sharing!</h2> \n";

	*mpStream <<
		"<p>Congratulations on creating your About Me page!  \n";

	/*
		"An About Me icon <a href=\""
	<<  "http://www.ebay.com/members/"
	<<  mpUser->GetUserId()
	<<	"/\">"
	<<  "<img src=\""
	<<  mpMarketPlace->GetImagePath()
	<<  "aboutme-small.gif\" border=0></a>"
		" will now appear next to your User ID \n"
		"which lets visitors know that you have an About Me page \n"
		"they can view. All they have to do is click this icon.</p> \n";
	*/

	*mpStream <<
		"<p>Your About Me page is now ready to share with others. "
		"All you need to do is let others know your personal URL "
		"and they will be able to view your About Me page.</p>\n"
		"<p>The URL for your About Me page is <a href=\""
	<<  mpMarketPlace->GetMembersPath()
	<<  "aboutme/"
	<<  mpUser->GetUserId()
	<<	"/\">"
	<<  mpMarketPlace->GetMembersPath()
	<<  "aboutme/"
	<<  mpUser->GetUserId()
	<<	"/</a>. </p> \n";

	*mpStream <<
		"<p>Any time you want to edit your page, simply go to the <a href=\""
	<<  mpMarketPlace->GetHTMLPath()
	<<  "services/aboutme/aboutme-login.html\">"
	    "login page</a>. You can either edit the HTML for your page, "
		"or you can simply recreate your page using the easy 3-step "
		"process you used the first time (select your layout, "
		"enter your information, then view and save your page).</p> \n";

	return;
}

// Given HTML with widgets in tags, draw the puppy.
void clseBayApp::DrawPageFromHTML(char *pHTML)
{
 
	clsTextToWidgets theWidgetParser(sUserOkWidgets, sNumOkWidgets, "<eBay");
	int         length;
	const char *pData;
	char       *pDataStore;

    theWidgetParser.SetText(pHTML);

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
}

void clseBayApp::ExpireThePage()
{
	int			rc;
	time_t		nowTime;
	time_t		expirationTime;
	struct tm  *pExpirationTimeAsTM;
	char		expiresHeader[128];

	// Set the page to expire 1 hour from how
	nowTime				= time(0);
	expirationTime		= nowTime + (60*60);

	pExpirationTimeAsTM	= gmtime(&expirationTime);

	if (pExpirationTimeAsTM)
	{
		// Make it the evil RFC1123 format.
		rc = strftime(expiresHeader,
			 		  sizeof(expiresHeader),
					  "%a, %d %b %Y, %H:%M:%S GMT",
					  pExpirationTimeAsTM);

		if (rc != 0)
		{
			*mpStream <<	"<meta http-equiv=\"Expires\" "
							"content=\""
					  <<	expiresHeader
					  <<	"\">";
		}
	}
}

/*
void clseBayApp::FillTemplateFromHTML(char *pHTML, TemplateElements *elements)
{
	clsTextToWidgets theWidgetParser(sUserOkWidgets, sNumOkWidgets, "<eBay");
	int         length;
	const char *pData;
	char       *pDataStore;

    theWidgetParser.SetText(pHTML);
    pData = theWidgetParser.GetDataDictionary(&length);


}
*/