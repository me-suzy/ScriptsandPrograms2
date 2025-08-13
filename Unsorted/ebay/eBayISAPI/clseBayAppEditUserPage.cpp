/* $Id: clseBayAppEditUserPage.cpp,v 1.2.348.2.90.2 1999/08/05 20:42:14 nsacco Exp $ */
// Let's a user edit, either from the database or from text,
// their user page.
//
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"
#include "clseBayApp.h"
#include "clsUserPage.h"
#include "clsWidgetPage.h"

void clseBayApp::EditUserPageFromText(char *pUserId,
									  char *pPassword,
									  char *pText,
									  int page /* = 0 */)
{
	char *pCleanedText;

	SetUp();

	*mpStream <<	"<html><head>"
                    "<title>"
              <<    mpMarketPlace->GetCurrentPartnerName()
              <<    " Edit User Page for "
              <<    pUserId
              <<    "</title>";

	// Don't end the header yet -- the JavaScript portion needs
	// to go in there.

    mpUser = mpUsers->GetAndCheckUserAndPassword(pUserId,
        pPassword, mpStream, 
		true, NULL, false, false, false, false, false, true);


    if (!mpUser)
    {
        CleanUp();
        return;
    }

    pCleanedText = clsUtilities::StripHTML(pText);

	/*
    *mpStream << "<FORM ACTION=\""
              << mpMarketPlace->GetCGIPath(PageSaveUserPage)
              << "eBayISAPI.dll\" METHOD=POST>\n"
                 "<INPUT TYPE=\"hidden\" NAME=\"MfcISAPICommand\""
                 " VALUE=\"SaveUserPage\">\n"
                 "<INPUT TYPE=\"hidden\" NAME=\"userid\""
                 " VALUE=\""
              << pUserId
              << "\"><INPUT TYPE=\"hidden\" NAME=\"password\""
                 " VALUE=\""
              << pPassword
              << "\"><br><TEXTAREA NAME=\"text\" ROWS=8 COLS=56>"
              << pCleanedText
              << "</TEXTAREA><p>\n"
                 "<INPUT TYPE=\"hidden\" NAME=\"page\" VALUE=\"0\">"
                 "<INPUT TYPE=\"submit\" NAME=\"preview\" VALUE=\"Preview\">\n"
                 "<INPUT TYPE=\"submit\" NAME=\"preview\" VALUE=\"Save It!\">"
                 "<INPUT TYPE=\"reset\" VALUE=\"Reset\">"
                 "</FORM>\n";
	*/

	ShowEditingPage(pUserId, pPassword, pCleanedText);

    *mpStream << flush;

    delete [] pCleanedText;

    *mpStream << mpMarketPlace->GetFooter();
    
    CleanUp();
    return;
}

void clseBayApp::EditUserPage(char *pUserId,
                              char *pPassword,
                              int page /* = 0 */)
{
    clsWidgetPage theWidgetPage;
    clsUserPage thePage;
    const char *pOriginalText;
    char *pCleanedText;

    SetUp();

    *mpStream <<    "<html><head>"
                    "<title>"
              <<    mpMarketPlace->GetCurrentPartnerName()
              <<    " Edit User Page for "
              <<    pUserId
              <<    "</title>";

	// Don't end the header yet -- the JavaScript portion needs
	// to go in there.

    mpUser = mpUsers->GetAndCheckUserAndPassword(pUserId,
        pPassword, mpStream, 
		true, NULL, false, false, false, false, false, true);

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

	/*
    *mpStream << "<FORM ACTION=\""
              << mpMarketPlace->GetCGIPath(PageSaveUserPage)
              << "eBayISAPI.dll\" METHOD=POST>\n"
                 "<INPUT TYPE=\"hidden\" NAME=\"MfcISAPICommand\""
                 " VALUE=\"SaveUserPage\">\n"
                 "<INPUT TYPE=\"hidden\" NAME=\"userid\""
                 " VALUE=\""
              << pUserId
              << "\"><INPUT TYPE=\"hidden\" NAME=\"password\""
                 " VALUE=\""
              << pPassword
              << "\"><br><TEXTAREA NAME=\"text\" ROWS=8 COLS=56>";

    if (pCleanedText)
        *mpStream
              << pCleanedText;

    *mpStream << "</TEXTAREA><p>\n"
                 "<INPUT TYPE=\"hidden\" NAME=\"page\" VALUE=\"0\">"
                 "<INPUT TYPE=\"submit\" NAME=\"preview\" VALUE=\"Preview\">\n"
                 "<INPUT TYPE=\"submit\" NAME=\"preview\" VALUE=\"Save It!\">"
                 "<INPUT TYPE=\"reset\" VALUE=\"Reset\">"
                 "</FORM>\n";

	*/

	ShowEditingPage(pUserId, pPassword, pCleanedText);

    *mpStream << flush;

    delete [] pCleanedText;

    *mpStream << mpMarketPlace->GetFooter();
    
    CleanUp();
    return;
}

void clseBayApp::UserPageLogin(char *pUserId,
							  char *pPassword)
{
	SetUp();

	*mpStream <<	"<html><head>"
                    "<title>"
              <<    mpMarketPlace->GetCurrentPartnerName()
              <<    " - About Me Options"
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

	if (mpUser->HasAboutMePage())
	{
		ShowMyPlaceManagerOptions(pUserId, pPassword);
		*mpStream << mpMarketPlace->GetFooter();
	}
	else
	{
		SelectCreationStyles(pUserId, pPassword, false);
		*mpStream << mpMarketPlace->GetFooter();
	}

	CleanUp();
	return;
}

//
// Handlers
//

