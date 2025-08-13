/* $Id: clseBayAppUserPageTemplates.cpp,v 1.2 1998/10/16 01:03:30 josh Exp $ */
//
//	File:		clseBayAppUserPageTemplates.cpp
//
//	Class:		clseBayApp
//
//	Author:		Alex, Barry
//
//	Function:
//
//			    Knows how to format raw HTML using templates.
//
//	Modifications:
// 
//   
#include "ebihdr.h"
#include "clsWidgetHandler.h"
#include "clseBayFeedbackWidget.h"
#include "clseBayItemListWidget.h"
#include "clseBayTimeWidget.h"

#include "userpage.h"

static const char *itemCaptions[] = {"Check it out!", "I'm selling this", "I collect these", "I love these!", "Fun item for sale.", "Should be in every collection"};
static int NUM_CAPTION_CHOICES = 7;

// Given a filled TemplateElements structure and an ostream,
//  write out HTML to the ostream. This HTML will contain
//  appropriately formatted eBay tags based on the TemplateElements
//  that were chosen by a user.
// Typically, the output of this function will then be used by 
//  the WidgetParser to output final HTML.
// Modify this routine when changing header or footer HTML common to all templates.
void clseBayApp::UserPageConvertTemplateToHTML(ostream *pStream, TemplateElements *elements, bool render)
{
 
	// Emit header HTML that is shared among all the templates
	*pStream	<<	"<!-- **** Start of HTML from the template **** --> \n";

	// Now emit HTML that is dependent on the particular templateLayout chosen
	switch (elements->templateLayout)
	{
		case 0:	// Emit HMTL for a side-by-side layout
			this->UserPageConvertSideBySideTemplateToHTML(pStream, elements, render);
			break;
		case 1:	// Emit HMTL for a newspaper layout
			this->UserPageConvertNewspaperTemplateToHTML(pStream, elements, render);
			break;
		case 2:	// Emit HMTL for a centered layout
			this->UserPageConvertCenteredTemplateToHTML(pStream, elements, render);
			break;
	}

//	this->UserPageConvertCenteredTemplateToHTML(pStream, elements);

	// Emit footer HTML that is shared among all templates
	*pStream	<<	"<!-- **** End of HTML from the template **** -->";

}


