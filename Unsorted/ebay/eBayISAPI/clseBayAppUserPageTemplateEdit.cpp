/* $Id: clseBayAppUserPageTemplateEdit.cpp,v 1.3.236.1.92.2 1999/08/05 20:42:22 nsacco Exp $ */
//
//	File:		clseBayAppUserPageTemplateEdit.cpp
//
//	Class:		clseBayApp
//
//	Author:		Barry
//
//	Function:
//			    Create user pages from a template.
//
//	Modifications:
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//  
// 
#include "ebihdr.h"
#include "userpage.h"

static int NUM_ITEM_CAPTIONS = 7;

// Display the different layout formats ("styles") the user can select
// from.
void clseBayApp::UserPageSelectTemplateStyles(CEBayISAPIExtension *pThis,
											  char *pUserId,
											  char *pPassword,
											  TemplateElements *elements,
											  bool writeHeader)
{

	SetUp();

	if (writeHeader)
	{
		*mpStream <<	"<html><head>";

		ExpireThePage();

		*mpStream <<    "<title>"
	              <<    mpMarketPlace->GetCurrentPartnerName()
		          <<    " - About Me Styles"
			      <<    "</title>"
				        "</head>"
		      <<	mpMarketPlace->GetHeader();
//		      <<	mpMarketPlace->GetAboutMeHeader();
	
	}
 
	*mpStream <<
		"<CENTER> \n"
		"<TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0 WIDTH=580> \n"
		"	<TR> \n"
		"      <TD VALIGN=TOP> <FONT SIZE=5><B>About Me</B></FONT> As easy as <font color=#FF0000><b>1</b></font>,  \n"
		"        2, 3 <BR> \n"
		"        <B><font color=#FF0000>Step 1</font></B> Choose an About Me layout template.  \n"
		"        <p></P> \n"
		"		</TD> \n"
		"	</TR> \n"
		"</TABLE> \n"
		"<BR> \n"
		"<B>Your Layout Template Choices</B> \n";

	*mpStream <<
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
		WriteTemplateElementsParams(elements, false);

	// And here they are now...
	// petra give every button its own name
	*mpStream <<
		"<TABLE BORDER=1 CELLPADDING=5 CELLSPACING=0 frame WIDTH=580 BGCOLOR=black> \n"
		"	<TR> \n"
		"		<TD BGCOLOR=#99CCCC WIDTH=180> \n"
		"		<INPUT type=\"submit\" VALUE=\"Two column layout\" NAME=\"action1\"> \n"
		"		</TD> \n"
		"		<TD BGCOLOR=#EFEFEF ALIGN=middle> \n"
		"		<IMG SRC=\""
	<<  mpMarketPlace->GetImagePath()
	<<  "aboutme-templatea.gif\" WIDTH=250 HEIGHT=204 BORDER=0> \n"	
		"</TD> \n"
		"	</TR> \n"
		"	<TR> \n"
		"		<TD BGCOLOR=#99CCCC WIDTH=180> \n"
		"		<INPUT type=\"submit\" VALUE=\"Newspaper layout\" NAME=\"action2\"> \n"
		"		</TD> \n"
		"		<TD BGCOLOR=#EFEFEF ALIGN=middle> \n"
		"		<IMG SRC=\""
	<<  mpMarketPlace->GetImagePath()
	<<  "aboutme-templateb.gif\" WIDTH=250 HEIGHT=221 BORDER=0> \n"
	"		</TD> \n"
		"	</TR> \n"
		"	<TR> \n"
		"		<TD BGCOLOR=#99CCCC WIDTH=180> \n"
		"		<INPUT type=\"submit\" VALUE=\"Centered layout\" NAME=\"action3\"> \n"
		"		<TD BGCOLOR=#EFEFEF ALIGN=middle> \n"
		"		<IMG SRC=\""
	<<  mpMarketPlace->GetImagePath()
	<<  "aboutme-templatec.gif\" WIDTH=250 HEIGHT=241 BORDER=0> \n"
		"		</TD> \n"
		"	</TR> \n"
		"</TABLE> \n"
		"</FORM> \n"
		"			</CENTER> \n";

	if (writeHeader)
	{
		*mpStream << mpMarketPlace->GetFooter();
	}

	CleanUp();

	return;
}

