/*	$Id: clseBayAppPastEssay.cpp,v 1.2.540.1 1999/08/01 03:01:21 barry Exp $	*/
//
//	File:	clseBayAppPastEssay.cpp
//
//	Class:	clseBayApp
//
//	Author:	Wen Wen (wwen@ebay.com)
//
//	Function:
//
//	To display a list of past essay topics
//
//
// Modifications:
//				- 09/29/98 wen	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include "clsBulletinBoards.h"

	
//
// ViewEssay
//
void clseBayApp::PastEssay(CEBayISAPIExtension *pThis)
{
	BulletinBoardVector					*pvBoards;
	BulletinBoardVector::iterator		iBoards;

	SetUp();

	//
	// Header
	//
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
					"Viewing "
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Past Essay Topics"
			  <<	"</TITLE>"
					"</HEAD>\n";

	*mpStream	<<	mpMarketPlace->GetHeader()
				<<	"<h2>Past Essay Topics</h2>\n";


	// Get past discussion subjects
	pvBoards	=	mpMarketPlace->GetBulletinBoards()->GetBoardVector();

	for (iBoards = pvBoards->begin();
		 iBoards != pvBoards->end();
		 iBoards++)
	{
		// don't include the board if it's not available, not essay, or postable
		if ((!(*iBoards)->IsAvailable()) || !(*iBoards)->IsEssay() || (*iBoards)->IsPostable())
			continue;

		// print the topic and the description
		*mpStream	<<	"<table width=\"450\" border=\"1\" cellspacing=\"0\" "
					<<	"cellpadding=\"3\">"
					<<	"<tr><td bgcolor=\"#FFFFCC\"><font size=\"5\" face=\"arial,helvetica\">"
					<<	"<strong>Topic: <em>"
					<<	"<a href=\""
					<<	mpMarketPlace->GetCGIPath(PageViewEssay)
					<<	"eBayISAPI.dll?ViewEssay&name="
					<<	(*iBoards)->GetShortName()
					<<	"\">"
					<<	(*iBoards)->GetName()
					<<	"</a></em></strong> </font>\n"
					<<	"<p><font face=\"arial,helvetica\">"
					<<	(*iBoards)->GetDescription()
					<<	"</font></td></tr></table>"
					<<	"<br>\n";
	}

	*mpStream <<	"<p>"
			 <<		mpMarketPlace->GetFooter();

	CleanUp();
	return;
}