void clseBayApp::SelectCreationStyles(char *pUserId,
									  char *pPassword,
									  bool doSetup /* = false */)
{
	if (doSetup) 
	{
		SetUp();

		*mpStream <<	"<html><head>"
			            "<title>"
				  <<    mpMarketPlace->GetCurrentPartnerName()
				  <<    " - Creation Styles"
	              <<    "</title>"
					    "</head>"
				  <<    mpMarketPlace->GetHeader();
	}

	*mpStream <<
		"<h2>Create Your <i>About Me</i> Page</h2> \n"
		"<p>You can create your About Me page in one of two ways. </p> \n"
		"<form method=\"post\" action=\""
	<<  mpMarketPlace->GetCGIPath(PageUserPageHandleCreationOptions)
	<<  "eBayISAPI.dll"
	<<  "\"> \n"
	    "<INPUT TYPE=\"hidden\" NAME=\"MfcISAPICommand\""
        " VALUE=\"UserPageHandleCreationOptions\">\n"
        "<INPUT TYPE=\"hidden\" NAME=\"userid\""
        " VALUE=\""
    << pUserId
    << "\"><INPUT TYPE=\"hidden\" NAME=\"password\""
       " VALUE=\""
    << pPassword
    << "\"> \n";

	*mpStream <<
		"  <p>1.  \n"
		"    <input type=\"submit\" name=\"action\" value=\"Start with a template\"> \n"
		"  </p> \n"
		"  <p>Starting with a template will help you create an About Me page without requiring  \n"
		"    you to know HTML. This is the easiest and fastest way to create an About Me  \n"
		"    page. If you want to do more than the templates will allow, don't worry: you  \n"
		"    can always fully customize the page you create later. </p> \n"
		"  <p>2.  \n"
		"    <input type=\"submit\" name=\"action\" value=\"Enter HTML directly\"> \n"
		"  </p> \n"
		"  <p>Entering HTML directly is the most flexible option. If you are creating a  \n"
		"    custom storefront and you know HTML, you may wish to enter your HTML directly,  \n"
		"    from scratch. </p> \n"
		"  </form> \n";

	if (doSetup) 
	{
		*mpStream << mpMarketPlace->GetFooter();

		CleanUp();
	}

	return;
}

void clseBayApp::SelectTemplateStyles(char *pUserId,
									  char *pPassword,
									  TemplateElements *elements)
{
	SetUp();

	*mpStream <<	"<html><head>"
                    "<title>"
              <<    mpMarketPlace->GetCurrentPartnerName()
              <<    " - About Me Styles"
              <<    "</title>"
                    "</head>"
              <<    mpMarketPlace->GetHeader();

	*mpStream <<
  		"<h2>Build a <i>My Place</i> Page: Step 1 of 2 </h2> \n"
  		"<b>Choose a Layout</b>  \n"
  		"<p>To create a My Place page based on a template, you can start by choosing a  \n"
    	"  layout. (Once you're happy with the layout, you will select the things to go  \n"
  		"  on your page -- such as a welcome message, a picture, your eBay feedback, and  \n"
  		"  more.)</p> \n"
  		"<p>Select from one of these layouts: by clicking Side-by-side, Newspaper, or Centered.</p> \n"
  		"<form method=\"post\" action=\""
	<<  mpMarketPlace->GetCGIPath(PageUserPageHandleStyleOptions)
	<<  "eBayISAPI.dll"
	<<  "\"> \n"
	    "<INPUT TYPE=\"hidden\" NAME=\"MfcISAPICommand\""
        " VALUE=\"UserPageHandleStyleOptions\">\n"
        "<INPUT TYPE=\"hidden\" NAME=\"userid\""
        " VALUE=\""
    << pUserId
    << "\"><INPUT TYPE=\"hidden\" NAME=\"password\""
       " VALUE=\""
    << pPassword
    << "\"> \n";

	if (elements)
		WriteTemplateElementsParams(elements);

	*mpStream <<
  		" <table border=1 width=75%> \n"
  		"    <tr>  \n"
  		"      <td width=27% bgcolor=\"dddddd\">  \n"
  		"        <div align=center><b>  \n"
  		"          <input type=\"submit\" name=\"action\" value=\"Side-by-side\"> \n"
  		"          </b></div> \n"
  		"      </td> \n"
  		"      <td width=27% bgcolor=\"dddddd\"><font size=2>Show your own information  \n"
  		"        on the left, and your eBay information on the right.</font></td> \n"
  		"      <td width=73%> <img src=\""
	<<  mpMarketPlace->GetImagePath()
	<<  "template1.gif\" width=155 height=102> </td> \n"
  		"    </tr> \n"
  		"    <tr>  \n"
  		"      <td width=27% bgcolor=\"dddddd\">  \n"
  		"        <div align=center><b>  \n"
  		"          <input type=\"submit\" name=\"action\" value=\"Newspaper\"> \n"
  		"          </b></div> \n"
  		"      </td> \n"
  		"      <td width=27% bgcolor=\"dddddd\"><font size=2>Show your own information  \n"
  		"        in a newspaper format, with eBay information beneath that.</font></td> \n"
  		"      <td width=73%>  \n"
  		"        <p><img src=\""
	<<  mpMarketPlace->GetImagePath()
	<<  "template2.gif\" width=155 height=102> </p> \n"
  		"      </td> \n"
  		"    </tr> \n"
  		"    <tr>  \n"
  		"      <td width=27% bgcolor=\"dddddd\">  \n"
  		"        <div align=center><b>  \n"
  		"          <input type=\"submit\" name=\"action\" value=\"Centered\"> \n"
  		"          </b></div> \n"
  		"      </td> \n"
  		"      <td width=27% bgcolor=\"dddddd\"><font size=2>Center each block of information  \n"
  		"        on the page. </font></td> \n"
  		"      <td width=73%><img src=\""	
	<<  mpMarketPlace->GetImagePath()
	<< "template3.gif\" width=155 height=102> </td> \n"
  		"    </tr> \n"
  		"  </table> \n"
  		"  </form> \n";

	*mpStream << mpMarketPlace->GetFooter();

	CleanUp();

	return;
}