// Given a filled TemplateElements structure and an ostream,
//  write out HTML to the ostream. This HTML will contain
//  appropriately formatted eBay tags based on the TemplateElements
//  that were chosen by a user.
// Typically, the output of this function will then be used by 
//  the WidgetParser to output final HTML.
// This routine does the side-by-side template.
void clseBayApp::UserPageConvertSideBySideTemplateToHTML(ostream *pStream, TemplateElements *elements, bool render)
{
	char *pName;
	char *pStrippedHTML;

	clsWidgetHandler widgetHandler(mpMarketPlace, this);
	clseBayItemListWidget *pItemListWidget;
	clseBayFeedbackWidget *pFeedbackWidget;

	clsItem *pItem;

	// set the user into the widget context
	if (render) widgetHandler.GetWidgetContext()->SetUser(mpUser);

	// Page title (centered) appears above the 2-column table
	if ((elements->pPageTitle) && (strlen(elements->pPageTitle)))
		*pStream	<<	"<center><h1>"
					<<	elements->pPageTitle
					<<	"</h1></center> \n\n";

	// Begin the 2-column table
	*pStream	<<	"<table border=0 cellpadding=5 cellspacing=0 width=580>\n";

	*pStream	<<	" <tr>\n";

	// Column 1
	*pStream	<<	"  <td valign=top>\n";

	// Picture
	if ((elements->pPictureURL) && (strlen(elements->pPictureURL)) && (strcmp(elements->pPictureURL, "http://")))
		*pStream	<<	"    <img src=\""
					<<	elements->pPictureURL
					<<	"\"><br>"
					<<	"\n";				

	// Picture caption
	if ((elements->pPictureCaption) && (strlen(elements->pPictureCaption)))
		*pStream	<<  "    <font size=2>"
					<<	elements->pPictureCaption
					<<	"</font><br><br>"
					<<	"\n\n";

	// Text area title 1
	if ((elements->pTextAreaTitle1) && (strlen(elements->pTextAreaTitle1)))
		*pStream	<<	"    <font color=#CC0000 size=5>"
					<<	elements->pTextAreaTitle1
					<<	"</font><br>"
					<<	"\n";

	// Text area 1
	if ((elements->pTextArea1) && (strlen(elements->pTextArea1)))
		*pStream	<<	"    "
					<<  elements->pTextArea1
					<<	"\n    <br><br>\n\n";
	
	// Text area title 2
	if ((elements->pTextAreaTitle2) && (strlen(elements->pTextAreaTitle2)))
		*pStream	<<	"    <font color=#CC0000 size=5>"
					<<	elements->pTextAreaTitle2
					<<	"</font><br>"
					<<	"\n";

	// Text area 2
	if ((elements->pTextArea2) && (strlen(elements->pTextArea2)))
		*pStream	<<	"    "
					<<  elements->pTextArea2
					<<	"\n    <br><br>\n\n";

	// Favorite items title
	if ((elements->item1) || (elements->item2) || (elements->item3))
		*pStream	<<	"    <font color=#000099 size=5>"
					<<	"Favorite Items"
					<<	"</font><br>"
					<<	"\n";

	// Favorite items 1
	if (elements->item1)
	{
		pItem = mpItems->GetItem(elements->item1, false);	// get item without description
		if (pItem)
		{
			pStrippedHTML = clsUtilities::StripHTML(pItem->GetTitle());

			// linked title
			*pStream	<<	"    <a href=\""
						<<	mpMarketPlace->GetCGIPath(PageViewItem)
						<<	"eBayISAPI.dll?ViewItem&item="
						<<	elements->item1
						<<	"\">"
						<<	pStrippedHTML
						<<	"</a>";

			delete [] pStrippedHTML;

			// caption
			if ((elements->item1CaptionChoice >= 0) && (elements->item1CaptionChoice < NUM_CAPTION_CHOICES - 1))
				*pStream	<<	" ("
							<<	itemCaptions[elements->item1CaptionChoice]
							<<	")";
			
				
			*pStream	<<	"<br>"
						<<	"\n";

			delete pItem;
		}	
	}
	

	// Favorite items 2
	if (elements->item2)
	{

		pItem = mpItems->GetItem(elements->item2, false);	// get item without description
		if (pItem)
		{
			pStrippedHTML = clsUtilities::StripHTML(pItem->GetTitle());

			// linked title
			*pStream	<<	"    <a href=\""
						<<	mpMarketPlace->GetCGIPath(PageViewItem)
						<<	"eBayISAPI.dll?ViewItem&item="
						<<	elements->item2
						<<	"\">"
						<<	pStrippedHTML
						<<	"</a>";

			delete [] pStrippedHTML;

			// caption
			if ((elements->item2CaptionChoice >= 0) && (elements->item2CaptionChoice < NUM_CAPTION_CHOICES - 1))
				*pStream	<<	" ("
							<<	itemCaptions[elements->item2CaptionChoice]
							<<	")";
			
				
			*pStream	<<	"<br>"
						<<	"\n";

			delete pItem;
		}	
	}

	// Favorite items 3
	if (elements->item3)
	{

		pItem = mpItems->GetItem(elements->item3, false);	// get item without description
		if (pItem)
		{
			pStrippedHTML = clsUtilities::StripHTML(pItem->GetTitle());

			// linked title
			*pStream	<<	"    <a href=\""
						<<	mpMarketPlace->GetCGIPath(PageViewItem)
						<<	"eBayISAPI.dll?ViewItem&item="
						<<	elements->item3
						<<	"\">"
						<<	pStrippedHTML
						<<	"</a>";

			delete [] pStrippedHTML;

			// caption
			if ((elements->item3CaptionChoice >= 0) && (elements->item3CaptionChoice < NUM_CAPTION_CHOICES - 1))
				*pStream	<<	" ("
							<<	itemCaptions[elements->item3CaptionChoice]
							<<	")";
			
				
			*pStream	<<	"<br>"
						<<	"\n";

			delete pItem;
		}	
	}

	*pStream	<<	"    <br>\n";

	// Favorite links title
	if (((elements->pFavoritesName1) && (strlen(elements->pFavoritesName1)) && 
		(elements->pFavoritesLink1) && (strlen(elements->pFavoritesLink1))) ||
		((elements->pFavoritesName2) && (strlen(elements->pFavoritesName2)) && 
		(elements->pFavoritesLink2) && (strlen(elements->pFavoritesLink2))) ||
		((elements->pFavoritesName3) && (strlen(elements->pFavoritesName3)) && 
		(elements->pFavoritesLink3) && (strlen(elements->pFavoritesLink3))))

		*pStream	<<	"    <font color=#009900 size=5>"
					<<	"Favorite Links"
					<<	"</font>"
					<<	"<br>\n";

	// Favorite link 1
	if ((elements->pFavoritesLink1) && (strlen(elements->pFavoritesLink1))
		 && strlen(elements->pFavoritesLink1) > 7)
	{

		*pStream	<< "    <!-- Favorite Link #1 -->\n";

		if ((elements->pFavoritesName1) && (strlen(elements->pFavoritesName1)))
			pName = elements->pFavoritesName1;
		else
			pName = elements->pFavoritesLink1;		
		
		// the linked name
		*pStream	<<	"    <b>"
					<<	"<a href=\""
					<<	elements->pFavoritesLink1
					<<	"\">"
					<<	pName
					<<	"</a>"
					<<	"</b>"
					<<	"<br>"
					<<	"\n";

		// the description
		if ((elements->pFavoritesDescription1) && (strlen(elements->pFavoritesDescription1)))
			*pStream	<<	"    "
						<<	elements->pFavoritesDescription1
						<<	"\n";
	}

	// Favorite link 2
	if ((elements->pFavoritesLink2) && (strlen(elements->pFavoritesLink2))
		 && strlen(elements->pFavoritesLink2) > 7)
	{

		*pStream	<< "    <!-- Favorite Link #2 -->\n";

		if ((elements->pFavoritesName2) && (strlen(elements->pFavoritesName2)))
			pName = elements->pFavoritesName2;
		else
			pName = elements->pFavoritesLink2;
		
		// the linked name
		*pStream	<<	"    <b>"
					<<	"<a href=\""
					<<	elements->pFavoritesLink2
					<<	"\">"
					<<	pName
					<<	"</a>"
					<<	"</b>"
					<<	"<br>"
					<<	"\n";

		// the description
		if ((elements->pFavoritesDescription2) && (strlen(elements->pFavoritesDescription2)))
			*pStream	<<	"    "
						<<	elements->pFavoritesDescription2
						<<	"\n";
	}

	// Favorite link 3
	if ((elements->pFavoritesLink3) && (strlen(elements->pFavoritesLink3))
		 && strlen(elements->pFavoritesLink3) > 7)
	{

		*pStream	<< "    <!-- Favorite Link #3 -->\n";

		if ((elements->pFavoritesName3) && (strlen(elements->pFavoritesName3)))
			pName = elements->pFavoritesName3;
		else
			pName = elements->pFavoritesLink3;
		
		// the linked name
		*pStream	<<	"    <b>"
					<<	"<a href=\""
					<<	elements->pFavoritesLink3
					<<	"\">"
					<<	pName
					<<	"</a>"
					<<	"</b>"
					<<	"<br>"
					<<	"\n";

		// the description
		if ((elements->pFavoritesDescription3) && (strlen(elements->pFavoritesDescription3)))
			*pStream	<<	"    "
						<<	elements->pFavoritesDescription3
						<<	"\n";
	}

	// end Column 1
	*pStream	<<	"  </td>\n";

	// Column 2
	*pStream	<<	"  <td valign=top>\n";

	// Items for sale
	if (elements->itemlistNumItems)
	{
		*pStream    << " <table border=0 width=100%><tr><td bgcolor=#FFCC00>";

		*pStream	<< "  \n   <!-- Items for sale graphic --> \n"
			        << "    <img src=\""
                    << mpMarketPlace->GetImagePath()
                    << "aboutme-listings.gif\" width=100 height=20>"
					<< "</tr></td>";

		*pStream    << "<tr><td>\n";
 
		// make the widget
		pItemListWidget = (clseBayItemListWidget*)widgetHandler.GetWidget(wtItemListWidget);

		// set parameters
		pItemListWidget->SetNumItems(elements->itemlistNumItems);
		if ((elements->pItemlistCaption) && (strlen(elements->pItemlistCaption)))
		{
			pItemListWidget->SetCaption(elements->pItemlistCaption);

			//pStrippedHTML = clsUtilities::StripHTML(elements->pItemlistCaption);
			//pItemListWidget->SetCaption(pStrippedHTML);
			//delete [] pStrippedHTML;
		}
 
	    pItemListWidget->SetUser(mpUser);

		// draw the tag
		// draw the HTML or the tag

		if (render)
			pItemListWidget->EmitHTML(pStream);
		else
		{
			*pStream	<< "    ";
			pItemListWidget->DrawTag(pStream, "ebayitemlist");
		}

		// delete the widget
		widgetHandler.ReleaseWidget(pItemListWidget);

		*pStream    << "</td></tr></table>\n";

		*pStream	<<	"\n    <br><br>\n";
	}

	// Feedback comments
	if (elements->feedbackNumComments)
	{
		*pStream    << " <table border=0 width=100%><tr><td bgcolor=#99CC00>";

		*pStream	<< " \n <!-- Feedback graphic -->\n"
			        << "    <img src=\""
					<< mpMarketPlace->GetImagePath()
                    << "aboutme-feedback.gif\" width=100 height=20>"
					<< "</td></tr>";

		*pStream    << "<tr><td>\n";

		// make the widget
		pFeedbackWidget = (clseBayFeedbackWidget*)widgetHandler.GetWidget(wtFeedbackWidget);

		// set parameters
		pFeedbackWidget->SetNumberOfItemToDisplay(elements->feedbackNumComments);
		pFeedbackWidget->SetUser(mpUser);

		// draw the HTML or the tag
		if (render)
			pFeedbackWidget->EmitHTML(pStream);
		else
		{
			*pStream	<< "    ";
			pFeedbackWidget->DrawTag(pStream, "ebayfeedback");
		}

		// delete the widget
		widgetHandler.ReleaseWidget(pFeedbackWidget);

		*pStream    << "</td></tr></table>\n";

		*pStream	<<	"\n    <br><br>\n";
	}

	// end Column 2
	*pStream	<<	"  </td>\n";

	*pStream	<<	" </tr>\n";

	// End the 2-column table
	*pStream	<<	"</table>\n";


	return;
}



