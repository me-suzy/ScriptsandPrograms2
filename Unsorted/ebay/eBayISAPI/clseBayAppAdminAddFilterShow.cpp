/*	$Id: clseBayAppAdminAddFilterShow.cpp,v 1.2 1999/05/19 02:34:19 josh Exp $	*/
//
//	File:		clseBayAppAdminAddFilterShow.cpp
//
//	Class:		clseBayApp
//
//	Author:		Lou Leonardo (lou@ebay.com)
//
//	Function:	clseBayApp::clseBayAppAdminAddFilterShow
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
"Save New Filter",
"Update Filter",
"Delete Filter"
};

static const char *NotifyText[] =
{
"The Filter was Successfully Saved.",
"The Filter was Successfully Updated.",
"The Filter was Successfully Deleted."
};


void clseBayApp::AdminAddFilterShow(CEBayISAPIExtension *pThis,
										int action,
										FilterId filterid,
										LPSTR pName,
										LPSTR pExpression,
										ActionType action_type,
										NotifyType notify_type,
										MessageId blocked_message,
										MessageId flagged_message,
										MessageId filter_email_text,
										MessageId buddy_email_text,
										LPSTR pFilterEmailAddress,
										LPSTR pBuddyEmailAddress,
										eBayISAPIAuthEnum authLevel)
{
	clsFilter	*pFilter = NULL;
	bool		error = false;

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
				<<	"</h2><br>\n";

	// Make sure there is a valid name
	if (FIELD_OMITTED(pName))
	{
		*mpStream	<<	"<p><b>Empty Name:</b><br>"
						"You must enter a name for your filter.\n";

		error	= true;
	}

	// Is the name too long?
	if (strlen(pName) > 63)
	{
		*mpStream	<<	"<p><b>Filter name too long:</b><br>"
						"The filter name can not be more than 63 characters.\n";

		error	= true;
	}

	// Make sure there is an expression entered
	if (FIELD_OMITTED(pExpression))
	{
		*mpStream	<<	"<p><b>Expression missing:</b><br>"
						"You must enter a filter expression.\n";

		error	= true;
	}

    //expression too long ?
	if (strlen(pExpression) > 253)
	{
		*mpStream	<<	"<p><b>Expression too long</b><br>"
						"Your expression can not be more than 253 characters.\n";

		error	= true;
	}


	// See if there have been any errors yet
	if (!error && action == nAddAction)
	{
		// Check for duplicate filter name
		pFilter = mpMarketPlace->GetFilters()->GetFilter(pName, true);
	
		if (pFilter)
		{
			*mpStream	<<	"<p><b>Duplicate filter name:</b><br>"
						<<	"The filter name has already been used.\n";

			// Clear pointer
			pFilter = NULL;

			error = true;
		}
	}


//LL: Remove for now
/*
	// Make sure the correct message is selected for the action type.
	if (action_type & ActionTypeWarnUser)
	{
		// Need to make sure that the correct message is selected for the action_type
		if (action_type & ActionTypeFlagListing)
		{
			// Make sure that a flag listing message is selected
			if (!flagged_message)
			{
				// Display the error
				*mpStream	<<	"<p><b>Flagged Message Missing:</b><br>"
								"You need to select a Flagged message when you select "
								"to Flag the item and Warn the User.\n";
				error	= true;
			}
		}
		// Need to make sure that the correct message is selected for the action_type
		if (action_type & ActionTypeBlockListing)
		{
			// Make sure that a flag listing message is selected
			if (!blocked_message)
			{
				// Display the error
				*mpStream	<<	"<p><b>Blocked Message Missing:</b><br>"
								"You need to select a Blocked message when you select "
								"to Block the item and Warn the User.\n";
				error	= true;
			}
		}
	}

	// Make sure the correct message is selected for the notify type.
	if (notify_type & NotifyTypeFilteringEmailAddresses)
	{
		// Make sure there is a message there
		if (!filter_email_text)
		{
			// Display Error
			*mpStream	<<	"<p><b>Filter Email Message Missing:</b><br>"
							"You need to select a Filter Email message when you select "
							"to Notify the Filter.\n";
			error	= true;
		}
	}

	// Make sure the correct message is selected for the notify type.
	if (notify_type & NotifyTypeBuddyEmailAddresses)
	{
		// Make sure there is a message there
		if (!buddy_email_text)
		{
			// Display Error
			*mpStream	<<	"<p><b>Buddy Email Message Missing:</b><br>"
							"You need to select a Buddy Email message when you select "
							"to Notify the Buddy.\n";
			error	= true;
		}
	}

*/

	// If there were no errors, lets make the changes
	if (!error)
	{
		switch (action)
		{
			// Create a new message and add it to the database
			case nAddAction:
				pFilter = new clsFilter(0, pName, pExpression, 0, action_type,
										notify_type, blocked_message, flagged_message,
										filter_email_text, buddy_email_text,
										pFilterEmailAddress, pBuddyEmailAddress);

				// Add filter to filter list
				if (!mpMarketPlace->GetFilters()->AddFilter(pFilter))
				{
					*mpStream	<<	"<p><b>The filter was not able to be stored.</b>\n";
					error = true;
				}
				break;
	
			// Create a message and use the id to update the info in the db
			case nModifyAction:
				pFilter = new clsFilter(filterid, pName, pExpression, 0, action_type,
										notify_type, blocked_message, flagged_message,
										filter_email_text, buddy_email_text,
										pFilterEmailAddress, pBuddyEmailAddress);


				if (!mpMarketPlace->GetFilters()->UpdateFilter(filterid, pFilter))
				{
					*mpStream	<<	"<p><b>The message was not able to be stored.</b>\n";
					error = true;
				}
				break;
			
			// Delete a message from the db by id - delete does not return a bool
			case nDeleteAction:
				mpMarketPlace->GetFilters()->DeleteFilter(filterid);
				break;
		}
	}

	// Let user know if the filter was saved
	if (!error)
	{
		*mpStream	<<	"<p><b>"
					<<	NotifyText[action]
					<<	"</b>\n";
	}
	else
	{
		*mpStream	<<	"<p><b>Please go back and try again.</b>\n";
	}

	// Footer
	*mpStream	<<	"<p>"
				<<	mpMarketPlace->GetFooter()
				<<	flush;

	CleanUp();

	// Do memory cleanup
	if (pFilter)
	{
		delete pFilter;
	}

	return;
}

