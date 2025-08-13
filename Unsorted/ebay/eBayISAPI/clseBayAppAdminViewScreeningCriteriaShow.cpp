/*	$Id: clseBayAppAdminViewScreeningCriteriaShow.cpp,v 1.2 1999/05/19 02:34:25 josh Exp $	*/
//
//	File:		clseBayAppAdminViewScreeningCriteriaShow.cpp
//
//	Class:		clseBayApp
//
//	Author:		Lou Leonardo (lou@ebay.com)
//
//	Function:	clseBayApp::clseBayAppAdminViewScreeningCriteriaShow
//
//
//	Modifications:
//				- 04/11/99 lou - Created

//	For use with Legal Buddies and Bottom Feeder.


#include "ebihdr.h"

static const int nAddAction = 0;
static const int nModifyAction = 1;
static const int nDeleteAction = 2;

//
// sort_filter_name
//
//	A private sort routine sort all filters by name
//
static bool sort_filter_name(clsFilter *pA, clsFilter *pB)
{
	if (strcmpi(pA->GetName() ,pB->GetName()) <= 0)
		return true;

	return false;
}

//
// sort_message_name
//
//	A private sort routine sort all messages by name
//
static bool sort_message_name(clsMessage *pA, clsMessage *pB)
{
	if (strcmpi(pA->GetName() ,pB->GetName()) <= 0)
		return true;

	return false;
}


void clseBayApp::AdminViewScreeningCriteriaShow(CEBayISAPIExtension *pThis,
													CategoryId categoryid, 
													eBayISAPIAuthEnum authLevel) 
{

	FilterVector 			vCatFilters;
	FilterVector::iterator	iCF;
	MessageVector 			vCatMsgs;
	MessageVector::iterator	vCM;
	clsCategory				*pCategory = NULL;
	clsMessages             *pMessages = NULL;
	bool					error = false;
	bool					bShowRemoveInfo = false;

	// Setup
	SetUp();	

	EmitHeader("View Screening Criteria");

	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp();
		return;
	}

	// Header
	*mpStream	<<	"\n"
					"<h2>View Category Screening Criteria</h2>\n";

	// Make sure we have a valid category, we get this from cache so no need to delete it
	if (categoryid)
		pCategory	= mpCategories->GetCategory(categoryid, true);

	if (!categoryid || !pCategory)
	{
		*mpStream	<<	"<p><b>Category Missing:</b><br>"
					<<	"Please make sure that a category is selected.\n"
					<<	"<p><strong>Please go back and try again.</strong>\n"
					<<	mpMarketPlace->GetFooter()
					<<	flush;
		CleanUp();
		return;
	}

	// Start form
	*mpStream	<<	"<form method=post action=\""
				<<	mpMarketPlace->GetCGIPath(PageAdminAddScreeningCriteriaShow)
				<<	"eBayISAPI.dll?AdminAddScreeningCriteriaShow\">\n";
					
	// Add items we want to pass through
	*mpStream	<<	"<input type=hidden name=categoryid value=\""
				<<	categoryid
				<<	"\">\n";

	// Display category and parents
	*mpStream	<<	"<b>Selected Category: ";

	mpCategories->EmitHTMLQualifiedName(mpStream, pCategory);

	*mpStream	<<	"</b>\n";

	// Filter Title
	*mpStream	<<	"<p><b>Filters:</b><br>\n";

	// Get Filters for the category
	mpMarketPlace->GetFilters()->GetThisAndParentCategoryFilters(categoryid, &vCatFilters, true);

	// See if we got anything
	if (vCatFilters.size() > 0)
	{
		// If we have any filters, sort them
		sort(vCatFilters.begin(), vCatFilters.end(), sort_filter_name);

		// Set flag so we can display remove info at bottom
		bShowRemoveInfo = true;

		// Start List box
		*mpStream	<<	"<SELECT SIZE=15 NAME=\"filterid\">\n";

		// Add items to a list
		for (iCF = vCatFilters.begin(); iCF != vCatFilters.end(); iCF++)
		{
			// Add Filter name and id to list
			*mpStream	<<	"<OPTION VALUE=\""
						<<	(*iCF)->GetId()
						<<	"\">"
						<<	(*iCF)->GetName()
						<< "</OPTION>\n";
		}

		// Finish the listbox
		*mpStream	<<	"</SELECT>\n";
	}
	else
	{
		// No Filters selected with this category
		*mpStream	<<	"There are no Filters selected "
					<<	"for this Category at this time.\n";
	}

	// Messages Title
	*mpStream	<<	"<p><b>Messages:</b><br>\n";

	// Get Messages for the category
    // mpMarketPlace->GetMessages()->GetMessagesByCategoryId(categoryid, &vCatMsgs, true);
	// Replaced above line with following steps: now it gets the most specific messages
	// of each type - Anoop (4/23/99).
	pMessages = mpMarketPlace->GetMessages();
	pMessages->GetMessages(categoryid, MessageTypeCategorySellerWhenListing, 
						   &vCatMsgs, true);
	pMessages->GetMessages(categoryid, MessageTypeCategoryBidderWhenBidding, 
						   &vCatMsgs, true);
	pMessages->GetMessages(categoryid, MessageTypeItemBlockedWhenListing, 
						   &vCatMsgs, true);
	pMessages->GetMessages(categoryid, MessageTypeItemFlaggedWhenListing, 
						   &vCatMsgs, true);

	// See if we got anything
	if (vCatMsgs.size() > 0)
	{
		// Sort messages
		sort(vCatMsgs.begin(), vCatMsgs.end(), sort_message_name);

		// Set flag so we can display remove info at bottom
		bShowRemoveInfo = true;

		// Start list box
		*mpStream	<<	"<SELECT SIZE=15 NAME=\"messageid\">\n";

		// Add items to a list
		for (vCM = vCatMsgs.begin(); vCM != vCatMsgs.end(); vCM++)
		{
			// Add message name and id to list
			*mpStream	<<	"<OPTION VALUE=\""
						<<	(*vCM)->GetId()
						<<	"\">"
						<<	(*vCM)->GetName()
						<< "</OPTION>\n";
		}

		// Finish the listbox
		*mpStream	<<	"</SELECT>";
	}
	else
	{
		// No Messages selected with this category
		*mpStream	<<	"There are no Messages selected "
					<<	"for this Category at this time.\n";
	}

	// Have link to Add a new filter or message to a category
	*mpStream	<<	"<p><a href=\""
				<<	mpMarketPlace->GetCGIPath(PageAdminAddScreeningCriteria)
				<<	"eBayISAPI.dll?AdminAddScreeningCriteria&categoryid="
				<<	categoryid
				<<	"\">"
				<<	"Add another Filter or Message to this Category.</a>\n";

	// Add our last hidden item
	*mpStream	<<	"<input type=hidden name=action value=\""
				<<	nDeleteAction
				<<	"\">\n";
	
	// Remove Filters and Messages
	if ( bShowRemoveInfo )
	{
		// Only display this if we have messages and/or filters for this category
		*mpStream	<<	"<p>You can remove a Filter or Message "
					<<	"from this Category by selecting an item "
					<<	"from one of the above lists.<br>\n"
					<<	"Then Press "
					<<	"<input type=submit value=\"Remove \">"
					<<	" to continue.\n";
	}

	// Footer
	*mpStream	<<	"<p>"
				<<	mpMarketPlace->GetFooter()
				<<	flush;

	// Clean up vectors
	vCatFilters.erase(vCatFilters.begin(), vCatFilters.end());
	vCatMsgs.erase(vCatMsgs.begin(), vCatMsgs.end());

	CleanUp();

	return;
}