// Given a filled TemplateElements structure and an ostream,
//  write out HTML to the ostream. This HTML will contain
//  appropriately formatted eBay tags based on the TemplateElements
//  that were chosen by a user.
// Typically, the output of this function will then be used by 
//  the WidgetParser to output final HTML.
// This routine does the newspaper-style template.
void clseBayApp::UserPageConvertNewspaperTemplateToHTML(ostream *pStream, TemplateElements *elements, bool render)
{
	char *pName;
	char *pStrippedHTML;

	clsWidgetHandler widgetHandler(mpMarketPlace, this);

	clseBayFeedbackWidget *pFeedbackWidget;
	clseBayItemListWidget *pItemListWidget;

	clsItem *pItem;


	// set the user into the widget context
	if (render) widgetHandler.GetWidgetContext()->SetUser(mpUser);

	// Page title as the main headline
	if ((elements->pPageTitle) && (strlen(elements->pPageTitle)))
		*pStream	<<	"<h1>"
					<<	elements->pPageTitle
					<<	"</h1> \n\n";

	// Begin the 2-column table
	*pStream	<<	"<table border=0 cellpadding=5 cellspacing=0 width=580>\n";

	*pStream	<<	" <tr>\n";

	// Column 1
	*pStream	<<	"  <td valign=top>\n";

	// Text area title 1
	if ((elements->pTextAreaTitle1) && (strlen(elements->pTextAreaTitle1)))
		*pStream	<<	"    <font color=#CC0000 size=5><b>"
					<<	elements->pTextAreaTitle1
					<<	"</b></font><br>"
					<<	"\n";

	// Text area 1
	if ((elements->pTextArea1) && (strlen(elements->pTextArea1)))
		*pStream	<<	"    "
					<<  elements->pTextArea1
					<<	"\n\n";
	
	// end Column 1
	*pStream	<<	"  </td>\n";

	// Column 2
	*pStream	<<	"  <td valign=top>\n";

	// Picture
	if ((elements->pPictureURL) && (strlen(elements->pPictureURL)) && (strcmp(elements->pPictureURL, "http://")))
	{
	
		*pStream	<<	"    <img src=\""
					<<	elements->pPictureURL
					<<	"\"><br>"
					<<	"\n";				

		// Picture caption
		if ((elements->pPictureCaption) && (strlen(elements->pPictureCaption)))
			*pStream	<<  "    <font size=3>"
						<<	elements->pPictureCaption
						<<	"</font><br><br>"
						<<	"\n\n";
	}
 
	// end Column 2
	*pStream	<<	"  </td>\n";

	*pStream	<<	" </tr>\n";

	// End the 2-column table
	*pStream	<<	"</table>\n";


	// Begin the 3-column table
	*pStream	<<	"<table border=0 cellpadding=5 cellspacing=0 width=580>\n";

	*pStream	<<	" <tr>\n";

	// Column 1
	*pStream	<<	"  <td valign=top width=33%>\n";

	// Text area title 2 

	if ((elements->pTextAreaTitle2) && (strlen(elements->pTextAreaTitle2)))
		*pStream	<<	"    <font color=#CC0033 size=5>"
					<<	elements->pTextAreaTitle2
					<<	"</font><br>"
					<<	"\n";

	// Text area 2
	if ((elements->pTextArea2) && (strlen(elements->pTextArea2)))
		*pStream	<<	"    "
					<<  elements->pTextArea2
					<<	"\n    <br><br>\n\n";

	// end Column 1
	*pStream	<<	"  </td>\n";

	// Column 2
	*pStream	<<	"  <td valign=top width=33%>\n";

	// Favorite items title
	if ((elements->item1) || (elements->item2) || (elements->item3))
		*pStream	<<	"    <font color=#000099 size=5>"
					<<	"Favorite Items"
					<<	"</font><br>"
					<<	"\n";

	// Favorite items 1
	if (elements->item1)
	{
		pItem = mpItems->GetItem(elements->item1, false);	// get item without description
		if (pItem)
		{
			pStrippedHTML = clsUtilities::StripHTML(pItem->GetTitle());

			// linked title
			*pStream	<<	"    <a href=\""
						<<	mpMarketPlace->GetCGIPath(PageViewItem)
						<<	"eBayISAPI.dll?ViewItem&item="
						<<	elements->item1
						<<	"\">"
						<<	pStrippedHTML
						<<	"</a>";

			delete [] pStrippedHTML;

			// caption
			if ((elements->item1CaptionChoice >= 0) && (elements->item1CaptionChoice < NUM_CAPTION_CHOICES - 1))
				*pStream	<<	" ("
							<<	itemCaptions[elements->item1CaptionChoice]
							<<	")";
			
				
			*pStream	<<	"<br>"
						<<	"\n";

			delete pItem;
		}	
	

	}

	// Favorite items 2
	if (elements->item2)
	{

		pItem = mpItems->GetItem(elements->item2, false);	// get item without description
		if (pItem)
		{

			pStrippedHTML = clsUtilities::StripHTML(pItem->GetTitle());

			// linked title
			*pStream	<<	"    <a href=\""
						<<	mpMarketPlace->GetCGIPath(PageViewItem)
						<<	"eBayISAPI.dll?ViewItem&item="
						<<	elements->item2
						<<	"\">"
						<<	pStrippedHTML
						<<	"</a>";

			delete [] pStrippedHTML;

			// caption
			if ((elements->item2CaptionChoice >= 0) && (elements->item2CaptionChoice < NUM_CAPTION_CHOICES - 1))
				*pStream	<<	" ("
							<<	itemCaptions[elements->item2CaptionChoice]
							<<	")";
			
				
			*pStream	<<	"<br>"
						<<	"\n";

			delete pItem;
		}
	}


	// Favorite items 3
	if (elements->item3)
	{

		pItem = mpItems->GetItem(elements->item3, false);	// get item without description
		if (pItem)
		{
			pStrippedHTML = clsUtilities::StripHTML(pItem->GetTitle());

			// linked title
			*pStream	<<	"    <a href=\""
						<<	mpMarketPlace->GetCGIPath(PageViewItem)
						<<	"eBayISAPI.dll?ViewItem&item="
						<<	elements->item3
						<<	"\">"
						<<	pStrippedHTML
						<<	"</a>";

			delete [] pStrippedHTML;

			// caption
			if ((elements->item3CaptionChoice >= 0) && (elements->item3CaptionChoice < NUM_CAPTION_CHOICES - 1))
				*pStream	<<	" ("
							<<	itemCaptions[elements->item3CaptionChoice]
							<<	")";
			
				
			*pStream	<<	"<br>"
						<<	"\n";

			delete pItem;
		}	
	}

	// end Column 2
	*pStream	<<	"  </td>\n";

	// Column 3
	*pStream	<<	"  <td valign=top width=33%>\n";

	// Favorite links title
	if (((elements->pFavoritesName1) && (strlen(elements->pFavoritesName1)) && 
		(elements->pFavoritesLink1) && (strlen(elements->pFavoritesLink1))) ||
		((elements->pFavoritesName2) && (strlen(elements->pFavoritesName2)) && 
		(elements->pFavoritesLink2) && (strlen(elements->pFavoritesLink2))) ||
		((elements->pFavoritesName3) && (strlen(elements->pFavoritesName3)) && 
		(elements->pFavoritesLink3) && (strlen(elements->pFavoritesLink3))))

		*pStream	<<	"    <font color=#009900 size=5>"
					<<	"Favorite Links"
					<<	"</font>"
					<<	"<br>\n";

	// Favorite link 1
	if ((elements->pFavoritesLink1) && (strlen(elements->pFavoritesLink1))
		 && strlen(elements->pFavoritesLink1) > 7)
	{

		*pStream	<< "    <!-- Favorite Link #1 -->\n";

		if ((elements->pFavoritesName1) && (strlen(elements->pFavoritesName1)))
			pName = elements->pFavoritesName1;
		else
			pName = elements->pFavoritesLink1;
		
		// the linked name
		*pStream	<<	"    <b>"
					<<	"<a href=\""
					<<	elements->pFavoritesLink1
					<<	"\">"
					<<	pName
					<<	"</a>"
					<<	"</b>"
					<<	"<br>"
					<<	"\n";

		// the description
		if ((elements->pFavoritesDescription1) && (strlen(elements->pFavoritesDescription1)))
			*pStream	<<	"    "
						<<	elements->pFavoritesDescription1
						<<	"\n";
	}

	// Favorite link 2
	if ((elements->pFavoritesLink2) && (strlen(elements->pFavoritesLink2))
		 && strlen(elements->pFavoritesLink2) > 7)
	{

		*pStream	<< "    <!-- Favorite Link #2 -->\n";

		if ((elements->pFavoritesName2) && (strlen(elements->pFavoritesName2)))
			pName = elements->pFavoritesName2;
		else
			pName = elements->pFavoritesLink2;
		
		// the linked name
		*pStream	<<	"    <b>"
					<<	"<a href=\""
					<<	elements->pFavoritesLink2
					<<	"\">"
					<<	pName
					<<	"</a>"
					<<	"</b>"
					<<	"<br>"
					<<	"\n";

		// the description
		if ((elements->pFavoritesDescription2) && (strlen(elements->pFavoritesDescription2)))
			*pStream	<<	"    "
						<<	elements->pFavoritesDescription2
						<<	"\n";
	}

	// Favorite link 3
	if ((elements->pFavoritesLink3) && (strlen(elements->pFavoritesLink3))
		 && strlen(elements->pFavoritesLink3) > 7)
	{

		*pStream	<< "    <!-- Favorite Link #3 -->\n";

		if ((elements->pFavoritesName3) && (strlen(elements->pFavoritesName3)))
			pName = elements->pFavoritesName3;
		else
			pName = elements->pFavoritesLink3;
		
		// the linked name
		*pStream	<<	"    <b>"
					<<	"<a href=\""
					<<	elements->pFavoritesLink3
					<<	"\">"
					<<	pName
					<<	"</a>"
					<<	"</b>"
					<<	"<br>"
					<<	"\n";

		// the description
		if ((elements->pFavoritesDescription3) && (strlen(elements->pFavoritesDescription3)))
			*pStream	<<	"    "
						<<	elements->pFavoritesDescription3
						<<	"\n";
	}

	// end Column 3
	*pStream	<<	"  </td>\n";

	*pStream	<<	" </tr>\n";

	// End the 3-column table
	*pStream	<<	"</table>\n";

	*pStream	<<	" <br><br>\n";

	// Items for sale
	if (elements->itemlistNumItems)
	{
		*pStream    << " <table border=0 width=100%><tr><td bgcolor=#FFCC00>";

		*pStream	<< "   \n  <!-- Items for sale graphic --> \n"
			        << "    <img src=\""
					<< mpMarketPlace->GetImagePath()
					<< "aboutme-listings.gif\" width=100 height=20>"
					<< "</td></tr>";

		*pStream    << "<tr><td>\n";

		// make the widget
		pItemListWidget = (clseBayItemListWidget*)widgetHandler.GetWidget(wtItemListWidget);
 
		// set parameters
		pItemListWidget->SetNumItems(elements->itemlistNumItems);
		if ((elements->pItemlistCaption) && (strlen(elements->pItemlistCaption)))
		{
			pItemListWidget->SetCaption(elements->pItemlistCaption);
			//pStrippedHTML = clsUtilities::StripHTML(elements->pItemlistCaption);
			//pItemListWidget->SetCaption(pStrippedHTML);
			//delete [] pStrippedHTML;
		}

	    pItemListWidget->SetUser(mpUser);

		// draw the tag  
		// draw the HTML or the tag

		if (render)
			pItemListWidget->EmitHTML(pStream);
		else
		{
			*pStream	<< "    ";
			pItemListWidget->DrawTag(pStream, "ebayitemlist");
		}

		// delete the widget
		widgetHandler.ReleaseWidget(pItemListWidget);

		*pStream    << "</td></tr></table>\n\n";

		*pStream	<<	"\n    <br><br>\n";
	}

	// Feedback comments
	if (elements->feedbackNumComments)
	{
		*pStream    << " <table border=0 width=100%><tr><td bgcolor=#99CC00>";

		*pStream	<< " \n <!-- Feedback graphic -->\n"
			        << "    <img src=\""
					<< mpMarketPlace->GetImagePath()
					<< "aboutme-feedback.gif\" width=100 height=20>"
					<< "</td></tr>";

		*pStream    << "<tr><td>";

		// make the widget
		pFeedbackWidget = (clseBayFeedbackWidget*)widgetHandler.GetWidget(wtFeedbackWidget);

		// set parameters
		pFeedbackWidget->SetNumberOfItemToDisplay(elements->feedbackNumComments);
		pFeedbackWidget->SetUser(mpUser);

		// draw the HTML or the tag
		if (render)
			pFeedbackWidget->EmitHTML(pStream);
		else
		{
			*pStream	<< "    ";
			pFeedbackWidget->DrawTag(pStream, "ebayfeedback");
		}

		// delete the widget
		widgetHandler.ReleaseWidget(pFeedbackWidget);

		*pStream    << "</td></tr></table>";

		*pStream	<<	"\n    <br><br>\n";
	}

	return;
}


