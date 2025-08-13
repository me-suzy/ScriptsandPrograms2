/*	$Id: clseBayAppViewBoard.cpp,v 1.9.198.1.108.2 1999/08/06 20:31:54 nsacco Exp $	*/
//
//	File:	clseBayAppViewBoard.cpp
//
//	Class:	clseBayApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//	A TEMPORARY method to handle the bulletin
//	boards. It's temporary because:
//
//	- The current boards are rather primitive
//	- We're putting it in thie DLL because we 
//	  haven't solved the "multiple DLLs sharing
//	  one set to thread local storage" problem.
//
//
// Modifications:
//				- 02/06/97 michael	- Created
//				- 01/05/98 charles  - modified for privacy User ID
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include "clsBulletinBoards.h"

// Added by Charles
#include "clsUserIdWidget.h"
	
//
// ViewBoard
//
void clseBayApp::ViewBoard(CEBayISAPIExtension *pThis,
						   char *pBoardName,
						   char	*pTimeLimit)
{
	int									timeLimit;

	BulletinBoardVector					*pvBoards;
	BulletinBoardVector::iterator		iBoards;

	clsBulletinBoard					*pBoard;
	BulletinBoardEntryList				*pEntryList;
	BulletinBoardEntryList::iterator	i;

	char								cTheDate[32];
	char								cTheTime[32];

	// Added by Charles
	clsUserIdWidget						*pUserIdWidget;
	clsUser								*pUser	= NULL;

	bool								colorSwitch	= true;

	char								*pHeadColor = "#99CCCC";

	bool								isEbayUser;

	SetUp();

	timeLimit	= atoi(pTimeLimit);

	pUserIdWidget	= new clsUserIdWidget(mpMarketPlace, this);
	//
	// Get the bulletin board object. 
	//
	pBoard	= mpMarketPlace->GetBulletinBoards()->GetBulletinBoard(pBoardName);

	if (!pBoard || pBoard->IsEssay())
	{
		*mpStream <<	"<HTML>"
						"<HEAD>"
						"<TITLE>"
						"Viewing "
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" Bulletin Board"
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


	// Get all the entries
	pEntryList	= new BulletinBoardEntryList;

	pBoard->GetAllEntries(pEntryList, timeLimit);

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


	// The board's name and description
	*mpStream <<	"<TABLE WIDTH=100%>"
					"<TR>"
					"<TD BGCOLOR=\""
			  <<	pHeadColor
			  <<	"\" WIDTH=100%>"
					"<CENTER>"
					"<FONT SIZE=+2>"
			  <<	pBoard->GetName()
			  <<	"</FONT>"
			  <<	"</CENTER>"
					"</TD>"
					"</TR>"
					"<TR><TD WIDTH=100%>"
			  <<	pBoard->GetDescription()
			  <<	"</TD>"
					"</TR>"
					"</TABLE>"
			  <<	"\n";

	// A way to go somewhere else ;-)
	pvBoards	=	mpMarketPlace->GetBulletinBoards()->GetBoardVector();

	// Form #1 (board hopping)
	*mpStream <<	"<form method=get action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageViewBoard)
			  <<	"eBayISAPI.dll"
					"\""
					">"
					"<INPUT TYPE=HIDDEN "
					"NAME=\"MfcISAPICommand\" "
					"VALUE=\"ViewBoard\">";

	*mpStream <<	"<b>"
					"Want to visit <i>another</i> board?"
					" You can view our "
					// TODO - change calculus
					"<A HREF=\"http://calculus.ebay.com/aw/announce.shtml\">"
					"eBay Announcements Board"
					"</A>"
					" or..."
					"<br>"
					"select from this list: "
					"<SELECT "
					"NAME=\"name\""
					">";

	for (iBoards = pvBoards->begin();
		 iBoards != pvBoards->end();
		 iBoards++)
	{
		// don't include the board if it's invisible and it's not the current board.
		// don't include the board if it is essay
		if ((((*iBoards)->IsInvisible()) &&
			(strcmp(pBoard->GetShortName(),(*iBoards)->GetShortName()) != 0)) ||
			(*iBoards)->IsEssay())
			continue;

		*mpStream <<	"<OPTION VALUE=\""
				  <<	(*iBoards)->GetShortName()
				  <<	"\" ";

		if (strcmp(pBoard->GetShortName(), 
				   (*iBoards)->GetShortName()) == 0)
		{
			*mpStream <<	"SELECTED";
		}

		*mpStream <<	">"
				  <<	(*iBoards)->GetName();
	}

	*mpStream <<	"</select>"
					" and "
					"<input type=submit value=\"Click here!\">"
					"</b>"
					"</form>";

	// The standard disclaimer stuff
	*mpStream <<	"Please scroll down to view the "
					"<A HREF=#messages>"
					"messages"
					"</A>. <p>";


	if (!pBoard->IsRestricted())
		*mpStream <<	"<strong>You are responsible for your own words.</strong>"
						" Please consider your message carefully before submitting it."
						"<p>";
		
	// Now, the table
	*mpStream <<	"<TABLE WIDTH=400 BORDER=1 CELLPADDING=0 "
					"BGCOLOR=\""
			  <<	pHeadColor
			  <<	"\">\n"
					"<TR>"
					"<TD>";

	// 
	// If the board's NOT restricted, then put up a post
	// box
	//
	if (!pBoard->IsRestricted())
	{

		// Form #2 (posting)
		*mpStream <<	"<form method=post action="
						"\""
				  <<	mpMarketPlace->GetCGIPath(PageAddToBoard)
				  <<	"eBayISAPI.dll"
						"\""
						">"
						"<INPUT TYPE=HIDDEN "
						"NAME=\"MfcISAPICommand\" "
						"VALUE=\"AddToBoard\">"
						"<INPUT TYPE=HIDDEN "
						"NAME=\"name\" "
						"VALUE="
						"\""
				  <<	pBoardName
				  <<	"\""
						">"
						"<TABLE WIDTH=100% CELLPADDING=0>\n"
						"<TR>"
						"<TD WIDTH=50%>"
						"<b>"
						"Your "
						"<a href="
						"\""
				  <<	mpMarketPlace->GetHTMLPath()
				  <<	"services/registration/register-by-country.html"
						"\""
						">"
						"registered"
						"</a>"
						" "
				  <<	mpMarketPlace->GetLoginPrompt()
				  <<	"</b>"
				  <<	"</TD>"
						"<TD WIDTH=50%>"
						"<b>"
						"Your "
					<<	mpMarketPlace->GetPasswordPrompt()
					<<	"</b>"
						"</TD>"
						"</TR>\n"
						"<TR>"
						"<TD WIDTH=20%>"
						"<input type=text name=userid size=30>"
						"</TD>"
						"<TD WIDTH=20%>"
						"<input type=password name=pass size=30>"
						"<input type=hidden name=\"limit\" "
						"value=\""
				  <<	pTimeLimit
				  <<	"\">"
						"</TD>"
						"</TR>\n"
						"</TABLE>\n"
						"<TABLE WIDTH=100% CELLPADDING=0>\n"
						"<TR>"
						"<TD WIDTH=100%>"
						"<b>"
						"Your message"
						"</b>"
						"</TD>"
						"</TR>\n"
						"<TR>"
						"<TD WIDTH=100%>"
						"<textarea name=info cols=60 rows=8>"
						"</textarea>"
						"</TD>"
						"</TR>\n"
						"</TABLE>"
						"<TABLE WIDTH=100%  CELLPADDING=0>"
						"<TR>"
						"<TD WIDTH=50%>"
						"<input type=submit value=\"Save my Message!\">"
						"</TD>"
						"<TD WIDTH=50%>"
						"<input type=reset value=\"Clear form\">"
						"</TD>"
						"</TR>"
						"</TABLE>"
						"</FORM>";
	}

	// Form #3 (reload with time limit)
	*mpStream <<	"<form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageViewBoard)
			  <<	"eBayISAPI.dll"
					"\""
					">"
					"<INPUT TYPE=HIDDEN "
					"NAME=\"MfcISAPICommand\" "
					"VALUE=\"ViewBoard\">"
					"<INPUT TYPE=HIDDEN "
					"NAME=\"name\" "
					"VALUE="
					"\""
			  <<	pBoardName
			  <<	"\""
					">"					
					"<input type=submit value=\"Reload\">"
					" and show me "
					"<SELECT NAME=\"limit\">";

		// 
		// A gnarly piece of work
		//
		if (timeLimit == 0)
			*mpStream <<	"<OPTION SELECTED VALUE=\"default\">All messages";
		else
			*mpStream <<	"<OPTION SELECTED VALUE=\"default\">All messages";

		if (timeLimit == 1440)
			*mpStream <<	"<OPTION SELECTED VALUE=\"1440\">Messages from the last 24 hours";
		else
			*mpStream <<	"<OPTION VALUE=\"1440\">Messages from the last 24 hours";

		if (timeLimit == 720)
			*mpStream <<	"<OPTION SELECTED VALUE=\"720\">Messages from the last 6 hours";
		else
			*mpStream <<	"<OPTION VALUE=\"720\">Messages from the last 6 hours";

		if (timeLimit == 360)
			*mpStream <<	"<OPTION SELECTED VALUE=\"360\">Messages from the last 3 hours";
		else
			*mpStream <<	"<OPTION VALUE=\"360\">Messages from the last 3 hours";

		if (timeLimit == 60)
			*mpStream <<	"<OPTION SELECTED VALUE=\"60\">Messages from the last hour";
		else
			*mpStream <<	"<OPTION VALUE=\"60\">Messages from the last hour";

		if (timeLimit == 30)
			*mpStream <<	"<OPTION SELECTED VALUE=\"30\">Messages from the last 30 minutes";
		else
			*mpStream <<	"<OPTION VALUE=\"30\">Messages from the last 30 minutes";

		if (timeLimit == 15)
			*mpStream <<	"<OPTION SELECTED VALUE=\"15\">Messages from the last 15 minutes";
		else
			*mpStream <<	"<OPTION VALUE=\"15\">Messages from the last 15 minutes";

		if (timeLimit == 5)
			*mpStream <<	"<OPTION SELECTED VALUE=\"5\">Messages from the last 5 minutes";
		else
			*mpStream <<	"<OPTION VALUE=\"5\">Messages from the last 5 minutes";

		*mpStream <<	"</SELECT>"
						"</FORM>"
						"</TD>"
						"</TR>"
						"</TABLE>";
	
	// All of the board entries are in a nice vector for us. Let's
	// emit them
	*mpStream <<	"<A NAME=messages></A>";

	for (i = pEntryList->begin();
		 i != pEntryList->end();
		 i++)
	{
		if (strstr((*i)->mpEmail, "@ebay.com") == 0)
			isEbayUser	= false;
		else
			isEbayUser	= true;

		*mpStream <<	"<TABLE WIDTH=100% BORDER=1>"
						"<TR>"
						"<TD>";
		// Added by Charles
		// The actual post now and guard tags to prevent
		// badness
		*mpStream <<	"<TABLE WIDTH=100% BORDER=0 ";
		
		if (1 || colorSwitch)
		{
			*mpStream <<	"bgcolor=#FFFFFF";
		}
		else
		{
			*mpStream <<	"bgcolor=#CCCCCC";
		}

		colorSwitch	= !colorSwitch;

		if (!isEbayUser)
			*mpStream	<<	">"
						"<TR>"
//						"<TD BGCOLOR=\"#FFFFCC\" ALIGN=left>";	// yellow
						"<TD BGCOLOR=\"#EFEFEF\" ALIGN=left>";	// lt gray
		else
			*mpStream	<<	">"
						"<TR>"
						"<TD BGCOLOR=\"#FFCCCC\" ALIGN=left>";


		*mpStream	<<	"<B>"
					<< "Posted by "
					<< "</B>";
		pUserIdWidget->SetUserInfo((*i)->mpUserId, 
								   (*i)->mpEmail,
								   UserUnknown,
								   mpMarketPlace->UserIdRecentlyChanged((*i)->mUserIdLastChangeTime),
								   (*i)->mFeedbackScore,
								   (*i)->mUserFlags);

		pUserIdWidget->SetShowUserStatus(false);
		pUserIdWidget->SetShowAboutMe();
		pUserIdWidget->EmitHTML(mpStream);

		// Date and such (modified 10/26/98 by ADP to respect dst)
		clsUtilities::GetDateAndTime((*i)->mTime, cTheDate, cTheTime);


		*mpStream <<	" "
						"<B>"
						" on "
						"</B>"
				  <<	cTheDate
				  <<	" "
						"<B>"
						" at "
						"</B>"
				  <<	cTheTime
				  <<	"</TD>";

		if (!isEbayUser)
		{
			*mpStream <<	"<TD "
							"BGCOLOR=\"#EFEFEF\" "
							"ALIGN=right>"
							"<A HREF=\""
					  <<	mpMarketPlace->GetCGIPath(PageViewListedItems)
					  <<	"eBayISAPI.dll"
							"?ViewListedItems"
							"&userid="
					  <<	(*i)->mpUserId
					  <<	"&completed=0&sort=0&since=-1"
							"\">Auctions</A>"
							"</TD>";
		}
		else
		{
			*mpStream <<	"<TD BGCOLOR=\"#FFCCCC\" ALIGN=right>"
							"&nbsp;"
					  <<	"</TD>";
		}
		
		*mpStream <<	"</TR>"
						"</TABLE>";

		// The actual post now and guard tags to prevent
		// badness

		*mpStream <<	"<TABLE WIDTH=100%>"
						"<TR>"
						"<TD>"
				  <<	(*i)->mpEntry
				  <<	"</TD>"
						"</TR>"
						"</TABLE>\n";

		*mpStream <<	"</TD></TR></TABLE>";

	}

	// Now the adornments at the end
	*mpStream <<	"<br>"
					"\n"
					"<samp>"
					"<a href=#top>"
					"[Top of page]"
					"</a>"
					"</samp>"
					"\n"
					"<hr>"
					"\n\n"
			 <<		mpMarketPlace->GetFooter();



	delete pUserIdWidget;
	// Alll done, lets' clean up
	for (i = pEntryList->begin();
		 i != pEntryList->end();
		 i++)
	{
		delete	(*i);
	}

	pEntryList->erase(pEntryList->begin(),
						pEntryList->end());

	delete	pEntryList;

	CleanUp();
	return;
}
