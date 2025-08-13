/*	$Id: clseBayAppAdminAddFilter.cpp,v 1.2 1999/05/19 02:34:17 josh Exp $	*/
//
//	File:		clseBayAppAdminAddFilter.cpp
//
//	Class:		clseBayApp
//
//	Author:		Lou Leonardo (lou@ebay.com)
//
//	Function:	clseBayApp::clseBayAppAdminAddFilter
//
//
//	Modifications:
//				- 04/11/99 lou - Created

//	For use with Legal Buddies and Bottom Feeder.



#include "ebihdr.h"

static const int nAddAction = 0;
static const int nModifyAction = 1;
static const int nDeleteAction = 2;

// Define title string
static const char *TitleText[] =
{
"Create Filter",
"Modify a Filter",
"Delete a Filter"
};

static const char *QText[] =
{
"Are you sure you want to Create this Filter.",
"Are you sure you want to Modify this Filter.",
"Are you sure you want to Delete this Filter."
};

static const char *ButtonText[] =
{
"Submit",
"Modify",
"Delete"
};


void clseBayApp::AdminAddFilter(CEBayISAPIExtension *pThis,
								int action, FilterId filterid, 
								eBayISAPIAuthEnum authLevel)
{
	clsFilter		*pFilter = NULL;
	bool			error = false;

	unsigned int	nActionType = 0;
	unsigned int	nNotifyType = 0;
	unsigned int	BlockingMessageId = 0;
	unsigned int	FlaggingMessageId = 0;
	unsigned int	FilteringMessageId = 0;
	unsigned int	BuddyMessageId =0;


	// Setup
	SetUp();	

	// Title
	EmitHeader(TitleText[action]);

	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp();
		return;
	}

	// Header
	*mpStream	<<	"\n<h2>"
				<<	TitleText[action]
				<<	"</h2>\n";

	// We need to have a filter id if we are modifying or deleting
	if (action > 0)
	{
		if (filterid > 0 )
		{
			// Get the Filter, don't delete pFilter it comes from the cache
			if (pFilter = mpMarketPlace->GetFilters()->GetFilter(filterid, true))
			{
				// Get the data from the selected message
				nActionType = pFilter->GetActionType();
				nNotifyType = pFilter->GetNotifyType();
				BlockingMessageId = pFilter->GetBlockingMessageId();
				FlaggingMessageId = pFilter->GetFlaggingMessageId();
				FilteringMessageId = pFilter->GetFilteringMessageId();
				BuddyMessageId =pFilter->GetBuddyMessageId();
			}
			else
			{
				// No filter id
				*mpStream	<<	"<p><b>Error Getting Filter:</b><br>"
							<<	"There was an error when trying to get the "
							<<	"selected filter from the database.<br>"
							<<	"Please go back and select a filter.\n";

				error = true;
			}
		}
		else
		{
			// No filter id
			*mpStream	<<	"<p><b>No Filter Selected:</b><br>"
						<<	"To "
						<<	TitleText[action]
						<<	" you must select a filter first.<br>"
						<<	"Please go back and select a filter.\n";

			error = true;
		}

		// Check to see if there was a problem
		if (error)
		{
			// Need to exit there was a problem
			*mpStream	<<	"<p>"
						<<	mpMarketPlace->GetFooter()
						<<	flush;

			CleanUp();
			return;
		}
	}

	//Start form
	*mpStream	<<	"<form method=post action=\""
				<<	mpMarketPlace->GetCGIPath(PageAdminAddFilterShow)
				<<	"eBayISAPI.dll?AdminAddFilterShow\">\n";

	// Add items we want to pass through
	*mpStream	<<	"<input type=hidden name=action value=\""
				<<	action
				<<	"\"><input type=hidden name=filterid value=\""
				<<	filterid
				<<	"\">\n";

					
	// Get the filter title
	*mpStream	<<	"<p><b>Filter Name:</b><br>"
					"<input type=text name=\"name\" size=\"63\" maxlength=\"63\" ";

	// Add the default 
	if (pFilter)
	{
		*mpStream	<<	"value=\""
					<<	pFilter->GetName()
					<<	"\"";
	}
	
	// Now finish off this line
	*mpStream	<<	"><br>\n";


	// Get the filter expression
	*mpStream	<<	"<p><b>Filter Expression:</b> (253 characters Max)<br>"
					"<textarea name=\"expression\" cols=\"55\" rows=\"5\">";

	//Add the default
	if (pFilter)
	{
		*mpStream	<<	pFilter->GetExpression();
	}

	//Now finish the statement
	*mpStream	<<	"</textarea><br>\n";

	// ***** Build the Action Type combo box
	*mpStream	<<	"<p><b>Action Type:</b><br>\n";
	*mpStream	<<	"<SELECT NAME=\"actiontype\" SIZE=\"1\">\n"
				<<	"<OPTION VALUE=\""
				<<	ActionTypeDoNothing
				<<	"\"";

	if (nActionType == ActionTypeDoNothing)
			*mpStream	<<	" SELECTED";

	*mpStream	<<	">Do Nothing</OPTION>\n";
	*mpStream	<<	"<OPTION VALUE=\""
				<<	ActionTypeFlagListing
				<<	"\"";
	if (nActionType == ActionTypeFlagListing)
			*mpStream	<<	" SELECTED";

	*mpStream	<<	">Flag Listing</OPTION>\n";

	
