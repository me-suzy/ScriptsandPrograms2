/*	$Id: clseBayAppViewEssay.cpp,v 1.4.230.1.108.1 1999/08/01 03:01:38 barry Exp $	*/
//
//	File:	clseBayAppViewEssay.cpp
//
//	Class:	clseBayApp
//
//	Author:	Wen Wen (wwen@ebay.com)
//
//	Function:
//
//		To display essay
//
// Modifications:
//				- 09/29/98 wen	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include "clsBulletinBoards.h"

#include "clsUserIdWidget.h"

	
//
// ViewEssay
//
void clseBayApp::ViewEssay(CEBayISAPIExtension *pThis,
						   char *pBoardName)
{
	clsBulletinBoard					*pBoard;
	BulletinBoardEntryList				EntryList;
	BulletinBoardEntryList::iterator	i;

	clsUserIdWidget						*pUserIdWidget;
	int									ColorSwitch	= 0;
	static char*						BGColor[] = {"#CCFFCC", "#CCFFFF"};


	SetUp();

	//
	// Get the bulletin board object. 
	//
	pBoard	= mpMarketPlace->GetBulletinBoards()->GetBulletinBoard(pBoardName);

	if (!pBoard || !pBoard->IsEssay())
	{
		*mpStream	<<	"<HTML>"
						"<HEAD>"
						"<TITLE>"
					<<	mpMarketPlace->GetCurrentPartnerName()
					<<	" View Essay "
					<<	"</TITLE>"
						"</HEAD>"			  
					<<	mpMarketPlace->GetHeader()
					<<	"\n"
					<<	"<h2>Invalid Board Name</h2>"
						"Sorry, the board name "
					<<	pBoardName
					<<	" is invalid. Please go back and try again."
					<<	"If you're using an old "
						"bookmark, you may need to rebookmark it due to "
						"recent changes to protect your privacy. "
					<<	"\n"
					<<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// Usual Title Stuff
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" "
			  <<	pBoard->GetName()
			  <<	"</TITLE>"
					"</HEAD>"			  
			  <<	mpMarketPlace->GetHeader()
			  <<	"\n";

	if (!pBoard->IsAvailable())
	{
		*mpStream <<	"<h2>Board Not Available</h2>"
						"Sorry, the board name "
				  <<	pBoardName
				  <<	" is not available. "
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// print the topic and the description
	*mpStream	<<	"<br><table width=\"540\" border=\"1\" cellspacing=\"0\" "
				<<	"cellpadding=\"3\">"
				<<	"<tr><td bgcolor=\"#FFFF99\"><font size=\"5\" face=\"arial,helvetica\">"
				<<	"<strong>Topic: <em>"
				<<	pBoard->GetName()
				<<	"</em></strong> </font>\n"
				<<	"<p><font face=\"arial,helvetica\">"
				<<	pBoard->GetDescription()
				<<	"</font></td></tr></table><br>\n";

	// let user to submit if the board is postable
	if (pBoard->IsPostable())
	{
		*mpStream	<<	"<p>&nbsp;</p>"
					<<	"<form method=\"post\" action=\""
					<<	mpMarketPlace->GetCGIPath(PageAddToBoard)
					<<	"eBayISAPI.dll\">"
					<<	"<input type=\"hidden\" NAME=\"MfcISAPICommand\" "
					<<	"VALUE=\"AddToBoard\">"
					<<	"<input type=\"hidden\" NAME=\"fromessay\" "
					<<	"VALUE=\"1\">"
					<<	"<input type=\"hidden\" NAME=\"name\" "
					<<	"VALUE=\""
					<<	pBoard->GetShortName()
					<<	"\">\n"
					<<	"<table border=\"1\" cellspacing=\"0\" width=\"540\" "
					<<	"cellpadding=\"4\" bgcolor=\"#FFFFCC\">\n"
					<<	"<tr><td width=\"500\"><font size=\"+2\">"
					<<	"<strong><i><a name=\"opinion\">\n"
					<<	"Please write in your thoughts"
					<<	"</a></i></strong></font>\n"
					<<	"</td></tr>\n"
					<<	"<tr><td width=\"500\">"
					<<	"<textarea cols=\"50\" name=\"info\" rows=\"12\"></textarea>"
					<<	"</td></tr>\n"
					<<	"<tr><td width=\"500\">\n"
					<<	"<table border=\"0\" width=\"100%\" cellspacing=\"0\">\n"
					<<	"<tr><td><strong>"
					<<	mpMarketPlace->GetLoginPrompt()
					<<	"</strong> or E-mail address</td>\n"
					<<	"<td><strong>Password</strong> (<a href=\""
					<<	mpMarketPlace->GetHTMLPath()
					<<	"services/buyandsell/reqpass.html\">forgotten</a> it?)"
					<<	"</td></tr>\n"
					<<	"<tr><td><input type=\"text\" name=\"userid\" size=\"22\" "
					<<	"maxlength=\"64\"></td>\n"
					<<	"<td><input type=\"password\" name=\"pass\" "
					<<	"size=\"22\" maxlength=\"64\"></td></tr></table>\n"
					<<	"<p align=\"center\">"
					<<	"<input type=\"submit\" value=\"Submit\">&nbsp; "
					<<	"<input type=\"reset\" value=\"Clear\">"
					<<	"</td></tr></table>\n"
					<<	"</form>\n";
	}

	// list the entries
	pUserIdWidget	= new clsUserIdWidget(mpMarketPlace, this);
	pBoard->GetAllEntries(&EntryList, 365 * 24 * 60); // set it to 365 days

	for (i = EntryList.begin();
		 i != EntryList.end();
		 i++)
	{
		*mpStream	<<	"<br><table border=\"1\" width=\"540\" bgcolor=\""
					<<	BGColor[ColorSwitch++]
					<<	"\" cellspacing=\"2\" cellpadding=\"6\">"
					<<	"<tr><td width=\"629\">\n"
					<<	(*i)->mpEntry
					<<	"\n<p>-- submitted by ";

		pUserIdWidget->SetUserInfo((*i)->mpUserId, 
								   (*i)->mpEmail,
								   UserUnknown,
								   mpMarketPlace->UserIdRecentlyChanged((*i)->mUserIdLastChangeTime),
								   (*i)->mFeedbackScore,
								   (*i)->mUserFlags);
		pUserIdWidget->SetShowUserStatus(false);
		pUserIdWidget->SetShowAboutMe();
		pUserIdWidget->EmitHTML(mpStream);

		*mpStream	<<	"\n</td></tr></table>\n";

		ColorSwitch %= 2;

	}

	// All done, lets' clean up
	delete pUserIdWidget;

	for (i = EntryList.begin();
		 i != EntryList.end();
		 i++)
	{
		delete	(*i);
	}

	EntryList.erase(EntryList.begin(), EntryList.end());

	*mpStream	<<	"<p>&nbsp;</p>"
				<<	"<font size=\"3\"><i>Click "
				<<	"<a href=\""
				<<	mpMarketPlace->GetCGIPath(PagePastEssay)
				<<	"eBayISAPI.dll?PastEssay\">here</a> for past essay topics.<br>"
				<<	"Do you have an idea for a express yourself topic? "
				<<	"Please <a href=\"mailto:essays@ebay.com\">e-mail it</a> to us."
				<<	"</i></font>";

	*mpStream	<<	"<br>"
				<<	mpMarketPlace->GetFooter();

	CleanUp();
	return;
}
