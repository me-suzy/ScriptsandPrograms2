/* $Id: clseBayAppUserPageHTMLEdit.cpp,v 1.3.236.3.86.2 1999/08/05 20:42:21 nsacco Exp $ */
//
//	File:		clseBayAppUserPageHTMLEdit.cpp
//
//	Class:		clseBayApp
//
//	Author:		Chad
//
//	Function:
//
//				Let's a user edit, either from the 
//              database or from text, their user page.
//
//	Modifications:
//    Barry     Added a confirmation page and updated the HTML layouts.
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
// 
#include "ebihdr.h"
#include "clseBayApp.h"
#include "clsUserPage.h"
#include "clsWidgetPage.h"

// Given the text, show the editing page to allow the user to
// edit raw HTML.
void clseBayApp::UserPageEditFromText(CEBayISAPIExtension *pThis,
									  char *pUserId,
									  char *pPassword,
									  char *pText,
									  int   page /* = 0 */)
{
	char *pCleanedText;

	SetUp();

	*mpStream <<	"<html><head>";

	ExpireThePage();

	*mpStream <<    "<title>"
              <<    mpMarketPlace->GetCurrentPartnerName()
              <<    " Edit User Page for "
              <<    pUserId
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

	// Convert to a safe format for putting in the page as a parameter.
    pCleanedText = clsUtilities::StripHTML(pText);

	// Do the guts of HTML for showing the editing elements.
	ShowEditingPage(pUserId, pPassword, pCleanedText);

    *mpStream << flush;

    delete [] pCleanedText;

    *mpStream << mpMarketPlace->GetFooter();
    
    CleanUp();
    return;
}

// We can also edit the raw HTML given the elements filled in
// from the template forms.
void clseBayApp::UserPageEditFromElements(CEBayISAPIExtension *pThis,
									  char *pUserId,
									  char *pPassword,
									  TemplateElements *elements)
{
	char		*pCleanedText;
	char        *pStr;
	ostrstream   oStream;

	SetUp();

	*mpStream <<	"<html><head>";

	ExpireThePage();

	*mpStream << 	"<title>"
              <<    mpMarketPlace->GetCurrentPartnerName()
              <<    " Edit User Page for "
              <<    pUserId
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

	// Take the form elements and convert them to HTML, taking
	// the widgets into account.
	UserPageConvertTemplateToHTML(&oStream, elements, false);
	oStream << ends;

	// Put the HTML just generated into a string.
	pStr = oStream.str();
    pCleanedText = clsUtilities::StripHTML(pStr);

	ShowEditingPage(pUserId, pPassword, pCleanedText);

    *mpStream << flush;

    *mpStream << mpMarketPlace->GetFooter();
    
    delete [] pCleanedText;
	delete [] pStr;

    CleanUp();
    return;
}


// Private helper functions to create the editing page when editing
// raw HTML either as entered by the user or that gets generated
// from a template.