//LL: Remove for now
//	*mpStream	<<	"<OPTION VALUE=\""
//				<<	(ActionTypeFlagListing + ActionTypeWarnUser)
//				<<	"\"";

//	if (nActionType == (ActionTypeFlagListing + ActionTypeWarnUser))
//			*mpStream	<<	" SELECTED";

//	*mpStream	<<	">Flag Listing & Warn User</OPTION>\n";
//	*mpStream	<<	"<OPTION VALUE=\""
//				<<	ActionTypeBlockListing
//				<<	"\"";

//	if (nActionType == ActionTypeBlockListing)
//			*mpStream	<<	" SELECTED";

//	*mpStream	<<	">Block Listing</OPTION>\n";

	
	*mpStream	<<	"<OPTION VALUE=\""
				<<	(ActionTypeBlockListing + ActionTypeWarnUser)
				<<	"\"";

	if (nActionType == (ActionTypeBlockListing + ActionTypeWarnUser))
			*mpStream	<<	" SELECTED";

	*mpStream	<<	">Block Listing & Warn User</OPTION></SELECT><br>\n";

//LL: Remove for now
/*

  // ***** Build the Notify Type combo box
	*mpStream	<<	"<p><b>Notification Type:</b><br>\n"
				<<	"<SELECT NAME=\"notifytype\" SIZE=\"1\">\n"
				<<	"<OPTION VALUE=\""
				<<	NotifyTypeNone
				<<	"\"";

	if (nNotifyType == NotifyTypeNone)
			*mpStream	<<	" SELECTED";

	*mpStream	<<	">Do Not Notify</OPTION>\n"
				<<	"<OPTION VALUE=\""
				<<	NotifyTypeFilteringEmailAddresses
				<<	"\"";

	if (nNotifyType == NotifyTypeFilteringEmailAddresses)
			*mpStream	<<	" SELECTED";

	*mpStream	<<	">Notify via Filter Email</OPTION>\n"
				<<	"<OPTION VALUE=\""
				<<	NotifyTypeBuddyEmailAddresses
				<<	"\"";

	if (nNotifyType == NotifyTypeBuddyEmailAddresses)
			*mpStream	<<	" SELECTED";

	*mpStream	<<	">Notify via Buddy Email</OPTION>\n"
				<<	"<OPTION VALUE=\""
				<<	(NotifyTypeFilteringEmailAddresses + NotifyTypeBuddyEmailAddresses)
				<<	"\"";

	if (nNotifyType == (NotifyTypeFilteringEmailAddresses + NotifyTypeBuddyEmailAddresses))
			*mpStream	<<	" SELECTED";

	*mpStream	<<	">Notify All</OPTION></SELECT><br>\n";

				
	// Select the Blocked message
	*mpStream	<<	"<p><b>Blocked Message:</b><br>\n";

	// Display Message combo box
	BuildMessageComboBox("blockedmessage", MessageTypeItemBlockedWhenListing, BlockingMessageId);

	// Select the Flagged message
	*mpStream	<<	"\n<p><b>Flagged Message:</b><br>\n";

	// Display Message combo box
	BuildMessageComboBox("flaggedmessage", MessageTypeItemFlaggedWhenListing, FlaggingMessageId);

	// Select the Filter message
	*mpStream	<<	"\n<p><b>Filter Email Message:</b><br>\n";

	// Display Message combo box
	BuildMessageComboBox("filteremailtext", MessageTypeFilteringEmailText, FilteringMessageId);

	// Select the Flagged message
	*mpStream	<<	"\n<p><b>Buddy Email Message:</b><br>\n";

	// Display Message combo box
	BuildMessageComboBox("buddyemailtext", MessageTypeBuddyEmailText, BuddyMessageId);

	// Get the filter's email address
	*mpStream	<<	"\n<p><b>Filter's Email Address:</b><br>"
					"<input type=text name=\"filteremailaddress\" size=\"64\" maxlength=\"253\" ";

	// Add the default 
	if (pFilter)
	{
		*mpStream	<<	"value=\""
					<<	pFilter->GetFilteringEmailAddresses()
					<<	"\"";
	}
	
	// Now finish off this line
	*mpStream	<<	"><br>\n";


	// Get the Buddies email address
	*mpStream	<<	"<p><b>Buddies Email Address:</b><br>"
					"<input type=text name=\"buddyemailaddress\" size=\"64\" maxlength=\"253\" ";

	// Add the default 
	if (pFilter)
	{
		*mpStream	<<	"value=\""
					<<	pFilter->GetBuddyEmailAddresses()
					<<	"\"";
	}

	
	// Now finish off this line
	*mpStream	<<	"><br>\n";
*/


	// Row for Review
	*mpStream	<<	"<p>"
				<<	QText[action]
				<<	"<br>\n"
				<<	"Press "
				<<	"<input type=submit value=\""
				<<	ButtonText[action]
				<<	"\">"
				<<	" to continue.\n";

	// Add clear only if we are adding new items
	if (action == nAddAction)
	{
		// Clear page
		*mpStream	<<	"<P>Press "
						"<input type=\"reset\" value=\"clear\">"
					<<	" to clear the form and start over.</p>\n";
	}

	// End form
	*mpStream	<<	"</form>";

	// Footer
	*mpStream	<<	"<p>"
				<<	mpMarketPlace->GetFooter()
				<<	flush;

	CleanUp();

	return;
}