void clseBayApp::SelectTemplateElements(char *pUserId,
									    char *pPassword,
									    TemplateElements *elements)
{
	int val;
	int entryVals[] = {0, 10, 25, 50, 100};

	SetUp();

	*mpStream <<	"<html><head>"
                    "<title>"
              <<    mpMarketPlace->GetCurrentPartnerName()
              <<    " - Select Template Elements"
              <<    "</title>"
                    "</head>"
              <<    mpMarketPlace->GetHeader();

	*mpStream <<
		"<h2 align=left>Build Your My Place Page: Step 2 of 2</h2> \n"
		"<div align=left> \n"
		"  <p><b>Pick the Elements for Your <i>My Place</i> Page </b></p> \n"
		"  <p>Select different elements you'd like to include on your My Place page. You  \n"
		"    can choose any combination of elements. These will be arranged according to  \n"
		"    the layout you chose on the previous page. </p> \n"
		"</div> \n";

	*mpStream <<
		"<form method=\"post\" action=\""
	<<  mpMarketPlace->GetCGIPath(PageUserPageHandleTemplateOptions)
	<<  "eBayISAPI.dll"
	<<  "\"> \n"
	    "<INPUT TYPE=\"hidden\" NAME=\"MfcISAPICommand\""
        " VALUE=\"UserPageHandleTemplateOptions\">\n"
        "<INPUT TYPE=\"hidden\" NAME=\"userid\""
        " VALUE=\""
    << pUserId
    << "\"><INPUT TYPE=\"hidden\" NAME=\"password\""
       " VALUE=\""
    << pPassword
    << "\"> \n";

	*mpStream <<
		"  <p align=left><b>HTML elements you can include</b></p> \n"
		"  <div align=left> \n"
		"    <table border=1 width=100%> \n"
		"      <tr>  \n";

	// TITLE

	*mpStream <<
		"        <td width=27% bgcolor=\"dddddd\">  \n"
		"          <b>Page title</b></td> \n"
		"        <td width=27% bgcolor=\"dddddd\"><font size=2>Show a page title.</font></td> \n"
		"        <td width=73%>  \n"
		"          <input type=\"text\" name=\"titletext\" size=50 ";

	if (elements->pTitleText)
		*mpStream 
			<< "value=\""
			<< elements->pTitleText
			<< "\"";

	*mpStream <<
		"> \n"
		"        </td> \n"
		"      </tr> \n";

	// WELCOME

	*mpStream <<
		"      <tr>  \n"
		"        <td width=27% bgcolor=\"dddddd\">  \n"
		"          <b>Welcome message</b></td> \n"
		"        <td width=27% bgcolor=\"dddddd\"><font size=2>Show a paragraph that  \n"
		"          greets someone viewing your page.</font></td> \n"
		"        <td width=73%>  \n"
		"          <p>  \n"
		"            <textarea name=\"welcometext\" cols=50 rows=6>";
	
	if (elements->pWelcomeText)
		*mpStream << elements->pWelcomeText;

	*mpStream <<
		"</textarea> \n"
		"          </p> \n"
		"        </td> \n"
		"      </tr> \n";

	// PICTURE

	*mpStream <<
		"      <tr>  \n"
		"        <td width=27% bgcolor=\"dddddd\">  \n"
		"          <b>Picture</b></td> \n"
		"        <td width=27% bgcolor=\"dddddd\"><font size=2>Show a picture that you've  \n"
		"          posted on the Web. </font></td> \n"
		"        <td width=73%>  \n"
		"          <input type=\"text\" name=\"pictureURL\" size=50 value=";
	
	*mpStream << "\""
			  << elements->pPictureURL
			  << "\" \n";

	*mpStream <<
		"        </td> \n"
		"      </tr> \n"
		"    </table> \n"
		"  </div> \n";

	*mpStream <<
		"  <p align=left><b><br> \n"
		"    eBay things you can include</b></p> \n"
		"  <div align=left> \n"
		"    <table border=1 width=100%> \n";

	// User ID

	*mpStream <<
		"      <tr>  \n"
		"        <td bgcolor=\"dddddd\" width=27%>  \n"
		"          <input type=\"checkbox\" name=\"showuserid\" value=\"yes\" ";
		
	if (elements->showuserid)
		*mpStream << "checked";

	*mpStream <<
		"> \n"
		"          <b>User ID</b><br> \n"
		"        </td> \n"
		"        <td bgcolor=\"dddddd\" width=27%><font size=2>Show your User ID.</font></td> \n"
		"        <td width=73%>  \n"
		"          <input type=\"checkbox\" name=\"showuseridEmail\" value=\"yes\" ";

	if (elements->showuseridEmail)
		*mpStream << "checked";

	*mpStream <<
		"> \n"
		"          Show email  \n"
		"          <input type=\"checkbox\" name=\"showuseridRating\" value=\"yes\" ";
	
	if (elements->showuseridRating)
		*mpStream << "checked";

	*mpStream << 
		"> \n"
		"          Show feedback rating</td> \n"
		"      </tr> \n";

	// MEMBER SINCE

	*mpStream <<
		"      <tr>  \n"
		"        <td bgcolor=\"dddddd\" width=27%>  \n"
		"          <input type=\"checkbox\" name=\"membersince\" value=\"yes\" ";
	
	if (elements->membersince)
		*mpStream << "checked";

	*mpStream <<
		"> \n"
		"          <b>Member since</b><br> \n"
		"        </td> \n"
		"        <td bgcolor=\"dddddd\" width=27%><font size=2>Show when you first joined  \n"
		"          eBay.</font></td> \n"
		"        <td width=73%>&nbsp;</td> \n"
		"      </tr> \n";

	// FEEDBACK

	*mpStream <<
		"      <tr>  \n"
		"        <td bgcolor=\"dddddd\" width=27%>  \n"
		"          <input type=\"checkbox\" name=\"feedback\" value=\"yes\" ";
	
	if (elements->feedback)
		*mpStream << "checked";

	*mpStream <<
		"> \n"
		"          <b>Feedback</b><br> \n"
		"        </td> \n"
		"        <td bgcolor=\"dddddd\" width=27%><font size=2>Show your feedback comments.</font></td> \n"
		"        <td width=73%>Show  \n"
		"          <select name=\"feedbackNumComments\"> \n";

	if (elements->feedback)
	{
		for (val = 0; val < 5; val++) 
		{
			int numComments = entryVals[val];

			*mpStream << "<option value="
					  << numComments;

			if (elements->feedbackNumComments == numComments)
				*mpStream << " selected";

			*mpStream << ">"
					  << numComments
					  << "</option> \n";
		}
	}
	else
	{
		*mpStream <<
		"            <option value=0>all</option> \n"
		"            <option value=10>10</option> \n"
		"            <option value=25>25</option> \n"
		"            <option value=50>50</option> \n"
		"            <option value=100>100</option> \n";
	}

	*mpStream <<
		"          </select> \n"
		"          comments </td> \n"
		"      </tr> \n";

	// ITEM LIST

	*mpStream <<
		"      <tr>  \n"
		"        <td bgcolor=\"dddddd\" width=27%>  \n"
		"          <p>  \n"
		"            <input type=\"checkbox\" name=\"itemlist\" value=\"yes\" ";
	
	if (elements->itemlist)
		*mpStream << "checked";
	
	*mpStream <<
		"> \n"
		"            <b>Items for sale</b><br> \n"
		"          </p> \n"
		"        </td> \n"
		"        <td bgcolor=\"dddddd\" width=27%><font size=2>Show your current items  \n"
		"          for sale.</font></td> \n"
		"        <td width=73%>  \n"
		"          <p>Show  \n"
		"            <select name=\"itemlistNumItems\"> \n";

	if (elements->itemlist)
	{
		for (val = 0; val < 5; val++) 
		{
			int numItems = entryVals[val];

			*mpStream << "<option value="
					  << numItems;

			if (elements->itemlistNumItems == numItems)
				*mpStream << " selected";

			*mpStream << ">"
					  << numItems
					  << "</option> \n";
		}
	}
	else
	{
		*mpStream <<
		"            <option value=0>all</option> \n"
		"            <option value=10>10</option> \n"
		"            <option value=25>25</option> \n"
		"            <option value=50>50</option> \n"
		"            <option value=100>100</option> \n";
	}

	*mpStream <<
		"            </select> \n"
		"            items for sale</p> \n"
		"          <p>Caption:  \n"
		"            <input type=\"text\" name=\"itemlistCaption\" size=50 ";
	
	if (elements->pItemlistCaption)
		*mpStream << "value=\""
				  << elements->pItemlistCaption
				  << "\"";

	*mpStream <<
		"> \n"
		"          </p> \n"
		"        </td> \n"
		"      </tr> \n"
		"    </table> \n"
		"  </div> \n";

	// FAVORITE LINKS

	*mpStream <<
		"  <p align=left><b><br> \n"
		"    Other fun things you can include</b></p> \n"
		"  <div align=left> \n"
		"    <table border=1 width=100%> \n"
		"      <tr>  \n"
		"        <td bgcolor=\"dddddd\" width=27%>  \n"
		"          <b>Favorite links</b></td> \n"
		"        <td bgcolor=\"dddddd\" width=27%><font size=2>Help others find your  \n"
		"          favorite things on the Web or at eBay.</font></td> \n"
		"        <td width=73%>  \n"
		"          <table border=0 width=100%> \n"
		"            <tr>  \n"
		"              <td>Name: </td> \n"
		"              <td>  \n"
		"                <input type=\"text\" name=\"favoritesName1\" size=50 ";

	if (elements->pFavoritesName1)
		*mpStream << "value=\""
		          << elements->pFavoritesName1
				  << "\"";

	*mpStream <<
		"> \n"
		"              </td> \n"
		"            </tr> \n"
		"            <tr>  \n"
		"              <td>Link: </td> \n"
		"              <td>  \n"
		"                <input type=\"text\" name=\"favoritesLink1\" size=50 ";
	
	if (elements->pFavoritesLink1 == NULL)
		*mpStream << "value=\"http://\"";
	else
		*mpStream << "value=\""
		          << elements->pFavoritesLink1
				  << "\"";
	
	*mpStream <<
		"> \n"
		"              </td> \n"
		"            </tr> \n"
		"          </table> \n"
		"          <br> \n"
		"          <table border=0 width=100%> \n"
		"            <tr>  \n"
		"              <td>Name: </td> \n"
		"              <td>  \n"
		"                <input type=\"text\" name=\"favoritesName2\" size=50 ";

	if (elements->pFavoritesName2)
		*mpStream << "value=\""
		          << elements->pFavoritesName2
				  << "\"";
	
	*mpStream <<
		"> \n"
		"              </td> \n"
		"            </tr> \n"
		"            <tr>  \n"
		"              <td>Link: </td> \n"
		"              <td>  \n"
		"                <input type=\"text\" name=\"favoritesLink2\" size=50 ";

	if (elements->pFavoritesLink2 == NULL)
		*mpStream << "value=\"http://\"";
	else
		*mpStream << "value=\""
		          << elements->pFavoritesLink2
				  << "\"";
	
	*mpStream <<
		"> \n"
		"              </td> \n"
		"            </tr> \n"
		"          </table> \n"
		"          <br> \n"
		"          <table border=0 width=100%> \n"
		"            <tr>  \n"
		"              <td>Name: </td> \n"
		"              <td>  \n"
		"                <input type=\"text\" name=\"favoritesName3\" size=50 ";

	if (elements->pFavoritesName3)
		*mpStream << "value=\""
		          << elements->pFavoritesName3
				  << "\"";
	
	*mpStream <<
		"> \n"
		"              </td> \n"
		"            </tr> \n"
		"            <tr>  \n"
		"              <td>Link: </td> \n"
		"              <td>  \n"
		"                <input type=\"text\" name=\"favoritesLink3\" size=50 ";
	
	if (elements->pFavoritesLink3 == NULL)
		*mpStream << "value=\"http://\"";
	else
		*mpStream << "value=\""
		          << elements->pFavoritesLink3
				  << "\"";

	*mpStream <<
		"> \n"
		"              </td> \n"
		"            </tr> \n"
		"          </table> \n"
		"        </td> \n"
		"      </tr> \n";

	// ITEMS ON EBAY

	*mpStream <<
		"      <tr>  \n"
		"        <td bgcolor=\"dddddd\" width=27%>  \n"
		"          <b>Favorite items</b></td> \n"
		"        <td bgcolor=\"dddddd\" width=27%><font size=2>Share your eBay &quot;finds&quot;  \n"
		"          with others.</font></td> \n"
		"        <td width=73%>  \n"
		"          <p><br> \n"
		"            Item #:  \n"
		"            <input type=\"text\" name=\"item1\" value=";
	
	if (elements->item1)
		*mpStream << elements->item1;
	else 
		*mpStream << "\"\"";

	*mpStream <<
		"> \n"
		"          </p> \n"
		"          <p>Item #:  \n"
		"            <input type=\"text\" name=\"item2\" value=";
	
	if (elements->item2)
		*mpStream << elements->item2;
	else 
		*mpStream << "\"\"";

	*mpStream <<
		"> \n"
		"          </p> \n"
		"          <p>Item #:  \n"
		"            <input type=\"text\" name=\"item3\" value=";

	if (elements->item3)
		*mpStream << elements->item3;
	else 
		*mpStream << "\"\"";

	*mpStream <<
		"> \n"
		"            <br> \n"
		"          </p> \n"
		"        </td> \n"
		"      </tr> \n";

	// PAGE COUNT

	*mpStream <<
		"      <tr>  \n"
		"        <td bgcolor=\"dddddd\" width=27%>  \n"
		"          <input type=\"checkbox\" name=\"pagecount\" value=\"yes\" ";
		
	if (elements->pagecount)
		*mpStream << "checked";
	
	*mpStream <<
		"> \n"
		"          <b>Page counter</b></td> \n"
		"        <td bgcolor=\"dddddd\" width=27%><font size=2>Show how many times someone  \n"
		"          has seen this page.</font></td> \n"
		"        <td width=73%>&nbsp;</td> \n"
		"      </tr> \n";

	// DATE AND TIME
	*mpStream <<
		"      <tr>  \n"
		"        <td bgcolor=\"dddddd\" width=27%>  \n"
		"          <input type=\"checkbox\" name=\"datetime\" value=\"yes\" ";
		
	if (elements->datetime)
		*mpStream << "checked";

	*mpStream <<
		"> \n"
		"          <b>Date and time</b></td> \n"
		"        <td bgcolor=\"dddddd\" width=27%><font size=2>Show the current date  \n"
		"          and time.</font></td> \n"
		"        <td width=73%>&nbsp;</td> \n"
		"      </tr> \n"
		"    </table> \n"
		"  </div> \n";

	*mpStream <<
		"  <p align=left><b><br> \n"
		"    Optional background choices and dividing lines to choose from</b></p> \n"
		"  <div align=left> \n"
		"    <table border=1 width=100%> \n"
		"      <tr>  \n";

	// BACKGROUND PATTERNS

	*mpStream <<
		"        <td width=15% bgcolor=\"dddddd\">  \n"
		"          <b>Background</b></td> \n"
		"        <td width=10% bgcolor=\"dddddd\"><font size=2>Pick a page pattern.</font></td> \n"
		"        <td width=75%>  \n"
		"          <input type=\"radio\" name=\"bgpattern\" value=0 ";
	
	if (elements->bgpattern == 0)
		*mpStream << "checked";

	*mpStream <<
		"> \n"
		"          plain white  \n"
		"          <input type=\"radio\" name=\"bgpattern\" value=1 ";
	
	if (elements->bgpattern == 1)
		*mpStream << "checked";

	*mpStream <<
		"> \n"
		"          <img src=\""
	<<  mpMarketPlace->GetImagePath()
	<< "background1.gif\" width=40 height=40>  \n"
		"          <input type=\"radio\" name=\"bgpattern\" value=2 ";

	if (elements->bgpattern == 2)
		*mpStream << "checked";
		
	*mpStream <<
		"> \n"
		"          <img src=\""
	<<  mpMarketPlace->GetImagePath()
	<<  "background2.gif\" width=40 height=40>  \n"
		"          <input type=\"radio\" name=\"bgpattern\" value=3 ";
		
	if (elements->bgpattern == 3)
		*mpStream << "checked";

	*mpStream <<
		"> \n"
		"          <img src=\""
	<< 	mpMarketPlace->GetImagePath()
	<<  "background3.gif\" width=40 height=40>  \n"
		"          <input type=\"radio\" name=\"bgpattern\" value=4 ";
	
	if (elements->bgpattern == 4)
		*mpStream << "checked";

	*mpStream <<
		"> \n"
		"          <img src=\""
	<<  mpMarketPlace->GetImagePath()
	<<  "background4.gif\" width=40 height=40></td> \n"
		"      </tr> \n";

	// HORIZONTAL RULES

	*mpStream <<
		"      <tr>  \n"
		"        <td width=15% bgcolor=\"dddddd\">  \n"
		"          <b>Dividing lines</b></td> \n"
		"        <td width=10% bgcolor=\"dddddd\"><font size=2>Pick a dividing line style.</font></td> \n"
		"        <td width=75%>  \n"
		"          <p>  \n"
		"            <input type=\"radio\" name=\"hr\" value=0 ";

	if (elements->hr == 0)
		*mpStream << "checked";

	*mpStream <<
		"> \n"
		"            plain gray</p> \n"
		"          <p>  \n"
		"            <input type=\"radio\" name=\"hr\" value=1 ";
	
	if (elements->hr == 1)
		*mpStream << "checked";
	
	*mpStream <<
		"> \n"
		"            rainbow <img src=\""
	<<  mpMarketPlace->GetImagePath()
	<<  "bar2.gif\" width=584 height=8></p> \n"
		"        </td> \n"
		"      </tr> \n"
		"    </table> \n"
		"  </div> \n";

	// BUTTONS AT THE BOTTOM
	// petra give every button its own name... ATTENTION: I sincerely hope
	// this function is unused. There's another call to the same ISAPI function
	// (UserPageHandleTemplateOptions) in clseBayAppUserPageTemplateEdit which 
	// only has two possible choices for actions. The corresponding code in 
	// eBayISAPI.cpp also only tests those two options... The options below are 
	// not tested for anywhere (that I know of)...
	// I'm not sure what to do with the third button here..?
	*mpStream <<
		"<p> \n"
		" <input type=\"submit\" name=\"action1\" value=\"Create your page now\"> "
		" (to preview what you've built)</p> "
		"<p> \n"
		" <input type=\"submit\" name=\"action2\" value=\"Template layout page\"> "
		" (where you chose how to arrange the elements on your page)</p> "
		"<p> \n" 
		" <input type=\"submit\" name=\"action3\" value=\"Page creation options\"> "
		" (where you chose from a template or entering free-form HTML)</p>";

    *mpStream << mpMarketPlace->GetFooter();

	CleanUp();
	return;
}