// This is the second step in creating pages based on a template. Select
// the elements to in the page. (The first step was to select the layout.)
void clseBayApp::UserPageSelectTemplateElements(CEBayISAPIExtension *pThis,
												char *pUserId,
												char *pPassword,
												TemplateElements *elements)
{
	char *pStrippedHTML;
	int   val;

	SetUp();

	*mpStream <<	"<html><head>"
                    "<title>"
              <<    mpMarketPlace->GetCurrentPartnerName()
              <<    " - Select Template Elements"
              <<    "</title>"
                    "</head>"
		      <<	mpMarketPlace->GetHeader();
//		      <<	mpMarketPlace->GetAboutMeHeader();

	*mpStream <<
		"<center>\n"
		"  <table border=0 cellpadding=5 cellspacing=0 width=607>\n"
		"    <tr>\n"
		"     <td valign=top> <font size=5><B>About Me</b></font> As easy as 1, <font color=#FF0000><b>2</b></font>, \n"
		"        3 <br>\n"
		"        <b><font color=#FF0000>Step 2</font></b> Pick elements to include in \n"
		"        your About Me page. \n"
		"        <p> Select different elements you'd like to include on your About Me page. \n"
		"          You can choose any combination of elements. These will be arranged according \n"
		"          to the layout you chose from <b>Step 1</b>. </p>\n"
		"		</td>\n"
		"	</tr>\n"
		"</table> \n";

	*mpStream <<
		"<form method=\"post\" action=\""
	<<  mpMarketPlace->GetCGIPath(PageUserPageHandleTemplateOptions)
	<<  "eBayISAPI.dll"
	<<  "\"> \n"
	    "<input type=\"hidden\" name=\"MfcISAPICommand\""
        " value=\"UserPageHandleTemplateOptions\">\n"
        "<input type=\"hidden\" name=\"userid\""
        " value=\""
    << pUserId
    << "\"><input type=\"hidden\" name=\"password\""
       " value=\""
    << pPassword
    << "\"> \n";

	*mpStream <<
		   "<input type=\"hidden\" name=\"templateLayout\" value="
		<< elements->templateLayout
		<< ">\n";

	*mpStream <<
          "<br> \n"
          "    <b>Personalize Your Page</b> \n"
          "    <table border=1 cellpadding=5 cellspacing=0 frame width=580 bgcolor=\"black\">\n"
          "      <tr> \n"
          "        <td bgcolor=#99cccc width=180> <b>Page Title</b> \n"
          "          <br>\n";

	// PAGE TITLE ------------------------------------------------------

	*mpStream <<
          "Create a title for your page. </TD> \n"
          " <TD BGCOLOR=#EFEFEF ALIGN=right> \n"
		  "	<table border=0 width=100%> \n"
		  " <tr> \n"
		  " <td width=15%>Title: </td>\n"
		  " <td>\n"
          " <input type=\"text\" name=\"pageTitle\" size=40 maxlength=120";

	if (elements->pPageTitle)
	{
		pStrippedHTML = clsUtilities::StripHTML(elements->pPageTitle);

		*mpStream 
			<< " value=\""
			<< pStrippedHTML
			<< "\"";

		delete [] pStrippedHTML;
	}

	// TEXT / WELCOME MESSAGE ------------------------------------------
	*mpStream <<
		"> \n"
		"</td> \n"
		"</tr> \n"
		"</table> \n";

	*mpStream <<
		"        </TD>\n"
		"      </TR>\n"
		"      <TR> \n"
		"        <TD BGCOLOR=#99CCCC WIDTH=180> <B>Welcome Message \n"
		"          </B> <BR>\n"
		"          Create a short paragraph to welcome visitors to your page. </TD>\n"
		"        <TD BGCOLOR=#EFEFEF ALIGN=right> \n"
		"          <table border=0 width=100%>\n"
		"            <tr> \n"
		"              <td width=15%>Heading: </td>\n"
		"              <td>\n"
        "   <input type=\"text\" name=\"textAreaTitle1\" size=40 maxlength=120";

	if (elements->pTextAreaTitle1)
	{
		pStrippedHTML = clsUtilities::StripHTML(elements->pTextAreaTitle1);

		*mpStream 
			<< " value=\""
			<< pStrippedHTML
			<< "\"";

		delete [] pStrippedHTML;
	}

	*mpStream <<
		" >"
		"              </td>\n"
		"            </tr>\n"
		"            <tr> \n"
		"              <td width=15%>Text: </td>\n"
		"              <td> \n"
		"                <textarea name=\"textArea1\" cols=40 rows=4 wrap=virtual>";
				
	if (elements->pTextArea1)
		*mpStream << elements->pTextArea1;
		
	*mpStream <<
		"				</textarea>\n"
		"              </td>\n"
		"            </tr>\n"
		"          </table>  \n"        
		"        </TD>\n"
		"      </TR>\n"
		"      <TR> \n"
		"        <TD BGCOLOR=#99CCCC WIDTH=180><b>Another Paragraph \n"
		"          </b> <br>\n"
		"          What else do you want to share with others? </TD>\n"
		"        <TD BGCOLOR=#EFEFEF ALIGN=right> \n"
		"          <table border=0 width=100%>\n"
		"            <tr> \n"
		"              <td width=15%>Heading: </td>\n"
		"              <td> \n"
		"                <input type=\"text\" name=\"textAreaTitle2\" size=40 maxlength=120";

	if (elements->pTextAreaTitle2)
	{
		pStrippedHTML = clsUtilities::StripHTML(elements->pTextAreaTitle2);

		*mpStream 
			<< " value=\""
			<< pStrippedHTML
			<< "\"";

		delete [] pStrippedHTML;
	}

	*mpStream <<
		" >"
		"              </td>\n"
		"            </tr>\n"
		"            <tr> \n"
		"              <td width=15%>Text: </td>\n"
		"              <td> \n"
		"                <textarea name=\"textArea2\" cols=40 rows=4 wrap=virtual>";
				
	if (elements->pTextArea2)
		*mpStream << elements->pTextArea2;				
				
	*mpStream <<
		"				</textarea>\n"
		"              </td>\n"
		"            </tr>\n"
		"          </table>\n"          
		"        </TD> \n"
		"      </TR>\n"
		"      <TR> \n"
		"        <TD BGCOLOR=#99CCCC WIDTH=180><b>Picture</b> \n"
		"          <br>\n"
		"          Link to a picture that you've posted on the Web. </TD>\n"
		"        <TD BGCOLOR=#EFEFEF ALIGN=right> \n";

	*mpStream <<
		"		<table border=0 width=100%> \n"
		"		<tr> \n"
		"		<td width=15%>Caption:</td> \n"
		"		<td> \n"
		"       <input type=\"text\" name=\"pictureCaption\" size=40 maxlength=120";


	if (elements->pPictureCaption)
	{
		pStrippedHTML = clsUtilities::StripHTML(elements->pPictureCaption);
		*mpStream 
			<< " value=\""
			<< pStrippedHTML
			<< "\"";

		delete [] pStrippedHTML;
	}

	*mpStream <<
		"\"> \n"
		"</td> \n"
		"</tr> \n"
		"<tr> \n"
		"<td width=15%>URL: </td> \n"
		"<td> \n"
		"<input type=\"text\" name=\"pictureURL\" size=40 maxlength=120 value=\""
	 << elements->pPictureURL
	 << "\"";

	*mpStream <<
		"> \n"
		"</td> \n"
		"</tr> \n"
		"</table> \n"
		"          </p>\n"
		"        </TD>\n"
		"      </TR>\n"
		"    </TABLE>\n";

	// YOUR ACTIVITIES ---------------------------------------------------

	*mpStream <<
		"	<BR>\n"
		"    <B>Show Your eBay Activity</B> \n"
		"          <table border=1 CELLPADDING=5 CELLSPACING=0 frame WIDTH=580 BGCOLOR=\"black\">\n"
		"            <tr bgcolor=\"#99CCCC\"> \n"
		"              <td><b>Feedback</b> <br>\n"
		"                Display your feedback comments. </td>\n"
		"              <td bgcolor=\"#EFEFEF\"> \n"
		"                <select name=\"feedbackNumComments\" size=1> \n";

	for (val = 0; val < 6; val++) 
	{
		*mpStream << "<option value="
				  << commentAndFeedbackVals[val];

		if (elements->feedbackNumComments == commentAndFeedbackVals[val])
			*mpStream << " selected";

		*mpStream << ">"
				  << commentText[val]
				  << "</option> \n";
	}
    
	*mpStream <<
           "     </select>\n"
           "              </td>\n"
           "            </tr>\n"
           "            <tr bgcolor=#99CCCC> \n"
           "              <td><b>Items for Sale</b> <br>\n"
           "                Display your current items for sale which will appear oldest to most recent. </td>\n"
           "              <td bgcolor=\"#EFEFEF\"> \n";

	*mpStream <<
           "     <table border=0 width=100%> \n"
           "        <tr>  \n"
           "          <td> Caption: </td> \n"
		   "          <td> <input type=\"text\" name=\"itemlistCaption\" maxlength=250";

	if (elements->pItemlistCaption)
	{
		pStrippedHTML = clsUtilities::StripHTML(elements->pItemlistCaption);
		*mpStream 
			<< " value=\""
			<< pStrippedHTML
			<< "\"";

		delete [] pStrippedHTML;
	}
		
	*mpStream <<
		"> </td>\n"
		"        </tr>\n"
		"        <tr> \n"
		"          <td>&nbsp;</td>\n"
		"          <td>\n";

	*mpStream <<
		"                <select name=\"itemlistNumItems\" size=1> \n";


	for (val = 0; val < 6; val++) 
	{
		*mpStream << "<option value="
				  << commentAndFeedbackVals[val];

		if (elements->itemlistNumItems == commentAndFeedbackVals[val])
			*mpStream << " selected";

		*mpStream << ">"
				  << itemlistText[val]
				  << "</option> \n";
	}

	*mpStream <<
			  " 	 </select> \n"
              "		</td>\n"
              "    </tr>\n"
              "   </table>\n"
              "  </td>\n"
              " </tr>\n"
              "</table>\n"
			  "<BR>\n"
              "    <B> Share Some of Your Favorite Things</B> \n"
              "    <TABLE BORDER=1 CELLPADDING=5 CELLSPACING=0 frame WIDTH=580 BGCOLOR=\"black\">\n";


	// FAVORITE LINKS ------------------------------------------------

	*mpStream <<
      "<TR> \n"
      "        <TD BGCOLOR=\"#99CCCC\" WIDTH=180> <B>Favorite Links</B> \n"
      "          <BR>\n"
      "          Help others find your favorite places on the Web. </TD> \n";

	*mpStream <<
      "        <TD BGCOLOR=\"#EFEFEF\" ALIGN=\"right\"> \n"	   
	  "			<table border=0 width=100%> \n"
	  "			<tr> \n"
	  "			<td width=15%> Name:</td> \n"
	  "			<td> \n"
      "         <input type=\"text\" name=\"favoritesName1\" size=\"40\" maxlength=\"120\"";
	  
	if (elements->pFavoritesName1)
	{
		pStrippedHTML = clsUtilities::StripHTML(elements->pFavoritesName1);

		*mpStream << " value=\""
		          << pStrippedHTML
				  << "\"";	  

		delete [] pStrippedHTML;
	}
		
	*mpStream <<
      "> \n"
	  "</td> \n"
      "</tr>  \n"
	  "<tr>  \n"
	  "<td width=15%> URL: </td> \n"
	  "<td> \n"
      "<input type=\"text\" name=\"favoritesLink1\" size=\"40\" maxlength=\"120\" value=\"";

	*mpStream << elements->pFavoritesLink1
			  << "\"";

	*mpStream <<
		"> \n"
		"</td>  \n"
		"</tr>  \n"
		"<tr>  \n"
		"<td width=15%> \n";

	*mpStream <<
      "Name:</td>"
	  "<td>  \n"
      "<input type=\"text\" name=\"favoritesName2\" size=\"40\" maxlength=\"120\"";
	  
	if (elements->pFavoritesName2)
	{
		pStrippedHTML = clsUtilities::StripHTML(elements->pFavoritesName2);

		*mpStream << " value=\""
		          << pStrippedHTML
				  << "\"";	  

		delete [] pStrippedHTML;
	}

	*mpStream <<
      "> \n"
	  "</td>  \n"
	  "</tr>  \n"
	  "<tr>  \n"
      "<td width=15%> URL: </td>  \n"
	  "<td>  \n"
      "<input type=\"text\" name=\"favoritesLink2\" size=\"40\" maxlength=\"120\" value=\"";

	*mpStream << elements->pFavoritesLink2
			  << "\"";

	*mpStream <<
		"> \n"
		"</td>  \n"
		"</tr>  \n"
		"<tr>    \n"
		"<td width=15%>  \n";

	*mpStream <<
      "Name: </td>"
	  "<td>  \n"
	  "<input type=\"text\" name=\"favoritesName3\" size=\"40\" maxlength=\"120\"";
	  
	if (elements->pFavoritesName3)
	{
		pStrippedHTML = clsUtilities::StripHTML(elements->pFavoritesName3);

		*mpStream << " value=\""
		          << pStrippedHTML
				  << "\"";	  

		delete [] pStrippedHTML;
	}

	*mpStream <<
      "> \n"
	  "</td>  \n"
	  "</tr>  \n"
	  "<tr>  \n"
	  "<td width=15%>  \n"
      "URL: </td>"
	  "<td>  \n"
      "<input type=\"text\" name=\"favoritesLink3\" size=\"40\" maxlength=\"120\" value=\"";

	*mpStream     << elements->pFavoritesLink3
				  << "\"";

	*mpStream <<
		"> \n"
        "</td> \n"
		"</tr>  \n"
		"</table>  \n"
       "   </TD> \n"
      "</TR> \n";
 
	// ITEMS ON EBAY ------------------------------------------------

	*mpStream <<
      "	  <TR> \n"
      "        <TD BGCOLOR=#99CCCC WIDTH=180> <B>Favorite eBay \n"
      "          Items</B> <BR>\n"
      "          Share your eBay &quot;finds&quot; with others. </TD>   \n"
      "        <TD BGCOLOR=#EFEFEF ALIGN=right> \n";

	*mpStream <<
		"<table border=0 width=100%> \n"
		"<tr> \n"
		"<td width=15%>Item #: </td>"
		"<td> \n"
		"<input type=\"text\" name=\"item1\" SIZE=14 maxlength=20 value=";

	if (elements->item1)
		*mpStream << elements->item1;
	else 
		*mpStream << "\"\"";

	*mpStream <<
		"> \n";

	*mpStream <<
		"			<select name=\"item1CaptionChoice\">\n";

	for (val = 0; val < NUM_ITEM_CAPTIONS; val++)
	{
		*mpStream 
			<< "<option value="
			<< val;
	
		if (elements->item1CaptionChoice == val)
			*mpStream << " selected";

		*mpStream << ">"
			      << itemCaptionText[val]
				  <<"</option>\n";
	}

	*mpStream <<
		"   </select> \n"
		"	</td> \n"
		"	</tr> \n"
		"	<tr> \n";

	*mpStream <<
		"       <td width=15%>Item #: </td> \n"
		"		<td>  \n"
		"       <input type=\"text\" name=\"item2\" SIZE=14 maxlength=20 value=";

	if (elements->item2)
		*mpStream << elements->item2;
	else 
		*mpStream << "\"\"";

	*mpStream <<
		"> \n";
		
	*mpStream <<
		"			<select name=\"item2CaptionChoice\">\n";
	
	for (val = 0; val < NUM_ITEM_CAPTIONS; val++)
	{
		*mpStream 
				<< "<option value="
				<< val;
	
		if (elements->item2CaptionChoice == val)
			*mpStream << " selected";

		*mpStream << ">"
			      << itemCaptionText[val]
				  <<"</option>\n";
	}

	*mpStream <<
		"          </select>\n"
		"          </td> \n"
		"			</tr> \n"
		"			<tr> \n"
        "			<td width=15%> \n"
		"			Item #:  </td>\n"
		"			<td> \n"
		"            <input type=\"text\" name=\"item3\" SIZE=14 maxlength=20 value=";

	if (elements->item3)
		*mpStream << elements->item3;
	else 
		*mpStream << "\"\"";

	*mpStream <<
		"> \n";

	*mpStream <<
		"			<select name=\"item3CaptionChoice\">\n";

	for (val = 0; val < NUM_ITEM_CAPTIONS; val++)
	{
		*mpStream 
			<< "<option value="
			<< val;
	
		if (elements->item3CaptionChoice == val)
			*mpStream << " selected";

		*mpStream << ">"
			      << itemCaptionText[val]
				  <<"</option>\n";
	}

	*mpStream <<
		"          </select>\n"
		"		</td> \n"
		"		</tr> \n"
		"		</table> \n"
		"        </TD> \n"
		"      </TR> \n"
		"    </TABLE> \n";

	// BUTTONS AT THE BOTTOM ------------------------------------------
	// petra give every button its own name

	*mpStream <<
		" <br><br> \n"
		"	<table border=0 width=75% align=center> \n"
		"  <tr> \n"
		"    <td> \n"
		"      <div align=center> \n"
		"        <input type=\"submit\" name=\"action1\" value=\"Preview your page\"> \n"
		"      </div> \n"
		"    </td> \n"
		"    <td> \n"
		"      <div align=center> \n"
		"        <input type=\"submit\" name=\"action2\" value=\"Choose new layout\"> \n"
		"      </div> \n"
		"    </td> \n"
		"  </tr> \n"
		"  <tr> \n"
		"    <td> \n"
		"      <div align=center><font size=\"-1\">(Go to step 3)</font></div> \n"
		"    </td> \n"
		"    <td> \n"
		"      <div align=center><font size=\"-1\">(Go back to step 1)</font></div> \n"
		"    </td> \n"
		"  </tr> \n"
		"</table> \n"
		"  </form> \n";	

			
    *mpStream << mpMarketPlace->GetFooter();

	CleanUp();
	return;
}