void clseBayApp::ShowEditingPage(char *pUserId,
								 char *pPassword,
								 char *pText)
{
	// We've already gone through all the setup. Just
	// do the guts of the HTML for the editing controls.

	*mpStream <<
	  "<h2>Edit Your <i>About Me</i> Page </h2> \n"
	  "<p>Edit the HTML for your About Me page in the text area below. Include any HTML  \n"
	  "  tags, as well as any special, eBay-specific tags that reflect your participation  \n"
	  "  at eBay.</p> \n";

  	*mpStream <<
		"<form method=\"post\" action=\""
    <<  mpMarketPlace->GetCGIPath(PageUserPageGoToHTMLPreview)
	<<  "eBayISAPI.dll\"> \n"
        "<INPUT TYPE=\"hidden\" NAME=\"MfcISAPICommand\""
        " VALUE=\"UserPageGoToHTMLPreview\"> \n";

	*mpStream <<
           "<INPUT TYPE=\"hidden\" NAME=\"userid\" VALUE=\""
		<< pUserId
		<< "\"> \n"
	       "<INPUT TYPE=\"hidden\" NAME=\"password\" VALUE=\""
		<< pPassword
		<< "\"> \n";

	*mpStream << 
	  " <center> \n"
	  "        <p align=center><b>Edit Your <i>About Me</i> Page below.</b></p> \n"
	  "        <p align=center>  \n"
	  "          <textarea name=\"html\" cols=80 rows=20 wrap=virtual>";
	  
  	if (pText)
		*mpStream << pText;

	*mpStream <<
	  "</textarea> \n"
	  "        </p> \n";

	*mpStream <<
	  "        <p>  \n"
	  "          <input type=\"submit\" value=\"Preview your page\"> \n"
	  "        </p> \n"
	  "  </center>\n";

	*mpStream << "<hr>";

	// Some help stuck onto the page. This consists of simple
	// HTML and eBay widget tag examples.
	*mpStream << "\n<center>\n";

	*mpStream <<
	  "<table border=0 width=75% bgcolor=\"#EFEFEF\" align=center> \n"
	  "  <tr> \n"
	  "    <td>  \n"
	  "      <h2 align=center>Examples of Tags You Can Use</h2> \n"
	  "      <table border=0 width=85% align=center> \n"
	  "        <tr>  \n"
	  "          <td>  \n"
	  "            <table border=0 width=100%> \n"
	  "              <tr>  \n"
	  "                <td valign=top width=57%>  \n"
	  "                  <div align=left>  \n"
	  "                    <p align=center><a href=\""
	  << mpMarketPlace->GetHTMLPath()
	  << "help/sellerguide/selling-html.html\"><font size=2><b>Examples "
	  "                      of HTML tags</b></font></a><font size=2><b>:</b></font></p> \n"
	  "                    <p align=left><font size=2> <u>Headings</u><br> \n"
	  "                      &lt;h1&gt;Large Heading&lt;/h1&gt;<br> \n"
	  "                      &lt;h2&gt;Smaller Heading&lt;/h2&gt;<br> \n"
	  "                      &lt;h3&gt;Even Smaller Heading&lt;/h3&gt;</font></p> \n"
	  "                    <p><font size=2> <u>Horizontal line</u><br> \n"
	  "                      &lt;hr&gt;<br> \n"
	  "                      <br> \n"
	  "                      <u>Paragraphs</u><br> \n"
	  "                      &lt;p&gt;Paragraph goes here.&lt;/p&gt;<br> \n"
	  "                      &lt;p align=center&gt;Centers this paragraph.&lt;/p&gt;</font></p> \n"
	  "                    <p><font size=2> <font color=\"#000000\"><u>Font settings</u></font><font color=\"#FF0000\"><u></u></font><u></u><br> \n"
	  "                      &lt;b&gt;This will be bold.&lt;/b&gt;<br> \n"
	  "                      &lt;i&gt;This will be italic.&lt;/i&gt;</font><font size=2><br> \n"
	  "                      &lt;u&gt;This will be underlined&lt;/u&gt;</font></p> \n"
	  "                    <p><font size=2> <u>Lists</u><br> \n"
	  "                      &lt;ol&gt;<br> \n"
	  "                      &lt;li&gt;Ordered list item number 1&lt;/li&gt;<br> \n"
	  "                      &lt;li&gt;Ordered list item number 2&lt;/li&gt;<br> \n"
	  "                      &lt;/ol&gt;</font></p> \n"
	  "                    <p><font size=2>&lt;ul&gt;<br> \n"
	  "                      &lt;li&gt;Bullet item number 1&lt;/li&gt;<br> \n"
	  "                      &lt;li&gt;Bullet item number 2&lt;/li&gt;<br> \n"
	  "                      &lt;/ul&gt;<br> \n"
	  "                      <br> \n"
	  "                      <u>Images</u><br> \n"
	  "                      &lt;img src=http://yourdomain/yourpic.gif&gt;</font></p> \n"
	  "                    </div> \n"
	  "                </td> \n"
	  "                <td valign=top width=43%>  \n"
	  "                  <p align=center><a href=\""
	  << mpMarketPlace->GetHTMLPath()
	  << "help/myinfo/ebaytags.html\"><font size=2><b>Examples  \n"
	  "                    of eBay-specific tags</b></font></a><font size=2><b>:</b></font></p> \n"
	  "                  <p><font size=2> <u>User ID</u><br> \n"
	  "                    &lt;ebayuserid&gt;</font> </p> \n"
	  "                  <p><font size=2> <u>Feedback</u><br> \n"
	  "                    &lt;ebayfeedback&gt; <br> \n"
	  "                    &lt;ebayfeedback size=10&gt;</font></p> \n"
	  "                  <p><font size=2> <u>Items for sale</u><br> \n"
	  "                    &lt;ebayitemlist&gt;</font> </p> \n"
	  "                  <p><font size=2> <u>Member since</u><br> \n"
	  "                    &lt;ebaymembersince&gt;</font></p> \n"
	  "                  <p><font size=2> <u>Current date/time</u></font> <br> \n"
	  "                    <font size=2>&lt;ebaytime&gt;</font></p> \n"
	  "                  </td> \n"
	  "              </tr> \n"
	  "            </table> \n"
	  "          </td> \n"
	  "        </tr> \n"
	  "      </table> \n"
	  "    </td> \n"
	  "  </tr> \n"
	  "</table> \n";
	
	*mpStream << "\n</center>\n";

	return;
}


