/*	$Id: clseBayAppAdminAddMessageShow.cpp,v 1.2 1999/05/19 02:34:19 josh Exp $	*/
//
//	File:		clseBayAppAdminAddMessageShow.cpp
//
//	Class:		clseBayApp
//
//	Author:		Lou Leonardo (lou@ebay.com)
//
//	Function:	clseBayApp::clseBayAppAdminAddMessageShow
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
"Save New Message",
"Update Message",
"Delete Message"
};

static const char *NotifyText[] =
{
"The Message was Successfully Saved.",
"The Message was Successfully Updated.",
"The Message was Successfully Deleted."
};


void clseBayApp::AdminAddMessageShow(CEBayISAPIExtension *pThis,
										int action, MessageId messageid,
										LPSTR pName, LPSTR pMessageText, 
										MessageType message_type,
										eBayISAPIAuthEnum authLevel)
{
	clsMessage	*pMessage = NULL;
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
	*mpStream	<<	"\n<h2> "
				<<	TitleText[action]
				<<	"</h2><br>\n";

	// Make sure there is a valid name
	if (FIELD_OMITTED(pName))
	{
		*mpStream <<	"<p><b>Empty Name:</b><br>"
						"You must enter a name for your message.\n";

		error	= true;
	}

	// Is the name too long?
	if (strlen(pName) > 63)
	{
		*mpStream <<	"<p><b>Message name too long</b><br>"
						"The message name can not be more than 63 characters.\n";

		error	= true;
	}

	// Make sure there is an message entered
	if (FIELD_OMITTED(pMessageText))
	{
		*mpStream <<	"<p><b>Message missing:</b><br>"
						"You must enter a message.\n";

		error	= true;
	}

    //expression too long ?
	if (strlen(pMessageText) > EBAY_MAX_MESSAGETEXT_SIZE)
	{
		*mpStream <<	"<p><b>Message too long:</b><br>"
						"Your message can not be more than 2048 characters.\n";

		error	= true;
	}

    // Check the Message Type ?
	if (message_type == 0)
	{
		*mpStream <<	"<p><b>Incorrect Message Type</b><br>"
						"A message type must be selected.\n";

		error	= true;
	}

	// See if there have been any errors yet
	if (!error && action == nAddAction)
	{
		// Check for duplicate filter name only if we are adding a new item
		pMessage = mpMarketPlace->GetMessages()->GetMessage(pName, true);
	
		if (pMessage)
		{
			*mpStream	<<	"<p><b>Duplicate message name</b><br>"
						<<	"The message name has already been used.\n";

			// Clear pointer, don't delete it
			pMessage = NULL;

			error = true;
		}
	}

	// If there were no errors, lets make the changes
	if (!error)
	{
		switch (action)
		{
			// Create a new message and add it to the database
			case nAddAction:
				pMessage = new clsMessage(0, pName, message_type, pMessageText, strlen(pMessageText));

				if (!mpMarketPlace->GetMessages()->AddMessage(pMessage))
				{
					*mpStream	<<	"<p><b>The message was not able to be stored.</b>\n";
					error = true;
				}
				break;
			
			// Create a message and use the id to update the info in the db
			case nModifyAction:
				pMessage = new clsMessage(messageid, pName, message_type, pMessageText, strlen(pMessageText));

				if (!mpMarketPlace->GetMessages()->UpdateMessage(messageid, pMessage))
				{
					*mpStream	<<	"<p><b>The message was not able to be stored.</b>\n";
					error = true;
				}
				break;
			
			// Delete a message from the db by id - delete does not return a bool
			case nDeleteAction:
				mpMarketPlace->GetMessages()->DeleteMessage(messageid);
				break;
		}
	}

	// Let user know if the filter was saved
	if (!error)
	{
		*mpStream	<<	"<p><b>"
					<<	NotifyText[action]
					<<	"</b><br>\n";
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

	// Memory cleanup
	if (pMessage)
	{
		delete pMessage;
	}

	return;
}