// If the user makes a selection that will cause the user to lose recent
// changes, we verify with the user first.
void clseBayApp::UserPageShowConfirmTemplateEditingChoices(CEBayISAPIExtension *pThis,
									char *pUserId,
									char *pPassword,
									TemplateElements *elements,
									UserPageEditingEnum which)
{
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

	switch ((UserPageEditingEnum)which) 
	{
	case UserPageTemplateToHTMLEditing:

		*mpStream <<
		"<h2>Are You Sure?</h2> \n"
		"<p>You are about to start editing your HTML directly. If you click &quot;Edit  \n"
		"  using HTML&quot; below, you will be able to use any HTML commands you would like, \n"
		" but you cannot return to using the templates you used here unless \n"
		" you start over again with a new page. If  \n"
		"  you do not really want to start editing using HTML, click &quot;Keep  \n"
		"  using templates for now.&quot;</p> \n";

		break;

	case UserPageTemplateEditingDontSave:
		*mpStream <<
		"<h2>Are You Sure?</h2> \n"
		"<p>You are about to end this editing session without saving the changes you \n"
		"made to your About Me page. If you click &quot;Don't  \n"
		"  save these edits&quot; below, you will end this editing session. If  \n"
		"  you do not really want to end without saving, click &quot;Keep  \n"
		"  editing for now.&quot;</p> \n";

		break;

	case UserPageTemplateEditingSave:
		*mpStream <<
		"<h2>Ready to Save Your Page?</h2> \n"
		"<p>If your page is complete and ready for others to view, go ahead and click  \n"
		"\"Save my page\". Please note that once you've saved your page, any  \n"
		"future editing will need to be performed using HTML. If you do not know HTML \n" 
		"and want to continue editing your page with the templates, you will need to delete your  \n"
		"page and start over. </p>\n"
		"<p>Therefore, if you want to edit some more using the templates, \n"
		"choose \"Keep editing for now\". </p> \n";

		break;

	case UserPageTemplateEditingStartOver:
		*mpStream <<
		"<p>You are about to delete your existing About Me page. If you click &quot;Start  \n"
		"  over&quot; below, you will have the chance to create a new page. If  \n"
		"  you do not really want to delete your existing About Me page, click &quot;Keep  \n"
		"  my page for now.&quot;</p> \n";

		break;
	
	}	
	
	*mpStream <<
		"<form method=\"post\" action=\""
	<<  mpMarketPlace->GetCGIPath(PageUserPageConfirmTemplateEditingChoice)
	<<  "eBayISAPI.dll\">\n"
        "<INPUT TYPE=\"hidden\" NAME=\"MfcISAPICommand\""
        " VALUE=\"UserPageConfirmTemplateEditingChoice\"> \n "
	<<  "  <input type=\"hidden\" name=\"userid\" value=\""
	<<  pUserId
	<<  "\"> \n"
	<<  "  <input type=\"hidden\" name=\"password\" value=\""
	<<  pPassword
	<<  "\"> \n"
	<<  "  <input type=\"hidden\" name=\"which\" value=\""
	<<  which
	<<  "\"> \n";

	WriteTemplateElementsParams(elements);

	*mpStream <<
		"  <table border=0 width=75%> \n"
		"    <tr> \n"
		"      <td> \n";

	// petra make button names unique
	switch (which) 
	{
	case UserPageTemplateToHTMLEditing:
		*mpStream <<
			"        <input type=\"submit\" name=\"action1\" value=\"Edit using HTML\"> \n"
			"      </td> \n"
			"      <td> \n"
			"        <input type=\"submit\" name=\"action2\" value=\"Keep using templates for now\"> \n";
		break;

	case UserPageTemplateEditingStartOver:
		*mpStream <<
			"        <input type=\"submit\" name=\"action1\" value=\"Delete and start over\"> \n"
			"      </td> \n"
			"      <td> \n"
			"        <input type=\"submit\" name=\"action2\" value=\"Keep my page for now\"> \n";
		break;

	case UserPageTemplateEditingSave:
		*mpStream <<
			"        <input type=\"submit\" name=\"action1\" value=\"Save my page\"> \n"
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

    CleanUp();
    return;
}