// Given a filled TemplateElements structure and an ostream,
//  write out HTML to the ostream. This HTML will contain
//  appropriately formatted eBay tags based on the TemplateElements
//  that were chosen by a user.
// Typically, the output of this function will then be used by 
//  the WidgetParser to output final HTML.
// This routine does the centered template.
void clseBayApp::UserPageConvertCenteredTemplateToHTML(ostream *pStream, TemplateElements *elements, bool render)
{
	char *pName;
	char *pStrippedHTML;
	bool  needSpace = false;

	clsWidgetHandler widgetHandler(mpMarketPlace, this);

	clseBayFeedbackWidget *pFeedbackWidget;
	clseBayItemListWidget *pItemListWidget;

	//clseBayTimeWidget *pTimeWidget;

	clsItem *pItem;

	// set the user into the widget context
	if (render) widgetHandler.GetWidgetContext()->SetUser(mpUser);

	// Center everything
	*pStream	<<	"<center>\n";

	// Page title
	if ((elements->pPageTitle) && (strlen(elements->pPageTitle)))
		*pStream	<<	"<h1>"
					<<	elements->pPageTitle
					<<	"</h1>"
					<<	"\n";

	// Text area title 1
	if ((elements->pTextAreaTitle1) && (strlen(elements->pTextAreaTitle1)))
		*pStream	<<	"<font color=red><h2>"
					<<	elements->pTextAreaTitle1
					<<	"</h2></font>"
					<<	"\n";

	// Text area 1
	if ((elements->pTextArea1) && (strlen(elements->pTextArea1)))
	{
		*pStream	<<	elements->pTextArea1
					<<	"\n";
	}
	

	// Text area title 2
	if ((elements->pTextAreaTitle2) && strlen(elements->pTextAreaTitle2))
	{

		*pStream << "<hr width=50% align=center>\n";

		*pStream	<<  "<font color=red><h2>"
					<<	elements->pTextAreaTitle2
					<<	"</h2></font>"
					<<	"\n";
	}

	// Text area 2
	if ((elements->pTextArea2) && (strlen(elements->pTextArea2)))
		*pStream	<<	elements->pTextArea2
					<<	"\n";

	// Picture caption
	if ((elements->pPictureCaption) && (strlen(elements->pPictureCaption)))
		*pStream	<<	"<hr width=50% align=center>\n"
					<<  "<h3>"
					<<	elements->pPictureCaption
					<<	"</h3>"
					<<	"\n";

	// Picture
	if ((elements->pPictureURL) && (strlen(elements->pPictureURL)) && (strcmp(elements->pPictureURL, "http://")))
		*pStream	<<	"<img src=\""
					<<	elements->pPictureURL
					<<	"\">"
					<<  "<br>"
					<<	"\n";

	needSpace = false;

	// Feedback comments
	if (elements->feedbackNumComments)
	{


		*pStream   << "<br><br><hr width=50% align=center><br><br>\n";

		*pStream    << "<table border=0 width=100%> <tr><td bgcolor=#99CC00>";
		*pStream   << " \n <!-- Feedback graphic --> \n"
			       << "<img src=\""
		 	        << mpMarketPlace->GetImagePath()
			        << "aboutme-feedback.gif\" width=100 height=20>"
					<< "</td></tr>";

		*pStream   << "<tr><td>\n";

		// make the widget
		pFeedbackWidget = (clseBayFeedbackWidget*)widgetHandler.GetWidget(wtFeedbackWidget);

		// set parameters
		pFeedbackWidget->SetNumberOfItemToDisplay(elements->feedbackNumComments);
		pFeedbackWidget->SetUser(mpUser);

		*pStream   << "\n\n <!-- Feedback --> \n";

		// draw the HTML or the tag
		if (render)
			pFeedbackWidget->EmitHTML(pStream);
		else
			pFeedbackWidget->DrawTag(pStream, "ebayfeedback");

		// delete the widget
		widgetHandler.ReleaseWidget(pFeedbackWidget);

		*pStream	<<	"</td></tr></table> \n";

		needSpace = true;
	}

	// Items for sale
	if (elements->itemlistNumItems)
	{


		*pStream   << "<br><br><hr width=50% align=center><br><br>\n";

		*pStream	<<	"<table border=0 width=100%> <tr><td bgcolor=#FFCC00> \n";

		*pStream   << " \n <!-- Items for sale graphic --> \n"
			        << "<img src=\""
					<< mpMarketPlace->GetImagePath()
			        << "aboutme-listings.gif\" width=100 height=20>"
					<< "</td></tr> <tr><td>";

		*pStream   << "\n\n <!-- Items for sale --> \n";

		// make the widget
		pItemListWidget = (clseBayItemListWidget*)widgetHandler.GetWidget(wtItemListWidget);
 
		// set parameters
		pItemListWidget->SetNumItems(elements->itemlistNumItems);
		if ((elements->pItemlistCaption) && (strlen(elements->pItemlistCaption)))
		{
			pItemListWidget->SetCaption(elements->pItemlistCaption);
			//pStrippedHTML = clsUtilities::StripHTML(elements->pItemlistCaption);
			//pItemListWidget->SetCaption(pStrippedHTML);
			//delete [] pStrippedHTML;
		}

	    pItemListWidget->SetUser(mpUser);

		// draw the tag
		// draw the HTML or the tag

		if (render)
			pItemListWidget->EmitHTML(pStream);
		else
			pItemListWidget->DrawTag(pStream, "ebayitemlist");

		// delete the widget
		widgetHandler.ReleaseWidget(pItemListWidget);

		*pStream	<<	"</td> </tr> </table> \n";

		needSpace = true;
	}

	// Favorite links title
	if (((elements->pFavoritesName1) && (strlen(elements->pFavoritesName1)) && 
		(elements->pFavoritesLink1) && (strlen(elements->pFavoritesLink1))) ||
		((elements->pFavoritesName2) && (strlen(elements->pFavoritesName2)) && 
		(elements->pFavoritesLink2) && (strlen(elements->pFavoritesLink2))) ||
		((elements->pFavoritesName3) && (strlen(elements->pFavoritesName3)) && 
		(elements->pFavoritesLink3) && (strlen(elements->pFavoritesLink3))))
	{

		if (needSpace)
			*pStream << "<br><br> \n";

		*pStream	<<	"    <font color=#009900 size=5>"
					<<	"Favorite Links"
					<<	"</font>"
					<<	"<br>\n";

		needSpace = true;
	}

	// Favorite link 1
	if ((elements->pFavoritesLink1) && (strlen(elements->pFavoritesLink1))
		 && strlen(elements->pFavoritesLink1) > 7)
	{

		*pStream	<< "    <!-- Favorite Link #1 -->\n";

		if ((elements->pFavoritesName1) && (strlen(elements->pFavoritesName1)))
			pName = elements->pFavoritesName1;
		else
			pName = elements->pFavoritesLink1;

		// the linked name
		*pStream	<<	"<p>"
					<<	"<b>"
					<<	"<a href=\""
					<<	elements->pFavoritesLink1
					<<	"\">"
					<<	pName
					<<	"</a>"
					<<	"</b>"
					<<	"<br>"
					<<	"\n";

		// the description
		if ((elements->pFavoritesDescription1) && (strlen(elements->pFavoritesDescription1)))
			*pStream	<<	elements->pFavoritesDescription1
						<<	"\n";
	
	}

	// Favorite link 2
	if ((elements->pFavoritesLink2) && (strlen(elements->pFavoritesLink2))
		 && strlen(elements->pFavoritesLink2) > 7)
	{

		*pStream	<< "    <!-- Favorite Link #2 -->\n";

		if ((elements->pFavoritesName2) && (strlen(elements->pFavoritesName2)))
			pName = elements->pFavoritesName2;
		else
			pName = elements->pFavoritesLink2;

		// the linked name
		*pStream	<<	"<p>"
					<<	"<b>"
					<<	"<a href=\""
					<<	elements->pFavoritesLink2
					<<	"\">"
					<<	pName
					<<	"</a>"
					<<	"</b>"
					<<	"<br>"
					<<	"\n";

		// the description
		if ((elements->pFavoritesDescription2) && (strlen(elements->pFavoritesDescription2)))
			*pStream	<<	elements->pFavoritesDescription2
					<<	"\n";
	
	}

	// Favorite link 3
	if ((elements->pFavoritesLink3) && (strlen(elements->pFavoritesLink3)) 
		 && strlen(elements->pFavoritesLink3) > 7)
	{

		*pStream	<< "    <!-- Favorite Link #3 -->\n";

		if ((elements->pFavoritesName3) && (strlen(elements->pFavoritesName3)))
			pName = elements->pFavoritesName3;
		else
			pName = elements->pFavoritesLink3;


		// the linked name
		*pStream	<<	"<p>"
					<<	"<b>"
					<<	"<a href=\""
					<<	elements->pFavoritesLink3
					<<	"\">"
					<<	pName
					<<	"</a>"
					<<	"</b>"
					<<	"<br>"
					<<	"\n";

		// the description
		if ((elements->pFavoritesDescription3) && (strlen(elements->pFavoritesDescription3)))
			*pStream	<<	elements->pFavoritesDescription3
						<<	"\n";
		
	}


	// Favorite items title
	if ((elements->item1) || (elements->item2) || (elements->item3))
	{

		if (needSpace) 
			*pStream << "<br><br>\n";

		*pStream	<<	"    <font color=#000099 size=5>"
					<<	"Favorite Items"
					<<	"</font><br>"
					<<	"\n";

	}

	if (elements->item1)
	{
		pItem = mpItems->GetItem(elements->item1, false);	// get item without description
		if (pItem)
		{

			pStrippedHTML = clsUtilities::StripHTML(pItem->GetTitle());

			// linked title
			*pStream	<<	"<a href=\""
						<<	mpMarketPlace->GetCGIPath(PageViewItem)
						<<	"eBayISAPI.dll?ViewItem&item="
						<<	elements->item1
						<<  "\">"
						<<	pStrippedHTML
						<<	"</a>";

			delete [] pStrippedHTML;

			// caption
			if ((elements->item1CaptionChoice >= 0) && (elements->item1CaptionChoice < NUM_CAPTION_CHOICES - 1))
				*pStream	<<	" ("
							<<	itemCaptions[elements->item1CaptionChoice]
							<<	")";
			
				
			*pStream	<<	"<br>"
						<<	"\n";

			delete pItem;
		}	
	}


	// Favorite items 2
	if (elements->item2)
	{

		*pStream	<< "    <!-- Favorite Item #2 -->\n";

		pItem = mpItems->GetItem(elements->item2, false);	// get item without description

		if (pItem)
		{
			pStrippedHTML = clsUtilities::StripHTML(pItem->GetTitle());

			// linked title
			*pStream	<<	"<a href=\""
						<<	mpMarketPlace->GetCGIPath(PageViewItem)
						<<	"eBayISAPI.dll?ViewItem&item="
						<<	elements->item2
						<<	"\">"
						<<	pStrippedHTML
						<<	"</a>";

			delete [] pStrippedHTML;

			// caption
			if ((elements->item2CaptionChoice >= 0) && (elements->item2CaptionChoice < NUM_CAPTION_CHOICES - 1))
				*pStream	<<	" ("
							<<	itemCaptions[elements->item2CaptionChoice]
							<<	")";
			
				
			*pStream	<<	"<br>"
						<<	"\n";

			delete pItem;
		}	
	}

	// Favorite items 3
	if (elements->item3)
	{
		*pStream	<< "    <!-- Favorite Item #3 -->\n";

		pItem = mpItems->GetItem(elements->item3, false);	// get item without description
		if (pItem)
		{
			pStrippedHTML = clsUtilities::StripHTML(pItem->GetTitle());

			// linked title
			*pStream	<<	"<a href=\""
						<<	mpMarketPlace->GetCGIPath(PageViewItem)
						<<	"eBayISAPI.dll?ViewItem&item="
						<<	elements->item3
						<<	"\">"
						<<	pStrippedHTML
						<<	"</a>";

			delete [] pStrippedHTML;

			// caption
			if ((elements->item3CaptionChoice >= 0) && (elements->item3CaptionChoice < NUM_CAPTION_CHOICES - 1))
				*pStream	<<	" ("
							<<	itemCaptions[elements->item3CaptionChoice]
							<<	")";
			
				
			*pStream	<<	"<br>"
						<<	"\n";

			delete pItem;
		}	
	}

	// End centering
	*pStream	<<	"</center>\n";
}


