/*	$Id: clseBayAppVerifyAddtoitem.cpp,v 1.11.138.2 1999/08/05 18:59:05 nsacco Exp $	*/
//
//	File:		clseBayApp.cc
//
//	Class:		clseBayApp
//
//	Author:		Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
//	Modifications:
//				- 05/01/97 michael	- Created
//				- 07/13/97 tini		- changed Featured to add SuperFeatured; 
//					still need to add Category Featured html and 
//				- 03/16/99 kaz		- VerifyAddToItem() now checks timezone
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"
#include "clseBayTimeWidget.h"

void clseBayApp::VerifyAddToItem(CEBayISAPIExtension *pServer,
								 char *pUser,
								 char *pPass,
								 char *pItemNo,
								 char *pAddition)
{
// petra	time_t		nowTime;
// petra	struct tm	*pTheTime;
	char		cDate[16];
	char		cTime[16];
// petra	clseBayTimeWidget	nowTimeWidget;
// petra	TimeZoneEnum		timeZone;

	char		seperator[128];
	char		*pDescription;
	int			descLen;
	char		*pNewAddition;
	char		*pNewDesc;
	const char  *pSafeDescription = NULL;

	// Setup
	SetUp();

	// Let's try and get the item
	if (!GetAndCheckItem(pItemNo))
	{
		CleanUp();
		return;
	}

	// Usual Title and Header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Verify Adding to item "
			  <<	pItemNo
			  <<	" - "
			  <<	mpItem->GetTitle()
			  <<	"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader();



	// Now, let's see if the user's legitimate 
	mpUser	= mpUsers->GetAndCheckUserAndPassword(pUser, pPass, mpStream);
	if (!mpUser)
	{
		*mpStream <<	"<br>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// Let's see if this item belongs to this user before
	// going any furthur
	if (mpUser->GetId() != mpItem->GetSeller())
	{
		*mpStream <<	"<p>"
						"<B>"
				  <<	pUser
				  <<	" is not the seller for item "
				  <<	pItemNo
				  <<	"</B>"
						"<p>"
						"Only the seller is allowed to add to an item\'s "
						"description. If you are the seller, please go back, "
						"correct the "
				  <<	mpMarketPlace->GetLoginPrompt()
				  <<	", and try again. "
				  <<	mpMarketPlace->GetFooter()
				  <<	flush;
		
		CleanUp();
		return;

	}

	// Let's elide junk from the addition
	pNewAddition	= CleanUpDescription(pAddition);

	// Let's build the nice line which goes 
	// before their addition

	clseBayTimeWidget nowTimeWidget (mpMarketPlace,					// petra
									 EBAY_TIMEWIDGET_MEDIUM_DATE,	// petra
									 EBAY_TIMEWIDGET_NO_TIME);		// petra
	nowTimeWidget.EmitString (cDate);								// petra
	nowTimeWidget.SetDateTimeFormat(EBAY_TIMEWIDGET_NO_DATE,		// petra
									EBAY_TIMEWIDGET_LONG_TIME);		// petra
	nowTimeWidget.EmitString (cTime);								// petra

	sprintf(seperator,
			"\n<hr><samp>On %s at %s, seller added the following information:</samp><p>\n",
			cDate,
			cTime);

	// Let's make a buffer big enough for the old description
	// plus the new
	pDescription	= mpItem->GetDescription();
	if (pDescription)
		descLen	= strlen(pDescription);
	else
		descLen	= 0;

	pNewDesc	= new char[descLen + 
						   strlen(seperator) +
						   strlen(pAddition) +
						   1];

	if (pDescription)
		strcpy(pNewDesc, pDescription);
	else
		*pNewDesc = '\0';
	
	strcat(pNewDesc, seperator);
	strcat(pNewDesc, pAddition);


	// Now show the user how it looks
	*mpStream <<	"<h2>Adding to your item\'s Description</h2>"
					"Below, your item description is shown as it "
			  <<	"will appear after your addition. If it is satisfactory, "
					"hit the \"Add to Description\" button below. If you\'d "
					"like to make changes, go back and revise your addition."
			  <<	"<hr width=50%>"
					"<br>";

	*mpStream << "<blockquote>\n";

//	pSafeDescription = clsUtilities::DrawSafeHTML(pNewDesc);
//	if (pSafeDescription)
//		*mpStream << pSafeDescription;
//	else
		*mpStream << pNewDesc;
//	delete (char *) pSafeDescription;

	*mpStream << "\n</blockquote>\n";

	*mpStream <<	"<hr width=50%>"
					"<form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageAddToItem)
			  <<	"eBayISAPI.dll"
					"\""
					">"
					"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"AddToItem\">"
					"<INPUT TYPE=HIDDEN NAME=userid value=\""
			  <<	pUser
			  <<	"\">"
					"<INPUT TYPE=HIDDEN NAME=pass value=\""
			  <<	pPass
			  <<	"\">"
					"<INPUT TYPE=HIDDEN NAME=itemno value=\""
			  <<	pItemNo
			  <<	"\">"
					"<INPUT TYPE=HIDDEN NAME=desc value=\""
			  <<	pNewAddition
			  <<	"\">"
					"<strong>"
					"Press this button to confirm this addition to your listing:"
					"</strong>"
					"<blockquote>"
					"<input type=submit value=\"Add To Description\">"
					"</blockquote>"
					"</form>"
					"<p>"
			  <<	mpMarketPlace->GetFooter();


	// Bye Bye
	delete	pNewAddition;
	delete	pNewDesc;

	CleanUp();

	return;

}