//
// Private helper functions to create dynamic pages.
//

void clseBayApp::ShowEditingPage(char *pUserId,
								 char *pPassword,
								 char *pText)
{
 
	// Note that the \n's in the HTML below need to be written as \\n to 
	// escape the \ so that it gets emitted as part of the page.
	*mpStream <<
	  "<script language=JavaScript1.1> \n"
	  "<!-- Hide script from old browsers  \n"
   	  "function fillHelp(which) \n"
	  "{ \n"
	  "     var helpText = [ \n"
	  "       \"<h1>Largest Heading</h1>\\n<h2>Next Largest Heading</h2>\\n<h3>Slightly Smaller</h3>\\n<h4>An Even Smaller Heading</h4>\" , \n"
	  "       \"<hr>\\n<hr size=10>\\n<hr width=50% align=center\" , \n"
	  "       \"<p>paragraph text</p>\\n<p align=center>centered text</p>\\n<p font=-1>text one size smaller than normal</p>\\n<p color=blue>a blue paragraph</p>\" , \n"
	  "       \"<i>italic</i>\\n<b>bold</i>\\n<u>underlined</u>\\n<font color=blue size=+2>blue text, 2 sizes larger than normal</font>\" , \n"
	  "       \"<ol>\\n<li>number 1</li>\\n<li>number 2</li>\\n</ol>\\nscroll for more...\\n\\n<ul>\\n<li>bullet 1</li>\\n<li>bullet 2</li>\\n</ul>\" , \n"
	  "       \"<img src=http://yourdomain/picture.gif>\" , \n"
	  "       \"<ebayuserid>\\n<ebayuserid email>\" , \n"
	  "       \"<ebayfeedback>\\n<ebayfeedback size=10>\\n<ebayfeedback size=50>\" , \n"
	  "       \"<ebayitemlist>\\n<ebayitemlist sort= >\\n<ebayitemlist categories= >\\n<ebayitemlist since= >\" , \n"
	  "       \"I joined eBay on: <ebaymembersince>\" , \n"
	  "       \"<ebayviewcount> people have viewed this page.\" , \n"
	  "       \"The current date and time is: <ebaytime>\"  \n"
      "     ]; \n"
	  "     document.helparea.helpwindow.value=helpText[which] \n"
	  "   } \n"
	  "   // End hiding script from old browsers --> \n"
	  "</script> \n"
	  "</head> \n";

	*mpStream << mpMarketPlace->GetHeader();

	*mpStream <<
	  "<h2>Edit Your <i>My Place</i> Page </h2> \n"
	  "<p>Edit the HTML for your My Place page in the text area below. Include any HTML  \n"
	  "  tags, as well as any special, eBay-specific tags that reflect your participation  \n"
	  "  at eBay.</p> \n";

  	*mpStream <<
		"<form name=\"helparea\" method=\"post\" action=\""
    <<  mpMarketPlace->GetCGIPath(PageUserPageHandleEditingOptions)
	<<  "eBayISAPI.dll\"> \n"
        "<INPUT TYPE=\"hidden\" NAME=\"MfcISAPICommand\""
        " VALUE=\"UserPageHandleEditingOptions\"> \n";

	*mpStream <<
           "<INPUT TYPE=\"hidden\" NAME=\"userid\" VALUE=\""
		<< pUserId
		<< "\"> \n"
	       "<INPUT TYPE=\"hidden\" NAME=\"password\" VALUE=\""
		<< pPassword
		<< "\"> \n";

	*mpStream << 
	  "  <table border=0 width=100% align=center> \n"
	  "    <tr>  \n"
	  "      <td bgcolor=\"dddddd\">  \n"
	  "        <p align=center><b>Edit Your <i>My Place</i> Page below.</b></p> \n"
	  "        <p align=center>  \n"
	  "          <textarea name=\"html\" cols=55 rows=20> \n";
	  
  	if (pText)
		*mpStream << pText;

	*mpStream <<
	  "</textarea> \n"
	  "        </p> \n"
	  "      </td> \n"
	  "      <td bgcolor=\"dddddd\">  \n";

	*mpStream <<
	  "        <p>  \n"
	  "          <input type=\"submit\" name=\"action\" value=\"View\"> \n"
	  "          your page.</p> \n"
	  "        <p>  \n"
	  "          <input type=\"submit\" name=\"action\" value=\"Save\"> \n"
	  "          your page. </p> \n"
	  "        <hr width=50% align=center> \n"
	  "        <p>  \n"
	  "          <input type=\"submit\" name=\"action\" value=\"Revert\"> \n"
	  "          to the last saved version. </p> \n"
	  "        <p>  \n"
	  "          <input type=\"submit\" name=\"action\" value=\"Delete\"> \n"
	  "          it all and start over.</p> \n"
	  "      </td> \n"
	  "    </tr> \n"
	  "  </table> \n";

	*mpStream << "<hr>";

	*mpStream <<
	  "<table border=0 width=100% bgcolor=\"#FFFFCC\"> \n"
	  "  <tr> \n"
	  "    <td>  \n"
	  "      <h2 align=center>Examples </h2> \n"
	  "      <table border=0 width=75%> \n"
	  "        <tr>  \n"
	  "          <td>  \n"
	  "            <table border=0 width=100%> \n"
	  "              <tr>  \n"
	  "                <td valign=top>  \n"
	  "                  <div align=left> <font size=2><b>HTML tags:</b><br> \n"
	  "                    <a href=javascript:fillHelp(0)>Headings</a><br> \n"
	  "                    <a href=javascript:fillHelp(1)>Horizontal line</a><br> \n"
	  "                    <a href=javascript:fillHelp(2)>Paragraphs</a><br> \n"
	  "                    <a href=javascript:fillHelp(3)>Font settings</a><br> \n"
	  "                    <a href=javascript:fillHelp(4)>Lists</a><br> \n"
	  "                    <a href=javascript:fillHelp(5)>Images</a> </font></div> \n"
	  "                </td> \n"
	  "                <td valign=top> <font size=2><b>eBay tags:</b><br> \n"
	  "                  <a href=javascript:fillHelp(6)>User ID</a><br> \n"
	  "                  <a href=javascript:fillHelp(7)>Feedback</a><br> \n"
	  "                  <a href=javascript:fillHelp(8)>Items for sale</a><br> \n"
	  "                  <a href=javascript:fillHelp(9)>Member since</a><br> \n"
	  "                  <a href=javascript:fillHelp(10)>Page counter</a><br> \n"
	  "                  <a href=javascript:fillHelp(11)>Current date/time</a></font>  \n"
	  "                </td> \n"
	  "              </tr> \n"
	  "            </table> \n"
	  "          </td> \n"
	  "        </tr> \n"
	  "        <tr>  \n"
	  "          <td>  \n"
	  "              <textarea name=\"helpwindow\" cols=50 rows=5> \n"
	  "Click a tag type from the list above,  \n"
	  "then cut and paste an example that  \n"
	  "appears here into the HTML edit window.  \n"
	  "               </textarea> \n"
	  "          </td> \n"
	  "        </tr> \n"
	  "      </table> \n"
	  "        More examples of  \n"
	  "        <a href=\""
	  << mpMarketPlace->GetHTMLPath()
	  << "help/sellerguide/selling-html.html\">HTML tags</a> and <a href=\""
	  << mpMarketPlace->GetHTMLPath()
	  << "help/myinfo/ebaytags.html\">eBay tags</a> \n"
	  "    </td> \n"
	  "  </tr> \n"
	  "</table> \n"
	  "</form> \n";
	return;
}