// Confirm that a choice the user made is really the right one.
void clseBayApp::UserPageShowConfirmHTMLEditingChoices(CEBayISAPIExtension *pThis,
								   char *pUserId,
							       char *pPassword,
								   char *pHTML,
							       UserPageEditingEnum which)
{
	char *pCleanedText;

    SetUp();

    *mpStream <<    "<html><head>"
                    "<title>"
              <<    mpMarketPlace->GetCurrentPartnerName()
              <<    " Confirm Your Selection "
              <<    pUserId
              <<    "</title>"
                    "</head>"
		      <<	mpMarketPlace->GetHeader();
//		      <<	mpMarketPlace->GetAboutMeHeader();
	*mpStream << 
		"<h2>Are You Sure?</h2> \n";

    pCleanedText = clsUtilities::StripHTML(pHTML);

	// We currently do not use the "Don't Save" option, but
	// I left it in until we decide for certain either not to
	// use it or go back to it.
	switch (which) 
	{
	case UserPageHTMLEditingStartOver:

		*mpStream <<
		"<p>You are about to delete your existing About Me page. If you click &quot;Start  \n"
		"  over&quot; below, you will have the chance to create a new page. If  \n"
		"  you do not really want to delete your existing About Me page, click &quot;Keep  \n"
		"  my page for now.&quot;</p> \n";

		break;

	case UserPageHTMLEditingDontSave:
		*mpStream <<
		"<p>You are about to end this editing session without saving the changes you \n"
		"made to your About Me page. If you click &quot;Don't  \n"
		"  save these edits&quot; below, you will end this editing session. If  \n"
		"  you do not really want to end without saving, click &quot;Keep  \n"
		"  editing for now.&quot;</p> \n";

		break;
	}

	*mpStream <<
		"<form method=\"post\" action=\""
	<<  mpMarketPlace->GetCGIPath(PageUserPageConfirmHTMLEditingChoice)
	<<  "eBayISAPI.dll\">\n"
        "<INPUT TYPE=\"hidden\" NAME=\"MfcISAPICommand\""
        " VALUE=\"UserPageConfirmHTMLEditingChoice\"> \n "
	<<  "  <input type=\"hidden\" name=\"userid\" value=\""
	<<  pUserId
	<<  "\"> \n"
	<<  "  <input type=\"hidden\" name=\"password\" value=\""
	<<  pPassword
	<<  "\"> \n"
	<<  "  <input type=\"hidden\" name=\"which\" value=\""
	<<  which
	<<  "\"> \n"
	<<  "  <input type=\"hidden\" name=\"html\" value=\""
	<<  pCleanedText
	<<  "\"> \n"
		"  <table border=0 width=75%> \n"
		"    <tr> \n"
		"      <td> \n";

	// petra make button names unique
	switch ((UserPageEditingEnum)which) 
	{
	case UserPageHTMLEditingStartOver:
		*mpStream <<
			"        <input type=\"submit\" name=\"action1\" value=\"Delete and start over\"> \n"
			"      </td> \n"
			"      <td> \n"
			"        <input type=\"submit\" name=\"action2\" value=\"Keep my page for now\"> \n";
		break;

	case UserPageHTMLEditingDontSave:
		*mpStream <<
			"        <input type=\"submit\" name=\"action1\" value=\"Don't save these edits\"> \n"
			"      </td> \n"
			"      <td> \n"
			"        <input type=\"submit\" name=\"action2\" value=\"Keep editing for now\"> \n";

		break;

	}

	*mpStream <<
		"      </td> \n"
		"    </tr> \n"
		"  </table> \n"
		"</form> \n";

	*mpStream << mpMarketPlace->GetFooter();

	delete [] pCleanedText;

    CleanUp();
    return;
}

