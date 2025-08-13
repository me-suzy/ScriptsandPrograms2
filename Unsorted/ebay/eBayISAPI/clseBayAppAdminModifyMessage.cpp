/*	$Id: clseBayAppAdminModifyMessage.cpp,v 1.2 1999/05/19 02:34:21 josh Exp $	*/
//
//	File:		clseBayAppAdminModifyMessage.cpp
//
//	Class:		clseBayApp
//
//	Author:		Lou Leonardo (lou@ebay.com)
//
//	Function:	clseBayApp::clseBayAppAdminModifyMessage
//
//
//	Modifications:
//				- 04/11/99 lou - Created

//	For use with Legal Buddies and Bottom Feeder.


#include "ebihdr.h"

static const int nModifyAction = 1;
static const int nDeleteAction = 2;

static const int nAllMessages = MessageTypeCategorySellerWhenListing	+
								MessageTypeCategoryBidderWhenBidding	+
								MessageTypeItemBlockedWhenListing		+
								MessageTypeItemFlaggedWhenListing		+
								MessageTypeFilteringEmailText			+
								MessageTypeBuddyEmailText;

// Define title string
static const char *TitleText[] =
{
"",
"Modify Messages",
"Delete Messages"
};

static const char *CaptionText[] =
{
"",
"Select a Message to Modify:",
"Select a Message to Delete:"
};

static const char *ButtonText[] =
{
"",
"Modify",
"Delete"
};

void clseBayApp::AdminModifyMessage(CEBayISAPIExtension *pThis,
										int action,	
										eBayISAPIAuthEnum authLevel)
{
	// Make sure action is in range, do not allow delete right now
	if (action != nModifyAction)
	{
		// Default action
		action = nModifyAction;
	}

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

	//Start form
	*mpStream	<<	"<form method=post action=\""
				<<	mpMarketPlace->GetCGIPath(PageAdminAddMessage)
				<<	"eBayISAPI.dll?AdminAddMessage\">\n";
					
	// Add action type variable
	*mpStream	<<	"<input type=hidden name=action value=\""
				<<	action
				<<	"\">\n";

	// Get the message name
	*mpStream	<<	"<p><b>"
				<<	CaptionText[action]
				<<	"</b><br>\n";

	// Display Message combo box
	BuildMessageComboBox("messageid", nAllMessages, 0);

	// Row for Review
	*mpStream	<<	"<p><input type=submit value=\""
				<<	ButtonText[action]
				<<	"\">\n";

	//Close Form
	*mpStream	<<	"</form>\n";

	// Footer
	*mpStream	<<	"<p>"
				<<	mpMarketPlace->GetFooter()
				<<	flush;

	CleanUp();

	return;
}