/*
void clseBayApp::ShowPreviewPage(char *pUserId,
								 char *pPassword,
								 char *pText)
{

	*mpStream <<
		"<table border=0 width=100%> \n"
		"  <tr> \n"
		"    <td bgcolor=\"#FFFFCC\">  \n"
		"<h2>Preview Your <i>My Place</i> Page</h2> "
		"<hr>  \n"
		"    </td> \n"
		"  </tr> \n"
		"</table> \n"
		"<p>&nbsp;</p>";


	*mpStream <<
		pText;

	*mpStream << 
		"<p>&nbsp;</p> \n"
		"		<table border=0 width=100%> \n"
		"  <tr>\n"
		"    <td bgcolor=\"#FFFFCC\"> \n"
		"<hr> \n"
		"<p>If you click &quot;Return to editing,&quot; you will have the chance to make  \n"
  		" further changes to your My Place page. If you click &quot;Save my page,&quot;  \n"
  		" you will save your page so others can see it, too. (You can always return later  \n"
		" to make adjustments to your page, but you will need to know HTML to do so.)</p> \n"
		"<form method=\"post\" action=\""
	<<  mpMarketPlace->GetCGIPath(PageHandlePreviewOptions)
	<<  "eBayISAPI.dll\">\n"
        "<INPUT TYPE=\"hidden\" NAME=\"MfcISAPICommand\""
        " VALUE=\"HandlePreviewOptions\"> \n "
	<<  "\"> \n"
	<<  "  <input type=\"hidden\" name=\"userid\" value=\""
	<<  pUserId
	<<  "\"> \n"
	<<  "  <input type=\"hidden\" name=\"password\" value=\""
	<<  pPassword
	<<  "\"> \n"
		"  <input type=\"submit\" name=\"action\" value=\"Return to editing\"> \n"
		"  <input type=\"submit\" name=\"action\" value=\"Save my page\"> \n"
		"</form> \n"
		"	</td> \n"
		"  </tr> \n"
		"</table> \n";

	return;
}
*/

