/*	$Id: clseBayAppAdminAddMessage.cpp,v 1.2 1999/05/19 02:34:19 josh Exp $	*/
//
//	File:		clseBayAppAdminAddMessage.cpp
//
//	Class:		clseBayApp
//
//	Author:		Lou Leonardo (lou@ebay.com)
//
//	Function:	clseBayApp::clseBayAppAdminAddMessage
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
"Create Message",
"Modify a Message",
"Delete a Message"
};

static const char *QText[] =
{
"Are you sure you want to Create this Message.",
"Are you sure you want to Modify this Message.",
"Are you sure you want to Delete this Message."
};

static const char *ButtonText[] =
{
"Submit",
"Modify",
"Delete"
};

static const char *MessageTypeText[] =
{
"-",
"For Seller when listing",
"For Bidder when bidding",
"Blocked Item when listing",
"Flagged Item when listing",
"Filter Email text",
"Buddy Email text",
};

static const int nMessageTypeNumItems = 7;


void clseBayApp::AdminAddMessage(CEBayISAPIExtension *pThis,
								 int action, MessageId messageid,  
								 eBayISAPIAuthEnum authLevel)
{
	clsMessage		*pMessage = NULL;
	bool			error = false;

	unsigned int	nMessageType = 0;
	unsigned int	nMask = 1;
	int				i;
	
	
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

	// We need to have a message id if we are modifying or deleting
	if (action > 0)
	{
		if (messageid > 0 )
		{
			// Get the Message
			if (pMessage = mpMarketPlace->GetMessages()->GetMessage(messageid, true))
			{
				// Get the data from the selected message
				nMessageType = pMessage->GetMessageType();
			}
			else
			{
				// No message id
				*mpStream	<<	"<p><b>Error Getting Message:</b><br>"
							<<	"There was an error when trying to get the "
							<<	"selected message from the database.<br>"
							<<	"Please go back and select a message.\n";

				error = true;
			}
		}
		else
		{
			// No message id
			*mpStream	<<	"<p><b>No Message Selected:</b><br>"
						<<	"To "
						<<	TitleText[action]
						<<	" you must select a message first.<br>"
						<<	"Please go back and select a message.\n";

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
				<<	mpMarketPlace->GetCGIPath(PageAdminAddMessageShow)
				<<	"eBayISAPI.dll?AdminAddMessageShow\">\n";
				
	// Add items we want to pass through
	*mpStream	<<	"<input type=hidden name=action value=\""
				<<	action
				<<	"\"><input type=hidden name=messageid value=\""
				<<	messageid
				<<	"\">\n";

	// Get the message name
	*mpStream	<<	"<p><b>Message Name:</b><br>"
				<<	"<input type=text name=\"name\" size=\"63\" maxlength=\"63\" ";

	// Add the default 
	if (pMessage)
	{
		*mpStream	<<	"value=\""
					<<	pMessage->GetName()
					<<	"\"";
	}
	// Now finish off this line
	*mpStream	<<	"><br>\n";


	// Get the message text
	*mpStream	<<	"<p><b>Message Text:</b> (2048 characters Max)<br>\n"
				<<	"<textarea name=\"message\" cols=\"55\" rows=\"5\">";

	//Add the default
	if (pMessage)
	{
		*mpStream	<<	pMessage->GetText();
	}

	//Now finish the statement
	*mpStream	<<	"</textarea><br>\n";


	// Get the message type
	*mpStream	<<	"<p><b>Message Type:</b><br>\n";

	// Add items to combo box
	*mpStream	<<	"<SELECT NAME=\"messagetype\" SIZE=\"1\">\n"
				<<	"<OPTION VALUE=\""
				<<	MessageTypeUnknown
				<<	"\">"
				<<	MessageTypeText[0]
				<<	"</OPTION>\n";

	// Build List
	for (i = 1; i < nMessageTypeNumItems; i++)
	{
		*mpStream	<<	"<OPTION VALUE=\""
					<<	nMask
					<<	"\"";
		// See if this should be the default
		if (nMask == nMessageType)
		{
			*mpStream	<<	" SELECTED";
		}

		// Finish up
		*mpStream	<<	">"
					<<	MessageTypeText[i]
					<<	"</OPTION>\n";

		// Shift nMask to get correct value
		nMask <<= 1;
	}

	// Finish up the statement
	*mpStream	<<	"</SELECT><br>\n";



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
		*mpStream	<<	"<P>Press "
						"<input type=\"reset\" value=\"clear\" name=\"reset\">"
					<<	" to clear the form and start over.</p>\n";
	}

	//Close Form
	*mpStream	<<	"</form>\n";

	// Footer
	*mpStream	<<	"<p>"
				<<	mpMarketPlace->GetFooter()
				<<	flush;

	CleanUp();

	return;
}

