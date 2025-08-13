/*	$Id: clseBayAppAdminAddScreeningCriteria.cpp,v 1.2 1999/05/19 02:34:20 josh Exp $	*/
//
//	File:		clseBayAppAdminAddScreeningCriteria.cpp
//
//	Class:		clseBayApp
//
//	Author:		Lou Leonardo (lou@ebay.com)
//
//	Function:	clseBayApp::clseBayAppAdminAddScreeningCriteria
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
// sort_message_name
//
//	A private sort routine sort all filters by name
//
static bool sort_vector_name(clsFilter *pA, clsFilter *pB)
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



// Build Filters combo box
void clseBayApp::BuildFilterComboBox(char *pTitle)
{
	FilterVector			vFilters;
	FilterVector::iterator	i;

	// Setup combo box for min list
	*mpStream	<<	"<SELECT SIZE=1 NAME=\""
				<<	pTitle
				<<	"\"> "
				<<	"<OPTION VALUE=\"0\"></OPTION>\n";

	// Get a vector of current Filters
	mpMarketPlace->GetFilters()->GetAllFilters(&vFilters, true);

	// If we have any filters, sort them
	if (vFilters.size() > 0)
	{
		sort(vFilters.begin(), vFilters.end(), sort_vector_name);
	}

	// Loop through filters and add to listbox
	for (i = vFilters.begin(); i != vFilters.end(); i++)
	{
		*mpStream	<<	"<OPTION VALUE=\""
					<<	(int)(*i)->GetId()
					<<	"\">"
					<<	(*i)->GetName()
					<<	"</OPTION>\n";	
	}

	// We're done adding close code					
	*mpStream	<<	"</SELECT>"
				<<	"<br>\n";

	// Clean up memory
	vFilters.erase(vFilters.begin(), vFilters.end());
}

// Build Messages combo box
void clseBayApp::BuildMessageComboBox(char *pTitle, int nMessageTypes,
										int selected)
{
	int						nMask = 1;
	MessageVector			vMessages;
	MessageVector::iterator	i;

	// Setup combo box for min list
	*mpStream	<<	"<SELECT SIZE=1 NAME=\""
				<<	pTitle
				<<	"\"> "
				<<	"<OPTION VALUE=\"0\"></OPTION>\n";

	while( true )
	{
		// Check if it is one of the messageTypes we want
		if (nMessageTypes & nMask)
		{
			// We do want the messages for this type, go get them
			mpMarketPlace->GetMessages()->GetMessagesByMessageType((MessageType) nMask, &vMessages, true);

			// If we have any filters, sort them
			if (vMessages.size() > 0)
			{
				sort(vMessages.begin(), vMessages.end(), sort_message_name);
			}

			// Loop through filters and add to listbox
			for (i = vMessages.begin(); i != vMessages.end(); i++)
			{
				*mpStream	<<	"<OPTION VALUE=\""
							<<	(int)(*i)->GetId()
							<<	"\"";

				// Select default
				if (selected == (int)(*i)->GetId())
					*mpStream	<<	" SELECTED";

				*mpStream	<<	">"
							<<	(*i)->GetName()
							<<	"</OPTION>\n";	
			}
	
			// Clear vector for use again
			vMessages.erase(vMessages.begin(), vMessages.end());
		}

		// Shift messageType to the left by 1 to get the mext messageType
		nMask <<= 1;

		// Check to see if we checked all types asked for
		if (nMask > nMessageTypes)
			break;
	}

	// We're done adding close code					
	*mpStream	<<	"</SELECT>"
				<<	"<br>\n";
}


void clseBayApp::AdminAddScreeningCriteria(CEBayISAPIExtension *pThis,
											  CategoryId categoryid, 
											  eBayISAPIAuthEnum authLevel)
{
	CategoryVector		vCategories;
	
	// Setup
	SetUp();	
				
	// Title
	EmitHeader("Add Screening Criteria");

	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp();
		return;
	}

	// Header
	*mpStream	<<	"\n"
					"<h2>Add Screening Criteria</h2>\n";

	//Start form
	*mpStream	<<	"<form method=post action=\""
				<<	mpMarketPlace->GetCGIPath(PageAdminAddScreeningCriteriaShow)
				<<	"eBayISAPI.dll?AdminAddScreeningCriteriaShow\">\n";
					
	// Display some information text
	*mpStream	<<	"<p>You can add filters or messages to a category that you have selected" 
			  		" from the list.<br>\n";

	// Display some more help
	*mpStream	<<	"<p><b>Select a category:</b><br>\n";

	//Display all the categories and the leafs
	mpCategories->EmitHTMLLeafSelectionList(mpStream, "categoryid", categoryid,
												NULL,
												NULL,
												&vCategories, true, true);
				
	// Display notice.
	*mpStream	<<	"<p>Only 1 filter or message can be selected at a time.\n";

	// Display some more help
	*mpStream	<<	"<p><b>Select a filter:</b>.<br>\n";

	// Display Filter combo box
	BuildFilterComboBox("filterid");

	// Give them a link to add new filters
	*mpStream	<<	"To create a new filter, "
					"<a href=\""
				<<	mpMarketPlace->GetCGIPath(PageAdminAddFilter)
				<<	"eBayISAPI.dll?AdminAddFilter"
				<<	"\">"
					" click here.</a>\n"
					"<p>";

	// Display some more help
	*mpStream	<<	"<b>Select a message:</b><br>\n";

	// Display Message combo box
	BuildMessageComboBox("messageid", (MessageTypeCategorySellerWhenListing 
										+ MessageTypeCategoryBidderWhenBidding 
										+ MessageTypeItemBlockedWhenListing
										+ MessageTypeItemFlaggedWhenListing), 0);

	// Give them a link to add new messages
	*mpStream	<<	"To create a new message, "
					"<a href=\""
				<<	mpMarketPlace->GetCGIPath(PageAdminAddMessage)
				<<	"eBayISAPI.dll?AdminAddMessage"
				<<	"\">"
					" click here.</a>\n";

	// Add items we want to pass through
	*mpStream	<<	"<input type=hidden name=action value=\""
				<<	nAddAction
				<<	"\">\n";

	// Row for Review
	*mpStream	<<	"<p>Press "
				<<	"<input type=submit value=\"Submit\">"
					" to save the Screening Criteria.</p>";

	// Row for Clear
	*mpStream	<<	"<P>Press "
					"<input type=\"reset\" value=\"clear form\" name=\"reset\">"
				<<	" to clear the form and start over.</p>\n";

	// End form
	*mpStream	<<	"</form>\n";

	// Footer
	*mpStream	<<	"<p>"
				<<	mpMarketPlace->GetFooter()
				<<	flush;

	//Clean up memory
	vCategories.erase(vCategories.begin(), vCategories.end());

	CleanUp();

	return;
}