void clseBayApp::ShowMyPlaceDonePage(char *pUserId, char *pPassword)
{
	*mpStream <<
		"<h2>Your <i>My Place</i> Page is Ready for Sharing!</h2> \n"
		"<p>Thanks for taking part and creating a page to share with others. You can find  \n"
		"  your page any time by going to:</p> \n"
	<<  "<p><a href=\"http://myplace.ebay.com/"
	<<  pUserId
	<<  "\">"
		"http://myplace.ebay.com/"
	<<  pUserId
	<<  "</a>"
	<<  "</p> \n"
		"<p>Feel free to give out this address to others.</p> \n"
		"<p>If you would like to <a href=\""
	<<  mpMarketPlace->GetHTMLPath()
	<<  "myplace-login.html\">"
	<<  "edit your page</a>, you can do so any time, but you will need  \n"
		"  to know HTML to make changes. We have a <a href=\""
	<<  mpMarketPlace->GetHTMLPath()
	<<  "tutorial/html-start.html\">"
	<<  "tutorial on HTML</a>, and there are many  \n"
		"  good books to learn from. You could also create a new My Place page by going  \n"
		"  to our Edit Page area and selecting Start Over.</p> \n";
	 
	return;
}

void clseBayApp::ShowMyPlaceManagerOptions(char *pUserId,
									       char *pPassword)
{
	*mpStream <<
		"<h2>Manage Your <i>My Place</i> Page </h2> \n"
		"<p>From here, you can choose how to manage your My Place page. You can:</p> \n";

	*mpStream <<
		"<form method=\"post\" action=\""
	<<  mpMarketPlace->GetCGIPath(PageUserPageHandleManagerOptions)
	<<  "eBayISAPI.dll"
	<<  "\"> \n"
	    "<INPUT TYPE=\"hidden\" NAME=\"MfcISAPICommand\""
        " VALUE=\"UserPageHandleManagerOptions\">\n"
        "<INPUT TYPE=\"hidden\" NAME=\"userid\""
        " VALUE=\""
    << pUserId
    << "\"><INPUT TYPE=\"hidden\" NAME=\"password\""
       " VALUE=\""
    << pPassword
    << "\"> \n";

	*mpStream <<
		"  <p> \n"
		"    <input type=\"submit\" name=\"action\" value=\"View\"> \n"
		"    your My Place page. </p> \n"
		"  <p>  \n"
		"    <input type=\"submit\" name=\"action\" value=\"Edit\"> \n"
		"    your My Place page.</p> \n"
		"  <p> \n"
		"    <input type=\"submit\" name=\"action\" value=\"Delete\"> \n"
		"    your My Place page and start over.</p> \n"
		"</form> \n";

	return;
}

void clseBayApp::WriteTemplateElementsParams(TemplateElements *elements)
{
	*mpStream 
		<<  "<input type=\"hidden\" name=\"templateLayout\" value=\""
		<<  elements->templateLayout
		<<  "\"> \n";

	if (elements->pTitleText)
	{

		*mpStream 
			<<  "<input type=\"hidden\" name=\"titletext\" value=\""
			<<  elements->pTitleText
			<<  "\"> \n";
	}

	if (elements->pWelcomeText)
	{

		*mpStream 
			<<  "<input type=\"hidden\" name=\"welcometext\" value=\""
			<<  elements->pWelcomeText
			<<  "\"> \n";
	}
	
	*mpStream 
			<<  "<input type=\"hidden\" name=\"pictureURL\" value=\""
			<<  elements->pPictureURL
			<<  "\"> \n";

	if (elements->showuserid)
		*mpStream 
			<<  "<input type=\"hidden\" name=\"showuserid\" value=\"yes\"> \n";

	if (elements->showuseridEmail)
		*mpStream 
			<<  "<input type=\"hidden\" name=\"showuseridEmail\" value=\"yes\"> \n";

	if (elements->showuseridRating)
		*mpStream 
			<<  "<input type=\"hidden\" name=\"showuserid\" value=\"yes\"> \n";

	if (elements->membersince)
		*mpStream 
			<<  "<input type=\"hidden\" name=\"membersince\" value=\"yes\"> \n";

	if (elements->feedback)
		*mpStream 
			<<  "<input type=\"hidden\" name=\"feedback\" value=\"yes\"> \n";

	*mpStream 
		<<  "<input type=\"hidden\" name=\"feedbackNumComments\" value=\""
		<<  elements->feedbackNumComments
		<<  "\"> \n";

	if (elements->itemlist)
		*mpStream 
			<<  "<input type=\"hidden\" name=\"itemlist\" value=\"yes\"> \n";

	*mpStream 
		<<  "<input type=\"hidden\" name=\"itemlistNumItems\" value=\""
		<<  elements->itemlistNumItems
		<<  "\"> \n";

	if (elements->pItemlistCaption)
		*mpStream 
			<<  "<input type=\"hidden\" name=\"itemlistCaption\" value=\""
			<<  elements->pItemlistCaption
			<<  "\"> \n";

	if (elements->pFavoritesName1)
		*mpStream 
			<<  "<input type=\"hidden\" name=\"favoritesName1\" value=\""
			<<  elements->pFavoritesName1
			<<  "\"> \n";

	if (elements->pFavoritesLink1)
		*mpStream 
			<<  "<input type=\"hidden\" name=\"favoritesLink1\" value=\""
			<<  elements->pFavoritesLink1
			<<  "\"> \n";

	if (elements->pFavoritesName2)
		*mpStream 
			<<  "<input type=\"hidden\" name=\"favoritesName2\" value=\""
			<<  elements->pFavoritesName2
			<<  "\"> \n";

	if (elements->pFavoritesLink2)
		*mpStream 
			<<  "<input type=\"hidden\" name=\"favoritesLink2\" value=\""
			<<  elements->pFavoritesLink2
			<<  "\"> \n";

	if (elements->pFavoritesName3)
		*mpStream 
			<<  "<input type=\"hidden\" name=\"favoritesName3\" value=\""
			<<  elements->pFavoritesName3
			<<  "\"> \n";

	if (elements->pFavoritesLink3)
		*mpStream 
			<<  "<input type=\"hidden\" name=\"favoritesLink3\" value=\""
			<<  elements->pFavoritesLink3
			<<  "\"> \n";

	if (elements->item1 > 0)
		*mpStream 
			<<  "<input type=\"hidden\" name=\"item1\" value=\""
			<<  elements->item1
			<<  "\"> \n";

	if (elements->item2 > 0)
		*mpStream 
			<<  "<input type=\"hidden\" name=\"item2\" value=\""
			<<  elements->item2
			<<  "\"> \n";

	if (elements->item3 > 0)
		*mpStream 
			<<  "<input type=\"hidden\" name=\"item3\" value=\""
			<<  elements->item3
			<<  "\"> \n";

	if (elements->pagecount)
		*mpStream 
			<<  "<input type=\"hidden\" name=\"pagecount\" value=\"yes\"> \n";

	if (elements->datetime)
		*mpStream 
			<<  "<input type=\"hidden\" name=\"datetime\" value=\"yes\"> \n";

	*mpStream 
		<<  "<input type=\"hidden\" name=\"bgpattern\" value=\""
		<<  elements->bgpattern
		<<  "\"> \n";

	*mpStream 
		<<  "<input type=\"hidden\" name=\"hr\" value=\""
		<<  elements->hr
		<<  "\"> \n";

	return;
}

